<?php

//
// Bskegories class
//

class BskDAO extends db {

    function select($Bsk_ID = NULL, $BskNam = NULL, $PerPag = NULL, $PagNum = NULL, $ReqObj = false,  $show_bypass = NULL) {

        $qryArray = array();
        $sql = 'SELECT
				*
				FROM baskets WHERE true';

        if (!is_null($Bsk_ID)) {
            $sql .= ' AND bsk_id = :bsk_id ';
            $qryArray["bsk_id"] = $Bsk_ID;
        } else {

            if (!is_null($BskNam)) {
                $BskNam = '%'.$BskNam.'%';
                $sql .= ' AND bskttl LIKE :bskttl ';
                $qryArray["bskttl"] = $BskNam;
            }
        }
        if($show_bypass){
            $sql .= ' AND bypass_min_order != 1';
        }
        $sql .= ' ORDER BY unipri ASC';

        //print_r($qryArray);
        //echo $sql;

        return $this->run($sql, $qryArray, $ReqObj);

    }


    function checkName($Bsk_ID=NULL, $BskNam=NULL, $SeoUrl=NULL) {

        $qryArray = array();
        $sql = 'SELECT * FROM baskets WHERE true ';

        if (!is_null($BskNam)) {
            $sql .= ' AND (bskttl = :bskttl OR seourl = :seourl) ';
            $qryArray["bskttl"] = $BskNam;
            $qryArray["seourl"] = $SeoUrl;
        }

        if (is_numeric($Bsk_ID)) {
            $sql .= ' AND bsk_id != :bsk_id ';
            $qryArray["bsk_id"] = $Bsk_ID;
        }

        return $this->run($sql, $qryArray, false);

    }


    function selectByTag($BskTag = NULL, $PerPag = NULL, $PagNum = NULL, $ReqObj = false, $show_bypass = NULL) {


        $OffSet=NULL;
        if (isset($PerPag) && isset($Pag_No) && is_numeric($PerPag) && is_numeric($Pag_No)) $OffSet = ($Pag_No-1) * $PerPag;

        $qryArray = array();
        $sql = 'SELECT
				*
				FROM baskets WHERE true';

        if($show_bypass){
            $sql .= ' AND bypass_min_order != "1"';
        }

        if (!is_null($BskTag)) {

            $sql .= ' AND bsktag RLIKE :bsktag ';
            $qryArray["bsktag"] = '[[:<:]]'.$BskTag.'[[:>:]]';

        }

        if (!is_null($OffSet) && is_numeric($OffSet) && !is_null($PerPag) && is_numeric($PerPag)) {
            $sql .= ' LIMIT '.$OffSet.' , '.$PerPag;
        } else {

        }



        $sql .= ' ORDER BY unipri ASC';

        //print_r($qryArray);
//        echo $sql;

        return $this->run($sql, $qryArray, $ReqObj);

    }


    function selectProducts($Bsk_ID = NULL) {

        if (!is_null($Bsk_ID)) {

            $qryArray = array();
            $sql = 'SELECT b.*,
                    p.prdnam,
                    p.unipri,
                    p.vegan,
                    p.vegetarian,
                    p.gluten_free,
                    a.atr_id,
                    a.atrnam,
                    s.subnam,
                    s.sub_id,
                    pdi.filnam AS prdimg
                    FROM basketproducts b
                    INNER JOIN products p ON p.prd_id = b.prd_id

                    LEFT OUTER JOIN attribute_group a ON a.atr_id = p.atr_id
                    LEFT OUTER JOIN subcategories s ON s.sub_id = a.tbl_id
                    LEFT OUTER JOIN (SELECT * FROM uploads pdi ORDER BY srtord) pdi ON pdi.tblnam = "PRODUCT" AND pdi.tbl_id = p.prd_id

                    WHERE bsk_id = :bsk_id

                    ORDER BY b.defsel, s.srtord, s.subnam';

            $qryArray["bsk_id"] = $Bsk_ID;

            return $this->run($sql, $qryArray, false);

        }
    }

