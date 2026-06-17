<?php

// Konfigurasi Database (Local / Railway)
$host     = getenv('MYSQLHOST') ?: 'localhost';
$dbname   = getenv('MYSQLDATABASE') ?: 'rekap_it';
$username = getenv('MYSQLUSER') ?: 'root';
$password = getenv('MYSQLPASSWORD') ?: '';
$port     = getenv('MYSQLPORT') ?: '3306';

try {
    // Tambahkan "mysql:host=..." dan pastikan tidak menggunakan localhost jika di Railway
    // Karena localhost di Linux seringkali memaksa penggunaan socket (.sock)
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8";
    
    $conn = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
    ]);

} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
?>
