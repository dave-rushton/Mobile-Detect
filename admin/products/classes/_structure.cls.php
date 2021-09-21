<?php



class StrDAO extends db {

	

	function select($Str_ID = NULL, $TblNam=NULL, $Tbl_ID = NULL, $ReqObj = false) {

	

		$qryArray = array();

		$sql = 'SELECT 

				*

				FROM structure

				WHERE TRUE';

		

		if (!is_null($Str_ID)) {

			$sql .= ' AND str_id = :str_id ';

			$qryArray["str_id"] = $Str_ID;

		} else {

			if (!is_null($TblNam)) {

				$sql .= ' AND tblnam = :tblnam ';

				$qryArray["tblnam"] = $TblNam;

			}

			if (!is_null($Tbl_ID) && is_numeric($Tbl_ID)) {

				$sql .= ' AND tbl_id = :tbl_id ';

				$qryArray["tbl_id"] = $Tbl_ID;

			}

		}

		

		$sql .= ' ORDER BY srtord';

		

		//echo $sql;

		

		return $this->run($sql, $qryArray, $ReqObj);



	}



    function selectLevel($Par_ID = NULL, $TblNam=NULL, $Tbl_ID = NULL, $ReqObj = false) {



        $qryArray = array();

        $sql = 'SELECT

				*

				FROM structure

				WHERE TRUE';



        if (!is_null($Par_ID) && is_numeric($Par_ID)) {

            $sql .= ' AND par_id = :par_id ';

            $qryArray["par_id"] = $Par_ID;

        }

        if (!is_null($TblNam)) {

            $sql .= ' AND tblnam = :tblnam ';

            $qryArray["tblnam"] = $TblNam;

        }

        if (!is_null($Tbl_ID) && is_numeric($Tbl_ID)) {

            $sql .= ' AND tbl_id = :tbl_id ';

            $qryArray["tbl_id"] = $Tbl_ID;

        }



        $sql .= ' ORDER BY srtord';



        //echo $sql;



        return $this->run($sql, $qryArray, $ReqObj);



    }

	

	function update($StrCls = NULL) {

	

		if (is_null($StrCls) || !$StrCls) return 'No Record To Update';

		

		$sql = '';

		

		$qryArray = array();

		

		if ($StrCls->str_id == 0) {

			

			$qryArray["tblnam"] = $StrCls->tblnam;

			$qryArray["tbl_id"] = $StrCls->tbl_id;

			$qryArray["par_id"] = $StrCls->par_id;

			$qryArray["strnam"] = $StrCls->strnam;

			$qryArray["seourl"] = $StrCls->seourl;

			$qryArray["srtord"] = $StrCls->srtord;

			$qryArray["sta_id"] = $StrCls->sta_id;

			$qryArray["strobj"] = $StrCls->strobj;



			$sql = "INSERT INTO structure

					(

					tblnam,

                    tbl_id,

                    par_id,

                    strnam,

                    seourl,

                    srtord,

                    sta_id,

                    strobj

					)

					VALUES

					(

					:tblnam,

                    :tbl_id,

                    :par_id,

                    :strnam,

                    :seourl,

                    :srtord,

                    :sta_id,

                    :strobj

					);";

						

		} else {



            $qryArray["tblnam"] = $StrCls->tblnam;

            $qryArray["tbl_id"] = $StrCls->tbl_id;

            $qryArray["par_id"] = $StrCls->par_id;

            $qryArray["strnam"] = $StrCls->strnam;

            $qryArray["seourl"] = $StrCls->seourl;

            $qryArray["srtord"] = $StrCls->srtord;

            $qryArray["sta_id"] = $StrCls->sta_id;

            $qryArray["strobj"] = $StrCls->strobj;

			

			$sql = "UPDATE structure

					SET

					tblnam = :tblnam,

                    tbl_id = :tbl_id,

                    par_id = :par_id,

                    strnam = :strnam,

                    seourl = :seourl,

                    srtord = :srtord,

                    sta_id = :sta_id,

                    strobj = :strobj

					WHERE str_id = :str_id";

			

			$qryArray["str_id"] = $StrCls->str_id;

			

		}

		

		$recordSet = $this->dbConn->prepare($sql);

		$recordSet->execute($qryArray);

		

		return ($StrCls->str_id == 0) ? $this->dbConn->lastInsertId('str_id') : $StrCls->str_id;

	}



    function hardInsert($StrCls = NULL)

