<?php

require_once("../../../config/config.php");
require_once("../../patchworks.php");
require_once("../../website/classes/pages.cls.php");
require_once("../../website/classes/template.cls.php");
require_once("../../website/classes/page.handler.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TplDao = new TplDAO();
$templateRec = $TplDao->selectDefault();

$Pag_ID = (isset($_GET['pag_id']) && is_numeric($_GET['pag_id'])) ? $_GET['pag_id'] : die('FAIL');
$PagDao = new PagDAO();

$pageRec = $PagDao->select($Pag_ID, NULL, true);

$throwJSON = array();
$throwJSON['id'] = '0';
$throwJSON['title'] = 'noaction';
$throwJSON['description'] = 'no action taken';
$throwJSON['type'] = 'warning';

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

// set seourl
// set template

$jsonArray = array();

if ($pageRec) {

	$pageRec->seourl = seoUrl($pageRec->title);
	$pageRec->pagttl = $pageRec->title.' | '.$patchworks->customerName;
	$pageRec->keywrd = $pageRec->title;
	$pageRec->pagdsc = $pageRec->title;
	$pageRec->lnktyp = 0;
	$pageRec->sta_id = 1;
	$pageRec->tmplte = ($templateRec) ? $templateRec->tpl_id : 0;
	
	$PagDao->update($pageRec);
		
	$throwJSON = array();
	$throwJSON['id'] = '0';
	$throwJSON['title'] = 'Page Created';
	$throwJSON['description'] = $pageRec->title.' page created';
	$throwJSON['type'] = 'success';

} else {

		
	$throwJSON = array();
	$throwJSON['id'] = '0';
	$throwJSON['title'] = 'Page Not Found';
	$throwJSON['description'] = 'the page you were looking for could not be found';
	$throwJSON['type'] = 'error';
	
}

$file = 'robots.txt';
$current = "User-agent: *\n";
$current .= "Disallow: /cgi-bin/\n";
$current .= "Disallow: /admin/\n";
$current .= "Disallow: /config/\n";
$current .= "Disallow: /Facebook/\n";
$current .= "Disallow: /twitterapi/\n";

$pageHandler = new pageHandler();
$inactivePages = $pageHandler->getInactive();
for ($i=0;$i<count($inactivePages);$i++) {
    $current .= "Disallow: /".$inactivePages[$i]['seourl']."/\n";
}
file_put_contents($file, $current);

die(json_encode($throwJSON));

?>