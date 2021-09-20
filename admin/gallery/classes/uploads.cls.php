<?php

class UplDAO extends db {

    function select($Upl_ID = NULL, $TblNam=NULL, $Tbl_ID=NULL, $UplNam = NULL, $ReqObj = false) {

        $qryArray = array();
        $sql = 'SELECT
				upl_id,
				filnam,
				tblnam,
				tbl_id,
				uplttl,
				upldsc,
				alttxt,
				urllnk,
				srtord,
				uplobj
				FROM uploads WHERE true';

        if (!is_null($Upl_ID)) {
            $sql .= ' AND upl_id = :upl_id ';
            $qryArray["upl_id"] = $Upl_ID;
        } else {

            if (!is_null($TblNam)) {
                $sql .= ' AND tblnam = :tblnam ';
                $qryArray["tblnam"] = $TblNam;
            }

            if (!is_null($Tbl_ID) && is_numeric($Tbl_ID)) {
                $sql .= ' AND tbl_id LIKE :tbl_id ';
                $qryArray["tbl_id"] = $Tbl_ID;
            }

            if (!is_null($UplNam)) {
                $UplNam = '%'.$UplNam.'%';
                $sql .= ' AND filnam LIKE :filnam ';
                $qryArray["filnam"] = $UplNam;
            }
        }

        $sql .= ' ORDER BY srtord ASC ';

        return $this->run($sql, $qryArray, $ReqObj);
    }

    function selectByGalSeo($GalSeo = NULL, $ReqObj = false, $TblNam = NULL)
    {
        $qryArray = array();
        $sql = 'SELECT
				u.*,
				g.galnam
				FROM uploads u
				INNER JOIN gallery g ON g.gal_id = u.tbl_id
				WHERE g.seourl = :galseo ';

        $qryArray["galseo"] = $GalSeo;

        if (!is_null($TblNam)) {
            $sql .= ' AND u.tblnam = :tblnam ';
            $qryArray["tblnam"] = $TblNam;
        }

        $sql .= ' ORDER BY u.srtord ASC ';

        return $this->run($sql, $qryArray, $ReqObj);
    }


    function update($UplCls = NULL) {

        if (is_null($UplCls) || !$UplCls) {
            return 'No Record To Update';
        }

        $sql = '';

        $qryArray = array();

        $qryArray["filnam"] = $UplCls->filnam;
        $qryArray["tblnam"] = $UplCls->tblnam;
        $qryArray["tbl_id"] = $UplCls->tbl_id;
        $qryArray["upldsc"] = $UplCls->upldsc;
        $qryArray["alttxt"] = $UplCls->alttxt;
        $qryArray["urllnk"] = $UplCls->urllnk;
        $qryArray["uplttl"] = $UplCls->uplttl;
        $qryArray["srtord"] = $UplCls->srtord;
        $qryArray["uplobj"] = $UplCls->uplobj;

        if ($UplCls->upl_id == 0) {
            $sql = "INSERT INTO uploads
					(
					
					filnam,
					tblnam,
					tbl_id,
					upldsc,
					alttxt,
					urllnk,
					uplttl,
					srtord,
					uplobj
					)
					VALUES
					(
					:filnam,
					:tblnam,
					:tbl_id,
					:upldsc,
					:alttxt,
					:urllnk,
					:uplttl,
					:srtord,
					:uplobj
					);";

        } else {

            $sql = "UPDATE uploads
					SET
					
					filnam = :filnam,
					tblnam = :tblnam,
					tbl_id = :tbl_id,
					upldsc = :upldsc,
					alttxt = :alttxt,
					urllnk = :urllnk,
					uplttl = :uplttl,
					srtord = :srtord,
					uplobj = :uplobj";

            $sql .= " WHERE upl_id = :upl_id";
            $qryArray["upl_id"] = $UplCls->upl_id;
        }

        $recordSet = $this->dbConn->prepare($sql);
        $recordSet->execute($qryArray);

        return ($UplCls->upl_id == 0) ? $this->dbConn->lastInsertId('upl_id') : $UplCls->upl_id;
    }

    function delete($Upl_ID = NULL) {
        try {

            if (!is_null($Upl_ID)) {

                $qryArray = array();
                $sql = 'SELECT
						upl_id,
						filnam,
						tblnam,
						tbl_id,
						uplttl,
						upldsc,
						urllnk,
						srtord,
						uplobj
						FROM uploads WHERE upl_id = :upl_id ';

                $qryArray["upl_id"] = $Upl_ID;

                $TmpUpl = $this->run($sql, $qryArray, true);

                $qryArray = array();
                $sql = 'DELETE FROM uploads WHERE upl_id = :upl_id ';
                $qryArray["upl_id"] = $Upl_ID;

                $recordSet = $this->dbConn->prepare($sql);
                $recordSet->execute($qryArray);

            }

        } catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }

        return $Upl_ID;
    }

    function deleteFile( $searchPath = '.', $searchFile = '' ){

        $qryArray = array();
        $sql = 'DELETE FROM uploads WHERE filnam = :filnam ';
        $qryArray["filnam"] = $searchFile;

        $recordSet = $this->dbConn->prepare($sql);
        $recordSet->execute($qryArray);

        $ignoreArray = array( 'cgi-bin', '.', '..', '.svn' );
        $searchDir = @opendir( $searchPath );

        while( false !== ( $currentFile = readdir( $searchDir ) ) ){

            if( !in_array( $currentFile, $ignoreArray ) ){

                if( is_dir( $searchPath.'/'.$currentFile ) ){
                    $this->deleteFile( $searchPath.'/'.$currentFile, $searchFile );
                } else if ( $searchFile != '') {
                    if ($currentFile == $searchFile) unlink( $searchPath.'/'.$currentFile );
                }

            }

        }

        closedir( $searchDir );
    }
}