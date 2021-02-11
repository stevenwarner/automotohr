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
                                        <h1 class="page-title"><i class="fa fa-file-o"></i>Company Billing Contacts</h1>
                                        <a href="<?php echo base_url('manage_admin/company_billing_contacts/' . $company_sid);?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Billing Contacts</a>

                                    </div>

                                    <div class="add-new-company">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="heading-title">
                                                    <h1 class="page-title"><?php echo $page_title; ?></h1>
                                                </div>


                                                <form enctype="multipart/form-data" method="POST">
                                                    <?php echo form_hidden('company_billing_contact_sid', $company_billing_contact_sid); ?>
                                                    <?php echo form_hidden('company_sid', $company_sid); ?>
                                                    <?php echo form_hidden('company_name', $company_name); ?>
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <div class="field-row">
                                                                <?php $temp_value = $company_billing_contact_sid != 0 ? $company_billing_contact_record['title'] : '' ; ?>
                                                                <label for="title">Title <span class="hr-required">*</span></label>
                                                                <?php echo form_input('title', set_value('title', $temp_value), 'class="hr-form-fileds"'); ?>
                                                                <?php echo form_error('title'); ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <div class="field-row">
                                                                <?php $temp_value = $company_billing_contact_sid != 0 ? $company_billing_contact_record['contact_name'] : '' ; ?>
                                                                <label for="contact_name">Contact Name <span class="hr-required">*</span></label>
                                                                <?php echo form_input('contact_name', set_value('contact_name', $temp_value), 'class="hr-form-fileds"'); ?>
                                                                <?php echo form_error('contact_name'); ?>
                                                            </div>
                                                        </div>
                                                        <!--
                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                            <div class="field-row field-row-autoheight">
                                                                <?php $temp_value = $company_billing_contact_sid != 0 ? $company_billing_contact_record['billing_address'] : '' ; ?>
                                                                <label for="Location_ZipCode">Billing Address <span class="hr-required">*</span></label>
                                                                <?php echo form_textarea('billing_address', set_value('billing_address', $temp_value), 'class="hr-form-fileds field-row-autoheight"'); ?>
                                                                <?php echo form_error('billing_address'); ?>
                                                            </div>
                                                        </div>
                                                        -->
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <div class="field-row">
                                                                <?php $temp_value = $company_billing_contact_sid != 0 ? $company_billing_contact_record['email_address'] : '' ; ?>
                                                                <label for="Location_ZipCode">Email Address <span class="hr-required">*</span></label>
                                                                <?php echo form_input('email_address', set_value('email_address', $temp_value), 'class="hr-form-fileds"'); ?>
                                                                <?php echo form_error('email_address'); ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <div class="field-row">
                                                                <?php $temp_value = $company_billing_contact_sid != 0 ? $company_billing_contact_record['phone_number'] : '' ; ?>
                                                                <label>Phone Number <span class="hr-required">*</span></label>
                                                                <?php echo form_input('phone_number', set_value('phone_number', $temp_value), 'class="hr-form-fileds"'); ?>
                                                                <?php echo form_error('phone_number'); ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <div class="field-row">
                                                                <?php $temp_value = $company_billing_contact_sid != 0 ? $company_billing_contact_record['cell_number'] : '' ; ?>
                                                                <label>Cell Number</label>
                                                                <?php echo form_input('cell_number', set_value('cell_number', $temp_value), 'class="hr-form-fileds"'); ?>
                                                                <?php echo form_error('cell_number'); ?>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-center hr-btn-panel">
                                                            <input type="submit" class="search-btn" value="Save" name="submit">
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
</div>
