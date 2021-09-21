<?php

function getStartAndEndDate($week, $year)
{
    $time = strtotime("1 January $year", time());
    $day = date('w', $time);
    $time += ((7*$week)+1-$day)*24*3600;
	//$return[0] = date('jS M Y', $time);
	
	$return[0] = date('Y-m-d', $time);
	
    $time += 6*24*3600;
    //$return[1] = date('jS M Y', $time);
	
	$return[1] = date('Y-m-d', $time);
	
    return $return;
}

require_once('../../../config/config.php');
require_once('../../patchworks.php'); 
require_once("../../projects/classes/bookings.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) die('login error');

$editProjectID = (isset($_GET['pla_id']) && is_numeric($_GET['pla_id'])) ? $_GET['pla_id'] : die('error');

$DateArray = array();
$CashArray = array();
$HourArray = array();

$TmpBoo = new BooDAO();
$activity = $TmpBoo->hoursByWeek('PROJECT', $editProjectID);
$tableLength = count($activity);
for ($i=0;$i<$tableLength;++$i) {

	$weekDates =  getStartAndEndDate($activity[$i]['boo_wk'],$activity[$i]['boo_yr']);

	//$HldDat = date('Y-m-d', strtotime("+".$d." day", $BegDat));
	
	$DateArray[$i] = $weekDates[0];
	$CashArray[$i] = 0;
	$HourArray[$i] = $activity[$i]['tothrs'];

} 

$jsonArray['dates'] = $DateArray;
$jsonArray['money'] = $CashArray;
$jsonArray['hours'] = $HourArray;

die( json_encode($jsonArray) );

?>