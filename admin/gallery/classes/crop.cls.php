<?php

class CropAvatar extends db
{
    private $src;
    private $data;
    private $file;
    private $docroot;
    private $newW;
    private $newH;
    private $newFileName;
    private $newExtension;
    private $dst;
    private $type;
    private $extension;
    private $srcDir = 'uploads/images/';
    private $dstDir = 'uploads/images/';
    private $msg;

    function __construct($src, $data, $file, $docroot, $size, $acWidth, $acHeight)
    {
        $this->acWidth = $acWidth;
        $this->acHeight = $acHeight;
        $this->setDocRoot($docroot);
        $this->setSize($size);
        $this->setSrc($src);
        $this->setData($data);
        //$this->setFile($file);
        $this->crop($this->src, $this->dst, $this->data,$src);
        $this->optimise($src, $data);
//        $this->makeWebP($src,$size);
    }
    private function makeWebP($src,$size)
    {
        ob_start();
//        $fileName = $src;
        $fileName = $src;
//        $file = $fileName;
        $file = $fileName;
        $length = strlen($fileName);
        if($length>4){
            $extension = substr($file,$length-4,4);
            $filename = substr($file,0,-4);
            switch($extension){
                case ".jpg":
                    $file=$filename.'.jpg';

                    $image=  imagecreatefromjpeg($file);
//                    $image->load("uploads/images/" . DIRECTORY_SEPARATOR . $fileName);
                    ob_start();
                    imagejpeg($image,NULL,60);
                    $cont = ob_get_contents();
                    ob_end_clean();
                    imagedestroy($image);
                    $content =  imagecreatefromstring($cont);

                    $cusfile = str_replace('.jpg', '', $file);
                    imagewebp($content, $cusfile.'.webp',60);
                    imagedestroy($content);
                    break;
                case ".JPG":
                    $file=$filename.'.JPG';
                    $image=  imagecreatefromjpeg($file);
                    ob_start();
                    imagejpeg($image,NULL,60);
                    $cont=  ob_get_contents();
                    ob_end_clean();
                    imagedestroy($image);
                    $content =  imagecreatefromstring($cont);
                    imagewebp($content,
                        str_replace('.jpg', '', $file).'.webp',60);
                    imagedestroy($content);


                    break;
                case ".png":
                    $file=$filename.'.png';
                    $image=  imagecreatefromjpeg($this->docroot."uploads/images/169-130/".$file);
                    ob_start();
                    imagepng($image);
                    imagealphablending($image, false);
                    imagesavealpha($image, true);
                    imagepalettetotruecolor($image);
                    $cont=  ob_get_contents();
                    ob_end_clean();
                    $content =  imagecreatefromstring($cont);
                    imagewebp($content, $size."/".str_replace('.png', '', $file).'.webp',60);
                    imagedestroy($image);

                    break;
                case ".PNG":
                    $file=$filename.'.PNG';
                    $image=  imagecreatefrompng($file);
                    ob_start();
                    imagepng($image);
                    imagealphablending($image, false);
                    imagesavealpha($image, true);

                    imagepalettetotruecolor($image);
                    $cont=  ob_get_contents();
                    ob_end_clean();
                    $content =  imagecreatefromstring($cont);
                    imagewebp($content, $size."/".str_replace('.PNG', '', $file).'.webp',60);
                    imagedestroy($image);


                    break;
                default:
                    break;
            }
        }
    }
    private function setSrc($src)
    {
        //echo getcwd();
        if (!empty($src)) {
            $newFileName = explode("/", $src);
            $newFileName = $newFileName[count($newFileName) - 1];
            $newFileName = explode(".", $newFileName);
            $this->newFileName = $newFileName[0];
            $this->newExtension = $newFileName[1];
            $src = $this->docroot.'uploads/images/'.$this->newFileName.'.'.$this->newExtension;
            //echo $src;
            if (function_exists('exif_imagetype')) {
                $type = exif_imagetype($src);
            } else {
                $r = getimagesize($src);
                $type = $r[2];
            }
            $type = exif_imagetype($src);
//          if (empty($type)) {
//
//              if ($this->newExtension == 'jpg') {
//                  $type = IMAGETYPE_JPEG;
//              }
//              if ($this->newExtension == 'png') {
//                  $type = IMAGETYPE_PNG;
//              }
//              if ($this->newExtension == 'bmp') {
//                  $type = IMAGETYPE_BMP;
//              }
//
//              if ($this->newExtension == 'gif') {
//                  $type = IMAGETYPE_GIF;
//              }
//
//          }
            //echo $this->newFileName.'~'.image_type_to_extension($type).'#';
            if ($type) {
                $this->src = $src;
                $this->type = $type;
                $this->extension = image_type_to_extension($type);
                $this->setDst();
            }
        }
    }
    private function setData($data)
    {
        if (!empty($data)) {
            $this->data = json_decode(stripslashes($data));
        }
    }
    private function setFile($file)
    {
        $errorCode = $file['error'];
        if ($errorCode === UPLOAD_ERR_OK) {
            $type = exif_imagetype($file['tmp_name']);
            if ($type) {
                $dir = $this->docroot . $this->srcDir;
                if (!file_exists($dir)) {
                    mkdir($dir, 0777);
                }
                $extension = image_type_to_extension($type);
                $src = $dir . '/' . $this->newFileName . $extension;
                if ($type == IMAGETYPE_GIF || $type == IMAGETYPE_JPEG || $type == IMAGETYPE_PNG) {
                    if (file_exists($src)) {
                        unlink($src);
                    }
                    $result = move_uploaded_file($file['tmp_name'], $src);
                    if ($result) {
                        $this->src = $src;
                        $this->type = $type;
                        $this->extension = $extension;
                        $this->setDst();
                    } else {
                        $this->msg = 'Failed to save file';
                    }
                } else {
                    $this->msg = 'Please upload image with the following types: JPG, PNG, GIF';
                }
            } else {
                $this->msg = 'Please upload image file';
            }
        } else {
            $this->msg = $this->codeToMessage($errorCode);
        }
    }
    private function setDocRoot($docroot)
    {
        if (!empty($docroot)) {
            $this->docroot = $docroot;
        }
    }
    private function setSize($size)
    {
        if (!empty($size)) {
            $this->dstDir = 'uploads/images/' . $size;
            $newSize = explode("-", $size);
            $this->newW = $newSize[0];
            $this->newH = $newSize[1];
        } else {
            $this->newW = $this->acWidth;
            $this->newH = $this->acHeight;
            $this->dstDir = 'uploads/images/';
        }
    }
    private function setDst()
    {
        //$dir = $this->docroot . $this->dstDir;
        $dir = $this->docroot.$this->dstDir;
        if (!file_exists($dir)) {
            mkdir($dir, 0777);
        }
        $this->dst = $dir . '/' . $this->newFileName . '.' . $this->newExtension;
    }
    private function crop($src, $dst, $data,$srcname)
    {
        //echo $src.' : '.$dst.' : '.$data;
        if (!empty($src) && !empty($dst) && !empty($data)) {
            switch ($this->type) {
                case IMAGETYPE_GIF:
                    $src_img = imagecreatefromgif($src);
                    break;
                case IMAGETYPE_JPEG:
                    $src_img = imagecreatefromjpeg($src);
                    break;
                case IMAGETYPE_PNG:
                    $src_img = imagecreatefrompng($src);
                    break;
            }
            if (!$src_img) {
                $this->msg = "Failed to read the image file";
                return;
            }
            $size = getimagesize($src);
            $size_w = $size[0]; // natural width
            $size_h = $size[1]; // natural height
            $src_img_w = $size_w;
            $src_img_h = $size_h;
            $degrees = $data->rotate;
            // Rotate the source image
            if (is_numeric($degrees) && $degrees != 0) {
                // PHP's degrees is opposite to CSS's degrees
                $new_img = imagerotate($src_img, -$degrees, imagecolorallocatealpha($src_img, 0, 0, 0, 127));
                imagedestroy($src_img);
                $src_img = $new_img;
                $deg = abs($degrees) % 180;
                $arc = ($deg > 90 ? (180 - $deg) : $deg) * M_PI / 180;
                $src_img_w = $size_w * cos($arc) + $size_h * sin($arc);
                $src_img_h = $size_w * sin($arc) + $size_h * cos($arc);
                // Fix rotated image miss 1px issue when degrees < 0
                $src_img_w -= 1;
                $src_img_h -= 1;
            }
            $tmp_img_w = $data->width;
            $tmp_img_h = $data->height;
            $dst_img_w = $this->newW;
            $dst_img_h = $this->newH;
            $src_x = $data->x;
            $src_y = $data->y;
            if ($src_x <= -$tmp_img_w || $src_x > $src_img_w) {
                $src_x = $src_w = $dst_x = $dst_w = 0;
            } else if ($src_x <= 0) {
                $dst_x = -$src_x;
                $src_x = 0;
                $src_w = $dst_w = min($src_img_w, $tmp_img_w + $src_x);
            } else if ($src_x <= $src_img_w) {
                $dst_x = 0;
                $src_w = $dst_w = min($tmp_img_w, $src_img_w - $src_x);
            }
            if ($src_w <= 0 || $src_y <= -$tmp_img_h || $src_y > $src_img_h) {
                $src_y = $src_h = $dst_y = $dst_h = 0;
            } else if ($src_y <= 0) {
                $dst_y = -$src_y;
                $src_y = 0;
                $src_h = $dst_h = min($src_img_h, $tmp_img_h + $src_y);
            } else if ($src_y <= $src_img_h) {
                $dst_y = 0;
                $src_h = $dst_h = min($tmp_img_h, $src_img_h - $src_y);
            }
            // Scale to destination position and size
            $ratio = $tmp_img_w / $dst_img_w;
            $dst_x /= $ratio;
            $dst_y /= $ratio;
            $dst_w /= $ratio;
            $dst_h /= $ratio;
            $dst_img = imagecreatetruecolor($dst_img_w, $dst_img_h);
            // Add transparent background to destination image
            imagefill($dst_img, 0, 0, imagecolorallocatealpha($dst_img, 0, 0, 0, 127));
            imagesavealpha($dst_img, true);
            $result = imagecopyresampled($dst_img, $src_img, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);

//            imagejpeg($dst_img, $src_img, 10);


            if ($result) {
                //TODO THIS NEEDS REWRITE
                if (!imagepng($dst_img, $dst)) {
                    $this->msg = "Failed to save the cropped image file";
                }
            } else {
//                $this->msg = "Failed to crop the image file";
            }

            //last /
            $file = substr($src, strrpos($src, '/') + 1);
            $length = strlen($file);
            if($length>4){

                if(substr($file,$length-4,4) == ".jpg" || substr($file,$length-4,4) == "jpeg" ){
                    imagejpeg($dst_img, $this->docroot.'/uploads/images/'.$dst_img_w.'-'.$dst_img_h.'/' . $file, $this->quality);
                }
            }

            imagedestroy($src_img);
            imagedestroy($dst_img);
        }
    }
    private function optimise($src, $data)
    {
        //echo $src.' : '.$dst.' : '.$data;


        if (!empty($src) && !empty($data)) {
            switch ($this->type) {
                case IMAGETYPE_GIF:
                    $src_img = imagecreatefromgif($src);
                    break;
                case IMAGETYPE_JPEG:
                    $src_img = imagecreatefromjpeg($src);
                    break;
                case IMAGETYPE_PNG:
                    $src_img = imagecreatefrompng($src);
                    break;
            }
            if (!$src_img) {
                $this->msg = "Failed to read the image file";
                return;
            }


        }
    }
    private function codeToMessage($code)
    {
        switch ($code) {
            case UPLOAD_ERR_INI_SIZE:
                $message = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $message = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
                break;
            case UPLOAD_ERR_PARTIAL:
                $message = 'The uploaded file was only partially uploaded';
                break;
            case UPLOAD_ERR_NO_FILE:
                $message = 'No file was uploaded';
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $message = 'Missing a temporary folder';
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $message = 'Failed to write file to disk';
                break;
            case UPLOAD_ERR_EXTENSION:
                $message = 'File upload stopped by extension';
                break;
            default:
                $message = 'Unknown upload error';
        }
        return $message;
    }
    public function getResult()
    {
        return !empty($this->data) ? $this->dst : $this->src;
    }
    public function getMsg()
    {
        return $this->msg;
    }
}