<?php

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("classes/multibuy.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpMul = new MulDAO();
$multibuy = $TmpMul->select(NULL, NULL, NULL, false);

?>
<!doctype html>
<html>
<head>
    <title>Multibuy Promotions</title>
    <?php include('../webparts/headdata.php'); ?>
    <!-- dataTables -->
    <script src="js/plugins/datatable/jquery.dataTables.min.js"></script>

    <script src="ecommerce/js/multibuy.js"></script>
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
                        <a href="ecommerce/vat.php">Multibuy</a>
                    </li>
                </ul>
            </div>
            <div class="row-fluid">
                <div class="span12">
                    <div class="box box-color box-bordered">
                        <div class="box-title">
                            <h3>
                                <i class="icon-bolt"></i> Multibuy</h3>
                            <div class="actions">
                                <a href="ecommerce/multibuy-edit.php" class="btn btn-mini" rel="tooltip" title="New multibuy promotion"><i class="icon-plus"></i></a>
                            </div>
                        </div>
                        <div class="box-content nopadding">

                            <table class="table" id="vatTable">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                </tr>
                                </thead>
                                <tbody id="vatBody">
                                <?php
                                $tableLength = count($multibuy);
                                for ($i=0;$i<$tableLength;++$i) {
                                    ?>
                                    <tr>
                                        <td><a href="ecommerce/multibuy-edit.php?mul_id=<?php echo $multibuy[$i]['mul_id']; ?>"><?php echo $multibuy[$i]['multtl']; ?></a></td>
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
