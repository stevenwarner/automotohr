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
                                        <h1 class="page-title"><i class="fa fa-file-code-o"></i><?php echo $page_title; ?></h1>
                                        <a href="<?php echo base_url('manage_admin/system_notification_emails'); ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> System Notification Emails</a>
                                    </div>
                                    <div class="hr-add-new-promotions">
                                        <?php echo form_open('',array('class'=>'form-horizontal'));?>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="field-row">
                                                <label for="CompanyName">Full Name <span class="hr-required">*</span></label>
                                                <?php echo form_input('full_name',set_value('full_name'),'class="hr-form-fileds"'); ?>
                                                <?php echo form_error('full_name'); ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="field-row">
                                                <label for="CompanyName">Email Address <span class="hr-required">*</span></label>
                                                <input type="email" id="email" name="email" value="<?php echo set_value('email'); ?>" class="hr-form-fileds" />
                                                <?php echo form_error('email'); ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="field-row">
                                                <button type="submit" class="btn btn-success">Add New Email Record</button>
                                                &nbsp;
                                                <a href="<?php echo base_url('manage_admin/system_notification_emails'); ?>" class="black-btn">Cancel</a>
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