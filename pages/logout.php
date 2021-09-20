<?php

require_once( "../config/config.php" );
require_once( "../admin/patchworks.php" );

unset($_SESSION['loginToken']);

header('location: '. $patchworks->webRoot);

?>