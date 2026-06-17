<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    $currentPage = basename($_SERVER['PHP_SELF']);

    if ($currentPage != 'login.php') {
        header('Location: login.php');
        exit();
    }
}

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$file = 'views/' . $page . '.php';

include 'views/header.php';

if (file_exists($file)) {
    include $file;
} else {
    echo "<div class='alert alert-danger'>Halaman tidak ditemukan!</div>";
}

include 'views/footer.php';
?>
