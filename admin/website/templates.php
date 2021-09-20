<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("classes/template.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpTmp = new TplDAO();
$templates = $TmpTmp->select(NULL, false);

?>
<!doctype html>
<html>
<head>
<title>Website Template Listing</title>
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
					<h1>Website Templates</h1>
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
						<a href="website/templates.php">Templates</a>
					</li>
				</ul>
			</div>
			<div class="row-fluid">
				<div class="span12">

                    <div class="box box-color box-bordered">
                        <div class="box-title">
                            <h3>
                                <i class="icon-comments"></i> Website Templates</h3>
                            <div class="actions">
                                <a href="website/templates-edit.php" class="btn btn-mini" rel="tooltip" title="New Template"><i class="icon-plus"></i></a>
                            </div>
                        </div>
                        <div class="box-content nopadding">
                            <table class="table table-bordered table-striped table-highlight" id="articleTable">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>File</th>
                                    <th width="20"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $tableLength = count($templates);
                                for ($i=0;$i<$tableLength;++$i) {
                                    ?>
                                    <tr>
                                        <td><a href="website/templates-edit.php?tpl_id=<?php echo $templates[$i]['tpl_id'] ?>"><?php echo $templates[$i]['tplnam']; ?></a></td>
                                        <td><?php echo $templates[$i]['tplfil'] ?></td>
                                        <td><?php echo ($templates[$i]['tpldef'] == 1) ? '<i class="icon icon-check"></i>' : ''; ?></td>
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
