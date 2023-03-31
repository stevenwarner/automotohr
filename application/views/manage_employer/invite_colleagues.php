<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/employer_column_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="dashboard-conetnt-wrp">
                        <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                        <div class="page-header-area">
                            <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?><?php echo $title; ?></span>
                        </div>
                        <div class="create-job-wrap">
                            <!-- <div class="job-title-text">                
                                <h1 class="title-bar-heading">Hooray, you're ready to send an offer!</h1>
                                <p>Start by adding your new hire and next you'll select the onboarding docs to send (offer letter, I9, W4, etc).</p>
                            </div> -->
                            <!-- <div class="form-col-100">
                                <p class="form-setps-title">Step 1 (of 2): Send Offer Letter</p>
                            </div> -->
                            <div class="row">
                                <div class="col-lg-7 col-md-7 col-xs-12 col-sm-12">
                                    <form id="employers_add" action="" method="POST" enctype="multipart/form-data" autocomplete="off">
                                        <div class="universal-form-style-v2">
                                            <ul>
                                                <li class="form-col-100 autoheight">
                                                    <label>First Name<span class="staric">*</span></label>
                                                    <input type="text" autocomplete="nope" class="invoice-fields" name="first_name" id="first_name" value="<?php
                                                                                                                                                            if (isset($formpost['first_name'])) {
                                                                                                                                                                echo $formpost['first_name'];
                                                                                                                                                            }
                                                                                                                                                            ?>">
                                                    <?php echo form_error('first_name'); ?>
                                                </li>
                                                <li class="form-col-100 autoheight">
                                                    <label>Last Name<span class="staric">*</span></label>
                                                    <input type="text" autocomplete="nope" class="invoice-fields" name="last_name" id="last_name" value="<?php
                                                                                                                                                            if (isset($formpost['last_name'])) {
                                                                                                                                                                echo $formpost['last_name'];
                                                                                                                                                            }
                                                                                                                                                            ?>">
                                                    <?php echo form_error('last_name'); ?>
                                                </li>
                                                <li class="form-col-100 autoheight">
                                                    <label>E-Mail<span class="staric">*</span></label>
                                                    <input type="email" autocomplete="nope" class="invoice-fields" name="email" id="email" value="<?php
                                                                                                                                                    if (isset($formpost['email'])) {
                                                                                                                                                        echo $formpost['email'];
                                                                                                                                                    }
                                                                                                                                                    ?>">
                                                    <?php echo form_error('email'); ?>
                                                </li>
                                                <li class="form-col-100 autoheight">
                                                    <label>Job Title<span class="staric">*</span></label>
                                                    <input type="text" autocomplete="nope" class="invoice-fields" name="job_title" id="job_title" value="<?php echo set_value('job_title'); ?>">
                                                    <?php echo form_error('job_title'); ?>
                                                </li>
                                                <li class="form-col-100">
                                                    <label>Access Level:<span class="staric">*</span></label>
                                                    <div class="hr-select-dropdown">
                                                        <select class="invoice-fields" name="access_level">
                                                            <option value="">Assign Security Access</option>
                                                            <?php //$accessLevels = explode(',', $access_levels[0]); 
                                                            ?>
                                                            <?php foreach ($access_levels as $accessLevel) { ?>
                                                                <option value="<?php echo $accessLevel; ?>" <?php
                                                                                                            if (isset($formpost['access_level']) && $accessLevel == $formpost['access_level']) {
                                                                                                                echo "selected";
                                                                                                            }
                                                                                                            ?>>
                                                                    <?php echo $accessLevel; ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <?php echo form_error('access_level'); ?>
                                                </li>





                                                <li class="form-col-100 autoheight">
                                                    <label>Start Date<span class="staric">*</span></label>
                                                    <input class="invoice-fields startdate" name="registration_date" type="text" autocomplete="nope" value="<?php echo set_value('registration_date'); ?>" readonly>
                                                    <?php echo form_error('registration_date'); ?>
                                                </li>
                                                <li class="form-col-100 autoheight hidden">
                                                    <label>Employment Status<span class="staric">*</span></label>
                                                    <select class="invoice-fields" name="employee-status" id="employee-status">
                                                        <option value="permanent">Permanent</option>
                                                        <option value="probation">Probation</option>
                                                        <option value="contractual">Contractual</option>
                                                        <option value="trainee">Trainee</option>
                                                        <option value="other">Other</option>
                                                    </select>
                                                </li>
                                                <li class="form-col-100 autoheight">
                                                    <label>Employment Type<span class="staric">*</span></label>
                                                    <select class="invoice-fields" name="employee-type" id="employee-type">
                                                        <option value="fulltime">Full-time</option>
                                                        <option value="parttime">Part-time</option>
                                                        <!-- <option value="casual">Casual</option>
                                                        <option value="fixedterm">Fixed term</option>
                                                        <option value="apprentices-and-trainees">Apprentices and trainees</option>
                                                        <option value="commission-and-piece-rate-employees">Commission and piece rate employees</option> -->
                                                    </select>
                                                </li>
                                                <li class="form-col-100 autoheight">
                                                    <label>Gender:</label>
                                                    <select class="invoice-fields" name="gender">
                                                        <option value="">Please Select Gender</option>
                                                        <option <?= $formpost["gender"] == 'male' ? 'selected' : ''; ?> value="male">Male</option>
                                                        <option <?= $formpost["gender"] == 'female' ? 'selected' : ''; ?> value="female">Female</option>
                                                        <option <?= $formpost["gender"] == 'other' ? 'selected' : ''; ?> value="other">Other</option>
                                                    </select>

                                                </li>


                                                <li class="form-col-100 autoheight">
                                                    <label>Team:</label>
                                                    <?= get_company_departments_teams(
                                                        $company_id,
                                                        'teamId'
                                                    ); ?>

                                                </li>
                                                
                                                


                                                <li class="form-col-100 autoheight">
                                                <label>I Speak:</label>
                                                </li>
                                                <li class="form-col-100 autoheight">
                                                <label class="control control--checkbox">
                                                        <input type="checkbox" name="secondaryLanguages[]" value="english" checked/> English
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </li>
                                                <li class="form-col-100 autoheight">
                                                <label class="control control--checkbox">
                                                        <input type="checkbox" name="secondaryLanguages[]" value="spanish" /> Spanish
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </li>
                                                <li class="form-col-100 autoheight">
                                                <label class="control control--checkbox">
                                                        <input type="checkbox" name="secondaryLanguages[]" value="russian" /> Russian
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </li>
                                                <li class="form-col-100 autoheight">
                                                <label class="control control--checkbox">
                                                        <input type="checkbox" name="secondaryOption" value="other" /> Others
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </li>


                                                <li class="form-col-100 autoheight jsOtherLanguage dn">
                                                <div class="col-sm-12">
                                                    <input type="text" class="invoice-fields" name="secondaryLanguages[]" placeholder="French, German" value="" />
                                                    <p><strong class="text-danger"><i>Add comma separated languages. e.g. French, German</i></strong></p>
                                                </div>
                                                </li>

                                            <script>
                                                $('[name="secondaryOption"]').click(function() {
                                                    $('.jsOtherLanguage').toggleClass('dn');
                                                });
                                            </script>

                                                <li class="form-col-100 autoheight">

                                                    <div class="row js-timezone-row">
                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                            <div class=" input-grey  ">
                                                                <?php $field_id = 'timezone'; ?>
                                                                <?php echo form_label('Timezone:', $field_id); ?>
                                                                <?= timezone_dropdown(
                                                                    '',
                                                                    array(
                                                                        'class' => 'invoice-fields js-timezone ',
                                                                        'id' => 'timezone',
                                                                        'name' => 'timezone'
                                                                    )
                                                                ); ?>

                                                            </div>
                                                        </div>
                                                    </div>

                                                </li>




                                                <li class="form-col-50-right autoheight" style="display: none">
                                                    <div class="checkbox-field">
                                                        <figure>
                                                            <input name="employeeType" class="employeeRadio" value="direct_hiring" type="radio" <?php echo set_radio('employeeType', 'direct_hiring'); ?> id="direct_hiring" required>
                                                        </figure>
                                                        <div class="text">
                                                            <label for="direct_hiring">Direct Hiring & Current Employees</label>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="form-col-50-left autoheight" style="display: none">
                                                    <div class="checkbox-field">
                                                        <figure>
                                                            <input name="employeeType" class="employeeRadio" value="onboard_hiring" type="radio" <?php echo set_radio('employeeType', 'onboard_hiring'); ?> id="onboard_hiring" required>
                                                        </figure>
                                                        <div class="text">
                                                            <label for="onboard_hiring">Onboarding & New Hires</label>
                                                        </div>
                                                    </div>
                                                </li>
                                                <div class="direct_hiring_div" style="display: none">
                                                    <li class="form-col-100 autoheight">
                                                        <label>Username:<span class="staric">*</span></label>
                                                        <input type="text" autocomplete="nope" class="invoice-fields" name="username" id="username" value="<?php
                                                                                                                                                            if (isset($formpost['username'])) {
                                                                                                                                                                echo $formpost['username'];
                                                                                                                                                            }
                                                                                                                                                            ?>">
                                                        <?php echo form_error('username'); ?>
                                                        <div class="video-link" style="font-style: italic;"><b></b>
                                                            Please create a Username for your Employee / Team member using their first and last name all one word all lower case. Example: johnsmith
                                                        </div>
                                                    </li>
                                                </div>
                                                <li class="form-col-100 autoheight">
                                                    <label class="control control--checkbox">
                                                        Send Email
                                                        <input class="select-domain" type="checkbox" name="send_welcome_email" value="1" checked="checked">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </li>
                                                <div class="video-link" style="font-style: italic;"><b></b>
                                                    <p><b>
                                                            If "Send Email" checkbox is checked then an email will automatically be sent to your Employee / Team member requesting that they create their own secure password. They will be able to log in to your platform as soon as they have created their password.
                                                        </b></p>
                                                </div>
                                                <li class="form-col-100 autoheight save-send-btn">
                                                    <input type="hidden" name="formsubmit" value="1">
                                                    <input type="submit" value="Save" onclick="return validate_form()" class="submit-btn btn-block">
                                                </li>
                                            </ul>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-lg-5 col-md-5 col-xs-12 col-sm-12">
                                    <!-- <div class="tick-list-box">
                                        <h2>New Hire Benefits</h2>
                                        <ul>
                                            <li>Onboard in just two easy steps</li>
                                            <li>Send a digital offer letter for e-signing</li>
                                            <li>Automate I-9, W-4 and delivery of HR docs</li>
                                            <li>All documents stored under My Employees</li>
                                        </ul>
                                    </div> -->
                                    <div class="tick-list-box">
                                        <h2><?php echo STORE_NAME; ?> is Secure</h2>
                                        <ul> 
                                            <li>Transmissions encrypted by Amazon Web Services® SSL</li>
                                            <li>Information treated confidential by AutomotoHR</li>
                                            <!-- <li>Receive emails with your signed paperwork</li> -->
                                        </ul>
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
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<!--<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/modernizr-custom.js"></script>-->
<script language="JavaScript" type="text/javascript">
    $(document).ready(function() {
        $("#direct_hiring").click();

        if ($('#direct_hiring').is(':checked')) {
            $(".direct_hiring_div").css('display', 'block');
            $("input[name='username']").prop('required', true);
            $("input[name='password']").prop('required', true);
        } else {
            $(".direct_hiring_div").css('display', 'none');
            $("input[name='username']").prop('required', false);
            $("input[name='password']").prop('required', false);
        }

        //
        $('#timezone').val('<?php echo $formpost['timezone']; ?>');
        $('#department').val('<?php echo $formpost['department']; ?>');

    });

    $('.employeeRadio').click(function() {
        if ($('#direct_hiring').is(':checked')) {
            $(".direct_hiring_div").css('display', 'block');
            $("input[name='username']").prop('required', true);
            $("input[name='password']").prop('required', true);
        } else {
            $(".direct_hiring_div").css('display', 'none');
            $("input[name='username']").prop('required', false);
            $("input[name='password']").prop('required', false);
        }
    });

    function getStates(val, states) {
        var html = '';
        if (val == '') {
            $('#state').html('<option value="">Select State</option><option value="">Please Select your country</option>');
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

    function validate_form() {
        $("#employers_add").validate({
            ignore: ":hidden:not(select)",
            rules: {
                first_name: {
                    required: true,
                    pattern: /^[a-zA-Z0-9\- ]+$/
                },
                last_name: {
                    required: true,
                    pattern: /^[a-zA-Z0-9\- ]+$/
                },
                job_title: {
                    required: true
                },
                access_level: {
                    required: true
                },
                salary_type: {
                    required: true
                },
                salary_amount: {
                    required: true
                },
                registration_date: {
                    required: true
                },
                email: {
                    required: true,
                    email: true
                },
                Location_City: {
                    pattern: /^[a-zA-Z0-9\- ]+$/
                },
                Location_ZipCode: {
                    pattern: /^[0-9\-]+$/
                },
                salary: {
                    pattern: /^[0-9\-]+$/
                }
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
                job_title: {
                    required: 'Job Title is required'
                },
                access_level: {
                    required: 'Assign security access level'
                },
                salary_type: {
                    required: 'Salary type is required'
                },
                salary_amount: {
                    required: 'Salary amount is required'
                },
                registration_date: {
                    required: 'Starting Date is required'
                },
                email: {
                    required: 'email is required',
                    email: 'Valid email Please'
                },
                username: {
                    required: 'Username is required',
                    pattern: 'Letters, numbers, and dashes only please'
                },
                Location_City: {
                    pattern: 'Letters, numbers, and dashes only please',
                },
                Location_ZipCode: {
                    pattern: 'Numeric values and dashes only please'
                },
                password: {
                    required: 'Password is required'
                },
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    }

    function check_file(val) {
        var fileName = $("#" + val).val();
        //            console.log(fileName);
        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
            var ext = fileName.split('.').pop();
            ext = ext.toLowerCase();
            if (val == 'profile_picture') {
                if (ext != "jpg" && ext != "jpeg" && ext != "png" && ext != "jpe" && ext != "JPG" && ext != "JPEG" && ext != "JPE" && ext != "PNG") {
                    $("#" + val).val(null);
                    alertify.error("Please select a valid Image format.");
                    $('#name_' + val).html('<p class="red">Only (.jpg .jpeg .png) allowed!</p>');
                    return false;
                } else
                    return true;
            }
        } else {
            $('#name_' + val).html('No file selected');
        }
    }

    $('.startdate').datepicker({
        dateFormat: 'mm-dd-yy',
        changeMonth: true,
        changeYear: true,
        yearRange: "<?php echo DOB_LIMIT; ?>"
    }).val();

    $('.jsSelect2').select2();
</script>