<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("classes/articles.cls.php");
require_once("../system/classes/subcategories.cls.php");
require_once("../system/classes/gallery.cls.php");
require_once("classes/pages.cls.php");
$PagDAO = new PagDAO();
$artpag = $PagDAO->select(NULL,NULL,NULL,18);
$arturl="";
if(!empty($articleRec)){

}
$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpArt = new ArtDAO();
$editArticleID = (isset($_GET['art_id']) && is_numeric($_GET['art_id'])) ? $_GET['art_id'] : NULL;
$articleRec = NULL;
if (!is_null($editArticleID)) $articleRec = $TmpArt->select($editArticleID, NULL, NULL, NULL, true); 

$TmpSub = new SubDAO();
$subCategories = $TmpSub->selectByTableName('article-types');
$TmpGal = new GalDAO();
$galleries = $TmpGal->select(NULL, 'WEBGALLERY', NULL, NULL, false);

?>



<!doctype html>
<html>
<head>
<title>Article</title>
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

<link rel="stylesheet" href="css/plugins/datepicker/datepicker.css">
<script src="js/plugins/datepicker/bootstrap-datepicker.js"></script>

<!-- CKEditor -->
<!--<script src="js/plugins/ckeditor/ckeditor.js"></script>-->
<script src="js/plugins/tinymce/tinymce.min.js"></script>

<script src="js/plugins/fileupload/bootstrap-fileupload.min.js"></script>

