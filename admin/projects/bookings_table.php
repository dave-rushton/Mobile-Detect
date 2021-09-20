<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("../system/classes/places.cls.php");
require_once("../system/classes/people.cls.php");
require_once("../projects/classes/bookings.cls.php");
require_once("../products/classes/products.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$BegDat = (isset($_GET['begdat']) ) ? $_GET['begdat'] : NULL;
$EndDat = (isset($_GET['enddat']) ) ? $_GET['enddat'] : NULL;

$Tbl_ID = (isset($_GET['tbl_id']) && is_numeric($_GET['tbl_id'])) ? $_GET['tbl_id'] : NULL;
$TblNam = (isset($_GET['tblnam']) && !empty($_GET['tblnam'])) ? $_GET['tblnam'] : NULL;
$Ref_ID = (isset($_GET['ref_id']) && is_numeric($_GET['ref_id'])) ? $_GET['ref_id'] : NULL;
$RefNam = (isset($_GET['refnam']) && !empty($_GET['refnam'])) ? $_GET['refnam'] : NULL;

$Sta_ID = (isset($_GET['sta_id']) && is_numeric($_GET['sta_id'])) ? $_GET['sta_id'] : NULL;

$TmpBoo = new BooDAO();
$bookings = $TmpBoo->select(NULL, $BegDat, $EndDat, $TblNam, $Tbl_ID, $RefNam, $Ref_ID, $Sta_ID, false);


$tableLength = count($bookings);
for ($i=0;$i<$tableLength;++$i) {

$hourdiff = round((strtotime($bookings[$i]['enddat']) - strtotime($bookings[$i]['begdat']))/3600, 2);

?>
<tr>
	<td style="background-color: <?php echo $bookings[$i]['boocol']; ?>;"><input type="checkbox" class="selectBookingCB" value="<?php echo $bookings[$i]['boo_id']; ?>"></td>
	<td><a href="#" class="selectBookingLnk" data-boo_id="<?php echo $bookings[$i]['boo_id']; ?>"><?php echo date("D jS M Y", strtotime($bookings[$i]['begdat'])); ?></a></td>
	<td><?php echo date("Ymd", strtotime($bookings[$i]['begdat'])); ?></td>
	<td><?php echo date("H:i", strtotime($bookings[$i]['begdat'])).' - '.date("H:i", strtotime($bookings[$i]['enddat'])); ?></td>
	<td><span class="booDur"><?php /*echo '#'.$bookings[$i]['caltim'].' ';*/ echo $hourdiff; //.' '.date("Y-m-d H:i", strtotime($bookings[$i]['enddat'])); ?></span></td>
	<td><?php echo $bookings[$i]['planam']; ?></td>
	<td><?php echo $bookings[$i]['pplnam']; ?></td>
	<td><?php echo $bookings[$i]['stanam']; ?></td>
	<td><a href="#" class="btn btn-mini btn-danger deleteBookingBtn" data-boo_id="<?php echo $bookings[$i]['boo_id']; ?>"><i class="icon-trash"></i></a></td>
</tr>

<?php } ?>
