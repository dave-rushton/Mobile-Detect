<?php

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("../system/classes/places.cls.php");
require_once("../projects/classes/bookings.cls.php");
require_once("../products/classes/products.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpBoo = new BooDAO();
$bookingRec = $TmpBoo->select($_GET['boo_id'], NULL, NULL, NULL, NULL,NULL, NULL, NULL, true, NULL, NULL, NULL);

//$TmpPla = new PlaDAO();
//$editPlaceID = (isset($_GET['pla_id']) && is_numeric($_GET['pla_id'])) ? $_GET['pla_id'] : NULL;
//$placeRec = NULL;
//if (!is_null($editPlaceID)) $placeRec = $TmpPla->select($editPlaceID, NULL, NULL, NULL, NULL, true);
//
//$places = $TmpPla->select(NULL, 'VENUE', NULL, NULL, 0, false);

// find venue for capacity
// find order lines for booking to calculate availability

?>
<!doctype html>
<html>
<head>
    <title>Event : <?php echo ($placeRec) ? $placeRec->planam : 'New Event'; ?></title>
    <?php include('../webparts/headdata.php'); ?>
</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
    <?php include('../webparts/sales-left.php'); ?>
    <div id="main">
        <div class="container-fluid">
            <div class="page-header">
                <div class="pull-left">
                    <h1>Event : <?php echo ($placeRec) ? $placeRec->planam : 'New Event'; ?></h1>
                </div>
                <div class="pull-right">
                    <?php include('../webparts/index-info.php'); ?>
                </div>
            </div>
            <div class="breadcrumbs">
                <ul>
                    <li>
                        <a href="index.php">Dashboard</a>
                        <i class="icon-angle-right"></i>
                    </li>
                    <li>
                        <a href="events/events.php">Events</a>
                        <i class="icon-angle-right"></i>
                    </li>
                    <li>
                        <a><?php echo ($placeRec) ? $placeRec->planam : 'New Event'; ?></a>
                    </li>
                </ul>
            </div>
            <div class="row-fluid">

                <div class="span12">

                    <div class="box box-color box-bordered">
                        <div class="box-title">
                            <h3>
                                <i class="icon-shopping-cart"></i> Booking Form</h3>
                            <div class="actions">
                                <a href="#listingScreen" class="btn btn-mini screenSelect" rel="tooltip" title="Cancel"><i class="icon-remove"></i></a>
                                <a href="#" class="btn btn-mini" rel="tooltip" title="Update Booking" id="updateBooking"><i class="icon-save"></i></a>
                            </div>
                        </div>

                        <div class="box-content nopadding">
                            <form action="events/create-booking.php" method="post" id="bookingForm" class="form-horizontal form-bordered">

                                <input type="hidden" name="prd_id" value="<?php echo $bookingRec->prd_id; ?>">
                                <input type="hidden" name="boo_id" value="<?php echo $bookingRec->boo_id; ?>">

                                <div class="control-group">
                                    <label class="control-label">Name<small>invoice name</small></label>
                                    <div class="controls">
                                        <input type="text" class="input-block-level" name="planam" value="">
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">Address</label>
                                    <div class="controls">
                                        <input type="text" class="input-block-level input-margin-bottom" name="adr1" value="">
                                        <input type="text" class="input-block-level input-margin-bottom" name="adr2" value="">
                                        <input type="text" class="input-block-level input-margin-bottom" name="adr3" value="">
                                        <input type="text" class="input-block-level input-margin-bottom" name="adr4" value="">
                                        <input type="text" class="input-block-level" name="ctynam" value="">
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">Postcode</label>
                                    <div class="controls">
                                        <div class="input-append">
                                            <input type="text" class="input-large" name="pstcod" value="">
                                            <button id="geoLocate" class="btn"><i class="icon-map-marker"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">Attendees</label>
                                    <div class="controls">
                                        <select name="numuni" class="input-large">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>

                                        </select>
                                    </div>
                                </div>

                                <button type="submit">Submit</button>

                            </form>
                        </div>
                    </div>

                </div>

            </div>


        </div>
    </div>
</div>
</body>
</html>
