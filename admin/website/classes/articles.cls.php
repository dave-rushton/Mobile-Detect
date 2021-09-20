<?php

//
// Articles class
//

class ArtDAO extends db
{

    function select($Art_ID = NULL, $SeoUrl = NULL, $PerPag = NULL, $Pag_No = NULL, $ReqObj = false)
    {

        $OffSet = NULL;
        if (isset($PerPag) && isset($Pag_No) && is_numeric($PerPag) && is_numeric($Pag_No)) $OffSet = ($Pag_No - 1) * $PerPag;

        $qryArray = array();
        $sql = 'SELECT
				art_id,
				artttl,
				artdat,
				artdsc,
				arttxt,
				arttyp,
				artimg,
				seourl,
				seokey,
				seodsc,
				sta_id,
				artobj
				FROM articles WHERE true';

        if (!is_null($Art_ID)) {
            $sql .= ' AND art_id = :art_id ';
            $qryArray["art_id"] = $Art_ID;
        } else {
            if (!is_null($SeoUrl)) {
                $sql .= ' AND seourl = :seourl ';
                $qryArray["seourl"] = $SeoUrl;
            }
        }

        $sql .= ' ORDER BY artdat DESC';

        if (!is_null($OffSet) && is_numeric($OffSet) && !is_null($PerPag) && is_numeric($PerPag)) {
            $sql .= ' LIMIT ' . $OffSet . ' , ' . $PerPag;
        } else {

        }

//		echo $qryArray["offset"].' : '.$qryArray["perpag"].' = '.$sql;

        return $this->run($sql, $qryArray, $ReqObj);

    }


    function selectBySeoUrl($SeoUrl = NULL)
    {

        if (!is_null($SeoUrl)) {

            $qryArray = array();
            $sql = 'SELECT
					art_id,
					artttl,
					artdat,
					artdsc,
					arttxt,
					arttyp,
					artimg,
					seourl,
					seokey,
					seodsc,
					sta_id,
					artobj
					FROM articles WHERE seourl = :seourl';

            $qryArray["seourl"] = $SeoUrl;

            return $this->run($sql, $qryArray, true);
        } else {
            return false;
        }
    }

    function selectByCategory($Sub_ID = NULL, $ReqObj = false, $PerPag = NULL, $Pag_No = NULL)
    {

        $OffSet = NULL;
        if (isset($PerPag) && isset($Pag_No) && is_numeric($PerPag) && is_numeric($Pag_No)) $OffSet = ($Pag_No - 1) * $PerPag;

        $qryArray = array();
        $sql = 'SELECT
				art_id,
				artttl,
				artdat,
				artdsc,
				arttxt,
				arttyp,
				artimg,
				seourl,
				seokey,
				seodsc,
				sta_id,
				artobj
				FROM articles
				WHERE true';

        if (!is_null($Sub_ID)) {
            $sql .= ' AND arttyp LIKE :arttyp';
            $qryArray["arttyp"] = '%|' . $Sub_ID . '|%';
        }

        $sql .= ' ORDER BY artdat DESC';

        if (!is_null($OffSet) && is_numeric($OffSet) && !is_null($PerPag) && is_numeric($PerPag)) {
            $sql .= ' LIMIT ' . $OffSet . ' , ' . $PerPag;
        } else {

        }

        //$this->displayQuery($sql, $qryArray);
        return $this->run($sql, $qryArray, $ReqObj);

    }

    function selectNext($Art_ID = NULL, $ArtTyp = NULL, $ReqObj = false)
    {

        $sql = "SELECT
                art_id,
                seourl, MIN(artdat) AS artdat
                FROM articles
                WHERE artdat < (SELECT artdat FROM articles WHERE art_id = " . $Art_ID . ")
                AND arttyp LIKE '%|" . $ArtTyp . "|%'
                GROUP BY art_id
                ORDER BY artdat DESC
                LIMIT 1";

        $qryArray = array();

        return $this->run($sql, $qryArray, $ReqObj);

    }

    function selectPrev($Art_ID = NULL, $ArtTyp = NULL, $ReqObj = false)
    {

        $sql = "SELECT
                art_id,
                seourl, MIN(artdat) AS artdat
                FROM articles
                WHERE artdat > (SELECT artdat FROM articles WHERE art_id = " . $Art_ID . ")
                AND arttyp LIKE '%|" . $ArtTyp . "|%'
                GROUP BY art_id
                ORDER BY artdat ASC
                LIMIT 1";

        $qryArray = array();

        return $this->run($sql, $qryArray, $ReqObj);

    }

