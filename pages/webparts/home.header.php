<?php

include_once("../admin/products/classes/products.cls.php");
include_once("../admin/products/classes/product_types.cls.php");
include_once("../admin/system/classes/places.cls.php");

$PrtDao = new PrtDAO();

$shoppingCart = (isset($_POST['cart'])) ? json_decode($_POST['cart'], true) : array();

if (isset($_SESSION['cart'])) $shoppingCart = json_decode($_SESSION['cart'], true);

$totalItems = 0;
$totalAmount = 0;

if (isset($shoppingCart['items']) && is_array($shoppingCart['items'])) {
    for ($i=0;$i<count($shoppingCart['items']);$i++) {

        //$uploads = $PrtDao->getProductVariantImage($shoppingCart['items'][$i]['prd_id']);
        //$imageURL = (isset($uploads[0]['filnam'])) ? $uploads[0]['filnam'] : '';

        $totalItems += $shoppingCart['items'][$i]['qty'];
        @$totalAmount += ($shoppingCart['items'][$i]['totamt'] * $shoppingCart['items'][$i]['qty']);

    }
}


$PwdTok = (isset($_POST['loginToken'])) ? $_POST['loginToken'] : '';
if (isset($_SESSION['loginToken'])) $PwdTok = $_SESSION['loginToken'];

$PlaDao = new PlaDAO();
$loggedIn = $PlaDao->loggedIn($PwdTok);


include('cookie.php');


?>




<a href="<?php echo $patchworks->webRoot; ?>" id="logo"></a>
<div id="mainMenu">
    <?php echo $pageHandler->getMenu($_GET['seourl'], NULL, NULL, NULL, NULL); ?>
</div>
