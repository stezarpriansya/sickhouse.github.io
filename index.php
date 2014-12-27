<?php
ob_start();
session_start();
include_once('./lib/koneksi.php');
if(!isset($_SESSION['username'])){
	// harus login
	// ambil form login untuk interfacenya

	// fungsi menambah akun admin.
	include_once('./temp/headerawal.php');
	include_once('./form/form_login.php');
	include_once('./controller/controller_login.php');
} else {
	// memanggil file utama backend
	if ($_SESSION['tipe'] == 1) {
//echo " Anda Login sebagai User";
   //header('location:profile.php');
	//include_once('profile.php');
		include('temp/headerprofile.php');
		include_once('./profile.php');
   
	}
	else if ($_SESSION['tipe'] == -1) {
		//include( 'temp/headeradmin.php' );
    	header('location:./backend4411/login.php');
	}
}
include_once ('temp/footer.php');
?>