<?php

//
// Products class
//

class PrdDAO extends db {

	function select($Prd_ID = NULL, $SeoUrl=NULL, $Prt_ID=NULL, $TblNam=NULL, $Tbl_ID=NULL, $PrdNam=NULL, $SrtOrd='p.srtord', $ReqObj=false, $PagNum=NULL, $PerPag=NULL)
    {

        if (is_null($SrtOrd)) $SrtOrd = 'p.srtord';

        $qryArray = array();
        $sql = 'SELECT
				p.prd_id,
				p.tblnam,
				p.tbl_id,
				p.prt_id,
				p.prdnam,
				p.prddsc,
				p.prdspc,
				p.unipri,
				p.buypri,
				p.delpri,
				p.inspri,
				p.sup_id,
				p.atr_id,
				p.sta_id,
				a.atrnam,
				p.usestk,
				p.in_stk,
				p.on_ord,
				p.on_del,
				p.seourl,
				p.seokey,
				p.seodsc,
				p.dim1,
				p.dim2,
				p.dim3,
				p.altref,
				p.altnam,
				p.prdtag,
				p.weight,
				p.srtord,
				p.vat_id,
				p.vegan,
				p.vegetarian,
				p.gluten_free,
				prt.prtnam,
				prt.seourl AS prtseo,
				a.atrnam,
				a.seourl AS atrseo,
				s.subnam,
				s.seourl AS subseo,
				pdi.filnam AS prdimg,
				pti.filnam AS prtimg
				FROM products p 
				LEFT OUTER JOIN attribute_group a ON a.atr_id = p.atr_id
				LEFT OUTER JOIN subcategories s ON s.sub_id = a.tbl_id
				LEFT OUTER JOIN producttypes prt ON prt.prt_id = p.prt_id

				LEFT OUTER JOIN (SELECT * FROM uploads pdi ORDER BY srtord) pdi ON pdi.tblnam = "PRODUCT" AND pdi.tbl_id = p.prd_id
                LEFT OUTER JOIN (SELECT * FROM uploads pdi ORDER BY srtord) pti ON pti.tblnam = "PRDTYPE" AND pti.tbl_id = p.prt_id

				WHERE TRUE';

        //LEFT OUTER JOIN uploads pdi ON pdi.tblnam = "PRODUCT" AND pdi.tbl_id = p.prd_id
        //LEFT OUTER JOIN uploads pti ON pti.tblnam = "PRDTYPE" AND pti.tbl_id = p.prt_id

        if (!is_null($Prd_ID)) {
            $sql .= ' AND p.prd_id = :prd_id ';
            $qryArray["prd_id"] = $Prd_ID;
        } else {
            if (!is_null($SeoUrl)) {
                $sql .= ' AND p.seourl = :seourl ';
                $qryArray["seourl"] = $SeoUrl;
            }
            if (!is_null($Prt_ID) && is_numeric($Prt_ID)) {
                $sql .= ' AND p.prt_id = :prt_id ';
                $qryArray["prt_id"] = $Prt_ID;
            }
            if (!is_null($TblNam)) {
                $sql .= ' AND p.tblnam = :tblnam ';
                $qryArray["tblnam"] = $TblNam;
            }
            if (!is_null($Tbl_ID) && is_numeric($Tbl_ID)) {
                $sql .= ' AND p.tbl_id = :tbl_id ';
                $qryArray["tbl_id"] = $Tbl_ID;
            }
            if (!is_null($PrdNam)) {
                $PrdNam = '%' . $PrdNam . '%';
                $sql .= ' AND p.prdnam LIKE :prdnam ';
                $qryArray["prdnam"] = $PrdNam;
            }
        }


        $sql .= ' GROUP BY p.prd_id'; // ORDER BY altnam, unipri DESC ';

        if (!is_null($SrtOrd)) {
            //$sql .= ' ORDER BY :srtord';
            //$qryArray["srtord"] = $SrtOrd;

            $sql .= ' ORDER BY '.stripslashes($SrtOrd);
        }

        if (!is_null($PagNum) && is_numeric($PagNum) && !is_null($PerPag) && is_numeric($PerPag)) {
            $sql .= ' LIMIT '.$PagNum.', '.$PerPag;
            //$qryArray["perpag"] = $PerPag;
            //$qryArray["pagnum"] = $PagNum;
        }

		//$sql .= ' LIMIT 7000, 2000';

		//echo $sql;
		//print_r($qryArray);

		return $this->run($sql, $qryArray, $ReqObj);

	}


