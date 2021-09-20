<?php
require_once('../../config/config.php');

require_once('../patchworks.php');

require_once("classes/emailtemplate.cls.php");



$userAuth = new AuthDAO();

$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);

if ($loggedIn == 0) header('location: ../login.php');



$TmpEmt = new EmtDAO();

$emailTemplates = $TmpEmt->select(NULL, 'WEBSITE', NULL, false);


?>

<!doctype html>

<html>

<head>

    <title>Email Template Listing</title>
    <?php include('../webparts/headdata.php'); ?>
    <script src="emailtemplates/js/emailtemplate.js"></script>

</head>

<?php include('../webparts/navigation.php'); ?>

<body class="theme-red">

<div class="container-fluid" id="content">

    <?php include('../webparts/website-left.php'); ?>

    <div id="main">

        <div class="container-fluid">

            <div class="page-header">

                <div class="pull-left">

                    <h1>Email Templates</h1>

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

                        <a href="emailtemplates/emailtemplate.php">Email Templates</a>

                    </li>

                </ul>

            </div>

            <div class="row-fluid">

                <div class="span12">

                    <div class="box box-color box-bordered">

                        <div class="box-title">

                            <h3>

                                <i class="icon-envelope"></i> Email Templates</h3>

                            <div class="actions">

                                <a href="emailtemplates/emailtemplate-edit.php" class="btn btn-mini" rel="tooltip" title="New Email Template"><i class="icon-plus"></i></a>

                            </div>

                        </div>

                        <div class="box-content nopadding">

                            <table class="table table-bordered table-striped table-highlight" id="userTable" data-returnurl="emailtemplates/emailtemplate.php">

                                <thead>

                                <tr>

                                    <th style="width: 40px;">#</th>

                                    <th>Email Template Name</th>

                                    <th style="width: 40px;"></th>

                                </tr>

                                </thead>

                                <tbody>

                                <?php

                                $tableLength = count($emailTemplates);

                                for ($i=0;$i<$tableLength;++$i) {

                                    ?>

                                    <tr>

                                        <td><?php echo $emailTemplates[$i]['emt_id']; ?></td>

                                        <td><a href="<?php $patchworks->pwRoot; ?>emailtemplates/emailtemplate-edit.php?emt_id=<?php echo $emailTemplates[$i]['emt_id']; ?>"><?php echo $emailTemplates[$i]['emtnam']; ?></a></td>

                                        <td><a href="#" class="btn btn-mini btn-danger delEmaTmpBtn" data-emt_id="<?php echo $emailTemplates[$i]["emt_id"] ?>"><i class="icon icon-trash"></i></a></td>

                                    </tr>

                                <?php } ?>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>



        </div>

    </div>

</div>

</body>

</html>

