<?php





function convertCurrency($amount, $from, $to){

    $url  = "https://www.google.com/finance/converter?a=$amount&from=$from&to=$to";

    $data = file_get_contents($url);

    preg_match("/<span class=bld>(.*)<\/span>/",$data, $converted);

    $converted = preg_replace("/[^0-9.]/", "", $converted[1]);

    return round($converted, 2);

}



$currConv = 1;

$dispCurr = '&pound;';



if (isset($_POST['currency']) && $_POST['currency'] != 'GBP') {



    $currConv = convertCurrency(1,'GBP','USD');

    $dispCurr = '$';



}



require_once('../../config/config.php');

require_once('../../admin/patchworks.php');

require_once("../../admin/website/classes/pageelements.cls.php");

require_once("../../admin/attributes/classes/attrgroups.cls.php");

require_once("../../admin/attributes/classes/attrlabels.cls.php");

require_once("../../admin/attributes/classes/attrvalues.cls.php");

require_once("../../admin/products/classes/product_types.cls.php");

require_once("../../admin/products/classes/products.cls.php");

require_once("../../admin/gallery/classes/uploads.cls.php");

require_once("../../admin/system/classes/subcategories.cls.php");

require_once("../../admin/system/classes/categories.cls.php");

require_once("../../admin/system/classes/related.cls.php");



$EleDao = new PelDAO();



$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;

$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);

if (!$EleObj) die();



$PerPag = (isset($_GET['perpag']) && is_numeric($_GET['perpag'])) ? $_GET['perpag'] : NULL;

$OffSet = (isset($_GET['offset']) && is_numeric($_GET['offset'])) ? $_GET['offset'] : NULL;

$Pag_No = (isset($_GET['pag_no']) && is_numeric($_GET['pag_no'])) ? $_GET['pag_no'] : 1;



if (!isset($OffSet) || !is_numeric($OffSet)) {

    $OffSet = ($Pag_No - 1) * $PerPag;

}



$SeoUrl = (isset($_GET['seourl'])) ? $_GET['seourl'] : NULL;

$FwdUrl = $EleDao->getVariable($EleObj, 'fwdurl');



if (!is_null($FwdUrl) && !empty($FwdUrl)) $SeoUrl = $FwdUrl;



$Pag_No = (isset($_GET['pag_no']) && is_numeric($_GET['pag_no'])) ? $_GET['pag_no'] : 1;

$PerPag = $EleDao->getVariable($EleObj, 'perpag');

if (empty($PerPag) || is_null($PerPag)) $PerPag = 12;





//$PerPag = NULL;

//$Pag_No = NULL;

//$OffSet = NULL;



$TmpCatSeo = $EleDao->getVariable($EleObj, 'catseo');

$TmpAtrSeo = $EleDao->getVariable($EleObj, 'atrseo');



$CatSeo = (isset($_GET['catseo'])) ? $_GET['catseo'] : NULL;

$AtrSeo = (isset($_GET['atrseo'])) ? $_GET['atrseo'] : NULL;

$PrtSeo = (isset($_GET['prtseo'])) ? $_GET['prtseo'] : NULL;

$PrdSeo = (isset($_GET['prdseo'])) ? $_GET['prdseo'] : NULL;

$PrdCat = (isset($_GET['prdcat'])) ? $_GET['prdcat'] : NULL;



$SrtOrd = (isset($_GET['srtord'])) ? $_GET['srtord'] : 'p.unipri DESC';



$DspTyp = 'UNKNOWN';



$NumCol = 3;

$NumCol = $EleDao->getVariable($EleObj, 'numcol');

if (!is_numeric($NumCol)) $NumCol = 3;



$ColWid = 12 / $NumCol;



//print_r($_GET);



