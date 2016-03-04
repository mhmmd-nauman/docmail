<?php
require_once __DIR__ . '/includes/header.php';
require_once dirname(__FILE__) . '/theme/header.php';

$table = 'audience';
$user_id = $cr_user['id'];




$data = array();

            $data['fb_id'] = $audience->id;
            $data['name'] = $_POST['name'];
            $data['description'] = $_POST['description'];
            $data['groups'] = implode(',', array_keys($tagArr));
            $data['user_id'] = 1;
            $data['active'] = 1;
            $data['date_modified'] = 'now()';
            if (empty($audi_id) || empty($db_audiences) || !in_array($audience->id, $db_audiences)) {
                $data['date_entered'] = $data['date_modified'];
                $_POST['audi_id'] = $id = $db_util->InsertRecords($tblName, $data);
                
$audiences = $db_util->SelectTable($table, "user_id='" . $user_id . "' ORDER BY date_entered", "");

echo "<br><br>-------<br><pre>",print_r($audiences ,1),"</pre><br>-------<br><br>";
?>

