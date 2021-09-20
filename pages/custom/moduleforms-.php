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


<div id="productCatalogueForm" class="elementMarkup">
    <div class="editOptions">
        <form class="elementForm">

            <div class="inputContainer">
                <label>Departments</label>
                <div class="inputWrapper">
                    <select name="catseo">
                        <option value="">Show All Departments</option>
                        <?php
                        echo $departmentsHTML;
                        ?>
                    </select>
                </div>
            </div>

            <div class="inputContainer">
                <label>Product Group</label>
                <div class="inputWrapper">
                    <select name="atrseo">
                        <option value="">Show All Groups</option>
                        <?php
                        echo $productGroupSeoHTML;
                        ?>
                    </select>
                </div>
            </div>

            <div class="inputContainer">
                <label>Forward To Page</label>
                <div class="inputWrapper">
                    <div class="menuMarkUpWrapper" data-htmlname="fwdurl">
                        <?php echo $menuHTML; ?>
                    </div>
                </div>
            </div>

            <div class="inputContainer">
                <label>Columns</label>
                <div class="inputWrapper">
                    <select name="numcol">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="6">6</option>
                    </select>
                </div>
            </div>

            <div class="inputContainer">
                <label>Per Page</label>
                <div class="inputWrapper">
                    <select name="perpag">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="6">6</option>
                        <option value="6">12</option>
                        <option value="6">18</option>
                    </select>
                </div>
            </div>

            <div class="buttonSet">
                <input type="submit" value="update" />
            </div>
        </form>
    </div>
</div>


<div id="productsByCategorySliderForm" class="elementMarkup">
    <div class="editOptions">
        <form class="elementForm">

            <div class="inputContainer">
                <label>Category</label>
                <div class="inputWrapper">
                    <select name="prdcat">
                        <option value="">Show All Categories</option>
                        <?php
                        echo $productCategoryHTML;
                        ?>
                    </select>
                </div>
            </div>

            <div class="inputContainer">
                <label>Forward To Page</label>
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


<div id="productTypesByCategorySliderForm" class="elementMarkup">
    <div class="editOptions">
        <form class="elementForm">

            <div class="inputContainer">
                <label>Category</label>
                <div class="inputWrapper">
                    <select name="prdcat">
                        <option value="">Show All Categories</option>
                        <?php
                        echo $productCategoryHTML;
                        ?>
                    </select>
                </div>
            </div>

            <div class="inputContainer">
                <label>Forward To Page</label>
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


