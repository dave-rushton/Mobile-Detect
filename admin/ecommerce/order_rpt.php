<?php

require_once("../../config/config.php");
require_once("../patchworks.php");
require_once("classes/order.cls.php");
require_once("classes/orderline.cls.php");


function withoutVAT($amount = 0) {
    $vatCalc = ((100 + 20) / 100);
    return number_format(($amount / $vatCalc),2);
}

function calcVAT($amount = 0) {
    $vatCalc = ((100 + 20) / 100);
    return number_format(round($amount - ($amount / $vatCalc),2, PHP_ROUND_HALF_DOWN),2);
}

function addVAT($amount = 0) {
    $amount = $amount + (($amount / 100) * 20);
    return number_format($amount,2);
}


$Ord_ID = (isset($_REQUEST['ord_id']) && is_numeric($_REQUEST['ord_id'])) ? $_REQUEST['ord_id'] : die('FAIL');

$OrdDao = new OrdDAO();
$OlnDao = new OlnDAO();

$order = $OrdDao->select($Ord_ID, NULL, NULL, NULL, true);
$orderlines = $OlnDao->select($Ord_ID, NULL, false);

$orderTotal = 0;





?>
<html>
<head>
<style type="text/css">
body { margin: 20px; padding: 0px; font-family: Helvetica, Helvetica-Oblique; font-size: 11pt; color:#000; height: 100%; }

table { width: 100%; margin-bottom: 0px; float: left; }

td { font-family: Helvetica, Helvetica-Oblique; padding: 5px 0; }

.orderLine td { border-top: solid 1px #cccccc; }

.orderTotals td { border-top: solid 1px #666666; padding-top: 20px; }

th { border-bottom: solid 1px #666666; padding-bottom: 5px; }

p { font-size: 11pt; font-family: Helvetica, Helvetica-Oblique; }
</style>
</head>

<body>
<table cellspacing="0" cellpadding="0" border="0" width="100%"  align="center">
	<tr>
		<td align="center" width="100%">
			<img src="../../pages/images/logo.jpg" width="260" height="60">
		</td>
	</tr>
	<tr>
		<td align="center" width="100%" style="font-size: 10px;">

			<p>
			Outlandish Items Ltd<br>
			Carlton Grange Farm<br>
			Three Gates Road<br>
			Carlton Curlieu<br>
			Leicestershire<br>
			LE8 0PQ<br>
				TEL: 0116 279 6900<br>
				EMAIL: info@masterclip.co.uk
			</p>
		</td>
	</tr>
	<tr>
		<td align="center" width="100%">
			<h2>INVOICE</h2>
		</td>
	</tr>
</table>
<table cellspacing="0" cellpadding="0" border="0" width="100%"  align="center">
	<tr>
		<td align="left" width="50%">
			<p>
				<?php echo $order->cusnam; ?><br>
				<?php echo $order->payadr1; ?><br />
				<?php echo $order->payadr2; ?><br />
				<?php echo $order->payadr3; ?><br />
				<?php echo $order->payadr4; ?><br />
				<?php echo $order->paypstcod; ?></p>

			<p>
				<?php echo $order->paytrm; ?><br>
				EMAIL: <?php echo $order->emaadr; ?></p>

            <p>
                <b>Deliver To</b>
            </p>
            <p>
				<?php echo $order->cusnam; ?><br>
				<?php echo $order->adr1; ?><br />
                <?php echo $order->adr2; ?><br />
                <?php echo $order->adr3; ?><br />
                <?php echo $order->adr4; ?><br />
                <?php echo $order->pstcod; ?></p>
		</td>
		<td align="right" width="50%">
			<p> Invoice No: <?php echo str_pad($order->ord_id, 8, "0", STR_PAD_LEFT); ?>
			<br> Invoice Date: <?php echo date("jS M Y", strtotime($order->invdat)); ?>
		</td>
	</tr>
</table>
<table cellspacing="0" cellpadding="0" border="0" width="100%"  align="center">
	<tr>
		<td colspan="5" height="20"></td>
	</tr> 
	<tr>
		<td align="left"> <b>Product</b> </td>
		<td align="left"> <b>Description</b> </td>
		<td align="left"> <b>Price</b> </td>
		<td align="left"> <b>Qty</b> </td>
        <td align="left"> <b>VAT</b> </td>
		<td align="right"> <b>Total</b> </td>
	</tr>
	<tr>
		<th colspan="6" height="5"></th>
	</tr>
	<tr>
		<td colspan="6" height="5"></td>
	</tr>
	<?php
	
	$tableLength = count($orderlines);
	for ($i=0;$i<$tableLength;++$i) {
		
		$orderTotal = $orderTotal + $orderlines[$i]['unipri'] * $orderlines[$i]['numuni'];
		
	?>
	
	<tr class="<?php if($i > 0) { echo 'orderLine'; } ?>">
		<td width="120"> <?php echo $orderlines[$i]['prdnam']; ?> </td>
		<td> <?php echo $orderlines[$i]['olndsc']; ?> </td>
		<td width="80"> &pound;<?php echo $orderlines[$i]['unipri']; ?> </td>
		<td width="50"> <?php echo number_format($orderlines[$i]['numuni'],0); ?> </td>

        <?php

        //$vatAmount = (100 + $orderlines[$i]['vatrat']) / 100;
        //$vatAmount = ($orderlines[$i]['unipri'] * $orderlines[$i]['numuni']) - ($orderlines[$i]['unipri'] * $orderlines[$i]['numuni']) / $vatAmount;

        $vatAmount = calcVAT($orderlines[$i]['unipri'] * $orderlines[$i]['numuni']);

        ?>

        <td width="50"> <?php echo number_format($vatAmount,2); ?> </td>

		<td width="80" align="right"> &pound;<?php echo number_format($orderlines[$i]['unipri'] * $orderlines[$i]['numuni'],2); ?> </td>
	</tr>
	
	<?php } ?>
	
	<tr>
		<th colspan="6" height="1"></th>
	</tr>
	
	<tr>
		<td colspan="6" height="20"></td>
	</tr>
	
	<tr class="orderTotals">
		<td colspan="2">
		<td colspan="3"> Sub Total </td>
		<td align="right"> &pound;<?php echo number_format($orderTotal, 2); ?> </td>
	</tr>
	
	<?php if ($order->vatrat > 0) { ?>
	<tr>
		<td colspan="2">
		<td colspan="3"> VAT </td>
		<td align="right"> &pound;<?php echo number_format( ($orderTotal / 100) * $order->vatrat, 2); ?> </td>
	</tr>
	<?php } ?>

	<tr>
		<td colspan="2">
		<td colspan="3"> <b>Total</b> </td>
		<td align="right"> <b>&pound;<?php echo number_format( (($orderTotal / 100) * $order->vatrat) + $orderTotal, 2); ?></b></td>
	</tr>
</table>


</body>
</html>