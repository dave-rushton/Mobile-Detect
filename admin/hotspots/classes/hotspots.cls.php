<?php

//
// Hot Spots class
//

class HotDAO extends db {

    function select($Hot_ID = NULL, $TblNam=NULL, $Tbl_ID = NULL, $ReqObj = false) {

        $qryArray = array();
        $sql = 'SELECT
				*
				FROM hotspots WHERE true';

        if (!is_null($Hot_ID)) {
            $sql .= ' AND hot_id = :hot_id ';
            $qryArray["hot_id"] = $Hot_ID;
        }

        //echo $sql;
        //print_r($qryArray);

        return $this->run($sql, $qryArray, $ReqObj);

    }

    function update($HotCls = NULL) {

        if (is_null($HotCls) || !$HotCls) return 'No Record To Update';

        $sql = '';

        $qryArray = array();

        if ($HotCls->hot_id == 0) {

            $qryArray["tblnam"] = $HotCls->tblnam;
            $qryArray["tbl_id"] = $HotCls->tbl_id;
            $qryArray["hotnam"] = $HotCls->hotnam;
            $qryArray["hotimg"] = $HotCls->hotimg;

            $sql = "INSERT INTO hotspots
					(
					
					tblnam,
					tbl_id,
					hotnam,
					hotimg
					)
					VALUES
					(
					:tblnam,
					:tbl_id,
					:hotnam,
					:hotimg
					);";

        } else {

            $qryArray["tblnam"] = $HotCls->tblnam;
            $qryArray["tbl_id"] = $HotCls->tbl_id;
            $qryArray["hotnam"] = $HotCls->hotnam;
            $qryArray["hotimg"] = $HotCls->hotimg;

            $sql = "UPDATE hotspots
					SET
					tblnam = :tblnam,
					tbl_id = :tbl_id,
					hotnam = :hotnam,
					hotimg = :hotimg";

            $sql .= " WHERE hot_id = :hot_id";
            $qryArray["hot_id"] = $HotCls->hot_id;

        }

        //echo $sql;
        //print_r($qryArray);

        $recordSet = $this->dbConn->prepare($sql);
        $recordSet->execute($qryArray);

        return ($HotCls->hot_id == 0) ? $this->dbConn->lastInsertId('hot_id') : $HotCls->hot_id;
    }

    function delete($Hot_ID = NULL) {

        try {

            if (!is_null($Hot_ID)) {
                $qryArray = array();
                $sql = 'DELETE FROM hotspots WHERE hot_id = :hot_id ';
                $qryArray["hot_id"] = $Hot_ID;

                $recordSet = $this->dbConn->prepare($sql);
                $recordSet->execute($qryArray);


                $qryArray = array();
                $sql = 'DELETE FROM hotspotsdetail WHERE hot_id = :hot_id ';
                $qryArray["hot_id"] = $Hot_ID;

                $recordSet = $this->dbConn->prepare($sql);
                $recordSet->execute($qryArray);

                return $Hot_ID;

            }

        } catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }

    }

}

?>