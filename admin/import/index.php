<?php
die();
include('../../config/config.php');
include('../patchworks.php');
require_once("../products/classes/product_types.cls.php");
require_once("../products/classes/products.cls.php");



$productDAO = new PrtDAO();
$varientDAO = new PrdDAO();

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');



$row = 1;

$object = new stdClass();

if (($handle = fopen($patchworks->docRoot.'admin/import/machines_all.csv', "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
//        if ($row==3){
//            die("remove die on row 4");
//        }
        $object = new stdClass();
        $custom_object = new stdClass();

        $num = count($data);
        echo "<p> $num fields in line $row: <br /></p>\n";
        $row++;
        $varient = new stdClass();
        $object->prtspc ="";
        $object->hompag =0;
        $object->prttag ='';
        $object->vat_id =0;
        $object->prtnam ="";
        $object->prtnam = "";
        $object->machine_title = "";
        for ($c=0; $c < $num; $c++) {
            switch($c){
                case 0:
                    //Product Number
                    $datacustom =  $data[$c];
                    $object->prt_id = 0;
//                    if(is_int($object->prt_id)){
//                        $object->prt_id = $datacustom;
//                    }

                break;
                case 1:
                    //Manufacturer
                    $object->manufacturer = $data[$c];
                    break;
                case 2:
                    //Blank
                case 3:
                    $object->machine_type = "" ;
                break;
                case 4:
                    //subcategory
                    //check related
                    $object->machine_subcategory = $data[$c] ;
                break;
                case 5:
                    $customdata = $data[$c];
                    $customdata = strtolower($customdata);
                    $customdata = str_replace(" ","-",$customdata);
                    $customdata = str_replace(" ","-",$customdata);
                    $customdata = str_replace(".","-",$customdata);
                    $customdata = str_replace("/","-",$customdata);
                    $customdata = str_replace("&","-and-",$customdata);
                    $customdata = str_replace("--","-",$customdata);
                    $customdata = str_replace("--","-",$customdata);
                    $customdata = str_replace("--","-",$customdata);
                    $customdata = str_replace("--","-",$customdata);
                    $customdata = str_replace("--","-",$customdata);
                    $customdata = str_replace("--","-",$customdata);
                    $object->seourl = $customdata ;
                    //blank possibly url
                break;
                case 6:

                    if(!empty($data[$c])){
                        $object->prtnam = $data[$c];
                        $object->machine_title = $data[$c];
                    }else{

                    }

                break;
                case 7:
                    $object->machine_code = $data[$c];
                break;
                case 8:
                   //BLANK
                break;
                case 9:
                    $object->seokey = $data[$c];
                break;
                case 10:
                   //BLANK
                break;
                case 11:
                   //Description
                    //TODO REVIEW SEODSC
                    $object->seodsc = $data[$c];
                    $custom_object->maindsc = '{"name":"maindsc","value":"'.htmlspecialchars_decode("<p>".$data[$c]."</p>").'"},';

                break;
                case 12:
                   //SEO DSC?
//                    $object->seodsc = $data[$c];
                break;
                case 13:
                    $object->heading_one = $data[$c];
                    //BLANK - suspect content
                    //Heading One? CMS
                    break;
                case 14:
                    $object->header_two = $data[$c];
                    //BLANK  - suspect content
                    //Heading Two? CMS
                break;
                case 15:
                    //BLANK
                    //
                break;
                case 16:
                    //?
                break;
                case 17:
                    //?
                break;
                case 18:
                    $object->feature_one = $data[$c];
                    //FEATURE 1
                break;
                case 19:
                    $object->feature_two = $data[$c];
                    //BLANK
                break;
                case 20:
                    //BLANK
//                    $std = new stdClass();
//                    $std->name = "maindsc";
//                    $std->value = $data[$c];
                    //TODO WHATS THIS
//                    $custom_object->maindsc = '{"name":"maindsc","value":"'.htmlspecialchars_decode($data[$c]).'"},';

//

                break;
                case 21:
                    //Optional Features:
                    $object->optional_features = $data[$c];
                case 23:
                    //Optional Features:
                    $object->technical_features = $data[$c];
                break;
                case 32:
//                    nMFjehhfAgw
//                    $customdata = $data[$c];
                    if(!empty($data[$c]) && $data[$c] != NULL)
                    {

                        $datacustom = str_replace('<iframe width="640" height="480" src="',"",$data[$c]);
                        $datacustom = str_replace('"frameborder="0" allowfullscreen></iframe><br />',"",$datacustom);
                        $datacustom = str_replace('//',"",$datacustom);
                        $datacustom = str_replace('embed/',"",$datacustom);
                        $datacustom = str_replace('www.youtube.com/',"",$datacustom);
                        $datacustom = str_replace('?rel=0',"",$datacustom);
                        $datacustom = str_replace('frameborder="0"',"",$datacustom);
                        $datacustom = str_replace('allowfullscreen',"",$datacustom);
                        $datacustom = str_replace('></iframe>',"",$datacustom);
                        $datacustom = str_replace('https://i.ytimg.com/vi/',"",$datacustom);
                        $datacustom = str_replace('/sddefault.jpg',"",$datacustom);
                        $datacustom = str_replace(' ',"",$datacustom);
                        $datacustom = str_replace(' ',"",$datacustom);
                        $datacustom = str_replace(' ',"",$datacustom);
                        $datacustom = str_replace(' ',"",$datacustom);
                        $datacustom = str_replace('"',"",$datacustom);
                        $custom_object->youtube = '{"name":"youtube","value":"'.$datacustom.'"},';
                    }

                break;
            }
            echo $c ." - ".$data[$c] . "<br />\n";
        }
        $object->unipri = 0.00;
        $object->buypri = 0.00;
        $object->delpri = 0.00;
//        if(!empty($custom_object) && strlen($object->prtnam) > 3){
//            $str .= "[";
//            foreach ($custom_object as $key=>$custom){
//
//                $str .= "{";
//                $str .= '"name":';
//                $str .= '"'.$key.'"';
//                $str .= ',"value":';
//                $str .= '"'.$custom.'"';
//                $str .= "},";
//
//                echo($key);
//            }
//            $str .= "]";
//        }

        $str ="";
        $i = 0;
        foreach ($custom_object as $custom){
            $i++;
            if($i < count($custom-1)){
                $str.=$custom;
            }else{
                $str.=str_replace("},","}",$custom);
            }

        }
        $object->prtobj = "[".$str."]";
//        $object->prtobj = $str;

//        die($str);
//        die($object->prtobj);
//        [{"name":"subnam","value":""},{"name":"maindsc","value":""},{"name":"youtube","value":""},[{"name":"fr_prdnam","value":""},{"name":"fr_keywrd","value":""},{"name":"fr_seodsc","value":""},{"name":"fr_prddsc","value":""},{"name":"fr_prdspc","value":""},{"name":"ge_prdnam","value":""},{"name":"ge_keywrd","value":""},{"name":"ge_seodsc","value":""},{"name":"ge_prddsc","value":""},{"name":"ge_prdspc","value":""},{"name":"sp_prdnam","value":""},{"name":"sp_keywrd","value":""},{"name":"sp_seodsc","value":""},{"name":"sp_prddsc","value":""},{"name":"sp_prdspc","value":""}]]

//        if(!empty($object->prtdsc)){
        echo "<pre>";

        print_r($object);
        echo "</pre>";
            if(strlen($object->prtnam) > 2 && strlen($object->prtnam)<70){

                $id = $productDAO->update($object);





            $varient->prt_id = $id;
            $varient->seourl = $object->seourl;
            $varient->prdnam = $object->prtnam;
            $varient->prddsc = "";
            $varient->sta_id = 0;
            $varient->unipri = 0;
            $varient->buypri = 0;
            $varient->delpri = 0;
            $varient->tblnam = 0;
            $varient->tbl_id = 0;
            $varient->atr_id = 0;
            $varient->usestk = 0;
            $varient->in_stk = 0;
            $varient->on_ord = 0;
            $varient->on_del = 0;
            $varient->weight = 0;
            $varient->srtord = 1000;
            $varient->vat_id = 0;

            $varient->prdspc = "";
            $varient->prdtag = "";
            $varientDAO->update($varient);

            echo "<pre>";

            print_r($varient);
            echo "</pre>";


            echo "<hr/>";
            echo "<hr/>";
            echo "<hr/>";
            echo "<hr/>";
            echo $id ." PRODUCT ID";
            echo "<hr/>";
            echo "<hr/>";
            echo "<hr/>";
            echo "<hr/>";
            echo "<hr/>";
//        }
            }
            else{
//                echo "ISSSUE -----------------------".$object->prt_id;
            }
    }

    fclose($handle);



}
