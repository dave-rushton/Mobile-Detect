<?php

require_once("../../config/config.php");
require_once("../../admin/patchworks.php");
require_once("../../admin/ecommerce/classes/order.cls.php");
require_once("../../admin/ecommerce/classes/orderline.cls.php");
require_once("../../admin/custom/classes/bookings.cls.php");
require_once("../../admin/system/classes/places.cls.php");
require_once("../../admin/products/classes/products.cls.php");

$TmpBoo = new BooDAO();
$TmpPla = new PlaDAO();
$TmpBoo = new BooDAO();
$TmpPrd = new PrdDAO();
$OrdDao = new OrdDAO();
$OlnDao = new OlnDAO();

// FIND BOOKING

$bookingDate = $TmpBoo->select($_GET['boo_id'], NULL, NULL, NULL, NULL, NULL, NULL, NULL, true, NULL, NULL, 'begdat desc');
$venueRec = $TmpPla->select($bookingDate->ref_id, NULL, NULL, NULL, NULL, true);
$productRec = $TmpPrd->select($bookingDate->prd_id, NULL, NULL, NULL, NULL, true, NULL, true, NULL, NULL);
$eventRec = $TmpPla->select($bookingDate->tbl_id, NULL, NULL, NULL, NULL, true);

// CREATE ORDER

$OrdObj = new stdClass();
$OrdObj->ord_id = 0;
$OrdObj->ordtyp = 'SALE';
$OrdObj->invdat = date('Y-m-d');
$OrdObj->duedat = date('Y-m-d');
$OrdObj->paydat = date('Y-m-d');
$OrdObj->cusnam = $_POST['bookingname'];
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
$OrdObj->paytrm = $_POST['telephone'];
$OrdObj->vatrat = 20;
$OrdObj->tblnam = 'EVENT';
$OrdObj->tbl_id = $_POST['boo_id'];
$OrdObj->sta_id = 0;
$OrdObj->altref = '';
$OrdObj->altnam = '';
$OrdObj->del_id = 0;
$OrdObj->discod = '';
$OrdObj->emaadr = $_POST['emailaddress'];

$Ord_ID = $OrdDao->update($OrdObj);

$OlnObj = new stdClass();
$OlnObj->oln_id = 0;
$OlnObj->ord_id = $Ord_ID;
$OlnObj->prd_id = $productRec->prd_id;
$OlnObj->numuni = $_POST['numuni'];
$OlnObj->unipri = $productRec->unipri;
$OlnObj->vatrat = 20;
$OlnObj->olndsc = $productRec->prdnam;
$OlnObj->tblnam = 'EVENT';
$OlnObj->tbl_id = $_POST['boo_id'];
$OlnObj->sta_id = 0;

$OlnDao->update($OlnObj);

header('location: '.$patchworks->webRoot.'/'.$_POST['fwdurl'].'/eventbooking/success');
die();

?>
