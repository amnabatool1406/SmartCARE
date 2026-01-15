<?php
include('auth/check_login.php');
checkAccess(2);
include('config/db.php');
include('includes/header.php');

$u_id = $_SESSION['user_id'];

if (isset($_POST['update_slots'])) {
    $slots = mysqli_real_escape_string($conn, $_POST['availability_text']);
    mysqli_query($conn, "UPDATE doctors SET availability_info = '$slots' WHERE user_id = '$u_id'");
    echo "<script>alert('Availability Schedule Updated!');</script>";
}

$res = mysqli_query($conn, "SELECT availability_info FROM doctors WHERE user_id = '$u_id'");
$doc = mysqli_fetch_assoc($res);
?>

<div class="flex min-h-screen bg-[#F8FAFC]">
    <div class="max-w-4xl mx-auto py-12 w-full px-6">
        <div class="bg-white rounded-[2.5rem] shadow-xl p-10 border border-gray-100">
            <h1 class="text-3xl font-black text-gray-900 mb-2">Manage <span class="text-blue-600">Availability</span></h1>
            <p class="text-gray-400 font-bold text-sm mb-10 uppercase tracking-widest">Set your weekly or monthly visiting hours</p>

            <form action="" method="POST">
                <div class="mb-8">
                    <label class="block text-xs font-black text-gray-400 uppercase mb-4">Availability Schedule (Details)</label>
                    <textarea name="availability_text" rows="6" placeholder="Example: Mon-Fri: 10:00 AM - 04:00 PM, Sat: Closed"
                        class="w-full p-6 bg-gray-50 border border-gray-100 rounded-[2rem] focus:ring-4 focus:ring-blue-500/10 outline-none font-bold text-gray-700 h-64"><?php echo $doc['availability_info']; ?></textarea>
                </div>

                <button type="submit" name="update_slots" class="w-full bg-blue-600 text-white font-black py-5 rounded-2xl hover:bg-gray-900 shadow-xl shadow-blue-100 transition-all transform active:scale-95 uppercase">
                    SAVE SCHEDULE <i class="fa-solid fa-clock ml-2"></i>
                </button>
            </form>
        </div>
    </div>
</div>