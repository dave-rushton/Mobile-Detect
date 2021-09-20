<?php

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once('../system/classes/messages.cls.php');
require_once("../attributes/classes/attrgroups.cls.php");
require_once("../attributes/classes/attrvalues.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$Atr_ID = (isset($_GET['atr_id']) && is_numeric($_GET['atr_id'])) ? $_GET['atr_id'] : NULL;

$MsgDao = new MsgDAO();
$webcontacts = $MsgDao->select(NULL, NULL, 'FORM', NULL, NULL, false);
//$webcontacts = $MsgDao->select(NULL, $Atr_ID, 'FORM', NULL, NULL, false);

$TmpAtr = new AtrDAO();
$TmpAtv = new AtvDAO();

?>
<!doctype html>
<html>
<head>
    <title>Website Contacts</title>
    <?php include('../webparts/headdata.php'); ?>
    <script src="website/js/webcontacts.js"></script>

</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
    <?php include('../webparts/website-left.php'); ?>
    <div id="main">
        <div class="container-fluid">
            <div class="page-header">
                <div class="pull-left">
                    <h1>Website Contact</h1>
                </div>
                <div class="pull-right">
                    <?php include('../webparts/index-info.php'); ?>
                </div>
            </div>
            <div class="breadcrumbs">
                <ul>
                    <li>
                        <a href="index.php">Dashboard</a>
                        <i class="icon-angle-right"></i>
                    </li>
                    <li>
                        <a>Website</a>
                        <i class="icon-angle-right"></i>
                    </li>
                    <li>
                        <a href="website/forms.php">Forms</a>
                        <i class="icon-angle-right"></i>
                    </li>
                    <li>
                        <a href="website/webcontacts.php">Contact</a>
                    </li>
                </ul>
            </div>
            <div class="row-fluid">
                <div class="span12">

                    <div class="box box-color box-bordered lightgrey">
                        <div class="box-title">
                            <h3><i class="icon-comments-alt"></i> Website Contact</h3>
                            <!--<div class="actions">
                                <a href="#new-task" data-toggle="modal" class="btn"><i class="icon-plus-sign"></i> Add Task</a>
                            </div>-->
                        </div>
                        <div class="box-content nopadding">
                            <ul class="tasklist">

                                <?php
                                $tableLength = count($webcontacts);
                                for ($i=0;$i<$tableLength;++$i) {

                                    $resultSet = $TmpAtv->selectValueSet($webcontacts[$i]['atr_id'], $webcontacts[$i]['tblnam'], $webcontacts[$i]['tbl_id'], NULL, NULL, false);
                                    $attrGroupRec = $TmpAtr->select($webcontacts[$i]['atr_id'], NULL, NULL, NULL, true);

                                    ?>

                                    <li class="<?php if ($webcontacts[$i]['sta_id'] == 1) { echo 'done'; } else if ($webcontacts[$i]['sta_id'] == 2) { echo 'bookmarked'; } ?>">
                                        <div class="check">
                                            <input type="checkbox" class='icheck-me' data-skin="square" data-color="blue" data-msg_id="<?php echo $webcontacts[$i]['msg_id']; ?>" <?php if ($webcontacts[$i]['sta_id'] == 1) echo 'checked'; ?>>
                                        </div>

                                        <a href="#modal-1" role="button" class="viewMessageLink" data-toggle="modal" data-atr_id="<?php echo $webcontacts[$i]['atr_id']; ?>"  data-tblnam="<?php echo $webcontacts[$i]['tblnam']; ?>"  data-tbl_id="<?php echo $webcontacts[$i]['tbl_id']; ?>"><span class="task"><span><?php echo ($attrGroupRec) ? $attrGroupRec->atrnam : '',' ',$webcontacts[$i]['msgtxt']; ?></span></span></a>
                                        <span class="task-actions">
											<a href="#" class='task-delete' rel="tooltip" title="Delete that task" data-msg_id="<?php echo $webcontacts[$i]['msg_id']; ?>"><i class="icon-remove"></i></a>
											<a href="#" class='task-bookmark' rel="tooltip" title="Mark as important" data-msg_id="<?php echo $webcontacts[$i]['msg_id']; ?>"><i class="icon-bookmark-empty"></i></a>
										</span>

                                    </li>

                                <?php } ?>

                            </ul>
                        </div>
                    </div>


                </div>
            </div>

        </div>
    </div>
</div>

<div id="modal-1" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Website Contact</h3>
    </div>
    <div class="modal-body" id="messageFields">

    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    </div>
</div>
</body>
</html>