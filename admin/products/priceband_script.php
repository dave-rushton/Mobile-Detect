<?php

require_once("../../config/config.php");
require_once("../patchworks.php");

require_once("classes/structure.cls.php");
require_once("../products/classes/pricebands.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);

$throwJSON = array();
$throwJSON['id'] = '0';
$throwJSON['title'] = 'noaction';
$throwJSON['description'] = 'no action taken';
$throwJSON['type'] = 'warning';

$PrbDao = new PrbDAO();

if ($loggedIn == 0) {
    
    $throwJSON['title'] = 'Authorisation';
    $throwJSON['description'] = 'You are not authorised for this action';
    $throwJSON['type'] = 'error';

    die(json_encode($throwJSON));

}

$Prb_ID = (isset($_REQUEST['prb_id']) && is_numeric($_REQUEST['prb_id'])) ? $_REQUEST['prb_id'] : die('fail');

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'select') {

    $priceBand = $PrbDao->select($Prb_ID, NULL, NULL, NULL, NULL, NULL, NULL, true);

    die(json_encode($priceBand));

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'search') {

    $Cus_ID = (isset($_REQUEST['cus_id']) && is_numeric($_REQUEST['cus_id'])) ? $_REQUEST['cus_id'] : NULL;
    $Prt_ID = (isset($_REQUEST['prt_id']) && is_numeric($_REQUEST['prt_id'])) ? $_REQUEST['prt_id'] : NULL;
    $Prd_ID = (isset($_REQUEST['prd_id']) && is_numeric($_REQUEST['prd_id'])) ? $_REQUEST['prd_id'] : NULL;
    $CurDat = (isset($_REQUEST['curdat']) && is_numeric($_REQUEST['curdat'])) ? $_REQUEST['curdat'] : NULL;
    $NumUni = (isset($_REQUEST['numuni']) && is_numeric($_REQUEST['numuni'])) ? $_REQUEST['numuni'] : NULL;
    $Sta_ID = (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) ? $_REQUEST['sta_id'] : NULL;

    $priceBand = $PrbDao->select(NULL, $Cus_ID, $Prt_ID, $Prd_ID, $CurDat, $NumUni, $Sta_ID, false);

    die(json_encode($priceBand));

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update') {

    $PrbObj = $PrbDao->select($Prb_ID, NULL, NULL, NULL, NULL, NULL, NULL, true);

    if (!$PrbObj) {

        $PrbObj = new stdClass();
        $PrbObj->prb_id = 0;
        $PrbObj->cus_id = 0;
        $PrbObj->prt_id = 0;
        $PrbObj->prd_id = 0;
        $PrbObj->begdat = '';
        $PrbObj->enddat = '';
        $PrbObj->prityp = 'A';
        $PrbObj->numuni = 1;
        $PrbObj->unipri = 0;
        $PrbObj->sta_id = 0;

        if (isset($_REQUEST['cus_id']) && is_numeric($_REQUEST['cus_id'])) $PrbObj->cus_id = $_REQUEST['cus_id'];
        if (isset($_REQUEST['prb_id']) && is_numeric($_REQUEST['prb_id'])) $PrbObj->prb_id = $_REQUEST['prb_id'];
        if (isset($_REQUEST['prt_id']) && is_numeric($_REQUEST['prt_id'])) $PrbObj->prt_id = $_REQUEST['prt_id'];
        if (isset($_REQUEST['prd_id']) && is_numeric($_REQUEST['prd_id'])) $PrbObj->prd_id = $_REQUEST['prd_id'];

        if (isset($_REQUEST['begdat'])) $PrbObj->begdat = $_REQUEST['begdat'];
        if (isset($_REQUEST['enddat'])) $PrbObj->enddat = $_REQUEST['enddat'];

        if (isset($_REQUEST['prityp'])) $PrbObj->prityp = $_REQUEST['prityp'];
        if (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) $PrbObj->sta_id = $_REQUEST['sta_id'];

        if (is_array($_REQUEST['numuni'])) {

            for ($b=0;$b<count($_REQUEST['numuni']);$b++) {

                if (is_numeric($_REQUEST['numuni'][$b])) $PrbObj->numuni = $_REQUEST['numuni'][$b];
                if (is_numeric($_REQUEST['unipri'][$b])) $PrbObj->unipri = $_REQUEST['unipri'][$b];

                $Prb_ID = $PrbDao->update($PrbObj);

            }

            $throwJSON['id'] = 0;
            $throwJSON['title'] = 'Price Bands Created';
            $throwJSON['description'] = 'Price Bands created';
            $throwJSON['type'] = 'success';

        } else {

            if (isset($_REQUEST['numuni']) && is_numeric($_REQUEST['numuni'])) $PrbObj->numuni = $_REQUEST['numuni'];
            if (isset($_REQUEST['unipri']) && is_numeric($_REQUEST['unipri'])) $PrbObj->unipri = $_REQUEST['unipri'];

            $Prb_ID = $PrbDao->update($PrbObj);

            $throwJSON['id'] = $Prb_ID;
            $throwJSON['title'] = 'Price Band Created';
            $throwJSON['description'] = 'Price Band created';
            $throwJSON['type'] = 'success';

        }


    } else {

        if (isset($_REQUEST['prb_id']) && is_numeric($_REQUEST['prb_id'])) $PrbObj->prb_id = $_REQUEST['prb_id'];
        if (isset($_REQUEST['cus_id']) && is_numeric($_REQUEST['cus_id'])) $PrbObj->cus_id = $_REQUEST['cus_id'];
        if (isset($_REQUEST['prt_id']) && is_numeric($_REQUEST['prt_id'])) $PrbObj->prt_id = $_REQUEST['prt_id'];
        if (isset($_REQUEST['prd_id']) && is_numeric($_REQUEST['prd_id'])) $PrbObj->prd_id = $_REQUEST['prd_id'];

        if (isset($_REQUEST['begdat'])) $PrbObj->begdat = $_REQUEST['begdat'];
        if (isset($_REQUEST['enddat'])) $PrbObj->enddat = $_REQUEST['enddat'];

        if (isset($_REQUEST['prityp'])) $PrbObj->prityp = $_REQUEST['prityp'];
        if (isset($_REQUEST['numuni']) && is_numeric($_REQUEST['numuni'])) $PrbObj->numuni = $_REQUEST['numuni'];
        if (isset($_REQUEST['unipri']) && is_numeric($_REQUEST['unipri'])) $PrbObj->unipri = $_REQUEST['unipri'];
        if (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) $PrbObj->sta_id = $_REQUEST['sta_id'];

        $Prb_ID = $PrbDao->update($PrbObj);

        $throwJSON['id'] = $Prb_ID;
        $throwJSON['title'] = 'Price Band Record Updated';
        $throwJSON['description'] = 'Price Band Record updated';
        $throwJSON['type'] = 'success';

    }

} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete') {

    $PrbObj = $PrbDao->select($Prb_ID, NULL, NULL, NULL, NULL, NULL, NULL, true);
    if ($PrbObj) $PrbDao->delete($PrbObj->prb_id);

    $throwJSON['id'] = $PrbObj->prb_id;
    $throwJSON['title'] = 'Price Band Deleted';
    $throwJSON['description'] = 'Price Band deleted';
    $throwJSON['type'] = 'success';

}

die(json_encode($throwJSON));

?>