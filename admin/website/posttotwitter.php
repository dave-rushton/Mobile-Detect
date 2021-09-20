<?php

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("classes/articles.cls.php");
require_once("../../twitterapi/codebird.php");

require_once("../../admin/website/classes/articles.cls.php");
require_once("../../admin/gallery/classes/uploads.cls.php");

$SeoUrl = (isset($_GET['seourl'])) ? $_GET['seourl'] : NULL;

if (!is_null($SeoUrl)) {

    $qryArray = array();
    $cmsProp = $patchworks->run("SELECT * FROM cmsprop", $qryArray);

    $TmpArt = new ArtDAO();
    $articleRec = $TmpArt->select(NULL, $SeoUrl, NULL, NULL, true);

    $UplDao = new UplDAO();
    $uploads = $UplDao->select(NULL, 'ARTICLE', $articleRec->art_id, NULL, false);
    $linkImage = NULL;
    if (is_array($uploads) && count($uploads) > 0) {
        $linkImage = $patchworks->webRoot.'uploads/images/'.$uploads[0]['filnam'];
    }

    $linkUrl = $articleRec->artttl.' : '.$patchworks->webRoot.$patchworks->articlesURL.$patchworks->articleURL.$articleRec->seourl;

    \Codebird\Codebird::setConsumerKey($cmsProp[0]['conkey'], $cmsProp[0]['consec']);
    $cb = \Codebird\Codebird::getInstance();
    $cb->setToken($cmsProp[0]['acctok'], $cmsProp[0]['accsec']);


    if (!is_null($linkImage)) {

        $params = array(
            'status' => $linkUrl,
            'media[]' => $linkImage
        );

        $reply = $cb->statuses_updateWithMedia($params);

    } else {

        $params = array(
            'status' => $linkUrl
        );

        $reply = $cb->statuses_update($params);

    }

    echo 'Article posted to Twitter';

}

?>