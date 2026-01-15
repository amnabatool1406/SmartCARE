<?php
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include('config/db.php');
include('includes/header.php');

if (isset($_POST['register'])) {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = $email;
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $blood_group = $_POST['blood_group'];
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $role_id = 3;

    $checkEmail = "SELECT email FROM users WHERE email='$email'";
    $res = mysqli_query($conn, $checkEmail);

    if (mysqli_num_rows($res) > 0) {
        $msg = "This email is already registered!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/\.com$|\.net$|\.org$/i', $email)) {
        $msg = "Please enter a valid email (e.g., user@gmail.com)";
    } elseif (!preg_match('/^((\+92)|(0092))?3[0-9]{9}$|^03[0-9]{9}$/', $phone)) {
        $msg = "Invalid Pakistani phone format! Use 03xx-xxxxxxx";
    } else {
        $user_sql = "INSERT INTO users (username, email, password, role_id, full_name, address, phone_number) 
                     VALUES ('$username', '$email', '$password', '$role_id', '$full_name', '$address', '$phone')";

        if (mysqli_query($conn, $user_sql)) {
            $last_id = mysqli_insert_id($conn);
            $pat_sql = "INSERT INTO patients (user_id, dob, blood_group, gender) 
                        VALUES ('$last_id', '$dob', '$blood_group', '$gender')";

            if (mysqli_query($conn, $pat_sql)) {

                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'info.smartcare.org@gmail.com';
                    $mail->Password   = 'vgft udnc zqwc gcjf';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = 587;

                    $mail->setFrom('info.smartcare.org@gmail.com', 'SmartCARE Hospital');
                    $mail->addAddress($email, $full_name);

                    $mail->isHTML(true);
                    $mail->Subject = 'Registration Successful - SmartCARE';
                    $mail->Body    = "
                    <div style='background-color: #f8fafc; padding: 40px; font-family: sans-serif;'>
                        <div style='max-width: 600px; margin: 0 auto; background: white; border-radius: 20px; overflow: hidden; border: 1px solid #e2e8f0;'>
                            <div style='background-color: #2563eb; padding: 30px; text-align: center; color: white;'>
                                <h1 style='margin: 0;'>SmartCARE</h1>
                            </div>
                            <div style='padding: 30px;'>
                                <h2 style='color: #1e293b;'>Welcome, $full_name!</h2>
                                <p style='line-height: 1.6; font-size: 16px;'>
                    Thank you for choosing <b>SmartCARE Hospital</b>. Your account has been successfully created, and you now have access to our digital healthcare services.</p>
                                <div style='background: #f1f5f9; padding: 20px; border-radius: 12px; margin: 20px 0;'>
                                    <p style='margin: 0; font-size: 12px; color: #94a3b8; font-weight: bold;'>LOGIN EMAIL</p>
                                    <p style='margin: 0; font-size: 16px; color: #1e293b; font-weight: bold;'>$email</p>
                                </div>
                                <div style='text-align: center; margin-top: 30px;'>
                                    <a href='http://localhost/SmartCARE/login.php' style='background: #2563eb; color: white; padding: 15px 25px; text-decoration: none; border-radius: 50px; font-weight: bold; display: inline-block;'>Login to Dashboard</a>
                                </div>
                            </div>
                        </div>
                    </div>";

                    $mail->send();
                } catch (Exception $e) {
                }

                echo "<script>alert('Account Created & Confirmation Email Sent!'); window.location.href='login.php';</script>";
            } else {
                $msg = "Error in patients table: " . mysqli_error($conn);
            }
        } else {
            $msg = "Error in users table: " . mysqli_error($conn);
        }
    }
}
?>
<div class="min-h-screen flex items-center justify-center px-4 bg-cover bg-center bg-no-repeat bg-fixed"
    style="background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('uploads/Hospital.jpg');">