<div id="iconSetForm" class="elementMarkup">
    <div class="editOptions">
        <form class="elementForm">

            <div class="inputContainer">
                <label>Title</label>
                <div class="inputWrapper">
                    <input type="text" name="boxttl">
                </div>
            </div>

            <div class="inputContainer">
                <label>Sub Title</label>
                <div class="inputWrapper">
                    <input type="text" name="subttl">
                </div>
            </div>

            <h3>Icon 1</h3>

            <div class="inputContainer">
                <label>Image</label>
                <div class="inputWrapper">
                    <select name="icoimg1">

                        <option value="NA">No Icon</option>

                        <?php
                        echo $galleryHTML;
                        ?>
                    </select>
                </div>
            </div>

            <div class="inputContainer">
                <label>Title</label>
                <div class="inputWrapper">
                    <input type="text" name="icottl1">
                </div>
            </div>

            <div class="inputContainer">
                <label>Text</label>
                <div class="inputWrapper">
                    <textarea name="icotxt1"></textarea>
                </div>
            </div>


            <div class="inputContainer">
                <label>Link To Page</label>
                <div class="inputWrapper">
                    <input type="text" name="icourl1">
                </div>
            </div>

            <h3>Icon 2</h3>

            <div class="inputContainer">
                <label>Image</label>
                <div class="inputWrapper">
                    <select name="icoimg2">

                        <option value="NA">No Icon</option>

                        <?php
                        echo $galleryHTML;
                        ?>
                    </select>
                </div>
            </div>

            <div class="inputContainer">
                <label>Title</label>
                <div class="inputWrapper">
                    <input type="text" name="icottl2">
                </div>
            </div>

            <div class="inputContainer">
                <label>Text</label>
                <div class="inputWrapper">
                    <textarea name="icotxt2"></textarea>
                </div>
            </div>

            <div class="inputContainer">
                <label>Link To Page</label>
                <div class="inputWrapper">
                    <input type="text" name="icourl2">
                </div>
            </div>

            <h3>Icon 3</h3>

            <div class="inputContainer">
                <label>Image</label>
                <div class="inputWrapper">
                    <select name="icoimg3">

                        <option value="NA">No Icon</option>

                        <?php
                        echo $galleryHTML;
                        ?>
                    </select>
                </div>
            </div>

            <div class="inputContainer">
                <label>Title</label>
                <div class="inputWrapper">
                    <input type="text" name="icottl3">
                </div>
            </div>

            <div class="inputContainer">
                <label>Text</label>
                <div class="inputWrapper">
                    <textarea name="icotxt3"></textarea>
                </div>
            </div>

            <div class="inputContainer">
                <label>Link To Page</label>
                <div class="inputWrapper">
                    <input type="text" name="icourl3">
                </div>
            </div>

            <h3>Icon 4</h3>

            <div class="inputContainer">
                <label>Image</label>
                <div class="inputWrapper">
                    <select name="icoimg4">

                        <option value="NA">No Icon</option>

                        <?php
                        echo $galleryHTML;
                        ?>
                    </select>
                </div>
            </div>

            <div class="inputContainer">
                <label>Title</label>
                <div class="inputWrapper">
                    <input type="text" name="icottl4">
                </div>
            </div>

            <div class="inputContainer">
                <label>Text</label>
                <div class="inputWrapper">
                    <textarea name="icotxt4"></textarea>
                </div>
            </div>

            <div class="inputContainer">
                <label>Link To Page</label>
                <div class="inputWrapper">
                    <input type="text" name="icourl4">
                </div>
            </div>

            <div class="buttonSet">
                <input type="submit" value="update" />
            </div>
        </form>
    </div>
</div>



<div id="homeStructureForm" class="elementMarkup">
    <div class="editOptions">
        <form class="elementForm">

            <div class="inputContainer">
                <label>Display Structure</label>
                <div class="inputWrapper">
                    <div class="menuMarkUpWrapper" data-htmlname="str_id">
                        <?php
                        $TmpStr->buildStructure(NULL, NULL, NULL, 'menuUL hide', false, NULL);
                        ?>
                    </div>
                </div>
            </div>


            <div class="inputContainer">
                <label>Forward To Page</label>
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



<div id="pageHeadingForm" class="elementMarkup">
    <div class="editOptions">
        <form class="elementForm">


            <div class="inputContainer">
                <label>Title 1</label>
                <div class="inputWrapper">
                    <input type="text" name="title1">
                </div>
            </div>
            <div class="inputContainer">
                <label>Title 2</label>
                <div class="inputWrapper">
                    <input type="text" name="title2">
                </div>
            </div>

            <div class="buttonSet">
                <input type="submit" value="update" />
            </div>
        </form>
    </div>
</div>


<div id="imageScrollForm" class="elementMarkup">
    <div class="editOptions">
        <form class="elementForm">


            <div class="inputContainer">
                <label>Title</label>
                <div class="inputWrapper">
                    <input type="text" name="title1">
                </div>
            </div>
            <div class="inputContainer">
                <label>Content</label>
                <div class="inputWrapper">
                    <textarea name="content" cols="30" rows="10" id="<?php echo uniqid(); ?>" class="tinymce"></textarea>
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

            <div class="buttonSet">
                <input type="submit" value="update" />
            </div>
        </form>
    </div>
</div>


<div id="topSliderForm" class="elementMarkup">
    <div class="editOptions">
        <form class="elementForm">

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

            <div class="buttonSet">
                <input type="submit" value="update" />
            </div>
        </form>
    </div>
</div>



<div id="topGalleryForm" class="elementMarkup">
    <div class="editOptions">
        <form class="elementForm">

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

            <div class="buttonSet">
                <input type="submit" value="update" />
            </div>
        </form>
    </div>
</div>