    function selectLight($Prd_ID = NULL, $SeoUrl=NULL, $Prt_ID=NULL, $TblNam=NULL, $Tbl_ID=NULL, $PrdNam=NULL, $SrtOrd='p.srtord', $ReqObj=false, $PagNum=NULL, $PerPag=NULL)
    {

        if (is_null($SrtOrd)) $SrtOrd = 'p.srtord';

        $qryArray = array();
        $sql = 'SELECT
				p.prd_id,
				p.prdnam,
				prt.prtnam
				FROM products p
				LEFT OUTER JOIN producttypes prt ON prt.prt_id = p.prt_id
				WHERE TRUE';


        if (!is_null($Prd_ID)) {
            $sql .= ' AND p.prd_id = :prd_id ';
            $qryArray["prd_id"] = $Prd_ID;
        } else {
            if (!is_null($SeoUrl)) {
                $sql .= ' AND p.seourl = :seourl ';
                $qryArray["seourl"] = $SeoUrl;
            }
            if (!is_null($Prt_ID) && is_numeric($Prt_ID)) {
                $sql .= ' AND p.prt_id = :prt_id ';
                $qryArray["prt_id"] = $Prt_ID;
            }
            if (!is_null($TblNam)) {
                $sql .= ' AND p.tblnam = :tblnam ';
                $qryArray["tblnam"] = $TblNam;
            }
            if (!is_null($Tbl_ID) && is_numeric($Tbl_ID)) {
                $sql .= ' AND p.tbl_id = :tbl_id ';
                $qryArray["tbl_id"] = $Tbl_ID;
            }
            if (!is_null($PrdNam)) {
                $PrdNam = '%' . $PrdNam . '%';
                $sql .= ' AND (p.prdnam LIKE :prdnam ';
                $qryArray["prdnam"] = $PrdNam;

                $sql .= ' OR p.altref LIKE :altref ) ';
                $qryArray["altref"] = $PrdNam;
            }
        }


        $sql .= ' GROUP BY p.prd_id'; // ORDER BY altnam, unipri DESC ';

        if (!is_null($SrtOrd)) {
            //$sql .= ' ORDER BY :srtord';
            //$qryArray["srtord"] = $SrtOrd;

            $sql .= ' ORDER BY '.stripslashes($SrtOrd);
        }

        if (!is_null($PagNum) && is_numeric($PagNum) && !is_null($PerPag) && is_numeric($PerPag)) {
            $sql .= ' LIMIT '.$PagNum.', '.$PerPag;
            //$qryArray["perpag"] = $PerPag;
            //$qryArray["pagnum"] = $PagNum;
        }

        //$sql .= ' LIMIT 7000, 2000';

        //echo $sql;
        //print_r($qryArray);

        return $this->run($sql, $qryArray, $ReqObj);

    }



    function checkProductName($Prd_ID = NULL, $PrdNam=NULL)
    {

        $qryArray = array();
        $sql = 'SELECT
				*
				FROM products WHERE prdnam = :prdnam';
        $qryArray["prdnam"] = $PrdNam;

        if (!is_null($Prd_ID)) {
            $sql .= ' AND prd_id != :prd_id ';
            $qryArray["prd_id"] = $Prd_ID;
        }

        return $this->run($sql, $qryArray, true);

    }


