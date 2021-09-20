<?php 
include('../config/config.php'); 
include('patchworks.php'); 

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: login.php');

require_once('system/classes/messages.cls.php');
$MsgDao = new MsgDAO();
$messages = $MsgDao->select(NULL, NULL, 'FORM', NULL, 0, false);
$warnings = $MsgDao->select(NULL, NULL, 'WARNING', NULL, 0, false);

require_once('system/classes/related.cls.php');
$RelDao = new RelDAO();
$employess = $RelDao->select(NULL, 'USR', 40, 'EMP', NULL, false);

require_once("system/classes/places.cls.php");
$TmpPla = new PlaDAO();
$projects = NULL;
$projects = $TmpPla->selectPlaceBookings(NULL, 'PROJECT', NULL, NULL, 0, false); 

?>
<!doctype html>
<html>
<head>
<title><?php echo $patchworks->customerName; ?> Administration</title>
<?php include('webparts/headdata.php'); ?>

</head>
<?php include('webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid nav-hidden" id="content">
	<?php include('webparts/website-left.php'); ?>
	<div id="main">
		<div class="container-fluid">
			<div class="page-header">
				<div class="pull-left">
					<h1>Welcome: <?php echo $_SESSION['s_usrnam']; ?></h1>
				</div>
				<div class="pull-right">
					<?php //include('webparts/index-info.php'); ?>
				</div>
			</div>

            <div class="row-fluid">
                <div class="span6">
                    <div class="alert alert-info">

                        <h3>Your Website</h3>

                        <p>Control the content on your website could not be simpler. Using the links below control and manage every element of your website.</p>

                        <ul class="tiles selfclear" id="indexTiles">
                            <li class="blue long">
                                <a href="<?php echo $patchworks->webRoot; ?>" target="_blank"><span class="nopadding"><h5>Launch Website</h5><p>View website in a new tab/window</p></span><span class="name"><i class="icon-sitemap"></i><span class="right">Launch</span></span></a>
                            </li>
                            <li class="blue long">
                                <a href="website/sitemap.php"><span class="nopadding"><h5>Sitemap</h5><p>Create, amend and delete website pages and content.</p></span><span class="name"><i class="icon-sitemap"></i><span class="right">Website</span></span></a>
                            </li>
                            <li class="magenta long">
                                <a href="gallery/galleries.php"><span class="nopadding"><h5>Galleries</h5><p>Upload and edit images for website galleries.</p></span><span class="name"><i class="icon-picture"></i><span class="right">Website</span></span></a>
                            </li>
                            <li class="green long">
                                <a href="downloads/libraries.php"><span class="nopadding"><h5>Libraries</h5><p>Upload and manage website downloads.</p></span><span class="name"><i class="icon-download"></i><span class="right">Website</span></span></a>
                            </li>
                            <li class="orange long">
                                <a href="website/forms.php"><span class="nopadding"><h5>Forms</h5><p>Build custom contact forms for your website.</p></span><span class="name"><i class="icon-tasks"></i><span class="right">Website</span></span></a>
                            </li>
                            <li class="red long">
                                <a href="website/articles.php"><span class="nopadding"><h5>News</h5><p>Publish a blog or news feed to your website.</p></span><span class="name"><i class="icon-comments"></i><span class="right">Website</span></span></a>
                            </li>

                            <li class="teal long">
                                <a href="website/generic-content.php"><span class="nopadding"><h5>Generic Content</h5><p>Manage site wide content from a single piece of content</p></span><span class="name"><i class="icon-sitemap"></i><span class="right">Website</span></span></a>
                            </li>
                            <li class="red long">
                                <a href="website/article-category.php"><span class="nopadding"><h5>Article Categories</h5><p>Group articles based on a category.</p></span><span class="name"><i class="icon-comments"></i><span class="right">Website</span></span></a>
                            </li>

                            <li class="lightgrey long">
                                <a href="locations/locations.php"><span class="nopadding"><h5>Locations</h5><p>Manage website map location pointers, ideal for contact us pages.</p></span><span class="name"><i class="icon-map-marker"></i><span class="right">Website</span></span></a>
                            </li>
                            <li class=" green long">
                                <a href="products/producttypes.php"><span class="nopadding"><h5>Products</h5><p>Shows All Products</p></span><span class="name"><i class="icon-comments"></i><span class="right">Website</span></span></a>
                            </li>
                            <li class=" orange long">
                                <a href="products/structure.php"><span class="nopadding"><h5>Structure</h5><p>Add categories and change sort order of products</p></span><span class="name"><i class="icon-comments"></i><span class="right">Website</span></span></a>
                            </li>
                            <li class=" teal long" style="background-color: #111">
                                <a href="website/copycontent.php"><span class="nopadding"><h5>Copy Content</h5><p>Copy content from an <br/>sitemap page</p></span><span class="name"><i class="icon-comments"></i><span class="right">Website</span></span></a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="span3">
                    <div class="alert">

                        <h3>Reporting</h3>

                        <p>Website form submissions, error logs and website assets</p>

                        <ul class="tiles selfclear" id="indexTiles">

                            <li class="lime">
                                <a href="website/webcontacts.php"><span class='count'><i class="icon-envelope"></i> <?php echo ( count($messages) > 0 ) ? count($messages) : ''; ?></span><span class='name'>Contact</span></a>
                            </li>
<!--                            <li class="green">-->
<!--                                <a href="website/statistics.php"><span><i class="icon-bar-chart"></i></span><span class='name'>Website Statistics</span></a>-->
<!--                            </li>-->
                            <li class="brown">
                                <a href="system/warnings.php"><span class='count'><i class="icon-bolt"></i> <?php echo ( count($warnings) > 0 ) ? count($warnings) : ''; ?></span><span class='name'>Warnings</span></a>
                            </li>
                            <li class="teal">
                                <a href="system/filetree.php"><span class='count'><i class="icon-cloud-upload"></i></span><span class='name'>Uploads</span></a>
                            </li>

                        </ul>

                    </div>
                </div>
                <div class="span3">
                    <div class="alert alert-success">

                        <h3>Profile</h3>

                        <p>Manage your profile</p>

                        <ul class="tiles selfclear" id="indexTiles">

                            <li class="green long">
                                <a href="system/user-account.php"><span class="nopadding"><h5>Profile</h5><p>Edit your profile.</p></span><span class="name"><i class="icon-user"></i><span class="right">Admin</span></span></a>
                            </li>

                            <li class="red">
                                <a href="logout.php"><span><i class="icon-signout"></i></span><span class='name'>Sign out</span></a>
                            </li>
                        </ul>

                    </div>
                </div>
            </div>

		</div>
	</div>
</div>
</body>
</html>
