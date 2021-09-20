<?php

require_once("../../config/config.php");
require_once("../patchworks.php");

include('classes/gallery.cls.php');
include('classes/uploads.cls.php');

include('../js/plugins/plupload/SimpleImage.php');
include('../js/plugins/plupload/imagemanipulation.php');

error_reporting(E_ALL);
date_default_timezone_set('Europe/London');
ini_set('max_execution_time', 0);


$UplStr = (isset($_REQUEST['uplstr']) && !empty($_REQUEST['uplstr'])) ? $_REQUEST['uplstr'] : NULL;
$Gal_ID = (isset($_REQUEST['gal_id']) && is_numeric($_REQUEST['gal_id'])) ? $_REQUEST['gal_id'] : NULL;
if ($Gal_ID == NULL) die('Empty Request');

$uploads = NULL;

$TmpGal = new GalDAO();
$galleryRec = $TmpGal->select($Gal_ID, NULL, NULL, NULL, true);

if ($UplStr != NULL) {
    $qryArray = array();
    // Doesn't work as a named parameter
    $sql = "SELECT * FROM uploads WHERE tblnam = :tblnam AND tbl_id = :tbl_id AND upl_id in (".$UplStr.")";
    $qryArray['tblnam'] = 'WEBGALLERY';
    $qryArray['tbl_id'] = $galleryRec->gal_id;
    $uploads = $patchworks->run($sql, $qryArray, false);
}

else {
    $TmpUpl = new UplDAO();
    $uploads = $TmpUpl->select(NULL, 'WEBGALLERY', $galleryRec->gal_id, NULL, false);
}

$searchPath = $patchworks->docRoot.'uploads/images/';
$searchFile = '';

$i = 0;

for ($i=0; $i<count($uploads); $i++) {
    resizeImage( $searchPath, $uploads[$i]['filnam'], $patchworks->galleryImageSizes.','.$galleryRec->imgsiz );
}

function getResizeVar( $iReqX, $iReqY, $iActX, $iActY ) {
    if ( $iActX >= $iActY ) {
        // Landscape
        $percentage = ($iReqX / $iActX) * 100;
        // Will the image fill the space after resize?
        if ( (($iActY / 100) * $percentage) > $iReqY ) {
            // Actual height greater than required height - OK
            return 'p';
        } else {
            return 'l';
        }
    } else {
        // Portrait
        $percentage = ($iReqX / $iActX) * 100;
        // Will the image fill the space after resize?
        if ( (($iActX / 100) * $percentage) > $iReqX ) {
            // Actual width greater than required width - OK
            return 'l';
        } else {
            return 'p';
        }
    }
}

function searchFiles( $searchPath = '.', $searchFile = '', $imageSizes = '' ){

    $searchPath = '../../uploads/images/';

    $countFiles = 0;

    $ignoreArray = array( '.tbm', 'cgi-bin', '.', '..', '.svn' );
    $searchDir = @opendir( $searchPath );

    //$entries = scandir($searchPath);

    while (false !== ($currentFile = readdir($searchDir))) {

        if (!in_array($currentFile, $ignoreArray)) {

            if (is_dir($searchPath . '/' . $currentFile)) {
                //searchFiles($searchPath . '/' . $currentFile, $searchFile);
            } else {
                //if ($currentFile == $searchFile) unlink( $searchPath.'/'.$currentFile );
                $countFiles++;
                echo $searchPath . '/' . $currentFile . '<br>';
                resizeImage($searchPath.'/',$currentFile, $imageSizes);
            }
        }
    }

    echo '<p>'.$countFiles.'</p>';
    closedir($searchDir);
}

function resizeImage( $targetDir = NULL, $fileName = NULL, $imageSize = '169-130') {

    // Calculate size of uploaded image
    list($width, $height, $type, $attr) = getimagesize($targetDir.'/'.$fileName);

    $resize = explode(",", $imageSize);

    for ($r = 0; $r < count($resize); $r++) {

        $resizeSizes = explode( "-", $resize[$r] );

        if (count($resizeSizes) > 1) {

            $trimWidth = $resizeSizes[0];
            $trimHeight = $resizeSizes[1];

            $chkDirectory = $targetDir.$trimWidth.'-'.$trimHeight.'/';
            if (!file_exists($chkDirectory)) @mkdir($chkDirectory);

            if ( getResizeVar( $trimWidth, $trimHeight, ceil($width), ceil($height) ) == 'p' ) {

                // Resize By Portrait

                $image = new SimpleImage();
                $image->load($targetDir . DIRECTORY_SEPARATOR . $fileName);
                $image->resizeToWidth($trimWidth);
                $smallImage = $targetDir.$trimWidth.'-'.$trimHeight.'/'.$fileName;
                $image->save($smallImage);

                list($width, $height, $type, $attr) = getimagesize($smallImage);

                $PadTop = 0;
                if ( $height > $trimHeight ) {
                    $PadTop = ($height-$trimHeight)/2;
                }

                $objImage = new ImageManipulation($smallImage);
                $objImage->setCrop(0, $PadTop, $trimWidth, $trimHeight);
                $objImage->save($smallImage);


            } else {

                // Resize to landscape

                $image = new SimpleImage();
                $image->load($targetDir . DIRECTORY_SEPARATOR . $fileName);
                $image->resizeToHeight($trimHeight);
                $smallImage = $targetDir.$trimWidth.'-'.$trimHeight.'/'.$fileName;
                $image->save($smallImage);

                list($width, $height, $type, $attr) = getimagesize($smallImage);

                $PadLft = 0;
                if ( $width >= $trimWidth ) {
                    $PadLft = ($width-$trimWidth)/2;
                }

                if ($PadLft < 0) { $PadLft = 0;}

                $objImage = new ImageManipulation($smallImage);
                $objImage->setCrop($PadLft, 0, $trimWidth, $trimHeight);
                $objImage->save($smallImage);

            }

        } else {

            $chkDirectory =$targetDir.$resizeSizes[0].'/';
            if (!file_exists($chkDirectory)) @mkdir($chkDirectory);

            $image = new SimpleImage();
            $image->load($targetDir . DIRECTORY_SEPARATOR . $fileName);
            $image->resizeToWidth($resizeSizes[0]);
            $smallImage = $targetDir.$resizeSizes[0].'/'.$fileName;
            $image->save($smallImage);
        }
    }
}