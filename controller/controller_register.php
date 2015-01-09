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
	$nama_gambar = $_FILES['atr']['name'];
	$tmp_gambar =  $_FILES['atr']['tmp_name'];
	$rand_text = rand(99999,239028302);
	$rand_gambar= $rand_text.$nama_gambar;
	$new_location = './../images/'.$rand_gambar;
	$fix_location = './images/'.$rand_gambar;
	$imageFileType = pathinfo($new_location,PATHINFO_EXTENSION);
	$check = getimagesize($tmp_gambar);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
    }
    if ($_FILES['atr']['size'] > 10000000) {
	    echo "Sorry, your file is too large.";
	    $uploadOk = 0;
	}
	// Allow certain file formats
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
	&& $imageFileType != "gif" ) {
	    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
	    $uploadOk = 0;
	}
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
					if ($uploadOk == 1) {
					    if (move_uploaded_file($tmp_gambar, $new_location)) {
					        $insert = mysql_query("INSERT INTO user VALUES($iddokter, '$user', '$EN_PASS', 1)");
							$insert1= mysql_query("INSERT INTO dokter VALUES('$maxkode', '$nama', '$jeniskel', '$tgl', '$fix_location', '$spesial', '$alamat', '$kota', '$telepon', '$sip', $iddokter)");
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
					        echo "<p>Maaf, tampaknya ada error ketika mengunggah foto anda, silakan coba kembali!<p>";
					} 
				} else {
					 echo "<p>File foto anda tidak dapat diunggah<p>";
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
