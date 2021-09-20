<?php
require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("classes/gallery.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpGal = new GalDAO();
$galleries = $TmpGal->select(NULL, 'WEBGALLERIES', NULL, NULL, false);

?>
<!doctype html>
<html>
    <head>
        <title>Gallery Listing</title>
        <?php include('../webparts/headdata.php'); ?>

        <script>
            $(function () {

                $('.sortOrder').click(function (e) {
                    e.preventDefault();
                });

                $('#galleryBody').sortable({
                    handle: ".sortOrder",
                    stop: function (event, ui) {

                        var subLst = '';

                        $('.sortOrder', $('#galleryBody')).each(function () {
                            subLst += (subLst == '') ? $(this).data('gal_id') : ',' + $(this).data('gal_id');
                        });

                        $.ajax({
                            url: 'gallery/gallery_script.php',
                            data: 'action=resort&ajax=true&gal_id=' + subLst,
                            type: 'POST',
                            async: false,
                            success: function (data) {

                                var result = JSON.parse(data);

                                $.msgGrowl({
                                    type: result.type
                                    , title: result.title
                                    , text: result.description
                                });

                            },
                            error: function (x, e) {
                                throwAjaxError(x, e);
                            }
                        });

                    }
                }).disableSelection();
            });

        </script>
    </head>

    <?php
    include('../webparts/navigation.php');
    ?>
    <body class="theme-red">
        <div class="container-fluid" id="content">
            <?php
            include('../webparts/website-left.php');
            ?>
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
                                    <h3>
                                        <i class="icon-picture"></i> Website Galleries</h3>
                                    <div class="actions">
                                        <a href="gallery/webgallery-edit.php" class="btn btn-mini" rel="tooltip" title="New Gallery"><i class="icon-plus"></i></a>
                                    </div>
                                </div>
                                <div class="box-content nopadding">
                                    <table class="table table-bordered table-striped table-highlight" id="userTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Gallery Name</th>
                                                <th width="50"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="galleryBody">
                                            <?php
                                            $tableLength = count($galleries);
                                            for ($i = 0; $i < $tableLength; ++$i) {
                                                echo '<tr>';
                                                    echo '<td>';
                                                        echo $galleries[$i]['gal_id'];
                                                     echo '</td>';
                                                    echo '<td>';
                                                        echo '<a href="' . $patchworks->pwRoot . 'gallery/webgallery-edit.php?gal_id=' . $galleries[$i]['gal_id'] . '">';
                                                            echo $galleries[$i]['galnam'];
                                                        echo '</a>';
                                                    echo '</td>';
                                                    echo '<td>';
                                                        echo '<a href="#" class="sortOrder" data-gal_id="' . $galleries[$i]['gal_id'] . '">';
                                                            echo '<i class="icon-reorder"></i>';
                                                        echo '</a>';
                                                    echo '</td>';
                                                echo '</tr>';
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