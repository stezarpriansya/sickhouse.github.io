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

$operasi_add = NULL; // Initialize page object first

class coperasi_add extends coperasi {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{E4257960-51C0-4B8A-8F41-FDCC3F20971D}";

	// Table name
	var $TableName = 'operasi';

	// Page object name
	var $PageObjName = 'operasi_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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
		if (!$Security->CanAdd()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ew_GetUrl("operasilist.php"));
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
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["kode_operasi"] != "") {
				$this->kode_operasi->setQueryStringValue($_GET["kode_operasi"]);
				$this->setKey("kode_operasi", $this->kode_operasi->CurrentValue); // Set up key
			} else {
				$this->setKey("kode_operasi", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
				$this->LoadDefaultValues(); // Load default values
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("operasilist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "operasiview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD;  // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->kode_operasi->CurrentValue = NULL;
		$this->kode_operasi->OldValue = $this->kode_operasi->CurrentValue;
		$this->kode_diagnosa->CurrentValue = NULL;
		$this->kode_diagnosa->OldValue = $this->kode_diagnosa->CurrentValue;
		$this->kode_pasien->CurrentValue = NULL;
		$this->kode_pasien->OldValue = $this->kode_pasien->CurrentValue;
		$this->id_tim->CurrentValue = NULL;
		$this->id_tim->OldValue = $this->id_tim->CurrentValue;
		$this->kode_ruang->CurrentValue = NULL;
		$this->kode_ruang->OldValue = $this->kode_ruang->CurrentValue;
		$this->jam_mulai->CurrentValue = NULL;
		$this->jam_mulai->OldValue = $this->jam_mulai->CurrentValue;
		$this->jam_berakhir->CurrentValue = NULL;
		$this->jam_berakhir->OldValue = $this->jam_berakhir->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->kode_operasi->FldIsDetailKey) {
			$this->kode_operasi->setFormValue($objForm->GetValue("x_kode_operasi"));
		}
		if (!$this->kode_diagnosa->FldIsDetailKey) {
			$this->kode_diagnosa->setFormValue($objForm->GetValue("x_kode_diagnosa"));
		}
		if (!$this->kode_pasien->FldIsDetailKey) {
			$this->kode_pasien->setFormValue($objForm->GetValue("x_kode_pasien"));
		}
		if (!$this->id_tim->FldIsDetailKey) {
			$this->id_tim->setFormValue($objForm->GetValue("x_id_tim"));
		}
		if (!$this->kode_ruang->FldIsDetailKey) {
			$this->kode_ruang->setFormValue($objForm->GetValue("x_kode_ruang"));
		}
		if (!$this->jam_mulai->FldIsDetailKey) {
			$this->jam_mulai->setFormValue($objForm->GetValue("x_jam_mulai"));
			$this->jam_mulai->CurrentValue = ew_UnFormatDateTime($this->jam_mulai->CurrentValue, 5);
		}
		if (!$this->jam_berakhir->FldIsDetailKey) {
			$this->jam_berakhir->setFormValue($objForm->GetValue("x_jam_berakhir"));
			$this->jam_berakhir->CurrentValue = ew_UnFormatDateTime($this->jam_berakhir->CurrentValue, 5);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->kode_operasi->CurrentValue = $this->kode_operasi->FormValue;
		$this->kode_diagnosa->CurrentValue = $this->kode_diagnosa->FormValue;
		$this->kode_pasien->CurrentValue = $this->kode_pasien->FormValue;
		$this->id_tim->CurrentValue = $this->id_tim->FormValue;
		$this->kode_ruang->CurrentValue = $this->kode_ruang->FormValue;
		$this->jam_mulai->CurrentValue = $this->jam_mulai->FormValue;
		$this->jam_mulai->CurrentValue = ew_UnFormatDateTime($this->jam_mulai->CurrentValue, 5);
		$this->jam_berakhir->CurrentValue = $this->jam_berakhir->FormValue;
		$this->jam_berakhir->CurrentValue = ew_UnFormatDateTime($this->jam_berakhir->CurrentValue, 5);
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("kode_operasi")) <> "")
			$this->kode_operasi->CurrentValue = $this->getKey("kode_operasi"); // kode_operasi
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$this->OldRecordset = ew_LoadRecordset($sSql);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// kode_operasi
			$this->kode_operasi->EditAttrs["class"] = "form-control";
			$this->kode_operasi->EditCustomAttributes = "";
			$this->kode_operasi->EditValue = ew_HtmlEncode($this->kode_operasi->CurrentValue);
			$this->kode_operasi->PlaceHolder = ew_RemoveHtml($this->kode_operasi->FldCaption());

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

			// Edit refer script
			// kode_operasi

			$this->kode_operasi->HrefValue = "";

			// kode_diagnosa
			$this->kode_diagnosa->HrefValue = "";

			// kode_pasien
			$this->kode_pasien->HrefValue = "";

			// id_tim
			$this->id_tim->HrefValue = "";

			// kode_ruang
			$this->kode_ruang->HrefValue = "";

			// jam_mulai
			$this->jam_mulai->HrefValue = "";

			// jam_berakhir
			$this->jam_berakhir->HrefValue = "";
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
		if (!$this->kode_operasi->FldIsDetailKey && !is_null($this->kode_operasi->FormValue) && $this->kode_operasi->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->kode_operasi->FldCaption(), $this->kode_operasi->ReqErrMsg));
		}
		if (!$this->kode_diagnosa->FldIsDetailKey && !is_null($this->kode_diagnosa->FormValue) && $this->kode_diagnosa->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->kode_diagnosa->FldCaption(), $this->kode_diagnosa->ReqErrMsg));
		}
		if (!$this->kode_pasien->FldIsDetailKey && !is_null($this->kode_pasien->FormValue) && $this->kode_pasien->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->kode_pasien->FldCaption(), $this->kode_pasien->ReqErrMsg));
		}
		if (!$this->id_tim->FldIsDetailKey && !is_null($this->id_tim->FormValue) && $this->id_tim->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id_tim->FldCaption(), $this->id_tim->ReqErrMsg));
		}
		if (!$this->kode_ruang->FldIsDetailKey && !is_null($this->kode_ruang->FormValue) && $this->kode_ruang->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->kode_ruang->FldCaption(), $this->kode_ruang->ReqErrMsg));
		}
		if (!$this->jam_mulai->FldIsDetailKey && !is_null($this->jam_mulai->FormValue) && $this->jam_mulai->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jam_mulai->FldCaption(), $this->jam_mulai->ReqErrMsg));
		}
		if (!ew_CheckDate($this->jam_mulai->FormValue)) {
			ew_AddMessage($gsFormError, $this->jam_mulai->FldErrMsg());
		}
		if (!ew_CheckDate($this->jam_berakhir->FormValue)) {
			ew_AddMessage($gsFormError, $this->jam_berakhir->FldErrMsg());
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

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// kode_operasi
		$this->kode_operasi->SetDbValueDef($rsnew, $this->kode_operasi->CurrentValue, "", FALSE);

		// kode_diagnosa
		$this->kode_diagnosa->SetDbValueDef($rsnew, $this->kode_diagnosa->CurrentValue, "", FALSE);

		// kode_pasien
		$this->kode_pasien->SetDbValueDef($rsnew, $this->kode_pasien->CurrentValue, "", FALSE);

		// id_tim
		$this->id_tim->SetDbValueDef($rsnew, $this->id_tim->CurrentValue, "", FALSE);

		// kode_ruang
		$this->kode_ruang->SetDbValueDef($rsnew, $this->kode_ruang->CurrentValue, "", FALSE);

		// jam_mulai
		$this->jam_mulai->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->jam_mulai->CurrentValue, 5), ew_CurrentDate(), FALSE);

		// jam_berakhir
		$this->jam_berakhir->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->jam_berakhir->CurrentValue, 5), NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['kode_operasi']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check for duplicate key
		if ($bInsertRow && $this->ValidateKey) {
			$sFilter = $this->KeyFilter();
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sKeyErrMsg = str_replace("%f", $sFilter, $Language->Phrase("DupKey"));
				$this->setFailureMessage($sKeyErrMsg);
				$rsChk->Close();
				$bInsertRow = FALSE;
			}
		}
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}

		// Get insert id if necessary
		if ($AddRow) {
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "operasilist.php", "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
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
if (!isset($operasi_add)) $operasi_add = new coperasi_add();

// Page init
$operasi_add->Page_Init();

// Page main
$operasi_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$operasi_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var operasi_add = new ew_Page("operasi_add");
operasi_add.PageID = "add"; // Page ID
var EW_PAGE_ID = operasi_add.PageID; // For backward compatibility

// Form object
var foperasiadd = new ew_Form("foperasiadd");

// Validate form
foperasiadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_kode_operasi");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $operasi->kode_operasi->FldCaption(), $operasi->kode_operasi->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_kode_diagnosa");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $operasi->kode_diagnosa->FldCaption(), $operasi->kode_diagnosa->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_kode_pasien");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $operasi->kode_pasien->FldCaption(), $operasi->kode_pasien->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_id_tim");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $operasi->id_tim->FldCaption(), $operasi->id_tim->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_kode_ruang");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $operasi->kode_ruang->FldCaption(), $operasi->kode_ruang->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jam_mulai");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $operasi->jam_mulai->FldCaption(), $operasi->jam_mulai->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jam_mulai");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($operasi->jam_mulai->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jam_berakhir");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($operasi->jam_berakhir->FldErrMsg()) ?>");

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
foperasiadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
foperasiadd.ValidateRequired = true;
<?php } else { ?>
foperasiadd.ValidateRequired = false; 
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
<?php $operasi_add->ShowPageHeader(); ?>
<?php
$operasi_add->ShowMessage();
?>
<form name="foperasiadd" id="foperasiadd" class="form-horizontal ewForm ewAddForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($operasi_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $operasi_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="operasi">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($operasi->kode_operasi->Visible) { // kode_operasi ?>
	<div id="r_kode_operasi" class="form-group">
		<label id="elh_operasi_kode_operasi" for="x_kode_operasi" class="col-sm-2 control-label ewLabel"><?php echo $operasi->kode_operasi->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $operasi->kode_operasi->CellAttributes() ?>>
<span id="el_operasi_kode_operasi">
<input type="text" data-field="x_kode_operasi" name="x_kode_operasi" id="x_kode_operasi" size="30" maxlength="6" placeholder="<?php echo ew_HtmlEncode($operasi->kode_operasi->PlaceHolder) ?>" value="<?php echo $operasi->kode_operasi->EditValue ?>"<?php echo $operasi->kode_operasi->EditAttributes() ?>>
</span>
<?php echo $operasi->kode_operasi->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($operasi->kode_diagnosa->Visible) { // kode_diagnosa ?>
	<div id="r_kode_diagnosa" class="form-group">
		<label id="elh_operasi_kode_diagnosa" for="x_kode_diagnosa" class="col-sm-2 control-label ewLabel"><?php echo $operasi->kode_diagnosa->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $operasi->kode_diagnosa->CellAttributes() ?>>
<span id="el_operasi_kode_diagnosa">
<input type="text" data-field="x_kode_diagnosa" name="x_kode_diagnosa" id="x_kode_diagnosa" size="30" maxlength="6" placeholder="<?php echo ew_HtmlEncode($operasi->kode_diagnosa->PlaceHolder) ?>" value="<?php echo $operasi->kode_diagnosa->EditValue ?>"<?php echo $operasi->kode_diagnosa->EditAttributes() ?>>
</span>
<?php echo $operasi->kode_diagnosa->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($operasi->kode_pasien->Visible) { // kode_pasien ?>
	<div id="r_kode_pasien" class="form-group">
		<label id="elh_operasi_kode_pasien" for="x_kode_pasien" class="col-sm-2 control-label ewLabel"><?php echo $operasi->kode_pasien->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $operasi->kode_pasien->CellAttributes() ?>>
<span id="el_operasi_kode_pasien">
<input type="text" data-field="x_kode_pasien" name="x_kode_pasien" id="x_kode_pasien" size="30" maxlength="6" placeholder="<?php echo ew_HtmlEncode($operasi->kode_pasien->PlaceHolder) ?>" value="<?php echo $operasi->kode_pasien->EditValue ?>"<?php echo $operasi->kode_pasien->EditAttributes() ?>>
</span>
<?php echo $operasi->kode_pasien->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($operasi->id_tim->Visible) { // id_tim ?>
	<div id="r_id_tim" class="form-group">
		<label id="elh_operasi_id_tim" for="x_id_tim" class="col-sm-2 control-label ewLabel"><?php echo $operasi->id_tim->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $operasi->id_tim->CellAttributes() ?>>
<span id="el_operasi_id_tim">
<input type="text" data-field="x_id_tim" name="x_id_tim" id="x_id_tim" size="30" maxlength="6" placeholder="<?php echo ew_HtmlEncode($operasi->id_tim->PlaceHolder) ?>" value="<?php echo $operasi->id_tim->EditValue ?>"<?php echo $operasi->id_tim->EditAttributes() ?>>
</span>
<?php echo $operasi->id_tim->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($operasi->kode_ruang->Visible) { // kode_ruang ?>
	<div id="r_kode_ruang" class="form-group">
		<label id="elh_operasi_kode_ruang" for="x_kode_ruang" class="col-sm-2 control-label ewLabel"><?php echo $operasi->kode_ruang->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $operasi->kode_ruang->CellAttributes() ?>>
<span id="el_operasi_kode_ruang">
<input type="text" data-field="x_kode_ruang" name="x_kode_ruang" id="x_kode_ruang" size="30" maxlength="6" placeholder="<?php echo ew_HtmlEncode($operasi->kode_ruang->PlaceHolder) ?>" value="<?php echo $operasi->kode_ruang->EditValue ?>"<?php echo $operasi->kode_ruang->EditAttributes() ?>>
</span>
<?php echo $operasi->kode_ruang->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($operasi->jam_mulai->Visible) { // jam_mulai ?>
	<div id="r_jam_mulai" class="form-group">
		<label id="elh_operasi_jam_mulai" for="x_jam_mulai" class="col-sm-2 control-label ewLabel"><?php echo $operasi->jam_mulai->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $operasi->jam_mulai->CellAttributes() ?>>
<span id="el_operasi_jam_mulai">
<input type="text" data-field="x_jam_mulai" name="x_jam_mulai" id="x_jam_mulai" placeholder="<?php echo ew_HtmlEncode($operasi->jam_mulai->PlaceHolder) ?>" value="<?php echo $operasi->jam_mulai->EditValue ?>"<?php echo $operasi->jam_mulai->EditAttributes() ?>>
</span>
<?php echo $operasi->jam_mulai->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($operasi->jam_berakhir->Visible) { // jam_berakhir ?>
	<div id="r_jam_berakhir" class="form-group">
		<label id="elh_operasi_jam_berakhir" for="x_jam_berakhir" class="col-sm-2 control-label ewLabel"><?php echo $operasi->jam_berakhir->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $operasi->jam_berakhir->CellAttributes() ?>>
<span id="el_operasi_jam_berakhir">
<input type="text" data-field="x_jam_berakhir" name="x_jam_berakhir" id="x_jam_berakhir" placeholder="<?php echo ew_HtmlEncode($operasi->jam_berakhir->PlaceHolder) ?>" value="<?php echo $operasi->jam_berakhir->EditValue ?>"<?php echo $operasi->jam_berakhir->EditAttributes() ?>>
</span>
<?php echo $operasi->jam_berakhir->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
foperasiadd.Init();
</script>
<?php
$operasi_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$operasi_add->Page_Terminate();
?>
