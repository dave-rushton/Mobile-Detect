<?php

//
// Multibuy Info class
//

class MulDAO extends db {
	
	function select($Mul_ID = NULL, $BegDat=NULL, $Sta_ID=NULL, $ReqObj=false) {

		$qryArray = array();
		$sql = 'SELECT
                mul_id,
                tblnam,
                tbl_id,
				multtl,
				multyp,
				prd_id,
				minbuy,
				minpri,
				begdat,
				enddat,
				sta_id,
				pctamt,
				disamt
				FROM multibuy WHERE TRUE';
		
		if (!is_null($Mul_ID)) {
            $sql .= ' AND mul_id = :mul_id ';
			$qryArray["mul_id"] = $Mul_ID;
		} else {
			
			if (!is_null($BegDat)) {
				$sql .= ' AND multtl <= :multtl ';
				$qryArray["multtl"] = $BegDat;
			}
			
			$sql .= ' ORDER BY multtl DESC';
			
			if (!is_null($BegDat)) {
				$sql .= ' LIMIT 1';	
			}
		}
		
		//echo $sql.' #'.$MulCod.'#';

		return $this->run($sql, $qryArray, $ReqObj);

	}
	
	function update($MulCls = NULL) {
	
		if (is_null($MulCls) || !$MulCls) return 'No Record To Update';
		
		$sql = '';
		
		$qryArray = array();
		
		if ($MulCls->mul_id == 0) {

			$qryArray["tblnam"] = $MulCls->tblnam;
			$qryArray["tbl_id"] = $MulCls->tbl_id;
			$qryArray["multtl"] = $MulCls->multtl;
			$qryArray["multyp"] = $MulCls->multyp;

            $qryArray["prd_id"] = $MulCls->prd_id;
            $qryArray["minbuy"] = $MulCls->minbuy;
            $qryArray["minpri"] = $MulCls->minpri;
            $qryArray["begdat"] = $MulCls->begdat;
            $qryArray["enddat"] = $MulCls->enddat;

            $qryArray["pctamt"] = $MulCls->pctamt;
            $qryArray["disamt"] = $MulCls->disamt;

			$sql = "INSERT INTO multibuy
					(
					tblnam,
					tbl_id,
					multtl,
					multyp,
                    prd_id,
                    minbuy,
                    minpri,
                    begdat,
                    enddat,
                    pctamt,
                    disamt
					)
					VALUES
					(
					:tblnam,
					:tbl_id,
					:multtl,
					:multyp,
                    :prd_id,
                    :minbuy,
                    :minpri,
                    :begdat,
                    :enddat,
                    :pctamt,
                    :disamt
					);";

					
						
		} else {

            $qryArray["tblnam"] = $MulCls->tblnam;
            $qryArray["tbl_id"] = $MulCls->tbl_id;
            $qryArray["multtl"] = $MulCls->multtl;
            $qryArray["multyp"] = $MulCls->multyp;

            $qryArray["prd_id"] = $MulCls->prd_id;
            $qryArray["minbuy"] = $MulCls->minbuy;
            $qryArray["minpri"] = $MulCls->minpri;
            $qryArray["begdat"] = $MulCls->begdat;
            $qryArray["enddat"] = $MulCls->enddat;

            $qryArray["pctamt"] = $MulCls->pctamt;
            $qryArray["disamt"] = $MulCls->disamt;
			
			$sql = "UPDATE multibuy
					SET
					
					tblnam = :tblnam,
					tbl_id = :tbl_id,
					multtl = :multtl,
					multyp = :multyp,
					prd_id = :prd_id,
					minbuy = :minbuy,
					minpri = :minpri,
					begdat = :begdat,
					enddat = :enddat,
					pctamt = :pctamt,
					disamt = :disamt
					";
				
			$sql .= " WHERE mul_id = :mul_id";
			$qryArray["mul_id"] = $MulCls->mul_id;
			
		}
		
		$recordSet = $this->dbConn->prepare($sql);
		$recordSet->execute($qryArray);
		
		return ($MulCls->mul_id == 0) ? $this->dbConn->lastInsertId('mul_id') : $MulCls->mul_id;
	}
	
	function delete($Mul_ID = NULL)
    {

        try {

            if (!is_null($Mul_ID)) {
                $qryArray = array();
                $sql = 'DELETE FROM multibuy WHERE mul_id = :mul_id ';
                $qryArray["mul_id"] = $Mul_ID;

                $recordSet = $this->dbConn->prepare($sql);
                $recordSet->execute($qryArray);

                return $Mul_ID;

            }

        } catch (PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }

    }

    function multiselectAvailable($Prd_ID = NULL, $NumItm = 0, $TotPri = NULL, $CurDat=NULL ) {

        //
        // Find multibuy
        //

        $qryArray = array();
        $sql = 'SELECT
                mul_id,
                tblnam,
                tbl_id,
				multtl,
				multyp,
				prd_id,
				minbuy,
				minpri,
				begdat,
				enddat,
				sta_id,
                pctamt,
                disamt
				FROM multibuy WHERE ';

        $sql .= ' minbuy <= :minbuy';
        $qryArray['minbuy'] = $NumItm;

        $sql .= ' AND minpri <= :minpri';
        $qryArray['minpri'] = $TotPri;

        if (!is_null($CurDat) && checkdate(date("m", strtotime($CurDat)), date("d", strtotime($CurDat)), date("Y", strtotime($CurDat)))) {
            $sql .= ' AND enddat >= :enddat ';
            $qryArray["enddat"] = $CurDat;
        }
        if (!is_null($CurDat) && checkdate(date("m", strtotime($CurDat)), date("d", strtotime($CurDat)), date("Y", strtotime($CurDat)))) {
            $sql .= ' AND begdat <= :begdat ';
            $qryArray["begdat"] = $CurDat;
        }

        //echo $sql;
        //var_dump($qryArray);

        return $this->run($sql, $qryArray, true);



    }
	
}

?>