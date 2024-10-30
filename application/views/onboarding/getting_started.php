<style>
    .bgred {
        background-color: #FF0000;
    }

    .bgred .status-panel {
        background: rgba(125, 4, 4, 0.6);
    }

    .bgcyan {
        background-color: #00FFFF;
    }

    .bgcyan .status-panel {
        background: rgba(3, 83, 64, 0.6);
    }

    .bgblue {
        background-color: #0000FF;
    }

    .bgblue .status-panel {
        background: rgba(3, 83, 64, 0.6);
    }

    .bgdarkblue {
        background-color: #00008B;
    }

    .bgdarkblue .status-panel {
        background: rgba(0, 1, 6, 0.6);
    }

    .bglightblue {
        background-color: #ADD8E6;
    }

    .bglightblue .status-panel {
        background: rgba(3, 83, 64, 0.6);
    }

    .bgpurple {
        background-color: #800080;
    }

    .bgpurple .status-panel {
        background: rgba(35, 2, 38, 0.6);
    }

    .bgyellow {
        background-color: #FFFF00;
    }

    .bgyellow .status-panel {
        background: rgba(166, 141, 6, 0.6);
    }

    .bglime {
        background-color: #00FF00;
    }

    .bglime .status-panel {
        background: rgba(166, 141, 6, 0.6);
    }

    .bgpink {
        background-color: #FFC0CB;
    }

    .bgpink .status-panel {
        background: rgba(215, 68, 178, 0.6);
    }

    .popover-title,
    .popover-content {
        color: #000 !important;
    }
