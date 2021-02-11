<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/settings_left_menu_config'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php echo $title; ?></span>
                            </div>
                        </div>
                        <div class="dashboard-conetnt-wrp">
                            <?php echo form_open('', array('id' => 'sociallinks')); ?>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="create-job-wrap">
                                    <div class="universal-form-style-v2 enable-social-profiles">
                                        <ul>
                                            <li class="form-col-100">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                        <label class="control control--checkbox">
                                                            Enable Facebook Profile
                                                            <input class="select-domain" type="checkbox" name="enable_facebook_footer" value="1" <?php echo ($company['enable_facebook_footer'] == 1 ? ' checked="checked" ' : ''); ?> />
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-6"><?php echo form_input('facebook_footer', set_value('facebook_footer', $company['facebook_footer']), 'class="invoice-fields"'); ?>
                                                    </div>   
                                                </div>                     
                                            </li>
                                            <li class="form-col-100">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                        <label class="control control--checkbox">
                                                            Enable Twitter profile
                                                            <input class="select-domain" type="checkbox" name="enable_twitter_footer" value="1" <?php echo ($company['enable_twitter_footer'] == 1 ? ' checked="checked" ' : ''); ?> />
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-6"><?php echo form_input('twitter_footer', set_value('twitter_footer', $company['twitter_footer']), 'class="invoice-fields"'); ?>
                                                    </div>
                                                </div>                        
                                            </li>
                                            <li class="form-col-100">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                    <label class="control control--checkbox">
                                                        Enable Google Plus
                                                        <input class="select-domain" type="checkbox" name="enable_google_footer" value="1" <?php echo ($company['enable_google_footer'] == 1 ? ' checked="checked" ' : ''); ?> />
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-6"><?php echo form_input('google_footer', set_value('google_footer', $company['google_footer']), 'class="invoice-fields"'); ?>
                                                    </div>
                                                </div>                       
                                            </li>
                                            <li class="form-col-100">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                    <label class="control control--checkbox">
                                                        Enable LinkedIn profile
                                                        <input class="select-domain" type="checkbox" name="enable_linkedin_footer" value="1" <?php echo ($company['enable_linkedin_footer'] == 1 ? ' checked="checked" ' : ''); ?> />
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-6"><?php echo form_input('linkedin_footer', set_value('linkedin_footer', $company['linkedin_footer']), 'class="invoice-fields"'); ?>
                                                   </div>    
                                                </div>                      
                                            </li> 
                                            <li class="form-col-100">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                    <label class="control control--checkbox">
                                                        Enable Youtube profile
                                                        <input class="select-domain" type="checkbox" name="enable_youtube_footer" value="1" <?php echo ($company['enable_youtube_footer'] == 1 ? ' checked="checked" ' : ''); ?> />
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-6"><?php echo form_input('youtube_footer', set_value('youtube_footer', $company['youtube_footer']), 'class="invoice-fields"'); ?>
                                                    </div>  
                                                </div>                      
                                            </li> 
                                            <li class="form-col-100">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                    <label class="control control--checkbox">
                                                        Enable Instagram profile
                                                        <input class="select-domain" type="checkbox" name="enable_instagram_footer" value="1" <?php echo ($company['enable_instagram_footer'] == 1 ? ' checked="checked" ' : ''); ?> />
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-6"><?php echo form_input('instagram_footer', set_value('instagram_footer', $company['instagram_footer']), 'class="invoice-fields"'); ?>
                                                    </div>  
                                                </div>                      
                                            </li> 
                                            <li class="form-col-100">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                    <label class="control control--checkbox">
                                                        Enable Glassdoor profile
                                                        <input class="select-domain" type="checkbox" name="enable_glassdoor_footer" value="1" <?php echo ($company['enable_glassdoor_footer'] == 1 ? ' checked="checked" ' : ''); ?> />
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-6"><?php echo form_input('glassdoor_footer', set_value('glassdoor_footer', $company['glassdoor_footer']), 'class="invoice-fields"'); ?>
                                                    </div>  
                                                </div>                      
                                            </li>
                                            <li class="form-col-100">
                                                <div class="btn-wrp">
                                                    <input type="submit" value="Save" class="submit-btn">
                                                    <input type="button" value="Cancel" class="submit-btn btn-cancel" onClick="document.location.href = '<?= base_url('my_settings') ?>'" />
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
<?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>