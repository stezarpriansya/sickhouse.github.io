<?php
//session_start();
//include( 'temp/headerprofile.php' );
if(!isset($_SESSION['username'])) {
	header('location:index.php'); 
}
else { $username = $_SESSION['username']; }
$ambiltabel=mysql_query("SELECT * FROM dokter INNER JOIN user ON user.user_id=dokter.user_id WHERE username='$username'"); 
$id= $_SESSION['id'];
$result=mysql_fetch_array($ambiltabel);
//'$dokter'=$result[kode_dokter];
$dokter="$result[kode_dokter]";
$_SESSION['xx']=$dokter;
//echo $dokter;
date_default_timezone_set('Asia/Jakarta');
$tgl_skg=date("Y-m-d H:i:s T");
$no=1;
$no1=1;
?>


<style type="text/css">
    
	.left {
		float: left;
		width: 30%;
		padding-right: 3%;
	}

	.right {
		float: right;
		width: 70%;
	}

	h4 { 
    padding-top: 50px;
    font-weight: bold;
	}

	#content {
		min-height: 300px;
		padding: 3% 5%;
		background: #93CAB7;
	}

	.title {
		padding: 20px;
		font-size: 22px;
		background: none repeat scroll 0% 0% #00B28B;
		color: white;
		font-weight: bold;
	}

	.detail{
		min-height: 250px;
		background: none repeat scroll 0% 0% white;
	}
	
	table { 
	  width: 100%; 
	  border-collapse: collapse; 
	}
	/* Zebra striping */
	tr:nth-of-type(odd) { 
	  background: #eee; 
	}
	th { 
	  background: #15AB67; 
	  color: white; 
	  font-weight: bold; 
	}
	td, th { 
	  padding: 6px; 
	  border: 1px solid #ccc; 
	  text-align: left; 
	}

	.detail img {
		width:250px;
		height:250px;
		margin: 10%;
	}

	.section {
		padding: 6px 28px;
	}

	.titles {
		color: #00A27E;
		font-size:18px;
		font-weight: bold;
	}
