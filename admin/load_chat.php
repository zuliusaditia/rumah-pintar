<?php
include "../koneksi.php";

$phone = $_GET['phone'] ?? '';

if(!$phone) exit;

$messages = mysqli_query($conn,"
SELECT * FROM chats
WHERE phone='$phone'
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