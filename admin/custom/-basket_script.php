<?php

require_once("../../config/config.php");
require_once("../patchworks.php");
require_once("../custom/classes/baskets.cls.php");

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


$Bsk_ID = (isset($_REQUEST['bsk_id']) && is_numeric($_REQUEST['bsk_id'])) ? $_REQUEST['bsk_id'] : NULL;
$BskDao = new BskDAO();

if (is_null($Bsk_ID)) {
	$throwJSON['title'] = 'Invalid Basket';
	$throwJSON['description'] = 'Basket not found';
	$throwJSON['type'] = 'error';
}

if (isset($_POST['jsonobj'])) {
    try {
        $basketRec = json_decode($_POST['jsonobj'], true);
    } catch (Exception $e) {
    }
}

if (isset($basketRec) && is_array($basketRec)) {

    $Bsk_ID = $basketRec['bsk_id'];

    if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update') {

        $BskObj = $BskDao->select($Bsk_ID, NULL, NULL, NULL, true);



        if (!$BskObj) {

            $BskObj = new stdClass();
            $BskObj->bsk_id = $basketRec['bsk_id'];
            $BskObj->bskttl = $basketRec['bskttl'];
            $BskObj->bskdsc = $basketRec['bskdsc'];
            $BskObj->unipri = $basketRec['unipri'];
            $BskObj->mrk_up = $basketRec['mrk_up'];
            $BskObj->bskimg = $basketRec['bskimg'];
            $BskObj->custom = $basketRec['custom'];
            $BskObj->sta_id = $basketRec['sta_id'];
            $BskObj->srtord = $basketRec['srtord'];
            $BskObj->bsktxt = $basketRec['bsktxt'];
            $BskObj->seourl = $basketRec['seourl'];
            $BskObj->keywrd = $basketRec['keywrd'];
            $BskObj->keydsc = $basketRec['keydsc'];
            $BskObj->atr_id = $basketRec['atr_id'];
            $BskObj->bsktag = $basketRec['bsktag'];
            $BskObj->customtext = $basketRec['customtext'];

            $BskObj->weight = $basketRec['weight'];
            $BskObj->vatrat = $basketRec['vatrat'];

            $BskObj->riblbl = $basketRec['riblbl'];
            $BskObj->ribcol = $basketRec['ribcol'];
            $BskObj->minord = $basketRec['minord'];

            $BskObj->products = [];

//            if (isset($basketRec['products']) && is_array($basketRec['products'])) {
//                for ($l = 0; $l < count($basketRec['products']); $l++) {
//                    $productRec = array();
//                    $productRec['prd_id'] = $basketRec['products'][$l]['prd_id'];
//                    $productRec['defsel'] = $basketRec['products'][$l]['defsel'];
//                    $productRec['bprext'] = $basketRec['products'][$l]['bprext'];
//                    $productRec['bprman'] = $basketRec['products'][$l]['bprman'];
//                    $productRec['extpri'] = $basketRec['products'][$l]['extpri'];
//                    $productRec['exttxt'] = $basketRec['products'][$l]['exttxt'];
//                    array_push($BskObj->products, $BskObj->products);
//                }
//            }

            $Bsk_ID = $BskDao->update($BskObj);

            $throwJSON['id'] = $Bsk_ID;
            $throwJSON['title'] = 'Basket Created';
            $throwJSON['description'] = 'Basket '.$BskObj->bskttl.' created';
            $throwJSON['type'] = 'success';


        } else {

            $BskObj->bsk_id = $basketRec['bsk_id'];
            $BskObj->bskttl = $basketRec['bskttl'];
            $BskObj->bskdsc = $basketRec['bskdsc'];
            $BskObj->unipri = $basketRec['unipri'];
            $BskObj->mrk_up = $basketRec['mrk_up'];
            $BskObj->bskimg = $basketRec['bskimg'];
            $BskObj->custom = $basketRec['custom'];
            $BskObj->sta_id = $basketRec['sta_id'];
            $BskObj->srtord = $basketRec['srtord'];
            $BskObj->bsktxt = $basketRec['bsktxt'];
            $BskObj->seourl = $basketRec['seourl'];
            $BskObj->keywrd = $basketRec['keywrd'];
            $BskObj->keydsc = $basketRec['keydsc'];
            $BskObj->atr_id = $basketRec['atr_id'];
            $BskObj->bsktag = $basketRec['bsktag'];
            $BskObj->customtext = $basketRec['customtext'];

            $BskObj->weight = $basketRec['weight'];
            $BskObj->vatrat = $basketRec['vatrat'];

            $BskObj->riblbl = $basketRec['riblbl'];
            $BskObj->ribcol = $basketRec['ribcol'];
            $BskObj->minord = $basketRec['minord'];

            $BskObj->products = [];

//            for ($l=0;$l<count($basketRec['products']);$l++) {
//                $productRec = array();
//                $productRec['prd_id'] = $basketRec['products'][$l]['prd_id'];
//                $productRec['defsel'] = $basketRec['products'][$l]['defsel'];
//                $productRec['bprext'] = $basketRec['products'][$l]['bprext'];
//                $productRec['bprman'] = $basketRec['products'][$l]['bprman'];
//                $productRec['extpri'] = $basketRec['products'][$l]['extpri'];
//                $productRec['exttxt'] = $basketRec['products'][$l]['exttxt'];
//                array_push($BskObj->products, $productRec);
//            }

            $BskObj->extras = [];

            for ($l=0;$l<count($basketRec['extras']);$l++) {
                $extraRec = array();
                $extraRec['bexttl'] = $basketRec['extras'][$l]['bexttl'];
                $extraRec['bextxt'] = $basketRec['extras'][$l]['bextxt'];
                $extraRec['bexpri'] = $basketRec['extras'][$l]['bexpri'];
                $extraRec['bexdef'] = $basketRec['extras'][$l]['bexdef'];
                $extraRec['bexman'] = $basketRec['extras'][$l]['bexman'];
                $extraRec['srtord'] = $basketRec['extras'][$l]['srtord'];
                array_push($BskObj->extras, $extraRec);
            }

            $Bsk_ID = $BskDao->update($BskObj);

            $throwJSON['id'] = $Bsk_ID;
            $throwJSON['title'] = 'Basket Updated';
            $throwJSON['description'] = 'Basket '.$BskObj->bskttl.' updated';
            $throwJSON['type'] = 'success';

        }


    }

}




