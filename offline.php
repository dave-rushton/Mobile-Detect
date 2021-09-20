<?php

require_once("config/config.php");
require_once("admin/patchworks.php");

$qryArray = array();
$sql = "SELECT * FROM ecommprop WHERE eco_id = 1";
$ecoProp = $patchworks->run($sql, array(), true);

?>

<!DOCTYPE html>
<html>
<head>

    <link rel="icon" href="favicon.ico" type="image/x-icon"/>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <base href="<?php echo $patchworks->webRoot; ?>"/>

    <title><?php echo $patchworks->customerName; ?>: WEBSITE UNDER MAINTENANCE</title>

    <style>

        html {
            height: 100%
        }

        body {
            font-family: arial, helvetica, sans-serif;
            color: #fff;
            font-size: 16px;
            min-height: 100%;
            margin: 0;
        }

        body.landing:before {
            content: " ";
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            min-height: 100%;
            background-image: url(pages/img/404.jpg);
            background-size: cover;
        }

        body.landing::after {
            content: " ";
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            min-height: 100%;
            background: rgba(0, 0, 0, .8);
            z-index: 1000
        }

        #logo {
            content: " ";
            display: block;
            /*
            position: absolute;
            top: 50%;
            left: 50%;
            */
            background: url(pages/img/logo.png) no-repeat center;
            background-size: contain;
            width: 68px;
            height: 77px;
            /*
            margin: -47px 0 0 -212px;
            z-index: 20000;
            color: #000;
            text-align: center;
            */

            max-width: 423px;

        }

        .contactinformation {

            position: absolute;
            z-index: 10000;
            width: 60%;
            top: 50%;
            left: 50%;
            -ms-transform: translate(-50%, -50%);
            -webkit-transform: translate(-50%, -50%);
            -moz-transform: translate(-50%, -50%);
            -o-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);

        }

        a {
            color: #ffffff;
        }

        a: hover {
            color: #ffffff;
        }

    </style>
</head>

<body class="landing">


<div class="contactinformation">

    <?php

    $address = '';

    $address .= $ecoProp->adr1.'<br>';
    $address .= $ecoProp->adr2.'<br>';
    $address .= $ecoProp->adr3.'<br>';
    $address .= $ecoProp->adr4.'<br>';
    $address .= $ecoProp->pstcod;
    ?>

    <div id="logo"></div>

    <h1>WEBSITE UNDER MAINTENANCE...</h1>
    <h2><?php echo $ecoProp->comnam; ?></h2>
    <p>
        <?php echo $address; ?>
    </p>

    <p>
        <a href="mailto:<?php echo $ecoProp->emaadr; ?>"><?php echo $ecoProp->emaadr; ?></a><br>
        <?php echo $ecoProp->comtel; ?>
    </p>


</div>


</body>

</html>
