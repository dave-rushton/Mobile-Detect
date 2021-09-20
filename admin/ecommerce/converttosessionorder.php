<?php

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("classes/order.cls.php");

$TmpOrd = new OrdDAO();
$orderRec = $TmpOrd->select($_GET['ord_id'], NULL, NULL, NULL, true);

$shoppingCart = json_decode($orderRec->ordobj, true);

unset($_SESSION['cart']);
$_SESSION['cart'] = json_encode($shoppingCart);


//print_r($_SESSION['cart']);

header('location: ' . $patchworks->webRoot);
exit();

?>