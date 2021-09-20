<?php

require_once("../../config/config.php");
require_once("../patchworks.php");

include('../js/plugins/plupload/SimpleImage.php');
include('../js/plugins/plupload/imagemanipulation.php');

error_reporting(E_ALL);
date_default_timezone_set('Europe/London');
ini_set('max_execution_time', 0);

$searchPath = $patchworks->docRoot.'uploads/images/';
$searchFile = '';

//searchFiles($searchPath, '', '169-130');

$targetDir = $searchPath;
$fileName = $_GET['filnam'];
$imageWidth = $_GET['imgsiz'];
$directory = $_GET['dir'];

echo ' IMGWIDTH: ' . $imageWidth;

echo ' LOAD:' . $targetDir . DIRECTORY_SEPARATOR . $fileName.' ';

$image = new SimpleImage();
$image->load($targetDir . DIRECTORY_SEPARATOR . $fileName);
$image->resizeToWidth($imageWidth);
$smallImage = $targetDir.'/'.$fileName;
$image->save($smallImage);


echo ' SAVE: '.$targetDir.$directory.'/'.$fileName;