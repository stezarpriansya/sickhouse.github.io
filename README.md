sickhouse
=========

final project pengantar basis data

===================================================================================================================
CARA PAKAI:
1. Buka XAMPP, start process APACHE dan MySQL
2. Import rumahsakitfpfix.sql melalui PHPMyAdmin atau database tools lainnya
3. Taruh file dalam folder sickhouse kedalam folder htdocs
4. Apabila root mysql memakai password, maka buka file backend4411/ewcfg11.php, kemudian edit line 52 :
define("EW_CONN_PASS", '', TRUE); menjadi --> efine("EW_CONN_PASS", '<password root>', TRUE);
5. Jalankan webnya dengan alamat akses localhost/sickhouse atau 127.0.0.1/sickhouse

Credit :
teamOne
===================================================================================================================
