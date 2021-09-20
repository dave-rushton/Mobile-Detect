<?php

require_once( "config/config.php" );
require_once( "admin/patchworks.php" );

unset($_SESSION['loginToken']);
unset($_SESSION['cart']);

header('location: '. $patchworks->webRoot);

?>