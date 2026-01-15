<?php
include('auth/check_login.php');
checkAccess(1);
include('config/db.php');
include('includes/header.php');

if (!isset($_GET['id'])) {
    header("Location: admin_dashboard.php");
    exit();
}

$id = $_GET['id'];
$res = mysqli_query($conn, "SELECT * FROM medical_info WHERE info_id = '$id'");
$data = mysqli_fetch_assoc($res);

if (isset($_POST['update_info'])) {
    $d_name = mysqli_real_escape_string($conn, $_POST['disease_name']);
    $prev = mysqli_real_escape_string($conn, $_POST['prevention']);
    $cure = mysqli_real_escape_string($conn, $_POST['cure']);

    if ($_FILES['info_image']['name'] != "") {
        if (!empty($data['info_image']) && file_exists("uploads/" . $data['info_image'])) {
            unlink("uploads/" . $data['info_image']);
        }

        $new_img = time() . "_" . $_FILES['info_image']['name'];
        move_uploaded_file($_FILES['info_image']['tmp_name'], "uploads/" . $new_img);

        mysqli_query($conn, "UPDATE medical_info SET disease_name='$d_name', prevention='$prev', cure='$cure', info_image='$new_img' WHERE info_id = '$id'");
    } else {
        mysqli_query($conn, "UPDATE medical_info SET disease_name='$d_name', prevention='$prev', cure='$cure' WHERE info_id = '$id'");
    }

    header("Location: admin_dashboard.php?status=updated");
}
?>

<div class="bg-gray-50 min-h-screen p-8">
    <div class="max-w-3xl mx-auto">
        <a href="admin_dashboard.php" class="inline-flex items-center text-sm font-bold text-gray-400 hover:text-blue-600 mb-8 transition">
            <i class="fa-solid fa-arrow-left mr-2"></i> BACK TO DASHBOARD
        </a>

        <div class="bg-white rounded-[3rem] shadow-2xl shadow-gray-200 border border-gray-100 overflow-hidden">
            <div class="p-10 border-b border-gray-50 bg-gray-900 text-white">
                <h2 class="text-3xl font-black italic">Edit <span class="text-blue-400">Health Info</span></h2>
                <p class="text-gray-400 text-sm mt-2">Update disease details and medical guidance.</p>
            </div>

            <form action="" method="POST" enctype="multipart/form-data" class="p-10 space-y-6">
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 ml-2">Disease Name</label>
                    <input type="text" name="disease_name" value="<?php echo $data['disease_name']; ?>" required
                        class="w-full p-4 bg-gray-50 rounded-2xl border-none focus:ring-2 focus:ring-blue-500 font-bold text-gray-700">
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 ml-2">Prevention Steps</label>
                    <textarea name="prevention" required
                        class="w-full p-4 bg-gray-50 rounded-2xl border-none focus:ring-2 focus:ring-blue-500 font-medium text-gray-600 h-32"><?php echo $data['prevention']; ?></textarea>
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 ml-2">Treatment / Cure</label>
                    <textarea name="cure" required
                        class="w-full p-4 bg-gray-50 rounded-2xl border-none focus:ring-2 focus:ring-blue-500 font-medium text-gray-600 h-32"><?php echo $data['cure']; ?></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center p-6 bg-blue-50 rounded-[2rem]">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-blue-600 mb-2">Cover Image</label>
                        <input type="file" name="info_image" class="text-xs font-bold text-gray-500">
                        <p class="text-[9px] text-blue-400 mt-2 font-medium">Leave empty to keep current image.</p>
                    </div>

                    <div class="flex justify-center">
                        <?php if (!empty($data['info_image'])): ?>
                            <div class="text-center">
                                <img src="uploads/<?php echo $data['info_image']; ?>" class="w-24 h-24 rounded-2xl object-cover shadow-lg border-2 border-white">
                                <span class="text-[9px] font-black text-blue-600 uppercase mt-2 block">Current Photo</span>
                            </div>
                        <?php else: ?>
                            <div class="w-24 h-24 rounded-2xl bg-gray-200 flex items-center justify-center text-gray-400">
                                <i class="fa-solid fa-image text-2xl"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <button type="submit" name="update_info"
                    class="w-full bg-blue-600 text-white py-5 rounded-2xl font-black hover:bg-gray-900 transition-all uppercase tracking-[0.2em] shadow-xl shadow-blue-100">
                    Update Medical Database
                </button>
            </form>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>