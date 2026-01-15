<?php


include('auth/check_login.php');
checkAccess(2);
include('config/db.php');
include('includes/header.php');

$u_id = $_SESSION['user_id'];

$doc_res = mysqli_query($conn, "SELECT doctor_id FROM doctors WHERE user_id = '$u_id'");
$doc_row = mysqli_fetch_assoc($doc_res);
$doctor_id = $doc_row['doctor_id'] ?? 0;

if (isset($_POST['update_status'])) {
    $app_id = $_POST['appointment_id'];
    $new_status = $_POST['status'];

    $update_sql = "UPDATE appointments SET status = '$new_status' WHERE appointment_id = '$app_id' AND doctor_id = '$doctor_id'";
    if (mysqli_query($conn, $update_sql)) {
        echo "<script>alert('Status Updated!'); window.location.href='manage_appointments.php';</script>";
    }
}

$query = "SELECT a.*, u.full_name 
        FROM appointments a 
        LEFT JOIN users u ON a.patient_id = u.user_id 
        WHERE a.doctor_id = '$doctor_id' 
        AND a.status = 'Admin Approved' 
        ORDER BY a.appointment_date ASC";

$appointments = mysqli_query($conn, $query);
?>

<div class="flex-1 p-8 lg:p-12 bg-[#F8FAFC]">
    <div class="max-w-6xl mx-auto">

        <div class="bg-gray-900 p-8 rounded-t-[3rem] shadow-2xl text-white">
            <h2 class="text-2xl font-black italic">Manage <span class="text-blue-500">Bookings</span></h2>
            <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mt-1">Update Patient Appointment Status</p>
        </div>

        <div class="bg-white rounded-b-[3rem] shadow-2xl border border-gray-100 overflow-hidden">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="p-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Patient Details</th>
                        <th class="p-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Schedule</th>
                        <th class="p-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                        <th class="p-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php while ($row = mysqli_fetch_assoc($appointments)): ?>
                        <tr class="hover:bg-blue-50/30 transition-all">
                            <td class="p-6">
                                <div class="font-black text-gray-700"><?= htmlspecialchars($row['patient_name']) ?></div>
                                <div class="text-[9px] text-gray-400 font-bold">Patient ID: <?= $row['patient_id'] ?></div>
                            </td>
                            <td class="p-6 text-sm font-bold text-gray-600">
                                <?= date('d M, Y', strtotime($row['appointment_date'])) ?><br>
                                <span class="text-blue-500 italic text-xs"><?= date('h:i A', strtotime($row['appointment_time'])) ?></span>
                            </td>
                            <td class="p-6">
                                <?php
                                $s = $row['status'] ?? 'Pending';
                                $clr = ($s == 'Confirmed') ? 'text-green-600 bg-green-50' : (($s == 'Cancelled') ? 'text-red-600 bg-red-50' : 'text-yellow-600 bg-yellow-50');
                                ?>
                                <span class="<?= $clr ?> px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-tighter">
                                    <?= $s ?>
                                </span>
                            </td>
                            <td class="p-6 text-right">
                                <form method="POST" class="inline-flex gap-2">
                                    <input type="hidden" name="appointment_id" value="<?= $row['appointment_id'] ?>">
                                    <button name="status" value="Confirmed" class="bg-green-600 text-white p-2 rounded-xl hover:shadow-lg transition-all active:scale-90" title="Confirm">
                                        <i class="fa-solid fa-check text-xs"></i>
                                    </button>
                                    <button name="status" value="Cancelled" class="bg-red-500 text-white p-2 rounded-xl hover:shadow-lg transition-all active:scale-90" title="Cancel">
                                        <i class="fa-solid fa-xmark text-xs"></i>
                                    </button>
                                    <input type="hidden" name="update_status" value="1">
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>