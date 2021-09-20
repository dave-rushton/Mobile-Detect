<?php

//
// Attribute Groups class
//

class AtrDAO extends db {
	
	function select($Atr_ID = NULL, $TblNam=NULL, $Tbl_ID=NULL, $AtrNam = NULL, $ReqObj = false) { 
	
		$qryArray = array();
		$sql = 'SELECT
				atr_id,
				atrnam,
				tblnam,
				tbl_id,
				atrdsc,
				atrema,
				sta_id,
				fwdurl,
				btntxt,
				ema_to
				FROM attribute_group WHERE true';
		
		if (!is_null($Atr_ID)) {
			$sql .= ' AND atr_id = :atr_id ';
			$qryArray["atr_id"] = $Atr_ID;
		} else {
			
			if (!is_null($TblNam)) {
				$sql .= ' AND tblnam = :tblnam ';
				$qryArray["tblnam"] = $TblNam;
			}
			
			if (!is_null($Tbl_ID) && is_numeric($Tbl_ID)) {
				$sql .= ' AND tbl_id = :tbl_id ';
				$qryArray["tbl_id"] = $Tbl_ID;
			}
			
			if (!is_null($AtrNam)) {
				$AtrNam = '%'.$AtrNam.'%';
				$sql .= ' AND atrnam LIKE :atrnam ';
				$qryArray["atrnam"] = $AtrNam;
			}
		}

		return $this->run($sql, $qryArray, $ReqObj);

	}
	
	function update($AtrCls = NULL) {
	
		if (is_null($AtrCls) || !$AtrCls) return 'No Record To Update';
		
		$sql = '';
		
		$qryArray = array();
		
		if ($AtrCls->atr_id == 0) {
						
			$qryArray["atrnam"] = $AtrCls->atrnam;
			$qryArray["tblnam"] = $AtrCls->tblnam;
			$qryArray["tbl_id"] = $AtrCls->tbl_id;
			$qryArray["atrema"] = $AtrCls->atrema;
			$qryArray["atrdsc"] = $AtrCls->atrdsc;
			$qryArray["sta_id"] = $AtrCls->sta_id;
			$qryArray["fwdurl"] = $AtrCls->fwdurl;
			$qryArray["btntxt"] = $AtrCls->btntxt;
			
			$sql = "INSERT INTO attribute_group
					(
					
					atrnam,
					tblnam,
					tbl_id,
					atrema,
					atrdsc,
					sta_id,
					fwdurl,
					btntxt
					)
					VALUES
					(
					:atrnam,
					:tblnam,
					:tbl_id,
					:atrema,
					:atrdsc,
					:sta_id,
					:fwdurl,
					:btntxt
					);";
						
		} else {
			
			$qryArray["atrnam"] = $AtrCls->atrnam;
			$qryArray["tblnam"] = $AtrCls->tblnam;
			$qryArray["tbl_id"] = $AtrCls->tbl_id;
			$qryArray["atrema"] = $AtrCls->atrema;
			$qryArray["atrdsc"] = $AtrCls->atrdsc;
			$qryArray["sta_id"] = $AtrCls->sta_id;
			$qryArray["fwdurl"] = $AtrCls->fwdurl;
			$qryArray["btntxt"] = $AtrCls->btntxt;
			
			$sql = "UPDATE attribute_group
					SET
					
					atrnam = :atrnam,
					tblnam = :tblnam,
					tbl_id = :tbl_id,
					atrema = :atrema,
					atrdsc = :atrdsc,
					sta_id = :sta_id,
					fwdurl = :fwdurl,
					btntxt = :btntxt";
				
			$sql .= " WHERE atr_id = :atr_id";
			$qryArray["atr_id"] = $AtrCls->atr_id;
			
		}
		
		$recordSet = $this->dbConn->prepare($sql);
		$recordSet->execute($qryArray);
		
		return ($AtrCls->atr_id == 0) ? $this->dbConn->lastInsertId('atr_id') : $AtrCls->atr_id;
	}
	
	function delete($Atr_ID = NULL) {
	
		try {
			
			if (!is_null($Atr_ID)) {
				$qryArray = array();
				$sql = 'DELETE FROM attribute_group WHERE atr_id = :atr_id ';
				$qryArray["atr_id"] = $Atr_ID;
				
				$recordSet = $this->dbConn->prepare($sql);
				$recordSet->execute($qryArray);
				
				$qryArray = array();
				$sql = 'DELETE FROM attribute_label WHERE atr_id = :atr_id ';
				$qryArray["atr_id"] = $Atr_ID;
				
				$recordSet = $this->dbConn->prepare($sql);
				$recordSet->execute($qryArray);
				
				$qryArray = array();
				$sql = 'DELETE FROM attribute_value WHERE atr_id = :atr_id ';
				$qryArray["atr_id"] = $Atr_ID;
				
				$recordSet = $this->dbConn->prepare($sql);
				$recordSet->execute($qryArray);
				
				return $Atr_ID;
				
			}
			
		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}
		
	}
	
}

?>