<div class="max-w-6xl mx-auto my-12 px-4">
    <div class="bg-white shadow-2xl rounded-[3rem] overflow-hidden flex flex-col md:flex-row border border-gray-100 min-h-[700px]">
        <div class="md:w-1/3 bg-blue-600 p-12 text-white flex flex-col justify-between relative overflow-hidden">
            <div class="relative z-10">
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-8 backdrop-blur-md">
                    <i class="fa-solid fa-notes-medical text-3xl text-white"></i>
                </div>
                <h2 class="text-4xl font-black leading-tight mb-6 italic">Join <br><span class="text-blue-200 uppercase">SmartCARE</span></h2>
                <p class="text-blue-100 text-lg font-medium leading-relaxed">Your health data, safe and accessible. Book appointments and consult with top specialists in minutes.</p>
            </div>

            <div class="bg-white/10 p-6 rounded-[2rem] backdrop-blur-sm border border-white/10 relative z-10">
                <p class="text-xs font-black uppercase tracking-[0.2em] mb-2 text-blue-200">System Status</p>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                    <span class="font-bold text-sm">Secure Registration Active</span>
                </div>
            </div>

            <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-blue-500 rounded-full opacity-50"></div>
        </div>

        <div class="md:w-2/3 p-10 lg:p-16 bg-[#F8FAFC]">
            <div class="mb-10">
                <h3 class="text-4xl font-black text-gray-900 mt-4 italic">Create <span class="text-blue-600">Account</span></h3>
                <p class="text-gray-500 mt-2 font-bold italic underline decoration-blue-200 underline-offset-4">Enter your medical details to get started.</p>
                <p class="text-gray-500 mt-2 font-medium">Already have an account? <a href="login.php" class="text-blue-600 hover:underline">Sign In</a></p>

            </div>

            <?php if (isset($msg)) { ?>
                <div class="bg-red-500 text-white p-4 mb-8 rounded-2xl font-bold shadow-lg shadow-red-100 flex items-center gap-3">
                    <i class="fa-solid fa-circle-exclamation"></i> <?php echo $msg; ?>
                </div>
            <?php } ?>

            <form action="" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Full Name</label>
                    <input type="text" name="full_name" required placeholder="Sana Sufiyan" class="w-full p-4 bg-white border-2 border-gray-100 rounded-2xl outline-none focus:border-blue-500 font-bold transition-all">
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Email Address</label>
                    <input type="email" name="email" required placeholder="sana@example.com" class="w-full p-4 bg-white border-2 border-gray-100 rounded-2xl outline-none focus:border-blue-500 font-bold transition-all">
                </div>

                <div class="space-y-2 relative">
                    <label class="block text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Password</label>
                    <div class="relative">
                        <input type="password" id="regPassword" name="password" required placeholder="••••••••" class="w-full p-4 bg-white border-2 border-gray-100 rounded-2xl outline-none focus:border-blue-500 font-bold transition-all">
                        <button type="button" onclick="toggleRegPassword()" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-blue-600 transition">
                            <i id="eyeIcon" class="fa-solid fa-eye-slash"></i>
                        </button>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Contact Number</label>
                    <input type="text" name="phone" required placeholder=" 03001234567" class="w-full p-4 bg-white border-2 border-gray-100 rounded-2xl outline-none focus:border-blue-500 font-bold transition-all">
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Date of Birth</label>
                    <input type="date" name="dob" required class="w-full p-4 bg-white border-2 border-gray-100 rounded-2xl outline-none focus:border-blue-500 font-bold transition-all">
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Blood Group</label>
                    <select name="blood_group" required class="w-full p-4 bg-white border-2 border-gray-100 rounded-2xl outline-none focus:border-blue-500 font-bold transition-all">
                        <option value="">Select Group</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                    </select>
                </div>

                <div class="md:col-span-2 space-y-2">
                    <label class="block text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Gender</label>
                    <div class="flex gap-4">
                        <label class="flex-1">
                            <input type="radio" name="gender" value="Male" class="hidden peer" checked>
                            <div class="text-center p-4 border-2 border-gray-100 rounded-2xl cursor-pointer peer-checked:bg-blue-600 peer-checked:border-blue-600 peer-checked:text-white transition-all font-black uppercase text-xs tracking-widest">Male</div>
                        </label>
                        <label class="flex-1">
                            <input type="radio" name="gender" value="Female" class="hidden peer">
                            <div class="text-center p-4 border-2 border-gray-100 rounded-2xl cursor-pointer peer-checked:bg-blue-600 peer-checked:border-blue-600 peer-checked:text-white transition-all font-black uppercase text-xs tracking-widest">Female</div>
                        </label>
                    </div>
                </div>

                <div class="md:col-span-2 space-y-2">
                    <label class="block text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Home Address</label>
                    <textarea name="address" required rows="2" placeholder="Street, City, Country" class="w-full p-4 bg-white border-2 border-gray-100 rounded-2xl outline-none focus:border-blue-500 font-bold transition-all"></textarea>
                </div>

                <div class="md:col-span-2 pt-4">
                    <button type="submit" name="register" class="w-full bg-blue-600 text-white font-black py-5 rounded-[2rem] hover:bg-gray-900 transition-all duration-300 shadow-2xl shadow-blue-200 transform active:scale-95 uppercase tracking-[0.2em] text-xs">
                        Create My Profile <i class="fa-solid fa-arrow-right ml-2"></i>
                    </button>
                    <p class="text-center mt-6 text-gray-400 text-xs font-bold">By registering, you agree to our Terms of Service</p>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
<script>
    function toggleRegPassword() {
        const passField = document.getElementById('regPassword');
        const eyeIcon = document.getElementById('eyeIcon');
        if (passField.type === 'password') {
            passField.type = 'text';
            eyeIcon.classList.replace('fa-eye-slash', 'fa-eye');
        } else {
            passField.type = 'password';
            eyeIcon.classList.replace('fa-eye', 'fa-eye-slash');
        }
    }
</script>

<?php include('includes/footer.php'); ?>