<?php
if (isset($_POST) && !empty($_POST) && !isset($_GET['err'])) {
    require_once __DIR__ . '/includes/header.php';

    if (isset($_POST['txtusername']) && isset($_POST['txtpassword']) && !empty($_POST['txtusername']) && !empty($_POST['txtpassword'])) {
        global $db_util;
        $colname_rslogin = "1";
        $colname_rsloginpass = "1";
        if (isset($_POST['txtusername'])) {
            $colname_rslogin = (get_magic_quotes_gpc()) ? $_POST['txtusername'] : addslashes($_POST['txtusername']);
        }
        if (isset($_POST['txtpassword'])) {
            $colname_rsloginpass = md5((get_magic_quotes_gpc()) ? $_POST['txtpassword'] : addslashes($_POST['txtpassword']));
        }

        $rslogin = $db_util->SelectTable("user", "email = '" . $colname_rslogin . "' AND password = '" . $colname_rsloginpass . "'", "");
        $totalRows_rslogin = count($rslogin);
        if ($totalRows_rslogin > 0) {
            $_SESSION['login'] = "yes";
            $usr = reset($rslogin);
            $_SESSION['login_user_id'] = $usr['id'];

            echo "<meta HTTP-EQUIV=\"REFRESH\" content=\"0; url=template.php\">";
        } else {
            echo "<meta HTTP-EQUIV=\"REFRESH\" content=\"0; url=login.php?err=1\">";
        }
    } else {
        echo "<meta HTTP-EQUIV=\"REFRESH\" content=\"0; url=login.php?err=1\">";
    }
    exit;
} else {
    session_start();
    if (isset($_SESSION['login'])) {
        header('Location: template.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Infusion - Docmail</title>

        <!-- Bootstrap Core CSS -->
        <link href="theme/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- MetisMenu CSS -->
        <link href="theme/bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="theme/dist/css/sb-admin-2.css" rel="stylesheet">

        <!-- Custom Fonts -->
        <link href="theme/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>

    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class="login-panel panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Infusion Docmail Login</h3>
                        </div>
                        <div class="panel-body">
                            <?php if (isset($_GET['err'])) { ?>
                                <div class="fa fa-warning"> Invalid Email or Password!<br><br></div>
                            <?php } ?>
                            <form role="form" action="login.php" method="post">
                                <fieldset>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="E-mail" name="txtusername" type="email" required="required" autofocus>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Password" name="txtpassword" type="password" required="required" value="">
                                    </div>
                                    <!--                                <div class="checkbox">
                                                                        <label>
                                                                            <input name="remember" type="checkbox" value="Remember Me">Remember Me
                                                                        </label>
                                                                    </div>-->
                                    <!-- Change this to a button or input when using this as a form -->
                                    <button type ="submit" class="btn btn-lg btn-success btn-block" name="sbmtbutton">Login</button>                                    
                                </fieldset>
                                <input type="hidden" name="frmaction" value="usrlogin"/>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- jQuery -->
        <script src="theme/bower_components/jquery/dist/jquery.min.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="theme/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

        <!-- Metis Menu Plugin JavaScript -->
        <script src="theme/bower_components/metisMenu/dist/metisMenu.min.js"></script>

        <!-- Custom Theme JavaScript -->
        <script src="theme/dist/js/sb-admin-2.js"></script>

    </body>

</html>

