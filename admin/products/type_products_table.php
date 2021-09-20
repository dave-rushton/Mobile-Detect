<?php
require_once("../../config/config.php");
require_once("../patchworks.php");
require_once("../products/classes/products.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
//if ($loggedIn == 0) header('location: ../login.php');

$Prt_ID = (isset($_GET['prt_id']) && is_numeric($_GET['prt_id'])) ? $_GET['prt_id'] : die('');

$products = NULL;
if ($Prt_ID > 0) {
    $TmpPrd = new PrdDAO();
    $products = $TmpPrd->select(NULL, NULL, $Prt_ID, NULL, NULL, NULL, NULL, false, NULL, NULL);
}

$tableLength = count($products);
for ($i=0;$i<$tableLength;++$i) {
?>
<tr>
	<td><a href="#" class="editProduct" data-prd_id="<?php echo $products[$i]['prd_id']; ?>"><?php echo $products[$i]['prdnam']; ?></a></td>
	<td><?php echo $products[$i]['unipri']; ?></td>
	<td><a href="#" class="btn btn-mini btn-danger deleteProduct" data-prd_id="<?php echo $products[$i]['prd_id']; ?>"><i class="icon icon-trash"></i></a></td>
	<td><a href="#" class="btn btn-mini btn-success sortProduct" data-prd_id="<?php echo $products[$i]['prd_id']; ?>"><i class="icon icon-sort"></i></a></td>
</tr>
<?php } ?>