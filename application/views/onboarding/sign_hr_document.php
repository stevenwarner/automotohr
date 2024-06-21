<?php $company_name = ucwords($session['company_detail']['CompanyName']); ?>
<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="btn-panel">
                    <div class="row">
                        <div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-4">
                            <a href="<?php echo $back_url; ?>" class="btn blue-button btn-block"><i class="fa fa-angle-left"></i> Documents</a>
                        </div>
                        <div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-4">

                        </div>
                        <div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-4">
                        </div>
                    </div>

                </div>
                <div class="page-header">
                    <h1 class="section-ttile">Review & Sign <?php echo $doc == 'o' ? 'Offer Letter' : 'Assigned Document'; ?></h1>
                    <strong>Information:</strong> If you are unable to view the document, kindly reload the page.
                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div id="jstopdf" class="hr-box" style="background: #fff;">
                            <div class="alert alert-info">
                                <strong><?php echo ucwords($document['document_title']); ?> <?php echo $document['is_required'] == 1 ? ' <i class="fa fa-asterisk jsTooltip" style="color: #cc1100;" aria-hidden="true" title="You must complete this document to finish the onboarding process."></i>' : ''; ?></strong>
                            </div>
                            <div class="hr-innerpadding">
                                <div class="row">
                                    <div class="col-xs-12" id="required_fields_div">
                                        <?php if ($document['document_type'] == 'hybrid_document' || $document['offer_letter_type'] == 'hybrid_document') { ?>
                                            <div class="img-thumbnail text-center" style="width: 100%; max-height: 82em;">
                                                <?php

                                                $document_filename = !empty($document['document_s3_name']) ? $document['document_s3_name'] : '';
                                                $document_file = pathinfo($document_filename);
                                                $document_extension = strtolower($document['document_extension']);

                                                //
                                                $t = explode('.', $document_filename);
                                                $de = $t[sizeof($t) - 1];
                                                //
                                                if ($de != $document_extension) $document_extension = $de;

                                                if (in_array($document_extension, ['pdf', 'csv'])) {
                                                    $allowed_mime_types = ''; ?>
                                                    <iframe src="<?php echo  'https://docs.google.com/gview?url=' . AWS_S3_BUCKET_URL . $document_filename . '&embedded=true'; ?>" class="uploaded-file-preview js-hybrid-preview" style="width:100%; height:80em;" frameborder="0"></iframe>
                                                <?php } else if (in_array($document_extension, ['jpe', 'jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg'])) { ?>
                                                    <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $document_filename; ?>" />
                                                <?php } else if (in_array($document_extension, ['doc', 'docx', 'xls', 'xlsx'])) { ?>
                                                    <iframe src="<?php echo 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode(AWS_S3_BUCKET_URL . $document_filename); ?>" class="uploaded-file-preview js-hybrid-preview" style="width:100%; height:80em;" frameborder="0"></iframe>
                                                <?php } else { ?>
                                                    <iframe src="<?php echo  'https://docs.google.com/gview?url=' . AWS_S3_BUCKET_URL . $document_filename . '&embedded=true'; ?>" class="uploaded-file-preview js-hybrid-preview" style="width:100%; height:80em;" frameborder="0"></iframe>
                                                <?php } ?>

                                            </div>
                                            <br />
                                            <br />
                                            <div class="alert alert-info js-hybrid-preview">
                                                <strong>Description</strong>
                                            </div>
                                            <?php
                                            $consent = isset($document['user_consent']) ? $document['user_consent'] : 0;
                                            if ($consent == 0 || ($consent == 1 && !empty($document['form_input_data']))) {
                                                echo html_entity_decode($document['document_description']);
                                            } elseif ($consent == 1) { ?>
                                                <iframe src="<?php echo $document['submitted_description']; ?>" name="printf" class="uploaded-file-preview" style="width:100%; height:80em;" frameborder="0"></iframe>
                                            <?php } ?>
                                        <?php } else if ($document['document_type'] == 'uploaded' || $document['offer_letter_type'] == 'uploaded') { ?>
                                            <div class="img-thumbnail text-center" style="width: 100%; max-height: 82em;">
                                                <?php
                                                $document_filename = !empty($document['document_s3_name']) ? $document['document_s3_name'] : '';
                                                $document_file = pathinfo($document_filename);
                                                $document_extension = strtolower($document['document_extension']);

                                                //
                                                $t = explode('.', $document_filename);
                                                $de = $t[sizeof($t) - 1];
                                                //
                                                if ($de != $document_extension) $document_extension = $de;

                                                if (in_array($document_extension, ['pdf', 'csv'])) {
                                                    $allowed_mime_types = ''; ?>
                                                    <iframe src="<?php echo  'https://docs.google.com/gview?url=' . AWS_S3_BUCKET_URL . $document_filename . '&embedded=true'; ?>" class="uploaded-file-preview" style="width:100%; height:80em;" frameborder="0"></iframe>
                                                <?php } else if (in_array($document_extension, ['jpe', 'jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg'])) { ?>
                                                    <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $document_filename; ?>" />
                                                <?php } else if (in_array($document_extension, ['doc', 'docx', 'xls', 'xlsx'])) { ?>
                                                    <iframe src="<?php echo 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode(AWS_S3_BUCKET_URL . $document_filename); ?>" class="uploaded-file-preview" style="width:100%; height:80em;" frameborder="0"></iframe>
                                                <?php } ?>
                                            </div>
                                            <?php } else {
                                            $consent = isset($document['user_consent']) ? $document['user_consent'] : 0;
                                            if ($consent == 0 || ($consent == 1 && !empty($document['form_input_data']))) {
                                                echo html_entity_decode($document['document_description']);
                                            } elseif ($consent == 1) { ?>
                                                <iframe src="<?php echo $document['submitted_description']; ?>" name="printf" class="uploaded-file-preview" style="width:100%; height:80em;" frameborder="0"></iframe>
                                        <?php }
                                        }
                                        // add signature here for generated document 
                                        ?>
                                        <?php if ($save_offer_letter_type == 'save_only') { ?>
                                            <form id="user_consent_form" enctype="multipart/form-data" method="post" action="">
                                                <input type="hidden" name="perform_action" value="sign_document" />
                                                <input type="hidden" name="page_content" value="">
                                                <input type="hidden" name="save_signature" id="save_signature" value="no">
                                                <input type="hidden" name="save_signature_initial" id="save_signature_initial" value="no">
                                                <input type="hidden" name="save_signature_date" id="save_signature_date" value="no">
                                                <input type="hidden" name="save_PDF" id="save_PDF" value="yes">
                                                <input type="hidden" name="save_input_values" id="save_input_values" value="">
                                                <input type="hidden" name="user_consent" value="1">
                                                <?php $consent = isset($document['user_consent']) ? $document['user_consent'] : 0;

                                                if ($consent == 0) { ?>
                                                    <div class="row">
                                                        <div class="col-lg-12 text-center">
                                                            <button onclick="func_save_document_only();" type="button" class="btn blue-button break-word-text disabled_consent_btn">Save Document</button>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </form>
                                        <?php } else if (($document['signature_required'] == 1 || $save_offer_letter_type == 'consent_only') && ($document_type == 'generated' || $document_type == 'hybrid_document' || ($document_type == 'offer_letter' && ($document['offer_letter_type'] == 'generated' || $document['offer_letter_type'] == 'hybrid_document')))) { ?>
                                            <form id="user_consent_form" enctype="multipart/form-data" method="post" action="<?php echo $save_post_url; ?>">
                                                <input type="hidden" name="perform_action" value="sign_document" />
                                                <input type="hidden" name="page_content" value="">
                                                <input type="hidden" name="save_signature" id="save_signature" value="yes">
                                                <input type="hidden" name="save_signature_initial" id="save_signature_initial" value="yes">
                                                <input type="hidden" name="save_signature_date" id="save_signature_date" value="yes">
                                                <input type="hidden" name="save_PDF" id="save_PDF" value="yes">
                                                <input type="hidden" name="save_input_values" id="save_input_values" value="">
                                                <hr />
                                                <?php $consent = isset($document['user_consent']) ? $document['user_consent'] : 0;
                                                if ($consent == 0) { ?>
                                                    <div class="row">
                                                        <div class="col-xs-12 text-justify">
                                                            <?php
                                                            echo '<p>' . str_replace("{{company_name}}", $company_name, SIGNATURE_CONSENT_HEADING) . '</p>';
                                                            echo '<p>' . SIGNATURE_CONSENT_TITLE . '</p>';
                                                            echo '<p>' . str_replace("{{company_name}}", $company_name, SIGNATURE_CONSENT_DESCRIPTION) . '</p>';
                                                            ?>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <?php $consent = isset($document['user_consent']) ? $document['user_consent'] : 0; ?>
                                                            <label class="control control--checkbox">
                                                                <?php echo SIGNATURE_CONSENT_CHECKBOX; ?>
                                                                <input <?php echo $signed_flag == true ? 'disabled="disabled"' : ''; ?> <?php echo set_checkbox('user_consent', 1, $consent == 1); ?> data-rule-required="true" type="checkbox" id="user_consent" name="user_consent" value="1" <?php echo $consent == 1 ? 'checked="checked"' : ''; ?> />
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                <?php } ?>


                                                <?php if ($signed_flag == false) { ?>
                                                    <div class="row">
                                                        <div class="col-lg-12 text-center">
                                                            <button <?php echo $signed_flag == true ? 'disabled="disabled"' : ''; ?> onclick="func_save_e_signature();" type="button" class="btn blue-button break-word-text disabled_consent_btn" <?php echo $consent == 1 ? 'disabled="disabled"' : ''; ?>><?php echo SIGNATURE_CONSENT_BUTTON; ?></button>
                                                        </div>
                                                    </div>
                                                    <hr />
                                                <?php } ?>
                                            </form>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if ($document['document_sid'] != 0) { ?> <!-- For All other than Manual Upload -->
                            <?php if (!empty($attached_video) && $attached_video['video_required'] == 1 && !empty($attached_video['video_source'])) { ?>
                                <div class="hr-box">
                                    <div class="alert alert-info">
                                        <strong>Attachment Video</strong>
                                    </div>
                                    <div class="hr-innerpadding">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <figure class="">
                                                    <?php $source = $attached_video['video_source']; ?>
                                                    <?php if ($source == 'youtube') { ?>
                                                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo $attached_video['video_url']; ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen style="width:100%; height:500px !important;"></iframe>
                                                    <?php } elseif ($source == 'vimeo') { ?>
                                                        <iframe class="embed-responsive-item" src="https://player.vimeo.com/video/<?php echo $attached_video['video_url']; ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen style="width:100%; height:500px !important;"></iframe>
                                                    <?php } else { ?>
                                                        <video controls>
                                                            <source src="<?php echo base_url() . 'assets/uploaded_videos/' . $attached_video['video_url']; ?>" type='video/mp4'>
                                                        </video>
                                                    <?php } ?>
                                                </figure>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>

                            <?php if ($document['acknowledgment_required'] == 1 || $document['download_required'] == 1 || $document['signature_required'] == 1) { ?>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <?php if ($document['acknowledgment_required'] == 1 && $document['signature_required'] == 0 && $save_offer_letter_type != 'consent_only') { ?>
                                            <div class="panel panel-primary">
                                                <div class="panel-heading stev_blue">
                                                    <strong><?php echo $acknowledgment_action_title; ?></strong>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="document-action-required">
                                                        <?php echo $acknowledgment_action_desc; ?>
                                                    </div>
                                                    <div class="document-action-required">
                                                        <?php echo $acknowledgement_status; ?>
                                                    </div>
                                                    <div class="document-action-required">
                                                        <form id="form_acknowledge_document" method="post" enctype="multipart/form-data" action="<?php echo current_url(); ?>">
                                                            <input type="hidden" id="perform_action" name="perform_action" value="acknowledge_document" />
                                                            <input type="hidden" id="unique_sid" name="unique_sid" value="<?php echo $unique_sid; ?>" />
                                                            <input type="hidden" id="user_type" name="user_type" value="<?php echo $document['user_type']; ?>" />
                                                            <input type="hidden" id="user_sid" name="user_sid" value="<?php echo $document['user_sid']; ?>" />
                                                            <input type="hidden" id="document_sid" name="document_sid" value="<?php echo $document['document_sid']; ?>" />
                                                        </form>
                                                        <?php if ($document['acknowledged_date'] != NULL) {
                                                            echo '<b>Acknowledged On: </b>';
                                                            echo convert_date_to_frontend_format($document['acknowledged_date']);
                                                        } ?>
                                                        <button onclick="<?php echo $acknowledgement_button_action; ?>" type="button" class="btn <?php echo $acknowledgement_button_css; ?> pull-right"><?php echo $acknowledgement_button_txt; ?></button>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <?php if ($document['download_required'] == 1) { ?>
                                            <div class="panel panel-primary">
                                                <div class="panel-heading stev_blue">
                                                    <strong><?php echo $download_action_title; ?></strong>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="document-action-required">
                                                        <?php echo $download_action_desc; ?>
                                                    </div>
                                                    <div class="document-action-required">
                                                        <?php echo $download_status; ?>
                                                    </div>
                                                    <div class="document-action-required">
                                                        <?php if ($document['downloaded_date'] != NULL) {
                                                            echo '<b>Downloaded On: </b>';
                                                            echo convert_date_to_frontend_format($document['downloaded_date']);
                                                        } ?>

                                                        <a target="_blank" href="<?php echo $download_button_action; ?>" id="download_btn_click" class="btn <?php echo $download_button_css; ?> pull-right" onclick="save_print()">
                                                            <?php echo $download_button_txt; ?>
                                                        </a>
                                                        <a target="_blank" href="<?php echo $print_button_action; ?>" class="btn pull-right <?php echo $download_button_css; ?>" style="margin-right: 10px;" id="print_btn_click">
                                                            print Document
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <?php if ($document['signature_required'] == 1 && ($document_type == 'uploaded' || $document['offer_letter_type'] == 'uploaded')) { ?>
                                            <div class="panel panel-primary">
                                                <div class="panel-heading stev_blue">
                                                    <strong><?php echo $uploaded_action_title; ?></strong>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="document-action-required">
                                                        <?php echo $uploaded_action_desc; ?>
                                                    </div>
                                                    <div class="document-action-required">
                                                        <?php echo $uploaded_status; ?>
                                                    </div>
                                                    <div class="document-action-required">
                                                        <?php if ($document['uploaded_date'] != NULL) {
                                                            echo '<b>Uploaded On: </b>';
                                                            echo convert_date_to_frontend_format($document['uploaded_date']);
                                                        } ?>

                                                        <form id="form_upload_file" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                            <input type="hidden" id="perform_action" name="perform_action" value="upload_document" />
                                                            <input type="hidden" id="unique_sid" name="unique_sid" value="<?php echo $unique_sid; ?>" />
                                                            <input type="hidden" id="user_type" name="user_type" value="<?php echo $document['user_type']; ?>" />
                                                            <input type="hidden" id="user_sid" name="user_sid" value="<?php echo $document['user_sid']; ?>" />
                                                            <input type="hidden" id="document_sid" name="document_sid" value="<?php echo $document['document_sid']; ?>" />
                                                            <input type="hidden" id="document_type" name="document_type" value="<?php echo $document['document_type']; ?>" />
                                                            <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $document['company_sid']; ?>" />

                                                            <div class="row">
                                                                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                                    <div class="form-wrp width-280">
                                                                        <div class="form-group">
                                                                            <div class="upload-file form-control">
                                                                                <span class="selected-file" id="name_upload_file">No file selected</span>
                                                                                <input name="upload_file" id="upload_file" type="file" />
                                                                                <a href="javascript:;" style="background: #0000ff;">Choose File</a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                    <button type="submit" class="btn <?php echo $uploaded_button_css; ?> btn-equalizer btn-block"><?php echo $uploaded_button_txt; ?></button>
                                                                    <?php if (!empty($document['uploaded_file'])) { ?>
                                                                        <?php $document_filename = $document['uploaded_file']; ?>
                                                                        <?php $document_file = pathinfo($document_filename); ?>
                                                                        <?php $document_extension = $document_file['extension']; ?>
                                                                        <a class="btn blue-button btn-equalizer btn-block" href="javascript:void(0);" onclick="fLaunchModal(this);" data-preview-url="<?= AWS_S3_BUCKET_URL . $document_filename; ?>" data-download-url="<?= AWS_S3_BUCKET_URL . $document_filename; ?>" data-file-name="<?php echo $document_filename; ?>" data-document-title="<?php echo $document_filename; ?>" data-download-sid="<?php echo $document['sid']; ?>" data-preview-ext="<?php echo $document_extension ?>">Preview Uploaded</a>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <?php if ($document['signature_required'] == 1 && $document_type == 'generated') { ?>
                                            <!--do nothing for now-->
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php   } ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="is_signature" value="false">
<input type="hidden" id="is_signature_initial" value="false">
<input type="hidden" id="is_signature_date" value="false">
<?php $this->load->view('static-pages/e_signature_popup'); ?>
<script src="<?php echo base_url('assets/employee_panel/js/kendoUI.min.js'); ?>"></script>
<style>
    #required_fields_div ol,
    #required_fields_div ul {
        padding-left: 15px !important;
    }
</style>
<script>
    $(document).ready(function() {
        //
        $(".js_last_work_date").datepicker({
            format: "MM/DD/YYYY",
            changeYear: true,
            changeMonth: true
        })

        //
        $(".js_date_of_occurrence").datepicker({
            format: "MM/DD/YYYY",
            changeYear: true,
            changeMonth: true
        })
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }

        if ($('#jstopdf').find('select').length >= 0) {
            $('#jstopdf').find('select').map(function(i) {
                //
                $(this).addClass('js_select_document');
                $(this).prop('name', 'selectDD' + i);
            });
        }

        <?php if (!empty($document['form_input_data'])) { ?>
            var form_input_data = <?php echo $form_input_data; ?>;
            form_input_data = Object.entries(form_input_data);

            $.each(form_input_data, function(key, input_value) {
                // for fillables
                if (input_value[0] === "supervisor") {
                    $("input.js_supervisor").val(input_value[1]);
                } else if (input_value[0] === "department") {
                    $("input.js_department").val(input_value[1]);
                } else if (input_value[0] === "last_work_date") {
                    $("input.js_last_work_date").val(input_value[1]);
                } else if (input_value[0] === "reason_to_leave_company") {
                    $("textarea.js_reason_to_leave_company").val(input_value[1]);
                } else if (input_value[0] === "forwarding_information") {
                    $("textarea.js_forwarding_information").val(input_value[1]);
                } else if (input_value[0] === "employee_name") {
                    $("input.js_employee_name").val(input_value[1]);
                } else if (input_value[0] === "employee_job_title") {
                    $("input.js_employee_job_title").val(input_value[1]);
                } else if (input_value[0] === "is_termination_voluntary") {
                    $('input.js_is_termination_voluntary[value="' + (input_value[1]) + '"]').prop("checked", true);
                } else if (input_value[0] === "property_returned") {
                    $('input.js_property_returned[value="' + (input_value[1]) + '"]').prop("checked", true);
                } else if (input_value[0] === "reemploying") {
                    $('input.js_reemploying[value="' + (input_value[1]) + '"]').prop("checked", true);
                } else if (input_value[0] === "date_of_occurrence") {
                    $("input.js_date_of_occurrence").val(input_value[1]);
                } else if (input_value[0] === "summary_of_violation") {
                    $("textarea.js_summary_of_violation").val(input_value[1]);
                } else if (input_value[0] === "summary_of_corrective_plan") {
                    $("textarea.js_summary_of_corrective_plan").val(input_value[1]);
                } else if (input_value[0] === "follow_up_dates") {
                    $("textarea.js_follow_up_dates").val(input_value[1]);
                } else if (input_value[0] === "counselling_form_fields_textarea") {
                    $("textarea.js_counselling_form_fields_textarea").removeClass("hidden");
                    $("textarea.js_counselling_form_fields_textarea").val(input_value[1]);
                } else if (input_value[0] === "counselling_form_fields") {
                    input_value[1].map(function(v) {
                        $('input.js_counselling_form_fields[value="' + (v) + '"]').prop("checked", true);
                    });
                } else if (input_value[0] === "q1") {
                    $("textarea.js_q1").val(input_value[1]);
                } else if (input_value[0] === "employee_number") {
                    $("input.js_employee_number").val(input_value[1]);
                } else if (input_value[0] === "q2") {
                    $("textarea.js_q2").val(input_value[1]);
                } else if (input_value[0] === "q3") {
                    $("textarea.js_q3").val(input_value[1]);
                } else if (input_value[0] === "q4") {
                    $("textarea.js_q4").val(input_value[1]);
                } else if (input_value[0] === "q5") {
                    $("textarea.js_q5").val(input_value[1]);
                } else if (input_value[0] === "fillable_rate") {
                    $('input.js_fillable_rate').prop("checked", input_value[1] === "yes" ? true : false);
                } else if (input_value[0] === "fillable_job") {
                    $('input.js_fillable_job').prop("checked", input_value[1] === "yes" ? true : false);
                } else if (input_value[0] === "fillable_department") {
                    $('input.js_fillable_department').prop("checked", input_value[1] === "yes" ? true : false);
                } else if (input_value[0] === "fillable_location") {
                    $('input.js_fillable_location').prop("checked", input_value[1] === "yes" ? true : false);
                } else if (input_value[0] === "fillable_shift") {
                    $('input.js_fillable_shift').prop("checked", input_value[1] === "yes" ? true : false);
                } else if (input_value[0] === "fillable_other") {
                    $('input.js_fillable_other').prop("checked", input_value[1] === "yes" ? true : false);
                } else if (input_value[0] === "fillable_all_reasons") {
                    input_value[1].map(function(v) {
                        $('input.js_fillable_all_reasons[value="' + (v) + '"]').prop("checked", true);
                    });
                } else if (input_value[0] === "fillable_from_rate") {
                    $("input.js_fillable_from_rate").val(input_value[1]);
                } else if (input_value[0] === "fillable_to_rate") {
                    $("input.js_fillable_to_rate").val(input_value[1]);
                } else if (input_value[0] == 'signature_person_name') {
                    var input_field_id = input_value[0];
                    var input_field_val = input_value[1];
                    $('#' + input_field_id).val(input_field_val);
                    $('.js_' + input_field_id).val(input_field_val);
                } else {
                    var input_field_id = input_value[0] + '_id';
                    var input_field_val = input_value[1];
                    var input_type = $('#' + input_field_id).attr('data-type');

                    if (input_type == 'text') {
                        $('#' + input_field_id).val(input_field_val);
                        <?php if ($document['user_consent'] == 1) : ?>
                            $('#' + input_field_id).prop('disabled', true);
                        <?php endif; ?>
                    } else if (input_type == 'checkbox') {
                        if (input_field_val == 'yes') {
                            $('#' + input_field_id).prop('checked', true);;
                        }
                        <?php if ($document['user_consent'] == 1) : ?>
                            $('#' + input_field_id).prop('disabled', true);
                        <?php endif; ?>

                    } else if (input_type == 'textarea') {
                        <?php if ($document['user_consent'] == 1) : ?>
                            $('#' + input_field_id).hide();
                            $('#' + input_field_id + '_sec').show();
                            $('#' + input_field_id + '_sec').html(input_field_val);
                            $('#' + input_field_id + '_sec').html(input_field_val);
                        <?php else : ?>
                            $('#' + input_field_id).show();
                            $('#' + input_field_id + '').val(input_field_val);
                        <?php endif; ?>
                    } else if (input_value[0].match(/select/) !== -1) {
                        if (input_value[1] != null) {
                            let cc = get_select_box_value(input_value[0], input_value[1]);
                            $(`select.js_select_document[name="${input_value[0]}"]`).html('');
                            $(`select.js_select_document[name="${input_value[0]}"]`).hide(0);
                            $(`select.js_select_document[name="${input_value[0]}"]`).after(`<strong style="font-size: 20px;">${cc}</strong>`);
                        }
                    }
                }
            });

        <?php } ?>

        function get_select_box_value(select_box_name, select_box_val) {
            var data = select_box_val;
            let cc = '';

            if (select_box_val.indexOf(',') > -1) {
                data = select_box_val.split(',');
            }


            if ($.isArray(data)) {
                let modify_string = '';
                $.each(data, function(key, value) {
                    if (modify_string == '') {
                        modify_string = ' ' + $(`select.js_select_document[name="${select_box_name}"] option[value="${value}"]`).text();
                    } else {
                        modify_string = modify_string + ', ' + $(`select.js_select_document[name="${select_box_name}"] option[value="${value}"]`).text();
                    }
                });
                cc = modify_string;
            } else {
                cc = $(`select.js_select_document[name="${select_box_name}"] option[value="${select_box_val}"]`).text();
            }

            return cc;
        }

        var imgs = $('#jstopdf').find('img');

        if (imgs.length) {
            $(imgs).each(function(i, v) {
                var imgSrc = $(this).attr('src');
                var _this = this;

                var p = /^(?:http(s)?:\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-\._~:/?#[\]@!\$&'\(\)\*\+,;=.]+$/gm;

                if (imgSrc.match(p)) {
                    $.ajax({
                        url: '<?= base_url('hr_documents_management/getbase64/') ?>',
                        data: {
                            url: imgSrc
                        },
                        type: "GET",
                        async: false,
                        success: function(resp) {
                            resp = JSON.parse(resp);
                            $(_this).attr("src", "data:" + resp.type + ";base64," + resp.string);
                        },
                        error: function() {

                        }
                    });
                }
            });
        }


        $('.date_picker2').datepicker({
            dateFormat: 'mm-dd-yy',
            changeYear: true
        });

        $('#form_upload_file').validate({
            rules: {
                upload_file: {
                    required: true,
                    accept: 'image/png,image/bmp,image/gif,image/jpeg,image/tiff,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                }
            },
            messages: {
                upload_file: {
                    required: 'You must select a file to upload.',
                    accept: 'Only Images, MS Word Documents and PDF files are allowed.'
                }
            }
        });

        $('body').on('change', 'input[type=file]', function() {
            var selected_file = $(this).val();
            var selected_file = selected_file.substring(selected_file.lastIndexOf('\\') + 1, selected_file.length);
            var id = $(this).attr('id');
            $('#name_' + id).html(selected_file);
        });
    });

    function func_acknowledge_document() {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to Acknowledge the document?',
            function() {
                $('#form_acknowledge_document').submit();
            },
            function() {
                alertify.error('Canceled!');
            });
    }

    function fLaunchModal(source) {
        var url_segment_original = $(source).attr('data-file-name');
        url_segment_original = url_segment_original.split('.')[0];
        var document_preview_url = $(source).attr('data-preview-url');
        var document_download_url = $(source).attr('data-download-url');
        var document_title = $(source).attr('data-document-title');
        var document_file_name = $(source).attr('data-file-name');
        var file_extension = $(source).attr('data-preview-ext');
        var modal_content = '';
        var footer_content = '';
        var iframe_url = '';
        var document_sid = $(source).attr('data-download-sid');
        var unique_sid = '<?php echo $unique_sid; ?>';

        if (document_preview_url != '') {
            switch (file_extension.toLowerCase()) {
                case 'pdf':
                    iframe_url = document_preview_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    footer_print_btn = '<a target="_blank" href="https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' + url_segment_original + '.pdf" class="btn blue-button">Print</a>';
                    footer_content = '<a target="_blank" class="btn blue-button" href="<?php echo base_url('onboarding/download_assign_document') ?>' + '/' + unique_sid + '/' + document_sid + '/submitted' + '">Download</a>';
                    break;
                case 'doc':
                    iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_preview_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    footer_print_btn = '<a target="_blank" href="https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' + url_segment_original + '%2Edoc&wdAccPdf=0" class="btn blue-button">Print</a>';
                    footer_content = '<a target="_blank" class="btn blue-button" href="<?php echo base_url('onboarding/download_assign_document') ?>' + '/' + unique_sid + '/' + document_sid + '/submitted' + '">Download</a>';
                    break;
                case 'docx':
                    iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_preview_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    footer_print_btn = '<a target="_blank" href="https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' + url_segment_original + '%2Edocx&wdAccPdf=0" class="btn blue-button">Print</a>';
                    footer_content = '<a target="_blank" class="btn blue-button" href="<?php echo base_url('onboarding/download_assign_document') ?>' + '/' + unique_sid + '/' + document_sid + '/submitted' + '">Download</a>';
                    break;
                case 'xls':
                    iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_preview_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    footer_print_btn = '<a target="_blank" href="https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' + url_segment_original + '%2Exls" class="btn blue-button">Print</a>';
                    footer_content = '<a target="_blank" class="btn blue-button" href="<?php echo base_url('onboarding/download_assign_document') ?>' + '/' + unique_sid + '/' + document_sid + '/submitted' + '">Download</a>';
                    break;
                case 'xlsx': //using office docs
                    iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_preview_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    footer_print_btn = '<a target="_blank" href="https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' + url_segment_original + '%2Exlsx" class="btn blue-button">Print</a>';
                    footer_content = '<a target="_blank" class="btn blue-button" href="<?php echo base_url('onboarding/download_assign_document') ?>' + '/' + unique_sid + '/' + document_sid + '/submitted' + '">Download</a>';
                    break;
                case 'jpg':
                case 'jpe':
                case 'jpeg':
                case 'png':
                case 'gif':
                    modal_content = '<img src="' + document_preview_url + '" style="width:100%; height:500px;" />';
                    footer_print_btn = '<a target="_blank" href="<?php echo base_url('onboarding/print_applicant_upload_img/') ?>' + document_file_name + '" class="btn blue-button">Print</a>';
                    footer_content = '<a target="_blank" class="btn blue-button" href="<?php echo base_url('onboarding/download_assign_document') ?>' + '/' + unique_sid + '/' + document_sid + '/submitted' + '">Download</a>';
                    break;
                default: //using google docs
                    iframe_url = 'https://docs.google.com/gview?url=' + document_preview_url + '&embedded=true';
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    footer_content = '<a target="_blank" class="btn blue-button" href="<?php echo base_url('onboarding/download_assign_document') ?>' + '/' + unique_sid + '/' + document_sid + '/submitted' + '">Download</a>';
                    break;
            }
        } else {
            modal_content = '<h5>No ' + document_title + ' Uploaded.</h5>';
            footer_content = '';
        }

        $('#document_modal_body').html(modal_content);
        $('#document_modal_footer').html(footer_content);
        $('#document_modal_footer').append(footer_print_btn);
        $('#document_modal_title').html(document_title);
        $('#document_modal').modal("toggle");
        $('#document_modal').on("shown.bs.modal", function() {
            if (iframe_url != '') {
                $('#preview_iframe').attr('src', iframe_url);
            }
        });
    }

    function save_print() {
        var company_sid = '<?php echo $document['company_sid']; ?>';
        var user_sid = '<?php echo $document['user_sid']; ?>';
        var document_sid = '<?php echo $document['sid']; ?>';
        var myurl = "<?= base_url() ?>onboarding/downloaded_generated_doc/" + user_sid + "/" + company_sid + "/" + document_sid + "/applicant";

        $.ajax({
            type: "GET",
            url: myurl,
            async: false,
            success: function(data) {
                $('#print_btn_click').removeClass('blue-button');
                $('#print_btn_click').addClass('btn-warning');
                $('#download_btn_click').removeClass('blue-button');
                $('#download_btn_click').addClass('btn-warning');
                $('#download_btn_click').html('Re-Download');
            },
            error: function(data) {

            }
        });

        // var draw = kendo.drawing;
        // draw.drawDOM($("#jstopdf"), {
        //     avoidLinks: true,
        //     paperSize: "A4",
        //     margin: { bottom: "1cm" },
        //     scale: 0.6
        // })
        //     .then(function(root) {
        //         return draw.exportPDF(root);
        //     })
        //     .done(function(data) {
        //         var consent = '<?php echo isset($document['user_consent']) ? $document['user_consent'] : 0; ?>';
        //         var pdfdata = "";
        //         if(consent == 0) {
        //             pdfdata = data;
        //         } else {
        //             pdfdata = '<?php echo $document['submitted_description']; ?>';
        //         }

        //         kendo.saveAs({
        //             dataURI: pdfdata,
        //             fileName: '<?php echo ucwords($document['document_title']) . ".pdf"; ?>',
        //         });
        //     });
    }

    $('#print_btn_click').click(function() {
        var company_sid = '<?php echo $document['company_sid']; ?>';
        var user_sid = '<?php echo $document['user_sid']; ?>';
        var document_sid = '<?php echo $document['sid']; ?>';
        var myurl = "<?= base_url() ?>onboarding/downloaded_generated_doc/" + user_sid + "/" + company_sid + "/" + document_sid + "/applicant";

        $.ajax({
            type: "GET",
            url: myurl,
            async: false,
            success: function(data) {
                $('#print_btn_click').removeClass('blue-button');
                $('#print_btn_click').addClass('btn-warning');
                $('#download_btn_click').removeClass('blue-button');
                $('#download_btn_click').addClass('btn-warning');
                $('#download_btn_click').html('Re-Download');
            },
            error: function(data) {

            }
        });
    });

    function func_save_e_signature() {
        if ($('#user_consent_form').valid()) {
            var is_sign = "";
            var is_init = "";
            var is_date = "";

            if ($('.get_signature')[0]) {
                is_sign = $('#is_signature').val();
            } else {
                is_sign = 'true';
                $('#save_signature').val('no');
            }

            if ($('.get_signature_initial')[0]) {
                is_init = $('#is_signature_initial').val();
            } else {
                is_init = 'true';
                $('#save_signature_initial').val('no');
            }

            if ($('.get_signature_date')[0]) {
                is_date = $('#is_signature_date').val();
            } else {
                is_date = 'true';
                $('#save_signature_date').val('no');
            }

            let manualRequired = false;

            if ($('input.js_employee_name').length && !$('input.js_employee_name').val()) {
                manualRequired = true;
            }
            if ($('input.employee_job_title').length && !$('input.employee_job_title').val()) {
                manualRequired = true;
            }
            if ($('input.js_supervisor').length && !$('input.js_supervisor').val()) {
                manualRequired = true;
            }
            if ($('input.js_department').length && !$('input.js_department').val()) {
                manualRequired = true;
            }
            if ($('input.js_last_work_date').length && !$('input.js_last_work_date').val()) {
                manualRequired = true;
            }
            if ($('textarea.js_reason_to_leave_company').length && !$('textarea.js_reason_to_leave_company').val()) {
                manualRequired = true;
            }
            if ($('textarea.js_forwarding_information').length && !$('textarea.js_forwarding_information').val()) {
                manualRequired = true;
            }
            if ($('input.js_date_of_occurrence').length && !$('input.js_date_of_occurrence').val()) {
                manualRequired = true;
            }
            if ($('textarea.js_summary_of_violation').length && !$('textarea.js_summary_of_violation').val()) {
                manualRequired = true;
            }
            if ($('textarea.js_summary_of_corrective_plan').length && !$('textarea.js_summary_of_corrective_plan').val()) {
                manualRequired = true;
            }
            if ($('textarea.js_follow_up_dates').length && !$('textarea.js_follow_up_dates').val()) {
                manualRequired = true;
            }

            if ($('[name="counselling_form_fields[]"]').length) {
                let selectedCounselling = $('[name="counselling_form_fields[]"]:checked').map(function() {
                    return $(this).val()
                }).get()

                if (!selectedCounselling.length) {
                    manualRequired = true;
                } else if ($.inArray("Other", selectedCounselling) !== -1 && !$(".js_counselling_form_fields_textarea").val()) {
                    manualRequired = true;
                }
            }

            if ($('textarea.js_q1').length && !$('textarea.js_q1').val()) {
                manualRequired = true;
            }
            if ($('input.js_employee_number').length && !$('input.js_employee_number').val()) {
                manualRequired = true;
            }
            if ($('textarea.js_q2').length && !$('textarea.js_q2').val()) {
                manualRequired = true;
            }
            if ($('textarea.js_q3').length && !$('textarea.js_q3').val()) {
                manualRequired = true;
            }
            if ($('textarea.js_q4').length && !$('textarea.js_q4').val()) {
                manualRequired = true;
            }
            if ($('textarea.js_q5').length && !$('textarea.js_q5').val()) {
                manualRequired = true;
            }

            // status and payroll

            if ($('[name="fillable_all_reasons[]"]').length) {
                let allReasons = $('[name="fillable_all_reasons[]"]:checked').map(function() {
                    return $(this).val()
                }).get()

                if (!allReasons.length) {
                    manualRequired = true;
                }
            }

            //
            if (
                $('input.js_fillable_rate').length &&
                $('input.js_fillable_rate').prop("checked")
            ) {

                if ($('input.js_fillable_from_rate').length &&
                    !$('input.js_fillable_from_rate').val()) {
                    manualRequired = true;
                }
                if ($('input.js_fillable_to_rate').length &&
                    !$('input.js_fillable_to_rate').val()) {
                    manualRequired = true;
                }
            }

            if (is_sign == 'true' && is_init == 'true' && is_date == 'true' && !manualRequired) {
                var input_values_obj = {};

                if ($('input.js_employee_name').length) {
                    input_values_obj["employee_name"] = $('input.js_employee_name').val();
                }
                if ($('input.js_employee_job_title').length) {
                    input_values_obj["employee_job_title"] = $('input.js_employee_job_title').val();
                }
                if ($('input.js_supervisor').length) {
                    input_values_obj["supervisor"] = $('input.js_supervisor').val();
                }
                if ($('input.js_department').length) {
                    input_values_obj["department"] = $('input.js_department').val();
                }
                if ($('input.js_last_work_date').length) {
                    input_values_obj["last_work_date"] = $('input.js_last_work_date').val();
                }
                if ($('textarea.js_reason_to_leave_company').length) {
                    input_values_obj["reason_to_leave_company"] = $('textarea.js_reason_to_leave_company').val();
                }
                if ($('textarea.js_forwarding_information').length) {
                    input_values_obj["forwarding_information"] = $('textarea.js_forwarding_information').val();
                }
                if ($('input.js_date_of_occurrence').length) {
                    input_values_obj["date_of_occurrence"] = $('input.js_date_of_occurrence').val();
                }
                if ($('textarea.js_summary_of_violation').length) {
                    input_values_obj["summary_of_violation"] = $('textarea.js_summary_of_violation').val();
                }
                if ($('textarea.js_summary_of_corrective_plan').length) {
                    input_values_obj["summary_of_corrective_plan"] = $('textarea.js_summary_of_corrective_plan').val();
                }
                if ($('textarea.js_follow_up_dates').length) {
                    input_values_obj["follow_up_dates"] = $('textarea.js_follow_up_dates').val();
                }
                if ($('[name="counselling_form_fields[]"]').length) {
                    input_values_obj["counselling_form_fields"] = $('[name="counselling_form_fields[]"]:checked').map(function() {
                        return $(this).val()
                    }).get()

                    if ($.inArray("Other", input_values_obj["counselling_form_fields"]) !== -1) {
                        input_values_obj["counselling_form_fields_textarea"] = $(".js_counselling_form_fields_textarea").val()
                    }
                }

                if ($('input.js_employee_number').length) {
                    input_values_obj["employee_number"] = $('input.js_employee_number').val();
                }
                if ($('textarea.js_q1').length) {
                    input_values_obj["q1"] = $('textarea.js_q1').val();
                }
                if ($('textarea.js_q2').length) {
                    input_values_obj["q2"] = $('textarea.js_q2').val();
                }
                if ($('textarea.js_q3').length) {
                    input_values_obj["q3"] = $('textarea.js_q3').val();
                }
                if ($('textarea.js_q4').length) {
                    input_values_obj["q4"] = $('textarea.js_q4').val();
                }
                if ($('textarea.js_q5').length) {
                    input_values_obj["q5"] = $('textarea.js_q5').val();
                }

                // status and payroll
                if ($('input.js_fillable_rate').length) {
                    input_values_obj["fillable_rate"] = $('input.js_fillable_rate').prop("checked") ? "yes" : "no";
                    if ($('input.js_fillable_from_rate').length) {
                        input_values_obj["fillable_from_rate"] = $('input.js_fillable_from_rate').val();
                    }
                    if ($('input.js_fillable_to_rate').length) {
                        input_values_obj["fillable_to_rate"] = $('input.js_fillable_to_rate').val();
                    }
                }
                if ($('input.js_fillable_job').length) {
                    input_values_obj["fillable_job"] = $('input.js_fillable_job').prop("checked") ? "yes" : "no";
                }
                if ($('input.js_fillable_department').length) {
                    input_values_obj["fillable_department"] = $('input.js_fillable_department').prop("checked") ? "yes" : "no";
                }
                if ($('input.js_fillable_location').length) {
                    input_values_obj["fillable_location"] = $('input.js_fillable_location').prop("checked") ? "yes" : "no";
                }
                if ($('input.js_fillable_shift').length) {
                    input_values_obj["fillable_shift"] = $('input.js_fillable_shift').prop("checked") ? "yes" : "no";
                }
                if ($('input.js_fillable_other').length) {
                    input_values_obj["fillable_other"] = $('input.js_fillable_other').prop("checked") ? "yes" : "no";
                }

                if ($('[name="fillable_all_reasons[]"]').length) {
                    input_values_obj["fillable_all_reasons"] = $('[name="fillable_all_reasons[]"]:checked').map(function() {
                        return $(this).val()
                    }).get()
                }

                $('input.short_textbox').map(function() {
                    input_values_obj[this.name] = this.value;
                }).get();


                $('input.long_textbox').map(function() {
                    input_values_obj[this.name] = this.value;
                }).get();

                $('input#signature_person_name').map(function() {
                    input_values_obj[this.name] = this.value;
                }).get();

                $('input.user_checkbox').map(function() {
                    var condition = 'no';
                    if ($(this).is(":checked")) {
                        condition = 'yes';
                    }

                    input_values_obj[this.name] = condition;
                }).get();

                $('textarea.text_area').map(function() {
                    input_values_obj[this.name] = this.value;
                }).get();

                let hasError = false;

                $('select.js_select_document').map(function() {
                    //
                    if ($(this).prop('required') == true && $(this).val() == null && hasError == false) {
                        hasError = true;
                        alertify.alert('WARNING!', 'Please select an option.', () => {});
                    } else if ($(this).prop('multiple') == true) {
                        var multi_select_values = $(this).val();
                        input_values_obj[this.name] = multi_select_values == null ? multi_select_values : multi_select_values.toString();
                    } else {
                        input_values_obj[this.name] = this.value;
                    }
                }).get();

                if (hasError) return;

                $('#save_input_values').val(JSON.stringify(input_values_obj));

                alertify.confirm(
                    'Are you Sure?',
                    'Are you sure you want to Consent And Accept Electronic Signature Agreement?',
                    function() {
                        $('#user_consent_form').submit();

                        /*
                        $('.js-hybrid-preview').remove();
                        $('br').replaceWith('<div></div>');
                        var draw = kendo.drawing;

                        draw.drawDOM($("#jstopdf"), {
                            avoidLinks: false,
                            paperSize: "A4",
                            multiPage: true,
                            margin: { bottom: "2cm" },
                            scale: 0.8
                        })
                            .then(function(root) {
                                return draw.exportPDF(root);
                            })
                            .done(function(pdfdata) {
                                $('.disabled_consent_btn').prop('disabled', true);
                                $('#save_PDF').val(pdfdata);
                                $('#user_consent_form').submit();
                            });
                             */
                    },
                    function() {
                        alertify.error('Cancelled!');
                    }).set('labels', {
                    ok: 'I Consent and Accept!',
                    cancel: 'Cancel'
                });
            } else {
                var validation = {
                    signature: is_sign,
                    signature_Initial: is_init,
                    signature_Date: is_date
                };

                //
                if ($('input.js_employee_name').length) {
                    validation["Your Name"] = $('input.js_employee_name').val() || "false"
                }
                //
                if ($('input.employee_job_title').length) {
                    validation["Job Title"] = $('input.employee_job_title').val() || "false"
                }
                //
                if ($('input.js_supervisor').length) {
                    validation["Supervisor"] = $('input.js_supervisor').val() || "false"
                }
                //
                if ($('input.js_department').length) {
                    validation["Department"] = $('input.js_department').val() || "false"
                }
                //
                if ($('input.js_last_work_date').length) {
                    validation["Last Work Date"] = $('input.js_last_work_date').val() || "false"
                }
                //
                if ($('textarea.js_reason_to_leave_company').length) {
                    validation["Reason to the company"] = $('textarea.js_reason_to_leave_company').val() || "false"
                }
                //
                if ($('textarea.js_forwarding_information').length) {
                    validation["Forwarding information"] = $('textarea.js_forwarding_information').val() || "false"
                }


                if ($('input.js_date_of_occurrence').length) {
                    validation["Date Of Occurence"] = $('input.js_date_of_occurrence').val() || "false"
                }
                if ($('textarea.js_summary_of_violation').length) {
                    validation["Summary Of Voilation"] = $('textarea.js_summary_of_violation').val() || "false"
                }
                if ($('textarea.js_summary_of_corrective_plan').length) {
                    validation["Summary Of Corrective Plan"] = $('textarea.js_summary_of_corrective_plan').val() || "false"
                }
                if ($('textarea.js_follow_up_dates').length) {
                    validation["Follow Up Dates"] = $('textarea.js_follow_up_dates').val() || "false"
                }

                if ($('[name="counselling_form_fields[]"]').length) {
                    let selectedCounselling = $('[name="counselling_form_fields[]"]:checked').map(function() {
                        return $(this).val()
                    }).get()

                    if (!selectedCounselling.length) {
                        validation["Counselling Form Fields"] = "false"
                    } else if ($.inArray("Other", selectedCounselling) !== -1 && !$(".js_counselling_form_fields_textarea").val()) {
                        validation["Counselling Form Fields Other"] = "false"
                    }
                }

                if ($('input.js_employee_number').length) {
                    validation["Employee number"] = $('input.js_employee_number').val() || "false"
                }
                if ($('textarea.js_q1').length) {
                    validation["Description of problem"] = $('textarea.js_q1').val() || "false"
                }
                if ($('textarea.js_q2').length) {
                    validation["Description of performance"] = $('textarea.js_q2').val() || "false"
                }
                if ($('textarea.js_q3').length) {
                    validation["Description of consequences for not meeting expectations"] = $('textarea.js_q3').val() || "false"
                }
                if ($('textarea.js_q4').length) {
                    validation["Dates and descriptions of prior discissions or warnings formal or informal"] = $('textarea.js_q4').val() || "false"
                }
                if ($('textarea.js_q5').length) {
                    validation["Other information you would like to provide"] = $('textarea.js_q5').val() || "false"
                }

                if (
                    $('input.js_fillable_rate').length &&
                    $('input.js_fillable_rate').prop("checked")
                ) {
                    if ($('input.js_fillable_from_rate').length) {
                        validation["From rate"] =
                            $('input.js_fillable_from_rate').val() || "false";
                    }
                    if ($('input.js_fillable_to_rate').length) {
                        validation["To rate"] =
                            $('input.js_fillable_to_rate').val() || "false";
                    }
                }

                if ($('[name="fillable_all_reasons[]"]').length) {
                    let selectedCounselling = $('[name="fillable_all_reasons[]"]:checked').map(function() {
                        return $(this).val()
                    }).get()

                    if (!selectedCounselling.length) {
                        validation["Reasons for change"] = "false"
                    }
                }

                for (var key in validation) {
                    if (validation[key] == 'false') {
                        var type = key.replace("_", " ");
                        alertify.error('Please provide ' + type);
                    }
                }
            }
        }
    }

    function func_save_document_only() {
        if ($('#user_consent_form').valid()) {

            let hasError = false;

            var validation = {};

            //
            if ($('input.js_employee_name').length) {
                validation["Your Name"] = $('input.js_employee_name').val() || "false"
            }
            //
            if ($('input.employee_job_title').length) {
                validation["Job Title"] = $('input.employee_job_title').val() || "false"
            }
            //
            if ($('input.js_supervisor').length) {
                validation["Supervisor"] = $('input.js_supervisor').val() || "false"
            }
            //
            if ($('input.js_department').length) {
                validation["Department"] = $('input.js_department').val() || "false"
            }
            //
            if ($('input.js_last_work_date').length) {
                validation["Last Work Date"] = $('input.js_last_work_date').val() || "false"
            }
            //
            if ($('textarea.js_reason_to_leave_company').length) {
                validation["Reason to the company"] = $('textarea.js_reason_to_leave_company').val() || "false"
            }
            //
            if ($('textarea.js_forwarding_information').length) {
                validation["Forwarding information"] = $('textarea.js_forwarding_information').val() || "false"
            }


            if ($('input.js_date_of_occurrence').length) {
                validation["Date Of Occurence"] = $('input.js_date_of_occurrence').val() || "false"
            }
            if ($('textarea.js_summary_of_violation').length) {
                validation["Summary Of Voilation"] = $('textarea.js_summary_of_violation').val() || "false"
            }
            if ($('textarea.js_summary_of_corrective_plan').length) {
                validation["Summary Of Corrective Plan"] = $('textarea.js_summary_of_corrective_plan').val() || "false"
            }
            if ($('textarea.js_follow_up_dates').length) {
                validation["Follow Up Dates"] = $('textarea.js_follow_up_dates').val() || "false"
            }

            if ($('[name="counselling_form_fields[]"]').length) {
                let selectedCounselling = $('[name="counselling_form_fields[]"]:checked').map(function() {
                    return $(this).val()
                }).get()

                if (!selectedCounselling.length) {
                    validation["Counselling Form Fields"] = "false"
                } else if ($.inArray("Other", selectedCounselling) !== -1 && !$(".js_counselling_form_fields_textarea").val()) {
                    validation["Counselling Form Fields Other"] = "false"
                }
            }

            if ($('input.js_employee_number').length) {
                validation["Employee number"] = $('input.js_employee_number').val() || "false"
            }
            if ($('textarea.js_q1').length) {
                validation["Description of problem"] = $('textarea.js_q1').val() || "false"
            }
            if ($('textarea.js_q2').length) {
                validation["Description of performance"] = $('textarea.js_q2').val() || "false"
            }
            if ($('textarea.js_q3').length) {
                validation["Description of consequences for not meeting expectations"] = $('textarea.js_q3').val() || "false"
            }
            if ($('textarea.js_q4').length) {
                validation["Dates and descriptions of prior discissions or warnings formal or informal"] = $('textarea.js_q4').val() || "false"
            }
            if ($('textarea.js_q5').length) {
                validation["Other information you would like to provide"] = $('textarea.js_q5').val() || "false"
            }

            //
            if ($('input.js_is_termination_voluntary').length) {
                validation["Voluntary Termination"] = $('input.js_is_termination_voluntary:checked').val() || "false"
            }
            //
            if ($('input.js_property_returned').length) {
                validation["Property returned"] = $('input.js_property_returned:checked').val() || "false"
            }
            //
            if ($('input.js_reemploying').length) {
                validation["Re-employing"] = $('input.js_reemploying:checked').val() || "false"
            }

            if (
                $('input.js_fillable_rate').length &&
                $('input.js_fillable_rate').prop("checked")
            ) {
                if ($('input.js_fillable_from_rate').length) {
                    validation["From rate"] =
                        $('input.js_fillable_from_rate').val() || "false";
                }
                if ($('input.js_fillable_to_rate').length) {
                    validation["To rate"] =
                        $('input.js_fillable_to_rate').val() || "false";
                }
            }

            if ($('[name="fillable_all_reasons[]"]').length) {
                let selectedCounselling = $('[name="fillable_all_reasons[]"]:checked').map(function() {
                    return $(this).val()
                }).get()

                if (!selectedCounselling.length) {
                    validation["Reasons for change"] = "false"
                }
            }


            for (var key in validation) {
                if (validation[key] == 'false') {
                    hasError = true;
                    var type = key.replace("_", " ");
                    alertify.error('Please provide ' + type);
                }
            }
            if (hasError) {
                return false;
            }

            alertify.confirm(
                'Are you Sure?',
                'Are you sure you want to Save this Document?',
                function() {
                    var draw = kendo.drawing;

                    draw.drawDOM($("#jstopdf"), {
                            avoidLinks: true,
                            paperSize: "A4",
                            margin: {
                                bottom: "1cm"
                            },
                            scale: 0.6
                        })
                        .then(function(root) {
                            return draw.exportPDF(root);
                        })
                        .done(function(pdfdata) {
                            var input_values_obj = {};
                            // status and payroll
                            if ($('input.js_fillable_rate').length) {
                                input_values_obj["fillable_rate"] = $('input.js_fillable_rate').prop("checked") ? "yes" : "no";
                                if ($('input.js_fillable_from_rate').length) {
                                    input_values_obj["fillable_from_rate"] = $('input.js_fillable_from_rate').val();
                                }
                                if ($('input.js_fillable_to_rate').length) {
                                    input_values_obj["fillable_to_rate"] = $('input.js_fillable_to_rate').val();
                                }
                            }
                            if ($('input.js_fillable_job').length) {
                                input_values_obj["fillable_job"] = $('input.js_fillable_job').prop("checked") ? "yes" : "no";
                            }
                            if ($('input.js_fillable_department').length) {
                                input_values_obj["fillable_department"] = $('input.js_fillable_department').prop("checked") ? "yes" : "no";
                            }
                            if ($('input.js_fillable_location').length) {
                                input_values_obj["fillable_location"] = $('input.js_fillable_location').prop("checked") ? "yes" : "no";
                            }
                            if ($('input.js_fillable_shift').length) {
                                input_values_obj["fillable_shift"] = $('input.js_fillable_shift').prop("checked") ? "yes" : "no";
                            }
                            if ($('input.js_fillable_other').length) {
                                input_values_obj["fillable_other"] = $('input.js_fillable_other').prop("checked") ? "yes" : "no";
                            }

                            if ($('[name="fillable_all_reasons[]"]').length) {
                                input_values_obj["fillable_all_reasons"] = $('[name="fillable_all_reasons[]"]:checked').map(function() {
                                    return $(this).val()
                                }).get()
                            }
                            if ($('input.js_employee_name').length) {
                                input_values_obj["employee_name"] = $('input.js_employee_name').val();
                            }
                            if ($('input.js_employee_job_title').length) {
                                input_values_obj["employee_job_title"] = $('input.js_employee_job_title').val();
                            }
                            if ($('input.js_supervisor').length) {
                                input_values_obj["supervisor"] = $('input.js_supervisor').val();
                            }
                            if ($('input.js_department').length) {
                                input_values_obj["department"] = $('input.js_department').val();
                            }
                            if ($('input.js_last_work_date').length) {
                                input_values_obj["last_work_date"] = $('input.js_last_work_date').val();
                            }
                            if ($('textarea.js_reason_to_leave_company').length) {
                                input_values_obj["reason_to_leave_company"] = $('textarea.js_reason_to_leave_company').val();
                            }
                            if ($('textarea.js_forwarding_information').length) {
                                input_values_obj["forwarding_information"] = $('textarea.js_forwarding_information').val();
                            }
                            if ($('input.js_date_of_occurrence').length) {
                                input_values_obj["date_of_occurrence"] = $('input.js_date_of_occurrence').val();
                            }
                            if ($('textarea.js_summary_of_violation').length) {
                                input_values_obj["summary_of_violation"] = $('textarea.js_summary_of_violation').val();
                            }
                            if ($('textarea.js_summary_of_corrective_plan').length) {
                                input_values_obj["summary_of_corrective_plan"] = $('textarea.js_summary_of_corrective_plan').val();
                            }
                            if ($('textarea.js_follow_up_dates').length) {
                                input_values_obj["follow_up_dates"] = $('textarea.js_follow_up_dates').val();
                            }
                            if ($('[name="counselling_form_fields[]"]').length) {
                                input_values_obj["counselling_form_fields"] = $('[name="counselling_form_fields[]"]:checked').map(function() {
                                    return $(this).val()
                                }).get()

                                if ($.inArray("Other", input_values_obj["counselling_form_fields"]) !== -1) {
                                    input_values_obj["counselling_form_fields_textarea"] = $(".js_counselling_form_fields_textarea").val()
                                }
                            }

                            if ($('input.js_employee_number').length) {
                                input_values_obj["employee_number"] = $('input.js_employee_number').val();
                            }
                            if ($('textarea.js_q1').length) {
                                input_values_obj["q1"] = $('textarea.js_q1').val();
                            }
                            if ($('textarea.js_q2').length) {
                                input_values_obj["q2"] = $('textarea.js_q2').val();
                            }
                            if ($('textarea.js_q3').length) {
                                input_values_obj["q3"] = $('textarea.js_q3').val();
                            }
                            if ($('textarea.js_q4').length) {
                                input_values_obj["q4"] = $('textarea.js_q4').val();
                            }
                            if ($('textarea.js_q5').length) {
                                input_values_obj["q5"] = $('textarea.js_q5').val();
                            }

                            if ($('input.js_employee_name').length) {
                                input_values_obj["employee_name"] = $('input.js_employee_name').val();
                            }
                            if ($('input.js_employee_job_title').length) {
                                input_values_obj["employee_job_title"] = $('input.js_employee_job_title').val();
                            }
                            if ($('input.js_supervisor').length) {
                                input_values_obj["supervisor"] = $('input.js_supervisor').val();
                            }
                            if ($('input.js_last_work_date').length) {
                                input_values_obj["last_work_date"] = $('input.js_last_work_date').val();
                            }
                            if ($('input.js_is_termination_voluntary').length) {
                                input_values_obj["is_termination_voluntary"] = $('input.js_is_termination_voluntary:checked').val();
                            }
                            if ($('input.js_property_returned').length) {
                                input_values_obj["property_returned"] = $('input.js_property_returned:checked').val();
                            }
                            if ($('input.js_reemploying').length) {
                                input_values_obj["reemploying"] = $('input.js_reemploying:checked').val();
                            }

                            $('input.short_textbox').map(function() {
                                input_values_obj[this.name] = this.value;
                            }).get();


                            $('input.long_textbox').map(function() {
                                input_values_obj[this.name] = this.value;
                            }).get();

                            $('input#signature_person_name').map(function() {
                                input_values_obj[this.name] = this.value;
                            }).get();

                            $('input.user_checkbox').map(function() {
                                var condition = 'no';
                                if ($(this).is(":checked")) {
                                    condition = 'yes';
                                }

                                input_values_obj[this.name] = condition;
                            }).get();

                            $('textarea.text_area').map(function() {
                                input_values_obj[this.name] = this.value;
                            }).get();

                            let hasError = false;

                            $('select.js_select_document').map(function() {
                                //
                                if ($(this).prop('required') == true && $(this).val() == null && hasError == false) {
                                    hasError = true;
                                    alertify.alert('WARNING!', 'Please select an option.', () => {});
                                } else if ($(this).prop('multiple') == true) {
                                    var multi_select_values = $(this).val();
                                    input_values_obj[this.name] = multi_select_values == null ? multi_select_values : multi_select_values.toString();
                                } else {
                                    input_values_obj[this.name] = this.value;
                                }
                            }).get();

                            if (hasError) return;

                            $('#save_input_values').val(JSON.stringify(input_values_obj));

                            $('.disabled_consent_btn').prop('disabled', true);
                            $('#save_PDF').val(pdfdata);
                            $('#user_consent_form').submit();
                        });
                },
                function() {
                    alertify.error('Cancelled!');
                }).set('labels', {
                ok: 'Save Document!',
                cancel: 'Cancel'
            });
        }
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

<style>
    #required_fields_div ul,
    #required_fields_div ol {
        padding-left: 20px;
    }
</style>

<?php $this->load->view('iframeLoader'); ?>

<script>
    loadIframe(
        $('iframe.uploaded-file-preview').prop('src'),
        'iframe.uploaded-file-preview',
        true,
        false
    );

    $(document).ready(function() {
        $('.jsTooltip').tooltip({
            placement: "top auto",
            trigger: "hover"
        });
    });
</script>