<?php

require_once('../../../config/config.php');
require_once('../../../admin/patchworks.php');
require_once("../../../admin/system/classes/places.cls.php");
require_once("../../../admin/events/classes/bookings.cls.php");
/*require_once("../../../admin/products/classes/products.cls.php");*/
require_once("../../../admin/gallery/classes/uploads.cls.php");

$TmpPla = new PlaDAO();
$TmpBoo = new BooDAO();
/*$TmpPrd = new PrdDAO();*/
$UplDao = new UplDAO();

$events = $TmpPla->select(NULL, 'EVT', NULL, NULL);

$FwdUrl = (isset($_GET['seourl'])) ? $_GET['seourl'] : NULL;

$DspTyp = 'Events';


if (isset($_GET['evtseo'])) {

    $DspTyp = 'Event';
    $eventRec = $TmpPla->select($_GET['evtseo'], NULL, NULL, NULL, NULL, true);
    $startDate = date("Y-m-d");
    $bookings = $TmpBoo->select(NULL, $startDate, NULL, 'EVENT', $eventRec->pla_id, NULL, NULL, NULL, false, NULL, NULL, 'begdat');

}

if (isset($_GET['boo_id'])) {

    $DspTyp = 'Booking';

    $bookingDate = $TmpBoo->select($_GET['boo_id'], NULL, NULL, NULL, NULL, NULL, NULL, NULL, true, NULL, NULL, 'begdat desc');

    $eventRec = $TmpPla->select($bookingDate->tbl_id, NULL, NULL, NULL, NULL, true);
    $venueRec = $TmpPla->select($bookingDate->ref_id, NULL, NULL, NULL, NULL, true);
    //$productRec = $TmpPrd->select($bookingDate->prd_id, NULL, NULL, NULL, NULL, true, NULL, true, NULL, NULL);

    $bookings = $TmpBoo->select(NULL, date("Y-m-d"), NULL, 'EVENT', $eventRec->pla_id, NULL, NULL, NULL, false, NULL, NULL, 'begdat');

    $availability = $TmpBoo->getAvailability($_GET['boo_id']);
    //$availablePlaces = $availability->rooms - $availability->booked;

    if (isset($availability->rooms)) {
        $availablePlaces = $availability->rooms - $availability->booked;
    } else {
        $availablePlaces = $bookingDate->rooms;
    }


}


if (isset($_GET['bookingaction']) && $_GET['bookingaction'] == 'details') { $DspTyp = 'CustomerDetails'; }
if (isset($_GET['bookingaction']) && $_GET['bookingaction'] == 'summary') { $DspTyp = 'BookingSummary'; }

if (isset($_GET['bookingaction']) && $_GET['bookingaction'] == 'success') { $DspTyp = 'Success'; }
if (isset($_GET['bookingaction']) && $_GET['bookingaction'] == 'fail') { $DspTyp = 'Fail'; }
if (isset($_GET['bookingaction']) && $_GET['bookingaction'] == 'availability') { $DspTyp = 'Availability'; }

if (isset($_GET['stepno']) && $_GET['stepno'] == 1) { $DspTyp = 'PaymentForm'; }

$bookingDetail = (isset($_POST['bookingdetail'])) ? json_decode($_POST['bookingdetail'], true) : array();

?>


<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">



<?php

if ($DspTyp != 'Events' && !isset($_GET['bookingaction'])) {

    ?>

    <div class="row">
        <div class="col-md-12">

            <ol class="breadcrumb">
                <li><a href="<?php echo $_GET['seourl']; ?>">COURSE BOOKING</a></li>

                <?php if (isset($eventRec)) { ?>


                    <?php if (isset($bookingDate)) { ?>
                        <li><a href="<?php echo $_GET['seourl'] . '/event/' . $eventRec->pla_id; ?>"><?php echo $eventRec->planam; ?></a></li>
                        <li class="active"><?php echo date("jS F Y", strtotime($bookingDate->begdat)); ?></li>

                    <?php } else { ?>
                        <li class="active"><?php echo $eventRec->planam; ?></li>
                    <?php } ?>
                <?php } ?>

            </ol>

        </div>
    </div>
<?php

}

?>

<?php

