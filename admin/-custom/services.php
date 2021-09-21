<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once('../website/classes/pagecontent.cls.php'); 

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$PgcDao = new PgcDAO();
$content = $PgcDao->selectGenericByTable('SERVICES', 0, NULL, false);

?>
<!doctype html>
<html>
<head>
<title>History</title>
<?php include('../webparts/headdata.php'); ?>


    <script>
        
        
        $(function(){

            $('#contentBody').sortable({
                handle: '.sortContent',
                stop: function( event, ui ) {

                    var pgcLst = '';

                    $('.sortContent', $('#contentBody')).each(function(){
                        pgcLst += (pgcLst == '') ? $(this).data('pgc_id') : ',' + $(this).data('pgc_id');
                    });

                    $.ajax({
                        url: 'website/json/content.json.php',
                        data: 'action=resort&ajax=true&pgc_id=' + pgcLst,
                        type: 'POST',
                        async: false,
                        success: function( data ) {

                            var result = JSON.parse(data);

                            $.msgGrowl ({
                                type: result.type
                                , title: result.title
                                , text: result.description
                            });

                        },
                        error: function (x, e) {
                            throwAjaxError(x, e);
                        }
                    });

                }
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
					<h1>History</h1>
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
						<a>History</a>
					</li>
				</ul>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-comments"></i> History</h3>
							<div class="actions">
								<a href="custom/services-edit.php" class="btn btn-mini" rel="tooltip" title="New Content"><i class="icon-plus"></i></a>
							</div>
						</div>
						<div class="box-content nopadding">
							<table class="table table-bordered table-striped table-highlight" id="contentTable">
							<thead>
								<tr>
									<th>Title</th>
									<th style="width: 30px"></th>
								</tr>
							</thead>
							<tbody id="contentBody">
								<?php
								$tableLength = count($content);
								for ($i=0;$i<$tableLength;++$i) {
								?>
								<tr>
									<td>
                                        <a href="custom/services-edit.php?pgc_id=<?php echo $content[$i]['pgc_id'] ?>"><?php echo $content[$i]['pgcttl'] ?></a>
                                    </td>
                                    <td>
                                        <a data-pgc_id="<?php echo $content[$i]['pgc_id'] ?>" class="sortContent"><i class="icon-reorder"></i></a>
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
