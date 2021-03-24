<?php if($this->uri->segment(1) == 'my_credentials') $sideBar = '';
else $sideBar = onboardingHelpWidget($company_info['sid']);
 ?>
<div class="main">
    <div class="container">
        
        <div class="row">
            <?php if($this->uri->segment(1) != 'my_credentials'){ ?>
            <div class="col-sm-12">
                <p style="color: #cc0000;"><b><i>We suggest that you only use Google Chrome to access your account
                            and use its Features. Internet Explorer is not supported and may cause certain feature
                            glitches and security issues.</i></b></p>
                            <?=$sideBar;?>
            </div>
            <?php } ?>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-4">
                <?php   if($enable_learbing_center && $company_eeoc_form_status == 1) {
                            $back_btn_function = 'learning_center';
                        } else if(!$enable_learbing_center && $company_eeoc_form_status == 0) {
                            $back_btn_function = 'hr_documents';
                        } else if($enable_learbing_center && $company_eeoc_form_status == 0) {
                            $back_btn_function = 'learning_center';
                        } else if(!$enable_learbing_center && $company_eeoc_form_status == 1) {
                            $back_btn_function = 'eeoc_form';
                        } ?>
                    <a href="<?php echo base_url('onboarding/'.$back_btn_function.'/' . $unique_sid); ?>" class="btn btn-info btn-block"><i class="fa fa-angle-left"></i> Review Previous Step</a>
                </div>

            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="page-header">
                    <h1 class="section-ttile">My Credentials</h1>
                </div>
                <div class="form-wrp full-width">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="well well-sm bg-white">
                                <strong>Instructions:</strong>
                                <?php   $instructions = html_entity_decode($credentials['instructions']); 
                                        $mystring = 'lorem ipsum';
                                        $pos = strpos(strtolower($instructions), $mystring);

                                        if ($pos === false) {
                                            // it is clean instructions. no need to change 
                                        } else {
                                           $instructions = '<p>Please create your login credentials to access your employee panel.</p><p><strong>Suggestion for User Name:</strong><br />You can use your first name and last name all one word all lower case. Example: johnsmith<br />If the username is already taken, you can add any number to it.</p><p><strong>Suggestion for Password:</strong><br />Please create a secure password using Alpha Numeric and special character combinations and do not share your password with anyone.</p>'; 
                                        }
                                ?>
                                <div><?php echo html_entity_decode($instructions); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group autoheight">
                                <?php $field_name = 'email'; ?>
                                <?php echo form_label('Email', $field_name); ?>
                                <input type="text" class="form-control" readonly value="<?php echo $applicant[$field_name]; ?>" />
                                <?php echo form_error($field_name); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group autoheight">
                                <?php $field_name = 'access_level'; ?>
                                <?php echo form_label('Access Level', $field_name); ?>
                                <input type="text" class="form-control" readonly value="<?php echo $credentials[$field_name]; ?>" />
                                <?php echo form_error($field_name); ?>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group autoheight">
                                <?php $field_name = 'joining_date'; ?>
                                <?php echo form_label('Joining Date', $field_name); ?>
                                <input type="text" class="form-control" id="joining_date" readonly value="<?php echo $credentials[$field_name]; ?>" />
                                <?php echo form_error($field_name); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <hr />
                <div class="form-wrp full-width">
                    <form id="form_my_credentials" method="post" enctype="multipart/form-data" action="<?php echo base_url('hire_onboarding_applicant/index/' . $unique_sid ); ?>">
                        <input type="hidden" id="perform_action" name="perform_action" value="hire_applicant" />
                        <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $onboarding_details['company_sid']; ?>" />
                        <input type="hidden" id="applicant_sid" name="applicant_sid" value="<?php echo $onboarding_details['applicant_sid']; ?>" />
                        <input type="hidden" id="job_list_sid" name="job_list_sid" value="<?php echo $onboarding_details['job_list_sid']; ?>" />
                        <input type="hidden" id="applicant_job_sid" name="applicant_job_sid" value="<?php echo $onboarding_details['job_sid']; ?>" />
                        <input type="hidden" id="applicant_email" name="applicant_email" value="<?php echo $applicant['email']; ?>" />
                        <input type="hidden" id="unique_sid" name="unique_sid" value="<?php echo $unique_sid; ?>" />
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group autoheight">
                                    <?php $field_name = 'username'; ?>
                                    <?php echo form_label('Username <span class="required">*</span>', $field_name); ?> <br /> Username should consist of a minimum of 5 characters.
                                    <?php echo form_input($field_name, '', 'class="form-control" id="' . $field_name . '"'); ?>
                                    <?php echo form_error($field_name); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group autoheight">
                                    <?php $field_name = 'password'; ?>
                                    <?php echo form_label('Password <span class="required">*</span>', $field_name); ?> <br /> Password should consist of a minimum of 6 characters.
                                    <input type="password"
                                           class="form-control"
                                           id="<?php echo $field_name; ?>"
                                           name="<?php echo $field_name; ?>"
                                           value=""
                                    />
                                    <?php echo form_error($field_name); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group autoheight">
                                    <?php $field_name = 'repeat_password'; ?>
                                    <?php echo form_label('Repeat Password <span class="required">*</span>', $field_name); ?> <br /> Password should consist of a minimum of 6 characters.
                                    <input type="password"
                                           class="form-control"
                                           id="<?php echo $field_name; ?>"
                                           name="<?php echo $field_name; ?>"
                                           value=""
                                    />
                                    <?php echo form_error($field_name); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
                                <a href="<?php echo $enable_learbing_center ? base_url('onboarding/learning_center/' . $unique_sid) : base_url('onboarding/eeoc_form/' . $unique_sid); ?>" class="btn btn-info mb-2">Review Previous Step</a>
                                <button onclick="func_create_user_credentials();" type="button" class="btn btn-success mb-2">Complete Onboarding</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div> 
                    <?php if($sideBar != ''){ ?>
</div>
<?php } ?>                             
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#joining_date').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            maxDate: new Date(),
            yearRange: "<?php echo JOINING_DATE_LIMIT; ?>",
        }).val();

        $('#form_my_credentials').validate({
            rules: {
                username: {
                    required: true,
                    minlength: 5,
                    pattern: /^[a-zA-Z0-9\-]+$/
                },
                password: {
                    required: true,
                    minlength: 6
                },
                repeat_password: {
                    required: true,
                    minlength: 6,
                    equalTo: '#password'
                }
            },
            messages: {
                username: {
                    required: 'Username is required',
                    pattern: 'Please provide valid username',
                    minlength: 'The User Name field must be at least 5 characters in length.',
                },
                password: {
                    required: 'Password is required',
                    minlength: 'Password should be 6 digits'
                },
                repeat_password: {
                    required: 'Confirm password is required',
                    minlength: 'Confirm Password should be 6 digits',
                    equalTo: "Password doesn't matched"
                }
            }
        });
    });

    function func_create_user_credentials(){
        if($('#form_my_credentials').valid()){
            alertify.confirm(
                'Are you sure?',
                'Are you sure you want to Create your user Credentials with the provided Information?',
                function(){

                    var form_data = new FormData();
                    form_data.append('company_sid', <?php echo $onboarding_details['company_sid']; ?>);
                    form_data.append('user_sid', <?php echo $onboarding_details['applicant_sid']; ?>);
                    form_data.append('user_type', 'applicant');
                form_data.append('action', 'check_user_complete_general_document');

                    $.ajax({
                        url: '<?= base_url('hr_documents_management/handler') ?>',
                        cache: false,
                        contentType: false,
                        processData: false,
                        type: 'post',
                        data: form_data,
                        success: function (resp) {
                            if (resp.Response.length > 0) {
                                var applicant_id = '<?php echo $this->uri->segment(3); ?>';
                                alertify.alert(
                                    'WARNING!', 
                                    `Please, complete the following documents to finish the onboarding process.  <br /> <strong>${resp.Response.join(',<br />')}</strong>`
                                );
                                
                                return;
                            }
                            $('#form_my_credentials').submit();
                        },
                        error: function () {
                        }
                    });
                    // 
                },
                function(){
                    alertify.error('Canceled!');
                }).set('labels', {ok:'Yes!', cancel:'No!'});
        }
    }

</script>