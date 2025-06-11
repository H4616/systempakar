<?php
// Koneksi ke database
include 'koneksi.php';

// Fungsi untuk menghitung Certainty Factor (CF) berdasarkan belief (MB) dan disbelief (MD)
function hitungCF($mb, $md) {
    return $mb - $md; // Menghitung Certainty Factor
}

// Fungsi untuk mendapatkan nama penyakit berdasarkan ID penyakit
function getPenyakit($id_penyakit, $conn) {
    $query = "SELECT nama_penyakit FROM penyakit WHERE id_penyakit = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_penyakit);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['nama_penyakit'];
}

// Fungsi untuk melakukan diagnosis berdasarkan gejala yang dipilih oleh pengguna
function lakukanDiagnosa($gejala_pilih, $conn) {
    $penyakitCF = [];

    // Loop untuk setiap gejala yang dipilih
    foreach ($gejala_pilih as $g) {
        // Query untuk mengambil aturan yang terkait dengan gejala
        $query = "SELECT p.id_penyakit, p.nama_penyakit, a.belief, a.disbelief 
                  FROM aturan a
                  JOIN penyakit p ON a.id_penyakit = p.id_penyakit
                  WHERE a.id_gejala = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $g);
        $stmt->execute();
        $result = $stmt->get_result();

        // Proses setiap aturan yang ditemukan untuk gejala
        while ($row = $result->fetch_assoc()) {
            $id_penyakit = $row['id_penyakit'];
            $mb = $row['belief'];
            $md = $row['disbelief'];

            // Menghitung CF untuk gejala dan penyakit terkait
            $cf = hitungCF($mb, $md);

            // Menambahkan CF ke total CF untuk penyakit
            if (!isset($penyakitCF[$id_penyakit])) {
                $penyakitCF[$id_penyakit] = 0;
            }
            $penyakitCF[$id_penyakit] += $cf;
        }
    }

    // Menentukan penyakit dengan total CF tertinggi
    $maxCF = -PHP_INT_MAX;
    $diagnosis_terpilih = null;

    foreach ($penyakitCF as $id_penyakit => $totalCF) {
        if ($totalCF > $maxCF) {
            $maxCF = $totalCF;
            $diagnosis_terpilih = getPenyakit($id_penyakit, $conn);
        }
    }

    return $diagnosis_terpilih;
}

// Mengecek jika gejala dipilih melalui form
if (isset($_POST['gejala'])) {
    $gejala_pilih = $_POST['gejala'];

    // Memanggil fungsi untuk melakukan diagnosis
    $diagnosis = lakukanDiagnosa($gejala_pilih, $conn);

    // Menampilkan hasil diagnosis
    echo "<h3>Hasil Diagnosis:</h3>";
    echo "<p>Penyakit yang kemungkinan Anda alami adalah: <strong>$diagnosis</strong></p>";
} else {
    echo "<p>Tidak ada gejala yang dipilih. Silakan pilih gejala terlebih dahulu di halaman <a href='index.php'>Index</a>.</p>";
}
?>
