<div class="wrapper-outer full-width">
<?php $this->load->view('main/header_logo_public_pages'); ?>
    <div class="main">
        <div class="login-box">
            <div class="login-box-body full-width">
                <?php if ($this->session->flashdata('message')) { ?>
                    <div class="flash_error_message">
                        <div class="alert alert-info alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <?php echo $this->session->flashdata('message'); ?>
                        </div>
                    </div>
                <?php } ?>
            <!--<p class="login-box-msg">Sign in to start your session</p>-->
                <div class="login-logo">
                    <?php echo $page_title; ?>
                </div>
                <div class="form-wrp">
                    <form action="" class="form-horizontal" method="post">
                        <div class="form-group auto-height">
                            <input name="identity" value="<?php echo set_value('identity'); ?>" class="form-control" placeholder="User Name" type="text">
                            <?php echo form_error('identity'); ?>
                        </div>
                        <div class="form-group auto-height">
                            <input name="password" value="" class="form-control" placeholder="Password" type="password">
                            <?php echo form_error('password'); ?>
                        </div>
                        <div class="form-group auto-height">
                            <div class="btn-panel">
                                <input name="submit" value="Login" class="btn btn-primary btn-block btn-flat" type="submit">
                            </div>
                        </div>
                    </form>
                    <a href="<?php echo base_url('dashboard/forgot_password'); ?>">Forgot Password?</a><br>
                    <a href="https://www.automotohr.com/can-we-send-you-a-check-every-month" target="_blank">Not an <?php echo STORE_NAME; ?> Affiliate Yet?</a>
                </div>
            </div>
            <div class="login-copyright text-center full-width mt-1">Copyright &copy; <?php echo date('Y') . ' ' . STORE_NAME; ?>. All Rights Reserved.</div>
            <div class="clear"></div>
        </div>
    </div>
    <div class="login-footer full-width">
        <?php $this->load->view('main/partials/sales_support_contact_info'); ?>
    </div>
</div>