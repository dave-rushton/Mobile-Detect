<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("../products/classes/product_types.cls.php");


$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

?>
<!doctype html>
<html>
<head>
<title>Product Types</title>
<?php include('../webparts/headdata.php'); ?>

    <link rel="stylesheet" href="css/plugins/datatable/TableTools.css">

    <script src="js/plugins/datatable/jquery.dataTables.min.js"></script>
    <script src="js/plugins/datatable/TableTools.min.js"></script>
    <script src="js/plugins/datatable/ColReorderWithResize.js"></script>
    <script src="js/plugins/datatable/ColVis.min.js"></script>
    <script src="js/plugins/datatable/jquery.dataTables.columnFilter.js"></script>
    <script src="js/plugins/datatable/jquery.dataTables.grouping.js"></script>

    <script src="js/plugins/garlic/garlic.min.js"></script>

    <script>

        $(function(){



            var oTable = $('#productTypeTable').dataTable({
                // "bServerSide": true,
                // "sServerMethod": "GET",
                "bProcessing": true,
                "bAutoWidth": false,
                "bServerSide": false,
                "bJQueryUI": false,
                "sAjaxDataProp": "aaData",
                "iDisplayLength": 9999,
                "sAjaxSource": "products/producttypes_table.php",
                "aaSorting": [[ 2, 'asc' ]],
                "aoColumns": [
                    { "bSortable ": true, },
                    { "bSortable ": true,  },
                    { "bSortable ": false, },
                    { "bSortable ": true, },
                    { "bSortable ": true, },
                ],
                "fnRowCallback": function( nRow, aData, iDisplayIndex ) {
                    $('td:eq(1)', nRow).html('<a href="products/producttype-edit.php?prt_id=' + aData[0] + '">' + aData[1] + '</a>');
                    $('td:eq(2)', nRow).html('<a href="products/producttype-edit.php?prt_id=' + aData[0] + '">' + aData[2] + '</a>');
                    $('td:eq(3)', nRow).html('<a style="text-transform:capitalize;" href="products/producttype-edit.php?prt_id=' + aData[0] + '">' + aData[3] + '</a>');
                    //$('td:eq(2)', nRow).html('<a href="products/productgroup-edit.php?atr_id=' + aData[3] + '">' + aData[4] + '</a>');

                    return nRow;
                }
            });
        });

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
					<h1>Product Types</h1>
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
						<a href="products/dashboard.php">Products Dashboard</a>
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a href="products/producttypes.php">Product Types</a>
					</li>
				</ul>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-sitemap"></i> Product Types</h3>
							<div class="actions">
								<a href="products/producttype-edit.php" class="btn btn-mini" rel="tooltip" title="New Product Type"><i class="icon-plus"></i></a>
							</div>
						</div>
						<div class="box-content nopadding">
							<table class="table table-bordered table-striped table-highlight" id="productTypeTable">
							<thead>
								<tr>
									<th width="160">ID</th>
									<th width="320">Manufacturer</th>
									<th width="320">Product Name</th>
									<th width="320">Machine Type</th>
									<th width="50">Done</th>
								</tr>
							</thead>
							<tbody id="productTypeBody">

							</tbody>
						</table>
						</div>
					</div>
				</div>
			</div>
			
		</div>
	</div>
</div>
</body>
</html>
