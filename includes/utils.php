<?php

class utils {

// For Upload (Insert) images....
    function ImageUpload($userfile1, $path, $type) {
        $Imagename = "";
        if (@$_FILES[$userfile1]["name"]) {
            $mMainPhoto = $_FILES[$userfile1]["name"];
            $mtmpRoot = $_FILES[$userfile1]["tmp_name"];
            if ($type == "img") {
                $allowed_ext = "jpg, gif, png, JPG, GIF, PNG";
            }

            if ($type == "text") {
                $allowed_ext = "txt, doc, docx, pdf";
            }
            $ok = "0";
            $extension = pathinfo($_FILES[$userfile1]['name']);
            $extension = $extension['extension'];
            $allowed_paths = explode(", ", $allowed_ext);
            for ($i = 0; $i < count($allowed_paths); $i++) {
                if ($allowed_paths[$i] == $extension) {
                    @$ok = "1";
                }
            } // end of for
            if (@$ok == "1") {
                $mOriginalPhoto = $mMainPhoto;
                $mExt = strrchr($mMainPhoto, ".");
                $mPhotoName = substr($mOriginalPhoto, 0, strrpos($mMainPhoto, $mExt));
                $mPhotoName = strtolower($mPhotoName);
                $today = getdate();
                $mKey = $mPhotoName . $today['mday'] . $today['mon'] . $today['year'] . $today['hours'] . $today['minutes'] . $today['seconds'];
                $fname = $mKey . $mExt;
                $mRoot = $path . $fname;
                if (move_uploaded_file($_FILES[$userfile1]['tmp_name'], $mRoot)) {
                    $Imagename = $fname;
                    return $Imagename;
                }
            }
        }
    }

// For update images....
    function UpdateImage($userfile1, $path, $oldimage, $type) {
// image upload start
        $Imagename = "";
        if (@$_FILES[$userfile1]["name"]) {
            $mMainPhoto = $_FILES[$userfile1]["name"];
            $mtmpRoot = $_FILES[$userfile1]["tmp_name"];
            if ($type == "img") {
                $allowed_ext = "jpg, gif, png, JPG, GIF, PNG";
            }
            if ($type == "text") {
                $allowed_ext = "txt, doc, docx, pdf";
            }

            $ok = "0";
            $extension = pathinfo($_FILES[$userfile1]['name']);
            $extension = $extension['extension'];
            $allowed_paths = explode(", ", $allowed_ext);
            for ($i = 0; $i < count($allowed_paths); $i++) {
                if ($allowed_paths[$i] == $extension) {
                    @$ok = "1";
                }
            } // end of for

            if (@$ok == "1") {
                $mOriginalPhoto = $mMainPhoto;
                $mExt = strrchr($mMainPhoto, ".");
                $mPhotoName = substr($mOriginalPhoto, 0, strrpos($mMainPhoto, $mExt));
                $mPhotoName = strtolower($mPhotoName);
                $today = getdate();
                $mKey = $mPhotoName . $today['mday'] . $today['mon'] . $today['year'] . $today['hours'] . $today['minutes'] . $today['seconds'];
                $fname1 = $mKey . $mExt;
                $mRoot = $path . $fname1;
                if (move_uploaded_file($_FILES[$userfile1]['tmp_name'], $mRoot)) {
                    $Imagename = $fname1;
                    if ($oldimage != "") {
                        @unlink($path . $oldimage);
                    }
                    return $Imagename;
                } else {
                    $Imagename = $oldimage;
                    return $Imagename;
                }
            } else {
                $Imagename = $oldimage;
                return $Imagename;
            }
        } else {
            $Imagename = $oldimage;
            return $Imagename;
        }
//image upload end
    }

