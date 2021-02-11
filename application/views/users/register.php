<script src='https://www.google.com/recaptcha/api.js'></script>
<div class="container">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
            <?php if (!$this->session->userdata('logged_in')) { ?>
                <div class="jobseeker_section">
                    <div class="sign-up-section">
                        <h2 class="form-heading">Create Your Company Account Now</h2>
                        <div class="required_message" style="font-size: 20px; font-weight: bold;">Create your company account now and get the first <?php echo NEW_ACCOUNT_EXPIRY_DAYS?> days for free. </div>
                        <div class="required_message">( <span class="required_icon">*</span> <?php echo NEW_ACCOUNT_EXPIRY_DAYS; ?> day free trial applies to our standard single rooftop package only )</div>
                        <div class="required_message">Fields marked with an asterisk (<span class="required_icon">*</span>) are mandatory</div>
                    </div>
                    <div class="registered-user">
                        <div class="error-panel">
                        </div>
                        <form method="post" class="job_seeker_form" action="" enctype="multipart/form-data" id="job_seeker_form">
                            <input type="hidden" name="action" value="register" />
                            <ul>
                                <div class="separator-div"><h2>company detail</h2></div>
                                <li>
                                    <div class="job_seeker_label">Company Name
                                        <span class="required_icon">&nbsp;*</span>
                                    </div>
                                    <div class="fields-wrapper  ">
                                        <input id="CompanyName" type="text" value="<?php echo set_value('CompanyName'); ?>" class="form-fileds " name="CompanyName" required="required" />
                                        <?php echo form_error('CompanyName'); ?>
                                    </div>
                                </li>
                                <li>
                                    <div class="job_seeker_label">Contact Name
                                        <span class="required_icon">&nbsp;*</span>
                                    </div>
                                    <div class="fields-wrapper  ">
                                        <input id="ContactName" type="text" value="<?php echo set_value('ContactName'); ?>" class="form-fileds " name="ContactName" required="required" />
                                        <?php echo form_error('ContactName'); ?>
                                    </div>
                                </li>
                                <li  id="Location_Country">
                                    <div class="job_seeker_label">Country
                                        <span class="required_icon">&nbsp;</span>
                                    </div>
                                    <div class="fields-wrapper">
                                        <div class="inputField">
                                            <select class="select_field_dropdown" name="Location_Country" onchange="getStates(this.value, <?php echo $states; ?>)">
                                                <option <?php echo set_select('Location_Country', ''); ?> value=""> Select Country </option>
                                                        <?php foreach ($active_countries as $active_country) { ?>
                                                            <option value="<?= $active_country["sid"]; ?>"
                                                            <?php   if (set_value('Location_Country') == $active_country["sid"]) {
                                                                        echo 'selected';
                                                                    } ?>>
                                                                    <?php echo $active_country["country_name"]; ?>
                                                            </option>
                                                        <?php } ?>
                                            </select>
                                            <span class="select_icon_new"><i class="fa fa-angle-down"></i></span>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="job_seeker_label">State
                                        <span class="required_icon">&nbsp;</span>
                                    </div>
                                    <div class="fields-wrapper">
                                        <div class="inputField">
                                            <select class="select_field_dropdown" name="Location_State" id="state">
                                                <?php   if (empty($country_id)) { ?><option value="">Select State </option><option value="">Select Your Country </option> 
                                                <?php   } else {
                                                            foreach ($active_states[$country_id] as $active_state) { ?>
                                                                <option <?php if (set_value('Location_State') == $active_state["sid"]) { ?> selected="selected" <?php } ?> value="<?= $active_state["sid"] ?>"><?= $active_state["state_name"] ?></option>
                                                                <?php
                                                            }
                                                        } ?>
                                            </select>
                                            <span class="select_icon_new"><i class="fa fa-angle-down"></i></span>
                                        </div>
                                    </div>
                                </li>
                                <li  id="Location_City">
                                    <div class="job_seeker_label">City
                                        <span class="required_icon">&nbsp;</span>
                                    </div>
                                    <div class="fields-wrapper">
                                        <div class="inputField"><input id="Location_City" type="text" value="<?php echo set_value('Location_City'); ?>" class="form-fileds " name="Location_City" />
                                        </div>
                                    </div>
                                </li>
                                <li  id="Location_ZipCode">
                                    <div class="job_seeker_label">Zip Code
                                        <span class="required_icon">&nbsp;</span>
                                    </div>
                                    <div class="fields-wrapper">
                                        <div class="inputField"><input id="Location_ZipCode" type="text" class="form-fileds " value="<?php echo set_value('Location_ZipCode'); ?>" name="Location_ZipCode" /></div>
                                    </div>
                                </li>
                                <li  id="Location_Address">
                                    <div class="job_seeker_label">Address
                                        <span class="required_icon">&nbsp;</span>
                                    </div>
                                    <div class="fields-wrapper">
                                        <div class="inputField"><input id="Location_Address" type="text" value="<?php echo set_value('Location_Address'); ?>" class="form-fileds " name="Location_Address" /></div>
                                    </div>
                                </li>
                                <li>
                                    <div class="job_seeker_label">Phone Number
                                        <span class="required_icon">&nbsp;</span>
                                    </div>
                                    <div class="fields-wrapper">
                                        <input id="PhoneNumber" type="text" value="<?php echo set_value('PhoneNumber'); ?>" class="form-fileds " name="PhoneNumber" />
                                    </div>
                                </li>
                                <li>
                                    <div class="job_seeker_label">Company Description
                                        <span class="required_icon">&nbsp;</span>
                                    </div>
                                    <div class="fields-wrapper">
                                        <textarea  class="ckeditor" name="CompanyDescription" rows="8" cols="60" ><?php echo set_value('CompanyDescription'); ?></textarea>
                                </li>
                                <li>
                                    <div class="job_seeker_label">Logo
                                        <span class="required_icon">&nbsp;</span>
                                    </div>
                                    <div class="fields-wrapper">
                                        <div id="logo_field_content_Logo">
                                            <div id="autoloadFileSelect_Logo" >
                                                <div class="fileUpload"  >
                                                    <span>Browse</span>
                                                    <input type="file" field_id="Logo" field_action="upload_profile_logo" field_target="logo_field_content_Logo" name="Logo" class="upload autouploadField " id="logo_upload" />
                                                </div>
                                                <span id="logo_message" class="file_message">No file chosen</span>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="job_seeker_label">YouTube Video
                                        <span class="required_icon">&nbsp;</span>
                                    </div>
                                    <div class="fields-wrapper  ">
                                        <input type="text" value="<?php echo set_value('YouTubeVideo'); ?>" class="form-fileds youtube_link " name="YouTubeVideo" /><br/>
                                        <i class="help_message"><b>e.g.</b> https://www.youtube.com/watch?v=XXXXXXXXXXX</i>
                                        <?php echo form_error('YouTubeVideo'); ?>
                                    </div>
                                </li>
                                <div class="separator-div"><h2>Your details</h2></div>
                                <li>
                                    <div class="job_seeker_label">First Name
                                        <span class="required_icon">&nbsp;*</span>
                                    </div>
                                    <div class="fields-wrapper  ">
                                        <input type="text" value="<?php echo set_value('first_name'); ?>" class="form-fileds " name="first_name" /><span class="aMessage"></span>
                                        <?php echo form_error('first_name'); ?>
                                    </div>
                                </li>
                                <li>
                                    <div class="job_seeker_label">Last name
                                        <span class="required_icon">&nbsp;*</span>
                                    </div>
                                    <div class="fields-wrapper  ">
                                        <input type="text" value="<?php echo set_value('last_name'); ?>" class="form-fileds " name="last_name" /><span class="aMessage"></span>
                                        <?php echo form_error('last_name'); ?>
                                    </div>
                                </li>
                                <li>
                                    <div class="job_seeker_label">User name
                                        <span class="required_icon">&nbsp;*</span>
                                    </div>
                                    <div class="fields-wrapper  ">
                                        <input type="text" value="<?php echo set_value('username'); ?>" class="form-fileds " name="username" /><span class="aMessage" id="am_username"></span>
                                        <?php echo form_error('username'); ?>
                                    </div>
                                </li>
                                <li>
                                    <div class="job_seeker_label">Password
                                        <span class="required_icon">&nbsp;*</span>
                                    </div>
                                    <div class="fields-wrapper  ">
                                        <input type="password" id="password" name="password" class="inputString form-fileds " />
                                        <input style="margin-top:12px" type="password" placeholder="Confirm Password"  name="passconf" class="form-fileds inputString " />
                                        <?php echo form_error('passconf'); ?>
                                    </div>
                                </li>
                                <li>
                                    <div class="job_seeker_label">Email
                                        <span class="required_icon">&nbsp;*</span>
                                    </div>
                                    <div class="fields-wrapper  ">
                                        <input type="text" value="<?php echo set_value('email'); ?>" class="form-fileds " name="email" onblur="checkField($(this), 'email')"/><span class="aMessage" id="am_email"></span>
                                        <?php echo form_error('email'); ?>
                                    </div>
                                </li>
                                <li>
                                    <div class="job_seeker_label">Your Picture
                                        <span class="required_icon">&nbsp;</span>
                                    </div>
                                    <div class="fields-wrapper  ">
                                        <div id="logo_field_content_Logo">
                                            <div id="autoloadFileSelect_Logo" >
                                                <div class="fileUpload"  >
                                                    <span>Browse</span>
                                                    <input type="file"
                                                           field_id="Logo"
                                                           field_action="upload_profile_logo"
                                                           field_target="logo_field_content_Logo"
                                                           name="profile_picture"
                                                           class="upload autouploadField "
                                                           id="profile_upload"
                                                           />
                                                </div>
                                                <span id="profile_message" class="file_message">No file chosen</span>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="job_seeker_label">Please Check The Box
                                        <span class="required_icon">&nbsp;*</span>
                                    </div>
                                    <div class="fields-wrapper  ">
                                        <div class="g-recaptcha" data-sitekey="6Les2Q0TAAAAAAyeysl-dZsPUm98_6K2fNkyNCwI"></div>
                                    </div>
                                    <?php echo form_error('g-recaptcha-response'); ?>
                                </li>
                                <li class="btn-responsive">
                                    <div class="fields-wrapper register_field">
                                        <input type="submit" class="reg-btn" onclick="validate_form()" value="Register" />
                                    </div>
                                </li>
                            </ul>
                        </form>
                    </div>
                    <div class="clear"></div>
                </div>
            <?php } else { ?>
                <div class="login-section ">
                    <div class="registered-user">
                        <h2 class="form-heading">Create Your Free Company Account Now.</h2>
                        <p class="error_message"><i class="fa fa-exclamation-circle"></i>
                            You are currently logged in as <b><?php echo $_SESSION["logged_in"]["employer_detail"]["username"]; ?></b><br>
                        </p>
                    </div>
                    <div class="clear"></div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<script  language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script  language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script>
        function getStates(val, states) {
            var html = '';
            if (val == '') {
                $('#state').html('<option value="">Select State</option><option value="">Select Your Country</option>');
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

        jQuery(document).ready(function () {
            jQuery("#logo_upload").change(function () {
                var value = jQuery(this).val();
                jQuery("#logo_message").html(value);
            });
            jQuery("#profile_upload").change(function () {
                var value = jQuery(this).val();
                jQuery("#profile_message").html(value);
            });
        });


        function validate_form() {
            $("#job_seeker_form").validate({
                ignore: ":hidden:not(select)",
                rules: {
                    CompanyName: {
                        required: true,
                        pattern: /^[a-zA-Z0-9\- ]+$/
                    },
                    ContactName: {
                        required: true,
                        pattern: /^[a-zA-Z0-9\- .]+$/
                    },
                    Location_City: {
                        pattern: /^[a-zA-Z0-9\- ]+$/
                    },
                    Location_ZipCode: {
                        pattern: /^[a-zA-Z0-9\- ]+$/
                    },
                    Location_Address: {
                        pattern: /^[a-zA-Z0-9\-#,':;. ]+$/
                    },
                    PhoneNumber: {
                        pattern: /^[0-9\-]+$/
                    },
                    first_name: {
                        required: true,
                        pattern: /^[a-zA-Z0-9\- ]+$/
                    },
                    last_name: {
                        required: true,
                        pattern: /^[a-zA-Z0-9\- ]+$/
                    },
                    username: {
                        required: true,
                        pattern: /^[a-zA-Z0-9\-]+$/
                    },
                    password: {
                        required: true,
                    },
                    passconf: {
                        required: true,
                        equalTo: "#password"
                    },
                    email: {
                        required: true,
                        email: true
                    }
                },
                messages: {
                    CompanyName: {
                        required: 'Company Name is required',
                        pattern: 'Letters, numbers, and dashes only please'
                    },
                    ContactName: {
                        required: 'Contact Name is required',
                        pattern: 'Letters, numbers, and dashes only please'
                    },
                    Location_City: {
                        required: 'City Name is required',
                        pattern: 'Please Provide valid City'
                    },
                    Location_ZipCode: {
                        required: 'Zip Code is required',
                        pattern: 'Please provide valid Zip Code'
                    },
                    Location_Address: {
                        required: 'Address is required',
                        pattern: 'Please provide valid Address'
                    },
                    PhoneNumber: {
                        required: 'Phone Number is required',
                        pattern: 'Please provide valid Phone Number'
                    },
                    username: {
                        required: 'Username is required',
                        pattern: 'Please provide valid username'
                    },
                    first_name: {
                        required: 'First Name is required',
                        pattern: 'Please provide valid First Name'
                    },
                    last_name: {
                        required: 'Last Name is required',
                        pattern: 'Please provide valid Last Name'
                    },
                    password: {
                        required: 'Password is required'
                    },
                    passconf: {
                        required: 'Confirm Password does not match'
                    },
                    email: {
                        required: 'Please provide Valid email'
                    }
                },
                submitHandler: function (form) {
                    form.submit();
                }
            });
        }
</script>