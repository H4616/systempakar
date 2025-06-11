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
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pakar Diagnosis Penyakit Ginjal</title>
    <style>
        /* Umum */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f1f8f5; /* Latar belakang lebih soft */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            height: 100vh;
            background-size: cover;
            background-position: center;
            overflow-y: auto;
        }

        .container {
            width: 90%;
            max-width: 850px;
            margin: 40px auto;
            background: rgba(51, 212, 73, 0.9);
            border-radius: 25px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
            padding: 50px;
            animation: fadeIn 1s ease-out;
        }

        .form-container {
            padding: 30px;
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #1E6F4D;
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 30px;
        }

        .card {
            background: #ffffff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        .submit-btn {
            display: block;
            margin: 30px auto 0;
            padding: 15px 40px;
            background: linear-gradient(45deg, #1E6F4D, #2C9E4F);
            color: white;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            font-size: 18px;
            font-weight: bold;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            transition: background 0.3s ease, transform 0.3s ease;
        }

        .submit-btn:hover {
            background: linear-gradient(45deg, #45a049, #3e8e41);
            transform: translateY(-5px);
        }

        .submit-btn:active {
            transform: translateY(2px);
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes slideInUp {
            from {
                transform: translateY(30px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes fadeInUp {
            from {
                transform: translateY(10px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Responsiveness */
        @media (max-width: 768px) {
            .container {
                width: 95%;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>Hasil Diagnosis Penyakit Ginjal</h2>
            <?php if (isset($diagnosis)): ?>
                <?php if ($diagnosis['penyakit'] != null): ?>
                    <div class="card">
                        <p>Penyakit yang kemungkinan Anda alami adalah: <strong><?= $diagnosis['penyakit'] ?></strong></p>
                        <p>Kepastian: <?= number_format($diagnosis['cf'] * 100) ?>%</p>
                    </div>
                <?php else: ?>
                    <p>Tidak ada diagnosis yang dapat diberikan berdasarkan gejala yang dipilih.</p>
                <?php endif; ?>
            <?php else: ?>
                <p>Tidak ada gejala yang dipilih. Silakan pilih gejala terlebih dahulu di halaman <a href="index.php">Index</a>.</p>
            <?php endif; ?>

            <!-- Kembali ke halaman Index -->
            <a href="index.php" class="submit-btn">Kembali</a>
        </div>
    </div>
</body>
</html>
