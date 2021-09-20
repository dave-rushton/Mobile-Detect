<?php

require_once("../config/config.php");
require_once("../admin/patchworks.php");

?>
<div class="section">
    <div class="container contentbox">
        <div class="row">
            <div class="col-sm-12">

                <?php

                if (isset($_GET['result']) && $_GET['result'] == 'noemail') {
                    ?>

                    <div class="alert alert-danger">
                        <p><strong>Error</strong></p>
                        <p>Your email account cannot be found on our system, please register <a href="useraccount/register">here</a></p>
                    </div>

                    <?php
                }

                if (isset($_GET['result']) && $_GET['result'] == 'error') {
                    ?>

                    <div class="alert alert-danger">
                        <p><strong>Error</strong></p>
                        <p>Sorry there was a problem sending the reset email, please try again later.</p>
                    </div>

                    <?php
                }

                if (isset($_GET['result']) && $_GET['result'] == 'emailsent') {
                    ?>

                    <div class="alert alert-success">
                        <p><strong>Success</strong></p>
                        <p>An email has been sent containing a link to reset your password.</p>
                    </div>

                    <?php
                }
                ?>
                
                <h2 class="heading">Forgotten Password</h2>
                <hr>
                <div class="box">
                <ul class="accountmenu"><li><a href="useraccount/login">Go to login</a></li></ul>

                <hr>

                <form action="pages/account/account_sendpassword.php" method="post" class="form-vertical" id="forgotForm">
                    <div class="pw-form">

                        <div class="pw-form-content">
                            <fieldset>
                                <div class="form-group">
                                    <label class="form-label">Your Email</label>
                                    <div class="controls">
                                        <input type="text" value="" class="form-control input-block-level required" name="accountemail">
                                    </div>
                                </div>


                            </fieldset>
                        </div>
                        <div class="form-actions text-right">
                            <button type="submit" class="button">Submit</button>
                        </div>
                    </div>
                </form>

                </div>

            </div>
        </div>
    </div>
</div>