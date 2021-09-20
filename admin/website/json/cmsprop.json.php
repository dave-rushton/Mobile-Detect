<?php

require_once("../../../config/config.php");
require_once("../../patchworks.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);

if ($loggedIn == 0) header('location: ../login.php');

$qryArray = array();
$sql = "SELECT cmsobj FROM cmsprop WHERE cms_id = 1";
$cmsProp = $patchworks->run($sql, $qryArray, true);

$jsonArray = array();


if ($cmsProp) {
	$recordArray = array();
	$recordArray["cmsobj"] = json_decode($cmsProp->cmsobj);
	$jsonArray[] = $recordArray;
}

die(json_encode($jsonArray));