    function selectSingleProduct($Bpr_ID = NULL) {

        if (!is_null($Bpr_ID)) {

            $qryArray = array();
            $sql = 'SELECT b.*,
                    p.prdnam,
                    p.unipri,
                    a.atr_id,
                    a.atrnam,
                    s.subnam,
                    s.sub_id,
                       p.vegan,
                    p.vegetarian,
                    p.gluten_free,
                    pdi.filnam AS prdimg
                    FROM basketproducts b
                    INNER JOIN products p ON p.prd_id = b.prd_id

                    LEFT OUTER JOIN attribute_group a ON a.atr_id = p.atr_id
                    LEFT OUTER JOIN subcategories s ON s.sub_id = a.tbl_id
                    LEFT OUTER JOIN (SELECT * FROM uploads pdi ORDER BY srtord) pdi ON pdi.tblnam = "PRODUCT" AND pdi.tbl_id = p.prd_id

                    WHERE bpr_id = :bpr_id';

            //echo $sql;

            $qryArray["bpr_id"] = $Bpr_ID;
            return $this->run($sql, $qryArray, true);

        }
    }

    function selectBasketExtra($Bex_ID = NULL) {

        if (!is_null($Bex_ID)) {

            $qryArray = array();
            $sql = 'SELECT *
                    FROM basketextras
                    WHERE bex_id = :bex_id';

            //echo $sql;

            $qryArray["bex_id"] = $Bex_ID;
            return $this->run($sql, $qryArray, true);

        }
    }


    function selectExtras($Bsk_ID = NULL) {

        if (!is_null($Bsk_ID)) {

            $qryArray = array();
            $sql = 'SELECT *
                    FROM basketextras
                    WHERE bsk_id = :bsk_id
                    ORDER BY srtord';

            $qryArray["bsk_id"] = $Bsk_ID;
            return $this->run($sql, $qryArray, false);

        }
    }

