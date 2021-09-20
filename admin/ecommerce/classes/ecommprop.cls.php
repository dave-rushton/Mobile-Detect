<?php

//
// Ecoivery Info class
//

class EcoDAO extends db {
	
	function select($ReqObj=false) {

		$qryArray = array();
		$sql = 'SELECT
                *
				FROM ecommprop WHERE TRUE';
		
		//echo $sql.' #'.$EcoCod.'#';

		return $this->run($sql, $qryArray, $ReqObj);

	}
	
	function update($EcoCls = NULL) {
	
		if (is_null($EcoCls) || !$EcoCls) return 'No Record To Update';
		
		$sql = '';
		
		$qryArray = array();
		
		if ($EcoCls->eco_id == 0) {

			$qryArray["comnam"] = $EcoCls->comnam;
			$qryArray["adr1"] = $EcoCls->adr1;
			$qryArray["adr2"] = $EcoCls->adr2;
			$qryArray["adr3"] = $EcoCls->adr3;
			$qryArray["adr4"] = $EcoCls->adr4;
			$qryArray["pstcod"] = $EcoCls->pstcod;
			$qryArray["emaadr"] = $EcoCls->emaadr;
			$qryArray["comtel"] = $EcoCls->comtel;

            $qryArray["sp_sta"] = $EcoCls->sp_sta;
            $qryArray["sp_ven"] = $EcoCls->sp_ven;
            $qryArray["sp_enc"] = $EcoCls->sp_enc;
            $qryArray["sptven"] = $EcoCls->sptven;
            $qryArray["sptenc"] = $EcoCls->sptenc;
            $qryArray["pp_sta"] = $EcoCls->pp_sta;
            $qryArray["pp_ema"] = $EcoCls->pp_ema;

			$sql = "INSERT INTO ecommprop
					(

					comnam,
					adr1,
					adr2,
					adr3,
					adr4,
					pstcod,
					emaadr,
					comtel,
					sp_sta,
                    sp_ven,
                    sp_enc,
                    sptven,
                    sptenc,
                    pp_sta,
                    pp_ema
					)
					VALUES
					(
					
					:comnam,
					:adr1,
					:adr2,
					:adr3,
					:adr4,
					:pstcod,
					:emaadr,
					:comtel
					:sp_sta,
                    :sp_ven,
                    :sp_enc,
                    :sptven,
                    :sptenc,
                    :pp_sta,
                    :pp_ema
					);";

					
						
		} else {

            $qryArray["comnam"] = $EcoCls->comnam;
            $qryArray["adr1"] = $EcoCls->adr1;
            $qryArray["adr2"] = $EcoCls->adr2;
            $qryArray["adr3"] = $EcoCls->adr3;
            $qryArray["adr4"] = $EcoCls->adr4;
            $qryArray["pstcod"] = $EcoCls->pstcod;
            $qryArray["emaadr"] = $EcoCls->emaadr;
            $qryArray["comtel"] = $EcoCls->comtel;

            $qryArray["sp_sta"] = $EcoCls->sp_sta;
            $qryArray["sp_ven"] = $EcoCls->sp_ven;
            $qryArray["sp_enc"] = $EcoCls->sp_enc;
            $qryArray["sptven"] = $EcoCls->sptven;
            $qryArray["sptenc"] = $EcoCls->sptenc;
            $qryArray["pp_sta"] = $EcoCls->pp_sta;
            $qryArray["pp_ema"] = $EcoCls->pp_ema;

			$sql = "UPDATE ecommprop
					SET
					
					comnam = :comnam,
					adr1 = :adr1,
					adr2 = :adr2,
					adr3 = :adr3,
					adr4 = :adr4,
					pstcod = :pstcod,
					emaadr = :emaadr,
					comtel = :comtel,
					sp_sta = :sp_sta,
					sp_ven = :sp_ven,
					sp_enc = :sp_enc,
					sptven = :sptven,
					sptenc = :sptenc,
					pp_sta = :pp_sta,
					pp_ema = :pp_ema
					";
				
			$sql .= " WHERE eco_id = :eco_id";
			$qryArray["eco_id"] = $EcoCls->eco_id;
			
		}
		
		$recordSet = $this->dbConn->prepare($sql);
		$recordSet->execute($qryArray);
		
		return ($EcoCls->eco_id == 0) ? $this->dbConn->lastInsertId('eco_id') : $EcoCls->eco_id;
	}
	
//	function delete($Eco_ID = NULL)
//    {
//
//        try {
//
//            if (!is_null($Eco_ID)) {
//                $qryArray = array();
//                $sql = 'DELETE FROM ecommprop WHERE eco_id = :eco_id ';
//                $qryArray["eco_id"] = $Eco_ID;
//
//                $recordSet = $this->dbConn->prepare($sql);
//                $recordSet->execute($qryArray);
//
//                return $Eco_ID;
//
//            }
//
//        } catch (PDOException $e) {
//            echo 'ERROR: ' . $e->getMessage();
//        }
//
//    }
	
}

?>