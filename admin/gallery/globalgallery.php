<?php

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("classes/gallery.cls.php");
require_once("classes/uploads.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id'], "website:globalgalleries");
if ($loggedIn == 0) header('location: ../login.php');

$TmpUpl = new UplDAO();
$uploads = $TmpUpl->select(NULL, 'GLOBAL', 0, NULL, false);

?>

<!doctype html>
<html>
    <head>
        <title>Edit Gallery</title>
        <?php include('../webparts/headdata.php'); ?>

        <!-- colorbox -->
        <link rel="stylesheet" href="css/plugins/colorbox/colorbox.css">

        <!-- colorbox -->
        <script src="js/plugins/colorbox/jquery.colorbox-min.js"></script>
        <!-- masonry -->
        <script src="js/plugins/masonry/jquery.masonry.min.js"></script>
        <!-- imagesloaded -->
        <script src="js/plugins/imagesLoaded/jquery.imagesloaded.min.js"></script>

        <!-- Plupload -->
        <link rel="stylesheet" href="css/plugins/plupload/jquery.plupload.queue.css">
        <!-- PLUpload -->
        <script src="js/plugins/plupload/plupload.full.js"></script>
        <script src="js/plugins/plupload/jquery.plupload.queue.js"></script>

        <script src="gallery/js/globalgallery.js"></script>

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
                            <h1>Global Gallery</h1>
                        </div>
                        <div class="pull-right">
                            <?php 
                                include('../webparts/index-info.php');
                            ?>
                        </div>
                    </div>
                    <div class="breadcrumbs">
                        <ul>
                            <li>
                                <a href="index.php">Dashboard</a> <i class="icon-angle-right"></i>
                            </li>
                            <li>
                                <a>Global Gallery</a>
                            </li>
                        </ul>
                    </div>
                    <div class="row-fluid">


                        <div class="span12">

                            <div id="uploadImagesDiv">

                                <div class="box">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-picture"></i> Global Images</h3>

                                    </div>
                                    <div class="box-content">

                                        <ul class="gallery gallery-dynamic" id="globalimagelisting">

                                        </ul>

                                    </div>
                                </div>

                                <div class="box">
                                    <div class="box-title">
                                        <h3><i class="icon-th"></i> Multi File upload</h3>
                                    </div>
                                    <div class="box-content nopadding">
                                        <div id="plupload" data-resize="<?php echo $patchworks->galleryImageSizes; ?>">
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="modal hide fade" id="imageModal">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3>Image Detail</h3>
            </div>
            <form action="gallery/upload_script.php" id="imageForm" class="form-horizontal" novalidate>
                <input type="hidden" name="upl_id" />
                <div class="modal-body">
                    <fieldset>

                        <div class="control-group">
                            <label class="control-label">Title</label>
                            <div class="controls">
                                <input type="text" class="input-block-level" name="uplttl">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">Description</label>
                            <div class="controls">
                                <textarea class="input-block-level" name="upldsc"></textarea>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">Alt Tag</label>
                            <div class="controls">
                                <input type="text" class="input-block-level" name="alttxt">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">Link</label>
                            <div class="controls">
                                <input type="text" class="input-block-level" name="urllnk">
                            </div>
                        </div>

                    </fieldset>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn" data-dismiss="modal"><i class="icon-remove"></i> Cancel</a>
                    <button type="submit" class="btn btn-primary" name="action" value="update" id="updateImageBtn">
                        <i class="icon-save"></i> Update
                    </button>
                </div>
            </form>
        </div>

    </body>
</html>