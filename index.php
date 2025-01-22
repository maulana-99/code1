<?php
session_start();

if (!isset($_SESSION['kwitansi_data'])) {
    $_SESSION['kwitansi_data'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $data = [
            'nama' => $_POST['nama'],
            'tanggal' => $_POST['tanggal'], 
            'kelas' => $_POST['kelas'],
            'jurusan' => $_POST['jurusan'],
            'total' => $_POST['total'],
            'keterangan' => $_POST['keterangan']
        ];

        array_push($_SESSION['kwitansi_data'], $data);
        $jsonData = json_encode($_SESSION['kwitansi_data'], JSON_PRETTY_PRINT);
        file_put_contents('kwitansi_data.json', $jsonData);

        $_SESSION['success_message'] = "Data berhasil disimpan!";
        
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Gagal menyimpan data: " . $e->getMessage();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwitansi</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input,
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .refresh-btn {
            background-color: #2196F3;
            margin-left: 10px;
            padding: 5px 10px;
        }

        .refresh-btn:hover {
            background-color: #0b7dda;
        }

        .captcha-container {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
    </style>
</head>

<body>
    <div class="container">
        <?php
        if (isset($_SESSION['success_message'])) {
            echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
            unset($_SESSION['success_message']);
        }
        if (isset($_SESSION['error_message'])) {
            echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
            unset($_SESSION['error_message']);
        }
        ?>
        <h2>Form Kwitansi</h2>
        <form id="Form Kwitansi" method="POST">
            <div class="form-group">
                <label>Nama Lengkap:</label>
                <input type="text" name="nama" required
                    value="<?= isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : '' ?>">
            </div>

            <div class="form-group">
                <label>Tanggal Bayar:</label>
                <input type="date" name="tanggal" id="tanggalBayar" required
                    value="<?= isset($_POST['tanggal']) ? htmlspecialchars($_POST['tanggal']) : '' ?>">
            </div>

            <div class="form-group">
                <label>Kelas:</label>
                <select name="kelas" required>
                    <option value="X" <?= isset($_POST['kelas']) && $_POST['kelas'] == 'X' ? 'selected' : '' ?>>X</option>
                    <option value="XI" <?= isset($_POST['kelas']) && $_POST['kelas'] == 'XI' ? 'selected' : '' ?>>XI
                    </option>
                    <option value="XII" <?= isset($_POST['kelas']) && $_POST['kelas'] == 'XII' ? 'selected' : '' ?>>XII
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label>Jurusan:</label>
                <select name="jurusan" required>
                    <option value="REKAYASA PERANGKAT LUNAK" <?= isset($_POST['jurusan']) && $_POST['jurusan'] == 'REKAYASA PERANGKAT LUNAK' ? 'selected' : '' ?>>Rekayasa Perangkat Lunak</option>
                    <option value="MULTIMEDIA" <?= isset($_POST['jurusan']) && $_POST['jurusan'] == 'MULTIMEDIA' ? 'selected' : '' ?>>Multimedia</option>
                    <option value="OTOMATISASI TATA KELOLA KEPEGAWAIAN" <?= isset($_POST['jurusan']) && $_POST['jurusan'] == 'OTOMATISASI TATA KELOLA KEPEGAWAIAN' ? 'selected' : '' ?>>Otomatisasi Tata
                        Kelola Kepegawaian</option>
                </select>
            </div>

            <div class="form-group">
                <label>Total Bayar:</label>
                <input type="number" name="total" required
                    value="<?= isset($_POST['total']) ? htmlspecialchars($_POST['total']) : '' ?>">
            </div>

            <div class="form-group">
                <label>Keterangan:</label>
                <input type="text" name="keterangan" required
                    value="<?= isset($_POST['keterangan']) ? htmlspecialchars($_POST['keterangan']) : '' ?>">
            </div>

            <div class="form-group">
                <label>CAPTCHA:</label>
                <div class="captcha-container">
                    <span id="captchaQuestion"></span>
                    <button type="button" class="refresh-btn" onclick="generateCaptcha()">Refresh CAPTCHA</button>
                </div>
                <input type="number" name="captcha_answer" id="captchaAnswer" required>
            </div>

            <button type="submit">Submit</button>
        </form>

        <h2>Data Kwitansi</h2>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Lengkap</th>
                    <th>Tanggal Bayar</th>
                    <th>Kelas</th>
                    <th>Jurusan</th>
                    <th>Total Bayar</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $jsonFile = 'kwitansi_data.json';
                $data = file_exists($jsonFile) ? json_decode(file_get_contents($jsonFile), true) : [];

                $no = 1;
                foreach ($data as $row) {
                    echo "<tr>";
                    echo "<td>" . $no++ . "</td>";
                    echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                    echo "<td>" . date('d/m/Y', strtotime($row['tanggal'])) . "</td>";
                    echo "<td>" . htmlspecialchars($row['kelas']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['jurusan']) . "</td>";
                    echo "<td>Rp. " . number_format($row['total'], 0, ',', '.') . ",-</td>";
                    echo "<td>" . htmlspecialchars($row['keterangan']) . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        let captchaResult;
        document.getElementById("tanggalBayar").valueAsDate = new Date();

        function generateCaptcha() {
            const num1 = Math.floor(Math.random() * 20) + 1;
            const num2 = Math.floor(Math.random() * 20) + 1;
            const operators = ['+', '-', '*', '/'];
            const operator = operators[Math.floor(Math.random() * operators.length)];

            let result;
            switch (operator) {
                case '+':
                    result = num1 + num2;
                    break;
                case '-':
                    result = num1 - num2;
                    break;
                case '*':
                    result = num1 * num2;
                    break;
                case '/':
                    if (num2 !== 0 && num1 % num2 === 0) {
                        result = num1 / num2;
                    } else {
                        const newNum2 = Math.floor(Math.random() * 10) + 1;
                        const newNum1 = newNum2 * (Math.floor(Math.random() * 10) + 1);
                        result = newNum1 / newNum2;
                    }
                    break;
            }

            captchaResult = result;
            document.getElementById('captchaQuestion').textContent = `${num1} ${operator} ${num2} = ?`;
        }

        // Generate initial captcha
        generateCaptcha();

        document.getElementById('Form Kwitansi').addEventListener('submit', function (e) {
            const total = document.querySelector('input[name="total"]').value;
            const userAnswer = document.getElementById('captchaAnswer').value;

            if (total <= 0) {
                e.preventDefault();
                alert('Total bayar harus lebih dari 0');
            }

            if (parseInt(userAnswer) !== captchaResult) {
                e.preventDefault();
                alert('Jawaban CAPTCHA salah!');
                generateCaptcha();
                document.getElementById('captchaAnswer').value = '';
            }
        });
    </script>
</body>

</html>