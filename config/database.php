<?php

// Membaca variabel MYSQL_URL dari Railway
$mysql_url = getenv('MYSQL_URL');

if ($mysql_url) {
    // Jika ada MYSQL_URL (di Railway), kita bongkar isinya
    $db_config = parse_url($mysql_url);
    $host     = $db_config['host'];
    $port     = $db_config['port'] ?? '3306';
    $username = $db_config['user'];
    $password = $db_config['pass'];
    $dbname   = ltrim($db_config['path'], '/');
} else {
    // Jika tidak ada (di lokal), gunakan default
    $host     = getenv('MYSQLHOST') ?: '127.0.0.1';
    $port     = getenv('MYSQLPORT') ?: '3306';
    $username = getenv('MYSQLUSER') ?: 'root';
    $password = getenv('MYSQLPASSWORD') ?: '';
    $dbname   = getenv('MYSQLDATABASE') ?: 'rekap_it';
}

// Pastikan tidak ada localhost yang memicu socket error di Linux
if ($host === 'localhost') { $host = '127.0.0.1'; }

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    
    $conn = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

} catch (PDOException $e) {
    // Tampilkan pesan error yang sangat detail untuk debug
    die("Koneksi database gagal. <br> Error: " . $e->getMessage() . "<br> Host digunakan: " . $host . "<br> Port: " . $port);
}
?>
