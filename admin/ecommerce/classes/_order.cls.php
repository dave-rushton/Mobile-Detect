<?php

//
// Ordces class
//

class OrdDAO extends db {
	
	function select($Ord_ID = NULL, $TblNam=NULL, $Tbl_ID=NULL, $Sta_ID=NULL, $ReqObj=false) { 
	
		$qryArray = array();
		$sql = 'SELECT
				o.ord_id,
				o.ordtyp,
				o.invdat,
				o.duedat,
				o.paydat,
				o.cusnam,
				o.adr1,
				o.adr2,
				o.adr3,
				o.adr4,
				o.pstcod,
				o.payadr1,
				o.payadr2,
				o.payadr3,
				o.payadr4,
				o.paypstcod,
				o.paytrm,
				o.vatrat,
				o.tblnam,
				o.tbl_id,
				o.sta_id,
				o.altref,
				o.altnam,
				o.del_id,
				o.discod,
				o.emaadr,
				p.planam,
				p.comnam,
				SUM(ol.unipri * ol.numuni) AS ordtot
				FROM orders o 
				LEFT OUTER JOIN places p ON p.pla_id = o.tbl_id
				LEFT OUTER JOIN orderline ol ON o.ord_id = ol.ord_id
				WHERE TRUE';
		
		if (!is_null($Ord_ID)) {
			$sql .= ' AND o.ord_id = :ord_id ';
			$qryArray["ord_id"] = $Ord_ID;
		} else {
			if (!is_null($TblNam)) {
				$sql .= ' AND o.tblnam = :tblnam ';
				$qryArray["tblnam"] = $TblNam;
			}
			if (!is_null($Tbl_ID) && is_numeric($Tbl_ID)) {
				$sql .= ' AND o.tbl_id = :tbl_id ';
				$qryArray["tbl_id"] = $Tbl_ID;
			}
			if (!is_null($Sta_ID)) {
			
				if (is_numeric($Sta_ID)) {
					$sql .= " AND o.sta_id = :sta_id ";
				} else {
					$sql .= " AND find_in_set(cast(o.sta_id as char), :sta_id) ";
				}
			
				$qryArray["sta_id"] = $Sta_ID;
			}
		}
		
		$sql .= ' GROUP BY o.ord_id ORDER BY o.invdat DESC';
		
		return $this->run($sql, $qryArray, $ReqObj);

	}


	function checkDiscount($CusEma = NULL, $DisCod=NULL)
	{

		$qryArray = array();
		$sql = 'SELECT *
				FROM orders o
				WHERE ';

		$sql .= ' o.emaadr = :cusema ';
		$qryArray["cusema"] = $CusEma;
		$sql .= ' AND o.discod = :discod ';
		$qryArray["discod"] = $DisCod;

		$sql .= ' GROUP BY o.ord_id ORDER BY o.invdat DESC';

		echo $sql;

		return $this->run($sql, $qryArray, true);

	}


	function selectFinancial($Sta_ID="10,20") {
	
		$sql = "SELECT 
    EXTRACT(MONTH FROM o.invdat) as month, 
	MONTHNAME(o.invdat) as monthname,
    EXTRACT(YEAR FROM o.invdat) as year,
	SUM(ol.numuni * ol.unipri) as total
FROM 
    orders o
INNER JOIN orderline ol ON ol.ord_id = o.ord_id
WHERE o.sta_id IN (".$Sta_ID.")
GROUP BY 
    month, 
    year
ORDER BY 
    year DESC, 
    month DESC";
	$qryArray = array();
	return $this->run($sql, $qryArray, false);
		
	}
	
