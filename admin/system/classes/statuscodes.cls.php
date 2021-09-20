<?php

class StaDAO extends db {
	
	function select($Sta_ID = NULL, $TblNam=NULL, $ReqObj = false) { 
	
		$qryArray = array();
		$sql = 'SELECT 
				sta_id,
				stanam,
				tblnam,
				staico
				FROM statuscodes';
		
		if (!is_null($Sta_ID) && is_numeric($Sta_ID)) {
			$sql .= ' WHERE sta_id = :sta_id ';
			$qryArray["sta_id"] = $Sta_ID;
		} else {
		
			if (!is_null($TblNam)) {
				$sql .= ' WHERE tblnam = :tblnam ';
				$qryArray["tblnam"] = $TblNam;
			}
		
		}
		
		$sql .= ' ORDER BY stanam DESC';
		
		return $this->run($sql, $qryArray, $ReqObj);

	}
	
	function update($StaCls = NULL) {
	
		if (is_null($StaCls) || !$StaCls) return 'No Record To Update';
		
		$sql = '';
		
		$qryArray = array();
		
		if ($StaCls->sta_id == 0) {
			
			$qryArray["stanam"] = $StaCls->stanam;
			$qryArray["tblnam"] = $StaCls->tblnam;
			
			$sql = "INSERT INTO statuscodes
					(
					stanam,
					tblnam
					)
					VALUES
					(
					:stanam,
					:tblnam
					);";
						
		} else {
			
			$qryArray["stanam"] = $StaCls->stanam;
			$qryArray["tblnam"] = $StaCls->tblnam;
			
			$sql = "UPDATE statuscodes
					SET
					stanam = :stanam,
					tblnam = :tblnam
					WHERE sta_id = :sta_id";
			
			$qryArray["sta_id"] = $StaCls->sta_id;
			
		}
		
		$recordSet = $this->dbConn->prepare($sql);
		$recordSet->execute($qryArray);
		
		return ($StaCls->sta_id == 0) ? $this->dbConn->lastInsertId('sta_id') : $StaCls->sta_id;
	}
	
	function delete($Sta_ID = NULL) {
	
		try {
			
			if (!is_null($Sta_ID)) {
				$qryArray = array();
				$sql = 'DELETE FROM statuscodes WHERE sta_id = :sta_id ';
				$qryArray["sta_id"] = $Sta_ID;
				
				$recordSet = $this->dbConn->prepare($sql);
				$recordSet->execute($qryArray);
				
				$qryArray = array();
				$sql = 'DELETE FROM statusflow WHERE frm_id = :sta_id OR to_id = :sta_id ';
				$qryArray["sta_id"] = $Sta_ID;
				
				$recordSet = $this->dbConn->prepare($sql);
				$recordSet->execute($qryArray);
				
				
				return $Sta_ID;
				
			}
			
		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}
		
	}
	
}

?>