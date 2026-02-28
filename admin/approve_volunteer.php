<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "session_config.php";
include "../koneksi.php";

// ==========================
// LOAD PHPMailer (WAJIB DI ATAS)
// ==========================
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require '../PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ==========================
// CEK LOGIN ADMIN
// ==========================
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// ==========================
// HANYA BOLEH POST
// ==========================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Akses tidak valid.");
}

// ==========================
// VALIDASI CSRF
// ==========================
if (!isset($_POST['csrf_token']) || 
    $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("CSRF token tidak valid.");
}

// ==========================
// VALIDASI INPUT
// ==========================
$id = (int) $_POST['id'];
$status = $_POST['status'];

if (!in_array($status, ['approved','rejected'])) {
    die("Status tidak valid.");
}

// ==========================
// UPDATE STATUS
// ==========================
$stmt = $conn->prepare("UPDATE volunteers SET status=? WHERE id=?");
$stmt->bind_param("si", $status, $id);
$stmt->execute();
$stmt->close();

// ==========================
// JIKA APPROVED → KIRIM EMAIL
// ==========================
if ($status === 'approved') {

    $stmt = $conn->prepare("SELECT nama,email FROM volunteers WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $vol = $result->fetch_assoc();
    $stmt->close();

    if ($vol) {

        $mail = new PHPMailer(true);

        try {

            // ======================
            // KONFIG SMTP
            // ======================
            $mail->isSMTP();
            $mail->Host       = MAIL_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = MAIL_USER; // GANTI
            $mail->Password   = MAIL_PASS; // GANTI (tanpa spasi)
            $mail->SMTPSecure = 'tls';
            $mail->Port       = MAIL_PORT;

            // ======================
            // EMAIL CONTENT
            // ======================
            $mail->setFrom(MAIL_USER, 'Rumah Pintar');
            $mail->addAddress($vol['email'], $vol['nama']);

            $mail->isHTML(true);
            $mail->Subject = 'Selamat! Anda Diterima sebagai Relawan';

            $mail->Body = "
                Halo <b>{$vol['nama']}</b>,<br><br>
                Selamat! Anda telah diterima sebagai relawan Rumah Pintar 🎉<br><br>
                Tim kami akan segera menghubungi Anda untuk koordinasi lebih lanjut.<br><br>
                Terima kasih atas kepedulian dan semangat Anda ❤️<br><br>
                Salam hangat,<br>
                <b>Tim Rumah Pintar</b>
            ";

            $mail->send();

        } catch (Exception $e) {
            echo "Mailer Error: " . $mail->ErrorInfo;
            exit;
        }
    }
}

// ==========================
// REDIRECT KEMBALI
// ==========================
header("Location: list_volunteer.php");
exit;