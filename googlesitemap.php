<?php 
require_once("config/config.php");
require_once("admin/patchworks.php");
require_once("admin/website/classes/pages.cls.php");
header('Content-Type: application/xml');
echo '<?xml version="1.0" encoding="UTF-8"?>'."\n"; 
?>

<urlset xmlns="http://www.google.com/schemas/sitemap/0.84">

<?php

$PagDao = new PagDAO();

$pages = $PagDao->select(NULL, NULL, false);

$tableLength = count($pages);
for ($i=0;$i<$tableLength;++$i) {
	
	if ($pages[$i]['pag_id'] < 2) continue;
	
?>

	<url>
		<loc><?php echo $patchworks->webRoot.$pages[$i]['seourl']; ?></loc>
	</url>

<?php
}

?>

</urlset>