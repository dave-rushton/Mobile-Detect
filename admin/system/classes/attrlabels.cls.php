<?php

//
// Attribute Labels class
//

class AtlDAO extends db {
	
	function select($Atr_ID = NULL, $Atl_ID = NULL, $ReqObj = false) { 
	
		$qryArray = array();
		$sql = 'SELECT
				atl_id,
				atr_id,
				atllbl,
				atltyp,
				atllst,
				atlreq,
				atlspc,
				srtord,
				srcabl,
				srctyp
				FROM attribute_label WHERE true';
		
		if (!is_null($Atl_ID)) {
			$sql .= ' AND atl_id = :atl_id ';
			$qryArray["atl_id"] = $Atl_ID;
		} else {
			
			if (!is_null($Atr_ID)) {
				$sql .= ' AND atr_id = :atr_id ';
				$qryArray["atr_id"] = $Atr_ID;
			}
			
		}
		
		return $this->run($sql, $qryArray, $ReqObj);

	}
	
	function update($AtlCls = NULL) {
	
		if (is_null($AtlCls) || !$AtlCls) return 'No Record To Update';
		
		$sql = '';
		
		$qryArray = array();
		
		if ($AtlCls->atl_id == 0) {
						
			$qryArray["atr_id"] = $AtlCls->atr_id;
			$qryArray["atllbl"] = $AtlCls->atllbl;
			$qryArray["atltyp"] = $AtlCls->atltyp;
			$qryArray["atllst"] = $AtlCls->atllst;
			$qryArray["atlspc"] = $AtlCls->atlspc;
			$qryArray["atlreq"] = $AtlCls->atlreq;
			$qryArray["srtord"] = $AtlCls->srtord;
			$qryArray["srcabl"] = $AtlCls->srcabl;
			$qryArray["srctyp"] = $AtlCls->srctyp;
			
			$sql = "INSERT INTO attribute_label
					(
					
					atr_id,
					atllbl,
					atltyp,
					atllst,
					atlspc,
					atlreq,
					srtord,
					srcabl,
					srctyp
					)
					VALUES
					(
					:atr_id,
					:atllbl,
					:atltyp,
					:atllst,
					:atlspc,
					:atlreq,
					:srtord,
					:srcabl,
					:srctyp
					);";
						
		} else {
			
			$qryArray["atr_id"] = $AtlCls->atr_id;
			$qryArray["atllbl"] = $AtlCls->atllbl;
			$qryArray["atltyp"] = $AtlCls->atltyp;
			$qryArray["atllst"] = $AtlCls->atllst;
			$qryArray["atlspc"] = $AtlCls->atlspc;
			$qryArray["atlreq"] = $AtlCls->atlreq;
			$qryArray["srtord"] = $AtlCls->srtord;
			$qryArray["srcabl"] = $AtlCls->srcabl;
			$qryArray["srctyp"] = $AtlCls->srctyp;
			
			$sql = "UPDATE attribute_label
					SET
					atr_id = :atr_id,
					atllbl = :atllbl,
					atltyp = :atltyp,
					atllst = :atllst,
					atlspc = :atlspc,
					atlreq = :atlreq,
					srtord = :srtord,
					srcabl = :srcabl,
					srctyp = :srctyp";
				
			$sql .= " WHERE atl_id = :atl_id";
			$qryArray["atl_id"] = $AtlCls->atl_id;
			
		}
		
		$recordSet = $this->dbConn->prepare($sql);
		$recordSet->execute($qryArray);
		
		return ($AtlCls->atl_id == 0) ? $this->dbConn->lastInsertId('atl_id') : $AtlCls->atl_id;
	}
	
	function delete($Atl_ID = NULL) {
	
		try {
			
			if (!is_null($Atl_ID)) {
				$qryArray = array();
				$sql = 'DELETE FROM attribute_label WHERE atl_id = :atl_id ';
				$qryArray["atl_id"] = $Atl_ID;
				
				$recordSet = $this->dbConn->prepare($sql);
				$recordSet->execute($qryArray);
				
				$qryArray = array();
				$sql = 'DELETE FROM attribute_value WHERE atl_id = :atl_id ';
				$qryArray["atl_id"] = $Atl_ID;
				
				$recordSet = $this->dbConn->prepare($sql);
				$recordSet->execute($qryArray);
				
				return $Atl_ID;
				
			}
			
		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}
		
	}
	
}

?>