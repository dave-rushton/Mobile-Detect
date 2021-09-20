<?php

require_once( "../../config/config.php" );
require_once( "../../admin/patchworks.php" );
require_once( "../../admin/system/classes/places.cls.php" );

$PlaDao = new PlaDAO();

$FwdURL =  (isset($_POST['fwdurl'])) ? $_POST['fwdurl'] : 'useraccount/account';
$LogEma =  (isset($_POST['loginEmail'])) ? $_POST['loginEmail'] : '';
$LogPwd =  (isset($_POST['loginPassword'])) ? $_POST['loginPassword'] : '';

$PwdTok = $PlaDao->login($LogEma, $LogPwd, 'CUS', NULL);

$_SESSION['loginToken'] = $PwdTok;

if (!empty($PwdTok)) {
    header('location: ' . $patchworks->webRoot . $FwdURL);
    exit();
} else {
    header('location: '.$patchworks->webRoot.'useraccount/login?fwdurl=' . $FwdURL .'&error=login');
}

?>