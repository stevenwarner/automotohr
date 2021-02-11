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
            <div class="main" style="margin-top: 50px;">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="box box-default">
                                <div class="box-body">
                                    <div class="alert alert-danger alert-dismissable">
                                        <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                                        <?php echo $error_message; ?>
                                    </div>
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->
                        </div><!-- /.col -->
                    </div> <!-- /.row -->
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="box box-default">
                                <div class="box-body">
                                    <div class="alert alert-info alert-dismissable">
                                        <h4><i class="icon fa fa-check"></i> Help!</h4>
                                        <?php echo $information; ?>
                                    </div>
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->
                        </div><!-- /.col -->
                    </div> <!-- /.row -->
                </div>

                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                            <div id="" class="universal-form-style-v2">
                                <form id="account_expiry_form" method="post">
                                    <div class="tagline-heading"><h4>Account Re-activation Form</h4></div>
                                    <ul>
                                        <li class="form-col-50-left">
                                            <label for="reference_title">Company Name<span class="staric">*</span></label>
                                            <input type="text" class="invoice-fields" value=""name="CompanyName" />
                                            <?php echo form_error('CompanyName'); ?>
                                        </li>
                                        <li class="form-col-50-right">
                                            <label for="reference_name">Contact Person<span class="staric">*</span></label>
                                            <input type="text" class="invoice-fields" value="" name="ContactName" />
                                            <?php echo form_error('ContactName'); ?>
                                        </li>
                                        <li class="form-col-50-left">
                                            <label for="reference_name">Contact Email<span class="staric">*</span></label>
                                            <input type="text" class="invoice-fields" value="" name="email" />
                                            <?php echo form_error('email'); ?>
                                        </li>
                                        <li class="form-col-50-right">
                                            <label for="reference_name">Contact Phone No<span class="staric">*</span></label>
                                            <input type="text" class="invoice-fields" value="" name="PhoneNumber" />
                                            <?php echo form_error('PhoneNumber'); ?>
                                        </li>
                                        <li class="form-col-100" style="height: 175px;">
                                            <label for="">Other Information / Message</label>
                                            <textarea type="text" class="invoice-fields-textarea" value="" id="work_other_information" name="message" ></textarea>
                                        </li>

                                        <li class="form-col-50-left">
                                            <button type="submit" class="submit-btn" value="Send" onclick="validate_form()">Send</button>
                                        </li>
                                        <li class="form-col-50-right"></li>
                                    </ul>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Wrapper End -->
</body>
<script  language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script  language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script>

                                                function validate_form() {
                                                    $("#account_expiry_form").validate({
                                                        ignore: ":hidden:not(select)",
                                                        rules: {
                                                            CompanyName: {
                                                                required: true,
                                                                pattern: /^[a-zA-Z0-9\- ]+$/
                                                            },
                                                            ContactName: {
                                                                required: true,
                                                                pattern: /^[a-zA-Z0-9\- .]+$/
                                                            },
                                                            PhoneNumber: {
                                                                required: true,
                                                                pattern: /^[0-9\-]+$/
                                                            },
                                                            email: {
                                                                required: true,
                                                                email: true
                                                            }
                                                        },
                                                        messages: {
                                                            CompanyName: {
                                                                required: 'Company Name is required',
                                                                pattern: 'Letters, numbers, and dashes only please'
                                                            },
                                                            ContactName: {
                                                                required: 'Contact Name is required',
                                                                pattern: 'Letters, numbers, and dashes only please'
                                                            },
                                                            PhoneNumber: {
                                                                required: 'Phone Number is required',
                                                                pattern: 'Please provide valid Phone Number'
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
</html>
