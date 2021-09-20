<?php
require_once("../config/config.php");
require_once("../admin/patchworks.php");
require_once("../admin/website/classes/page.handler.php");
require_once("../admin/website/classes/articles.cls.php");
require_once("../admin/gallery/classes/uploads.cls.php");
require_once("../admin/system/classes/subcategories.cls.php");

$pageHandler = new pageHandler();
$pageHandler->getPage($_GET['seourl'], $_GET, $_POST);

$action = (isset($_GET['action'])) ? $_GET['action'] : 'articleshome';

$TmpArt = new ArtDAO();
$TmpUpl = new UplDAO();

if (isset($_GET['artseo'])) {

    $action = 'article';

    $articleRec = $TmpArt->select(NULL, $_GET['artseo'], NULL, NULL, true);
    $uploads = $TmpUpl->select(NULL, 'ARTICLE', $articleRec->art_id, NULL, false);

}

// check if category posted
// build social data here

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1">
    <link rel="icon" href="favicon.ico" type="image/x-icon"/>

    <title><?php echo (isset($articleRec->artttl)) ? $articleRec->artttl : $pageHandler->PagTtl; ?></title>

    <?php echo $pageHandler->googleAnalytics(); ?>

    <base href="<?php echo $patchworks->webRoot; ?>"/>
    <meta name="keywords" content="<?php echo $pageHandler->KeyWrd; ?>"/>
    <meta name="description" content="<?php echo $pageHandler->PagDsc; ?>"/>

    <meta name="author" content="<?php echo $patchworks->customerName; ?>"/>


    <!-- Twitter Card data -->
    <meta name="twitter:card" content="summary">
<!--    <meta name="twitter:site" content="@publisher_handle">-->
    <meta name="twitter:title" content="">
    <meta name="twitter:description" content="<?php echo (isset($articleRec->keydsc)) ? $articleRec->keydsc : $pageHandler->PagDsc; ?>">
<!--    <meta name="twitter:creator" content="@author_handle">-->
    <!-- Twitter Summary card images must be at least 120x120px -->

    <?php if (isset($uploads) && count($uploads) > 0) { ?>
    <meta name="twitter:image" content="<?php echo $patchworks->webRoot.'uploads/images/' . $uploads[0]['filnam'] ?>">
    <?php } ?>

    <!-- Open Graph data -->
    <meta property="og:title" content="<?php echo (isset($articleRec->artttl)) ? $articleRec->artttl : $pageHandler->PagTtl; ?>" />
    <meta property="og:type" content="article" />
    <meta property="og:url" content="<?php echo (isset($articleRec->seourl)) ? $patchworks->webRoot.$_GET['seourl'].'/article/'.$articleRec->seourl : $patchworks->webRoot.$_GET['seourl']; ?>" />

    <?php if (isset($uploads) && count($uploads) > 0) { ?>
    <meta property="og:image" content="<?php echo $patchworks->webRoot.'uploads/images/850-600/' . $uploads[0]['filnam'] ?>" />
    <?php } ?>

    <meta property="og:description" content="<?php echo (isset($articleRec->keydsc)) ? $articleRec->keydsc : $pageHandler->PagDsc; ?>" />
    <meta property="og:site_name" content="<?php echo $patchworks->customerName; ?>" />
<!--    <meta property="fb:admins" content="Facebook numeric ID" />-->

    <?php
    echo $pageHandler->critialCSS();
    echo $pageHandler->getTopJS($_GET['seourl']);
    ?>

    <script src="pages/js/jquery.js"></script>

</head>

<body id="articlespage">

<?php
include('webparts/page.header.php');
?>


<div class="section nopadding nomargin boxes">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">

                <div id="articlesWrapper">

                    <?php

                    if ($action == 'articleshome') {

                        include('elements/articles/articles.listing.php');

                    } else if ($action == 'category') {

                        include('elements/articles/articles.category.php');

                    } else if ($action == 'article') {

                        include('elements/articles/articles.article.php');

                    }

                    ?>

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



