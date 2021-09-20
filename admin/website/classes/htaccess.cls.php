<?php

//
// htAccess class
//

class HtaDAO extends db
{

    function select($Hta_ID = NULL, $ReqObj = false)
    {

        $qryArray = array();
        $sql = 'SELECT
				*
				FROM htaccess WHERE true';

        if (!is_null($Hta_ID)) {
            $sql .= ' AND hta_id = :hta_id ';
            $qryArray["hta_id"] = $Hta_ID;
        } else {

        }

        $sql .= " ORDER BY srtord";

        return $this->run($sql, $qryArray, $ReqObj);

    }

    function update($HtaCls = NULL)
    {

        if (is_null($HtaCls) || !$HtaCls) return 'No Record To Update';

        $sql = '';

        $qryArray = array();

        if ($HtaCls->hta_id == 0) {

            $qryArray["frmurl"] = $HtaCls->frmurl;
            $qryArray["to_url"] = $HtaCls->to_url;
            $qryArray["htaobj"] = $HtaCls->htaobj;

            $sql = "INSERT INTO htaccess
					(
					
					frmurl,
					to_url,
					htaobj
					)
					VALUES
					(
					:frmurl,
					:to_url,
					:htaobj
					);";

        } else {

            $qryArray["frmurl"] = $HtaCls->frmurl;
            $qryArray["to_url"] = $HtaCls->to_url;
            $qryArray["htaobj"] = $HtaCls->htaobj;

            $sql = "UPDATE htaccess
					SET
					
					frmurl = :frmurl,
					to_url = :to_url,
					htaobj = :htaobj";

            $sql .= " WHERE hta_id = :hta_id";
            $qryArray["hta_id"] = $HtaCls->hta_id;

        }

        //echo $sql;

        $recordSet = $this->dbConn->prepare($sql);
        $recordSet->execute($qryArray);

        return ($HtaCls->hta_id == 0) ? $this->dbConn->lastInsertId('hta_id') : $HtaCls->hta_id;
    }

    function delete($Hta_ID = NULL)
    {

        try {

            if (!is_null($Hta_ID)) {
                $qryArray = array();
                $sql = 'DELETE FROM htaccess WHERE hta_id = :hta_id ';
                $qryArray["hta_id"] = $Hta_ID;

                $recordSet = $this->dbConn->prepare($sql);
                $recordSet->execute($qryArray);

                return $Hta_ID;

            }

        } catch (PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }

    }

}