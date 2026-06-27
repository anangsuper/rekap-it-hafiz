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
        @import url("https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap");

        :root {
            --primary-color: #6366f1; /* Indigo */
            --primary-hover: #4f46e5;
            --primary-light: rgba(99, 102, 241, 0.08);
            --secondary-color: #a855f7; /* Purple */
            --secondary-hover: #9333ea;
            
            /* Ambient premium mesh background */
            --bg-body: radial-gradient(at 0% 0%, rgba(224, 231, 255, 0.6) 0px, transparent 50%),
                       radial-gradient(at 50% 0%, rgba(243, 232, 255, 0.6) 0px, transparent 50%),
                       radial-gradient(at 100% 0%, rgba(229, 231, 235, 0.4) 0px, transparent 50%),
                       #f8fafc;
            
            --navbar-bg: rgba(15, 23, 42, 0.95);
            --navbar-text: #94a3b8;
            --card-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.02), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
            --card-shadow-hover: 0 20px 35px -8px rgba(99, 102, 241, 0.08), 0 10px 15px -3px rgba(0, 0, 0, 0.03);
            --glass-bg: rgba(255, 255, 255, 0.75);
            --glass-border: rgba(255, 255, 255, 0.6);
        }

        /* Smooth scrollbar styling */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        body {
            font-family: "Plus Jakarta Sans", sans-serif;
            background: var(--bg-body);
            background-attachment: fixed;
            min-height: 100vh;
            color: #1e293b;
            letter-spacing: -0.015em;
            padding-top: 110px;
            overflow-x: hidden;
            line-height: 1.6;
        }

        /* Floating Pill Glass Navbar */
        .navbar-custom {
            background: var(--navbar-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            padding: 10px 0;
            box-shadow: 0 12px 30px -10px rgba(15, 23, 42, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            margin: 15px auto;
            width: calc(100% - 32px);
            max-width: 1280px;
            left: 50% !important;
            transform: translateX(-50%) !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1030;
        }

        .modal {
            z-index: 1060 !important;
        }
        .modal-backdrop {
            z-index: 1050 !important;
            backdrop-filter: blur(4px);
            background-color: rgba(15, 23, 42, 0.4);
        }

        .navbar-brand h4 {
            font-weight: 800;
            letter-spacing: -0.8px;
            margin: 0;
            font-size: 1.35rem;
            background: linear-gradient(135deg, #ffffff 0%, #cbd5e1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-link {
            color: var(--navbar-text) !important;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 8px 14px !important;
            border-radius: 12px;
            transition: all 0.25s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .nav-link:hover {
            color: #fff !important;
            background: rgba(255, 255, 255, 0.06);
        }

        .nav-link.active {
            color: #fff !important;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%) !important;
            box-shadow: 0 4px 15px -3px rgba(99, 102, 241, 0.4);
        }

        .dropdown-menu {
            background: #0f172a;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 8px;
            box-shadow: 0 15px 35px -5px rgba(0,0,0,0.3);
            animation: dropdownAppear 0.2s ease;
        }

        @keyframes dropdownAppear {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .dropdown-item {
            color: var(--navbar-text);
            border-radius: 10px;
            padding: 8px 14px;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .dropdown-item:hover {
            background: rgba(255, 255, 255, 0.06);
            color: #fff;
        }

        /* Main Content wrapper */
        .main-content {
            padding: 20px 16px;
            min-height: calc(100vh - 100px);
            max-width: 1280px;
            margin: 0 auto;
        }

        /* Glassmorphic Page Header */
        .page-header {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: 24px 32px;
            margin-bottom: 30px;
            border-radius: 24px;
            border: 1px solid var(--glass-border);
            box-shadow: var(--card-shadow);
        }

        .page-header h4 {
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -0.6px;
            background: linear-gradient(135deg, #0f172a 0%, #334155 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Premium Modern Cards */
        .card {
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 24px;
            box-shadow: var(--card-shadow);
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card:hover {
            box-shadow: var(--card-shadow-hover);
            border-color: rgba(226, 232, 240, 1);
        }

        /* Prevent ugly double-shadows and nested glassmorphism on child cards */
        
        /* Bell shake animation */
        @keyframes bell-shake {
            0%, 100% { transform: rotate(0); }
            10%, 30%, 50%, 70%, 90% { transform: rotate(12deg); }
            20%, 40%, 60%, 80% { transform: rotate(-12deg); }
        }
        .animate-bell {
            animation: bell-shake 2.5s cubic-bezier(.36,.07,.19,.97) both;
            animation-iteration-count: infinite;
            transform-origin: top center;
            display: inline-block;
        }

        .notif-scroll-container::-webkit-scrollbar {
            width: 5px;
        }
        .notif-scroll-container::-webkit-scrollbar-track {
            background: transparent;
        }
        .notif-scroll-container::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        .notif-scroll-container::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .card .card {
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
            padding: 0 !important;
        }

        /* Styled inputs, forms, selects */
        .form-control, .form-select {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 10px 16px;
            font-size: 0.9rem;
            font-weight: 500;
            color: #1e293b;
            transition: all 0.2s ease-in-out;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
            background-color: #ffffff;
            color: #1e293b;
        }

        /* Button Redesign */
        .btn {
            border-radius: 12px;
            padding: 10px 20px;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%) !important;
            border: none !important;
            color: #ffffff !important;
            box-shadow: 0 4px 14px rgba(99, 102, 241, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.45);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-secondary {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            color: #475569;
        }

        .btn-secondary:hover {
            background: #f8fafc;
            color: #1e293b;
            border-color: #cbd5e1;
        }

        /* SaaS Table Layout override */
        .table-responsive {
            border-radius: 16px;
            overflow: visible;
        }

        .table {
            border-collapse: separate;
            border-spacing: 0 8px; /* Give space between rows for floating-card look */
            margin-top: -8px;
            width: 100%;
        }

        .table tr {
            background-color: #ffffff;
            border-radius: 14px;
            transition: all 0.2s ease;
        }

        .table tr:hover {
            background-color: #f8fafc;
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.02);
        }

        .table th {
            background: transparent !important;
            border: none !important;
            color: #64748b !important;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 14px 18px !important;
        }

        .table td {
            background: transparent !important;
            border: none !important;
            border-top: 1px solid #f1f5f9;
            border-bottom: 1px solid #f1f5f9;
            padding: 16px 18px !important;
            vertical-align: middle;
            color: #334155;
            font-size: 0.85rem;
        }

        /* Corner rounding for tr cards */
        .table tr td:first-child {
            border-left: 1px solid #f1f5f9;
            border-top-left-radius: 14px;
            border-bottom-left-radius: 14px;
        }

        .table tr td:last-child {
            border-right: 1px solid #f1f5f9;
            border-top-right-radius: 14px;
            border-bottom-right-radius: 14px;
        }

        /* Modal styling */
        .modal-content {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.7);
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
            animation: modalSlide 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes modalSlide {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .modal-header {
            border-bottom: 1px solid #e2e8f0;
            padding: 20px 24px;
        }

        .modal-header .modal-title {
            font-weight: 800;
            font-size: 1.15rem;
            color: #0f172a;
            letter-spacing: -0.3px;
        }

        .modal-footer {
            border-top: 1px solid #e2e8f0;
            padding: 16px 24px;
        }

        /* Animations */
        .animate-fade-in {
            animation: fadeInSimple 0.4s ease-out forwards;
        }

        @keyframes fadeInSimple {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @media (max-width: 991.98px) {
            body { padding-top: 90px; }
            .main-content { padding: 15px; }
            .navbar-custom {
                border-radius: 16px;
                margin: 10px;
                width: calc(100% - 20px);
            }
            .navbar-collapse { 
                background: #0f172a; 
                padding: 15px; 
                border-radius: 15px; 
                margin-top: 10px; 
                border: 1px solid rgba(255, 255, 255, 0.05);
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
                    <button class="nav-link position-relative p-2 border-0 bg-transparent text-white opacity-75 hover-opacity-100 transition-all" data-bs-toggle="dropdown" aria-expanded="false" id="notifDropdown">
                        <i class="bi bi-bell fs-5 <?= ($notifCount > 0) ? 'animate-bell' : '' ?>"></i>
                        <?php if ($notifCount > 0): ?>
                            <span class="position-absolute top-1 start-75 translate-middle p-1.5 bg-danger border border-2 border-dark rounded-circle">
                                <span class="visually-hidden">New alerts</span>
                            </span>
                        <?php endif; ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2 p-0 overflow-hidden" style="width: 340px; border-radius: 16px; z-index: 9999; background: #ffffff;">
                        <li class="px-4 py-3 bg-light border-bottom d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="m-0 fw-bold text-dark"><i class="bi bi-bell-fill text-primary me-2"></i>Notifikasi</h6>
                                <small class="text-muted"><?= $notifCount ?> jadwal perlu tindakan</small>
                            </div>
                            <?php if ($notifCount > 0): ?>
                                <span class="badge bg-primary rounded-pill small"><?= $notifCount ?> Baru</span>
                            <?php endif; ?>
                        </li>
                        <div class="notif-scroll-container" style="max-height: 320px; overflow-y: auto;">
                            <?php if ($notifCount === 0): ?>
                                <li class="px-4 py-5 text-center text-muted">
                                    <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex p-3 mb-3">
                                        <i class="bi bi-shield-check fs-3"></i>
                                    </div>
                                    <p class="small fw-semibold mb-0">Semua Terkendali!</p>
                                    <small class="text-muted d-block mt-1">Tidak ada jadwal maintenance mendesak.</small>
                                </li>
                            <?php else: ?>
                                <?php foreach ($notifications as $n): 
                                    // Calculate days remaining
                                    $today = new DateTime(date('Y-m-d'));
                                    $target = new DateTime(date('Y-m-d', strtotime($n['tanggal'])));
                                    $diff = $today->diff($target)->days;
                                    $is_past = $target < $today;
                                    
                                    if ($is_past) {
                                        $timeText = "Terlewat";
                                        $timeBadge = "bg-danger";
                                    } elseif ($diff === 0) {
                                        $timeText = "Hari ini";
                                        $timeBadge = "bg-danger";
                                    } elseif ($diff === 1) {
                                        $timeText = "Besok";
                                        $timeBadge = "bg-warning text-dark";
                                    } else {
                                        $timeText = "H-" . $diff;
                                        $timeBadge = "bg-primary bg-opacity-10 text-primary";
                                    }
                                ?>
                                    <li class="border-bottom">
                                        <a class="dropdown-item px-4 py-3 text-dark transition-all d-flex align-items-start gap-3" href="index.php?page=maintenance" style="white-space: normal;">
                                            <div class="bg-primary bg-opacity-10 p-2 rounded-3 text-primary d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; flex-shrink: 0;">
                                                <i class="bi bi-pc-display"></i>
                                            </div>
                                            <div class="flex-grow-1 min-w-0">
                                                <div class="fw-bold small text-dark mb-0 text-truncate"><?= $n['kode_aset'] ?></div>
                                                <div class="text-muted small text-truncate mb-2"><?= $n['nama_aset'] ?></div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="text-muted small" style="font-size: 0.75rem;"><i class="bi bi-calendar-event me-1"></i><?= date('d M Y', strtotime($n['tanggal'])) ?></span>
                                                    <span class="badge <?= $timeBadge ?> rounded-pill px-2 py-0.5" style="font-size: 0.7rem; font-weight: 700;"><?= $timeText ?></span>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <li class="px-4 py-2 bg-light border-top text-center">
                            <a href="index.php?page=maintenance" class="text-primary text-decoration-none small fw-bold d-block py-1 hover-underline">
                                Lihat Semua Jadwal <i class="bi bi-arrow-right ms-1"></i>
                            </a>
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
