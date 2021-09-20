<?php
date_default_timezone_set('Europe/London');
session_start();

ini_set('memory_limit', '-1');

//
// DB Connection Class
//

class db extends config {

//	var string $recordSet;
//  var $dbConn;

	function __construct() {
		try {
			$this->dbConn = new PDO($this->connStr, $this->user, $this->password);
			$this->dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch(PDOException $e) {
			//echo '<p>ERROR: ' . $e->getMessage(),'</p>';
		}
	}

	function run ($sqlQuery=NULL, $qryArray=NULL, $requestObject=false) {

//		echo '<p>'.$sqlQuery.'<br>';
//		echo var_dump($qryArray).'<br></p>';

		if (!is_null($sqlQuery) && !is_null($qryArray) && is_array($qryArray)) {

            try {
                $this->dbConn = new PDO($this->connStr, $this->user, $this->password);
                $this->dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $e) {
                //echo '<p>ERROR: ' . $e->getMessage(),'</p>';
            }

			try {
				$recordSet = $this->dbConn->prepare($sqlQuery);
				$recordSet->execute($qryArray);

				return (!$requestObject) ? $recordSet->fetchAll() : $recordSet->fetch(PDO::FETCH_OBJ);

			} catch(PDOException $err) {

				return NULL;
			}

		}

	}

    function displayQuery ($sql = '', $qryArray = NULL) {

        if (is_array($qryArray)) {

            while (list($key, $val) = each($qryArray)) {

                if (is_numeric($val)) {
                    $sql = str_replace(":".$key, $val, $sql);
                } else {
                    $sql = str_replace(":".$key, "'".$val."'", $sql);
                }

            }

            echo '<p>'.$sql.'</p>';

        }

    }


    function logFile ($displayText = '') {

        $file = $this->docRoot.'pw_cms.txt';

        $f1 = fopen($file, "a");
        $output = $current = '['.date("Y-m-d H:i:s")."] ".$displayText . PHP_EOL;
        fwrite($f1, $output);
        fclose($f1);

    }

}

//
// PatchWorks Config
//

class pw extends db {

	public $dbConn = '';

	function pw() {
		$db = new db;
		$this->dbConn = $db->dbConn;
	}

    function getJSONVariable($JSONstr=NULL, $VarNam=NULL, $strip=true ) {

        if ($strip) {
            $eleVarArr = json_decode(stripslashes($JSONstr), true);
        } else {
            $eleVarArr = json_decode($JSONstr, true);
        }

        if (is_array($eleVarArr) && !is_null($VarNam)) {
            for ($i = 0; $i < count($eleVarArr); ++$i) {

                foreach($eleVarArr[$i] as $key => $item) {
                    if ($item === $VarNam) {
                        return $eleVarArr[$i]['value'];
                    }
                }
            }
        }
        return '';
    }

}

//
// AUTHENTICATION class
//

class AuthDAO extends db {

	function login($UsrEma=NULL,$PasWrd=NULL) {

		if (is_null($UsrEma) || is_null($PasWrd)) return NULL;

		$qryArray = array();
		$sql = 'SELECT 
				usr_id,
				usrema,
				usracc
				FROM users WHERE TRUE
				AND usrema = :usrema
				AND paswrd = :paswrd ';
		$qryArray["usrema"] = $UsrEma;
		$qryArray["paswrd"] = hash('sha512',$this->salt.$PasWrd); //md5($PasWrd);

		$userRec = $this->run($sql, $qryArray);

//		$recordSet = $this->dbConn->prepare($sql);
//		$recordSet->execute($qryArray);

		if (count($userRec) < 1) return NULL;

		$Log_ID = md5($userRec[0]['usrema'].date("sYimHd") );

		unset($qryArray);

		$qryArray = array();
		$sql = 'UPDATE users SET
				log_id = :log_id
				WHERE usr_id = :usr_id';
		$qryArray["log_id"] = $Log_ID;
		$qryArray["usr_id"] = $userRec[0]['usr_id'];

//		$this->run($sql, $qryArray);

//		For obscure logins
		$recordSet = $this->dbConn->prepare($sql);
		$recordSet->execute($qryArray);

		unset($qryArray);
		$qryArray = array();
		$sql = 'SELECT 
				usr_id,
				usrnam,
				paswrd,
				usrema,
				usracc,
				sta_id,
				log_id 
				FROM users WHERE 
				usr_id = :usr_id';
		$qryArray["usr_id"] = $userRec[0]['usr_id'];

		return $this->run($sql, $qryArray);

	}

	function loggedIn($Log_ID=NULL) {

		if (is_null($Log_ID)) return NULL;

		$qryArray = array();
		$sql = "SELECT 
				usr_id,
				log_id
				FROM users WHERE
				log_id = :log_id AND log_id != ''";
		$qryArray["log_id"] = $Log_ID;

		$userRec = $this->run($sql, $qryArray, true);

		return ($userRec) ? $userRec->usr_id : 0; //count($userRec);

	}

}

$patchworks = new pw();

?>