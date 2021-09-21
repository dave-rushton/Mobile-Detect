<?php

require_once("../../config/config.php");
require_once("../patchworks.php");
require_once("../products/classes/products.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) die('nologin');

$prtArray = array();

$throwJSON = array();
$throwJSON['id'] = '0';
$throwJSON['title'] = 'noaction';
$throwJSON['description'] = 'no action taken';
$throwJSON['type'] = 'warning';

if ($loggedIn == 0) {

    $throwJSON['title'] = 'Authorisation';
    $throwJSON['description'] = 'You are not authorised for this action';
    $throwJSON['type'] = 'error';

} else {

    if ( isset($_POST['action']) && $_POST['action'] == 'incrementprice' ) {

        $sql = "UPDATE products SET unipri = unipri + ((unipri / 100) * :uplift)";
        $qryArray["uplift"] = $_POST['unipriinc'];

        $recordSet = $patchworks->dbConn->prepare($sql);
        $recordSet->execute($qryArray);


        $sql = "UPDATE producttypes SET unipri = unipri + ((unipri / 100) * :uplift)";
        $qryArray["uplift"] = $_POST['unipriinc'];

        $recordSet = $patchworks->dbConn->prepare($sql);
        $recordSet->execute($qryArray);


        $sql = "UPDATE pricebands SET unipri = unipri + ((unipri / 100) * :uplift)";
        $qryArray["uplift"] = $_POST['unipriinc'];

        $recordSet = $patchworks->dbConn->prepare($sql);
        $recordSet->execute($qryArray);


    }

}

?>