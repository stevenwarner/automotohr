<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<link rel="stylesheet" href="<?= base_url() ?>assets/alertifyjs/css/alertify.min.css"/>
<link rel="stylesheet" href="<?= base_url() ?>assets/alertifyjs/css/themes/default.min.css"/>
<div class="job-detail-banner">
    <div class="container-fluid">
        <div class="detail-banner-caption">
            <header class="heading-title">
                <h1 class="section-title"><?php echo $heading_title; ?></h1>
            </header>
        </div>
    </div>
</div>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<div class="main">
    <div class="container-fluid">
        <div class="row">					
            <div class="col-md-12">
                <div class="text-column" style="width:100%;">
                    <?php $this->load->view($theme_name . '/_parts/admin_flash_message'); ?>
                </div>
                <div class="join-our-network">
                    <?php $this->load->view('common/jobs_fair_common'); ?>
                    
                    <?php   if (isset($formpost['country'])) {
                                $country_id = $formpost['country'];
                            } else {
                                $country_id = 227;
                            } ?>
                    <div class="form-column">
                        <div class="join-from">
                            <ul style="position: relative; padding: 8px;">
                            <?php if($form_type == 'default') { ?>
                                <form method="post" name="job_fair_default" id="job_fair_default" action="" class="form" enctype="multipart/form-data">
                                    <li class="form-col-left">
                                        <label>First Name<span class="staric">*</span></label>
                                        <input type="text" name="first_name" placeholder="" value="<?php if(isset($formpost['first_name'])){ echo $formpost['first_name']; } ?>" class="join-from-fields" required>
                                        <?php echo form_error('first_name'); ?>
                                    </li>
                                    <li class="form-col-right">
                                        <label>Last Name<span class="staric">*</span></label>
                                        <input type="text" name="last_name" placeholder="" value="<?php if(isset($formpost['last_name'])){ echo $formpost['last_name']; } ?>" class="join-from-fields" required>
                                        <?php echo form_error('last_name'); ?>
                                    </li>
                                    <li>
                                        <label>Email Address<span class="staric">*</span></label>
                                        <input type="email" name="email" placeholder="" value="<?php if(isset($formpost['email'])){ echo $formpost['email']; } ?>" class="join-from-fields" required>
                                        <?php echo form_error('email'); ?>
                                    </li>
                                    <li class="form-col-left">
                                        <label>Country<span class="staric">*</span></label>
                                        <select class="join-from-fields" name="country" onchange="getStates(this.value, '<?php echo $states; ?>')" required data-rule-required="true">
                                            <?php foreach ($active_countries as $active_country) { ?>
                                                    <option value="<?php echo $active_country['sid']; ?>" 
                                                    <?php if ($active_country['sid'] == $country_id) { 
                                                            echo "selected";
                                                            $country_id = $active_country['sid'];
                                                            } ?>><?php echo $active_country['country_name']; ?>
                                                    </option>
                                            <?php } ?>
                                        </select>
                                        <?php echo form_error('country'); ?>
                                    </li>
                                    <li class="form-col-right">
                                        <label>State<span class="staric">*</span></label>
                                        <select class="join-from-fields" name="state" id="state" required="required" data-rule-required="true">
                                            <?php   if (empty($country_id)) {
                                                        echo '<option value="">Select State </option>';
                                                    } else {
                                                        foreach ($active_states[$country_id] as $active_state) {
                                                            echo '<option value="' . $active_state['sid'] . '">' . $active_state['state_name'] . '</option>';
                                                        }
                                                    }
                                                ?>
                                        </select>
                                        <?php echo form_error('state'); ?>
                                    </li>
                                    <li class="form-col-left">
                                        <label>City<span class="staric">*</span></label>
                                        <input type="text" name="city" placeholder="" value="<?php if(isset($formpost['city'])){ echo $formpost['city']; } ?>" class="join-from-fields" required>
                                        <?php echo form_error('city'); ?>
                                    </li>
                                    <li class="form-col-right">
                                        <label>Best Contact Number</label>
                                            <input 
                                                type="text" 
                                                id="PhoneNumber" 
                                                name="phone_number" 
                                                placeholder=""
                                                value="<?=isset($formpost['phone_number']) ?  $formpost['phone_number'] : ''; ?>"
                                                class="join-from-fields" />
                                        <?php echo form_error('phone_number'); ?>
                                    </li>
                                    <!-- <li>
                                        <label>Desired Job Title<span class="staric">*</span></label>
                                        <input type="text" name="desired_job_title" placeholder="" value="<?php //if(isset($formpost['desired_job_title'])){ echo $formpost['desired_job_title']; } ?>" class="join-from-fields" required>
                                        <?php //echo form_error('desired_job_title'); ?> 
                                    </li>-->
                                    <li>
                                        <label>College / University Name<span class="staric">*</span></label>
                                        <input type="text" name="college_university_name" placeholder="" value="<?php if(isset($formpost['college_university_name'])){ echo $formpost['college_university_name']; } ?>" class="join-from-fields" required>
                                        <?php echo form_error('college_university_name'); ?> 
                                    </li>
                                    <li>
                                        <label>Upload a Resume (.pdf .docx .doc .rtf .jpg .jpe .jpeg .png .gif) </label>
                                        <div class="join-from-fields choose-file">
                                            <div class="file-name" id="name_resume">Please Select</div>
                                            <input class="choose-file-filed bg-color" type="file" name="resume" id="resume" onchange="check_file()">
                                            <a class="choose-btn bg-color" href="javascript:;">choose file</a>
                                        </div>
                                        <?php echo form_error('resume'); ?>
                                    </li>
                                    
                                    <li class="custom_video_source">  
                                        <label for="video_source" class="text_transform_reset">Video Source: <span style="font-weight: 500;">Upload your video resume or just tell us a little bit about yourself.<br>Video can be uploaded using YouTube, Vimeo or directly from your computer or mobile device using the Upload feature (MOV, MP3, MP4 files)</span></label>
                                        <label class="control control--radio video_source_margins">
                                            <?php echo NO_VIDEO; ?>
                                            <input checked="checked" class="video_source bg-color" type="radio" id="no_video" name="video_source" value="no_video">
                                            <div class="control__indicator"></div>
                                        </label>
                                        <label class="control control--radio video_source_margins">
                                            <?php echo YOUTUBE_VIDEO; ?>
                                            <input class="video_source bg-color" type="radio" id="video_source_youtube" name="video_source" value="youtube">
                                            <div class="control__indicator"></div>
                                        </label>
                                        <label class="control control--radio video_source_margins">
                                            <?php echo VIMEO_VIDEO; ?>
                                            <input class="video_source" type="radio" id="video_source_vimeo" name="video_source" value="vimeo">
                                            <div class="control__indicator"></div>
                                        </label>
                                        <label class="control control--radio video_source_margins">
                                            <?php echo UPLOAD_VIDEO; ?>
                                            <input class="video_source" type="radio" id="video_source_upload" name="video_source" value="uploaded">
                                            <div class="control__indicator"></div>
                                        </label>
                                    </li>
                                    <li id="yt_vm_video_container">
                                        <label for="YouTube_Video" id="label_youtube">Youtube Video:</label>
                                        <label for="Vimeo_Video" id="label_vimeo" style="display: none">Vimeo Video:</label>
                                        <input type="text" name="yt_vm_video_url" class="join-from-fields" id="yt_vm_video_url">
                                    </li>  
                                    <li id="up_video_container" style="display: none">
                                        <label>Upload Video <span class="hr-required">*</span></label>
                                        <div class="join-from-fields choose-file">
                                            <span class="ile-name" id="name_video_upload"></span>
                                            <input class="choose-file-filed bg-color" type="file" name="video_upload" id="video_upload" onchange="check_upload_video('video_upload')" >
                                            <a class="choose-btn bg-color" href="javascript:;">Choose Video</a>
                                        </div>
                                    </li>
                                    <li class="textarea">
                                        <label>What types of jobs are you interested in? (max 128 characters)<span class="staric">*</span></label>
                                        <textarea class="join-from-fields" name="job_interest_text" id="job_interest_text" maxlength="128" onkeyup="check_length()" required=""><?php if(isset($formpost['job_interest_text'])){ echo $formpost['job_interest_text']; } ?></textarea>
                                        <p id="remaining_text" class="info">128 characters left!</p>
                                        <?php echo form_error('job_interest_text'); ?> 
                                    </li>
                                    <li class="custom_video_source">
                                        <label>Upload a Profile Picture (.jpg .jpe .jpeg .png .gif) </label>
                                        <div class="join-from-fields choose-file">
                                            <div class="file-name" id="name_profile_picture">Please Select</div>
                                            <input class="choose-file-filed bg-color" type="file" name="profile_picture" id="profile_picture" onchange="check_profile_picture_file()">
                                            <a class="choose-btn bg-color" href="javascript:;">choose file</a>
                                        </div>
                                        <?php echo form_error('profile_picture'); ?>
                                    </li>

                                    <li>
                                <div class="g-recaptcha" data-callback="googleCaptchaChecker" data-sitekey="<?= getCreds('AHR')->GOOGLE_CAPTCHA_API_KEY_V2; ?>"></div>
                               <label id='captchaerror' style="display: none; float: none !important;color: #CC0000 !important;font-weight: 400;margin: 0 !important;" >Empty/Invalid Captcha </label>
                            </li>

                                   <!--  <li>
                                        <div id="RecaptchaField"></div>
                                    </li> -->
                                    <li><br>
                                        <div class="loader_cover" style="display:none; background: #ccc; width: 100%; position: absolute; top: 0; bottom: 0; left: 0; right: 0; opacity: 0.7;">
                                            <div class="loader" style="display: none; width: 100px; position: absolute; top: 0; bottom: 0; left: 0; right: 0; margin: auto;"></div>
                                        </div>
                                        <input type="submit" name="action" value="Submit" class="join-btn bg-color" id="add_edit_submit" onclick="validate_form()">
                                    </li>
                                </form>
                            <?php } else { ?>
                                <form method="post" name="job_fair_custom" id="job_fair_custom" action="" class="form" enctype="multipart/form-data">
                                    <li class="form-col-left">
                                        <label>First Name<span class="staric">*</span></label>
                                        <input type="text" name="first_name" placeholder="" value="<?php if(isset($formpost['first_name'])){ echo $formpost['first_name']; } ?>" class="join-from-fields" required>
                                        <?php echo form_error('first_name'); ?>
                                    </li>
                                    <li class="form-col-right">
                                        <label>Last Name<span class="staric">*</span></label>
                                        <input type="text" name="last_name" placeholder="" value="<?php if(isset($formpost['last_name'])){ echo $formpost['last_name']; } ?>" class="join-from-fields" required>
                                        <?php echo form_error('last_name'); ?>
                                    </li>
                                    <li>
                                        <label>Email Address<span class="staric">*</span></label>
                                        <input type="email" name="email" placeholder="" value="<?php if(isset($formpost['email'])){ echo $formpost['email']; } ?>" class="join-from-fields" required>
                                        <?php echo form_error('email'); ?>
                                    </li>
                                    
                                    <?php if(!empty($job_fair_default_questions)) {
                                        foreach($job_fair_default_questions as $default_data_keys => $default_data_fields) { 
                                            $field_id = $default_data_fields['field_id'];
                                            
                                            if($default_data_fields['field_priority'] == 'optional' && $field_id != 'desired_job_title') { 
                                                if($default_data_fields['question_type'] == 'string') { ?>
                                                    <li>
                                                        <label><?php echo $default_data_fields['field_name']; if($default_data_fields['is_required'] == 1) { ?><span class="staric">*</span> <?php } ?></label>
                                                        <?php  if($default_data_fields['field_id'] == 'email') {$input_type = 'email';} else {$input_type = 'text';} ?>
                                                            <!-- // Check for phone fields  -->
                                                            
                                                            <input 
                                                            type="<?php echo $input_type; ?>" 
                                                            name="<?php echo $field_id; ?>" 
                                                            placeholder="" 
                                                            value="<?php if(isset($formpost[$field_id])){ echo $formpost[$field_id]; } ?>" 
                                                            class="join-from-fields  " <?php if($default_data_fields['is_required'] == 1) { ?>required <?php } ?>>
                                                            
                                                            <?php echo form_error($field_id); ?>
                                                    </li>
                                                <?php } else if($default_data_fields['question_type'] == 'list') { 
                                                    if($default_data_fields['field_id'] == 'country') { ?>
                                                    <li>
                                                        <label><?php echo $default_data_fields['field_name']; if($default_data_fields['is_required'] == 1) { ?><span class="staric">*</span> <?php } ?></label>
                                                        <select class="join-from-fields" name="<?php echo $field_id; ?>" onchange="getStates(this.value, '<?php echo $states; ?>')"  <?php if($default_data_fields['is_required'] == 1) { ?>required="required" <?php } ?>>
                                                            <?php foreach ($active_countries as $active_country) { ?>
                                                                    <option value="<?php echo $active_country['sid']; ?>" 
                                                                    <?php if ($active_country['sid'] == $country_id) { 
                                                                            echo "selected";
                                                                            $country_id = $active_country['sid'];
                                                                            } ?>><?php echo $active_country['country_name']; ?>
                                                                    </option>
                                                            <?php } ?>
                                                        </select>
                                                        <?php echo form_error($field_id); ?>
                                                    </li>
                                                <?php } else if($default_data_fields['field_id'] == 'state') { ?>
                                                    <li>
                                                        <label><?php echo $default_data_fields['field_name']; if($default_data_fields['is_required'] == 1) { ?><span class="staric">*</span> <?php } ?></label>
                                                        <select class="join-from-fields" name="<?php echo $field_id; ?>" id="state"  <?php if($default_data_fields['is_required'] == 1) { ?>required="required" <?php } ?>>
                                                            <?php   if (empty($country_id)) {
                                                                        echo '<option value="">Select State </option>';
                                                                    } else {
                                                                        foreach ($active_states[$country_id] as $active_state) {
                                                                            echo '<option value="' . $active_state['sid'] . '">' . $active_state['state_name'] . '</option>';
                                                                        }
                                                                    }
                                                                ?>
                                                        </select>
                                                        <?php echo form_error($field_id); ?>
                                                    </li>
                                                <?php }                     
                                                } else if($default_data_fields['question_type'] == 'file') { ?>
                                                    <?php if ($default_data_fields['field_id'] == 'resume') { ?>
                                                        <li class="custom_video_source">
                                                            <label><?php echo $default_data_fields['field_name']; if($default_data_fields['is_required'] == 1) { ?><span class="staric">*</span> <?php } ?></label>
                                                            <div class="join-from-fields choose-file">
                                                                <div class="file-name" id="name_resume">Please Select</div>
                                                                <input class="choose-file-filed bg-color" type="file" name="<?php echo $field_id; ?>" id="resume" onchange="check_file()" <?php if($default_data_fields['is_required'] == 1) { ?>required="required" data-rule-required="true"<?php } ?>>
                                                                <a class="choose-btn bg-color" href="javascript:;">choose file</a>
                                                            </div>
                                                            <?php echo form_error($field_id); ?>
                                                        </li>
                                                    <?php } else if ($default_data_fields['field_id'] == 'profile_picture') { ?>
                                                        <li class="custom_video_source">
                                                            <label><?php echo $default_data_fields['field_name']; if($default_data_fields['is_required'] == 1) { ?><span class="staric">*</span> <?php } ?></label>
                                                            <div class="join-from-fields choose-file">
                                                                <div class="file-name" id="name_profile_picture">Please Select</div>
                                                                <input class="choose-file-filed bg-color" type="file" name="<?php echo $field_id; ?>" id="profile_picture" onchange="check_profile_picture_file()" <?php if($default_data_fields['is_required'] == 1) { ?>required="required" data-rule-required="true"<?php } ?>>
                                                                <a class="choose-btn bg-color" href="javascript:;">choose file</a>
                                                            </div>
                                                            <?php echo form_error($field_id); ?>
                                                        </li>
                                                    <?php } ?>
                                                <?php } else if($default_data_fields['question_type'] == 'custom_video_resume') { ?>
                                                    <li class="custom_video_source"> 
                                                        <label for="video_source" class="text_transform_reset">Video Source: <span style="font-weight: 500;">Upload your video resume or just tell us a little bit about yourself.<br>Video can be uploaded using YouTube, Vimeo or directly from your computer or mobile device using the Upload feature (MOV, MP3, MP4 files)</span></label>
                                                        <label class="control control--radio video_source_margins">
                                                            <?php echo NO_VIDEO; ?>
                                                            <input checked="checked" class="video_source bg-color" type="radio" id="no_video" name="video_source" value="no_video">
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                        <label class="control control--radio video_source_margins">
                                                            <?php echo YOUTUBE_VIDEO; ?>
                                                            <input class="video_source bg-color" type="radio" id="video_source_youtube" name="video_source" value="youtube">
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                        <label class="control control--radio video_source_margins">
                                                            <?php echo VIMEO_VIDEO; ?>
                                                            <input class="video_source" type="radio" id="video_source_vimeo" name="video_source" value="vimeo">
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                        <label class="control control--radio video_source_margins">
                                                            <?php echo UPLOAD_VIDEO; ?>
                                                            <input class="video_source" type="radio" id="video_source_upload" name="video_source" value="uploaded">
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </li>
                                                    <li id="yt_vm_video_container">
                                                        <label for="YouTube_Video" id="label_youtube">Youtube Video:</label>
                                                        <label for="Vimeo_Video" id="label_vimeo" style="display: none">Vimeo Video:</label>
                                                        <input type="text" name="yt_vm_video_url" class="join-from-fields" id="yt_vm_video_url">
                                                    </li>  
                                                    <li id="up_video_container" style="display: none">
                                                        <label>Upload Video <span class="hr-required">*</span></label>
                                                        <div class="join-from-fields choose-file">
                                                            <span class="ile-name" id="name_video_upload"></span>
                                                            <input class="choose-file-filed bg-color" type="file" name="video_upload" id="video_upload" onchange="check_upload_video('video_upload')" >
                                                            <a class="choose-btn bg-color" href="javascript:;">Choose Video</a>
                                                        </div>
                                                    </li>
                                            <?php }//question_type
                                            } // optional fields
                                        } // loop
                                    } // job_fair_default_questions 
                                    
                                    if(!empty($job_fair_custom_questions)) {
                                        foreach($job_fair_custom_questions as $custom_data_keys => $custom_data_fields) { 
                                                $field_id = $custom_data_fields['field_id'];
                                            
                                            if($custom_data_fields['field_priority'] == 'optional') { 
                                                if($custom_data_fields['question_type'] == 'string') { ?>
                                                    <li>
                                                        <label><?php echo $custom_data_fields['field_name']; if($custom_data_fields['is_required'] == 1) { ?><span class="staric">*</span> <?php } ?></label>
                                                        <?php if($custom_data_fields['field_id'] == 'email') {$input_type = 'email';} else {$input_type = 'text';} ?>
                                                            <?php if( $custom_data_fields['is_phone_field'] ) { ?>
                                                                <div class="input-group cs-input-group">
                                                                    <div class="input-group-addon">
                                                                        <span class="input-addon-text">+1</span>
                                                                    </div>
                                                            <?php } ?>
                                                            <input type="<?php echo $input_type; ?>" name="<?php echo $field_id; ?>" placeholder="" value="<?php if(isset($formpost[$field_id])){ echo $formpost[$field_id]; } ?>" class="join-from-fields <?=$custom_data_fields['is_phone_field'] === 1 ? 'js-phone' : '';?>" <?php if($custom_data_fields['is_required'] == 1) { ?>required data-rule-required="true"<?php } ?>>
                                                            <?php if( $custom_data_fields['is_phone_field'] ) { ?>
                                                                    </div>
                                                            <?php } ?>
                                                            <?php echo form_error($field_id); ?>
                                                    </li>
                                                <?php } else if($custom_data_fields['question_type'] == 'list') { 
                                                        $questions_sid = $custom_data_fields['sid']; ?>
                                                    <li>
                                                        <label><?php echo $custom_data_fields['field_name']; if($custom_data_fields['is_required'] == 1) { ?><span class="staric">*</span> <?php } ?></label>
                                                        <select class="join-from-fields" name="<?php echo $field_id; ?>" <?php if($custom_data_fields['is_required'] == 1) { ?>required="required" data-rule-required="true"<?php } ?>>
                                                            <option value="">Please Select</option>
                                                        <?php   foreach ($job_fair_question_options as $question_option) {
                                                                    if ($question_option['questions_sid'] == $questions_sid) { ?>
                                                                            <option value="<?php echo $question_option['value']; ?>">
                                                                                <?php echo $question_option['value']; ?>
                                                                            </option>
                                                        <?php   } ?>
                                                        <?php       } ?>
                                                        </select>
                                                        <?php echo form_error($field_id); ?>
                                                    </li>                                                    
                                                <?php } else if($custom_data_fields['question_type'] == 'boolean') { ?>
                                                    <li>
                                                        <label><?php echo $custom_data_fields['field_name']; if($custom_data_fields['is_required'] == 1) { ?><span class="staric">*</span> <?php } ?></label>
                                                         <label class="control control--radio">
                                                            Yes
                                                            <input type="radio" name="<?php echo $field_id; ?>" value="Yes" <?php if ($custom_data_fields['is_required'] == 1) { ?> required data-rule-required="true" <?php } ?>>
                                                            <div class="control__indicator"></div>
                                                         </label>
                                                        <label class="control control--radio">
                                                            No
                                                            <input type="radio" name="<?php echo $field_id; ?>" value="No" <?php if ($custom_data_fields['is_required'] == 1) { ?> required data-rule-required="true" <?php } ?>>
                                                            <div class="control__indicator"></div>
                                                         </label>
                                                     </li>
                                                <?php } else if($custom_data_fields['question_type'] == 'multilist') { 
                                                        $questions_sid = $custom_data_fields['sid']; ?>
                                                     <li class="autoheight">
                                                        <label><?php echo $custom_data_fields['field_name']; if($custom_data_fields['is_required'] == 1) { ?><span class="staric">*</span> <?php } ?></label>
                                                <?php   foreach ($job_fair_question_options as $key => $question_option) { 
                                                            if ($question_option['questions_sid'] == $questions_sid) { ?>
                                                            <label class="control control--checkbox">
                                                                <?php echo $question_option['value']; ?>
                                                                <input type="checkbox" name="<?php echo $field_id; ?>[]" id="squared<?php echo $key; ?>" value="<?php echo $question_option['value']; ?>" <?php if ($custom_data_fields['is_required'] == 1) { ?> required data-rule-required="true" <?php } ?>>
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                <?php       }
                                                        } ?>
                                                     </li>
                                            <?php } //question_type
                                            } // optional fields
                                        } // loop
                                    } // job_fair_custom_questions ?>
                                  
                                    <li>
                                <div class="g-recaptcha" data-callback="googleCaptchaChecker" data-sitekey="<?= getCreds('AHR')->GOOGLE_CAPTCHA_API_KEY_V2; ?>"></div>
                                <label id='captchaerror' style="display: none; float: none !important;color: #CC0000 !important;font-weight: 400;margin: 0 !important;" >Empty/Invalid Captcha </label>
                            </li>
                               <br>
                                    <li>
                                        <div class="loader_cover" style="display:none; background: #ccc; width: 100%; position: absolute; top: 0; bottom: 0; left: 0; right: 0; opacity: 0.7;">
                                            <div class="loader" style="display: none; width: 100px; position: absolute; top: 0; bottom: 0; left: 0; right: 0; margin: auto;"></div>
                                        </div>
                                        <input type="submit" name="action" value="Submit" id="add_edit_submit" class="join-btn bg-color">
                                    </li>
                                </form> 
                            <?php } // custom form ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script language="JavaScript" type="text/javascript" src="<?php echo base_url('assets/theme-1/js/jquery.validate.min.js'); ?>"></script>
