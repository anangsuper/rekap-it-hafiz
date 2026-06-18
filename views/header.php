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
            --sidebar-width: 280px;
            --primary-color: #4361ee;
            --primary-light: rgba(67, 97, 238, 0.1);
            --secondary-color: #3f37c9;
            --bg-body: #f8fafc;
            --sidebar-bg: #0f172a;
            --sidebar-text: #94a3b8;
            --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
            --glass-bg: rgba(255, 255, 255, 0.85);
        }

        body {
            font-family: "Plus Jakarta Sans", sans-serif;
            background-color: var(--bg-body);
            color: #1e293b;
            letter-spacing: -0.01em;
        }

        /* Sidebar Modern */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: var(--sidebar-bg);
            color: #fff;
            z-index: 1001;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-y: auto;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
        }

        .sidebar-header {
            padding: 40px 30px;
            text-align: left;
        }

        .sidebar-header h4 {
            font-weight: 800;
            letter-spacing: -0.5px;
            margin: 0;
            font-size: 1.5rem;
            background: linear-gradient(to right, #fff, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .user-profile {
            padding: 20px;
            background: rgba(255, 255, 255, 0.03);
            margin: 0 20px 30px;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            align-items: center;
        }

        .user-profile img {
            border: 2px solid var(--primary-color);
            padding: 2px;
            margin-right: 15px;
        }

        .nav-section {
            padding: 20px 30px 10px;
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #475569;
            font-weight: 700;
        }

        .nav-link {
            padding: 12px 25px;
            color: var(--sidebar-text) !important;
            display: flex;
            align-items: center;
            transition: 0.25s;
            border-radius: 12px;
            margin: 4px 15px;
            font-weight: 500;
            font-size: 0.925rem;
        }

        .nav-link i {
            width: 32px;
            font-size: 1.2rem;
            transition: 0.3s;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.03);
            color: #fff !important;
            transform: translateX(5px);
        }

        .nav-link.active {
            background: var(--primary-color) !important;
            color: #fff !important;
            box-shadow: 0 10px 20px -5px rgba(67, 97, 238, 0.4);
        }

        .nav-link.active i {
            color: #fff;
        }

        /* Main Content & Navbar */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 30px;
            min-height: 100vh;
            transition: all 0.3s;
        }

        .top-navbar {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 18px 30px;
            margin-bottom: 35px;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: var(--card-shadow);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 20px;
            z-index: 1000;
        }

        /* Global Component Styles */
        .card {
            border: 1px solid rgba(0, 0, 0, 0.03);
            border-radius: 24px;
            box-shadow: var(--card-shadow);
            background: #fff;
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.03);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 10px 24px;
            border-radius: 14px;
            font-weight: 600;
            box-shadow: 0 4px 14px 0 rgba(67, 97, 238, 0.3);
            transition: all 0.2s;
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(67, 97, 238, 0.4);
        }

        .form-control, .form-select {
            padding: 12px 18px;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            background-color: #fcfcfd;
            transition: all 0.2s;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px var(--primary-light);
            background-color: #fff;
        }

        .badge-status {
            padding: 8px 16px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.75rem;
            text-transform: uppercase;
        }

        .table thead th {
            background-color: #f8fafc;
            text-transform: uppercase;
            font-size: 0.75rem;
            font-weight: 700;
            color: #64748b;
            padding: 18px 20px;
            border-bottom: 2px solid #f1f5f9;
        }

        .table td {
            padding: 18px 20px;
            vertical-align: middle;
            color: #334155;
            border-bottom: 1px solid #f1f5f9;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease forwards;
        }
    </style>
</head>

