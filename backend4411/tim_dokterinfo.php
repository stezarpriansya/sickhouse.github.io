<?php

// Global variable for table object
$tim_dokter = NULL;

//
// Table class for tim_dokter
//
class ctim_dokter extends cTable {
	var $id_tim;
	var $dokter1;
	var $peran1;
	var $dokter2;
	var $peran2;
	var $dokter3;
	var $peran3;
	var $dokter4;
	var $peran4;
	var $dokter5;
	var $peran5;
	var $dokter6;
	var $peran6;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'tim_dokter';
		$this->TableName = 'tim_dokter';
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

		// id_tim
		$this->id_tim = new cField('tim_dokter', 'tim_dokter', 'x_id_tim', 'id_tim', '`id_tim`', '`id_tim`', 200, -1, FALSE, '`id_tim`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['id_tim'] = &$this->id_tim;

		// dokter1
		$this->dokter1 = new cField('tim_dokter', 'tim_dokter', 'x_dokter1', 'dokter1', '`dokter1`', '`dokter1`', 200, -1, FALSE, '`dokter1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['dokter1'] = &$this->dokter1;

		// peran1
		$this->peran1 = new cField('tim_dokter', 'tim_dokter', 'x_peran1', 'peran1', '`peran1`', '`peran1`', 201, -1, FALSE, '`peran1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['peran1'] = &$this->peran1;

		// dokter2
		$this->dokter2 = new cField('tim_dokter', 'tim_dokter', 'x_dokter2', 'dokter2', '`dokter2`', '`dokter2`', 200, -1, FALSE, '`dokter2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['dokter2'] = &$this->dokter2;

		// peran2
		$this->peran2 = new cField('tim_dokter', 'tim_dokter', 'x_peran2', 'peran2', '`peran2`', '`peran2`', 201, -1, FALSE, '`peran2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['peran2'] = &$this->peran2;

		// dokter3
		$this->dokter3 = new cField('tim_dokter', 'tim_dokter', 'x_dokter3', 'dokter3', '`dokter3`', '`dokter3`', 200, -1, FALSE, '`dokter3`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['dokter3'] = &$this->dokter3;

		// peran3
		$this->peran3 = new cField('tim_dokter', 'tim_dokter', 'x_peran3', 'peran3', '`peran3`', '`peran3`', 201, -1, FALSE, '`peran3`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['peran3'] = &$this->peran3;

		// dokter4
		$this->dokter4 = new cField('tim_dokter', 'tim_dokter', 'x_dokter4', 'dokter4', '`dokter4`', '`dokter4`', 200, -1, FALSE, '`dokter4`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['dokter4'] = &$this->dokter4;

		// peran4
		$this->peran4 = new cField('tim_dokter', 'tim_dokter', 'x_peran4', 'peran4', '`peran4`', '`peran4`', 201, -1, FALSE, '`peran4`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['peran4'] = &$this->peran4;

		// dokter5
		$this->dokter5 = new cField('tim_dokter', 'tim_dokter', 'x_dokter5', 'dokter5', '`dokter5`', '`dokter5`', 200, -1, FALSE, '`dokter5`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['dokter5'] = &$this->dokter5;

		// peran5
		$this->peran5 = new cField('tim_dokter', 'tim_dokter', 'x_peran5', 'peran5', '`peran5`', '`peran5`', 201, -1, FALSE, '`peran5`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['peran5'] = &$this->peran5;

		// dokter6
		$this->dokter6 = new cField('tim_dokter', 'tim_dokter', 'x_dokter6', 'dokter6', '`dokter6`', '`dokter6`', 200, -1, FALSE, '`dokter6`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['dokter6'] = &$this->dokter6;

		// peran6
		$this->peran6 = new cField('tim_dokter', 'tim_dokter', 'x_peran6', 'peran6', '`peran6`', '`peran6`', 201, -1, FALSE, '`peran6`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['peran6'] = &$this->peran6;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`tim_dokter`";
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
	var $UpdateTable = "`tim_dokter`";

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
			if (array_key_exists('id_tim', $rs))
				ew_AddFilter($where, ew_QuotedName('id_tim') . '=' . ew_QuotedValue($rs['id_tim'], $this->id_tim->FldDataType));
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
		return "`id_tim` = '@id_tim@'";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		$sKeyFilter = str_replace("@id_tim@", ew_AdjustSql($this->id_tim->CurrentValue), $sKeyFilter); // Replace key value
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
			return "tim_dokterlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "tim_dokterlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("tim_dokterview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("tim_dokterview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "tim_dokteradd.php?" . $this->UrlParm($parm);
		else
			return "tim_dokteradd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("tim_dokteredit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("tim_dokteradd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("tim_dokterdelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id_tim->CurrentValue)) {
			$sUrl .= "id_tim=" . urlencode($this->id_tim->CurrentValue);
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
			$arKeys[] = @$_GET["id_tim"]; // id_tim

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
			$this->id_tim->CurrentValue = $key;
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
		$this->id_tim->setDbValue($rs->fields('id_tim'));
		$this->dokter1->setDbValue($rs->fields('dokter1'));
		$this->peran1->setDbValue($rs->fields('peran1'));
		$this->dokter2->setDbValue($rs->fields('dokter2'));
		$this->peran2->setDbValue($rs->fields('peran2'));
		$this->dokter3->setDbValue($rs->fields('dokter3'));
		$this->peran3->setDbValue($rs->fields('peran3'));
		$this->dokter4->setDbValue($rs->fields('dokter4'));
		$this->peran4->setDbValue($rs->fields('peran4'));
		$this->dokter5->setDbValue($rs->fields('dokter5'));
		$this->peran5->setDbValue($rs->fields('peran5'));
		$this->dokter6->setDbValue($rs->fields('dokter6'));
		$this->peran6->setDbValue($rs->fields('peran6'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// id_tim
		// dokter1
		// peran1
		// dokter2
		// peran2
		// dokter3
		// peran3
		// dokter4
		// peran4
		// dokter5
		// peran5
		// dokter6
		// peran6
		// id_tim

		$this->id_tim->ViewValue = $this->id_tim->CurrentValue;
		$this->id_tim->ViewCustomAttributes = "";

		// dokter1
		$this->dokter1->ViewValue = $this->dokter1->CurrentValue;
		$this->dokter1->ViewCustomAttributes = "";

		// peran1
		$this->peran1->ViewValue = $this->peran1->CurrentValue;
		$this->peran1->ViewCustomAttributes = "";

		// dokter2
		$this->dokter2->ViewValue = $this->dokter2->CurrentValue;
		$this->dokter2->ViewCustomAttributes = "";

		// peran2
		$this->peran2->ViewValue = $this->peran2->CurrentValue;
		$this->peran2->ViewCustomAttributes = "";

		// dokter3
		$this->dokter3->ViewValue = $this->dokter3->CurrentValue;
		$this->dokter3->ViewCustomAttributes = "";

		// peran3
		$this->peran3->ViewValue = $this->peran3->CurrentValue;
		$this->peran3->ViewCustomAttributes = "";

		// dokter4
		$this->dokter4->ViewValue = $this->dokter4->CurrentValue;
		$this->dokter4->ViewCustomAttributes = "";

		// peran4
		$this->peran4->ViewValue = $this->peran4->CurrentValue;
		$this->peran4->ViewCustomAttributes = "";

		// dokter5
		$this->dokter5->ViewValue = $this->dokter5->CurrentValue;
		$this->dokter5->ViewCustomAttributes = "";

		// peran5
		$this->peran5->ViewValue = $this->peran5->CurrentValue;
		$this->peran5->ViewCustomAttributes = "";

		// dokter6
		$this->dokter6->ViewValue = $this->dokter6->CurrentValue;
		$this->dokter6->ViewCustomAttributes = "";

		// peran6
		$this->peran6->ViewValue = $this->peran6->CurrentValue;
		$this->peran6->ViewCustomAttributes = "";

		// id_tim
		$this->id_tim->LinkCustomAttributes = "";
		$this->id_tim->HrefValue = "";
		$this->id_tim->TooltipValue = "";

		// dokter1
		$this->dokter1->LinkCustomAttributes = "";
		$this->dokter1->HrefValue = "";
		$this->dokter1->TooltipValue = "";

		// peran1
		$this->peran1->LinkCustomAttributes = "";
		$this->peran1->HrefValue = "";
		$this->peran1->TooltipValue = "";

		// dokter2
		$this->dokter2->LinkCustomAttributes = "";
		$this->dokter2->HrefValue = "";
		$this->dokter2->TooltipValue = "";

		// peran2
		$this->peran2->LinkCustomAttributes = "";
		$this->peran2->HrefValue = "";
		$this->peran2->TooltipValue = "";

		// dokter3
		$this->dokter3->LinkCustomAttributes = "";
		$this->dokter3->HrefValue = "";
		$this->dokter3->TooltipValue = "";

		// peran3
		$this->peran3->LinkCustomAttributes = "";
		$this->peran3->HrefValue = "";
		$this->peran3->TooltipValue = "";

		// dokter4
		$this->dokter4->LinkCustomAttributes = "";
		$this->dokter4->HrefValue = "";
		$this->dokter4->TooltipValue = "";

		// peran4
		$this->peran4->LinkCustomAttributes = "";
		$this->peran4->HrefValue = "";
		$this->peran4->TooltipValue = "";

		// dokter5
		$this->dokter5->LinkCustomAttributes = "";
		$this->dokter5->HrefValue = "";
		$this->dokter5->TooltipValue = "";

		// peran5
		$this->peran5->LinkCustomAttributes = "";
		$this->peran5->HrefValue = "";
		$this->peran5->TooltipValue = "";

		// dokter6
		$this->dokter6->LinkCustomAttributes = "";
		$this->dokter6->HrefValue = "";
		$this->dokter6->TooltipValue = "";

		// peran6
		$this->peran6->LinkCustomAttributes = "";
		$this->peran6->HrefValue = "";
		$this->peran6->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// id_tim
		$this->id_tim->EditAttrs["class"] = "form-control";
		$this->id_tim->EditCustomAttributes = "";
		$this->id_tim->EditValue = $this->id_tim->CurrentValue;
		$this->id_tim->ViewCustomAttributes = "";

		// dokter1
		$this->dokter1->EditAttrs["class"] = "form-control";
		$this->dokter1->EditCustomAttributes = "";
		$this->dokter1->EditValue = ew_HtmlEncode($this->dokter1->CurrentValue);
		$this->dokter1->PlaceHolder = ew_RemoveHtml($this->dokter1->FldCaption());

		// peran1
		$this->peran1->EditAttrs["class"] = "form-control";
		$this->peran1->EditCustomAttributes = "";
		$this->peran1->EditValue = ew_HtmlEncode($this->peran1->CurrentValue);
		$this->peran1->PlaceHolder = ew_RemoveHtml($this->peran1->FldCaption());

		// dokter2
		$this->dokter2->EditAttrs["class"] = "form-control";
		$this->dokter2->EditCustomAttributes = "";
		$this->dokter2->EditValue = ew_HtmlEncode($this->dokter2->CurrentValue);
		$this->dokter2->PlaceHolder = ew_RemoveHtml($this->dokter2->FldCaption());

		// peran2
		$this->peran2->EditAttrs["class"] = "form-control";
		$this->peran2->EditCustomAttributes = "";
		$this->peran2->EditValue = ew_HtmlEncode($this->peran2->CurrentValue);
		$this->peran2->PlaceHolder = ew_RemoveHtml($this->peran2->FldCaption());

		// dokter3
		$this->dokter3->EditAttrs["class"] = "form-control";
		$this->dokter3->EditCustomAttributes = "";
		$this->dokter3->EditValue = ew_HtmlEncode($this->dokter3->CurrentValue);
		$this->dokter3->PlaceHolder = ew_RemoveHtml($this->dokter3->FldCaption());

		// peran3
		$this->peran3->EditAttrs["class"] = "form-control";
		$this->peran3->EditCustomAttributes = "";
		$this->peran3->EditValue = ew_HtmlEncode($this->peran3->CurrentValue);
		$this->peran3->PlaceHolder = ew_RemoveHtml($this->peran3->FldCaption());

		// dokter4
		$this->dokter4->EditAttrs["class"] = "form-control";
		$this->dokter4->EditCustomAttributes = "";
		$this->dokter4->EditValue = ew_HtmlEncode($this->dokter4->CurrentValue);
		$this->dokter4->PlaceHolder = ew_RemoveHtml($this->dokter4->FldCaption());

		// peran4
		$this->peran4->EditAttrs["class"] = "form-control";
		$this->peran4->EditCustomAttributes = "";
		$this->peran4->EditValue = ew_HtmlEncode($this->peran4->CurrentValue);
		$this->peran4->PlaceHolder = ew_RemoveHtml($this->peran4->FldCaption());

		// dokter5
		$this->dokter5->EditAttrs["class"] = "form-control";
		$this->dokter5->EditCustomAttributes = "";
		$this->dokter5->EditValue = ew_HtmlEncode($this->dokter5->CurrentValue);
		$this->dokter5->PlaceHolder = ew_RemoveHtml($this->dokter5->FldCaption());

		// peran5
		$this->peran5->EditAttrs["class"] = "form-control";
		$this->peran5->EditCustomAttributes = "";
		$this->peran5->EditValue = ew_HtmlEncode($this->peran5->CurrentValue);
		$this->peran5->PlaceHolder = ew_RemoveHtml($this->peran5->FldCaption());

		// dokter6
		$this->dokter6->EditAttrs["class"] = "form-control";
		$this->dokter6->EditCustomAttributes = "";
		$this->dokter6->EditValue = ew_HtmlEncode($this->dokter6->CurrentValue);
		$this->dokter6->PlaceHolder = ew_RemoveHtml($this->dokter6->FldCaption());

		// peran6
		$this->peran6->EditAttrs["class"] = "form-control";
		$this->peran6->EditCustomAttributes = "";
		$this->peran6->EditValue = ew_HtmlEncode($this->peran6->CurrentValue);
		$this->peran6->PlaceHolder = ew_RemoveHtml($this->peran6->FldCaption());

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
					if ($this->id_tim->Exportable) $Doc->ExportCaption($this->id_tim);
					if ($this->dokter1->Exportable) $Doc->ExportCaption($this->dokter1);
					if ($this->peran1->Exportable) $Doc->ExportCaption($this->peran1);
					if ($this->dokter2->Exportable) $Doc->ExportCaption($this->dokter2);
					if ($this->peran2->Exportable) $Doc->ExportCaption($this->peran2);
					if ($this->dokter3->Exportable) $Doc->ExportCaption($this->dokter3);
					if ($this->peran3->Exportable) $Doc->ExportCaption($this->peran3);
					if ($this->dokter4->Exportable) $Doc->ExportCaption($this->dokter4);
					if ($this->peran4->Exportable) $Doc->ExportCaption($this->peran4);
					if ($this->dokter5->Exportable) $Doc->ExportCaption($this->dokter5);
					if ($this->peran5->Exportable) $Doc->ExportCaption($this->peran5);
					if ($this->dokter6->Exportable) $Doc->ExportCaption($this->dokter6);
					if ($this->peran6->Exportable) $Doc->ExportCaption($this->peran6);
				} else {
					if ($this->id_tim->Exportable) $Doc->ExportCaption($this->id_tim);
					if ($this->dokter1->Exportable) $Doc->ExportCaption($this->dokter1);
					if ($this->dokter2->Exportable) $Doc->ExportCaption($this->dokter2);
					if ($this->dokter3->Exportable) $Doc->ExportCaption($this->dokter3);
					if ($this->dokter4->Exportable) $Doc->ExportCaption($this->dokter4);
					if ($this->dokter5->Exportable) $Doc->ExportCaption($this->dokter5);
					if ($this->dokter6->Exportable) $Doc->ExportCaption($this->dokter6);
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
						if ($this->id_tim->Exportable) $Doc->ExportField($this->id_tim);
						if ($this->dokter1->Exportable) $Doc->ExportField($this->dokter1);
						if ($this->peran1->Exportable) $Doc->ExportField($this->peran1);
						if ($this->dokter2->Exportable) $Doc->ExportField($this->dokter2);
						if ($this->peran2->Exportable) $Doc->ExportField($this->peran2);
						if ($this->dokter3->Exportable) $Doc->ExportField($this->dokter3);
						if ($this->peran3->Exportable) $Doc->ExportField($this->peran3);
						if ($this->dokter4->Exportable) $Doc->ExportField($this->dokter4);
						if ($this->peran4->Exportable) $Doc->ExportField($this->peran4);
						if ($this->dokter5->Exportable) $Doc->ExportField($this->dokter5);
						if ($this->peran5->Exportable) $Doc->ExportField($this->peran5);
						if ($this->dokter6->Exportable) $Doc->ExportField($this->dokter6);
						if ($this->peran6->Exportable) $Doc->ExportField($this->peran6);
					} else {
						if ($this->id_tim->Exportable) $Doc->ExportField($this->id_tim);
						if ($this->dokter1->Exportable) $Doc->ExportField($this->dokter1);
						if ($this->dokter2->Exportable) $Doc->ExportField($this->dokter2);
						if ($this->dokter3->Exportable) $Doc->ExportField($this->dokter3);
						if ($this->dokter4->Exportable) $Doc->ExportField($this->dokter4);
						if ($this->dokter5->Exportable) $Doc->ExportField($this->dokter5);
						if ($this->dokter6->Exportable) $Doc->ExportField($this->dokter6);
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
