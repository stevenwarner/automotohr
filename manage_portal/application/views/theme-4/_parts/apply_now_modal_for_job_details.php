<?php
//
$is_regex = 0;
$input_group_start = $input_group_end = '';
$primary_phone_number = isset($formpost['phone_number']) ? $formpost['phone_number'] : '';
if (isset($phone_pattern_enable) && $phone_pattern_enable == 1) {
    $primary_phone_number = phonenumber_format($formpost['phone_number'], true);
    $is_regex = 1;
    $input_group_start = '<div class="input-group cs-input-group"><div class="input-group-addon cs-input-group-addon"><span class="input-group-text" id="basic-addon1">+1</span></div>';
    $input_group_end   = '</div>';
}
?>
<script language="JavaScript" type="text/javascript" src="<?php echo base_url('assets'); ?>/theme-4/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo base_url('assets') ?>/theme-4/js/additional-methods.min.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>assets/alertifyjs/css/alertify.min.css" />
<link rel="stylesheet" href="<?= base_url() ?>assets/alertifyjs/css/themes/default.min.css" />
<script src="<?= base_url() ?>assets/alertifyjs/alertify.min.js"></script>

<script>
    $(document).ready(function() {
        $('.spinner').hide();
    });

    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id))
            return;
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

    $(document).ready(function() {
        $('.g-recaptcha-err').each(function() {
            $(this).hide();
        });

        var hash = window.location.hash;
        var n = hash.indexOf("#");
        if (n == 0) {
            $('#widget_applynow').find('a').trigger('click');
        }
    });

    function getStates(val, states) {
        states = jQuery.parseJSON(states);
        //console.log(states);
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

    var googleCaptchaToken = null;

    function googleCaptchaChecker(don) {
        googleCaptchaToken = don;
    }

    var sm_enable = <?= $sms_module_status == 1 ? 1 : 0 ?>;
    var sm_regex = sm_enable == 1 ? /(\d{10})|(\d{11})$/ : /^[0-9\-]+$/;;

    function validate_form() {
        youtube_check()
        $("#register-form").validate({
            ignore: ":hidden:not(select)",
            rules: {
                first_name: {
                    required: true,
                    pattern: /^[a-zA-Z0-9\- ]+$/
                },
                last_name: {
                    required: true,
                    pattern: /^[a-zA-Z0-9\- .]+$/
                },
                email: {
                    required: true,
                    email: true
                },
                phone_number: {
                    <?php if ($is_regex === 1) { ?>
                        pattern: /(\(\d{3}\))\s(\d{3})-(\d{4})$/ // (555) 123-4567
                    <?php } else { ?>
                        pattern: /^[0-9\-]+$/
                    <?php } ?>
                },
                city: {
                    pattern: /^[a-zA-Z0-9\- ]+$/
                },
                state: {
                    required: true,
                },
                country: {
                    required: true,
                },
            },
            messages: {
                first_name: {
                    required: 'First Name is required',
                    pattern: 'Letters, numbers, and dashes only please'
                },
                last_name: {
                    required: 'Last Name is required',
                    pattern: 'Letters, numbers, and dashes only please'
                },
                email: {
                    required: 'Please provide Valid email'
                },
                phone_number: {
                    required: 'Phone Number is required',
                    <?php if ($is_regex === 1) { ?>
                        pattern: 'Invalid phone number. e.g. (XXX) XXX-XXXX'
                    <?php } else { ?>
                        pattern: 'Numbers and dashes only please'
                    <?php } ?>
                },
                city: {
                    required: 'City is required',
                    pattern: 'Please Provide valid City'
                },
                state: {
                    required: 'State is required'
                },
                country: {
                    required: 'Country is required'
                },
                us_citizen: "Please Provide Your Residential Status.",
                visa_status: "Please Provide Your Visa Status.",
                group_status: "Please Provide Your Group Status.",
                veteran: "Please Provide Your Veteran Status.",
                disability: "Please Provide Your Disability Status.",
                gender: "Please Select Your Gender."
            },
            submitHandler: function(form, event) {

                if (googleCaptchaToken === null) {

                    $("#captchaerror").show();
                    return;
                }

                $('.spinner').show();
                $('#mySubmitBtn').prop('disabled', true);
                <?php if ($is_regex === 1) { ?>
                    $("#register-form").append('<input type="hidden" name="txt_phonenumber" id="txt_phonenumber" value="+1' + ($('#PhoneNumber').val().replace(/\D/g, '')) + '" />')
                <?php } ?>


                form.submit();


            }
        });
    }

    <?php if (is_subdomain_of_automotohr()) { ?>

        function validate_friend_form() {
            $("#friend-form").validate({
                // Specify the validation error messages
                rules: {
                    sender_name: {
                        required: true
                    },
                    receiver_name: {
                        required: true
                    },
                    receiver_email: {
                        required: true
                    }


                },
                messages: {
                    sender_name: "Please provide your name",
                    receiver_name: "Please provide receiver name",
                    receiver_email: "Please provide valid email address",
                }
            });

            if ($('#friend-form').valid()) {

                $('#friend-form').submit();


            }

        }
    <?php } else { ?>

        function validate_friend_form() {
            $("#friend-form").validate({
                // Specify the validation error messages
                rules: {
                    sender_name: {
                        required: true
                    },
                    receiver_name: {
                        required: true
                    },
                    receiver_email: {
                        required: true
                    }
                },
                messages: {
                    sender_name: "Please provide your name",
                    receiver_name: "Please provide receiver name",
                    receiver_email: "Please provide valid email address",
                }
            });

            if ($('#friend-form').valid()) {
                $('#friend-form').submit();
            }
        }
    <?php } ?>

    function check_file(val) {
        var fileName = $("#" + val).val();
        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 35));
            var ext = fileName.split('.').pop();

            if (val == 'resume' || val == 'cover_letter') {
                if (ext != "pdf" && ext != "docx" && ext != "doc" && ext != "jpg" && ext != "jpeg" && ext != "png" && ext != "jpe" && ext != "gif") {
                    $("#" + val).val(null);
                    $('#name_' + val).html('<p class="red">Only (.pdf .docx .doc .jpg .jpeg .png .jpe .gif) allowed!</p>');
                }
            } else if (val == 'pictures') {
                if (ext != "jpg" && ext != "jpeg" && ext != "png" && ext != "jpe" && ext != "JPG") {
                    $("#" + val).val(null);
                    $('#name_' + val).html('<p class="red">Only (.jpg .jpeg .png) allowed!</p>');
                }
            }
        } else {
            $('#name_' + val).html('Please Select');
        }
    }

    function youtube_check() {
        var matches = $('#YouTube_Video').val().match(/https:\/\/(?:www\.)?youtube.*watch\?v=([a-zA-Z0-9\-_]+)/);
        data = $('#YouTube_Video').val();
        if (matches || data == '') {
            $("#video_link").html("");
            return true;
        } else {
            $("#video_link").html("<label for='YouTube_Video' generated='true' class='error'>Please provide youtube link</label>");
            return false;
        }
    }