</style>
<?php
$show_empty_box = true;
$document_d_base = base_url('hr_documents_management/sign_hr_document/d');
?>
<div class="main jsmaincontent">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">

                <?php if (!empty($sections) || !empty($ems_notification)) {
                    $show_empty_box = false; ?>
                    <div class="widget-box">
                        <h3 class="text-blue">Your Activities</h3>
                        <ul class="activities-links">
                            <?php $ems_flag = 0;

                            if (isset($ems_notification) && !empty($ems_notification)) {
                                foreach ($ems_notification as $key =>  $notification) { ?>
                                    <li class="section_links" id="ems_<?php echo $ems_flag; ?>"><a href="javascript:func_show_section_ems('<?php echo $ems_flag++; ?>');"><?php echo $notification['title']; ?></a>
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
                                <li class="section_links" id="link_<?php echo $section_flag; ?>"><a href="javascript:func_show_section(<?php echo $section_flag++; ?>);"><?php echo $section['section_title']; ?></a>
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
                                            <!-- <? //= reset_datetime(array('datetime' => $time['start_time'], '_this' => $this, 'format' => 'h:i a')) . ' - ' . reset_datetime(array('datetime' => $time['end_time'], '_this' => $this, 'format' => 'h:i a')); 
                                                    ?> -->
                                            <?php echo date('h:i A', strtotime($time['start_time'])) . ' - ' . date('h:i A', strtotime($time['end_time'])); ?>
                                        </div>
                                    </li>
                            <?php               }
                            } ?>

                            <?php if (!empty($custom_office_timings)) {
                                foreach ($custom_office_timings as $time) { ?>
                                    <li>
                                        <strong><?php echo $time['hour_title']; ?></strong>
                                        <div><i class="fa fa-clock-o"></i>
                                            <!-- <? //= reset_datetime(array('datetime' => $time['hour_start_time'], '_this' => $this, 'format' => 'h:i a')) . ' - ' . reset_datetime(array('datetime' => $time['hour_end_time'], '_this' => $this, 'format' => 'h:i a')); 
                                                    ?> -->
                                            <?php echo date('h:i A', strtotime($time['hour_start_time'])) . ' - ' . date('h:i A', strtotime($time['hour_end_time'])); ?>
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
                                    <strong style="display: inline-block; width: 100%; margin: 8px 0 8px 0;"><?php echo $location['location_title']; ?></strong>
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
                                    <strong style="display: inline-block; width: 100%; margin: 8px 0 8px 0;"><?php echo $location['location_title']; ?></strong>
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
                                            <a href="<?php echo base_url('dashboard/colleague_profile') . '/' . $person['employee_info']['sid']; ?>">
                                                <?php if (isset($person['employee_info']['profile_picture']) && !empty($person['employee_info']['profile_picture'])) { ?>
                                                    <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $person['employee_info']['profile_picture']; ?>">
                                                <?php } else { ?>
                                                    <img class="img-responsive" src="<?php echo base_url(); ?>assets/images/default_pic.jpg">
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


                <?php $getCompanyHelpboxInfo = get_company_helpbox_info($company_sid);
                if ($getCompanyHelpboxInfo[0]['box_status'] == 1) { ?>
                    <!-- Company help box -->
                    <div class="widget-box">
                        <div style='border: 1px solid #d2d2d2; padding-left :15px;padding-right :15px;padding-top :5px;padding-bottom :5px;'>
                            <div class="admin-info">
                                <h4 class="text-blue" style="border-bottom: 1px solid #c4c4c4; margin: 0 0 15px 0; padding: 0 0 10px 0;"><?php echo $getCompanyHelpboxInfo[0]['box_title']; ?></h4>
                                <div class="profile-pic-area">
                                    <div class="form-col-100">
                                        <span class="admin-contact-info">
                                            <label>Support</label><br>
                                            <?php if ($getCompanyHelpboxInfo[0]['box_support_phone_number']) { ?>
                                                <span><i class="fa fa-phone"></i> <?php echo $getCompanyHelpboxInfo[0]['box_support_phone_number']; ?></span><br>
                                            <?php } ?>
                                            <span>
                                                <button class="btn btn-orange jsCompanyHelpBoxBtn">
                                                    <i class="fa fa-envelope-o" aria-hidden="true"></i>&nbsp;<?= $getCompanyHelpboxInfo[0]['button_text']; ?>
                                                </button>
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php $this->load->view('company_help_box_script'); ?>
                <?php } ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
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
                                        <iframe class="embed-responsive-item" src="//www.youtube.com/embed/<?php echo $section['video_url']; ?>"></iframe>
                                    </div>
                                <?php } else if ($section['video_source'] == 'vimeo') { ?>
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe src="https://player.vimeo.com/video/<?php echo $section['video_url']; ?>" width="640" height="480" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                    </div>
                                <?php } else { ?>
                                    <video controls>
                                        <source src="<?php echo base_url('assets/uploaded_videos/' . $section['video_url']); ?>" type='video/mp4'>
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
                                        <iframe class="embed-responsive-item" src="//www.youtube.com/embed/<?php echo $section['section_video']; ?>"></iframe>
                                    </div>
                                <?php } else { ?>
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe src="https://player.vimeo.com/video/<?php echo $section['section_video']; ?>" width="640" height="480" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                    </div>
                                <?php } ?>
                            <?php } else if (!empty($section['section_image'])) { ?>
                                <div class="img-thumbnail">
                                    <img src="<?php echo AWS_S3_BUCKET_URL . $section['section_image']; ?>" class="img-responsive" />
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
                                <img style="display: inline-block; width: 70%; height: auto; opacity: 0.60;" src="<?php echo base_url('assets/images/onboarding.png') ?>" />
                            </div>
                        </div>
                <?php }
                } ?>


                <?php
                if ($employee_handbook_enable) { ?>
                    <!--  -->
                    <div class="row">
                        <br>
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <button class="btn btn-orange btn-lg csF26" data-toggle="collapse" href="#jsEmployeeHandbookArea" role="button" aria-expanded="false" aria-controls="jsEmployeeHandbookArea">Employee Handbook & Policies</button>
                        </div>
                    </div>
                    <div class="row collapse" id="jsEmployeeHandbookArea">
                        <br />
                        <div class="col-sm-12">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <table class="table table-plane cs-w4-table">
                                        <?php if (!empty($handbook_documents['original'])) { ?>
                                            <thead>
                                                <tr>
                                                    <th class="col-lg-8 hidden-xs">Document Name</th>
                                                    <th class="col-xs-12 hidden-md hidden-lg hidden-sm">Document</th>
                                                    <th class="col-lg-4 col-xs-12 hidden-xs text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($handbook_documents['assigned'] as $key => $document) { ?>
                                                    <?php if ($document['archive'] != 1) { ?>
                                                        <?php $pdBtn = getPDBTN($document, 'btn-info');  ?>
                                                        <tr>
                                                            <td class="">
                                                                <?php
                                                                echo $document['document_title'] . ($document['is_required'] == 1 ? ' <i class="fa fa-asterisk jsTooltip" style="color: #cc1100;" aria-hidden="true" title="' . ($requiredMessage) . '"></i>' : '') . '';
                                                                echo $document['status'] ? '' : '<b>(revoked)</b>';
                                                                echo $document['document_sid'] == 0 ? '<b> (Manual Upload)</b>' : '';
                                                                echo $document['document_type'] == 'offer_letter' ? '<b> (Offer Letter)</b>' : '';
                                                                if (isset($document['assigned_date']) && $document['assigned_date'] != '0000-00-00 00:00:00') {
                                                                    echo "<br><b>Assigned On: </b>" . reset_datetime(array('datetime' => $document['assigned_date'], '_this' => $this));
                                                                }
                                                                ?>
                                                                <div class="hidden-lg hidden-md hidden-sm">
                                                                    <?= $pdBtn['pm'] . $pdBtn['dm']; ?>
                                                                    <a href="<?php echo $document_d_base . '/' . $document['sid']; ?>" class="btn btn-info">View Sign</a>
                                                                </div>
                                                            </td>
                                                            <td class="text-center hidden-xs">
                                                                <?= $pdBtn['pw'] . $pdBtn['dw']; ?>
                                                                <a href="<?php echo $document_d_base . '/' . $document['sid']; ?>" class="btn btn-info">View Sign</a>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                <?php } ?>
                                                <?php foreach ($handbook_documents['original'] as $key => $document) { ?>
                                                    <?php if ($document['archive'] != 1) { ?>
                                                        <?php $pdBtn = getPDBTN($document, 'btn-info', '', true);  ?>
                                                        <tr>
                                                            <td class="">
                                                                <?php
                                                                echo $document['document_title'] . ($document['is_required'] == 1 ? ' <i class="fa fa-asterisk jsTooltip" style="color: #cc1100;" aria-hidden="true" title="' . ($requiredMessage) . '"></i>' : '') . '';
                                                                ?>
                                                            </td>
                                                            <td class="text-center hidden-xs">
                                                                <?php if ($document['document_type'] == 'generated') { ?>
                                                                    <a href="<?= base_url("hr_documents_management/print_generated_and_offer_later/original/generated/" . ($document['sid']) . "/print"); ?>" class="btn btn-orange" target="_blank">Print</a>
                                                                <?php } ?>
                                                                <?php if ($document['document_type'] == 'uploaded') { ?>
                                                                    <a href="<?= base_url("hr_documents_management/download_upload_document/" . ($document['uploaded_document_s3_name'])); ?>" class="btn btn-black" target="_blank">Download</a>
                                                                <?php } else if ($document['document_type'] == 'hybrid_document') {
                                                                } else { ?>
                                                                    <a href="<?= base_url("hr_documents_management/print_generated_and_offer_later/original/generated/" . ($document['sid']) . "/download"); ?>" class="btn btn-black" target="_blank">Download</a>
                                                                <?php } ?>

                                                                <a onclick="func_get_generated_document_preview(<?= $document['sid']; ?>,'<?= $document['document_type']; ?>', 'Employee');" class="btn btn-info">View Sign</a>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                <?php } ?>
                                            </tbody>
                                        <?php } else { ?>
                                            <tbody>
                                                <tr>
                                                    <td class="col-lg-12 text-center"><strong>No document(s) found!</strong></td>
                                                </tr>
                                            </tbody>
                                        <?php } ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>

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
                                        <? //= $has_announcements;
                                        ?>
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



                        <?php if (checkIfAppIsEnabled('incidents')) { ?>

                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                <div class="widget-box">
                                    <a href="<?php echo base_url('incident_reporting_system'); ?>">
                                        <div class="link-box bg-cyan full-width">
                                            <h2>Incidents</h2>
                                            <!--                                            <ul class="cs-jam-ul" style="position: relative; z-index: 99;">-->
                                            <!--                                                <li>Assigned Incidents: -->
                                            <? //= $incidents_count;
                                            ?>
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
                        <?php } ?>


                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                            <div class="widget-box">
                                <a href="<?php echo base_url('my_learning_center'); ?>">
                                    <div class="link-box bg-bgcyan full-width">
                                        <h2>My Learning Center</h2>
                                        <div class="current-date">
                                            <span><?php echo $training_session_count; ?><sub>Pending</sub></span>
                                        </div>
                                        <!--                                            <ul class="cs-jam-ul">-->
                                        <!--                                                <li>Training Sessions: -->
                                        <? //= $training_session_count;
                                        ?>
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
                                        <? //= $documents_count;
                                        ?>
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

                        <?php if (checkIfAppIsEnabled('documentlibrary')) { ?>
                            <!-- Documents Library -->
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                <div class="widget-box ">
                                    <a href="<?php echo base_url('library_document'); ?>">
                                        <div class="link-box bgdarkblue full-width">
                                            <h2>
                                                Employee Forms Library&nbsp;
                                                <span href="javascript:void(0)" data-toggle="popover" data-trigger="hover" data-content='<?= $this->lang->line('document_librray_helping_text'); ?>'>
                                                    <i class="fa fa-question-circle" style="font-size: 25px;" aria-hidden="true"></i>
                                                </span>
                                            </h2>

                                            <div class="current-date">
                                                <span><?php echo $total_library_doc; ?> <sub>Total</sub></span>
                                            </div>
                                            <div class="status-panel">
                                                <h3>View Forms</h3>
                                                <span>Available to Complete</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        <?php } ?>

                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                            <div class="widget-box">
                                <a href="<?php echo base_url('hr_documents_management/approval_documents'); ?>">
                                    <div class="link-box bglime full-width">
                                        <h2>Approval Documents</h2>
                                        <div class="current-date">
                                            <span><?php echo $all_documents_approval; ?><sub>Pending</sub></span>
                                        </div>
                                        <div class="status-panel">
                                            <h3>View</h3>
                                            <span>Needs your approval</span>
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
                                        <? //= $messages;
                                        ?>
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
                        <?php if (!checkGeneralDocumentActive("all")): ?>
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
                        <?php endif; ?>
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
                            <?php $complyNetLink = getComplyNetLink($company_sid, $employee_sid); ?>
                            <?php if ($complyNetLink) { ?>
                                <!-- ComplyNet -->
                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                    <div class="widget-box">
                                        <a href="<?= base_url('cn/redirect'); ?>" target="_blank">
                                            <div class="link-box bg-complynet full-width">
                                                <h2>Compliance <br /> Management System</h2>
                                                <div class="status-panel">
                                                    <h3>ComplyNet</h3>
                                                    <span>Show</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            <?php
                            } ?>

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


                        <!-- Authorised Signature  -->
                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                            <a href="<?= base_url('authorized_document'); ?>">
                                <div class="widget-box">
                                    <div class="link-box bg-redish bgdarkblue full-width">
                                        <h2 class="text-blue">Assigned Document(s)</h2>
                                        <ul class="pto-box">
                                            <li>
                                                <span><?= $AuthorizedDocuments['Today']; ?></span>
                                                <span>Assigned Document(s) Today</span>

                                            </li>
                                            <li>
                                                <span><?= $AuthorizedDocuments['Pending']; ?></span>
                                                <span>Pending Document(s)</span>

                                            </li>
                                            <li>
                                                <span><?= $AuthorizedDocuments['Total']; ?></span>
                                                <span>Total Document(s)</span>
                                            </li>
                                        </ul>
                                        <div class="status-panel">
                                            <h3>View Document(s)</h3>
                                            <span>Show</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Employers Section  -->
                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                            <a href="<?= base_url('hr_documents_management/company_varification_document'); ?>">
                                <div class="widget-box">
                                    <div class="link-box bg-info full-width">
                                        <h2 class="text-blue">Employer Section(s)</h2>
                                        <ul class="pto-box">
                                            <li>
                                                <span><?= $PendingEmployerSection['Total']; ?></span>
                                                <span>Total</span>
                                            </li>
                                            <li>
                                                <span><?= $PendingEmployerSection['Applicant']; ?></span>
                                                <span>Applicant(s)</span>
                                            </li>
                                            <li>
                                                <span><?= $PendingEmployerSection['Employee']; ?></span>
                                                <span>Employee(s)</span>
                                            </li>
                                        </ul>
                                        <div class="status-panel">
                                            <h3>View Document(s)</h3>
                                            <span>Show</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <?php if (checkIfAppIsEnabled('performance_management')) { ?>
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6" id="js-to-box">
                                <a href="<?= base_url('performance-management/dashboard'); ?>">
                                    <div class="widget-box">
                                        <div class="link-box  bg-pr full-width">
                                            <h2 class="text-blue">Performance Management</h2>
                                            <ul class="pto-box">
                                                <li>
                                                    <span><?= $review['Reviews']; ?></span>
                                                    <span>Review(s)</span>
                                                </li>
                                                <li>
                                                    <span><?= $review['Feedbacks']; ?></span>
                                                    <span>Feedback(s)</span>
                                                </li>
                                                <li>
                                                    <span><?= $total_goals; ?></span>
                                                    <span>Goal(s)</span>
                                                </li>
                                            </ul>
                                            <div class="status-panel">
                                                <h3>View Review(s)</h3>
                                                <span>Show</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php } ?>

                        <?php if (checkIfAppIsEnabled('timeoff')) { ?>
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6" id="js-to-box">
                                <a href="<?= base_url('timeoff/lms'); ?>">
                                    <div class="widget-box">
                                        <div class="link-box bg-timeoff full-width">
                                            <h2 class="text-blue">Time Off</h2>
                                            <ul class="pto-box">
                                                <li>
                                                    <span id="jsRemainingTime">0 hour(s)</span>
                                                    <span>remaining</span>

                                                </li>
                                                <li>
                                                    <span id="jsConsumedTime">0 hour(s)</span>
                                                    <span>consumed</span>

                                                </li>
                                                <li>
                                                    <span id="jsTotalTimeoffs">0 Time-offs approved</span>

                                                </li>
                                                <li style="margin-top: 10px;">
                                                    <a href="javascript:void(0)" data-id="<?= $employee_sid; ?>" class="btn btn-black jsBreakdownRequest"><i class="fa fa-eye" style="font-size: 14px;" aria-hidden="true"></i>&nbsp;View Policies</a>
                                                    <a href="javascript:void(0)" data-id="<?= $employee_sid; ?>" class="btn btn-success jsReport"><i class="fa fa-area-chart" style="font-size: 14px;" aria-hidden="true"></i>&nbsp;Report</a>
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
                                                <a href="#" data-id="<?= $employee_sid; ?>" class="btn btn-success form-control jsCreateRequest" style="margin-right: 5px;"><i class="fa fa-plus-circle" style="font-size: 14px;" aria-hidden="true"></i>&nbsp;Create A Time-off Request</a>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php } ?>

                        <?php $this->load->view('v1/attendance/partials/clocks/blue/getting_started_block'); ?>

                        <?php if (isset($employeePayStubsCount)) { ?>
                            <!-- Payroll -->
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                <div class="widget-box">
                                    <a href="<?php echo base_url('payrolls/pay-stubs'); ?>">
                                        <div class="link-box bg-redish full-width">
                                            <h2>Payroll</h2>
                                            <div><span>&nbsp;</span></div>
                                            <div class="current-date">
                                                <span><?= $employeePayStubsCount; ?><sub>Pay Stub(s)</sub></span>
                                            </div>
                                            <div class="status-panel">
                                                <h3>View Pay Stubs</h3>
                                                <span>Show</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if (isCompanyVerifiedForPayroll() && isEmployeeOnPayroll($employee_sid)) { ?>
                            <!-- Payroll -->
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                <div class="widget-box bg-box">
                                    <a href="<?php echo base_url('payrolls/clair/employee/setup'); ?>">
                                        <div class="link-box bg-grey full-width">
                                            <div class="bg-icon-holder">
                                                <i class="fa fa-cogs"></i>
                                            </div>
                                            <h2>Set up Clair</h2>
                                            <div class="status-panel">
                                                <h3>Set up Clair</h3>
                                                <span>Setup</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if ($isLMSModuleEnabled) : ?>
                            <!-- LMS - Team Courses -->
                            <?php if ($haveSubordinate == "yes") { ?>
                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                    <div class="widget-box bg-box">
                                        <a href="<?php echo base_url('lms/courses/my_lms_dashboard'); ?>">
                                            <div class="link-box bg-redish full-width">
                                                <div class="bg-icon-holder">
                                                    <i class="fa fa-users"></i>
                                                </div>
                                                <h2>Team Courses</h2>
                                                <div><span>&nbsp;</span></div>
                                                <div class="current-date">
                                                    <span><?= $subordinateCount ?? 0; ?><sub>Employees</sub></span>
                                                </div>
                                                <div class="status-panel">
                                                    <h3>Manage Team Courses</h3>
                                                    <span>Manage</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            <?php } ?>
                            <!-- LMS - Courses -->
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                <div class="widget-box">
                                    <a href="<?php echo base_url('lms/courses/my_lms_dashboard'); ?>">
                                        <div class="link-box bg-redish full-width">
                                            <h2>Courses</h2>
                                            <div><span>&nbsp;</span></div>
                                            <div class="current-date">
                                                <span><?= $pendingTrainings ?? 0; ?><sub>Pending</sub></span>
                                            </div>
                                            <div class="status-panel">
                                                <h3>Courses</h3>
                                                <span>Assigned to You</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>


                        <?php if (checkIfAppIsEnabled(SCHEDULE_MODULE)) : ?>

                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                <div class="widget-box">
                                    <a href="<?php echo base_url('shifts/my'); ?>">
                                        <div class="link-box bg-redish full-width bg-orange">
                                            <h2>My Shifts</h2>
                                            <div><span>&nbsp;</span></div>
                                            <div class="current-date">
                                                <span><?= $myAssignedShifts ?? 0; ?><sub>Scheduled</sub></span>

                                            </div>
                                            <div class="status-panel">
                                                <h3>Show Shifts</h3>
                                                <span>Scheduled</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>


                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                <div class="widget-box">
                                    <a href="<?php echo base_url('shifts/my/subordinates'); ?>">
                                        <div class="link-box bg-redish full-width bg-red">
                                            <h2>My Team Shifts</h2>
                                            <div><span>&nbsp;</span></div>
                                            <div class="current-date">
                                                <span><?= count($mySubordinatesCount); ?></span>

                                            </div>
                                            <div class="status-panel">
                                                <h3>Show Team</h3>
                                                <span>Shifts</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                <div class="widget-box">
                                    <a href="<?php echo base_url('shifts/myTrade'); ?>">
                                        <div class="link-box bg-redish full-width bg-orange">
                                            <h2>Shift Swap Requests</h2>
                                            <div><span>&nbsp;</span></div>
                                            <div class="current-date" style="margin-top: -30px;;">
                                                <span><?= $awaitingShiftRequests ?? 0; ?><sub>Pending</sub></span>

                                            </div>
                                            <div class="status-panel">
                                                <h3>Show Details</h3>
                                                <span>&nbsp;</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <?php if (isPayrollOrPlus()) { ?>
                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                    <div class="widget-box">
                                        <a href="<?php echo base_url('settings/shifts/trade'); ?>">
                                            <div class="link-box bg-redish full-width bg-orange">
                                                <h2>Shift Swap Approvals</h2>
                                                <div><span>&nbsp;</span></div>
                                                <div class="current-date" style="margin-top: -30px;;">
                                                    <span><?= $awaitingShiftsApprovals ?? 0; ?><sub>Pending</sub></span>

                                                </div>
                                                <div class="status-panel">
                                                    <h3>Show Details</h3>
                                                    <span>&nbsp;</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php endif; ?>

                        <?php if (checkIfAppIsEnabled('performanceevaluation')) { ?>
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                <div class="widget-box">
                                    <a href="<?php echo base_url('fillable/epe/verification/documents'); ?>">
                                        <div class="link-box bg-bgcyan full-width">
                                            <h2>Employee Performance Evaluation</h2>
                                            <div class="current-date">
                                                <span><?= $pendingVerificationPerformanceDocument ?? 0; ?><sub>Pending</sub></span>
                                            </div>
                                            <div class="status-panel">
                                                <h3>Document(s)</h3>
                                            </div>
                                        </div>
                                    </a>
                                </div>
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
            func_show_section_ems(0);
        <?php } else { ?>
            func_show_section(0);
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

<script>
    $(function() {


        //
        var RTOT = 'my';
        //
        $('.jsReport').click(function(e) {
            //
            e.preventDefault();
            //
            Modal({
                Id: "jsReportModal",
                Title: "Time-off Report",
                Loader: "jsReportModalLoader",
                Body: `<div class="row">
            <div class="col-sm-12 col-xs-12">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-3 col-xs-12">
                            <div class="panel-heading col-sm-12 col-xs-12" id="tab_filter" style="background-color: #3554DC !important; color: #fff; padding-bottom: 0; padding-left: 5px;">
                            <span>
                            <a href="javascript:;" style="display: inline-block; padding: 11px" class="" id="my_tf_btn" placement="top" data-key="0" data-original-title="Show time offs for my team members">My Time-off</a>
                            </span>
                            <span>
                                <a href="javascript:;" style="display: inline-block; padding: 11px" class="" id="all_tf_btn" placement="top" data-key="0" data-original-title="Show time offs for my team members">All Time-off</a>
                            </span>
                            <div class="clearfix"></div>
                            </div>
                            <!--  -->
                            <form action="" method="GET" id="form_filter">
                                <div class="form-group" id="filter_employees_section">
                                    <label>Employee(s)</label>
                                    <select multiple="true" name="employees" id="filter_employees">
                                        
                                    </select>
                                </div>
                                <!--  -->
                                <div class="form-group" id="filter_departments_section">
                                    <label>Department(s)</label>
                                    <select multiple="true" name="departments" id="filter_departments">
                                        
                                    </select>
                                </div>
                                <!--  -->
                                <div class="form-group" id="filter_teams_section">
                                    <label>Team(s)</label>
                                    <select multiple="true" name="teams" id="filter_teams">
                                        
                                    </select>
                                </div>
                                <!--  -->
                                <div class="form-group" id="filter_jobtitle_section">
                                    <label>Job Title(s)</label>
                                    <select id="jsJobTitles" multiple="true">
                                    
                                    </select>
                                </div>
                                <!--  -->
                                <div class="form-group" id="filter_employeetype_section">
                                    <label>Employment Type(s)</label>
                                    <select id="jsEmploymentTypes" multiple="true">
                                        <option value="fulltime">Full-time</option>
                                        <option value="parttime">Part-time</option>
                                        <option value="contractual">Contractual</option>
                                    </select>
                                </div>
                                <!--  -->
                                <div class="form-group">
                                    <label>Start Date</label>
                                    <input type="text" id="jsReportStartDate" name="startDate" class="form-control" readonly />
                                </div>
                                <!--  -->
                                <div class="form-group">
                                    <label>End Date</label>
                                    <input type="text" id="jsReportEndDate" name="endDate" class="form-control" readonly />
                                </div>
                                <input type="hidden" name="user_allow" id="user_allow">
                                <input type="hidden" name="request_type" id="request_type">
                                <input type="hidden" name="request_token" id="request_token">
                                <div class="form-group">
                                    <button class="btn btn-success form-control jsGetReport" data-href="${baseURL+'timeoff/get_report/'+(employeeId)+''}">Apply Filter</button>
                                </div>
                            </form>    
                            <!--  -->
                            <div class="form-group">
                                <a href="<?php echo base_url('timeoff/report'); ?>" class="btn btn-black form-control">Clear Filter</a>
                            </div>
                        </div>
                        <div class="col-sm-9 col-xs-12">
                            <span class="pull-right">
                                <button class="btn btn-success jsReportLink" data-href="${baseURL+'timeoff/report/print/all'}"><i class="fa fa-print" aria-hidden="true"></i>&nbsp;Print</button>
                                <button class="btn btn-success jsReportLink" data-href="${baseURL+'timeoff/report/download/all'}"><i class="fa fa-download" aria-hidden="true"></i>&nbsp;Download</button>
                            </span>
                            <div class="clearfix"></div>
                            <div class="table-responsive">
                                <table class="table table-striped table-condensed">
                                    <caption></caption>
                                    <thead>
                                        <tr style="background: #444444; color:#fff;">
                                            <th scope="col">Employee Name</th>
                                            <th scope="col">Department</th>
                                            <th scope="col">Team</th>
                                            <th scope="col"># of Requests</th>
                                            <th sco   
                                         pe="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="timeoff_container"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>`
            }, function() {
                var date = new Date();
                var y = date.getFullYear();
                var m = date.getMonth();
                var firstDay = new Date(y, m, 1);
                var lastDay = new Date(y, m + 1, 0);
                var type = $('.jsReport').attr('data-action');
                var enable = {
                    backgroundColor: "#fd7a2a",
                    color: "#fff"
                };

                var disable = {
                    backgroundColor: "#fff",
                    color: "#000"
                };

                get_user_access_level();


                $('#filter_employees').select2({
                    closeOnSelect: false
                });
                $('#filter_departments').select2({

                    closeOnSelect: false
                });
                $('#filter_teams').select2({
                    closeOnSelect: false
                });
                $('#jsJobTitles').select2({
                    closeOnSelect: false
                });
                $('#jsEmploymentTypes').select2({
                    closeOnSelect: false
                });

                $("#my_tf_btn").css(enable);
                $("#all_tf_btn").css(disable);
                $("#request_type").val('my');
                //
                $('#filter_employees_section').hide();
                $('#filter_departments_section').hide();
                $('#filter_teams_section').hide();
                $('#filter_jobtitle_section').hide();
                $('#filter_employeetype_section').hide();

                $("#jsReportStartDate").val($.datepicker.formatDate('mm/dd/yy', firstDay));
                $("#jsReportEndDate").val($.datepicker.formatDate('mm/dd/yy', lastDay));
                //
                ml(false, 'jsReportModalLoader');
                //
                $('#jsReportStartDate').datepicker({
                    format: 'm/d/y',
                    changeMonth: true,
                    changeYear: true,
                    yearRange: "<?php echo DOB_LIMIT; ?>",
                    onSelect: function(d) {
                        $('#jsReportEndDate').datepicker('option', 'minDate', d);
                    }
                });
                //
                $("#all_tf_btn").on("click", function() {
                    $("#request_type").val('all');
                    $("#my_tf_btn").css(disable);
                    $("#all_tf_btn").css(enable);
                    RTOT = 'all';

                    $('#filter_employees_section').show();
                    $('#filter_departments_section').show();
                    $('#filter_teams_section').show();
                    $('#filter_jobtitle_section').show();
                    $('#filter_employeetype_section').show();
                    get_timeoff_report()
                });

                $("#my_tf_btn").on("click", function() {
                    RTOT = 'my';
                    $("#request_type").val('my');
                    $("#my_tf_btn").css(enable);

                    $("#all_tf_btn").css(disable);

                    $('#filter_employees_section').hide();
                    $('#filter_departments_section').hide();
                    $('#filter_teams_section').hide();
                    $('#filter_jobtitle_section').hide();
                    $('#filter_employeetype_section').hide();
                    get_timeoff_report()
                });
                //
                $('#jsReportEndDate').datepicker({
                    format: 'm/d/y',
                    changeMonth: true,
                    changeYear: true,
                    yearRange: "<?php echo DOB_LIMIT; ?>"
                });
                //
                $(document).on('click', '.timeoff_count', function() {
                    $('.' + $(this).data('id')).toggle();
                });
                //
                $(".jsGetReport").click(function(c) {
                    //
                    c.preventDefault();
                    let startDate = $('#jsReportStartDate').val() || 'all',
                        endDate = $('#jsReportEndDate').val() || 'all';
                    //
                    ml(true, 'jsReportModalLoader');
                    //
                    var URL = $(this).data('href');
                    var formData = $("#form_filter").serialize();
                    //
                    $.ajax({
                        url: URL,
                        cache: false,
                        contentType: false,
                        processData: false,
                        type: 'GET',
                        data: formData,
                        success: function(resp) {
                            //
                            $('#request_token').val(resp.session_token);
                            $('#timeoff_container').html(resp.modal)
                            //
                            ml(false, 'jsReportModalLoader');
                        },
                        error: function() {}
                    });
                });
                //
                $('.jsReportLink').click(function(c) {
                    //
                    c.preventDefault();
                    let startDate = $('#jsReportStartDate').val() || 'all',
                        endDate = $('#jsReportEndDate').val() || 'all',
                        token = $('#request_token').val();
                    //
                    window.open($(this).data('href') + '?start=' + (startDate) + '&end=' + (endDate) + '&token=' + (token));
                });
            });
        });

        function get_user_access_level() {
            ml(true, 'jsReportModalLoader');
            var my_url = baseURL + 'timeoff/get_employee_status/' + (employeeId);

            $.ajax({
                url: my_url,
                cache: false,
                contentType: false,
                processData: false,
                type: 'get',
                success: function(resp) {
                    ml(false, 'jsReportModalLoader');
                    //

                    if (resp.allow_access == 'no') {
                        $('#filter_employees_section').hide();
                        $('#filter_departments_section').hide();
                        $('#filter_teams_section').hide();
                        $('#filter_jobtitle_section').hide();
                        $('#filter_employeetype_section').hide();
                        $('#user_allow').val('no');
                        $('#tab_filter').hide();

                    } else {
                        $('#filter_employees').html(resp.employee);
                        $('#filter_departments').html(resp.department);
                        $('#filter_teams').html(resp.team);

                        $('#user_allow').val('yes');
                        $('#tab_filter').show();
                    }

                    get_timeoff_report();
                },
                error: function() {}
            });
        }

        function get_timeoff_report() {
            //
            ml(true, 'jsReportModalLoader');
            //
            var URL = baseURL + 'timeoff/get_report/' + (employeeId);;
            var formData = $("#form_filter").serialize();
            //
            $.ajax({
                url: URL,
                cache: false,
                contentType: false,
                processData: false,
                type: 'GET',
                data: formData,
                success: function(resp) {
                    ml(false, 'jsReportModalLoader');
                    //
                    $('#timeoff_container').html(resp.modal);
                    $('#request_token').val(resp.session_token);
                    //
                    if (RTOT == 'my') {
                        console.log(RTOT);
                        $("tr").filter(function() {
                            return this.className.match(/timeoff_/);
                        }).show();
                    }
                    //
                    if (resp.main_action_button == 'no') {
                        $('.jsReportLink').hide();
                    }
                },
                error: function() {}
            });
        }
    });
</script>

<script>
    function func_get_generated_document_preview(document_sid, doc_flag = 'generated', doc_title = 'Preview Generated Document') {
        var my_request;
        var footer_print_btn;
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
            $.ajax({
                'url': '<?php echo base_url('hr_documents_management/get_print_url'); ?>',
                'type': 'POST',
                'data': {
                    'request_type': 'original',
                    'document_type': doc_flag,
                    'document_sid': document_sid
                },
                success: function(urls) {
                    var obj = jQuery.parseJSON(urls);
                    var print_url = obj.print_url;
                    var download_url = obj.download_url;
                    footer_content = '<a target="_blank" class="btn btn-success" href="' + download_url + '">Download</a>';
                    footer_print_btn = '<a target="_blank" class="btn btn-success" href="' + print_url + '" >Print</a>';
                    $('#document_modal_body').html(response);
                    $('#document_modal_footer').html(footer_content);
                    $('#document_modal_footer').append(footer_print_btn);
                    $('#document_modal_title').html(doc_title);
                    $('#document_modal').modal("toggle");
                }
            });
        });
    }
</script>


<div id="document_modal" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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