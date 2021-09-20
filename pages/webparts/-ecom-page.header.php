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



<header>
    <div class="container">
        <div class="row">
            <div class="col-sm-4 col-xs-12 abs col-sm-offset-4">
                <div class="logo">
                    <a href="<?php echo $patchworks->webRoot; ?>">
                        <img src="pages/img/logo.jpg" alt="E.A Tailby LTD">
                    </a>
                </div>
            </div>
            <div class="col-sm-4 col-xs-12 pull-left ">
                <div class="contact-wrapper">
                    <div class="ico-wrapper">
                        <a href="mailto:info@tailby.com">
                            <span class="ico">
                                <img src="pages/img/email.png" alt="">
                            </span>
                            <span class="desc">
                                info@tailby.com
                            </span>
                        </a>
                    </div>
                    <div class="ico-wrapper">
                        <a href="tel:+441536512639">
                            <span class="ico">
                                <img src="pages/img/phone.png" alt="">
                            </span>
                            <span class="desc">
                                   +44 (0)1536 512639
                            </span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-xs-12 pull-right">
                <div class="basket-wrapper">
                    <div class="ico-wrapper">
                        <a href="mailto:info@tailby.com">
                            <span class="desc">
                                Basket
                            </span>
                            <span class="ico">
                                <img src="pages/img/bag.png" alt="Basket">
                            </span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-xs-12">
                <div id="menubtnwrapper">
                    <div id="menubtn">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<div class="nav-wrapper">
    <div class="container">
        <nav>
            <?php echo $pageHandler->getMenu($_GET['seourl'], NULL, NULL, NULL, NULL); ?>
        </nav>
    </div>
</div>

