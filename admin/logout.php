<?php

require_once("../config/config.php");
require_once("patchworks.php");

unset($_SESSION['s_usrnam']);
unset($_SESSION['s_usracc']);
unset($_SESSION['s_log_id']);
unset($_SESSION['s_usrema']);

header( 'Location: login.php' );

?>