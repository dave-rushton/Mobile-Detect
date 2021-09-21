<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("../attributes/classes/attrgroups.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpAtr = new AtrDAO();

$editAttrGroup = (isset($_GET['atr_id']) && is_numeric($_GET['atr_id'])) ? $_GET['atr_id'] : NULL;
$attrGroupRec = NULL;
$attrLabelRec = NULL;

if (!is_null($editAttrGroup)) {
	$attrGroupRec = $TmpAtr->select($editAttrGroup, NULL, NULL, NULL, true); 
}

?>
<!doctype html>
<html>
<head>
<title>Custom Form</title>
<?php include('../webparts/headdata.php'); ?>
<script src="custom/js/forms-edit.js"></script>
</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
	<?php include('../webparts/website-left.php'); ?>
	<div id="main">
		<div class="container-fluid">
			<div class="page-header">
				<div class="pull-left">
					<h1>Website Forms</h1>
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
						<a href="custom/forms.php">Custom Forms</a>
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a>Custom Form</a>
					</li>
				</ul>
			</div>
			<div class="row-fluid">
				<div class="span6">
					<div class="box box-color box-bordered" id="attrGroupBox">
						<div class="box-title">
							<h3>
								<i class="icon-comments"></i> Custom Forms</h3>
							<div class="actions">
								<a href="#" id="updateAttrGroupBtn" class="btn btn-mini" rel="tooltip" title="Save"><i class="icon-save"></i></a>
							</div>
						</div>
						<div class="box-content nopadding">
							<form class="form-horizontal form-validate form-bordered" method="POST" action="attributes/attrgroup_script.php" id="attrGroupForm" data-returnurl="website/forms.php">
								<input type="hidden" name="atr_id" id="id" value="<?php echo($attrGroupRec) ? $attrGroupRec->atr_id : 0; ?>" />
								<div class="control-group hide">
									<label class="control-label">Table Name</label>
									<div class="controls">
										<input type="hidden" name="tblnam" value="CUSTOM">
									</div>
								</div>
								<div class="control-group hide">
									<label class="control-label">Table ID</label>
									<div class="controls">
										<input type="hidden" name="tbl_id" value="<?php echo($attrGroupRec) ? $attrGroupRec->tbl_id : 0; ?>">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Form Name<small>identify form name</small></label>
									<div class="controls">
										<input type="text" name="atrnam" data-rule-required="true" data-rule-minlength="2" value="<?php echo($attrGroupRec) ? $attrGroupRec->atrnam : ''; ?>">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Description<small>short description of form</small></label>
									<div class="controls">
										<textarea name="atrdsc"><?php echo($attrGroupRec) ? $attrGroupRec->atrdsc : ''; ?></textarea>
									</div>
								</div>
								<div class="control-group hide">
									<label class="control-label">Email Address<small>acknowledgement email address of form submission</small></label>
									<div class="controls">

                                        <!-- data-rule-email="true" -->

										<input type="text" name="atrema" value="<?php echo($attrGroupRec) ? $attrGroupRec->atrema : ''; ?>" class="input-block-level">
									</div>
								</div>
								<div class="control-group hide">
									<label class="control-label">Forward URL<small>website URL address on submission</small></label>
									<div class="controls">
										<input type="text" name="fwdurl" data-rule-minlength="2" value="<?php echo($attrGroupRec) ? $attrGroupRec->fwdurl : ''; ?>">
									</div>
								</div>
								<div class="control-group hide">
									<label class="control-label">Button Text<small>button label displayed to users</small></label>
									<div class="controls">
										<input type="text" name="btntxt" value="<?php echo($attrGroupRec) ? $attrGroupRec->btntxt : ''; ?>">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Status<small>form status</small></label>
									<div class="controls">
										<label class="radio">
											<input type="radio" name="sta_id" value="0" <?php echo($attrGroupRec && $attrGroupRec->sta_id == 0) ? 'checked' : ''; ?>>
											Active</label>
										<label class="radio">
											<input type="radio" name="sta_id" value="1" <?php echo(!$attrGroupRec || $attrGroupRec && $attrGroupRec->sta_id == 1) ? 'checked' : ''; ?>>
											In-Active </label>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="span6">
					<div class="box box-color box-bordered" id="attrLabelTableBox">
						<div class="box-title">
							<h3>
								<i class="icon-tasks"></i> Form Fields</h3>
							<div class="actions">
								<a href="#" id="createAttrLabelBtn" class="btn btn-mini" <?php echo (!$attrGroupRec) ? 'style="display: none;"' : ''; ?> rel="tooltip" title="New Article"><i class="icon-plus"></i></a>
							</div>
						</div>
						<div class="box-content nopadding">
							<table class="table table-bordered table-striped table-highlight" id="attrLabelTable">
								<thead>
									<tr>
										
										<th width="20"></th>
										<th>Label</th>
										<th>Type</th>
										<th width="20"></th>
									</tr>
								</thead>
								<tbody id="attrLabelBody">
									
								</tbody>
							</table>
						</div>
					</div>
					<div class="box box-color box-bordered satgreen" id="attrLabelBox" style="display: none;">
						<div class="box-title">
							<h3>
								<i class="icon-check"></i> Form Field</h3>
							<div class="actions">
								<a href="#" class="btn btn-mini" id="cancelLabelBtn" rel="tooltip" title="Cancel"><i class="icon-remove"></i></a>
								<a href="#" id="updateAttrLabelBtn" class="btn btn-mini" rel="tooltip" title="Update"><i class="icon-save"></i></a>
							</div>
						</div>
						<div class="box-content nopadding">
							<form class="form-horizontal form-validate form-bordered" action="<?php echo $patchworks->pwRoot; ?>attributes/attrlabel_script.php" id="attrLabelForm">
								<input type="hidden" name="atr_id" id="atrId" value="<?php echo($attrGroupRec) ? $attrGroupRec->atr_id : 0; ?>" />
								<input type="hidden" name="atl_id" id="id" value="0" />
								<input type="hidden" name="srtord" value="" />
								<input type="hidden" name="atllst" value="" />
								<div class="control-group">
									<label class="control-label">Field Label<small>field displayed to user</small></label>
									<div class="controls">
										<input type="text" name="atllbl" data-rule-required="true" data-rule-minlength="2" value="">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Input Type<small>select the desired input type</small></label>
									<div class="controls">
										<select name="atltyp">
											<option value="text">Text</option>
											<option value="textarea">Description</option>
											<option value="checkbox">Checkbox</option>
											<!--<optgroup label="Lists">-->
											<option value="select">Select List</option>
											<option value="date">Date</option>
											<option value="upload">Upload</option>
											<!--<option value="radio">Radio List</option>-->
											<!--</optgroup>-->
											<!--<optgroup label="Special">
											<option value="date">Date</option>
											<option value="WYSIWYG">WYSIWYG</option>
											</optgroup>-->
										</select>
									</div>
								</div>
								<div id="AtlLstDiv">
									<div class="control-group">
										<label class="control-label">Add Entry<small>enter an option and click the add button</small></label>
										<div class="controls">
											<div class="input-append">
												<input type="text" id="AddAltLst" class="input-medium" />
												<button class="btn" type="button" id="addAltLst" rel="tooltip" title="Add Entry To List"><i class="icon icon-plus"></i></button>
											</div>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Entry List</label>
										<div class="controls">
											<ul id="AtrLst_UL" style="padding: 0; margin: 0;">
											</ul>
										</div>
									</div>
								</div>
								<textarea id="AtlLst" name="atllst" class="hide"></textarea>
								<div class="control-group">
									<label class="control-label">Required?<small>check to make form field mandatory</small></label>
									<div class="controls">
										<label class="checkbox">
											<input type="checkbox" name="atlreq" value="1">
											Required </label>
									</div>
								</div>
								<div class="control-group hide">
									<label class="control-label">Show on website</label>
									<div class="controls">
										<label class="checkbox">
											<input type="checkbox" name="srcabl" value="1">
											Visible to users?</label>
									</div>
								</div>
								<div class="control-group hide">
									<label class="control-label">Search Type</label>
									<div class="controls">
										<select name="srctyp">
											<option value="text">Text</option>
										</select>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Reply Email Field<small>check if this is the users email address and you wish to send an acknowledgement and track form entries</small></label>
									<div class="controls">
										<label class="checkbox">
											<input type="checkbox" name="atlspc" value="1">
											Reply Email Field </label>
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
