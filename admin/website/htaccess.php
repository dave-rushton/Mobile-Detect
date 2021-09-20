<?php

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("classes/htaccess.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id'], "website:htaccess");
if ($loggedIn == 0) header('location: ../login.php');

$TmpHta = new HtaDAO();
$htAccess = $TmpHta->select(NULL, false);

?>
<!doctype html>
<html>
<head>
    <title>htAccess Config</title>
    <?php include('../webparts/headdata.php'); ?>

    <style>

        .slimScrollDiv {
            border: none !important;
        }

    </style>

    <script src="website/js/htaccess.js"></script>

</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
    <?php include('../webparts/system-left.php'); ?>
    <div id="main">
        <div class="container-fluid">
            <div class="page-header">
                <div class="pull-left">
                    <h1>htAccess Redirects</h1>
                </div>
                <div class="pull-right">
                    <?php include('../webparts/index-info.php'); ?>
                </div>
            </div>
            <div class="breadcrumbs">
                <ul>
                    <li>
                        <a href="index.php">Website Portal</a>
                        <i class="icon-angle-right"></i>
                    </li>
                    <li>
                        <a href="website/index.php">Website</a>
                        <i class="icon-angle-right"></i>
                    </li>
                    <li>
                        <a>htAccess</a>
                    </li>
                </ul>
            </div>

            <div class="row-fluid">
                <div class="span8">

                    <div class="box box-color box-bordered">
                        <div class="box-title">
                            <h3>
                                <i class="icon-paste"></i> Redirects</h3>
                            <div class="actions">


                            </div>
                        </div>
                        <div class="box-content nopadding">

                            <form action="#" id="htAccessForm" method="POST" class="form-horizontal form-bordered">
                                <div class="control-group">
                                    <label for="textfield" class="control-label">From URL</label>
                                    <div class="controls">

                                        <div class="input-append input-prepend">
                                            <span class="add-on"><?php echo $patchworks->webRoot; ?></span>
                                            <input type="text" name="frmurl" class="input-block-level" required autocomplete="off">
                                        </div>

                                    </div>
                                </div>
                                <div class="control-group">
                                    <label for="textfield" class="control-label">To URL</label>
                                    <div class="controls">
                                        <div class="input-append input-prepend">
                                            <span class="add-on"><?php echo $patchworks->webRoot; ?></span>
                                            <input type="text" name="to_url" class="input-block-level" required autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label for="textfield" class="control-label">Object</label>
                                    <div class="controls">
                                        <div class="input-wrapper">
                                            <textarea name="htaobj" cols="30" rows="3" class="input-block-level"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary"><i class="icon icon-save"></i> Save</button>
                                </div>
                            </form>

                        </div>
                        <div class="box-content nopadding">

                            <div class="scrollarea">

                            <table class="table table-bordered table-striped table-highlight table-condensed" id="userTable">
                                <thead>
                                <tr>
                                    <th style="width: 20px">#</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th style="width: 45px"></th>
                                </tr>
                                </thead>
                                <tbody id="htAccessBody">

                                <?php
                                for ($i=0;$i<count($htAccess);$i++) {
                                    ?>

                                    <tr>
                                        <td>1</td>
                                        <td><?php echo $htAccess[$i]['frmurl']; ?></td>
                                        <td><?php echo $htAccess[$i]['to_url']; ?></td>
                                        <td>
                                            <a href="#" class="btn btn-mini btn-danger deleteAccessLink" data-hta_id="<?php echo $htAccess[$i]['hta_id']; ?>"><i class="icon icon-remove"></i></a>
                                            <a href="#" class="btn btn-mini btn-warning resortAccessLink" data-hta_id="<?php echo $htAccess[$i]['hta_id']; ?>"><i class="icon icon-reorder"></i></a>
                                        </td>
                                    </tr>

                                    <?php
                                }
                                ?>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" style="text-align: right;"><a href="#" class="btn btn-primary" id="updateAllhtAccess"><i class="icon icon-save"></i> Save</a></td>
                                    </tr>
                                </tfoot>
                            </table>

                            </div>

                        </div>
                    </div>
                </div>

                <div class="span4">

                    <div class="box box-color box-bordered">
                        <div class="box-title">
                            <h3>
                                <i class="icon-cloud-upload"></i> Redirect Import</h3>
                        </div>
                        <div class="box-content nopadding">

                            <form action="#" method="post" class="form-horizontal form-bordered" enctype="multipart/form-data" id="importForm">
                                <div class="control-group">
                                    <label for="file" class="control-label">Local XLSX File
                                        <small>Select xlsx file</small>
                                    </label>
                                    <div class="controls">
                                        <input type="file" name="file" id="file" class="input-block-level">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary">Import Redirects</button>
                                </div>
                            </form>

                            <div id="resultoutput"></div>

                        </div>
                    </div>

                    <div class="box box-color box-bordered">
                        <div class="box-title">
                            <h3>
                                <i class="icon-file-alt"></i> View File</h3>
                            <div class="actions">
                                <a href="website/build_files/build.htaccess.php" id="rebuildFile" class="btn btn-mini" rel="tooltip" title="Rebuild htAccess"><i class="icon-refresh"></i></a>
                                <a href="#" class="btn btn-mini content-slideUp"><i class="icon-angle-down"></i></a>
                            </div>
                        </div>
                        <div class="box-content" style="display: none">

                            <?php

                            $topHT = file_get_contents($patchworks->docRoot.'.htaccess');
                            //$topHT .= file_get_contents($patchworks->pwRoot.'website/build_files/htaccess_bottom.txt');
                            ?>

                            <textarea name="" id="" cols="30" rows="20" class="input-block-level" style="font-family: 'Courier New', Monospace;"><?php echo $topHT; ?></textarea>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
</body>
</html>
