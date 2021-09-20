<?php

require_once("../../../config/config.php");
require_once("../../patchworks.php");
require_once("../classes/pages.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);

if ($loggedIn == 0) header('location: ../login.php');

$Pag_ID = (isset($_GET['pag_id']) && is_numeric($_GET['pag_id'])) ? $_GET['pag_id'] : die('FAIL');
$SeoUrl = (isset($_GET['seourl']) ) ? $_GET['seourl'] : NULL;

$PagDao = new PagDAO();

$pageRec = $PagDao->select($Pag_ID, $SeoUrl, true);

$jsonArray = array();

if ($pageRec) {
	
	$recordArray = array();
	$recordArray['title'] = $pageRec->title;
	$recordArray['pag_id'] = $pageRec->pag_id;
	$recordArray['pagttl'] = $pageRec->pagttl;
	$recordArray['tmplte'] = $pageRec->tmplte;
	$recordArray['lnktyp'] = $pageRec->lnktyp;
	$recordArray['seourl'] = $pageRec->seourl;
	$recordArray['keywrd'] = $pageRec->keywrd;
	$recordArray['pagdsc'] = $pageRec->pagdsc;
	$recordArray['googex'] = $pageRec->googex;
	$recordArray['sta_id'] = $pageRec->sta_id;
	$recordArray['defpag'] = $pageRec->defpag;
	$recordArray['pagimg'] = $pageRec->pagimg;
	$recordArray['pagobj'] = $pageRec->pagobj;
	$jsonArray[] = $recordArray;
}

die(json_encode($jsonArray));

?>