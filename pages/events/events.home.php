<?php

$upcomingEvents = $TmpBoo->select(NULL, date("Y-m-d"), NULL, 'EVENT', NULL, NULL, NULL, 0, false, 6, NULL, 'begdat' );

?>

<div class="row">
    <div class="col-sm-12">
        <div class="eventdatepicker">
            <div class="monthcalendar">

                <div id="calendarWrapper">

                </div>

            </div>
            <div class="eventdetails">
                <h3>Charity Event One - 10 January 2018 at 6pm</h3>
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. orem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident.
                </p>
                <div class="button prev"><i class="fa fa-chevron-left"></i> Previous Event</div>
                <div class="button next">Next Event <i class="fa fa-chevron-right"></i></div>
            </div>
        </div>
    </div>
</div>
<div class="row">

    <?php
    for ($i=0;$i<count($upcomingEvents);$i++) {

        $uploads = $TmpUpl->select(NULL, 'EVENT', $upcomingEvents[$i]['tbl_id'], NULL, false);

        $imgUrl = 'pages/img/noimg.png';
        if (isset($uploads[0]['filnam'])) $imgUrl = 'uploads/images/'.$uploads[0]['filnam'];

        ?>

        <div class="col-sm-4">
            <div class="eventlink">
                <a href="<?php echo $_GET['seourl'].'/event/'.$upcomingEvents[$i]['boo_id'].'/'.$upcomingEvents[$i]['seourl']; ?>" class="image" style="background-image: url('<?php echo $imgUrl; ?>')"></a>
                <div class="content">
                    <h3>
                        <?php echo date("d F", strtotime($upcomingEvents[$i]['begdat'])); ?> - <?php echo $upcomingEvents[$i]['planam']; ?>
                    </h3>

                    <div class="eventdetails">
                        <?php echo $upcomingEvents[$i]['boodsc']; ?>
                    </div>

                </div>
                <a href="<?php echo $_GET['seourl'].'/event/'.$upcomingEvents[$i]['boo_id'].'/'.$upcomingEvents[$i]['seourl']; ?>" class="findoutmore">FIND OUT MORE</a>
            </div>
        </div>

        <?php
    }
    ?>

</div>