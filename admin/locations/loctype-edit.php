<?php

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("../system/classes/categories.cls.php");
require_once("../system/classes/subcategories.cls.php");


$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');


$TblNam = 'location-types';
$TmpCat = new CatDAO();
$categoryRec = $TmpCat->select(NULL,$TblNam,NULL,NULL,true);

if (!$categoryRec) {

    $CatObj = new stdClass();

    $CatObj->cat_id = 0;
    $CatObj->tblnam = 'location-types';
    $CatObj->tbl_id = 0;
    $CatObj->catnam = 'Location Types';
    $CatObj->seourl = 'Location-types';
    $CatObj->keywrd = 'Location Types';
    $CatObj->keydsc = 'Location Types';
    $CatObj->sta_id = 0;
    $Cat_ID = $TmpCat->update($CatObj);

} else {
    $Cat_ID = $categoryRec->cat_id;
}

$Sub_ID = (isset($_REQUEST['sub_id']) && is_numeric($_REQUEST['sub_id'])) ? $_REQUEST['sub_id'] : NULL;
$SubDao = new SubDAO();
if (!is_null($Sub_ID)) $SubObj = $SubDao->select(NULL, $Sub_ID, NULL, NULL, true);

?>
<!doctype html>
<html>
<head>
    <title>Job Type : <?php echo(isset($SubObj)) ? $SubObj->subnam : 'New Job Type'; ?></title>
    <?php include('../webparts/headdata.php'); ?>
    <script src="locations/js/loctype-edit.js"></script>
</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
    <?php include('../webparts/website-left.php'); ?>
    <div id="main">
        <div class="container-fluid">
            <div class="page-header">
                <div class="pull-left">
                    <h1>Job Type</h1>
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
                        <a href="locations/loctype.php">Job Types</a>
                        <i class="icon-angle-right"></i>
                    </li>
                    <li>
                        <a>Job Type</a>
                    </li>
                </ul>
            </div>
            <div class="row-fluid">
                <div class="span6">
                    <div class="box box-color box-bordered">
                        <div class="box-title">
                            <h3>
                                <i class="icon-cog"></i> Job Type</h3>
                            <div class="actions">
                                <a href="#" id="updateSubCategoryBtn" class="btn btn-mini" rel="tooltip" title="Update"><i class="icon-save"></i></a>
                                <a href="#" id="deleteSubCategoryBtn" class="btn btn-mini" rel="tooltip" title="Delete"><i class="icon-trash"></i></a>
                            </div>
                        </div>
                        <div class="box-content">
                            <form action="system/subcategories_script.php" id="subCategoriesForm" class="form-horizontal" data-returnurl="locations/loctype.php">
                                <input type="hidden" name="sub_id" id="id" value="<?php echo(isset($SubObj)) ? $SubObj->sub_id : '0'; ?>">
                                <input type="hidden" name="cat_id" value="<?php echo $Cat_ID; ?>">
                                <div class="control-group">
                                    <label class="control-label">Job Type Name</label>
                                    <div class="controls">
                                        <input type="text" class="input-block-level" name="subnam" value="<?php echo(isset($SubObj)) ? $SubObj->subnam : ''; ?>">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">SEO URL</label>
                                    <div class="controls">
                                        <input type="text" class="input-block-level" name="seourl" value="<?php echo(isset($SubObj)) ? $SubObj->seourl : ''; ?>">
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
