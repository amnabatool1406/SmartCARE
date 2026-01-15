<?php 
include('auth/check_login.php');
checkAccess(1); 
include('config/db.php');
include('includes/header.php'); 
?>

<div class="max-w-7xl mx-auto px-4 py-12">
    <div class="bg-white rounded-[2.5rem] shadow-2xl overflow-hidden border border-gray-100">
        <div class="bg-gray-900 p-8 flex justify-between items-center">
            <h2 class="text-3xl font-black text-white italic">Inquiry <span class="text-blue-500">Inbox</span></h2>
        </div>

        <div class="p-8">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b-2 border-gray-100">
                        <th class="py-4 text-xs font-black uppercase text-gray-400 tracking-widest">User/Role</th>
                        <th class="py-4 text-xs font-black uppercase text-gray-400 tracking-widest">Message Details</th>
                        <th class="py-4 text-xs font-black uppercase text-gray-400 tracking-widest">Date</th>
                        <th class="py-4 text-xs font-black uppercase text-gray-400 tracking-widest">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php
                    $q = "SELECT * FROM contacts ORDER BY created_at DESC";
                    $res = mysqli_query($conn, $q);
                    while($row = mysqli_fetch_assoc($res)) {
                        $role_label = ($row['role_id'] == 2) ? 'Doctor' : (($row['role_id'] == 3) ? 'Patient' : 'Guest');
                        $role_class = ($row['role_id'] == 2) ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600';
                    ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-6">
                            <p class="font-black text-gray-900"><?= $row['full_name'] ?></p>
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase <?= $role_class ?>"><?= $role_label ?></span>
                        </td>
                        <td class="py-6">
                            <p class="text-xs font-bold text-blue-500 mb-1"><?= $row['email'] ?></p>
                            <p class="text-sm text-gray-600 line-clamp-2"><?= $row['message'] ?></p>
                        </td>
                        <td class="py-6 text-xs font-black text-gray-400"><?= date('M d, Y', strtotime($row['created_at'])) ?></td>
                        <td class="py-6">
                         <button onclick="showMessage('<?= $row['full_name'] ?>', '<?= addslashes(nl2br($row['message'])) ?>')" 
        class="text-gray-900 hover:text-blue-600 font-black uppercase text-[10px] tracking-widest border-b-2 border-gray-900">
    View
</button>                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
function showMessage(sender, msg) {
    // Aap yahan koi acha SweetAlert bhi use kar sakte hain, abhi simple alert/confirm hai
    alert("Message from: " + sender + "\n\n" + msg.replace(/<br\s*\/?>/mg,"\n"));
}
</script>
<?php include('includes/footer.php'); ?>