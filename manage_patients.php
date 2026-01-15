<?php
include('auth/check_login.php');
checkAccess(1);
include('config/db.php');
include('includes/header.php');

if (isset($_GET['delete_id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    mysqli_query($conn, "DELETE FROM appointments WHERE patient_id = (SELECT user_id FROM users WHERE user_id = '$id')");
    mysqli_query($conn, "DELETE FROM patients WHERE user_id = '$id'");
    mysqli_query($conn, "DELETE FROM users WHERE user_id = '$id'");
    header("Location: manage_patients.php?msg=deleted");
}
?>

<div class="flex min-h-screen bg-[#F8FAFC]">
    <div class="w-80 bg-white border-r-4 border-gray-900 shadow-2xl hidden lg:block">
        <div class="p-8 sticky top-24">
            <nav class="space-y-3 mt-10">
                <a href="admin_dashboard.php" class="flex items-center p-4 text-gray-500 hover:bg-gray-50 rounded-2xl font-bold transition">
                    <i class="fa-solid fa-gauge-high mr-4"></i> Dashboard
                </a>
                <a href="manage_cities.php" class="flex items-center p-4 text-gray-500 hover:bg-gray-50 rounded-2xl font-bold">
                    <i class="fa-solid fa-city mr-4"></i> Manage Cities
                </a>
                <a href="manage_doctors.php" class="flex items-center p-4 text-gray-500 hover:bg-gray-50 rounded-2xl font-bold">
                    <i class="fa-solid fa-user-md mr-4"></i> Doctors List
                </a>
                <a href="manage_patients.php" class="flex items-center p-4 bg-gray-900 text-white rounded-2xl font-bold shadow-lg">
                    <i class="fa-solid fa-hospital-user mr-4"></i> Patients List
                </a>
                <div class="pt-10">
                    <a href="logout.php" class="flex items-center p-4 text-red-500 font-bold hover:bg-red-50 rounded-2xl transition">
                        <i class="fa-solid fa-power-off mr-4"></i> Logout
                    </a>
                </div>
            </nav>
        </div>
    </div>

    <div class="flex-1 p-8 lg:p-12">
        <div class="max-w-6xl mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
                <div>
                    <h1 class="text-4xl font-black text-gray-900">Patient Directory</h1>
                    <p class="text-gray-400 font-medium">Manage all users registered as patients.</p>
                </div>
                <div class="bg-white px-6 py-4 rounded-3xl shadow-sm border border-gray-100">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-tighter mb-1">Total Users</p>
                    <p class="text-2xl font-black text-blue-600">
                        <?php echo mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role_id = 3"))['total']; ?>
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-[3rem] shadow-xl border border-gray-100 overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-gray-400 text-[10px] font-black uppercase tracking-widest border-b">
                        <tr>
                            <th class="px-8 py-6">Patient Details</th>
                            <th class="px-8 py-6">Contact Info</th>
                            <th class="px-8 py-6">Registration Date</th>
                            <th class="px-8 py-6 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php
                        $res = mysqli_query($conn, "SELECT * FROM users WHERE role_id = 3 ORDER BY user_id DESC");
                        if (mysqli_num_rows($res) > 0) {
                            while ($row = mysqli_fetch_assoc($res)) {
                        ?>
                                <tr class="hover:bg-blue-50/20 transition group">
                                    <td class="px-8 py-6">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mr-4 font-black">
                                                <?php echo strtoupper(substr($row['full_name'], 0, 1)); ?>
                                            </div>
                                            <p class="font-black text-gray-900"><?php echo $row['full_name']; ?></p>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <p class="text-sm font-bold text-gray-700"><?php echo $row['email']; ?></p>
                                        <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest"><?php echo $row['phone_number'] ?: 'No Phone'; ?></p>
                                    </td>
                                    <td class="px-8 py-6">
                                        <p class="text-sm font-bold text-gray-500">
                                            <i class="fa-regular fa-calendar-check mr-2"></i>
                                            Joined System
                                        </p>
                                    </td>
                                    <td class="px-8 py-6 text-center">
                                        <a href="?delete_id=<?php echo $row['user_id']; ?>"
                                            onclick="return confirm('Are you sure to delete this patient?')"
                                            class="inline-flex items-center justify-center w-10 h-10 bg-red-50 text-red-400 rounded-xl hover:bg-red-500 hover:text-white transition">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </a>
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='4' class='p-20 text-center text-gray-400 italic font-bold'>No patients registered yet.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>