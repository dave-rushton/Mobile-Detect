<?php

//
// Subcategories class
//

class SubDAO extends db {
	
	function select($Cat_ID = NULL, $Sub_ID=NULL, $SubNam = NULL, $Sta_ID=NULL, $ReqObj = false) {
	
		$qryArray = array();
		$sql = 'SELECT
				cat_id,
				subnam,
				sub_id,
				keydsc,
				seourl,
				keywrd,
				keydsc,
				sta_id,
				subtxt
				FROM subcategories WHERE true';
		
		if (!is_null($Sub_ID)) {
			$sql .= ' AND sub_id = :sub_id ';
			$qryArray["sub_id"] = $Sub_ID;
		} else if (!is_null($Cat_ID)) {
			$sql .= ' AND cat_id = :cat_id ';
			$qryArray["cat_id"] = $Cat_ID;
		} else {
			
			if (!is_null($SubNam)) {
				$SubNam = '%'.$SubNam.'%';
				$sql .= ' AND subnam LIKE :subnam ';
				$qryArray["subnam"] = $SubNam;
			}
		}

        if (is_numeric($Sta_ID)) {
            $sql .= ' AND sta_id = :sta_id ';
            $qryArray["sta_id"] = $Sta_ID;
        }

		$sql .= ' ORDER BY srtord,subnam';

		return $this->run($sql, $qryArray, $ReqObj);

	}
	
	function selectByTableName($TblNam=NULL, $Sta_ID=0) {
		
		$qryArray = array();
		$sql = 'SELECT
				s.cat_id,
				s.subnam,
				s.sub_id,
				s.keydsc,
				s.seourl,
				s.keywrd,
				s.keydsc,
				s.sta_id,
				s.subtxt
				FROM subcategories s 
				INNER JOIN categories c ON s.cat_id = c.cat_id
				WHERE true ';
	
		if (!is_null($TblNam)) {

            $TblNamArr = explode(",", $TblNam);

            if (count($TblNamArr) > 1) {
                //$qryArray["tblnam"] = rtrim(implode("','", $TblNamArr));
                $sql .= " AND find_in_set(cast(tblnam as char), :tblnam) ";
                $qryArray["tblnam"] = $TblNam;
            }
            else {
                $sql .= ' AND c.tblnam = :tblnam ';
                $qryArray["tblnam"] = $TblNam;
            }
		}

        if (is_numeric($Sta_ID)) {
            $sql .= ' AND s.sta_id = :sta_id ';
            $qryArray["sta_id"] = $Sta_ID;
        }

		$sql .= ' ORDER BY srtord,subnam';

        //echo $sql;

		return $this->run($sql, $qryArray, false);

	}


    function selectByIDs($Sub_ID=NULL) {

        if (!empty($Sub_ID)) {

        $qryArray = array();
        $sql = 'SELECT
				s.cat_id,
				s.subnam,
				s.sub_id,
				s.keydsc,
				s.seourl,
				s.keywrd,
				s.keydsc,
				s.sta_id,
				s.subtxt
				FROM subcategories s
				INNER JOIN categories c ON s.cat_id = c.cat_id
				WHERE s.sta_id = 0 ';



            $Sub_IDArr = explode(",", $Sub_ID);

            if (count($Sub_IDArr) > 1) {
                //$qryArray["tblnam"] = rtrim(implode("','", $TblNamArr));
                $sql .= " AND find_in_set(cast(sub_id as char), :sub_id) ";
                $qryArray["sub_id"] = $Sub_ID;
            }
            else {
                $sql .= ' AND s.sub_id = :sub_id ';
                $qryArray["sub_id"] = $Sub_ID;
            }

            $sql .= ' ORDER BY srtord,subnam';

            //echo $sql;

            return $this->run($sql, $qryArray, false);

        }

    }

	
	function selectBySeoUrl($SeoUrl=NULL, $ReqObj=false, $TblNam=NULL) {
		
		$qryArray = array();
		$sql = 'SELECT
				s.cat_id,
				s.subnam,
				s.sub_id,
				s.keydsc,
				s.seourl,
				s.keywrd,
				s.keydsc,
				s.sta_id,
				s.subtxt
				FROM subcategories s 
				INNER JOIN categories c ON s.cat_id = c.cat_id
				WHERE s.sta_id = 0 ';
	
		if (!is_null($SeoUrl)) {
			$sql .= ' AND s.seourl = :seourl ';
			$qryArray["seourl"] = $SeoUrl;
		}

        if (!is_null($TblNam)) {
            $sql .= ' AND c.tblnam = :tblnam ';
            $qryArray["tblnam"] = $TblNam;
        }

		$sql .= ' ORDER BY subnam';
		
		return $this->run($sql, $qryArray, $ReqObj);

	}
	
