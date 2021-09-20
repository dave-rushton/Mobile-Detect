<?php

//
// Related class
//

class RelDAO extends db {
	
	function select($Rel_ID = NULL, $TblNam=NULL, $Tbl_ID=NULL, $RefNam=NULL, $Ref_ID=NULL, $ReqObj=false, $SrtOrd = 'ref_id') {
	
		$qryArray = array();
		$sql = 'SELECT * FROM related WHERE TRUE';
		
		if (!is_null($Rel_ID)) {
			$sql .= ' AND rel_id = :rel_id ';
			$qryArray["rel_id"] = $Rel_ID;
		} else {
			if (!is_null($TblNam)) {
				$sql .= ' AND tblnam = :tblnam ';
				$qryArray["tblnam"] = $TblNam;
			}
			if (!is_null($Tbl_ID) && is_numeric($Tbl_ID)) {
				$sql .= ' AND tbl_id = :tbl_id ';
				$qryArray["tbl_id"] = $Tbl_ID;
			}
			if (!is_null($RefNam)) {
				$sql .= ' AND refnam = :refnam ';
				$qryArray["refnam"] = $RefNam;
			}
			if (!is_null($Ref_ID) && is_numeric($Ref_ID)) {
				$sql .= ' AND ref_id = :ref_id ';
				$qryArray["ref_id"] = $Ref_ID;
			}

            if (!is_null($SrtOrd)) {

                //echo '#'.$SrtOrd;

                $sql .= ' ORDER BY :srtord';
                $qryArray["srtord"] = $SrtOrd;
            }

        }

        //echo $sql;

		return $this->run($sql, $qryArray, $ReqObj);

	}

    function relatedProducts($Rel_ID = NULL, $TblNam=NULL, $Tbl_ID=NULL, $RefNam=NULL, $Ref_ID=NULL, $ReqObj=false, $bothways = false) {

        $qryArray = array();
        $sql = 'SELECT
				r.rel_id,
				r.tblnam,
				r.tbl_id,
				r.refnam,
				r.ref_id,
				r.reltyp,
				p.prtnam,
				p.seourl,
				p.prtobj,
				tp.prtnam AS thisname,
				tp.seourl AS this_seo
				FROM related r
				INNER JOIN producttypes p ON r.ref_id = p.prt_id
				INNER JOIN producttypes tp ON r.tbl_id = tp.prt_id
				WHERE TRUE';

        if (!is_null($Rel_ID)) {
            $sql .= ' AND r.rel_id = :rel_id ';
            $qryArray["rel_id"] = $Rel_ID;
        } else {



                if (!is_null($TblNam)) {
                $sql .= ' AND r.tblnam = :tblnam ';
                $qryArray["tblnam"] = $TblNam;
            }
            if (!is_null($Tbl_ID) && is_numeric($Tbl_ID)) {
                $sql .= ' AND r.tbl_id = :tbl_id ';
                $qryArray["tbl_id"] = $Tbl_ID;
            }
            if (!is_null($RefNam)) {
                $sql .= ' AND r.refnam = :refnam ';
                $qryArray["refnam"] = $RefNam;
            }



            $sql .= ' ORDER BY p.prtnam';
        }

//        echo $this->displayQuery($sql, $qryArray);
        return $this->run($sql, $qryArray, $ReqObj);

    }

