<?php



//

// Email Template class

//



class EmtDAO extends db {



    function select($Emt_ID = NULL, $TblNam=NULL, $Tbl_ID=NULL, $EmtNam = NULL, $ReqObj = false) {



        $qryArray = array();

        $sql = 'SELECT

				emt_id,

				tblnam,

				tbl_id,

				emtnam,

				sta_id

				FROM emailtemplate WHERE true';



        if (!is_null($Emt_ID)) {

            $sql .= ' AND emt_id = :emt_id ';

            $qryArray["emt_id"] = $Emt_ID;

        } else {



            if (!is_null($TblNam)) {

                $sql .= ' AND tblnam = :tblnam ';

                $qryArray["tblnam"] = $TblNam;

            }



            if (!is_null($Tbl_ID) && is_numeric($Tbl_ID)) {

                $sql .= ' AND tbl_id LIKE :tbl_id ';

                $qryArray["tbl_id"] = $Tbl_ID;

            }



            if (!is_null($EmtNam)) {

                $EmtNam = '%'.$EmtNam.'%';

                $sql .= ' AND emtnam LIKE :emtnam ';

                $qryArray["emtnam"] = $EmtNam;

            }

        }



        //print_r($qryArray);

        //echo $sql;



        //$this->displayQuery($sql, $qryArray);

        return $this->run($sql, $qryArray, $ReqObj);



    }



    function update($EmtCls = NULL) {



        if (is_null($EmtCls) || !$EmtCls) return 'No Record To Update';



        $sql = '';



        $qryArray = array();



        if ($EmtCls->emt_id == 0) {


            $qryArray["emtnam"] = $EmtCls->emtnam;

            $qryArray["tblnam"] = $EmtCls->tblnam;

            $qryArray["tbl_id"] = $EmtCls->tbl_id;

            $qryArray["sta_id"] = $EmtCls->sta_id;



            $sql = "INSERT INTO emailtemplate
					(

					emtnam,

					tblnam,

					tbl_id,

					sta_id

					)

					VALUES

					(

					:emtnam,

					:tblnam,

					:tbl_id,

					:sta_id

					);";



        } else {



            $qryArray["emtnam"] = $EmtCls->emtnam;

            $qryArray["tblnam"] = $EmtCls->tblnam;

            $qryArray["tbl_id"] = $EmtCls->tbl_id;

            $qryArray["sta_id"] = $EmtCls->sta_id;



            $sql = "UPDATE emailtemplate

					SET

					

					emtnam = :emtnam,

					tblnam = :tblnam,

					tbl_id = :tbl_id,

					sta_id = :sta_id";



            $sql .= " WHERE emt_id = :emt_id";

            $qryArray["emt_id"] = $EmtCls->emt_id;



        }



//        echo $sql."<br>";
//        print_r($qryArray);


        $recordSet = $this->dbConn->prepare($sql);

        $recordSet->execute($qryArray);



        return ($EmtCls->emt_id == 0) ? $this->dbConn->lastInsertId('emt_id') : $EmtCls->emt_id;

    }



    function delete($Emt_ID = NULL) {



        try {



            if (!is_null($Emt_ID)) {

                $qryArray = array();

                $sql = 'DELETE FROM emailtemplate WHERE emt_id = :emt_id ';

                $qryArray["emt_id"] = $Emt_ID;



                $recordSet = $this->dbConn->prepare($sql);

                $recordSet->execute($qryArray);



                $qryArray = array();

                $sql = 'DELETE FROM emailsections WHERE emt_id = :emt_id ';

                $qryArray["emt_id"] = $Emt_ID;



                $recordSet = $this->dbConn->prepare($sql);

                $recordSet->execute($qryArray);



                return $Emt_ID;



            }



        } catch(PDOException $e) {

            echo 'ERROR: ' . $e->getMessage();

        }



    }



}



?>