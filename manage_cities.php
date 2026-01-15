<?php
include('auth/check_login.php');
checkAccess(1); 
include('config/db.php');
include('includes/header.php'); 

if(isset($_POST['add_city'])) {
    $city_name = mysqli_real_escape_string($conn, $_POST['city_name']);
    if(!empty($city_name)) {
        mysqli_query($conn, "INSERT INTO cities (city_name) VALUES ('$city_name')");
        echo "<script>window.location.href='manage_cities.php';</script>";
    }
}

if(isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    mysqli_query($conn, "DELETE FROM cities WHERE city_id = '$id'");
    echo "<script>window.location.href='manage_cities.php';</script>";
}
?>

<div class="flex min-h-screen bg-[#F8FAFC]">
    <div class="w-80 bg-white border-r-4 border-gray-900 shadow-2xl hidden lg:block">
        <div class="p-8 sticky top-24">
            <nav class="space-y-3 mt-10">
                <a href="admin_dashboard.php" class="flex items-center p-4 text-gray-500 hover:bg-gray-50 rounded-2xl font-bold transition">
                    <i class="fa-solid fa-gauge-high mr-4"></i> Dashboard
                </a>
                <a href="manage_cities.php" class="flex items-center p-4 bg-gray-900 text-white rounded-2xl font-bold shadow-lg">
                    <i class="fa-solid fa-city mr-4"></i> Manage Cities
                </a>
                <a href="manage_doctors.php" class="flex items-center p-4 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-2xl font-bold transition">
                    <i class="fa-solid fa-user-md mr-4"></i> Doctors List
                </a>
            </nav>
        </div>
    </div>

    <div class="flex-1 p-8 lg:p-12">
        <div class="max-w-5xl mx-auto">
            <h1 class="text-4xl font-black text-gray-900 mb-10">Location Management</h1>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                <div class="lg:col-span-1">
                    <div class="bg-white p-8 rounded-[2.5rem] shadow-xl border border-gray-100 sticky top-12">
                        <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mb-6">
                            <i class="fa-solid fa-plus text-xl"></i>
                        </div>
                        <h3 class="text-xl font-black text-gray-900 mb-6">Add New City</h3>
                        
                        <form action="" method="POST" class="space-y-6">
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">City Name</label>
                                <input type="text" name="city_name" placeholder="e.g. Karachi" required 
                                       class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 outline-none font-bold text-gray-700">
                            </div>
                            <button type="submit" name="add_city" class="w-full bg-blue-600 text-white py-4 rounded-2xl font-black hover:bg-gray-900 transition shadow-xl shadow-blue-100">
                                SAVE CITY
                            </button>
                        </form>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="bg-white rounded-[2.5rem] shadow-xl border border-gray-100 overflow-hidden">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 text-gray-400 text-[10px] font-black uppercase tracking-widest border-b">
                                <tr>
                                    <th class="px-8 py-5">ID</th>
                                    <th class="px-8 py-5">City Name</th>
                                    <th class="px-8 py-5 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                <?php
                                $res = mysqli_query($conn, "SELECT * FROM cities ORDER BY city_id DESC");
                                if(mysqli_num_rows($res) > 0) {
                                    while($row = mysqli_fetch_assoc($res)) {
                                ?>
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-8 py-6 text-gray-400 font-bold">#<?php echo $row['city_id']; ?></td>
                                    <td class="px-8 py-6 text-gray-900 font-black tracking-tight text-lg"><?php echo $row['city_name']; ?></td>
                                    <td class="px-8 py-6 text-right">
                                        <a href="?delete_id=<?php echo $row['city_id']; ?>" 
                                           onclick="return confirm('Are you sure? This may affect registered doctors.')"
                                           class="inline-flex items-center justify-center w-10 h-10 bg-red-50 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition shadow-sm">
                                            <i class="fa-solid fa-trash-can text-sm"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php 
                                    }
                                } else {
                                    echo "<tr><td colspan='3' class='p-10 text-center text-gray-400 italic font-bold'>No cities added yet.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>