<?php

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("../attributes/classes/attrgroups.cls.php");
require_once("../attributes/classes/attrlabels.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) die();

$TmpAtr = new AtrDAO();
$TmpAtl = new AtlDAO();

$editAttrGroup = (isset($_GET['atr_id']) && is_numeric($_GET['atr_id'])) ? $_GET['atr_id'] : NULL;
$TblNam = (isset($_GET['tblnam'])) ? $_GET['tblnam'] : NULL;
$Tbl_ID = (isset($_GET['tbl_id']) && is_numeric($_GET['tbl_id'])) ? $_GET['tbl_id'] : NULL;
$attrGroupRec = NULL;
$attrLabelRec = array();

if (!is_null($editAttrGroup) && !empty($editAttrGroup)) {

	$attrGroupRec = $TmpAtr->select($editAttrGroup, $TblNam, $Tbl_ID, NULL, true, NULL, NULL, NULL);

    if (isset($attrGroupRec->atr_id)) {
        $editAttrGroup = $attrGroupRec->atr_id;
        $attrLabelRec = $TmpAtl->select($editAttrGroup);
    }
}

$tableLength = count($attrLabelRec);
for ($i=0;$i<$tableLength;++$i) {
    echo '<tr>';
        echo '<td>';
            echo '<a href="#" class="btn btn-mini attrLabelSort" rel="tooltip" title="Drag To Reorder"><i class="icon icon-reorder"></i></a>';
        echo '</td>';
        echo '<td>';
        	echo '<a href="#" data-atl_id="' . $attrLabelRec[$i]['atl_id'] . '" data-atr_id="' . $attrLabelRec[$i]['atr_id'] . '" class="editAttrLabel"> ' . $attrLabelRec[$i]['atllbl'] . '</a>';
        echo '</td>';
        echo '<td>';
            echo $attrLabelRec[$i]['atltyp'];
        echo '</td>';
        echo '<td>';
            echo '<a href="#" data-atl_id="' . $attrLabelRec[$i]['atl_id'] . '" data-atr_id="' . $attrLabelRec[$i]['atr_id'] .
                '" class="btn btn-mini btn-danger deleteAttrLabelBtn" rel="tooltip" title="Delete">';
                echo '<i class="icon icon-trash"></i>';
            echo '</a>';
        echo '</td>';
    echo '</tr>';
}