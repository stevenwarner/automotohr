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
                    <p class="login-box-msg">Please enter your new password:</p>
                </div>

                <div class="form-wrp">
                    <form action="" id="form_update_new_password" class="form-horizontal" method="post">
                        <div class="form-group auto-height">
                            <input name="password" id="password" value="" class="form-control" placeholder="New Password" type="password">
                            <?php echo form_error('password'); ?>
                        </div>
                        <div class="form-group auto-height">
                            <input name="retypepassword" id="retypepassword" value="" class="form-control" placeholder="Retype New Password" type="password">
                            <?php echo form_error('retypepassword'); ?>
                        </div>
                        <div class="form-group auto-height">
                            <div class="btn-panel">
                                <input id="update_new_password_submit" class="btn btn-primary btn-block btn-flat" type="button" value="Submit">
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
<script  language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script  language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script type="text/javascript">
    $('#update_new_password_submit').click(function () {
        var pass = $('#password').val();
        var re_pass = $('#retypepassword').val();
        if (pass == re_pass) {
            $("#form_update_new_password").submit();
        }
    });
    
    $("#form_update_new_password").validate({
        ignore: [],
        rules: {
            password: {
                minlength: 6
            },
            retypepassword: {
                minlength: 6,
                equalTo: "#password"
            }
        },
        messages: {
            password: {
                minlength: 'Password should be 6 digits'
            },
            retypepassword: {
                minlength: 'Confirm Password should be 6 digits',
                equalTo: "Password doesn't match"
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });
</script>