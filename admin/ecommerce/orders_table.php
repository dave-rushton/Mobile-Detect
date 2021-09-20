<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("../system/classes/places.cls.php");
require_once("../products/classes/products.cls.php");
require_once("../ecommerce/classes/order.cls.php");
require_once("../ecommerce/classes/delivery.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) die('#');

$Sta_ID = (isset($_GET['sta_id'])) ? $_GET['sta_id'] : NULL;
$BegDat = (isset($_GET['begdat']) && !empty($_GET['begdat'])) ? $_GET['begdat'] : NULL;
$EndDat = (isset($_GET['enddat']) && !empty($_GET['enddat'])) ? $_GET['enddat'] : NULL;
$TblNam = (isset($_GET['tblnam'])) ? $_GET['tblnam'] : NULL;
$CusNam = (isset($_GET['cusnam'])) ? $_GET['cusnam'] : NULL;
$Tbl_ID = (isset($_GET['tbl_id']) && is_numeric($_GET['tbl_id'])) ? $_GET['tbl_id'] : NULL;
$Ord_ID = (isset($_GET['ord_id']) && is_numeric($_GET['ord_id'])) ? $_GET['ord_id'] : NULL;

$TmpDel = new DelDAO();

$TmpOrd = new OrdDAO();
//$orders = $TmpOrd->select(NULL, $TblNam, $Tbl_ID, $Sta_ID, false);

$orders = $TmpOrd->searchOrders($Ord_ID, $Sta_ID, $BegDat, $EndDat, $CusNam);

$tableLength = count($orders);
for ($i=0;$i<$tableLength;++$i) {

    $ordTot = str_replace(',', '', number_format($orders[$i]['ordtot'], 2));

    $deliveryOption = $TmpDel->select($orders[$i]['del_id'], NULL, NULL, NULL, true);

    $className = '';

    $displayAddress = '<strong>'.$orders[$i]['cusnam'].'</strong><br><small style="font-size: 10px; line-height: 1em;">'.$orders[$i]['adr1'];
    $displayAddress .= (!empty($orders[$i]['adr2'])) ? '<br>'.$orders[$i]['adr2'] : '';
    $displayAddress .= (!empty($orders[$i]['adr3'])) ? '<br>'.$orders[$i]['adr3'] : '';
    $displayAddress .= (!empty($orders[$i]['adr4'])) ? '<br>'.$orders[$i]['adr4'] : '';
    $displayAddress .= (!empty($orders[$i]['pstcod'])) ? '<br>'.$orders[$i]['pstcod'] : '';
    $displayAddress .= '</small>';

    switch ($orders[$i]['sta_id']) {
        case 0:
            $className = 'error';
            break;
        case 10:
            $className = 'warning';
            break;
        case 20:
            $className = 'warning';
            break;
        case 30:
            $className = 'success';
            break;
        case 99:
            $className = 'error';
            break;
    }

?>
<tr class="<?php echo $className; ?>">


    <td style="vertical-align: top; width: 20px"><input type="checkbox" class="ord_cb" data-ord_id="<?php echo $orders[$i]['ord_id']; ?>" value="<?php echo $orders[$i]['ord_id']; ?>"> </td>

    <td style="vertical-align: top;"><a href="ecommerce/order-edit.php?ord_id=<?php echo $orders[$i]['ord_id']; ?>" class="editOrderLnk" data-ord_id="<?php echo $orders[$i]['ord_id']; ?>"><?php echo str_pad($orders[$i]['ord_id'], 8, "0", STR_PAD_LEFT); ?></a></td>

    <td style="vertical-align: top;">
        <?php
        //echo '<strong>'.$orders[$i]['cusnam'].'</strong><br><small style="font-size: 10px; line-height: 1em;">'.$orders[$i]['adr1'].'<br>'.$orders[$i]['adr2'].'<br>'.$orders[$i]['adr3'].'<br>'.$orders[$i]['adr4'].'<br>'.$orders[$i]['pstcod'].'</small>';
        echo $displayAddress;
        ?>
    </td>

	<td style="vertical-align: top;"><?php echo date("jS M Y", strtotime($orders[$i]['invdat'])); ?></td>
<!--	<td style="vertical-align: top; text-align: right;">&pound;<span class="orderTotalCalc">--><?php //echo $ordTot; ?><!--</span></td>-->
<!--    <td style="vertical-align: top; text-align: right;"><span>&pound;--><?php //echo (isset($deliveryOption->delpri)) ? $deliveryOption->delpri.'<br><i>'.$deliveryOption->delnam.'</i>' : '0.00'; ?><!--</span></td>-->
<!--	<td style="vertical-align: top;">-->
	<?php 
	
	$status = 'UNKNOWN';
	switch ($orders[$i]['sta_id']) {
		case 0:
			echo "Active";
			break;
		case 10:
			echo "Invoiced";
			break;
		case 20:
			echo "Paid (".$orders[$i]['altref'].")";
			break;
        case 30:
            echo "Despatched";
            break;
        case 99:
            echo "Cancelled";
            break;
	}
	
	$datediff = '';
	if ($orders[$i]['sta_id'] == 10 && !is_null($orders[$i]['duedat']) && $orders[$i]['duedat'] != '') {
		$now = time();
		$your_date = strtotime($orders[$i]['duedat']);
		$datediff = $your_date - $now;
		$datediff = ceil($datediff/(60*60*24));
		if ($datediff > 0) {
			echo ' <small class="pull-right label label-info">due in '.$datediff.' day(s)</small>';
		} else {
			echo ' <small class="pull-right label label-important">'.$datediff.' day(s) overdue</small>';
		}
	}
	
	?>
	</td>
</tr>
<?php } ?>