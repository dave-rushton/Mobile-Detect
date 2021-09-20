<?php

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("../website/classes/pages.cls.php");
require_once("../website/classes/pageelements.cls.php");
require_once("../website/classes/pagecontent.cls.php");


$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

// find from page

$PagDao = new PagDAO();
$PelDao = new PelDAO();
$PgcDao = new PgcDAO();

if (empty($_GET['frmpag']) || empty($_GET['to_pag'])) {
    header('location: copycontent.php?error=page');
    die();
}

$FrmPag = $_GET['frmpag'];
$To_Pag = $_GET['to_pag'];

$fromPage = $PagDao->select(NULL, $FrmPag, true);
$toPage = $PagDao->select(NULL, $To_Pag, true);


//if ($fromPage->tmplte != $toPage->tmplte) {
//    header('location: copycontent.php?error=template&frmpag='.$FrmPag.'&to_pag='.$To_Pag);
//    die();
//}

// get page elements

//echo '<br>pag_id: '.$fromPage->pag_id.' '.$fromPage->pagttl;
//echo '<br>pag_id: '.$toPage->pag_id.' '.$toPage->pagttl;

$fromElements = $PelDao->select(NULL, $fromPage->pag_id, NULL, false);
for ($i=0;$i<count($fromElements);$i++) {

    //echo '<br>pel_id: '.$fromElements[$i]['elevar'];

    $EleObj = new stdClass();
    $EleObj->pel_id = 0;
    $EleObj->pag_id = $toPage->pag_id;
    $EleObj->div_id = $fromElements[$i]['div_id'];
    $EleObj->srtord = $fromElements[$i]['srtord'];
    $EleObj->eletyp = $fromElements[$i]['eletyp'];
    $EleObj->pgc_id = $fromElements[$i]['pgc_id'];
    $EleObj->incfil = $fromElements[$i]['incfil'];
    $EleObj->incurl = $fromElements[$i]['incurl'];
    $EleObj->sta_id = $fromElements[$i]['sta_id'];
    $EleObj->elevar = $fromElements[$i]['elevar'];
    $Pel_ID = $PelDao->update($EleObj);

    $fromContent = $PgcDao->select($fromElements[$i]['pgc_id'], NULL, NULL, true);

    //echo '<br>#'.$fromElements[$i]['pgc_id'];

    if (isset($fromContent->pgctxt)) {

        //echo '<br>'.$fromContent->pgctxt;

        $EleObj = new stdClass();
        $EleObj->pgcttl = $fromContent->pgcttl;
        $EleObj->pgc_id = 0;
        $EleObj->pgctxt = $fromContent->pgctxt;
        $EleObj->sta_id = $fromContent->sta_id;
        $EleObj->tblnam = $fromContent->tblnam;
        $EleObj->tbl_id = $fromContent->tbl_id;

        $Pgc_ID = $PgcDao->update($EleObj);

    }

}

header('location: copycontent.php?frmpag='.$FrmPag.'&to_pag='.$To_Pag);

?>