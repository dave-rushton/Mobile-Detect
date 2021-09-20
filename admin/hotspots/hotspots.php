<?php

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("../hotspots/classes/hotspots.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpHot = new HotDAO();

$hotSpotRecs = $TmpHot->select(NULL, NULL, NULL, false);

?>
<!doctype html>
<html>
<head>
    <title>Hot Spots</title>
    <?php include('../webparts/headdata.php'); ?>
</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
    <?php include('../webparts/website-left.php'); ?>
    <div id="main">
        <div class="container-fluid">
            <div class="page-header">
                <div class="pull-left">
                    <h1>Hot Spots</h1>
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
                        <a href="custom/hotspots.php">Hot Spots</a>
                    </li>
                </ul>
            </div>
            <div class="row-fluid">
                <div class="span12">
                    <div class="box box-color box-bordered">
                        <div class="box-title">
                            <h3>
                                <i class="icon-map-marker"></i> Hot Spots</h3>
                            <div class="actions">
                                <a href="hotspots/hotspots-edit.php" class="btn btn-mini" rel="tooltip" title="New Hotspot"><i class="icon-plus"></i></a>
                            </div>
                        </div>
                        <div class="box-content nopadding">
                            <table class="table table-bordered table-striped table-highlight" id="hotSpotTable">
                                <thead>
                                <tr>
                                    <th>Hot Spot Name</th>
                                </tr>
                                </thead>
                                <tbody id="hotSpotBody">

                                <?php
                                $tableLength = count($hotSpotRecs);
                                for ($i=0;$i<$tableLength;++$i) {
                                    ?>
                                    <tr>
                                        <td><a href="<?php $patchworks->pwRoot; ?>hotspots/hotspots-edit.php?hot_id=<?php echo $hotSpotRecs[$i]['hot_id']; ?>"><?php echo $hotSpotRecs[$i]['hotnam']; ?></a></td>
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
