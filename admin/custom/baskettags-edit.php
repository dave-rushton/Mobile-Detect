<?php

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("../system/classes/subcategories.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$Cat_ID = (isset($_REQUEST['cat_id']) && is_numeric($_REQUEST['cat_id'])) ? $_REQUEST['cat_id'] : NULL;
$Sub_ID = (isset($_REQUEST['sub_id']) && is_numeric($_REQUEST['sub_id'])) ? $_REQUEST['sub_id'] : NULL;
$SubDao = new SubDAO();
if (!is_null($Sub_ID)) $SubObj = $SubDao->select(NULL, $Sub_ID, NULL, NULL, true);

?>
<!doctype html>
<html>
<head>
    <title>Activities : <?php echo(isset($SubObj)) ? $SubObj->subnam : 'New Activity'; ?></title>
    <?php include('../webparts/headdata.php'); ?>

    <style>

        .fileupload-preview {
            background: #e1e1e1;
        }

    </style>

    <script src="js/plugins/fileupload/bootstrap-fileupload.min.js"></script>
    <script src="js/plugins/tinymce/tinymce.min.js"></script>
    <script src="custom/js/baskettags-edit.js"></script>

</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
    <?php include('../webparts/website-left.php'); ?>
    <div id="main">
        <div class="container-fluid">
            <div class="page-header">
                <div class="pull-left">
                    <h1>Activities</h1>
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
                        <a href="custom/baskettags.php">Basket Tags</a>
                        <i class="icon-angle-right"></i>
                    </li>
                    <li>
                        <a><?php echo(isset($SubObj)) ? $SubObj->subnam : 'New Activity'; echo $_SESSION['s_log_id']; ?></a>
                    </li>
                </ul>
            </div>
            <div class="row-fluid">
                <div class="span6">
                    <form action="system/subcategories_script.php" id="subCategoriesForm" class="form-horizontal" data-returnurl="custom/activities.php">
                    <div class="box box-color box-bordered">
                        <div class="box-title">
                            <h3>
                                <i class="icon-comments"></i> Basket Tags</h3>
                            <div class="actions">
                                <a href="#" id="updateSubCategoryBtn" class="btn btn-mini" rel="tooltip" title="Update"><i class="icon-save"></i></a>
                                <a href="#" id="deleteSubCategoryBtn" class="btn btn-mini" rel="tooltip" title="Delete"><i class="icon-trash"></i></a>
                            </div>
                        </div>
                        <div class="box-content">

                                <input type="hidden" name="sub_id" id="id" value="<?php echo(isset($SubObj)) ? $SubObj->sub_id : '0'; ?>">
                                <input type="hidden" name="cat_id" value="<?php echo $Cat_ID; ?>">
                                <div class="control-group">
                                    <label class="control-label">Tag Name</label>
                                    <div class="controls">
                                        <input type="text" class="input-block-level" name="subnam" value="<?php echo(isset($SubObj)) ? $SubObj->subnam : ''; ?>">
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">Tag Text</label>
                                    <div class="controls">

                                        <textarea class="tinymce customfield" rows="20" style="height: 400px;" id="subtxt" name="subtxt">

                                            <?php if (isset($SubObj->subtxt)) echo $patchworks->getJSONVariable($SubObj->subtxt, 'subtxt', false); ?>

                                        </textarea>

                                    </div>
                                </div>

                                <div class="control-group">
                                    <label for="textfield" class="control-label">Header Image</label>
                                    <div class="controls">
                                        <div class="fileupload fileupload-new" data-provides="fileupload">
                                            <div class="fileupload-new thumbnail" style="max-width: 200px; max-height: 180px; background: #cecece">

                                                <?php
                                                $SubImg = (isset($SubObj->subtxt)) ? $patchworks->getJSONVariable($SubObj->subtxt, 'subimg', false) : '';
                                                ?>

                                                <?php

                                                if (
                                                    isset($SubImg) &&
                                                    file_exists($patchworks->docRoot . 'uploads/images/' . $SubImg) &&
                                                    !is_dir($patchworks->docRoot . 'uploads/images/' . $SubImg)
                                                ) {
                                                    echo '<img src="../uploads/images/' . $SubImg . '" class="img-responsive" />';
                                                } else {
                                                    echo '<img src="http://www.placehold.it/200x180/EFEFEF/AAAAAA&text=no+image" />';
                                                }
                                                ?>

                                            </div>

                                            <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                            <div>
                                                <span class="btn btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span><input type="file" name='logofile2' id="logofile2" /></span>
                                                <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                                            </div>

                                            <input type="hidden" name="subimg" class="customfield" value="<?php echo $SubImg; ?>">

                                        </div>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">Image Text</label>
                                    <div class="controls">

                                        <input type="text" class="input-block-level customfield" name="imgtxt" value="<?php if (isset($SubObj->subtxt)) echo $patchworks->getJSONVariable($SubObj->subtxt, 'imgtxt', false); ?>">

                                    </div>
                                </div>


                                <div class="control-group">
                                    <label class="control-label">SEO URL</label>
                                    <div class="controls">
                                        <input type="text" class="input-block-level customfield" name="seourl" value="<?php echo(isset($SubObj)) ? $SubObj->seourl : ''; ?>">
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">Status<small>status</small></label>
                                    <div class="controls">
                                        <label class="radio">
                                            <input type="radio" name="sta_id" id="Sta_ID0" value="0" <?php echo(!isset($SubObj) || isset($SubObj) && $SubObj->sta_id == 0) ? 'checked' : ''; ?>>
                                            Active</label>
                                        <label class="radio">
                                            <input type="radio" name="sta_id" id="Sta_ID1" value="1" <?php echo(isset($SubObj) && $SubObj->sta_id == 1) ? 'checked' : ''; ?>>
                                            Inactive </label>
                                    </div>
                                </div>


                        </div>
                    </div>


                        <div class="box box-color box-bordered">
                            <div class="box-title">
                                <h3>Home Page Content</h3>
                            </div>
                            <div class="box-content">

                                <div class="control-group">
                                    <label class="control-label">Home Page Title</label>
                                    <div class="controls">
                                        <input type="text" class="input-block-level customfield" name="homttl" value="<?php if (isset($SubObj->subtxt)) echo $patchworks->getJSONVariable($SubObj->subtxt, 'homttl', false); ?>">
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">Home Page Summary</label>
                                    <div class="controls">

                                        <textarea name="homsum" cols="30" rows="10" class="input-block-level customfield"><?php if (isset($SubObj->subtxt)) echo $patchworks->getJSONVariable($SubObj->subtxt, 'homsum', false); ?></textarea>

                                    </div>
                                </div>

                                <div class="control-group">
                                    <label for="textfield" class="control-label">Home Page Image (600x600)</label>
                                    <div class="controls">
                                        <div class="fileupload fileupload-new" data-provides="fileupload">
                                            <div class="fileupload-new thumbnail" style="max-width: 200px; max-height: 180px; background: #cecece">

                                                <?php $HomImg = (isset($SubObj->subtxt)) ? $patchworks->getJSONVariable($SubObj->subtxt, 'homimg', false) : ''; ?>

                                                <?php

                                                if (
                                                    isset($HomImg) &&
                                                    file_exists($patchworks->docRoot . 'uploads/images/' . $HomImg) &&
                                                    !is_dir($patchworks->docRoot . 'uploads/images/' . $HomImg)
                                                ) {
                                                    echo '<img src="../uploads/images/' . $HomImg . '" class="img-responsive" />';
                                                } else {
                                                    echo '<img src="http://www.placehold.it/200x180/EFEFEF/AAAAAA&text=no+image" />';
                                                }
                                                ?>

                                            </div>

                                            <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                            <div>
                                                <span class="btn btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span><input type="file" name='logofile3' id="logofile3" /></span>
                                                <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                                            </div>

                                            <input type="hidden" name="homimg" class="customfield" value="<?php echo $HomImg; ?>">

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
</div>
</body>
</html>
