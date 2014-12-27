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

$tim_dokter_view = NULL; // Initialize page object first

class ctim_dokter_view extends ctim_dokter {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{E4257960-51C0-4B8A-8F41-FDCC3F20971D}";

	// Table name
	var $TableName = 'tim_dokter';

	// Page object name
	var $PageObjName = 'tim_dokter_view';

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

		// Table object (tim_dokter)
		if (!isset($GLOBALS["tim_dokter"]) || get_class($GLOBALS["tim_dokter"]) == "ctim_dokter") {
			$GLOBALS["tim_dokter"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tim_dokter"];
		}
		$KeyUrl = "";
		if (@$_GET["id_tim"] <> "") {
			$this->RecKey["id_tim"] = $_GET["id_tim"];
			$KeyUrl .= "&amp;id_tim=" . urlencode($this->RecKey["id_tim"]);
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
			define("EW_TABLE_NAME", 'tim_dokter', TRUE);

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
			if (@$_GET["id_tim"] <> "") {
				$this->id_tim->setQueryStringValue($_GET["id_tim"]);
				$this->RecKey["id_tim"] = $this->id_tim->QueryStringValue;
			} else {
				$sReturnUrl = "tim_dokterlist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "tim_dokterlist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "tim_dokterlist.php"; // Not page request, return to list
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
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

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
		$Breadcrumb->Add("list", $this->TableVar, "tim_dokterlist.php", "", $this->TableVar, TRUE);
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
if (!isset($tim_dokter_view)) $tim_dokter_view = new ctim_dokter_view();

// Page init
$tim_dokter_view->Page_Init();

// Page main
$tim_dokter_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tim_dokter_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var tim_dokter_view = new ew_Page("tim_dokter_view");
tim_dokter_view.PageID = "view"; // Page ID
var EW_PAGE_ID = tim_dokter_view.PageID; // For backward compatibility

// Form object
var ftim_dokterview = new ew_Form("ftim_dokterview");

// Form_CustomValidate event
ftim_dokterview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftim_dokterview.ValidateRequired = true;
<?php } else { ?>
ftim_dokterview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php $tim_dokter_view->ExportOptions->Render("body") ?>
<?php
	foreach ($tim_dokter_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $tim_dokter_view->ShowPageHeader(); ?>
<?php
$tim_dokter_view->ShowMessage();
?>
<form name="ftim_dokterview" id="ftim_dokterview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($tim_dokter_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $tim_dokter_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="tim_dokter">
<table class="table table-bordered table-striped ewViewTable">
<?php if ($tim_dokter->id_tim->Visible) { // id_tim ?>
	<tr id="r_id_tim">
		<td><span id="elh_tim_dokter_id_tim"><?php echo $tim_dokter->id_tim->FldCaption() ?></span></td>
		<td<?php echo $tim_dokter->id_tim->CellAttributes() ?>>
<span id="el_tim_dokter_id_tim" class="form-group">
<span<?php echo $tim_dokter->id_tim->ViewAttributes() ?>>
<?php echo $tim_dokter->id_tim->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tim_dokter->dokter1->Visible) { // dokter1 ?>
	<tr id="r_dokter1">
		<td><span id="elh_tim_dokter_dokter1"><?php echo $tim_dokter->dokter1->FldCaption() ?></span></td>
		<td<?php echo $tim_dokter->dokter1->CellAttributes() ?>>
<span id="el_tim_dokter_dokter1" class="form-group">
<span<?php echo $tim_dokter->dokter1->ViewAttributes() ?>>
<?php echo $tim_dokter->dokter1->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tim_dokter->peran1->Visible) { // peran1 ?>
	<tr id="r_peran1">
		<td><span id="elh_tim_dokter_peran1"><?php echo $tim_dokter->peran1->FldCaption() ?></span></td>
		<td<?php echo $tim_dokter->peran1->CellAttributes() ?>>
<span id="el_tim_dokter_peran1" class="form-group">
<span<?php echo $tim_dokter->peran1->ViewAttributes() ?>>
<?php echo $tim_dokter->peran1->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tim_dokter->dokter2->Visible) { // dokter2 ?>
	<tr id="r_dokter2">
		<td><span id="elh_tim_dokter_dokter2"><?php echo $tim_dokter->dokter2->FldCaption() ?></span></td>
		<td<?php echo $tim_dokter->dokter2->CellAttributes() ?>>
<span id="el_tim_dokter_dokter2" class="form-group">
<span<?php echo $tim_dokter->dokter2->ViewAttributes() ?>>
<?php echo $tim_dokter->dokter2->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tim_dokter->peran2->Visible) { // peran2 ?>
	<tr id="r_peran2">
		<td><span id="elh_tim_dokter_peran2"><?php echo $tim_dokter->peran2->FldCaption() ?></span></td>
		<td<?php echo $tim_dokter->peran2->CellAttributes() ?>>
<span id="el_tim_dokter_peran2" class="form-group">
<span<?php echo $tim_dokter->peran2->ViewAttributes() ?>>
<?php echo $tim_dokter->peran2->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tim_dokter->dokter3->Visible) { // dokter3 ?>
	<tr id="r_dokter3">
		<td><span id="elh_tim_dokter_dokter3"><?php echo $tim_dokter->dokter3->FldCaption() ?></span></td>
		<td<?php echo $tim_dokter->dokter3->CellAttributes() ?>>
<span id="el_tim_dokter_dokter3" class="form-group">
<span<?php echo $tim_dokter->dokter3->ViewAttributes() ?>>
<?php echo $tim_dokter->dokter3->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tim_dokter->peran3->Visible) { // peran3 ?>
	<tr id="r_peran3">
		<td><span id="elh_tim_dokter_peran3"><?php echo $tim_dokter->peran3->FldCaption() ?></span></td>
		<td<?php echo $tim_dokter->peran3->CellAttributes() ?>>
<span id="el_tim_dokter_peran3" class="form-group">
<span<?php echo $tim_dokter->peran3->ViewAttributes() ?>>
<?php echo $tim_dokter->peran3->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tim_dokter->dokter4->Visible) { // dokter4 ?>
	<tr id="r_dokter4">
		<td><span id="elh_tim_dokter_dokter4"><?php echo $tim_dokter->dokter4->FldCaption() ?></span></td>
		<td<?php echo $tim_dokter->dokter4->CellAttributes() ?>>
<span id="el_tim_dokter_dokter4" class="form-group">
<span<?php echo $tim_dokter->dokter4->ViewAttributes() ?>>
<?php echo $tim_dokter->dokter4->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tim_dokter->peran4->Visible) { // peran4 ?>
	<tr id="r_peran4">
		<td><span id="elh_tim_dokter_peran4"><?php echo $tim_dokter->peran4->FldCaption() ?></span></td>
		<td<?php echo $tim_dokter->peran4->CellAttributes() ?>>
<span id="el_tim_dokter_peran4" class="form-group">
<span<?php echo $tim_dokter->peran4->ViewAttributes() ?>>
<?php echo $tim_dokter->peran4->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tim_dokter->dokter5->Visible) { // dokter5 ?>
	<tr id="r_dokter5">
		<td><span id="elh_tim_dokter_dokter5"><?php echo $tim_dokter->dokter5->FldCaption() ?></span></td>
		<td<?php echo $tim_dokter->dokter5->CellAttributes() ?>>
<span id="el_tim_dokter_dokter5" class="form-group">
<span<?php echo $tim_dokter->dokter5->ViewAttributes() ?>>
<?php echo $tim_dokter->dokter5->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tim_dokter->peran5->Visible) { // peran5 ?>
	<tr id="r_peran5">
		<td><span id="elh_tim_dokter_peran5"><?php echo $tim_dokter->peran5->FldCaption() ?></span></td>
		<td<?php echo $tim_dokter->peran5->CellAttributes() ?>>
<span id="el_tim_dokter_peran5" class="form-group">
<span<?php echo $tim_dokter->peran5->ViewAttributes() ?>>
<?php echo $tim_dokter->peran5->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tim_dokter->dokter6->Visible) { // dokter6 ?>
	<tr id="r_dokter6">
		<td><span id="elh_tim_dokter_dokter6"><?php echo $tim_dokter->dokter6->FldCaption() ?></span></td>
		<td<?php echo $tim_dokter->dokter6->CellAttributes() ?>>
<span id="el_tim_dokter_dokter6" class="form-group">
<span<?php echo $tim_dokter->dokter6->ViewAttributes() ?>>
<?php echo $tim_dokter->dokter6->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tim_dokter->peran6->Visible) { // peran6 ?>
	<tr id="r_peran6">
		<td><span id="elh_tim_dokter_peran6"><?php echo $tim_dokter->peran6->FldCaption() ?></span></td>
		<td<?php echo $tim_dokter->peran6->CellAttributes() ?>>
<span id="el_tim_dokter_peran6" class="form-group">
<span<?php echo $tim_dokter->peran6->ViewAttributes() ?>>
<?php echo $tim_dokter->peran6->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<script type="text/javascript">
ftim_dokterview.Init();
</script>
<?php
$tim_dokter_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tim_dokter_view->Page_Terminate();
?>
