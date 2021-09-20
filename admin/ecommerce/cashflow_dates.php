<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("../system/classes/places.cls.php");
require_once("../products/classes/products.cls.php");
require_once("../ecommerce/classes/order.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) die();

$Sta_ID = ( isset($_GET['sta_id']) && !is_null($_GET['sta_id'])) ? $_GET['sta_id'] : '10,20';

$TmpOrd = new OrdDAO();
$orders = $TmpOrd->selectFinancial($Sta_ID);

$jsonArray = array();
$DateArray = array();
$CashArray = array();

//for ($d=0;$d<=56;$d++) {
//	$HldDat = date('Y-m-d', strtotime("+".$d." day", strtotime('monday this week')));
//	$DateArray[$d] = $HldDat;
//	$CashArray[$d] = 0;
//}


$Yr_Num = (isset($_GET['yr_num']) && is_numeric($_GET['yr_num'])) ? $_GET['yr_num'] : date("Y");

for ($d = 0; $d<12;$d++) {
	
	$dd = $d+1;
	if ($dd < 10) {
		$dd = '0'.$dd;
	}
	
	$HldDat = $Yr_Num.'-'.$dd.'-01';
	
	$DateArray[$d] = $HldDat;
	$CashArray[$d] = 0;
	
}



$tableLength = count($orders);
for ($i=0;$i<$tableLength;++$i) {
	
	//$key = array_search(date("Y-m-d", strtotime($orders[$i]['duedat'])), $DateArray);
	
	$dmn = $orders[$i]['month'];
	if ($orders[$i]['month'] < 10) $dmn = '0'.$dmn;
	
	$key = array_search($orders[$i]['year'].'-'.$dmn.'-01', $DateArray);
	
	//echo $orders[$i]['year'].'-'.$dmn.'-01 '.$key.' '.$orders[$i]['total'].'<br>';
	
	if (is_numeric($key)) {
		$CashArray[$key] = intval($orders[$i]['total']);
	}
}

$jsonArray['dates'] = $DateArray; //$DateArray;
$jsonArray['money'] = $CashArray;

die( json_encode($jsonArray) );

?>