    {



        if (is_null($StrCls) || !$StrCls) return 'No Record To Update';



        $sql = '';



        $qryArray = array();



        $qryArray["str_id"] = $StrCls->str_id;

        $qryArray["tblnam"] = $StrCls->tblnam;

        $qryArray["tbl_id"] = $StrCls->tbl_id;

        $qryArray["par_id"] = $StrCls->par_id;

        $qryArray["strnam"] = $StrCls->strnam;

        $qryArray["seourl"] = $StrCls->seourl;

        $qryArray["srtord"] = $StrCls->srtord;

        $qryArray["sta_id"] = $StrCls->sta_id;

        $qryArray["strobj"] = $StrCls->strobj;



        $sql = "INSERT INTO structure

                (

                str_id,

                tblnam,

                tbl_id,

                par_id,

                strnam,

                seourl,

                srtord,

                sta_id,

                strobj

                )

                VALUES

                (

                :str_id,

                :tblnam,

                :tbl_id,

                :par_id,

                :strnam,

                :seourl,

                :srtord,

                :sta_id,

                :strobj

                );";



        //echo $sql.'<br>';

        //var_dump($qryArray);



        $recordSet = $this->dbConn->prepare($sql);

        $recordSet->execute($qryArray);



        return ($StrCls->str_id == 0) ? $this->dbConn->lastInsertId('str_id') : $StrCls->str_id;



    }

	

	function delete($Str_ID = NULL) {

	

		try {

			

			if (!is_null($Str_ID)) {

				$qryArray = array();

				$sql = 'DELETE FROM structure WHERE str_id = :str_id ';

				$qryArray["str_id"] = $Str_ID;

				

				$recordSet = $this->dbConn->prepare($sql);

				$recordSet->execute($qryArray);

				

				return $Str_ID;

				

			}

			

		} catch(PDOException $e) {

			echo 'ERROR: ' . $e->getMessage();

		}

		

	}



    function buildStructure ($Par_ID = NULL, $SeoUrl = NULL, $Ele_ID = NULL, $EleCls = NULL, $admin = false, $language = NULL) {



        if (!is_numeric($Par_ID)) $Par_ID = 0;



        $qryArray = array();

        $sql = 'SELECT * FROM structure WHERE true ';



        if (!is_null($Par_ID)) {

            $sql .= ' AND par_id = :par_id ';

            $qryArray["par_id"] = $Par_ID;

        }



        $sql .= ' ORDER BY srtord';



        $qryArray['par_id'] = $Par_ID;

        $structureRecs = $this->run($sql, $qryArray, false);



        $Ele_ID = (!is_null($Ele_ID)) ? ' id="'.$Ele_ID.'"" ' : '';

        $EleCls = (!is_null($EleCls)) ? ' class="'.$EleCls.'"" ' : '';



        echo '<ul'.$Ele_ID.$EleCls.'>';



        for ($i=0;$i<count($structureRecs);$i++) {



            echo '<li>';



            $languageText = '';

            if ($language == 'FRE') $languageText = $this->getJSONVariable($structureRecs[$i]['prtobj'], 'fr_strnam', false);

            if ($language == 'GER') $languageText = $this->getJSONVariable($structureRecs[$i]['prtobj'], 'ge_strnam', false);

            if ($language == 'ESP') $languageText = $this->getJSONVariable($structureRecs[$i]['prtobj'], 'sp_strnam', false);

            if (empty($languageText)) $languageText = (!empty($structureRecs[$i]['strnam'])) ? $structureRecs[$i]['strnam'] : $structureRecs[$i]['strnam'];



            if ($admin) {



                echo '<a href="' . $structureRecs[$i]['str_id'] . '" class="selectStructureBtn" data-str_id="' . $structureRecs[$i]['str_id'] . '">';

                echo $languageText;

                //echo $structureRecs[$i]['strnam'];

                echo '</a>';



            } else {



                echo '<a href="' . $SeoUrl . '/category/' . $structureRecs[$i]['str_id'] . '/' . $structureRecs[$i]['seourl'] . '">';

                //echo $structureRecs[$i]['strnam'];

                echo $languageText;

                echo '<i></i></a>';



            }



            $this->ShowSubCats($structureRecs[$i]['str_id'], $SeoUrl, $admin, $language);



            echo '</li>';



        }



        echo '</ul>';



    }



    function ShowSubCats($Par_ID, $SeoUrl = NULL, $admin = false, $language = NULL)