if (!is_null($PrdCat)) {



    $DspTyp = 'ProductsByCategories';



    $TmpSub = new SubDAO();

    $subCategories = $TmpSub->selectBySeoUrl($PrdCat, true);



    $UplDao = new UplDAO();



    $TmpPrd = new PrdDAO();



    if (isset($AtrSeo)) {

        $subCats = $subCategories->sub_id;

    } else {

        $subCats = $subCategories->sub_id . ',10';

    }



    $products = $TmpPrd->searchProductsByCategory($subCats, NULL, NULL, NULL);



    $TmpAtr = new AtrDAO();

    $productGroups = $TmpAtr->searchByCategory($subCategories->sub_id, NULL, NULL, NULL);





} else if (!is_null($PrdSeo)) {



    //

    // Product Detail Page (PRD)

    //



    $DspTyp = 'Product';



    $TmpPrd = new PrdDAO();

    $TmpPrt = new PrtDAO();

    $TmpRel = new RelDAO();



    $product = $TmpPrd->select(NULL, $PrdSeo, NULL, NULL, NULL, NULL, NULL, true, NULL, NULL, 0);



    $relatedProducts = $TmpRel->relatedProducts(NULL, 'PRODUCT', $product->prd_id, 'PRODUCT', NULL, false, "RAND()", true);



    $UplDao = new UplDAO();

    $uploads = $UplDao->select(NULL, 'PRODUCT', $product->prd_id, NULL, false);

    $pdfs = $UplDao->select(NULL, 'PRODUCTFIL', $product->prd_id, NULL, false);



} else if (!is_null($PrtSeo)) {



    //

    // Product Type Page (PRD)

    //



    $DspTyp = 'ProductType';



    $TmpPrd = new PrdDAO();

    $TmpPrt = new PrtDAO();



    $productType = $TmpPrt->select(NULL, $_GET['prtseo'], NULL, NULL, NULL, NULL, NULL, NULL, true);



    var_dump($productType);



    $products = $TmpPrd->select(NULL, NULL, $productType->prt_id, NULL, NULL, NULL, NULL, false, NULL, NULL, 0);

	

    $UplDao = new UplDAO();

    $uploads = $UplDao->select(NULL, 'PRODUCT', $productType->prt_id, NULL, false);



} else {





    if (is_null($AtrSeo) && $TmpAtrSeo != '') $AtrSeo = $TmpAtrSeo;

    if (is_null($CatSeo) && $TmpCatSeo != '') $CatSeo = $TmpCatSeo;



    if (!is_null($AtrSeo)) {



        //

        // Product Listing (PRDS)

        //



        $DspTyp = 'Products';



        $AtrDao = new AtrDAO();

        $attrGroup = $AtrDao->selectBySeo($AtrSeo);

        if ($attrGroup) $Atr_ID = $attrGroup->atr_id;



        //echo '#'.$AtrSeo.' '.$Atr_ID.'#';



        $TmpAtl = new AtlDAO();

        $availAtl = $TmpAtl->select($Atr_ID, NULL, true);

        if (isset($availAtl->atr_id)) {

            $availAtl = true;

        } else {

            $availAtl = false;

        }



        if (isset($_GET['formaction']) && $_GET['formaction'] == 'SEARCH') {



            $PrdDao = new PrdDAO();

            $TmpAtv = new AtvDAO();



            //

            // process attribute group values

            //



            $FldArrStr = '';

            $AtrArr = (isset($_GET['fldnum'])) ? $_GET['fldnum'] : NULL;

            for ($fldnum = 0; $fldnum < count($AtrArr); $fldnum++) {

                $FldArrStr .= ($FldArrStr == '') ? $AtrArr[$fldnum] : ',' . $AtrArr[$fldnum];

            }



            $FldValStr = '';

            $AtrVal = (isset($_GET['fld'])) ? $_GET['fld'] : NULL;

            $requestedMatches = 0;

            for ($fldnum = 0; $fldnum < count($AtrVal); $fldnum++) {

                $FldValStr .= ($FldValStr == '') ? $AtrVal[$fldnum] : ',' . $AtrVal[$fldnum];

                if ($AtrVal[$fldnum] != '') $requestedMatches++;

            }



            $Atr_ID = (isset($_GET['atr_id'])) ? $_GET['atr_id'] : NULL;

            $TblNam = (isset($_GET['atvtblnam'])) ? $_GET['atvtblnam'] : NULL;

            $Tbl_ID = (isset($_GET['atvtbl_id'])) ? $_GET['atvtbl_id'] : NULL;



            $attributeSearch = $TmpAtv->searchAttributeValues($Atr_ID, $AtrArr, $AtrVal, $TblNam, $Tbl_ID);



            if (count($attributeSearch)) {



                $PrdLst = '';



                foreach ($attributeSearch as $row) {



                    if (isset($row['atv_eq']) && $row['atv_eq'] == $requestedMatches) {

                        $PrdLst .= ($PrdLst == '') ? "'".$row['Ref_ID']."'" : ",'".$row['Ref_ID']."'";

                    } else if ($requestedMatches == 0) {

                        //$PrdLst .= ($PrdLst == '') ? "'".$row['Ref_ID']."'" : ",'".$row['Ref_ID']."'";

                    }

                }



                $products = $PrdDao->selectByIDs($PrdLst, $Atr_ID, $PerPag, $Pag_No, $SrtOrd);

                $MaxRec = count($PrdDao->selectByIDs($PrdLst, $Atr_ID, NULL, NULL));



            } else {



                if (count($AtrArr) == 0) {



                    $TmpPrd = new PrdDAO();

                    $products = $TmpPrd->searchProducts($Atr_ID, $PerPag, $Pag_No, $SrtOrd);

                    $MaxRec = count($TmpPrd->searchProducts($Atr_ID, NULL, NULL));



                    $recordCount = count($products);



                }



            }



        } else {



            $TmpPrt = new PrtDAO();

            //$products = $TmpPrt->select(NULL, NULL, NULL, NULL, NULL, $Atr_ID, $PerPag, $Pag_No, false);



            // CHECK IF ATTRIBUTE LABELS



            $TmpPrd = new PrdDAO();

            $products = $TmpPrd->searchProducts($Atr_ID, $PerPag, $Pag_No, $SrtOrd);

            $MaxRec = count($TmpPrd->searchProducts($Atr_ID, NULL, NULL));



            $recordCount = count($products);



        }



        $UplDao = new UplDAO();



    } else {





        if (is_null($CatSeo)) {



            //

            // Shopping Departments

            //



            $TblNam = 'shopping-departments';

            $CatDao = new CatDAO();

            $category = $CatDao->select(NULL, $TblNam, NULL, NULL, true);

            $TmpSub = new SubDAO();

            $subCategories = $TmpSub->select($category->cat_id, NULL, NULL, 0, false);



            $DspTyp = 'Departments';



            $UplDao = new UplDAO();



        } else {



            $TmpSub = new SubDAO();

            $subCategory = $TmpSub->selectByCategory(NULL, $CatSeo, NULL);



            //

            // Product Categories (ATR)

            //



            $OffSet = ($Pag_No - 1) * $PerPag;



            $DspTyp = 'Categories';



            $UplDao = new UplDAO();

            $TmpAtr = new AtrDAO();

            $attrGroups = $TmpAtr->select(NULL, 'PRODUCTGROUP', $subCategory->sub_id, NULL, false, $OffSet, $PerPag, 0);



        }



    }



}



