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
    <title>Our Facilities | SmartCARE</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#F8FAFC]">
    <div class="max-w-7xl mx-auto px-4 py-20">
        <div class="text-center mb-16">
            <h2 class="text-blue-600 font-black uppercase tracking-widest text-sm mb-3">World Class</h2>
            <h1 class="text-5xl font-extrabold text-gray-900 italic">Our <span class="text-blue-600">Facilities</span></h1>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white p-10 rounded-[3rem] shadow-lg border border-gray-50">
                <h3 class="text-2xl font-black mb-4 italic text-blue-600 underline">24/7 Emergency Care</h3>
                <p class="text-gray-500 leading-relaxed">Our emergency department is always open, staffed by specialist trauma doctors and nurses.</p>
            </div>
            <div class="bg-white p-10 rounded-[3rem] shadow-lg border border-gray-100">
                <h3 class="text-2xl font-black mb-4 italic text-blue-600 underline">Digital Lab Reports</h3>
                <p class="text-gray-500 leading-relaxed">Get your medical reports directly in your patient dashboard without any physical hassle.</p>
            </div>
            <div class="bg-white p-10 rounded-[3rem] shadow-lg border border-gray-100">
                <h3 class="text-2xl font-black mb-4 italic text-blue-600 underline">Advanced Diagnostics</h3>
                <p class="text-gray-500 leading-relaxed">Modern MRI, CT Scan, and Ultrasound facilities with AI-driven analysis.</p>
            </div>
            <div class="bg-white p-10 rounded-[3rem] shadow-lg border border-gray-100">
                <h3 class="text-2xl font-black mb-4 italic text-blue-600 underline">Pharmacy Services</h3>
                <p class="text-gray-500 leading-relaxed">Authentic medicines available 24/7 with home delivery options for registered patients.</p>
            </div>
        </div>
    </div>
    <?php include('includes/footer.php'); ?>
</body>

</html>