<?php

require_once("../../config/config.php");
require_once("../../admin/patchworks.php");
require_once("../../admin/website/classes/page.handler.php");

$bcHandler = new pageHandler;
if (isset($_GET['seourl'])) echo $bcHandler->getBreadcrumb($_GET['seourl']);

?>