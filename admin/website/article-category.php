<?php 







require_once('../../config/config.php');



require_once('../patchworks.php'); 



require_once("../system/classes/categories.cls.php");



require_once("../system/classes/subcategories.cls.php");











$userAuth = new AuthDAO();



$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);



if ($loggedIn == 0) header('location: ../login.php');







$TblNam = 'article-types';



$TmpCat = new CatDAO();



$categoryRec = $TmpCat->select(NULL,$TblNam,NULL,NULL,true);







if (!isset($categoryRec->cat_id)) {



	



	$CatObj = new stdClass();



	$CatObj->cat_id = 0;



	$CatObj->tblnam = 'article-types';



	$CatObj->tbl_id = 0;



	$CatObj->catnam = 'Article Types';



	$CatObj->seourl = 'article-types';



	$CatObj->keywrd = 'Article Types';



	$CatObj->keydsc = 'Article Types';



	$CatObj->sta_id = 0;



	$Cat_ID = $TmpCat->update($CatObj);



	



} else {



	$Cat_ID = $categoryRec->cat_id;



}







$TmpSub = new SubDAO();



$subCategories = $TmpSub->selectByTableName($TblNam);







?>



<!doctype html>



<html>



<head>



<title>Article Categories</title>



<?php include('../webparts/headdata.php'); ?>



</head>



<?php include('../webparts/navigation.php'); ?>



<body class="theme-red">



<div class="container-fluid" id="content">



	<?php include('../webparts/website-left.php'); ?>



	<div id="main">



		<div class="container-fluid">



			<div class="page-header">



				<div class="pull-left">



					<h1>Website Articles Categories</h1>



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



						<a>Website</a>



						<i class="icon-angle-right"></i>



					</li>



					<li>



						<a href="website/article-category.php">Article Categories</a>



					</li>



				</ul>



			</div>



			<div class="row-fluid">



				<div class="span12">



					<div class="box box-color box-bordered">



						<div class="box-title">



							<h3>



								<i class="icon-comments"></i> Website Article Categories</h3>



							<div class="actions">



								<a href="website/article-category-edit.php?cat_id=<?php echo $Cat_ID; ?>" class="btn btn-mini" rel="tooltip" title="New Article"><i class="icon-plus"></i></a>



							</div>



						</div>



						<div class="box-content nopadding">



							<table class="table table-bordered table-striped table-highlight" id="articleCatTable">



							<thead>



								<tr>



									<th>Article Category Name</th>



                                    <th width="50"></th>



								</tr>



							</thead>



							<tbody id="sortBody">



                            <?php



                            $tableLength = count($subCategories);



                            for ($i=0;$i<$tableLength;++$i) {



                                ?>



                                <tr>



                                    <td><a href="website/article-category-edit.php?sub_id=<?php echo $subCategories[$i]['sub_id'] ?>&cat_id=<?php echo $subCategories[$i]['cat_id']; ?>"><?php echo $subCategories[$i]['subnam'] ?></a></td>



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















<script>







    $(function(){







        $('#sortBody').sortable({



            handle: ".sortOrder",



            stop: function( event, ui ) {







                var subLst = '';







                $('.sortOrder', $('#sortBody')).each(function(){







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



        });







    });







</script>











</body>



</html>



