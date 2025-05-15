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
	<title>Mahasiswa</title>
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
				<span>Data Mahasiswa</span>
			</div>
			<div id="main">
				<form action="search-mahasiswa.php" method="post">
					<input type="text" class="search" name="search-mahasiswa" placeholder="Search" required>
					<input type="hidden" name="jenis" value="mahasiswa">
					<input type="submit" class="button" value="Search">
					<a href="mahasiswa.php" class="button">Kembali</a>
					<a href="form-mahasiswa.php" class="button">Tambah</a>
				</form>
				<br>
				<table class="bordered">
					<thead>
						<tr>
							<th width="25px">No.</th>
							<th>NISN</th>
							<th>Nama Lengkap</th>
							<th>Nama Orang Tua</th>
							<th>Kelas</th>
							<th>Jenis Kelamin</th>
							<th width="141px">Aksi</th>
						</tr>
					</thead>
					<tbody>
					<?php
						include "koneksi.php";
						
						$search = isset($_GET['cari']) ? htmlspecialchars($_GET['cari']) : null;
						$no_urut = 0;

						// Siapkan query dengan parameterized query
						$sql = $search ? 
							"SELECT * FROM tbl_mahasiswa WHERE nim LIKE ? OR nama_lengkap LIKE ? OR program_studi LIKE ? OR kelas LIKE ? OR jenis_kelamin LIKE ?" :
							"SELECT * FROM tbl_mahasiswa";

						if ($stmt = mysqli_prepare($koneksi, $sql)) {
							if ($search) {
								$searchTerm = "%$search%";
								mysqli_stmt_bind_param($stmt, "sssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
							}
							mysqli_stmt_execute($stmt);
							$result = mysqli_stmt_get_result($stmt);

							while ($row = mysqli_fetch_assoc($result)) {
								$no_urut++;
								echo "<tr>
									<td align='center'>$no_urut</td>
									<td align='center'>" . htmlspecialchars($row['nim']) . "</td>
									<td>" . htmlspecialchars($row['nama_lengkap']) . "</td>
									<td>" . htmlspecialchars($row['program_studi']) . "</td>
									<td>" . htmlspecialchars($row['kelas']) . "</td>
									<td>" . htmlspecialchars($row['jenis_kelamin']) . "</td>
									<td align='center'>
										<a href='detail-mahasiswa.php?kode=" . urlencode($row['nim']) . "'>Detail</a> |
										<a href='form-mahasiswa.php?kode=" . urlencode($row['nim']) . "'>Ubah</a> |
										<a href='hapus-mahasiswa.php?kode=" . urlencode($row['nim']) . "'>Hapus</a>
									</td>
								</tr>";
							}
							mysqli_stmt_close($stmt);
						} else {
							echo "<tr><td colspan='7' align='center'>Data tidak ditemukan</td></tr>";
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