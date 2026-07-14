<?php
include 'header.php';

// Proses Tambah Anggota
if(isset($_POST['tambah'])){
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $jurusan = $_POST['jurusan'];
    $angkatan = $_POST['angkatan'];
    
    mysqli_query($conn, "INSERT INTO anggota (nim, nama, jurusan, angkatan) VALUES ('$nim', '$nama', '$jurusan', '$angkatan')");
    echo "<script>window.location='anggota.php';</script>";
}

// Proses Hapus Anggota
if(isset($_GET['hapus'])){
    $nim = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM anggota WHERE nim='$nim'");
    echo "<script>window.location='anggota.php';</script>";
}

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
?>

<!-- Form Input Tersembunyi (Toggle) -->
<div class="card shadow-sm mb-4 d-none" id="formTambahAnggota">
    <div class="card-header bg-white fw-bold">Form Tambah Anggota</div>
    <div class="card-body">
        <form action="" method="POST" class="row g-3">
            <div class="col-md-3">
                <label class="form-label small">NIM</label>
                <input type="text" class="form-control" name="nim" placeholder="Masukkan NIM" required>
            </div>
            <div class="col-md-3">
                <label class="form-label small">Nama Lengkap</label>
                <input type="text" class="form-control" name="nama" placeholder="Masukkan Nama" required>
            </div>
            <div class="col-md-3">
                <label class="form-label small">Jurusan</label>
                <input type="text" class="form-control" name="jurusan" placeholder="Masukkan Jurusan" required>
            </div>
            <div class="col-md-3">
                <label class="form-label small">Angkatan</label>
                <input type="number" class="form-control" name="angkatan" placeholder="Contoh: 2024" required>
            </div>
            <div class="col-12">
                <button type="submit" name="tambah" class="btn btn-primary btn-sm px-4">Simpan</button>
                <button type="button" class="btn btn-secondary btn-sm px-4" onclick="toggleForm()">Batal</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span class="fw-bold">Data Anggota</span>
        <button class="btn btn-success btn-sm fw-bold" onclick="toggleForm()">+ Tambah Anggota</button>
    </div>
    <div class="p-3 bg-light border-bottom d-flex justify-content-end">
        <form action="" method="GET" class="d-flex" style="width: 250px;">
            <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Cari anggota..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-outline-secondary btn-sm">Cari</button>
        </form>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped table-hover small">
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Jurusan</th>
                    <th>Angkatan</th>
                    <th width="100">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $q_filter = $search != '' ? "WHERE nama LIKE '%$search%' OR nim LIKE '%$search%' OR jurusan LIKE '%$search%'" : "";
                $res = mysqli_query($conn, "SELECT * FROM anggota $q_filter ORDER BY nim ASC");
                $no = 1;
                while($row = mysqli_fetch_assoc($res)):
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $row['nim'] ?></td>
                    <td><?= $row['nama'] ?></td>
                    <td><?= $row['jurusan'] ?></td>
                    <td><?= $row['angkatan'] ?></td>
                    <td>
                        <a href="anggota.php?hapus=<?= $row['nim'] ?>" class="btn btn-sm text-danger p-0" onclick="return confirm('Apakah anda yakin ingin menghapus anggota ini?')"><i class="bi bi-trash fs-6"></i></a>
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
    var element = document.getElementById("formTambahAnggota");
    element.classList.toggle("d-none");
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>