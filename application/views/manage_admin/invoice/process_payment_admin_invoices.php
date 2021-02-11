<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="heading-title page-title">
                                            <span class="page-title"><i class="fa fa-money"></i>Process Payment For Admin Invoice # <?php echo $invoice['invoice_number'];?></span>
                                            <a href="<?php echo base_url('manage_admin/invoice/list_admin_invoices'); ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Back to Invoices</a>
                                            <a href="<?php echo base_url('manage_admin/companies/manage_company/' . $invoice['company_sid']);?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Back to Manage Company</a>
                                        </div>
                                        <div class="add-new-company">
                                            <div class="heading-title page-title">
                                                <span class="page-title">Invoice Details</span>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-3">
                                                    <div class="field-row">
                                                        <label>Invoice</label>
                                                        <span class="hr-form-fileds padding-8px"><?php echo $invoice['invoice_number']; ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-xs-3">
                                                    <div class="field-row">
                                                        <label>Amount</label>
                                                        <!--
                                                        <?php /*if($invoice['is_discounted'] == 1) { */?>
                                                            <span class="hr-form-fileds padding-8px">$ <?php /*echo number_format($invoice['total_after_discount'], 2, '.', ','); */?></span>
                                                        <?php /*} else { */?>
                                                            <span class="hr-form-fileds padding-8px">$ <?php /*echo number_format($invoice['value'], 2, '.', ','); */?></span>
                                                        <?php /*} */?>
                                                        -->
                                                        <span class="hr-form-fileds padding-8px">$ <?php echo number_format($invoice['total_after_discount'], 2, '.', ','); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-xs-3">
                                                    <div class="field-row">
                                                        <label>Is Discounted</label>
                                                        <span class="hr-form-fileds padding-8px">
                                                            <?php echo ($invoice['is_discounted'] == 1 ? 'Yes' : 'No'); ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="payment_method">
                                                <div class="heading-title page-title">
                                                    <span class="page-title">Select Payment Method</span>
                                                </div>
                                                <div>
                                                    <div class="col-xs-3 col-md-3 col-sm-3 col-lg-3">
                                                        <label class="package_label" for="package_01">
                                                            <div class="img-thumbnail text-center package-info-box">
                                                                <figure>
                                                                    <i class="fa fa-money" style="font-size: 100px"></i>
                                                                </figure>
                                                                <div class="caption">
                                                                    <h2><strong>Cash</strong></h2>
                                                                    <p>Process Cash Payment</p>
                                                                </div>
                                                                <input class="select-package" type="radio" id="package_01" name="method" value="cash" />
                                                            </div>
                                                        </label>
                                                    </div>
                                                    <div class="col-xs-3 col-md-3 col-sm-3 col-lg-3">
                                                        <label class="package_label" for="package_03">
                                                            <div class="img-thumbnail text-center package-info-box">
                                                                <figure>
                                                                    <i class="fa fa-bank" style="font-size: 100px"></i>
                                                                </figure>
                                                                <div class="caption">
                                                                    <h2><strong>Check</strong></h2>
                                                                    <p>Process Check Payment</p>
                                                                </div>
                                                                <input class="select-package" type="radio" id="package_03" name="method" value="check" />
                                                            </div>
                                                        </label>
                                                    </div>
                                                    <div class="col-xs-3 col-md-3 col-sm-3 col-lg-3">
                                                        <label class="package_label" for="package_02">
                                                            <div class="img-thumbnail text-center package-info-box">
                                                                <figure>
                                                                    <i class="fa fa-credit-card" style="font-size: 100px"></i>
                                                                </figure>
                                                                <div class="caption">
                                                                    <h2><strong>Credit Card</strong></h2>
                                                                    <p>Credit Card Payment</p>
                                                                </div>
                                                                <input class="select-package" type="radio" id="package_02" name="method" value="credit_card" />
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="cash_payment">
                                                <div class="heading-title page-title">
                                                    <span class="page-title">Process Cash Payment</span>
                                                </div>
                                                <form id="form_process_cash_payment" method="post" enctype="application/x-www-form-urlencoded" action="<?php echo base_url('manage_admin/invoice/ajax_responder'); ?>">
                                                    <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $invoice['company_sid']; ?>" />
                                                    <input type="hidden" id="invoice_sid" name="invoice_sid" value="<?php echo $invoice['sid']; ?>" />
                                                    <input type="hidden" id="perform_action" name="perform_action" value="process_cash_payment" />
                                                    <input type="hidden" id="admin_user_id" name="admin_user_id" value="<?php echo $admin_user_id; ?>" />

                                                    <?php /*if($invoice['is_discounted'] == 1) { */?><!--
                                                        <input type="hidden" id="invoice_amount" name="invoice_amount" value="<?php /*echo $invoice['total_after_discount']; */?>" />
                                                    <?php /*} else { */?>
                                                        <input type="hidden" id="invoice_amount" name="invoice_amount" value="<?php /*echo $invoice['value']; */?>" />
                                                    --><?php /*} */?>

                                                    <input type="hidden" id="invoice_amount" name="invoice_amount" value="<?php echo $invoice['total_after_discount']; ?>" />
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <div class="form-group">
                                                                <label for="payment_description">Payment Description</label>
                                                                <textarea id="payment_description" name="payment_description" rows="4" class="form-control"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <button type="button" class="site-btn" onclick="fProcessPaymentCash();">Process Cash Payment</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div id="check_payment">
                                                <div class="heading-title page-title">
                                                    <span class="page-title">Process Check Payment</span>
                                                </div>
                                                <form id="form_process_check_payment" method="post" enctype="application/x-www-form-urlencoded" action="<?php echo base_url('manage_admin/invoice/ajax_responder'); ?>">
                                                    <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $invoice['company_sid']; ?>" />
                                                    <input type="hidden" id="invoice_sid" name="invoice_sid" value="<?php echo $invoice['sid']; ?>" />
                                                    <input type="hidden" id="perform_action" name="perform_action" value="process_check_payment" />
                                                    <input type="hidden" id="admin_user_id" name="admin_user_id" value="<?php echo $admin_user_id; ?>" />
                                                    <input type="hidden" id="invoice_amount" name="invoice_amount" value="<?php echo $invoice['total_after_discount']; ?>" />
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <div class="field-row">
                                                                <label>Check Number <span class="hr-required">*</span></label>
                                                                <input type="text" class="form-control" id="check_number" name="check_number" />
                                                                <?php echo form_error('check_number'); ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12">
                                                            <div class="form-group">
                                                                <label for="payment_description">Payment Description <span class="hr-required">*</span></label>
                                                                <textarea id="payment_description_check" name="payment_description" rows="4" class="form-control"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <button type="button" class="site-btn" onclick="fProcessPaymentCheck();">Process Check Payment</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div id="credit_card_payment">
                                                <div class="heading-title page-title">
                                                    <span class="page-title">Process Payment Using Credit Card</span>
                                                </div>
                                                <form id="form_credit_card_details" method="post" enctype="application/x-www-form-urlencoded" action="<?php echo base_url('manage_admin/misc/process_payment_admin_invoice') . '/' . $invoice['sid']; ?>">
                                                    <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $invoice['company_sid']; ?>" />
                                                    <input type="hidden" id="invoice_sid" name="invoice_sid" value="<?php echo $invoice['sid']; ?>" />
                                                    <input type="hidden" id="perform_action" name="perform_action" value="process_credit_card_payment" />
                                                    <input type="hidden" id="admin_user_id" name="admin_user_id" value="<?php echo $admin_user_id; ?>" />

                                                    <?php /*if($invoice['is_discounted'] == 1) { */?><!--
                                                        <input type="hidden" id="invoice_amount" name="invoice_amount" value="<?php /*echo $invoice['total_after_discount']; */?>" />
                                                    <?php /*} else { */?>
                                                        <input type="hidden" id="invoice_amount" name="invoice_amount" value="<?php /*echo $invoice['value']; */?>" />
                                                    --><?php /*} */?>

                                                    <input type="hidden" id="invoice_amount" name="invoice_amount" value="<?php echo $invoice['total_after_discount']; ?>" />
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <div class="field-row">
                                                                <label>Choose Saved Card</label>
                                                                <div class="hr-select-dropdown">
                                                                    <select class="invoice-fields" id="prev_saved_cc" name="prev_saved_cc">
                                                                        <option value="0">Please Select Credit Card</option>
                                                                        <?php foreach($user_cc as $cc) { ?>
                                                                                <option value="<?php echo $cc['sid']?>">
                                                                                        <?php echo $cc['number']; ?> - 
                                                                                        <?php echo $cc['type'] . ' '; ?>
                                                                                        <?php echo ($cc['is_default'] == 1) ? '(Default)' : ''; ?>
                                                                                </option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="credit_card_details">
                                                        <div class="row">
                                                            <div class="col-xs-6">
                                                                <div class="field-row">
                                                                    <label>Credit Card Number <span class="hr-required">*</span></label>
                                                                    <input type="text" class="hr-form-fileds" id="cc_number" name="cc_number" />
                                                                    <?php echo form_error('cc_number'); ?>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-6">
                                                                <div class="field-row">
                                                                    <label>Credit Card Type <span class="hr-required">*</span></label>
                                                                    <div class="hr-select-dropdown">
                                                                        <select class="invoice-fields" id="cc_type" name="cc_type">
                                                                            <option value="">Please Select Card Type</option>
                                                                            <option value="visa">Visa</option>
                                                                            <option value="mastercard">Mastercard</option>
                                                                            <option value="discover">Discover</option>
                                                                            <option value="amex">Amex</option>
                                                                        </select>
                                                                    </div>
                                                                    <?php echo form_error('cc_type'); ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-xs-6">
                                                                <div class="field-row">
                                                                    <label>Expiration Month <span class="hr-required">*</span></label>
                                                                    <div class="hr-select-dropdown">
                                                                        <select class="invoice-fields" id="cc_expiration_month" name="cc_expiration_month">
                                                                            <option value="">Please Select</option>
                                                                            <option value="1">January</option>
                                                                            <option value="2">February</option>
                                                                            <option value="3">March</option>
                                                                            <option value="4">April</option>
                                                                            <option value="5">May</option>
                                                                            <option value="6">June</option>
                                                                            <option value="7">July</option>
                                                                            <option value="8">August</option>
                                                                            <option value="9">September</option>
                                                                            <option value="10">October</option>
                                                                            <option value="11">November</option>
                                                                            <option value="12">December</option>
                                                                        </select>
                                                                    </div>
                                                                    <?php echo form_error('cc_expiration_month'); ?>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-6">
                                                                <div class="field-row">
                                                                    <label>Expiration Year <span class="hr-required">*</span></label>
                                                                    <div class="hr-select-dropdown">
                                                                        <select class="invoice-fields" id="cc_expiration_year" name="cc_expiration_year">
                                                                            <option value="">Please Select</option>
                                                                            <option value="2016">2016</option>
                                                                            <option value="2017">2017</option>
                                                                            <option value="2018">2018</option>
                                                                            <option value="2019">2019</option>
                                                                            <option value="2020">2020</option>
                                                                            <option value="2021">2021</option>
                                                                            <option value="2022">2022</option>
                                                                            <option value="2023">2023</option>
                                                                            <option value="2024">2024</option>
                                                                            <option value="2025">2025</option>
                                                                            <option value="2026">2026</option>
                                                                            <option value="2027">2027</option>
                                                                            <option value="2028">2028</option>
                                                                            <option value="2029">2029</option>
                                                                            <option value="2030">2030</option>
                                                                        </select>
                                                                    </div>
                                                                    <?php echo form_error('cc_expiration_year'); ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-xs-6">
                                                                <div class="field-row">
                                                                    <label>CCV</label>
                                                                    <input type="text" class="hr-form-fileds"  id="cc_ccv" name="cc_ccv" />
                                                                    <?php echo form_error('cc_ccv'); ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-xs-6">
                                                                <div class="field-row">
                                                                    <input value="1" type="checkbox" id="cc_save_for_future" name="cc_save_for_future" checked="checked" />
                                                                    <label for="cc_save_for_future">Save Card for Future Use</label>                                                                    
                                                                    <?php echo form_error('cc_save_for_future'); ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="row">
                                                            <div class="col-xs-12">
                                                                <button type="button" class="site-btn" id="pro-pay" onclick="fProcessPayment();">Process Payment</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <hr />
                                            <div id="">
                                                <div class="heading-title page-title">
                                                    <span class="page-title">Send CC Update Request</span>
                                                </div>
                                                <form id="form_send_update_cc_request_email" enctype="multipart/form-data" method="post" action="<?php echo base_url('manage_admin/invoice/ajax_responder');?>">
                                                    <input type="hidden" id="perform_action" name="perform_action" value="send_update_cc_request_email" />
                                                    <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $invoice['company_sid'];?>" />
                                                    <input type="hidden" id="invoice_sid" name="invoice_sid" value="<?php echo $invoice['sid'];?>" />
                                                    <input type="hidden" name="company_name" value="<?php echo $company_info['CompanyName'];?>" />

                                                    <div class="row">
                                                        <div class="col-xs-4">
                                                            <div class="field-row">
                                                                <label for="contact_name">Contact Name</label>
                                                                <input data-rule-required="true" id="contact_name" name="contact_name" type="text" class="hr-form-fileds"  value=""/>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-8">
                                                            <div class="field-row">
                                                                <label for="email_address">Email Address</label>
                                                                <input data-rule-required="true" data-rule-email="true" id="email_address" name="email_address" type="email" class="hr-form-fileds"  value="<?php echo $company_info['email']; ?>"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xs-4">
                                                            <div class="field-row">
                                                                <label for="email_template">Email Template</label>
                                                                <div class="hr-select-dropdown">
                                                                    <select class="invoice-fields" name="email_template">
                                                                        <option value="UPDATE_CREDIT_CARD_REQUEST">Already Expired Card</option>
                                                                        <option value="CREDIT_CARD_EXPIRATION_NOTIFICATION">May Expire Soon</option>
                                                                    </select>   
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-5">
                                                            <div class="field-row">
                                                                <label for="card_no">Card No</label>
                                                                <div class="hr-select-dropdown">
                                                                    <select class="invoice-fields" name="card_no">
                                                                        <?php   foreach($user_cc as $cc) { ?>
                                                                                    <option value="<?php echo $cc['sid']?>"><?php echo $cc['number']; ?></option>
                                                                        <?php   } ?>
                                                                    </select>   
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-3">
                                                            <div class="field-row">
                                                                <label for="button">&nbsp;</label>
                                                                <button type="button" class="btn btn-success btn-block btn-equalizer" onclick="func_send_update_cc_notification_emil();">Send Request</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function func_send_update_cc_notification_emil() {
        $('#form_send_update_cc_request_email').validate();

        if($('#form_send_update_cc_request_email').valid()) {
            alertify.confirm(
                'Are You Sure?',
                'Are you sure you want to send <strong>Update Credit Card Details</strong> Request Email to this Company?',
                function () {
                    $('#form_send_update_cc_request_email').submit();
                }, function () {
                    alertify.error('Cancelled!');
                }).set('labels', {ok: 'Yes', cancel: 'No'});
        }
    }

    function fProcessPaymentCash(){
        var payment_description = $('#payment_description').val();

        if(payment_description != '') {
            alertify.confirm(
                'Are You Sure?',
                'Are you sure want to Process Payment against this invoice?',
                function () {
                    $('#form_process_cash_payment').submit();
                },
                function () {

                }
            );
        } else {
            alertify.error('Please write some details about the cash payment.');
        }
    }
    
    function fProcessPaymentCheck(){
        var payment_description = $('#payment_description_check').val();
        var check_number = $('#check_number').val();

        if(payment_description != '' && check_number != '') {
            alertify.confirm(
                'Are You Sure?',
                'Are you sure want to Process Payment against this invoice?',
                function () {
                    $('#form_process_check_payment').submit();
                },
                function () {

                }
            );
        } else {
            alertify.error('Please provide Check Number and payment description.');
        }
    }

    function fProcessPayment(){
        var prev_cc = $('#prev_saved_cc').val();
        var cc_type = $('#cc_type').val();
        var cc_number = $('#cc_number').val();



        if(prev_cc != '' || (cc_type != '' && cc_number != '')) {
            alertify.confirm(
                'Are You Sure?',
                'Are you sure want to Process Payment against this invoice?',
                function () {
                    $('#pro-pay').attr('disabled','disabled');
                    $('#pro-pay').addClass('disabled-btn');
                    $('#form_credit_card_details').submit();
                },
                function () {

                }
            );
        } else {
            alertify.error('Please specify credit card details.');
        }
    }

    function fEnableFeatures(){
        var Url = '<?php echo base_url('manage_admin/invoice/ajax_responder'); ?>';
        var myRequest;

        myRequest = $.ajax({
            url : Url,
            type: 'POST',
            data: { perform_action : 'update_package'}
        });

        myRequest.done(function (response) {
           // console.log(response);
        });
    }

    $('#prev_saved_cc').on('change', function(){
        var selected_card = $(this).val();
        //console.log(selected_card);
        if(selected_card == 0){
            $('#credit_card_details').show();
        } else {
            $('#credit_card_details').hide();
        }
    });

    $(document).ready(function () {
        $('#credit_card_payment').hide();
        $('#cash_payment').hide();
        $('#check_payment').hide();
        
        $('.select-package').click(function () {
            $('.select-package:not(:checked)').parent().removeClass("selected-package");
            $('.select-package:checked').parent().addClass("selected-package");
            //console.log($(this).val());
            if($(this).val() == 'cash'){
                $('#cash_payment').show();
                $('#credit_card_payment').hide();
                $('#check_payment').hide();
            } else if($(this).val() == 'credit_card') {
                $('#cash_payment').hide();
                $('#credit_card_payment').show();
                $('#check_payment').hide();
            } else if($(this).val() == 'check') {
                $('#cash_payment').hide();
                $('#credit_card_payment').hide();
                $('#check_payment').show();
            }
        });
    });
</script>