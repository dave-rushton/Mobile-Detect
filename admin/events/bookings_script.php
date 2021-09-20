<?php

require_once("../../config/config.php");
require_once("../patchworks.php");
require_once("classes/bookings.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);

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


$Boo_ID = (isset($_REQUEST['boo_id']) && is_numeric($_REQUEST['boo_id'])) ? $_REQUEST['boo_id'] : NULL;

if (is_null($Boo_ID)) {
	$throwJSON['title'] = 'Invalid Booking';
	$throwJSON['description'] = 'Booking not found';
	$throwJSON['type'] = 'error';
}

$BooDao = new BooDAO();

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update') {

	$BooObj = $BooDao->select($Boo_ID, NULL, NULL, NULL, NULL, NULL, NULL, NULL, true);
	
//	echo $_REQUEST['begdat'].'<br/>';
//	echo $_REQUEST['enddat'];
	
	if (!$BooObj) {
		
		$BooObj = new stdClass();
		$BooObj->boo_id = 0;
		$BooObj->boodsc = (isset($_REQUEST['boodsc'])) ? $_REQUEST['boodsc'] : '';
		$BooObj->actdat = (isset($_REQUEST['actdat'])) ? $_REQUEST['actdat'] : date("Y-m-d H:i:s");
		$BooObj->begdat = (isset($_REQUEST['begdat'])) ? $_REQUEST['begdat'] : date("Y-m-d H:i:s");
		$BooObj->enddat = (isset($_REQUEST['enddat'])) ? $_REQUEST['enddat'] : date("Y-m-d H:i:s");
		$BooObj->tblnam = (isset($_REQUEST['tblnam'])) ? $_REQUEST['tblnam'] : '';
		$BooObj->tbl_id = (isset($_REQUEST['tbl_id']) && is_numeric($_REQUEST['tbl_id'])) ? $_REQUEST['tbl_id'] : 0;
		$BooObj->reftbl = (isset($_REQUEST['reftbl'])) ? $_REQUEST['reftbl'] : 0;
		$BooObj->ref_id = (isset($_REQUEST['ref_id']) && is_numeric($_REQUEST['ref_id'])) ? $_REQUEST['ref_id'] : 0;
		$BooObj->sta_id = (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) ? $_REQUEST['sta_id'] : 0;
		
		$BooObj->prd_id = (isset($_REQUEST['prd_id']) && is_numeric($_REQUEST['prd_id'])) ? $_REQUEST['prd_id'] : 0;
		$BooObj->unipri = (isset($_REQUEST['unipri']) && is_numeric($_REQUEST['unipri'])) ? $_REQUEST['unipri'] : 0;
		$BooObj->buypri = (isset($_REQUEST['buypri']) && is_numeric($_REQUEST['buypri'])) ? $_REQUEST['buypri'] : 0;
		$BooObj->delpri = (isset($_REQUEST['delpri']) && is_numeric($_REQUEST['delpri'])) ? $_REQUEST['delpri'] : 0;
		
		$BooObj->alttyp = (isset($_REQUEST['alttyp']) && is_numeric($_REQUEST['alttyp'])) ? $_REQUEST['alttyp'] : 0;
		$BooObj->uplift = (isset($_REQUEST['uplift']) && is_numeric($_REQUEST['uplift'])) ? $_REQUEST['uplift'] : 0;
		
		$BooObj->cat_id = (isset($_REQUEST['cat_id']) && is_numeric($_REQUEST['cat_id'])) ? $_REQUEST['cat_id'] : 0;
		$BooObj->sub_id = (isset($_REQUEST['sub_id']) && is_numeric($_REQUEST['sub_id'])) ? $_REQUEST['sub_id'] : 0;
		
		$BooObj->allday = (isset($_REQUEST['allday']) && is_numeric($_REQUEST['allday'])) ? $_REQUEST['allday'] : 0;
		$BooObj->remtim = (isset($_REQUEST['remtim']) && is_numeric($_REQUEST['remtim'])) ? $_REQUEST['remtim'] : 0;
		
		$BooObj->boocol = (isset($_REQUEST['boocol'])) ? $_REQUEST['boocol'] : '';
				
		$Boo_ID = $BooDao->update($BooObj);
		
		$throwJSON['id'] = $Boo_ID;
		$throwJSON['title'] = 'Booking Created';
		$throwJSON['description'] = 'Booking '.$BooObj->boodsc.' created';
		$throwJSON['type'] = 'success';

		
	} else {
		
		$BooObj->boodsc = (isset($_REQUEST['boodsc'])) ? $_REQUEST['boodsc'] : $BooObj->boodsc;
		$BooObj->begdat = (isset($_REQUEST['begdat'])) ? $_REQUEST['begdat'] : $BooObj->begdat;
		$BooObj->enddat = (isset($_REQUEST['enddat'])) ? $_REQUEST['enddat'] : $BooObj->enddat;
		$BooObj->tblnam = (isset($_REQUEST['tblnam'])) ? $_REQUEST['tblnam'] : $BooObj->tblnam;
		$BooObj->tbl_id = (isset($_REQUEST['tbl_id']) && is_numeric($_REQUEST['tbl_id'])) ? $_REQUEST['tbl_id'] : $BooObj->tbl_id;
		
		$BooObj->reftbl = (isset($_REQUEST['reftbl'])) ? $_REQUEST['reftbl'] : 0;
		$BooObj->ref_id = (isset($_REQUEST['ref_id']) && is_numeric($_REQUEST['ref_id'])) ? $_REQUEST['ref_id'] : $BooObj->ref_id;
		
		if (isset($_REQUEST['prd_id']) && is_numeric($_REQUEST['prd_id'])) $BooObj->prd_id = $_REQUEST['prd_id'];
		
		if (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) $BooObj->sta_id = $_REQUEST['sta_id'];
		if (isset($_REQUEST['boocol'])) $BooObj->boocol = $_REQUEST['boocol'];
		
		$BooObj->allday = (isset($_REQUEST['allday']) && is_numeric($_REQUEST['allday'])) ? $_REQUEST['allday'] :  $BooObj->alldat;
		
		$Boo_ID = $BooDao->update($BooObj);
		
		$throwJSON['id'] = $Boo_ID;
		$throwJSON['title'] = 'Booking Updated';
		$throwJSON['description'] = 'Booking '.$BooObj->boodsc.' updated';
		$throwJSON['type'] = 'success';
		
	}

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete') {
	
	$BooObj = $BooDao->select($Boo_ID, NULL, NULL, NULL, NULL, NULL, NULL, NULL, true);
	
	if ($BooObj) {
		$BooDao->delete($BooObj->boo_id);
	
		$throwJSON['id'] = $Boo_ID;
		$throwJSON['title'] = 'Booking Deleted';
		$throwJSON['description'] = 'Booking '.$Boo_ID.' deleted';
		$throwJSON['type'] = 'success';
	} else {
		
		$throwJSON['id'] = $Boo_ID;
		$throwJSON['title'] = 'Booking No Found';
		$throwJSON['description'] = 'Booking not found';
		$throwJSON['type'] = 'error';	
	}
	
} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'select') {
	
	$booking = $BooDao->select($Boo_ID, NULL, NULL, NULL, NULL, NULL, NULL, NULL, false);
	die(json_encode($booking));

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'changestatus') {
	
	$BooLst = (isset($_REQUEST['boolst'])) ? $_REQUEST['boolst'] : NULL;
	$Sta_ID = (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) ? $_REQUEST['sta_id'] : NULL;
	
	$booking = $BooDao->changeStatus($BooLst, $Sta_ID);
	
	$throwJSON['id'] = 0;
	$throwJSON['title'] = 'Change Status';
	$throwJSON['description'] = 'Change status complete';
	$throwJSON['type'] = 'success';

}

die(json_encode($throwJSON));

//header('location: users.php');

?>