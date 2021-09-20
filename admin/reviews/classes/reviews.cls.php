<?php

//
// Revcategories class
//

class RevDAO extends db {

    function select($Rev_ID = NULL, $TblNam=NULL, $Tbl_ID = NULL, $ReqObj = false, $Sta_ID = NULL) {

        $qryArray = array();
        $sql = 'SELECT
				*
				FROM reviews WHERE true';

        if (!is_null($Rev_ID)) {
            $sql .= ' AND rev_id = :rev_id ';
            $qryArray["rev_id"] = $Rev_ID;
        } else {

            if (!is_null($TblNam)) {
                $sql .= ' AND tblnam = :tblnam ';
                $qryArray["tblnam"] = $TblNam;
            }
            if (!is_null($Tbl_ID) && is_numeric($Tbl_ID)) {
                $sql .= ' AND tbl_id = :tbl_id ';
                $qryArray["tbl_id"] = $Tbl_ID;
            }
            if (!is_null($Sta_ID) && is_numeric($Sta_ID)) {
                $sql .= ' AND sta_id = :sta_id ';
                $qryArray["sta_id"] = $Sta_ID;
            }

        }

        return $this->run($sql, $qryArray, $ReqObj);

    }

    function update($RevCls = NULL) {

        if (is_null($RevCls) || !$RevCls) return 'No Record To Update';

        $sql = '';

        $qryArray = array();

        if ($RevCls->rev_id == 0) {

            $qryArray["tblnam"] = $RevCls->tblnam;
            $qryArray["tbl_id"] = $RevCls->tbl_id;
            $qryArray["refnam"] = $RevCls->refnam;
            $qryArray["ref_id"] = $RevCls->ref_id;
            $qryArray["revttl"] = $RevCls->revttl;
            $qryArray["revdsc"] = $RevCls->revdsc;
            $qryArray["rating"] = $RevCls->rating;
            $qryArray["sta_id"] = $RevCls->sta_id;

            $sql = "INSERT INTO reviews
					(
					
					tblnam,
					tbl_id,
					refnam,
					ref_id,
					revttl,
					revdsc,
					rating,
					sta_id
					)
					VALUES
					(
					:tblnam,
					:tbl_id,
					:refnam,
					:ref_id,
					:revttl,
					:revdsc,
					:rating,
					:sta_id
					);";

        } else {

            $qryArray["tblnam"] = $RevCls->tblnam;
            $qryArray["tbl_id"] = $RevCls->tbl_id;
            $qryArray["refnam"] = $RevCls->refnam;
            $qryArray["ref_id"] = $RevCls->ref_id;
            $qryArray["revttl"] = $RevCls->revttl;
            $qryArray["revdsc"] = $RevCls->revdsc;
            $qryArray["rating"] = $RevCls->rating;
            $qryArray["sta_id"] = $RevCls->sta_id;

            $sql = "UPDATE reviews
					SET
					tblnam = :tblnam,
					tbl_id = :tbl_id,
					refnam = :refnam,
					ref_id = :ref_id,
					revttl = :revttl,
					revdsc = :revdsc,
					rating = :rating,
					sta_id = :sta_id";

            $sql .= " WHERE rev_id = :rev_id";
            $qryArray["rev_id"] = $RevCls->rev_id;

        }

        //echo $sql;
        //print_r($qryArray);

        $recordSet = $this->dbConn->prepare($sql);
        $recordSet->execute($qryArray);

        return ($RevCls->rev_id == 0) ? $this->dbConn->lastInsertId('rev_id') : $RevCls->rev_id;
    }

    function delete($Rev_ID = NULL) {

        try {

            if (!is_null($Rev_ID)) {
                $qryArray = array();
                $sql = 'DELETE FROM reviews WHERE rev_id = :rev_id ';
                $qryArray["rev_id"] = $Rev_ID;

                $recordSet = $this->dbConn->prepare($sql);
                $recordSet->execute($qryArray);

                return $Rev_ID;

            }

        } catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }

    }

}

?>