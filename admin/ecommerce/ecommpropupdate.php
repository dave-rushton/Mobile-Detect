<?php

require_once("../../config/config.php");
require_once("../patchworks.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) die('login');

$throwJSON = array();
$throwJSON['id'] = '0';
$throwJSON['title'] = 'noaction';
$throwJSON['description'] = 'no action taken';
$throwJSON['type'] = 'warning';

if ($loggedIn == 0) {

    $throwJSON['title'] = 'Authorisation';
    $throwJSON['description'] = 'You are not authorised for this action';
    $throwJSON['type'] = 'error';
    die(json_encode($throwJSON));

}

$sql = "UPDATE ecommprop
		SET
		comnam = :comnam,
		adr1   = :adr1,
		adr2   = :adr2,
		adr3   = :adr3,
		adr4   = :adr4,
		pstcod = :pstcod,
		emaadr = :emaadr,
		comtel = :comtel,
        sp_sta = :sp_sta,
        sp_ven = :sp_ven,
        sp_enc = :sp_enc,
        sptven = :sptven,
        sptenc = :sptenc,
        pp_sta = :pp_sta,
        pp_ema = :pp_ema,
        wp_sta = :wp_sta,
        wpinst = :wpinst,
        incvat = :incvat,
        outstk = :outstk,
        colect = :colect,
        prddsp = :prddsp,
        acctyp = :acctyp,
        ecoobj = :ecoobj
		";

$qryArray = array();
$qryArray['comnam'] = (isset($_POST['comnam'])) ? $_POST['comnam'] : '';
$qryArray['adr1'] = (isset($_POST['adr1'])) ? $_POST['adr1'] : '';
$qryArray['adr2'] = (isset($_POST['adr2'])) ? $_POST['adr2'] : '';
$qryArray['adr3'] = (isset($_POST['adr3'])) ? $_POST['adr3'] : '';
$qryArray['adr4'] = (isset($_POST['adr4'])) ? $_POST['adr4'] : '';
$qryArray['pstcod'] = (isset($_POST['pstcod'])) ? $_POST['pstcod'] : '';
$qryArray['emaadr'] = (isset($_POST['emaadr'])) ? $_POST['emaadr'] : '';
$qryArray['comtel'] = (isset($_POST['comtel'])) ? $_POST['comtel'] : '';

$qryArray['sp_sta'] = (isset($_POST['sp_sta'])) ? $_POST['sp_sta'] : '';
$qryArray['sp_ven'] = (isset($_POST['sp_ven'])) ? $_POST['sp_ven'] : '';
$qryArray['sp_enc'] = (isset($_POST['sp_enc'])) ? $_POST['sp_enc'] : '';
$qryArray['sptven'] = (isset($_POST['sptven'])) ? $_POST['sptven'] : '';
$qryArray['sptenc'] = (isset($_POST['sptenc'])) ? $_POST['sptenc'] : '';
$qryArray['pp_sta'] = (isset($_POST['pp_sta'])) ? $_POST['pp_sta'] : '';
$qryArray['pp_ema'] = (isset($_POST['pp_ema'])) ? $_POST['pp_ema'] : '';

$qryArray['wp_sta'] = (isset($_POST['wp_sta'])) ? $_POST['wp_sta'] : '';
$qryArray['wpinst'] = (isset($_POST['wpinst'])) ? $_POST['wpinst'] : '';

$qryArray['incvat'] = (isset($_POST['incvat'])) ? $_POST['incvat'] : '0';

$qryArray['outstk'] = (isset($_POST['outstk'])) ? $_POST['outstk'] : '0';
$qryArray['colect'] = (isset($_POST['colect'])) ? $_POST['colect'] : '0';
$qryArray['prddsp'] = (isset($_POST['prddsp'])) ? $_POST['prddsp'] : '1';
$qryArray['acctyp'] = (isset($_POST['acctyp'])) ? $_POST['acctyp'] : '0';

$qryArray['ecoobj'] = (isset($_POST['ecoobj'])) ? $_POST['ecoobj'] : '';

$recordSet = $patchworks->dbConn->prepare($sql);
$recordSet->execute($qryArray);

$throwJSON['title'] = 'Properties Updated';
$throwJSON['description'] = 'Success';
$throwJSON['type'] = 'error';

die(json_encode($throwJSON));


?>