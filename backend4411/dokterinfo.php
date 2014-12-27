<?php

// Global variable for table object
$dokter = NULL;

//
// Table class for dokter
//
class cdokter extends cTable {
	var $kode_dokter;
	var $nama_dokter;
	var $jenis_kelamin;
	var $tgl_lahir;
	var $foto_dokter;
	var $spesialisasi;
	var $alamat_dokter;
	var $kota_dokter;
	var $telepon;
	var $SIP;
	var $user_id;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'dokter';
		$this->TableName = 'dokter';
		$this->TableType = 'TABLE';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// kode_dokter
		$this->kode_dokter = new cField('dokter', 'dokter', 'x_kode_dokter', 'kode_dokter', '`kode_dokter`', '`kode_dokter`', 200, -1, FALSE, '`kode_dokter`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['kode_dokter'] = &$this->kode_dokter;

		// nama_dokter
		$this->nama_dokter = new cField('dokter', 'dokter', 'x_nama_dokter', 'nama_dokter', '`nama_dokter`', '`nama_dokter`', 200, -1, FALSE, '`nama_dokter`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['nama_dokter'] = &$this->nama_dokter;

		// jenis_kelamin
		$this->jenis_kelamin = new cField('dokter', 'dokter', 'x_jenis_kelamin', 'jenis_kelamin', '`jenis_kelamin`', '`jenis_kelamin`', 200, -1, FALSE, '`jenis_kelamin`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['jenis_kelamin'] = &$this->jenis_kelamin;

		// tgl_lahir
		$this->tgl_lahir = new cField('dokter', 'dokter', 'x_tgl_lahir', 'tgl_lahir', '`tgl_lahir`', 'DATE_FORMAT(`tgl_lahir`, \'%Y/%m/%d\')', 133, 5, FALSE, '`tgl_lahir`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->tgl_lahir->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateYMD"));
		$this->fields['tgl_lahir'] = &$this->tgl_lahir;

		// foto_dokter
		$this->foto_dokter = new cField('dokter', 'dokter', 'x_foto_dokter', 'foto_dokter', '`foto_dokter`', '`foto_dokter`', 201, -1, FALSE, '`foto_dokter`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['foto_dokter'] = &$this->foto_dokter;

		// spesialisasi
		$this->spesialisasi = new cField('dokter', 'dokter', 'x_spesialisasi', 'spesialisasi', '`spesialisasi`', '`spesialisasi`', 201, -1, FALSE, '`spesialisasi`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['spesialisasi'] = &$this->spesialisasi;

		// alamat_dokter
		$this->alamat_dokter = new cField('dokter', 'dokter', 'x_alamat_dokter', 'alamat_dokter', '`alamat_dokter`', '`alamat_dokter`', 201, -1, FALSE, '`alamat_dokter`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['alamat_dokter'] = &$this->alamat_dokter;

		// kota_dokter
		$this->kota_dokter = new cField('dokter', 'dokter', 'x_kota_dokter', 'kota_dokter', '`kota_dokter`', '`kota_dokter`', 201, -1, FALSE, '`kota_dokter`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['kota_dokter'] = &$this->kota_dokter;

		// telepon
		$this->telepon = new cField('dokter', 'dokter', 'x_telepon', 'telepon', '`telepon`', '`telepon`', 200, -1, FALSE, '`telepon`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['telepon'] = &$this->telepon;

		// SIP
		$this->SIP = new cField('dokter', 'dokter', 'x_SIP', 'SIP', '`SIP`', '`SIP`', 200, -1, FALSE, '`SIP`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['SIP'] = &$this->SIP;

		// user_id
		$this->user_id = new cField('dokter', 'dokter', 'x_user_id', 'user_id', '`user_id`', '`user_id`', 3, -1, FALSE, '`user_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->user_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['user_id'] = &$this->user_id;
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`dokter`";
	}

	function SqlFrom() { // For backward compatibility
    	return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
    	$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
    	return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
    	$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
    	return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
    	$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
    	return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
    	$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
    	return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
    	$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
    	return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
    	$this->_SqlOrderBy = $v;
	}

	// Check if Anonymous User is allowed
	function AllowAnonymousUser() {
		switch (@$this->PageID) {
			case "add":
			case "register":
			case "addopt":
				return FALSE;
			case "edit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return FALSE;
			case "delete":
				return FALSE;
			case "view":
				return FALSE;
			case "search":
				return FALSE;
			default:
				return FALSE;
		}
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		global $conn;
		$cnt = -1;
		if ($this->TableType == 'TABLE' || $this->TableType == 'VIEW') {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		global $conn;
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Update Table
	var $UpdateTable = "`dokter`";

	// INSERT statement
	function InsertSQL(&$rs) {
		global $conn;
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		global $conn;
		return $conn->Execute($this->InsertSQL($rs));
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "") {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL) {
		global $conn;
		return $conn->Execute($this->UpdateSQL($rs, $where));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "") {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if ($rs) {
			if (array_key_exists('kode_dokter', $rs))
				ew_AddFilter($where, ew_QuotedName('kode_dokter') . '=' . ew_QuotedValue($rs['kode_dokter'], $this->kode_dokter->FldDataType));
		}
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "") {
		global $conn;
		return $conn->Execute($this->DeleteSQL($rs, $where));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`kode_dokter` = '@kode_dokter@'";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		$sKeyFilter = str_replace("@kode_dokter@", ew_AdjustSql($this->kode_dokter->CurrentValue), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "dokterlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "dokterlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("dokterview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("dokterview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "dokteradd.php?" . $this->UrlParm($parm);
		else
			return "dokteradd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("dokteredit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("dokteradd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("dokterdelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->kode_dokter->CurrentValue)) {
			$sUrl .= "kode_dokter=" . urlencode($this->kode_dokter->CurrentValue);
		} else {
			return "javascript:alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return ew_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET)) {
			$arKeys[] = @$_GET["kode_dokter"]; // kode_dokter

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		foreach ($arKeys as $key) {
			$ar[] = $key;
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->kode_dokter->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {
		global $conn;

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->kode_dokter->setDbValue($rs->fields('kode_dokter'));
		$this->nama_dokter->setDbValue($rs->fields('nama_dokter'));
		$this->jenis_kelamin->setDbValue($rs->fields('jenis_kelamin'));
		$this->tgl_lahir->setDbValue($rs->fields('tgl_lahir'));
		$this->foto_dokter->setDbValue($rs->fields('foto_dokter'));
		$this->spesialisasi->setDbValue($rs->fields('spesialisasi'));
		$this->alamat_dokter->setDbValue($rs->fields('alamat_dokter'));
		$this->kota_dokter->setDbValue($rs->fields('kota_dokter'));
		$this->telepon->setDbValue($rs->fields('telepon'));
		$this->SIP->setDbValue($rs->fields('SIP'));
		$this->user_id->setDbValue($rs->fields('user_id'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// kode_dokter
		// nama_dokter
		// jenis_kelamin
		// tgl_lahir
		// foto_dokter
		// spesialisasi
		// alamat_dokter
		// kota_dokter
		// telepon
		// SIP
		// user_id
		// kode_dokter

		$this->kode_dokter->ViewValue = $this->kode_dokter->CurrentValue;
		$this->kode_dokter->ViewCustomAttributes = "";

		// nama_dokter
		$this->nama_dokter->ViewValue = $this->nama_dokter->CurrentValue;
		$this->nama_dokter->ViewCustomAttributes = "";

		// jenis_kelamin
		$this->jenis_kelamin->ViewValue = $this->jenis_kelamin->CurrentValue;
		$this->jenis_kelamin->ViewCustomAttributes = "";

		// tgl_lahir
		$this->tgl_lahir->ViewValue = $this->tgl_lahir->CurrentValue;
		$this->tgl_lahir->ViewValue = ew_FormatDateTime($this->tgl_lahir->ViewValue, 5);
		$this->tgl_lahir->ViewCustomAttributes = "";

		// foto_dokter
		$this->foto_dokter->ViewValue = $this->foto_dokter->CurrentValue;
		$this->foto_dokter->ViewCustomAttributes = "";

		// spesialisasi
		$this->spesialisasi->ViewValue = $this->spesialisasi->CurrentValue;
		$this->spesialisasi->ViewCustomAttributes = "";

		// alamat_dokter
		$this->alamat_dokter->ViewValue = $this->alamat_dokter->CurrentValue;
		$this->alamat_dokter->ViewCustomAttributes = "";

		// kota_dokter
		$this->kota_dokter->ViewValue = $this->kota_dokter->CurrentValue;
		$this->kota_dokter->ViewCustomAttributes = "";

		// telepon
		$this->telepon->ViewValue = $this->telepon->CurrentValue;
		$this->telepon->ViewCustomAttributes = "";

		// SIP
		$this->SIP->ViewValue = $this->SIP->CurrentValue;
		$this->SIP->ViewCustomAttributes = "";

		// user_id
		$this->user_id->ViewValue = $this->user_id->CurrentValue;
		$this->user_id->ViewCustomAttributes = "";

		// kode_dokter
		$this->kode_dokter->LinkCustomAttributes = "";
		$this->kode_dokter->HrefValue = "";
		$this->kode_dokter->TooltipValue = "";

		// nama_dokter
		$this->nama_dokter->LinkCustomAttributes = "";
		$this->nama_dokter->HrefValue = "";
		$this->nama_dokter->TooltipValue = "";

		// jenis_kelamin
		$this->jenis_kelamin->LinkCustomAttributes = "";
		$this->jenis_kelamin->HrefValue = "";
		$this->jenis_kelamin->TooltipValue = "";

		// tgl_lahir
		$this->tgl_lahir->LinkCustomAttributes = "";
		$this->tgl_lahir->HrefValue = "";
		$this->tgl_lahir->TooltipValue = "";

		// foto_dokter
		$this->foto_dokter->LinkCustomAttributes = "";
		$this->foto_dokter->HrefValue = "";
		$this->foto_dokter->TooltipValue = "";

		// spesialisasi
		$this->spesialisasi->LinkCustomAttributes = "";
		$this->spesialisasi->HrefValue = "";
		$this->spesialisasi->TooltipValue = "";

		// alamat_dokter
		$this->alamat_dokter->LinkCustomAttributes = "";
		$this->alamat_dokter->HrefValue = "";
		$this->alamat_dokter->TooltipValue = "";

		// kota_dokter
		$this->kota_dokter->LinkCustomAttributes = "";
		$this->kota_dokter->HrefValue = "";
		$this->kota_dokter->TooltipValue = "";

		// telepon
		$this->telepon->LinkCustomAttributes = "";
		$this->telepon->HrefValue = "";
		$this->telepon->TooltipValue = "";

		// SIP
		$this->SIP->LinkCustomAttributes = "";
		$this->SIP->HrefValue = "";
		$this->SIP->TooltipValue = "";

		// user_id
		$this->user_id->LinkCustomAttributes = "";
		$this->user_id->HrefValue = "";
		$this->user_id->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// kode_dokter
		$this->kode_dokter->EditAttrs["class"] = "form-control";
		$this->kode_dokter->EditCustomAttributes = "";
		$this->kode_dokter->EditValue = $this->kode_dokter->CurrentValue;
		$this->kode_dokter->ViewCustomAttributes = "";

		// nama_dokter
		$this->nama_dokter->EditAttrs["class"] = "form-control";
		$this->nama_dokter->EditCustomAttributes = "";
		$this->nama_dokter->EditValue = ew_HtmlEncode($this->nama_dokter->CurrentValue);
		$this->nama_dokter->PlaceHolder = ew_RemoveHtml($this->nama_dokter->FldCaption());

		// jenis_kelamin
		$this->jenis_kelamin->EditAttrs["class"] = "form-control";
		$this->jenis_kelamin->EditCustomAttributes = "";
		$this->jenis_kelamin->EditValue = ew_HtmlEncode($this->jenis_kelamin->CurrentValue);
		$this->jenis_kelamin->PlaceHolder = ew_RemoveHtml($this->jenis_kelamin->FldCaption());

		// tgl_lahir
		$this->tgl_lahir->EditAttrs["class"] = "form-control";
		$this->tgl_lahir->EditCustomAttributes = "";
		$this->tgl_lahir->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->tgl_lahir->CurrentValue, 5));
		$this->tgl_lahir->PlaceHolder = ew_RemoveHtml($this->tgl_lahir->FldCaption());

		// foto_dokter
		$this->foto_dokter->EditAttrs["class"] = "form-control";
		$this->foto_dokter->EditCustomAttributes = "";
		$this->foto_dokter->EditValue = ew_HtmlEncode($this->foto_dokter->CurrentValue);
		$this->foto_dokter->PlaceHolder = ew_RemoveHtml($this->foto_dokter->FldCaption());

		// spesialisasi
		$this->spesialisasi->EditAttrs["class"] = "form-control";
		$this->spesialisasi->EditCustomAttributes = "";
		$this->spesialisasi->EditValue = ew_HtmlEncode($this->spesialisasi->CurrentValue);
		$this->spesialisasi->PlaceHolder = ew_RemoveHtml($this->spesialisasi->FldCaption());

		// alamat_dokter
		$this->alamat_dokter->EditAttrs["class"] = "form-control";
		$this->alamat_dokter->EditCustomAttributes = "";
		$this->alamat_dokter->EditValue = ew_HtmlEncode($this->alamat_dokter->CurrentValue);
		$this->alamat_dokter->PlaceHolder = ew_RemoveHtml($this->alamat_dokter->FldCaption());

		// kota_dokter
		$this->kota_dokter->EditAttrs["class"] = "form-control";
		$this->kota_dokter->EditCustomAttributes = "";
		$this->kota_dokter->EditValue = ew_HtmlEncode($this->kota_dokter->CurrentValue);
		$this->kota_dokter->PlaceHolder = ew_RemoveHtml($this->kota_dokter->FldCaption());

		// telepon
		$this->telepon->EditAttrs["class"] = "form-control";
		$this->telepon->EditCustomAttributes = "";
		$this->telepon->EditValue = ew_HtmlEncode($this->telepon->CurrentValue);
		$this->telepon->PlaceHolder = ew_RemoveHtml($this->telepon->FldCaption());

		// SIP
		$this->SIP->EditAttrs["class"] = "form-control";
		$this->SIP->EditCustomAttributes = "";
		$this->SIP->EditValue = ew_HtmlEncode($this->SIP->CurrentValue);
		$this->SIP->PlaceHolder = ew_RemoveHtml($this->SIP->FldCaption());

		// user_id
		$this->user_id->EditAttrs["class"] = "form-control";
		$this->user_id->EditCustomAttributes = "";
		$this->user_id->EditValue = ew_HtmlEncode($this->user_id->CurrentValue);
		$this->user_id->PlaceHolder = ew_RemoveHtml($this->user_id->FldCaption());

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->kode_dokter->Exportable) $Doc->ExportCaption($this->kode_dokter);
					if ($this->nama_dokter->Exportable) $Doc->ExportCaption($this->nama_dokter);
					if ($this->jenis_kelamin->Exportable) $Doc->ExportCaption($this->jenis_kelamin);
					if ($this->tgl_lahir->Exportable) $Doc->ExportCaption($this->tgl_lahir);
					if ($this->foto_dokter->Exportable) $Doc->ExportCaption($this->foto_dokter);
					if ($this->spesialisasi->Exportable) $Doc->ExportCaption($this->spesialisasi);
					if ($this->alamat_dokter->Exportable) $Doc->ExportCaption($this->alamat_dokter);
					if ($this->kota_dokter->Exportable) $Doc->ExportCaption($this->kota_dokter);
					if ($this->telepon->Exportable) $Doc->ExportCaption($this->telepon);
					if ($this->SIP->Exportable) $Doc->ExportCaption($this->SIP);
					if ($this->user_id->Exportable) $Doc->ExportCaption($this->user_id);
				} else {
					if ($this->kode_dokter->Exportable) $Doc->ExportCaption($this->kode_dokter);
					if ($this->nama_dokter->Exportable) $Doc->ExportCaption($this->nama_dokter);
					if ($this->jenis_kelamin->Exportable) $Doc->ExportCaption($this->jenis_kelamin);
					if ($this->tgl_lahir->Exportable) $Doc->ExportCaption($this->tgl_lahir);
					if ($this->telepon->Exportable) $Doc->ExportCaption($this->telepon);
					if ($this->SIP->Exportable) $Doc->ExportCaption($this->SIP);
					if ($this->user_id->Exportable) $Doc->ExportCaption($this->user_id);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->kode_dokter->Exportable) $Doc->ExportField($this->kode_dokter);
						if ($this->nama_dokter->Exportable) $Doc->ExportField($this->nama_dokter);
						if ($this->jenis_kelamin->Exportable) $Doc->ExportField($this->jenis_kelamin);
						if ($this->tgl_lahir->Exportable) $Doc->ExportField($this->tgl_lahir);
						if ($this->foto_dokter->Exportable) $Doc->ExportField($this->foto_dokter);
						if ($this->spesialisasi->Exportable) $Doc->ExportField($this->spesialisasi);
						if ($this->alamat_dokter->Exportable) $Doc->ExportField($this->alamat_dokter);
						if ($this->kota_dokter->Exportable) $Doc->ExportField($this->kota_dokter);
						if ($this->telepon->Exportable) $Doc->ExportField($this->telepon);
						if ($this->SIP->Exportable) $Doc->ExportField($this->SIP);
						if ($this->user_id->Exportable) $Doc->ExportField($this->user_id);
					} else {
						if ($this->kode_dokter->Exportable) $Doc->ExportField($this->kode_dokter);
						if ($this->nama_dokter->Exportable) $Doc->ExportField($this->nama_dokter);
						if ($this->jenis_kelamin->Exportable) $Doc->ExportField($this->jenis_kelamin);
						if ($this->tgl_lahir->Exportable) $Doc->ExportField($this->tgl_lahir);
						if ($this->telepon->Exportable) $Doc->ExportField($this->telepon);
						if ($this->SIP->Exportable) $Doc->ExportField($this->SIP);
						if ($this->user_id->Exportable) $Doc->ExportField($this->user_id);
					}
					$Doc->EndExportRow();
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		// Enter your code here
	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
