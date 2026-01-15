<?php
include('auth/check_login.php');
include('config/db.php');
include('includes/header.php');
?>

<div class="bg-white min-h-screen">
    <div class="bg-gray-900 py-20 px-8 text-center text-white">
        <h1 class="text-5xl font-black mb-4">SmartCARE <span class="text-blue-500">Newsroom</span></h1>
        <p class="text-gray-400 max-w-2xl mx-auto mb-8">Stay updated with the latest medical breakthroughs and hospital announcements.</p>
    </div>

    <div class="max-w-5xl mx-auto py-16 px-8">
        <div class="space-y-20">
            <?php
            $news = mysqli_query($conn, "SELECT * FROM medical_news ORDER BY news_id DESC");
            while ($n = mysqli_fetch_assoc($news)) {
            ?>
                <article class="flex flex-col md:flex-row gap-10 group">
                    <div class="md:w-1/3 bg-blue-50 rounded-[2rem] flex items-center justify-center overflow-hidden p-1 group-hover:bg-blue-600 transition duration-500">
                        <?php if (!empty($n['news_image']) && file_exists("uploads/" . $n['news_image'])): ?>
                            <img src="uploads/<?php echo $n['news_image']; ?>" class="w-full h-full object-cover rounded-[2rem]">
                        <?php else: ?>
                            <i class="fa-solid fa-newspaper text-6xl text-blue-200 group-hover:text-white transition"></i>
                        <?php endif; ?>
                    </div>

                    <div class="md:w-2/3">
                        <div class="flex items-center gap-4 mb-4">
                            <span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-[10px] font-black uppercase">LATEST UPDATE</span>
                        </div>
                        <h2 class="text-3xl font-black text-gray-900 mb-4 hover:text-blue-600 cursor-pointer transition italic leading-tight">
                            <?php echo $n['title']; ?>
                        </h2>
                        <p class="text-gray-500 leading-relaxed text-lg mb-6 line-clamp-3">
                            <?php echo substr(strip_tags($n['content']), 0, 200); ?>...
                        </p>

                        <form action="news_details.php" method="POST">
                            <input type="hidden" name="news_id" value="<?php echo $n['news_id']; ?>">
                            <button type="submit" class="text-gray-900 font-black border-b-4 border-blue-600 pb-1 hover:text-blue-600 transition uppercase tracking-widest text-sm">
                                READ FULL STORY &rarr;
                            </button>
                        </form>
                    </div>
                </article>
                <hr class="border-gray-100">
            <?php } ?>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>