<div id="topImageForm" class="elementMarkup">
    <div class="editOptions">
        <form class="elementForm">
            <div class="inputContainer">
                <label>Title</label>
                <div class="inputWrapper">
                    <input type="text" name="imgtxt">
                </div>
            </div>

            <div class="inputContainer">
                <label>Content</label>
                <div class="inputWrapper">
                    <textarea name="sectxt" cols="30" rows="10" id="<?php echo uniqid(); ?>" class="tinymce"></textarea>
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
                <label>Page Link</label>
                <div class="inputWrapper">
                    <div class="menuMarkUpWrapper" data-htmlname="fwdurl">
                        <?php echo $menuHTML; ?>
                    </div>
                </div>
            </div>

            <div class="inputContainer">
                <label>Link Text</label>
                <div class="inputWrapper">
                    <input type="text" name="lnktxt">
                </div>
            </div>


            <div class="buttonSet">
                <input type="submit" value="update" />
            </div>
        </form>
    </div>
</div>

<div id="pageImageForm" class="elementMarkup">
    <div class="editOptions">
        <form class="elementForm">

            <div class="inputContainer">
                <label>Title</label>
                <div class="inputWrapper">
                    <textarea name="imgtxt" cols="30" rows="10" id="<?php echo uniqid(); ?>" class="tinymce"></textarea>
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
                <label>Page Link</label>
                <div class="inputWrapper">
                    <div class="menuMarkUpWrapper" data-htmlname="fwdurl">
                        <?php echo $menuHTML; ?>
                    </div>
                </div>
            </div>
            <div class="inputContainer">
                <label>Link Text</label>
                <div class="inputWrapper">
                    <input type="text" name="lnktxt">
                </div>
            </div>
            <div class="buttonSet">
                <input type="submit" value="update" />
            </div>
        </form>
    </div>
</div>

<div id="justImageForm" class="elementMarkup">
    <div class="editOptions">
        <form class="elementForm">
            <div class="inputContainer">
                <label>Padding</label>
                <div class="inputWrapper">
                    <select name="nopadding" id="">
                        <option value="">Yes</option>
                        <option value="nopadding">No</option>
                    </select>
                </div>
            </div>
            <div class="inputContainer">
                <label>Margin</label>
                <div class="inputWrapper">
                    <select name="nomargin" id="">
                        <option value="">Yes</option>
                        <option value="nomargin">No</option>
                    </select>
                </div>
            </div>

            <div class="inputContainer">
                <label>Full</label>
                <div class="inputWrapper">
                    <select name="full" id="">
                        <option value="full">Yes</option>
                        <option value="nofull">No</option>
                    </select>
                </div>
            </div>
            <div class="inputContainer">
                <label>Title</label>
                <div class="inputWrapper">
                    <textarea name="imgtxt" cols="30" rows="10" id="<?php echo uniqid(); ?>" class="tinymce"></textarea>
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
            <div class="buttonSet">
                <input type="submit" value="update" />
            </div>
        </form>
    </div>
</div>


<div id="styledMenuForm" class="elementMarkup">
    <div class="editOptions">
        <form class="elementForm">

            <div class="inputContainer">
                <label>Parent Page</label>
                <div class="inputWrapper">
                    <div class="menuMarkUpWrapper" data-htmlname="par_id">
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


<div id="customNewsForm" class="elementMarkup">
    <div class="editOptions">
        <form class="elementForm">

            <div class="inputContainer hide">
                <label>Title</label>
                <div class="inputWrapper">
                    <input type="text" name="secttl">
                </div>
            </div>

            <div class="inputContainer hide">
                <label>Sub Title</label>
                <div class="inputWrapper">
                    <input type="text" name="subttl">
                </div>
            </div>

            <div class="inputContainer hide">
                <label>Articles to display</label>
                <div class="inputWrapper">
                    <select name="numitm">
                        <option value="1">1</option>
                        <option value="2" selected>2</option>
                        <option value="2">3</option>
                        <option value="2">4</option>
                        <option value="2">6</option>
                        <option value="2">12</option>
                    </select>
                </div>
            </div>

            <div class="inputContainer hide">
                <label>Articles to display</label>
                <div class="inputWrapper">
                    <select name="numcol">
                        <option value="1">1</option>
                        <option value="2" selected>2</option>
                        <option value="2">3</option>
                        <option value="2">4</option>
                    </select>
                </div>
            </div>

            <div class="inputContainer">
                <label>Forward to Page</label>
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

