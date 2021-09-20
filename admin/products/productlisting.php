<?php 

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("../attributes/classes/attrgroups.cls.php");
require_once("../attributes/classes/attrlabels.cls.php");
require_once("../attributes/classes/attrvalues.cls.php");

require_once("classes/products.cls.php");


$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');


$TmpAtr = new AtrDAO();
$attrGroups = $TmpAtr->select(NULL, 'PRODUCTGROUP');

?>
<!doctype html>
<html>
<head>
<title>Products</title>
<?php include('../webparts/headdata.php'); ?>

<link rel="stylesheet" href="css/plugins/datatable/TableTools.css">
<script src="js/plugins/datatable/jquery.dataTables.min.js"></script>
<script src="js/plugins/datatable/TableTools.min.js"></script>
<script src="js/plugins/datatable/ColReorderWithResize.js"></script>
<script src="js/plugins/datatable/ColVis.min.js"></script>
<script src="js/plugins/datatable/jquery.dataTables.columnFilter.js"></script>
<script src="js/plugins/datatable/jquery.dataTables.grouping.js"></script>

<script>

//$(function(){
//
//	var oTable = $('#productTable').dataTable({
//        "bServerSide": true,
//        "sServerMethod": "GET",
//        "sAjaxSource": "products/products_table.php",
//        "sAjaxDataProp": "aaData",
//        "iDisplayLength": 100,
//        "aoColumnDefs": [
//            { "bVisible": true, "aTargets": [ 0 ] },
//            { "bVisible": false, "aTargets": [ 1 ] },
//            { "bVisible": true, "aTargets": [ 2 ] },
//            { "bVisible": false, "aTargets": [ 3 ] },
//            { "bVisible": true, "aTargets": [ 4 ] },
//            { "bVisible": true, "aTargets": [ 5 ] },
//            { "bVisible": true, "aTargets": [ 6 ] }
//        ],
//        "fnRowCallback": function( nRow, aData, iDisplayIndex ) {
//
//            $('td:eq(1)', nRow).html('<a href="products/product-edit.php?prd_id=' + aData[1] + '">' + aData[2] + '</a>');
//            $('td:eq(2)', nRow).html('<a href="products/productgroup-edit.php?atr_id=' + aData[3] + '">' + aData[4] + '</a>');
//
//            return nRow;
//        }
//    });
//
//
//});

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
					<h1>Products</h1>
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
						<a href="products/products.php">Products</a>
					</li>
				</ul>
			</div>
			<div class="row-fluid">
                <div class="span4">
                    <div class="box box-color box-bordered">
                        <div class="box-title">
                            <h3>
                                <i class="icon-shopping-cart"></i> Search</h3>
                            <div class="actions">
                                <a href="products/product-edit.php" class="btn btn-mini" rel="tooltip" title="New Product"><i class="icon-plus"></i></a>
                            </div>
                        </div>
                        <div class="box-content nopadding">



                            <form id="productSearchForm" class="form-horizontal form-validate form-bordered" novalidate="novalidate">
                                <div class="control-group">
                                    <label class="control-label">Product</label>
                                    <div class="controls">
                                        <select name="atr_id">
                                            <?php
                                            $tableLength = count($attrGroups);
                                            for ($i=0;$i<$tableLength;++$i) {
                                                ?>
                                                <option value="<?php echo $attrGroups[$i]['atr_id']; ?>"><?php echo $attrGroups[$i]['atrnam']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div id="productAttributeSearch" class="form-vertical attributeForm">

                                </div>

                                <div class="control-group">
                                    <label class="control-label">&nbsp;</label>
                                    <div class="controls">
                                        <a href="#" class="btn btn-primary" id="productSearchBtn">Search</a>
                                    </div>
                                </div>



                            </form>

                            <script>

                                var productSearchForm, productAttributeSearch;

                                $(function(){

                                    productSearchForm = $('#productSearchForm');
                                    productAttributeSearch = $('#productAttributeSearch');

                                    $('[name="atr_id"]', productSearchForm).change(function(){

                                        //
                                        // get form
                                        //

                                        $.ajax({
                                            url: 'attributes/ajax/attribute_form.php',
                                            data: 'atr_id=' + $(this).val() + '&search=1&atvtblnam=PRODUCT&atvtbl_id=',// + $('#id', $('#productForm')).val(),
                                            type: 'GET',
                                            async: true,
                                            success: function( data ) {

                                                //alert( data );

                                                $('#productAttributeSearch').html(data);

                                            },
                                            error: function (x, e) {
                                                throwAjaxError(x, e);
                                            }
                                        });

                                    });

                                    $('#productSearchBtn').click(function (e) {

                                        e.preventDefault();

                                        $(':radio:checked', productAttributeSearch).each(function(){
                                            $(this).parent().parent().find(':hidden').val( $(this).val() );
                                        });

                                        $(':checkbox', productAttributeSearch).each(function(){
                                            $(this).parent().next(':hidden').val( ($(this).is(':checked')) ? 1 : 0 );
                                        });

                                        //if ($(this).valid()) {

                                        //alert('products/products_search_table.php?action=update&ajax=true&tblnam=PRODUCTGROUP&' + $('#productAttributeSearch :input').serialize() );


                                        $.ajax({
                                            url: 'products/products_search_table.php',
                                            data: 'ajax=true&tblnam=PRODUCTGROUP&' + $('#productAttributeSearch :input').serialize(),
                                            type: 'GET',
                                            async: false,
                                            success: function( data ) {

                                                if (data.length > 0) {

                                                    $('#productAttributeSearchResults').html(data);

                                                } else {

                                                    $('#productAttributeSearchResults').html('');

                                                    $.msgGrowl ({
                                                        type: 'warning'
                                                        , title: 'Only Product Types Available'
                                                        , text: 'Product will not appear in advanced search'
                                                    });

                                                }

                                            },
                                            error: function (x, e) {
                                                throwAjaxError(x, e);
                                            }
                                        });
                                        //}
                                        //else {
                                        //	$.msgGrowl ({
                                        //		type: 'error'
                                        //		, title: 'Invalid Form'
                                        //		, text: 'There is an error in the form'
                                        //	});
                                        //}
                                    });

                                });

                            </script>


                        </div>
                    </div>
                </div>
                <div class="span8">
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-shopping-cart"></i> Products</h3>
							<div class="actions">
								<a href="products/product-edit.php" class="btn btn-mini" rel="tooltip" title="New Product"><i class="icon-plus"></i></a>
							</div>
						</div>
						<div class="box-content nopadding">

                            <table class="table table-bordered table-striped table-highlight" id="productTable">

                                <tbody id="productAttributeSearchResults">

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
