<?php

require_once("../../admin/products/classes/structure.cls.php");
$TmpStr = new StrDAO();

$qryArray = array();
$sql = 'SELECT a.atr_id, a.atrnam, a.seourl, s.subnam FROM attribute_group a INNER JOIN subcategories s ON s.sub_id = a.tbl_id WHERE a.tblnam = :tblnam ORDER BY s.subnam, a.atrnam';

$qryArray['tblnam'] = 'PRODUCTGROUP';
$productGroups = $patchworks->run($sql, $qryArray);

$productGroupHTML = '';
$productGroupSeoHTML = '';

$tableLength = count($productGroups);

$SubNamHld = '';

for ($i=0;$i<$tableLength;++$i) {

    if ($productGroups[$i]['subnam'] != $SubNamHld) {

        if ($i > 0) {
            $productGroupHTML .= '</optgroup>';
            $productGroupSeoHTML .= '</optgroup>';
        }

        $productGroupHTML .= '<optgroup label="'.$productGroups[$i]['subnam'].'">';
        $productGroupSeoHTML .= '<optgroup label="'.$productGroups[$i]['subnam'].'">';

        $SubNamHld = $productGroups[$i]['subnam'];

    }

    $productGroupHTML .= '<option value="'.$productGroups[$i]['atr_id'].'">'.$productGroups[$i]['atrnam'].'</option>';
    $productGroupSeoHTML .= '<option value="'.$productGroups[$i]['seourl'].'">'.$productGroups[$i]['atrnam'].'</option>';
}

$productGroupHTML .= '</optgroup>';
$productGroupSeoHTML .= '</optgroup>';

$qryArray = array();
$sql = 'SELECT * FROM pagecontent WHERE sta_id = 10';
$genericContent = $patchworks->run($sql, $qryArray);

$tableLength = count($genericContent);
$genericContentHTML = '';
for ($i=0;$i<$tableLength;++$i) {
    $genericContentHTML .= '<option value="'.$genericContent[$i]['pgc_id'].'">'.$genericContent[$i]['pgcttl'].'</option>';
}


$qryArray = array();
$sql = 'SELECT s.* FROM subcategories s INNER JOIN categories c ON c.cat_id = s.cat_id WHERE c.tblnam = "shopping-departments"';
$departments = $patchworks->run($sql, $qryArray);

$departmentsHTML = '';
$tableLength = count($departments);
for ($i=0;$i<$tableLength;++$i) {
    $departmentsHTML .= '<option value="'.$departments[$i]['seourl'].'">'.$departments[$i]['subnam'].'</option>';
}


$qryArray = array();
$sql = 'SELECT s.* FROM subcategories s INNER JOIN categories c ON c.cat_id = s.cat_id WHERE c.tblnam = "product-category" ORDER BY s.subnam';
$productCategories = $patchworks->run($sql, $qryArray);

$productCategoryHTML = '';
$tableLength = count($productCategories);
for ($i=0;$i<$tableLength;++$i) {
    $productCategoryHTML .= '<option value="'.$productCategories[$i]['sub_id'].'">'.$productCategories[$i]['subnam'].'</option>';
}
?>

