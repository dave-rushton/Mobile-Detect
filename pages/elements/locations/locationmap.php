<?php

require_once("../../../config/config.php" );
require_once("../../../admin/patchworks.php" );
require_once("../../../admin/website/classes/pageelements.cls.php");


$EleDao = new PelDAO();
$Pel_ID = (isset($_REQUEST['pel_id']) && is_numeric($_REQUEST['pel_id'])) ? $_REQUEST['pel_id'] : NULL;
$EleObj = $EleDao->select($Pel_ID, NULL, NULL, true);
if (!$EleObj) die();

?>

<?php
if (!isset($_GET['locseo'])) {
    ?>

    <style>

        .infobox-wrapper {
            display: none;
        }

        .infoBox {
            /*border: 2px solid black;*/
            margin-top: 8px;
            background: #cecece;
            color: #000;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            padding: 5px 10px;
            width: 280px;
        }

        .infoBox .mainImg {
            max-width: 260px;
            margin-top: 5px;
            margin-bottom: 10px;
        }

    </style>

    <form class="form-vertical" id="locationsForm">

        <input type="hidden" id="GooLat">
        <input type="hidden" id="GooLng">
        <input type="hidden" id="SeoUrl" value="<?php echo $_GET['seourl']; ?>">

        <div class="pw-form">
            <div class="pw-form-header">
                <h3></h3>
            </div>
            <div class="pw-form-content">
                <div class="control-group form-group">
                    <div class="controls">
                        <label>Search</label>
                        <input type="text" name="searchaddress" id="locPstCod" class="form-control">
                    </div>
                </div>
                <div class="control-group form-group">
                    <div class="controls">
                        <label>Distance</label>

                        <select name="searchdistance" id="plaDis" class="form-control">
                            <option value="9999" selected>Any Distance</option>
                            <option value="1">1</option>
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>

                    </div>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">SEARCH</button>
            </div>
        </div>
    </form>

    <div class="locationsMap img-thumbnail" id="map_canvas" style="margin-top: 20px; width: 100%; height: 550px;">
    </div>

<?php
} else {

    require_once("../../admin/system/classes/places.cls.php");
    $TmpPla = new PlaDAO();
    $locationRec = $TmpPla->selectBySeo($_GET['locseo']);
    if (isset($locationRec->planam)) {

        ?>

        <h1><?php echo $locationRec->planam; ?></h1>

        <p>
            <?php echo ($locationRec->adr1 != '') ? $locationRec->adr1 . '<br>' : ''; ?>
            <?php echo ($locationRec->adr2 != '') ? $locationRec->adr2 . '<br>' : ''; ?>
            <?php echo ($locationRec->adr3 != '') ? $locationRec->adr3 . '<br>' : ''; ?>
            <?php echo ($locationRec->adr4 != '') ? $locationRec->adr4 . '<br>' : ''; ?>
            <?php echo ($locationRec->pstcod != '') ? $locationRec->pstcod : ''; ?>
        </p>

        <p>
            <?php echo ($locationRec->platel != '') ? '<i class="fa fa-phone" style="width: 20px;"></i> ' . $locationRec->platel . '<br>' : ''; ?>
            <?php echo ($locationRec->plamob != '') ? '<i class="fa fa-fax" style="width: 20px;"></i> ' . $locationRec->plamob . '<br>' : ''; ?>
            <?php echo ($locationRec->plaema != '') ? '<i class="fa fa-envelope" style="width: 20px;"></i> <a href="mailto:' . $locationRec->plaema . '">' . $locationRec->plaema . '</a><br>' : ''; ?>
            <?php echo ($locationRec->plaurl != '') ? '<i class="fa fa-globe" style="width: 20px;"></i> <a href="' . $locationRec->plaurl . '">' . str_replace('https://','',str_replace('http://','',$locationRec->plaurl)) . '</a>' : ''; ?>
        </p>

        <div class="alert alert-warning"><?php echo $patchworks->getJSONVariable($locationRec->platxt, 'cusfld1', false); ?></div>
        <div class="alert alert-info"><?php echo $patchworks->getJSONVariable($locationRec->platxt, 'cusfld2', false); ?></div>

    <?php
    }
}
?>