<?php

require_once('../../config/config.php');
require_once('../../admin/patchworks.php');

$_SESSION['currency'] = $_GET['currency'];

header('location:'.$patchworks->webRoot);

?>