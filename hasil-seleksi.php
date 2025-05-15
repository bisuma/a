<!DOCTYPE HTML>
<?php
	session_start();
	if(empty($_SESSION['username'])){
		header("Location: form-login.php");
		exit();
	}
?>
<html lang="id">
<head>
	<title>Hasil Seleksi</title>
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
                    <li><a href="ahp_calculation.php" class="active">Perhitungan AHP</a></li>
                    <li><a href="pembobotan-kriteria.php">Pembobotan Kriteria</a></li>
                    <li><a href="hasil-seleksi.php">Hasil Seleksi</a></li>
                    <li><a href="laporan.php">Laporan</a></li>
                    <li><a href="user.php">Manajemen User</a></li>
				</ul>
			</div>
		</div>
	</div>
	<div id="main-wrap">
		<div id="main-center">
			<div id="head-main">
				<span>Data Hasil Seleksi</span>
			</div>
			<div id="main">
				<table class="bordered">
					<thead>
						<tr>
							<th width="25px">No.</th>
							<th>NISN</th>
							<th>Nama Lengkap</th>
							<th>Kelas</th>
							<th>Jenis Kelamin</th>
							<th>Hasil Pembobotan</th>
							<th>Status</th>
							<th width="70px">Aksi</th>
						</tr>
					</thead>
					<tbody>
					<?php
						include "koneksi.php";
						$no_urut = 0;

						// Siapkan query
						$sql = "
							SELECT 
								tbl_mahasiswa.nim, 
								tbl_mahasiswa.nama_lengkap, 
								tbl_mahasiswa.kelas, 
								tbl_mahasiswa.jenis_kelamin, 
								tbl_pembobotan.hasil_pembobotan 
							FROM 
								tbl_mahasiswa
							INNER JOIN 
								tbl_kriteria ON tbl_mahasiswa.nim = tbl_kriteria.nim
							INNER JOIN 
								tbl_normalisasi ON tbl_kriteria.id_kriteria = tbl_normalisasi.id_kriteria
							INNER JOIN 
								tbl_pembobotan ON tbl_normalisasi.id_normalisasi = tbl_pembobotan.id_normalisasi
							ORDER BY 
								hasil_pembobotan DESC
						";

						// Eksekusi query
						if ($stmt = mysqli_prepare($koneksi, $sql)) {
							mysqli_stmt_execute($stmt);
							$result = mysqli_stmt_get_result($stmt);

							// Tampilkan data
							while ($row = mysqli_fetch_assoc($result)) {
								$no_urut++;
								$status = ($row['hasil_pembobotan'] >= 75) ? "<span class='status-lulus'>Lulus</span>" : "<span class='status-gagal'>Gagal</span>";
								echo "<tr>
									<td align='center'>$no_urut</td>
									<td>" . htmlspecialchars($row['nim']) . "</td>
									<td>" . htmlspecialchars($row['nama_lengkap']) . "</td>
									<td>" . htmlspecialchars($row['kelas']) . "</td>
									<td>" . htmlspecialchars($row['jenis_kelamin']) . "</td>
									<td>" . htmlspecialchars($row['hasil_pembobotan']) . "</td>
									<td>" . $status . "</td>
									<td align='center'>
										<a href='detail-hasil-seleksi.php?kode=" . urlencode($row['nim']) . "'>Detail</a>
									</td>
								</tr>";
							}

							mysqli_stmt_close($stmt);
						} else {
							echo "<tr><td colspan='8' align='center'>Data tidak ditemukan</td></tr>";
						}
					?>
					</tbody>
				</table>
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