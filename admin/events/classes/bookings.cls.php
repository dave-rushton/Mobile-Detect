<?php

//
// Booking class
//

class BooDAO extends db {

    function select($Boo_ID = NULL, $BegDat = NULL, $EndDat=NULL, $TblNam=NULL, $Tbl_ID=NULL, $RefNam=NULL, $Ref_ID=NULL, $Sta_ID=NULL, $ReqObj = false, $limit=NULL, $offset=NULL, $SrtOrd=NULL) {

        $qryArray = array();
        $sql = 'SELECT
				b.boo_id,
				b.boodsc,
				b.actdat,
				b.begdat,
				b.enddat,
				b.sta_id,
				b.tblnam,
				b.tbl_id,
				b.reftbl,
				b.ref_id,
				b.prd_id,
				b.unipri,
				b.buypri,
				b.delpri,
				b.alttyp,
				b.uplift,
				b.cat_id,
				b.sub_id,
				b.allday,
				b.remtim,
				b.boocol,
				pla.planam,
				pla.seourl,
				pla.platxt,
				cus.pla_id as cus_id,
				cus.planam as cusnam,
				ven.planam as vennam,
				ven.rooms,
				s.stanam,
				TIMEDIFF(b.enddat,b.begdat) AS caltim,
				TIME_TO_SEC(TIMEDIFF(b.enddat,b.begdat))/3600 AS tothrs
				FROM bookings b
				LEFT OUTER JOIN places pla ON b.tbl_id = pla.pla_id 
				LEFT OUTER JOIN places cus ON pla.tbl_id = cus.pla_id 
				LEFT OUTER JOIN places ven ON b.ref_id = ven.pla_id
				LEFT OUTER JOIN statuscodes s ON s.sta_id = b.sta_id 
				WHERE true';

        if (!is_null($Boo_ID)) {
            $sql .= ' AND b.boo_id = :boo_id ';
            $qryArray["boo_id"] = $Boo_ID;
        } else {
            if (!is_null($BegDat) && checkdate(date("m", strtotime($BegDat)), date("d", strtotime($BegDat)), date("Y", strtotime($BegDat)))) {
                $sql .= ' AND b.begdat >= :begdat ';
                $qryArray["begdat"] = $BegDat;
            }
            if (!is_null($EndDat) && checkdate(date("m", strtotime($BegDat)), date("d", strtotime($BegDat)), date("Y", strtotime($BegDat)))) {
                $sql .= ' AND b.begdat <= :enddat ';
                $qryArray["enddat"] = $EndDat;
            }
            if (!is_null($TblNam)) {
                $sql .= ' AND b.tblnam = :tblnam ';
                $qryArray["tblnam"] = $TblNam;
            }
            if (!is_null($Tbl_ID) && is_numeric($Tbl_ID)) {
                $sql .= ' AND b.tbl_id = :tbl_id ';
                $qryArray["tbl_id"] = $Tbl_ID;
            }
            if (!is_null($RefNam)) {
                $sql .= ' AND b.refnam = :refnam ';
                $qryArray["refnam"] = $RefNam;
            }
            if (!is_null($Ref_ID) && is_numeric($Ref_ID)) {
                $sql .= ' AND b.ref_id = :ref_id ';
                $qryArray["ref_id"] = $Ref_ID;
            }
            if (!is_null($Sta_ID) && is_numeric($Sta_ID)) {
                $sql .= ' AND b.sta_id = :sta_id ';
                $qryArray["sta_id"] = $Sta_ID;
            }

            if (!is_null($SrtOrd)) {
                $sql .= ' ORDER BY '.$SrtOrd;
            } else {
                $sql .= ' ORDER BY b.begdat DESC ';
            }

            if (is_numeric($limit)) {
                $sql .= ' LIMIT '.$limit;

                if (is_numeric($offset)) {
                    $sql .= ' OFFSET '.$offset;
                }

            }

            //echo $sql;
            //print_r($qryArray);

        }

        return $this->run($sql, $qryArray, $ReqObj);

    }

    function bookingsByCustomer(  ) {



    }

