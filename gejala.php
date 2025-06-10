<?php
include('koneksi.php');

// Fungsi untuk menambahkan gejala baru
if (isset($_POST['tambah_gejala'])) {
    $nama_gejala = $_POST['nama_gejala'];

    // Query untuk menambahkan gejala baru
    $sql = "INSERT INTO gejala (nama_gejala) VALUES ('$nama_gejala')";

    if ($conn->query($sql) === TRUE) {
        echo "Gejala berhasil ditambahkan!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fungsi untuk mengedit gejala
if (isset($_POST['edit_gejala'])) {
    $id_gejala = $_POST['id_gejala'];
    $nama_gejala = $_POST['nama_gejala'];

    // Query untuk mengedit gejala
    $sql = "UPDATE gejala SET nama_gejala = '$nama_gejala' WHERE id_gejala = $id_gejala";

    if ($conn->query($sql) === TRUE) {
        echo "Gejala berhasil diperbarui!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fungsi untuk menghapus gejala
if (isset($_GET['hapus_gejala'])) {
    $id_gejala = $_GET['hapus_gejala'];

    // Query untuk menghapus gejala
    $sql = "DELETE FROM gejala WHERE id_gejala = $id_gejala";

    if ($conn->query($sql) === TRUE) {
        echo "Gejala berhasil dihapus!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Gejala</title>
</head>
<body>
    <h1>Manajemen Gejala Penyakit Ginjal</h1>

    <!-- Form untuk menambahkan gejala baru -->
    <h2>Tambah Gejala Baru</h2>
    <form action="gejala.php" method="POST">
        <label for="nama_gejala">Nama Gejala:</label><br>
        <input type="text" id="nama_gejala" name="nama_gejala" required><br><br>
        <input type="submit" name="tambah_gejala" value="Tambah Gejala">
    </form>

    <hr>

    <!-- Daftar gejala yang ada -->
    <h2>Daftar Gejala yang Ada</h2>
    <?php
    // Query untuk mengambil semua gejala
    $sql = "SELECT * FROM gejala";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table border='1'>
                <tr>
                    <th>ID</th>
                    <th>Nama Gejala</th>
                    <th>Aksi</th>
                </tr>";
        // Menampilkan semua gejala
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row['id_gejala'] . "</td>
                    <td>" . $row['nama_gejala'] . "</td>
                    <td>
                        <a href='gejala.php?edit_gejala=" . $row['id_gejala'] . "'>Edit</a> |
                        <a href='gejala.php?hapus_gejala=" . $row['id_gejala'] . "' onclick='return confirm(\"Apakah Anda yakin ingin menghapus gejala ini?\")'>Hapus</a>
                    </td>
                </tr>";
        }
        echo "</table>";
    } else {
        echo "Tidak ada gejala yang tersedia.";
    }

    // Jika ada permintaan untuk edit gejala
    if (isset($_GET['edit_gejala'])) {
        $id_gejala = $_GET['edit_gejala'];
        $sql = "SELECT * FROM gejala WHERE id_gejala = $id_gejala";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        echo "<hr><h3>Edit Gejala</h3>
            <form action='gejala.php' method='POST'>
                <input type='hidden' name='id_gejala' value='" . $row['id_gejala'] . "'>
                <label for='nama_gejala'>Nama Gejala:</label><br>
                <input type='text' id='nama_gejala' name='nama_gejala' value='" . $row['nama_gejala'] . "' required><br><br>
                <input type='submit' name='edit_gejala' value='Perbarui Gejala'>
            </form>";
    }
    ?>

</body>
</html>
