<?php

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("../system/classes/places.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpPla = new PlaDAO();

$eventID = (isset($_GET['evt_id']) && is_numeric($_GET['evt_id'])) ? $_GET['evt_id'] : NULL;
$locationRec = NULL;
if (!is_null($eventID)) $locationRec = $TmpPla->select($eventID, NULL, NULL, NULL, NULL, true);

$editLocationID = (isset($_GET['pla_id']) && is_numeric($_GET['pla_id'])) ? $_GET['pla_id'] : NULL;
$locationRec = NULL;
if (!is_null($editLocationID)) $locationRec = $TmpPla->select($editLocationID, NULL, NULL, NULL, NULL, true);


// GAL SELECT HTML


$qryArray = array();
$sql = 'SELECT u.*, g.galnam, g.imgsiz FROM uploads u INNER JOIN gallery g ON g.gal_id = u.tbl_id WHERE u.tblnam = :tblnam ORDER BY g.galnam, srtord ASC';

$qryArray['tblnam'] = 'WEBGALLERY';
$galleryImages = $patchworks->run($sql, $qryArray);

$galleryHTML = '<option>No Image</option>';

$GalNam = '';
$tableLength = count($galleryImages);
for ($i = 0; $i < $tableLength; ++$i) {

    if ($GalNam != $galleryImages[$i]['galnam']) {

        $GalNam = $galleryImages[$i]['galnam'];

        if ($i > 0) $galleryHTML .= '</optgroup>';
        $galleryHTML .= '<optgroup label="' . $GalNam . '">';

    }

    if (isset($locationRec->platxt) && $patchworks->getJSONVariable($locationRec->platxt, 'imgurl', false) == 'uploads/images/'.$galleryImages[$i]['filnam']) {
        $selected = 'selected';
    }
    else {
        $selected = '';
    }

    $galleryHTML .= '<option '.$selected.' value="uploads/images/' . $galleryImages[$i]['filnam'] . '" data-imgsiz="' . $galleryImages[$i]['imgsiz'] . '">' . $galleryImages[$i]['uplttl'] . '</option>';
}
$galleryHTML .= '</optgroup>';


?>
<!doctype html>
<html>
<head>
    <title>Location :<?php echo ($locationRec) ? $locationRec->planam : 'New Location'; ?></title>
    <?php include('../webparts/headdata.php'); ?>


    <!-- colorbox -->
    <link rel="stylesheet" href="css/plugins/colorbox/colorbox.css">

    <!-- colorbox -->
    <script src="js/plugins/colorbox/jquery.colorbox-min.js"></script>
    <!-- masonry -->
    <script src="js/plugins/masonry/jquery.masonry.min.js"></script>
    <!-- imagesloaded -->
    <script src="js/plugins/imagesLoaded/jquery.imagesloaded.min.js"></script>

    <!-- Plupload -->
    <link rel="stylesheet" href="css/plugins/plupload/jquery.plupload.queue.css">
    <!-- PLUpload -->
    <script src="js/plugins/plupload/plupload.full.js"></script>
    <script src="js/plugins/plupload/jquery.plupload.queue.js"></script>

    <script src="js/plugins/fileupload/bootstrap-fileupload.min.js"></script>

    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBs-E6B6RubDojb3UZ9DotE_Iq_I2dW5qg&sensor=false"></script>
    <script src="js/plugins/gmap/gmap3.min.js"></script>
    <script src="js/plugins/gmap/gmap3-menu.js"></script>
    <script src="js/plugins/datatable/jquery.dataTables.min.js"></script>

    <!--<script src="js/plugins/tiny_mce/tiny_mce.js"></script>-->
    <script src="js/plugins/tinymce/tinymce.min.js"></script>

    <script src="locations/js/locations-edit.js"></script>
</head>
<?php include('../webparts/navigation.php'); ?>
<body>
<div class="container-fluid" id="content">
    <?php include('../webparts/website-left.php'); ?>
    <div id="main">
        <div class="container-fluid">
            <div class="page-header">
                <div class="pull-left">
                    <h1>Location : <?php echo ($locationRec) ? $locationRec->planam : 'New Location'; ?></h1>
                </div>
                <div class="pull-right">
                    <?php include('../webparts/index-info.php'); ?>
                </div>
            </div>
            <div class="breadcrumbs">
                <ul>
                    <li><a href="index.php">Dashboard</a> <i class="icon-angle-right"></i></li>
                    <li><a href="locations/locations.php">Locations</a> <i class="icon-angle-right"></i></li>
                    <li><a><?php echo ($locationRec) ? $locationRec->planam : 'New Location'; ?></a></li>
                </ul>
            </div>

            <div class="row-fluid">
                <form action="system/places_script.php" id="locationForm" class="form-horizontal form-bordered"
                      data-returnurl="locations/locations.php">
                    <div class="span6">
                        <div class="box box-color box-bordered">
                            <div class="box-title">
                                <h3><i class="icon-map-marker"></i> Location</h3>

                                <div class="actions">
                                    <a href="#" id="deleteLocationBtn" class="btn btn-mini" rel="tooltip"
                                       title="Delete"><i class="icon-trash"></i></a>
                                </div>
                            </div>
                            <div class="box-content nopadding">
                                <input type="hidden" name="pla_id" id="id"
                                       value="<?php echo ($locationRec) ? $locationRec->pla_id : '0'; ?>">
                                <input type="hidden" name="tblnam" value="LOCATION">
                                <input type="hidden" name="tbl_id"
                                       value="<?php echo ($locationRec) ? $locationRec->pla_id : '0'; ?>">

                                <div class="control-group">
                                    <label class="control-label">Company Name</label>

                                    <div class="controls">
                                        <input type="text" class="input-block-level" name="comnam"
                                               value="<?php echo ($locationRec) ? $locationRec->comnam : ''; ?>">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Location Name</label>

                                    <div class="controls">
                                        <input type="text" class="input-block-level" name="planam"
                                               value="<?php echo ($locationRec) ? $locationRec->planam : ''; ?>">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Email Address</label>

                                    <div class="controls">
                                        <input type="text" class="input-block-level" name="plaema"
                                               value="<?php echo ($locationRec) ? $locationRec->plaema : ''; ?>">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="password">Telephone</label>

                                    <div class="controls">
                                        <input type="text" class="input-block-level" name="platel"
                                               value="<?php echo ($locationRec) ? $locationRec->platel : ''; ?>">
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">Fax</label>

                                    <div class="controls">
                                        <input type="text" class="input-block-level customfield" name="plafax"
                                               value="<?php echo (isset($locationRec->platxt)) ? $patchworks->getJSONVariable($locationRec->platxt, 'plafax', false) : ''; ?>">
                                    </div>
                                </div>



                                <div class="control-group">
                                    <label class="control-label" for="password">Website</label>

                                    <div class="controls">
                                        <input type="text" class="input-block-level" name="plaurl"
                                               value="<?php echo ($locationRec) ? $locationRec->plaurl : ''; ?>">
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" for="password">SEO URL</label>

                                    <div class="controls">
                                        <input type="text" class="input-block-level" name="seourl"
                                               value="<?php echo ($locationRec) ? $locationRec->seourl : ''; ?>">
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" for="password">Keywords</label>

                                    <div class="controls">
                                        <input type="text" class="input-block-level" name="keywrd"
                                               value="<?php echo ($locationRec) ? $locationRec->keywrd : ''; ?>">
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" for="password">Description</label>

                                    <div class="controls">
                                        <textarea class="input-block-level"
                                                  name="keydsc"><?php echo ($locationRec) ? $locationRec->keydsc : ''; ?></textarea>
                                    </div>
                                </div>

                                <div class="control-group hide">
                                    <label class="control-label">Password</label>

                                    <div class="controls">
                                        <input type="text" class="input-block-level" name="paswrd"/>
                                    </div>
                                </div>
                                <div class="control-group hide">
                                    <label class="control-label" for="confirm">Confirm Password</label>

                                    <div class="controls">
                                        <input type="text" class="input-block-level" name="pascnf" id="PasCnf">
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" for="confirm">Image</label>

                                    <div class="controls">
                                        <div class="inputContainer">
                                            <div class="inputWrapper">
                                                <select name="imgurl" class="customfield input-block-level">
                                                    <?php
                                                    echo $galleryHTML;
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="control-group hide">
                                    <label for="textfield" class="control-label">Image</label>

                                    <div class="controls">
                                        <div class="fileupload fileupload-new" data-provides="fileupload">
                                            <div class="fileupload-new thumbnail"
                                                 style="max-width: 200px; max-height: 150px;">


                                                <?php
                                                if (
                                                    isset($locationRec->plaimg) &&
                                                    file_exists($patchworks->docRoot . 'uploads/images/locations/' . $locationRec->plaimg) &&
                                                    !is_dir($patchworks->docRoot . 'uploads/images/locations/' . $locationRec->plaimg)
                                                ) {
                                                    echo '<img src="' . $patchworks->webRoot . '/uploads/images/locations/' . $locationRec->plaimg . '" class="img-responsive productImage" />';
                                                } else {
                                                    echo '<img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image" />';
                                                }
                                                ?>

                                            </div>

                                            <div class="fileupload-preview fileupload-exists thumbnail"
                                                 style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                            <div>
                                                <span class="btn btn-file"><span
                                                        class="fileupload-new">Select image</span><span
                                                        class="fileupload-exists">Change</span><input type="file"
                                                                                                      name='logofile'
                                                                                                      id="logofile"/></span>
                                                <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="plaimg"
                                           value="<?php echo ($locationRec) ? $locationRec->plaimg : ''; ?>">
                                </div>

                                <div class="control-group">
                                    <label class="control-label">Status</label>

                                    <div class="controls">
                                        <label class="radio">
                                            <input type="radio" name="sta_id"
                                                   value="0" <?php echo (!$locationRec || ($locationRec && $locationRec->sta_id == 0)) ? 'checked' : ''; ?>>
                                            Active</label>
                                        <label class="radio">
                                            <input type="radio" name="sta_id"
                                                   value="1" <?php echo ($locationRec && $locationRec->sta_id == 1) ? 'checked' : ''; ?>>
                                            In-Active </label>
                                    </div>
                                </div>
                                <div class="control-group" style="display: none;">
                                    <label class="control-label">Days</label>

                                    <div class="controls">
                                        <input type="text" class="input-block-level" name="rooms"
                                               value="<?php echo ($locationRec) ? $locationRec->rooms : '1'; ?>">
                                    </div>
                                </div>
                                <div class="control-group" style="display: none;">
                                    <label class="control-label">Colour</label>

                                    <div class="controls">
                                        <select name="placol">
                                            <option
                                                value="#e51400" <?php echo ($locationRec && $locationRec->placol == '#e51400') ? 'selected' : ''; ?>>
                                                Red
                                            </option>
                                            <option
                                                value="#f8a31f" <?php echo ($locationRec && $locationRec->placol == '#f8a31f') ? 'selected' : ''; ?>>
                                                Orange
                                            </option>
                                            <option
                                                value="#393" <?php echo ($locationRec && $locationRec->placol == '#393') ? 'selected' : ''; ?>>
                                                Green
                                            </option>
                                            <option
                                                value="#a05000" <?php echo ($locationRec && $locationRec->placol == '#a05000') ? 'selected' : ''; ?>>
                                                Brown
                                            </option>
                                            <option
                                                value="#368ee0" <?php echo ($locationRec && $locationRec->placol == '#368ee0') ? 'selected' : ''; ?>>
                                                Blue
                                            </option>
                                            <option
                                                value="#8cbf26" <?php echo ($locationRec && $locationRec->placol == '#8cbf26') ? 'selected' : ''; ?>>
                                                Lime
                                            </option>
                                            <option
                                                value="#00aba9" <?php echo ($locationRec && $locationRec->placol == '#00aba9') ? 'selected' : ''; ?>>
                                                Teal
                                            </option>
                                            <option
                                                value="#ff0097" <?php echo ($locationRec && $locationRec->placol == '#ff0097') ? 'selected' : ''; ?>>
                                                Purple
                                            </option>
                                            <option
                                                value="#e671b8" <?php echo ($locationRec && $locationRec->placol == '#e671b8') ? 'selected' : ''; ?>>
                                                Pink
                                            </option>
                                            <option
                                                value="#a200ff" <?php echo ($locationRec && $locationRec->placol == '#a200ff') ? 'selected' : ''; ?>>
                                                Magenta
                                            </option>
                                            <option
                                                value="#333" <?php echo ($locationRec && $locationRec->placol == '#333') ? 'selected' : ''; ?>>
                                                Grey
                                            </option>
                                            <option
                                                value="#204e81" <?php echo ($locationRec && $locationRec->placol == '#204e81') ? 'selected' : ''; ?>>
                                                Dark Blue
                                            </option>
                                            <option
                                                value="#e63a3a" <?php echo ($locationRec && $locationRec->placol == '#e63a3a') ? 'selected' : ''; ?>>
                                                Light Red
                                            </option>
                                            <option
                                                value="#666" <?php echo ($locationRec && $locationRec->placol == '#666') ? 'selected' : ''; ?>>
                                                Light Grey
                                            </option>
                                            <option
                                                value="#2c5e7b" <?php echo ($locationRec && $locationRec->placol == '#2c5e7b') ? 'selected' : ''; ?>>
                                                Sat Blue
                                            </option>
                                            <option
                                                value="#56af45" <?php echo ($locationRec && $locationRec->placol == '#56af45') ? 'selected' : ''; ?>>
                                                Sat Green
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">VAT No.</label>

                                    <div class="controls">
                                        <input type="text" class="input-block-level customfield" name="vatnum"
                                               value="<?php echo (isset($locationRec->platxt)) ? $patchworks->getJSONVariable($locationRec->platxt, 'vatnum', false) : ''; ?>">
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">Company No.</label>

                                    <div class="controls">
                                        <input type="text" class="input-block-level customfield" name="comnum"
                                               value="<?php echo (isset($locationRec->platxt)) ? $patchworks->getJSONVariable($locationRec->platxt, 'comnum', false) : ''; ?>">
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary"><i class="icon-save"></i> Update
                                    </button>
                                </div>

                            </div>
                        </div>

                    </div>

                    <div class="span6">
                        <div class="box box-color box-bordered green">
                            <div class="box-title">
                                <h3>
                                    <i class="icon-pushpin"></i> Address</h3>

                                <div class="actions">
                                    <a href="#" class="btn btn-mini" id="adrControl"><i class="icon-angle-up"></i></a>
                                    <!--<a href="ecommerce/events-edit.php" class="btn btn-mini" rel="tooltip" title="New Event"><i class="icon-file"></i></a>-->
                                </div>
                            </div>
                            <div class="box-content nopadding" id="adrInputs">
                                <div class="control-group">
                                    <label class="control-label">Address</label>

                                    <div class="controls">
                                        <input type="text" class="input-block-level input-margin-bottom" name="adr1"
                                               value="<?php echo ($locationRec) ? $locationRec->adr1 : ''; ?>">
                                        <input type="text" class="input-block-level input-margin-bottom" name="adr2"
                                               value="<?php echo ($locationRec) ? $locationRec->adr2 : ''; ?>">
                                        <input type="text" class="input-block-level input-margin-bottom" name="adr3"
                                               value="<?php echo ($locationRec) ? $locationRec->adr3 : ''; ?>">
                                        <input type="text" class="input-block-level input-margin-bottom" name="adr4"
                                               value="<?php echo ($locationRec) ? $locationRec->adr4 : ''; ?>">
                                        <input type="text" class="input-block-level" name="ctynam"
                                               value="<?php echo ($locationRec) ? $locationRec->ctynam : ''; ?>">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Postcode</label>

                                    <div class="controls">
                                        <div class="input-append">
                                            <input type="text" class="input-block-level" name="pstcod"
                                                   value="<?php echo ($locationRec) ? $locationRec->pstcod : ''; ?>">
                                            <button id="geoLocate" class="btn"><i class="icon-map-marker"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Google Data</label>

                                    <div class="controls">
                                        <input type="text" class="input-block-level input-margin-bottom" name="goolat"
                                               id="GooLat"
                                               value="<?php echo (!empty($locationRec)) ? $locationRec->goolat : ''; ?>">
                                        <input type="text" class="input-block-level input-margin-bottom" name="goolng"
                                               id="GooLng"
                                               value="<?php echo  (!empty($locationRec)) ? $locationRec->goolng : ''; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="box-content nopadding">

                                <div id="map_canvas" style="height: 400px;">
                                </div>

                            </div>
                        </div>
                    </div>

                </form>
            </div>

            <div class="row-fluid">
                <div class="span8">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                <i class="icon-picture"></i> Gallery Images </h3>
                        </div>
                        <div class="box-content">

                            <ul class="gallery gallery-dynamic gallerylist" id="galleryImages">

                            </ul>
                        </div>
                    </div>
                </div>

                <div class="span4">
                    <div class="box" <?php // echo($galleryRec) ? '' : 'style="display: none;"'; ?>>
                        <div class="box-title">
                            <h3><i class="icon-picture"></i> Image Uploader</h3>

                            <div class="actions">
                                <a class="btn btn-mini content-slideUp" href="#">
                                    <i class="icon-angle-down"></i>
                                </a>
                            </div>
                        </div>
                        <div class="box-content nopadding">
                            <div id="plupload" data-resize="<?php echo $patchworks->galleryImageSizes; ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row-fluid">
                <div class="span8">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                <i class="icon-file-alt"></i> Product PDFs </h3>
                        </div>
                        <div class="box-content">

                            <ul id="galleryPDFs" class="unstyled gallerylist">

                            </ul>
                        </div>
                    </div>
                </div>

                <div class="span4">
                    <div class="box" <?php // echo($galleryRec) ? '' : 'style="display: none;"'; ?>>
                        <div class="box-title">
                            <h3><i class="icon-file-alt"></i> File Uploader</h3>

                            <div class="actions">
                                <a class="btn btn-mini content-slideUp" href="#">
                                    <i class="icon-angle-down"></i>
                                </a>
                            </div>
                        </div>
                        <div class="box-content nopadding">
                            <div id="pdfplupload">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


<div class="modal hide fade" id="imageModal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h3>Image Detail</h3>
    </div>
    <form action="gallery/upload_script.php" id="imageForm" class="form-horizontal" novalidate>
        <input type="hidden" name="upl_id"/>

        <div class="modal-body">
            <fieldset>

                <div class="control-group">
                    <label class="control-label">Title</label>

                    <div class="controls">
                        <input type="text" class="input-block-level" name="uplttl">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Description</label>

                    <div class="controls">
                        <textarea class="input-block-level" name="upldsc"></textarea>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Link</label>

                    <div class="controls">
                        <input type="text" class="input-block-level" name="urllnk">
                    </div>
                </div>

            </fieldset>
        </div>
        <div class="modal-footer">
            <a href="#" class="btn" data-dismiss="modal"><i class="icon-remove"></i> Cancel</a>
            <button type="submit" class="btn btn-primary" name="action" value="update" id="updateImageBtn"><i
                    class="icon-save"></i> Update
            </button>
        </div>
    </form>
</div>

</body>
</html>
