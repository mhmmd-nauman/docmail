<?php


require_once __DIR__ . '/../config/config.inc';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/utils.php';

global $db, $db_util, $cr_user;
$db_util = new DataBase();

if (!utils::isCron()) {
    session_start();
    if (!isset($_SESSION['login']) && !(isset($_POST['frmaction']) && $_POST['frmaction'] == 'usrlogin')) {
        header('Location: login.php');
//	echo "<meta HTTP-EQUIV=\"REFRESH\" content=\"0; url=index.php?err=1\">";
        exit();
    }

    if (isset($_SESSION['cr_user']) && !empty($_SESSION['cr_user'])) {
        $cr_user = $_SESSION['cr_user'];
    } elseif (isset($_SESSION['login_user_id'])) {
        $cr_user = $_SESSION['cr_user'] = utils::loadUser("id='" . $_SESSION['login_user_id'] . "'");
    }

    if (isset($_SESSION['login']) && !stristr($_SERVER['PHP_SELF'], 'profile')) {

        if (utils::verify_details($cr_user)) {
            utils::defineSysDetails($cr_user);
        } else {
            header('Location: profile.php');
            $_SESSION['flash_msg'] = array('danger' => 'Please fill all the fields with correct values to work system properly.');
            exit();
        }
    }
}