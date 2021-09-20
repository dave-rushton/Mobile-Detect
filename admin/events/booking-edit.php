<?php

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("../system/classes/places.cls.php");
require_once("../system/classes/statuscodes.cls.php");
require_once("../bookings/classes/bookings.cls.php");
require_once("../products/classes/products.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpPla = new PlaDAO();
$editPlaceID = (isset($_GET['pla_id']) && is_numeric($_GET['pla_id'])) ? $_GET['pla_id'] : NULL;
$placeRec = NULL;
if (!is_null($editPlaceID)) $placeRec = $TmpPla->select($editPlaceID, NULL, NULL, NULL, NULL, true);


$places = $TmpPla->select(NULL, 'VENUE', NULL, NULL, 0, false);

$TmpPrd = new PrdDAO();
$products = $TmpPrd->select(NULL, NULL, NULL, NULL, false);

$TmpSta = new StaDAO();
$statusCodes = $TmpSta->select(NULL, 'BOOKING', false);

?>
<!doctype html>
<html>
<head>
    <title>Event : <?php echo ($placeRec) ? $placeRec->planam : 'New Event'; ?></title>
    <?php include('../webparts/headdata.php'); ?>

    <link rel="stylesheet" href="css/plugins/datepicker/datepicker.css">

    <link rel="stylesheet" href="css/plugins/timepicker/bootstrap-timepicker.min.css">
    <script src="js/plugins/timepicker/bootstrap-timepicker.min.js"></script>

    <script src="js/plugins/datepicker/bootstrap-datepicker.js"></script>
    <script src="js/system.date.js"></script>

    <script type="text/javascript"
            src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDjZSf7lI4D80NIwFMozDDABq-tSkGgKIs&sensor=false"></script>
    <script src="js/plugins/gmap/gmap3.min.js"></script>
    <script src="js/plugins/gmap/gmap3-menu.js"></script>

    <script src="js/plugins/ckeditor/ckeditor.js"></script>

    <script src="events/js/booking-edit.js"></script>
</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-green">
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
                            <form action="bookings/bookings_script.php" id="bookingForm" class="form-horizontal form-bordered">
                                <input type="hidden" name="boo_id" value="0">
                                <input type="hidden" name="tblnam" value="EVENT">
                                <input type="hidden" name="tbl_id" value="<?php echo $_GET['pla_id']; ?>">
                                <input type="hidden" name="refnam" value="">
                                <input type="hidden" name="begdat" value="">
                                <input type="hidden" name="enddat" value="">
                                <div class="control-group">
                                    <label class="control-label">Venue<small>select venue</small></label>
                                    <div class="controls">
                                        <select data-placeholder="Select a project..." name="ref_id" class="input-block-level">
                                            <option value="0">N/A</option>
                                            <?php
                                            $tableLength = count($places);
                                            for ($i=0;$i<$tableLength;++$i) {
                                                ?>
                                                <option value="<?php echo $places[$i]['pla_id']; ?>"><?php echo $places[$i]['planam']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Product<small>select product</small></label>
                                    <div class="controls">
                                        <select data-placeholder="Select an product..." name="prd_id" class="input-block-level">
                                            <option value=""></option>
                                            <?php
                                            $tableLength = count($products);
                                            for ($i=0;$i<$tableLength;++$i) {
                                                ?>
                                                <option value="<?php echo $products[$i]['prd_id']; ?>"><?php echo $products[$i]['prdnam']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group hide">
                                    <label class="control-label">Description<small>short description of booking</small></label>
                                    <div class="controls">
                                        <textarea class="input-block-level" name="boodsc" rows="6"></textarea>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Start Date<small>booking start date</small></label>
                                    <div class="controls">
                                        <input type="text" class="input-block-level" name="begdatdsp" value="<?php echo date("Y-m-d"); ?>">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Start Time<small>booking start time</small></label>
                                    <div class="controls">
                                        <div class="bootstrap-timepicker">
                                            <input type="text" class="input-block-level" name="begtim" value="09:00">
                                        </div>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">End Date<small>booking end date</small></label>
                                    <div class="controls">
                                        <input type="text" class="input-block-level" name="enddatdsp" value="<?php echo date("Y-m-d"); ?>">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">End Time<small>booking end time</small></label>
                                    <div class="controls">
                                        <div class="bootstrap-timepicker">
                                            <input type="text" class="input-block-level" name="endtim" value="17:00">
                                        </div>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Status<small>booking status</small></label>
                                    <div class="controls">
                                        <select name="sta_id" class="input-block-level">
                                            <option value="0">Available</option>
                                            <option value="1">In-Active</option>
                                            <option value="2">Cancelled</option>
                                        </select>
                                    </div>
                                </div>


                                <div class="control-group hide">
                                    <label class="control-label">Color<small>calendar colour</small></label>
                                    <div class="controls">
                                        <select name="boocol" class="input-block-level">
                                            <option value="#e51400">Red</option>
                                            <option value="#f8a31f">Orange</option>
                                            <option value="#393">Green</option>
                                            <option value="#a05000">Brown</option>
                                            <option value="#368ee0">Blue</option>
                                            <option value="#8cbf26">Lime</option>
                                            <option value="#00aba9">Teal</option>
                                            <option value="#ff0097">Purple</option>
                                            <option value="#e671b8">Pink</option>
                                            <option value="#a200ff">Magenta</option>
                                            <option value="#333">Grey</option>
                                            <option value="#204e81">Dark Blue</option>
                                            <option value="#e63a3a">Light Red</option>
                                            <option value="#666">Light Grey</option>
                                            <option value="#2c5e7b">Sat Blue</option>
                                            <option value="#56af45">Sat Green</option>
                                        </select>
                                    </div>
                                </div>

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
