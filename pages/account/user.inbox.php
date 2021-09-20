<?php

require_once("../config/config.php");
require_once("../admin/patchworks.php");
require_once("../admin/system/classes/places.cls.php");
require_once("../admin/custom/classes/messages.cls.php");

$PwdTok = (isset($_SESSION['loginToken'])) ? $_SESSION['loginToken'] : '';
$PlaDao = new PlaDAO();
$loggedIn = $PlaDao->loggedIn($PwdTok);

if (!$loggedIn) die('login');

$MsgDao = new MsgDAO();
$inbox = $MsgDao->select(NULL, NULL, 'MESSAGE', $loggedIn->pla_id,NULL, false);

?>

<div class="section">
    <div class="container contentbox">
        <div class="row">
            <div class="col-sm-12">


                <h1 class="heading">My Messages</h1>

                <div class="inbox">

                    <?php
                    for ($i=0; $i<count($inbox);$i++) {
                    ?>

                    <div class="message">

                        <div class="actions">

                            <a href="#" class="btn replybtn">Reply</a>
                            <a href="#" class="btn deletebtn">Delete</a>

                        </div>

                        <div class="title"><?php echo $inbox[$i]['msgttl']; ?></div>
                        <div class="date"><?php echo date("jS M Y H:i", strtotime($inbox[$i]['credat'])); ?></div>

                        <div class="item">ITEM</div>

                        <div class="messagetext">

                            <?php echo nl2br($inbox[$i]['msgtxt']); ?>

                        </div>

                        <div class="replyarea">

                            <form id="contactForm" class="form-vertical" method="post" action="pages/custom/sendmessage.php">
                                <div class="pw-form blueform">
                                    <div class="pw-form-header">
                                        <h3>
                                            Reply</h3>

                                    </div>
                                    <div class="pw-form-content">
                                        <fieldset>
                                            <input type="hidden" name="httpChk" value="http://">
                                            <input type="hidden" name="fwdurl" value="">

                                            <input type="hidden" name="tblnam" value="MESSAGE">
                                            <input type="hidden" name="tbl_id" value="<?php echo $inbox[$i]['ref_id']; ?>">

                                            <input type="hidden" name="refnam" value="USER">
                                            <input type="hidden" name="ref_id" value="<?php echo $loggedIn->pla_id; ?>">

                                            <input type="hidden" name="msgttl" value="Reply from <?php echo $loggedIn->planam; ?>">

                                            <div class="control-group form-group">
                                                <div class="controls">
                                                    <label>Message:</label>
                                                <textarea name="msgtxt"
                                                      data-validation-required-message="Please enter your name."
                                                      required="" class="form-control"></textarea>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>

                                    <div class="form-actions">
                                        <button class="btn btn-primary" type="submit">Send</button>
                                    </div>

                                </div>
                            </form>

                        </div>

                    </div>

                    <?php } ?>

                </div>

            </div>
        </div>
    </div>
</div>