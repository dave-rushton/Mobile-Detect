<?php

require_once("../config/config.php");
require_once("../admin/patchworks.php");
require_once("../admin/website/classes/page.handler.php");
require_once("../admin/system/classes/places.cls.php");

$PwdTok = (isset($_SESSION['loginToken'])) ? $_SESSION['loginToken'] : '';
$PlaDao = new PlaDAO();
$loggedIn = $PlaDao->loggedIn($PwdTok);

//if (!$loggedIn) header('location: login');
$pageHandler = new pageHandler();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1">
    <meta name="format-detection" content="telephone=no">
    <link rel="icon" href="favicon.ico" type="image/x-icon"/>

    <title><?php echo $pageHandler->PagTtl; ?></title>

    <?php echo $pageHandler->googleAnalytics(); ?>

    <base href="<?php echo $patchworks->webRoot; ?>"/>
    <meta name="keywords" content="<?php echo $pageHandler->KeyWrd; ?>"/>
    <meta name="description" content="<?php echo $pageHandler->PagDsc; ?>"/>

    <link href='http://fonts.googleapis.com/css?family=Lato:400,300,100,700,900' rel='stylesheet' type='text/css'>

    <link rel="stylesheet" type="text/css" href="pages/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="pages/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="pages/css/flexslider.css">
    <link rel="stylesheet" type="text/css" href="pages/css/styles.css">
    <script src="pages/js/jquery.js"></script>

</head>

<body>

<?php
include('webparts/page.header.php'); ?>
<?php

if ($_GET['action'] == 'account') {

    include('account/user.account.php');

} else if ($_GET['action'] == 'login') {

    include('account/user.login.php');

} else if ($_GET['action'] == 'register') {

    include('account/user.register.php');

} else if ($_GET['action'] == 'forgotpassword') {

    include('account/user.forgotpassword.php');

} else if ($_GET['action'] == 'inbox') {

    include('account/user.inbox.php');

}

?>

<?php include('webparts/page.footer.php'); ?>

</body>

<script src="pages/js/jquery.flexslider.js"></script>
<script src="pages/js/parsley.min.js"></script>
<script src="pages/js/script.js"></script>

</html>