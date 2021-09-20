<?php

//
// Places class
//

class PlaDAO extends db {
	
	function select($Pla_ID = NULL, $TblNam=NULL, $Tbl_ID=NULL, $PlaNam=NULL, $Sta_ID=NULL, $ReqObj=false) { 
	
		$qryArray = array();
		$sql = 'SELECT
				pla_id,
				tblnam,
				tbl_id,
				comnam,
				planam,
				adr1,
				adr2,
				adr3,
				adr4,
				pstcod,
				ctynam,
				goolat,
				goolng,
				plaema,
				platel,
				plamob,
				plaref,
				usrnam,
				paswrd,
				sta_id,
				credat,
				amndat,
				plaimg,
				minpri,
				maxpri,
				rooms,
				platyp,
				placol,
				plaurl,
				platxt,
				seourl,
				keywrd,
				keydsc
				FROM places WHERE TRUE';
		
		if (!is_null($Pla_ID)) {
			$sql .= ' AND pla_id = :pla_id ';
			$qryArray["pla_id"] = $Pla_ID;
		} else {
			if (!is_null($TblNam)) {
				$sql .= ' AND tblnam = :tblnam ';
				$qryArray["tblnam"] = $TblNam;
			}
			if (!is_null($Tbl_ID) && is_numeric($Tbl_ID)) {
				$sql .= ' AND tbl_id = :tbl_id ';
				$qryArray["tbl_id"] = $Tbl_ID;
			}
			if (!is_null($PlaNam)) {
				$PlaNam = '%'.$PlaNam.'%';
				$sql .= ' AND planam LIKE :planam ';
				$qryArray["planam"] = $PlaNam;
			}
			if (!is_null($Sta_ID) && is_numeric($Sta_ID)) {
				$sql .= ' AND sta_id = :sta_id ';
				$qryArray["sta_id"] = $Sta_ID;
			}
			
			$sql .= ' ORDER BY planam';
		}
		
		//echo $sql;

		return $this->run($sql, $qryArray, $ReqObj);

	}

    function selectBySeo($PlaSeo = NULL) {

        $qryArray = array();
        $sql = 'SELECT
				*
				FROM places WHERE seourl = :plaseo ';
            $qryArray["plaseo"] = $PlaSeo;

        return $this->run($sql, $qryArray, true);

    }

    function checkEmail($PlaEma = NULL, $TblNam = NULL) {

        if (!is_null($PlaEma) && !is_null($TblNam)) {
            $qryArray = array();
            $sql = 'SELECT
				*
				FROM places WHERE plaema = :plaema AND tblnam = :tblnam ';
            $qryArray["plaema"] = $PlaEma;
            $qryArray["tblnam"] = $TblNam;

            return $this->run($sql, $qryArray, true);
        } else {
            return NULL;
        }

    }

	function selectPlaceBookings($Pla_ID = NULL, $TblNam=NULL, $Tbl_ID=NULL, $PlaNam=NULL, $Sta_ID=NULL, $ReqObj=false, $planned=NULL) { 
	
		$qryArray = array();
		$sql = 'SELECT p.pla_id, p.planam, cli.planam AS clinam, p.rooms, p.tblnam, p.plaurl, p.tbl_id, p.placol, p.sta_id, max(b.begdat) AS lstdat, SUM(TIME_TO_SEC(TIMEDIFF(b.enddat,b.begdat)))/3600 AS tothrs
				FROM places p
				LEFT OUTER JOIN bookings b ON b.tblnam = p.tblnam AND b.tbl_id = p.pla_id 
				LEFT OUTER JOIN places cli ON cli.tblnam = "CUS" AND p.tbl_id = cli.pla_id';
				
		if (!is_null($planned)) {
			if ($planned == false) {
				$sql .= ' AND b.begdat <= NOW()';
			} else {
				$sql .= ' AND b.begdat > NOW()';
			}
		}
		
		$sql .= ' WHERE TRUE ';
		
		if (!is_null($planned)) {
			
			if ($planned == false) {
				$sql .= ' AND b.begdat <= NOW()';
			} else {
				$sql .= ' AND b.boo_id IS NOT NULL';
			}
		} else {	
		}
		
		if (!is_null($Pla_ID)) {
			$sql .= ' AND p.pla_id = :pla_id ';
			$qryArray["pla_id"] = $Pla_ID;
		} else {
			if (!is_null($TblNam)) {
				$sql .= ' AND p.tblnam = :tblnam ';
				$qryArray["tblnam"] = $TblNam;
			}
			if (!is_null($Tbl_ID) && is_numeric($Tbl_ID)) {
				$sql .= ' AND p.tbl_id = :tbl_id ';
				$qryArray["tbl_id"] = $Tbl_ID;
			}
			if (!is_null($PlaNam)) {
				$PlaNam = '%'.$PlaNam.'%';
				$sql .= ' AND p.planam LIKE :planam ';
				$qryArray["planam"] = $PlaNam;
			}
			if (!is_null($Sta_ID) && is_numeric($Sta_ID)) {
				$sql .= ' AND p.sta_id = :sta_id ';
				$qryArray["sta_id"] = $Sta_ID;
			}
			
			$sql .= ' GROUP BY p.pla_id';
			
			$sql .= ' ORDER BY planam, lstdat';
		}
		
		//echo $sql;
		//print_r($qryArray);
		//$this->displayQuery($sql, $qryArray);
		return $this->run($sql, $qryArray, $ReqObj);

	}

