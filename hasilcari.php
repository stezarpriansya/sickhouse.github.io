<?php
include( 'temp/headercari.php' );
?>

<style type="text/css">
	
	#content2 {
		min-height: 600px;
	}

	.hdcari {
		width: 100%;
		background: #00AD87;
		min-height: 120px;

	}

	.tbcari {
		background: #EEEEEE;
		width: 850px;
		margin: 10px auto;
		margin-bottom: 60px;
		overflow:auto;
		height:470px;
		border:1px #000000;
	}

	table { 
	  width: 100%; 
	  border-collapse: collapse; 
	  margin: auto;
	  overflow:auto;
	  height: 20px;
	}
	/* Zebra striping */
	tr:nth-of-type(odd) { 
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


	h2 {
		padding: 1%;
		text-align: center;
		color: #fff;
		padding: 6% 0% 0% 0%;
	}

	.hdcari img {
		width: 50px;
		height: 50px;
		position: absolute;
		left: 17%;
    	top: 63px;
	}

</style>


<div id="content2">
	<div class="hdcari">
		<a href="cari.php"><img src="images/back.png"></a>
		<h2>Result search for "<?php echo"$_POST[cari]"; ?>"</h2>
	</div>
<div class="clear"></div>
	<div class="tbcari">
		<table>
				<thead><tr><th>Nama pasien</th><th>Nama ruang</th><th>Gedung</th><th>Tanggal datang</th><th>Tanggal keluar</th></tr></thead>
					
				<?php
					include "lib/koneksi.php";
						$name= $_POST['cari']; //get the nama value from form
						$q="SELECT 
							nama_pasien, 
							nama_ruang, 
							gedung, 
							tgl_datang,
							tgl_keluar
							FROM (
							SELECT pasien.kode_pasien, pasien.nama_pasien, pasien.tgl_datang, pasien.tgl_keluar, rawat_inap.kode_ruang
							FROM pasien
							LEFT OUTER JOIN rawat_inap 
							ON pasien.kode_pasien = rawat_inap.kode_pasien
							WHERE nama_pasien LIKE '%".$name."%') 
							AS A
							LEFT OUTER JOIN ruang ON A.kode_ruang = ruang.kode_ruang
							ORDER BY tgl_datang DESC";  //query to get the search result
						$result = mysql_query($q); //execute the query $q
					while ($data = mysql_fetch_array($result)){
					?>
					<tbody>
						<tr>
					  <td><?php echo"$data[nama_pasien]"; ?></td>
					  <td><?php echo"$data[nama_ruang]"; ?></td>
					  <td><?php echo"$data[gedung]"; ?></td>
					  <td><?php echo"$data[tgl_datang]"; ?></td>
					  <td><?php echo"$data[tgl_keluar]"; ?></td>
					  </tr>
					 </tbody>
					<?php 
					}

					?>
		
		</table>
	</div>

</div>

<?php
include( 'temp/footer.php' );