<script src="<?= base_url() ?>assets/alertifyjs/alertify.min.js"></script>
<!-- <script src="https://www.google.com/recaptcha/api.js?onload=CaptchaCallback&render=explicit" async defer></script> -->

<script language="JavaScript" type="text/javascript">
    // var CaptchaCallback = function() {
    //     var sitekey = '<?php echo $this->config->item('google_key'); ?>';
    //     grecaptcha.render('RecaptchaField', {'sitekey' : sitekey});
    // };

    $(document).ready(function () {
        $('#yt_vm_video_container').hide();
        $('#up_video_container').hide();  
    });

    var googleCaptchaToken = null;
    
    function googleCaptchaChecker(don) {
        googleCaptchaToken = don;
    }

    function validate_form() {
        $("#job_fair_default").validate({
            ignore: ":hidden:not(select)",
            messages: {
                first_name: "Please provide first name",
                last_name: "Please provide last name",
                email: "Please provide valid email address",
                city: "City required!",
                resume: "Resume required!",
                zipcode: "Zipcode required!",
                country: "Country required!",
                //check_box: "You must agree to our Terms & Condition and Privacy Policy",
                college_university_name: "College University name required",
                job_interest_text: "types Of Jobs Interested required",
                profile_picture: "profile_picture required"
            },
            submitHandler: function (form) {

                if(googleCaptchaToken === null){
                    
                    $("#captchaerror").show();
                     return;
                 }

                var video_source = $('input[name="video_source"]:checked').val();
                var flag = 0;
                    
                if (video_source  == 'youtube') {
                    if ($('#yt_vm_video_url').val() != '') {
                        var p = /(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.)?youtube\.com\/watch(?:\.php)?\?.*v=)([a-zA-Z0-9\-_]+)/;
                        
                        if (!$('#yt_vm_video_url').val().match(p)) {
                            alertify.error('Not a Valid Youtube URL');
                            flag = 1;
                            return false;
                        }
                    } else {
                        alertify.error('Video URL is required');
                        flag = 1;
                        return false;
                    }
                }
                
                if (video_source  == 'vimeo') {
                    if ($('#yt_vm_video_url').val() != '') {
                        var myurl = "<?= base_url() ?>home/validate_vimeo";
                        $.ajax({
                            type: "POST",
                            url: myurl,
                            data: {url: $('#yt_vm_video_url').val()},
                            async: false,
                            success: function (data) {
                                if (data == false) {
                                    alertify.error('Not a Valid Vimeo URL');
                                    flag = 1;
                                    return false;
                                }
                            },
                            error: function (data) {
                            }
                        });
                    } else {
                        alertify.error('Video URL is required');
                        flag = 1;
                        return false;
                    }
                }

                if (video_source  == 'uploaded') {
                    if ($('#video_upload').val() == '') {
                        alertify.error('Please Choose Video');
                        flag = 1;
                        return false;
                    }   
                }
        
                if(flag != 1){ 
                    $("#add_edit_submit").attr("disabled", true);  
                    $("#add_edit_submit").prop("disabled", true);
                    $('#add_edit_submit').addClass("greyed_out");
                    $('.loader_cover').show();
                    $('.loader').show();

                    form.submit();

                    // var recaptcha = $("#g-recaptcha-response").val();

                    // if (recaptcha === "") {
                    //     $('.loader_cover').hide();
                    //     $('.loader').hide();
                    //     alertify.alert('Please check the recaptcha');
                    //     return;
                    // } else {
                    //     form.submit();
                    // }
                    
                }
            }
        });
    }

    function check_file() {
        var fileName = $("#resume").val();
        if (fileName.length > 0) {
            $('#name_resume').html(fileName.substring(12, fileName.length));
            var ext = fileName.split('.').pop();
            var lower_ext = ext.toLowerCase();
            if (lower_ext != "pdf" && lower_ext != "doc" && lower_ext != "docx" && lower_ext != "jpg" && lower_ext != "jpe" && lower_ext != "jpeg" && lower_ext != "png" && lower_ext != "gif") {
                $("#resume").val(null);
                $('#name_resume').html('Only (.pdf, .doc, .docx, .jpg, .jpe, .jpeg, .png, .gif) allowed! Please Select again.');
            }
        } else {
            $('#name_resume').html('Please Select');
        }
    }

    function check_profile_picture_file() {
        var fileName = $("#profile_picture").val();
        if (fileName.length > 0) {
            $('#name_profile_picture').html(fileName.substring(12, fileName.length));
            var ext = fileName.split('.').pop();
            var lower_ext = ext.toLowerCase();
            if (lower_ext != "jpg" && lower_ext != "jpe" && lower_ext != "jpeg" && lower_ext != "png" && lower_ext != "gif") {
                $("#profile_picture").val(null);
                $('#name_profile_picture').html('Only (.jpg, .jpe, .jpeg, .png, .gif) allowed! Please Select again.');
            }
        } else {
            $('#name_profile_picture').html('Please Select');
        }
    }

    function check_length() {
        var text_allowed = 128;
        var user_text = $('#job_interest_text').val();
        var newLines = user_text.match(/(\r\n|\n|\r)/g);
        var addition = 0;
        
        if (newLines != null) {
            addition = newLines.length;
        }
        
        var text_length = user_text.length + addition;
        var text_left = text_allowed - text_length;
        $('#remaining_text').html(text_left + ' characters left!');
    }

    function getStates(val, states) {
        states = jQuery.parseJSON(states);
        var html = '';
        
        if (val == '') {
            $('#state').html('<option value="">Select State</option>');
        } else {
            allstates = states[val];
            for (var i = 0; i < allstates.length; i++) {
                var id = allstates[i].sid;
                var name = allstates[i].state_name;
                html += '<option value="' + id + '">' + name + '</option>';
            }
            $('#state').html(html);
        }
    }

    $('.video_source').on('click', function () {
        var selected = $(this).val();

        if(selected == 'youtube'){
            $('#label_youtube').show();
            $('#label_vimeo').hide();
            $('#yt_vm_video_container').show();
            $('#up_video_container').hide();
        } else if (selected == 'vimeo') {
            $('#label_youtube').hide();
            $('#label_vimeo').show();
            $('#yt_vm_video_container').show();
            $('#up_video_container').hide();
        } else if(selected == 'uploaded') {
            $('#yt_vm_video_container').hide();
            $('#up_video_container').show();
        } else if(selected == 'no_video')  {
            $('#yt_vm_video_container').hide();
            $('#up_video_container').hide();
        }
    });

    function check_upload_video(val) {
        var fileName = $("#" + val).val();

        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
            var ext = fileName.split('.').pop();
            var ext = ext.toLowerCase();

            if (val == 'video_upload') {
                if (ext != "mp4" && ext != "m4a" && ext != "m4v" && ext != "f4v" && ext != "f4a" && ext != "m4b" && ext != "m4r" && ext != "f4b" && ext != "mov") {
                    $("#" + val).val(null);
                    alertify.error("Please select a valid video format.");
                    $('#name_' + val).html('<p class="red">Only (.mp4, .m4a, .m4v, .f4v, .f4a, .m4b, .m4r, .f4b, .mov) allowed!</p>');
                    return false;
                } else {
                    var file_size = Number(($("#" + val)[0].files[0].size/1024/1024).toFixed(2));
                    var video_size_limit = Number('<?php echo UPLOAD_VIDEO_SIZE; ?>');
                    if (video_size_limit < file_size) {
                        $("#" + val).val(null);
                        alertify.error('<?php echo ERROR_UPLOAD_VIDEO_SIZE; ?>');
                        $('#name_' + val).html('');
                        return false;
                    } else {
                        var selected_file = fileName;
                        var original_selected_file = selected_file.substring(selected_file.lastIndexOf('\\') + 1, selected_file.length);
                        $('#name_' + val).html(original_selected_file);
                        return true;
                    }
                }
            }
        } else {
            $('#name_' + val).html('No video selected');
            alertify.error("No video selected");
            $('#name_' + val).html('<p class="red">Please select video</p>');

        }
    }

