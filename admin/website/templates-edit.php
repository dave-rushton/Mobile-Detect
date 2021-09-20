<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("../website/classes/template.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpTpl = new TplDAO();
$Tpl_ID = (isset($_GET['tpl_id'])) ? $_GET['tpl_id'] : NULL;
if (is_numeric($Tpl_ID)) $templateRec = $TmpTpl->select($Tpl_ID, true);

?>
<!doctype html>
<html>
<head>
<title>Website Template</title>

    <?php include('../webparts/headdata.php'); ?>


    <style>

        #templateLayout {

        }

        #templateLayout .rowWrapper {
            position: relative;
            padding-right: 45px;
        }

        #templateLayout .rowWrapper .moveRow {
            position: absolute;
            left: 100%;
            top: 4px;
            margin-left: 5px;
        }

        #templateLayout .row-fluid {
            position: relative;
            background: #ccc;
            padding: 5px;
            margin-bottom: 5px;
        }

        #templateLayout .row-fluid [class^="span"] {
            margin-bottom: 0px;
            background: #fff;
            padding: 3px;
        }

        #templateLayout .row-fluid [class^="span"] a.deleteColumn {
            float: right;
        }

        #templateLayout .row-fluid .deleteRow {

            position: absolute;
            top: 4px;
            right: 100%;
            margin-right: 5px;
        }

        #templateLayout .row-fluid .addColumn {

            position: absolute;
            top: 4px;
            left: 100%;
            margin-left: 5px;
        }

    </style>

    <script src="website/js/templates-edit.js"></script>

</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
    <?php include('../webparts/website-left.php'); ?>

	<div id="main">
		<div class="container-fluid">
			<div class="page-header">
				<div class="pull-left">
					<h1>Website Templates</h1>
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
						<a href="website/templates.php">Templates</a>
                        <i class="icon-angle-right"></i>
					</li>
                    <li>
                        <a>Website Template</a>
                    </li>
				</ul>
			</div>
			<div class="row-fluid">
				<div class="span4">
					<div class="box box-color box-bordered" id="attrGroupBox">
						<div class="box-title">
							<h3>
								<i class="icon-comments"></i> Website Templates</h3>
							<div class="actions">

							</div>
						</div>
						<div class="box-content nopadding">
							<form class="form-horizontal form-validate form-bordered" method="POST" action="website/templates_script.php" id="templateForm" data-returnurl="website/templates.php">
								<input type="hidden" name="tpl_id" id="id" value="<?php echo (isset($templateRec->tpl_id)) ? $templateRec->tpl_id : '0';?>" />

								<div class="control-group">
									<label class="control-label">Template Name<small>identify template name</small></label>
									<div class="controls">
										<input type="text" class="input-block-level" name="tplnam" data-rule-required="true" data-rule-minlength="2" value="<?php echo (isset($templateRec->tplnam)) ? $templateRec->tplnam : '';?>">
									</div>
								</div>

								<div class="control-group">
									<label class="control-label">Template File<small>actual file of template</small></label>
									<div class="controls">
                                        <input type="text" class="input-block-level" name="tplfil" data-rule-required="true" value="<?php echo (isset($templateRec->tplfil)) ? $templateRec->tplfil : '';?>">
									</div>
								</div>

                                <div class="control-group">
                                    <label class="control-label">Template Object<small>template</small></label>
                                    <div class="controls">
                                        <textarea class="input-block-level" name="tplobj" id="tplobj" cols="30" rows="10"><?php echo (isset($templateRec->tplobj)) ? $templateRec->tplobj : '';?></textarea>
                                    </div>
                                </div>

								<div class="control-group">
									<label class="control-label">Default Template<small>is this the default template</small></label>
									<div class="controls">
										<label class="radio">
											<input type="radio" name="tpldef" value="0" <?php echo (!isset($templateRec->tpldef) || isset($templateRec->tpldef) && $templateRec->tpldef == 0) ? 'checked' : '';?>>
											Standard</label>
										<label class="radio">
											<input type="radio" name="tpldef" value="1" <?php echo (isset($templateRec->tpldef) && $templateRec->tpldef == 1) ? 'checked' : '';?>>
											Default Page Template </label>
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

                    <div class="box box-color box-bordered" id="attrGroupBox">
                        <div class="box-title">
                            <h3>
                                <i class="icon-comments"></i> Templates Layout Defaults</h3>
                            <div class="actions">

                            </div>
                        </div>
                        <div class="box-content" style="padding: 30px 50px;">


                            <div id="templateLayout">

                            </div>

                            <a href="#" class="btn btn-primary" id="addNewRow">Add New Row</a>


                        </div>
                    </div>

				</div>
			</div>
		</div>
	</div>
</div>



<div id="rowFormWrapper">
    <form class="form-vertical form-validate form-bordered rowForm">
        <div class="control-group">
            <label class="control-label">Class<small></small></label>
            <div class="controls">
                <input type="text" class="input-block-level" name="class" value="">
            </div>
        </div>
    </form>
</div>

<div id="columnFormWrapper">
    <form class="form-vertical form-validate form-bordered columnForm" style="display: none;">

        <input type="hidden" name="elementname">

        <div class="control-group1">
            <label class="control-label">Columns<small>based on 12 columns</small></label>
            <div class="controls">
                <input type="text" class="input-block-level" name="colspan" value="6">
            </div>
        </div>

        <div class="control-group1">
            <label class="control-label">Class<small></small></label>
            <div class="controls">
                <input type="text" class="input-block-level" name="class" value="">
            </div>
        </div>

        <?php

        $string = file_get_contents('pbparts/pagebuilder.xml',TRUE);
        $modules = new SimpleXMLElement($string);
        ?>

        <div class="control-group1">
            <label class="control-label">Default<small></small></label>
            <div class="controls">
                <select name="pageelement" class="input-block-level">

                    <option value="">No Default</option>

                    <?php

                    foreach ($modules as $module) {

                        ?>
                        <optgroup label="<?php echo $module->name ?>">
                        <?php

                        foreach ($module->element as $element) {
                            ?>
                            <option value="<?php echo $element->file; ?>"><?php echo $element->name; ?></option>
                            <?php
                        }
                        ?>
                        </optgroup>
                        <?php
                    }
                    ?>

                </select>
            </div>
        </div>

        <div class="form-action">
            <button type="submit">Update</button>
        </div>

    </form>
</div>

</body>
</html>
