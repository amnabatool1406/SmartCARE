<?php
include('auth/check_login.php');
checkAccess(2);
include('config/db.php');
include('includes/header.php');




use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$doc_user_id = $_SESSION['user_id'];

$doc_res = mysqli_query($conn, "SELECT doctor_id FROM doctors WHERE user_id = '$doc_user_id'");
$doc_data = mysqli_fetch_assoc($doc_res);
$doctor_id = ($doc_data) ? $doc_data['doctor_id'] : 0;

$total_app = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM appointments WHERE doctor_id = '$doctor_id'"))['total'];
$pending_app = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM appointments WHERE doctor_id = '$doctor_id' AND status = 'Pending'"))['total'];

if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $action = $_GET['action'];
    $new_status = ($action == 'approve') ? 'Confirmed' : 'Cancelled';

    // Status Update
    $update_query = "UPDATE appointments SET status = '$new_status' WHERE appointment_id = '$id' AND doctor_id = '$doctor_id'";

    if (mysqli_query($conn, $update_query) && $new_status == 'Confirmed') {

        // Patient ki details nikalna
        $mail_query = "SELECT u.email, u.full_name, a.appointment_date, a.appointment_time 
                       FROM appointments a 
                       JOIN users u ON a.patient_id = u.user_id 
                       WHERE a.appointment_id = '$id'";
        $res = mysqli_query($conn, $mail_query);
        $data = mysqli_fetch_assoc($res);

        if ($data) {
            $mail = new PHPMailer(true);
            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com'; // Apna SMTP host likhein
                $mail->SMTPAuth   = true;
                $mail->Username   = 'info.smartcare.org@gmail.com'; // Apna email
                $mail->Password   = 'vgft udnc zqwc gcjf'; // Gmail App Password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                // Recipients
                $mail->setFrom('info.smartcare.org@gmail.com', 'SmartCare Hospital');
                $mail->addAddress($data['email'], $data['full_name']);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Appointment Confirmed - SmartCare';
                $mail->Body    = "<h3>Hello " . $data['full_name'] . ",</h3>
                                  <p>Your appointment has been <b>Confirmed</b> by the doctor.</p>
                                  <p><b>Date:</b> " . $data['appointment_date'] . "<br>
                                  <b>Time:</b> " . $data['appointment_time'] . "</p>
                                  <p>Please arrive 15 minutes early.</p>";

                $mail->send();
            } catch (Exception $e) {
            }
        }
    }
    echo "<script>window.location.href='doctor_dashboard.php';</script>";
    exit();
}
?>