</script>
<?php   if($form_type == 'custom') { ?>
<script>
    $(document).ready(function () {
        $("#job_fair_custom").validate({
            submitHandler: function(form) {

                var video_source = $('input[name="video_source"]:checked').val();
                var flag = 0;
                
                if (video_source  == 'youtube') {
                    if ($('#yt_vm_video_url').val() != '') {
                        var p = /(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.)?youtube\.com\/watch(?:\.php)?\?.*v=)([a-zA-Z0-9\-_]+)/;

                        if (!$('#yt_vm_video_url').val().match(p)) {
                            alertify.error('Not a Valid Youtube URL');
                            flag = 1;
                            return false;
                        }
                    } else {
                        alertify.error('Video URL is required');
                        flag = 1;
                        return false;
                    }
                }
                
                if (video_source  == 'vimeo') {
                    if ($('#yt_vm_video_url').val() != '') {
                        var myurl = "<?= base_url() ?>home/validate_vimeo";
                        $.ajax({
                            type: "POST",
                            url: myurl,
                            data: {url: $('#yt_vm_video_url').val()},
                            async: false,
                            success: function (data) {
                                if (data == false) {
                                    alertify.error('Not a Valid Vimeo URL');
                                    flag = 1;
                                    return false;
                                }
                            },
                            error: function (data) {
                            }
                        });
                    } else {
                        alertify.error('Video URL is required');
                        flag = 1;
                        return false;
                    }
                }
                
                if (video_source  == 'uploaded') {
                    if ($('#video_upload').val() == '') {
                        alertify.error('Please Choose Video');
                        flag = 1;
                        return false;
                    }   
                }

                // var recaptcha = $("#g-recaptcha-response").val();

                // if (recaptcha === "") {
                //     $('.loader_cover').hide();
                //     $('.loader').hide();
                //     alertify.alert("Please check the recaptcha");
                //     flag = 1;
                //     return false;
                // }

                if(flag != 1) {
                    $("#add_edit_submit").attr("disabled", true);  
                    $("#add_edit_submit").prop("disabled", true);
                    $('#add_edit_submit').addClass("greyed_out");
                    $('.loader_cover').show();
                    $('.loader').show();
                    form.submit();
                }
            }
        });
    });
