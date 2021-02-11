
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php echo $title; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_onboarding') ?>/css/style.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_onboarding') ?>/css/style-tabs.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_onboarding') ?>/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_onboarding') ?>/css/font-awesome.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_onboarding') ?>/css/responsive.css">
        <script type="text/javascript" src="<?php echo base_url('assets/employee_onboarding') ?>/js/jquery-1.11.3.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url('assets/employee_onboarding') ?>/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url('assets/employee_onboarding') ?>/js/functions.js"></script>
    </head>
    <body class="login-page">
        <!-- Wrapper Start -->
        <div class="wrapper">
            <div class="top-title-area">
                <h2>Welcome to <?php echo ucfirst($companyDetail['CompanyName']); ?></h2>
                <p>Congrats, you ‘ve been sent an offer! Create an account to view it.</p>
            </div>
            <div class="clear"></div>
            <!-- Login Area Start -->	
            <div class="login-area">
                <h1>Create Login Credentials</h1>
                <div class="universal-form-style">
                    <ul>
                        <form method="POST" id="onboarding_register">
                            <li>
                                <label>Personal Email<span class="staric">*</span></label>
                                <input class="form-filed readonly-field" type="email" placeholder="<?php echo $employerDetail['email']; ?>" readonly>
                            </li>
                            <li>
                                <label>User Name<span class="staric">*</span></label>
                                <input class="form-filed"  name="username" placeholder="username" type="text">
                                <?php echo form_error('username'); ?>
                            </li>
                            <li>
                                <label>Create Password<span class="staric">*</span></label>
                                <input class="form-filed"  type="password" name="password" id="password" placeholder="password">
                                <?php echo form_error('password'); ?>
                            </li>
                            <li>
                                <label>Confirm Password<span class="staric">*</span></label>
                                <input class="form-filed" type="password"  name="c_password" placeholder="confirm password">
                                <?php echo form_error('c_password'); ?>
                            </li>
                            <li class="autoheight">
                                <div class="checkbox-field">
                                    <figure>
                                        <input type="checkbox" name="checkbox"  checked id="agree-with" required="">
                                    </figure>
                                    <div class="text">
                                        <label for="agree-with"> I agree with<a href="javascript:;"><?php echo STORE_NAME; ?> Terms</a> &<a href="javascript:;">Privacy Policy</a>.</label> 
                                    </div>
                                </div>
                            </li>
                            <li class="autoheight">
                                <div class="btn-panel">
                                    <input type="submit" value="Create Account & View Offer" onclick="validate_form();" class="submit-btn">
                                </div>
                            </li>
                        </form>
                    </ul>
                </div>
                <div class="clear"></div>
            </div>
            <!-- Login Area End -->
            <div class="clear"></div>
        </div>
        <!-- Wrapper End -->
    </body>
</html>

<script  language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script  language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script>

                                        function validate_form() {
                                            $("#onboarding_register").validate({
                                                ignore: ":hidden:not(select)",
                                                rules: {
                                                    username: {
                                                        required: true,
                                                        pattern: /^[a-zA-Z0-9\-]+$/
                                                    },
                                                    password: {
                                                        required: true,
                                                    },
                                                    c_password: {
                                                        required: true,
                                                        equalTo: "#password"
                                                    },
                                                    email: {
                                                        required: true,
                                                        email: true
                                                    },
                                                    checkbox: {
                                                        required: true,
                                                    }
                                                },
                                                messages: {
                                                    username: {
                                                        required: 'Username is required',
                                                        pattern: 'Please provide valid username'
                                                    },
                                                    password: {
                                                        required: 'Password is required'
                                                    },
                                                    c_password: {
                                                        required: 'Confirm Password does not match'
                                                    },
                                                    email: {
                                                        required: 'Please provide Valid email'
                                                    },
                                                    checkbox: {
                                                        required: 'Please agree to Our Terms and Conditions'
                                                    }
                                                },
                                                submitHandler: function (form) {
                                                    form.submit();
                                                }
                                            });
                                        }

</script>