<?php
require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("classes/gallery.cls.php");
require_once("classes/uploads.cls.php");
require_once("../website/classes/articles.cls.php");
require_once("../system/classes/places.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpGal = new GalDAO();
$editGalleryID = (isset($_GET['gal_id']) && is_numeric($_GET['gal_id'])) ? $_GET['gal_id'] : NULL;
$galleryRec = NULL;

if (!is_null($editGalleryID)) $galleryRec = $TmpGal->select($editGalleryID, NULL, NULL, NULL, true);
$TmpUpl = new UplDAO();
$uploads = $TmpUpl->select(NULL, NULL, NULL, NULL, false);
$TmpGal = new GalDAO();
$galleries = $TmpGal->select(NULL, 'WEBGALLERY', NULL, NULL, false);
$TmpArt = new ArtDAO();
$articles = $TmpArt->select(NULL, NULL, NULL, NULL, false);
$TmpPla = new PlaDAO();
$events = $TmpPla->select(NULL, 'EVENT', NULL, NULL, false);

?>
<!doctype html>
<html>
    <head>
        <title>Edit Gallery</title>
        <?php include('../webparts/headdata.php'); ?>
        <!-- colorbox -->
        <link rel="stylesheet" href="css/plugins/colorbox/colorbox.css">
        <link rel="stylesheet" href="gallery/css/style.css">
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
        <script src="gallery/js/gallery-edit.js"></script>
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
                                <a href="gallery/galleries.php">Galleries</a> <i class="icon-angle-right"></i>
                            </li>
                            <li>
                                <a>Gallery</a>
                            </li>
                        </ul>
                    </div>
                    <div class="row-fluid">
                        <div class="span4">
                            <div class="box box-color box-bordered">
                                <div class="box-title">
                                    <h3>
                                        <i class="icon-picture"></i> Website Gallery</h3>
                                    <div class="actions">
                                        <a href="#" id="updateGalleryBtn" class="btn btn-mini" rel="tooltip" title="Update"><i class="icon-save"></i></a>
                                        <a href="#" id="deleteGalleryBtn" data-type="warning" class="btn btn-mini" rel="tooltip" title="Delete"><i class="icon-trash"></i></a>
                                    </div>
                                </div>
                                <div class="box-content nopadding">
                                    <form class="form-vertical form-validate form-bordered" method="POST" action="gallery/gallery_script.php" id="galleryForm" data-returnurl="gallery/galleries.php" data-imgsiz="<?php echo $patchworks->galleryImageSizes; ?>">
                                        <input type="hidden" name="gal_id" id="id" value="<?php echo ($galleryRec) ? $galleryRec->gal_id : 0; ?>" />
                                        <div class="control-group hide">
                                            <label class="control-label">Table Name</label>
                                            <div class="controls">
                                                <input type="text" class="input-block-level" name="tblnam" value="<?php echo ($galleryRec) ? 'WEBGALLERY' : 'WEBGALLERY'; ?>">
                                            </div>
                                        </div>
                                        <div class="control-group hide">
                                            <label class="control-label">Table ID</label>
                                            <div class="controls">
                                                <input type="text" class="input-block-level" name="tbl_id" value="<?php echo ($galleryRec) ? $galleryRec->gal_id : ''; ?>">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">Gallery Name<small>identifying name for
                                                    gallery</small></label>
                                            <div class="controls">
                                                <input type="text" class="input-block-level" name="galnam" data-rule-required="true" data-rule-minlength="2" placeholder="Gallery Name" value="<?php echo ($galleryRec) ? $galleryRec->galnam : ''; ?>">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">SEO friendly URL<small>browser URL
                                                    (W/A)</small></label>
                                            <div class="controls">
                                                <input type="text" class="input-block-level" name="seourl" placeholder="Search Engine Friendly URL" value="<?php echo ($galleryRec) ? $galleryRec->seourl : ''; ?>">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">Image Sizes<small>comma seperated list of
                                                    hyphen seperated (width-height) image sizes</small></label>
                                            <div class="controls">
                                                <div class="input-append">
                                                    <input type="text" name="imgsiz" placeholder="Image Sizes (100-100,200-100)" class="input-large" value="<?php echo ($galleryRec) ? $galleryRec->imgsiz : ''; ?>">
                                                    <button class="btn" type="button" id="rebuildGalleryBtn">Rebuild
                                                        Gallery <i class="icon icon-chevron-right"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">Keywords<small>keywords to help search
                                                    engines</small></label>
                                            <div class="controls">
                                                <textarea name="keywrd" class="input-block-level"><?php echo ($galleryRec) ? $galleryRec->keywrd : ''; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">Description<small>search engines
                                                    description</small></label>
                                            <div class="controls">
                                                <textarea name="keydsc" class="input-block-level"><?php echo ($galleryRec) ? $galleryRec->keydsc : ''; ?></textarea>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="span8">
                            <div id="galleryImagesDiv">
                                <div class="box">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-picture"></i> Gallery Images </h3>
                                        <ul class="tabs">
                                            <li class="active">
                                                <a href="#t1" data-toggle="tab">Thumbnails</a>
                                            </li>
                                            <li class="">
                                                <a href="#t2" data-toggle="tab">List</a>
                                            </li>
                                        </ul>
                                        <div class="actions">
                                            <div id="rebuildBtnWrapper" style="display:none">
                                                <a href="#" id="rebuildAllBtn" class="btn btn-mini" rel="tooltip" title="Rebuild All Images"><i class="icon-check">All
                                                        Images</i></a>
                                                <a href="#" id="confirmRebuildBtn" class="btn btn-mini" rel="tooltip" title="Rebuild"><i class="icon-ok"></i></a>
                                                <a href="#" id="cancelRebuildBtn" class="btn btn-mini" rel="tooltip" title="Cancel Rebuild""><i class="icon-remove"></i></a>
                                            </div>
                                            <a href="#" id="addImageBtn" class="btn btn-mini" rel="tooltip" title="Add Images" style="display: none"><i class="icon-plus"></i></a>
                                        </div>
                                    </div>
                                    <div class="box-content">
                                        <div id="t1" class="listbox">
                                            <ul class="gallery gallery-dynamic" id="galleryImages">
                                            </ul>
                                        </div>
                                    </div>
                                    <div id="t2" class="listbox">
                                        <div class="row-fluid">
                                            <a href="#" id="updateList" class="btn btn-primary pull-right" rel="tooltip" title="" data-original-title="Update All"><i class="icon-save"></i></a>
                                        </div>

                                        <ul class="gallery gallery-dynamic" id="galleryListImages">
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
                            <div id="uploadImagesDiv" style="display: none;">
                                <div class="box">
                                    <div class="box-title">
                                        <h3>
                                            <i class="icon-picture"></i> Add Images To Gallery</h3>
                                        <div class="actions">
                                            <a href="#" id="updateGalleryImagesBtn" class="btn" rel="tooltip" title="Update"><i class="icon-save"></i></a>
                                            <a href="#" id="cancelGalleryImagesBtn" class="btn" rel="tooltip" title="Cancel"><i class="icon-remove"></i></a>
                                        </div>
                                    </div>
                                    <div class="box-content">
                                        <form class="form-vertical form-validate form-bordered" method="POST" id="gallerySearchForm" novalidate="novalidate">
                                            <input name="img_id" type="hidden"> <input name="imginp" type="hidden">
                                            <div class="control-group">
                                                <label class="control-label">Keywords <small>search gallery
                                                        images</small> </label>
                                                <div class="controls">
                                                    <div class="input-append">
                                                        <select id="action" class="input-large">
                                                            <option value="gallery">Galleries</option>
                                                            <option value="article">Articles</option>
                                                            <option value="event">Events</option>
                                                        </select>
                                                    </div>
                                                    <div class="input-append gal-options" id="gallery-select">
                                                        <select name="gal_id1" class="input-large">
                                                            <option value="0">Search Global Gallery</option>
                                                            <?php
                                                            $tableLength = count($galleries);
                                                            for ($i = 0; $i < $tableLength; ++$i) {
                                                                ?>
                                                                <option
                                                                        value="<?php echo $galleries[$i]['gal_id']; ?>"><?php echo $galleries[$i]['galnam']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>

                                                    <div class="input-append gal-options" id="article-select" style="display:none;">
                                                        <select name="gal_id1" class="input-large">
                                                            <?php
                                                            $tableLength = count($articles);
                                                            for ($i = 0; $i < $tableLength; ++$i) {
                                                                ?>
                                                                <option
                                                                        value="<?php echo $articles[$i]['art_id']; ?>"><?php echo $articles[$i]['artttl']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>

                                                    <div class="input-append gal-options" id="event-select" style="display:none;">
                                                        <select name="gal_id1" class="input-large">
                                                            <?php
                                                            $tableLength = count($events);
                                                            for ($i = 0; $i < $tableLength; ++$i) {
                                                                ?>
                                                                <option
                                                                        value="<?php echo $events[$i]['pla_id']; ?>"><?php echo $events[$i]['planam']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label">Keywords <small>search gallery
                                                        images</small> </label>
                                                <div class="controls">
                                                    <div class="input-append">
                                                        <input name="keywrd" id="keywrd1" placeholder="Keyword Search..." class="input-large" type="text">
                                                        <button class="btn" type="submit"><i class="icon-search"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="control-group selfclear">
                                                <ul class="gallery gallery-dynamic masonry" id="searchCoverImagelisting" style="position: relative; height: 0px;">
                                                </ul>
                                            </div>
                                        </form>
                                        <ul class="gallery gallery-dynamic" id="imagelisting">
                                        </ul>
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
                            <label class="control-label">Quote From</label>
                            <div class="controls">
                                <input type="text" class="input-block-level customfield" name="quotefrom">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">Quote From Position</label>
                            <div class="controls">
                                <input type="text" class="input-block-level customfield" name="quotepos">
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
        <div class="modal hide fade" id="transfereModal">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3>Move to another Gallery</h3>
            </div>
            <form action="gallery/upload_script.php" name="transfereForm" id="transfereForm" class="form-horizontal" novalidate>
                <input type="hidden" name="upl_id" />
                <div class="modal-body">
                    <fieldset>
                        <div class="control-group">
                            <label class="control-label">Gallery</label>
                            <div class="controls">
                                <select name="gal_id" id="">
                                    <?php foreach ($galleries as $Lst) {
                                        echo "<option value='" . $Lst['gal_id'] . "'>" . $Lst['galnam'] . "</option>";
                                    } ?>
                                </select>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn" data-dismiss="modal"><i class="icon-remove"></i> Cancel</a>
                    <button type="submit" class="btn btn-primary" name="action" value="update" id="updateTransfereBtn">
                        <i class="icon-save"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </body>
</html>