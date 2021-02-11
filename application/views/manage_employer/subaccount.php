<div class="container">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
            <div class="jobseeker_section">
                <div class="sign-up-section">
                    <h2 class="form-heading">Add sub account</h2>
                    <div class="required_message">Fields marked with an asterisk (<span class="required_icon">*</span>) are mandatory</div>
                </div>
                <div class="registered-user">
                    <form method="post" class="job_seeker_form" action="" enctype="multipart/form-data" id="job_seeker_form">
                        <ul>
                            <div class="separator-div"><h2>Sub Account details</h2></div>
                            <li>
                                <div class="job_seeker_label">First name
                                    <span class="required_icon">&nbsp;*</span>
                                </div>
                                <div class="fields-wrapper  ">
                                    <input type="text" value="<?php echo set_value('first_name'); ?>" class="form-fileds " name="first_name" />
                                    <?php echo form_error('first_name'); ?>
                                </div>
                            </li>
                            <li>
                                <div class="job_seeker_label">Last name
                                    <span class="required_icon">&nbsp;*</span>
                                </div>
                                <div class="fields-wrapper  ">
                                    <input type="text" value="<?php echo set_value('last_name'); ?>" class="form-fileds " name="last_name" />

                                    <?php echo form_error('last_name'); ?>
                                </div>
                            </li>
                            <li>
                                <div class="job_seeker_label">User name
                                    <span class="required_icon">&nbsp;*</span>
                                </div>
                                <div class="fields-wrapper  ">
                                    <input type="text" value="<?php echo set_value('username'); ?>" class="form-fileds " name="username" />
                                    <?php echo form_error('username'); ?>
                                </div>
                            </li>
                            <li>
                                <div class="job_seeker_label">Password
                                    <span class="required_icon">&nbsp;*</span>
                                </div>
                                <div class="fields-wrapper  ">
                                    <input type="password" id="password" name="password" class="inputString form-fileds " />
                                    <input style="margin-top:12px" type="password" placeholder="Confirm Password"  name="passconf" class="form-fileds inputString " />
                                    <?php echo form_error('passconf'); ?>
                                </div>
                            </li>
                            <li>
                                <div class="job_seeker_label">Email
                                    <span class="required_icon">&nbsp;*</span>
                                </div>
                                <div class="fields-wrapper  ">
                                    <input type="text" value="<?php echo set_value('email'); ?>" class="form-fileds " name="email" onblur="checkField($(this), 'email')"/><span class="aMessage" id="am_email"></span>
                                    <?php echo form_error('email'); ?>
                                </div>
                            </li>
                            <li class="btn-responsive">
                                <div class="fields-wrapper register_field">
                                    <input type="submit" class="reg-btn" onclick="validate_form()" value="Add Sub Account" />
                                </div>
                            </li>
                        </ul>
                    </form>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </div>
</div>

<script  language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script  language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script>
                                        function validate_form() {
                                            $("#job_seeker_form").validate({
                                                ignore: ":hidden:not(select)",
                                                rules: {
                                                    first_name: {
                                                        required: true,
                                                        pattern: /^[a-zA-Z0-9\- ]+$/
                                                    },
                                                    last_name: {
                                                        required: true,
                                                        pattern: /^[a-zA-Z0-9\- .]+$/
                                                    },
                                                    username: {
                                                        required: true,
                                                        pattern: /^[a-zA-Z0-9\-]+$/,
                                                        minlength: 5
                                                    },
                                                    password: {
                                                        required: true,
                                                        minlength: 6
                                                    },
                                                    passconf: {
                                                        required: true,
                                                        equalTo: "#password",
                                                        minlength: 6
                                                    },
                                                    email: {
                                                        required: true,
                                                        email: true
                                                    }
                                                },
                                                messages: {
                                                    first_name: {
                                                        required: 'First name is required',
                                                        pattern: 'Letters, numbers, and dashes only please'
                                                    },
                                                    last_name: {
                                                        required: 'Last name is required',
                                                        pattern: 'Letters, numbers, and dashes only please'
                                                    },
                                                    username: {
                                                        required: 'Username is required',
                                                        pattern: 'Please provide valid username'
                                                    },
                                                    password: {
                                                        required: 'Password is required'
                                                    },
                                                    passconf: {
                                                        required: 'Confirm password does not match'
                                                    },
                                                    email: {
                                                        required: 'Please provide valid email'
                                                    }
                                                },
                                                submitHandler: function (form) {
                                                    form.submit();
                                                }
                                            });
                                        }
</script>