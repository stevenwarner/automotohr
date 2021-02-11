<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('main/employer_column_left_view'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow">
                            <a href="<?php echo base_url('my_settings'); ?>" class="dashboard-link-btn"><i class="fa fa-chevron-left"></i>&nbsp;Settings</a>
                            <?php echo $title; ?>
                        </span>
                    </div>


                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="hr-box">
                                <div class="hr-box-header">
                                    <strong>List of all Overdue Invoices</strong>
                                </div>
                                <div class="hr-innerpadding">
                                    <div class="table-responsive table-outer">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th class="text-center col-xs-3">Invoice Number</th>
                                                <th class="text-center col-xs-3">Date</th>
                                                <th class="text-center col-xs-3">Pyament Status</th>
                                                <th class="text-center col-xs-3">Amount</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php if(!empty($invoices)) { ?>
                                            <?php foreach($invoices as $invoice) {?>
                                                <tr>
                                                    <td class="text-center">
                                                        <?php echo $invoice['invoice_number']; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?=reset_datetime(array('datetime'=>$invoice['created'], '_this' => $this)); ?>
                                                    </td>
                                                    <td class="text-center">
                                                <span class="text-danger">
                                                    <?php echo ucwords(strtolower($invoice['payment_status'])); ?>
                                                </span>
                                                    </td>
                                                    <td class="text-right">$
                                                        <!--
                                                        <?php /*if($invoice['is_discounted'] == 1) { */?>
                                                            <?php /*echo number_format($invoice['total_after_discount'], 2); */?>
                                                        <?php /*} else { */?>
                                                            <?php /*echo number_format($invoice['value'], 2); */?>
                                                        <?php /*} */?>
                                                        -->
                                                        <?php echo number_format($invoice['total_after_discount'], 2); ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <?php } else { ?>
                                                <tr>
                                                    <td colspan="4">
                                                        <span class="no-data">No Pending Invoices</span>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th colspan="3">Total</th>
                                                <th class="text-right">$ <?php echo number_format($grand_total, 2); ?></th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <?php if($grand_total > 0) { ?>
                            <div class="hr-box">
                                <div class="hr-box-header">
                                    <strong>Pay Amount</strong>
                                </div>
                                <div class="hr-innerpadding">
                                    <form action="<?php echo base_url('misc/process_payment_pending_invoices')?>" method="post" id="form_credit_card_details">
                                        <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                        <input type="hidden" id="employer_sid" name="employer_sid" value="<?php echo $employer_sid; ?>" />

                                        <?php foreach($invoices as $invoice) { ?>
                                            <input type="hidden" id="invoice_sid" name="invoice_sid[]" value="<?php echo $invoice['sid']; ?>" />
                                        <?php } ?>

                                        <input type="hidden" id="perform_action" name="perform_action" value="process_credit_card_payment" />

                                        <input type="hidden" id="invoice_amount" name="invoice_amount" value="<?php echo $grand_total; ?>" />
                                        <!--<input type="hidden" id="invoice_amount" name="invoice_amount" value="1" />-->

                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="field-row">
                                                    <label>Choose Saved Card</label>
                                                    <div class="hr-select-dropdown">
                                                        <select name="prev_saved_cc" id="prev_saved_cc" class="invoice-fields">
                                                            <option value="">Please Select Credit Card</option>
                                                            <?php foreach($user_cc as $cc) { ?>
                                                                <option value="<?php echo $cc['sid']?>"><?php echo $cc['number']; ?> - <?php echo $cc['type']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="credit_card_details" class="credit_card_details">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label>Credit Card Number</label>
                                                        <input type="text" name="cc_number" id="cc_number" class="invoice-fields">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label>Credit Card Type</label>
                                                        <div class="hr-select-dropdown">
                                                            <select name="cc_type" id="cc_type" class="invoice-fields">
                                                                <option value="">Please Select Card Type</option>
                                                                <option value="visa">Visa</option>
                                                                <option value="mastercard">Mastercard</option>
                                                                <option value="discover">Discover</option>
                                                                <option value="amex">Amex</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label>Expiration Month</label>
                                                        <div class="hr-select-dropdown">
                                                            <select name="cc_expiration_month" id="cc_expiration_month" class="invoice-fields">
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
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label>Expiration Year</label>
                                                        <div class="hr-select-dropdown">
                                                            <select name="cc_expiration_year" id="cc_expiration_year" class="invoice-fields">
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
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label>CCV</label>
                                                        <input type="text" name="cc_ccv" id="cc_ccv" class="invoice-fields">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <input type="checkbox" checked="checked" name="cc_save_for_future" id="cc_save_for_future" value="1">
                                                        <label for="cc_save_for_future">Save Card for Future Use</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="btn-panel">
                                            <button class="btn btn-success" type="button" onclick="fProcessPayment()">Process Payment</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>

    function fProcessPayment(){

        var prev_cc = $('#prev_saved_cc').val();
        var cc_type = $('#cc_type').val();
        var cc_number = $('#cc_number').val();

        if(prev_cc != '' || (cc_type != '' && cc_number != '')) {
            alertify.confirm(
                'Are You Sure?',
                'Are you sure want to Process Payment against this invoice?',
                function () {
                    $('#form_credit_card_details').submit();
                },
                function () {

                }
            )
        }else{
            alertify.error('Please specify credit card details.');
        }
    }

    $(document).ready(function () {
        $('#prev_saved_cc').on('change', function(){

            var selected_card = $(this).val();
            console.log(selected_card);
            if(selected_card == 0){
                $('#credit_card_details').show();
            }else{
                $('#credit_card_details').hide();
            }
        });


    });
</script>