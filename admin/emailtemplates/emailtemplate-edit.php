<?php

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("classes/emailtemplate.cls.php");
require_once("classes/emailsections.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpEmt = new EmtDAO();
$Emt_ID = (isset($_GET['emt_id'])) ? $_GET['emt_id'] : NULL;
if (is_numeric($Emt_ID)) $emailTemplateRec = $TmpEmt->select($Emt_ID, NULL, NULL, NULL, true);

$TmpEms = new EmsDAO();
if (is_numeric($Emt_ID)) $emailSectionRec = $TmpEms->select($Emt_ID, NULL, false);

//$tmpEmsClass = new stdClass();
//$tmpEmsClass->ems_id = 1;
//$tmpEmsClass->emt_id = 1;
//$tmpEmsClass->emstyp = "text";
//$tmpEmsClass->emsfil = "includes/text.php";
//$tmpEmsClass->emsobj = "test";
//$tmpEmsClass->srtord = 99;
//$tmpEmsClass->sta_id = 1;
//Class is operational

?>
<!doctype html>
<html>
<head>
<title>Email Template</title>

    <?php include('../webparts/headdata.php'); ?>

    <script src="emailtemplates/js/emailtemplate-edit.js"></script>

</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
    <?php include('../webparts/website-left.php'); ?>

	<div id="main">
		<div class="container-fluid">
			<div class="page-header">
				<div class="pull-left">
					<h1>Email Templates</h1>
				</div>
				<div class="pull-right">
				</div>
			</div>
			<div class="breadcrumbs">
				<ul>
					<li>
						<a href="index.php">Dashboard</a>
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a>Website</a>
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a href="emailtemplates/emailtemplate.php">Email Templates</a>
                        <i class="icon-angle-right"></i>
					</li>
                    <li>
                        <a>Email Template</a>
                    </li>
				</ul>
			</div>
			<div class="row-fluid">
				<div class="span4">
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-envelope"></i> Email Templates</h3>
							<div class="actions">

							</div>
						</div>
						<div class="box-content nopadding">
							<form class="form-horizontal form-validate form-bordered" method="POST" action="emailtemplates/emailtemplate_script.php" id="emailTemplateForm" data-returnurl="emailtemplates/emailtemplate.php">
								<input type="hidden" name="emt_id" id="id" value="<?php echo (isset($emailTemplateRec->emt_id)) ? $emailTemplateRec->emt_id : '0';?>" />

								<div class="control-group">
									<label class="control-label">Template Name<small>identify template name</small></label>
									<div class="controls">
										<input type="text" name="emtnam" data-rule-required="true" data-rule-minlength="2" value="<?php echo (isset($emailTemplateRec->emtnam)) ? $emailTemplateRec->emtnam : '';?>">
									</div>
								</div>

								<div class="control-group">
									<label class="control-label">Status<small>email template status</small></label>
									<div class="controls">
										<label class="radio">
											<input type="radio" name="sta_id" value="0" <?php echo(isset($emailTemplateRec) && $emailTemplateRec->sta_id == 0) ? 'checked' : ''; ?>>
											Active</label>
										<label class="radio">
											<input type="radio" name="sta_id" value="1" <?php echo(!isset($emailTemplateRec) || $emailTemplateRec && $emailTemplateRec->sta_id == 1) ? 'checked' : ''; ?>>
											In-Active </label>
									</div>
								</div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary">Update <i class="icon icon-save"></i></button>
                                </div>

							</form>
						</div>
					</div>
				</div>
				<div class="span8">
                    <div class="box box-color box-bordered" id="sectionTableBox">
                        <div class="box-title">
                            <h3>
                                <i class="icon-envelope"></i> Email Design</h3>
                            <div class="actions">
								<a href="#" id="createSectionBtn" class="btn btn-mini" <?php echo (!isset($emailTemplateRec)) ? 'style="display: none;"' : ''; ?> rel="tooltip" title="New Section"><i class="icon-plus"></i></a>
							</div>
                        </div>
                        <div class="box-content nopadding">
							<table class="table table-bordered table-striped table-highlight" id="sectionTable">
								<thead>
								<tr>
									<th width="20"></th>
									<th>Section</th>
									<th width="20"></th>
								</tr>
								</thead>
								<tbody id="sectionTableBody">
								</tbody>
							</table>
                        </div>
                    </div>
					<div class="box box-color box-bordered satgreen" id="sectionBox" style="display: none;">
						<div class="box-title">
							<h3>
								<i class="icon-check"></i> Section</h3>
							<div class="actions">
								<a href="#" id="cancelSectionBtn" class="btn btn-mini" rel="tooltip" title="Cancel"><i class="icon-remove"></i></a>
								<a href="#" id="updateSectionBtn" class="btn btn-mini" rel="tooltip" title="Update"><i class="icon-save"></i></a>
							</div>
						</div>
						<div class="box-content nopadding">
							<form class="form-horizontal form-validate form-bordered" action="<?php echo $patchworks->pwRoot; ?>emailtemplates/emailsection_script.php" id="emailSectionForm">
								<input type="hidden" name="emt_id" value="<?php echo $emailTemplateRec->emt_id ?>">
								<input type="hidden" name="ems_id">
								<div class="control-group">
									<label class="control-label">Section Type<small>select the desired section type</small></label>
									<div class="controls">
										<select name="emstyp">
											<option value="text">Text</option>
											<option value="news">News</option>
											<option value="gallery">Gallery</option>
											<option value="products">Products</option>
										</select>
									</div>
								</div>

								<div class="control-group">
									<label class="control-label">Status<small>email section status</small></label>
									<div class="controls">
										<label class="radio">
											<input type="radio" name="sta_id" value="1">
											Active</label>
										<label class="radio">
											<input type="radio" name="sta_id" value="0">
											In-Active </label>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
