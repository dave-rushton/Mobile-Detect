<?php

$menuHTML = $pageHandler->getMenu($_GET['seourl'], NULL, NULL, NULL, 'hide menuUL');

$qryArray = array();
$sql = 'SELECT u.*, g.galnam, g.imgsiz FROM uploads u INNER JOIN gallery g ON g.gal_id = u.tbl_id WHERE u.tblnam = :tblnam ORDER BY g.galnam, srtord ASC';

$qryArray['tblnam'] = 'WEBGALLERY';
$galleryImages = $patchworks->run($sql, $qryArray);

$galleryHTML = '';
$galleryIdHTML = '';

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
$sql = 'SELECT u.* FROM uploads u WHERE u.tblnam = :tblnam ORDER BY srtord ASC';
$qryArray['tblnam'] = 'GLOBAL';
$galleryImages = $patchworks->run($sql, $qryArray);


$tableLength = count($galleryImages);
if ($tableLength > 0) {
    $galleryHTML .= '<optgroup label="Global Gallery">';
    for ($i = 0; $i < $tableLength; ++$i) {
        $galleryHTML .= '<option value="uploads/images/' . $galleryImages[$i]['filnam'] . '">' . $galleryImages[$i]['uplttl'] . '</option>';
    }
    $galleryHTML .= '</optgroup>';
}

$tableLength = count($galleryImages);
if ($tableLength > 0) {
    $galleryIdHTML .= '<optgroup label="Global Gallery">';
    for ($i = 0; $i < $tableLength; ++$i) {
        $galleryIdHTML .= '<option value="uploads/images/' . $galleryImages[$i]['filnam'] . '">' . $galleryImages[$i]['uplttl'] . '</option>';
    }
    $galleryIdHTML .= '</optgroup>';
}

$qryArray = array();
$sql = 'SELECT * FROM gallery WHERE tblnam = :tblnam ORDER BY galnam';
$qryArray['tblnam'] = 'WEBGALLERY';
$galleries = $patchworks->run($sql, $qryArray);

$galleriesHTML = '';

$tableLength = count($galleries);
for ($i=0;$i<$tableLength;++$i) {
    $galleriesHTML .= '<option value="'.$galleries[$i]['gal_id'].'">'.$galleries[$i]['galnam'].'</option>';
}



//
// LIBRARIES
//

$qryArray = array();
$sql = 'SELECT u.*, g.galnam FROM uploads u INNER JOIN gallery g ON g.gal_id = u.tbl_id WHERE u.tblnam = :tblnam ORDER BY g.galnam, srtord ASC';
$qryArray['tblnam'] = 'DOWNLOAD';
$libraryFiles = $patchworks->run($sql, $qryArray);
$libraryHTML = '';
$libraryFilesHTML = '';
$tableLength = count($libraryFiles);
for ($i=0;$i<$tableLength;++$i) {
    $libraryFilesHTML .= '<option value="uploads/files/'.$libraryFiles[$i]['filnam'].'">'.$libraryFiles[$i]['galnam'].' : '.$libraryFiles[$i]['uplttl'].'</option>';
}


//
//
// FORMS


$qryArray = array();
$sql = 'SELECT * FROM attribute_group WHERE tblnam = :tblnam ORDER BY atrnam';

$qryArray['tblnam'] = 'FORM';
$forms = $patchworks->run($sql, $qryArray);

$formsHTML = '';

$tableLength = count($forms);
for ($i=0;$i<$tableLength;++$i) {
    $formsHTML .= '<option value="'.$forms[$i]['atr_id'].'">'.$forms[$i]['atrnam'].'</option>';
}


//
// GENERIC CONTENT
//


$qryArray = array();
$sql = 'SELECT * FROM pagecontent WHERE sta_id = 10';
$genericContent = $patchworks->run($sql, $qryArray);

$tableLength = count($genericContent);
$genericContentHTML = '';
for ($i=0;$i<$tableLength;++$i) {
    $genericContentHTML .= '<option value="'.$genericContent[$i]['pgc_id'].'">'.$genericContent[$i]['pgcttl'].'</option>';
}


$gallerySize = explode(",",$patchworks->galleryImageSizes);
$gallerySizeHTML = '';
for ($i=0;$i<count($gallerySize);$i++) {
    $imageSize = explode("-", $gallerySize[$i]);
    $gallerySizeHTML .= '<option value="'.$gallerySize[$i].'">'.$gallerySize[$i].'</option>';
}


