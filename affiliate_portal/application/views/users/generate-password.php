<div class="wrapper-outer full-width">
<?php $this->load->view('main/header_logo_public_pages'); ?>
    <div class="main">
        <div class="login-box generate-password">
            <div class="login-box-body full-width mb-4">
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
                    <p class="login-box-msg">Please generate a secure password for your Login ID: <strong class="text-muted"><?php echo ucwords($username); ?></strong></p>
                </div>
                <div class="form-wrp">
                    <form action="" class="form-horizontal" method="post" id="generate-pass">
                        <div class="form-group auto-height">
                            <input name="password" id="password" value="" class="form-control" placeholder="Password" type="password" required="required">
                            <?php echo form_error('password'); ?>
                        </div>
                        <div class="form-group auto-height">
                            <input name="cpassword" id="cpassword" value="" class="form-control" placeholder="Confirm Password" type="password" required="required">
                            <?php echo form_error('cpassword'); ?>
                        </div>
                        <div class="form-group auto-height">
                            <input name="submit" value="Generate Password" class="btn btn-primary btn-block" type="submit">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="login-footer full-width">
        <?php $this->load->view('main/partials/sales_support_contact_info'); ?>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#generate-pass").validate({
            rules: {
                password: {
                    required: true,
                    minlength: 6
                },
                cpassword: {
                    equalTo: "#password",
                    minlength: 6
                }
            },
            messages: {
                password: {
                    required: "Password is required of min 6 digits"
                }
            }
        });
    });
</script>