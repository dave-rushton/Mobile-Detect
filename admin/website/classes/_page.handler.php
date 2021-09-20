<?php

class pageHandler extends pw
{

    public $Pag_ID;
    public $PagTtl;
    public $KeyWrd;
    public $PagDsc;
    public $PagImg;
    public $Tmplte;
    private $url;
    private $logID;
    private $getParams;
    private $sessionVars;
    public $pageStructure;
    public $elements;
    public $cmsProp;
    public $ParentID;
    public $PagObj;
    public $TplObj;

    function pageHandler()
    {
        $qryArray = array();
        $sql = "SELECT * FROM cmsprop";
        $cmsPropHold = $this->run($sql, $qryArray, true);

        if ($cmsPropHold->weboff == 1) header('location: ' . $this->webRoot . 'offline');
    }

    function getCmsProp()
    {
        $qryArray = array();
        $sql = "SELECT * FROM cmsprop";
        $this->cmsProp = $this->run($sql, $qryArray, true);
    }

//    function getPagObj($seourl, $obj) {
//        $qryArray = array();
//        $sql = "SELECT pagobj FROM pages WHERE seourl = :seourl";
//        $qryArray["seourl"] = $seourl;
//        $pagobj = $this->run($sql, $qryArray, true);
//        $pagobj = json_decode($pagobj->pagobj);
//
//        foreach ($pagobj as $subArray) {
//            if ($subArray->name == $obj) {
//                return $subArray->value;
//            }
//        }
//    }

    function getTopJS($seourl = NULL)
    {

        // get cms prop obj
        $string = $this->getJSONVariable($this->cmsProp->cmsobj, 'top_js', false);

        if (!is_null($seourl)) {
            $sql = "SELECT pagobj FROM pages WHERE seourl = :seourl";
            $qryArray["seourl"] = $seourl;
            $pagobj = $this->run($sql, $qryArray, true);

            $string .= " " . $this->getJSONVariable($pagobj->pagobj, 'top_js', false);
        }

        return $string;

//        //works for bot or bottom
//        $location = substr($location, 0, 3);
//        $location .="_js";
//        $cmsObj = json_decode($this->cmsProp->cmsobj);
//        $script = "";
//
//        foreach ($cmsObj as $subArray) {
//            if ($subArray->name == $location) {
//                $script .= $subArray->value;
//            }
//        }
//
//        if ($seourl != null) {
//            $script .= $this->getPagObj($seourl, $location);
//        }
//
//        return $script;
    }

    function getBotJS($seourl = NULL)
    {

        // get cms prop obj
        $string = $this->getJSONVariable($this->cmsProp->cmsobj, 'bot_js', false);

        if (!is_null($seourl)) {
            $sql = "SELECT pagobj FROM pages WHERE seourl = :seourl";
            $qryArray["seourl"] = $seourl;
            $pagobj = $this->run($sql, $qryArray, true);

            $string .= " " . $this->getJSONVariable($pagobj->pagobj, 'bot_js', false);
        }

        return $string;
    }

