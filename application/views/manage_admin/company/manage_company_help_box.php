<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
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
                                        <h1 class="page-title"><i class="fa fa-users"></i>Manage Company Help Box
                                        </h1>
                                        <a href="<?php echo base_url('manage_admin/companies/manage_company/' . $company_sid); ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Back to Manage Company</a>
                                    </div>
                                    <div class="edit-email-template">
                                        <p>Fields marked with an asterisk (<span class="hr-required">*</span>) are mandatory</p>
                                        <div class="edit-template-from-main">
                                            <?php echo form_open('', array('class' => 'form-horizontal')); ?>
                                            <ul>
                                                <li>
                                                    <?php echo form_label('Title <span class="hr-required">*</span>', 'helpboxtitle'); ?>
                                                    <div class="hr-fields-wrap">
                                                        <?php echo form_input('helpboxtitle', set_value('helpboxtitle', $contact_info[0]['box_title'] !== '' ? $contact_info[0]['box_title'] : getCompanyNameBySid($company_sid) . ' HR Department'), 'class="hr-form-fileds"');
                                                        echo form_error('helpboxtitle'); ?>
                                                    </div>
                                                </li>
                                                <li>
                                                    <?php echo form_label('Email <span class="hr-required">*</span>', 'helpboxemail'); ?>
                                                    <div class="hr-fields-wrap">
                                                        <?php echo form_input('helpboxemail', set_value('helpboxemail', $contact_info[0]['box_support_email'] != '' ? $contact_info[0]['box_support_email'] : ''), 'class="hr-form-fileds"');
                                                        echo form_error('helpboxemail'); ?>
                                                    </div>
                                                </li>
                                                <li>
                                                    <?php echo form_label('Phone Number <span class="hr-required">*</span>', 'helpboxphonenumber'); ?>
                                                    <div class="hr-fields-wrap">
                                                        <?php echo form_input('helpboxphonenumber', set_value('helpboxphonenumber', $contact_info[0]['box_support_phone_number'] != '' ? $contact_info[0]['box_support_phone_number'] : ''), 'class="hr-form-fileds"');
                                                        echo form_error('helpboxphonenumber'); ?>
                                                    </div>
                                                </li>
                                                <li>
                                                    <?php echo form_label('Status <span class="hr-required">*</span>', 'helpboxstatus'); ?>
                                                    <div class="hr-fields-wrap">
                                                        <select name="helpboxstatus" class="hr-form-fileds" id="helpboxstatus">
                                                            <option value="0" <?php echo $contact_info[0]['box_status'] == 0 ? 'selected' : '' ?>>Inactive</option>
                                                            <option value="1" <?php echo $contact_info[0]['box_status'] == 1 ? 'selected' : '' ?>>Active</option>
                                                        </select>
                                                    </div>
                                                </li>



                                                <li>
                                                    <input type="hidden" name="sid" value="<?php echo $company_sid; ?>">
                                                    <a href="<?php echo base_url('manage_admin/companies/manage_company/' . $company_sid); ?>" class="search-btn">Cancel</a>
                                                    <input type="submit" name="submit" value="Save" class="search-btn">
                                                    <?php //echo form_submit('submit', 'Save profile', 'class="btn btn-primary btn-lg btn-block"');   
                                                    ?>
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