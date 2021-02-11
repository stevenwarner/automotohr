<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container-fluid">
        <div class="row">		
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                    <div class="heading-title page-title">
                                        <span class="page-title"><i class="fa fa-briefcase"></i><?php echo $page_title; ?></span>
                                        <a href="<?php echo base_url('manage_admin/marketing_agencies/manage_commissions/' . $payment_voucher['marketing_agency_sid']); ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Commissions</a>
                                    </div>
                                    <div class="hr-box">
                                        <div class="hr-box-header bg-header-green">
                                            <span class="hr-registered pull-left">Payment Voucher Details</span>
                                        </div>
                                        <div class="hr-box-body hr-innerpadding">
                                            <div class="table-responsive">
                                                <?php if (!empty($payment_voucher)) { ?>
                                                    <table class="table table-bordered table-hover table-stripped">
                                                        <tbody>
                                                            <tr>
                                                                <th class="col-xs-4">Date</th>
                                                                <td class="col-xs-8"><?php echo convert_date_to_frontend_format($payment_voucher['created']); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="col-xs-4">Payment Voucher Number</th>
                                                                <td class="col-xs-8"><?php echo $payment_voucher['voucher_number']; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="col-xs-4">Commission Invoice Number</th>
                                                                <td class="col-xs-8"><?php echo $payment_voucher['commission_invoice_no']; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="col-xs-4">Against Invoice Number</th>
                                                                <td class="col-xs-8"><b><?php echo $voucher_details['invoice']['invoice_number']; ?></b></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="col-xs-4">Against Invoice Type</th>
                                                                <?php if ($voucher_details['invoice_origin'] == 'super_admin') { ?>
                                                                    <td class="col-xs-8">Admin Invoice</td>
                                                                <?php } else { ?>
                                                                    <td class="col-xs-8">Marketplace Invoice</td>
                                                                <?php } ?>
                                                            </tr>
                                                            <tr>
                                                                <th class="col-xs-4">Marketing Agency Name</th>
                                                                <td class="col-xs-8"><?php echo ucwords($payment_voucher['marketing_agency_name']); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="col-xs-4">Company Name</th>
                                                                <td class="col-xs-8"><?php echo ucwords($payment_voucher['company_name']); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="col-xs-4">Payment Amount</th>
                                                                <td class="col-xs-8">$<?php echo number_format($payment_voucher['paid_amount'], 2); ?></td>
                                                            </tr>
                                                            <?php if(strtolower($payment_voucher['payment_status']) == 'paid') { ?>
                                                            <tr>
                                                                <th class="col-xs-4">Payment Status</th>
                                                                <td class="col-xs-8"><?php echo ucwords($payment_voucher['payment_status']); ?>&nbsp;
                                                                <button type="button" class="btn btn-success btn-sm" onclick="mark_payment_voucher(<?php echo $payment_voucher['sid']; ?>, <?php echo $payment_voucher['commission_invoice_sid']; ?>, 'mark_payment_voucher_as_unpaid', 'UN-Paid');">Mark As UN-Paid</button>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th class="col-xs-4">Payment Date</th>
                                                                <td class="col-xs-8"><?php echo convert_date_to_frontend_format($payment_voucher['payment_date']); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="col-xs-4">Payment Method</th>
                                                                <td class="col-xs-8"><?php echo ucwords($payment_voucher['payment_method']); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="col-xs-4">Payment Reference</th>
                                                                <td class="col-xs-8"><?php echo $payment_voucher['payment_reference']; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="col-xs-4">Payment Description</th>
                                                                <td class="col-xs-8"><?php echo $payment_voucher['payment_description']; ?></td>
                                                            </tr>
                                                            <?php } else { ?>
                                                                <tr>
                                                                    <th class="col-xs-4">Mark Paid</th>
                                                                    <td class="col-xs-8"><button type="button" class="btn btn-success btn-sm" onclick="mark_payment_voucher(<?php echo $payment_voucher['sid']; ?>, <?php echo $payment_voucher['commission_invoice_sid']; ?>, 'mark_payment_voucher_as_paid', 'Paid');">Mark As Paid</button></td>
                                                                </tr>
                                                                <tr>
                                                                    <th class="col-xs-4">Update Voucher Details</th>
                                                                    <td class="col-xs-8"><a class="btn btn-success btn-sm" href="<?php echo base_url('manage_admin/marketing_agencies/edit_payment_voucher/' . $payment_voucher['sid']); ?>">Edit Payment Details</a></td>
                                                                </tr>
                                                            <?php } ?>

                                                        </tbody>
                                                    </table>
                                                <?php } else { ?>
                                                    <div class="text-center">
                                                        <div class="no-data">
                                                            Payment voucher not Found!
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if (!empty($payment_voucher)) { ?>
<!--                                    <div class="row">
                                        <div class="col-xs-10">
                                            <form id="form_send_pending_invoices_summary" enctype="multipart/form-data" method="post" action="<?php current_url(); ?>">
                                                <div class="row">
                                                    <div class="col-xs-10">
                                                        <div class="field-row">
                                                            <label for="email_address">Send Voucher To</label>
                                                            <input data-rule-required="true" data-rule-email="true" type="email" class="hr-form-fileds" id="email_address" name="email_address" value="<?php //echo $company_info['email']; ?>" placeholder="xyz@abc.com" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-2">
                                                        <div class="field-row">
                                                            <label for="">&nbsp;</label>
                                                            <button onclick="func_send_pending_invoices_summary();" type="button" class="btn btn-success btn-block btn-equalizer">Send</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="hidden" id="perform_action" name="perform_action" value="send_pending_invoices" />
                                                <input type="hidden" id="company_sid" name="company_sid" value="<?php //echo $company_sid?>" />
                                            </form>
                                        </div>
                                        <div class="col-xs-2">
                                            <div class="field-row">
                                                <label for="">&nbsp;</label>
                                                <a href="<?php echo base_url('manage_admin/invoice/print_pending_invoices/' . $company_sid); ?>" class="btn btn-block btn-success btn-equalizer" target="_blank">Print</a>
                                            </div>
                                        </div>
                                    </div> -->
                                        <?php if($payment_voucher['payment_status'] == 'paid') { ?>

                                            <div class="hr-box">
                                                <div class="hr-box-header bg-header-green">
                                                    <span class="hr-registered pull-left">Send Payment Voucher E-Mail</span>
                                                </div>
                                                <div class="hr-box-body hr-innerpadding">
                                                    <div class="hr-setting-page">
                                                        <?php echo form_open(''); ?>
                                                            <input type="hidden" id="voucher_sid" name="voucher_sid" value="<?php echo isset($payment_voucher['sid']) ? $payment_voucher['sid'] : 0; ?>" />
                                                            <ul>
                                                                <li>
                                                                    <label>To Name:</label>
                                                                    <div class="hr-fields-wrap">
                                                                        <?php echo form_input('to_name', set_value('to_name'), 'class="hr-form-fileds"'); ?>
                                                                        <?php echo form_error('to_name'); ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <label>To Email</label>
                                                                    <div class="hr-fields-wrap">
                                                                        <?php echo form_input(array('class'=>'hr-form-fileds','type'=>'email','name'=>'to_email'), set_value('to_email')); ?>
                                                                        <?php echo form_error('to_email'); ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <?php echo form_submit('send_voucher_email','Send Voucher E-Mail',array('class'=>'site-btn'));?>
                                                                </li>
                                                            </ul>
                                                        <?php echo form_close(); ?>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="hr-box">
                                                <div class="hr-box-header bg-header-green">
                                                    <span class="hr-registered pull-left">Voucher Sent History</span>
                                                </div>
                                                <div class="hr-box-body hr-innerpadding">
                                                    <div class="hr-promotions table-responsive">
                                                        <table>
                                                            <thead>
                                                                <tr>
                                                                    <th>Name</th>
                                                                    <th>Email</th>
                                                                    <th>Sent Date</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php   if(!empty($payment_voucher_email_log)) { ?>
                                                                        <?php foreach($payment_voucher_email_log as $pve){ ?>
                                                                                <tr>
                                                                                    <td><?php echo $pve['to_name'];?></td>
                                                                                    <td><?php echo $pve['to_email'];?></td>
                                                                                    <td><?php echo convert_date_to_frontend_format($pve['sent_date']); ?></td>
                                                                                </tr>
                                                                        <?php } ?>
                                                                <?php   } else { ?>
                                                                            <tr><td colspan="3">Not Sent to anyone</td></tr>
                                                                <?php   } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
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
    function mark_payment_voucher(sid, commission_invoice_sid, perform_action, type){
        alertify.confirm(
            'Mark Payment Voucher as '+type,
            'Are you sure you want to mark payment voucher as '+type+'?',
            function () {
                var myRequest;
                var myUrl = '<?php echo base_url("manage_admin/marketing_agencies/ajax_responder"); ?>';
                var myData = { 'perform_action': perform_action, 'sid' : sid, 'commission_invoice_sid' : commission_invoice_sid };

                myRequest = $.ajax({
                                    url: myUrl,
                                    type: 'POST',
                                    data: myData
                                });

                myRequest.done(function(response){
                    if(response == 'success'){
                        window.location = window.location.href;
                    }
                });
            }, function () {
                alertify.error('Cancelled!');
            });
    }
</script>