    function selectMonthBookings ($month = NULL, $year = NULL, $TblNam = NULL, $Tbl_ID = NULL) {

        $BegDat = $year."-".$month.'-01';

        if ($month == 12) {
            $month = 1;
            $year++;
        } else {
            $month++;
        }

        $EndDat = $year."-".$month.'-01';

        $qryArray = array();
        $sql = 'SELECT
				b.boo_id,
				b.boodsc,
				b.actdat,
				b.begdat,
				b.enddat,
				b.sta_id,
				b.tblnam,
				b.tbl_id,
				b.reftbl,
				b.ref_id,
				b.prd_id,
				b.unipri,
				b.buypri,
				b.delpri,
				b.alttyp,
				b.uplift,
				b.cat_id,
				b.sub_id,
				b.allday,
				b.remtim,
				b.boocol,
				pla.planam,
				cus.pla_id as cus_id,
				cus.planam as cusnam,
				ven.planam as vennam,
				ven.rooms,
				s.stanam,
				TIMEDIFF(b.enddat,b.begdat) AS caltim,
				TIME_TO_SEC(TIMEDIFF(b.enddat,b.begdat))/3600 AS tothrs
				FROM bookings b
				LEFT OUTER JOIN places pla ON b.tbl_id = pla.pla_id
				LEFT OUTER JOIN places cus ON pla.tbl_id = cus.pla_id
				LEFT OUTER JOIN places ven ON b.ref_id = ven.pla_id
				LEFT OUTER JOIN statuscodes s ON s.sta_id = b.sta_id
				WHERE true';

        if (!is_null($BegDat) && checkdate(date("m", strtotime($BegDat)), date("d", strtotime($BegDat)), date("Y", strtotime($BegDat)))) {
            $sql .= ' AND b.begdat >= :begdat ';
            $qryArray["begdat"] = $BegDat;
        }
        if (!is_null($EndDat) && checkdate(date("m", strtotime($BegDat)), date("d", strtotime($BegDat)), date("Y", strtotime($BegDat)))) {
            $sql .= ' AND b.begdat < :enddat ';
            $qryArray["enddat"] = $EndDat;
        }

        if (!is_null($TblNam)) {
            $sql .= ' AND b.tblnam = :tblnam ';
            $qryArray["tblnam"] = $TblNam;
        }
        if (!is_null($Tbl_ID) && is_numeric($Tbl_ID)) {
            $sql .= ' AND b.tbl_id = :tbl_id ';
            $qryArray["tbl_id"] = $Tbl_ID;
        }

        return $this->run($sql, $qryArray, false);

    }


    function getAvailability( $Boo_ID = NULL ) {

        $qryArray = array();
        $sql = 'SELECT
                SUM(ol.numuni) as booked,
                v.rooms,
                e.planam,
                b.begdat,
                v.planam
                FROM orderline ol
                INNER JOIN places e ON e.pla_id = ol.tbl_id
                INNER JOIN bookings b ON b.boo_id = :boo_id
                INNER JOIN places v ON v.pla_id = b.ref_id
                WHERE
                ol.tblnam = "EVENT" AND
                ol.tbl_id = :evt_id';

        $qryArray = array();
        $qryArray["boo_id"] = $Boo_ID;
        $qryArray["evt_id"] = $Boo_ID;

        return $this->run($sql, $qryArray, true);

    }

    function hoursByWeek($TblNam=NULL, $Tbl_ID=NULL) {

        if (!is_null($TblNam) && is_numeric($Tbl_ID)) {

            $sql = "SELECT CONCAT(YEAR(begdat), '/', WEEK(begdat)) AS week_name,
						   YEAR(begdat) AS boo_yr, WEEK(begdat) AS boo_wk, COUNT(*),
							SUM(TIME_TO_SEC(TIMEDIFF(enddat,begdat)))/3600 AS tothrs
					FROM bookings WHERE tblnam = :tblnam AND tbl_id = :tbl_id
					GROUP BY week_name
					ORDER BY YEAR(begdat) DESC, WEEK(begdat) DESC";

            $qryArray = array();
            $qryArray["tblnam"] = $TblNam;
            $qryArray["tbl_id"] = $Tbl_ID;

            return $this->run($sql, $qryArray, false);

        } else {
            return false;
        }

    }

