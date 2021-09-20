<?php

$doc = new DOMDocument;

// We don't want to bother with white spaces
$doc->preserveWhiteSpace = false;

// Most HTML Developers are chimps and produce invalid markup...
$doc->strictErrorChecking = false;
$doc->recover = true;

$doc->loadHTMLFile('http://lakelands.s9demo.co.uk/opportunities');

$xpath = new DOMXPath($doc);

$query = "//div[@id='webContent']";

$entries = $xpath->query($query);
var_dump($entries->item(0)->textContent);

die();

?>
<?php

require_once("../../../config/config.php");
require_once("../../patchworks.php");
require_once("../classes/pages.cls.php");
require_once("../classes/page.handler.php");

$page = file_get_contents('http://lakelands.s9demo.co.uk/opportunities');

$doc = new DOMDocument();
@$doc->loadHTML($page);

var_dump($doc);

$divs = $doc->getElementsByTagName('div');
foreach($divs as $div) {

    // Loop through the DIVs looking for one withan id of "content"
    // Then echo out its contents (pardon the pun)
    if ($div->getAttribute('id') === 'websiteContent') {
        var_dump($div);
    }
}

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
//if ($loggedIn == 0) header('location: ../login.php');

//$Pag_ID = (isset($_POST['pag_id']) && is_numeric($_POST['pag_id'])) ? $_POST['pag_id'] : die('FAIL');



die();


$PagTxt = (isset($_POST['pagtxt'])) ? $_POST['pagtxt'] : NULL;

$PagDao = new PagDAO();
$pageRec = $PagDao->select($Pag_ID, NULL, true);

$throwJSON = array();
$throwJSON['id'] = '0';
$throwJSON['title'] = 'noaction';
$throwJSON['description'] = 'no action taken';
$throwJSON['type'] = 'warning';

$jsonArray = array();

if ($pageRec) {

	if (!is_null($PagTxt)) $pageRec->pagtxt = $PagTxt;

	$PagDao->updatePageText($pageRec);
		
	$throwJSON = array();
	$throwJSON['id'] = $Pag_ID;
	$throwJSON['title'] = 'Page Updated';
	$throwJSON['description'] = $pageRec->title.' page updated';
	$throwJSON['type'] = 'success';

} else {

	$throwJSON = array();
	$throwJSON['id'] = '0';
	$throwJSON['title'] = 'Page Not Found';
	$throwJSON['description'] = 'the page you were looking for could not be found';
	$throwJSON['type'] = 'error';
	
}

die(json_encode($throwJSON));

?>