    function update($BskCls = NULL) {

        if (is_null($BskCls) || !$BskCls) return 'No Record To Update';

        $sql = '';

        $qryArray = array();

        if ($BskCls->bsk_id == 0) {

            $qryArray["bskttl"] = $BskCls->bskttl;
            $qryArray["bskdsc"] = $BskCls->bskdsc;
            $qryArray["unipri"] = $BskCls->unipri;
            $qryArray["mrk_up"] = $BskCls->mrk_up;
            $qryArray["bskimg"] = $BskCls->bskimg;
            $qryArray["custom"] = $BskCls->custom;
            $qryArray["sta_id"] = $BskCls->sta_id;
            $qryArray["srtord"] = $BskCls->srtord;
            $qryArray["bsktxt"] = $BskCls->bsktxt;
            $qryArray["seourl"] = $BskCls->seourl;
            $qryArray["keywrd"] = $BskCls->keywrd;
            $qryArray["keydsc"] = $BskCls->keydsc;
            $qryArray["atr_id"] = $BskCls->atr_id;
            $qryArray["bsktag"] = $BskCls->bsktag;
            $qryArray["customtext"] = $BskCls->customtext;

            $qryArray["weight"] = $BskCls->weight;
            $qryArray["vatrat"] = $BskCls->vatrat;

            $qryArray["riblbl"] = $BskCls->riblbl;
            $qryArray["ribcol"] = $BskCls->ribcol;
            $qryArray["minord"] = $BskCls->minord;
            $qryArray["bypass_min_order"] = $BskCls->bypass_min_order;

            $sql = "INSERT INTO baskets
					(
                    bskttl,
                    bskdsc,
                    unipri,
                    mrk_up,
                    bskimg,
                    custom,
                    sta_id,
                    srtord,
                    bsktxt,
                    seourl,
                    keywrd,
                    keydsc,
                    atr_id,
                    bsktag,
                    weight,
                    vatrat,
                    riblbl,
                    ribcol,
                    customtext,
                    minord,
                    bypass_min_order
					)
					VALUES
					(
                    :bskttl,
                    :bskdsc,
                    :unipri,
                    :mrk_up,
                    :bskimg,
                    :custom,
                    :sta_id,
                    :srtord,
                    :bsktxt,
                    :seourl,
                    :keywrd,
                    :keydsc,
                    :atr_id,
                    :bsktag,
                    :weight,
                    :vatrat,
                    :riblbl,
                    :ribcol,
                    :customtext,
                    :minord,
                    :bypass_min_order
					);";

        } else {

            $qryArray["bskttl"] = $BskCls->bskttl;
            $qryArray["bskdsc"] = $BskCls->bskdsc;
            $qryArray["unipri"] = $BskCls->unipri;
            $qryArray["mrk_up"] = $BskCls->mrk_up;
            $qryArray["bskimg"] = $BskCls->bskimg;
            $qryArray["custom"] = $BskCls->custom;
            $qryArray["sta_id"] = $BskCls->sta_id;
            $qryArray["srtord"] = $BskCls->srtord;
            $qryArray["bsktxt"] = $BskCls->bsktxt;
            $qryArray["seourl"] = $BskCls->seourl;
            $qryArray["keywrd"] = $BskCls->keywrd;
            $qryArray["keydsc"] = $BskCls->keydsc;
            $qryArray["customtext"] = $BskCls->customtext;
            $qryArray["atr_id"] = $BskCls->atr_id;
            $qryArray["bsktag"] = $BskCls->bsktag;

            $qryArray["weight"] = $BskCls->weight;
            $qryArray["vatrat"] = $BskCls->vatrat;

            $qryArray["riblbl"] = $BskCls->riblbl;
            $qryArray["ribcol"] = $BskCls->ribcol;
            $qryArray["minord"] = $BskCls->minord;
            $qryArray["bypass_min_order"] = $BskCls->bypass_min_order;

            $sql = "UPDATE baskets
					SET
				    bskttl = :bskttl,
                    bskdsc = :bskdsc,
                    unipri = :unipri,
                    mrk_up = :mrk_up,
                    bskimg = :bskimg,
                    custom = :custom,
                    sta_id = :sta_id,
                    srtord = :srtord,
                    bsktxt = :bsktxt,
                    seourl = :seourl,
                    keywrd = :keywrd,
                    keydsc = :keydsc,
                    atr_id = :atr_id,
                    bsktag = :bsktag,
                    weight = :weight,
                    vatrat = :vatrat,
                    riblbl = :riblbl,
                    ribcol = :ribcol,
                    customtext = :customtext,
                    minord = :minord,
                    bypass_min_order = :bypass_min_order
                    
                    ";

            $sql .= " WHERE bsk_id = :bsk_id";
            $qryArray["bsk_id"] = $BskCls->bsk_id;

        }

        //echo $sql;
        //print_r($qryArray);

        $recordSet = $this->dbConn->prepare($sql);
        $recordSet->execute($qryArray);

        $Bsk_ID = ($BskCls->bsk_id == 0) ? $this->dbConn->lastInsertId('bsk_id') : $BskCls->bsk_id;


//        //
//        // Basket Products
//        //
//
//        try {
//
//            $qryArray = array();
//            $sql = 'DELETE FROM basketproducts WHERE bsk_id = :bsk_id ';
//
//            $qryArray["bsk_id"] = $Bsk_ID;
//
//            $recordSet = $this->dbConn->prepare($sql);
//            $recordSet->execute($qryArray);
//
//        } catch(PDOException $e) {
//            echo 'ERROR: ' . $e->getMessage();
//        }
//
//
//        if (isset($BskCls->products) && is_array($BskCls->products)) {
//
//            for ($i = 0; $i < count($BskCls->products); $i++) {
//
//                $qryArray = array();
//                $qryArray["bsk_id"] = $Bsk_ID;
//                $qryArray["prd_id"] = $BskCls->products[$i]['prd_id'];
//                $qryArray["srtord"] = $i;
//                $qryArray["defsel"] = ($BskCls->products[$i]['defsel'] == 'true') ? 1 : 0;
//                $qryArray["bprext"] = ($BskCls->products[$i]['bprext'] == 'true') ? 1 : 0;
//                $qryArray["bprman"] = ($BskCls->products[$i]['bprman'] == 'true') ? 1 : 0;
//                $qryArray["extpri"] = $BskCls->products[$i]['extpri'];
//                $qryArray["exttxt"] = $BskCls->products[$i]['exttxt'];
//
//                $sql = "INSERT INTO basketproducts
//					(
//                    bsk_id,
//                    prd_id,
//                    srtord,
//                    defsel,
//                    bprext,
//                    bprman,
//                    extpri,
//                    exttxt
//					)
//					VALUES
//					(
//                    :bsk_id,
//                    :prd_id,
//                    :srtord,
//                    :defsel,
//                    :bprext,
//                    :bprman,
//                    :extpri,
//                    :exttxt
//					);";
//
//                $recordSet = $this->dbConn->prepare($sql);
//                $recordSet->execute($qryArray);
//
//            }
//        }


        //
        // Basket Products
        //

        try {

            $qryArray = array();
            $sql = 'DELETE FROM basketextras WHERE bsk_id = :bsk_id ';

            $qryArray["bsk_id"] = $Bsk_ID;

            $recordSet = $this->dbConn->prepare($sql);
            $recordSet->execute($qryArray);

        } catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }


