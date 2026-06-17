<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap IT - Asset Management</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        @import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap");

        body {
            font-family: "Inter", sans-serif;
            background-color: #f8f9fa;
        }

        .sidebar {
            min-height: 100vh;
            background: #212529;
            color: white;
            padding-top: 20px;
        }

        .sidebar a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            padding: 12px 20px;
            display: block;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background: #343a40;
            color: white;
            padding-left: 25px;
        }

        .sidebar a.active {
            background: #0d6efd;
            color: white;
        }

        .content {
            padding: 30px;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
    </style>
</head>

<body>
<div class="container-fluid">
    <div class="row">

        <div class="col-md-2 sidebar d-none d-md-block">
            <h4 class="text-center mb-4">
                <i class="fas fa-laptop-code"></i> REKAP IT
            </h4>

            <div class="px-3 mb-4 text-center">
                <div class="small opacity-75">Logged in as:</div>
                <div class="fw-bold"><?= $_SESSION['nama'] ?></div>
                <span class="badge bg-info text-dark mt-1" style="font-size: 0.7rem;"><?= ucfirst($_SESSION['role']) ?></span>
            </div>

            <a href="index.php?page=dashboard" class="<?= ($page == 'dashboard') ? 'active' : '' ?>">
                <i class="fas fa-home me-2"></i> Dashboard
            </a>

            <a href="index.php?page=inventaris" class="<?= ($page == 'inventaris') ? 'active' : '' ?>">
                <i class="fas fa-boxes me-2"></i> Inventaris
            </a>

            <a href="index.php?page=maintenance" class="<?= ($page == 'maintenance') ? 'active' : '' ?>">
                <i class="fas fa-tools me-2"></i> Maintenance
            </a>

            <a href="index.php?page=perbaikan" class="<?= ($page == 'perbaikan') ? 'active' : '' ?>">
                <i class="fas fa-wrench me-2"></i> Perbaikan
            </a>

            <hr>

            <a href="logout.php" class="text-danger">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>
        </div>

        <div class="col-md-10 content">
