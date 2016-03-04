<?php
require_once __DIR__ . '/includes/header.php';

$success = FALSE;
if (isset($_POST) && !empty($_POST)) {
    $fld_array = array(
        'cur_pwd',
        'new_pwd',
        'new_pwd_repeat',
    );

    if (is_array($_POST) && count($_POST)) {
        $err = FALSE;
        foreach ($fld_array as $fld) {
            if (!array_key_exists($fld, $_POST) || empty($_POST[$fld]) || (strlen($_POST[$fld]) < 6 || strlen($_POST[$fld]) > 12)) {
                $err = TRUE;
            }
        }
        if ($err || $_POST['new_pwd'] != $_POST['new_pwd_repeat'] || $_POST['cur_pwd'] == $_POST['new_pwd']) {
            $err = TRUE;
        }
        if ($err) {
            $_SESSION['flash_msg']['danger'] = "Please follow below rules in order to change password.";
            header('Location: changePassword.php');
            exit;
        }
        $updata = array();
        $updata['password'] = md5(trim($_POST['new_pwd']));

        if (count($updata)) {
            $table = "user";
            $success = $db_util->UpdateRecords($table, "id='" . $cr_user['id'] . "'", $updata);
            if ($success) {
                $_SESSION['flash_msg']['success'] = "Password changed successfully.";
            }
        }
    }
    header('Location: changePassword.php');
    exit;
}



require_once dirname(__FILE__) . '/theme/header.php';

$user_id = $cr_user['id'];
$table = "user";
$user = current($db_util->SelectTable($table, "id='" . $user_id . "'"));
?>
<div class="row">

    <div class="col-lg-12">
        <h1 class="page-header">Profile</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>Change Password</h4>
            </div>
            <br>
            <div class="col-lg-12">
                <?php
                if (isset($_SESSION['flash_msg']))
                    include __DIR__ . '/includes/message.php';

                $_SESSION['flash_msg'] = array('info' => "NOTE:"
                    . "<br>1. Password should be of minimun 6 to maximum 12 characters."
                    . "<br>2. New password should not same as Current password.");
                include __DIR__ . '/includes/message.php';
                ?>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <form name="frmpassword" role="form" method="post" action="changePassword.php">
                            <div class="form-group">
                                <label>Current Password</label>
                                <input class="form-control" placeholder="Enter Current Password" type="password" name="cur_pwd" minlength="6" maxlength="12" required="required">             
                            </div>
                            <div class="form-group">
                                <label>New Password</label>
                                <input class="form-control" placeholder="Enter New Password" type="password" name="new_pwd" minlength="6" maxlength="12" required="required">             
                            </div>
                            <div class="form-group">
                                <label>Re-enter New Password</label>
                                <input class="form-control" placeholder="Enter New Password again" type="password" name="new_pwd_repeat" minlength="6" maxlength="12" required="required">             
                            </div>

                            <button type="submit" class="btn btn-primary">Save</button>
                            <button type="reset" class="btn btn-default">Reset</button>
                        </form>
                    </div>
                    <!-- /.col-lg-6 (nested) -->                   
                </div>
                <!-- /.row (nested) -->
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<?php
require_once dirname(__FILE__) . '/theme/footer.php';
?>