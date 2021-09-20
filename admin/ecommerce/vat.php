<?php

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("classes/vat.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpVat = new VatDAO();
$vat = $TmpVat->select(NULL, NULL, NULL, false);

?>
<!doctype html>
<html>
<head>
    <title>VAT</title>
    <?php include('../webparts/headdata.php'); ?>
    <!-- dataTables -->
    <script src="js/plugins/datatable/jquery.dataTables.min.js"></script>

    <script src="ecommerce/js/vat.js"></script>
</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
    <?php include('../webparts/website-left.php'); ?>
    <div id="main">
        <div class="container-fluid">
            <div class="page-header">
                <div class="pull-left">
                    <h1>VAT</h1>
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
                        <a href="ecommerce/dashboard.php">eCommerce</a>
                        <i class="icon-angle-right"></i>
                    </li>
                    <li>
                        <a href="ecommerce/vat.php">VAT</a>
                    </li>
                </ul>
            </div>
            <div class="row-fluid">
                <div class="span12">
                    <div class="box box-color box-bordered">
                        <div class="box-title">
                            <h3>
                                <i class="icon-legal"></i> VAT</h3>
                            <div class="actions">
                                <a href="ecommerce/vat-edit.php" class="btn btn-mini" rel="tooltip" title="New VAT item"><i class="icon-plus"></i></a>
                            </div>
                        </div>
                        <div class="box-content nopadding">

                            <table class="table" id="vatTable">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Rate</th>
                                    <th>Start Date</th>
                                </tr>
                                </thead>
                                <tbody id="vatBody">
                                <?php
                                $tableLength = count($vat);
                                for ($i=0;$i<$tableLength;++$i) {
                                    ?>
                                    <tr>
                                        <td><a href="ecommerce/vat-edit.php?vat_id=<?php echo $vat[$i]['vat_id']; ?>"><?php echo $vat[$i]['vatnam']; ?></a></td>
                                        <td><?php echo $vat[$i]['vatrat']; ?></td>
										<td><?php echo date("jS M Y", strtotime($vat[$i]['begdat'])); ?></td>
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
