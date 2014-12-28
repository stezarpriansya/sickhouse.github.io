<?php
include( 'temp/headercari.php' );
?>

<style type="text/css">
	
	#content2 {
		min-height: 560px;
	}

	.hdcari {
		width: 100%;
		background: #00AD87;

	}

	.tbcari {
		background: #EEEEEE;
		min-height:300px;
		width: 850px;
		margin: 10px auto;
		margin-bottom: 10px;
	}

	table { 
	  width: 100%; 
	  border-collapse: collapse; 
	  margin: auto;
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
				<thead><tr><th>Kode Pasien</th><th>Nama Pasien</th><th>Tanggal Datang</th><th>kode_ruang</th></tr></thead>
					
				<?php
					include "lib/koneksi.php";
						$name= $_POST['cari']; //get the nama value from form
						$q="SELECT 
							nama_pasien, 
							nama_ruang, 
							gedung, 
							tgl_datang
							FROM (
							SELECT 
							pasien.kode_pasien, 
							pasien.nama_pasien, 
							pasien.tgl_datang, 
							rawat_inap.kode_ruang
							FROM pasien
							LEFT OUTER JOIN rawat_inap 
							ON pasien.kode_pasien = rawat_inap.kode_pasien
							WHERE nama_pasien LIKE '%".$name."%') 
							AS A
							LEFT OUTER JOIN ruang ON A.kode_ruang = ruang.kode_ruang";  //query to get the search result
						$result = mysql_query($q); //execute the query $q
					while ($data = mysql_fetch_array($result)){
					?>
					<tbody>
						<tr>
					  <td><?php echo"$data[kode_pasien]"; ?></td>
					  <td><?php echo"$data[nama_pasien]"; ?></td>
					  <td><?php echo"$data[tgl_datang]"; ?></td>
					  <td><?php echo"$data[kode_ruang]"; ?></td>
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