    function hoursByWeekPerCustomer($RefNam=NULL, $Ref_ID=NULL, $BegDat=NULL, $EndDat=NULL, $CusPro="CUS") {

        //if (!is_null($RefNam) && is_numeric($Ref_ID)) {
        $qryArray = array();
        $sql = "SELECT CONCAT(YEAR(begdat), '/', WEEK(begdat)) AS week_name,
						   YEAR(begdat) AS boo_yr, WEEK(begdat) AS boo_wk, COUNT(*),
							SUM(TIME_TO_SEC(TIMEDIFF(enddat,begdat)))/3600 AS tothrs,
					pla.planam AS planam,
					cus.planam AS cusnam
					FROM bookings b 
					INNER JOIN places pla ON pla.pla_id = b.tbl_id
					INNER JOIN places cus ON cus.pla_id = pla.tbl_id 
					WHERE TRUE";

        if (is_numeric($Ref_ID)) {
            $sql .= " AND cus.pla_id = :ref_id ";
            $qryArray["ref_id"] = $Ref_ID;
        }

        if (!is_null($BegDat)) {
            $sql .= " AND b.begdat >= :begdat";
            $qryArray["begdat"] = $BegDat; // + ' 00:00:00';
        }

        if (!is_null($EndDat)) {
            $sql .= " AND b.begdat <= :enddat";
            $qryArray["enddat"] = $EndDat; // + ' 23:59:59';
        }


        if ($CusPro == 'CUS') {

            $sql .= " GROUP BY cus.pla_id, week_name
					ORDER BY YEAR(begdat) DESC, WEEK(begdat) DESC";

        } else {
            $sql .= " GROUP BY pla.pla_id, week_name
					ORDER BY YEAR(begdat) DESC, WEEK(begdat) DESC";
        }

        return $this->run($sql, $qryArray, false);

        //} else {
        //	return false;
        //}

    }

    function update($BooCls = NULL) {

        if (is_null($BooCls) || !$BooCls) return 'No Record To Update';

        $sql = '';

        $qryArray = array();

        if ($BooCls->boo_id == 0) {

            $qryArray["boodsc"] = $BooCls->boodsc;
            $qryArray["actdat"] = $BooCls->actdat;
            $qryArray["begdat"] = $BooCls->begdat;
            $qryArray["enddat"] = $BooCls->enddat;
            $qryArray["sta_id"] = $BooCls->sta_id;
            $qryArray["tblnam"] = $BooCls->tblnam;
            $qryArray["tbl_id"] = $BooCls->tbl_id;
            $qryArray["reftbl"] = $BooCls->reftbl;
            $qryArray["ref_id"] = $BooCls->ref_id;
            $qryArray["prd_id"] = $BooCls->prd_id;
            $qryArray["unipri"] = $BooCls->unipri;
            $qryArray["buypri"] = $BooCls->buypri;
            $qryArray["delpri"] = $BooCls->delpri;
            $qryArray["alttyp"] = $BooCls->alttyp;
            $qryArray["uplift"] = $BooCls->uplift;
            $qryArray["cat_id"] = $BooCls->cat_id;
            $qryArray["sub_id"] = $BooCls->sub_id;
            $qryArray["allday"] = $BooCls->allday;
            $qryArray["remtim"] = $BooCls->remtim;
            $qryArray["boocol"] = $BooCls->boocol;

            $sql = "INSERT INTO bookings
					(
					boodsc,
					actdat,
					begdat,
					enddat,
					sta_id,
					tblnam,
					tbl_id,
					reftbl,
					ref_id,
					prd_id,
					unipri,
					buypri,
					delpri,
					alttyp,
					uplift,
					cat_id,
					sub_id,
					allday,
					remtim,
					boocol
					)
					VALUES
					(
					:boodsc,
					:actdat,
					:begdat,
					:enddat,
					:sta_id,
					:tblnam,
					:tbl_id,
					:reftbl,
					:ref_id,
					:prd_id,
					:unipri,
					:buypri,
					:delpri,
					:alttyp,
					:uplift,
					:cat_id,
					:sub_id,
					:allday,
					:remtim,
					:boocol
					);";

        } else {

            $qryArray["boodsc"] = $BooCls->boodsc;
            $qryArray["actdat"] = $BooCls->actdat;
            $qryArray["begdat"] = $BooCls->begdat;
            $qryArray["enddat"] = $BooCls->enddat;
            $qryArray["sta_id"] = $BooCls->sta_id;
            $qryArray["tblnam"] = $BooCls->tblnam;
            $qryArray["tbl_id"] = $BooCls->tbl_id;
            $qryArray["reftbl"] = $BooCls->reftbl;
            $qryArray["ref_id"] = $BooCls->ref_id;
            $qryArray["prd_id"] = $BooCls->prd_id;
            $qryArray["unipri"] = $BooCls->unipri;
            $qryArray["buypri"] = $BooCls->buypri;
            $qryArray["delpri"] = $BooCls->delpri;
            $qryArray["alttyp"] = $BooCls->alttyp;
            $qryArray["uplift"] = $BooCls->uplift;
            $qryArray["cat_id"] = $BooCls->cat_id;
            $qryArray["sub_id"] = $BooCls->sub_id;
            $qryArray["allday"] = $BooCls->allday;
            $qryArray["remtim"] = $BooCls->remtim;
            $qryArray["boocol"] = $BooCls->boocol;

            $sql = "UPDATE bookings
					SET
					boodsc = :boodsc,
					actdat = :actdat,
					begdat = :begdat,
					enddat = :enddat,
					sta_id = :sta_id,
					tblnam = :tblnam,
					tbl_id = :tbl_id,
					reftbl = :reftbl,
					ref_id = :ref_id,
					prd_id = :prd_id,
					unipri = :unipri,
					buypri = :buypri,
					delpri = :delpri,
					alttyp = :alttyp,
					uplift = :uplift,
					cat_id = :cat_id,
					sub_id = :sub_id,
					allday = :allday,
					remtim = :remtim,
					boocol = :boocol";

            $sql .= " WHERE boo_id = :boo_id";
            $qryArray["boo_id"] = $BooCls->boo_id;

        }

        $recordSet = $this->dbConn->prepare($sql);
        $recordSet->execute($qryArray);

        return ($BooCls->boo_id == 0) ? $this->dbConn->lastInsertId('boo_id') : $BooCls->boo_id;
    }

