<?php

// Global variable for table object
$operasi = NULL;

//
// Table class for operasi
//
class coperasi extends cTable {
	var $kode_operasi;
	var $kode_diagnosa;
	var $kode_pasien;
	var $id_tim;
	var $kode_ruang;
	var $jam_mulai;
	var $jam_berakhir;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'operasi';
		$this->TableName = 'operasi';
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

		// kode_operasi
		$this->kode_operasi = new cField('operasi', 'operasi', 'x_kode_operasi', 'kode_operasi', '`kode_operasi`', '`kode_operasi`', 200, -1, FALSE, '`kode_operasi`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['kode_operasi'] = &$this->kode_operasi;

		// kode_diagnosa
		$this->kode_diagnosa = new cField('operasi', 'operasi', 'x_kode_diagnosa', 'kode_diagnosa', '`kode_diagnosa`', '`kode_diagnosa`', 200, -1, FALSE, '`kode_diagnosa`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['kode_diagnosa'] = &$this->kode_diagnosa;

		// kode_pasien
		$this->kode_pasien = new cField('operasi', 'operasi', 'x_kode_pasien', 'kode_pasien', '`kode_pasien`', '`kode_pasien`', 200, -1, FALSE, '`kode_pasien`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['kode_pasien'] = &$this->kode_pasien;

		// id_tim
		$this->id_tim = new cField('operasi', 'operasi', 'x_id_tim', 'id_tim', '`id_tim`', '`id_tim`', 200, -1, FALSE, '`id_tim`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['id_tim'] = &$this->id_tim;

		// kode_ruang
		$this->kode_ruang = new cField('operasi', 'operasi', 'x_kode_ruang', 'kode_ruang', '`kode_ruang`', '`kode_ruang`', 200, -1, FALSE, '`kode_ruang`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['kode_ruang'] = &$this->kode_ruang;

		// jam_mulai
		$this->jam_mulai = new cField('operasi', 'operasi', 'x_jam_mulai', 'jam_mulai', '`jam_mulai`', 'DATE_FORMAT(`jam_mulai`, \'%Y/%m/%d\')', 135, 5, FALSE, '`jam_mulai`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->jam_mulai->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateYMD"));
		$this->fields['jam_mulai'] = &$this->jam_mulai;

		// jam_berakhir
		$this->jam_berakhir = new cField('operasi', 'operasi', 'x_jam_berakhir', 'jam_berakhir', '`jam_berakhir`', 'DATE_FORMAT(`jam_berakhir`, \'%Y/%m/%d\')', 135, 5, FALSE, '`jam_berakhir`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->jam_berakhir->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateYMD"));
		$this->fields['jam_berakhir'] = &$this->jam_berakhir;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`operasi`";
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
	var $UpdateTable = "`operasi`";

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
			if (array_key_exists('kode_operasi', $rs))
				ew_AddFilter($where, ew_QuotedName('kode_operasi') . '=' . ew_QuotedValue($rs['kode_operasi'], $this->kode_operasi->FldDataType));
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
		return "`kode_operasi` = '@kode_operasi@'";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		$sKeyFilter = str_replace("@kode_operasi@", ew_AdjustSql($this->kode_operasi->CurrentValue), $sKeyFilter); // Replace key value
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
			return "operasilist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "operasilist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("operasiview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("operasiview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "operasiadd.php?" . $this->UrlParm($parm);
		else
			return "operasiadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("operasiedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("operasiadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("operasidelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->kode_operasi->CurrentValue)) {
			$sUrl .= "kode_operasi=" . urlencode($this->kode_operasi->CurrentValue);
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
			$arKeys[] = @$_GET["kode_operasi"]; // kode_operasi

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
			$this->kode_operasi->CurrentValue = $key;
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
		$this->kode_operasi->setDbValue($rs->fields('kode_operasi'));
		$this->kode_diagnosa->setDbValue($rs->fields('kode_diagnosa'));
		$this->kode_pasien->setDbValue($rs->fields('kode_pasien'));
		$this->id_tim->setDbValue($rs->fields('id_tim'));
		$this->kode_ruang->setDbValue($rs->fields('kode_ruang'));
		$this->jam_mulai->setDbValue($rs->fields('jam_mulai'));
		$this->jam_berakhir->setDbValue($rs->fields('jam_berakhir'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// kode_operasi
		// kode_diagnosa
		// kode_pasien
		// id_tim
		// kode_ruang
		// jam_mulai
		// jam_berakhir
		// kode_operasi

		$this->kode_operasi->ViewValue = $this->kode_operasi->CurrentValue;
		$this->kode_operasi->ViewCustomAttributes = "";

		// kode_diagnosa
		$this->kode_diagnosa->ViewValue = $this->kode_diagnosa->CurrentValue;
		$this->kode_diagnosa->ViewCustomAttributes = "";

		// kode_pasien
		$this->kode_pasien->ViewValue = $this->kode_pasien->CurrentValue;
		$this->kode_pasien->ViewCustomAttributes = "";

		// id_tim
		$this->id_tim->ViewValue = $this->id_tim->CurrentValue;
		$this->id_tim->ViewCustomAttributes = "";

		// kode_ruang
		$this->kode_ruang->ViewValue = $this->kode_ruang->CurrentValue;
		$this->kode_ruang->ViewCustomAttributes = "";

		// jam_mulai
		$this->jam_mulai->ViewValue = $this->jam_mulai->CurrentValue;
		$this->jam_mulai->ViewValue = ew_FormatDateTime($this->jam_mulai->ViewValue, 5);
		$this->jam_mulai->ViewCustomAttributes = "";

		// jam_berakhir
		$this->jam_berakhir->ViewValue = $this->jam_berakhir->CurrentValue;
		$this->jam_berakhir->ViewValue = ew_FormatDateTime($this->jam_berakhir->ViewValue, 5);
		$this->jam_berakhir->ViewCustomAttributes = "";

		// kode_operasi
		$this->kode_operasi->LinkCustomAttributes = "";
		$this->kode_operasi->HrefValue = "";
		$this->kode_operasi->TooltipValue = "";

		// kode_diagnosa
		$this->kode_diagnosa->LinkCustomAttributes = "";
		$this->kode_diagnosa->HrefValue = "";
		$this->kode_diagnosa->TooltipValue = "";

		// kode_pasien
		$this->kode_pasien->LinkCustomAttributes = "";
		$this->kode_pasien->HrefValue = "";
		$this->kode_pasien->TooltipValue = "";

		// id_tim
		$this->id_tim->LinkCustomAttributes = "";
		$this->id_tim->HrefValue = "";
		$this->id_tim->TooltipValue = "";

		// kode_ruang
		$this->kode_ruang->LinkCustomAttributes = "";
		$this->kode_ruang->HrefValue = "";
		$this->kode_ruang->TooltipValue = "";

		// jam_mulai
		$this->jam_mulai->LinkCustomAttributes = "";
		$this->jam_mulai->HrefValue = "";
		$this->jam_mulai->TooltipValue = "";

		// jam_berakhir
		$this->jam_berakhir->LinkCustomAttributes = "";
		$this->jam_berakhir->HrefValue = "";
		$this->jam_berakhir->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// kode_operasi
		$this->kode_operasi->EditAttrs["class"] = "form-control";
		$this->kode_operasi->EditCustomAttributes = "";
		$this->kode_operasi->EditValue = $this->kode_operasi->CurrentValue;
		$this->kode_operasi->ViewCustomAttributes = "";

		// kode_diagnosa
		$this->kode_diagnosa->EditAttrs["class"] = "form-control";
		$this->kode_diagnosa->EditCustomAttributes = "";
		$this->kode_diagnosa->EditValue = ew_HtmlEncode($this->kode_diagnosa->CurrentValue);
		$this->kode_diagnosa->PlaceHolder = ew_RemoveHtml($this->kode_diagnosa->FldCaption());

		// kode_pasien
		$this->kode_pasien->EditAttrs["class"] = "form-control";
		$this->kode_pasien->EditCustomAttributes = "";
		$this->kode_pasien->EditValue = ew_HtmlEncode($this->kode_pasien->CurrentValue);
		$this->kode_pasien->PlaceHolder = ew_RemoveHtml($this->kode_pasien->FldCaption());

		// id_tim
		$this->id_tim->EditAttrs["class"] = "form-control";
		$this->id_tim->EditCustomAttributes = "";
		$this->id_tim->EditValue = ew_HtmlEncode($this->id_tim->CurrentValue);
		$this->id_tim->PlaceHolder = ew_RemoveHtml($this->id_tim->FldCaption());

		// kode_ruang
		$this->kode_ruang->EditAttrs["class"] = "form-control";
		$this->kode_ruang->EditCustomAttributes = "";
		$this->kode_ruang->EditValue = ew_HtmlEncode($this->kode_ruang->CurrentValue);
		$this->kode_ruang->PlaceHolder = ew_RemoveHtml($this->kode_ruang->FldCaption());

		// jam_mulai
		$this->jam_mulai->EditAttrs["class"] = "form-control";
		$this->jam_mulai->EditCustomAttributes = "";
		$this->jam_mulai->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->jam_mulai->CurrentValue, 5));
		$this->jam_mulai->PlaceHolder = ew_RemoveHtml($this->jam_mulai->FldCaption());

		// jam_berakhir
		$this->jam_berakhir->EditAttrs["class"] = "form-control";
		$this->jam_berakhir->EditCustomAttributes = "";
		$this->jam_berakhir->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->jam_berakhir->CurrentValue, 5));
		$this->jam_berakhir->PlaceHolder = ew_RemoveHtml($this->jam_berakhir->FldCaption());

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
					if ($this->kode_operasi->Exportable) $Doc->ExportCaption($this->kode_operasi);
					if ($this->kode_diagnosa->Exportable) $Doc->ExportCaption($this->kode_diagnosa);
					if ($this->kode_pasien->Exportable) $Doc->ExportCaption($this->kode_pasien);
					if ($this->id_tim->Exportable) $Doc->ExportCaption($this->id_tim);
					if ($this->kode_ruang->Exportable) $Doc->ExportCaption($this->kode_ruang);
					if ($this->jam_mulai->Exportable) $Doc->ExportCaption($this->jam_mulai);
					if ($this->jam_berakhir->Exportable) $Doc->ExportCaption($this->jam_berakhir);
				} else {
					if ($this->kode_operasi->Exportable) $Doc->ExportCaption($this->kode_operasi);
					if ($this->kode_diagnosa->Exportable) $Doc->ExportCaption($this->kode_diagnosa);
					if ($this->kode_pasien->Exportable) $Doc->ExportCaption($this->kode_pasien);
					if ($this->id_tim->Exportable) $Doc->ExportCaption($this->id_tim);
					if ($this->kode_ruang->Exportable) $Doc->ExportCaption($this->kode_ruang);
					if ($this->jam_mulai->Exportable) $Doc->ExportCaption($this->jam_mulai);
					if ($this->jam_berakhir->Exportable) $Doc->ExportCaption($this->jam_berakhir);
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
						if ($this->kode_operasi->Exportable) $Doc->ExportField($this->kode_operasi);
						if ($this->kode_diagnosa->Exportable) $Doc->ExportField($this->kode_diagnosa);
						if ($this->kode_pasien->Exportable) $Doc->ExportField($this->kode_pasien);
						if ($this->id_tim->Exportable) $Doc->ExportField($this->id_tim);
						if ($this->kode_ruang->Exportable) $Doc->ExportField($this->kode_ruang);
						if ($this->jam_mulai->Exportable) $Doc->ExportField($this->jam_mulai);
						if ($this->jam_berakhir->Exportable) $Doc->ExportField($this->jam_berakhir);
					} else {
						if ($this->kode_operasi->Exportable) $Doc->ExportField($this->kode_operasi);
						if ($this->kode_diagnosa->Exportable) $Doc->ExportField($this->kode_diagnosa);
						if ($this->kode_pasien->Exportable) $Doc->ExportField($this->kode_pasien);
						if ($this->id_tim->Exportable) $Doc->ExportField($this->id_tim);
						if ($this->kode_ruang->Exportable) $Doc->ExportField($this->kode_ruang);
						if ($this->jam_mulai->Exportable) $Doc->ExportField($this->jam_mulai);
						if ($this->jam_berakhir->Exportable) $Doc->ExportField($this->jam_berakhir);
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