    function importCheck($Prd_ID = NULL, $SeoUrl=NULL, $AltRef=NULL)
    {

        $qryArray = array();
        $sql = 'SELECT
				*
				FROM products
				WHERE altref = "'.addslashes($AltRef).'"';
				//'WHERE altref REGEXP "[[:<:]]'.$AltRef.'[[:>:]]"';

        //$qryArray['altref'] = '[[:<:]]'.$AltRef.'[[:>:]]';

        return $this->run($sql, $qryArray, false);

    }

    function checkSEO($Prd_ID=NULL, $SeoUrl=NULL)
    {

        $qryArray = array();
        $sql = 'SELECT
				*
				FROM products
				WHERE seourl = :seourl
                AND prd_id != :prd_id';

        $qryArray['prd_id'] = $Prd_ID;
        $qryArray['seourl'] = $SeoUrl;

        return $this->run($sql, $qryArray, false);

    }


	function selectByIDs($Prd_ID = NULL, $Atr_ID=NULL, $PerPag=NULL, $Pag_No=NULL, $SrtOrd = 'p.unipri ASC') {

        $OffSet=NULL;
        if (isset($PerPag) && isset($Pag_No) && is_numeric($PerPag) && is_numeric($Pag_No)) $OffSet = ($Pag_No-1) * $PerPag;

		$qryArray = array();
		$sql = 'SELECT
				p.prd_id,
				p.tblnam,
				p.tbl_id,
				p.prt_id,
				p.prdnam,
				p.prddsc,
				p.prdspc,
				p.unipri,
				p.buypri,
				p.delpri,
				p.sup_id,
				p.atr_id,
				p.sta_id,
				a.atrnam,
				p.usestk,
				p.in_stk,
				p.on_ord,
				p.on_del,
				p.seourl,
				p.seokey,
				p.seodsc,
				p.prdtag,
				p.weight,
				p.srtord,
				p.vat_id,
				p.vegan,
				p.vegetarian,
				p.gluten_free,
				prt.prtnam,
				prt.seourl AS prtseo,
				pdi.filnam AS prdimg,
				pti.filnam AS prtimg
				FROM products p 
				LEFT OUTER JOIN attribute_group a ON a.atr_id = p.atr_id
				LEFT OUTER JOIN producttypes prt ON prt.prt_id = p.prt_id
				LEFT OUTER JOIN uploads pdi ON pdi.tblnam = "PRODUCT" AND pdi.tbl_id = p.prd_id
				LEFT OUTER JOIN uploads pti ON pti.tblnam = "PRDTYPE" AND pti.tbl_id = p.prt_id
				WHERE TRUE';

		if (!empty($Atr_ID)) {
			$sql .= ' AND p.atr_id = '.$Atr_ID.' ';
			$qryArray["atr_id"] = $Atr_ID;
		}

		if (!empty($Prd_ID)) {
			$sql .= ' AND p.prd_id IN ('.$Prd_ID.') ';
			$qryArray["prd_id"] = $Prd_ID;
		}

		$sql .= ' GROUP BY p.prd_id';

		if (!is_null($SrtOrd)) {
            $sql .= ' ORDER BY '.stripslashes($SrtOrd);
		} else {
			$sql .= ' ORDER BY p.prd_id ';
		}

        if (!is_null($OffSet) && is_numeric($OffSet) && !is_null($PerPag) && is_numeric($PerPag)) {
            $sql .= ' LIMIT '.$OffSet.' , '.$PerPag;
        }


        //echo $sql;

		return $this->run($sql, $qryArray, false);

	}


