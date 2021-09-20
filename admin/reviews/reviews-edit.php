<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("../reviews/classes/reviews.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpRev = new RevDAO();

$editReviewID = (isset($_GET['rev_id']) && is_numeric($_GET['rev_id'])) ? $_GET['rev_id'] : NULL;
$reviewRec = NULL;
if (!is_null($editReviewID)) $reviewRec = $TmpRev->select($editReviewID, NULL, NULL, true);

?>
<!doctype html>
<html>
<head>
<title>Review : <?php echo(isset($reviewRec)) ? $reviewRec->revttl : 'New Review'; ?></title>
<?php include('../webparts/headdata.php'); ?>
    
<script src="reviews/js/reviews-edit.js"></script>

</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-orange">
<div class="container-fluid" id="content">
	<?php include('../webparts/website-left.php'); ?>
	<div id="main">
		<div class="container-fluid">
			<div class="page-header">
				<div class="pull-left">
					<h1>Review : <?php echo(isset($reviewRec)) ? $reviewRec->revttl : 'New Review'; ?></h1>
				</div>
				<div class="pull-right">
					<?php include('../webparts/index-info.php'); ?>
				</div>
			</div>
			<div class="breadcrumbs">
				<ul>
					<li>
						<a href="index.php">Dashboard</a>
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a href="reviews/reviews.php">Reviews</a>
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a><?php echo(isset($reviewRec)) ? $reviewRec->revttl : 'New Review'; ?></a>
					</li>
				</ul>
			</div>
			<div class="row-fluid">
				<form action="reviews/reviews_script.php" id="reviewForm" class="form-horizontal form-bordered" data-returnurl="reviews/reviews.php" enctype="multipart/form-data">

					<div class="span12">
						<div class="box box-color box-bordered">
							<div class="box-title">
								<h3>
									<i class="icon-shopping-cart"></i> Review</h3>
								<div class="actions">
									<a href="#" id="updateReviewBtn" class="btn btn-mini" rel="tooltip" title="Update"><i class="icon-save"></i></a>
									<a href="#" id="deleteReviewBtn" class="btn btn-mini" rel="tooltip" title="Delete"><i class="icon-trash"></i></a>
								</div>
							</div>
							<div class="box-content nopadding">
								<input type="hidden" name="rev_id" id="id" value="<?php echo(isset($reviewRec)) ? $reviewRec->rev_id : '0'; ?>">
								<input type="hidden" name="tblnam" value="<?php echo(isset($reviewRec)) ? $reviewRec->tblnam : 'WEBSITE'; ?>">

                                <input type="hidden" name="refnam" value="<?php echo(isset($reviewRec)) ? $reviewRec->refnam : ''; ?>">
                                <input type="hidden" name="ref_id" value="<?php echo(isset($reviewRec)) ? $reviewRec->ref_id : '0'; ?>">

                                <div class="control-group">
                                    <label class="control-label">Table ID<small></small></label>
                                    <div class="controls">
                                        <input type="text" class="input-large" name="tbl_id" value="<?php echo(isset($reviewRec)) ? $reviewRec->tbl_id : '0'; ?>">
                                    </div>
                                </div>

                                <div class="control-group">
									<label class="control-label">Review Title<small>identifying name</small></label>
									<div class="controls">
										<input type="text" class="input-large" name="revttl" value="<?php echo(isset($reviewRec)) ? $reviewRec->revttl : ''; ?>">
									</div>
								</div>

                                <div class="control-group">
                                    <label class="control-label">Review Description<small>short description</small></label>
                                    <div class="controls">
                                        <textarea class="input-large" name="revdsc" rows="6"><?php echo (isset($reviewRec)) ? nl2br($reviewRec->revdsc) : ''; ?></textarea>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">Review Rating<small></small></label>
                                    <div class="controls">

                                        <select name="rating" class="input-large">
                                            <option value="1" <?php if (isset($reviewRec) && $reviewRec->rating == 1) echo 'selected'; ?>>1</option>
                                            <option value="2" <?php if (isset($reviewRec) && $reviewRec->rating == 2) echo 'selected'; ?>>2</option>
                                            <option value="3" <?php if (isset($reviewRec) && $reviewRec->rating == 3) echo 'selected'; ?>>3</option>
                                            <option value="4" <?php if (isset($reviewRec) && $reviewRec->rating == 4) echo 'selected'; ?>>4</option>
                                            <option value="5" <?php if (isset($reviewRec) && $reviewRec->rating == 5) echo 'selected'; ?>>5</option>
                                            <option value="6" <?php if (isset($reviewRec) && $reviewRec->rating == 6) echo 'selected'; ?>>6</option>
                                            <option value="7" <?php if (isset($reviewRec) && $reviewRec->rating == 7) echo 'selected'; ?>>7</option>
                                            <option value="8" <?php if (isset($reviewRec) && $reviewRec->rating == 8) echo 'selected'; ?>>8</option>
                                            <option value="9" <?php if (isset($reviewRec) && $reviewRec->rating == 9) echo 'selected'; ?>>9</option>
                                            <option value="10" <?php if (isset($reviewRec) && $reviewRec->rating == 10) echo 'selected'; ?>>10</option>
                                        </select>

                                    </div>
                                </div>

							</div>
						</div>
					</div>

				</form>
			</div>
			
			
			
		</div>
	</div>
</div>
</body>
</html>
