<?php
header("HTTP/1.0 404 Not Found");
require_once("config/config.php");
require_once("admin/patchworks.php");

$qryArray = array();
$sql = "SELECT * FROM ecommprop WHERE eco_id = 1";
$ecoProp = $patchworks->run($sql, array(), true);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta name="format-detection" content="telephone=no">
    <link rel="icon" href="favicon.ico" type="image/x-icon"/>

    <title></title>


    <base href="<?php echo $patchworks->webRoot;?>"/>
    <meta name="keywords" content="Contact"/>
    <meta name="description" content="Contact"/>
    <script src="pages/js/jquery.js"></script>
</head>
    <body id="homepage">
        404 Page
    </body>
</html>