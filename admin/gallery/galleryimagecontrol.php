<?php
require_once("../../config/config.php");
require_once("../patchworks.php");
require_once("../gallery/classes/uploads.cls.php");

$TblNam = (isset($_REQUEST['tblnam'])) ? $_REQUEST['tblnam'] : NULL;
$Tbl_ID = (isset($_REQUEST['tbl_id']) && is_numeric($_REQUEST['tbl_id'])) ? $_REQUEST['tbl_id'] : NULL;
$FilNam = (isset($_REQUEST['filnam'])) ? $_REQUEST['filnam'] : NULL;

$images = explode(',',$FilNam);


$TmpUpl = new UplDAO();
if (count($images) > 0 && !empty($FilNam)) {

    for ($i = 0; $i < count($images); $i++) {
        $uploadRec = $TmpUpl->select($images[$i], NULL, NULL, NULL, true);

        if ( isset($uploadRec) && !is_null($uploadRec->upl_id) ) {
            $qryArray = array();

            $qryArray["filnam"] = $uploadRec->filnam;
            $qryArray["uplttl"] = $uploadRec->uplttl;
            $qryArray["upldsc"] = $uploadRec->upldsc;

            $qryArray["credat"] = date("Y-m-d H:i:s");
            $qryArray["tblnam"] = $TblNam;
            $qryArray["tbl_id"] = $Tbl_ID;
            $qryArray["filsiz"] = 0;
            $qryArray["filtyp"] = '';
            $qryArray["srtord"] = 99;
            $qryArray["urllnk"] = '';

            $sql = 'INSERT INTO uploads
					(
					filnam,
					uplttl,
					upldsc,
					credat,
					tblnam,
					tbl_id,
					filsiz,
					filtyp,
					srtord,
					urllnk
					)
					VALUES
					(
					:filnam,
					:uplttl,
					:upldsc,
					:credat,
					:tblnam,
					:tbl_id,
					:filsiz,
					:filtyp,
					:srtord,
					:urllnk
					);';

            $recordSet = $patchworks->dbConn->prepare($sql);
            $recordSet->execute($qryArray);
        }
    }
}