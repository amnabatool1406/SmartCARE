<?php
include('auth/check_login.php');
checkAccess(2); // Sirf Doctors ke liye
include('config/db.php');
include('includes/header.php');

$u_id = $_SESSION['user_id'];

// Purana data fetch karna taake fields bhari hui nazar aayein
$fetch = mysqli_query($conn, "SELECT qualification, consultation_fee, availability_status FROM doctors WHERE user_id = '$u_id'");
$data = mysqli_fetch_assoc($fetch);

if (isset($_POST['quick_update'])) {
    $qual = mysqli_real_escape_string($conn, $_POST['qualification']);
    $fee = (int)$_POST['consultation_fee'];
    $status = mysqli_real_escape_string($conn, $_POST['availability_status']);

    // Update Query
    $sql = "UPDATE doctors SET 
            qualification = '$qual', 
            consultation_fee = '$fee', 
            availability_status = '$status' 
            WHERE user_id = '$u_id'";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Professional Details Updated!'); window.location.href='update_doctor.php';</script>";
    }
}
?>

<div class="flex-1 p-8 bg-[#F8FAFC]">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-[2.5rem] shadow-2xl overflow-hidden border border-gray-100">
            <div class="bg-blue-600 p-8 text-white">
                <h2 class="text-2xl font-black italic tracking-tighter italic">Update <span class="text-gray-900">Professional Info</span></h2>
                <p class="text-blue-100 text-[10px] font-bold uppercase tracking-widest mt-1">Manage your fees and availability</p>
            </div>

            <form method="POST" class="p-10 space-y-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Your Qualification</label>
                    <input type="text" name="qualification" value="<?= $data['qualification'] ?? '' ?>" placeholder="e.g. MBBS, FCPS" class="w-full p-5 bg-gray-50 border border-gray-100 rounded-2xl font-bold text-gray-700 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Consultation Fee (PKR)</label>
                    <input type="number" name="consultation_fee" value="<?= $data['consultation_fee'] ?? '' ?>" class="w-full p-5 bg-gray-50 border border-gray-100 rounded-2xl font-bold text-gray-700 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Availability Status</label>
                    <select name="availability_status" class="w-full p-5 bg-gray-50 border border-gray-100 rounded-2xl font-bold text-gray-700 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all appearance-none cursor-pointer">
                        <option value="Available" <?= ($data['availability_status'] ?? '') == 'Available' ? 'selected' : '' ?>>Available</option>
                        <option value="Busy" <?= ($data['availability_status'] ?? '') == 'Busy' ? 'selected' : '' ?>>Busy</option>
                        <option value="Away" <?= ($data['availability_status'] ?? '') == 'Away' ? 'selected' : '' ?>>Away</option>
                    </select>
                </div>

                <button type="submit" name="quick_update" class="w-full bg-blue-600 text-white font-black py-5 rounded-2xl shadow-xl hover:bg-blue-700 transition-all active:scale-95 uppercase tracking-widest text-xs">
                    Apply Changes
                </button>
            </form>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>