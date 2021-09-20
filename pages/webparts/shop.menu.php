<?php

require_once('../admin/products/classes/structure.cls.php');

$TmpStr = new StrDAO();

?>

<div class="section nopadding nomargin" id="menuwrapper">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">

                <div id="mainmenu">
                    <?php
                    $TmpStr->buildStructure(0, $_GET['seourl'], 'structuremenu', NULL, false);
                    ?>
                </div>

            </div>
        </div>
    </div>
</div>

