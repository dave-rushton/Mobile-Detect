<?php
include('../../config/config.php');
include('../patchworks.php');
if (!isset($_SESSION['s_log_id'])) header('location: login.php');

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);



error_reporting(E_ALL);
date_default_timezone_set('Europe/London');
ini_set('max_execution_time', 0);



$max= 1000000;
$start=0;
$totaldel = 0;
$totalkept = 0;
$bytes = 0;
//$externalstr = 'uploads/images/';
$externalstr = 'uploads/images/products/';
if(!empty($_POST['action'])){
   $action = $_POST['action'];
}else{
    die();
};
$file = file_get_contents($_FILES['file']['tmp_name']);


//safety
if(substr($_FILES['file']['name'], -4)==".sql"){
    $quickstr = $patchworks->productImageSizes;
//    $quickstr = "169-130";
    $externalarrays = explode(',',$quickstr);
    array_push($externalarrays,'');

//    print_r($externalarrays);

//    $file = file_get_contents('../db.sql', $patchworks->docRoot."admin/");
//    echo $file;
    foreach ($externalarrays as $externalarray){
        $start=0;
        $external = $externalstr.$externalarray.'/';
        if ($loggedIn == 0) header('location: login.php');
        if ($handle = opendir($patchworks->docRoot.$external)) {
            while (false !== ($entry = readdir($handle))) {
                $pos = strpos($entry,".jpg");
                if(empty($pos)){
                    $pos = strpos($entry,".png");
                }
                if(empty($pos)){
                    $pos = strpos($entry,".PNG");
                }
                if(empty($pos)){
                    $pos = strpos($entry,".JPG");
                }
                if(empty($pos)){
                    $pos = strpos($entry,".jpeg");
                }

                if($pos >0){
                    if($start<$max){
                        if ($entry != "." && $entry != "..") {

                            if(empty(strpos($file,$entry))){
//                                echo "delete $entry <br/>";
                                $totaldel++;
                                $bytes+=filesize($patchworks->docRoot.$external.$entry);
                                if($action=="delete"){
                                    if (!unlink($patchworks->docRoot.$external.$entry))
                                    {
//                                        echo ("Error deleting");
                                    }
                                    else
                                    {
//                                        echo ("Deleted $entry");
                                    }
                                }

                            }else{
                                $totalkept++;
//                                echo "dont delete $entry <br/>";
                            }
                        }
                        $start++;
                    }
                }
            }
            closedir($handle);
        }
    }
}
$kb = $bytes/1024;
$mb = $kb/1024;
$ObjPrp=new stdClass();
$ObjPrp->flesiz=round($mb,2)."mb";
$ObjPrp->fledel=$totaldel;
$ObjPrp->flekep=$totalkept;
$ObjPrp->fleext=substr($_FILES['file']['name'], -4);
echo json_encode($ObjPrp);

//    mysqldump -uUSERNAME -p DATABASE_NAME > database-dump.sql
//    grep -i database-dump.sql "Search string"
?>