	function update($OrdCls = NULL) {
		
		if (is_null($OrdCls) || !$OrdCls) return 'No Record To Update';
		
		$sql = '';
		
		$qryArray = array();
		
		if ($OrdCls->ord_id == 0) {
			
			$qryArray["ordtyp"] = $OrdCls->ordtyp;
			$qryArray["invdat"] = $OrdCls->invdat;
			$qryArray["duedat"] = $OrdCls->duedat;
			$qryArray["paydat"] = $OrdCls->paydat;
			$qryArray["cusnam"] = $OrdCls->cusnam;
			$qryArray["adr1"] = $OrdCls->adr1;
			$qryArray["adr2"] = $OrdCls->adr2;
			$qryArray["adr3"] = $OrdCls->adr3;
			$qryArray["adr4"] = $OrdCls->adr4;
			$qryArray["pstcod"] = $OrdCls->pstcod;
			$qryArray["payadr1"] = $OrdCls->payadr1;
			$qryArray["payadr2"] = $OrdCls->payadr2;
			$qryArray["payadr3"] = $OrdCls->payadr3;
			$qryArray["payadr4"] = $OrdCls->payadr4;
			$qryArray["paypstcod"] = $OrdCls->paypstcod;
			$qryArray["paytrm"] = $OrdCls->paytrm;
			$qryArray["vatrat"] = $OrdCls->vatrat;
			$qryArray["tblnam"] = $OrdCls->tblnam;
			$qryArray["tbl_id"] = $OrdCls->tbl_id;
			$qryArray["sta_id"] = $OrdCls->sta_id;

            $qryArray["altref"] = $OrdCls->altref;
            $qryArray["altnam"] = $OrdCls->altnam;
            $qryArray["del_id"] = $OrdCls->del_id;
            $qryArray["discod"] = $OrdCls->discod;
            $qryArray["emaadr"] = $OrdCls->emaadr;
			
			$sql = "INSERT INTO orders
					(
					
					ordtyp,
					invdat,
					duedat,
					paydat,
					cusnam,
					adr1,
					adr2,
					adr3,
					adr4,
					pstcod,
					payadr1,
					payadr2,
					payadr3,
					payadr4,
					paypstcod,
					paytrm,
					vatrat,
					tblnam,
					tbl_id,
					sta_id,
					altref,
					altnam,
					del_id,
					discod,
					emaadr
					
					)
					VALUES
					(
					
					:ordtyp,
					:invdat,
					:duedat,
					:paydat,
					:cusnam,
					:adr1,
					:adr2,
					:adr3,
					:adr4,
					:pstcod,
					:payadr1,
					:payadr2,
					:payadr3,
					:payadr4,
					:paypstcod,
					:paytrm,
					:vatrat,
					:tblnam,
					:tbl_id,
					:sta_id,
					:altref,
					:altnam,
					:del_id,
					:discod,
					:emaadr

					);";
						
		} else {
			
			$qryArray["ordtyp"] = $OrdCls->ordtyp;
			$qryArray["invdat"] = $OrdCls->invdat;
			$qryArray["duedat"] = $OrdCls->duedat;
			$qryArray["paydat"] = $OrdCls->paydat;
			$qryArray["cusnam"] = $OrdCls->cusnam;
			$qryArray["adr1"] = $OrdCls->adr1;
			$qryArray["adr2"] = $OrdCls->adr2;
			$qryArray["adr3"] = $OrdCls->adr3;
			$qryArray["adr4"] = $OrdCls->adr4;
			$qryArray["pstcod"] = $OrdCls->pstcod;
			$qryArray["payadr1"] = $OrdCls->payadr1;
			$qryArray["payadr2"] = $OrdCls->payadr2;
			$qryArray["payadr3"] = $OrdCls->payadr3;
			$qryArray["payadr4"] = $OrdCls->payadr4;
			$qryArray["paypstcod"] = $OrdCls->paypstcod;
			$qryArray["paytrm"] = $OrdCls->paytrm;
			$qryArray["vatrat"] = $OrdCls->vatrat;
			$qryArray["tblnam"] = $OrdCls->tblnam;
			$qryArray["tbl_id"] = $OrdCls->tbl_id;
			$qryArray["sta_id"] = $OrdCls->sta_id;

            $qryArray["altref"] = $OrdCls->altref;
            $qryArray["altnam"] = $OrdCls->altnam;
            $qryArray["del_id"] = $OrdCls->del_id;
            $qryArray["discod"] = $OrdCls->discod;
            $qryArray["emaadr"] = $OrdCls->emaadr;
			
			$sql = "UPDATE orders
					SET
					
					ordtyp = :ordtyp,
					invdat = :invdat,
					duedat = :duedat,
					paydat = :paydat,
					cusnam = :cusnam,
					adr1 = :adr1,
					adr2 = :adr2,
					adr3 = :adr3,
					adr4 = :adr4,
					pstcod = :pstcod,
					payadr1 = :payadr1,
					payadr2 = :payadr2,
					payadr3 = :payadr3,
					payadr4 = :payadr4,
					paypstcod = :paypstcod,
					paytrm = :paytrm,
					vatrat = :vatrat,
					tblnam = :tblnam,
					tbl_id = :tbl_id,
					sta_id = :sta_id,
					altref = :altref,
					altnam = :altnam,
					del_id = :del_id,
					discod = :discod,
					emaadr = :emaadr";
				
			$sql .= " WHERE ord_id = :ord_id";
			$qryArray["ord_id"] = $OrdCls->ord_id;
			
		}

		$recordSet = $this->dbConn->prepare($sql);
		$recordSet->execute($qryArray);
		
		return ($OrdCls->ord_id == 0) ? $this->dbConn->lastInsertId('ord_id') : $OrdCls->ord_id;
	}
	
	function delete($Ord_ID = NULL) {
	
		try {
			
			if (!is_null($Ord_ID)) {
				$qryArray = array();
				$sql = 'DELETE FROM orders WHERE ord_id = :ord_id ';
				$qryArray["ord_id"] = $Ord_ID;
				
				$recordSet = $this->dbConn->prepare($sql);
				$recordSet->execute($qryArray);

                $qryArray = array();
                $sql = 'DELETE FROM orderlines WHERE ord_id = :ord_id ';
                $qryArray["ord_id"] = $Ord_ID;

                $recordSet = $this->dbConn->prepare($sql);
                $recordSet->execute($qryArray);

				//
				// DELETE ATTRIBUTES
				//
				
				//
				// DELETE IMAGES
				//
				
				return $Ord_ID;
				
			}
			
		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}
		
	}
	
}

?>