<?php
include('auth/check_login.php');
checkAccess(3);
include('config/db.php');
include('includes/header.php');

$user_id = $_SESSION['user_id'];

$p_query = mysqli_query($conn, "SELECT patient_id FROM patients WHERE user_id = '$user_id'");
$p_data = mysqli_fetch_assoc($p_query);
$patient_id = $p_data['patient_id'];

$sql = "SELECT a.*, u.full_name as doctor_name, d.specialization 
        FROM appointments a
        JOIN doctors d ON a.doctor_id = d.doctor_id
        JOIN users u ON d.user_id = u.user_id
        WHERE a.patient_id = '$patient_id'
        ORDER BY a.appointment_date DESC";

$result = mysqli_query($conn, $sql);
?>

<div class="bg-gray-50 min-h-screen py-10 px-4">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-10">
            <h2 class="text-3xl font-black text-gray-900">My <span class="text-blue-600">Appointments</span></h2>
            <a href="search_doctors.php" class="bg-blue-600 text-white px-6 py-3 rounded-2xl font-bold shadow-lg hover:bg-gray-900 transition">
                + Book New
            </a>
        </div>

        <div class="grid grid-cols-1 gap-6">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-gray-100 flex flex-col md:flex-row items-center justify-between hover:shadow-md transition">

                        <div class="flex items-center space-x-6 mb-4 md:mb-0">
                            <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center">
                                <i class="fa-solid fa-user-doctor text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-gray-900">Dr. <?php echo $row['doctor_name']; ?></h3>
                                <p class="text-gray-500 font-bold text-sm uppercase tracking-tight"><?php echo $row['specialization']; ?></p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-8 text-center md:text-left">
                            <div>
                                <p class="text-xs text-gray-400 font-black uppercase mb-1">Date</p>
                                <p class="font-bold text-gray-700"><i class="fa-regular fa-calendar text-blue-500 mr-2"></i> <?php echo date('d M, Y', strtotime($row['appointment_date'])); ?></p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-black uppercase mb-1">Time Slot</p>
                                <p class="font-bold text-gray-700"><i class="fa-regular fa-clock text-blue-500 mr-2"></i> <?php echo $row['appointment_time']; ?></p>
                            </div>
                        </div>

                        <div class="mt-4 md:mt-0">
                            <?php
                            $status = $row['status']; //
                            $class = "bg-yellow-100 text-yellow-700"; // Pending
                            if ($status == 'Confirmed') $class = "bg-green-100 text-green-700";
                            if ($status == 'Cancelled') $class = "bg-red-100 text-red-700";
                            ?>
                            <span class="px-6 py-2 rounded-full font-black text-sm <?php echo $class; ?>">
                                <?php echo strtoupper($status); ?>
                            </span>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="text-center bg-white p-20 rounded-[3rem] border-4 border-dashed border-gray-100">
                    <i class="fa-solid fa-calendar-xmark text-6xl text-gray-200 mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-400">No appointments found yet.</h3>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>