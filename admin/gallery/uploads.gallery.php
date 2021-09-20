<?php
require_once("../../config/config.php");
require_once("../patchworks.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: login.php');

$qryArray = array();
$sql = '';

if (isset($_GET['tblnam'])) {
    $sql = 'SELECT
		* 
		FROM uploads WHERE tblnam = :tblnam AND tbl_id = :tbl_id ORDER BY srtord ASC';
    $qryArray['tblnam'] = (isset($_GET['tblnam'])) ? $_GET['tblnam'] : '';
    $qryArray['tbl_id'] = (isset($_GET['tbl_id']) && is_numeric($_GET['tbl_id'])) ? $_GET['tbl_id'] : 0;
} else {
    $sql = 'SELECT * FROM uploads';
}
$uploads = $patchworks->run($sql, $qryArray);
?>
<?php
$tableLength = count($uploads);
for ($i = 0; $i < $tableLength; ++$i) {
    $filename = $patchworks->docRoot . 'uploads/images/169-130/' . $uploads[$i]['filnam'];
    
    echo '<li>';
        echo '<a href="#" style="width: 169px;">';
            echo '<img src="' . $patchworks->webRoot . 'uploads/images/169-130/' .   $uploads[$i]['filnam'] . '?u=' .   @filemtime($filename) . '" />';
        echo '</a>';

        echo '<div class="extras">';
            echo '<div class="extras-inner">';
                echo '<a href="' . $patchworks->webRoot . 'uploads/images/' . $uploads[$i]['filnam'] . '" class="colorbox-image" rel="group-1">';
                    echo '<i class="icon-search"></i>';
                echo '</a>';

                echo '<a href="#" class="editUpload" data-upl_id="' . $uploads[$i]['upl_id'] . '">';
                    echo '<i class="icon-pencil"></i>';
                echo '</a>';

                echo '<a href="#" class="deleteUpload" data-upl_id="' . $uploads[$i]['upl_id'] . '">';
                    echo '<i class="icon-trash"></i>';
                echo '</a>';

                echo '<a href="#" class="moveUpload" data-upl_id="' .   $uploads[$i]['upl_id'] . '  ">';
                    echo '<i class="icon-move"></i>';
                echo '</a>';

                echo '<a href="#" class="transfereUpload" data-upl_id="' .   $uploads[$i]['upl_id'] . '  ">';
                    echo '<i class="icon-share-alt"></i>';
                echo '</a>';

                echo '<a target="_blank" href="gallery/imagecropper.php?imgfil=' . $uploads[$i]['filnam'] . '" class="cropUpload" data-upl_id="' .   $uploads[$i]['upl_id'] . '  ">';
                echo '<i class="icon-external-link"></i>';
                echo '</a>';

            echo '</div>';
        echo '</div>';
    echo '</li>';
}