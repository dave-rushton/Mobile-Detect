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
                ?>


                <p><a href=""></a></p>
                
                <h4>Reset Password</h4>
                <p>Please enter a new password for this account.</p>
                <hr>

                <form action="pages/account/account_resetpassword.php" method="post" class="form-vertical" id="forgotForm">
                    <div class="pw-form">

                        <div class="pw-form-content">
                            <fieldset>
                                <input type="hidden" name="pwdtok" value="<?php echo $_GET['pwdtok']; ?>">
                                <div class="form-group">
                                    <label>New Password</label>
                                    <div class="controls">
                                        <input type="password" value="" class="form-control input-block-level required" name="newpassword">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Confirm Password</label>
                                    <div class="controls">
                                        <input type="password" value="" class="form-control input-block-level required" name="confirmpassword">
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>