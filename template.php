<?php
require_once __DIR__ . '/includes/header.php';
require_once dirname(__FILE__) . '/theme/header.php';

$table = 'template';
$user_id = $cr_user['id'];
$templates = $db_util->SelectTable($table, "user_id='" . $user_id . "' ORDER BY date_entered", "");
?>
<script>
    $(document).ready(function () {
        $('#dataTables-audiences').DataTable({
            "order": [[0, "desc"]]
        });
});
        function deleteTemplate(id)
        {
            if (delete_record() == true)
            {
                document.frmdel.templ_id.value = id;
                document.frmdel.submit();
            }
        }
    
</script>
<div class="row">
    <div class="col-lg-11">
        <h1 class="page-header">Mail Templates</h1>
    </div>
    <div class="col-lg-1">
        <a href="createTemplate.php" class="page-header pull-right fa fa-plus-circle fa-2x" title="Create New Mail Template"></a>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <?php include_once __DIR__ . '/includes/message.php'; ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                Mail Templates
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="dataTable_wrapper">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-audiences">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Postage</th>
                                <th>Created date</th>                                
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $cnt = 0;
                            foreach ($templates as $key => $template) {
                                $level = ($cnt % 2 == 0) ? 'even' : 'odd';
                                switch ($cnt % 2) {
                                    case 0:
                                        $level = 'even';
                                        $rowClass = 'gradeA';
                                        break;
                                    default :
                                        $level = 'odd';
                                        $rowClass = 'gradeC';
                                }

                                $db_tags = array();
                                ?>

                                <tr class="<?php echo $level . ' ' . $rowClass; ?> ">
                                    <td><?php echo $template['id']; ?></td>
                                    <td><?php echo $template['name']; ?></td>
                                    <td><?php echo $template['type']; ?></td>
                                    <td><?php echo $template['postage']; ?></td>
                                    <td><?php echo date("m/d/Y H:i", strtotime($template['date_entered'])); ?></td>
                                    <td class="center text-center">
                                        <a title="Edit" href="createTemplate.php?templ_id=<?php echo $template['id']; ?>"><span class="fa fa-edit"></span></a> 
                                        <a title="Delete"  href="javascript:void(0);" onClick="deleteTemplate('<?php echo $template['id'];?>');"> <span class="fa fa-trash-o"></span></a></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>          

<form name="frmdel" action="templateins.php" method="post">
    <input type="hidden" name="MM_delete" value="frmdel">
    <input type="hidden" name="templ_id" value="">
</form>
<?php require_once 'theme/footer.php'; ?>