    {



        $qryArray = array();

        $sql = 'SELECT * FROM structure WHERE par_id = :par_id ORDER BY srtord';

        $qryArray['par_id'] = $Par_ID;



        $structureRecs = $this->run($sql, $qryArray, false);



        if (count($structureRecs) > 0) {



            echo '<ul>';



            for ($i=0;$i<count($structureRecs);$i++) {



                echo '<li>';



                if ($admin) {

                    echo '<a href="' . $structureRecs[$i]['str_id'] . '" class="selectStructureBtn" data-str_id="' . $structureRecs[$i]['str_id'] . '">';

                    echo $structureRecs[$i]['strnam'];

                    echo '</a>';

                } else {

                    echo '<a href="' . $SeoUrl . '/category/' . $structureRecs[$i]['str_id'] . '/' . $structureRecs[$i]['seourl'] . '">';

                    echo $structureRecs[$i]['strnam'];

                    echo '</a>';

                }



                $this->ShowSubCats($structureRecs[$i]['str_id'], $SeoUrl, $admin);



                echo '</li>';

            }



            echo '</ul>';



        }

    }





    function buildObjectStructure2 ($Par_ID = 0, $SeoUrl = NULL, $Ele_ID = NULL, $EleCls = NULL, $admin = false) {

        $returnHTML = '';

        $returnObject = Array();

        if (!is_numeric($Par_ID)) $Par_ID = 0;

        $qryArray = array();

        $sql = 'SELECT * FROM structure WHERE par_id = :par_id ORDER BY srtord';

        $qryArray['par_id'] = $Par_ID;

        $structureRecs = $this->run($sql, $qryArray, false);

        $Ele_ID = (!is_null($Ele_ID)) ? ' id="'.$Ele_ID.'"" ' : '';

        $EleCls = (!is_null($EleCls)) ? ' class="'.$EleCls.'"" ' : '';

        if (count($structureRecs) > 0) {

            for ($i = 0; $i < count($structureRecs); $i++) {

                $inner = new stdClass();

                $strnam = $structureRecs[$i]['strnam'];

                $str_id = $structureRecs[$i]['str_id'];

                $strcat = $structureRecs[$i]['seourl'];

                $strobj= $structureRecs[$i]['strobj'];

                $seourl = $SeoUrl . '/category/' . $structureRecs[$i]['str_id'] . '/' . $structureRecs[$i]['seourl'];

                $inner->strnam=$strnam;

                $inner->str_id=$str_id;

                $inner->strcat=$strcat;

                $inner->seourl=$seourl;

                $inner->strobj=$strobj;

                array_push($returnObject,$inner);

            }

        }

        return $returnObject;

    }

    function buildStructure2 ($Par_ID = 0, $SeoUrl = NULL, $Ele_ID = NULL, $EleCls = NULL, $admin = false, $language = NULL) {



        $returnHTML = '';



        if (!is_numeric($Par_ID)) $Par_ID = 0;



        $qryArray = array();

        $sql = 'SELECT * FROM structure WHERE par_id = :par_id ORDER BY srtord';

        $qryArray['par_id'] = $Par_ID;

        $structureRecs = $this->run($sql, $qryArray, false);



        $Ele_ID = (!is_null($Ele_ID)) ? ' id="'.$Ele_ID.'"" ' : '';

        $EleCls = (!is_null($EleCls)) ? ' class="'.$EleCls.'"" ' : '';



        if (count($structureRecs) > 0) {



            $returnHTML .= '<ul' . $Ele_ID . $EleCls . '>';



            for ($i = 0; $i < count($structureRecs); $i++) {



                $languageText = '';

                if ($language == 'FRE') $languageText = $this->getJSONVariable($structureRecs[$i]['strobj'], 'fr_strnam', false);

                if ($language == 'GER') $languageText = $this->getJSONVariable($structureRecs[$i]['strobj'], 'ge_strnam', false);

                if ($language == 'ESP') $languageText = $this->getJSONVariable($structureRecs[$i]['strobj'], 'sp_strnam', false);

                if (empty($languageText)) $languageText = (!empty($structureRecs[$i]['strnam'])) ? $structureRecs[$i]['strnam'] : $structureRecs[$i]['strnam'];



                $returnHTML .= '<li>';

//

//                if ($admin) {

//                    $returnHTML .= '<a href="' . $structureRecs[$i]['str_id'] . '" class="selectStructureBtn" data-str_id="' . $structureRecs[$i]['str_id'] . '">' . $structureRecs[$i]['strnam'] . '</a>';

//                } else {

//                    $returnHTML .= '<a href="' . $SeoUrl . '/category/' . $structureRecs[$i]['str_id'] . '/' . $structureRecs[$i]['seourl'] . '">';

//

//                    $returnHTML .= $structureRecs[$i]['strnam'] . ' <i></i></a>';

//                }





                if ($admin) {



                    $returnHTML .= '<a href="' . $structureRecs[$i]['str_id'] . '" class="selectStructureBtn" data-str_id="' . $structureRecs[$i]['str_id'] . '">';

                    $returnHTML .= $languageText;

                    //echo $structureRecs[$i]['strnam'];

                    $returnHTML .= '</a>';



                } else {



                    $returnHTML .= '<a href="' . $SeoUrl . '/category/' . $structureRecs[$i]['str_id'] . '/' . $structureRecs[$i]['seourl'] . '">';

                    //echo $structureRecs[$i]['strnam'];

                    $returnHTML .= $languageText;

                    $returnHTML .= '<i></i></a>';



                }





                $this->ShowSubCats2($structureRecs[$i]['str_id'], $SeoUrl, $admin, $language);



                $returnHTML .= '</li>';



            }



            $returnHTML .= '</ul>';



        }



        return $returnHTML;



    }



