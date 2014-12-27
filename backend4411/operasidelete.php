<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "operasiinfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$operasi_delete = NULL; // Initialize page object first

class coperasi_delete extends coperasi {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{E4257960-51C0-4B8A-8F41-FDCC3F20971D}";

	// Table name
	var $TableName = 'operasi';

	// Page object name
	var $PageObjName = 'operasi_delete';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME]);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		$GLOBALS["Page"] = &$this;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (operasi)
		if (!isset($GLOBALS["operasi"]) || get_class($GLOBALS["operasi"]) == "coperasi") {
			$GLOBALS["operasi"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["operasi"];
		}

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// User table object (user)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'operasi', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		$Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		$Security->TablePermission_Loaded();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ew_GetUrl("operasilist.php"));
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn, $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $operasi;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($operasi);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		$conn->Close();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("operasilist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in operasi class, operasiinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		switch ($this->CurrentAction) {
			case "D": // Delete
				$this->SendEmail = TRUE; // Send email on delete success
				if ($this->DeleteRows()) { // Delete rows
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				}
		}
	}

// No functions
	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {
		global $conn;

		// Load List page SQL
		$sSql = $this->SelectSQL();

		// Load recordset
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
		$conn->raiseErrorFn = '';

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		global $conn;
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->kode_operasi->setDbValue($rs->fields('kode_operasi'));
		$this->kode_diagnosa->setDbValue($rs->fields('kode_diagnosa'));
		$this->kode_pasien->setDbValue($rs->fields('kode_pasien'));
		$this->id_tim->setDbValue($rs->fields('id_tim'));
		$this->kode_ruang->setDbValue($rs->fields('kode_ruang'));
		$this->jam_mulai->setDbValue($rs->fields('jam_mulai'));
		$this->jam_berakhir->setDbValue($rs->fields('jam_berakhir'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->kode_operasi->DbValue = $row['kode_operasi'];
		$this->kode_diagnosa->DbValue = $row['kode_diagnosa'];
		$this->kode_pasien->DbValue = $row['kode_pasien'];
		$this->id_tim->DbValue = $row['id_tim'];
		$this->kode_ruang->DbValue = $row['kode_ruang'];
		$this->jam_mulai->DbValue = $row['jam_mulai'];
		$this->jam_berakhir->DbValue = $row['jam_berakhir'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// kode_operasi
		// kode_diagnosa
		// kode_pasien
		// id_tim
		// kode_ruang
		// jam_mulai
		// jam_berakhir

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $conn, $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['kode_operasi'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "operasilist.php", "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($operasi_delete)) $operasi_delete = new coperasi_delete();

// Page init
$operasi_delete->Page_Init();

// Page main
$operasi_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$operasi_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var operasi_delete = new ew_Page("operasi_delete");
operasi_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = operasi_delete.PageID; // For backward compatibility

// Form object
var foperasidelete = new ew_Form("foperasidelete");

// Form_CustomValidate event
foperasidelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
foperasidelete.ValidateRequired = true;
<?php } else { ?>
foperasidelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($operasi_delete->Recordset = $operasi_delete->LoadRecordset())
	$operasi_deleteTotalRecs = $operasi_delete->Recordset->RecordCount(); // Get record count
if ($operasi_deleteTotalRecs <= 0) { // No record found, exit
	if ($operasi_delete->Recordset)
		$operasi_delete->Recordset->Close();
	$operasi_delete->Page_Terminate("operasilist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $operasi_delete->ShowPageHeader(); ?>
<?php
$operasi_delete->ShowMessage();
?>
<form name="foperasidelete" id="foperasidelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($operasi_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $operasi_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="operasi">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($operasi_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $operasi->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($operasi->kode_operasi->Visible) { // kode_operasi ?>
		<th><span id="elh_operasi_kode_operasi" class="operasi_kode_operasi"><?php echo $operasi->kode_operasi->FldCaption() ?></span></th>
<?php } ?>
<?php if ($operasi->kode_diagnosa->Visible) { // kode_diagnosa ?>
		<th><span id="elh_operasi_kode_diagnosa" class="operasi_kode_diagnosa"><?php echo $operasi->kode_diagnosa->FldCaption() ?></span></th>
<?php } ?>
<?php if ($operasi->kode_pasien->Visible) { // kode_pasien ?>
		<th><span id="elh_operasi_kode_pasien" class="operasi_kode_pasien"><?php echo $operasi->kode_pasien->FldCaption() ?></span></th>
<?php } ?>
<?php if ($operasi->id_tim->Visible) { // id_tim ?>
		<th><span id="elh_operasi_id_tim" class="operasi_id_tim"><?php echo $operasi->id_tim->FldCaption() ?></span></th>
<?php } ?>
<?php if ($operasi->kode_ruang->Visible) { // kode_ruang ?>
		<th><span id="elh_operasi_kode_ruang" class="operasi_kode_ruang"><?php echo $operasi->kode_ruang->FldCaption() ?></span></th>
<?php } ?>
<?php if ($operasi->jam_mulai->Visible) { // jam_mulai ?>
		<th><span id="elh_operasi_jam_mulai" class="operasi_jam_mulai"><?php echo $operasi->jam_mulai->FldCaption() ?></span></th>
<?php } ?>
<?php if ($operasi->jam_berakhir->Visible) { // jam_berakhir ?>
		<th><span id="elh_operasi_jam_berakhir" class="operasi_jam_berakhir"><?php echo $operasi->jam_berakhir->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$operasi_delete->RecCnt = 0;
$i = 0;
while (!$operasi_delete->Recordset->EOF) {
	$operasi_delete->RecCnt++;
	$operasi_delete->RowCnt++;

	// Set row properties
	$operasi->ResetAttrs();
	$operasi->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$operasi_delete->LoadRowValues($operasi_delete->Recordset);

	// Render row
	$operasi_delete->RenderRow();
?>
	<tr<?php echo $operasi->RowAttributes() ?>>
<?php if ($operasi->kode_operasi->Visible) { // kode_operasi ?>
		<td<?php echo $operasi->kode_operasi->CellAttributes() ?>>
<span id="el<?php echo $operasi_delete->RowCnt ?>_operasi_kode_operasi" class="form-group operasi_kode_operasi">
<span<?php echo $operasi->kode_operasi->ViewAttributes() ?>>
<?php echo $operasi->kode_operasi->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($operasi->kode_diagnosa->Visible) { // kode_diagnosa ?>
		<td<?php echo $operasi->kode_diagnosa->CellAttributes() ?>>
<span id="el<?php echo $operasi_delete->RowCnt ?>_operasi_kode_diagnosa" class="form-group operasi_kode_diagnosa">
<span<?php echo $operasi->kode_diagnosa->ViewAttributes() ?>>
<?php echo $operasi->kode_diagnosa->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($operasi->kode_pasien->Visible) { // kode_pasien ?>
		<td<?php echo $operasi->kode_pasien->CellAttributes() ?>>
<span id="el<?php echo $operasi_delete->RowCnt ?>_operasi_kode_pasien" class="form-group operasi_kode_pasien">
<span<?php echo $operasi->kode_pasien->ViewAttributes() ?>>
<?php echo $operasi->kode_pasien->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($operasi->id_tim->Visible) { // id_tim ?>
		<td<?php echo $operasi->id_tim->CellAttributes() ?>>
<span id="el<?php echo $operasi_delete->RowCnt ?>_operasi_id_tim" class="form-group operasi_id_tim">
<span<?php echo $operasi->id_tim->ViewAttributes() ?>>
<?php echo $operasi->id_tim->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($operasi->kode_ruang->Visible) { // kode_ruang ?>
		<td<?php echo $operasi->kode_ruang->CellAttributes() ?>>
<span id="el<?php echo $operasi_delete->RowCnt ?>_operasi_kode_ruang" class="form-group operasi_kode_ruang">
<span<?php echo $operasi->kode_ruang->ViewAttributes() ?>>
<?php echo $operasi->kode_ruang->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($operasi->jam_mulai->Visible) { // jam_mulai ?>
		<td<?php echo $operasi->jam_mulai->CellAttributes() ?>>
<span id="el<?php echo $operasi_delete->RowCnt ?>_operasi_jam_mulai" class="form-group operasi_jam_mulai">
<span<?php echo $operasi->jam_mulai->ViewAttributes() ?>>
<?php echo $operasi->jam_mulai->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($operasi->jam_berakhir->Visible) { // jam_berakhir ?>
		<td<?php echo $operasi->jam_berakhir->CellAttributes() ?>>
<span id="el<?php echo $operasi_delete->RowCnt ?>_operasi_jam_berakhir" class="form-group operasi_jam_berakhir">
<span<?php echo $operasi->jam_berakhir->ViewAttributes() ?>>
<?php echo $operasi->jam_berakhir->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$operasi_delete->Recordset->MoveNext();
}
$operasi_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div class="btn-group ewButtonGroup">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
</div>
</form>
<script type="text/javascript">
foperasidelete.Init();
</script>
<?php
$operasi_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$operasi_delete->Page_Terminate();
?>
