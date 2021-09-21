<?php

function seoUrl($string) {
    //Unwanted:  {UPPERCASE} ; / ? : @ & = + $ , . ! ~ * ' ( )
    $string = strtolower($string);
    //Strip any unwanted characters
    $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
    //Clean multiple dashes or whitespaces
    $string = preg_replace("/[\s-]+/", " ", $string);
    //Convert whitespaces and underscore to dash
    $string = preg_replace("/[\s_]/", "-", $string);
    return $string;
}

require('../../config/config.php');
require('../patchworks.php');
require('../products/classes/products.cls.php');
require('../products/classes/product_types.cls.php');
require('../attributes/classes/attrgroups.cls.php');
require('../system/classes/subcategories.cls.php');


$PrtDao = new PrtDAO();
$PrdDao = new PrdDAO();
$AtrDao = new AtrDAO();
$SubDao = new SubDAO();


error_reporting(E_ALL);
date_default_timezone_set('Europe/London');
ini_set('max_execution_time', 300);

require 'simplexlsx.php';

$rowNum = 0;

$prdUpdate = 0;
$prdCreate = 0;

//if (isset($_FILES['file'])) {

    $xlsx = new SimpleXLSX( $_FILES['file']['tmp_name'] );
	
	$xlsx = new SimpleXLSX( '../../Shopex3.xlsx' );
	
    /*** PRODUCTS ***/

    //list($num_cols, $num_rows) = $xlsx->dimension();
	
    $rowNum = 0;

    $productRecs = array();
	
	echo 'estimated rows: '.count( $xlsx->rows() ).'<br>';
	
    foreach( $xlsx->rows() as $r ) {
		
        $rowNum++;

        if ($rowNum < 3 || substr($r[0],0,3) == '---' || strtolower(substr($r[0],0,4)) == 'part') {
            continue;
        }
		
		//echo '<p>CELLS: '.count($r).'</p>';

        if (is_null($r[0]) || empty($r[0])) continue;

        //
        // FIND PRODUCT BY ALTREF
        //

        $productRec = $PrdDao->importCheck(NULL, NULL, $r[0]);

        if (empty($productRec[0]['prd_id']) || is_null($productRec[0]['prd_id']) || !is_array($productRec)) {

            echo 'CREATE: '.$r[1].' ('.$r[0].')<br>';

            $PrdObj = new stdClass();

            $PrdObj->prd_id = 0;
            $PrdObj->tblnam = '';
            $PrdObj->tbl_id = 0;
            $PrdObj->prt_id = 0; //$Prt_ID;
            $PrdObj->prdnam = $r[1];
            $PrdObj->prddsc = $r[4];
            $PrdObj->prdspc = $r[5];
            $PrdObj->unipri = $r[7];
            $PrdObj->buypri = $r[9];
            $PrdObj->delpri = 0;
            $PrdObj->sup_id = 0;
            $PrdObj->atr_id = 392; //$Atr_ID;
            $PrdObj->sta_id = (substr($r[1], 0, 1) == '#') ? 1 : 0;
            $PrdObj->seourl = seoUrl($r[0] + ' ' + $r[1]);
            $PrdObj->seokey = $r[1];
            $PrdObj->seodsc = $r[1];
            $PrdObj->prdtag = '';
            $PrdObj->usestk = 0;
            $PrdObj->in_stk = 0;
            $PrdObj->on_ord = 0;
            $PrdObj->on_del = 0;
			
			$AltRef = $r[0];
			if (!empty($r[8])) {
				//$AltRef .= ',' . $r[8];
			}
            $PrdObj->altref = $AltRef;
			$PrdObj->altnam = $r[8];
			
			$PrdDao->update($PrdObj);
			
            $prdCreate++;

        } else {

            echo 'UPDATE PRODUCT ID: '.$productRec[0]['prd_id'].' - '.$r[1].' ('.$r[0].')<br>';

            $PrdObj = new stdClass();

            $PrdObj->prd_id = $productRec[0]['prd_id'];
            $PrdObj->tblnam = '';
            $PrdObj->tbl_id = 0;
            $PrdObj->prt_id = 0; //$Prt_ID;
            $PrdObj->prdnam = $r[1];
            $PrdObj->prddsc = $r[4];
            $PrdObj->prdspc = $r[5];
            $PrdObj->unipri = $r[7];
            $PrdObj->buypri = $r[9];
            $PrdObj->delpri = 0;
            $PrdObj->sup_id = 0;
            $PrdObj->atr_id = 392; //$Atr_ID;
            $PrdObj->sta_id = (substr($r[1], 0, 1) == '#') ? 1 : 0;
            $PrdObj->seourl = seoUrl($r[0] + ' ' + $r[1]);
            $PrdObj->seokey = $r[1];
            $PrdObj->seodsc = $r[1];
            $PrdObj->prdtag = '';
            $PrdObj->usestk = 0;
            $PrdObj->in_stk = 0;
            $PrdObj->on_ord = 0;
            $PrdObj->on_del = 0;
            
			$AltRef = $r[0];
			if (!empty($r[8])) {
				//$AltRef .= ',' . $r[8];
			}
            $PrdObj->altref = $AltRef;
			$PrdObj->altnam = $r[8];

            $PrdDao->update($PrdObj);

            $prdUpdate++;

        }

    }


echo $prdCreate.' products created and '.$prdUpdate.' products updated';

?>