    function relatedProductTypes($Rel_ID = NULL, $TblNam=NULL, $Tbl_ID=NULL, $RefNam=NULL, $Ref_ID=NULL, $ReqObj=false, $bothways = false) {

        $qryArray = array();
        $sql = 'SELECT
				r.rel_id,
				r.tblnam,
				r.tbl_id,
				r.refnam,
				r.ref_id,
				r.reltyp,
				p.prt_id,
				p.prtnam,
				p.seourl,
				p.unipri,
				tp.prtnam AS thisname,
				tp.seourl AS this_seo
				FROM related r
				INNER JOIN producttypes p ON r.ref_id = p.prt_id
				INNER JOIN producttypes tp ON r.tbl_id = tp.prt_id
				WHERE TRUE';

        if (!is_null($Rel_ID)) {
            $sql .= ' AND r.rel_id = :rel_id ';
            $qryArray["rel_id"] = $Rel_ID;
        } else {

            if ($bothways) {

                if (!is_null($TblNam)) {

                    $sql .= ' AND r.tblnam = :tblnam ';
                    $qryArray["tblnam"] = $TblNam;

                    $sql .= ' AND r.refnam = :refnam ';
                    $qryArray["refnam"] = $TblNam;


                    $prdIDs = explode(",", $Tbl_ID);

                    $sql .= ' AND (r.tbl_id IN ('.implode(",", $prdIDs).')  OR r.ref_id IN ('.implode(",", $prdIDs).')) ';

                }

            } else {

                if (!is_null($TblNam)) {
                    $sql .= ' AND r.tblnam = :tblnam ';
                    $qryArray["tblnam"] = $TblNam;
                }
                if (!is_null($Tbl_ID) && is_numeric($Tbl_ID)) {
                    $sql .= ' AND r.tbl_id = :tbl_id ';
                    $qryArray["tbl_id"] = $Tbl_ID;
                }
                if (!is_null($RefNam)) {
                    $sql .= ' AND r.refnam = :refnam ';
                    $qryArray["refnam"] = $RefNam;
                }

                if (!is_null($Ref_ID)) {

                    $prdIDs = explode(",", $Ref_ID);
                    $sql .= ' AND r.tbl_id IN ('.implode(",", $prdIDs).') ';

                }

            }

            $sql .= ' ORDER BY p.prtnam';
        }

        //echo $sql;

        return $this->run($sql, $qryArray, $ReqObj);

    }

    function structureProducts($Rel_ID = NULL, $TblNam=NULL, $Tbl_ID=NULL, $RefNam=NULL, $Ref_ID=NULL, $ReqObj=false, $bothways = false, $PagNum=NULL, $PerPag=NULL, $In_Stk=NULL) {

        $qryArray = array();
        $sql = 'SELECT
				r.rel_id,
				r.tblnam,
				r.tbl_id AS reftbl_id,
				r.refnam,
				r.ref_id,
				r.reltyp,
				p.*
				FROM related r
				INNER JOIN producttypes p ON r.tbl_id = p.prt_id ';

        if (!is_null($In_Stk)) {
            $sql .= ' INNER JOIN products prd ON prd.prt_id = p.prt_id AND in_stk > 0 ';
        } else {
            //$sql .= ' LEFT OUTER JOIN products prd ON prd.prt_id = p.prt_id ';
        }

        $sql .= ' WHERE TRUE ';

        if (!is_null($Rel_ID)) {
            $sql .= ' AND r.rel_id = :rel_id ';
            $qryArray["rel_id"] = $Rel_ID;
        } else {

            if ($bothways) {

                if (!is_null($TblNam)) {

                    $sql .= ' AND r.tblnam = :tblnam ';
                    $qryArray["tblnam"] = $TblNam;

                    $sql .= ' AND r.refnam = :refnam ';
                    $qryArray["refnam"] = $TblNam;

                    //echo '~'.$Tbl_ID.'~';

                    $prdIDs = explode(",", $Tbl_ID);

                    //var_dump($prdIDs);

                    $sql .= ' AND (r.tbl_id IN ('.implode(",", $prdIDs).')  OR r.ref_id IN ('.implode(",", $prdIDs).')) ';

                }

            } else {

                if (!is_null($TblNam)) {
                    $sql .= ' AND r.tblnam = :tblnam ';
                    $qryArray["tblnam"] = $TblNam;
                }
                if (!is_null($Tbl_ID)) {

                    //echo 'Tbl';

                    $prdIDs = explode(",", $Tbl_ID);
                    //var_dump($prdIDs);
                    $sql .= ' AND r.tbl_id IN ('.implode(",", $prdIDs).') ';

//                    $sql .= ' AND r.tbl_id = :tbl_id ';
//                    $qryArray["tbl_id"] = $Tbl_ID;
                }
                if (!is_null($RefNam)) {
                    $sql .= ' AND r.refnam = :refnam ';
                    $qryArray["refnam"] = $RefNam;
                }

                if (!is_null($Ref_ID)) {

                    //echo 'Ref';

                    $prdIDs = explode(",", $Ref_ID);
                    //var_dump($prdIDs);
                    $sql .= ' AND r.ref_id IN ('.implode(",", $prdIDs).') ';

                }

            }

            $sql .= ' ORDER BY r.srtord';
        }

        if (!is_null($PagNum) && is_numeric($PagNum) && !is_null($PerPag) && is_numeric($PerPag)) {
            $sql .= ' LIMIT '.$PagNum.', '.$PerPag;
            //$qryArray["perpag"] = $PerPag;
            //$qryArray["pagnum"] = $PagNum;
        }

        //echo '<pre>'.$sql.'</pre>';

        return $this->run($sql, $qryArray, $ReqObj);

    }

