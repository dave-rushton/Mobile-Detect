<?php
require_once("../../config/config.php");
require_once("../../admin/patchworks.php");
require_once("../../admin/system/classes/places.cls.php");

$PwdTok = (isset($_SESSION['loginToken'])) ? $_SESSION['loginToken'] : '';
$PlaDao = new PlaDAO();
$loggedIn = $PlaDao->loggedIn($PwdTok);

if (!$loggedIn) die('login');

function geocode($address){

    // url encode the address
    $address = urlencode($address);

    // google map geocode api url
    $url = "http://maps.google.com/maps/api/geocode/json?sensor=false&address={$address}";

    // get the json response
    $resp_json = file_get_contents($url);

    // decode the json
    $resp = json_decode($resp_json, true);

    // response status will be 'OK', if able to geocode given address
    if($resp['status']=='OK'){

        // get the important data
        $lati = $resp['results'][0]['geometry']['location']['lat'];
        $longi = $resp['results'][0]['geometry']['location']['lng'];
        $formatted_address = $resp['results'][0]['formatted_address'];

        // verify if data is complete
        if($lati && $longi && $formatted_address){

            // put the data in the array
            $data_arr = array();

            array_push(
                $data_arr,
                $lati,
                $longi,
                $formatted_address
            );

            return $data_arr;

        }else{
            return false;
        }

    }else{
        return false;
    }
}

$PlaDao = new PlaDAO();
$PlaObj = $PlaDao->select($loggedIn->pla_id, NULL, NULL, NULL, NULL, true);

if (isset($_REQUEST['tblnam'])) $PlaObj->tblnam = $_REQUEST['tblnam'];
if (isset($_REQUEST['tbl_id']) && is_numeric($_REQUEST['tbl_id'])) $PlaObj->tbl_id = $_REQUEST['tbl_id'];
if (isset($_REQUEST['comnam'])) $PlaObj->comnam = $_REQUEST['comnam'];
if (isset($_REQUEST['planam'])) $PlaObj->planam = $_REQUEST['planam'];

if (isset($_REQUEST['plattl'])) $PlaObj->plattl = $_REQUEST['plattl'];
if (isset($_REQUEST['plafna'])) $PlaObj->plafna = $_REQUEST['plafna'];
if (isset($_REQUEST['plasna'])) $PlaObj->plasna = $_REQUEST['plasna'];

if (isset($_REQUEST['adr1'])) $PlaObj->adr1 = $_REQUEST['adr1'];
if (isset($_REQUEST['adr2'])) $PlaObj->adr2 = $_REQUEST['adr2'];
if (isset($_REQUEST['adr3'])) $PlaObj->adr3 = $_REQUEST['adr3'];
if (isset($_REQUEST['adr4'])) $PlaObj->adr4 = $_REQUEST['adr4'];
if (isset($_REQUEST['pstcod'])) $PlaObj->pstcod = $_REQUEST['pstcod'];
if (isset($_REQUEST['coucod'])) $PlaObj->coucod = $_REQUEST['coucod'];
if (isset($_REQUEST['ctynam'])) $PlaObj->ctynam = $_REQUEST['ctynam'];
if (isset($_REQUEST['country'])) $PlaObj->coucod = $_REQUEST['country'];
if (isset($_REQUEST['plaema'])) $PlaObj->plaema = $_REQUEST['plaema'];
if (isset($_REQUEST['platel'])) $PlaObj->platel = $_REQUEST['platel'];
if (isset($_REQUEST['plamob'])) $PlaObj->plamob = $_REQUEST['plamob'];
if (isset($_REQUEST['plaref'])) $PlaObj->plaref = $_REQUEST['plaref'];
if (isset($_REQUEST['usrnam'])) $PlaObj->usrnam = $_REQUEST['usrnam'];

$PlaObj->paswrd = $_REQUEST['paswrd'];

if (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) { $PlaObj->sta_id = $_REQUEST['sta_id']; }
if (isset($_REQUEST['plaimg'])) $PlaObj->plaimg = $_REQUEST['plaimg'];
if (isset($_REQUEST['rooms']) && is_numeric($_REQUEST['rooms'])) $PlaObj->rooms = $_REQUEST['rooms'];
if (isset($_REQUEST['platyp']) && is_numeric($_REQUEST['platyp'])) $PlaObj->platyp = $_REQUEST['platyp'];
if (isset($_REQUEST['placol'])) $PlaObj->placol = $_REQUEST['placol'];
if (isset($_REQUEST['plaurl'])) $PlaObj->plaurl = $_REQUEST['plaurl'];
if (isset($_REQUEST['platxt'])) $PlaObj->platxt = $_REQUEST['platxt'];


$data_arr = geocode($PlaObj->pstcod.' ,united kingdom');

if ($data_arr) {

    $PlaObj->goolat = $data_arr[0];
    $PlaObj->goolng = $data_arr[1];

}

$Pla_ID = $PlaDao->update($PlaObj);

header('location: ../../useraccount/account?update=ok');

?>
