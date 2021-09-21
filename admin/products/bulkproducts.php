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
<title>Bulk Update of Products</title>
<?php include('../webparts/headdata.php'); ?>

<script>

    $(function(){

        $('.bulkUpdateForm').submit(function(e){

			var thisForm = $(this);

			thisForm.block({ message: 'UPDATING : PLEASE WAIT' });

            e.preventDefault();

            $.ajax({
                url: 'products/bulk.products.php',
                data: thisForm.serialize(),
                type: 'POST',
                aSync: false,
                success: function (data) {

					alert( data );

                    thisForm.unblock();

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
					<h1>Bulk Update of Products</h1>
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
						<a>Bulk Update of Products</a>
					</li>
				</ul>
			</div>
			<div class="row-fluid">
				<div class="span12">

					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-cloud-upload"></i> Bulk Update of Products</h3>
						</div>
						<div class="box-content nopadding">
						
							<form action="#" method="post" class="form-horizontal form-bordered bulkUpdateForm" enctype="multipart/form-data">

								<input type="hidden" name="action" value="incrementprice">

								<div class="control-group">
									<label for="unipriinc" class="control-label">Increment Unit Price By x%
										<small>to decrease use (-x)</small>
									</label>
									<div class="controls">
										<input type="number" name="unipriinc" value="1" class="input-block-level">
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
