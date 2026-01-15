<?php
include('auth/check_login.php');
checkAccess(3);
include('config/db.php');
include('includes/header.php');

$user_id = $_SESSION['user_id'];

$p_query = mysqli_query($conn, "SELECT patient_id FROM patients WHERE user_id = '$user_id'");
$p_data = mysqli_fetch_assoc($p_query);
$patient_id = $p_data['patient_id'];

$pending_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM appointments WHERE patient_id = '$patient_id' AND status = 'Pending'"))['total'];
$completed_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM appointments WHERE patient_id = '$patient_id' AND status = 'Confirmed'"))['total'];

$query = "SELECT a.*, u.full_name as doc_name, d.specialization 
          FROM appointments a
          JOIN doctors d ON a.doctor_id = d.doctor_id
          JOIN users u ON d.user_id = u.user_id
          WHERE a.patient_id = '$patient_id' 
          ORDER BY a.appointment_date DESC LIMIT 5";
$res = mysqli_query($conn, $query);
?>

<div class="flex min-h-screen bg-[#F8FAFC]">
    <div class="w-80 bg-white border-r-4 border-blue-600 shadow-2xl hidden lg:block">
        <div class="p-8 sticky top-24">
            <div class="bg-blue-50 p-6 rounded-[2rem] mb-10 border border-blue-100">
                <p class="text-xs font-black text-blue-600 uppercase tracking-widest mb-1">Patient Portal</p>
                <h2 class="text-xl font-black text-gray-900"><?php echo $_SESSION['full_name']; ?></h2>
            </div>
            <nav class="space-y-3">
                <a href="patient_dashboard.php" class="flex items-center p-4 bg-blue-600 text-white rounded-2xl font-bold shadow-lg shadow-blue-200 transition-all">
                    <i class="fa-solid fa-house-user mr-4 text-xl"></i> Overview
                </a>
                <a href="search_doctors.php" class="flex items-center p-4 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-2xl font-bold transition-all group">
                    <i class="fa-solid fa-magnifying-glass mr-4 group-hover:rotate-12 transition"></i> Find Doctors
                </a>
                <a href="my_appointments.php" class="flex items-center p-4 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-2xl font-bold transition-all group">
                    <i class="fa-solid fa-calendar-check mr-4 group-hover:scale-110 transition"></i> My Appointments
                </a>
                <div class="my-8 border-t border-gray-100"></div>
                <a href="logout.php" class="flex items-center p-4 text-red-500 hover:bg-red-50 rounded-2xl font-bold transition-all">
                    <i class="fa-solid fa-right-from-bracket mr-4"></i> Logout
                </a>
            </nav>
        </div>
    </div>

    <div class="flex-1 p-8 lg:p-12">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                <div class="bg-white p-8 rounded-[2.5rem] shadow-xl shadow-blue-50 border border-white hover-shadow">
                    <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fa-solid fa-clock-rotate-left text-2xl"></i>
                    </div>
                    <p class="text-gray-500 font-bold text-sm uppercase">Pending Visits</p>
                    <h3 class="text-3xl font-black text-gray-900"><?php echo str_pad($pending_count, 2, "0", STR_PAD_LEFT); ?></h3>
                </div>

                <div class="bg-white p-8 rounded-[2.5rem] shadow-xl shadow-blue-50 border border-white hover-shadow">
                    <div class="w-14 h-14 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fa-solid fa-check-double text-2xl"></i>
                    </div>
                    <p class="text-gray-500 font-bold text-sm uppercase">Completed</p>
                    <h3 class="text-3xl font-black text-gray-900"><?php echo str_pad($completed_count, 2, "0", STR_PAD_LEFT); ?></h3>
                </div>

                <div class="bg-blue-600 p-8 rounded-[2.5rem] shadow-2xl shadow-blue-200 border border-blue-500 text-white relative overflow-hidden group">
                    <i class="fa-solid fa-heart-pulse absolute -right-4 -bottom-4 text-8xl opacity-10 group-hover:scale-110 transition-transform duration-500"></i>
                    <p class="font-bold text-blue-100 text-sm uppercase">Quick Action</p>
                    <h3 class="text-3xl font-black mb-4 tracking-tight">Need Help?</h3>
                    <a href="search_doctors.php" class="inline-block bg-white text-blue-600 px-6 py-2 rounded-xl font-bold text-sm">Book Doctor</a>
                </div>
            </div>

            <div class="bg-white rounded-[3rem] p-10 shadow-2xl shadow-gray-200/50 border border-gray-50 mb-12">
                <div class="flex flex-col md:flex-row justify-between items-center gap-8">
                    <div class="text-center md:text-left">
                        <h2 class="text-4xl font-extrabold text-gray-900 mb-2">Hello, <?php echo explode(' ', $_SESSION['full_name'])[0]; ?>! 👋</h2>
                        <p class="text-gray-500 text-lg font-medium">How are you feeling today? Need a quick checkup?</p>
                    </div>
                    <div class="flex gap-4">
                        <a href="medical_history.php" class="px-8 py-4 bg-gray-900 text-white font-black rounded-2xl hover:bg-blue-600 transition shadow-lg inline-block">
                            VIEW MEDICAL HISTORY
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-xl overflow-hidden">
                <div class="p-8 border-b border-gray-50 flex justify-between items-center">
                    <h3 class="text-2xl font-black text-gray-900">Your Recent Appointments</h3>
                    <a href="my_appointments.php" class="text-blue-600 font-bold text-sm hover:underline">View All &rarr;</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 text-gray-400 text-xs font-black uppercase tracking-widest">
                            <tr>
                                <th class="px-8 py-5">Doctor Name</th>
                                <th class="px-8 py-5">Specialization</th>
                                <th class="px-8 py-5">Date & Time</th>
                                <th class="px-8 py-5">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 font-medium">
                            <?php
                            if (mysqli_num_rows($res) > 0) {
                                while ($row = mysqli_fetch_assoc($res)) {
                                    $status = $row['status'];
                                    $status_class = "bg-yellow-100 text-yellow-700"; // Pending
                                    if ($status == 'Confirmed') $status_class = "bg-green-100 text-green-700";
                                    if ($status == 'Cancelled') $status_class = "bg-red-100 text-red-700";
                            ?>
                                    <tr class="hover:bg-blue-50/30 transition">
                                        <td class="px-8 py-6 text-gray-900 font-bold">Dr. <?php echo $row['doc_name']; ?></td>
                                        <td class="px-8 py-6 text-blue-600 font-bold text-sm"><?php echo $row['specialization']; ?></td>
                                        <td class="px-8 py-6 text-gray-500">
                                            <?php echo date('d M, Y', strtotime($row['appointment_date'])); ?> | <?php echo $row['appointment_time']; ?>
                                        </td>
                                        <td class="px-8 py-6">
                                            <span class="<?php echo $status_class; ?> px-3 py-1 rounded-full text-xs font-black uppercase">
                                                <?php echo $status; ?>
                                            </span>
                                        </td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo "<tr><td colspan='4' class='px-8 py-10 text-center text-gray-400 font-bold'>No appointments found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>