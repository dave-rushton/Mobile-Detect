<?php

require_once('../../config/config.php');
require_once('../patchworks.php');
require_once("../reviews/classes/reviews.cls.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$TmpRev = new RevDAO();
$reviews = $TmpRev->select(NULL, NULL, NULL, false);

?>
<!doctype html>
<html>
<head>
    <title>Review Listing</title>
    <?php include('../webparts/headdata.php'); ?>

    <script>

        $(function(){

            $('#reviewBody')
                .on('click', 'a.confirmComment', function(e){

                    var rev_id = $(this).data('rev_id');

                    var cmtrow = $(this).parent().parent();

                    e.preventDefault();

                    $.ajax({
                        url: 'reviews/reviews_script.php',
                        data: 'action=confirm&ajax=true&rev_id=' + rev_id,
                        type: 'POST',
                        async: false,
                        success: function (data) {

                            var result = JSON.parse(data);

                            cmtrow.fadeOut();

                            $.msgGrowl({
                                type: result.type,
                                title: result.title,
                                text: result.description
                            });

                        },
                        error: function (x, e) {
                            throwAjaxError(x, e);
                        }
                    });

                }).on('click', 'a.deleteComment', function(e){

                    e.preventDefault();

                    var rev_id = $(this).data('rev_id');

                    var cmtrow = $(this).parent().parent();

                    $.msgAlert ({
                        type: 'warning'
                        , title: 'Delete This Review'
                        , text: 'Are you sure you wish to permanently remove this review from the database?'
                        , callback: function () {

                            var cmtrow = $(this).parent().parent();

                            e.preventDefault();

                            $.ajax({
                                url: 'reviews/reviews_script.php',
                                data: 'action=delete&ajax=true&rev_id=' + rev_id,
                                type: 'POST',
                                async: false,
                                success: function (data) {

                                    var result = JSON.parse(data);

                                    cmtrow.fadeOut();

                                    $.msgGrowl({
                                        type: result.type,
                                        title: result.title,
                                        text: result.description
                                    });

                                },
                                error: function (x, e) {
                                    throwAjaxError(x, e);
                                }
                            });

                        }
                    });



                })

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
                    <h1>Website Reviews</h1>
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
                        <a>Website</a>
                        <i class="icon-angle-right"></i>
                    </li>
                    <li>
                        <a href="reviews/reviews.php">Reviews</a>
                    </li>
                </ul>
            </div>
            <div class="row-fluid">
                <div class="span12">
                    <div class="box box-color box-bordered">
                        <div class="box-title">
                            <h3>
                                <i class="icon-star"></i> Website Reviews</h3>
                            <div class="actions">
                                <a href="reviews/reviews-edit.php" class="btn btn-mini" rel="tooltip" title="New Review"><i class="icon-plus"></i></a>
                            </div>
                        </div>
                        <div class="box-content nopadding">
                            <table class="table table-bordered table-striped table-highlight" id="reviewsTable">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Rating</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody id="reviewBody">
                                <?php
                                $tableLength = count($reviews);
                                for ($i=0;$i<$tableLength;++$i) {
                                    ?>
                                    <tr class="<?php if ($reviews[$i]['sta_id'] == 0) echo 'success'; ?>">
                                        <td><a href="<?php $patchworks->pwRoot; ?>reviews/reviews-edit.php?rev_id=<?php echo $reviews[$i]['rev_id']; ?>"><?php echo $reviews[$i]['revttl']; ?></a></td>
                                        <td><?php echo $reviews[$i]['revdsc']; ?></td>
                                        <td><?php echo $reviews[$i]['rating']; ?></td>

                                        <td>
                                            <a href="#" class="btn btn-mini btn-success confirmComment" data-rev_id="<?php echo $reviews[$i]['rev_id']; ?>"><i class="icon-ok-sign"></i></a>
                                            <a href="#" class="btn btn-mini btn-danger deleteComment" data-rev_id="<?php echo $reviews[$i]['rev_id']; ?>"><i class="icon-remove"></i></a>
                                        </td>

                                    </tr>
                                <?php } ?>
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
