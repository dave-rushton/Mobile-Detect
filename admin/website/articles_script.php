<?php







require_once("../../config/config.php");



require_once("../patchworks.php");



require_once("../website/classes/articles.cls.php");







$userAuth = new AuthDAO();



$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);







$throwJSON = array();



$throwJSON['id'] = '0';



$throwJSON['title'] = 'noaction';



$throwJSON['description'] = 'no action taken';



$throwJSON['type'] = 'warning';







if ($loggedIn == 0) {



	



	//header('location: ../login.php');



		



	$throwJSON['title'] = 'Authorisation';



	$throwJSON['description'] = 'You are not authorised for this action';



	$throwJSON['type'] = 'error';



}











$Art_ID = (isset($_REQUEST['art_id']) && is_numeric($_REQUEST['art_id'])) ? $_REQUEST['art_id'] : die('FAIL');







if (is_null($Art_ID)) {



	$throwJSON['title'] = 'Invalid Article';



	$throwJSON['description'] = 'Article not found';



	$throwJSON['type'] = 'error';



}







$ArtDao = new ArtDAO();







if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update') {







	$ArtObj = $ArtDao->select($Art_ID, NULL, NULL, NULL, true);



	



	if (!$ArtObj) {



		



		$ArtObj = new stdClass();



		



		$ArtObj->art_id = 0;



		$ArtObj->artttl = '';



		$ArtObj->artdsc = '';



		$ArtObj->artdat = date("Y-m-d H:i:s");;



		$ArtObj->seourl = '';



		$ArtObj->seokey = '';



		$ArtObj->seodsc = '';



		$ArtObj->arttxt = '';



		$ArtObj->arttyp = '';



		$ArtObj->artimg = '';



		$ArtObj->sta_id = 0;



		$ArtObj->artobj = '';







		if (isset($_REQUEST['artttl'])) $ArtObj->artttl = $_REQUEST['artttl'];



		if (isset($_REQUEST['artdsc'])) $ArtObj->artdsc = $_REQUEST['artdsc'];



		if (isset($_REQUEST['artdat'])) $ArtObj->artdat = $_REQUEST['artdat'];



		if (isset($_REQUEST['seourl'])) $ArtObj->seourl = $_REQUEST['seourl'];



		if (isset($_REQUEST['seokey'])) $ArtObj->seokey = $_REQUEST['seokey'];



		if (isset($_REQUEST['seodsc'])) $ArtObj->seodsc = $_REQUEST['seodsc'];



		if (isset($_REQUEST['arttxt'])) $ArtObj->arttxt = $_REQUEST['arttxt'];



		if (isset($_REQUEST['arttyp'])) $ArtObj->arttyp = $_REQUEST['arttyp'];



		if (isset($_REQUEST['artimg'])) $ArtObj->artimg = $_REQUEST['artimg'];



		if (isset($_REQUEST['sta_id'])) $ArtObj->sta_id = $_REQUEST['sta_id'];



        if (isset($_REQUEST['artobj'])) $ArtObj->artobj = $_REQUEST['artobj'];







		$Art_ID = $ArtDao->update($ArtObj);



		



		$throwJSON['id'] = $Art_ID;



		$throwJSON['title'] = 'Article Created';



		$throwJSON['description'] = 'Article '.$ArtObj->artdsc.' created';



		$throwJSON['type'] = 'success';







		



	} else {



		



		if (isset($_REQUEST['artttl'])) $ArtObj->artttl = $_REQUEST['artttl'];



		if (isset($_REQUEST['artdsc'])) $ArtObj->artdsc = $_REQUEST['artdsc'];



		if (isset($_REQUEST['artdat'])) $ArtObj->artdat = $_REQUEST['artdat'];



		if (isset($_REQUEST['seourl'])) $ArtObj->seourl = $_REQUEST['seourl'];



		if (isset($_REQUEST['seokey'])) $ArtObj->seokey = $_REQUEST['seokey'];



		if (isset($_REQUEST['seodsc'])) $ArtObj->seodsc = $_REQUEST['seodsc'];



		if (isset($_REQUEST['arttxt'])) $ArtObj->arttxt = $_REQUEST['arttxt'];



		if (isset($_REQUEST['arttyp'])) $ArtObj->arttyp = $_REQUEST['arttyp'];



		if (isset($_REQUEST['artimg'])) $ArtObj->artimg = $_REQUEST['artimg'];



		if (isset($_REQUEST['sta_id'])) $ArtObj->sta_id = $_REQUEST['sta_id'];



        if (isset($_REQUEST['artobj'])) $ArtObj->artobj = $_REQUEST['artobj'];



		



		$Art_ID = $ArtDao->update($ArtObj);



		



		$throwJSON['id'] = $Art_ID;



		$throwJSON['title'] = 'Article Updated';



		$throwJSON['description'] = 'Article '.$ArtObj->artdsc.' updated';



		$throwJSON['type'] = 'success';



		



	}







} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete') {



	



	$ArtObj = $ArtDao->select($Art_ID, NULL, NULL, NULL, true);



	if ($ArtObj) $ArtDao->delete($ArtObj->art_id);



	







	$throwJSON['id'] = $ArtObj->art_id;



	$throwJSON['title'] = 'Article Deleted';



	$throwJSON['description'] = 'Article '.$ArtObj->artdsc.' deleted';



	$throwJSON['type'] = 'success';



	



}







die(json_encode($throwJSON));







?>