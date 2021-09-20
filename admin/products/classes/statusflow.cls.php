<?php

class FloDAO extends db {
	
	function select($Flo_ID = NULL, $Frm_ID=NULL, $To_ID = NULL, $ReqObj = false) { 
	
		$qryArray = array();
		$sql = 'SELECT 
				flo_id,
				frm_id,
				to_id,
				flonam,
				fc.stanam AS frmnam,
				fc.staico AS frmico,
				tc.stanam AS to_nam,
				tc.staico AS to_ico
				FROM statusflow sf
				LEFT OUTER JOIN statuscodes fc ON fc.sta_id = sf.frm_id
				LEFT OUTER JOIN statuscodes tc ON tc.sta_id = sf.to_id
				WHERE TRUE';
		
		if (!is_null($Flo_ID)) {
			$sql .= ' AND flo_id = :flo_id ';
			$qryArray["flo_id"] = $Flo_ID;
		} else {
			if (!is_null($Frm_ID) && is_numeric($Frm_ID)) {
				$sql .= ' AND frm_id = :frm_id ';
				$qryArray["frm_id"] = $Frm_ID;
			}
			if (!is_null($To_ID) && is_numeric($To_ID)) {
				$sql .= ' AND to_id = :to_id ';
				$qryArray["to_id"] = $To_ID;
			}
		}
		
		$sql .= ' ORDER BY flonam DESC';
		
		//echo $sql;
		
		return $this->run($sql, $qryArray, $ReqObj);

	}
	
	function update($FloCls = NULL) {
	
		if (is_null($FloCls) || !$FloCls) return 'No Record To Update';
		
		$sql = '';
		
		$qryArray = array();
		
		if ($FloCls->flo_id == 0) {
			
			$qryArray["frm_id"] = $FloCls->frm_id;
			$qryArray["to_id"] = $FloCls->to_id;
			$qryArray["flonam"] = $FloCls->flonam;
			
			$sql = "INSERT INTO statusflow
					(
					frm_id,
					to_id,
					flonam
					)
					VALUES
					(
					:frm_id,
					:to_id,
					:flonam
					);";
						
		} else {
			
			
			$qryArray["frm_id"] = $FloCls->frm_id;
			$qryArray["to_id"] = $FloCls->to_id;
			$qryArray["flonam"] = $FloCls->flonam;
			
			$sql = "UPDATE statusflow
					SET
					frm_id = :frm_id,
					to_id = :to_id,
					flonam = :flonam
					WHERE flo_id = :flo_id";
			
			$qryArray["flo_id"] = $FloCls->flo_id;
			
		}
		
		$recordSet = $this->dbConn->prepare($sql);
		$recordSet->execute($qryArray);
		
		return ($FloCls->flo_id == 0) ? $this->dbConn->lastInsertId('flo_id') : $FloCls->flo_id;
	}
	
	function removeFlow($Frm_ID = NULL, $To_ID=NULL) {
	
		try {
			
			if (!is_null($Frm_ID) && !is_null($To_ID)) {
				$qryArray = array();
				$sql = 'DELETE FROM statusflow WHERE frm_id = :frm_id AND to_id = :to_id ';
				$qryArray["frm_id"] = $Frm_ID;
				$qryArray["to_id"] = $To_ID;
				
				$recordSet = $this->dbConn->prepare($sql);
				$recordSet->execute($qryArray);
				
				return 0;
				
			}
			
		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}
		
	}
	
	function delete($Flo_ID = NULL) {
	
		try {
			
			if (!is_null($Flo_ID)) {
				$qryArray = array();
				$sql = 'DELETE FROM statusflow WHERE flo_id = :flo_id ';
				$qryArray["flo_id"] = $Flo_ID;
				
				$recordSet = $this->dbConn->prepare($sql);
				$recordSet->execute($qryArray);
				
				return $Flo_ID;
				
			}
			
		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}
		
	}
	
}

?>