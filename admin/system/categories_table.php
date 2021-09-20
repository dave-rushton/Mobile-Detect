<?php
require_once("../../config/config.php");
require_once("../patchworks.php");
require_once("classes/categories.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) die();

$Cat_ID = NULL;
$TblNam = NULL;
$Tbl_ID = NULL;
$CatNam = NULL;

$TmpCat = new CatDAO();
$categories = $TmpCat->select(NULL, $TblNam, $Tbl_ID, $CatNam, false);

$tableLength = count($categories);
for ($i=0;$i<$tableLength;++$i) {
?>

<tr>
	<td><?php echo $categories[$i]['tblnam']; ?></td>
	<td><a href="#" class="editCategoryLink" data-cat_id="<?php echo $categories[$i]['cat_id']; ?>"><?php echo $categories[$i]['catnam']; ?></a></td>
	<td><a href="#" class="deleteCategoryLink" data-cat_id="<?php echo $categories[$i]['cat_id']; ?>" data-type="warning"><i class="icon-remove"></i></a></td>
</tr>

<?php } ?>