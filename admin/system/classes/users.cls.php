<?php

//
// User class
//

class UsrDAO extends db {
	
	function select($Usr_ID = NULL, $UsrNam = NULL, $ReqObj = false) { 
	
		$qryArray = array();
		$sql = 'SELECT 
				usr_id,
				usrnam,
				paswrd,
				usrema,
				usracc,
				sta_id 
				FROM users';
		
		if (!is_null($Usr_ID)) {
			$sql .= ' WHERE usr_id = :usr_id ';
			$qryArray["usr_id"] = $Usr_ID;
		} else {
			if (!is_null($UsrNam)) {
				
				$UsrNam = '%'.$UsrNam.'%';
				
				$sql .= ' WHERE usrnam LIKE :usrnam ';
				$qryArray["usrnam"] = $UsrNam;
			}
		}

		return $this->run($sql, $qryArray, $ReqObj);

	}
	
	function update($UsrCls = NULL) {
	
		if (is_null($UsrCls) || !$UsrCls) return 'No Record To Update';
		
		$sql = '';
		
		$qryArray = array();
		
		if ($UsrCls->usr_id == 0) {
			
			$qryArray["usrnam"] = $UsrCls->usrnam;
//			$qryArray["paswrd"] = md5($UsrCls->paswrd);
			$qryArray["paswrd"] = hash('sha512',$this->salt.$UsrCls->paswrd);
			$qryArray["usrema"] = $UsrCls->usrema;
			$qryArray["usracc"] = $UsrCls->usracc;
			$qryArray["sta_id"] = $UsrCls->sta_id;
			
			$sql = "INSERT INTO users
					(
					usrnam,
					paswrd,
					usrema,
					sta_id,
					usracc
					)
					VALUES
					(
					:usrnam,
					:paswrd,
					:usrema,
					:sta_id,
					:usracc
					);";
						
		} else {
			
			$qryArray["usrnam"] = $UsrCls->usrnam;
			$qryArray["usrema"] = $UsrCls->usrema;
			$qryArray["usracc"] = $UsrCls->usracc;
			$qryArray["sta_id"] = $UsrCls->sta_id;
			
			$sql = "UPDATE users
					SET
					usrnam = :usrnam,
					usrema = :usrema,
					sta_id = :sta_id,
					usracc = :usracc";
				
			if ( $UsrCls->paswrd && $UsrCls->paswrd != "" ) {
				$sql .= ", paswrd = :paswrd";
//				$qryArray["paswrd"] = md5($UsrCls->paswrd);
				$qryArray["paswrd"] = hash('sha512',$this->salt.$UsrCls->paswrd);
			}
				
			$sql .= " WHERE usr_id = :usr_id";
			$qryArray["usr_id"] = $UsrCls->usr_id;
			
		}
		
		$recordSet = $this->dbConn->prepare($sql);
		$recordSet->execute($qryArray);
		
		return ($UsrCls->usr_id == 0) ? $this->dbConn->lastInsertId('usr_id') : $UsrCls->usr_id;
	}
	
	function delete($Usr_ID = NULL) {
	
		try {
			
			if (!is_null($Usr_ID)) {
				$qryArray = array();
				$sql = 'DELETE FROM users WHERE usr_id = :usr_id ';
				$qryArray["usr_id"] = $Usr_ID;
				
				$recordSet = $this->dbConn->prepare($sql);
				$recordSet->execute($qryArray);
				
				return $Usr_ID;
				
			}
			
		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}
		
	}
	
}

?>