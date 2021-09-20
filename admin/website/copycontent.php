<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("classes/articles.cls.php");
require_once("classes/page.handler.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$pageHandler = new pageHandler();
$menuHTML = $pageHandler->getMenu(NULL, NULL, NULL, NULL, 'hide menuUL');

?>
<!doctype html>
<html>
<head>
<title>Website Content Copy</title>
<?php include('../webparts/headdata.php'); ?>

<script src="website/js/selectnavpreview.js"></script>

<script>

	$(function(){
		$('ul.menuUL').each(function() {

			var selectID = $(this).parent().data('htmlname') + $('ul.menuUL').index( $(this) );

			$(this).attr("id", selectID );

			selectnav(selectID, {
				name: $(this).parent().data('htmlname'),
				label: 'Display Whole Menu',
				nested: true,
				indent: '-'
			});

			$('.selectnav').change();
		});

		copyContentForm = $('#copyContentForm');

		copyContentForm.submit(function(e){


		})

		$('[name="frmpag"]').val( $('#frmPag').val() );
		$('[name="to_pag"]').val( $('#to_Pag').val() );

		$('[name="to_pag"]').focus();

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
					<h1>Website Content Copy</h1>
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
						<a href="website/contentcopy.php">Content Copy</a>
					</li>
				</ul>
			</div>
			<div class="row-fluid">
				<div class="span12">

					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-comments"></i> Copy Page Content</h3>
						</div>
						<div class="box-content">


							<?php
							if (isset($_GET['error'])) {

								if ($_GET['error'] == 'template') {

									?>
									<div class="alert alert-danger">
										<p><strong>ERROR:</strong> Can only copy content to same template</p>
									</div>
									<?php

								} else if ($_GET['error'] == 'page') {
									?>
									<div class="alert alert-danger">
										<p><strong>ERROR:</strong> Please select a from and to page</p>
									</div>
									<?php
								}
							}
							?>

							<form action="website/copycontent_script.php" method="get" id="copyContentForm" class="form-horizontal">
								<div class="control-group">
									<label class="control-label">From Page</label>
									<div class="controls">
										<div class="menuMarkUpWrapper" data-htmlname="frmpag">
											<?php echo $menuHTML; ?>
										</div>

										<input type="hidden" id="frmPag" value="<?php if (isset($_GET['frmpag'])) echo $_GET['frmpag']; ?>">

									</div>
								</div>

								<div class="control-group">
									<label class="control-label">To Page</label>
									<div class="controls">
										<div class="menuMarkUpWrapper" data-htmlname="to_pag">
											<?php echo $menuHTML; ?>
										</div>

										<input type="hidden" id="to_Pag" value="<?php if (isset($_GET['to_pag'])) echo $_GET['to_pag']; ?>">
									</div>
								</div>

								<div class="form-actions">
									<button type="submit" typeof="submit" class="btn btn-primary"><i class="icon-save"></i> Update </button>
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