if ($DspTyp == 'Events') {

    $tableLength = count($events);
    for ($i = 0; $i < $tableLength; ++$i) {

        $uploads = $UplDao->select(NULL, 'EVENT', $events[$i]['pla_id'], NULL, false);

        ?>

        <div class="coursewrapper">
            <div class="row">
                <div class="col-md-3">

                    <?php
                    if (
                        isset($uploads[0]) &&
                        file_exists($patchworks->docRoot . 'uploads/images/200-200/' . $uploads[0]['filnam']) &&
                        !is_dir($patchworks->docRoot . 'uploads/images/200-200/' . $uploads[0]['filnam'])
                    ) {
                        echo '<img src="uploads/images/200-200/' . $uploads[0]['filnam'] . '" class="img-responsive" />';
                    } else {
                        echo '<img class="img-responsive" src="http://placehold.it/200x200&text=Awaiting Image">';
                    }
                    ?>

                </div>
                <div class="col-md-9">

                    <h2><a href="<?php echo $patchworks->webRoot . $FwdUrl; ?>/event/<?php echo $events[$i]['pla_id']; ?>"><?php echo $events[$i]['planam']; ?></a></h2>

                    <p>
                        <?php
                        if (strlen($events[$i]['platxt']) > 250) {
                            $pos = strpos($events[$i]['platxt'], ' ', 250);
                            echo substr($events[$i]['platxt'], 0, $pos).'...';
                        } else {
                            echo $events[$i]['platxt'];
                        }
                        ?>
                    </p>

                </div>
            </div>

        </div>

    <?php
    }
    ?>

<?php
}
?>


<?php

//echo $DspTyp.' '.$bookingDetail['billingaddress']['fwdurl'];

?>

<div class="row">
<?php if ($DspTyp == 'Event' || $DspTyp == 'Booking') { ?>


        <div class="col-md-7">

            <h1><?php echo $eventRec->planam; ?></h1>

            <?php if ($DspTyp == '_Booking') { ?>
                <a href="#" class="readmorelink">READ MORE...</a>
            <?php } ?>

            <div class="eventdescription" <?php if ($DspTyp == '_Booking') echo 'style="display: none;"'; ?> >
                <?php echo $eventRec->platxt; ?>
            </div>

        </div>


<?php } ?>


<?php
if ($DspTyp == 'Event') {
    ?>


        <div class="col-md-5">

            <h2>Event Dates</h2>

            <ul class="eventdates">
            <?php
            $tableLength = count($bookings);
            for ($i = 0; $i < $tableLength; ++$i) {

                $availability = $TmpBoo->getAvailability($bookings[$i]['boo_id']);

                if (isset($availability->rooms)) {
                    $availablePlaces = $availability->rooms - $availability->booked;
                } else {
                    $availablePlaces = $bookings[$i]['rooms'];
                }

                ?>
                <li>

                    <a href="<?php echo $patchworks->webRoot . $FwdUrl; ?>/eventdate/<?php echo $bookings[$i]['boo_id']; ?>">

                    <div class="dateformat">
                        <div class="day"><?php echo date("d", strtotime($bookings[$i]['begdat'])); ?></div>
                        <div class="mon"><?php echo strtoupper(date("M", strtotime($bookings[$i]['begdat']))); ?></div>
                    </div>

                    <?php //echo date("D jS M Y", strtotime($bookings[$i]['begdat'])); ?>
                    <?php echo $bookings[$i]['vennam']; ?><br>
<!--                    <span>--><?php //echo $availablePlaces; ?><!-- places available</span>-->

                        <p>

                            <?php

                            $startdate=date("Y-m-d H:i:s");
                            $enddate=date("Y-m-d H:i:s", strtotime($bookings[$i]['begdat']));

                            $diff=strtotime($enddate)-strtotime($startdate);

                            // immediately convert to days
                            $temp=$diff/86400; // 60 sec/min*60 min/hr*24 hr/day=86400 sec/day

                            // days
                            $days=floor($temp);
                            //echo "days: $days<br/>\n";
                            $temp=24*($temp-$days);
                            // hours
                            $hours=floor($temp);
                            //echo "hours: $hours<br/>\n";
                            $temp=60*($temp-$hours);
                            // minutes
                            $minutes=floor($temp);
                            //echo "minutes: $minutes<br/>\n";
                            $temp=60*($temp-$minutes);
                            // seconds
                            $seconds=floor($temp);
                            //echo "seconds: $seconds<br/>\n<br/>\n";

                            //echo "Result: {$days}d {$hours}h {$minutes}m {$seconds}s<br/>\n";
                            echo "EVENT IN: {$days} days, {$hours}h, {$minutes}m";

                            ?>

                        </p>

                    </a>
                </li>
            <?php
            }
            ?>
            </ul>

        </div>


<?php } ?>



