<?php

class PrbDAO extends db {

	function select($Prb_ID = NULL, $Cus_ID=NULL, $Prt_ID=NULL, $Prd_ID=NULL, $CurDat=NULL, $NumUni=NULL, $Sta_ID=NULL, $ReqObj = false) {

		$qryArray = array();
		$sql = 'SELECT 
				pb.*,
				pt.prtnam,
				p.prdnam
				FROM pricebands pb
				INNER JOIN producttypes pt ON pt.prt_id = pb.prt_id
				LEFT OUTER JOIN products p ON p.prd_id = pb.prd_id
				WHERE true ';

        if (!is_null($Prb_ID)) {
            $sql .= ' AND pb.prb_id = :prb_id ';
            $qryArray["prb_id"] = $Prb_ID;
        } else {

            if (is_numeric($Cus_ID)) {
                $sql .= ' AND pb.cus_id = :cus_id ';
                $qryArray["cus_id"] = $Cus_ID;
            }
            if (is_numeric($Prt_ID)) {
                $sql .= ' AND pb.prt_id = :prt_id ';
                $qryArray["prt_id"] = $Prt_ID;
            }
            if (is_numeric($Prd_ID)) {
                $sql .= ' AND pb.prd_id = :prd_id ';
                $qryArray["prd_id"] = $Prd_ID;
            }

            if (!is_null($CurDat) && checkdate(date("m", strtotime($CurDat)), date("d", strtotime($CurDat)), date("Y", strtotime($CurDat)))) {
                $sql .= ' AND ((pb.begdat <= :begdat OR (pb.begdat IS NULL OR pb.begdat = "")';
                $sql .= ' AND (pb.enddat >= :enddat OR (pb.enddat IS NULL OR pb.enddat = "")))) ';
                $qryArray["begdat"] = $CurDat;
                $qryArray["enddat"] = $CurDat;
            }

            if (is_numeric($NumUni)) {
                $sql .= ' AND pb.numuni <= :numuni ';
                $qryArray["numuni"] = $NumUni;
            }

            if (is_numeric($Sta_ID)) {
                $sql .= ' AND pb.sta_id = :sta_id ';
                $qryArray["sta_id"] = $Sta_ID;
            }

        }

        $sql .= ' ORDER BY unipri DESC';

		//$this->displayQuery($sql, $qryArray);
		return $this->run($sql, $qryArray, $ReqObj);

	}

	function update($PrbCls = NULL) {

		if (is_null($PrbCls) || !$PrbCls) return 'No Record To Update';

		$sql = '';

		$qryArray = array();

		if ($PrbCls->prb_id == 0) {

			$qryArray["cus_id"] = $PrbCls->cus_id;
			$qryArray["prt_id"] = $PrbCls->prt_id;
			$qryArray["prd_id"] = $PrbCls->prd_id;
			$qryArray["begdat"] = $PrbCls->begdat;
			$qryArray["enddat"] = $PrbCls->enddat;
			$qryArray["prityp"] = $PrbCls->prityp;
			$qryArray["numuni"] = $PrbCls->numuni;
			$qryArray["unipri"] = $PrbCls->unipri;
			$qryArray["sta_id"] = $PrbCls->sta_id;

			$sql = "INSERT INTO pricebands
					(
					cus_id,
					prt_id,
					prd_id,
					begdat,
					enddat,
					prityp,
					numuni,
					unipri,
					sta_id
					)
					VALUES
					(
					:cus_id,
					:prt_id,
					:prd_id,
					:begdat,
					:enddat,
					:prityp,
					:numuni,
					:unipri,
					:sta_id
					);";

		} else {

            $qryArray["cus_id"] = $PrbCls->cus_id;
            $qryArray["prt_id"] = $PrbCls->prt_id;
            $qryArray["prd_id"] = $PrbCls->prd_id;
            $qryArray["begdat"] = $PrbCls->begdat;
            $qryArray["enddat"] = $PrbCls->enddat;
            $qryArray["prityp"] = $PrbCls->prityp;
            $qryArray["numuni"] = $PrbCls->numuni;
            $qryArray["unipri"] = $PrbCls->unipri;
            $qryArray["sta_id"] = $PrbCls->sta_id;

			$sql = "UPDATE pricebands
					SET
					cus_id = :cus_id,
					prt_id = :prt_id,
					prd_id = :prd_id,
					begdat = :begdat,
					enddat = :enddat,
					prityp = :prityp,
					numuni = :numuni,
					unipri = :unipri,
					sta_id = :sta_id
					WHERE prb_id = :prb_id";

			$qryArray["prb_id"] = $PrbCls->prb_id;

		}

        //$this->displayQuery($sql,$qryArray);

		$recordSet = $this->dbConn->prepare($sql);
		$recordSet->execute($qryArray);

		return ($PrbCls->prb_id == 0) ? $this->dbConn->lastInsertId('prb_id') : $PrbCls->prb_id;
	}

	function delete($Prb_ID = NULL) {

		try {

			if (!is_null($Prb_ID)) {
				$qryArray = array();
				$sql = 'DELETE FROM pricebands WHERE prb_id = :prb_id ';
				$qryArray["prb_id"] = $Prb_ID;

				$recordSet = $this->dbConn->prepare($sql);
				$recordSet->execute($qryArray);

				return $Prb_ID;

			}

		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}

	}

}

?>
