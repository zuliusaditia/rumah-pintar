<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "koneksi.php";
include "includes/header.php";

$success = false;
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nama = trim($_POST['nama']);
    $no_hp = trim($_POST['no_hp']);

    if (empty($_POST['jenis_barang'])) {
        $error_message = "Minimal pilih 1 barang.";
    }

    $tas = 0;
    $sepatu = 0;
    $jam = 0;
    $baju = 0;

    $allowed_barang = [
        "tas" => 1,
        "sepatu" => 1,
        "jam_tangan" => 1,
        "baju" => 3
    ];

    if (empty($error_message)) {

        $total_barang = 0;

        foreach ($_POST['jenis_barang'] as $index => $jenis) {

            $jumlah = (int) $_POST['jumlah'][$index];

            if (!array_key_exists($jenis, $allowed_barang)) {
                $error_message = "Jenis barang tidak valid.";
                break;
            }

            if ($jumlah <= 0 || $jumlah > $allowed_barang[$jenis]) {
                $error_message = "Jumlah melebihi batas maksimal untuk $jenis.";
                break;
            }

            $total_barang += $jumlah;

            if ($total_barang > 5) {
                $error_message = "Total maksimal 5 barang.";
                break;
            }

            if ($jenis == "tas") $tas += $jumlah;
            if ($jenis == "sepatu") $sepatu += $jumlah;
            if ($jenis == "jam_tangan") $jam += $jumlah;
            if ($jenis == "baju") $baju += $jumlah;
        }
    }

    // ======================
    // VALIDASI FILE
    // ======================
    if (!isset($_FILES['foto']) || $_FILES['foto']['error'] !== 0) {
        $error_message = "File tidak valid.";
    }

    if (empty($error_message)) {

        $allowed_ext = ['jpg','jpeg','png'];
        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $file_size = $_FILES['foto']['size'];

        if (!in_array($ext, $allowed_ext)) {
            $error_message = "Format hanya JPG, JPEG, PNG.";
        }

        if ($file_size > 2 * 1024 * 1024) {
            $error_message = "Ukuran maksimal 2MB.";
        }

        if (empty($error_message)) {

            $new_name = uniqid("barang_", true) . "." . $ext;
            $folder = "uploads/";

            if (move_uploaded_file($_FILES['foto']['tmp_name'], $folder . $new_name)) {

                $status = "pending";

                $stmt = $conn->prepare(
                    "INSERT INTO donation_barang 
                    (nama, no_hp, tas, sepatu, jam_tangan, baju, foto, status)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
                );

                $stmt->bind_param(
                    "ssiiiiss",
                    $nama,
                    $no_hp,
                    $tas,
                    $sepatu,
                    $jam,
                    $baju,
                    $new_name,
                    $status
                );

                if ($stmt->execute()) {
                    $success = true;
                } else {
                    $error_message = "Terjadi kesalahan sistem.";
                }

                $stmt->close();

            } else {
                $error_message = "Upload gagal.";
            }
        }
    }
}
?>

<section class="section section-soft text-center">
    <div class="container">
        <h1 class="fw-bold">Donasi Barang</h1>
        <p class="text-muted">Salurkan perlengkapan sekolah untuk anak-anak Rumah Pintar</p>
    </div>
</section>

<section class="section">
    <div class="container" style="max-width:700px;">

        <div class="card-custom">

            <h4 class="fw-bold text-center mb-4">Form Donasi Barang</h4>

            <?php if ($success) { ?>
                <div class="alert alert-success text-center">
                    Donasi barang berhasil dikirim. Menunggu verifikasi admin.
                </div>
            <?php } ?>

            <?php if (!empty($error_message)) { ?>
                <div class="alert alert-danger text-center">
                    <?php echo $error_message; ?>
                </div>
            <?php } ?>

            <form method="POST" enctype="multipart/form-data">

                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">No WhatsApp</label>
                    <input type="text" name="no_hp" class="form-control" required>
                </div>

                <div id="barang-wrapper">

                    <div class="barang-item row mb-3">
                        <div class="col-md-6">
                            <select name="jenis_barang[]" class="form-select barang-select" required>
                                <option value="">-- Pilih Barang --</option>
                                <option value="tas">Tas</option>
                                <option value="sepatu">Sepatu</option>
                                <option value="jam_tangan">Jam Tangan</option>
                                <option value="baju">Baju</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <input type="number" name="jumlah[]" class="form-control jumlah-input" min="1" required>
                        </div>

                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger remove-btn">X</button>
                        </div>
                    </div>

                </div>

                <button type="button" class="btn btn-outline-custom mb-3" onclick="addBarang()">
                    + Tambah Barang
                </button>

                <div class="mb-3">
                    <label class="form-label">Upload Foto Barang</label>
                    <input type="file" name="foto" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary-custom w-100">
                    Kirim Donasi Barang
                </button>

            </form>
        </div>
    </div>
</section>

<script>
const maxLimit = {
    tas: 1,
    sepatu: 1,
    jam_tangan: 1,
    baju: 3
};

const maxTotal = 5;

function calculateTotal() {
    let total = 0;
    document.querySelectorAll(".jumlah-input").forEach(input => {
        total += parseInt(input.value) || 0;
    });
    return total;
}

function addBarang() {

    const wrapper = document.getElementById("barang-wrapper");

    const div = document.createElement("div");
    div.classList.add("barang-item", "row", "mb-3");

    div.innerHTML = `
        <div class="col-md-6">
            <select name="jenis_barang[]" class="form-select barang-select" required>
                <option value="">-- Pilih Barang --</option>
                <option value="tas">Tas</option>
                <option value="sepatu">Sepatu</option>
                <option value="jam_tangan">Jam Tangan</option>
                <option value="baju">Baju</option>
            </select>
        </div>
        <div class="col-md-4">
            <input type="number" name="jumlah[]" class="form-control jumlah-input" min="1" required>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-danger remove-btn">X</button>
        </div>
    `;

    wrapper.appendChild(div);
}

document.addEventListener("input", function(e) {

    if (e.target.classList.contains("jumlah-input")) {

        const row = e.target.closest(".barang-item");
        const jenis = row.querySelector(".barang-select").value;
        const jumlah = parseInt(e.target.value);

        // Validasi per jenis
        if (jenis && jumlah > maxLimit[jenis]) {
            alert("Jumlah melebihi batas maksimal untuk " + jenis);
            e.target.value = maxLimit[jenis];
        }

        // Validasi total
        if (calculateTotal() > maxTotal) {
            alert("Total maksimal 5 barang.");
            e.target.value = 0;
        }
    }
});

document.addEventListener("click", function(e) {
    if (e.target.classList.contains("remove-btn")) {
        e.target.closest(".barang-item").remove();
    }
});
</script>

<?php include "includes/footer.php"; ?>