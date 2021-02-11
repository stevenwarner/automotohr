<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="job-detail-banner">
    <div class="container-fluid">
        <div class="detail-banner-caption">
            <header class="heading-title">
                <h1 class="section-title"><?php echo $heading_title; ?></h1>
            </header>
        </div>
    </div>
</div>
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
                            <ul>
                    <?php   if($form_type == 'default') { ?>
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
                                        <input type="text" name="phone_number" placeholder="" value="<?php if(isset($formpost['phone_number'])){ echo $formpost['phone_number']; } ?>" class="join-from-fields">
                                        <?php echo form_error('phone_number'); ?>
                                    </li>
                                    <li>
                                        <label>Desired Job Title<span class="staric">*</span></label>
                                        <input type="text" name="desired_job_title" placeholder="" value="<?php if(isset($formpost['desired_job_title'])){ echo $formpost['desired_job_title']; } ?>" class="join-from-fields" required>
                                        <?php echo form_error('desired_job_title'); ?> 
                                    </li>
                                    <li>
                                        <label>College / University Name<span class="staric">*</span></label>
                                        <input type="text" name="college_university_name" placeholder="" value="<?php if(isset($formpost['college_university_name'])){ echo $formpost['college_university_name']; } ?>" class="join-from-fields" required>
                                        <?php echo form_error('college_university_name'); ?> 
                                    </li>
                                    <li>
                                        <label>Upload a Resume (.pdf .docx .doc) </label>
                                        <div class="join-from-fields choose-file">
                                            <div class="file-name" id="name_resume">Please Select</div>
                                            <input class="choose-file-filed bg-color" type="file" name="resume" id="resume" onchange="check_file()">
                                            <a class="choose-btn bg-color" href="javascript:;">choose file</a>
                                        </div>
                                        <?php echo form_error('resume'); ?>
                                    </li>
                                    <li class="textarea">
                                        <label>What types of jobs are you interested in? (max 128 characters)<span class="staric">*</span></label>
                                        <textarea class="join-from-fields" name="job_interest_text" id="job_interest_text" maxlength="128" onkeyup="check_length()" required=""><?php if(isset($formpost['job_interest_text'])){ echo $formpost['job_interest_text']; } ?></textarea>
                                        <p id="remaining_text" class="info">128 characters left!</p>
                                        <?php echo form_error('job_interest_text'); ?> 
                                    </li>
                                    <li>
                                        <input type="submit" name="action" value="Submit" class="join-btn bg-color" onclick="validate_form()">
                                    </li>
                                </form>
                    <?php   } else { ?>
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
                                    
                    <?php           if(!empty($job_fair_default_questions)) {
                                        foreach($job_fair_default_questions as $default_data_keys => $default_data_fields) { 
                                                $field_id = $default_data_fields['field_id'];
                                            
                                            if($default_data_fields['field_priority'] == 'optional') { 
                                                if($default_data_fields['question_type'] == 'string') { ?>
                                                    <li>
                                                        <label><?php echo $default_data_fields['field_name']; if($default_data_fields['is_required'] == 1) { ?><span class="staric">*</span> <?php } ?></label>
                    <?php                                   if($default_data_fields['field_id'] == 'email') {$input_type = 'email';} else {$input_type = 'text';} ?>
                                                            <input type="<?php echo $input_type; ?>" name="<?php echo $field_id; ?>" placeholder="" value="<?php if(isset($formpost[$field_id])){ echo $formpost[$field_id]; } ?>" class="join-from-fields" <?php if($default_data_fields['is_required'] == 1) { ?>required <?php } ?>>
                    <?php                                   echo form_error($field_id); ?>
                                                    </li>
                    <?php                       } else if($default_data_fields['question_type'] == 'list') { 
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
                    <?php                           } else if($default_data_fields['field_id'] == 'state') { ?>
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
                    <?php                           }                     
                                                } else if($default_data_fields['question_type'] == 'file') { ?>
                                                    <li>
                                                        <label><?php echo $default_data_fields['field_name']; if($default_data_fields['is_required'] == 1) { ?><span class="staric">*</span> <?php } ?></label>
                                                        <div class="join-from-fields choose-file">
                                                            <div class="file-name" id="name_resume">Please Select</div>
                                                            <input class="choose-file-filed bg-color" type="file" name="<?php echo $field_id; ?>" id="resume" onchange="check_file()">
                                                            <a class="choose-btn bg-color" href="javascript:;">choose file</a>
                                                        </div>
                                                        <?php echo form_error($field_id); ?>
                                                    </li>
                    <?php                       }//question_type
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
                    <?php                                   if($custom_data_fields['field_id'] == 'email') {$input_type = 'email';} else {$input_type = 'text';} ?>
                                                            <input type="<?php echo $input_type; ?>" name="<?php echo $field_id; ?>" placeholder="" value="<?php if(isset($formpost[$field_id])){ echo $formpost[$field_id]; } ?>" class="join-from-fields" <?php if($custom_data_fields['is_required'] == 1) { ?>required <?php } ?>>
                    <?php                                   echo form_error($field_id); ?>
                                                    </li>
                    <?php                       } else if($custom_data_fields['question_type'] == 'list') { 
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
                    <?php                       } else if($custom_data_fields['question_type'] == 'boolean') { ?>
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
                    <?php                       } else if($custom_data_fields['question_type'] == 'multilist') { 
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
                    <?php                       } //question_type
                                            } // optional fields
                                        } // loop
                                    } // job_fair_custom_questions ?>
                                    <li>
                                        <input type="submit" name="action" value="Submit" class="join-btn bg-color">
                                    </li>
                                </form> 
                    <?php   } // custom form ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**/ ?>
<script language="JavaScript" type="text/javascript" src="<?php echo base_url('assets/theme-1/js/jquery.validate.min.js'); ?>"></script>
<script language="JavaScript" type="text/javascript">
function validate_form() {
    $("#job_fair_default").validate({
        ignore: ":hidden:not(select)",
        messages: {
            first_name: "Please provide first name",
            last_name: "Please provide last name",
            email: "Please provide valid email address",
            desired_job_title: "Please provide your desired job title",
            city: "City required!",
            resume: "Resume required!",
            zipcode: "Zipcode required!",
            country: "Country required!",
            //check_box: "You must agree to our Terms & Condition and Privacy Policy",
            college_university_name: "College University name required",
            job_interest_text: "types Of Jobs Interested required"
        },
        submitHandler: function (form) {
            form.submit();
        }
    });
}

function check_file() {
    var fileName = $("#resume").val();
    if (fileName.length > 0) {
        $('#name_resume').html(fileName.substring(12, fileName.length));
        var ext = fileName.split('.').pop();
        var lower_ext = ext.toLowerCase();
        if (lower_ext != "pdf" && lower_ext != "docx" && lower_ext != "doc") {
            $("#resume").val(null);
            $('#name_resume').html('Only (.pdf .docx .doc) allowed! Please Select again.');
        }
    } else {
        $('#name_resume').html('Please Select');
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
    //console.log(user_text+' = '+text_length+" LEFT: "+text_left);
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
</script>
<?php   if($form_type == 'custom') { ?>
<script>
    $(document).ready(function () {
        $('#job_fair_custom').validate();
    });
</script>
<?php } ?>
<style>
    .modal-backdrop.in {
        z-index: 100 !important;
    }
</style>