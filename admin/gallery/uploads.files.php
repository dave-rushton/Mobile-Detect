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

$uploads = $patchworks->run($sql, $qryArray);

$tableLength = count($uploads);
for ($i = 0; $i < $tableLength; ++$i) {
    echo '<li class="selfclear">';

        echo '<a href="#" class="deleteUpload btn-danger btn btn-sm" data-upl_id="' . $uploads[$i]['upl_id'] . '">';
            echo '<i class="icon-trash"></i>';
        echo '</a>';

        echo '<a href="#" class="editUpload btn btn-sm" data-upl_id="' . $uploads[$i]['upl_id'] . '">';
            echo '<i class="icon-pencil"></i>';
        echo '</a>';

        echo '<a href="#" class="btn btn-sm" data-upl_id="' . $uploads[$i]['upl_id'] . '">';
            echo '<i class="icon-zoom-in"></i>';
        echo '</a>';

        echo $uploads[$i]['uplttl'];

    echo '</li>';
}
