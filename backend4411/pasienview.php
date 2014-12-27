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

$pasien_view = NULL; // Initialize page object first

class cpasien_view extends cpasien {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{E4257960-51C0-4B8A-8F41-FDCC3F20971D}";

	// Table name
	var $TableName = 'pasien';

	// Page object name
	var $PageObjName = 'pasien_view';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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
		$KeyUrl = "";
		if (@$_GET["kode_pasien"] <> "") {
			$this->RecKey["kode_pasien"] = $_GET["kode_pasien"];
			$KeyUrl .= "&amp;kode_pasien=" . urlencode($this->RecKey["kode_pasien"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// User table object (user)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'pasien', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
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
		if (!$Security->CanView()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ew_GetUrl("pasienlist.php"));
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
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["kode_pasien"] <> "") {
				$this->kode_pasien->setQueryStringValue($_GET["kode_pasien"]);
				$this->RecKey["kode_pasien"] = $this->kode_pasien->QueryStringValue;
			} else {
				$sReturnUrl = "pasienlist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "pasienlist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "pasienlist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageAddLink")) . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());

		// Edit
		$item = &$option->Add("edit");
		$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageEditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "" && $Security->CanEdit());

		// Copy
		$item = &$option->Add("copy");
		$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageCopyLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		$item->Visible = ($this->CopyUrl <> "" && $Security->CanAdd());

		// Delete
		$item = &$option->Add("delete");
		$item->Body = "<a class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "" && $Security->CanDelete());

		// Set up action default
		$option = &$options["action"];
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
		$option->UseImageAndText = TRUE;
		$option->UseDropDownButton = FALSE;
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
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
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "pasienlist.php", "", $this->TableVar, TRUE);
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, $url);
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

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

	    //$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($pasien_view)) $pasien_view = new cpasien_view();

// Page init
$pasien_view->Page_Init();

// Page main
$pasien_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pasien_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var pasien_view = new ew_Page("pasien_view");
pasien_view.PageID = "view"; // Page ID
var EW_PAGE_ID = pasien_view.PageID; // For backward compatibility

// Form object
var fpasienview = new ew_Form("fpasienview");

// Form_CustomValidate event
fpasienview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpasienview.ValidateRequired = true;
<?php } else { ?>
fpasienview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php $pasien_view->ExportOptions->Render("body") ?>
<?php
	foreach ($pasien_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $pasien_view->ShowPageHeader(); ?>
<?php
$pasien_view->ShowMessage();
?>
<form name="fpasienview" id="fpasienview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($pasien_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $pasien_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="pasien">
<table class="table table-bordered table-striped ewViewTable">
<?php if ($pasien->kode_pasien->Visible) { // kode_pasien ?>
	<tr id="r_kode_pasien">
		<td><span id="elh_pasien_kode_pasien"><?php echo $pasien->kode_pasien->FldCaption() ?></span></td>
		<td<?php echo $pasien->kode_pasien->CellAttributes() ?>>
<span id="el_pasien_kode_pasien" class="form-group">
<span<?php echo $pasien->kode_pasien->ViewAttributes() ?>>
<?php echo $pasien->kode_pasien->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pasien->nama_pasien->Visible) { // nama_pasien ?>
	<tr id="r_nama_pasien">
		<td><span id="elh_pasien_nama_pasien"><?php echo $pasien->nama_pasien->FldCaption() ?></span></td>
		<td<?php echo $pasien->nama_pasien->CellAttributes() ?>>
<span id="el_pasien_nama_pasien" class="form-group">
<span<?php echo $pasien->nama_pasien->ViewAttributes() ?>>
<?php echo $pasien->nama_pasien->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pasien->alamat_pasien->Visible) { // alamat_pasien ?>
	<tr id="r_alamat_pasien">
		<td><span id="elh_pasien_alamat_pasien"><?php echo $pasien->alamat_pasien->FldCaption() ?></span></td>
		<td<?php echo $pasien->alamat_pasien->CellAttributes() ?>>
<span id="el_pasien_alamat_pasien" class="form-group">
<span<?php echo $pasien->alamat_pasien->ViewAttributes() ?>>
<?php echo $pasien->alamat_pasien->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pasien->jenis_kelamin->Visible) { // jenis_kelamin ?>
	<tr id="r_jenis_kelamin">
		<td><span id="elh_pasien_jenis_kelamin"><?php echo $pasien->jenis_kelamin->FldCaption() ?></span></td>
		<td<?php echo $pasien->jenis_kelamin->CellAttributes() ?>>
<span id="el_pasien_jenis_kelamin" class="form-group">
<span<?php echo $pasien->jenis_kelamin->ViewAttributes() ?>>
<?php echo $pasien->jenis_kelamin->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pasien->tgl_lahir->Visible) { // tgl_lahir ?>
	<tr id="r_tgl_lahir">
		<td><span id="elh_pasien_tgl_lahir"><?php echo $pasien->tgl_lahir->FldCaption() ?></span></td>
		<td<?php echo $pasien->tgl_lahir->CellAttributes() ?>>
<span id="el_pasien_tgl_lahir" class="form-group">
<span<?php echo $pasien->tgl_lahir->ViewAttributes() ?>>
<?php echo $pasien->tgl_lahir->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pasien->kota_pasien->Visible) { // kota_pasien ?>
	<tr id="r_kota_pasien">
		<td><span id="elh_pasien_kota_pasien"><?php echo $pasien->kota_pasien->FldCaption() ?></span></td>
		<td<?php echo $pasien->kota_pasien->CellAttributes() ?>>
<span id="el_pasien_kota_pasien" class="form-group">
<span<?php echo $pasien->kota_pasien->ViewAttributes() ?>>
<?php echo $pasien->kota_pasien->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pasien->tgl_datang->Visible) { // tgl_datang ?>
	<tr id="r_tgl_datang">
		<td><span id="elh_pasien_tgl_datang"><?php echo $pasien->tgl_datang->FldCaption() ?></span></td>
		<td<?php echo $pasien->tgl_datang->CellAttributes() ?>>
<span id="el_pasien_tgl_datang" class="form-group">
<span<?php echo $pasien->tgl_datang->ViewAttributes() ?>>
<?php echo $pasien->tgl_datang->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pasien->tgl_keluar->Visible) { // tgl_keluar ?>
	<tr id="r_tgl_keluar">
		<td><span id="elh_pasien_tgl_keluar"><?php echo $pasien->tgl_keluar->FldCaption() ?></span></td>
		<td<?php echo $pasien->tgl_keluar->CellAttributes() ?>>
<span id="el_pasien_tgl_keluar" class="form-group">
<span<?php echo $pasien->tgl_keluar->ViewAttributes() ?>>
<?php echo $pasien->tgl_keluar->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pasien->kode_dokter->Visible) { // kode_dokter ?>
	<tr id="r_kode_dokter">
		<td><span id="elh_pasien_kode_dokter"><?php echo $pasien->kode_dokter->FldCaption() ?></span></td>
		<td<?php echo $pasien->kode_dokter->CellAttributes() ?>>
<span id="el_pasien_kode_dokter" class="form-group">
<span<?php echo $pasien->kode_dokter->ViewAttributes() ?>>
<?php echo $pasien->kode_dokter->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<script type="text/javascript">
fpasienview.Init();
</script>
<?php
$pasien_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$pasien_view->Page_Terminate();
?>
