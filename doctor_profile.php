<?php
include('auth/check_login.php');
checkAccess(2);
include('config/db.php');
include('includes/header.php');

$u_id = $_SESSION['user_id'];

$check_query = "SELECT d.*, u.full_name FROM doctors d 
                RIGHT JOIN users u ON d.user_id = u.user_id 
                WHERE u.user_id = '$u_id'";
$res = mysqli_query($conn, $check_query);
$data = mysqli_fetch_assoc($res);

if (isset($_POST['save_doc'])) {
    $spec = mysqli_real_escape_string($conn, $_POST['specialization'] ?? '');
    $qual = mysqli_real_escape_string($conn, $_POST['qualification'] ?? '');
    $d_name = mysqli_real_escape_string($conn, $_POST['doc_name'] ?? '');
    $d_email = mysqli_real_escape_string($conn, $_POST['doctor_email'] ?? '');
    $d_phone = mysqli_real_escape_string($conn, $_POST['doctor_phone'] ?? '');
    $exp = (int)($_POST['experience_years'] ?? 0);
    $fee = (int)($_POST['consultation_fee'] ?? 0);
    $status = mysqli_real_escape_string($conn, $_POST['availability_status'] ?? 'Available');
    $city = (int)$_POST['city_id'];

    $verify = mysqli_query($conn, "SELECT * FROM doctors WHERE user_id = '$u_id'");

    if (mysqli_num_rows($verify) > 0) {
        $sql = "UPDATE doctors SET 
                specialization = '$spec', qualification = '$qual', doc_name = '$d_name',
                doctor_email = '$d_email', doctor_phone = '$d_phone', experience_years = '$exp', 
                consultation_fee = '$fee', availability_status = '$status', city_id = '$city' 
                WHERE user_id = '$u_id'";
    } else {
        $sql = "INSERT INTO doctors (user_id, specialization, qualification, doc_name, doctor_email, doctor_phone, experience_years, consultation_fee, availability_status, city_id) 
                VALUES ('$u_id', '$spec', '$qual', '$d_name', '$d_email', '$d_phone', '$exp', '$fee', '$status', '$city')";
    }

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Profile Saved Successfully!'); window.location.href='doctor_dashboard.php';</script>";
        exit();
    } else {
        echo "<div class='bg-red-600 text-white p-4 mb-4 rounded-xl'>Database Error: " . mysqli_error($conn) . "</div>";
    }
}
?>

<div class="flex-1 p-8 lg:p-12 bg-[#F8FAFC]">
    <div class="max-w-4xl mx-auto bg-white rounded-[3rem] shadow-2xl border border-gray-100 overflow-hidden">

        <div class="bg-gray-900 p-8 text-white">
            <h2 class="text-2xl font-black italic">Professional <span class="text-blue-500">Settings</span></h2>
            <p class="text-gray-400 text-[10px] font-bold uppercase tracking-[0.2em] mt-1">
                Dr. <?php echo htmlspecialchars($data['doc_name'] ?? $data['full_name'] ?? 'New Profile'); ?>
            </p>
        </div>

        <form action="" method="POST" class="p-10 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Full Display Name</label>
                    <input type="text" name="doc_name" value="<?= htmlspecialchars($data['doc_name'] ?? '') ?>" placeholder="e.g. Dr. Hassan Ali" class="w-full p-5 bg-gray-50 border border-gray-100 rounded-[1.5rem] font-bold text-gray-700 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all placeholder:text-gray-300">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Specialization</label>
                    <input type="text" name="specialization" value="<?= htmlspecialchars($data['specialization'] ?? '') ?>" placeholder="e.g. Cardiologist" class="w-full p-5 bg-gray-50 border border-gray-100 rounded-[1.5rem] font-bold text-gray-700 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all placeholder:text-gray-300">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Qualification</label>
                    <input type="text" name="qualification" value="<?= htmlspecialchars($data['qualification'] ?? '') ?>" placeholder="e.g. MBBS, FCPS" class="w-full p-5 bg-gray-50 border border-gray-100 rounded-[1.5rem] font-bold text-gray-700 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all placeholder:text-gray-300">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Experience (Years)</label>
                    <input type="number" name="experience_years" value="<?= $data['experience_years'] ?? 0 ?>" class="w-full p-5 bg-gray-50 border border-gray-100 rounded-[1.5rem] font-bold text-gray-700 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Consultation Fee (PKR)</label>
                    <input type="number" name="consultation_fee" value="<?= $data['consultation_fee'] ?? 0 ?>" class="w-full p-5 bg-gray-50 border border-gray-100 rounded-[1.5rem] font-bold text-gray-700 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Current Status</label>
                    <select name="availability_status" class="w-full p-5 bg-gray-50 border border-gray-100 rounded-[1.5rem] font-bold text-gray-700 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all appearance-none cursor-pointer">
                        <?php $s = $data['availability_status'] ?? 'Available'; ?>
                        <option value="Available" <?= $s == 'Available' ? 'selected' : '' ?>>Available</option>
                        <option value="On Leave" <?= $s == 'On Leave' ? 'selected' : '' ?>>On Leave</option>
                        <option value="Busy" <?= $s == 'Busy' ? 'selected' : '' ?>>Busy</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Contact Email</label>
                    <input type="email" name="doctor_email" value="<?= htmlspecialchars($data['doctor_email'] ?? '') ?>" placeholder="doctor@example.com" class="w-full p-5 bg-gray-50 border border-gray-100 rounded-[1.5rem] font-bold text-gray-700 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all placeholder:text-gray-300">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Phone Number</label>
                    <input type="text" name="doctor_phone" value="<?= htmlspecialchars($data['doctor_phone'] ?? '') ?>" placeholder="e.g. 03001234567" class="w-full p-5 bg-gray-50 border border-gray-100 rounded-[1.5rem] font-bold text-gray-700 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all placeholder:text-gray-300">
                </div>

                <div class="md:col-span-2 space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Select City</label>
                    <select name="city_id" class="w-full p-5 bg-gray-50 border border-gray-100 rounded-[1.5rem] font-bold text-gray-700 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all appearance-none cursor-pointer">
                        <?php
                        $cities = mysqli_query($conn, "SELECT * FROM cities");
                        while ($c = mysqli_fetch_assoc($cities)) {
                            $selected = ($c['city_id'] == ($data['city_id'] ?? 0)) ? "selected" : "";
                            echo "<option value='" . $c['city_id'] . "' $selected>" . $c['city_name'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <button type="submit" name="save_doc" class="w-full bg-blue-600 text-white font-black py-6 rounded-[1.5rem] hover:bg-gray-900 shadow-2xl shadow-blue-200 transition-all transform active:scale-95 uppercase tracking-[0.3em] text-sm">
                Save Professional Profile
            </button>
        </form>
    </div>
</div>