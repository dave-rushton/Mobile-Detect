<?php

require_once("../../config/config.php");
require_once("../patchworks.php");
require_once("classes/order.cls.php");
require_once("classes/orderline.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
//if ($loggedIn == 0) header('location: ../login.php');


$throwJSON = array();
$throwJSON['id'] = '0';
$throwJSON['title'] = 'noaction';
$throwJSON['description'] = 'no action taken';
$throwJSON['type'] = 'warning';

if ($loggedIn == 0) {
	
	//header('location: ../login.php');
		
	$throwJSON['title'] = 'Authorisation';
	$throwJSON['description'] = 'You are not authorised for this action';
	$throwJSON['type'] = 'error';
}


$Ord_ID = (isset($_REQUEST['ord_id']) && is_numeric($_REQUEST['ord_id'])) ? $_REQUEST['ord_id'] : die('FAIL');

if (is_null($Ord_ID)) {
	$throwJSON['title'] = 'Invalid Order';
	$throwJSON['description'] = 'Order not found';
	$throwJSON['type'] = 'error';
}

$OrdDao = new OrdDAO();
$OlnDao = new OlnDAO();

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update') {

	$OrdObj = $OrdDao->select($Ord_ID, NULL, NULL, NULL, true);
	
	if (!$OrdObj) {
		
		$OrdObj = new stdClass();
		
		$OrdObj->ord_id = 0;
		$OrdObj->ordtyp = 'UNKNOWN';
		$OrdObj->invdat = date('Y-m-d');
		$OrdObj->duedat = date('Y-m-d');
		$OrdObj->paydat = date('Y-m-d');
		$OrdObj->cusnam = '';
		$OrdObj->adr1 = '';
		$OrdObj->adr2 = '';
		$OrdObj->adr3 = '';
		$OrdObj->adr4 = '';
		$OrdObj->pstcod = '';
		$OrdObj->payadr1 = '';
		$OrdObj->payadr2 = '';
		$OrdObj->payadr3 = '';
		$OrdObj->payadr4 = '';
		$OrdObj->paypstcod = '';
		$OrdObj->paytrm = '';
		$OrdObj->vatrat = 0;
		$OrdObj->tblnam = '';
		$OrdObj->tbl_id = 0;
		$OrdObj->sta_id = 0;
        $OrdObj->altref = '';
        $OrdObj->alnam = '';
        $OrdObj->del_id = 0;
        $OrdObj->discod = '';
        $OrdObj->emaadr = '';
        $OrdObj->ordobj = '';

		if (isset($_REQUEST['ordtyp'])) $OrdObj->ordtyp = $_REQUEST['ordtyp'];
		if (isset($_REQUEST['invdat'])) $OrdObj->invdat = $_REQUEST['invdat'];
		if (isset($_REQUEST['duedat'])) $OrdObj->duedat = $_REQUEST['duedat'];
		if (isset($_REQUEST['paydat'])) $OrdObj->paydat = $_REQUEST['paydat'];
		if (isset($_REQUEST['cusnam'])) $OrdObj->cusnam = $_REQUEST['cusnam'];
		if (isset($_REQUEST['adr1'])) $OrdObj->adr1 = $_REQUEST['adr1'];
		if (isset($_REQUEST['adr2'])) $OrdObj->adr2 = $_REQUEST['adr2'];
		if (isset($_REQUEST['adr3'])) $OrdObj->adr3 = $_REQUEST['adr3'];
		if (isset($_REQUEST['adr4'])) $OrdObj->adr4 = $_REQUEST['adr4'];
		if (isset($_REQUEST['pstcod'])) $OrdObj->pstcod = $_REQUEST['pstcod'];
		if (isset($_REQUEST['ctynam'])) $OrdObj->ctynam = $_REQUEST['ctynam'];
		if (isset($_REQUEST['payadr1'])) $OrdObj->payadr1 = $_REQUEST['payadr1'];
		if (isset($_REQUEST['payadr2'])) $OrdObj->payadr2 = $_REQUEST['payadr2'];
		if (isset($_REQUEST['payadr3'])) $OrdObj->payadr3 = $_REQUEST['payadr3'];
		if (isset($_REQUEST['payadr4'])) $OrdObj->payadr4 = $_REQUEST['payadr4'];
		if (isset($_REQUEST['paypstcod'])) $OrdObj->paypstcod = $_REQUEST['paypstcod'];
		if (isset($_REQUEST['paytrm'])) $OrdObj->paytrm = $_REQUEST['paytrm'];
		if (isset($_REQUEST['vatrat']) && is_numeric($_REQUEST['vatrat'])) $OrdObj->vatrat = $_REQUEST['vatrat'];
		if (isset($_REQUEST['tblnam'])) $OrdObj->tblnam = $_REQUEST['tblnam'];
		if (isset($_REQUEST['tbl_id']) && is_numeric($_REQUEST['tbl_id'])) $OrdObj->tbl_id = $_REQUEST['tbl_id'];
		if (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) $OrdObj->sta_id = $_REQUEST['sta_id'];

        if (isset($_REQUEST['altref'])) $OrdObj->altref = $_REQUEST['altref'];
        if (isset($_REQUEST['altnam'])) $OrdObj->altnam = $_REQUEST['altnam'];
        if (isset($_REQUEST['del_id']) && is_numeric($_REQUEST['del_id'])) $OrdObj->del_id = $_REQUEST['del_id'];
        if (isset($_REQUEST['discod'])) $OrdObj->discod = $_REQUEST['discod'];
        if (isset($_REQUEST['emaadr'])) $OrdObj->emaadr = $_REQUEST['emaadr'];

        if (isset($_REQUEST['ordobj'])) $OrdObj->ordobj = $_REQUEST['ordobj'];

		$Ord_ID = $OrdDao->update($OrdObj);
		
		$throwJSON['id'] = $Ord_ID;
		$throwJSON['title'] = 'Order Created';
		$throwJSON['description'] = 'Order '.$OrdObj->paydat.' created';
		$throwJSON['type'] = 'success';

		
	} else {
		
		if (isset($_REQUEST['ordtyp'])) $OrdObj->ordtyp = $_REQUEST['ordtyp'];
		if (isset($_REQUEST['invdat'])) $OrdObj->invdat = $_REQUEST['invdat'];
		if (isset($_REQUEST['duedat'])) $OrdObj->duedat = $_REQUEST['duedat'];
		if (isset($_REQUEST['paydat'])) $OrdObj->paydat = $_REQUEST['paydat'];
		if (isset($_REQUEST['cusnam'])) $OrdObj->cusnam = $_REQUEST['cusnam'];
		if (isset($_REQUEST['adr1'])) $OrdObj->adr1 = $_REQUEST['adr1'];
		if (isset($_REQUEST['adr2'])) $OrdObj->adr2 = $_REQUEST['adr2'];
		if (isset($_REQUEST['adr3'])) $OrdObj->adr3 = $_REQUEST['adr3'];
		if (isset($_REQUEST['adr4'])) $OrdObj->adr4 = $_REQUEST['adr4'];
		if (isset($_REQUEST['pstcod'])) $OrdObj->pstcod = $_REQUEST['pstcod'];
		if (isset($_REQUEST['ctynam'])) $OrdObj->ctynam = $_REQUEST['ctynam'];
		if (isset($_REQUEST['payadr1'])) $OrdObj->payadr1 = $_REQUEST['payadr1'];
		if (isset($_REQUEST['payadr2'])) $OrdObj->payadr2 = $_REQUEST['payadr2'];
		if (isset($_REQUEST['payadr3'])) $OrdObj->payadr3 = $_REQUEST['payadr3'];
		if (isset($_REQUEST['payadr4'])) $OrdObj->payadr4 = $_REQUEST['payadr4'];
		if (isset($_REQUEST['paypstcod'])) $OrdObj->paypstcod = $_REQUEST['paypstcod'];
		if (isset($_REQUEST['paytrm'])) $OrdObj->paytrm = $_REQUEST['paytrm'];
		if (isset($_REQUEST['vatrat']) && is_numeric($_REQUEST['vatrat'])) $OrdObj->vatrat = $_REQUEST['vatrat'];
		if (isset($_REQUEST['tblnam'])) $OrdObj->tblnam = $_REQUEST['tblnam'];
		if (isset($_REQUEST['tbl_id']) && is_numeric($_REQUEST['tbl_id'])) $OrdObj->tbl_id = $_REQUEST['tbl_id'];
		if (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) $OrdObj->sta_id = $_REQUEST['sta_id'];

        if (isset($_REQUEST['altref'])) $OrdObj->altref = $_REQUEST['altref'];
        if (isset($_REQUEST['altnam'])) $OrdObj->altnam = $_REQUEST['altnam'];
        if (isset($_REQUEST['del_id']) && is_numeric($_REQUEST['del_id'])) $OrdObj->del_id = $_REQUEST['del_id'];
        if (isset($_REQUEST['discod'])) $OrdObj->discod = $_REQUEST['discod'];
        if (isset($_REQUEST['emaadr'])) $OrdObj->emaadr = $_REQUEST['emaadr'];

        if (isset($_REQUEST['ordobj'])) $OrdObj->ordobj = $_REQUEST['ordobj'];

		
		$Ord_ID = $OrdDao->update($OrdObj);
		
		$throwJSON['id'] = $Ord_ID;
		$throwJSON['title'] = 'Order Updated';
		$throwJSON['description'] = 'Order '.$OrdObj->paydat.' updated';
		$throwJSON['type'] = 'success';
		
	}

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete') {
	
	$OrdObj = $OrdDao->select($Ord_ID, NULL, NULL, NULL, true);
	if ($OrdObj) {
		$OrdDao->delete($OrdObj->ord_id);
	
		$throwJSON['id'] = $OrdObj->ord_id;
		$throwJSON['title'] = 'Order Deleted';
		$throwJSON['description'] = 'Order '.$OrdObj->paydat.' deleted';
		$throwJSON['type'] = 'success';
	} else {
		
		$throwJSON['id'] = $Ord_ID;
		$throwJSON['title'] = 'Order No Found';
		$throwJSON['description'] = 'Order not found';
		$throwJSON['type'] = 'error';

			
	}
	
} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'select') {
	
	$orders = $OrdDao->select($Ord_ID, NULL, NULL, NULL, false);
	$orderlines = $OlnDao->select($Ord_ID, NULL, false);
	
	$jsonObj['order'] = $orders;
	$jsonObj['orderlines'] = $orderlines;
	
	die(json_encode($jsonObj));
}

die(json_encode($throwJSON));

?>