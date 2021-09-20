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

<!-- TinyMCE Popup class (REQUIRED) -->
<!--<script src="../js/plugins/tiny_mce/tiny_mce_popup.js"></script>-->

  <script type="text/javascript">
    var FileBrowserDialogue = {
      init: function() {
        // Here goes your code for setting your custom things onLoad.
      },
      mySubmit: function (URL) {
        // pass selected file path to TinyMCE
        parent.tinymce.activeEditor.windowManager.getParams().setUrl(URL);

        // force the TinyMCE dialog to refresh and fill in the image dimensions
        var t = parent.tinymce.activeEditor.windowManager.windows[0];
        t.find('#src').fire('change');

        // close popup window
        parent.tinymce.activeEditor.windowManager.close();
      }
    }

    $().ready(function() {
      var elf = $('#finder').elfinder({
        // set your elFinder options here
        url: '../js/plugins/elfinder/php/connector.php',  // connector URL
        getFileCallback: function(file) { // editor callback
          // file.url - commandsOptions.getfile.onlyURL = false (default)
          // file     - commandsOptions.getfile.onlyURL = true
          FileBrowserDialogue.mySubmit(file.url); // pass selected file path to TinyMCE
        }
      }).elfinder('instance');
    });
  </script>
<!--  -->
<!--<script type="text/javascript">-->
<!--  var FileBrowserDialogue = {-->
<!--    init: function() {-->
<!--      // Here goes your code for setting your custom things onLoad.-->
<!--    },-->
<!--    mySubmit: function (URL) {-->
<!--      var win = tinyMCEPopup.getWindowArg('window');-->
<!---->
<!--      // pass selected file path to TinyMCE-->
<!--      win.document.getElementById(tinyMCEPopup.getWindowArg('input')).value = URL;-->
<!---->
<!--      // are we an image browser?-->
<!--      if (typeof(win.ImageDialog) != 'undefined') {-->
<!--        // update image dimensions-->
<!--        if (win.ImageDialog.getImageData) {-->
<!--          win.ImageDialog.getImageData();-->
<!--        }-->
<!--        // update preview if necessary-->
<!--        if (win.ImageDialog.showPreviewImage) {-->
<!--          win.ImageDialog.showPreviewImage(URL);-->
<!--        }-->
<!--      }-->
<!---->
<!--      // close popup window-->
<!--      tinyMCEPopup.close();-->
<!--    }-->
<!--  }-->
<!---->
<!--  tinyMCEPopup.onInit.add(FileBrowserDialogue.init, FileBrowserDialogue);-->
<!---->
<!--  $().ready(function() {-->
<!--    var elf = $('#finder').elfinder({-->
<!--      // set your elFinder options here-->
<!--      url: '../js/plugins/elfinder/php/connector.php',  // connector URL-->
<!--      getFileCallback: function(file) { // editor callback-->
<!--	  -->
<!--	  //alert(file.url);-->
<!--	  -->
<!--	  	//file.url = file.url.substr(0, (file.url.length-6));-->
<!--	  -->
<!--        FileBrowserDialogue.mySubmit(file.url); // pass selected file path to TinyMCE -->
<!--      }-->
<!--    }).elfinder('instance');      -->
<!--  });-->
<!--</script>-->

</head>

<body>

<div id="finder"></div>

</body>
</html>