<?php

require_once("../config/config.php");
require_once("../admin/patchworks.php");
require_once("../admin/website/classes/page.handler.php");
require_once("../admin/system/classes/places.cls.php");
require_once("../admin/ecommerce/classes/delivery.cls.php");

$TmpDel = new DelDAO();

$action = $_GET['action'];

if (
    $action != 'login' &&
    $action != 'register' &&
    $action != 'forgotpassword' &&
    $action != 'resetpassword'
) {
    $PlaDao = new PlaDAO();
    $loggedIn = $PlaDao->loggedIn($_SESSION['loginToken']);

    if (!$loggedIn) {
        header('location: login');
        exit();
    }
}

$pageHandler = new pageHandler();

$SeoUrl = '';
$_GET['seourl'] = '';

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="favicon.ico" type="image/x-icon"/>

    <title>User Account</title>

    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">

    <base href="<?php echo $patchworks->webRoot; ?>"/>

    <?php echo $pageHandler->critialCSS(); ?>

    <link rel="stylesheet" type="text/css" href="pages/css/style.css">
    <link rel="stylesheet" type="text/css" href="pages/css/account.css">
    <script src="pages/js/jquery.js"></script>

</head>

<body>

<?php
include('webparts/page.header.php'); ?>

<div class="section peach">

    <div class="container">
        <div class="row">
            <div class="col-sm-12">

                <div class="whitebox">

                    <div id="accountWrapper">

                        <?php

                        if ($_GET['action'] == 'home') {

                            include('account/account.home.php');

                        } else if ($_GET['action'] == 'account') {

                            include('account/user.account.php');

                        } else if ($_GET['action'] == 'login') {

                            include('account/user.login.php');

                        } else if ($_GET['action'] == 'register') {

                            include('account/user.register.php');

                        } else if ($_GET['action'] == 'forgotpassword') {

                            include('account/user.forgotpassword.php');

                        } else if ($_GET['action'] == 'resetpassword') {

                            include('account/user.resetpassword.php');

                        } else if ($_GET['action'] == 'inbox') {

                            include('account/user.inbox.php');

                        } else if ($_GET['action'] == 'orders') {

                            include('account/account.orders.php');

                        } else if ($_GET['action'] == 'quotes') {

                            include('account/account.quotes.php');

                        } else if ($_GET['action'] == 'paylater') {

                            include('account/account.paylater.php');

                        } else if ($_GET['action'] == 'addresses') {

                            include('account/account.addresses.php');

                        } else if ($_GET['action'] == 'wishlist') {

                            include('account/account.wishlist.php');

                        } else if ($_GET['action'] == 'your-products') {

                            include('account/account.products.php');

                        } else if ($_GET['action'] == 'your-downloads') {

                            include('account/account.downloads.php');

                        }

                        ?>

                    </div>

                </div>

            </div>
        </div>
    </div>


</div>

<?php include('webparts/page.footer.php'); ?>

</body>

<script src="pages/js/jquery.flexslider.js"></script>
<script src="pages/js/jquery.fittext.js"></script>
<script src="pages/js/parsley.min.js"></script>
<script src="pages/js/script.js"></script>

</html>