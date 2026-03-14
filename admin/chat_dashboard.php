<?php
require_once "session_config.php";
include "../koneksi.php";
include "../includes/whatsapp.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

/* =========================
KIRIM PESAN
========================= */

if(isset($_POST['message']) && isset($_POST['phone'])){

$phone = $_POST['phone'];
$message = trim($_POST['message']);

if($phone && $message){

/* kirim ke whatsapp */

kirimWA($phone,$message);

/* simpan ke database */

$stmt = $conn->prepare("
INSERT INTO chats(phone,message,sender)
VALUES(?,?, 'admin')
");

$stmt->bind_param("ss",$phone,$message);
$stmt->execute();
$stmt->close();

}

/* reload chat */

header("Location: chat_dashboard.php?phone=".$phone);
exit;
}

/* =========================
AMBIL CUSTOMER
========================= */

$customers = mysqli_query($conn,"
SELECT phone, MAX(created_at) as last_chat
FROM chats
GROUP BY phone
ORDER BY last_chat DESC
");

$active_phone = $_GET['phone'] ?? null;

include "partials/header.php";
?>

<div class="container-fluid">

<?php include "partials/sidebar.php"; ?>

<div class="content-area">

<div class="row">

<!-- =========================
LIST CUSTOMER
========================= -->

<div class="col-lg-3">

<div class="card p-3">

<h6>Customer</h6>

<div class="list-group">

<?php while($c = mysqli_fetch_assoc($customers)): ?>

<a href="?phone=<?= $c['phone'] ?>"
class="list-group-item list-group-item-action
<?= $active_phone==$c['phone']?'active':'' ?>">

<?= htmlspecialchars($c['phone']) ?>

</a>

<?php endwhile; ?>

</div>

</div>

</div>


<!-- =========================
CHAT AREA
========================= -->

<div class="col-lg-9">

<div class="card p-3">

<?php if($active_phone): ?>

<div id="chatArea" style="height:420px;overflow-y:auto;">

<?php

$messages = mysqli_query($conn,"
SELECT * FROM chats
WHERE phone='$active_phone'
ORDER BY id ASC
");

while($m = mysqli_fetch_assoc($messages)):

?>

<div style="
margin-bottom:10px;
text-align:<?= $m['sender']=='admin'?'right':'left' ?>
">

<div style="
display:inline-block;
padding:10px 14px;
border-radius:12px;
background:<?= $m['sender']=='admin'?'#DCF8C6':'#eee' ?>
">

<?= nl2br(htmlspecialchars($m['message'])) ?>

</div>

</div>

<?php endwhile; ?>

</div>

<hr>

<form method="POST">

<input type="hidden" name="phone" value="<?= $active_phone ?>">

<textarea
name="message"
class="form-control"
rows="2"
placeholder="Tulis pesan..."
required></textarea>

<button class="btn btn-success mt-2">
Kirim
</button>

</form>

<?php else: ?>

<p>Pilih customer untuk mulai chat</p>

<?php endif; ?>

</div>

</div>

</div>

</div>

</div>

<script>

/* auto scroll chat */

let chatBox = document.getElementById("chatArea");
if(chatBox){
chatBox.scrollTop = chatBox.scrollHeight;
}

</script>

<script>

let phone = "<?= $active_phone ?>";

function loadChat(){

if(!phone) return;

fetch("load_chat.php?phone="+phone)
.then(res=>res.text())
.then(data=>{
document.getElementById("chatArea").innerHTML = data;

let box = document.getElementById("chatArea");
box.scrollTop = box.scrollHeight;
});

}

/* refresh setiap 2 detik */

setInterval(loadChat,2000);

</script>

<script>

const textarea = document.querySelector("textarea[name='message']");

if(textarea){

textarea.addEventListener("keypress",function(e){

if(e.key === "Enter" && !e.shiftKey){
e.preventDefault();
this.form.submit();
}

});

}

</script>

<?php include "partials/footer.php"; ?>