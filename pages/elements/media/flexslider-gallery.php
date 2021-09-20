<?php
require_once("../../../config/config.php");
require_once("../../../admin/patchworks.php");
require_once("../../../admin/website/classes/pageelements.cls.php");
require_once("../../../admin/gallery/classes/uploads.cls.php");

$EleDao = new PelDAO();

$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;

$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

$UplDao = new UplDAO();
$eleVarArr = json_decode($EleObj->elevar, true);

$ImgSiz = '';
$Gal_ID = NULL;
$FulWid = false;

$Gal_ID = $EleDao->getVariable($EleObj, 'gal_id', false );
$ImgSiz = $EleDao->getVariable($EleObj, 'imgsiz', false );
$FulWid = $EleDao->getVariable($EleObj, 'fulwid', false );

$DirCon = $EleDao->getVariable($EleObj, 'dircon', false );
$SldSel = $EleDao->getVariable($EleObj, 'sldsel', false );
$nopadding = $EleDao->getVariable($EleObj, 'nopadding', false );
$nomargin = $EleDao->getVariable($EleObj, 'nomargin', false );

$nomargin = ($nomargin==1)?"nomargin":"";
$nopadding = ($nopadding==1)?"nopadding":"";

if (is_null($DirCon)) $DirCon = 'off';
if (is_null($SldSel)) $SldSel = 'off';

if (is_null($Gal_ID)) die();

$UplObj = $UplDao->select(NULL, 'WEBGALLERY', $Gal_ID);

?>


<div class="section <?php echo $nopadding." ".$nomargin; ?>">
    <div class="flexslider" data-dircon="<?php echo $DirCon; ?>" data-sldsel="<?php echo $SldSel; ?>">
        <ul class="slides">
            <?php
            $tableLength = count($UplObj);
            for ($i=0;$i<$tableLength;++$i) {
            ?>
            <?php //echo $UplObj[$i]['upldsc']; ?>
            <li>

                <div class="homeslide">
                    <img src="<?php echo 'uploads/images/'.$ImgSiz.'/'.$UplObj[$i]['filnam']; ?>" alt="">

                </div>

            </li>
            <?php } ?>
        </ul>
    </div>
</div>


