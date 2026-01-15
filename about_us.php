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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | SmartCARE</title>
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
            <h2 class="text-blue-600 font-black uppercase tracking-widest text-sm mb-3">Who We Are</h2>
            <h1 class="text-5xl font-extrabold text-gray-900 italic">About <span class="text-blue-600">CARE Group</span></h1>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-center mb-20">
            <div class="relative">
                <img src="https://images.unsplash.com/photo-1516549655169-df83a0774514?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80"
                    alt="Medical Team"
                    class="rounded-[3rem] shadow-2xl border-8 border-white">
                <div class="absolute -bottom-6 -right-6 bg-blue-600 text-white p-8 rounded-[2rem] shadow-xl hidden lg:block">
                    <p class="text-3xl font-black italic">20+</p>
                    <p class="text-xs font-bold uppercase tracking-widest">Years Experience</p>
                </div>
            </div>

            <div>
                <h3 class="text-3xl font-bold text-gray-900 mb-6">Our Journey & Mission</h3>
                <p class="text-gray-500 leading-relaxed mb-6 text-lg">
                    CARE Group has been providing medical services for a long time. Recognizing the challenges in healthcare, we developed this centralized platform to eliminate long queues and reduce manual appointment handling.
                </p>
                <p class="text-gray-500 leading-relaxed text-lg italic border-l-4 border-blue-600 pl-4 mb-8">
                    "Our mission is to provide easy access to medical services and maintain secure records for doctors and patients alike."
                </p>

                <div class="grid grid-cols-2 gap-4">
                    <a href="doctors.php" class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center gap-4 hover:border-blue-500 transition-all group">
                        <i class="fa-solid fa-user-doctor text-blue-600 text-2xl group-hover:scale-110 transition-transform"></i>
                        <span class="font-bold text-gray-800">Expert Doctors</span>
                    </a>
                    <a href="facilities.php" class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center gap-4 hover:border-blue-500 transition-all group">
                        <i class="fa-solid fa-hospital text-blue-600 text-2xl group-hover:scale-110 transition-transform"></i>
                        <span class="font-bold text-gray-800">Top Facilities</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-blue-600 rounded-[3rem] overflow-hidden mb-20 shadow-2xl">
            <div class="grid grid-cols-1 md:grid-cols-2 items-center">
                <div class="p-12 lg:p-20 text-white">
                    <h3 class="text-3xl font-black mb-6 italic uppercase tracking-tighter">Global Standards of Care</h3>
                    <p class="text-blue-100 leading-relaxed text-lg mb-8">
                        We are not just local; our systems and protocols are designed according to international medical standards. We connect you with specialists who have global exposure and use world-class technology.
                    </p>
                    <div class="flex gap-8">
                        <div>
                            <p class="text-3xl font-black">50+</p>
                            <p class="text-xs uppercase font-bold opacity-70">Cities Covered</p>
                        </div>
                        <div class="border-l border-blue-400 pl-8">
                            <p class="text-3xl font-black">100%</p>
                            <p class="text-xs uppercase font-bold opacity-70">Secure Data</p>
                        </div>
                    </div>
                </div>
                <div class="h-full min-h-[300px]">
                    <img src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80"
                        alt="Global Medical Technology"
                        class="w-full h-full object-cover opacity-90 hover:opacity-100 transition-opacity">
                </div>
            </div>
        </div>

        <div class="text-center mb-12">
            <h2 class="text-3xl font-black text-gray-900">Why Patients Trust <span class="text-blue-600">CARE</span></h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-20">
            <div class="bg-white rounded-[2.5rem] overflow-hidden shadow-lg border border-gray-50 group hover:-translate-y-2 transition-transform">
                <img src="uploads/Global Specialists.jpg" alt="Specialist" class="w-full h-48 object-cover">
                <div class="p-8">
                    <h4 class="font-black text-xl mb-2 italic">Global Specialists</h4>
                    <p class="text-gray-500 text-sm">Access to world-class doctors from every field of medicine.</p>
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] overflow-hidden shadow-lg border border-gray-50 group hover:-translate-y-2 transition-transform">
                <img src="https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="Technology" class="w-full h-48 object-cover">
                <div class="p-8">
                    <h4 class="font-black text-xl mb-2 italic">Modern Tech</h4>
                    <p class="text-gray-500 text-sm">Using the latest digital tools to manage your health reports safely.</p>
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] overflow-hidden shadow-lg border border-gray-50 group hover:-translate-y-2 transition-transform">
                <img src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="Care" class="w-full h-48 object-cover">
                <div class="p-8">
                    <h4 class="font-black text-xl mb-2 italic">Patient First</h4>
                    <p class="text-gray-500 text-sm">We prioritize your comfort and time above everything else.</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center bg-gray-900 rounded-[3rem] p-12 text-white shadow-2xl shadow-blue-200">
            <div>
                <h2 class="text-4xl font-black text-blue-500 mb-2">10k+</h2>
                <p class="font-bold uppercase tracking-widest text-xs opacity-70">Happy Patients</p>
            </div>
            <div>
                <h2 class="text-4xl font-black text-blue-500 mb-2">500+</h2>
                <p class="font-bold uppercase tracking-widest text-xs opacity-70">Specialist Doctors</p>
            </div>
            <div>
                <h2 class="text-4xl font-black text-blue-500 mb-2">24/7</h2>
                <p class="font-bold uppercase tracking-widest text-xs opacity-70">Online Support</p>
            </div>
        </div>
    </div>

    <?php include('includes/footer.php'); ?>

</body>

</html>