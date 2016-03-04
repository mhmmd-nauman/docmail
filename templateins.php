<?php

require_once __DIR__ . '/includes/header.php';

$success = FALSE;
$is_update = FALSE;

if (isset($_POST) && !empty($_POST)) {
    $table = "template";

    if ((isset($_POST["MM_delete"])) && ($_POST["MM_delete"] == "frmdel")) {

        if (isset($_POST['templ_id'])) {
            $itemid = $_POST['templ_id'];
        } else {
            $itemid = 0;
        }

        $row = $db_util->SelectSingleRow($table, "id=" . $itemid, "id,document");

        $success = $db_util->DeleteRecords($table, "id=" . $itemid);
        if ($success) {
            if ($row['document'] != "") {
                @unlink("uploads/" . $row['document']);
            }
            $_SESSION['flash_msg']['success'] = "Template deleted successfully.";
        } else {
            $_SESSION['flash_msg']['danger'] = "Error while deleting Template.";
        }
    } else {


        $utils = new utils();
        if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frmTemplate")) {
            $is_update = FALSE;
            $document = $utils->ImageUpload("document", "uploads/", "text"); // 
        } else if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "frmTemplate")) {
            $is_update = TRUE;
            $document = $utils->UpdateImage("document", "uploads/", $_POST['olddocument'], "text"); // image field name, destination path, Old Image name
            if ($document != $_POST['olddocument']) {
                @unlink("uploads/" . $_POST['olddocument']);
            }
        }
        $fld_array = array(
            'type',
            'postage',
            'custom1',
            'custom2',
            'custom3',
            'custom4',
            'custom5',
            'custom6',
            'custom7',
            'custom8',
            'custom9',
            'custom10',
        );

        if (is_array($_POST) && count($_POST)) {
            $data = array();
            foreach ($fld_array as $field) {
                if (isset($_POST[$field]))
                    $data[$field] = $_POST[$field];
            }
        }
        $data["name"] = $utils->quotestoascii($_POST['name']);
        $data["description"] = $utils->quotestoascii($_POST['description']);
        $data['date_entered'] = 'now()';
        $data["document"] = $document;
        $data['user_id'] = $cr_user['id'];
        if ($is_update) {
            $success = $db_util->UpdateRecords($table, "id='" . $_POST['templ_id'] . "'", $data);
            if ($success) {
                $_SESSION['flash_msg']['success'] = "Template updated successfully.";
            } else {
                $_SESSION['flash_msg']['danger'] = "Error while updating Template.";
            }
        } else {
            $data['date_entered'] = 'now()';
            $id = $db_util->InsertRecords($table, $data); //to debug query just pass third parameter ,1 ie.InsertRecords("tablename",$data,1); 
            if ($id) {
                $success = TRUE;
                $_SESSION['flash_msg']['success'] = "New mail template created successfully.<br><br>Post Link: " . utils::getPostUrl($id);
            } else {
                $_SESSION['flash_msg']['danger'] = "Error while creating Template.";
            }
        }
    }
}
header('Location: template.php');
exit;