    function selectPlaceStats($Pla_ID = NULL, $TblNam=NULL, $Tbl_ID=NULL, $PlaNam=NULL, $Sta_ID=NULL, $ReqObj=false, $planned=NULL) {
	
		$qryArray = array();
		$sql = 'SELECT p.pla_id, p.planam, cli.planam AS clinam, p.rooms, p.tblnam, p.plaurl, p.tbl_id, p.placol, p.sta_id, p.credat, p.amndat,
				(SELECT SUM(TIME_TO_SEC(TIMEDIFF(bookings.enddat,bookings.begdat)))/3600 AS tothrs FROM bookings WHERE bookings.tbl_id = p.pla_id AND bookings.begdat <= now()) AS tothrs,
				(SELECT SUM(TIME_TO_SEC(TIMEDIFF(bookings.enddat,bookings.begdat)))/3600 AS tothrs FROM bookings WHERE bookings.tbl_id = p.pla_id AND bookings.begdat > now()) AS plnhrs
				FROM places p 
				INNER JOIN places cli ON cli.tblnam = "CUS" AND p.tbl_id = cli.pla_id';
		
		$sql .= ' WHERE TRUE ';
		
		if (!is_null($Pla_ID)) {
			$sql .= ' AND p.pla_id = :pla_id ';
			$qryArray["pla_id"] = $Pla_ID;
		} else {
			if (!is_null($TblNam)) {
				$sql .= ' AND p.tblnam = :tblnam ';
				$qryArray["tblnam"] = $TblNam;
			}
			if (!is_null($Tbl_ID) && is_numeric($Tbl_ID)) {
				$sql .= ' AND p.tbl_id = :tbl_id ';
				$qryArray["tbl_id"] = $Tbl_ID;
			}
			if (!is_null($PlaNam)) {
				$PlaNam = '%'.$PlaNam.'%';
				$sql .= ' AND p.planam LIKE :planam ';
				$qryArray["planam"] = $PlaNam;
			}
			if (!is_null($Sta_ID) && is_numeric($Sta_ID)) {
				$sql .= ' AND p.sta_id = :sta_id ';
				$qryArray["sta_id"] = $Sta_ID;
			}
			
			$sql .= ' GROUP BY p.pla_id';
			
			$sql .= ' ORDER BY planam';
		}
		
		//echo $sql;
		//print_r($qryArray);
		return $this->run($sql, $qryArray, $ReqObj);

	}
	
	
	function findByGoogle( $TblNam=NULL, $Tbl_ID=NULL, $GooLat=NULL, $GooLng=NULL, $GooRad=NULL ) {
		
		$EthRad = 3959;  // earth's radius in miles
		
		$sql = "SELECT distinct pla.pla_id as pla_id, pla.planam as planam, pla.plaimg, pla.adr1, pla.adr2, pla.adr3, pla.adr4, pla.pstcod as pstcod, pla.goolat as goolat, pla.goolng as goolng, pla.seourl, ";
		
		if(is_numeric($GooLat) && is_numeric($GooLng))  {
			
			$GooLat = deg2rad($GooLat);
			$GooLng = deg2rad($GooLng);	
			$sql .= "acos(sin($GooLat)*sin(radians(pla.goolat)) + cos($GooLat)*cos(radians(pla.goolat))*cos(radians(pla.goolng) - $GooLng))* $EthRad AS pladis  "; 
				
		}
		else 
			$sql .= "NULL AS pladis ";
		
		$sql .= "FROM places pla WHERE TRUE ";
		
		if(is_numeric($GooLat) && is_numeric($GooLng) && is_numeric($GooRad)) {		
			
			$sql .= "AND acos(sin($GooLat)*sin(radians(pla.goolat)) + cos($GooLat)*cos(radians(pla.goolat))*cos(radians(pla.goolng) - $GooLng))* $EthRad <= " . $GooRad . " ";
			
		}
		
		if (!is_null($TblNam)) $sql .= " AND tblnam = '".addslashes($TblNam)."'";
		if (is_numeric($Tbl_ID)) $sql .= " AND tbl_id = ".addslashes($Tbl_ID);
		
		$sql .= "ORDER BY pladis ASC";
		
		//echo $sql;
		
		$qryArray = array();
		return $this->run($sql, $qryArray, false);
		
	}
	
