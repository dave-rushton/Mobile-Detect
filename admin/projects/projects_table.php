<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("../system/classes/places.cls.php");
require_once("../system/classes/people.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpPla = new PlaDAO();
$custID = (isset($_GET['cus_id']) && is_numeric($_GET['cus_id'])) ? $_GET['cus_id'] : NULL;
$editStatusID = (isset($_GET['sta_id']) && is_numeric($_GET['sta_id'])) ? $_GET['sta_id'] : NULL;
$editPlaceID = (isset($_GET['pla_id']) && is_numeric($_GET['pla_id'])) ? $_GET['pla_id'] : NULL;
$editTableID = (isset($_GET['tbl_id']) && is_numeric($_GET['tbl_id'])) ? $_GET['tbl_id'] : NULL;
$editTableNm = (isset($_GET['tblnam'])) ? $_GET['tblnam'] : NULL;
$projects = NULL;

$placeRec = $TmpPla->select($custID, NULL, NULL, NULL, NULL, true); 
$projects = $TmpPla->selectPlaceStats($editPlaceID, $editTableNm, $editTableID, NULL, $editStatusID, false, NULL); 

$tableLength = count($projects);
for ($i=0;$i<$tableLength;++$i) {
	$className = ($projects[$i]['sta_id'] == 1) ? 'error' : '';

    $completion = 0;
    if (is_numeric($projects[$i]['rooms']) && is_numeric($projects[$i]['tothrs']) && $projects[$i]['rooms'] > 0) {
        $completion = number_format(($projects[$i]['tothrs'] / $projects[$i]['rooms']) * 100, 2);
    }

?>
<tr class="<?php echo $className; ?>">
	
	<td>
	<?php if (strlen($projects[$i]['plaurl']) > 0 ) { ?>
	<a href="<?php echo $projects[$i]['plaurl']; ?>" class="btn btn-mini btn-success" style="background: <?php echo $projects[$i]['placol']; ?>" target="_blank" rel="tooltip" title="Launch URL"><i class="icon-external-link"></i></a>
	<?php } ?>
	</td>
	<td><a href="projects/project-edit.php?cus_id=<?php echo $projects[$i]['tbl_id']; ?>&pla_id=<?php echo $projects[$i]['pla_id'] ?>"><?php echo $projects[$i]['planam'] ?></a></td>
	<td><a href="projects/customers-edit.php?pla_id=<?php echo $projects[$i]['tbl_id'] ?>"><?php echo $projects[$i]['clinam'] ?></a></td>
	<td style="text-align: right;"><?php echo number_format($projects[$i]['rooms'],2) ?>hrs</td>
	<td style="text-align: right;"><?php echo number_format($projects[$i]['tothrs'],2) ?>hrs</td>
	<td style="text-align: right;"><?php echo number_format($projects[$i]['plnhrs'],2) ?>hrs</td>
	<td style="text-align: right;">
	<?php 
	//echo date("jS M Y", strtotime($projects[$i]['amndat'])); 
	if (!empty($projects[$i]['amndat']) && $projects[$i]['sta_id'] == 0) {
		$now = time();
		$your_date = strtotime($projects[$i]['amndat']);
		$datediff = $your_date - $now;
		$datediff = ceil($datediff/(60*60*24))-1;

        $advTim = 0;

		if ($datediff > 0) {

            $advTim = number_format((number_format($projects[$i]['rooms'], 2) - number_format($projects[$i]['tothrs'], 2)) / $datediff, 2);

			echo ' <small class="pull-right label label-info">due in '.$datediff.' day(s) @ '.$advTim.' per day</small>';
		} else {
			echo ' <small class="pull-right label label-important">'.$datediff.' day(s) overdue</small>';
		}
		
	}
	?>
	</td>
	<td width="100">
		<?php if ($completion == 0) { ?>
			
		<?php } else if ($completion > 100) { ?>
			<span class="badge badge-important"><?php echo $completion-100; ?>%</span>
		<?php } else { ?>
		<div class="pagestats bar">
			<span style="position: absolute; margin-top: 5px;"><?php echo $completion; ?>% <?php if ($projects[$i]['sta_id'] == 1 && $completion < 100) echo '<i class="icon-thumbs-up"></i>'; ?></span>
			<div class="progress small">
				<div class="bar" style="width:<?php echo $completion; ?>%"></div>
			</div>
		</div>
		<?php } ?>
	</td>
	<td>
		<input type="checkbox" class="proactive" value="<?php echo $projects[$i]['pla_id'] ?>" <?php if ($projects[$i]['sta_id'] == 0) echo 'checked'; ?> />
	</td>
</tr>
<?php } ?>