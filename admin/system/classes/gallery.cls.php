<?php

//
// Galegories class
//

class GalDAO extends db {
	
	function select($Gal_ID = NULL, $TblNam=NULL, $Tbl_ID=NULL, $GalNam = NULL, $ReqObj = false) { 
	
		$qryArray = array();
		$sql = 'SELECT
				gal_id,
				galnam,
				tblnam,
				tbl_id,
				keydsc,
				seourl,
				keywrd,
				keydsc,
				sta_id
				FROM gallery WHERE true';
		
		if (!is_null($Gal_ID)) {
			$sql .= ' AND gal_id = :gal_id ';
			$qryArray["gal_id"] = $Gal_ID;
		} else {
			
			if (!is_null($TblNam)) {
				$sql .= ' AND tblnam = :tblnam ';
				$qryArray["tblnam"] = $TblNam;
			}
			
			if (!is_null($Tbl_ID) && is_numeric($Tbl_ID)) {
				$sql .= ' AND tbl_id LIKE :tbl_id ';
				$qryArray["tbl_id"] = $Tbl_ID;
			}
			
			if (!is_null($GalNam)) {
				$GalNam = '%'.$GalNam.'%';
				$sql .= ' AND galnam LIKE :galnam ';
				$qryArray["galnam"] = $GalNam;
			}
		}

		return $this->run($sql, $qryArray, $ReqObj);

	}
	
	function update($GalCls = NULL) {
	
		if (is_null($GalCls) || !$GalCls) return 'No Record To Update';
		
		$sql = '';
		
		$qryArray = array();
		
		if ($GalCls->gal_id == 0) {
						
			$qryArray["galnam"] = $GalCls->galnam;
			$qryArray["tblnam"] = $GalCls->tblnam;
			$qryArray["tbl_id"] = $GalCls->tbl_id;
			$qryArray["seourl"] = $GalCls->seourl;
			$qryArray["keywrd"] = $GalCls->keywrd;
			$qryArray["keydsc"] = $GalCls->keydsc;
			$qryArray["sta_id"] = $GalCls->sta_id;
			
			$sql = "INSERT INTO gallery
					(
					
					galnam,
					tblnam,
					tbl_id,
					seourl,
					keywrd,
					keydsc,
					sta_id
					)
					VALUES
					(
					:galnam,
					:tblnam,
					:tbl_id,
					:seourl,
					:keywrd,
					:keydsc,
					:sta_id
					);";
						
		} else {
			
			$qryArray["galnam"] = $GalCls->galnam;
			$qryArray["tblnam"] = $GalCls->tblnam;
			$qryArray["tbl_id"] = $GalCls->tbl_id;
			$qryArray["seourl"] = $GalCls->seourl;
			$qryArray["keywrd"] = $GalCls->keywrd;
			$qryArray["keydsc"] = $GalCls->keydsc;
			$qryArray["sta_id"] = $GalCls->sta_id;
			
			$sql = "UPDATE gallery
					SET
					
					galnam = :galnam,
					tblnam = :tblnam,
					tbl_id = :tbl_id,
					seourl = :seourl,
					keywrd = :keywrd,
					keydsc = :keydsc,
					sta_id = :sta_id";
				
			$sql .= " WHERE gal_id = :gal_id";
			$qryArray["gal_id"] = $GalCls->gal_id;
			
		}
		
		$recordSet = $this->dbConn->prepare($sql);
		$recordSet->execute($qryArray);
		
		return ($GalCls->gal_id == 0) ? $this->dbConn->lastInsertId('gal_id') : $GalCls->gal_id;
	}
	
	function delete($Gal_ID = NULL) {
	
		try {
			
			if (!is_null($Gal_ID)) {
				$qryArray = array();
				$sql = 'DELETE FROM gallery WHERE gal_id = :gal_id ';
				$qryArray["gal_id"] = $Gal_ID;
				
				$recordSet = $this->dbConn->prepare($sql);
				$recordSet->execute($qryArray);
				
				return $Gal_ID;
				
			}
			
		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}
		
	}
	
}

?>