//
// ARTICLE TYPES
//

$qryArray = array();
$sql = "SELECT s.* FROM subcategories s INNER JOIN categories c ON c.cat_id = s.cat_id WHERE c.tblnam = 'article-types'";
$articleTypes = $patchworks->run($sql, $qryArray);

$tableLength = count($articleTypes);
$articleTypesHTML = '';
for ($i=0;$i<$tableLength;++$i) {
    $articleTypesHTML .= '<option value="'.$articleTypes[$i]['seourl'].'">'.$articleTypes[$i]['subnam'].'</option>';
}

//
// ARTICLES
//

$qryArray = array();
$sql = "SELECT * FROM articles";
$articles = $patchworks->run($sql, $qryArray);

$tableLength = count($articles);
$articlesHTML = '';
for ($i=0;$i<$tableLength;++$i) {
    $articlesHTML .= '<option value="'.$articles[$i]['seourl'].'">Article : '.$articles[$i]['artttl'].'</option>';
}


//
// PAGES
//

$qryArray = array();
$sql = "SELECT * FROM pages WHERE id > 4";
$pages = $patchworks->run($sql, $qryArray);

$tableLength = count($pages);
$pagesHTML = '';
for ($i=0;$i<$tableLength;++$i) {
    $pagesHTML .= '<option value="'.$pages[$i]['seourl'].'">Page : '.$pages[$i]['title'].'</option>';
}

//
// HOT SPOTS
//

$qryArray = array();
$sql = "SELECT * FROM hotspots";
$hotSpots = $patchworks->run($sql, $qryArray);

$tableLength = count($hotSpots);
$hotSpotsHTML = '';
for ($i=0;$i<$tableLength;++$i) {
    $hotSpotsHTML .= '<option value="'.$hotSpots[$i]['hot_id'].'">'.$hotSpots[$i]['hotnam'].'</option>';
}



//
// HOT SPOTS
//

$qryArray = array();
$sql = "SELECT * FROM places where tblnam = 'LOCATION'";
$locations = $patchworks->run($sql, $qryArray);

$tableLength = count($locations);
$locationsHTML = '';
for ($i=0;$i<$tableLength;++$i) {
    $locationsHTML .= '<label><input type="checkbox" class="checkboxoption" value="'.$locations[$i]['pla_id'].'">'.$locations[$i]['planam'].'</label>';
}


