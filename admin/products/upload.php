<?php



require_once("../../config/config.php");



require_once("../patchworks.php");







include('../system/SimpleImage.php');



include('../system/imagemanipulation.php');







$userAuth = new AuthDAO();



$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);



if ($loggedIn == 0) {



    die();



}







ini_set('gd.jpeg_ignore_warning', 1);







// HTTP headers for no cache etc



header("Expires: Mon, 26 Jul 2027 05:00:00 GMT");



header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");



header("Cache-Control: no-store, no-cache, must-revalidate");



header("Cache-Control: post-check=0, pre-check=0", false);



header("Pragma: no-cache");







function getResizeVar( $iReqX, $iReqY, $iActX, $iActY ) {



    if ( $iActX >= $iActY ) {



        // Landscape



        $percentage = ($iReqX / $iActX) * 100;



        // Will the image fill the space after resize?



        if ( (($iActY / 100) * $percentage) > $iReqY ) {



            // Actual height greater than required height - OK



            return 'p';



        } else {



            return 'l';



        }



    } else {



        // Portrait



        $percentage = ($iReqX / $iActX) * 100;



        // Will the image fill the space after resize?



        if ( (($iActX / 100) * $percentage) > $iReqX ) {



            // Actual width greater than required width - OK



            return 'l';



        } else {



            return 'p';



        }



    }



}







// Settings



//$targetDir = $_SERVER['DOCUMENT_ROOT'].'uploads/images/';







$targetDir = '../../uploads/images/products/';







//$cleanupTargetDir = false; // Remove old files



//$maxFileAge = 60 * 60; // Temp file age in seconds







// 5 minutes execution time



@set_time_limit(5 * 60);







// Uncomment this one to fake upload time



// usleep(5000);







// Get parameters



$chunk = isset($_REQUEST["chunk"]) ? $_REQUEST["chunk"] : 0;



$chunks = isset($_REQUEST["chunks"]) ? $_REQUEST["chunks"] : 0;



$fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';







// Clean the fileName for security reasons



$fileName = preg_replace('/[^\w\._]+/', '', $fileName);







// Make sure the fileName is unique but only if chunking is disabled



if ($chunks < 2 && file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName)) {



    $ext = strrpos($fileName, '.');



    $fileName_a = substr($fileName, 0, $ext);



    $fileName_b = substr($fileName, $ext);







    $count = 1;



    while (file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b))



        $count++;







    $fileName = $fileName_a . '_' . $count . $fileName_b;







}







// Create target dir



if (!file_exists($targetDir))



    @mkdir($targetDir);







// Remove old temp files



/* this doesn't really work by now



	



if (is_dir($targetDir) && ($dir = opendir($targetDir))) {



	while (($file = readdir($dir)) !== false) {



		$filePath = $targetDir . DIRECTORY_SEPARATOR . $file;







		// Remove temp files if they are older than the max age



		if (preg_match('/\\.tmp$/', $file) && (filemtime($filePath) < time() - $maxFileAge))



			@unlink($filePath);



	}







	closedir($dir);



} else



	die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');



*/



















// Look for the content type header



if (isset($_SERVER["HTTP_CONTENT_TYPE"]))



    $contentType = $_SERVER["HTTP_CONTENT_TYPE"];







if (isset($_SERVER["CONTENT_TYPE"]))



    $contentType = $_SERVER["CONTENT_TYPE"];







// Handle non multipart uploads older WebKit versions didn't support multipart in HTML5



