<?php

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("../system/classes/places.cls.php");
require_once("../gallery/classes/gallery.cls.php");
$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) die('#');

$TmpGal = new GalDAO();
$galleries = $TmpGal->select();


$tableLength = count($galleries);
for ($i=0;$i<$tableLength;++$i) {
    echo '<tr>';
        echo '<td>';
            echo $galleries[$i]['gal_id'];
        echo '</td>';
        echo '<td>';
            echo '<a href="gallery/gallery-edit.php?gal_id=' . $galleries[$i]['gal_id'] . '">';
                echo $galleries[$i]['gal_id'];
            echo '</a>';
        echo '</td>';

        echo '<td width="600">';
            echo '<a href="gallery/gallery-edit.php?gal_id=' . $galleries[$i]['gal_id'] . '">';
                echo $galleries[$i]['galnam'];
            echo '</a>';
        echo '</td>';

        echo '<td width="600">';
            echo '<a href="gallery/gallery-edit.php?gal_id=' . $galleries[$i]['gal_id'] . '">';
               echo str_replace(",",",",$galleries[$i]['imgsiz']);
            echo '</a>';
        echo '</td>';
    echo '</tr>';
}