if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update') {

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete') {
	
	$BskObj = $BskDao->select($Bsk_ID, NULL, NULL, NULL, true);
	if ($BskObj) $BskDao->delete($BskObj->bsk_id);

	$throwJSON['id'] = $BskObj->bsk_id;
	$throwJSON['title'] = 'Basket Deleted';
	$throwJSON['description'] = 'Basket '.$BskObj->bskttl.' deleted';
	$throwJSON['type'] = 'success';


} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'addgroup') {

    $BpgCls = new stdClass();
    $BpgCls->bpg_id = 0;
    $BpgCls->bsk_id = $_REQUEST['bsk_id'];
    $BpgCls->bpgttl = $_REQUEST['bpgttl'];
    $BpgCls->bpgmin = 1;
    $BpgCls->bpgmax = 1;
    $BpgCls->mulsel = 0;
    $BpgCls->srtord = 99;

    $TmpBpg = new BpgDAO();
    $Bpg_ID = $TmpBpg->update($BpgCls);

    $throwJSON['id'] = $Bpg_ID;
    $throwJSON['title'] = 'Basket Group Created';
    $throwJSON['description'] = 'Basket group created';
    $throwJSON['type'] = 'success';

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'deletegroup') {

    $TmpBpg = new BpgDAO();
    $Bpg_ID = $TmpBpg->delete($_REQUEST['bpg_id']);

    $throwJSON['id'] = $_REQUEST['bpg_id'];
    $throwJSON['title'] = 'Basket Group Delete';
    $throwJSON['description'] = 'Basket group deleted';
    $throwJSON['type'] = 'success';

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'resortgroup') {

    $TmpBpg = new BpgDAO();
    $Bpg_ID = $TmpBpg->resort($_REQUEST['bpg_id']);

    $throwJSON['id'] = 0;
    $throwJSON['title'] = 'Basket Group Resort';
    $throwJSON['description'] = 'Basket group resorted';
    $throwJSON['type'] = 'success';

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'addproduct') {

    $TmpBpr = new BprDAO();

    //
    // Check if product in basket?
    //

    if (is_array($_REQUEST['prd_id'])) {

        $prodExists = false;
        for ($p=0;$p<count($_REQUEST['prd_id']);$p++) {

            $addOK = $TmpBpr->checkBasketProduct($_REQUEST['bsk_id'], $_REQUEST['prd_id'][$p]);
            if (count($addOK) > 0) {
                $prodExists = true;
            }

            $BprCls = new stdClass();

            $BprCls->bpr_id = 0;
            $BprCls->bsk_id = $_REQUEST['bsk_id'];
            $BprCls->bpg_id = $_REQUEST['bpg_id'];
            $BprCls->prd_id = $_REQUEST['prd_id'][$p];
            $BprCls->defsel = 0; //$_REQUEST['defsel'];
            $BprCls->srtord = 99; //$_REQUEST['srtord'];
            $BprCls->bprext = 0; //$_REQUEST['bprext'];
            $BprCls->bprman = 0; //$_REQUEST['bprman'];
            $BprCls->extpri = 0; //$_REQUEST['extpri'];
            $BprCls->exttxt = ''; //$_REQUEST['exttxt'];
            $BprCls->bprqty = 1; //$_REQUEST['exttxt'];

            $Bpr_ID = $TmpBpr->update($BprCls);

        }

        if ($prodExists) {
            $throwJSON['id'] = 0; //$Bpr_ID;
            $throwJSON['title'] = 'Basket Group Product Exists';
            $throwJSON['description'] = 'Basket group product exists';
            $throwJSON['type'] = 'warning';
        } else {
            $throwJSON['id'] = $Bpr_ID;
            $throwJSON['title'] = 'Basket Group Product Created';
            $throwJSON['description'] = 'Basket product group created';
            $throwJSON['type'] = 'success';
        }

    } else {

        $addOK = $TmpBpr->checkBasketProduct($_REQUEST['bsk_id'], $_REQUEST['prd_id']);

        // If not add

        if (count($addOK) > 0) {

            $BprCls = new stdClass();

            $BprCls->bpr_id = 0;
            $BprCls->bsk_id = $_REQUEST['bsk_id'];
            $BprCls->bpg_id = $_REQUEST['bpg_id'];
            $BprCls->prd_id = $_REQUEST['prd_id'];
            $BprCls->defsel = 0; //$_REQUEST['defsel'];
            $BprCls->srtord = 99; //$_REQUEST['srtord'];
            $BprCls->bprext = 0; //$_REQUEST['bprext'];
            $BprCls->bprman = 0; //$_REQUEST['bprman'];
            $BprCls->extpri = 0; //$_REQUEST['extpri'];
            $BprCls->exttxt = ''; //$_REQUEST['exttxt'];
            $BprCls->bprqty = 1; //$_REQUEST['exttxt'];

            $Bpr_ID = $TmpBpr->update($BprCls);

            $throwJSON['id'] = 0; //$Bpr_ID;
            $throwJSON['title'] = 'Basket Group Product Exists';
            $throwJSON['description'] = 'Basket group product exists';
            $throwJSON['type'] = 'warning';

        } else {

            $BprCls = new stdClass();

            $BprCls->bpr_id = 0;
            $BprCls->bsk_id = $_REQUEST['bsk_id'];
            $BprCls->bpg_id = $_REQUEST['bpg_id'];
            $BprCls->prd_id = $_REQUEST['prd_id'];
            $BprCls->defsel = 0; //$_REQUEST['defsel'];
            $BprCls->srtord = 99; //$_REQUEST['srtord'];
            $BprCls->bprext = 0; //$_REQUEST['bprext'];
            $BprCls->bprman = 0; //$_REQUEST['bprman'];
            $BprCls->extpri = 0; //$_REQUEST['extpri'];
            $BprCls->exttxt = ''; //$_REQUEST['exttxt'];
            $BprCls->bprqty = 1; //$_REQUEST['exttxt'];

            $Bpr_ID = $TmpBpr->update($BprCls);

            $throwJSON['id'] = $Bpr_ID;
            $throwJSON['title'] = 'Basket Group Product Created';
            $throwJSON['description'] = 'Basket product group created';
            $throwJSON['type'] = 'success';

        }
    }

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'updategroupmulti') {

    $Bpg_ID = (isset($_REQUEST['bpg_id']) && is_numeric($_REQUEST['bpg_id'])) ? $_REQUEST['bpg_id'] : 0;
    $MulSel = (isset($_REQUEST['mulsel']) && is_numeric($_REQUEST['mulsel'])) ? $_REQUEST['mulsel'] : 0;

    $TmpBpg = new BpgDAO();
    $Bpg_ID = $TmpBpg->updatemulti($Bpg_ID, $MulSel);

    $throwJSON['id'] = $Bpg_ID;
    $throwJSON['title'] = 'Basket Group Updated';
    $throwJSON['description'] = 'Basket group updated';
    $throwJSON['type'] = 'success';

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'updateproductdefault') {

    $Bpr_ID = (isset($_REQUEST['bpr_id']) && is_numeric($_REQUEST['bpr_id'])) ? $_REQUEST['bpr_id'] : 0;
    $DefSel = (isset($_REQUEST['defsel']) && is_numeric($_REQUEST['defsel'])) ? $_REQUEST['defsel'] : 0;

    $TmpBpr = new BprDAO();
    $Bpr_ID = $TmpBpr->updatedefault($Bpr_ID, $DefSel);

    $throwJSON['id'] = $Bpr_ID;
    $throwJSON['title'] = 'Basket Group Product Updated';
    $throwJSON['description'] = 'Basket product group updated';
    $throwJSON['type'] = 'success';

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'updateproductmandatory') {

    $Bpr_ID = (isset($_REQUEST['bpr_id']) && is_numeric($_REQUEST['bpr_id'])) ? $_REQUEST['bpr_id'] : 0;
    $BprMan = (isset($_REQUEST['bprman']) && is_numeric($_REQUEST['bprman'])) ? $_REQUEST['bprman'] : 0;

    $TmpBpr = new BprDAO();
    $Bpr_ID = $TmpBpr->updatemandatory($Bpr_ID, $BprMan);

    $throwJSON['id'] = $Bpr_ID;
    $throwJSON['title'] = 'Basket Group Product Updated';
    $throwJSON['description'] = 'Basket product group updated';
    $throwJSON['type'] = 'success';

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'deleteproduct') {

    $TmpBpr = new BprDAO();
    $Bpr_ID = $TmpBpr->delete($_REQUEST['bpr_id']);

    $throwJSON['id'] = $_REQUEST['bpr_id'];
    $throwJSON['title'] = 'Basket Group Product Delete';
    $throwJSON['description'] = 'Basket group product deleted';
    $throwJSON['type'] = 'success';

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'updateproduct') {

    $TmpBpr = new BprDAO();
    $BprObj = $TmpBpr->select($_REQUEST['bpr_id'], NULL, NULL, true);

    $BprObj->extpri = $_REQUEST['extpri'];
    $BprObj->bprqty = $_REQUEST['bprqty'];

    $TmpBpr->update($BprObj);

    $throwJSON['id'] = $_REQUEST['bpr_id'];
    $throwJSON['title'] = 'Basket Group Product Update';
    $throwJSON['description'] = 'Basket group product updated';
    $throwJSON['type'] = 'success';

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'addextra') {

    $BexCls = new stdClass();

    $BexCls->bex_id = 0;
    $BexCls->bsk_id = $_REQUEST['bsk_id'];
    $BexCls->bexttl = $_REQUEST['bexttl'];
    $BexCls->bextxt = $_REQUEST['bextxt'];
    $BexCls->bexpri = $_REQUEST['bexpri'];
    $BexCls->bexdef = $_REQUEST['bexdef'];
    $BexCls->bexman = $_REQUEST['bexman'];
    $BexCls->srtord = 99;

    $TmpBex = new BexDAO();
    $Bex_ID = $TmpBex->update($BexCls);

    $throwJSON['id'] = $Bex_ID;
    $throwJSON['title'] = 'Basket Extra Created';
    $throwJSON['description'] = 'Basket Extra Created';
    $throwJSON['type'] = 'success';

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'deleteextra') {

    $TmpBex = new BexDAO();
    $Bex_ID = $TmpBex->delete($_REQUEST['bex_id']);

    $throwJSON['id'] = $_REQUEST['bex_id'];
    $throwJSON['title'] = 'Basket Extra Deleted';
    $throwJSON['description'] = 'Basket extra deleted';
    $throwJSON['type'] = 'success';

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'updategroup') {

    $TmpBpg = new BpgDAO();
    $BpgObj = $TmpBpg->select($_REQUEST['bpg_id'], NULL, NULL, true);

    $BpgObj->bpgttl = $_REQUEST['bpgttl'];
    $BpgObj->bpgmin = $_REQUEST['bpgmin'];
    $BpgObj->bpgmax = $_REQUEST['bpgmax'];

    $TmpBpg->update($BpgObj);

    $throwJSON['id'] = $_REQUEST['bpg_id'];
    $throwJSON['title'] = 'Basket Group Updates';
    $throwJSON['description'] = 'Basket group updated';
    $throwJSON['type'] = 'success';

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'resort') {

    $SrtOrd = (isset($_REQUEST['bsk_id'])) ? $_REQUEST['bsk_id'] : NULL;

    if (!is_null($SrtOrd)) {

        $SrtOrd = explode(",",$SrtOrd);

        for ($o=0; $o<count($SrtOrd); $o++) {

            $qryArray = array();
            $sql = 'UPDATE baskets SET
				srtord = :srtord
				WHERE bsk_id = :bsk_id';
            $qryArray["srtord"] = $o;
            $qryArray["bsk_id"] = $SrtOrd[$o];

            $recordSet = $patchworks->dbConn->prepare($sql);
            $recordSet->execute($qryArray);

        }

        $throwJSON['id'] = 0;
        $throwJSON['title'] = 'Baskets Resorted';
        $throwJSON['description'] = 'Baskets resorted';
        $throwJSON['type'] = 'success';

    }

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'checkname') {

    $TmpBsk = new BskDAO();
    $basketOK = $TmpBsk->checkName($_REQUEST['bsk_id'], $_REQUEST['bskttl'], $_REQUEST['seourl']);

    if ( count($basketOK) > 0 ) {
        $throwJSON['id'] = 0;
        $throwJSON['title'] = 'Basket Exists';
        $throwJSON['description'] = 'Basket title or SEO URL exists';
        $throwJSON['type'] = 'error';
    } else {
        $throwJSON['id'] = 0;
        $throwJSON['title'] = 'Basket Name OK';
        $throwJSON['description'] = 'Baskets name ok';
        $throwJSON['type'] = 'success';
    }

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'copygroup') {

    // find bpg
    $TmpBpg = new BpgDAO();
    $basketGroup = $TmpBpg->select($_REQUEST['bpg_id'], 0, NULL, true);

    // create new class with bpg data
    // update bpg
    $BpgCls = new stdClass();
    $BpgCls->bpg_id = 0;
    $BpgCls->bsk_id = $_REQUEST['bsk_id'];
    $BpgCls->bpgttl = $basketGroup->bpgttl;
    $BpgCls->bpgmin = $basketGroup->bpgmin;
    $BpgCls->bpgmax = $basketGroup->bpgmax;
    $BpgCls->mulsel = $basketGroup->mulsel;
    $BpgCls->srtord = 99;
    $Bpg_ID = $TmpBpg->update($BpgCls);

    $TmpBpr = new BprDAO();
    $basketProducts = NULL;
    $basketProducts = $TmpBpr->select(NULL, 0, $_REQUEST['bpg_id'], false);

    for ($i = 0; $i < count($basketProducts); $i++) {

        // loop bpg products
        // create new class with bgp data
        $BprCls = new stdClass();
        $BprCls->bpr_id = 0;
        $BprCls->bsk_id = $_REQUEST['bsk_id'];
        $BprCls->bpg_id = $Bpg_ID;
        $BprCls->prd_id = $basketProducts[$i]['prd_id'];
        $BprCls->defsel = $basketProducts[$i]['defsel'];
        $BprCls->srtord = $basketProducts[$i]['srtord'];
        $BprCls->bprext = $basketProducts[$i]['bprext'];
        $BprCls->bprman = $basketProducts[$i]['bprman'];
        $BprCls->extpri = $basketProducts[$i]['extpri'];
        $BprCls->exttxt = $basketProducts[$i]['exttxt'];
        $BprCls->bprqty = $basketProducts[$i]['bprqty'];
        //update bgp
        $Bpr_ID = $TmpBpr->update($BprCls);

    }


    $throwJSON['id'] = 0;
    $throwJSON['title'] = 'Group Copy OK';
    $throwJSON['description'] = 'Baskets group copy ok';
    $throwJSON['type'] = 'success';

}

die(json_encode($throwJSON));

?>