    function getPage($SeoUrl = NULL, $getParams = NULL, $sessionVars = NULL)
    {

        //
        // get page is run twice, once by the index.php and once by the actual template file
        // index.php passes through the GET and SESSION information
        // when building the CURL URL for the template file the GET is sent through the URL and the SESSION is sent through as POST
        // the template in turn passes through the $_GET and $_POST back to this function (getPage) so that the elements also have...
        // ...access to this information via the CURL requests
        //

        $this->getCmsProp();

        $this->getParams = $getParams;
        $this->sessionVars = $sessionVars;

        $qryArray = array();
        $sql = "SELECT p.*, t.tplfil, t.tplobj FROM pages p INNER JOIN template t ON t.tpl_id = p.TemplateID WHERE seourl = :seourl";

        //echo $sql;

        $qryArray["seourl"] = $SeoUrl;
        $pageRec = $this->run($sql, $qryArray, true);

        if (!$pageRec) header('location: 404.php');

        $this->url = $this->webRoot . 'pages/' . $pageRec->tplfil . '?';

        $this->Pag_ID = $pageRec->id;
        $this->PagTtl = $pageRec->pagttl;
        $this->KeyWrd = $pageRec->keywrd;
        $this->PagDsc = $pageRec->pagdsc;
        $this->PagImg = $pageRec->pagimg;
        $this->ParentID = $pageRec->parent_id;
        $this->PagObj = $pageRec->pagobj;

        $this->Tmplte = $pageRec->TemplateID;
        $this->TplObj = $pageRec->tplobj;

        // Overwrite Page Defaults

        //print_r($this->getParams);

        if (isset($this->getParams['catseo']) && !empty($this->getParams['catseo'])) {

            $qryArray = array();
            $sql = "SELECT subnam, keywrd, keydsc FROM subcategories WHERE seourl = :catseo";
            $qryArray["catseo"] = $this->getParams['catseo'];
            $productRec = $this->run($sql, $qryArray, true);

            $this->PagTtl = $productRec->subnam;
            $this->KeyWrd = $productRec->keywrd . ',' . $this->KeyWrd;
            $this->PagDsc = $productRec->keydsc . ',' . $this->PagDsc;

        }

        if (isset($this->getParams['atrseo']) && !empty($this->getParams['atrseo'])) {

            $qryArray = array();
            $sql = "SELECT atrnam, seokey, seodsc FROM attribute_group WHERE seourl = :atrseo";
            $qryArray["atrseo"] = $this->getParams['atrseo'];
            $productRec = $this->run($sql, $qryArray, true);

            $this->PagTtl = $productRec->atrnam;
            $this->KeyWrd = $productRec->seokey . ',' . $this->KeyWrd;
            $this->PagDsc = $productRec->seodsc . ',' . $this->PagDsc;

        }

        if (isset($this->getParams['artseo']) && !empty($this->getParams['artseo'])) {

            $qryArray = array();
            $sql = "SELECT artttl, seokey, seodsc FROM articles WHERE seourl = :seourl";
            $qryArray["seourl"] = $this->getParams['artseo'];
            $articleRec = $this->run($sql, $qryArray, true);

            $this->PagTtl = $articleRec->artttl;
            $this->KeyWrd = $articleRec->seokey . ',' . $this->KeyWrd;
            $this->PagDsc = $articleRec->seodsc . ',' . $this->PagDsc;

        }

        if (isset($this->getParams['prtseo']) && !empty($this->getParams['prtseo'])) {

            $qryArray = array();
            $sql = "SELECT prtnam, seokey, seodsc FROM producttypes WHERE seourl = :prtseo";
            $qryArray["prtseo"] = $this->getParams['prtseo'];
            $productRec = $this->run($sql, $qryArray, true);

            $this->PagTtl = $productRec->prtnam;
            $this->KeyWrd = $productRec->seokey . ',' . $this->KeyWrd;
            $this->PagDsc = $productRec->seodsc . ',' . $this->PagDsc;

        }

        if (isset($this->getParams['prdseo']) && !empty($this->getParams['prdseo'])) {

            $qryArray = array();
            $sql = "SELECT prdnam, seokey, seodsc FROM products WHERE seourl = :prdseo";
            $qryArray["prdseo"] = $this->getParams['prdseo'];
            $productRec = $this->run($sql, $qryArray, true);

            $this->PagTtl = $productRec->prdnam;
            $this->KeyWrd = $productRec->seokey . ',' . $this->KeyWrd;
            $this->PagDsc = $productRec->seodsc . ',' . $this->PagDsc;

        }

        //
        // get structure
        //

        $qryArray = array();
        $sql = "SELECT * FROM pagestructure WHERE pag_id = :pag_id ORDER BY srtord";
        $qryArray["pag_id"] = $this->Pag_ID;
        $this->pageStructure = $this->run($sql, $qryArray, false);

        //
        // get elements
        //

        $qryArray = array();
        $sql = "SELECT * FROM pageelements WHERE pag_id = :pag_id OR tmplte = :tmplte ORDER BY srtord";
        $qryArray["pag_id"] = $this->Pag_ID;
        $qryArray["tmplte"] = $this->Tmplte;
        $this->elements = $this->run($sql, $qryArray, false);

    }

    function getInactive()
    {
        $qryArray = array();
        $sql = "SELECT seourl FROM pages WHERE sta_id = 1";
        return $this->run($sql, $qryArray, false);
    }

    function getElement($Pel_ID = NULL)
    {
        $qryArray = array();
        $sql = "SELECT e.*, c.pgctxt FROM pageelements e LEFT OUTER JOIN pagecontent c ON e.pgc_id = c.pgc_id WHERE e.pel_id = :pel_id";

        //echo "SELECT e.*, c.pgctxt FROM pageelements e LEFT OUTER JOIN pagecontent c ON e.pgc_id = c.pgc_id WHERE e.pel_id = ".$Pel_ID;

        $qryArray["pel_id"] = $Pel_ID;
        return $this->run($sql, $qryArray, true);
    }

    function pageTitle()
    {
        return $this->PagTtl;
    }

    function metaKeywords()
    {
        return $this->KeyWrd;
    }

    function metaDescription()
    {
        return $this->KeyDsc;
    }

    function googleVerification()
    {
        return $this->GooVer;
    }

    function googleWebmaster()
    {
        return $this->GooWeb;
    }

    function googleAnalytics()
    {
        return htmlspecialchars_decode($this->cmsProp->gooana, ENT_QUOTES);
    }

