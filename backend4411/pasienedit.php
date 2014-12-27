<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "pasieninfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$pasien_edit = NULL; // Initialize page object first

class cpasien_edit extends cpasien {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{E4257960-51C0-4B8A-8F41-FDCC3F20971D}";

	// Table name
	var $TableName = 'pasien';

	// Page object name
	var $PageObjName = 'pasien_edit';

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

		// Table object (pasien)
		if (!isset($GLOBALS["pasien"]) || get_class($GLOBALS["pasien"]) == "cpasien") {
			$GLOBALS["pasien"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["pasien"];
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
			define("EW_TABLE_NAME", 'pasien', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("pasienlist.php"));
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
		global $EW_EXPORT, $pasien;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($pasien);
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
		if (@$_GET["kode_pasien"] <> "") {
			$this->kode_pasien->setQueryStringValue($_GET["kode_pasien"]);
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
		if ($this->kode_pasien->CurrentValue == "")
			$this->Page_Terminate("pasienlist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("pasienlist.php"); // No matching record, return to list
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
		if (!$this->kode_pasien->FldIsDetailKey) {
			$this->kode_pasien->setFormValue($objForm->GetValue("x_kode_pasien"));
		}
		if (!$this->nama_pasien->FldIsDetailKey) {
			$this->nama_pasien->setFormValue($objForm->GetValue("x_nama_pasien"));
		}
		if (!$this->alamat_pasien->FldIsDetailKey) {
			$this->alamat_pasien->setFormValue($objForm->GetValue("x_alamat_pasien"));
		}
		if (!$this->jenis_kelamin->FldIsDetailKey) {
			$this->jenis_kelamin->setFormValue($objForm->GetValue("x_jenis_kelamin"));
		}
		if (!$this->tgl_lahir->FldIsDetailKey) {
			$this->tgl_lahir->setFormValue($objForm->GetValue("x_tgl_lahir"));
			$this->tgl_lahir->CurrentValue = ew_UnFormatDateTime($this->tgl_lahir->CurrentValue, 5);
		}
		if (!$this->kota_pasien->FldIsDetailKey) {
			$this->kota_pasien->setFormValue($objForm->GetValue("x_kota_pasien"));
		}
		if (!$this->tgl_datang->FldIsDetailKey) {
			$this->tgl_datang->setFormValue($objForm->GetValue("x_tgl_datang"));
			$this->tgl_datang->CurrentValue = ew_UnFormatDateTime($this->tgl_datang->CurrentValue, 5);
		}
		if (!$this->tgl_keluar->FldIsDetailKey) {
			$this->tgl_keluar->setFormValue($objForm->GetValue("x_tgl_keluar"));
			$this->tgl_keluar->CurrentValue = ew_UnFormatDateTime($this->tgl_keluar->CurrentValue, 5);
		}
		if (!$this->kode_dokter->FldIsDetailKey) {
			$this->kode_dokter->setFormValue($objForm->GetValue("x_kode_dokter"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->kode_pasien->CurrentValue = $this->kode_pasien->FormValue;
		$this->nama_pasien->CurrentValue = $this->nama_pasien->FormValue;
		$this->alamat_pasien->CurrentValue = $this->alamat_pasien->FormValue;
		$this->jenis_kelamin->CurrentValue = $this->jenis_kelamin->FormValue;
		$this->tgl_lahir->CurrentValue = $this->tgl_lahir->FormValue;
		$this->tgl_lahir->CurrentValue = ew_UnFormatDateTime($this->tgl_lahir->CurrentValue, 5);
		$this->kota_pasien->CurrentValue = $this->kota_pasien->FormValue;
		$this->tgl_datang->CurrentValue = $this->tgl_datang->FormValue;
		$this->tgl_datang->CurrentValue = ew_UnFormatDateTime($this->tgl_datang->CurrentValue, 5);
		$this->tgl_keluar->CurrentValue = $this->tgl_keluar->FormValue;
		$this->tgl_keluar->CurrentValue = ew_UnFormatDateTime($this->tgl_keluar->CurrentValue, 5);
		$this->kode_dokter->CurrentValue = $this->kode_dokter->FormValue;
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
		$this->kode_pasien->setDbValue($rs->fields('kode_pasien'));
		$this->nama_pasien->setDbValue($rs->fields('nama_pasien'));
		$this->alamat_pasien->setDbValue($rs->fields('alamat_pasien'));
		$this->jenis_kelamin->setDbValue($rs->fields('jenis_kelamin'));
		$this->tgl_lahir->setDbValue($rs->fields('tgl_lahir'));
		$this->kota_pasien->setDbValue($rs->fields('kota_pasien'));
		$this->tgl_datang->setDbValue($rs->fields('tgl_datang'));
		$this->tgl_keluar->setDbValue($rs->fields('tgl_keluar'));
		$this->kode_dokter->setDbValue($rs->fields('kode_dokter'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->kode_pasien->DbValue = $row['kode_pasien'];
		$this->nama_pasien->DbValue = $row['nama_pasien'];
		$this->alamat_pasien->DbValue = $row['alamat_pasien'];
		$this->jenis_kelamin->DbValue = $row['jenis_kelamin'];
		$this->tgl_lahir->DbValue = $row['tgl_lahir'];
		$this->kota_pasien->DbValue = $row['kota_pasien'];
		$this->tgl_datang->DbValue = $row['tgl_datang'];
		$this->tgl_keluar->DbValue = $row['tgl_keluar'];
		$this->kode_dokter->DbValue = $row['kode_dokter'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// kode_pasien
		// nama_pasien
		// alamat_pasien
		// jenis_kelamin
		// tgl_lahir
		// kota_pasien
		// tgl_datang
		// tgl_keluar
		// kode_dokter

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// kode_pasien
			$this->kode_pasien->ViewValue = $this->kode_pasien->CurrentValue;
			$this->kode_pasien->ViewCustomAttributes = "";

			// nama_pasien
			$this->nama_pasien->ViewValue = $this->nama_pasien->CurrentValue;
			$this->nama_pasien->ViewCustomAttributes = "";

			// alamat_pasien
			$this->alamat_pasien->ViewValue = $this->alamat_pasien->CurrentValue;
			$this->alamat_pasien->ViewCustomAttributes = "";

			// jenis_kelamin
			$this->jenis_kelamin->ViewValue = $this->jenis_kelamin->CurrentValue;
			$this->jenis_kelamin->ViewCustomAttributes = "";

			// tgl_lahir
			$this->tgl_lahir->ViewValue = $this->tgl_lahir->CurrentValue;
			$this->tgl_lahir->ViewValue = ew_FormatDateTime($this->tgl_lahir->ViewValue, 5);
			$this->tgl_lahir->ViewCustomAttributes = "";

			// kota_pasien
			$this->kota_pasien->ViewValue = $this->kota_pasien->CurrentValue;
			$this->kota_pasien->ViewCustomAttributes = "";

			// tgl_datang
			$this->tgl_datang->ViewValue = $this->tgl_datang->CurrentValue;
			$this->tgl_datang->ViewValue = ew_FormatDateTime($this->tgl_datang->ViewValue, 5);
			$this->tgl_datang->ViewCustomAttributes = "";

			// tgl_keluar
			$this->tgl_keluar->ViewValue = $this->tgl_keluar->CurrentValue;
			$this->tgl_keluar->ViewValue = ew_FormatDateTime($this->tgl_keluar->ViewValue, 5);
			$this->tgl_keluar->ViewCustomAttributes = "";

			// kode_dokter
			$this->kode_dokter->ViewValue = $this->kode_dokter->CurrentValue;
			$this->kode_dokter->ViewCustomAttributes = "";

			// kode_pasien
			$this->kode_pasien->LinkCustomAttributes = "";
			$this->kode_pasien->HrefValue = "";
			$this->kode_pasien->TooltipValue = "";

			// nama_pasien
			$this->nama_pasien->LinkCustomAttributes = "";
			$this->nama_pasien->HrefValue = "";
			$this->nama_pasien->TooltipValue = "";

			// alamat_pasien
			$this->alamat_pasien->LinkCustomAttributes = "";
			$this->alamat_pasien->HrefValue = "";
			$this->alamat_pasien->TooltipValue = "";

			// jenis_kelamin
			$this->jenis_kelamin->LinkCustomAttributes = "";
			$this->jenis_kelamin->HrefValue = "";
			$this->jenis_kelamin->TooltipValue = "";

			// tgl_lahir
			$this->tgl_lahir->LinkCustomAttributes = "";
			$this->tgl_lahir->HrefValue = "";
			$this->tgl_lahir->TooltipValue = "";

			// kota_pasien
			$this->kota_pasien->LinkCustomAttributes = "";
			$this->kota_pasien->HrefValue = "";
			$this->kota_pasien->TooltipValue = "";

			// tgl_datang
			$this->tgl_datang->LinkCustomAttributes = "";
			$this->tgl_datang->HrefValue = "";
			$this->tgl_datang->TooltipValue = "";

			// tgl_keluar
			$this->tgl_keluar->LinkCustomAttributes = "";
			$this->tgl_keluar->HrefValue = "";
			$this->tgl_keluar->TooltipValue = "";

			// kode_dokter
			$this->kode_dokter->LinkCustomAttributes = "";
			$this->kode_dokter->HrefValue = "";
			$this->kode_dokter->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// kode_pasien
			$this->kode_pasien->EditAttrs["class"] = "form-control";
			$this->kode_pasien->EditCustomAttributes = "";
			$this->kode_pasien->EditValue = $this->kode_pasien->CurrentValue;
			$this->kode_pasien->ViewCustomAttributes = "";

			// nama_pasien
			$this->nama_pasien->EditAttrs["class"] = "form-control";
			$this->nama_pasien->EditCustomAttributes = "";
			$this->nama_pasien->EditValue = ew_HtmlEncode($this->nama_pasien->CurrentValue);
			$this->nama_pasien->PlaceHolder = ew_RemoveHtml($this->nama_pasien->FldCaption());

			// alamat_pasien
			$this->alamat_pasien->EditAttrs["class"] = "form-control";
			$this->alamat_pasien->EditCustomAttributes = "";
			$this->alamat_pasien->EditValue = ew_HtmlEncode($this->alamat_pasien->CurrentValue);
			$this->alamat_pasien->PlaceHolder = ew_RemoveHtml($this->alamat_pasien->FldCaption());

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

			// kota_pasien
			$this->kota_pasien->EditAttrs["class"] = "form-control";
			$this->kota_pasien->EditCustomAttributes = "";
			$this->kota_pasien->EditValue = ew_HtmlEncode($this->kota_pasien->CurrentValue);
			$this->kota_pasien->PlaceHolder = ew_RemoveHtml($this->kota_pasien->FldCaption());

			// tgl_datang
			$this->tgl_datang->EditAttrs["class"] = "form-control";
			$this->tgl_datang->EditCustomAttributes = "";
			$this->tgl_datang->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->tgl_datang->CurrentValue, 5));
			$this->tgl_datang->PlaceHolder = ew_RemoveHtml($this->tgl_datang->FldCaption());

			// tgl_keluar
			$this->tgl_keluar->EditAttrs["class"] = "form-control";
			$this->tgl_keluar->EditCustomAttributes = "";
			$this->tgl_keluar->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->tgl_keluar->CurrentValue, 5));
			$this->tgl_keluar->PlaceHolder = ew_RemoveHtml($this->tgl_keluar->FldCaption());

			// kode_dokter
			$this->kode_dokter->EditAttrs["class"] = "form-control";
			$this->kode_dokter->EditCustomAttributes = "";
			$this->kode_dokter->EditValue = ew_HtmlEncode($this->kode_dokter->CurrentValue);
			$this->kode_dokter->PlaceHolder = ew_RemoveHtml($this->kode_dokter->FldCaption());

			// Edit refer script
			// kode_pasien

			$this->kode_pasien->HrefValue = "";

			// nama_pasien
			$this->nama_pasien->HrefValue = "";

			// alamat_pasien
			$this->alamat_pasien->HrefValue = "";

			// jenis_kelamin
			$this->jenis_kelamin->HrefValue = "";

			// tgl_lahir
			$this->tgl_lahir->HrefValue = "";

			// kota_pasien
			$this->kota_pasien->HrefValue = "";

			// tgl_datang
			$this->tgl_datang->HrefValue = "";

			// tgl_keluar
			$this->tgl_keluar->HrefValue = "";

			// kode_dokter
			$this->kode_dokter->HrefValue = "";
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
		if (!$this->kode_pasien->FldIsDetailKey && !is_null($this->kode_pasien->FormValue) && $this->kode_pasien->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->kode_pasien->FldCaption(), $this->kode_pasien->ReqErrMsg));
		}
		if (!$this->nama_pasien->FldIsDetailKey && !is_null($this->nama_pasien->FormValue) && $this->nama_pasien->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->nama_pasien->FldCaption(), $this->nama_pasien->ReqErrMsg));
		}
		if (!$this->jenis_kelamin->FldIsDetailKey && !is_null($this->jenis_kelamin->FormValue) && $this->jenis_kelamin->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jenis_kelamin->FldCaption(), $this->jenis_kelamin->ReqErrMsg));
		}
		if (!ew_CheckDate($this->tgl_lahir->FormValue)) {
			ew_AddMessage($gsFormError, $this->tgl_lahir->FldErrMsg());
		}
		if (!ew_CheckDate($this->tgl_datang->FormValue)) {
			ew_AddMessage($gsFormError, $this->tgl_datang->FldErrMsg());
		}
		if (!ew_CheckDate($this->tgl_keluar->FormValue)) {
			ew_AddMessage($gsFormError, $this->tgl_keluar->FldErrMsg());
		}
		if (!$this->kode_dokter->FldIsDetailKey && !is_null($this->kode_dokter->FormValue) && $this->kode_dokter->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->kode_dokter->FldCaption(), $this->kode_dokter->ReqErrMsg));
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

			// kode_pasien
			// nama_pasien

			$this->nama_pasien->SetDbValueDef($rsnew, $this->nama_pasien->CurrentValue, "", $this->nama_pasien->ReadOnly);

			// alamat_pasien
			$this->alamat_pasien->SetDbValueDef($rsnew, $this->alamat_pasien->CurrentValue, NULL, $this->alamat_pasien->ReadOnly);

			// jenis_kelamin
			$this->jenis_kelamin->SetDbValueDef($rsnew, $this->jenis_kelamin->CurrentValue, "", $this->jenis_kelamin->ReadOnly);

			// tgl_lahir
			$this->tgl_lahir->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tgl_lahir->CurrentValue, 5), NULL, $this->tgl_lahir->ReadOnly);

			// kota_pasien
			$this->kota_pasien->SetDbValueDef($rsnew, $this->kota_pasien->CurrentValue, NULL, $this->kota_pasien->ReadOnly);

			// tgl_datang
			$this->tgl_datang->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tgl_datang->CurrentValue, 5), NULL, $this->tgl_datang->ReadOnly);

			// tgl_keluar
			$this->tgl_keluar->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tgl_keluar->CurrentValue, 5), NULL, $this->tgl_keluar->ReadOnly);

			// kode_dokter
			$this->kode_dokter->SetDbValueDef($rsnew, $this->kode_dokter->CurrentValue, "", $this->kode_dokter->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, "pasienlist.php", "", $this->TableVar, TRUE);
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
if (!isset($pasien_edit)) $pasien_edit = new cpasien_edit();

