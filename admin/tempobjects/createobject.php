<?php


require_once('../../config/config.php');
require_once('../../admin/patchworks.php');
require_once("../../admin/system/classes/tempobject.cls.php");

$tempObject = array();

$fieldObject = array();
$fieldObject['field1'] = 'field1';
$fieldObject['field2'] = 'field2';

array_push($tempObject, $fieldObject);

$fieldObject = array();
$fieldObject['field3'] = 'field3';
$fieldObject['field4'] = 'field4';

array_push($tempObject, $fieldObject);


$TmpObj = new stdClass();
$TmpObj->tmp_id = 0;
$TmpObj->tblnam = 'DEMO';
$TmpObj->tbl_id = 0;
$TmpObj->tmpobj = json_encode($tempObject);

$TmpDao = new TmpDAO();
$Tmp_ID = $TmpDao->update($TmpObj);