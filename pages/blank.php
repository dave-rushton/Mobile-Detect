<?php
require_once("../config/config.php");
require_once("../admin/patchworks.php");
require_once("../admin/website/classes/page.handler.php");

$pageHandler = new pageHandler();
$pageHandler->getPage($_GET['seourl'], $_GET, $_POST);

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="favicon.ico" type="image/x-icon"/>

    <title><?php echo $pageHandler->PagTtl; ?></title>

    <?php echo $pageHandler->googleAnalytics(); ?>

    <meta name="author" content="">
    <base href="<?php echo $patchworks->webRoot; ?>"/>
    <meta name="keywords" content="<?php echo $pageHandler->KeyWrd; ?>"/>
    <meta name="description" content="<?php echo $pageHandler->PagDsc; ?>"/>

    <link href='http://fonts.googleapis.com/css?family=Lato:400,300,100,700,900' rel='stylesheet' type='text/css'>

    <link rel="stylesheet" type="text/css" href="pages/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="pages/css/styles.css">

</head>

<body>


<div data-diff="100" data-img-height="1064" data-img-width="1600" style="background-image: url(&quot;http://www.minimit.com/images/picjumbo.com_IMG_6648.jpg&quot;);" class="background parallax fullscreen">
    <div class="content-a">
        <div class="content-b">
            EXAMPLE TEXT        </div>
    </div>
</div>


<!--<div class="section">-->
<!--    <div class="container">-->
<!--        <div class="row">-->
<!--            <div class="col-sm-12">-->

                <div class="pageElement" id="content1">
                    <?php $pageHandler->displayElements('content1'); ?>
                </div>

<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->


</body>

<script src="pages/js/jquery.js"></script>
<script src="pages/js/bootstrap.js"></script>


<script src="pages/js/script.js"></script>

</html>