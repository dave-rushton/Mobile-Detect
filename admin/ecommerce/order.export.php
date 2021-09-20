<?php

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("../system/classes/places.cls.php");
require_once("../products/classes/products.cls.php");
require_once("../ecommerce/classes/order.cls.php");
require_once("../ecommerce/classes/orderline.cls.php");
require_once("../custom/classes/baskets.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpPla = new PlaDAO();
$customers = $TmpPla->select(NULL, 'CUS', NULL, NULL);

$TmpPrd = new PrdDAO();
$products = $TmpPrd->select(NULL, NULL, NULL, NULL, false);

$TmpOrd = new OrdDAO();
$orders = $TmpOrd->select();
$TmpOln = new OlnDAO();

$TmpBsk = new BskDAO();

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=data.csv');
$output = fopen('php://output', 'w');


$headerArray = array();
array_push($headerArray, 'Order Number');
array_push($headerArray, 'Company');
array_push($headerArray, 'Contact Name');
array_push($headerArray, 'Phone No.');
array_push($headerArray, 'Email');

array_push($headerArray, 'Billing Address 1');
array_push($headerArray, 'Billing Address 2');
array_push($headerArray, 'Billing City');
array_push($headerArray, 'Billing State');
array_push($headerArray, 'Billing Country');
array_push($headerArray, 'Billing Postcode');

array_push($headerArray, 'Shipping Address 1');
array_push($headerArray, 'Shipping Address 2');
array_push($headerArray, 'Shipping City');
array_push($headerArray, 'Shipping State');
array_push($headerArray, 'Shipping Country');
array_push($headerArray, 'Shipping Postcode');

array_push($headerArray, 'Order Date');
array_push($headerArray, 'Requested Delivery date');
array_push($headerArray, 'PO Number');
array_push($headerArray, 'Basket Name');
array_push($headerArray, 'Product Names');
array_push($headerArray, 'Product Quantities');
array_push($headerArray, 'Basket price');
array_push($headerArray, 'Basket Quantity');
array_push($headerArray, 'Discount');
array_push($headerArray, 'Shipping Service');
array_push($headerArray, 'Shipping Price');


fputcsv($output, $headerArray);

$tableLength = count($orders);

//echo 'LENGTH: '.$tableLength.'<br>';

for ($i=0;$i<$tableLength;++$i) {

    // Order Rows
    // Parse Order Line Object

    $orderlines = $TmpOln->select($orders[$i]['ord_id'], NULL, false);

    $orderTotal = 0;

    //echo $orders[$i]['ord_id'].'<br>';

    $lineLength = count($orderlines);
    for ($l = 0; $l < $lineLength; $l++) {

        $basketRec = $TmpBsk->select($orderlines[$l]['prd_id'], NULL, NULL, NULL, true);

        //array_push($rowArray, $orderlines[$l]['prdnam']);

        $products = json_decode($orderlines[$l]['olndsc'], true);
        //$formInfo = '';

        if (isset($products['products']) && is_array($products['products'])) {

            //$formInfo = $products['forminfo'];
            $products = $products['products'];

            for ($p=0;$p<count($products);$p++) {

                //echo 'products<br>';

                $rowArray = array();
                array_push($rowArray, $orders[$i]['ord_id']);
                array_push($rowArray, $orders[$i]['cusnam']);
                array_push($rowArray, 'Contact Name');
                array_push($rowArray, 'Phone No.');
                array_push($rowArray, $orders[$i]['emaadr']);

                array_push($rowArray, $orders[$i]['payadr1']);
                array_push($rowArray, $orders[$i]['payadr2']);
                array_push($rowArray, $orders[$i]['payadr3']);
                array_push($rowArray, $orders[$i]['payadr4']);
                array_push($rowArray, '');
                array_push($rowArray, $orders[$i]['paypstcod']);

                array_push($rowArray, $orders[$i]['adr1']);
                array_push($rowArray, $orders[$i]['adr2']);
                array_push($rowArray, $orders[$i]['adr3']);
                array_push($rowArray, $orders[$i]['adr4']);
                array_push($rowArray, '');
                array_push($rowArray, $orders[$i]['pstcod']);

                array_push($rowArray, $orders[$i]['invdat']);
                array_push($rowArray, 'Requested Delivery date');
                array_push($rowArray, 'PO Number');

                array_push($rowArray, $basketRec->bskttl);
                array_push($rowArray, $products[$p]['prdnam']);
                array_push($rowArray, number_format($products[$p]['bprqty'] * $orderlines[$l]['numuni'],3));
                array_push($rowArray, $products[$p]['unipri']);
                array_push($rowArray, $orderlines[$l]['numuni']);
                array_push($rowArray, $orders[$i]['discod']);

                // Find Delivery

                array_push($rowArray, $orders[$i]['del_id']);
                array_push($rowArray, 'Shipping Price');

                fputcsv($output, $rowArray);

            }

        }

        //$orderTotal = $orderTotal + $orderlines[$l]['unipri'] * $orderlines[$l]['numuni'];

    }

}


?>