        if (isset($BskCls->extras) && is_array($BskCls->extras)) {
            for ($i = 0; $i < count($BskCls->extras); $i++) {

                $qryArray = array();
                $qryArray["bsk_id"] = $Bsk_ID;
                $qryArray["bexttl"] = $BskCls->extras[$i]['bexttl'];
                $qryArray["bextxt"] = $BskCls->extras[$i]['bexttl'];
                $qryArray["bexpri"] = $BskCls->extras[$i]['bexpri'];
                $qryArray["bexdef"] = ($BskCls->extras[$i]['bexdef'] == 'true') ? 1 : 0;
                $qryArray["bexman"] = ($BskCls->extras[$i]['bexman'] == 'true') ? 1 : 0;
                $qryArray["srtord"] = $BskCls->extras[$i]['srtord'];

                $sql = "INSERT INTO basketextras
					(
                    bsk_id,
                    bexttl,
                    bextxt,
                    bexpri,
                    bexdef,
                    bexman,
                    srtord
					)
					VALUES
					(
                    :bsk_id,
                    :bexttl,
                    :bextxt,
                    :bexpri,
                    :bexdef,
                    :bexman,
                    :srtord
					);";

                $recordSet = $this->dbConn->prepare($sql);
                $recordSet->execute($qryArray);

            }
        }

        return $Bsk_ID;

    }

    function delete($Bsk_ID = NULL) {

        try {

            if (!is_null($Bsk_ID)) {
                $qryArray = array();
                $sql = 'DELETE FROM baskets WHERE bsk_id = :bsk_id ';
                $qryArray["bsk_id"] = $Bsk_ID;

                $recordSet = $this->dbConn->prepare($sql);
                $recordSet->execute($qryArray);

                $qryArray = array();
                $sql = 'DELETE FROM basketproductgroups WHERE bsk_id = :bsk_id ';
                $qryArray["bsk_id"] = $Bsk_ID;

                $recordSet = $this->dbConn->prepare($sql);
                $recordSet->execute($qryArray);

                $qryArray = array();
                $sql = 'DELETE FROM basketproducts WHERE bsk_id = :bsk_id ';
                $qryArray["bsk_id"] = $Bsk_ID;

                $recordSet = $this->dbConn->prepare($sql);
                $recordSet->execute($qryArray);

                $qryArray = array();
                $sql = 'DELETE FROM basketextras WHERE bsk_id = :bsk_id ';
                $qryArray["bsk_id"] = $Bsk_ID;

                $recordSet = $this->dbConn->prepare($sql);
                $recordSet->execute($qryArray);

                return $Bsk_ID;

            }

        } catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }

    }

}




class BprDAO extends db
{

