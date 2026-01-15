<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('config/db.php');

$message = "";
$error = "";

if (isset($_POST['reset_request'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Check if email exists
    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        
        // Generate Token and Expiry (1 hour from now)
        $token = bin2hex(random_bytes(32));
        $expiry = date("Y-m-d H:i:s", strtotime('+1 hour'));

        // Update Database with token
        $update_query = "UPDATE users SET reset_token='$token', token_expiry='$expiry' WHERE email='$email'";
        
        if (mysqli_query($conn, $update_query)) {
            // Note: Asli project mein yahan email send karne ka function aayega.
            // Filhal demo ke liye hum link screen par dikha rahe hain.
            $reset_link = "reset_password.php?token=" . $token;
            $message = "A reset link has been generated. <br> <a href='$reset_link' class='font-bold underline text-blue-700'>Click here to reset your password</a>";
        } else {
            $error = "Something went wrong. Please try again.";
        }
    } else {
        $error = "No account found with this email address.";
    }
}
include('includes/header.php');
?>

<div class="min-h-screen flex items-center justify-center px-4 bg-cover bg-center bg-no-repeat bg-fixed"
    style="background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('uploads/Hospital.jpg');">
    
    <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl p-8 border border-gray-100">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-extrabold text-gray-900">Forgot Password?</h2>
            <p class="text-gray-500 mt-2">Enter your email to receive a password reset link.</p>
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

        <form action="" method="POST" class="space-y-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700">Email Address</label>
                <input type="email" name="email" required 
                    class="w-full mt-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition"
                    placeholder="example@gmail.com">
            </div>

            <button type="submit" name="reset_request" 
                class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition duration-300 transform hover:scale-[1.02] shadow-lg">
                Send Reset Link
            </button>

            <div class="text-center mt-4">
                <a href="login.php" class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition">
                    <i class="fa-solid fa-arrow-left mr-2"></i>Back to Login
                </a>
            </div>
        </form>
    </div>
</div>

<?php include('includes/footer.php'); ?>