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
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1">
    <link rel="icon" href="favicon.ico" type="image/x-icon"/>

    <title><?php echo $pageHandler->PagTtl; ?></title>

    <?php echo $pageHandler->googleAnalytics(); ?>

    <base href="<?php echo $patchworks->webRoot; ?>"/>
    <meta name="keywords" content="<?php echo $pageHandler->KeyWrd; ?>"/>
    <meta name="description" content="<?php echo $pageHandler->PagDsc; ?>"/>


    <?php
    if (isset($_GET['artseo'])) {

        require_once("../admin/website/classes/articles.cls.php");
        require_once("../admin/gallery/classes/uploads.cls.php");

        $TmpArt = new ArtDAO();
        $articleRec = $TmpArt->selectBySeoUrl($_GET['artseo']);

        $UplDao = new UplDAO();
        $uploads = $UplDao->select(NULL, 'ARTICLE', $articleRec->art_id, NULL, false);

        if (is_array($uploads) && count($uploads) > 0) {
            $linkImage = $patchworks->webRoot.'uploads/images/750-400/'.$uploads[0]['filnam'];
        }
        
        // Find Article

        $linkUrl = $patchworks->webRoot.$patchworks->articlesURL.$patchworks->articleURL.$articleRec->seourl;

        ?>

        <meta property="fb:app_id" content="1515606485404909" />

        <meta property="og:url" content="<?php echo $linkUrl; ?>" />
        <meta property="og:type" content="article" />
        <meta property="og:title" content="<?php echo $articleRec->artttl; ?>" />
        <meta property="og:description" content="<?php echo $articleRec->artdsc; ?>" />

        <?php
        if (count($uploads) > 0) {
            ?>

            <meta property="og:image"
                  content="<?php echo $patchworks->webRoot.'uploads/images/750-400/' . $uploads[0]['filnam']; ?>" />

            <?php
        }
        ?>

        <?php
    }
    ?>

    <link href='https://fonts.googleapis.com/css?family=Lato:400,300,700' rel='stylesheet' type='text/css'>

    <link rel="stylesheet" type="text/css" href="pages/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="pages/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="pages/css/flexslider.css">
    <link rel="stylesheet" type="text/css" href="pages/css/styles.css">
    <script src="pages/js/jquery.js"></script>

</head>

<body>

<?php
include('webparts/page.header.php');
?>

<div class="pageElement" id="fullwidthcontent">
    <?php $pageHandler->displayElements('fullwidthcontent'); ?>
</div>

<?php
include('webparts/page.footer.php');
?>

</body>

<script src="pages/js/jquery.flexslider.js"></script>
<script src="pages/js/bootstrap.js"></script>
<script src="pages/js/parsley.min.js"></script>
<script src="pages/js/wookmark.min.js"></script>
<script src="pages/js/script.js"></script>

</html>



