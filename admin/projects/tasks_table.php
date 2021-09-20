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
	<td><input type="checkbox"></td>
	<td><span class="booDur"><?php echo $tasks[$i]['btkdur']; ?></span></td>
	<td>
		<a href="#" class="selectTaskLnk" data-btk_id="<?php echo $tasks[$i]['btk_id']; ?>"><?php echo $tasks[$i]['btkttl']; ?></a>
		<br>
		<?php echo $tasks[$i]['btkdsc']; ?>
	</td>
	<td><a href="projects/project-edit.php?pla_id=<?php echo $tasks[$i]['tbl_id']; ?>"><?php echo $tasks[$i]['planam']; ?></a></td>
	<td><?php echo $tasks[$i]['pplnam']; ?></td>
	<td><a href="#" class="btn btn-mini btn-danger deleteTaskBtn" data-btk_id="<?php echo $tasks[$i]['btk_id']; ?>"><i class="icon-trash"></i></a></td>
</tr>

<?php } ?>
