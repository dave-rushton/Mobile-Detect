<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("../ecommerce/classes/order.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

?>
<!doctype html>
<html>
<head>
<title>eCommerce Dashboard</title>
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
					<h1>Products Dashboard</h1>
				</div>
				<div class="pull-right">
					<?php //include('../webparts/sales-info.php'); ?>
				</div>
			</div>
			<div class="breadcrumbs">
				<ul>
					<li>
						<a href="index.php">Dashboard</a>
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a>Products Dashboard</a>
					</li>
				</ul>
			</div>
			<div class="row-fluid">

				<div class="span12">


                    <ul class="tiles selfclear" id="indexTiles">

                        <li class="blue long">
                            <a href="products/producttypes.php"><span class="nopadding"><h5>Products</h5><p>Create, Amend and Delete Product Information</p></span><span class="name"><i class="icon-sitemap"></i></a>
                        </li>
                        <li class="green long">
                            <a href="products/structure.php"><span class="nopadding"><h5>Shop Structure</h5><p>Create, Amend and Delete Shop Heirachy</p></span><span class="name"><i class="icon-sitemap"></i></a>
                        </li>
                        <li class="red long">
                            <a href="products/priceband-edit.php"><span class="nopadding"><h5>Price Bands</h5><p>Create, Amend and Delete Price Bands</p></span><span class="name"><i class="icon-sitemap"></i></a>
                        </li>

                    </ul>

				</div>
			</div>
			
		</div>
	</div>
</div>
</body>
</html>
