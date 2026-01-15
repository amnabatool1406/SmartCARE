<?php
include('auth/check_login.php');
checkAccess(3);
include('config/db.php');
include('includes/header.php');

$user_id = $_SESSION['user_id'];
$p_query = mysqli_query($conn, "SELECT patient_id FROM patients WHERE user_id = '$user_id'");
$p_data = mysqli_fetch_assoc($p_query);
$patient_id = $p_data['patient_id'];

$query = "SELECT a.*, u.full_name as doc_name, d.specialization 
          FROM appointments a
          JOIN doctors d ON a.doctor_id = d.doctor_id
          JOIN users u ON d.user_id = u.user_id
          WHERE a.patient_id = '$patient_id' AND a.status = 'Confirmed'
          ORDER BY a.appointment_date DESC";
$res = mysqli_query($conn, $query);
?>

<div class="bg-gray-50 min-h-screen py-12 px-4">
    <div class="max-w-5xl mx-auto">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h2 class="text-3xl font-black text-gray-900 uppercase tracking-tight">Medical <span class="text-blue-600">History</span></h2>
                <p class="text-gray-500 font-medium">View all your past successful consultations.</p>
            </div>
            <i class="fa-solid fa-file-medical text-5xl text-blue-100"></i>
        </div>

        <div class="space-y-6">
            <?php if (mysqli_num_rows($res) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($res)): ?>
                    <div class="bg-white p-8 rounded-[2.5rem] shadow-xl shadow-gray-200/50 border border-white flex flex-col md:flex-row gap-8 items-center hover:scale-[1.01] transition-transform">

                        <div class="bg-blue-600 text-white p-4 rounded-3xl text-center min-w-[100px]">
                            <p class="text-xs font-black uppercase opacity-80"><?php echo date('M', strtotime($row['appointment_date'])); ?></p>
                            <p class="text-2xl font-black"><?php echo date('d', strtotime($row['appointment_date'])); ?></p>
                            <p class="text-xs font-bold"><?php echo date('Y', strtotime($row['appointment_date'])); ?></p>
                        </div>

                        <div class="flex-1">
                            <h3 class="text-xl font-black text-gray-900">Dr. <?php echo $row['doc_name']; ?></h3>
                            <p class="text-blue-600 font-bold text-sm mb-3"><?php echo $row['specialization']; ?></p>
                            <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                                <p class="text-xs font-black text-gray-400 uppercase mb-1">Diagnosis / Reason</p>
                                <p class="text-gray-700 font-medium italic">"<?php echo $row['disease_description'] ?? 'Regular Checkup'; ?>"</p>
                            </div>
                        </div>

                        <div>
                            <span class="bg-green-100 text-green-700 px-6 py-2 rounded-full font-black text-xs uppercase">
                                <i class="fa-solid fa-circle-check mr-2"></i> Completed
                            </span>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="bg-white p-20 rounded-[3rem] text-center border-4 border-dashed border-gray-100">
                    <i class="fa-solid fa-notes-medical text-6xl text-gray-200 mb-6"></i>
                    <h3 class="text-2xl font-black text-gray-400">No medical records found.</h3>
                    <p class="text-gray-400 mt-2">Your history will appear here after your first completed visit.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>