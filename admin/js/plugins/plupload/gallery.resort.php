<?php
require_once("../config/patchworks.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: login.php');

$SrtOrd = (isset($_GET['srtord'])) ? $_GET['srtord'] : NULL;

if (!is_null($SrtOrd)) {
	
	$SrtOrd = explode(",",$SrtOrd);

//	echo '<p>'.var_dump($SrtOrd).'</p>';

	for ($o=0; $o<count($SrtOrd); $o++) {
		
		$qryArray = array();
		$sql = 'UPDATE uploads SET
				srtord = '.$o.'
				WHERE upl_id = '.$SrtOrd[$o];
		
//		echo $sql;
		
		$upload = $patchworks->run($sql, $qryArray, false);
		
	}

}

?>