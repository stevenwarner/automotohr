<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $primary_phone_number = isset($formpost['phone_number']) ? $formpost['phone_number'] : ''; ?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<div class="main-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php $this->load->view($theme_name . '/_parts/admin_flash_message'); ?>
                <div class="join-our-network">
                    <?php $this->load->view('common/talent_network_content'); ?>
                    <div class="form-column">
                        <div class="join-from">
                            <ul>
                                <form method="post" name="join_network" id="join_network" action="" class="form" enctype="multipart/form-data">
                                    <li>
                                        <label>Email Address<span class="staric">*</span></label>
                                        <input type="email" name="email" placeholder="" value="<?php if (isset($formpost['email'])) {
                                                                                                    echo $formpost['email'];
                                                                                                } ?>" class="join-from-fields" required>
                                        <?php echo form_error('email'); ?>
                                    </li>
                                    <li class="form-col-left">
                                        <label>First Name<span class="staric">*</span></label>
                                        <input type="text" name="first_name" placeholder="" value="<?php if (isset($formpost['first_name'])) {
                                                                                                        echo $formpost['first_name'];
                                                                                                    } ?>" class="join-from-fields" required>
                                        <?php echo form_error('first_name'); ?>
                                    </li>
                                    <li class="form-col-right">
                                        <label>Last Name<span class="staric">*</span></label>
                                        <input type="text" name="last_name" placeholder="" value="<?php if (isset($formpost['last_name'])) {
                                                                                                        echo $formpost['last_name'];
                                                                                                    } ?>" class="join-from-fields" required>
                                        <?php echo form_error('last_name'); ?>
                                    </li>
                                    <?php if (isset($formpost['country'])) {
                                        $country_id = $formpost['country'];
                                    } else {
                                        $country_id = 227;
                                    } ?>
                                    <li class="form-col-left">
                                        <label>Country<span class="staric">*</span></label>
                                        <select class="join-from-fields" name="country" onchange="getStates(this.value, '<?php echo $states; ?>')" required>
                                            <?php foreach ($active_countries as $active_country) { ?>
                                                <option value="<?php echo $active_country['sid']; ?>" <?php if ($active_country['sid'] == $country_id) {
                                                                                                            echo "selected";
                                                                                                            $country_id = $active_country['sid'];
                                                                                                        } ?>> <?php echo $active_country['country_name']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                        <?php echo form_error('country'); ?>
                                    </li>
                                    <li class="form-col-right">
                                        <label>State<span class="staric">*</span></label>
                                        <select class="join-from-fields" name="state" id="state" required="required">
                                            <?php if (empty($country_id)) {
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
                                        <input type="text" name="city" placeholder="" value="<?php if (isset($formpost['city'])) {
                                                                                                    echo $formpost['city'];
                                                                                                } ?>" class="join-from-fields" required>
                                        <?php echo form_error('city'); ?>
                                    </li>
                                    <li class="form-col-right">
                                        <label>Best Contact Number</label>
                                        <input type="text" id="PhoneNumber" name="phone_number" placeholder="" value="<?= $primary_phone_number; ?>" class="join-from-fields js-phone" />
                                        <?php echo form_error('phone_number'); ?>
                                    </li>
                                    <li>
                                        <label>Desired Job Title<span class="staric">*</span></label>
                                        <input type="text" name="desired_job_title" placeholder="" value="<?php if (isset($formpost['desired_job_title'])) {
                                                                                                                echo $formpost['desired_job_title'];
                                                                                                            } ?>" class="join-from-fields" required>
                                        <?php echo form_error('desired_job_title'); ?>
                                    </li>
                                    <li>
                                        <label>interest level<span class="staric">*</span></label>
                                        <select class="join-from-fields" name="interest_level" required="required">
                                            <option value="">---Select An Option---</option>
                                            <option value="Passive Interest">Passive Interest</option>
                                            <option value="Actively Looking but Employed">Actively Looking but Employed</option>
                                            <option value="Actively Looking and Available Immediately">Actively Looking and Available Immediately</option>
                                        </select>
                                        <?php echo form_error('interest_level'); ?>
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
                                        <textarea class="join-from-fields" name="job_interest_text" id="job_interest_text" maxlength="128" onkeyup="check_length()" required=""><?php if (isset($formpost['job_interest_text'])) {
                                                                                                                                                                                    echo $formpost['job_interest_text'];
                                                                                                                                                                                } ?></textarea>
                                        <p id="remaining_text" class="info">128 characters left!</p>
                                        <?php echo form_error('job_interest_text'); ?>
                                    </li>
                                    <li class="terms-conditions">
                                        <label for="squared" class="hint-label">I have Read and Understand the <a href="javascript:viod(0);" data-toggle="modal" data-target="#terms_and_conditions">Terms & Conditions</a> and <a href="javascript:viod(0);" data-toggle="modal" data-target="#privay_policy">Privacy Policy</a><span class="staric">*</span></label>
                                        <input type="checkbox" required="required" name="check_box" value="1" id="squared">
                                    </li>

                                    <li>
                                        <div class="g-recaptcha" data-callback="googleCaptchaChecker" data-sitekey="<?= getCreds('AHR')->GOOGLE_CAPTCHA_API_KEY_V2; ?>"></div>
                                        <label id='captchaerror' style="display: none; float: none !important;color: #CC0000 !important;font-weight: 400;margin: 0 !important;">Empty/Invalid Captcha </label>
                                    </li> <br>

                                    <li>
                                        <?php
                                        $csrf = array(
                                            'name' => $this->security->get_csrf_token_name(),
                                            'hash' => $this->security->get_csrf_hash()
                                        ); ?>

                                        <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />
                                        <input type="submit" name="action" value="join now" class="join-btn" onclick="validate_form()">
                                    </li>
                                </form>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
