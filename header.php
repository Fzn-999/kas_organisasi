<?php
include 'koneksi.php';
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Kas Organisasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background-color: #1e293b; color: white; }
        .sidebar .nav-link { color: #94a3b8; border-radius: 5px; margin: 5px 15px; }
        .sidebar .nav-link.active, .sidebar .nav-link:hover { color: white; background-color: #0284c7; }
        .header-top { background-color: white; border-bottom: 1px solid #e2e8f0; padding: 15px 30px; }
        .content-area { padding: 30px; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 p-0 sidebar">
            <div class="p-3 text-center border-bottom border-secondary">
                <i class="bi bi-wallet2 fs-3 text-info"></i>
                <h6 class="mt-2 fw-bold text-white small">SISTEM KAS ORGANISASI</h6>
            </div>
            <ul class="nav flex-column mt-3">
                <li class="nav-item">
                    <a class="nav-link <?= $current_page == 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $current_page == 'kas_masuk.php' ? 'active' : ''; ?>" href="kas_masuk.php">
                        <i class="bi bi-box-arrow-in-down me-2"></i> Kas Masuk
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $current_page == 'kas_keluar.php' ? 'active' : ''; ?>" href="kas_keluar.php">
                        <i class="bi bi-box-arrow-up me-2"></i> Kas Keluar
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $current_page == 'anggota.php' ? 'active' : ''; ?>" href="anggota.php">
                        <i class="bi bi-people me-2"></i> Data Anggota
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $current_page == 'laporan.php' ? 'active' : ''; ?>" href="laporan.php">
                        <i class="bi bi-file-earmark-text me-2"></i> Laporan
                    </a>
                </li>
                <li class="nav-item mt-5">
                    <a class="nav-link text-danger" href="logout.php">
                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Main Content Wrapper -->
        <div class="col-md-10 p-0">
            <!-- Navbar Header -->
            <div class="header-top d-flex justify-content-between align-items-center">
                <h5 class="m-0 fw-bold text-secondary">
                    <?php 
                        if($current_page == 'dashboard.php') echo 'Dashboard';
                        elseif($current_page == 'kas_masuk.php') echo 'Kas Masuk';
                        elseif($current_page == 'kas_keluar.php') echo 'Kas Keluar';
                        elseif($current_page == 'anggota.php') echo 'Data Anggota';
                        elseif($current_page == 'laporan.php') echo 'Laporan Keuangan';
                    ?>
                </h5>
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1 text-primary"></i> <?= $_SESSION['nama_lengkap'] ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="content-area">