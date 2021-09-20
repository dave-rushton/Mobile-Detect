<div id="modulePanel" class="preview topRight">

    <div class="modulePanelHeader">
        <a href="#" id="showModules"><i class="icon icon-upload"></i></a>
        <a href="#" id="moveModules"><i class="icon icon-refresh"></i></a>
    </div>

    <div class="moduleListing">

        <a href="#" class="moduleHeader"><i class="icon icon-folder-close"></i>Standard</a>
        <div class="moduleList">
            <div class="module" id="column12Element" data-incfil="pages/elements/website/column12.php" data-markup="column12Form">
                <i class="icon-tasks"></i> Full Width
            </div>
        </div>
        <a href="#" class="moduleHeader"><i class="icon icon-folder-close"></i>Website</a>
        <div class="moduleList">


            <div class="module" id="column66Element" data-incfil="pages/elements/website/column66.php" data-markup="column66Form">
                <i class="icon-tasks"></i> 2 Column Width
            </div>
            <div class="module" id="column444Element" data-incfil="pages/elements/website/column444.php" data-markup="column444Form">
                <i class="icon-tasks"></i> 3 Column Width
            </div>
            <div class="module" id="column3333Element" data-incfil="pages/elements/website/column3333.php" data-markup="column3333Form">
                <i class="icon-tasks"></i> 4 Column Width
            </div>
            <div class="module" id="webFormElement" data-incfil="pages/elements/website/websiteform.php" data-markup="websiteFormForm">
                <i class="icon-tasks"></i> Website Forms
            </div>
            <div class="module" id="webFormResultElement" data-incfil="pages/elements/website/webformresult.php" data-markup="webResultForm">
                <i class="icon-tasks"></i> Website Form Result
            </div>

            <div class="module" id="genericContent" data-incfil="pages/elements/website/genericcontent.php" data-markup="genericContentForm">
                <i class="icon-font"></i> Generic Content
            </div>
            <div class="module" id="menuElement" data-incfil="pages/elements/website/menu.php" data-markup="menuForm">
                <i class="icon-sitemap"></i> Menu Element
            </div>
            <div class="module" id="breadCrumbElement" data-incfil="pages/elements/website/breadcrumb.php">
                <i class="icon-sitemap"></i> Bread Crumb Element
            </div>



            <div class="module" id="siteSearchFormElement" data-incfil="pages/elements/website/searchform.php" data-markup="websiteSearchFormForm">
                <i class="icon-search"></i> Search Form
            </div>
            <div class="module" id="siteSearchResultsElement" data-markup="siteSearchResultsForm" data-incfil="pages/elements/website/searchresults.php">
                <i class="icon-search"></i> Search Results
            </div>
            <div class="module" id="employeesElement" data-incfil="pages/elements/website/employees.php">
                <i class="icon-user"></i> Employees
            </div>

        </div>

        <a href="#" class="moduleHeader"><i class="icon icon-folder-close"></i>Media</a>
        <div class="moduleList">
            <div class="module" id="imageElement" data-incfil="pages/elements/media/image.php" data-markup="imageForm">
                <i class="icon-picture"></i> Image Element
            </div>
            <div class="module" id="flexsliderElement" data-incfil="pages/elements/media/flexslider-gallery.php" data-markup="flexsliderForm">
                <i class="icon-refresh"></i> Image Slider
            </div>
            <div class="module" id="autoParallaxElement" data-incfil="pages/elements/media/parallaxsection.php" data-markup="parallaxImageForm">
                <i class="icon-tasks"></i> Parallax Section
            </div>
            <div class="module" id="kenBurnsElement" data-incfil="pages/elements/media/kenburns.php" data-markup="kenBurnsForm">
                <i class="icon-tasks"></i> Ken Burns
            </div>
            <div class="module" id="galleriesElement" data-incfil="pages/elements/media/gallery.php" data-markup="galleriesForm">
                <i class="icon-picture"></i> Gallery
            </div>
            <div class="module" id="youtubeElement" data-incfil="pages/elements/media/youtube.php" data-markup="youtubeForm">
                <i class="icon-video"></i> YouTube Video
            </div>
            <div class="module" id="libraryListingElement" data-incfil="pages/elements/media/libraries.php" data-markup="libraryListForm">
                <i class="icon-download-alt"></i> Downloads Listing
            </div>
            <div class="module" id="downloadLinkElement" data-incfil="pages/elements/media/downloadlink.php" data-markup="downloadLinkForm">
                <i class="icon-pencil"></i> Download Item
            </div>
        </div>


        <a href="#" class="moduleHeader"><i class="icon icon-folder-close"></i>Locations</a>
        <div class="moduleList">
            <div class="module" id="locationsElement" data-incfil="pages/elements/locations/locations.php" data-markup="locationsForm">
                <i class="icon-map-marker"></i> Location Mini Map
            </div>
            <div class="module" id="locationMapElement" data-incfil="pages/elements/locations/locationmap.php" data-markup="locationMapForm">
                <i class="icon-map-marker"></i> Location Interactive Map
            </div>
            <div class="module" id="locationListElement" data-incfil="pages/elements/locations/locationlist.php" data-markup="locationListForm">
                <i class="icon-map-marker"></i> Location List
            </div>
        </div>

        <?php
        if (file_exists('../../pages/standard/modulepanel.php')) {
            include('../../pages/standard/modulepanel.php');
        }
        if (file_exists('../../pages/custom/modulepanel.php')) {
            include('../../pages/custom/modulepanel.php');
        }


        ?>

    </div>

</div>

<!--<div id="templatePanel" class="preview topRight">-->
<!---->
<!--    <div class="moduleListing">-->
<!---->
<!--        <a href="#" class="moduleHeader"><i class="icon icon-folder-close"></i>Website</a>-->
<!--        <div class="moduleList">-->
<!---->
<!--            <div class="module" id="column12Element" data-incfil="pages/elements/website/column12.php" data-markup="column12Form">-->
<!--                <i class="icon-tasks"></i> Full Width-->
<!--            </div>-->
<!---->
<!--        </div>-->
<!---->
<!--    </div>-->
<!---->
<!--</div>-->