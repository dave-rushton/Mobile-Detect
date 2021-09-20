<?php

require('../../config/config.php');
require('../patchworks.php');
require('../website/classes/htaccess.cls.php');
require('../system/classes/simplexlsx.php');

error_reporting(E_ALL);
date_default_timezone_set('Europe/London');
ini_set('max_execution_time', 300);

$rowNum = 0;

$impOK = true;

$servername = $patchworks->host;
$username = $patchworks->user;
$password = $patchworks->password;
$dbname = $patchworks->dbname;
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$HtaDao = new HtaDAO();

if (isset($_FILES['file'])) {

    $xlsx = new SimpleXLSX( $_FILES['file']['tmp_name'] );
	
    $rowNum = 0;

    $productRecs = array();
	
	echo 'estimated rows: '.count( $xlsx->rows() ).'<br>';
	
    foreach( $xlsx->rows() as $r ) {

        $rowData = new stdClass();
        $rowData->frmurl = $r[0];
        $rowData->to_url = (isset($r[1])) ? $r[1] : '';
		
        $rowNum++;

        $HtaObj = new stdClass();
		$HtaObj->hta_id = 0;
		$HtaObj->frmurl = str_replace($patchworks->webRoot, '', $rowData->frmurl);
		$HtaObj->to_url = $rowData->to_url;
		$HtaObj->htaobj = '';
		$HtaObj->srtord = 9999;
		$Hta_ID = $HtaDao->update($HtaObj);

        var_dump($rowData);

    }

}


?>