<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include('auth/check_login.php');
checkAccess(1);
include('config/db.php');
include('includes/header.php');

// PHPMailer files require karein
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if (isset($_POST['update_status'])) {
    $app_id = $_POST['appointment_id'];
    $new_status = $_POST['status'];

    $update_sql = "UPDATE appointments SET status = '$new_status' WHERE appointment_id = '$app_id'";
    
    if (mysqli_query($conn, $update_sql)) {
        
        if ($new_status == 'Confirmed') {
            
            $mail_query = "SELECT u.email, u.full_name, a.appointment_date, a.appointment_time 
                           FROM appointments a 
                           JOIN patients p ON a.patient_id = p.patient_id 
                           JOIN users u ON p.user_id = u.user_id 
                           WHERE a.appointment_id = '$app_id'";
            
            $res = mysqli_query($conn, $mail_query);
            $data = mysqli_fetch_assoc($res);

            if ($data) {
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'info.smartcare.org@gmail.com';
                    $mail->Password   = 'vgft udnc zqwc gcjf'; 
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = 587;

                    $mail->setFrom('info.smartcare.org@gmail.com', 'SmartCare Hospital');
                    $mail->addAddress($data['email'], $data['full_name']);

                    $mail->isHTML(true);
                    $mail->Subject = 'Appointment Confirmed - SmartCare';
                    
                    // Styled Email Body
                    $mail->Body = "
                    <div style='background-color: #f8fafc; padding: 40px; font-family: sans-serif;'>
                        <div style='max-width: 600px; margin: 0 auto; background: white; border-radius: 20px; overflow: hidden; border: 1px solid #e2e8f0;'>
                            <div style='background-color: #2563eb; padding: 30px; text-align: center; color: white;'>
                                <h1 style='margin: 0;'>SmartCARE</h1>
                            </div>
                            <div style='padding: 30px;'>
                                <h2 style='color: #1e293b;'>Hello " . $data['full_name'] . "!</h2>
                                <p style='line-height: 1.6; font-size: 16px; color: #475569;'>
                                    Great news! Your appointment at <b>SmartCARE Hospital</b> has been successfully <b>Confirmed</b>.
                                </p>
                                <div style='background: #f1f5f9; padding: 20px; border-radius: 12px; margin: 20px 0;'>
                                    <p style='margin: 0; font-size: 12px; color: #94a3b8; font-weight: bold;'>APPOINTMENT DATE</p>
                                    <p style='margin: 0; font-size: 16px; color: #1e293b; font-weight: bold;'>" . date('d M, Y', strtotime($data['appointment_date'])) . "</p>
                                    <p style='margin: 10px 0 0 0; font-size: 12px; color: #94a3b8; font-weight: bold;'>SCHEDULED TIME</p>
                                    <p style='margin: 0; font-size: 16px; color: #2563eb; font-weight: bold;'>" . date('h:i A', strtotime($data['appointment_time'])) . "</p>
                                </div>
                                <div style='text-align: center; margin-top: 30px;'>
                                    <a href='http://localhost/SmartCARE/login.php' style='background: #2563eb; color: white; padding: 15px 25px; text-decoration: none; border-radius: 50px; font-weight: bold; display: inline-block;'>View Dashboard</a>
                                </div>
                            </div>
                        </div>
                    </div>";

                    $mail->send();
                } catch (Exception $e) { }
            }
        }
        echo "<script>alert('Status Updated & Notification Sent!'); window.location.href='manage_all_appointments.php';</script>";
        exit(); 
    }
}

$query = "SELECT a.*, u_pat.full_name as patient_name, u_doc.full_name as doctor_name 
          FROM appointments a 
          LEFT JOIN patients p ON a.patient_id = p.patient_id 
          LEFT JOIN users u_pat ON p.user_id = u_pat.user_id 
          LEFT JOIN doctors d ON a.doctor_id = d.doctor_id
          LEFT JOIN users u_doc ON d.user_id = u_doc.user_id
          ORDER BY a.appointment_date DESC";
$appointments = mysqli_query($conn, $query);
?>

<div class="flex-1 p-8 lg:p-12 bg-[#F8FAFC]">
    <div class="max-w-6xl mx-auto">
        <div class="bg-blue-600 p-8 rounded-t-[3rem] shadow-2xl text-white">
            <h2 class="text-2xl font-black italic">System <span class="text-gray-900">Master Bookings</span></h2>
            <p class="text-blue-100 text-[10px] font-bold uppercase tracking-widest mt-1">Admin Control: View & Manage All Appointments</p>
        </div>

        <div class="bg-white rounded-b-[3rem] shadow-2xl border border-gray-100 overflow-hidden">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="p-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Patient & Doctor</th>
                        <th class="p-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Schedule</th>
                        <th class="p-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                        <th class="p-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Admin Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php if ($appointments && mysqli_num_rows($appointments) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($appointments)): ?>
                            <tr class="hover:bg-blue-50/30 transition-all">
                                <td class="p-6">
                                    <div class="font-black text-gray-700">Pat: <?= htmlspecialchars($row['patient_name'] ?? 'Unknown') ?></div>
                                    <div class="text-[10px] text-blue-600 font-bold">Doc: Dr. <?= htmlspecialchars($row['doctor_name'] ?? 'N/A') ?></div>
                                </td>
                                <td class="p-6 text-sm font-bold text-gray-600">
                                    <?= date('d M, Y', strtotime($row['appointment_date'])) ?><br>
                                    <span class="text-blue-500 italic text-xs"><?= date('h:i A', strtotime($row['appointment_time'])) ?></span>
                                </td>
                                <td class="p-6">
    <?php
    // Database se status nikalna
    $raw_status = $row['status'];
    
    // Agar status khali hai ya NULL hai, toh usay 'Pending' maan lo
    if (empty($raw_status)) {
        $display_status = 'Pending';
        $clr = 'text-amber-600 bg-amber-50 border border-amber-200';
    } elseif ($raw_status == 'Confirmed') {
        $display_status = 'Confirmed';
        $clr = 'text-green-600 bg-green-50 border border-green-200';
    } elseif ($raw_status == 'Cancelled') {
        $display_status = 'Cancelled';
        $clr = 'text-red-600 bg-red-50 border border-red-200';
    } else {
        // Agar pehle se 'Pending' likha hai
        $display_status = 'Pending';
        $clr = 'text-amber-600 bg-amber-50 border border-amber-200';
    }
    ?>
    
    <span class="<?= $clr ?> px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-tighter inline-flex items-center gap-1">
        <?php if($display_status == 'Pending'): ?>
            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
        <?php endif; ?>
        <?= $display_status ?>
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
                    <?php else: ?>
                        <tr><td colspan="4" class="p-10 text-center font-bold text-gray-400">No appointments found in the system.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>