<div id="articles-form" class="elementMarkup">
    <div class="editOptions">
        <form class="newsForm elementForm">

            <?php require "cms-form-template.php" ?>

            <h3>Listing</h3>
            <hr>
            <div class="inputContainer">
                <label>Hide Title</label>
                <div class="inputWrapper">
                    <input type="checkbox" value="hide_date" name="hide_title" />
                </div>
            </div>
            <div class="inputContainer">
                <label>Hide Date</label>
                <div class="inputWrapper">
                    <input type="checkbox" value="hide_date" name="hide_date" />
                </div>
            </div>
            <div class="inputContainer">
                <label>Hide Image</label>
                <div class="inputWrapper">
                    <input type="checkbox" value="hide_date" name="hide_image" />
                </div>
            </div>
            <div class="inputContainer">
                <label>Hide Description</label>
                <div class="inputWrapper">
                    <input type="checkbox" value="hide_date" name="hide_description" />
                </div>
            </div>
            <div class="inputContainer">
                <label>Listing Style</label>
                <div class="inputWrapper">
                    <select name="listing_template">
                        <option value="Style 1">Style 1</option>
                        <option value="Style 2">Style 2</option>
                        <option value="Style 3">Style 3</option>
                        <option value="Style 4">Style 4</option>
                    </select>
                </div>
            </div>
            <div class="inputContainer">
                <label>Article Item Page</label>
                <div class="inputWrapper">
                    <div class="menuMarkUpWrapper" data-htmlname="fwdurl">
                        <?php echo $menuHTML; ?>
                    </div>
                </div>
            </div>
            <div class="inputContainer">
                <label>Results Per Page</label>
                <div class="inputWrapper">
                    <select name="perpag">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                    </select>
                </div>
            </div>
            <div class="inputContainer">
                <label>Remove Paging</label>
                <div class="inputWrapper">
                    <input type="checkbox" name="rempag" />
                </div>
            </div>

            <div class="inputContainer">
                <label>Include Paging</label>
                <div class="inputWrapper">
                    <select name="style">
                        <option value="Style 1">Style 1</option>
                        <option value="Style 2">Style 2</option>
                        <option value="Style 3">Style 3</option>
                        <option value="Style 4">Style 4</option>
                    </select>
                </div>
            </div>
            <h3>Main Articles</h3>
            <hr>
            <div class="inputContainer">
                <label>Article Style</label>
                <div class="inputWrapper">
                    <select name="article_template">
                        <option value="Style 1">Style 1</option>
                        <option value="Style 2">Style 2</option>
                        <option value="Style 3">Style 3</option>
                        <option value="Style 4">Style 4</option>
                    </select>
                </div>
            </div>
            <div class="inputContainer">
                <label>Graphic Type</label>
                <div class="inputWrapper">
                    <select name="graphic_type">
                        <option value="slider">Slider</option>
                        <option value="image-list">Images List</option>
                        <option value="">None</option>
                    </select>
                </div>
            </div>
            <div class="inputContainer">
                <label>Content First</label>
                <div class="inputWrapper">
                    <input type="checkbox" name="content_first" value="content_first" />
                </div>
            </div>

            <div class="inputContainer">
                <label>Sidebar</label>
                <div class="inputWrapper">
                    <textarea name="sidebar" cols="30" rows="10" id="<?php echo uniqid(); ?>" class="tinymce"></textarea>
                </div>
            </div>
            <div class="inputContainer">
                <label>Article Types</label>
                <div class="inputWrapper">
                    <select name="arttyp">
                        <option value="">Show All</option>
                        <?php echo $articleTypesHTML; ?>
                    </select>
                </div>
            </div>



            <div class="buttonSet">
                <input type="submit" value="update" />
            </div>
        </form>
    </div>
</div>

<div id="gallery-listing-form" class="elementMarkup">
    <div class="editOptions">
        <form class="elementForm">
           <?php require "cms-form-template.php" ?>

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
                <label>Text</label>
                <div class="inputWrapper">
                    <textarea name="text" cols="30" rows="10" id="<?php echo uniqid(); ?>" class="tinymce"></textarea>
                </div>
            </div>
            <div class="buttonSet">
                <input type="submit" value="update" />
            </div>
        </form>
    </div>
</div>
<div id="gallery-listing-marquee-form" class="elementMarkup">
    <div class="editOptions">
        <form class="elementForm">
            <?php require "cms-form-template.php" ?>

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
                <label>Text</label>
                <div class="inputWrapper">
                    <textarea name="text" cols="30" rows="10" id="<?php echo uniqid(); ?>" class="tinymce"></textarea>
                </div>
            </div>
            <div class="buttonSet">
                <input type="submit" value="update" />
            </div>
        </form>
    </div>
</div>
<div id="gallery-listing-slider-form" class="elementMarkup">
    <div class="editOptions">
        <form class="elementForm">
           <?php require "cms-form-template.php" ?>
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
                <label>Text</label>
                <div class="inputWrapper">
                    <textarea name="text" cols="30" rows="10" id="<?php echo uniqid(); ?>" class="tinymce"></textarea>
                </div>
            </div>
            <div class="buttonSet">
                <input type="submit" value="update" />
            </div>
        </form>
    </div>