?>





<div class="section">

    <div class="container">











<!-- DISPLAY PRODUCT CATEGORIES -->



<?php



//echo $DspTyp.'#'.$_GET['atrseo'];





if ($DspTyp == 'ProductCategories') {



    $tableLength = count($subCategories);

    for ($i = 0; $i < $tableLength; ++$i) {



        $uploads = $UplDao->select(NULL, 'PRDCAT', $subCategories[$i]['sub_id'], NULL, false);



        ?>

        <?php if (($i % $NumCol) == 0 && $i > 0) echo '</div>'; ?>

        <?php if (($i % $NumCol) == 0) echo '<div class="row shopCategoryList">'; ?>



        <div class="col-md-<?php echo $ColWid; ?>">

            <a href="<?php echo $SeoUrl; ?>/department/<?php echo $subCategories[$i]['seourl'] ?>"

               class="shopCategoryLink">





                <?php

                if (

                    isset($uploads[0]) &&

                    file_exists($patchworks->docRoot . 'uploads/images/620-414/' . $uploads[0]['filnam']) &&

                    !is_dir($patchworks->docRoot . 'uploads/images/620-414/' . $uploads[0]['filnam'])

                ) {

                    echo '<img src="uploads/images/620-414/' . $uploads[0]['filnam'] . '" class="productImage" />';

                } else {

                    echo '<img class="productImage" src="http://placehold.it/614x414&text=Awaiting Image">';

                }

                ?>

                <h3><span><?php echo $subCategories[$i]['subnam']; ?></span></h3>

            </a>

        </div>



    <?php

    }



    echo '</div>';



}



?>





<!-- DISPLAY PRODUCT CATEGORIES -->



<?php



