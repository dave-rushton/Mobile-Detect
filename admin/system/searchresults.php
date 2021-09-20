<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

include_once("../system/classes/keyword.cls.php");
include_once("../website/classes/pages.cls.php");
include_once("../website/classes/pagecontent.cls.php");
include_once("../website/classes/articles.cls.php");

$searchTerm = (isset($_GET['keyword'])) ? $_GET['keyword'] : '';

$sdArray = array();
$i=0;

if ( $searchTerm != '') {
	
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
		$sdArray[$i]->contentPageName = $articles[$a]['artttl'];
	
		$sdArray[$i]->seoUrl = $articles[$a]['seourl'];
		$sdArray[$i]->inSEO($searchTerm, $articles[$a]['seourl']);
		$sdArray[$i]->inTitle($searchTerm, $articles[$a]['artttl']);
				
		++$i;
		
	}
}

?>
<!doctype html>
<html>
<head>
<title>Search Results</title>
<?php include('../webparts/headdata.php'); ?>
</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
	<?php include('../webparts/system-left.php'); ?>
	<div id="main">
		<div class="container-fluid">
			<div class="page-header">
				<div class="pull-left">
					<h1>Search Results</h1>
				</div>
				<div class="pull-right">
					<?php include('../webparts/index-info.php'); ?>
				</div>
			</div>
			<div class="breadcrumbs">
				<ul>
					<li>
						<a href="index.php">Dashboard</a>
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a>Search</a>
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a>Results for : <?php echo $_GET['keyword']; ?></a>
					</li>
				</ul>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-search"></i> Search results </h3>
						</div>
						<div class="box-content nopadding">
							<div class="search-results">
								<ul>
									<?php 
									$j = 0;
									foreach( $sdArray as $searchResults ) {
										
										if ($searchResults->occurances < 1 || $searchResults->TblNam != 'page') continue;
										
										$searchResults->actSeo = 'website/pagebuilder.php?seourl='.$searchResults->seoUrl;
										
									?>
									<li>
										<!--<div class="thumbnail">
											<img alt="" src="http://www.placehold.it/80">
										</div>-->
										<div class="search-info" style="margin: 0;">
											<a href="<?php echo $searchResults->actSeo; ?>"><?php echo $searchResults->contentPageName; ?></a>
											<p class="url"><?php echo $searchResults->actSeo; ?></p>
											<p><?php echo $searchResults->occurances; ?> occurances</p>
										</div>
									</li>
									<?php } ?>
									<?php 
									$j = 0;
									foreach( $sdArray as $searchResults ) {
										
										if ($searchResults->occurances < 1 || $searchResults->TblNam != 'article') continue;
										
										$searchResults->actSeo = 'website/pagebuilder.php?seourl='.$searchResults->seoUrl;
										
									?>
									<li>
										<!--<div class="thumbnail">
											<img alt="" src="http://www.placehold.it/80">
										</div>-->
										<div class="search-info" style="margin: 0;">
											<a href="<?php echo $searchResults->actSeo; ?>"><?php echo $searchResults->contentPageName; ?></a>
											<p class="url"><?php echo $searchResults->actSeo; ?></p>
											<p><?php echo $searchResults->occurances; ?> occurances</p>
										</div>
									</li>
									<?php } ?>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
