<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="add-new-company  payment-area-wrp">
                                <form action="" method="POST" id="update_bank_account">
                                    <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                    <input type="hidden" id="sid" name="sid" value="<?php echo $bankAccount['sid'] ? $bankAccount['sid'] : '0' ?>" />
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="heading-title page-title">
                                                <h1 class="page-title">Edit Bank Account Details For (<?php echo getCompanyNameBySid($company_sid) ?>)</h1>
                                                <a class="black-btn pull-right" href="<?php echo base_url('manage_admin/companies'); ?>"><i class="fa fa-long-arrow-left"></i> Back to Companies</a>

                                            </div>
                                        </div>

                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="field-row">
                                                <?php echo form_label('Bank Name <span class="hr-required">*</span>'); ?>
                                                <?php echo form_input('bank_name', set_value('bank_name', $bankAccount['bank_name']), 'class="hr-form-fileds" data-rule-required="true" data-msg-required="Bank Name is Required!"'); ?>
                                                <?php echo form_error('bank_name'); ?>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="field-row">
                                                <?php echo form_label('Account Title <span class="hr-required">*</span>'); ?>
                                                <?php echo form_input('account_title', set_value('account_number', $bankAccount['account_title']), 'class="hr-form-fileds" data-rule-required="true" data-msg-required="Account Title is Required!"'); ?>
                                                <?php echo form_error('account_title'); ?>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="field-row">
                                                <?php echo form_label('Account Type <span class="hr-required">*</span>'); ?><br>
                                                <div class="col-sm-2">
                                                    <label class="control control--radio">
                                                        Checking
                                                        <input type="radio" class="js-account-type validate_error" name="account_type" style="width: 20px;" value="checking" <?php echo $bankAccount['account_type'] == 'checking' ? 'checked="true"' : ''; ?> error_key="account_type" />
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label class="control control--radio">
                                                        Saving
                                                        <input type="radio" class="js-account-type validate_error" name="account_type" style="width: 20px;" value="savings" <?php echo $bankAccount['account_type'] == 'savings' ? 'checked="true"' : ''; ?> error_key="account_type" />
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="field-row">
                                                <?php echo form_label('Account number <span class="hr-required">*</span>'); ?>
                                                <?php echo form_input('account_number', set_value('account_number', $bankAccount['account_number']), 'class="hr-form-fileds" data-rule-required="true" data-msg-required="Account number is Required!"'); ?>
                                                <?php echo form_error('account_number'); ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="field-row">
                                                <label for="number">Bank routing number (ABA number): </label>
                                                <?php echo form_label('Routing number <span class="hr-required">*</span>', ''); ?>
                                                <?php echo form_input('routing_number', set_value('routing_number', $bankAccount['routing_number']), 'class="hr-form-fileds" data-rule-required="true" data-msg-required="Bank routing number is Required!"'); ?>
                                                <?php echo form_error('routing_number'); ?>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-center hr-btn-panel">
                                            <input type="hidden" name="update_card" value="update_card">
                                            <input type="submit" value="Update" onclick="return confirm_bank_account()" class="site-btn">
                                            <a href="<?php echo base_url('manage_admin/companies/manage_company') . '/' . $bankAccount['company_sid']; ?>" class="black-btn">Cancel</a>
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











<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script>
    function confirm_bank_account() {
        $("#update_bank_account").validate({
            ignore: ":hidden:not(select)",
            rules: {
                bank_name: {
                    required: true

                },
                account_title: {
                    required: true
                },
                account_type: {
                    required: true
                },
                account_number: {
                    required: true,
                    number: true,
                    minlength: 9
                },
                routing_number: {
                    required: true,
                    number: true,
                    minlength: 9,
                    maxlength: 9
                }
            },
            messages: {

                bank_name: {
                    required: 'Bank Name is required!'
                },
                account_title: {
                    required: 'Account Title is required!'
                },
                account_type: {
                    required: 'Account Type is required!'
                },
                account_number: {
                    required: 'Account Number is required!'
                },
                routing_number: {
                    required: 'Routing Number is required!'
                }
            },
            submitHandler: function(form) {
                console.log('success');
                form.submit();

            }
        });
    }
</script>