<?php
include('config/db.php');
session_start();
if (isset($_SESSION['role_id'])) {
    if ($_SESSION['role_id'] == 1) header("Location: admin_dashboard.php");
    elseif ($_SESSION['role_id'] == 2) header("Location: doctor_dashboard.php");
    elseif ($_SESSION['role_id'] == 3) header("Location: patient_dashboard.php");
    exit();
}

$dashboard_link = "";
if (isset($_SESSION['role_id'])) {
    if ($_SESSION['role_id'] == 1) $dashboard_link = "admin/dashboard.php";
    elseif ($_SESSION['role_id'] == 2) $dashboard_link = "doctor/dashboard.php";
    elseif ($_SESSION['role_id'] == 3) $dashboard_link = "patient/dashboard.php";
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
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        html {
            scroll-behavior: smooth;
        }

        .hover-shadow {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .hover-shadow:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(37, 99, 235, 0.15);
        }

     
    </style>
</head>

<body class="bg-[#F8FAFC]">

   <?php
    include('includes/header.php');
    ?>

    <header class="relative bg-white pt-16 pb-32 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 lg:flex items-center">
            <div class="lg:w-1/2 relative z-10 text-center lg:text-left">
                <h1 class="text-5xl lg:text-7xl font-extrabold text-gray-900 leading-[1.1] mb-8">
                    Your Health, Our <br> <span class="text-blue-600">Commitment</span>
                </h1>
                <p class="text-lg text-gray-500 mb-10 max-w-lg mx-auto lg:mx-0 leading-relaxed">
                    Skip the waiting room. Book appointments with top specialists in your city within seconds. High-quality care is now just a click away.
                </p>
                <div class="flex flex-col sm:flex-row justify-center lg:justify-start gap-4">
                    <a href="register.php" class="bg-blue-600 text-white px-10 py-5 rounded-2xl font-black text-lg shadow-2xl shadow-blue-200 hover:bg-blue-700 hover:-translate-y-1 transition duration-300">
                        GET STARTED NOW <i class="fa-solid fa-arrow-right ml-2"></i>
                    </a>
                    <div class="flex items-center px-6 py-4 bg-gray-50 rounded-2xl border border-gray-100">
                        <span class="flex -space-x-2">
                            <img class="w-10 h-10 rounded-full border-2 border-white" src="https://i.pravatar.cc/100?u=1" alt="user">
                            <img class="w-10 h-10 rounded-full border-2 border-white" src="https://i.pravatar.cc/100?u=2" alt="user">
                            <img class="w-10 h-10 rounded-full border-2 border-white" src="https://i.pravatar.cc/100?u=3" alt="user">
                        </span>
                        <span class="ml-3 text-sm font-bold text-gray-600">Joined by 10k+ Patients</span>
                    </div>
                </div>
            </div>
            <div class="lg:w-1/2 mt-16 lg:mt-0 relative">
                <div class="bg-blue-600/5 absolute inset-0 rounded-[3rem] rotate-3 scale-105"></div>
                <img src="https://images.unsplash.com/photo-1579684385127-1ef15d508118?auto=format&fit=crop&q=80&w=1000"
                    class="rounded-[2.5rem] shadow-2xl relative z-10 border-4 border-white object-cover h-[500px] w-full" alt="Medical Team">
            </div>
        </div>
    </header>

    <section id="info" class="py-24">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16">
                <div>
                    <h2 class="text-blue-600 font-black uppercase tracking-widest text-sm mb-3">Stay Informed</h2>
                    <h3 class="text-4xl font-extrabold text-gray-900">Medical News & Health Tips</h3>
                </div>
                <a href="medical_news.php" class="mt-4 md:mt-0 text-blue-600 font-bold hover:underline">View All News &rarr;</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php
                $info_query = mysqli_query($conn, "SELECT * FROM medical_info LIMIT 3");
                if (mysqli_num_rows($info_query) > 0) {
                    while ($info = mysqli_fetch_assoc($info_query)) {
                ?>
                        <div class="hover-shadow bg-white p-10 rounded-[2.5rem] border border-gray-100 relative group">
                            <div class="absolute top-0 right-10 bg-blue-600 text-white p-4 rounded-b-2xl shadow-lg group-hover:bg-gray-900 transition-colors">
                                <i class="fa-solid fa-file-medical text-xl"></i>
                            </div>
                            <h4 class="text-2xl font-bold text-gray-900 mb-4"><?php echo $info['disease_name']; ?></h4>
                            <p class="text-gray-500 leading-relaxed mb-8">
                                <?php echo substr($info['prevention'], 0, 150); ?>...
                            </p>
                            <div class="pt-6 border-t border-gray-50">
                                <span class="text-blue-600 font-extrabold cursor-pointer hover:text-blue-800">LEARN MORE</span>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo "<div class='col-span-3 text-center py-20 bg-white rounded-3xl shadow-inner border border-dashed border-gray-300 text-gray-400 font-medium'>No medical updates available at the moment.</div>";
                }
                ?>
            </div>
        </div>
    </section>

    <?php include('includes/footer.php'); ?>