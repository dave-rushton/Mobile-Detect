<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');


require_once("../system/classes/categories.cls.php");
require_once("../system/classes/subcategories.cls.php");
require_once("../attributes/classes/attrgroups.cls.php");
require_once("../products/classes/product_types.cls.php");
require_once("../products/classes/products.cls.php");
require_once("../products/classes/discounts.cls.php");


$TblNam = 'shopping-departments';
$CatDao = new CatDAO();
$category = $CatDao->select(NULL, $TblNam, NULL, NULL, true);
$TmpSub = new SubDAO();
$departments = $TmpSub->select($category->cat_id, NULL, NULL, NULL, false);


$TmpAtr = new AtrDAO();
$productGroups = $TmpAtr->select(NULL, 'PRODUCTGROUP');


$TmpPrt = new PrtDAO();
$productTypes = $TmpPrt->select(NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, false);


$TmpPrd = new PrdDAO();
$products = $TmpPrd->select(NULL, NULL, NULL, NULL, NULL, NULL, 'prdnam', false);



$Dis_ID = (isset($_REQUEST['dis_id']) && is_numeric($_REQUEST['dis_id'])) ? $_REQUEST['dis_id'] : NULL;
$TmpDis = new DisDAO();
if (!is_null($Dis_ID)) $DisObj = $TmpDis->select($Dis_ID, NULL, NULL, NULL, NULL, NULL, NULL, true);

?>
<!doctype html>
<html>
<head>
<title>Product Discount : <?php echo(isset($DisObj)) ? $DisObj->disnam : 'New Product Discount'; ?></title>
<?php include('../webparts/headdata.php'); ?>

<link rel="stylesheet" href="css/plugins/datepicker/datepicker.css">

<script src="js/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="js/system.date.js"></script>

