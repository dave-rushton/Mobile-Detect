<?php

//
// Order line class
//

class OlnDAO extends db {
	
	function select($Ord_ID = NULL, $Oln_ID = NULL, $ReqObj=false) { 
	
		$qryArray = array();
		$sql = 'SELECT
				o.oln_id,
				o.ord_id,
				o.prd_id,
				o.numuni,
				o.unipri,
				o.vatrat,
				o.olndsc,
				o.tblnam,
				o.tbl_id,
				o.sta_id,
				p.prdnam
				FROM orderline o 
				LEFT OUTER JOIN products p ON o.prd_id = p.prd_id
				WHERE TRUE';
		
		if (!is_null($Oln_ID)) {
			$sql .= ' AND oln_id = :oln_id ';
			$qryArray["oln_id"] = $Oln_ID;
		} else {
			if (!is_null($Ord_ID) && is_numeric($Ord_ID)) {
				$sql .= ' AND ord_id = :ord_id ';
				$qryArray["ord_id"] = $Ord_ID;
			}
		}

		return $this->run($sql, $qryArray, $ReqObj);

	}
	
	function update($OnlCls = NULL) {
	
		if (is_null($OnlCls) || !$OnlCls) return 'No Record To Update';
		
		$sql = '';
		
		$qryArray = array();
		
		if ($OnlCls->oln_id == 0) {
						
			$qryArray["ord_id"] = $OnlCls->ord_id;
			$qryArray["prd_id"] = $OnlCls->prd_id;
			$qryArray["numuni"] = $OnlCls->numuni;
			$qryArray["unipri"] = $OnlCls->unipri;
			$qryArray["vatrat"] = $OnlCls->vatrat;
			$qryArray["olndsc"] = $OnlCls->olndsc;
			$qryArray["tblnam"] = $OnlCls->tblnam;
			$qryArray["tbl_id"] = $OnlCls->tbl_id;
			$qryArray["sta_id"] = $OnlCls->sta_id;
			
			$sql = "INSERT INTO orderline
					(
					ord_id,
					prd_id,
					numuni,
					unipri,
					vatrat,
					olndsc,
					tblnam,
					tbl_id,
					sta_id
					
					)
					VALUES
					(
					:ord_id,
					:prd_id,
					:numuni,
					:unipri,
					:vatrat,
					:olndsc,
					:tblnam,
					:tbl_id,
					:sta_id
					
					);";
						
		} else {
			
			$qryArray["ord_id"] = $OnlCls->ord_id;
			$qryArray["prd_id"] = $OnlCls->prd_id;
			$qryArray["numuni"] = $OnlCls->numuni;
			$qryArray["unipri"] = $OnlCls->unipri;
			$qryArray["vatrat"] = $OnlCls->vatrat;
			$qryArray["olndsc"] = $OnlCls->olndsc;
			$qryArray["tblnam"] = $OnlCls->tblnam;
			$qryArray["tbl_id"] = $OnlCls->tbl_id;
			$qryArray["sta_id"] = $OnlCls->sta_id;
			
			$sql = "UPDATE orderline
					SET
					
					ord_id = :ord_id,
					prd_id = :prd_id,
					numuni = :numuni,
					unipri = :unipri,
					vatrat = :vatrat,
					olndsc = :olndsc,
					tblnam = :tblnam,
					tbl_id = :tbl_id,
					sta_id = :sta_id";
				
			$sql .= " WHERE oln_id = :oln_id";
			$qryArray["oln_id"] = $OnlCls->oln_id;
			
		}
		
		//echo $sql;
		
		$recordSet = $this->dbConn->prepare($sql);
		$recordSet->execute($qryArray);
		
		return ($OnlCls->oln_id == 0) ? $this->dbConn->lastInsertId('oln_id') : $OnlCls->oln_id;
	}
	
	function delete($Oln_ID = NULL) {
	
		try {
			
			if (!is_null($Oln_ID)) {
				$qryArray = array();
				$sql = 'DELETE FROM orderline WHERE oln_id = :oln_id ';
				$qryArray["oln_id"] = $Oln_ID;
				
				$recordSet = $this->dbConn->prepare($sql);
				$recordSet->execute($qryArray);
				
				//
				// DELETE ATTRIBUTES
				//
				
				//
				// DELETE IMAGES
				//
				
				return $Oln_ID;
				
			}
			
		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}
		
	}
	
	function cleanLines($Ord_ID = NULL) {
	
		try {
			
			if (!is_null($Ord_ID)) {
				$qryArray = array();
				$sql = 'DELETE FROM orderline WHERE ord_id = :ord_id ';
				$qryArray["ord_id"] = $Ord_ID;
				
				$recordSet = $this->dbConn->prepare($sql);
				$recordSet->execute($qryArray);
				
			}
			
		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}
		
	}
	
}

?>