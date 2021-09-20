<?php

//
// Attribute Values class
//

class AtvDAO extends db {
	
	function select($TblNam=NULL, $Tbl_ID = NULL, $ReqObj = false) { 
	
		$qryArray = array();
		$sql = 'SELECT 
				a.atrnam,
				l.atllbl,
				l.atldsc,
				l.srtord,
				v.atr_id,
				v.atv_id,
				v.atl_id,
				v.atvval,
				v.atvlst,
				v.tblnam,
				v.tbl_id,
				FROM attribute_value v
				INNER JOIN attribute_group a ON a.atr_id = v.atr_id 
				INNER JOIN attribute_label l ON l.atl_id = v.atl_id 
				WHERE TRUE ';
		
		if (!is_null($TblNam) && is_numeric($Tbl_ID)) {
			
			$sql .= ' AND v.tblnam = :tblnam AND v.tbl_id = :tbl_id ';
			$qryArray["tblnam"] = $TblNam;
			$qryArray["tbl_id"] = $Tbl_ID;
		}
		
		$sql .= " ORDER BY l.srtord";
		
		$res = $this->run($sql, $qryArray, $ReqObj);

		return $res;

	}
	
	function selectResultSet($Atr_ID=NULL, $TblNam=NULL, $Tbl_ID = NULL, $ReqObj = false) { 
	
		$qryArray = array();
		$sql = 'SELECT 
				DISTINCT(v.tbl_id) AS Ref_ID,
				a.atrnam,
				v.atr_id,
				v.tblnam
				FROM attribute_value v
				INNER JOIN attribute_group a ON a.atr_id = v.atr_id 
				WHERE TRUE ';
		
		// INNER JOIN attribute_label l ON l.atl_id = v.atl_id 
		
		if (!is_null($Atr_ID) && is_numeric($Atr_ID)) {
			
			$sql .= ' AND v.atr_id = :atr_id ';
			$qryArray["atr_id"] = $Atr_ID;
		}
		
		if (!is_null($TblNam)) {
			
			$sql .= ' AND v.tblnam = :tblnam ';
			$qryArray["tblnam"] = $TblNam;
			
		}
		if (!is_null($Tbl_ID) && is_numeric($Tbl_ID)) {
			
			$sql .= ' AND v.tbl_id = :tbl_id ';
			$qryArray["tbl_id"] = $Tbl_ID;
		}
		
		$sql .= ' ORDER BY v.atv_id DESC ';
		
		$res = $this->run($sql, $qryArray, $ReqObj);

		return $res;

	}
	
	function searchAttributeValues ( $Atr_ID=NULL, $AtrArr=NULL, $AtrVal=NULL, $TblNam=NULL, $Tbl_ID=NULL ) {
		
		if ( isset($AtrArr) && is_array($AtrArr) ) {
		
			$avLen = sizeof($AtrArr);
			
			$joinSql = '';
			
			for ($av = 0; $av < $avLen; $av++) {
				
				if ( $AtrVal[$av] != '' ) {
					
					$AtrValStr = '';
					
					$AtrValArr = explode(",",$AtrVal[$av]);
					
					for ($j=0; $j < count($AtrValArr);$j++) {
						$AtrValStr .= ($AtrValStr != '') ? "','".$AtrValArr[$j] : $AtrValArr[$j];						
					}
					$AtrValStr = "'".$AtrValStr."'";

					//echo $AtrValStr.' '.count($AtrValArr).'#<br>';
					
					if ($joinSql == '') {
						$joinSql .= " (atv.atl_id = ".$AtrArr[$av]." AND atv.atvval IN (".$AtrValStr."))";
					} else {
						$joinSql .= " OR (atv.atl_id = ".$AtrArr[$av]." AND atv.atvval IN (". $AtrValStr."))";
					}
					
				}
			
			}
			
			if ($joinSql != '') {
				$sql = "SELECT COUNT(DISTINCT atv.atv_id) AS atv_eq, atr.tblnam, atr.tbl_id, atv.tblnam as RefNam, atv.tbl_id as Ref_ID ";
				$sql .= " FROM attribute_group atr ";
				$sql .= " RIGHT JOIN attribute_value atv ";
				$sql .= "ON (".$joinSql.")";
				$sql .= " WHERE atr.atr_id = ".$Atr_ID;
				$sql .= " GROUP BY atv.tblnam, atv.tbl_id ";
				$sql .= " ORDER BY atv_eq DESC";
			} else {
				$sql = "SELECT DISTINCT atv.tbl_id AS Ref_ID FROM attribute_value atv WHERE atv.atr_id = ".$Atr_ID." AND atv.tblnam = '".$TblNam."'";
			}
			
			//echo '<pre>'.$sql.'</pre>';
			
			return  $this->dbConn->query($sql);
			
		} else {
			$sql = "SELECT DISTINCT atv.tbl_id AS Ref_ID FROM attribute_value atv WHERE atv.atr_id = ".$Atr_ID." AND atv.tblnam = '".$TblNam."'";
		}
		

	}
	
	function clear($TblNam=NULL, $Tbl_ID = NULL) {
		
		if (!is_null($TblNam) && !is_null($Tbl_ID)) {

			$qryArray = array();
			$sql = 'DELETE FROM attribute_value WHERE tblnam = "'.addslashes($TblNam).'" AND tbl_id = "'.addslashes($Tbl_ID).'"';
			
			$qryArray["tblnam"] = $TblNam;
			$qryArray["tbl_id"] = $Tbl_ID;
			
			$this->dbConn->query($sql);

			
			return true;
		}
		return false;
	}
	
