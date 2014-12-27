<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "tim_dokterinfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$tim_dokter_delete = NULL; // Initialize page object first

class ctim_dokter_delete extends ctim_dokter {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{E4257960-51C0-4B8A-8F41-FDCC3F20971D}";

	// Table name
	var $TableName = 'tim_dokter';

	// Page object name
	var $PageObjName = 'tim_dokter_delete';

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

		// Table object (tim_dokter)
		if (!isset($GLOBALS["tim_dokter"]) || get_class($GLOBALS["tim_dokter"]) == "ctim_dokter") {
			$GLOBALS["tim_dokter"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tim_dokter"];
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
			define("EW_TABLE_NAME", 'tim_dokter', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("tim_dokterlist.php"));
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
		global $EW_EXPORT, $tim_dokter;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($tim_dokter);
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
			$this->Page_Terminate("tim_dokterlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in tim_dokter class, tim_dokterinfo.php

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

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_tim->DbValue = $row['id_tim'];
		$this->dokter1->DbValue = $row['dokter1'];
		$this->peran1->DbValue = $row['peran1'];
		$this->dokter2->DbValue = $row['dokter2'];
		$this->peran2->DbValue = $row['peran2'];
		$this->dokter3->DbValue = $row['dokter3'];
		$this->peran3->DbValue = $row['peran3'];
		$this->dokter4->DbValue = $row['dokter4'];
		$this->peran4->DbValue = $row['peran4'];
		$this->dokter5->DbValue = $row['dokter5'];
		$this->peran5->DbValue = $row['peran5'];
		$this->dokter6->DbValue = $row['dokter6'];
		$this->peran6->DbValue = $row['peran6'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
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

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id_tim
			$this->id_tim->ViewValue = $this->id_tim->CurrentValue;
			$this->id_tim->ViewCustomAttributes = "";

			// dokter1
			$this->dokter1->ViewValue = $this->dokter1->CurrentValue;
			$this->dokter1->ViewCustomAttributes = "";

			// dokter2
			$this->dokter2->ViewValue = $this->dokter2->CurrentValue;
			$this->dokter2->ViewCustomAttributes = "";

			// dokter3
			$this->dokter3->ViewValue = $this->dokter3->CurrentValue;
			$this->dokter3->ViewCustomAttributes = "";

			// dokter4
			$this->dokter4->ViewValue = $this->dokter4->CurrentValue;
			$this->dokter4->ViewCustomAttributes = "";

			// dokter5
			$this->dokter5->ViewValue = $this->dokter5->CurrentValue;
			$this->dokter5->ViewCustomAttributes = "";

			// dokter6
			$this->dokter6->ViewValue = $this->dokter6->CurrentValue;
			$this->dokter6->ViewCustomAttributes = "";

			// id_tim
			$this->id_tim->LinkCustomAttributes = "";
			$this->id_tim->HrefValue = "";
			$this->id_tim->TooltipValue = "";

			// dokter1
			$this->dokter1->LinkCustomAttributes = "";
			$this->dokter1->HrefValue = "";
			$this->dokter1->TooltipValue = "";

			// dokter2
			$this->dokter2->LinkCustomAttributes = "";
			$this->dokter2->HrefValue = "";
			$this->dokter2->TooltipValue = "";

			// dokter3
			$this->dokter3->LinkCustomAttributes = "";
			$this->dokter3->HrefValue = "";
			$this->dokter3->TooltipValue = "";

			// dokter4
			$this->dokter4->LinkCustomAttributes = "";
			$this->dokter4->HrefValue = "";
			$this->dokter4->TooltipValue = "";

			// dokter5
			$this->dokter5->LinkCustomAttributes = "";
			$this->dokter5->HrefValue = "";
			$this->dokter5->TooltipValue = "";

			// dokter6
			$this->dokter6->LinkCustomAttributes = "";
			$this->dokter6->HrefValue = "";
			$this->dokter6->TooltipValue = "";
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
				$sThisKey .= $row['id_tim'];
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
		$Breadcrumb->Add("list", $this->TableVar, "tim_dokterlist.php", "", $this->TableVar, TRUE);
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
if (!isset($tim_dokter_delete)) $tim_dokter_delete = new ctim_dokter_delete();

// Page init
$tim_dokter_delete->Page_Init();

// Page main
$tim_dokter_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tim_dokter_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var tim_dokter_delete = new ew_Page("tim_dokter_delete");
tim_dokter_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = tim_dokter_delete.PageID; // For backward compatibility

// Form object
var ftim_dokterdelete = new ew_Form("ftim_dokterdelete");

// Form_CustomValidate event
ftim_dokterdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftim_dokterdelete.ValidateRequired = true;
<?php } else { ?>
ftim_dokterdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($tim_dokter_delete->Recordset = $tim_dokter_delete->LoadRecordset())
	$tim_dokter_deleteTotalRecs = $tim_dokter_delete->Recordset->RecordCount(); // Get record count
if ($tim_dokter_deleteTotalRecs <= 0) { // No record found, exit
	if ($tim_dokter_delete->Recordset)
		$tim_dokter_delete->Recordset->Close();
	$tim_dokter_delete->Page_Terminate("tim_dokterlist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $tim_dokter_delete->ShowPageHeader(); ?>
<?php
$tim_dokter_delete->ShowMessage();
?>
<form name="ftim_dokterdelete" id="ftim_dokterdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($tim_dokter_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $tim_dokter_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="tim_dokter">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($tim_dokter_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $tim_dokter->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($tim_dokter->id_tim->Visible) { // id_tim ?>
		<th><span id="elh_tim_dokter_id_tim" class="tim_dokter_id_tim"><?php echo $tim_dokter->id_tim->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tim_dokter->dokter1->Visible) { // dokter1 ?>
		<th><span id="elh_tim_dokter_dokter1" class="tim_dokter_dokter1"><?php echo $tim_dokter->dokter1->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tim_dokter->dokter2->Visible) { // dokter2 ?>
		<th><span id="elh_tim_dokter_dokter2" class="tim_dokter_dokter2"><?php echo $tim_dokter->dokter2->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tim_dokter->dokter3->Visible) { // dokter3 ?>
		<th><span id="elh_tim_dokter_dokter3" class="tim_dokter_dokter3"><?php echo $tim_dokter->dokter3->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tim_dokter->dokter4->Visible) { // dokter4 ?>
		<th><span id="elh_tim_dokter_dokter4" class="tim_dokter_dokter4"><?php echo $tim_dokter->dokter4->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tim_dokter->dokter5->Visible) { // dokter5 ?>
		<th><span id="elh_tim_dokter_dokter5" class="tim_dokter_dokter5"><?php echo $tim_dokter->dokter5->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tim_dokter->dokter6->Visible) { // dokter6 ?>
		<th><span id="elh_tim_dokter_dokter6" class="tim_dokter_dokter6"><?php echo $tim_dokter->dokter6->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$tim_dokter_delete->RecCnt = 0;
$i = 0;
while (!$tim_dokter_delete->Recordset->EOF) {
	$tim_dokter_delete->RecCnt++;
	$tim_dokter_delete->RowCnt++;

	// Set row properties
	$tim_dokter->ResetAttrs();
	$tim_dokter->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$tim_dokter_delete->LoadRowValues($tim_dokter_delete->Recordset);

	// Render row
	$tim_dokter_delete->RenderRow();
?>
	<tr<?php echo $tim_dokter->RowAttributes() ?>>
<?php if ($tim_dokter->id_tim->Visible) { // id_tim ?>
		<td<?php echo $tim_dokter->id_tim->CellAttributes() ?>>
<span id="el<?php echo $tim_dokter_delete->RowCnt ?>_tim_dokter_id_tim" class="form-group tim_dokter_id_tim">
<span<?php echo $tim_dokter->id_tim->ViewAttributes() ?>>
<?php echo $tim_dokter->id_tim->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tim_dokter->dokter1->Visible) { // dokter1 ?>
		<td<?php echo $tim_dokter->dokter1->CellAttributes() ?>>
<span id="el<?php echo $tim_dokter_delete->RowCnt ?>_tim_dokter_dokter1" class="form-group tim_dokter_dokter1">
<span<?php echo $tim_dokter->dokter1->ViewAttributes() ?>>
<?php echo $tim_dokter->dokter1->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tim_dokter->dokter2->Visible) { // dokter2 ?>
		<td<?php echo $tim_dokter->dokter2->CellAttributes() ?>>
<span id="el<?php echo $tim_dokter_delete->RowCnt ?>_tim_dokter_dokter2" class="form-group tim_dokter_dokter2">
<span<?php echo $tim_dokter->dokter2->ViewAttributes() ?>>
<?php echo $tim_dokter->dokter2->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tim_dokter->dokter3->Visible) { // dokter3 ?>
		<td<?php echo $tim_dokter->dokter3->CellAttributes() ?>>
<span id="el<?php echo $tim_dokter_delete->RowCnt ?>_tim_dokter_dokter3" class="form-group tim_dokter_dokter3">
<span<?php echo $tim_dokter->dokter3->ViewAttributes() ?>>
<?php echo $tim_dokter->dokter3->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tim_dokter->dokter4->Visible) { // dokter4 ?>
		<td<?php echo $tim_dokter->dokter4->CellAttributes() ?>>
<span id="el<?php echo $tim_dokter_delete->RowCnt ?>_tim_dokter_dokter4" class="form-group tim_dokter_dokter4">
<span<?php echo $tim_dokter->dokter4->ViewAttributes() ?>>
<?php echo $tim_dokter->dokter4->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tim_dokter->dokter5->Visible) { // dokter5 ?>
		<td<?php echo $tim_dokter->dokter5->CellAttributes() ?>>
<span id="el<?php echo $tim_dokter_delete->RowCnt ?>_tim_dokter_dokter5" class="form-group tim_dokter_dokter5">
<span<?php echo $tim_dokter->dokter5->ViewAttributes() ?>>
<?php echo $tim_dokter->dokter5->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tim_dokter->dokter6->Visible) { // dokter6 ?>
		<td<?php echo $tim_dokter->dokter6->CellAttributes() ?>>
<span id="el<?php echo $tim_dokter_delete->RowCnt ?>_tim_dokter_dokter6" class="form-group tim_dokter_dokter6">
<span<?php echo $tim_dokter->dokter6->ViewAttributes() ?>>
<?php echo $tim_dokter->dokter6->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$tim_dokter_delete->Recordset->MoveNext();
}
$tim_dokter_delete->Recordset->Close();
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
ftim_dokterdelete.Init();
</script>
<?php
$tim_dokter_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tim_dokter_delete->Page_Terminate();
?>
