<?php



$UplDao = new UplDAO();

$TmpPrd = new PrdDAO();

$TmpPrt = new PrtDAO();



$productType = $TmpPrt->select(NULL, $_GET['prtseo'], NULL, NULL, NULL, NULL, NULL, NULL, true);

$products = $TmpPrd->select(NULL, NULL, $productType->prt_id, NULL, NULL, NULL, 'p.srtord', false, NULL, NULL, 0);



//$uploads = $UplDao->select(NULL, 'PRDTYPE', $productType->prt_id, NULL, false);

$uploads = $TmpPrt->getProductImage($productType->prt_id);



?>



<h1>Product</h1>



<div class="row">

    <div class="col-md-12">

        <div class="productItem">



            <div class="breadCrumb">





                <?php

                //$structureParent = $TmpRel->select(NULL, 'PRODUCT', $productType->prt_id, 'STRUCTURE', NULL, true, ' srtord DESC ');

                //$TmpStr->getBreadCrumb($structureParent->ref_id);

                ?>



            </div>





            <div class="row">

                <div class="col-md-12">

                    <h1><?php echo $productType->prtnam; ?> </h1>

                    <h3 class="productPrice">&pound;<?php echo $productType->unipri; ?></h3>

                    <hr/>



                </div>

            </div>



            <div class="row">



                <div class="col-md-9">

                    <div id="productGallery">

                        <ul>



                            <?php

                            $tableLength = count($uploads);

                            for ($i = 0; $i < $tableLength; ++$i) {



                                echo '<li>';



                                if (

                                    file_exists($patchworks->docRoot . 'uploads/images/products/169-130/' . $uploads[$i]['filnam']) &&

                                    !is_dir($patchworks->docRoot . 'uploads/images/products/169-130/' . $uploads[$i]['filnam'])

                                ) {



                                    echo '<a href="uploads/images/products/' . $uploads[$i]['filnam'] . '" data-arr_id="'.$i.'" data-prd_id="' . $uploads[$i]['tbl_id'] . '" class="image-link productThumb" style="background-image: url(uploads/images/products/169-130/' . $uploads[$i]['filnam'] . ');">';

                                    echo '<img src="uploads/images/products/169-130/' . $uploads[$i]['filnam'] . '" alt="" /></a>';

                                    echo '<span>'.$uploads[$i]['prdnam'].'</span><br>';



                                } else {



                                    echo '<a href="uploads/images/products/' . $uploads[$i]['filnam'] . '" data-arr_id="'.$i.'" data-prd_id="' . $uploads[$i]['tbl_id'] . '" class="image-link productThumb">';

                                    echo '<div class="noimage" data-imgurl="'.$uploads[$i]['filnam'].'"></div></a>';

                                    echo '<span>'.$uploads[$i]['filnam'].'</span>';



                                }



                                echo '<div class="mobilebtns">';

                                echo '<a href="shoppingcart/add/'.$uploads[$i]['tbl_id'].'" class="addtocart">add to cart</a>';

                                echo '<a href="uploads/images/products/' . $uploads[$i]['filnam'] . '"  data-arr_id="'.$i.'" data-prd_id="' . $uploads[$i]['tbl_id'].'" class="viewproduct">view</a>';

                                echo '</div>';



                                echo '</li>';



                            }

                            ?>

                        </ul>

                    </div>



                </div>

                <div class="col-md-3 hidden-sm hidden-xs">



                    <?php if (count($uploads) > 0) { ?>



                        <a href="uploads/images/products/<?php echo $uploads[0]['filnam']; ?>" id="productPopupLink" class="image-link" target="_blank" data-arr_id="0">

                            <img src="uploads/images/products/<?php echo $uploads[0]['filnam']; ?>" id="heroImage"/>

                        </a>

                        <p class="redtext">click to view larger image</p>



                    <?php } else { ?>







                    <?php } ?>



                    <div class="productDescription">

                        <h3>Product Description</h3>

                        <?php echo $productType->prtdsc; ?>

                    </div>



                    <hr>

                    <h4>Add To Basket</h4>



                    <form action="pages/shoppingcart/shoppingcart_control.php" method="post" id="productForm"

                          class="form-vertical">



                        <input type="hidden" name="action" value="add">



                        <div class="control-group form-group">

                            <div class="controls">

                                <label>Select Product:</label>

                                <select name="prd_id" class="form-control">



                                    <?php

                                    $tableLength = count($products);

                                    for ($i = 0; $i < $tableLength; ++$i) {



                                        $products[$i]['unipri'] = $products[$i]['unipri'];



                                        ?>



                                        <option value="<?php echo $products[$i]['prd_id']; ?>">

                                            <?php echo $products[$i]['prdnam']; ?>



                                            <?php

                                            if ($products[$i]['unipri'] != $productType->unipri) echo ' (&pound;'.$products[$i]['unipri'].')';

                                            ?>



                                        </option>



                                        <?php

                                    }

                                    ?>

                                </select>



                            </div>

                        </div>



                        <div class="control-group form-group">

                            <div class="controls">

                                <label>Quantity:</label>

                                <input type="text" class="form-control" name="qty" value="1">



                            </div>

                        </div>



                        <div class="form-actions">

                            <button type="submit">Add To Cart</button>

                        </div>



                    </form>



                </div>





            </div>





            <!---->





        </div>





    </div>

</div>