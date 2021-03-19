<?php $show_empty_box = true; ?>
<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">

                <?php if (!empty($sections) || !empty($ems_notification)) {
                    $show_empty_box = false; ?>
                <div class="widget-box">
                    <h3 class="text-blue">Your Activities</h3>
                    <ul class="activities-links">
                        <?php if (isset($ems_notification) && !empty($ems_notification)) { ?>
                        <li class="section_links" id="welcome_video_link">
                            <a href="javascript:func_show_section_ems('welcome_video');">Welcome Video</a>
                        </li>
                        <?php } ?>
                        <?php $ems_flag = 0;

                            if (isset($ems_notification) && !empty($ems_notification)) {
                                foreach ($ems_notification as $notification) { ?>
                        <li class="section_links" id="ems_<?php echo $ems_flag; ?>"><a
                                href="javascript:func_show_section_ems('<?php echo $ems_flag++; ?>');"><?php echo $notification['title']; ?></a>
                        </li>
                        <?php                   }
                            }

                            $section_flag = 0;

                            foreach ($sections as $section) { ?>
                        <!--                                <li class="section_links" id="link_--><?php //echo $section['section_unique_id']; 
                                                                                                            ?>
                        <!--" ><a href="javascript:func_show_section('--><?php //echo $section['section_unique_id']; 
                                                                                    ?>
                        <!--');">--><?php //echo $section['section_title']; 
                                            ?>
                        <!--</a></li>-->
                        <li class="section_links" id="link_<?php echo $section_flag; ?>"><a
                                href="javascript:func_show_section(<?php echo $section_flag++; ?>);"><?php echo $section['section_title']; ?></a>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
                <?php   } ?>

                <?php if (!empty($links) || !empty($custom_useful_link)) {
                    $show_empty_box = false; ?>
                <div class="widget-box">
                    <h3 class="text-blue">Helpful Links</h3>
                    <ul class="quick-links border-gray">

                        <?php foreach ($links as $link) { ?>
                            <?php if (!empty($link['link_url'])) { ?>
                                <li>
                                    <a target="_blank" href="<?php echo $link['link_url']; ?>"><?php echo $link['link_title']; ?></a>
                                    <p><?php echo $link['link_description']; ?></p>
                                </li>
                            <?php } else { ?>
                                <li>
                                    <a href="javascript:;"><?php echo $link['link_title']; ?></a>
                                    <p><?php echo $link['link_description']; ?></p>
                                </li>
                            <?php } ?>
                        <?php } ?>

                        <?php foreach ($custom_useful_link as $link) { ?>
                            <?php if (!empty($link['link_url'])) { ?>
                                <li>
                                    <a target="_blank" href="<?php echo $link['link_url']; ?>"><?php echo $link['link_title']; ?></a>
                                    <p><?php echo $link['link_description']; ?></p>
                                </li>
                            <?php } else { ?>
                                <li>
                                    <a href="javascript:;"><?php echo $link['link_title']; ?></a>
                                    <p><?php echo $link['link_description']; ?></p>
                                </li>
                            <?php } ?>
                        <?php } ?>

                    </ul>
                </div>
                <?php   } ?>

                <?php if (!empty($timings) || !empty($custom_office_timings)) {
                    $show_empty_box = false; ?>
                <div class="widget-box hidden">
                    <h3 class="text-blue">Office Hours</h3>
                    <ul class="quick-links border-gray">
                        <?php if (!empty($timings)) {
                                foreach ($timings as $time) { ?>
                        <li>
                            <strong><?php echo $time['title']; ?></strong>
                            <div><i class="fa fa-clock-o"></i>
                                <!-- <?//= reset_datetime(array('datetime' => $time['start_time'], '_this' => $this, 'format' => 'h:i a')) . ' - ' . reset_datetime(array('datetime' => $time['end_time'], '_this' => $this, 'format' => 'h:i a')); ?> -->
                                <?php echo date('h:i A', strtotime($time['start_time'])). ' - ' .date('h:i A', strtotime($time['end_time'])); ?>
                            </div>
                        </li>
                        <?php               }
                            } ?>

                        <?php if (!empty($custom_office_timings)) {
                                foreach ($custom_office_timings as $time) { ?>
                        <li>
                            <strong><?php echo $time['hour_title']; ?></strong>
                            <div><i class="fa fa-clock-o"></i>
                                <!-- <?//= reset_datetime(array('datetime' => $time['hour_start_time'], '_this' => $this, 'format' => 'h:i a')) . ' - ' . reset_datetime(array('datetime' => $time['hour_end_time'], '_this' => $this, 'format' => 'h:i a')); ?> -->
                                <?php echo date('h:i A', strtotime($time['hour_start_time'])). ' - ' .date('h:i A', strtotime($time['hour_end_time'])); ?>
                            </div>
                        </li>
                        <?php               }
                            } ?>
                    </ul>
                </div>
                <?php   } ?>

                <?php if (!empty($locations) || !empty($custom_office_locations)) {
                    $show_empty_box = false; ?>
                <div class="widget-box">
                    <h3 class="text-blue">Office Location(s)</h3>
                    <ul class="quick-links border-gray">
                        <?php if (isset($locations) && !empty($locations)) {
                                foreach ($locations as $location) { ?>
                        <strong
                            style="display: inline-block; width: 100%; margin: 8px 0 8px 0;"><?php echo $location['location_title']; ?></strong>
                        <li>
                            <table class="table table-condensed" style="margin-bottom: 0;">
                                <tr>
                                    <th><i class="fa fa-map"></i></th>
                                    <td><?php echo $location['location_address']; ?></td>
                                </tr>
                                <tr>
                                    <th><i class="fa fa-phone"></i></th>
                                    <td><?php echo $location['location_telephone']; ?></td>
                                </tr>
                                <tr>
                                    <th><i class="fa fa-fax"></i></th>
                                    <td><?php echo $location['location_fax']; ?></td>
                                </tr>
                            </table>
                        </li>
                        <?php               }
                            } ?>

                        <?php if (isset($custom_office_locations) && !empty($custom_office_locations)) {
                                foreach ($custom_office_locations as $location) { ?>
                        <strong
                            style="display: inline-block; width: 100%; margin: 8px 0 8px 0;"><?php echo $location['location_title']; ?></strong>
                        <li>
                            <table class="table table-condensed" style="margin-bottom: 0;">
                                <tr>
                                    <th><i class="fa fa-map"></i></th>
                                    <td><?php echo $location['location_address']; ?></td>
                                </tr>
                                <tr>
                                    <th><i class="fa fa-phone"></i></th>
                                    <td><?php echo $location['location_phone']; ?></td>
                                </tr>
                                <tr>
                                    <th><i class="fa fa-fax"></i></th>
                                    <td><?php echo $location['location_fax']; ?></td>
                                </tr>
                            </table>
                        </li>
                        <?php               }
                            } ?>
                    </ul>
                </div>
                <?php   } ?>

                <?php $incident = $this->session->userdata('incident_config');
                if ($incident > 0) { ?>

                <?php if ($show_empty_box) { ?>
                <div class="widget-box">
                    <div class="link-box full-width" style="background-color: #f5f5f5;">&nbsp;</div>
                </div>
                <?php       } ?>
                <?php if (!empty($people)) { ?>
                <div class="colleague-pics full-width bg-white">
                    <h3 class="bg-blue">Your Colleagues</h3>
                    <ul class="colleague-list">
                        <?php foreach ($people as $person) { ?>
                        <?php if ($person['employee_info']['sid'] != $employee['sid']) { ?>
                        <li>
                            <a
                                href="<?php echo base_url('dashboard/colleague_profile') . '/' . $person['employee_info']['sid']; ?>">
                                <?php if (isset($person['employee_info']['profile_picture']) && !empty($person['employee_info']['profile_picture'])) { ?>
                                <img class="img-responsive"
                                    src="<?php echo AWS_S3_BUCKET_URL . $person['employee_info']['profile_picture']; ?>">
                                <?php } else { ?>
                                <img class="img-responsive"
                                    src="<?php echo base_url(); ?>assets/images/default_pic.jpg">
                                <?php } ?>
                            </a>
                        </li>
                        <?php } ?>
                        <?php } ?>
                    </ul>
                </div>
                <?php } ?>
                <?php if (!empty($items)) { ?>
                <div class="widget-box">
                    <h3 class="text-blue">Items To Bring</h3>
                    <ul class="quick-links border-gray">
                        <?php foreach ($items as $item) { ?>
                        <li>
                            <strong><?php echo $item['item_title']; ?></strong>
                            <div><?php echo $item['item_description']; ?></div>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
                <?php } ?>

                <?php       } ?>

            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                <?php if (!empty($welcome_video) && isset($welcome_video)) { ?>
                <div id="welcome_video" class="section welcone-video-box full-width">
                    <?php if ($welcome_video['video_source'] == 'youtube') { ?>
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item"
                            src="//www.youtube.com/embed/<?php echo $welcome_video['video_url']; ?>"></iframe>
                    </div>
                    <?php } else if ($welcome_video['video_source'] == 'vimeo') { ?>
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe src="https://player.vimeo.com/video/<?php echo $welcome_video['video_url']; ?>"
                            width="640" height="480" frameborder="0" webkitallowfullscreen mozallowfullscreen
                            allowfullscreen></iframe>
                    </div>
                    <?php } else { ?>
                    <div class="embed-responsive embed-responsive-16by9">
                        <video controls>
                            <source
                                src="<?php echo base_url('assets/uploaded_videos/' . $welcome_video['video_url']); ?>"
                                type='video/mp4'>
                        </video>
                    </div>
                    <?php } ?>
                </div>
                <?php } ?>
                <?php if (!empty($ems_notification) && isset($ems_notification)) {
                    $ems_flag = 0; ?>
                <?php foreach ($ems_notification as $section) { ?>
                <!-- <div id="--><?php //echo $section['section_unique_id']; 
                                            ?>
                <!--" class="section welcone-video-box full-width">-->
                <div id="ems_flag<?php echo $ems_flag++; ?>" class="section welcone-video-box full-width">
                    <h1 class="text-blue"><?php echo $section['title']; ?></h1>
                    <?php if ($section['video_status'] && !empty($section['video_url'])) { ?>
                    <?php if ($section['video_source'] == 'youtube') { ?>
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item"
                            src="//www.youtube.com/embed/<?php echo $section['video_url']; ?>"></iframe>
                    </div>
                    <?php } else if ($section['video_source'] == 'vimeo') { ?>
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe src="https://player.vimeo.com/video/<?php echo $section['video_url']; ?>" width="640"
                            height="480" frameborder="0" webkitallowfullscreen mozallowfullscreen
                            allowfullscreen></iframe>
                    </div>
                    <?php } else { ?>
                    <video controls>
                        <source src="<?php echo base_url('assets/uploaded_videos/' . $section['video_url']); ?>"
                            type='video/mp4'>
                    </video>
                    <?php } ?>
                    <?php } else if ($section['image_status'] && !empty($section['image_code'])) { ?>
                    <div class="img-thumbnail">
                        <img src="<?php echo AWS_S3_BUCKET_URL . $section['image_code']; ?>" class="img-responsive" />
                    </div>
                    <?php } ?>
                    <?php if (!empty($section['description'])) { ?>
                    <div class="welcome-text text-justify">
                        <?php echo html_entity_decode($section['description']); ?>
                    </div>
                    <?php } ?>
                </div>
                <?php } ?>
                <?php }
                if (!empty($sections) && isset($sections)) {
                    $section_flag = 0; ?>
                <?php foreach ($sections as $section) { ?>
                <!-- <div id="--><?php //echo $section['section_unique_id']; 
                                            ?>
                <!--" class="section welcone-video-box full-width">-->
                <div id="<?php echo $section_flag++; ?>" class="section welcone-video-box full-width">
                    <h1 class="text-blue"><?php echo $section['section_title']; ?></h1>
                    <?php if (!empty($section['section_video'])) { ?>
                    <?php if ($section['section_video_source'] == 'youtube') { ?>
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item"
                            src="//www.youtube.com/embed/<?php echo $section['section_video']; ?>"></iframe>
                    </div>
                    <?php } else { ?>
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe src="https://player.vimeo.com/video/<?php echo $section['section_video']; ?>"
                            width="640" height="480" frameborder="0" webkitallowfullscreen mozallowfullscreen
                            allowfullscreen></iframe>
                    </div>
                    <?php } ?>
                    <?php } else if (!empty($section['section_image'])) { ?>
                    <div class="img-thumbnail">
                        <img src="<?php echo AWS_S3_BUCKET_URL . $section['section_image']; ?>"
                            class="img-responsive" />
                    </div>
                    <?php } ?>
                    <?php if (!empty($section['section_content'])) { ?>
                    <div class="welcome-text text-justify">
                        <?php echo html_entity_decode($section['section_content']); ?>
                    </div>
                    <?php } ?>
                </div>
                <?php } ?>
                <?php } else {
                    if (empty($ems_notification) || !isset($ems_notification)) { ?>
                <div class="text-center">
                    <div class="text-center">
                        <img style="display: inline-block; width: 70%; height: auto; opacity: 0.60;"
                            src="<?php echo base_url('assets/images/onboarding.png') ?>" />
                    </div>
                </div>
                <?php }
                } ?>
                <div class="full-width margin-top-20">

                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                            <div class="widget-box">
                                <a href="<?php echo base_url('list_announcements'); ?>">
                                    <div class="link-box bg-pink full-width">
                                        <h2>Announcements</h2>
                                        <div><span>&nbsp;</span></div>
                                        <div class="current-date">
                                            <span><?php echo $has_announcements; ?><sub>Pending</sub></span>
                                        </div>
                                        <!--                                            <ul class="cs-jam-ul">-->
                                        <!--                                                <li>Announcements: -->
                                        <?//= $has_announcements;?>
                                        <!-- Pending </li>-->
                                        <!--                                            </ul>-->
                                        <div class="status-panel">
                                            <h3>View Announcements</h3>
                                            <span>Company Announcements</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                            <div class="widget-box">
                                <a href="<?php echo base_url('incident_reporting_system'); ?>">
                                    <div class="link-box bg-cyan full-width">
                                        <h2>Incidents</h2>
                                        <!--                                            <ul class="cs-jam-ul" style="position: relative; z-index: 99;">-->
                                        <!--                                                <li>Assigned Incidents: -->
                                        <?//= $incidents_count;?>
                                        <!-- Pending </li>-->
                                        <!--                                            </ul>-->
                                        <div class="text-center" style="position: relative; z-index: 99;">
                                            <span style="color: white"> Assigned Incidents</span>
                                        </div>
                                        <div class="current-date" style="position: relative; z-index: 99;">
                                            <span><?php echo $incidents_count; ?><sub>Pending</sub></span>
                                        </div>
                                        <div class="status-panel">
                                            <h3>Incident Reporting</h3>
                                            <span>Report an Incident</span>
                                            <?php //echo $complete_steps['documents'] > 0 ? '<span>completed</span>' : '<span>skipped</span>'
                                            ?>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                            <div class="widget-box">
                                <a href="<?php echo base_url('my_learning_center'); ?>">
                                    <div class="link-box bg-redish full-width">
                                        <h2>My Learning Center</h2>
                                        <div class="current-date">
                                            <span><?php echo $training_session_count; ?><sub>Pending</sub></span>
                                        </div>
                                        <!--                                            <ul class="cs-jam-ul">-->
                                        <!--                                                <li>Training Sessions: -->
                                        <?//= $training_session_count;?>
                                        <!-- Pending </li>-->
                                        <!--                                            </ul>-->
                                        <div class="status-panel">
                                            <h3>Training Sessions and Online Videos</h3>
                                            <!--                                                <span>Assigned to You</span>-->
                                            <?php //echo $complete_steps['documents'] > 0 ? '<span>completed</span>' : '<span>skipped</span>'
                                            ?>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                            <div class="widget-box">
                                <a href="<?php echo base_url('hr_documents_management/my_documents'); ?>">
                                    <div class="link-box bg-redish full-width">
                                        <h2>Documents</h2>
                                        <div><span>&nbsp;</span></div>
                                        <div class="current-date">
                                            <span><?php echo $documents_count; ?><sub>Pending</sub></span>
                                        </div>
                                        <!--                                            <ul class="cs-jam-ul">-->
                                        <!--                                                <li>Documents: -->
                                        <?//= $documents_count;?>
                                        <!-- Pending</li>-->
                                        <!--                                            </ul>-->
                                        <div class="status-panel">
                                            <h3>Company Documents</h3>
                                            <span>Assigned to You</span>
                                            <?php //echo $complete_steps['documents'] > 0 ? '<span>completed</span>' : '<span>skipped</span>'
                                            ?>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                            <div class="widget-box">
                                <a href="<?php echo base_url('private_messages'); ?>">
                                    <div class="link-box bg-blue full-width">
                                        <h2>Private Messages</h2>
                                        <div><span>&nbsp;</span></div>
                                        <div class="current-date">
                                            <span><?php echo $messages; ?><sub>Pending</sub></span>
                                        </div>
                                        <!--                                            <ul class="cs-jam-ul">-->
                                        <!--                                                <li>Messages: -->
                                        <?//= $messages;?>
                                        <!-- Pending</li>-->
                                        <!--                                            </ul>-->
                                        <div class="status-panel">
                                            <h3>View Messages</h3>
                                            <span>Send & Receive Messages</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                            <div class="widget-box">
                                <a href="<?php echo base_url('my_events'); ?>">
                                    <div class="link-box bg-orange full-width">
                                        <h2>Calendar</h2>
                                        <div class="text-center">
                                            <span style="color: white">Today Date:
                                                <?php echo date('d'); ?><sub><?php echo strtolower(date('D')); ?></span>
                                        </div>
                                        <div class="current-date">
                                            <span><?php echo $eventCountToday; ?><sub>Pending</sub></span>
                                        </div>
                                        <!--                                            <div class="current-date">-->
                                        <!--                                                <span>--><?php //echo date('d'); 
                                                                                                        ?>
                                        <!--<sub>--><?php //echo strtolower(date('D')); 
                                                    ?>
                                        <!--</sub></span>-->
                                        <!--                                            </div>-->
                                        <div class="status-panel">
                                            <h3>View Appointments</h3>
                                            <span>View Schedules</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <?php //           $extra_info = $this->session->userdata('logged_in')['company_detail']['extra_info'];
                        //                            $safety_check = 1;
                        //                            if(!is_null($extra_info)) {
                        //                                $extra_info = unserialize($extra_info);
                        //                                $safety_check = $extra_info['safety_sheet'];
                        //                            }
                        //                            if($safety_check == 1 && $safety_sheet_flag > 0) {
                        if ($safety_sheet_flag > 0) { ?>
                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                            <div class="widget-box">
                                <a href="<?php echo base_url('safety_sheets'); ?>">
                                    <div class="link-box bg-incident full-width">
                                        <h2>Safety Sheets</h2>
                                        <div class="status-panel">
                                            <h3>Safety Sheets</h3>
                                            <span>Safety Sheets</span>
                                            <?php //echo $complete_steps['documents'] > 0 ? '<span>completed</span>' : '<span>skipped</span>'
                                                ?>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <?php } ?>
                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                            <div class="widget-box">
                                <a href="<?php echo base_url('e_signature'); ?>">
                                    <div class="link-box bg-green full-width">
                                        <h2>E-Signature</h2>
                                        <div class="status-panel">
                                            <h3>Status</h3>
                                            <?php echo $complete_steps['e_signature'] > 0 ? '<span>completed</span>' : '<span>Pending</span>' ?>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                            <div class="widget-box">
                                <a href="<?php echo base_url('general_info'); ?>">
                                    <div class="link-box bg-info full-width">
                                        <h2>General Information</h2>
                                        <div class="status-panel">
                                            <h3>General Information</h3>
                                            <span>General Information</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <?php if ($has_approval_access) { ?>
                        <!-- Approval -->
                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                            <div class="widget-box">
                                <a href="<?php echo base_url('job_approval_management'); ?>">
                                    <div class="link-box bg-pink full-width">
                                        <h2>Job Approval Management</h2>
                                        <ul class="cs-jam-ul">
                                            <li>Pending : <?= $all_unapproved_jobs_count; ?></li>
                                            <li>Approved : <?= $all_approved_jobs_count; ?></li>
                                            <li>Declined : <?= $all_rejected_jobs_count; ?></li>
                                        </ul>
                                        <div class="status-panel">
                                            <h3>Manage</h3>
                                            <span>JAM</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <?php } ?>
                        <?php $comply_status = $session["company_detail"]["complynet_status"];
                        $employee_status = $session["employer_detail"]["complynet_status"];
                        $complynet_dashboard_link = $session["company_detail"]["complynet_dashboard_link"];
                        $access_level  = $session["employer_detail"]['access_level'];
                        ?>
                        <?php if (check_access_permissions_for_view($security_details, 'complynet') && $comply_status && $employee_status) { ?>
                        <!-- Approval -->
                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                            <div class="widget-box">
                                <span id="main">
                                    <div class="link-box bg-complynet full-width">
                                        <h2>Compliance <br /> Management System</h2>
                                        <ul class="cs-jam-ul" style="position: relative; z-index:1;">
                                            <?php if(!empty($complynet_dashboard_link) && $complynet_dashboard_link != NULL && $access_level != 'Employee'){?>
                                            <li><button id="js-dashboard"
                                                    data-href="<?=base_url('complynet/dashboard');?>"
                                                    class="btn btn-link"
                                                    style="color: #ffffff; padding: 3px;">Dashboard</button></li>
                                            <?php }?>
                                            <li><button id="js-login" data-href="<?=base_url('complynet/login');?>"
                                                    class="btn btn-link"
                                                    style="color: #ffffff; padding: 3px;">Login</button></li>
                                        </ul>
                                        <div class="status-panel">
                                            <h3>Complynet</h3>
                                            <span>Show</span>
                                        </div>
                                    </div>
                                </span>
                            </div>
                        </div>
                        <?php } ?>

                        <?php if (checkIfAppIsEnabled('timeoff')) { ?>
                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6" id="js-to-box">
                            <a href="<?=base_url('timeoff/lms');?>">
                                <div class="widget-box">
                                    <div class="link-box bg-timeoff full-width">
                                        <h2 class="text-blue">Time Off</h2>
                                        <ul class="pto-box">
                                            <li>
                                                <span id="jsRemainingTime">0 hour(s)</span>
                                                <span>remaining</span>
                                                <!-- <i class="cs-jam-ul fa fa-question-circle question-custom"
                                                    data-html="true" data-toggle="popover" data-placement="top"
                                                    data-trigger="hover" data-content="Balance remaining">
                                                </i> -->
                                            </li>
                                            <li>
                                                <span id="jsConsumedTime">0 hour(s)</span>
                                                <span>consumed</span>
                                                <!-- <i class="cs-jam-ul fa fa-question-circle question-custom"
                                                    data-html="true" data-toggle="popover" data-placement="top"
                                                    data-trigger="hover" data-content="Balance consumed">
                                                </i> -->
                                            </li>
                                            <li>
                                                <span id="jsTotalTimeoffs">0 Time-offs approved</span>
                                                <!-- <i class="cs-jam-ul fa fa-question-circle question-custom"
                                                    data-html="true" data-toggle="popover" data-placement="top"
                                                    data-trigger="hover" data-content="# of Time-offs approved">
                                                </i> -->
                                            </li>
                                            <li>
                                            <br />
                                                <a href="javascript:void(0)" data-id="<?=$employee_sid;?>" class="btn btn-black jsBreakdownRequest">View Details</a>
                                            </li>
                                        </ul>

                                        <div style="
                                            position: absolute;
                                            bottom: 0;
                                            left: 0;
                                            background: rgba(0,0,0,.2);
                                            width: 100%;
                                            height: 50px;
                                            z-index: 1;
                                            padding: 10px 5px;
                                        ">
                                            <a href="#" data-id="<?=$employee_sid;?>"
                                                class="btn btn-success form-control jsCreateRequest"
                                                style="margin-right: 5px;">Create A Time-off Request</a>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php } ?>

                        <?php if (checkIfAppIsEnabled('performance_review')) { ?>
                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6" id="js-to-box">
                            <a href="<?= base_url('performance-management/lms/reviews'); ?>">
                                <div class="widget-box">
                                    <div class="link-box  bg-pr full-width">
                                        <h2 class="text-blue">Performance Management</h2>
                                        <div><span>&nbsp;</span></div>
                                        <div class="current-date">
                                            <span><?php echo $performanceReviewPending; ?><sub>Pending</sub></span>
                                        </div>
                                        <div class="status-panel">
                                            <h3>Show</h3>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php } ?>

                        <?php if (checkIfAppIsEnabled('performance_review')) { ?>
                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6" id="js-to-box">
                            <a href="<?= base_url('performance-management/lms/goals'); ?>">
                                <div class="widget-box">
                                    <div class="link-box bg-blue full-width">
                                        <h2 class="text-blue">Goals</h2>
                                        <div><span>&nbsp;</span></div>
                                        <div class="current-date">
                                            <span><?php echo $goals; ?></span>
                                        </div>
                                        <div class="status-panel">
                                            <h3>Show</h3>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    <?php if (isset($ems_notification) && sizeof($ems_notification) > 0) { ?>
    <?php if (isset($welcome_video) && sizeof($welcome_video) > 0) { ?>
    func_show_section_ems('welcome_video');
    <?php } else { ?>
    func_show_section_ems(0);
    <?php } ?>

    <?php } else { ?>
    <?php if (isset($welcome_video) && sizeof($welcome_video) > 0) { ?>
    func_show_section_ems('welcome_video');
    <?php } else { ?>
    func_show_section(0);
    <?php } ?>

    <?php } ?>
});

function func_show_section(section_id) {
    $('.section').slideUp();
    $('#' + section_id).slideDown();
    $('.section_links').removeClass('active');
    $('#link_' + section_id).addClass('active');
}

function func_show_section_ems(section_id) {
    if (section_id == 'welcome_video') {
        $('.section').slideUp();
        $('#welcome_video').slideDown();
        $('.section_links').removeClass('active');
        $('#welcome_video_link').addClass('active');
    } else {
        $('.section').slideUp();
        $('#ems_flag' + section_id).slideDown();
        $('.section_links').removeClass('active');
        $('#ems_' + section_id).addClass('active');
    }

}
</script>

<script>
$(function() {
    $('#main').mousedown(function(e) {
        switch (e.which) {
            case 1:
                e.stopPropagation();
                e.preventDefault();
                if (e.target.id == 'js-dashboard' || e.target.id == 'js-login') window.location.href = e
                    .target.dataset.href;
                else window.location = 'complynet';
                break;
            case 2:
                event.stopPropagation();
                event.preventDefault();
                if (e.target.id == 'js-dashboard' || e.target.id == 'js-login') window.open(e.target
                    .dataset.href, '_blank');
                else window.open('complynet', '_blank');
                break;
            case 3:
                //                event.stopPropagation();
                //                event.preventDefault();
                //                if(e.target.id == 'js-dashboard' || e.target.id == 'js-login') window.open(e.target.dataset.href, '_blank');
                //                else window.open('complynet', '_blank');
                //                break;
        }
        return true;
    });
});
</script>


<style>
ul.cs-jam-ul {
    margin-left: 10px;
    list-style: none;
}

ul.cs-jam-ul li {
    color: #ffffff;
}
</style>

<script>
$('[data-toggle="popover"]').popover();
</script>

<style>
ul.cs-jam-ul {
    margin-left: 10px;
    list-style: none;
}

ul.cs-jam-ul li {
    color: #FFFFFF;
}

.link-box .pto-box {
    position: relative;
    z-index: 2;
    padding: 0px 0px 0px 10px;
}

.link-box .pto-box li {
    list-style: none;
    font-size: 24px;
    color: #fff;
    font-weight: 600;
}

.link-box .pto-box li span {
    font-size: 13px;
    color: #fff;
}

.link-box .pto-box .question-custom {
    font-size: 13px;
    color: #fff;
}

.create-pto-margin {
    clear: both;
    padding-top: 30px;
}

.pto-box {
    line-height: 26px;
}

.pto-box .popover-content {
    float: left;
    width: 100%;
    color: #000000;
}

.pto-box .popover-content ul li {
    text-align: right;
    list-style: none;
    color: #999;
    font-size: 13px;
    font-weight: 400;
}

.pto-box .popover {
    max-width: inherit !important;
}

.pto-box .popover-content ul li span {
    display: inline-block;
    margin-right: 15px;
    color: #818181;
    font-weight: 600;
    float: left;
}

.info-pto-custom {
    font-size: 30px;
    display: inline-block;
    margin-bottom: 15px;
}

.info-pto-custom li {
    line-height: 30px !important;
}

.info-pto-custom span {
    font-size: 13px;
}

.info-pto-custom i {
    font-size: 13px;
}

.matrics-content .circle-text {}

.matrics-content ul {
    text-decoration: none;
    margin-bottom: 0px !important;
}

.matrics-content ul li {
    list-style: none;
}

.matrics-content ul li h2 {
    display: inline-block;
    font-weight: 700;
    color: #818181;
    font-size: 24px;
    float: left;
    clear: both;
    color: #fff;
}

.matrics-content ul li p {
    font-size: 12px;
    color: #818181;
}

.box {
    display: inline-block;
    position: relative;
}

.box:hover .tooltip--multiline {
    display: block;
}

.tooltip--multiline .left-tooltip-content {
    width: 49%;
    float: left;
}

.tooltip--multiline .right-tooltip-content {
    width: 49%;
    float: right;
}

.clearfix-tooltip {
    display: block;
    clear: both;
}

.tooltip--multiline {
    background: #000;
    color: #fff;
    display: none;
    padding: 10px;
    position: absolute;
    bottom: 75%;
    width: 225px;
    left: 50%;
    -webkit-transform: translate(-50%, 0%);
    -moz-transform: translate(-50%, 0%);
    -ms-transform: translate(-50%, 0%);
    -o-transform: translate(-50%, 0%);
    transform: translate(-50%, 0%);
    border-radius: 7px;
    background-color: white;
    color: #000;
    border: 1px solid #999999;
    z-index: 100;
}

.tooltip--multiline:before {
    border-top: 10px solid #999999;
    border-left: 10px solid transparent;
    border-right: 10px solid transparent;
    content: '';
    position: absolute;
    top: 100%;
    left: 50%;
    -webkit-transform: translate(-50%, 0%);
    -moz-transform: translate(-50%, 0%);
    -ms-transform: translate(-50%, 0%);
    -o-transform: translate(-50%, 0%);
    transform: translate(-50%, 0%);
    border-radius: 0px;
}

.tooltip--multiline:after {
    border-top: 9px solid #fff;
    border-left: 9px solid transparent;
    border-right: 9px solid transparent;
    content: '';
    position: absolute;
    top: 100%;
    left: 50%;
    -webkit-transform: translate(-50%, 0%);
    -moz-transform: translate(-50%, 0%);
    -ms-transform: translate(-50%, 0%);
    -o-transform: translate(-50%, 0%);
    transform: translate(-50%, 0%);
    border-radius: 0px;
}

.left-tooltip-content {
    color: #818181;
}

.right-tooltip-content {
    color: #C6C6C6;
}
</style>