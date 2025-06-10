<?php
$host = 'localhost';  // Ganti dengan nama host Anda jika berbeda
$username = 'root';   // Ganti dengan username database Anda
$password = '';       // Ganti dengan password database Anda jika ada
$dbname = 'ginjal';   // Nama database yang Anda buat

// Membuat koneksi
$conn = new mysqli($host, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
echo "Koneksi berhasil!";
?>