if ($DspTyp == 'ProductsByCategories') {

    ?>



    <div class="row">

        <div class="col-md-12">



            <div class="breadCrumb">



                <ul>

                    <li><a href="<?php echo $SeoUrl; ?>">Home</a></li>

                    <li><a href="<?php echo $SeoUrl.'/productcategory/'.$subCategories->seourl; ?>"><?php echo $subCategories->subnam; ?></a></li>

                </ul>



            </div>



        </div>

    </div>



    <div class="row productcategoryheader">

        <div class="col-md-4">



            <div class="NOcategoryimage">

            <?php $uploads = $UplDao->select(NULL, 'PRDCAT', $subCategories->sub_id, NULL, false); ?>



            <?php

            if (

                isset($uploads[1]) &&

                file_exists($patchworks->docRoot . 'uploads/images/620-414/' . $uploads[1]['filnam']) &&

                !is_dir($patchworks->docRoot . 'uploads/images/620-414/' . $uploads[1]['filnam'])

            ) {

                echo '<img src="uploads/images/620-414/' . $uploads[1]['filnam'] . '" class="productImage" />';

            } else {



                if (

                    isset($uploads[0]) &&

                    file_exists($patchworks->docRoot . 'uploads/images/620-414/' . $uploads[0]['filnam']) &&

                    !is_dir($patchworks->docRoot . 'uploads/images/620-414/' . $uploads[0]['filnam'])

                ) {

                    echo '<img src="uploads/images/620-414/' . $uploads[0]['filnam'] . '" class="productImage" />';

                } else {

                    echo '<img class="productImage" src="http://placehold.it/614x414&text=Awaiting Image">';

                }

            }

            ?>

<!--                <span>--><?php //echo $uploads[1]['uplttl']; ?><!--</span>-->

            </div>

        </div>

        <div class="col-md-8">

            <div class="categorydescription">

            <?php echo $subCategories->subdsc; ?>

            </div>

        </div>

    </div>



    <div class="row">

        <div class="col-md-12">

        <div class="shopCategoryList">

            <?php



            $item = 0;



            //

            // GET FEATURED PRODUCT FOR CATEGORY

            //



            //print_r($productGroups);

            //if (isset($AtrSeo)) {

            $tableLength = count($products);

            for ($i = 0; $i < $tableLength; ++$i) {





                //

                // currency conversion

                //



                $products[$i]['unipri'] = $products[$i]['unipri'] * $currConv;



                if ( isset($_GET['atrseo']) && ($products[$i]['atrseo'] != $_GET['atrseo']) ) continue;



                $uploads = $UplDao->select(NULL, 'PRODUCT', $products[$i]['prd_id'], NULL, false);



                if (count($uploads) == 0) {

                    $uploads = $UplDao->select(NULL, 'PRDTYPE', $products[$i]['prt_id'], NULL, false);

                }



                ?>



                <?php if (($item % $NumCol) == 0 && $item > 0) echo '</div>'; ?>

                <?php if (($item % $NumCol) == 0) echo '<div class="row">'; ?>



                <div class="col-md-<?php echo $ColWid; ?>">

                    <div class="productItem">

                        <?php

                        if ($products[$i]['prtseo'] != '') {

                            $url = $SeoUrl . '/producttype/' . $products[$i]['prtseo'];

                        } else {

                            $url = $SeoUrl . '/product/' . $products[$i]['seourl'];

                        }

                        ?>



                        <a href="<?php echo $url; ?>" class="shopCategoryLink">



                            <span class="displayprice"><?php echo $dispCurr.number_format($products[$i]['unipri'],2); ?></span>



                            <?php

                            if (

                                isset($uploads[0]) &&

                                file_exists($patchworks->docRoot . 'uploads/images/620-414/' . $uploads[0]['filnam']) &&

                                !is_dir($patchworks->docRoot . 'uploads/images/620-414/' . $uploads[0]['filnam'])

                            ) {

                                echo '<img src="uploads/images/620-414/' . $uploads[0]['filnam'] . '" class="productImage" />';

                            } else {

                                echo '<img class="productImage" src="http://placehold.it/614x414&text=Awaiting Image">';

                            }

                            ?>

                            <h3><span><?php echo $products[$i]['prdnam']; ?></span></h3>

                        </a>



                    </div>

                </div>



            <?php

                $item++;

            }//}







            if (!isset($AtrSeo)) {



                $tableLength = count($productGroups);

                for ($i = 0; $i < $tableLength; ++$i) {



                    $uploads = $UplDao->select(NULL, 'PRDGRP', $productGroups[$i]['atr_id'], NULL, false);



                    ?>

                    <?php if (($item % $NumCol) == 0 && $item > 0) echo '</div>'; ?>

                    <?php if (($item % $NumCol) == 0) echo '<div class="row">'; ?>



                    <?php

                    //$url = $SeoUrl . '/productgroup/' . $productGroups[$i]['seourl'];

                    $url = $SeoUrl . '/productcategory/' . $PrdCat . '/group/' . $productGroups[$i]['atrseo'];

                    ?>



                    <div class="col-md-<?php echo $ColWid; ?>">

                        <a href="<?php echo $url; ?>"

                           class="shopCategoryLink">

                            <?php

                            if (

                                isset($uploads[0]) &&

                                file_exists($patchworks->docRoot . 'uploads/images/620-414/' . $uploads[0]['filnam']) &&

                                !is_dir($patchworks->docRoot . 'uploads/images/620-414/' . $uploads[0]['filnam'])

                            ) {

                                echo '<img src="uploads/images/620-414/' . $uploads[0]['filnam'] . '" class="productImage" />';

                            } else {

                                echo '<img class="productImage" src="http://placehold.it/614x414&text=Awaiting Image">';

                            }

                            ?>

                            <h3><span><?php echo $productGroups[$i]['atrnam']; ?></span></h3>



                            <div style="display: none;"><?php echo $productGroups[$i]['atrdsc']; ?></div>

                        </a>

                    </div>

                    <?php

                    $item++;

                }



            }





            ?>

        </div>

        </div>

    </div>

    </div>

<?php } ?>



<!-- DISPLAY DEPARTMENTS -->



<?php



if ($DspTyp == 'Departments') {

    ?>



    <div class="row">

        <div class="col-md-12">



            <div class="breadCrumb">

                <ul>

                    <li><a href="<?php echo $SeoUrl; ?>">Home</a></li>

                </ul>

            </div>



        </div>

    </div>



    <?php

    $tableLength = count($subCategories);

    for ($i = 0; $i < $tableLength; ++$i) {



        $uploads = $UplDao->select(NULL, 'DEPT', $subCategories[$i]['sub_id'], NULL, false);



        $url = $SeoUrl.'/department/'.$subCategories[$i]['seourl'];



        if ( substr($subCategories[$i]['seourl'], 0, 4) == 'http') {

            $url = $subCategories[$i]['seourl'];

        }



        ?>

        <?php if (($i % $NumCol) == 0 && $i > 0) echo '</div>'; ?>

        <?php if (($i % $NumCol) == 0) echo '<div class="row shopCategoryList">'; ?>



        <div class="col-md-<?php echo $ColWid; ?>">

            <a href="<?php echo $url; ?>"

               class="shopCategoryLink">

                <?php

                if (

                    isset($uploads[0]) &&

                    file_exists($patchworks->docRoot . 'uploads/images/620-414/' . $uploads[0]['filnam']) &&

                    !is_dir($patchworks->docRoot . 'uploads/images/620-414/' . $uploads[0]['filnam'])

                ) {

                    echo '<img src="uploads/images/620-414/' . $uploads[0]['filnam'] . '" class="productImage" />';

                } else {

                    echo '<img class="productImage" src="http://placehold.it/614x414&text=Awaiting Image">';

                }

                ?>

                <h3><span><?php echo $subCategories[$i]['subnam']; ?></span></h3>

            </a>

        </div>



    <?php

    }



    echo '</div>';



}



