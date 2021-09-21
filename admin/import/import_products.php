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
<title>Products Import</title>
<?php include('../webparts/headdata.php'); ?>

<script>

    $(function(){

        $('#importForm').submit(function(e){

            $('#importForm').block({ message: 'IMPORTING : PLEASE WAIT' });

            e.preventDefault();

            var data;

            data = new FormData();
            data.append('file', $('#file')[0].files[0]);

            $.ajax({
                url: 'import/import_products_script.php',
                data: data,
                processData: false,
                type: 'POST',
                contentType: false,
                aSync: false,
                success: function (data) {

                    $('#resultoutput').html( data );

                    var chkimp = data.split(' ');

                    if ( chkimp[0] == '0' ) {

                        $('#importFailed').slideDown();

                    } else {

                        //$('#rowsImported').html( data );
                        $('#importComplete').slideDown();

                    }

                    $('#importForm').unblock();

                },
                error: function (x, e) {

                    throwAjaxError(x, e);

                    $('#importFailed').slideDown();

                }
            });

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
					<h1>Products Import</h1>
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
						<a href="import/import_postcodes.php">Products Import</a>
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
								<i class="icon-cloud-upload"></i> Products Import</h3>
						</div>
						<div class="box-content nopadding">
						
							<form action="#" method="post" class="form-horizontal form-bordered" enctype="multipart/form-data" id="importForm">
								<div class="control-group">
									<label for="file" class="control-label">Local XLSX File
										<small>Select products  xlsx file</small>
									</label>
									<div class="controls">
										<input type="file" name="file" id="file" class="input-block-level">
										<span class="help-block"></span>
									</div>
								</div>
								<div class="form-actions">
									<button type="submit" class="btn btn-primary">Import Products</button>
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
