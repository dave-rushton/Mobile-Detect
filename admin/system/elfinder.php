<?php

require_once('../../config/config.php');
require_once('../patchworks.php');

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!-- jQuery UI -->
<link rel="stylesheet" href="../css/plugins/jquery-ui/smoothness/jquery-ui.css">
<link rel="stylesheet" href="../css/plugins/jquery-ui/smoothness/jquery.ui.theme.css">

<!-- Elfinder -->
<link rel="stylesheet" href="../css/plugins/elfinder/elfinder.min.css">
<link rel="stylesheet" href="../css/plugins/elfinder/theme.css">

<!-- jQuery -->
<script src="../js/jquery.min.js"></script>
<!-- jQuery UI -->
<script src="../js/plugins/jquery-ui/jquery.ui.core.min.js"></script>
<script src="../js/plugins/jquery-ui/jquery.ui.widget.min.js"></script>
<script src="../js/plugins/jquery-ui/jquery.ui.mouse.min.js"></script>
<script src="../js/plugins/jquery-ui/jquery.ui.draggable.min.js"></script>
<script src="../js/plugins/jquery-ui/jquery.ui.resizable.min.js"></script>
<script src="../js/plugins/jquery-ui/jquery.ui.sortable.min.js"></script>
<script src="../js/plugins/jquery-ui/jquery.ui.selectable.min.js"></script>
<script src="../js/plugins/jquery-ui/jquery.ui.droppable.min.js"></script>
<script src="../js/plugins/jquery-ui/jquery.ui.draggable.min.js"></script>

<!-- elFinder --> 
<script src="../js/plugins/elfinder/elfinder.min.js"></script>

<!-- CKEditor --> 

<script type="text/javascript" charset="utf-8">
// Helper function to get parameters from the query string.
function getUrlParam(paramName) {
    var reParam = new RegExp('(?:[\?&]|&amp;)' + paramName + '=([^&]+)', 'i') ;
    var match = window.location.search.match(reParam) ;

    return (match && match.length > 1) ? match[1] : '' ;
}

$().ready(function() {
    var funcNum = getUrlParam('CKEditorFuncNum');

    var elf = $('#finder').elfinder({
        url : '../js/plugins/elfinder/php/connector.php',
        getFileCallback : function(file) {
            window.opener.CKEDITOR.tools.callFunction(funcNum, file.url);
            window.close();
        },
        resizable: false
    }).elfinder('instance');
});</script>

</head>

<body>

<div id="finder"></div>

</body>
</html>