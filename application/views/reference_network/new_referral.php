<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top'); ?>

                            <div class="dashboard-conetnt-wrp">
                                <div class="form-title-section">
                                    <div class="margin-top">
                                        <h2><?php echo $title; ?></h2>
                                        <div class="form-btns">
                                            <a class="submit-btn" href="<?php echo base_url('my_referral_network'); ?>" >Cancel</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="compose-message">
                                    <div class="universal-form-style-v2">
                                        <ul>
                                            <?php echo form_open('', array('enctype' => 'multipart/form-data', 'method' => 'post')); ?>
                                            <input type="hidden" id="action" name="action" />
                                            <li class="form-col-100 autoheight">
                                                <?php echo form_label('Refer This Job', 'job_sid');?>
                                                <div class="hr-select-dropdown">
                                                    <?php echo form_dropdown(array('class' => 'invoice-fields', 'id' => 'job_sid', 'name' => 'job_sid'), $jobs, array(), set_select('job_sid')); ?>
                                                </div>
                                                <?php echo form_error('job_sid'); ?>
                                            </li>
                                            <li class="form-col-100 autoheight">
                                                <?php echo form_label('Refer To', 'referred_to');?>
                                                <?php echo form_input(array('class' => 'invoice-fields', 'id' => 'referred_to', 'name' => 'referred_to'), set_value('referred_to')); ?>
                                                <?php echo form_error('referred_to'); ?>
                                            </li>
                                            <li class="form-col-100 autoheight">
                                                <?php echo form_label('Email Address', 'reference_email');?>
                                                <?php echo form_input(array('class' => 'invoice-fields', 'id' => 'reference_email', 'name' => 'reference_email'), set_value('reference_email')); ?>
                                                <?php echo form_error('reference_email'); ?>
                                            </li>
                                            <li class="form-col-100 autoheight">
                                                <?php echo form_label('Personal Message', 'personal_message');?>
                                                <?php echo form_textarea(array('class' => 'invoice-fields-textarea', 'id' => 'personal_message', 'name' => 'personal_message'), set_value('personal_message')); ?>
                                                <?php echo form_error('personal_message'); ?>
                                            </li>
                                            <li class="form-col-100 autoheight">
                                                <div class="form-btns">
                                                    <?php echo form_submit(array('value' => 'Send', 'class' => 'form-btn', 'type' => 'submit'));?>
                                                </div>
                                            </li>
                                            <?php echo form_close();?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $this->load->view('manage_employer/employee_management/profile_right_menu_personal'); ?>
            </div>
        </div>
    </div>
</div>