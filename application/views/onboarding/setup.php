<?php
$watch_video_base_url = '';

if (isset($applicant)) {
    $watch_video_base_url = base_url('onboarding/watch_video/' . $unique_sid);
} else if (isset($employee)) {
    $watch_video_base_url = base_url('learning_center/watch_video/');
}

if ($user_type == 'applicant') {
    $sendNotification = "yes";
    $sendNotificationText = "";
    $sendNotificationURL = "";

    if (!checkOnboardingNotification($user_info['sid'])) {
        $sendNotification = "no";
        $sendNotificationText = "The Onboarding email notification for this candidate is still pending.";
        $sendNotificationURL = base_url('onboarding/setup/applicant') . '/' . $user_info['sid'] . '/' . $job_list_sid . '#send_email_to_applicant';
    }
}
//echo '<pre>'; print_r($items); echo '</pre>';
?>
<style type="text/css">
    .notice_div {
        float: left;
        width: 100%;
        margin: 0 0 20px 0;
        position: relative;
    }

    .notice_div_area {
        color: #fff;
        float: left;
        width: 100%;
        padding: 11px;
        font-size: 18px;
        font-weight: 600;
        position: relative;
        border-radius: 5px;
        border: none;
        overflow: hidden;
        background-color: #b4052c;
    }
