<?php

require_once("../../config/config.php");
require_once("../patchworks.php");
require_once("../products/classes/products.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) die('nologin');

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

} else {

    var_dump($_POST);

    if ( isset($_POST['days']) && is_numeric($_POST['days']) ) {

        echo '#';

        $TmpPrd = new PrdDAO();
        $TmpPrd->updateLeadTime($_POST['days']);

    }

}



?>