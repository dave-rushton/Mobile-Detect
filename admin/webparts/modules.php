<?php


$modules = array();

//
// WEBSITE MODULE
//

$module = array();
$module['name'] = 'Website';
$module['items'] = array();

$moduleitem = array();

$moduleitem['name'] = 'Sitemap';
$moduleitem['link'] = 'website/sitemap.php';
array_push($module['items'], $moduleitem);

$moduleitem['name'] = 'Articles';
$moduleitem['link'] = 'website/articles.php';
array_push($module['items'], $moduleitem);

$moduleitem['name'] = 'Article Categories';
$moduleitem['link'] = 'website/article-category.php';
array_push($module['items'], $moduleitem);

$moduleitem['name'] = 'Forms';
$moduleitem['link'] = 'website/forms.php';
array_push($module['items'], $moduleitem);

$moduleitem['name'] = 'Galleries';
$moduleitem['link'] = 'gallery/gallery.php';
array_push($module['items'], $moduleitem);

$moduleitem['name'] = 'Statistics';
$moduleitem['link'] = 'website/statistics.php';
array_push($module['items'], $moduleitem);

array_push($modules, $module);


//
// SALES MODULE
//

$module = array();
$module['name'] = 'Sales';
$module['items'] = array();

$moduleitem = array();

$moduleitem['name'] = 'Customers';
$moduleitem['link'] = 'ecommerce/customers.php';
array_push($module['items'], $moduleitem);

$moduleitem['name'] = 'Orders';
$moduleitem['link'] = 'ecommerce/orders.php';
array_push($module['items'], $moduleitem);

array_push($modules, $module);



//die(json_encode($modules));


?>