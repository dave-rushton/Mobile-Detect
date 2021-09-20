<?php

require_once("../config/config.php");
require_once("../admin/patchworks.php");

if (isset($_SESSION['loginToken']) && is_null($_SESSION['loginToken'])) echo '#'.'login error';

?>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">


                <h2 class="heading">LOGIN</h2>
                <hr>
                <div class="box">

                <form action="<?php echo $patchworks->webRoot; ?>pages/account/account_login.php" method="post"
                      class="form-vertical" id="loginForm">
                    <div class="pw-form">

                        <?php
                        if (isset($_GET['error'])) {

                            ?>

                            <div class="alert alert-danger">
                                <p><strong>ERROR:</strong> Your login details were not found.</p>
                                <p>Please try again</p>
                            </div>

                            <?php

                        }
                        ?>

                        <div class="pw-form-content">
                            <fieldset>
                                <input type="hidden" value="http://" name="httpChk">
                                <input type="hidden" name="fwdurl" value="<?php echo (isset($_GET['fwdurl'])) ? $_GET['fwdurl'] : 'useraccount/account'; ?>">

                                <div class="control-group form-group">
                                    <div class="controls">
                                        <label>Your Email:</label>
                                        <input type="text" name="loginEmail" class="form-control" required
                                               data-validation-required-message="Please enter your email.">

                                        <p class="help-block"></p>
                                    </div>
                                </div>
                                <div class="control-group form-group">
                                    <div class="controls">
                                        <label>Password:</label>
                                        <input type="password" name="loginPassword" class="form-control" required
                                               data-validation-required-message="Please enter your password.">

                                        <p class="help-block"></p>
                                    </div>
                                </div>

                                <ul>
                                    <li><a href="useraccount/register">Need to register?</a></li>
                                    <li><a href="useraccount/forgotpassword">Forgotten Password</a></li>
                                </ul>

                            </fieldset>
                        </div>

                        <div class="form-actions text-right">
                            <button type="submit" class="button">Login</button>
                        </div>

                    </div>
                </form>

                </div>

            </div>
        </div>
    </div>
</div>