<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("../gallery/classes/gallery.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpGal = new GalDAO();
$galleries = $TmpGal->select(NULL, 'DOWNLOAD', NULL, NULL, false);

?>
<!doctype html>
<html>
<head>
<title>Library Listing</title>
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
                        <h1>Website Libraries</h1>
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
                            <a href="downloads/libraries.php">Libraries</a>
                        </li>
                    </ul>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                        <div class="box box-color box-bordered">
                            <div class="box-title">
                                <h3>
                                    <i class="icon-cloud-download"></i> Website Libraries</h3>
                                <div class="actions">
                                    <a href="downloads/library-edit.php" class="btn btn-mini" rel="tooltip" title="New Library"><i class="icon-plus"></i></a>
                                </div>
                            </div>
                            <div class="box-content nopadding">
                                <table class="table table-bordered table-striped table-highlight" id="userTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Library Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $tableLength = count($galleries);
                                    for ($i=0;$i<$tableLength;++$i) {
                                    ?>
                                    <tr>
                                        <td><?php echo $galleries[$i]['gal_id']; ?></td>
                                        <td><a href="<?php $patchworks->pwRoot; ?>downloads/library-edit.php?gal_id=<?php echo $galleries[$i]['gal_id']; ?>"><?php echo $galleries[$i]['galnam']; ?></a></td>
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
