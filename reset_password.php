<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('config/db.php');

$message = "";
$error = "";
$token_valid = false;

if (isset($_GET['token'])) {
    $token = mysqli_real_escape_string($conn, $_GET['token']);
    $current_time = date("Y-m-d H:i:s");

    $query = "SELECT * FROM users WHERE reset_token='$token' AND token_expiry > '$current_time'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $token_valid = true;
        $user = mysqli_fetch_assoc($result);
    } else {
        $error = "Invalid or expired token. Please request a new link.";
    }
}

if (isset($_POST['update_password'])) {
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];
    $user_id = $user['user_id'];

    if ($new_pass === $confirm_pass) {
        $hashed_password = password_hash($new_pass, PASSWORD_DEFAULT);
        $update_query = "UPDATE users SET password='$hashed_password', reset_token=NULL, token_expiry=NULL WHERE user_id='$user_id'";

        if (mysqli_query($conn, $update_query)) {
            $message = "Password updated successfully! <a href='login.php' class='font-bold underline text-blue-700'>Login Now</a>";
            $token_valid = false;
        } else {
            $error = "Error updating password.";
        }
    } else {
        $error = "Passwords do not match!";
    }
}

include('includes/header.php');
?>

<div class="min-h-screen flex items-center justify-center px-4 bg-cover bg-center bg-no-repeat bg-fixed"
    style="background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('uploads/Hospital.jpg');">

    <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl p-8 border border-gray-100">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-extrabold text-gray-900">Reset Password</h2>
            <p class="text-gray-500 mt-2">Enter your new secure password.</p>
        </div>

        <?php if ($message != ""): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <?php if ($error != ""): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if ($token_valid): ?>
            <form action="" method="POST" class="space-y-6">
                <div class="relative">
                    <label class="block text-sm font-semibold text-gray-700">New Password</label>
                    <input type="password" id="passwordField" name="new_password" required
                        class="w-full mt-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition">
                    <button type="button" onclick="togglePassword('passwordField', 'eyeIcon1')" class="absolute right-3 top-[38px] text-gray-400 hover:text-blue-600 transition">
                        <i id="eyeIcon1" class="fa-solid fa-eye-slash text-xl"></i>
                    </button>
                </div>

                <div class="relative">
                    <label class="block text-sm font-semibold text-gray-700">Confirm Password</label>
                    <input type="password" id="confirmPasswordField" name="confirm_password" required
                        class="w-full mt-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition">
                    <button type="button" onclick="togglePassword('confirmPasswordField', 'eyeIcon2')" class="absolute right-3 top-[38px] text-gray-400 hover:text-blue-600 transition">
                        <i id="eyeIcon2" class="fa-solid fa-eye-slash text-xl"></i>
                    </button>
                </div>

                <button type="submit" name="update_password"
                    class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition duration-300 transform hover:scale-[1.02] shadow-lg">
                    Update Password
                </button>
            </form>
        <?php endif; ?>
    </div>
</div>

<script>
    function togglePassword(fieldId, iconId) {
        const passwordField = document.getElementById(fieldId);
        const eyeIcon = document.getElementById(iconId);

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