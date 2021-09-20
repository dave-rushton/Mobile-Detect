<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("../system/classes/places.cls.php");
require_once("../system/classes/people.cls.php");
require_once("../projects/classes/bookings.cls.php");
require_once("../products/classes/products.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$BegDat = (isset($_GET['begdat']) ) ? $_GET['begdat'] : NULL;
$EndDat = (isset($_GET['enddat']) ) ? $_GET['enddat'] : NULL;

$Tbl_ID = (isset($_GET['tbl_id']) && is_numeric($_GET['tbl_id'])) ? $_GET['tbl_id'] : NULL;
$TblNam = (isset($_GET['tblnam']) && !empty($_GET['tblnam'])) ? $_GET['tblnam'] : NULL;
$Ref_ID = (isset($_GET['ref_id']) && is_numeric($_GET['ref_id'])) ? $_GET['ref_id'] : NULL;
$RefNam = (isset($_GET['refnam']) && !empty($_GET['refnam'])) ? $_GET['refnam'] : NULL;

$Sta_ID = (isset($_GET['sta_id']) && is_numeric($_GET['sta_id'])) ? $_GET['sta_id'] : NULL;

$TmpBoo = new BooDAO();
$bookings = $TmpBoo->select(NULL, $BegDat, $EndDat, $TblNam, $Tbl_ID, $RefNam, $Ref_ID, $Sta_ID, false);

$totalHours = 0;

$tableLength = count($bookings);
for ($i=0;$i<$tableLength;++$i) {

	$hourdiff = round((strtotime($bookings[$i]['enddat']) - strtotime($bookings[$i]['begdat']))/3600, 2);
	$totalHours += $hourdiff;
	//echo $bookings[$i]['boo_id']

}

$availability = ($totalHours / 40) * 100;

$jsonStr = [];
$jsonStr['total_hours'] = $totalHours;
$jsonStr['availability'] = $availability;

die(json_encode($jsonStr));

 ?>