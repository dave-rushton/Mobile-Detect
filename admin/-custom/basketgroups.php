<?php

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("../custom/classes/baskets.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');


$TmpBpg = new BpgDAO();
$basketProductGroups = $TmpBpg->select(NULL, 0, NULL, false);

?>
<!doctype html>
<html>
<head>
    <title><?php echo (isset($basketRec->bskttl)) ? $basketRec->bskttl : 'Baskets123'; ?></title>
    <?php include('../webparts/headdata.php'); ?>

    <script src="js/plugins/datatable/jquery.dataTables.min.js"></script>
    <script src="js/plugins/datatable/TableTools.min.js"></script>
    <script src="js/plugins/datatable/ColReorder.min.js"></script>
    <script src="js/plugins/datatable/ColVis.min.js"></script>
    <script src="js/plugins/datatable/jquery.dataTables.columnFilter.js"></script>

    <script>


        var addGroupForm;

        $(function(){

            addGroupForm = $('#addGroupForm');

            addGroupForm.submit(function(e){

                e.preventDefault();

                //alert('action=addgroup&bsk_id=' + $('[name="bsk_id"]', basketForm).val() + '&bpgttl=' + $('[name="bpgttl"]', addGroupForm).val());

                $.ajax({
                    url: 'custom/basket_script.php?action=addgroup&bsk_id=0&bpgttl=' + $('[name="bpgttl"]', addGroupForm).val(),
                    processData: false,
                    type: 'POST',
                    contentType: false,
                    aSync: false,
                    success: function(data) {

                        //alert(data);

                        location.reload();

                        $('[name="bpgttl"]', addGroupForm).val('');



                    },
                    error: function (x, e) {

                        throwAjaxError(x, e);

                    }
                });


            });


        })

    </script>

</head>
<?php include('../webparts/navigation.php'); ?>
<body class="theme-red">
<div class="container-fluid" id="content">
    <?php include('../webparts/website-left.php'); ?>
    <div id="main">
        <div class="container-fluid">
            <div class="page-header">
                <div class="pull-left">
                    <h1>Basket Groups</h1>
                </div>
                <div class="pull-right">
                    <?php include('../webparts/index-info.php'); ?>
                </div>
            </div>
            <div class="breadcrumbs">
                <ul>
                    <li>
                        <a href="index.php">Dashboard</a>
                        <i class="icon-angle-right"></i>
                    </li>
                    <li>
                        <a href="custom/basketgroups.php">Basket Groups</a>
                    </li>
                </ul>
            </div>
            <div class="row-fluid">
                <div class="span12">
                    <div class="box box-color box-bordered">
                        <div class="box-title">
                            <h3>
                                <i class="icon-shopping-cart"></i> Basket Groups</h3>
                            <div class="actions">
<!--                                <a href="custom/basketgroups-edit.php" class="btn btn-mini" rel="tooltip" title="New Basket"><i class="icon-plus"></i></a>-->
                            </div>
                        </div>

                        <div class="box-content nopadding">

                            <form id="addGroupForm" class="form-horizontal form-bordered form-validate">
                                <input type="hidden" name="bsk_id" value="0">

                                <div class="control-group">
                                    <label for="textfield" class="control-label">Create Basket Group</label>

                                    <div class="controls">
                                        <div class="input-append">
                                            <input type="text" class="input-large" placeholder="Enter Group Name" name="bpgttl">
                                            <button class="btn" type="submit" id="addGroupBtn"><span class="icon icon-plus"></span></button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>

                        <div class="box-content nopadding">
                            <table class="table table-bordered table-striped table-highlight" id="basketTable">
                                <thead>
                                <tr>
                                    <th>Basket Group Name</th>
                                </tr>
                                </thead>
                                <tbody id="basketBody">

                                <?php
                                for ($i=0;$i<count($basketProductGroups);$i++) {
                                    ?>

                                    <tr>
                                        <td>
                                            <a href="custom/basketgroups-edit.php?bpg_id=<?php echo $basketProductGroups[$i]['bpg_id']; ?>"><?php echo $basketProductGroups[$i]['bpgttl']; ?></a></td>

                                    </tr>

                                    <?php
                                }
                                ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
</body>
</html>