    function critialCSS()
    {

        $critialCSS = '<style>';

        $critialCSS .= file_get_contents($this->docRoot . 'pages/css/critical.css');

        $critialCSS .= '</style>';

        return $critialCSS;

    }

    function setPageTitle($PagTtl = NULL)
    {
        if (!is_null($PagTtl)) $this->PagTtl = $PagTtl;
    }

    function setKeywords($KeyWrd = NULL)
    {
        if (!is_null($KeyWrd)) $this->KeyWrd = $KeyWrd;
    }

    function setDescription($KeyDsc = NULL)
    {
        if (!is_null($KeyDsc)) $this->KeyDsc = $KeyDsc;
    }

    function displayElements($Div_ID = NULL)
    {

        //echo 'displayElements: '.$Div_ID.' '.count($this->elements);

        $displayString = '';

        if (!is_null($Div_ID)) {

            for ($e = 0; $e < count($this->elements); $e++) {

                if ($this->elements[$e]['div_id'] != $Div_ID) continue;

                if (isset($this->getParams['tmplte']) && !empty($this->getParams['tmplte'])) {

                    //echo '<p>TEMPLATE</p>';

                    if ($this->elements[$e]['sta_id'] != 1) continue;

                }

                $displayString .= '<div class="elementModule ' . $this->elements[$e]['eletyp'] . '" data-type="' . $this->elements[$e]['eletyp'] . '" data-element="' . $this->elements[$e]['pel_id'] . '" data-pgc="' . $this->elements[$e]['pgc_id'] . '">';

                //echo $this->webRoot.$this->elements[$e]['incfil'].'?blank=&'.$this->logID.'&pel_id='.$this->elements[$e]['pel_id'].'&'.$this->elements[$e]['incurl'].'&'.$this->logID;

                $url = $this->webRoot . $this->elements[$e]['incfil'] . '?blank=&pel_id=' . $this->elements[$e]['pel_id'] . '&' . $this->elements[$e]['incurl'];

                $displayString .= $this->curlRequest($url);
                $displayString .= '</div>';

            }
        }

        return $displayString;
    }


    function displayStructure()
    {

        // Find Template
        // $this->Tmplte

        $templateArray = json_decode($this->TplObj, true);

        $displayString = '';


        $displayString .= '<div class="section">';
        $displayString .= '<div class="container">';

        for ($r = 0; $r < count($templateArray); $r++) {

            $displayString .= '<div class="row">';

            for ($c = 0; $c < count($templateArray[$r]['columns']); $c++) {

                $displayString .= '<div class="col-sm-' . $templateArray[$r]['columns'][$c]['colspan'] . '">';

                $displayString .= '<div class="pageElement" id="row-' . $r . '-col-' . $c . '">';

                if (isset($templateArray[$r]['columns'][$c]['content'])) $displayString .= $templateArray[$r]['columns'][$c]['content'];

                $displayString .= $this->displayElements('row-' . $r . '-col-' . $c);

                $displayString .= '</div>'; // pageElement

                $displayString .= '</div>'; // divcol

            }

            $displayString .= '</div>'; // row

        }

        $displayString .= '</div>'; // container
        $displayString .= '</div>'; //


        return $displayString;


        for ($e = 0; $e < count($this->pageStructure); $e++) {


            $displayString .= '<div class="section">';
            $displayString .= '<div class="container">';

            $colSet = json_decode($this->pageStructure[$e]['colset']);

            $displayString .= '<div class="row">';
            for ($c = 0; $c < count($colSet); $c++) {

                $displayString .= '<div class="col-sm-' . $this->getJSONVariable(json_encode($colSet[$c]), 'colspan', false) . '">';

                $displayString .= '<div class="pageElement" id="' . $this->pageStructure[$e]['pgs_id'] . '-' . $c . '" data-pgs_id="' . $this->pageStructure[$e]['pgs_id'] . '">';

                $displayString .= $this->displayElements($this->pageStructure[$e]['pgs_id'] . '-' . $c);

                $displayString .= '</div>'; // pageElement

                $displayString .= '</div>'; // divcol
            }
            $displayString .= '</div>'; // row

            $displayString .= '</div>'; // container
            $displayString .= '</div>'; //

        }


        echo $displayString;

    }


    function displayPage()
    {
        echo $this->curlRequest($this->url);
    }


    function socialMeta()
    {

        echo '<meta name="twitter:card" content="summary">';
        //echo '<meta name="twitter:site" content="@publisher_handle">';
        echo '<meta name="twitter:title" content="' . $this->PagTtl . '">';
        echo '<meta name="twitter:description" content="' . $this->KeyDsc . '">';
        //echo '<meta name="twitter:creator" content="@author_handle">';
        echo '<meta name="twitter:image" content="http://www.example.com/image.jpg">';

        echo '<meta property="og:title" content="' . $this->PagTtl . '" />';
        echo '<meta property="og:type" content="article" />';
        echo '<meta property="og:url" content="http://www.example.com/" />';
        echo '<meta property="og:image" content="http://example.com/image.jpg" />';
        echo '<meta property="og:description" content="' . $this->KeyDsc . '" />';
        echo '<meta property="og:site_name" content="Site Name, i.e. Moz" />';

    }