    function select($Bpr_ID = NULL, $Bsk_ID = NULL, $Bpg_ID = NULL, $ReqObj = false)
    {

        $qryArray = array();
        $sql = 'SELECT
				b.*,
				p.prdnam,
                p.unipri,
                a.atr_id,
                a.atrnam,
                s.subnam,
                s.sub_id,
                   p.vegan,
                    p.vegetarian,
                    p.gluten_free,
                pdi.filnam AS prdimg
                FROM basketproducts b
                INNER JOIN products p ON p.prd_id = b.prd_id

                LEFT OUTER JOIN attribute_group a ON a.atr_id = p.atr_id
                LEFT OUTER JOIN subcategories s ON s.sub_id = a.tbl_id
                LEFT OUTER JOIN (SELECT * FROM uploads pdi ORDER BY srtord) pdi ON pdi.tblnam = "PRODUCT" AND pdi.tbl_id = p.prd_id

                WHERE true ';

        if (!is_null($Bpr_ID)) {
            $sql .= ' AND b.bpr_id = :bpr_id ';
            $qryArray["bpr_id"] = $Bpr_ID;
        } else {

            if (!is_null($Bsk_ID)) {
                $sql .= ' AND b.bsk_id = :bsk_id ';
                $qryArray["bsk_id"] = $Bsk_ID;
            }

            if (!is_null($Bpg_ID)) {
                $sql .= ' AND b.bpg_id = :bpg_id ';
                $qryArray["bpg_id"] = $Bpg_ID;
            }

        }

//        print_r($qryArray);
//        echo $sql;

        return $this->run($sql, $qryArray, $ReqObj);

    }

    function checkBasketProduct( $Bsk_ID=NULL, $Prd_ID=NULL ) {

        $qryArray = array();
        $sql = 'SELECT
				p.*,
				b.bskttl
                FROM basketproducts p
                INNER JOIN baskets b ON b.bsk_id = p.bsk_id
                WHERE true ';

        if (!is_null($Bsk_ID)) {
            $sql .= ' AND p.bsk_id = :bsk_id ';
            $qryArray["bsk_id"] = $Bsk_ID;
        }

        if (!is_null($Prd_ID)) {
            $sql .= ' AND p.prd_id = :prd_id ';
            $qryArray["prd_id"] = $Prd_ID;
        }

        return $this->run($sql, $qryArray, false);

    }

    function update($BprCls = NULL) {

        if (is_null($BprCls) || !$BprCls) return 'No Record To Update';

        $sql = '';

        $qryArray = array();

        if ($BprCls->bpr_id == 0) {

            $qryArray["bsk_id"] = $BprCls->bsk_id;
            $qryArray["bpg_id"] = $BprCls->bpg_id;
            $qryArray["prd_id"] = $BprCls->prd_id;
            $qryArray["defsel"] = $BprCls->defsel;
            $qryArray["srtord"] = $BprCls->srtord;
            $qryArray["bprext"] = $BprCls->bprext;
            $qryArray["bprman"] = $BprCls->bprman;
            $qryArray["extpri"] = $BprCls->extpri;
            $qryArray["exttxt"] = $BprCls->exttxt;
            $qryArray["bprqty"] = $BprCls->bprqty;

            $sql = "INSERT INTO basketproducts
					(
					bsk_id,
                    bpg_id,
                    prd_id,
                    defsel,
                    srtord,
                    bprext,
                    bprman,
                    extpri,
                    exttxt,
                    bprqty
					)
					VALUES
					(
                    :bsk_id,
                    :bpg_id,
                    :prd_id,
                    :defsel,
                    :srtord,
                    :bprext,
                    :bprman,
                    :extpri,
                    :exttxt,
                    :bprqty
					);";

        } else {

            $qryArray["bsk_id"] = $BprCls->bsk_id;
            $qryArray["bpg_id"] = $BprCls->bpg_id;
            $qryArray["prd_id"] = $BprCls->prd_id;
            $qryArray["defsel"] = $BprCls->defsel;
            $qryArray["srtord"] = $BprCls->srtord;
            $qryArray["bprext"] = $BprCls->bprext;
            $qryArray["bprman"] = $BprCls->bprman;
            $qryArray["extpri"] = $BprCls->extpri;
            $qryArray["exttxt"] = $BprCls->exttxt;
            $qryArray["bprqty"] = $BprCls->bprqty;

            $sql = "UPDATE basketproducts
					SET
                    bsk_id = :bsk_id,
                    bpg_id = :bpg_id,
                    prd_id = :prd_id,
                    defsel = :defsel,
                    srtord = :srtord,
                    bprext = :bprext,
                    bprman = :bprman,
                    extpri = :extpri,
                    exttxt = :exttxt,
                    bprqty = :bprqty
                    ";

            $sql .= " WHERE bpr_id = :bpr_id";
            $qryArray["bpr_id"] = $BprCls->bpr_id;

        }

        //echo $sql;
        //print_r($qryArray);

        $recordSet = $this->dbConn->prepare($sql);
        $recordSet->execute($qryArray);

        $Bpr_ID = ($BprCls->bpr_id == 0) ? $this->dbConn->lastInsertId('bpr_id') : $BprCls->bpr_id;

        return $Bpr_ID;

    }

