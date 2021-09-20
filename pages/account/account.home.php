<?php

require_once("../config/config.php");

require_once("../admin/patchworks.php");

require_once("../admin/system/classes/places.cls.php");


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

            <div class="col-sm-12">


                <h2 class="heading">Account Home</h2>

                <div class="box">

                    <?php include('account.menu.php'); ?>

                </div>


            </div>

        </div>

    </div>

</div>

