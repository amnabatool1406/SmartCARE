<?php
include('auth/check_login.php');
checkAccess(1);
include('config/db.php');
include('includes/header.php');

$news_id = "";
$title = "";
$content = "";
$current_image = "";

if (isset($_GET['id'])) {
    $news_id = mysqli_real_escape_string($conn, $_GET['id']);
    $res = mysqli_query($conn, "SELECT * FROM medical_news WHERE news_id = '$news_id'");

    if (mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        $title = $row['title'];
        $content = $row['content'];
        $current_image = $row['news_image'];
    } else {
        echo "<script>alert('News not found!'); window.location='admin_dashboard.php';</script>";
        exit;
    }
}

if (isset($_POST['update_news'])) {
    $id = $_POST['news_id'];
    $u_title = mysqli_real_escape_string($conn, $_POST['title']);
    $u_content = mysqli_real_escape_string($conn, $_POST['content']);

    if ($_FILES['news_image']['name'] != "") {
        $new_img = time() . "_" . $_FILES['news_image']['name'];
        move_uploaded_file($_FILES['news_image']['tmp_name'], "uploads/" . $new_img);

        if ($current_image != "" && file_exists("uploads/" . $current_image)) {
            unlink("uploads/" . $current_image);
        }

        mysqli_query($conn, "UPDATE medical_news SET title='$u_title', content='$u_content', news_image='$new_img' WHERE news_id='$id'");
    } else {
        mysqli_query($conn, "UPDATE medical_news SET title='$u_title', content='$u_content' WHERE news_id='$id'");
    }
    header("Location: admin_dashboard.php?status=updated");
}
?>

<div class="flex min-h-screen bg-[#F8FAFC] justify-center items-center p-6">
    <div class="bg-white p-10 rounded-[3rem] shadow-2xl border border-gray-100 w-full max-w-2xl">
        <header class="mb-8">
            <h1 class="text-3xl font-black text-gray-900">Edit <span class="text-blue-600">News Post</span></h1>
        </header>

        <form action="" method="POST" enctype="multipart/form-data" class="space-y-6">
            <input type="hidden" name="news_id" value="<?php echo $news_id; ?>">

            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 ml-4">News Title</label>
                <input type="text" name="title" value="<?php echo $title; ?>" required
                    class="w-full p-5 bg-gray-50 rounded-2xl border-none focus:ring-2 focus:ring-blue-500 font-bold text-gray-800">
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 ml-4">News Content</label>
                <textarea name="content" required
                    class="w-full p-5 bg-gray-50 rounded-2xl border-none focus:ring-2 focus:ring-blue-500 font-medium text-gray-600 h-48"><?php echo $content; ?></textarea>
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 ml-4">Update Image (Optional)</label>
                <div class="p-5 bg-blue-50/50 border-2 border-dashed border-blue-100 rounded-2xl flex items-center justify-between">
                    <input type="file" name="news_image" class="text-sm font-bold text-blue-600">
                    <?php if ($current_image) { ?>
                        <img src="uploads/<?php echo $current_image; ?>" class="w-12 h-12 rounded-lg object-cover">
                    <?php } ?>
                </div>
            </div>

            <div class="flex gap-4 pt-4">
                <button type="submit" name="update_news"
                    class="flex-1 bg-gray-900 text-white py-5 rounded-2xl font-black hover:bg-blue-600 transition shadow-lg uppercase tracking-widest">
                    Save Changes
                </button>
                <a href="admin_dashboard.php"
                    class="flex-1 bg-gray-100 text-gray-500 py-5 rounded-2xl font-black hover:bg-gray-200 transition text-center uppercase tracking-widest">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<?php include('includes/footer.php'); ?>