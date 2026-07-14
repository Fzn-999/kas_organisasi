<?php
include 'header.php';

// Hitung ringkasan keuangan
$masuk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) as total FROM kas WHERE jenis='masuk'"))['total'] ?? 0;
$keluar = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) as total FROM kas WHERE jenis='keluar'"))['total'] ?? 0;
$saldo = $masuk - $keluar;
$anggota = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM anggota"))['total'] ?? 0;

// Ambil data untuk grafik (6 bulan terakhir di tahun 2026)
$months = ['01', '02', '03', '04', '05', '06'];
$chart_masuk = [];
$chart_keluar = [];

foreach ($months as $m) {
    $m_query = "2026-" . $m;
    $in = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) as total FROM kas WHERE jenis='masuk' AND DATE_FORMAT(tanggal, '%Y-%m') = '$m_query'"))['total'] ?? 0;
    $out = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) as total FROM kas WHERE jenis='keluar' AND DATE_FORMAT(tanggal, '%Y-%m') = '$m_query'"))['total'] ?? 0;
    
    $chart_masuk[] = $in;
    $chart_keluar[] = $out;
}
?>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary mb-3 shadow-sm">
            <div class="card-body">
                <h6 class="card-title text-uppercase small">TOTAL KAS MASUK</h6>
                <h3>Rp <?= number_format($masuk, 0, ',', '.') ?></h3>
                <hr class="my-2">
                <a href="kas_masuk.php" class="text-white text-decoration-none small">Lihat Detail <i class="bi bi-arrow-right-circle"></i></a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-danger mb-3 shadow-sm">
            <div class="card-body">
                <h6 class="card-title text-uppercase small">TOTAL KAS KELUAR</h6>
                <h3>Rp <?= number_format($keluar, 0, ',', '.') ?></h3>
                <hr class="my-2">
                <a href="kas_keluar.php" class="text-white text-decoration-none small">Lihat Detail <i class="bi bi-arrow-right-circle"></i></a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success mb-3 shadow-sm">
            <div class="card-body">
                <h6 class="card-title text-uppercase small">SALDO KAS</h6>
                <h3>Rp <?= number_format($saldo, 0, ',', '.') ?></h3>
                <hr class="my-2">
                <a href="laporan.php" class="text-white text-decoration-none small">Lihat Detail <i class="bi bi-arrow-right-circle"></i></a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning mb-3 shadow-sm">
            <div class="card-body">
                <h6 class="card-title text-uppercase small">JUMLAH ANGGOTA</h6>
                <h3><?= $anggota ?> Orang</h3>
                <hr class="my-2">
                <a href="anggota.php" class="text-white text-decoration-none small">Lihat Detail <i class="bi bi-arrow-right-circle"></i></a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Grafik Nyata Dinamis -->
    <div class="col-md-8">
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-white fw-bold">Grafik Pemasukan dan Pengeluaran</div>
            <div class="card-body">
                <canvas id="keuanganChart" style="height: 250px; width: 100%;"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Transaksi Terbaru -->
    <div class="col-md-4">
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
                <span>Transaksi Terbaru</span>
                <a href="laporan.php" class="btn btn-primary btn-sm px-2 py-0" style="font-size: 11px;">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <?php 
                    $recent = mysqli_query($conn, "SELECT kas.*, kategori.nama_kategori FROM kas JOIN kategori ON kas.kategori_id=kategori.id ORDER BY kas.tanggal DESC, kas.id DESC LIMIT 4");
                    while($row = mysqli_fetch_assoc($recent)):
                    ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <div>
                            <small class="text-muted" style="font-size: 11px;"><?= date('d/m/Y', strtotime($row['tanggal'])) ?> &bull; <?= $row['nama_kategori'] ?></small>
                            <h6 class="mb-0 small fw-bold text-dark"><?= $row['keterangan'] ?></h6>
                        </div>
                        <span class="fw-bold <?= $row['jenis'] == 'masuk' ? 'text-success' : 'text-danger' ?>">
                            <?= $row['jenis'] == 'masuk' ? '+' : '-' ?><?= number_format($row['nominal'], 0, ',', '.') ?>
                        </span>
                    </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

</div> 
</div> 
</div> 
</div> 

<!-- Load Chart.js CDN untuk merender grafik otomatis -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('keuanganChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [
            {
                label: 'Kas Masuk',
                data: <?= json_encode($chart_masuk) ?>,
                borderColor: '#10b981',
                backgroundColor: '#10b981',
                tension: 0.3,
                borderWidth: 2
            },
            {
                label: 'Kas Keluar',
                data: <?= json_encode($chart_keluar) ?>,
                borderColor: '#ef4444',
                backgroundColor: '#ef4444',
                tension: 0.3,
                borderWidth: 2
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'top' }
        }
    }
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>