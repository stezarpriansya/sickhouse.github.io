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

$tim_dokter_edit = NULL; // Initialize page object first

class ctim_dokter_edit extends ctim_dokter {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{E4257960-51C0-4B8A-8F41-FDCC3F20971D}";

	// Table name
	var $TableName = 'tim_dokter';

	// Page object name
	var $PageObjName = 'tim_dokter_edit';

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
			define("EW_PAGE_ID", 'edit', TRUE);

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
		if (!$Security->CanEdit()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ew_GetUrl("tim_dokterlist.php"));
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
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["id_tim"] <> "") {
			$this->id_tim->setQueryStringValue($_GET["id_tim"]);
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
		if ($this->id_tim->CurrentValue == "")
			$this->Page_Terminate("tim_dokterlist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("tim_dokterlist.php"); // No matching record, return to list
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
		if (!$this->id_tim->FldIsDetailKey) {
			$this->id_tim->setFormValue($objForm->GetValue("x_id_tim"));
		}
		if (!$this->dokter1->FldIsDetailKey) {
			$this->dokter1->setFormValue($objForm->GetValue("x_dokter1"));
		}
		if (!$this->peran1->FldIsDetailKey) {
			$this->peran1->setFormValue($objForm->GetValue("x_peran1"));
		}
		if (!$this->dokter2->FldIsDetailKey) {
			$this->dokter2->setFormValue($objForm->GetValue("x_dokter2"));
		}
		if (!$this->peran2->FldIsDetailKey) {
			$this->peran2->setFormValue($objForm->GetValue("x_peran2"));
		}
		if (!$this->dokter3->FldIsDetailKey) {
			$this->dokter3->setFormValue($objForm->GetValue("x_dokter3"));
		}
		if (!$this->peran3->FldIsDetailKey) {
			$this->peran3->setFormValue($objForm->GetValue("x_peran3"));
		}
		if (!$this->dokter4->FldIsDetailKey) {
			$this->dokter4->setFormValue($objForm->GetValue("x_dokter4"));
		}
		if (!$this->peran4->FldIsDetailKey) {
			$this->peran4->setFormValue($objForm->GetValue("x_peran4"));
		}
		if (!$this->dokter5->FldIsDetailKey) {
			$this->dokter5->setFormValue($objForm->GetValue("x_dokter5"));
		}
		if (!$this->peran5->FldIsDetailKey) {
			$this->peran5->setFormValue($objForm->GetValue("x_peran5"));
		}
		if (!$this->dokter6->FldIsDetailKey) {
			$this->dokter6->setFormValue($objForm->GetValue("x_dokter6"));
		}
		if (!$this->peran6->FldIsDetailKey) {
			$this->peran6->setFormValue($objForm->GetValue("x_peran6"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id_tim->CurrentValue = $this->id_tim->FormValue;
		$this->dokter1->CurrentValue = $this->dokter1->FormValue;
		$this->peran1->CurrentValue = $this->peran1->FormValue;
		$this->dokter2->CurrentValue = $this->dokter2->FormValue;
		$this->peran2->CurrentValue = $this->peran2->FormValue;
		$this->dokter3->CurrentValue = $this->dokter3->FormValue;
		$this->peran3->CurrentValue = $this->peran3->FormValue;
		$this->dokter4->CurrentValue = $this->dokter4->FormValue;
		$this->peran4->CurrentValue = $this->peran4->FormValue;
		$this->dokter5->CurrentValue = $this->dokter5->FormValue;
		$this->peran5->CurrentValue = $this->peran5->FormValue;
		$this->dokter6->CurrentValue = $this->dokter6->FormValue;
		$this->peran6->CurrentValue = $this->peran6->FormValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

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

			// Edit refer script
			// id_tim

			$this->id_tim->HrefValue = "";

			// dokter1
			$this->dokter1->HrefValue = "";

			// peran1
			$this->peran1->HrefValue = "";

			// dokter2
			$this->dokter2->HrefValue = "";

			// peran2
			$this->peran2->HrefValue = "";

			// dokter3
			$this->dokter3->HrefValue = "";

			// peran3
			$this->peran3->HrefValue = "";

			// dokter4
			$this->dokter4->HrefValue = "";

			// peran4
			$this->peran4->HrefValue = "";

			// dokter5
			$this->dokter5->HrefValue = "";

			// peran5
			$this->peran5->HrefValue = "";

			// dokter6
			$this->dokter6->HrefValue = "";

			// peran6
			$this->peran6->HrefValue = "";
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
		if (!$this->id_tim->FldIsDetailKey && !is_null($this->id_tim->FormValue) && $this->id_tim->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id_tim->FldCaption(), $this->id_tim->ReqErrMsg));
		}
		if (!$this->dokter1->FldIsDetailKey && !is_null($this->dokter1->FormValue) && $this->dokter1->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->dokter1->FldCaption(), $this->dokter1->ReqErrMsg));
		}
		if (!$this->peran1->FldIsDetailKey && !is_null($this->peran1->FormValue) && $this->peran1->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->peran1->FldCaption(), $this->peran1->ReqErrMsg));
		}
		if (!$this->dokter2->FldIsDetailKey && !is_null($this->dokter2->FormValue) && $this->dokter2->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->dokter2->FldCaption(), $this->dokter2->ReqErrMsg));
		}
		if (!$this->peran2->FldIsDetailKey && !is_null($this->peran2->FormValue) && $this->peran2->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->peran2->FldCaption(), $this->peran2->ReqErrMsg));
		}
		if (!$this->dokter3->FldIsDetailKey && !is_null($this->dokter3->FormValue) && $this->dokter3->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->dokter3->FldCaption(), $this->dokter3->ReqErrMsg));
		}
		if (!$this->peran3->FldIsDetailKey && !is_null($this->peran3->FormValue) && $this->peran3->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->peran3->FldCaption(), $this->peran3->ReqErrMsg));
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

			// id_tim
			// dokter1

			$this->dokter1->SetDbValueDef($rsnew, $this->dokter1->CurrentValue, "", $this->dokter1->ReadOnly);

			// peran1
			$this->peran1->SetDbValueDef($rsnew, $this->peran1->CurrentValue, "", $this->peran1->ReadOnly);

			// dokter2
			$this->dokter2->SetDbValueDef($rsnew, $this->dokter2->CurrentValue, "", $this->dokter2->ReadOnly);

			// peran2
			$this->peran2->SetDbValueDef($rsnew, $this->peran2->CurrentValue, "", $this->peran2->ReadOnly);

			// dokter3
			$this->dokter3->SetDbValueDef($rsnew, $this->dokter3->CurrentValue, "", $this->dokter3->ReadOnly);

			// peran3
			$this->peran3->SetDbValueDef($rsnew, $this->peran3->CurrentValue, "", $this->peran3->ReadOnly);

			// dokter4
			$this->dokter4->SetDbValueDef($rsnew, $this->dokter4->CurrentValue, NULL, $this->dokter4->ReadOnly);

			// peran4
			$this->peran4->SetDbValueDef($rsnew, $this->peran4->CurrentValue, NULL, $this->peran4->ReadOnly);

			// dokter5
			$this->dokter5->SetDbValueDef($rsnew, $this->dokter5->CurrentValue, NULL, $this->dokter5->ReadOnly);

			// peran5
			$this->peran5->SetDbValueDef($rsnew, $this->peran5->CurrentValue, NULL, $this->peran5->ReadOnly);

			// dokter6
			$this->dokter6->SetDbValueDef($rsnew, $this->dokter6->CurrentValue, NULL, $this->dokter6->ReadOnly);

			// peran6
			$this->peran6->SetDbValueDef($rsnew, $this->peran6->CurrentValue, NULL, $this->peran6->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, "tim_dokterlist.php", "", $this->TableVar, TRUE);
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
if (!isset($tim_dokter_edit)) $tim_dokter_edit = new ctim_dokter_edit();

