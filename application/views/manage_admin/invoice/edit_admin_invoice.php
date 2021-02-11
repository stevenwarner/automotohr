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
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-file-excel-o"></i><?php echo $page_title; ?></h1>
                                        <a href="<?php echo base_url('manage_admin/invoice/view_admin_invoice/'.$invoice['sid']); ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Back to Invoices</a>
                                    </div>
                                    <div class="add-new-company">
                                        <div class="heading-title">
                                            <h1 class="page-title"><?php echo $company_info['CompanyName'];?></h1> 
                                        </div>
                                    </div>
                                    <div class="edit-email-template">
                                        <!--<p>Fields marked with an asterisk (<span class="hr-required">*</span>) are mandatory</p>-->
                                        <div class="edit-template-from-main" >
                                            <?php echo form_open_multipart('', array('class'=>'form-horizontal')); ?>
                                                <ul>
                                                    <li>
                                                        <?php echo form_label('Created On','created'); ?>
                                                        <div class="hr-fields-wrap">
                                                            <?php   $created = strtotime(str_replace('-', '/', $invoice['created'])); ?>
                                                                    <input class="hr-form-fileds datepicker" type="text" name="created"
                                                                        value="<?php echo set_value('created', date('m-d-Y', $created)); ?>"/>
                                                           <?php echo form_error('created'); ?>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <?php echo form_label('Payment Status','payment_status'); ?>
                                                        <div class="hr-fields-wrap">
                                                        <?php $payment_status = $invoice['payment_status']; ?>
                                                                <div class="hr-select-dropdown">
                                                                    <select class="invoice-fields" name="payment_status">
                                                                        <option <?php if ($payment_status == 'unpaid') { ?>selected="selected" <?php } ?> value="unpaid" >Unpaid</option>
                                                                        <option <?php if ($payment_status == 'paid') { ?>selected="selected" <?php } ?> value="unpaid" >Paid</option>
                                                                    </select>
                                                                </div>     
                                                        <?php echo form_error('payment_status'); ?>
                                                        </div>
                                                    </li>
                                                    <?php if ($payment_status == 'paid') { ?>
                                                        <li>
                                                            <?php echo form_label('Payment Date','payment_date'); ?>
                                                            <div class="hr-fields-wrap">
                                                                <?php   $payment_date = strtotime(str_replace('-', '/', $invoice['payment_date'])); ?>
                                                                        <input class="hr-form-fileds datepicker" type="text" name="payment_date"
                                                                            value="<?php echo set_value('payment_date', date('m-d-Y', $payment_date)); ?>"/>
                                                               <?php echo form_error('payment_date'); ?>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <?php echo form_label('Payment Method','payment_method'); ?>
                                                            <div class="hr-fields-wrap">
                                                            <?php $payment_method = $invoice['payment_method']; ?>
                                                                    <div class="hr-select-dropdown">
                                                                        <select class="invoice-fields" name="payment_method">
                                                                            <option <?php if (strtolower($payment_method) == 'check') { ?>selected="selected" <?php } ?> value="Check" >Check</option>
                                                                            <option <?php if (strtolower($payment_method) == 'cash') { ?>selected="selected" <?php } ?> value="cash" >Cash</option>
                                                                            <option <?php if (strtolower($payment_method) == 'credit-card') { ?>selected="selected" <?php } ?> value="credit-card" >Credit Card</option>
                                                                        </select>
                                                                    </div>     
                                                            <?php echo form_error('payment_method'); ?>
                                                            </div>
                                                        </li>
                                                        <?php if (strtolower($payment_method) == 'check') { ?>
                                                                <li>
                                                                    <?php echo form_label('Check Number','check_number'); ?>
                                                                    <div class="hr-fields-wrap">
                                                                        <?php   $check_number = $invoice['check_number']; ?>
                                                                                <input class="hr-form-fileds datepicker" type="text" name="check_number"
                                                                                    value="<?php echo set_value('check_number', $check_number); ?>"/>
                                                                       <?php echo form_error('check_number'); ?>
                                                                    </div>
                                                                </li>
                                                        <?php } ?>
                                                        
                                                        <?php if (strtolower($payment_method) == 'credit-card') { ?>
                                                                <li>
                                                                    <?php echo form_label('Credit Card Number','credit_card_number'); ?>
                                                                    <div class="hr-fields-wrap">
                                                                        <?php   $credit_card_number = $invoice['credit_card_number']; ?>
                                                                                <input class="hr-form-fileds" type="text" name="credit_card_number"
                                                                                    value="<?php echo set_value('credit_card_number', $credit_card_number); ?>"/>
                                                                       <?php echo form_error('credit_card_number'); ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <?php echo form_label('CC Type','credit_card_type'); ?>
                                                                    <div class="hr-fields-wrap">
                                                                    <?php $credit_card_type = $invoice['credit_card_type']; ?>
                                                                            <div class="hr-select-dropdown">
                                                                                <select class="invoice-fields" name="credit_card_type">
                                                                                    <option <?php if (strtolower($credit_card_type) == 'visa') { ?>selected="selected" <?php } ?> value="visa" >Visa</option>
                                                                                    <option <?php if (strtolower($credit_card_type) == 'mastercard') { ?>selected="selected" <?php } ?> value="mastercard" >Mastercard</option>
                                                                                    <option <?php if (strtolower($credit_card_type) == 'discover') { ?>selected="selected" <?php } ?> value="discover" >Discover</option>
                                                                                    <option <?php if (strtolower($credit_card_type) == 'amex') { ?>selected="selected" <?php } ?> value="amex" >Amex</option>
                                                                                </select>
                                                                            </div>     
                                                                    <?php echo form_error('payment_method'); ?>
                                                                    </div>
                                                                </li>
                                                        <?php } ?>
                                                        <li>
                                                            <?php echo form_label('Payment Description','payment_description'); ?>
                                                            <div class="hr-fields-wrap">
                                                                <?php   $payment_description = $invoice['payment_description']; ?>
                                                                        <input class="hr-form-fileds datepicker" type="text" name="payment_description"
                                                                            value="<?php echo set_value('payment_description', $payment_description); ?>"/>
                                                               <?php echo form_error('payment_description'); ?>
                                                            </div>
                                                        </li>
                                                    <?php } ?>
                                                        <li>
                                                            <input type="hidden" name="sid" value="<?php echo $invoice['sid']; ?>">
                                                        </li>
                                                </ul>
                                                <div class="row">
                                                    <div class="col-xs-12 text-right">
                                                        <input type="submit" name="submit" value="Update" class="btn btn-success">
                                                        <a href="<?php echo base_url('manage_admin/invoice/view_admin_invoice/'.$invoice['sid']); ?>" class="btn black-btn">Cancel</a>
                                                    </div>
                                                </div>
                                            <?php echo form_close();?>
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
    $(document).ready(function(){
        $('.datepicker').datepicker({dateFormat: 'mm-dd-yy'}).val();
    });
</script>
