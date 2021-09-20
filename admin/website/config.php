<?php

require_once('../../config/config.php');
$treeConfig = new config();

// Database config & class
$db_config = array(
	"servername"=> $treeConfig->host, 
	"username"	=> $treeConfig->user, 
	"password"	=> $treeConfig->password, 
	"database"	=> $treeConfig->dbname 
);

if(extension_loaded("mysqli")) require_once("_inc/class._database_i.php"); 
else 
require_once("_inc/class._database.php"); 

// Tree class
require_once("_inc/class.tree.php"); 
?>