</script>
<?php } ?>
<style>
    .modal-backdrop.in {
        z-index: 100 !important;
    }
    
    .greyed_out{
        background-color: grey !important;
        cursor: default !important;
    } 
    
    .loader {
        border: 16px solid #f3f3f3; /* Light grey */
        border-top: 16px solid #3498db; /* Blue */
        border-radius: 50%;
        width:100px;
        height: 100px;
        animation: spin 2s linear infinite;
      }

      @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
      }
</style>


<style>
    .cs-input-group > .input-group-addon{ 
        background-color: transparent; 
        border-top-left-radius: 20px; 
        border-bottom-left-radius: 20px; 
        -webkit-border-top-left-radius: 20px; 
        -webkit-border-bottom-left-radius: 20px; 
        -moz-border-top-left-radius: 20px; 
        -moz-border-bottom-left-radius: 20px; 
    }
    .cs-input-group > .input-group-addon span{
        font-size: 16px;
        font-weight: bolder;
    }
    .cs-input-group input{ 
        border-left: none;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        -webkit-border-top-left-radius: 0;
        -webkit-border-bottom-left-radius: 0;
        -moz-border-top-left-radius: 0;
        -moz-border-bottom-left-radius: 0;
    }
    .cs-error{ border-color: #CC0000; }
</style>