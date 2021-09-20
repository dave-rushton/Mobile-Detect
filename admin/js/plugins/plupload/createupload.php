<?php
require_once("../config/patchworks.php");

$qryArray = array();
$qryArray["filnam"] = '';
$qryArray["uplttl"] = (isset($_REQUEST['uplttl'])) ? $_REQUEST['uplttl'] : 'Image';
$qryArray["upldsc"] = (isset($_REQUEST['upldsc'])) ? $_REQUEST['upldsc'] : 'Image';
$qryArray["credat"] = date("Y-m-d H:i:s");
$qryArray["tblnam"] = (isset($_REQUEST['tblnam'])) ? $_REQUEST['tblnam'] : '';
$qryArray["tbl_id"] = (isset($_REQUEST['tbl_id']) && is_numeric($_REQUEST['tbl_id'])) ? $_REQUEST['tbl_id'] : 0;
$qryArray["filsiz"] = 0;
$qryArray["filtyp"] = '';
$qryArray["srtord"] = 99;
$qryArray["urllnk"] = '';

$sql = 'INSERT INTO uploads
		(
		filnam,
		uplttl,
		upldsc,
		credat,
		tblnam,
		tbl_id,
		filsiz,
		filtyp,
		srtord,
		urllnk
		)
		VALUES
		(
		:filnam,
		:uplttl,
		:upldsc,
		:credat,
		:tblnam,
		:tbl_id,
		:filsiz,
		:filtyp,
		:srtord,
		:urllnk
		);';

$recordSet = $patchworks->dbConn->prepare($sql);
$recordSet->execute($qryArray);

?>