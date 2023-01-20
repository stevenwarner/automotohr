<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('main/manage_ems_left_view'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow">
                        <?php $this->load->view('manage_employer/company_logo_name'); ?>
   
                        <?php echo $title; ?></span>
                    </div>
                    <div class="form-wrp">
                        <form id="add_new_event" action="" method="POST" enctype="multipart/form-data" autocomplete="off">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label>Announcement Type: </label>
                                        <div class="hr-select-dropdown">
                                            <select class="form-control" name="type" id="type">
                                                <option value="New Hire" <?php if ($event[0]['type'] == "New Hire") { ?>selected<?php } ?>>New Hire Event</option>
                                                <option value="General" <?php if ($event[0]['type'] == "General") { ?>selected<?php } ?>>General Event</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                    <div class="form-group" id="custom_ticket_cat">
                                        <label>Title : <span class="staric">*</span></label>
                                        <input type="text" class="form-control" name="title" id="title" value="<?php echo $event[0]['title'] ?>">
                                        <?php echo form_error('custom_category'); ?>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label>Display Start Date: <span class="staric">*</span></label>
                                        <input class="form-control"
                                               type="text"
                                               name="display_start_date"
                                               id="display_start_date"
                                               value="<?php echo $event[0]['display_start_date'] != NULL && !empty($event[0]['display_start_date']) ? date('m-d-Y', strtotime($event[0]['display_start_date'])) : ''; ?>"/>

                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label>Display End Date : </label>
                                        <input class="form-control"
                                               type="text"
                                               name="display_end_date"
                                               id="display_end_date"
                                               value="<?php echo $event[0]['display_end_date'] != NULL && !empty($event[0]['display_end_date']) ? date('m-d-Y', strtotime($event[0]['display_end_date'])) : ''; ?>"/>
                                    </div>
                                </div>
                                <input type="hidden" name="status" value="<?php echo $event[0]['status'] ? 1 : 0; ?>">

                                <div class="new-hire-div">
                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                        <div class="form-group">
                                            <label>New Hire Name: <span class="staric">*</span></label>
                                            <div class="hr-select-dropdown">
                                                <select class="form-control" name="new_hire_name" id="new_hire_name">
                                                    <option value="">Please Select</option>
                                                    <?php foreach ($all_emp as $emp) { ?>
                                                        <option value="<?php echo ucwords($emp['first_name'] . ' ' . $emp['last_name']) ?>" <?php if ($event[0]['new_hire_name'] == ucwords($emp['first_name'] . ' ' . $emp['last_name'])) { ?>selected<?php } ?>><?php echo ucwords($emp['first_name'] . ' ' . $emp['last_name']) ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="event-div">
                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                        <div class="form-group">
                                            <label>General Event Start Date : </label>
                                            <input class="form-control"
                                                   type="text"
                                                   name="event_start_date"
                                                   id="event_start_date"
                                                   value="<?php echo $event[0]['event_start_date'] != NULL && !empty($event[0]['event_start_date']) ? date('m-d-Y', strtotime($event[0]['event_start_date'])) : ''; ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                        <div class="form-group">
                                            <label>General Event End Date :</label>
                                            <input class="form-control"
                                                   type="text"
                                                   name="event_end_date"
                                                   id="event_end_date"
                                                   value="<?php echo $event[0]['event_end_date'] != NULL && !empty($event[0]['event_end_date']) ? date('m-d-Y', strtotime($event[0]['event_end_date'])) : ''; ?>"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="<?php echo $event[0]['type'] == 'New Hire' ? 'col-lg-6 col-md-6 col-xs-12 col-sm-6' : 'col-lg-12 col-md-12 col-xs-12 col-sm-12' ?>" id="announce-for">
                                    <div class="form-group">
                                        <label>Announcement For: </label>
                                        <div class="hr-select-dropdown">
                                            <select class="chosen-select" multiple="multiple" name="announcement_for[]" id="announcement_for">
                                                <?php $for_array = explode(',', $event[0]['announcement_for']); ?>
                                                <option value="0" <?php echo in_array(0, $for_array) ? 'selected' : '' ?>>All</option>
                                                <?php foreach ($all_emp as $emp) { ?>
                                                    <option value="<?= $emp['sid'] ?>" <?php echo in_array($emp['sid'], $for_array) ? 'selected' : '' ?>><?php echo ucwords($emp['first_name'] . ' ' . $emp['last_name']) ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="new-hire-div">
                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                        <div class="form-group">
                                            <label>New Hire Start Date : </label>
                                            <input class="form-control"
                                                   type="text"
                                                   name="new_hire_joining_date"
                                                   id="new_hire_joining_date"
                                                   value="<?php echo $event[0]['new_hire_joining_date'] != NULL && !empty($event[0]['new_hire_joining_date']) ? date('m-d-Y', strtotime($event[0]['new_hire_joining_date'])) : ''; ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                        <div class="form-group">
                                            <label>New Hire Position : </label>
                                            <input class="form-control"
                                                   type="text"
                                                   name="new_hire_job_position"
                                                   id="new_hire_job_position"
                                                   value="<?php echo $event[0]['new_hire_job_position']; ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="form-group autoheight">
                                            <label>New Hire Bio: </label>
                                            <div style='margin-bottom:5px;'><?php $this->load->view('templates/_parts/ckeditor_gallery_link'); ?></div>
                                            <textarea class="ckeditor" name="new_hire_bio" id="new_hire_bio" cols="60" rows="10"><?= $event[0]['new_hire_bio'] ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="form-group autoheight">
                                        <label>Message: </label>
                                        <div style='margin-bottom:5px;'><?php $this->load->view('templates/_parts/ckeditor_gallery_link'); ?></div>
                                        <textarea class="ckeditor" name="message" id="message" cols="60" rows="10"><?= $event[0]['message'] ?></textarea>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                    <!--                                    <div class="form-group">-->
                                    <label>Video Source: </label>
                                    <label class="control control--radio">
                                        <?php echo YOUTUBE_VIDEO; ?>
                                        <input id="youtube" class="video_source" name="video_source" checked="checked" value="youtube" type="radio">
                                        <div class="control__indicator"></div>
                                    </label>
                                    <label class="control control--radio">
                                        <?php echo VIMEO_VIDEO; ?>
                                        <input id="vimeo" class="video_source" name="video_source" <?= $event[0]['section_video_source'] == 'vimeo' ? 'checked="checked"' : '' ?> value="vimeo" type="radio">
                                        <div class="control__indicator"></div>
                                    </label>
                                    <label class="control control--radio">
                                        <?php echo UPLOAD_VIDEO; ?>
                                        <input id="upload_video" class="video_source" name="video_source" <?= $event[0]['section_video_source'] == 'upload_video' ? 'checked="checked"' : '' ?> value="upload_video" type="radio">
                                        <div class="control__indicator"></div>
                                    </label>
                                    <!--                                    </div>-->
                                </div>
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                    <label>Video status: </label>
                                    <label class="control control--radio">
                                        Disable
                                        <input id="disable_video" class="video_status" name="video_status" checked="checked" value="0" type="radio">
                                        <div class="control__indicator"></div>
                                    </label>
                                    <label class="control control--radio">
                                        Enable
                                        <input id="enable_video" class="video_status" name="video_status" value="1" type="radio" <?= $event[0]['section_video_status'] == '1' ? 'checked="checked"' : '' ?>>
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>

                                <?php $temp = (isset($event[0]['section_video']) ? html_entity_decode($event[0]['section_video']) : ''); ?>
                                <?php if ($event[0]['section_video_source'] == 'youtube') { ?>
                                    <?php $temp = empty($temp) ? '' : 'https://www.youtube.com/watch?v=' . $temp; ?>
                                <?php } else if ($event[0]['section_video_source'] == 'vimeo') { ?>
                                    <?php $temp = empty($temp) ? '' : 'https://vimeo.com/' . $temp; ?>
                                <?php } else if ($event[0]['section_video_source'] == 'upload_video') { ?>
                                    <?php $up_temp = empty($temp) ? '' : $temp; ?>    
                                <?php } ?>

                                <div class="col-lg-12 col-md-6 col-xs-12 col-sm-12" id="yt_video_container" <?php echo $event[0]['section_video_source'] == 'youtube' ? 'style="display: none"' : '' ?>>
                                    <div class="form-group">
                                        <label>Youtube Video: </label>
                                        <input class="form-control" type="text" name="video_url" id="video_url_youtube" value="<?= $event[0]['section_video_source'] == 'youtube' ? $temp : '' ?>"/>
                                        <?php echo form_error('video_url'); ?>
                                        <div class="video-link float-right" style='font-style: italic;'><b>e.g.</b> https://www.youtube.com/watch?v=XXXXXXXXXXX </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12" style="display: none;" id="vm_video_container" <?php echo $event[0]['section_video_source'] == 'vimeo' ? 'style="display: none"' : '' ?>>
                                    <div class="form-group">
                                        <label>Vimeo Video: </label>
                                        <input class="form-control" type="text" name="video_url" id="video_url_vimeo" value="<?= $event[0]['section_video_source'] == 'vimeo' ? $temp : '' ?>"/>
                                        <?php echo form_error('video_url'); ?>
                                        <div class="video-link float-right" style='font-style: italic;'><b>e.g.</b> https://vimeo.com/XXXXXXX </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12" style="display: none;" id="ul_video_container" <?php echo $event[0]['section_video_source'] == 'upload_video' ? 'style="display: none"' : '' ?>>
                                    <div class="form-group universal-form-style-v2">
                                        <label>Upload Video <span class="hr-required">*</span></label>
                                        <div class="upload-file invoice-fields">
                                            <span class="selected-file" id="name_video">No file selected</span>
                                            <input class="customImage" type="file" name="video_upload" id="video" onchange="check_upload_video('video')">
                                            <a href="javascript:;">Choose Video</a>
                                        </div>
                                    </div>
                                </div>

                                
                                <?php if ($event[0]['section_video_source'] == 'upload_video') { ?>
                                    <input type="hidden" id="old_upload_video" name="old_upload_video" value="<?php echo $up_temp ?>">  
                                <?php } ?>



                                <?php $field_id = 'section_video'; ?>
                                <?php $temp = (isset($event[0][$field_id]) ? $event[0][$field_id] : ''); ?>
                                <?php if ($temp != '') { ?>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="well well-sm">
                                            <?php if ($event[0]['section_video_source'] == 'youtube') { ?>
                                                <div class="embed-responsive embed-responsive-16by9">
                                                    <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo $event[0]['section_video']; ?>"></iframe>
                                                </div>
                                            <?php } elseif ($event[0]['section_video_source'] == 'vimeo') { ?>
                                                <div class="embed-responsive embed-responsive-16by9">
                                                    <iframe src="https://player.vimeo.com/video/<?php echo $event[0]['section_video']; ?>" frameborder="0"></iframe>
                                                </div>
                                            <?php } else { ?>
                                                <div class="embed-responsive embed-responsive-16by9">
                                                    <video controls>
                                                        <source src="<?php echo base_url() . 'assets/uploaded_videos/' . $event[0]['section_video']; ?>" type='video/mp4'>
                                                    </video>
                                                </div>
                                            <?php } ?>    
                                        </div>
                                    </div>
                                <?php } ?>

                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                    <div class="form-group universal-form-style-v2">
                                        <label>Banner Image: </label>
                                        <!--                                        <input type="file" class="form-fields" id="section_image" name="section_image" />-->
                                        <div class="upload-file invoice-fields">
                                            <span class="selected-file" id="name_section_image"">No file selected</span>
                                            <input class="customImage" type="file" name="section_image" id="section_image" onchange="check_file('section_image')">
                                            <a href="javascript:;">Choose File</a>
                                        </div>
                                    </div>
                                </div>
                                
                                <input type="hidden" id="old_upload_image" name="old_upload_image" value="<?php echo $event[0]['section_image'] ?>"> 

                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label>Banner Image status: </label><br>
                                    <label class="control control--radio">
                                        Disable
                                        <input id="disable_banner" class="banner_status" name="banner_status" checked="checked" value="0" type="radio">
                                        <div class="control__indicator"></div>
                                    </label>
                                    <label class="control control--radio">
                                        Enable
                                        <input id="enable_banner" class="banner_status" name="banner_status" value="1" type="radio" <?= $event[0]['section_image_status'] == '1' ? 'checked="checked"' : '' ?>>
                                        <div class="control__indicator"></div>
                                    </label>
                                    </div>
                                </div>

                                <?php $field_id = 'section_image'; //echo 'this is '.$event[0]['section_image_status']; die();?>
                                <?php $sec_img = $temp = (isset($event[0][$field_id]) ? $event[0][$field_id] : ''); ?>
                                <?php if (!empty($temp)) { ?>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="form-col-100 autoheight">
                                            <div class="well well-sm">
                                                <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $temp; ?>" />
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>

                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="text-right">
                                        <a href="<?= base_url('announcements') ?>" class="submit-btn"> Cancel </a>
                                        <input type="submit" value="Update" name="submit" class="submit-btn" id="add_event_submit">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="my_loader" class="text-center my_loader" style="display: none;">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text" style="display:block; margin-top: 35px;">
            <?php echo VIDEO_LOADER_MESSAGE; ?>
        </div>
    </div>
</div>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url('assets/css/chosen.css'); ?>"  />
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets/js/chosen.jquery.js'); ?>"></script>
<script type="text/javascript">
        $('#add_event_submit').click(function () {
            var video_url_required;
            var video_url_message;
            var upload_required;
            var image_url_required;

            if ($('#enable_banner').is(':checked')) {
                if ($('#old_upload_image').val()) {
                    image_url_required = false;
                } else {
                    image_url_required = true;
                }
            } else {
                image_url_required = false;
            }

            if ($('#enable_video').is(':checked')) {

                if ($('#youtube').is(':checked')) {
                    video_url_required = true;
                    video_url_message = 'Please provide Youtube link';
                } else if ($('#vimeo').is(':checked')) {
                    video_url_required = true;
                    video_url_message = 'Please provide vimeo link';
                } else if ($('#upload_video').is(':checked')) {
                    if ($('#old_upload_video').val()) {
                        upload_required = false;
                    } else {
                        upload_required = true;
                    }
                } else {
                    upload_required = false;
                    video_url_required = false;
                }

            } else {


                video_url_required = false;
                upload_required = false;
            }

            $("#add_new_event").validate({
                ignore: [],
                rules: {
                    title: {
                        required: true,
                    },
                    display_start_date: {
                        required: true,
                    }, video_upload: {
                        required: upload_required
                    },
                    video_url: {
                        required: video_url_required
                    },
                    section_image: {
                        required: image_url_required
                    }
                },
                messages: {
                    title: {
                        required: 'Title Is Required',
                    },
                    display_start_date: {
                        required: 'Display Start Date Is Required',
                    },
                    video_upload: {
                        required: 'Please upload video',
                    },
                    video_url: {
                        required: video_url_message,
                    },
                    section_image: {
                        required: 'Please Upload Image',
                    }
                },
                submitHandler: function (form) {

                    var type = $('#type').val();
                    if (type == 'New Hire') {
                        if ($('#new_hire_name').val() == '') {
                            alertify.error('New Hire Name Is Required');
                            return false;
                        }
                    }

                    if ($('input[name="video_source"]:checked').val() == 'youtube') {
                        var flag = 0;
                        if ($('#video_url_youtube').val() != '') {
                            var p = /(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.)?youtube\.com\/watch(?:\.php)?\?.*v=)([a-zA-Z0-9\-_]+)/;
                            if (!$('#video_url_youtube').val().match(p)) {
                                alertify.error('Not a Valid Youtube URL');
                                flag = 1;
                                return false;
                            }
                        }
                    } else if ($('input[name="video_source"]:checked').val() == 'vimeo') {
                        if ($('#video_url_vimeo').val() != '') {
                            var flag = 0;
                            var myurl = "<?= base_url() ?>onboarding/validate_vimeo";
                            $.ajax({
                                type: "POST",
                                url: myurl,
                                data: {url: $('#video_url_vimeo').val()},
                                async: false,
                                success: function (data) {
                                    console.log(data);
                                    if (data == 'false') {
                                        alertify.error('Not a Valid Vimeo URL');
                                        flag = 1;
                                        return false;
                                    }
                                },
                                error: function (data) {

                                }
                            });
                        }
                        if (flag) {
                            return false;
                        }
                    }
                    $('#my_loader').show();
                    form.submit();
                }
            });
        });

        $(document).ready(function () {
            $("#video").val("");

            $('.video_source').on('click', function () {
                var selected = $(this).val();

                if (selected == 'youtube') {
                    $('#yt_video_container input').prop('disabled', false);
                    $('#yt_video_container').show();

                    $('#vm_video_container input').prop('disabled', true);
                    $('#vm_video_container').hide();
                    $('#ul_video_container input').prop('disabled', true);
                    $('#ul_video_container').hide();
                } else if (selected == 'vimeo') {
                    $('#yt_video_container input').prop('disabled', true);
                    $('#yt_video_container').hide();
                    $('#ul_video_container input').prop('disabled', true);
                    $('#ul_video_container').hide();

                    $('#vm_video_container input').prop('disabled', false);
                    $('#vm_video_container').show();
                } else if (selected == 'upload_video') {
                    $('#yt_video_container input').prop('disabled', true);
                    $('#yt_video_container').hide();
                    $('#vm_video_container input').prop('disabled', true);
                    $('#vm_video_container').hide();

                    $('#ul_video_container input').prop('disabled', false);
                    $('#ul_video_container').show();
                }

            });

            $('.video_source:checked').trigger('click');

            $(".chosen-select").chosen().change(function () {});

            var type = $('#type').val();

            if (type == 'New Hire') {
                $('.new-hire-div').show();
                $('.event-div').hide();
                $('#announce-for').addClass('col-lg-6 col-md-6 col-sm-6');
                $('#announce-for').removeClass('col-lg-12 col-md-12 col-sm-12');
            } else {
                $('.new-hire-div').hide();
                $('.event-div').show();
                $('#announce-for').removeClass('col-lg-6 col-md-6 col-sm-6');
                $('#announce-for').addClass('col-lg-12 col-md-12 col-sm-12');
            }

            $('#type').change(function () {
                var type = $(this).val();
                if (type == 'New Hire') {
                    $('.new-hire-div').show();
                    $('.event-div').hide();
                } else {
                    $('.new-hire-div').hide();
                    $('.event-div').show();
                }
            });

            $('.datepicker').datepicker({dateFormat: 'mm-dd-yy'}).val();

            $('#display_start_date').datepicker({
                dateFormat: 'mm-dd-yy',
                changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>",
                onSelect: function (value) {
                    $('#display_end_date').datepicker('option', 'minDate', value);
                }
            }).datepicker('option', 'maxDate', $('#display_end_date').val());

            $('#display_end_date').datepicker({
                dateFormat: 'mm-dd-yy',
                changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>",
                onSelect: function (value) {
                    $('#display_start_date').datepicker('option', 'maxDate', value);
                }
            }).datepicker('option', 'minDate', $('#display_start_date').val());

            $('#event_start_date').datepicker({
                dateFormat: 'mm-dd-yy',
                changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>",
                onSelect: function (value) {
                    $('#event_end_date').datepicker('option', 'minDate', value);
                }
            }).datepicker('option', 'maxDate', $('#event_end_date').val());

            $('#event_end_date').datepicker({
                dateFormat: 'mm-dd-yy',
                changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>",
                onSelect: function (value) {
                    $('#event_start_date').datepicker('option', 'maxDate', value);
                }
            }).datepicker('option', 'minDate', $('#event_start_date').val());

            $('#new_hire_joining_date').datepicker({
                dateFormat: 'mm-dd-yy',
                changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>"
            });


        });

        function check_file(val) {

            var fileName = $("#" + val).val();
            if (fileName.length > 0) {
                $('#name_' + val).html(fileName.substring(0, 45));
                var ext = fileName.split('.').pop();
                if (val == 'pictures') {
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

        function check_upload_video(val) {
            var fileName = $("#" + val).val();

            if (fileName.length > 0) {
                $('#name_' + val).html(fileName.substring(0, 45));
                var ext = fileName.split('.').pop();
                var ext = ext.toLowerCase();

                if (val == 'video') {
                    if (ext != "mp4" && ext != "m4a" && ext != "m4v" && ext != "f4v" && ext != "f4a" && ext != "m4b" && ext != "m4r" && ext != "f4b" && ext != "mov") {
                        $("#" + val).val(null);
                        alertify.error("Please select a valid video format.");
                        $('#name_' + val).html('<p class="red">Only (.mp4, .m4a, .m4v, .f4v, .f4a, .m4b, .m4r, .f4b, .mov) allowed!</p>');
                        return false;
                    } else {
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
                    }

                }
            } else {
                $('#name_' + val).html('No video selected');
                alertify.error("No video selected");
                $('#name_' + val).html('<p class="red">Please select video</p>');

            }
        }
</script>