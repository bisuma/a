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
	<title>Kriteria</title>
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
				<span>Data Kriteria</span>
			</div>
			<div id="main">
				<form action="search-kriteria.php" method="post">
					<input type="text" class="search" name="search-kriteria" placeholder="Search" required>
					<input type="hidden" name="jenis" value="kriteria">
					<input type="submit" class="button" value="Search">
					<a href="kriteria.php" class="button">Kembali</a>
					<a href="form-kriteria.php" class="button">Tambah</a>
				</form>
				<br>
				<table class="bordered">
					<thead>
						<tr>
							<th>Id. Kriteria</th>
							<th>NISN</th>
							<th>Penghasilan Orang Tua</th>
							<th>Nilai</th>
							<th>Semester</th>
							<th>Tanggungan Orang Tua</th>
							<th>Saudara</th>
							<th width="141px">Aksi</th>
						</tr>
					</thead>
					<tbody>
					<?php
						include "koneksi.php";

						// Cek apakah ada parameter cari
						$search = isset($_GET['cari']) ? htmlspecialchars($_GET['cari']) : null;

						// Siapkan query
						$sql = $search ? 
							"SELECT * FROM tbl_kriteria WHERE nim LIKE ?" :
							"SELECT * FROM tbl_kriteria";

						// Prepare statement
						if ($stmt = mysqli_prepare($koneksi, $sql)) {
							if ($search) {
								$searchTerm = "%$search%";
								mysqli_stmt_bind_param($stmt, "s", $searchTerm);
							}
							mysqli_stmt_execute($stmt);
							$result = mysqli_stmt_get_result($stmt);

							// Tampilkan data
							while ($row = mysqli_fetch_assoc($result)) {
								echo "<tr>
									<td align='center'>" . htmlspecialchars($row['id_kriteria']) . "</td>
									<td><a href='detail-mahasiswa.php?kode=" . urlencode($row['nim']) . "'>" . htmlspecialchars($row['nim']) . "</a></td>
									<td>" . htmlspecialchars($row['penghasilan_ortu']) . "</td>
									<td>" . htmlspecialchars($row['nilai_ipk']) . "</td>
									<td>" . htmlspecialchars($row['semester']) . "</td>
									<td>" . htmlspecialchars($row['tanggungan_ortu']) . "</td>
									<td>" . htmlspecialchars($row['saudara_kandung']) . "</td>
									<td align='center'>
										<a href='detail-kriteria.php?kode=" . urlencode($row['id_kriteria']) . "'>Detail</a> |
										<a href='form-kriteria.php?kode=" . urlencode($row['id_kriteria']) . "'>Ubah</a> |
										<a href='hapus-kriteria.php?kode=" . urlencode($row['id_kriteria']) . "'>Hapus</a>
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