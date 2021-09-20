<?php 

require_once('../../../config/config.php');
require_once('../../patchworks.php'); 
require_once("../../system/classes/places.cls.php");
require_once("../../projects/classes/bookings.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpPla = new PlaDAO();
$editPlaceID = (isset($_GET['cus_id']) && is_numeric($_GET['cus_id'])) ? $_GET['cus_id'] : NULL;

// Projects
if (!is_null($editPlaceID)) $projects = $TmpPla->select(NULL, 'PROJECT', $editPlaceID, NULL, 0, false); 

//$editProjectID = (isset($_GET['pla_id']) && is_numeric($_GET['pla_id'])) ? $_GET['pla_id'] : NULL;

$BegDat = (isset($_GET['begdat'])) ? $_GET['begdat'] : '2014-06-01';
$EndDat = (isset($_GET['enddat'])) ? $_GET['enddat'].' 23:59:59' : '2014-06-30';
$CusPro = (isset($_GET['cuspro'])) ? $_GET['cuspro'] : NULL;

?>

<?php
function number_of_working_days($from, $to) {
    $workingDays = [1, 2, 3, 4, 5]; # date format = N (1 = Monday, ...)
    //$holidayDays = ['*-12-25', '*-01-01', '2013-12-23']; # variable and fixed holidays
    $holidayDays = [];

    $from = new DateTime($from);
    $to = new DateTime($to);
    //$to->modify('+1 day');
    $interval = new DateInterval('P1D');
    $periods = new DatePeriod($from, $interval, $to);

    $days = 0;
    foreach ($periods as $period) {
        if (!in_array($period->format('N'), $workingDays)) continue;
        if (in_array($period->format('Y-m-d'), $holidayDays)) continue;
        if (in_array($period->format('*-m-d'), $holidayDays)) continue;
        $days++;
    }
    return $days;
}

?>


<html>
<head>

</head>

<body>

<table width="100%" class="table table-bordered table-striped table-highlight">

    <thead>
        <tr>
            <th>OT</th>
            <th>Customer</th>
            <th>Project</th>
            <th>Notes</th>
            <th>Date</th>
            <th>Start</th>
            <th>End</th>
            <th style="text-align: right;">Total</th>
        </tr>
    </thead>

	<?php
//	$tableLength = count($projects);
//	echo $tableLength;
//	for ($i=0;$i<$tableLength;++$i) {
		
		$TmpBoo = new BooDAO();
		
		$bookings = $TmpBoo->select(NULL, $BegDat, $EndDat, 'PROJECT', NULL, NULL, NULL, NULL, false, NULL, NULL, 'b.begdat ASC');
		
		$bookinglength = count($bookings);
		$totHrs = 0;
		for ($b=0;$b<$bookinglength;++$b) {
			
			if ( is_numeric($_GET['cus_id']) && $_GET['cus_id'] != $bookings[$b]['cus_id'] ) continue
			
		?>
		
		<tr>
			<td style="text-align: center;"><?php echo ( date("H", strtotime($bookings[$b]['begdat'])) <= 8 || date("H", strtotime($bookings[$b]['begdat'])) >= 18 || (date("w", strtotime($bookings[$b]['begdat'])) == 0 || date("w", strtotime($bookings[$b]['begdat'])) == 6) ) ? '*' : ''; ?></td>
			<td><?php echo $bookings[$b]['cusnam']; ?></td>
			<td><?php echo $bookings[$b]['planam']; ?></td>
			<td><?php echo $bookings[$b]['boodsc']; ?></td>
			<td><?php echo date("D jS M", strtotime($bookings[$b]['begdat'])); ?></td>
			<td><?php echo date("H:i", strtotime($bookings[$b]['begdat'])); ?></td>
			<td><?php echo date("H:i", strtotime($bookings[$b]['enddat'])); ?></td>
			<td style="text-align: right;">
                <?php

                $decTime = number_format($bookings[$b]['tothrs'],2);
                $hour = floor($decTime);
                $min = round(60*($decTime-$hour));

                echo $hour.' hrs '.$min.' mins';
                ?>
            </td>
		</tr>		
		
		<?php
		$totHrs = $totHrs + number_format($bookings[$b]['tothrs'],2);
		}
//		
//	}
	?>

    <tr>
        <td colspan="7" style="text-align: right"><strong>EXPECTED HOURS:</strong></td>
        <td style="text-align: right;">
            <?php
            $expectedHours = (number_of_working_days($BegDat, $EndDat) * 7.5);

            $decTime = number_format($expectedHours,2);
            $hour = floor($decTime);
            $min = round(60*($decTime-$hour));

            echo $hour.' hrs '.$min.' mins';

            ?>
        </td>
    </tr>
    <tr>
        <td colspan="7" style="text-align: right"><strong>TOTAL:</strong></td>
        <td style="text-align: right;">
            <?php

            $decTime = number_format($totHrs,2);
            $hour = floor($decTime);
            $min = round(60*($decTime-$hour));

            echo $hour.' hrs '.$min.' mins';
            ?>
        </td>
    </tr>

	
</table>

</body>
</html>