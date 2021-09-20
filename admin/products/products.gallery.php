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

    $filename = $patchworks->docRoot.'uploads/images/products/169-130/'.$uploads[$i]['filnam'];

?>

<li>
	<a href="#" style="width: 169px;">
		<img src="<?php echo $patchworks->webRoot; ?>uploads/images/products/169-130/<?php echo $uploads[$i]['filnam']; ?>?u=<?php echo @filemtime($filename) ?>" />
	</a>
	<div class="extras">
		<div class="extras-inner">
			<a href="<?php echo $patchworks->webRoot; ?>uploads/images/products/<?php echo $uploads[$i]['filnam']; ?>" class='colorbox-image' rel="group-1"><i class="icon-search"></i></a>
			<a href="#" class="editUpload" data-upl_id="<?php echo $uploads[$i]['upl_id']; ?>"><i class="icon-pencil"></i></a>
			<a href="#" class="deleteUpload" data-upl_id="<?php echo $uploads[$i]['upl_id']; ?>"><i class="icon-trash"></i></a>
			<a href="#" class="moveUpload" data-upl_id="<?php echo $uploads[$i]['upl_id']; ?>"><i class="icon-move"></i></a>
            <a href="products/imagecropper.php?imgfil=products/<?php echo $uploads[$i]['filnam']; ?>" class="cropUpload" data-upl_id="<?php echo $uploads[$i]['upl_id']; ?>"><i class="icon-external-link"></i></a>
		</div>
	</div>
</li>

<?php } ?>
