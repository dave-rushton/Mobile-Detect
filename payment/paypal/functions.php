<?php
// functions.php
function check_txnid($tnxid){

//	global $link;
//	return true;

	$valid_txnid = true;

    $qryArray = array();
    $sql = 'SELECT * FROM payments WHERE txnid = :txnid';
    $qryArray['txnid'] = $tnxid;


    $file = 'paypal.txt';
    $current = file_get_contents($file);
    $current .= $sql."\n";
    file_put_contents($file, $current);


    $payments = $patchworks->run($sql, $qryArray);

    if (count($payments) > 0) $valid_txnid = false;

	return $valid_txnid;

}

function check_price($price, $id){
	$valid_price = false;
	//you could use the below to check whether the correct price has been paid for the product
	
	/*
	$sql = mysql_query("SELECT amount FROM `products` WHERE id = '$id'");
	if (mysql_num_rows($sql) != 0) {
		while ($row = mysql_fetch_array($sql)) {
			$num = (float)$row['amount'];
			if($num == $price){
				$valid_price = true;
			}
		}
	}
	return $valid_price;
	*/
	return true;
}

function updatePayments($data){

    $file = 'paypal.txt';
    $current = file_get_contents($file);
    $current .= "INSERT RECORD\n";
    file_put_contents($file, $current);
	
	if (is_array($data)) {

        $qryArray = array();
		$sql = "INSERT INTO `payments` (txnid, payment_amount, payment_status, itemid, createdtime) VALUES (
				'".$data['txn_id']."' ,
				'".$data['payment_amount']."' ,
				'".$data['payment_status']."' ,
				'".$data['item_number']."' ,
				'".date("Y-m-d H:i:s")."'
				)";

        $payment = $patchworks->run($sql, $qryArray);

        $file = 'paypal.txt';
        $current = file_get_contents($file);
        $current .= $sql."\n";
        file_put_contents($file, $current);

        return $payment->id;

		//return mysql_insert_id($link);
	}

}
