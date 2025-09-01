<?php

// Konfigurasi Database
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root'); 
define('DB_PASSWORD', '');     
define('DB_NAME', 'testdb');

// Membuat koneksi ke database menggunakan MySQLi
$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Cek koneksi
if ($mysqli->connect_errno) {
    // Jika koneksi gagal, hentikan skrip dan tampilkan pesan error
    die("ERROR: Tidak bisa terhubung ke database. " . $mysqli->connect_error);
}

// Set a default character set to utf8
$mysqli->set_charset("utf8mb4");
?>
