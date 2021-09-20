<?php



include('../../config/config.php');

include('../../admin/patchworks.php');

include('classes/shoppingcart.cls.php');



$shoppingcart = new shoppingCart();

//$shoppingcart->clearCart();

//$shoppingcart->addProduct(8029,5);

//$shoppingcart->removeProduct(8029,1);

//$shoppingcart->updateQty(8029,10);

//$shoppingcart->setDelivery(2);

//$shoppingcart->checkMultibuy();



//echo $shoppingcart->calcCartPrice();

//echo $shoppingcart->setDiscount('TEST2');



echo $shoppingcart->totalPrice;







echo '<pre>'.print_r($shoppingcart).'</pre>';



?>