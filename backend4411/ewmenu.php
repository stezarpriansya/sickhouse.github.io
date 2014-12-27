<!-- Begin Main Menu -->
<?php $RootMenu = new cMenu(EW_MENUBAR_ID) ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(1, "mi_diagnosa", $Language->MenuPhrase("1", "MenuText"), "diagnosalist.php", -1, "", AllowListMenu('{E4257960-51C0-4B8A-8F41-FDCC3F20971D}diagnosa'), FALSE);
$RootMenu->AddMenuItem(2, "mi_dokter", $Language->MenuPhrase("2", "MenuText"), "dokterlist.php", -1, "", AllowListMenu('{E4257960-51C0-4B8A-8F41-FDCC3F20971D}dokter'), FALSE);
$RootMenu->AddMenuItem(3, "mi_operasi", $Language->MenuPhrase("3", "MenuText"), "operasilist.php", -1, "", AllowListMenu('{E4257960-51C0-4B8A-8F41-FDCC3F20971D}operasi'), FALSE);
$RootMenu->AddMenuItem(4, "mi_pasien", $Language->MenuPhrase("4", "MenuText"), "pasienlist.php", -1, "", AllowListMenu('{E4257960-51C0-4B8A-8F41-FDCC3F20971D}pasien'), FALSE);
$RootMenu->AddMenuItem(5, "mi_rawat_inap", $Language->MenuPhrase("5", "MenuText"), "rawat_inaplist.php", -1, "", AllowListMenu('{E4257960-51C0-4B8A-8F41-FDCC3F20971D}rawat_inap'), FALSE);
$RootMenu->AddMenuItem(6, "mi_ruang", $Language->MenuPhrase("6", "MenuText"), "ruanglist.php", -1, "", AllowListMenu('{E4257960-51C0-4B8A-8F41-FDCC3F20971D}ruang'), FALSE);
$RootMenu->AddMenuItem(7, "mi_tim_dokter", $Language->MenuPhrase("7", "MenuText"), "tim_dokterlist.php", -1, "", AllowListMenu('{E4257960-51C0-4B8A-8F41-FDCC3F20971D}tim_dokter'), FALSE);
$RootMenu->AddMenuItem(8, "mi_user", $Language->MenuPhrase("8", "MenuText"), "userlist.php", -1, "", AllowListMenu('{E4257960-51C0-4B8A-8F41-FDCC3F20971D}user'), FALSE);
$RootMenu->AddMenuItem(-1, "mi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
