<?php

$uploads = $TmpUpl->select(NULL, 'EVENT', $eventRec->pla_id, NULL, false);
$imgUrl = 'pages/img/noimg.png';
if (isset($uploads[0]['filnam'])) $imgUrl = 'uploads/images/'.$uploads[0]['filnam'];

$upcomingEvents = $TmpBoo->select(NULL, date("Y-m-d"), NULL, 'EVENT', NULL, NULL, NULL, 0, false, 3, NULL, 'begdat' );

?>
<div class="row">
    <div class="col-sm-12">

        <div class="imageheader" style="background-image: url('<?php echo $imgUrl; ?>')">
            <h1><?php echo $eventRec->planam.' @ '.$venueRec->planam; ?></h1>
        </div>

    </div>
</div>

<div class="row">
    <div class="col-lg-8 col-lg-push-4">

        <div class="textblock">

            <h3><?php echo date("d F", strtotime($bookingRec->begdat)).' - '.$eventRec->planam.','.$venueRec->planam; ?></h3>

            <?php echo $eventRec->platxt; ?>

        </div>


        <form id="eventBookingForm">

            <div class="title">BOOK EVENT</div>

            <input type="text" name="name" placeholder="Your Name">
            <input type="email" name="email" placeholder="Your Email">
            <input type="number" name="place" placeholder="Required Places (e.g 2)">
            <button>SUBMIT</button>

        </form>


    </div>
    <div class="col-xs-4 col-lg-pull-8">

        <div class="eventlist">

            <a href="<?php echo $_GET['seourl']; ?>" class="backbutton">BACK TO EVENTS</a>

            <ul>
                <?php
                for ($i=0;$i<count($upcomingEvents);$i++) {

                    $uploads = $TmpUpl->select(NULL, 'EVENT', $upcomingEvents[$i]['tbl_id'], NULL, false);

                    $imgUrl = 'pages/img/noimg.png';
                    if (isset($uploads[0]['filnam'])) $imgUrl = 'uploads/images/' . $uploads[0]['filnam'];

                    ?>

                    <li>
                        <h3><?php echo date("d F", strtotime($upcomingEvents[$i]['begdat'])); ?> - <?php echo $upcomingEvents[$i]['planam']; ?></h3>

                        <div class="eventdetail">
                        <?php echo $upcomingEvents[$i]['boodsc']; ?>
                        </div>

                        <a href="<?php echo $_GET['seourl'].'/event/'.$upcomingEvents[$i]['boo_id'].'/'.$upcomingEvents[$i]['seourl']; ?>" class="findoutmore">FIND OUT MORE <i class="fa fa-chevron-right"></i></a>
                    </li>

                    <?php
                }
                ?>

            </ul>
        </div>

    </div>
</div>