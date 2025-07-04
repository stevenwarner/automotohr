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

                <div class="login-logo">
                    <?php echo $page_title; ?>
                    <p class="login-box-msg">Please, enter your email in the field below and we'll send you a link to a page where you can change your password:</p>
                </div>
                <div class="form-wrp">
                    <form action="" class="form-horizontal" method="post">
                        <div class="form-group auto-height">
                            <input name="email" value="" class="form-control" placeholder="Email" type="email">
                            <?php echo form_error('email'); ?>
                        </div>
                        <div class="form-group auto-height">
                            <div class="btn-panel">
                                <input name="submit" value="Submit" class="btn btn-primary btn-block btn-flat" type="submit">
                                <a href="<?php echo base_url('login'); ?>" class="btn btn-danger btn-block btn-flat">Cancel</a>
                            </div>
                        </div>
                    </form>
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