    function curlRequest($url = NULL)
    {

        //
        // as mentioned in other comments here the getParams ($_GET) are passed through with the CURL URL
        // the $_SESSION variables are converted to $_POST which the template file can read as $_GET and $_POST, as can the elements using this procedure
        //

        $url .= ($this->getParams) ? http_build_query($this->getParams) : '';

        //echo $url;

        $postfields = ($this->sessionVars) ? http_build_query($this->sessionVars) : '';

        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $url);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl_handle, CURLOPT_POST, count($this->sessionVars));
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $postfields);
        $output = curl_exec($curl_handle);

//		echo curl_getinfo($curl_handle) . '<br/>';
//		echo curl_errno($curl_handle) . '<br/>';
//		echo curl_error($curl_handle) . '<br/>';

        curl_close($curl_handle);

        return $output;

    }

    function setSession()
    {
    }

    function getSession()
    {
    }

    function getMenu($SeoUrl = NULL, $UseBrk = 0, $ParSeo = NULL, $Men_ID = NULL, $MenCls = NULL)
    {

        $qryArray = array();
        $seourl = (isset($SeoUrl)) ? $SeoUrl : '';
        $useBreak = $UseBrk;
        $parent = (isset($ParSeo)) ? $ParSeo : '';

        $breakStart = 0;
        $breakLevel = 2;
        $breakFirst = 0;

        //
        // IF PARENT SET FIND START AND END PAGE
        //
        if (!is_null($ParSeo) && !empty($ParSeo)) {

            $sql = 'SELECT * FROM pages WHERE seourl = "' . $ParSeo . '"';
            $parentPage = $this->run($sql, $qryArray, false);
            $parent = $parentPage[0]['id'];

            //echo 'PARENT: '.$parentPage[0]['seourl'].' '.$parentPage[0]['title'].'<br>';

            //$sql = 'SELECT * FROM pages WHERE level = '.$parentPage[0]['level'].' AND id != '.$parentPage[0]['id'].' ORDER BY `left` LIMIT 0,1';
            $sql = 'SELECT * FROM pages WHERE level = ' . $parentPage[0]['level'] . ' ORDER BY `left`';
            $breakPage = $this->run($sql, $qryArray, false);

            $foundParent = false;

            for ($b = 0; $b < count($breakPage); $b++) {

                if ($foundParent == true) {

                    //
                    // This could be cleaner
                    //

                    $sql = 'SELECT * FROM pages WHERE Id = ' . $breakPage[$b]['id'] . ' ORDER BY `left`';
                    $breakPage = $this->run($sql, $qryArray, false);
                    break;
                }

                if ($breakPage[$b]['id'] == $parent) {
                    $foundParent = true;
                }

                //echo $breakPage[$b]['title'].'<br>';

            }

            //echo 'BREAK ON : '.$breakPage[0]['seourl'].' '.$breakPage[0]['title'].'<br><br><br>';


        } else {
            $useBreak = 0;
            $breakStart = 1;
        }

        //
        // GET ALL PAGES
        //
        $sql = "SELECT * FROM pages WHERE Id > 2  ORDER BY `left`";
        $pags = $this->run($sql, $qryArray, false);

        $NumPag = 0;
        $Disp = 1;
        $DispLvl = 2;

        if (is_array($pags)) {
            $NumPag = count($pags);
        }

        $tidyUp = 1;

        $menuStr = '';

        $ulString = '';
        if ($Men_ID != NULL) $ulString .= ' id="' . $Men_ID . '" ';
        if ($MenCls != NULL) $ulString .= ' class="' . $MenCls . '" ';
        $menuStr .= '<ul' . $ulString . '>';

        for ($p = 0; $p < $NumPag; $p++) {

            if ($useBreak == 1) {

                if ($pags[$p]['id'] == $parentPage[0]['id']) {
                    $breakStart = 1;
                    $breakFirst = 1;
                    //echo 'cont<br>';
                    continue;
                }

                if ($pags[$p]['id'] == $breakPage[0]['id']) {
                    $breakStart = 0;
                }

            } else {

            }

            if ($breakStart == 1) {

            } else {
                continue;
            }

            // Display Page ?
            if ($pags[$p]['lnktyp'] == 1 || $pags[$p]['sta_id'] == 1) {
                // Do Not Display
                $Disp = 0;
            } else {
                // Display Pages?
                $Disp = 1;
            }

            if ($p > 0 && $breakFirst == 0) {

                if ($pags[$p - 1]['level'] == $pags[$p]['level']) {
                    $menuStr .= '</li>';
                }

                if ($pags[$p - 1]['level'] < $pags[$p]['level']) {
                    $menuStr .= '<ul class="nav-child">';

                    $tidyUp++;
                }

                if ($pags[$p - 1]['level'] > $pags[$p]['level']) {

                    $level = $pags[$p - 1]['level'];

                    $menuStr .= '</li>';

                    for ($l = $level; $l > $pags[$p]['level']; $l--) {
                        $menuStr .= '</ul></li>';
                        $tidyUp--;
                    }
                }
            }

            // Active Class
            $linkClass = 'class="';
            $displayLink = '';
            if ($pags[$p]['seourl'] == $seourl) {
                $linkClass .= ' active ';
            }

            $linkClass .= '"';

            if ($Disp == 0) {
                $displayLink = 'style="display: none;"';
            }


            $extLink = ($pags[$p]['lnktyp'] == 3) ? ' target="_blank"' : '';
            $linkSeo = ($pags[$p]['defpag'] == 1) ? $this->webRoot : $pags[$p]['seourl'];
            $linkSeo = ($pags[$p]['lnktyp'] == 2) ? '#' : $linkSeo;

            $liClass = "sub-parent";
            if ($pags[$p]['level'] == 2) $liClass = "nav-parent";
            if ($pags[$p]['lnktyp'] == 98) $liClass .= " megamenu";

            //$linkSeo = 'pages/'.$pags[$p]['tmplte'].'/index.php?seourl='.$linkSeo;

            $menuStr .= '<li class="' . $liClass . '" ' . $displayLink . '><a href="' . $linkSeo . '" ' . $linkClass . ' ' . $extLink . '>' . $pags[$p]['title'] . '</a>';

            // testing
            //$menuStr .= '(L:'.$pags[$p]['level'].', T:'.$pags[$p]->lnktyp.', D:'.$Disp.'/'.$DispLvl.')';
            //$menuStr .= '(L:'.$pags[$p]['level'].', B:'.$breakStart.', ID: '.$breakLevel.')';


            //
            // ADD HOOKS
            //

            if ($pags[$p]['lnktyp'] == 90) {

                // LOCATIONS

                $qryArray = array();
                $sql = "SELECT * FROM places WHERE tblnam = 'LOCATION'";
                $locations = $this->run($sql, $qryArray, false);

                $menuStr .= '<ul>';

                for ($l = 0; $l < count($locations); $l++) {
                    $menuStr .= '<li><a href="practice/' . $locations[$l]['seourl'] . '">' . $locations[$l]['planam'] . '</a></li>';
                }

                $menuStr .= '</ul>';

            }


            if ($pags[$p]['lnktyp'] == 98) {

                $menuStr .= $this->buildStructure(0, $pags[$p]['seourl'], NULL, 'megamenu', false);

            }

            $breakFirst = 0;

        }

        $menuStr .= '</li></ul>';
        $tidyUp--;

        $tidyUp = ($tidyUp < 0) ? 1 : $tidyUp;

        for ($l = $tidyUp; $l > 0; $l--) {
            $menuStr .= '</li></ul>';
        }

        return $menuStr;

    }

    function getBootstrapMenu($SeoUrl = NULL, $UseBrk = 0, $ParSeo = NULL, $Men_ID = NULL, $MenCls = NULL)
    {

        $qryArray = array();

        $seourl = (isset($SeoUrl)) ? $SeoUrl : '';
        $useBreak = $UseBrk;

        $parent = (isset($ParSeo)) ? $ParSeo : '';

        if (!is_null($ParSeo) && !empty($ParSeo)) {
            $sql = 'SELECT * FROM pages WHERE seourl = "' . $ParSeo . '"';
            $parentPage = $this->run($sql, $qryArray, false);
            $parent = $parentPage[0]['id'];
//			echo '~~'.$parent.'~~';
        } else {
            $useBreak = 0;
        }

        $sql = "SELECT * FROM pages WHERE Id > 2  ORDER BY `left`";
        $pags = $this->run($sql, $qryArray, false);

//		$sql = "SELECT * FROM pages WHERE Id > 2";
//		$sql = $sql." ORDER BY `left`";
//		
//		$pags = $this->execute($sql);

        $NumPag = 0;
        $Disp = 1;
        $DispLvl = 2;

        if (is_array($pags)) {
            $NumPag = count($pags);
        }

        $breakStart = 0;
        $breakLevel = 2;
        $breakFirst = 0;    // Ignore the LI terminator 1st time through

        $tidyUp = 1;

        $menuStr = '';

        $ulString = '';
        if ($Men_ID != NULL) $ulString .= ' id="' . $Men_ID . '" ';
        if ($MenCls != NULL) $ulString .= ' class="' . $MenCls . '" ';
//		echo $breakLevel.' #'.$ParSeo;
        $menuStr .= '<ul' . $ulString . '>';
        for ($p = 0; $p < $NumPag; $p++) {

            // Break Off Menu
            if ($useBreak == 1) {
                if ($pags[$p]['parent_id'] == $parent) {
                    $breakLevel = $pags[$p]['level'];
                    $breakStart = 1;
                    $breakFirst = 1;
                } else {
                    if ($breakStart == 1) {
//						echo '<p>'.$pags[$p-1]['level'].' '.$pags[$p-1]['pagttl'].'~</p>';

                        if ($pags[$p - 1]['level'] == $breakLevel && $breakLevel != 2) {
                            break;
                        }
                    } else {
                        continue;
                    }
                }
            }

            // Display Page ?
            if ($pags[$p]['lnktyp'] == 1 || $pags[$p]['sta_id'] == 1) {
                // Do Not Display
                $Disp = 0;
            } else {
                // Display Pages?
                $Disp = 1;
            }

            if ($p > 0 && $breakFirst == 0) {

                if ($pags[$p - 1]['level'] == $pags[$p]['level']) {
                    $menuStr .= '</li>';
                }

                if ($pags[$p - 1]['level'] < $pags[$p]['level']) {
                    $menuStr .= '<ul class="dropdown-menu">';
                    $tidyUp++;
                }

                if ($pags[$p - 1]['level'] > $pags[$p]['level']) {

                    $level = $pags[$p - 1]['level'];

                    $menuStr .= '</li>';

                    for ($l = $level; $l > $pags[$p]['level']; $l--) {
                        $menuStr .= '</ul></li>';
                        $tidyUp--;
                    }
                }
            }


            // Active Class
            $linkClass = '';
            $displayLink = '';
            if ($pags[$p]['seourl'] == $seourl) {
                $linkClass = 'class="active"';
            }

            if ($Disp == 0) {
                $displayLink = 'style="display: none;"';
            }

            $extLink = ($pags[$p]['lnktyp'] == 3) ? ' target="_blank"' : '';

            //$menuStr .= '<li '.$displayLink.'><a href="'.$pags[$p]['seourl'].'" '.$linkClass.'>'.$pags[$p]['title'].'</a>';

            $linkSeo = ($pags[$p]['lnktyp'] == 2) ? '#' : $pags[$p]['seourl'];

            //
            // bootstrap
            //
            if ($p + 1 < count($pags) && $breakFirst == 0) {
                if ($pags[$p + 1]['level'] > $pags[$p]['level']) {

                    if ($pags[$p + 1]['lnktyp'] == 1 || $pags[$p + 1]['sta_id'] == 1) {
                        $menuStr .= '<li ' . $displayLink . '><a href="' . $linkSeo . '" ' . $extLink . '>' . $pags[$p]['title'] . '</a>';
                    } else {

                        if ($pags[$p]['level'] == 2) {

                            $menuStr .= '<li class="dropdown" ' . $displayLink . '><a href="' . $linkSeo . '" ' . $extLink . ' class="dropdown-toggle" data-toggle="dropdown">' . $pags[$p]['title'] . ' <b class="caret"></b></a>';

                        } else {
                            $menuStr .= '<li class="dropdown" ' . $displayLink . '><a href="' . $linkSeo . '" ' . $extLink . ' class="dropdown-toggle" data-toggle="dropdown">' . $pags[$p]['title'] . '</a>';
                        }

                    }


                } else {
                    $menuStr .= '<li ' . $displayLink . '><a href="' . $linkSeo . '" ' . $extLink . '>' . $pags[$p]['title'] . '</a>';
                }
            } else {

                if ($pags[$p]['lnktyp'] == 91) {
                    $menuStr .= '<li ' . $displayLink . '><a href="' . $linkSeo . '" ' . $extLink . ' class="dropdown-toggle" data-toggle="dropdown">' . $pags[$p]['title'] . '<b class="caret"></b></a>';
                } else {
                    $menuStr .= '<li ' . $displayLink . '><a href="' . $linkSeo . '" ' . $extLink . '>' . $pags[$p]['title'] . '</a>';
                }


            }

//			$menuStr .= '<li class="dropdown"><a href="'.$pags[$p]['seourl'].'" class="dropdown-toggle" data-toggle="dropdown">'.$pags[$p]['title'].' <b class="caret"></b></a>';

            // testing
            //$menuStr .= '(L:'.$pags[$p]['level'].', T:'.$pags[$p]->lnktyp.', D:'.$Disp.'/'.$DispLvl.')';
            //$menuStr .= '(L:'.$pags[$p]['level'].', B:'.$breakStart.', ID: '.$breakLevel.')';


            //
            // ADD HOOKS
            //

            if ($pags[$p]['lnktyp'] == 90) {

                // LOCATIONS

                $qryArray = array();
                $sql = "SELECT * FROM places WHERE tblnam = 'LOCATION'";
                $locations = $this->run($sql, $qryArray, false);

                $menuStr .= '<ul class="dropdown-menu">';

                for ($l = 0; $l < count($locations); $l++) {
                    $menuStr .= '<li><a href="location/' . $locations[$l]['seourl'] . '">' . $locations[$l]['planam'] . '</a></li>';
                }

                $menuStr .= '</ul>';
            }

            if ($pags[$p]['lnktyp'] == 91) {

                // Products

                $qryArray = array();
                $sql = "SELECT s.* FROM subcategories s INNER JOIN categories c ON c.tblnam = 'shopping-departments' AND c.cat_id = s.cat_id";
                $locations = $this->run($sql, $qryArray, false);

                $menuStr .= '<ul class="dropdown-menu">';

                for ($l = 0; $l < count($locations); $l++) {
                    $menuStr .= '<li><a href="products/department/' . $locations[$l]['seourl'] . '">' . $locations[$l]['subnam'] . '</a></li>';


//                    $qryArray = array();
//                    $sql = "SELECT s.* FROM attribute_groups a";
//                    $locations = $this->run($sql, $qryArray, false);


                }

                $menuStr .= '</ul>';

            }


            $breakFirst = 0;

        }
        $menuStr .= '</li></ul>';
        $tidyUp--;

        $tidyUp = ($tidyUp < 0) ? 1 : $tidyUp;

        for ($l = $tidyUp; $l > 0; $l--) {
            $menuStr .= '</li></ul>';
        }

        return $menuStr;

    }

    function getBreadcrumb($SeoUrl = NULL)
    {

        $breadCrumbHTML = '';

        $topParent = 0;

        $qryArray = array();
        $sql = 'SELECT * FROM pages WHERE seourl = :seourl';
        $qryArray['seourl'] = $SeoUrl;
        $parentPage = $this->run($sql, $qryArray, true);

        if ($parentPage) {
            $breadCrumbHTML = '<li class="active">' . $parentPage->title . '</li>';

            while ($topParent === 0) {

                $parentPage = $this->findParent($parentPage->parent_id);

                if ($parentPage) {
                    if ($parentPage->id == 2 || $parentPage->defpag == 1) {
                        $topParent = 1;
                    } else {

                        if ($parentPage->lnktyp == 0 && $parentPage->sta_id == 0) {

                            $breadCrumbHTML = '<li><a href="' . $parentPage->seourl . '">' . $parentPage->title . '</a></li></li>' . $breadCrumbHTML;

                        }
                    }
                }

            }

            $breadCrumbHTML = '<li><a href="' . $this->webRoot . '">home</a></li></li>' . $breadCrumbHTML;

            $breadCrumbHTML = '<ol class="breadcrumb">' . $breadCrumbHTML . '</ol>';

            echo $breadCrumbHTML;

        }
    }

    function findParent($Pag_ID = NULL)
    {

        $qryArray = array();
        $sql = 'SELECT * FROM pages WHERE id = :pag_id';
        $qryArray['pag_id'] = $Pag_ID;
        return $this->run($sql, $qryArray, true);

    }

    function findPageBySEO($SeoUrl = NULL)
    {

        $qryArray = array();
        $sql = 'SELECT * FROM pages WHERE seourl = :seourl';
        $qryArray['seourl'] = $SeoUrl;
        return $this->run($sql, $qryArray, true);

    }

    function getChildren($Pag_ID = NULL)
    {

        $qryArray = array();
        $sql = 'SELECT * FROM pages WHERE parent_id = :pag_id';
        $qryArray['pag_id'] = $Pag_ID;
        return $this->run($sql, $qryArray, false);

    }

    function hasChildren($Pag_ID = NULL)
    {

        $qryArray = array();
        $sql = 'SELECT * FROM pages WHERE parent_id = :pag_id';
        $qryArray['pag_id'] = $Pag_ID;
        $results = $this->run($sql, $qryArray, false);
        return (count($results) > 0) ? true : false;

    }

    function getHomePage()
    {

        $qryArray = array();
        $sql = 'SELECT 
				p.id AS pag_id,
				p.TemplateID AS tmplte,
				p.title,
				p.lnktyp,
				p.seourl,
				p.pagttl,
				p.keywrd,
				p.pagdsc,
				p.googex,
				p.sta_id,
				p.defpag,
				t.tplfil
				FROM pages p
				LEFT OUTER JOIN template t ON t.tpl_id = p.TemplateID
				WHERE p.defpag = 1';

        return $this->run($sql, $qryArray, true);

    }

    function selectNext($Pag_ID = NULL)
    {

        $sql = "SELECT
                *
                FROM pages
                WHERE id = :pag_id
                LIMIT 1";

        $qryArray = array();
        $qryArray['pag_id'] = $Pag_ID;

        //$this->displayQuery($sql, $qryArray);
        $pageRec = $this->run($sql, $qryArray, true);

        if (isset($pageRec->id)) {

            $sql = "SELECT
                *
                FROM pages
                WHERE
                parent_id = :par_id AND
                position > :position
                ORDER BY `left`
                LIMIT 1";

            $qryArray = array();
            $qryArray['par_id'] = $pageRec->parent_id;
            $qryArray['position'] = $pageRec->position;

            //$this->displayQuery($sql, $qryArray);
            return $this->run($sql, $qryArray, true);

        } else {
            return NULL;
        }

    }

    function selectPrev($Pag_ID = NULL, $ReqObj = false)
    {

        $sql = "SELECT
                *
                FROM pages
                WHERE id = :pag_id
                LIMIT 1";

        $qryArray = array();
        $qryArray['pag_id'] = $Pag_ID;

        //$this->displayQuery($sql, $qryArray);
        $pageRec = $this->run($sql, $qryArray, true);

        if (isset($pageRec->id)) {

            $sql = "SELECT
                *
                FROM pages
                WHERE
                parent_id = :par_id AND
                position < :position AND
                `left` < :left
                ORDER BY `left` DESC
                LIMIT 1";

            $qryArray = array();
            $qryArray['par_id'] = $pageRec->parent_id;
            $qryArray['position'] = $pageRec->position;
            $qryArray['left'] = $pageRec->left;

            //$this->displayQuery($sql, $qryArray);
            return $this->run($sql, $qryArray, true);

        } else {
            return NULL;
        }

    }


    function buildStructure($Par_ID = 0, $SeoUrl = NULL, $Ele_ID = NULL, $EleCls = NULL, $admin = false)
    {

        $strStr = '';

        if (!is_numeric($Par_ID)) $Par_ID = 0;

        $qryArray = array();
        $sql = 'SELECT * FROM structure WHERE par_id = :par_id ORDER BY srtord';
        $qryArray['par_id'] = $Par_ID;
        $structureRecs = $this->run($sql, $qryArray, false);

        $Ele_ID = (!is_null($Ele_ID)) ? ' id="' . $Ele_ID . '"" ' : '';
        $EleCls = (!is_null($EleCls)) ? ' class="' . $EleCls . '"" ' : '';

        $strStr .= '<ul' . $Ele_ID . $EleCls . '>';

        for ($i = 0; $i < count($structureRecs); $i++) {

            $strStr .= '<li>';

            if ($admin) {
                $strStr .= '<a href="' . $structureRecs[$i]['str_id'] . '" class="selectStructureBtn" data-str_id="' . $structureRecs[$i]['str_id'] . '">' . $structureRecs[$i]['strnam'] . '</a>';
            } else {
                $strStr .= '<a href="' . $SeoUrl . '/category/' . $structureRecs[$i]['str_id'] . '/' . $structureRecs[$i]['seourl'] . '">';

                $strStr .= $structureRecs[$i]['strnam'] . ' <i></i>';

                $strStr .= '</a>';
            }

            $strStr .= $this->ShowSubCats($structureRecs[$i]['str_id'], $SeoUrl, $admin);

            $strStr .= '</li>';

        }

        $strStr .= '</ul>';

        return $strStr;

    }

    function ShowSubCats($Par_ID, $SeoUrl = NULL, $admin = false)
    {

        $strStr = '';

        $qryArray = array();
        $sql = 'SELECT * FROM structure WHERE par_id = :par_id ORDER BY srtord';
        $qryArray['par_id'] = $Par_ID;

        $structureRecs = $this->run($sql, $qryArray, false);

        if (count($structureRecs) > 0) {

            $strStr .= '<ul>';

            for ($i = 0; $i < count($structureRecs); $i++) {

                $strStr .= '<li>';

                if ($admin) {
                    $strStr .= '<a href="' . $structureRecs[$i]['str_id'] . '" class="selectStructureBtn" data-str_id="' . $structureRecs[$i]['str_id'] . '">' . $structureRecs[$i]['strnam'] . '</a>';
                } else {
                    $strStr .= '<a href="' . $SeoUrl . '/category/' . $structureRecs[$i]['str_id'] . '/' . $structureRecs[$i]['seourl'] . '">' . $structureRecs[$i]['strnam'] . '</a>';
                }

                $strStr .= $this->ShowSubCats($structureRecs[$i]['str_id'], $SeoUrl, $admin);

                $strStr .= '</li>';
            }

            $strStr .= '</ul>';

        }

        return $strStr;

    }


}

?>