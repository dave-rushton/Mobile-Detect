<?php

require_once("../../../config/config.php");
require_once("../../patchworks.php");

require_once("../../attributes/classes/attrvalues.cls.php");

$TmpAtv = new AtvDAO();

$Atr_ID = (isset($_GET['atr_id'])) ? $_GET['atr_id'] : NULL;
$TblNam = (isset($_GET['tblnam'])) ? $_GET['tblnam'] : NULL;
$Tbl_ID = (isset($_GET['tbl_id'])) ? $_GET['tbl_id'] : NULL;

$resultSet = NULL;
$resultSet = $TmpAtv->selectValueSet($Atr_ID, $TblNam, $Tbl_ID, NULL, NULL, false);

echo '<ul>';
    foreach ($resultSet as $row) {
        echo '<li><strong>' . $row['atllbl'] . '</strong>' . $row['atvval'] . '</li>';
    }
echo '</ul>';