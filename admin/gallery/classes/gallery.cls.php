<?php

class GalDAO extends db {
	
	function select($Gal_ID = NULL, $TblNam=NULL, $Tbl_ID=NULL, $GalNam = NULL, $ReqObj = false) { 
	
		$qryArray = array();
		$sql = 'SELECT
				gal_id,
				galnam,
				tblnam,
				tbl_id,
				keydsc,
				seourl,
				keywrd,
				keydsc,
				sta_id,
				imgsiz
				FROM gallery WHERE true';
		
		if (!is_null($Gal_ID)) {
			$sql .= ' AND gal_id = :gal_id ';
			$qryArray["gal_id"] = $Gal_ID;
		} else {
			
			if (!is_null($TblNam)) {
				$sql .= ' AND tblnam = :tblnam ';
				$qryArray["tblnam"] = $TblNam;
			}
			
			if (!is_null($Tbl_ID) && is_numeric($Tbl_ID)) {
				$sql .= ' AND tbl_id LIKE :tbl_id ';
				$qryArray["tbl_id"] = $Tbl_ID;
			}
			
			if (!is_null($GalNam)) {
				$GalNam = '%'.$GalNam.'%';
				$sql .= ' AND galnam LIKE :galnam ';
				$qryArray["galnam"] = $GalNam;
			}

            $sql .= ' ORDER BY srtord ';
		}

		return $this->run($sql, $qryArray, $ReqObj);
	}
	
	function update($GalCls = NULL) {
	
		if (is_null($GalCls) || !$GalCls) return 'No Record To Update';
		
		$sql = '';
		
		$qryArray = array();

        $qryArray["galnam"] = $GalCls->galnam;
        $qryArray["tblnam"] = $GalCls->tblnam;
        $qryArray["tbl_id"] = $GalCls->tbl_id;
        $qryArray["seourl"] = $GalCls->seourl;
        $qryArray["keywrd"] = $GalCls->keywrd;
        $qryArray["keydsc"] = $GalCls->keydsc;
        $qryArray["sta_id"] = $GalCls->sta_id;
        $qryArray["imgsiz"] = $GalCls->imgsiz;

		if ($GalCls->gal_id == 0) {
			$sql = "INSERT INTO gallery
					(
					galnam,
					tblnam,
					tbl_id,
					seourl,
					keywrd,
					keydsc,
					sta_id,
					imgsiz
					)
					VALUES
					(
					:galnam,
					:tblnam,
					:tbl_id,
					:seourl,
					:keywrd,
					:keydsc,
					:sta_id,
					:imgsiz
					);";
		} else {

            $qryArray["gal_id"] = $GalCls->gal_id;
			
			$sql = "UPDATE gallery
					SET
					
					galnam = :galnam,
					tblnam = :tblnam,
					tbl_id = :tbl_id,
					seourl = :seourl,
					keywrd = :keywrd,
					keydsc = :keydsc,
					sta_id = :sta_id,
					imgsiz = :imgsiz";
				
			$sql .= " WHERE gal_id = :gal_id";

		}
		
		$recordSet = $this->dbConn->prepare($sql);
		$recordSet->execute($qryArray);

		return ($GalCls->gal_id == 0) ? $this->dbConn->lastInsertId('gal_id') : $GalCls->gal_id;
	}
	
	function delete($Gal_ID = NULL) {
	
		try {
			if (!is_null($Gal_ID)) {
				$qryArray = array();
				$sql = 'DELETE FROM gallery WHERE gal_id = :gal_id ';
				$qryArray["gal_id"] = $Gal_ID;
				
				$recordSet = $this->dbConn->prepare($sql);
				$recordSet->execute($qryArray);
				
				$this->deleteUploads('WEBGALLERY', $Gal_ID);
				
				$qryArray = array();
				$sql = 'DELETE FROM uploads WHERE tblnam = :tblnam AND tbl_id = :tbl_id ';
				$qryArray["tblnam"] = 'WEBGALLERY';
				$qryArray["tbl_id"] = $Gal_ID;
				
				$recordSet = $this->dbConn->prepare($sql);
				$recordSet->execute($qryArray);

			}
		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}

        return $Gal_ID;
	}

	function deleteUploads($TblNam=NULL, $Tbl_ID=NULL) {

		$qryArray = array();
		$sql = 'SELECT
         filnam
         FROM uploads WHERE tblnam = :tblnam AND tbl_id = :tbl_id ';
		$qryArray["tblnam"] = $TblNam;
		$qryArray["tbl_id"] = $Tbl_ID;

		$TmpUpl = $this->run($sql, $qryArray);

		$tableLength = count($TmpUpl);

		for ($i=0;$i<$tableLength;++$i) {
			$sql1 = 'SELECT
         filnam
         FROM uploads WHERE filnam = :filnam';
			$qryArray1["filnam"] = $TmpUpl[$i]['filnam'];
			$Tmp = $this->run($sql1, $qryArray1);
			if(count($Tmp)<=1){
				$this->deleteFile($this->docRoot, $TmpUpl[$i]['filnam']);
			}
		}
	}
	
	function deleteFile( $searchPath = '.', $searchFile = '' ){
		$ignoreArray = array( 'cgi-bin', '.', '..', '.svn' );
		$searchDir = @opendir( $searchPath );
		while( false !== ( $currentFile = readdir( $searchDir ) ) ){
			if( !in_array( $currentFile, $ignoreArray ) ){
				if( is_dir( $searchPath.'/'.$currentFile ) ){
					$this->deleteFile( $searchPath.'/'.$currentFile, $searchFile );
				} else if ( $searchFile != '') {
					if ($currentFile == $searchFile) unlink( $searchPath.'/'.$currentFile );
				}
			}
		}
		
		closedir( $searchDir );
	}
}