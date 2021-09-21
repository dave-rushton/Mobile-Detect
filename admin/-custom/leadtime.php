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
<title>Update Lead Times</title>
<?php include('../webparts/headdata.php'); ?>

<script>

    $(function(){

        $('#leadTimeForm').submit(function(e){

            $('#leadTimeForm').block({ message: 'UPDATING : PLEASE WAIT' });

            e.preventDefault();

            console.log( $('#leadTimeForm').serialize() );

            $.ajax({
                url: 'custom/increment.leadtime.php',
                data: $('#leadTimeForm').serialize(),
                type: 'POST',
                aSync: false,
                success: function (data) {

                    alert('complete');

                    $('#leadTimeForm').unblock();

                },
                error: function (x, e) {

                    throwAjaxError(x, e);

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

					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-cloud-upload"></i> Update Lead Time</h3>
						</div>
						<div class="box-content nopadding">
						
							<form action="#" method="post" class="form-horizontal form-bordered" enctype="multipart/form-data" id="leadTimeForm">
								<div class="control-group">
									<label for="days" class="control-label">Increment Lead Days
										<small>to decrease lead time use (-x)</small>
									</label>
									<div class="controls">
										<input type="number" name="days" value="1" class="input-block-level">
									</div>
								</div>
								<div class="form-actions">
									<button type="submit" class="btn btn-primary">Execute</button>
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
