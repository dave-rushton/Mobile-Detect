<?php

//print_r($_REQUEST);

include('SimpleImage.php');
include('imagemanipulation.php');

$target_dir = "../../uploads/images/basket/";

$target_file = $target_dir . basename($_FILES["logofile"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

$newFileName = $_POST['newname'].'.'.$imageFileType;

$target_file = $target_dir . $newFileName;

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["logofile"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}

// Check if file already exists
//if (file_exists($target_file)) {
//    echo "Sorry, file already exists.";
//    $uploadOk = 0;
//}

// Check file size
if ($_FILES["logofile"]["size"] > 5242880 ) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
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

        $trimWidth = 600;
        $trimHeight = 600;

        $image = new SimpleImage();
        $image->load($target_file);

        if ($height > $trimHeight) {
            $image->resizeToHeight($trimHeight);
        }

        //$image->resizeToWidth($trimWidth);
        $image->save($target_file);

        list($width, $height, $type, $attr) = getimagesize($target_file);

        $PadLft = 0;
        $PadTop = 0;
        if ( $width > $trimWidth ) {
            $PadLft = ($width-$trimWidth)/2;
        }

        if ($PadLft < 0) { $PadLft = 0;}

        $objImage = new ImageManipulation($target_file);
        $objImage->setCrop($PadLft, 0, $trimWidth, $trimHeight);
        $objImage->save($target_file);

        echo $newFileName;

    } else {

        echo "FAIL";

    }
}
?>