	// Single Item Selection
	function selectValueSet($Atr_ID=NULL, $TblNam=NULL, $Tbl_ID = NULL, $LnkTbl=NULL, $LnkFld=NULL, $ReqObj = false) { 
		
		//echo $Atr_ID;
		
		if (!is_null($TblNam) && !is_null($Tbl_ID)) {

			$qryArray = array();
			$sql = 'SELECT 
					a.atrnam,
					l.atllbl,
					l.atldsc,
					l.srtord,
					l.atr_id,
					l.atltyp,
					l.atllst,
					l.atlreq,
					l.srcabl,
					l.srctyp,
					v.atv_id,
					l.atl_id,
					v.atvval,
					v.tblnam,
					v.tbl_id ';
			if (!is_null($LnkTbl) && !is_null($LnkFld)) {
				$sql .=	' , lt.* ';
					
			}
			
			$sql .=	' FROM attribute_label l
					INNER JOIN attribute_group a ON a.atr_id = l.atr_id 
					LEFT OUTER JOIN attribute_value v ON l.atl_id = v.atl_id AND v.tblnam = :tblnam AND v.tbl_id = :tbl_id ';
			
			if (!is_null($LnkTbl) && !is_null($LnkFld)) {
				$sql .= ' LEFT OUTER JOIN '.$LnkTbl.' lt ON lt.'.$LnkFld.' = v.tbl_id ';	
			}
			
			$sql .= ' WHERE l.atr_id = :atr_id
					ORDER BY l.srtord';
			
			//echo $sql;
			
			$qryArray["tblnam"] = $TblNam;
			$qryArray["tbl_id"] = $Tbl_ID;
			$qryArray["atr_id"] = $Atr_ID;
			
			//print_r($qryArray);
			
			$res = $this->run($sql, $qryArray, $ReqObj);
			
			return $res;
			
		}
		return NULL;
	}



    function selectDistinctValues($TblNam=NULL, $Tbl_ID = NULL, $Atr_ID = NULL, $Atl_ID = NULL, $ReqObj = false) {

        $qryArray = array();
        $sql = 'SELECT
				GROUP_CONCAT(DISTINCT atvval) AS atvlst
				FROM attribute_value v
				WHERE TRUE ';

        if (!is_null($TblNam) && is_numeric($Tbl_ID)) {

            $sql .= ' AND v.tblnam = :tblnam AND v.tbl_id = :tbl_id ';
            $qryArray["tblnam"] = $TblNam;
            $qryArray["tbl_id"] = $Tbl_ID;
        }

        if (!is_null($Atr_ID) && is_numeric($Atr_ID)) {
            $sql .= ' AND v.atr_id = :atr_id ';
            $qryArray["atr_id"] = $Atr_ID;
        }
        if (!is_null($Atl_ID) && is_numeric($Atl_ID)) {
            $sql .= ' AND v.atl_id = :atl_id ';
            $qryArray["atl_id"] = $Atl_ID;
        }

        //echo '<pre>'.$sql.'</pre>';

        $res = $this->run($sql, $qryArray, $ReqObj);

        return $res;
    }


	function clearValues($TblNam=NULL, $Tbl_ID=NULL) {
		
		if (!is_null($TblNam) && is_numeric($Tbl_ID)) {
			$qryArray = array();
			$sql = 'DELETE FROM attribute_value WHERE tblnam = :tblnam AND tbl_id = :tbl_id ';
			$qryArray["tblnam"] = $TblNam;
			$qryArray["tbl_id"] = $Tbl_ID;
			
			$recordSet = $this->dbConn->prepare($sql);
			$recordSet->execute($qryArray);
		}
		
	}
	
	function update($AtvCls = NULL) {
	
		if (is_null($AtvCls) || !$AtvCls) return 'No Record To Update';
		
		$sql = '';
		
		$qryArray = array();

        $qryArray["atr_id"] = $AtvCls->atr_id;
        $qryArray["atl_id"] = $AtvCls->atl_id;
        $qryArray["atvval"] = $AtvCls->atvval;
        $qryArray["tblnam"] = $AtvCls->tblnam;
        $qryArray["tbl_id"] = $AtvCls->tbl_id;

		if ($AtvCls->atv_id == 0) {
			

			
			$sql = "INSERT INTO attribute_value
					(
					atr_id,
					atl_id,
					atvval,
					tblnam,
					tbl_id
					)
					VALUES
					(
					:atr_id,
					:atl_id,
					:atvval,
					:tblnam,
					:tbl_id
					);";
						
		} else {
            $qryArray["atv_id"] = $AtvCls->atv_id;

			$sql = "UPDATE attribute_value
					SET
					atr_id = :atr_id,
					atl_id = :atl_id,
					atvval = :atvval,
					atvlst = :atvlst,
					tblnam = :tblnam,
					tbl_id = :tbl_id
					WHERE atv_id = :atv_id";
		}
		
		//echo $AtvCls->tbl_id;
		
		$recordSet = $this->dbConn->prepare($sql);
		$recordSet->execute($qryArray);
		
		return ($AtvCls->atv_id == 0) ? $this->dbConn->lastInsertId('atv_id') : $AtvCls->atv_id;
	}
	
	function delete($Atv_ID = NULL) {
		try {
			if (!is_null($Atv_ID)) {
				$qryArray = array();
				$sql = 'DELETE FROM attribute_value WHERE atv_id = :atv_id ';
				$qryArray["atv_id"] = $Atv_ID;
				
				$recordSet = $this->dbConn->prepare($sql);
				$recordSet->execute($qryArray);
				
			}
			
		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}
        return $Atv_ID;
	}
	
}