</div>

<div id="youtube-form" class="elementMarkup">
    <div class="editOptions">
        <form class="elementForm">
            <?php require "cms-form-template.php" ?>
            <div class="inputContainer">
                <label>Youtube ID/URL</label>
                <input type="text" name="link_location" value="" placeholder="Example: https://www.youtube.com/watch?v=I02nI6chKG8" />
            </div>
            <div class="buttonSet">
                <input type="submit" value="update" />
            </div>
        </form>
    </div>
</div>
<div id="text-form" class="elementMarkup">
    <div class="editOptions">
        <form class="elementForm">
            <?php require "cms-form-template.php" ?>
            <div class="inputContainer">
                <label>Text</label>
                <div class="inputWrapper">
                    <textarea name="text" cols="30" rows="10" id="<?php echo uniqid(); ?>" class="tinymce"></textarea>
                </div>
            </div>
            <div class="buttonSet">
                <input type="submit" value="update" />
            </div>
        </form>
    </div>
</div>

<div id="ico-description-form" class="elementMarkup">
    <div class="editOptions">
        <form class="elementForm">
            <?php require "cms-form-template.php" ?>
            <div class="inputContainer">
                <label>Adjustments</label>
                <div class="inputWrapper">
                    <select name="style">
                        <option value="">Default</option>
                        <option value="extra-height">Extra Height</option>
                        <option value="adjusted">Centered Title</option>
                    </select>
                </div>
            </div>
            <div class="inputContainer">
                <label>Style</label>
                <div class="inputWrapper">
                    <select name="main-style">
                        <option value="">Default</option>
                        <option value="item-inline">Inline</option>
                        <option value="fifth-containment">1/5</option>
                        <option value="full">Full</option>
                        <option value="full-capped">Full (Capped Height)</option>
                        <option value="custom">Custom</option>
                    </select>
                </div>
            </div>
            <div class="inputContainer">
                <label>Image</label>
                <div class="inputWrapper">
                    <select name="imgurl">
                        <option value=""></option>
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
                <label>Title</label>
                <div class="inputWrapper small-wrapper">
                    <textarea name="title" cols="30" rows="10" id="<?php echo uniqid(); ?>" class="tinymce"></textarea>
                </div>
            </div>
            <div class="inputContainer">
                <label>Text</label>
                <div class="inputWrapper small-wrapper">
                    <textarea name="text" cols="30" rows="10" id="<?php echo uniqid(); ?>" class="tinymce"></textarea>
                </div>
            </div>
            <div class="inputContainer">
                <label>Generic Content</label>
                <div class="inputWrapper small-wrapper">
                    <select name="generic_content" id="">
                        <option value="">Select Option</option>
                        <?php
                            echo $genericContentHTML;
                        ?>
                        <option value=""></option>
                    </select>
                </div>
            </div>
            <?php //This is here so we can unify the height. ?>
            <div class="inputContainer">
                <label>Enable Link</label>
                <div class="inputWrapper">
                    <input type="checkbox" name="enable_link" value="enabled" />
                </div>
            </div>
            <div class="inputContainer">
                <label>Link Text</label>
                <input type="text" name="link_text" value="" placeholder="" />
            </div>
            <div class="inputContainer">
                <label>Link</label>
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

<div id="just-image-form" class="elementMarkup">
    <div class="editOptions">
        <form class="elementForm">
            <?php require "cms-form-template.php" ?>

            <div class="inputContainer">
                <label>Image Style</label>
                <div class="inputWrapper">
                    <select name="image_style">
                        <option value="">Default</option>
                        <option value="full">Full Width</option>
                        <option value="rounded">Rounded</option>
                        <option value="left">Left</option>
                    </select>
                </div>
            </div>
            <div class="inputContainer">
                <label>Image</label>
                <div class="inputWrapper">
                    <select name="imgurl">
                        <option value="NA">No Image</option>
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
            <div class="buttonSet">
                <input type="submit" value="update" />
            </div>
        </form>
    </div>
</div>


