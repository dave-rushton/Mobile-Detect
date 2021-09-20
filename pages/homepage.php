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

    <meta property="og:title" content="<?php echo $pageHandler->PagTtl; ?>"/>
    <meta property="og:description" content="<?php echo $pageHandler->PagDsc; ?>"/>
    <?php
    $image = $pageHandler->PagImg;
    if(empty($image)){
        echo '<meta property="og:image" content=""/>';
    }
    else{
        echo '<meta property="og:image" content=""'.$pageHandler->PagImg.'"/>';
    }
    ?>


    <?php
    echo $pageHandler->critialCSS();
    echo $pageHandler->getTopJS($_GET['seourl']);
    ?>

    <script src="pages/js/jquery.js"></script>

</head>

<body id="homepage">

<?php
include('webparts/page.header.php');
?>

<div class="theme1">

    <div class="pageElement" id="full-outer-1">
        <?php echo $pageHandler->displayElements('full-outer-1'); ?>
    </div>

    <div class="container">
        <div class="pageElement" id="full-inner-0">
            <?php echo $pageHandler->displayElements('full-inner-0'); ?>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="pageElement" id="full-inner-1a">
                    <?php echo $pageHandler->displayElements('full-inner-1a'); ?>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="pageElement" id="full-inner-2a">
                    <?php echo $pageHandler->displayElements('full-inner-2a'); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="pageElement" id="full-inner-3a">
                    <?php echo $pageHandler->displayElements('full-inner-3a'); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="pageElement" id="full-inner-1">
                    <?php echo $pageHandler->displayElements('full-inner-1'); ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="pageElement" id="full-inner-2">
                    <?php echo $pageHandler->displayElements('full-inner-2'); ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="pageElement" id="full-inner-3">
                    <?php echo $pageHandler->displayElements('full-inner-3'); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="pageElement" id="full-inner-4a">
                    <?php echo $pageHandler->displayElements('full-inner-4a'); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="pageElement" id="full-inner-5a">
                    <?php echo $pageHandler->displayElements('full-inner-5a'); ?>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="pageElement" id="full-inner-6a">
                    <?php echo $pageHandler->displayElements('full-inner-6a'); ?>
                </div>
            </div>
        </div>
        <div class="pageElement" id="full-inner-4">
            <?php echo $pageHandler->displayElements('full-inner-4'); ?>
        </div>
    </div>

    <div class="pageElement" id="full-outer-2">
        <?php echo $pageHandler->displayElements('full-outer-2'); ?>
    </div>

    <div class="container">
        <div class="pageElement" id="full-inner-5">
            <?php echo $pageHandler->displayElements('full-inner-5'); ?>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="pageElement" id="full-inner-1b">
                    <?php echo $pageHandler->displayElements('full-inner-1b'); ?>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="pageElement" id="full-inner-2b">
                    <?php echo $pageHandler->displayElements('full-inner-2b'); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="pageElement" id="full-inner-3b">
                    <?php echo $pageHandler->displayElements('full-inner-3b'); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="pageElement" id="full-inner-6">
                    <?php echo $pageHandler->displayElements('full-inner-6'); ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="pageElement" id="full-inner-7">
                    <?php echo $pageHandler->displayElements('full-inner-7'); ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="pageElement" id="full-inner-8">
                    <?php echo $pageHandler->displayElements('full-inner-8'); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="pageElement" id="full-inner-4b">
                    <?php echo $pageHandler->displayElements('full-inner-4b'); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="pageElement" id="full-inner-5b">
                    <?php echo $pageHandler->displayElements('full-inner-5b'); ?>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="pageElement" id="full-inner-6b">
                    <?php echo $pageHandler->displayElements('full-inner-6b'); ?>
                </div>
            </div>
        </div>
        <div class="pageElement" id="full-inner-4">
            <?php echo $pageHandler->displayElements('full-inner-9'); ?>
        </div>
    </div>

    <div class="pageElement" id="full-outer-10">
        <?php echo $pageHandler->displayElements('full-outer-10'); ?>
    </div>
    
    
</div>
<?php
include('webparts/page.footer.php');
echo $pageHandler->getBotJS($_GET['seourl']);
?>
</div>
</body>
</html>