    function ImageUploadNew($userfile1, $path, $type, $size) {
        $Imagename = "";
        if (@$_FILES[$userfile1]["name"]) {
            $mMainPhoto = $_FILES[$userfile1]["name"];
            $mtmpRoot = $_FILES[$userfile1]["tmp_name"];
            $file_type = $_FILES[$userfile1]["type"];
            if ($type == "img") {
                $allowed_ext = "jpg, JPEG, jpeg, gif, png, JPG, GIF, PNG";
            }

            if ($type == "text") {
                $allowed_ext = "txt, doc, docx,pdf";
            }
            $ok = "0";
            $extension = pathinfo($_FILES[$userfile1]['name']);
            $extension = $extension['extension'];
            $allowed_paths = explode(", ", $allowed_ext);
            for ($i = 0; $i < count($allowed_paths); $i++) {
                if ($allowed_paths[$i] == $extension) {
                    @$ok = "1";
                }
            } // end of for
            if (@$ok == "1") {
                $mOriginalPhoto = $mMainPhoto;
                $mExt = strrchr($mMainPhoto, ".");
                $mPhotoName = substr($mOriginalPhoto, 0, strrpos($mMainPhoto, $mExt));
                $mPhotoName = strtolower($mPhotoName);
                $today = getdate();
                $mKey = $mPhotoName . $today['mday'] . $today['mon'] . $today['year'] . $today['hours'] . $today['minutes'] . $today['seconds'];
                $fname = $mKey . $mExt;
//$mRoot = $path.$fname;
                if ($file_type == "image/pjpeg" || $file_type == "image/jpeg") {
                    $new_img = imagecreatefromjpeg($mtmpRoot);
                } elseif ($file_type == "image/x-png" || $file_type == "image/png") {
                    $new_img = imagecreatefrompng($mtmpRoot);
                } elseif ($file_type == "image/gif") {
                    $new_img = imagecreatefromgif($mtmpRoot);
                }


                list($width, $height) = getimagesize($mtmpRoot);
                if ($height <= $size && $width <= $size) {
                    $new_width = $width;
                    $new_height = $height;
                } else {
                    if ($height > $width) {
                        $new_height = $size;
                        $new_width = ceil(($width * $size) / $height);
                    } else {
                        $new_width = $size;
                        $new_height = ceil(($height * $size) / $width);
                    }
                }
                $img = imagecreatetruecolor($new_width, $new_height);
                imagecopyresampled($img, $new_img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);


//echo  $img;
                ImageJpeg($img, $path . $fname);
                $Imagename = $fname;
                return $Imagename;
            }
        }
    }

// For update images....
    function UpdateImageNew($userfile1, $path, $oldimage, $type, $size) {
// image upload start
        $Imagename = "";
        if (@$_FILES[$userfile1]["name"]) {
            $mMainPhoto = $_FILES[$userfile1]["name"];
            $mtmpRoot = $_FILES[$userfile1]["tmp_name"];
            $file_type = $_FILES[$userfile1]["type"];
            if ($type == "img") {
                $allowed_ext = "jpg, JPEG, jpeg, gif, png, JPG, GIF, PNG";
            }
            if ($type == "text") {
                $allowed_ext = "txt, doc, docx, pdf";
            }

            $ok = "0";
            $extension = pathinfo($_FILES[$userfile1]['name']);
            $extension = $extension['extension'];
            $allowed_paths = explode(", ", $allowed_ext);
            for ($i = 0; $i < count($allowed_paths); $i++) {
                if ($allowed_paths[$i] == $extension) {
                    @$ok = "1";
                }
            } // end of for

            if (@$ok == "1") {
                $mOriginalPhoto = $mMainPhoto;
                $mExt = strrchr($mMainPhoto, ".");
                $mPhotoName = substr($mOriginalPhoto, 0, strrpos($mMainPhoto, $mExt));
                $mPhotoName = strtolower($mPhotoName);
                $today = getdate();
                $mKey = $mPhotoName . $today['mday'] . $today['mon'] . $today['year'] . $today['hours'] . $today['minutes'] . $today['seconds'];
                $fname1 = $mKey . $mExt;
                $mRoot = $path . $fname1;
                if ($file_type == "image/pjpeg" || $file_type == "image/jpeg") {
                    $new_img = imagecreatefromjpeg($mtmpRoot);
                } elseif ($file_type == "image/x-png" || $file_type == "image/png") {
                    $new_img = imagecreatefrompng($mtmpRoot);
                } elseif ($file_type == "image/gif") {
                    $new_img = imagecreatefromgif($mtmpRoot);
                }


                list($width, $height) = getimagesize($mtmpRoot);
                if ($height <= $size && $width <= $size) {
                    $new_height = $height;
                    $new_width = $width;
                } else {
                    if ($height > $width) {
                        $new_height = $size;
                        $new_width = ceil(($width * $size) / $height);
                    } else {
                        $new_width = $size;
                        $new_height = ceil(($height * $size) / $width);
                    }
                }$img = imagecreatetruecolor($new_width, $new_height);
                imagecopyresampled($img, $new_img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);


                ImageJpeg($img, $path . $fname1);
                $Imagename = $fname1;
                if ($oldimage != "") {
                    @unlink($path . $oldimage);
                }

                return $Imagename;
            } else {
                $Imagename = $oldimage;
                return $Imagename;
            }
        } else {
            $Imagename = $oldimage;
            return $Imagename;
        }
//image upload end
    }

// For update images....
    function UpdateImageNew_Sp($userfile1, $path, $oldimage, $type, $size, $iname, $extn = "jpg") {
// image upload start
        $Imagename = "";
        if (@$_FILES[$userfile1]["name"]) {
            $mMainPhoto = $_FILES[$userfile1]["name"];
            $mtmpRoot = $_FILES[$userfile1]["tmp_name"];
            $file_type = $_FILES[$userfile1]["type"];
            if ($type == "img") {
                $allowed_ext = $extn;
            }
            if ($type == "text") {
                $allowed_ext = "txt, doc, docx, pdf";
            }

            $ok = "0";
            $extension = pathinfo($_FILES[$userfile1]['name']);
            $extension = $extension['extension'];
            $allowed_paths = explode(", ", $allowed_ext);
            for ($i = 0; $i < count($allowed_paths); $i++) {
                if ($allowed_paths[$i] == $extension) {
                    @$ok = "1";
                }
            } // end of for

            if (@$ok == "1") {
                $mOriginalPhoto = $mMainPhoto;
                $mExt = strrchr($mMainPhoto, ".");
                $mPhotoName = substr($mOriginalPhoto, 0, strrpos($mMainPhoto, $mExt));
                $mPhotoName = strtolower($mPhotoName);
                $today = getdate();
                $mKey = $iname;
                $fname1 = $mKey . $mExt;
                $mRoot = $path . $fname1;
                if ($file_type == "image/pjpeg" || $file_type == "image/jpeg") {
                    $new_img = imagecreatefromjpeg($mtmpRoot);
                } elseif ($file_type == "image/x-png" || $file_type == "image/png") {
                    $new_img = imagecreatefrompng($mtmpRoot);
                } elseif ($file_type == "image/gif") {
                    $new_img = imagecreatefromgif($mtmpRoot);
                }


                list($width, $height) = getimagesize($mtmpRoot);
                if ($height <= $size && $width <= $size) {
                    $new_height = $height;
                    $new_width = $width;
                } else {
                    if ($height > $width) {
                        $new_height = $size;
                        $new_width = ceil(($width * $size) / $height);
                    } else {
                        $new_width = $size;
                        $new_height = ceil(($height * $size) / $width);
                    }
                }$img = imagecreatetruecolor($new_width, $new_height);
                imagecopyresampled($img, $new_img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);


                ImageJpeg($img, $path . $fname1);
                $Imagename = $fname1;

                return $Imagename;
            } else {
                $Imagename = $oldimage;
                return $Imagename;
            }
        } else {
            $Imagename = $oldimage;
            return $Imagename;
        }
//image upload end
    }

//read form
    function readform() {
        global $_POST;
        global $_GET;
        if (isset($_POST)) {
            foreach ($_POST as $key => $value) {
                if (gettype($value) == "array") {
                    foreach ($value as $v) {
                        $formarray[] = trim($v);
                    }
                    $form[$key] = $formarray;
                    $formarray = "";
                } else {
                    $form[$key] = trim($value);
                }
            }
        }
        if (isset($_GET)) {
            foreach ($_GET as $key => $value) {
                if (gettype($value) == "array") {
                    foreach ($value as $v) {
                        $formarray[] = trim($v);
                    }
                    $form[$key] = $formarray;
                    $formarray = "";
                } else {
                    $form[$key] = trim($value);
                }
            }
        }
        return $form;
    }

