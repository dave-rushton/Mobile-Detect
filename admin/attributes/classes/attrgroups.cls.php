<?php
//
// Attribute Groups class
//

class AtrDAO extends db {

    function select($Atr_ID = NULL, $TblNam=NULL, $Tbl_ID=NULL, $AtrNam = NULL, $ReqObj = false, $PagNum=NULL, $PerPag=NULL, $Sta_ID=NULL) {

        $qryArray = array();
        $sql = 'SELECT
				a.*,
				s.seourl AS subseo,
				s.subnam
				FROM attribute_group a
				LEFT OUTER JOIN subcategories s ON s.sub_id = a.tbl_id
				WHERE true';

        if (!is_null($Atr_ID)) {
            $sql .= ' AND a.atr_id = :atr_id ';
            $qryArray["atr_id"] = $Atr_ID;
        } else {

            if (!is_null($TblNam)) {
                $sql .= ' AND a.tblnam = :tblnam ';
                $qryArray["tblnam"] = $TblNam;
            }

            if (!is_null($Tbl_ID) && is_numeric($Tbl_ID)) {
                $sql .= ' AND a.tbl_id = :tbl_id ';
                $qryArray["tbl_id"] = $Tbl_ID;
            }

            if (!is_null($AtrNam)) {
                $AtrNam = '%'.$AtrNam.'%';
                $sql .= ' AND a.atrnam LIKE :atrnam ';
                $qryArray["atrnam"] = $AtrNam;
            }
        }

        if (!is_null($Sta_ID)) {
            $sql .= ' AND a.sta_id = :sta_id ';
            $qryArray["sta_id"] = $Sta_ID;
        }

        if (!is_null($PagNum) && !is_null($PerPag)) {
            $sql .= ' LIMIT '.$PerPag.' OFFSET '.$PagNum;
        }

        return $this->run($sql, $qryArray, $ReqObj);
    }

    function selectBySeo($SeoUrl=NULL) {
        $qryArray = array();
        $sql = 'SELECT
				a.atr_id,
				a.atrnam,
				a.tblnam,
				a.tbl_id,
				a.seourl,
				a.seodsc,
				s.subnam,
				s.seourl AS subseo
				FROM attribute_group a 
				LEFT OUTER JOIN subcategories s ON s.sub_id = a.tbl_id
				WHERE a.seourl = :seourl AND a.sta_id = 0';
        $qryArray["seourl"] = $SeoUrl;

        return $this->run($sql, $qryArray, true);
    }

    function searchByCategory($PrdTag=NULL, $PerPag=NULL, $Pag_No=NULL, $SrtOrd='atrnam') {
        $OffSet=NULL;
        if (isset($PerPag) && isset($Pag_No) && is_numeric($PerPag) && is_numeric($Pag_No)) $OffSet = ($Pag_No-1) * $PerPag;

        $qryArray = array();
        $sql = 'SELECT
				a.*,
				s.seourl,
				s.subnam,
				a.seourl AS atrseo
				FROM attribute_group a
				LEFT OUTER JOIN subcategories s ON s.sub_id = a.tbl_id
				WHERE true';

        if (!is_null($PrdTag) && is_numeric($PrdTag)) {
            $sql .= ' AND a.atrtag RLIKE :atrtag ';
            $qryArray["atrtag"] = '[[:<:]]'.$PrdTag.'[[:>:]]';
        }

        if (!is_null($SrtOrd)) {
            $sql .= ' ORDER BY '.$SrtOrd;
        }

        if (!is_null($OffSet) && is_numeric($OffSet) && !is_null($PerPag) && is_numeric($PerPag)) {
            $sql .= ' LIMIT '.$OffSet.' , '.$PerPag;
        }

        return $this->run($sql, $qryArray, false);
    }

