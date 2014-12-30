<?php
session_start();
include( 'temp/headerstatus.php' );
if(!isset($_SESSION['username'])) {
	header('location:index.php'); }
else { $username = $_SESSION['username']; }

//'$dokter'=$result[kode_dokter];
$dokter=$_SESSION['xx'];
//echo $dokter;
?>


<style type="text/css">

	body {
		min-width: 80%;
	}
    
	.left {
		float: left;
		width: 40%;
		padding-right: 3%;
	}

	.right {
		float: right;
		width: 60%;
	}

	#content {
		min-height: 300px;
		padding: 7% 5%;
		background: #93CAB7;
		min-width: 80%;
	}

	.title {
		padding: 20px;
		font-size: 22px;
		background: none repeat scroll 0% 0% #00B28B;
		color: white;
		font-weight: bold;
	}

	.detail{
		min-height: 450px;
		background: none repeat scroll 0% 0% white;
	}

	.isi {
		padding: 4%;
	}

	h3 {
		text-align: center;
		color: #00B28B;
	}

	table { 
	  width: 100%; 
	  border-collapse: collapse; 
	  margin: auto;
	  overflow:auto;
	  height: 20px;
	}
	/* Zebra striping */
	tr{ 
	  background: #eee; 

	}
	th { 
	  background: #48AC86; 
	  color: white; 
	  font-weight: bold; 
	  padding: 6px;
	}

	td,th { 
	  padding: 6px;  
	  text-align: left; 
	}

</style>

