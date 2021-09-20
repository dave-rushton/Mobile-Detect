<?php
require_once("../../config/config.php");
require_once("../patchworks.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: login.php');

$qryArray = array();
$sql = 'SELECT
		* 
		FROM uploads WHERE tblnam = :tblnam AND tbl_id = :tbl_id ORDER BY srtord ASC';

$qryArray['tblnam'] = (isset($_GET['tblnam'])) ? $_GET['tblnam'] : '';
$qryArray['tbl_id'] = (isset($_GET['tbl_id']) && is_numeric($_GET['tbl_id'])) ? $_GET['tbl_id'] : 0;

$uploads = $patchworks->run($sql, $qryArray);

?>
<?php
$tableLength = count($uploads);
for ($i = 0; $i < $tableLength; ++$i) {
    ?>

    <tr data-imageid="<?php echo $uploads[$i]['upl_id']; ?>">
        <td width="120">
            <a href="<?php echo $patchworks->pwRoot; ?>uploader/uploads/<?php echo $uploads[$i]['filnam']; ?>" class="zoom ui-lightbox ui-lightbox">
                <img src="<?php echo $patchworks->pwRoot; ?>uploader/uploads/169-130/<?php echo $uploads[$i]['filnam']; ?>" />
            </a>
        </td>
        <td>
            <form action="<?php echo $patchworks->pwRoot; ?>gallery/uploads_script.php" class="form-horizontal" novalidate="novalidate">
                <input type="hidden" name="upl_id" value="<?php echo $uploads[$i]['upl_id']; ?>" />
                <input type="text" name="uplttl" value="<?php echo $uploads[$i]['uplttl']; ?>" /> <br />
                <textarea name="upldsc"><?php echo $uploads[$i]['upldsc']; ?></textarea>
            </form>
        </td>
        <td><a href="#" class="btn btn-small updateImageButton" rel="<?php echo $uploads[$i]['upl_id']; ?>">update</a>
            <br />
            <a href="#" class="btn btn-small deleteImageButton" data-type="warning" rel="<?php echo $uploads[$i]['upl_id']; ?>"><i class="icon icon-remove"></i>
                delete</a></td>
    </tr>
<?php }
