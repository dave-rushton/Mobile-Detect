<?php

require_once("config/config.php");
require_once("admin/patchworks.php");
require_once("admin/website/classes/page.handler.php");

$pageHandler = new pageHandler();

$s9demo = preg_match('/\/staging\//', $_SERVER['DOCUMENT_ROOT']);

if (!isset($_GET['seourl'])) {
	
	$homePage = $pageHandler->getHomePage();
	if ($homePage) {
		$_GET['seourl'] = $homePage->seourl;
		$seourl = $homePage->seourl;
	} else {
		die('Website currently under maintenance');
	}
	
} else {
	$seourl = $_GET['seourl'];
}

$preview = (isset($_GET['preview'])) ? true : false; 

$pageHandler->preview = true;


//
// get page run first time to set variables to page handler object
//


$pageHandler->getPage($seourl, $_GET, $_SESSION);
//
// display page takes the elements set above and create a URL based on the $_GET variables and the $_SESSION variables are sent through as $_POST throughout the elements
//
$pageHandler->displayPage();


?>
