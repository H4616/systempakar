<?php
// Koneksi ke database
include 'koneksi.php';

// Mengambil semua gejala dari database
$query = "SELECT * FROM gejala";
$result = $conn->query($query);

// Tentukan jumlah gejala minimum yang harus dipilih
$min_gejala = 3; // Misalnya, pengguna harus memilih minimal 3 gejala
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
            background: #f1f8f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start; 
            height: 100vh;
            background-image: url('https://www.example.com/background-image.jpg');
            background-size: cover;
            background-position: center;
            overflow-y: auto;
        }

        .container {
            width: 90%;
            max-width: 850px;
            margin: 40px auto;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 20px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            padding: 40px;
            animation: fadeIn 1s ease-out;
        }

        .form-container {
            padding: 30px;
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            animation: slideInUp 0.5s ease-in-out;
        }

        h2 {
            text-align: center;
            color: #1E6F4D;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        h3 {
            text-align: center;
            color: #1E6F4D;
            font-size: 24px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        label {
            font-size: 18px;
            color: #333;
            margin-bottom: 10px;
            display: block;
            font-weight: 600;
        }

        input[type="checkbox"] {
            margin-right: 10px;
            transform: scale(1.2);
            transition: transform 0.3s ease, background-color 0.3s ease;
        }

        input[type="checkbox"]:hover {
            transform: scale(1.4);
            background-color: #1E6F4D;
        }

        .card {
            background: #ffffff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        .checkbox-labels {
            display: flex;
            flex-direction: column;
            gap: 15px;
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
            <h2>Diagnosa Penyakit Ginjal Berdasarkan Gejala</h2>
            <form action="diagnosis.php" method="post" onsubmit="return validateForm()">
                <h3>Pilih gejala yang Anda alami:</h3>
                <div class="checkbox-labels">
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<div class="card">';
                            echo '<input type="checkbox" name="gejala[]" value="' . $row['id_gejala'] . '" id="gejala' . $row['id_gejala'] . '">';
                            echo '<label for="gejala' . $row['id_gejala'] . '">' . $row['nama_gejala'] . '</label>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>Tidak ada gejala yang tersedia untuk dipilih.</p>';
                    }
                    ?>
                </div>
                <button type="submit" class="submit-btn">Proses Diagnosis</button>
            </form>
            <!-- Error message for minimum selection -->
            <p id="error-message" style="color: red; display: none;">Anda harus memilih minimal <?php echo $min_gejala; ?> gejala untuk melanjutkan diagnosis.</p>
        </div>
    </div>

    <script>
        // Fungsi untuk memvalidasi form sebelum submit
        function validateForm() {
            var gejalaSelected = document.querySelectorAll('input[name="gejala[]"]:checked').length;
            if (gejalaSelected < <?php echo $min_gejala; ?>) {
                document.getElementById("error-message").style.display = "block";  // Menampilkan pesan error
                return false;  // Mencegah form untuk submit
            }
            return true;  // Jika validasi berhasil, form akan disubmit
        }
    </script>
</body>
</html>
