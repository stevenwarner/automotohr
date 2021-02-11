<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo $title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/alertifyjs/css/alertify.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/alertifyjs/css/themes/default.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/css/jquery.datetimepicker.css" />
    <link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css" />

    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/css/public-form-style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/css/responsive.css">

    <script src="<?php echo base_url('assets') ?>/js/jquery-1.11.3.min.js"></script>
    <script src="https://code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script>
    <script src="<?php echo base_url('assets') ?>/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url('assets') ?>/js/jquery.datetimepicker.js"></script>
    <script src="<?php echo base_url('assets') ?>/alertifyjs/alertify.min.js"></script>

    <script src="<?php echo base_url('assets') ?>/js/functions.js"></script>
</head>
<body>
<!-- Wrapper Start -->
<div class="wrapper">
    <!-- Header Start -->
    <header class="header header-position">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-md-offset-3 col-lg-offset-3">
                    <h2 style="color: #fff; text-align: center;"><?php echo $title; ?></h2>
                </div>
            </div>
        </div>
    </header>
    <!-- Header End -->
    <div class="clear"></div>
    <!-- Main Start -->
    <div class="main" style="margin-top: 50px;">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <ul>
                        <li>
                            <h4>Step 1</h4>
                            <p>
                                Please Goto <a target="_blank" href="https://developers.facebook.com/">Facebook Developer Site</a> Login with your Account or register for a Developer Account.
                            </p>
                        </li>
                        <li>
                            <h4>Step 2</h4>
                            <p>
                                Click On <strong>Create App</strong>
                                <div class="thumbnail">
                                    <img class="img-responsive" src="<?php echo base_url('assets/images/facebook_api_instructions/001.png')?>" />
                                </div>
                            </p>
                        </li>
                        <li>
                            <h4>Step 3</h4>
                            <p>
                                Fill in <strong>Display Name</strong> and <strong>Contact Email</strong> and click on <strong>Create App ID</strong>
                                <div class="thumbnail">
                                    <img class="img-responsive" src="<?php echo base_url('assets/images/facebook_api_instructions/002.png')?>" />
                                </div>
                            </p>
                        </li>
                        <li>
                            <h4>Step 4</h4>
                            <p>
                                Click On <strong>Settings</strong> in left menu.
                            <div class="thumbnail">
                                <img class="img-responsive" src="<?php echo base_url('assets/images/facebook_api_instructions/003.png')?>" />
                            </div>
                            </p>
                        </li>
                        <li>
                            <h4>Step 5</h4>
                            <p>
                                Fill in <strong>Contact Email</strong> and <strong>Privacy Policy URL</strong> "https://www.automotohr.com/services/privacy-policy/" and click on <strong>Add Platform</strong>
                            <div class="thumbnail">
                                <img class="img-responsive" src="<?php echo base_url('assets/images/facebook_api_instructions/004.png')?>" />
                            </div>
                            </p>
                        </li>
                        <li>
                            <h4>Step 6</h4>
                            <p>
                                Click on <strong>Page Tab</strong>
                            <div class="thumbnail">
                                <img class="img-responsive" src="<?php echo base_url('assets/images/facebook_api_instructions/005.png')?>" />
                            </div>
                            </p>
                        </li>

                        <li>
                            <h4>Step 7</h4>
                            <p>
                                Paste your <strong>Secure Page Tab URL</strong> from AutomotoHR Facebook API Configuration, Fill in <strong>Page Tab Name</strong>
                            <div class="thumbnail">
                                <img class="img-responsive" src="<?php echo base_url('assets/images/facebook_api_instructions/007.png')?>" />
                            </div>
                            </p>
                        </li>

                        <li>
                            <h4>Step 8</h4>
                            <p>
                                Verify all marked fields.
                            <div class="thumbnail">
                                <img class="img-responsive" src="<?php echo base_url('assets/images/facebook_api_instructions/006.png')?>" />
                            </div>
                            </p>
                        </li>

                        <li>
                            <h4>Step 9</h4>
                            <p>
                                And Click <strong>Save Changes</strong>
                                <div class="thumbnail">
                                    <img class="img-responsive" src="<?php echo base_url('assets/images/facebook_api_instructions/008.png')?>" />
                                </div>
                            </p>
                        </li>

                        <li>
                            <h4>Step 10</h4>
                            <p>
                                Verify Details and click on <strong>Off</strong> button to make the app Live
                                <div class="thumbnail">
                                    <img class="img-responsive" src="<?php echo base_url('assets/images/facebook_api_instructions/009.png')?>" />
                                </div>
                            </p>
                        </li>

                        <li>
                            <h4>Step 10</h4>
                            <p>
                                Click on <strong>Confirm</strong> to save.
                            <div class="thumbnail">
                                <img class="img-responsive" src="<?php echo base_url('assets/images/facebook_api_instructions/010.png')?>" />
                            </div>
                            </p>
                        </li>

                        <li>
                            <h4>Step 11</h4>
                            <p>
                            <ul>
                                <li>From AutomotHR > Settings > Facebook Configuration now click on<strong>Click Here to Authorize Your Facebook App</strong> Button</li>
                            </ul>
                            <div class="thumbnail">
                                <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL; ?>AuthorizeApp-Gkzlm7.png" />
                            </div>
                            <ul>
                                <li>From next popup screen login to your facebook account and add the app to your business page.</li>
                            </ul>
                            </p>
                        </li>
                    </ul>

                </div>
            </div>
        </div>
    </div>

    <div class="clear"></div>
</div>
<!-- Wrapper End -->

</body>
</html>
<script>


</script>