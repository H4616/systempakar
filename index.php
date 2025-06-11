<?php
// Koneksi ke database
include 'koneksi.php';

// Mengambil semua gejala dari database
$query = "SELECT * FROM gejala";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pakar Diagnosis Penyakit Ginjal</title>
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
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>Diagnosa Penyakit Ginjal Berdasarkan Gejala</h2>
            <form action="diagnosis.php" method="post">
                <h3>Pilih gejala yang Anda alami:</h3>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div>';
                        echo '<input type="checkbox" name="gejala[]" value="' . $row['id_gejala'] . '" id="gejala' . $row['id_gejala'] . '">';
                        echo '<label for="gejala' . $row['id_gejala'] . '">' . $row['nama_gejala'] . '</label>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>Tidak ada gejala yang tersedia untuk dipilih.</p>';
                }
                ?>
                <button type="submit" class="submit-btn">Proses Diagnosis</button>
            </form>
        </div>
    </div>
</body>
</html>