	function searchProducts($Atr_ID=NULL, $PerPag=NULL, $Pag_No=NULL, $SrtOrd='p.srtord') {


		$OffSet=NULL;
		if (isset($PerPag) && isset($Pag_No) && is_numeric($PerPag) && is_numeric($Pag_No)) $OffSet = ($Pag_No-1) * $PerPag;

		$qryArray = array();
		$sql = 'SELECT
				p.*,
				a.atrnam,
				prt.prtnam,
				prt.seourl AS prtseo,
				pdi.filnam AS prdimg,
				pti.filnam AS prtimg
				FROM products p 
				LEFT OUTER JOIN attribute_group a ON a.atr_id = p.atr_id
				LEFT OUTER JOIN producttypes prt ON prt.prt_id = p.prt_id
				LEFT OUTER JOIN uploads pdi ON pdi.tblnam = "PRODUCT" AND pdi.tbl_id = p.prd_id
				LEFT OUTER JOIN uploads pti ON pti.tblnam = "PRDTYPE" AND pti.tbl_id = p.prt_id
				WHERE TRUE';

		if (!is_null($Atr_ID) && is_numeric($Atr_ID)) {
			$sql .= ' AND p.atr_id = :atr_id ';
			$qryArray["atr_id"] = $Atr_ID;
		}

		$sql .= ' GROUP BY p.prd_id';

		$sql .= ' ORDER BY '.$SrtOrd;

		if (!is_null($OffSet) && is_numeric($OffSet) && !is_null($PerPag) && is_numeric($PerPag)) {
			$sql .= ' LIMIT '.$OffSet.' , '.$PerPag;
		}

		//echo $sql.' '.$Atr_ID.'<br>';

		return $this->run($sql, $qryArray, false);

	}

	function searchProductsByCategory($PrdTag=NULL, $PerPag=NULL, $Pag_No=NULL, $SrtOrd='srtord') {


		$OffSet=NULL;
		if (isset($PerPag) && isset($Pag_No) && is_numeric($PerPag) && is_numeric($Pag_No)) $OffSet = ($Pag_No-1) * $PerPag;

		$qryArray = array();
		$sql = 'SELECT
				p.*,
				prt.prtnam,
				prt.seourl AS prtseo,
				p.prdtag,
				a.seourl AS atrseo
				FROM products p 
				LEFT OUTER JOIN attribute_group a ON a.atr_id = p.atr_id
				LEFT OUTER JOIN producttypes prt ON prt.prt_id = p.prt_id
				WHERE TRUE';

		if (!is_null($PrdTag) && !empty($PrdTag)) {

            //$sql .= ' AND (';

            $prdTagArr = explode(",",$PrdTag);

            for ($i=0;$i<count($prdTagArr);$i++) {
                $sql .= ' AND p.prdtag RLIKE :prdtag'.$i.' ';
                $qryArray["prdtag".$i] = '[[:<:]]' . $prdTagArr[$i]. '[[:>:]]';
            }

            //$sql .= ' ) ';
		}

		$sql .= ' GROUP BY p.prd_id';

		if (!is_null($SrtOrd)) {
			$sql .= ' ORDER BY '.$SrtOrd;
		}

		if (!is_null($OffSet) && is_numeric($OffSet) && !is_null($PerPag) && is_numeric($PerPag)) {
			$sql .= ' LIMIT '.$OffSet.' , '.$PerPag;
		}

		//echo $sql;
        //print_r($qryArray);

		return $this->run($sql, $qryArray, false);

	}

