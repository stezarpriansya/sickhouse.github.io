<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "ruanginfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$ruang_edit = NULL; // Initialize page object first

class cruang_edit extends cruang {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{E4257960-51C0-4B8A-8F41-FDCC3F20971D}";

	// Table name
	var $TableName = 'ruang';

	// Page object name
	var $PageObjName = 'ruang_edit';

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

		// Table object (ruang)
		if (!isset($GLOBALS["ruang"]) || get_class($GLOBALS["ruang"]) == "cruang") {
			$GLOBALS["ruang"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["ruang"];
		}

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// User table object (user)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'ruang', TRUE);

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
		if (!$Security->CanEdit()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ew_GetUrl("ruanglist.php"));
		}

		// Create form object
		$objForm = new cFormObj();
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
		global $EW_EXPORT, $ruang;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($ruang);
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
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["kode_ruang"] <> "") {
			$this->kode_ruang->setQueryStringValue($_GET["kode_ruang"]);
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->kode_ruang->CurrentValue == "")
			$this->Page_Terminate("ruanglist.php"); // Invalid key, return to list

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("ruanglist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = $this->getReturnUrl();
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->kode_ruang->FldIsDetailKey) {
			$this->kode_ruang->setFormValue($objForm->GetValue("x_kode_ruang"));
		}
		if (!$this->nama_ruang->FldIsDetailKey) {
			$this->nama_ruang->setFormValue($objForm->GetValue("x_nama_ruang"));
		}
		if (!$this->jenis_ruang->FldIsDetailKey) {
			$this->jenis_ruang->setFormValue($objForm->GetValue("x_jenis_ruang"));
		}
		if (!$this->gedung->FldIsDetailKey) {
			$this->gedung->setFormValue($objForm->GetValue("x_gedung"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->kode_ruang->CurrentValue = $this->kode_ruang->FormValue;
		$this->nama_ruang->CurrentValue = $this->nama_ruang->FormValue;
		$this->jenis_ruang->CurrentValue = $this->jenis_ruang->FormValue;
		$this->gedung->CurrentValue = $this->gedung->FormValue;
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
		$this->kode_ruang->setDbValue($rs->fields('kode_ruang'));
		$this->nama_ruang->setDbValue($rs->fields('nama_ruang'));
		$this->jenis_ruang->setDbValue($rs->fields('jenis_ruang'));
		$this->gedung->setDbValue($rs->fields('gedung'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->kode_ruang->DbValue = $row['kode_ruang'];
		$this->nama_ruang->DbValue = $row['nama_ruang'];
		$this->jenis_ruang->DbValue = $row['jenis_ruang'];
		$this->gedung->DbValue = $row['gedung'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// kode_ruang
		// nama_ruang
		// jenis_ruang
		// gedung

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// kode_ruang
			$this->kode_ruang->ViewValue = $this->kode_ruang->CurrentValue;
			$this->kode_ruang->ViewCustomAttributes = "";

			// nama_ruang
			$this->nama_ruang->ViewValue = $this->nama_ruang->CurrentValue;
			$this->nama_ruang->ViewCustomAttributes = "";

			// jenis_ruang
			$this->jenis_ruang->ViewValue = $this->jenis_ruang->CurrentValue;
			$this->jenis_ruang->ViewCustomAttributes = "";

			// gedung
			$this->gedung->ViewValue = $this->gedung->CurrentValue;
			$this->gedung->ViewCustomAttributes = "";

			// kode_ruang
			$this->kode_ruang->LinkCustomAttributes = "";
			$this->kode_ruang->HrefValue = "";
			$this->kode_ruang->TooltipValue = "";

			// nama_ruang
			$this->nama_ruang->LinkCustomAttributes = "";
			$this->nama_ruang->HrefValue = "";
			$this->nama_ruang->TooltipValue = "";

			// jenis_ruang
			$this->jenis_ruang->LinkCustomAttributes = "";
			$this->jenis_ruang->HrefValue = "";
			$this->jenis_ruang->TooltipValue = "";

			// gedung
			$this->gedung->LinkCustomAttributes = "";
			$this->gedung->HrefValue = "";
			$this->gedung->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// kode_ruang
			$this->kode_ruang->EditAttrs["class"] = "form-control";
			$this->kode_ruang->EditCustomAttributes = "";
			$this->kode_ruang->EditValue = $this->kode_ruang->CurrentValue;
			$this->kode_ruang->ViewCustomAttributes = "";

			// nama_ruang
			$this->nama_ruang->EditAttrs["class"] = "form-control";
			$this->nama_ruang->EditCustomAttributes = "";
			$this->nama_ruang->EditValue = ew_HtmlEncode($this->nama_ruang->CurrentValue);
			$this->nama_ruang->PlaceHolder = ew_RemoveHtml($this->nama_ruang->FldCaption());

			// jenis_ruang
			$this->jenis_ruang->EditAttrs["class"] = "form-control";
			$this->jenis_ruang->EditCustomAttributes = "";
			$this->jenis_ruang->EditValue = ew_HtmlEncode($this->jenis_ruang->CurrentValue);
			$this->jenis_ruang->PlaceHolder = ew_RemoveHtml($this->jenis_ruang->FldCaption());

			// gedung
			$this->gedung->EditAttrs["class"] = "form-control";
			$this->gedung->EditCustomAttributes = "";
			$this->gedung->EditValue = ew_HtmlEncode($this->gedung->CurrentValue);
			$this->gedung->PlaceHolder = ew_RemoveHtml($this->gedung->FldCaption());

			// Edit refer script
			// kode_ruang

			$this->kode_ruang->HrefValue = "";

			// nama_ruang
			$this->nama_ruang->HrefValue = "";

			// jenis_ruang
			$this->jenis_ruang->HrefValue = "";

			// gedung
			$this->gedung->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->kode_ruang->FldIsDetailKey && !is_null($this->kode_ruang->FormValue) && $this->kode_ruang->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->kode_ruang->FldCaption(), $this->kode_ruang->ReqErrMsg));
		}
		if (!$this->nama_ruang->FldIsDetailKey && !is_null($this->nama_ruang->FormValue) && $this->nama_ruang->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->nama_ruang->FldCaption(), $this->nama_ruang->ReqErrMsg));
		}
		if (!$this->jenis_ruang->FldIsDetailKey && !is_null($this->jenis_ruang->FormValue) && $this->jenis_ruang->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jenis_ruang->FldCaption(), $this->jenis_ruang->ReqErrMsg));
		}
		if (!$this->gedung->FldIsDetailKey && !is_null($this->gedung->FormValue) && $this->gedung->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->gedung->FldCaption(), $this->gedung->ReqErrMsg));
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Update record based on key values
	function EditRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// kode_ruang
			// nama_ruang

			$this->nama_ruang->SetDbValueDef($rsnew, $this->nama_ruang->CurrentValue, "", $this->nama_ruang->ReadOnly);

			// jenis_ruang
			$this->jenis_ruang->SetDbValueDef($rsnew, $this->jenis_ruang->CurrentValue, "", $this->jenis_ruang->ReadOnly);

			// gedung
			$this->gedung->SetDbValueDef($rsnew, $this->gedung->CurrentValue, "", $this->gedung->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "ruanglist.php", "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($ruang_edit)) $ruang_edit = new cruang_edit();

// Page init
$ruang_edit->Page_Init();

// Page main
$ruang_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$ruang_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var ruang_edit = new ew_Page("ruang_edit");
ruang_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = ruang_edit.PageID; // For backward compatibility

// Form object
var fruangedit = new ew_Form("fruangedit");

// Validate form
fruangedit.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	this.PostAutoSuggest();
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_kode_ruang");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $ruang->kode_ruang->FldCaption(), $ruang->kode_ruang->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_nama_ruang");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $ruang->nama_ruang->FldCaption(), $ruang->nama_ruang->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jenis_ruang");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $ruang->jenis_ruang->FldCaption(), $ruang->jenis_ruang->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_gedung");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $ruang->gedung->FldCaption(), $ruang->gedung->ReqErrMsg)) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fruangedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fruangedit.ValidateRequired = true;
<?php } else { ?>
fruangedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $ruang_edit->ShowPageHeader(); ?>
<?php
$ruang_edit->ShowMessage();
?>
<form name="fruangedit" id="fruangedit" class="form-horizontal ewForm ewEditForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($ruang_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $ruang_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="ruang">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($ruang->kode_ruang->Visible) { // kode_ruang ?>
	<div id="r_kode_ruang" class="form-group">
		<label id="elh_ruang_kode_ruang" for="x_kode_ruang" class="col-sm-2 control-label ewLabel"><?php echo $ruang->kode_ruang->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $ruang->kode_ruang->CellAttributes() ?>>
<span id="el_ruang_kode_ruang">
<span<?php echo $ruang->kode_ruang->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $ruang->kode_ruang->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_kode_ruang" name="x_kode_ruang" id="x_kode_ruang" value="<?php echo ew_HtmlEncode($ruang->kode_ruang->CurrentValue) ?>">
<?php echo $ruang->kode_ruang->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($ruang->nama_ruang->Visible) { // nama_ruang ?>
	<div id="r_nama_ruang" class="form-group">
		<label id="elh_ruang_nama_ruang" for="x_nama_ruang" class="col-sm-2 control-label ewLabel"><?php echo $ruang->nama_ruang->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $ruang->nama_ruang->CellAttributes() ?>>
<span id="el_ruang_nama_ruang">
<input type="text" data-field="x_nama_ruang" name="x_nama_ruang" id="x_nama_ruang" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($ruang->nama_ruang->PlaceHolder) ?>" value="<?php echo $ruang->nama_ruang->EditValue ?>"<?php echo $ruang->nama_ruang->EditAttributes() ?>>
</span>
<?php echo $ruang->nama_ruang->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($ruang->jenis_ruang->Visible) { // jenis_ruang ?>
	<div id="r_jenis_ruang" class="form-group">
		<label id="elh_ruang_jenis_ruang" for="x_jenis_ruang" class="col-sm-2 control-label ewLabel"><?php echo $ruang->jenis_ruang->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $ruang->jenis_ruang->CellAttributes() ?>>
<span id="el_ruang_jenis_ruang">
<input type="text" data-field="x_jenis_ruang" name="x_jenis_ruang" id="x_jenis_ruang" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($ruang->jenis_ruang->PlaceHolder) ?>" value="<?php echo $ruang->jenis_ruang->EditValue ?>"<?php echo $ruang->jenis_ruang->EditAttributes() ?>>
</span>
<?php echo $ruang->jenis_ruang->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($ruang->gedung->Visible) { // gedung ?>
	<div id="r_gedung" class="form-group">
		<label id="elh_ruang_gedung" for="x_gedung" class="col-sm-2 control-label ewLabel"><?php echo $ruang->gedung->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $ruang->gedung->CellAttributes() ?>>
<span id="el_ruang_gedung">
<input type="text" data-field="x_gedung" name="x_gedung" id="x_gedung" size="30" maxlength="1" placeholder="<?php echo ew_HtmlEncode($ruang->gedung->PlaceHolder) ?>" value="<?php echo $ruang->gedung->EditValue ?>"<?php echo $ruang->gedung->EditAttributes() ?>>
</span>
<?php echo $ruang->gedung->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fruangedit.Init();
</script>
<?php
$ruang_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$ruang_edit->Page_Terminate();
?>
