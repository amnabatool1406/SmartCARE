<?php
include('auth/check_login.php');
checkAccess(1);
include('config/db.php');
include('includes/header.php');

if (!isset($_GET['id'])) {
    header("Location: manage_doctors.php");
    exit();
}
$id = mysqli_real_escape_string($conn, $_GET['id']);

if (isset($_POST['update_doc'])) {
    $spec = mysqli_real_escape_string($conn, $_POST['specialization']);
    $city = mysqli_real_escape_string($conn, $_POST['city_id']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);

    $u_sql = "UPDATE users u 
              JOIN doctors d ON u.user_id = d.user_id 
              SET u.full_name='$full_name', u.phone_number='$phone' 
              WHERE d.doctor_id='$id'";

    $d_sql = "UPDATE doctors SET specialization='$spec', city_id='$city' WHERE doctor_id='$id'";

    if (mysqli_query($conn, $u_sql) && mysqli_query($conn, $d_sql)) {
        echo "<script>alert('Doctor Profile Updated Successfully!'); window.location.href='manage_doctors.php';</script>";
    } else {
        $error = "Update failed: " . mysqli_error($conn);
    }
}

$res = mysqli_query($conn, "SELECT d.*, u.full_name, u.phone_number, u.email 
                            FROM doctors d 
                            JOIN users u ON d.user_id = u.user_id 
                            WHERE d.doctor_id = '$id'");
$doc = mysqli_fetch_assoc($res);

if (!$doc) {
    echo "<div class='text-center py-20 font-bold text-red-500'>Doctor not found!</div>";
    exit();
}
?>

<div class="bg-gray-50 min-h-screen py-12 px-4">
    <div class="max-w-3xl mx-auto bg-white rounded-[3rem] shadow-2xl overflow-hidden border-8 border-white">
        <div class="bg-gray-900 p-10 text-center">
            <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-gray-800 shadow-lg">
                <i class="fa-solid fa-user-md text-white text-3xl"></i>
            </div>
            <h2 class="text-3xl font-black text-white italic tracking-tight">Modify <span class="text-blue-500">Specialist</span></h2>
            <p class="text-gray-400 font-bold text-xs uppercase mt-2 tracking-widest italic opacity-80">
                Editing: <?= $doc['full_name']; ?> (<?= $doc['email']; ?>)
            </p>
        </div>

        <form action="" method="POST" class="p-10 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                <div class="group">
                    <label class="block text-[10px] font-black uppercase text-gray-400 mb-2 ml-2 tracking-widest">Doctor Full Name</label>
                    <input type="text" name="full_name" value="<?= $doc['full_name']; ?>" required
                        class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none font-bold transition-all shadow-sm">
                </div>

                <div class="group">
                    <label class="block text-[10px] font-black uppercase text-gray-400 mb-2 ml-2 tracking-widest">Phone Number</label>
                    <input type="text" name="phone" value="<?= $doc['phone_number']; ?>" required
                        class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none font-bold transition-all shadow-sm">
                </div>

                <div class="group">
                    <label class="block text-[10px] font-black uppercase text-gray-400 mb-2 ml-2 tracking-widest">Specialization</label>
                    <input type="text" name="specialization" value="<?= $doc['specialization']; ?>" required
                        class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none font-bold transition-all shadow-sm">
                </div>

                <div class="group">
                    <label class="block text-[10px] font-black uppercase text-gray-400 mb-2 ml-2 tracking-widest">City Location</label>
                    <select name="city_id" class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none font-bold transition-all shadow-sm">
                        <?php
                        $cities = mysqli_query($conn, "SELECT * FROM cities ORDER BY city_name ASC");
                        while ($c = mysqli_fetch_assoc($cities)) {
                            $sel = ($c['city_id'] == $doc['city_id']) ? "selected" : "";
                            echo "<option value='" . $c['city_id'] . "' $sel>" . $c['city_name'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-4 pt-6 border-t border-gray-50">
                <button type="submit" name="update_doc"
                    class="flex-[2] bg-blue-600 text-white font-black py-5 rounded-2xl hover:bg-gray-900 transition-all duration-300 shadow-xl shadow-blue-200 uppercase tracking-widest text-sm flex items-center justify-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Save Changes
                </button>
                <a href="manage_doctors.php"
                    class="flex-1 bg-gray-100 text-gray-500 text-center font-black py-5 rounded-2xl hover:bg-gray-200 transition-all uppercase tracking-widest text-sm flex items-center justify-center gap-2">
                    <i class="fa-solid fa-xmark"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<?php include('includes/footer.php'); ?>