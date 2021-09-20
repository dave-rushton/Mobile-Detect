<?php

class TmpDAO extends db {

    function select($Tmp_ID = NULL, $TblNam = NULL, $Tbl_ID = NULL, $ReqObj = false) {

        $qryArray = array();
        $sql = 'SELECT
				*
				FROM tempobject WHERE true';

        if (!is_null($Tmp_ID)) {
            $sql .= ' AND tmp_id = :tmp_id ';
            $qryArray["tmp_id"] = $Tmp_ID;
        } else {

            if (!is_null($TblNam)) {
                $sql .= ' AND tblnam = :tblnam ';
                $qryArray["tblnam"] = $TblNam;
            }

            if (is_numeric($Tbl_ID)) {
                $sql .= ' AND tbl_id = :tbl_id ';
                $qryArray["tbl_id"] = $Tbl_ID;
            }

        }

        $sql .= " ORDER BY credat DESC";

        //print_r($qryArray);
        //echo $sql;

        return $this->run($sql, $qryArray, $ReqObj);

    }


    function update($TmpCls = NULL) {

        if (is_null($TmpCls) || !$TmpCls) return 'No Record To Update';

        $sql = '';

        $qryArray = array();

        if ($TmpCls->tmp_id == 0) {

            $qryArray["tblnam"] = $TmpCls->tblnam;
            $qryArray["tbl_id"] = $TmpCls->tbl_id;
            $qryArray["tmpobj"] = $TmpCls->tmpobj;

            $sql = "INSERT INTO tempobject
					(
                    tblnam,
                    tbl_id,
                    tmpobj
					)
					VALUES
					(
                    :tblnam,
                    :tbl_id,
                    :tmpobj
					);";

        } else {

            $qryArray["tblnam"] = $TmpCls->tblnam;
            $qryArray["tbl_id"] = $TmpCls->tbl_id;
            $qryArray["tmpobj"] = $TmpCls->tmpobj;

            $sql = "UPDATE tempobject
					SET
				    tblnam = :tblnam,
                    tbl_id = :tbl_id,
                    tmpobj = :tmpobj";

            $sql .= " WHERE tmp_id = :tmp_id";
            $qryArray["tmp_id"] = $TmpCls->tmp_id;

        }

        //echo $sql;
        //print_r($qryArray);

        $recordSet = $this->dbConn->prepare($sql);
        $recordSet->execute($qryArray);

        $Tmp_ID = ($TmpCls->tmp_id == 0) ? $this->dbConn->lastInsertId('tmp_id') : $TmpCls->tmp_id;

        return $Tmp_ID;

    }

    function delete($Tmp_ID = NULL) {

        try {

            if (!is_null($Tmp_ID)) {
                $qryArray = array();
                $sql = 'DELETE FROM tempobject WHERE tmp_id = :tmp_id ';
                $qryArray["tmp_id"] = $Tmp_ID;

                $recordSet = $this->dbConn->prepare($sql);
                $recordSet->execute($qryArray);

                return $Tmp_ID;

            }

        } catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }

    }

}

?>