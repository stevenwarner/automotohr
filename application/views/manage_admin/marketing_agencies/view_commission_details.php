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
                                        <a href="<?php echo base_url('manage_admin/marketing_agencies/manage_commissions/' . $commission_invoice['marketing_agency_sid']); ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Commissions</a>
                                    </div>
                                    <div class="hr-box">
                                        <div class="hr-box-header bg-header-green">
                                            <span class="hr-registered pull-left">Commission Details</span>
                                        </div>
                                        <div class="hr-box-body hr-innerpadding">
                                            <div class="table-responsive">
                                                <?php if (!empty($commission_invoice)) { ?>
                                                    <table class="table table-bordered table-hover table-stripped">
                                                        <tbody>
                                                            <tr>
                                                                <th class="col-xs-4">Date</th>
                                                                <td class="col-xs-8"><?php echo convert_date_to_frontend_format($commission_invoice['created']); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="col-xs-4">Commission Invoice Number</th>
                                                                <td class="col-xs-8"><?php echo $commission_invoice['invoice_number']; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="col-xs-4">Company Name</th>
                                                                <td class="col-xs-8"><?php echo ucwords($commission_invoice['company_name']); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="col-xs-4">Against Invoice Number</th>
                                                                <?php if ($commission_invoice['invoice_origin'] == 'super_admin') { ?>
                                                                    <td class="col-xs-8"><b><?php echo STORE_CODE . '-' . str_pad($commission_invoice['invoice_sid'], 6, 0, STR_PAD_LEFT); ?></b></td>
                                                                <?php } else if ($commission_invoice['invoice_origin'] == 'employer_portal') { ?>
                                                                    <td class="col-xs-8"><b><?php echo $commission_invoice['invoice_sid']; ?></b></td>
                                                                <?php } ?>
                                                            </tr>
                                                            <tr>
                                                                <th class="col-xs-4">Against Invoice Type</th>
                                                                <?php if ($commission_invoice['invoice_origin'] == 'super_admin') { ?>
                                                                    <td class="col-xs-8">Admin Invoice</td>
                                                                <?php } else if ($commission_invoice['invoice_origin'] == 'employer_portal') { ?>
                                                                    <td class="col-xs-8">Marketplace Invoice</td>
                                                                <?php } ?>
                                                            </tr>
                                                            <tr>
                                                                <th class="col-xs-4">Subtotal</th>
                                                                <td class="col-xs-8">$ <?php echo number_format($commission_invoice['value'], 2); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="col-xs-4">Discount Percentage</th>
                                                                <td class="col-xs-8"><?php echo number_format($commission_invoice['discount_percentage'], 2); ?> %</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="col-xs-4">Discount Amount</th>
                                                                <td class="col-xs-8">$ <?php echo number_format($commission_invoice['discount_amount'], 2); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="col-xs-4">Total</th>
                                                                <?php if ($commission_invoice['discount_amount'] > 0) { ?>
                                                                    <td class="col-xs-8">$ <?php echo number_format($commission_invoice['total_after_discount'], 2); ?></td>
                                                                <?php } else { ?>
                                                                    <td class="col-xs-8">$ <?php echo number_format($commission_invoice['value'], 2); ?></td>
                                                                <?php } ?>
                                                            </tr>
                                                            <tr>
                                                                <th class="col-xs-4">Total Commission Payable</th>
                                                                <?php if ($commission_invoice['discount_amount'] > 0) { ?>
                                                                    <td class="col-xs-8">$ <?php echo number_format($commission_invoice['total_commission_after_discount'], 2); ?></td>
                                                                <?php } else { ?>
                                                                    <td class="col-xs-8">$ <?php echo number_format($commission_invoice['commission_value'], 2); ?></td>
                                                                <?php } ?>
                                                            </tr>
                                                            <tr>
                                                                <th class="col-xs-4">Commission Payment Status</th>
                                                                <td class="col-xs-8">
                                                                    <?php if ($commission_invoice['payment_status'] == 'unpaid') { ?>
                                                                        <span class="text-danger">
                                                                            <?php echo ucwords($commission_invoice['payment_status']); ?>
                                                                        </span>
                                                                    <?php } else if ($commission_invoice['payment_status'] == 'paid') { ?>
                                                                        <span class="text-success">
                                                                            <?php echo ucwords($commission_invoice['payment_status']); ?>
                                                                        </span>
                                                                    <?php } ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th class="col-xs-4">&nbsp;</th>
                                                                <td class="col-xs-8">
                                                                    <?php if ($commission_invoice['payment_status'] == 'unpaid') { ?>
                                                                        <span class="pull-left">
                                                                            <!--<button type="button" class="btn btn-success btn-sm" onclick="mark_commission_invoice_as_paid(<?php /*echo $commission_invoice['sid']; */?>);">Mark As Paid</button>-->
                                                                        </span>
                                                                        <span class="pull-left" style="margin-left: 10px;">
                                                                            <form id="form_recalculate_commission" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                <input type="hidden" id="perform_action" name="perform_action" value="recalculate_commission" />
                                                                                <input type="hidden" id="commission_invoice_sid" name="commission_invoice_sid" value="<?php echo $commission_invoice['sid']; ?>" />
                                                                                <button type="submit" class="btn btn-success btn-sm">Re Calculate Commission</button>
                                                                            </form>
                                                                        </span>
                                                                    <?php } else if ($commission_invoice['payment_status'] == 'paid') { ?>
                                                                        <!--<button type="button" class="btn btn-success btn-sm disabled" disabled="disabled">Mark As Paid</button>-->
                                                                    <?php } ?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                <?php } else { ?>
                                                    <div class="text-center">
                                                        <div class="no-data">
                                                            Invoice not Found!
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
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function mark_commission_invoice_as_paid(commission_invoice_sid){
        alertify.confirm(
            'Mark Commission As Paid',
            'Are you sure you want to mark this Commission Invoice as Commission Paid?',
            function () {
                var myRequest;
                var myUrl = '<?php echo base_url("manage_admin/marketing_agencies/ajax_responder"); ?>';
                var myData = { 'perform_action': 'mark_commission_invoice_as_commission_paid', 'commission_invoice_sid' : commission_invoice_sid };

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