<?php 

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once('../products/classes/structure.cls.php');

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpStr = new StrDAO();


?>
<!doctype html>
<html>
<head>
<title>Shop Structure</title>
<?php include('../webparts/headdata.php'); ?>


    <style>

        #buildStructure {

            min-height: 100px;

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
            list-style: none;
            border-bottom: solid 1px #ccc;
            padding: 3px 0;
        }

        #subStructure li a {
            display: inline-block;
            float: right;
        }

    </style>

<script src="website/js/selectnavpreview.js"></script>

</head>
<?php include('../webparts/navigation.php'); ?>
<body>
<div class="container-fluid" id="content">
    
	<div id="main">
		<div class="container-fluid">
			<div class="page-header">
				<div class="pull-left">
					<h1>Structure</h1>
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
						<a>Structure</a>
					</li>
				</ul>
			</div>
			
			<div class="row-fluid">
				<div class="span4">
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-checkbox"></i> Create Main Category</h3>
							<div class="actions">
<!--								<a href="#" class="btn btn-mini" id="createStructureBtn"><i class="icon-save"></i></a>-->
                                <a href="#" class="btn btn-mini" id="createStructureBtn"><i class="icon-plus-sign"></i></a>
							</div>
						</div>
<!--						<div class="box-content nopadding">-->
<!--							<form action="products/structure_script.php" id="structureForm" class="form-horizontal form-bordered">-->
<!--								<input type="hidden" name="str_id" value="0">-->
<!--								<input type="hidden" name="sta_id" value="0">-->
<!--								<input type="hidden" name="tblnam" value="">-->
<!--								<input type="hidden" name="tbl_id" value="0">-->
<!--								<div class="control-group">-->
<!--									<label class="control-label">Name</label>-->
<!--									<div class="controls">-->
<!--										<input type="text" class="input-large" name="strnam" value="">-->
<!--									</div>-->
<!--								</div>-->
<!--							</form>-->
<!--						</div>-->
                        <div class="box-content">

                            <div id="buildStructure">
                            <?php
                            //$TmpStr->buildStructure(0,NULL, true);
                            ?>
                            </div>

                        </div>
					</div>
				</div>

                <div class="span6">

                    <div class="box box-color box-bordered">
                        <div class="box-title">
                            <h3>
                                <i class="icon-checkbox"></i> Sort</h3>
                            <div class="actions">
                            </div>
                        </div>
                        <div class="box-content" id="subBlockOut">

                            <ul id="subStructure">

                            </ul>

                        </div>
                    </div>

                </div>

			</div>
			
		</div>
	</div>
</div>



<div class="modal hide fade" id="structureModal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h3>Product Search</h3>
    </div>

    <form id="structureModalForm" class="form-horizontal form-bordered form-validate">

        <input type="hidden" name="str_id" value="0">
        <input type="hidden" name="sta_id" value="0">
        <input type="hidden" name="tblnam" value="STRUCTURE">
        <input type="hidden" name="tbl_id" value="0">

        <input type="hidden" name="strtxt" value="NO TEXT">
        <input type="hidden" name="strimg" value="">

        <div class="modal-body" style="padding: 0">

            <div class="control-group">
                <label class="control-label">Parent</label>
                <div class="controls">

                    <div id="modalStructure">
                    <?php
                    //$TmpStr->buildStructure(NULL, NULL, 'parentID', 'hide', true);
                    ?>
                    </div>

                </div>
            </div>

            <div class="control-group">
                <label class="control-label">Name</label>

                <div class="controls">
                    <input type="text" class="input-block-level" name="strnam" value="" required>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">SEO URL
                </label>

                <div class="controls">
                    <input type="text" class="input-block-level" name="seourl" value="" required>
                </div>
            </div>


            <div class="control-group">
                <label class="control-label">Keywords
                </label>

                <div class="controls">
                    <input type="text" class="input-block-level" name="keywrd" value="">
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">Key Description</label>
                <div class="controls">
                    <textarea class="input-block-level" name="keydsc" value=""></textarea>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">Sort Order
                </label>

                <div class="controls">
                    <input type="text" class="input-block-level" name="srtord" value="" required>
                </div>
            </div>

        </div>
        <div class="modal-footer">
            <a href="#" class="btn" data-dismiss="modal"><i class="icon-remove"></i> Cancel</a>
            <button type="submit" class="btn btn-primary" name="action" value="update" id="updateStructureBtn"><i
                    class="icon-save"></i> Update
            </button>
        </div>

    </form>


</div>

<script src="products/js/structure.js"></script>

</body>
</html>
