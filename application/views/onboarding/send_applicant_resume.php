<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="applicant-profile-wrp">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="application-header">
                                <article>
                                    <figure>
                                        <img src="<?php echo isset($user_info['pictures']) && $user_info['pictures'] != NULL && $user_info['pictures'] != '' ? AWS_S3_BUCKET_URL . $user_info['pictures'] : base_url('assets/images/default_pic.jpg'); ?>" alt="Profile Picture" />
                                    </figure>
                                    <div class="text">
                                        <h2><?php echo $user_info["first_name"]; ?> <?= $user_info["last_name"] ?></h2>
                                        <div class="start-rating">
                                            <input readonly="readonly" id="input-21b" value="<?php echo isset($user_average_rating) ? $user_average_rating : 0; ?>" type="number" name="rating" class="rating" min=0 max=5 step=0.2  data-size="xs" />
                                        </div>
                                    </div>
                                </article>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area margin-top">
                                <?php if ($user_type == 'applicant') { ?>
                                    <span class="page-heading down-arrow">
                                        <a class="dashboard-link-btn" href="<?php echo base_url('applicant_profile/' . $user_info['sid']); ?>">
                                            <i class="fa fa-chevron-left"></i>Applicant Profile</a>
                                        Send Resume Request
                                    </span>
                                <?php } else if ($user_type == 'employee') { ?>
                                    <span class="page-heading down-arrow">
                                        <a class="dashboard-link-btn" href="<?php echo base_url('employee_profile/' . $user_info['sid']); ?>">
                                            <i class="fa fa-chevron-left"></i>Employee Profile</a>
                                        Send Offer Letter
                                    </span>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div id="step_onboarding">
                                <div id="getting_started" class="getting-started form-wrp">                               
                                    <form id="form_send_resume_request" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                        <input type="hidden" id="perform_action" name="perform_action" value="send_resume_request" />
                                        <div id="offer_letter" class="offer-letter">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="well well-sm">
                                                        <strong>Instructions:</strong>
                                                        <p>Please Compose message for <b><?php echo $user_info["first_name"]; ?> <?= $user_info["last_name"] ?></b></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="form-group auto-height">
                                                        <label>Subject<span class="staric">*</span></label>
                                                        <input type="text" id="resume_request_subject" name="resume_request_subject"  class="form-control" value="<?php echo !empty($resume_request) ? html_entity_decode($resume_request['requested_subject']) : $default_subject; ?>">
                                                    </div>
                                                    <span id="subject_error" class="text-danger"></span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="form-group auto-height">
                                                        <label>Resume Request Message<span class="staric">*</span></label>
                                                        <textarea id="resume_request_message" name="resume_request_message" class="ckeditor"><?php echo !empty($resume_request) ? html_entity_decode($resume_request['requested_message']) : $default_template; ?></textarea>
                                                    </div>
                                                    <span id="body_error" class="text-danger"></span>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="offer-letter-help-widget full-width form-group auto-height">
                                                        <div class="how-it-works-insturction">
                                                            <strong>How it Works:</strong>
                                                            <p class="how-works-attr">1. Insert multiple tags where applicable in Resume Request</p>
                                                            <p class="how-works-attr">2. Send the Resume Request</p>
                                                        </div>

                                                        <div class="tags-arae">
                                                            <div class="col-md-12">
                                                                <h5><strong>Company Information Tags:</strong></h5>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group auto-height">
                                                                    <input type="text" class="form-control tag" readonly="" value="{{company_name}}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group auto-height">
                                                                    <input type="text" class="form-control tag" readonly="" value="{{company_address}}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group auto-height">
                                                                    <input type="text" class="form-control tag" readonly="" value="{{company_phone}}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group auto-height">
                                                                    <input type="text" class="form-control tag" readonly="" value="{{career_site_url}}">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="tags-arae">
                                                            <div class="col-md-12">
                                                                <h5><strong>Employee / Applicant Tags :</strong></h5>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group auto-height">
                                                                    <input type="text" class="form-control tag" readonly="" value="{{first_name}}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group auto-height">
                                                                    <input type="text" class="form-control tag" readonly="" value="{{last_name}}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group auto-height">
                                                                    <input type="text" class="form-control tag" readonly="" value="{{email}}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group auto-height">
                                                                    <input type="text" class="form-control tag" readonly="" value="{{job_title}}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-right" style="top: 0;">
                                                    <div class="form-group auto-height">
                                                        <?php if(empty($resume_request)){ ?>
                                                            <a href="javascript:;" class="btn btn-success" id="send_resume_request">Send Resume Request</a>
                                                        <?php } else { ?>
                                                            <a href="<?php echo base_url('onboarding/send_requested_resume').'/'.$user_info['verification_key']; ?>" class="btn btn-primary" target="_blank">Preview Resume Request</a>
                                                            <a href="javascript:;" class="btn btn-warning" id="send_resume_request">Resend Resume Request</a>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php if ($user_type == 'applicant') { ?>
                    <?php $this->load->view('manage_employer/application_tracking_system/profile_right_menu_applicant'); ?>
                <?php } elseif($user_type == 'employee'){
                    $this->load->view('manage_employer/employee_management/profile_right_menu_employee_new');
                } ?>
            </div>
        </div>
    </div>
</div>
<!-- Main End -->
<script type="text/javascript">
    $("#send_resume_request").on('click', function () {
        
        var resume_request_subject = $('#resume_request_subject').val();
        var resume_request_message = CKEDITOR.instances['resume_request_message'].getData();
        
        if (resume_request_message == '' && resume_request_subject == '') {
            $('#subject_error').html('<b>Subject is required.</b>');
            $('#body_error').html('<b>Request Message is required.</b>');
        } else if (resume_request_subject == '') {
            $('#subject_error').html('<b>Subject is required.</b>');
            $('#body_error').html('');
        } else if (resume_request_message == '') {  
            $('#subject_error').html('');  
            $('#body_error').html('<b>Request Message is required.</b>');
        } else {
            $('#subject_error').html('');
            $('#body_error').html('');
            $('#form_send_resume_request').submit();
        }
    }); 
</script>