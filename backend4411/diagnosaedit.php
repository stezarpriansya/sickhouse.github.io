<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "diagnosainfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$diagnosa_edit = NULL; // Initialize page object first

class cdiagnosa_edit extends cdiagnosa {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{E4257960-51C0-4B8A-8F41-FDCC3F20971D}";

	// Table name
	var $TableName = 'diagnosa';

	// Page object name
	var $PageObjName = 'diagnosa_edit';

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

		// Table object (diagnosa)
		if (!isset($GLOBALS["diagnosa"]) || get_class($GLOBALS["diagnosa"]) == "cdiagnosa") {
			$GLOBALS["diagnosa"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["diagnosa"];
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
			define("EW_TABLE_NAME", 'diagnosa', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("diagnosalist.php"));
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
		global $EW_EXPORT, $diagnosa;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($diagnosa);
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
		if (@$_GET["kode_diagnosa"] <> "") {
			$this->kode_diagnosa->setQueryStringValue($_GET["kode_diagnosa"]);
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
		if ($this->kode_diagnosa->CurrentValue == "")
			$this->Page_Terminate("diagnosalist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("diagnosalist.php"); // No matching record, return to list
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
		if (!$this->kode_diagnosa->FldIsDetailKey) {
			$this->kode_diagnosa->setFormValue($objForm->GetValue("x_kode_diagnosa"));
		}
		if (!$this->kode_pasien->FldIsDetailKey) {
			$this->kode_pasien->setFormValue($objForm->GetValue("x_kode_pasien"));
		}
		if (!$this->kode_dokter->FldIsDetailKey) {
			$this->kode_dokter->setFormValue($objForm->GetValue("x_kode_dokter"));
		}
		if (!$this->diagnosa_dokter->FldIsDetailKey) {
			$this->diagnosa_dokter->setFormValue($objForm->GetValue("x_diagnosa_dokter"));
		}
		if (!$this->penanganan->FldIsDetailKey) {
			$this->penanganan->setFormValue($objForm->GetValue("x_penanganan"));
		}
		if (!$this->tanggal->FldIsDetailKey) {
			$this->tanggal->setFormValue($objForm->GetValue("x_tanggal"));
			$this->tanggal->CurrentValue = ew_UnFormatDateTime($this->tanggal->CurrentValue, 5);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->kode_diagnosa->CurrentValue = $this->kode_diagnosa->FormValue;
		$this->kode_pasien->CurrentValue = $this->kode_pasien->FormValue;
		$this->kode_dokter->CurrentValue = $this->kode_dokter->FormValue;
		$this->diagnosa_dokter->CurrentValue = $this->diagnosa_dokter->FormValue;
		$this->penanganan->CurrentValue = $this->penanganan->FormValue;
		$this->tanggal->CurrentValue = $this->tanggal->FormValue;
		$this->tanggal->CurrentValue = ew_UnFormatDateTime($this->tanggal->CurrentValue, 5);
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
		$this->kode_diagnosa->setDbValue($rs->fields('kode_diagnosa'));
		$this->kode_pasien->setDbValue($rs->fields('kode_pasien'));
		$this->kode_dokter->setDbValue($rs->fields('kode_dokter'));
		$this->diagnosa_dokter->setDbValue($rs->fields('diagnosa_dokter'));
		$this->penanganan->setDbValue($rs->fields('penanganan'));
		$this->tanggal->setDbValue($rs->fields('tanggal'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->kode_diagnosa->DbValue = $row['kode_diagnosa'];
		$this->kode_pasien->DbValue = $row['kode_pasien'];
		$this->kode_dokter->DbValue = $row['kode_dokter'];
		$this->diagnosa_dokter->DbValue = $row['diagnosa_dokter'];
		$this->penanganan->DbValue = $row['penanganan'];
		$this->tanggal->DbValue = $row['tanggal'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// kode_diagnosa
		// kode_pasien
		// kode_dokter
		// diagnosa_dokter
		// penanganan
		// tanggal

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// kode_diagnosa
			$this->kode_diagnosa->ViewValue = $this->kode_diagnosa->CurrentValue;
			$this->kode_diagnosa->ViewCustomAttributes = "";

			// kode_pasien
			$this->kode_pasien->ViewValue = $this->kode_pasien->CurrentValue;
			$this->kode_pasien->ViewCustomAttributes = "";

			// kode_dokter
			$this->kode_dokter->ViewValue = $this->kode_dokter->CurrentValue;
			$this->kode_dokter->ViewCustomAttributes = "";

			// diagnosa_dokter
			$this->diagnosa_dokter->ViewValue = $this->diagnosa_dokter->CurrentValue;
			$this->diagnosa_dokter->ViewCustomAttributes = "";

			// penanganan
			$this->penanganan->ViewValue = $this->penanganan->CurrentValue;
			$this->penanganan->ViewCustomAttributes = "";

			// tanggal
			$this->tanggal->ViewValue = $this->tanggal->CurrentValue;
			$this->tanggal->ViewValue = ew_FormatDateTime($this->tanggal->ViewValue, 5);
			$this->tanggal->ViewCustomAttributes = "";

			// kode_diagnosa
			$this->kode_diagnosa->LinkCustomAttributes = "";
			$this->kode_diagnosa->HrefValue = "";
			$this->kode_diagnosa->TooltipValue = "";

			// kode_pasien
			$this->kode_pasien->LinkCustomAttributes = "";
			$this->kode_pasien->HrefValue = "";
			$this->kode_pasien->TooltipValue = "";

			// kode_dokter
			$this->kode_dokter->LinkCustomAttributes = "";
			$this->kode_dokter->HrefValue = "";
			$this->kode_dokter->TooltipValue = "";

			// diagnosa_dokter
			$this->diagnosa_dokter->LinkCustomAttributes = "";
			$this->diagnosa_dokter->HrefValue = "";
			$this->diagnosa_dokter->TooltipValue = "";

			// penanganan
			$this->penanganan->LinkCustomAttributes = "";
			$this->penanganan->HrefValue = "";
			$this->penanganan->TooltipValue = "";

			// tanggal
			$this->tanggal->LinkCustomAttributes = "";
			$this->tanggal->HrefValue = "";
			$this->tanggal->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// kode_diagnosa
			$this->kode_diagnosa->EditAttrs["class"] = "form-control";
			$this->kode_diagnosa->EditCustomAttributes = "";
			$this->kode_diagnosa->EditValue = $this->kode_diagnosa->CurrentValue;
			$this->kode_diagnosa->ViewCustomAttributes = "";

			// kode_pasien
			$this->kode_pasien->EditAttrs["class"] = "form-control";
			$this->kode_pasien->EditCustomAttributes = "";
			$this->kode_pasien->EditValue = ew_HtmlEncode($this->kode_pasien->CurrentValue);
			$this->kode_pasien->PlaceHolder = ew_RemoveHtml($this->kode_pasien->FldCaption());

			// kode_dokter
			$this->kode_dokter->EditAttrs["class"] = "form-control";
			$this->kode_dokter->EditCustomAttributes = "";
			$this->kode_dokter->EditValue = ew_HtmlEncode($this->kode_dokter->CurrentValue);
			$this->kode_dokter->PlaceHolder = ew_RemoveHtml($this->kode_dokter->FldCaption());

			// diagnosa_dokter
			$this->diagnosa_dokter->EditAttrs["class"] = "form-control";
			$this->diagnosa_dokter->EditCustomAttributes = "";
			$this->diagnosa_dokter->EditValue = ew_HtmlEncode($this->diagnosa_dokter->CurrentValue);
			$this->diagnosa_dokter->PlaceHolder = ew_RemoveHtml($this->diagnosa_dokter->FldCaption());

			// penanganan
			$this->penanganan->EditAttrs["class"] = "form-control";
			$this->penanganan->EditCustomAttributes = "";
			$this->penanganan->EditValue = ew_HtmlEncode($this->penanganan->CurrentValue);
			$this->penanganan->PlaceHolder = ew_RemoveHtml($this->penanganan->FldCaption());

			// tanggal
			$this->tanggal->EditAttrs["class"] = "form-control";
			$this->tanggal->EditCustomAttributes = "";
			$this->tanggal->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->tanggal->CurrentValue, 5));
			$this->tanggal->PlaceHolder = ew_RemoveHtml($this->tanggal->FldCaption());

			// Edit refer script
			// kode_diagnosa

			$this->kode_diagnosa->HrefValue = "";

			// kode_pasien
			$this->kode_pasien->HrefValue = "";

			// kode_dokter
			$this->kode_dokter->HrefValue = "";

			// diagnosa_dokter
			$this->diagnosa_dokter->HrefValue = "";

			// penanganan
			$this->penanganan->HrefValue = "";

			// tanggal
			$this->tanggal->HrefValue = "";
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
		if (!$this->kode_diagnosa->FldIsDetailKey && !is_null($this->kode_diagnosa->FormValue) && $this->kode_diagnosa->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->kode_diagnosa->FldCaption(), $this->kode_diagnosa->ReqErrMsg));
		}
		if (!$this->kode_pasien->FldIsDetailKey && !is_null($this->kode_pasien->FormValue) && $this->kode_pasien->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->kode_pasien->FldCaption(), $this->kode_pasien->ReqErrMsg));
		}
		if (!$this->kode_dokter->FldIsDetailKey && !is_null($this->kode_dokter->FormValue) && $this->kode_dokter->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->kode_dokter->FldCaption(), $this->kode_dokter->ReqErrMsg));
		}
		if (!$this->diagnosa_dokter->FldIsDetailKey && !is_null($this->diagnosa_dokter->FormValue) && $this->diagnosa_dokter->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->diagnosa_dokter->FldCaption(), $this->diagnosa_dokter->ReqErrMsg));
		}
		if (!ew_CheckDate($this->tanggal->FormValue)) {
			ew_AddMessage($gsFormError, $this->tanggal->FldErrMsg());
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

			// kode_diagnosa
			// kode_pasien

			$this->kode_pasien->SetDbValueDef($rsnew, $this->kode_pasien->CurrentValue, "", $this->kode_pasien->ReadOnly);

			// kode_dokter
			$this->kode_dokter->SetDbValueDef($rsnew, $this->kode_dokter->CurrentValue, "", $this->kode_dokter->ReadOnly);

			// diagnosa_dokter
			$this->diagnosa_dokter->SetDbValueDef($rsnew, $this->diagnosa_dokter->CurrentValue, "", $this->diagnosa_dokter->ReadOnly);

			// penanganan
			$this->penanganan->SetDbValueDef($rsnew, $this->penanganan->CurrentValue, NULL, $this->penanganan->ReadOnly);

			// tanggal
			$this->tanggal->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tanggal->CurrentValue, 5), NULL, $this->tanggal->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, "diagnosalist.php", "", $this->TableVar, TRUE);
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
if (!isset($diagnosa_edit)) $diagnosa_edit = new cdiagnosa_edit();

