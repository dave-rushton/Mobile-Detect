<?php

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("classes/product_types.cls.php");
require_once("../system/classes/subcategories.cls.php");
$TmpSub = new SubDAO();
$manufacture = $TmpSub->selectByTableName('manufacturer-types');
$getname = array();
foreach ($manufacture as $m){
    if($m >-1){
        $getname[$m['sub_id']] = $m['subnam'];
    }
}

ini_set('memory_limit', '-1');

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$offset = (isset($_GET['iDisplayStart']) && is_numeric($_GET['iDisplayStart'])) ? (int)$_GET['iDisplayStart'] : 0;
$perpag = (isset($_GET['iDisplayLength']) && is_numeric($_GET['iDisplayLength'])) ? (int)$_GET['iDisplayLength'] : 5000;
$search = (isset($_GET['sSearch']) && !empty($_GET['sSearch'])) ? $_GET['sSearch'] : NULL;



//$file = 'prdtypetable.txt';
//$current = file_get_contents($file);
//$current .= "iDisplayLength: ".$perpag." iDisplayStart: ".$offset."\r\n";
//file_put_contents($file, $current);


//die($offset.' '.$perpag);

$sOrder = "p.prtnam";

if ( isset( $_GET['iSortCol_0'] ) )
{

    //if ($_GET['iSortCol_0'] == 0) $sOrder = 'filnam';
    if ($_GET['iSortCol_0'] == 2) $sOrder = 'p.prtnam';
    //if ($_GET['iSortCol_0'] == 4) $sOrder = 'a.atrnam';
    //if ($_GET['iSortCol_0'] == 5) $sOrder = 'prt.prtnam';
    //if ($_GET['iSortCol_0'] == 5) $sOrder = 'p.unipri';

    $sOrder .= ' '.$_GET['sSortDir_0'];

}



if (empty($search)) $search = NULL;

$TmpPrt = new PrtDAO();

if ($offset > 0) $offset = ($offset / $perpag) + 1;

$productcount = $TmpPrt->select(NULL, NULL, NULL, NULL, $search, NULL, NULL, NULL, false);
$products = $TmpPrt->select(NULL, NULL, NULL, NULL, $search, NULL, $perpag, $offset, false);

$ajaxData = array();

$draw = (isset($_GET['sEcho']) && is_numeric($_GET['sEcho'])) ? (int)$_GET['sEcho'] : 1;

$ajaxData['sEcho'] = $draw;
$ajaxData['iTotalRecords'] = count($productcount);
$ajaxData['iTotalDisplayRecords'] = count($productcount);
$ajaxData['aaData'] = array();




//$file = 'prdtypetable.txt';
//$current = file_get_contents($file);
//$current .= "perpag: ".$perpag." offset: ".$offset."\r\n";
//$current .= "totalrecs: ".count($productcount)." pagedrecords: ".count($products)."\r\n";
//file_put_contents($file, $current);



$tableLength = count($products);
for ($i=0;$i<$tableLength;++$i) {

    $productImage = $TmpPrt->getProductTypeImage($products[$i]['prt_id']);

    $prdImage = '';

    if (isset($productImage) && is_array($productImage)) {

        if (
            isset($productImage[0]) &&
            file_exists($patchworks->docRoot . 'uploads/images/products/169-130/' . $productImage[0]['filnam']) &&
            !is_dir($patchworks->docRoot . 'uploads/images/products/169-130/' . $productImage[0]['filnam'])
        ) {
            $prdImage = '<img src="' . $patchworks->webRoot . 'uploads/images/products/169-130/' . $productImage[0]['filnam'] . '" class="img-responsive" />';
        } else {



        }
    }

    //$tableRec = array($prdImage, '<a href="products/product-edit.php?prd_id='.$products[$i]['prd_id'].'">'.$products[$i]['prdnam'].'</a>', '<a href="products/productgroup-edit.php?atr_id='.$products[$i]['atr_id'].'">'.$products[$i]['atrnam'].'</a>', '<a href="products/producttype-edit.php?prt_id='.$products[$i]['prt_id'].'">'.$products[$i]['prtnam'].'</a>', number_format($products[$i]['unipri'],2) );
    $machine_type = (!empty($products[$i]['machine_type'])?$products[$i]['machine_type']:"Bandsaw");
    $machine_type = str_replace("-"," ",$machine_type);

    $manufacture_name = "";
    if(!empty($getname[$products[$i]['manufacturer']])){
        $manufacture_name = $getname[$products[$i]['manufacturer']];
    }

    $tableRec = array(
//        ,
        $products[$i]['prt_id'],
        //change here
        $manufacture_name,
        $products[$i]['prtnam'],
        $machine_type,
        $products[$i]['done'],

//        $prdImage
    );

    array_push($ajaxData['aaData'], $tableRec);

}

die(json_encode($ajaxData));

?>