<?php

function seoUrl($string) {
    //Unwanted:  {UPPERCASE} ; / ? : @ & = + $ , . ! ~ * ' ( )
    $string = strtolower($string);
	
	$string = str_replace('+','plus',$string);
	
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
require('../ecommerce/classes/vat.cls.php');


$PrtDao = new PrtDAO();
$PrdDao = new PrdDAO();
$AtrDao = new AtrDAO();
$SubDao = new SubDAO();
$VatDao = new VatDAO();

$vatRate = 0;
$vatRec = $VatDao->select(NULL, date("Y-m-d"), NULL, true);
if (!is_null($vatRec) && isset($vatRec->vatrat)) {
    $vatRate = $vatRec->vatrat;
}


error_reporting(E_ALL);
date_default_timezone_set('Europe/London');
ini_set('max_execution_time', 300);

require 'simplexlsx.php';

$rowNum = 0;

$prdUpdate = 0;
$prdCreate = 0;

$impOK = true;

if (isset($_FILES['file'])) {

    $xlsx = new SimpleXLSX( $_FILES['file']['tmp_name'] );
	
	//$xlsx = new SimpleXLSX( '../../Shopex3.xlsx' );
	
    /*** PRODUCTS ***/

    //list($num_cols, $num_rows) = $xlsx->dimension();
	
    $rowNum = 0;

    $productRecs = array();
	
	echo 'estimated rows: '.count( $xlsx->rows() ).'<br>';
	
    foreach( $xlsx->rows() as $r ) {
		
        $rowNum++;

        if ($rowNum == 1) continue;

        // Find Product

        $PrdSeo = seoUrl($r[0]);
        $PrdNam = $r[0];

        $productRec = $PrdDao->select(NULL, NULL, NULL, NULL, NULL, $PrdNam, NULL, true, NULL, NULL);
        if (isset($productRec->prdnam) && !empty(isset($productRec->prdnam))) {

            if ($productRec->in_stk == $r[3]) continue;

            // Update Stock

            // Update Product

            echo '<div class="alert alert-success">';
            echo '&quot;'.$r[0].'&quot; Found<br>';
            echo 'ROW: '.$rowNum.'<br>';
            echo 'Current Stock: '.$productRec->in_stk.'<br>';
            echo 'Update Stock To: '.$r[3].'<br>';
            echo '</div>';

        } else {

            $impOK = false;

            echo '<div class="alert alert-danger">';
            echo '&quot;'.$r[0].'&quot; not Found<br>';
            echo 'ROW: '.$rowNum.'<br>';
            echo '</div>';

        }


    }

    //die('<pre>'.print_r($productRecs).'</pre>');
}

$updatedRecords = 0;

if ($impOK == true) {

    foreach( $xlsx->rows() as $r ) {

        $rowNum++;

        if ($rowNum == 1) continue;

        // Find Product

        $PrdSeo = seoUrl($r[0]);
        $PrdNam = $r[0];

        $productRec = $PrdDao->select(NULL, NULL, NULL, NULL, NULL, $PrdNam, NULL, true, NULL, NULL);
        if (isset($productRec->prdnam)) {

            if ($productRec->in_stk == $r[3]) continue;

            $productRec->in_stk = $r[3];
            $PrdDao->update($productRec);

            $updatedRecords++;

        } else {

        }


    }

}

echo $updatedRecords.' products updated';


?>