// Page init
$diagnosa_edit->Page_Init();

// Page main
$diagnosa_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$diagnosa_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var diagnosa_edit = new ew_Page("diagnosa_edit");
diagnosa_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = diagnosa_edit.PageID; // For backward compatibility

// Form object
var fdiagnosaedit = new ew_Form("fdiagnosaedit");

// Validate form
fdiagnosaedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_kode_diagnosa");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $diagnosa->kode_diagnosa->FldCaption(), $diagnosa->kode_diagnosa->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_kode_pasien");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $diagnosa->kode_pasien->FldCaption(), $diagnosa->kode_pasien->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_kode_dokter");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $diagnosa->kode_dokter->FldCaption(), $diagnosa->kode_dokter->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_diagnosa_dokter");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $diagnosa->diagnosa_dokter->FldCaption(), $diagnosa->diagnosa_dokter->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tanggal");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($diagnosa->tanggal->FldErrMsg()) ?>");

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
fdiagnosaedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdiagnosaedit.ValidateRequired = true;
<?php } else { ?>
fdiagnosaedit.ValidateRequired = false; 
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
<?php $diagnosa_edit->ShowPageHeader(); ?>
<?php
$diagnosa_edit->ShowMessage();
?>
<form name="fdiagnosaedit" id="fdiagnosaedit" class="form-horizontal ewForm ewEditForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($diagnosa_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $diagnosa_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="diagnosa">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($diagnosa->kode_diagnosa->Visible) { // kode_diagnosa ?>
	<div id="r_kode_diagnosa" class="form-group">
		<label id="elh_diagnosa_kode_diagnosa" for="x_kode_diagnosa" class="col-sm-2 control-label ewLabel"><?php echo $diagnosa->kode_diagnosa->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $diagnosa->kode_diagnosa->CellAttributes() ?>>
<span id="el_diagnosa_kode_diagnosa">
<span<?php echo $diagnosa->kode_diagnosa->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $diagnosa->kode_diagnosa->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_kode_diagnosa" name="x_kode_diagnosa" id="x_kode_diagnosa" value="<?php echo ew_HtmlEncode($diagnosa->kode_diagnosa->CurrentValue) ?>">
<?php echo $diagnosa->kode_diagnosa->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($diagnosa->kode_pasien->Visible) { // kode_pasien ?>
	<div id="r_kode_pasien" class="form-group">
		<label id="elh_diagnosa_kode_pasien" for="x_kode_pasien" class="col-sm-2 control-label ewLabel"><?php echo $diagnosa->kode_pasien->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $diagnosa->kode_pasien->CellAttributes() ?>>
<span id="el_diagnosa_kode_pasien">
<input type="text" data-field="x_kode_pasien" name="x_kode_pasien" id="x_kode_pasien" size="30" maxlength="6" placeholder="<?php echo ew_HtmlEncode($diagnosa->kode_pasien->PlaceHolder) ?>" value="<?php echo $diagnosa->kode_pasien->EditValue ?>"<?php echo $diagnosa->kode_pasien->EditAttributes() ?>>
</span>
<?php echo $diagnosa->kode_pasien->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($diagnosa->kode_dokter->Visible) { // kode_dokter ?>
	<div id="r_kode_dokter" class="form-group">
		<label id="elh_diagnosa_kode_dokter" for="x_kode_dokter" class="col-sm-2 control-label ewLabel"><?php echo $diagnosa->kode_dokter->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $diagnosa->kode_dokter->CellAttributes() ?>>
<span id="el_diagnosa_kode_dokter">
<input type="text" data-field="x_kode_dokter" name="x_kode_dokter" id="x_kode_dokter" size="30" maxlength="6" placeholder="<?php echo ew_HtmlEncode($diagnosa->kode_dokter->PlaceHolder) ?>" value="<?php echo $diagnosa->kode_dokter->EditValue ?>"<?php echo $diagnosa->kode_dokter->EditAttributes() ?>>
</span>
<?php echo $diagnosa->kode_dokter->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($diagnosa->diagnosa_dokter->Visible) { // diagnosa_dokter ?>
	<div id="r_diagnosa_dokter" class="form-group">
		<label id="elh_diagnosa_diagnosa_dokter" for="x_diagnosa_dokter" class="col-sm-2 control-label ewLabel"><?php echo $diagnosa->diagnosa_dokter->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $diagnosa->diagnosa_dokter->CellAttributes() ?>>
<span id="el_diagnosa_diagnosa_dokter">
<textarea data-field="x_diagnosa_dokter" name="x_diagnosa_dokter" id="x_diagnosa_dokter" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($diagnosa->diagnosa_dokter->PlaceHolder) ?>"<?php echo $diagnosa->diagnosa_dokter->EditAttributes() ?>><?php echo $diagnosa->diagnosa_dokter->EditValue ?></textarea>
</span>
<?php echo $diagnosa->diagnosa_dokter->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($diagnosa->penanganan->Visible) { // penanganan ?>
	<div id="r_penanganan" class="form-group">
		<label id="elh_diagnosa_penanganan" for="x_penanganan" class="col-sm-2 control-label ewLabel"><?php echo $diagnosa->penanganan->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $diagnosa->penanganan->CellAttributes() ?>>
<span id="el_diagnosa_penanganan">
<textarea data-field="x_penanganan" name="x_penanganan" id="x_penanganan" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($diagnosa->penanganan->PlaceHolder) ?>"<?php echo $diagnosa->penanganan->EditAttributes() ?>><?php echo $diagnosa->penanganan->EditValue ?></textarea>
</span>
<?php echo $diagnosa->penanganan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($diagnosa->tanggal->Visible) { // tanggal ?>
	<div id="r_tanggal" class="form-group">
		<label id="elh_diagnosa_tanggal" for="x_tanggal" class="col-sm-2 control-label ewLabel"><?php echo $diagnosa->tanggal->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $diagnosa->tanggal->CellAttributes() ?>>
<span id="el_diagnosa_tanggal">
<input type="text" data-field="x_tanggal" name="x_tanggal" id="x_tanggal" placeholder="<?php echo ew_HtmlEncode($diagnosa->tanggal->PlaceHolder) ?>" value="<?php echo $diagnosa->tanggal->EditValue ?>"<?php echo $diagnosa->tanggal->EditAttributes() ?>>
</span>
<?php echo $diagnosa->tanggal->CustomMsg ?></div></div>
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
fdiagnosaedit.Init();
</script>
<?php
$diagnosa_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$diagnosa_edit->Page_Terminate();
?>
