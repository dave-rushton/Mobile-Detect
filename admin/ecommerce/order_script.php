<?php

require_once("../../config/config.php");
require_once("../patchworks.php");
require_once("classes/order.cls.php");
require_once("classes/orderline.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);

$throwJSON = array();
$throwJSON['id'] = '0';
$throwJSON['title'] = 'noaction';
$throwJSON['description'] = 'no action taken';
$throwJSON['type'] = 'warning';

if ($loggedIn == 0) {
	$throwJSON['title'] = 'Authorisation';
	$throwJSON['description'] = 'You are not authorised for this action';
	$throwJSON['type'] = 'error';
}

if (isset($_POST['jsonobj'])) {
    try {
        $orderRec = json_decode($_POST['jsonobj'], true);
    } catch (Exception $e) {
    }
}

//print_r( $orderRec['orderlines'] );


if (isset($orderRec) && is_array($orderRec)) {
	
	$OrdDao = new OrdDAO();
	$OlnDao = new OlnDAO();
	
	$Ord_ID = $orderRec['ord_id'];
	
	if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update') {
		
		$OrdObj = $OrdDao->select($Ord_ID, NULL, NULL, NULL, true);
		
		if (!$OrdObj) {
			
			$OrdObj = new stdClass();
			
			$OrdObj->ord_id = 0;
			$OrdObj->ordtyp = $orderRec['ordtyp'];
			$OrdObj->invdat = date('Y-m-d');
			$OrdObj->duedat = date('Y-m-d');
			$OrdObj->paydat = date('Y-m-d');
			$OrdObj->cusnam = $orderRec['cusnam'];
			$OrdObj->adr1 = $orderRec['adr1'];
			$OrdObj->adr2 = $orderRec['adr2'];
			$OrdObj->adr3 = $orderRec['adr3'];
			$OrdObj->adr4 = $orderRec['adr4'];
			$OrdObj->pstcod = $orderRec['pstcod'];
			$OrdObj->payadr1 = $orderRec['payadr1'];
			$OrdObj->payadr2 = $orderRec['payadr2'];
			$OrdObj->payadr3 = $orderRec['payadr3'];
			$OrdObj->payadr4 = $orderRec['payadr4'];
			$OrdObj->paypstcod = $orderRec['paypstcod'];
			$OrdObj->paytrm = $orderRec['paytrm'];
			$OrdObj->vatrat = $orderRec['vatrat'];
			$OrdObj->tblnam = $orderRec['tblnam'];
			$OrdObj->tbl_id = $orderRec['tbl_id'];
			$OrdObj->sta_id = $orderRec['sta_id'];
					
			$OrdObj->invdat = $orderRec['invdat'];
			$OrdObj->duedat = $orderRec['duedat'];
			$OrdObj->paydat = $orderRec['paydat'];

            $OrdObj->altref = $orderRec['altref'];
            $OrdObj->altnam = $orderRec['altnam'];
            $OrdObj->del_id = $orderRec['del_id'];
            $OrdObj->discod = $orderRec['discod'];
            $OrdObj->emaadr = $orderRec['emaadr'];

			
			$Ord_ID = $OrdDao->update($OrdObj);
			
			for ($l=0;$l<count($orderRec['orderlines']);$l++) {
			
				$OlnObj = new stdClass();
				$OlnObj->oln_id = 0;
				$OlnObj->ord_id = $Ord_ID;
				$OlnObj->prd_id = $orderRec['orderlines'][$l]['prd_id'];
				$OlnObj->numuni = $orderRec['orderlines'][$l]['numuni'];
				$OlnObj->unipri = $orderRec['orderlines'][$l]['unipri'];
				$OlnObj->vatrat = $orderRec['orderlines'][$l]['vatrat'];
				$OlnObj->olndsc = $orderRec['orderlines'][$l]['olndsc'];
				$OlnObj->tblnam = $orderRec['orderlines'][$l]['tblnam'];
				$OlnObj->tbl_id = $orderRec['orderlines'][$l]['tbl_id'];
				$OlnObj->sta_id = $orderRec['orderlines'][$l]['sta_id'];
                $OlnObj->vatrat = $orderRec['orderlines'][$l]['vatrat'];
				
				$OlnDao->update($OlnObj);
				
			}
			
			$throwJSON['id'] = $Ord_ID;
			$throwJSON['title'] = 'Order Created';
			$throwJSON['description'] = 'Order '.$OrdObj->ord_id.' created';
			$throwJSON['type'] = 'success';
	
			
		} else {
			
			$OrdObj->ordtyp = $orderRec['ordtyp'];
			$OrdObj->cusnam = $orderRec['cusnam'];
			$OrdObj->adr1 = $orderRec['adr1'];
			$OrdObj->adr2 = $orderRec['adr2'];
			$OrdObj->adr3 = $orderRec['adr3'];
			$OrdObj->adr4 = $orderRec['adr4'];
			$OrdObj->pstcod = $orderRec['pstcod'];
			$OrdObj->payadr1 = $orderRec['payadr1'];
			$OrdObj->payadr2 = $orderRec['payadr2'];
			$OrdObj->payadr3 = $orderRec['payadr3'];
			$OrdObj->payadr4 = $orderRec['payadr4'];
			$OrdObj->paypstcod = $orderRec['paypstcod'];
			$OrdObj->paytrm = $orderRec['paytrm'];
			$OrdObj->vatrat = $orderRec['vatrat'];
			$OrdObj->tblnam = $orderRec['tblnam'];
			$OrdObj->tbl_id = $orderRec['tbl_id'];
			$OrdObj->sta_id = $orderRec['sta_id'];
			
			$OrdObj->invdat = $orderRec['invdat'].' '.date("H:i:s", strtotime($OrdObj->invdat));
			$OrdObj->duedat = $orderRec['duedat'].' '.date("H:i:s", strtotime($OrdObj->duedat));
			$OrdObj->paydat = $orderRec['paydat'].' '.date("H:i:s", strtotime($OrdObj->paydat));

            $OrdObj->altref = $orderRec['altref'];
            $OrdObj->altnam = $orderRec['altnam'];
            $OrdObj->del_id = $orderRec['del_id'];
            $OrdObj->discod = $orderRec['discod'];
            $OrdObj->emaadr = $orderRec['emaadr'];
			
			$Ord_ID = $OrdDao->update($OrdObj);
			
			$OlnDao->cleanLines($Ord_ID);
			
			for ($l=0;$l<count($orderRec['orderlines']);$l++) {
			
				$OlnObj = new stdClass();
				$OlnObj->oln_id = 0;
				$OlnObj->ord_id = $Ord_ID;
				$OlnObj->prd_id = $orderRec['orderlines'][$l]['prd_id'];
				$OlnObj->numuni = $orderRec['orderlines'][$l]['numuni'];
				$OlnObj->unipri = $orderRec['orderlines'][$l]['unipri'];
				$OlnObj->vatrat = $orderRec['orderlines'][$l]['vatrat'];
				$OlnObj->olndsc = $orderRec['orderlines'][$l]['olndsc'];
				$OlnObj->tblnam = $orderRec['orderlines'][$l]['tblnam'];
				$OlnObj->tbl_id = $orderRec['orderlines'][$l]['tbl_id'];
				$OlnObj->sta_id = $orderRec['orderlines'][$l]['sta_id'];
                $OlnObj->vatrat = $orderRec['orderlines'][$l]['vatrat'];
				
				$OlnDao->update($OlnObj);
				
			}
			
			$throwJSON['id'] = $Ord_ID;
			$throwJSON['title'] = 'Order Updated';
			$throwJSON['description'] = 'Order '.$OrdObj->ord_id.' updated';
			$throwJSON['type'] = 'success';
			
		}
	
	} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete') {
		
		$OrdObj = $OrdDao->select($Ord_ID, NULL, NULL, NULL, true);
		if ($OrdObj) {
			$OrdDao->delete($OrdObj->ord_id);
		
			$throwJSON['id'] = $OrdObj->ord_id;
			$throwJSON['title'] = 'Order Deleted';
			$throwJSON['description'] = 'Order '.$OrdObj->ord_id.' deleted';
			$throwJSON['type'] = 'success';
		} else {
			
			$throwJSON['id'] = $Ord_ID;
			$throwJSON['title'] = 'Order No Found';
			$throwJSON['description'] = 'Order not found';
			$throwJSON['type'] = 'error';
				
		}
		
	} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'select') {
		
		$orders = $OrdDao->select($Ord_ID, NULL, NULL, NULL, false);
		die(json_encode($orders));
	}
	
	die(json_encode($throwJSON));
	
} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'changestatus') {

    //
    // UPDATE STATUS
    //

    $OrdDao = new OrdDAO();
    $OlnDao = new OlnDAO();

    $orderIDs = explode(",",$_REQUEST['ord_id']);

    for ($o = 0; $o < count($orderIDs); $o++) {

        $OrdObj = $OrdDao->select($orderIDs[$o], NULL, NULL, NULL, true);
        $OrdObj->sta_id = $_REQUEST['sta_id'];
        $Ord_ID = $OrdDao->update($OrdObj);

    }

    $throwJSON['title'] = 'Status Updated';
    $throwJSON['description'] = 'The status of the selected orders have been updated';
    $throwJSON['type'] = 'success';


    //
    // Send Email
    //



    die(json_encode($throwJSON));

} else {

	$throwJSON['title'] = 'Data Error';
	$throwJSON['description'] = 'The server could not process the request';
	$throwJSON['type'] = 'error';

}

/*
Array
(
    [ord_id] => 2
    [ordtyp] => SALE
    [adr1] => 2a Park Road
    [adr2] => Wellingborough
    [adr3] => Northampton
    [adr4] => 
    [pstcod] => NN8 4DJ
    [payadr1] => 2a Park Road
    [payadr2] => Wellingborough
    [payadr3] => Northampton
    [payadr4] => 
    [paypstcod] => NN8 4DJ
    [paytrm] => Payment due within 30 days of invoice
Please make checks payable to ABC
    [vatrat] => 0.00
    [tblnam] => CUS
    [tbl_id] => 54
    [sta_id] => 0
    [orderlines] => Array
        (
            [0] => Array
                (
                    [ord_id] => 2
                    [prd_id] => 30
                    [numuni] => 10
                    [unipri] => 19.00
                    [vatrat] => 0.00
                    [olndsc] => 
                    [tblnam] => SALE
                    [tbl_id] => 0
                    [sta_id] => 0
                )

        )

)
*/


//print_r($orderRec);

?>