<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap IT - Asset Management</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        @import url("https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap");

        :root {
            --sidebar-width: 260px;
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --bg-light: #f8f9fa;
            --dark-blue: #1e1e2d;
        }

        body {
            font-family: "Plus Jakarta Sans", sans-serif;
            background-color: var(--bg-light);
            color: #2d3436;
        }

        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: var(--dark-blue);
            color: #fff;
            z-index: 1000;
            transition: all 0.3s;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 25px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .sidebar-header h4 {
            font-weight: 700;
            letter-spacing: 1px;
            margin: 0;
            color: #fff;
            font-size: 1.2rem;
        }

        .user-profile {
            padding: 15px;
            background: rgba(255,255,255,0.03);
            margin: 15px;
            border-radius: 12px;
        }

        .nav-link {
            padding: 10px 20px;
            color: rgba(255,255,255,0.7) !important;
            display: flex;
            align-items: center;
            transition: 0.2s;
            border-radius: 8px;
            margin: 2px 15px;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .nav-link i {
            width: 25px;
            font-size: 1rem;
        }

        .nav-link:hover {
            background: rgba(255,255,255,0.05);
            color: #fff !important;
        }

        .nav-link.active {
            background: var(--primary-color) !important;
            color: #fff !important;
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
        }

        .nav-section {
            padding: 15px 25px 5px;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255,255,255,0.4);
            font-weight: 700;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: 30px;
            min-height: 100vh;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.02);
            transition: transform 0.3s;
        }

        .top-navbar {
            background: #fff;
            padding: 15px 30px;
            margin-bottom: 30px;
            border-radius: 16px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.03);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>

<body>
<div class="sidebar">
    <div class="sidebar-header">
        <h4><i class="fas fa-server me-2 text-primary"></i> REKAP IT</h4>
    </div>

    <div class="user-profile text-center">
        <div class="mb-2">
            <img src="https://ui-avatars.com/api/?name=<?= $_SESSION['nama'] ?>&background=random&color=fff" class="rounded-circle" width="45">
        </div>
        <div class="small fw-bold text-white"><?= $_SESSION['nama'] ?></div>
        <div class="small text-muted" style="font-size: 0.65rem;"><?= strtoupper(str_replace('_', ' ', $_SESSION['role'])) ?></div>
    </div>

    <div class="nav flex-column">
        <a href="index.php?page=dashboard" class="nav-link <?= ($page == 'dashboard') ? 'active' : '' ?>">
            <i class="fas fa-th-large me-2"></i> Dashboard
        </a>

        <div class="nav-section">Manajemen Organisasi</div>
        <a href="index.php?page=cabang" class="nav-link <?= ($page == 'cabang') ? 'active' : '' ?>">
            <i class="fas fa-building me-2"></i> Cabang
        </a>
        <a href="index.php?page=divisi" class="nav-link <?= ($page == 'divisi') ? 'active' : '' ?>">
            <i class="fas fa-sitemap me-2"></i> Divisi
        </a>
        <a href="index.php?page=karyawan" class="nav-link <?= ($page == 'karyawan') ? 'active' : '' ?>">
            <i class="fas fa-users me-2"></i> Karyawan
        </a>

        <div class="nav-section">Inventaris & Aset</div>
        <a href="index.php?page=kategori" class="nav-link <?= ($page == 'kategori') ? 'active' : '' ?>">
            <i class="fas fa-tags me-2"></i> Kategori Aset
        </a>
        <a href="index.php?page=inventaris" class="nav-link <?= ($page == 'inventaris') ? 'active' : '' ?>">
            <i class="fas fa-laptop me-2"></i> Data Aset
        </a>
        <a href="index.php?page=mutasi" class="nav-link <?= ($page == 'mutasi') ? 'active' : '' ?>">
            <i class="fas fa-exchange-alt me-2"></i> Mutasi Aset
        </a>

        <div class="nav-section">Maintenance & Perbaikan</div>
        <a href="index.php?page=maintenance" class="nav-link <?= ($page == 'maintenance') ? 'active' : '' ?>">
            <i class="fas fa-tools me-2"></i> Maintenance
        </a>
        <a href="index.php?page=perbaikan" class="nav-link <?= ($page == 'perbaikan') ? 'active' : '' ?>">
            <i class="fas fa-wrench me-2"></i> Perbaikan
        </a>
        <a href="index.php?page=sparepart" class="nav-link <?= ($page == 'sparepart') ? 'active' : '' ?>">
            <i class="fas fa-box me-2"></i> Sparepart
        </a>

        <div class="nav-section">Laporan & Audit</div>
        <a href="index.php?page=audit" class="nav-link <?= ($page == 'audit') ? 'active' : '' ?>">
            <i class="fas fa-clipboard-check me-2"></i> Audit Aset
        </a>
        <a href="index.php?page=laporan" class="nav-link <?= ($page == 'laporan') ? 'active' : '' ?>">
            <i class="fas fa-file-alt me-2"></i> Laporan
        </a>

        <div class="nav-section">Sistem</div>
        <a href="index.php?page=pengguna" class="nav-link <?= ($page == 'pengguna') ? 'active' : '' ?>">
            <i class="fas fa-user-cog me-2"></i> Manajemen User
        </a>
        <a href="logout.php" class="nav-link text-danger mt-3">
            <i class="fas fa-sign-out-alt me-2"></i> Logout
        </a>
    </div>
</div>

<div class="main-content">
    <div class="top-navbar">
        <h5 class="m-0 fw-bold"><?= ucwords(str_replace('_', ' ', $page)) ?> Overview</h5>
        <div class="d-flex align-items-center">
            <span class="text-muted small me-3"><i class="far fa-calendar-alt me-1"></i> <?= date('d M Y') ?></span>
            <div class="vr me-3"></div>
            <i class="far fa-bell text-muted"></i>
        </div>
    </div>
