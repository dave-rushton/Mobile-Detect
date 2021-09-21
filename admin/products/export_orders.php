<?php


require_once('../../config/config.php');
require_once('../patchworks.php');
require_once('../ecommerce/classes/order.cls.php');
require_once('../ecommerce/classes/orderline.cls.php');

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpOrd = new OrdDAO();
$orders = $TmpOrd->select();

$TmpOln = new OlnDAO();


header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=data.csv');

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

$headersDone = false;

$tableLength = count($orders);
for ($i=0;$i<$tableLength;++$i) {

    $orderlines = $TmpOln->select($orders[$i]['ord_id']);

    if (!$headersDone) {

        fputcsv($output, ['#','Invoice Date','Customer','Address']);
        $headersDone = true;

    }

    $csvRow = [ $orders[$i]['ord_id'], date("d-m-Y", strtotime($orders[$i]['invdat'])), $orders[$i]['cusnam'], $orders[$i]['adr1'] ];

    fputcsv($output, $csvRow);

}

?>