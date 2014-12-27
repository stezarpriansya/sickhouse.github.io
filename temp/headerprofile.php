<!DOCTYPE html>
<html>
<head>
	<link rel="icon" href="images/logo.png" type="image/x-icon">
	<link rel="stylesheet" href="css/style.css" type="text/css">
	<title><?php echo $_SESSION['username'];?>'s Profile</title>
</head>
<body>
	<header>
		<div id="logo">
			<a href="#"><img src="images/logo.png" width="50px" height="50px"></a>
			<div><strong>RUMAH SAKIT PELITA HARAPAN</strong></div>
		
		</div>
		<div id="nav">
			<ul>
				<li><a href="status.php">STATUS</a></li>
				<li><a href="cari.php">CARI PASIEN</a></li>
				<li><a href="logout.php">LOG OUT</a></li>
			</ul>
		</div>
		<div class="clear"></div>
	</header>