<?php
require_once('/admin/website/classes/pagecontent.cls.php');
$PgcDao = new PgcDAO();
$contentRec = $PgcDao->select(1, NULL, NULL, true);


?>
<footer>
    <div class="container">
        <?php
            echo htmlspecialchars_decode($contentRec->pgctxt);
        ?>
        <p>
            &copy; 2019 E.A Tailby Ltd.
        </p>
    </div>
</footer>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css" integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ==" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js" integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw==" crossorigin=""></script>
<script src="pages/js/map.js"></script>


<script type="text/javascript">/* First CSS File */
    var giftofspeed = document.createElement('link');
    giftofspeed.rel = 'stylesheet';
    giftofspeed.href = 'https://fonts.googleapis.com/css?family=Open+Sans:300|Quicksand|Anton';
    giftofspeed.type = 'text/css';
    var godefer = document.getElementsByTagName('link')[0];
    godefer.parentNode.insertBefore(giftofspeed, godefer);
</script>
<!--<link rel="stylesheet" type="text/css" href="pages/css/bootstrap.css">-->
<link rel="stylesheet" type="text/css" href="pages/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="pages/css/style.css?v=<?php echo $patchworks->fileVersion;?>">
<script src="pages/js/parsley.min.js"></script>
<script src="pages/js/scripts-cms.js?v=<?php echo $patchworks->fileVersion;?>" defer></script>
<script src="pages/js/bootstrap.min.js"></script>
<!--PRODUCTS-->
<link rel="stylesheet" type="text/css" href="pages/css/products.css?v=<?php echo $patchworks->fileVersion;?>">
<!--GALLERY-->
<link rel="stylesheet" type="text/css" href="pages/css/magnific-popup.css">
<script src="pages/js/jquery.magnific-popup.min.js"></script>
<script src="pages/js/gallery-cms.js?v=<?php echo $patchworks->fileVersion;?>" defer></script>
<!--FLEXSLIDER-->
<link rel="stylesheet" type="text/css" href="pages/css/flexslider.css?v=<?php echo $patchworks->fileVersion;?>">
<script src="pages/js/jquery.flexslider.js" defer></script>
<script src="pages/js/flexslider-cms.js?v=<?php echo $patchworks->fileVersion;?>" defer></script>
<script src="pages/js/custom.js?v=<?php echo $patchworks->fileVersion;?>" defer></script>

<link rel="stylesheet" type="text/css" href="pages/css/slinky.min.css?v=<?php echo $patchworks->fileVersion;?>">
<script src="pages/js/slinky.min.js" defer></script>

<!--MAPS-->
<script type="text/javascript">

    var giftofspeed = document.createElement('script');
    giftofspeed.src = 'https://maps.googleapis.com/maps/api/js?key=AIzaSyC36oRHRU2COINS002mdpZmk_sp4ASAR2I';
    giftofspeed.type = 'text/javascript';
    giftofspeed.async = true;

    giftofspeed.onload = function () {

        var giftofspeed = document.createElement('script');
        giftofspeed.src = 'pages/js/infobox.js';
        giftofspeed.type = 'text/javascript';
        giftofspeed.async = true;

        document.getElementsByTagName('head')[0].appendChild(giftofspeed);

        var giftofspeed = document.createElement('script');
        giftofspeed.src = 'pages/js/cms-maps.js';
        giftofspeed.type = 'text/javascript';
        giftofspeed.async = true;

        document.getElementsByTagName('head')[0].appendChild(giftofspeed);

        var giftofspeed = document.createElement('script');
        giftofspeed.src = 'pages/js/cms-locationmap.js';
        giftofspeed.type = 'text/javascript';
        giftofspeed.async = true;

        document.getElementsByTagName('head')[0].appendChild(giftofspeed);

    };

    document.getElementsByTagName('head')[0].appendChild(giftofspeed);

</script>
<script src="pages/js/js.cookie.js"></script>
<script>
    if (Cookies.get('cookieaccept') === undefined) {
        document.getElementById('cookiepolicy').style.display = 'block';
    } else {
        document.getElementById('cookiepolicy').style.display = 'none';
    }
    function setCookie() {
        Cookies.set('cookieaccept', '1');
        document.getElementById('cookiepolicy').style.display = 'none';
    }
</script>
<!--Google Captcha-->
<script src='https://www.google.com/recaptcha/api.js'></script>

<script src="pages/js/jquery.slimscroll.min.js" defer></script>
<script src="pages/js/script.js?v=<?php echo $patchworks->fileVersion;?>" defer></script>