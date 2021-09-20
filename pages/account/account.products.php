<?php

require_once("../config/config.php");
require_once("../admin/patchworks.php");
require_once("../admin/system/classes/places.cls.php");
require_once("../admin/ecommerce/classes/order.cls.php");
require_once("../admin/ecommerce/classes/orderline.cls.php");

$PwdTok = (isset($_SESSION['loginToken'])) ? $_SESSION['loginToken'] : '';
$PlaDao = new PlaDAO();
$loggedIn = $PlaDao->loggedIn($PwdTok);

if (!$loggedIn) {
    header('location: login');
    exit();
}

?>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-3">

                <div class="box">

                    <?php include('account.menu.php'); ?>

                </div>

            </div>
            <div class="col-sm-9">

                <h2 class="heading">Your Products</h2>

                <hr>



            </div>
        </div>
    </div>
</div>