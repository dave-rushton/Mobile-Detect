<?php
require_once("../config/config.php");
require_once("../admin/patchworks.php");
require_once("../admin/website/classes/page.handler.php");
require_once("../admin/system/classes/places.cls.php");
require_once("../admin/gallery/classes/uploads.cls.php");
require_once("../admin/events/classes/bookings.cls.php");

$pageHandler = new pageHandler();
$pageHandler->getPage($_GET['seourl'], $_GET, $_POST);

$action = 'home';

//
// SEO DETAILS
//

$pageTitle = $pageHandler->PagTtl;
$keyWords = $pageHandler->KeyWrd;
$keyDescription = $pageHandler->PagDsc;

$TmpPla = new PlaDAO();
$TmpBoo = new BooDAO();
$TmpUpl = new UplDAO();

if (isset($_GET['boo_id']) && is_numeric($_GET['boo_id'])) {

    // FIND EVENT

    $action = 'event';

    //$eventRec = $TmpPla->select($_GET['pla_id'], 'EVT', NULL, NULL, NULL, true);
    $bookingRec = $TmpBoo->select($_GET['boo_id'], NULL, NULL, NULL, NULL, NULL, NULL, NULL, true, NULL, NULL, NULL);

    //$bookings = $TmpBoo->select(NULL, date("Y-m-d"), NULL, 'EVENT', $eventRec->pla_id, NULL, NULL, NULL, false, NULL, NULL, 'begdat');
    $eventRec = $TmpPla->select($bookingRec->tbl_id, 'EVT', NULL, NULL, NULL, true);
    $venueRec = $TmpPla->select($bookingRec->ref_id, 'EVT', NULL, NULL, NULL, true);


} else {

    // EVENTS HOME

    //$events = $TmpPla->select(NULL, 'EVT', NULL, NULL);
    $upcomingEvents = $TmpBoo->select(NULL, date("Y-m-d"), NULL, 'EVENT', NULL, NULL, NULL, NULL, false, NULL, NULL, 'begdat');

    //var_dump($upcomingEvents);

}


?>
<!DOCTYPE html>
<html lang="en" itemscope itemtype="http://schema.org/Product">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1">
    <link rel="icon" href="favicon.ico" type="image/x-icon"/>

    <title><?php echo $pageTitle; ?></title>

    <?php echo $pageHandler->googleAnalytics(); ?>

    <base href="<?php echo $patchworks->webRoot; ?>"/>
    <meta name="keywords" content="<?php echo $keyWords; ?>"/>
    <meta name="description" content="<?php echo $keyDescription; ?>"/>

    <link href='https://fonts.googleapis.com/css?family=Roboto:300,700,400' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Dancing+Script:400,700' rel='stylesheet' type='text/css'>

    <?php
    echo $pageHandler->critialCSS();
    echo $pageHandler->getTopJS($_GET['seourl']);
    ?>

    <script src="pages/js/jquery.js"></script>

</head>

<body>

<div id="watermarkWrapper">
    <div class="watermark"></div>
</div>

<?php
include('webparts/page.header.php');
?>

<?php
if ($action == 'home') {
    ?>
    <div class="pageElement" id="fullwidthcontent">
        <?php $pageHandler->displayElements('fullwidthcontent'); ?>
    </div>
    <?php
}
?>

<div class="section nomargin nopadding">
    <div class="container">

        <div class="row">
            <div class="col-sm-12">

                <div id="eventCatalogueWrapper">

                    <?php

                    if ($action == 'home') {

                        include('events/events.home.php');

                    } else if ($action == 'event') {

                        include('events/events.event.php');

                    } else if ($action == 'booking') {

                        include('events/events.booking.php');

                    }

                    ?>

                </div>

            </div>

        </div>
    </div>
</div>

<div class="pageElement" id="fullwidthcontentb">
    <?php $pageHandler->displayElements('fullwidthcontentb'); ?>
</div>

<?php
include('webparts/page.footer.php');
?>

<script src="pages/js/cms-events.js"></script>

</body>

<link rel="stylesheet" type="text/css" href="pages/css/events.css">

</html>