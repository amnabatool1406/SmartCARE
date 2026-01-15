<?php
include('auth/check_login.php');
checkAccess(3);
include('config/db.php');
include('includes/header.php');
?>

<div class="bg-[#F8FAFC] min-h-screen pb-20">
    <div class="bg-blue-600 pt-16 pb-32 px-4">
        <div class="max-w-5xl mx-auto text-center">
            <h1 class="text-4xl md:text-5xl font-black text-white mb-6">Find Your Specialist</h1>
            <p class="text-blue-100 text-lg mb-10">Search by city or specialization and book your slot instantly.</p>

            <form action="" method="GET" class="bg-white p-4 rounded-[2.5rem] shadow-2xl flex flex-col md:flex-row gap-4 items-center border-8 border-white/20">
                <div class="flex-1 w-full relative">
                    <i class="fa-solid fa-location-dot absolute left-5 top-5 text-blue-500"></i>
                    <select name="city" class="w-full pl-12 pr-6 py-4 bg-gray-50 border-none rounded-3xl focus:ring-2 focus:ring-blue-500 font-bold text-gray-700 outline-none appearance-none">
                        <option value="">All Cities</option>
                        <?php
                        $cities = mysqli_query($conn, "SELECT * FROM cities");
                        while ($c = mysqli_fetch_assoc($cities)) {
                            $sel = (isset($_GET['city']) && $_GET['city'] == $c['city_id']) ? "selected" : "";
                            echo "<option value='" . $c['city_id'] . "' $sel>" . $c['city_name'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="flex-1 w-full relative">
                    <i class="fa-solid fa-stethoscope absolute left-5 top-5 text-blue-500"></i>
                    <input type="text" name="spec" placeholder="Specialization (e.g. Dentist)" value="<?php echo $_GET['spec'] ?? ''; ?>"
                        class="w-full pl-12 pr-6 py-4 bg-gray-50 border-none rounded-3xl focus:ring-2 focus:ring-blue-500 font-bold text-gray-700 outline-none">
                </div>
                <button type="submit" class="w-full md:w-auto bg-gray-900 text-white px-10 py-4 rounded-3xl font-black hover:bg-blue-700 transition-all shadow-lg transform active:scale-95">
                    SEARCH NOW
                </button>
            </form>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 -mt-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php
            $where = "WHERE 1=1";
            if (!empty($_GET['city'])) {
                $city_id = mysqli_real_escape_string($conn, $_GET['city']);
                $where .= " AND d.city_id = '$city_id'";
            }
            if (!empty($_GET['spec'])) {
                $spec = mysqli_real_escape_string($conn, $_GET['spec']);
                $where .= " AND d.specialization LIKE '%$spec%'";
            }

            $sql = "SELECT d.*, u.full_name, c.city_name 
                    FROM doctors d 
                    JOIN users u ON d.user_id = u.user_id 
                    JOIN cities c ON d.city_id = c.city_id
                    $where";

            $res = mysqli_query($conn, $sql);

            if (mysqli_num_rows($res) > 0) {
                while ($doc = mysqli_fetch_assoc($res)) {
            ?>
                    <div class="bg-white p-8 rounded-[3rem] shadow-xl border border-gray-100 hover:shadow-blue-100/50 transition-all duration-500 group relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-bl-[5rem] -mr-16 -mt-16 group-hover:bg-blue-600 transition-colors duration-500"></div>

                        <div class="relative z-10">
                            <div class="w-20 h-20 bg-blue-100 text-blue-600 rounded-3xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                                <i class="fa-solid fa-user-md text-4xl"></i>
                            </div>

                            <h3 class="text-2xl font-black text-gray-900 mb-1">Dr. <?php echo $doc['full_name']; ?></h3>
                            <p class="text-blue-600 font-bold text-sm mb-6 uppercase tracking-widest italic"><?php echo $doc['specialization']; ?></p>

                            <div class="space-y-4 mb-8">
                                <div class="flex items-center text-gray-500 font-bold text-sm">
                                    <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center mr-4">
                                        <i class="fa-solid fa-location-arrow text-blue-400"></i>
                                    </div>
                                    <?php echo $doc['city_name']; ?>
                                </div>
                                <div class="flex items-center text-gray-500 font-bold text-sm">
                                    <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center mr-4">
                                        <i class="fa-solid fa-star text-yellow-400"></i>
                                    </div>
                                    <?php echo $doc['experience_years']; ?>+ Years Experience
                                </div>
                            </div>

                            <a href="book_appointment.php?doc_id=<?php echo $doc['doctor_id']; ?>"
                                class="block text-center w-full py-5 bg-blue-600 text-white font-black rounded-2xl hover:bg-gray-900 transition-all shadow-xl shadow-blue-100 transform active:scale-95">
                                BOOK APPOINTMENT
                            </a>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo "
                <div class='col-span-full bg-white p-20 rounded-[3rem] text-center border-4 border-dashed border-gray-100'>
                    <i class='fa-solid fa-user-slash text-6xl text-gray-200 mb-6'></i>
                    <h3 class='text-2xl font-black text-gray-400'>No specialists found in this category.</h3>
                    <p class='text-gray-400 mt-2'>Try changing the city or search terms.</p>
                </div>";
            }
            ?>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>