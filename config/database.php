<?php

// Konfigurasi Database (Local / Railway)

$host     = getenv('MYSQLHOST') ?: 'localhost';
$dbname   = getenv('MYSQLDATABASE') ?: 'rekap_it';
$username = getenv('MYSQLUSER') ?: 'root';
$password = getenv('MYSQLPASSWORD') ?: '';
$port     = getenv('MYSQLPORT') ?: '3306';

try {

    $conn = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8",
        $username,
        $password
    );

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {

    die("Koneksi database gagal: " . $e->getMessage());

}

?>
