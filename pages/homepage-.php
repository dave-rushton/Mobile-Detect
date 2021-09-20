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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta name="format-detection" content="telephone=no">
    <link rel="icon" href="favicon.ico" type="image/x-icon"/>

    <title><?php echo $pageHandler->PagTtl; ?></title>

    <?php echo $pageHandler->googleAnalytics(); ?>

    <base href="<?php echo $patchworks->webRoot; ?>"/>
    <meta name="keywords" content="<?php echo $pageHandler->KeyWrd; ?>"/>
    <meta name="description" content="<?php echo $pageHandler->PagDsc; ?>"/>

    <?php
    echo $pageHandler->critialCSS();
    echo $pageHandler->getTopJS($_GET['seourl']);
    ?>

    <script src="pages/js/jquery.js"></script>

</head>

<body id="homepage">

<?php
include('webparts/home.header.php');
?>

<div class="overlay">


    <div class="pageElement" id="fullwidthcontent">
        <?php echo $pageHandler->displayElements('fullwidthcontent'); ?>
    </div>
    <div class="section nopadding nomargin">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">

                    <div class="pageElement" id="contentleft">
                        <?php echo $pageHandler->displayElements('contentleft'); ?>
                    </div>

                </div>
                <div class="col-lg-12">

                    <div class="pageElement" id="contentright">
                        <?php echo $pageHandler->displayElements('contentright'); ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="section nopadding nomargin">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">

                    <div class="pageElement" id="contentleftaa">
                        <?php echo $pageHandler->displayElements('contentleftaa'); ?>
                    </div>

                </div>
                <div class="col-lg-8">

                    <div class="pageElement" id="contentrightbb">
                        <?php echo $pageHandler->displayElements('contentrightbb'); ?>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <?php
    if(1==2){
        ?>
        <div class="section  nomargin">
            <div class="contentboxes">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4 non-rel">
                            <div class="pageElement" id="center1">
                                <?php echo $pageHandler->displayElements('center1'); ?>
                            </div>
                        </div>
                        <div class="col-md-4 non-rel">
                            <div class="pageElement" id="center2">
                                <?php echo $pageHandler->displayElements('center2'); ?>
                            </div>
                        </div>
                        <div class="col-md-4 non-rel">
                            <div class="pageElement" id="center3">
                                <?php echo $pageHandler->displayElements('center3'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
    ?>


    <div class="pageElement" id="bottomcontent">
        <?php echo $pageHandler->displayElements('bottomcontent'); ?>
    </div>



<?php
include('webparts/page.footer.php');
echo $pageHandler->getBotJS($_GET['seourl']);
?>
</div>
</body>
</html>