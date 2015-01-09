<!DOCTYPE html>
<html>
<head>
	<link rel="icon" href="images/logo.png" type="image/x-icon">
	<link rel="stylesheet" href="css/style.css" type="text/css">
	<title>Pendaftaran - Sick House</title>
</head>
<body>
	<header>
		<div id="logo">
			<a href="./"><img src="images/logo.png" width="50px" height="50px"></a>
			<div><strong>RUMAH SAKIT PELITA HARAPAN</strong></div>
		
		</div>
		<div id="nav">
			<?php
			session_start();
			echo "<ul>";
			if(isset($_SESSION['username']))
			{
				echo "<li><a href='logout.php'>LOG OUT</a></li>";
				echo "<li><a href='status.php'>STATUS</a></li>";
				echo "<li><a href='profile.php'>HOME</a></li>";
			}
			else
			{
				echo "<li><a href='index.php'>LOG IN</a></li>";
			}
			echo "</ul>";
			?>
		</div>
		<div class="clear"></div>
	</header>
