<?php

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once('../website/classes/pagecontent.cls.php');


$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$editContentID = (isset($_GET['pgc_id']) && is_numeric($_GET['pgc_id'])) ? $_GET['pgc_id'] : 0;
$contentRec = NULL;
$PgcDao = new PgcDAO();
$contentRec = $PgcDao->selectGeneric($editContentID, true);

?>
<!doctype html>
<html>
<head>
    <title>Generic Content</title>
    <?php include('../webparts/headdata.php'); ?>

    <!-- CKEditor -->
    <!--<script src="js/plugins/ckeditor/ckeditor.js"></script>-->
    <script src="js/plugins/tinymce/tinymce.min.js"></script>
    <script src="js/plugins/fileupload/bootstrap-fileupload.min.js"></script>

    <script src="website/js/generic-content-edit.js"></script>
</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
    <?php include('../webparts/website-left.php'); ?>
    <div id="main">
        <div class="container-fluid">
            <div class="page-header">
                <div class="pull-left">
                    <h1>Generic Content</h1>
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
                        <a>Website</a>
                        <i class="icon-angle-right"></i>
                    </li>
                    <li>
                        <a href="website/generic-content.php">Generic Content</a>
                        <i class="icon-angle-right"></i>
                    </li>
                    <li>
                        <a>Generic Content</a>
                    </li>
                </ul>
            </div>
            <div class="row-fluid">
                <form class="form-horizontal form-validate form-bordered" method="POST" action="website/json/content.json.php" id="contentForm" data-returnurl="website/generic-content.php">
                    <div class="span8">
                        <div class="box box-color box-bordered">
                            <div class="box-title">
                                <h3>
                                    <i class="icon-comments"></i> Generic Content</h3>
                                <div class="actions">
                                    <a href="#" id="updateContentBtn" class="btn btn-mini" rel="tooltip" title="Update"><i class="icon-save"></i></a>
                                    <a href="#" id="deleteContentBtn" class="btn btn-mini" rel="tooltip" title="Delete"><i class="icon-trash"></i></a>
                                </div>
                            </div>
                            <div class="box-content nopadding">
                                <input type="hidden" name="pgc_id" id="id" value="<?php echo ($contentRec) ? $contentRec->pgc_id : '0'; ?>" >
                                <input type="hidden" name="srtord" id="id" value="<?php echo ($contentRec) ? $contentRec->srtord : '99'; ?>" >
                                <input type="hidden" name="sta_id" value="10" >

                                <div class="control-group">
                                    <label class="control-label">Table Name<small>developer only</small></label>
                                    <div class="controls">
                                        <input type="text" class="input-block-level" name="tblnam" value="<?php echo ($contentRec) ? $contentRec->tblnam : ''; ?>">
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" for="name">Content Title<small>indentifying title</small></label>
                                    <div class="controls">
                                        <input type="text" class="input-block-level" name="pgcttl" value="<?php echo ($contentRec) ? $contentRec->pgcttl : 'New Content'; ?>">
                                    </div>
                                </div>


                                <div class="control-group hide">
                                    <label class="control-label" for="name">Forward URL</label>
                                    <div class="controls">
                                        <input type="text" class="input-block-level customfield" name="fwdurl" value="<?php echo (isset($contentRec->pgcobj)) ? $patchworks->getJSONVariable($contentRec->pgcobj, 'fwdurl', false) : ''; ?>">
                                    </div>
                                </div>

                            </div>
                        </div>
                        <br class="clear">

                        <div class="box box-color box-bordered darkblue">
                            <div class="box-title">
                                <h3><i class="icon-th"></i> Content</h3>
                            </div>
                            <div class="box-content nopadding">
                                <textarea name="pgctxt" class='tinymce' rows="20" style="height: 400px;" id="pgctxt"><?php echo ($contentRec) ? $contentRec->pgctxt : ''; ?></textarea>
                            </div>
                        </div>

                        <div class="control-group hide">
                            <label for="textfield" class="control-label">Image</label>
                            <div class="controls">
                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="fileupload-new thumbnail" style="max-width: 200px; max-height: 150px;">


                                        <?php
                                        if (
                                            isset($contentRec->pgcimg) &&
                                            file_exists($patchworks->docRoot . 'uploads/images/content/' . $contentRec->pgcimg) &&
                                            !is_dir($patchworks->docRoot . 'uploads/images/content/' . $contentRec->pgcimg)
                                        ) {
                                            echo '<img src="'.$patchworks->webRoot.'/uploads/images/content/' . $contentRec->pgcimg . '" class="img-responsive productImage" />';
                                        } else {
                                            echo '<img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image" />';
                                        }
                                        ?>

                                    </div>

                                    <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                    <div>
                                        <span class="btn btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span><input type="file" name='logofile' id="logofile" /></span>
                                        <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="pgcimg" value="<?php echo($contentRec) ? $contentRec->pgcimg : ''; ?>">
                        </div>

                        <input type="hidden" name="pgcobj" value="<?php echo($contentRec) ? $contentRec->pgcobj : ''; ?>">

                        <br class="clear">
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
<input type="hidden" id="webRoot" value="<?php echo $patchworks->webRoot; ?>">
</body>
</html>
