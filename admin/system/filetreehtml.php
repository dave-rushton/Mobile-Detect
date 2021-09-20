<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

?>
<!doctype html>
<html>
<head>
<title>System Filetree</title>
<?php include('../webparts/headdata.php'); ?>
<!-- Elfinder -->
<link rel="stylesheet" href="css/plugins/elfinder/elfinder.min.css">
<link rel="stylesheet" href="css/plugins/elfinder/theme.css">
</head>

<body>
<div id="fileTree"></div>
</body>


<!-- Elfinder -->
<script src="js/plugins/elfinder/elfinder.min.js"></script>

<script type="text/javascript" charset="utf-8">
// Helper function to get parameters from the query string.
function getUrlParam(paramName) {
    var reParam = new RegExp('(?:[\?&]|&amp;)' + paramName + '=([^&]+)', 'i') ;
    var match = window.location.search.match(reParam) ;

    return (match && match.length > 1) ? match[1] : '' ;
}

$().ready(function() {
    var funcNum = getUrlParam('CKEditorFuncNum');

    var elf = $('#fileTree').elfinder({
        url : 'js/plugins/elfinder/php/connector.php',
        getFileCallback : function(file) {
            window.opener.CKEDITOR.tools.callFunction(funcNum, file);
            window.close();
        },
        resizable: false
    }).elfinder('instance');
});</script>

<script>

$(function(){
	
//	var elf = $('#fileTree').elfinder({
//		url : 'js/plugins/elfinder/php/connector.php'  // connector URL (REQUIRED)
//	}).elfinder('instance');
	
//	$('#fileTree').elfinder({
//		url:'js/plugins/elfinder/php/connector.php'
//	});
	
});

</script>

</html>
