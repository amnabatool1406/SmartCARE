<?php
include('auth/check_login.php');
include('config/db.php');
include('includes/header.php');
?>

<div class="bg-gray-50 min-h-screen p-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-10">
            <h1 class="text-4xl font-black text-gray-900 italic">Health <span class="text-blue-600">Library</span></h1>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <?php
            $res = mysqli_query($conn, "SELECT * FROM medical_info ORDER BY info_id DESC");
            while ($row = mysqli_fetch_assoc($res)) {
            ?>
                <div class="bg-white rounded-[2.5rem] shadow-xl border border-gray-100 overflow-hidden flex flex-col">
                    <div class="h-48 bg-gray-200 relative">
                        <?php if (!empty($row['info_image'])): ?>
                            <img src="uploads/<?php echo $row['info_image']; ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="flex items-center justify-center h-full text-gray-400 font-bold italic">No Image Available</div>
                        <?php endif; ?>
                    </div>

                    <div class="p-8 relative">
                        <h3 class="text-2xl font-black text-gray-900 mb-4"><?php echo $row['disease_name']; ?></h3>

                        <div class="space-y-4">
                            <div>
                                <span class="text-[10px] font-black text-blue-600 uppercase tracking-widest block mb-1">Prevention</span>
                                <p class="text-gray-500 text-sm leading-relaxed"><?php echo $row['prevention']; ?></p>
                            </div>
                            <div>
                                <span class="text-[10px] font-black text-green-600 uppercase tracking-widest block mb-1">Cure / Treatment</span>
                                <p class="text-gray-700 font-medium text-sm"><?php echo $row['cure']; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>