	function update($PlaCls = NULL) {
	
		if (is_null($PlaCls) || !$PlaCls) return 'No Record To Update';
		
		$sql = '';
		
		$qryArray = array();
		
		if ($PlaCls->pla_id == 0) {
						
			$qryArray["tblnam"] = $PlaCls->tblnam;
			$qryArray["tbl_id"] = $PlaCls->tbl_id;
			$qryArray["comnam"] = $PlaCls->comnam;
			$qryArray["planam"] = $PlaCls->planam;
			$qryArray["adr1"] = $PlaCls->adr1;
			$qryArray["adr2"] = $PlaCls->adr2;
			$qryArray["adr3"] = $PlaCls->adr3;
			$qryArray["adr4"] = $PlaCls->adr4;
			$qryArray["pstcod"] = $PlaCls->pstcod;
			$qryArray["ctynam"] = $PlaCls->ctynam;
			$qryArray["goolat"] = $PlaCls->goolat;
			$qryArray["goolng"] = $PlaCls->goolng;
			$qryArray["plaema"] = $PlaCls->plaema;
			$qryArray["platel"] = $PlaCls->platel;
			$qryArray["plamob"] = $PlaCls->plamob;
			$qryArray["plaref"] = $PlaCls->plaref;
			$qryArray["usrnam"] = $PlaCls->usrnam;
			$qryArray["paswrd"] = md5($PlaCls->paswrd);
			$qryArray["sta_id"] = $PlaCls->sta_id;
			$qryArray["credat"] = $PlaCls->credat;
			$qryArray["amndat"] = $PlaCls->amndat;
			$qryArray["plaimg"] = $PlaCls->plaimg;
			$qryArray["minpri"] = $PlaCls->minpri;
			$qryArray["maxpri"] = $PlaCls->maxpri;
			$qryArray["rooms"] = $PlaCls->rooms;
			$qryArray["platyp"] = $PlaCls->platyp;
			$qryArray["placol"] = $PlaCls->placol;
			$qryArray["plaurl"] = $PlaCls->plaurl;
			$qryArray["platxt"] = $PlaCls->platxt;

            $qryArray["seourl"] = $PlaCls->seourl;
            $qryArray["keywrd"] = $PlaCls->keywrd;
            $qryArray["keydsc"] = $PlaCls->keydsc;
			
			$sql = "INSERT INTO places
					(
					
					tblnam,
					tbl_id,
					comnam,
					planam,
					adr1,
					adr2,
					adr3,
					adr4,
					pstcod,
					ctynam,
					goolat,
					goolng,
					plaema,
					platel,
					plamob,
					plaref,
					usrnam,
					paswrd,
					sta_id,
					credat,
					amndat,
					plaimg,
					minpri,
					maxpri,
					rooms,
					platyp,
					placol,
					plaurl,
					platxt,
					seourl,
					keywrd,
					keydsc
					
					)
					VALUES
					(
					
					:tblnam,
					:tbl_id,
					:comnam,
					:planam,
					:adr1,
					:adr2,
					:adr3,
					:adr4,
					:pstcod,
					:ctynam,
					:goolat,
					:goolng,
					:plaema,
					:platel,
					:plamob,
					:plaref,
					:usrnam,
					:paswrd,
					:sta_id,
					:credat,
					:amndat,
					:plaimg,
					:minpri,
					:maxpri,
					:rooms,
					:platyp,
					:placol,
					:plaurl,
					:platxt,
					:seourl,
					:keywrd,
					:keydsc
					
					);";
					
					
						
		} else {
			
			$qryArray["tblnam"] = $PlaCls->tblnam;
			$qryArray["tbl_id"] = $PlaCls->tbl_id;
			$qryArray["comnam"] = $PlaCls->comnam;
			$qryArray["planam"] = $PlaCls->planam;
			$qryArray["adr1"] = $PlaCls->adr1;
			$qryArray["adr2"] = $PlaCls->adr2;
			$qryArray["adr3"] = $PlaCls->adr3;
			$qryArray["adr4"] = $PlaCls->adr4;
			$qryArray["pstcod"] = $PlaCls->pstcod;
			$qryArray["ctynam"] = $PlaCls->ctynam;
			$qryArray["goolat"] = $PlaCls->goolat;
			$qryArray["goolng"] = $PlaCls->goolng;
			$qryArray["plaema"] = $PlaCls->plaema;
			$qryArray["platel"] = $PlaCls->platel;
			$qryArray["plamob"] = $PlaCls->plamob;
			$qryArray["plaref"] = $PlaCls->plaref;
			$qryArray["usrnam"] = $PlaCls->usrnam;
			$qryArray["sta_id"] = $PlaCls->sta_id;
			$qryArray["credat"] = $PlaCls->credat;
			$qryArray["amndat"] = $PlaCls->amndat;
			$qryArray["plaimg"] = $PlaCls->plaimg;
			$qryArray["minpri"] = $PlaCls->minpri;
			$qryArray["maxpri"] = $PlaCls->maxpri;
			$qryArray["rooms"]  = $PlaCls->rooms;
			$qryArray["platyp"] = $PlaCls->platyp;
			$qryArray["placol"] = $PlaCls->placol;
			$qryArray["plaurl"] = $PlaCls->plaurl;
			$qryArray["platxt"] = $PlaCls->platxt;

            $qryArray["seourl"] = $PlaCls->seourl;
            $qryArray["keywrd"] = $PlaCls->keywrd;
            $qryArray["keydsc"] = $PlaCls->keydsc;
			
			$sql = "UPDATE places
					SET
					
					tblnam = :tblnam,
					tbl_id = :tbl_id,
					comnam = :comnam,
					planam = :planam,
					adr1 = :adr1,
					adr2 = :adr2,
					adr3 = :adr3,
					adr4 = :adr4,
					pstcod = :pstcod,
					ctynam = :ctynam,
					goolat = :goolat,
					goolng = :goolng,
					plaema = :plaema,
					platel = :platel,
					plamob = :plamob,
					plaref = :plaref,
					usrnam = :usrnam,
					sta_id = :sta_id,
					credat = :credat,
					amndat = :amndat,
					plaimg = :plaimg,
					minpri = :minpri,
					maxpri = :maxpri,
					rooms = :rooms,
					platyp = :platyp,
					placol = :placol,
					plaurl = :plaurl,
					platxt = :platxt,
					seourl = :seourl,
					keywrd = :keywrd,
					keydsc = :keydsc";
				
			if ( $PlaCls->paswrd && $PlaCls->paswrd != "" ) {
				$sql .= ", paswrd = :paswrd";
				$qryArray["paswrd"] = md5($PlaCls->paswrd);
			}
				
			$sql .= " WHERE pla_id = :pla_id";
			$qryArray["pla_id"] = $PlaCls->pla_id;
			
		}
		
		$recordSet = $this->dbConn->prepare($sql);
		$recordSet->execute($qryArray);
		
		return ($PlaCls->pla_id == 0) ? $this->dbConn->lastInsertId('pla_id') : $PlaCls->pla_id;
	}
	
