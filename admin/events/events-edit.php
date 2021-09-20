<?php

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("../system/classes/places.cls.php");
require_once("../events/classes/bookings.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpPla = new PlaDAO();
$editPlaceID = (isset($_GET['pla_id']) && is_numeric($_GET['pla_id'])) ? $_GET['pla_id'] : NULL;
$placeRec = NULL;
if (!is_null($editPlaceID)) $placeRec = $TmpPla->select($editPlaceID, NULL, NULL, NULL, NULL, true);

$TmpBoo = new BooDAO();
$bookings = $TmpBoo->select(NULL, NULL, NULL, 'EVENT', $editPlaceID, NULL, NULL, NULL, false, NULL, NULL, 'begdat desc');

?>
<!doctype html>
<html>
<head>
    <title>Event : <?php echo ($placeRec) ? $placeRec->planam : 'New Event'; ?></title>
    <?php include('../webparts/headdata.php'); ?>

    <script type="text/javascript"
            src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDjZSf7lI4D80NIwFMozDDABq-tSkGgKIs&sensor=false"></script>
    <script src="js/plugins/gmap/gmap3.min.js"></script>
    <script src="js/plugins/gmap/gmap3-menu.js"></script>

<!--    <script src="js/plugins/ckeditor/ckeditor.js"></script>-->
    <script src="js/plugins/tinymce/tinymce.min.js"></script>

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

    <script src="events/js/events-edit.js"></script>
</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
    <?php include('../webparts/website-left.php'); ?>
    <div id="main">
        <div class="container-fluid">
            <div class="page-header">
                <div class="pull-left">
                    <h1>Event : <?php echo ($placeRec) ? $placeRec->planam : 'New Event'; ?></h1>
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
                        <a href="events/events.php">Events</a>
                        <i class="icon-angle-right"></i>
                    </li>
                    <li>
                        <a><?php echo ($placeRec) ? $placeRec->planam : 'New Event'; ?></a>
                    </li>
                </ul>
            </div>
            <div class="row-fluid">

                <div class="span6">
                    <form action="system/places_script.php" id="eventForm" class="form-horizontal form-bordered"
                          data-returnurl="events/events.php">
                        <div class="box box-color box-bordered">
                            <div class="box-title">
                                <h3>
                                    <i class="icon-calendar"></i> Event</h3>

                                <div class="actions">
                                    <a href="#" id="deleteEventBtn" class="btn btn-mini" rel="tooltip" title="Delete"><i
                                            class="icon-trash"></i></a>
                                </div>
                            </div>
                            <div class="box-content nopadding">
                                <input type="hidden" name="pla_id" id="id"
                                       value="<?php echo ($placeRec) ? $placeRec->pla_id : '0'; ?>">
                                <input type="hidden" name="tblnam" value="EVT">
                                <input type="hidden" name="tbl_id" value="0">

                                <div class="control-group hide">
                                    <label class="control-label">Event Name
                                        <small>Main name used on invoicing</small>
                                    </label>

                                    <div class="controls">
                                        <input type="text" class="input-large" name="comnam"
                                               value="<?php echo ($placeRec) ? $placeRec->comnam : ''; ?>">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Event Name
                                        <small>identifying name</small>
                                    </label>

                                    <div class="controls">
                                        <input type="text" class="input-large" name="planam"
                                               value="<?php echo ($placeRec) ? $placeRec->planam : ''; ?>">
                                    </div>
                                </div>


                                <div class="control-group">
                                    <label class="control-label">SEO URL
                                        <small>identifying name</small>
                                    </label>

                                    <div class="controls">
                                        <input type="text" class="input-large" name="seourl"
                                               value="<?php echo ($placeRec) ? $placeRec->seourl : ''; ?>">
                                    </div>
                                </div>

                                <textarea name="platxt" id="platxt" class='span12' rows="5"
                                          style="height: 300px;"><?php echo ($placeRec) ? $placeRec->platxt : ''; ?></textarea>

                                <div class="control-group hide">
                                    <label class="control-label">Email Address
                                        <small>email correspondance</small>
                                    </label>

                                    <div class="controls">
                                        <input type="text" class="input-large" name="plaema"
                                               value="<?php echo ($placeRec) ? $placeRec->plaema : ''; ?>">
                                    </div>
                                </div>
                                <div class="control-group hide">
                                    <label class="control-label" for="password">Telephone
                                        <small>telephone correspondance</small>
                                    </label>

                                    <div class="controls">
                                        <input type="text" class="input-large" name="platel"
                                               value="<?php echo ($placeRec) ? $placeRec->platel : ''; ?>">
                                    </div>
                                </div>
                                <div class="control-group hide">
                                    <label class="control-label">Password
                                        <small>where applicable</small>
                                    </label>

                                    <div class="controls">
                                        <input type="text" class="input-large" name="paswrd"/>
                                    </div>
                                </div>
                                <div class="control-group hide">
                                    <label class="control-label" for="confirm">Confirm Password
                                        <small>where applicable</small>
                                    </label>

                                    <div class="controls">
                                        <input type="text" class="input-large" name="pascnf" id="PasCnf">
                                    </div>
                                </div>

                                <div class="control-group hide">
                                    <label class="control-label">Days</label>

                                    <div class="controls">
                                        <input type="text" class="input-large" name="rooms"
                                               value="<?php echo ($placeRec) ? $placeRec->rooms : '1'; ?>">
                                    </div>
                                </div>
                                <div class="control-group hide">
                                    <label class="control-label">Colour</label>

                                    <div class="controls">
                                        <select name="placol">
                                            <option
                                                value="#e51400" <?php echo ($placeRec && $placeRec->placol == '#e51400') ? 'selected' : ''; ?>>
                                                Red
                                            </option>
                                            <option
                                                value="#f8a31f" <?php echo ($placeRec && $placeRec->placol == '#f8a31f') ? 'selected' : ''; ?>>
                                                Orange
                                            </option>
                                            <option
                                                value="#393" <?php echo ($placeRec && $placeRec->placol == '#393') ? 'selected' : ''; ?>>
                                                Green
                                            </option>
                                            <option
                                                value="#a05000" <?php echo ($placeRec && $placeRec->placol == '#a05000') ? 'selected' : ''; ?>>
                                                Brown
                                            </option>
                                            <option
                                                value="#368ee0" <?php echo ($placeRec && $placeRec->placol == '#368ee0') ? 'selected' : ''; ?>>
                                                Blue
                                            </option>
                                            <option
                                                value="#8cbf26" <?php echo ($placeRec && $placeRec->placol == '#8cbf26') ? 'selected' : ''; ?>>
                                                Lime
                                            </option>
                                            <option
                                                value="#00aba9" <?php echo ($placeRec && $placeRec->placol == '#00aba9') ? 'selected' : ''; ?>>
                                                Teal
                                            </option>
                                            <option
                                                value="#ff0097" <?php echo ($placeRec && $placeRec->placol == '#ff0097') ? 'selected' : ''; ?>>
                                                Purple
                                            </option>
                                            <option
                                                value="#e671b8" <?php echo ($placeRec && $placeRec->placol == '#e671b8') ? 'selected' : ''; ?>>
                                                Pink
                                            </option>
                                            <option
                                                value="#a200ff" <?php echo ($placeRec && $placeRec->placol == '#a200ff') ? 'selected' : ''; ?>>
                                                Magenta
                                            </option>
                                            <option
                                                value="#333" <?php echo ($placeRec && $placeRec->placol == '#333') ? 'selected' : ''; ?>>
                                                Grey
                                            </option>
                                            <option
                                                value="#204e81" <?php echo ($placeRec && $placeRec->placol == '#204e81') ? 'selected' : ''; ?>>
                                                Dark Blue
                                            </option>
                                            <option
                                                value="#e63a3a" <?php echo ($placeRec && $placeRec->placol == '#e63a3a') ? 'selected' : ''; ?>>
                                                Light Red
                                            </option>
                                            <option
                                                value="#666" <?php echo ($placeRec && $placeRec->placol == '#666') ? 'selected' : ''; ?>>
                                                Light Grey
                                            </option>
                                            <option
                                                value="#2c5e7b" <?php echo ($placeRec && $placeRec->placol == '#2c5e7b') ? 'selected' : ''; ?>>
                                                Sat Blue
                                            </option>
                                            <option
                                                value="#56af45" <?php echo ($placeRec && $placeRec->placol == '#56af45') ? 'selected' : ''; ?>>
                                                Sat Green
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">Status
                                        <small>current event status</small>
                                    </label>

                                    <div class="controls">
                                        <label class="radio">
                                            <input type="radio" name="sta_id"
                                                   value="0" <?php echo (!$placeRec || $placeRec && $placeRec->sta_id == 0) ? 'checked' : ''; ?>>
                                            Active</label>
                                        <label class="radio">
                                            <input type="radio" name="sta_id"
                                                   value="1" <?php echo ($placeRec && $placeRec->sta_id == 1) ? 'checked' : ''; ?>>
                                            In-Active </label>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary" id="updateEventBtn"><i class="icon-save"></i> Update </button>
                                </div>

                            </div>
                        </div>

                        <div class="box box-color box-bordered hide">
                            <div class="box-title">
                                <h3>
                                    <i class="icon-pushpin"></i> Address</h3>

                                <div class="actions">
                                    <a href="#" class="btn btn-mini" id="adrControl"><i class="icon-angle-down"></i></a>
                                    <!--<a href="ecommerce/events-edit.php" class="btn btn-mini" rel="tooltip" title="New Event"><i class="icon-file"></i></a>-->
                                </div>
                            </div>
                            <div class="box-content nopadding" id="adrInputs" style="display: none;">
                                <div class="control-group">
                                    <label class="control-label">Address</label>

                                    <div class="controls">
                                        <input type="text" class="input-large input-margin-bottom" name="adr1"
                                               value="<?php echo ($placeRec) ? $placeRec->adr1 : ''; ?>">
                                        <input type="text" class="input-large input-margin-bottom" name="adr2"
                                               value="<?php echo ($placeRec) ? $placeRec->adr2 : ''; ?>">
                                        <input type="text" class="input-large input-margin-bottom" name="adr3"
                                               value="<?php echo ($placeRec) ? $placeRec->adr3 : ''; ?>">
                                        <input type="text" class="input-large input-margin-bottom" name="adr4"
                                               value="<?php echo ($placeRec) ? $placeRec->adr4 : ''; ?>">
                                        <input type="text" class="input-large" name="ctynam"
                                               value="<?php echo ($placeRec) ? $placeRec->ctynam : ''; ?>">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Postcode</label>

                                    <div class="controls">
                                        <div class="input-append">
                                            <input type="text" class="input-large" name="pstcod"
                                                   value="<?php echo ($placeRec) ? $placeRec->pstcod : ''; ?>">
                                            <button id="geoLocate" class="btn"><i class="icon-map-marker"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Google Data</label>

                                    <div class="controls">
                                        <input type="text" class="input-large input-margin-bottom" name="goolat"
                                               id="GooLat" value="<?php echo ($placeRec) ? $placeRec->goolat : ''; ?>">
                                        <input type="text" class="input-large input-margin-bottom" name="goolng"
                                               id="GooLng" value="<?php echo ($placeRec) ? $placeRec->goolng : ''; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="box-content nopadding">

                                <div id="map_canvas" style="height: 400px;">
                                </div>

                            </div>
                        </div>

                    </form>

                    <div class="box">
                        <div class="box-title">
                            <h3>
                                <i class="icon-picture"></i> Gallery Images </h3>
                            <div class="actions">
                                <a href="#" id="addImageBtn" class="btn btn-mini" rel="tooltip" title="Add Images" style="display: none"><i class="icon-plus"></i></a>
                            </div>
                        </div>
                        <div class="box-content">

                            <ul class="gallery gallery-dynamic" id="galleryImages">

                            </ul>
                        </div>
                    </div>

                    <div class="box">
                        <div class="box-title">
                            <h3><i class="icon-th"></i> Multi File upload</h3>
                            <div class="actions">
                                <a class="btn btn-mini content-slideUp" href="#">
                                    <i class="icon-angle-down"></i>
                                </a>
                            </div>
                        </div>
                        <div class="box-content nopadding">
                            <div id="plupload">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="span6">



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
        <input type="hidden" name="upl_id" />
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
            <button type="submit" class="btn btn-primary" name="action" value="update" id="updateImageBtn"><i class="icon-save"></i> Update</button>
        </div>
    </form>
</div>

</body>
</html>
