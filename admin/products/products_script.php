<?php

require_once("../../config/config.php");
require_once("../patchworks.php");
require_once("classes/products.cls.php");
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


$Prd_ID = (isset($_REQUEST['prd_id']) && is_numeric($_REQUEST['prd_id'])) ? $_REQUEST['prd_id'] : die('FAIL');

if (is_null($Prd_ID)) {
	$throwJSON['title'] = 'Invalid Product';
	$throwJSON['description'] = 'Product not found';
	$throwJSON['type'] = 'error';
}

$PrdDao = new PrdDAO();

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update') {

	$PrdObj = $PrdDao->select($Prd_ID, NULL, NULL, NULL, NULL, NULL, NULL, true);

    //
    // Check Product Name
    //

    $checkName = $PrdDao->checkProductName($Prd_ID, $_REQUEST['prdnam']);
    if ( isset($checkName->prdnam) ) {
        $throwJSON['title'] = 'Product Name Invalid';
        $throwJSON['description'] = 'Product name exists';
        $throwJSON['type'] = 'error';
        die(json_encode($throwJSON));
    }

	if (!$PrdObj) {
		
		$PrdObj = new stdClass();
		
		$PrdObj->prd_id = 0;
		$PrdObj->tblnam = '';
		$PrdObj->tbl_id = 0;
		$PrdObj->prt_id = 0;
		$PrdObj->prdnam = '';
		$PrdObj->prddsc = '';
		$PrdObj->prdspc = '';
		$PrdObj->unipri = 0;
		$PrdObj->buypri = 0;
		$PrdObj->delpri = 0;
		$PrdObj->sup_id = 0;
		$PrdObj->atr_id = 0;
		$PrdObj->sta_id = 0;
		
		$PrdObj->seourl = '';
		$PrdObj->seokey = '';
		$PrdObj->seodsc = '';
		
		$PrdObj->prdtag = '';
		
		$PrdObj->usestk = 0;
		$PrdObj->in_stk = 0;
		$PrdObj->on_ord = 0;
		$PrdObj->on_del = 0;

        $PrdObj->altref = '';
        $PrdObj->altnam = '';

        $PrdObj->weight = 0;
        $PrdObj->srtord = 1000;

        $PrdObj->vat_id = 0;
        $PrdObj->vegan = 0;
        $PrdObj->vegetarian = 0;
        $PrdObj->gluten_free = 0;

		if (isset($_REQUEST['tblnam'])) $PrdObj->tblnam = $_REQUEST['tblnam'];
		if (isset($_REQUEST['tbl_id']) && is_numeric($_REQUEST['tbl_id'])) $PrdObj->tbl_id = $_REQUEST['tbl_id'];
		if (isset($_REQUEST['prt_id'])) $PrdObj->prt_id = $_REQUEST['prt_id'];
		if (isset($_REQUEST['prdnam'])) $PrdObj->prdnam = $_REQUEST['prdnam'];
		if (isset($_REQUEST['prddsc'])) $PrdObj->prddsc = $_REQUEST['prddsc'];
		if (isset($_REQUEST['prdspc'])) $PrdObj->prdspc = $_REQUEST['prdspc'];
		if (isset($_REQUEST['unipri'])) $PrdObj->unipri = $_REQUEST['unipri'];
		if (isset($_REQUEST['buypri'])) $PrdObj->buypri = $_REQUEST['buypri'];
		if (isset($_REQUEST['delpri'])) $PrdObj->delpri = $_REQUEST['delpri'];
		if (isset($_REQUEST['sup_id'])) $PrdObj->sup_id = $_REQUEST['sup_id'];
		if (isset($_REQUEST['atr_id'])) $PrdObj->atr_id = $_REQUEST['atr_id'];
		if (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) $PrdObj->sta_id = $_REQUEST['sta_id'];
		
		if (isset($_REQUEST['seourl'])) $PrdObj->seourl = $_REQUEST['seourl'];
		if (isset($_REQUEST['seokey'])) $PrdObj->seokey = $_REQUEST['seokey'];
		if (isset($_REQUEST['seodsc'])) $PrdObj->seodsc = $_REQUEST['seodsc'];
		
		if (isset($_REQUEST['prdtag'])) $PrdObj->prdtag = $_REQUEST['prdtag'];
		
		if (isset($_REQUEST['usestk']) && is_numeric($_REQUEST['usestk'])) $PrdObj->usestk = $_REQUEST['usestk'];
		if (isset($_REQUEST['in_stk']) && is_numeric($_REQUEST['in_stk'])) $PrdObj->in_stk = $_REQUEST['in_stk'];
		if (isset($_REQUEST['on_ord']) && is_numeric($_REQUEST['on_ord'])) $PrdObj->on_ord = $_REQUEST['on_ord'];
		if (isset($_REQUEST['on_del']) && is_numeric($_REQUEST['on_del'])) $PrdObj->on_del = $_REQUEST['on_del'];

        if (isset($_REQUEST['altref'])) $PrdObj->altref = $_REQUEST['altref'];
        if (isset($_REQUEST['altnam'])) $PrdObj->altnam = $_REQUEST['altnam'];

        if (isset($_REQUEST['weight']) && is_numeric($_REQUEST['weight'])) $PrdObj->weight = $_REQUEST['weight'];

        if (isset($_REQUEST['srtord']) && is_numeric($_REQUEST['srtord'])) $PrdObj->srtord = $_REQUEST['srtord'];

        if (isset($_REQUEST['vat_id']) && is_numeric($_REQUEST['vat_id'])) $PrdObj->vat_id = $_REQUEST['vat_id'];
        if (isset($_REQUEST['vegan']) && is_numeric($_REQUEST['vegan'])) $PrdObj->vegan = $_REQUEST['vegan'];
        if (isset($_REQUEST['vegetarian']) && is_numeric($_REQUEST['vegetarian'])) $PrdObj->vegetarian = $_REQUEST['vegetarian'];
        if (isset($_REQUEST['gluten_free']) && is_numeric($_REQUEST['gluten_free'])) $PrdObj->gluten_free = $_REQUEST['gluten_free'];
		$Prd_ID = $PrdDao->update($PrdObj);
		
		$throwJSON['id'] = $Prd_ID;
		$throwJSON['title'] = 'Product Created';
		$throwJSON['description'] = 'Product '.$PrdObj->prdnam.' created';
		$throwJSON['type'] = 'success';

		
	} else {
		
		if (isset($_REQUEST['tblnam'])) $PrdObj->tblnam = $_REQUEST['tblnam'];
		if (isset($_REQUEST['tbl_id']) && is_numeric($_REQUEST['tbl_id'])) $PrdObj->tbl_id = $_REQUEST['tbl_id'];
		if (isset($_REQUEST['prt_id'])) $PrdObj->prt_id = $_REQUEST['prt_id'];
		if (isset($_REQUEST['prdnam'])) $PrdObj->prdnam = $_REQUEST['prdnam'];
		if (isset($_REQUEST['prddsc'])) $PrdObj->prddsc = $_REQUEST['prddsc'];
		if (isset($_REQUEST['prdspc'])) $PrdObj->prdspc = $_REQUEST['prdspc'];
		if (isset($_REQUEST['unipri'])) $PrdObj->unipri = $_REQUEST['unipri'];
		if (isset($_REQUEST['buypri'])) $PrdObj->buypri = $_REQUEST['buypri'];
		if (isset($_REQUEST['delpri'])) $PrdObj->delpri = $_REQUEST['delpri'];
		if (isset($_REQUEST['sup_id'])) $PrdObj->sup_id = $_REQUEST['sup_id'];
		if (isset($_REQUEST['atr_id'])) $PrdObj->atr_id = $_REQUEST['atr_id'];
		if (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) $PrdObj->sta_id = $_REQUEST['sta_id'];
		
		if (isset($_REQUEST['usestk']) && is_numeric($_REQUEST['usestk'])) $PrdObj->usestk = $_REQUEST['usestk'];
		if (isset($_REQUEST['in_stk']) && is_numeric($_REQUEST['in_stk'])) $PrdObj->in_stk = $_REQUEST['in_stk'];
		if (isset($_REQUEST['on_ord']) && is_numeric($_REQUEST['on_ord'])) $PrdObj->on_ord = $_REQUEST['on_ord'];
		if (isset($_REQUEST['on_del']) && is_numeric($_REQUEST['on_del'])) $PrdObj->on_del = $_REQUEST['on_del'];
		
		if (isset($_REQUEST['seourl'])) $PrdObj->seourl = $_REQUEST['seourl'];
		if (isset($_REQUEST['seokey'])) $PrdObj->seokey = $_REQUEST['seokey'];
		if (isset($_REQUEST['seodsc'])) $PrdObj->seodsc = $_REQUEST['seodsc'];
		
		if (isset($_REQUEST['prdtag'])) $PrdObj->prdtag = $_REQUEST['prdtag'];

        if (isset($_REQUEST['altref'])) $PrdObj->altref = $_REQUEST['altref'];
        if (isset($_REQUEST['altnam'])) $PrdObj->altnam = $_REQUEST['altnam'];

        if (isset($_REQUEST['weight']) && is_numeric($_REQUEST['weight'])) $PrdObj->weight = $_REQUEST['weight'];

        if (isset($_REQUEST['srtord']) && is_numeric($_REQUEST['srtord'])) $PrdObj->srtord = $_REQUEST['srtord'];

        if (isset($_REQUEST['vat_id']) && is_numeric($_REQUEST['vat_id'])) $PrdObj->vat_id = $_REQUEST['vat_id'];
        if (isset($_REQUEST['vegan']) && is_numeric($_REQUEST['vegan'])){
            $PrdObj->vegan = $_REQUEST['vegan'];
        } else{
            $PrdObj->vegan = 0;
        }

        if (isset($_REQUEST['vegetarian']) && is_numeric($_REQUEST['vegetarian'])){
            $PrdObj->vegetarian = $_REQUEST['vegetarian'];
        } else{
            $PrdObj->vegetarian = 0;
        }
        if (isset($_REQUEST['gluten_free']) && is_numeric($_REQUEST['gluten_free'])){
            $PrdObj->gluten_free = $_REQUEST['gluten_free'];
        } else{
            $PrdObj->gluten_free = 0;
        }
		$Prd_ID = $PrdDao->update($PrdObj);


		$throwJSON['id'] = $Prd_ID;
		$throwJSON['title'] = 'Product Updated';
		$throwJSON['description'] = 'Product '.$PrdObj->prdnam.' updated';
		$throwJSON['type'] = 'success';
		
	}

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete') {

    $PrdObj = $PrdDao->select($Prd_ID, NULL, NULL, NULL, NULL, NULL, NULL, true);
    if ($PrdObj) {

        $TmpBpr = new BprDAO();
        $checkProduct = $TmpBpr->checkBasketProduct(NULL, $Prd_ID);

        if (count($checkProduct) > 0) {

            $throwJSON['id'] = $PrdObj->prd_id;
            $throwJSON['title'] = 'Product Found In Basket';
            $throwJSON['description'] = 'Product ' . $PrdObj->prdnam . ' is associated to a basket';
            $throwJSON['type'] = 'error';

        } else {

            $Prd_ID = $PrdDao->delete($PrdObj->prd_id);

            if ($Prd_ID > 0) {

                $throwJSON['id'] = $PrdObj->prd_id;
                $throwJSON['title'] = 'Product Deleted';
                $throwJSON['description'] = 'Product ' . $PrdObj->prdnam . ' deleted';
                $throwJSON['type'] = 'success';

            } else {

                $throwJSON['id'] = $Prd_ID;
                $throwJSON['title'] = 'Product Found';
                $throwJSON['description'] = 'Product ' . $PrdObj->prdnam . ' exists in orders';
                $throwJSON['type'] = 'error';

            }
        }

    } else {

        $throwJSON['id'] = $Prd_ID;
        $throwJSON['title'] = 'Product No Found';
        $throwJSON['description'] = 'Product not found';
        $throwJSON['type'] = 'error';


    }

	
} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'select') {

    if ($Prd_ID == 0) $Prd_ID = NULL;
    $products = $PrdDao->select($Prd_ID, NULL, NULL, NULL, NULL, NULL, 'p.srtord', false);
    die(json_encode($products));

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'selectlight') {

    if ($Prd_ID == 0) $Prd_ID = NULL;
    $products = $PrdDao->selectLight($Prd_ID, NULL, NULL, NULL, NULL, NULL, 'p.srtord', false);
    die(json_encode($products));

}

die(json_encode($throwJSON));

?>