    function ShowSubCats2($Par_ID, $SeoUrl = NULL, $admin = false, $language = NULL)

    {



        $returnHTML = '';



        $qryArray = array();

        $sql = 'SELECT * FROM structure WHERE par_id = :par_id ORDER BY srtord';

        $qryArray['par_id'] = $Par_ID;



        $structureRecs = $this->run($sql, $qryArray, false);



        if (count($structureRecs) > 0) {



            $returnHTML .= '<ul>';



            for ($i=0;$i<count($structureRecs);$i++) {



                $languageText = '';

                if ($language == 'FRE') $languageText = $this->getJSONVariable($structureRecs[$i]['strobj'], 'fr_strnam', false);

                if ($language == 'GER') $languageText = $this->getJSONVariable($structureRecs[$i]['strobj'], 'ge_strnam', false);

                if ($language == 'ESP') $languageText = $this->getJSONVariable($structureRecs[$i]['strobj'], 'sp_strnam', false);

                if (empty($languageText)) $languageText = (!empty($structureRecs[$i]['strnam'])) ? $structureRecs[$i]['strnam'] : $structureRecs[$i]['strnam'];





                $returnHTML .= '<li>';



                if ($admin) {

                    $returnHTML .= '<a href="' . $structureRecs[$i]['str_id'] . '" class="selectStructureBtn" data-str_id="' . $structureRecs[$i]['str_id'] . '">' . $structureRecs[$i]['strnam'] . '</a>';

                } else {

                    $returnHTML .= '<a href="' . $SeoUrl . '/category/' . $structureRecs[$i]['str_id'] . '/' . $structureRecs[$i]['seourl'] . '">' . $structureRecs[$i]['strnam'] . '</a>';

                }



                $this->ShowSubCats($structureRecs[$i]['str_id'], $SeoUrl, $admin);



                $returnHTML .= '</li>';

            }



            $returnHTML .= '</ul>';



        }



        return $returnHTML;



    }







