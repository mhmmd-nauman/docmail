<?php
require_once __DIR__ . '/includes/header.php';
require_once dirname(__FILE__) . '/theme/header.php';
$utils = new utils();
$tblName = "template";
$db_tags = $db_util->SelectTable($tblName, "id != '' AND user_id = '" . $cr_user['id'] . "'", "");

$db_tags = utils::array_orderby($db_tags, 'name', SORT_ASC, 'id', SORT_ASC);

if (isset($_SESSION['infContactCustomFlds']) && !empty($_SESSION['infContactCustomFlds'])) {
    $cstm_fld_arr = $_SESSION['infContactCustomFlds'];
} else {
    $_SESSION['infContactCustomFlds'] = $cstm_fld_arr = $utils->getInfContactCustomFields();
}

if (isset($_GET['templ_id']) && !empty($_GET['templ_id'])) {

    $templ_id = $_GET['templ_id'];
    $tblName2 = "template";
    $tmp_db_template = $db_util->SelectTable($tblName2, "id = '" . $templ_id . "'", "");

    if (count($tmp_db_template) && !empty($tmp_db_template[0]))
        $template = reset($tmp_db_template);
    ?>
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Edit Template</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Template Details: <?php echo $template['name']; ?>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <form role="form" action="templateins.php" name="CreateTemplate" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label>Post Link:</label>
                                    <div class="alert alert-info">
                                        <?php echo utils::getPostUrl($templ_id); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Name:</label>
                                    <input type="text" name="name" class="form-control" placeholder="Enter Template Name" required="required" value="<?php echo $template['name']; ?>">
                                </div>
                                <div class="form-group">
                                    <label>Description:</label>
                                    <textarea name="description" class="form-control" rows="3" placeholder="Description"><?php echo $template['description']; ?></textarea>
                                </div> 
                                <div class="form-group">
                                    <label>Type:</label>
                                    <select name="type" class="form-control" required="required">
                                        <?php
                                        if (isset($type_arr) && count($type_arr)) {
                                            foreach ($type_arr as $key => $type) {
                                                ?>
                                                <option value="<?php echo $key; ?>"  label="<?php echo $type; ?>" <?php
                                                if ($key == $template['type']) {
                                                    echo 'selected = "selected"';
                                                }
                                                ?>><?php echo $type; ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Postage:</label>
                                    <select name="postage" class="form-control" required="required">
                                        <?php
                                        if (isset($postage_arr) && count($postage_arr)) {
                                            foreach ($postage_arr as $p_key => $postage) {
                                                ?>
                                                <option value="<?php echo $p_key; ?>"  label="<?php echo $postage; ?>" <?php
                                                if ($p_key == $template['postage']) {
                                                    echo 'selected = "selected"';
                                                }
                                                ?>><?php echo $postage; ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Document:</label> <span style="margin-left: 16px;"><a title="Click to download current document" href="uploads/<?php echo $template['document']; ?>" download><i class="fa fa-download fa-2x"></i> <?php echo ' ' . $template['document']; ?></a></span>
                                    <input type="file" name="document" class="form-control" placeholder="Select document">
                                </div>
                                <hr>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <b>Docmail Merge Fields:</b>
                                    </div><div class="panel-body">
                                        <div class="form-group">
                                            <label>Field 1</label>
                                            <select name="custom1" class="form-control">
                                                <option value="" label="--None assigned--">--Select--</option>
                                                <?php
                                                if (isset($cstm_fld_arr) && count($cstm_fld_arr)) {
                                                    foreach ($cstm_fld_arr as $cstm_fld) {
                                                        ?>
                                                        <option value="<?php echo $cstm_fld; ?>" label="<?php echo $cstm_fld; ?>"
                                                        <?php
                                                        if ($cstm_fld == $template['custom1']) {
                                                            echo 'selected = "selected"';
                                                        }
                                                        ?>><?php echo $cstm_fld; ?></option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Field 2</label>
                                            <select name="custom2" class="form-control">
                                                <option value="" label="--None assigned--">--Select--</option>
                                                <?php
                                                if (isset($cstm_fld_arr) && count($cstm_fld_arr)) {
                                                    foreach ($cstm_fld_arr as $cstm_fld) {
                                                        ?>
                                                        <option value="<?php echo $cstm_fld; ?>" label="<?php echo $cstm_fld; ?>"
                                                        <?php
                                                        if ($cstm_fld == $template['custom2']) {
                                                            echo 'selected = "selected"';
                                                        }
                                                        ?>><?php echo $cstm_fld; ?></option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Field 3</label>
                                            <select name="custom3" class="form-control">
                                                <option value="" label="--None assigned--">--Select--</option>
                                                <?php
                                                if (isset($cstm_fld_arr) && count($cstm_fld_arr)) {
                                                    foreach ($cstm_fld_arr as $cstm_fld) {
                                                        ?>
                                                        <option value="<?php echo $cstm_fld; ?>" label="<?php echo $cstm_fld; ?>"
                                                        <?php
                                                        if ($cstm_fld == $template['custom3']) {
                                                            echo 'selected = "selected"';
                                                        }
                                                        ?>><?php echo $cstm_fld; ?></option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Field 4</label>
                                            <select name="custom4" class="form-control">
                                                <option value="" label="--None assigned--">--Select--</option>
                                                <?php
                                                if (isset($cstm_fld_arr) && count($cstm_fld_arr)) {
                                                    foreach ($cstm_fld_arr as $cstm_fld) {
                                                        ?>
                                                        <option value="<?php echo $cstm_fld; ?>" label="<?php echo $cstm_fld; ?>"
                                                        <?php
                                                        if ($cstm_fld == $template['custom4']) {
                                                            echo 'selected = "selected"';
                                                        }
                                                        ?>><?php echo $cstm_fld; ?></option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Field 5</label>
                                            <select name="custom5" class="form-control">
                                                <option value="" label="--None assigned--">--Select--</option>
                                                <?php
                                                if (isset($cstm_fld_arr) && count($cstm_fld_arr)) {
                                                    foreach ($cstm_fld_arr as $cstm_fld) {
                                                        ?>
                                                        <option value="<?php echo $cstm_fld; ?>" label="<?php echo $cstm_fld; ?>"
                                                        <?php
                                                        if ($cstm_fld == $template['custom5']) {
                                                            echo 'selected = "selected"';
                                                        }
                                                        ?>><?php echo $cstm_fld; ?></option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Field 6</label>
                                            <select name="custom6" class="form-control">
                                                <option value="" label="--None assigned--">--Select--</option>
                                                <?php
                                                if (isset($cstm_fld_arr) && count($cstm_fld_arr)) {
                                                    foreach ($cstm_fld_arr as $cstm_fld) {
                                                        ?>
                                                        <option value="<?php echo $cstm_fld; ?>" label="<?php echo $cstm_fld; ?>"
                                                        <?php
                                                        if ($cstm_fld == $template['custom6']) {
                                                            echo 'selected = "selected"';
                                                        }
                                                        ?>><?php echo $cstm_fld; ?></option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Field 7</label>
                                            <select name="custom7" class="form-control">
                                                <option value="" label="--None assigned--">--Select--</option>
                                                <?php
                                                if (isset($cstm_fld_arr) && count($cstm_fld_arr)) {
                                                    foreach ($cstm_fld_arr as $cstm_fld) {
                                                        ?>
                                                        <option value="<?php echo $cstm_fld; ?>" label="<?php echo $cstm_fld; ?>"
                                                        <?php
                                                        if ($cstm_fld == $template['custom7']) {
                                                            echo 'selected = "selected"';
                                                        }
                                                        ?>><?php echo $cstm_fld; ?></option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Field 8</label>
                                            <select name="custom8" class="form-control">
                                                <option value="" label="--None assigned--">--Select--</option>
                                                <?php
                                                if (isset($cstm_fld_arr) && count($cstm_fld_arr)) {
                                                    foreach ($cstm_fld_arr as $cstm_fld) {
                                                        ?>
                                                        <option value="<?php echo $cstm_fld; ?>" label="<?php echo $cstm_fld; ?>"
                                                        <?php
                                                        if ($cstm_fld == $template['custom8']) {
                                                            echo 'selected = "selected"';
                                                        }
                                                        ?>><?php echo $cstm_fld; ?></option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Field 9</label>
                                            <select name="custom9" class="form-control">
                                                <option value="" label="--None assigned--">--Select--</option>
                                                <?php
                                                if (isset($cstm_fld_arr) && count($cstm_fld_arr)) {
                                                    foreach ($cstm_fld_arr as $cstm_fld) {
                                                        ?>
                                                        <option value="<?php echo $cstm_fld; ?>" label="<?php echo $cstm_fld; ?>"
                                                        <?php
                                                        if ($cstm_fld == $template['custom9']) {
                                                            echo 'selected = "selected"';
                                                        }
                                                        ?>><?php echo $cstm_fld; ?></option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Field 10</label>
                                            <select name="custom10" class="form-control">
                                                <option value="" label="--None assigned--">--Select--</option>
                                                <?php
                                                if (isset($cstm_fld_arr) && count($cstm_fld_arr)) {
                                                    foreach ($cstm_fld_arr as $cstm_fld) {
                                                        ?>
                                                        <option value="<?php echo $cstm_fld; ?>" label="<?php echo $cstm_fld; ?>"
                                                        <?php
                                                        if ($cstm_fld == $template['custom10']) {
                                                            echo 'selected = "selected"';
                                                        }
                                                        ?>><?php echo $cstm_fld; ?></option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="templ_id" value="<?php echo $templ_id; ?>">
                                <input name="olddocument" type="hidden" value="<?php echo $template['document']; ?>"/>
                                <input type="hidden" name="MM_update" value="frmTemplate">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <button type="reset" class="btn btn-primary">Reset</button>
                            </form>
                        </div>
                        <!-- /.col-lg-6 (nested) -->

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
<?php } else { ?>
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Create New Template</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Template Details
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <form role="form" action="templateins.php" name="CreateTemplate" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label>Name:</label>
                                    <input type="text" name="name" class="form-control" placeholder="Enter Template Name" required="required">
                                </div>
                                <div class="form-group">
                                    <label>Description:</label>
                                    <textarea name="description" class="form-control" rows="3" placeholder="Description"></textarea>
                                </div> 
                                <div class="form-group">
                                    <label>Type</label>
                                    <select name="type" class="form-control" required="required">
                                        <option value="" label="--Please select type of mail--">--Select--</option>
                                        <?php
                                        if (isset($type_arr) && count($type_arr)) {
                                            foreach ($type_arr as $key => $type) {
                                                ?>
                                                <option value="<?php echo $key; ?>" label="<?php echo $type; ?>"><?php echo $type; ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Postage</label>
                                    <select name="postage" class="form-control" required="required">
                                        <option value="" label="--Please select postage option--">--Select--</option>
                                        <?php
                                        if (isset($postage_arr) && count($postage_arr)) {
                                            foreach ($postage_arr as $p_key => $postage) {
                                                ?>
                                                <option value="<?php echo $p_key; ?>" label="<?php echo $postage; ?>"><?php echo $postage; ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Document:</label>
                                    <input type="file" name="document" class="form-control" placeholder="Select document" required="required">
                                </div>
                                <hr>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <b>Docmail Merge Fields:</b>
                                    </div><div class="panel-body">
                                        <div class="form-group">
                                            <label>Field 1</label>
                                            <select name="custom1" class="form-control">
                                                <option value="" label="--None assigned--">--Select--</option>
                                                <?php
                                                if (isset($cstm_fld_arr) && count($cstm_fld_arr)) {
                                                    foreach ($cstm_fld_arr as $cstm_fld) {
                                                        ?>
                                                        <option value="<?php echo $cstm_fld; ?>" label="<?php echo $cstm_fld; ?>"><?php echo $cstm_fld; ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Field 2</label>
                                            <select name="custom2" class="form-control">
                                                <option value="" label="--None assigned--">--Select--</option>
                                                <?php
                                                if (isset($cstm_fld_arr) && count($cstm_fld_arr)) {
                                                    foreach ($cstm_fld_arr as $cstm_fld) {
                                                        ?>
                                                        <option value="<?php echo $cstm_fld; ?>" label="<?php echo $cstm_fld; ?>"><?php echo $cstm_fld; ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Field 3</label>
                                            <select name="custom3" class="form-control">
                                                <option value="" label="--None assigned--">--Select--</option>
                                                <?php
                                                if (isset($cstm_fld_arr) && count($cstm_fld_arr)) {
                                                    foreach ($cstm_fld_arr as $cstm_fld) {
                                                        ?>
                                                        <option value="<?php echo $cstm_fld; ?>" label="<?php echo $cstm_fld; ?>"><?php echo $cstm_fld; ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Field 4</label>
                                            <select name="custom4" class="form-control">
                                                <option value="" label="--None assigned--">--Select--</option>
                                                <?php
                                                if (isset($cstm_fld_arr) && count($cstm_fld_arr)) {
                                                    foreach ($cstm_fld_arr as $cstm_fld) {
                                                        ?>
                                                        <option value="<?php echo $cstm_fld; ?>" label="<?php echo $cstm_fld; ?>"><?php echo $cstm_fld; ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Field 5</label>
                                            <select name="custom5" class="form-control">
                                                <option value="" label="--None assigned--">--Select--</option>
                                                <?php
                                                if (isset($cstm_fld_arr) && count($cstm_fld_arr)) {
                                                    foreach ($cstm_fld_arr as $cstm_fld) {
                                                        ?>
                                                        <option value="<?php echo $cstm_fld; ?>" label="<?php echo $cstm_fld; ?>"><?php echo $cstm_fld; ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Field 6</label>
                                            <select name="custom6" class="form-control">
                                                <option value="" label="--None assigned--">--Select--</option>
                                                <?php
                                                if (isset($cstm_fld_arr) && count($cstm_fld_arr)) {
                                                    foreach ($cstm_fld_arr as $cstm_fld) {
                                                        ?>
                                                        <option value="<?php echo $cstm_fld; ?>" label="<?php echo $cstm_fld; ?>"><?php echo $cstm_fld; ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Field 7</label>
                                            <select name="custom7" class="form-control">
                                                <option value="" label="--None assigned--">--Select--</option>
                                                <?php
                                                if (isset($cstm_fld_arr) && count($cstm_fld_arr)) {
                                                    foreach ($cstm_fld_arr as $cstm_fld) {
                                                        ?>
                                                        <option value="<?php echo $cstm_fld; ?>" label="<?php echo $cstm_fld; ?>"><?php echo $cstm_fld; ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Field 8</label>
                                            <select name="custom8" class="form-control">
                                                <option value="" label="--None assigned--">--Select--</option>
                                                <?php
                                                if (isset($cstm_fld_arr) && count($cstm_fld_arr)) {
                                                    foreach ($cstm_fld_arr as $cstm_fld) {
                                                        ?>
                                                        <option value="<?php echo $cstm_fld; ?>" label="<?php echo $cstm_fld; ?>"><?php echo $cstm_fld; ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Field 9</label>
                                            <select name="custom9" class="form-control">
                                                <option value="" label="--None assigned--">--Select--</option>
                                                <?php
                                                if (isset($cstm_fld_arr) && count($cstm_fld_arr)) {
                                                    foreach ($cstm_fld_arr as $cstm_fld) {
                                                        ?>
                                                        <option value="<?php echo $cstm_fld; ?>" label="<?php echo $cstm_fld; ?>"><?php echo $cstm_fld; ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Field 10</label>
                                            <select name="custom10" class="form-control">
                                                <option value="" label="--None assigned--">--Select--</option>
                                                <?php
                                                if (isset($cstm_fld_arr) && count($cstm_fld_arr)) {
                                                    foreach ($cstm_fld_arr as $cstm_fld) {
                                                        ?>
                                                        <option value="<?php echo $cstm_fld; ?>" label="<?php echo $cstm_fld; ?>"><?php echo $cstm_fld; ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="MM_insert" value="frmTemplate">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <button type="reset" class="btn btn-primary">Reset</button>
                            </form>
                        </div>
                        <!-- /.col-lg-6 (nested) -->

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
}
require_once 'theme/footer.php';
?>