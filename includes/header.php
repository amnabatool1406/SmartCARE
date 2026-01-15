<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Home link hamesha index.php rahegi
$home_link = "index.php"; 

// Dashboard link ke liye alag variable
$db_path = ""; 
if (isset($_SESSION['role_id'])) {
    if ($_SESSION['role_id'] == 1) $db_path = "admin_dashboard.php";
    elseif ($_SESSION['role_id'] == 2) $db_path = "doctor_dashboard.php";
    elseif ($_SESSION['role_id'] == 3) $db_path = "patient_dashboard.php";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartCARE | Professional Medical Services</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .nav-link { position: relative; transition: all 0.3s; color: #4b5563; font-weight: 700; }
        .nav-link::after { content: ''; position: absolute; width: 0; height: 2px; bottom: -4px; left: 0; background-color: #2563eb; transition: width 0.3s; }
        .nav-link:hover::after { width: 100%; }
        .nav-link:hover { color: #2563eb; }
    </style>
</head>
<body class="bg-[#F8FAFC]">

    <nav class="bg-white sticky top-0 z-50 shadow-md border-b-4 border-blue-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <a href="index.php" class="flex items-center group">
                        <div class="bg-blue-600 p-2 rounded-xl group-hover:rotate-6 transition-transform">
                            <i class="fa-solid fa-house-medical text-white text-xl"></i>
                        </div>
                        <span class="ml-3 font-extrabold text-2xl tracking-tighter text-gray-900">
                            Smart<span class="text-blue-600 uppercase">Care</span>
                        </span>
                    </a>
                </div>

                <div class="hidden md:flex items-center space-x-8">
                    <a href="index.php" class="nav-link">Home</a>
                    <a href="about_us.php" class="nav-link">About Us</a>
                    <a href="medical_news.php" class="nav-link">Medical News</a>
                    <a href="medical_info.php" class="nav-link">Medical Info</a>
                    <a href="facilities.php" class="nav-link">Facilities</a>
                    <a href="contact.php" class="nav-link">Contact</a>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="flex items-center space-x-6 border-l pl-8 border-gray-200">
                            <a href="<?= $db_path ?>" class="bg-blue-600 text-white px-6 py-2.5 rounded-xl font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200 flex items-center">
                                <i class="fa-solid fa-user-circle mr-2 text-lg"></i> Dashboard
                            </a>
                            <a href="logout.php" class="text-red-500 hover:text-red-700 font-extrabold flex items-center transition">
                                <i class="fa-solid fa-power-off mr-1"></i> Logout
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="flex items-center space-x-6 border-l pl-8 border-gray-200">
                            <a href="login.php" class="text-gray-900 font-extrabold hover:text-blue-600 transition">Login</a>
                            <a href="register.php" class="bg-blue-600 text-white px-7 py-3 rounded-xl font-extrabold hover:bg-blue-700 transition shadow-xl shadow-blue-100">
                                Register
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>