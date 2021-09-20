<?php
require_once("../../config/config.php");
require_once("../patchworks.php");
require_once("classes/subcategories.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) die();

$Cat_ID = (isset($_GET['cat_id']) && is_numeric($_GET['cat_id'])) ? $_GET['cat_id'] : NULL;
$SubNam = NULL;

$TmpSub = new SubDAO();
$subCategories = $TmpSub->select($Cat_ID);

$tableLength = count($subCategories);
for ($i=0;$i<$tableLength;++$i) {
?>

<tr>
	<td><a href="#" class="editSubCategoryLink" data-sub_id="<?php echo $subCategories[$i]['sub_id']; ?>"><?php echo $subCategories[$i]['subnam']; ?></a></td>
	<td><a href="#" class="deleteSubCategoryLink" data-cat_id="<?php echo $subCategories[$i]['cat_id']; ?>" data-sub_id="<?php echo $subCategories[$i]['sub_id']; ?>" data-type="warning"><i class="icon-remove"></i></a></td>
</tr>

<?php } ?>