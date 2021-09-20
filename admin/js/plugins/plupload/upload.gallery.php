<?php
require_once("../config/patchworks.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: login.php');

$qryArray = array();
$sql = 'SELECT
		* 
		FROM uploads WHERE tblnam = :tblnam AND tbl_id = :tbl_id ORDER BY srtord ASC';

$qryArray['tblnam'] = (isset($_GET['tblnam'])) ? $_GET['tblnam'] : '';
$qryArray['tbl_id'] = (isset($_GET['tbl_id']) && is_numeric($_GET['tbl_id'])) ? $_GET['tbl_id'] : 0;

$uploads = $patchworks->run($sql, $qryArray);

?>

<?php
							$tableLength = count($uploads);
							for ($i=0;$i<$tableLength;++$i) {
							?>
													
							<li data-imageid="<?php echo $uploads[$i]['upl_id']; ?>">
								<div class="frame">
									<div class="img">
										<img src="<?php echo $patchworks->webRoot.'patchworks/uploader/uploads/169-130/'.$uploads[$i]['filnam']; ?>" alt="" style="opacity: 1; ">
									</div>
									<!-- /img -->
									
									<div class="actions" style="display: none; ">
										<a href="#" class="edit">
											<i class="icon-pencil"></i>
										</a>
										<a href="<?php echo $patchworks->webRoot.'patchworks/uploader/uploads/'.$uploads[$i]['filnam']; ?>" class="zoom ui-lightbox ui-lightbox">
											<i class="icon-search"></i>
										</a>
									</div>
									<!-- /actions -->
									<img src="./img/gallery/frame.png" alt="Frame">
								</div>
								<!-- /frame -->
							</li>
							
							<?php } ?>