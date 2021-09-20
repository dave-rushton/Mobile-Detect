<?php

class SthDAO extends db {
	
	function select($Sth_ID = NULL, $TblNam=NULL, $Tbl_ID=NULL, $ReqObj = false) { 
	
		$qryArray = array();
		$sql = 'SELECT 
				sth_id,
				tblnam,
				tbl_id,
				refnam,
				ref_id,
				sthdat,
				flo_id,
				sthttl,
				sthtxt
				FROM statushistory WHERE true';
		
		if (!is_null($Sth_ID) && is_numeric($Sth_ID)) {
			$sql .= ' AND sth_id = :sth_id ';
			$qryArray["sth_id"] = $Sth_ID;
		} else {
		
			if (!is_null($TblNam) && is_numeric($TblNam)) {
				$sql .= ' AND tblnam = :tblnam ';
				$qryArray["tblnam"] = $TblNam;
			}
			
			if (!is_null($Tbl_ID) && is_numeric($Tbl_ID)) {
				$sql .= ' AND tbl_id = :tbl_id ';
				$qryArray["tbl_id"] = $Tbl_ID;
			}
		
		}
		
		$sql .= ' ORDER BY tblnam DESC';
		
		return $this->run($sql, $qryArray, $ReqObj);

	}
	
	function update($SthCls = NULL) {
	
		if (is_null($SthCls) || !$SthCls) return 'No Record To Update';
		
		$sql = '';
		
		$qryArray = array();
		
		if ($SthCls->sth_id == 0) {
			
			$qryArray["tblnam"] = $SthCls->tblnam;
			$qryArray["tbl_id"] = $SthCls->tbl_id;
			$qryArray["refnam"] = $SthCls->refnam;
			$qryArray["ref_id"] = $SthCls->ref_id;
			$qryArray["sthdat"] = $SthCls->sthdat;
			$qryArray["flo_id"] = $SthCls->flo_id;
			$qryArray["sthttl"] = $SthCls->sthttl;
			$qryArray["sthtxt"] = $SthCls->sthtxt;
			
			$sql = "INSERT INTO statushistory
					(
					tblnam,
					tbl_id,
					refnam,
					ref_id,
					sthdat,
					flo_id,
					sthttl,
					sthtxt
					)
					VALUES
					(
					:tblnam,
					:tbl_id,
					:refnam,
					:ref_id,
					:sthdat,
					:flo_id,
					:sthttl,
					:sthtxt
					);";
						
		} else {
			
			$qryArray["tblnam"] = $SthCls->tblnam;
			$qryArray["tbl_id"] = $SthCls->tbl_id;
			$qryArray["refnam"] = $SthCls->refnam;
			$qryArray["ref_id"] = $SthCls->ref_id;
			$qryArray["sthdat"] = $SthCls->sthdat;
			$qryArray["flo_id"] = $SthCls->flo_id;
			$qryArray["sthttl"] = $SthCls->sthttl;
			$qryArray["sthtxt"] = $SthCls->sthtxt;
			
			$sql = "UPDATE statushistory
					SET
					tblnam = :tblnam,
					tbl_id = :tbl_id,
					refnam = :refnam,
					ref_id = :ref_id,
					sthnam = :sthdat,
					flo_id = :flo_id,
					sthttl = :sthttl,
					sthtxt = :sthtxt
					WHERE sth_id = :sth_id";
			
			$qryArray["sth_id"] = $SthCls->sth_id;
			
		}
		
		$recordSet = $this->dbConn->prepare($sql);
		$recordSet->execute($qryArray);
		
		return ($SthCls->sth_id == 0) ? $this->dbConn->lastInsertId('sth_id') : $SthCls->sth_id;
	}
	
	function delete($Sth_ID = NULL) {
	
		try {
			
			if (!is_null($Sth_ID)) {
				$qryArray = array();
				$sql = 'DELETE FROM statushistory WHERE sth_id = :sth_id ';
				$qryArray["sth_id"] = $Sth_ID;
				
				$recordSet = $this->dbConn->prepare($sql);
				$recordSet->execute($qryArray);
				
				return $Sth_ID;
				
			}
			
		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}
		
	}
	
}

?>