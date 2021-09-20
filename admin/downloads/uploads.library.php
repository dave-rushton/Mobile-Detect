<?php
require_once("../../config/config.php");
require_once("../patchworks.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: login.php');

$qryArray = array();
$sql = 'SELECT
		* 
		FROM uploads WHERE tblnam = :tblnam AND tbl_id = :tbl_id ORDER BY srtord ASC';

$qryArray['tblnam'] = (isset($_GET['tblnam'])) ? $_GET['tblnam'] : '';
$qryArray['tbl_id'] = (isset($_GET['tbl_id']) && is_numeric($_GET['tbl_id'])) ? $_GET['tbl_id'] : 0;

//$patchworks->displayQuery($sql, $qryArray);
$uploads = $patchworks->run($sql, $qryArray, false);

$tableLength = count($uploads);
for ($i=0;$i<$tableLength;++$i) {

    echo '<tr>';
        echo '<td>';
            echo  $uploads[$i]['uplttl'];
        echo '</td>';

        echo '<td>';
            echo '<a href="' . $patchworks->webRoot . 'uploads/files/' . $uploads[$i]['filnam'] . '" target="_blank" class="btn btn-info">';
                echo '<i class="icon icon-eye-open"></i>';
            echo '</a>';

            echo '<a href="#" class="btn btn-default editUpload" data-upl_id="' . $uploads[$i]['upl_id'] . '">';
                echo '<i class="icon icon-pencil"></i>';
            echo '</a>';

            echo '<a href="#" class="btn btn-danger deleteUpload" data-upl_id="' . $uploads[$i]['upl_id'] . '">';
                echo '<i class="icon icon-trash"></i>';
            echo '</a>';
        echo '</td>';
    echo '</tr>';
}