<script language="JavaScript" type="text/javascript" src="<?php echo base_url('assets/theme-1/js/jquery.validate.min.js'); ?>"></script>
<script language="JavaScript" type="text/javascript">
    var phone_regex = new RegExp(/(\(\d{3}\))\s(\d{3})-(\d{4})$/);
    var onloadCallback = function() {
        widgetId1 = grecaptcha.render("jsGoogleCaptcha", {
            "sitekey": "<?php echo $this->config->item('google_key'); ?>",
            "theme": "light"
        });
    };
    var googleCaptchaToken = null;

    function googleCaptchaChecker(don) {
        googleCaptchaToken = don;
    }
    function validate_form() {
        $("#join_network").validate({
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
                check_box: "You must agree to our Terms & Condition and Privacy Policy",
                interest_level: "Interest Level required",
                job_interest_text: "types Of Jobs Interested required"
            },
            submitHandler: function(form) {
                // phone_regex.lastIndex = 0;
                // var phone = $('#PhoneNumber').val().trim(),
                var error = false;

                // $('.js-error-class').remove();
                // $('#txt_phonenumber').remove();
                // if(phone != '' && phone != '(___) ___-____' && !phone_regex.test(phone)){
                //     $('#PhoneNumber').parent().after('<p class="js-error-class" style="color: #cc0000;">Contact number is invalid. Please use following format (XXX) XXX-XXXX.</p>');
                //     error = true;
                //     return;
                // }
                // if(phone != '' && phone != '(___) ___-____') $("#join_network").append('<input type="hidden" name="txt_phonenumber" id="txt_phonenumber" value="+1'+(phone.replace(/\D/g, ''))+'" />');

                // Google Captcha Handler
                // if($('#jsGoogleCaptcha').find('textarea').length == 0 || $('#jsGoogleCaptcha').find('textarea').val() == '') {
                //     $('#jsGoogleCaptcha').before('<p class="js-error-class" style="color: #cc0000;">Captcha is required.</p>');
                //     error = true;
                // }
                if (googleCaptchaToken === null) {

                    $("#captchaerror").show();
                    return;
                }

                if (error === false) form.submit();
                // form.submit();
            }
        });
    }

    function check_file() {
        var fileName = $("#resume").val();
        if (fileName.length > 0) {
            $('#name_resume').html(fileName.substring(12, fileName.length));
            var ext = fileName.split('.').pop();
            if (ext != "pdf" && ext != "docx" && ext != "doc") {
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
                var id = allstates[i].id;
                var name = allstates[i].state_name;
                html += '<option value="' + id + '">' + name + '</option>';
            }
            $('#state').html(html);
        }
    }
</script>
<style>
    .modal-backdrop.in {
        z-index: 100 !important;
    }
</style>

<style>
    .cs-input-group>.input-group-addon {
        background-color: transparent;
        border-top-left-radius: 20px;
        border-bottom-left-radius: 20px;
        -webkit-border-top-left-radius: 20px;
        -webkit-border-bottom-left-radius: 20px;
        -moz-border-top-left-radius: 20px;
        -moz-border-bottom-left-radius: 20px;
    }

    .cs-input-group input {
        border-left: none;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        -webkit-border-top-left-radius: 0;
        -webkit-border-bottom-left-radius: 0;
        -moz-border-top-left-radius: 0;
        -moz-border-bottom-left-radius: 0;
    }

    .cs-error {
        border-color: #CC0000;
    }
</style>