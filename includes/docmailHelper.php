<?php

require_once __DIR__ . "/../vendor/nusoap/lib/nusoap.php";

// Flag for outputting debug print messages	
$GLOBALS["debug"] = false;
$GLOBALS["fileContentsDebug"] = false;
$GLOBALS["callParamArrayDebug"] = false;
$GLOBALS["resultArrayDebug"] = false;

//construct standard error handler for backwards compatibility
if (!function_exists('error_get_last')) {
    set_error_handler(
            create_function(
                    '$errno,$errstr,$errfile,$errline,$errcontext', '
					global $__error_get_last_retval__;
					$__error_get_last_retval__ = array(
						\'type\'        => $errno,
						\'message\'        => $errstr,
						\'file\'        => $errfile,
						\'line\'        => $errline
					);
					return false;
				'
            )
    );

    function error_get_last() {
        global $__error_get_last_retval__;
        if (!isset($__error_get_last_retval__)) {
            return null;
        }
        return $__error_get_last_retval__;
    }

}

function MakeCall($callName, $params, $extraInfo) {

    $callResult = "";
    if ($GLOBALS["debug"])
        print_object_linebreak("<br/><b>About to call " . $callName . " " . $extraInfo . "</b>");
    if ($GLOBALS["callParamArrayDebug"])
        print_object_linebreak($params);
    $callResult = $GLOBALS["client"]->call($callName, $params);

    if ($GLOBALS["resultArrayDebug"]) {
        print $callName . " RESULT WAS: ";
        print_object_linebreak($callResult);
    };
    if ($GLOBALS["debug"]) {
        print_object_linebreak("<b>Result " . $callName . ": </b>");
        print_object_linebreak($callResult[$callName . "Result"]);

        CheckError($callResult[$callName . "Result"]);   //parse & check error fields from result as described above
    }
    flush();

    return $callResult[$callName . "Result"];
}

function GetStatus($sUsr, $sPwd, $MailingGUID) {

    ///////////////////////
    // GetStatus - Setup array to pass into webservice call
    ///////////////////////
    // other available params listed here:  (https://www.cfhdocmail.com/TestAPI2/DMWS.asmx?op=GetStatus) returns the status of a mailing from the mailing guid
    $callResult = "";
    $callName = "GetStatus";
    $params = array(
        "Username" => $sUsr,
        "Password" => $sPwd,
        "MailingGUID" => $MailingGUID,
        "ReturnFormat" => "Text"
    );
    print_object_linebreak($MailingGUID);
    // other available params listed here:  (https://www.cfhdocmail.com/TestAPI2/DMWS.asmx?op=GetStatus) returns the status of a mailing from the mailing guid
    $callResult = MakeCall($callName, $params, "for MailingGUID " . $MailingGUID);

    $Status = GetFld($callResult, "Status");
    print_object_linebreak("Status = " . $Status);

    return $callResult;
}

function WaitForProcessMailingStatus($sUsr, $sPwd, $MailingGUID, $ExpectedStatus, $ExceptionOnFail) {

    //poll GetStatus in a loop until the processing has completed
    //loop a maximum of 10 times, with a 10 second delay between iterations.
    //	alternatively; handle callbacks from the HttpPostOnSuccess & HttpPostOnError parameters on ProcessMailing to identify when the processing has completed
    $i = 0;
    do {
        // other available params listed here:  (https://www.cfhdocmail.com/TestAPI2/DMWS.asmx?op=GetStatus) returns the status of a mailing from the mailing guid
        $result = GetStatus($sUsr, $sPwd, $MailingGUID);

        $Status = GetFld($result, "Status");
        $Error = GetFld($result, "Error code");
        //end loop once processing is complete
        if ($Status == $ExpectedStatus) {
            break;
        } //success
        if ($Status == "Error in processing") {
            break;
        } //error in processing
        if ($Error) {
            break;
        }   //error			

        sleep(10); //wait 10 seconds before repeating	
        ++$i;
    } while ($i < 10);

    //
    if ($Status == "Error in processing") {
        //get description of error in processing
        $params = array(
            "Username" => $sUsr,
            "Password" => $sPwd,
            "MethodName" => "GetProcessingError",
            "ReturnFormat" => "Text",
            "Properties" => array(
                "PropertyName" => "GetProcessingError",
                "PropertyValue" => $MailingGUID
            )
        );
        $result = MakeCall("ExtendedCall", $params, ": GetProcessingError");
        print_object_linebreak($result);
    }


    //TODO:	handle the status not being reached appropriately for your system
    if ($Status != $ExpectedStatus) {
        if ($ExceptionOnFail) {
            throw new Exception("<h2>There was an error:</h2> expected status '" . $ExpectedStatus . "' not reached.  Current status: '" . $Status . "'<br/>");
        } else {
            print_object_linebreak("WARNING: expected status '" . $ExpectedStatus . "' not reached.  Current status: '" . $Status . "'");
        }
    }

    flush();
}

function CheckError($Res) {
    if ($Res == null)
        return;
    //make sure the array is read fromt he beginning
    if (is_array($Res))
        reset($Res);
    //check for  the keys 'Error code', 'Error code string' and 'Error message' to test/report errors
    $errCode = GetFld($Res, "Error code");
    if ($errCode) {
        $errName = GetFld($Res, "Error code string");
        $errMsg = GetFld($Res, "Error message");
        print_object_linebreak('ErrCode ' . $errCode);
        print_object_linebreak('ErrName ' . $errName);
        print_object_linebreak('ErrMsg ' . $errMsg);
        throw new Exception("<h2>There was an error:</h2> " . $errCode . " " . $errName . " - " . $errMsg);
    } else {
        print_object_linebreak("Success!");
    }
    if (is_array($Res))
        reset($Res);
    flush();
}

//uses print_r to output the object contents, AND resets the object pointer back to the beginning
function print_object($obj) {
    if (is_array($obj))
        reset($obj);
    print_r($obj);
    if (is_array($obj))
        reset($obj);
    flush();
}

function print_object_linebreak($obj) {
    print_object($obj);
    print_object("<br/>");
}

function GetFld($FldList, $FldName) {
    // calls return a multi-line string structured as :
    // [KEY]: [VALUE][carriage return][line feed][KEY]: [VALUE][carriage return][line feed][KEY]: [VALUE][carriage return][line feed][KEY]: [VALUE]
    //explode lines
    //print "Looking for Field '".$FldName."'<br>";
    $lines = explode("\n", $FldList);
    for ($lineCounter = 0; $lineCounter < count($lines); $lineCounter+=1) {
        //explode field/value
        $fields = explode(":", $lines[$lineCounter]);
        //find matching field name
        if ($fields[0] == $FldName) {
            //print "'".$FldName."' Value: ".ltrim($fields[1], " ")."<br>";
            return ltrim($fields[1], " "); //return value
        }
    }
    //print_object_linebreak( "'".$FldName."' NOT found");
}