// Page init
$tim_dokter_edit->Page_Init();

// Page main
$tim_dokter_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tim_dokter_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var tim_dokter_edit = new ew_Page("tim_dokter_edit");
tim_dokter_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = tim_dokter_edit.PageID; // For backward compatibility

// Form object
var ftim_dokteredit = new ew_Form("ftim_dokteredit");

// Validate form
ftim_dokteredit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_id_tim");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tim_dokter->id_tim->FldCaption(), $tim_dokter->id_tim->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_dokter1");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tim_dokter->dokter1->FldCaption(), $tim_dokter->dokter1->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_peran1");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tim_dokter->peran1->FldCaption(), $tim_dokter->peran1->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_dokter2");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tim_dokter->dokter2->FldCaption(), $tim_dokter->dokter2->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_peran2");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tim_dokter->peran2->FldCaption(), $tim_dokter->peran2->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_dokter3");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tim_dokter->dokter3->FldCaption(), $tim_dokter->dokter3->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_peran3");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tim_dokter->peran3->FldCaption(), $tim_dokter->peran3->ReqErrMsg)) ?>");

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
ftim_dokteredit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftim_dokteredit.ValidateRequired = true;
<?php } else { ?>
ftim_dokteredit.ValidateRequired = false; 
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
<?php $tim_dokter_edit->ShowPageHeader(); ?>
<?php
$tim_dokter_edit->ShowMessage();
?>
<form name="ftim_dokteredit" id="ftim_dokteredit" class="form-horizontal ewForm ewEditForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($tim_dokter_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $tim_dokter_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="tim_dokter">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($tim_dokter->id_tim->Visible) { // id_tim ?>
	<div id="r_id_tim" class="form-group">
		<label id="elh_tim_dokter_id_tim" for="x_id_tim" class="col-sm-2 control-label ewLabel"><?php echo $tim_dokter->id_tim->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tim_dokter->id_tim->CellAttributes() ?>>
<span id="el_tim_dokter_id_tim">
<span<?php echo $tim_dokter->id_tim->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $tim_dokter->id_tim->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_id_tim" name="x_id_tim" id="x_id_tim" value="<?php echo ew_HtmlEncode($tim_dokter->id_tim->CurrentValue) ?>">
<?php echo $tim_dokter->id_tim->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tim_dokter->dokter1->Visible) { // dokter1 ?>
	<div id="r_dokter1" class="form-group">
		<label id="elh_tim_dokter_dokter1" for="x_dokter1" class="col-sm-2 control-label ewLabel"><?php echo $tim_dokter->dokter1->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tim_dokter->dokter1->CellAttributes() ?>>
<span id="el_tim_dokter_dokter1">
<input type="text" data-field="x_dokter1" name="x_dokter1" id="x_dokter1" size="30" maxlength="6" placeholder="<?php echo ew_HtmlEncode($tim_dokter->dokter1->PlaceHolder) ?>" value="<?php echo $tim_dokter->dokter1->EditValue ?>"<?php echo $tim_dokter->dokter1->EditAttributes() ?>>
</span>
<?php echo $tim_dokter->dokter1->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tim_dokter->peran1->Visible) { // peran1 ?>
	<div id="r_peran1" class="form-group">
		<label id="elh_tim_dokter_peran1" for="x_peran1" class="col-sm-2 control-label ewLabel"><?php echo $tim_dokter->peran1->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tim_dokter->peran1->CellAttributes() ?>>
<span id="el_tim_dokter_peran1">
<textarea data-field="x_peran1" name="x_peran1" id="x_peran1" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($tim_dokter->peran1->PlaceHolder) ?>"<?php echo $tim_dokter->peran1->EditAttributes() ?>><?php echo $tim_dokter->peran1->EditValue ?></textarea>
</span>
<?php echo $tim_dokter->peran1->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tim_dokter->dokter2->Visible) { // dokter2 ?>
	<div id="r_dokter2" class="form-group">
		<label id="elh_tim_dokter_dokter2" for="x_dokter2" class="col-sm-2 control-label ewLabel"><?php echo $tim_dokter->dokter2->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tim_dokter->dokter2->CellAttributes() ?>>
<span id="el_tim_dokter_dokter2">
<input type="text" data-field="x_dokter2" name="x_dokter2" id="x_dokter2" size="30" maxlength="6" placeholder="<?php echo ew_HtmlEncode($tim_dokter->dokter2->PlaceHolder) ?>" value="<?php echo $tim_dokter->dokter2->EditValue ?>"<?php echo $tim_dokter->dokter2->EditAttributes() ?>>
</span>
<?php echo $tim_dokter->dokter2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tim_dokter->peran2->Visible) { // peran2 ?>
	<div id="r_peran2" class="form-group">
		<label id="elh_tim_dokter_peran2" for="x_peran2" class="col-sm-2 control-label ewLabel"><?php echo $tim_dokter->peran2->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tim_dokter->peran2->CellAttributes() ?>>
<span id="el_tim_dokter_peran2">
<textarea data-field="x_peran2" name="x_peran2" id="x_peran2" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($tim_dokter->peran2->PlaceHolder) ?>"<?php echo $tim_dokter->peran2->EditAttributes() ?>><?php echo $tim_dokter->peran2->EditValue ?></textarea>
</span>
<?php echo $tim_dokter->peran2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tim_dokter->dokter3->Visible) { // dokter3 ?>
	<div id="r_dokter3" class="form-group">
		<label id="elh_tim_dokter_dokter3" for="x_dokter3" class="col-sm-2 control-label ewLabel"><?php echo $tim_dokter->dokter3->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tim_dokter->dokter3->CellAttributes() ?>>
<span id="el_tim_dokter_dokter3">
<input type="text" data-field="x_dokter3" name="x_dokter3" id="x_dokter3" size="30" maxlength="6" placeholder="<?php echo ew_HtmlEncode($tim_dokter->dokter3->PlaceHolder) ?>" value="<?php echo $tim_dokter->dokter3->EditValue ?>"<?php echo $tim_dokter->dokter3->EditAttributes() ?>>
</span>
<?php echo $tim_dokter->dokter3->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tim_dokter->peran3->Visible) { // peran3 ?>
	<div id="r_peran3" class="form-group">
		<label id="elh_tim_dokter_peran3" for="x_peran3" class="col-sm-2 control-label ewLabel"><?php echo $tim_dokter->peran3->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tim_dokter->peran3->CellAttributes() ?>>
<span id="el_tim_dokter_peran3">
<textarea data-field="x_peran3" name="x_peran3" id="x_peran3" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($tim_dokter->peran3->PlaceHolder) ?>"<?php echo $tim_dokter->peran3->EditAttributes() ?>><?php echo $tim_dokter->peran3->EditValue ?></textarea>
</span>
<?php echo $tim_dokter->peran3->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tim_dokter->dokter4->Visible) { // dokter4 ?>
	<div id="r_dokter4" class="form-group">
		<label id="elh_tim_dokter_dokter4" for="x_dokter4" class="col-sm-2 control-label ewLabel"><?php echo $tim_dokter->dokter4->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $tim_dokter->dokter4->CellAttributes() ?>>
<span id="el_tim_dokter_dokter4">
<input type="text" data-field="x_dokter4" name="x_dokter4" id="x_dokter4" size="30" maxlength="6" placeholder="<?php echo ew_HtmlEncode($tim_dokter->dokter4->PlaceHolder) ?>" value="<?php echo $tim_dokter->dokter4->EditValue ?>"<?php echo $tim_dokter->dokter4->EditAttributes() ?>>
</span>
<?php echo $tim_dokter->dokter4->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tim_dokter->peran4->Visible) { // peran4 ?>
	<div id="r_peran4" class="form-group">
		<label id="elh_tim_dokter_peran4" for="x_peran4" class="col-sm-2 control-label ewLabel"><?php echo $tim_dokter->peran4->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $tim_dokter->peran4->CellAttributes() ?>>
<span id="el_tim_dokter_peran4">
<textarea data-field="x_peran4" name="x_peran4" id="x_peran4" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($tim_dokter->peran4->PlaceHolder) ?>"<?php echo $tim_dokter->peran4->EditAttributes() ?>><?php echo $tim_dokter->peran4->EditValue ?></textarea>
</span>
<?php echo $tim_dokter->peran4->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tim_dokter->dokter5->Visible) { // dokter5 ?>
	<div id="r_dokter5" class="form-group">
		<label id="elh_tim_dokter_dokter5" for="x_dokter5" class="col-sm-2 control-label ewLabel"><?php echo $tim_dokter->dokter5->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $tim_dokter->dokter5->CellAttributes() ?>>
<span id="el_tim_dokter_dokter5">
<input type="text" data-field="x_dokter5" name="x_dokter5" id="x_dokter5" size="30" maxlength="6" placeholder="<?php echo ew_HtmlEncode($tim_dokter->dokter5->PlaceHolder) ?>" value="<?php echo $tim_dokter->dokter5->EditValue ?>"<?php echo $tim_dokter->dokter5->EditAttributes() ?>>
</span>
<?php echo $tim_dokter->dokter5->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tim_dokter->peran5->Visible) { // peran5 ?>
	<div id="r_peran5" class="form-group">
		<label id="elh_tim_dokter_peran5" for="x_peran5" class="col-sm-2 control-label ewLabel"><?php echo $tim_dokter->peran5->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $tim_dokter->peran5->CellAttributes() ?>>
<span id="el_tim_dokter_peran5">
<textarea data-field="x_peran5" name="x_peran5" id="x_peran5" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($tim_dokter->peran5->PlaceHolder) ?>"<?php echo $tim_dokter->peran5->EditAttributes() ?>><?php echo $tim_dokter->peran5->EditValue ?></textarea>
</span>
<?php echo $tim_dokter->peran5->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tim_dokter->dokter6->Visible) { // dokter6 ?>
	<div id="r_dokter6" class="form-group">
		<label id="elh_tim_dokter_dokter6" for="x_dokter6" class="col-sm-2 control-label ewLabel"><?php echo $tim_dokter->dokter6->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $tim_dokter->dokter6->CellAttributes() ?>>
<span id="el_tim_dokter_dokter6">
<input type="text" data-field="x_dokter6" name="x_dokter6" id="x_dokter6" size="30" maxlength="6" placeholder="<?php echo ew_HtmlEncode($tim_dokter->dokter6->PlaceHolder) ?>" value="<?php echo $tim_dokter->dokter6->EditValue ?>"<?php echo $tim_dokter->dokter6->EditAttributes() ?>>
</span>
<?php echo $tim_dokter->dokter6->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tim_dokter->peran6->Visible) { // peran6 ?>
	<div id="r_peran6" class="form-group">
		<label id="elh_tim_dokter_peran6" for="x_peran6" class="col-sm-2 control-label ewLabel"><?php echo $tim_dokter->peran6->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $tim_dokter->peran6->CellAttributes() ?>>
<span id="el_tim_dokter_peran6">
<textarea data-field="x_peran6" name="x_peran6" id="x_peran6" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($tim_dokter->peran6->PlaceHolder) ?>"<?php echo $tim_dokter->peran6->EditAttributes() ?>><?php echo $tim_dokter->peran6->EditValue ?></textarea>
</span>
<?php echo $tim_dokter->peran6->CustomMsg ?></div></div>
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
ftim_dokteredit.Init();
</script>
<?php
$tim_dokter_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tim_dokter_edit->Page_Terminate();
?>
