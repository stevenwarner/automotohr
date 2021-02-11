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
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?>
                                        </h1>
                                        <a href="<?php echo base_url('manage_admin/companies/manage_company/' . $company_sid);?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Back to Manage Company</a>
                                    </div>
                                    <div class="edit-email-template">
                                        <p>Fields marked with an asterisk (<span class="hr-required">*</span>) are mandatory</p>
                                        <div class="edit-template-from-main">
                                            <?php echo form_open('', array('class' => 'form-horizontal')); ?>
                                            <ul>
                                                <li>
                                                    <?php echo form_label('Sales Executive Phone Number <span class="hr-required">*</span>', 'SalesPhoneNumber'); ?>
                                                    <div class="hr-fields-wrap">
                                                        <?php   echo form_input('SalesPhoneNumber', set_value('SalesPhoneNumber', sizeof($contact_info)>0 ? $contact_info[0]['exec_sales_phone_no']:''), 'class="hr-form-fileds"');
                                                        echo form_error('SalesPhoneNumber'); ?>
                                                    </div>
                                                </li>
                                                <li>
                                                    <?php echo form_label('Sales Executive Email <span class="hr-required">*</span>', 'SalesEmail'); ?>
                                                    <div class="hr-fields-wrap">
                                                        <?php   echo form_input('SalesEmail', set_value('SalesEmail', sizeof($contact_info)>0 ? $contact_info[0]['exec_sales_email']:''), 'class="hr-form-fileds"');
                                                        echo form_error('SalesEmail'); ?>
                                                    </div>
                                                </li>
                                                <li>
                                                    <?php echo form_label('Technical Support Phone Number <span class="hr-required">*</span>', 'TechnicalSupportPhoneNumber'); ?>
                                                    <div class="hr-fields-wrap">
                                                        <?php   echo form_input('TechnicalSupportPhoneNumber', set_value('TechnicalSupportPhoneNumber', sizeof($contact_info)>0 ? $contact_info[0]['tech_support_phone_no']:''), 'class="hr-form-fileds"');
                                                        echo form_error('TechnicalSupportPhoneNumber'); ?>
                                                    </div>
                                                </li>
                                                <li>
                                                    <?php echo form_label('Technical Support Email <span class="hr-required">*</span>', 'TechnicalSupportEmail'); ?>
                                                    <div class="hr-fields-wrap">
                                                        <?php   echo form_input('TechnicalSupportEmail', set_value('TechnicalSupportEmail', sizeof($contact_info)>0 ? $contact_info[0]['tech_support_email']:''), 'class="hr-form-fileds"');
                                                        echo form_error('TechnicalSupportEmail'); ?>
                                                    </div>
                                                </li>



                                                <li>
                                                    <input type="hidden" name="sid" value="<?php echo $company_sid; ?>">
                                                    <a href="<?php echo base_url('manage_admin/companies/manage_company/' . $company_sid);?>" class="search-btn">Cancel</a>
                                                    <input type="submit" name="submit" value="Save" class="search-btn">
                                                    <?php //echo form_submit('submit', 'Save profile', 'class="btn btn-primary btn-lg btn-block"');   ?>
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