    function delete($Bpr_ID = NULL) {

        try {

            if (!is_null($Bpr_ID)) {
                $qryArray = array();
                $sql = 'DELETE FROM basketproducts WHERE bpr_id = :bpr_id ';
                $qryArray["bpr_id"] = $Bpr_ID;

                $recordSet = $this->dbConn->prepare($sql);
                $recordSet->execute($qryArray);

                return $Bpr_ID;

            }

        } catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }

    }

    function updatedefault($Bpr_ID = NULL, $DefSel = 0) {

        try {

            if (!is_null($Bpr_ID)) {
                $qryArray = array();
                $sql = 'UPDATE basketproducts SET defsel = :defsel WHERE bpr_id = :bpr_id ';
                $qryArray["defsel"] = $DefSel;
                $qryArray["bpr_id"] = $Bpr_ID;

                $recordSet = $this->dbConn->prepare($sql);
                $recordSet->execute($qryArray);

                return $Bpr_ID;

            }

        } catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }

    }

    function updatemandatory($Bpr_ID = NULL, $BprMan = 0) {

        try {

            if (!is_null($Bpr_ID)) {
                $qryArray = array();
                $sql = 'UPDATE basketproducts SET bprman = :bprman WHERE bpr_id = :bpr_id ';
                $qryArray["bprman"] = $BprMan;
                $qryArray["bpr_id"] = $Bpr_ID;

                $recordSet = $this->dbConn->prepare($sql);
                $recordSet->execute($qryArray);

                return $Bpr_ID;

            }

        } catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }

    }
}




class BpgDAO extends db
{

    function select($Bpg_ID = NULL, $Bsk_ID = NULL, $BpgTtl = NULL, $ReqObj = false)
    {

        $qryArray = array();
        $sql = 'SELECT
				*
				FROM basketproductgroups WHERE true';

        if (!is_null($Bpg_ID)) {
            $sql .= ' AND bpg_id = :bpg_id ';
            $qryArray["bpg_id"] = $Bpg_ID;
        } else {

            if (!is_null($Bsk_ID)) {
                $sql .= ' AND bsk_id = :bsk_id ';
                $qryArray["bsk_id"] = $Bsk_ID;
            }

            if (!is_null($BpgTtl)) {
                $BskNam = '%' . $BpgTtl . '%';
                $sql .= ' AND bpgttl LIKE :bpgttl ';
                $qryArray["bpgttl"] = $BpgTtl;
            }
        }

        $sql .= ' ORDER BY srtord';

        //print_r($qryArray);
        //echo $sql;

        return $this->run($sql, $qryArray, $ReqObj);

    }

