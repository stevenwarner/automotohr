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
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="dashboard-content">
                                        <div class="dash-inner-block">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="heading-title page-title">
                                                        <span class="page-title"><i class="fa fa-cog"></i><?php echo $page_title; ?></span>
                                                        <a href="<?php echo base_url('manage_admin/marketing_agencies/payment_voucher/' . $payment_voucher['commission_invoice_sid']); ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Back to Voucher</a>
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
                                                                        </tbody>
                                                                    </table>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="hr-box">
                                                        <div class="hr-box-header bg-header-green">
                                                            <span class="hr-registered pull-left">Modify Payment Details</span>
                                                        </div>
                                                        <div class="hr-box-body hr-innerpadding">
                                                            <div class="hr-setting-page">
                                                                <?php echo form_open(''); ?>
                                                                    <ul>
                                                                        <li>
                                                                            <label>Payment Amount</label>
                                                                            <div class="hr-fields-wrap">
                                                                                <?php   echo form_input('paid_amount', set_value('paid_amount', $payment_voucher['paid_amount']), 'class="hr-form-fileds"'); ?>
                                                                                <?php   echo form_error('paid_amount'); ?>
                                                                            </div>
                                                                        </li>
                                                                        <li>
                                                                            <label>Payment Method</label>
                                                                            <div class="hr-fields-wrap">
                                                                                <?php   echo form_input('payment_method', set_value('payment_method', $payment_method), 'class="hr-form-fileds"'); ?>
                                                                                <?php   echo form_error('payment_method'); ?>
                                                                            </div>
                                                                        </li>
                                                                        <li>
                                                                            <label>Payment Reference</label>
                                                                            <div class="hr-fields-wrap">
                                                                                <?php   echo  form_input(array('class'=>'hr-form-fileds','type'=>'text','name'=>'payment_reference'), set_value('payment_reference',$payment_reference)); ?>
                                                                                <?php   echo form_error('payment_reference'); ?>
                                                                            </div>
                                                                        </li>
                                                                        <li>
                                                                            <label>Payment Description</label>
                                                                            <div class="hr-fields-wrap">
                                                                                <textarea style="padding:10px; height:200px;" class="hr-form-fileds" name="payment_description"><?php echo set_value('payment_description', $payment_description); ?></textarea>
                                                                                <?php   echo form_error('payment_description'); ?>
                                                                            </div>
                                                                        </li>
                                                                        <li>
                                                                            <a class="black-btn btn full-on-small" href="<?php echo base_url('manage_admin/marketing_agencies/payment_voucher/' . $payment_voucher['commission_invoice_sid']); ?>">Cancel</a>
                                                                                <?php   echo form_submit('setting_submit','Update Payment Details',array('class'=>'site-btn'));?>
                                                                        </li>
                                                                    </ul>
                                                                <?php echo form_close(); ?>
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
            </div>
        </div>
    </div>
</div>