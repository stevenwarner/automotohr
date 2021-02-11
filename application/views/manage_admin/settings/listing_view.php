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
                                                        <h1 class="page-title"><i class="fa fa-cog"></i><?php echo $page_title; ?></h1>
                                                    </div>
                                                    <div class="hr-setting-page">
                                                        <?php echo form_open(''); ?>
                                                            <ul>
                                                                <li>
                                                                    <label>Site Title</label>
                                                                    <div class="hr-fields-wrap">
                                                                        <?php   echo  form_input(array('class'=>'hr-form-fileds','name'=>'site_title'), set_value('site_title',$data['site_title']));?>
                                                                        <?php   echo form_error('site_title'); ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <label>Admin Email</label>
                                                                    <div class="hr-fields-wrap">
                                                                        <?php   echo  form_input(array('class'=>'hr-form-fileds','type'=>'email','name'=>'admin_email'), set_value('admin_email',$data['admin_email'])) ?>
                                                                        <?php   echo form_error('admin_email'); ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <label>Send Payment To</label>
                                                                    <div class="hr-fields-wrap">
                                                                        <textarea style="padding:10px; height:200px;" class="hr-form-fileds" name="payment_to"><?php echo set_value('payment_to', $data['payment_to']); ?></textarea>
                                                                        <?php echo form_error('payment_to'); ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <label>From Name</label>
                                                                    <div class="hr-fields-wrap">
                                                                        <?php echo  form_input(array('class'=>'hr-form-fileds','name'=>'mail_send_from'), set_value('mail_send_from', $data['mail_send_from']))  ?>
                                                                        <?php echo form_error('mail_send_from'); ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <label>Mail Send E-mail</label>
                                                                    <div class="hr-fields-wrap">
                                                                        <?php echo  form_input(array('class'=>'hr-form-fileds','type'=>'email','name'=>'mail_send_email'), set_value('mail_send_email', $data['mail_send_email']))  ?>
                                                                        <?php echo form_error('mail_send_email'); ?>
                                                                 </div>
                                                                </li>
                                                                <li>
                                                                    <label>Mail Signature</label>
                                                                    <div class="hr-fields-wrap">
                                                                        <textarea style="padding:10px; height:200px;" class="hr-form-fileds" name="mail_signature"><?php echo set_value('mail_signature', $data['mail_signature']); ?></textarea>
                                                                        <?php echo form_error('mail_signature'); ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <?php echo form_submit('setting_submit','Save',array('class'=>'site-btn'));?>
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