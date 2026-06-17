<?php

// Ambil konfigurasi dari Environment Railway atau default lokal
$host     = getenv('MYSQLHOST') ?: '127.0.0.1'; 
$dbname   = getenv('MYSQLDATABASE') ?: 'rekap_it';
$username = getenv('MYSQLUSER') ?: 'root';
$password = getenv('MYSQLPASSWORD') ?: '';
$port     = getenv('MYSQLPORT') ?: '3306';

// Jika di Railway host-nya tetap terbaca localhost, paksa ke 127.0.0.1 untuk koneksi TCP
if ($host === 'localhost') {
    $host = '127.0.0.1';
}

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8";
    
    $conn = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

} catch (PDOException $e) {
    // Menambahkan info host untuk memudahkan debug jika gagal
    die("Koneksi database gagal: " . $e->getMessage() . " (Target Host: $host)");
}
?>