</style>
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
                                        <?php
                                        $userInfoNew = get_user_datescolumns($user_info['sid']);
                                        ?>
                                        <h2><?php echo $user_info["first_name"]; ?> <?= $user_info["last_name"] ?></h2>
                                        <?php if ($user_type === 'employee') { ?>
                                            <span>
                                                <?= remakeEmployeeName(getUserColumnByWhere(['sid' => $user_info['sid']]), false); ?>
                                            </span>
                                        <?php } ?>
                                        <h3 style="margin-top: -10px;margin-bottom: 5px">
                                            <span>
                                                <?= get_user_anniversary_date(
                                                    $userInfoNew[0]['joined_at'],
                                                    $userInfoNew[0]['registration_date'],
                                                    $userInfoNew[0]['rehire_date']
                                                );
                                                ?>
                                            </span>
                                        </h3>
                                        <div class="start-rating">
                                            <?php if ($user_type == 'applicant') { ?>
                                                <input readonly="readonly" id="input-21b" value="<?php echo isset($user_average_rating) ? $user_average_rating : 0; ?>" type="number" name="rating" class="rating" min=0 max=5 step=0.2 data-size="xs" />
                                            <?php } else if ($user_type == 'employee') { ?>
                                                <?php if ($this->session->userdata('logged_in')['employer_detail']['access_level_plus']) { ?>
                                                    <a class="btn-employee-status btn-warning" href="<?php echo base_url('employee_status/' . $employer['sid']); ?>">Employee Status</a>
                                                <?php } ?>
                                            <?php } ?>
                                        </div>
                                        <?php if (isset($employee_terminate_status) && !empty($employee_terminate_status)) {
                                            echo '<h4>' . $employee_terminate_status . '</h4>';
                                        } else if (isset($employer['active'])) { ?>
                                            <h4>
                                                <?php if ($employer['active']) { ?>
                                                    Active Employee
                                                <?php } else { ?>
                                                    <?php if ($employer['archived'] != '1') { ?>
                                                        Onboarding or Deactivated Employee
                                                    <?php } else { ?>
                                                        Archived Employee
                                                    <?php } ?>
                                                <?php } ?>
                                            </h4>
                                        <?php } else { ?>
                                            <span> <?php echo 'Applicant'; ?></span>
                                        <?php } ?>
                                    </div>
                                </article>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area margin-top">
                                <?php if ($user_type == 'applicant') { ?>
                                    <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                        <a class="dashboard-link-btn" href="<?php echo base_url('applicant_profile/' . $user_info['sid']); ?>">
                                            <i class="fa fa-chevron-left"></i>
                                            Applicant Profile
                                        </a>
                                        Setup On-boarding
                                        <!-- <?php //if($this->session->userdata('logged_in')['company_detail']['ems_status'] && ($session['company_detail']['has_applicant_approval_rights'] == 0 || $session['employer_detail']['has_applicant_approval_rights'] == 1)){
                                                ?>
                                            <a class="dashboard-link-btn-right" href="javascript:;" onclick="fun_hire_applicant();">Direct Hire Candidate</a>
                                        <?php //} 
                                        ?>   -->
                                    </span>
                                <?php } else if ($user_type == 'employee') { ?>
                                    <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                        <a class="dashboard-link-btn" href="<?php echo base_url('employee_profile/' . $user_info['sid']); ?>">
                                            <i class="fa fa-chevron-left"></i>Employee Profile</a>
                                        Setup Employee Panel
                                    </span>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <?php if ($sendNotification == "no") { ?>
                        <div class="row" id="jsNotificationEmailError">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="notice_div margin-top">
                                    <span class="notice_div_area">
                                        Notice: The Onboarding email notification for this candidate is still pending.
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div id="step_onboarding">
                                <ul>
                                    <li><a href="#getting_started">Getting Started</a></li>
                                    <li><a href="#office_locations">Office Locations</a></li>
                                    <li><a href="#office_hours">Office Hours</a></li>
                                    <li><a href="#people_to_meet">People To Meet</a></li>
                                    <li><a href="#what_to_bring">What To Bring</a></li>
                                    <li><a href="#useful_links">Useful Links</a></li>
                                    <li><a href="#offer_letter">Offer Letter / Pay Plan</a></li>
                                    <li><a href="#documents">Documents</a></li>
                                    <li><a href="#learning">Learning Center</a></li>
                                    <?php if ($user_type == 'applicant') { ?>
                                        <li><a href="#department_teams">Department/Team</a></li>
                                    <?php } ?>
                                    <?php if ($user_type == 'applicant') { ?>
                                        <li><a href="#credentials_configuration">Credentials Configuration</a></li>
                                    <?php } ?>
                                    <!--<li><a href="#summary">Summary</a></li>-->
                                    <?php if ($user_type == 'applicant') { ?>
                                        <li><a href="#send_email_to_applicant">Send On-Boarding E-Mail</a></li>
                                    <?php } ?>



                                </ul>
                                <div>

                                    <div id="getting_started" class="getting-started">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="well well-sm">
                                                    <strong>Welcome Video:</strong>
                                                    <p>You can set any special or general welcome video for <b><?php echo $user_info['first_name']; ?> <?= $user_info['last_name'] ?></b>. These welcome video can be unique for every applicant. Please modify the welcome video as per your requirements and press Next to save it.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <hr />
                                        <?php if (isset($welcome_videos_collection) && !empty($welcome_videos_collection)) { ?>
                                            <div class="hr-box">
                                                <div class="hr-box-header">
                                                    <strong>Welcome Video Library:</strong>
                                                </div>
                                                <div class="hr-innerpadding">
                                                    <div class="row">
                                                        <?php $compare_vid = isset($welcome_video) && !empty($welcome_video) ? $welcome_video['video_url'] : ''; ?>
                                                        <?php foreach ($welcome_videos_collection as $key => $collection) { ?>
                                                            <?php if ($compare_vid != $collection['video_url']) { ?>
                                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                    <article class="listing-article interview-video-listing">
                                                                        <input type="hidden" id="welcome_video_old_url" name="welcome_video_old_url" value="<?php echo isset($collection) ? $collection['video_url'] : ''; ?>" />
                                                                        <div class="col-lg-10 col-md-10 col-xs-12 col-sm-10">
                                                                            <figure class="assign-video-player">
                                                                                <?php $collection_source = $collection['video_source']; ?>
                                                                                <?php if ($collection_source == 'youtube') { ?>
                                                                                    <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo $collection['video_url']; ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                                                                <?php } elseif ($collection_source == 'vimeo') { ?>
                                                                                    <iframe class="embed-responsive-item" src="https://player.vimeo.com/video/<?php echo $collection['video_url']; ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                                                                <?php } else { ?>
                                                                                    <video controls width="316px" height="auto">
                                                                                        <source src="<?php echo base_url() . 'assets/uploaded_videos/' . $collection['video_url']; ?>" type='video/mp4'>
                                                                                    </video>
                                                                                <?php } ?>
                                                                            </figure>
                                                                        </div>
                                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 assign-status">
                                                                            <label class="control control--radio">
                                                                                <strong>Assign Status</strong>
                                                                                <input onclick="fun_assign_welcome_video(<?php echo $collection['sid']; ?>)" type="radio" name="welcome_video_collection" <?php echo $compare_vid == $collection['video_url'] ? 'checked="checked"' : ''; ?>>
                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                        </div>
                                                                    </article>
                                                                </div>
                                                            <?php } ?>
                                                        <?php } ?>
                                                        <?php if (isset($welcome_video) && !empty($welcome_video)) { ?>
                                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                <article class="listing-article interview-video-listing">
                                                                    <input type="hidden" id="welcome_video_old_url" name="welcome_video_old_url" value="<?php echo isset($collection) ? $collection['video_url'] : ''; ?>" />
                                                                    <div class="col-lg-10 col-md-10 col-xs-12 col-sm-10">
                                                                        <figure class="assign-video-player">
                                                                            <?php $video_source = $welcome_video['video_source']; ?>
                                                                            <?php if ($video_source == 'youtube') { ?>
                                                                                <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo $welcome_video['video_url']; ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                                                            <?php } elseif ($video_source == 'vimeo') { ?>
                                                                                <iframe class="embed-responsive-item" src="https://player.vimeo.com/video/<?php echo $welcome_video['video_url']; ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                                                            <?php } else { ?>
                                                                                <video controls width="316px" height="auto">
                                                                                    <source src="<?php echo base_url() . 'assets/uploaded_videos/' . $welcome_video['video_url']; ?>" type='video/mp4'>
                                                                                </video>
                                                                            <?php } ?>
                                                                        </figure>
                                                                    </div>
                                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 assign-status">
                                                                        <label class="control control--radio">
                                                                            <strong>Assign Status</strong>
                                                                            <input type="radio" name="welcome_video_collection" checked="checked">
                                                                            <div class="control__indicator"></div>
                                                                        </label>
                                                                    </div>
                                                                </article>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } else { ?>
                                            <?php if (isset($welcome_video) && !empty($welcome_video)) { ?>
                                                <div class="hr-box">
                                                    <div class="hr-box-header">
                                                        <strong>Welcome Video Library:</strong>
                                                    </div>
                                                    <div class="hr-innerpadding">
                                                        <div class="row">
                                                            <?php $compare_vid = isset($welcome_video) && !empty($welcome_video) ? $welcome_video['video_url'] : ''; ?>
                                                            <?php foreach ($welcome_videos_collection as $key => $collection) { ?>
                                                                <?php if ($compare_vid != $collection['video_url']) { ?>
                                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                        <article class="listing-article interview-video-listing">
                                                                            <input type="hidden" id="welcome_video_old_url" name="welcome_video_old_url" value="<?php echo isset($collection) ? $collection['video_url'] : ''; ?>" />
                                                                            <div class="col-lg-10 col-md-10 col-xs-12 col-sm-10">
                                                                                <figure class="assign-video-player">
                                                                                    <?php $collection_source = $collection['video_source']; ?>
                                                                                    <?php if ($collection_source == 'youtube') { ?>
                                                                                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo $collection['video_url']; ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                                                                    <?php } elseif ($collection_source == 'vimeo') { ?>
                                                                                        <iframe class="embed-responsive-item" src="https://player.vimeo.com/video/<?php echo $collection['video_url']; ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                                                                    <?php } else { ?>
                                                                                        <video controls width="316px" height="auto">
                                                                                            <source src="<?php echo base_url() . 'assets/uploaded_videos/' . $collection['video_url']; ?>" type='video/mp4'>
                                                                                        </video>
                                                                                    <?php } ?>
                                                                                </figure>
                                                                            </div>
                                                                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 assign-status">
                                                                                <label class="control control--radio">
                                                                                    <strong>Assign Status</strong>
                                                                                    <input onclick="fun_assign_welcome_video(<?php echo $collection['sid']; ?>)" type="radio" name="welcome_video_collection" <?php echo $compare_vid == $collection['video_url'] ? 'checked="checked"' : ''; ?>>
                                                                                    <div class="control__indicator"></div>
                                                                                </label>
                                                                            </div>
                                                                        </article>
                                                                    </div>
                                                                <?php } ?>
                                                            <?php } ?>
                                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                <article class="listing-article interview-video-listing">
                                                                    <input type="hidden" id="welcome_video_old_url" name="welcome_video_old_url" value="<?php echo isset($collection) ? $collection['video_url'] : ''; ?>" />
                                                                    <div class="col-lg-10 col-md-10 col-xs-12 col-sm-10">
                                                                        <figure class="assign-video-player">
                                                                            <?php $video_source = $welcome_video['video_source']; ?>
                                                                            <?php if ($video_source == 'youtube') { ?>
                                                                                <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo $welcome_video['video_url']; ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                                                            <?php } elseif ($video_source == 'vimeo') { ?>
                                                                                <iframe class="embed-responsive-item" src="https://player.vimeo.com/video/<?php echo $welcome_video['video_url']; ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                                                            <?php } else { ?>
                                                                                <video controls width="316px" height="auto">
                                                                                    <source src="<?php echo base_url() . 'assets/uploaded_videos/' . $welcome_video['video_url']; ?>" type='video/mp4'>
                                                                                </video>
                                                                            <?php } ?>
                                                                        </figure>
                                                                    </div>
                                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 assign-status">
                                                                        <label class="control control--radio">
                                                                            <strong>Assign Status</strong>
                                                                            <input type="radio" name="welcome_video_collection" checked="checked">
                                                                            <div class="control__indicator"></div>
                                                                        </label>
                                                                    </div>
                                                                </article>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        <?php } ?>

                                        <div class="hr-box">
                                            <div class="hr-box-header">
                                                <strong>Assign Custom Welcome Video:</strong>
                                            </div>
                                            <div class="hr-innerpadding">
                                                <form id="func_insert_welcome_video" enctype="multipart/form-data" method="post" action="<?php echo base_url('onboarding/add_custom_welcome_video'); ?>">
                                                    <input type="hidden" name="perform_action" value="perform_action" />
                                                    <input type="hidden" name="user_sid" value="<?php echo $user_sid; ?>" />
                                                    <input type="hidden" name="user_type" value="<?php echo $user_type; ?>" />
                                                    <?php if ($user_type == 'applicant') { ?>
                                                        <input type="hidden" name="job_list_sid" value="<?php echo $job_list_sid; ?>" />
                                                    <?php } ?>
                                                    <div class="universal-form-style-v2">
                                                        <ul>
                                                            <?php if (isset($welcome_video) && !empty($welcome_video) && (isset($welcome_video['is_custom']) && $welcome_video['is_custom'] == 1)) { ?>
                                                                <li class="form-col-100 autoheight" style="width:100%; height:500px!important;">
                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                        <figure class="">
                                                                            <?php $custom_source = $welcome_video['video_source']; ?>
                                                                            <?php if ($custom_source == 'youtube') { ?>
                                                                                <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo $welcome_video['video_url']; ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen style="width:100%; height:500px !important;"></iframe>
                                                                            <?php } elseif ($custom_source == 'vimeo') { ?>
                                                                                <iframe class="embed-responsive-item" src="https://player.vimeo.com/video/<?php echo $welcome_video['video_url']; ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen style="width:100%; height:500px !important;"></iframe>
                                                                            <?php } else { ?>
                                                                                <video controls width="100%" height="500px">
                                                                                    <source src="<?php echo base_url() . 'assets/uploaded_videos/' . $welcome_video['video_url']; ?>" type='video/mp4'>
                                                                                </video>
                                                                            <?php } ?>
                                                                        </figure>
                                                                    </div>
                                                                </li>
                                                            <?php } ?>
                                                            <li class="form-col-50-left autoheight edit_filter">
                                                                <label for="video_source">Video Source <span class="hr-required">*</span></label>
                                                                <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                                    <?php echo YOUTUBE_VIDEO; ?>
                                                                    <input class="welcome_video_source" type="radio" id="welcome_video_source_youtube" name="welcome_video_source" value="youtube">
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                                <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                                    <?php echo VIMEO_VIDEO; ?>
                                                                    <input class="welcome_video_source" type="radio" id="welcome_video_source_vimeo" name="welcome_video_source" value="vimeo">
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                                <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                                    <?php echo UPLOAD_VIDEO; ?>
                                                                    <input class="welcome_video_source" type="radio" id="welcome_video_source_upload" name="welcome_video_source" value="upload">
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </li>
                                                            <li class="form-col-100" id="welcome_yt_vm_video_container">
                                                                <input type="text" name="yt_vm_video_url" value="" class="invoice-fields" id="yt_vm_video_url">
                                                                <?php echo form_error('yt_vm_video_url'); ?>
                                                            </li>
                                                            <li class="form-col-100 autoheight edit_filter" id="welcome_up_video_container" style="display: none">
                                                                <label>Upload Video <span class="hr-required">*</span></label>
                                                                <div class="upload-file invoice-fields">
                                                                    <span class="selected-file" id="name_welcome_video_upload"></span>
                                                                    <input type="file" name="welcome_video_upload" id="welcome_video_upload" onchange="welcome_video_check('welcome_video_upload')">
                                                                    <a href="javascript:;">Choose Video</a>
                                                                </div>
                                                            </li>
                                                            <li class="form-col-100">
                                                                <button type="button" class="btn btn-success pull-right" id="add_custom_welcome_video_submit">Assign Video</button>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <form id="form_onboarding_setup" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                            <input type="hidden" id="perform_action" name="perform_action" value="save_and_move_to_onboarding" />
                                            <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                            <input type="hidden" id="employer_sid" name="employer_sid" value="<?php echo $employer_sid; ?>" />
                                            <input type="hidden" id="user_type" name="user_type" value="<?php echo $user_type; ?>" />
                                            <input type="hidden" id="user_sid" name="user_sid" value="<?php echo $user_sid; ?>" />
                                            <input type="hidden" id="company_name" name="company_name" value="<?php echo $company_name; ?>" />
                                            <input type="hidden" id="user_email" name="user_email" value="<?php echo $user_info['email']; ?>" />
                                            <input type="hidden" id="unique_sid" name="unique_sid" value="<?php echo $unique_sid; ?>" />

                                            <?php if ($user_type == 'applicant') { ?>
                                                <input type="hidden" id="job_list_sid" name="job_list_sid" value="<?php echo $job_list_sid; ?>" />
                                            <?php } ?>

                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="well well-sm">
                                                        <strong>Instructions:</strong>
                                                        <?php if ($user_type == 'applicant') { ?>
                                                            <p>You can set any special instructions for <b><?php echo $user_info['first_name']; ?> <?= $user_info['last_name'] ?></b>. These instructions can be unique for every applicant. Please modify the instructions as per your requirements and press Next to save it.</p>
                                                        <?php       } else { ?>
                                                            <p>You can set any special instructions for <b><?php echo $user_info['first_name']; ?> <?= $user_info['last_name'] ?></b>. These instructions can be unique for every Employee. Please modify the instructions as per your requirements and press Next to save it.</p>
                                                        <?php       } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label>On-boarding Instructions</label>
                                                        <textarea id="onboarding_instructions" name="onboarding_instructions" class="ckeditor"><?php echo html_entity_decode($onboarding_instructions); ?></textarea>

                                                    </div>
                                                </div>
                                            </div>

                                            <hr />
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label>On-boarding disclosure</label>
                                                        <textarea id="onboarding_disclosure" name="onboarding_disclosure" class="ckeditor"><?php echo html_entity_decode($onboarding_disclosure); ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>


                                    <div id="department_teams" style="display: none;">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                                <div class="universal-form-style-v2" style=" margin-left: 30px; margin-bottom: 20px;">
                                                    <label>Select Team</label>
                                                    <?= get_company_departments_teams($company_sid, 'teamId', $teamSid ?? 0); ?>
                                                    <script>
                                                        $('.jsSelect2').select2();
                                                    </script>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div id="office_locations" class="office-locations">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="well well-sm">
                                                    <strong>Instructions:</strong>
                                                    <?php if ($user_type == 'applicant') { ?>
                                                        <p>Please select the office location for <b><?php echo $user_info["first_name"]; ?> <?= $user_info["last_name"] ?></b>. You can also select multiple office location depending on applicant job role.</p>
                                                    <?php       } else { ?>
                                                        <p>Please select the office location for <b><?php echo $user_info["first_name"]; ?> <?= $user_info["last_name"] ?></b>. You can also select multiple office location depending on employee job role.</p>
                                                    <?php       } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-success" id="add_custom_location_dtn">Add Custom Location</button>
                                        <hr />
                                        <div class="row grid-columns" id="custom_office_location_section">
                                            <?php if (!empty($office_locations)) { ?>
                                                <?php foreach ($office_locations as $key => $location) {
                                                    //
                                                    $shouldBeChecked = false;
                                                    //
                                                    $shouldBeChecked = in_array($location['sid'], $locations) ?? $shouldBeChecked;
                                                    //
                                                    if (empty($locations)) {
                                                        $shouldBeChecked = $location['is_primary'] == 1 ? true : $shouldBeChecked;
                                                    }

                                                ?>
                                                    <div class="col-xs-12 col-md-4 col-sm-6 col-lg-3">
                                                        <label class="package_label" for="location_<?php echo $location['sid']; ?>">
                                                            <div class="img-thumbnail text-center package-info-box">
                                                                <figure>
                                                                    <i class="fa fa-map"></i>
                                                                </figure>
                                                                <div class="caption">
                                                                    <h3><strong><?php echo $location['location_title']; ?></strong></h3>
                                                                    <div class="btn-preview full-width">
                                                                        <button onclick="show_office_details('<?php echo $location['location_title']; ?>','<?php echo $location['location_address']; ?>','<?php echo $location['location_telephone']; ?>','<?php echo $location['location_fax']; ?>');" type="button" class="btn btn-default btn-sm btn-block">View Detail</button>
                                                                    </div>
                                                                </div>
                                                                <input <?php echo set_checkbox('location[]', $location['sid'], $shouldBeChecked); ?> class="select-package" data-type="location" id="location_<?php echo $location['sid']; ?>" name="locations[]" type="checkbox" value="<?php echo $location['sid']; ?>" />

                                                            </div>
                                                        </label>
                                                    </div>
                                                <?php } ?>
                                                <?php if (!empty($custom_office_locations)) { ?>
                                                    <?php foreach ($custom_office_locations as $key => $custom_location) { ?>
                                                        <div class="col-xs-12 col-md-4 col-sm-6 col-lg-3">
                                                            <label class="package_label" for="custom_location_<?php echo $custom_location['sid']; ?>">
                                                                <div class="img-thumbnail text-center package-info-box">
                                                                    <figure>
                                                                        <i class="fa fa-map"></i>
                                                                    </figure>
                                                                    <div class="caption">
                                                                        <h3><strong id="custom_location_title_<?php echo $custom_location['sid']; ?>"><?php echo $custom_location['location_title']; ?></strong></h3>
                                                                        <div class="btn-preview full-width">
                                                                            <button onclick="func_get_custom_location(<?php echo $custom_location['sid']; ?>);" type="button" class="btn btn-default btn-sm btn-block">Update Location</button>
                                                                        </div>
                                                                    </div>
                                                                    <input <?php echo $custom_location['status'] == 1 ? 'checked = "checked"' : '';  ?> class="select-package change_custom_record_status" data-type="location" id="custom_location_<?php echo $custom_location['sid']; ?>" name="" type="checkbox" value="<?php echo $custom_location['sid']; ?>">

                                                                </div>
                                                            </label>
                                                        </div>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="text-center">
                                                        <span class="no-data">No Locations Setup</span>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div id="office_hours" class="office-hours">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="well well-sm">
                                                    <strong>Instructions:</strong>
                                                    <?php if ($user_type == 'applicant') { ?>
                                                        <p>Please choose the hours for <b><?php echo $user_info["first_name"]; ?> <?= $user_info["last_name"] ?></b> first day schedule.</p>
                                                    <?php } else { ?>
                                                        <p>Please choose the office working hours for <b><?php echo $user_info["first_name"]; ?> <?= $user_info["last_name"] ?></b>.</p>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-success" id="add_custom_office_hour_dtn">Add Custom Office Hour</button>
                                        <hr />
                                        <div class="row grid-columns" id="custom_office_timing_section">
                                            <?php if (!empty($office_timings)) { ?>
                                                <?php foreach ($office_timings as $key => $timing) { ?>
                                                    <div class="col-xs-12 col-md-4 col-sm-6 col-lg-3">
                                                        <label class="package_label" for="timing_<?php echo $timing['sid']; ?>">
                                                            <div class="img-thumbnail text-center package-info-box">
                                                                <figure>
                                                                    <i class="fa fa-clock-o"></i>
                                                                </figure>
                                                                <div class="caption">
                                                                    <h3>
                                                                        <strong><?php echo $timing['title']; ?></strong>
                                                                        <br />
                                                                        <!-- <small><?php //echo date('h:i A', strtotime($timing['start_time'])) . ' - ' . date('h:i A', strtotime($timing['end_time'])); 
                                                                                    ?></small> -->
                                                                        <small><?= reset_datetime(array('datetime' => $timing['start_time'], '_this' => $this, 'format' => 'h:i A', 'new_zone' => 'PST')) . ' - ' . reset_datetime(array('datetime' => $timing['end_time'], '_this' => $this, 'format' => 'h:i A', 'new_zone' => 'PST')); ?></small>
                                                                    </h3>
                                                                    <hr />
                                                                </div>
                                                                <input <?php echo set_checkbox('timings[]', $timing['sid'], in_array($timing['sid'], $timings)); ?> class="select-package" data-type="time" id="timing_<?php echo $timing['sid']; ?>" name="timings[]" type="checkbox" value="<?php echo $timing['sid']; ?>" />
                                                            </div>
                                                        </label>
                                                    </div>
                                                <?php } ?>
                                                <?php if (!empty($custom_office_timings)) { ?>
                                                    <?php foreach ($custom_office_timings as $key => $custom_timing) { ?>
                                                        <div class="col-xs-12 col-md-4 col-sm-6 col-lg-3">
                                                            <label class="package_label" for="custom_timing_<?php echo $custom_timing['sid']; ?>">
                                                                <div class="img-thumbnail text-center package-info-box">
                                                                    <figure>
                                                                        <i class="fa fa-clock-o"></i>
                                                                    </figure>
                                                                    <div class="caption">
                                                                        <h3>
                                                                            <strong id="custom_hours_title_<?php echo $custom_timing['sid']; ?>"><?php echo $custom_timing['hour_title']; ?></strong>
                                                                            <br />
                                                                            <small id="custom_hours_time_<?php echo $custom_timing['sid']; ?>"><?php echo date('h:i A', strtotime($custom_timing['hour_start_time'])) . ' - ' . date('h:i A', strtotime($custom_timing['hour_end_time'])); ?></small>
                                                                        </h3>
                                                                        <div class="btn-preview full-width">
                                                                            <button onclick="func_get_custom_timimg(<?php echo $custom_timing['sid']; ?>);" type="button" class="btn btn-default btn-sm btn-block">Update Hours</button>
                                                                        </div>
                                                                        <hr />
                                                                    </div>
                                                                    <input <?php echo $custom_timing['status'] == 1 ? 'checked = "checked"' : '';  ?> class="select-package change_custom_record_status" data-type="time" id="custom_timing_<?php echo $custom_timing['sid']; ?>" name="" type="checkbox" value="<?php echo $custom_timing['sid']; ?>">
                                                                </div>
                                                            </label>
                                                        </div>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="text-center">
                                                        <span class="no-data">Office timing not configured</span>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div id="people_to_meet" class="people-to-meet">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="well well-sm">
                                                    <strong>Instructions:</strong>
                                                    <?php if ($user_type == 'applicant') { ?>
                                                        <p>In your first week you'll have onboarding meetings with team leads and meet with finance team to talk about payroll and benefits. And, of course, you'll spend quite a bit of time with following team members.</p>
                                                    <?php       } else { ?>
                                                        <p>Please select the team lead or team members for <b><?php echo $user_info["first_name"]; ?> <?= $user_info["last_name"] ?></b>.</p>
                                                    <?php       } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row grid-columns">
                                            <?php if (!empty($people_to_meet)) { ?>
                                                <?php foreach ($people_to_meet as $key => $person) { ?>
                                                    <div class="col-xs-12 col-md-4 col-sm-6 col-lg-3">
                                                        <label class="package_label" for="person_<?php echo $person['sid']; ?>">
                                                            <div class="img-thumbnail text-center package-info-box">
                                                                <figure>
                                                                    <?php if (!empty($person['profile_picture'])) { ?>
                                                                        <!--<div class="" style="width: 100%; height: 250px; background-repeat: no-repeat; background-size: 100%; background-image: url('<?php //echo AWS_S3_BUCKET_URL . $person['profile_picture']; 
                                                                                                                                                                                                            ?>'); background-position: center center;"></div>-->
                                                                        <img class="img-responsive img-thumbnail" src="<?php echo AWS_S3_BUCKET_URL . $person['profile_picture']; ?>" alt="Profile Picture" />
                                                                    <?php } else { ?>
                                                                        <!--<div class="" style="width: 100%; height: 250px; background-repeat: no-repeat; background-size: 100%; background-image: url('<?php //echo base_url('assets/images/default_pic.jpg'); 
                                                                                                                                                                                                            ?>'); background-position: center center;"></div>-->
                                                                        <img class="img-responsive img-thumbnail" src="<?php echo base_url('assets/images/default_pic.jpg'); ?>" alt="Profile Picture" />
                                                                    <?php } ?>
                                                                </figure>
                                                                <div class="caption">
                                                                    <h4>
                                                                        <strong>
                                                                            <?php //echo $person['first_name'] . ' ' . $person['last_name']; 
                                                                            ?>
                                                                            <?php echo getUserNameBySID($person['employer_sid']); ?>
                                                                        </strong>
                                                                        <br />
                                                                        <small><?php echo $person['notes']; ?></small>
                                                                    </h4>
                                                                    <div class="btn-preview full-width">
                                                                        <a target="_blank" href="<?php echo base_url('dashboard/colleague_profile') . '/' . $person['employer_sid']; ?>" type="button" class="btn btn-default btn-sm btn-block">Profile</a>
                                                                    </div>
                                                                </div>
                                                                <input <?php echo set_checkbox('person[]', $person['sid'], in_array($person['sid'], $people)); ?> class="select-package" data-type="person" id="person_<?php echo $person['sid']; ?>" name="people[]" type="checkbox" value="<?php echo $person['sid']; ?>" />
                                                            </div>
                                                        </label>
                                                    </div>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="text-center">
                                                        <span class="no-data">No Team Member configured</span>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div id="what_to_bring" class="what-to-bring">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="well well-sm">
                                                    <strong>Instructions:</strong>
                                                    <?php if ($user_type == 'applicant') { ?>
                                                        <p>You can explain office environment to <b><?php echo $user_info["first_name"]; ?> <?= $user_info["last_name"] ?></b>. You can suggest any item to bring to work </p>
                                                    <?php       } else { ?>
                                                        <p>Employee can be informed about any important stuff it should bring to office.</p>
                                                    <?php       } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-success" id="add_custom_item_dtn">Add Custom Item To Bring</button>
                                        <hr />
                                        <div class="row grid-columns" id="custom_office_item_section">
                                            <?php if (!empty($what_to_bring) || !empty($custom_office_items)) { ?>
                                                <?php if ($what_to_bring) { ?>
                                                    <?php foreach ($what_to_bring as $key => $item) { ?>
                                                        <div class="col-xs-12 col-md-4 col-sm-6 col-lg-3">
                                                            <label class="package_label" for="item_<?php echo $item['sid']; ?>">
                                                                <div class="img-thumbnail text-center package-info-box">
                                                                    <figure>
                                                                        <i class="fa fa-star"></i>
                                                                    </figure>
                                                                    <div class="caption">
                                                                        <h3>
                                                                            <strong><?php echo $item['item_title']; ?></strong>
                                                                            <br />
                                                                            <small>
                                                                                <?php echo $item['item_description']; ?>
                                                                            </small>
                                                                        </h3>
                                                                        <hr />
                                                                    </div>
                                                                    <input <?php echo set_checkbox('items[]', $item['sid'], in_array($item['sid'], $items)); ?> class="select-package" data-type="item" id="item_<?php echo $item['sid']; ?>" name="items[]" type="checkbox" value="<?php echo $item['sid']; ?>" />
                                                                </div>
                                                            </label>
                                                        </div>
                                                    <?php } ?>
                                                <?php } ?>
                                                <?php if ($custom_office_items) { ?>
                                                    <?php foreach ($custom_office_items as $key => $custom_item) { ?>
                                                        <div class="col-xs-12 col-md-4 col-sm-6 col-lg-3">
                                                            <label class="package_label" for="custom_item_<?php echo $custom_item['sid']; ?>">
                                                                <div class="img-thumbnail text-center package-info-box">
                                                                    <figure>
                                                                        <i class="fa fa-star"></i>
                                                                    </figure>
                                                                    <div class="caption">
                                                                        <h3><strong id="custom_item_title_<?php echo $custom_item['sid']; ?>"><?php echo $custom_item['item_title']; ?></strong>
                                                                            <br />
                                                                            <small id="custom_item_description_<?php echo $custom_item['sid']; ?>">
                                                                                <?php echo $custom_item['item_description']; ?>
                                                                            </small>
                                                                        </h3>
                                                                        <div class="btn-preview full-width">
                                                                            <button onclick="func_get_custom_item(<?php echo $custom_item['sid']; ?>);" type="button" class="btn btn-default btn-sm btn-block">Update Item</button>
                                                                        </div>
                                                                    </div>
                                                                    <input <?php echo $custom_item['status'] == 1 ? 'checked = "checked"' : '';  ?> class="select-package change_custom_record_status" data-type="location" id="custom_item_<?php echo $custom_item['sid']; ?>" name="" type="checkbox" value="<?php echo $custom_item['sid']; ?>">

                                                                </div>
                                                            </label>
                                                        </div>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="text-center">
                                                        <span class="no-data">No Items Selected</span>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div id="useful_links" class="useful-links">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="well well-sm">
                                                    <strong>Instructions:</strong>
                                                    <?php if ($user_type == 'applicant') { ?>
                                                        <p>You can list down all necessary / important hyperlinks.</p>
                                                    <?php       } else { ?>
                                                        <p>You can list down all necessary / important hyperlinks.</p>
                                                    <?php       } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <button type="button" id="add_custom_link" class="btn btn-success pull-right">Add a useful link</button>
                                            </div>
                                            <hr />
                                        </div>
                                        <!-- Custom useful link start -->
                                        <div class="grid-columns">
                                            <div class="panel panel-default ems-documents">
                                                <div class="panel-heading">
                                                    <strong>Custom Useful Links</strong>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-plane">
                                                            <thead>
                                                                <tr>
                                                                    <th class="col-lg-4">
                                                                        <strong>
                                                                            Link Name
                                                                        </strong>
                                                                        </br>
                                                                        <small>
                                                                            (URL)
                                                                        </small>
                                                                    </th>
                                                                    <th class="col-lg-6 text-center">Description/Instruction</th>
                                                                    <th class="col-lg-1 text-center">Assign</th>
                                                                    <th class="col-lg-1 text-center">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="custom_useful_link_section">
                                                                <?php if (!empty($custom_useful_links)) { ?>
                                                                    <?php foreach ($custom_useful_links as $key => $link) { ?>
                                                                        <tr class="package_label" id="row_<?= $link['sid'] ?>">
                                                                            <td>
                                                                                <strong>
                                                                                    <?php echo $link['link_title']; ?>
                                                                                </strong>
                                                                                <?php if (!empty($link['link_url'])) { ?>
                                                                                    </br>
                                                                                    <small>
                                                                                        (<?php echo $link['link_url']; ?>)
                                                                                    </small>
                                                                                <?php } ?>
                                                                            </td>
                                                                            <td>
                                                                                <textarea class="invoice-fields autoheight" cols="40" rows="2" id="link_description_<?= $link['sid'] ?>" readonly><?php echo $link['link_description']; ?></textarea>
                                                                            </td>
                                                                            <td>
                                                                                <label class="control control--checkbox">
                                                                                    <input type="checkbox" <?php echo $link['status'] == 1 ? 'checked="checked"' : ''; ?> />
                                                                                    <div class="control__indicator"></div>
                                                                                </label>
                                                                            </td>
                                                                            <td>
                                                                                <a href="javascript:;" data-id="<?php echo $link['sid']; ?>" data-title="<?php echo $link['link_title']; ?>" data-url="<?php echo $link['link_url']; ?>" data-des_id="link_description_<?= $link['sid'] ?>" class="btn btn-success edit_useful_link">Edit</a>
                                                                            </td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Custom useful link end -->
                                        <!-- Company useful link start -->
                                        <div class="grid-columns">
                                            <div class="panel panel-default ems-documents">
                                                <div class="panel-heading">
                                                    <strong>Useful Links</strong>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-plane">
                                                            <thead>
                                                                <tr>
                                                                    <th class="col-lg-4">
                                                                        <strong>
                                                                            Link Name
                                                                        </strong>
                                                                        </br>
                                                                        <small>
                                                                            (URL)
                                                                        </small>
                                                                    </th>
                                                                    <th class="col-lg-7 text-center">Description/Instruction</th>
                                                                    <th class="col-lg-1 text-center">Assign</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php if (!empty($useful_links)) { ?>
                                                                    <?php foreach ($useful_links as $key => $link) { ?>
                                                                        <tr class="package_label">
                                                                            <td>
                                                                                <strong>
                                                                                    <?php echo $link['link_title']; ?>
                                                                                </strong>
                                                                                </br>
                                                                                <small>
                                                                                    (<?php echo $link['link_url']; ?>)
                                                                                </small>
                                                                            </td>
                                                                            <td>
                                                                                <input type="hidden" name="<?php echo 'link-sid-' . $link['sid']; ?>" value="<?php echo $link['sid']; ?>">
                                                                                <input type="hidden" name="<?php echo 'sid-' . $link['sid'] . '-title'; ?>" value="<?php echo $link['link_title']; ?>">
                                                                                <input type="hidden" name="<?php echo 'sid-' . $link['sid'] . '-url'; ?>" value="<?php echo $link['link_url']; ?>">
                                                                                <input type="hidden" name="<?php echo 'sid-' . $link['sid'] . '-status'; ?>" value="<?php echo $link['status']; ?>">

                                                                                <?php $description = $link['link_description'];
                                                                                if (!empty($links)) {
                                                                                    foreach ($links as $key => $value) {
                                                                                        if ($value['link_sid'] == $link['sid']) {
                                                                                            $description = $value['link_description'];
                                                                                        }
                                                                                    }
                                                                                } ?>
                                                                                <textarea name="<?php echo 'sid-' . $link['sid'] . '-description'; ?>" class="invoice-fields autoheight" cols="40" rows="2" id="edit_link_description"><?php echo $description; ?></textarea>
                                                                            </td>
                                                                            <td class="">
                                                                                <label class="control control--checkbox">
                                                                                    <!-- <input <?php //echo set_checkbox('links[]', $link['sid'], in_array($link['sid'], $links)); 
                                                                                                ?> data-type="link" id="link_<?php //echo $link['sid']; 
                                                                                                                                ?>" name="links[]" type="checkbox" value="<?php //echo $link['sid']; 
                                                                                                                                                                            ?>" /> -->
                                                                                    <input data-type="link" id="link_<?php echo $link['sid']; ?>" name="links[]" type="checkbox" value="<?php echo $link['sid']; ?>" <?php if (!empty($links)) {
                                                                                                                                                                                                                            foreach ($links as $key => $value) {
                                                                                                                                                                                                                                if ($value['link_sid'] == $link['sid']) { ?>checked="checked" <?php }
                                                                                                                                                                                                                                                                                        }
                                                                                                                                                                                                                                                                                    } ?> />

                                                                                    <div class="control__indicator"></div>
                                                                                </label>
                                                                            </td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                <?php } else { ?>
                                                                    <tr>
                                                                        <div class="text-center">
                                                                            <span class="no-data">No Hyperlinks configured</span>
                                                                        </div>
                                                                    </tr>
                                                                <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--  <div class="col-xs-12 col-md-4 col-sm-6 col-lg-3">
                                                            <label class="package_label" for="link_<?php echo $link['sid']; ?>">
                                                                <div class="img-thumbnail text-center package-info-box">
                                                                    <figure>
                                                                        <i class="fa fa-link"></i>
                                                                    </figure>
                                                                    <div class="caption">
                                                                        <h4>
                                                                            <strong><?php echo $link['link_title']; ?></strong>
                                                                            <br />
                                                                            <small><?php echo $link['link_description']; ?></small>
                                                                        </h4>
                                                                        <div class="btn-preview full-width">
                                                                            <a target="_blank" href="<?php echo $link['link_url']; ?>" type="button" class="btn btn-default btn-sm btn-block">Visit</a>
                                                                        </div>
                                                                    </div>
                                                                    <input <?php echo set_checkbox('links[]', $link['sid'], in_array($link['sid'], $links)); ?> class="select-package" data-type="link" id="link_<?php echo $link['sid']; ?>" name="links[]" type="checkbox" value="<?php echo $link['sid']; ?>" />
                                                                </div>
                                                            </label>
                                                        </div> -->
                                            <!--  <?php //} 
                                                    ?>
                                                <?php //}// else { 
                                                ?>
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="text-center">
                                                            <span class="no-data">No Hyperlinks configured</span>
                                                        </div>
                                                    </div>
                                                <?php //} 
                                                ?> -->
                                        </div>
                                        <!-- Company useful link end -->
                                    </div>

                                    <div id="offer_letter" class="offer-letter">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="well well-sm">
                                                    <strong>Instructions:</strong>
                                                    <p>Please select the Offer Letter / Pay Plan for <b><?php echo $user_info["first_name"]; ?> <?= $user_info["last_name"] ?></b></p>
                                                </div>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <span class="pull-right">
                                                    <button type="button" class="btn btn-success  js-offer-letter-btn">Add Offer Letter / Pay Plan</button>
                                                </span>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <?php foreach ($offer_letters as $offer_letter) { ?>
                                                    <input type="hidden" id="letter_name_<?php echo $offer_letter['sid']; ?>" value="<?php echo $offer_letter['letter_name'] ?>" />
                                                    <input type="hidden" id="letter_body_<?php echo $offer_letter['sid']; ?>" value="<?php echo htmlentities(html_entity_decode($offer_letter['letter_body'])); ?>" />
                                                <?php } ?>
                                                <div class="form-group">
                                                    <label>Offer Letter / Pay Plan</label>
                                                    <select id="offer_letter_select" name="offer_letter_select">
                                                        <option value="">Please Select</option>
                                                        <?php foreach ($offer_letters as $offer_letter) { ?>
                                                            <?php
                                                            $offer_letter['letter_body'] = html_entity_decode($offer_letter['letter_body']);
                                                            $allOfferLetters[] = $offer_letter;
                                                            ?>
                                                            <option value="<?= $offer_letter['sid']; ?>"><?= $offer_letter['letter_name']; ?> (<?= $offer_letter['letter_type']; ?>)</option>
                                                        <?php } ?>
                                                    </select>
                                                    <input type="hidden" id="selected_letter_type">
                                                </div>
                                            </div>
                                        </div>

                                        <div id="uploaded_offer_letter" style="display: none">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="panel-body">
                                                        <div class="accordion-colored-header header-bg-gray">
                                                            <div class="panel-group" id="onboarding-configuration-accordions">
                                                                <div class="panel panel-default parent_div">
                                                                    <div class="panel-heading">
                                                                        <h4 class="panel-title">
                                                                            Uploaded Offer Letter / Pay Plan
                                                                        </h4>
                                                                    </div>
                                                                    <div class="panel-body">
                                                                        <div id="uploaded_offer_letter_iframe"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="generated_offer_letter" style="display: none">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label>Letter Body<span class="staric">*</span></label>
                                                        <textarea id="letter_body" name="letter_body" class="ckeditor"></textarea>
                                                    </div>
                                                    <span id="body_error" class="text-danger"></span>
                                                </div>
                                            </div>

                                            <br>
                                            <?php $this->load->view('hr_documents_management/partials/approvers_section'); ?>
                                            <br>


                                            <?php $this->load->view('hr_documents_management/partials/settings', [
                                                'is_confidential' =>  $document_info['is_confidential']
                                            ]); ?>

                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label>Authorized Management Signers </label>
                                                        <select name="js-signers[]" id="js-signers" multiple="">
                                                            <?php
                                                            if (sizeof($managers_list)) {
                                                                foreach ($managers_list as $key => $value) {
                                                                    echo '<option value="' . ($value['sid']) . '">' . (remakeEmployeeName($value)) . '</option>';
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="offer-letter-help-widget full-width form-group">
                                                        <div class="how-it-works-insturction">
                                                            <strong>How it Works:</strong>
                                                            <p class="how-works-attr">1. Generate a new Electronic Document</p>
                                                            <p class="how-works-attr">2. Enable a Document Acknowledgment</p>
                                                            <p class="how-works-attr">3. Enable an Electronic Signature</p>
                                                            <p class="how-works-attr">4. Insert multiple tags where applicable</p>
                                                            <p class="how-works-attr">5. Save the Document</p>
                                                        </div>

                                                        <div class="tags-arae">
                                                            <div class="col-md-12">
                                                                <h5><strong>Company Information Tags:</strong></h5>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group autoheight">
                                                                    <input type="text" class="form-control tag" readonly="" value="{{company_name}}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group autoheight">
                                                                    <input type="text" class="form-control tag" readonly="" value="{{company_address}}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group autoheight">
                                                                    <input type="text" class="form-control tag" readonly="" value="{{company_phone}}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group autoheight">
                                                                    <input type="text" class="form-control tag" readonly="" value="{{career_site_url}}">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="tags-arae">
                                                            <div class="col-md-12">
                                                                <h5><strong>Employee / Applicant Tags :</strong></h5>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group autoheight">
                                                                    <input type="text" class="form-control tag" readonly="" value="{{first_name}}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group autoheight">
                                                                    <input type="text" class="form-control tag" readonly="" value="{{last_name}}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group autoheight">
                                                                    <input type="text" class="form-control tag" readonly="" value="{{email}}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group autoheight">
                                                                    <input type="text" class="form-control tag" readonly="" value="{{job_title}}">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="tags-arae">
                                                            <div class="col-md-12">
                                                                <h5><strong>Signature tags:</strong></h5>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group autoheight">
                                                                    <input type="text" class="form-control tag" readonly="" value="{{authorized_signature}}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group autoheight">
                                                                    <input type="text" class="form-control tag" readonly="" value="{{authorized_signature_date}}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group autoheight">
                                                                    <input type="text" class="form-control tag" readonly="" value="{{signature}}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group autoheight">
                                                                    <input type="text" class="form-control tag" readonly="" value="{{signature_print_name}}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group autoheight">
                                                                    <input type="text" class="form-control tag" readonly="" value="{{inital}}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group autoheight">
                                                                    <input type="text" class="form-control tag" readonly="" value="{{sign_date}}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group autoheight">
                                                                    <input type="text" class="form-control tag" readonly="" value="{{text}}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group autoheight">
                                                                    <input type="text" class="form-control tag" readonly="" value="{{text_area}}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group autoheight">
                                                                    <input type="text" class="form-control tag" readonly="" value="{{checkbox}}">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="tags-arae">
                                                            <div class="col-md-12">
                                                                <h5><strong>Pay Plan / Offer Letter tags:</strong></h5>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group autoheight">
                                                                    <input type="text" class="form-control tag" readonly="" value="{{hourly_rate}}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group autoheight">
                                                                    <input type="text" class="form-control tag" readonly="" value="{{hourly_technician}}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group autoheight">
                                                                    <input type="text" class="form-control tag" readonly="" value="{{flat_rate_technician}}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group autoheight">
                                                                    <input type="text" class="form-control tag" readonly="" value="{{semi_monthly_salary}}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group autoheight">
                                                                    <input type="text" class="form-control tag" readonly="" value="{{semi_monthly_draw}}">
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-right" style="top: 0;">
                                                <div class="form-group">
                                                    <?php if ($assigned_offer_letter_sid == 0) { ?>
                                                        <a href="javascript:;" id="assign-offer-letter" class="btn btn-success">Modify and Assign</a>
                                                    <?php } elseif ($assigned_offer_letter_sid > 0 && $offer_letter_status) { ?>
                                                        <a href="javascript:;" id="revoke-offer-letter" class="btn btn-danger">Revoke</a>
                                                        <a style="display:none;" href="javascript:;" id="activate-offer-letter" class="btn btn-success">Activate</a>
                                                        <a href="javascript:;" id="reassign-offer-letter" class="btn btn-warning">Modify and Re-Assign</a>
                                                    <?php } elseif ($assigned_offer_letter_sid > 0 && !$offer_letter_status) { ?>
                                                        <a style="display:none;" href="javascript:;" id="revoke-offer-letter" class="btn btn-danger">Revoke</a>
                                                        <a href="javascript:;" id="activate-offer-letter" class="btn btn-success">Activate</a>
                                                        <a href="javascript:;" id="reassign-offer-letter" class="btn btn-warning">Modify and Re-Assign</a>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="documents" class="step-documents" style="display: none;">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="well well-sm">
                                                    <strong>Instructions:</strong>
                                                    <p>You can send all legal documents and company policy documents. Please review the documents list and select all the documents.</p>
                                                    <p>Please check all the documents you want to assign.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">

                                                <div class="panel panel-default ems-documents">
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <div class="hr-box">
                                                                <div class="hr-box-header">
                                                                    <strong>Employment Eligibility Verification Documents</strong>
                                                                </div>
                                                                <div class="hr-innerpadding">
                                                                    <div class="table-responsive">
                                                                        <table class="table table-bordered table-hover table-stripped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th class="col-lg-3">Document Name</th>
                                                                                    <th class="col-lg-3 text-center">Type</th>
                                                                                    <th class="col-lg-3 text-center">Assigned On</th>
                                                                                    <th class="col-lg-3 text-center">Actions</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td class="col-lg-3">
                                                                                        W4 Fillable <?php echo sizeof($w4_form_data) > 0 && !$w4_form_data['status'] ? '<b>(revoked)</b>' : ''; ?>
                                                                                    </td>
                                                                                    <td class="col-lg-1 text-center">
                                                                                        <i class="fa fa-2x fa-file-text"></i>
                                                                                    </td>
                                                                                    <td class="col-lg-2 text-center" id="w4-date">
                                                                                        <?php if (sizeof($w4_form_data) > 0 && $w4_form_data['status']) { ?>
                                                                                            <i class="fa fa-check fa-2x text-success"></i>
                                                                                            <div class="text-center">
                                                                                                <?= reset_datetime(array('datetime' => $w4_form_data['sent_date'], '_this' => $this)); ?>
                                                                                                <?php if ($w4_form_data['last_assign_by'] != '' && $w4_form_data['last_assign_by'] != '0') {
                                                                                                    echo "<br>Assigned By: " . getUserNameBySID($w4_form_data['last_assign_by']);
                                                                                                }
                                                                                                ?>
                                                                                            </div>
                                                                                        <?php } else { ?>
                                                                                            <i class="fa fa-times fa-2x text-danger"></i>
                                                                                        <?php } ?>
                                                                                    </td>
                                                                                    <td class="col-lg-1 text-center">
                                                                                        <?php if (sizeof($w4_form_data) > 0) {
                                                                                            if ($w4_form_data['status']) { ?>
                                                                                                <a href="javascript:;" id="w4" onclick="func_remove_w4();" class="btn btn-danger">Revoke</a>
                                                                                            <?php } else { ?>
                                                                                                <a href="javascript:;" id="w4" onclick="func_assign_w4();" class="btn btn-warning">Re-Assign</a>
                                                                                            <?php }
                                                                                            echo '<button class="btn btn-success jsManageW4" title="Manage W4">Manage W4</button>';
                                                                                        } else { ?>
                                                                                            <a href="javascript:;" id="w4" onclick="func_assign_w4();" class="btn btn-success">Assign</a>
                                                                                        <?php } ?>
                                                                                        <?php if (isset($w4_form_data['sid'])) { ?>
                                                                                            <!--  -->
                                                                                            <a href="javascript:;" onclick="show_document_track('w4', <?= $w4_form_data['sid']; ?>);" class="btn btn-success" title="View action trail for W4 form" placement="top">W4 Trail</a>
                                                                                            <!--  -->
                                                                                            <a href="javascript:;" onclick="VerificationDocumentHistory('w4', <?= $w4_form_data['sid']; ?>);" class="btn btn-success" title="View history for W4 form" placement="top">W4
                                                                                                History</a>
                                                                                            <!--  -->
                                                                                        <?php } ?>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="col-lg-3">
                                                                                        W9 Fillable <?php echo sizeof($w9_form_data) > 0 && !$w9_form_data['status'] ? '<b>(revoked)</b>' : ''; ?>
                                                                                    </td>
                                                                                    <td class="col-lg-1 text-center">
                                                                                        <i class="fa fa-2x fa-file-text"></i>
                                                                                    </td>
                                                                                    <td class="col-lg-2 text-center" id="w9-date">
                                                                                        <?php if (sizeof($w9_form_data) > 0 && $w9_form_data['status']) { ?>
                                                                                            <i class="fa fa-check fa-2x text-success"></i>
                                                                                            <div class="text-center">
                                                                                                <?= reset_datetime(array('datetime' => $w9_form_data['sent_date'], '_this' => $this)); ?>
                                                                                                <?php if ($w9_form_data['last_assign_by'] != '' && $w9_form_data['last_assign_by'] != '0') {
                                                                                                    echo "<br>Assigned By: " . getUserNameBySID($w9_form_data['last_assign_by']);
                                                                                                }
                                                                                                ?>
                                                                                            </div>
                                                                                        <?php } else { ?>
                                                                                            <i class="fa fa-times fa-2x text-danger"></i>
                                                                                        <?php } ?>
                                                                                    </td>
                                                                                    <td class="col-lg-1 text-center">
                                                                                        <?php if (sizeof($w9_form_data) > 0) {
                                                                                            if ($w9_form_data['status']) { ?>
                                                                                                <a href="javascript:;" id="w9" onclick="func_remove_w9();" class="btn btn-danger">Revoke</a>
                                                                                            <?php } else { ?>
                                                                                                <a href="javascript:;" id="w9" onclick="func_assign_w9();" class="btn btn-warning">Re-Assign</a>
                                                                                            <?php }
                                                                                            echo '<button class="btn btn-success jsManageW9" title="Manage W9">Manage W9</button>';
                                                                                        } else { ?>
                                                                                            <a href="javascript:;" id="w9" onclick="func_assign_w9();" class="btn btn-success">Assign</a>
                                                                                        <?php } ?>
                                                                                        <?php if (isset($w9_form_data['sid'])) { ?>
                                                                                            <!--  -->
                                                                                            <a href="javascript:;" onclick="show_document_track('w9', <?= $w9_form_data['sid']; ?>);" class="btn btn-success" title="View action trail for W9 form" placement="top">W9 Trail</a>
                                                                                            <!--  -->
                                                                                            <a href="javascript:;" onclick="VerificationDocumentHistory('w9', <?= $w9_form_data['sid']; ?>);" class="btn btn-success" title="View history for W9 form" placement="top">W9
                                                                                                History</a>
                                                                                            <!--  -->
                                                                                        <?php } ?>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="col-lg-3">
                                                                                        I9 Fillable <?php echo sizeof($i9_form_data) > 0 && !$i9_form_data['status'] ? '<b>(revoked)</b>' : ''; ?>
                                                                                    </td>
                                                                                    <td class="col-lg-1 text-center">
                                                                                        <i class="fa fa-2x fa-file-text"></i>
                                                                                    </td>
                                                                                    <td class="col-lg-2 text-center" id="i9-date">
                                                                                        <?php if (sizeof($i9_form_data) > 0 && $i9_form_data['status']) { ?>
                                                                                            <i class="fa fa-check fa-2x text-success"></i>
                                                                                            <div class="text-center">
                                                                                                <?= reset_datetime(array('datetime' => $i9_form_data['sent_date'], '_this' => $this)); ?>
                                                                                                <?php if ($i9_form_data['last_assign_by'] != '' && $i9_form_data['last_assign_by'] != '0') {
                                                                                                    echo "<br>Assigned By: " . getUserNameBySID($i9_form_data['last_assign_by']);
                                                                                                }
                                                                                                ?>

                                                                                            </div>
                                                                                        <?php } else { ?>
                                                                                            <i class="fa fa-times fa-2x text-danger"></i>
                                                                                        <?php } ?>
                                                                                    </td>
                                                                                    <td class="col-lg-1 text-center">
                                                                                        <?php if (sizeof($i9_form_data) > 0) {
                                                                                            if ($i9_form_data['status']) { ?>
                                                                                                <a href="javascript:;" id="i9" onclick="func_remove_i9();" class="btn btn-danger">Revoke</a>
                                                                                            <?php } else { ?>
                                                                                                <a href="javascript:;" id="i9" onclick="func_assign_i9();" class="btn btn-warning">Re-Assign</a>
                                                                                            <?php }
                                                                                            echo '<button class="btn btn-success jsManageI9" title="Manage I9">Manage I9</button>';
                                                                                        } else { ?>
                                                                                            <a href="javascript:;" id="i9" onclick="func_assign_i9();" class="btn btn-success">Assign</a>
                                                                                        <?php } ?>
                                                                                        <?php if (isset($i9_form_data['sid'])) { ?>
                                                                                            <!--  -->
                                                                                            <a href="javascript:;" onclick="show_document_track('i9', <?= $i9_form_data['sid']; ?>);" class="btn btn-success" title="View action trail for I9 form" placement="top">I9 Trail</a>
                                                                                            <!--  -->
                                                                                            <a href="javascript:;" onclick="VerificationDocumentHistory('i9', <?= $i9_form_data['sid']; ?>);" class="btn btn-success" title="View history for I9 form" placement="top">I9
                                                                                                History</a>
                                                                                            <!--  -->
                                                                                        <?php } ?>
                                                                                    </td>
                                                                                </tr>
                                                                                <?php if ($onboarding_eeo_form_status) { ?>
                                                                                    <tr>
                                                                                        <td class="col-lg-2">
                                                                                            EEOC FORM
                                                                                            <img class="img-responsive pull-left" style=" width: 22px; height: 22px; margin-right:5px;" alt="" title="Signed" data-toggle="tooltip" data-placement="top" src="<?php echo site_url('assets/manage_admin/images/' . (empty($eeo_form_info['status'] && $eeo_form_info['is_expired']) ? 'off' : 'on') . '.gif'); ?>">
                                                                                        </td>
                                                                                        <td class="col-lg-1 text-center">
                                                                                            <i aria-hidden="true" class="fa fa-2x fa-file-text"></i>
                                                                                        </td>
                                                                                        <td class="col-lg-2 text-center">
                                                                                            <?php if (empty($eeo_form_info)) { ?>
                                                                                                <i aria-hidden="true" class="fa fa-times fa-2x text-danger"></i>
                                                                                            <?php } else { ?>
                                                                                                <i aria-hidden="true" class="fa fa-check fa-2x text-success"></i>
                                                                                                <div class="text-center">
                                                                                                    <?php
                                                                                                    if (!empty($eeo_form_info['last_sent_at'])) {

                                                                                                        echo convertTimeZone(
                                                                                                            $eeo_form_info['last_sent_at'],
                                                                                                            DB_DATE_WITH_TIME,
                                                                                                            STORE_DEFAULT_TIMEZONE_ABBR,
                                                                                                            getLoggedInPersonTimeZone(),
                                                                                                            true,
                                                                                                            DATE_WITH_TIME
                                                                                                        );
                                                                                                    } else {
                                                                                                        echo "N/A";
                                                                                                    }
                                                                                                    ?>
                                                                                                    <?php if ($eeo_form_info['last_assigned_by'] != '' && $eeo_form_info['last_assigned_by'] != '0') {
                                                                                                        echo "<br>Assigned By: " . getUserNameBySID($eeo_form_info['last_assigned_by']);
                                                                                                    }
                                                                                                    ?>
                                                                                                </div>
                                                                                            <?php } ?>
                                                                                        </td>
                                                                                        <td class="col-lg-6 text-center">
                                                                                            <?php if (!empty($eeo_form_info)) { ?>
                                                                                                <?php if ($eeo_form_info['status']) { ?>
                                                                                                    <button onclick="func_remove_EEOC();" type="button" class="btn btn-danger">Revoke</button>
                                                                                                    <?php if ($eeo_form_info['is_expired'] != 1) { ?>
                                                                                                        <a class="btn btn-success jsResendEEOC" ref="javascript:void(0);" title="Send reminder email to <?= ucwords($user_info['first_name'] . ' ' . $user_info['last_name']); ?>" placement="top">
                                                                                                            <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                                                                                            Send Email Notification
                                                                                                        </a>
                                                                                                    <?php } ?>
                                                                                                <?php } else { ?>
                                                                                                    <button onclick="func_assign_EEOC();" type="button" class="btn btn-warning">Re-Assign</button>
                                                                                                <?php } ?>
                                                                                                <?php if (isset($eeo_form_info['sid'])) { ?>
                                                                                                    <!--  -->
                                                                                                    <button onclick="show_document_track('eeoc', <?= $eeo_form_info['sid']; ?>);" class="btn btn-success" title="View action trail for EEOC form" placement="top">EEOC Trail</button>
                                                                                                    <!--  -->
                                                                                                    <button onclick="VerificationDocumentHistory('eeoc', <?= $eeo_form_info['sid']; ?>);" class="btn btn-success" title="View history for EEOC form" placement="top">EEOC History</button>
                                                                                                <?php } ?>
                                                                                            <?php } else { ?>
                                                                                                <a class="btn btn-success jsResendEEOC" ref="javascript:void(0);" title="Assign EEOC form to <?= ucwords($user_info['first_name'] . ' ' . $user_info['last_name']); ?>" placement="top">Assign</a>
                                                                                            <?php } ?>

                                                                                            <?php if (!empty($eeo_form_info['last_completed_on'])) { ?>
                                                                                                <p>Last completed on <strong>
                                                                                                        <?php
                                                                                                        echo convertTimeZone(
                                                                                                            $eeo_form_info['last_completed_on'],
                                                                                                            DB_DATE_WITH_TIME,
                                                                                                            STORE_DEFAULT_TIMEZONE_ABBR,
                                                                                                            getLoggedInPersonTimeZone(),
                                                                                                            true,
                                                                                                            DATE_WITH_TIME
                                                                                                        );
                                                                                                        ?>

                                                                                                    </strong></p>
                                                                                            <?php } ?>
                                                                                        </td>
                                                                                    </tr>
                                                                                <?php } ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--  -->
                                                <?php $this->load->view('hr_documents_management/general_document_assignment'); ?>
                                                <!-- group start -->
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <div class="hr-box">
                                                            <div class="hr-box-header">
                                                                <strong>All Document Groups</strong>
                                                            </div>
                                                            <div class="hr-innerpadding">
                                                                <div class="table-responsive">
                                                                    <table class="table table-bordered table-hover table-stripped">
                                                                        <thead>
                                                                            <tr>
                                                                                <th class="col-lg-3">Group Name</th>
                                                                                <th class="col-lg-3 text-center">Group Description</th>
                                                                                <th class="col-lg-3 text-center">Status</th>
                                                                                <th class="col-lg-3 text-center">Actions</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php foreach ($groups as $group) { ?>
                                                                                <?php if ($group['document_status'] == 1 || $group['w4'] == 1 || $group['w9'] == 1 || $group['i9'] == 1) { ?>
                                                                                    <tr id='row_<?php echo $group['sid']; ?>'>
                                                                                        <td><?php echo ucwords($group['name']); ?></td>
                                                                                        <td><?php echo html_entity_decode($group['description']); ?></td>
                                                                                        <td><?php echo $group['status'] == 1 ? 'Active' : 'Deactive'; ?></td>
                                                                                        <td>
                                                                                            <?php if (in_array($group['sid'], $assigned_groups)) { ?>
                                                                                                <button type="button" class="btn btn-success btn-block btn-sm">Group Assigned</button>
                                                                                            <?php } else { ?>
                                                                                                <button type="button" id="btn_group_<?php echo $group['sid']; ?>" onclick="func_assign_document_group(<?php echo $group['sid']; ?>,'<?php echo $user_type; ?>',<?php echo $user_sid; ?>)" class="btn btn-primary btn-block btn-sm">Assign Group</button>
                                                                                            <?php } ?>
                                                                                        </td>
                                                                                    </tr>
                                                                                <?php } ?>
                                                                            <?php } ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- group end-->

                                                <?php if (!empty($all_uploaded_generated_doc)) { ?>
                                                    <div class="panel panel-default ems-documents">
                                                        <div class="panel-heading">
                                                            <strong>All Documents</strong>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="table-responsive">
                                                                <table class="table table-plane">
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="col-xs-5">Document Name</th>
                                                                            <th class="col-xs-4">Type</th>
                                                                            <th class="col-xs-3 text-center" colspan="3">Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php foreach ($all_uploaded_generated_doc as $key => $document) { ?>
                                                                            <tr class="package_label">
                                                                                <td>
                                                                                    <strong>
                                                                                        <?php echo $document['document_title']; ?>
                                                                                    </strong>
                                                                                </td>
                                                                                <td>
                                                                                    <?php echo ucwords($document['document_type']); ?>
                                                                                </td>
                                                                                <!-- <td id="assign-status-td---><? //= $document['sid'];
                                                                                                                    ?>
                                                                                <!--">-->
                                                                                <td>
                                                                                    <?php if (in_array($document['sid'], $approval_documents)) { ?>
                                                                                        <button data-approval_document_sid="<?= $document['sid']; ?>" class="btn btn-danger btn-block btn-sm jsRevokeApprovalDocument">Revoke Approval</button>
                                                                                        <button data-document_sid="<?= $document['sid']; ?>" data-user_type="<?= $user_type; ?>" data-user_sid="<?= $user_sid; ?>" class="btn btn-success btn-block btn-sm jsViewDocumentApprovares">
                                                                                            View Approver(s)
                                                                                        </button>
                                                                                    <?php } else { ?>
                                                                                        <?php if (in_array($document['sid'], $all_assigned_sids)) {  // revoke here 
                                                                                        ?>
                                                                                            <button class="btn btn-danger btn-sm btn-block js-modify-revoke-document-btn" data-id="<?= $document['sid']; ?>">Revoke</button>

                                                                                        <?php } else if (in_array($document['sid'], $revoked_sids)) { // re-assign here 
                                                                                        ?>
                                                                                            <button class="btn btn-warning btn-sm btn-block js-modify-assign-document-btn" data-id="<?= $document['sid']; ?>">Modify and Reassign</button>
                                                                                        <?php } else { ?>
                                                                                            <button class="btn btn-success btn-sm btn-block js-modify-assign-document-btn" data-id="<?= $document['sid']; ?>">Modify and Assign</button>
                                                                                        <?php } ?>
                                                                                    <?php } ?>
                                                                                </td>
                                                                                <td>
                                                                                    <?php if ($document['document_type'] == 'hybrid_document') { ?>
                                                                                        <button type="button" data-id="<?= $document['sid'] ?>" data-document="original" data-type="document" class="btn btn-success btn-sm btn-block js-hybrid-preview">
                                                                                            View Doc
                                                                                        </button>
                                                                                    <?php } else if ($document['document_type'] == 'uploaded') { ?>
                                                                                        <!-- <button type="button" class="btn btn-success btn-sm  btn-block"
                                                                                                onclick="fLaunchModal(this);"
                                                                                                data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_document_s3_name']; ?>"
                                                                                                data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_document_s3_name']; ?>"
                                                                                                data-file-name="<?php echo $document['uploaded_document_original_name']; ?>"
                                                                                                data-document-title="<?php echo $document['uploaded_document_original_name']; ?>">View Doc</button> -->

                                                                                        <button class="btn btn-success btn-sm btn-block" type="button" onclick="preview_latest_generic_function(this);" date-letter-type="uploaded" data-on-action="assigned" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_document_s3_name']; ?>" data-s3-name="<?php echo $document['uploaded_document_s3_name']; ?>">
                                                                                            View Doc
                                                                                        </button>
                                                                                    <?php } else { ?>
                                                                                        <!-- <button type="button" onclick="func_get_generated_document_preview(<?php echo $document['sid']; ?>,'generated', '<?php echo addslashes($document['document_title']); ?>');" class="btn btn-success btn-sm btn-block">View Doc</button> -->

                                                                                        <button class="btn btn-success btn-sm btn-block" type="button" onclick="preview_latest_generic_function(this);" date-letter-type="generated" data-doc-sid="<?php echo $document['sid']; ?>" data-on-action="company" data-from="company_document">
                                                                                            View Doc
                                                                                        </button>
                                                                                    <?php } ?>
                                                                                </td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="panel panel-default ems-documents">
                                                        <div class="panel-heading">
                                                            <strong>Company Documents</strong>
                                                        </div>
                                                        <div class="panel-body text-center">
                                                            <span class="no-data">No Documents Found</span>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="learning" class="step-documents" style="display: none;">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="well well-sm">
                                                    <strong>Instructions:</strong>
                                                    <p>Learning center videos and training session can be configured with one simple click</p>
                                                </div>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <?php if (!empty($videos)) { ?>
                                                    <div class="panel panel-default ems-documents">
                                                        <div class="panel-heading">
                                                            <strong>Online Videos</strong>
                                                        </div>
                                                        <div class="panel-body"><?php foreach ($videos as $document) { ?>
                                                                <article class="col-sm-12 listing-article interview-video-listing">
                                                                    <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
                                                                        <figure class="assign-video-player">
                                                                            <?php if ($document['video_source'] == 'youtube') { ?>
                                                                                <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo $document['video_id'] ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                                                            <?php   } elseif ($document['video_source'] == 'vimeo') { ?>
                                                                                <iframe class="embed-responsive-item" src="https://player.vimeo.com/video/<?php echo $document['video_id'] ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                                                            <?php   } else { ?>
                                                                                <video controls width="300" height="150">
                                                                                    <source src="<?php echo base_url('assets/uploaded_videos/' . $document['video_id']) ?>" type='video/mp4'>
                                                                                </video>
                                                                            <?php   } ?>
                                                                        </figure>
                                                                    </div>
                                                                    <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
                                                                        <h3><?php echo $document['video_title']; ?></h3>
                                                                        <div class="full-width announcement-des">
                                                                            <p><?php echo $document['video_description']; ?></p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 assign-status">
                                                                        <strong>Assign Status</strong>
                                                                        <label class="control control--checkbox">
                                                                            <input <?php echo set_checkbox('videos[]', $document['sid'], in_array($document['sid'], $assign_videos)); ?> data-type="video_session" id="video_session<?php echo $document['sid']; ?>" name="video_session[]" type="checkbox" value="<?php echo $document['sid']; ?>" />
                                                                            <div class="control__indicator"></div>
                                                                        </label>
                                                                    </div>
                                                                </article>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="panel panel-default ems-documents">
                                                        <div class="panel-heading">
                                                            <strong>Training Session</strong>
                                                        </div>
                                                        <div class="panel-body">
                                                            <span class="no-data">No Video Found</span>
                                                        </div>
                                                    </div>
                                                <?php } ?>

                                                <?php if (!empty($sessions)) { ?>
                                                    <div class="panel panel-default ems-documents">
                                                        <div class="panel-heading">
                                                            <strong>Training Session</strong>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="table-responsive">
                                                                <table class="table table-plane">
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="col-lg-2">Topic</th>
                                                                            <th class="col-lg-2 text-center">Date</th>
                                                                            <th class="col-lg-2 text-center">Start Time</th>
                                                                            <th class="col-lg-2">End Time</th>
                                                                            <th class="col-lg-2 text-center">Preview</th>
                                                                            <th class="col-lg-2 text-center">Assign Status</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php foreach ($sessions as $document) { ?>
                                                                            <tr class="package_label">
                                                                                <td class="col-lg-3">
                                                                                    <strong>
                                                                                        <?php echo $document['session_topic']; ?>
                                                                                    </strong>
                                                                                </td>
                                                                                <td class="col-lg-1 text-center">
                                                                                    <strong>
                                                                                        <!-- <?php echo $document['session_date']; ?> -->
                                                                                        <?= reset_datetime(array('datetime' => $document['session_date'], '_this' => $this)); ?>
                                                                                    </strong>
                                                                                </td>
                                                                                <td class="col-lg-1 text-center">
                                                                                    <strong>
                                                                                        <?= reset_datetime(array('datetime' => $document['session_date'] . ' ' . $document['session_start_time'], '_this' => $this,  'format' => 'h:i a')); ?>
                                                                                    </strong>
                                                                                </td>
                                                                                <td class="col-lg-1 text-center">
                                                                                    <strong>
                                                                                        <?= reset_datetime(array('datetime' => $document['session_date'] . ' ' . $document['session_end_time'], '_this' => $this,  'format' => 'h:i a')); ?>
                                                                                    </strong>
                                                                                </td>
                                                                                <td class="col-lg-1 text-center">
                                                                                    <strong>
                                                                                        <button type="button" onclick="func_get_training_session_preview('<?php echo $document['session_topic']; ?>', '<?php echo $document['session_description']; ?>', '<?php echo $document['session_location']; ?>', '<?php echo $document['session_date']; ?>', '<?php echo $document['session_start_time']; ?>', '<?php echo $document['session_end_time']; ?>');" class="btn btn-default btn-block">Preview</button>
                                                                                    </strong>
                                                                                </td>
                                                                                <td class="assign_docx_checkbox_center">
                                                                                    <label class="control control--checkbox">
                                                                                        <input <?php echo set_checkbox('tsession[]', $document['sid'], in_array($document['sid'], $assign_session)); ?> class="" data-type="tsession" id="tsession_<?php echo $document['sid']; ?>" name="tsession[]" type="checkbox" value="<?php echo $document['sid']; ?>" />
                                                                                        <div class="control__indicator control_indicator_position_relative"></div>
                                                                                    </label>
                                                                                </td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="panel panel-default ems-documents">
                                                        <div class="panel-heading">
                                                            <strong>Training Session</strong>
                                                        </div>
                                                        <div class="panel-body">
                                                            <span class="no-data">No Session Found</span>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if ($user_type == 'applicant') { ?>
                                        <div id="credentials_configuration" style="display: none;">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="well well-sm">
                                                        <strong>Instructions:</strong>
                                                        <p>Please select the access level for the applicant, by default we have selected 'Employee' access level which is the most basic access level of the system.</p>
                                                        <p>Please set the starting date of the applicant.</p>
                                                        <p>We have set default instructions for the applicant so that he/she can easily create his/her login credentials. Please review it and make any necessary modifications as per your requirements.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                    <?php $field_id = 'credentials_email'; ?>
                                                    <div class="form-group <?php echo $user_exists ? 'has-error' : 'has-success'; ?>">
                                                        <?php echo form_label('Email Address', $field_id);
                                                        $read = !$user_exists ? 'readonly="readonly"' : ''; ?>
                                                        <div class="input-group">
                                                            <?php echo form_input($field_id, set_value($field_id, $user_info['email']), 'class="form-control readonly" id="' . $field_id . '" ' . $read); ?>
                                                            <?php if ($user_exists) { ?>
                                                                <span class="input-group-addon">
                                                                    <i class="fa fa-times text-danger"></i>
                                                                </span>
                                                            <?php } else { ?>
                                                                <span class="input-group-addon">
                                                                    <i class="fa fa-check text-success"></i>
                                                                </span>
                                                            <?php } ?>
                                                        </div>
                                                        <?php if ($user_exists) { ?>
                                                            <div class="help-text text-danger">A user with the same email address already exists, you cannot proceed with on-boarding process!</div>
                                                        <?php } else { ?>
                                                            <div class="help-text text-success">This is a unique email address, you can proceed with on-boarding process!</div>
                                                        <?php } ?>
                                                        <?php echo form_error($field_id); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                    <div class="form-group">
                                                        <?php $joining_date = $credentials['joining_date']; ?>
                                                        <?php $field_id = 'credentials_joining_date'; ?>
                                                        <?php echo form_label('Starting Date', $field_id); ?>
                                                        <?php echo form_input($field_id, set_value($field_id, $joining_date, false), 'class="form-control" id="' . $field_id . '"'); ?>
                                                        <?php echo form_error($field_id); ?>
                                                    </div>
                                                </div>

                                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                    <?php $saved_access_level = $credentials['access_level']; ?>
                                                    <?php $field_id = 'credentials_access_level'; ?>
                                                    <?php echo form_label('Access Level', $field_id); ?>
                                                    <div class="hr-select-dropdown">
                                                        <select class="invoice-fields" id="<?php echo $field_id; ?>" name="<?php echo $field_id; ?>">
                                                            <?php foreach ($access_levels as $access_level) { ?>
                                                                <option <?php echo $access_level == $saved_access_level ? 'selected="selected"' : ''; ?> value="<?php echo $access_level; ?>">
                                                                    <?php echo $access_level; ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <?php echo form_error($field_id); ?>
                                                </div>
                                            </div>


                                            <div class="row">
                                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                    <div class="form-group">
                                                        <?php $employee_status = strtolower($user_info['employee_status']); ?>
                                                        <?php $field_id = 'employee-status'; ?>
                                                        <?php echo form_label('Employment Status'); ?>
                                                        <div class="hr-select-dropdown">
                                                            <select class="invoice-fields" name="employee-status" id="employee-status">
                                                                <?php foreach ($employment_statuses as $key => $employee_status) { ?>
                                                                    <option value="<?= $key ?>" <?php if (strtolower($user_info['employee_status']) == $key) {
                                                                                                    echo 'selected';
                                                                                                } ?>><?= $employee_status ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <?php echo form_error($field_id); ?>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                    <?php $employee_type = strtolower($user_info['employee_type']); ?>
                                                    <?php $field_id = 'employee-type'; ?>
                                                    <?php echo form_label('Employment Type'); ?>
                                                    <div class="hr-select-dropdown">
                                                        <select class="invoice-fields" name="employee-type" id="employee-type">
                                                            <?php foreach ($employment_types as $key => $employment_type) { ?>
                                                                <option value="<?= $key ?>" <?php if ($user_info['employee_type'] == $key) {
                                                                                                echo 'selected';
                                                                                            } ?>><?= $employment_type ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <?php echo form_error($field_id); ?>
                                                </div>
                                            </div>


                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                    <?php $instructions = $credentials['instructions']; ?>
                                                    <?php $field_id = 'credentials_instructions'; ?>
                                                    <?php echo form_label('Instructions', $field_id); ?>
                                                    <?php echo form_textarea($field_id, set_value($field_id, html_entity_decode($instructions), false), 'class="form-control ckeditor" id="' . $field_id . '"'); ?>
                                                    <?php echo form_error($field_id); ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="send_email_to_applicant" style="display: none;">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="well well-sm">
                                                        <strong style="font-size: 24px;">Instructions:</strong>
                                                        <p style="font-size: 18px;">If you do not click send, the On-Boarding documentation will not be sent to the candidate.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                    <div class="well well-sm">
                                                        <?php if (empty($email_sent_date) || empty($unique_sid)) { ?>
                                                            <p style="font-size: 20px; color: #b4052c;" id="date-sent-div"><strong>A Notification email has NOT been sent.</strong></p>
                                                        <?php } else { ?>
                                                            <p style="font-size: 20px; color: #518401;" id="date-sent-div"><strong>A Notification email has been sent at <?php echo $email_sent_date ?></strong></p>
                                                        <?php } ?>
                                                    </div>

                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                    <div class="panel panel-default">
                                                        <div class="panel-heading">
                                                            <h2 class="panel-title" style="line-height: 36px;">
                                                                <a href="javascript:;" class="btn btn-xs blue-button pull-right <?= empty($unique_sid) ? ('disabled my-popover') : ''; ?>" <?= empty($unique_sid) ? '' : '' ?> id="<?= !empty($unique_sid) ? 'send_an_email_to_applicant' : ''; ?>" style="font-size: 18px;">
                                                                    <i class="fa fa-paper-plane"></i>&nbsp;Send Onboarding Notification
                                                                </a>
                                                                <strong style="font-size: 24px;">Send Email</strong>
                                                            </h2>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    <?php } ?>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if ($user_type == 'applicant') { ?>
                    <?php $this->load->view('manage_employer/application_tracking_system/profile_right_menu_applicant'); ?>
                <?php } else if ($user_type == 'employee') { ?>
                    <?php $this->load->view('manage_employer/employee_management/profile_right_menu_employee_new'); ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<!-- Main End -->
<!-- Modal -->
<div id="document_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="document_modal_title">Modal title</h4>
            </div>
            <div id="document_modal_body" class="modal-body">
                ...
            </div>
            <div id="document_modal_footer" class="modal-footer">

            </div>
        </div>
    </div>
</div>

<!-- Preview Documents Modal Start -->
<div id="show_preview_documents_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="offer_letter_modal_title"></h4>
            </div>
            <div class="modal-body">
                <div id="offer-letter-iframe-container" style="display:none;">
                    <div class="embed-responsive embed-responsive-4by3">
                        <div id="offer-letter-iframe-holder" class="embed-responsive-item">
                        </div>
                    </div>
                </div>
                <div id="assigned_document_preview" style="display:none;">

                </div>
            </div>
            <div class="modal-footer" id="offer_letter_modal_footer">

            </div>
        </div>
    </div>
</div>
<!-- Preview Documents Modal End -->


<?php $this->load->view('onboarding/custom_what_to_bring'); ?>
<?php $this->load->view('onboarding/custom_office_location'); ?>
<?php $this->load->view('onboarding/custom_office_time'); ?>
<?php $this->load->view('onboarding/custom_useful_links'); ?>

<div id="model_generated_doc" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="generated_document_title">Assign Document</h4>
            </div>
            <div class="modal-body">
                <div class="compose-message">
                    <div class="universal-form-style-v2">
                        <ul>
                            <form method='post' id='register-form' name='register-form' action="<?= current_url(); ?>">
                                <li class="form-col-100 autoheight">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h4 id="gen_document_label"></h4>
                                            <b>Please review this document and make any necessary modifications. Modifications will not affect the Original Document.</b>
                                            <!--<br>The Modified document will only be sent to the <?= ucwords($user_type); ?> it was assigned to.-->
                                        </div>
                                    </div>
                                </li>
                                <li class="form-col-100 autoheight">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Document Description<span class="hr-required red"> * </span></label>
                                            <textarea style="padding:5px; height:200px; width:100%;" class="ckeditor" id="gen_doc_description" name="document_description"></textarea>
                                        </div>
                                    </div>
                                </li>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="offer-letter-help-widget full-width form-group" style="top: 0;">
                                            <div class="how-it-works-insturction">
                                                <strong>How it Works:</strong>
                                                <p class="how-works-attr">1. Generate a new Electronic Document</p>
                                                <p class="how-works-attr">2. Enable a Document Acknowledgment</p>
                                                <p class="how-works-attr">3. Enable an Electronic Signature</p>
                                                <p class="how-works-attr">4. Insert multiple tags where applicable</p>
                                                <p class="how-works-attr">5. Save the Document</p>
                                            </div>

                                            <div class="tags-arae">
                                                <div class="col-md-12">
                                                    <h5><strong>Company Information Tags:</strong></h5>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group autoheight">
                                                        <input type="text" class="form-control tag" readonly="" value="{{company_name}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group autoheight">
                                                        <input type="text" class="form-control tag" readonly="" value="{{company_address}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group autoheight">
                                                        <input type="text" class="form-control tag" readonly="" value="{{company_phone}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group autoheight">
                                                        <input type="text" class="form-control tag" readonly="" value="{{career_site_url}}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="tags-arae">
                                                <div class="col-md-12">
                                                    <h5><strong>Employee / Applicant Tags :</strong></h5>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group autoheight">
                                                        <input type="text" class="form-control tag" readonly="" value="{{first_name}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group autoheight">
                                                        <input type="text" class="form-control tag" readonly="" value="{{last_name}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group autoheight">
                                                        <input type="text" class="form-control tag" readonly="" value="{{email}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group autoheight">
                                                        <input type="text" class="form-control tag" readonly="" value="{{job_title}}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="tags-arae">
                                                <div class="col-md-12">
                                                    <h5><strong>Signature tags:</strong></h5>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group autoheight">
                                                        <input type="text" class="form-control tag" readonly="" value="{{signature}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group autoheight">
                                                        <input type="text" class="form-control tag" readonly="" value="{{inital}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group autoheight">
                                                        <input type="text" class="form-control tag" readonly="" value="{{authorized_signature}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group autoheight">
                                                        <input type="text" class="form-control tag" readonly="" value="{{authorized_signature_date}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group autoheight">
                                                        <input type="text" class="form-control tag" readonly="" value="{{sign_date}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group autoheight">
                                                        <input type="text" class="form-control tag" readonly="" value="{{short_text}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group autoheight">
                                                        <input type="text" class="form-control tag" readonly="" value="{{text}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group autoheight">
                                                        <input type="text" class="form-control tag" readonly="" value="{{text_area}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group autoheight">
                                                        <input type="text" class="form-control tag" readonly="" value="{{checkbox}}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="tags-arae">
                                                <div class="col-md-12">
                                                    <h5><strong>Pay Plan / Offer Letter tags:</strong></h5>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group autoheight">
                                                        <input type="text" class="form-control tag" readonly="" value="{{hourly_rate}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group autoheight">
                                                        <input type="text" class="form-control tag" readonly="" value="{{hourly_technician}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group autoheight">
                                                        <input type="text" class="form-control tag" readonly="" value="{{flat_rate_technician}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group autoheight">
                                                        <input type="text" class="form-control tag" readonly="" value="{{semi_monthly_salary}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group autoheight">
                                                        <input type="text" class="form-control tag" readonly="" value="{{semi_monthly_draw}}">
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" id="perform_action" name="perform_action" value="assign_document" />
                                <input type="hidden" name="document_type" id="gen-doc-type">
                                <input type="hidden" name="document_sid" id="gen-doc-sid">
                                <input type="hidden" name="hidden-doc-title" id="hidden-doc-title">
                                <li class="form-col-100 autoheight">
                                    <div class="message-action-btn">
                                        <input type="button" onclick="assign_generated()" value="Assign Document" id="send-gen-doc" class="submit-btn">
                                        <!--<input type="submit" value="Assign Document" id="send-gen-doc" class="submit-btn">-->
                                    </div>
                                </li>
                            </form>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="<?= base_url('assets/mFileUploader/index.css'); ?>" />
<script src="<?= base_url('assets/mFileUploader/index.js'); ?>"></script>
<?php $this->load->view('iframeLoader'); ?>
<?php $this->load->view('hr_documents_management/hybrid/scripts'); ?>
<?php $this->load->view('hr_documents_management/scripts/index', ['offerLetters' => $allOfferLetters, 'doRefresh' => 0]); ?>

<script type="text/javascript">
    $(document).ready(function() {
        var is_notify = "<?php echo $sendNotification; ?>";

        if (is_notify == "no") {
            var notify_text = "<?php echo $sendNotificationText; ?>";
            var notify_url = "<?php echo $sendNotificationURL; ?>";
            // alertify.alert("Notice",notify_text, function () {
            // window.location.href = notify_url;
            // });
        }
        //
        $('#offer_letter_select').select2();
        //
        var
            assignedOfferLetter = <?= json_encode($assignedOfferLetter); ?>,
            offerLetters = <?= json_encode($allOfferLetters); ?>,
            assignedSid = <?= $assigned_offer_letter_sid ?>;
        //
        setTimeout(function() {
            $('#offer_letter_select').select2('val', assignedSid);
        }, 2000);
        //
        $('#credentials_joining_date').datepicker();
        $('.my-popover').popover({
            title: 'Message',
            content: "Save the onboarding first to send email!",
            trigger: 'hover',
            delay: {
                show: 0,
                hide: 500
            }
        });

        $('#offer_letter_select').on('change', function() {
            loadOfferLetterView($(this).val());
        });

        //
        function loadOfferLetterView(
            sid
        ) {
            var
                l = [],
                i = 0,
                il = offerLetters.length;

            for (i; i < il; i++) {
                if (offerLetters[i]['sid'] == sid) l = offerLetters[i];
            }
            //
            if (l.length === 0) return;

            if (sid == assignedSid) {
                l = assignedOfferLetter;
                l.letter_type = l.offer_letter_type;
                l.letter_body = l.document_description;
                l.uploaded_document_s3_name = l.document_s3_name;
                l.uploaded_document_original_name = l.document_original_name;
            }
            //
            $('#js-signers').select2({
                closeOnSelect: false
            });
            $('#js-signers').select2('val', l.signers != null ? l.signers.split(',') : null);
            //
            $('#selected_letter_type').val(l.letter_type);

            $('[name="setting_is_confidential"]').prop('checked', l.is_confidential == 1 ? true : false);


            // Approval flow 
            if (l.has_approval_flow == 1) {

                $('.jsEmployeesadditionalBox').html('');
                $('#js-popup [name="has_approval_flow"]').prop('checked', l.has_approval_flow == 1 ? true : false);
                $('.jsApproverFlowContainer').show();
                $('#js-popup [name="assigner_note"]').val(l.document_approval_note);
                DocumentApproverPrefill(l.document_approval_employees, 0);

            } else {
                $('#js-popup [name="has_approval_flow"]').prop('checked', false);
                $('.jsApproverFlowContainer').hide();
                $('#js-popup [name="assigner_note"]').val();

            }



            //
            var f = getUploadedFileAPIUrl(l.uploaded_document_s3_name, 'original');
            //
            if (l.letter_type == 'hybrid_document') {
                $("#uploaded_offer_letter").show();
                $("#generated_offer_letter").show();
                //
                $("#uploaded_offer_letter_iframe").html(f.getHTML());
                $('#selected_document_s3_name').val(l.uploaded_document_s3_name);
                $('#selected_document_original_name').val(l.uploaded_document_original_name);
                loadIframe(
                    f.URL,
                    f.Target
                );
                //
                CKEDITOR.instances['letter_body'].setData(l.letter_body);
            } else if (l.letter_type == 'generated') {
                $("#generated_offer_letter").show();
                $("#uploaded_offer_letter").hide();
                //
                $('#selected_document_s3_name').val('');
                $('#selected_document_original_name').val('');
                ///
                CKEDITOR.instances['letter_body'].setData(l.letter_body);
            } else {
                $("#uploaded_offer_letter").show();
                $("#generated_offer_letter").hide();
                //
                $("#uploaded_offer_letter_iframe").html(f.getHTML());
                $('#selected_document_s3_name').val(l.uploaded_document_s3_name);
                $('#selected_document_original_name').val(l.uploaded_document_original_name);
                loadIframe(
                    f.URL,
                    f.Target
                );
                //
                $('#letter_body').val('');
            }
        }

        // var offer_letter_type = "<?php echo $assigned_offer_letter_type; ?>";
        // if (offer_letter_type == 'uploaded') {
        //     $('#selected_document_s3_name').val("<?php echo $assign_offer_letter_s3_url; ?>");
        //     $('#selected_document_original_name').val("<?php echo $assign_offer_letter_name; ?>");
        //     $('#selected_letter_type').val(offer_letter_type);
        //     $("#uploaded_offer_letter").show();
        // } else {
        //     $('#selected_letter_type').val(offer_letter_type);
        //     $("#generated_offer_letter").show();
        // } 

        var welcome_inputbox_status = '<?php echo isset($source) ? $source : "youtube"; ?>';

        if (welcome_inputbox_status == 'youtube' || welcome_inputbox_status == 'vimeo') {
            $('#welcome_yt_vm_video_container').show();
            $('#welcome_up_video_container').hide();
        } else if (welcome_inputbox_status == 'upload') {
            $('#welcome_yt_vm_video_container').hide();
            $('#welcome_up_video_container').show();
        }

        $('.select-package:checked').parent().addClass("selected-package");

        <?php if ($user_type == 'applicant') { ?>

            $('.js-finish-btn').click(function() {
                var user_exists = '<?php echo $user_exists ? 'true' : 'false'; ?>';
                if (user_exists == 'true') {
                    alertify.error('User with same email already exists in your company!');
                    return;
                }
                alertify.confirm(
                    'Are you sure?',
                    'Are you sure you want to finish setup and move the applicant to On-boarding?',
                    function() {
                        $('#form_onboarding_setup').submit();
                    },
                    function() {
                        alertify.error('Canceled!');
                    }
                );
            });
            var btnFinish = $('<button></button>');
            btnFinish.text('Save')
                .addClass('btn btn-default finish-btn')
                .attr('type', 'button')
                .on('click', function() {
                    var user_exists = '<?php echo $user_exists ? 'true' : 'false'; ?>';
                    if (user_exists == 'false') {
                        alertify.confirm(
                            'Are you sure?',
                            'Are you sure you want to finish setup and move the applicant to On-boarding?',
                            function() {
                                $('#form_onboarding_setup').submit();
                            },
                            function() {
                                alertify.error('Canceled!');
                            }
                        );
                    } else {
                        alertify.error('User with same email already exists in your company!');
                    }
                });
        <?php } else if ($user_type == 'employee') { ?> // Toolbar extra buttons
            var btnFinish = $('<button></button>');
            btnFinish.text('Save')
                .addClass('btn btn-default finish-btn')
                .attr('type', 'button')
                .on('click', function() {
                    alertify.confirm(
                        'Are you sure?',
                        'Are you sure you want to finish employee panel setup?',
                        function() {
                            $('#form_onboarding_setup').submit();
                        },
                        function() {
                            alertify.error('Canceled!');
                        }
                    );
                });
        <?php } ?>

        $('#step_onboarding').smartWizard({
            selected: 0,
            theme: 'default',
            transitionEffect: 'fade',
            showStepURLhash: true,
            toolbarSettings: {
                toolbarPosition: 'both',
                toolbarExtraButtons: [btnFinish]
            },
            anchorSettings: {
                enableAllAnchors: true
            }
        });

        $("#step_onboarding").on("showStep", function(e, anchorObject, stepNumber, stepDirection) {
            if (anchorObject.html() == 'Summary') {
                $('.sw-btn-next').removeClass('btn-success');
                $('.sw-btn-next').addClass('btn-default');
                $('.finish-btn').removeClass('btn-default');
                $('.finish-btn').addClass('btn-success');
            }
            if (anchorObject.html() == 'Offer Letter' || anchorObject.html() == 'Documents') {
                $('.finish-btn').hide();
            } else {
                $('.finish-btn').show();
            }
        });

        $("#step_onboarding").on("leaveStep", function(e, anchorObject, stepNumber, stepDirection) {
            if (anchorObject.html() == 'Summary') {
                $('.sw-btn-next').removeClass('btn-default');
                $('.sw-btn-next').addClass('btn-success');
                $('.finish-btn').removeClass('btn-success');
                $('.finish-btn').addClass('btn-default');
            }
        });

        var checked_ = $('input[type=checkbox]:checked');

        $(checked_).each(function() {
            var type = $(this).attr('data-type');
            var value = $(this).val();
            var checked = $(this).prop('checked')

            if (checked == true) {
                $('li#' + type + '_' + value).addClass('active');
            } else {
                $('li#' + type + '_' + value).removeClass('active');
            }
        });

        $('input[type=checkbox]').on('click', function() {
            var type = $(this).attr('data-type');
            var value = $(this).val();
            var checked = $(this).prop('checked')

            if (checked == true) {
                $('li#' + type + '_' + value).addClass('active');
            } else {
                $('li#' + type + '_' + value).removeClass('active');
            }
        });

        $('.sw-btn-next').removeClass('btn-default');
        $('.sw-btn-next').addClass('btn-success');

        $('.collapse').on('shown.bs.collapse', function() {
            $(this).parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
        }).on('hidden.bs.collapse', function() {
            $(this).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
        });
    });

    function preview_latest_generic_function(source) {
        var letter_type = $(source).attr('date-letter-type');
        var request_type = $(source).attr('data-on-action');
        var document_title = '';

        if (request_type == 'assigned') {
            document_title = 'Assigned Document';
        } else if (request_type == 'submitted') {
            document_title = 'Submitted Document';
        } else if (request_type == 'company') {
            document_title = 'Company Document';
        }

        if (letter_type == 'uploaded') {
            var document_iframe_url = '';
            var document_sid = $(source).attr('data-doc-sid');
            var file_s3_path = $(source).attr('data-preview-url');
            var file_s3_name = $(source).attr('data-s3-name');
            var file_extension = file_s3_name.substr(file_s3_name.lastIndexOf('.') + 1, file_s3_name.length);
            var document_file_name = file_s3_name.substr(0, file_s3_name.lastIndexOf('.'));
            var document_extension = file_extension.toLowerCase();
            var document_print_url = '';
            var document_download_url = '';

            switch (file_extension.toLowerCase()) {
                case 'pdf':
                    document_iframe_url = 'https://docs.google.com/gview?url=' + file_s3_path + '&embedded=true';
                    document_print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' + document_file_name + '.pdf';
                    break;
                case 'csv':
                    document_iframe_url = 'https://docs.google.com/gview?url=' + file_s3_path + '&embedded=true';
                    document_print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' + document_file_name + '.csv';
                    break;
                case 'doc':
                    document_iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURI(file_s3_path);
                    document_print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' + document_file_name + '%2Edoc&wdAccPdf=0';
                    break;
                case 'docx':
                    document_iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURI(file_s3_path);
                    document_print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' + document_file_name + '%2Edocx&wdAccPdf=0';
                    break;
                case 'ppt':
                    document_iframe_url = 'https://docs.google.com/gview?url=' + file_s3_path + '&embedded=true';
                    document_print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' + document_file_name + '.ppt';
                    break;
                case 'pptx':
                    ddocument_iframe_url = 'https://docs.google.com/gview?url=' + file_s3_path + '&embedded=true';
                    document_print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' + document_file_name + '.pptx';
                    break;
                case 'xls':
                    document_iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURI(file_s3_path);
                    ocument_print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' + document_file_name + '%2Exls';
                    break;
                case 'xlsx':
                    document_iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURI(file_s3_path);
                    document_print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' + document_file_name + '%2Exlsx';
                    break;
                case 'jpg':
                case 'jpe':
                case 'jpeg':
                case 'png':
                case 'gif':
                case 'JPG':
                case 'JPE':
                case 'JPEG':
                case 'PNG':
                case 'GIF':
                    document_iframe_url = 'https://docs.google.com/gview?url=' + file_s3_path + '&embedded=true';
                    document_print_url = '<?php echo base_url("hr_documents_management/print_generated_and_offer_later/submitted/generated"); ?>' + document_sid;
                    break;
                default: //using google docs
                    document_iframe_url = 'https://docs.google.com/gview?url=' + document_preview_url + '&embedded=true';
                    break;
            }

            document_download_url = '<?php echo base_url("hr_documents_management/download_upload_document"); ?>' + '/' + file_s3_name;

            $('#show_preview_documents_modal').modal('show');
            $("#offer_letter_modal_title").html(document_title);

            $('#offer-letter-iframe-container').show();
            var model_contant = $("<iframe />")
                .attr("id", "offer_letter_iframe")
                .attr("class", "uploaded-file-preview")
                .attr("src", document_iframe_url);

            $("#offer-letter-iframe-holder").append(model_contant);

            footer_content = '<a target="_blank" class="btn btn-success" href="' + document_print_url + '">Print</a>';
            footer_content += '<a target="_blank" class="btn btn-success" href="' + document_download_url + '">Download</a>';
            $("#offer_letter_modal_footer").html(footer_content);

            //
            loadIframe(
                document_iframe_url,
                '#offer_letter_iframe',
                true
            );
        } else {
            var request_sid = $(source).attr('data-doc-sid');
            var request_from = $(source).attr('data-from');

            $.ajax({
                'url': '<?php echo base_url('hr_documents_management/get_document_content'); ?>' + '/' + request_sid + '/' + request_type + '/' + request_from,
                'type': 'GET',
                success: function(contant) {
                    var obj = jQuery.parseJSON(contant);
                    var requested_content = obj.requested_content;
                    var document_view = obj.document_view;
                    var form_input_data = obj.form_input_data;
                    var is_iframe_preview = obj.is_iframe_preview;

                    var print_url = '<?php echo base_url('hr_documents_management/perform_action_on_document_content'); ?>' + '/' + request_sid + '/' + request_type + '/' + request_from + '/print';
                    var download_url = '<?php echo base_url('hr_documents_management/perform_action_on_document_content'); ?>' + '/' + request_sid + '/' + request_type + '/' + request_from + '/download';

                    $('#show_preview_documents_modal').modal('show');
                    $("#offer_letter_modal_title").html(document_title);

                    if (request_type == 'submitted') {
                        if (is_iframe_preview == 1) {
                            var model_contant = '';

                            $('#offer-letter-iframe-container').show();
                            $('#assigned_document_preview').hide();

                            var model_contant = $("<iframe />")
                                .attr("id", "offer_letter_iframe")
                                .attr("class", "uploaded-file-preview")
                                .attr("src", requested_content);

                            $("#offer-letter-iframe-holder").append(model_contant);
                        } else {
                            $('#offer-letter-iframe-container').hide();
                            $('#assigned_document_preview').show();
                            $("#assigned_document_preview").html(document_view);

                            form_input_data = Object.entries(form_input_data);

                            $.each(form_input_data, function(key, input_value) {
                                if (input_value[0] == 'signature_person_name') {
                                    var input_field_id = input_value[0];
                                    var input_field_val = input_value[1];
                                    $('#' + input_field_id).val(input_field_val);
                                } else {
                                    var input_field_id = input_value[0] + '_id';
                                    var input_field_val = input_value[1];
                                    var input_type = $('#' + input_field_id).attr('data-type');

                                    if (input_type == 'text') {
                                        $('#' + input_field_id).val(input_field_val);
                                        $('#' + input_field_id).prop('disabled', true);
                                    } else if (input_type == 'checkbox') {
                                        if (input_field_val == 'yes') {
                                            $('#' + input_field_id).prop('checked', true);;
                                        }
                                        $('#' + input_field_id).prop('disabled', true);

                                    } else if (input_type == 'textarea') {
                                        $('#' + input_field_id).hide();
                                        $('#' + input_field_id + '_sec').show();
                                        $('#' + input_field_id + '_sec').html(input_field_val);
                                    }
                                }
                            });
                        }
                    } else {
                        $('#offer-letter-iframe-container').hide();
                        $('#assigned_document_preview').show();
                        $("#assigned_document_preview").html(document_view);
                    }

                    // if (request_type == 'submitted') {
                    //     var res = requested_content.replace("data:application/pdf;base64,", "");
                    //     pdf_base64_data = res;
                    //     footer_content = '<button type="button" class="btn btn-success" onclick="print_my_content()">Print</button>';
                    // } else {
                    //     footer_content = '<a target="_blank" class="btn btn-success" href="' + print_url + '">Print</a>';
                    // }   

                    footer_content = '<a target="_blank" class="btn btn-success" href="' + print_url + '">Print</a>';
                    footer_content += '<a target="_blank" class="btn btn-success" href="' + download_url + '">Download</a>';
                    $("#offer_letter_modal_footer").html(footer_content);
                }
            });
        }
    }

    $('#show_preview_documents_modal').on('hidden.bs.modal', function() {
        $("#offer-letter-iframe-holder").html('');
        $("#offer_letter_iframe").remove();
        $('#offer-letter-iframe-container').hide();
        $('#assigned_document_preview').html('');
        $('#assigned_document_preview').hide();
    });

    $('#assign-offer-letter').click(function() {
        var letter_sid = $('#offer_letter_select').val();
        var letter_type = $('#selected_letter_type').val();
        var letter_body = CKEDITOR.instances.letter_body.getData();

        if (!letter_sid) {
            alertify.alert('WARNING!', 'Please select the offer letter first');
            return;
        }

        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to assign this offer letter?',
            function() {
                $.ajax({
                    'url': '<?php echo current_url(); ?>',
                    'type': 'POST',
                    'data': {
                        'perform_action': 'assign_offer_letter',
                        'letter_sid': letter_sid,
                        'letter_body': letter_body,
                        'letter_type': letter_type,
                    },
                    success: function() {
                        $('<a href="javascript:;" id="revoke-offer-letter" class="btn btn-danger">Revoke</a> <a style="display:none;" href="javascript:;" id="activate-offer-letter" class="btn btn-success">Activate</a> <a href="javascript:;" id="reassign-offer-letter" class="btn btn-warning">Modify & Re-Assign</a>').insertAfter('#assign-offer-letter');
                        $('#assign-offer-letter').hide();
                        alertify.success('Assigned Successfully!');
                    }
                });
            },
            function() {
                alertify.error('Canceled!');
            });

    });

    $(document).on('click', '#reassign-offer-letter', function() {


        var letter_sid = $('#offer_letter_select').val();
        var letter_type = $('#selected_letter_type').val();
        var setting_is_confidential = $('[name="setting_is_confidential"]').prop('checked') ? 'on' : 'off';
        var letter_body = CKEDITOR.instances.letter_body.getData();

        var has_approval_flow = $('[name="has_approval_flow"]').prop('checked') ? 'on' : 'off';
        var document_approval_employees = $('[name="assigner]').val();
        var document_approval_note = $('[name="assigner_note"]').val();



        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to re-assign this offer letter?',
            function() {
                $.ajax({
                    'url': '<?php echo current_url(); ?>',
                    'type': 'POST',
                    'data': {
                        'perform_action': 'assign_offer_letter',
                        'letter_sid': letter_sid,
                        'letter_body': letter_body,
                        'letter_type': letter_type,
                        'setting_is_confidential': setting_is_confidential,
                        'signers': $('#js-signers').val(),
                        'has_approval_flow': has_approval_flow,
                        'document_approval_employees': document_approval_employees,
                        'document_approval_note': document_approval_note
                    },
                    success: function() {
                        alertify.success('Re-Assigned Successfully!');
                    }
                });
            },
            function() {
                alertify.error('Canceled!');
            });
    });

    $(document).on('click', '#revoke-offer-letter', function() {

        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to revoke this offer letter?',
            function() {
                $.ajax({
                    'url': '<?php echo current_url(); ?>',
                    'type': 'POST',
                    'data': {
                        'perform_action': 'active_revoke_offer_letter',
                        'status': 0
                    },
                    success: function() {
                        $('#activate-offer-letter').show();
                        $('#revoke-offer-letter').hide();
                        alertify.success('Revoked Successfully!');
                    }
                });
            },
            function() {
                alertify.error('Canceled!');
            });
    });

    $(document).on('click', '#activate-offer-letter', function() {

        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to activate this offer letter?',
            function() {
                $.ajax({
                    'url': '<?php echo current_url(); ?>',
                    'type': 'POST',
                    'data': {
                        'perform_action': 'active_revoke_offer_letter',
                        'status': 1
                    },
                    success: function() {
                        $('#activate-offer-letter').hide();
                        $('#revoke-offer-letter').show();
                        alertify.success('Activated Successfully!');
                    }
                });
            },
            function() {
                alertify.error('Canceled!');
            });
    });

    function assign_generated() {
        var document_description = CKEDITOR.instances.gen_doc_description.getData().trim();
        var type = $('#gen-doc-type').val();
        var doc_title = $('#hidden-doc-title').val();
        var document_sid = $('#gen-doc-sid').val();
        $.ajax({
            'url': '<?php echo current_url(); ?>',
            'type': 'POST',
            'data': {
                'perform_action': 'assign_document',
                'document_sid': document_sid,
                'document_type': type,
                'document_description': document_description
            },
            success: function(data) {
                $('#model_generated_doc').modal('toggle');
                if (type == 'uploaded') {
                    $('#revoke_uploaded_doc_' + document_sid).show();
                    $('#assign_uploaded_doc_' + document_sid).hide();
                } else {
                    $('#revoke_generated_doc_' + document_sid).show();
                    $('#assign_generated_doc_' + document_sid).hide();
                }
                // var button = '<a onclick="func_remove_document('+"'"+type+"'"+', '+document_sid+','+"'"+doc_title+"'"+','+"'"+document_description+"'"+')" class="btn btn-danger btn-block btn-sm">Revoke</a>';
                // $('#assign-status-td-'+document_sid).html(button);
            }
        });
    }

    function func_remove_w4() {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to revoke this document?',
            function() {
                $.ajax({
                    'url': '<?php echo current_url(); ?>',
                    'type': 'POST',
                    'data': {
                        'perform_action': 'remove_w4'
                    },
                    success: function() {
                        var w4_date = '<i class="fa fa-times fa-2x text-danger"></i>';
                        $('#w4').html('Assign');
                        $('#w4').removeClass('btn-danger');
                        $('#w4').addClass('btn-success');
                        $('#w4').attr('onclick', 'func_assign_w4()');
                        $('#w4-date').html(w4_date);
                    }
                });
            },
            function() {
                alertify.error('Canceled!');
            });
    }

    function func_assign_w4() {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to assign this document?',
            function() {
                $.ajax({
                    'url': '<?php echo current_url(); ?>',
                    'type': 'POST',
                    'data': {
                        'perform_action': 'assign_w4'
                    },
                    success: function(data) {
                        var w4_date = '<i class="fa fa-check fa-2x text-success"></i><div class="text-center">' + data + '</div>';
                        $('#w4').html('Revoke');
                        $('#w4').removeClass('btn-success');
                        $('#w4').addClass('btn-danger');
                        $('#w4').attr('onclick', 'func_remove_w4()');
                        $('#w4-date').html(w4_date);
                    }
                });
            },
            function() {
                alertify.error('Canceled!');
            });
    }

    function func_remove_i9() {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to revoke this document?',
            function() {
                $.ajax({
                    'url': '<?php echo current_url(); ?>',
                    'type': 'POST',
                    'data': {
                        'perform_action': 'remove_i9'
                    },
                    success: function() {
                        var i9_date = '<i class="fa fa-times fa-2x text-danger"></i>';
                        $('#i9').html('Assign');
                        $('#i9').removeClass('btn-danger');
                        $('#i9').addClass('btn-success');
                        $('#i9').attr('onclick', 'func_assign_i9()');
                        $('#i9-date').html(i9_date);
                    }
                });
            },
            function() {
                alertify.error('Canceled!');
            });
    }

    function func_assign_i9() {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to assign this document?',
            function() {
                $.ajax({
                    'url': '<?php echo current_url(); ?>',
                    'type': 'POST',
                    'data': {
                        'perform_action': 'assign_i9'
                    },
                    success: function(data) {
                        var i9_date = '<i class="fa fa-check fa-2x text-success"></i><div class="text-center">' + data + '</div>';
                        $('#i9').html('Revoke');
                        $('#i9').removeClass('btn-success');
                        $('#i9').addClass('btn-danger');
                        $('#i9').attr('onclick', 'func_remove_i9()');
                        $('#i9-date').html(i9_date);
                    }
                });
            },
            function() {
                alertify.error('Canceled!');
            });
    }

    function func_remove_w9() {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to revoke this document?',
            function() {
                $.ajax({
                    'url': '<?php echo current_url(); ?>',
                    'type': 'POST',
                    'data': {
                        'perform_action': 'remove_w9'
                    },
                    success: function() {
                        var w9_date = '<i class="fa fa-times fa-2x text-danger"></i>';
                        $('#w9').html('Assign');
                        $('#w9').removeClass('btn-danger');
                        $('#w9').addClass('btn-success');
                        $('#w9').attr('onclick', 'func_assign_w9()');
                        $('#w9-date').html(w9_date);
                    }
                });
            },
            function() {
                alertify.error('Canceled!');
            });
    }

    function func_assign_w9() {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to assign this document?',
            function() {
                $.ajax({
                    'url': '<?php echo current_url(); ?>',
                    'type': 'POST',
                    'data': {
                        'perform_action': 'assign_w9'
                    },
                    success: function(data) {
                        var w9_date = '<i class="fa fa-check fa-2x text-success"></i><div class="text-center">' + data + '</div>';
                        $('#w9').html('Revoke');
                        $('#w9').removeClass('btn-success');
                        $('#w9').addClass('btn-danger');
                        $('#w9').attr('onclick', 'func_remove_w9()');
                        $('#w9-date').html(w9_date);
                    }
                });
            },
            function() {
                alertify.error('Canceled!');
            });
    }

    function func_remove_document(type, document_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to revoke this document?',
            function() {
                // $('#form_remove_document_' + type + '_' + document_sid).submit();
                $.ajax({
                    'url': '<?php echo current_url(); ?>',
                    'type': 'POST',
                    'data': {
                        'perform_action': 'remove_document',
                        'document_sid': document_sid,
                        'document_type': type
                    },
                    success: function(data) {
                        if (type == 'uploaded') {
                            $('#revoke_uploaded_doc_' + document_sid).hide();
                            $('#assign_uploaded_doc_' + document_sid).show();
                        } else {
                            $('#revoke_generated_doc_' + document_sid).hide();
                            $('#assign_generated_doc_' + document_sid).show();
                        }
                    }
                });
            },
            function() {
                alertify.error('Canceled!');
            });
    }

    function func_assign_document(type, document_sid, title = "'Please Confirm, Assign Document'", message = "'Are you sure you want to assign this document?'") {
        alertify.confirm(
            title,
            message,
            function() {
                $.ajax({
                    'url': '<?php echo current_url(); ?>',
                    'type': 'POST',
                    'data': {
                        'perform_action': 'assign_document',
                        'document_sid': document_sid,
                        'document_type': type
                    },
                    success: function(data) {
                        if (type == 'uploaded') {
                            $('#revoke_uploaded_doc_' + document_sid).show();
                            $('#assign_uploaded_doc_' + document_sid).hide();
                        } else {
                            $('#revoke_generated_doc_' + document_sid).show();
                            $('#assign_generated_doc_' + document_sid).hide();
                        }
                        // var button = '<a onclick="func_remove_document('+"'"+type+"'"+', '+document_sid+','+title+','+message+')" class="btn btn-danger btn-block btn-sm">Revoke</a>';
                        // $('#assign-status-td-'+document_sid).html(button);
                    }
                });
            },
            function() {
                alertify.error('Canceled!');
            });
    }

    function fLaunchModalGen(source, assign_type = 'assign') {
        var document_title = $(source).attr('data-title');
        var document_description = $(source).attr('data-description');
        var document_type = $(source).attr('data-document-type');
        var document_sid = $(source).attr('data-document-sid');
        title = 'Modify and Re-Assign This Document';
        document_label = "Are you sure you want to Re-Assign this document [<b>" + document_title + "</b>] <br> <?php echo ucwords($user_type); ?> will be required to re-submit this document";
        button_title = 'Re-Assign this Document';

        if (assign_type == 'assign') {
            title = 'Modify and Assign Document';
            document_label = "Are you sure you want to assign this document: " + document_title;
            button_title = 'Assign This Document';
        }

        $('#model_generated_doc').modal('toggle');

        CKEDITOR.instances.gen_doc_description.setData(document_description);
        $('#gen-doc-type').val(document_type);
        $('#gen-doc-sid').val(document_sid);
        $('#send-gen-doc').val(button_title);
        $('#hidden-doc-title').val(document_title);
        $('#generated_document_title').html(title);
        $('#gen_document_label').html(document_label);
    }

    function func_get_map(location_sid) {
        var my_request;

        my_request = $.ajax({
            url: '<?php echo base_url('onboarding/ajax_responder'); ?>',
            type: 'POST',
            data: {
                'perform_action': 'get_location_map',
                'location_sid': location_sid
            }
        });

        my_request.done(function(response) {
            $('#popupmodallabel').html('Location Map');
            $('#popupmodalbody').html(response);
            $('#popupmodal').modal('show');
        });
    }

    function func_preview_getting_started_section(section_sid) {
        var my_request;

        my_request = $.ajax({
            url: '<?php echo base_url('onboarding/ajax_responder'); ?>',
            type: 'POST',
            data: {
                'perform_action': 'generate_getting_started_section_preview',
                'section_sid': section_sid
            }
        });

        my_request.done(function(response) {
            $('#popupmodallabel').html('Section Preview');
            $('#popupmodalbody').html(response);
            $('#popupmodal .modal-dialog').addClass('modal-lg');
            $('#popupmodal').modal('show');
        });
    }

    function func_map_coordinates(source) {
        var img = $('<img />');
        img.attr('src', 'data:image/png;base64,' + $(source).attr('data-map_base_64'));
        img.addClass('img-responsive');
        var my_container = $('<div></div>');
        my_container.attr('id', 'map');
        my_container.addClass('img-thumbnail');
        my_container.append(img);
        $('#popupmodallabel').text('Map');
        $('#popupmodalbody').html(my_container.html());
        $('#popupmodal').modal('toggle');
    }

    function fLaunchModal(source) {
        var document_preview_url = $(source).attr('data-preview-url');
        var document_download_url = $(source).attr('data-download-url');
        var document_title = $(source).attr('data-document-title');
        var document_file_name = $(source).attr('data-file-name');
        var file_extension = document_file_name.substr(document_file_name.lastIndexOf('.') + 1, document_file_name.length);
        var modal_content = '';
        var footer_content = '';
        var iframe_url = '';

        if (document_preview_url != '') {
            switch (file_extension.toLowerCase()) {
                case 'doc':
                case 'docx':
                    console.log('in office docs check');
                    //using office docs
                    iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_preview_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    break;
                case 'jpg':
                case 'jpe':
                case 'jpeg':
                case 'png':
                case 'gif':
                    //console.log('in images check');
                    modal_content = '<img src="' + document_preview_url + '" style="width:100%; height:500px;" />';
                default:
                    //console.log('in google docs check');
                    //using google docs
                    iframe_url = 'https://docs.google.com/gview?url=' + document_preview_url + '&embedded=true';
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    break;
            }

            footer_content = '<a download="download" class="btn btn-success" href="' + document_download_url + '">Download</a>';
        } else {
            modal_content = '<h5>No ' + document_title + ' Uploaded.</h5>';
            footer_content = '';
        }

        $('#document_modal_body').html(modal_content);
        $('#document_modal_footer').html(footer_content);
        $('#document_modal_title').html(document_title);
        $('#document_modal').modal("toggle");
        $('#document_modal').on("shown.bs.modal", function() {
            if (iframe_url != '') {
                $('#preview_iframe').attr('src', iframe_url);
                //document.getElementById('preview_iframe').contentWindow.location.reload();
            }
        });
    }

    function show_office_details(location_title, location_address, location_telephone, location_fax) {
        var modal_content = '<div class="universal-form-style-v2"><ul><li class="form-col-100"><label>Title:</label><input type="text" class="invoice-fields" readonly value="' + location_title + '"></li><li class="form-col-100 autoheight"><label>Address:</label> <textarea class="invoice-fields autoheight" rows="10" cols="40" readonly>' + location_address + '</textarea></li><li class="form-col-100"><label>Phone:</label> <input type="text" class="invoice-fields" readonly value="' + location_telephone + '"></li><li class="form-col-100"><label>Fax:</label> <input type="text" class="invoice-fields" readonly value="' + location_fax + '"></li></ul></div>';
        var footer_content = '';


        $('#document_modal_body').html(modal_content);
        $('#document_modal_footer').html(footer_content);
        $('#document_modal_title').html('Office Address');
        $('#document_modal').modal("toggle");
        $('#document_modal').on("shown.bs.modal", function() {});
    }

    function func_get_generated_document_preview(document_sid, doc_flag = 'generated', doc_title = 'Preview Generated Document') {
        var my_request;
        my_request = $.ajax({
            'url': '<?php echo base_url('hr_documents_management/ajax_responder'); ?>',
            'type': 'POST',
            'data': {
                'perform_action': 'get_generated_document_preview',
                'document_sid': document_sid,
                'source': doc_flag,
                'fetch_data': 'original'
            }
        });

        my_request.done(function(response) {
            $('#popupmodalbody').html(response);
            $('#popupmodallabel').html(doc_title);
            $('#popupmodal .modal-dialog').css('width', '60%');
            $('#popupmodal').modal('toggle');
        });
    }

    function func_get_training_session_preview(session_topic, session_description, session_location, session_date, session_start_time, session_end_time) {
        var modal_content = '';
        var footer_content = '';
        var iframe_url = '';
        modal_content = '<div class="table-responsive">'
        modal_content += '<table class="table table-boardered">'
        modal_content += '<tr><th class="col-lg-2"><b>Location</b></th><td>' + session_location + '</td>'
        modal_content += '<tr><th class="col-lg-2"><b>Description</b></th><td>' + session_description + '</td>'
        modal_content += '</tr>'
        modal_content += '</table>'
        modal_content += '</div>'
        footer_content = '';

        $('#document_modal_body').html(modal_content);
        $('#document_modal_footer').html(footer_content);
        $('#document_modal_title').html(session_topic);
        $('#document_modal').modal("toggle");
        $('#document_modal').on("shown.bs.modal", function() {
            if (iframe_url != '') {
                $('#preview_iframe').attr('src', iframe_url);
            }
        });
    }

    function func_get_video_session_preview(session_id, session_source, video_title) {
        var modal_content = '';
        var footer_content = '';
        var iframe_url = '';
        var source = '';

        if (session_source == 'youtube') {
            source = "https://www.youtube.com/embed/" + session_id;
        } else {
            source = "https://player.vimeo.com/video/" + session_id;
        }

        modal_content = '<div class="well well-sm"><div class="embed-responsive embed-responsive-16by9"><iframe class="embed-responsive-item" src="' + source + '"></iframe></div></div>';
        footer_content = '';

        $('#document_modal_body').html(modal_content);
        $('#document_modal_footer').html(footer_content);
        $('#document_modal_title').html(video_title);
        $('#document_modal').modal("toggle");
        $('#document_modal').on("shown.bs.modal", function() {
            if (iframe_url != '') {
                $('#preview_iframe').attr('src', iframe_url);
            }
        });
    }

    $(document).on('click', '.select-package', function() {
        $('.select-package:not(:checked)').parent().removeClass("selected-package");
        $('.select-package:checked').parent().addClass("selected-package");
    });

    $('.welcome_video_source').on('click', function() {

        var selected = $(this).val();

        if (selected == 'youtube' || selected == 'vimeo') {
            $('#welcome_yt_vm_video_container').show();
            $('#welcome_up_video_container').hide();
        } else if (selected == 'upload') {
            $('#welcome_yt_vm_video_container').hide();
            $('#welcome_up_video_container').show();
        }
    });

    $('#assign_welcome_video').on('change', function() {
        var welcome_checkBox = document.getElementById("assign_welcome_video");
        var wv_sid = '<?php echo isset($welcome_video_sid) ? $welcome_video_sid : "youtube"; ?>';

        if (welcome_checkBox.checked == false) {
            var myurl = "<?= base_url() ?>onboarding/updateWelcomeVideoStatus";
            $.ajax({
                type: 'POST',
                data: {
                    sid: wv_sid,
                    status: 0
                },
                url: myurl,
                success: function(data) {
                    alertify.success('Welcome Video Disabled Successfully');
                },
                error: function() {

                }
            });
        } else if (welcome_checkBox.checked == true) {
            var myurl = "<?= base_url() ?>onboarding/updateWelcomeVideoStatus";
            $.ajax({
                type: 'POST',
                data: {
                    sid: wv_sid,
                    status: 1
                },
                url: myurl,
                success: function(data) {
                    alertify.success('Welcome Video Enable Successfully');
                },
                error: function() {

                }
            });
        }
    });

    function welcome_video_check(val) {
        var fileName = $("#" + val).val();

        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
            var ext = fileName.split('.').pop();
            var ext = ext.toLowerCase();

            if (val == 'welcome_video_upload') {
                if (ext != "mp4" && ext != "m4a" && ext != "m4v" && ext != "f4v" && ext != "f4a" && ext != "m4b" && ext != "m4r" && ext != "f4b" && ext != "mov") {
                    $("#" + val).val(null);
                    alertify.error("Please select a valid video format.");
                    $('#name_' + val).html('<p class="red">Only (.mp4, .m4a, .m4v, .f4v, .f4a, .m4b, .m4r, .f4b, .mov) allowed!</p>');
                    return false;
                } else {
                    var file_size = Number(($("#" + val)[0].files[0].size / 1024 / 1024).toFixed(2));
                    var video_size_limit = Number('<?php echo UPLOAD_VIDEO_SIZE; ?>');
                    if (video_size_limit < file_size) {
                        $("#" + val).val(null);
                        var file_size = Number(($("#" + val)[0].files[0].size / 1024 / 1024).toFixed(2));
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
                        $('#name_' + val).html('');
                        return false;
                    } else {
                        var selected_file = fileName;
                        var original_selected_file = selected_file.substring(selected_file.lastIndexOf('\\') + 1, selected_file.length);
                        $('#name_' + val).html(original_selected_file);
                        var form = new FormData($('#movieFormData')[0]);
                        myurl = "<?= base_url() ?>onboarding/updateWelcomeVideoSource";
                        $.ajax({
                            url: myurl,
                            type: 'POST',
                            success: function(res) {
                                location.reload();
                                alertify.success('Welcome Uploaded Video Update Successfully');
                            },
                            data: form,
                            cache: false,
                            contentType: false,
                            processData: false
                        });
                        return true;
                    }

                }

            }
        } else {
            $('#name_' + val).html('No video selected');
            alertify.error("No video selected");
            $('#name_' + val).html('<p class="red">Please select video</p>');
            return false;

        }
    }

    function my_yt_vm_video_url_function() {
        var myurl = "<?= base_url() ?>onboarding/updateWelcomeVideoSource";
        var wv_sid = '<?php echo isset($welcome_video_sid) ? $welcome_video_sid : "youtube"; ?>';

        if ($('input[name="welcome_video_source"]:checked').val() == 'youtube') {
            if ($('#yt_vm_video_url').val() != '') {
                var p = /(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.)?youtube\.com\/watch(?:\.php)?\?.*v=)([a-zA-Z0-9\-_]+)/;

                if (!$('#yt_vm_video_url').val().match(p)) {
                    alertify.error('Not a Valid Youtube URL');
                    return false;
                } else {
                    var wv_yt_url = $('#yt_vm_video_url').val();
                    var source = 'youtube';
                    $.ajax({
                        type: 'POST',
                        data: {
                            welcome_sid: wv_sid,
                            welcome_url: wv_yt_url,
                            welcome_source: source
                        },
                        url: myurl,
                        success: function(data) {
                            location.reload();
                            alertify.success('Welcome YouTube Video Update Successfully');
                        },
                        error: function() {

                        }
                    });
                }
            } else {
                alertify.error('Please Provide Valid Youtube URL ');
                return false;
            }

        }

        if ($('input[name="welcome_video_source"]:checked').val() == 'vimeo') {
            if ($('#yt_vm_video_url').val() != '') {
                var validate_vimeo = "<?= base_url() ?>learning_center/validate_vimeo";
                $.ajax({
                    type: "POST",
                    url: validate_vimeo,
                    data: {
                        url: $('#yt_vm_video_url').val()
                    },
                    async: false,
                    success: function(data) {
                        if (data == false) {
                            alertify.error('Not a Valid Vimeo URL');
                            return false;
                        } else {
                            var wv_yt_url = $('#yt_vm_video_url').val();
                            var source = 'vimeo';
                            $.ajax({
                                type: 'POST',
                                data: {
                                    welcome_sid: wv_sid,
                                    welcome_url: wv_yt_url,
                                    welcome_source: source
                                },
                                url: myurl,
                                success: function(data) {
                                    location.reload();
                                    alertify.success('Welcome Vimeo Video Update Successfully');
                                },
                                error: function() {

                                }
                            });
                        }
                    },
                    error: function(data) {}
                });
            } else {
                alertify.error('Please Provide Valid Vimeo URL ');
                return false;
            }
        }
    }

    $('#send_an_email_to_applicant').on('click', function() {
        alertify.confirm(
            'Are you Sure?',
            'Are you sure you want to send an email to an applicant?',
            function() {
                var send_email_url = "<?= base_url() ?>onboarding/sendEmailToApplicant";
                var uq_sid = '<?php echo isset($unique_sid) ? $unique_sid : ""; ?>';
                var u_sid = $('#user_sid').val();
                var c_sid = $('#company_sid').val();
                var c_name = $('#company_name').val();

                $.ajax({
                    type: 'POST',
                    data: {
                        unique_sid: uq_sid,
                        user_sid: u_sid,
                        company_sid: c_sid,
                        company_name: c_name
                    },
                    url: send_email_url,
                    success: function(resp) {
                        if (resp.status) {
                            $('#date-sent-div').html(resp.message);
                            $('#jsNotificationEmailError').hide(resp.message);
                        }

                        alertify.alert('SUCCESS!', resp.message, function() {
                            window.location.reload();
                        });
                    },
                    error: function() {

                    }
                });
            },
            function() {
                alertify.error('Canceled!');
            }).set('labels', {
            ok: 'YES!',
            cancel: 'NO'
        });
    });

    $(document).on('click', '.change_custom_record_status', function() {
        var sid = $(this).val();

        if ($(this).is(':checked')) {
            change_custom_status(sid, 1);
            alertify.success('Custom location Enable');
        } else {
            change_custom_status(sid, 0);
            alertify.error('Custom location Disable');
        }
    });

    function change_custom_status(sid, status) {
        var myurl = "<?= base_url() ?>onboarding/change_custom_status";

        $.ajax({
            type: 'POST',
            data: {
                custom_record_sid: sid,
                custom_record_status: status
            },
            url: myurl,
            success: function(data) {
                return true;
            },
            error: function() {

            }
        });
    }

    function fun_assign_welcome_video(video_sid) {
        var url = "<?= base_url() ?>onboarding/assign_welcome_video_from_library";

        $.ajax({
            type: 'POST',
            data: {
                welcome_video_sid: video_sid,
                user_sid: '<?php echo $user_sid; ?>',
                usertype: '<?php echo $user_type; ?>',
                company_sid: '<?php echo $company_sid; ?>'
            },
            url: url,
            success: function(data) {
                alertify.success('Welcome Video Update Successfully!');
            },
            error: function() {

            }
        });
    }

    $('#add_custom_welcome_video_submit').click(function() {

        var flag = 0;
        var welcome_video_title = $('#welcome_video_title').val();

        if (welcome_video_title == '') {
            alertify.error('Welcome video title is required');
            flag = 0;
            return false;
        }

        if ($('input[name="welcome_video_source"]:checked').val() == 'youtube') {


            if ($('#yt_vm_video_url').val() != '') {

                var p = /(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.)?youtube\.com\/watch(?:\.php)?\?.*v=)([a-zA-Z0-9\-_]+)/;
                if (!$('#yt_vm_video_url').val().match(p)) {
                    alertify.error('Not a Valid Youtube URL');
                    flag = 0;
                    return false;
                } else {
                    flag = 1;
                }
            } else {
                flag = 0;
            }

        } else if ($('input[name="welcome_video_source"]:checked').val() == 'vimeo') {

            if ($('#yt_vm_video_url').val() != '') {
                var flag = 0;
                var myurl = "<?= base_url() ?>learning_center/validate_vimeo";
                $.ajax({
                    type: "POST",
                    url: myurl,
                    data: {
                        url: $('#yt_vm_video_url').val()
                    },
                    async: false,
                    success: function(data) {
                        if (data == false) {
                            alertify.error('Not a Valid Vimeo URL');
                            flag = 0;
                            return false;
                        } else {
                            flag = 1;
                        }
                    },
                    error: function(data) {}
                });
            } else {
                flag = 0;
            }
        } else if ($('input[name="welcome_video_source"]:checked').val() == 'upload') {
            var file = welcome_video_check('welcome_video_upload');
            if (file == false) {
                // alertify.error('Please upload welcome video');
                flag = 0;
                return false;
            } else {
                flag = 1;
            }
        } else {
            flag = 0
        }

        if (flag == 1) {
            $('#my_loader').show();
            $("#func_insert_welcome_video").submit();
        } else {
            alertify.error('Please provide welcome video data');
        }
    });

    function func_assign_document_group(group_sid, user_type, user_sid) {
        var myurl = "<?php echo base_url('hr_documents_management/ajax_assign_group_2_applicant'); ?>" + '/' + group_sid + "/" + user_type + "/" + user_sid;

        $.ajax({
            type: "GET",
            url: myurl,
            async: false,
            success: function(data) {

                $("#btn_group_" + group_sid).removeClass("btn btn-primary btn-block btn-sm");
                $("#btn_group_" + group_sid).addClass("btn btn-success btn-block btn-sm");
                $("#btn_group_" + group_sid).text("Group Assigned");

                alertify.success('Group Assigned Successfully');
                location.reload();

            },
            error: function(data) {

            }
        });
    }

    function fun_hire_applicant() {
        alertify.confirm(
            'Are you Sure?',
            'By selecting this option the Candidate will skip the onboarding process. Are you sure you want to directly hire this Candidate?',
            function() {
                var hiring_url = "<?php echo base_url('hire_onboarding_applicant/hire_applicant_manually'); ?>";

                $.ajax({
                    type: 'POST',
                    data: {
                        applicant_sid: '<?php echo isset($user_sid) && !empty($user_sid) ? $user_sid : ""; ?>',
                        applicant_job_sid: '<?php echo isset($job_list_sid) && !empty($job_list_sid) ? $job_list_sid : ""; ?>',
                        company_sid: '<?php echo isset($company_sid) && !empty($company_sid) ? $company_sid : ""; ?>'
                    },
                    url: hiring_url,
                    success: function(data) {
                        if (data == 'success') {
                            alertify.success('Applicant is successfully hired!');
                            setTimeout(function() {
                                window.location.href = '<?php echo base_url("application_tracking_system/active/all/all/all/all/all/all/all/all/all"); ?>';
                            }, 1000);
                        } else if (data == 'failure_e') {
                            alertify.success('Error! The E-Mail address of the applicant is already registered at your company as employee!');
                        } else if (data == 'failure_f') {
                            alertify.success('Could not found applicant data, Please try again!');
                        } else if (data == 'failure_i') {
                            alertify.success('Could not move applicant to employee due to database error, Please try again!');
                        } else if (data == 'error') {
                            alertify.success('Could not found applicant information, Please try again!');
                        }
                    },
                    error: function() {

                    }
                });
            },
            function() {
                alertify.error('Canceled!');
            }).set('labels', {
            ok: 'YES!',
            cancel: 'NO'
        });
    }
</script>
<style>
    .select2-container--default .select2-selection--single {
        background-color: #fff !important;
        border: 1px solid #aaa !important;
    }

    .select2-container .select2-selection--single {

        height: 38px !important;
    }
</style>
<script>
    $(function() {
        //
        $('.jsResendEEOC').click(function(event) {
            //
            event.preventDefault();
            var msg = $(this).text().trim().toLowerCase() == 'assign' ? "Are you sure you want to assign EEOC form?" : "Are you sure you want to sent an email notification?";
            var msg2 = $(this).text().trim().toLowerCase() == 'assign' ? 'EEOC form has been assigned.' : 'EEOC form notification has been sent.';
            //
            alertify.confirm(
                msg,
                function() {
                    //
                    $.post(
                        "<?= base_url('send_eeoc_form'); ?>", {
                            userId: <?= $user_sid; ?>,
                            userType: "<?= $user_type; ?>",
                            userJobId: "<?= $job_list_sid; ?>",
                            userLocation: "Setup Panel"
                        }
                    ).done(function(resp) {
                        //
                        if (resp == 'success') {
                            alertify.alert('Success!', msg2, function() {
                                window.location.reload();
                            });
                        } else {
                            //
                            alertify.alert('Error!', 'Something went wrong. Please, try again in a few moments.')
                        }
                    });
                }
            ).setHeader('Confirm!');
        });
    });
    //
    function func_remove_EEOC(event) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to revoke this document?',
            function() {
                generateForm('remove_EEOC', 'form_remove_EEOC');
                $('#form_remove_EEOC').submit();
            },
            function() {
                alertify.alert("Warning", 'Cancelled!');
            });
    }

    function generateForm(formAction, formId) {
        var form = '<form id="' + (formId) + '" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">';
        form += ' <input type="hidden" id="perform_action" name="perform_action" value="' + (formAction) + '" />';
        form += ' <input type="hidden" name="company_sid" value="<?= $company_sid; ?>" />';
        form += ' <input type="hidden" name="user_sid" value="<?= $user_sid; ?>" />';
        form += ' <input type="hidden" name="user_type" value="<?= $user_type; ?>" />';
        form += ' </form>';

        $('body').remove('#' + (formId) + '').append(form);
    }
    //
    function func_assign_EEOC() {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to assign this document?',
            function() {
                generateForm('assign_EEOC', 'form_assign_EEOC')
                $('#form_assign_EEOC').submit();
            },
            function() {
                alertify.alert("Warning", 'Cancelled!');
            });
    }

    $(document).on('click', '.jsRevokeApprovalDocument', function() {
        var approval_document_sid = $(this).data("approval_document_sid");
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to revoke approval?',
            function() {
                $('#my_loader').show();
                //
                var form_data = new FormData();
                form_data.append('document_sid', approval_document_sid);
                form_data.append('user_sid', '<?php echo $user_sid; ?>');
                form_data.append('user_type', '<?php echo $user_type; ?>');

                $.ajax({
                    url: '<?= base_url('hr_documents_management/revoke_approval_document') ?>',
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'post',
                    data: form_data,
                    success: function(resp) {
                        $('#my_loader').hide();
                        alertify.alert('SUCCESS!', resp.message, function() {
                            window.location.reload();
                        });

                    },
                    error: function() {}
                });
            },
            function() {
                alertify.error('Canceled!');
            }
        );
    });

    $('#confidentialSelectedEmployees').select2();

    $(document).on('click', '[name="setting_is_confidential"]', function() {
        //
        if (!$(this).prop('checked')) {
            $('#confidentialSelectedEmployeesdiv').hide();
            $('#confidentialSelectedEmployees').select2('val', null);
        } else {
            $('#confidentialSelectedEmployeesdiv').show();
        }

    });
</script>

<!--  -->
<?php $this->load->view('hr_documents_management/document_track'); ?>
<?php $this->load->view('hr_documents_management/verification_document_history', ['user_sid' => $user_sid, 'user_type' => $user_type]); ?>

<!-- Preview Latest Document Modal Start -->
<div id="fillable_history_document_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="history_document_modal_title">
                    Fillable Verification History
                </h4>
            </div>
            <div class="modal-body">
                <div id="history_document_preview" style="display:none;">

                </div>
            </div>
            <div class="modal-footer" id="history_document_modal_footer">

            </div>
        </div>
    </div>
</div>
<!-- Preview Latest Document Modal Modal End -->