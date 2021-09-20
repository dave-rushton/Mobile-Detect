<?php

//
// Vativery Info class
//

class VatDAO extends db {
	
	function select($Vat_ID = NULL, $BegDat=NULL, $Sta_ID=NULL, $ReqObj=false) {

		$qryArray = array();
		$sql = 'SELECT
                vat_id,
                vatnam,
                vatrat,
				begdat,
				defvat
				FROM vat WHERE TRUE';
		
		if (!is_null($Vat_ID)) {
            $sql .= ' AND vat_id = :vat_id ';
			$qryArray["vat_id"] = $Vat_ID;
		} else {
			
			if (!is_null($BegDat)) {
				$sql .= ' AND begdat <= :begdat ';
				$qryArray["begdat"] = $BegDat;
			}
			
			$sql .= ' ORDER BY begdat DESC';
			
//			if (!is_null($BegDat)) {
//				$sql .= ' LIMIT 1';
//			}
		}
		
		//echo $sql.' #'.$VatCod.'#';

		return $this->run($sql, $qryArray, $ReqObj);

	}
	
	function update($VatCls = NULL) {
	
		if (is_null($VatCls) || !$VatCls) return 'No Record To Update';
		
		$sql = '';
		
		$qryArray = array();


        if ( $VatCls->defvat == 1 ) {

            $qryArray = array();
            $sql = "UPDATE vat SET defvat = 0";
            $recordSet = $this->dbConn->prepare($sql);
            $recordSet->execute($qryArray);

            $qryArray = array();
            $qryArray['vat_id'] = $VatCls->vat_id;
            $sql = "UPDATE vat SET defvat = 1 WHERE vat_id = :vat_id";
            $recordSet = $this->dbConn->prepare($sql);
            $recordSet->execute($qryArray);

        }

		
		if ($VatCls->vat_id == 0) {

            $qryArray = array();
			$qryArray["vatnam"] = $VatCls->vatnam;
			$qryArray["vatrat"] = $VatCls->vatrat;
			$qryArray["begdat"] = $VatCls->begdat;
            $qryArray["defvat"] = $VatCls->defvat;
			
			$sql = "INSERT INTO vat
					(

					vatnam,
					vatrat,
					begdat,
					defvat
					
					)
					VALUES
					(
					
					:vatnam,
					:vatrat,
					:begdat,
					:defvat
					
					);";

					
						
		} else {

            $qryArray = array();
            $qryArray["vatnam"] = $VatCls->vatnam;
            $qryArray["vatrat"] = $VatCls->vatrat;
            $qryArray["begdat"] = $VatCls->begdat;
            $qryArray["defvat"] = $VatCls->defvat;
			
			$sql = "UPDATE vat
					SET
					
					vatnam = :vatnam,
					vatrat = :vatrat,
					begdat = :begdat,
					defvat = :defvat";
				
			$sql .= " WHERE vat_id = :vat_id";
			$qryArray["vat_id"] = $VatCls->vat_id;
			
		}
		
		$recordSet = $this->dbConn->prepare($sql);
		$recordSet->execute($qryArray);
		
		return ($VatCls->vat_id == 0) ? $this->dbConn->lastInsertId('vat_id') : $VatCls->vat_id;
	}
	
	function delete($Vat_ID = NULL)
    {

        try {

            if (!is_null($Vat_ID)) {
                $qryArray = array();
                $sql = 'DELETE FROM vat WHERE vat_id = :vat_id ';
                $qryArray["vat_id"] = $Vat_ID;

                $recordSet = $this->dbConn->prepare($sql);
                $recordSet->execute($qryArray);

                return $Vat_ID;

            }

        } catch (PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }

    }
	
}

?>