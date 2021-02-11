<div id="messageBox">
    <div id="content">
        <div class="row">
            <div  class="col-lg-12 col-md-12 col-xs-12 col-sm-12 login-page-bg">
                <?php if (!$this->session->userdata('logged_in')) { ?>
                    <div class="login-section ">
                        <div class="registered-user">
                            <h2 class="form-heading">Already Registered? Login Here.</h2>
                            <?php if($this->session->flashdata('message')){ ?>
                                <div class="flash_error_message">
                                    <div class="alert alert-info alert-dismissible" role="alert">
                                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                      <?php echo $this->session->flashdata('message');?>
                                    </div>
                                </div>
                            <?php } ?>
                            <form action="" method="post" id="loginForm" class="ng-pristine ng-valid">
                                <input type="hidden" name="return_url" value="{$return_url}" />
                                <input type="hidden" name="action" value="login" />
                                <ul>
                                    <li>
                                        <div class="fields-wrapper">
                                            <input class="form-fileds" value="<?php echo set_value('username'); ?>" type="text" id="email" name="username" placeholder="Username">
                                            <span class="field-icon"><i class="fa fa-user"></i></span>
                                            <?php echo form_error('username'); ?>

                                        </div>
                                    </li>
                                    <li>
                                        <div class="fields-wrapper">
                                            <input class="form-fileds"  type="password" id="password" name="password" placeholder="Password">
                                            <span class="field-icon"><i class="fa fa-unlock"></i></span>
                                            <?php echo form_error('password'); ?>

                                        </div>
                                    </li>
<!--                                    <li>
                                        <div class="fields-wrapper">
                                            <label class="control control--checkbox">Keep me signed in
                                                <input type="checkbox" name="keep" id="remember" >
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </li>-->
                                    <li>
                                        <div class="fields-wrapper">
                                            <input class="login-btn" type="submit" value="Login">
                                            <div class="forgot-password">
                                                <a href="<?php echo site_url('forgot_password');?>">Forgot password?</a>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </form>
                        </div>
                        <div class="separator">
                            <div class="separator-inner"><span>or</span></div>
                        </div>
                        <div class="sign-up-section">
                            <h2 class="form-heading">Executive admin login here </h2>
                            <a class="login-btn" href="<?php echo STORE_PROTOCOL_SSL.$_SERVER['HTTP_HOST'].str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']).'executive_admin'; ?>">Executive Admin Login</a>
                        </div>
                        <div class="separator">
                            <div class="separator-inner"><span>or</span></div>
                        </div>
                        <div class="sign-up-section">
                            <h2 class="form-heading">Don't Have An <?php echo STORE_NAME; ?> Account Yet? No Problem Get Yours Today.</h2>
                            <a class="login-btn" href="<?php echo base_url('schedule_your_free_demo'); ?>">Get Your Account</a>
                        </div>
                        <div class="clear"></div>
                    </div>
                <?php } else { ?>
                    <div>
                        <div class="login-section ">
<!--                            <div class="registered-user">
                                <h2 class="form-heading">Already Registered? Login Here.</h2>
                                <p class="error_message"><i class="fa fa-exclamation-circle"></i>
                                    You are currently logged in as <b><?php //echo $_SESSION["logged_in"]["employer_detail"]["username"]; ?></b><br>
                                </p>
                            </div>-->
                        <div class="sign-up-section">
                            <h2 class="form-heading">Already Logged in</h2>
                            <p class="error_message"><i class="fa fa-exclamation-circle"></i>
                                    You are currently logged in as <b><?php echo $_SESSION["logged_in"]["employer_detail"]["username"]; ?></b><br>
                                </p>
                            <a class="login-btn" href="<?php echo base_url('dashboard'); ?>">Goto Dashboard</a>
                        </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <?php $this->load->view('main/demobuttons'); ?>
        </div>		
    </div>
</div>
<script type="text/javascript">
    $('input').keydown(function(e) {
        if (e.keyCode == 13) {
            $(this).closest('form').submit();
        }
    });
</script>