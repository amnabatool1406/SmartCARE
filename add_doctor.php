<?php
// 1. Namespaces hamesha sabse top par (<?php ke foran baad)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include('auth/check_login.php');
checkAccess(1);
include('config/db.php');
include('includes/header.php');

// 2. PHPMailer files require karein
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if (isset($_POST['add_doctor'])) {
    $name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $raw_password = $_POST['password']; // Email ke liye original password
    $password = password_hash($raw_password, PASSWORD_DEFAULT);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $city_id = $_POST['city_id'];
    $spec = mysqli_real_escape_string($conn, $_POST['specialization']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $exp = mysqli_real_escape_string($conn, $_POST['experience']);
    $fees = mysqli_real_escape_string($conn, $_POST['consultation_fee']);

    // --- Backend Validation ---
    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' OR username='$username'");
    
    if (mysqli_num_rows($check) > 0) {
        $error = "Error: Email or Username already exists!";
    }
    // Strict Email Validation (.com check)
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/\.com$|\.net$|\.org$/i', $email)) {
        $error = "Please enter a valid Doctor email (e.g. dr.name@hospital.com)";
    }
    // Pakistani Phone Validation
    elseif (!preg_match('/^((\+92)|(0092))?3[0-9]{9}$|^03[0-9]{9}$/', $phone)) {
        $error = "Invalid Phone! Use Pakistani format (03xx-xxxxxxx)";
    }
    // Username Space Check
    elseif (preg_match('/\s/', $username)) {
        $error = "Username should not contain spaces!";
    }
    else {
        // Step 1: Insert into Users Table
        $user_sql = "INSERT INTO users (username, email, password, role_id, full_name, address, phone_number) 
                     VALUES ('$username', '$email', '$password', '2', '$name', '$address', '$phone')";

        if (mysqli_query($conn, $user_sql)) {
            $last_id = mysqli_insert_id($conn);
            
            // Step 2: Insert into Doctors Table
            $doc_sql = "INSERT INTO doctors (user_id, city_id, specialization, experience_years, consultation_fee) 
                        VALUES ('$last_id', '$city_id', '$spec', '$exp', '$fees')";

            if (mysqli_query($conn, $doc_sql)) {
                
                // Step 3: Send Email via PHPMailer
                $mail = new PHPMailer(true);
                try {
                  // add_doctor.php mein SMTP wala hissa aise update karein
$mail->isSMTP();
$mail->Host       = 'smtp.gmail.com';
$mail->SMTPAuth   = true;
$mail->Username   = 'info.smartcare.org@gmail.com'; 
$mail->Password   = 'vgft udnc zqwc gcjf'; // Confirm karein ke ye App Password hi hai
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Ye line TLS ko ensure karti hai
$mail->Port       = 587;
$mail->Timeout    = 60; // Server ko thoda zyada waqt den connect hone ke liye

// Authentication settings for Gmail
$mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);

                    $mail->setFrom('info.smartcare.org@gmail.com', 'SmartCARE Admin');
                    $mail->addAddress($email, $name);

                    $mail->isHTML(true);
                    $mail->Subject = 'SmartCARE - Your Doctor Account Credentials';
                    $mail->Body    = "
                        <div style='font-family: Arial, sans-serif; padding: 20px; border: 1px solid #eee;'>
                            <h2 style='color: #2563eb;'>Welcome Dr. $name</h2>
                            <p>Your account has been created by the Admin. Use these details to login:</p>
                            <p><strong>Username:</strong> $username</p>
                            <p><strong>Password:</strong> $raw_password</p>
                            <br>
                            <p>Regards,<br>SmartCARE Administration</p>
                        </div>";

                    $mail->send();
                } catch (Exception $e) {
                    // Agar mail fail ho jaye to redirect nahi rukega
                }

                // Step 4: Success Session and Redirect
                $_SESSION['new_doc'] = [
                    'name' => $name,
                    'username' => $username,
                    'email' => $email,
                    'password' => $raw_password
                ];
                header("Location: doctor_success.php");
                exit();

            } else {
                $error = "Doctor Table Error: " . mysqli_error($conn);
            }
        } else {
            $error = "User Account Error: " . mysqli_error($conn);
        }
    }
}
?>

