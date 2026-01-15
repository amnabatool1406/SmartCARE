<?php
include('auth/check_login.php');
checkAccess(3);
include('config/db.php');
include('includes/header.php');

if (isset($_GET['doc_id'])) {
    $doc_id = mysqli_real_escape_string($conn, $_GET['doc_id']);

    $query = "SELECT u.full_name, d.*, c.city_name 
              FROM users u 
              JOIN doctors d ON u.user_id = d.user_id 
              JOIN cities c ON d.city_id = c.city_id 
              WHERE d.doctor_id = '$doc_id'";

    $res = mysqli_query($conn, $query);
    if (mysqli_num_rows($res) > 0) {
        $doctor = mysqli_fetch_assoc($res);
    } else {
        echo "<script>alert('Doctor not found!'); window.location.href='search_doctors.php';</script>";
        exit;
    }
} else {
    header("Location: search_doctors.php");
    exit;
}

if (isset($_POST['book_now'])) {
    $user_id = $_SESSION['user_id'];
    $app_date = $_POST['app_date'];
    $app_time = $_POST['app_time'];

    $p_query = mysqli_query($conn, "SELECT patient_id FROM patients WHERE user_id = '$user_id'");
    $p_data = mysqli_fetch_assoc($p_query);

    if ($p_data) {
        $patient_id = $p_data['patient_id'];

        $sql = "INSERT INTO appointments (doctor_id, patient_id, appointment_date, appointment_time, status) 
                VALUES ('$doc_id', '$patient_id', '$app_date', '$app_time', 'Pending')";

        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Appointment Requested!'); window.location.href='patient_dashboard.php';</script>";
        }
    } else {
        echo "<script>alert('Error: Patient record not found. Please complete your profile.');</script>";
    }
}
?>

<div class="bg-gray-50 min-h-screen py-12 px-4">
    <div class="max-w-4xl mx-auto">
        <a href="search_doctors.php" class="inline-flex items-center text-blue-600 font-bold mb-8 hover:translate-x-[-5px] transition-transform">
            <i class="fa-solid fa-arrow-left mr-2"></i> Back to Search
        </a>

        <div class="bg-white rounded-[3rem] shadow-2xl overflow-hidden flex flex-col md:flex-row border border-gray-100">
            <div class="md:w-1/3 bg-blue-600 p-10 text-white">
                <div class="text-center">
                    <div class="w-24 h-24 bg-white/20 rounded-[2rem] flex items-center justify-center mx-auto mb-6 backdrop-blur-md">
                        <i class="fa-solid fa-user-doctor text-4xl"></i>
                    </div>
                    <h2 class="text-2xl font-black mb-1">Dr. <?php echo $doctor['full_name']; ?></h2>
                    <p class="text-blue-100 font-bold text-sm uppercase tracking-widest mb-6"><?php echo $doctor['specialization']; ?></p>

                    <div class="space-y-4 text-left border-t border-white/10 pt-6">
                        <div class="flex items-center text-sm">
                            <i class="fa-solid fa-location-dot w-8 opacity-70"></i>
                            <span><?php echo $doctor['city_name']; ?></span>
                        </div>
                        <div class="flex items-center text-sm">
                            <i class="fa-solid fa-award w-8 opacity-70"></i>
                            <span><?php echo $doctor['experience_years']; ?>+ Years Exp.</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="md:w-2/3 p-10 lg:p-14">
                <h3 class="text-3xl font-black text-gray-900 mb-2">Book Your Slot</h3>
                <p class="text-gray-500 mb-10 font-medium">Please fill in the details for your visit.</p>

                <?php if (isset($error)) {
                    echo "<p class='text-red-500 mb-4 font-bold'>$error</p>";
                } ?>

                <form action="" method="POST" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-black text-gray-700 mb-2 uppercase tracking-wide">Preferred Date</label>
                            <input type="date" name="app_date" required min="<?php echo date('Y-m-d'); ?>"
                                class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 outline-none font-bold text-gray-700 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-black text-gray-700 mb-2 uppercase tracking-wide">Preferred Time</label>
                            <select name="app_time" required class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 outline-none font-bold text-gray-700 transition">
                                <option value="10:00 AM">10:00 AM</option>
                                <option value="11:00 AM">11:00 AM</option>
                                <option value="12:00 PM">12:00 PM</option>
                                <option value="05:00 PM">05:00 PM</option>
                                <option value="06:00 PM">06:00 PM</option>
                                <option value="07:00 PM">07:00 PM</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-black text-gray-700 mb-2 uppercase tracking-wide">Disease Description / Symptoms</label>
                        <textarea name="disease" rows="4" required placeholder="Describe your health issue briefly..."
                            class="w-full p-5 bg-gray-50 border border-gray-100 rounded-[2rem] focus:ring-4 focus:ring-blue-500/10 outline-none font-medium text-gray-700 transition"></textarea>
                    </div>

                    <button type="submit" name="book_now" class="w-full bg-blue-600 text-white py-5 rounded-2xl font-black text-lg shadow-xl shadow-blue-100 hover:bg-gray-900 transition-all transform active:scale-95">
                        CONFIRM APPOINTMENT <i class="fa-solid fa-calendar-check ml-2"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>