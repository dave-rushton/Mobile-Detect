<?php
require_once('../../config/config.php');
require_once('../patchworks.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="<?php echo $patchworks->webRoot; ?>pages/css/bootstrap.css">
</head>
<body>

    <a href="posttotwitter.php?seourl=<?php echo $_GET['seourl'] ?>" id="twitterLink" class="btn btn-primary">Post to TWITTER</a>

</body>
</html>
