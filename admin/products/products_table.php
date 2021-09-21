<?php

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("classes/products.cls.php");

ini_set('memory_limit', '-1');

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$offset = (isset($_GET['iDisplayStart']) && is_numeric($_GET['iDisplayStart'])) ? (int)$_GET['iDisplayStart'] : 0;
$perpag = (isset($_GET['iDisplayLength']) && is_numeric($_GET['iDisplayLength'])) ? (int)$_GET['iDisplayLength'] : 50;
$search = (isset($_GET['sSearch']) && !empty($_GET['sSearch'])) ? $_GET['sSearch'] : NULL;

$sOrder = "p.prdnam";
if ( isset( $_GET['iSortCol_0'] ) )
{

    //if ($_GET['iSortCol_0'] == 0) $sOrder = 'filnam';
    if ($_GET['iSortCol_0'] == 2) $sOrder = 'p.prdnam';
    if ($_GET['iSortCol_0'] == 4) $sOrder = 'a.atrnam';
    //if ($_GET['iSortCol_0'] == 5) $sOrder = 'prt.prtnam';
    if ($_GET['iSortCol_0'] == 5) $sOrder = 'p.unipri';

    $sOrder .= ' '.$_GET['sSortDir_0'];

}



if (empty($search)) $search = NULL;

$TmpPrd = new PrdDAO();

// DATATABLES PASSING VARIABLES IN WRONG NAME ???

$productcount = $TmpPrd->select(NULL, NULL, NULL, NULL, NULL, $search, NULL, false, NULL, NULL);

$products = 	$TmpPrd->select(NULL, NULL, NULL, NULL, NULL, $search, $sOrder, false, $offset, $perpag);

$ajaxData = array();

$draw = (isset($_GET['sEcho']) && is_numeric($_GET['sEcho'])) ? (int)$_GET['sEcho'] : 1;

$ajaxData['sEcho'] = $draw;
$ajaxData['iTotalRecords'] = count($productcount);
$ajaxData['iTotalDisplayRecords'] = count($productcount);
$ajaxData['aaData'] = array();


$tableLength = count($products);
for ($i=0;$i<$tableLength;++$i) {

    $prdImage = '';

    if (
        file_exists($patchworks->docRoot.'uploads/images/169-130/'.$products[$i]['prdimg']) &&
        !is_dir($patchworks->docRoot.'uploads/images/169-130/'.$products[$i]['prdimg']))
    {
        $prdImage = '<img src="'.$patchworks->webRoot.'uploads/images/169-130/'.$products[$i]['prdimg'].'" class="img-responsive" />';
    } else {
        if (
            file_exists($patchworks->docRoot.'uploads/images/169-130/'.$products[$i]['prtimg']) &&
            !is_dir($patchworks->docRoot.'uploads/images/169-130/'.$products[$i]['prtimg']))
        {
            $prdImage = '<img src="'.$patchworks->webRoot.'uploads/images/169-130/'.$products[$i]['prtimg'].'" class="img-responsive" />';
        } else {
            $prdImage = '<img class="img-responsive" src="http://placehold.it/169x130&text=no image">';
        }
    }

    $tableRec = array($prdImage, $products[$i]['prd_id'], $products[$i]['prdnam'], $products[$i]['atr_id'], $products[$i]['atrnam'], $products[$i]['subnam'], number_format($products[$i]['unipri'],2), $products[$i]['unipri']*100 );

    //$tableRec = array($products[$i]['prd_id'], $products[$i]['prdnam'], number_format($products[$i]['unipri'],2), $products[$i]['unipri']*100 );

    array_push($ajaxData['aaData'], $tableRec);

}

die(json_encode($ajaxData));

?>