?>


    <div id="column12Form" class="elementMarkup">
        <div class="editOptions">
            <form class="elementForm">

                <?php
                include('section.fields.php');
                ?>

                <div class="inputContainer">
                    <label>Text</label>
                </div>
                <div class="inputContainer">
                    <textarea class="tinymce" name="boxtxt" id="<?php echo uniqid(); ?>" rows="6" style="width: 100%; height: 200px;"></textarea>
                </div>
                <div class="inputContainer">
                    <label>Line Layout (HR)</label>
                    <div class="inputWrapper">

                        <select name="hr">

                            <option value="">Default</option>
                            <option value="hr-small">Small</option>
                            <option value="hr-wide">Wide</option>

                        </select>

                    </div>
                </div>
                <div class="buttonSet">
                    <input type="submit" value="update" />
                </div>
            </form>
        </div>
    </div>

    <div id="column66Form" class="elementMarkup">
        <div class="editOptions">
            <form class="elementForm">

                <?php
                include('section.fields.php');
                ?>

                <div class="inputContainer">
                    <label>Layout Option</label>
                    <div class="inputWrapper">

                        <select name="layout">

                            <option value="6-6">Half and Half</option>
                            <option value="4-8">Thin and Wide</option>
                            <option value="8-4">Wide and Thin</option>

                        </select>

                    </div>
                </div>

                <div class="inputContainer">
                    <textarea class="tinymce" name="lefttxt" id="lefttxt_<?php echo uniqid(); ?>" rows="6" style="width: 100%; height: 200px;"></textarea>
                </div>
                <div class="inputContainer">
                    <textarea class="tinymce" name="righttxt" id="righttxt_<?php echo uniqid(); ?>" rows="6" style="width: 100%; height: 200px;"></textarea>
                </div>
                <div class="buttonSet">
                    <input type="submit" value="update" />
                </div>
            </form>
        </div>
    </div>


    <div id="column444Form" class="elementMarkup">
        <div class="editOptions">
            <form class="elementForm">

                <?php
                include('section.fields.php');
                ?>

                <div class="inputContainer">
                    <label>Column 1 Text</label>
                </div>
                <div class="inputContainer">
                    <textarea class="tinymce" name="col1txt" id="col1txt_<?php echo uniqid(); ?>" rows="6" style="width: 100%; height: 200px;"></textarea>
                </div>
                <div class="inputContainer">
                    <label>Column 2 Text</label>
                </div>
                <div class="inputContainer">
                    <textarea class="tinymce" name="col2txt" id="col2txt<?php echo uniqid(); ?>" rows="6" style="width: 100%; height: 200px;"></textarea>
                </div>

                <div class="inputContainer">
                    <label>Column 3 Text</label>
                </div>
                <div class="inputContainer">
                    <textarea class="tinymce" name="col3txt" id="col3txt_<?php echo uniqid(); ?>" rows="6" style="width: 100%; height: 200px;"></textarea>
                </div>

                <div class="buttonSet">
                    <input type="submit" value="update" />
                </div>
            </form>
        </div>
    </div>

    <div id="column3333Form" class="elementMarkup">
        <div class="editOptions">
            <form class="elementForm">

                <?php
                include('section.fields.php');
                ?>

                <div class="inputContainer">
                    <label>Column 1 Text</label>
                </div>
                <div class="inputContainer">
                    <textarea class="tinymce" name="col1txt" id="col1txt_<?php echo uniqid(); ?>" rows="6" style="width: 100%; height: 200px;"></textarea>
                </div>
                <div class="inputContainer">
                    <label>Column 2 Text</label>
                </div>
                <div class="inputContainer">
                    <textarea class="tinymce" name="col2txt" id="col2txt<?php echo uniqid(); ?>" rows="6" style="width: 100%; height: 200px;"></textarea>
                </div>

                <div class="inputContainer">
                    <label>Column 3 Text</label>
                </div>
                <div class="inputContainer">
                    <textarea class="tinymce" name="col3txt" id="col3txt_<?php echo uniqid(); ?>" rows="6" style="width: 100%; height: 200px;"></textarea>
                </div>
                <div class="inputContainer">
                    <label>Column 4 Text</label>
                </div>
                <div class="inputContainer">
                    <textarea class="tinymce" name="col4txt" id="col4txt<?php echo uniqid(); ?>" rows="6" style="width: 100%; height: 200px;"></textarea>
                </div>

                <div class="buttonSet">
                    <input type="submit" value="update" />
                </div>
            </form>
        </div>
    </div>

    <div id="defaultElementMarkUp" class="elementMarkup">
        <div class="elementOptions">
            <a href="#" class="editButton"  rel="tooltip" title="Edit Element"><i class="icon icon-edit"></i></a>
            <a href="#" class="deleteButton"><i class="icon icon-trash" rel="tooltip" title="Delete Element"></i></a>
            <a href="#" class="moveElement"  rel="tooltip" title="Transfer Element"><i class="icon icon-share-alt"></i></a>
            <a href="#" class="cloneButton"  rel="tooltip" title="Clone Element"><i class="icon icon-eye-open"></i></a>
            <a href="#" class="draggerHandle" rel="tooltip" title="Move Element"><i class="icon icon-move"></i></a>
        </div>
        <div class="deleteOptions">
            <form class="elementForm deleteForm">
                <div class="inputContainer">
                    <label>Delete element</label>
                </div>
                <div class="buttonSet">
                    <input type="submit" value="delete" />
                </div>
            </form>
        </div>
    </div>

    <div id="menuForm" class="elementMarkup">
        <div class="editOptions">
            <form class="elementForm">

                <div class="inputContainer">
                    <label>Class</label>
                    <div class="inputWrapper">
                        <input type="text" name="clsnam">
                    </div>
                </div>

                <div class="inputContainer">
                    <label>ID</label>
                    <div class="inputWrapper">
                        <input type="text" name="id_nam">
                    </div>
                </div>

                <div class="inputContainer">
                    <label>Parent Page</label>
                    <div class="inputWrapper">
                        <div class="menuMarkUpWrapper" data-htmlname="parseo">
                            <?php echo $menuHTML; ?>
                        </div>
                    </div>
                </div>
                <div class="inputContainer hide">
                    <label>Include Parent</label>
                    <div class="inputWrapper">
                        <input type="checkbox" name="incpar" />
                    </div>
                </div>
                <div class="buttonSet">
                    <input type="submit" value="update" />
                </div>
            </form>
        </div>
    </div>


    <div id="genericContentForm" class="elementMarkup">
        <div class="editOptions">
            <form class="elementForm">

                <?php
                include('section.fields.php');
                ?>

                <div class="inputContainer">
                    <div class="inputWrapper">
                        <select name="pgc_id">
                            <?php
                            echo $genericContentHTML;
                            ?>
                        </select>
                    </div>
                </div>
                <div class="buttonSet">
                    <input type="submit" value="update" />
                </div>
            </form>
        </div>
    </div>





    <div id="imageForm" class="elementMarkup">
        <div class="editOptions">
            <form class="elementForm">

                <?php
                include('section.fields.php');
                ?>

                <div class="inputContainer">
                    <label>Image</label>
                    <div class="inputWrapper">
                        <select name="imgurl">
                            <?php
                            echo $galleryHTML;
                            ?>
                        </select>
                    </div>
                </div>

                <div class="inputContainer">
                    <label>Image Size</label>
                    <div class="inputWrapper">
                        <select name="imgsiz">
                            <?php
                            echo $gallerySizeHTML;
                            ?>
                        </select>
                    </div>
                </div>

                <div class="inputContainer">
                    <label>Height</label>
                    <div class="inputWrapper">
                        <input type="text" name="height" value="100" />
                    </div>
                </div>

                <div class="buttonSet">
                    <input type="submit" value="update" />
                </div>
            </form>
        </div>
    </div>


    <div id="parallaxImageForm" class="elementMarkup">
        <div class="editOptions">
            <form class="elementForm">

                <?php
                include('section.fields.php');
                ?>

                <div class="inputContainer">
                    <label>Image</label>
                    <div class="inputWrapper">
                        <select name="imgurl">
                            <?php
                            echo $galleryHTML;
                            ?>
                        </select>
                    </div>
                </div>
                <div class="inputContainer">
                    <label>Section Text</label>
                    <div class="inputWrapper">
                        <textarea class="tinymce" name="sectxt" rows="6" style="width: 100%; height: 300px;"></textarea>
                    </div>
                </div>
                <div class="inputContainer">
                    <label>Section Size</label>
                    <div class="inputWrapper">
                        <select name="secsiz">
                            <option value="" selected>Auto</option>
                            <option value="fullscreen">Full Screen</option>
                        </select>
                    </div>
                </div>
                <div class="inputContainer">
                    <label>Difference (100 - 400)</label>
                    <div class="inputWrapper">
                        <input type="text" name="secdif" value="100" />
                    </div>
                </div>

                <div class="buttonSet">
                    <input type="submit" value="update" />
                </div>
            </form>
        </div>
    </div>

    <div id="galleryForm" class="elementMarkup">
        <div class="editOptions">
            <form class="elementForm">

                <?php
                include('section.fields.php');
                ?>

                <div class="inputContainer">
                    <label>Gallery</label>
                    <div class="inputWrapper">
                        <select name="gal_id">
                            <?php
                            echo $galleriesHTML;
                            ?>
                        </select>
                    </div>
                </div>
                <div class="buttonSet">
                    <input type="submit" value="update" />
                </div>
            </form>
        </div>
    </div>

    


    <div id="flexsliderForm" class="elementMarkup">
        <div class="editOptions">
            <form class="elementForm">

                <?php
                include('section.fields.php');
                ?>

                <div class="inputContainer">
                    <label>Gallery</label>
                    <div class="inputWrapper">
                        <select name="gal_id">
                            <?php
                            echo $galleriesHTML;
                            ?>
                        </select>
                    </div>
                </div>

                <div class="inputContainer">
                    <label>Image Size</label>
                    <div class="inputWrapper">
                        <select name="imgsiz">
                            <?php
                            echo $gallerySizeHTML;
                            ?>
                        </select>
                    </div>
                </div>

                <div class="inputContainer">
                    <label>Full Width</label>
                    <div class="inputWrapper">
                        <input type="checkbox" name="fulwid" />
                    </div>
                </div>

                <div class="inputContainer">
                    <label>Direction Control</label>
                    <div class="inputWrapper">
                        <input type="checkbox" name="dircon" />
                    </div>
                </div>

                <div class="inputContainer">
                    <label>Slide Selection</label>
                    <div class="inputWrapper">
                        <input type="checkbox" name="sldsel" />
                    </div>
                </div>

                <div class="buttonSet">
                    <input type="submit" value="update" />
                </div>
            </form>
        </div>
    </div>

    <div id="kenBurnsForm" class="elementMarkup">
        <div class="editOptions">
            <form class="elementForm">

                <?php
                include('section.fields.php');
                ?>

                <div class="inputContainer">
                    <label>Gallery</label>
                    <div class="inputWrapper">
                        <select name="gal_id">
                            <?php
                            echo $galleriesHTML;
                            ?>
                        </select>
                    </div>
                </div>

                <div class="inputContainer">
                    <label>Image Size</label>
                    <div class="inputWrapper">
                        <select name="imgsiz">
                            <option value="">Original Size</option>
                            <?php
                            echo $gallerySizeHTML;
                            ?>
                        </select>
                    </div>
                </div>

                <div class="inputContainer">
                    <label>Full Width</label>
                    <div class="inputWrapper">
                        <input type="checkbox" name="fulwid" />
                    </div>
                </div>

                <div class="buttonSet">
                    <input type="submit" value="update" />
                </div>
            </form>
        </div>
    </div>


    <!--

    ARTICLES

    -->

    <!--<div id="newsListForm" class="elementMarkup">-->
    <!--	<div class="editOptions">-->
    <!--		<form class="newsForm elementForm">-->
    <!---->
    <!--            <div class="inputContainer">-->
    <!--                <label>Article Types</label>-->
    <!--                <div class="inputWrapper">-->
    <!--                    <select name="arttyp">-->
    <!--                        <option value="">Show All</option>-->
    <!--                        --><?php //echo $articleTypesHTML; ?>
    <!--                    </select>-->
    <!--                </div>-->
    <!--            </div>-->
    <!---->
    <!--			<div class="inputContainer">-->
    <!--				<label>Article Item Page</label>-->
    <!--				<div class="inputWrapper">-->
    <!--					<div class="menuMarkUpWrapper" data-htmlname="fwdurl">-->
    <!--					--><?php //echo $menuHTML; ?>
    <!--					</div>-->
    <!--				</div>-->
    <!--			</div>-->
    <!--			<div class="inputContainer">-->
    <!--				<label>Results Per Page</label>-->
    <!--				<div class="inputWrapper">-->
    <!--					<select name="perpag">-->
    <!--						<option value="1">1</option>-->
    <!--						<option value="2">2</option>-->
    <!--						<option value="3">3</option>-->
    <!--						<option value="4">4</option>-->
    <!--						<option value="5">5</option>-->
    <!--						<option value="10">10</option>-->
    <!--						<option value="20">20</option>-->
    <!--						<option value="50">50</option>-->
    <!--					</select>-->
    <!--				</div>-->
    <!--			</div>-->
    <!---->
    <!--            <div class="inputContainer">-->
    <!--                <label>Columns</label>-->
    <!--                <div class="inputWrapper">-->
    <!--                    <select name="numcol">-->
    <!--                        <option value="1">1</option>-->
    <!--                        <option value="2">2</option>-->
    <!--                        <option value="3">3</option>-->
    <!--                        <option value="4">4</option>-->
    <!--                        <option value="6">6</option>-->
    <!--                    </select>-->
    <!--                </div>-->
    <!--            </div>-->
    <!---->
    <!--			<div class="inputContainer">-->
    <!--				<label>Remove Paging</label>-->
    <!--				<div class="inputWrapper">-->
    <!--					<input type="checkbox" name="rempag" />-->
    <!--				</div>-->
    <!--			</div>-->
    <!--            <div class="inputContainer">-->
    <!--                <label>Include Paging</label>-->
    <!--                <div class="inputWrapper">-->
    <!--                    <select name="style">-->
    <!--                        <option value="Style 1">Style 1</option>-->
    <!--                        <option value="Style 2">Style 2</option>-->
    <!--                        <option value="Style 3">Style 3</option>-->
    <!--                        <option value="Style 4">Style 4</option>-->
    <!--                    </select>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--			<div class="buttonSet">-->
    <!--				<input type="submit" value="update" />-->
    <!--			</div>-->
    <!--		</form>-->
    <!--	</div>-->
    <!--</div>-->
    <!---->
    <!--<div id="articleFilterForm" class="elementMarkup">-->
    <!--	<div class="editOptions">-->
    <!--		<form class="newsForm elementForm">-->
    <!--			<div class="inputContainer">-->
    <!--				<label>HTML Class Name</label>-->
    <!--				<div class="inputContainer">-->
    <!--					<input type="text" name="clsnam" />-->
    <!--				</div>-->
    <!--			</div>-->
    <!--			<div class="buttonSet">-->
    <!--				<input type="submit" value="update" />-->
    <!--			</div>-->
    <!--		</form>-->
    <!--	</div>-->
    <!--</div>-->
    <!---->
    <!--<div id="articleArchiveForm" class="elementMarkup">-->
    <!--	<div class="editOptions">-->
    <!--		<form class="newsForm elementForm">-->
    <!--			<div class="inputContainer">-->
    <!--				<label>HTML Class Name</label>-->
    <!--				<div class="inputContainer">-->
    <!--					<input type="text" name="clsnam" />-->
    <!--				</div>-->
    <!--			</div>-->
    <!--			<div class="buttonSet">-->
    <!--				<input type="submit" value="update" />-->
    <!--			</div>-->
    <!--		</form>-->
    <!--	</div>-->
    <!--</div>-->

    <!--

    FORMS

    -->

    <div id="websiteFormForm" class="elementMarkup">
        <div class="editOptions">
            <form class="elementForm">

                <?php
                include('section.fields.php');
                ?>

                <div class="inputContainer">
                    <label>Form</label>
                    <div class="inputWrapper">
                        <select name="atr_id">
                            <?php
                            echo $formsHTML;
                            ?>
                        </select>
                    </div>
                </div>

                <div class="inputContainer">
                    <label>Show Title</label>
                    <div class="inputWrapper">
                        <input type="checkbox" name="shwttl" />&nbsp;
                    </div>
                </div>

                <div class="inputContainer">
                    <label>Show Description</label>
                    <div class="inputWrapper">
                        <input type="checkbox" name="shwdsc" />&nbsp;
                    </div>
                </div>

                <div class="inputContainer">
                    <label>Show Labels</label>
                    <div class="inputWrapper">
                        <input type="checkbox" name="shwlbl" />&nbsp;
                    </div>
                </div>

                <div class="inputContainer">
                    <label>Thank you page</label>
                    <div class="inputWrapper">
                        <div class="menuMarkUpWrapper" data-htmlname="fwdurl">
                            <?php echo $menuHTML; ?>
                        </div>
                    </div>
                </div>

                <div class="inputContainer">
                    <label>Fail Page</label>
                    <div class="inputWrapper">
                        <div class="menuMarkUpWrapper" data-htmlname="alturl">
                            <?php echo $menuHTML; ?>
                        </div>
                    </div>
                </div>

                <div class="buttonSet">
                    <input type="submit" value="update" />
                </div>
            </form>
        </div>
    </div>

    <div id="webResultForm" class="elementMarkup">
        <div class="editOptions">
            <form class="elementForm">

                <?php
                include('section.fields.php');
                ?>

                <div class="inputContainer">
                    <label>Success message title</label>
                    <div class="inputWrapper">
                        <input type="text" name="sucttl">
                    </div>
                </div>

                <div class="inputContainer">
                    <label>Success message</label>
                    <div class="inputWrapper">
                        <textarea class="tinymce" name="sucmsg" id="sucmsg<?php echo uniqid(); ?>" rows="6" style="width: 100%; height: 200px;"></textarea>
                    </div>
                </div>

                <div class="inputContainer">
                    <label>Captcha not ticked title</label>
                    <div class="inputWrapper">
                        <input type="text" name="capttl">
                    </div>
                </div>

                <div class="inputContainer">
                    <label>Captcha not ticked message</label>
                    <div class="inputWrapper">
                        <textarea class="tinymce" name="capmsg" id="capmsg<?php echo uniqid(); ?>" rows="6" style="width: 100%; height: 200px;"></textarea>
                    </div>
                </div>

                <div class="inputContainer">
                    <label>Fail message title</label>
                    <div class="inputWrapper">
                        <input type="text" name="errttl">
                    </div>
                </div>

                <div class="inputContainer">
                    <label>Error message</label>
                    <div class="inputWrapper">
                        <textarea class="tinymce" name="errmsg" id="errmsg<?php echo uniqid(); ?>" rows="6" style="width: 100%; height: 200px;"></textarea>
                    </div>
                </div>

                <div class="buttonSet">
                    <input type="submit" value="update" />
                </div>
            </form>
        </div>
    </div>


    <div id="websiteSearchFormForm" class="elementMarkup">
        <div class="editOptions">
            <form class="elementForm">

                <?php
                include('section.fields.php');
                ?>

                <div class="inputContainer">
                    <label>Results page</label>
                    <div class="inputWrapper">
                        <div class="menuMarkUpWrapper" data-htmlname="fwdurl">
                            <?php echo $menuHTML; ?>
                        </div>
                    </div>
                </div>
                <div class="buttonSet">
                    <input type="submit" value="update" />
                </div>
            </form>
        </div>
    </div>



    <div id="youtubeForm" class="elementMarkup">
        <div class="editOptions">
            <form class="elementForm">

                <?php
                include('section.fields.php');
                ?>

                <div class="inputContainer">
                    <label>Video ID</label>
                    <div class="inputWrapper">
                        <input type="text" name="vid_id" />
                    </div>
                </div>

                <div class="buttonSet">
                    <input type="submit" value="update" />
                </div>
            </form>
        </div>
    </div>


    <div id="downloadLinkForm" class="elementMarkup">
        <div class="editOptions">
            <form class="elementForm">

                <?php
                include('section.fields.php');
                ?>

                <div class="inputContainer">
                    <label>Text</label>
                    <div class="inputWrapper">
                        <textarea name="boxtxt" rows="6"></textarea>
                    </div>
                </div>

                <div class="inputContainer">
                    <label>Library File</label>
                    <div class="inputWrapper">
                        <select name="upl_id">
                            <?php
                            echo $libraryFilesHTML;
                            ?>
                        </select>
                    </div>
                </div>

                <div class="buttonSet">
                    <input type="submit" value="update" />
                </div>
            </form>
        </div>
    </div>



    <div id="locationsForm" class="elementMarkup">
        <div class="editOptions">
            <form class="elementForm">

                <?php
                include('section.fields.php');
                ?>

                <div class="inputContainer">
                    <label>Location Type</label>
                    <div class="inputWrapper">
                        <select name="loctyp">
                            <option value="0">Default</option>
                        </select>
                    </div>
                </div>
                <div class="buttonSet">
                    <input type="submit" value="update" />
                </div>
            </form>
        </div>
    </div>


    <div id="galleriesForm" class="elementMarkup">
        <div class="editOptions">
            <form class="elementForm">
                <div class="inputContainer">
                    <label>Image</label>
                    <div class="inputWrapper">
                        <select name="gal_id">
                            <?php
                            echo $galleriesHTML;
                            ?>
                        </select>
                    </div>
                </div>

                <div class="inputContainer">
                    <label>Image Size</label>
                    <div class="inputWrapper">
                        <select name="imgsiz">
                            <option value="">Original Size</option>
                            <?php
                            echo $gallerySizeHTML;
                            ?>
                        </select>
                    </div>
                </div>

                <div class="inputContainer">
                    <label>Columns</label>
                    <div class="inputWrapper">
                        <select name="numcol">
                            <option>2</option>
                            <option>4</option>
                            <option>6</option>
                        </select>
                    </div>
                </div>

                <div class="inputContainer">
                    <label>Include Other Galleries</label>
                    <div class="inputWrapper">
                        <input type="checkbox" name="incoth" />
                    </div>
                </div>

                <div class="buttonSet">
                    <input type="submit" value="update" />
                </div>
            </form>
        </div>
    </div>


<?php

if (file_exists('../../pages/custom/moduleforms.php')) {
    include('../../pages/custom/moduleforms.php');
}

if (file_exists('../../pages/standard/moduleforms.php')) {
    include('../../pages/standard/moduleforms.php');
}

?>