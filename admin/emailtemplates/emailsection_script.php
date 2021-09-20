<?php
require_once("../../config/config.php");
require_once("../patchworks.php");
require_once("classes/emailsections.cls.php");

$userAuth = new AuthDAO();

$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);

if ($loggedIn == 0) header('location: ../login.php');

$throwJSON = array();

$throwJSON['id'] = '0';

$throwJSON['title'] = 'No Action';

$throwJSON['description'] = 'no action taken';

$throwJSON['type'] = 'warning';

if ($loggedIn == 0) {
    //header('loemtion: ../login.php');
    $throwJSON['title'] = 'Authorisation';

    $throwJSON['description'] = 'You are not authorised for this action';

    $throwJSON['type'] = 'error';

}

$EmsDAO = new EmsDAO();

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'resort') {
    $Ems_ID = (isset($_REQUEST['ems_id'])) ? $_REQUEST['ems_id'] : die('FAIL');

    $EmsDAO->resort($Ems_ID);

    $throwJSON['id'] = 0;
    $throwJSON['title'] = 'Resort Complete';
    $throwJSON['description'] = 'Resort Complete';
    $throwJSON['type'] = 'success';

    die(json_encode($throwJSON));

}

$Ems_ID = (isset($_REQUEST['ems_id']) && is_numeric($_REQUEST['ems_id'])) ? $_REQUEST['ems_id'] : NULL;
$Emt_ID = (isset($_REQUEST['emt_id']) && is_numeric($_REQUEST['emt_id'])) ? $_REQUEST['emt_id'] : NULL;

if (is_null($Ems_ID)) {

    $throwJSON['title'] = 'Invalid Email Template';

    $throwJSON['description'] = 'Email Template not found';

    $throwJSON['type'] = 'error';

}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update' && !is_null($Ems_ID)) {

    $EmsObj = $EmsDAO->select(NULL, $Ems_ID, true);

    if (!$EmsObj) {
        $EmsObj = new stdClass();
        $EmsObj->ems_id = 0;
        $EmsObj->emt_id = 1;
        $EmsObj->emstyp = "";
        $EmsObj->emsfil = "";
        $EmsObj->emsobj = "";
        $EmsObj->srtord = 99;
        $EmsObj->sta_id = 1;

        if (isset($_REQUEST['emt_id']) && is_numeric($_REQUEST['emt_id'])) $EmsObj->emt_id = $_REQUEST['emt_id'];

        if (isset($_REQUEST['emstyp'])) $EmsObj->emstyp = $_REQUEST['emstyp'];

        if (isset($_REQUEST['emsfil'])) $EmsObj->emsfil = $_REQUEST['emsfil'];

        if (isset($_REQUEST['emsobj'])) $EmsObj->emsobj = $_REQUEST['emsobj'];

        if (isset($_REQUEST['srtord']) && is_numeric($_REQUEST['srtord'])) $EmsObj->srtord = $_REQUEST['srtord'];

        if (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) $EmsObj->sta_id = $_REQUEST['sta_id'];

        $Ems_ID = $EmsDAO->update($EmsObj);

        $throwJSON['id'] = $Ems_ID;

        $throwJSON['title'] = 'Email Template Created';

        $throwJSON['description'] = 'Email Section '.$EmsObj->emstyp.' created';

        $throwJSON['type'] = 'success';

    } else {

        if (isset($_REQUEST['emt_id']) && is_numeric($_REQUEST['emt_id'])) $EmsObj->emt_id = $_REQUEST['emt_id'];

        if (isset($_REQUEST['emstyp'])) $EmsObj->emstyp = $_REQUEST['emstyp'];

        if (isset($_REQUEST['emsfil'])) $EmsObj->emsfil = $_REQUEST['emsfil'];

        if (isset($_REQUEST['emsobj'])) $EmsObj->emsobj = $_REQUEST['emsobj'];

        if (isset($_REQUEST['srtord']) && is_numeric($_REQUEST['srtord'])) $EmsObj->srtord = $_REQUEST['srtord'];

        if (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) $EmsObj->sta_id = $_REQUEST['sta_id'];

        $Ems_ID = $EmsDAO->update($EmsObj);

        $throwJSON['id'] = $Ems_ID;
        $throwJSON['title'] = 'Email Section Updated';
        $throwJSON['description'] = 'Email Template '.$EmsObj->emstyp.' updated';
        $throwJSON['type'] = 'success';
    }
}

else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete') {


    $EmsObj = $EmsDAO->select(NULL, $Ems_ID, true);
    if ($EmsObj) $EmsDAO->delete($EmsObj->ems_id);

    $throwJSON['id'] = $EmsObj->ems_id;

    $throwJSON['title'] = 'Email Template Deleted';

    $throwJSON['description'] = 'Email Template '.$EmsObj->emstyp.' deleted';

    $throwJSON['type'] = 'success';

}

else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'select') {
    $EmsObj = $EmsDAO->select(NULL, $Ems_ID, true);
    $jsonArray = array();

    if (is_numeric($Ems_ID) && $EmsObj) {
        $recordArray = array();
        $recordArray['ems_id'] = $EmsObj->ems_id;
        $recordArray['emt_id'] = $EmsObj->emt_id;
        $recordArray['emstyp'] = $EmsObj->emstyp;
        $recordArray['emsfil'] = $EmsObj->emsfil;
        $recordArray['emsobj'] = $EmsObj->emsobj;
        $recordArray['srtord'] = $EmsObj->srtord;
        $recordArray['sta_id'] = $EmsObj->sta_id;
        $jsonArray[] = $recordArray;
    }

    die(json_encode($jsonArray));

}



die(json_encode($throwJSON));



?>