    function selectByArchive($Art_Yr = NULL, $Art_Mn = NULL)
    {

        $qryArray = array();
        $sql = 'SELECT
				art_id,
				artttl,
				artdat,
				artdsc,
				arttxt,
				arttyp,
				artimg,
				seourl,
				seokey,
				seodsc,
				sta_id,
				artobj
				FROM articles 
				WHERE true';

        if (!is_null($Art_Yr) && is_numeric($Art_Yr)) {
            $sql .= ' AND YEAR(artdat) = :art_yr';
            $qryArray["art_yr"] = $Art_Yr;
        }

        if (!is_null($Art_Mn) && is_numeric($Art_Mn)) {
            $sql .= ' AND MONTH(artdat) = :art_mn';
            $qryArray["art_mn"] = $Art_Mn;
        }

        $sql .= ' ORDER BY artdat DESC';

        return $this->run($sql, $qryArray, false);

    }

    function update($ArtCls = NULL)
    {

        if (is_null($ArtCls) || !$ArtCls) return 'No Record To Update';

        $sql = '';

        $qryArray = array();

        if ($ArtCls->art_id == 0) {

            $qryArray["artttl"] = $ArtCls->artttl;
            $qryArray["artdat"] = $ArtCls->artdat;
            $qryArray["artdsc"] = $ArtCls->artdsc;
            $qryArray["arttxt"] = $ArtCls->arttxt;
            $qryArray["arttyp"] = $ArtCls->arttyp;
            $qryArray["artimg"] = $ArtCls->artimg;
            $qryArray["seourl"] = $ArtCls->seourl;
            $qryArray["seokey"] = $ArtCls->seokey;
            $qryArray["seodsc"] = $ArtCls->seodsc;
            $qryArray["sta_id"] = $ArtCls->sta_id;
            $qryArray["artobj"] = $ArtCls->artobj;

            $sql = "INSERT INTO articles
					(
					
					artttl,
					artdat,
					artdsc,
					arttxt,
					arttyp,
					artimg,
					seourl,
					seokey,
					seodsc,
					sta_id,
					artobj
					
					)
					VALUES
					(
					
					:artttl,
					:artdat,
					:artdsc,
					:arttxt,
					:arttyp,
					:artimg,
					:seourl,
					:seokey,
					:seodsc,
					:sta_id,
					:artobj
					
					);";

        } else {

            $qryArray["artttl"] = $ArtCls->artttl;
            $qryArray["artdat"] = $ArtCls->artdat;
            $qryArray["artdsc"] = $ArtCls->artdsc;
            $qryArray["arttxt"] = $ArtCls->arttxt;
            $qryArray["arttyp"] = $ArtCls->arttyp;
            $qryArray["artimg"] = $ArtCls->artimg;
            $qryArray["seourl"] = $ArtCls->seourl;
            $qryArray["seokey"] = $ArtCls->seokey;
            $qryArray["seodsc"] = $ArtCls->seodsc;
            $qryArray["sta_id"] = $ArtCls->sta_id;
            $qryArray["artobj"] = $ArtCls->artobj;

            $sql = "UPDATE articles
					SET
					
					artttl = :artttl,
					artdat = :artdat,
					artdsc = :artdsc,
					arttxt = :arttxt,
					arttyp = :arttyp,
					artimg = :artimg,
					seourl = :seourl,
					seokey = :seokey,
					seodsc = :seodsc,
					sta_id = :sta_id,
					artobj = :artobj";

            $sql .= " WHERE art_id = :art_id";
            $qryArray["art_id"] = $ArtCls->art_id;

        }

        $recordSet = $this->dbConn->prepare($sql);
        $recordSet->execute($qryArray);

        return ($ArtCls->art_id == 0) ? $this->dbConn->lastInsertId('art_id') : $ArtCls->art_id;
    }

    function delete($Art_ID = NULL)
    {

        try {

            if (!is_null($Art_ID)) {
                $qryArray = array();
                $sql = 'DELETE FROM articles WHERE art_id = :art_id ';
                $qryArray["art_id"] = $Art_ID;

                $recordSet = $this->dbConn->prepare($sql);
                $recordSet->execute($qryArray);

                return $Art_ID;

            }

        } catch (PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }

    }

    function getArticlesArchive($articlesURL = '')
    {

        $year = 0000;
        $month = "";
        $first = true;

        $sql = "SELECT MONTHNAME(artdat) as month, MONTH(artdat) as monthint, YEAR(artdat) as year FROM articles GROUP BY YEAR(artdat), MONTH(artdat) DESC ORDER BY artdat DESC;";
        $articles = $this->run($sql, array(), false);

        $tableLength = count($articles);
        echo '<ul>';
        for ($i = 0; $i < $tableLength; ++$i) {
            if ($year != $articles[$i]['year']) {
                $year = $articles[$i]['year'];
                echo ($first) ? '<li>' . $year . '<ul>' : '</ul></li><li>' . $year . '<ul>';
                $first = false;
            }

            echo '<li><a href="', $articlesURL, '/archive/', $year, '/', ($articles[$i]['monthint'] < 10) ? '0' . $articles[$i]['monthint'] : $articles[$i]['monthint'], '">', $articles[$i]['month'], '</a></li>';
        }
        echo ($tableLength > 0) ? '</ul></li></ul>' : '</ul>';

    }

}