<?php

$account = "3464"; // Insert your account name here
$password = "johfb42q"; // Insert your password here

$postCode = (isset($_GET['pstcod'])) ? $_GET['pstcod'] : NULL;

$throwJSON = array();
$URL = "http://ws1.postcodesoftware.co.uk/lookup.asmx/getAddress?account=" . $account . "&password=" . $password . "&postcode=" . $postCode;
$xml = simplexml_load_file(str_replace(' ','', $URL)); // Removes unnecessary spaces
$array = json_decode(json_encode((array)$xml), TRUE);

//var_dump($array);
//echo $array['Address1'].'<br>';
//echo $array['Address2'].'<br>';
//echo $array['Address3'].'<br>';
//echo $array['Address4'].'<br>';
//echo $array['Town'].'<br>';
//echo $array['County'].'<br>';
//echo $array['Postcode'].'<br>';

If ($xml->ErrorNumber <> 0) {

    $throwJSON['id'] = '0';
    $throwJSON['title'] = 'Error';
    $throwJSON['description'] = $xml->ErrorMessage;
    $throwJSON['type'] = 'error';

}
else {

    if ($xml->PremiseData <> "") {

        $chunks = explode (";", $xml->PremiseData); // Splits up premise data

        foreach ($chunks as $v) {
            if ($v <> "") {
                list($organisation, $building , $number) = explode ('|', $v); // Splits premises into organisation, building and number
            }
        }

    }
    else {
        $throwJSON['adr1'] = $array['Address1'];
    }

    If ($xml->Address2 <> "") {
        $throwJSON['adr2'] = $array['Address2'];
    }
    If ($xml->Address3 <> "") {
        $throwJSON['adr3'] = $array['Address3'];
    }
    If ($xml->Address4 <> "") {
        $throwJSON['adr4'] = $array['Address4'];
    }

    $throwJSON['town'] = $array['Town'];
    $throwJSON['county'] = $array['County'];
    $throwJSON['postcode'] = $array['Postcode'];

}

die(json_encode($throwJSON));

?>