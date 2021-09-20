<?php
require_once("../../config/config.php");
require_once("../patchworks.php");
require_once("../ecommerce/classes/order.cls.php");
require_once("../ecommerce/classes/delivery.cls.php");
$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');
$filepath = $patchworks->docRoot."orders_report.csv";
$header = array("Order No", "Customer", "Customer Address", "Order Date", "Amount", "Delivery", "Status", "Due Date");
$file = fopen($filepath, "w");
fputcsv($file, $header);
$TmpDel = new DelDAO();
$TmpOrd = new OrdDAO();
$line = array();
function getAddress($order) {
    $address = (!empty($order['adr1'])) ? $order['adr1'] : '';
    $address .= (!empty($order['adr2'])) ? '; '.$order['adr2'] : '';
    $address .= (!empty($order['adr3'])) ? '; '.$order['adr3'] : '';
    $address .= (!empty($order['adr4'])) ? '; '.$order['adr4'] : '';
    $address .= (!empty($order['pstcod'])) ? '; '.$order['pstcod'] : '';
    return $address;
}
$throwJSON = array();
if (isset($_GET)) {
    $Sta_ID = (isset($_GET['sta_id'])) ? $_GET['sta_id'] : NULL;
    $BegDat = (isset($_GET['begdat']) && !empty($_GET['begdat'])) ? $_GET['begdat'] : NULL;
    $EndDat = (isset($_GET['enddat']) && !empty($_GET['enddat'])) ? $_GET['enddat'] : NULL;
    $TblNam = (isset($_GET['tblnam'])) ? $_GET['tblnam'] : NULL;
    $CusNam = (isset($_GET['cusnam'])) ? $_GET['cusnam'] : NULL;
    $Tbl_ID = (isset($_GET['tbl_id']) && is_numeric($_GET['tbl_id'])) ? $_GET['tbl_id'] : NULL;
    $Ord_ID = (isset($_GET['ord_id']) && is_numeric($_GET['ord_id'])) ? $_GET['ord_id'] : NULL;
    $orders = $TmpOrd->searchOrders($Ord_ID, $Sta_ID, $BegDat, $EndDat, $CusNam);
    foreach ($orders as $order) {
        $line["ord_id"] = str_pad($order['ord_id'], 8, "0", STR_PAD_LEFT);
        $line["cusnam"] = (!empty($order['cusnam']) && $order["cusnam"] != " ") ? $order['cusnam'].' ' : '';
        $line["cusadr"] = getAddress($order);
        $line["orddat"] = date("jS M Y", strtotime($order['invdat']));
        $line["ordtot"] = str_replace(',', '', number_format($order['ordtot'], 2));
        $deliveryOption = $TmpDel->select($order['del_id'], NULL, NULL, NULL, true);
        $line["delpri"] = (isset($deliveryOption->delpri)) ? $deliveryOption->delpri.' '.$deliveryOption->delnam : '0.00';
        $status = 'UNKNOWN';
        switch ($order['sta_id']) {
            case 0:
                $status = "Active";
                break;
            case 10:
                $status = "Invoiced";
                break;
            case 20:
                $status = "Paid (".$order['altref'].")";
                break;
            case 30:
                $status = "Despatched";
                break;
            case 99:
                $status = "Cancelled";
                break;
        }
        $line["status"] = $status;
        $datediff = '';
        if ($order['sta_id'] == 10 && !is_null($order['duedat']) && $order['duedat'] != '') {
            $now = time();
            $your_date = strtotime($order['duedat']);
            $datediff = $your_date - $now;
            $datediff = ceil($datediff/(60*60*24));
            if ($datediff > 0) {
                $line["duedat"] = 'due in '.$datediff.' day(s)';
            } else {
                $line["duedat"] = $datediff.' day(s) overdue';
            }
        }
        else {
            $line["duedat"] = "";
        }
        fputcsv($file, $line);
    }
    $throwJSON['id'] = $Ord_ID;
    $throwJSON['title'] = 'Report Created';
    $throwJSON['description'] = 'Report Successfully Created in your root directory';
    $throwJSON['type'] = 'success';
}
die(json_encode($throwJSON));
