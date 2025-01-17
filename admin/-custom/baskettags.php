<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("../system/classes/subcategories.cls.php");
require_once("../system/classes/categories.cls.php");


//$userAuth = new AuthDAO();
//$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
//if ($loggedIn == 0) header('location: ../login.php');
//
//$TblNam = 'shopping-departments';
//$CatDao = new CatDAO();
//$category = $CatDao->select(NULL, $TblNam, NULL, NULL, true);
//$TmpSub = new SubDAO();
//$subCategories = $TmpSub->select($category->cat_id, NULL, NULL, NULL, false);


$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TblNam = 'basket-tags';
$TmpCat = new CatDAO();
$categoryRec = $TmpCat->select(NULL,$TblNam,NULL,NULL,true);

if (!$categoryRec) {

    $CatObj = new stdClass();

    $CatObj->cat_id = 0;
    $CatObj->tblnam = 'basket-tags';
    $CatObj->tbl_id = 0;
    $CatObj->catnam = 'Basket Tags';
    $CatObj->seourl = 'basket-tags';
    $CatObj->keywrd = 'basket-tags';
    $CatObj->keydsc = 'Basket Tags';
    $CatObj->sta_id = 0;
    $Cat_ID = $TmpCat->update($CatObj);

    $categoryRec = $TmpCat->select(NULL,$TblNam,NULL,NULL,true);

} else {
    $Cat_ID = $categoryRec->cat_id;
}

$TmpSub = new SubDAO();
$subCategories = $TmpSub->selectByTableName($TblNam, NULL);

?>
<!doctype html>
<html>
<head>
<title>Basket Tags</title>
<?php include('../webparts/headdata.php'); ?>

<script>

$(function(){

	$('.sortOrder').click(function(e){
		e.preventDefault();
	});
	
	$('#basketTagsBody').sortable({
		handle: ".sortOrder",
		stop: function( event, ui ) {
			
			var subLst = '';
			
			$('.sortOrder', $('#basketTagsBody')).each(function(){
			
				subLst += (subLst == '') ? $(this).data('sub_id') : ',' + $(this).data('sub_id');
				
			});
			
			$.ajax({
				url: 'system/subcategories_script.php',
				data: 'action=resort&ajax=true&sub_id=' + subLst,
				type: 'POST',
				async: false,
				success: function( data ) {

					var result = JSON.parse(data);
					
					$.msgGrowl ({
						type: result.type
						, title: result.title
						, text: result.description
					});
	
				},
				error: function (x, e) {
					throwAjaxError(x, e);
				}
			});
			
		} 
	}).disableSelection();
	
	
	
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
					<h1>Basket Tags</h1>
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
						<a href="custom/baskettags.php">Basket Tags</a>
					</li>
				</ul>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-comments"></i> Basket Tags</h3>
							<div class="actions">
								<a href="custom/baskettags-edit.php?cat_id=<?php echo $categoryRec->cat_id; ?>" class="btn btn-mini" rel="tooltip" title="New Basket Tag"><i class="icon-plus"></i></a>
							</div>
						</div>
						<div class="box-content nopadding">
							<table class="table table-bordered table-striped table-highlight" id="basketTagsTable">
							<thead>
								<tr>
									<th>Basket Tag Name</th>
									<th width="50"></th>
								</tr>
							</thead>
							<tbody id="basketTagsBody">
								<?php
								$tableLength = count($subCategories);
								for ($i=0;$i<$tableLength;++$i) {
								?>
								<tr>
									<td width="100%;"><a href="custom/baskettags-edit.php?sub_id=<?php echo $subCategories[$i]['sub_id'] ?>&cat_id=<?php echo $subCategories[$i]['cat_id']; ?>"><?php echo $subCategories[$i]['subnam']; ?> <?php if ($subCategories[$i]['sta_id'] == 1) { echo '<div class="badge badge-important">INACTIVE</div>';} ?></a></td>
									<td><a href="#" class="sortOrder" data-sub_id="<?php echo $subCategories[$i]['sub_id'] ?>"><i class="icon-reorder"></i></a></td>
								</tr>
								
								<?php } ?>
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