	function update($PrdCls = NULL) {


		if (is_null($PrdCls) || !$PrdCls) return 'No Record To Update';

		$sql = '';

		$qryArray = array();

		if ($PrdCls->prd_id == 0) {

			$qryArray["tblnam"] = $PrdCls->tblnam;
			$qryArray["tbl_id"] = $PrdCls->tbl_id;
			$qryArray["prt_id"] = $PrdCls->prt_id;
			$qryArray["prdnam"] = $PrdCls->prdnam;
			$qryArray["prddsc"] = $PrdCls->prddsc;
			$qryArray["prdspc"] = $PrdCls->prdspc;
			$qryArray["unipri"] = $PrdCls->unipri;
			$qryArray["buypri"] = $PrdCls->buypri;
			$qryArray["delpri"] = $PrdCls->delpri;
			$qryArray["sup_id"] = $PrdCls->sup_id;
			$qryArray["atr_id"] = $PrdCls->atr_id;
			$qryArray["sta_id"] = $PrdCls->sta_id;

			$qryArray["usestk"] = $PrdCls->usestk;
			$qryArray["in_stk"] = $PrdCls->in_stk;
			$qryArray["on_ord"] = $PrdCls->on_ord;
			$qryArray["on_del"] = $PrdCls->on_del;

			$qryArray["seourl"] = $PrdCls->seourl;
			$qryArray["seokey"] = $PrdCls->seokey;
			$qryArray["seodsc"] = $PrdCls->seodsc;

			$qryArray["prdtag"] = $PrdCls->prdtag;

            $qryArray["altref"] = $PrdCls->altref;
            $qryArray["altnam"] = $PrdCls->altnam;

            $qryArray["weight"] = $PrdCls->weight;
            $qryArray["srtord"] = $PrdCls->srtord;

            $qryArray["vat_id"] = $PrdCls->vat_id;
            $qryArray["vegan"] = $PrdCls->vegan;
            $qryArray["vegetarian"] = $PrdCls->vegetarian;
            $qryArray["gluten_free"] = $PrdCls->gluten_free;



			$sql = "INSERT INTO products
					(
					
					tblnam,
					tbl_id,
					prt_id,
					prdnam,
					prddsc,
					prdspc,
					unipri,
					buypri,
					delpri,
					sup_id,
					atr_id,
					sta_id,
					usestk,
					in_stk,
					on_ord,
					on_del,
					seourl,
					seokey,
					seodsc,
					prdtag,
					altref,
					altnam,
					weight,
					srtord,
					vat_id,
                    vegan,
                    vegetarian,
                    gluten_free
					)
					VALUES
					(
					
					:tblnam,
					:tbl_id,
					:prt_id,
					:prdnam,
					:prddsc,
					:prdspc,
					:unipri,
					:buypri,
					:delpri,
					:sup_id,
					:atr_id,
					:sta_id,
					:usestk,
					:in_stk,
					:on_ord,
					:on_del,
					:seourl,
					:seokey,
					:seodsc,
					:prdtag,
					:altref,
					:altnam,
					:weight,
					:srtord,
					:vat_id,
					:vegan,
                    :vegetarian,
                    :gluten_free
					
					
					);";

		} else {

			$qryArray["tblnam"] = $PrdCls->tblnam;
			$qryArray["tbl_id"] = $PrdCls->tbl_id;
			$qryArray["prt_id"] = $PrdCls->prt_id;
			$qryArray["prdnam"] = $PrdCls->prdnam;
			$qryArray["prddsc"] = $PrdCls->prddsc;
			$qryArray["prdspc"] = $PrdCls->prdspc;
			$qryArray["unipri"] = $PrdCls->unipri;
			$qryArray["buypri"] = $PrdCls->buypri;
			$qryArray["delpri"] = $PrdCls->delpri;
			$qryArray["sup_id"] = $PrdCls->sup_id;
			$qryArray["atr_id"] = $PrdCls->atr_id;
			$qryArray["sta_id"] = $PrdCls->sta_id;

			$qryArray["usestk"] = $PrdCls->usestk;
			$qryArray["in_stk"] = $PrdCls->in_stk;
			$qryArray["on_ord"] = $PrdCls->on_ord;
			$qryArray["on_del"] = $PrdCls->on_del;

			$qryArray["seourl"] = $PrdCls->seourl;
			$qryArray["seokey"] = $PrdCls->seokey;
			$qryArray["seodsc"] = $PrdCls->seodsc;

			$qryArray["prdtag"] = $PrdCls->prdtag;

            $qryArray["altref"] = $PrdCls->altref;
            $qryArray["altnam"] = $PrdCls->altnam;

            $qryArray["weight"] = $PrdCls->weight;
            $qryArray["srtord"] = $PrdCls->srtord;

            $qryArray["vat_id"] = $PrdCls->vat_id;
            $qryArray["vegan"] = $PrdCls->vegan;
            $qryArray["vegetarian"] = $PrdCls->vegetarian;
            $qryArray["gluten_free"] = $PrdCls->gluten_free;

			$sql = "UPDATE products
					SET
					
					tblnam = :tblnam,
					tbl_id = :tbl_id,
					prt_id = :prt_id,
					prdnam = :prdnam,
					prddsc = :prddsc,
					prdspc = :prdspc,
					unipri = :unipri,
					buypri = :buypri,
					delpri = :delpri,
					sup_id = :sup_id,
					atr_id = :atr_id,
					sta_id = :sta_id,
					usestk = :usestk,
					in_stk = :in_stk,
					on_ord = :on_ord,
					on_del = :on_del,
					seourl = :seourl,
					seokey = :seokey,
					seodsc = :seodsc,
					prdtag = :prdtag,
					altref = :altref,
					altnam = :altnam,
					weight = :weight,
					srtord = :srtord,
					vat_id = :vat_id,
					vegan = :vegan,
					vegetarian = :vegetarian,
					gluten_free = :gluten_free";

			$sql .= " WHERE prd_id = :prd_id";
			$qryArray["prd_id"] = $PrdCls->prd_id;

		}

        //echo $sql.'<br>';
		//print_r($qryArray);

		$recordSet = $this->dbConn->prepare($sql);
		$recordSet->execute($qryArray);

		return ($PrdCls->prd_id == 0) ? $this->dbConn->lastInsertId('prd_id') : $PrdCls->prd_id;
	}

