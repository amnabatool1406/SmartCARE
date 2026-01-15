<?php
include('auth/check_login.php');
checkAccess(1);
include('config/db.php');
include('includes/header.php');

if (isset($_POST['add_info'])) {
    $d_name = mysqli_real_escape_string($conn, $_POST['disease_name']);
    $prev = mysqli_real_escape_string($conn, $_POST['prevention']);
    $cure = mysqli_real_escape_string($conn, $_POST['cure']);

    $info_image = "";
    if ($_FILES['info_image']['name'] != "") {
        $info_image = time() . "_" . $_FILES['info_image']['name'];
        move_uploaded_file($_FILES['info_image']['tmp_name'], "uploads/" . $info_image);
        mysqli_query($conn, "INSERT INTO medical_info (disease_name, prevention, cure, info_image) VALUES ('$d_name', '$prev', '$cure', '$info_image')");
    } else {
        mysqli_query($conn, "INSERT INTO medical_info (disease_name, prevention, cure) VALUES ('$d_name', '$prev', '$cure')");
    }
    header("Location: admin_dashboard.php?status=info_added");
}

if (isset($_GET['del_info'])) {
    $id = $_GET['del_info'];
    $img_res = mysqli_query($conn, "SELECT info_image FROM medical_info WHERE info_id = '$id'");
    $img_row = mysqli_fetch_assoc($img_res);
    if ($img_row['info_image'] != "" && file_exists("uploads/" . $img_row['info_image'])) {
        unlink("uploads/" . $img_row['info_image']);
    }
    mysqli_query($conn, "DELETE FROM medical_info WHERE info_id = '$id'");
    header("Location: admin_dashboard.php?status=deleted");
}

