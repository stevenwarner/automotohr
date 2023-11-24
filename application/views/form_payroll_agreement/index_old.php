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
                        <!-- page print -->
                        <span class="page-heading down-arrow affiliate_end_user_agreement_color">PAYROLL AGREEMENT</span>
                        <div class="end-user-agreement-wrp">
                            <form action="<?php echo base_url('form_payroll_agreement' . '/' . $verification_key) . ($is_pre_fill == 1 ? '/pre_fill' : ''); ?>" method="post" enctype="multipart/form-data">
                                <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_document['company_sid']; ?>" />
                                <input type="hidden" id="is_pre_fill" name="is_pre_fill" value="<?php echo $is_pre_fill; ?>" />

                                <div> <?php $this->load->view('payroll/partials/form_payroll_agreement'); ?></div>
                              
                        </div>

                        <div class="card-fields-row">
                            <div class="row">
                                <div class="col-lg-6">
                                    <?php $uri_segment = $this->uri->segment(3); ?>
                                    <?php if ($uri_segment == 'view' || $uri_segment == null) { ?>
                                        <div class="form-col-100">
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

                                                    <span class="error"><?php echo $error; ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>

                        <div class="card-fields-row">

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