<?php

require_once("../../config/config.php");
require_once("../../admin/patchworks.php");
require_once("../../admin/events/classes/bookings.cls.php");

function showMonth($month, $year, $bookedDates = NULL)
{
    //var_dump($bookedDates);
    $date = mktime(0, 0, 0, $month, 1, $year);
    $daysInMonth = date("t", $date);
    // calculate the position of the first day in the calendar (sunday = 1st column, etc)
    $offset = date("w", $date);
    $rows = 1;
    echo '<table>';
    echo '<tr><th><div class="calendarday">Sun</div></th><th><div class="calendarday">Mon</div></th><th><div class="calendarday">Tue</div></th><th><div class="calendarday">Wed</div></th><th><div class="calendarday">Thu</div></th><th><div class="calendarday">Fri</div></th><th><div class="calendarday">Sat</div></th></tr>';
    echo '<tr>';
    for($i = 1; $i <= $offset; $i++)
    {
        echo '<td></td>';
    }
    for($day = 1; $day <= $daysInMonth; $day++)
    {
        if( ($day + $offset - 1) % 7 == 0 && $day != 1)
        {
            echo '</tr><tr>';
            $rows++;
        }
        $dispDay = $day;
        $dispMon = $month;
        if ($month < 10) $dispMon = '0'.$month;
        if ($day < 10) $dispDay = '0'.$day;
        $checkDate = $year.'-'.$dispMon.'-'.$dispDay;
        //echo $checkDate;
        if ( in_array($checkDate, $bookedDates)) {
            echo '<td><div class="calendarday eventday"><a href="#" data-begdat="'.$checkDate.'" class="selectdate">' . $day . '</a></div></td>';
        } else {
            echo '<td><div class="calendarday"><a href="#" data-begdat="'.$checkDate.'" class="selectdate">' . $day . '</a></div></td>';
        }
    }
    while( ($day + $offset) <= $rows * 7)
    {
        echo '<td></td>';
        $day++;
    }
    echo '</tr>';
    echo '</table>';
}

$calendarMonth = (isset($_GET['m']) && is_numeric($_GET['m'])) ? $_GET['m'] : date("m");
$calendarYear  = (isset($_GET['y']) && is_numeric($_GET['y'])) ? $_GET['y'] : date("Y");

$calendarMonth = ltrim($calendarMonth, '0');

// get bookings
$TmpBoo = new BooDAO();
$availEvents = $TmpBoo->selectMonthBookings($calendarMonth,$calendarYear, 'EVENT', NULL);
$bookedDates = array();
for ($e=0;$e<count($availEvents);$e++) {
    $date = date("Y-m-d",strtotime($availEvents[$e]['begdat']));
    array_push($bookedDates,$date);
}

$prevMonth = 1;
$prevYear  = date("Y");
$nextMonth = 2;
$nextYear  = date("Y");

if ($calendarMonth == 1) {
    $prevMonth = 12;
    $prevYear  = $calendarYear-1;
} else if ($calendarMonth == 12) {
    $nextMonth = 1;
    $nextYear  = $calendarYear+1;
} else {
    $prevMonth = $calendarMonth-1;
    $nextMonth = $calendarMonth+1;
}

?>
<div class="monthselect">
    <div class="monthname">
        <?php echo date("F", strtotime( $calendarYear.'-'.$calendarMonth.'-01' )); ?>
    </div>

    <a href="#" class="button prev" id="calendarMonthPrev" data-month="<?php echo $prevMonth; ?>" data-year="<?php echo $prevYear; ?>"><i class="fa fa-chevron-left"></i></a>

    <a href="#" class="button next" id="calendarMonthNext" data-month="<?php echo $nextMonth; ?>" data-year="<?php echo $nextYear; ?>"><i class="fa fa-chevron-right"></i></a>
</div>
<?php
showMonth($calendarMonth, $calendarYear, $bookedDates);
?>