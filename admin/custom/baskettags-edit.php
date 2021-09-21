<?php

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("../system/classes/subcategories.cls.php");
require_once("../gallery/classes/gallery.cls.php");
require_once("../website/classes/articles.cls.php");
$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$Cat_ID = (isset($_REQUEST['cat_id']) && is_numeric($_REQUEST['cat_id'])) ? $_REQUEST['cat_id'] : NULL;
$Sub_ID = (isset($_REQUEST['sub_id']) && is_numeric($_REQUEST['sub_id'])) ? $_REQUEST['sub_id'] : NULL;
$SubDao = new SubDAO();

$ArtDAO = new ArtDAO();
$articles = $ArtDAO->select();

$TmpGal = new GalDAO();

$galleries = $TmpGal->select(NULL, 'WEBGALLERY', NULL, NULL, false);
if (!is_null($Sub_ID)) $SubObj = $SubDao->select(NULL, $Sub_ID, NULL, NULL, true);

?>
<!doctype html>
<html>
<head>
    <title>Activities : <?php echo(isset($SubObj)) ? $SubObj->subnam : 'New Activity'; ?></title>
    <?php include('../webparts/headdata.php'); ?>

    <style>

        .transfereUpload{
            display: none;
        }
        .fileupload-preview {
            background: #e1e1e1;
        }

    </style>

    <script src="js/plugins/fileupload/bootstrap-fileupload.min.js"></script>
    <script src="js/plugins/tinymce/tinymce.min.js"></script>
    <script src="js/plugins/imagesLoaded/jquery.imagesloaded.min.js"></script>
    <script src="js/plugins/masonry/jquery.masonry.min.js"></script>
    <script src="custom/js/baskettags-edit.js"></script>

