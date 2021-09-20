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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="format-detection" content="telephone=no">
    <link rel="icon" href="favicon.ico" type="image/x-icon"/>

    <title><?php echo $pageHandler->PagTtl; ?></title>

    <?php echo $pageHandler->googleAnalytics(); ?>

    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">

    <base href="<?php echo $patchworks->webRoot; ?>"/>
    <meta name="keywords" content="<?php echo $pageHandler->KeyWrd; ?>"/>
    <meta name="description" content="<?php echo $pageHandler->PagDsc; ?>"/>

    <?php
    echo $pageHandler->critialCSS();
    echo $pageHandler->getTopJS($_GET['seourl']);
    ?>

    <script src="pages/js/jquery.js"></script>

</head>
<body>


<div id="watermarkWrapper">
    <div class="watermark"></div>
</div>


<?php
include('webparts/page.header.php');
?>

<div class="pageElement" id="fullwidthcontent">
    <?php echo $pageHandler->displayElements('fullwidthcontent'); ?>
</div>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <div class="pageElement" id="bottomcontent4">
                    <?php echo $pageHandler->displayElements('bottomcontent4'); ?>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="pageElement" id="bottomcontent5">
                    <?php echo $pageHandler->displayElements('bottomcontent5'); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('webparts/page.footer.php');
echo $pageHandler->getBotJS($_GET['seourl']);
?>

</body>
</html>