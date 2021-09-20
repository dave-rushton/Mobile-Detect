<?php

require_once( "../../config/config.php" );
require_once( "../../admin/patchworks.php" );
require_once( "../../admin/system/classes/places.cls.php" );



$PlaDao = new PlaDAO();
$customerExists = $PlaDao->checkEmail($_POST['registerEmail'], 'CUS');

if (!is_null($customerExists->plaema)) {
    header('location: '.$patchworks->webRoot.'useraccount/register?error=foundemail');
    exit();
}

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

//print_r($_POST);


$PlaObj = new stdClass();
$PlaObj->pla_id = 0;
$PlaObj->tblnam = 'CUS';
$PlaObj->tbl_id = 0;
$PlaObj->comnam = '';
$PlaObj->planam = '';
$PlaObj->plattl = '';
$PlaObj->plafna = '';
$PlaObj->plasna = '';
$PlaObj->adr1 = '';
$PlaObj->adr2 = '';
$PlaObj->adr3 = '';
$PlaObj->adr4 = '';
$PlaObj->pstcod = '';
$PlaObj->ctynam = '';
$PlaObj->coucod = '';
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
$PlaObj->placol = '';
$PlaObj->plaurl = '';
$PlaObj->platxt = '';
$PlaObj->seourl = '';
$PlaObj->keywrd = '';
$PlaObj->keydsc = '';

$objectData = array();
if (isset($_POST['customerType'])) {
    $objectObject = new stdClass();
    $objectObject->name = 'customertype';
    $objectObject->value = $_POST['customerType'];
    array_push($objectData, $objectObject);
}
if (isset($_POST['tradeName'])) {
    $objectObject = new stdClass();
    $objectObject->name = 'tradename';
    $objectObject->value = $_POST['tradeName'];
    array_push($objectData, $objectObject);
}
if (isset($_POST['companyNumber'])) {
    $objectObject = new stdClass();
    $objectObject->name = 'companynumber';
    $objectObject->value = $_POST['companyNumber'];
    array_push($objectData, $objectObject);
}
if (isset($_POST['vatNumber'])) {
    $objectObject = new stdClass();
    $objectObject->name = 'vatnumber';
    $objectObject->value = $_POST['vatNumber'];
    array_push($objectData, $objectObject);
}


if (isset($_POST['type_paint_dec'])) {
    $objectObject = new stdClass();
    $objectObject->name = 'type_paint_dec';
    $objectObject->value = 1;
    array_push($objectData, $objectObject);
}
if (isset($_POST['type_online'])) {
    $objectObject = new stdClass();
    $objectObject->name = 'type_online';
    $objectObject->value = 1;
    array_push($objectData, $objectObject);
}
if (isset($_POST['type_other'])) {
    $objectObject = new stdClass();
    $objectObject->name = 'type_other';
    $objectObject->value = 1;
    array_push($objectData, $objectObject);
}


$PlaObj->platxt = json_encode($objectData);


$FwdURL = '';
if (isset($_POST['fwdurl'])) $FwdURL = $_POST['fwdurl'];

if (isset($_POST['registerName'])) $PlaObj->comnam = $_POST['registerName'];

if (isset($_POST['title'])) $PlaObj->plattl = $_POST['title'];
if (isset($_POST['firstname'])) $PlaObj->plafna = $_POST['firstname'];
if (isset($_POST['surname'])) $PlaObj->plasna = $_POST['surname'];

if (isset($_POST['registerName'])) $PlaObj->planam = $PlaObj->plattl.' '.$PlaObj->plafna.' '.$PlaObj->plasna;
if (isset($_POST['registerEmail'])) $PlaObj->plaema = $_POST['registerEmail'];
if (isset($_POST['registerPassword'])) $PlaObj->paswrd = $_POST['registerPassword'];

if (isset($_POST['adr1'])) $PlaObj->adr1 = $_POST['adr1'];
if (isset($_POST['adr2'])) $PlaObj->adr2 = $_POST['adr2'];
if (isset($_POST['adr3'])) $PlaObj->adr3 = $_POST['adr3'];
if (isset($_POST['adr4'])) $PlaObj->adr4 = $_POST['adr4'];
if (isset($_POST['pstcod'])) $PlaObj->pstcod = $_POST['pstcod'];
if (isset($_POST['country'])) $PlaObj->coucod = $_POST['country'];

if (isset($_POST['reqcrd'])) $PlaObj->maxpri = $_POST['reqcrd'];

$data_arr = geocode($PlaObj->pstcod.' ,united kingdom');

if ($data_arr) {

    //$latitude = $data_arr[0];
    //$longitude = $data_arr[1];
    //$formatted_address = $data_arr[2];

    $PlaObj->goolat = $data_arr[0];
    $PlaObj->goolng = $data_arr[1];

}


// Create Customer Record
$Pla_ID = $PlaDao->update($PlaObj);

$PwdTok = $PlaDao->login($PlaObj->plaema, $PlaObj->paswrd, 'CUS', NULL);
$_SESSION['loginToken'] = $PwdTok;



// Create Delivery Address

$DelObj = new stdClass();
$DelObj->pla_id = 0;
$DelObj->tblnam = 'DELADR';
$DelObj->tbl_id = $Pla_ID;
$DelObj->comnam = $PlaObj->comnam;
$DelObj->planam = $PlaObj->plaadr;
$DelObj->adr1 = $PlaObj->adr1;
$DelObj->adr2 = $PlaObj->adr2;
$DelObj->adr3 = $PlaObj->adr3;
$DelObj->adr4 = $PlaObj->adr4;
$DelObj->pstcod = $PlaObj->pstcod;
$DelObj->coucod = $PlaObj->coucod;
$DelObj->ctynam = $PlaObj->ctycod;
$DelObj->goolat = $PlaObj->goolat;
$DelObj->goolng = $PlaObj->goolng;
$DelObj->plaema = $PlaObj->plaema;
$DelObj->platel = $PlaObj->platel;
$DelObj->plamob = $PlaObj->plamob;
$DelObj->plaref = $PlaObj->plaref;
$DelObj->usrnam = '';
$DelObj->paswrd = md5(date("Y-m-d H:i:s"));
$DelObj->sta_id = 0;
$DelObj->credat = date("Y-m-d H:i:s");
$DelObj->amndat = date("Y-m-d H:i:s");
$DelObj->plaimg = '';
$DelObj->minpri = 0;
$DelObj->maxpri = 0;
$DelObj->rooms = 0;
$DelObj->platyp = 0;
$DelObj->placol = '#f3f3f3';
$DelObj->plaurl = '';
$DelObj->platxt = '';
$DelObj->seourl = '';
$DelObj->keywrd = '';
$DelObj->keydsc = '';
$PlaDel_ID = $PlaDao->update($DelObj);



$throwJSON['id'] = $Pla_ID;
$throwJSON['title'] = 'Place Created';
$throwJSON['description'] = 'Place '.$PlaObj->planam.' created';
$throwJSON['type'] = 'success';


$FwdURL =  (isset($_POST['fwdurl'])) ? $_POST['fwdurl'] : 'useraccount/account';
header('location: '.$patchworks->webRoot.$FwdURL);
exit();

//header('location: ../../useraccount/account');
//
//die(json_encode($throwJSON));

?>