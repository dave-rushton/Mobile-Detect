<?php
require_once('../../config/config.php');
require_once('../patchworks.php');
$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');
?>
<!doctype html>
<html>
<head>
    <title>Image Clear</title>
    <?php include('../webparts/headdata.php'); ?>
    <script>
        function runScript(dataobj){
            if($('#file')[0].files[0]!=undefined){
                $.ajax({
                    url: 'tools/checkclear.php',
                    data: dataobj,
                    processData: false,
                    type: 'POST',
                    contentType: false,
                    aSync: true,
                    success: function (data) {

                        $('#run-optimiser').css('display','inline-block');

                        ObjPrp = JSON.parse(data)
                        if(ObjPrp.fleext!=".sql"){
                            html="Script will not run as the file provided is not a SQL file.";
                        }else{
                            html="The optimise script has found a total of " + (ObjPrp.flekep+ObjPrp.fledel)+" Images! <br/>";
                            html+=ObjPrp.fledel+" ("+Math.round(((ObjPrp.fledel)/(ObjPrp.flekep+ObjPrp.fledel)*100))+"%) of them images can safely be deleted.<br/>"
                            html+="Saving a total of "+ObjPrp.flesiz
                        }

//                        alert(ObjPrp.flesiz);
//                        alert(ObjPrp.flekep);
//                        alert(ObjPrp.fledel);

                        $('#stats').html(html);
                    },
                    error: function (x, e) {
                        throwAjaxError(x, e);
                        $('#importFailed').slideDown();
                    }
                });
            }
        }
        $(function(){
            $('#file').change(function(){
                $('#run-optimiser').css('display','none');
                $('#stats').html('');
            })


            $('#run-summary').click(function(e){
                e.preventDefault();
                var data;
                data = new FormData();
                data.append('file', $('#file')[0].files[0]);
                data.append('action',"run");
                runScript(data);
            })
            $('#run-optimiser').click(function(e){
                e.preventDefault();
                var data;
                data = new FormData();
                data.append('file', $('#file')[0].files[0]);
                data.append('action',"delete");
                runScript(data);
            })
        })
    </script>
</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
    <?php include('../webparts/website-left.php'); ?>
    <div id="main">
        <div class="container-fluid">
            <div class="page-header">
                <div class="pull-left">
                    <h1>Image Clear</h1>
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
                        <a href="import/import_postcodes.php">Image Clear</a>
                    </li>
                </ul>
            </div>
            <div class="row-fluid">
                <div class="span12">
                    <div class="alert alert-success" style="margin-top: 20px; display: none;" id="importComplete">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>IMPORT COMPLETE: </strong> <span id="rowsImported">0</span> rows imported.
                    </div>
                    <div class="alert alert-error" style="margin-top: 20px; display: none;" id="importFailed">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>IMPORT FAILED: </strong> please check your products xlsx file and try again.
                    </div>
                    <div class="box box-color box-bordered">
                        <div class="box-title">
                            <h3>
                                <i class="icon-cloud-upload"></i> Image Clear</h3>
                        </div>
                        <div class="box-content nopadding">
                            <form action="#" method="post" class="form-horizontal form-bordered" enctype="multipart/form-data" id="clearImageForm">
                                <div class="control-group">
                                    <label for="file" class="control-label">Local DB File
                                        <small>Clear Unused Images</small>
                                    </label>
                                    <div class="controls">
                                        <input type="file" name="file" id="file" class="input-block-level">
                                        <span class="help-block"></span>
                                        <div id="stats">

                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <div id="run-summary" class="btn btn-primary">Run Report</div>
                                    <div id="run-optimiser" class="hide btn btn-primary">Run Optimiser</div>
                                </div>
                            </form>
                            <div id="resultoutput"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
