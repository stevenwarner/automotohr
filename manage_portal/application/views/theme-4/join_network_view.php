<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
//
$is_regex = 0;
$input_group_start = $input_group_end = '';
$primary_phone_number = isset($formpost['phone_number']) ? $formpost['phone_number'] : '';
// if(isset($phone_pattern_enable) && $phone_pattern_enable == 1) {
//     $primary_phone_number = phonenumber_format($formpost['phone_number'], true);
//     $is_regex = 1;
//     $input_group_start = '<div class="input-group cs-input-group"><div class="input-group-addon cs-input-group-addon"><span class="input-group-text" id="basic-addon1">+1</span></div>';
//     $input_group_end   = '</div>';
// }
?>
<?php if (!($customize_career_site['status'] == 1 && $customize_career_site['menu'] == 0)) { ?>
    <div class="job-detail-banner">
        <div class="container-fluid">
            <div class="detail-banner-caption">
                <header class="heading-title">
                    <h1 class="section-title">Joining our Talent Network will enhance your job search and application process.</h1>
                </header>
            </div>
        </div>
    </div>
<?php } else { ?>
    <div class="text-center" style="width:100%;z-index:10;padding-top:20px;">
        <a href="<?= base_url('jobs') ?>" class="site-btn bg-color">Back</a>
    </div>
<?php } ?>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="text-column" style="width:100%;">
                    <?php $this->load->view($theme_name . '/_parts/admin_flash_message'); ?>
                </div>
                <div class="join-our-network">
                    <?php $this->load->view('common/talent_network_content'); ?>
                    <div class="form-column">
                        <div class="join-from">
                            <ul>
                                <form method="post" name="join_network" id="join_network" action="" class="form" enctype="multipart/form-data">
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
                                    <li>
                                        <label>Email Address<span class="staric">*</span></label>
                                        <input type="email" name="email" placeholder="" value="<?php if (isset($formpost['email'])) {
                                                                                                    echo $formpost['email'];
                                                                                                } ?>" class="join-from-fields" required>
                                        <?php echo form_error('email'); ?>
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
                                        <?= $input_group_start; ?>
                                        <input type="text" id="PhoneNumber" name="phone_number" placeholder="" value="<?= $primary_phone_number; ?>" class="join-from-fields js-phone" />
                                        <?= $input_group_end; ?>
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
                                        <label>Interest level<span class="staric">*</span></label>
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
                                      <br>  <input type="submit" name="action" value="join now" class="join-btn bg-color" onclick="validate_form()">
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
                phone_regex.lastIndex = 0;
                var phone = $('#PhoneNumber').val().trim(),
                    error = false;

                $('.js-error-class').remove();
                <?php if ($is_regex === 1) { ?>
                    $('#txt_phonenumber').remove();
                    if (phone != '' && phone != '(___) ___-____' && !phone_regex.test(phone)) {
                        $('#PhoneNumber').parent().after('<p class="js-error-class" style="color: #cc0000;">Contact number is invalid. Please use following format (XXX) XXX-XXXX.</p>');
                        error = true;
                        return;
                    }
                    if (phone != '' && phone != '(___) ___-____') $("#join_network").append('<input type="hidden" name="txt_phonenumber" id="txt_phonenumber" value="+1' + (phone.replace(/\D/g, '')) + '" />');
                <?php } ?>
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
                var id = allstates[i].sid;
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


<?php if ($is_regex === 1) { ?>
    <script>
        var phone_regex = new RegExp(/(\(\d{3}\))\s(\d{3})-(\d{4})$/);

        $.each($('.js-phone'), function() {
            var v = fpn($(this).val().trim());
            if (typeof(v) === 'object') {
                $(this).val(v.number);
                setCaretPosition(this, v.cur);
            } else $(this).val(v);
        });


        $('.js-phone').keyup(function(e) {
            var val = fpn($(this).val().trim());
            if (typeof(val) === 'object') {
                $(this).val(val.number);
                setCaretPosition(this, val.cur);
            } else $(this).val(val);
        })


        // Format Phone Number
        // @param phone_number
        // The phone number string that 
        // need to be reformatted
        // @param format
        // Match format 
        // @param is_return
        // Verify format or change format
        function fpn(phone_number, format, is_return) {
            // 
            var default_number = '(___) ___-____';
            var cleaned = phone_number.replace(/\D/g, '');
            if (cleaned.length > 10) cleaned = cleaned.substring(0, 10);
            match = cleaned.match(/^(1|)?(\d{3})(\d{3})(\d{4})$/);
            //
            if (match) {
                var intlCode = '';
                if (format == 'e164') intlCode = (match[1] ? '+1 ' : '');
                return is_return === undefined ? [intlCode, '(', match[2], ') ', match[3], '-', match[4]].join('') : true;
            } else {
                var af = '',
                    an = '',
                    cur = 1;
                if (cleaned.substring(0, 1) != '') {
                    af += "(_";
                    an += '(' + cleaned.substring(0, 1);
                    cur++;
                }
                if (cleaned.substring(1, 2) != '') {
                    af += "_";
                    an += cleaned.substring(1, 2);
                    cur++;
                }
                if (cleaned.substring(2, 3) != '') {
                    af += "_) ";
                    an += cleaned.substring(2, 3) + ') ';
                    cur = cur + 3;
                }
                if (cleaned.substring(3, 4) != '') {
                    af += "_";
                    an += cleaned.substring(3, 4);
                    cur++;
                }
                if (cleaned.substring(4, 5) != '') {
                    af += "_";
                    an += cleaned.substring(4, 5);
                    cur++;
                }
                if (cleaned.substring(5, 6) != '') {
                    af += "_-";
                    an += cleaned.substring(5, 6) + '-';
                    cur = cur + 2;
                }
                if (cleaned.substring(6, 7) != '') {
                    af += "_";
                    an += cleaned.substring(6, 7);
                    cur++;
                }
                if (cleaned.substring(7, 8) != '') {
                    af += "_";
                    an += cleaned.substring(7, 8);
                    cur++;
                }
                if (cleaned.substring(8, 9) != '') {
                    af += "_";
                    an += cleaned.substring(8, 9);
                    cur++;
                }
                if (cleaned.substring(9, 10) != '') {
                    af += "_";
                    an += cleaned.substring(9, 10);
                    cur++;
                }

                if (is_return) return match === null ? false : true;

                return {
                    number: default_number.replace(af, an),
                    cur: cur
                };
            }
        }

        // Change cursor position in input
        function setCaretPosition(elem, caretPos) {
            if (elem != null) {
                if (elem.createTextRange) {
                    var range = elem.createTextRange();
                    range.move('character', caretPos);
                    range.select();
                } else {
                    if (elem.selectionStart) {
                        elem.focus();
                        elem.setSelectionRange(caretPos, caretPos);
                    } else elem.focus();
                }
            }
        }
    </script>
<?php } ?>
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

    .cs-input-group>.input-group-addon span {
        font-size: 21px;
        font-weight: bolder;
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