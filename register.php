<?php
include_once('./lib/koneksi.php');
if(!isset($_SESSION['username'])){
	include_once('./temp/headercari.php');
	include_once('./form/form_register.php');
	include_once('./controller/controller_register.php');
} else {
    	header('location:./index.php');
	}
include_once ('temp/footer.php');
?>