?>



<!-- DISPLAY CATEGORIES -->



<?php if ($DspTyp == 'Categories') { ?>



    <div class="row">

        <div class="col-md-12">



            <div class="breadCrumb">

                <ul>

                    <li><a href="<?php echo $SeoUrl; ?>">Home</a></li>

                    <li><?php echo $subCategory->subnam; ?></li>

                </ul>



            </div>



        </div>

    </div>



    <div class="row">

        <div class="col-md-12">



        <div class="shopCategoryList">

            <?php

            $tableLength = count($attrGroups);

            for ($i = 0; $i < $tableLength; ++$i) {



                $uploads = $UplDao->select(NULL, 'PRDGRP', $attrGroups[$i]['atr_id'], NULL, false);



                $url = $SeoUrl . '/productgroup/' . $attrGroups[$i]['seourl'];



                ?>

                <?php if (($i % $NumCol) == 0 && $i > 0) echo '</div>'; ?>

                <?php if (($i % $NumCol) == 0) echo '<div class="row">'; ?>



                <div class="col-md-<?php echo $ColWid; ?>">

                    <a href="<?php echo $url; ?>"

                       class="shopCategoryLink">

                        <?php

                        if (

                            isset($uploads[0]) &&

                            file_exists($patchworks->docRoot . 'uploads/images/620-414/' . $uploads[0]['filnam']) &&

                            !is_dir($patchworks->docRoot . 'uploads/images/620-414/' . $uploads[0]['filnam'])

                        ) {

                            echo '<img src="uploads/images/620-414/' . $uploads[0]['filnam'] . '" class="productImage" />';

                        } else {

                            echo '<img class="productImage" src="http://placehold.it/614x414&text=Awaiting Image">';

                        }

                        ?>

                        <h3><span><?php echo $attrGroups[$i]['atrnam']; ?></span></h3>



                        <div style="display: none;"><?php echo $attrGroups[$i]['atrdsc']; ?></div>

                    </a>

                </div>

            <?php } ?>

        </div>



        </div>



        </div>



    </div>

<?php } ?>



<!-- END CATEGORIES -->





<!-- START PRODUCTS -->



<?php if ($DspTyp == 'Products') { ?>

    <div class="row">

        <div class="col-md-12">



            <div class="breadCrumb">

                <ul>

                    <li><a href="<?php echo $SeoUrl; ?>">Home</a></li>

                    <li>



                        <?php $url = $SeoUrl . '/department/' . $attrGroup->subseo; ?>



                        <a href="<?php echo $url; ?>"><?php echo $attrGroup->subnam; ?></a></li>



                    <li><?php echo $attrGroup->atrnam; ?></li>

                </ul>



            </div>



        </div>

    </div>

    <div class="row">



        <?php



        $availAtl = true;



        if ($availAtl) {

        ?>



        <div class="col-md-4">



            <?php

            $editAttrGroup = $attrGroup->atr_id;

            $editReferenceTable = 'PRODUCTGROUP';

            $editReferenceID = '';

            include('product_search_form.php');

            ?>



        </div>



        <?php

        }

        ?>



        <div class="col-md-<?php echo ($availAtl) ? '8' : '12'; ?>">





            <div class="shopCategoryList">

                <?php

                $tableLength = count($products);

                for ($i = 0; $i < $tableLength; ++$i) {



                    $products[$i]['unipri'] = $products[$i]['unipri'] * $currConv;



                    $uploads = $UplDao->select(NULL, 'PRODUCT', $products[$i]['prd_id'], NULL, false);



                    if (count($uploads) == 0) {

                        $uploads = $UplDao->select(NULL, 'PRDTYPE', $products[$i]['prt_id'], NULL, false);

                    }

                    //print_r($uploads);

                    ?>



                    <?php if (($i % $NumCol) == 0 && $i > 0) echo '</div>'; ?>

                    <?php if (($i % $NumCol) == 0) echo '<div class="row">'; ?>



                    <div class="col-md-<?php echo $ColWid; ?>">

                        <div class="productItem">

                            <?php



                            if ($products[$i]['prtseo'] != '') {

                                $url = $SeoUrl . '/producttype/' . $products[$i]['prtseo'];

                            } else {

                                $url = $SeoUrl . '/product/' . $products[$i]['seourl'];

                            }

                            

                            ?>





                            <a href="<?php echo $url; ?>" class="shopCategoryLink">



                                <span class="displayprice"><?php echo $dispCurr.number_format($products[$i]['unipri'],2); ?></span>



                                <?php

                                if (

                                    isset($uploads[0]) &&

                                    file_exists($patchworks->docRoot . 'uploads/images/620-414/' . $uploads[0]['filnam']) &&

                                    !is_dir($patchworks->docRoot . 'uploads/images/620-414/' . $uploads[0]['filnam'])

                                ) {

                                    echo '<img src="uploads/images/620-414/' . $uploads[0]['filnam'] . '" class="productImage" />';

                                } else {

                                    echo '<img class="productImage" src="http://placehold.it/614x414&text=Awaiting Image">';

                                }

                                ?>

                                <h3><span><?php echo (!empty($products[$i]['prdnam'])) ? $products[$i]['prdnam'] : $products[$i]['prtnam']; ?></span></h3>

                            </a>



                        </div>

                    </div>



                <?php } ?>

            </div>



            <?php

            if (count($products) > 0) {

            ?>

            <nav>

                <ul class="pagination">



                    <?php

                    $useQS = false;

                    $qryString = $_SERVER['QUERY_STRING'];

                    $strPos = strpos($qryString, 'atvtblnam');

                    if ($strPos > 0) {

                        $useQS = true;

                        $qryString = substr($qryString, $strPos, (strlen($qryString) - $strPos));

                    }

                    ?>



                    <li class="disabled"><a href="#">&laquo;</a></li>



                    <?php



                    if ((is_numeric($MaxRec) && $MaxRec > 0) && (is_numeric($PerPag) && $PerPag > 0)) {

                        $PageCount = ceil($MaxRec / $PerPag);

                    } else {

                        $PageCount = 0;

                    }



                    for ($p = 0; $p < $PageCount; $p++) {

                        if ($useQS) {

                            $url = $SeoUrl . '/productgroup/' . $AtrSeo . '?'. $qryString.'&pag_no=' . ($p + 1);

                        } else {

                            $url = $SeoUrl . '/productgroup/' . $AtrSeo . '?pag_no=' . ($p + 1);

                        }

                        ?>



                        <li <?php if ($Pag_No == ($p + 1)) echo 'class="active"'; ?>>



                            <a href="<?php echo $url; ?>"><?php echo $p + 1; ?>



                                <span class="sr-only">(current)</span>



                            </a>



                        </li>



                    <?php } ?>



                    <li class="disabled"><a href="#">&raquo;</a></li>





                </ul>

            </nav>

            <?php

            } else {

            ?>



            <h2>Sorry there are no products to match your search</h2>



            <?php

            }

            ?>



        </div>

    </div>

    </div>

<?php } ?>



