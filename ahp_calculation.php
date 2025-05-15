<!DOCTYPE HTML>
<?php
    session_start();
    if (empty($_SESSION['username'])) {
        header("Location: form-login.php");
        exit();
    }
?>
<html lang="id">
<head>
    <title>Perhitungan AHP</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="images/favicon.jpg">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script src="https://code.jquery.com/jquery-latest.min.js"></script>
    <script src="js/script.js"></script>
</head>
<body>
    <div id="header-wrap">
        <div id="header">
            <div id="logo">
                <img src="images/a.png" alt="Logo">
                <div class="admin">
                    Selamat Datang, <?= htmlspecialchars($_SESSION['username']); ?><br>
                    <a href="#">Help</a> | <a href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>
    <div id="menu-wrap">
        <div id="menu-padding">
            <div id="cssmenu">
                <ul>
                    <li><a href="halaman-admin.php">Beranda</a></li>
                    <li><a href="mahasiswa.php">Siswa</a></li>
                    <li><a href="kriteria.php">Kriteria</a></li>
                    <li><a href="normalisasi.php">Normalisasi</a></li>
                    <li><a href="pembobotan-kriteria.php">Pembobotan Kriteria</a></li>
                    <li><a href="hasil-seleksi.php">Hasil Seleksi</a></li>
                    <li><a href="laporan.php">Laporan</a></li>
                    <li><a href="user.php">Manajemen User</a></li>
                    <li><a href="ahp_calculation.php" class="active">Perhitungan AHP</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div id="main-wrap">
        <div id="main-center">
            <div id="head-main">
                <span>Perhitungan AHP</span>
            </div>
            <div id="main">
                <!-- Form Input Matriks Perbandingan Berpasangan -->
                <form action="process_ahp.php" method="post">
                    <h3>Input Matriks Perbandingan Berpasangan</h3>
                    <table class="bordered">
                        <thead>
                            <tr>
                                <th>Kriteria</th>
                                <th>Kriteria Dibandingkan</th>
                                <th>Nilai Perbandingan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include "koneksi.php";

                            // Ambil daftar kriteria dari database
                            $sql = "SELECT id_kriteria FROM tbl_kriteria";
                            $result = mysqli_query($koneksi, $sql);
                            $kriteria = [];
                            while ($row = mysqli_fetch_assoc($result)) {
                                $kriteria[] = $row['id_kriteria'];
                            }

                            // Generate form input berdasarkan kriteria
                            for ($i = 0; $i < count($kriteria); $i++) {
                for ($j = $i + 1; $j < count($kriteria); $j++) {
                    echo "<tr>
                        <td><a href='detail-kriteria.php?kode=" . urlencode($kriteria[$i]) . "'>{$kriteria[$i]}</a></td>
                        <td><a href='detail-kriteria.php?kode=" . urlencode($kriteria[$j]) . "'>{$kriteria[$j]}</a></td>
                        <td>
                            <input type='number' name='matrix[{$kriteria[$i]}][{$kriteria[$j]}]' min='1' max='9' required>
                        </td>
                    </tr>";
                }
            }
                            ?>
                        </tbody>
                    </table>
                    <br>
                    <input type="submit" class="button" value="Proses AHP">
                </form>

                <!-- Tabel Hasil Normalisasi -->
                <?php
                // Contoh hasil normalisasi (dummy data, perlu diganti dengan hasil proses nyata)
                if (isset($_POST['matrix'])) {
                    echo "<h3>Hasil Normalisasi</h3>";
                    echo "<table class='bordered'>
                            <thead>
                                <tr>
                                    <th>Kriteria</th>
                                    <th>Bobot Prioritas</th>
                                </tr>
                            </thead>
                            <tbody>";
                    foreach ($kriteria as $krit) {
                        echo "<tr>
                                <td>{$krit}</td>
                                <td>" . rand(1, 100) / 100 . "</td>
                              </tr>";
                    }
                    echo "</tbody></table>";
                }
                ?>

                <!-- Validasi Konsistensi -->
                <?php
                if (isset($_POST['matrix'])) {
                    echo "<h3>Validasi Konsistensi</h3>";
                    // Contoh validasi konsistensi (dummy data)
                    $cr = rand(1, 10) / 100; // Random CR untuk contoh
                    if ($cr <= 0.1) {
                        echo "<p class='status-lulus'>Matriks konsisten (CR = {$cr})</p>";
                    } else {
                        echo "<p class='status-gagal'>Matriks tidak konsisten (CR = {$cr}), harap periksa input.</p>";
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <div id="footer">
        <div id="footer-wrap">
            <div class="cleaner_h20"></div>
            <div align="center">
                Copyright &copy; 2018 Hadi Suhada & Friends <br>
                All Rights Reserved.
            </div>
            <div class="cleaner_h30"></div>
        </div>
    </div>
</body>
</html>