	function update($RelCls = NULL) {
	
		if (is_null($RelCls) || !$RelCls) return 'No Record To Update';
		
		$sql = '';
		
		$qryArray = array();
		
		if ($RelCls->rel_id == 0) {
						
			$qryArray["tblnam"] = $RelCls->tblnam;
			$qryArray["tbl_id"] = $RelCls->tbl_id;
			$qryArray["refnam"] = $RelCls->refnam;
			$qryArray["ref_id"] = $RelCls->ref_id;
			$qryArray["reltyp"] = $RelCls->reltyp;
			$qryArray["srtord"] = $RelCls->srtord;

			$sql = "INSERT INTO related
					(
					
					tblnam,
					tbl_id,
					refnam,
					ref_id,
					reltyp,
					srtord
					
					)
					VALUES
					(
					
					:tblnam,
					:tbl_id,
					:refnam,
					:ref_id,
					:reltyp,
					:srtord
					
					);";
					
					
						
		} else {
			
			$qryArray["tblnam"] = $RelCls->tblnam;
			$qryArray["tbl_id"] = $RelCls->tbl_id;
			$qryArray["refnam"] = $RelCls->refnam;
			$qryArray["ref_id"] = $RelCls->ref_id;
			$qryArray["reltyp"] = $RelCls->reltyp;
			$qryArray["srtord"] = $RelCls->srtord;

			$sql = "UPDATE related
					SET
					
					tblnam = :tblnam,
					tbl_id = :tbl_id,
					refnam = :refnam,
					ref_id = :ref_id,
					reltyp = :reltyp,
					srtord = :srtord";
				
			$sql .= " WHERE rel_id = :rel_id";
			$qryArray["rel_id"] = $RelCls->rel_id;
			
		}
		
		$recordSet = $this->dbConn->prepare($sql);
		$recordSet->execute($qryArray);
		
		return ($RelCls->rel_id == 0) ? $this->dbConn->lastInsertId('rel_id') : $RelCls->rel_id;
	}
	
	function delete($Rel_ID = NULL) {
	
		try {
			
			if (!is_null($Rel_ID)) {
				$qryArray = array();
				$sql = 'DELETE FROM related WHERE rel_id = :rel_id ';
				$qryArray["rel_id"] = $Rel_ID;
				
				$recordSet = $this->dbConn->prepare($sql);
				$recordSet->execute($qryArray);
				
				//
				// DELETE ATTRIBUTES
				//
				
				//
				// DELETE IMAGES
				//
				
				return $Rel_ID;
				
			}
			
		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}
		
	}
	
	function clear($TblNam=NULL, $Tbl_ID=NULL, $RefNam=NULL) {
		
		if (!is_null($TblNam) && is_numeric($Tbl_ID) && !is_null($RefNam)) {
			
			try {

				$qryArray = array();
				$sql = 'DELETE FROM related WHERE tblnam = :tblnam AND tbl_id = :tbl_id AND refnam = :refnam ';
				
				$qryArray["tblnam"] = $TblNam;
				$qryArray["tbl_id"] = $Tbl_ID;
				$qryArray["refnam"] = $RefNam;
				
				$recordSet = $this->dbConn->prepare($sql);
				$recordSet->execute($qryArray);
				
			} catch(PDOException $e) {
				echo 'ERROR: ' . $e->getMessage();
			}
		
		}
		
	}
	
}

?>