<?php
if ($DspTyp == 'Booking') {
    ?>

        <div class="col-md-5">

<!--            <h2>--><?php //echo $productRec->prdnam; ?><!-- <span-->
<!--                    class="pull-right">&pound;--><?php //echo $productRec->unipri; ?><!--</span></h2>-->

            <h3><?php echo date("jS F Y", strtotime($bookingDate->begdat)); ?></h3>

            <div class="availability">

                <?php //echo $availablePlaces.' OUT OF '.$venueRec->rooms.' PLACES AVAILABLE'; ?>

            </div>

            <address>
                <strong><?php echo $venueRec->planam; ?></strong><br>
                <?php echo $venueRec->adr1; ?><br>
                <?php echo $venueRec->adr2; ?><br>
                <?php echo $venueRec->adr3; ?><br>
                <?php echo $venueRec->adr4; ?><br>
                <?php echo $venueRec->pstcod; ?><br><br>
                <abbr title="Telephone">T:</abbr> <?php echo $venueRec->platel; ?><br>
                <abbr title="Email">E:</abbr> <a href="mailto:<?php echo $venueRec->plaema; ?>"> <?php echo $venueRec->plaema; ?></a>
            </address>


            <div class="eventmap" id="eventmap" style="height: 200px; margin-bottom: 30px;"></div>



            <?php
            if ($availablePlaces > 0) {
            ?>

                <a href="<?php echo $_GET['seourl']; ?>/eventbooking/details/<?php echo $_GET['boo_id']; ?>" class="arrowbtn">Book Your Place</a>

            <?php
            } else {
            ?>

                <h4>SORRY THIS COURSE IS FULLY BOOKED</h4>

                <?php
                $tableLength = count($bookings);
                for ($i = 0; $i < $tableLength; ++$i) {

                    $availability = $TmpBoo->getAvailability($bookings[$i]['boo_id']);

                    if (isset($availability->rooms)) {
                        $availablePlaces = $availability->rooms - $availability->booked;
                    } else {
                        $availablePlaces = $bookings[$i]['rooms'];
                    }

                    ?>
                    <p>
                        <a href="<?php echo $patchworks->webRoot . $FwdUrl; ?>/eventdate/<?php echo $bookings[$i]['boo_id']; ?>"><?php echo date("D jS M Y", strtotime($bookings[$i]['begdat'])); ?></a><br>
                        <?php echo $bookings[$i]['vennam']; ?>
                    </p>
                <?php
                }
                ?>

            <?php
            }
            ?>

        </div>


<?php } ?>



