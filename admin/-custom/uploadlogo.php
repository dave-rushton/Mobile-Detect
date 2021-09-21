<?php

require_once("../../config/config.php");
require_once("../../admin/patchworks.php");
require_once("../../admin/system/classes/places.cls.php" );


$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');


if ($loggedIn == 0) {

    $throwJSON['title'] = 'Authorisation';
    $throwJSON['description'] = 'You are not authorised for this action '.$PwdTok;
    $throwJSON['type'] = 'error';

    die(json_encode($throwJSON));

}


include('SimpleImage.php');
include('imagemanipulation.php');

ini_set('display_errors',1);

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

//print_r($_REQUEST);

$target_dir = "../../uploads/images/";
$target_file = $target_dir . basename($_FILES["logofile"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);


$throwJSON = array();
$throwJSON['id'] = '0';
$throwJSON['title'] = 'noaction';
$throwJSON['description'] = 'no action taken';
$throwJSON['type'] = 'warning';




function seoUrl($string) {
    //Unwanted:  {UPPERCASE} ; / ? : @ & = + $ , . ! ~ * ' ( )
    $string = strtolower($string);
    //Strip any unwanted characters
    $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
    //Clean multiple dashes or whitespaces
    $string = preg_replace("/[\s-]+/", " ", $string);
    //Convert whitespaces and underscore to dash
    $string = preg_replace("/[\s_]/", "-", $string);
    return $string;
}

// random id

$newFileName = seoUrl( generateRandomString(20) ).'.'.$imageFileType;

$target_file = $target_dir . $newFileName;

// Check if image file is a actual image or fake image

if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["logofile"]["tmp_name"]);
    if($check !== false) {
//        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
//        echo "File is not an image.";
        $uploadOk = 0;
    }
}

// Check if file already exists
//if (file_exists($target_file)) {
//    echo "Sorry, file already exists.";
//    $uploadOk = 0;
//}

// Check file size
if ($_FILES["logofile"]["size"] > 5242880) {

    $throwJSON['title'] = 'File Too Large';
    $throwJSON['description'] = 'The file you are uploading is too large, please make sure the maximum size is 4mb';
    $throwJSON['type'] = 'error';

    die(json_encode($throwJSON));

    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {

    $throwJSON['title'] = 'Invalid File Type';
    $throwJSON['description'] = 'Please ensure your item image is either JPG,JPEG,GIF or PNG';
    $throwJSON['type'] = 'error';

    die(json_encode($throwJSON));

    echo "FAIL: Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "FAIL: Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["logofile"]["tmp_name"], $target_file)) {

        list($width, $height, $type, $attr) = getimagesize($target_file);

//        if ($width > 1400) {
//
//            $image = new SimpleImage();
//            $image->load($target_file);
//            $image->resizeToWidth(1400);
//            $image->save($target_file);
//
//        }


        $trimWidth = 1400;
        $trimHeight = 400;

        $image = new SimpleImage();
        $image->load($target_file);
        $image->resizeToWidth($trimWidth);
        $image->save($target_file);

        list($width, $height, $type, $attr) = getimagesize($target_file);

        $PadLft = 0;
        $PadTop = 0;
        if ( $height > $trimHeight ) {
            $PadTop = ($height-$trimHeight)/2;
        }

        if ($PadLft < 0) { $PadLft = 0;}

        $objImage = new ImageManipulation($target_file);
        $objImage->setCrop(0, $PadTop, $trimWidth, $trimHeight);
        $objImage->save($target_file);

        $throwJSON['title'] = 'File Uploaded';
        $throwJSON['description'] = $newFileName;
        $throwJSON['type'] = 'success';

        die(json_encode($throwJSON));

        echo $newFileName;

    } else {

        $throwJSON['title'] = 'File Upload Fail';
        $throwJSON['description'] = $_FILES['logofile']['error'];
        $throwJSON['type'] = 'error';

        die(json_encode($throwJSON));

        echo $_FILES['logofile']['error'];

        echo "FAIL: ".$_FILES["logofile"]["tmp_name"].' '.$target_file;
    }
}
?>