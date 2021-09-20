<?php

//
// Page Elements class
//

class PgcDAO extends db
{

    function select($Pgc_ID = NULL, $Pag_ID = NULL, $Div_ID = NULL, $ReqObj = false)
    {

        $qryArray = array();
        $sql = 'SELECT 
				p.pgc_id,
				p.pgcttl,
				p.pgctxt,
				p.sta_id,
				p.tblnam,
				p.tbl_id,
				p.pgcobj,
				p.srtord
				FROM pagecontent p ';

        if (!is_null($Pag_ID)) {
            $sql .= ' INNER JOIN pageelements e ON e.pgc_id = p.pgc_id ';
        }

        $sql .= ' WHERE TRUE ';

        if (!is_null($Pgc_ID)) {
            $sql .= ' AND p.pgc_id = :pgc_id ';
            $qryArray["pgc_id"] = $Pgc_ID;
        }

        if (!is_null($Pag_ID)) {
            $sql .= ' AND e.pag_id = :pag_id ';
            $qryArray["pag_id"] = $Pag_ID;
        }

        $sql .= ' ORDER BY srtord';

        //echo $sql;

        return $this->run($sql, $qryArray, $ReqObj);

    }

    function selectGeneric($Pgc_ID = NULL, $ReqObj = false)
    {

        $qryArray = array();
        $sql = 'SELECT 
				p.pgc_id,
				p.pgcttl,
				p.pgctxt,
				p.sta_id,
				p.tblnam,
				p.tbl_id,
				p.pgcobj,
				p.srtord
				FROM pagecontent p 
				WHERE p.sta_id = 10
				';

        if (!is_null($Pgc_ID)) {
            $sql .= ' AND p.pgc_id = :pgc_id ';
            $qryArray["pgc_id"] = $Pgc_ID;
        }

        $sql .= ' ORDER BY srtord';

        return $this->run($sql, $qryArray, $ReqObj);

    }

    function selectGenericByTable($TblNam = NULL, $Tbl_ID = NULL, $Sta_ID = NULL, $ReqObj = false)
    {

        $qryArray = array();
        $sql = 'SELECT
				p.pgc_id,
				p.pgcttl,
				p.pgctxt,
				p.sta_id,
				p.tblnam,
				p.tbl_id,
				p.pgcobj,
				p.srtord
				FROM pagecontent p
				WHERE TRUE
				';

        if (!is_null($TblNam)) {
            $sql .= ' AND p.tblnam = :tblnam ';
            $qryArray["tblnam"] = $TblNam;
        }
        if (is_numeric($Tbl_ID)) {
            $sql .= ' AND p.tbl_id = :tbl_id ';
            $qryArray["tbl_id"] = $Tbl_ID;
        }
        if (is_numeric($Sta_ID)) {
            $sql .= ' AND p.sta_id = :sta_id ';
            $qryArray["sta_id"] = $Sta_ID;
        }

        $sql .= ' ORDER BY srtord';

        return $this->run($sql, $qryArray, $ReqObj);

    }

    function update($PgcCls = NULL)
    {

        $sql = '';

        $qryArray = array();

        if ($PgcCls->pgc_id == 0) {

            $qryArray["pgcttl"] = $PgcCls->pgcttl;
            $qryArray["pgctxt"] = $PgcCls->pgctxt;
            $qryArray["sta_id"] = $PgcCls->sta_id;

            $qryArray["tblnam"] = $PgcCls->tblnam;
            $qryArray["tbl_id"] = $PgcCls->tbl_id;

            $qryArray["pgcobj"] = $PgcCls->pgcobj;

            $sql = "INSERT INTO pagecontent
					(
					pgcttl,
					pgctxt,
					sta_id,
					tblnam,
					tbl_id,
					pgcobj
					)
					VALUES
					(
					:pgcttl,
					:pgctxt,
					:sta_id,
					:tblnam,
					:tbl_id,
					:pgcobj
					);";

        } else {

            $qryArray["pgcttl"] = $PgcCls->pgcttl;
            $qryArray["pgctxt"] = $PgcCls->pgctxt;
            $qryArray["sta_id"] = $PgcCls->sta_id;

            $qryArray["tblnam"] = $PgcCls->tblnam;
            $qryArray["tbl_id"] = $PgcCls->tbl_id;

            $qryArray["pgcobj"] = $PgcCls->pgcobj;

            $sql = "UPDATE pagecontent
					SET
					pgcttl = :pgcttl,
					pgctxt = :pgctxt,
					sta_id = :sta_id,
					tblnam = :tblnam,
					tbl_id = :tbl_id,
					pgcobj = :pgcobj
					WHERE pgc_id = :pgc_id";

            $qryArray["pgc_id"] = $PgcCls->pgc_id;

        }

        //echo $sql;

        $recordSet = $this->dbConn->prepare($sql);

        $recordSet->execute($qryArray);

        return ($PgcCls->pgc_id == 0) ? $this->dbConn->lastInsertId('pgc_id') : $PgcCls->pgc_id;

    }

    function delete($Pgc_ID = NULL)
    {

        try {

            if (!is_null($Pgc_ID)) {

                $qryArray = array();
                $sql = 'DELETE FROM pagecontent WHERE pgc_id = :pgc_id ';
                $qryArray["pgc_id"] = $Pgc_ID;

                $recordSet = $this->dbConn->prepare($sql);
                $recordSet->execute($qryArray);

                $qryArray = array();
                $sql = 'DELETE FROM pageelements WHERE pgc_id = :pgc_id ';
                $qryArray["pgc_id"] = $Pgc_ID;

                $recordSet = $this->dbConn->prepare($sql);
                $recordSet->execute($qryArray);

                return $Pgc_ID;

            }

        } catch (PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }

    }

}