<?php
if ($DspTyp == 'CustomerDetails') {
    ?>

    <div class="row">
    <div class="col-md-12">

<!--        <h2>--><?php //echo $productRec->prdnam; ?><!-- <span-->
<!--                class="pull-right">&pound;--><?php //echo $productRec->unipri; ?><!--</span></h2>-->

        <h3><?php echo date("jS F Y", strtotime($bookingDate->begdat)); ?></h3>

        <div class="availability">

            <?php //echo $availablePlaces.' OUT OF '.$venueRec->rooms.' PLACES AVAILABLE'; ?>

        </div>

        <address>
            <strong><?php echo $venueRec->planam; ?></strong><br>
            <?php echo $venueRec->adr1; ?><br>
            <?php echo $venueRec->adr2; ?><br>
            <?php echo $venueRec->adr3; ?><br>
            <?php echo $venueRec->adr4; ?><br>
            <?php echo $venueRec->pstcod; ?><br><br>
            <abbr title="Telephone">T:</abbr> <?php echo $venueRec->platel; ?><br>
            <abbr title="Email">E:</abbr> <a
                href="mailto:<?php echo $venueRec->plaema; ?>"> <?php echo $venueRec->plaema; ?></a>
        </address>

        <div class="eventmap" id="eventmap" style="height: 200px; margin-bottom: 30px;"></div>

        <?php
        if ($availablePlaces > 0) {
            ?>

            <form method="post" action="pages/events/events_control.php" data-parsley-validate data-parsley-excluded="input[type=button], input[type=submit], input[type=reset], input[type=hidden], [disabled], :hidden">

                <h2>Book Your Place</h2>

                <input type="hidden" name="boo_id" value="<?php echo $_GET['boo_id']; ?>">
                <!--<input type="hidden" name="prd_id" value="<?php /*echo $productRec->prd_id; */?>">-->
                <input type="hidden" name="fwdurl" value="<?php echo $_GET['seourl']; ?>">
                <input type="hidden" name="stepno" value="1">


                <div class="form-group">
                    <label>Title</label>
                    <select name="custtl" class="form-control">
                        <option value="Mr">Mr</option>
                        <option value="Mrs">Mrs</option>
                        <option value="Ms">Ms</option>
                        <option value="Dr">Dr</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" class="form-control" name="cusfna" placeholder="First Name" required data-parsley-error-message="Please enter a first name" value="<?php if (isset($bookingDetail['billingaddress'])) echo $bookingDetail['billingaddress']['firstname']; ?>">
                </div>
                <div class="form-group">
                    <label>Surname</label>
                    <input type="text" class="form-control" name="cussna" placeholder="Surname" required data-parsley-error-message="Please enter a surname" value="<?php if (isset($bookingDetail['billingaddress'])) echo $bookingDetail['billingaddress']['surname']; ?>">
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="text" class="form-control" name="emailaddress" placeholder="Your Email" required data-parsley-type="email" data-parsley-error-message="Please enter a contact email" value="<?php if (isset($bookingDetail['billingaddress'])) echo $bookingDetail['billingaddress']['cusema']; ?>">
                </div>
                <div class="form-group">
                    <label>Telephone</label>
                    <input type="text" class="form-control" name="telephone" placeholder="Your Telephone" value="<?php if (isset($bookingDetail['billingaddress'])) echo $bookingDetail['billingaddress']['custel']; ?>">
                </div>

                <div class="form-group">
                    <label>Address</label>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="adr1" placeholder="Address Line 1" required data-parsley-error-message="Please enter address line 1" value="<?php if (isset($bookingDetail['billingaddress'])) echo $bookingDetail['billingaddress']['adr1']; ?>">
                </div>
                <div class="form-group">

                    <input type="text" class="form-control" name="adr2" placeholder="Address Line 2" value="<?php if (isset($bookingDetail['billingaddress'])) echo $bookingDetail['billingaddress']['adr2']; ?>">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="adr3" placeholder="Town" required data-parsley-error-message="Please enter a town" value="<?php if (isset($bookingDetail['billingaddress'])) echo $bookingDetail['billingaddress']['adr3']; ?>">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="adr4" placeholder="County" value="<?php if (isset($bookingDetail['billingaddress'])) echo $bookingDetail['billingaddress']['adr4']; ?>">
                </div>

                <div class="form-group">
                    <label>Postcode</label>
                    <input type="text" class="form-control" name="pstcod" placeholder="Postcode" required data-parsley-pattern="[A-Z]{1,2}[0-9][0-9A-Z]?\s?[0-9][A-Z]{2}" data-parsley-error-message="Please enter post code" value="<?php if (isset($bookingDetail['billingaddress'])) echo $bookingDetail['billingaddress']['pstcod']; ?>">
                </div>

                <div class="form-group">
                    <label>Group Size</label>

                    <select name="numuni" class="form-control">

                        <?php

                        for ($i = 1; $i <= $availablePlaces; $i++) {

                            $checked = (isset($bookingDetail['billingaddress']) && $bookingDetail['billingaddress']['numuni'] == $i ) ? ' selected ' : '';

                            echo '<option value="' . $i . '"'. $checked  .'>' . $i . '</option>';
                        }

                        ?>

                    </select>
                </div>


                <div class="checkbox">
                    <label>
                        <input type="checkbox" required data-parsley-error-message="Please agree to the terms and conditions" value="1" name="tncagree"> Agree to the <a href="ts-and-cs">terms and conditions</a>
                    </label>
                </div>

                <button type="submit" class="btn btn-default">Submit</button>
            </form>

        <?php
        } else {
            ?>

            <h4>SORRY THIS COURSE IS FULLY BOOKED</h4>

            <?php
            $tableLength = count($bookings);
            for ($i = 0; $i < $tableLength; ++$i) {

                $availability = $TmpBoo->getAvailability($_GET['boo_id']);
                $availablePlaces = $availability->rooms - $availability->booked;

                ?>
                <p>
                    <a href="<?php echo $patchworks->webRoot . $FwdUrl; ?>/eventdate/<?php echo $bookings[$i]['boo_id']; ?>"><?php echo date("D jS M Y", strtotime($bookings[$i]['begdat'])); ?></a><br>
                    <?php echo $bookings[$i]['vennam']; ?>
                </p>
            <?php
            }
            ?>

        <?php
        }
        ?>

    </div>
    </div>


<?php } ?>


