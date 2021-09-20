<?php
require_once("../../../config/config.php");
require_once("../../patchworks.php");
require_once("../../system/classes/places.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: login.php');


$searchPlaceID = (isset($_GET['pla_id']) && is_numeric($_GET['pla_id'])) ? $_GET['pla_id'] : NULL;
$searchPlaceName = (isset($_GET['planam']) && !empty($_GET['planam'])) ? $_GET['planam'] : NULL;
$TblNam = (isset($_GET['tblnam']) && !empty($_GET['tblnam'])) ? $_GET['tblnam'] : NULL;
$Tbl_ID = (isset($_GET['tbl_id']) && is_numeric($_GET['tbl_id'])) ? $_GET['tbl_id'] : NULL;

$TmpPla = new PlaDAO();
$places = $TmpPla->select($searchPlaceID, $TblNam, $Tbl_ID, $searchPlaceName);

die(json_encode($places));

?>