    function getBreadcrumb( $Str_ID=NULL, $SeoUrl = '', $Prt_ID = NULL, $language = NULL ) {



        $breadCrumbHTML = '';

        $topParent = 0;

        $qryArray = array();

        $sql = 'SELECT * FROM structure WHERE str_id = :str_id ORDER BY srtord DESC';

        $qryArray['str_id'] = $Str_ID;

        $parentPage = $this->run($sql, $qryArray, true);

        if ($parentPage) {



            $languageText = '';

            if ($language == 'FRE') $languageText = $this->getJSONVariable($parentPage->strobj, 'fr_strnam', false);

            if ($language == 'GER') $languageText = $this->getJSONVariable($parentPage->strobj, 'ge_strnam', false);

            if ($language == 'ESP') $languageText = $this->getJSONVariable($parentPage->strobj, 'sp_strnam', false);

            if (empty($languageText)) $languageText = (!empty($parentPage->strnam)) ? $parentPage->strnam : $parentPage->strnam;



            $languageText = $parentPage->strnam;



            if (is_null($Prt_ID)) {

                $breadCrumbHTML = '<li class="active">' . $languageText . '</li>';

            } else {

                $breadCrumbHTML = '<li><a href="'.$SeoUrl.'/category/'.$parentPage->str_id.'/'.$parentPage->seourl.'">'.$languageText.'</a></li>';

            }

            while ($topParent === 0) {

                $parentPage = $this->findParent($parentPage->par_id);



                if ($parentPage) {



                    $languageText = '';

                    if ($language == 'FRE') $languageText = $this->getJSONVariable($parentPage->strobj, 'fr_strnam', false);

                    if ($language == 'GER') $languageText = $this->getJSONVariable($parentPage->strobj, 'ge_strnam', false);

                    if ($language == 'ESP') $languageText = $this->getJSONVariable($parentPage->strobj, 'sp_strnam', false);

                    if (empty($languageText)) $languageText = (!empty($parentPage->strnam)) ? $parentPage->strnam : $parentPage->strnam;



                    $languageText = $parentPage->strnam;



                    if ($parentPage->par_id == 0) {

                        $topParent = 1;

                        $breadCrumbHTML = '<li><a href="'.$SeoUrl.'/category/'.$parentPage->str_id.'/'.$parentPage->seourl.'">'.$languageText.'</a></li></li>'.$breadCrumbHTML;

                    } else {

                        $breadCrumbHTML = '<li><a href="'.$SeoUrl.'/category/'.$parentPage->str_id.'/'.$parentPage->seourl.'">'.$languageText.'</a></li></li>'.$breadCrumbHTML;

                    }

                } else {

                    $topParent = 1;

                }

            }

            $breadCrumbHTML = '<li><a href="'.$this->webRoot.$SeoUrl.'">Shop</a></li></li>'.$breadCrumbHTML;



            //

            // Add Product

            //

            if (!is_null($Prt_ID)) {

                $qryArray = array();

                $sql = 'SELECT * FROM producttypes WHERE prt_id = :prt_id';

                $qryArray['prt_id'] = $Prt_ID;

                $productTypeRec = $this->run($sql, $qryArray, true);

                if (!is_null($productTypeRec->prtnam)) {

                    $breadCrumbHTML = $breadCrumbHTML.'<li class="active">'.$productTypeRec->prtnam.'</li>';

                }

            }



            $breadCrumbHTML = '<ol class="breadcrumb">'.$breadCrumbHTML.'</ol>';

            echo $breadCrumbHTML;

        }

    }



    function findParent($Str_ID=NULL) {



        $qryArray = array();

        $sql = 'SELECT * FROM structure WHERE str_id = :str_id';

        $qryArray['str_id'] = $Str_ID;

        return $this->run($sql, $qryArray, true);



    }





    function deleteStructure ($Str_ID = 0) {



        if (is_numeric($Str_ID)) {



            $qryArray = array();

            $sql = 'SELECT * FROM structure WHERE str_id = :str_id';



            $qryArray['str_id'] = $Str_ID;

            $structureRecs = $this->run($sql, $qryArray, false);



            for ($i = 0; $i < count($structureRecs); $i++) {



                $this->deleteSubCats($structureRecs[$i]['str_id']);



                $qryArray2 = array();

                $sql = 'DELETE FROM structure WHERE str_id = :str_id';

                $qryArray2['str_id'] = $structureRecs[$i]['str_id'];

                $this->run($sql, $qryArray2, false);



            }



        }



    }



    function deleteSubCats($Par_ID)

    {



        $qryArray3 = array();

        $sql = 'SELECT * FROM structure WHERE par_id = :par_id';

        $qryArray3['par_id'] = $Par_ID;



        $structureRecs = $this->run($sql, $qryArray3, false);



        if (count($structureRecs) > 0) {



            for ($i=0;$i<count($structureRecs);$i++) {



                $this->deleteSubCats($structureRecs[$i]['str_id']);



                $qryArray4 = array();

                $sql = 'DELETE FROM structure WHERE str_id = :str_id';

                $qryArray4['str_id'] = $structureRecs[$i]['str_id'];

                $this->run($sql, $qryArray4, false);



            }







        }

    }









