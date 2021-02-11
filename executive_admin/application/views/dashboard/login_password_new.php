<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <?php //$this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="btn-panel">
                    <a href="<?php echo $back_url; ?>" class="btn btn-success"><i class="fa fa-angle-left"></i> Dashboard</a>
                    <a href="<?php echo $back_url; ?>/my_profile" class="btn btn-success"><i class="fa fa-pencil"></i> My Profile</a>
                </div>
                <div class="page-header">
                    <h2 class="section-ttile"><?php echo $page_title; ?></h2>
                </div>
                <div class="form-wrp">
                    <form id="form_applicant_information" method="POST" enctype="multipart/form-data" action="<?php echo current_url(); ?>" autocomplete="on">
                        <input type="hidden" id="perform_action" name="perform_action" value="update_profile" />
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group">
                                    <label for="username">Username: *</label>
                                    <input type="text" name="username" value="<?php echo set_value(username, $executive_user['username']); ?>" class="form-control valid" readonly id="username" required="required">
                                    <?php echo form_error('username'); ?>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group">
                                    <label for="password">Password: *</label>
                                    <input type="password" name="password" class="form-control valid" id="password" required="required" onkeyup="passwordStrength(this.value)" autocomplete="off">
                                    <div class="password-trength-wrp">
                                        <div id="passwordStrength" >
                                            <div class='pass0 strength0'></div>
                                            <div class='pass1 strength0'></div>
                                            <div class='pass1 strength0'></div>
                                            <div class='pass1 strength0'></div>
                                        </div>
                                        <div class="passwordDescription" id="passwordDescription">Password not entered</div>
                                    </div>
                                    <?php echo form_error('password');?>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group">
                                    <label for="cpassword">Confirm Password: *</label>
                                    <input type="password" name="cpassword" class="form-control valid" id="cpassword" required="required" autocomplete="off">
                                    <?php echo form_error('cpassword'); ?>
                                </div>
                            </div>
                        </div>

                        <div class="btn-wrp full-width text-right">
                            <div class="form-group">
                                <a class="btn btn-black margin-right" href="<?php echo $back_url; ?>/my_profile">Cancel</a>
                                <input id="form" class="btn btn-success" value="Update" type="submit">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url() ?>assets/alertifyjs/alertify.min.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>assets/alertifyjs/css/alertify.min.css"/>
<link rel="stylesheet" href="<?= base_url() ?>assets/alertifyjs/css/themes/default.min.css"/>
<script type="text/javascript">


    function passwordStrength(password) {
        var desc = new Array();
        if (password.length == 0) {
            desc[0] = "Password Not Entered";
        } else {
            desc[0] = "Very Weak";
        }
        desc[1] = "Very Weak";
        desc[2] = "Not secure enough";
        desc[3] = "Fair";
        desc[4] = "Strong";
        desc[5] = "Very Strong";

        var toggle_class = new Array();
        if (password.length == 0) {
            toggle_class[0] = "<div class='pass0 strength0'></div><div class='pass1 strength0'></div><div class='pass1 strength0'></div><div class='pass1 strength0'></div>";
        } else {
            toggle_class[0] = "<div class='pass0 strength1'></div><div class='pass1 strength1'></div><div class='pass1 strength1'></div><div class='pass1 strength0'></div>";
        }
        toggle_class[1] = "<div class='pass0 strength1'></div><div class='pass1 strength1'></div><div class='pass1 strength1'></div><div class='pass1 strength0'></div>";
        toggle_class[2] = "<div class='pass0 strength2'></div><div class='pass1 strength2'></div><div class='pass1 strength0'></div><div class='pass1 strength0'></div>";
        toggle_class[3] = "<div class='pass0 strength3'></div><div class='pass1 strength3'></div><div class='pass1 strength0'></div><div class='pass1 strength0'></div>";
        toggle_class[4] = "<div class='pass0 strength4'></div><div class='pass1 strength4'></div><div class='pass1 strength4'></div><div class='pass1 strength0'></div>";
        toggle_class[5] = "<div class='pass0 strength5'></div><div class='pass1 strength5'></div><div class='pass1 strength5'></div><div class='pass1 strength5'></div>";

        var score = 0;
        //if password bigger than 6 give 1 point
        if (password.length > 6)
            score++;

        //if password has both lower and uppercase characters give 1 point
        if ((password.match(/[a-z]/)) && (password.match(/[A-Z]/)))
            score++;

        //if password has at least one number give 1 point
        if (password.match(/\d+/))
            score++;

        //if password has at least one special caracther give 1 point
        if (password.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/))
            score++;

        //if password bigger than 12 give another 1 point
        if (password.length > 10)
            score++;

        document.getElementById("passwordDescription").innerHTML = desc[score];
        document.getElementById("passwordStrength").innerHTML = toggle_class[score];

    }
    $(function () {
        $.validator.setDefaults({
            debug: true,
            success: "valid"
        });

        $("#form_applicant_information").validate({
            ignore: ":hidden:not(select)",
            rules: {
                password: {
                    required: true,
                    minlength: 6
                },
                cpassword: {
                    required: true,
                    minlength: 6,
                    equalTo: "#password"
                }
            },
            messages: {
                password: {
                    required: 'Password is required',
                    minlength: 'Password should be 6 digits'
                },
                cpassword: {
                    required: 'Confirm password is required',
                    minlength: 'Confirm Password should be 6 digits',
                    equalTo: "Password doesn't matched"
                }
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
    });
</script>