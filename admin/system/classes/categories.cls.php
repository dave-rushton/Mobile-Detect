<?php

//
// Categories class
//

class CatDAO extends db {
	
	function select($Cat_ID = NULL, $TblNam=NULL, $Tbl_ID=NULL, $CatNam = NULL, $ReqObj = false) { 
	
		$qryArray = array();
		$sql = 'SELECT
				cat_id,
				catnam,
				tblnam,
				tbl_id,
				keydsc,
				seourl,
				keywrd,
				keydsc,
				sta_id
				FROM categories WHERE true';
		
		if (!is_null($Cat_ID)) {
			$sql .= ' AND cat_id = :cat_id ';
			$qryArray["cat_id"] = $Cat_ID;
		} else {
			
			if (!is_null($TblNam)) {
				$sql .= ' AND tblnam = :tblnam ';
				$qryArray["tblnam"] = $TblNam;
			}
			
			if (!is_null($Tbl_ID) && is_numeric($Tbl_ID)) {
				$sql .= ' AND tbl_id LIKE :tbl_id ';
				$qryArray["tbl_id"] = $Tbl_ID;
			}
			
			if (!is_null($CatNam)) {
				$CatNam = '%'.$CatNam.'%';
				$sql .= ' AND catnam LIKE :catnam ';
				$qryArray["catnam"] = $CatNam;
			}
		}
		
		//print_r($qryArray);
		//echo $sql;
		
		return $this->run($sql, $qryArray, $ReqObj);

	}
	
	function update($CatCls = NULL) {
	
		if (is_null($CatCls) || !$CatCls) return 'No Record To Update';
		
		$sql = '';
		
		$qryArray = array();
		
		if ($CatCls->cat_id == 0) {
						
			$qryArray["catnam"] = $CatCls->catnam;
			$qryArray["tblnam"] = $CatCls->tblnam;
			$qryArray["tbl_id"] = $CatCls->tbl_id;
			$qryArray["seourl"] = $CatCls->seourl;
			$qryArray["keywrd"] = $CatCls->keywrd;
			$qryArray["keydsc"] = $CatCls->keydsc;
			$qryArray["sta_id"] = $CatCls->sta_id;
			
			$sql = "INSERT INTO categories
					(
					
					catnam,
					tblnam,
					tbl_id,
					seourl,
					keywrd,
					keydsc,
					sta_id
					)
					VALUES
					(
					:catnam,
					:tblnam,
					:tbl_id,
					:seourl,
					:keywrd,
					:keydsc,
					:sta_id
					);";
						
		} else {
			
			$qryArray["catnam"] = $CatCls->catnam;
			$qryArray["tblnam"] = $CatCls->tblnam;
			$qryArray["tbl_id"] = $CatCls->tbl_id;
			$qryArray["seourl"] = $CatCls->seourl;
			$qryArray["keywrd"] = $CatCls->keywrd;
			$qryArray["keydsc"] = $CatCls->keydsc;
			$qryArray["sta_id"] = $CatCls->sta_id;
			
			$sql = "UPDATE categories
					SET
					
					catnam = :catnam,
					tblnam = :tblnam,
					tbl_id = :tbl_id,
					seourl = :seourl,
					keywrd = :keywrd,
					keydsc = :keydsc,
					sta_id = :sta_id";
				
			$sql .= " WHERE cat_id = :cat_id";
			$qryArray["cat_id"] = $CatCls->cat_id;
			
		}
		
		//echo $sql;
		
		$recordSet = $this->dbConn->prepare($sql);
		$recordSet->execute($qryArray);
		
		return ($CatCls->cat_id == 0) ? $this->dbConn->lastInsertId('cat_id') : $CatCls->cat_id;
	}
	
	function delete($Cat_ID = NULL) {
	
		try {
			
			if (!is_null($Cat_ID)) {
				$qryArray = array();
				$sql = 'DELETE FROM categories WHERE cat_id = :cat_id ';
				$qryArray["cat_id"] = $Cat_ID;
				
				$recordSet = $this->dbConn->prepare($sql);
				$recordSet->execute($qryArray);
				
				$qryArray = array();
				$sql = 'DELETE FROM subcategories WHERE cat_id = :cat_id ';
				$qryArray["cat_id"] = $Cat_ID;
				
				$recordSet = $this->dbConn->prepare($sql);
				$recordSet->execute($qryArray);
				
				return $Cat_ID;
				
			}
			
		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}
		
	}
	
}

?>