<?php
require_once("../../config/config.php");
require_once("../../admin/patchworks.php");
require_once("../../admin/website/classes/pageelements.cls.php");

$EleDao = new PelDAO();

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

include_once("../../admin/website/classes/pages.cls.php");
include_once("../../admin/website/classes/pagecontent.cls.php");
include_once("../../admin/website/classes/keyword.cls.php");
include_once("../../admin/website/classes/articles.cls.php");
//include_once("../../admin/products/classes/products.cls.php");

$searchTerm = (isset($_GET['keyword'])) ? $_GET['keyword'] : '';

$sdArray = array();
$i=0;

if ( $searchTerm != '') {
	
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
	
	
	//
	// Website Content
	//
	
	$PagDao = new PagDAO();
	$PgcDao = new PgcDAO();
	
	$pages = $PagDao->select();
	
	for ($p=0; $p<count($pages);$p++) {
		
		$sdArray[$i] = new searchDetail;
		$sdArray[$i]->TblNam = 'page';
		$sdArray[$i]->Tbl_ID = $pages[$p]['pag_id'];
		
		//
		// Loop through pagecontent
		//
		
		$contentString = '';
		$pageContent = $PgcDao->select(NULL, $pages[$p]['pag_id'], NULL, false);
		for ($pc=0; $pc<count($pageContent);$pc++) {
			$contentString .= ' '.$pageContent[$pc]['pgctxt'].' ';
		}
		
		$sdArray[$i]->searchContent = strip_tags($contentString);
		
		$sdArray[$i]->getWordCount($searchTerm, $contentString);
		$sdArray[$i]->contentPageName = $pages[$p]['title'];
	
		$sdArray[$i]->seoUrl = $pages[$p]['seourl'];
		$sdArray[$i]->inSEO($searchTerm, $pages[$p]['seourl']);
		$sdArray[$i]->inTitle($searchTerm, $pages[$p]['pagttl']);
				
		++$i;
		
	}
	
	//
	// Website Articles
	//
	
	$ArtDao = new ArtDAO();
	$articles = $ArtDao->select();
	
	for ($a=0; $a<count($articles);$a++) {
		
		$sdArray[$i] = new searchDetail;
		$sdArray[$i]->TblNam = 'article';
		$sdArray[$i]->Tbl_ID = $articles[$a]['art_id'];

		$sdArray[$i]->getWordCount($searchTerm, $articles[$a]['arttxt']);
		$sdArray[$i]->searchContent = $articles[$a]['arttxt'];
		$sdArray[$i]->contentPageName = $articles[$a]['artttl'];
	
		$sdArray[$i]->seoUrl = $articles[$a]['seourl'];
		$sdArray[$i]->inSEO($searchTerm, $articles[$a]['seourl']);
		$sdArray[$i]->inTitle($searchTerm, $articles[$a]['artttl']);
				
		++$i;
		
	}
	
}

?>
<div class="searchResultsList">
<?php 
	if ( $searchTerm != '') {
	$j = 0;
	echo '<ul>';
	foreach( $sdArray as $searchResults ) {
		
		if ($searchResults->occurances < 1) continue;
		
		//$searchResults->actSeo = 'website/article-edit.php?art_id='.$searchResults->Tbl_ID;
		
		$searchResults->actSeo = $searchResults->seoUrl;
		
		if ($searchResults->TblNam == 'article') $searchResults->actSeo = $patchworks->articlesURL.$patchworks->articleURL.$searchResults->seoUrl;
		
	?>
	<li>
	
	<a href="<?php echo $searchResults->actSeo; ?>"><strong><?php echo ucwords($searchResults->TblNam); ?>: </strong><?php echo $searchResults->contentPageName; ?> (<?php echo $searchResults->occurances; ?>)</a>
	
	<div><?php echo strip_tags($searchResults->searchString($searchTerm, $searchResults->searchContent, 100)); ?></div>
	
	</li>
	<?php
	
	}
	echo '</ul>';
	} else {
		echo '<p>Please enter a keyword to see your results....</p>';
	}
?>
</div>