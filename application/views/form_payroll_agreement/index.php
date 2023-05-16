<?php $this->load->view('main/static_header'); ?>

<body>
    <div class="main-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <?php
                    $readonly = '';
                    if ($company_document['status'] == 'signed') {
                        $readonly = ' readonly="readonly" ';
                    }
                    ?>
                    <div class="end-user-wrp-outer">
                        <div class="top-logo text-center">
                            <img src="<?php echo base_url('assets/images/form-logo.jpg') ?>">
                        </div>
                        <!-- page print -->
                        <div class="top-logo" id="print_div">
                            <a href="javascript:;" class="btn btn-success affiliate_end_user_agreement_color_btn" onclick="print_page('.container');">
                                <i class="fa fa-print" aria-hidden="true"></i> Print or Save
                            </a>
                        </div>
                    
                        <div class="top-logo  alert alert-danger">
                            <strong><?php echo $error; ?></strong>
                        </div>

                        <!-- page print -->
                        <div class="end-user-agreement-wrp">
                            <form action="<?php echo base_url('form_payroll_agreement' . '/' . $verification_key) . ($is_pre_fill == 1 ? '/pre_fill' : ''); ?>" method="post" enctype="multipart/form-data">
                                <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_document['company_sid']; ?>" />
                                <input type="hidden" id="is_pre_fill" name="is_pre_fill" value="<?php echo $is_pre_fill; ?>" />


                                <div> <?php $this->load->view('payroll/partials/form_payroll_agreement'); ?></div>



                                <div class="end-user-form-wrp">
                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                        <span class="page-heading down-arrow affiliate_end_user_agreement_color">COMPANY </span>
                                        <div class="field-row">
                                            <div class="row">
                                                <div class="col-lg-2">
                                                    <label>By:</label>
                                                </div>
                                                <div class="col-lg-10">
                                                    <?php
                                                    $company_by = $company_document['company_by'];
                                                    if ($company_by == '') {
                                                        $company_by = STORE_NAME;
                                                    }
                                                    ?>
                                                    <input type="text" class="invoice-fields" id="company_by" name="company_by" value="<?php echo set_value('company_by', $company_by); ?>" <?php echo $readonly; ?> <?php echo ($is_pre_fill == 0) ? 'readonly="readonly"' : ''; ?> />
                                                    <?php echo form_error('company_by'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="field-row">
                                            <div class="row">
                                                <div class="col-lg-2">
                                                    <label>Name:</label>
                                                </div>
                                                <div class="col-lg-10">
                                                    <?php
                                                    $company_name = $company_document['company_name'];
                                                    if ($company_name == '') {
                                                        $company_name = 'Steven Warner';
                                                    }
                                                    ?>
                                                    <input type="text" class="invoice-fields" id="company_name" name="company_name" value="<?php echo set_value('company_name', $company_name); ?>" <?php echo $readonly; ?> <?php echo ($is_pre_fill == 0) ? 'readonly="readonly"' : ''; ?> />
                                                    <?php echo form_error('company_name'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="field-row">
                                            <div class="row">
                                                <div class="col-lg-2">
                                                    <label>Title:</label>
                                                </div>
                                                <div class="col-lg-10">
                                                    <?php
                                                    $company_title = $company_document['company_title'];
                                                    if ($company_title == '') {
                                                        $company_title = 'CEO';
                                                    }
                                                    ?>
                                                    <input type="text" class="invoice-fields" id="company_title" name="company_title" value="<?php echo set_value('company_title', $company_title); ?>" <?php echo $readonly; ?> <?php echo ($is_pre_fill == 0) ? 'readonly="readonly"' : ''; ?> />
                                                    <?php echo form_error('company_title'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="field-row">
                                            <div class="row">
                                                <div class="col-lg-2">
                                                    <label>DATE:</label>
                                                </div>
                                                <div class="col-lg-10">
                                                    <?php
                                                    if ($company_document['status'] == 'signed') {
                                                        $company_document['company_date'] = date('m-d-Y', strtotime(str_replace('-', '/', $company_document['company_date'])));
                                                    } else {
                                                        $company_document['company_date'] = date('m-d-Y');
                                                    }

                                                    ?>
                                                    <input type="text" class="invoice-fields startdate" id="company_date" name="company_date" value="<?php echo set_value('company_date', $company_document['company_date']); ?>" <?php echo $readonly; ?> <?php echo ($is_pre_fill == 0) ? 'readonly="readonly"' : ''; ?> />
                                                    <?php echo form_error('company_date'); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                        <span class="page-heading down-arrow affiliate_end_user_agreement_color">CLIENT</span>
                                        <div class="field-row">
                                            <div class="row">
                                                <div class="col-lg-2">
                                                    <label>By:</label>
                                                </div>
                                                <div class="col-lg-10">
                                                    <input type="text" class="invoice-fields" id="client_by" name="client_by" value="<?php echo set_value('client_by', $company_document['client_by']); ?>" <?php echo $readonly; ?> />
                                                    <?php echo form_error('client_by'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="field-row">
                                            <div class="row">
                                                <div class="col-lg-2">
                                                    <label>Name:</label>
                                                </div>
                                                <div class="col-lg-10">
                                                    <input type="text" class="invoice-fields" id="client_name" name="client_name" value="<?php echo set_value('client_name', $company_document['client_name']); ?>" <?php echo $readonly; ?> />
                                                    <?php echo form_error('client_name'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="field-row">
                                            <div class="row">
                                                <div class="col-lg-2">
                                                    <label>Title:</label>
                                                </div>
                                                <div class="col-lg-10">
                                                    <input type="text" class="invoice-fields" id="client_title" name="client_title" value="<?php echo set_value('client_title', $company_document['client_title']); ?>" <?php echo $readonly; ?> />
                                                    <?php echo form_error('client_title'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="field-row">
                                            <div class="row">
                                                <div class="col-lg-2">
                                                    <label>DATE:</label>
                                                </div>
                                                <div class="col-lg-10">
                                                    <?php
                                                    if ($company_document['status'] == 'signed') {
                                                        $company_document['client_date'] = date('m-d-Y', strtotime(str_replace('-', '/', $company_document['client_date'])));
                                                    } else {
                                                        $company_document['client_date'] = date('m-d-Y');
                                                    }

                                                    ?>

                                                    <input type="text" class="invoice-fields startdate" id="client_date" name="client_date" value="<?php echo set_value('client_date', $company_document['client_date']); ?>" <?php echo $readonly; ?> />
                                                    <?php echo form_error('client_date'); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p style="float:left; width:100%;" class="col-lg-12">IN WITNESS WHEREOF, the parties have caused this Agreement to be executed in its name and attested to by its duly authorized officers or individuals as of the Date below. </p>

                                <div class="card-fields-row">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="card-fields-row">
                                                <div class="col-lg-3">
                                                    <label class="signature-label" style="font-size:14px;">E-SIGNATURE</label>
                                                </div>
                                                <div class="col-lg-9">
                                                    <?php
                                                    $company_signature = $company_document['company_signature'];
                                                    if ($company_signature == '') {
                                                        $company_signature = 'Steven Warner';
                                                    }
                                                    ?>
                                                    <input type="text" class="signature-field" name="company_signature" id="company_signature" value="<?php echo set_value('company_signature', $company_signature); ?>" <?php echo $readonly; ?> <?php echo ($is_pre_fill == 0) ? 'readonly="readonly"' : ''; ?> />
                                                    <p>Please type your First and Last Name</p>
                                                    <?php echo form_error('company_signature'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="card-fields-row">
                                                <div class="col-lg-3">
                                                    <label class="signature-label" style="font-size:14px;">E-SIGNATURE</label>
                                                </div>
                                                <div class="col-lg-9">
                                                    <input data-rule-required="true" type="text" class="signature-field" name="client_signature" id="client_signature" value="<?php echo set_value('client_signature', $company_document['client_signature']); ?>" <?php echo $readonly; ?> />
                                                    <p>Please type your First and Last Name</p>
                                                    <?php echo form_error('client_signature'); ?>
                                                </div>
                                            </div>

                                            <?php $uri_segment = $this->uri->segment(3); ?>
                                            <?php if ($uri_segment == 'view' || $uri_segment == null) { ?>
                                                <div class="form-col-100">
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-3 col-xs-5 col-sm-3">
                                                            <label class="" style="font-size:14px;">IP Address</label>
                                                        </div>
                                                        <div class="col-lg-9 col-md-9 col-xs-7 col-sm-9">
                                                            <span>
                                                                <?php if (!empty($ip_track)) { ?>
                                                                    <?php echo $ip_track['ip_address']; ?>
                                                                <?php } else { ?>
                                                                    <?php echo getUserIP(); ?>
                                                                <?php } ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-3 col-xs-5 col-sm-3">
                                                            <label class="" style="font-size:14px;">Date/Time</label>
                                                        </div>
                                                        <div class="col-lg-9 col-md-9 col-xs-7 col-sm-9">
                                                            <span>
                                                                <?php if (!empty($ip_track)) { ?>
                                                                    <?php echo date('m/d/Y h:i A', strtotime($ip_track['document_timestamp'])); ?>
                                                                <?php } else { ?>
                                                                    <?php echo date('m/d/Y h:i A'); ?>
                                                                <?php } ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-fields-row">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <p style="text-align: center !important;">

                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-fields-row">
                                    <p>
                                        <?php echo DEFAULT_SIGNATURE_CONSENT_HEADING; ?>
                                    </p>
                                    <p>
                                        <?php echo DEFAULT_SIGNATURE_CONSENT_TITLE; ?>
                                    </p>
                                    <p>
                                        <?php echo DEFAULT_SIGNATURE_CONSENT_DESCRIPTION; ?>
                                    </p>
                                </div>
                                <div class="card-fields-row acknowledgment-row">
                                    <?php
                                    $is_default_accepted = false;
                                    if ($company_document['acknowledgement'] == 'terms_accepted') {
                                        $is_default_accepted = true;
                                    }
                                    ?>
                                    <label class="control control--checkbox" for="acknowledgement">
                                        <input type="checkbox" value="terms_accepted" id="acknowledgement" name="acknowledgement" <?php echo set_checkbox('acknowledgement', 'terms_accepted', $is_default_accepted); ?> <?php echo ($company_document['status'] == 'signed' ? 'onclick="return false"' : ''); ?> />&nbsp;
                                        <?php echo DEFAULT_SIGNATURE_CONSENT_CHECKBOX; ?>
                                        <div class="control__indicator"></div>
                                    </label>
                                    <?php echo form_error('acknowledgement'); ?>
                                </div>

                                <?php if ($company_document['status'] != 'signed') { ?>
                                    <div class="col-lg-6 col-lg-offset-3" id="signed">
                                        <?php if ($is_pre_fill == 1) { ?>
                                            <button type="submit" class="page-heading affiliate_end_user_agreement_color_btn">Save</button>
                                        <?php } else { ?>
                                            <button type="submit" class="page-heading affiliate_end_user_agreement_color_btn"><?php echo DEFAULT_SIGNATURE_CONSENT_BUTTON; ?></button>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    ?>

    <script>
        $(document).ready(function() {
            $('#form_end_user_license_agreement').validate();

            var value = $("input[name='payment_method']:checked").val();
            display(value);
        });

        function display(value) {
            if (value == 'monthly_subscription') {
                $('.monthly-subscription').show();
                $('.trial-period').hide();
            } else {
                $('.monthly-subscription').hide();
                $('.trial-period').show();
            }
        }
    </script>

    <?php if ($company_document['status'] != 'signed') { ?>
        <script type="text/javascript">
            $(document).ready(function() {
                $('.startdate').datepicker({
                    dateFormat: 'mm-dd-yy',
                    changeMonth: true,
                    changeYear: true,
                    yearRange: "<?php echo DOB_LIMIT; ?>"
                }).val();

                $('.static-class').each(function() {
                    $(this).on('change', function() {
                        var current = $(this).val();
                    });
                });
            });



            $("input[type='radio']").click(function() {
                var value = $("input[name='payment_method']:checked").val();
                display(value);
            });
        </script>
    <?php } ?>
    <script>
        // print page button
        function print_page(elem) {
            $('form input[type=text]').each(function() {
                $(this).attr('value', $(this).val());
            });

            // hide the signed button
            $('#signed').hide();
            $('#print_div').hide();

            var data = ($(elem).html());
            var mywindow = window.open('', 'Print Report', 'height=800,width=1200');

            mywindow.document.write('<html><head><title>' + '<?php echo $page_title; ?>' + '</title>');
            mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/bootstrap.css'); ?>" type="text/css" />');
            mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/font-awesome.css'); ?>" type="text/css" />');
            mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/jquery-ui-datepicker-custom.css'); ?>" type="text/css" />');
            mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/style.css'); ?>" type="text/css" />');
            mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/responsive.css'); ?>" type="text/css" />');
            mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/alertifyjs/css/alertify.min.css'); ?>" type="text/css" />');
            mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/alertifyjs/css/themes/default.min.css'); ?>" type="text/css" />');
            mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/jquery.datetimepicker.css'); ?>" type="text/css" />');
            mywindow.document.write('</head><body >');
            mywindow.document.write(data);
            mywindow.document.write('</body></html>');
            mywindow.document.write('<scr' + 'ipt src="<?php echo site_url('assets/manage_admin/js/jquery-1.11.3.min.js'); ?>"></scr' + 'ipt>');

            mywindow.document.write('<scr' + 'ipt type="text/javascript">$(window).load(function() { window.print(); window.close(); });</scr' + 'ipt>');
            mywindow.document.close();
            mywindow.focus();

            // display the button again
            $('#signed').show();
            $('#print_div').show();
        }
    </script>
</body>

</html>