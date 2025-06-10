<?php
function lakukanDiagnosa($gejala_pilih, $conn) {
    $gejala_str = implode(",", $gejala_pilih);  // Menggabungkan gejala yang dipilih menjadi string

    // Query untuk mencari aturan yang sesuai dengan gejala yang dipilih
    $sql = "SELECT p.id_penyakit, p.nama_penyakit, a.kepastian 
            FROM aturan a
            JOIN penyakit p ON a.id_penyakit = p.id_penyakit
            WHERE a.id_gejala IN ($gejala_str)";

    $result = $conn->query($sql);

    // Array untuk menyimpan hasil diagnosis
    $penyakit_cf = [];

    // Menyimpan data penyakit dan nilai CF untuk setiap penyakit yang ditemukan
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $penyakit_id = $row['id_penyakit'];
            $penyakit_nama = $row['nama_penyakit'];
            $kepastian = $row['kepastian'];

            if (!isset($penyakit_cf[$penyakit_id])) {
                $penyakit_cf[$penyakit_id] = [
                    'nama_penyakit' => $penyakit_nama,
                    'cf_total' => $kepastian
                ];
            } else {
                // Gabungkan CF jika penyakit yang sama ditemukan lebih dari sekali
                $penyakit_cf[$penyakit_id]['cf_total'] = gabungkanCF($penyakit_cf[$penyakit_id]['cf_total'], $kepastian);
            }
        }
    }

    // Cari penyakit dengan CF tertinggi
    $diagnosa_utama = null;
    $cf_tertinggi = 0;
    foreach ($penyakit_cf as $penyakit) {
        if ($penyakit['cf_total'] > $cf_tertinggi) {
            $cf_tertinggi = $penyakit['cf_total'];
            $diagnosa_utama = $penyakit;
        }
    }

    return $diagnosa_utama;
}

// Fungsi untuk menggabungkan CF
function gabungkanCF($cf1, $cf2) {
    return $cf1 + $cf2 * (1 - abs($cf1));
}
?>
