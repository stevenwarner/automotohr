<?php 
$companyId = $session['company_detail']['sid'];
$employeeId = $session['employer_detail']['sid'];
?>
<!-- Email Section Start -->

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
                                                    
                                                        <?php if (!empty($employees)) { ?>
                                                            <?php foreach ($employees as $employee) { ?>
                                                                <option value="<?php echo $employee['sid']; ?>">
                                                                    <?php echo remakeEmployeeName($employee); ?>
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
                                                    <textarea class="ckeditor" style="padding:5px; height:200px; width:100%;" class="invoice-fields" name="message" id="email_message"></textarea>
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($report['emails']) { ?>
    <?php $this->load->view('compliance_safety_reporting/partials/files/manager_safety_incident_email_section', $emails); ?>
<?php } ?>
<!-- Email Section End -->

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
                                        <th class="text-center" colspan="2">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($report['libraryItems'])) { ?>
                                        <?php foreach ($report['libraryItems'] as $d_key => $item) { ?>
                                            <?php if ($item['file_type'] == 'document' || $item['file_type'] == 'image' ) { ?>
                                                <tr>
                                                    <?php
                                                    $extension = pathinfo($item['s3_file_value'], PATHINFO_EXTENSION);
                                                    $item_url       = '';
                                                    $item_category  = '';
                                                    $item_type      = $item['file_type'];
                                                    $item_extension = strtolower($extension);
                                                    $item_path      = $item['s3_file_value'];
                                                    $item_title     = $item['title'];

                                                    if ($item_extension == 'pdf') {
                                                        $item_category = 'Document';
                                                        $item_url = 'https://docs.google.com/gview?url=' . AWS_S3_BUCKET_URL . $item_path . '&embedded=true';
                                                    } else if (in_array($item_extension, ['doc', 'docx'])) {
                                                        $item_category = 'Document';
                                                        $item_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode(AWS_S3_BUCKET_URL . $item_path);
                                                    } else if (in_array($item_extension, ['jpe', 'jpg', 'jpeg', 'png', 'gif'])) {
                                                        $item_category = 'Image';
                                                        $item_url = AWS_S3_BUCKET_URL . $item_path;
                                                    }
                                                    ?>

                                                    <td class="text-center"><?php echo $item_title; ?></td>
                                                    <td class="text-center">
                                                        <?php echo $item_category; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="javascript:;" class="btn btn-block btn-info" onclick="view_library_item(this);" item-category="document" item-title="<?php echo $item_title; ?>" item-type="<?php echo $item_type; ?>" item-url="<?php echo $item_url; ?>">View Document</a>
                                                    </td>
                                                    <td class="text-center">
                                                        <label class="control control--checkbox" style="margin-left:10px; margin-top:10px;">
                                                            <input class="select_lib_item" id="doc_key_d_<?php echo $item['sid']; ?>" type="checkbox" item-category="Document" item-title="<?php echo $item_title; ?>" item-type="<?php echo $item_type; ?>" item-sid="d_<?php echo $item['sid']; ?>" />
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </td>
                                                </tr>
                                            <?php } else { ?>
                                                <tr>
                                                    <?php
                                                    $media_url      = '';
                                                    $media_category = '';
                                                    $media_btn_text = 'Watch Video';
                                                    $media_title    = $item['title'];
                                                    $item_type     = strtolower($item['file_type']);

                                                    if ($item_type == 'link') {
                                                        $media_category = 'Link';
                                                        $media_url = $item['s3_file_value'];
                                                    } else if ($item_type == "video") {
                                                        $media_category = 'Video';
                                                        $media_url = base_url() . 'assets/uploaded_videos/incident_videos/' . $item['s3_file_value'];
                                                    } else if ($item_type == "audio") {
                                                        $media_category = 'Audio';
                                                        $media_btn_text = 'Listen Audio';
                                                        $media_url = base_url() . 'assets/uploaded_videos/incident_videos/' . $item['s3_file_value'];
                                                    }
                                                    ?>

                                                    <td class="text-center"><?php echo $media_title; ?></td>
                                                    <td class="text-center"><?php echo $media_category; ?></td>
                                                    <td class="text-center">
                                                        <a href="javascript:;" class="btn btn-block btn-info" onclick="view_library_item(this);" item-category="media" item-title="<?php echo $media_title; ?>" item-type="<?php echo $item_type; ?>" item-url="<?php echo $media_url; ?>"><?php echo $media_btn_text; ?></a>
                                                    </td>
                                                    <td class="text-center">
                                                        <label class="control control--checkbox" style="margin-left:10px; margin-top:10px;">
                                                            <input class="select_lib_item" id="med_key_m_<?php echo $item['sid']; ?>" type="checkbox" item-category="Media" item-title="<?php echo $media_title; ?>" item-type="<?php echo $item_type; ?>" item-sid="m_<?php echo $item['sid']; ?>" />
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </td>
                                                </tr>
                                            <?php } ?>
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
                                    <th class="text-center" colspan="2">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($report['libraryItems'])) { ?>
                                    <?php foreach ($report['libraryItems'] as $d_key => $item) { ?>
                                        <?php if ($item['file_type'] == 'document' || $item['file_type'] == 'image' ) { ?>
                                            <tr>
                                                <?php
                                                $extension = pathinfo($item['s3_file_value'], PATHINFO_EXTENSION);
                                                $item_url       = '';
                                                $item_category  = '';
                                                $item_type      = $item['file_type'];
                                                $item_extension = strtolower($extension);
                                                $item_path      = $item['s3_file_value'];
                                                $item_title     = $item['title'];

                                                if ($item_extension == 'pdf') {
                                                    $item_category = 'Document';
                                                    $item_url = 'https://docs.google.com/gview?url=' . AWS_S3_BUCKET_URL . $item_path . '&embedded=true';
                                                } else if (in_array($item_extension, ['doc', 'docx'])) {
                                                    $item_category = 'Document';
                                                    $item_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode(AWS_S3_BUCKET_URL . $item_path);
                                                } else if (in_array($item_extension, ['jpe', 'jpg', 'jpeg', 'png', 'gif'])) {
                                                    $item_category = 'Image';
                                                    $item_url = AWS_S3_BUCKET_URL . $item_path;
                                                }
                                                ?>

                                                <td class="text-center"><?php echo $item_title; ?></td>
                                                <td class="text-center">
                                                    <?php echo $item_category; ?>
                                                </td>
                                                <td class="text-center">
                                                    <a href="javascript:;" class="btn btn-block btn-info" onclick="view_library_item(this);" item-category="document" item-title="<?php echo $item_title; ?>" item-type="<?php echo $item_type; ?>" item-url="<?php echo $item_url; ?>">View Document</a>
                                                </td>
                                                <td class="text-center">
                                                    <label class="control control--checkbox" style="margin-left:10px; margin-top:10px;">
                                                        <input class="select_lib_item" id="doc_key_d_<?php echo $item['sid']; ?>" type="checkbox" item-category="Document" item-title="<?php echo $item_title; ?>" item-type="<?php echo $item_type; ?>" item-sid="d_<?php echo $item['sid']; ?>" />
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </td>
                                            </tr>
                                        <?php } else { ?>
                                            <tr>
                                                <?php
                                                $media_url      = '';
                                                $media_category = '';
                                                $media_btn_text = 'Watch Video';
                                                $media_title    = $item['title'];
                                                $item_type     = strtolower($item['file_type']);

                                                if ($item_type == 'link') {
                                                    $media_category = 'Link';
                                                    $media_url = $item['s3_file_value'];
                                                } else if ($item_type == "video") {
                                                    $media_category = 'Video';
                                                    $media_url = base_url() . 'assets/uploaded_videos/incident_videos/' . $item['s3_file_value'];
                                                } else if ($item_type == "audio") {
                                                    $media_category = 'Audio';
                                                    $media_btn_text = 'Listen Audio';
                                                    $media_url = base_url() . 'assets/uploaded_videos/incident_videos/' . $item['s3_file_value'];
                                                }
                                                ?>

                                                <td class="text-center"><?php echo $media_title; ?></td>
                                                <td class="text-center"><?php echo $media_category; ?></td>
                                                <td class="text-center">
                                                    <a href="javascript:;" class="btn btn-block btn-info" onclick="view_library_item(this);" item-category="media" item-title="<?php echo $media_title; ?>" item-type="<?php echo $item_type; ?>" item-url="<?php echo $media_url; ?>"><?php echo $media_btn_text; ?></a>
                                                </td>
                                                <td class="text-center">
                                                    <label class="control control--checkbox" style="margin-left:10px; margin-top:10px;">
                                                        <input class="select_lib_item" id="med_key_m_<?php echo $item['sid']; ?>" type="checkbox" item-category="Media" item-title="<?php echo $media_title; ?>" item-type="<?php echo $item_type; ?>" item-sid="m_<?php echo $item['sid']; ?>" />
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </td>
                                            </tr>
                                        <?php } ?>
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

<link rel="StyleSheet" type="text/css" href="<?= base_url('assets/css/chosen.css'); ?>"  />
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets/js/chosen.jquery.js'); ?>"></script>
<script>
    var config = { // Multiselect
        '.chosen-select': {}
    }

    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }

    var emailAttachmentIds = [];

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
        //
        var element = $('#attachment_listing_data > .manual_upload_items'); // Or any selector
        var attachment_size = 0;

        if (element.length) {
            var attachment_size = $('#attachment_listing_data > .manual_upload_items').length;
        }

        if ($('input[name="send_type"]:checked').val() == 'system') {
            receivers = $('#receivers').val();
        } else {
            receivers = $('#manual_address').val();
        }

        var message_subject = $('#subject').val();
        var message_body = CKEDITOR.instances['email_message'].getData();

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
                    var save_attachment_url = '<?php echo base_url('compliance_safety_reporting/save_email_manual_attachment'); ?>';

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
                    form_data.append('report_sid', <?php echo $reportId; ?>);
                    form_data.append('incident_sid', <?php echo $incidentId; ?>);
                    form_data.append('company_sid', <?php echo $companyId; ?>);
                    form_data.append('uploaded_by', <?php echo $employeeId; ?>);

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

                            emailAttachmentIds.push(res_item_sid);

                            if (attachment_size == 0) {
                                setTimeout(function() {
                                    sendNormalEmail('normal');
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
                sendNormalEmail('normal')
            }
        }
    });

    function sendNormalEmail (type) {
        $('#attachment_loader').show();
        var send_type;
        var receivers;
        var message_subject = '';
        var message_body = '';

        if (type == 'normal') {
            message_subject = $('#subject').val();
            message_body = CKEDITOR.instances['email_message'].getData();
        } else if (type == 'popup') {
            message_subject = $('#send_email_subject').val();
            message_body = CKEDITOR.instances['send_email_message'].getData();
        }    
        //
        var form_data = new FormData();
        form_data.append('report_id', '<?php echo $reportId; ?>');
        form_data.append('incident_id', '<?php echo $incidentId; ?>');
        form_data.append('subject', message_subject);
        form_data.append('message', message_body);
        // //
        if (type == 'normal') {
            if ($('input[name="send_type"]:checked').val() == 'system') {
                receivers = $('#receivers').val();
                //
                form_data.append('receivers', receivers);
                form_data.append('send_type', "system");
            } else {
                manual_email = $('#manual_address').val();
                //
                form_data.append('manual_email', manual_email);
                form_data.append('send_type', "manual");
            }
        } else if (type == 'popup') {
            var senderType = $('#send_email_type').val();
            //
            if (senderType == 'system') {
                manual_email = $('#send_email_user').val();
                //
                form_data.append('receivers', receivers);
                form_data.append('send_type', "system");
            } else {
                receivers = $('#send_email_user').val();
                //
                form_data.append('manual_email', manual_email);
                form_data.append('send_type', "manual");
            }
        } 
        //
        if (emailAttachmentIds.length) {
            form_data.append('attach_files', emailAttachmentIds);
        }
        //
        $.ajax({
            url: '<?php echo base_url('compliance_safety_reporting/send_compliance_report_email'); ?>',
            cache: false,
            contentType: false,
            processData: false,
            type: 'post',
            data: form_data,
            success: function(response) {
                $('#attachment_loader').hide();
                alertify.alert('SUCCESS!', 'You have succesfully send an email.', () => {
                    window.location.reload();
                });
            },
            error: function() {

            }
        });
        
    }

    function mark_read(email_sid) {
        var update_url = '<?php echo base_url('compliance_safety_reporting/update_email_read_flag'); ?>';
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
        // 
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

            if (item_type == 'link') {

                $('#library-youtube-section').show();
                var video = $("<iframe />")
                    .attr("id", "library-youtube-iframe")
                    .attr("src", item_url);
                $("#library-youtube-placeholder").append(video);

            } else if (item_type == 'video') {
                $('#library-video-section').show();
                var video = $("<video />")
                    .attr("id", "library-upload-video")
                    .attr('src', item_url)
                    .attr('controls', true);
                $("#library-video-placeholder").append(video);

            } else if (item_type == 'audio') {
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
console.log(emailAttachmentIds)
        if ($(this).prop('checked') == true) {

            var item_type = $(this).attr("item-category");
            var item_source = $(this).attr("item-type");
            var item_title = $(this).attr("item-title");

            $('#email_attachment_list').show();
            $('#attachment_listing_data').prepend('<tr id="lib_item_' + item_id + '"><input type="hidden" name="attachment[' + item_id + '][item_type]" value="' + item_type + '"><input type="hidden" name="attachment[' + item_id + '][record_sid]" value="' + item_id + '"><td class="text-center">' + item_title + '</td><td class="text-center">' + item_type + '</td><td class="text-center">' + item_source + '</td><td><a href="javascript:;" item-sid="' + item_id + '" attachment-type="library" item-type="' + item_type + '" class="btn btn-block btn-info js-remove-attachment">Remove</a></td></tr>');
        
            emailAttachmentIds.push(item_id.split('_')[1]);
        } else {
            $('#lib_item_' + item_id).remove();
            //
            var index = emailAttachmentIds.indexOf(item_id);

            // If the ID exists, remove it
            if (index > -1) {
                emailAttachmentIds.splice(index, 1);
            }
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
        //
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
                var myurl = "<?php echo base_url('compliance_safety_reporting/validate_vimeo'); ?>";
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
            //
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
            var email_address = $(source).attr('data-email');
            $('#send_email_address').val(email_address);
            $('#send_email_user').attr('name', 'manual_email');
            $('#send_email_user').val(email_address);
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
            //
            emailAttachmentIds.push(item_id.split('_')[1]);
        } else {
            $('#pop_up_lib_item_' + item_id).remove();
            //
            var index = emailAttachmentIds.indexOf(item_id);

            // If the ID exists, remove it
            if (index > -1) {
                emailAttachmentIds.splice(index, 1);
            }
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
                var myurl = "<?php echo base_url('compliance_safety_reporting/validate_vimeo'); ?>";
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

        var element = $('#pop_up_attachment_listing_data > .pop_up_manual_upload_items'); // Or any selector
        var manual_attachment_size = 0;

        if (element.length) {
            var manual_attachment_size = $('#pop_up_attachment_listing_data > .pop_up_manual_upload_items').length;
        }

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
                    var save_attachment_url = '<?php echo base_url('compliance_safety_reporting/save_email_manual_attachment'); ?>';

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
                    form_data.append('report_sid', <?php echo $reportId; ?>);
                    form_data.append('incident_sid', <?php echo $incidentId; ?>);
                    form_data.append('company_sid', <?php echo $companyId; ?>);
                    form_data.append('uploaded_by', <?php echo $employeeId; ?>);

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
                            emailAttachmentIds.push(res_item_sid);

                            if (manual_attachment_size == 0) {
                                setTimeout(function() {
                                    sendNormalEmail('popup')
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
                sendNormalEmail('popup')
            }
        }
    });

    // Email JS End
        
</script>
