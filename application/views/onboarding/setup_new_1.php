<?php
    $watch_video_base_url = '';

if (isset($applicant)) {
    $watch_video_base_url = base_url('onboarding/watch_video/' . $unique_sid);
} else if (isset($employee)) {
    $watch_video_base_url = base_url('learning_center/watch_video/');
} 
//echo '<pre>'; print_r($items); echo '</pre>';
?>
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
                                    <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                        <a class="dashboard-link-btn" href="<?php echo base_url('applicant_profile/' . $user_info['sid']); ?>">
                                            <i class="fa fa-chevron-left"></i>Applicant Profile</a>
                                        Setup On-boarding
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
                                        <!----><?php //if ($user_type == 'applicant') { ?>
                                            <li><a href="#offer_letter">Offer Letter</a></li>
                                        <!----><?php //} ?>
                                        <li><a href="#documents">Documents</a></li>
                                        <li><a href="#learning">Learning Center</a></li>
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
                                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                    <article class="listing-article interview-video-listing">
                                                                        <input type="hidden" id="welcome_video_old_url" name="welcome_video_old_url" value="<?php echo isset($collection)? $collection['video_url'] : ''; ?>" />
                                                                        <div class="col-lg-10 col-md-10 col-xs-12 col-sm-10">
                                                                            <figure class="assign-video-player">
                                                                                <?php $collection_source = $collection['video_source']; ?>
                                                                                <?php if($collection_source == 'youtube') { ?>
                                                                                    <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo $collection['video_url']; ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                                                                <?php } elseif($collection_source == 'vimeo') { ?>
                                                                                    <iframe class="embed-responsive-item" src="https://player.vimeo.com/video/<?php echo $collection['video_url']; ?>"  frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                                                                <?php } else {?>
                                                                                    <video controls width="316px" height="auto">
                                                                                        <source src="<?php echo base_url().'assets/uploaded_videos/'.$collection['video_url']; ?>" type='video/mp4'>
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
                                                        </div>
                                                    </div>
                                                </div>        
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
                                                                            <?php if($custom_source == 'youtube') { ?>
                                                                                <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo $welcome_video['video_url']; ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen style="width:100%; height:500px !important;"></iframe>
                                                                            <?php } elseif($custom_source == 'vimeo') { ?>
                                                                                <iframe class="embed-responsive-item" src="https://player.vimeo.com/video/<?php echo $welcome_video['video_url']; ?>"  frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen style="width:100%; height:500px !important;"></iframe>
                                                                            <?php } else {?>
                                                                                <video controls width="100%" height="500px">
                                                                                    <source src="<?php echo base_url().'assets/uploaded_videos/'.$welcome_video['video_url']; ?>" type='video/mp4'>
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
                                                                        <input type="file" name="welcome_video_upload" id="welcome_video_upload" onchange="welcome_video_check('welcome_video_upload')" >
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
                                            <?php       if ($user_type == 'applicant') { ?>
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
                                        </div>
                                        
                                        
                                        <div id="office_locations" class="office-locations">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="well well-sm">
                                                        <strong>Instructions:</strong>
                                            <?php       if ($user_type == 'applicant') { ?>
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
                                                    <?php foreach ($office_locations as $key => $location) { ?>
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
                                                                    <input <?php echo set_checkbox('location[]', $location['sid'], in_array($location['sid'], $locations)); ?> class="select-package" data-type="location" id="location_<?php echo $location['sid']; ?>" name="locations[]" type="checkbox" value="<?php echo $location['sid']; ?>" />

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
                                            <?php       if ($user_type == 'applicant') { ?>
                                                            <p>Please choose the office working hours for <b><?php echo $user_info["first_name"]; ?> <?= $user_info["last_name"] ?></b>.</p>
                                            <?php       } else { ?>
                                                            <p>Please choose the office working hours for <b><?php echo $user_info["first_name"]; ?> <?= $user_info["last_name"] ?></b>.</p>
                                            <?php       } ?>
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
                                                                            <small><?php echo date('h:i A', strtotime($timing['start_time'])) . ' - ' . date('h:i A', strtotime($timing['end_time'])); ?></small>
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
                                            <?php       if ($user_type == 'applicant') { ?>
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
                                                                                <!--<div class="" style="width: 100%; height: 250px; background-repeat: no-repeat; background-size: 100%; background-image: url('<?php //echo AWS_S3_BUCKET_URL . $person['profile_picture'];  ?>'); background-position: center center;"></div>-->
                                                                            <img class="img-responsive img-thumbnail" src="<?php echo AWS_S3_BUCKET_URL . $person['profile_picture']; ?>" alt="Profile Picture" />
                                                                        <?php } else { ?>
                                                                            <!--<div class="" style="width: 100%; height: 250px; background-repeat: no-repeat; background-size: 100%; background-image: url('<?php //echo base_url('assets/images/default_pic.jpg');  ?>'); background-position: center center;"></div>-->
                                                                            <img class="img-responsive img-thumbnail" src="<?php echo base_url('assets/images/default_pic.jpg'); ?>" alt="Profile Picture" />
                                                                        <?php } ?>
                                                                    </figure>
                                                                    <div class="caption">
                                                                        <h4>
                                                                            <strong><?php echo $person['first_name'] . ' ' . $person['last_name']; ?></strong>
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
                                            <?php       if ($user_type == 'applicant') { ?>
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
                                                <?php if (!empty($what_to_bring)) { ?>
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
                                                    <?php if (!empty($custom_office_items)) { ?>
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
                                            <?php       if ($user_type == 'applicant') { ?>
                                                            <p>You can list down all necessary / important hyperlinks.</p>
                                            <?php       } else { ?>
                                                            <p>You can list down all necessary / important hyperlinks.</p>
                                            <?php       } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="row grid-columns">
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
                                                                                    <input type="hidden" name="<?php echo 'link-sid-'.$link['sid']; ?>" value="<?php echo $link['sid']; ?>">
                                                                                    <input type="hidden" name="<?php echo 'sid-'.$link['sid'].'-title'; ?>" value="<?php echo $link['link_title']; ?>">
                                                                                    <input type="hidden" name="<?php echo 'sid-'.$link['sid'].'-url'; ?>" value="<?php echo $link['link_url']; ?>">
                                                                                     <input type="hidden" name="<?php echo 'sid-'.$link['sid'].'-status'; ?>" value="<?php echo $link['status']; ?>">
                                                                                         
                                                                                    <?php $description = $link['link_description'];
                                                                                    if(!empty($links)){
                                                                                        foreach ($links as $key => $value) { 
                                                                                            if($value['link_sid'] == $link['sid']){
                                                                                                $description = $value['link_description'];
                                                                                            }
                                                                                        }
                                                                                        
                                                                                    } ?>
                                                                                    <textarea name="<?php echo 'sid-'.$link['sid'].'-description'; ?>" class="invoice-fields autoheight" cols="40" rows="2" id="edit_link_description"><?php echo $description; ?></textarea>
                                                                                </td>
                                                                                <td class="">
                                                                                    <label class="control control--checkbox">
                                                                                        <!-- <input <?php //echo set_checkbox('links[]', $link['sid'], in_array($link['sid'], $links)); ?> data-type="link" id="link_<?php //echo $link['sid']; ?>" name="links[]" type="checkbox" value="<?php //echo $link['sid']; ?>" /> -->
                                                                                        <input data-type="link" id="link_<?php echo $link['sid']; ?>" name="links[]" type="checkbox" value="<?php echo $link['sid']; ?>" <?php if(!empty($links)){ foreach ($links as $key => $value) { if($value['link_sid'] == $link['sid']){ ?>checked="checked"
                                                                                        <?php }}} ?> />
                                                                                        
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
                                                   <!--  <?php //} ?>
                                                <?php //}// else { ?>
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="text-center">
                                                            <span class="no-data">No Hyperlinks configured</span>
                                                        </div>
                                                    </div>
                                                <?php //} ?> -->
                                            </div>                                               
                                        </div>
                                        <div id="offer_letter" class="offer-letter">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="well well-sm">
                                                        <strong>Instructions:</strong>
                                                        <p>Please select the Offer Letter for <b><?php echo $user_info["first_name"]; ?> <?= $user_info["last_name"] ?></b></p>
                                                    </div>
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
                                                        <label>Offer Letter</label>
                                                        <select id="offer_letter_select" name="offer_letter_select" class="form-control">
                                                            <option value="">Please Select</option>
                                                            <?php foreach ($offer_letters as $offer_letter) { ?>
                                                                <option <?php echo $assigned_offer_letter_sid == $offer_letter['sid'] ? 'selected="selected"' : ''; ?> value="<?php echo $offer_letter['sid']; ?>"><?php echo $offer_letter['letter_name']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label>Letter Body</label>
                                                        <textarea id="letter_body" name="letter_body" class="ckeditor"><?php echo html_entity_decode($offer_letter_body); ?></textarea>
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
                                                            <!-- <div class="col-md-6">
                                                                <div class="form-group autoheight">
                                                                    <input type="text" class="form-control tag" readonly="" value="{{authorized_signature}}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group autoheight">
                                                                    <input type="text" class="form-control tag" readonly="" value="{{authorized_signature_print_name}}">
                                                                </div>
                                                            </div> -->
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

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-right" style="top: 0;">
                                                    <div class="form-group">
                                                        <?php if($assigned_offer_letter_sid == 0){ ?>
                                                            <a href="javascript:;" id="assign-offer-letter" class="btn btn-success">Modify and Assign</a>
                                                        <?php } elseif($assigned_offer_letter_sid > 0 && $offer_letter_status){ ?>
                                                            <a href="javascript:;" id="revoke-offer-letter" class="btn btn-danger">Revoke</a>
                                                            <a style="display:none;" href="javascript:;" id="activate-offer-letter" class="btn btn-success">Activate</a>
                                                            <a href="javascript:;" id="reassign-offer-letter" class="btn btn-warning">Modify and Re-Assign</a>
                                                        <?php } elseif($assigned_offer_letter_sid > 0 && !$offer_letter_status){ ?>
                                                            <a style="display:none;" href="javascript:;" id="revoke-offer-letter" class="btn btn-danger">Revoke</a>
                                                            <a href="javascript:;" id="activate-offer-letter" class="btn btn-success">Activate</a>
                                                            <a href="javascript:;" id="reassign-offer-letter" class="btn btn-warning">Modify and Re-Assign</a>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="documents" class="step-documents">
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
                                                                                        W4 Fillable <?php echo sizeof($w4_form_data) > 0 && !$w4_form_data['status'] ? '<b>(revoked)</b>':'' ;?>
                                                                                    </td>
                                                                                    <td class="col-lg-1 text-center">
                                                                                        <i class="fa fa-2x fa-file-text"></i>
                                                                                    </td>
                                                                                    <td class="col-lg-2 text-center" id="w4-date">
                                                                                        <?php if (sizeof($w4_form_data) > 0 && $w4_form_data['status']) { ?>
                                                                                            <i class="fa fa-check fa-2x text-success"></i>
                                                                                            <div class="text-center">
                                                                                                <?php echo date_format(new DateTime($w4_form_data['sent_date']), 'M d Y h:i a'); ?>
                                                                                            </div>
                                                                                        <?php } else { ?>
                                                                                            <i class="fa fa-times fa-2x text-danger"></i>
                                                                                        <?php } ?>
                                                                                    </td>
                                                                                    <td class="col-lg-1 text-center">
                                                                                        <?php if (sizeof($w4_form_data) > 0) {
                                                                                            if($w4_form_data['status']){ ?>
                                                                                                <a href="javascript:;" id="w4" onclick="func_remove_w4();" class="btn btn-danger">Revoke</a>
                                                                                        <?php } else{ ?>
                                                                                                <a href="javascript:;" id="w4" onclick="func_assign_w4();" class="btn btn-warning">Re-Assign</a>
                                                                                            <?php }
                                                                                        } else { ?>
                                                                                            <a href="javascript:;" id="w4" onclick="func_assign_w4();" class="btn btn-success">Assign</a>
                                                                                        <?php } ?>
                                                                                        <!--                                                                <a href="--><?php ////echo $w4_url; ?><!--" class="btn btn-success">View Sign</a>-->
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="col-lg-3">
                                                                                        W9 Fillable <?php echo sizeof($w9_form_data) > 0 && !$w9_form_data['status'] ? '<b>(revoked)</b>':'' ;?>
                                                                                    </td>
                                                                                    <td class="col-lg-1 text-center">
                                                                                        <i class="fa fa-2x fa-file-text"></i>
                                                                                    </td>
                                                                                    <td class="col-lg-2 text-center" id="w9-date">
                                                                                        <?php if (sizeof($w9_form_data) > 0 && $w9_form_data['status']) { ?>
                                                                                            <i class="fa fa-check fa-2x text-success"></i>
                                                                                            <div class="text-center">
                                                                                                <?php echo date_format(new DateTime($w9_form_data['sent_date']), 'M d Y h:i a'); ?>
                                                                                            </div>
                                                                                        <?php } else { ?>
                                                                                            <i class="fa fa-times fa-2x text-danger"></i>
                                                                                        <?php } ?>
                                                                                    </td>
                                                                                    <td class="col-lg-1 text-center">
                                                                                        <?php if (sizeof($w9_form_data) > 0) {
                                                                                            if ($w9_form_data['status']) { ?>
                                                                                                <a href="javascript:;" id="w9" onclick="func_remove_w9();" class="btn btn-danger">Revoke</a>
                                                                                            <?php } else{ ?>
                                                                                                <a href="javascript:;" id="w9" onclick="func_assign_w9();" class="btn btn-warning">Re-Assign</a>
                                                                                            <?php }
                                                                                        }else { ?>
                                                                                            <a href="javascript:;" id="w9" onclick="func_assign_w9();" class="btn btn-success">Assign</a>
                                                                                        <?php } ?>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="col-lg-3">
                                                                                        I9 Fillable <?php echo sizeof($i9_form_data) > 0 && !$i9_form_data['status'] ? '<b>(revoked)</b>':'' ;?>
                                                                                    </td>
                                                                                    <td class="col-lg-1 text-center">
                                                                                        <i class="fa fa-2x fa-file-text"></i>
                                                                                    </td>
                                                                                    <td class="col-lg-2 text-center" id="i9-date">
                                                                                        <?php if (sizeof($i9_form_data) > 0 && $i9_form_data['status']) { ?>
                                                                                            <i class="fa fa-check fa-2x text-success"></i>
                                                                                            <div class="text-center">
                                                                                                <?php echo date_format(new DateTime($i9_form_data['sent_date']), 'M d Y h:i a'); ?>
                                                                                            </div>
                                                                                        <?php } else { ?>
                                                                                            <i class="fa fa-times fa-2x text-danger"></i>
                                                                                        <?php } ?>
                                                                                    </td>
                                                                                    <td class="col-lg-1 text-center">
                                                                                        <?php if (sizeof($i9_form_data) > 0) {
                                                                                            if ($i9_form_data['status']) { ?>
                                                                                                <a href="javascript:;" id="i9" onclick="func_remove_i9();" class="btn btn-danger">Revoke</a>
                                                                                            <?php } else{?>
                                                                                                <a href="javascript:;" id="i9" onclick="func_assign_i9();" class="btn btn-warning">Re-Assign</a>
                                                                                            <?php }
                                                                                        }else { ?>
                                                                                            <a href="javascript:;" id="i9" onclick="func_assign_i9();" class="btn btn-success">Assign</a>
                                                                                        <?php } ?>
                                                                                    </td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

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
<!--                                                                                <td id="assign-status-td---><?//= $document['sid'];?><!--">-->
                                                                                <td>
                                                                                    <?php if (in_array($document['sid'], $all_assigned_sids) || in_array($document['sid'], $revoked_sids)) {

                                                                                        if(in_array($document['sid'], $all_assigned_sids)) {  // revoke here ?>

                                                                                            <a id="<?= $document['document_type'] == 'uploaded' ? 'revoke_uploaded_doc_'.$document['sid'] : 'revoke_generated_doc_'.$document['sid']; ?>" onclick="func_remove_document('<?php echo $document['document_type']; ?>', <?php echo $document['sid']; ?>)" class="btn btn-danger btn-block btn-sm">Revoke</a>

                                                                                            <?php if($document['document_type'] == 'uploaded') { ?>
                                                                                                <a style="display: none;" id="assign_uploaded_doc_<?php echo $document['sid']; ?>" onclick="func_assign_document('<?php echo $document['document_type']; ?>', <?php echo $document['sid']; ?>, title = 'Please Confirm, Re-Assign Document', 'Are you sure you want to Re-Assign the document? <br><?= ucwords($user_type); ?> will be required to re-submit the document.');" class="btn btn-warning btn-block btn-sm">Re-Assign</a>
                                                                                            <?php } else { ?>
                                                                                                <a style="display: none;" id="assign_generated_doc_<?php echo $document['sid']; ?>" class="btn btn-warning btn-sm btn-block"
                                                                                                   onclick="fLaunchModalGen(this, 'reassign');"
                                                                                                   data-title="<?php echo $document['document_title']; ?>"
                                                                                                   data-description="<?php echo $document['document_description']; ?>"
                                                                                                   data-document-type="<?php echo $document['document_type']; ?>"
                                                                                                   data-document-sid="<?php echo $document['sid']; ?>">Modify and Re-Assign</a>
                                                                                            <?php }
                                                                                        } else { // re-assign here ?>
                                                                                            <?php if($document['document_type'] == 'uploaded') { ?>
                                                                                                <a id="assign_uploaded_doc_<?php echo $document['sid']; ?>" onclick="func_assign_document('<?php echo $document['document_type']; ?>', <?php echo $document['sid']; ?>, title = 'Please Confirm, Re-Assign Document', 'Are you sure you want to Re-Assign the document? <br><?= ucwords($user_type); ?> will be required to re-submit the document.');" class="btn btn-warning btn-block btn-sm">Re-Assign</a>
                                                                                                <a style="display: none;" id="revoke_uploaded_doc_<?php echo $document['sid']; ?>" onclick="func_remove_document('<?php echo $document['document_type']; ?>', <?php echo $document['sid']; ?>)" class="btn btn-danger btn-block btn-sm">Revoke</a>
                                                                                            <?php } else { ?>
                                                                                                <a id="assign_generated_doc_<?php echo $document['sid']; ?>" class="btn btn-warning btn-sm btn-block"
                                                                                                        onclick="fLaunchModalGen(this, 'reassign');"
                                                                                                        data-title="<?php echo $document['document_title']; ?>"
                                                                                                        data-description="<?php echo $document['document_description']; ?>"
                                                                                                        data-document-type="<?php echo $document['document_type']; ?>"
                                                                                                        data-document-sid="<?php echo $document['sid']; ?>">Modify and Re-Assign</a>
                                                                                                <a style="display: none;" id="revoke_generated_doc_<?php echo $document['sid']; ?>" onclick="func_remove_document('<?php echo $document['document_type']; ?>', <?php echo $document['sid']; ?>)" class="btn btn-danger btn-block btn-sm">Revoke</a>
                                                                                            <?php }
                                                                                        }
                                                                                    } else { ?>
                                                                                        <?php if($document['document_type'] == 'uploaded') { ?>
                                                                                            <a id="assign_uploaded_doc_<?php echo $document['sid']; ?>" onclick="func_assign_document('<?php echo $document['document_type']; ?>', <?php echo $document['sid']; ?>);" class="btn btn-success btn-block btn-sm">Assign</a>
                                                                                            <a style="display: none;" id="revoke_uploaded_doc_<?php echo $document['sid']; ?>" onclick="func_remove_document('<?php echo $document['document_type']; ?>', <?php echo $document['sid']; ?>)" class="btn btn-danger btn-block btn-sm">Revoke</a>
                                                                                        <?php } else { ?>
                                                                                            <a id="assign_generated_doc_<?php echo $document['sid']; ?>" class="btn btn-success btn-sm btn-block"
                                                                                                    onclick="fLaunchModalGen(this);"
                                                                                                    data-title="<?php echo $document['document_title']; ?>"
                                                                                                    data-description="<?php echo $document['document_description']; ?>"
                                                                                                    data-document-type="<?php echo $document['document_type']; ?>"
                                                                                                    data-document-sid="<?php echo $document['sid']; ?>">Assign</a>
                                                                                            <a style="display: none;" id="revoke_generated_doc_<?php echo $document['sid']; ?>" onclick="func_remove_document('<?php echo $document['document_type']; ?>', <?php echo $document['sid']; ?>)" class="btn btn-danger btn-block btn-sm">Revoke</a>


                                                                                        <?php }
                                                                                    } ?>
                                                                                </td>
                                                                                <td>
                                                                                    <?php if($document['document_type'] == 'uploaded') { ?>
                                                                                        <button type="button" class="btn btn-success btn-sm  btn-block"
                                                                                                onclick="fLaunchModal(this);"
                                                                                                data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_document_s3_name']; ?>"
                                                                                                data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_document_s3_name']; ?>"
                                                                                                data-file-name="<?php echo $document['uploaded_document_original_name']; ?>"
                                                                                                data-document-title="<?php echo $document['uploaded_document_original_name']; ?>">View Doc</button>
                                                                                    <?php } else { ?>
                                                                                        <button type="button" onclick="func_get_generated_document_preview(<?php echo $document['sid']; ?>,'generated', '<?php echo addslashes($document['document_title']); ?>');" class="btn btn-success btn-sm btn-block">View Doc</button>
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

                                        <div id="learning" class="step-documents">
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
                                                                    <article class="listing-article interview-video-listing">
                                                                        <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
                                                                            <figure class="assign-video-player">
                                                                                <?php   if($document['video_source'] == 'youtube') { ?>
                                                                                    <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo $document['video_id'] ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                                                                <?php   } elseif($document['video_source'] == 'vimeo')  { ?>
                                                                                    <iframe class="embed-responsive-item" src="https://player.vimeo.com/video/<?php echo $document['video_id'] ?>"  frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                                                                <?php   } else { ?> 
                                                                                    <video controls width="300" height="150">
                                                                                        <source src="<?php echo base_url('assets/uploaded_videos/'.$document['video_id']) ?>" type='video/mp4'>
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
                                                                                            <?php echo date('m-d-Y', strtotime($document['session_date'])); ?>
                                                                                        </strong>
                                                                                    </td>
                                                                                    <td class="col-lg-1 text-center">
                                                                                        <strong>
                                                                                            <?php echo date('h:i a', strtotime($document['session_start_time'])); ?>
                                                                                        </strong>
                                                                                    </td>
                                                                                    <td class="col-lg-1 text-center">
                                                                                        <strong>
                                                                                            <?php echo date('h:i a', strtotime($document['session_end_time'])); ?>
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
                                            <div id="credentials_configuration" class="">
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
                                                            <?php echo form_label('Email Address', $field_id); $read = !$user_exists ? 'readonly="readonly"':''; ?>
                                                            <div class="input-group">
                                                                <?php echo form_input($field_id, set_value($field_id, $user_info['email']), 'class="form-control readonly" id="' . $field_id . '" '.$read); ?>
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
                                                            <select class="invoice-fields" id="<?php echo $field_id; ?>" name="<?php echo $field_id; ?>" >
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
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        <?php $instructions = $credentials['instructions']; ?>
                                                        <?php $field_id = 'credentials_instructions'; ?>
                                                        <?php echo form_label('Instructions', $field_id); ?>
                                                        <?php echo form_textarea($field_id, set_value($field_id, html_entity_decode($instructions), false), 'class="form-control ckeditor" id="' . $field_id . '"'); ?>
                                                        <?php echo form_error($field_id); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
<!--                                        <div id="summary" class="step-summary">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="well well-sm">
                                                        <strong>Instructions:</strong>
                                                        <p>It is the summary of all the steps. Please review it. You can re-configure any step in case of you missed anything.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="row">
                                                <div class="panel-group grid-columns">
                                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                                        <div id="office_locations" class="panel panel-default full-width">
                                                            <div class="panel-heading">
                                                                <strong>Office Locations</strong>
                                                            </div>
                                                            <div class="panel-body">
                                                                <?php /*if (!empty($office_locations)) { ?>
                                                                    <ul class="list-group">
                                                                        <?php foreach ($office_locations as $key => $location) { ?>
                                                                            <li class="list-group-item list-item-onboarding" id="location_<?php echo $location['sid']; ?>">
                                                                                <?php echo $location['location_title']; ?>
                                                                            </li>
                                                                        <?php } ?>
                                                                    </ul>
                                                                <?php } */?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                                        <div id="office_timings" class="panel panel-default full-width">
                                                            <div class="panel-heading">
                                                                <strong>Office Hours</strong>
                                                            </div>
                                                            <div class="panel-body">
                                                                <?php /*if (!empty($office_timings)) { ?>
                                                                    <ul id="list-group">
                                                                        <?php foreach ($office_timings as $key => $timing) { ?>
                                                                            <li class="list-group-item list-item-onboarding" id="time_<?php echo $timing['sid']; ?>">
                                                                                <?php echo $timing['title']; ?>
                                                                            </li>
                                                                        <?php } ?>
                                                                    </ul>
                                                                <?php } */?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                                        <div id="people_to_meet" class="panel panel-default full-width">
                                                            <div class="panel-heading">
                                                                <strong>People To Meet</strong>
                                                            </div>
                                                            <div class="panel-body">
                                                                <?php /*if (!empty($people_to_meet)) { ?>
                                                                    <ul class="list-group">
                                                                        <?php foreach ($people_to_meet as $key => $person) { ?>
                                                                            <li class="list-group-item list-item-onboarding" id="person_<?php echo $person['sid']; ?>">
                                                                                <?php echo $person['first_name'] . ' ' . $person['last_name']; ?>
                                                                            </li>
                                                                        <?php } ?>
                                                                    </ul>
                                                                <?php } */?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                                        <div id="what_to_bring" class="panel panel-default full-width">
                                                            <div class="panel-heading">
                                                                <strong>Useful Links</strong>
                                                            </div>
                                                            <div class="panel-body">
                                                                <?php /*if (!empty($useful_links)) { ?>
                                                                    <ul class="list-group">
                                                                        <?php foreach ($useful_links as $key => $link) { ?>
                                                                            <li class="list-group-item list-item-onboarding" id="link_<?php echo $link['sid']; ?>">
                                                                                <?php echo $link['link_title']; ?>
                                                                            </li>
                                                                        <?php } ?>
                                                                    </ul>
                                                                <?php } */?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                                        <div id="getting_started" class="panel panel-default full-width">
                                                            <div class="panel-heading">
                                                                <strong>What to Bring</strong>
                                                            </div>
                                                            <div class="panel-body">
                                                                <?php /*if (!empty($what_to_bring)) { ?>
                                                                    <ul id="list-group">
                                                                        <?php foreach ($what_to_bring as $key => $item) { ?>
                                                                            <li class="list-group-item list-item-onboarding" id="item_<?php echo $item['sid']; ?>">
                                                                                <?php echo $item['item_title']; ?>
                                                                            </li>
                                                                        <?php } ?>
                                                                    </ul>
                                                                <?php } */?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                                        <div id="learning_center" class="panel panel-default full-width">
                                                            <div class="panel-heading">
                                                                <strong>Learning Center</strong>
                                                            </div>
                                                            <div class="panel-body">
                                                                <ul id="list-group">
                                                                    <?php /*if (!empty($videos)) { ?>
                                                                        <?php foreach ($videos as $document) { ?>
                                                                            <li class="list-group-item list-item-onboarding" id="video_session_<?php echo $document['sid']; ?>">
                                                                                <?php echo $document['video_title']; ?>
                                                                            </li>
                                                                        <?php } ?>
                                                                    <?php } ?>

                                                                    <?php if (!empty($sessions)) { ?>
                                                                        <?php foreach ($sessions as $document) { ?>
                                                                            <li class="list-group-item list-item-onboarding" id="tsession_<?php echo $document['sid']; ?>">
                                                                                <?php echo $document['session_topic']; ?>
                                                                            </li>
                                                                        <?php } ?>
                                                                    <?php } */?>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>-->
                                        <?php if ($user_type == 'applicant') { ?>
                                            <div id="send_email_to_applicant" class="">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="well well-sm">
                                                            <strong>Instructions:</strong>
                                                            <p>If you do not click send, the On-Boarding documentation will not be sent to the candidate.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr />
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        <div class="panel panel-default">
                                                            <div class="panel-heading">                                        
                                                                <h4 class="panel-title">
                                                                    <div class="btn btn-xs btn-success pull-right <?=empty($unique_sid) ? 'js-finish-btn' : '';?>" id="<?=!empty($unique_sid) ? 'send_an_email_to_applicant' : '';?>">
                                                                        <i class="fa fa-paper-plane"></i>&nbsp;Send Email
                                                                    </div>
                                                                    Send Email
                                                                </h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </form>
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


<?php $this->load->view('onboarding/custom_what_to_bring'); ?>
<?php $this->load->view('onboarding/custom_office_location'); ?>
<?php $this->load->view('onboarding/custom_office_time'); ?>

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
                                                <b>Please review this document and make any necessary modifications. Modifications will not affect the Original Document.</b> <!--<br>The Modified document will only be sent to the <?= ucwords($user_type); ?> it was assigned to.-->
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
                                                            <input type="text" class="form-control tag" readonly="" value="{{authorized_editable_date}}">
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
                                                            <input type="text" class="form-control tag" readonly="" value="{{checkbox}}">
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
<!--                                            <input type="submit" value="Assign Document" id="send-gen-doc" class="submit-btn">-->
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

<script type="text/javascript">

    $('#assign-offer-letter').click(function(){
        var letter_sid  = $('#offer_letter_select').val();
        var letter_body = CKEDITOR.instances.letter_body.getData();

        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to assign this offer letter?',
            function () {
                $.ajax({
                    'url': '<?php echo current_url(); ?>',
                    'type': 'POST',
                    'data': {
                        'perform_action': 'assign_offer_letter',
                        'letter_sid': letter_sid,
                        'letter_body': letter_body
                    },
                    success: function(){
                        $('<a href="javascript:;" id="revoke-offer-letter" class="btn btn-danger">Revoke</a> <a style="display:none;" href="javascript:;" id="activate-offer-letter" class="btn btn-success">Activate</a> <a href="javascript:;" id="reassign-offer-letter" class="btn btn-warning">Re-Assign</a>').insertAfter('#assign-offer-letter');
                        $('#assign-offer-letter').hide();
                        alertify.success('Assigned Successfully!');
                    }
                });
            },
            function () {
                alertify.error('Cancelled!');
            });

    });

    $(document).on('click','#reassign-offer-letter',function(){
        var letter_sid  = $('#offer_letter_select').val();
        var letter_body = CKEDITOR.instances.letter_body.getData();

        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to re-assign this offer letter?',
            function () {
                $.ajax({
                    'url': '<?php echo current_url(); ?>',
                    'type': 'POST',
                    'data': {
                        'perform_action': 'assign_offer_letter',
                        'letter_sid': letter_sid,
                        'letter_body': letter_body
                    },
                    success: function(){
                        alertify.success('Re-Assigned Successfully!');
                    }
                });
            },
            function () {
                alertify.error('Cancelled!');
            });
    });

    $(document).on('click','#revoke-offer-letter',function(){

        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to rovoke this offer letter?',
            function () {
                $.ajax({
                    'url': '<?php echo current_url(); ?>',
                    'type': 'POST',
                    'data': {
                        'perform_action': 'active_revoke_offer_letter',
                        'status': 0
                    },
                    success: function(){
                        $('#activate-offer-letter').show();
                        $('#revoke-offer-letter').hide();
                        alertify.success('Revoked Successfully!');
                    }
                });
            },
            function () {
                alertify.error('Cancelled!');
            });
    });

    $(document).on('click','#activate-offer-letter',function(){

        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to activate this offer letter?',
            function () {
                $.ajax({
                    'url': '<?php echo current_url(); ?>',
                    'type': 'POST',
                    'data': {
                        'perform_action': 'active_revoke_offer_letter',
                        'status': 1
                    },
                    success: function(){
                        $('#activate-offer-letter').hide();
                        $('#revoke-offer-letter').show();
                        alertify.success('Activated Successfully!');
                    }
                });
            },
            function () {
                alertify.error('Cancelled!');
            });
    });

    function assign_generated(){
        var document_description = CKEDITOR.instances.gen_doc_description.getData().trim();
        var type = $('#gen-doc-type').val();
        var doc_title = $('#hidden-doc-title').val();
        var document_sid = $('#gen-doc-sid').val();
        $.ajax({
            'url': '<?php echo current_url(); ?>',
            'type': 'POST',
            'data': {
                'perform_action': 'assign_document',
                'document_sid' : document_sid,
                'document_type': type,
                'document_description': document_description
            },
            success: function(data){
                $('#model_generated_doc').modal('toggle');
                if(type == 'uploaded'){
                    $('#revoke_uploaded_doc_'+document_sid).show();
                    $('#assign_uploaded_doc_'+document_sid).hide();
                } else{
                    $('#revoke_generated_doc_'+document_sid).show();
                    $('#assign_generated_doc_'+document_sid).hide();
                }
//                var button = '<a onclick="func_remove_document('+"'"+type+"'"+', '+document_sid+','+"'"+doc_title+"'"+','+"'"+document_description+"'"+')" class="btn btn-danger btn-block btn-sm">Revoke</a>';
//                $('#assign-status-td-'+document_sid).html(button);
            }
        });
    }

    function func_remove_w4() {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to revoke this document?',
            function () {
                $.ajax({
                    'url': '<?php echo current_url(); ?>',
                    'type': 'POST',
                    'data': {
                        'perform_action': 'remove_w4'
                    },
                    success: function(){
                        var w4_date = '<i class="fa fa-times fa-2x text-danger"></i>';
                        $('#w4').html('Assign');
                        $('#w4').removeClass('btn-danger');
                        $('#w4').addClass('btn-success');
                        $('#w4').attr('onclick','func_assign_w4()');
                        $('#w4-date').html(w4_date);
                    }
                });
            },
            function () {
                alertify.error('Cancelled!');
            });
    }

    function func_assign_w4() {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to assign this document?',
            function () {
                $.ajax({
                    'url': '<?php echo current_url(); ?>',
                    'type': 'POST',
                    'data': {
                        'perform_action': 'assign_w4'
                    },
                    success: function(data){
                        var w4_date = '<i class="fa fa-check fa-2x text-success"></i><div class="text-center">'+data+'</div>';
                        $('#w4').html('Revoke');
                        $('#w4').removeClass('btn-success');
                        $('#w4').addClass('btn-danger');
                        $('#w4').attr('onclick','func_remove_w4()');
                        $('#w4-date').html(w4_date);
                    }
                });
            },
            function () {
                alertify.error('Cancelled!');
            });
    }

    function func_remove_i9() {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to revoke this document?',
            function () {
                $.ajax({
                    'url': '<?php echo current_url(); ?>',
                    'type': 'POST',
                    'data': {
                        'perform_action': 'remove_i9'
                    },
                    success: function(){
                        var i9_date = '<i class="fa fa-times fa-2x text-danger"></i>';
                        $('#i9').html('Assign');
                        $('#i9').removeClass('btn-danger');
                        $('#i9').addClass('btn-success');
                        $('#i9').attr('onclick','func_assign_i9()');
                        $('#i9-date').html(i9_date);
                    }
                });
            },
            function () {
                alertify.error('Cancelled!');
            });
    }

    function func_assign_i9() {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to assign this document?',
            function () {
                $.ajax({
                    'url': '<?php echo current_url(); ?>',
                    'type': 'POST',
                    'data': {
                        'perform_action': 'assign_i9'
                    },
                    success: function(data){
                        var i9_date = '<i class="fa fa-check fa-2x text-success"></i><div class="text-center">'+data+'</div>';
                        $('#i9').html('Revoke');
                        $('#i9').removeClass('btn-success');
                        $('#i9').addClass('btn-danger');
                        $('#i9').attr('onclick','func_remove_i9()');
                        $('#i9-date').html(i9_date);
                    }
                });
            },
            function () {
                alertify.error('Cancelled!');
            });
    }

    function func_remove_w9() {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to revoke this document?',
            function () {
                $.ajax({
                    'url': '<?php echo current_url(); ?>',
                    'type': 'POST',
                    'data': {
                        'perform_action': 'remove_w9'
                    },
                    success: function(){
                        var w9_date = '<i class="fa fa-times fa-2x text-danger"></i>';
                        $('#w9').html('Assign');
                        $('#w9').removeClass('btn-danger');
                        $('#w9').addClass('btn-success');
                        $('#w9').attr('onclick','func_assign_w9()');
                        $('#w9-date').html(w9_date);
                    }
                });
            },
            function () {
                alertify.error('Cancelled!');
            });
    }

    function func_assign_w9() {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to assign this document?',
            function () {
                $.ajax({
                    'url': '<?php echo current_url(); ?>',
                    'type': 'POST',
                    'data': {
                        'perform_action': 'assign_w9'
                    },
                    success: function(data){
                        var w9_date = '<i class="fa fa-check fa-2x text-success"></i><div class="text-center">'+data+'</div>';
                        $('#w9').html('Revoke');
                        $('#w9').removeClass('btn-success');
                        $('#w9').addClass('btn-danger');
                        $('#w9').attr('onclick','func_remove_w9()');
                        $('#w9-date').html(w9_date);
                    }
                });
            },
            function () {
                alertify.error('Cancelled!');
            });
    }

    function func_remove_document(type, document_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to revoke this document?',
            function () {
//                $('#form_remove_document_' + type + '_' + document_sid).submit();
                $.ajax({
                    'url': '<?php echo current_url(); ?>',
                    'type': 'POST',
                    'data': {
                        'perform_action': 'remove_document',
                        'document_sid' : document_sid,
                        'document_type': type
                    },
                    success: function(data){
                        if(type == 'uploaded'){
                            $('#revoke_uploaded_doc_'+document_sid).hide();
                            $('#assign_uploaded_doc_'+document_sid).show();
                        } else{
                            $('#revoke_generated_doc_'+document_sid).hide();
                            $('#assign_generated_doc_'+document_sid).show();
                        }
                    }
                });
            },
            function () {
                alertify.error('Cancelled!');
            });
    }

    function func_assign_document(type, document_sid, title = "'Please Confirm, Assign Document'", message = "'Are you sure you want to assign this document?'") {
        alertify.confirm(
            title,
            message,
            function (){
                $.ajax({
                    'url': '<?php echo current_url(); ?>',
                    'type': 'POST',
                    'data': {
                        'perform_action': 'assign_document',
                        'document_sid' : document_sid,
                        'document_type': type
                    },
                    success: function(data){
                        if(type == 'uploaded'){
                            $('#revoke_uploaded_doc_'+document_sid).show();
                            $('#assign_uploaded_doc_'+document_sid).hide();
                        } else{
                            $('#revoke_generated_doc_'+document_sid).show();
                            $('#assign_generated_doc_'+document_sid).hide();
                        }
//                        var button = '<a onclick="func_remove_document('+"'"+type+"'"+', '+document_sid+','+title+','+message+')" class="btn btn-danger btn-block btn-sm">Revoke</a>';
//                        $('#assign-status-td-'+document_sid).html(button);
                    }
                });
            },
            function () {
                alertify.error('Cancelled!');
            });
    }

    function fLaunchModalGen(source, assign_type = 'assign') {
        var document_title = $(source).attr('data-title');
        var document_description = $(source).attr('data-description');
        var document_type = $(source).attr('data-document-type');
        var document_sid = $(source).attr('data-document-sid');
        title = 'Modify and Re-Assign This Document';
        document_label = "Are you sure you want to Re-Assign this document [<b>" + document_title + "</b>] <br> <?php echo ucwords($user_type);?> will be required to re-submit this document";
        button_title = 'Re-Assign this Document';

        if(assign_type == 'assign') {
            title = 'Modify and Assign Document';
            document_label = "Are you sure you want to assign this document: "+document_title;
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

    $(document).ready(function () {
        $('#credentials_joining_date').datepicker();
        $('#offer_letter_select').on('change', function () {
            var selected = $(this).val();
            var body = $('#letter_body_' + selected).val();
            CKEDITOR.instances['letter_body'].setData(body);
        });

        var welcome_inputbox_status = '<?php echo isset($source)? $source: "youtube"; ?>';
        
        if(welcome_inputbox_status == 'youtube' || welcome_inputbox_status == 'vimeo'){
            $('#welcome_yt_vm_video_container').show();
            $('#welcome_up_video_container').hide();
        } else if(welcome_inputbox_status == 'upload'){
            $('#welcome_yt_vm_video_container').hide();
            $('#welcome_up_video_container').show();
        }
    });

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

        my_request.done(function (response) {
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

        my_request.done(function (response) {
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
                default :
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
        $('#document_modal').on("shown.bs.modal", function () {
            if (iframe_url != '') {
                $('#preview_iframe').attr('src', iframe_url);
                //document.getElementById('preview_iframe').contentWindow.location.reload();
            }
        });
    }

    function show_office_details(location_title,location_address,location_telephone,location_fax) {
        var modal_content = '<div class="universal-form-style-v2"><ul><li class="form-col-100"><label>Title:</label><input type="text" class="invoice-fields" readonly value="'+location_title+'"></li><li class="form-col-100 autoheight"><label>Address:</label> <textarea class="invoice-fields autoheight" rows="10" cols="40" readonly>'+location_address+'</textarea></li><li class="form-col-100"><label>Phone:</label> <input type="text" class="invoice-fields" readonly value="'+location_telephone+'"></li><li class="form-col-100"><label>Fax:</label> <input type="text" class="invoice-fields" readonly value="'+location_fax+'"></li></ul></div>';
        var footer_content = '';


        $('#document_modal_body').html(modal_content);
        $('#document_modal_footer').html(footer_content);
        $('#document_modal_title').html('Office Address');
        $('#document_modal').modal("toggle");
        $('#document_modal').on("shown.bs.modal", function () {
        });
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

        my_request.done(function (response) {
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
        $('#document_modal').on("shown.bs.modal", function () {
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
        $('#document_modal').on("shown.bs.modal", function () {
            if (iframe_url != '') {
                $('#preview_iframe').attr('src', iframe_url);
            }
        });
    }

    $(document).on('click', '.select-package', function() {
        $('.select-package:not(:checked)').parent().removeClass("selected-package");
        $('.select-package:checked').parent().addClass("selected-package");
    });
    
    $(document).ready(function () {
        $('.select-package:checked').parent().addClass("selected-package");

<?php   if ($user_type == 'applicant') { ?>

            $('.js-finish-btn').click(function(){
                var user_exists = '<?php echo $user_exists ? 'true' : 'false'; ?>';
                if (user_exists == 'true') {
                    alertify.error('User with same email already exists in your company!');
                    return;
                }
                alertify.confirm(
                        'Are you sure?',
                        'Are you sure you want to finish setup and move the applicant to On-boarding?',
                        function () {
                            $('#form_onboarding_setup').submit();
                        },
                        function () {
                            alertify.error('Cancelled!');
                        }
                );
            });
            var btnFinish = $('<button></button>');
            btnFinish.text('Save')
            .addClass('btn btn-default finish-btn')
            .attr('type', 'button')
            .on('click', function () {
                var user_exists = '<?php echo $user_exists ? 'true' : 'false'; ?>';
                if (user_exists == 'false') {
                    alertify.confirm(
                            'Are you sure?',
                            'Are you sure you want to finish setup and move the applicant to On-boarding?',
                            function () {
                                $('#form_onboarding_setup').submit();
                            },
                            function () {
                                alertify.error('Cancelled!');
                            }
                    );
                } else {
                    alertify.error('User with same email already exists in your company!');
                }
            });
<?php   } else if ($user_type == 'employee') { ?> // Toolbar extra buttons
            var btnFinish = $('<button></button>');
            btnFinish.text('Save')
            .addClass('btn btn-default finish-btn')
            .attr('type', 'button')
            .on('click', function () {
                alertify.confirm(
                        'Are you sure?',
                        'Are you sure you want to finish employee panel setup?',
                        function () {
                            $('#form_onboarding_setup').submit();
                        },
                        function () {
                            alertify.error('Cancelled!');
                        }
                );
            });
<?php   } ?>
    
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
        
        $("#step_onboarding").on("showStep", function (e, anchorObject, stepNumber, stepDirection) {
            if (anchorObject.html() == 'Summary') {
                $('.sw-btn-next').removeClass('btn-success');
                $('.sw-btn-next').addClass('btn-default');
                $('.finish-btn').removeClass('btn-default');
                $('.finish-btn').addClass('btn-success');
            }
            if(anchorObject.html() == 'Offer Letter' || anchorObject.html() == 'Documents'){
                $('.finish-btn').hide();
            } else{
                $('.finish-btn').show();
            }
        });
        
        $("#step_onboarding").on("leaveStep", function (e, anchorObject, stepNumber, stepDirection) {
            if (anchorObject.html() == 'Summary') {
                $('.sw-btn-next').removeClass('btn-default');
                $('.sw-btn-next').addClass('btn-success');
                $('.finish-btn').removeClass('btn-success');
                $('.finish-btn').addClass('btn-default');
            }
        });

        var checked_ = $('input[type=checkbox]:checked');

        $(checked_).each(function () {
            var type = $(this).attr('data-type');
            var value = $(this).val();
            var checked = $(this).prop('checked')

            if (checked == true) {
                $('li#' + type + '_' + value).addClass('active');
            } else {
                $('li#' + type + '_' + value).removeClass('active');
            }
        });
        $('input[type=checkbox]').on('click', function () {
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
        $('.collapse').on('shown.bs.collapse', function () {
            $(this).parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
        }).on('hidden.bs.collapse', function () {
            $(this).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
        });
    });

    $('.welcome_video_source').on('click', function(){

        var selected = $(this).val();
        
        if(selected == 'youtube' || selected == 'vimeo'){
            $('#welcome_yt_vm_video_container').show();
            $('#welcome_up_video_container').hide();
        } else if(selected == 'upload'){
            $('#welcome_yt_vm_video_container').hide();
            $('#welcome_up_video_container').show();
        }
    });

    $('#assign_welcome_video').on('change',function(){
        var welcome_checkBox = document.getElementById("assign_welcome_video");
        var wv_sid = '<?php echo isset($welcome_video_sid)? $welcome_video_sid: "youtube"; ?>';
        
        if (welcome_checkBox.checked == false) {
            var myurl = "<?= base_url() ?>onboarding/updateWelcomeVideoStatus";
            $.ajax({
                type: 'POST',
                data:{
                    sid:wv_sid,
                    status:0
                },
                url: myurl,
                success: function(data){
                    alertify.success('Welcome Video Disabled Successfully');
                },
                error: function(){

                }
            });
        } else if (welcome_checkBox.checked == true) {
            var myurl = "<?= base_url() ?>onboarding/updateWelcomeVideoStatus";
            $.ajax({
                type: 'POST',
                data:{
                    sid:wv_sid,
                    status:1
                },
                url: myurl,
                success: function(data){
                    alertify.success('Welcome Video Enable Successfully');
                },
                error: function(){

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
                } else{
                    var file_size = Number(($("#" + val)[0].files[0].size/1024/1024).toFixed(2));
                    var video_size_limit = Number('<?php echo UPLOAD_VIDEO_SIZE; ?>');
                    if (video_size_limit < file_size) {
                        $("#" + val).val(null);
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
                            success: function (res) {
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

    function my_yt_vm_video_url_function () {
        var myurl = "<?= base_url() ?>onboarding/updateWelcomeVideoSource";
        var wv_sid = '<?php echo isset($welcome_video_sid)? $welcome_video_sid: "youtube"; ?>';

        if($('input[name="welcome_video_source"]:checked').val() == 'youtube') {
            if($('#yt_vm_video_url').val() != '') { 
                var p = /(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.)?youtube\.com\/watch(?:\.php)?\?.*v=)([a-zA-Z0-9\-_]+)/;
                
                if (!$('#yt_vm_video_url').val().match(p)) {
                    alertify.error('Not a Valid Youtube URL');
                    return false;
                } else {
                    var wv_yt_url = $('#yt_vm_video_url').val();
                    var source = 'youtube';
                    $.ajax({
                        type: 'POST',
                        data:{
                            welcome_sid:wv_sid,
                            welcome_url:wv_yt_url,
                            welcome_source:source
                        },
                        url: myurl,
                        success: function(data){
                            location.reload();
                            alertify.success('Welcome YouTube Video Update Successfully');
                        },
                        error: function(){

                        }
                    });
                }
            } else {
                alertify.error('Please Provide Valid Youtube URL ');
                return false;
            }
            
        } 

        if($('input[name="welcome_video_source"]:checked').val() == 'vimeo') {
            if($('#yt_vm_video_url').val() != '') {
                var validate_vimeo = "<?= base_url() ?>learning_center/validate_vimeo";
                $.ajax({
                    type: "POST",
                    url: validate_vimeo,
                    data: {url: $('#yt_vm_video_url').val()},
                    async : false,
                    success: function (data) {
                        if (data == false) {
                            alertify.error('Not a Valid Vimeo URL');
                            return false;
                        } else {
                            var wv_yt_url = $('#yt_vm_video_url').val();
                            var source = 'vimeo';
                            $.ajax({
                                type: 'POST',
                                data:{
                                    welcome_sid:wv_sid,
                                    welcome_url:wv_yt_url,
                                    welcome_source:source
                                },
                                url: myurl,
                                success: function(data){
                                    location.reload();
                                    alertify.success('Welcome Vimeo Video Update Successfully');
                                },
                                error: function(){

                                }
                            });
                        }
                    },
                    error: function (data) {
                    }
                });
            } else {
                alertify.error('Please Provide Valid Vimeo URL ');
                return false;
            }
        }
    }

    $('#send_an_email_to_applicant').on('click', function(){
        alertify.confirm(
                'Are you Sure?',
                'Are you sure you want to send an email to an applicant?',
                function () {
                    var send_email_url = "<?= base_url() ?>onboarding/sendEmailToApplicant";
                    var uq_sid = '<?php echo isset($unique_sid)? $unique_sid:""; ?>';
                    var u_sid = $('#user_sid').val();
                    var c_sid = $('#company_sid').val();
                    var c_name = $('#company_name').val();

                    $.ajax({
                        type: 'POST',
                        data:{
                            unique_sid:uq_sid,
                            user_sid: u_sid,
                            company_sid: c_sid,
                            company_name: c_name
                        },
                        url: send_email_url,
                        success: function(data){
                            alertify.success('Send an Email Successfully');
                        },
                        error: function(){

                        }
                    });
                },
                function () {
                    alertify.error('Cancelled!');
                }).set('labels', {ok: 'YES!', cancel: 'NO'});
    });

    $(document).on('click', '.change_custom_record_status', function() {
        var sid = $(this).val();
        
        if($(this).is(':checked')) {
            change_custom_status(sid,1);
            alertify.success('Custom location Enable');
        } else {
            change_custom_status(sid,0);
            alertify.error('Custom location Disable');
        }
    });

    function change_custom_status(sid, status){
        var myurl = "<?= base_url() ?>onboarding/change_custom_status";
        
        $.ajax({
            type: 'POST',
            data:{
                custom_record_sid:sid,
                custom_record_status:status
            },
            url: myurl,
            success: function(data){
                return true;
            },
            error: function(){

            }
        });
    }

    function fun_assign_welcome_video (video_sid){
        var url = "<?= base_url() ?>onboarding/assign_welcome_video_from_library";
        
        $.ajax({
            type: 'POST',
            data:{
               welcome_video_sid: video_sid,
               user_sid: '<?php echo $user_sid; ?>',
               usertype: '<?php echo $user_type; ?>',
               company_sid: '<?php echo $company_sid; ?>'
            },
            url: url,
            success: function(data){
                alertify.success('Welcome Video Update Successfully!');
            },
            error: function(){

            }
        });
    }

    $('#add_custom_welcome_video_submit').click(function () {

        var flag = 0;
        var welcome_video_title = $('#welcome_video_title').val();

        if (welcome_video_title == '') {
            alertify.error('Welcome video title is required');
            flag = 0;
            return false;
        }

        if($('input[name="welcome_video_source"]:checked').val() == 'youtube'){
            
            
            if($('#yt_vm_video_url').val() != '') { 

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
            
        } else if($('input[name="welcome_video_source"]:checked').val() == 'vimeo'){
            
            if($('#yt_vm_video_url').val() != '') {              
                var flag = 0;
                var myurl = "<?= base_url() ?>learning_center/validate_vimeo";
                $.ajax({
                    type: "POST",
                    url: myurl,
                    data: {url: $('#yt_vm_video_url').val()},
                    async : false,
                    success: function (data) {
                        if (data == false) {
                            alertify.error('Not a Valid Vimeo URL');
                            flag = 0;
                            return false;
                        } else {
                            flag = 1;
                        }
                    },
                    error: function (data) {
                    }
                });
            } else {
                flag = 0;
            }
        } else if ($('input[name="welcome_video_source"]:checked').val() == 'upload') {
            var file = welcome_video_check('welcome_video_upload');
            if (file == false){
                // alertify.error('Please upload welcome video');
                flag = 0;
                return false;    
            } else {
                flag = 1;
            }
        } else {
            flag = 0
        }

        if(flag == 1){
            $('#my_loader').show(); 
            $("#func_insert_welcome_video").submit(); 
        } else {
            alertify.error('Please provide welcome video data');
        }

        
      
    });
</script>