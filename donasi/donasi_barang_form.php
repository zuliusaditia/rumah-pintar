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
}

.form-control,
.form-select{
    padding:10px 14px;
    border-radius:8px;
}

.barang-row{
    display:flex;
    gap:10px;
    align-items:center;
    margin-bottom:10px;
}

.barang-row select{
    flex:2;
}

.barang-row input{
    flex:1;
}

.remove-btn{
    background:#ff4d4f;
    border:none;
    color:white;
    border-radius:8px;
    padding:6px 10px;
    cursor:pointer;
    transition:0.2s;
}

.remove-btn:hover{
    background:#d9363e;
}

.add-btn{
    border:1px dashed #2F4B8F;
    background:#f5f7ff;
    color:#2F4B8F;
    padding:8px 16px;
    border-radius:8px;
    font-weight:600;
    cursor:pointer;
    transition:0.2s;
}

.add-btn:hover{
    background:#e8ecff;
}

.submit-btn{
    background:#2F4B8F;
    border:none;
    padding:10px;
    border-radius:8px;
    font-weight:600;
}

.submit-btn:hover{
    background:#243c73;
}

.helper-text{
    font-size:13px;
    color:#6c757d;
}

</style>


<div class="card-custom">

<h5 class="fw-bold text-center mb-4">
Konfirmasi Donasi Barang
</h5>


<form action="donasi/process_donasi_barang.php" method="POST" enctype="multipart/form-data">


<!-- NAMA -->

<div class="form-group">

<label class="form-label">
Nama Lengkap
</label>

<input
type="text"
name="nama"
class="form-control"
required>

</div>


<!-- WHATSAPP -->

<div class="form-group">

<label class="form-label">
No WhatsApp
</label>

<input
type="text"
name="no_hp"
class="form-control"
required>

</div>


<!-- BARANG -->

<div class="form-group">

<label class="form-label">
Barang Donasi
</label>

<p class="helper-text">
Maksimal total 5 barang
</p>

<div id="barang-wrapper">

<div class="barang-row">

<select name="jenis_barang[]" class="form-select barang-select" required>

<option value="">Pilih Barang</option>
<option value="tas">Tas</option>
<option value="sepatu">Sepatu</option>
<option value="jam_tangan">Jam Tangan</option>
<option value="baju">Baju</option>

</select>

<input
type="number"
name="jumlah[]"
class="form-control jumlah-input"
placeholder="Jumlah"
min="1"
required>

<button
type="button"
class="remove-btn">
✕
</button>

</div>

</div>


<button
type="button"
class="add-btn mt-2"
onclick="addBarang()">

+ Tambah Barang

</button>

</div>


<!-- FOTO -->

<div class="form-group">

<label class="form-label">
Upload Foto Barang
</label>

<input
type="file"
name="foto"
class="form-control"
required>

</div>


<button class="btn submit-btn w-100">

Kirim Donasi Barang

</button>

</form>

</div>



<script>

const maxLimit = {
    tas: 1,
    sepatu: 1,
    jam_tangan: 1,
    baju: 3
};

const maxTotal = 5;



function calculateTotal(){

let total = 0;

document.querySelectorAll(".jumlah-input").forEach(input=>{
    total += parseInt(input.value) || 0;
});

return total;

}



function addBarang(){

const wrapper = document.getElementById("barang-wrapper");

const div = document.createElement("div");

div.classList.add("barang-row");

div.innerHTML = `

<select name="jenis_barang[]" class="form-select barang-select" required>

<option value="">Pilih Barang</option>
<option value="tas">Tas</option>
<option value="sepatu">Sepatu</option>
<option value="jam_tangan">Jam Tangan</option>
<option value="baju">Baju</option>

</select>

<input
type="number"
name="jumlah[]"
class="form-control jumlah-input"
placeholder="Jumlah"
min="1"
required>

<button type="button" class="remove-btn">
✕
</button>

`;

wrapper.appendChild(div);

}



document.addEventListener("input", function(e){

if(e.target.classList.contains("jumlah-input")){

const row = e.target.closest(".barang-row");

const jenis = row.querySelector(".barang-select").value;

const jumlah = parseInt(e.target.value);



if(jenis && jumlah > maxLimit[jenis]){

alert("Jumlah melebihi batas maksimal untuk " + jenis);

e.target.value = maxLimit[jenis];

}



if(calculateTotal() > maxTotal){

alert("Total maksimal 5 barang.");

e.target.value = 0;

}

}

});



document.addEventListener("click", function(e){

if(e.target.classList.contains("remove-btn")){

e.target.closest(".barang-row").remove();

}

});

</script>