    function formatcategoryurl($name, $id) {
//$catoutput  = formaturl($name)."/".$id.".htm";
//echo $catoutput;
//if ($id=="1"){return "birds-animals.htm";}
//if ($id=="2"){return "equestrian.htm";}
//if ($id=="3"){return "commissions.htm";}

        return "product.php?catid=" . $id;
    }

    function formatproducturl($cname, $cid, $pname, $id) {
//$prodoutput  = formaturl($cname)."/".formaturl($pname)."/".$id.".htm";
//echo $prodoutput;
        return "productdetails.php?catid=" . $cid . "&pid=" . $id;
    }

    function simplereplace($str) {
        return str_replace("'", "", $str);
    }

    function strbrhtml($str) {
        $StringStr = preg_replace("/[^a-zA-Z0-9_ -]/s", "", strip_tags($str));
        return $StringStr;
    }

    function strbrhtmldescr($str) {
        $StringStr = preg_replace("/[\n\r][\n\r]/", "<br />", $str);
        $Stringdec = str_replace("'", "&prime;", $StringStr);

        echo $Stringdec;
    }

    function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") {
        $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

        switch ($theType) {
            case "text":
                $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                break;
            case "long":
            case "int":
                $theValue = ($theValue != "") ? intval($theValue) : "NULL";
                break;
            case "double":
                $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
                break;
            case "date":
                $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                break;
            case "defined":
                $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
                break;
        }
        return $theValue;
    }

    function imageresizedyn($src, $max_width, $max_height) {
        $size = getimagesize($src);
        $width = $size[0];
        $height = $size[1];

        $x_ratio = $max_width / $width;
        $y_ratio = $max_height / $height;

        if (($width <= $max_width) && ($height <= $max_height)) {
            $tn_width = $width;
            $tn_height = $height;
        } else if (($x_ratio * $height) < $max_height) {
            $tn_height = ceil($x_ratio * $height);
            $tn_width = $max_width;
        } else {
            $tn_width = ceil($y_ratio * $width);
            $tn_height = $max_height;
        }

        $ret = array($tn_width, $tn_height);
        return $ret;
    }

    function imageresize($src, $max_width, $max_height) {
        $size = getimagesize($src);
        $width = $size[0];
        $height = $size[1];

        $x_ratio = $max_width / $width;
        $y_ratio = $max_height / $height;

        if (($width <= $max_width) && ($height <= $max_height)) {
            $tn_width = $width;
            $tn_height = $height;
        } else if (($x_ratio * $height) < $max_height) {
            $tn_height = ceil($x_ratio * $height);
            $tn_width = $max_width;
        } else {
            $tn_width = ceil($y_ratio * $width);
            $tn_height = $max_height;
        }

        return ' width="' . $tn_width . '" height="' . $tn_height . '" ';
    }

    function printprice($val) {
        return number_format($val);
    }

    function printpricenew($val, $dec = 2) {
        return number_format($val, $dec, '.', ' ');
    }

    function formaturl($url) {
        $pattern = array(" ", "(", ")", "&amp;", "&");
        $with = array("-", "-", "-", "and", "and");
        $tmp = str_replace($pattern, $with, trim(strtolower($url)));

        for ($i = 0; $i < strlen($tmp); $i++) {
            $code = ord($tmp{$i});
            if (($code >= 48 && $code <= 57) || ($code >= 65 && $code <= 90) || ($code >= 97 && $code <= 122) || ($tmp{$i} == "-") || ($tmp{$i} == "/") || ($tmp{$i} == "_")) {
                $returl .=$tmp{$i};
            } else {
                if ($tmp{$i} == ".") {
                    if ((substr($tmp, $i + 1, 3) == "htm") || (substr($tmp, $i + 1, 3) == "php")) {
                        $returl .=$tmp{$i};
                    }
                }
            }
        }

        return($returl);
    }

    function printshortdesc($value, $numofwords = 6) {
        $desc = split(" ", $value);
        $c = 0;
        foreach ($desc as $val) {
            if ($val != "") {
                echo $val;
                $c++;
            }
            if ($c > $numofwords)
                break;
            else
                echo " ";
        }
    }

    function leading_zeros($value) {
        if ($value < 100000) {      // If the value is under 10000 then 1 zero is needed to make a 5 digit value.
            $new_value = "000" . $value;
        } else if ($value < 1000000) {      // If the value is under 10000 then 1 zero is needed to make a 5 digit value.
            $new_value = "00" . $value;
        } else if ($value < 10000000) {      // If the value is under 10000 then 1 zero is needed to make a 5 digit value.
            $new_value = "0" . $value;
        } else {                               // If the value is 10000 or over then is is already a 5 digit value.
            $new_value = $value;
        }
        return $new_value;
    }

    function quotestoascii($strValue) {
        $strValue = (!get_magic_quotes_gpc()) ? htmlspecialchars($strValue, ENT_QUOTES) : $strValue;
        $strValue = ($strValue != "") ? $strValue : "";
        return $strValue;
    }

    function str_makerand($minlength, $maxlength, $useupper, $usespecial, $usenumbers) {
        $key = "";
// $charset = "abcdefghijklmnopqrstuvwxyz"; 
        $charset = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        if ($useupper)
            $charset .= "abcdefghijklmnopqrstuvwxyz";
        if ($usenumbers)
            $charset .= "0123456789";
        if ($usespecial)
            $charset .= "~@#$%^*()_+-={}|][";   // Note: using all special characters this reads: "~!@#$%^&*()_+`-={}|\\]?[\":;'><,./"; 
        if ($minlength > $maxlength)
            $length = mt_rand($maxlength, $minlength);
        else
            $length = mt_rand($minlength, $maxlength);
        for ($i = 0; $i < $length; $i++)
            $key .= $charset[(mt_rand(0, (strlen($charset) - 1)))];
        return $key;
    }

    function getDistance($lat1, $long1, $lat2, $long2) {

        $earth = 6371; #km change accordingly
//$earth = 3960; #miles
#Point 1 cords
        $lat1 = deg2rad($lat1);
        $long1 = deg2rad($long1);

#Point 2 cords
        $lat2 = deg2rad($lat2);
        $long2 = deg2rad($long2);

#Haversine Formula
        $dlong = $long2 - $long1;
        $dlat = $lat2 - $lat1;

        $sinlat = sin($dlat / 2);
        $sinlong = sin($dlong / 2);

        $a = ($sinlat * $sinlat) + cos($lat1) * cos($lat2) * ($sinlong * $sinlong);

        $c = 2 * asin(min(1, sqrt($a)));

        $d = number_format(($earth * $c), 2);

        return $d;
    }

    function strhtmldecode($str) {
        return(htmlspecialchars_decode($str, ENT_QUOTES));
    }

    static public function array_orderby() {
        $args = func_get_args();
        $data = array_shift($args);
        foreach ($args as $n => $field) {
            if (is_string($field)) {
                $tmp = array();
                foreach ($data as $key => $row)
                    $tmp[$key] = $row[$field];
                $args[$n] = $tmp;
            }
        }
        $args[] = &$data;
        call_user_func_array('array_multisort', $args);
        return array_pop($args);
    }

    function getInfContactsById($contId, $cstmFields = array()) {

        $contactArr = array();
        if (isset($contId) && !empty($contId)) {
            if (!class_exists('iNFUSION'))
                require_once(dirname(__FILE__) . '/../vendor/isdk/infusionData.php');

            $infObject = new iNFUSION();
            $contacts = $infObject->getContactDetailsNew(array('Id'=>$contId), 0, $cstmFields);
            if (is_array($contacts) && !empty($contacts) && count($contacts)) {
                foreach ($contacts as $contactId => $contact) {
                    $contactArr[$contactId] = $contact;
                }
            }
        }
        return $contactArr;
    }
    
    function getInfContactCustomFields() {

        $fieldsArr = array();

        if (!class_exists('iNFUSION'))
            require_once(dirname(__FILE__) . '/../vendor/isdk/infusionData.php');

        $infObject = new iNFUSION();

        $fields = $infObject->getContactCustomFields();


        if (is_array($fields) && !empty($fields) && count($fields)) {
            $fieldsArr = array_keys($fields);
            asort($fieldsArr);
        }

        return $fieldsArr;
    }

    /**
     * TO load user from database
     * 
     * @param type $condition
     * @return array
     */
    public static function loadUser($condition) {
        global $db_util;

        return $db_util->SelectSingleRow("user", $condition, "");
    }

    /**
     * TO check all required field for application are available in input array or not
     * 
     * @param type $user_details
     * @return boolean
     */
    public static function verify_details($user_details) {
        $success = TRUE;

        $required_fields = array('inf_host', 'inf_api_key', 'doc_username', 'doc_password');
        foreach ($required_fields as $field) {
            if (!array_key_exists($field, $user_details) || empty($user_details[$field])) {
                return FALSE;
            }
        }
        return $success;
    }

    /**
     * TO load sys details of user
     * 
     * @param type $user
     * @return void
     */
    public static function defineSysDetails($user) {

        //Infusionsoft credentials
        define('INF_HOSTNAME', $user['inf_host']);
        define('INF_API_KEY', $user['inf_api_key']);

        //Facebbok credentials
        define('DOC_USERNAME', $user['doc_username']);
        define('DOC_PASSWORD', $user['doc_password']);
    }

    //Just to handle cron requests
    public static function isCron() {
        $is_cron = (isset($_GET['mail']) && !empty($_GET['mail'])) ? $_GET['mail'] : 0;

        return ($is_cron) ? TRUE : FALSE;
    }

    //To initialize required data for cron execution
    public static function handleCron() {
        global $cr_user;
        if (isset($_SESSION['cr_user']) && !empty($_SESSION['cr_user']) && !empty($_SESSION['cr_user']['id'])) {
            $cr_user = $_SESSION['cr_user'];
        } else {
            $cr_user = $_SESSION['cr_user'] = utils::loadUser("id='" . ACTIVE_USER_ID . "'");
        }

        if (utils::verify_details($cr_user))
            utils::defineSysDetails($cr_user);
        else {
            die('<br><br>Insufficient details to execute cron...');
        }
        return TRUE;
    }
    
    public static function getPostUrl($id) {
        return empty($id) ? '' : BASE_URL . "?mail={$id}&id=&lt;INFUSION_CONTACT_ID&gt;";
        ;
    }

}

?>