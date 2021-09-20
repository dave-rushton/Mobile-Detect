<?php

require_once("../../config/config.php");
require_once("../patchworks.php");

error_reporting("E_ALL");

$qryArray = array();
$sql = "SELECT * FROM cmsprop WHERE cms_id = 1";
$cmsProp = $patchworks->run($sql, array(), true);

require_once 'src/apiClient.php';
require_once 'src/contrib/apiAnalyticsService.php';

$webURL = $cmsProp->gooweb; // 'www.idosoftware.co.uk'; //$ConArr['GooAcc'];
$refreshToken = '1/hpCrYYp0MMmGHbT5GNPSjvZAy_2UbV_FSsZIefAGVig';
$analyticsID = '';

$BegDat = date('Y-m-d', strtotime('-30 days'));
$EndDat = date('Y-m-d');

$reportType = (isset($_REQUEST['report'])) ? $_REQUEST['report'] : 'dashboard';
$fromDate = (isset($_REQUEST['fromdate'])) ? $_REQUEST['fromdate'] : $BegDat; //'2012-01-01';
$toDate = (isset($_REQUEST['todate'])) ? $_REQUEST['todate'] : $EndDat; //'2012-12-31';

$DatArr = array();

session_start();

$client = new apiClient();
$client->setApplicationName("idoSOFTWARE Google Analytics PHP Application");
$client->setClientId('469907447588.apps.googleusercontent.com');
$client->setClientSecret('Ah1q8cW6RVcQYuUDqR-JQwKL');
$client->setRedirectUri('http://localhost/patchworks/patchworks/googleapi/google.analytics.php');
$client->setDeveloperKey('');
$client->setAccessType('offline');
$service = new apiAnalyticsService($client);

if (isset($_GET['logout'])) {
  unset($_SESSION['token']);
}

if (isset($_SESSION['token'])) {
	
	$authObj = json_decode($_SESSION['token']);
	$accessToken = $authObj->access_token;
	$refreshToken = $authObj->refresh_token;
	$tokenType = $authObj->token_type;
	$expiresIn = $authObj->expires_in;
}

if (!is_null($refreshToken)) $client->refreshToken($refreshToken);

if (isset($_GET['code'])) {
	//
	// On return from authentication
	//
	
	$client->authenticate();
	$_SESSION['token'] = $client->getAccessToken();
	
	$authObj = json_decode($_SESSION['token']);
	$_SESSION['refreshToken'] = $authObj->refresh_token;
	
	header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
}

if (isset($_SESSION['token'])) {
	
	//
	// Logged in set up client class
	//
	
	$client->setAccessToken($_SESSION['token']);
}
 	