// Page init
$pasien_edit->Page_Init();

// Page main
$pasien_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pasien_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var pasien_edit = new ew_Page("pasien_edit");
pasien_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = pasien_edit.PageID; // For backward compatibility

// Form object
var fpasienedit = new ew_Form("fpasienedit");

// Validate form
fpasienedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_kode_pasien");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pasien->kode_pasien->FldCaption(), $pasien->kode_pasien->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_nama_pasien");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pasien->nama_pasien->FldCaption(), $pasien->nama_pasien->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jenis_kelamin");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pasien->jenis_kelamin->FldCaption(), $pasien->jenis_kelamin->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tgl_lahir");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pasien->tgl_lahir->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tgl_datang");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pasien->tgl_datang->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tgl_keluar");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pasien->tgl_keluar->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_kode_dokter");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pasien->kode_dokter->FldCaption(), $pasien->kode_dokter->ReqErrMsg)) ?>");

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
fpasienedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpasienedit.ValidateRequired = true;
<?php } else { ?>
fpasienedit.ValidateRequired = false; 
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
<?php $pasien_edit->ShowPageHeader(); ?>
<?php
$pasien_edit->ShowMessage();
?>
<form name="fpasienedit" id="fpasienedit" class="form-horizontal ewForm ewEditForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($pasien_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $pasien_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="pasien">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($pasien->kode_pasien->Visible) { // kode_pasien ?>
	<div id="r_kode_pasien" class="form-group">
		<label id="elh_pasien_kode_pasien" for="x_kode_pasien" class="col-sm-2 control-label ewLabel"><?php echo $pasien->kode_pasien->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pasien->kode_pasien->CellAttributes() ?>>
<span id="el_pasien_kode_pasien">
<span<?php echo $pasien->kode_pasien->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pasien->kode_pasien->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_kode_pasien" name="x_kode_pasien" id="x_kode_pasien" value="<?php echo ew_HtmlEncode($pasien->kode_pasien->CurrentValue) ?>">
<?php echo $pasien->kode_pasien->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pasien->nama_pasien->Visible) { // nama_pasien ?>
	<div id="r_nama_pasien" class="form-group">
		<label id="elh_pasien_nama_pasien" for="x_nama_pasien" class="col-sm-2 control-label ewLabel"><?php echo $pasien->nama_pasien->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pasien->nama_pasien->CellAttributes() ?>>
<span id="el_pasien_nama_pasien">
<input type="text" data-field="x_nama_pasien" name="x_nama_pasien" id="x_nama_pasien" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($pasien->nama_pasien->PlaceHolder) ?>" value="<?php echo $pasien->nama_pasien->EditValue ?>"<?php echo $pasien->nama_pasien->EditAttributes() ?>>
</span>
<?php echo $pasien->nama_pasien->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pasien->alamat_pasien->Visible) { // alamat_pasien ?>
	<div id="r_alamat_pasien" class="form-group">
		<label id="elh_pasien_alamat_pasien" for="x_alamat_pasien" class="col-sm-2 control-label ewLabel"><?php echo $pasien->alamat_pasien->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pasien->alamat_pasien->CellAttributes() ?>>
<span id="el_pasien_alamat_pasien">
<textarea data-field="x_alamat_pasien" name="x_alamat_pasien" id="x_alamat_pasien" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($pasien->alamat_pasien->PlaceHolder) ?>"<?php echo $pasien->alamat_pasien->EditAttributes() ?>><?php echo $pasien->alamat_pasien->EditValue ?></textarea>
</span>
<?php echo $pasien->alamat_pasien->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pasien->jenis_kelamin->Visible) { // jenis_kelamin ?>
	<div id="r_jenis_kelamin" class="form-group">
		<label id="elh_pasien_jenis_kelamin" for="x_jenis_kelamin" class="col-sm-2 control-label ewLabel"><?php echo $pasien->jenis_kelamin->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pasien->jenis_kelamin->CellAttributes() ?>>
<span id="el_pasien_jenis_kelamin">
<input type="text" data-field="x_jenis_kelamin" name="x_jenis_kelamin" id="x_jenis_kelamin" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($pasien->jenis_kelamin->PlaceHolder) ?>" value="<?php echo $pasien->jenis_kelamin->EditValue ?>"<?php echo $pasien->jenis_kelamin->EditAttributes() ?>>
</span>
<?php echo $pasien->jenis_kelamin->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pasien->tgl_lahir->Visible) { // tgl_lahir ?>
	<div id="r_tgl_lahir" class="form-group">
		<label id="elh_pasien_tgl_lahir" for="x_tgl_lahir" class="col-sm-2 control-label ewLabel"><?php echo $pasien->tgl_lahir->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pasien->tgl_lahir->CellAttributes() ?>>
<span id="el_pasien_tgl_lahir">
<input type="text" data-field="x_tgl_lahir" name="x_tgl_lahir" id="x_tgl_lahir" placeholder="<?php echo ew_HtmlEncode($pasien->tgl_lahir->PlaceHolder) ?>" value="<?php echo $pasien->tgl_lahir->EditValue ?>"<?php echo $pasien->tgl_lahir->EditAttributes() ?>>
</span>
<?php echo $pasien->tgl_lahir->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pasien->kota_pasien->Visible) { // kota_pasien ?>
	<div id="r_kota_pasien" class="form-group">
		<label id="elh_pasien_kota_pasien" for="x_kota_pasien" class="col-sm-2 control-label ewLabel"><?php echo $pasien->kota_pasien->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pasien->kota_pasien->CellAttributes() ?>>
<span id="el_pasien_kota_pasien">
<textarea data-field="x_kota_pasien" name="x_kota_pasien" id="x_kota_pasien" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($pasien->kota_pasien->PlaceHolder) ?>"<?php echo $pasien->kota_pasien->EditAttributes() ?>><?php echo $pasien->kota_pasien->EditValue ?></textarea>
</span>
<?php echo $pasien->kota_pasien->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pasien->tgl_datang->Visible) { // tgl_datang ?>
	<div id="r_tgl_datang" class="form-group">
		<label id="elh_pasien_tgl_datang" for="x_tgl_datang" class="col-sm-2 control-label ewLabel"><?php echo $pasien->tgl_datang->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pasien->tgl_datang->CellAttributes() ?>>
<span id="el_pasien_tgl_datang">
<input type="text" data-field="x_tgl_datang" name="x_tgl_datang" id="x_tgl_datang" placeholder="<?php echo ew_HtmlEncode($pasien->tgl_datang->PlaceHolder) ?>" value="<?php echo $pasien->tgl_datang->EditValue ?>"<?php echo $pasien->tgl_datang->EditAttributes() ?>>
</span>
<?php echo $pasien->tgl_datang->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pasien->tgl_keluar->Visible) { // tgl_keluar ?>
	<div id="r_tgl_keluar" class="form-group">
		<label id="elh_pasien_tgl_keluar" for="x_tgl_keluar" class="col-sm-2 control-label ewLabel"><?php echo $pasien->tgl_keluar->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pasien->tgl_keluar->CellAttributes() ?>>
<span id="el_pasien_tgl_keluar">
<input type="text" data-field="x_tgl_keluar" name="x_tgl_keluar" id="x_tgl_keluar" placeholder="<?php echo ew_HtmlEncode($pasien->tgl_keluar->PlaceHolder) ?>" value="<?php echo $pasien->tgl_keluar->EditValue ?>"<?php echo $pasien->tgl_keluar->EditAttributes() ?>>
</span>
<?php echo $pasien->tgl_keluar->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pasien->kode_dokter->Visible) { // kode_dokter ?>
	<div id="r_kode_dokter" class="form-group">
		<label id="elh_pasien_kode_dokter" for="x_kode_dokter" class="col-sm-2 control-label ewLabel"><?php echo $pasien->kode_dokter->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pasien->kode_dokter->CellAttributes() ?>>
<span id="el_pasien_kode_dokter">
<input type="text" data-field="x_kode_dokter" name="x_kode_dokter" id="x_kode_dokter" size="30" maxlength="6" placeholder="<?php echo ew_HtmlEncode($pasien->kode_dokter->PlaceHolder) ?>" value="<?php echo $pasien->kode_dokter->EditValue ?>"<?php echo $pasien->kode_dokter->EditAttributes() ?>>
</span>
<?php echo $pasien->kode_dokter->CustomMsg ?></div></div>
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
fpasienedit.Init();
</script>
<?php
$pasien_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$pasien_edit->Page_Terminate();
?>
