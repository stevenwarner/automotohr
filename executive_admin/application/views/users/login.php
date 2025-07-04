<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="hr-login-section">
                    <div class="executive-login-screen-heading"><h1><?php echo $page_title; ?></h1></div>
                    <div class="hr-box-wrap">					
                        <div class="hr-login-box">
                            <?php if ($this->session->flashdata('message')) { ?>
                                <div class="flash_error_message">
                                    <div class="alert alert-info alert-dismissible" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <?php echo $this->session->flashdata('message'); ?>
                                    </div>
                                </div>
                            <?php } ?> 
                            <form action="" class="form-horizontal" method="post">
                                <ul>
                                    <li>
                                        <div class="fields-wrapper">
                                            <input name="identity" value="<?php echo set_value('identity'); ?>" class="form-fileds" placeholder="User Name" type="text">
                                            <span class="field-icon"><i class="fa fa-user"></i></span>
                                            <?php echo form_error('identity'); ?>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="fields-wrapper">
                                            <input name="password" value="" class="form-fileds" placeholder="Password" type="password">
                                            <span class="field-icon"><i class="fa fa-unlock"></i></span>
                                            <?php echo form_error('password'); ?>
                                        </div>
                                    </li>
                                    <li>
<!--                                        <div class="fields-wrapper keep-me-loggedin">
                                            <input name="remember" value="on" id="rememberme" type="checkbox">
                                            <label for="rememberme">Keep me Logged in </label>
                                        </div>-->
                                        <div class="fields-wrapper forgot-password">
                                            <a href="<?php echo base_url('dashboard/forgot_password'); ?>">Forgot Password?</a>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="fields-wrapper">
                                            <input name="submit" value="login" class="hr-login-btn" type="submit">
                                        </div>
                                    </li>
                                </ul>
                            </form>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="login-copyright">&copy;<?php echo date('Y') . ' ' . STORE_NAME; ?>. All Rights Reserved.</div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <?php $this->load->view('main/demobuttons'); ?>
        </div>
    </div>
</div> 
<?php echo validation_errors(); ?>