    function changeStatus($BooLst=NULL, $Sta_ID=NULL) {

        if (!is_null($BooLst) && !is_null($Sta_ID)) {

            $booids = explode(",",$BooLst);

            $bookings = '';
            for ($b=0;$b<count($booids);$b++) {
                $bookings .= ($bookings == '') ? "'".$booids[$b]."'" : ",'".$booids[$b]."'";
            }

            $qryArray = array();
            $sql = "UPDATE bookings
					SET
					sta_id = :sta_id
					WHERE
					boo_id IN (".$bookings.")";
            $qryArray["sta_id"] = $Sta_ID;

            $recordSet = $this->dbConn->prepare($sql);
            $recordSet->execute($qryArray);

            // create history if applicable (turn into multi fire PDO style)

            for ($b=0;$b<count($booids);$b++) {

                $qryArray = array();
                $qryArray["tblnam"] = 'BOOKING';
                $qryArray["tbl_id"] = $booids[$b];
                $qryArray["refnam"] = '';
                $qryArray["ref_id"] = 0;
                $qryArray["sthdat"] = date("Y-m-d H:i:s");
                $qryArray["flo_id"] = $Sta_ID;
                $qryArray["sthttl"] = 'Booking status change';
                $qryArray["sthtxt"] = 'The booking was changed from X to Y';

                $sql = "INSERT INTO statushistory
						(
						tblnam,
						tbl_id,
						refnam,
						ref_id,
						sthdat,
						flo_id,
						sthttl,
						sthtxt
						)
						VALUES
						(
						:tblnam,
						:tbl_id,
						:refnam,
						:ref_id,
						:sthdat,
						:flo_id,
						:sthttl,
						:sthtxt
						);";

                $recordSet = $this->dbConn->prepare($sql);
                $recordSet->execute($qryArray);
            }

        }

    }

    function delete($Boo_ID = NULL) {

        try {

            if (!is_null($Boo_ID)) {
                $qryArray = array();
                $sql = 'DELETE FROM bookings WHERE boo_id = :boo_id ';
                $qryArray["boo_id"] = $Boo_ID;

                $recordSet = $this->dbConn->prepare($sql);
                $recordSet->execute($qryArray);

                return $Boo_ID;

            }

        } catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }

    }

}

?>