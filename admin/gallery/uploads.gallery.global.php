<?php
require_once("../../config/config.php");
require_once("../patchworks.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: login.php');

$qryArray = array();
$sql = 'SELECT * FROM uploads WHERE TRUE ';

if (isset($_GET['tblnam'])) {

    $sql .= ' AND tblnam = :tblnam AND tbl_id = :tbl_id ';
    $qryArray['tblnam'] = (isset($_GET['tblnam'])) ? $_GET['tblnam'] : '';
    $qryArray['tbl_id'] = (isset($_GET['tbl_id']) && is_numeric($_GET['tbl_id'])) ? $_GET['tbl_id'] : 0;

}

if (isset($_GET['keywrd'])) {
    $sql .= ' AND (uplttl LIKE :kw1) ';
    $qryArray['kw1'] = '%'.$_GET['keywrd'].'%';
}

$sql .= ' ORDER BY srtord ASC, upl_id ASC';

//echo $sql;
//var_dump($qryArray);

$uploads = $patchworks->run($sql, $qryArray);

?>
<?php
$tableLength = count($uploads);
for ($i=0;$i<$tableLength;++$i) {

    echo '<li>';

        echo '<a href="#" class="imageselect" data-imgnam="' . $uploads[$i]['filnam'] . '" data-upl_id="' . $uploads[$i]['upl_id'] . '" style="width: 169px;">';
            echo '<img src="' . $patchworks->webRoot . 'uploads/images/169-130/' . $uploads[$i]['filnam'] . '" />';
        echo '</a>';

        echo '<div class="extras">';
            echo '<div class="extras-inner">';

                echo '<a href="#" class="selectUpload" data-upl_id="' . $uploads[$i]['upl_id'] . '">';
                    echo '<i class="icon-check"></i>';
                echo '</a>';

                echo '<a href="' . $patchworks->webRoot . 'uploads/images/' . $uploads[$i]['filnam'] . '" class="colorbox-image" rel="group-1">';
                    echo '<i class="icon-search"></i>';
                echo '</a>';

                 echo '<a href="#" class="deleteUpload masterimage" data-upl_id="' . $uploads[$i]['upl_id'] . '">';
                    echo '<i class="icon-trash"></i>';
                echo '</a>';

            echo '</div>';
        echo '</div>';
    echo '</li>';
}