if (strpos($contentType, "multipart") !== false) {



    if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {







        $actualFileName = $_FILES['file']['name'];



        $actualFileType = $_FILES['file']['type'];







        // Open temp file



        $out = fopen($targetDir . DIRECTORY_SEPARATOR . $fileName, $chunk == 0 ? "wb" : "ab");



        if ($out) {



            // Read binary input stream and append it to temp file



            $in = fopen($_FILES['file']['tmp_name'], "rb");







            if ($in) {



                while ($buff = fread($in, 4096))



                    fwrite($out, $buff);







                // Calculate size of uploaded image







                list($width, $height, $type, $attr) = getimagesize($targetDir . DIRECTORY_SEPARATOR . $fileName);







//                if ($width > 2000) {



//



//                    $image = new SimpleImage();



//                    $image->load($targetDir . DIRECTORY_SEPARATOR . $fileName);



//                    $image->resizeToWidth(2000);



//                    $image->save($targetDir . DIRECTORY_SEPARATOR . $fileName);



//



//                }







                $resize = explode(",", $_GET['resize']);







                for ($r = 0; $r < count($resize); $r++) {







                    $resizeSizes = explode( "-", $resize[$r] );







                    if (count($resizeSizes) > 1) {







                        $trimWidth = $resizeSizes[0];



                        $trimHeight = $resizeSizes[1];







                        $chkDirectory = $targetDir.$trimWidth.'-'.$trimHeight.'/';



                        if (!file_exists($chkDirectory)) @mkdir($chkDirectory);







                        if ( getResizeVar( $trimWidth, $trimHeight, ceil($width), ceil($height) ) == 'p' ) {







                            // Resize By Portrait







                            $image = new SimpleImage();



                            $image->load($targetDir . DIRECTORY_SEPARATOR . $fileName);



                            $image->resizeToWidth($trimWidth);



                            $smallImage = $targetDir.$trimWidth.'-'.$trimHeight.'/'.$fileName;



                            $image->save($smallImage);







                            list($width, $height, $type, $attr) = getimagesize($smallImage);







                            $PadTop = 0;



                            if ( $height > $trimHeight ) {



                                $PadTop = ($height-$trimHeight)/2;



                            }







                            $objImage = new ImageManipulation($smallImage);



                            $objImage->setCrop(0, $PadTop, $trimWidth, $trimHeight);



                            $objImage->save($smallImage);











                        } else {







                            // Resize to landscape







                            $image = new SimpleImage();



                            $image->load($targetDir . DIRECTORY_SEPARATOR . $fileName);



                            $image->resizeToHeight($trimHeight);



                            $smallImage = $targetDir.$trimWidth.'-'.$trimHeight.'/'.$fileName;



                            $image->save($smallImage);







                            list($width, $height, $type, $attr) = getimagesize($smallImage);







                            $PadLft = 0;



                            if ( $width >= $trimWidth ) {



                                $PadLft = ($width-$trimWidth)/2;



                            }







                            if ($PadLft < 0) { $PadLft = 0;}







                            $objImage = new ImageManipulation($smallImage);



                            $objImage->setCrop($PadLft, 0, $trimWidth, $trimHeight);



                            $objImage->save($smallImage);







                        }







                    } else {







                        // Simple resize to width







                        $chkDirectory =$targetDir.$resizeSizes[0].'/';



                        if (!file_exists($chkDirectory)) @mkdir($chkDirectory);







                        $image = new SimpleImage();



                        $image->load($targetDir . DIRECTORY_SEPARATOR . $fileName);



                        $image->resizeToWidth($resizeSizes[0]);



                        $smallImage = $targetDir.$resizeSizes[0].'/'.$fileName;



                        $image->save($smallImage);







                    }







                }







            } else



                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');







            fclose($in);



            fclose($out);







            //



            // create upload (problem with chunking creating multiple files, could include a test to find record and create if doesnt exist)



            //







            $sql = 'SELECT * FROM uploads WHERE filnam = "'.addslashes($fileName).'" LIMIT 1';







            $recordSet = $patchworks->dbConn->prepare($sql);



            $uploadRec = $recordSet->execute($qryArray);







            //if (!$uploadRec) {







            $qryArray = array();



            $qryArray["filnam"] = $fileName;



            $qryArray["uplttl"] = $actualFileName; //(isset($_REQUEST['uplttl'])) ? $_REQUEST['uplttl'] : 'Image';



            $qryArray["upldsc"] = $actualFileName; //(isset($_REQUEST['upldsc'])) ? $_REQUEST['upldsc'] : 'Image';



            $qryArray["credat"] = date("Y-m-d H:i:s");



            $qryArray["tblnam"] = (isset($_REQUEST['tblnam'])) ? $_REQUEST['tblnam'] : '';



            $qryArray["tbl_id"] = (isset($_REQUEST['tbl_id']) && is_numeric($_REQUEST['tbl_id'])) ? $_REQUEST['tbl_id'] : 0;



            $qryArray["filsiz"] = 0;



            $qryArray["filtyp"] = '';



            $qryArray["srtord"] = 99;



            $qryArray["urllnk"] = '';







            $sql = 'INSERT INTO uploads



					(



					filnam,



					uplttl,



					upldsc,



					credat,



					tblnam,



					tbl_id,



					filsiz,



					filtyp,



					srtord,



					urllnk



					)



					VALUES



					(



					:filnam,



					:uplttl,



					:upldsc,



					:credat,



					:tblnam,



					:tbl_id,



					:filsiz,



					:filtyp,



					:srtord,



					:urllnk



					);';







            $recordSet = $patchworks->dbConn->prepare($sql);



            $recordSet->execute($qryArray);







            //}











            //



            // if SVG copy to 169-130



            //







            if ($actualFileType == 'image/svg+xml') {







                for ($r = 0; $r < count($resize); $r++) {







                    copy($targetDir . DIRECTORY_SEPARATOR . $fileName, $targetDir . DIRECTORY_SEPARATOR . $resize[$r] . DIRECTORY_SEPARATOR . $fileName);







                }



            }











            @unlink($_FILES['file']['tmp_name']);



        } else



            die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');



    } else



        die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');



} else {



    // Open temp file



    $out = fopen($targetDir . DIRECTORY_SEPARATOR . $fileName, $chunk == 0 ? "wb" : "ab");



    if ($out) {



        // Read binary input stream and append it to temp file



        $in = fopen("php://input", "rb");







        if ($in) {



            while ($buff = fread($in, 4096))



                fwrite($out, $buff);



        } else



            die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');







        fclose($in);



        fclose($out);



    } else



        die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');



}







// Return JSON-RPC response



die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');







?>