<?php

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("../custom/classes/baskets.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');


$TmpBsk = new BskDAO();
$baskets = $TmpBsk->select(NULL, NULL, NULL, NULL, false);

if (isset($_GET['bsk_id']) && is_numeric(isset($_GET['bsk_id']))) {
    $basketRec = $TmpBsk->select($_GET['bsk_id'], NULL, NULL, NULL, true);
}

?>
<!doctype html>
<html>
<head>
    <title>Basket Report</title>
    <?php include('../webparts/headdata.php'); ?>

    <script src="js/plugins/datatable/jquery.dataTables.min.js"></script>
    <script src="js/plugins/datatable/TableTools.min.js"></script>
    <script src="js/plugins/datatable/ColReorder.min.js"></script>
    <script src="js/plugins/datatable/ColVis.min.js"></script>
    <script src="js/plugins/datatable/jquery.dataTables.columnFilter.js"></script>

</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
    <?php include('../webparts/website-left.php'); ?>
    <div id="main">
        <div class="container-fluid">
            <div class="page-header">
                <div class="pull-left">
                    <h1>Basket Report (No VAT)</h1>
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
                        <a href="custom/baskets.php">Baskets</a>
                    </li>
                </ul>
            </div>
            <div class="row-fluid">
                <div class="span12">
                    <div class="box box-color box-bordered">
                        <div class="box-title">
                            <h3>
                                <i class="icon-shopping-cart"></i> Baskets</h3>
                            <div class="actions">
                                <a href="custom/baskets-edit.php" class="btn btn-mini" rel="tooltip" title="New Basket"><i class="icon-plus"></i></a>
                            </div>
                        </div>
                        <div class="box-content nopadding">
                            <table class="table table-bordered table-striped table-highlight" id="basketTable">
                                <thead>
                                <tr>
                                    <th width="100"></th>
                                    <th>Basket Name</th>
                                    <th>Weight</th>
                                    <th>VAT Rate</th>
                                </tr>
                                </thead>
                                <tbody id="basketBody">

                                <?php
                                for ($i=0;$i<count($baskets);$i++) {

                                    if ( $baskets[$i]['vatrat'] > 0 ) continue;

                                    ?>

                                    <tr>
                                        <td style="width: 120px;">
                                            <?php

                                            if (
                                                isset($baskets[$i]['bskimg']) &&
                                                file_exists($patchworks->docRoot . 'uploads/images/basket/' . $baskets[$i]['bskimg']) &&
                                                !is_dir($patchworks->docRoot . 'uploads/images/basket/' . $baskets[$i]['bskimg'])
                                            ) {
                                                echo '<img src="../uploads/images/basket/' . $baskets[$i]['bskimg'] . '" class="img-responsive productImage" />';
                                            } else {
                                                echo '<img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image" />';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <a href="custom/baskets-edit.php?bsk_id=<?php echo $baskets[$i]['bsk_id']; ?>"><?php echo $baskets[$i]['bskttl']; ?></a>
                                        </td>
                                        <td>
                                            <?php echo $baskets[$i]['weight']; ?>
                                        </td>
                                        <td>
                                            <?php echo $baskets[$i]['vatrat']; ?>
                                        </td>
                                    </tr>

                                    <?php
                                }
                                ?>

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
