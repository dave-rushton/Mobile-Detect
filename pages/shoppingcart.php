<?php

require_once("../config/config.php");
require_once("../admin/patchworks.php");
require_once("../admin/website/classes/page.handler.php");
require_once("../admin/system/classes/places.cls.php");

require_once("../admin/products/classes/product_types.cls.php");
require_once("../admin/products/classes/products.cls.php");
require_once("../admin/gallery/classes/uploads.cls.php");
require_once("../admin/system/classes/related.cls.php");

require_once("../admin/ecommerce/classes/delivery.cls.php");
//require_once("../admin/ecommerce/classes/deliveryextra.cls.php");
require_once("../admin/ecommerce/classes/discounts.cls.php");
require_once("../admin/ecommerce/classes/ecommprop.cls.php");
require_once("../pages/shoppingcart/classes/shoppingcart.cls.php");
$shoppingcart = new shoppingCart();

$TmpPrd = new PrdDAO();

$PwdTok = (isset($_SESSION['loginToken'])) ? $_SESSION['loginToken'] : '';
$PlaDao = new PlaDAO();
$loggedIn = $PlaDao->loggedIn($PwdTok);

$pageHandler = new pageHandler();

$_GET['seourl'] = '';

$TmpDis = new DisDAO();
$discounts = $TmpDis->select();
$allowDiscount = (count($discounts) > 0) ? true : false;

$TmpDel = new DelDAO();

$TmpEco = new EcoDAO();
$eCommProp = $TmpEco->select(true);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1">
    <meta name="format-detection" content="telephone=no">
    <link rel="icon" href="favicon.ico" type="image/x-icon"/>

    <title>Shopping Basket</title>

    <base href="<?php echo $patchworks->webRoot; ?>"/>

    <?php echo $pageHandler->critialCSS(); ?>

    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">

    <script src="pages/js/jquery.js"></script>
    <script src="pages/js/cms-shoppingcart.js"></script>

    <link rel="stylesheet" type="text/css" href="pages/css/shoppingcart.css">
</head>

<body>

<?php
include('webparts/page.header.php'); ?>



<div id="shoppingCartWrapper">


<?php

//echo '<pre>'.print_r($shoppingcart->shoppingCart['items']).'</pre>';

if ($_GET['action'] == 'shoppingcart') {

    include('shoppingcart/shoppingcart.php');

} else if ($_GET['action'] == 'details') {

    include('shoppingcart/customer_details.php');

} else if ($_GET['action'] == 'options') {

    include('shoppingcart/cart_options.php');

} else if ($_GET['action'] == 'complete') {

    include('shoppingcart/ordercomplete.php');

} else if ($_GET['action'] == 'fail') {

    include('shoppingcart/orderfail.php');

} else if ($_GET['action'] == 'login') {

    include('account/user.login.php');

}

?>

</div>

<?php include('webparts/page.footer.php'); ?>

</body>

</html>
