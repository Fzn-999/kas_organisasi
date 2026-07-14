<?php
include 'header.php';

// Proses Tambah Data
if(isset($_POST['simpan'])){
    $tanggal = $_POST['tanggal'];
    $kategori_id = $_POST['kategori'];
    $nominal = $_POST['nominal'];
    $keterangan = $_POST['keterangan'];
    
    $query = "INSERT INTO kas (tanggal, kategori_id, jenis, nominal, keterangan) VALUES ('$tanggal', '$kategori_id', 'masuk', '$nominal', '$keterangan')";
    mysqli_query($conn, $query);
    echo "<script>window.location='kas_masuk.php';</script>";
}

// Proses Hapus Data
if(isset($_GET['hapus'])){
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM kas WHERE id=$id");
    echo "<script>window.location='kas_masuk.php';</script>";
}

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
?>

<!-- Form Input Tersembunyi (Akan Muncul saat Tombol Ditekan) -->
<div class="card shadow-sm mb-4 d-none" id="formTambahKas">
    <div class="card-header bg-white fw-bold">Form Transaksi Kas Masuk</div>
    <div class="card-body">
        <form action="" method="POST" class="row g-3">
            <div class="col-md-3">
                <label class="form-label small">Tanggal</label>
                <input type="date" class="form-control" name="tanggal" required value="2026-06-04">
            </div>
            <div class="col-md-3">
                <label class="form-label small">Kategori</label>
                <select class="form-select" name="kategori" required>
                    <?php 
                    $kat = mysqli_query($conn, "SELECT * FROM kategori WHERE jenis='masuk'");
                    while($k = mysqli_fetch_assoc($kat)) {
                        echo "<option value='".$k['id']."'>".$k['nama_kategori']."</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small">Nominal (Rp)</label>
                <input type="number" class="form-control" name="nominal" placeholder="Masukkan nominal" required>
            </div>
            <div class="col-md-3">
                <label class="form-label small">Keterangan</label>
                <input type="text" class="form-control" name="keterangan" placeholder="Masukkan keterangan" required>
            </div>
            <div class="col-12">
                <button type="submit" name="simpan" class="btn btn-primary btn-sm px-4">Simpan</button>
                <button type="button" class="btn btn-secondary btn-sm px-4" onclick="toggleForm()">Batal</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span class="fw-bold">Kas Masuk</span>
        <!-- Tombol Tambah Data Kas Sesuai Layout Gambar -->
        <button class="btn btn-success btn-sm fw-bold" onclick="toggleForm()">+ Tambah Data</button>
    </div>
    <div class="p-3 bg-light border-bottom d-flex justify-content-end">
        <form action="" method="GET" class="d-flex" style="width: 250px;">
            <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Cari data..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-outline-secondary btn-sm">Cari</button>
        </form>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped table-hover small">
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>Tanggal</th>
                    <th>Kategori</th>
                    <th>Nominal (Rp)</th>
                    <th>Keterangan</th>
                    <th width="100">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $q_filter = $search != '' ? "AND (keterangan LIKE '%$search%' OR nama_kategori LIKE '%$search%')" : "";
                $res = mysqli_query($conn, "SELECT kas.*, kategori.nama_kategori FROM kas JOIN kategori ON kas.kategori_id=kategori.id WHERE kas.jenis='masuk' $q_filter ORDER BY kas.tanggal ASC");
                $no = 1;
                while($row = mysqli_fetch_assoc($res)):
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                    <td><?= $row['nama_kategori'] ?></td>
                    <td><?= number_format($row['nominal'], 0, ',', '.') ?></td>
                    <td><?= $row['keterangan'] ?></td>
                    <td>
                        <a href="kas_masuk.php?hapus=<?= $row['id'] ?>" class="btn btn-sm text-danger p-0" onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')"><i class="bi bi-trash fs-6"></i></a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</div>
</div>
</div>
</div>
<script>
function toggleForm() {
    var element = document.getElementById("formTambahKas");
    element.classList.toggle("d-none");
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>