<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top'); ?>

                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                            <div class="page-header-area margin-top">
                                <span class="page-heading down-arrow"><?php echo $title; ?></span>
                            </div>
                            <div class="job-title-text">                
                                <p>Fields marked with an asterisk (<span>*</span>) are mandatory.</p>
                            </div>
                        </div>
                        <div class="dashboard-conetnt-wrp">
                            <?php echo form_open('', array('id' => 'loginform')); ?>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="create-job-wrap">
                                    <div class="universal-form-style-v2">
                                        <ul>
                                            <li class="form-col-100">
                                                <?php echo form_label('EMAIL ADDRESS <span class="hr-required">*</span>', 'email'); ?>
                                                <?php echo form_input('email', set_value('email', $employer['email']), 'class="invoice-fields"'); ?>
                                                <?php echo form_error('email'); ?>
                                            </li>
                                            <li class="form-col-100">
                                                <?php echo form_label('USERNAME <span class="hr-required">*</span>', 'username'); ?> Username should consist of a minimum of 5 characters.
                                                <?php echo form_input('username', set_value('username', $employer['username']), 'class="invoice-fields"'); ?>
                                                <?php echo form_error('username'); ?>
                                            </li>
                                            <li class="form-col-100">
                                                <label>PASSWORD</label>
                                                <input class="invoice-fields" type="password" name="password" onkeyup="passwordStrength(this.value)">
                                                <div class="password-trength-wrp">							
                                                    <div id="passwordStrength" >
                                                        <div class='pass0 strength0'></div>
                                                        <div class='pass1 strength0'></div>
                                                        <div class='pass1 strength0'></div>
                                                        <div class='pass1 strength0'></div>
                                                    </div>									
                                                    <div class="passwordDescription" id="passwordDescription">Password not entered</div>
                                                </div>	
                                            </li>
                                            <li class="form-col-100">
                                                <label>CONFIRM PASSWORD</label>
                                                <input class="invoice-fields" type="password" name="cpassword">
                                                <?php echo form_error('password'); ?>
                                            </li>
                                            <li class="form-col-100 autoheight">
                                                <input type="hidden" name="id" value="<?php echo $employer['sid']; ?>">
                                                <input type="submit" value="Save" onclick="return validate_form()" class="submit-btn">
                                                <input type="button" value="Cancel" class="submit-btn btn-cancel" onClick="document.location.href = '<?= base_url('employee_management') ?>'" />
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
                <?php $this->load->view($left_navigation); ?>

            </div>
        </div>
    </div>
</div>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
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
                                                    function validate_form() {
                                                        $("#loginform").validate({
                                                            ignore: ":hidden:not(select)",
                                                            rules: {
                                                                username: {
                                                                    required: true,
                                                                    pattern: /^[a-zA-Z0-9\-]+$/
                                                                },
                                                                email: {
                                                                    required: true,
                                                                    email: true
                                                                }
                                                            },
                                                            messages: {
                                                                username: {
                                                                    required: 'Username is required',
                                                                    pattern: 'Please provide valid username'
                                                                },
                                                                email: {
                                                                    required: 'Please provide Valid email'
                                                                }
                                                            },
                                                            submitHandler: function (form) {
                                                                form.submit();
                                                            }
                                                        });
                                                    }
</script>