<body>
<div class="sidebar">
    <div class="sidebar-header">
        <h4><i class="fas fa-microchip me-2"></i> REKAP IT</h4>
    </div>

    <div class="user-profile">
        <img src="https://ui-avatars.com/api/?name=<?= $_SESSION['nama'] ?>&background=4361ee&color=fff" class="rounded-circle" width="45">
        <div>
            <div class="small fw-bold text-white"><?= $_SESSION['nama'] ?></div>
            <div class="small" style="color: #64748b; font-size: 0.7rem;"><?= strtoupper(str_replace('_', ' ', $_SESSION['role'])) ?></div>
        </div>
    </div>

    <div class="nav flex-column">
        <a href="index.php?page=dashboard" class="nav-link <?= ($page == 'dashboard') ? 'active' : '' ?>">
            <i class="bi bi-grid-1x2-fill me-2"></i> Dashboard
        </a>

        <div class="nav-section">Organization</div>
        <a href="index.php?page=cabang" class="nav-link <?= ($page == 'cabang') ? 'active' : '' ?>">
            <i class="bi bi-building me-2"></i> Cabang
        </a>
        <a href="index.php?page=divisi" class="nav-link <?= ($page == 'divisi') ? 'active' : '' ?>">
            <i class="bi bi-diagram-3 me-2"></i> Divisi
        </a>
        <a href="index.php?page=karyawan" class="nav-link <?= ($page == 'karyawan') ? 'active' : '' ?>">
            <i class="bi bi-people me-2"></i> Karyawan
        </a>

        <div class="nav-section">Inventory</div>
        <a href="index.php?page=kategori" class="nav-link <?= ($page == 'kategori') ? 'active' : '' ?>">
            <i class="bi bi-tags me-2"></i> Kategori
        </a>
        <a href="index.php?page=inventaris" class="nav-link <?= ($page == 'inventaris') ? 'active' : '' ?>">
            <i class="bi bi-laptop me-2"></i> Data Aset
        </a>
        <a href="index.php?page=mutasi" class="nav-link <?= ($page == 'mutasi') ? 'active' : '' ?>">
            <i class="bi bi-arrow-left-right me-2"></i> Mutasi
        </a>

        <div class="nav-section">Operations</div>
        <a href="index.php?page=maintenance" class="nav-link <?= ($page == 'maintenance') ? 'active' : '' ?>">
            <i class="bi bi-tools me-2"></i> Maintenance
        </a>
        <a href="index.php?page=perbaikan" class="nav-link <?= ($page == 'perbaikan') ? 'active' : '' ?>">
            <i class="bi bi-wrench-adjustable me-2"></i> Perbaikan
        </a>
        <a href="index.php?page=sparepart" class="nav-link <?= ($page == 'sparepart') ? 'active' : '' ?>">
            <i class="bi bi-box-seam me-2"></i> Sparepart
        </a>

        <div class="nav-section">Reports & Logs</div>
        <a href="index.php?page=audit" class="nav-link <?= ($page == 'audit') ? 'active' : '' ?>">
            <i class="bi bi-shield-check me-2"></i> Audit Aset
        </a>
        <a href="index.php?page=laporan" class="nav-link <?= ($page == 'laporan') ? 'active' : '' ?>">
            <i class="bi bi-file-earmark-bar-graph me-2"></i> Laporan
        </a>

        <div class="mt-4 mb-4">
            <a href="logout.php" class="nav-link text-danger border-top border-secondary pt-3" style="border-color: rgba(255,255,255,0.05) !important;">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
            </a>
        </div>
    </div>
</div>

<div class="main-content">
    <div class="top-navbar">
        <h5 class="m-0 fw-bold animate-fade-in"><?= ucwords(str_replace('_', ' ', $page)) ?> Overview</h5>
        <div class="d-flex align-items-center animate-fade-in">
            <div class="text-end me-4 d-none d-md-block">
                <div class="small fw-bold">Kamis, 18 Juni 2026</div>
                <div class="text-muted" style="font-size: 0.7rem;">System Status: <span class="text-success fw-bold">Online</span></div>
            </div>
            <div class="position-relative me-3">
                <i class="bi bi-bell text-muted fs-5 cursor-pointer"></i>
                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
            </div>
            <div class="vr mx-3 text-muted opacity-25"></div>
            <i class="bi bi-search text-muted fs-5 ms-2 cursor-pointer"></i>
        </div>
    </div>
