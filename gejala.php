<?php
// Koneksi ke database
include 'koneksi.php';

// Menambah gejala baru
if (isset($_POST['tambah_gejala'])) {
    $nama_gejala = $_POST['nama_gejala'];

    // Validasi input
    if (!empty($nama_gejala)) {
        // Query untuk menambahkan gejala baru ke database
        $query = "INSERT INTO gejala (nama_gejala) VALUES (?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $nama_gejala);

        if ($stmt->execute()) {
            echo "<p>Gejala berhasil ditambahkan!</p>";
        } else {
            echo "<p>Gagal menambahkan gejala. Coba lagi.</p>";
        }
    } else {
        echo "<p>Nama gejala tidak boleh kosong!</p>";
    }
}

// Menghapus gejala
if (isset($_GET['hapus'])) {
    $id_gejala = $_GET['hapus'];

    // Query untuk menghapus gejala berdasarkan ID
    $query = "DELETE FROM gejala WHERE id_gejala = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_gejala);

    if ($stmt->execute()) {
        echo "<p>Gejala berhasil dihapus!</p>";
    } else {
        echo "<p>Gagal menghapus gejala. Coba lagi.</p>";
    }
}

// Mengambil daftar gejala dari database
$query = "SELECT * FROM gejala";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Gejala - Sistem Pakar Diagnosis Penyakit Ginjal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 50%;
            margin: 0 auto;
        }
        .form-container {
            background-color: #f4f4f4;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            font-size: 16px;
        }
        .submit-btn {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        .hapus-btn {
            color: red;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>Manajemen Gejala</h2>

            <!-- Form untuk menambah gejala baru -->
            <form action="gejala.php" method="post">
                <label for="nama_gejala">Nama Gejala:</label>
                <input type="text" name="nama_gejala" id="nama_gejala" required>
                <button type="submit" name="tambah_gejala" class="submit-btn">Tambah Gejala</button>
            </form>

            <h3>Daftar Gejala yang Tersedia</h3>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Gejala</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Menampilkan daftar gejala
                    if ($result->num_rows > 0) {
                        $no = 1;
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $no++ . "</td>";
                            echo "<td>" . $row['nama_gejala'] . "</td>";
                            echo "<td><a href='gejala.php?hapus=" . $row['id_gejala'] . "' class='hapus-btn'>Hapus</a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>Tidak ada gejala yang tersedia.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
