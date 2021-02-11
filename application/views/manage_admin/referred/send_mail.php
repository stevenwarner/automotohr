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
                                    </div>
                                    <div class="hr-add-new-promotions">
                                        <?php echo form_open_multipart('', array('class' => 'form-horizontal', 'id' => 'send_mail')); ?>
                                        <ul>
                                            <li>
                                                <label>Email <span class="hr-required">*</span></label>				
                                                <div class="hr-fields-wrap">
                                                    <?php echo form_input('email', set_value('email', $enquirer_email), 'class="hr-form-fileds"');?>  
                                                    <?php echo form_error('email'); ?>                                                    
                                                </div>
                                            </li>
                                            <li>
                                                <label>Subject <span class="hr-required">*</span></label>				
                                                <div class="hr-fields-wrap">
                                                    <?php echo form_input('subject', set_value('subject', $enquirer_subject), 'class="hr-form-fileds"');?>
                                                    <?php echo form_error('subject'); ?>                                                                                                      
                                                </div>
                                            </li>
                                            <li>
                                                <label>Message <span class="hr-required">*</span></label>
                                                <div class="hr-fields-wrap">
                                                    <textarea style="padding:10px; height:200px;" class="hr-form-fileds ckeditor" name="message"><?php echo set_value('message'); ?></textarea>
                                                    <?php echo form_error('message'); ?>
                                                </div>
                                            </li>
                                            <li>
                                                <?php if(empty($sid)){ ?>
                                                    <a class="site-btn" href="<?php echo site_url('manage_admin/referred');?>">Cancel</a> 
                                                <?php } else { ?> 
                                                    <a href="<?php echo site_url('manage_admin/referred_clients/view_details/'.$sid);?>" class="site-btn">Cancel</a>
                                                <?php } ?>
                                                <input type="submit" value="Send Mail" class="site-btn">
                                            </li>
                                        </ul>
                                        <?php form_close() ?>
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