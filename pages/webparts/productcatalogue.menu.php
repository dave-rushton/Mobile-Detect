<?php
?>

<div class="section nopadding nomargin" id="menuwrapper">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">

                <div id="mainmenu">

                    <ul>

                        <?php
                        for ($c = 0; $c < count($categories); $c++) {
                            ?>


                            <li>
                                <a href="<?php echo $_GET['seourl'] . '/department/' . $categories[$c]['seourl']; ?>"><span>Shop for</span><?php echo $categories[$c]['catnam']; ?>
                                    <i></i></a>

                                <?php
                                $subCats = $TmpSub->select($categories[$c]['cat_id'], NULL, NULL, NULL, false);
                                if (count($subCats) > 0) {
                                    ?>

                                    <ul>

                                        <?php
                                        for ($s = 0; $s < count($subCats); $s++) {

                                            $TmpAtr = new AtrDAO();
                                            $attrGroups = $TmpAtr->select(NULL, 'PRODUCTGROUP', $subCats[$s]['sub_id'], NULL, false);

                                            ?>

                                            <li>
                                                <a href="<?php echo 'shop/department/' . $subCats[$s]['seourl']; ?>"><?php echo $subCats[$s]['subnam'] ?></a>

                                                <?php
                                                if (count($attrGroups) > 0) {
                                                    ?>
                                                    <ul>
                                                        <?php
                                                        for ($g = 0; $g < count($attrGroups); ++$g) {
                                                            ?>
                                                            <li>
                                                                <a href="<?php echo 'shop/productgroup/' . $attrGroups[$g]['seourl']; ?>"><?php echo $attrGroups[$g]['atrnam']; ?></a>
                                                            </li>
                                                            <?php
                                                        }
                                                        ?>


                                                    </ul>

                                                <?php } ?>

                                            </li>

                                            <?php
                                        }
                                        ?>

                                    </ul>

                                    <?php
                                }
                                ?>


                            </li>


                            <?php
                        }
                        ?>

                    </ul>


                </div>
            </div>
        </div>
    </div>
</div>