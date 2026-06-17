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
        }

        .sidebar-header {
            padding: 30px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .sidebar-header h4 {
            font-weight: 700;
            letter-spacing: 1px;
            margin: 0;
            color: #fff;
        }

        .user-profile {
            padding: 20px;
            background: rgba(255,255,255,0.03);
            margin: 15px;
            border-radius: 12px;
        }

        .nav-link {
            padding: 12px 25px;
            color: rgba(255,255,255,0.7) !important;
            display: flex;
            align-items: center;
            transition: 0.2s;
            border-radius: 8px;
            margin: 4px 15px;
            font-weight: 500;
        }

        .nav-link i {
            width: 25px;
            font-size: 1.1rem;
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

        .card:hover {
            transform: translateY(-5px);
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

        .badge-status {
            padding: 6px 12px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 0.75rem;
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
            <img src="https://ui-avatars.com/api/?name=<?= $_SESSION['nama'] ?>&background=random&color=fff" class="rounded-circle" width="50">
        </div>
        <div class="small fw-bold text-white"><?= $_SESSION['nama'] ?></div>
        <div class="small text-muted" style="font-size: 0.7rem;"><?= strtoupper($_SESSION['role']) ?></div>
    </div>

    <div class="nav flex-column">
        <a href="index.php?page=dashboard" class="nav-link <?= ($page == 'dashboard') ? 'active' : '' ?>">
            <i class="fas fa-th-large me-2"></i> Dashboard
        </a>
        <a href="index.php?page=inventaris" class="nav-link <?= ($page == 'inventaris') ? 'active' : '' ?>">
            <i class="fas fa-laptop me-2"></i> Inventaris
        </a>
        <a href="index.php?page=maintenance" class="nav-link <?= ($page == 'maintenance') ? 'active' : '' ?>">
            <i class="fas fa-tools me-2"></i> Maintenance
        </a>
        <a href="index.php?page=perbaikan" class="nav-link <?= ($page == 'perbaikan') ? 'active' : '' ?>">
            <i class="fas fa-wrench me-2"></i> Perbaikan
        </a>
        
        <div class="mt-auto" style="margin-top: 100px !important;">
            <a href="logout.php" class="nav-link text-danger">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>
        </div>
    </div>
</div>

<div class="main-content">
    <div class="top-navbar">
        <h5 class="m-0 fw-bold"><?= ucfirst($page) ?> Overview</h5>
        <div class="d-flex align-items-center">
            <span class="text-muted small me-3"><i class="far fa-calendar-alt me-1"></i> <?= date('d M Y') ?></span>
            <div class="vr me-3"></div>
            <i class="far fa-bell text-muted"></i>
        </div>
    </div>
