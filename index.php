<?php

require_once __DIR__ . '/includes/header.php';
if (!utils::isCron()) {
    header('Location: template.php');
    exit();
}
utils::handleCron();

$utils = new utils();

$table = 'template';
$user_id = $cr_user['id'];

if (isset($_REQUEST['mail']) && !empty($_REQUEST['mail']) && isset($_REQUEST['id']) && !empty($_REQUEST['id'])) {
    $templ_id = trim($_REQUEST['mail']);
    $template = $db_util->SelectSingleRow($table, "user_id = '" . $user_id . "' AND id = '" . $templ_id . "'", "");

    if (!empty($template['id']) && $template['id'] == $templ_id) {
        $infContId = trim($_REQUEST['id']);

        $cstmFields = array();
        for ($index = 1; $index <= 10; $index++) {
            $cstmFields[] = $template['custom' . $index];
        }
        $cstmFields = array_filter($cstmFields);

        $contactArr = $utils->getInfContactsById($infContId, $cstmFields);

        if (count($contactArr)) {
            $contact = current($contactArr);
            if (!empty($contact)) {
                $contact = $contact->toArray();

                require_once __DIR__ . "/includes/docmailHelper.php";

                //------------- Docmail section START ---------------------------
                try {

                    $GLOBALS["client"] = new nusoap_client(WSDL_URL, true);
                    $GLOBALS["client"]->timeout = 240;
                    // Increase php script server timeout
                    set_time_limit(240);
                    error_reporting(E_ALL);

                    $sUsr = DOC_USERNAME;  // docmail username
                    $sPwd = DOC_PASSWORD; // docmail password

                    $sMailingName = $template['name'];   // "Your reference" for this mailing (information)
                    $sCallingApplicationID = $cr_user['doc_appid'];   // could be useful to show your application name in docmail (information)
                    $sTemplateName = $template['document']; // friendly name in docmail for your template file (information)
                    $sTemplateFileName = $template['document']; // file name to be passed to docmail for your template file (information)
                    $TemplateFile = "uploads/" . $template['document'];           // filename (in this case the file is on the root of the webserver!)
                    $bColour = true;                // Print in colour?
                    $bDuplex = false;                // Print on both sides of paper?
                    $eDeliveryType = $template['postage']; // <eDeliveryType>Undefined or FirstClass or StandardClass</eDeliveryType> - to get the BEST benefit use StandardClass 
                    $eAddressNameFormat = "Full Name"; //How the name appears in the envelope address  â€œFull Nameâ€?, â€œFirstname Surnameâ€?, â€œTitle Initial Surnameâ€?,â€œTitle Surnameâ€?, or â€œTitle Firstname Surnameâ€? 
                    $ProductType = $prod_type_arr[$template['type']]; //ProductType (on Mailing): â€œA4Letterâ€?, â€œBusinessCardâ€?, â€œGreetingCardâ€?, or â€œPostcardâ€?
                    $DocumentType = $template['type']; //DocumentType (on Templates - selects the sub-type for a given template): â€œA4Letterâ€?, â€œBusinessCardâ€?,â€œGreetingCardA5â€?, â€œPostcardA5â€?, â€œPostcardA6â€?, â€œPostcardA5Rightâ€? or â€œPostcardA6Rightâ€? 
                    //used in adding an address list file

                    if (in_array($ProductType, array('GreetingCard', 'Postcard')))
                        $bDuplex = TRUE;

                    switch ($DocumentType) {
                        case 'A4LetterDouble':
                            $bDuplex = TRUE;
                            $DocumentType = $ProductType;
                            break;
                        default:
                            break;
                    }

                    $AddressFile = "";           // address CSV file filename (in this case the file is on the root of the webserver!)
                    //used in adding a single address
                    $NameTitle = $contact['Title'];     //recipient title/salutation
                    $FirstName = $contact['FirstName'];    //recipient 1st name
                    $LastName = $contact['LastName'];     //recipient surname
                    $JobTitle = $contact['JobTitle'];         // Address line 1
                    $CompanyName = $contact['Company'];         // Address line 1
                    $sEmail = $contact['Email'];
                    $sTelephone = $contact['Phone1'];
                    $sExtraInfo = "Telephone type: " . $contact['Phone1Type'];
                    $sAddress1 = $contact['StreetAddress1'];         // Address line 1
                    $sAddress2 = $contact['StreetAddress2'];         // Address line 2
                    $sAddress3 = $contact['City'];       // Address line 3
                    $sAddress4 = $contact['State'];          // Address line 4
                    $sAddress5 = $contact['Country'];
                    $sPostCode = $contact['PostalCode'];       // PostCode                    

                    for ($index = 1; $index <= 10; $index++) {
                        ${'sCustom' . $index} = (!empty($template['custom' . $index])) ? $contact[$template['custom' . $index]] : '';
                    }

                    $sMailingDescription = $template['description'];
                    $bIsMono = !$bColour;
                    $sDespatchASAP = true;
                    $sDespatchDate = "";
                    $ProofApprovalRequired = FALSE; //false = Automatically approve the order without returning a proof

                    $EmailOnProcessMailingError = "sandip.work18@gmail.com";  //developer email used as an example
                    $EmailOnProcessMailingSuccess = "sandip.work18@gmail.com";

                    $HttpPostOnProcessMailingError = "";     //URL on your server set up to handle callbacks
                    $HttpPostOnProcessMailingSuccess = "";   //URL on your server set up to handle callbacks

                    $callName = "";

//                print_object_linebreak("...done");
                    //true  = proof approval required.  
                    //	call ProcessMailing  with Submit=0 PartialProcess=1 to approve the proof and submit the mailing
                    //	Poll GetStatus to check that proof is ready (loop)  'Mailing submitted', 'Mailing processed' or 'Partial processing complete' mean the proof is ready
                    //	call GetProofFile to return the proof file data
                    //	call ProcessMailing  with Submit=1 PartialProcess=0 to approve the proof and submit the mailing
//                print_object_linebreak("<br/><br/><h1>Running Docmail API script</h1>");
                    ///////////////////////
                    // CreateMailing  - Setup array to pass into webservice call
                    ///////////////////////

                    $callName = "CreateMailing";
                    // Setup array to pass into webservice call
                    $params = array(
                        "Username" => $sUsr,
                        "Password" => $sPwd,
                        "CustomerApplication" => $sCallingApplicationID,
                        "ProductType" => $ProductType,
                        "MailingName" => $sMailingName,
                        "MailingDescription" => $sMailingDescription,
                        "IsMono" => $bIsMono,
                        "IsDuplex" => $bDuplex,
                        "DeliveryType" => $eDeliveryType,
                        "DespatchASAP" => $sDespatchASAP,
                        //"DespatchDate" => $sDespatchDate,   //only include if delayed despatch is required
                        "AddressNameFormat" => $eAddressNameFormat,
                        "ReturnFormat" => "Text"
                    );
                    // other available params listed here:  https://www.cfhdocmail.com/TestAPI2/DMWS.asmx?op=CreateMailing
                    $result = MakeCall($callName, $params, "");

                    $MailingGUID = GetFld($result, "MailingGUID"); //parse the value  for key 'MailingGUID' from $result
//                print_object_linebreak("Order Ref: " . GetFld($result, "OrderRef"));
                    ///////////////////////
                    // AddTemplateFile  - Read in $TemplateFile file data and Setup array to pass into webservice call
                    ///////////////////////
                    // Load contents of word file into base-64 array to pass across SOAP
                    // for example the word file is at the root of the webserver

                    $TemplateHandle = fopen($TemplateFile, "r");
                    $TempeContents_doc = base64_encode(fread($TemplateHandle, filesize($TemplateFile)));
                    fclose($TemplateHandle);

//	print_object_linebreak(  "<br><b>Preparing file for AddTemplateFile </b>");

                    if ($GLOBALS["debug"]) {
                        print_object_linebreak("Template file size is " . filesize($TemplateFile) . " bytes");
                        if ($GLOBALS["fileContentsDebug"]) {
                            print_object_linebreak("Contents of file:");
                            print_object_linebreak($TempeContents_doc);
                        }
                    }

                    $callName = "AddTemplateFile";
                    $params = array(
                        "Username" => $sUsr,
                        "Password" => $sPwd,
                        "MailingGUID" => $MailingGUID,
                        "DocumentType" => $DocumentType,
                        "TemplateName" => $sTemplateName,
                        "FileName" => $sTemplateFileName,
                        "FileData" => $TempeContents_doc,
                        "AddressedDocument" => true,
                        "Copies" => 1,
                        "ReturnFormat" => "Text"
                    );
                    // other available params listed here:  https://www.cfhdocmail.com/TestAPI2/DMWS.asmx?op=AddTemplateFile

                    $result = MakeCall($callName, $params, "File " . $TemplateFile . " (" . filesize($TemplateFile) . ")");
                    //
                    ///////////////////////
                    //Add Single Address   - use this to add a single address by setting up array to pass into webservice call
                    ///////////////////////

                    $callName = "AddAddress";
                    $params = array(
                        "Username" => $sUsr,
                        "Password" => $sPwd,
                        "MailingGUID" => $MailingGUID,
                        "Title" => $NameTitle,
                        "FirstName" => $FirstName,
                        "Surname" => $LastName,
                        "CompanyName" => $CompanyName,
                        "Email" => $sEmail,
                        "Telephone" => $sTelephone,
                        "ExtraInfo" => $sExtraInfo,
                        "JobTitle" => $JobTitle,
                        "Address1" => $sAddress1,
                        "Address2" => $sAddress2,
                        "Address3" => $sAddress3,
                        "Address4" => $sAddress4,
                        "Address5" => $sAddress5,
                        "Address6" => $sPostCode,
                        "Custom1" => $sCustom1,
                        "Custom2" => $sCustom2,
                        "Custom3" => $sCustom3,
                        "Custom4" => $sCustom4,
                        "Custom5" => $sCustom5,
                        "Custom6" => $sCustom6,
                        "Custom7" => $sCustom7,
                        "Custom8" => $sCustom8,
                        "Custom9" => $sCustom9,
                        "Custom10" => $sCustom10,
                        "ReturnFormat" => "Text"
                    );

                    $result = MakeCall($callName, $params, "for MailingGUID " . $MailingGUID);

                    ///////////////////////
                    // ProcessMailing - Setup array to pass into webservice call
                    ///////////////////////
                    $callName = "ProcessMailing";
                    $params = array(
                        "Username" => $sUsr,
                        "Password" => $sPwd,
                        "MailingGUID" => $MailingGUID,
                        "CustomerApplication" => $sCallingApplicationID,
                        "SkipPreviewImageGeneration" => false,
                        "Submit" => !$ProofApprovalRequired, //auto submit when approval is not requried
                        "PartialProcess" => $ProofApprovalRequired, //fully process when approval is not requried 
                        "Copies" => 1,
                        "ReturnFormat" => "Text",
                        "EmailSuccessList" => $EmailOnProcessMailingSuccess,
                        "EmailErrorList" => $EmailOnProcessMailingError,
                        "HttpPostOnSuccess" => $HttpPostOnProcessMailingSuccess,
                        "HttpPostOnError" => $HttpPostOnProcessMailingError
                    );
                    // there are useful parameters that you may wish to include on this call which enable asynchronous notifications of successes and fails of automated orders to be sent to you via email or HTTP Post:
                    //		EmailSuccessList,EmailErrorList
                    //		HttpPostOnSuccess,HttpPostOnError
                    // other available params listed here:  https://www.cfhdocmail.com/TestAPI2/DMWS.asmx?op=ProcessMailing

                    $result = MakeCall($callName, $params, "");

//	print_object_linebreak( date('r'));
                    print_object_linebreak("<br/><br/><h1><center>FINISHED!</center></h1>");
                } catch (Exception $e) {
                    print_object_linebreak(date('r'));
                    print_object_linebreak("<br/><br/><h1>PROBLEM:</h1>");
                    print_object_linebreak($e->getMessage());
                }
                //display any system errors
//                print_object_linebreak(error_get_last());
                //------------- Docmail section END ---------------------------
            } else {
                die('Sorry! Contact not found.');
            }
        }
    } else {
        die('Sorry! Mail template not found.');
    }
} else {
    die('Sorry! Mail template and Infusion contact id both are required to proceed.');
}
?>    