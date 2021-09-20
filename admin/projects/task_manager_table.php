<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("../system/classes/places.cls.php");
require_once("../system/classes/people.cls.php");
require_once("../projects/classes/tasks.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$Tbl_ID = (isset($_GET['tbl_id']) && is_numeric($_GET['tbl_id'])) ? $_GET['tbl_id'] : NULL;
$TblNam = (isset($_GET['tblnam']) && !empty($_GET['tblnam'])) ? $_GET['tblnam'] : NULL;
$Ref_ID = (isset($_GET['ref_id']) && is_numeric($_GET['ref_id'])) ? $_GET['ref_id'] : NULL;
$RefNam = (isset($_GET['refnam']) && !empty($_GET['refnam'])) ? $_GET['refnam'] : NULL;

$Sta_ID = (isset($_GET['sta_id']) && is_numeric($_GET['sta_id'])) ? $_GET['sta_id'] : NULL;


$TmpBtk = new BtkDAO();
$tasks = $TmpBtk->select(NULL, $TblNam, $Tbl_ID, NULL, false, $Sta_ID);

$tableLength = count($tasks);
for ($i=0;$i<$tableLength;++$i) {

?>

<tr>
	<td class="table-checkbox hidden-480">
		<input type="checkbox" class="selectable selBtk"  value="<?php echo $tasks[$i]['btk_id']; ?>">
	</td>
	<td class="table-icon hidden-480">
		<a href="#" class="sel-star <?php if ( $tasks[$i]['impflg'] == 1 ) echo 'active'; ?>" data-btk_id="<?php echo $tasks[$i]['btk_id']; ?>"><i class="icon-star"></i></a>
	</td>
	<td>
		<?php echo $tasks[$i]['pplnam']; ?><br />
		<small><?php echo $tasks[$i]['planam']; ?></small>
	</td>
	<td class="table-fixed-medium">
		<strong><?php echo $tasks[$i]['btkttl']; ?></strong><br />
		<?php echo $tasks[$i]['btkdsc']; ?>
	</td>
	<td class="hidden-480">
		<?php echo $tasks[$i]['btkdur']; ?> hrs
		
		<?php
		$datediff = '';
		if (!is_null($tasks[$i]['duedat']) && $tasks[$i]['duedat'] != '') {
			$now = time();
			$your_date = strtotime($tasks[$i]['duedat']);
			$datediff = $your_date - $now;
			$datediff = ceil($datediff/(60*60*24));
			echo '<br /><small>due in '.$datediff.' day(s)</small>';
		}
		?>
		
	</td>
</tr>


<?php } ?>
