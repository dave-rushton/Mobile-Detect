<?php

//
// People class
//

class PplDAO extends db {
	
	function select($Ppl_ID = NULL, $TblNam=NULL, $Tbl_ID=NULL, $PplNam = NULL, $ReqObj = false) { 
	
		$qryArray = array();
		$sql = 'SELECT
				ppl_id,
				tblnam,
				tbl_id,
				pplttl,
				pplfna,
				pplsna,
				pplnam,
				adr1,
				adr2,
				adr3,
				adr4,
				pstcod,
				ctynam,
				goolat,
				goolng,
				pplema,
				ppltel,
				pplmob,
				pplref,
				usrnam,
				paswrd,
				sta_id,
				credat,
				amndat,
				pplimg,
				ppltxt,
				srtord
				FROM people WHERE true';

		
		if (!is_null($Ppl_ID)) {
			$sql .= ' AND ppl_id = :ppl_id ';
			$qryArray["ppl_id"] = $Ppl_ID;
		} else {
			
			if (!is_null($TblNam)) {
				$sql .= ' AND tblnam = :tblnam ';
				$qryArray["tblnam"] = $TblNam;
			}
			
			if (!is_null($Tbl_ID) && is_numeric($Tbl_ID)) {
				$sql .= ' AND tbl_id LIKE :tbl_id ';
				$qryArray["tbl_id"] = $Tbl_ID;
			}
			
			if (!is_null($PplNam)) {
				$PplNam = '%'.$PplNam.'%';
				$sql .= ' AND pplnam LIKE :pplnam ';
				$qryArray["pplnam"] = $PplNam;
			}
		}

		$sql .= ' ORDER BY srtord';
		
//		echo $this->displayQuery($sql, $qryArray, $ReqObj);
		return $this->run($sql, $qryArray, $ReqObj);

	}
	
