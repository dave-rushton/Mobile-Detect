<?php

require_once('../../config/config.php');
require_once('../patchworks.php');

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

global $jpegquality;
global $pngquality;

$action = (isset($_GET['action'])) ? $_GET['action'] : NULL;
$path = (isset($_GET['path'])) ? $_GET['path'] : $patchworks->docRoot . 'uploads/images/';
$quality = (isset($_GET['quality']) && is_numeric($_GET['quality'])) ? $_GET['quality'] : 80;
$pngquality = (isset($_GET['pngquality']) && is_numeric($_GET['pngquality'])) ? $_GET['pngquality'] : 9;

$jpegquality = $quality; // The quality at which you would like to optimize the JPEG images. 80 is fairly standard for high-quality JPEG images
$pngquality = $pngquality; // The amount you would like to compress the PNG images. 9 is the maximum compression for PNG images

?>
<!doctype html>
<html>
    <head>
        <title>Optimise Images</title>
        <?php include('../webparts/headdata.php'); ?>
    </head>
    <?php include('../webparts/navigation.php'); ?>
    <body class="theme-red">
        <div class="container-fluid" id="content">
            <?php include('../webparts/website-left.php'); ?>
            <div id="main">
                <div class="container-fluid">
                    <div class="page-header">
                        <div class="pull-left">
                            <h1>Optimise Images</h1>
                        </div>
                        <div class="pull-right">
                            <?php include('../webparts/index-info.php'); ?>
                        </div>
                    </div>
                    <div class="breadcrumbs">
                        <ul>
                            <li>
                                <a href="index.php">Dashboard</a> <i class="icon-angle-right"></i>
                            </li>
                            <li>
                                <a>Optimise Images</a>
                            </li>
                        </ul>
                    </div>
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="box box-color box-bordered">
                                <div class="box-title">
                                    <h3>
                                        <i class="icon-picture"></i> Optimise Images</h3>
                                </div>
                                <div class="box-content">

                                    <?php

                                    if ($action == 'summary') {

                                        $ar = getDirectorySize($path);

                                        // <h1>'.$path.'</h1>

                                        echo '
                            <dl>
                                <dt>Total Size:</dt>
                                <dd>' . sizeFormat($ar['size']) . '</dd>
                                <dt>Number of files:</dt>
                                <dd>' . $ar['count'] . '</dd>
                                <dt>Number of subdirectories:</dt>
                                <dd>' . $ar['dircount'] . '</dd>
                            </dl>';

                                        ?>

                                        <form class="form-vertical form-validate form-bordered" method="GET" action="gallery/optimiseimages.php" id="optimiseForm">
                                            <div class="control-group">
                                                <label class="control-label">Path</label>
                                                <div class="controls">
                                                    <input type="text" class="input-block-level" name="path" value="<?php echo $path; ?>">

                                                    <button type="submit" name="action" value="summary" class="btn btn-primary">
                                                        Re-Summary
                                                    </button>

                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label">JPG/GIF Quality</label>
                                                <div class="controls">
                                                    <input type="text" class="input-block-level" name="quality" value="<?php echo $quality; ?>">
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label">PNG Quality</label>
                                                <div class="controls">
                                                    <input type="text" class="input-block-level" name="pngquality" value="<?php echo $pngquality; ?>">
                                                </div>
                                            </div>
                                            <button type="submit" name="action" value="optimise" class="btn btn-primary">
                                                Optimise
                                            </button>

                                        </form>

                                        <?php


                                    } else if ($action == 'optimise') {

                                        $ar = getDirectorySize($path);

                                        $done = recursedir($path);
                                        echo "\n</ul>";
                                        if ($done) {
                                            $as = getDirectorySize($path);
                                            echo '<h3>The images have all been resampled to JPEG ' . $quality . '-quality photos.</h3>
	<dl>
		<dt>Total Size:</dt>
		<dd>' . sizeFormat($as['size']) . '</dd>
		<dt>Number of files:</dt>
		<dd>' . $as['count'] . '</dd>
		<dt>Number of subdirectories:</dt>
		<dd>' . $as['dircount'] . '</dd>
	</dl>
	<p>The directory is ' . sizeFormat(($ar['size'] - $as['size'])) . ' smaller than it was before the optimization.</p>';
                                        } else {
                                            echo '<h1>The function did not complete successfully.</h1>';
                                        }

                                    } else {
                                        ?>

                                        <form class="form-vertical form-validate form-bordered" method="GET" action="gallery/optimiseimages.php" id="optimiseForm">
                                            <div class="control-group">
                                                <label class="control-label">Path</label>
                                                <div class="controls">
                                                    <input type="text" class="input-block-level" name="path" value="<?php echo $path; ?>">
                                                </div>
                                            </div>
                                            <button type="submit" name="action" value="summary" class="btn btn-primary">
                                                Summary
                                            </button>

                                        </form>

                                        <?php
                                    }

                                    ?>


                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <?php

        function getDirectorySize($path)
        {
            $totalsize = 0;
            $totalcount = 0;
            $dircount = 0;
            if ($handle = opendir($path)) {
                while (false !== ($file = readdir($handle))) {
                    $nextpath = $path . '/' . $file;
                    if ($file != '.' && $file != '..' && !is_link($nextpath)) {
                        if (is_dir($nextpath)) {
                            $dircount++;
                            $result = getDirectorySize($nextpath);
                            $totalsize += $result['size'];
                            $totalcount += $result['count'];
                            $dircount += $result['dircount'];
                        } elseif (is_file($nextpath)) {
                            $totalsize += filesize($nextpath);
                            $totalcount++;
                        }
                    }
                }
            }
            closedir($handle);
            $total['size'] = $totalsize;
            $total['count'] = $totalcount;
            $total['dircount'] = $dircount;
            return $total;
        }

        function sizeFormat($size)
        {
            if ($size < 1024) {
                return $size . " bytes";
            } else if ($size < (1024 * 1024)) {
                $size = round($size / 1024, 1);
                return $size . " KB";
            } else if ($size < (1024 * 1024 * 1024)) {
                $size = round($size / (1024 * 1024), 1);
                return $size . " MB";
            } else {
                $size = round($size / (1024 * 1024 * 1024), 1);
                return $size . " GB";
            }

        }

        function recursedir($path, $print = true)
        {
            if ($handle = opendir($path)) {
                while (false !== ($file = readdir($handle))) {
                    if ($print) {
                        echo "\n<li>$path/$file";
                    }
                    if (!is_dir($path . '/' . $file) && $file != '.' && $file != '..') {
                        $start = filesize($path . '/' . $file);
                        if (exif_imagetype($path . '/' . $file) == IMAGETYPE_JPEG) {
                            optimize_jpeg($path . '/' . $file);
                        } elseif (exif_imagetype($path . '/' . $file) == IMAGETYPE_PNG) {
                            optimize_png($path . '/' . $file);
                        }
                        $end = filesize($path . '/' . $file);
                        if ($print) {
                            echo "</li>";
                        }
                    } elseif (is_dir($path . '/' . $file) && $file != '.' && $file != '..') {
                        if ($print) {
                            echo "\n<ul>";
                        }
                        recursedir($path . '/' . $file);
                        if ($print) {
                            echo "</ul>";
                        }
                    }
                }
            }
            return true;
        }

        function optimize_jpeg($file)
        {
            if (!isset($GLOBALS['jpegquality']) || !is_numeric($GLOBALS['jpegquality'])) {
                return false;
            }
            if ($GLOBALS['jpegquality'] > 100 || $GLOBALS['jpegquality'] < 0) {
                $GLOBALS['jpegquality'] = 80;
            }
            list($w, $h) = @getimagesize($file);
            if (empty($w) || empty($h)) {
                return false;
            }
            $src = imagecreatefromjpeg($file);
            $tmp = imagecreatetruecolor($w, $h);
            imagecopyresampled($tmp, $src, 0, 0, 0, 0, $w, $h, $w, $h);
            $src = imagejpeg($tmp, $file, $GLOBALS['jpegquality']);
            imagedestroy($tmp);
            return true;
        }

        function optimize_png($file)
        {
            if (!isset($GLOBALS['pngquality']) || !is_numeric($GLOBALS['pngquality'])) {
                return false;
            }
            if ($GLOBALS['pngquality'] > 9 || $GLOBALS['pngquality'] < 0) {
                $GLOBALS['pngquality'] = 9;
            }
            list($w, $h) = @getimagesize($file);
            if (empty($w) || empty($h)) {
                return false;
            }
            $src = imagecreatefrompng($file);
            $tmp = imagecreatetruecolor($w, $h);


            imagefill($tmp, 0, 0, imagecolorallocatealpha($tmp, 0, 0, 0, 127));
            imagesavealpha($tmp, true);

            imagecopyresampled($tmp, $src, 0, 0, 0, 0, $w, $h, $w, $h);
            $src = imagepng($tmp, $file, $GLOBALS['pngquality']);
            imagedestroy($tmp);
            return true;
        }

        ?>
    </body>
</html>