	function delete($Pla_ID = NULL) {
	
		try {
			
			if (!is_null($Pla_ID)) {
				$qryArray = array();
				$sql = 'DELETE FROM places WHERE pla_id = :pla_id ';
				$qryArray["pla_id"] = $Pla_ID;
				
				$recordSet = $this->dbConn->prepare($sql);
				$recordSet->execute($qryArray);
				
				//
				// DELETE ATTRIBUTES
				//
				
				//
				// DELETE IMAGES
				//
				
				return $Pla_ID;
				
			}
			
		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}
		
	}
	
	function login($LogEma=NULL, $LogPwd=NULL, $TblNam=NULL, $Tbl_ID=NULL) {
		
		//$Log_ID = md5(date("sYimHd") );
		$Log_ID = NULL;

		if (is_null($LogEma) || is_null($LogPwd)) return $Log_ID;
		
		$qryArray = array();
		$sql = 'SELECT
				pla_id,
				plaema
				FROM places WHERE ';
		
		$sql .= ' plaema = :plaema ';
		$qryArray["plaema"] = $LogEma;
		$sql .= ' AND paswrd = :paswrd ';
		$qryArray["paswrd"] = md5($LogPwd);
	
		if (!is_null($TblNam)) {
			$sql .= ' AND tblnam = :tblnam ';
			$qryArray["tblnam"] = $TblNam;
		}
		if (!is_null($Tbl_ID) && is_numeric($Tbl_ID)) {
			$sql .= ' AND tbl_id = :tbl_id ';
			$qryArray["tbl_id"] = $Tbl_ID;
		}
		
		//echo $sql.' '.$LogEma.' '.$LogPwd.' '.$TblNam;
		
		$placeRec = $this->run($sql, $qryArray);
		
		if ($placeRec) {
			
			//print_r($placeRec);
			
			$Log_ID = md5($placeRec[0]['plaema'].date("sYimHd") );
			
			unset($qryArray);
			$qryArray = array();
			
			$sql = "UPDATE places
					SET
					pwdtok = :pwdtok WHERE pla_id = :pla_id";
			$qryArray["pwdtok"] = $Log_ID;
			$qryArray["pla_id"] = $placeRec[0]['pla_id'];
			
			$recordSet = $this->dbConn->prepare($sql);
			$recordSet->execute($qryArray);
			
		}
		
		return $Log_ID;
	}
	
