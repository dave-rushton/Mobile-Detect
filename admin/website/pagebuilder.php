<?php
require_once( "../../config/config.php" );
require_once( "../patchworks.php" );
require_once( "../website/classes/pages.cls.php" );
require_once("../website/classes/page.handler.php");

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

if (isset($_GET['seourl']))
	$seourl = $_GET['seourl'];
else
	die('no page');

$pageHandler = new pageHandler();
$pageHandler->getPage($seourl, $_GET, $_SESSION);
$pageHandler->displayPage();

$PagDAO = new PagDAO;
$pageRec = $PagDAO->select(NULL, $seourl, true);

?>

<link rel="stylesheet" href="<?php echo $patchworks->webRoot?>/pages/standard/css/modal.css">

<div class="transfer-lightbox" data-pag-id="<?php echo $pageRec->pag_id;?>">
    <div class="inner">
        <div class="title">
            Transfer Element to Another Page
            <div class="close">&times;</div>
        </div>
        <div class="body">
            <form id="transfer-element" method="post" action="">

                <input type="hidden" name="pel_id" value="0">
                <div class="menuMarkUpWrapper" data-htmlname="fwdurl">
                    <?php
                        $menuHTML = $pageHandler->getMenu($_GET['seourl'], NULL, NULL, NULL, 'hide menuUL',NULL,false);
                        echo $menuHTML;
                    ?>
                </div>

                <div class="transfer_footer">
                    <div class="btn-wrapper">
                        <input type="submit">
                    </div>
                    <div class="btn-wrapper">
                        <div class="btn" id="transfer-cancel">
                            Cancel
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(function(){

        $('.transfer-lightbox').click(function(e){
            if(e.target.className === "transfer-lightbox"){
                $('.transfer-lightbox').fadeOut(100)
            }
        })
        $('#transfer-cancel,.transfer-lightbox .close').click(function(){
            $('.transfer-lightbox').fadeOut(100);
        })

        $('.transfer-lightbox ul.menuUL').each(function() {

            var selectID = $(this).parent().data('htmlname') + $('ul.menuUL').index( $(this) );

            $(this).attr("id", selectID );

            selectnav(selectID, {
                name: $(this).parent().data('htmlname'),
                label: 'Display Whole Menu',
                nested: true,
                indent: '-'
            });

            $('.selectnav').change();
        });
    })

</script>
<?php include('pbparts/header.php'); ?>
<?php include('pbparts/modulepanel.php'); ?>
<?php include('pbparts/moduleforms.php'); ?>


<input type="hidden" id="EdtPag_ID" value="<?php echo $pageRec->pag_id; ?>" />
<input type="hidden" id="webRoot" value="<?php echo $patchworks->webRoot; ?>"  />
<input type="hidden" id="PagSeoUrl" value="<?php echo $seourl; ?>"  />

<!--<button id="publishButton">PUBLISH</button>-->