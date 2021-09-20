<?php

//
// Page Elements class
//

class PelDAO extends db
{

    function select($Pel_ID = NULL, $Pag_ID = NULL, $Div_ID = NULL, $ReqObj = false)
    {

        $qryArray = array();
        $sql = 'SELECT 
				p.pel_id,
				p.pag_id,
				p.div_id,
				p.srtord,
				p.eletyp,
				p.pgc_id,
				p.incfil,
				p.incurl,
				p.sta_id,
				p.elevar
				FROM pageelements p
				WHERE TRUE
				';

        if (!is_null($Pel_ID)) {
            $sql .= ' AND pel_id = :pel_id ';
            $qryArray["pel_id"] = $Pel_ID;
        } else {
            if (!is_null($Div_ID)) {
                $sql .= ' AND pag_id = :pag_id
					  AND div_id = :div_id';
                $qryArray["pag_id"] = $Pag_ID;
                $qryArray["div_id"] = $Div_ID;
            } else {
                $sql .= ' AND pag_id = :pag_id';
                $qryArray["pag_id"] = $Pag_ID;
            }
        }

        $sql .= " ORDER BY p.srtord";

        //echo $sql;

        return $this->run($sql, $qryArray, $ReqObj);

    }

    function update($PelCls = NULL)
    {

        $sql = '';

        $qryArray = array();

        if ($PelCls->pel_id == 0) {

            $qryArray["pag_id"] = $PelCls->pag_id;
            $qryArray["div_id"] = $PelCls->div_id;
            $qryArray["srtord"] = $PelCls->srtord;
            $qryArray["eletyp"] = $PelCls->eletyp;
            $qryArray["pgc_id"] = $PelCls->pgc_id;
            $qryArray["incfil"] = $PelCls->incfil;
            $qryArray["incurl"] = $PelCls->incurl;
            $qryArray["sta_id"] = $PelCls->sta_id;
            $qryArray["elevar"] = $PelCls->elevar;

            $sql = "INSERT INTO pageelements
					(
					pag_id,
					div_id,
					srtord,
					eletyp,
					pgc_id,
					incfil,
					incurl,
					sta_id,
					elevar
					)
					VALUES
					(
					:pag_id,
					:div_id,
					:srtord,
					:eletyp,
					:pgc_id,
					:incfil,
					:incurl,
					:sta_id,
					:elevar
					);";

        } else {

            $qryArray["pag_id"] = $PelCls->pag_id;
            $qryArray["div_id"] = $PelCls->div_id;
            $qryArray["srtord"] = $PelCls->srtord;
            $qryArray["eletyp"] = $PelCls->eletyp;
            $qryArray["pgc_id"] = $PelCls->pgc_id;
            $qryArray["incfil"] = $PelCls->incfil;
            $qryArray["incurl"] = $PelCls->incurl;
            $qryArray["sta_id"] = $PelCls->sta_id;
            $qryArray["elevar"] = $PelCls->elevar;

            $sql = "UPDATE pageelements
					SET
					pag_id = :pag_id,
					div_id = :div_id,
					srtord = :srtord,
					eletyp = :eletyp,
					pgc_id = :pgc_id,
					incfil = :incfil,
					incurl = :incurl,
					sta_id = :sta_id,
					elevar = :elevar
					WHERE pel_id = :pel_id";

            $qryArray["pel_id"] = $PelCls->pel_id;

        }

        $recordSet = $this->dbConn->prepare($sql);

        $recordSet->execute($qryArray);

        return ($PelCls->pel_id == 0) ? $this->dbConn->lastInsertId('pel_id') : $PelCls->pel_id;

    }

    function updateOrder($Div_ID = NULL, $EleStr = NULL)
    {

        $sql = '';

        $qryArray = array();

        if (!is_null($Div_ID) && !is_null($EleStr)) {

            $sql = "UPDATE pageelements
						SET
						div_id = :div_id,
						srtord = :srtord
						WHERE pel_id = :pel_id";
//			echo $sql;	


            $EleArr = explode(",", $EleStr);

            for ($i = 0; $i < count($EleArr); $i++) {

                $qryArray["div_id"] = $Div_ID;
                $qryArray["srtord"] = $i;
                $qryArray["pel_id"] = $EleArr[$i];

                //echo print_r($qryArray);

                $recordSet = $this->dbConn->prepare($sql);
                $recordSet->execute($qryArray);

            }

            return true;

        }

        return false;

    }

    function delete($Pel_ID = NULL)
    {

        try {

            if (!is_null($Pel_ID)) {

                $qryArray = array();
                $sql = 'DELETE FROM pageelements WHERE pel_id = :pel_id ';
                $qryArray["pel_id"] = $Pel_ID;

                $recordSet = $this->dbConn->prepare($sql);
                $recordSet->execute($qryArray);

                return $Pel_ID;

            }

        } catch (PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }

    }

    function getVariable($PelCls = NULL, $VarNam = NULL, $strip = true)
    {

        if ($strip == true) {
            $eleVarArr = json_decode(stripslashes($PelCls->elevar), true);
        } else {
            $eleVarArr = json_decode($PelCls->elevar, true);
        }

        if (is_array($eleVarArr) && !is_null($VarNam)) {
            for ($i = 0; $i < count($eleVarArr); ++$i) {
                foreach ($eleVarArr[$i] as $key => $item) {

                    //echo '<br />Asking For: '.$VarNam.', Current Item = '.$item.'  has value : '.$eleVarArr[$i]['value'];

                    if ($item === $VarNam) {

                        //echo ' <--- '.$eleVarArr[$i]['value'];;

                        return $eleVarArr[$i]['value'];
                    }
                }
            }
        }

        return '';

    }

}