</style>
<html>
<body>
<div id="content">
	<div class="left">
		<div class="title">PROFILE</div>
		<div class="detail">
			<img src=echo"$result[foto_dokter]">
			<div class="section">
				<div class="titles">NAMA</div>
				<div class="description"><?php echo"$result[nama_dokter]"; ?></div>
			</div>
			<div class="section">
				<div class="titles">SIP</div>
				<div class="description"><?php echo"$result[SIP]"; ?></div>
			</div>
			<div class="section">
				<div class="titles">KODE DOKTER</div>
				<div class="description"><?php echo"$result[kode_dokter]"; ?></div>
			</div>
			<div class="section">
				<div class="titles">SPESIALISASI</div>
				<div class="description"><?php echo"$result[spesialisasi]"; ?></div>
			</div>
			<div class="section">
				<div class="titles">KOTA</div>
				<div class="description"><?php echo"$result[kota_dokter]"; ?></div>
			</div>
		</div>
	</div>
	
	<div class="right">
		<div>
				<?php
				$ambilpasien1=mysql_query("SELECT kode, nama, jenis, diagnosa, kota, datang FROM
					(SELECT
					pasien.kode_pasien as kode,
					pasien.nama_pasien as nama,
					pasien.jenis_kelamin as jenis,
					diagnosa.diagnosa_dokter as diagnosa,
					pasien.kota_pasien as kota,
					pasien.tgl_datang as datang
					FROM
					dokter
					INNER JOIN pasien ON pasien.kode_dokter = dokter.kode_dokter
					INNER JOIN diagnosa ON diagnosa.kode_dokter = dokter.kode_dokter AND diagnosa.kode_pasien = pasien.kode_pasien
					WHERE dokter.user_id = $id AND
					pasien.tgl_keluar IS NULL
					UNION ALL
					SELECT
					pasien.kode_pasien as kode,
					pasien.nama_pasien as nama,
					pasien.jenis_kelamin as jenis,
					diagnosa.diagnosa_dokter as diagnosa,
					pasien.kota_pasien as kota,
					pasien.tgl_datang as datang
					FROM
					pasien
					INNER JOIN diagnosa ON diagnosa.kode_pasien = pasien.kode_pasien
					INNER JOIN operasi ON operasi.kode_diagnosa = diagnosa.kode_diagnosa AND operasi.kode_pasien = pasien.kode_pasien
					INNER JOIN tim_dokter ON operasi.id_tim = tim_dokter.id_tim
					WHERE
					pasien.tgl_keluar IS NULL AND
					operasi.jam_berakhir IS NULL AND
					operasi.jam_mulai > NOW() AND
					(tim_dokter.dokter1='$dokter' OR tim_dokter.dokter2='$dokter' OR tim_dokter.dokter3='$dokter' OR tim_dokter.dokter4='$dokter' OR
					tim_dokter.dokter5='$dokter' OR tim_dokter.dokter6='$dokter')) as uniontable
					ORDER by kode") or die(mysql_error());
				/*
				$ambilpasien=mysql_query("SELECT
					pasien.kode_pasien as kode,
					pasien.nama_pasien as nama,
					pasien.jenis_kelamin as `jenis`,
					diagnosa.diagnosa_dokter as diagnosa,
					pasien.kota_pasien as kota,
					pasien.tgl_datang as datang
					FROM
					dokter
					INNER JOIN pasien ON pasien.kode_dokter = dokter.kode_dokter
					INNER JOIN diagnosa ON diagnosa.kode_dokter = dokter.kode_dokter AND diagnosa.kode_pasien = pasien.kode_pasien
							WHERE dokter.user_id = $id AND
							pasien.tgl_keluar IS NULL
							ORDER BY pasien.kode_pasien");*/
				
				$a=0;
				$cek1=mysql_num_rows($ambilpasien1);
				//echo $n;
				if ($cek1 > 0){
					while ($row1=mysql_fetch_array($ambilpasien1)){
						$kode[$a]="$row1[kode]";$nama[$a]="$row1[nama]";$jenis[$a]="$row1[jenis]";$diag[$a]="$row1[diagnosa]";$kota[$a]="$row1[kota]";$datang[$a]="$row1[datang]";
						if ($a<>0 && $kode[$a]==$kode[$a--]){
							$a++;
							continue;
						} else if ($kode[$a]== ""){
							$kode[$a]="$row1[kode]";
						}
						$a++;
					}
					$n=count($kode);
					if ($cek1 > 1){ ?>
			<div class="title">CURRENT PATIENTS</div>
				<div class="detail">
					<div class="table">
					<?php }
					else { ?>
			<div class="title">CURRENT PATIENT</div>
				<div class="detail">
					<div class="table">
					<?php } ?>
					<table>
					<thead><tr><th>No.</th><th>Kode Pasien</th><th>Nama Pasien</th><th>Jenis Kelamin</th><th>Diagnosa</th><th>Kota Asal</th><th>Tanggal Datang</th></tr></thead>
					<?php
					for($i=0;$i<$n;$i++)
					{ ?>
					  <tbody>
					  <tr>
					  <td><?php echo $no++; ?></td>
					  <td><?php echo $kode[$i]; ?></td>
					  <td><?php echo $nama[$i]; ?></td>
					  <td><?php echo $jenis[$i]; ?></td>
					  <td><?php echo $diag[$i]; ?></td>
					  <td><?php echo $kota[$i]; ?></td>
					  <td><?php echo $datang[$i]; ?></td>
					  </tr>
					 </tbody>
					<?php
					} ?>
					</table>
				<?php }
				else { ?>
			<div class="title">CURRENT PATIENT</div>
				<div class="detail">
					<div class="table">
					<h4 align="center">Tidak ada Pasien yang sedang ditangani! Have fun :)</h4>
				<?php
				}
				?>
					</div>
				</div>
		</div>
		<div style="margin-top: 3%">
				<?php
					///*
					$ambiloperasi=mysql_query("SELECT
					operasi.kode_operasi as kode1,
					operasi.kode_pasien as kode2,
					diagnosa.diagnosa_dokter as diagnosa1,
					operasi.jam_mulai as mulai
					FROM
					operasi
					INNER JOIN diagnosa ON operasi.kode_diagnosa = diagnosa.kode_diagnosa
					INNER JOIN tim_dokter ON operasi.id_tim = tim_dokter.id_tim
					WHERE
					operasi.jam_berakhir IS NULL AND
					operasi.jam_mulai > NOW() AND 
					(tim_dokter.dokter1='$dokter' OR tim_dokter.dokter2='$dokter' OR tim_dokter.dokter3='$dokter' OR tim_dokter.dokter4='$dokter' OR
					tim_dokter.dokter5='$dokter' OR tim_dokter.dokter6='$dokter')");
					/*
					$ambiloperasi=mysql_query("SELECT
					operasi.kode_operasi as kode1,
					diagnosa.diagnosa_dokter as diagnosa1,
					operasi.jam_mulai as mulai
					FROM
					operasi
					INNER JOIN diagnosa ON operasi.kode_diagnosa = diagnosa.kode_diagnosa
					INNER JOIN tim_dokter ON operasi.id_tim = tim_dokter.id_tim
					WHERE
					operasi.jam_berakhir IS NULL AND
					operasi.jam_mulai > NOW() AND 
					(tim_dokter.dokter1 IS NULL OR tim_dokter.dokter2 IS NULL OR tim_dokter.dokter3 IS NULL OR tim_dokter.dokter4 IS NULL OR
					tim_dokter.dokter5 IS NULL OR tim_dokter.dokter6 IS NULL)");
					*/
					$cek = mysql_num_rows($ambiloperasi);
					if ($cek>0){ 
						if ($cek > 1){ ?>
			<div class="title">ONGOING SURGERIES</div>
				<div class="detail">
					<div class="table">
					<?php }
					else { ?>
			<div class="title">ONGOING SURGERY</div>
				<div class="detail">
					<div class="table">
					<?php } ?>
						<table>
						<thead><tr><th>No.</th><th>Kode Operasi</th><th>Kode Pasien</th><th>Diagnosa Dokter</th><th>Tanggal Mulai</th></tr></thead>
						<?php
						
						while($row = mysql_fetch_array($ambiloperasi)){
						?>
						<tbody>
						  <tr>
						  <td><?php echo $no1++; ?></td>
						  <td><?php echo"$row[kode1]"; ?></td>
						  <td><?php echo"$row[kode2]"; ?></td>
						  <td><?php echo"$row[diagnosa1]"; ?></td>
						  <td><?php echo"$row[mulai]"; ?></td>
						  </tr>
						 </tbody>
				<?php } ?>
				</table>
				<?php }
				else { ?>
			<div class="title">ONGOING SURGERY</div>
				<div class="detail">
					<div class="table">
					<h4 align="center">Tidak ada Operasi yang belum dilakukan!</h4>
				<?php }
				?>
					</div>
				</div>
		</div>
	</div>
	<div class="clear"></div>
</div>
<body>
</html>
<?php
include( 'temp/footer.php' );