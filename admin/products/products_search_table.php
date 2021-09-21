<?php
require_once("../../config/config.php");
require_once("../patchworks.php");

require_once("../attributes/classes/attrgroups.cls.php");
require_once("../attributes/classes/attrlabels.cls.php");
require_once("../attributes/classes/attrvalues.cls.php");

require_once("../products/classes/products.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');


$PrdDao = new PrdDAO();
$TmpAtv = new AtvDAO();

//
// process attribute group values
//

$FldArrStr ='';
$AtrArr = (isset($_GET['fldnum'])) ? $_GET['fldnum'] : NULL;
for ($fldnum=0; $fldnum < count($AtrArr); $fldnum++) {
	$FldArrStr .= ($FldArrStr == '') ? $AtrArr[$fldnum] : ','.$AtrArr[$fldnum];
}

$FldValStr ='';
$AtrVal = (isset($_GET['fld'])) ? $_GET['fld'] : NULL;
$requestedMatches = 0;
for ($fldnum=0; $fldnum < count($AtrVal); $fldnum++) {
	$FldValStr .= ($FldValStr == '') ? $AtrVal[$fldnum] : ','.$AtrVal[$fldnum];
	if ($AtrVal[$fldnum] != '') $requestedMatches++;
}

//
//
//

$Atr_ID = (isset($_GET['atr_id'])) ? $_GET['atr_id'] : NULL;
$TblNam = (isset($_GET['tblnam'])) ? $_GET['tblnam'] : NULL;
$Tbl_ID = (isset($_GET['tbl_id'])) ? $_GET['tbl_id'] : NULL;

$attributeSearch = $TmpAtv->searchAttributeValues ( $Atr_ID , $AtrArr, $AtrVal, $TblNam, $Tbl_ID );

$products = NULL;

if (count($attributeSearch) > 0) {
				
	$PrdLst = '';
	
	foreach ($attributeSearch as $row) {
		
		// exact
		if (isset($row['atv_eq']) && $row['atv_eq'] == $requestedMatches) {
			$PrdLst .= ($PrdLst == '') ? "'".$row['Ref_ID']."'" : ",'".$row['Ref_ID']."'";
		} else if ($requestedMatches == 0) {
            $PrdLst .= ($PrdLst == '') ? "'".$row['Ref_ID']."'" : ",'".$row['Ref_ID']."'";
        }
	}

	$products = (!empty($PrdLst)) ? $PrdDao->selectByIDs($PrdLst, $Atr_ID) : NULL;
	
} else {

    $products = $PrdDao->searchProducts($Atr_ID, NULL, NULL, 'p.srtord');

}

$tableLength = count($products);
for ($i=0;$i<$tableLength;++$i) {
	
	$editType = 0;
	if ($products[$i]['prtseo'] != '') {
		$editType = 1;
	}
	
?>

<tr>
	<td>
	
		<?php 
		if (
			file_exists($patchworks->docRoot.'uploads/images/169-130/'.$products[$i]['prdimg']) && 
			!is_dir($patchworks->docRoot.'uploads/images/169-130/'.$products[$i]['prdimg'])) 
			{
				echo '<img src="'.$patchworks->webRoot.'uploads/images/169-130/'.$products[$i]['prdimg'].'" class="img-responsive" />'; 
			} else {
				if (
					file_exists($patchworks->docRoot.'uploads/images/169-130/'.$products[$i]['prtimg']) && 
					!is_dir($patchworks->docRoot.'uploads/images/169-130/'.$products[$i]['prtimg'])) 
					{
						echo '<img src="'.$patchworks->webRoot.'uploads/images/169-130/'.$products[$i]['prtimg'].'" class="img-responsive" />'; 
					} else {
						echo '<img class="img-responsive" src="http://placehold.it/169x130&text=no image">';
					}
			}
		?>
	
	</td>
	<td>
		<p><a href="ecommerce/single-product-edit.php?prd_id=<?php echo $products[$i]['prd_id']; ?>&prt_id=<?php echo $products[$i]['prt_id']; ?>" class="btn btn-info"><?php echo $products[$i]['prdnam']; ?></a></p>

        <p><?php echo $products[$i]['atrnam']; ?> </p>

		<?php if ($editType == 1) { ?>
		    <p><a href="ecommerce/producttype-edit.php?prt_id=<?php echo $products[$i]['prt_id']; ?>" class="btn btn-mini">edit <?php echo $products[$i]['prtnam']; ?> product type</a></p>



		<?php } ?>
		
		
	</td>
	<td style="text-align: right">
		<?php echo $products[$i]['unipri']; ?>
	</td>
	<td>
		<ul>
			<?php 
			$resultSet = NULL;
			$resultSet = $TmpAtv->selectValueSet($Atr_ID, $TblNam, $products[$i]['prd_id'], NULL, NULL, false);
			
			foreach ($resultSet as $row) { ?>
			
			<li><strong><?php echo $row['atllbl']; ?></strong> <?php echo $row['atvval']; ?></li>
			
			<?php 
			} 
			?>
		</ul>
	</td>
</tr>

<?php
}
?>