<?php if ($DspTyp == 'BookingSummary') { ?>

    <div class="col-md-12">
        <h1>Booking Summary</h1>

<!--        <hr>-->

<!--        <div class="row">-->
<!--            <div class="col-md-4">-->
<!--                <p>--><?php //echo $productRec->prdnam; ?><!-- @ --><?php //echo $venueRec->planam; ?><!--</p>-->
<!--            </div>-->
<!--            <div class="col-md-2">--><?php //echo date("jS F Y", strtotime($bookingDate->begdat)); ?><!--</div>-->
<!--            <div class="col-md-2 text-right">&pound;--><?php //echo $productRec->unipri; ?><!--</div>-->
<!--            <div class="col-md-2 text-right">--><?php //echo $bookingDetail['billingaddress']['numuni']; ?><!--</div>-->
<!--            <div class="col-md-2 text-right">--><?php //echo number_format($bookingDetail['billingaddress']['numuni'] * $productRec->unipri, 2); ?><!--</div>-->
<!--        </div>-->

        <table class="table">
            <tr>
                <th>Course</th>
                <th>Date</th>
                <th class="text-right">Price</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Total</th>
            </tr>
            <tr>
                <td><?php //echo $productRec->prdnam; ?> @ <?php echo $venueRec->planam; ?></td>
                <td><?php echo date("jS F Y", strtotime($bookingDate->begdat)); ?></td>
                <td class="text-right">&pound;<?php //echo $productRec->unipri; ?></td>
                <td class="text-right"><?php echo $bookingDetail['billingaddress']['numuni']; ?></td>
                <td class="text-right">&pound;<?php //echo number_format($bookingDetail['billingaddress']['numuni'] * $productRec->unipri, 2); ?></td>
            </tr>
        </table>

        <h1><br><br>Billing Details</h1>
        <hr>

        <div class="row">
            <div class="col-md-6">

                <address>
                    <strong><?php echo $bookingDetail['billingaddress']['title'].' '.$bookingDetail['billingaddress']['firstname'].' '.$bookingDetail['billingaddress']['surname']; ?></strong><br>
                    <?php echo $bookingDetail['billingaddress']['adr1']; ?><br>
                    <?php echo $bookingDetail['billingaddress']['adr2']; ?><br>
                    <?php echo $bookingDetail['billingaddress']['adr3']; ?><br>
                    <?php echo $bookingDetail['billingaddress']['adr4']; ?><br>
                    <?php echo $bookingDetail['billingaddress']['pstcod']; ?><br><br>
                    <abbr title="Telephone">T:</abbr> <?php echo $bookingDetail['billingaddress']['custel']; ?><br>
                    <abbr title="Email">E:</abbr> <?php echo $bookingDetail['billingaddress']['cusema']; ?>
                </address>

                <p><a href="pages/events/events_control.php?stepno=2" class="arrowbtn">CONFIRM BOOKING</a></p>

            </div>
            <div class="col-md-6"></div>
        </div>


    </div>
<?php } ?>


<?php if ($DspTyp == 'PaymentForm') { ?>
    <div class="col-md-12">
        <h1>Please enter your details</h1>
    </div>
<?php } ?>




<?php
if ($DspTyp == 'Success') {

?>
    <div class="col-md-12">
        <h1>Thank you for your booking</h1>
    </div>
<?php



}
?>

<?php if ($DspTyp == 'Fail') { ?>
    <div class="col-md-12">
        <h1>We're sorry something went wrong</h1>
    </div>
<?php } ?>

<?php if ($DspTyp == 'Availability') { ?>
    <div class="col-md-12">
        <h1>We're sorry</h1>
        <p>The course you are attempting to book is currently fully booked.</p>
    </div>
<?php } ?>

</div>

            </div>
        </div>
    </div>
</div>

<script src="pages/js/jquery.js"></script>
<script type="text/javascript"
        src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDjZSf7lI4D80NIwFMozDDABq-tSkGgKIs&sensor=false"></script>
<script>

    $(function () {

        geocoder = new google.maps.Geocoder();
        var latlng = new google.maps.LatLng(<?php echo $venueRec->goolat; ?>, <?php echo $venueRec->goolng; ?>);
        var myOptions = {
            zoom: 15,
            center: latlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            disableDefaultUI: true,
            scrollwheel: false
        }
        map = new google.maps.Map(document.getElementById("eventmap"), myOptions);

        var marker = new google.maps.Marker({
            position: new google.maps.LatLng(<?php echo $venueRec->goolat; ?>, <?php echo $venueRec->goolng; ?>),
            //icon: new google.maps.MarkerImage('pages/img/group-2.png',null, null, new google.maps.Point(10,50)),
            map: map,
            draggable: false
        });

    });

</script>

<script>
    $(function () {

        $('.readmorelink').click(function (e) {

            $(this).next().slideToggle();
            e.preventDefault();

        })

    })
</script>