<?php

//
// Product Types class
//

class DisDAO extends db {
	
	function select($Dis_ID = NULL, $Sub_ID=NULL, $Atr_ID=NULL, $Prt_ID=NULL, $Prd_ID=NULL, $PerPag=NULL, $Pag_No=NULL, $ReqObj=false) { 
		
		$OffSet=NULL;
		if (isset($PerPag) && isset($Pag_No) && is_numeric($PerPag) && is_numeric($Pag_No)) $OffSet = ($Pag_No-1) * $PerPag;
		
		$qryArray = array();
		$sql = 'SELECT
				*
				FROM discounts
				WHERE TRUE';
		
		if (!is_null($Dis_ID)) {
			$sql .= ' AND dis_id = :dis_id ';
			$qryArray["dis_id"] = $Dis_ID;
		} else {
			
			if (!is_null($Sub_ID)) {
				$sql .= ' AND sub_id = :sub_id ';
				$qryArray["sub_id"] = $Sub_ID;
			}
			
			if (!is_null($Atr_ID) && is_numeric($Atr_ID)) {
				$sql .= ' AND atr_id = :atr_id ';
				$qryArray["atr_id"] = $Atr_ID;
			}
			
			if (!is_null($Prt_ID)) {
				$sql .= ' AND prt_id = :prt_id ';
				$qryArray["prt_id"] = $Prt_ID;
			}
			if (!is_null($Prd_ID) && is_numeric($Prd_ID)) {
				$sql .= ' AND prd_id = :prd_id ';
				$qryArray["prd_id"] = $Prd_ID;
			}
			
			$sql .= ' GROUP BY disnam ';
			
			if (!is_null($OffSet) && is_numeric($OffSet) && !is_null($PerPag) && is_numeric($PerPag)) {
				$sql .= ' LIMIT '.$OffSet.' , '.$PerPag;
			} else {
				
			}
			
		}
		
		//echo $sql;
		
		return $this->run($sql, $qryArray, $ReqObj);

	}
	
	function selectByCode($DisCod = NULL) { 
				
		if (!is_null($DisCod)) {
			
			$qryArray = array();
			$sql = 'SELECT
					*
					FROM discounts
					WHERE discod = :discod';
			$qryArray["discod"] = $DisCod;
			
			return $this->run($sql, $qryArray, true);
			
		}
		
		return NULL;

	}

	
	function update($DisCls = NULL) {
	
		if (is_null($DisCls) || !$DisCls) return 'No Record To Update';
		
		$sql = '';
		
		$qryArray = array();
		
		if ($DisCls->dis_id == 0) {
			
			$qryArray["disnam"] = $DisCls->disnam;
			$qryArray["discod"] = $DisCls->discod;
			$qryArray["sub_id"] = $DisCls->sub_id;
			$qryArray["atr_id"] = $DisCls->atr_id;
			$qryArray["prt_id"] = $DisCls->prt_id;
			$qryArray["prd_id"] = $DisCls->prd_id;
			$qryArray["pctamt"] = $DisCls->pctamt;
			$qryArray["disamt"] = $DisCls->disamt;
			
			$qryArray["begdat"] = $DisCls->begdat;
			$qryArray["enddat"] = $DisCls->enddat;
			$qryArray["totuse"] = $DisCls->totuse;
			$qryArray["minamt"] = $DisCls->minamt;
			
			$sql = "INSERT INTO discounts
					(
					disnam,
					discod,
					sub_id,
					atr_id,
					prt_id,
					prd_id,
					pctamt,
					disamt,
					begdat,
					enddat,
					totuse,
					minamt
					)
					VALUES
					(
					:disnam,
					:discod,
					:sub_id,
					:atr_id,
					:prt_id,
					:prd_id,
					:pctamt,
					:disamt,
					:begdat,
					:enddat,
					:totuse,
					:minamt
					);";
						
		} else {
			
			$qryArray["disnam"] = $DisCls->disnam;
			$qryArray["discod"] = $DisCls->discod;
			$qryArray["sub_id"] = $DisCls->sub_id;
			$qryArray["atr_id"] = $DisCls->atr_id;
			$qryArray["prt_id"] = $DisCls->prt_id;
			$qryArray["prd_id"] = $DisCls->prd_id;
			$qryArray["pctamt"] = $DisCls->pctamt;
			$qryArray["disamt"] = $DisCls->disamt;
			
			$qryArray["begdat"] = $DisCls->begdat;
			$qryArray["enddat"] = $DisCls->enddat;
			$qryArray["totuse"] = $DisCls->totuse;
			$qryArray["minamt"] = $DisCls->minamt;
			
			$sql = "UPDATE discounts
					SET
					
					disnam = :disnam,
					discod = :discod,
					sub_id = :sub_id,
					atr_id = :atr_id,
					prt_id = :prt_id,
					prd_id = :prd_id,
					pctamt = :pctamt,
					disamt = :disamt,
					begdat = :begdat,
					enddat = :enddat,
					totuse = :totuse,
					minamt = :minamt
					";
				
			$sql .= " WHERE dis_id = :dis_id";
			$qryArray["dis_id"] = $DisCls->dis_id;
			
		}
		
		$recordSet = $this->dbConn->prepare($sql);
		$recordSet->execute($qryArray);
		
		return ($DisCls->dis_id == 0) ? $this->dbConn->lastInsertId('dis_id') : $DisCls->dis_id;
	}
	
	function delete($Dis_ID = NULL) {
	
		try {
			
			if (!is_null($Dis_ID)) {
				$qryArray = array();
				$sql = 'DELETE FROM discounts WHERE dis_id = :dis_id ';
				$qryArray["dis_id"] = $Dis_ID;
				
				$recordSet = $this->dbConn->prepare($sql);
				$recordSet->execute($qryArray);
				
				return $Dis_ID;
				
			}
			
		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}
		
	}
	
}

?>