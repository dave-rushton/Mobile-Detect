<?php

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once('../products/classes/structure.cls.php');

require_once("../attributes/classes/attrgroups.cls.php");
require_once("../attributes/classes/attrlabels.cls.php");
require_once("../attributes/classes/attrvalues.cls.php");
require_once("../gallery/classes/gallery.cls.php");
$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpStr = new StrDAO();

ini_set('xdebug.max_nesting_level', 4000);

$TmpAtr = new AtrDAO();
$attrGroups = $TmpAtr->select(NULL, 'PRODUCTGROUP');
$productTypeAttr = $TmpAtr->select(NULL, 'PRODUCTTYPE', NULL, NULL, true, NULL, NULL, NULL);

$TmpGal = new GalDAO();
$galleries = $TmpGal->select(NULL, 'WEBGALLERY', NULL, NULL, false);

?>
<!doctype html>
<html>
    <head>
        <style>
            .colorbox-image, .deleteUpload {
                display: none !important;
            }

            #galleryImages .deleteUpload {
                display: inline-block !important;
            }
        </style>
        <title>Shop Structure</title>
        <?php include('../webparts/headdata.php'); ?>


        <!-- colorbox -->
        <link rel="stylesheet" href="css/plugins/colorbox/colorbox.css">

        <style>

            #buildStructure {

                min-height: 100px;

            }

            #buildStructure ul {
                /*margin: 0;*/
                /*padding: 0;*/
                margin-top: 10px;
            }

            #buildStructure ul li {
                /*margin: 0;*/
                /*padding: 0;*/
                list-style: none;
                margin-bottom: 10px;
            }

            #buildStructure ul li .btn {
                display: inline-block;
                margin-right: 5px;
            }

            #subStructure {
                margin: 0;
                padding: 0;
            }

            #subStructure li {
                margin: 0;
                padding: 0;
                list-style: none;
            }

            #subStructure li {
                border-bottom: solid 1px #ccc;
                padding: 3px 0;
                cursor: ns-resize;
            }

        </style>

        <!-- dataTables -->
        <!--<script src="js/plugins/datatable/jquery.dataTables.min.js"></script>-->
        <!--<script src="js/plugins/datatable/TableTools.min.js"></script>-->
        <!--<script src="js/plugins/datatable/ColReorder.min.js"></script>-->
        <!--<script src="js/plugins/datatable/ColVis.min.js"></script>-->
        <!--<script src="js/plugins/datatable/jquery.dataTables.columnFilter.js"></script>-->
        <script src="website/js/selectnavpreview.js"></script>

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

    </head>
    <?php include('../webparts/navigation.php'); ?>
    <body>
        <div class="container-fluid" id="content">

            <div id="main">
                <div class="container-fluid">
                    <div class="page-header">
                        <div class="pull-left">
                            <h1>Structure</h1>
                        </div>
                        <div class="pull-right">
                            <?php include('../webparts/website-left.php'); ?>
                        </div>
                    </div>
                    <div class="breadcrumbs">
                        <ul>
                            <li>
                                <a href="index.php">Dashboard</a> <i class="icon-angle-right"></i>
                            </li>
                            <li>
                                <a>Structure</a>
                            </li>
                        </ul>
                    </div>

                    <div class="row-fluid">
                        <div class="span4">
                            <div class="box box-color box-bordered">
                                <div class="box-title">
                                    <h3>
                                        <i class="icon-checkbox"></i> Create Main Category</h3>
                                    <div class="actions">
                                        <!--								<a href="#" class="btn btn-mini" id="createStructureBtn"><i class="icon-save"></i></a>-->
                                        <a href="#" class="btn btn-mini" id="createStructureBtn"><i class="icon-plus-sign"></i></a>
                                    </div>
                                </div>
                                <!--						<div class="box-content nopadding">-->
                                <!--							<form action="products/structure_script.php" id="structureForm" class="form-horizontal form-bordered">-->
                                <!--								<input type="hidden" name="str_id" value="0">-->
                                <!--								<input type="hidden" name="sta_id" value="0">-->
                                <!--								<input type="hidden" name="tblnam" value="">-->
                                <!--								<input type="hidden" name="tbl_id" value="0">-->
                                <!--								<div class="control-group">-->
                                <!--									<label class="control-label">Name</label>-->
                                <!--									<div class="controls">-->
                                <!--										<input type="text" class="input-large" name="strnam" value="">-->
                                <!--									</div>-->
                                <!--								</div>-->
                                <!--							</form>-->
                                <!--						</div>-->
                                <div class="box-content">

                                    <div id="buildStructure">
                                        <?php
                                        //$TmpStr->buildStructure(0,NULL, true);
                                        ?>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="span8">

                            <div class="box box-color">
                                <div class="box-title">
                                    <h3>
                                        <i class="icon-reorder"></i> Structure Detail
                                    </h3>
                                    <ul class="tabs">
                                        <li class="">
                                            <a href="#t7" data-toggle="tab">Images</a>
                                        </li>
                                        <li class="active">
                                            <a href="#t8" data-toggle="tab">Products</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="box-content">
                                    <div class="tab-content">
                                        <div class="tab-pane" id="t7">

                                            <div class="row-fluid">
                                                <div class="span12">
                                                    <ul class="gallery gallery-dynamic" id="galleryImages">

                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="row-fluid">

                                                <div class="span12">
                                                    <div id="uploadImagesDiv" style="display: none;">
                                                        <div class="box-title" style="margin-top: 0;">
                                                            <div class="actions">
                                                                <a href="#" id="updateGalleryImagesBtn" style="display: block" class="btn btn-mini" rel="tooltip" title="" data-original-title="Update"><i class="icon-save"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="box" id="productimagepicker">
                                                            <div class="box-content">
                                                                <form class="form-vertical form-validate form-bordered" method="POST" id="gallerySearchForm" novalidate="novalidate">
                                                                    <input name="img_id" type="hidden">
                                                                    <input name="imginp" type="hidden">
                                                                    <div class="control-group">
                                                                        <label class="control-label">Image <small>Choose
                                                                                an image </small> </label>
                                                                        <div class="controls">
                                                                            <div class="input-append">
                                                                                <select id="action" class="input-large">
                                                                                    <option value="gallery">Galleries
                                                                                    </option>
                                                                                    <option value="article">Articles
                                                                                    </option>
                                                                                    <option value="event">Events
                                                                                    </option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="input-append gal-options" id="gallery-select">
                                                                                <select name="gal_id1" class="input-large">
                                                                                    <option value="0">Search Global
                                                                                        Gallery
                                                                                    </option>
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
                                                                        <label class="control-label">Keywords <small>search
                                                                                gallery images</small> </label>
                                                                        <div class="controls">
                                                                            <div class="input-append">
                                                                                <input name="keywrd" id="keywrd1" placeholder="Keyword Search..." class="input-large" type="text">
                                                                                <button class="btn" type="submit">
                                                                                    <i class="icon-search"></i></button>
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
                                                    <div id="plupload">
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="tab-pane active" id="t8">


                                            <ul id="subStructure">
                                            </ul>


                                        </div>

                                    </div>
                                </div>
                            </div>


                        </div>

                    </div>

                </div>
            </div>
        </div>


        <div class="modal hide fade" id="structureModal">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3>Shop Structure</h3>
            </div>

            <form id="structureModalForm" class="form-horizontal form-bordered form-validate">

                <input type="hidden" name="str_id" value="0"> <input type="hidden" name="tblnam" value="">
                <input type="hidden" name="tbl_id" value="0">

                <div class="modal-body" style="padding: 0">

                    <div class="control-group">
                        <label class="control-label">Parent</label>
                        <div class="controls">

                            <div id="modalStructure">
                                <?php
                                $TmpStr->buildStructure(NULL, NULL, 'parentID', 'hide', true);
                                ?>
                            </div>

                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Status</label>
                        <div class="controls">

                            <select name="sta_id">
                                <option value="0">Normal</option>
                                <option value="1">Offline</option>
                                <option value="2">No submenu</option>
                                <option value="3">No submenu and no link</option>
                                <option value="4">No link (with submenu)</option>
                            </select>

                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Name</label>

                        <div class="controls">
                            <input type="text" class="input-block-level" name="strnam" value="" required>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">SEO Title <small></small> </label>

                        <div class="controls">
                            <input type="text" class="input-block-level customfield" name="seotitle" value="" required>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Description</label>

                        <div class="controls">
                            <input type="text" class="input-block-level customfield"
                                   name="strdsc"
                                   value="">
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">SEO URL </label>

                        <div class="controls">
                            <input type="text" class="input-block-level" name="seourl" value="" required>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Title <small></small> </label>
                        <div class="controls">
                            <input type="text" class="input-block-level customfield" name="title" value="" required>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Keywords </label>

                        <div class="controls">
                            <input type="text" class="input-block-level" name="keywrd" value="">
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Key Description</label>
                        <div class="controls">
                            <textarea class="input-block-level" name="keydsc" value=""></textarea>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Sort Order </label>

                        <div class="controls">
                            <input type="text" class="input-block-level" name="srtord" value="" required>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Group <small>select the product group</small> </label>

                        <div class="controls">
                            <select name="tbl_id">

                                <option value="0">N/A</option>

                                <?php
                                $tableLength = count($attrGroups);
                                for ($i = 0; $i < $tableLength; ++$i) {
                                    ?>
                                    <option
                                            value="<?php echo $attrGroups[$i]['atr_id']; ?>"><?php echo $attrGroups[$i]['atrnam']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>


                    <div class="control-group">
                        <label class="control-label">Quick Launch <small></small> </label>

                        <div class="controls">
                            <label class="checkbox"> <input type="checkbox" name="quickl" class="customfield" value="1">
                                Add drop down to breadcrumb? </label>
                        </div>
                    </div>


                    <div class="control-group">
                        <label class="control-label">Screen Type <small></small> </label>

                        <div class="controls">
                            <select name="dsptyp" class="customfield">
                                <option value="0">Default</option>
                                <option value="1">Grid</option>
                            </select>
                        </div>
                    </div>


                    <div class="control-group">
                        <label class="control-label">H1 <small></small> </label>
                        <div class="controls">
                            <input type="text" class="input-block-level customfield" name="h1" value="" required>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Top Text <small></small> </label>

                        <div class="controls">
                            <textarea class="input-block-level customfield" name="toptext" value=""></textarea>

                        </div>
                    </div>

                </div>


                <div class="box" style="display: none;">
                    <div class="box-title">
                        <h3>
                            <i class="icon-comments"></i> Language
                        </h3>
                        <ul class="tabs">
                            <li class="active">
                                <a href="#french" class="changelanguagelink" data-toggle="tab">French</a>
                            </li>
                            <li>
                                <a href="#german" class="changelanguagelink" data-toggle="tab">German</a>
                            </li>
                            <li class="">
                                <a href="#spanish" class="changelanguagelink" data-toggle="tab">Spanish</a>
                            </li>
                        </ul>
                    </div>
                    <div class="box-content nopadding">

                        <div class="tab-content">
                            <div class="tab-pane active" id="french">

                                <div class="control-group">
                                    <label class="control-label">French Name</label>

                                    <div class="controls">
                                        <input type="text" class="input-block-level customfield"
                                               name="fr_strnam"
                                               value="">
                                    </div>
                                </div>

                            </div>

                            <div class="tab-pane" id="german">

                                <div class="control-group">
                                    <label class="control-label">German Name</label>

                                    <div class="controls">
                                        <input type="text" class="input-block-level customfield"
                                               name="ge_strnam"
                                               value="">
                                    </div>
                                </div>

                            </div>
                            <div class="tab-pane" id="spanish">

                                <div class="control-group">
                                    <label class="control-label">Spanish Name</label>

                                    <div class="controls">
                                        <input type="text" class="input-block-level customfield"
                                               name="sp_strnam"
                                               value="">
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>


                <div class="modal-footer">
                    <a href="#" class="btn" data-dismiss="modal"><i class="icon-remove"></i> Cancel</a>
                    <button type="submit" class="btn btn-primary" name="action" value="update" id="updateStructureBtn">
                        <i
                                class="icon-save"></i> Update
                    </button>
                </div>

            </form>


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

        <script src="products/js/structure.js"></script>

    </body>
</html>
