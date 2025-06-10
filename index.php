<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pakar Penyakit Ginjal</title>
</head>
<body>
    <h1>Pilih Gejala yang Anda Alami</h1>
    <form action="diagnosis.php" method="POST">
        <?php
        include('koneksi.php');
        $sql = "SELECT * FROM gejala";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<input type='checkbox' name='gejala[]' value='" . $row['id_gejala'] . "'>" . $row['nama_gejala'] . "<br>";
            }
        } else {
            echo "Tidak ada gejala yang tersedia.";
        }
        ?>
        <br>
        <input type="submit" value="Diagnosa">
    </form>
</body>
</html>