</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
    <?php include('../webparts/website-left.php'); ?>
    <div id="main">
        <div class="container-fluid">
            <div class="page-header">
                <div class="pull-left">
                    <h1>Activities</h1>
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
                        <a href="custom/baskettags.php">Basket Tags</a>
                        <i class="icon-angle-right"></i>
                    </li>
                    <li>
                        <a>
                            <?php
                            echo(!empty($SubObj->subnam)) ? $SubObj->subnam : 'New Basket Tag';
                            ?>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="row-fluid">
                <div class="span6">
                    <form action="system/subcategories_script.php" id="subCategoriesForm" class="form-horizontal" data-returnurl="custom/baskettags.php">
                    <div class="box box-color box-bordered">
                        <div class="box-title">
                            <h3>
                                <i class="icon-comments"></i> Basket Tags</h3>
                            <div class="actions">
                                <a href="#" id="updateSubCategoryBtn" class="btn btn-mini" rel="tooltip" title="Update"><i class="icon-save"></i></a>
                                <a href="#" id="deleteSubCategoryBtn" class="btn btn-mini" rel="tooltip" title="Delete"><i class="icon-trash"></i></a>
                            </div>
                        </div>
                        <div class="box-content">

                                <input type="hidden" name="sub_id" id="id" value="<?php echo(!empty($SubObj->sub_id)) ? $SubObj->sub_id : '0'; ?>">
                                <input type="hidden" name="cat_id" value="<?php echo $Cat_ID; ?>">
                                <div class="control-group">
                                    <label class="control-label">Tag Name</label>
                                    <div class="controls">
                                        <input type="text" class="input-block-level" name="subnam" value="<?php echo(isset($SubObj->subnam)) ? $SubObj->subnam : 'New Tag'; ?>">
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">Tag Text</label>
                                    <div class="controls">

                                        <textarea class="tinymce customfield" rows="20" style="height: 400px;" id="subtxt" name="subtxt">

                                            <?php if (isset($SubObj->subtxt)) echo $patchworks->getJSONVariable($SubObj->subtxt, 'subtxt', false); ?>

                                        </textarea>

                                    </div>
                                </div>

                                <div class="control-group hide">
                                    <label for="textfield" class="control-label">Header Image</label>
                                    <div class="controls">
                                        <div class="fileupload fileupload-new" data-provides="fileupload">
                                            <div class="fileupload-new thumbnail" style="max-width: 200px; max-height: 180px; background: #cecece">

                                                <?php
                                                $SubImg = (isset($SubObj->subtxt)) ? $patchworks->getJSONVariable($SubObj->subtxt, 'subimg', false) : '';
                                                ?>

                                                <?php

                                                if (
                                                    isset($SubImg) &&
                                                    file_exists($patchworks->docRoot . 'uploads/images/' . $SubImg) &&
                                                    !is_dir($patchworks->docRoot . 'uploads/images/' . $SubImg)
                                                ) {
                                                    echo '<img src="../uploads/images/' . $SubImg . '" class="img-responsive" />';
                                                } else {
                                                    echo '<img src="http://www.placehold.it/200x180/EFEFEF/AAAAAA&text=no+image" />';
                                                }
                                                ?>

                                            </div>

                                            <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                            <div>
                                                <span class="btn btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span><input type="file" name='logofile2' id="logofile2" /></span>
                                                <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                                            </div>

                                            <input type="hidden" name="subimg" class="customfield" value="<?php echo $SubImg; ?>">

                                        </div>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">Image Text</label>
                                    <div class="controls">

                                        <input type="text" class="input-block-level customfield" name="imgtxt" value="<?php if (isset($SubObj->subtxt)) echo $patchworks->getJSONVariable($SubObj->subtxt, 'imgtxt', false); ?>">

                                    </div>
                                </div>


                                <div class="control-group">
                                    <label class="control-label">SEO URL</label>
                                    <div class="controls">
                                        <input type="text" class="input-block-level customfield" name="seourl" value="<?php echo(isset($SubObj->seourl)) ? $SubObj->seourl : 'new-tag'; ?>">
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">Status<small>status</small></label>
                                    <div class="controls">
                                        <label class="radio">
                                            <input type="radio" name="sta_id" id="Sta_ID0" value="0" <?php echo(!isset($SubObj->sta_id) || isset($SubObj->sta_id) && $SubObj->sta_id == 0) ? 'checked' : ''; ?>>
                                            Active</label>
                                        <label class="radio">
                                            <input type="radio" name="sta_id" id="Sta_ID1" value="1" <?php echo(isset($SubObj->sta_id) && $SubObj->sta_id == 1) ? 'checked' : ''; ?>>
                                            Inactive </label>
                                    </div>
                                </div>


                        </div>
                    </div>




                    </form>
                </div>
                <div class="span6">
                    <div class="box box-color box-bordered">
                        <div class="box-title">
                            <h3>Home Page Content</h3>
                        </div>
                        <div class="box-content">

                            <div class="control-group">
                                <label class="control-label">Home Page Title</label>
                                <div class="controls">
                                    <input type="text" class="input-block-level customfield" name="homttl" value="<?php if (isset($SubObj->subtxt)) echo $patchworks->getJSONVariable($SubObj->subtxt, 'homttl', false); ?>">
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Home Page Summary</label>
                                <div class="controls">

                                    <textarea name="homsum" cols="30" rows="10" class="input-block-level customfield"><?php if (isset($SubObj->subtxt)) echo $patchworks->getJSONVariable($SubObj->subtxt, 'homsum', false); ?></textarea>

                                </div>
                            </div>

                            <div class="control-group hide">
                                <label for="textfield" class="control-label"></label>
                                <div class="controls">
                                    <div class="fileupload fileupload-new" data-provides="fileupload">
                                        <div class="fileupload-new thumbnail" style="max-width: 200px; max-height: 180px; background: #cecece">

                                            <?php $HomImg = (isset($SubObj->subtxt)) ? $patchworks->getJSONVariable($SubObj->subtxt, 'homimg', false) : ''; ?>

                                            <?php

                                            if (
                                                    isset($HomImg) &&
                                                    file_exists($patchworks->docRoot . 'uploads/images/' . $HomImg) &&
                                                    !is_dir($patchworks->docRoot . 'uploads/images/' . $HomImg)
                                            ) {
                                                echo '<img src="../uploads/images/' . $HomImg . '" class="img-responsive" />';
                                            } else {
                                                echo '<img src="http://www.placehold.it/200x180/EFEFEF/AAAAAA&text=no+image" />';
                                            }
                                            ?>

                                        </div>

                                        <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                        <div>
                                            <span class="btn btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span><input type="file" name='logofile3' id="logofile3" /></span>
                                            <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                                        </div>

                                        <input type="hidden" name="homimg" class="customfield" value="<?php echo $HomImg; ?>">

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div id="galleryImagesDiv">
                        <div class="box">
                            <div class="box-title">
                                <h3>
                                    <i class="icon-picture"></i> Basket Tag Images </h3>


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
                    <div class="box-title">
                        <h4>Image Order Information</h4>
                        <p>
                            Image 1. Home Page Image (600x600)
                        </p>
                        <p>
                            Image 2. Header Image
                        </p>
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
                <button type="submit" class="btn btn-primary" name="action" value="update" id="updateImageBtn"><i class="icon-save"></i> Update</button>
            </div>
        </form>
    </div>
</body>
</html>