<div class="flex min-h-screen bg-[#F8FAFC]">
    <div class="w-80 bg-white border-r-4 border-blue-600 shadow-2xl hidden lg:flex flex-col h-screen sticky top-0">
        <div class="p-8 sticky top-0 bg-white z-10">
            <div class="bg-blue-600 p-6 rounded-[2rem] text-white shadow-xl shadow-blue-100">
                <p class="text-xs font-bold opacity-80 uppercase tracking-widest mb-1">
                    <?php
                    if ($_SESSION['role_id'] == 1) echo "Admin Portal";
                    elseif ($_SESSION['role_id'] == 2) echo "Doctor Portal";
                    else echo "Patient Portal";
                    ?>
                </p>
                <h2 class="text-xl font-black italic">
                    <?php echo explode(' ', $_SESSION['full_name'])[0]; ?>
                </h2>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto px-6 space-y-2 custom-scrollbar">
            <?php if ($_SESSION['role_id'] == 1): ?>
                <a href="admin_dashboard.php" class="flex items-center gap-4 p-4 text-blue-600 bg-blue-50 rounded-2xl font-bold">
                    <i class="fa-solid fa-chart-pie"></i> Analytics
                </a>
                <a href="manage_doctors.php" class="flex items-center gap-4 p-4 text-gray-400 hover:bg-gray-50 hover:text-blue-600 rounded-2xl transition font-bold">
                    <i class="fa-solid fa-user-doctor"></i> Manage Doctors
                </a>
            <?php elseif ($_SESSION['role_id'] == 2): ?>
                <a href="doctor_dashboard.php" class="flex items-center gap-4 p-4 text-blue-600 bg-blue-50 rounded-2xl font-bold">
                    <i class="fa-solid fa-calendar-check"></i> Appointments
                </a>
                <a href="doctor_profile.php" class="flex items-center gap-4 p-4 text-gray-400 hover:bg-gray-50 hover:text-blue-600 rounded-2xl transition font-bold">
                    <i class="fa-solid fa-user-gear"></i> Profile Settings
                </a>
                 <a href="update_doctor.php" class="flex items-center gap-4 p-4 text-gray-400 hover:bg-gray-50 hover:text-blue-600 rounded-2xl transition font-bold">
                    <i class="fa-solid fa-user-gear"></i> Update Profile 
                </a>
            <?php endif; ?>
        </div>

        <div class="p-6 border-t border-gray-100">
            <a href="logout.php" class="flex items-center gap-4 p-4 text-red-500 hover:bg-red-50 rounded-2xl transition font-black uppercase text-xs tracking-widest">
                <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-power-off"></i>
                </div>
                Logout System
            </a>
        </div>
    </div>

    <div class="flex-1 p-8 lg:p-12">
        <div class="max-w-6xl mx-auto">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                <div class="bg-white p-8 rounded-[2.5rem] shadow-xl border border-gray-50">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                            <i class="fa-solid fa-users text-xl"></i>
                        </div>
                        <span class="text-xs font-black text-blue-600 bg-blue-50 px-3 py-1 rounded-full uppercase">Lifetime</span>
                    </div>
                    <h3 class="text-4xl font-black text-gray-900 mb-1"><?php echo $total_app; ?></h3>
                    <p class="text-gray-400 font-bold text-sm">Total Patients</p>
                </div>

                <div class="bg-white p-8 rounded-[2.5rem] shadow-xl border border-gray-50">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-12 h-12 bg-yellow-100 text-yellow-600 rounded-xl flex items-center justify-center">
                            <i class="fa-solid fa-clock text-xl"></i>
                        </div>
                        <span class="text-xs font-black text-yellow-600 bg-yellow-50 px-3 py-1 rounded-full uppercase">Action Needed</span>
                    </div>
                    <h3 class="text-4xl font-black text-gray-900 mb-1"><?php echo $pending_app; ?></h3>
                    <p class="text-gray-400 font-bold text-sm">New Requests</p>
                </div>

              
            </div>

            <div class="bg-white rounded-[3rem] shadow-xl border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-50 flex justify-between items-center">
                    <div>
                        <h3 class="text-2xl font-black text-gray-900">Patient Requests</h3>
                        <p class="text-gray-400 text-sm font-medium">Manage your incoming appointments</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 text-gray-400 text-[10px] font-black uppercase tracking-widest">
                            <tr>
                                <th class="px-8 py-5">Patient Name</th>
                                <th class="px-8 py-5">Symptoms</th>
                                <th class="px-8 py-5">Date / Time</th>
                                <th class="px-8 py-5">Status</th>
                                <th class="px-8 py-5 text-center">Quick Actions</th>
                            </tr>
                        </thead>
<tbody class="divide-y divide-gray-50">
    <?php
    // Sahi Join: Appointments -> Patients -> Users
    $sql = "SELECT a.*, u.full_name as patient_actual_name 
            FROM appointments a 
            JOIN patients p ON a.patient_id = p.patient_id 
            JOIN users u ON p.user_id = u.user_id 
            WHERE a.doctor_id = '$doctor_id' 
            AND a.status = 'Confirmed' 
            ORDER BY a.appointment_date ASC";

    $res = mysqli_query($conn, $sql);

    if ($res && mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
    ?>
            <tr class="hover:bg-blue-50/20 transition">
                <td class="px-8 py-6">
                    <p class="text-gray-900 font-black">
                        <?php echo htmlspecialchars($row['patient_actual_name']); ?>
                    </p>
                </td>
                <td class="px-8 py-6">
                    <p class="text-gray-500 text-xs font-medium max-w-[200px] truncate">
                        <?php echo htmlspecialchars($row['disease_description'] ?? 'No Symptoms'); ?>
                    </p>
                </td>
                <td class="px-8 py-6">
                    <p class="text-gray-900 font-bold text-sm">
                        <?php echo date('d M, Y', strtotime($row['appointment_date'])); ?>
                    </p>
                    <p class="text-blue-500 text-[10px] font-black uppercase">
                        <?php echo $row['appointment_time']; ?>
                    </p>
                </td>
                <td class="px-8 py-6 text-center">
                    <span class="bg-green-100 text-green-700 px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-widest">
                        <i class="fa-solid fa-check-double mr-1"></i> <?php echo $row['status']; ?>
                    </span>
                </td>
                <td class="px-8 py-6 text-center">
                    <span class="text-blue-600 font-bold text-xs uppercase">
                        Ready for Visit
                    </span>
                </td>
            </tr>
    <?php
        }
    } else {
        echo "<tr><td colspan='5' class='px-8 py-16 text-center text-gray-400 font-bold italic'>No confirmed patients found for Doctor ID: $doctor_id</td></tr>";
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