<script src="ecommerce/js/discount-edit.js"></script>
</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
	<?php include('../webparts/website-left.php'); ?>
	<div id="main">
		<div class="container-fluid">
			<div class="page-header">
				<div class="pull-left">
					<h1>Product Discount</h1>
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
						<a>Products</a>
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a href="ecommerce/discounts.php">Product Discounts</a>
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a><?php echo(isset($DisObj)) ? $DisObj->disnam : 'New Product Discount'; ?></a>
					</li>
				</ul>
			</div>
			<div class="row-fluid">
				<div class="span6">
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-gift"></i> Product Discount</h3>
							<div class="actions">
								<a href="#" id="updateDiscountBtn" class="btn btn-mini" rel="tooltip" title="Update"><i class="icon-save"></i></a>
								<a href="#" id="deleteDiscountBtn" class="btn btn-mini" rel="tooltip" title="Delete"><i class="icon-trash"></i></a>
							</div>
						</div>
						<div class="box-content">
							<form action="ecommerce/discounts_script.php" id="discountForm" class="form-horizontal form-validate" data-returnurl="ecommerce/discounts.php">
								<input type="hidden" name="dis_id" id="id" value="<?php echo(isset($DisObj)) ? $DisObj->dis_id : '0'; ?>">
								<div class="control-group">
									<label class="control-label">Discount Name</label>
									<div class="controls">
										<input type="text" class="input-block-level" name="disnam" data-rule-required="true" data-rule-minlength="2" value="<?php echo(isset($DisObj)) ? $DisObj->disnam : ''; ?>">
									</div>
								</div>
                                <div class="control-group">
									<label class="control-label">Discount Code</label>
									<div class="controls">
										<input type="text" class="input-block-level" name="discod" data-rule-required="true" data-rule-minlength="2" value="<?php echo(isset($DisObj)) ? $DisObj->discod : ''; ?>">
									</div>
								</div>

                                <div class="hide">

                                    <div class="control-group">
                                        <label class="control-label">Department</label>
                                        <div class="controls">
                                            <select name="sub_id">
                                                <option value="0">N/A</option>
                                                <?php
                                                $tableLength = count($departments);
                                                for ($i=0;$i<$tableLength;++$i) {
                                                ?>
                                                <option value="<?php echo $departments[$i]['sub_id']; ?>" <?php if (isset($DisObj) && $departments[$i]['sub_id'] == $DisObj->sub_id) echo 'selected'; ?>><?php echo $departments[$i]['subnam']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label">Product Group</label>
                                        <div class="controls">
                                            <select name="atr_id">
                                                <option value="0">N/A</option>
                                                <?php
                                                $tableLength = count($productGroups);
                                                for ($i=0;$i<$tableLength;++$i) {
                                                ?>
                                                <option value="<?php echo $productGroups[$i]['atr_id']; ?>" <?php if (isset($DisObj) && $productGroups[$i]['atr_id'] == $DisObj->atr_id) echo 'selected'; ?>><?php echo $productGroups[$i]['atrnam']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                
                                <div class="control-group hide">
									<label class="control-label">Product Type</label>
									<div class="controls">
                                        <select name="prt_id">
                                        	<option value="0">N/A</option>
											<?php
											$tableLength = count($productTypes);
											for ($i=0;$i<$tableLength;++$i) {
											?>
<!--                                        	<option value="--><?php //echo $productTypes[$i]['prt_id']; ?><!--" --><?php //if (isset($DisObj) && $productTypes[$i]['prt_id'] == $DisObj->prt_id) echo 'selected'; ?><!-->--><?php //echo $productTypes[$i]['prtnam']; ?><!--</option>-->
                                            <?php } ?>
                                        </select>
									</div>
								</div>

                                <div class="control-group">
                                    <label class="control-label">Products<small>select products</small></label>
                                    <div class="controls">

                                        <select multiple="multiple" class="input-large" name="prd_idselect" id="prd_idselect">

                                            <?php
                                            $tableLength = count($products);
                                            for ($i=0;$i<$tableLength;++$i) {
                                                ?>


                                                <option value="<?php echo $products[$i]['prd_id'] ?>" <?php echo (isset($DisObj) && preg_match('/\b' . $products[$i]['prd_id'] . '\b/', $DisObj->prd_id) ) ? 'selected' : ''; ?>><?php echo $products[$i]['prtnam'].' > '.$products[$i]['prdnam'] ?></option>

                                            <?php } ?>

                                        </select>

                                        <input type="hidden" name="prd_id">

                                    </div>
                                </div>

                                <div class="control-group">
									<label class="control-label">Discount Type</label>
									<div class="controls">
                                        <select name="pctamt">
                                        	<option value="A" <?php if (isset($DisObj) && $DisObj->pctamt == 'A') echo 'selected'; ?>>Amount</option>
                                        	<option value="P" <?php if (isset($DisObj) && $DisObj->pctamt == 'P') echo 'selected'; ?>>Percentage</option>
                                        </select>
									</div>
								</div>
                                <div class="control-group">
									<label class="control-label">Discount Amount</label>
									<div class="controls">
                                        <input type="text" class="input-block-level" name="disamt" data-rule-required="true" data-rule-number="true" value="<?php echo(isset($DisObj)) ? $DisObj->disamt : ''; ?>">
									</div>
								</div>
                                
								<div class="control-group">
									<label class="control-label">Start Date</label>
									<div class="controls">
										<div class="input-append">
											<input type="text" name="begdat" value="<?php echo(isset($DisObj)) ? $DisObj->begdat : ''; ?>">
											<a href="#" id="clearBegDateBtn" class="btn" rel="tooltip" data-placement="top" data-original-title="Clear Start Date"><i class="icon-remove"></i></a>
										</div>
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">End Date</label>
									<div class="controls">
										<div class="input-append">
											<input type="text" name="enddat" value="<?php echo(isset($DisObj)) ? $DisObj->enddat : ''; ?>">
											<a href="#" id="clearEndDateBtn" class="btn" rel="tooltip" data-placement="top" data-original-title="Clear End Date"><i class="icon-remove"></i></a>
										</div>
									</div>
								</div>
								
                                
                                <div class="control-group">
									<label class="control-label">Total uses</label>
									<div class="controls">
                                        <input type="text" class="input-block-level" name="totuse" data-rule-required="true" data-rule-number="true" value="<?php echo(isset($DisObj)) ? $DisObj->totuse : '-1'; ?>">
									</div>
								</div>
                                <div class="control-group">
									<label class="control-label">Minimum Spend</label>
									<div class="controls">
                                        <input type="text" class="input-block-level" name="minamt" data-rule-required="true" data-rule-number="true" value="<?php echo(isset($DisObj)) ? $DisObj->minamt : '0'; ?>">
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
