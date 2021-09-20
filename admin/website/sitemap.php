<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once("classes/template.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpTpl = new TplDAO();
$templates = $TmpTpl->select();

$qryArray = array();
$sql = "SELECT * FROM cmsprop WHERE cms_id = 1";
$cmsProp = $patchworks->run($sql, array(), true);


$qryArray = array();
$sql = 'SELECT u.*, g.galnam, g.imgsiz FROM uploads u INNER JOIN gallery g ON g.gal_id = u.tbl_id WHERE u.tblnam = :tblnam ORDER BY g.galnam, srtord ASC';

$qryArray['tblnam'] = 'WEBGALLERY';
$galleryImages = $patchworks->run($sql, $qryArray);

$galleryHTML = '';

$GalNam = '';
$tableLength = count($galleryImages);
for ($i=0;$i<$tableLength;++$i) {

    if ($GalNam != $galleryImages[$i]['galnam']) {

        $GalNam = $galleryImages[$i]['galnam'];

        if ($i > 0) $galleryHTML .= '</optgroup>';
        $galleryHTML .= '<optgroup label="'.$GalNam.'">';

    }

    $galleryHTML .= '<option value="uploads/images/'.$galleryImages[$i]['filnam'].'" data-imgsiz="'.$galleryImages[$i]['imgsiz'].'">'.$galleryImages[$i]['uplttl'].'</option>';
}
$galleryHTML .= '</optgroup>';


$qryArray = array();
$sql = 'SELECT seourl, title, pagttl FROM pages WHERE id > 2';
$seoCheck = $patchworks->run($sql, $qryArray);

$seoError = array();
$chkArray = array();
$namArray = array();

$tableLength = count($seoCheck);
for ($i=0;$i<$tableLength;++$i) {

    if (in_array($seoCheck[$i]['seourl'], $chkArray)) {
        $origName = array_search($seoCheck[$i]['seourl'], $chkArray);
        array_push($seoError, 'An SEO duplicate has been found in your site map: ['.$seoCheck[$i]['title'].'] and ['.$namArray[$origName].'] have the same SEO URL "'.$seoCheck[$i]['seourl'].'"');
    } else {
        array_push($chkArray, $seoCheck[$i]['seourl']);
        array_push($namArray, $seoCheck[$i]['title']);
    }
}

?>
<!doctype html>
<html>
<head>
<title>Website Sitemap</title>
<?php include('../webparts/headdata.php'); ?>
<script type="text/javascript" src="js/plugins/jstree/jquery.jstree.js"></script>
<script type="text/javascript" src="js/jquery_cookie.js"></script>
<script type="text/javascript" src="website/js/sitemap.js"></script>

<style>
    .jstree-search { font-weight: bold !important; color: red !important; }
</style>

</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
	<?php include('../webparts/website-left.php'); ?>
	<div id="main">
		<div class="container-fluid">

			<div class="page-header">
				<div class="pull-left">
					<h1>Sitemap</h1>
				</div>
				<div class="pull-right">
				</div>
			</div>

			<div class="breadcrumbs">
				<ul>
					<li>
						<a href="index.php">Dashboard</a>
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a>Website</a>
						<i class="icon-angle-right"></i>
					</li>
					<li>
						<a href="sitemap.php">Sitemap</a>
					</li>
				</ul>
			</div>

            <div class="row-fluid">
				<div class="span4">
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-sitemap"></i> Website Structure</h3>
							<div class="actions">
								<a href="#" id="renameSitemapPage" class="btn btn-mini" role="button" rel="tooltip" title="Rename Page"><i class="icon-font"></i></a>
								<a href="#" id="deleteSitemapPage" class="btn btn-mini" role="button" rel="tooltip" title="Delete Page"><i class="icon-minus"></i></a>
								<a href="#" id="addSitemapPage" class="btn btn-mini" role="button" rel="tooltip" title="Add Page"><i class="icon-plus"></i></a>
							</div>
						</div>
						<div class="box-content">


                            <?php
                            if(!empty($patchworks->disable_seo_error)){
                                if($patchworks->disable_seo_error == false){
                                    if (count($seoError) > 0) {
                                        ?>

                                        <div class="alert alert-error">
                                            <h4>SEO Error</h4><br>
                                            <p>
                                                <?php
                                                for ($e=0;$e<count($seoError);$e++) {
                                                    echo $seoError[$e].'<br>';
                                                }
                                                ?>
                                            </p>
                                        </div>

                                        <?php
                                    }
                                }
                            }else{
                                if (count($seoError) > 0) {
                                    ?>

                                    <div class="alert alert-error">
                                        <h4>SEO Error</h4><br>
                                        <p>
                                            <?php
                                            for ($e=0;$e<count($seoError);$e++) {
                                                echo $seoError[$e].'<br>';
                                            }
                                            ?>
                                        </p>
                                    </div>

                                    <?php
                                }
                            }

                            ?>


                            <div class="control-group">
                                <label class="control-label">Search Pages</label>
                                <div class="controls">
                                    <input type="text" id="treesearch" class="input-block-level">
                                </div>
                            </div>



							<div id="siteMap" style="border: none !important;">
							</div>
						</div>
					</div>
				</div>
				<div class="span8">

                    <form action="<?php echo $patchworks->pwRoot; ?>website/json/cmspropupdate.php" id="cmsPropForm" class="form-horizontal form-bordered" novalidate>
                        <div class="box box-color box-bordered">
                            <div class="box-title">
                                <h3>
                                    <i class="icon-cogs"></i> Website Properties</h3>
                                <div class="actions">
                                    <a href="#" class="btn btn-mini submit-form" role="button" rel="tooltip" title="Update Properties"><i class="icon-save"></i></a>
                                </div>
                                <ul class="tabs">
                                    <li class="active">
                                        <a href="#t1" data-toggle="tab">Basic</a>
                                    </li>
                                    <li class="">
                                        <a href="#t2" data-toggle="tab">Advanced</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="box-content nopadding">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="t1">
                                        <div class="control-group">
                                            <label class="control-label" for="PagTtl">Google Analytics<small>Copy and paste your google analytics javascript code segment in this field</small></label>
                                            <div class="controls">
                                                <textarea class="input-block-level" name="gooana" rows="15" ><?php echo htmlspecialchars_decode($cmsProp->gooana, ENT_QUOTES); ?></textarea>
                                            </div>
                                        </div>


                                        <div class="control-group">
                                            <label class="control-label">Website Offline<small>check box to turn off website</small></label>
                                            <div class="controls">
                                                <label class="checkbox">
                                                    <input type="checkbox" name="weboff" value="1" <?php echo ($cmsProp->weboff == 1) ? 'checked' : ''; ?>>
                                                    Website Under Maintenance? </label>
                                            </div>
                                        </div>

                                        <div class="hide">
                                            <div class="control-group">
                                                <label class="control-label" for="PagTtl">Google Analytics Name<small>Enter the name given to the GA account</small></label>
                                                <div class="controls">
                                                    <input type="text" class="input-block-level" name="gooweb" id="GooWeb" value="<?php echo $cmsProp->gooweb; ?>">
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="PagTtl">Google API</label>
                                                <div class="controls">
                                                    <input type="text" class="input-block-level" name="gooapi" value="<?php echo $cmsProp->gooapi; ?>">
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="PagTtl">Google Account Login<small>email used to log into google account</small></label>
                                                <div class="controls">
                                                    <input type="text" class="input-block-level" name="webusr" value="<?php echo $cmsProp->webusr; ?>">
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="PagTtl">Google Login Password<small>password used to log into google account</small></label>
                                                <div class="controls">
                                                    <input type="password" class="input-block-level" name="webpwd" value="<?php echo $cmsProp->webpwd; ?>">
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label">Twitter ID</label>
                                                <div class="controls">
                                                    <input type="text" class="input-block-level" name="twi_id" value="<?php echo $cmsProp->twi_id; ?>">
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label">Facebook ID</label>
                                                <div class="controls">
                                                    <input type="text" class="input-block-level" name="f_b_id" value="<?php echo $cmsProp->f_b_id; ?>">
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label">Linked In ID</label>
                                                <div class="controls">
                                                    <input type="text" class="input-block-level" name="l_i_id" value="<?php echo $cmsProp->l_i_id; ?>">
                                                </div>
                                            </div>


                                            <div class="control-group">
                                                <label class="control-label"><i class="icon-facebook"></i> API ID</label>
                                                <div class="controls">
                                                    <input type="text" class="input-block-level" name="fb_app" value="<?php echo $cmsProp->fb_app; ?>">
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label"><i class="icon-facebook"></i> API SECRET</label>
                                                <div class="controls">
                                                    <input type="text" class="input-block-level" name="fb_sec" value="<?php echo $cmsProp->fb_sec; ?>">
                                                </div>
                                            </div>

                                            <div class="control-group">
                                                <label class="control-label"><i class="icon-twitter"></i> CONSUMER KEY</label>
                                                <div class="controls">
                                                    <input type="text" class="input-block-level" name="conkey" value="<?php echo $cmsProp->conkey; ?>">
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label"><i class="icon-twitter"></i> CONSUMER SECRET</label>
                                                <div class="controls">
                                                    <input type="text" class="input-block-level" name="consec" value="<?php echo $cmsProp->consec; ?>">
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label"><i class="icon-twitter"></i> ACCESS TOKEN</label>
                                                <div class="controls">
                                                    <input type="text" class="input-block-level" name="acctok" value="<?php echo $cmsProp->acctok; ?>">
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label"><i class="icon-twitter"></i> ACCESS SECRET</label>
                                                <div class="controls">
                                                    <input type="text" class="input-block-level" name="accsec" value="<?php echo $cmsProp->accsec; ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="control-group">
                                            <label class="control-label"><i class="icon-google-plus-sign"></i> GOOGLE CAPTCHA KEY</label>
                                            <div class="controls">
                                                <input type="text" class="input-block-level" name="capkey" value="<?php echo $cmsProp->capkey; ?>">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label"><i class="icon-google-plus-sign"></i> GOOGLE CAPTCHA SECRET</label>
                                            <div class="controls">
                                                <input type="text" class="input-block-level" name="capsec" value="<?php echo $cmsProp->capsec; ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane" id="t2">
                                        <div class="control-group">
                                            <label class="control-label"><i class="icon-google-plus-sign"></i> Stacktable</label>
                                            <div class="controls">
                                                <label class="checkbox">
                                                    <input type="checkbox" class="customfield" name="stacktable" value="1">
                                                    Stacktable
                                                </label>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">Top JS<small></small></label>
                                            <div class="controls">
                                                <textarea name="top_js" class="input-block-level valid customfield" value=""></textarea>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">Bottom JS<small></small></label>
                                            <div class="controls">
                                                <textarea name="bot_js" class="input-block-level valid customfield"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

					<form action="<?php echo $patchworks->pwRoot; ?>website/json/pageupdate.json.php" id="pageForm" class="form-horizontal form-bordered" novalidate style="display: none;">
						<input type="hidden" name="pag_id">
						<div class="box box-color box-bordered">
							<div class="box-title">
								<h3>
									<i class="icon-file-alt"></i> Page Details</h3>
                                <div class="actions">
                                    <button type="submit" class="btn btn-mini" role="button" rel="tooltip" title="Update Page"><i class="icon-save white-text"></i></button>
                                    <a href="pagebuilder.php" target="_blank" id="pageBuilderLink" class="btn btn-mini" role="button" rel="tooltip" title="Page Builder"><i class="icon-table"></i></a>
                                    <a href="#" target="_blank" id="pagePreviewLink" class="btn btn-mini" role="button" rel="tooltip" title="Preview"><i class="icon-external-link"></i></a>
                                </div>
                                <ul class="tabs">
                                    <li class="active">
                                        <a href="#t3" data-toggle="tab">Basic</a>
                                    </li>
                                    <li class="">
                                        <a href="#t4" data-toggle="tab">Advanced</a>
                                    </li>
                                </ul>
							</div>
							<div class="box-content nopadding">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="t3">
                                        <div class="control-group">
                                            <label class="control-label" for="PagTtl">Page Title<small>page title used in navigation</small></label>
                                            <div class="controls">
                                                <input type="text" class="input-block-level" name="pagttl" id="PagTtl" value="">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label" for="SeoUrl">SEO friendly URL<small>name used in address bar of browser</small></label>
                                            <div class="controls">
                                                <input type="text" class="input-block-level" name="seourl" id="SeoUrl" value="">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label" for="TmpLte">Page Template<small>select your desired page layout</small></label>
                                            <div class="controls">
                                               
                                                <select id="TmpLte" name="tmplte" class="input-block-level">

                                                    <?php




                                                    $tableLength = count($templates);
                                                    for ($i=0;$i<$tableLength;++$i) {
                                                        if (file_exists( '../../pages/'.$templates[$i]['tplfil'] )) {
                                                            ?>
                                                            <option value="<?php echo $templates[$i]['tpl_id']; ?>"><?php echo $templates[$i]['tplnam']; ?></option>
                                                        <?php }} ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label" for="LnkTyp">Link Type<small>navigation function</small></label>
                                            <div class="controls">
                                                <select id="LnkTyp" name="lnktyp" class="input-block-level">
                                                    <option value="0">Default</option>
                                                    <option value="1">Remove From Menu</option>
                                                    <option value="2">No Link</option>
                                                    <option value="3">External URL</option>

                                                    <option value="98">Product Drop Down</option>
                                                    <option value="200">Replace With Products</option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="control-group hide">
                                            <!--<label class="control-label">Checkboxes</label>-->
                                            <div class="controls">
                                                <label class="checkbox">
                                                    <input type="checkbox" value="1" name="newwin" id="NewWin">
                                                    Open in new window </label>
                                                <label class="checkbox">
                                                    <input type="checkbox" value="1" name="googex" id="GoogEx">
                                                    Hidden from search engines </label>
                                            </div>
                                        </div>

                                        <div class="control-group">
                                            <label class="control-label">Status<small>enable/disable page</small></label>
                                            <div class="controls">

                                                <label class="checkbox">
                                                    <input type="checkbox" name="sta_id" value="1">
                                                    Offline? </label>

                                                <!--                                        <select id="Sta_ID" name="sta_id" class="input-block-level">-->
                                                <!--                                            <option value="0">Published</option>-->
                                                <!--                                            <option value="1">Offline</option>-->
                                                <!--                                        </select>-->
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label" for="KeyWrd">Keywords<small>keywords to help search engines</small></label>
                                            <div class="controls">
                                                <textarea class="input-block-level" name="keywrd" id="KeyWrd"></textarea>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label" for="PagDsc">Description<small>page description used on search engines</small></label>
                                            <div class="controls">
                                                <textarea class="input-block-level" name="pagdsc" id="PagDsc"></textarea>
                                            </div>
                                        </div>

                                        <div class="control-group">
                                            <label class="control-label">Home Page<small>check box and save to set as homepage</small></label>
                                            <div class="controls">
                                                <label class="checkbox">
                                                    <input type="checkbox" name="defpag" value="1">
                                                    Set as home page? </label>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">Page Image<small></small></label>
                                            <div class="controls">

                                                <select class="input-block-level" name="pagimg" id="PagImg">
                                                    <option value="">N/A</option>
                                                    <?php
                                                    echo $galleryHTML;
                                                    ?>
                                                </select>

                                            </div>
                                        </div>

                                        <div class="control-group">
                                            <label class="control-label" for="PagDsc">Page Button Text<small></small></label>
                                            <div class="controls">
                                                <textarea class="input-block-level customfield" name="pagtxt"></textarea>
                                            </div>
                                        </div>

                                        <!---->
                                        <!--                                <div class="control-group">-->
                                        <!--                                    <label class="control-label">Link Colour<small></small></label>-->
                                        <!--                                    <div class="controls">-->
                                        <!--                                        <select class="input-block-level customfield" name="lnkcol">-->
                                        <!--                                            <option value="">N/A</option>-->
                                        <!--                                            <option value="blue">Blue</option>-->
                                        <!--                                            <option value="yellow">Yellow</option>-->
                                        <!--                                            <option value="red">Red</option>-->
                                        <!--                                        </select>-->
                                        <!--                                    </div>-->
                                        <!--                                </div>-->
                                        <!---->
                                        <!--                                <div class="control-group">-->
                                        <!--                                    <label class="control-label">Remove Sub Menu<small></small></label>-->
                                        <!--                                    <div class="controls">-->
                                        <!--                                        <label class="checkbox">-->
                                        <!--                                            <input type="checkbox" class="customfield" name="incsub" value="1">-->
                                        <!--                                            Remove sub-menu? </label>-->
                                        <!--                                    </div>-->
                                        <!--                                </div>-->
                                    </div>
                                    <div class="tab-pane" id="t4">
                                        <div class="control-group">
                                            <label class="control-label">Top JS<small></small></label>
                                            <div class="controls">
                                                <textarea name="top_js" class="input-block-level valid customfield"></textarea>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">Bottom JS<small></small></label>
                                            <div class="controls">
                                                <textarea name="bot_js" class="input-block-level valid customfield"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
							</div>
						</div>
					</form>

				</div>
			</div>
		</div>
	</div>
</div>
<input type="hidden" id="pwRoot" value="<?php echo $patchworks->pwRoot; ?>">
</body>
</html>
