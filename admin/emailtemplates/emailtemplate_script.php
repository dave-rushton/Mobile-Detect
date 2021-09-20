<?php



require_once("../../config/config.php");

require_once("../patchworks.php");

require_once("classes/emailtemplate.cls.php");



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





$Emt_ID = (isset($_REQUEST['emt_id']) && is_numeric($_REQUEST['emt_id'])) ? $_REQUEST['emt_id'] : NULL;



if (is_null($Emt_ID)) {

    $throwJSON['title'] = 'Invalid Email Section';

    $throwJSON['description'] = 'Email Section not found';

    $throwJSON['type'] = 'error';

}



$EmtDao = new EmtDAO();



if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update' && !is_null($Emt_ID)) {



    $EmtObj = $EmtDao->select($Emt_ID, NULL, NULL, NULL, true);



    if (!$EmtObj) {



        $EmtObj = new stdClass();



        $EmtObj->emt_id = 0;

        $EmtObj->tblnam = 'WEBSITE';

        $EmtObj->tbl_id = 0;

        $EmtObj->emtnam = '';

        $EmtObj->sta_id = 0;


        if (isset($_REQUEST['emtnam'])) $EmtObj->emtnam = $_REQUEST['emtnam'];

        if (isset($_REQUEST['tblnam'])) $EmtObj->tblnam = $_REQUEST['tblnam'];

        if (isset($_REQUEST['tbl_id']) && is_numeric($_REQUEST['tbl_id'])) $EmtObj->tbl_id = $_REQUEST['tbl_id'];

        if (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) $EmtObj->sta_id = $_REQUEST['sta_id'];



        $Emt_ID = $EmtDao->update($EmtObj);



        $throwJSON['id'] = $Emt_ID;

        $throwJSON['title'] = 'Email Section Created';

        $throwJSON['description'] = 'Email Section '.$EmtObj->emtnam.' created';

        $throwJSON['type'] = 'success';





    } else {



        if (isset($_REQUEST['tblnam'])) $EmtObj->tblnam = $_REQUEST['tblnam'];

        if (isset($_REQUEST['tbl_id']) && is_numeric($_REQUEST['tbl_id'])) $EmtObj->tbl_id = $_REQUEST['tbl_id'];

        if (isset($_REQUEST['emtnam'])) $EmtObj->emtnam = $_REQUEST['emtnam'];

        if (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) $EmtObj->sta_id = $_REQUEST['sta_id'];



        $Emt_ID = $EmtDao->update($EmtObj);



        $throwJSON['id'] = $Emt_ID;

        $throwJSON['title'] = 'Email Section Updated';

        $throwJSON['description'] = 'Email Section '.$EmtObj->emtnam.' updated';

        $throwJSON['type'] = 'success';



    }



} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete') {



    $EmtObj = $EmtDao->select($Emt_ID, NULL, NULL, NULL, true);

    if ($EmtObj) $EmtDao->delete($EmtObj->emt_id);





    $throwJSON['id'] = $EmtObj->emt_id;

    $throwJSON['title'] = 'Email Section Deleted';

    $throwJSON['description'] = 'Email Section '.$EmtObj->emtnam.' deleted';

    $throwJSON['type'] = 'success';



} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'select') {



    $EmtObj = $EmtDao->select($Emt_ID, NULL, NULL, NULL, true);

    $jsonArray = array();



    if (is_numeric($Emt_ID) && $EmtObj) {



        $recordArray = array();

        $recordArray['emt_id'] = $EmtObj->emt_id;

        $recordArray['tblnam'] = $EmtObj->tblnam;

        $recordArray['tbl_id'] = $EmtObj->tbl_id;

        $recordArray['emtnam'] = $EmtObj->emtnam;

        $recordArray['sta_id'] = $EmtObj->sta_id;

        $jsonArray[] = $recordArray;

    }



    die(json_encode($jsonArray));



}



die(json_encode($throwJSON));



?>