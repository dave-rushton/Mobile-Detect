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
include('webparts/page.header.php');
?>



    <div class="pageElement" id="full-outer-1">
        <?php echo $pageHandler->displayElements('full-outer-1'); ?>
    </div>

    <div class="container">
        <div class="pageElement" id="full-inner-0">
            <?php echo $pageHandler->displayElements('full-inner-0'); ?>
        </div>
    </div>
        <?php
            $count = 7;
            $inner = 1;
            $outer = 2;
            for($i=0; $i < $count ; $i++)
            {
                ?>
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="pageElement" id="full-inner-<?php echo $inner;?>">
                                <?php
                                    echo $pageHandler->displayElements('full-inner-'.$inner);
                                    $inner ++;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pageElement" id="full-outer-<?php echo $outer;?>">
                    <?php
                        echo $pageHandler->displayElements('full-outer-'.$outer);
                        $outer ++;
                    ?>
                </div>
                <div class="nogap">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="pageElement" id="full-outer-<?php echo $outer;?>">
                                <?php
                                    echo $pageHandler->displayElements('full-outer-'.$outer);
                                    $outer ++;
                                ?>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="pageElement" id="full-outer-<?php echo $outer;?>">
                                <?php
                                    echo $pageHandler->displayElements('full-outer-'.$outer);
                                    $outer ++;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pageElement" id="full-outer-<?php echo $outer;?>">
                    <?php
                    echo $pageHandler->displayElements('full-outer-'.$outer);
                    $outer ++;
                    ?>
                </div>

                <?php
            }
        ?>



<?php
include('webparts/page.footer.php');
echo $pageHandler->getBotJS($_GET['seourl']);
?>
</div>
</body>
</html>