    function structureToCats ($Str_ID = 0, $TblNam = 'basic-category') {



        if (is_numeric($Str_ID)) {



            $qryArray = array();

            $sql = 'SELECT * FROM structure WHERE str_id = :str_id';



            $qryArray['str_id'] = $Str_ID;

            $structureRecs = $this->run($sql, $qryArray, false);



            if (count($structureRecs) > 0) {



                //

                // Find category of TblNam, create if need be

                //



                $qryArray = array();

                $sql = 'SELECT * FROM categories WHERE tblnam = :tblnam';

                $qryArray['tblnam'] = $TblNam;

                $categoryRec = $this->run($sql, $qryArray, true);



                if (!isset($categoryRec->cat_id)) {



                    $Cat_ID = 0;



                    $qryArray = array();

                    $qryArray["catnam"] = $TblNam;

                    $qryArray["tblnam"] = $TblNam;

                    $qryArray["tbl_id"] = 0;

                    $qryArray["seourl"] = $TblNam;

                    $qryArray["keywrd"] = $TblNam;

                    $qryArray["keydsc"] = $TblNam;

                    $qryArray["sta_id"] = 0;



                    $sql = "INSERT INTO categories

					(



					catnam,

					tblnam,

					tbl_id,

					seourl,

					keywrd,

					keydsc,

					sta_id

					)

					VALUES

					(

					:catnam,

					:tblnam,

					:tbl_id,

					:seourl,

					:keywrd,

					:keydsc,

					:sta_id

					);";



                    $recordSet = $this->dbConn->prepare($sql);

                    $recordSet->execute($qryArray);

                    $Cat_ID = $this->dbConn->lastInsertId('cat_id');



                } else {



                    $Cat_ID = $categoryRec->cat_id;



                }



                for ($i = 0; $i < count($structureRecs); $i++) {



                    //

                    // Create SubCat based on structure

                    //



                    $qryArray = array();

                    $qryArray["subnam"] = $structureRecs[$i]['strnam'];

                    $qryArray["cat_id"] = $Cat_ID;

                    $qryArray["seourl"] = $structureRecs[$i]['seourl'];

                    $qryArray["keywrd"] = '';

                    $qryArray["keydsc"] = '';

                    $qryArray["sta_id"] = 0;

                    $qryArray["subtxt"] = '';



                    $sql = "INSERT INTO subcategories

					(



					subnam,

					cat_id,

					seourl,

					keywrd,

					keydsc,

					srtord,

					sta_id,

					subtxt

					)

					VALUES

					(

					:subnam,

					:cat_id,

					:seourl,

					:keywrd,

					:keydsc,

					99,

					:sta_id,

					:subtxt

					);";



                    $recordSet = $this->dbConn->prepare($sql);

                    $recordSet->execute($qryArray);



                    // Get SubStructures

                    $this->structureToSubCats($structureRecs[$i]['str_id'], $TblNam, $Cat_ID);



                }



            }



            $this->deleteStructure($Str_ID);



        }



    }



    function structureToSubCats($Par_ID, $TblNam = '', $Cat_ID = 0)

    {



        $qryArray3 = array();

        $sql = 'SELECT * FROM structure WHERE par_id = :par_id';

        $qryArray3['par_id'] = $Par_ID;



        $structureRecs = $this->run($sql, $qryArray3, false);



        if (count($structureRecs) > 0) {

            for ($i=0;$i<count($structureRecs);$i++) {



                //

                // Create SubCat based on structure

                //



                $qryArray = array();

                $qryArray["subnam"] = $structureRecs[$i]['strnam'];

                $qryArray["cat_id"] = $Cat_ID;

                $qryArray["seourl"] = $structureRecs[$i]['seourl'];

                $qryArray["keywrd"] = '';

                $qryArray["keydsc"] = '';

                $qryArray["sta_id"] = 0;

                $qryArray["subtxt"] = '';



                $sql = "INSERT INTO subcategories

					(



					subnam,

					cat_id,

					seourl,

					keywrd,

					keydsc,

					srtord,

					sta_id,

					subtxt

					)

					VALUES

					(

					:subnam,

					:cat_id,

					:seourl,

					:keywrd,

					:keydsc,

					99,

					:sta_id,

					:subtxt

					);";



                $recordSet = $this->dbConn->prepare($sql);

                $recordSet->execute($qryArray);



                $this->structureToSubCats($structureRecs[$i]['str_id'], $TblNam);



            }

        }

    }









    function getJSONVariable($JSONstr=NULL, $VarNam=NULL, $strip=true ) {

        if ($strip == true) {

            $eleVarArr = json_decode(stripslashes($JSONstr), true);

        } else {

            $eleVarArr = json_decode($JSONstr, true);

        }

        if (is_array($eleVarArr) && !is_null($VarNam)) {

            for ($i = 0; $i < count($eleVarArr); ++$i) {

                foreach($eleVarArr[$i] as $key => $item) {

                    if ($item === $VarNam) {

                        return $eleVarArr[$i]['value'];

                    }

                }

            }

        }

        return '';

    }

	

}



?>