<?php

require_once("../../config/config.php");
require_once("../../admin/patchworks.php");
require_once("../../admin/system/classes/places.cls.php");

$PwdTok = (isset($_SESSION['loginToken'])) ? $_SESSION['loginToken'] : '';
$PlaDao = new PlaDAO();
$loggedIn = $PlaDao->loggedIn($PwdTok);


if (!$loggedIn) {
    header('location: login');
    exit();
}


$Pla_ID = (isset($_REQUEST['pla_id']) && is_numeric($_REQUEST['pla_id'])) ? $_REQUEST['pla_id'] : NULL;
if (is_null($Pla_ID)) header('location: ../../useraccount/addresses?result=error');


$PlaDao = new PlaDAO();
$PlaObj = $PlaDao->select($Pla_ID, NULL, NULL, NULL, NULL, true);

if (!$PlaObj) {

    $PlaObj = new stdClass();
    $PlaObj->pla_id = 0;
    $PlaObj->tblnam = '';
    $PlaObj->tbl_id = 0;
    $PlaObj->comnam = '';
    $PlaObj->planam = '';
    $PlaObj->adr1 = '';
    $PlaObj->adr2 = '';
    $PlaObj->adr3 = '';
    $PlaObj->adr4 = '';
    $PlaObj->pstcod = '';
    $PlaObj->coucod = '';
    $PlaObj->ctynam = '';
    $PlaObj->goolat = 0;
    $PlaObj->goolng = 0;
    $PlaObj->plaema = '';
    $PlaObj->platel = '';
    $PlaObj->plamob = '';
    $PlaObj->plaref = '';
    $PlaObj->usrnam = '';
    $PlaObj->paswrd = 'password';
    $PlaObj->sta_id = 0;
    $PlaObj->credat = date("Y-m-d H:i:s");
    $PlaObj->amndat = date("Y-m-d H:i:s");
    $PlaObj->plaimg = '';
    $PlaObj->minpri = 0;
    $PlaObj->maxpri = 0;
    $PlaObj->rooms = 0;
    $PlaObj->platyp = 0;
    $PlaObj->placol = '#f3f3f3';
    $PlaObj->plaurl = '';
    $PlaObj->platxt = '';
    $PlaObj->seourl = '';
    $PlaObj->keywrd = '';
    $PlaObj->keydsc = '';

    if (isset($_REQUEST['tblnam'])) $PlaObj->tblnam = $_REQUEST['tblnam'];
    if (isset($_REQUEST['tbl_id']) && is_numeric($_REQUEST['tbl_id'])) $PlaObj->tbl_id = $_REQUEST['tbl_id'];
    if (isset($_REQUEST['comnam'])) $PlaObj->comnam = $_REQUEST['comnam'];
    if (isset($_REQUEST['planam'])) $PlaObj->planam = $_REQUEST['planam'];
    if (isset($_REQUEST['adr1'])) $PlaObj->adr1 = $_REQUEST['adr1'];
    if (isset($_REQUEST['adr2'])) $PlaObj->adr2 = $_REQUEST['adr2'];
    if (isset($_REQUEST['adr3'])) $PlaObj->adr3 = $_REQUEST['adr3'];
    if (isset($_REQUEST['adr4'])) $PlaObj->adr4 = $_REQUEST['adr4'];
    if (isset($_REQUEST['pstcod'])) $PlaObj->pstcod = $_REQUEST['pstcod'];
    if (isset($_REQUEST['coucod'])) $PlaObj->coucod = $_REQUEST['coucod'];
    if (isset($_REQUEST['ctynam'])) $PlaObj->ctynam = $_REQUEST['ctynam'];
    if (isset($_REQUEST['goolat'])) $PlaObj->goolat = $_REQUEST['goolat'];
    if (isset($_REQUEST['goolng'])) $PlaObj->goolng = $_REQUEST['goolng'];
    if (isset($_REQUEST['plaema'])) $PlaObj->plaema = $_REQUEST['plaema'];
    if (isset($_REQUEST['platel'])) $PlaObj->platel = $_REQUEST['platel'];
    if (isset($_REQUEST['plamob'])) $PlaObj->plamob = $_REQUEST['plamob'];
    if (isset($_REQUEST['plaref'])) $PlaObj->plaref = $_REQUEST['plaref'];
    if (isset($_REQUEST['usrnam'])) $PlaObj->usrnam = $_REQUEST['usrnam'];
    if (isset($_REQUEST['paswrd'])) $PlaObj->paswrd = $_REQUEST['paswrd'];
    if (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) $PlaObj->sta_id = $_REQUEST['sta_id'];
    if (isset($_REQUEST['credat'])) $PlaObj->credat = $_REQUEST['credat'];
    if (isset($_REQUEST['amndat'])) $PlaObj->amndat = $_REQUEST['amndat'];
    if (isset($_REQUEST['plaimg'])) $PlaObj->plaimg = $_REQUEST['plaimg'];
    if (isset($_REQUEST['minpri'])) $PlaObj->minpri = $_REQUEST['minpri'];
    if (isset($_REQUEST['maxpri'])) $PlaObj->maxpri = $_REQUEST['maxpri'];
    if (isset($_REQUEST['rooms']) && is_numeric($_REQUEST['rooms'])) $PlaObj->rooms = $_REQUEST['rooms'];
    if (isset($_REQUEST['platyp']) && is_numeric($_REQUEST['platyp'])) $PlaObj->platyp = $_REQUEST['platyp'];
    if (isset($_REQUEST['placol'])) $PlaObj->placol = $_REQUEST['placol'];
    if (isset($_REQUEST['plaurl'])) $PlaObj->plaurl = $_REQUEST['plaurl'];
    if (isset($_REQUEST['platxt'])) $PlaObj->platxt = urldecode($_REQUEST['platxt']);

    if (isset($_REQUEST['seourl'])) $PlaObj->seourl = $_REQUEST['seourl'];
    if (isset($_REQUEST['keywrd'])) $PlaObj->keywrd = $_REQUEST['keywrd'];
    if (isset($_REQUEST['keydsc'])) $PlaObj->keydsc = $_REQUEST['keydsc'];


    $Pla_ID = $PlaDao->update($PlaObj);



} else {

    if ($loggedIn->pla_id == $PlaObj->tbl_id) {

        if (isset($_REQUEST['tblnam'])) $PlaObj->tblnam = $_REQUEST['tblnam'];
        if (isset($_REQUEST['tbl_id']) && is_numeric($_REQUEST['tbl_id'])) $PlaObj->tbl_id = $_REQUEST['tbl_id'];
        if (isset($_REQUEST['comnam'])) $PlaObj->comnam = $_REQUEST['comnam'];
        if (isset($_REQUEST['planam'])) $PlaObj->planam = $_REQUEST['planam'];
        if (isset($_REQUEST['adr1'])) $PlaObj->adr1 = $_REQUEST['adr1'];
        if (isset($_REQUEST['adr2'])) $PlaObj->adr2 = $_REQUEST['adr2'];
        if (isset($_REQUEST['adr3'])) $PlaObj->adr3 = $_REQUEST['adr3'];
        if (isset($_REQUEST['adr4'])) $PlaObj->adr4 = $_REQUEST['adr4'];
        if (isset($_REQUEST['pstcod'])) $PlaObj->pstcod = $_REQUEST['pstcod'];
        if (isset($_REQUEST['coucod'])) $PlaObj->coucod = $_REQUEST['coucod'];
        if (isset($_REQUEST['ctynam'])) $PlaObj->ctynam = $_REQUEST['ctynam'];
        if (isset($_REQUEST['goolat'])) $PlaObj->goolat = $_REQUEST['goolat'];
        if (isset($_REQUEST['goolng'])) $PlaObj->goolng = $_REQUEST['goolng'];
        if (isset($_REQUEST['plaema'])) $PlaObj->plaema = $_REQUEST['plaema'];
        if (isset($_REQUEST['platel'])) $PlaObj->platel = $_REQUEST['platel'];
        if (isset($_REQUEST['plamob'])) $PlaObj->plamob = $_REQUEST['plamob'];
        if (isset($_REQUEST['plaref'])) $PlaObj->plaref = $_REQUEST['plaref'];
        if (isset($_REQUEST['usrnam'])) $PlaObj->usrnam = $_REQUEST['usrnam'];
        if (isset($_REQUEST['paswrd'])) $PlaObj->paswrd = $_REQUEST['paswrd'];
        if (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) {
            $PlaObj->sta_id = $_REQUEST['sta_id'];
        }
        if (isset($_REQUEST['credat'])) $PlaObj->credat = $_REQUEST['credat'];
        if (isset($_REQUEST['amndat'])) $PlaObj->amndat = $_REQUEST['amndat'];
        if (isset($_REQUEST['plaimg'])) $PlaObj->plaimg = $_REQUEST['plaimg'];
        if (isset($_REQUEST['minpri'])) $PlaObj->minpri = $_REQUEST['minpri'];
        if (isset($_REQUEST['maxpri'])) $PlaObj->maxpri = $_REQUEST['maxpri'];
        if (isset($_REQUEST['rooms']) && is_numeric($_REQUEST['rooms'])) $PlaObj->rooms = $_REQUEST['rooms'];
        if (isset($_REQUEST['platyp']) && is_numeric($_REQUEST['platyp'])) $PlaObj->platyp = $_REQUEST['platyp'];
        if (isset($_REQUEST['placol'])) $PlaObj->placol = $_REQUEST['placol'];
        if (isset($_REQUEST['plaurl'])) $PlaObj->plaurl = $_REQUEST['plaurl'];
        if (isset($_REQUEST['platxt'])) $PlaObj->platxt = urldecode($_REQUEST['platxt']);

        if (isset($_REQUEST['seourl'])) $PlaObj->seourl = $_REQUEST['seourl'];
        if (isset($_REQUEST['keywrd'])) $PlaObj->keywrd = $_REQUEST['keywrd'];
        if (isset($_REQUEST['keydsc'])) $PlaObj->keydsc = $_REQUEST['keydsc'];

        $Pla_ID = $PlaDao->update($PlaObj);

    } else {

        header('location: ../../useraccount/addresses?result=error');

    }

}

header('location: ../../useraccount/addresses?result=ok');


?>