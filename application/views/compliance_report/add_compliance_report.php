<?php
$company_sid = 0;
$users_type = '';
$users_sid = 0;
$back_url = '';
$dependants_arr = array();
$delete_post_url = '';
$save_post_url = '';
$next_btn = '';
$center_btn = '';
$back_btn = 'Dashboard';

//
$currentEmployeeId = $session['employer_detail']['sid'];

if (isset($applicant)) {
    $company_sid = $applicant['employer_sid'];
    $users_type = 'applicant';
    $users_sid = $applicant['sid'];
    $back_url = base_url('onboarding/general_information/' . $unique_sid);
    $next_btn = '<a href="javascript:;" class="btn btn-success btn-block" id="go_next" onclick="func_save_e_signature();"> Save And Proceed Next <i class="fa fa-angle-right"></i></a>';
    $center_btn = '<a href="' . base_url('onboarding/documents/' . $unique_sid) . '" class="btn btn-warning btn-block"> Bypass This Step <i class="fa fa-angle-right"></i></a>';
    $back_btn = 'Review Previous Step';
    $save_post_url = current_url();
    $first_name = $applicant['first_name'];
    $last_name = $applicant['last_name'];
    $email = $applicant['email'];
} else if (isset($employee)) {
    $company_sid = $employee['parent_sid'];
    $users_type = 'employee';
    $users_sid = $employee['sid'];
    $back_url = $employee['access_level'] == 'Employee' ? base_url('dashboard') : base_url('employee_management_system');
    $save_post_url = current_url();
    $first_name = $employee['first_name'];
    $last_name = $employee['last_name'];
    $email = $employee['email'];
} ?>
<div class="main jsmaincontent">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
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
                        <a href="<?php echo base_url('incident_reporting_system/assigned_incidents'); ?>" class="btn btn-info btn-block mb-2"><i class="fa fa-stethoscope "></i> <?= $this->lang->line('tab_assigned_incidents', false) ?></a></a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo base_url('incident_reporting_system/safety_check_list') ?>" class="btn btn-info btn-block mb-2"><i class="fa fa-book"></i> Safety Check List </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="page-header">
                    <h2 class="section-ttile"><?php echo $title; ?></h2>
                </div>
                <div class="form-group">
                    <h3 class="text-blue">You are about to report a "<?php echo ucwords($report_type); ?>" Report</h3>
                </div>

                <form method="post" action="" id="inc-form" enctype="multipart/form-data" autocomplete="off">

                    <div class="form-wrp">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                        </div>

                        <?php if (sizeof($questions) > 0) { ?>
                            <?php if ($report_type == 'confidential') { ?>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="form-group">
                                            <label>Your Full Name:<span class="required">*</span></label>
                                            <?php echo form_input('full-name', set_value('full-name', $session['employer_detail']['first_name'] . " " . $session['employer_detail']['last_name']), 'class="form-control" id="full-name" readonly'); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="form-group">
                                        <label>Compliance Safety Title:<span class="required">*</span></label>
                                        <input type="text" class="form-control" name="compliance_safety_title" value="" id="jsComplianceSafetyTitle">
                                    </div>
                                </div>
                            </div>
                            <?php foreach ($questions as $question) { ?>
                                <?php echo '<div class="row"><div class="col-lg-12 col-md-12 col-xs-12 col-sm-12"><div class="form-group autoheight">'; ?>

                                <?php if ($question['question_type'] == 'textarea') { ?>
                                    <label class="auto-height"><?php echo strip_tags($question['label']); ?>: <span class="required required_<?php echo $question['related_to_question']; ?>"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
                                    <script type="text/javascript" src="<?php echo site_url('assets/ckeditor/ckeditor.js'); ?>"></script>
                                    <textarea id="text_<?php echo $question['id']; ?>" class="form-control textarea related_<?php echo $question['related_to_question']; ?>" data-require="<?php echo $question['is_required']; ?>" data-attr="<?php echo $question['related_to_question']; ?>" name="text_<?php echo $question['id']; ?>" rows="8" cols="60" <?php echo $question['is_required'] ? "required" : "" ?>><?php echo set_value('text_' . $question['id']); ?></textarea>
                                <?php } elseif ($question['question_type'] == 'text') { ?>
                                    <label class="auto-height"><?php echo strip_tags($question['label']) ?> : <span class="required required_<?php echo $question['related_to_question']; ?>"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
                                    <?php
                                    $required = $question['is_required'] ? "required" : "";
                                    echo form_input('text_' . $question['id'], set_value('text_' . $question['id']), 'class="form-control related_' . $question['related_to_question'] . '" id="' . $question['id'] . '" data-require="' . $question['is_required'] . '" ' . $required . ' data-attr="' . $question['related_to_question'] . '"');
                                    ?>
                                <?php } elseif ($question['question_type'] == 'time') { ?>
                                    <label class="auto-height"><?php echo strip_tags($question['label']) ?> : <span class="required required_<?php echo $question['related_to_question']; ?>"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
                                    <?php $required = $question['is_required'] ? "required" : ""; ?>
                                    <input id="<?php echo $question['id']; ?>" type="text" name="time_<?php echo $question['id']; ?>" value="12:00AM" class="form-control start_time related_<?php echo $question['related_to_question']; ?>" readonly data-require="<?php echo $question['is_required']; ?>" data-attr="<?php echo $question['related_to_question']; ?>" aria-invalid="false" <?php echo $required; ?>>
                                <?php } elseif ($question['question_type'] == 'date') { ?>
                                    <label class="auto-height"><?php echo strip_tags($question['label']) ?> : <span class="required required_<?php echo $question['related_to_question']; ?>"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
                                    <?php $required = $question['is_required'] ? "required" : ""; ?>
                                    <input id="<?php echo $question['id']; ?>" type="text" name="date_<?php echo $question['id']; ?>" value="" data-require="<?php echo $question['is_required']; ?>" data-attr="<?php echo $question['related_to_question']; ?>" class="form-control start_date related_<?php echo $question['related_to_question']; ?>" aria-invalid="false" <?php echo $required; ?> autocomplete="off" readonly>
                                <?php } elseif ($question['question_type'] == 'signature') { ?>
                                    <div class="form-group">
                                        <label class="auto-height">Signature : <span class="required">*</span></label>
                                    </div>

                                    <!-- the below loaded view add e-signature -->
                                    <?php $this->load->view('static-pages/e_signature_button'); ?>
                                    <input type="hidden" name="signature" value="" id="signature_bas64_image">

                                <?php } elseif ($question['question_type'] == 'radio') { ?>
                                    <label><?php echo strip_tags($question['label']) ?>: <span class="required"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-6 col-xs-12 col-sm-6">
                                            <label class="control control--radio">
                                                Yes<input type="radio" id="<?php echo $question['id']; ?>" name="radio_<?php echo $question['id']; ?>" data-attr="<?php echo $question['is_required']; ?>" value="yes" style="position: relative;">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-xs-12 col-sm-6">
                                            <label class="control control--radio">
                                                No<input type="radio" id="<?php echo $question['id']; ?>" name="radio_<?php echo $question['id']; ?>" data-attr="<?php echo $question['is_required']; ?>" value="no" style="position: relative;" checked>
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                <?php } elseif ($question['question_type'] == 'single select') { ?>
                                    <label class="auto-height"><?php echo strip_tags($question['label']) ?> : <span class="required"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
                                    <div class="hr-select-dropdown">
                                        <select id="<?php echo $question['id']; ?>" name="list_<?php echo $question['id']; ?>" class="form-control" <?php if ($question['is_required'] == 1) { ?> required <?php } ?>>
                                            <option value="">-- Please Select --</option>
                                            <?php
                                            $options = explode(',', $question['options']);
                                            foreach ($options as $option) {
                                            ?>
                                                <option value="<?php echo $option; ?>"> <?php echo ucfirst($option); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                <?php } elseif ($question['question_type'] == 'multi select') { ?>
                                    <label class="multi-checkbox auto-height" data-attr="<?php echo $question['is_required'] ?>" data-key="<?php echo $question['id']; ?>" data-value="<?php echo $question['label'] ?>"><?php echo strip_tags($question['label']); ?> <span class="required"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
                                    <div class="row">
                                        <?php $options = explode(',', $question['options']); ?>
                                        <?php foreach ($options as $option) { ?>
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <label class="control control--checkbox">
                                                    <?php echo $option; ?>
                                                    <input id="<?php echo $question['id']; ?>" type="checkbox" name="multi-list_<?php echo $question['id']; ?>[]" value="<?php echo $option; ?>" style="position: relative;">
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                                <?php echo '</div> </div> </div>'; ?>
                            <?php } ?>
                            <div class="row">

                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12" id="media_section">
                                    <div class="panel panel-blue">
                                        <div class="panel-heading incident-panal-heading">
                                            <b>Upload Compliance Safety Supporting Video / Audio</b>
                                        </div>
                                        <div class="panel-body">
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
                                                <button type="button" class="btn btn-info" id="save_compliance_video">Save Video</button>
                                            </div>

                                            <div class="table-responsive table-outer full-width" style="margin-top: 20px;" id="video_listing">
                                                <div class="table-wrp data-table">
                                                    <table class="table table-bordered table-hover table-stripped">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">Video Title</th>
                                                                <th class="text-center">Video Type</th>
                                                                <th class="text-center">Video Status</th>
                                                                <th class="text-center" colspan="2">Video Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="video_listing_data">

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12" id="document_section">
                                    <div class="panel panel-blue">
                                        <div class="panel-heading incident-panal-heading">
                                            <b>Upload Compliance Safety Supporting Documents</b>
                                        </div>
                                        <div class="panel-body">
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
                                                <button type="button" class="btn btn-info" id="save_incident_document">Save Document</button>
                                            </div>

                                            <div class="table-responsive table-outer full-width" style="margin-top: 20px;" id="document_listing">
                                                <div class="table-wrp data-table">
                                                    <table class="table table-bordered table-hover table-stripped">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">Document Title</th>
                                                                <th class="text-center">Document Type</th>
                                                                <th class="text-center">Document Status</th>
                                                                <th class="text-center" colspan="2">Document Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="document_listing_data">

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12 col-xl-12 col-sm-12">
                                    <label class="auto-height">Add Company Employees to Compliance Safety Report: <span class="required">*</span></label>
                                    <div class="row">
                                        <?php foreach ($employees as $employee) { ?>
                                            <?php if ($employee['sid'] == $current_user) continue; ?>
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <label class="control control--checkbox">
                                                    <?php echo getUserNameBySID($employee['sid']); ?>
                                                    <input type="checkbox" name="review_manager[]" value="<?php echo $employee['sid']; ?>" style="position: relative;">
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="form-group">
                                        <b>
                                            <h4>BY CLICKING ON "SUBMIT" I CERTIFY THAT I HAVE BEEN TRUTHFUL IN EVERY RESPECT IN FILLING THIS REPORT</h4>
                                        </b>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">

                                    <div class="form-group">

                                        <div class="custom_loader">
                                            <div id="submit-loader" class="loader" style="display: none">
                                                <i style="font-size: 25px; color: #81b431;" class="fa fa-cog fa-spin"></i>
                                                <span>Submitting...</span>
                                            </div>
                                        </div>
                                        <?php
                                        foreach ($incident_managers as $key => $manager) {
                                            if ($manager['employee_id'] == $employer_sid) {
                                                unset($incident_managers[$key]);
                                            }
                                        }
                                        $show_button = count($incident_managers);
                                        ?>
                                        <?php //if ($show_button > 0) { 
                                        ?>
                                        <div class="btn-wrp full-width text-right">
                                            <input type="submit" value="Submit" name="submit" class="btn btn-info" id="submit">
                                        </div>
                                        <?php //} 
                                        ?>
                                    </div>
                                </div>

                                <input type="hidden" id="inc-id" name="inc-id" value="0" />
                            </div>
                        <?php } else { ?>
                            <?php echo "<span class='no-data'>No Questions Scheduled For This Type</span>"; ?>
                        <?php } ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('static-pages/e_signature_popup'); ?>

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

<!-- Loader Start -->
<div id="incident_loader" class="text-center my_loader" style="display: none;">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text" id="loader_text_div" style="display:block; margin-top: 35px;">
        </div>
    </div>
</div>
<!-- Loader End -->



<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css" />
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        <?php
        foreach ($questions as $question) {
            if ($question['question_type'] == 'textarea') {
                echo 'CKEDITOR.replace("text_' . $question['id'] . '");' . "\r\n";
            }
        }
        ?>

        $('#up_video_container input').prop('disabled', true);
        $('#up_video_container').hide();

        $('#up_audio_container input').prop('disabled', true);
        $('#up_audio_container').hide();

        $('#video_listing').hide();
        $('#document_listing').hide();
    });

    var field_name = 0;

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

            $('#save_compliance_video').text('Save Video');

        } else if (selected == 'vimeo') {
            $('#yt_vm_video_container input').prop('disabled', false);
            $('#yt_vm_video_container').show();

            $('#up_video_container input').prop('disabled', true);
            $('#up_video_container').hide();

            $('#up_audio_container input').prop('disabled', true);
            $('#up_audio_container').hide();

            $('#save_compliance_video').text('Save Video');

        } else if (selected == 'upload_video') {
            $('#yt_vm_video_container input').prop('disabled', true);
            $('#yt_vm_video_container').hide();

            $('#up_video_container input').prop('disabled', false);
            $('#up_video_container').show();

            $('#up_audio_container input').prop('disabled', true);
            $('#up_audio_container').hide();

            $('#save_compliance_video').text('Save Video');

        } else if (selected == 'upload_audio') {
            $('#yt_vm_video_container input').prop('disabled', true);
            $('#yt_vm_video_container').hide();

            $('#up_video_container input').prop('disabled', true);
            $('#up_video_container').hide();

            $('#up_audio_container input').prop('disabled', false);
            $('#up_audio_container').show();

            $('#save_compliance_video').text('Save Audio');

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

    $('#save_compliance_video').on('click', function() {
        var flag = 0;
        var message = '';
        var video_title = $('#video_title').val();
        var video_source = $('input[name="video_source"]:checked').val();

        if (video_source == 'youtube') {
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

        if (video_source == 'vimeo') {
            if ($('#video_id').val() != '') {
                var myurl = "<?php echo base_url('compliance_report/validate_vimeo'); ?>";
                $.ajax({
                    type: "POST",
                    url: myurl,
                    data: {
                        url: $('#video_id').val()
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

        if (video_source == 'upload_video') {
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

        if (video_source == 'upload_audio') {
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


        if (video_title == '' || video_title.length == 0) {
            message = 'Please provide a Video Title.';
            flag = 1;
        }

        if (flag == 1) {
            alertify.alert(message);
            return false;
        } else {
            $("#incident_loader").show();
            $("#loader_text_div").text('Please wait while we are uploading media ...');

            var add_video_url = '<?php echo base_url('compliance_report/add_compliance_video'); ?>';
            var form_data = new FormData();

            if (video_source == 'upload_audio') {
                var audio_data = $('#audio').prop('files')[0];

                form_data.append('audio', audio_data);
                form_data.append('file_type', video_source);
            } else if (video_source == 'upload_video') {
                var video_data = $('#video').prop('files')[0];

                form_data.append('video', video_data);
                form_data.append('file_type', video_source);
            } else if (video_source == 'youtube') {
                var youtube_video_link = $('#video_id').val();

                form_data.append('youtube_video_link', youtube_video_link);
                form_data.append('file_type', video_source);
            } else if (video_source == 'vimeo') {
                var vimeo_video_link = $('#video_id').val();

                form_data.append('vimeo_video_link', vimeo_video_link);
                form_data.append('file_type', video_source);
            }

            if ($('#inc-id').val() != '0') {
                var incident_sid = $('#inc-id').val();
                form_data.append('incident_sid', incident_sid);
            }

            form_data.append('video_title', video_title);
            form_data.append('incident_type_sid', <?php echo $id; ?>);
            form_data.append('company_sid', <?php echo $company_sid; ?>);

            $('#save_compliance_video').addClass('disabled-btn');
            $('#save_compliance_video').prop('disabled', true);

            $.ajax({
                url: add_video_url,
                cache: false,
                contentType: false,
                processData: false,
                type: 'post',
                data: form_data,
                success: function(response) {
                    $("#incident_loader").hide();

                    $('#video_title').val('');
                    if (video_source == 'upload_audio') {
                        $('#name_audio').html('');
                    } else if (video_source == 'upload_video') {
                        $('#name_video').html('');
                    } else {
                        $('#video_id').val('');
                    }

                    $('#save_compliance_video').removeClass('disabled-btn');
                    $('#save_compliance_video').prop('disabled', false);
                    if (response != "error") {
                        var obj = jQuery.parseJSON(response);
                        var res_incident_sid = obj['incident_sid'];
                        var res_video_sid = obj['video_sid'];
                        var res_video_title = obj['video_title'];
                        var res_video_source = obj['video_source'];
                        var res_video_url = obj['video_url'];
                        $('#inc-id').val(res_incident_sid);
                        $('#video_listing').show();
                        $('#video_listing_data').prepend('<tr id="video_' + res_video_sid + '"><td class="text-center">' + res_video_title + '</td><td class="text-center">' + res_video_source + '</td><td class="text-center">Success</td><td><a href="javascript:;" video-sid="' + res_video_sid + '" video-title="' + res_video_title + '" class="btn btn-block btn-info js-edit-video">Edit Video</a></td><td><a href="javascript:;" video-title="' + res_video_title + '" video-source="' + res_video_source + '" video-url="' + res_video_url + '" class="btn btn-block btn-info js-view-video">Watch Video</a></td></tr>');

                        alertify.alert('Supporting Incident Video Uploaded Successfully!', function() {
                            $('html, body').animate({
                                scrollTop: $("#media_section").offset().top
                            }, 2000);
                        });
                    } else {
                        alertify.alert('Error Occurred While Uploading Video');
                    }
                },
                error: function() {}
            });
        }
    });

    $(document).on('click', '.js-view-video', function() {
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

    $(document).on('click', '.js-edit-video', function() {
        var video_sid = $(this).attr('video-sid');
        var old_title = $(this).attr('video-title');

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

    $('#save_updated_video').on('click', function(event) {
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
                    var myurl = "<?php echo base_url('compliance_report/validate_vimeo'); ?>";
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
            $("#incident_loader").show();
            $("#loader_text_div").text('Please wait while we are uploading media ...');

            var update_video_url = '<?php echo base_url('compliance_report/update_compliance_video'); ?>';
            var targit_video = $('#update_video_sid').val();
            var incident_sid = $('#inc-id').val();
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
            form_data.append('user_type', 'employee');
            form_data.append('update_title', update_title);
            form_data.append('incident_sid', incident_sid);
            form_data.append('company_sid', <?php echo $company_sid; ?>);

            $('#edit_incident_video').modal('hide');

            $.ajax({
                url: update_video_url,
                cache: false,
                contentType: false,
                processData: false,
                type: 'post',
                data: form_data,
                success: function(response) {
                    $("#incident_loader").hide();
                    if (response != "error") {
                        var obj = jQuery.parseJSON(response);
                        var res_incident_sid = obj['incident_sid'];
                        var res_video_sid = obj['video_sid'];
                        var res_video_title = obj['video_title'];
                        var res_video_source = obj['video_source'];
                        var res_video_url = obj['video_url'];

                        $('#video_' + res_video_sid).html('<td class="text-center">' + res_video_title + '</td><td class="text-center">' + res_video_source + '</td><td class="text-center">Success</td><td><a href="javascript:;" video-sid="' + res_video_sid + '" video-title="' + res_video_title + '" class="btn btn-block btn-info js-edit-video">Edit Video</a></td><td><a href="javascript:;" video-title="' + res_video_title + '" video-source="' + res_video_source + '" video-url="' + res_video_url + '" class="btn btn-block btn-info js-view-video">Watch Video</a></td>');

                        alertify.alert('Supporting Incident Video Update Successfully!', function() {
                            $('html, body').animate({
                                scrollTop: $("#media_section").offset().top
                            }, 2000);
                        });
                    } else {
                        alertify.alert('Some error occurred while uploading video.');
                    }

                },
                error: function() {}
            });
        }
    });
    // Media JS End

    // Document JS Start
    $('#save_incident_document').on('click', function() {
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
        } else {
            $("#incident_loader").show();
            $("#loader_text_div").text('Please wait while we are uploading document ...');

            var add_document_url = '<?php echo base_url('compliance_report/add_compliance_document'); ?>';
            var file_data = $('#upload_document').prop('files')[0];
            var file_ext = fileName.split('.').pop();
            var form_data = new FormData();

            if ($('#inc-id').val() != '0') {
                var incident_sid = $('#inc-id').val();
                form_data.append('incident_sid', incident_sid);
            }

            form_data.append('document_title', title);
            form_data.append('docs', file_data);
            form_data.append('file_name', fileName.replace('C:\\fakepath\\', ''));
            form_data.append('file_ext', file_ext);
            form_data.append('company_sid', <?php echo $company_sid; ?>);
            form_data.append('incident_type_sid', <?php echo $id; ?>);

            $('#save_incident_document').addClass('disabled-btn');
            $('#save_incident_document').prop('disabled', true);

            $.ajax({
                url: add_document_url,
                cache: false,
                contentType: false,
                processData: false,
                type: 'post',
                data: form_data,
                success: function(response) {
                    $("#incident_loader").hide();
                    $('#save_incident_document').removeClass('disabled-btn');
                    $('#save_incident_document').prop('disabled', false);
                    $("#upload_document").val(null);
                    $('#name_upload_document').html('');
                    $('#document_title').val('');
                    if (response != "error") {
                        var obj = jQuery.parseJSON(response);
                        var res_incident_sid = obj['incident_sid'];
                        var res_document_sid = obj['document_sid'];
                        var res_document_title = obj['document_title'];
                        var res_document_type = obj['document_type'];
                        var res_document_ext = obj['document_extension'];
                        var res_document_url = obj['document_url'];
                        $('#inc-id').val(res_incident_sid);
                        $('#document_listing').show();
                        $('#document_listing_data').prepend('<tr id="document_' + res_document_sid + '"><td class="text-center">' + res_document_title + '</td><td class="text-center">' + res_document_type + '</td><td class="text-center">Success</td><td><a href="javascript:;" document-sid="' + res_document_sid + '" document-title="' + res_document_title + '" document-ext="' + res_document_ext + '" document-url="' + res_document_url + '" class="btn btn-block btn-info js-edit-document">Edit Document</a></td><td><a href="javascript:;" document-title="' + res_document_title + '" document-ext="' + res_document_ext + '" document-url="' + res_document_url + '" class="btn btn-block btn-info js-view-document">View Document</a></td></tr>');

                        alertify.alert('Supporting Incident Document Update Successfully!', function() {
                            $('html, body').animate({
                                scrollTop: $("#document_section").offset().top
                            }, 2000);
                        });
                    } else {
                        alertify.alert('Error Occurred While Uploading Supporting Incident Document');
                    }
                },
                error: function() {}
            });
        }
    });

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

    $(document).on('click', '.js-edit-document', function() {

        var iframe_url = '';
        var modal_content = '';
        var footer_content = '';
        var document_sid = $(this).attr('document-sid');
        var document_title = $(this).attr('document-title');
        var file_extension = $(this).attr('document-ext');
        var document_preview_url = $(this).attr('document-url');

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
                case 'xls':
                    iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_preview_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    break;
                case 'xlsx':
                    //using office docs
                    iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_preview_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    break;
                case 'jpg':
                case 'jpe':
                case 'jpeg':
                case 'png':
                case 'gif':
                    modal_content = '<img src="' + document_preview_url + '" style="width:100%; height:500px;" />';
                    break;
                default:
                    //using google docs
                    iframe_url = 'https://docs.google.com/gview?url=' + document_preview_url + '&embedded=true';
                    break;
            }
        } else {
            modal_content = '<h5>No ' + document_title + ' Uploaded.</h5>';
        }

        $('#update_document_sid').val(document_sid);
        $('#update_doc_title').val(document_title);
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
            $("#incident_loader").show();
            $("#loader_text_div").text('Please wait while we are uploading document ...');

            var incident_sid = $('#inc-id').val();
            var update_document_url = '<?php echo base_url('compliance_report/update_compliance_document'); ?>';
            var targit_document = $('#update_document_sid').val();
            var file_data = $('#edit_upload_document').prop('files')[0];
            var file_ext = fileName.split('.').pop();
            var form_data = new FormData();


            form_data.append('update_type', validation);
            form_data.append('document_title', document_title);
            form_data.append('document_sid', targit_document);
            form_data.append('incident_sid', incident_sid);
            form_data.append('docs', file_data);
            form_data.append('file_name', fileName.replace('C:\\fakepath\\', ''));
            form_data.append('file_ext', file_ext);
            form_data.append('company_sid', <?php echo $company_sid; ?>);
            form_data.append('user_type', 'employee');

            $('#edit_incident_document').modal('hide');

            $.ajax({
                url: update_document_url,
                cache: false,
                contentType: false,
                processData: false,
                type: 'post',
                data: form_data,
                success: function(response) {
                    $("#incident_loader").hide();
                    if (response != "error") {
                        var obj = jQuery.parseJSON(response);
                        var res_incident_sid = obj['incident_sid'];
                        var res_document_sid = obj['document_sid'];
                        var res_document_title = obj['document_title'];
                        var res_document_type = obj['document_type'];
                        var res_document_ext = obj['document_extension'];
                        var res_document_url = obj['document_url'];

                        $('#document_' + res_document_sid).html('<td class="text-center">' + res_document_title + '</td><td class="text-center">' + res_document_type + '</td><td class="text-center">Success</td><td><a href="javascript:;" document-sid="' + res_document_sid + '" document-title="' + res_document_title + '" document-ext="' + res_document_ext + '" document-url="' + res_document_url + '" class="btn btn-block btn-info js-edit-document">Edit Document</a></td><td><a href="javascript:;" document-title="' + res_document_title + '" document-ext="' + res_document_ext + '" document-url="' + res_document_url + '" class="btn btn-block btn-info js-view-document">View Document</a></td>');

                        alertify.alert('Supporting Incident Document Update Successfully!', function() {
                            $('html, body').animate({
                                scrollTop: $("#document_section").offset().top
                            }, 2000);
                        });
                    } else {
                        alertify.alert('Some Error Occurred While Uploading Supporting Incident Document.');
                    }
                },
                error: function() {}
            });
        }
    });

    $(document).on('click', '.js-view-document', function() {
        var iframe_url = '';
        var modal_content = '';
        var footer_content = '';
        var document_title = $(this).attr('document-title');
        var file_extension = $(this).attr('document-ext');
        var document_preview_url = $(this).attr('document-url');

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
                case 'xls':
                    iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_preview_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    break;
                case 'xlsx':
                    //using office docs
                    iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_preview_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    break;
                case 'jpg':
                case 'jpe':
                case 'jpeg':
                case 'png':
                case 'gif':
                    modal_content = '<img src="' + document_preview_url + '" style="width:100%; height:500px;" />';
                    break;
                default:
                    //using google docs
                    iframe_url = 'https://docs.google.com/gview?url=' + document_preview_url + '&embedded=true';
                    break;
            }
        } else {
            modal_content = '<h5>No ' + document_title + ' Uploaded.</h5>';
        }

        $('#view_document_modal_body').html(modal_content);
        $('#document_modal_title').html(document_title);
        $('#document_modal').modal("toggle");
        $('#document_modal').on("shown.bs.modal", function() {
            if (iframe_url != '') {
                $('#preview_iframe').attr('src', iframe_url);
            }
        });
    });
    // Document JS End

    $("#inc-form").validate({
        ignore: ":hidden:not(select)",
        submitHandler: function(form) {

            var flag = 0;

            if ($('#jsComplianceSafetyTitle').val().length == 0) {
                alertify.alert('Please enter compliance safety title.');
                flag = 1;
                $('#submit').removeAttr('disabled');
                return false;
            }

            if ($("#yes_witnesses").is(':checked')) {
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
                            alertify.alert('Please add witness full name at row ' + i + ' !');
                        } else if (witnesses_phone == '') {
                            alertify.alert('Please add witness phone number at row ' + i + ' !');
                        } else if (witnesses_email == '') {
                            alertify.alert('Please add witness email at row ' + i + ' !');
                        } else if (witnesses_title == '') {
                            alertify.alert('Please add witness title / relationship at row ' + i + ' !');
                        }
                        $("#submit").removeAttr("disabled");
                        flag = 1;
                        return false;
                    } else if (witnesses_email != '' && !email.test(witnesses_email)) {
                        alertify.alert('Please add valid witness email at row ' + i + ' !');
                        $("#submit").removeAttr("disabled");
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
                            alertify.alert('Please add witness full name at row ' + i + ' !');
                        } else if (witnesses_phone == '') {
                            alertify.alert('Please add witness phone number at row ' + i + ' !');
                        } else if (witnesses_email == '') {
                            alertify.alert('Please add witness email at row ' + i + ' !');
                        } else if (witnesses_title == '') {
                            alertify.alert('Please add witness title / relationship at row ' + i + ' !');
                        }
                        $("#submit").removeAttr("disabled");
                        flag = 1;
                        return false;
                    } else if (witnesses_email != '' && !email.test(witnesses_email)) {
                        alertify.alert('Please add valid witness email at row ' + i + ' !');
                        $("#submit").removeAttr("disabled");
                        flag = 1;
                        return false;
                    }
                });
            }

            var is_signature_exist = $('#signature_bas64_image').val();
            $('#submit').attr('disabled', 'disabled');
            <?php
            foreach ($questions as $question) {
                if ($question['question_type'] == 'textarea' && $question['is_required'] == 1) {
                    echo 'var instances' . $question['id'] . ' = $.trim(CKEDITOR.instances.text_' . $question['id'] . '.getData());' . "\r\n";
                    echo 'if (instances' . $question['id'] . '.length === 0) {' . "\r\n";
                    echo 'alertify.alert("Error! Answer Missing", "Please Provide All (*)Required Fields");' . "\r\n";
                    echo '$("#submit").removeAttr("disabled");' . "\r\n";
                    echo 'return false;' . "\r\n";
                    echo '}' . "\r\n";
                }
            }
            ?>
            if (is_signature_exist == "") {
                alertify.alert('Please Add Your Signature!');
                $("#submit").removeAttr("disabled");
                return false;
            }



            $(".multi-checkbox").each(function(index, element) {
                if ($(this).attr('data-attr') != '0') {
                    var key = "multi-list_" + $(this).attr('data-key');
                    var name = "input:checkbox[name^='" + key + "']:checked";
                    var checked = $(name).length;

                    if (!checked) {
                        alertify.alert($(this).attr('data-value') + ' is required');
                        flag = 1;
                        $('#submit').removeAttr('disabled');
                        return false;
                    }
                }
            });

            if ($('[name="review_manager[]"]:checked').length == 0) {
                alertify.alert('Please select manager');
                flag = 1;
                $('#submit').removeAttr('disabled');
                return false;
            }

            if (flag) {
                $('#submit').removeAttr('disabled');
                return false;
            }

            $('#submit-loader').show();
            $("#inc-form")[0].submit();
        }
    });

    $('.start_date').datepicker({
        dateFormat: 'mm-dd-yy',
        changeMonth: true,
        changeYear: true,
        yearRange: "-100:+50"
    });

    $('.start_time').datetimepicker({
        datepicker: false,
        format: 'g:iA',
        formatTime: 'g:iA',
        step: 15
    });

    $(document).on('change', 'input[type="radio"]', function() {
        var related = $(this).attr('id');
        var value = $("input[type='radio'][name='radio_" + related + "']:checked").val();
        if (value == 'no') {

            $('.related_' + related).removeClass('error');
            $('.related_' + related).removeAttr('required');
            $('.required_' + related).hide();
        } else {
            $('.related_' + related).each(function(index, object) {
                var require = $(object).attr('data-require');
                if (require == '1') {

                    $(object).addClass('error');
                    $(object).prop('required', true);
                    $(object).prev().find('.required_' + related).show();
                } else {
                    $(object).removeClass('error');
                    $(object).removeAttr('required');
                    $(object).prev().find('.required_' + related).hide();
                }
            });
        }
    });

    function check_file(val) {
        var fileName = $("#" + val).val();

        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
            $('.upload-file').hide();
            $('#uploaded-files').hide();
            $('#file-upload-div').append('<div class="form-group form-control autoheight"><div class="pull-left"> <span class="selected-file" id="name_docs">' + fileName + '</span> </div> <div class="pull-right"> <input class="submit-btn btn btn-info" type="button" value="Upload" name="upload" id="upload" onclick="DoUpload()"> <input class="submit-btn btn btn-info" type="button" value="Cancel" name="cancel" onclick="CancelUpload();"> </div> </div>');
        } else {
            $('#name_' + val).html('No file selected');
        }
    }

    function CancelUpload() {
        $('.upload-file').show();

        if ($('#uploaded-files').html() != '') {
            $('#uploaded-files').show();
        }

        $('#file-upload-div').html("");
        $('#name_docs').html("No file selected");
    }

    function DoUpload() {
        var file_data = $('#docs').prop('files')[0];
        var form_data = new FormData();
        form_data.append('docs', file_data);
        form_data.append('id', <?php echo $id; ?>);

        if (incident_sid != '0') {
            form_data.append('inc_id', incident_sid);
        }

        $('#loader').show();
        $('#upload').addClass('disabled-btn');
        $('#upload').prop('disabled', true);
        $.ajax({
            url: '<?= base_url('compliance_report/ajax_handler') ?>',
            cache: false,
            contentType: false,
            processData: false,
            type: 'post',
            data: form_data,
            success: function(data) {
                $('#loader').hide();
                $('#upload').removeClass('disabled-btn');
                $('#upload').prop('disabled', false);
                alertify.alert('New document has been uploaded');
                $('.upload-file').show();
                $('#uploaded-files').show();
                $('#uploaded-files').append('<div class="row"><div class="col-lg-6 col-md-6 col-xs-12 col-sm-6"> <div id="uploaded-files-name"><b>Name:</b> ' + file_data['name'] + '</div> </div> <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 text-right"> <span><b>Status:</b> Uploaded</span> </div> </div>');
                $('#file-upload-div').html("");
                $('#name_docs').html("No file selected");

                if (data != "error") {
                    $('#inc-id').val(data);
                } else {
                    alert('Doc error');
                }
            },
            error: function() {}
        });
    }

    $(document).ready(function() {
        //
        $("#incident_employee_id").select2();
        //
        $('#incident_employee_id').on("change", function(e) {
            $('#full-name').val(
                $(this).find("option:selected").data('name')
            );
        });
    });
</script>
<style>
    .select2-container--default .select2-selection--single {
        background-color: #fff !important;
        border: 1px solid #aaa !important;
        padding: 5px !important;
        border-radius: 4px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        top: 12px !important;
    }
</style>