	function delete($Prd_ID = NULL) {

		try {

			if (!is_null($Prd_ID)) {


                //
                // FIND PRODUCT IN ORDERS
                //

                $qryArray = array();
                $sql = 'SELECT * FROM orderline WHERE prd_id = :prd_id LIMIT 1';
                $qryArray["prd_id"] = $Prd_ID;

                $orderLines = $this->run($sql, $qryArray, false);

                if (count($orderLines) > 0) {

                    return 0;

                } else {
                    $qryArray = array();
                    $sql = 'DELETE FROM products WHERE prd_id = :prd_id ';
                    $qryArray["prd_id"] = $Prd_ID;

                    $recordSet = $this->dbConn->prepare($sql);
                    $recordSet->execute($qryArray);

                    //
                    // DELETE ATTRIBUTES
                    //

                    //
                    // DELETE IMAGES
                    //

                    $qryArray = array();
                    $sql = 'DELETE FROM uploads WHERE tblnam = "PRODUCT" AND tbl_id = :prd_id ';
                    $qryArray["prd_id"] = $Prd_ID;

                    $recordSet = $this->dbConn->prepare($sql);
                    $recordSet->execute($qryArray);

                    //
                    // DELETE RELATED
                    //

                    $qryArray = array();
                    $sql = 'DELETE FROM related WHERE tblnam = "PRODUCT" AND refnam = "PRODUCT" AND (tbl_id = :prd_id OR ref_id = :ref_id)';
                    $qryArray["prd_id"] = $Prd_ID;
                    $qryArray["ref_id"] = $Prd_ID;

                    $recordSet = $this->dbConn->prepare($sql);
                    $recordSet->execute($qryArray);

                    return $Prd_ID;

                }

			}

		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}

	}

    function updateLeadTime ($days) {

        $sql = "UPDATE products SET on_ord = (on_ord + :days)";
        $qryArray["days"] = $days;

        $recordSet = $this->dbConn->prepare($sql);
        $recordSet->execute($qryArray);

    }

}

?>