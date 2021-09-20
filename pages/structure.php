<?php

require_once("../config/config.php");
require_once("../admin/patchworks.php");
require_once("../admin/website/classes/page.handler.php");


$pageHandler = new pageHandler();
$pageHandler->getPage($_GET['seourl'], $_GET, $_POST);
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="icon" href="favicon.ico" type="image/x-icon"/>

    <title><?php echo $pageHandler->PagTtl; ?></title>

    <?php echo $pageHandler->googleAnalytics(); ?>

    <base href="<?php echo $patchworks->webRoot; ?>"/>
    <meta name="keywords" content="<?php echo $pageHandler->KeyWrd; ?>"/>
    <meta name="description" content="<?php echo $pageHandler->PagDsc; ?>"/>

    <?php
    echo $pageHandler->critialCSS();
    echo $pageHandler->getTopJS($_GET['seourl']);
    ?>

    <script src="pages/js/jquery.js"></script>

</head>

<body id="homepage">

<div id="watermarkWrapper">
    <div class="watermark"></div>
</div>



<?php
include('webparts/page.header.php');
?>

<div id="websiteContent">
    <?php echo $pageHandler->displayStructure(); ?>
</div>


<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">

                <div class="homeslide">

                    <div class="content">
                        <h1>
                            <span class="slabtext">Delivering Excellence in Hospitality</span>
                        </h1>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

<div class="section headingfade">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h3>
                    <span class="slabtext">Quality Products For a Quality Stay</span>
                </h3>
            </div>
        </div>
    </div>
</div>


<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">

                <h2>Hospitality</h2>
                <h3>Exquisite Bedrooms</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Atque blanditiis consectetur, cumque delectus, ea eaque eos eveniet excepturi illo laborum, mollitia pariatur quos tempora? Corporis dolorum ducimus ex fugiat maxime mollitia odit pariatur quas quia vero. Animi assumenda beatae consectetur cum fugit iste minima, nihil vel. Ab accusamus aliquam asperiores at atque blanditiis commodi consequatur cupiditate doloremque, dolores ea earum eos et eum fugiat illo incidunt laborum maxime neque porro possimus quae quia quo recusandae saepe soluta sunt suscipit tempore vel voluptate! Adipisci cumque dolorum error laborum odio qui quia!</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Culpa delectus distinctio eos et facere molestiae pariatur porro, provident quisquam sapiente. Ab deleniti pariatur rerum. Consequuntur deserunt earum error expedita hic id illo ipsum placeat, quos recusandae rerum sunt tenetur velit? Consectetur cupiditate dolores earum enim error fugiat fugit itaque, iure molestiae natus neque numquam odio perspiciatis recusandae reprehenderit sed velit!</p>

            </div>
            <div class="col-sm-6">


            </div>
        </div>
    </div>
</div>


<div class="section sectionfade">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h2>Products</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">

                <a href="#" class="linkbutton">
                    <span class="imagewrapper">
                        <span class="image"></span>
                    </span>
                    <span class="title">
                        Bedroom
                    </span>
                </a>
            </div>
            <div class="col-sm-3">

                <a href="#" class="linkbutton">
                    <span class="imagewrapper">
                        <span class="image"></span>
                    </span>
                    <span class="title">
                        Bathroom
                    </span>
                </a>
            </div>
            <div class="col-sm-3">

                <a href="#" class="linkbutton">
                    <span class="imagewrapper">
                        <span class="image"></span>
                    </span>
                    <span class="title">
                        Public Area
                    </span>
                </a>
            </div>
            <div class="col-sm-3">
                <a href="#" class="linkbutton">
                    <span class="imagewrapper">
                        <span class="image"></span>
                    </span>
                    <span class="title">
                        Caddie
                    </span>
                </a>
            </div>
        </div>
    </div>
</div>



<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">

                <h2>Product Innovation</h2>
                <h4>Innovation & Dependability</h4>
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab accusantium atque beatae commodi cumque deserunt dolor doloremque excepturi hic impedit incidunt maxime mollitia necessitatibus nesciunt obcaecati, sit unde vitae voluptates. Adipisci, aut commodi delectus dicta, dolor earum ex incidunt, necessitatibus officiis placeat vero vitae voluptatum. At culpa dolorum incidunt ipsum maiores mollitia quia. Architecto ea enim id repellat voluptas? Cumque dolor dolorum eaque, incidunt molestias repudiandae saepe voluptatem! At aut consectetur dolore dolores error est ipsa quos sint ullam voluptatibus!
                </p>



            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <h3>Title</h3>
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Deleniti enim exercitationem in laborum nam nisi sunt? Accusamus deserunt dicta, eos error eum facilis illo illum minus modi, mollitia nam nihil officia perferendis quam quasi quia recusandae saepe suscipit, totam voluptas.
                </p>
            </div>
            <div class="col-sm-4">
                <h3>Title</h3>
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Deleniti enim exercitationem in laborum nam nisi sunt? Accusamus deserunt dicta, eos error eum facilis illo illum minus modi, mollitia nam nihil officia perferendis quam quasi quia recusandae saepe suscipit, totam voluptas.
                </p>
            </div>
            <div class="col-sm-4">
                <h3>Title</h3>
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Deleniti enim exercitationem in laborum nam nisi sunt? Accusamus deserunt dicta, eos error eum facilis illo illum minus modi, mollitia nam nihil officia perferendis quam quasi quia recusandae saepe suscipit, totam voluptas.
                </p>
            </div>
        </div>
    </div>
</div>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">

                <h2>Contact</h2>
                <h3>Contact</h3>
                <p>
                    Decotel Limited <br>
                    Sullivan Way <br>
                    Loughborough <br>
                    Leicestershire <br>
                    LE11 5QS
                </p>
                <p>
                    Registered in England No. 00412523
                </p>
                <p>
                    Tel: 01509 264422 <br>
                    Fax: 01509 265452
                </p>

            </div>
        </div>
    </div>
</div>

<?php
include('webparts/page.footer.php');
echo $pageHandler->getBotJS($_GET['seourl']);
?>

</body>
</html>