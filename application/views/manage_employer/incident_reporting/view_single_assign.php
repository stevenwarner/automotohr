<?php
$incidentReporterId = $assigned_incidents[0]['reporter_id'];
?>
<div class="main jsmaincontent">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="row">

                    <div class="col-lg-1 col-md-1 col-xs-12 col-sm-1">
                        <a href="<?php echo $employee['access_level'] == 'Employee' ? base_url('dashboard') : base_url('employee_management_system'); ?>" class="btn btn-info csRadius5"><i class="fa fa-arrow-left"></i> Dashboard</a>
                    </div>

                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                        <a href="<?php echo base_url('incident_reporting_system') ?>" class="btn btn-info btn-block mb-2"><i class="fa fa-angle-left"> </i> Incident Reporting</a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo base_url('incident_reporting_system/list_incidents') ?>" class="btn btn-info btn-block mb-2"><i class="fa fa-heartbeat"></i> <?= $this->lang->line('tab_my_incidents', false) ?></a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo base_url('incident_reporting_system/assigned_incidents'); ?>" class="btn btn-info btn-block mb-2"><i class="fa fa-stethoscope "></i> <?= $this->lang->line('tab_assigned_incidents', false) ?></a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo base_url('incident_reporting_system/safety_check_list'); ?>" class="btn btn-info btn-block mb-2"><i class="fa fa-book"></i> Safety Check List </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="page-header">
                    <h2 class="section-ttile"><?php echo $title; ?> <span class="pull-right"><b><?php echo sizeof($assigned_incidents) ? '( ' . $incident_name . ' )' : ''; ?></b></span> </h2>
                </div>
                <?php if (sizeof($assigned_incidents) > 0) { ?>
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                            <?php if ($assigned_incidents[0]['status'] !== "Closed") { ?>
                                <a class="btn btn-warning btn-block jsMarkItResolved" href="javascript:;" data-incidentId="<?php echo $id; ?>">Mark it Resolved</a>
                            <?php } ?>
                        </div>
                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                            <a target="_blank" href="<?php echo base_url('incident_reporting_system/print_and_download/manager/0/all/2') . '/' . $id; ?>" class="btn btn-info btn-block mb-2"><i class="fa fa-download"></i> Download Reported Incident </a>
                        </div>
                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                            <a target="_blank" href="<?php echo base_url('incident_reporting_system/print_and_download/manager/0/all/1') . '/' . $id; ?>" class="btn btn-info btn-block mb-2"><i class="fa fa-print"></i> Print Reported Incident</a>
                        </div>
                    </div>

                    <?php if ($assigned_incidents[0]['report_type'] == 'confidential') { ?>
                        <div class="table-responsive table-outer">
                            <div class="table-wrp data-table">
                                <table class="table table-bordered table-hover table-stripped" id="reference_network_table">
                                    <b>Reporter Name</b>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <?php echo $assigned_incidents[0]['first_name'] . " " . $assigned_incidents[0]['last_name'] ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="table-responsive table-outer">
                            <div class="table-wrp data-table">
                                <table class="table table-bordered table-hover table-stripped" id="reference_network_table">
                                    <b>Reporter Telephone Number</b>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <?php echo isset($assigned_incidents[0]['PhoneNumber']) && !empty($assigned_incidents[0]['PhoneNumber']) ? $assigned_incidents[0]['PhoneNumber'] : 'N/A' ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="table-responsive table-outer">
                            <div class="table-wrp data-table">
                                <table class="table table-bordered table-hover table-stripped" id="reference_network_table">
                                    <b>Reporter Email</b>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <?php echo $assigned_incidents[0]['email'] ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="table-responsive table-outer">
                            <div class="table-wrp data-table">
                                <table class="table table-bordered table-hover table-stripped" id="reference_network_table">
                                    <b>Reporter Title</b>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <?php echo $assigned_incidents[0]['job_title'] ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php } ?>

                    <!-- Question/Answer Section Start -->
                    <?php foreach ($assigned_incidents as $incident) { ?>
                        <div class="table-responsive table-outer">
                            <div class="table-wrp data-table">
                                <table class="table table-bordered table-hover table-stripped" id="reference_network_table">
                                    <b><?php echo $incident['question']; ?></b>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <?php
                                                $ans = @unserialize($incident['answer']);
                                                if ($ans !== false) {
                                                    echo implode(', ', $ans);
                                                } else {
                                                    echo ucfirst($incident['answer']);
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php } ?>
                    <!-- Question/Answer Section End -->

                    <!-- Witnesses Section Start -->
                    <?php if ($assigned_incidents[0]['status'] != 'Closed') { ?>
                        <div class="table-responsive table-outer">
                            <div class="panel panel-blue">
                                <div class="panel-heading incident-panal-heading">
                                    <b>Add Witnesses</b>
                                </div>
                                <div class="panel-body">
                                    <form id="form_new_witnesses" method="post" action="" autocomplete="off">
                                        <input type="hidden" id="perform_action" name="perform_action" value="add_witness" />

                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <label class="auto-height">
                                                        Add Company Witnesses :
                                                    </label>
                                                    <?php if (sizeof($employees) > 0) { ?>
                                                        <select id="employee_to_witness" src="1" class="form-control">
                                                            <option>Please Select Employee as Witness</option>
                                                            <?php foreach ($employees as $employee) { ?>
                                                                <?php
                                                                if ($employer_sid == $employee['sid']) {
                                                                    continue;
                                                                }
                                                                $employee_full_name =  $employee['first_name'] . ' ' . $employee['last_name'];
                                                                $option_value = $employee_full_name . ',' . $employee['email'] . ',' . $employee['PhoneNumber'];
                                                                ?>

                                                                <option value="<?php echo $option_value; ?>"><?php echo $employee_full_name; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    <?php } else { ?>
                                                        <p>No Employee Found.</p>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <label class="auto-height">
                                                        Add Outside Witnesses :
                                                    </label>
                                                    <a href="javascript:;" id="add_new_witrness" class="btn btn-info btn-block mb-2" src="1" onclick="add_new_witrness('outter');" style="height: : 30px; line-height: 30px">Add Witness</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="panel panel-info">
                                                    <div class="panel-heading">
                                                        <strong>Company Witnesses</strong>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div id="add_inner_wirnesses_section">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="panel panel-info">
                                                    <div class="panel-heading">
                                                        <strong>Outside Witnesses</strong>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div id="add_outter_wirnesses_section" style="background-color: #eee;">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 text-right">
                                            <button type="submit" class="btn btn-info incident-panal-button" name="submit" value="submit">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (isset($witnesses) && !empty($witnesses)) { ?>
                        <div class="table-responsive table-outer">
                            <div class="panel panel-blue">
                                <div class="panel-heading incident-panal-heading">
                                    <b>Related Witnesses</b>
                                </div>
                                <div class="panel-body">
                                    <div class="accordion" id="accordionExample">
                                        <div class="table-responsive table-outer">
                                            <div class="table-wrp data-table">
                                                <table class="table table-bordered table-hover table-stripped">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">Witness Name</th>
                                                            <th class="text-center">Witness Phone</th>
                                                            <th class="text-center">Witness Email</th>
                                                            <th class="text-center">Witness Title</th>
                                                            <th class="text-center">Can Provide Information</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($witnesses as $key => $witness) { ?>
                                                            <tr>
                                                                <td class="text-center"><?php echo $witness['witness_name']; ?></td>
                                                                <td class="text-center"><?php echo $witness['witness_phone']; ?></td>
                                                                <td class="text-center"><?php echo $witness['witness_email']; ?></td>
                                                                <td class="text-center"><?php echo $witness['witness_title']; ?></td>
                                                                <td class="text-center">
                                                                    <?php
                                                                    $can_provide = $witness['can_provide_info'];
                                                                    if ($can_provide == 'yes') {
                                                                        echo '<b>YES</b>';
                                                                    } else if ($can_provide == 'no') {
                                                                        echo '<b>NO</b>';
                                                                    }
                                                                    ?>
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
                    <?php } ?>
                    <!-- Witnesses Section End -->

                    <!-- Media Section Start -->
                    <?php if ($assigned_incidents[0]['status'] != 'Closed') { ?>
                        <div class="table-responsive table-outer">
                            <div class="panel panel-blue">
                                <div class="panel-heading incident-panal-heading">
                                    <b>Upload Incident Videos/Audios</b>
                                </div>
                                <div class="panel-body">
                                    <form id="form_new_video" enctype="multipart/form-data" method="post" action="" autocomplete="off">
                                        <input type="hidden" id="perform_action" name="perform_action" value="add_video" />
                                        <div class="form-group edit_filter autoheight">
                                            <?php $field_name = 'video_source' ?>
                                            <?php echo form_label('Video Source', $field_name); ?>
                                            <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                <?php echo YOUTUBE_VIDEO; ?>
                                                <input checked="checked" class="video_source" type="radio" name="video_source" value="youtube" />
                                                <div class="control__indicator"></div>
                                            </label>
                                            <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                <?php echo VIMEO_VIDEO; ?>
                                                <input class="video_source" type="radio" name="video_source" value="vimeo" />
                                                <div class="control__indicator"></div>
                                            </label>
                                            <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                <?php echo UPLOAD_VIDEO; ?>
                                                <input class="video_source" type="radio" name="video_source" value="upload_video" />
                                                <div class="control__indicator"></div>
                                            </label>
                                            <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                <?php echo UPLOAD_AUDIO; ?>
                                                <input class="video_source" type="radio" name="video_source" value="upload_audio" />
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>

                                        <div class="row">
                                            <div class="field-row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 form-wrp">
                                                    <div class="form-group autoheight">
                                                        <label for="video_id">Video Title <span class="required">*</span></label>
                                                        <input type="text" name="video_title" value="" class="form-control" id="video_title" placeholder="Please Enter Video Title">
                                                    </div>
                                                    <div class="form-group autoheight" id="yt_vm_video_container">
                                                        <label for="video_id">Video Url <span class="required">*</span></label>
                                                        <input type="text" name="video_id" value="" class="form-control" id="video_id">
                                                    </div>
                                                    <div class="form-group autoheight" id="up_video_container">
                                                        <label>Upload Video <span class="required">*</span></label>
                                                        <div class="upload-file form-control" style="margin-bottom:10px;">
                                                            <span class="selected-file" id="name_video"></span>
                                                            <input type="file" name="video_upload" id="video" onchange="check_video_file('video')">
                                                            <a href="javascript:;">Choose Video</a>
                                                        </div>
                                                    </div>
                                                    <div class="form-group autoheight" id="up_audio_container">
                                                        <label>Upload Audio <span class="required">*</span></label>
                                                        <div class="upload-file form-control" style="margin-bottom:10px;">
                                                            <span class="selected-file" id="name_audio"></span>
                                                            <input type="file" name="audio_upload" id="audio" onchange="check_audio_file('audio')">
                                                            <a href="javascript:;">Choose Audio</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 text-right">
                                            <button type="submit" class="btn btn-info incident-panal-button" name="submit" value="submit" id="upload_audio_video">Save Video</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (!empty($videos) || !empty($videos_archived)) { ?>
                        <div class="table-responsive table-outer">
                            <div class="panel panel-blue">
                                <div class="panel-heading incident-panal-heading">
                                    <b>Related Video / Audio</b>
                                    <a target="_blank" href="<?php echo base_url('incident_reporting_system/download_incident_all_documents_and_videos/media/0') . '/' . $id; ?>" class="pull-right print-incident modify-comment-btn"><i class="fa fa-download"></i> Download All Media</a>
                                </div>
                                <div class="panel-body">
                                    <div class="accordion" id="accordionExample2">
                                        <div class="card">
                                            <div class="card-header" id="hw1">
                                                <h2 class="mb-0" style="background-color: #eee;">
                                                    <button class="btn btn-link incident-panal-links" type="button" data-toggle="collapse" data-target="#activeVideo" aria-expanded="true" aria-controls="activeVideo">
                                                        <?php
                                                        $act_media_count = sizeof($videos);
                                                        $act_media_text = '';
                                                        if ($act_media_count > 1) {
                                                            $act_media_text = '( ' . $act_media_count . ' Items )';
                                                        } else {
                                                            $act_media_text = '( ' . $act_media_count . ' Item )';
                                                        }
                                                        ?>
                                                        Active Videos <?php echo $act_media_text; ?>
                                                    </button>
                                                </h2>
                                            </div>

                                            <div id="activeVideo" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <div class="announcements-listing">
                                                        <?php $watch_video = base_url('incident_reporting_system/watch_video'); ?>
                                                        <?php foreach ($videos as $video) { ?>
                                                            <article class="listing-article">
                                                                <figure>
                                                                    <?php
                                                                    $video_source = $video['video_type'];
                                                                    $media_url = '';
                                                                    if ($video_source == 'upload_video') {
                                                                        $media_url = base_url() . 'assets/uploaded_videos/incident_videos/' . $video['video_url'];
                                                                    } else if ($video_source == 'upload_audio') {
                                                                        $media_url = base_url() . 'assets/uploaded_videos/incident_videos/' . $video['video_url'];
                                                                    } else {
                                                                        $media_url = $video['video_url'];
                                                                    }
                                                                    ?>
                                                                    <?php if ($video_source == 'youtube') { ?>
                                                                        <a href="javascript:;" video-title="<?php echo $video['video_title']; ?>" video-source="<?php echo $video_source; ?>" video-url="<?php echo $media_url; ?>" class="js-view-video">
                                                                            <img src="https://img.youtube.com/vi/<?php echo $video['video_url']; ?>/hqdefault.jpg" />
                                                                        </a>
                                                                    <?php } else if ($video_source == 'vimeo') {
                                                                        $thumbnail_image = vimeo_video_data($video['video_url']); ?>
                                                                        <a href="javascript:;" video-title="<?php echo $video['video_title']; ?>" video-source="<?php echo $video_source; ?>" video-url="<?php echo $media_url; ?>" class="js-view-video">
                                                                            <img src="<?php echo $thumbnail_image; ?>" />
                                                                        </a>
                                                                    <?php } else if ($video_source == 'upload_video') { ?>
                                                                        <a href="javascript:;" video-title="<?php echo $video['video_title']; ?>" video-source="<?php echo $video_source; ?>" video-url="<?php echo $media_url; ?>" class="js-view-video">
                                                                            <video width="214" height="210">
                                                                                <source src="<?php echo base_url('assets/uploaded_videos/incident_videos/' . $video['video_url']); ?>" type="video/mp4">
                                                                            </video>
                                                                        </a>
                                                                    <?php } else if ($video_source == 'upload_audio') { ?>
                                                                        <a href="javascript:;" video-title="<?php echo $video['video_title']; ?>" video-source="<?php echo $video_source; ?>" video-url="<?php echo $media_url; ?>" class="js-view-video">
                                                                            <img src="<?php echo base_url('assets/images/incident_audio.png') ?>" />
                                                                        </a>
                                                                    <?php } ?>
                                                                </figure>
                                                                <div class="text">
                                                                    <h3>
                                                                        <?php
                                                                        $title = !empty($video['video_title']) ? $video['video_title'] : 'No Title';
                                                                        echo 'Title : ' . $title;
                                                                        ?>
                                                                    </h3>
                                                                    <div class="post-options">
                                                                        <ul>
                                                                            <li><?php echo 'Uploaded Date : ' . reset_datetime(array('datetime' => $video['uploaded_date'], '_this' => $this)); ?></li>
                                                                        </ul>
                                                                        <span class="post-author">
                                                                            <?php
                                                                            $video_source = $video['video_type'];
                                                                            $media_url = '';
                                                                            if ($video_source == 'upload_video') {
                                                                                $media_url = base_url() . 'assets/uploaded_videos/incident_videos/' . $video['video_url'];
                                                                            } else if ($video_source == 'upload_audio') {
                                                                                $media_url = base_url() . 'assets/uploaded_videos/incident_videos/' . $video['video_url'];
                                                                            } else {
                                                                                $media_url = $video['video_url'];
                                                                            }
                                                                            ?>

                                                                            <a href="javascript:;" video-title="<?php echo $video['video_title']; ?>" video-source="<?php echo $video_source; ?>" video-url="<?php echo $media_url; ?>" class="btn btn-block btn-info js-view-video">Watch Video</a>
                                                                        </span>
                                                                        <span class="post-author" style="margin-right: 10px;"><a href="javascript:void(0)" data-id="<?= $video['sid']; ?>" class="btn btn-block btn-info js-archive-video">Archive Video</a></span>

                                                                        <span class="post-author" style="margin-right: 10px;"><a href="javascript:void(0)" data-id="<?php echo $video['sid']; ?>" data-title="<?php echo $video['video_title']; ?>" class="btn btn-block btn-info js-edit-video">Edit Video</a></span>
                                                                        <?php if ($video['video_type'] == 'upload_audio' || $video['video_type'] == 'upload_video') { ?>
                                                                            <span class="post-author" style="margin-right: 10px;">
                                                                                <a target="_blank" href="<?php echo base_url('incident_reporting_system/download_media_file') . '/' . $video['sid']; ?>" class="btn btn-block btn-info">
                                                                                    <i class="fa fa-download"></i>
                                                                                </a>
                                                                            </span>
                                                                        <?php } ?>
                                                                    </div>
                                                                    <div class="full-width announcement-des" style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                                                                        <?php
                                                                        $user_info = db_get_employee_profile($video['uploaded_by']);
                                                                        echo 'Uploaded By : ' . strtoupper($user_info[0]['first_name'] . ' ' . $user_info[0]['last_name']);
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                            </article>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card">
                                            <div class="card-header" id="hw2">
                                                <h2 class="mb-0" style="background-color: #eee;">
                                                    <button class="btn btn-link incident-panal-links" type="button" data-toggle="collapse" data-target="#archiveVideo" aria-expanded="true" aria-controls="archiveVideo">
                                                        <?php
                                                        $Archived_media_count = sizeof($videos_archived);
                                                        $Archived_media_text = '';
                                                        if ($Archived_media_count > 1) {
                                                            $Archived_media_text = '( ' . $Archived_media_count . ' Items )';
                                                        } else {
                                                            $Archived_media_text = '( ' . $Archived_media_count . ' Item )';
                                                        }
                                                        ?>
                                                        Archived Videos <?php echo $Archived_media_text; ?>
                                                    </button>
                                                </h2>
                                            </div>

                                            <div id="archiveVideo" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <div class="announcements-listing">
                                                        <?php $watch_video = base_url('incident_reporting_system/watch_video'); ?>
                                                        <?php foreach ($videos_archived as $video) { ?>
                                                            <article class="listing-article">
                                                                <figure>
                                                                    <?php
                                                                    $video_source = $video['video_type'];
                                                                    $media_url = '';
                                                                    if ($video_source == 'upload_video') {
                                                                        $media_url = base_url() . 'assets/uploaded_videos/incident_videos/' . $video['video_url'];
                                                                    } else if ($video_source == 'upload_audio') {
                                                                        $media_url = base_url() . 'assets/uploaded_videos/incident_videos/' . $video['video_url'];
                                                                    } else {
                                                                        $media_url = $video['video_url'];
                                                                    }
                                                                    ?>
                                                                    <?php if ($video_source == 'youtube') { ?>
                                                                        <a href="javascript:;" video-title="<?php echo $video['video_title']; ?>" video-source="<?php echo $video_source; ?>" video-url="<?php echo $media_url; ?>" class="js-view-video">
                                                                            <img src="https://img.youtube.com/vi/<?php echo $video['video_url']; ?>/hqdefault.jpg" />
                                                                        </a>
                                                                    <?php } else if ($video_source == 'vimeo') {
                                                                        $thumbnail_image = vimeo_video_data($video['video_url']); ?>
                                                                        <a href="javascript:;" video-title="<?php echo $video['video_title']; ?>" video-source="<?php echo $video_source; ?>" video-url="<?php echo $media_url; ?>" class="js-view-video">
                                                                            <img src="<?php echo $thumbnail_image; ?>" />
                                                                        </a>
                                                                    <?php } else if ($video_source == 'upload_video') { ?>
                                                                        <a href="javascript:;" video-title="<?php echo $video['video_title']; ?>" video-source="<?php echo $video_source; ?>" video-url="<?php echo $media_url; ?>" class="js-view-video">
                                                                            <video width="214" height="210">
                                                                                <source src="<?php echo base_url('assets/uploaded_videos/incident_videos/' . $video['video_url']); ?>" type="video/mp4">
                                                                            </video>
                                                                        </a>
                                                                    <?php } else if ($video_source == 'upload_audio') { ?>
                                                                        <a href="javascript:;" video-title="<?php echo $video['video_title']; ?>" video-source="<?php echo $video_source; ?>" video-url="<?php echo $media_url; ?>" class="js-view-video">
                                                                            <img src="<?php echo base_url('assets/images/incident_audio.png') ?>" />
                                                                        </a>
                                                                    <?php } ?>
                                                                </figure>
                                                                <div class="text">
                                                                    <h3>
                                                                        <?php
                                                                        $title = !empty($video['video_title']) ? $video['video_title'] : 'No Title';
                                                                        echo 'Title : ' . $title;
                                                                        ?>
                                                                    </h3>
                                                                    <div class="post-options">
                                                                        <ul>
                                                                            <li><?php echo 'Uploaded Date : ' . reset_datetime(array('datetime' => $video['uploaded_date'], '_this' => $this)); ?></li>
                                                                        </ul>
                                                                        <span class="post-author">
                                                                            <?php
                                                                            $video_source = $video['video_type'];
                                                                            $media_url = '';
                                                                            if ($video_source == 'upload_video') {
                                                                                $media_url = base_url() . 'assets/uploaded_videos/incident_videos/' . $video['video_url'];
                                                                            } else if ($video_source == 'upload_audio') {
                                                                                $media_url = base_url() . 'assets/uploaded_videos/incident_videos/' . $video['video_url'];
                                                                            } else {
                                                                                $media_url = $video['video_url'];
                                                                            }
                                                                            ?>

                                                                            <a href="javascript:;" video-title="<?php echo $video['video_title']; ?>" video-source="<?php echo $video_source; ?>" video-url="<?php echo $media_url; ?>" class="btn btn-block btn-info js-view-video">Watch Video</a>
                                                                        </span>
                                                                        <span class="post-author" style="margin-right: 10px;"><a href="javascript:void(0)" data-id="<?= $video['sid']; ?>" class="btn btn-block btn-info js-active-video">Activate Video</a></span>
                                                                        <span class="post-author" style="margin-right: 10px;"><a href="javascript:void(0)" data-id="<?php echo $video['sid']; ?>" data-title="<?php echo $video['video_title']; ?>" class="btn btn-block btn-info js-edit-video">Edit Video</a></span>
                                                                        <?php if ($video['video_type'] == 'upload_audio' || $video['video_type'] == 'upload_video') { ?>
                                                                            <span class="post-author" style="margin-right: 10px;">
                                                                                <a target="_blank" href="<?php echo base_url('incident_reporting_system/download_media_file') . '/' . $video['sid']; ?>" class="btn btn-block btn-info">
                                                                                    <i class="fa fa-download"></i>
                                                                                </a>
                                                                            </span>
                                                                        <?php } ?>
                                                                    </div>
                                                                    <div class="full-width announcement-des" style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                                                                        <?php
                                                                        $user_info = db_get_employee_profile($video['uploaded_by']);
                                                                        echo 'Uploaded By : ' . strtoupper($user_info[0]['first_name'] . ' ' . $user_info[0]['last_name']);
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                            </article>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <!-- Media Section End -->

                    <!-- Document Section Start -->
                    <?php if ($assigned_incidents[0]['status'] != 'Closed') { ?>
                        <div class="table-responsive table-outer">
                            <div class="panel panel-blue">
                                <div class="panel-heading incident-panal-heading">
                                    <b>Upload Documents</b>
                                </div>
                                <div class="panel-body">
                                    <form id="form_new_document" enctype="multipart/form-data" method="post" action="">
                                        <input type="hidden" id="perform_action" name="perform_action" value="add_document" />
                                        <div class="row">
                                            <div class="field-row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 form-wrp">
                                                    <div class="form-group autoheight">
                                                        <label for="document_title">Document Title <span class="required">*</span></label>
                                                        <input type="text" name="document_title" value="" class="form-control" id="document_title" placeholder="Please Enter Document Title">
                                                    </div>
                                                    <div class="form-group autoheight">
                                                        <label>Upload Document <span class="required">*</span></label>
                                                        <div class="upload-file form-control" style="margin-bottom:10px;">
                                                            <span class="selected-file" id="name_upload_document"></span>
                                                            <input type="file" name="upload_document" id="upload_document" onchange="check_upload_document('upload_document')">
                                                            <a href="javascript:;">Choose File</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 text-right">
                                            <button type="submit" class="btn btn-info incident-panal-button" name="submit" value="submit">Save Document</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (sizeof($get_incident_document) > 0 || sizeof($get_incident_document_archived) > 0) { ?>
                        <div class="table-responsive table-outer">
                            <div class="panel panel-blue">
                                <div class="panel-heading incident-panal-heading">
                                    <b>Related Documents</b>
                                    <a target="_blank" href="<?php echo base_url('incident_reporting_system/download_incident_all_documents_and_videos/document/0') . '/' . $id; ?>" class="pull-right print-incident modify-comment-btn"><i class="fa fa-download"></i> Download All Documents</a>
                                </div>
                                <div class="panel-body">

                                    <div class="accordion" id="accordionExample">
                                        <div class="card">
                                            <div class="card-header" id="headingOne">
                                                <h2 class="mb-0" style="background-color: #eee;">
                                                    <button class="btn btn-link incident-panal-links" type="button" data-toggle="collapse" data-target="#activeDocumentsTab" aria-expanded="true" aria-controls="activeDocumentsTab">
                                                        <?php
                                                        $act_doc_count = sizeof($get_incident_document);
                                                        $act_doc_text = '';
                                                        if ($act_doc_count > 1) {
                                                            $act_doc_text = '( ' . $act_doc_count . ' Items )';
                                                        } else {
                                                            $act_doc_text = '( ' . $act_doc_count . ' Item )';
                                                        }
                                                        ?>
                                                        Active Documents <?php echo $act_doc_text; ?>
                                                    </button>
                                                </h2>
                                            </div>

                                            <div id="activeDocumentsTab" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <div class="table-responsive table-outer">
                                                        <div class="table-wrp data-table">
                                                            <?php if (!empty($get_incident_document)) { ?>
                                                                <table class="table table-bordered table-hover table-stripped">
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="text-center">Document Title</th>
                                                                            <th class="text-center">Uploaded By</th>
                                                                            <th class="text-center">Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php foreach ($get_incident_document as $key => $document) { ?>
                                                                            <tr>
                                                                                <td class="text-center">
                                                                                    <?php echo $document['document_title']; ?>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <?php
                                                                                    $user_info = db_get_employee_profile($document['employer_id']);
                                                                                    echo strtoupper($user_info[0]['first_name'] . ' ' . $user_info[0]['last_name']);
                                                                                    ?>
                                                                                    <br>
                                                                                    <?php
                                                                                    echo '<b>Uploaded Date : </b>' . reset_datetime(array('datetime' => $document['uploaded_date'], '_this' => $this));
                                                                                    ?>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <?php
                                                                                    $download_url = $document['file_code'];
                                                                                    $file_name = explode(".", $download_url);
                                                                                    $document_name = $file_name[0];
                                                                                    $document_extension = $file_name[1];
                                                                                    if ($document_extension == 'pdf') {
                                                                                        $print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $document_name . '.pdf';
                                                                                        $preview_url = 'https://docs.google.com/gview?url=' . AWS_S3_BUCKET_URL . $download_url . '&embedded=true';
                                                                                    } else if ($document_extension == 'doc') {
                                                                                        $print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_name . '%2Edoc&wdAccPdf=0';
                                                                                        $preview_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode(AWS_S3_BUCKET_URL . $download_url);
                                                                                    } else if ($document_extension == 'docx') {
                                                                                        $print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_name . '%2Edocx&wdAccPdf=0';
                                                                                        $preview_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode(AWS_S3_BUCKET_URL . $download_url);
                                                                                    } else if (in_array($document_extension, ['jpe', 'jpg', 'jpeg', 'png', 'gif'])) {
                                                                                        $print_url = base_url('incident_reporting_system/print_image/' . $document['id']);
                                                                                        $preview_url = AWS_S3_BUCKET_URL . $download_url;
                                                                                    } else {
                                                                                        $print_url = '';
                                                                                    }
                                                                                    ?>

                                                                                    <a href="javascript:;" class="pull-right print-incident modify-comment-btn" onclick="view_incident_doc(this);" data-preview-url="<?= AWS_S3_BUCKET_URL . $download_url; ?>" data-preview-ext="<?php echo $document_extension; ?>" data-title="<?php echo $document['document_title']; ?>"><i class="fa fa-file"></i> View</a>

                                                                                    <a target="_blank" href="<?php echo $print_url; ?>" class="pull-right print-incident modify-comment-btn"><i class="fa fa-print"></i> Print</a>

                                                                                    <a target="_blank" href="<?php echo base_url('incident_reporting_system/download_incident_document') . '/' . $download_url; ?>" class="pull-right print-incident modify-comment-btn"><i class="fa fa-download"></i> Download</a>

                                                                                    <a href="javascript:void(0)" class="js-edit-document print-incident pull-right modify-comment-btn" data-id="<?php echo $document['id']; ?>" data-url="<?php echo $preview_url; ?>" data-ext="<?php echo $document_extension; ?>" data-title="<?php echo $document['document_title']; ?>"><i class="fa fa-pencil"></i>&nbsp;Edit</a>

                                                                                    <a href="javascript:void(0)" class="js-archive-document pull-right print-incident modify-comment-btn" data-id="<?php echo $document['id']; ?>"><i class="fa fa-archive"></i>&nbsp;Archive</a>
                                                                                </td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                    </tbody>
                                                                </table>
                                                            <?php } ?>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header" id="headingTwo">
                                                    <h2 class="mb-0" style="background-color: #eee;">
                                                        <button class="btn btn-link incident-panal-links" type="button" data-toggle="collapse" data-target="#archivedDocumentsTab" aria-expanded="true" aria-controls="archivedDocumentsTab">
                                                            <?php
                                                            $archived_doc_count = sizeof($get_incident_document_archived);
                                                            $archived_doc_text = '';
                                                            if ($archived_doc_count > 1) {
                                                                $archived_doc_text = '( ' . $archived_doc_count . ' Items )';
                                                            } else {
                                                                $archived_doc_text = '( ' . $archived_doc_count . ' Item )';
                                                            }
                                                            ?>
                                                            Archived Documents <?php echo $archived_doc_text; ?>
                                                        </button>
                                                    </h2>
                                                </div>

                                                <div id="archivedDocumentsTab" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                                    <div class="card-body">
                                                        <div class="table-responsive table-outer">
                                                            <div class="table-wrp data-table">
                                                                <?php if (!empty($get_incident_document_archived)) { ?>
                                                                    <table class="table table-bordered table-hover table-stripped">
                                                                        <thead>
                                                                            <tr>
                                                                                <th class="text-center">Document Title</th>
                                                                                <th class="text-center">Uploaded By</th>
                                                                                <th class="text-center">Action</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php foreach ($get_incident_document_archived as $key => $document) { ?>
                                                                                <tr>
                                                                                    <td class="text-center">
                                                                                        <?php echo $document['document_title']; ?>
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <?php
                                                                                        $user_info = db_get_employee_profile($document['employer_id']);
                                                                                        echo strtoupper($user_info[0]['first_name'] . ' ' . $user_info[0]['last_name']);
                                                                                        ?>
                                                                                        <br>
                                                                                        <?php
                                                                                        echo '<b>Uploaded Date : </b>' . reset_datetime(array('datetime' => $document['uploaded_date'], '_this' => $this));
                                                                                        ?>
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <?php
                                                                                        $download_url = $document['file_code'];
                                                                                        $file_name = explode(".", $download_url);
                                                                                        $document_name = $file_name[0];
                                                                                        $document_extension = $file_name[1];

                                                                                        if ($document_extension == 'pdf') {
                                                                                            $print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $document_name . '.pdf';
                                                                                            $preview_url = 'https://docs.google.com/gview?url=' . AWS_S3_BUCKET_URL . $download_url . '&embedded=true';
                                                                                        } else if ($document_extension == 'doc') {
                                                                                            $print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_name . '%2Edoc&wdAccPdf=0';
                                                                                            $preview_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode(AWS_S3_BUCKET_URL . $download_url);
                                                                                        } else if ($document_extension == 'docx') {
                                                                                            $print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_name . '%2Edocx&wdAccPdf=0';
                                                                                            $preview_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode(AWS_S3_BUCKET_URL . $download_url);
                                                                                        } else if (in_array($document_extension, ['jpe', 'jpg', 'jpeg', 'png', 'gif'])) {
                                                                                            $print_url = base_url('incident_reporting_system/print_image/' . $document['id']);
                                                                                            $preview_url = AWS_S3_BUCKET_URL . $download_url;
                                                                                        } else {
                                                                                            $print_url = '';
                                                                                        }
                                                                                        ?>

                                                                                        <a href="javascript:;" class="pull-right print-incident modify-comment-btn" onclick="view_incident_doc(this);" data-preview-url="<?= AWS_S3_BUCKET_URL . $download_url; ?>" data-preview-ext="<?php echo $document_extension; ?>" data-title="<?php echo $document['document_title']; ?>"><i class="fa fa-file"></i> View</a>

                                                                                        <a target="_blank" href="<?php echo $print_url; ?>" class="pull-right print-incident modify-comment-btn"><i class="fa fa-print"></i> Print</a>

                                                                                        <a target="_blank" href="<?php echo base_url('incident_reporting_system/download_incident_document') . '/' . $download_url; ?>" class="pull-right print-incident modify-comment-btn"><i class="fa fa-download"></i> Download</a>

                                                                                        <a href="javascript:void(0)" class="js-edit-document print-incident pull-right modify-comment-btn" data-id="<?php echo $document['id']; ?>" data-url="<?php echo $preview_url; ?>" data-ext="<?php echo $document_extension; ?>" data-title="<?php echo $document['document_title']; ?>"><i class="fa fa-pencil"></i>&nbsp;Edit</a>

                                                                                        <a href="javascript:void(0)" class="js-active-document pull-right print-incident modify-comment-btn" data-id="<?php echo $document['id']; ?>"><i class="fa fa-archive"></i>&nbsp;Activate</a>
                                                                                    </td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                        </tbody>
                                                                    </table>
                                                                <?php } ?>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <!-- Document Section End -->

                        <!-- Email Section Start -->
                        <?php if ($assigned_incidents[0]['status'] != 'Closed') { ?>
                            <div class="table-responsive table-outer">
                                <div class="panel panel-blue">
                                    <div class="panel-heading incident-panal-heading">
                                        <strong>Compose Message</strong>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="dashboard-conetnt-wrp">
                                                    <div class="table-responsive table-outer">
                                                        <div class="table-wrp data-table">
                                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                <form id="form_new_email" enctype="multipart/form-data" method="post" action="" autocomplete="off">
                                                                    <input type="hidden" id="perform_action" name="perform_action" value="send_email" />
                                                                    <table class="table table-bordered table-hover table-stripped">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td><b>Select Email Type</b></td>
                                                                                <td>
                                                                                    <div class="form-group edit_filter autoheight">
                                                                                        <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                                                            Internal System Email
                                                                                            <input <?php echo !empty($incident_assigned_managers) ? 'checked="checked"' : ''; ?> name="send_type" class="email_type" type="radio" value="system" />
                                                                                            <div class="control__indicator"></div>
                                                                                        </label>
                                                                                        <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                                                            Outside Email
                                                                                            <input <?php echo empty($incident_assigned_managers) ? 'checked="checked"' : ''; ?> class="email_type" name="send_type" type="radio" value="manual" />
                                                                                            <div class="control__indicator"></div>
                                                                                        </label>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>Message To</b> ;</td>
                                                                                <td id="system_email">
                                                                                    <select multiple class="chosen-select" tabindex="8" name='receivers[]' id="receivers">
                                                                                        <?php if (!empty($incident_assigned_managers)) { ?>
                                                                                            <?php foreach ($incident_assigned_managers as $manager) { ?>
                                                                                                <option value="<?php echo $manager['employee_id']; ?>">
                                                                                                    <?php echo $manager['employee_name']; ?>
                                                                                                </option>
                                                                                            <?php } ?>
                                                                                        <?php } else { ?>
                                                                                            <option value="">No User Found</option>
                                                                                        <?php } ?>
                                                                                    </select>
                                                                                </td>
                                                                                <td id="manual_email">
                                                                                    <input type="text" name="manual_email" id="manual_address" value="" class="form-control invoice-fields">
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>Subject</b> <span class="required">*</span></td>
                                                                                <td>
                                                                                    <input type="text" id="subject" name="subject" value="" class="form-control invoice-fields">
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>Attachment</b></td>
                                                                                <td>
                                                                                    <div class="row">
                                                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                                            <a href="javascript:;" class="btn btn-info btn-block show_media_library">Add Library Attachment</a>
                                                                                        </div>
                                                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                                            <a href="javascript:;" class="btn btn-info btn-block show_manual_attachment">Add Manual Attachment</a>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="table-responsive table-outer full-width" style="margin-top: 20px; display: none;" id="email_attachment_list">
                                                                                        <div class="table-wrp data-table">
                                                                                            <table class="table table-bordered table-hover table-stripped">
                                                                                                <thead>
                                                                                                    <tr>
                                                                                                        <th class="text-center">Attachment Title</th>
                                                                                                        <th class="text-center">Attachment Type</th>
                                                                                                        <th class="text-center">Attachment Source</th>
                                                                                                        <th class="text-center">Action</th>
                                                                                                    </tr>
                                                                                                </thead>
                                                                                                <tbody id="attachment_listing_data">

                                                                                                </tbody>
                                                                                            </table>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div style="display: none;" id="email_attachment_files">
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>Message</b> <span class="required">*</span></td>
                                                                                <td>
                                                                                    <textarea class="ckeditor" style="padding:5px; height:200px; width:100%;" class="invoice-fields" name="message" id="message"></textarea>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td colspan="2">
                                                                                    <div class="btn-wrp full-width text-right">
                                                                                        <button type="button" class="btn btn-info incident-panal-button" name="submit" value="submit" id="send_normal_email">Send Email</button>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if (!empty($incident_all_emails)) { ?>
                            <?php $this->load->view('manage_employer/incident_reporting/manager_incident_email_section', $incident_all_emails); ?>
                        <?php } ?>
                        <!-- Email Section End -->

                        <!-- Notes Section Start -->
                        <?php if ($assigned_incidents[0]['status'] != 'Closed') { ?>
                            <div class="table-responsive table-outer">
                                <div class="panel panel-blue">
                                    <div class="panel-heading incident-panal-heading">
                                        <strong>Add New Note</strong>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="universal-form-style-v2">
                                                    <form id="form_new_note" enctype="multipart/form-data" method="post" action="" autocomplete="off">
                                                        <!-- <input type="hidden" id="applicant_sid" name="applicant_sid" value="10"> -->
                                                        <input type="hidden" id="perform_action" name="perform_action" value="add_comment" />
                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                            <div class="form-group autoheight">
                                                                <label class="auto-height">Type: <span class="required">*</span></label>

                                                                <div class="hr-select-dropdown">
                                                                    <select name="response_type" class="form-control" id="note_type">
                                                                        <option value="0">Please Select Note Type </option>
                                                                        <option value="Personal"> Personal Note</option>
                                                                        <option value="Response"> Employee Note</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                            <div class="form-group autoheight">
                                                                <label for="note_txt">Note <span class="required">*</span></label>
                                                                <script type="text/javascript" src="<?php echo site_url('assets/ckeditor/ckeditor.js'); ?>"></script>
                                                                <textarea class="form-control response ckeditor" name="response" rows="8" cols="60" required><?php echo set_value('response'); ?></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                            <div class="form-group autoheight">
                                                                <button type="submit" class="btn btn-info incident-panal-button" name="submit" value="submit">Add Note
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if (!empty($comments)) { ?>
                            <div class="table-responsive full-width table-outer">
                                <div class="panel panel-blue">
                                    <div class="panel-heading incident-panal-heading">
                                        <strong>Related Notes</strong>
                                        <a target="_blank" href="<?php echo base_url('incident_reporting_system/print_and_download/manager/0/comments/1') . '/' . $id; ?>" class="pull-right print-incident modify-comment-btn"><i class="fa fa-print"></i> Print</a>
                                        <a target="_blank" href="<?php echo base_url('incident_reporting_system/print_and_download/manager/0/comments/2') . '/' . $id; ?>" class="pull-right print-incident modify-comment-btn"><i class="fa fa-download"></i> Download</a>
                                    </div>
                                    <div class="panel-body">
                                        <div class="respond">
                                            <?php foreach ($comments as $comment) { ?>
                                                <?php
                                                $name = empty($comment['user2']) ? ucfirst($comment['user1']) : ucfirst($comment['user2']);
                                                $pp = empty($comment['user2']) ? $comment['pp1'] : $comment['pp2'];
                                                $url = empty($pp) ? base_url() . "assets/images/attachment-img.png" : AWS_S3_BUCKET_URL . $pp;
                                                ?>
                                                <article <?php echo empty($comment['user2']) ? '' : 'class="reply"' ?>>
                                                    <figure><img class="img-responsive" src="<?= $url ?>"></figure>
                                                    <div class="text">
                                                        <div class="message-header">
                                                            <div class="message-title">
                                                                <h2><?php echo ($assigned_incidents[0]['report_type'] == 'anonymous' && $comment['emp_id'] != NULL) ? 'Anonymous' : $name . " (" . $comment['response_type'] . ')'; ?></h2>
                                                            </div>
                                                            <ul class="message-option">
                                                                <li>
                                                                    <time><?php echo my_date_format($comment['date_time']); ?></time>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <p><?php echo strip_tags($comment['comment']); ?></p>
                                                    </div>
                                                </article>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <!-- Notes Section End -->

                        <!-- Reassign Manager Section Start -->
                        <?php if ($assigned_incidents[0]['status'] != 'Closed') { ?>
                            <?php if (!empty($incident_managers)) { ?>
                                <div class="table-responsive table-outer">
                                    <div class="panel panel-blue">
                                        <div class="panel-heading incident-panal-heading">
                                            <strong>Reassign Incident</strong>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <form id="form_reassign_manager" enctype="multipart/form-data" method="post" action="<?php echo base_url('incident_reporting_system/reassign_manager'); ?>" autocomplete="off">
                                                    <div class="col-lg-12 col-md-12 col-xl-12 col-sm-12">
                                                        <input type="hidden" name="perform_action" value="reassign_manager">
                                                        <input type="hidden" name="incident_sid" value="<?php echo $id; ?>">

                                                        <div class="row">
                                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                <label class="auto-height">Please select Employee to Reassign this incident:</label>
                                                                <select multiple class="chosen-select" tabindex="8" name='reassign_manager[]' id="reassign_manager">
                                                                    <?php foreach ($incident_managers as $manager) {
                                                                        // Skip if employee id is same as reporter idea
                                                                        if ($manager['employee_id'] == $incidentReporterId) continue;
                                                                    ?>
                                                                        <option value="<?php echo $manager['employee_id']; ?>"><?php echo $manager['employee_name']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                                <div class="form-group autoheight btn-wrp full-width text-right">
                                                                    <button type="submit" class="btn btn-info incident-panal-button" style="margin-top: 10px;">Reassign Incident</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } ?>
                        <!-- Reassign Manager Section End -->

                    <?php } else { ?>
                        <div id="show_no_jobs" class="table-wrp">
                            <span class="applicant-not-found">No Incident Assigned!</span>
                        </div>
                    <?php } ?>
                    <!-- Document Section End -->
                        </div>
            </div>
        </div>
    </div>

    <!-- Update Document Section Start -->
    <div id="edit_incident_document" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-bg">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Incident Document</h4>
                </div>
                <div class="modal-body">
                    <div class="table-responsive table-outer">
                        <div class="panel panel-blue">
                            <div class="panel-heading">
                                <b>Edit Document</b>
                            </div>
                            <div class="panel-body">
                                <input type="hidden" id="update_document_sid" value="" />
                                <div class="row">
                                    <div class="field-row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 form-wrp">
                                            <div class="form-group edit_filter autoheight">
                                                <label>Update</label>
                                                <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                    Both
                                                    <input checked="checked" class="update_document_type" type="radio" name="update_document_type" value="both" />
                                                    <div class="control__indicator"></div>
                                                </label>
                                                <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                    <input class="update_document_type" type="radio" name="update_document_type" value="title" />
                                                    <div class="control__indicator"></div>
                                                    Title
                                                </label>
                                                <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                    Document
                                                    <input class="update_document_type" type="radio" name="update_document_type" value="document" />
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                            <div class="form-group autoheight" id="only_doc_title">
                                                <label for="document_title">Document Title <span class="required">*</span></label>
                                                <input type="text" name="document_title" value="" class="form-control" id="update_doc_title">
                                            </div>
                                            <div class="form-group autoheight" id="only_document">
                                                <label>Upload Document <span class="required">*</span></label>
                                                <div class="upload-file form-control" style="margin-bottom:10px;">
                                                    <span class="selected-file" id="name_edit_upload_document"></span>
                                                    <input type="file" name="edit_upload_document" id="edit_upload_document" onchange="check_edit_document('edit_upload_document')">
                                                    <a href="javascript:;">Choose File</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 text-right">
                                    <button type="button" class="btn btn-info" id="save_updated_doc">Edit Document</button>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-blue">
                            <div class="panel-heading">
                                <b>Previous Uploaded Document</b>
                            </div>
                            <div class="panel-body" id="document_modal_body">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    <!-- Update Document Section End -->

    <!-- Update Media Section Start -->
    <div id="edit_incident_video" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-bg">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Incident Video</h4>
                </div>
                <div class="modal-body">
                    <div class="table-responsive table-outer">
                        <div class="panel panel-blue">
                            <div class="panel-heading">
                                <b>Edit Video</b>
                            </div>
                            <div class="panel-body">
                                <input type="hidden" id="update_video_sid" value="" />

                                <div class="form-group edit_filter autoheight">
                                    <label>Update</label>
                                    <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                        Both
                                        <input checked="checked" class="update_type" type="radio" name="update_type" id="update_option" value="both" />
                                        <div class="control__indicator"></div>
                                    </label>
                                    <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                        <input class="update_type" type="radio" name="update_type" value="title" />
                                        <div class="control__indicator"></div>
                                        Title
                                    </label>
                                    <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                        Video
                                        <input class="update_type" type="radio" name="update_type" value="video" />
                                        <div class="control__indicator"></div>
                                    </label>

                                </div>

                                <div class="form-group edit_filter autoheight" id="only_video_select">
                                    <?php $field_name = 'video_source' ?>
                                    <?php echo form_label('Video Source', $field_name); ?>
                                    <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                        <?php echo YOUTUBE_VIDEO; ?>
                                        <input checked="checked" class="update_video_source" id="update_media_option" type="radio" name="update_video_source" value="youtube" />
                                        <div class="control__indicator"></div>
                                    </label>
                                    <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                        <?php echo VIMEO_VIDEO; ?>
                                        <input class="update_video_source" type="radio" name="update_video_source" value="vimeo" />
                                        <div class="control__indicator"></div>
                                    </label>
                                    <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                        <?php echo UPLOAD_VIDEO; ?>
                                        <input class="update_video_source" type="radio" name="update_video_source" value="upload_video" />
                                        <div class="control__indicator"></div>
                                    </label>
                                    <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                        <?php echo UPLOAD_AUDIO; ?>
                                        <input class="update_video_source" type="radio" name="update_video_source" value="upload_audio" />
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>

                                <div class="row" id="only_title">
                                    <div class="field-row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 form-wrp">
                                            <div class="form-group autoheight">
                                                <label for="video_id">Video Title <span class="required">*</span></label>
                                                <input type="text" name="upload_video_title" class="form-control" id="upload_video_title">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" id="only_video">
                                    <div class="field-row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 form-wrp">
                                            <div class="form-group autoheight" id="update_yt_vm_video_container">
                                                <label for="video_id">Video Url <span class="required">*</span></label>
                                                <input type="text" name="video_id" value="" class="form-control" id="update_video_id">
                                            </div>
                                            <div class="form-group autoheight" id="update_up_video_container">
                                                <label>Upload Video <span class="required">*</span></label>
                                                <div class="upload-file form-control" style="margin-bottom:10px;">
                                                    <span class="selected-file" id="name_update_video"></span>
                                                    <input type="file" name="video_upload" id="update_video" onchange="check_update_video_file('update_video')">
                                                    <a href="javascript:;">Choose Video</a>
                                                </div>
                                            </div>
                                            <div class="form-group autoheight" id="update_up_audio_container">
                                                <label>Upload Audio <span class="required">*</span></label>
                                                <div class="upload-file form-control" style="margin-bottom:10px;">
                                                    <span class="selected-file" id="name_update_audio"></span>
                                                    <input type="file" name="audio_upload" id="update_audio" onchange="check_update_audio_file('update_audio')">
                                                    <a href="javascript:;">Choose Audio</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 text-right">
                                    <button type="button" class="btn btn-info" id="save_updated_video">Edit Video</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    <!-- Update Media Section End -->

    <!-- View Video Section Start -->
    <div id="view_incident_video" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="video">
            <div class="modal-content">
                <div class="modal-header modal-header-bg">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close_media_header" video-source="" onclick="stop_media(this);"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="video_modal_title"></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="well well-sm">
                                <div class="embed-responsive embed-responsive-16by9">
                                    <div id="youtube-section" style="display:none;">
                                        <div id="youtube-video-placeholder" class="embed-responsive-item">
                                        </div>
                                    </div>
                                    <div id="vimeo-section" style="display:none;">
                                        <div id="vimeo-video-placeholder"></div>
                                    </div>
                                    <div id="video-section" style="display:none;">
                                        <video id="my-video" controls></video>
                                    </div>
                                    <div id="audio-section" style="display:none;">
                                        <audio id="my-audio" controls></audio>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info incident-panal-button" data-dismiss="modal" id="close_media_footer" onclick="stop_media(this);">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- View Video Section End -->

    <!-- View Document Section Start -->
    <div id="document_modal" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-bg">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="document_modal_title"></h4>
                </div>
                <div id="view_document_modal_body" class="modal-body">
                    ...
                </div>
                <div id="document_modal_footer" class="modal-footer">
                    <button type="button" class="btn btn-info incident-panal-button" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- View Document Section End -->

    <!-- Send Email Section Start -->
    <div id="send_email_modal" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-header-bg">
                    <button type="button" class="close email_pop_up_back_to_compose_email" btn-from="main" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="send_email_pop_up_title"></h4>
                </div>
                <div class="modal-body">
                    <div id="pop_up_email_compose_container">
                        <form id="form_send_email" enctype="multipart/form-data" method="post" action="" autocomplete="off">
                            <input type="hidden" id="perform_action" name="perform_action" value="send_email" />
                            <input type="hidden" id="send_email_type" name="send_type" value="" />
                            <input type="hidden" id="send_email_user" name="" value="" />

                            <table class="table table-bordered table-hover table-stripped">
                                <tbody>
                                    <tr>
                                        <td><b>Message To</b></td>
                                        <td>
                                            <input type="text" id="send_email_address" value="" class="form-control invoice-fields" readonly="">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>Subject</b> <span class="required">*</span></td>
                                        <td>
                                            <input type="text" id="send_email_subject" name="subject" value="" class="form-control invoice-fields">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>Attachment</b></td>
                                        <td>
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <a href="javascript:;" class="btn btn-info btn-block attachment_pop_up" attachment-type="library">Add Library Attachment</a>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <a href="javascript:;" class="btn btn-info btn-block attachment_pop_up" attachment-type="manual">Add Manual Attachment</a>
                                                </div>
                                            </div>

                                            <div class="table-responsive table-outer full-width" style="margin-top: 20px; display: none;" id="pop_up_email_attachment_list">
                                                <div class="table-wrp data-table">
                                                    <table class="table table-bordered table-hover table-stripped">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">Attachment Title</th>
                                                                <th class="text-center">Attachment Type</th>
                                                                <th class="text-center">Attachment Source</th>
                                                                <th class="text-center">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="pop_up_attachment_listing_data">

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div style="display: none;" id="pop_up_email_attachment_files">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>Message</b> <span class="required">*</span></td>
                                        <td>
                                            <textarea class="ckeditor" style="padding:5px; height:200px; width:100%;" class="invoice-fields" name="message" id="send_email_message"></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <div class="btn-wrp full-width text-right">
                                                <button type="button" class="btn btn-black incident-panal-button" data-dismiss="modal">Cancel</button>
                                                <button type="button" class="btn btn-info incident-panal-button" id="send_pop_up_email" name="submit" value="submit">Send Email</button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>

                    <div id="pop_up_attachment_library_container" style="display: none;">
                        <div class="table-responsive table-outer" id="show_pop_up_library_item">
                            <div class="text-right" style="margin-top:15px;">
                                <button type="button" class="btn btn-info incident-panal-button email_pop_up_back_to_compose_email" btn-from="library" style="margin-bottom: 20px;">Back To Compose Email</button>
                            </div>

                            <div class="table-wrp data-table">
                                <table class="table table-bordered table-hover table-stripped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Title</th>
                                            <th class="text-center">Category</th>
                                            <th class="text-center">Type</th>
                                            <th class="text-center" colspan="2">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($library_documets) || !empty($library_media)) { ?>
                                            <?php foreach ($library_documets as $d_key => $document) { ?>
                                                <tr>
                                                    <?php
                                                    $document_url       = '';
                                                    $document_category  = '';
                                                    $document_type      = strtolower($document['type']);
                                                    $document_path      = $document['file_code'];
                                                    $document_title     = $document['document_title'];
                                                    $item_type          = "document";

                                                    if ($document_type == 'pdf') {
                                                        $document_category = 'PDF Document';
                                                        $document_url = 'https://docs.google.com/gview?url=' . AWS_S3_BUCKET_URL . $document_path . '&embedded=true';
                                                    } else if (in_array($document_type, ['doc', 'docx'])) {
                                                        $document_category = 'Word Document';
                                                        $document_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode(AWS_S3_BUCKET_URL . $document_path);
                                                    } else if (in_array($document_type, ['jpe', 'jpg', 'jpeg', 'png', 'gif'])) {
                                                        $document_category = 'Image';
                                                        $document_url = AWS_S3_BUCKET_URL . $document_path;
                                                        $item_type    = "image";
                                                    }
                                                    ?>

                                                    <td class="text-center"><?php echo $document_title; ?></td>
                                                    <td class="text-center">Document</td>
                                                    <td class="text-center">
                                                        <?php echo $document_category; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="javascript:;" class="btn btn-block btn-info" onclick="view_pop_up_library_item(this);" item-category="document" item-title="<?php echo $document_title; ?>" item-type="<?php echo $item_type; ?>" item-url="<?php echo $document_url; ?>">View Document</a>
                                                    </td>
                                                    <td class="text-center">
                                                        <label class="control control--checkbox" style="margin-left:10px; margin-top:10px;">
                                                            <input class="email_pop_up_select_lib_item" id="pop_up_doc_key_d_<?php echo $document['id']; ?>" type="checkbox" item-category="Document" item-title="<?php echo $document_title; ?>" item-type="<?php echo $item_type; ?>" item-sid="d_<?php echo $document['id']; ?>" />
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <?php foreach ($library_media as $key => $media) { ?>
                                                <tr>
                                                    <?php
                                                    $media_url      = '';
                                                    $media_category = '';
                                                    $media_btn_text = 'Watch Video';
                                                    $media_title    = $media['video_title'];
                                                    $media_type     = strtolower($media['video_type']);

                                                    if ($media_type == 'youtube') {
                                                        $media_category = 'Youtube';
                                                        $media_url = $media['video_url'];
                                                    } else if ($media_type == 'vimeo') {
                                                        $media_category = 'Vimeo';
                                                        $media_url = $media['video_url'];
                                                    } else if ($media_type == "upload_video") {
                                                        $media_category = 'Upload Video';
                                                        $media_url = base_url() . 'assets/uploaded_videos/incident_videos/' . $media['video_url'];
                                                    } else if ($media_type == "upload_audio") {
                                                        $media_category = 'Upload Audio';
                                                        $media_btn_text = 'Listen Audio';
                                                        $media_url = base_url() . 'assets/uploaded_videos/incident_videos/' . $media['video_url'];
                                                    }
                                                    ?>

                                                    <td class="text-center"><?php echo $media_title; ?></td>
                                                    <td class="text-center">Media</td>
                                                    <td class="text-center"><?php echo $media_category; ?></td>
                                                    <td class="text-center">
                                                        <a href="javascript:;" class="btn btn-block btn-info" onclick="view_pop_up_library_item(this);" item-category="media" item-title="<?php echo $media_title; ?>" item-type="<?php echo $media_type; ?>" item-url="<?php echo $media_url; ?>"><?php echo $media_btn_text; ?></a>
                                                    </td>
                                                    <td class="text-center">
                                                        <label class="control control--checkbox" style="margin-left:10px; margin-top:10px;">
                                                            <input class="email_pop_up_select_lib_item" id="pop_up_med_key_m_<?php echo $media['sid']; ?>" type="checkbox" item-category="Media" item-title="<?php echo $media_title; ?>" item-type="<?php echo $media_type; ?>" item-sid="m_<?php echo $media['sid']; ?>" />
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <tr>
                                                <td colspan="4">
                                                    <h3 class="text-center">
                                                        No Library Item Found
                                                    </h3>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="text-right" style="margin-top:15px;">
                                <button type="button" class="btn btn-info incident-panal-button email_pop_up_back_to_compose_email" btn-from="library">Back To Compose Email</button>
                            </div>
                        </div>
                        <div id="view_pop_up_library_item" style="display:none;">
                            <h3 id="pop_up_library_item_title"></h3>
                            <hr>
                            <div class="embed-responsive embed-responsive-16by9">
                                <div id="email-pop-up-youtube-container" style="display:none;">
                                    <div id="email-pop-up-youtube-iframe-holder" class="embed-responsive-item">
                                    </div>
                                </div>
                                <div id="email-pop-up-vimeo-container" style="display:none;">
                                    <div id="email-pop-up-vimeo-iframe-holder" class="embed-responsive-item">
                                    </div>
                                </div>
                                <div id="email-pop-up-video-container" style="display:none;">
                                    <div id="email-pop-up-video-player-holder" class="embed-responsive-item">
                                    </div>
                                </div>
                                <div id="email-pop-up-audio-container" style="display:none;">
                                    <div id="email-pop-up-audio-player-holder" class="embed-responsive-item">
                                    </div>
                                </div>
                                <div id="email-pop-up-document-container" style="display:none;">
                                    <div id="email-pop-up-document-iframe-holder" class="embed-responsive-item">
                                    </div>
                                </div>
                            </div>
                            <div class="text-right" style="margin-top:15px;">
                                <button type="button" class="btn btn-info incident-panal-button email_pop_up_back_to_library">Back To Library</button>
                            </div>
                        </div>
                    </div>
                    <div id="pop_up_manual_attachment_container" style="display: none;">
                        <div class="form-group edit_filter autoheight">
                            <label for="attachment_type">Select Attachment Type</label>
                            <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                <?php echo YOUTUBE_VIDEO; ?>
                                <input id="default_manual_pop_up" class="pop_up_attach_item_source" type="radio" name="pop_up_attach_item_source" value="youtube" checked="checked" />
                                <div class="control__indicator"></div>
                            </label>
                            <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                <?php echo VIMEO_VIDEO; ?>
                                <input class="pop_up_attach_item_source" type="radio" name="pop_up_attach_item_source" value="vimeo" />
                                <div class="control__indicator"></div>
                            </label>
                            <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                <?php echo UPLOAD_VIDEO; ?>
                                <input class="pop_up_attach_item_source" type="radio" name="pop_up_attach_item_source" value="upload_video" />
                                <div class="control__indicator"></div>
                            </label>
                            <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                <?php echo UPLOAD_AUDIO; ?>
                                <input class="pop_up_attach_item_source" type="radio" name="pop_up_attach_item_source" value="upload_audio" />
                                <div class="control__indicator"></div>
                            </label>
                            <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                Document
                                <input class="pop_up_attach_item_source" type="radio" name="pop_up_attach_item_source" value="upload_document" />
                                <div class="control__indicator"></div>
                            </label>
                        </div>

                        <div class="row">
                            <div class="field-row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 form-wrp">
                                    <div class="form-group autoheight">
                                        <label for="attachment_title">Attachment Title <span class="required">*</span></label>
                                        <input type="text" name="attachment_title" class="form-control" id="pop_up_attachment_item_title">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" id="only_video">
                            <div class="field-row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 form-wrp">
                                    <div class="form-group autoheight" id="pop_up_attachment_yt_vm_video_input_container">
                                        <label for="video_id">Video Url <span class="required">*</span></label>
                                        <input type="text" name="pop_up_attach_social_video" value="" class="form-control" id="pop_up_attach_social_video">
                                    </div>
                                    <div class="form-group autoheight" id="pop_up_attachment_upload_video_input_container">
                                        <label>Attach Video <span class="required">*</span></label>
                                        <div class="upload-file form-control" style="margin-bottom:10px;">
                                            <span class="selected-file" id="name_pop_up_attach_video"></span>
                                            <input type="file" name="pop_up_attach_video" id="pop_up_attach_video" onchange="pop_up_check_attach_video('pop_up_attach_video')">
                                            <a href="javascript:;">Choose Video</a>
                                        </div>
                                    </div>
                                    <div class="form-group autoheight" id="pop_up_attachment_upload_audio_input_container">
                                        <label>Attach Audio <span class="required">*</span></label>
                                        <div class="upload-file form-control" style="margin-bottom:10px;">
                                            <span class="selected-file" id="name_pop_up_attach_audio"></span>
                                            <input type="file" name="pop_up_attach_audio" id="pop_up_attach_audio" onchange="pop_up_check_attach_audio('pop_up_attach_audio')">
                                            <a href="javascript:;">Choose Audio</a>
                                        </div>
                                    </div>
                                    <div class="form-group autoheight" id="pop_up_attachment_upload_document_input_container">
                                        <label>Attach Document <span class="required">*</span></label>
                                        <div class="upload-file form-control" style="margin-bottom:10px;">
                                            <span class="selected-file" id="name_pop_up_attach_document"></span>
                                            <input type="file" name="pop_up_attach_document" id="pop_up_attach_document" onchange="pop_up_check_attach_document('pop_up_attach_document')">
                                            <a href="javascript:;">Choose Document</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="field-row">
                                <div class="col-lg-12 text-right">
                                    <button type="button" class="btn btn-info incident-panal-button email_pop_up_back_to_compose_email" btn-from="manual">Back To Compose Email</button>
                                    <button type="button" class="btn btn-info incident-panal-button" id="pop_up_save_attach_item">Save Attachment</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Send Email Section End -->

    <!-- Attachment Library Section Start -->
    <div id="attachment_library_modal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content full-width">
                <div class="modal-header modal-header-bg">
                    <button type="button" class="close back_to_library" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="library_item_title">Attachment Library</h4>
                </div>
                <div class="modal-body full-width">
                    <div class="table-responsive table-outer" id="show_library_item">
                        <div class="table-wrp data-table">
                            <table class="table table-bordered table-hover table-stripped">
                                <thead>
                                    <tr>
                                        <th class="text-center">Title</th>
                                        <th class="text-center">Category</th>
                                        <th class="text-center">Type</th>
                                        <th class="text-center" colspan="2">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($library_documets) || !empty($library_media)) { ?>
                                        <?php foreach ($library_documets as $d_key => $document) { ?>
                                            <tr>
                                                <?php
                                                $document_url       = '';
                                                $document_category  = '';
                                                $document_type      = strtolower($document['type']);
                                                $document_path      = $document['file_code'];
                                                $document_title     = $document['document_title'];
                                                $item_type          = "document";

                                                if ($document_type == 'pdf') {
                                                    $document_category = 'PDF Document';
                                                    $document_url = 'https://docs.google.com/gview?url=' . AWS_S3_BUCKET_URL . $document_path . '&embedded=true';
                                                } else if (in_array($document_type, ['doc', 'docx'])) {
                                                    $document_category = 'Word Document';
                                                    $document_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode(AWS_S3_BUCKET_URL . $document_path);
                                                } else if (in_array($document_type, ['jpe', 'jpg', 'jpeg', 'png', 'gif'])) {
                                                    $document_category = 'Image';
                                                    $document_url = AWS_S3_BUCKET_URL . $document_path;
                                                    $item_type    = "image";
                                                }
                                                ?>

                                                <td class="text-center"><?php echo $document_title; ?></td>
                                                <td class="text-center">Document</td>
                                                <td class="text-center">
                                                    <?php echo $document_category; ?>
                                                </td>
                                                <td class="text-center">
                                                    <a href="javascript:;" class="btn btn-block btn-info" onclick="view_library_item(this);" item-category="document" item-title="<?php echo $document_title; ?>" item-type="<?php echo $item_type; ?>" item-url="<?php echo $document_url; ?>">View Document</a>
                                                </td>
                                                <td class="text-center">
                                                    <label class="control control--checkbox" style="margin-left:10px; margin-top:10px;">
                                                        <input class="select_lib_item" id="doc_key_d_<?php echo $document['id']; ?>" type="checkbox" item-category="Document" item-title="<?php echo $document_title; ?>" item-type="<?php echo $item_type; ?>" item-sid="d_<?php echo $document['id']; ?>" />
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <?php foreach ($library_media as $key => $media) { ?>
                                            <tr>
                                                <?php
                                                $media_url      = '';
                                                $media_category = '';
                                                $media_btn_text = 'Watch Video';
                                                $media_title    = $media['video_title'];
                                                $media_type     = strtolower($media['video_type']);

                                                if ($media_type == 'youtube') {
                                                    $media_category = 'Youtube';
                                                    $media_url = $media['video_url'];
                                                } else if ($media_type == 'vimeo') {
                                                    $media_category = 'Vimeo';
                                                    $media_url = $media['video_url'];
                                                } else if ($media_type == "upload_video") {
                                                    $media_category = 'Upload Video';
                                                    $media_url = base_url() . 'assets/uploaded_videos/incident_videos/' . $media['video_url'];
                                                } else if ($media_type == "upload_audio") {
                                                    $media_category = 'Upload Audio';
                                                    $media_btn_text = 'Listen Audio';
                                                    $media_url = base_url() . 'assets/uploaded_videos/incident_videos/' . $media['video_url'];
                                                }
                                                ?>

                                                <td class="text-center"><?php echo $media_title; ?></td>
                                                <td class="text-center">Media</td>
                                                <td class="text-center"><?php echo $media_category; ?></td>
                                                <td class="text-center">
                                                    <a href="javascript:;" class="btn btn-block btn-info" onclick="view_library_item(this);" item-category="media" item-title="<?php echo $media_title; ?>" item-type="<?php echo $media_type; ?>" item-url="<?php echo $media_url; ?>"><?php echo $media_btn_text; ?></a>
                                                </td>
                                                <td class="text-center">
                                                    <label class="control control--checkbox" style="margin-left:10px; margin-top:10px;">
                                                        <input class="select_lib_item" id="med_key_m_<?php echo $media['sid']; ?>" type="checkbox" item-category="Media" item-title="<?php echo $media_title; ?>" item-type="<?php echo $media_type; ?>" item-sid="m_<?php echo $media['sid']; ?>" />
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <tr>
                                            <td colspan="4">
                                                <h3 class="text-center">
                                                    No Library Item Found
                                                </h3>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="view_library_item" style="display:none;">
                        <div class="embed-responsive embed-responsive-16by9">
                            <div id="library-youtube-section" style="display:none;">
                                <div id="library-youtube-placeholder" class="embed-responsive-item">
                                </div>
                            </div>
                            <div id="library-vimeo-section" style="display:none;">
                                <div id="library-vimeo-placeholder" class="embed-responsive-item">
                                </div>
                            </div>
                            <div id="library-video-section" style="display:none;">
                                <div id="library-video-placeholder" class="embed-responsive-item">
                                </div>
                            </div>
                            <div id="library-audio-section" style="display:none;">
                                <div id="library-audio-placeholder" class="embed-responsive-item">
                                </div>
                            </div>
                            <div id="library-document-section" style="display:none;">
                                <div id="library-document-placeholder" class="embed-responsive-item">
                                </div>
                            </div>
                        </div>
                        <div class="text-right" style="margin-top:15px;">
                            <button type="button" class="btn btn-info incident-panal-button back_to_library">Back To Library</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer full-width">
                    <button type="button" class="btn btn-info incident-panal-button" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Attachment Library Section End -->

    <!-- Manual Attachment Section Start -->
    <div id="manual_attachment_modal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-bg">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Manual Attachment</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group edit_filter autoheight">
                        <label for="attachment_type">Select Attachment Type</label>
                        <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                            <?php echo YOUTUBE_VIDEO; ?>
                            <input id="default_manual_select" class="attach_item_source" type="radio" name="attach_item_source" value="youtube" checked="checked" />
                            <div class="control__indicator"></div>
                        </label>
                        <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                            <?php echo VIMEO_VIDEO; ?>
                            <input class="attach_item_source" type="radio" name="attach_item_source" value="vimeo" />
                            <div class="control__indicator"></div>
                        </label>
                        <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                            <?php echo UPLOAD_VIDEO; ?>
                            <input class="attach_item_source" type="radio" name="attach_item_source" value="upload_video" />
                            <div class="control__indicator"></div>
                        </label>
                        <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                            <?php echo UPLOAD_AUDIO; ?>
                            <input class="attach_item_source" type="radio" name="attach_item_source" value="upload_audio" />
                            <div class="control__indicator"></div>
                        </label>
                        <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                            Document
                            <input class="attach_item_source" type="radio" name="attach_item_source" value="upload_document" />
                            <div class="control__indicator"></div>
                        </label>
                    </div>

                    <div class="row">
                        <div class="field-row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 form-wrp">
                                <div class="form-group autoheight">
                                    <label for="attachment_title">Attachment Title <span class="required">*</span></label>
                                    <input type="text" name="attachment_title" class="form-control" id="attachment_item_title">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="only_video">
                        <div class="field-row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 form-wrp">
                                <div class="form-group autoheight" id="attachment_yt_vm_video_container">
                                    <label for="video_id">Video Url <span class="required">*</span></label>
                                    <input type="text" name="attach_social_video" value="" class="form-control" id="attach_social_video">
                                </div>
                                <div class="form-group autoheight" id="attachment_video_container">
                                    <label>Attach Video <span class="required">*</span></label>
                                    <div class="upload-file form-control" style="margin-bottom:10px;">
                                        <span class="selected-file" id="name_attach_video"></span>
                                        <input type="file" name="attach_video" id="attach_video" onchange="check_attach_video('attach_video')">
                                        <a href="javascript:;">Choose Video</a>
                                    </div>
                                </div>
                                <div class="form-group autoheight" id="attachment_audio_container">
                                    <label>Attach Audio <span class="required">*</span></label>
                                    <div class="upload-file form-control" style="margin-bottom:10px;">
                                        <span class="selected-file" id="name_attach_audio"></span>
                                        <input type="file" name="attach_audio" id="attach_audio" onchange="check_attach_audio('attach_audio')">
                                        <a href="javascript:;">Choose Audio</a>
                                    </div>
                                </div>
                                <div class="form-group autoheight" id="attachment_document_container">
                                    <label>Attach Document <span class="required">*</span></label>
                                    <div class="upload-file form-control" style="margin-bottom:10px;">
                                        <span class="selected-file" id="name_attach_document"></span>
                                        <input type="file" name="attach_document" id="attach_document" onchange="check_attach_document('attach_document')">
                                        <a href="javascript:;">Choose Document</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="field-row">
                            <div class="col-lg-12 text-right">
                                <button type="button" class="btn btn-info" id="save_attach_item">Save Attachment</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info incident-panal-button" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Manual Attachment Section End -->

    <!-- View Email Attachment Section Start -->
    <div id="view_media_document_modal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content full-width">
                <div class="modal-header modal-header-bg">
                    <button type="button" class="close close-current-item" data-dismiss="modal" aria-label="Close" id="close_media_document_modal_up"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="view_item_title"></h4>
                </div>
                <div class="modal-body full-width">
                    <div class="embed-responsive embed-responsive-16by9">
                        <div id="youtube-container" style="display:none;">
                            <div id="youtube-iframe-holder" class="embed-responsive-item">
                            </div>
                        </div>
                        <div id="vimeo-container" style="display:none;">
                            <div id="vimeo-iframe-holder" class="embed-responsive-item">
                            </div>
                        </div>
                        <div id="video-container" style="display:none;">
                            <div id="video-player-holder" class="embed-responsive-item">
                            </div>
                        </div>
                        <div id="audio-container" style="display:none;">
                            <div id="audio-player-holder" class="embed-responsive-item">
                            </div>
                        </div>
                        <div id="document-container" style="display:none;">
                            <div id="document-iframe-holder" class="embed-responsive-item">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer full-width">
                    <button type="button" class="btn btn-info incident-panal-button close-current-item" data-dismiss="modal" id="close_media_document_modal_down" file-type="">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- View Email Attachment Section End -->

    <!-- Email Attachment Loader Start -->
    <div id="attachment_loader" class="text-center my_loader" style="display: none;">
        <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
        <div class="loader-icon-box">
            <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
            <div class="loader-text" style="display:block; margin-top: 35px;">Please wait while we are uploading email attachment...
            </div>
        </div>
    </div>
    <!-- Email Attachment Loader End -->


    <script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
    <script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css" />
    <script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>

    <script type="application/javascript">
        $(document).on('click', '.jsMarkItResolved', function() {
            var iid = $(this).attr('data-incidentId');
            alertify.confirm('Resolved?', 'Are you sure, you want to mark this incident resolved?', function() {
                $.ajax({
                    type: 'POST',
                    url: '<?= base_url('incident_reporting_system/mark_resolved') ?>',
                    data: {
                        id: iid
                    },
                    success: function(response) {
                        if (response == 'Done') {
                            window.location.href = window.location.href;
                        }
                    },
                    error: function() {

                    }
                });
            }, function() {

            });
        });
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }

        var field_name = 0;
        var config = { // Multiselect
            '.chosen-select': {}
        }

        for (var selector in config) {
            $(selector).chosen(config[selector]);
        }

        $(document).ready(function() {
            var itemsArr = new Array();
            <?php if (!empty($incident_assigned_managers)) { ?>
                $('#manual_email').hide();
            <?php } else if (empty($incident_assigned_managers)) { ?>
                $('#system_email').hide();
            <?php } ?>

            $('#up_video_container input').prop('disabled', true);
            $('#up_video_container').hide();

            $('#up_audio_container input').prop('disabled', true);
            $('#up_audio_container').hide();

            $('#view_0').trigger('click');
            $('.js-main-coll').on('shown.bs.collapse', function(e) {
                e.stopPropagation();
                $(this).parent().find(".js-main-gly").removeClass("glyphicon-plus").addClass("glyphicon-minus");
            }).on('hidden.bs.collapse', function() {
                $(this).parent().find(".js-main-gly").removeClass("glyphicon-minus").addClass("glyphicon-plus");
            });

            $('.js-child-coll').on('shown.bs.collapse', function(e) {
                e.stopPropagation();
                $(this).parent().find(".js-child-gly").removeClass("glyphicon-plus").addClass("glyphicon-minus");
            }).on('hidden.bs.collapse', function() {
                $(this).parent().find(".js-child-gly").removeClass("glyphicon-minus").addClass("glyphicon-plus");
            });

            $('#attachment_video_container input').prop('disabled', true);
            $('#attachment_video_container').hide();

            $('#attachment_audio_container input').prop('disabled', true);
            $('#attachment_audio_container').hide();

            $('#attachment_document_container input').prop('disabled', true);
            $('#attachment_document_container').hide();

            $('#pop_up_attachment_upload_video_input_container input').prop('disabled', true);
            $('#pop_up_attachment_upload_video_input_container').hide();

            $('#pop_up_attachment_upload_audio_input_container input').prop('disabled', true);
            $('#pop_up_attachment_upload_audio_input_container').hide();

            $('#pop_up_attachment_upload_document_input_container input').prop('disabled', true);
            $('#pop_up_attachment_upload_document_input_container').hide();
        });

        // Witness JS Start
        $("#employee_to_witness").on('change', function() {
            var selected_witness = $("#employee_to_witness").val();
            var selected_witness_info = selected_witness.split(',');
            var selected_witness_name = selected_witness_info[0];
            var selected_witness_email = selected_witness_info[1];
            var selected_witness_phone = selected_witness_info[2];
            var current = $('#employee_to_witness').attr('src');


            add_new_witrness('inner');
            $("#witnesses_name_0_" + current).val(selected_witness_name);
            $("#witnesses_phone_0_" + current).val(selected_witness_phone);
            $("#witnesses_email_0_" + current).val(selected_witness_email);
            $("#witnesses_title_0_" + current).val('Office Colleague');
            $("#employee_to_witness option[value='" + selected_witness + "']").remove();
        });

        function add_new_witrness(section) {

            var current;
            var div_color;
            var read_only;
            var field_color;
            var div_id;
            var btn_id;
            var delete_witness;

            if (section == 'inner') {
                readonly = 'readonly';
                current = $('#employee_to_witness').attr('src');
                div_id = 'witness_inner_' + current;
                field_id = 0 + "_" + current;
                delete_witness = 0;
            } else if (section == 'outter') {
                readonly = '';
                current = $('#add_new_witrness').attr('src');
                div_id = 'witness_outter_' + current;
                field_id = 1 + "_" + current;
                delete_witness = 1;
            }


            if (current % 2 != 0) {
                div_color = '#eee';
                field_color = '#fff !important';
            }

            var witness = '';
            witness += '<div class="full-width" id="' + div_id + '" src="' + current + '" style="border-top: 1px solid #ddd; padding :5px 0px; background-color:' + div_color + ';">';
            if (section == 'inner') {
                witness += '<input type="hidden" name="witnesses[' + field_name + '][type]" value="employee">';
            } else if (section == 'outter') {
                witness += '<input type="hidden" name="witnesses[' + field_name + '][type]" value="others">';
            }

            witness += '<div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">';
            witness += '<div class="form-group">';
            witness += '<label class="auto-height">';
            witness += 'WITNESS FULL NAME : ';
            witness += '<span class="required">*</span>';
            witness += '</label>';
            witness += '<input id="witnesses_name_' + field_id + '" type="text" name="witnesses[' + field_name + '][full_name]" value="" ' + readonly + ' class="form-control" style=" background-color:' + field_color + ';">';
            witness += '</div>';
            witness += '</div>';

            witness += '<div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">';
            witness += '<div class="form-group">';
            witness += '<label class="auto-height">';
            witness += 'WITNESS TELEPHONE NUMBER : ';
            witness += '<span class="required">*</span>';
            witness += '</label>';
            witness += '<input id="witnesses_phone_' + field_id + '" type="text" name="witnesses[' + field_name + '][phone]" value="" class="form-control" style=" background-color:' + field_color + ';">';
            witness += '</div>';
            witness += '</div>';

            witness += '<div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">';
            witness += '<div class="form-group">';
            witness += '<label class="auto-height">';
            witness += 'WITNESS EMAIL : ';
            witness += '<span class="required">*</span>';
            witness += '</label>';
            witness += '<input id="witnesses_email_' + field_id + '" type="text" name="witnesses[' + field_name + '][email]" value="" ' + readonly + ' class="form-control" style=" background-color:' + field_color + ';">';
            witness += '</div>';
            witness += '</div>';

            witness += '<div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">';
            witness += '<div class="form-group">';
            witness += '<label class="auto-height">';
            witness += 'WITNESS TITLE/RELATIONSHIP : ';
            witness += '<span class="required">*</span>';
            witness += '</label>';
            witness += '<input id="witnesses_title_' + field_id + '" type="text" name="witnesses[' + field_name + '][title]" value="" ' + readonly + ' class="form-control" style=" background-color:' + field_color + ';">';
            witness += '</div>';
            witness += '</div>';

            witness += '<div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">';
            witness += '<div class="form-group">';
            witness += '<label class="auto-height">';
            witness += 'WITNESS, CAN PROVIDE INFORMATION REGARDING : ';
            witness += '<span class="required">*</span> ';
            witness += '</label>';
            witness += '<div class="hr-select-dropdown">';
            witness += '<select id="can_witness_provide_info_' + current + '" name="witnesses[' + field_name + '][can_provide_info]" class="form-control" style=" background-color:' + field_color + ';">';
            witness += '<option value="yes" selected="selected">Yes</option>';
            witness += '<option value="no">No</option>';
            witness += '</select>';
            witness += '</div>';
            witness += '</div>';
            witness += '</div>';

            witness += '<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">';
            witness += '<div class="btn-wrp full-width text-right">';
            witness += '<a href="javascript:;" class="btn btn-danger" onclick="delete_witrness(' + current + ',' + delete_witness + ');" style="margin-top: 16px;">';
            witness += '<i class="fa fa-user-times" aria-hidden="true"></i>';
            witness += '</a>';
            witness += '</div>';
            witness += '</div>';

            witness += '</div>';

            if (section == 'inner') {
                $('#add_inner_wirnesses_section').prepend(witness);
                $("#employee_to_witness").attr("src", ++current);

            } else if (section == 'outter') {
                $('#add_outter_wirnesses_section').prepend(witness);
                $("#add_new_witrness").attr("src", ++current);
            }
            ++field_name;
        }

        function delete_witrness(id, type) {
            if (type == 0) {
                var inner_name = $("#witnesses_name_0_" + id).val();
                var inner_phone = $("#witnesses_phone_0_" + id).val();
                var inner_email = $("#witnesses_email_0_" + id).val();
                var inner_title = $("#witnesses_title_0_" + id).val();

                if (inner_name != '' || inner_phone != '' || inner_email != '' || inner_title != '') {
                    alertify.confirm(
                        'Are you Sure?',
                        'Are you sure you want to Delete this witness?',
                        function() {
                            var option_value = inner_name + ',' + inner_email + ',' + inner_phone;
                            $('#employee_to_witness').append($("<option></option>").attr("value", option_value).text(inner_name));
                            $("#witness_inner_" + id).remove();
                        },
                        function() {
                            alertify.error('Cancelled!');
                        }).set('labels', {
                        ok: 'Yes!',
                        cancel: 'Cancel'
                    });
                } else {
                    $("#witness_inner_" + id).remove();
                }

            } else if (type == 1) {
                var outter_name = $("#witnesses_name_1_" + id).val();
                var outter_phone = $("#witnesses_phone_1_" + id).val();
                var outter_email = $("#witnesses_email_1_" + id).val();
                var outter_title = $("#witnesses_title_1_" + id).val();

                if (outter_name != '' || outter_phone != '' || outter_email != '' || outter_title != '') {
                    alertify.confirm(
                        'Are you Sure?',
                        'Are you sure you want to Delete this witness?',
                        function() {
                            $("#witness_outter_" + id).remove();
                        },
                        function() {
                            alertify.error('Cancelled!');
                        }).set('labels', {
                        ok: 'Yes!',
                        cancel: 'Cancel'
                    });
                } else {
                    $("#witness_outter_" + id).remove();
                }
            }
        }

        $('#form_new_witnesses').submit(function() {
            var flag = 0;
            var witnesses_message = '';

            $('#add_inner_wirnesses_section > div').each(function(key) {
                var i = key + 1;
                var div = 0;
                div = $(this).attr('src');
                var email = /\S+@\S+\.\S+/;

                var witness_name = $("#witnesses_name_0_" + div).val();
                var witnesses_phone = $("#witnesses_phone_0_" + div).val();
                var witnesses_email = $("#witnesses_email_0_" + div).val();
                var witnesses_title = $("#witnesses_title_0_" + div).val();

                if (witness_name == '' || witnesses_phone == '' || witnesses_email == '' || witnesses_title == '') {
                    if (witness_name == '') {
                        witnesses_message = 'Please add witness full name at row ' + i + ' !';
                    } else if (witnesses_phone == '') {
                        witnesses_message = 'Please add witness phone number at row ' + i + ' !';
                    } else if (witnesses_email == '') {
                        witnesses_message = 'Please add witness email at row ' + i + ' !';
                    } else if (witnesses_title == '') {
                        witnesses_message = 'Please add witness title / relationship at row ' + i + ' !';
                    }
                    $("#submit").removeAttr("disabled");
                    flag = 1;
                    return false;
                } else if (witnesses_email != '' && !email.test(witnesses_email)) {
                    witnesses_message = 'Please add valid witness email at row ' + i + ' !';
                    flag = 1;
                    return false;
                }
            });

            $('#add_outter_wirnesses_section > div').each(function(key) {
                var i = key + 1;
                var div = 0;
                div = $(this).attr('src');
                var email = /\S+@\S+\.\S+/;

                var witness_name = $("#witnesses_name_1_" + div).val();
                var witnesses_phone = $("#witnesses_phone_1_" + div).val();
                var witnesses_email = $("#witnesses_email_1_" + div).val();
                var witnesses_title = $("#witnesses_title_1_" + div).val();

                if (witness_name == '' || witnesses_phone == '' || witnesses_email == '' || witnesses_title == '') {
                    if (witness_name == '') {
                        witnesses_message = 'Please add witness full name at row ' + i + ' !';
                    } else if (witnesses_phone == '') {
                        witnesses_message = 'Please add witness phone number at row ' + i + ' !';
                    } else if (witnesses_email == '') {
                        witnesses_message = 'Please add witness email at row ' + i + ' !';
                    } else if (witnesses_title == '') {
                        witnesses_message = 'Please add witness title / relationship at row ' + i + ' !';
                    }
                    $("#submit").removeAttr("disabled");
                    flag = 1;
                    return false;
                } else if (witnesses_email != '' && !email.test(witnesses_email)) {
                    witnesses_message = 'Please add valid witness email at row ' + i + ' !';
                    flag = 1;
                    return false;
                }
            });

            if (flag == 1) {
                alertify.alert(witnesses_message);
                return false;
            }
        });
        // Witness JS End

        // Media JS Start
        $('.video_source').on('click', function() {
            var selected = $(this).val();

            if (selected == 'youtube') {
                $('#yt_vm_video_container input').prop('disabled', false);
                $('#yt_vm_video_container').show();

                $('#up_video_container input').prop('disabled', true);
                $('#up_video_container').hide();

                $('#up_audio_container input').prop('disabled', true);
                $('#up_audio_container').hide();

                $('#upload_audio_video').text('Save Video');

            } else if (selected == 'vimeo') {
                $('#yt_vm_video_container input').prop('disabled', false);
                $('#yt_vm_video_container').show();

                $('#up_video_container input').prop('disabled', true);
                $('#up_video_container').hide();

                $('#up_audio_container input').prop('disabled', true);
                $('#up_audio_container').hide();

                $('#upload_audio_video').text('Save Video');

            } else if (selected == 'upload_video') {
                $('#yt_vm_video_container input').prop('disabled', true);
                $('#yt_vm_video_container').hide();

                $('#up_video_container input').prop('disabled', false);
                $('#up_video_container').show();

                $('#up_audio_container input').prop('disabled', true);
                $('#up_audio_container').hide();

                $('#upload_audio_video').text('Save Video');

            } else if (selected == 'upload_audio') {
                $('#yt_vm_video_container input').prop('disabled', true);
                $('#yt_vm_video_container').hide();

                $('#up_video_container input').prop('disabled', true);
                $('#up_video_container').hide();

                $('#up_audio_container input').prop('disabled', false);
                $('#up_audio_container').show();

                $('#upload_audio_video').text('Save Audio');

            }
        });

        function check_video_file(val) {
            var fileName = $("#" + val).val();

            if (fileName.length > 0) {
                $('#name_' + val).html(fileName.substring(0, 45));
                var ext = fileName.split('.').pop();
                var ext = ext.toLowerCase();

                if (val == 'video') {
                    if (ext != "mp4" && ext != "m4a" && ext != "m4v" && ext != "f4v" && ext != "f4a" && ext != "m4b" && ext != "m4r" && ext != "f4b" && ext != "mov") {
                        $("#" + val).val(null);
                        alertify.alert("Please select a valid video format.");
                        $('#name_' + val).html('<p class="red">Only (.mp4, .m4a, .m4v, .f4v, .f4a, .m4b, .m4r, .f4b, .mov) allowed!</p>');
                        return false;
                    } else {
                        var file_size = Number(($("#" + val)[0].files[0].size / 1024 / 1024).toFixed(2));
                        var video_size_limit = Number('<?php echo UPLOAD_VIDEO_SIZE; ?>');
                        if (video_size_limit < file_size) {
                            $("#" + val).val(null);
                            alertify.alert('<?php echo ERROR_UPLOAD_VIDEO_SIZE; ?>');
                            $('#name_' + val).html('');
                            return false;
                        } else {
                            var selected_file = fileName;
                            var original_selected_file = selected_file.substring(selected_file.lastIndexOf('\\') + 1, selected_file.length);
                            $('#name_' + val).html(original_selected_file);
                            return true;
                        }

                    }
                }
            } else {
                $('#name_' + val).html('No video selected');
                alertify.alert("No video selected");
                $('#name_' + val).html('<p class="red">Please select video</p>');
                return false;
            }
        }

        function check_audio_file(val) {
            var fileName = $("#" + val).val();

            if (fileName.length > 0) {
                $('#name_' + val).html(fileName.substring(0, 45));
                var ext = fileName.split('.').pop();
                var ext = ext.toLowerCase();

                if (val == 'audio') {
                    if (ext != "mp3" && ext != "m4a" && ext != "mp4" && ext != "ogg" && ext != "flac" && ext != "wav") {
                        $("#" + val).val(null);
                        alertify.alert("Please select a valid Audio format.");
                        $('#name_' + val).html('<p class="red">Only (.mp3, .m4a, .mp4, .ogg, .flac, .wav) allowed!</p>');
                        return false;
                    } else {
                        var file_size = Number(($("#" + val)[0].files[0].size / 1024 / 1024).toFixed(2));
                        var audio_size_limit = Number('<?php echo UPLOAD_AUDIO_SIZE; ?>');
                        if (audio_size_limit < file_size) {
                            $("#" + val).val(null);
                            alertify.alert('<?php echo ERROR_UPLOAD_AUDIO_SIZE; ?>');
                            $('#name_' + val).html('');
                            return false;
                        } else {
                            var selected_file = fileName;
                            var original_selected_file = selected_file.substring(selected_file.lastIndexOf('\\') + 1, selected_file.length);
                            $('#name_' + val).html(original_selected_file);
                            return true;
                        }

                    }
                }
            } else {
                $('#name_' + val).html('No audio selected');
                alertify.alert("No audio selected");
                $('#name_' + val).html('<p class="red">Please select audio</p>');
                return false;
            }
        }

        $('#form_new_video').submit(function() {
            var flag = 0;
            var message;
            var title = $('#video_title').val();

            if ($('input[name="video_source"]:checked').val() == 'youtube') {
                if ($('#video_id').val() != '') {
                    var p = /(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.)?youtube\.com\/watch(?:\.php)?\?.*v=)([a-zA-Z0-9\-_]+)/;
                    if (!$('#video_id').val().match(p)) {
                        message = 'Not a Valid Youtube URL';
                        flag = 1;
                    }
                } else {
                    message = 'Please provide a Valid Youtube URL';
                    flag = 1;
                }
            }

            if ($('input[name="video_source"]:checked').val() == 'vimeo') {
                if ($('#video_id').val() != '') {
                    var myurl = "<?php echo base_url('Incident_reporting_system/validate_vimeo'); ?>";
                    $.ajax({
                        type: "POST",
                        url: myurl,
                        data: {
                            url: $('#video_id').val()
                        },
                        async: false,
                        success: function(data) {
                            if (data == false) {
                                message = 'Not a Valid Vimeo URL';
                                flag = 1;
                            }
                        },
                        error: function(data) {}
                    });
                } else {
                    message = 'Please provide a Valid Vimeo URL';
                    flag = 1;
                }
            }

            if ($('input[name="video_source"]:checked').val() == 'upload_video') {
                var fileName = $("#video").val();

                if (fileName.length > 0) {
                    $('#name_video').html(fileName.substring(0, 45));
                    var ext = fileName.split('.').pop();
                    var ext = ext.toLowerCase();


                    if (ext != "mp4" && ext != "m4a" && ext != "m4v" && ext != "f4v" && ext != "f4a" && ext != "m4b" && ext != "m4r" && ext != "f4b" && ext != "mov") {
                        $("#video").val(null);
                        $('#name_video').html('<p class="red">Only (.mp4, .m4a, .m4v, .f4v, .f4a, .m4b, .m4r, .f4b, .mov) allowed!</p>');
                        message = 'Please select a valid video format.';
                        flag = 1;
                    } else {
                        var file_size = Number(($("#video")[0].files[0].size / 1024 / 1024).toFixed(2));
                        var video_size_limit = Number('<?php echo UPLOAD_VIDEO_SIZE; ?>');
                        if (video_size_limit < file_size) {
                            $("#video").val(null);
                            $('#name_video').html('');
                            message = '<?php echo ERROR_UPLOAD_VIDEO_SIZE; ?>';
                            flag = 1;
                        }
                    }
                } else {
                    $('#name_video').html('<p class="red">Please select video</p>');
                    message = 'Please select video to upload';
                    flag = 1;
                }
            }

            if ($('input[name="video_source"]:checked').val() == 'upload_audio') {
                var fileName = $("#audio").val();

                if (fileName.length > 0) {
                    $('#name_audio').html(fileName.substring(0, 45));
                    var ext = fileName.split('.').pop();
                    var ext = ext.toLowerCase();


                    if (ext != "mp3" && ext != "m4a" && ext != "mp4" && ext != "ogg" && ext != "flac" && ext != "wav") {
                        $("#audio").val(null);
                        $('#name_audio').html('<p class="red">Only (.mp3, .m4a, .mp4, .ogg, .flac, .wav) allowed!</p>');
                        message = 'Please select a valid audio format.';
                        flag = 1;
                    } else {
                        var file_size = Number(($("#audio")[0].files[0].size / 1024 / 1024).toFixed(2));
                        var audio_size_limit = Number('<?php echo UPLOAD_AUDIO_SIZE; ?>');
                        if (audio_size_limit < file_size) {
                            $("#audio").val(null);
                            $('#name_audio').html('');
                            message = '<?php echo ERROR_UPLOAD_AUDIO_SIZE; ?>';
                            flag = 1;
                        }
                    }
                } else {
                    $('#name_audio').html('<p class="red">Please select audio</p>');
                    message = 'Please select audio to upload';
                    flag = 1;
                }
            }

            if (title == '' || title.length == 0) {
                message = 'Please provide a Video Title.';
                flag = 1;
            }

            if (flag == 1) {
                alertify.alert(message);
                return false;
            }
        });

        $('.js-edit-video').on('click', function() {
            var video_sid = $(this).attr('data-id');
            var old_title = $(this).attr('data-title');

            $('#update_video_sid').val(video_sid);
            $('#upload_video_title').val(old_title);
            $('#update_video_id').val('');
            $("#update_video").val(null);
            $('#name_update_video').html('');
            $("#update_audio").val(null);
            $('#name_update_audio').html('');
            $('#edit_incident_video').modal('show');

            $("#update_option").prop("checked", true);
            $("#update_media_option").prop("checked", true);
            $('#update_yt_vm_video_container input').prop('disabled', false);
            $('#update_yt_vm_video_container').show();
            $('#update_up_audio_container').hide();
            $('#update_up_video_container').hide();
        });

        $('.update_type').on('click', function() {
            var selected = $(this).val();

            if (selected == 'title') {
                $('#only_title').show();
                $('#only_video').hide();
                $('#only_video_select').hide();
            } else if (selected == 'video') {
                $('#only_title').hide();
                $('#only_video').show();
                $('#only_video_select').show();
            } else if (selected == 'both') {
                $('#only_title').show();
                $('#only_video').show();
                $('#only_video_select').show();
            }
        });

        $('.update_video_source').on('click', function() {
            var selected = $(this).val();

            if (selected == 'youtube') {
                $('#update_yt_vm_video_container input').prop('disabled', false);
                $('#update_yt_vm_video_container').show();

                $('#update_up_video_container input').prop('disabled', true);
                $('#update_up_video_container').hide();

                $('#update_up_audio_container input').prop('disabled', true);
                $('#update_up_audio_container').hide();

                $('#save_updated_video').text('Update Video');

            } else if (selected == 'vimeo') {
                $('#update_yt_vm_video_container input').prop('disabled', false);
                $('#update_yt_vm_video_container').show();

                $('#update_up_video_container input').prop('disabled', true);
                $('#update_up_video_container').hide();

                $('#update_up_audio_container input').prop('disabled', true);
                $('#update_up_audio_container').hide();

                $('#save_updated_video').text('Update Video');

            } else if (selected == 'upload_video') {
                $('#update_yt_vm_video_container input').prop('disabled', true);
                $('#update_yt_vm_video_container').hide();

                $('#update_up_video_container input').prop('disabled', false);
                $('#update_up_video_container').show();

                $('#update_up_audio_container input').prop('disabled', true);
                $('#update_up_audio_container').hide();

                $('#save_updated_video').text('Update Video');

            } else if (selected == 'upload_audio') {
                $('#update_yt_vm_video_container input').prop('disabled', true);
                $('#update_yt_vm_video_container').hide();

                $('#update_up_video_container input').prop('disabled', true);
                $('#update_up_video_container').hide();

                $('#update_up_audio_container input').prop('disabled', false);
                $('#update_up_audio_container').show();

                $('#save_updated_video').text('Update Audio');

            }
        });

        function check_update_video_file(val) {
            var fileName = $("#" + val).val();

            if (fileName.length > 0) {
                $('#name_' + val).html(fileName.substring(0, 45));
                var ext = fileName.split('.').pop();
                var ext = ext.toLowerCase();

                if (val == 'update_video') {
                    if (ext != "mp4" && ext != "m4a" && ext != "m4v" && ext != "f4v" && ext != "f4a" && ext != "m4b" && ext != "m4r" && ext != "f4b" && ext != "mov") {
                        $("#" + val).val(null);
                        alertify.alert("Please select a valid video format.");
                        $('#name_' + val).html('<p class="red">Only (.mp4, .m4a, .m4v, .f4v, .f4a, .m4b, .m4r, .f4b, .mov) allowed!</p>');
                        return false;
                    } else {
                        var file_size = Number(($("#" + val)[0].files[0].size / 1024 / 1024).toFixed(2));
                        var video_size_limit = Number('<?php echo UPLOAD_VIDEO_SIZE; ?>');
                        if (video_size_limit < file_size) {
                            $("#" + val).val(null);
                            alertify.alert('<?php echo ERROR_UPLOAD_VIDEO_SIZE; ?>');
                            $('#name_' + val).html('');
                            return false;
                        } else {
                            var selected_file = fileName;
                            var original_selected_file = selected_file.substring(selected_file.lastIndexOf('\\') + 1, selected_file.length);
                            $('#name_' + val).html(original_selected_file);
                            return true;
                        }

                    }
                }
            } else {
                $('#name_' + val).html('No video selected');
                alertify.alert("No video selected");
                $('#name_' + val).html('<p class="red">Please select video</p>');
                return false;
            }
        }

        function check_update_audio_file(val) {
            var fileName = $("#" + val).val();

            if (fileName.length > 0) {
                $('#name_' + val).html(fileName.substring(0, 45));
                var ext = fileName.split('.').pop();
                var ext = ext.toLowerCase();

                if (val == 'update_audio') {
                    if (ext != "mp3" && ext != "m4a" && ext != "mp4" && ext != "ogg" && ext != "flac" && ext != "wav") {
                        $("#" + val).val(null);
                        alertify.alert("Please select a valid Audio format.");
                        $('#name_' + val).html('<p class="red">Only (.mp3, .m4a, .mp4, .ogg, .flac, .wav) allowed!</p>');
                        return false;
                    } else {
                        var file_size = Number(($("#" + val)[0].files[0].size / 1024 / 1024).toFixed(2));
                        var audio_size_limit = Number('<?php echo UPLOAD_AUDIO_SIZE; ?>');
                        if (audio_size_limit < file_size) {
                            $("#" + val).val(null);
                            alertify.alert('<?php echo ERROR_UPLOAD_AUDIO_SIZE; ?>');
                            $('#name_' + val).html('');
                            return false;
                        } else {
                            var selected_file = fileName;
                            var original_selected_file = selected_file.substring(selected_file.lastIndexOf('\\') + 1, selected_file.length);
                            $('#name_' + val).html(original_selected_file);
                            return true;
                        }

                    }
                }
            } else {
                $('#name_' + val).html('No audio selected');
                alertify.alert("No audio selected");
                $('#name_' + val).html('<p class="red">Please select audio</p>');
                return false;
            }
        }

        $('#save_updated_video').on('click', function() {
            var flag = 0;
            var message;
            var validation = $('input[name="update_type"]:checked').val();

            if (validation == 'video' || validation == 'both') {
                if ($('input[name="update_video_source"]:checked').val() == 'youtube') {
                    if ($('#update_video_id').val() != '') {
                        var p = /(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.)?youtube\.com\/watch(?:\.php)?\?.*v=)([a-zA-Z0-9\-_]+)/;
                        if (!$('#update_video_id').val().match(p)) {
                            message = 'Not a Valid Youtube URL';
                            flag = 1;
                        }
                    } else {
                        message = 'Please provide a Valid Youtube URL';
                        flag = 1;
                    }
                }

                if ($('input[name="update_video_source"]:checked').val() == 'vimeo') {
                    if ($('#update_video_id').val() != '') {
                        var myurl = "<?php echo base_url('Incident_reporting_system/validate_vimeo'); ?>";
                        $.ajax({
                            type: "POST",
                            url: myurl,
                            data: {
                                url: $('#update_video_id').val()
                            },
                            async: false,
                            success: function(data) {
                                if (data == false) {
                                    message = 'Not a Valid Vimeo URLs';
                                    flag = 1;
                                }
                            },
                            error: function(data) {}
                        });
                    } else {
                        message = 'Please provide a Valid Vimeo URL';
                        flag = 1;
                    }
                }

                if ($('input[name="update_video_source"]:checked').val() == 'upload_video') {
                    var fileName = $("#update_video").val();

                    if (fileName.length > 0) {
                        $('#name_update_video').html(fileName.substring(0, 45));
                        var ext = fileName.split('.').pop();
                        var ext = ext.toLowerCase();


                        if (ext != "mp4" && ext != "m4a" && ext != "m4v" && ext != "f4v" && ext != "f4a" && ext != "m4b" && ext != "m4r" && ext != "f4b" && ext != "mov") {
                            $("#update_video").val(null);
                            $('#name_update_video').html('<p class="red">Only (.mp4, .m4a, .m4v, .f4v, .f4a, .m4b, .m4r, .f4b, .mov) allowed!</p>');
                            message = 'Please select a valid video format.';
                            flag = 1;
                        } else {
                            var file_size = Number(($("#update_video")[0].files[0].size / 1024 / 1024).toFixed(2));
                            var video_size_limit = Number('<?php echo UPLOAD_VIDEO_SIZE; ?>');
                            if (video_size_limit < file_size) {
                                $("#update_video").val(null);
                                $('#name_update_video').html('');
                                message = '<?php echo ERROR_UPLOAD_VIDEO_SIZE; ?>';
                                flag = 1;
                            }
                        }
                    } else {
                        $('#name_update_video').html('<p class="red">Please select video</p>');
                        message = 'Please select video to upload';
                        flag = 1;
                    }
                }

                if ($('input[name="update_video_source"]:checked').val() == 'upload_audio') {
                    var fileName = $("#update_audio").val();

                    if (fileName.length > 0) {
                        $('#name_update_audio').html(fileName.substring(0, 45));
                        var ext = fileName.split('.').pop();
                        var ext = ext.toLowerCase();


                        if (ext != "mp3" && ext != "m4a" && ext != "mp4" && ext != "ogg" && ext != "flac" && ext != "wav") {
                            $("#update_audio").val(null);
                            $('#name_update_audio').html('<p class="red">Only (.mp3, .m4a, .mp4, .ogg, .flac, .wav) allowed!</p>');
                            message = 'Please select a valid audio format.';
                            flag = 1;
                        } else {
                            var file_size = Number(($("#update_audio")[0].files[0].size / 1024 / 1024).toFixed(2));
                            var audio_size_limit = Number('<?php echo UPLOAD_AUDIO_SIZE; ?>');
                            if (audio_size_limit < file_size) {
                                $("#update_audio").val(null);
                                $('#name_update_audio').html('');
                                message = '<?php echo ERROR_UPLOAD_AUDIO_SIZE; ?>';
                                flag = 1;
                            }
                        }
                    } else {
                        $('#name_update_audio').html('<p class="red">Please select audio</p>');
                        message = 'Please select audio to upload';
                        flag = 1;
                    }
                }
            }

            if (validation == 'title' || validation == 'both') {
                var update_title = $('#upload_video_title').val();

                if (update_title == '' || update_title.length == 0) {
                    message = 'Please provide a Video Title.';
                    flag = 1;
                }
            }

            if (flag == 1) {
                alertify.alert(message);
                return false;
            } else {
                var update_url = '<?php echo base_url('incident_reporting_system/update_incident_video'); ?>';
                var targit_video = $('#update_video_sid').val();
                var form_data = new FormData();

                if ($('input[name="update_video_source"]:checked').val() == 'upload_audio') {
                    var audio_data = $('#update_audio').prop('files')[0];

                    form_data.append('audio', audio_data);
                    form_data.append('file_type', 'upload_audio');
                } else if ($('input[name="update_video_source"]:checked').val() == 'upload_video') {
                    var video_data = $('#update_video').prop('files')[0];

                    form_data.append('video', video_data);
                    form_data.append('file_type', 'upload_video');
                } else if ($('input[name="update_video_source"]:checked').val() == 'youtube') {
                    var youtube_video_link = $('#update_video_id').val();

                    form_data.append('youtube_video_link', youtube_video_link);
                    form_data.append('file_type', 'youtube');
                } else if ($('input[name="update_video_source"]:checked').val() == 'vimeo') {
                    var vimeo_video_link = $('#update_video_id').val();

                    form_data.append('vimeo_video_link', vimeo_video_link);
                    form_data.append('file_type', 'vimeo');
                }

                form_data.append('update_type', validation);
                form_data.append('video_sid', targit_video);
                form_data.append('user_type', 'manager');
                form_data.append('update_title', update_title);
                form_data.append('incident_sid', <?php echo $id; ?>);
                form_data.append('company_sid', <?php echo $company_sid; ?>);

                $.ajax({
                    url: update_url,
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'post',
                    data: form_data,
                    success: function(data) {
                        if (data == 'success') {
                            alertify.alert('Supporting Incident Video Update Successfully.');
                        } else {
                            alertify.alert('Some error occurred while uploading video.');
                        }
                        location.reload();

                    },
                    error: function() {}
                });
            }
        });

        $('.js-archive-video').click(function(e) {
            e.preventDefault();
            var videoSid = $(this).data('id');
            alertify.confirm('Do you really want to move this video to Archive?', function() {
                $.post("<?= base_url('incident_reporting_system/handler') ?>", {
                    action: 'archive_video',
                    videoSid: videoSid
                }, function(resp) {
                    if (resp.Status === false) {
                        alertify.alert('ERROR!', resp.Response);
                        return;
                    }
                    alertify.alert('SUCCESS!', resp.Response, function() {
                        window.location.reload();
                    });
                });
            }).set('labels', {
                ok: 'Yes',
                cancel: 'No'
            });
        });

        $('.js-active-video').click(function(e) {
            e.preventDefault();
            var videoSid = $(this).data('id');
            alertify.confirm('Do you really want to move this video to Active?', function() {
                $.post("<?= base_url('incident_reporting_system/handler') ?>", {
                    action: 'active_video',
                    videoSid: videoSid
                }, function(resp) {
                    if (resp.Status === false) {
                        alertify.alert('ERROR!', resp.Response);
                        return;
                    }
                    alertify.alert('SUCCESS!', resp.Response, function() {
                        window.location.reload();
                    });
                });
            }).set('labels', {
                ok: 'Yes',
                cancel: 'No'
            });
        });

        $('.js-view-video').click(function(e) {
            var video_title = $(this).attr('video-title');
            var video_source = $(this).attr('video-source');
            var video_url = $(this).attr('video-url');

            $('#video_modal_title').html(video_title);

            if (video_source == 'youtube') {

                $('#youtube-section').show();
                var video = $("<iframe />")
                    .attr("id", "youtube_iframe")
                    .attr("src", "https://www.youtube.com/embed/" + video_url);
                $("#youtube-video-placeholder").append(video);

            } else if (video_source == 'vimeo') {

                $('#vimeo-section').show();
                var video = $("<iframe />")
                    .attr("id", "vimeo_iframe")
                    .attr("src", "https://player.vimeo.com/video/" + video_url);
                $("#vimeo-video-placeholder").append(video);

            } else if (video_source == 'upload_video') {

                $('#video-section').show();
                var video = document.getElementById('my-video');
                var source = document.createElement('source');
                $("#my-video").first().attr('src', video_url);

            } else if (video_source == 'upload_audio') {

                $('#audio-section').show();
                var video = document.getElementById('my-audio');
                var source = document.createElement('source');
                $("#my-audio").first().attr('src', video_url);

            }

            $('#close_media_header').attr('video-source', video_source);
            $('#close_media_footer').attr('video-source', video_source);
            $('#view_incident_video').modal('show');
        });

        function stop_media(source) {
            var video_source = $(source).attr('video-source');

            if (video_source == 'youtube') {
                $("#youtube-video-placeholder").append('');
                $("#youtube_iframe").remove();
                $('#youtube-section').hide();
            } else if (video_source == 'vimeo') {
                $("#vimeo-video-placeholder").append('');
                $("#vimeo_iframe").remove();
                $('#vimeo-section').hide();
            } else if (video_source == 'upload_video') {
                $("#my-video").first().attr('src', '');
                $('#video-section').hide();
            } else if (video_source == 'upload_audio') {
                $("#my-audio").first().attr('src', '');
                $('#audio-section').hide();
            }
        }
        // Media JS End

        // Documents JS Start
        function check_upload_document(val) {
            var fileName = $("#" + val).val();

            if (fileName.length > 0) {
                $('#name_' + val).html(fileName.substring(0, 45));
                var ext = fileName.split('.').pop();
                var ext = ext.toLowerCase();

                if (val == 'upload_document') {
                    if (ext != "pdf" && ext != "doc" && ext != "docx" && ext != "jpg" && ext != "jpe" && ext != "jpeg" && ext != "png" && ext != "gif") {
                        $("#" + val).val(null);
                        alertify.alert("Please select a valid document format.");
                        $('#name_' + val).html('<p class="red">Only (.pdf, .doc, .docx, .jpg, .jpe, .jpeg, .png, .gif) allowed!</p>');
                        return false;
                    } else {
                        var selected_file = fileName;
                        var original_selected_file = selected_file.substring(selected_file.lastIndexOf('\\') + 1, selected_file.length);
                        $('#name_' + val).html(original_selected_file);
                        return true;
                    }
                }
            } else {
                $('#name_' + val).html('No document selected');
                alertify.alert("No document selected");
                $('#name_' + val).html('<p class="red">Please select document</p>');
                return false;
            }
        }

        $('#form_new_document').submit(function() {
            var flag = 0;
            var message;
            var fileName = $("#upload_document").val();
            var title = $('#document_title').val();

            if (fileName.length > 0) {
                $('#name_upload_document').html(fileName.substring(0, 45));
                var ext = fileName.split('.').pop();
                var ext = ext.toLowerCase();

                if (ext != "pdf" && ext != "doc" && ext != "docx" && ext != "jpg" && ext != "jpe" && ext != "jpeg" && ext != "png" && ext != "gif") {
                    $("#upload_document").val(null);
                    $('#name_upload_document').html('<p class="red">Only (.pdf, .doc, .docx, .jpg, .jpe, .jpeg, .png, .gif) allowed!</p>');
                    message = 'Please select a valid document format.';
                    flag = 1;
                }
            } else {
                $('#name_upload_document').html('<p class="red">Please select document</p>');
                message = 'Please select document to upload';
                flag = 1;
            }

            if (title == '' || title.length == 0) {
                message = 'Please provide a Document Title.';
                flag = 1;
            }

            if (flag == 1) {
                alertify.alert(message);
                return false;
            }
        });

        $('.js-edit-document').on('click', function() {
            var iframe_url = $(this).attr('data-url');
            var doc_sid = $(this).attr('data-id');
            var doc_ext = $(this).attr('data-ext');
            var doc_title = $(this).attr('data-title');

            if (doc_ext != "jpg" && doc_ext != "jpe" && doc_ext != "jpeg" && doc_ext != "png" && doc_ext != "gif") {
                modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
            } else {
                modal_content = '<img src="' + iframe_url + '" style="width:100%; height:500px;" />';
            }

            $('#update_document_sid').val(doc_sid);
            $('#update_doc_title').val(doc_title);
            $('#document_modal_body').html(modal_content);
            $('#edit_incident_document').modal('show');
        });

        $('.update_document_type').on('click', function() {
            var selected = $(this).val();

            if (selected == 'title') {
                $('#only_doc_title').show();
                $('#only_document').hide();
            } else if (selected == 'document') {
                $('#only_doc_title').hide();
                $('#only_document').show();
            } else if (selected == 'both') {
                $('#only_doc_title').show();
                $('#only_document').show();
            }
        });

        function check_edit_document(val) {
            var fileName = $("#" + val).val();

            if (fileName.length > 0) {
                $('#name_' + val).html(fileName.substring(0, 45));
                var ext = fileName.split('.').pop();
                var ext = ext.toLowerCase();

                if (val == 'edit_upload_document') {
                    if (ext != "pdf" && ext != "doc" && ext != "docx" && ext != "jpg" && ext != "jpe" && ext != "jpeg" && ext != "png" && ext != "gif") {
                        $("#" + val).val(null);
                        alertify.alert("Please select a valid document format.");
                        $('#name_' + val).html('<p class="red">Only (.pdf, .doc, .docx, .jpg, .jpe, .jpeg, .png, .gif) allowed!</p>');
                        return false;
                    } else {
                        var selected_file = fileName;
                        var original_selected_file = selected_file.substring(selected_file.lastIndexOf('\\') + 1, selected_file.length);
                        $('#name_' + val).html(original_selected_file);
                        return true;
                    }
                }
            } else {
                $('#name_' + val).html('No document selected');
                alertify.alert("No document selected");
                $('#name_' + val).html('<p class="red">Please select document</p>');
                return false;
            }
        }

        $('#save_updated_doc').on('click', function() {
            var flag = 0;
            var message;
            var fileName = $("#edit_upload_document").val();
            var validation = $('input[name="update_document_type"]:checked').val();

            if (validation == 'document' || validation == 'both') {
                if (fileName.length > 0) {
                    $('#name_edit_upload_document').html(fileName.substring(0, 45));
                    var ext = fileName.split('.').pop();
                    var ext = ext.toLowerCase();

                    if (ext != "pdf" && ext != "doc" && ext != "docx" && ext != "jpg" && ext != "jpe" && ext != "jpeg" && ext != "png" && ext != "gif") {
                        $("#edit_upload_document").val(null);
                        $('#name_edit_upload_document').html('<p class="red">Only (.pdf, .doc, .docx, .jpg, .jpe, .jpeg, .png, .gif) allowed!</p>');
                        message = 'Please select a valid document format.';
                        flag = 1;
                    }
                } else {
                    $('#name_edit_upload_document').html('<p class="red">Please select document</p>');
                    message = 'Please select document to upload';
                    flag = 1;
                }
            }

            if (validation == 'title' || validation == 'both') {
                var document_title = $('#update_doc_title').val();

                if (document_title == '' || document_title.length == 0) {
                    message = 'Please provide a Document Title.';
                    flag = 1;
                }
            }

            if (flag == 1) {
                alertify.alert(message);
                return false;
            } else {
                var update_url = '<?php echo base_url('incident_reporting_system/update_incident_document'); ?>';
                var targit_document = $('#update_document_sid').val();
                var file_data = $('#edit_upload_document').prop('files')[0];
                var file_ext = fileName.split('.').pop();
                var form_data = new FormData();

                form_data.append('update_type', validation);
                form_data.append('document_title', document_title);
                form_data.append('document_sid', targit_document);
                form_data.append('incident_sid', <?php echo $id; ?>);
                form_data.append('docs', file_data);
                form_data.append('file_name', fileName.replace('C:\\fakepath\\', ''));
                form_data.append('file_ext', file_ext);
                form_data.append('company_sid', <?php echo $company_sid; ?>);
                form_data.append('user_type', 'manager');

                $.ajax({
                    url: update_url,
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'post',
                    data: form_data,
                    success: function(return_data_array) {
                        alertify.alert('Supporting Incident Document Update Successfully.');
                        location.reload();
                    },
                    error: function() {}
                });
            }
        });

        $('.js-archive-document').click(function(e) {
            e.preventDefault();
            var documentSid = $(this).data('id');
            alertify.confirm('Do you really want to move this document to Archive?', function() {
                $.post("<?= base_url('incident_reporting_system/handler') ?>", {
                    action: 'archive_document',
                    documentSid: documentSid
                }, function(resp) {
                    if (resp.Status === false) {
                        alertify.alert('ERROR!', resp.Response);
                        return;
                    }
                    alertify.alert('SUCCESS!', resp.Response, function() {
                        window.location.reload();
                    });
                });
            }).set('labels', {
                ok: 'Yes',
                cancel: 'No'
            });
        });

        $('.js-active-document').click(function(e) {
            e.preventDefault();
            var documentSid = $(this).data('id');
            alertify.confirm('Do you really want to move this document to Active?', function() {
                $.post("<?= base_url('incident_reporting_system/handler') ?>", {
                    action: 'active_document',
                    documentSid: documentSid
                }, function(resp) {
                    if (resp.Status === false) {
                        alertify.alert('ERROR!', resp.Response);
                        return;
                    }
                    alertify.alert('SUCCESS!', resp.Response, function() {
                        window.location.reload();
                    });
                });
            }).set('labels', {
                ok: 'Yes',
                cancel: 'No'
            });
        });

        function view_incident_doc(source) {
            var iframe_url = '';
            var modal_content = '';
            var footer_content = '';
            var document_title = $(source).attr('data-title');
            var file_extension = $(source).attr('data-preview-ext');
            var document_preview_url = $(source).attr('data-preview-url');

            if (document_preview_url != '') {
                switch (file_extension.toLowerCase()) {
                    case 'pdf':
                        iframe_url = document_preview_url;
                        modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                        break;
                    case 'doc':
                        iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_preview_url;
                        modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                        break;
                    case 'docx':
                        iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_preview_url;
                        modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                        break;
                    case 'jpg':
                    case 'jpe':
                    case 'jpeg':
                    case 'png':
                    case 'gif':
                        modal_content += '<div style=" display: flex; justify-content: center; align-items: center; height: 100vh; ">';
                        modal_content += '<img src="' + document_preview_url + '" style="max-width:100%; max-height:100%; width: auto; height: auto; object-fit: contain;" />';
                        modal_content += '</div>';
                        break;
                    default:
                        //using google docs
                        iframe_url = 'https://docs.google.com/gview?url=' + document_preview_url + '&embedded=true';
                        break;
                }
            } else {
                modal_content = '<h5>No ' + document_title + ' Uploaded.</h5>';
                footer_content = '';
            }

            $('#view_document_modal_body').html(modal_content);
            $('#document_modal_title').html(document_title);
            $('#document_modal').modal("toggle");
            $('#document_modal').on("shown.bs.modal", function() {
                if (iframe_url != '') {
                    $('#preview_iframe').attr('src', iframe_url);
                }
            });
        }
        // Documents JS End

        // Email JS Start
        $('.email_type').on('click', function() {
            var selected = $(this).val();

            if (selected == 'system') {
                $('#system_email').show();
                $('#manual_email').hide();
            } else if (selected == 'manual') {
                $('#manual_email').show();
                $('#system_email').hide();
            }
        });

        $("#send_normal_email").on('click', function() {
            var flag = 0;
            var message = '';
            var receivers;
            var attachment_size = $('#attachment_listing_data > .manual_upload_items').size();

            if ($('input[name="send_type"]:checked').val() == 'system') {
                receivers = $('#receivers').val();
            } else {
                receivers = $('#manual_address').val();
            }

            var message_subject = $('#subject').val();
            var message_body = CKEDITOR.instances['message'].getData();

            if (receivers == null && message_subject == '' && message_body == '') {
                message = 'All fields are required.';
                flag = 1;
            } else if (receivers == null && message_subject == '') {
                message = 'Email address and Subject are required.';
                flag = 1;
            } else if (receivers == null && message_body == '') {
                message = 'Email address and Message are required.';
                flag = 1;
            } else if (message_subject == '' && message_body == '') {
                message = 'Subject and Message body are required.';
                flag = 1;
            } else if (receivers == null) {
                message = 'Email address is required.';
                flag = 1;
            } else if (message_body == '') {
                message = 'Message body is required.';
                flag = 1;
            } else if (message_subject == '') {
                message = 'Subject is required.';
                flag = 1;
            }

            if (attachment_size > 0 && flag == 0) {
                $('#attachment_loader').show();
                $('#attachment_listing_data > .manual_upload_items').each(function(key) {
                    var item_status = $(this).attr('item-status');
                    if (item_status == 'pending') {
                        var item_row_id = $(this).attr('row-id');
                        var item_title = $(this).attr('item-title');
                        var item_source = $(this).attr('item-source');
                        var save_attachment_url = '<?php echo base_url('incident_reporting_system/save_email_manual_attachment'); ?>';

                        var form_data = new FormData();
                        form_data.append('attachment_title', item_title);

                        if (item_source == 'youtube' || item_source == 'vimeo') {
                            var social_url = $(this).attr('item-data');
                            form_data.append('social_url', social_url);
                        } else {
                            var item_id = item_title.replace(/ /g, '');
                            var item_file = $('#' + item_id).prop('files')[0];
                            form_data.append('file', item_file);

                            if (item_source == 'upload_document') {
                                var fileName = $('#' + item_id).val();
                                var file_ext = fileName.split('.').pop();
                                form_data.append('file_name', fileName.replace('C:\\fakepath\\', ''));
                                form_data.append('file_ext', file_ext);
                            }
                        }

                        form_data.append('file_type', item_source);
                        form_data.append('user_type', 'manager');
                        form_data.append('incident_sid', <?php echo $id; ?>);
                        form_data.append('company_sid', <?php echo $company_sid; ?>);
                        form_data.append('uploaded_by', <?php echo $current_user; ?>);

                        $.ajax({
                            url: save_attachment_url,
                            cache: false,
                            contentType: false,
                            processData: false,
                            type: 'post',
                            data: form_data,
                            success: function(response) {
                                var obj = jQuery.parseJSON(response);
                                var res_item_sid = obj['item_sid'];
                                var res_item_title = obj['item_title'];
                                var res_item_type = obj['item_type'];
                                var res_item_source = obj['item_source'];

                                $('#' + item_row_id).html('<input type="hidden" name="attachment[' + res_item_sid + '][item_type]" value="' + res_item_type + '"><input type="hidden" name="attachment[' + res_item_sid + '][record_sid]" value="' + res_item_sid + '"><td class="text-center">' + res_item_title + '</td><td class="text-center">' + res_item_type + '</td><td class="text-center">' + res_item_source + '</td><td><a href="javascript:;" item-sid="' + res_item_sid + '" attachment-type="library" item-type="' + res_item_type + '" class="btn btn-block btn-info js-remove-attachment">Remove</a></td>');

                                $('#' + item_row_id).attr("item-status", "done");

                                attachment_size = attachment_size - 1;

                                if (attachment_size == 0) {
                                    setTimeout(function() {
                                        $("#send_normal_email").attr('type', 'submit');
                                        $('#send_normal_email').click();
                                    }, 1000);
                                }
                            },
                            error: function() {

                            }
                        });
                    }
                });
            } else {
                if (flag == 1) {
                    alertify.alert(message);
                    return false;
                } else {
                    $("#send_normal_email").attr('type', 'submit');
                    $('#send_normal_email').click();
                }
            }
        });

        function mark_read(email_sid) {
            var update_url = '<?php echo base_url('incident_reporting_system/update_email_read_flag'); ?>';
            var targit_document = $('#update_document_sid').val();
            var form_data = new FormData();
            form_data.append('email_sid', email_sid);
            form_data.append('receiver_sid', <?php echo $current_user; ?>);

            $.ajax({
                url: update_url,
                cache: false,
                contentType: false,
                processData: false,
                type: 'post',
                data: form_data,
                success: function(response) {
                    var obj = jQuery.parseJSON(response);
                    var status_one = obj['status_one'];
                    var status_two = obj['status_two'];
                    var sender_sid = obj['sender_sid'];

                    if (status_one == 0) {
                        $('#current_user_notification').hide();
                    }

                    if (status_two == 0) {
                        $('#email_notification_' + sender_sid).hide();
                    }

                    $('#email_read_' + email_sid).hide();
                },
                error: function() {}
            });
        }

        $('.show_media_library').on('click', function() {
            $("#library_item_title").html('Attachment Library');
            $("#attachment_library_modal").modal('show');
        });

        function view_library_item(source) {
            var item_category = $(source).attr('item-category');
            var item_title = $(source).attr('item-title');
            var item_url = $(source).attr('item-url');
            var item_type = $(source).attr('item-type');

            $("#show_library_item").hide();
            $("#view_library_item").show();
            $("#library_item_title").html(item_title);
            $('.back_to_library').attr('file-type', item_type);

            if (item_category == 'document') {

                $('#library-document-section').show();
                if (item_type == 'document') {
                    var document_content = $("<iframe />")
                        .attr("id", "library-document-iframe")
                        .attr("class", "uploaded-file-preview")
                        .attr("src", item_url);
                    $("#library-document-placeholder").append(document_content);
                } else {
                    var image_content = $("<img />")
                        .attr("id", "library-image")
                        .attr("class", "img-responsive")
                        .attr("src", item_url);
                    $("#library-document-placeholder").append(image_content);
                }

            } else {

                if (item_type == 'youtube') {

                    $('#library-youtube-section').show();
                    var video = $("<iframe />")
                        .attr("id", "library-youtube-iframe")
                        .attr("src", "https://www.youtube.com/embed/" + item_url);
                    $("#library-youtube-placeholder").append(video);

                } else if (item_type == 'vimeo') {

                    $('#library-vimeo-section').show();
                    var video = $("<iframe />")
                        .attr("id", "library-vimeo-iframe")
                        .attr("src", "https://player.vimeo.com/video/" + item_url);
                    $("#library-vimeo-placeholder").append(video);

                } else if (item_type == 'upload_video') {
                    $('#library-video-section').show();
                    var video = $("<video />")
                        .attr("id", "library-upload-video")
                        .attr('src', item_url)
                        .attr('controls', true);
                    $("#library-video-placeholder").append(video);

                } else if (item_type == 'upload_audio') {
                    $('#library-audio-section').show();
                    var audio = $("<audio />")
                        .attr("id", "library-upload-audio")
                        .attr('src', item_url)
                        .attr('controls', true);
                    $("#library-audio-placeholder").append(audio);
                }
            }
        }

        $('.back_to_library').on('click', function() {
            var item_type = $(this).attr('file-type');

            if (item_type == 'youtube') {
                $("#library-youtube-iframe").remove();
                $('#library-youtube-section').hide();
            } else if (item_type == 'vimeo') {
                $("#library-vimeo-iframe").remove();
                $('#library-vimeo-section').hide();
            } else if (item_type == 'upload_video') {
                $("#library-upload-video").remove();
                $('#library-video-section').hide();
            } else if (item_type == 'upload_audio') {
                $("#library-upload-audio").remove();
                $('#library-audio-section').hide();
            } else if (item_type == 'document') {
                $("#library-document-iframe").remove();
                $('#library-document-section').hide();
            } else if (item_type == 'image') {
                $("#library-image").remove();
                $('#library-document-section').hide();
            }

            $("#view_library_item").hide();
            $("#library_item_title").html('Attachment Library');
            $("#show_library_item").show();
        });

        $(".select_lib_item").on("click", function() {
            var item_id = $(this).attr("item-sid");

            if ($(this).prop('checked') == true) {

                var item_type = $(this).attr("item-category");
                var item_source = $(this).attr("item-type");
                var item_title = $(this).attr("item-title");

                $('#email_attachment_list').show();
                $('#attachment_listing_data').prepend('<tr id="lib_item_' + item_id + '"><input type="hidden" name="attachment[' + item_id + '][item_type]" value="' + item_type + '"><input type="hidden" name="attachment[' + item_id + '][record_sid]" value="' + item_id + '"><td class="text-center">' + item_title + '</td><td class="text-center">' + item_type + '</td><td class="text-center">' + item_source + '</td><td><a href="javascript:;" item-sid="' + item_id + '" attachment-type="library" item-type="' + item_type + '" class="btn btn-block btn-info js-remove-attachment">Remove</a></td></tr>');
            } else {
                $('#lib_item_' + item_id).remove();
            }
        });

        $(document).on('click', '.js-remove-attachment', function() {
            var remove_item_sid = $(this).attr('item-sid');
            var attachment_type = $(this).attr('attachment-type');
            var remove_item_type = $(this).attr('item-type');

            if (attachment_type == 'library') {

                $('#lib_item_' + remove_item_sid).remove();
                if (remove_item_type == "Document") {

                    $("#doc_key_" + remove_item_sid).prop("checked", false);
                } else {

                    $("#med_key_" + remove_item_sid).prop("checked", false);
                }
            } else {
                $('#man_item_' + remove_item_sid).remove();
            }
        });

        $('.show_manual_attachment').on('click', function() {
            $('#attachment_item_title').val('');
            $('#attach_social_video').val('');
            $('#default_manual_select').prop("checked", true);

            $("#attach_video").val(null);
            $("#name_attach_video").html('');

            $("#attach_audio").val(null);
            $("#name_attach_audio").html('');

            $("#attach_document").val(null);
            $("#name_attach_document").html('');

            $('#attachment_yt_vm_video_container input').prop('disabled', false);
            $('#attachment_yt_vm_video_container').show();

            $('#attachment_video_container input').prop('disabled', true);
            $('#attachment_video_container').hide();

            $('#attachment_audio_container input').prop('disabled', true);
            $('#attachment_audio_container').hide();

            $('#attachment_document_container input').prop('disabled', true);
            $('#attachment_document_container').hide();

            $("#manual_attachment_modal").modal('show');
        });

        $('.attach_item_source').on('click', function() {
            var selected = $(this).val();

            if (selected == 'youtube') {
                $('#attachment_yt_vm_video_container input').prop('disabled', false);
                $('#attachment_yt_vm_video_container').show();

                $('#attachment_video_container input').prop('disabled', true);
                $('#attachment_video_container').hide();

                $('#attachment_audio_container input').prop('disabled', true);
                $('#attachment_audio_container').hide();

                $('#attachment_document_container input').prop('disabled', true);
                $('#attachment_document_container').hide();

            } else if (selected == 'vimeo') {
                $('#attachment_yt_vm_video_container input').prop('disabled', false);
                $('#attachment_yt_vm_video_container').show();

                $('#attachment_video_container input').prop('disabled', true);
                $('#attachment_video_container').hide();

                $('#attachment_audio_container input').prop('disabled', true);
                $('#attachment_audio_container').hide();

                $('#attachment_document_container input').prop('disabled', true);
                $('#attachment_document_container').hide();

            } else if (selected == 'upload_video') {
                $('#attachment_yt_vm_video_container input').prop('disabled', true);
                $('#attachment_yt_vm_video_container').hide();

                $('#attachment_video_container input').prop('disabled', false);
                $('#attachment_video_container').show();

                $('#attachment_audio_container input').prop('disabled', true);
                $('#attachment_audio_container').hide();

                $('#attachment_document_container input').prop('disabled', true);
                $('#attachment_document_container').hide();

            } else if (selected == 'upload_audio') {
                $('#attachment_yt_vm_video_container input').prop('disabled', true);
                $('#attachment_yt_vm_video_container').hide();

                $('#attachment_video_container input').prop('disabled', true);
                $('#attachment_video_container').hide();

                $('#attachment_audio_container input').prop('disabled', false);
                $('#attachment_audio_container').show();

                $('#attachment_document_container input').prop('disabled', true);
                $('#attachment_document_container').hide();

            } else if (selected == 'upload_document') {
                $('#attachment_yt_vm_video_container input').prop('disabled', true);
                $('#attachment_yt_vm_video_container').hide();

                $('#attachment_video_container input').prop('disabled', true);
                $('#attachment_video_container').hide();

                $('#attachment_audio_container input').prop('disabled', true);
                $('#attachment_audio_container').hide();

                $('#attachment_document_container input').prop('disabled', false);
                $('#attachment_document_container').show();

            }
        });

        function check_attach_video(val) {
            var fileName = $("#" + val).val();

            if (fileName.length > 0) {
                $('#name_' + val).html(fileName.substring(0, 45));
                var ext = fileName.split('.').pop();
                var ext = ext.toLowerCase();

                if (val == 'attach_video') {
                    if (ext != "mp4" && ext != "m4a" && ext != "m4v" && ext != "f4v" && ext != "f4a" && ext != "m4b" && ext != "m4r" && ext != "f4b" && ext != "mov") {
                        $("#" + val).val(null);
                        alertify.alert("Please select a valid video format.");
                        $('#name_' + val).html('<p class="red">Only (.mp4, .m4a, .m4v, .f4v, .f4a, .m4b, .m4r, .f4b, .mov) allowed!</p>');
                        return false;
                    } else {
                        var file_size = Number(($("#" + val)[0].files[0].size / 1024 / 1024).toFixed(2));
                        var video_size_limit = Number('<?php echo UPLOAD_VIDEO_SIZE; ?>');
                        if (video_size_limit < file_size) {
                            $("#" + val).val(null);
                            alertify.alert('<?php echo ERROR_UPLOAD_VIDEO_SIZE; ?>');
                            $('#name_' + val).html('');
                            return false;
                        } else {
                            var selected_file = fileName;
                            var original_selected_file = selected_file.substring(selected_file.lastIndexOf('\\') + 1, selected_file.length);
                            $('#name_' + val).html(original_selected_file);
                            return true;
                        }

                    }
                }
            } else {
                $('#name_' + val).html('No video selected');
                alertify.alert("No video selected");
                $('#name_' + val).html('<p class="red">Please select video</p>');
                return false;
            }
        }

        function check_attach_audio(val) {
            var fileName = $("#" + val).val();

            if (fileName.length > 0) {
                $('#name_' + val).html(fileName.substring(0, 45));
                var ext = fileName.split('.').pop();
                var ext = ext.toLowerCase();

                if (val == 'attach_audio') {
                    if (ext != "mp3" && ext != "m4a" && ext != "mp4" && ext != "ogg" && ext != "flac" && ext != "wav") {
                        $("#" + val).val(null);
                        alertify.alert("Please select a valid Audio format.");
                        $('#name_' + val).html('<p class="red">Only (.mp3, .m4a, .mp4, .ogg, .flac, .wav) allowed!</p>');
                        return false;
                    } else {
                        var file_size = Number(($("#" + val)[0].files[0].size / 1024 / 1024).toFixed(2));
                        var audio_size_limit = Number('<?php echo UPLOAD_AUDIO_SIZE; ?>');
                        if (audio_size_limit < file_size) {
                            $("#" + val).val(null);
                            alertify.alert('<?php echo ERROR_UPLOAD_AUDIO_SIZE; ?>');
                            $('#name_' + val).html('');
                            return false;
                        } else {
                            var selected_file = fileName;
                            var original_selected_file = selected_file.substring(selected_file.lastIndexOf('\\') + 1, selected_file.length);
                            $('#name_' + val).html(original_selected_file);
                            return true;
                        }

                    }
                }
            } else {
                $('#name_' + val).html('No audio selected');
                alertify.alert("No audio selected");
                $('#name_' + val).html('<p class="red">Please select audio</p>');
                return false;
            }
        }

        function check_attach_document(val) {
            var fileName = $("#" + val).val();

            if (fileName.length > 0) {
                $('#name_' + val).html(fileName.substring(0, 45));
                var ext = fileName.split('.').pop();
                var ext = ext.toLowerCase();

                if (val == 'attach_document') {
                    if (ext != "pdf" && ext != "doc" && ext != "docx" && ext != "jpg" && ext != "jpe" && ext != "jpeg" && ext != "png" && ext != "gif") {
                        $("#" + val).val(null);
                        alertify.alert("Please select a valid document format.");
                        $('#name_' + val).html('<p class="red">Only (.pdf, .doc, .docx, .jpg, .jpe, .jpeg, .png, .gif) allowed!</p>');
                        return false;
                    } else {
                        var selected_file = fileName;
                        var original_selected_file = selected_file.substring(selected_file.lastIndexOf('\\') + 1, selected_file.length);
                        $('#name_' + val).html(original_selected_file);
                        return true;
                    }
                }
            } else {
                $('#name_' + val).html('No document selected');
                alertify.alert("No document selected");
                $('#name_' + val).html('<p class="red">Please select document</p>');
                return false;
            }
        }

        var item = 1;
        $('#save_attach_item').on('click', function() {

            var flag = 0;
            var message;
            var item_type;
            var item_source;
            var document_type;
            var source = $('input[name="attach_item_source"]:checked').val();

            if (source == 'youtube') {
                if ($('#attach_social_video').val() != '') {
                    var p = /(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.)?youtube\.com\/watch(?:\.php)?\?.*v=)([a-zA-Z0-9\-_]+)/;
                    if (!$('#attach_social_video').val().match(p)) {
                        message = 'Not a Valid Youtube URL';
                        flag = 1;
                    }
                } else {
                    message = 'Please provide a Valid Youtube URL';
                    flag = 1;
                }
            }

            if (source == 'vimeo') {
                if ($('#attach_social_video').val() != '') {
                    var myurl = "<?php echo base_url('Incident_reporting_system/validate_vimeo'); ?>";
                    $.ajax({
                        type: "POST",
                        url: myurl,
                        data: {
                            url: $('#attach_social_video').val()
                        },
                        async: false,
                        success: function(data) {
                            if (data == false) {
                                message = 'Not a Valid Vimeo URLs';
                                flag = 1;
                            }
                        },
                        error: function(data) {}
                    });
                } else {
                    message = 'Please provide a Valid Vimeo URL';
                    flag = 1;
                }
            }

            if (source == 'upload_video') {
                var fileName = $("#attach_video").val();

                if (fileName.length > 0) {
                    $('#name_attach_video').html(fileName.substring(0, 45));
                    var ext = fileName.split('.').pop();
                    var ext = ext.toLowerCase();


                    if (ext != "mp4" && ext != "m4a" && ext != "m4v" && ext != "f4v" && ext != "f4a" && ext != "m4b" && ext != "m4r" && ext != "f4b" && ext != "mov") {
                        $("#attach_video").val(null);
                        $('#name_attach_video').html('<p class="red">Only (.mp4, .m4a, .m4v, .f4v, .f4a, .m4b, .m4r, .f4b, .mov) allowed!</p>');
                        message = 'Please select a valid video format.';
                        flag = 1;
                    } else {
                        var file_size = Number(($("#attach_video")[0].files[0].size / 1024 / 1024).toFixed(2));
                        var video_size_limit = Number('<?php echo UPLOAD_VIDEO_SIZE; ?>');
                        if (video_size_limit < file_size) {
                            $("#attach_video").val(null);
                            $('#name_attach_video').html('');
                            message = '<?php echo ERROR_UPLOAD_VIDEO_SIZE; ?>';
                            flag = 1;
                        }
                    }
                } else {
                    $('#name_attach_video').html('<p class="red">Please select video</p>');
                    message = 'Please select video to upload';
                    flag = 1;
                }
            }

            if (source == 'upload_audio') {
                var fileName = $("#attach_audio").val();

                if (fileName.length > 0) {
                    $('#name_attach_audio').html(fileName.substring(0, 45));
                    var ext = fileName.split('.').pop();
                    var ext = ext.toLowerCase();


                    if (ext != "mp3" && ext != "m4a" && ext != "mp4" && ext != "ogg" && ext != "flac" && ext != "wav") {
                        $("#attach_audio").val(null);
                        $('#name_attach_audio').html('<p class="red">Only (.mp3, .m4a, .mp4, .ogg, .flac, .wav) allowed!</p>');
                        message = 'Please select a valid audio format.';
                        flag = 1;
                    } else {
                        var file_size = Number(($("#attach_audio")[0].files[0].size / 1024 / 1024).toFixed(2));
                        var audio_size_limit = Number('<?php echo UPLOAD_AUDIO_SIZE; ?>');
                        if (audio_size_limit < file_size) {
                            $("#attach_audio").val(null);
                            $('#name_attach_audio').html('');
                            message = '<?php echo ERROR_UPLOAD_AUDIO_SIZE; ?>';
                            flag = 1;
                        }
                    }
                } else {
                    $('#name_attach_audio').html('<p class="red">Please select audio</p>');
                    message = 'Please select audio to upload';
                    flag = 1;
                }
            }

            if (source == 'upload_document') {
                var fileName = $("#attach_document").val();

                if (fileName.length > 0) {
                    $('#name_attach_document').html(fileName.substring(0, 45));
                    var ext = fileName.split('.').pop();
                    var ext = ext.toLowerCase();
                    document_type = ext.toUpperCase();

                    if (ext != "pdf" && ext != "doc" && ext != "docx" && ext != "jpg" && ext != "jpe" && ext != "jpeg" && ext != "png" && ext != "gif") {
                        $("#attach_document").val(null);
                        $('#name_attach_document').html('<p class="red">Only (.pdf, .doc, .docx, .jpg, .jpe, .jpeg, .png, .gif) allowed!</p>');
                        message = 'Please select a valid document format.';
                        flag = 1;
                    }
                } else {
                    $('#name_attach_document').html('<p class="red">Please select document</p>');
                    message = 'Please select document to upload';
                    flag = 1;
                }
            }

            var attachment_title = $('#attachment_item_title').val();

            if (attachment_title == '' || attachment_title.length == 0) {
                message = 'Please provide a Video Title.';
                flag = 1;
            }

            if (flag == 1) {
                alertify.alert(message);
                return false;
            } else {

                var form_data = new FormData();
                var upload_data = '';

                if (source == 'youtube') {
                    item_type = 'Media';
                    item_source = 'Youtube';

                    var youtube_video_link = $('#attach_social_video').val();
                    upload_data = youtube_video_link;

                } else if (source == 'vimeo') {
                    item_type = 'Media';
                    item_source = 'Vimeo';

                    var vimeo_video_link = $('#attach_social_video').val();
                    upload_data = vimeo_video_link;

                } else if (source == 'upload_video') {
                    item_type = 'Media';
                    item_source = 'Upload Video';

                    var video_data = $('#attach_video').prop('files')[0];
                    upload_data = video_data;
                    var item_id = attachment_title.replace(/ /g, '');
                    $("#attach_video").clone().prop('id', item_id).insertAfter("div#email_attachment_files:last");
                    $("#" + item_id).hide();

                } else if (source == 'upload_audio') {
                    item_type = 'Media';
                    item_source = 'Upload Audio';

                    var audio_data = $('#attach_audio').prop('files')[0];
                    upload_data = audio_data;
                    var item_id = attachment_title.replace(/ /g, '');
                    $("#attach_audio").clone().prop('id', item_id).insertAfter("div#email_attachment_files:last");
                    $("#" + item_id).hide();

                } else if (source == 'upload_document') {
                    item_type = 'Document';
                    item_source = document_type;

                    var document_data = $('#attach_document').prop('files')[0];
                    upload_data = document_data;
                    var item_id = attachment_title.replace(/ /g, '');
                    $("#attach_document").clone().prop('id', item_id).insertAfter("div#email_attachment_files:last");
                    $("#" + item_id).hide();

                }

                $("#manual_attachment_modal").modal('hide');
                $('#email_attachment_list').show();
                $('#attachment_listing_data').prepend('<tr id="man_item_' + item + '" class="manual_upload_items" item-status="pending" row-id="man_item_' + item + '" item-title="' + attachment_title + '" item-source="' + source + '" item-data="' + upload_data + '"><td class="text-center">' + attachment_title + '</td><td class="text-center">' + item_type + '</td><td class="text-center">' + item_source + '</td><td><a href="javascript:;" item-sid="' + item + '" attachment-type="manual" item-type="' + item_source + '" class="btn btn-block btn-info js-remove-attachment">Remove</a></td></tr>');

                ++item;
            }
        });

        function view_attach_item(source) {
            var item_category = $(source).attr('item-category');
            var item_title = $(source).attr('item-title');
            var item_url = $(source).attr('item-url');
            var item_type = $(source).attr('item-type');


            $("#view_media_document_modal").modal('show');
            $("#view_item_title").html(item_title);
            $("#close_media_document_modal_up").attr('file-type', item_type);
            $("#close_media_document_modal_down").attr('file-type', item_type);

            if (item_category == 'Document') {
                $('#document-container').show();
                if (item_type == 'document') {
                    var document_content = $("<iframe />")
                        .attr("id", "document-iframe")
                        .attr("class", "uploaded-file-preview")
                        .attr("src", item_url);
                    $("#document-iframe-holder").html(document_content);
                } else {
                    var image_content = $("<img />")
                        .attr("id", "image-tag")
                        .attr("class", "img-responsive")
                        .attr("src", item_url);
                    $("#document-iframe-holder").html(image_content);
                }

            } else {

                if (item_type == 'youtube') {

                    $('#youtube-container').show();
                    var video = $("<iframe />")
                        .attr("id", "youtube-iframe")
                        .attr("src", "https://www.youtube.com/embed/" + item_url);
                    $("#youtube-iframe-holder").append(video);

                } else if (item_type == 'vimeo') {

                    $('#vimeo-container').show();
                    var video = $("<iframe />")
                        .attr("id", "vimeo-iframe")
                        .attr("src", "https://player.vimeo.com/video/" + item_url);
                    $("#vimeo-iframe-holder").append(video);

                } else if (item_type == 'upload_video') {
                    $('#video-container').show();
                    var video = $("<video />")
                        .attr("id", "video-player")
                        .attr('src', item_url)
                        .attr('controls', true);
                    $("#video-player-holder").append(video);

                } else if (item_type == 'upload_audio') {
                    $('#audio-container').show();
                    var audio = $("<audio />")
                        .attr("id", "audio-player")
                        .attr('src', item_url)
                        .attr('controls', true);
                    $("#audio-player-holder").append(audio);
                }
            }
        }

        $('.close-current-item').on('click', function() {
            var item_type = $(this).attr('file-type');

            if (item_type == 'youtube') {
                $("#youtube-iframe").remove();
                $('#youtube-container').hide();
            } else if (item_type == 'vimeo') {
                $("#vimeo-iframe").remove();
                $('#vimeo-container').hide();
            } else if (item_type == 'upload_video') {
                $("#video-player").remove();
                $('#video-container').hide();
            } else if (item_type == 'upload_audio') {
                $("#audio-player").remove();
                $('#audio-container').hide();
            } else if (item_type == 'document') {
                $("#document-iframe").remove();
                $('#document-container').hide();
            } else if (item_type == 'image') {
                $("#image-tag").remove();
                $('#document-container').hide();
            }
        });

        function send_email(source) {
            var email_type = $(source).attr('data-type');
            var email_reciever = $(source).attr('data-sid');
            var email_subject = $(source).attr('data-subject');
            var email_title = $(source).attr('data-title');

            if (email_type == 'system') {
                var system_user_email = $(source).attr('data-email');
                $('#send_email_address').val(system_user_email);
                $('#send_email_user').attr('name', 'receivers[]');
                var user = [email_reciever];
                $('#send_email_user').val(user);
            } else {
                $('#send_email_address').val(email_reciever);
                $('#send_email_user').attr('name', 'manual_email');
                $('#send_email_user').val(email_reciever);
            }

            if (email_title == 'reply') {
                email_title = '<i class="fa fa-reply"></i> Reply Email';
            } else if (email_title == 'resend') {
                email_title = '<i class="fa fa-retweet"></i> Resend Email';
            }

            $('#send_email_pop_up_title').html(email_title);
            $('#send_email_type').val(email_type);
            $('#send_email_subject').val(email_subject);
            $('#send_email_modal').modal('show');
        }

        $(".attachment_pop_up").on('click', function() {
            var attachment_type = $(this).attr('attachment-type');

            if (attachment_type == 'library') {
                $("#pop_up_email_compose_container").hide();
                $("#pop_up_attachment_library_container").show();
            } else {
                $("#pop_up_email_compose_container").hide();
                reset_manual_input_fields();
                $("#pop_up_manual_attachment_container").show();
            }
        });

        function view_pop_up_library_item(source) {
            var item_category = $(source).attr('item-category');
            var item_title = $(source).attr('item-title');
            var item_url = $(source).attr('item-url');
            var item_type = $(source).attr('item-type');

            $("#show_pop_up_library_item").hide();
            $("#view_pop_up_library_item").show();
            $("#pop_up_library_item_title").html(item_title);
            $('.email_pop_up_back_to_library').attr('item-type', item_type);

            if (item_category == 'document') {

                $('#email-pop-up-document-container').show();
                if (item_type == 'document') {
                    var document_content = $("<iframe />")
                        .attr("id", "email-pop-up-document-iframe")
                        .attr("class", "uploaded-file-preview")
                        .attr("src", item_url);
                    $("#email-pop-up-document-iframe-holder").append(document_content);
                } else {
                    var image_content = $("<img />")
                        .attr("id", "email-pop-up-image-tag")
                        .attr("class", "img-responsive")
                        .attr("src", item_url);
                    $("#email-pop-up-document-iframe-holder").append(image_content);
                }

            } else {

                if (item_type == 'youtube') {

                    $('#email-pop-up-youtube-container').show();
                    var video = $("<iframe />")
                        .attr("id", "email-pop-up-youtube-iframe")
                        .attr("src", "https://www.youtube.com/embed/" + item_url);
                    $("#email-pop-up-youtube-iframe-holder").append(video);

                } else if (item_type == 'vimeo') {

                    $('#email-pop-up-vimeo-container').show();
                    var video = $("<iframe />")
                        .attr("id", "email-pop-up-vimeo-iframe")
                        .attr("src", "https://player.vimeo.com/video/" + item_url);
                    $("#email-pop-up-vimeo-container").append(video);

                } else if (item_type == 'upload_video') {
                    $('#email-pop-up-video-container').show();
                    var video = $("<video />")
                        .attr("id", "email-pop-up-video-player")
                        .attr('src', item_url)
                        .attr('controls', true);
                    $("#email-pop-up-video-player-holder").append(video);

                } else if (item_type == 'upload_audio') {
                    $('#email-pop-up-audio-container').show();
                    var audio = $("<audio />")
                        .attr("id", "email-pop-up-audio-player")
                        .attr('src', item_url)
                        .attr('controls', true);
                    $("#email-pop-up-audio-player-holder").append(audio);
                }
            }
        }

        $(".email_pop_up_back_to_library").on("click", function() {
            var item_type = $(".email_pop_up_back_to_library").attr('item-type');

            if (item_type == 'youtube') {
                $("#email-pop-up-youtube-iframe").remove();
                $('#email-pop-up-youtube-container').hide();
            } else if (item_type == 'vimeo') {
                $("#email-pop-up-vimeo-iframe").remove();
                $('#email-pop-up-vimeo-container').hide();
            } else if (item_type == 'upload_video') {
                $("#email-pop-up-video-player").remove();
                $('#email-pop-up-video-container').hide();
            } else if (item_type == 'upload_audio') {
                $("#email-pop-up-audio-player").remove();
                $('#email-pop-up-audio-container').hide();
            } else if (item_type == 'document') {
                $("#email-pop-up-document-iframe").remove();
                $('#email-pop-up-document-container').hide();
            } else if (item_type == 'image') {
                $("#email-pop-up-image-tag").remove();
                $('#email-pop-up-document-container').hide();
            }

            $("#view_pop_up_library_item").hide();
            $("#show_pop_up_library_item").show();
        });

        $(".email_pop_up_back_to_compose_email").on("click", function() {
            var button_from = $(this).attr('btn-from');

            if (button_from == 'library') {
                $("#pop_up_attachment_library_container").hide();
                $("#pop_up_email_compose_container").show();
            } else if (button_from == 'manual') {
                $("#pop_up_manual_attachment_container").hide();
                $("#pop_up_email_compose_container").show();
            } else {
                $("#pop_up_attachment_library_container").hide();
                $("#pop_up_manual_attachment_container").hide();
                $("#pop_up_email_compose_container").show();
            }
        });

        $(".email_pop_up_select_lib_item").on("click", function() {
            var item_id = $(this).attr("item-sid");

            if ($(this).prop('checked') == true) {

                var item_type = $(this).attr("item-category");
                var item_source = $(this).attr("item-type");
                var item_title = $(this).attr("item-title");

                $('#pop_up_email_attachment_list').show();
                $('#pop_up_attachment_listing_data').prepend('<tr id="pop_up_lib_item_' + item_id + '"><input type="hidden" name="attachment[' + item_id + '][item_type]" value="' + item_type + '"><input type="hidden" name="attachment[' + item_id + '][record_sid]" value="' + item_id + '"><td class="text-center">' + item_title + '</td><td class="text-center">' + item_type + '</td><td class="text-center">' + item_source + '</td><td><a href="javascript:;" item-sid="' + item_id + '" attachment-type="library" item-type="' + item_type + '" class="btn btn-block btn-info js-pop-up-remove-attachment">Remove</a></td></tr>');
            } else {
                $('#pop_up_lib_item_' + item_id).remove();
            }
        });

        $(document).on('click', '.js-pop-up-remove-attachment', function() {
            var remove_item_sid = $(this).attr('item-sid');
            var attachment_type = $(this).attr('attachment-type');
            var remove_item_type = $(this).attr('item-type')

            if (attachment_type == 'library') {
                $('#pop_up_lib_item_' + remove_item_sid).remove();
                if (remove_item_type == "Document") {

                    $("#pop_up_doc_key_" + remove_item_sid).prop("checked", false);
                } else {

                    $("#pop_up_med_key_" + remove_item_sid).prop("checked", false);
                }
            } else {
                $('#pop_up_man_item_' + remove_item_sid).remove();
            }
        });

        function reset_manual_input_fields() {

            $('#pop_up_attachment_item_title').val('');
            $('#pop_up_attach_social_video').val('');
            $('#default_manual_pop_up').prop("checked", true);

            $("#pop_up_attach_video").val(null);
            $("#name_pop_up_attach_video").html('');

            $("#pop_up_attach_audio").val(null);
            $("#name_pop_up_attach_audio").html('');

            $("#pop_up_attach_document").val(null);
            $("#name_pop_up_attach_document").html('');

            $('#pop_up_attachment_yt_vm_video_input_container input').prop('disabled', false);
            $('#pop_up_attachment_yt_vm_video_input_container').show();

            $('#pop_up_attachment_upload_video_input_container input').prop('disabled', true);
            $('#pop_up_attachment_upload_video_input_container').hide();

            $('#pop_up_attachment_upload_audio_input_container input').prop('disabled', true);
            $('#pop_up_attachment_upload_audio_input_container').hide();

            $('#pop_up_attachment_upload_document_input_container input').prop('disabled', true);
            $('#pop_up_attachment_upload_document_input_container').hide();
        }

        $('.pop_up_attach_item_source').on('click', function() {
            var selected = $(this).val();

            if (selected == 'youtube') {
                $('#pop_up_attachment_yt_vm_video_input_container input').prop('disabled', false);
                $('#pop_up_attachment_yt_vm_video_input_container').show();

                $('#pop_up_attachment_upload_video_input_container input').prop('disabled', true);
                $('#pop_up_attachment_upload_video_input_container').hide();

                $('#pop_up_attachment_upload_audio_input_container input').prop('disabled', true);
                $('#pop_up_attachment_upload_audio_input_container').hide();

                $('#pop_up_attachment_upload_document_input_container input').prop('disabled', true);
                $('#pop_up_attachment_upload_document_input_container').hide();

            } else if (selected == 'vimeo') {
                $('#pop_up_attachment_yt_vm_video_input_container input').prop('disabled', false);
                $('#pop_up_attachment_yt_vm_video_input_container').show();

                $('#pop_up_attachment_upload_video_input_container input').prop('disabled', true);
                $('#pop_up_attachment_upload_video_input_container').hide();

                $('#pop_up_attachment_upload_audio_input_container input').prop('disabled', true);
                $('#pop_up_attachment_upload_audio_input_container').hide();

                $('#pop_up_attachment_upload_document_input_container input').prop('disabled', true);
                $('#pop_up_attachment_upload_document_input_container').hide();

            } else if (selected == 'upload_video') {
                $('#pop_up_attachment_yt_vm_video_input_container input').prop('disabled', true);
                $('#pop_up_attachment_yt_vm_video_input_container').hide();

                $('#pop_up_attachment_upload_video_input_container input').prop('disabled', false);
                $('#pop_up_attachment_upload_video_input_container').show();

                $('#pop_up_attachment_upload_audio_input_container input').prop('disabled', true);
                $('#pop_up_attachment_upload_audio_input_container').hide();

                $('#pop_up_attachment_upload_document_input_container input').prop('disabled', true);
                $('#pop_up_attachment_upload_document_input_container').hide();

            } else if (selected == 'upload_audio') {
                $('#pop_up_attachment_yt_vm_video_input_container input').prop('disabled', true);
                $('#pop_up_attachment_yt_vm_video_input_container').hide();

                $('#pop_up_attachment_upload_video_input_container input').prop('disabled', true);
                $('#pop_up_attachment_upload_video_input_container').hide();

                $('#pop_up_attachment_upload_audio_input_container input').prop('disabled', false);
                $('#pop_up_attachment_upload_audio_input_container').show();

                $('#pop_up_attachment_upload_document_input_container input').prop('disabled', true);
                $('#pop_up_attachment_upload_document_input_container').hide();

            } else if (selected == 'upload_document') {
                $('#pop_up_attachment_yt_vm_video_input_container input').prop('disabled', true);
                $('#pop_up_attachment_yt_vm_video_input_container').hide();

                $('#pop_up_attachment_upload_video_input_container input').prop('disabled', true);
                $('#pop_up_attachment_upload_video_input_container').hide();

                $('#pop_up_attachment_upload_audio_input_container input').prop('disabled', true);
                $('#pop_up_attachment_upload_audio_input_container').hide();

                $('#pop_up_attachment_upload_document_input_container input').prop('disabled', false);
                $('#pop_up_attachment_upload_document_input_container').show();

            }
        });

        function pop_up_check_attach_video(val) {
            var fileName = $("#" + val).val();

            if (fileName.length > 0) {
                $('#name_' + val).html(fileName.substring(0, 45));
                var ext = fileName.split('.').pop();
                var ext = ext.toLowerCase();

                if (val == 'pop_up_attach_video') {
                    if (ext != "mp4" && ext != "m4a" && ext != "m4v" && ext != "f4v" && ext != "f4a" && ext != "m4b" && ext != "m4r" && ext != "f4b" && ext != "mov") {
                        $("#" + val).val(null);
                        alertify.alert("Please select a valid video format.");
                        $('#name_' + val).html('<p class="red">Only (.mp4, .m4a, .m4v, .f4v, .f4a, .m4b, .m4r, .f4b, .mov) allowed!</p>');
                        return false;
                    } else {
                        var file_size = Number(($("#" + val)[0].files[0].size / 1024 / 1024).toFixed(2));
                        var video_size_limit = Number('<?php echo UPLOAD_VIDEO_SIZE; ?>');
                        if (video_size_limit < file_size) {
                            $("#" + val).val(null);
                            alertify.alert('<?php echo ERROR_UPLOAD_VIDEO_SIZE; ?>');
                            $('#name_' + val).html('');
                            return false;
                        } else {
                            var selected_file = fileName;
                            var original_selected_file = selected_file.substring(selected_file.lastIndexOf('\\') + 1, selected_file.length);
                            $('#name_' + val).html(original_selected_file);
                            return true;
                        }

                    }
                }
            } else {
                $('#name_' + val).html('No video selected');
                alertify.alert("No video selected");
                $('#name_' + val).html('<p class="red">Please select video</p>');
                return false;
            }
        }

        function pop_up_check_attach_audio(val) {
            var fileName = $("#" + val).val();

            if (fileName.length > 0) {
                $('#name_' + val).html(fileName.substring(0, 45));
                var ext = fileName.split('.').pop();
                var ext = ext.toLowerCase();

                if (val == 'pop_up_attach_audio') {
                    if (ext != "mp3" && ext != "m4a" && ext != "mp4" && ext != "ogg" && ext != "flac" && ext != "wav") {
                        $("#" + val).val(null);
                        alertify.alert("Please select a valid Audio format.");
                        $('#name_' + val).html('<p class="red">Only (.mp3, .m4a, .mp4, .ogg, .flac, .wav) allowed!</p>');
                        return false;
                    } else {
                        var file_size = Number(($("#" + val)[0].files[0].size / 1024 / 1024).toFixed(2));
                        var audio_size_limit = Number('<?php echo UPLOAD_AUDIO_SIZE; ?>');
                        if (audio_size_limit < file_size) {
                            $("#" + val).val(null);
                            alertify.alert('<?php echo ERROR_UPLOAD_AUDIO_SIZE; ?>');
                            $('#name_' + val).html('');
                            return false;
                        } else {
                            var selected_file = fileName;
                            var original_selected_file = selected_file.substring(selected_file.lastIndexOf('\\') + 1, selected_file.length);
                            $('#name_' + val).html(original_selected_file);
                            return true;
                        }

                    }
                }
            } else {
                $('#name_' + val).html('No audio selected');
                alertify.alert("No audio selected");
                $('#name_' + val).html('<p class="red">Please select audio</p>');
                return false;
            }
        }

        function pop_up_check_attach_document(val) {
            var fileName = $("#" + val).val();

            if (fileName.length > 0) {
                $('#name_' + val).html(fileName.substring(0, 45));
                var ext = fileName.split('.').pop();
                var ext = ext.toLowerCase();

                if (val == 'pop_up_attach_document') {
                    if (ext != "pdf" && ext != "doc" && ext != "docx" && ext != "jpg" && ext != "jpe" && ext != "jpeg" && ext != "png" && ext != "gif") {
                        $("#" + val).val(null);
                        alertify.alert("Please select a valid document format.");
                        $('#name_' + val).html('<p class="red">Only (.pdf, .doc, .docx, .jpg, .jpe, .jpeg, .png, .gif) allowed!</p>');
                        return false;
                    } else {
                        var selected_file = fileName;
                        var original_selected_file = selected_file.substring(selected_file.lastIndexOf('\\') + 1, selected_file.length);
                        $('#name_' + val).html(original_selected_file);
                        return true;
                    }
                }
            } else {
                $('#name_' + val).html('No document selected');
                alertify.alert("No document selected");
                $('#name_' + val).html('<p class="red">Please select document</p>');
                return false;
            }
        }

        var pop_up_item = 1;
        $('#pop_up_save_attach_item').on('click', function() {

            var flag = 0;
            var message;
            var item_type;
            var item_source;
            var document_type;
            var source = $('input[name="pop_up_attach_item_source"]:checked').val();

            if (source == 'youtube') {
                if ($('#pop_up_attach_social_video').val() != '') {
                    var p = /(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.)?youtube\.com\/watch(?:\.php)?\?.*v=)([a-zA-Z0-9\-_]+)/;
                    if (!$('#pop_up_attach_social_video').val().match(p)) {
                        message = 'Not a Valid Youtube URL';
                        flag = 1;
                    }
                } else {
                    message = 'Please provide a Valid Youtube URL';
                    flag = 1;
                }
            }

            if (source == 'vimeo') {
                if ($('#pop_up_attach_social_video').val() != '') {
                    var myurl = "<?php echo base_url('Incident_reporting_system/validate_vimeo'); ?>";
                    $.ajax({
                        type: "POST",
                        url: myurl,
                        data: {
                            url: $('#pop_up_attach_social_video').val()
                        },
                        async: false,
                        success: function(data) {
                            if (data == false) {
                                message = 'Not a Valid Vimeo URLs';
                                flag = 1;
                            }
                        },
                        error: function(data) {}
                    });
                } else {
                    message = 'Please provide a Valid Vimeo URL';
                    flag = 1;
                }
            }

            if (source == 'upload_video') {
                var fileName = $("#pop_up_attach_video").val();

                if (fileName.length > 0) {
                    $('#name_pop_up_attach_video').html(fileName.substring(0, 45));
                    var ext = fileName.split('.').pop();
                    var ext = ext.toLowerCase();


                    if (ext != "mp4" && ext != "m4a" && ext != "m4v" && ext != "f4v" && ext != "f4a" && ext != "m4b" && ext != "m4r" && ext != "f4b" && ext != "mov") {
                        $("#pop_up_attach_video").val(null);
                        $('#name_pop_up_attach_video').html('<p class="red">Only (.mp4, .m4a, .m4v, .f4v, .f4a, .m4b, .m4r, .f4b, .mov) allowed!</p>');
                        message = 'Please select a valid video format.';
                        flag = 1;
                    } else {
                        var file_size = Number(($("#pop_up_attach_video")[0].files[0].size / 1024 / 1024).toFixed(2));
                        var video_size_limit = Number('<?php echo UPLOAD_VIDEO_SIZE; ?>');
                        if (video_size_limit < file_size) {
                            $("#pop_up_attach_video").val(null);
                            $('#name_pop_up_attach_video').html('');
                            message = '<?php echo ERROR_UPLOAD_VIDEO_SIZE; ?>';
                            flag = 1;
                        }
                    }
                } else {
                    $('#name_pop_up_attach_video').html('<p class="red">Please select video</p>');
                    message = 'Please select video to upload';
                    flag = 1;
                }
            }

            if (source == 'upload_audio') {
                var fileName = $("#pop_up_attach_audio").val();

                if (fileName.length > 0) {
                    $('#name_pop_up_attach_audio').html(fileName.substring(0, 45));
                    var ext = fileName.split('.').pop();
                    var ext = ext.toLowerCase();


                    if (ext != "mp3" && ext != "m4a" && ext != "mp4" && ext != "ogg" && ext != "flac" && ext != "wav") {
                        $("#pop_up_attach_audio").val(null);
                        $('#name_pop_up_attach_audio').html('<p class="red">Only (.mp3, .m4a, .mp4, .ogg, .flac, .wav) allowed!</p>');
                        message = 'Please select a valid audio format.';
                        flag = 1;
                    } else {
                        var file_size = Number(($("#pop_up_attach_audio")[0].files[0].size / 1024 / 1024).toFixed(2));
                        var audio_size_limit = Number('<?php echo UPLOAD_AUDIO_SIZE; ?>');
                        if (audio_size_limit < file_size) {
                            $("#pop_up_attach_audio").val(null);
                            $('#name_pop_up_attach_audio').html('');
                            message = '<?php echo ERROR_UPLOAD_AUDIO_SIZE; ?>';
                            flag = 1;
                        }
                    }
                } else {
                    $('#name_pop_up_attach_audio').html('<p class="red">Please select audio</p>');
                    message = 'Please select audio to upload';
                    flag = 1;
                }
            }

            if (source == 'upload_document') {
                var fileName = $("#pop_up_attach_document").val();

                if (fileName.length > 0) {
                    $('#name_pop_up_attach_document').html(fileName.substring(0, 45));
                    var ext = fileName.split('.').pop();
                    var ext = ext.toLowerCase();
                    document_type = ext.toUpperCase();

                    if (ext != "pdf" && ext != "doc" && ext != "docx" && ext != "jpg" && ext != "jpe" && ext != "jpeg" && ext != "png" && ext != "gif") {
                        $("#pop_up_attach_document").val(null);
                        $('#name_pop_up_attach_document').html('<p class="red">Only (.pdf, .doc, .docx, .jpg, .jpe, .jpeg, .png, .gif) allowed!</p>');
                        message = 'Please select a valid document format.';
                        flag = 1;
                    }
                } else {
                    $('#name_pop_up_attach_document').html('<p class="red">Please select document</p>');
                    message = 'Please select document to upload';
                    flag = 1;
                }
            }

            var attachment_title = $('#pop_up_attachment_item_title').val();

            if (attachment_title == '' || attachment_title.length == 0) {
                message = 'Please provide a Video Title.';
                flag = 1;
            }

            if (flag == 1) {
                alertify.alert(message);
                return false;
            } else {

                var form_data = new FormData();
                var upload_data = '';

                if (source == 'youtube') {
                    item_type = 'Media';
                    item_source = 'Youtube';

                    var youtube_video_link = $('#pop_up_attach_social_video').val();
                    upload_data = youtube_video_link;

                } else if (source == 'vimeo') {
                    item_type = 'Media';
                    item_source = 'Vimeo';

                    var vimeo_video_link = $('#pop_up_attach_social_video').val();
                    upload_data = vimeo_video_link;

                } else if (source == 'upload_video') {
                    item_type = 'Media';
                    item_source = 'Upload Video';

                    var video_data = $('#pop_up_attach_video').prop('files')[0];
                    upload_data = video_data;
                    var item_id = attachment_title.replace(/ /g, '');
                    $("#pop_up_attach_video").clone().prop('id', item_id).insertAfter("div#pop_up_email_attachment_files:last");
                    $("#" + item_id).hide();

                } else if (source == 'upload_audio') {
                    item_type = 'Media';
                    item_source = 'Upload Audio';

                    var audio_data = $('#attach_audio').prop('files')[0];
                    upload_data = audio_data;
                    var item_id = attachment_title.replace(/ /g, '');
                    $("#pop_up_attach_audio").clone().prop('id', item_id).insertAfter("div#pop_up_email_attachment_files:last");
                    $("#" + item_id).hide();

                } else if (source == 'upload_document') {
                    item_type = 'Document';
                    item_source = document_type;

                    var document_data = $('#attach_document').prop('files')[0];
                    upload_data = document_data;
                    var item_id = attachment_title.replace(/ /g, '');
                    $("#pop_up_attach_document").clone().prop('id', item_id).insertAfter("div#pop_up_email_attachment_files:last");
                    $("#" + item_id).hide();

                }

                $("#pop_up_manual_attachment_container").hide();
                $("#pop_up_email_compose_container").show();
                $('#pop_up_email_attachment_list').show();
                $('#pop_up_attachment_listing_data').prepend('<tr id="pop_up_man_item_' + pop_up_item + '" class="pop_up_manual_upload_items" item-status="pending" row-id="man_item_' + pop_up_item + '" item-title="' + attachment_title + '" item-source="' + source + '" item-data="' + upload_data + '"><td class="text-center">' + attachment_title + '</td><td class="text-center">' + item_type + '</td><td class="text-center">' + item_source + '</td><td><a href="javascript:;" item-sid="' + pop_up_item + '" attachment-type="manual" item-type="' + item_source + '" class="btn btn-block btn-info js-pop-up-remove-attachment">Remove</a></td></tr>');

                ++pop_up_item;

            }
        });

        $("#send_pop_up_email").on('click', function() {
            var flag = 0;
            var message = '';
            var receivers;
            var manual_attachment_size = $('#pop_up_attachment_listing_data > .pop_up_manual_upload_items').size();

            var message_subject = $('#send_email_subject').val();
            var message_body = CKEDITOR.instances['send_email_message'].getData();

            if (message_subject == '' && message_body == '') {
                message = 'All fields are required.';
                flag = 1;
            } else if (message_body == '') {
                message = 'Message body is required.';
                flag = 1;
            } else if (message_subject == '') {
                message = 'Subject is required.';
                flag = 1;
            }

            if (manual_attachment_size > 0 && flag == 0) {
                $('#send_email_modal').modal('hide');
                $('#attachment_loader').show();
                $('#pop_up_attachment_listing_data > .pop_up_manual_upload_items').each(function(key) {
                    var item_status = $(this).attr('item-status');
                    if (item_status == 'pending') {
                        var item_row_id = $(this).attr('row-id');

                        var item_title = $(this).attr('item-title');
                        var item_source = $(this).attr('item-source');
                        var save_attachment_url = '<?php echo base_url('incident_reporting_system/save_email_manual_attachment'); ?>';

                        var form_data = new FormData();
                        form_data.append('attachment_title', item_title);

                        if (item_source == 'youtube' || item_source == 'vimeo') {
                            var social_url = $(this).attr('item-data');
                            form_data.append('social_url', social_url);
                        } else {
                            var item_id = item_title.replace(/ /g, '');
                            var item_file = $('#' + item_id).prop('files')[0];
                            form_data.append('file', item_file);

                            if (item_source == 'upload_document') {
                                var fileName = $('#' + item_id).val();
                                var file_ext = fileName.split('.').pop();
                                form_data.append('file_name', fileName.replace('C:\\fakepath\\', ''));
                                form_data.append('file_ext', file_ext);
                            }
                        }

                        form_data.append('file_type', item_source);
                        form_data.append('user_type', 'manager');
                        form_data.append('incident_sid', <?php echo $id; ?>);
                        form_data.append('company_sid', <?php echo $company_sid; ?>);
                        form_data.append('uploaded_by', <?php echo $current_user; ?>);

                        $.ajax({
                            url: save_attachment_url,
                            cache: false,
                            contentType: false,
                            processData: false,
                            type: 'post',
                            data: form_data,
                            success: function(response) {
                                var obj = jQuery.parseJSON(response);
                                var res_item_sid = obj['item_sid'];
                                var res_item_title = obj['item_title'];
                                var res_item_type = obj['item_type'];
                                var res_item_source = obj['item_source'];

                                $('#pop_up_' + item_row_id).html('<input type="hidden" name="attachment[' + res_item_sid + '][item_type]" value="' + res_item_type + '"><input type="hidden" name="attachment[' + res_item_sid + '][record_sid]" value="' + res_item_sid + '"><td class="text-center">' + res_item_title + '</td><td class="text-center">' + res_item_type + '</td><td class="text-center">' + res_item_source + '</td><td><a href="javascript:;" item-sid="' + res_item_sid + '" attachment-type="library" item-type="' + res_item_type + '" class="btn btn-block btn-info js-remove-attachment">Remove</a></td>');

                                $('#pop_up_' + item_row_id).attr("item-status", "done");

                                manual_attachment_size = manual_attachment_size - 1;

                                if (manual_attachment_size == 0) {
                                    setTimeout(function() {
                                        $("#send_pop_up_email").attr('type', 'submit');
                                        $('#send_pop_up_email').click();
                                    }, 1000);
                                }
                            },
                            error: function() {

                            }
                        });
                    }
                });
            } else {
                if (flag == 1) {
                    alertify.alert(message);
                    return false;
                } else {
                    $("#send_pop_up_email").attr('type', 'submit');
                    $('#send_pop_up_email').click();
                }
            }
        });

        // Email JS End

        // Notes JS Start
        $('#form_new_note').submit(function() {
            var note_type = $('#note_type').val();
            var comment_body = CKEDITOR.instances['response'].getData();
            var flag = 0;
            var message = '';

            if (note_type == '0' && comment_body == '') {
                message = 'All fields are required.';
                flag = 1;
            } else if (note_type == '0') {
                message = 'Please select note type';
                flag = 1;
            } else if (comment_body == '') {
                message = 'Note is required.';
                flag = 1;
            }

            if (flag == 1) {
                alertify.alert(message);
                return false;
            }
        });
        // Notes JS End

        // Reassign manager JS Start
        $('#form_reassign_manager').submit(function() {
            var receivers = $('#reassign_manager').val();

            if (receivers == null) {
                alertify.alert('Please Select Employee to Reassign.');
                return false;
            }
        });
        // Reassign manager JS End
    </script>