<?php

//
// Hsp Spots class
//

class HspDAO extends db {

    function select($Hsp_ID = NULL, $Hot_ID = NULL, $ReqObj = false) {

        $qryArray = array();
        $sql = 'SELECT
				*
				FROM hotspotsdetail WHERE true';

        if (!is_null($Hsp_ID)) {
            $sql .= ' AND hsp_id = :hsp_id ';
            $qryArray["hsp_id"] = $Hsp_ID;
        }

        return $this->run($sql, $qryArray, $ReqObj);

    }

    function update($HspCls = NULL) {

        if (is_null($HspCls) || !$HspCls) return 'No Record To Update';

        $sql = '';

        $qryArray = array();

        if ($HspCls->hsp_id == 0) {

            $qryArray["hot_id"] = $HspCls->hot_id;
            $qryArray["hottop"] = $HspCls->hottop;
            $qryArray["hotlft"] = $HspCls->hotlft;
            $qryArray["hspttl"] = $HspCls->hspttl;
            $qryArray["hsptxt"] = $HspCls->hsptxt;

            $sql = "INSERT INTO hotspotsdetail
					(
					
					hot_id,
					hottop,
					hotlft,
					hspttl,
					hsptxt
					)
					VALUES
					(
					:hot_id,
					:hottop,
					:hotlft,
					:hspttl,
					:hsptxt
					);";

        } else {

            $qryArray["hot_id"] = $HspCls->hot_id;
            $qryArray["hottop"] = $HspCls->hottop;
            $qryArray["hotlft"] = $HspCls->hotlft;
            $qryArray["hspttl"] = $HspCls->hspttl;
            $qryArray["hsptxt"] = $HspCls->hsptxt;

            $sql = "UPDATE hotspotsdetail
					SET
					hot_id = :hot_id,
					hottop = :hottop,
					hotlft = :hotlft,
					hspttl = :hspttl,
					hsptxt = :hsptxt";

            $sql .= " WHERE hsp_id = :hsp_id";
            $qryArray["hsp_id"] = $HspCls->hsp_id;

        }

        //echo $sql;
        //print_r($qryArray);

        $recordSet = $this->dbConn->prepare($sql);
        $recordSet->execute($qryArray);

        return ($HspCls->hsp_id == 0) ? $this->dbConn->lastInsertId('hsp_id') : $HspCls->hsp_id;
    }

    function delete($Hsp_ID = NULL) {

        try {

            if (!is_null($Hsp_ID)) {
                $qryArray = array();
                $sql = 'DELETE FROM hotspotsdetail WHERE hsp_id = :hsp_id ';
                $qryArray["hsp_id"] = $Hsp_ID;

                $recordSet = $this->dbConn->prepare($sql);
                $recordSet->execute($qryArray);

                return $Hsp_ID;

            }

        } catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }

    }

}

?>