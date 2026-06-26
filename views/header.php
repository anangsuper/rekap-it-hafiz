<?php
require_once 'models/Maintenance.php';
$mModel = new Maintenance($conn);
$notifications = $mModel->getUpcomingNotifications(7);
$notifCount = count($notifications);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap IT - Asset Management</title>

    <!-- Fonts & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        @import url("https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap");

        :root {
            /* Premium Futuristic Palette */
            --bg-body: #050b18;
            --navbar-bg: rgba(10, 16, 32, 0.7);
            --glass-bg: rgba(15, 23, 42, 0.6);
            --glass-border: rgba(255, 255, 255, 0.1);
            
            --primary-color: #38bdf8; /* Cyan */
            --accent-color: #8b5cf6;  /* Deep Purple */
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            
            --card-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
            --glass-blur: blur(12px);
        }

        body {
            font-family: "Inter", sans-serif;
            background-color: var(--bg-body);
            background-image: radial-gradient(circle at 10% 20%, rgba(56, 189, 248, 0.05) 0%, transparent 40%),
                              radial-gradient(circle at 90% 80%, rgba(139, 92, 246, 0.05) 0%, transparent 40%);
            background-attachment: fixed;
            min-height: 100vh;
            color: var(--text-main);
            padding-top: 90px;
        }

        /* Navbar Glassmorphism */
        .navbar-custom {
            background: var(--navbar-bg);
            backdrop-filter: var(--glass-blur);
            -webkit-backdrop-filter: var(--glass-blur);
            border-bottom: 1px solid var(--glass-border);
            padding: 12px 0;
        }

        .navbar-brand h4 {
            color: white;
            font-weight: 700;
            background: linear-gradient(to right, #38bdf8, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-link {
            color: var(--text-muted) !important;
            transition: 0.3s;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--primary-color) !important;
            text-shadow: 0 0 8px rgba(56, 189, 248, 0.5);
        }

        .dropdown-menu {
            background: var(--glass-bg);
            backdrop-filter: var(--glass-blur);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
        }

        .dropdown-item {
            color: var(--text-main);
        }

        /* Glass Cards */
        .page-header {
            background: var(--glass-bg);
            backdrop-filter: var(--glass-blur);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            box-shadow: var(--card-shadow);
        }

        .card {
            background: var(--glass-bg);
            backdrop-filter: var(--glass-blur);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            color: var(--text-main);
        }

        .table {
            color: var(--text-main);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(56, 189, 248, 0.3);
        }

        /* Animations - Simplified without transforms on large wrappers */
        .animate-fade-in {
            animation: fadeInSimple 0.4s ease-out forwards;
        }

        @keyframes fadeInSimple {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @media (max-width: 991.98px) {
            body { padding-top: 70px; }
            .main-content { padding: 15px; }
            .navbar-collapse { 
                background: var(--navbar-bg); 
                padding: 15px; 
                border-radius: 15px; 
                margin-top: 10px; 
            }
        }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
    <div class="container">
        <a class="navbar-brand" href="index.php?page=dashboard">
            <h4><i class="fas fa-microchip me-2"></i> REKAP IT</h4>
        </a>
        
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4 gap-1">
                <li class="nav-item">
                    <a href="index.php?page=dashboard" class="nav-link <?= ($page == 'dashboard') ? 'active' : '' ?>">
                        <i class="bi bi-grid-1x2-fill me-1"></i> Dashboard
                    </a>
                </li>

                <?php if (hasRole('admin')): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= in_array($page, ['cabang', 'divisi', 'karyawan']) ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-database me-1"></i> Data Master
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="index.php?page=cabang">Cabang</a></li>
                        <li><a class="dropdown-item" href="index.php?page=divisi">Divisi</a></li>
                        <li><a class="dropdown-item" href="index.php?page=karyawan">Karyawan</a></li>
                        <li><a class="dropdown-item" href="index.php?page=pengguna">Pengguna</a></li>
                    </ul>
                </li>
                <?php endif; ?>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= in_array($page, ['kategori', 'inventaris', 'mutasi']) ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-laptop me-1"></i> Aset
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="index.php?page=inventaris">Data Aset</a></li>
                        <li><a class="dropdown-item" href="index.php?page=kategori">Kategori</a></li>
                        <li><a class="dropdown-item" href="index.php?page=mutasi">Mutasi</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= ($page == 'maintenance') ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-calendar-check me-1"></i> Maintenance
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="index.php?page=maintenance&sub=history">History Maintenance</a></li>
                        <li><a class="dropdown-item" href="index.php?page=maintenance&sub=massal">Bulk Maintenance</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= in_array($page, ['perbaikan', 'sparepart']) ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-tools me-1"></i> Perbaikan
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="index.php?page=perbaikan">Tiket Perbaikan</a></li>
                        <li><a class="dropdown-item" href="index.php?page=sparepart">Sparepart</a></li>
                    </ul>
                </li>

                <?php if (hasRole('admin')): ?>
                <li class="nav-item">
                    <a href="index.php?page=audit" class="nav-link <?= ($page == 'audit') ? 'active' : '' ?>">
                        <i class="bi bi-shield-check me-1"></i> Audit
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= in_array($page, ['laporan', 'logs', 'laporan_maintenance']) ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-file-earmark-bar-graph me-1"></i> Laporan
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="index.php?page=laporan">Export Excel</a></li>
                        <li><a class="dropdown-item" href="index.php?page=laporan_maintenance">Laporan Maintenance</a></li>
                        <li><a class="dropdown-item" href="index.php?page=logs">Log Aktivitas</a></li>
                    </ul>
                </li>
                <?php endif; ?>
            </ul>

            <div class="navbar-nav align-items-center">
                <!-- Notifications -->
                <div class="dropdown me-3">
                    <button class="nav-link position-relative p-2 border-0 bg-transparent" data-bs-toggle="dropdown" aria-expanded="false" id="notifDropdown">
                        <i class="bi bi-bell fs-5"></i>
                        <?php if ($notifCount > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-dark" style="font-size: 0.6rem;">
                                <?= $notifCount ?>
                            </span>
                        <?php endif; ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2 animate-slide-down" style="width: 320px; border-radius: 20px; overflow: hidden; z-index: 9999;">
                        <li class="px-4 py-3 border-bottom bg-light">
                            <h6 class="m-0 fw-bold text-dark">Notifikasi Maintenance</h6>
                            <small class="text-muted"><?= $notifCount ?> jadwal dalam 7 hari kedepan</small>
                        </li>
                        <div style="max-height: 300px; overflow-y: auto;">
                            <?php if ($notifCount === 0): ?>
                                <li class="px-4 py-3 text-muted small">Tidak ada jadwal maintenance</li>
                            <?php else: ?>
                                <?php foreach ($notifications as $n): ?>
                                    <li class="border-bottom">
                                        <a class="dropdown-item px-4 py-3 text-dark transition-hover" href="index.php?page=maintenance">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3">
                                                    <i class="bi bi-tools text-primary"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="fw-bold small text-truncate"><?= $n['kode_aset'] ?> - <?= $n['nama_aset'] ?></div>
                                                    <small class="text-muted"><?= date('d M Y', strtotime($n['tanggal'])) ?></small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <li class="px-4 py-2 border-top bg-light text-center">
                            <a href="index.php?page=maintenance" class="text-primary text-decoration-none small fw-bold">Lihat Semua</a>
                        </li>
                    </ul>
                </div>

                <!-- User Profile -->
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['nama']) ?>&background=4361ee&color=fff" class="rounded-circle" width="32">
                        <span class="small fw-bold text-white d-none d-xl-inline"><?= $_SESSION['nama'] ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li class="px-3 py-2 border-bottom border-secondary mb-2">
                            <div class="small text-muted">Signed in as</div>
                            <div class="fw-bold text-white"><?= $_SESSION['username'] ?></div>
                        </li>
                        <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>

<div class="main-content container">
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h4 class="m-0 fw-bold"><?= ucwords(str_replace('_', ' ', $page)) ?></h4>
            <div class="small text-muted d-none d-sm-block">Sistem Manajemen Aset IT</div>
        </div>
        <div class="d-none d-md-flex align-items-center gap-3">
            <div class="text-end">
                <div class="small fw-bold" id="realtime-clock">Loading time...</div>
                <div class="text-muted" style="font-size: 0.7rem;">Status: <span class="text-success fw-bold">Online</span></div>
            </div>
            <!-- Notifications moved out -->
        </div>
    </div>

<style>
    .animate-slide-down {
        animation: slideDown 0.3s ease-out forwards;
    }
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .transition-hover { transition: all 0.2s ease; }
    .transition-hover:hover { background-color: #f8fafc; }
</style>


    <div class="content-body">
        <div class="animate-fade-in">
<script>
    function updateClock() {
        const now = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' };
        document.getElementById('realtime-clock').textContent = now.toLocaleDateString('id-ID', options);
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>
