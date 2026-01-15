<?php
include('auth/check_login.php');
checkAccess(1);
include('config/db.php');
include('includes/header.php'); 

if(isset($_GET['delete_id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    mysqli_query($conn, "DELETE FROM appointments WHERE doctor_id = '$id'");
    
    $get_user = mysqli_query($conn, "SELECT user_id FROM doctors WHERE doctor_id = '$id'");
    $u_row = mysqli_fetch_assoc($get_user);
    $user_id = $u_row['user_id'];
    
    mysqli_query($conn, "DELETE FROM users WHERE user_id = '$user_id'");
    echo "<script>window.location.href='manage_doctors.php?msg=deleted';</script>";
}
?>

<div class="flex min-h-screen bg-[#F8FAFC]">
    <div class="w-80 bg-white border-r-4 border-gray-900 shadow-2xl hidden lg:block">
        <div class="p-8 sticky top-24">
            <nav class="space-y-3 mt-10">
                <a href="admin_dashboard.php" class="flex items-center p-4 text-gray-500 hover:bg-gray-50 rounded-2xl font-bold transition">
                    <i class="fa-solid fa-gauge-high mr-4"></i> Dashboard
                </a>
                <a href="manage_doctors.php" class="flex items-center p-4 bg-gray-900 text-white rounded-2xl font-bold shadow-xl">
                    <i class="fa-solid fa-user-doctor mr-4"></i> Manage Doctors
                </a>
                <a href="manage_patients.php" class="flex items-center p-4 text-gray-500 hover:bg-gray-50 rounded-2xl font-bold transition">
                    <i class="fa-solid fa-user-injured mr-4"></i> Patients
                </a>
                <a href="manage_cities.php" class="flex items-center p-4 text-gray-500 hover:bg-gray-50 rounded-2xl font-bold transition">
                    <i class="fa-solid fa-city mr-4"></i> Manage Cities
                </a>
            </nav>
        </div>
    </div>

    <div class="flex-1 p-12">
        <div class="max-w-6xl mx-auto">
            <div class="flex justify-between items-center mb-12">
                <div>
                    <h1 class="text-4xl font-black text-gray-900 italic">Hospital <span class="text-blue-600">Specialists</span></h1>
                    <p class="text-gray-400 font-bold mt-2 uppercase tracking-widest text-xs">Total Registered Doctors in System</p>
                </div>
                <a href="add_doctor.php" class="bg-blue-600 text-white px-8 py-4 rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-gray-900 transition shadow-2xl shadow-blue-200">
                    <i class="fa-solid fa-plus mr-2"></i> Add New Doctor
                </a>
            </div>

            <div class="bg-white rounded-[3rem] shadow-xl overflow-hidden border-8 border-white">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 text-gray-400 text-[10px] font-black uppercase tracking-widest">
                        <tr>
                            <th class="px-8 py-5">Doctor Info</th>
                            <th class="px-8 py-5">Specialization</th>
                            <th class="px-8 py-5">Experience/Fee</th>
                            <th class="px-8 py-5 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php
                        $query = "SELECT d.*, u.full_name, u.email, c.city_name 
                                  FROM doctors d 
                                  JOIN users u ON d.user_id = u.user_id 
                                  JOIN cities c ON d.city_id = c.city_id";
                        $res = mysqli_query($conn, $query);

                        if(mysqli_num_rows($res) > 0) {
                            while($row = mysqli_fetch_assoc($res)) {
                        ?>
                        <tr class="hover:bg-blue-50/30 transition">
                            <td class="px-8 py-6">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                                        <i class="fa-solid fa-user-md text-blue-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-gray-900 font-black italic">Dr. <?php echo $row['full_name']; ?></p>
                                        <p class="text-xs text-gray-400 font-bold"><?php echo $row['email']; ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="bg-blue-50 text-blue-600 px-4 py-2 rounded-xl inline-block">
                                    <p class="text-xs font-black uppercase"><?php echo $row['specialization']; ?></p>
                                    <p class="text-[10px] font-bold text-blue-400 italic"><?php echo $row['city_name']; ?></p>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-gray-900 font-bold text-sm"><?php echo $row['experience_years']; ?>+ Years</p>
                                 </td>
                            <td class="px-8 py-6">
                                <div class="flex justify-center gap-3">
                                    <a href="edit_doctors.php?id=<?php echo $row['doctor_id']; ?>" class="w-10 h-10 bg-gray-100 text-gray-600 rounded-xl flex items-center justify-center hover:bg-blue-600 hover:text-white transition shadow-sm">
                                        <i class="fa-solid fa-pen-to-square text-sm"></i>
                                    </a>
                                    <a href="?delete_id=<?php echo $row['doctor_id']; ?>" 
                                       onclick="return confirm('Danger! Are you sure you want to delete this doctor?')"
                                       class="w-10 h-10 bg-red-50 text-red-500 rounded-xl flex items-center justify-center hover:bg-red-500 hover:text-white transition shadow-sm">
                                        <i class="fa-solid fa-trash-can text-sm"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php 
                            }
                        } else {
                            echo "<tr><td colspan='4' class='p-20 text-center text-gray-400 italic font-bold'>No doctors registered yet.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>