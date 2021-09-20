<?php

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("../hotspots/classes/hotspots.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpHot = new HotDAO();

$editHotSpotID = (isset($_GET['hot_id']) && is_numeric($_GET['hot_id'])) ? $_GET['hot_id'] : NULL;
$hotSpotRec = NULL;
if (!is_null($editHotSpotID)) $hotSpotRec = $TmpHot->select($editHotSpotID, NULL, NULL, true);

?>
<!doctype html>
<html>
<head>
    <title>Hot Spot : <?php echo(isset($hotSpotRec)) ? $hotSpotRec->hotnam : 'New Hot Spot'; ?></title>
    <?php include('../webparts/headdata.php'); ?>

    <style>

        #hotSpotWrapper {
            position: relative;
            background: #cecece;
        }

        #hotSpotWrapper .hotSpotDetail {

            position: absolute;
            width: 20px;
            height: 20px;
            border-radius: 100%;
            background: #fff;
            border: solid 1px #000;
            text-align: center;
            line-height: 20px;

        }

        #addHotSpot {

            width: 20px;
            height: 20px;
            border-radius: 100%;
            background: #fff;
            border: solid 1px #000;
            text-align: center;
            line-height: 20px;
            display: inline-block;
            z-index: 2000;

        }

    </style>

    <script src="js/plugins/fileupload/bootstrap-fileupload.min.js"></script>
    <script src="hotspots/js/hotspots-edit.js"></script>
</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-orange">
<div class="container-fluid" id="content">
    <?php include('../webparts/website-left.php'); ?>
    <div id="main">
        <div class="container-fluid">
            <div class="page-header">
                <div class="pull-left">
                    <h1>Hot Spot : <?php echo(isset($hotSpotRec)) ? $hotSpotRec->hotnam : 'New Hot Spot'; ?></h1>
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
                        <a href="hotspots/hotspots.php">Hot Spots</a>
                        <i class="icon-angle-right"></i>
                    </li>
                    <li>
                        <a><?php echo(isset($hotSpotRec)) ? $hotSpotRec->hotnam : 'New Hot Spot'; ?></a>
                    </li>
                </ul>
            </div>
            <div class="row-fluid">
                <form action="hotspots/hotspots_script.php" id="hotSpotForm" class="form-horizontal form-bordered" data-returnurl="hotspots/hotspots.php" enctype="multipart/form-data">

                    <div class="span4">
                        <div class="box box-color box-bordered">
                            <div class="box-title">
                                <h3>
                                    <i class="icon-map-marker"></i> Hot Spot</h3>
                                <div class="actions">
                                    <a href="#" id="updateHotSpotBtn" class="btn btn-mini" rel="tooltip" title="Update"><i class="icon-save"></i></a>
                                    <a href="#" id="deleteHotSpotBtn" class="btn btn-mini" rel="tooltip" title="Delete"><i class="icon-trash"></i></a>
                                </div>
                            </div>
                            <div class="box-content nopadding">
                                <input type="hidden" name="hot_id" id="id" value="<?php echo(isset($hotSpotRec)) ? $hotSpotRec->hot_id : '0'; ?>">
                                <input type="hidden" name="tblnam" value="WEBSITE">
                                <input type="hidden" name="tbl_id" value="0">

                                <div class="control-group">
                                    <label class="control-label">Hot Spot Title<small>identifying name</small></label>
                                    <div class="controls">
                                        <input type="text" class="input-block-level" name="hotnam" value="<?php echo(isset($hotSpotRec)) ? $hotSpotRec->hotnam : ''; ?>">
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label for="textfield" class="control-label">Image</label>
                                    <div class="controls">
                                        <div class="fileupload fileupload-new" data-provides="fileupload">
                                            <div class="fileupload-new thumbnail" style="max-width: 200px; max-height: 150px; background: #cecece">

                                                <?php

                                                if (
                                                    isset($hotSpotRec->hotimg) &&
                                                    file_exists($patchworks->docRoot . 'uploads/images/hotspots/' . $hotSpotRec->hotimg) &&
                                                    !is_dir($patchworks->docRoot . 'uploads/images/hotspots/' . $hotSpotRec->hotimg)
                                                ) {
                                                    echo '<img src="../uploads/images/hotspots/' . $hotSpotRec->hotimg . '" class="img-responsive productImage" />';
                                                } else {
                                                    echo '<img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=no+image" />';
                                                }
                                                ?>

                                            </div>

                                            <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                            <div>
                                                <span class="btn btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span><input type="file" name='logofile' id="logofile" /></span>
                                                <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                                            </div>

                                            <input type="hidden" name="hotimg" value="<?php echo(isset($hotSpotRec)) ? $hotSpotRec->hotimg : ''; ?>">

                                        </div>
                                    </div>
                                </div>
                                

                            </div>
                        </div>
                    </div>

                    <div class="span8">
                        <div class="box box-color box-bordered">
                            <div class="box-title">
                                <h3>
                                    <i class="icon-map-marker"></i> Hot Spot Drop</h3>
                                <div class="actions">
                                    <a href="#" id="addHotSpotBtn" class="btn btn-mini" rel="tooltip" title="Add hot Spot"><i class="icon-plus"></i></a>
                                </div>
                            </div>
                            <div class="box-content">

                                <div id="hotSpotWrapper" style="display: inline-block">

                                    <?php
                                    if (
                                        isset($hotSpotRec->hotimg) &&
                                        file_exists($patchworks->docRoot . 'uploads/images/hotspots/' . $hotSpotRec->hotimg) &&
                                        !is_dir($patchworks->docRoot . 'uploads/images/hotspots/' . $hotSpotRec->hotimg)
                                    ) {
                                        echo '<img src="../uploads/images/hotspots/' . $hotSpotRec->hotimg . '" class="img-responsive productImage" />';
                                    } else {

                                    }
                                    ?>

                                </div>

                            </div>
                            <div class="box-content">

                                <p class="alert alert-info">Drag and drop the circle marker onto the image</p>

                                <a href="#" id="addHotSpot"></a>

                            </div>
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>

<div class="modal hide fade" id="hotSpotModal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h3>Hot Spot Detail</h3>
    </div>
    <form action="hotspots/hotspots_script.php" id="hotSpotDetailForm" class="form-horizontal" novalidate>
        <input type="hidden" name="hsp_id" />
        <div class="modal-body">
            <fieldset>
                <div class="control-group">
                    <label class="control-label">Title</label>
                    <div class="controls">
                        <input type="text" class="input-block-level" name="hspttl">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Description</label>
                    <div class="controls">
                        <textarea class="input-block-level" name="hsptxt"></textarea>
                    </div>
                </div>
            </fieldset>
        </div>
        <div class="modal-footer">
            <a href="#" class="btn btn-danger" id="deleteHotSpotDetailBtn"><i class="icon-trash"></i> Delete</a>
            <a href="#" class="btn" data-dismiss="modal"><i class="icon-remove"></i> Cancel</a>
            <button type="submit" class="btn btn-primary" value="update" id="updateHotSpotBtn"><i class="icon-save"></i> Update</button>
        </div>
    </form>
</div>

</body>
</html>