    function update($BpgCls = NULL) {

        if (is_null($BpgCls) || !$BpgCls) return 'No Record To Update';

        $sql = '';

        $qryArray = array();

        if ($BpgCls->bpg_id == 0) {

            $qryArray["bsk_id"] = $BpgCls->bsk_id;
            $qryArray["bpgttl"] = $BpgCls->bpgttl;
            $qryArray["bpgmin"] = $BpgCls->bpgmin;
            $qryArray["bpgmax"] = $BpgCls->bpgmax;

            $qryArray["mulsel"] = $BpgCls->mulsel;
            $qryArray["srtord"] = $BpgCls->srtord;

            $sql = "INSERT INTO basketproductgroups
					(
					bsk_id,
                    bpgttl,
                    bpgmin,
                    bpgmax,
                    mulsel,
                    srtord
					)
					VALUES
					(
                    :bsk_id,
                    :bpgttl,
                    :bpgmin,
                    :bpgmax,
                    :mulsel,
                    :srtord
					);";

        } else {

            $qryArray["bpgttl"] = $BpgCls->bpgttl;
            $qryArray["bpgmin"] = $BpgCls->bpgmin;
            $qryArray["bpgmax"] = $BpgCls->bpgmax;

            $qryArray["mulsel"] = $BpgCls->mulsel;
            $qryArray["srtord"] = $BpgCls->srtord;

            $sql = "UPDATE basketproductgroups
					SET
				    bpgttl = :bpgttl,
                    bpgmin = :bpgmin,
                    bpgmax = :bpgmax,
                    mulsel = :mulsel,
                    srtord = :srtord
                    ";

            $sql .= " WHERE bpg_id = :bpg_id";
            $qryArray["bpg_id"] = $BpgCls->bpg_id;

        }

        //echo $sql;
        //print_r($qryArray);

        $recordSet = $this->dbConn->prepare($sql);
        $recordSet->execute($qryArray);

        $Bpg_ID = ($BpgCls->bpg_id == 0) ? $this->dbConn->lastInsertId('bpg_id') : $BpgCls->bpg_id;

        return $Bpg_ID;

    }

    function delete($Bpg_ID = NULL) {

        try {

            if (!is_null($Bpg_ID)) {
                $qryArray = array();
                $sql = 'DELETE FROM basketproductgroups WHERE bpg_id = :bpg_id ';
                $qryArray["bpg_id"] = $Bpg_ID;

                $recordSet = $this->dbConn->prepare($sql);
                $recordSet->execute($qryArray);

                $qryArray = array();
                $sql = 'DELETE FROM basketproducts WHERE bpg_id = :bpg_id';
                $qryArray["bpg_id"] = $Bpg_ID;

                $recordSet = $this->dbConn->prepare($sql);
                $recordSet->execute($qryArray);

//                $qryArray = array();
//                $sql = 'DELETE FROM basketextras WHERE bsk_id = :bsk_id ';
//                $qryArray["bsk_id"] = $Bsk_ID;
//
//                $recordSet = $this->dbConn->prepare($sql);
//                $recordSet->execute($qryArray);

                return $Bpg_ID;

            }

        } catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }

    }

    function resort($Bpg_ID = NULL) {

        try {

            $Bpg_IDArr = explode(',',$Bpg_ID);

            for ($i=0;$i<count($Bpg_IDArr);$i++) {

                $qryArray = array();
                $sql = 'UPDATE basketproductgroups SET srtord = :srtord WHERE bpg_id = :bpg_id ';
                $qryArray["srtord"] = $i;
                $qryArray["bpg_id"] = $Bpg_IDArr[$i];

                $recordSet = $this->dbConn->prepare($sql);
                $recordSet->execute($qryArray);

            }

            return 0;


        } catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }

    }

    function updatemulti($Bpg_ID = NULL, $MulSel = 0) {

        try {

            if (!is_null($Bpg_ID)) {
                $qryArray = array();
                $sql = 'UPDATE basketproductgroups SET mulsel = :mulsel WHERE bpg_id = :bpg_id ';
                $qryArray["mulsel"] = $MulSel;
                $qryArray["bpg_id"] = $Bpg_ID;

                $recordSet = $this->dbConn->prepare($sql);
                $recordSet->execute($qryArray);

                return $Bpg_ID;

            }

        } catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }

    }

}