	function update($PplCls = NULL) {
	
		if (is_null($PplCls) || !$PplCls) return 'No Record To Update';
		
		$sql = '';
		
		$qryArray = array();
		
		if ($PplCls->ppl_id == 0) {
						
			$qryArray["tblnam"] = $PplCls->tblnam;
			$qryArray["tbl_id"] = $PplCls->tbl_id;
			$qryArray["pplttl"] = $PplCls->pplttl;
			$qryArray["pplfna"] = $PplCls->pplfna;
			$qryArray["pplsna"] = $PplCls->pplsna;
			$qryArray["pplnam"] = $PplCls->pplnam;
			$qryArray["adr1"] = $PplCls->adr1;
			$qryArray["adr2"] = $PplCls->adr2;
			$qryArray["adr3"] = $PplCls->adr3;
			$qryArray["adr4"] = $PplCls->adr4;
			$qryArray["pstcod"] = $PplCls->pstcod;
			$qryArray["ctynam"] = $PplCls->ctynam;
			$qryArray["goolat"] = $PplCls->goolat;
			$qryArray["goolng"] = $PplCls->goolng;
			$qryArray["pplema"] = $PplCls->pplema;
			$qryArray["ppltel"] = $PplCls->ppltel;
			$qryArray["pplmob"] = $PplCls->pplmob;
			$qryArray["pplref"] = $PplCls->pplref;
			$qryArray["usrnam"] = $PplCls->usrnam;
			$qryArray["paswrd"] = md5($PplCls->paswrd);
			$qryArray["sta_id"] = $PplCls->sta_id;
			$qryArray["credat"] = $PplCls->credat;
			$qryArray["amndat"] = $PplCls->amndat;
			$qryArray["pplimg"] = $PplCls->pplimg;
			$qryArray["ppltxt"] = $PplCls->ppltxt;
			$qryArray["srtord"] = $PplCls->srtord;

			$sql = "INSERT INTO people
					(
					
					tblnam,
					tbl_id,
					pplttl,
					pplfna,
					pplsna,
					pplnam,
					adr1,
					adr2,
					adr3,
					adr4,
					pstcod,
					ctynam,
					goolat,
					goolng,
					pplema,
					ppltel,
					pplmob,
					pplref,
					usrnam,
					paswrd,
					sta_id,
					credat,
					amndat,
					pplimg,
					ppltxt,
					srtord
					
					)
					VALUES
					(
					
					:tblnam,
					:tbl_id,
					:pplttl,
					:pplfna,
					:pplsna,
					:pplnam,
					:adr1,
					:adr2,
					:adr3,
					:adr4,
					:pstcod,
					:ctynam,
					:goolat,
					:goolng,
					:pplema,
					:ppltel,
					:pplmob,
					:pplref,
					:usrnam,
					:paswrd,
					:sta_id,
					:credat,
					:amndat,
					:pplimg,
					:ppltxt,
					:srtord
					
					);";
						
		} else {
			
			$qryArray["tblnam"] = $PplCls->tblnam;
			$qryArray["tbl_id"] = $PplCls->tbl_id;
			$qryArray["pplttl"] = $PplCls->pplttl;
			$qryArray["pplfna"] = $PplCls->pplfna;
			$qryArray["pplsna"] = $PplCls->pplsna;
			$qryArray["pplnam"] = $PplCls->pplnam;
			$qryArray["adr1"] = $PplCls->adr1;
			$qryArray["adr2"] = $PplCls->adr2;
			$qryArray["adr3"] = $PplCls->adr3;
			$qryArray["adr4"] = $PplCls->adr4;
			$qryArray["pstcod"] = $PplCls->pstcod;
			$qryArray["ctynam"] = $PplCls->ctynam;
			$qryArray["goolat"] = $PplCls->goolat;
			$qryArray["goolng"] = $PplCls->goolng;
			$qryArray["pplema"] = $PplCls->pplema;
			$qryArray["ppltel"] = $PplCls->ppltel;
			$qryArray["pplmob"] = $PplCls->pplmob;
			$qryArray["pplref"] = $PplCls->pplref;
			$qryArray["usrnam"] = $PplCls->usrnam;
			$qryArray["sta_id"] = $PplCls->sta_id;
			$qryArray["credat"] = $PplCls->credat;
			$qryArray["amndat"] = $PplCls->amndat;
			$qryArray["pplimg"] = $PplCls->pplimg;
            $qryArray["ppltxt"] = $PplCls->ppltxt;
            $qryArray["srtord"] = $PplCls->srtord;

			$sql = "UPDATE people
					SET
					
					tblnam = :tblnam,
					tbl_id = :tbl_id,
					pplttl = :pplttl,
					pplfna = :pplfna,
					pplsna = :pplsna,
					pplnam = :pplnam,
					adr1 = :adr1,
					adr2 = :adr2,
					adr3 = :adr3,
					adr4 = :adr4,
					pstcod = :pstcod,
					ctynam = :ctynam,
					goolat = :goolat,
					goolng = :goolng,
					pplema = :pplema,
					ppltel = :ppltel,
					pplmob = :pplmob,
					pplref = :pplref,
					usrnam = :usrnam,
					sta_id = :sta_id,
					credat = :credat,
					amndat = :amndat,
					pplimg = :pplimg,
					ppltxt = :ppltxt,
					srtord = :srtord
					";

			if ( $PplCls->paswrd && $PplCls->paswrd != "" ) {
				$sql .= ", paswrd = :paswrd";
				$qryArray["paswrd"] = md5($PplCls->paswrd);
			}
				
			$sql .= " WHERE ppl_id = :ppl_id";
			$qryArray["ppl_id"] = $PplCls->ppl_id;
			
		}
		
		$recordSet = $this->dbConn->prepare($sql);
		$recordSet->execute($qryArray);
		
		return ($PplCls->ppl_id == 0) ? $this->dbConn->lastInsertId('ppl_id') : $PplCls->ppl_id;
	}
	
	function delete($Ppl_ID = NULL) {
	
		try {
			
			if (!is_null($Ppl_ID)) {
				$qryArray = array();
				$sql = 'DELETE FROM people WHERE ppl_id = :ppl_id ';
				$qryArray["ppl_id"] = $Ppl_ID;
				
				$recordSet = $this->dbConn->prepare($sql);
				$recordSet->execute($qryArray);
				
				return $Ppl_ID;
				
			}
			
		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}
		
	}
	
}

?>