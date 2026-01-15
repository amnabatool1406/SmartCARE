<?php
include('config/db.php');
include('includes/header.php');

if (isset($_POST['send_message'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    $u_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : "NULL";
    $r_id = isset($_SESSION['role_id']) ? $_SESSION['role_id'] : "NULL";

    $sql = "INSERT INTO contacts (user_id, role_id, full_name, email, message) 
            VALUES ($u_id, $r_id, '$name', '$email', '$message')";

    if (mysqli_query($conn, $sql)) {
        $success = "Your message has been sent successfully!";
    } else {
        $error = "Something went wrong. Please try again.";
    }
}
?>

<style>
    .contact-card {
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .contact-card:hover {
        transform: translateY(-5px);
    }
    .form-input {
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    .form-input:focus {
        border-color: #2563eb;
        background: white;
        box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.1);
        transform: scale(1.01);
    }
    .floating-icon {
        animation: floating 3s ease-in-out infinite;
    }
    @keyframes floating {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
</style>

<div class="max-w-6xl mx-auto px-4 py-16">
    <div class="text-center mb-16">
        <h2 class="text-5xl font-black text-gray-900 tracking-tight">Get In <span class="text-blue-600 relative inline-block">Touch<span class="absolute bottom-0 left-0 w-full h-2 bg-blue-100 -z-10"></span></span></h2>
        <p class="text-gray-500 mt-4 text-lg">Your health is our priority. Reach out anytime.</p>
    </div>

    <div class="bg-white rounded-[3.5rem] shadow-[0_35px_60px_-15px_rgba(0,0,0,0.1)] overflow-hidden border border-gray-50 flex flex-col md:flex-row contact-card">
        
        <div class="md:w-1/3 bg-gradient-to-br from-blue-600 to-blue-800 p-12 text-white flex flex-col justify-between relative overflow-hidden">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/10 rounded-full blur-3xl"></div>
            
            <div class="relative z-10">
                <h3 class="text-2xl font-black mb-10 border-b-2 border-blue-400/50 pb-2 inline-block">Contact Info</h3>
                <ul class="space-y-10">
                    <li>
                        <a href="tel:+923001234567" class="flex items-center group">
                            <div class="bg-white/10 p-4 rounded-2xl mr-5 group-hover:bg-white group-hover:text-blue-600 transition-all duration-300">
                                <i class="fa-solid fa-phone text-xl"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-tighter text-blue-200">24/7 Support</p>
                                <p class="text-lg font-bold">+92 300 1234567</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="mailto:support@smartcare.com" class="flex items-center group">
                            <div class="bg-white/10 p-4 rounded-2xl mr-5 group-hover:bg-white group-hover:text-blue-600 transition-all duration-300">
                                <i class="fa-solid fa-envelope text-xl"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-tighter text-blue-200">Email Address</p>
                                <p class="text-lg font-bold">support@smartcare.com</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.google.com/maps?q=Medical+Center+Karachi" target="_blank" class="flex items-center group">
                            <div class="bg-white/10 p-4 rounded-2xl mr-5 group-hover:bg-white group-hover:text-blue-600 transition-all duration-300">
                                <i class="fa-solid fa-location-dot text-xl"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-tighter text-blue-200">Main Center</p>
                                <p class="text-lg font-bold">Karachi, Pakistan</p>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="mt-10 rounded-3xl overflow-hidden border-4 border-white/10 shadow-2xl relative z-10">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3619.3139414592816!2d67.06785891157954!3d24.86165784408543!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3eb33ee079047979%3A0x6a0f195079a49931!2sPECHS%20Block%206%2C%20Karachi!5e0!3m2!1sen!2spk!4v1715634567890!5m2!1sen!2spk" width="100%" height="150" style="border:0; filter: grayscale(100%) invert(90%) contrast(90%);" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>

        <div class="md:w-2/3 p-12 lg:p-20 bg-gray-50/30">
            <?php if (isset($success)) {
                echo "<div class='bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 px-6 py-4 rounded-2xl mb-8 shadow-sm font-bold flex items-center gap-3'><i class='fa-solid fa-circle-check text-xl'></i> $success</div>";
            } ?>
            
            <form action="" method="POST" class="space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1 uppercase tracking-widest">Full Name</label>
                        <input type="text" name="name"
                            value="<?= isset($_SESSION['full_name']) ? $_SESSION['full_name'] : '' ?>"
                            required class="w-full p-5 bg-white border-2 border-gray-100 rounded-3xl outline-none form-input font-semibold text-gray-800" placeholder="John Doe">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1 uppercase tracking-widest">Email Address</label>
                        <input type="email" name="email"
                            placeholder="example@mail.com" required class="w-full p-5 bg-white border-2 border-gray-100 rounded-3xl outline-none form-input font-semibold text-gray-800">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-bold text-gray-700 ml-1 uppercase tracking-widest">Your Message</label>
                    <textarea name="message" rows="5" required placeholder="Tell us how we can assist you..." class="w-full p-5 bg-white border-2 border-gray-100 rounded-3xl outline-none form-input font-semibold text-gray-800"></textarea>
                </div>

                <button type="submit" name="send_message" class="group w-full bg-gray-900 text-white font-black py-6 rounded-3xl hover:bg-blue-600 transition-all duration-500 shadow-[0_20px_50px_rgba(0,0,0,0.2)] hover:shadow-blue-500/40 uppercase tracking-[0.2em] flex items-center justify-center gap-4 text-lg">
                    Send Message 
                    <i class="fa-solid fa-paper-plane group-hover:translate-x-2 group-hover:-translate-y-2 transition-transform duration-300"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>