<div class="bg-gray-50 min-h-screen py-12 px-4">
    <div class="max-w-4xl mx-auto bg-white rounded-[3rem] shadow-2xl overflow-hidden border-8 border-white">
        <div class="bg-gray-900 p-10 text-center">
            <h2 class="text-3xl font-black text-white italic">Add New <span class="text-blue-500">Specialist</span></h2>
            <?php if (isset($error)): ?>
                <p class="bg-red-500/10 text-red-500 p-3 rounded-xl font-bold mt-4 border border-red-500/20 text-sm italic">
                    <i class="fa-solid fa-triangle-exclamation mr-2"></i> <?php echo $error; ?>
                </p>
            <?php endif; ?>
        </div>

        <form action="" method="POST" class="p-10 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-black uppercase text-gray-400 mb-2">Unique Username</label>
                    <input type="text" name="username" required placeholder="e.g. hassan_doc" class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none font-bold">
                </div>

                <div>
                    <label class="block text-xs font-black uppercase text-gray-400 mb-2">Email Address</label>
                    <input type="email" name="email" required placeholder="doctor@example.com" class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none font-bold">
                </div>

                <div>
                    <label class="block text-xs font-black uppercase text-gray-400 mb-2">Full Name</label>
                    <input type="text" name="full_name" required class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none font-bold">
                </div>

                <div class="relative">
                    <label class="block text-xs font-black uppercase text-gray-400 mb-2">Initial Password</label>
                    <input type="password" id="passwordField" name="password" required class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none font-bold">
                    <button type="button" onclick="togglePassword()" class="absolute right-4 top-[42px] text-gray-400 hover:text-blue-600">
                        <i id="eyeIcon" class="fa-solid fa-eye-slash text-xl"></i>
                    </button>
                </div>

                <div>
                    <label class="block text-xs font-black uppercase text-gray-400 mb-2">Specialization</label>
                    <input type="text" name="specialization" required placeholder="e.g. Cardiologist" class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none font-bold">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black uppercase text-gray-400 mb-2">Experience (Yrs)</label>
                        <input type="number" name="experience" required class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none font-bold">
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase text-gray-400 mb-2">Fees (PKR)</label>
                        <input type="number" name="consultation_fee" required class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none font-bold">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-black uppercase text-gray-400 mb-2">Phone Number</label>
                    <input type="text" name="phone" required placeholder="03xxxxxxxxx" class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none font-bold">
                </div>

                <div>
                    <label class="block text-xs font-black uppercase text-gray-400 mb-2">City</label>
                    <select name="city_id" required class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none font-bold">
                        <option value="">Select City</option>
                        <?php
                        $city_query = mysqli_query($conn, "SELECT * FROM cities ORDER BY city_name ASC");
                        while ($city = mysqli_fetch_assoc($city_query)) {
                            echo "<option value='" . $city['city_id'] . "'>" . $city['city_name'] . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-black uppercase text-gray-400 mb-2">Clinic Address</label>
                    <textarea name="address" required rows="2" class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none font-bold"></textarea>
                </div>
            </div>

            <button type="submit" name="add_doctor" class="w-full bg-blue-600 text-white font-black py-5 rounded-2xl hover:bg-gray-900 transition shadow-xl uppercase tracking-widest mt-4">
                Register Doctor Account
            </button>
        </form>
    </div>
</div>

<script>
    function togglePassword() {
        const passwordField = document.getElementById('passwordField');
        const eyeIcon = document.getElementById('eyeIcon');
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            eyeIcon.classList.replace('fa-eye-slash', 'fa-eye');
        } else {
            passwordField.type = 'password';
            eyeIcon.classList.replace('fa-eye', 'fa-eye-slash');
        }
    }
</script>

<?php include('includes/footer.php'); ?>