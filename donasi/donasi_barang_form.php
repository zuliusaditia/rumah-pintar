<form action="donasi/process_donasi_barang.php" method="POST">
    
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

<button class="btn btn-primary w-100">
Kirim Donasi Barang
</button>

</form>

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