class BexDAO extends db
{

    function select($Bex_ID = NULL, $Bsk_ID = NULL, $ReqObj = false)
    {

        $qryArray = array();
        $sql = 'SELECT
				*
				FROM basketextras WHERE true';

        if (!is_null($Bex_ID)) {
            $sql .= ' AND bex_id = :bex_id ';
            $qryArray["bex_id"] = $Bex_ID;
        } else {

            if (!is_null($Bsk_ID)) {
                $sql .= ' AND bsk_id = :bsk_id ';
                $qryArray["bsk_id"] = $Bsk_ID;
            }

        }

        $sql .= ' ORDER BY srtord';

        //print_r($qryArray);
        //echo $sql;

        return $this->run($sql, $qryArray, $ReqObj);

    }

    function update($BexCls = NULL) {

        if (is_null($BexCls) || !$BexCls) return 'No Record To Update';

        $sql = '';

        $qryArray = array();

        if ($BexCls->bex_id == 0) {

            $qryArray["bsk_id"] = $BexCls->bsk_id;
            $qryArray["bexttl"] = $BexCls->bexttl;
            $qryArray["bextxt"] = $BexCls->bextxt;
            $qryArray["bexpri"] = $BexCls->bexpri;

            $qryArray["bexdef"] = $BexCls->bexdef;
            $qryArray["bexman"] = $BexCls->bexman;

            $sql = "INSERT INTO basketextras
					(
					bsk_id,
                    bexttl,
                    bextxt,
                    bexpri,
                    bexdef,
                    bexman
					)
					VALUES
					(
                    :bsk_id,
                    :bexttl,
                    :bextxt,
                    :bexpri,
                    :bexdef,
                    :bexman
					);";

        } else {

            $qryArray["bsk_id"] = $BexCls->bsk_id;
            $qryArray["bexttl"] = $BexCls->bexttl;
            $qryArray["bextxt"] = $BexCls->bextxt;
            $qryArray["bexpri"] = $BexCls->bexpri;

            $qryArray["bexdef"] = $BexCls->bexdef;
            $qryArray["bexman"] = $BexCls->bexman;

            $sql = "UPDATE basketextras
					SET
				    bexttl = :bexttl,
                    bextxt = :bextxt,
                    bexpri = :bexpri,
                    bexdef = :bexdef,
                    bexman = :bexman
                    ";

            $sql .= " WHERE bex_id = :bex_id";
            $qryArray["bex_id"] = $BexCls->bex_id;

        }

        //echo $sql;
        //print_r($qryArray);

        $recordSet = $this->dbConn->prepare($sql);
        $recordSet->execute($qryArray);

        $Bex_ID = ($BexCls->bex_id == 0) ? $this->dbConn->lastInsertId('bex_id') : $BexCls->bex_id;

        return $Bex_ID;

    }

    function delete($Bex_ID = NULL) {

        try {

            if (!is_null($Bex_ID)) {
                $qryArray = array();
                $sql = 'DELETE FROM basketextras WHERE bex_id = :bex_id ';
                $qryArray["bex_id"] = $Bex_ID;

                $recordSet = $this->dbConn->prepare($sql);
                $recordSet->execute($qryArray);

                return $Bex_ID;

            }

        } catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }

    }

    function resort($Bex_ID = NULL) {

        try {

            $Bex_IDArr = explode(',',$Bex_ID);

            for ($i=0;$i<count($Bex_IDArr);$i++) {

                $qryArray = array();
                $sql = 'UPDATE basketextras SET srtord = :srtord WHERE bex_id = :bex_id ';
                $qryArray["srtord"] = $i;
                $qryArray["bex_id"] = $Bex_IDArr[$i];

                $recordSet = $this->dbConn->prepare($sql);
                $recordSet->execute($qryArray);

            }

            return 0;


        } catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }

    }

}



?>