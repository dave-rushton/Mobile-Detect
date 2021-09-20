<?php
require_once("../../../config/config.php");
require_once("../../../admin/patchworks.php");
require_once("../../../admin/ecommerce/classes/ecommprop.cls.php");
require_once("../../../admin/website/classes/pageelements.cls.php");

$TmpEco = new EcoDAO();
$EleDao = new PelDAO();

$eCommProp = $TmpEco->select(true);

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

include_once("../../../admin/website/classes/pages.cls.php");
include_once("../../../admin/website/classes/pagecontent.cls.php");
include_once("../../../admin/website/classes/keyword.cls.php");
include_once("../../../admin/website/classes/articles.cls.php");
include_once("../../../admin/products/classes/products.cls.php");
include_once("../../../admin/products/classes/product_types.cls.php");
include_once("../../../admin/gallery/classes/uploads.cls.php");

$searchTerm = (isset($_GET['keyword'])) ? $_GET['keyword'] : '';

$sdArray = array();
$i = 0;

if ($searchTerm != '') {

    //
    // Website Products
    //

//	$PrdDao = new PrdDAO();
//	//$products = $PrdDao->select(NULL, NULL, NULL, NULL, NULL, NULL, NULL, FALSE, 0, 9999999, NULL, NULL, 0);
//    $products = $PrdDao->select(NULL, NULL, NULL, NULL, NULL, NULL, NULL, FALSE, 0, 9999999);
//
//	for ($a=0; $a<count($products);$a++) {
//
//		$sdArray[$i] = new searchDetail;
//		$sdArray[$i]->TblNam = 'products';
//		$sdArray[$i]->Tbl_ID = $products[$a]['prd_id'];
//
//		$sdArray[$i]->getWordCount($searchTerm, $products[$a]['prdnam']);
//		$sdArray[$i]->getWordCount($searchTerm, $products[$a]['prddsc']);
//        $sdArray[$i]->getWordCount($searchTerm, $products[$a]['altref']);
//        $sdArray[$i]->getWordCount($searchTerm, $products[$a]['altnam']);
//		$sdArray[$i]->searchContent = $products[$a]['prddsc'];
//		$sdArray[$i]->contentPageName = $products[$a]['prdnam'];
//
//		$sdArray[$i]->seoUrl = $products[$a]['seourl'];
//		$sdArray[$i]->inSEO($searchTerm, $products[$a]['seourl']);
//		$sdArray[$i]->inTitle($searchTerm, $products[$a]['prdnam']);
//
//		++$i;
//
//	}


    $UplDao = new UplDAO();
    $PrtDao = new PrtDAO();
    //$products = $PrdDao->select(NULL, NULL, NULL, NULL, NULL, NULL, NULL, FALSE, 0, 9999999, NULL, NULL, 0);
    $products = $PrtDao->select(NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, false);

    for ($a = 0; $a < count($products); $a++) {

        //if ($eCommProp->outstk == 0 && $products[$a]['in_stk'] == 0) continue;

        $sdArray[$i] = new searchDetail;
        $sdArray[$i]->TblNam = 'products';
        $sdArray[$i]->Tbl_ID = $products[$a]['prt_id'];

        //$uploads = $UplDao->select(NULL, 'PRDTYPE', $products[$a]['prt_id'], NULL, false);

        $uploads = $PrtDao->getProductImage($products[$a]['prt_id']);

        $sdArray[$i]->ImgUrl = '';
        if (isset($uploads[0]['filnam'])) {
            $sdArray[$i]->ImgUrl = $uploads[0]['filnam'];
        }

        $sdArray[$i]->getWordCount($searchTerm, $products[$a]['prtnam']);
        $sdArray[$i]->getWordCount($searchTerm, $products[$a]['prtdsc']);
        //$sdArray[$i]->getWordCount($searchTerm, $products[$a]['altref']);
        //$sdArray[$i]->getWordCount($searchTerm, $products[$a]['altnam']);
        $sdArray[$i]->searchContent = $products[$a]['prtdsc'];
        $sdArray[$i]->contentPageName = $products[$a]['prtnam'];

        $sdArray[$i]->seoUrl = 'products/productlist/'.$products[$a]['prt_id'].'/'.$products[$a]['seourl'];
        $sdArray[$i]->inSEO($searchTerm, $products[$a]['seourl']);
        $sdArray[$i]->inTitle($searchTerm, $products[$a]['prtnam']);

        ++$i;

    }


    //
    // Website Content
    //

    $PagDao = new PagDAO();
    $PgcDao = new PgcDAO();

//    $pages = $PagDao->select();
//
//    for ($p = 0; $p < count($pages); $p++) {
//
//        $sdArray[$i] = new searchDetail;
//        $sdArray[$i]->TblNam = 'page';
//        $sdArray[$i]->Tbl_ID = $pages[$p]['pag_id'];
//
//        //
//        // Loop through pagecontent
//        //
//
//        $contentString = '';
//        $pageContent = $PgcDao->select(NULL, $pages[$p]['pag_id'], NULL, false);
//        for ($pc = 0; $pc < count($pageContent); $pc++) {
//            $contentString .= ' ' . $pageContent[$pc]['pgctxt'] . ' ';
//        }
//
//        $sdArray[$i]->ImgUrl = '';
//
//        $sdArray[$i]->searchContent = strip_tags($contentString);
//
//        $sdArray[$i]->getWordCount($searchTerm, $contentString);
//        $sdArray[$i]->contentPageName = $pages[$p]['title'];
//
//        $sdArray[$i]->seoUrl = $pages[$p]['seourl'];
//        $sdArray[$i]->inSEO($searchTerm, $pages[$p]['seourl']);
//        $sdArray[$i]->inTitle($searchTerm, $pages[$p]['pagttl']);
//
//        ++$i;
//
//    }

    //
    // Website Articles
    //

//    $ArtDao = new ArtDAO();
//    $articles = $ArtDao->select();
//
//    for ($a = 0; $a < count($articles); $a++) {
//
//        $sdArray[$i] = new searchDetail;
//
//        $sdArray[$i]->ImgUrl = '';
//
//        $sdArray[$i]->TblNam = 'article';
//        $sdArray[$i]->Tbl_ID = $articles[$a]['art_id'];
//
//        $sdArray[$i]->getWordCount($searchTerm, $articles[$a]['arttxt']);
//        $sdArray[$i]->searchContent = $articles[$a]['arttxt'];
//        $sdArray[$i]->contentPageName = $articles[$a]['artttl'];
//
//        $sdArray[$i]->seoUrl = $articles[$a]['seourl'];
//        $sdArray[$i]->inSEO($searchTerm, $articles[$a]['seourl']);
//        $sdArray[$i]->inTitle($searchTerm, $articles[$a]['artttl']);
//
//        ++$i;
//
//    }

}

