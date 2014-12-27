<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "dokterinfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$dokter_add = NULL; // Initialize page object first

class cdokter_add extends cdokter {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{E4257960-51C0-4B8A-8F41-FDCC3F20971D}";

	// Table name
	var $TableName = 'dokter';

	// Page object name
	var $PageObjName = 'dokter_add';

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

		// Table object (dokter)
		if (!isset($GLOBALS["dokter"]) || get_class($GLOBALS["dokter"]) == "cdokter") {
			$GLOBALS["dokter"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["dokter"];
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
			define("EW_TABLE_NAME", 'dokter', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("dokterlist.php"));
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
		global $EW_EXPORT, $dokter;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($dokter);
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
			if (@$_GET["kode_dokter"] != "") {
				$this->kode_dokter->setQueryStringValue($_GET["kode_dokter"]);
				$this->setKey("kode_dokter", $this->kode_dokter->CurrentValue); // Set up key
			} else {
				$this->setKey("kode_dokter", ""); // Clear key
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
					$this->Page_Terminate("dokterlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "dokterview.php")
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
		$this->kode_dokter->CurrentValue = NULL;
		$this->kode_dokter->OldValue = $this->kode_dokter->CurrentValue;
		$this->nama_dokter->CurrentValue = NULL;
		$this->nama_dokter->OldValue = $this->nama_dokter->CurrentValue;
		$this->jenis_kelamin->CurrentValue = NULL;
		$this->jenis_kelamin->OldValue = $this->jenis_kelamin->CurrentValue;
		$this->tgl_lahir->CurrentValue = NULL;
		$this->tgl_lahir->OldValue = $this->tgl_lahir->CurrentValue;
		$this->foto_dokter->CurrentValue = NULL;
		$this->foto_dokter->OldValue = $this->foto_dokter->CurrentValue;
		$this->spesialisasi->CurrentValue = NULL;
		$this->spesialisasi->OldValue = $this->spesialisasi->CurrentValue;
		$this->alamat_dokter->CurrentValue = NULL;
		$this->alamat_dokter->OldValue = $this->alamat_dokter->CurrentValue;
		$this->kota_dokter->CurrentValue = NULL;
		$this->kota_dokter->OldValue = $this->kota_dokter->CurrentValue;
		$this->telepon->CurrentValue = NULL;
		$this->telepon->OldValue = $this->telepon->CurrentValue;
		$this->SIP->CurrentValue = NULL;
		$this->SIP->OldValue = $this->SIP->CurrentValue;
		$this->user_id->CurrentValue = NULL;
		$this->user_id->OldValue = $this->user_id->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->kode_dokter->FldIsDetailKey) {
			$this->kode_dokter->setFormValue($objForm->GetValue("x_kode_dokter"));
		}
		if (!$this->nama_dokter->FldIsDetailKey) {
			$this->nama_dokter->setFormValue($objForm->GetValue("x_nama_dokter"));
		}
		if (!$this->jenis_kelamin->FldIsDetailKey) {
			$this->jenis_kelamin->setFormValue($objForm->GetValue("x_jenis_kelamin"));
		}
		if (!$this->tgl_lahir->FldIsDetailKey) {
			$this->tgl_lahir->setFormValue($objForm->GetValue("x_tgl_lahir"));
			$this->tgl_lahir->CurrentValue = ew_UnFormatDateTime($this->tgl_lahir->CurrentValue, 5);
		}
		if (!$this->foto_dokter->FldIsDetailKey) {
			$this->foto_dokter->setFormValue($objForm->GetValue("x_foto_dokter"));
		}
		if (!$this->spesialisasi->FldIsDetailKey) {
			$this->spesialisasi->setFormValue($objForm->GetValue("x_spesialisasi"));
		}
		if (!$this->alamat_dokter->FldIsDetailKey) {
			$this->alamat_dokter->setFormValue($objForm->GetValue("x_alamat_dokter"));
		}
		if (!$this->kota_dokter->FldIsDetailKey) {
			$this->kota_dokter->setFormValue($objForm->GetValue("x_kota_dokter"));
		}
		if (!$this->telepon->FldIsDetailKey) {
			$this->telepon->setFormValue($objForm->GetValue("x_telepon"));
		}
		if (!$this->SIP->FldIsDetailKey) {
			$this->SIP->setFormValue($objForm->GetValue("x_SIP"));
		}
		if (!$this->user_id->FldIsDetailKey) {
			$this->user_id->setFormValue($objForm->GetValue("x_user_id"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->kode_dokter->CurrentValue = $this->kode_dokter->FormValue;
		$this->nama_dokter->CurrentValue = $this->nama_dokter->FormValue;
		$this->jenis_kelamin->CurrentValue = $this->jenis_kelamin->FormValue;
		$this->tgl_lahir->CurrentValue = $this->tgl_lahir->FormValue;
		$this->tgl_lahir->CurrentValue = ew_UnFormatDateTime($this->tgl_lahir->CurrentValue, 5);
		$this->foto_dokter->CurrentValue = $this->foto_dokter->FormValue;
		$this->spesialisasi->CurrentValue = $this->spesialisasi->FormValue;
		$this->alamat_dokter->CurrentValue = $this->alamat_dokter->FormValue;
		$this->kota_dokter->CurrentValue = $this->kota_dokter->FormValue;
		$this->telepon->CurrentValue = $this->telepon->FormValue;
		$this->SIP->CurrentValue = $this->SIP->FormValue;
		$this->user_id->CurrentValue = $this->user_id->FormValue;
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

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->kode_dokter->DbValue = $row['kode_dokter'];
		$this->nama_dokter->DbValue = $row['nama_dokter'];
		$this->jenis_kelamin->DbValue = $row['jenis_kelamin'];
		$this->tgl_lahir->DbValue = $row['tgl_lahir'];
		$this->foto_dokter->DbValue = $row['foto_dokter'];
		$this->spesialisasi->DbValue = $row['spesialisasi'];
		$this->alamat_dokter->DbValue = $row['alamat_dokter'];
		$this->kota_dokter->DbValue = $row['kota_dokter'];
		$this->telepon->DbValue = $row['telepon'];
		$this->SIP->DbValue = $row['SIP'];
		$this->user_id->DbValue = $row['user_id'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("kode_dokter")) <> "")
			$this->kode_dokter->CurrentValue = $this->getKey("kode_dokter"); // kode_dokter
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

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// kode_dokter
			$this->kode_dokter->EditAttrs["class"] = "form-control";
			$this->kode_dokter->EditCustomAttributes = "";
			$this->kode_dokter->EditValue = ew_HtmlEncode($this->kode_dokter->CurrentValue);
			$this->kode_dokter->PlaceHolder = ew_RemoveHtml($this->kode_dokter->FldCaption());

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

			// Edit refer script
			// kode_dokter

			$this->kode_dokter->HrefValue = "";

			// nama_dokter
			$this->nama_dokter->HrefValue = "";

			// jenis_kelamin
			$this->jenis_kelamin->HrefValue = "";

			// tgl_lahir
			$this->tgl_lahir->HrefValue = "";

			// foto_dokter
			$this->foto_dokter->HrefValue = "";

			// spesialisasi
			$this->spesialisasi->HrefValue = "";

			// alamat_dokter
			$this->alamat_dokter->HrefValue = "";

			// kota_dokter
			$this->kota_dokter->HrefValue = "";

			// telepon
			$this->telepon->HrefValue = "";

			// SIP
			$this->SIP->HrefValue = "";

			// user_id
			$this->user_id->HrefValue = "";
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
		if (!$this->kode_dokter->FldIsDetailKey && !is_null($this->kode_dokter->FormValue) && $this->kode_dokter->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->kode_dokter->FldCaption(), $this->kode_dokter->ReqErrMsg));
		}
		if (!$this->nama_dokter->FldIsDetailKey && !is_null($this->nama_dokter->FormValue) && $this->nama_dokter->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->nama_dokter->FldCaption(), $this->nama_dokter->ReqErrMsg));
		}
		if (!$this->jenis_kelamin->FldIsDetailKey && !is_null($this->jenis_kelamin->FormValue) && $this->jenis_kelamin->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jenis_kelamin->FldCaption(), $this->jenis_kelamin->ReqErrMsg));
		}
		if (!ew_CheckDate($this->tgl_lahir->FormValue)) {
			ew_AddMessage($gsFormError, $this->tgl_lahir->FldErrMsg());
		}
		if (!$this->SIP->FldIsDetailKey && !is_null($this->SIP->FormValue) && $this->SIP->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->SIP->FldCaption(), $this->SIP->ReqErrMsg));
		}
		if (!$this->user_id->FldIsDetailKey && !is_null($this->user_id->FormValue) && $this->user_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->user_id->FldCaption(), $this->user_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->user_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->user_id->FldErrMsg());
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

		// kode_dokter
		$this->kode_dokter->SetDbValueDef($rsnew, $this->kode_dokter->CurrentValue, "", FALSE);

		// nama_dokter
		$this->nama_dokter->SetDbValueDef($rsnew, $this->nama_dokter->CurrentValue, "", FALSE);

		// jenis_kelamin
		$this->jenis_kelamin->SetDbValueDef($rsnew, $this->jenis_kelamin->CurrentValue, "", FALSE);

		// tgl_lahir
		$this->tgl_lahir->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tgl_lahir->CurrentValue, 5), NULL, FALSE);

		// foto_dokter
		$this->foto_dokter->SetDbValueDef($rsnew, $this->foto_dokter->CurrentValue, NULL, FALSE);

		// spesialisasi
		$this->spesialisasi->SetDbValueDef($rsnew, $this->spesialisasi->CurrentValue, NULL, FALSE);

		// alamat_dokter
		$this->alamat_dokter->SetDbValueDef($rsnew, $this->alamat_dokter->CurrentValue, NULL, FALSE);

		// kota_dokter
		$this->kota_dokter->SetDbValueDef($rsnew, $this->kota_dokter->CurrentValue, NULL, FALSE);

		// telepon
		$this->telepon->SetDbValueDef($rsnew, $this->telepon->CurrentValue, NULL, FALSE);

		// SIP
		$this->SIP->SetDbValueDef($rsnew, $this->SIP->CurrentValue, "", FALSE);

		// user_id
		$this->user_id->SetDbValueDef($rsnew, $this->user_id->CurrentValue, 0, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['kode_dokter']) == "") {
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
		$Breadcrumb->Add("list", $this->TableVar, "dokterlist.php", "", $this->TableVar, TRUE);
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
if (!isset($dokter_add)) $dokter_add = new cdokter_add();

// Page init
$dokter_add->Page_Init();

// Page main
$dokter_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$dokter_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var dokter_add = new ew_Page("dokter_add");
dokter_add.PageID = "add"; // Page ID
var EW_PAGE_ID = dokter_add.PageID; // For backward compatibility

// Form object
var fdokteradd = new ew_Form("fdokteradd");

// Validate form
fdokteradd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_kode_dokter");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $dokter->kode_dokter->FldCaption(), $dokter->kode_dokter->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_nama_dokter");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $dokter->nama_dokter->FldCaption(), $dokter->nama_dokter->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jenis_kelamin");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $dokter->jenis_kelamin->FldCaption(), $dokter->jenis_kelamin->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tgl_lahir");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($dokter->tgl_lahir->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_SIP");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $dokter->SIP->FldCaption(), $dokter->SIP->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_user_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $dokter->user_id->FldCaption(), $dokter->user_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_user_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($dokter->user_id->FldErrMsg()) ?>");

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
fdokteradd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdokteradd.ValidateRequired = true;
<?php } else { ?>
fdokteradd.ValidateRequired = false; 
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
<?php $dokter_add->ShowPageHeader(); ?>
<?php
$dokter_add->ShowMessage();
?>
<form name="fdokteradd" id="fdokteradd" class="form-horizontal ewForm ewAddForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($dokter_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $dokter_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="dokter">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($dokter->kode_dokter->Visible) { // kode_dokter ?>
	<div id="r_kode_dokter" class="form-group">
		<label id="elh_dokter_kode_dokter" for="x_kode_dokter" class="col-sm-2 control-label ewLabel"><?php echo $dokter->kode_dokter->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $dokter->kode_dokter->CellAttributes() ?>>
<span id="el_dokter_kode_dokter">
<input type="text" data-field="x_kode_dokter" name="x_kode_dokter" id="x_kode_dokter" size="30" maxlength="6" placeholder="<?php echo ew_HtmlEncode($dokter->kode_dokter->PlaceHolder) ?>" value="<?php echo $dokter->kode_dokter->EditValue ?>"<?php echo $dokter->kode_dokter->EditAttributes() ?>>
</span>
<?php echo $dokter->kode_dokter->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($dokter->nama_dokter->Visible) { // nama_dokter ?>
	<div id="r_nama_dokter" class="form-group">
		<label id="elh_dokter_nama_dokter" for="x_nama_dokter" class="col-sm-2 control-label ewLabel"><?php echo $dokter->nama_dokter->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $dokter->nama_dokter->CellAttributes() ?>>
<span id="el_dokter_nama_dokter">
<input type="text" data-field="x_nama_dokter" name="x_nama_dokter" id="x_nama_dokter" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($dokter->nama_dokter->PlaceHolder) ?>" value="<?php echo $dokter->nama_dokter->EditValue ?>"<?php echo $dokter->nama_dokter->EditAttributes() ?>>
</span>
<?php echo $dokter->nama_dokter->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($dokter->jenis_kelamin->Visible) { // jenis_kelamin ?>
	<div id="r_jenis_kelamin" class="form-group">
		<label id="elh_dokter_jenis_kelamin" for="x_jenis_kelamin" class="col-sm-2 control-label ewLabel"><?php echo $dokter->jenis_kelamin->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $dokter->jenis_kelamin->CellAttributes() ?>>
<span id="el_dokter_jenis_kelamin">
<input type="text" data-field="x_jenis_kelamin" name="x_jenis_kelamin" id="x_jenis_kelamin" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($dokter->jenis_kelamin->PlaceHolder) ?>" value="<?php echo $dokter->jenis_kelamin->EditValue ?>"<?php echo $dokter->jenis_kelamin->EditAttributes() ?>>
</span>
<?php echo $dokter->jenis_kelamin->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($dokter->tgl_lahir->Visible) { // tgl_lahir ?>
	<div id="r_tgl_lahir" class="form-group">
		<label id="elh_dokter_tgl_lahir" for="x_tgl_lahir" class="col-sm-2 control-label ewLabel"><?php echo $dokter->tgl_lahir->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $dokter->tgl_lahir->CellAttributes() ?>>
<span id="el_dokter_tgl_lahir">
<input type="text" data-field="x_tgl_lahir" name="x_tgl_lahir" id="x_tgl_lahir" placeholder="<?php echo ew_HtmlEncode($dokter->tgl_lahir->PlaceHolder) ?>" value="<?php echo $dokter->tgl_lahir->EditValue ?>"<?php echo $dokter->tgl_lahir->EditAttributes() ?>>
</span>
<?php echo $dokter->tgl_lahir->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($dokter->foto_dokter->Visible) { // foto_dokter ?>
	<div id="r_foto_dokter" class="form-group">
		<label id="elh_dokter_foto_dokter" for="x_foto_dokter" class="col-sm-2 control-label ewLabel"><?php echo $dokter->foto_dokter->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $dokter->foto_dokter->CellAttributes() ?>>
<span id="el_dokter_foto_dokter">
<textarea data-field="x_foto_dokter" name="x_foto_dokter" id="x_foto_dokter" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($dokter->foto_dokter->PlaceHolder) ?>"<?php echo $dokter->foto_dokter->EditAttributes() ?>><?php echo $dokter->foto_dokter->EditValue ?></textarea>
</span>
<?php echo $dokter->foto_dokter->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($dokter->spesialisasi->Visible) { // spesialisasi ?>
	<div id="r_spesialisasi" class="form-group">
		<label id="elh_dokter_spesialisasi" for="x_spesialisasi" class="col-sm-2 control-label ewLabel"><?php echo $dokter->spesialisasi->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $dokter->spesialisasi->CellAttributes() ?>>
<span id="el_dokter_spesialisasi">
<textarea data-field="x_spesialisasi" name="x_spesialisasi" id="x_spesialisasi" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($dokter->spesialisasi->PlaceHolder) ?>"<?php echo $dokter->spesialisasi->EditAttributes() ?>><?php echo $dokter->spesialisasi->EditValue ?></textarea>
</span>
<?php echo $dokter->spesialisasi->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($dokter->alamat_dokter->Visible) { // alamat_dokter ?>
	<div id="r_alamat_dokter" class="form-group">
		<label id="elh_dokter_alamat_dokter" for="x_alamat_dokter" class="col-sm-2 control-label ewLabel"><?php echo $dokter->alamat_dokter->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $dokter->alamat_dokter->CellAttributes() ?>>
<span id="el_dokter_alamat_dokter">
<textarea data-field="x_alamat_dokter" name="x_alamat_dokter" id="x_alamat_dokter" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($dokter->alamat_dokter->PlaceHolder) ?>"<?php echo $dokter->alamat_dokter->EditAttributes() ?>><?php echo $dokter->alamat_dokter->EditValue ?></textarea>
</span>
<?php echo $dokter->alamat_dokter->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($dokter->kota_dokter->Visible) { // kota_dokter ?>
	<div id="r_kota_dokter" class="form-group">
		<label id="elh_dokter_kota_dokter" for="x_kota_dokter" class="col-sm-2 control-label ewLabel"><?php echo $dokter->kota_dokter->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $dokter->kota_dokter->CellAttributes() ?>>
<span id="el_dokter_kota_dokter">
<textarea data-field="x_kota_dokter" name="x_kota_dokter" id="x_kota_dokter" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($dokter->kota_dokter->PlaceHolder) ?>"<?php echo $dokter->kota_dokter->EditAttributes() ?>><?php echo $dokter->kota_dokter->EditValue ?></textarea>
</span>
<?php echo $dokter->kota_dokter->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($dokter->telepon->Visible) { // telepon ?>
	<div id="r_telepon" class="form-group">
		<label id="elh_dokter_telepon" for="x_telepon" class="col-sm-2 control-label ewLabel"><?php echo $dokter->telepon->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $dokter->telepon->CellAttributes() ?>>
<span id="el_dokter_telepon">
<input type="text" data-field="x_telepon" name="x_telepon" id="x_telepon" size="30" maxlength="13" placeholder="<?php echo ew_HtmlEncode($dokter->telepon->PlaceHolder) ?>" value="<?php echo $dokter->telepon->EditValue ?>"<?php echo $dokter->telepon->EditAttributes() ?>>
</span>
<?php echo $dokter->telepon->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($dokter->SIP->Visible) { // SIP ?>
	<div id="r_SIP" class="form-group">
		<label id="elh_dokter_SIP" for="x_SIP" class="col-sm-2 control-label ewLabel"><?php echo $dokter->SIP->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $dokter->SIP->CellAttributes() ?>>
<span id="el_dokter_SIP">
<input type="text" data-field="x_SIP" name="x_SIP" id="x_SIP" size="30" maxlength="16" placeholder="<?php echo ew_HtmlEncode($dokter->SIP->PlaceHolder) ?>" value="<?php echo $dokter->SIP->EditValue ?>"<?php echo $dokter->SIP->EditAttributes() ?>>
</span>
<?php echo $dokter->SIP->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($dokter->user_id->Visible) { // user_id ?>
	<div id="r_user_id" class="form-group">
		<label id="elh_dokter_user_id" for="x_user_id" class="col-sm-2 control-label ewLabel"><?php echo $dokter->user_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $dokter->user_id->CellAttributes() ?>>
<span id="el_dokter_user_id">
<input type="text" data-field="x_user_id" name="x_user_id" id="x_user_id" size="30" placeholder="<?php echo ew_HtmlEncode($dokter->user_id->PlaceHolder) ?>" value="<?php echo $dokter->user_id->EditValue ?>"<?php echo $dokter->user_id->EditAttributes() ?>>
</span>
<?php echo $dokter->user_id->CustomMsg ?></div></div>
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
fdokteradd.Init();
</script>
<?php
$dokter_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$dokter_add->Page_Terminate();
?>
