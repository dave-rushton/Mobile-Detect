<?php

class EmsDAO extends db {
    function select($Emt_ID = NULL, $Ems_ID = NULL, $ReqObj = false) {
        $qryArray = array();

        $sql = 'SELECT
				ems_id,
				emt_id,
				emstyp,
				emsfil,
				emsobj,
				srtord,
				sta_id
				FROM emailsections WHERE true';


        if (!is_null($Ems_ID)) {

            $sql .= ' AND ems_id = :ems_id ';

            $qryArray["ems_id"] = $Ems_ID;

        }
        else if (!is_null($Emt_ID)) {

            $sql .= ' AND emt_id = :emt_id ';

            $qryArray["emt_id"] = $Emt_ID;

        } else {

        }

        $sql .= ' ORDER BY srtord ';

        return $this->run($sql, $qryArray, $ReqObj);
    }

    function update($EmsCls = NULL) {



        if (is_null($EmsCls) || !$EmsCls) return 'No Record To Update';



        $sql = '';



        $qryArray = array();



        if ($EmsCls->ems_id == 0) {

            $qryArray["emt_id"] = $EmsCls->emt_id;

            $qryArray["emstyp"] = $EmsCls->emstyp;

            $qryArray["emsfil"] = $EmsCls->emsfil;

            $qryArray["emsobj"] = $EmsCls->emsobj;

            $qryArray["srtord"] = $EmsCls->srtord;

            $qryArray["sta_id"] = $EmsCls->sta_id;


            $sql = "INSERT INTO emailsections
					(
					emt_id,
					emstyp,
					emsfil,
					emsobj,
					srtord,
					sta_id
					)
					VALUES
					(
					:emt_id,
					:emstyp,
					:emsfil,
					:emsobj,
					:srtord,
					:sta_id
					);";



        } else {

            $qryArray["emt_id"] = $EmsCls->emt_id;

            $qryArray["emstyp"] = $EmsCls->emstyp;

            $qryArray["emsfil"] = $EmsCls->emsfil;

            $qryArray["emsobj"] = $EmsCls->emsobj;

            $qryArray["srtord"] = $EmsCls->srtord;

            $qryArray["sta_id"] = $EmsCls->sta_id;


            $sql = "UPDATE emailsections
					SET
					emt_id = :emt_id,
					emstyp = :emstyp,
					emsfil = :emsfil,
					emsobj = :emsobj,
					srtord = :srtord,
					sta_id = :sta_id
					";



            $sql .= " WHERE ems_id = :ems_id";

            $qryArray["ems_id"] = $EmsCls->ems_id;


        }


//        echo $sql."<br>";
//        print_r($qryArray);


        $recordSet = $this->dbConn->prepare($sql);

        $recordSet->execute($qryArray);

        return ($EmsCls->ems_id == 0) ? $this->dbConn->lastInsertId('ems_id') : $EmsCls->ems_id;
    }

    function delete($Ems_ID = NULL) {

        try {

            if (!is_null($Ems_ID)) {

                $qryArray = array();

                $sql = 'DELETE FROM emailsections WHERE ems_id = :ems_id ';

                $qryArray["ems_id"] = $Ems_ID;

                $recordSet = $this->dbConn->prepare($sql);

                $recordSet->execute($qryArray);

                return $Ems_ID;
            }

        } catch(PDOException $e) {

            echo 'ERROR: ' . $e->getMessage();

        }

    }

    function resort ($Ems_ID=NULL) {

        if (!is_null($Ems_ID)) {

            $SecLst = explode(",", $Ems_ID);

            for ($l=0; $l < count($SecLst); $l++) {

                $qryArray = array();
                $sql = 'UPDATE emailsections SET srtord = :srtord WHERE ems_id = :ems_id ';
                $qryArray["srtord"] = $l;
                $qryArray["ems_id"] = $SecLst[$l];

                $recordSet = $this->dbConn->prepare($sql);
                $recordSet->execute($qryArray);

            }

        }

    }
}