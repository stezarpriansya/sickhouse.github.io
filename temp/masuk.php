<?php

$link = mysql_connect($server, $db_user, $db_pass)
or die ("Could not connect to mysql because ".mysql_error());
// pilih database
mysql_select_db($database)
or die ("Could not select database because ".mysql_error());
$match = "select id from $table where username = '".$_POST['username']."'
and password = '".$_POST['password']."';";
$qry = mysql_query($match)
or die ("Could not match data because ".mysql_error());
$num_rows = mysql_num_rows($qry);
if ($num_rows <= 0) {
echo "Maaf, tidak ada username $username dengan password tersebut.<br>";
echo "<a href=login.html>Coba lagi</a>";
exit;
} else {
setcookie("loggedin", "TRUE", time()+(3600 * 24));
setcookie("mysite_username", "$username");
echo "Anda telah login!<br>";
echo "Lanjutkan ke <a href=members.php>member</a> area.";
}
?> 