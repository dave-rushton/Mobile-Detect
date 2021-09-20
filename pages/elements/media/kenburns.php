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

if (is_null($Gal_ID)) die();

$UplObj = $UplDao->select(NULL, 'WEBGALLERY', $Gal_ID);

?>

<?php
if (!$FulWid) {
    ?>

    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">

    <?php
}
?>

<div style="position: relative; width: 100%; min-height: 100vh;">
<div id="element">

    <?php
    $tableLength = count($UplObj);
    for ($i=0;$i<$tableLength;++$i) {
    ?>

        <img src="<?php echo 'uploads/images/'.$ImgSiz.'/'.$UplObj[$i]['filnam']; ?>" alt="<?php echo $UplObj[$i]['uplttl']; ?>" />

    <?php } ?>

</div>
</div>


<?php
if (!$FulWid) {
    ?>
                </div>
            </div>
        </div>
    </div>

    <?php
}
?>
<script src="pages/js/velocity.min.js"></script>
<script src="pages/js/jquery.kenburnsy.min.js"></script>
<script>

    $(function() {
        $("#element").kenburnsy({
            fullscreen: true
        });
    });

</script>