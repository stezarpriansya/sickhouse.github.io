<?php
// PROSES REGISTRASI DOKTER
if(isset($_POST['submit'])){
	include_once("./../lib/koneksi.php");
	$query=mysql_query("SELECT MAX(kode_dokter) as kode FROM dokter");
	$result=mysql_fetch_array($query);
	$maxkode="$result[kode]";

	$password2 = $_POST['pwd'];
	$pass	= $_POST['password'];
	$nama	= $_POST['nama'];
	$jeniskel = $_POST['klmn'];
	$tgl	= $_POST['tgl'];
	//$ava	= $_POST['atr'];
	$spesial= $_POST['spesial'];
	$alamat	= $_POST['alamat'];
	$kota	= $_POST['kota'];
	$telepon= $_POST['telepon'];
	$sip	= $_POST['sip'];
	$user	= $_POST['username'];
	
 
	if($nama && $jeniskel && $user && $pass && $password2 && $tgl && $spesial && $alamat && $kota && $telepon && $sip){
			if($pass == $password2){
				$cek = mysql_query("SELECT * FROM user WHERE username='$user'");
				$num = mysql_num_rows($cek);
 
				if($num == 0){
					$maxkode++;
					$EN_PASS=md5($pass);
					$query1=mysql_query("SELECT MAX(`user_id`) as id_max FROM user");
					$result1=mysql_fetch_array($query1);
					$iddokter=$result1['id_max'];
					$iddokter++;
					
					$insert = mysql_query("INSERT INTO user VALUES($iddokter, '$user', '$EN_PASS', 1)");
					$insert1= mysql_query("INSERT INTO dokter VALUES('$maxkode', '$nama', '$jeniskel', '$tgl', NULL, '$spesial', '$alamat', '$kota', '$telepon', '$sip', $iddokter)");
 
					if($insert && $insert1){
						session_start();
						ob_start();
						$_SESSION['username'] = $user;
						$_SESSION['tipe']=1;
						$_SESSION['id']=$iddokter;
						$_SESSION['password']=$pass;
						echo '<p><b>Selamat... Anda berhasil Register!<br>
						Sekarang anda akan ter-redirect ke halaman Profile, dengan waktu mundur 3 detik :)</b></p>';
 						header("Refresh: 3; url=./../index.php");
					} else {
						echo '<p>Gagal melakukan Register, coba lagi!</p>';
					}
				} else {
					echo '<p>Username sudah terdaftar, pilih Username lain!</p>';
				}
			} else {
				echo '<p>Ulangi Password yang sama!</p>';
			}
		}
	else {
		echo '<p>Semua kolom wajib Anda isi!</p>';
	}
}
?>