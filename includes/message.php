<?php
//success,info,warning,danger
if (isset($_SESSION['flash_msg']) && !empty($_SESSION['flash_msg'])) {
    $msg_type = current(array_keys($_SESSION['flash_msg']));
    ?>
    <div class="alert alert-<?php echo $msg_type; ?> alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?php echo $_SESSION['flash_msg'][$msg_type]; ?>
    </div>
    <?php
    unset($_SESSION['flash_msg']);
}?>