if (isset($_POST['add_news'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    $image_name = $_FILES['news_image']['name'];
    if ($image_name != "") {
        $target = "uploads/" . time() . "_" . basename($image_name);
        if (move_uploaded_file($_FILES['news_image']['tmp_name'], $target)) {
            $final_img = time() . "_" . $image_name;
            mysqli_query($conn, "INSERT INTO medical_news (title, content, news_image) VALUES ('$title', '$content', '$final_img')");
        }
    } else {
        mysqli_query($conn, "INSERT INTO medical_news (title, content) VALUES ('$title', '$content')");
    }
    header("Location: admin_dashboard.php?status=news_added");
}

if (isset($_GET['del_news'])) {
    $id = $_GET['del_news'];
    $img_res = mysqli_query($conn, "SELECT news_image FROM medical_news WHERE news_id = '$id'");
    $img_row = mysqli_fetch_assoc($img_res);
    if ($img_row['news_image'] != "" && file_exists("uploads/" . $img_row['news_image'])) {
        unlink("uploads/" . $img_row['news_image']);
    }
    mysqli_query($conn, "DELETE FROM medical_news WHERE news_id = '$id'");
    header("Location: admin_dashboard.php?status=deleted");
}

$total_doctors = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM doctors"))['total'];
$total_patients = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role_id = 3"))['total'];
$total_cities = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM cities"))['total'];
$total_appointments = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM appointments"))['total'];
$total_wiki = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM medical_info"))['total'];
$total_news = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM medical_news"))['total'];
?>

<div class="flex min-h-screen bg-[#F8FAFC]">
    <div class="w-80 bg-white border-r-4 border-blue-600 shadow-2xl hidden lg:flex flex-col h-screen sticky top-0">
        <div class="p-8 sticky top-0 bg-white z-10">
            <div class="bg-gray-900 p-6 rounded-[2rem] text-white shadow-xl shadow-gray-100">
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
                <a href="manage_patients.php" class="flex items-center gap-4 p-4 text-gray-400 hover:bg-gray-50 hover:text-blue-600 rounded-2xl transition font-bold">
                    <i class="fa-solid fa-user-injured"></i> Manage Patients
                </a>
                <a href="manage_cities.php" class="flex items-center gap-4 p-4 text-gray-400 hover:bg-gray-50 hover:text-blue-600 rounded-2xl transition font-bold">
                    <i class="fa-solid fa-city"></i> Manage Cities
                </a>
                <a href="medical_info.php" class="flex items-center gap-4 p-4 text-gray-400 hover:bg-gray-50 hover:text-blue-600 rounded-2xl transition font-bold">
                    <i class="fa-solid fa-book-medical"></i> Health Library
                </a>
                <a href="medical_news.php" class="flex items-center gap-4 p-4 text-gray-400 hover:bg-gray-50 hover:text-blue-600 rounded-2xl transition font-bold">
                    <i class="fa-solid fa-newspaper"></i> Medical News
                </a>
                <a href="manage_all_appointments.php" class="flex items-center gap-4 p-4 text-gray-400 hover:bg-gray-50 hover:text-blue-600 rounded-2xl transition font-bold">
                    <i class="fa-solid fa-calendar-check"></i> All Appointments
                </a>
                <a href="manage_contacts.php" class="flex items-center gap-4 p-4 text-gray-400 hover:bg-gray-50 hover:text-blue-600 rounded-2xl transition font-bold">
                    <i class="fa-solid fa-envelope"></i> Inquiry Inbox
                </a>
            <?php elseif ($_SESSION['role_id'] == 2): ?>
                <a href="doctor_dashboard.php" class="flex items-center gap-4 p-4 text-blue-600 bg-blue-50 rounded-2xl font-bold">
                    <i class="fa-solid fa-calendar-check"></i> Appointments
                </a>
                <a href="doctor_profile.php" class="flex items-center gap-4 p-4 text-gray-400 hover:bg-gray-50 hover:text-blue-600 rounded-2xl transition font-bold">
                    <i class="fa-solid fa-user-gear"></i> Profile Settings
                </a>
            <?php else: ?>
                <a href="patient_dashboard.php" class="flex items-center gap-4 p-4 text-blue-600 bg-blue-50 rounded-2xl font-bold">
                    <i class="fa-solid fa-columns"></i> My Bookings
                </a>
                <a href="book_appointment.php" class="flex items-center gap-4 p-4 text-gray-400 hover:bg-gray-50 hover:text-blue-600 rounded-2xl transition font-bold">
                    <i class="fa-solid fa-calendar-plus"></i> New Appointment
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
            <header class="mb-12">
                <h1 class="text-4xl font-black text-gray-900">System Overview</h1>
                <p class="text-gray-500 font-medium">Monitoring SmartCARE health services.</p>
            </header>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                <div class="bg-white p-6 rounded-[2rem] shadow-xl shadow-gray-100 border border-white">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mb-4"><i class="fa-solid fa-user-md text-xl"></i></div>
                    <h3 class="text-3xl font-black text-gray-900"><?php echo str_pad($total_doctors, 2, "0", STR_PAD_LEFT); ?></h3>
                    <p class="text-gray-400 font-bold text-xs uppercase tracking-widest">Active Doctors</p>
                </div>
                <div class="bg-white p-6 rounded-[2rem] shadow-xl shadow-gray-100 border border-white">
                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center mb-4"><i class="fa-solid fa-hospital-user text-xl"></i></div>
                    <h3 class="text-3xl font-black text-gray-900"><?php echo str_pad($total_patients, 2, "0", STR_PAD_LEFT); ?></h3>
                    <p class="text-gray-400 font-bold text-xs uppercase tracking-widest">Registered Patients</p>
                </div>
                <div class="bg-blue-600 p-6 rounded-[2rem] shadow-xl shadow-blue-100 text-white">
                    <div class="w-12 h-12 bg-white/20 text-white rounded-2xl flex items-center justify-center mb-4 backdrop-blur-md"><i class="fa-solid fa-calendar-check text-xl"></i></div>
                    <h3 class="text-3xl font-black"><?php echo $total_appointments; ?></h3>
                    <p class="text-blue-100 font-bold text-xs uppercase tracking-widest">Total Bookings</p>
                </div>
                <div class="bg-white p-6 rounded-[2rem] shadow-xl shadow-gray-100 border border-white">
                    <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-2xl flex items-center justify-center mb-4"><i class="fa-solid fa-map-location-dot text-xl"></i></div>
                    <h3 class="text-3xl font-black text-gray-900"><?php echo str_pad($total_cities, 2, "0", STR_PAD_LEFT); ?></h3>
                    <p class="text-gray-400 font-bold text-xs uppercase tracking-widest">Operational Cities</p>
                </div>
                <div class="bg-white p-6 rounded-[2rem] shadow-xl shadow-gray-100 border border-white">
                    <div class="w-12 h-12 bg-red-100 text-red-600 rounded-2xl flex items-center justify-center mb-4"><i class="fa-solid fa-book-medical text-xl"></i></div>
                    <h3 class="text-3xl font-black text-gray-900"><?php echo str_pad($total_wiki, 2, "0", STR_PAD_LEFT); ?></h3>
                    <p class="text-gray-400 font-bold text-xs uppercase tracking-widest">Health Articles</p>
                </div>
                <div class="bg-white p-6 rounded-[2rem] shadow-xl shadow-gray-100 border border-white">
                    <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-2xl flex items-center justify-center mb-4"><i class="fa-solid fa-newspaper text-xl"></i></div>
                    <h3 class="text-3xl font-black text-gray-900"><?php echo str_pad($total_news, 2, "0", STR_PAD_LEFT); ?></h3>
                    <p class="text-gray-400 font-bold text-xs uppercase tracking-widest">News Posts</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
                <div class="bg-white p-8 rounded-[2.5rem] shadow-xl border border-gray-100">
                    <h3 class="text-xl font-black mb-6 flex items-center"><i class="fa-solid fa-heart-pulse text-red-500 mr-3"></i> Add Health Info</h3>
                    <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
                        <input type="text" name="disease_name" placeholder="Disease Name (e.g. Flu)" required class="w-full p-4 bg-gray-50 rounded-2xl border-none focus:ring-2 focus:ring-blue-500 font-bold">
                        <textarea name="prevention" placeholder="Prevention Steps..." required class="w-full p-4 bg-gray-50 rounded-2xl border-none focus:ring-2 focus:ring-blue-500 font-medium h-24"></textarea>
                        <textarea name="cure" placeholder="Treatment/Cure..." required class="w-full p-4 bg-gray-50 rounded-2xl border-none focus:ring-2 focus:ring-blue-500 font-medium h-24"></textarea>
                        <div class="p-4 bg-red-50/50 border-2 border-dashed border-red-100 rounded-2xl">
                            <input type="file" name="info_image" class="text-sm font-bold text-red-600">
                            <p class="text-[10px] text-gray-400 mt-1 uppercase font-black">Add Wiki Cover Photo (Optional)</p>
                        </div>
                        <button type="submit" name="add_info" class="w-full bg-blue-600 text-white py-4 rounded-2xl font-black hover:bg-gray-900 transition uppercase tracking-widest">Publish to Wiki</button>
                    </form>
                </div>

                <div class="bg-white p-8 rounded-[2.5rem] shadow-xl border border-gray-100">
                    <h3 class="text-xl font-black mb-6 flex items-center"><i class="fa-solid fa-bullhorn text-blue-500 mr-3"></i> Post Medical News</h3>
                    <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
                        <input type="text" name="title" placeholder="News Title" required class="w-full p-4 bg-gray-50 rounded-2xl border-none focus:ring-2 focus:ring-blue-500 font-bold">
                        <textarea name="content" placeholder="News Content/Details..." required class="w-full p-4 bg-gray-50 rounded-2xl border-none focus:ring-2 focus:ring-blue-500 font-medium h-32"></textarea>
                        <div class="p-4 bg-blue-50/50 border-2 border-dashed border-blue-100 rounded-2xl">
                            <input type="file" name="news_image" class="text-sm font-bold text-blue-600">
                            <p class="text-[10px] text-gray-400 mt-1 uppercase font-black">Add Cover Photo (Optional)</p>
                        </div>
                        <button type="submit" name="add_news" class="w-full bg-gray-900 text-white py-4 rounded-2xl font-black hover:bg-blue-600 transition uppercase tracking-widest">Post News Update</button>
                    </form>
                </div>
            </div>

            <div class="bg-white rounded-[3rem] shadow-xl border border-gray-100 overflow-hidden mb-12">
                <div class="p-8 border-b border-gray-50">
                    <h3 class="text-2xl font-black text-gray-900">Manage Health Library (Wiki)</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 text-gray-400 text-[10px] font-black uppercase tracking-widest">
                            <tr>
                                <th class="px-8 py-5">Disease Name</th>
                                <th class="px-8 py-5">Image</th>
                                <th class="px-8 py-5 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <?php
                            $wiki_res = mysqli_query($conn, "SELECT * FROM medical_info ORDER BY info_id DESC LIMIT 5");
                            while ($w = mysqli_fetch_assoc($wiki_res)) {
                            ?>
                                <tr>
                                    <td class="px-8 py-6 font-bold"><?php echo $w['disease_name']; ?></td>
                                    <td class="px-8 py-6">
                                        <?php if (!empty($w['info_image'])) { ?>
                                            <img src="uploads/<?php echo $w['info_image']; ?>" class="w-12 h-12 rounded-xl object-cover">
                                        <?php } else {
                                            echo "No Image";
                                        } ?>
                                    </td>
                                    <td class="px-8 py-6 text-center space-x-4">
                                        <a href="manage_wiki.php?id=<?php echo $w['info_id']; ?>" class="text-blue-600 font-black hover:underline text-xs uppercase">EDIT</a>
                                        <span class="text-gray-300">|</span>
                                        <a href="?del_info=<?php echo $w['info_id']; ?>" onclick="return confirm('Delete this info?')" class="text-red-500 font-black hover:underline text-xs uppercase">DELETE</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-[3rem] shadow-xl border border-gray-100 overflow-hidden mb-12">
                <div class="p-8 border-b border-gray-50 flex justify-between items-center">
                    <h3 class="text-2xl font-black text-gray-900">Latest System Appointments</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 text-gray-400 text-[10px] font-black uppercase tracking-widest">
                            <tr>
                                <th class="px-8 py-5">Doctor</th>
                                <th class="px-8 py-5">Patient</th>
                                <th class="px-8 py-5">City</th>
                                <th class="px-8 py-5 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <?php
                            $recent_sql = "SELECT a.status, u1.full_name as doc_name, u2.full_name as pat_name, c.city_name 
                                           FROM appointments a
                                           LEFT JOIN doctors d ON a.doctor_id = d.doctor_id
                                           LEFT JOIN users u1 ON d.user_id = u1.user_id
                                           LEFT JOIN users u2 ON a.patient_id = u2.user_id
                                           LEFT JOIN cities c ON d.city_id = c.city_id
                                           ORDER BY a.appointment_id DESC LIMIT 5";
                            $recent_res = mysqli_query($conn, $recent_sql);

                            if (mysqli_num_rows($recent_res) > 0) {
                                while ($row = mysqli_fetch_assoc($recent_res)) {
                                    $s_clr = ($row['status'] == 'Confirmed') ? 'text-green-600 bg-green-50' : 'text-yellow-600 bg-yellow-50';
                            ?>
                                   <tr class="hover:bg-blue-50/20 transition">
    <td class="px-8 py-6 font-bold text-gray-900">Dr. <?php echo ($row['doc_name'] ?? 'N/A'); ?></td>
    <td class="px-8 py-6 font-medium text-gray-600"><?php echo ($row['pat_name'] ?? 'Guest'); ?></td>
    <td class="px-8 py-6 font-medium text-gray-400 text-sm"><?php echo ($row['city_name'] ?? 'Global'); ?></td>
    <td class="px-8 py-6 text-center">
        <?php 
        // Status handling logic
        $current_status = $row['status'];
        
        if ($current_status == 'Confirmed') {
            $s_clr = 'text-green-600 bg-green-50 border-green-100';
            $label = 'Confirmed';
        } elseif ($current_status == 'Cancelled') {
            $s_clr = 'text-red-600 bg-red-50 border-red-100';
            $label = 'Cancelled';
        } else {
            // Agar status empty ho ya 'Pending' ho
            $s_clr = 'text-amber-600 bg-amber-50 border-amber-200';
            $label = 'Pending';
        }
        ?>
        <span class="<?php echo $s_clr; ?> px-3 py-1 rounded-full text-[10px] font-black uppercase border">
            <?php echo $label; ?>
        </span>
    </td>
</tr>
                            <?php
                                }
                            } else {
                                echo "<tr><td colspan='4' class='px-8 py-10 text-center font-bold text-gray-400'>No Appointments Found in System</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-[3rem] shadow-xl border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-50">
                    <h3 class="text-2xl font-black text-gray-900">Manage News & Blogs</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 text-gray-400 text-[10px] font-black uppercase tracking-widest">
                            <tr>
                                <th class="px-8 py-5">Title</th>
                                <th class="px-8 py-5">Image</th>
                                <th class="px-8 py-5 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <?php
                            $blogs = mysqli_query($conn, "SELECT * FROM medical_news ORDER BY news_id DESC LIMIT 5");
                            while ($b = mysqli_fetch_assoc($blogs)) {
                            ?>
                                <tr>
                                    <td class="px-8 py-6 font-bold"><?php echo $b['title']; ?></td>
                                    <td class="px-8 py-6">
                                        <?php if ($b['news_image']) { ?>
                                            <img src="uploads/<?php echo $b['news_image']; ?>" class="w-12 h-12 rounded-xl object-cover">
                                        <?php } else {
                                            echo "No Image";
                                        } ?>
                                    </td>
                                    <td class="px-8 py-6 text-center space-x-4">
                                        <a href="manage_news.php?id=<?php echo $b['news_id']; ?>" class="text-blue-600 font-black hover:underline text-xs uppercase">EDIT</a>
                                        <span class="text-gray-300">|</span>
                                        <a href="?del_news=<?php echo $b['news_id']; ?>" onclick="return confirm('Delete this blog?')" class="text-red-500 font-black hover:underline text-xs uppercase">DELETE</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>