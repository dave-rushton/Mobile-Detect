<?php

//
// Product Types class
//

class PrtDAO extends db {
	
	function select($Prt_ID = NULL, $SeoUrl=NULL, $TblNam=NULL, $Tbl_ID=NULL, $PrtNam=NULL, $Atr_ID=NULL, $PerPag=NULL, $Pag_No=NULL, $ReqObj=false) { 
		
		$OffSet=NULL;
		if (isset($PerPag) && isset($Pag_No) && is_numeric($PerPag) && is_numeric($Pag_No)) $OffSet = ($Pag_No-1) * $PerPag;
		
		$qryArray = array();
		$sql = 'SELECT
				p.prt_id,
				p.tblnam,
				p.tbl_id,
				p.prtnam,
				p.prtdsc,
				p.atr_id,
				p.sta_id,
				p.usestk,
				p.atr_id,
				a.atrnam,
				a.seourl AS atrseo,
				p.unipri,
				p.buypri,
				p.delpri,
				p.seourl,
				p.seokey,
				p.seodsc,
				s.subnam,
				s.seourl AS subseo,
				u.filnam AS prtimg
				FROM producttypes p 
				LEFT OUTER JOIN attribute_group a ON a.atr_id = p.atr_id
				LEFT OUTER JOIN subcategories s ON s.sub_id = a.tbl_id
				LEFT OUTER JOIN uploads u ON u.tblnam = "PRDTYPE" AND u.tbl_id = p.prt_id
				WHERE TRUE';
		
		if (!is_null($Prt_ID)) {
			$sql .= ' AND p.prt_id = :prt_id ';
			$qryArray["prt_id"] = $Prt_ID;
		} else {
			if (!is_null($Atr_ID) && is_numeric($Atr_ID)) {
				$sql .= ' AND p.atr_id = :atr_id ';
				$qryArray["atr_id"] = $Atr_ID;
			}
			
			if (!is_null($SeoUrl)) {
				$sql .= ' AND p.seourl = :seourl ';
				$qryArray["seourl"] = $SeoUrl;
			}
			
			if (!is_null($TblNam)) {
				$sql .= ' AND p.tblnam = :tblnam ';
				$qryArray["tblnam"] = $TblNam;
			}
			if (!is_null($Tbl_ID) && is_numeric($Tbl_ID)) {
				$sql .= ' AND p.tbl_id = :tbl_id ';
				$qryArray["tbl_id"] = $Tbl_ID;
			}
			if (!is_null($PrtNam)) {
				$PrtNam = '%'.$PrtNam.'%';
				$sql .= ' AND p.prtnam LIKE :prtnam ';
				$qryArray["prtnam"] = $PrtNam;
			}
			
			$sql .= ' GROUP BY p.prt_id ';
			
			if (!is_null($OffSet) && is_numeric($OffSet) && !is_null($PerPag) && is_numeric($PerPag)) {
				$sql .= ' LIMIT '.$OffSet.' , '.$PerPag;
			} else {
				
			}
			
			
			
		}
		
		//echo '<p>'.$sql.'</p>';
		//print_r($qryArray);
		
		return $this->run($sql, $qryArray, $ReqObj);

	}

	
	function update($PrtCls = NULL) {
	
		if (is_null($PrtCls) || !$PrtCls) return 'No Record To Update';
		
		$sql = '';
		
		$qryArray = array();
		
		if ($PrtCls->prt_id == 0) {
			
			$qryArray["tblnam"] = $PrtCls->tblnam;
			$qryArray["tbl_id"] = $PrtCls->tbl_id;
			$qryArray["prtnam"] = $PrtCls->prtnam;
			$qryArray["prtdsc"] = $PrtCls->prtdsc;
			$qryArray["atr_id"] = $PrtCls->atr_id;
			$qryArray["sta_id"] = $PrtCls->sta_id;
			
			$qryArray["usestk"] = $PrtCls->usestk;
			$qryArray["unipri"] = $PrtCls->unipri;
			$qryArray["buypri"] = $PrtCls->buypri;
			$qryArray["delpri"] = $PrtCls->delpri;
			
			$qryArray["seourl"] = $PrtCls->seourl;
			$qryArray["seokey"] = $PrtCls->seokey;
			$qryArray["seodsc"] = $PrtCls->seodsc;
			
			
			$sql = "INSERT INTO producttypes
					(
					
					tblnam,
					tbl_id,
					prtnam,
					prtdsc,
					atr_id,
					sta_id,
					usestk,
					unipri,
					buypri,
					delpri,
					seourl,
					seokey,
					seodsc
					
					)
					VALUES
					(
					
					:tblnam,
					:tbl_id,
					:prtnam,
					:prtdsc,
					:atr_id,
					:sta_id,
					:usestk,
					:unipri,
					:buypri,
					:delpri,
					:seourl,
					:seokey,
					:seodsc
					
					);";
						
		} else {
			
			$qryArray["tblnam"] = $PrtCls->tblnam;
			$qryArray["tbl_id"] = $PrtCls->tbl_id;
			$qryArray["prt_id"] = $PrtCls->prt_id;
			$qryArray["prtnam"] = $PrtCls->prtnam;
			$qryArray["prtdsc"] = $PrtCls->prtdsc;
			$qryArray["atr_id"] = $PrtCls->atr_id;
			$qryArray["sta_id"] = $PrtCls->sta_id;
			$qryArray["unipri"] = $PrtCls->unipri;
			$qryArray["buypri"] = $PrtCls->buypri;
			$qryArray["delpri"] = $PrtCls->delpri;
			$qryArray["usestk"] = $PrtCls->usestk;
			
			$qryArray["seourl"] = $PrtCls->seourl;
			$qryArray["seokey"] = $PrtCls->seokey;
			$qryArray["seodsc"] = $PrtCls->seodsc;
			
			$sql = "UPDATE producttypes
					SET
					
					tblnam = :tblnam,
					tbl_id = :tbl_id,
					prt_id = :prt_id,
					prtnam = :prtnam,
					prtdsc = :prtdsc,
					atr_id = :atr_id,
					sta_id = :sta_id,
					unipri = :unipri,
					buypri = :buypri,
					delpri = :delpri,
					usestk = :usestk,
					seourl = :seourl,
					seokey = :seokey,
					seodsc = :seodsc";
				
			$sql .= " WHERE prt_id = :prt_id";
			$qryArray["prt_id"] = $PrtCls->prt_id;
			
		}
		
		$recordSet = $this->dbConn->prepare($sql);
		$recordSet->execute($qryArray);
		
		return ($PrtCls->prt_id == 0) ? $this->dbConn->lastInsertId('prt_id') : $PrtCls->prt_id;
	}
	
	function delete($Prt_ID = NULL) {
	
		try {
			
			if (!is_null($Prt_ID)) {
				$qryArray = array();
				$sql = 'DELETE FROM producttypes WHERE prt_id = :prt_id ';
				$qryArray["prt_id"] = $Prt_ID;
				
				$recordSet = $this->dbConn->prepare($sql);
				$recordSet->execute($qryArray);
				
				//
				// DELETE ATTRIBUTES
				//
				
				//
				// DELETE IMAGES
				//
				
				return $Prt_ID;
				
			}
			
		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}
		
	}
	
}

?>