<?php

class MsgDAO extends db {

    function select($Msg_ID = NULL, $Atr_ID=NULL, $TblNam = NULL, $Tbl_ID=NULL, $Sta_ID=NULL, $ReqObj = false) {

        $qryArray = array();
        $sql = 'SELECT 
				msg_id,
				tblnam,
				tbl_id,
				msgttl,
				msgtxt,
				sta_id,
				atr_id
				FROM messages
				WHERE TRUE';

        if (!is_null($Msg_ID)) {
            $sql .= ' AND msg_id = :msg_id ';
            $qryArray["msg_id"] = $Msg_ID;
        } else {
            if (!is_null($Atr_ID) && is_numeric($Atr_ID)) {
                $sql .= ' AND atr_id = :atr_id ';
                $qryArray["atr_id"] = $Atr_ID;
            }
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

        $sql .= ' ORDER BY sta_id, credat DESC';

        //echo $sql;

        return $this->run($sql, $qryArray, $ReqObj);

    }

    function update($MsgCls = NULL) {

        if (is_null($MsgCls) || !$MsgCls) return 'No Record To Update';

        $sql = '';

        $qryArray = array();

        if ($MsgCls->msg_id == 0) {

            $qryArray["tblnam"] = $MsgCls->tblnam;
            $qryArray["tbl_id"] = $MsgCls->tbl_id;
            $qryArray["msgttl"] = $MsgCls->msgttl;
            $qryArray["msgtxt"] = $MsgCls->msgtxt;
            $qryArray["sta_id"] = $MsgCls->sta_id;
            $qryArray["atr_id"] = $MsgCls->atr_id;

            $sql = "INSERT INTO messages
					(
					tblnam,
					tbl_id,
					msgttl,
					sta_id,
					msgtxt,
					atr_id
					)
					VALUES
					(
					:tblnam,
					:tbl_id,
					:msgttl,
					:sta_id,
					:msgtxt,
					:atr_id
					);";

        } else {

            $qryArray["tblnam"] = $MsgCls->tblnam;
            $qryArray["tbl_id"] = $MsgCls->tbl_id;
            $qryArray["msgttl"] = $MsgCls->msgttl;
            $qryArray["msgtxt"] = $MsgCls->msgtxt;
            $qryArray["sta_id"] = $MsgCls->sta_id;
            $qryArray["atr_id"] = $MsgCls->atr_id;

            $sql = "UPDATE messages
					SET
					tblnam = :tblnam,
					tbl_id = :tbl_id,
					msgttl = :msgttl,
					sta_id = :sta_id,
					msgtxt = :msgtxt,
					atr_id = :atr_id
					WHERE msg_id = :msg_id";

            $qryArray["msg_id"] = $MsgCls->msg_id;

        }

        $recordSet = $this->dbConn->prepare($sql);
        $recordSet->execute($qryArray);

        return ($MsgCls->msg_id == 0) ? $this->dbConn->lastInsertId('msg_id') : $MsgCls->msg_id;
    }

    function delete($Msg_ID = NULL) {

        try {

            if (!is_null($Msg_ID)) {
                $qryArray = array();
                $sql = 'DELETE FROM messages WHERE msg_id = :msg_id ';
                $qryArray["msg_id"] = $Msg_ID;

                $recordSet = $this->dbConn->prepare($sql);
                $recordSet->execute($qryArray);

                return $Msg_ID;

            }

        } catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }

    }

}

?>