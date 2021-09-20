<?php

require_once("../../config/config.php");
require_once("../patchworks.php");
require_once("../ecommerce/classes/order.cls.php");
require_once("../ecommerce/classes/orderline.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);

$OrdDao = new OrdDAO();
$OlnDao = new OlnDAO();

if ($loggedIn == 0) {
    die();
}

// FIND EVENT

// FIND PRODUCT

// CHECK AVAILABILITY

$OrdObj = new stdClass();

$OrdObj->ord_id = 0;
$OrdObj->ordtyp = 'SALE';
$OrdObj->invdat = date('Y-m-d');
$OrdObj->duedat = date('Y-m-d');
$OrdObj->paydat = date('Y-m-d');
$OrdObj->cusnam = $_POST['planam'];
$OrdObj->adr1 = $_POST['adr1'];
$OrdObj->adr2 = $_POST['adr2'];
$OrdObj->adr3 = $_POST['adr3'];
$OrdObj->adr4 = $_POST['adr4'];
$OrdObj->pstcod = $_POST['pstcod'];
$OrdObj->payadr1 = $_POST['adr1'];
$OrdObj->payadr2 = $_POST['adr2'];
$OrdObj->payadr3 = $_POST['adr3'];
$OrdObj->payadr4 = $_POST['adr4'];
$OrdObj->paypstcod = $_POST['pstcod'];
$OrdObj->paytrm = '';
$OrdObj->vatrat = 20;
$OrdObj->tblnam = 'EVENT';
$OrdObj->tbl_id = $_POST['boo_id'];
$OrdObj->sta_id = 0;

$Ord_ID = $OrdDao->update($OrdObj);

$OlnObj = new stdClass();
$OlnObj->oln_id = 0;
$OlnObj->ord_id = $Ord_ID;
$OlnObj->prd_id = $_POST['prd_id'];
$OlnObj->numuni = $_POST['numuni'];
$OlnObj->unipri = 0;
$OlnObj->vatrat = 20;
$OlnObj->olndsc = '';
$OlnObj->tblnam = 'EVENT';
$OlnObj->tbl_id = $_POST['boo_id'];
$OlnObj->sta_id = 0;

$OlnDao->update($OlnObj);

header('location: events.php');
die();

?>