<div id="hero-graphic-form" class="elementMarkup">
    <div class="editOptions">
        <form class="elementForm">
            <?php require "cms-form-template.php" ?>


            <div class="inputContainer">
                <label>Page Link</label>
                <div class="inputWrapper">
                    <div class="menuMarkUpWrapper" data-htmlname="fwdurl">
                        <?php echo $menuHTML; ?>
                    </div>
                </div>
            </div>
            <div class="inputContainer">
                <label>Height</label>
                <div class="inputWrapper">
                    <select name="size">
                        <option value="">Default</option>
                        <option value="small">Small</option>
                        <option value="medium">Medium</option>
                        <option value="large">Large</option>
                        <option value="scale">Scale</option>
                        <option value="full">Full</option>
                    </select>
                </div>
            </div>

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
                <label>Image (Mob)</label>
                <div class="inputWrapper">
                    <select name="mobimg">
                        <option value="">No Image</option>
                        <?php
                        echo $galleryHTML;
                        ?>
                    </select>
                </div>
            </div>
			 <div class="inputContainer">
                <label>Responsive Image</label>
                <div class="inputWrapper">
                    <input type="checkbox" name="background" value="yes" />
                </div>
            </div>
            <br/>
            <div class="inputContainer">
                <label>Icon Image</label>
                <div class="inputWrapper">
                    <select name="imgicon">
                        <option value="">No Image</option>
                        <?php
                        echo $galleryHTML;
                        ?>
                    </select>
                </div>
            </div>
            <div class="inputContainer">
                <label>Text</label>
                <div class="inputWrapper">
                    <textarea class="tinymce" name="text"></textarea>
                </div>
            </div>
            <div class="inputContainer">
                <label>Text First</label>
                <div class="inputWrapper">
                    <input type="checkbox" name="textfirst" value="textfirst" />
                </div>
            </div>
            <div class="buttonSet">
                <input type="submit" value="update" />
            </div>
        </form>
    </div>
</div>


<div id="siteSearchResultsForm " class="elementMarkup">
    <div class="editOptions">
        <form class="elementForm">
            <?php require "cms-form-template.php" ?>


            <div class="inputContainer">
                <label>Page Link</label>
                <div class="inputWrapper">
                    <div class="menuMarkUpWrapper" data-htmlname="fwdurl">
                        <?php echo $menuHTML; ?>
                    </div>
                </div>
            </div>
            <div class="inputContainer">
                <label>Height</label>
                <div class="inputWrapper">
                    <select name="size">
                        <option value="">Default</option>
                        <option value="small">Small</option>
                        <option value="medium">Medium</option>
                        <option value="large">Large</option>
                        <option value="full">Full</option>
                        <option value="scale">Scale</option>
                    </select>
                </div>
            </div>

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
            <br/>
            <div class="inputContainer">
                <label>Icon Image</label>
                <div class="inputWrapper">
                    <select name="imgicon">
                        <option value="">None</option>
                        <?php
                        echo $galleryHTML;
                        ?>
                    </select>
                </div>
            </div>
            <div class="inputContainer">
                <label>Text</label>
                <div class="inputWrapper">
                    <textarea class="tinymce" name="text"></textarea>
                </div>
            </div>
            <div class="inputContainer">
                <label>Text First</label>
                <div class="inputWrapper">
                    <input type="checkbox" name="textfirst" value="textfirst" />
                </div>
            </div>
            <div class="buttonSet">
                <input type="submit" value="update" />
            </div>
        </form>
    </div>
</div>


<div id="testimonials-form" class="elementMarkup">
    <div class="editOptions">
        <form class="elementForm">
            <?php require "cms-form-template.php" ?>

            <div class="buttonSet">
                <input type="submit" value="update" />
            </div>
        </form>
    </div>
</div>


<div id="cms-form-form" class="elementMarkup">
    <div class="editOptions">
        <form class="elementForm">
            <?php require "cms-form-template.php" ?>
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

<div id="people-form" class="elementMarkup">
    <div class="editOptions">
        <form class="elementForm">
            <?php require "cms-form-template.php" ?>
            <div class="buttonSet">
                <input type="submit" value="update" />
            </div>
        </form>
    </div>
</div>