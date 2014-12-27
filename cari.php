<?php
$is_fullscreen = true;
include( 'temp/headercari.php' );
?>

<link rel="stylesheet" href="css/style1.css" type="text/css">

<div class="body">
		<img src="images/opo.png" class="imgBody">
		<img src="images/yeye.png" class="tulisan">
</div>

	<div class="cari">
		<form action="hasilcari.php" method="post">
			CARI<br>
			<input type="text" name="cari" placeholder="Cari pasien" title="masukkan nama pasien" style="color:#00AD87">
			<input type="submit" value="Cari" style="color:#00AD87">
		</form>
	</div>

<div class="foot">
	
</div>

<?php
include( 'temp/footer.php' );