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

$ImgSiz = '';
$Gal_ID = NULL;
$i = 0;

for ($i = 0; $i < count($eleVarArr); ++$i) {
	foreach($eleVarArr[$i] as $key => $item) {
		if ($item == 'gal_id') $Gal_ID = $eleVarArr[$i]['value'];
        if ($item == 'imgsiz') $ImgSiz = $eleVarArr[$i]['value'];
	}
}

if (is_null($Gal_ID)) die();

$UplObj = $UplDao->select(NULL, 'WEBGALLERY', $Gal_ID);

?>

<div class="flexslider">
	<ul class="slides">
		<?php
		$tableLength = count($UplObj);
		for ($i=0;$i<$tableLength;++$i) {
		?>
		<?php //echo $UplObj[$i]['upldsc']; ?>
		<li>

            <div class="homeslide">

                <img src="<?php echo 'uploads/images/'.$ImgSiz.'/'.$UplObj[$i]['filnam']; ?>" alt="<?php echo $UplObj[$i]['uplttl']; ?>" />

                <?php
                if ($UplObj[$i]['uplttl'] != '') {
                ?>

                    <div class="slidetext">
                        <div class="slideinner">

                            <h1><?php echo $UplObj[$i]['uplttl']; ?></h1>

                            <p><?php echo $UplObj[$i]['upldsc']; ?></p>

                        </div>
                    </div>

                <?php
                }
                ?>

            </div>

        </li>
		<?php } ?>
	</ul>
</div>