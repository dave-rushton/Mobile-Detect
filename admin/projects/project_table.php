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
$editTableNm = 'PROJECT';
$projects = NULL;

$placeRec = $TmpPla->select($custID, NULL, NULL, NULL, NULL, true); 
$projects = $TmpPla->select($editPlaceID, $editTableNm, $editTableID, NULL, $editStatusID, false); 

$tableLength = count($projects);
for ($i=0;$i<$tableLength;++$i) {
	$className = ($projects[$i]['sta_id'] == 1) ? 'error' : '';
?>
<tr class="<?php echo $className; ?>">
	<td><a href="projects/project-edit.php?cus_id=<?php echo $projects[$i]['tbl_id']; ?>&pla_id=<?php echo $projects[$i]['pla_id'] ?>"><?php echo $projects[$i]['planam'] ?></a></td>
</tr>
<?php } ?>