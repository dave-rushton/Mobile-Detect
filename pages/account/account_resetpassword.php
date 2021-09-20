<?php

require_once( "../../config/config.php" );
require_once( "../../admin/patchworks.php" );
require_once( "../../admin/system/classes/places.cls.php" );

$PwdTok =  (isset($_POST['pwdtok'])) ? $_POST['pwdtok'] : '+++';
$PasWrd =  (isset($_POST['newpassword'])) ? $_POST['newpassword'] : '';
$CnfPas =  (isset($_POST['confirmpassword'])) ? $_POST['confirmpassword'] : '';

if (!empty($PasWrd) && !empty($CnfPas) && $PasWrd == $CnfPas) {

    $PlaDao = new PlaDAO();
    $userRecord = $PlaDao->loggedIn($PwdTok);

    if (isset($userRecord->planam)) {

        $userRecord->paswrd = $PasWrd;

        $Pla_ID = $PlaDao->update($userRecord);

        header('location: ' . $patchworks->webRoot . 'useraccount/login');
        exit();

    }

    header('location: ' . $patchworks->webRoot . 'useraccount/forgotpassword?result=error');
    exit();

} else {

    header('location: ' . $patchworks->webRoot . 'useraccount/forgotpassword?result=error');
    exit();

}


?>