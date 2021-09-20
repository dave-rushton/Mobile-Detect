<?php
require_once('../../config/config.php');
require_once('../patchworks.php');
require_once('classes/crop.cls.php');

$jsonData = json_decode($_POST['avatar_data'],true);
$avatarSize = $_POST['avatar_size'];

if ($_POST['avatar_size'] == 'FREE') {
    $avatarSize = round($jsonData['width'],0,PHP_ROUND_HALF_UP).'-'.round($jsonData['height'],0,PHP_ROUND_HALF_UP);
    $avatarSize = '';
}

$acWidth = round($jsonData['width'],0,PHP_ROUND_HALF_UP);
$acHeight = round($jsonData['height'],0,PHP_ROUND_HALF_UP);


$crop = new CropAvatar($_POST['avatar_src'], $_POST['avatar_data'], NULL, $patchworks->docRoot, $avatarSize, $acWidth, $acHeight);

$response = array(
    'state' => 200,
    'message' => $crop->getMsg(),
    'result' => $crop->getResult()
);

echo json_encode($response);