<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("../system/classes/statuscodes.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpSta = new StaDAO();
$editTableNm = (isset($_GET['tblnam'])) ? $_GET['tblnam'] : NULL;
$statusCodes = NULL;

$statusCodes = $TmpSta->select(NULL, $editTableNm, false);

$tableLength = count($statusCodes);
for ($i=0;$i<$tableLength;++$i) {
?>
<tr class="<?php echo $className; ?>">
	<td><a href="#"><?php echo $statusCodes[$i]['stanam'] ?></a></td>
</tr>
<?php } ?>