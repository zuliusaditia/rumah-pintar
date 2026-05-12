<?php
/*
|--------------------------------------------------------------------------
| GENERATE PASSWORD HASH CMS ADMIN
|--------------------------------------------------------------------------
| Cara pakai:
| 1. Simpan file ini sebagai generate_password.php di root project
| 2. Buka di browser:
|    http://localhost/rumahpintar/generate_password.php
| 3. Masukkan password baru
| 4. Copy hash yang muncul
| 5. Update ke database admin table
|--------------------------------------------------------------------------
*/

$password = "";
$hash = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $password = $_POST["password"] ?? "";

    if (!empty($password)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Generate Admin Password</title>

<style>
body{
font-family:Arial, sans-serif;
background:#f8fafc;
display:flex;
justify-content:center;
align-items:center;
min-height:100vh;
margin:0;
}

.card{
background:white;
padding:30px;
border-radius:16px;
box-shadow:0 10px 25px rgba(0,0,0,.08);
width:100%;
max-width:650px;
}

h2{
margin-top:0;
text-align:center;
}

input, textarea{
width:100%;
padding:12px;
margin-top:10px;
border:1px solid #ddd;
border-radius:10px;
font-size:14px;
box-sizing:border-box;
}

button{
width:100%;
padding:12px;
margin-top:15px;
background:#2F4B8F;
color:white;
border:none;
border-radius:10px;
cursor:pointer;
font-size:15px;
font-weight:bold;
}

button:hover{
background:#1e3a6f;
}

.result{
margin-top:20px;
background:#f1f5f9;
padding:15px;
border-radius:10px;
word-break:break-all;
}

small{
display:block;
margin-top:10px;
color:#666;
line-height:1.5;
}
</style>
</head>
<body>

<div class="card">

<h2>Generate Password CMS</h2>

<form method="POST">

<label>Password Baru</label>

<input
type="text"
name="password"
placeholder="Masukkan password baru..."
required>

<button type="submit">
Generate Hash
</button>

</form>

<?php if($hash): ?>

<div class="result">

<strong>Password:</strong><br>
<?= htmlspecialchars($password) ?>

<br><br>

<strong>Hash:</strong><br>
<textarea rows="4" readonly><?= htmlspecialchars($hash) ?></textarea>

</div>

<small>
Copy hash di atas lalu update ke database.<br><br>

Contoh SQL:<br>
UPDATE admin SET password='HASH_BARU' WHERE username='admin';
</small>

<?php endif; ?>

</div>

</body>
</html>