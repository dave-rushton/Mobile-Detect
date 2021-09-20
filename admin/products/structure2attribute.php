<?php 

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once('../products/classes/structure.cls.php');

require_once("../attributes/classes/attrgroups.cls.php");
require_once("../attributes/classes/attrlabels.cls.php");
require_once("../attributes/classes/attrvalues.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpStr = new StrDAO();

ini_set('xdebug.max_nesting_level', 4000);

$TmpAtr = new AtrDAO();
$attrGroups = $TmpAtr->select(NULL, 'PRODUCTGROUP');
$productTypeAttr = $TmpAtr->select(NULL, 'PRODUCTTYPE', NULL, NULL, true, NULL, NULL, NULL);

?>
<!doctype html>
<html>
<head>
<title>Move Structure to Product Group</title>
<?php include('../webparts/headdata.php'); ?>


    <!-- colorbox -->
    <link rel="stylesheet" href="css/plugins/colorbox/colorbox.css">

    <style>

        #buildStructure {

            /*min-height: 100px;*/

        }
        #buildStructure ul {
            /*margin: 0;*/
            /*padding: 0;*/
            margin-top: 10px;
        }
        #buildStructure ul li {
            /*margin: 0;*/
            /*padding: 0;*/
            list-style: none;
            margin-bottom: 10px;
        }
        #buildStructure ul li .btn {
            display: inline-block;
            margin-right: 5px;
        }

        #subStructure {
            margin: 0;
            padding: 0;
        }
        #subStructure li {
            margin: 0;
            padding: 0;
            list-style: none;
        }
        #subStructure li {
            border-bottom: solid 1px #ccc;
            padding: 3px 0;
            cursor: ns-resize;
        }

        .box.box-bordered.box-color .slimScrollDiv {
            border-bottom: none;
        }

    </style>

<!-- dataTables -->
<!--<script src="js/plugins/datatable/jquery.dataTables.min.js"></script>-->
<!--<script src="js/plugins/datatable/TableTools.min.js"></script>-->
<!--<script src="js/plugins/datatable/ColReorder.min.js"></script>-->
<!--<script src="js/plugins/datatable/ColVis.min.js"></script>-->
<!--<script src="js/plugins/datatable/jquery.dataTables.columnFilter.js"></script>-->
<script src="website/js/selectnavpreview.js"></script>

<!-- colorbox -->
<script src="js/plugins/colorbox/jquery.colorbox-min.js"></script>
<!-- masonry -->
<script src="js/plugins/masonry/jquery.masonry.min.js"></script>
<!-- imagesloaded -->
<script src="js/plugins/imagesLoaded/jquery.imagesloaded.min.js"></script>

<!-- Plupload -->
<link rel="stylesheet" href="css/plugins/plupload/jquery.plupload.queue.css">
<!-- PLUpload -->
<script src="js/plugins/plupload/plupload.full.js"></script>
<script src="js/plugins/plupload/jquery.plupload.queue.js"></script>

</head>
<?php include('../webparts/navigation.php'); ?>
<body>
<div class="container-fluid" id="content">
    
	<div id="main">
		<div class="container-fluid">
			<div class="page-header">
				<div class="pull-left">
					<h1>Move Structure to Product Group</h1>
				</div>
				<div class="pull-right">
					<?php include('../webparts/website-left.php'); ?>
				</div>
			</div>
			<div class="breadcrumbs">
				<ul>
					<li>
						<a href="index.php">Dashboard</a>
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a>Move Structure to Product Group</a>
					</li>
				</ul>
			</div>
			
			<div class="row-fluid">
				<div class="span4">
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-checkbox"></i> Move Structure to Product Group</h3>
							<div class="actions">
							</div>
						</div>
                        <div class="box-content nopadding">

                            <form class="form-horizontal form-bordered form-validate" method="POST" action="" id="structureToAttributeForm">

                                <div class="control-group">
                                    <label class="control-label">Group
                                        <small>select the product group</small>
                                    </label>

                                    <div class="controls">

                                        <div id="buildStructure">
                                            <?php
                                            $TmpStr->buildStructure(0, NULL, 'mparentID', 'hide', true);
                                            ?>
                                        </div>

                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">Group
                                        <small>select the product group</small>
                                    </label>

                                    <div class="controls">
                                        <select name="atr_id">
                                            <?php
                                            $tableLength = count($attrGroups);
                                            for ($i = 0; $i < $tableLength; ++$i) {
                                                ?>
                                                <option
                                                    value="<?php echo $attrGroups[$i]['atr_id']; ?>"><?php echo $attrGroups[$i]['atrnam']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary">Apply Move</button>
                                </div>

                            </form>

                        </div>
					</div>
				</div>

                <div class="span8">




                </div>

			</div>

		</div>
	</div>
</div>


<script src="products/js/structure2attribute.js"></script>

</body>
</html>
