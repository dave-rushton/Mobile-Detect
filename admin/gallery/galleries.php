<?php
require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("classes/gallery.cls.php");
$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id'], "website:galleries");

if ($loggedIn == 0) header('location: ../login.php');

$TmpGal = new GalDAO();
$galleries = $TmpGal->select(NULL, 'WEBGALLERY', NULL, NULL, false);

?>
<!doctype html>
<html>
    <head>
        <title>Gallery Listing</title>
        <?php include('../webparts/headdata.php'); ?>
        <script src="js/plugins/datatable/jquery.dataTables.min.js"></script>
        <script src="js/plugins/datatable/TableTools.min.js"></script>
        <script src="js/plugins/datatable/ColReorder.min.js"></script>
        <script src="js/plugins/datatable/ColVis.min.js"></script>
        <script src="js/plugins/datatable/jquery.dataTables.columnFilter.js"></script>
        <script src="js/plugins/datepicker/bootstrap-datepicker.js"></script>
        <script src="js/system.date.js"></script>
        <script src="gallery/js/galleries.js"></script>
    </head>
    <?php include('../webparts/navigation.php'); ?>
    <body class="theme-red">
        <div class="container-fluid" id="content">
            <?php include('../webparts/website-left.php'); ?>
            <div id="main">
                <div class="container-fluid">
                    <div class="page-header">
                        <div class="pull-left">
                            <h1>Website Galleries</h1>
                        </div>
                        <div class="pull-right">
                            <?php include('../webparts/index-info.php'); ?>
                        </div>
                    </div>
                    <div class="breadcrumbs">
                        <ul>
                            <li>
                                <a href="index.php">Dashboard</a> <i class="icon-angle-right"></i>
                            </li>
                            <li>
                                <a>Website</a> <i class="icon-angle-right"></i>
                            </li>
                            <li>
                                <a href="gallery/galleries.php">Galleries</a>
                            </li>
                        </ul>
                    </div>
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="box box-color box-bordered">
                                <div class="box-title">
                                    <h3><i class="icon-picture"></i> Website Galleries</h3>
                                    <div class="actions">
                                        <a href="gallery/gallery-edit.php" class="btn btn-mini" rel="tooltip" title="New Gallery"><i class="icon-plus"></i></a>
                                    </div>
                                </div>

                                <div class="box-content nopadding">

                                    <table class="table table-nomargin table-striped" id="galleriesTable">
                                        <thead>
                                            <tr>
                                                <th style="width: 160px">#</th>
                                                <th style="width: 160px">#</th>
                                                <th style="width: 160px">Gallery Name</th>
                                                <th>Gallery Sizes</th>
                                            </tr>
                                        </thead>
                                        <tbody id="galleriesBody">

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