<!-- END PRODUCTS -->



<!-- START PRODUCT TYPE -->



<?php if ($DspTyp == 'ProductType') { ?>

    <div class="row">

        <div class="col-md-12">

            <div class="productItem">



                <div class="breadCrumb">

                    <ul>

                        <li><a href="<?php echo $SeoUrl; ?>">Home</a></li>

                        <li>

                            <a href="<?php echo $SeoUrl . '/department/' . $productType->subseo; ?>"><?php echo $productType->subnam; ?>

                                </a></li>

                        <li>

                            <a href="<?php echo $SeoUrl . '/productgroup/' . $productType->atrseo; ?>"><?php echo $productType->atrnam; ?>

                               </a></li>

                        <li><?php echo $productType->prtnam; ?></li>

                    </ul>



                </div>

				

				

				<div class="row">

                    <div class="col-md-12">

                        <h2><?php echo $productType->prtnam; ?></h2>

                        <hr/>

                    </div>

                </div>



                <div class="row">

                    <div class="col-md-6">



                        <?php if (count($uploads) > 0) { ?>



                            <div class="czwrapper">

                                <a href="uploads/images/620-414/<?php echo $uploads[0]['filnam']; ?>" class="cloud-zoom"

                                   id="zoom-image"

                                   rel="adjustX: 10, adjustY:-4, softFocus:true, zoomPosition:'inside'">

                                    <img src="uploads/images/<?php echo $uploads[0]['filnam']; ?>" alt=''

                                         id="zoom-image-default"/>

                                </a>



                                <div class="zoomicon"><span><i class="fa fa-search"></i></span></div>



                            </div>



                        <?php } else { ?>

						

							<img class="productImage" src="http://placehold.it/614x414&text=Awaiting Image">

						

						<?php } ?>

						

                        <div id="productGallery">

                            <ul>

                                <?php

                                $tableLength = count($uploads);

                                for ($i = 0; $i < $tableLength; ++$i) {

                                    echo '<li><a href="uploads/images/620-414/' . $uploads[$i]['filnam'] . '" data-zoomimage="uploads/images/620-414/' . $uploads[$i]['filnam'] . '" class="selectimage"><img src="uploads/images/169-130/' . $uploads[$i]['filnam'] . '" alt="" /></a></li>';

                                }

                                ?>

                            </ul>

                        </div>



                    </div>





                    <div class="col-md-6">



                        <div class="productDescription">

                            <?php echo $productType->prtdsc; ?>

                        </div>



                        <hr/>



                        <div class="productPrice" style="float: none;"><?php echo $dispCurr.$productType->unipri; ?></div>

						

						<hr />

						

						<div class="productVariation">

							<ul>

								<?php

								$tableLength = count($products);

								for ($i = 0; $i < $tableLength; ++$i) {



                                    $products[$i]['unipri'] = $products[$i]['unipri'] * $currConv;



									?>

			

									<li>



                                        <?php if (isset($products[$i]['in_stk']) && $products[$i]['in_stk'] > 0) { ?>



										<a href="shoppingcart/add/<?php echo $products[$i]['prd_id']; ?>" class="btn btn-primary addToCartBtn">Add to cart</a>



                                        <?php } else { ?>



                                            <div class="alert alert-info"><strong>Sorry</strong> This product is out of stock.</div>



                                        <?php } ?>



										<?php echo $products[$i]['prdnam']; ?>

										

										<?php if ($products[$i]['unipri'] != $productType->unipri) echo ' <br><span>' . number_format($products[$i]['unipri'],2) . '</span>'; ?>

									</li>

			

								<?php } ?>

							</ul>

						</div>

						

                    </div>



                </div>

				

				

				<!---->

				



                



            </div>



            

        </div>

    </div>

<?php } ?>



