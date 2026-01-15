<?php
include('config/db.php');
include('includes/header.php');

$id = isset($_POST['news_id']) ? mysqli_real_escape_string($conn, $_POST['news_id']) : 0;

if ($id == 0) {
    echo "<script>window.location.href='medical_news.php';</script>";
    exit;
}

$news_query = mysqli_query($conn, "SELECT * FROM medical_news WHERE news_id = '$id'");
$news = mysqli_fetch_assoc($news_query);
?>

<div class="max-w-4xl mx-auto py-12 px-8">
    <a href="medical_news.php" class="text-blue-600 font-black text-xs uppercase tracking-widest hover:text-gray-900 transition">
        &larr; Back to Newsroom
    </a>

    <h1 class="text-5xl font-black text-gray-900 my-8 italic leading-tight">
        <?php echo $news['title']; ?>
    </h1>

    <div class="w-full h-[450px] bg-blue-50 rounded-[3rem] overflow-hidden mb-12 shadow-2xl shadow-blue-100 border-4 border-white">
        <?php if (!empty($news['news_image']) && file_exists("uploads/" . $news['news_image'])): ?>
            <img src="uploads/<?php echo $news['news_image']; ?>" class="w-full h-full object-cover">
        <?php else: ?>
            <div class="flex items-center justify-center h-full text-blue-100 text-9xl">
                <i class="fa-solid fa-newspaper"></i>
            </div>
        <?php endif; ?>
    </div>

    <div class="text-gray-700 leading-relaxed font-medium text-xl whitespace-pre-line">
        <?php
        echo htmlspecialchars($news['content']);
        ?>
    </div>

    <div class="mt-16 pt-8 border-t border-gray-100 text-gray-400 text-sm font-bold italic">
        SmartCARE Verified Medical News &bull; 2026
    </div>
</div>

<?php include('includes/footer.php'); ?>