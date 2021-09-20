<?php
require_once('../../config/config.php');
require_once('../patchworks.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="<?php echo $patchworks->webRoot; ?>pages/css/bootstrap.css">

</head>

<body>

<?php


require_once("classes/articles.cls.php");
require_once("../../Facebook/autoload.php");
require_once("../../admin/website/classes/articles.cls.php");
require_once("../../admin/gallery/classes/uploads.cls.php");

$SeoUrl = (isset($_GET['seourl'])) ? $_GET['seourl'] : NULL;


if (!is_null($SeoUrl)) {

    $TmpArt = new ArtDAO();
    $articleRec = $TmpArt->select(NULL, $SeoUrl, NULL, NULL, true);

    $UplDao = new UplDAO();
    $uploads = $UplDao->select(NULL, 'ARTICLE', $articleRec->art_id, NULL, false);

    $linkImage = NULL;

    if (is_array($uploads) && count($uploads) > 0) {
        $linkImage = $patchworks->webRoot.'uploads/images/'.$uploads[0]['filnam'];
    }


    $fb = new Facebook\Facebook([
        'app_id' => $patchworks->FB_APP_ID,
        'app_secret' => $patchworks->FB_SECRET,
        'default_graph_version' => 'v2.2',
    ]);


    $helper = $fb->getRedirectLoginHelper();
    $permissions = [
        'email',
        'user_location',
        'user_birthday',
        'publish_actions',
        'publish_pages',
        'manage_pages',
        'public_profile'
    ];


    $_SESSION['facebooklink'] = [
        'link' => $patchworks->webRoot.$patchworks->articlesURL.$patchworks->articleURL.$articleRec->seourl,
        'message' => $articleRec->artdsc,
        'caption' => $articleRec->artttl,
        'picture' => $linkImage,
        'type' => 'link'
    ];


    $loginUrl = $helper->getLoginUrl($patchworks->webRoot . 'admin/website/posttofb.php', $permissions);

    echo '<a href="'.$loginUrl.'" class="btn btn-primary" id="facebookLink">Post to Facebook</a>';

}

?>

</body>

</html>
