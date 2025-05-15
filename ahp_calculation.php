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
                <form action="" method="post">
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
                            $result = mysqli_query($koneksi, $sql) or die("Query gagal: " . mysqli_error($koneksi));
                            $kriteria = [];
                            while ($row = mysqli_fetch_assoc($result)) {
                                $kriteria[] = $row['id_kriteria'];
                            }

                            // Generate form input berdasarkan kriteria
                            for ($i = 0; $i < count($kriteria); $i++) {
                                for ($j = $i + 1; $j < count($kriteria); $j++) {
                                    echo "<tr>
                                        <td>{$kriteria[$i]}</td>
                                        <td>{$kriteria[$j]}</td>
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
                    <input type="submit" class="button" value="Process AHP">
                </form>

                <!-- Proses Perhitungan AHP -->
                <?php
                if (isset($_POST['matrix'])) {
                    $matrix = $_POST['matrix'];

                    // Simpan data mentah ke tabel tbl_matriks_perbandingan
                    foreach ($matrix as $id_kriteria_1 => $subArray) {
                        foreach ($subArray as $id_kriteria_2 => $nilai_perbandingan) {
                            $sql = "INSERT INTO tbl_matriks_perbandingan (id_kriteria_1, id_kriteria_2, nilai_perbandingan)
                                    VALUES ('$id_kriteria_1', '$id_kriteria_2', $nilai_perbandingan)
                                    ON DUPLICATE KEY UPDATE nilai_perbandingan = $nilai_perbandingan";
                            if (!mysqli_query($koneksi, $sql)) {
                                die("Error menyimpan data ke tabel matriks perbandingan: " . mysqli_error($koneksi));
                            }
                        }
                    }
                    echo "<p>Data matriks perbandingan berhasil disimpan ke database.</p>";

                    // Inisialisasi matriks perbandingan
                    $pairwiseMatrix = [];
                    foreach ($kriteria as $i) {
                        foreach ($kriteria as $j) {
                            if ($i === $j) {
                                $pairwiseMatrix[$i][$j] = 1; // Diagonal matriks bernilai 1
                            } elseif (isset($matrix[$i][$j])) {
                                $pairwiseMatrix[$i][$j] = $matrix[$i][$j];
                                $pairwiseMatrix[$j][$i] = 1 / $matrix[$i][$j]; // Nilai kebalikan
                            }
                        }
                    }

                    // Hitung total kolom
                    $columnSums = array_fill_keys($kriteria, 0);
                    foreach ($pairwiseMatrix as $row) {
                        foreach ($kriteria as $j) {
                            $columnSums[$j] += $row[$j];
                        }
                    }

                    // Normalisasi matriks dan hitung bobot prioritas
                    $priorities = array_fill_keys($kriteria, 0);
                    foreach ($pairwiseMatrix as $i => $row) {
                        foreach ($kriteria as $j) {
                            $pairwiseMatrix[$i][$j] /= $columnSums[$j]; // Normalisasi
                            $priorities[$i] += $pairwiseMatrix[$i][$j];
                        }
                        $priorities[$i] /= count($kriteria); // Rata-rata
                    }

                    // Hitung Consistency Ratio (CR)
                    $lambdaMax = 0;
                    foreach ($pairwiseMatrix as $i => $row) {
                        foreach ($kriteria as $j) {
                            $lambdaMax += $row[$j] * $priorities[$j];
                        }
                    }
                    $ci = ($lambdaMax - count($kriteria)) / (count($kriteria) - 1);
                    $ri = [0, 0, 0.58, 0.9, 1.12, 1.24]; // Random Index (RI) untuk matriks ukuran n
                    $cr = $ci / $ri[count($kriteria)];

                    // Tampilkan hasil
                    echo "<h3>Hasil Normalisasi</h3>";
                    echo "<table class='bordered'>
                            <thead>
                                <tr>
                                    <th>Kriteria</th>
                                    <th>Bobot Prioritas</th>
                                </tr>
                            </thead>
                            <tbody>";
                    foreach ($priorities as $kriteria => $priority) {
                        echo "<tr>
                                <td>{$kriteria}</td>
                                <td>" . number_format($priority, 4) . "</td>
                              </tr>";
                    }
                    echo "</tbody></table>";

                    // Tampilkan validasi konsistensi
                    echo "<h3>Validasi Konsistensi</h3>";
                    if ($cr <= 0.1) {
                        echo "<p class='status-lulus'>Matriks konsisten (CR = " . number_format($cr, 4) . ")</p>";
                    } else {
                        echo "<p class='status-gagal'>Matriks tidak konsisten (CR = " . number_format($cr, 4) . "), harap periksa input.</p>";
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