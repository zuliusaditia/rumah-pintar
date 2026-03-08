<?php

include "../koneksi.php";

$settings = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT bank_name, rekening, account_holder FROM settings LIMIT 1")
);

$total = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT SUM(nominal) as total FROM donation_money WHERE status='approved'")
);

$total_donasi = $total['total'] ?? 0;

?>

<style>

.card-custom{
    background:#ffffff;
    border-radius:14px;
    padding:32px;
    box-shadow:0 8px 24px rgba(0,0,0,0.05);
    margin-bottom:24px;
}

.form-group{
    margin-bottom:18px;
}

.form-label{
    font-weight:600;
    margin-bottom:6px;
    display:block;
    text-align:left;
}

.form-control{
    padding:10px 14px;
    border-radius:8px;
}

.nominal-group{
    display:flex;
    gap:8px;
    flex-wrap:wrap;
    margin-bottom:10px;
}

.nominal-btn{
    border:1px solid #d9d9d9;
    background:white;
    padding:6px 14px;
    border-radius:20px;
    font-size:14px;
    cursor:pointer;
    transition:0.2s;
}

.nominal-btn:hover{
    background:#2F4B8F;
    color:white;
    border-color:#2F4B8F;
}

.rekening-box{
    background:#f8f9fa;
    border-radius:14px;
    padding:28px;
    text-align:center;
}

.rekening-number{
    display:flex;
    justify-content:center;
    align-items:center;
    gap:10px;
    font-size:24px;
    font-weight:600;
    margin-bottom:8px;
}

.copy-btn{
    border:none;
    background:#2F4B8F;
    color:white;
    padding:6px 14px;
    border-radius:20px;
    font-size:13px;
    cursor:pointer;
    transition:0.2s;
}

.copy-btn:hover{
    background:#243c73;
}

.total-box{
    text-align:center;
}

.total-box h2{
    font-weight:700;
    color:#198754;
}

.submit-btn{
    background:#2F4B8F;
    border:none;
    padding:10px;
    border-radius:8px;
    font-weight:600;
    color: #ffffff;
}

.submit-btn:hover{
    background:#243c73;
}

</style>


<!-- REKENING INFO -->

<div class="card-custom rekening-box">

    <h5 class="fw-bold mb-3">
        Transfer Donasi ke Rekening
    </h5>

    <div class="rekening-number">

        <span id="rekeningText">
            <?= htmlspecialchars($settings['rekening']) ?>
        </span>

        <button onclick="copyRekening()" class="copy-btn">
            Copy
        </button>

    </div>

    <h6 class="mb-1">
        <?= htmlspecialchars($settings['bank_name']) ?>
    </h6>

    <p class="text-muted mb-0">
        a.n <?= htmlspecialchars($settings['account_holder']) ?>
    </p>

</div>


<!-- TOTAL DONASI -->

<div class="card-custom total-box">

    <h6 class="text-muted">
        Total Dana Terkumpul
    </h6>

    <h2>
        Rp <?= number_format($total_donasi, 0, ',', '.') ?>
    </h2>

    <p class="text-muted mb-0">
        Terima kasih kepada para donatur
    </p>

</div>


<!-- FORM DONASI -->

<div class="card-custom">

    <h5 class="fw-bold text-center mb-4">
        Konfirmasi Donasi
    </h5>

    <form method="POST" action="donasi/process_donasi_uang.php" enctype="multipart/form-data">

        <div class="form-group">

            <label class="form-label">
                Nama Lengkap
            </label>

            <input
                type="text"
                name="nama"
                class="form-control"
                required
            >

        </div>


        <div class="form-group">

            <label class="form-label">
                No WhatsApp
            </label>

            <input
                type="text"
                name="no_hp"
                class="form-control"
                required
            >

        </div>


        <div class="form-group">

            <label class="form-label">
                Nominal Donasi
            </label>

            <div class="nominal-group">

                <button type="button" class="nominal-btn" onclick="setNominal(50000)">
                    50rb
                </button>

                <button type="button" class="nominal-btn" onclick="setNominal(100000)">
                    100rb
                </button>

                <button type="button" class="nominal-btn" onclick="setNominal(250000)">
                    250rb
                </button>

                <button type="button" class="nominal-btn" onclick="setNominal(500000)">
                    500rb
                </button>

            </div>

            <input
                type="number"
                name="nominal"
                id="nominalInput"
                class="form-control"
                required
            >

        </div>


        <div class="form-group">

            <label class="form-label">
                Upload Bukti Transfer
            </label>

            <input
                type="file"
                name="bukti"
                class="form-control"
                required
            >

        </div>


        <button class="btn submit-btn w-100">
            Kirim Donasi
        </button>

    </form>

</div>


<script>

function setNominal(value){
    document.getElementById("nominalInput").value = value;
}

function copyRekening(){

    const text = document.getElementById("rekeningText").innerText;

    navigator.clipboard.writeText(text).then(function(){

        alert("Nomor rekening berhasil disalin!");

    });

}

</script>