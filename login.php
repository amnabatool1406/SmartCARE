<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('config/db.php');

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $db_pass = $user['password'];
        $is_correct = false;

        if (password_verify($password, $db_pass)) {
            $is_correct = true;
        } elseif ($password === $db_pass) {
            $is_correct = true;
            $new_hash = password_hash($password, PASSWORD_DEFAULT);
            mysqli_query($conn, "UPDATE users SET password='$new_hash' WHERE user_id='" . $user['user_id'] . "'");
        }

        if ($is_correct) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role_id'] = $user['role_id'];
            $_SESSION['full_name'] = $user['full_name'];

            if ($user['role_id'] == 1) {
                header("Location: admin_dashboard.php");
                exit();
            } elseif ($user['role_id'] == 2) {
                header("Location: doctor_dashboard.php");
                exit();
            } elseif ($user['role_id'] == 3) {
                header("Location: patient_dashboard.php");
                exit();
            }
        } else {
            $error = "Invalid Password!";
        }
    } else {
        $error = "Email not found!";
    }
}
include('includes/header.php');
?>
<div class="min-h-screen flex items-center justify-center px-4 bg-cover bg-center bg-no-repeat bg-fixed"
    style="background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('uploads/Hospital.jpg');">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl p-8 border border-gray-100">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-extrabold text-gray-900">Welcome Back</h2>
            <p class="text-gray-500 mt-2">Please enter your details to login</p>
        </div>

        <?php if (isset($error)) {
            echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4'>$error</div>";
        } ?>

        <form action="" method="POST" class="space-y-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700">Email Address</label>
                <input type="email" name="email" required class="w-full mt-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition">
            </div>

            <div class="relative">
                <label class="block text-sm font-semibold text-gray-700">Password</label>
                <input type="password" id="passwordField" name="password" required class="w-full mt-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition">

                <button type="button" onclick="togglePassword()" class="absolute right-3 top-[38px] text-gray-400 hover:text-blue-600 transition">
                    <i id="eyeIcon" class="fa-solid fa-eye-slash text-xl"></i>
                </button>
            </div>

            <div class="flex justify-end mt-2">
                <a href="forgot_password.php" class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition">
                    Forgot Password?
                </a>
            </div>

            <button type="submit" name="login" class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition duration-300 transform hover:scale-[1.02] shadow-lg">
                Sign In
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
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        } else {
            passwordField.type = 'password';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        }
    }
</script>

<?php include('includes/footer.php'); ?>