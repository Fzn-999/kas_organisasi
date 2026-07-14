<?php
include 'header.php';
$bulan_pilihan = isset($_GET['bulan']) ? $_GET['bulan'] : '2026-06';
?>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white fw-bold">Filter Laporan</div>
    <div class="card-body">
        <form action="" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label small">Pilih Periode (Bulan - Tahun)</label>
                <input type="month" class="form-control" name="bulan" value="<?= $bulan_pilihan ?>" required>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary btn-sm px-4">Tampilkan</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span class="fw-bold">Laporan Keuangan - Periode <?= date('F Y', strtotime($bulan_pilihan)) ?></span>
        <div>
            <button type="button" class="btn btn-success btn-sm me-1" onclick="window.print()"><i class="bi bi-file-pdf"></i> Cetak PDF</button>
            <!-- Fungsi Export Excel Asli Menggunakan JavaScript Blob -->
            <button type="button" class="btn btn-success btn-sm" onclick="exportTableToExcel('tabelLaporan', 'Laporan_Kas_<?= $bulan_pilihan ?>')"><i class="bi bi-file-excel"></i> Export Excel</button>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped small" id="tabelLaporan">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Keterangan</th>
                    <th>Kas Masuk (Rp)</th>
                    <th>Kas Keluar (Rp)</th>
                    <th>Saldo (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $res = mysqli_query($conn, "SELECT kas.*, kategori.nama_kategori FROM kas JOIN kategori ON kas.kategori_id=kategori.id WHERE DATE_FORMAT(kas.tanggal, '%Y-%m') = '$bulan_pilihan' ORDER BY kas.tanggal ASC, kas.id ASC");
                
                $total_masuk = 0;
                $total_keluar = 0;
                $running_balance = 0;

                if(mysqli_num_rows($res) > 0):
                    while($row = mysqli_fetch_assoc($res)):
                        if ($row['jenis'] == 'masuk') {
                            $masuk = $row['nominal'];
                            $keluar = 0;
                            $running_balance += $masuk;
                            $total_masuk += $masuk;
                        } else {
                            $masuk = 0;
                            $keluar = $row['nominal'];
                            $running_balance -= $keluar;
                            $total_keluar += $keluar;
                        }
                ?>
                <tr>
                    <td><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                    <td><?= $row['keterangan'] ?></td>
                    <td><?= $masuk > 0 ? number_format($masuk, 0, ',', '.') : '-' ?></td>
                    <td><?= $keluar > 0 ? number_format($keluar, 0, ',', '.') : '-' ?></td>
                    <td><?= number_format($running_balance, 0, ',', '.') ?></td>
                </tr>
                <?php 
                    endwhile; 
                endif; 
                ?>
            </tbody>
            <tfoot>
                <tr class="fw-bold">
                    <td colspan="2">TOTAL</td>
                    <td><?= number_format($total_masuk, 0, ',', '.') ?></td>
                    <td><?= number_format($total_keluar, 0, ',', '.') ?></td>
                    <td class="text-success"><?= number_format($running_balance, 0, ',', '.') ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

</div>
</div>
</div>
</div>

<script>
// Fungsi JavaScript untuk ekspor langsung ke bentuk dokumen Excel asli (.xls)
function exportTableToExcel(tableID, filename = ''){
    var downloadLink;
    var dataType = 'application/vnd.ms-excel';
    var tableSelect = document.getElementById(tableID);
    var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
    
    filename = filename?filename+'.xls':'excel_data.xls';
    downloadLink = document.createElement("a");
    document.body.appendChild(downloadLink);
    
    if(navigator.msSaveOrOpenBlob){
        var blob = new Blob(['\ufeff' + tableHTML], { type: dataType });
        navigator.msSaveOrOpenBlob( blob, filename);
    } else {
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
        downloadLink.download = filename;
        downloadLink.click();
    }
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>