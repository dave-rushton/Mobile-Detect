<?php

//
// Booking tasks class
//

class BtkDAO extends db {
	
	function select($Btk_ID = NULL, $TblNam=NULL, $Tbl_ID=NULL, $BtkNam=NULL, $ReqObj=false, $Sta_ID=NULL) { 
	
		$qryArray = array();
		$sql = 'SELECT
				t.btk_id,
				t.tblnam,
				t.tbl_id,
				t.btkttl,
				t.btkdsc,
				t.btkdur,
				t.sta_id,
				t.reftbl,
				t.ref_id,
				t.credat,
				t.duedat,
				t.impflg,
				pla.planam,
				ppl.pplnam
				FROM bookingstasks t
				LEFT OUTER JOIN places pla ON t.tbl_id = pla.pla_id 
				LEFT OUTER JOIN people ppl ON t.ref_id = ppl.ppl_id 
				WHERE TRUE';
		
		if (!is_null($Btk_ID)) {
			$sql .= ' AND t.btk_id = :btk_id ';
			$qryArray["btk_id"] = $Btk_ID;
		} else {
			if (!is_null($TblNam)) {
				$sql .= ' AND t.tblnam = :tblnam ';
				$qryArray["tblnam"] = $TblNam;
			}
			if (!is_null($Tbl_ID) && is_numeric($Tbl_ID)) {
				$sql .= ' AND t.tbl_id = :tbl_id ';
				$qryArray["tbl_id"] = $Tbl_ID;
			}
			if (!is_null($BtkNam)) {
				$BtkNam = '%'.$BtkNam.'%';
				$sql .= ' AND t.btkdsc LIKE :btkdsc ';
				$qryArray["btkdsc"] = $BtkNam;
			}
			
			
			if (!is_null($Sta_ID)) {
			
				if (is_numeric($Sta_ID)) {
					$sql .= " AND t.sta_id = :sta_id ";
				} else {
					$sql .= " AND find_in_set(cast(t.sta_id as char), :sta_id) ";
				}
			
				$qryArray["sta_id"] = $Sta_ID;
			}
			
		}
		
		$sql .= ' ORDER BY t.tbl_id';
		
		return $this->run($sql, $qryArray, $ReqObj);

	}
	
	function update($BtkCls = NULL) {
	
		if (is_null($BtkCls) || !$BtkCls) return 'No Record To Update';
		
		$sql = '';
		
		$qryArray = array();
		
		if ($BtkCls->btk_id == 0) {
						
			$qryArray["tblnam"] = $BtkCls->tblnam;
			$qryArray["tbl_id"] = $BtkCls->tbl_id;
			$qryArray["btkttl"] = $BtkCls->btkttl;
			$qryArray["btkdsc"] = $BtkCls->btkdsc;
			$qryArray["btkdur"] = $BtkCls->btkdur;
			$qryArray["sta_id"] = $BtkCls->sta_id;
			$qryArray["reftbl"] = $BtkCls->reftbl;
			$qryArray["ref_id"] = $BtkCls->ref_id;
			
			$sql = "INSERT INTO bookingstasks
					(
					
					tblnam,
					tbl_id,
					btkttl,
					btkdsc,
					btkdur,
					sta_id,
					reftbl,
					ref_id
					
					)
					VALUES
					(
					
					:tblnam,
					:tbl_id,
					:btkttl,
					:btkdsc,
					:btkdur,
					:sta_id,
					:reftbl,
					:ref_id
					
					);";
						
		} else {
			
			$qryArray["tblnam"] = $BtkCls->tblnam;
			$qryArray["tbl_id"] = $BtkCls->tbl_id;
			$qryArray["btkttl"] = $BtkCls->btkttl;
			$qryArray["btkdsc"] = $BtkCls->btkdsc;
			$qryArray["btkdur"] = $BtkCls->btkdur;
			$qryArray["sta_id"] = $BtkCls->sta_id;
			$qryArray["reftbl"] = $BtkCls->reftbl;
			$qryArray["ref_id"] = $BtkCls->ref_id;
			
			$sql = "UPDATE bookingstasks
					SET
					
					tblnam = :tblnam,
					tbl_id = :tbl_id,
					btkttl = :btkttl,
					btkdsc = :btkdsc,
					btkdur = :btkdur,
					sta_id = :sta_id,
					reftbl = :reftbl,
					ref_id = :ref_id";
				
			$sql .= " WHERE btk_id = :btk_id";
			$qryArray["btk_id"] = $BtkCls->btk_id;
			
		}
		
		$recordSet = $this->dbConn->prepare($sql);
		$recordSet->execute($qryArray);
		
		return ($BtkCls->btk_id == 0) ? $this->dbConn->lastInsertId('btk_id') : $BtkCls->btk_id;
	}
	
	function updateStatus($Btk_ID=NULL, $Sta_ID=NULL) {
		
		if (!is_null($Btk_ID) && is_numeric($Sta_ID)) {
			
			$sql = "UPDATE bookingstasks
					SET
					sta_id = :sta_id";
			
			$qryArray["sta_id"] = $Sta_ID;
			
			if (is_numeric($Btk_ID)) {
				$sql .= " WHERE btk_id = :btk_id ";
			} else {
				$sql .= " WHERE find_in_set(cast(btk_id as char), :btk_id) ";
			}
			
			$qryArray["btk_id"] = $Btk_ID;
			
			$recordSet = $this->dbConn->prepare($sql);
			$recordSet->execute($qryArray);
			
		}
	}
	
	function updateHighlight($Btk_ID=NULL, $ImpFlg=NULL) {
		
		if (!is_null($Btk_ID) && is_numeric($ImpFlg)) {
			
			$sql = "UPDATE bookingstasks
					SET
					impflg = :impflg";
			
			$qryArray["impflg"] = $ImpFlg;
			
			if (is_numeric($Btk_ID)) {
				$sql .= " WHERE btk_id = :btk_id ";
			} else {
				$sql .= " WHERE find_in_set(cast(btk_id as char), :btk_id) ";
			}
			
			$qryArray["btk_id"] = $Btk_ID;
			
			$recordSet = $this->dbConn->prepare($sql);
			$recordSet->execute($qryArray);
			
		}
	}
	
	function delete($Btk_ID = NULL) {
	
		try {
			
			if (!is_null($Btk_ID)) {
				$qryArray = array();
				
				if (is_numeric($Btk_ID)) {
					$sql .= "DELETE FROM bookingstasks WHERE btk_id = :btk_id ";
				} else {
					$sql .= "DELETE FROM bookingstasks WHERE find_in_set(cast(btk_id as char), :btk_id) ";
				}
				
				$qryArray["btk_id"] = $Btk_ID;
				
				$recordSet = $this->dbConn->prepare($sql);
				$recordSet->execute($qryArray);
				
				return 0;
				
			}
			
		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}
		
	}
	
}

?>