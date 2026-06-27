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
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%236366f1'><path d='M19 9h2V7h-2V5c0-1.1-.9-2-2-2h-2V1h-2v2h-2V1H9v2H7c-1.1 0-2 .9-2 2v2H3v2h2v2H3v2h2v2H3v2h2v2c0 1.1.9 2 2 2h2v2h2v-2h2v2h2v-2h2c1.1 0 2-.9 2-2v-2h2v-2h-2v-2h2v-2h-2V9zm-2 8H7V5h10v12zm-8-9h6v6H9V8z'/></svg>">

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
            
            /* Ambient premium background */
            --bg-body: radial-gradient(at 0% 0%, rgba(224, 231, 255, 0.4) 0px, transparent 50%),
                       radial-gradient(at 50% 0%, rgba(243, 232, 255, 0.4) 0px, transparent 50%),
                       radial-gradient(at 100% 0%, rgba(229, 231, 235, 0.3) 0px, transparent 50%),
                       #f8fafc;
            
            --sidebar-bg: #0f172a; /* Slate 900 */
            --sidebar-border: rgba(255, 255, 255, 0.06);
            --card-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.02), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
            --card-shadow-hover: 0 20px 35px -8px rgba(99, 102, 241, 0.08), 0 10px 15px -3px rgba(0, 0, 0, 0.03);
            --glass-bg: rgba(255, 255, 255, 0.75);
            --glass-border: rgba(255, 255, 255, 0.6);
        }

        /* Smooth scrollbar styling */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
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
            padding-top: 0;
            overflow-x: hidden;
            line-height: 1.6;
        }

        /* Premium Left Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 260px;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--sidebar-border);
            z-index: 1030;
            padding: 24px 16px;
            overflow-y: auto;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-brand {
            margin-bottom: 28px;
            padding-left: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .sidebar-brand h4 {
            font-weight: 800;
            letter-spacing: -0.8px;
            margin: 0;
            font-size: 1.3rem;
            background: linear-gradient(135deg, #ffffff 0%, #cbd5e1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .sidebar-heading {
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #475569; /* Slate 600 */
            font-weight: 700;
            margin: 18px 0 8px 10px;
        }

        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            color: #94a3b8;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.85rem;
            border-radius: 12px;
            transition: all 0.2s ease;
        }

        .sidebar-link:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar-link.active {
            color: #ffffff !important;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%) !important;
            box-shadow: 0 4px 15px -3px rgba(99, 102, 241, 0.4);
        }

        .sidebar-link i {
            font-size: 1.1rem;
        }

        /* Top Header Bar */
        .top-header-bar {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            padding: 16px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-left: 260px;
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .main-content {
            margin-left: 260px;
            padding: 40px;
            min-height: calc(100vh - 72px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Cards & Forms overrides */
        .card {
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card:hover {
            box-shadow: var(--card-shadow-hover);
            border-color: rgba(226, 232, 240, 1);
        }

        .form-control, .form-select {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 10px 16px;
            font-size: 0.9rem;
            font-weight: 500;
            color: #1e293b;
            transition: all 0.2s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
        }

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

        /* SaaS Table Layout */
        .table-responsive {
            border-radius: 16px;
            overflow: visible;
        }

        .table {
            border-collapse: separate;
            border-spacing: 0 8px;
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
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.7);
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        }

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

        /* Page transition & typography helper animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.45s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .fw-800 { font-weight: 800; }
        .fw-700 { font-weight: 700; }
        .fw-600 { font-weight: 600; }

        /* Sidebar Toggle styles for Desktop */
        body.sidebar-hidden .sidebar {
            transform: translateX(-260px);
        }
        body.sidebar-hidden .top-header-bar {
            margin-left: 0;
        }
        body.sidebar-hidden .main-content {
            margin-left: 0;
        }

        .sidebar-toggle-btn {
            color: #475569 !important;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .sidebar-toggle-btn:hover {
            color: var(--primary-color) !important;
            transform: scale(1.08);
        }

        /* Responsive Mobile Layout overrides */
        @media (max-width: 991.98px) {
            .sidebar-toggle-btn {
                color: #ffffff !important;
            }
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .top-header-bar {
                margin-left: 0;
                padding: 12px 20px;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                background: #0f172a;
                color: white;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            }
            .top-header-bar .top-bar-title {
                color: #ffffff !important;
            }
            .top-header-bar .text-muted {
                color: #94a3b8 !important;
            }
            .main-content {
                margin-left: 0;
                padding: 20px 16px;
                padding-top: 88px;
            }
            body {
                padding-top: 0;
            }
        }
    </style>
</head>
<body>

<!-- Left Sidebar Navigation -->
<div class="sidebar" id="sidebarContainer">
    <a class="sidebar-brand" href="index.php?page=dashboard">
        <i class="fas fa-microchip text-primary fs-4"></i>
        <h4>REKAP IT</h4>
    </a>

    <div class="sidebar-heading">MONITORING</div>
    <ul class="sidebar-nav">
        <li>
            <a href="index.php?page=dashboard" class="sidebar-link <?= ($page == 'dashboard') ? 'active' : '' ?>">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>
        </li>
        <?php if (hasRole('admin')): ?>
        <li>
            <a href="index.php?page=logs" class="sidebar-link <?= ($page == 'logs') ? 'active' : '' ?>">
                <i class="bi bi-clock-history"></i> Log Aktivitas
            </a>
        </li>
        <?php endif; ?>
    </ul>

    <div class="sidebar-heading">OPERASIONAL</div>
    <ul class="sidebar-nav">
        <li>
            <a href="index.php?page=maintenance&sub=history" class="sidebar-link <?= ($page == 'maintenance') ? 'active' : '' ?>">
                <i class="bi bi-calendar-check"></i> Maintenance PC
            </a>
        </li>
        <li>
            <a href="index.php?page=perbaikan" class="sidebar-link <?= in_array($page, ['perbaikan', 'sparepart']) ? 'active' : '' ?>">
                <i class="bi bi-tools"></i> Tiket Perbaikan
            </a>
        </li>
        <?php if (hasRole('admin')): ?>
        <li>
            <a href="index.php?page=audit" class="sidebar-link <?= ($page == 'audit') ? 'active' : '' ?>">
                <i class="bi bi-shield-check"></i> Audit Fisik
            </a>
        </li>
        <?php endif; ?>
    </ul>

    <div class="sidebar-heading">MANAJEMEN ASET</div>
    <ul class="sidebar-nav">
        <li>
            <a href="index.php?page=inventaris" class="sidebar-link <?= ($page == 'inventaris') ? 'active' : '' ?>">
                <i class="bi bi-laptop"></i> Data Aset
            </a>
        </li>
        <li>
            <a href="index.php?page=kategori" class="sidebar-link <?= ($page == 'kategori') ? 'active' : '' ?>">
                <i class="bi bi-tags"></i> Kategori Aset
            </a>
        </li>
        <li>
            <a href="index.php?page=mutasi" class="sidebar-link <?= ($page == 'mutasi') ? 'active' : '' ?>">
                <i class="bi bi-arrow-left-right"></i> Mutasi Aset
            </a>
        </li>
    </ul>

    <?php if (hasRole('admin')): ?>
    <div class="sidebar-heading">MASTER DATA</div>
    <ul class="sidebar-nav">
        <li>
            <a href="index.php?page=cabang" class="sidebar-link <?= ($page == 'cabang') ? 'active' : '' ?>">
                <i class="bi bi-building"></i> Cabang
            </a>
        </li>
        <li>
            <a href="index.php?page=divisi" class="sidebar-link <?= ($page == 'divisi') ? 'active' : '' ?>">
                <i class="bi bi-people"></i> Divisi
            </a>
        </li>
        <li>
            <a href="index.php?page=karyawan" class="sidebar-link <?= ($page == 'karyawan') ? 'active' : '' ?>">
                <i class="bi bi-person-badge"></i> Karyawan
            </a>
        </li>
        <li>
            <a href="index.php?page=pengguna" class="sidebar-link <?= ($page == 'pengguna') ? 'active' : '' ?>">
                <i class="bi bi-person-gear"></i> Pengguna
            </a>
        </li>
    </ul>

    <div class="sidebar-heading">LAPORAN</div>
    <ul class="sidebar-nav">
        <li>
            <a href="index.php?page=laporan_maintenance" class="sidebar-link <?= ($page == 'laporan_maintenance') ? 'active' : '' ?>">
                <i class="bi bi-file-earmark-bar-graph"></i> Report Bulanan
            </a>
        </li>
        <li>
            <a href="index.php?page=laporan" class="sidebar-link <?= ($page == 'laporan') ? 'active' : '' ?>">
                <i class="bi bi-file-earmark-excel"></i> Export Excel
            </a>
        </li>
    </ul>
    <?php endif; ?>
</div>

<!-- Floating Top Header Bar -->
<div class="top-header-bar">
    <div class="d-flex align-items-center gap-3">
        <!-- Sidebar Toggle -->
        <button class="btn btn-sm btn-link p-0 border-0 sidebar-toggle-btn" id="sidebarToggleBtn">
            <i class="bi bi-justify fs-3"></i>
        </button>
        <div>
            <h5 class="m-0 fw-bold top-bar-title text-dark"><?= ucwords(str_replace('_', ' ', $page)) ?></h5>
            <small class="text-muted d-none d-sm-block">Sistem Manajemen Aset IT</small>
        </div>
    </div>

    <div class="d-flex align-items-center gap-3">
        <!-- Clock (Hidden on Mobile) -->
        <div class="text-end d-none d-md-block me-2">
            <div class="small fw-bold text-dark" id="realtime-clock">Loading time...</div>
            <div class="text-muted" style="font-size: 0.7rem;">Status: <span class="text-success fw-bold">Online</span></div>
        </div>

        <!-- Notifications -->
        <div class="dropdown">
            <button class="nav-link position-relative p-2 border-0 bg-transparent text-muted opacity-75 hover-opacity-100 transition-all" data-bs-toggle="dropdown" aria-expanded="false" id="notifDropdown">
                <i class="bi bi-bell fs-5 <?= ($notifCount > 0) ? 'animate-bell' : '' ?>"></i>
                <?php if ($notifCount > 0): ?>
                    <span class="position-absolute top-1 start-75 translate-middle p-1 bg-danger border border-2 border-light rounded-circle">
                        <span class="visually-hidden">New alerts</span>
                    </span>
                <?php endif; ?>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2 p-0 overflow-hidden" style="width: 320px; border-radius: 16px; z-index: 1050; background: #ffffff;">
                <li class="px-4 py-3 bg-light border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="m-0 fw-bold text-dark"><i class="bi bi-bell-fill text-primary me-2"></i>Notifikasi</h6>
                        <small class="text-muted"><?= $notifCount ?> jadwal perlu tindakan</small>
                    </div>
                </li>
                <div class="notif-scroll-container" style="max-height: 280px; overflow-y: auto;">
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
                                    <div class="bg-primary bg-opacity-10 p-2 rounded-3 text-primary d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; flex-shrink: 0;">
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

        <!-- User Profile Dropdown -->
        <div class="dropdown">
            <button class="btn btn-link p-0 border-0 bg-transparent dropdown-toggle d-flex align-items-center gap-2 text-decoration-none" data-bs-toggle="dropdown">
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['nama']) ?>&background=4361ee&color=fff" class="rounded-circle border" width="34">
                <span class="small fw-bold text-dark d-none d-lg-inline"><?= $_SESSION['nama'] ?></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2 rounded-3" style="min-width: 180px;">
                <li class="px-3 py-2 border-bottom mb-2 bg-light">
                    <div class="small text-muted">Signed in as</div>
                    <div class="fw-bold text-dark"><?= $_SESSION['username'] ?></div>
                </li>
                <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
            </ul>
        </div>
    </div>
</div>

<!-- Main Content Area -->
<div class="main-content">
    <div class="content-body">
        <div class="animate-fade-in">