    function update($AtrCls = NULL) {

        if (is_null($AtrCls) || !$AtrCls) return 'No Record To Update';

        $sql = '';

        $qryArray = array();

        $qryArray["atrnam"] = $AtrCls->atrnam;
        $qryArray["tblnam"] = $AtrCls->tblnam;
        $qryArray["tbl_id"] = $AtrCls->tbl_id;
        $qryArray["atrema"] = $AtrCls->atrema;
        $qryArray["atrdsc"] = $AtrCls->atrdsc;
        $qryArray["sta_id"] = $AtrCls->sta_id;
        $qryArray["fwdurl"] = $AtrCls->fwdurl;
        $qryArray["alturl"] = $AtrCls->alturl;
        $qryArray["btntxt"] = $AtrCls->btntxt;
        $qryArray["seourl"] = $AtrCls->seourl;
        $qryArray["seokey"] = $AtrCls->seokey;
        $qryArray["seodsc"] = $AtrCls->seodsc;
        $qryArray["sta_id"] = $AtrCls->sta_id;
        $qryArray["atrtag"] = $AtrCls->atrtag;
        $qryArray["numcol"] = $AtrCls->numcol;
        $qryArray["gdpr_title"] = $AtrCls->gdpr_title;
        $qryArray["gdpr_text"] = $AtrCls->gdpr_text;
        $qryArray["gdpr_yes"] = $AtrCls->gdpr_yes;
        $qryArray["gdpr_no"] = $AtrCls->gdpr_no;

        if ($AtrCls->atr_id == 0) {
            $sql = "INSERT INTO attribute_group
					(
					atrnam,
					tblnam,
					tbl_id,
					atrema,
					atrdsc,
					sta_id,
					fwdurl,
					alturl,
					btntxt,
					seourl,
					seokey,
					seodsc,
					atrtag,
					numcol,
					gdpr_title,
					gdpr_text,
					gdpr_yes,
					gdpr_no
					)
					VALUES
					(
					:atrnam,
					:tblnam,
					:tbl_id,
					:atrema,
					:atrdsc,
					:sta_id,
					:fwdurl,
					:alturl,
					:btntxt,
					:seourl,
					:seokey,
					:seodsc,
					:atrtag,
					:numcol,
					:gdpr_title,
					:gdpr_text,
					:gdpr_yes,
					:gdpr_no
					);";

        } else {
            $qryArray["atr_id"] = $AtrCls->atr_id;

            $sql = "UPDATE attribute_group
					SET
					atrnam = :atrnam,
					tblnam = :tblnam,
					tbl_id = :tbl_id,
					atrema = :atrema,
					atrdsc = :atrdsc,
					sta_id = :sta_id,
					fwdurl = :fwdurl,
					alturl = :alturl,
					btntxt = :btntxt,
					seourl = :seourl,
					seokey = :seokey,
					seodsc = :seodsc,
					sta_id = :sta_id,
					atrtag = :atrtag,
					atrtag = :atrtag,
					gdpr_title = :gdpr_title,
					gdpr_text = :gdpr_text,
					gdpr_yes = :gdpr_yes,
					gdpr_no = :gdpr_no,
					numcol = :numcol";

            $sql .= " WHERE atr_id = :atr_id";

            $sql2 = "SELECT atl_id, colnum FROM attribute_label WHERE atr_id = :atr_id";
            $qryArray2["atr_id"] = $AtrCls->atr_id;
            $colnum = $this->dbConn->prepare($sql2);
            $colnum->execute($qryArray2);
            $result = $colnum->fetchAll();

            foreach ($result as $subarray) {
                if ($subarray["colnum"] > $AtrCls->numcol) {
                    $sql2 = "UPDATE attribute_label SET colnum = :colnum WHERE atl_id = :atl_id";
                    $qryArray2 = array();
                    $qryArray2["atl_id"] = $subarray["atl_id"];
                    $qryArray2["colnum"] = $AtrCls->numcol;

                    $colnum = $this->dbConn->prepare($sql2);
                    $colnum->execute($qryArray2);
                }
            }
        }

        $recordSet = $this->dbConn->prepare($sql);
        $recordSet->execute($qryArray);

        return ($AtrCls->atr_id == 0) ? $this->dbConn->lastInsertId('atr_id') : $AtrCls->atr_id;
    }

    function delete($Atr_ID = NULL) {
        try {
            if (!is_null($Atr_ID)) {
                $qryArray = array();
                $sql = 'DELETE FROM attribute_group WHERE atr_id = :atr_id ';
                $qryArray["atr_id"] = $Atr_ID;

                $recordSet = $this->dbConn->prepare($sql);
                $recordSet->execute($qryArray);

                $qryArray = array();
                $sql = 'DELETE FROM attribute_label WHERE atr_id = :atr_id ';
                $qryArray["atr_id"] = $Atr_ID;

                $recordSet = $this->dbConn->prepare($sql);
                $recordSet->execute($qryArray);

                $qryArray = array();
                $sql = 'DELETE FROM attribute_value WHERE atr_id = :atr_id ';
                $qryArray["atr_id"] = $Atr_ID;

                $recordSet = $this->dbConn->prepare($sql);
                $recordSet->execute($qryArray);
            }
        } catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }

        return $Atr_ID;
    }
}