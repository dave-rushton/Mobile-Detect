<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("../system/classes/places.cls.php");
require_once("../products/classes/products.cls.php");
require_once("../projects/classes/bookings.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) die();


$BegDat = (isset($_GET['begdat']) ) ? $_GET['begdat'] : strtotime("-28 day", strtotime('now'));
$EndDat = (isset($_GET['enddat']) ) ? $_GET['enddat'] : NULL;
$Tbl_ID = (isset($_GET['tbl_id']) && is_numeric($_GET['tbl_id'])) ? $_GET['tbl_id'] : NULL;
$TblNam = (isset($_GET['tblnam']) && !empty($_GET['tblnam'])) ? $_GET['tblnam'] : NULL;
$Ref_ID = (isset($_GET['ref_id']) && is_numeric($_GET['ref_id'])) ? $_GET['ref_id'] : NULL;
$RefNam = (isset($_GET['refnam']) && !empty($_GET['refnam'])) ? $_GET['refnam'] : NULL;
$Sta_ID = (isset($_GET['sta_id']) && is_numeric($_GET['sta_id'])) ? $_GET['sta_id'] : NULL;

$DateArray = array();
$CashArray = array();
$HourArray = array();

for ($d=0;$d<=42;$d++) {
	$HldDat = date('Y-m-d', strtotime("+".$d." day", $BegDat));
	
	//if ($d==0) $BegDat = $HldDat;
	
	$DateArray[$d] = $HldDat;
	$CashArray[$d] = 0;
	$HourArray[$d] = 0;
}

$TmpBoo = new BooDAO();
$bookings = $TmpBoo->select(NULL, $BegDat, $EndDat, $TblNam, $Tbl_ID, $RefNam, $Ref_ID, $Sta_ID, false);

$PrdDao = new PrdDAO();

$tableLength = count($bookings);

for ($i=0;$i<$tableLength;++$i) {
	
	$key = array_search(date("Y-m-d", strtotime($bookings[$i]['begdat'])), $DateArray);
	
	$hourdiff = round((strtotime($bookings[$i]['enddat']) - strtotime($bookings[$i]['begdat']))/3600, 2);
	
	$product = $PrdDao->select($bookings[$i]['prd_id'], NULL, NULL, NULL, NULL, false);
	
	if (is_numeric($key)) {
        if (isset($product[0]['unipri'])) {
            $CashArray[$key] = $CashArray[$key] + (round((strtotime($bookings[$i]['enddat']) - strtotime($bookings[$i]['begdat'])) / 3600, 2) * $product[0]['unipri']);
        }
		$HourArray[$key] = $HourArray[$key] + $hourdiff;
	}
}

$jsonArray['dates'] = $DateArray;
$jsonArray['money'] = $CashArray;
$jsonArray['hours'] = $HourArray;

die( json_encode($jsonArray) );

?>