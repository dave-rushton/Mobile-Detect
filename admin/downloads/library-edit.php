<?php
require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("../gallery/classes/gallery.cls.php");

//$userAuth = new AuthDAO();
//$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
//if ($loggedIn == 0) header('location: ../login.php');

$TmpGal = new GalDAO();
$editLibraryID = (isset($_GET['gal_id']) && is_numeric($_GET['gal_id'])) ? $_GET['gal_id'] : NULL;
$libraryRec = NULL;
if (!is_null($editLibraryID)) $libraryRec = $TmpGal->select($editLibraryID, NULL, NULL, NULL, true);

?>
<!doctype html>
<html>
    <head>
        <title>Edit Library</title>
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
        <script src="downloads/js/library-edit.js"></script>
    </head>
    <?php include('../webparts/navigation.php'); ?>
    <body class="theme-red">
        <div class="container-fluid" id="content">
            <?php include('../webparts/website-left.php'); ?>
            <div id="main">
                <div class="container-fluid">
                    <div class="page-header">
                        <div class="pull-left">
                            <h1>Website Library</h1>
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
                                <a href="downloads/libraries.php">Libraries</a>
                            </li>
                        </ul>
                    </div>
                    <div class="row-fluid">
                        <div class="span4">
                            <div class="box box-color box-bordered">
                                <div class="box-title">
                                    <h3>
                                        <i class="icon-cloud-download"></i> Website Library</h3>
                                    <div class="actions">
                                        <a href="#" id="updateLibraryBtn" class="btn btn-mini" rel="tooltip" title="Update"><i class="icon-save"></i></a>
                                        <a href="#" id="deleteLibraryBtn" data-type="warning" class="btn btn-mini" rel="tooltip" title="Delete"><i class="icon-trash"></i></a>
                                    </div>
                                </div>
                                <div class="box-content">
                                    <form class="form-vertical form-validate form-bordered" method="POST" action="gallery/gallery_script.php" id="libraryForm" data-returnurl="downloads/libraries.php">
                                        <input type="hidden" name="gal_id" id="id" value="<?php echo ($libraryRec) ? $libraryRec->gal_id : 0; ?>" />
                                        <div class="control-group hide">
                                            <label class="control-label">Table Name</label>
                                            <div class="controls">
                                                <input type="text" class="input-block-level" name="tblnam" value="<?php echo ($libraryRec) ? 'DOWNLOAD' : 'DOWNLOAD'; ?>">
                                            </div>
                                        </div>
                                        <div class="control-group hide">
                                            <label class="control-label">Table ID</label>
                                            <div class="controls">
                                                <input type="text" class="input-block-level" name="tbl_id" value="<?php echo ($libraryRec) ? $libraryRec->tbl_id : ''; ?>">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">Library Name<small>identifying name for
                                                    library</small></label>
                                            <div class="controls">
                                                <input type="text" class="input-block-level" name="galnam" data-rule-required="true" data-rule-minlength="2" placeholder="Library Name" value="<?php echo ($libraryRec) ? $libraryRec->galnam : ''; ?>">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">SEO friendly URL<small>browser URL
                                                    (W/A)</small></label>
                                            <div class="controls">
                                                <input type="text" class="input-block-level" name="seourl" placeholder="Search Engine Friendly URL" value="<?php echo ($libraryRec) ? $libraryRec->seourl : ''; ?>">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">Keywords</label>
                                            <div class="controls">
                                            <span class="help-block">
                                            keywords to help search engines
                                            </span>
                                                <textarea name="keywrd"><?php echo ($libraryRec) ? $libraryRec->keywrd : ''; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">Description<small>search engines
                                                    description</small></label>
                                            <div class="controls">
                                                <textarea name="keydsc"><?php echo ($libraryRec) ? $libraryRec->keydsc : ''; ?></textarea>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="span8">

                            <div class="box">
                                <div class="box-title">
                                    <h3>
                                        <i class="icon-book"></i> Library Files </h3>
                                </div>
                                <div class="box-content nopadding">

                                    <table class="table table-bordered table-striped table-highlight" id="userTable">
                                        <thead>
                                            <tr>
                                                <th>File Name</th>
                                                <th style="width: 120px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="libraryFiles">

                                        </tbody>
                                    </table>


                                    <ul class="tiles" id="libraryImages">

                                    </ul>
                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="box" <?php // echo($libraryRec) ? '' : 'style="display: none;"'; ?>>
                                <div class="box-title">
                                    <h3><i class="icon-th"></i> Multi File upload</h3>
                                    <div class="actions">
                                        <a class="btn btn-mini content-slideUp" href="#">
                                            <i class="icon-angle-down"></i> </a>
                                    </div>
                                </div>
                                <div class="box-content nopadding">
                                    <div id="plupload">
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
            <form action="library/upload_script.php" id="imageForm" class="form-horizontal" novalidate>
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