</script>
<div class="modal modal-fullscreen fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog custom-popup" role="document">
        <div class="modal-content">
            <div class="modal-header border-none">
                <button type="button" class="close btn-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title modal-heading" id="myModalLabel">Apply for '<?php echo $job_details['Title']; ?>'</h4>
            </div>
            <div class="modal-body">
                <div class="apply-job-from">
                    <ul>
                        <form class="popup-form" method="post" name="register-form" enctype="multipart/form-data" id="register-form">
                            <li>
                                <label>first name <span class="staric">*</span></label>
                                <input class="form-fields" type="text" name="first_name" required="required" placeholder="Enter First Name"
                                    value="<?php
                                            if (isset($formpost['first_name'])) {
                                                echo $formpost['first_name'];
                                            }
                                            ?>">
                                <?php echo form_error('first_name'); ?>
                            </li>
                            <li>
                                <label>last name <span class="staric">*</span></label>
                                <input class="form-fields" type="text" name="last_name" required="required" placeholder="Enter Last Name" value="<?php
                                                                                                                                                    if (isset($formpost['last_name'])) {
                                                                                                                                                        echo $formpost['last_name'];
                                                                                                                                                    }
                                                                                                                                                    ?>">
                                <?php echo form_error('last_name'); ?>
                            </li>
                            <li>
                                <label>profile picture</label>
                                <div class="form-fields choose-file">
                                    <div class="file-name" id="name_pictures">Please Select</div>
                                    <input class="choose-file-filed bg-color" type="file" name="pictures" id="pictures" onchange="check_file('pictures')">
                                    <a class="choose-btn bg-color" href="javascript:;">choose file</a>
                                </div>
                                <?php echo form_error('pictures'); ?>
                            </li>
                            <li>
                                <?php $this->load->view('common/attach_video_fields'); ?>
                            </li>
                            <li>
                                <label>email <span class="staric">*</span></label>
                                <input class="form-fields" type="email" name="email" required="required" placeholder="Enter Email" value="<?php
                                                                                                                                            if (isset($formpost['email'])) {
                                                                                                                                                echo $formpost['email'];
                                                                                                                                            }
                                                                                                                                            ?>">
                                <?php echo form_error('email'); ?>
                            </li>
                            <li>
                                <label>Phone <span class="staric">*</span></label>
                                <?= $input_group_start; ?>
                                <input
                                    class="form-fields js-phone"
                                    id="PhoneNumber"
                                    type="text"
                                    name="phone_number"
                                    required="required"
                                    placeholder="Phone Number"
                                    value="<?= $primary_phone_number; ?>" />
                                <?= $input_group_end; ?>
                                <?php echo form_error('phone_number'); ?>
                            </li>
                            <li>
                                <label>street address</label>
                                <input class="form-fields" type="text" name="address" placeholder="Enter Address" value="<?php
                                                                                                                            if (isset($formpost['address'])) {
                                                                                                                                echo $formpost['address'];
                                                                                                                            }
                                                                                                                            ?>">
                                <?php echo form_error('address'); ?>
                            </li>
                            <li>
                                <label>city <span class="staric">*</span></label>
                                <input class="form-fields" type="text" name="city" required="required" placeholder="Enter City" value="<?php
                                                                                                                                        if (isset($formpost['city'])) {
                                                                                                                                            echo $formpost['city'];
                                                                                                                                        }
                                                                                                                                        ?>">
                                <?php echo form_error('city'); ?>
                            </li>
                            <li>
                                <?php
                                if (isset($formpost['country'])) {
                                    $country_id = $formpost['country'];
                                } else if (isset($active_countries[38])) {
                                    $country_id = 38;
                                } else {
                                    $country_id = 227;
                                }
                                ?>
                                <label>state <span class="staric">*</span></label>
                                <select class="form-fields" name="state" id="state" required="required">
                                    <?php
                                    if (empty($country_id)) {
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
                            <li>
                                <label>country <span class="staric">*</span></label>
                                <select class="form-fields" name="country" onchange="getStates(this.value, '<?php echo $states; ?>')" required="required">
                                    <?php foreach ($active_countries as $active_country) { ?>
                                        <option value="<?php echo $active_country['sid']; ?>"
                                            <?php if ($active_country['sid'] == $country_id) { ?>
                                            selected
                                            <?php $country_id = $active_country['sid'];
                                            }
                                            ?>>
                                            <?php echo $active_country['country_name']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                                <?php echo form_error('country'); ?>
                            </li>
                            <li>
                                <?php $this->load->view('common/attach_resume_fields'); ?>
                            </li>
                            <li>
                                <label>attach cover </label>
                                <div class="form-fields choose-file">
                                    <div class="file-name" id="name_cover_letter">Please Select</div>
                                    <input class="choose-file-filed" type="file" id="cover_letter" name="cover_letter" onchange="check_file('cover_letter')">
                                    <a class="choose-btn bg-color" href="javascript:;">choose file</a>
                                </div>
                                <?php echo form_error('cover_letter'); ?>
                            </li>

                            <li>
                                <label for="referred_by_name">Referred By Name</label>
                                <input class="form-fields" type="text" id="referred_by_name" name="referred_by_name" placeholder="Enter Referrer Name" value="<?php if (isset($formpost['first_name'])) {
                                                                                                                                                                    echo $formpost['referred_by_name'];
                                                                                                                                                                } ?>">
                                <?php echo form_error('referred_by_name'); ?>
                            </li>
                            <li>
                                <label for="referred_by_email">Referred By Email</label>
                                <input class="form-fields" type="email" id="referred_by_email" name="referred_by_email" placeholder="Enter Referrer Email"
                                    value="<?php if (isset($formpost['referred_by_email'])) {
                                                echo $formpost['referred_by_email'];
                                            } ?>">
                                <?php echo form_error('referred_by_email'); ?>
                            </li>
                            <li class="questionare-section" id="show_questionnaire">
                                <label>Attach Resume (.pdf .docx .doc .jpg .jpe .jpeg .png .gif) Attach Cover (.pdf .docx .doc .jpg .jpe .jpeg .png .gif)</label>
                                <?php if ($job_details['questionnaire_sid'] > 0) { ?>
                                    <div class="wrap-container">
                                        <div class="wrap-inner">
                                            <h2 class="post-title color">Questionnaire</h2>
                                            <input type='hidden' name="q_name" value="<?php echo $job_details['q_name']; ?>">
                                            <input type='hidden' name="q_passing" value="<?php echo $job_details['q_passing']; ?>">
                                            <input type='hidden' name="q_send_pass" value="<?php echo $job_details['q_send_pass']; ?>">
                                            <input type='hidden' name="q_pass_text" value="<?php echo $job_details['q_pass_text']; ?>">
                                            <input type='hidden' name="q_send_fail" value="<?php echo $job_details['q_send_fail']; ?>">
                                            <input type='hidden' name="q_fail_text" value="<?php echo $job_details['q_fail_text']; ?>">
                                            <input type='hidden' name="my_id" value="<?php echo $job_details['my_id']; ?>">
                                            <?php $my_id = $job_details['my_id'];

                                            foreach ($job_details[$my_id] as $questions_list) { ?>
                                                <input type="hidden" name="all_questions_ids[]" value="<?php echo $questions_list['questions_sid']; ?>">
                                                <input type="hidden" name="caption<?php echo $questions_list['questions_sid']; ?>" value="<?php echo $questions_list['caption']; ?>">
                                                <input type="hidden" name="type<?php echo $questions_list['questions_sid']; ?>" value="<?php echo $questions_list['question_type']; ?>">
                                                <p>
                                                    <label><?php echo $questions_list['caption']; ?>: <?php if ($questions_list['is_required'] == 1) { ?><samp class="red"> * </samp><?php } ?></label>
                                                    <?php if ($questions_list['question_type'] == 'string') { ?>
                                                        <input type="text" class="form-fields" name="string<?php echo $questions_list['questions_sid']; ?>" placeholder="<?php echo $questions_list['caption']; ?>" value="" <?php if ($questions_list['is_required'] == 1) { ?> required <?php } ?>>
                                                    <?php } ?>
                                                    <?php if ($questions_list['question_type'] == 'boolean') { ?>
                                                        <?php $answer_key = 'q_answer_' . $questions_list['questions_sid']; ?>
                                                        <?php foreach ($job_details[$answer_key] as $answer_list) { ?>
                                                            <input type="radio" name="boolean<?php echo $questions_list['questions_sid']; ?>" value="<?php echo $answer_list['value']; ?> @#$ <?php echo $answer_list['score']; ?>" <?php if ($questions_list['is_required'] == 1) { ?> required <?php } ?>> <?php echo $answer_list['value']; ?>&nbsp;
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php if ($questions_list['question_type'] == 'list') { ?>
                                                        <?php $answer_key = 'q_answer_' . $questions_list['questions_sid']; ?>
                                                        <select name="list<?php echo $questions_list['questions_sid']; ?>" class="form-fields" <?php if ($questions_list['is_required'] == 1) { ?> required <?php } ?>>
                                                            <option value="">-- Please Select --</option>
                                                            <?php foreach ($job_details[$answer_key] as $answer_list) { ?>
                                                                <option value="<?php echo $answer_list['value']; ?> @#$ <?php echo $answer_list['score']; ?>"> <?php echo $answer_list['value']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    <?php } ?>
                                                    <?php if ($questions_list['question_type'] == 'multilist') { ?>
                                                        <?php $answer_key = 'q_answer_' . $questions_list['questions_sid']; ?>
                                                <div class="checkbox-wrap">
                                                    <?php $iterate = 0; ?>
                                                    <?php foreach ($job_details[$answer_key] as $answer_list) { ?>
                                                        <div class="label-wrap">
                                                            <div class="squared">
                                                                <input type="checkbox" name="multilist<?php echo $questions_list['questions_sid']; ?>[]" id="squared<?php echo $iterate; ?>" value="<?php echo $answer_list['value']; ?> @#$ <?php echo $answer_list['score']; ?>">
                                                                <label for="squared<?php echo $iterate; ?>"></label>
                                                            </div>
                                                            <span><?php echo $answer_list['value']; ?></span>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            <?php } ?>
                                            </p>
                                        <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </li>
                            <?php if ($eeo_form_status == 1) { ?>
                                <li class="employment-opertinity-form">
                                    <div class="opertinity-form-inner">
                                        <!-- <p>Federal law requires employers to provide reasonable accommodation to qualified individuals with disabilities. In the event you require reasonable accommodation to apply for this job, please contact our company and appropriate assistance will be provided.</p> -->
                                        <div class="accommodation-auestion">
                                            <strong>This EEO form is optional, do you want to fill it out?<span class="staric">*</span> </strong>
                                            <div class="col-wrp">
                                                <div class="fancy-radio-btn">
                                                    <label for="eeo_yes">
                                                        <input class="eeo_check" type="radio" value="Yes" name="EEO" id="eeo_yes" required="required"> <span>Yes</span>
                                                    </label>
                                                    <label for="eeo_no">
                                                        <input class="eeo_check" type="radio" value="No" name="EEO" id="eeo_no" required="required"> <span>No</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accommodation-auestion">
                                            <p><strong>EQUAL EMPLOYMENT OPPORTUNITY FORM</strong><br>
                                                We are an equal opportunity employer and we give consideration for employment to qualified applicants without regard to race, color, religion, sex, sexual orientation, gender identity, national origin, disability, or protected veteran status. If you'd like more information about your EEO rights as an applicant under the law, please click here:
                                                <a href="https://www.dol.gov/ofccp/regs/compliance/posters/pdf/eeopost.pdf" target="_blank">https://www.dol.gov/ofccp/regs/compliance/posters/pdf/eeopost.pdf.</a>
                                            </p>
                                            <p>Federal law requires employers to provide reasonable accommodation to qualified individuals with disabilities. In the event you require reasonable accommodation to apply for this job, please contact our company and appropriate assistance will be provided.</p>
                                        </div>
                                        <div class="questionnaire-form panel-collapse collapse">
                                            <ul>
                                                <li>
                                                    <label>I am a U.S. citizen or permanent resident <span class="staric">*</span> </label>
                                                    <div class="radio-btn">
                                                        <div class="fancy-radio-btn">
                                                            <label for="citizen-yes">
                                                                <input class="citizen_check" type="radio" value="Yes" name="us_citizen" id="citizen-yes"> <span>Yes</span>
                                                            </label>
                                                            <label for="citizen-no">
                                                                <input class="citizen_check" type="radio" value="No" name="us_citizen" id="citizen-no"> <span>No</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="visa_status_div" style="display: none;">
                                                    <label><big class="big-text">If no, please indicate your visa status</big> <span class="staric">*</span></label>
                                                    <textarea name="visa_status" id="visa_status" style="height:100px; border-radius:5px; resize:none;" class="form-fields"></textarea>
                                                </li>
                                            </ul>
                                            <div class="separator-border"></div>
                                            <div class="question-block">
                                                <h2>1. GROUP STATUS (PLEASE CHECK ONE) <span class="staric">*</span></h2>
                                                <div class="question-block-inner">
                                                    <div class="question-row">
                                                        <label for="q1">
                                                            <input type="radio" value="Hispanic or Latino" name="group_status" id="q1"> <span>
                                                                <p class="text"><strong>Hispanic or Latino </strong>
                                                            </span>
                                                            - A person of Cuban, Mexican, Puerto Rican, South or Central American, or other Spanish culture or origin regardless of race.</p>
                                                        </label>
                                                    </div>
                                                    <div class="question-row">
                                                        <label for="q2">
                                                            <input type="radio" value="White" name="group_status" id="q2"> <span>
                                                                <p class="text"><strong>White (Not Hispanic or Latino)</strong>
                                                            </span>
                                                            - A person having origins in any of the original peoples of Europe, the Middle East or North Africa.</p>
                                                        </label>
                                                    </div>
                                                    <div class="question-row">
                                                        <label for="q3">
                                                            <input type="radio" value="Black or African American" name="group_status" id="q3"> <span>
                                                                <p class="text"><strong>Black or African American (Not Hispanic or Latino) </strong>
                                                            </span>
                                                            - A person having origins in any of the black racial groups of Africa.</p>
                                                        </label>
                                                    </div>
                                                    <div class="question-row">
                                                        <label for="q4">
                                                            <input type="radio" value="Native Hawaiian or Other Pacific Islander" name="group_status" id="q4"> <span>
                                                                <p class="text"><strong>Native Hawaiian or Other Pacific Islander (Not Hispanic or Latino) </strong>
                                                            </span>
                                                            - A person having origins in any of the peoples of Hawaii, Guam, Samoa or other Pacific Islands.</p>
                                                        </label>
                                                    </div>
                                                    <div class="question-row">
                                                        <label for="q5">
                                                            <input type="radio" value="Asian" name="group_status" id="q5"> <span>
                                                                <p class="text"><strong>Asian (Not Hispanic or Latino) </strong>
                                                            </span>
                                                            - A person having origins in any of the original peoples of the Far East, Southeast Asia or the Indian Subcontinent, including, for example, Cambodia, China, India, Japan, Korea, Malaysia, Pakistan, the Philippine Islands, Thailand and Vietnam.</p>
                                                        </label>
                                                    </div>
                                                    <div class="question-row">
                                                        <label for="q6">
                                                            <input type="radio" value="American Indian or Alaska Native" name="group_status" id="q6"> <span>
                                                                <p class="text"><strong>American Indian or Alaska Native (Not Hispanic or Latino)</strong>
                                                            </span>
                                                            - A person having origins in any of the original peoples of North and South America (including Central America) and who maintain tribal affiliation or community attachment.</p>
                                                        </label>
                                                    </div>
                                                    <div class="question-row">
                                                        <label for="q7">
                                                            <input type="radio" value="Two or More Races" name="group_status" id="q7"> <span>
                                                                <p class="text"><strong>Two or More Races (Not Hispanic or Latino)</strong>
                                                            </span>
                                                            - All persons who identify with more than one of the above five races.</p>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="separator-border"></div>
                                            <div class="question-block">
                                                <h2>2. VETERAN<span class="staric">*</span></h2>
                                                <div class="question-block-inner">
                                                    <div class="question-row">
                                                        <label for="q8">
                                                            <input type="radio" value="Disabled Veteran" name="veteran" id="q8"> <span>
                                                                <p class="text"><strong>Disabled Veteran:</strong>
                                                            </span>
                                                            A veteran of the U.S. military, ground, naval or air service who is entitled to compensation (or who but for the receipt of military retired pay would be entitled to compensation) under laws administered by the Secretary of Veterans Affairs; or a person who was discharged or released from active duty because of a service-connected disability.</p>
                                                        </label>
                                                    </div>
                                                    <div class="question-row">
                                                        <label for="q9">
                                                            <input type="radio" value="Recently Separated Veteran" name="veteran" id="q9"> <span>
                                                                <p class="text"><strong>Recently Separated Veteran: </strong>
                                                            </span>
                                                            A "recently separated veteran" means any veteran during the three-year period beginning on the date of such veteran's discharge or release from active duty in the U.S. military, ground, naval, or air service.</p>
                                                        </label>
                                                    </div>
                                                    <div class="question-row">
                                                        <label for="q10">
                                                            <input type="radio" value="Active Duty Wartime or Campaign Badge Veteran" name="veteran" id="q10"> <span>
                                                                <p class="text"><strong>Active Duty Wartime or Campaign Badge Veteran: </strong>
                                                            </span>
                                                            An "active duty wartime or campaign badge veteran" means a veteran who served on active duty in the U.S. military, ground, naval or air service during a war, or in a campaign or expedition for which a campaign badge has been authorized under the laws administered by the Department of Defense. </p>
                                                        </label>
                                                    </div>
                                                    <div class="question-row">
                                                        <label for="q11">
                                                            <input type="radio" value="Armed Forces Service Medal Veteran" name="veteran" id="q11"> <span>
                                                                <p class="text"><strong>Armed Forces Service Medal Veteran:</strong>
                                                            </span>
                                                            An "Armed forces service medal veteran" means a veteran who, while serving on active duty in the U.S. military, ground, naval or air service, participated in a United States military operation for which an Armed Forces service medal was awarded pursuant to Executive Order 12985.</p>
                                                        </label>
                                                    </div>
                                                    <div class="question-row">
                                                        <label for="q12">
                                                            <input type="radio" value="I Am Not a Protected Veteran" name="veteran" id="q12"> <span>
                                                                <p class="text"><strong>I Am Not a Protected Veteran </strong>
                                                            </span></p>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="separator-border"></div>
                                            <div class="question-block">
                                                <h2>3. VOLUNTARY SELF-IDENTIFICATION OF DISABILITY<span class="staric">*</span></h2>
                                                <div class="question-block-inner">
                                                    <strong>Why are you being asked to complete this form? </strong>
                                                    <p>Because we do business with the government, we must reach out to, hire, and provide equal opportunity to qualified people with disabilities.i To help us measure how well we are doing, we are asking you to tell us if you have a disability or if you ever had a disability. Completing this form is voluntary, but we hope that you will choose to fill it out. If you are applying for a job, any answer you give will be kept private and will not be used against you in any way. </p>
                                                    <p>If you already work for us, your answer will not be used against you in any way. Because a person may become disabled at any time, we are required to ask all of our employees to update their information every five years. You may voluntarily self-identify as having a disability on this form without fear of any punishment because you did not identify as having a disability earlier. </p>
                                                    <strong>How do I know if I have a disability?</strong>
                                                    <p>You are considered to have a disability if you have a physical or mental impairment or medical condition that substantially limits a major life activity, or if you have a history or record of such an impairment or medical condition. </p>
                                                    <p>Disabilities include, but are not limited to: </p>
                                                </div>
                                            </div>
                                            <div class="disabilities-list">
                                                <ul>
                                                    <li>&raquo; &nbsp; Blindness</li>
                                                    <li>&raquo; &nbsp; Deafness</li>
                                                    <li>&raquo; &nbsp; Cancer</li>
                                                    <li>&raquo; &nbsp; Diabetes</li>
                                                    <li>&raquo; &nbsp; Epilepsy</li>
                                                </ul>
                                                <ul>
                                                    <li>&raquo; &nbsp; Autism</li>
                                                    <li>&raquo; &nbsp; Cerebral palsy</li>
                                                    <li>&raquo; &nbsp; HIV/AIDS</li>
                                                    <li>&raquo; &nbsp; Schizophrenia</li>
                                                    <li>&raquo; &nbsp; Muscular dystrophy </li>
                                                </ul>
                                                <ul>
                                                    <li>&raquo; &nbsp; Bipolar disorder</li>
                                                    <li>&raquo; &nbsp; Major depression</li>
                                                    <li>&raquo; &nbsp; Multiple sclerosis (MS)</li>
                                                    <li>&raquo; &nbsp; Missing limbs or partially missing limbs </li>
                                                </ul>
                                                <ul>
                                                    <li>&raquo; &nbsp; Post-traumatic stress disorder (PTSD)</li>
                                                    <li>&raquo; &nbsp; Obsessive compulsive disorder</li>
                                                    <li>&raquo; &nbsp; Impairments requiring the use of a wheelchair</li>
                                                    <li>&raquo; &nbsp; Intellectual disability (previously called mental retardation)</li>
                                                </ul>
                                            </div>
                                            <div class="question-block">
                                                <h2 style="text-decoration:underline;">Please check one of the boxes below: </h2>
                                                <div class="question-row">
                                                    <label for="disability">
                                                        <input type="radio" value="YES, I HAVE A DISABILITY" name="disability" id="disability"> <span>
                                                            <p class="text">YES, I HAVE A DISABILITY (or previously had a disability)
                                                        </span></p>
                                                    </label>
                                                </div>
                                                <div class="question-row">
                                                    <label for="no-disability">
                                                        <input type="radio" value="NO, I DON'T HAVE A DISABILITY" name="disability" id="no-disability"> <span>
                                                            <p class="text">NO, I DON'T HAVE A DISABILITY
                                                        </span></p>
                                                    </label>
                                                </div>
                                                <div class="question-row">
                                                    <label for="no-answer">
                                                        <input type="radio" value="I DON'T WISH TO ANSWER" name="disability" id="no-answer"> <span>
                                                            <p class="text">I DON'T WISH TO ANSWER
                                                        </span></p>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="separator-border"></div>
                                            <div class="question-block">
                                                <h2>4. GENDER (PLEASE CHECK ONE)<span class="staric">*</span></h2>
                                                <div class="fancy-radio-btn">
                                                    <label for="male">
                                                        <input type="radio" value="Male" name="gender" id="male"> <span>Male</span>
                                                    </label>
                                                    <label for="female">
                                                        <input type="radio" value="Female" name="gender" id="female"> <span>Female</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <?php } ?>
                            <li class="terms-conditions full-width">
                                <label for="squared" class="hint-label">I have Read and Understand the <a href="javascript:;" data-toggle="modal" data-target="#terms_and_conditions_apply_now">Terms & Conditions</a> and <a href="javascript:;" data-toggle="modal" data-target="#privay_policy_apply_now">Privacy Policy</a><span class="staric">*</span></label>
                                <input type="checkbox" required="required" name="check_box" value="1" id="squared">
                            </li>

                            <li>
                                <div class="g-recaptcha" data-callback="googleCaptchaChecker" data-sitekey="<?= getCreds('AHR')->GOOGLE_CAPTCHA_API_KEY_V2; ?>"></div>
                                <label id='captchaerror' style="display: none; float: none !important;color: #CC0000 !important;font-weight: 400;margin: 0 !important;">Empty/Invalid Captcha </label>
                            </li>

                            <li>
                                <input type="hidden" name='job_sid' id="job_sid" value="<?php echo $job_details['sid']; ?>">
                                <input type="hidden" name="questionnaire_sid" id="questionnaire_sid" value="<?php echo $job_details['questionnaire_sid']; ?>">
                                <input type="hidden" name="action" value="job_applicant">
                                <input type="hidden" name="applied_from" value="job">
                                <input id="mySubmitBtn" class="siteBtn bg-color" type="submit" onclick="validate_form()" value="apply now">
                                <!-- <input class="siteBtn bg-color" type="submit" value="apply now">-->
                            </li>
                        </form>
                    </ul>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </div>
</div>
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