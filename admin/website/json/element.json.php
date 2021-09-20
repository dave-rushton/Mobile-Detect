<?php

require_once("../../../config/config.php");
require_once("../../patchworks.php");
require_once("../../website/classes/pageelements.cls.php");
require_once("../../website/classes/page.handler.php");

//$patchworks = new pw();
$pageHandler = new pageHandler();



//$userAuth = new AuthDAO();
//$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
//
//if ($loggedIn == 0) header('location: ../login.php');

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;

$EleDao = new PelDAO();
 
if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'requesturl') {
	
	$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
	die($EleObj->incfil.'?'.$EleObj->incurl);
	
} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'select') {
	
	$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
	
	$jsonArray = array();
	
	if ($EleObj) {
		die($EleObj->elevar);
	}
	
	die(json_encode($jsonArray));

	
} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update') {
	
	$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
	
	if (!$EleObj) {
		
		$EleObj = new stdClass();
		$EleObj->pel_id = 0;
		
		$EleObj->pag_id = (isset($_REQUEST['pag_id']) && is_numeric($_REQUEST['pag_id'])) ? $_REQUEST['pag_id'] : $EleObj->pag_id;
		$EleObj->div_id = (isset($_REQUEST['div_id'])) ? $_REQUEST['div_id'] : $EleObj->div_id;
		$EleObj->srtord = (isset($_REQUEST['srtord']) && is_numeric($_REQUEST['srtord'])) ? $_REQUEST['srtord'] : $EleObj->srtord;
		$EleObj->eletyp = (isset($_REQUEST['eletyp'])) ? $_REQUEST['eletyp'] : $EleObj->eletyp;
		$EleObj->pgc_id = (isset($_REQUEST['pgc_id']) && is_numeric($_REQUEST['pgc_id'])) ? $_REQUEST['pgc_id'] : $EleObj->pgc_id;
		$EleObj->incfil = (isset($_REQUEST['incfil'])) ? $_REQUEST['incfil'] : $EleObj->incfil;
		$EleObj->incurl = (isset($_REQUEST['incurl'])) ? $_REQUEST['incurl'] : $EleObj->incurl;
		$EleObj->sta_id = (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) ? $_REQUEST['sta_id'] : $EleObj->sta_id;
		$EleObj->elevar = (isset($_REQUEST['elevar'])) ? $_REQUEST['elevar'] : '';
		
		$Pel_ID = $EleDao->update($EleObj);
		
	} else {
		
		$EleObj->pag_id = (isset($_REQUEST['pag_id']) && is_numeric($_REQUEST['pag_id'])) ? $_REQUEST['pag_id'] : $EleObj->pag_id;
		$EleObj->div_id = (isset($_REQUEST['div_id']) && is_numeric($_REQUEST['div_id'])) ? $_REQUEST['div_id'] : $EleObj->div_id;
		$EleObj->srtord = (isset($_REQUEST['srtord']) && is_numeric($_REQUEST['srtord'])) ? $_REQUEST['srtord'] : $EleObj->srtord;
		$EleObj->eletyp = (isset($_REQUEST['eletyp'])) ? $_REQUEST['eletyp'] : $EleObj->eletyp;
		$EleObj->pgc_id = (isset($_REQUEST['pgc_id']) && is_numeric($_REQUEST['pgc_id'])) ? $_REQUEST['pgc_id'] : $EleObj->pgc_id;
		$EleObj->incfil = (isset($_REQUEST['incfil'])) ? $_REQUEST['incfil'] : $EleObj->incfil;
		$EleObj->incurl = (isset($_REQUEST['incurl'])) ? $_REQUEST['incurl'] : $EleObj->incurl;
		$EleObj->sta_id = (isset($_REQUEST['sta_id']) && is_numeric($_REQUEST['sta_id'])) ? $_REQUEST['sta_id'] : $EleObj->sta_id;
		$EleObj->elevar = (isset($_REQUEST['elevar'])) ? $_REQUEST['elevar'] : $EleObj->elevar;
		
		$Pel_ID = $EleDao->update($EleObj);
	
	}

}
else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'clone') {

    $EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
    $EleObj->pel_id = 0;
    $EleObj->srtord = 999;
    $Pel_ID = $EleDao->update($EleObj);

}
else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'move') {

    $EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);



    $servername = $patchworks->host;
    $username = $patchworks->user;
    $password = $patchworks->password;
    $dbname = $patchworks->dbname;
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }




    //TODO HELP ME!!!
    //TODO WAS STRUGGLING WITH INHERITANCE
    if(!empty($_REQUEST['fwdurl'])){
        $fwdurl = $_REQUEST['fwdurl'];
        $fwdurl = htmlspecialchars($fwdurl);

        $pel_id = $_REQUEST['pel_id'];
        if($pel_id > 0){
            $sql = 'SELECT * FROM pages WHERE seourl = "'.$fwdurl.'" ORDER BY `id` ASC LIMIT 1';
            $result = $conn->query($sql);

            //PAGE ID
            if(empty($fwdurl) || $fwdurl==""){
                $sql = ' UPDATE pageelements SET pag_id="3" WHERE pel_id="'.$pel_id.'"';
            }
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $pag_id = $row["id"];
                    echo $pag_id;
                    $sql = ' UPDATE pageelements SET pag_id="'.$pag_id.'" WHERE pel_id="'.$pel_id.'"';
                }
            }


            if (mysqli_query($conn, $sql)) {}
            mysqli_close($conn);
        }
    }



    die();
    $EleObj->pag_id = 0;
    $Pel_ID = $EleDao->update($EleObj);

}

else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete') {
	
	$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
	if ($EleObj) $EleDao->delete($EleObj->pel_id);
	
} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'updateorder') {
	
	$Div_ID = (isset($_REQUEST['div_id'])) ? $_REQUEST['div_id'] : NULL;
	$EleStr = (isset($_REQUEST['elestr'])) ? $_REQUEST['elestr'] : NULL;
	
	//echo '#'.$Div_ID.'~'.$EleStr;
	
	if (!is_null($Div_ID) && !is_null($EleStr)) $EleDao->updateOrder($Div_ID, $EleStr);
	
}

die($Pel_ID);

?>