<?php

require_once("../../config/config.php");
require_once("../patchworks.php");
$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: login.php');
$qryArray = array();
$sql = '';
if (isset($_GET['tblnam'])) {
    $sql = 'SELECT
		* 
		FROM uploads WHERE tblnam = :tblnam AND tbl_id = :tbl_id ORDER BY srtord ASC';
    $qryArray['tblnam'] = (isset($_GET['tblnam'])) ? $_GET['tblnam'] : '';
    $qryArray['tbl_id'] = (isset($_GET['tbl_id']) && is_numeric($_GET['tbl_id'])) ? $_GET['tbl_id'] : 0;
} else {
    $sql = 'SELECT * FROM uploads';
}
$uploads = $patchworks->run($sql, $qryArray);
?>
<?php
$tableLength = count($uploads);
for ($i=0;$i<$tableLength;++$i) {
    $filename = $patchworks->docRoot.'uploads/images/169-130/'.$uploads[$i]['filnam'];
?>
<li>

		<div class="span3">

			<span class="img">
				<a href="#" style="width: 169px;">
					<img src="<?php echo $patchworks->webRoot; ?>uploads/images/169-130/<?php echo $uploads[$i]['filnam']; ?>?u=<?php echo @filemtime($filename) ?>" />
				</a>
				<div class="extras">
					<div class="extras-inner">
						<a href="<?php echo $patchworks->webRoot; ?>uploads/images/<?php echo $uploads[$i]['filnam']; ?>" class='colorbox-image' rel="group-1"><i class="icon-search"></i></a>
						<a href="#" class="transfereUpload" data-upl_id="3"><i class="icon-share-alt"></i></a>
						<a target="_blank" href="gallery/imagecropper.php?imgfil=<?php echo $uploads[$i]['filnam']; ?>" class="cropUpload" data-upl_id="<?php echo $uploads[$i]['upl_id']; ?>"><i class="icon-external-link"></i></a>
					</div>
				</div>
			</span>
			<a href="#" class="moveUpload" data-upl_id="<?php echo $uploads[$i]['upl_id']; ?>"><i class="icon-move"></i></a>
		</div>
		<div class="span9">
			<input type="hidden" class="input-block-level" name="upl_id" value="<?php echo $uploads[$i]['upl_id']; ?>" />
			<div class="title">
				<label>Title</label>
				<input type="text" class="input-block-level" name="uplttl" value="<?php echo $uploads[$i]['uplttl']; ?>" />
			</div>
			<div class="description">
				<label>Description</label>
				<textarea class="input-block-level" name="upldsc"><?php echo $uploads[$i]['upldsc']; ?></textarea>
			</div>
			<div class="row-fluid">
				<div class="span6">
					<div class="alt">
						<label>Alt Text</label>
						<input type="text" name="alttxt" class="input-block-level" value="<?php echo $uploads[$i]['alttxt']; ?>" />
					</div>
				</div>
				<div class="span6">
					<div class="link">
						<label>Link</label>
						<input type="text" name="urllnk" class="input-block-level" value="<?php echo $uploads[$i]['urllnk']; ?>" />
					</div>
				</div>
			</div>
			<div class="description">
				<div class="btn btn-primary saveGalItem" data-upl_id="<?php echo $uploads[$i]['upl_id']; ?>">Save</div>
				<div class="btn btn-primary deleteUpload" data-upl_id="<?php echo $uploads[$i]['upl_id']; ?>">Delete</div>
			</div>
		</div>
</li>

<?php }
