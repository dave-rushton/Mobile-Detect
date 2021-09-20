<?php

if ($_POST) {
	
	require_once('../config/config.php');
	require_once('patchworks.php');
	
	$loggedIn = new AuthDAO();
	$loginRec = $loggedIn->login($_POST['useremail'], $_POST['password']);
		
	if (is_array($loginRec)) {
		
		$_SESSION['s_usrnam'] = $loginRec[0]['usrnam'];
		$_SESSION['s_usracc'] = explode(',',$loginRec[0]['usracc']);
		$_SESSION['s_accstr'] = $loginRec[0]['usracc'];
		
		$_SESSION['s_log_id'] = $loginRec[0]['log_id'];
		$_SESSION['s_usrema'] = $loginRec[0]['usrema'];
		
		header( 'Location: index.php' );
		exit();
		
	} else {
		header( 'Location: login.php?error=true' );
		exit();
	}
	
}

?>