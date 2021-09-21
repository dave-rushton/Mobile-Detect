<?php


require_once('../../config/config.php');
require_once('../patchworks.php');
require_once('classes/kamarin.cls.php');


$KamDao = new kamarinClass();

$KamDao->createOrderFromID(4);

?>