<?php

//
// Payments class
//

class PayDAO extends db {

    function check_txnid($tnxid = NULL) {
        
        $qryArray = array();
        $sql = 'SELECT * FROM payments WHERE txnid = :txnid';
        $qryArray['txnid'] = $tnxid;

        return $this->run($sql, $qryArray, false);

    }

    function update($PayCls = NULL) {

        if (is_null($PayCls) || !$PayCls) return 'No Record To Update';

        $sql = '';

        $qryArray = array();

        if ($PayCls->id == 0) {

            $qryArray["txnid"] = $PayCls->txnid;
            $qryArray["payment_amount"] = $PayCls->payment_amount;
            $qryArray["payment_status"] = $PayCls->payment_status;
            $qryArray["itemid"] = $PayCls->itemid;
            $qryArray["createdtime"] = $PayCls->createdtime;

            $sql = "INSERT INTO payments
					(
					txnid,
					payment_amount,
					payment_status,
					itemid,
					createdtime
					)
					VALUES
					(
					:txnid,
					:payment_amount,
					:payment_status,
					:itemid,
					:createdtime
					);";

        } else {

            $qryArray["txnid"] = $PayCls->txnid;
            $qryArray["payment_amount"] = $PayCls->payment_amount;
            $qryArray["payment_status"] = $PayCls->payment_status;
            $qryArray["itemid"] = $PayCls->itemid;
            $qryArray["createdtime"] = $PayCls->createdtime;

            $sql = "UPDATE payments
					SET
					txnid = :txnid,
					payment_amount = :payment_amount,
					payment_status = :payment_status,
					itemid = :itemid,
					createdtime = :createdtime";

            $sql .= " WHERE id = :id";
            $qryArray["id"] = $PayCls->id;

        }

        $recordSet = $this->dbConn->prepare($sql);
        $recordSet->execute($qryArray);

        return ($PayCls->id == 0) ? $this->dbConn->lastInsertId('id') : $PayCls->id;
    }

}

?>