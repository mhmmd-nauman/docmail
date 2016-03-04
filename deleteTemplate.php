<?php

require_once __DIR__ . '/includes/header.php';

use FacebookAds\Api;
use FacebookAds\Object\CustomAudience;
use FacebookAds\Object\Fields\CustomAudienceFields;
use FacebookAds\Object\Values\CustomAudienceTypes;
use FacebookAds\Object\Values\CustomAudienceSubtypes;

$del_id = $_GET['del_id'];

if (!empty($del_id)) {
    $tblName = "audience";
    $tmp_db_audience = $db_util->SelectTable($tblName, "id = '" . $del_id . "'", "");

    $db_audience = reset($tmp_db_audience);
    if (!empty($db_audience) && !empty($db_audience['fb_id'])) {

        try {
// Initialize a new Session and instantiate an Api object
            Api::init(
                    FB_APP_ID, // App ID
                    FB_APP_SECRET, $_SESSION['facebook_access_token'] // Your user access token
            );

// use the namespace for Custom Audiences and Fields
// Create a custom audience object, setting the parent to be the account id
            $audience = new CustomAudience($db_audience['fb_id'], 'act_' . FB_ACCOUNT_ID);

            $del_success = $audience->delete();

            $db_util->DeleteRecords($tblName, "id = '" . $del_id . "'");
            $_SESSION['flash_msg']['success'] = 'Audience deleted successfully.';
        } catch (Exception $e) {
            $_SESSION['flash_msg']['danger'] = 'Something wrong, unable to delete audience. Please try again after sometime.';
        }
    }
}
header('Location: index.php');
