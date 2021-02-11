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
                                                                <label>Facebook Link</label>
                                                                <div class="hr-fields-wrap">
                                                                    <?php echo form_input(array('class' => 'hr-form-fileds', 'name' => 'facebook_url'), set_value('facebook_url', $data['facebook_url'])); ?>
                                                                    <?php echo form_error('facebook_url'); ?>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <label>Twitter Link</label>
                                                                <div class="hr-fields-wrap">
                                                                    <?php echo form_input(array('class' => 'hr-form-fileds', 'name' => 'twitter_url'), set_value('twitter_url', $data['twitter_url'])); ?>
                                                                    <?php echo form_error('twitter_url'); ?>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <label>Google+ Link</label>
                                                                <div class="hr-fields-wrap">
                                                                    <?php echo form_input(array('class' => 'hr-form-fileds', 'name' => 'google_plus_url'), set_value('google_plus_url', $data['google_plus_url'])); ?>
                                                                    <?php echo form_error('google_plus_url'); ?>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <label>LinkedIn Link</label>
                                                                <div class="hr-fields-wrap">
                                                                    <?php echo form_input(array('class' => 'hr-form-fileds', 'name' => 'linkedin_url'), set_value('linkedin_url', $data['linkedin_url'])); ?>
                                                                    <?php echo form_error('linkedin_url'); ?>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <label>Glass Door Link</label>
                                                                <div class="hr-fields-wrap">
                                                                    <?php echo form_input(array('class' => 'hr-form-fileds', 'name' => 'glassdoor_url'), set_value('glassdoor_url', $data['glassdoor_url'])); ?>
                                                                    <?php echo form_error('glassdoor_url'); ?>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <label>Youtube Link</label>
                                                                <div class="hr-fields-wrap">
                                                                    <?php echo form_input(array('class' => 'hr-form-fileds', 'name' => 'youtube_url'), set_value('youtube_url', $data['youtube_url'])); ?>
                                                                    <?php echo form_error('youtube_url'); ?>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <label>Instagram Link</label>
                                                                <div class="hr-fields-wrap">
                                                                    <?php echo form_input(array('class' => 'hr-form-fileds', 'name' => 'instagram_url'), set_value('instagram_url', $data['instagram_url'])); ?>
                                                                    <?php echo form_error('instagram_url'); ?>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <?php echo form_submit('', 'Save', array('class' => 'site-btn')); ?>
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