<!-- END PRODUCT TYPE -->



<!-- START SINGLE PRODUCT -->



<?php if ($DspTyp == 'Product') { ?>

    <div class="row">

        <div class="col-md-12">

            <div class="productItemSingle">



                <div class="breadCrumb">

                    <ul>

                        <li><a href="<?php echo $SeoUrl; ?>">Home</a></li>

                        <li>

                            <a href="<?php echo $SeoUrl . '/department/' . $product->subseo; ?>"><?php echo $product->subnam; ?>

                                </a></li>

                        <li>





                            <?php $url = $SeoUrl . '/productgroup/' . $product->atrseo; ?>



                            <a href="<?php echo $url; ?>"><?php echo $product->atrnam; ?>

                                </a></li>

                        <li><?php echo $product->prdnam; ?></li>

                    </ul>







                </div>



                <div class="row">

                    <div class="col-md-12">

                        <h2><?php echo $product->prdnam; ?> <span><?php echo $product->altref; ?></span></h2>

                        <hr/>

                    </div>

                </div>



                <div class="row">

                    <div class="col-md-6">



                        <?php if (count($uploads) > 0) { ?>

                        <div class="row" style="margin-top: 20px;" id="productPopupParent">

                            <div class="col-md-12">

                            <a href="uploads/images/<?php echo $uploads[0]['filnam']; ?>" id="productPopupLink" class="image-link">

                                <img src="uploads/images/<?php echo $uploads[0]['filnam']; ?>" alt="" id="heroImage" style="width: 100%;" data-arr_id="0" />

                            </a>

                            </div>



                        </div>

                        <?php } ?>



                        <div class="row" style="margin-top: 20px;" id="productPopupParent">



                            <?php

                            $tableLength = count($uploads);

                            for ($i = 0; $i < $tableLength; ++$i) {

                            ?>



                            <div class="col-xs-4">



                                <a class="image-link" href="uploads/images/<?php echo $uploads[$i]['filnam']; ?>" data-arr_id="<?php echo $i; ?>"><img src="uploads/images/<?php echo $uploads[$i]['filnam']; ?>" alt="" style="width: 100%;" /></a>



                            </div>



                            <?php } ?>



                        </div>



                    </div>









                    <div class="col-md-6">



                        <div class="productheader">



                        <div class="productPrice">

                            <?php echo $dispCurr.number_format($product->unipri * $currConv,2); ?>



                            <?php if ($product->in_stk == 0) { ?>



                                <small>Out of Stock</small>



                            <?php } ?>



                        </div>



                        <?php if ($product->in_stk > 0) { ?>

                            <a href="shoppingcart/add/<?php echo $product->prd_id; ?>" class="btn btn-primary addToCartBtn">Add to cart</a>

                        <?php } ?>



                        </div>



                        <div role="tabpanel">

                            <ul class="nav nav-tabs" role="tablist">

                                <li role="presentation" class="active"><a href="#prddescription" aria-controls="prddescription" role="tab" data-toggle="tab">Description</a></li>

                                <li role="presentation"><a href="#prdspecification" aria-controls="prdspecification" role="tab" data-toggle="tab">Specification</a></li>



                                <?php if (isset($pdfs) && count($pdfs) > 0) { ?>

                                <li role="presentation"><a href="#prddownloads" aria-controls="prddownloads" role="tab" data-toggle="tab">Downloads</a></li>

                                <?php } ?>



                                <?php if (isset($product->altnam) && !empty($product->altnam)) { ?>

                                    <li role="presentation"><a href="#prdvideo" aria-controls="prdvideo" role="tab" data-toggle="tab">Video</a></li>

                                <?php } ?>



                            </ul>

                            <div class="tab-content">

                                <div role="tabpanel" class="tab-pane active" id="prddescription">



<!--                                    <h4>Description</h4>-->

                                    <div class="productDescription">

                                        <?php echo $product->prddsc; ?>

                                    </div>



                                </div>

                                <div role="tabpanel" class="tab-pane" id="prdspecification">



<!--                                    <h4>Specification</h4>-->

                                    <div class="productSpecification">

                                        <?php echo $product->prdspc; ?>

                                    </div>



                                </div>

                                <?php if (isset($pdfs) && count($pdfs) > 0) { ?>



                                <div role="tabpanel" class="tab-pane" id="prddownloads">



<!--                                    <h4>Product Downloads</h4>-->



                                    <div id="productPDFs">

                                        <ul class="list-group">

                                            <?php

                                            $tableLength = count($pdfs);

                                            for ($i = 0; $i < $tableLength; ++$i) {

                                                echo '<li class="list-group-item"><a href="uploads/files/' . $pdfs[$i]['filnam'] . '" target="_blank"><i class="fa fa-file-pdf-o"></i> '.$pdfs[$i]['uplttl'] . '</a></li>';

                                            }

                                            ?>

                                        </ul>

                                    </div>

                                </div>



                                <?php } ?>



                                <?php if (isset($product->altnam) && !empty($product->altnam)) { ?>



                                    <div role="tabpanel" class="tab-pane" id="prdvideo">



<!--                                        <h4>Product Video</h4>-->



                                        <div class="youtubevideo">



                                            <iframe width="100%" height="400" src="//www.youtube.com/embed/<?php echo $product->altnam; ?>?rel=0&showinfo=0&cc_load_policy=0&modestbranding=0" frameborder="0" allowfullscreen></iframe>



                                        </div>



                                    </div>



                                <?php } ?>



                            </div>

                        </div>





                    </div>



                </div>



                <?php if ( count($relatedProducts) > 0 ) { ?>



                    <div class="row" style="margin-top: 30px;">



                            <div class="col-md-12">

                                <h4>Related Products</h4>

                            </div>



                            <?php

                            for ($i=0;$i<count($relatedProducts);$i++) {



                                if ($relatedProducts[$i]['tbl_id'] == $product->prd_id) {



                                    $productupload = $UplDao->select(NULL, 'PRODUCT', $relatedProducts[$i]['ref_id'], NULL, false);



                                    $url = $SeoUrl.'/product/'.$relatedProducts[$i]['seourl'];

                                    $img = (count($productupload) > 0) ? $productupload[0]['filnam'] : '';

                                    $nam = $relatedProducts[$i]['prdnam'];



                                } else {



                                    $productupload = $UplDao->select(NULL, 'PRODUCT', $relatedProducts[$i]['tbl_id'], NULL, false);



                                    $url = $SeoUrl.'/product/'.$relatedProducts[$i]['this_seo'];

                                    $img = (count($productupload) > 0) ? $productupload[0]['filnam'] : '';

                                    $nam = $relatedProducts[$i]['thisname'];



                                }



                            ?>



                                <div class="col-md-2">

                                    <div class="relatedItem">



                                        <a href="<?php echo $url; ?>">



                                        <?php

                                        if (



                                            file_exists($patchworks->docRoot . 'uploads/images/620-414/' . $img) &&

                                            !is_dir($patchworks->docRoot . 'uploads/images/620-414/' . $img)

                                        ) {

                                            echo '<img src="uploads/images/620-414/' . $img . '" class="productImage" />';

                                        } else {

                                            echo '<img class="productImage" src="http://placehold.it/614x414&text=Awaiting Image">';

                                        }

                                        ?>



                                        </a>



                                        <h5><?php echo $nam; ?></h5>



                                    </div>

                                </div>





                            <?php

                            }

                            ?>





<!--                        </div>-->

                    </div>

                <?php } ?>



            </div>

        </div>

    </div>





    <script>



        $(function(){



            $('.image-link:not(#productPopupLink)').click(function(e){



                e.preventDefault();



                $('#heroImage').attr("src", $(this).attr('href')).data('arr_id', $(this).data('arr_id'));

                $('#productPopupLink').attr("href", $(this).find('img').attr('href') );



            });



            var itemArray = [];



            $('.image-link:not(#productPopupLink)').each(function(){

                itemArray.push( {src: $(this).attr('href')} );

            });



            $('#productPopupLink').magnificPopup({

                items: itemArray,

                type:'image',

                gallery: {

                    enabled: true

                },

                callbacks: {

                    open: function () {



                        startAt = $('#heroImage').data('arr_id');



                        $.magnificPopup.instance.goTo(startAt);

                    }

                }

            });



            try {



                $('.selectimage').live('click', function (e) {

                    e.preventDefault();



                    var ImgTmp = $(this).attr('href');

                    //ImgTmp = ImgTmp.replace('/105-105/','/460/');



                    $('#zoom-image-default').attr('src', ImgTmp);



                    ImgTmp = $(this).attr('href');



                    //ImgTmp = ImgTmp.replace('/105-105/','/');



                    $('#zoom-image').attr('href', $(this).data('zoomimage'));



                    $('#zoom-image').CloudZoom();



                    return false;

                });



            } catch(ex) {



                $('#productGallery').on('click', '.selectimage', function () {



                    e.preventDefault();



                    var ImgTmp = $(this).attr('href');

                    //ImgTmp = ImgTmp.replace('/105-105/','/460/');



                    $('#zoom-image-default').attr('src', ImgTmp);



                    ImgTmp = $(this).attr('href');



                    //ImgTmp = ImgTmp.replace('/105-105/','/');



                    $('#zoom-image').attr('href', $(this).data('zoomimage'));



                    $('#zoom-image').CloudZoom();



                });



            }



        })



    </script>



<?php } ?>



<!-- END SINGLE PRODUCT -->



</div>

</div>