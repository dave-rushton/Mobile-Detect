<?php
require_once("../../config/config.php");
require_once("../../admin/patchworks.php");
require_once("../../admin/website/classes/pageelements.cls.php");
require_once("../../admin/gallery/classes/uploads.cls.php");

$EleDao = new PelDAO();

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;

$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

$UplDao = new UplDAO();
$eleVarArr = json_decode($EleObj->elevar, true);

$Gal_ID = NULL;
$i = 0;

for ($i = 0; $i < count($eleVarArr); ++$i) {
	foreach($eleVarArr[$i] as $key => $item) {
		if ($item == 'gal_id') $Gal_ID = $eleVarArr[$i]['value'];
	}
}

if (is_null($Gal_ID)) die();

$UplObj = $UplDao->select(NULL, 'WEBGALLERY', $Gal_ID);

?>

<div id="myCarousel" class="carousel slide">
	<div class="carousel-inner">
		
		<?php
		$tableLength = count($UplObj);
		for ($i=0;$i<$tableLength;++$i) {
		?>
		<div class="item <?php if ($i==0) echo 'active'; ?>">
			<img src="<?php echo 'uploads/images/'.$UplObj[$i]['filnam']; ?>" alt="">
			<div class="container">
				<div class="carousel-caption">
					<h1><?php echo $UplObj[$i]['uplttl']; ?></h1>
					<p class="lead"><?php echo $UplObj[$i]['upldsc']; ?></p>
					<a class="btn btn-large btn-primary" href="#">Learn more</a>
				</div>
			</div>
		</div>
		<?php } ?>
		
		
	</div>
	<a class="left carousel-control" href="#myCarousel" data-slide="prev"> <span class="icon-prev"></span> </a> 
	<a class="right carousel-control" href="#myCarousel" data-slide="next"> <span class="icon-next"></span> </a>
</div>