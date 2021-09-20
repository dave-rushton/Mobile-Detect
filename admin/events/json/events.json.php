<?php

error_reporting(0);

require_once("../../../config/config.php");
require_once("../../patchworks.php");

$host=$patchworks->host;
$username=$patchworks->user;
$password=$patchworks->password;
$db_name=$patchworks->dbname;

$con = mysql_connect($host, $username, $password) or die('Could not connect to server');
mysql_select_db($db_name) or die('Could not find database');

$BegDat = date("Y-m-d", $_REQUEST['start']);
$EndDat = date("Y-m-d", $_REQUEST['end']);

$TblNam = (isset($_REQUEST['tblnam'])) ? $_REQUEST['tblnam'] : 'EVENT';
$Tbl_ID = (isset($_REQUEST['tbl_id']) && is_numeric($_REQUEST['tbl_id'])) ? $_REQUEST['tbl_id'] : NULL;

if (!is_null($Tbl_ID)) {
	$sql="SELECT b.*,
            v.planam AS vennam,
            e.planam AS evtnam,
            prd.prdnam, prd.unipri,
            TIME_TO_SEC(TIMEDIFF(b.enddat,b.begdat))/3600 AS tothrs
			FROM bookings b
			LEFT OUTER JOIN places e ON e.tblnam = 'EVT' AND e.pla_id = b.tbl_id
			LEFT OUTER JOIN places v ON v.tblnam = 'VENUE' AND v.pla_id = b.ref_id
			LEFT OUTER JOIN products prd ON prd.prd_id = b.prd_id
			WHERE b.tblnam = '".$TblNam."' AND b.tbl_id = ".$Tbl_ID." AND b.begdat >= '".$BegDat."' AND b.begdat <= '".$EndDat."'";
} else {
	$sql="SELECT b.*,
            v.planam AS vennam,
            e.planam AS evtnam,
            prd.prdnam, prd.unipri,
            TIME_TO_SEC(TIMEDIFF(b.enddat,b.begdat))/3600 AS tothrs
			FROM bookings b
			LEFT OUTER JOIN places e ON e.tblnam = 'EVT' AND e.pla_id = b.tbl_id
			LEFT OUTER JOIN places v ON v.tblnam = 'VENUE' AND v.pla_id = b.ref_id
			LEFT OUTER JOIN products prd ON prd.prd_id = b.prd_id 
			WHERE b.tblnam = '".$TblNam."' AND b.begdat >= '".$BegDat."' AND b.begdat <= '".$EndDat."'";
}

//echo $sql;
$res = mysql_query($sql,$con);

$jsonArray = array();
$i = 0;
while($evt = mysql_fetch_assoc($res)) {
	
	$buildArray = array();
	$buildArray['id'] = $evt['boo_id'];
	$buildArray['start'] = $evt['begdat'];
	$buildArray['end'] = $evt['enddat'];
	$buildArray['title'] = (is_null($evt['evtnam'])) ? 'NO EVENT <br/> NO VENUE' : $evt['evtnam'].' <br/> '.$evt['vennam'];
	$buildArray['cusnam'] = $evt['vennam'];
	$buildArray['prdnam'] = $evt['prdnam'];
	$buildArray['boodur'] = number_format($evt['tothrs'],2);
	$buildArray['unipri'] = number_format($evt['unipri'],2);
	$buildArray['text'] = $evt['boodsc'];
	$buildArray['tblnam'] = $evt['tblnam'];
	$buildArray['tbl_id'] = $evt['tbl_id'];
	$buildArray['refnam'] = $evt['reftbl'];
	$buildArray['ref_id'] = $evt['ref_id'];
	$buildArray['prd_id'] = $evt['prd_id'];
	$buildArray['allDay'] = ($evt['allday'] == 1) ? true : false;
	$buildArray['backgroundColor'] = $evt['boocol'];
	
	$jsonArray[$i] = $buildArray;
	
	$i++;
	
}

die(json_encode($jsonArray));

?>