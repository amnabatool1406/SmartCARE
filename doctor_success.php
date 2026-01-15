<?php
include('auth/check_login.php');
checkAccess(1);
include('includes/header.php');

if (!isset($_SESSION['new_doc'])) {
    header("Location: manage_doctors.php");
    exit();
}
$doc = $_SESSION['new_doc'];
unset($_SESSION['new_doc']);
?>

<div class="max-w-xl mx-auto my-20 px-4">
    <div class="bg-white rounded-[3rem] shadow-2xl border-t-8 border-blue-600 overflow-hidden">
        <div class="p-10 text-center">
            <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6 text-4xl">
                <i class="fa-solid fa-id-card"></i>
            </div>
            <h2 class="text-3xl font-black text-gray-900 italic">Doctor <span class="text-blue-600">Account Created!</span></h2>
            <p class="text-gray-500 mt-2 font-bold italic">Share these login details with the doctor</p>
        </div>

        <div class="bg-gray-50 p-8 mx-10 mb-10 rounded-[2rem] border-2 border-dashed border-gray-200">
            <div class="space-y-4">
                <div class="flex justify-between border-b pb-2">
                    <span class="text-xs font-black uppercase text-gray-400">Username</span>
                    <span class="font-bold text-gray-800"><?= $doc['username'] ?></span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="text-xs font-black uppercase text-gray-400">Login Email</span>
                    <span class="font-bold text-blue-600"><?= $doc['email'] ?></span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="text-xs font-black uppercase text-gray-400">Password</span>
                    <span class="font-black text-red-500 italic"><?= $doc['password'] ?></span>
                </div>
            </div>
        </div>

        <div class="px-10 pb-10 flex gap-4">
            <button onclick="window.print()" class="flex-1 bg-gray-900 text-white font-black py-4 rounded-2xl hover:bg-blue-600 transition shadow-lg uppercase text-xs tracking-widest">
                Print Credentials
            </button>
            <a href="manage_doctors.php" class="flex-1 bg-blue-100 text-blue-600 text-center font-black py-4 rounded-2xl hover:bg-blue-200 transition uppercase text-xs tracking-widest leading-[3rem]">
                Back to List
            </a>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>