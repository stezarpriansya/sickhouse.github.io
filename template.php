<table border="0">
<tr>
	<td valign="top">
	<!-- sidebar -->

		<div>
		Howdy,
		<?php
		echo $_SESSION['username'];
		?> (<a href="./index.php?menu=logout">Logout</a>)
		</div>


		<ul>
			<li><a href="./index.php">Home</a></li>
			<li><a href="./index.php?menu=katering">Katering</a></li>
		</ul>
	</td>
	<td valign="top"> 
	<!-- content -->
	<?php
	// controller template admin.
	if(isset($_GET['menu'])){
		if($_GET['menu'] == "logout"){
			// unset session
			session_destroy();
			header('location: ./index.php');
		} else if($_GET['menu'] == "katering"){
			include_once('./module/module_katering.php');
		}
	} else {
		echo 'Welcome bro :)';
		// saran dikasih dashboard
	}
	?>
	</td>
</tr>
</table>