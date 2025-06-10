<?php
include('koneksi.php');
include('inference.php');  // Memasukkan logika inferensi

// Mendapatkan gejala yang dipilih dari form
$gejala_pilih = $_POST['gejala'];

// Memproses diagnosis berdasarkan gejala yang dipilih
$hasil_diagnosa = lakukanDiagnosa($gejala_pilih, $conn);

if ($hasil_diagnosa) {
    echo "<h3>Hasil Diagnosis Utama:</h3>";
    echo "<p>Penyakit: " . $hasil_diagnosa['nama_penyakit'] . "</p>";
    echo "<p>Kepastian: " . number_format($hasil_diagnosa['cf_total'], 2) . "</p>";
} else {
    echo "Tidak ada penyakit yang cocok dengan gejala yang Anda pilih.";
}

$conn->close();
?>
