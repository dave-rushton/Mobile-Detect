<?php 

require_once('../../config/config.php');
require_once('../patchworks.php'); 
require_once('../system/classes/messages.cls.php');

$userAuth = new AuthDAO();
$loggedIn = $userAuth->loggedIn($_SESSION['s_log_id']);
if ($loggedIn == 0) header('location: ../login.php');

$MsgDao = new MsgDAO();
$messages = $MsgDao->select(NULL, NULL, 'FORM', NULL, 0, false);

if (count($messages) > 0) {

?>

<ul class="tiles tiles-center tiles-small nomargin">
	<li class="orange">
		<span class="label label-inverse"><?php echo count($messages); ?></span>
		<a href="website/webcontacts.php"><span><i class="icon-comments"></i></span><span class="name">Contact</span></a>
	</li>
</ul>

<?php } ?>