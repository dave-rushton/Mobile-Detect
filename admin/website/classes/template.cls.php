<?php

//
// Page Templates class
//

class TplDAO extends db
{

    function select($Tpl_ID = NULL, $ReqObj = false)
    {

        $qryArray = array();
        $sql = 'SELECT 
				tpl_id,
				tplnam,
				tplfil,
				tpldef,
				tplobj
				FROM template
				WHERE TRUE
				';

        if (!is_null($Tpl_ID)) {
            $sql .= ' AND tpl_id = :tpl_id ';
            $qryArray["tpl_id"] = $Tpl_ID;
        }

        //echo $sql;

        return $this->run($sql, $qryArray, $ReqObj);

    }

    function selectDefault()
    {

        $qryArray = array();
        $sql = 'SELECT 
				tpl_id,
				tplnam,
				tplfil,
				tpldef,
				tplobj
				FROM template
				WHERE tpldef = 1';

        return $this->run($sql, $qryArray, true);

    }

    function update($TplCls = NULL)
    {

        $sql = '';

        $qryArray = array();

        if ($TplCls->tpl_id == 0) {

            $qryArray["tplnam"] = $TplCls->tplnam;
            $qryArray["tplfil"] = $TplCls->tplfil;
            $qryArray["tpldef"] = $TplCls->tpldef;
            $qryArray["tplobj"] = $TplCls->tplobj;

            $sql = "INSERT INTO template
					(
					tplnam,
					tplfil,
					tpldef,
					tplobj
					)
					VALUES
					(
					:tplnam,
					:tplfil,
					:tpldef,
					:tplobj
					);";

        } else {

            $qryArray["tplnam"] = $TplCls->tplnam;
            $qryArray["tplfil"] = $TplCls->tplfil;
            $qryArray["tpldef"] = $TplCls->tpldef;
            $qryArray["tplobj"] = $TplCls->tplobj;

            $sql = "UPDATE template
					SET
					tplnam = :tplnam,
					tplfil = :tplfil,
					tpldef = :tpldef,
					tplobj = :tplobj
					WHERE tpl_id = :tpl_id";

            $qryArray["tpl_id"] = $TplCls->tpl_id;

        }

        $recordSet = $this->dbConn->prepare($sql);

        $recordSet->execute($qryArray);

        return ($TplCls->tpl_id == 0) ? $this->dbConn->lastInsertId('tpl_id') : $TplCls->tpl_id;

    }

    function delete($Tpl_ID = NULL)
    {

        try {

            if (!is_null($Tpl_ID)) {

                $qryArray = array();
                $sql = 'DELETE FROM template WHERE tpl_id = :tpl_id ';
                $qryArray["tpl_id"] = $Tpl_ID;

                $recordSet = $this->dbConn->prepare($sql);
                $recordSet->execute($qryArray);

                return $Tpl_ID;

            }

        } catch (PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }

    }

}