	function selectByCategory($Sub_ID=NULL, $SubSeo=NULL) { 
		
		$qryArray = array();
		$sql = 'SELECT
				s.cat_id,
				s.subnam,
				s.sub_id,
				s.keydsc,
				s.seourl,
				s.keywrd,
				s.keydsc,
				s.sta_id,
				s.subtxt
				FROM subcategories s 
				INNER JOIN categories c ON s.cat_id = c.cat_id
				WHERE s.sta_id = 0 ';
	
		if (!is_null($Sub_ID) && is_numeric($Sub_ID)) {
			$sql .= ' AND c.sub_id = :sub_id ';
			$qryArray["sub_id"] = $Sub_ID;
		}
		
		if (!is_null($SubSeo)) {
			$sql .= ' AND s.seourl = :subseo ';
			$qryArray["subseo"] = $SubSeo;
		}

		$sql .= ' ORDER BY subnam';
		
		return $this->run($sql, $qryArray, true);

	}
	
	function update($SubCls = NULL) {
	
		if (is_null($SubCls) || !$SubCls) return 'No Record To Update';
		
		$sql = '';
		
		$qryArray = array();
		
		if ($SubCls->sub_id == 0) {
						
			$qryArray["subnam"] = $SubCls->subnam;
			$qryArray["cat_id"] = $SubCls->cat_id;
			$qryArray["seourl"] = $SubCls->seourl;
			$qryArray["keywrd"] = $SubCls->keywrd;
			$qryArray["keydsc"] = $SubCls->keydsc;
            $qryArray["sta_id"] = $SubCls->sta_id;
            $qryArray["subtxt"] = $SubCls->subtxt;
			
			$sql = "INSERT INTO subcategories
					(
					
					subnam,
					cat_id,
					seourl,
					keywrd,
					keydsc,
					srtord,
					sta_id,
					subtxt
					)
					VALUES
					(
					:subnam,
					:cat_id,
					:seourl,
					:keywrd,
					:keydsc,
					99,
					:sta_id,
					:subtxt
					);";
						
		} else {
			
			$qryArray["subnam"] = $SubCls->subnam;
			$qryArray["cat_id"] = $SubCls->cat_id;
			$qryArray["seourl"] = $SubCls->seourl;
			$qryArray["keywrd"] = $SubCls->keywrd;
			$qryArray["keydsc"] = $SubCls->keydsc;
            $qryArray["sta_id"] = $SubCls->sta_id;
            $qryArray["subtxt"] = $SubCls->subtxt;
			
			$sql = "UPDATE subcategories
					SET
					
					subnam = :subnam,
					cat_id = :cat_id,
					seourl = :seourl,
					keywrd = :keywrd,
					keydsc = :keydsc,
					sta_id = :sta_id,
					subtxt = :subtxt";
				
			$sql .= " WHERE sub_id = :sub_id";
			$qryArray["sub_id"] = $SubCls->sub_id;
			
		}
		
		//echo $sql;
		//print_r($qryArray);
		
		$recordSet = $this->dbConn->prepare($sql);
		$recordSet->execute($qryArray);
		
		return ($SubCls->sub_id == 0) ? $this->dbConn->lastInsertId('sub_id') : $SubCls->sub_id;
	}
	
	function delete($Sub_ID = NULL) {
	
		try {
			
			if (!is_null($Sub_ID)) {
				$qryArray = array();
				$sql = 'DELETE FROM subcategories WHERE sub_id = :sub_id ';
				$qryArray["sub_id"] = $Sub_ID;
				
				$recordSet = $this->dbConn->prepare($sql);
				$recordSet->execute($qryArray);
				
				return $Sub_ID;
				
			}
			
		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}
		
	}
	
}

?>