<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "session_config.php";
include "../koneksi.php";

// ==========================
// CEK LOGIN ADMIN
// ==========================
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// ==========================
// VALIDASI ID
// ==========================
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID tidak valid.");
}

$id = (int) $_GET['id'];

// ==========================
// AMBIL DATA VOLUNTEER
// ==========================
$stmt = $conn->prepare("SELECT nama, created_at FROM volunteers WHERE id=? AND status='approved'");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$vol = $result->fetch_assoc();
$stmt->close();

if (!$vol) {
    die("Volunteer belum approved atau tidak ditemukan.");
}

// ==========================
// LOAD FPDF
// ==========================
require __DIR__ . '/../fpdf186/fpdf.php';

$pdf = new FPDF('L', 'mm', 'A4');
$pdf->AddPage();

// ==========================
// BACKGROUND (optional)
// ==========================
// Kalau nanti mau pakai background image, bisa tambahkan:
// $pdf->Image('../assets/certificate_bg.jpg',0,0,297,210);

// ==========================
// TITLE
// ==========================
$pdf->SetFont('Arial', 'B', 30);
$pdf->Cell(0, 30, 'SERTIFIKAT RELAWAN', 0, 1, 'C');

// ==========================
// SUBTITLE
// ==========================
$pdf->SetFont('Arial', '', 18);
$pdf->Cell(0, 10, 'Diberikan kepada:', 0, 1, 'C');

// ==========================
// NAMA
// ==========================
$pdf->SetFont('Arial', 'B', 26);
$pdf->Cell(0, 20, $vol['nama'], 0, 1, 'C');

// ==========================
// DESKRIPSI
// ==========================
$pdf->SetFont('Arial', '', 16);
$pdf->MultiCell(0, 10,
    "Atas kontribusi dan dedikasinya sebagai Relawan\n".
    "di Yayasan Rumah Pintar.\n\n".
    "Semoga semangat kepedulian ini terus tumbuh\n".
    "dan memberikan dampak positif bagi sesama.",
    0,
    'C'
);

// ==========================
// TANGGAL
// ==========================
$pdf->Ln(10);
$pdf->SetFont('Arial', '', 14);
$pdf->Cell(0, 10, "Diberikan pada: " . date('d F Y'), 0, 1, 'C');

// ==========================
// OUTPUT PDF
// ==========================
$pdf->Output('I', 'Sertifikat_'.$vol['nama'].'.pdf');
exit;