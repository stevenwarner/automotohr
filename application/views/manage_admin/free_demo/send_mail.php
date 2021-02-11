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
                                        <input type="hidden" id="demo_sid" name="demo_sid" value="<?php echo $sid; ?>" />
                                        <ul>
                                            <li>
                                                <label>Email <span class="hr-required">*</span></label>				
                                                <div class="hr-fields-wrap">
                                                    <?php echo form_input('email', set_value('email', $enquirer_email), 'class="hr-form-fileds"');?>  
                                                    <?php echo form_error('email'); ?>                                                    
                                                </div>
                                            </li>
                                            <li>
                                                <label>
                                                    Templates
                                                </label>
                                                <div class="hr-fields-wrap">
                                                    <select class="form-control js-email-template">
                                                        <option value="null">[Select a template]</option>
                                                        <?php if(sizeof($admin_templates)) { ?>
                                                        <?php   foreach ($admin_templates as $k0 => $v0) { ?>
                                                        <option value="<?=$v0['id'];?>"><?=$v0['templateName'];?></option>
                                                        <?php   } ?>
                                                        <?php } ?>
                                                    </select>
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
                                                <div class="hr-fields-wrap cs-fields-wrap-3">
                                                    <div class="offer-letter-help-widget pull-right" style="top: 0;">
                                                        <div class="tags-area pull-left">
                                                            <br />
                                                            <strong>Tags :</strong>
                                                            <ul class="tags">
                                                                <li>{{first_name}}</li>
                                                                <li>{{last_name}}</li>
                                                                <li>{{phone}}</li>
                                                                <li>{{email}}</li>
                                                                <li>{{company_name}}</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="hr-fields-wrap cs-fields-wrap-9">
                                                    <textarea style="padding:10px; height:200px;" class="hr-form-fileds ckeditor" name="message" id="txtMessage"><?php echo set_value('message'); ?></textarea>
                                                    <?php echo form_error('message'); ?>
                                                </div>
                                            </li>
                                            <li>
                                                <?php if(empty($sid)){ ?>
                                                    <a class="site-btn" href="<?php echo site_url('manage_admin/free_demo');?>">Cancel</a> 
                                                <?php } else if ($this->uri->segment(2) == 'demo_admin_reply') { ?> 
                                                    <a href="<?php echo site_url('manage_admin/enquiry_message_details/'.$sid);?>" class="black-btn">Cancel</a>
                                                <?php } else if ($this->uri->segment(2) == 'referred_clients') { ?>
                                                    <a href="<?php echo site_url('manage_admin/referred_clients/enquiry_message_details/'.$sid);?>" class="black-btn">Cancel</a>
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

<script>
    $(function(){
        var email_templates = <?=@json_encode($admin_templates);?>;

        $('.js-email-template').change(function(event) {
            var obj = indexFinder('id', $(this).val());
            if(!obj) return false;
            //
            $('input[name="subject"]').val(obj['subject']);
            // $('textarea[name="message"]').val(obj['body']);
            CKEDITOR.instances['txtMessage'].setData(obj['body']);
        });

        function indexFinder(searchIndex, searchValue){
            var i = 0,
            arrLength = email_templates.length;
            for(i; i < arrLength; i++){
                if(email_templates[i][searchIndex] == searchValue) return email_templates[i];
            }
            return false;
        }
    })
</script>

<style>
    .tags-area strong{ padding: 0 10px;}
    .offer-letter-help-widget{ padding-bottom: 10px; }
    .tags-area ul.tags{ padding: 0 0; }
    .tags-area ul.tags li{ width: auto; background-color: #f8f8f8; border: 1px solid #d9d8d5;border-radius: 50px;display: inline-block;height: auto !important;margin: 10px 0 0 10px !important;overflow: hidden;padding: 7px;text-align: center; }

    .hr-fields-wrap.cs-fields-wrap-9{ width: 55.3%; margin-right: 10px; }
    .hr-fields-wrap.cs-fields-wrap-3{ width: 22%; }

    @media only screen and (max-width: 600px) {
        .hr-fields-wrap.cs-fields-wrap-9{ width: 100%; margin-right: 0; }
        .hr-fields-wrap.cs-fields-wrap-3{ width: 100%; margin-bottom: 10px; }
    }
</style>