<html>
<body>
<div id="content">
	<div class="left">
		<div class="title">PATIENT</div>
			<div class="detail">
				<div class="isi">
				<?php
					require_once('lib/koneksi.php');
					// mencari jumlah laki-laki dari database
					$query = "SELECT count(*) AS jumCowok 
					FROM pasien 
					INNER JOIN dokter on pasien.kode_dokter=dokter.kode_dokter
					WHERE pasien.jenis_kelamin = 'Laki-laki'
					and dokter.kode_dokter='$dokter'"; 
					$hasil = mysql_query($query);
					$data  = mysql_fetch_array($hasil);
					$jumCowok = $data['jumCowok'];

					// mencari jumlah perempuan dari database
					$query2 = "SELECT count(*) AS jumCewek 
					FROM pasien 
					INNER JOIN dokter on pasien.kode_dokter=dokter.kode_dokter
					WHERE pasien.jenis_kelamin = 'Perempuan'
					and dokter.kode_dokter='$dokter'";
					$hasil2 = mysql_query($query2);
					$data2  = mysql_fetch_array($hasil2);
					$jumCewek = $data2['jumCewek'];

					if ($jumCowok==0&&$jumCewek==0){
						$prosenCowok=0;
						$prosenCewek=0;

						$panjangGrafikCowok = $prosenCowok * 30 / 100;
						$panjangGrafikCewek = $prosenCewek * 30 / 100;
					}
					else if ($jumCowok==0&&$jumCewek<>0){
						$total = $jumCowok + $jumCewek;
						$prosenCowok=0;
						$prosenCewek = $jumCewek/$total * 100;

						$panjangGrafikCowok = $prosenCowok * 30 / 100;
						$panjangGrafikCewek = $prosenCewek * 30 / 100;

					}
					else if ($jumCewek==0&&$jumCowok<>0) {
						$total = $jumCowok + $jumCewek;
						$prosenCewek=0;

						$panjangGrafikCowok = $prosenCowok * 30 / 100;
						$panjangGrafikCewek = $prosenCewek * 30 / 100;
					}
					else{
					
						$total = $jumCowok + $jumCewek;
						$prosenCowok = $jumCowok/$total * 100;
						$prosenCewek = $jumCewek/$total * 100;

						$panjangGrafikCowok = $prosenCowok * 30 / 100;
						$panjangGrafikCewek = $prosenCewek * 30 / 100;
						// menghitung prosentase laki-laki dan perempuan
					
					}
					
					$prosen1=number_format($prosenCowok,2);
					$prosen2=number_format($prosenCewek,2);// menghitung total Pasien

					?>
					<h3>Rekapitulasi Pasien</h3> <br>
					<h4>Berdasarkan Jenis Kelamin</h4><br>
					<p>
						<b>Laki-laki</b><br>
						Jumlah: 
						<?php echo "$jumCowok Orang"; ?> <br> 
						<?php echo "Persentase : $prosen1"; ?>%)

						<div style="height: 20px; width:<?php echo $panjangGrafikCowok; ?>%; background-color: #FC6605;" title="<?php echo "$prosen1" ?>%">
						</div>
					</p>
					<p>
						<b>Perempuan</b><br>
						Jumlah: 
						<?php echo "$jumCewek Orang"; ?> <br> 
						<?php echo "Persentase : $prosen2"; ?>%) 

						<div style="height: 20px; width:<?php echo $panjangGrafikCewek; ?>%; background-color: #2288BB;" title="<?php echo "$prosen2" ?>%" ></div>
					</p>
					<br>
					<?php
					echo "dari jumlah pasien : $total Orang";
					?>

					

				</div>

				<div class="isi">
					<?php
					require_once('lib/koneksi.php');
					// mencari jumlah laki-laki dari database
					$query = "SELECT count(*) AS jum1 FROM diagnosa WHERE penanganan = 'Obat'";
					$hasil = mysql_query($query);
					$data  = mysql_fetch_array($hasil);
					$jum1 = $data['jum1'];

					// mencari jumlah perempuan dari database
					$query = "SELECT count(*) AS jum2 FROM diagnosa WHERE penanganan = 'Operasi' ";
					$hasil = mysql_query($query);
					$data  = mysql_fetch_array($hasil);
					$jum2 = $data['jum2'];

					// menghitung total Pasien
					$total = $jum1 + $jum2;

					// menghitung prosentase laki-laki dan perempuan
					$prosen1 = $jum1/$total * 100;
					$prosen2 = $jum2/$total * 100;
					$pros1=number_format($prosen1,2);
					$pros2=number_format($prosen2,2);

					// menentukan panjang grafik batang berdasarkan prosentase
					$panjangGrafik1 = $prosen1 * 30 / 100;
					$panjangGrafik2 = $prosen2 * 30 / 100;

					?>
					<h4>Berdasarkan penanganan</h4><br>

					<p><b>penanganan Obat</b><br>Jumlah: <?php echo "$jum1 Orang"; ?> <br> <?php echo "Persentase : $pros1"; ?>% 
					<div style="height: 20px; width: <?php echo $panjangGrafik1; ?>%; background-color: #FC6605;"></div></p>

					<p><b>Penanganan Operasi</b><br>Jumlah: <?php echo "$jum2 Orang"; ?> <br> <?php echo "Persentase : $pros2"; ?>% 
					<div style="height: 20px; width: <?php echo $panjangGrafik2; ?>%; background-color: #2288BB;" ></div></p>

					<br>
					<?php
					echo "dari jumlah pasien : $total Orang";
					?>
					
				</div>    		
    		</div>
	</div>
	
	<div class="right">
		<div>
			<div class="title">SURGERY</div>
			<div class="detail">

				<div class="isi">
				<?php
					require_once('lib/koneksi.php');
					// mencari jumlah laki-laki dari database
					$query = "SELECT count(*) AS jumCowok 
					FROM pasien
					WHERE jenis_kelamin = 'Laki-laki'";
					$hasil = mysql_query($query);
					$data  = mysql_fetch_array($hasil);
					$jumCowok = $data['jumCowok'];

					// mencari jumlah perempuan dari database
					$query = "SELECT count(*) AS jumCewek 
					FROM pasien 
					WHERE jenis_kelamin = 'Perempuan'";
					$hasil = mysql_query($query);
					$data  = mysql_fetch_array($hasil);
					$jumCewek = $data['jumCewek'];

					// menghitung total Pasien
					$total = $jumCowok + $jumCewek;

					// menghitung prosentase laki-laki dan perempuan
					$prosenCowok = $jumCowok/$total * 100;
					$prosenCewek = $jumCewek/$total * 100;
					$prosen1=number_format($prosenCowok,2);
					$prosen2=number_format($prosenCewek,2);

					// menentukan panjang grafik batang berdasarkan prosentase
					$panjangGrafikCowok = $prosenCowok * 30 / 100;
					$panjangGrafikCewek = $prosenCewek * 30 / 100;

					?>
					<h3>Rekapitulasi Operasi</h3> <br>
					<h4>Berdasarkan Jenis Kelamin</h4><br>
					<p><b>Laki-laki</b><br>Jumlah: <?php echo "$jumCowok Orang"; ?> <br> <?php echo "Persentase : $prosen1"; ?>%)
					<div style="height: 20px; width: <?php echo $panjangGrafikCowok; ?>%; background-color: #FC6605;"></div></p>
					<p><b>Perempuan</b><br>Jumlah: <?php echo "$jumCewek Orang"; ?> <br> <?php echo "Persentase : $prosen2"; ?>%) 
					<div style="height: 20px; width: <?php echo $panjangGrafikCewek; ?>%; background-color: #2288BB;"></div></p>

					<br>
					<?php
					echo "dari jumlah pasien : $total Orang";
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