if ($client->getAccessToken()) {
	
	//
	// Find the account ID
	//
	
	$accounts = $service->management_accounts->listManagementAccounts();
	
	$accountLength = count($accounts['items']);
	
	for ($a=0;$a<$accountLength;$a++) {
		
//		echo $accounts['items'][$a]['name'].'<br>';
		
		if ( $accounts['items'][$a]['name'] == $webURL ) {
			
			$analyticsID = $accounts['items'][$a]['id'];
		}
			
	}
	
	//
	// Analytics class connections
	//
	
	$analytics = new apiAnalyticsService($client);
	$webproperties = $analytics->management_webproperties->listManagementWebproperties($analyticsID);
	$profiles = $analytics->management_profiles->listManagementProfiles($analyticsID, $webproperties['items'][0]['id']);

	if ($reportType == 'dashboard') {
		
		//
		// DASHBOARD QUERY
		//

		$results = $analytics->data_ga->get('ga:'.$profiles['items'][0]['id'], $fromDate, $toDate, 'ga:visitors, ga:visits, ga:newVisits, ga:avgTimeOnSite');
		
		$DatArr['SiteVisits'] = $results['totalsForAllResults']['ga:visits']; 
		
		// Date Visits Break Down
		
		$metrics = "ga:visits, ga:visitors";
		$dimensions = "ga:date";
		$optParams = array('dimensions' => $dimensions);
		$results = $analytics->data_ga->get('ga:'.$profiles['items'][0]['id'], $fromDate, $toDate, $metrics, $optParams);
		
		$LandingData = '';
		$i = 0;
		for ($r=0; $r<count($results['rows']); $r++) {
		
			$d = substr($results['rows'][$r][0], -2);
			$m = substr($results['rows'][$r][0], 4, -2);
			$y = substr($results['rows'][$r][0], 0,4);
			
			$DatArr[$i]['day'] =  $d;
			$DatArr[$i]['month'] =  $m;
			$DatArr[$i]['year'] =  $y;
			$DatArr[$i]['date'] =  date( 'j M', strtotime($y . '-' . $m . '-' . $d) );
			$DatArr[$i]['visits'] = $results['rows'][$r][1];
			
			++$i;
			
		}
		
		$DatArr['count'] = ceil($i -1);	
	
	}
	
	if ($reportType == 'sitestats') {
		
		//
		// GOOGLE ANALYTICS PAGE
		//
		
		// Basic results
		
		$results = $analytics->data_ga->get('ga:'.$profiles['items'][0]['id'], $fromDate, $toDate, 'ga:visitors, ga:visits, ga:visitBounceRate, ga:pageviews, ga:avgTimeOnSite');
		
		$DatArr['SiteVisits'] = $results['totalsForAllResults']['ga:visits']; 
		$DatArr['UniqueVisits'] = $results['totalsForAllResults']['ga:visitors']; 
		$DatArr['PageVisits'] = $results['totalsForAllResults']['ga:pageviews']; 
		$DatArr['BounceRate'] = number_format($results['totalsForAllResults']['ga:visitBounceRate'],2);
		$DatArr['AverageTimeOnSite'] = ceil( ($results['totalsForAllResults']['ga:avgTimeOnSite']/60) ).' mins'; 
		
		// Top landing pages
		
		$metrics = "ga:entrances,ga:bounces";
		$dimensions = "ga:landingPagePath";
		$optParams = array('dimensions' => $dimensions,'sort' => '-ga:entrances','max-results' => 5);
		$results = $analytics->data_ga->get('ga:'.$profiles['items'][0]['id'], $fromDate, $toDate, $metrics, $optParams);
		
		$LandingData = '';
		for ($r=0; $r<count($results['rows']); $r++) {
			
			$LandingData .= (($results['rows'][$r][0] == '/') ? 'home' : $results['rows'][$r][0]) . ',' . $results['rows'][$r][1] . ';';
			
		}
		
		$DatArr['LandingPages'] = $LandingData; 
		
		// top pages
		
		$metrics = "ga:pageviews,ga:uniquePageviews,ga:timeOnPage,ga:bounces,ga:entrances,ga:exits";
		$dimensions = "ga:pagePath";
		$optParams = array('dimensions' => $dimensions,'sort' => '-ga:pageviews','max-results' => 10);
		$results = $analytics->data_ga->get('ga:'.$profiles['items'][0]['id'], $fromDate, $toDate, $metrics, $optParams);
		
		$LandingData = '';
		for ($r=0; $r<count($results['rows']); $r++) {
			
			$LandingData .= (($results['rows'][$r][0] == '/') ? 'home' : $results['rows'][$r][0]) . ',' . $results['rows'][$r][1] . ';';
			
		}
		
		$DatArr['MostVisitedPages'] = $LandingData; 
		
		// Search
		
		$metrics = "ga:visits";
		$dimensions = "ga:keyword";
		$optParams = array('dimensions' => $dimensions, 'sort' => '-ga:visits', 'max-results' => 10);
		$results = $analytics->data_ga->get('ga:'.$profiles['items'][0]['id'], $fromDate, $toDate, $metrics, $optParams);
		
		$LandingData = '';
		for ($r=0; $r<count($results['rows']); $r++) {
			
			$LandingData .= (($results['rows'][$r][0] == '/') ? 'home' : $results['rows'][$r][0]) . ',' . $results['rows'][$r][1] . ';';
			
		}
		
		$DatArr['SearchTerms'] = $LandingData; 
		
		// Search
		
		$metrics = "ga:pageviews,ga:timeOnSite,ga:exits";
		$dimensions = "ga:source";
		$filters = "ga:medium==cpa,ga:medium==cpc,ga:medium==cpm,ga:medium==cpp,ga:medium==cpv,ga:medium==organic,ga:medium==ppc";
		$filters = "ga:medium==referral";
		$optParams = array('dimensions' => $dimensions, 'filters' => $filters, 'sort' => '-ga:pageviews', 'max-results' => 10);
		$results = $analytics->data_ga->get('ga:'.$profiles['items'][0]['id'], $fromDate, $toDate, $metrics, $optParams);
		
		$LandingData = '';
		for ($r=0; $r<count($results['rows']); $r++) {
			
			$LandingData .= (($results['rows'][$r][0] == '/') ? 'home' : $results['rows'][$r][0]) . ',' . $results['rows'][$r][1] . ';';
			
		}
		
		$DatArr['TopReferrers'] = $LandingData; 
		
		// Date Visits Break Down
		
		$metrics = "ga:visits, ga:visitors";
		$dimensions = "ga:date";
		$optParams = array('dimensions' => $dimensions);
		$results = $analytics->data_ga->get('ga:'.$profiles['items'][0]['id'], $fromDate, $toDate, $metrics, $optParams);
		
		$LandingData = '';
		$i = 0;
		for ($r=0; $r<count($results['rows']); $r++) {
		
			$d = substr($results['rows'][$r][0], -2);
			$m = substr($results['rows'][$r][0], 4, -2);
			$y = substr($results['rows'][$r][0], 0,4);
			
			$DatArr[$i]['day'] =  $d;
			$DatArr[$i]['month'] =  $m;
			$DatArr[$i]['year'] =  $y;
			$DatArr[$i]['date'] =  date( 'j M', strtotime($y . '-' . $m . '-' . $d) );
			$DatArr[$i]['visits'] = $results['rows'][$r][1];
			
			++$i;
			
		}
		
		$DatArr['count'] = ceil($i -1);	
	
	}
	
	
//	die();
//	
//	foreach ($results['totalsForAllResults'] as $k => $v) {
//		echo '<p>'.$k.' => '.$v.'</p>';
//	}
//	
//	echo '<h2>Unique Visits</h2>';
//	
//	$results = $analytics->data_ga->get('ga:'.$profiles['items'][0]['id'], $fromDate, $toDate, 'ga:uniquePageviews');
//	
//	foreach ($results['totalsForAllResults'] as $k => $v) {
//		echo '<p>'.$k.' => '.$v.'</p>';
//	}
//	
//	echo '<h2>Organic Searchs</h2>';
//	
//	$results = $analytics->data_ga->get('ga:'.$profiles['items'][0]['id'], $fromDate, $toDate, 'ga:organicSearches');
//	
//	foreach ($results['totalsForAllResults'] as $k => $v) {
//		echo '<p>'.$k.' => '.$v.'</p>';
//	}
//	
//	echo '<h2>Page Tracking</h2>';
//	
//	$results = $analytics->data_ga->get('ga:'.$profiles['items'][0]['id'], $fromDate, $toDate, 'ga:entrances, ga:pageviews, ga:pageviewsPerVisit, ga:timeOnPage ');
//	
//	foreach ($results['totalsForAllResults'] as $k => $v) {
//		echo '<p>'.$k.' => '.$v.'</p>';
//	}
//	
//	echo '<h2>Page Loadtime</h2>';
//	
//	$results = $analytics->data_ga->get('ga:'.$profiles['items'][0]['id'], $fromDate, $toDate, 'ga:pageLoadTime, ga:avgPageLoadTime');
//	
//	foreach ($results['totalsForAllResults'] as $k => $v) {
//		echo '<p>'.$k.' => '.$v.'</p>';
//	}
//	
//	
//	
//	echo '<h1>Using Dimensions</h1>';
//	
//	
//	echo '<h2>Browsers</h2>';
//	
//	$metrics = "ga:visits"; //,ga:pageviews
//	$dimensions = "ga:browser";
//	$optParams = array('dimensions' => $dimensions);
//	$results = $analytics->data_ga->get('ga:'.$profiles['items'][0]['id'], $fromDate, $toDate, $metrics, $optParams);
//	
//	foreach ($results['rows'] as $k => $v) {
//		
//		foreach ($v as $b => $bv) {
//			echo '<p>'.$b.' => '.$bv.'</p>';
//		}
//
//	}
//	
//	echo '<h2>Date</h2>';
//	
//	$metrics = "ga:visits"; //,ga:pageviews
//	$dimensions = "ga:date";
//	$optParams = array('dimensions' => $dimensions);
//	$results = $analytics->data_ga->get('ga:'.$profiles['items'][0]['id'], $fromDate, $toDate, $metrics, $optParams);
//	
//	foreach ($results['rows'] as $k => $v) {
//		
//		foreach ($v as $b => $bv) {
//			echo '<p>'.$b.' => '.$bv.'</p>';
//		}
//
//	}
//	
//	echo '<h2>Website Page Views</h2>';
//	
//	$metrics = "ga:pageviews"; //,ga:pageviews
//	$dimensions = "ga:pagePath";
//	$optParams = array('dimensions' => $dimensions,'sort' => '-ga:pageviews');
//	$results = $analytics->data_ga->get('ga:'.$profiles['items'][0]['id'], $fromDate, $toDate, $metrics, $optParams);
//	
//	foreach ($results['rows'] as $k => $v) {
//		
//		foreach ($v as $b => $bv) {
//			echo '<p>'.$b.' => '.$bv.'</p>';
//		}
//
//	}
//	
//	
//	echo '<h2>GEO / Network</h2>';
//	
//	$metrics = "ga:visits"; //,ga:pageviews
//	$dimensions = "ga:country";
//	$optParams = array('dimensions' => $dimensions, 'sort' => '-ga:visits');
//	$results = $analytics->data_ga->get('ga:'.$profiles['items'][0]['id'], $fromDate, $toDate, $metrics, $optParams);
//	
//	foreach ($results['rows'] as $k => $v) {
//		
//		foreach ($v as $b => $bv) {
//			echo '<p>'.$b.' => '.$bv.'</p>';
//		}
//
//	}
//	
//	
//	echo '<h1>Page Specific</h1>';
//	
//	echo '<h2>Unique page views for the Root URL</h2>';
//	
//	$metrics = "ga:uniquePageviews"; //,ga:pageviews
//	$dimensions = "ga:pagePath";
//	$optParams = array('dimensions' => $dimensions,'filters' => 'ga:pagePath==/');
//	$results = $analytics->data_ga->get('ga:'.$profiles['items'][0]['id'], $fromDate, $toDate, $metrics, $optParams);
//	
//	foreach ($results['rows'] as $k => $v) {
//		
//		foreach ($v as $b => $bv) {
//			echo '<p>'.$b.' => '.$bv.'</p>';
//		}
//
//	}
//	
//	
//	
//	echo '<h2>Organic Searches</h2>';
//	
//	$metrics = "ga:organicSearches";
//	$dimensions = "ga:keyword, ga:source, ga:medium, ga:referralPath";
//	$optParams = array('dimensions' => $dimensions);
//	$results = $analytics->data_ga->get('ga:'.$profiles['items'][0]['id'], $fromDate, $toDate, $metrics, $optParams);
//	
//	foreach ($results['rows'] as $k => $v) {
//		
//		foreach ($v as $b => $bv) {
//			echo '<p>'.$b.' => '.$bv.'</p>';
//		}
//
//	}
//	
//	
//	die();

  
} else {
	
	//
	// Not logged in
	//
	
	$authUrl = $client->createAuthUrl();
	print "<a class='login' href='$authUrl'>Connect Me!</a>";
}

echo json_encode($DatArr);