<script src="website/js/articles-edit.js"></script>
</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
	<?php include('../webparts/website-left.php'); ?>
	<div id="main">
		<div class="container-fluid">
			<div class="page-header">
				<div class="pull-left">
					<h1>Website News</h1>
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
						<a href="website/articles.php">News</a>
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a>News</a>
					</li>
				</ul>
			</div>
			<div class="row-fluid">
				<form class="form-horizontal form-validate form-bordered" method="POST" action="website/articles_script.php" id="articleForm" data-returnurl="website/articles.php">
					<div class="span6">
						<div class="box box-color box-bordered" id="articleBox">
							<div class="box-title">
								<h3>
									<i class="icon-comments"></i> Website News</h3>

								<div class="actions">
									<?php
									if(!empty($articleRec)){
										?>
										<a class="btn" target="_blank" href="<?php echo $patchworks->webRoot.$patchworks->articlesURL."article/".$articleRec->seourl;?>">
											<i class="icon-eye-open icon"></i>
										</a>
										<?php
									}else{

									}
									?>
									<a href="#" id="updateArticleBtn" class="btn btn-mini" rel="tooltip" title="Update"><i class="icon-save"></i></a>
									<a href="#" id="deleteArticleBtn" class="btn btn-mini" rel="tooltip" title="Delete"><i class="icon-trash"></i></a>
								</div>
							</div>
							<div class="box-content nopadding">
								<input type="hidden" name="art_id" id="id" value="<?php echo ($articleRec) ? $articleRec->art_id : '0'; ?>" >
								<input type="hidden" name="arttyp" value="<?php echo ($articleRec) ? $articleRec->arttyp : ''; ?>" >
								<div class="control-group">
									<label class="control-label" for="name">Article Title<small>main article title</small></label>
									<div class="controls">
										<input type="text" class="input-block-level" name="artttl" value="<?php echo ($articleRec) ? $articleRec->artttl : 'New Article'; ?>" required>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="name">Summary<small>short summary displayed in listing</small></label>
									<div class="controls">
										<textarea name="artdsc" class="input-block-level" rows="5"><?php echo ($articleRec) ? $articleRec->artdsc : 'New Article'; ?></textarea>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="name">Published Date<small>published date of article used for archive</small></label>
									<div class="controls">
										<input type="text" class="input-block-level" id="ArtDat" name="artdat" value="<?php echo ($articleRec) ? date("Y-m-d", strtotime($articleRec->artdat)) : date("Y-m-d"); ?>">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Categories<small>check the related categories used for filtering</small></label>
									<div class="controls">
										<?php
										$subCatArr = ($articleRec) ? explode("|", $articleRec->arttyp) : array();
										$tableLength = count($subCategories);
										for ($i=0;$i<$tableLength;++$i) {
											
										?>
										<label class="checkbox">
											<input type="checkbox" name="articleTypeCheckbox" value="<?php echo $subCategories[$i]['sub_id']; ?>" class="articleTypeCheckboxCB" <?php echo ( in_array( $subCategories[$i]['sub_id'], $subCatArr) ) ? 'checked' : ''; ?>>
											<?php echo $subCategories[$i]['subnam']; ?>
										</label>
										<?php } ?>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="name">SEO friendly URL<small>name used in address bar of browser</small></label>
									<div class="controls">
										<input type="text" class="input-block-level" name="seourl" value="<?php echo ($articleRec) ? $articleRec->seourl : 'new-article'; ?>">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="name">Keywords<small>keywords to help search engines</small></label>
									<div class="controls">
										<input type="text" class="input-block-level" name="seokey" value="<?php echo ($articleRec) ? $articleRec->seokey : ''; ?>">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="name">Description<small>page description used on search engines</small></label>
									<div class="controls">
										<textarea name="seodsc" class="input-block-level"><?php echo ($articleRec) ? $articleRec->seodsc : ''; ?></textarea>
									</div>
								</div>
                                <div class="control-group hide">
                                    <label for="textfield" class="control-label">Image <small>W1920px:H800px</small></label>
                                    <div class="controls">
                                        <div class="fileupload fileupload-new" data-provides="fileupload">
                                            <div class="fileupload-new thumbnail" style="max-width: 200px; max-height: 150px;">

                                                <?php

                                                $contentImg = (isset($articleRec->artobj)) ? $patchworks->getJSONVariable($articleRec->artobj, 'cuslogo', true) : '';
                                                if (
                                                    isset($contentImg) &&
                                                    file_exists($patchworks->docRoot . 'uploads/images/website/' . $contentImg) &&
                                                    !is_dir($patchworks->docRoot . 'uploads/images/website/' . $contentImg)
                                                ) {
                                                    echo '<img src="../uploads/images/website/' . $contentImg . '" class="img-responsive" />';
                                                } else {
                                                    echo '<img src="http://www.placehold.it/1920x800/EFEFEF/AAAAAA&text=no+image" />';
                                                }
                                                ?>


                                            </div>

                                            <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                            <div>

                                                <span class="btn btn-file"><span class="fileupload-new">Select image</span>


													<span class="fileupload-exists">Change</span><input type="file" name='logofile' id="logofile" /></span>
                                                <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
												<span class="btn btn-file reset">Remove Cover</span>
                                            </div>

                                            <input type="hidden" name="cuslogo" class="customfield" value="<?php echo($articleRec) ? $contentImg : ''; ?>">

                                        </div>
                                    </div>
                                </div>
								<div class="control-group">
									<label class="control-label" for="name">Status<small> Show or Hide Article</small></label>
									<div class="controls">
										<label class="radio">
											<input name="sta_id" value="0" <?php echo(!$articleRec || $articleRec && $articleRec->sta_id == 0) ? 'checked' : ''; ?> type="radio">
											Active
										</label>
										<label class="radio">

											<input name="sta_id" value="1" <?php echo(!$articleRec || $articleRec && $articleRec->sta_id == 1) ? 'checked' : ''; ?> type="radio">
											In-Active
										</label>
									</div>
								</div>
							</div>
						</div>
						<br class="clear">
					</div>
					<div class="span6">
						<div class="box box-color box-bordered darkblue" id="articleBoxContent">
							<div class="box-title">
								<h3><i class="icon-th"></i> Main Article</h3>
							</div>
							<div class="box-content nopadding">
								<textarea name="arttxt" id="arttxt" class='ckeditor span12' rows="5" style="height: 400px;"><?php echo ($articleRec) ? $articleRec->arttxt : ''; ?></textarea>
							</div>
						</div>
						<br class="clear">
					</div>
					
				</form>
			</div>
			<div class="row-fluid">
				<div class="span12">

                    <div id="galleryImagesDiv">

                        <div class="box">
                            <div class="box-title">
                                <h3>
                                    <i class="icon-picture"></i> Gallery Images </h3>
                                <div class="actions">
                                    <a href="#" id="addImageBtn" class="btn btn-mini" rel="tooltip" title="Add Images" style="display: none"><i class="icon-plus"></i></a>
                                </div>
                            </div>
                            <div class="box-content">

                                <ul class="gallery gallery-dynamic" id="galleryImages">

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
                                    <a href="#" id="updateGalleryImagesBtn" class="btn btn-mini" rel="tooltip" title="Update"><i class="icon-save"></i></a>
                                    <a href="#" id="cancelGalleryImagesBtn" class="btn btn-mini" rel="tooltip" title="Cancel"><i class="icon-remove"></i></a>
                                </div>
                            </div>
                            <div class="box-content">
								<form class="form-vertical form-validate form-bordered" method="POST" id="gallerySearchForm" novalidate="novalidate">
									<input name="img_id" type="hidden">
									<input name="imginp" type="hidden">
									<div class="control-group">
										<label class="control-label">Keywords
											<small>search gallery images</small>
										</label>
										<div class="controls">
											<div class="input-append">
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
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Keywords
											<small>search gallery images</small>
										</label>
										<div class="controls">
											<div class="input-append">
												<input name="keywrd" id="keywrd1" placeholder="Keyword Search..." class="input-large" type="text">
												<button class="btn" type="submit"><i class="icon-search"></i></button>
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


<div class="modal fade hide" id="fbModal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h3>Social Media Actions</h3>
    </div>

        <div class="modal-body">

            <h5>Would you like to post this article to your social networks?</h5>

            <iframe src="" frameborder="0" id="facebookFrame" width="100%" height="50px"></iframe>

            <iframe src="" frameborder="0" id="twitterFrame" width="100%" height="50px"></iframe>

        </div>
        <div class="modal-footer">
            <a href="#" class="btn" data-dismiss="modal"><i class="icon-remove"></i> Cancel</a>
<!--            <a href="#" class="btn btn-primary" id="facebookLink">Post to Facebook</a>-->
        </div>

</div>

<input type="hidden" id="webRoot" value="<?php echo $patchworks->webRoot; ?>">

</body>
</html>
