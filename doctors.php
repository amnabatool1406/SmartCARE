<?php
include('config/db.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('includes/header.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Our Specialist Doctors | SmartCARE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-[#F8FAFC]">

    <div class="max-w-7xl mx-auto px-4 py-20">
        <div class="text-center mb-16">
            <h2 class="text-blue-600 font-black uppercase tracking-widest text-sm mb-3">Expert Team</h2>
            <h1 class="text-5xl font-extrabold text-gray-900 italic">Meet Our <span class="text-blue-600">Specialists</span></h1>
            <p class="text-gray-500 mt-4 font-medium">Top-rated doctors dedicated to your health and well-being.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            <?php
            $q = "SELECT d.*, u.full_name, c.city_name 
              FROM doctors d 
              JOIN users u ON d.user_id = u.user_id 
              JOIN cities c ON d.city_id = c.city_id";
            $res = mysqli_query($conn, $q);

            if (mysqli_num_rows($res) > 0) {
                while ($row = mysqli_fetch_assoc($res)) {
                    $degree = !empty($row['qualification']) ? $row['qualification'] : "Specialist Degree";
                    $fee = !empty($row['consultation_fee']) ? $row['consultation_fee'] : "1500";
            ?>

                    <div class="bg-white rounded-[2.5rem] p-8 shadow-xl border border-gray-50 hover:-translate-y-2 transition-all flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start mb-6">
                                <div class="w-20 h-20 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600 text-3xl shadow-inner">
                                    <i class="fa-solid fa-user-md"></i>
                                </div>
                                <span class="bg-green-100 text-green-600 text-[10px] font-black px-3 py-1 rounded-full uppercase">Available</span>
                            </div>

                            <h3 class="text-2xl font-black text-gray-900 mb-1 leading-tight"><?php echo $row['full_name']; ?></h3>
                            <p class="text-blue-600 font-bold text-xs uppercase tracking-widest mb-4 italic"><?php echo $row['specialization']; ?></p>

                            <div class="space-y-3 border-t border-gray-100 pt-5">
                                <div class="flex items-center text-gray-500">
                                    <i class="fa-solid fa-graduation-cap w-6 text-blue-400"></i>
                                    <span class="text-sm font-bold"><?php echo $degree; ?></span>
                                </div>
                                <div class="flex items-center text-gray-500">
                                    <i class="fa-solid fa-location-dot w-6 text-blue-400"></i>
                                    <span class="text-sm font-medium"><?php echo $row['city_name']; ?></span>
                                </div>
                                <div class="flex items-center justify-between mt-6 bg-gray-50 p-4 rounded-2xl">
                                    <span class="text-xs font-black uppercase text-gray-400 tracking-tighter">Consultation Fee</span>
                                    <span class="text-xl font-black text-gray-900">Rs. <?php echo $fee; ?></span>
                                </div>
                            </div>
                        </div>

                        <a href="login.php" class="block w-full text-center mt-8 bg-gray-900 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-blue-600 transition shadow-lg shadow-gray-200">
                            Book Appointment <i class="fa-solid fa-arrow-right ml-2"></i>
                        </a>
                    </div>

            <?php
                }
            } else {
                echo '<div class="col-span-3 text-center py-20 bg-white rounded-[3rem] shadow-sm border border-dashed">
                    <i class="fa-solid fa-user-slash text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-400 font-bold italic">No doctors found in your area.</p>
                  </div>';
            }
            ?>
        </div>
    </div>

    <?php include('includes/footer.php'); ?>
</body>

</html>