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
                                    <input type="hidden" id="sid" name="sid" value="<?php echo $taxInfo['sid'] ? $taxInfo['sid'] : '0' ?>" />
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="heading-title page-title">
                                                <h1 class="page-title">Edit Federal tax information For (<?php echo getCompanyNameBySid($company_sid)?>)</h1>
                                                <a class="black-btn pull-right" href="<?php echo base_url('manage_admin/companies'); ?>"><i class="fa fa-long-arrow-left"></i> Back to Companies</a>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <p> <strong> Your entity type and legal name of your company. You can finde this info on your <a href="https://www.irs.gov/businesses/small-businesses-self-employed/lost-or-misplaced-your-ein" target="_blank">FEIN assignment form (form CP575).</a> We need this to file and pay your taxes correctly. </strong></p>
                                            <div class="field-row">
                                                <?php echo form_label('Federal EIN <span class="hr-required">*</span>'); ?><br>
                                                Your company's Feferal Employeer Identification Number (EIN). if you don't have one , please <a href="https://www.irs.gov/businesses/small-businesses-self-employed/employer-id-numbers" target="_blank"><strong>apply online</strong></a>
                                                <?php echo form_input('ssn', set_value('ssn', $taxInfo['ssn']), 'class="hr-form-fileds" data-rule-required="true" data-msg-required="Bank Name is Required!"'); ?>
                                                <?php echo form_error('ssn'); ?>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12"><br>
                                            <div class="field-row">
                                                <?php echo form_label('Tax Payer Type<span class="hr-required">*</span>'); ?> <br>
                                                Some common types are Sole Prop,LLC,and S-Corp

                                                <select class="form-control jsTaxPayerType" name="tax_payer_type">
                                                    <option value="">[Select]</option>
                                                    <option value="S-Corporation" <?= !empty($taxInfo) &&  $taxInfo['tax_payer_type'] === "S-Corporation" ? 'selected="selected"' : ''; ?>>S-Corporation</option>
                                                    <option value="C-Corporation" <?= !empty($taxInfo) &&  $taxInfo['tax_payer_type'] === "C-Corporation" ? 'selected="selected"' : ''; ?>>C-Corporation</option>
                                                    <option value="Sole proprietor" <?= !empty($taxInfo) &&  $taxInfo['tax_payer_type'] === "Sole proprietor" ? 'selected="selected"' : ''; ?>>Sole Proprietor</option>
                                                    <option value="LLC" <?= !empty($taxInfo) &&  $taxInfo['tax_payer_type'] === "LLC" ? 'selected="selected"' : ''; ?>>LLC</option>
                                                    <option value="LLP" <?= !empty($taxInfo) &&  $taxInfo['tax_payer_type'] === "LLP" ? 'selected="selected"' : ''; ?>>LLP</option>
                                                    <option value="Limited partnership" <?= !empty($taxInfo) &&  $taxInfo['tax_payer_type'] === "Limited partnership" ? 'selected="selected"' : ''; ?>>Limited Partnership</option>
                                                    <option value="Co-ownership" <?= !empty($taxInfo) &&  $taxInfo['tax_payer_type'] === "Co-ownership" ? 'selected="selected"' : ''; ?>>Co-ownership</option>
                                                    <option value="Association" <?= !empty($taxInfo) &&  $taxInfo['tax_payer_type'] === "Association" ? 'selected="selected"' : ''; ?>>Association</option>
                                                    <option value="Trusteeship" <?= !empty($taxInfo) &&  $taxInfo['tax_payer_type'] === "Trusteeship" ? 'selected="selected"' : ''; ?>>Trusteeship</option>
                                                    <option value="General partnership" <?= !empty($taxInfo) &&  $taxInfo['tax_payer_type'] === "General partnership" ? 'selected="selected"' : ''; ?>>General Partnership</option>
                                                    <option value="Joint venture" <?= !empty($taxInfo) &&  $taxInfo['tax_payer_type'] === "Joint venture" ? 'selected="selected"' : ''; ?>>Joint Venture</option>
                                                    <option value="Non-Profit" <?= !empty($taxInfo) &&  $taxInfo['tax_payer_type'] === "Non-Profit" ? 'selected="selected"' : ''; ?>>Non-Profit</option>
                                                </select>
                                                <?php echo form_error('tax_payer_type'); ?>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12"><br>
                                            <div class="field-row">
                                                <?php echo form_label('Federal Filling Form<span class="hr-required">*</span>'); ?><br>

                                                To learn more about the different Federal Tax Form filing for payroll, please review the <a href="https://www.irs.gov/newsroom/employers-should-you-file-form-944-or-941" target="_blank"><strong>IRS Website.</strong></a>
                                                <select class="form-control jsTaxFillingForm" name="filing_form">
                                                    <option value="">[Select]</option>
                                                    <option value="941" <?= !empty($taxInfo) &&  $taxInfo['filing_form'] === "941" ? 'selected="selected"' : ''; ?>>941 (Quarterly federal tax return)</option>
                                                    <option value="944" <?= !empty($taxInfo) &&  $taxInfo['filing_form'] === "944" ? 'selected="selected"' : ''; ?>>944 (Annual federal tax return)</option>
                                                </select>
                                                <?php echo form_error('filing_form'); ?>

                                            </div>
                                        </div>

                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12"><br>
                                            <div class="field-row">
                                                <?php echo form_label('Legal Entitlty Name <span class="hr-required">*</span>'); ?><br>
                                                Make sure this is your legal name, not DBA
                                                <?php echo form_input('legal_name', set_value('legal_name', $taxInfo['legal_name']), 'class="hr-form-fileds" data-rule-required="true" data-msg-required="Legal Entitlty Name is Required!"'); ?>
                                                <?php echo form_error('legal_name'); ?>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-center hr-btn-panel">
                                            <input type="hidden" name="update_card" value="update_card">
                                            <input type="submit" value="Update" onclick="return confirm_bank_account()" class="site-btn">
                                            <a href="<?php echo base_url('manage_admin/companies/manage_company') . '/' . $taxInfo['company_sid']; ?>" class="black-btn">Cancel</a>
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
                ssn: {
                    required: true

                },
                tax_payer_type: {
                    required: true
                },
                filing_form: {
                    required: true
                },
                legal_name: {
                    required: true
                }

            },
            messages: {

                ssn: {
                    required: 'Federal EIN is required!'
                },
                tax_payer_type: {
                    required: 'Tax Payer Type is required!'
                },
                filing_form: {
                    required: 'Federal Filling Form is required!'
                },
                legal_name: {
                    required: 'Legal Entitlty Name is required!'
                }
            },
            submitHandler: function(form) {
                form.submit();

            }
        });
    }
</script>