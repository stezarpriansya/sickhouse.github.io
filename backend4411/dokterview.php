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

$dokter_view = NULL; // Initialize page object first

class cdokter_view extends cdokter {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{E4257960-51C0-4B8A-8F41-FDCC3F20971D}";

	// Table name
	var $TableName = 'dokter';

	// Page object name
	var $PageObjName = 'dokter_view';

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

		// Table object (dokter)
		if (!isset($GLOBALS["dokter"]) || get_class($GLOBALS["dokter"]) == "cdokter") {
			$GLOBALS["dokter"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["dokter"];
		}
		$KeyUrl = "";
		if (@$_GET["kode_dokter"] <> "") {
			$this->RecKey["kode_dokter"] = $_GET["kode_dokter"];
			$KeyUrl .= "&amp;kode_dokter=" . urlencode($this->RecKey["kode_dokter"]);
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
			define("EW_TABLE_NAME", 'dokter', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("dokterlist.php"));
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
			if (@$_GET["kode_dokter"] <> "") {
				$this->kode_dokter->setQueryStringValue($_GET["kode_dokter"]);
				$this->RecKey["kode_dokter"] = $this->kode_dokter->QueryStringValue;
			} else {
				$sReturnUrl = "dokterlist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "dokterlist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "dokterlist.php"; // Not page request, return to list
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
		$Breadcrumb->Add("list", $this->TableVar, "dokterlist.php", "", $this->TableVar, TRUE);
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
if (!isset($dokter_view)) $dokter_view = new cdokter_view();

// Page init
$dokter_view->Page_Init();

// Page main
$dokter_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$dokter_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var dokter_view = new ew_Page("dokter_view");
dokter_view.PageID = "view"; // Page ID
var EW_PAGE_ID = dokter_view.PageID; // For backward compatibility

// Form object
var fdokterview = new ew_Form("fdokterview");

// Form_CustomValidate event
fdokterview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdokterview.ValidateRequired = true;
<?php } else { ?>
fdokterview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php $dokter_view->ExportOptions->Render("body") ?>
<?php
	foreach ($dokter_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $dokter_view->ShowPageHeader(); ?>
<?php
$dokter_view->ShowMessage();
?>
<form name="fdokterview" id="fdokterview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($dokter_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $dokter_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="dokter">
<table class="table table-bordered table-striped ewViewTable">
<?php if ($dokter->kode_dokter->Visible) { // kode_dokter ?>
	<tr id="r_kode_dokter">
		<td><span id="elh_dokter_kode_dokter"><?php echo $dokter->kode_dokter->FldCaption() ?></span></td>
		<td<?php echo $dokter->kode_dokter->CellAttributes() ?>>
<span id="el_dokter_kode_dokter" class="form-group">
<span<?php echo $dokter->kode_dokter->ViewAttributes() ?>>
<?php echo $dokter->kode_dokter->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($dokter->nama_dokter->Visible) { // nama_dokter ?>
	<tr id="r_nama_dokter">
		<td><span id="elh_dokter_nama_dokter"><?php echo $dokter->nama_dokter->FldCaption() ?></span></td>
		<td<?php echo $dokter->nama_dokter->CellAttributes() ?>>
<span id="el_dokter_nama_dokter" class="form-group">
<span<?php echo $dokter->nama_dokter->ViewAttributes() ?>>
<?php echo $dokter->nama_dokter->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($dokter->jenis_kelamin->Visible) { // jenis_kelamin ?>
	<tr id="r_jenis_kelamin">
		<td><span id="elh_dokter_jenis_kelamin"><?php echo $dokter->jenis_kelamin->FldCaption() ?></span></td>
		<td<?php echo $dokter->jenis_kelamin->CellAttributes() ?>>
<span id="el_dokter_jenis_kelamin" class="form-group">
<span<?php echo $dokter->jenis_kelamin->ViewAttributes() ?>>
<?php echo $dokter->jenis_kelamin->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($dokter->tgl_lahir->Visible) { // tgl_lahir ?>
	<tr id="r_tgl_lahir">
		<td><span id="elh_dokter_tgl_lahir"><?php echo $dokter->tgl_lahir->FldCaption() ?></span></td>
		<td<?php echo $dokter->tgl_lahir->CellAttributes() ?>>
<span id="el_dokter_tgl_lahir" class="form-group">
<span<?php echo $dokter->tgl_lahir->ViewAttributes() ?>>
<?php echo $dokter->tgl_lahir->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($dokter->foto_dokter->Visible) { // foto_dokter ?>
	<tr id="r_foto_dokter">
		<td><span id="elh_dokter_foto_dokter"><?php echo $dokter->foto_dokter->FldCaption() ?></span></td>
		<td<?php echo $dokter->foto_dokter->CellAttributes() ?>>
<span id="el_dokter_foto_dokter" class="form-group">
<span<?php echo $dokter->foto_dokter->ViewAttributes() ?>>
<?php echo $dokter->foto_dokter->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($dokter->spesialisasi->Visible) { // spesialisasi ?>
	<tr id="r_spesialisasi">
		<td><span id="elh_dokter_spesialisasi"><?php echo $dokter->spesialisasi->FldCaption() ?></span></td>
		<td<?php echo $dokter->spesialisasi->CellAttributes() ?>>
<span id="el_dokter_spesialisasi" class="form-group">
<span<?php echo $dokter->spesialisasi->ViewAttributes() ?>>
<?php echo $dokter->spesialisasi->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($dokter->alamat_dokter->Visible) { // alamat_dokter ?>
	<tr id="r_alamat_dokter">
		<td><span id="elh_dokter_alamat_dokter"><?php echo $dokter->alamat_dokter->FldCaption() ?></span></td>
		<td<?php echo $dokter->alamat_dokter->CellAttributes() ?>>
<span id="el_dokter_alamat_dokter" class="form-group">
<span<?php echo $dokter->alamat_dokter->ViewAttributes() ?>>
<?php echo $dokter->alamat_dokter->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($dokter->kota_dokter->Visible) { // kota_dokter ?>
	<tr id="r_kota_dokter">
		<td><span id="elh_dokter_kota_dokter"><?php echo $dokter->kota_dokter->FldCaption() ?></span></td>
		<td<?php echo $dokter->kota_dokter->CellAttributes() ?>>
<span id="el_dokter_kota_dokter" class="form-group">
<span<?php echo $dokter->kota_dokter->ViewAttributes() ?>>
<?php echo $dokter->kota_dokter->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($dokter->telepon->Visible) { // telepon ?>
	<tr id="r_telepon">
		<td><span id="elh_dokter_telepon"><?php echo $dokter->telepon->FldCaption() ?></span></td>
		<td<?php echo $dokter->telepon->CellAttributes() ?>>
<span id="el_dokter_telepon" class="form-group">
<span<?php echo $dokter->telepon->ViewAttributes() ?>>
<?php echo $dokter->telepon->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($dokter->SIP->Visible) { // SIP ?>
	<tr id="r_SIP">
		<td><span id="elh_dokter_SIP"><?php echo $dokter->SIP->FldCaption() ?></span></td>
		<td<?php echo $dokter->SIP->CellAttributes() ?>>
<span id="el_dokter_SIP" class="form-group">
<span<?php echo $dokter->SIP->ViewAttributes() ?>>
<?php echo $dokter->SIP->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($dokter->user_id->Visible) { // user_id ?>
	<tr id="r_user_id">
		<td><span id="elh_dokter_user_id"><?php echo $dokter->user_id->FldCaption() ?></span></td>
		<td<?php echo $dokter->user_id->CellAttributes() ?>>
<span id="el_dokter_user_id" class="form-group">
<span<?php echo $dokter->user_id->ViewAttributes() ?>>
<?php echo $dokter->user_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<script type="text/javascript">
fdokterview.Init();
</script>
<?php
$dokter_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$dokter_view->Page_Terminate();
?>
