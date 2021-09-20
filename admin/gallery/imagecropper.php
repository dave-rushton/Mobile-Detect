<?php
require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("classes/gallery.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');
$fileName = $patchworks->webRoot . 'uploads/images/' . $_GET['imgfil'];

function resize($ImgSiz = NULL)
{
    $targetDir = 'uploads/images/';
    $chkDirectory = $targetDir . $ImgSiz . '/';
    if (!file_exists($chkDirectory)) @mkdir($chkDirectory);
    $image = new SimpleImage();
    $image->load($targetDir . DIRECTORY_SEPARATOR . $fileName);
    $image->resizeToWidth($ImgSiz);
    $smallImage = $targetDir . $ImgSiz . '/' . $fileName;
    $image->save($smallImage);
}

?>
<!doctype html>
<html lang="en">
    <head>
        <?php include('../webparts/headdata.php'); ?>
        <title>Cropper</title>
        <link href="css/plugins/cropper/cropper.css" rel="stylesheet">
    </head>

    <body class="theme-red">
        <?php include('../webparts/navigation.php'); ?>
        <div class="container-fluid nav-hidden" id="content">
            <?php include('../webparts/website-left.php'); ?>
            <div id="main">
                <div class="container-fluid">
                    <div class="page-header">
                        <div class="pull-left">
                            <h1>Website Galleries</h1>
                        </div>
                        <div class="pull-right">
                        </div>
                    </div>

                    <div class="row-fluid">
                        <div class="span12">
                            <?php
                            list($width, $height, $type, $attr) = getimagesize($patchworks->webRoot . 'uploads/images/' . $_GET['imgfil']);
                            ?>
                            <div class="box box-color">
                                <div class="box-title">
                                    <h3>
                                        <i class="icon-picture"></i> Image Cropper</h3>
                                    <div class="actions">
                                        <!--                                <a href="#" id="rotateBtn" class="btn btn-mini" rel="tooltip" title=""-->
                                        <!--                                   data-original-title="Rotate Image"><i class="icon-refresh"></i></a>-->
                                        <a href="#" id="rotateBtn" class="btn btn-mini" rel="tooltip" title="Rotate Image"
                                           data-original-title="Rotate Image"><i class="icon-refresh"></i></a>
                                    </div>
                                </div>
                                <div class="box-content nopadding">
                                    <div class="img-container">
                                        <img id="image" src="<?php echo $patchworks->webRoot; ?>uploads/images/<?php echo $_GET['imgfil']; ?>?t=<?php echo time(); ?>" alt="Picture">
                                    </div>
                                </div>
                            </div>
                            <div class="box box-color box-bordered">
                                <div class="box-title">
                                    <h3>
                                        <i class="icon-picture"></i> Image Cropper</h3>
                                    <div class="actions">
                                        <a href="#" id="saveImage" class="btn btn-mini" rel="tooltip" title=""
                                           data-original-title="Update Image"><i class="icon-save"></i></a>
                                    </div>
                                </div>
                                <div class="box-content">
                                    <ul class="nav nav-pills">
                                        <?php
                                        $createSize = 0;
                                        $gallerySize = explode(",", $patchworks->galleryImageSizes);
                                        if (isset($_GET['imgsiz'])) {
                                            $gallerySize = explode(",", $_GET['imgsiz']);
                                        }
                                        if (!isset($_GET['imgsiz']) || empty($_GET['imgsiz'])) {
                                            array_push($gallerySize, 'FREE');
                                        }
                                        for ($i = 0; $i < count($gallerySize); $i++) {
                                            $imageSize = explode("-", $gallerySize[$i]);
                                            //echo $imageSize.'<br>';
                                            if ($imageSize[0] == 'FREE') {
                                                $ratio = NULL;
                                            } else {
                                                if (count($imageSize) == 1) continue;
                                                $ratio = $imageSize[0] / $imageSize[1];
                                                if ($imageSize[0] > $createSize) {
                                                    $createSize = $imageSize[0];
                                                }
                                            }
                                            ?>
                                            <li role="presentation">
                                                <a href="#" class="changeratio" data-directory="<?php echo $gallerySize[$i]; ?>" data-ratio="<?php echo $ratio; ?>"><?php echo $gallerySize[$i]; ?></a>
                                            </li>
                                            <?php
                                        }
                                        ?>
                                        <?php
                                        if ($createSize > $width) {
                                            ?>
                                            <li>
                                                <a href="#" class="createWidth" data-imgfil="<?php echo $_GET['imgfil']; ?>" data-width="<?php echo $createSize; ?>">Create
                                                    Width (<?php echo $createSize; ?>)</a></li>
                                            <?php
                                        }
                                        ?>
                                    </ul>
                                    <div class="imgpreview" id="imgpreview"
                                         style="width: 620px; height: 349px; overflow: hidden"></div>
                                    <form id="cropForm" method="post" style="display: none;">
                                        <input class="avatar-file" name="avatar_file" type="text" value="">
                                        <input class="avatar-src" name="avatar_src" type="text"
                                               value="<?php echo $patchworks->docRoot; ?>uploads/images/<?php echo $_GET['imgfil']; ?>">
                                        <input class="avatar-data" name="avatar_data" type="text">
                                        <input class="avatar-size" name="avatar_size" type="text" value="960-440">
                                    </form>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="cropinfo"></div>
        <script type="text/javascript" src="js/plugins/cropper/cropper.js"></script>
        <script src="gallery/js/imagecropper.js"></script>
    </body>
</html>