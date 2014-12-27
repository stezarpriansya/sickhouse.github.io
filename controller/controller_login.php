<?php
if(isset($_POST['submit'])){
$filter_username = mysql_real_escape_string($_POST['username']);
$filter_password = mysql_real_escape_string(md5($_POST['password']));
$query_login = mysql_query("SELECT * FROM user WHERE username = '$filter_username' AND password = '$filter_password'");
$hasil=mysql_fetch_array($query_login);
function checkLogin($cek){

	if(mysql_num_rows($cek) <= 0){
		// username atau password salah
		return false;
	} else {
		// informasi login benar
		return true;
	}
}
	if(checkLogin($query_login)){
		// set session
		
		//$_SESSION['username'] = $_POST['username'];
		$_SESSION['username'] = $hasil['username'];
		$_SESSION['tipe']=$hasil['tipe'];
		$_SESSION['id']=$hasil['user_id'];
		$_SESSION['password']=$_POST['password'];
		header('location: ./index.php');
	}
	else {
		echo 'sorry username or password incorrect';
	}

}
?>