	function loggedIn($Log_ID=NULL) {
		
		if (is_null($Log_ID)) return NULL;
		
		$qryArray = array();
		$sql = "SELECT 
				*
				FROM places WHERE
				pwdtok = :pwdtok AND pwdtok != ''";
		$qryArray["pwdtok"] = $Log_ID;

		return $this->run($sql, $qryArray, true);
		
		return (is_array($placeRec)) ? $placeRec[0]['pwdtok'] : 0; //count($userRec);
		
	}
	
	
	function forgotPassword($LogEma=NULL, $TblNam = NULL) {
		
		if (is_null($LogEma) || empty($LogEma)) return NULL;
		
		$qryArray = array();
		$sql = "SELECT 
				*
				FROM places WHERE
				plaema = :logema";
		$qryArray["logema"] = $LogEma;

        if (!is_null($TblNam)) {
            $sql .= ' AND tblnam = :tblnam ';
            $qryArray["tblnam"] = $TblNam;
        }

		$placeRec = $this->run($sql, $qryArray, true);

		if (isset($placeRec->planam)) {
			
			$PwdTok = rand(0,999999999);
			$sql = "UPDATE places SET pwdtok = '" . $PwdTok . "' WHERE plaema = :plaema LIMIT 1";
			$qryArray = array();
			$qryArray["plaema"] = $LogEma;

//            echo $sql;
//            print_r($qryArray);

			$recordSet = $this->dbConn->prepare($sql);
			$recordSet->execute($qryArray);
			
			return $PwdTok;
			
		} else {
			
			return '0';
			
		}
		
	}
	
	function updatePassword($ForTok=NULL, $PasWrd=NULL) {
		
		if (is_null($ForTok) || is_null($PasWrd)) return NULL;
		
		$qryArray = array();
		$sql = "SELECT 
				*
				FROM places WHERE
				fortok = :fortok AND fortok != ''";
		$qryArray["fortok"] = $ForTok;
		
		$placeRec = $this->run($sql, $qryArray, true);
		
		if (isset($placeRec)) {
			
			echo ' found place '.$ForTok;
			
			$PwdTok = rand(0,999999999);
			$sql = "UPDATE places SET paswrd = '" . md5($PasWrd) . "', fortok = '' WHERE fortok = :fortok LIMIT 1";
			$qryArray = array();
			$qryArray["fortok"] = $ForTok;
			
			$recordSet = $this->dbConn->prepare($sql);
			$recordSet->execute($qryArray);
			
		} else {
			
			return '0';
			
		}
		
	}
	
}

?>