?>


<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">

                <div class="searchResultsList">
                    <?php
                    if ($searchTerm != '') {
                        $j = 0;
                        echo '<ul>';
                        foreach ($sdArray as $searchResults) {

                            if ($searchResults->occurances < 1) continue;

                            $j++;

                            //$searchResults->actSeo = 'website/article-edit.php?art_id='.$searchResults->Tbl_ID;

                            $searchResults->actSeo = $searchResults->seoUrl;

                            if ($searchResults->TblNam == 'article') $searchResults->actSeo = $patchworks->articlesURL . $patchworks->articleURL . $searchResults->seoUrl;

                            ?>
                            <li>

                                <?php
                                if (
                                    isset($searchResults->ImgUrl) &&
                                    file_exists($patchworks->docRoot . 'uploads/images/products/169-130/' . $searchResults->ImgUrl) &&
                                    !is_dir($patchworks->docRoot . 'uploads/images/products/169-130/' . $searchResults->ImgUrl)
                                ) {
                                    echo '<img src="uploads/images/products/169-130/' . $searchResults->ImgUrl . '" class="productImage" />';
                                } else {

                                    echo '<img src="pages/img/noimg.png" class="productImage" />';
                                }
                                ?>

                                <a href="<?php echo $searchResults->actSeo; ?>"><?php echo $searchResults->contentPageName.' ('.$searchResults->TblNam.')'; ?></a>

                                <div
                                    class="description">...<?php echo $searchResults->searchString($searchTerm, $searchResults->searchContent, 150); ?>...</div>

                            </li>
                            <?php

                        }
                        echo '</ul>';
                    } else {
                        echo '<p>Please enter a keyword to see your results....</p>';
                    }
                    ?>

                    <?php
                    if ($j == 0) {

                        echo "<h2>Sorry, but we couldn't find what you were searching for.</h2>";
                        echo "<p>Please try again.</p>";

                    }
                    ?>

                </div>

            </div>
        </div>
    </div>
</div>