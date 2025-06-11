<?php
// Koneksi ke database
include 'koneksi.php';

// Fungsi untuk menghitung Certainty Factor (CF) berdasarkan belief (MB) dan disbelief (MD)
function hitungCF($mb, $md) {
    return $mb - $md; // Menghitung Certainty Factor untuk setiap gejala
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

// Fungsi untuk menghitung total CF dengan rata-rata
function gabungkanCF($cf_array) {
    $total_cf = 0;
    foreach ($cf_array as $cf) {
        $total_cf += $cf;  // Menjumlahkan CF
    }
    // Rata-rata CF, pastikan CF tidak melebihi 1
    $total_cf = $total_cf / count($cf_array);
    return min($total_cf, 1);  // Pembatasan agar CF tidak melebihi 1
}

// Fungsi untuk melakukan diagnosis berdasarkan gejala yang dipilih oleh pengguna
function lakukanDiagnosa($gejala_pilih, $conn) {
    $penyakitCF = []; // Inisialisasi variabel $penyakitCF
    $cf_array = [];  // Array untuk menyimpan semua CF

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
            $mb = $row['belief'];
            $md = $row['disbelief'];

            $cf = hitungCF($mb, $md); // Menghitung CF untuk setiap gejala
            $cf_array[] = $cf;  // Menambahkan CF ke array

            // Menambahkan CF ke total CF untuk penyakit
            if (!isset($penyakitCF[$row['id_penyakit']])) {
                $penyakitCF[$row['id_penyakit']] = 0;
            }
            $penyakitCF[$row['id_penyakit']] += $cf;
        }
    }

    // Menghitung total CF dengan penggabungan rata-rata atau pembatasan nilai CF
    $maxCF = -PHP_INT_MAX;
    $diagnosis_terpilih = null;

    foreach ($penyakitCF as $id_penyakit => $totalCF) {
        // Normalisasi CF
        $totalCF_normalized = gabungkanCF($cf_array);  // Menggabungkan CF dan memastikan nilainya tidak lebih dari 1
        if ($totalCF_normalized > $maxCF) {
            $maxCF = $totalCF_normalized;
            $diagnosis_terpilih = getPenyakit($id_penyakit, $conn);
        }
    }

    return ['penyakit' => $diagnosis_terpilih, 'cf' => $maxCF];
}

// Mengecek jika gejala dipilih melalui form
if (isset($_POST['gejala'])) {
    $gejala_pilih = $_POST['gejala'];

    // Memanggil fungsi untuk melakukan diagnosis
    $diagnosis = lakukanDiagnosa($gejala_pilih, $conn);

    // Menampilkan hasil diagnosis
    echo "<h3>Hasil Diagnosis:</h3>";
    if ($diagnosis['penyakit'] != null) {
        echo "<p>Penyakit yang kemungkinan Anda alami adalah: <strong>" . $diagnosis['penyakit'] . "</strong></p>";
        echo "<p>Kepastian: " . $diagnosis['cf'] . "</p>";
    } else {
        echo "<p>Tidak ada diagnosis yang dapat diberikan berdasarkan gejala yang dipilih.</p>";
    }
} else {
    echo "<p>Tidak ada gejala yang dipilih. Silakan pilih gejala terlebih dahulu di halaman <a href='index.php'>Index</a>.</p>";
}
?>
