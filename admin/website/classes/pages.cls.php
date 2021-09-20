<?php

//
// Pages class
//

class PagDAO extends db
{

    function select($Pag_ID = NULL, $SeoUrl = NULL, $ReqObj = false)
    {

        $qryArray = array();
        $sql = 'SELECT 
				p.id AS pag_id,
				p.TemplateID AS tmplte,
				p.title,
				p.lnktyp,
				p.seourl,
				p.pagttl,
				p.keywrd,
				p.pagdsc,
				p.googex,
				p.sta_id,
				p.defpag,
				t.tplfil,
				p.pagimg,
				p.pagobj
				FROM pages p
				LEFT OUTER JOIN template t ON t.tpl_id = p.TemplateID
				';

        if (!is_null($Pag_ID)) {
            $sql .= ' WHERE id = :pag_id ';
            $qryArray["pag_id"] = $Pag_ID;
        } else if (!is_null($SeoUrl)) {
            $sql .= ' WHERE seourl = :seourl ';
            $qryArray["seourl"] = $SeoUrl;
        } else {
            $sql .= ' WHERE TRUE';
        }

        return $this->run($sql, $qryArray, $ReqObj);

    }

    function updatePageText($PagCls = NULL)
    {

        if (is_null($PagCls) || !$PagCls) return 'No Record To Update';

        $qryArray["pagtxt"] = $PagCls->pagtxt;

        $sql = "UPDATE pages
				SET
				pagtxt = :pagtxt
				WHERE id = :pag_id";

        $qryArray["pag_id"] = $PagCls->pag_id;

        $recordSet = $this->dbConn->prepare($sql);
        $recordSet->execute($qryArray);

        return ($PagCls->pag_id == 0) ? $this->dbConn->lastInsertId('pag_id') : $PagCls->pag_id;

    }

    function update($PagCls = NULL)
    {

        if (is_null($PagCls) || !$PagCls) return 'No Record To Update';

        if ($PagCls->defpag == 1) {

            $qryArray = array();
            $sql = "UPDATE pages SET defpag = 0";
            $recordSet = $this->dbConn->prepare($sql);
            $recordSet->execute($qryArray);

            $qryArray = array();
            $qryArray['pag_id'] = $PagCls->pag_id;
            $sql = "UPDATE pages SET defpag = 1 WHERE id = :pag_id";
            $recordSet = $this->dbConn->prepare($sql);
            $recordSet->execute($qryArray);

        }

        $sql = '';

        $qryArray = array();
        $qryArray["tmplte"] = $PagCls->tmplte;
        $qryArray["lnktyp"] = $PagCls->lnktyp;
        $qryArray["seourl"] = $PagCls->seourl;
        $qryArray["pagttl"] = $PagCls->pagttl;
        $qryArray["keywrd"] = $PagCls->keywrd;
        $qryArray["pagdsc"] = $PagCls->pagdsc;
        $qryArray["googex"] = $PagCls->googex;
        $qryArray["sta_id"] = $PagCls->sta_id;
        $qryArray["pagimg"] = $PagCls->pagimg;

        $qryArray["pagobj"] = $PagCls->pagobj;


        $sql = "UPDATE pages
				SET
				TemplateID = :tmplte,
				lnktyp = :lnktyp,
				seourl = :seourl,
				pagttl = :pagttl,
				keywrd = :keywrd,
				pagdsc = :pagdsc,
				googex = :googex,
				sta_id = :sta_id,
				pagimg = :pagimg,
				pagobj = :pagobj
				WHERE id = :pag_id";

        $qryArray["pag_id"] = $PagCls->pag_id;

        $recordSet = $this->dbConn->prepare($sql);
        $recordSet->execute($qryArray);

        return ($PagCls->pag_id == 0) ? $this->dbConn->lastInsertId('pag_id') : $PagCls->pag_id;
    }

    function delete($Pag_ID = NULL)
    {

        try {

            if (!is_null($Pag_ID) && is_numeric($Pag_ID)) {

                $qryArray = array();
                $sql = 'SELECT pgc_id FROM pageelements WHERE pag_id = :pag_id ';
                $qryArray["pag_id"] = $Pag_ID;
                $pgcList = $this->run($sql, $qryArray);

                $tableLength = count($pgcList);
                for ($i = 0; $i < $tableLength; ++$i) {

//					echo $pgcList[$i]['pgc_id'].'<br>';

                    $qryArray = array();
                    $sql = 'DELETE FROM pagecontent WHERE pgc_id = :pgc_id ';
                    $qryArray["pgc_id"] = $pgcList[$i]['pgc_id'];

                    $recordSet = $this->dbConn->prepare($sql);
                    $recordSet->execute($qryArray);


                }

                $qryArray = array();
                $sql = 'DELETE FROM pageelements WHERE pag_id = :pag_id ';
                $qryArray["pag_id"] = $Pag_ID;

                $recordSet = $this->dbConn->prepare($sql);
                $recordSet->execute($qryArray);

                return $Pag_ID;

            }

        } catch (PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }

    }

}