<div id="textImageForm" class="elementMarkup">
    <div class="editOptions">
        <form class="elementForm">

            <div class="inputContainer hide">
                <label>Title</label>
                <div class="inputWrapper">
                    <input type="text" name="secttl">
                </div>
            </div>

            <div class="inputContainer hide">
                <label>Sub Title</label>
                <div class="inputWrapper">
                    <input type="text" name="subttl">
                </div>
            </div>

            <div class="inputContainer">
                <label>Content</label>
                <div class="inputWrapper">
                    <textarea name="sectxt" cols="30" rows="10" id="<?php echo uniqid(); ?>" class="tinymce"></textarea>
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
                <label>Image Text</label>
                <div class="inputWrapper">
                    <input type="text" name="imgtxt">
                </div>
            </div>

            <div class="inputContainer">
                <label>Image Position</label>
                <div class="inputWrapper">
                    <select name="imgpos">
                        <option value="L" selected>Left</option>
                        <option value="R">Right</option>
                    </select>
                </div>
            </div>

            <div class="inputContainer">
                <label>Display Type</label>
                <div class="inputWrapper">
                    <select name="dsptyp">
                        <option value="66" selected>Half and Half</option>
                        <option value="84">Wide and Thin</option>
                        <option value="48">Thin and Wide</option>
                    </select>
                </div>
            </div>


            <div class="buttonSet">
                <input type="submit" value="update" />
            </div>
        </form>
    </div>
</div>



<div id="expandContentForm" class="elementMarkup">
    <div class="editOptions">
        <form class="elementForm">

            <div class="inputContainer">
                <label>Title</label>
                <div class="inputWrapper">
                    <input type="text" name="boxttl">
                </div>
            </div>

            <div class="inputContainer">
                <label>Content</label>
                <div class="inputContainer">
                    <textarea class="tinymce" name="boxtxt" rows="6" style="width: 100%; height: 200px;"></textarea>
                </div>
            </div>

            <div class="buttonSet">
                <input type="submit" value="update" />
            </div>
        </form>
    </div>
</div>


<div id="productsByCategoryForm" class="elementMarkup">
    <div class="editOptions">
        <form class="elementForm">


            <div class="inputContainer">
                <label>Category</label>
                <div class="inputWrapper">
                    <select name="prdcat">
                        <option value="">Show All Categories</option>
                        <?php
                        echo $productCategoryHTML;
                        ?>
                    </select>
                </div>
            </div>

            <div class="inputContainer">
                <label>Forward To Page</label>
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




<div id="structureForm" class="elementMarkup">
    <div class="editOptions">
        <form class="elementForm">
            <div class="inputContainer">
                <label>Padding</label>
                <div class="inputWrapper">
                    <select name="nopadding" id="">
                        <option value="">Yes</option>
                        <option value="nopadding">No</option>
                    </select>
                </div>
            </div>
            <div class="inputContainer">
                <label>Margin</label>
                <div class="inputWrapper">
                    <select name="nomargin" id="">
                        <option value="">Yes</option>
                        <option value="nomargin">No</option>
                    </select>
                </div>
            </div>
            <div class="inputContainer">
                <label>Show Structure</label>
                <div class="inputWrapper">
                    <div class="menuMarkUpWrapper" data-htmlname="str_id">
                        <?php
                        $TmpStr->buildStructure(NULL, NULL, NULL, 'menuUL hide', true, NULL);
                        ?>
                    </div>
                </div>
            </div>

            <div class="inputContainer">
                <label>Products Template Page</label>
                <div class="inputWrapper">
                    <div class="menuMarkUpWrapper" data-htmlname="fwdurl">
                        <?php echo $menuHTML; ?>
                    </div>
                </div>
            </div>
            <div class="inputContainer">
                <label>Negative Margin</label>
                <div class="inputWrapper">
                    <select name="neg" id="">
                        <option value="">No</option>
                        <option value="neg-margin">Yes</option>
                    </select>
                </div>
            </div>
            <div class="buttonSet">
                <input type="submit" value="update" />
            </div>
        </form>
    </div>
</div>