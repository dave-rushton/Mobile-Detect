<?php

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("classes/articles.cls.php");
require_once("../../Facebook/autoload.php");

$fb = new Facebook\Facebook([
    'app_id' => $patchworks->FB_APP_ID,
    'app_secret' => $patchworks->FB_SECRET,
    'default_graph_version' => 'v2.2',
]);


$helper = $fb->getRedirectLoginHelper();
try {
    $accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}

if (isset($accessToken)) {

    $_SESSION['facebook_access_token'] = (string) $accessToken;

    $linkData = $_SESSION['facebooklink'];

    try {
        $response = $fb->post('/me/feed', $linkData, $_SESSION['facebook_access_token']);
    } catch(Facebook\Exceptions\FacebookResponseException $e) {
        echo 'Graph returned an error: ' . $e->getMessage();
        die();
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        die();
    }

    $graphNode = $response->getGraphNode();

    echo 'Article posted to Facebook';

}

//header('location: articles.php?fbposted=true');

?>

