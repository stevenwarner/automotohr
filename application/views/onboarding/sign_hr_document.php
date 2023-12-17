<?php $company_name = ucwords($session['company_detail']['CompanyName']); ?>
<?php if ($document['fillable_documents_slug'] == 'written-employee-counseling-report-form' || $document['fillable_documents_slug'] == 'notice-of-separation') {
    $save_offer_letter_type = 'consent_only';
}
?>
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


                                        <?php } else if ($document['fillable_documents_slug'] != null && $document['fillable_documents_slug'] != '') { ?>
                                            <div class="img-thumbnail text-center" style="width: 100%; max-height: 100%;">
                                                <?php
                                                $document_filename = !empty($document['fillable_documents_slug']) ? $document['fillable_documents_slug'] : '';
                                                $document_file = pathinfo($document_filename);
                                                $document_extension = strtolower($document['document_extension']);
                                                //
                                                $doc = str_replace('-', '_', $document['fillable_documents_slug']);
                                                ?>

                                                <?php $this->load->view('v1/fillable_documents/' . $doc, $document); ?>
                                                <br>
                                            </div>


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


                                                        <?php if ($document['fillable_documents_slug'] != null &&  $document['fillable_documents_slug'] != '') { ?>
                                                            <a target="_blank" href="<?= base_url('v1/fillable_documents/downloadFillable/' . $document['fillable_documents_slug'] . '/' . $document['sid'] . '/original/download') ?>" id="download_btn_click" class="btn <?php echo $download_button_css; ?> pull-right">
                                                                <?php echo $download_button_txt; ?>
                                                            </a>
                                                            <a target="_blank" href="<?= base_url('v1/fillable_documents/downloadFillable/' . $document['fillable_documents_slug'] . '/' . $document['sid'] . '/original/print') ?>" class="btn pull-right <?php echo $download_button_css; ?>" style="margin-right: 10px;" id="print_btn_click">
                                                                print Document
                                                            </a>
                                                        <?php } else { ?>
                                                            <a target="_blank" href="<?php echo $download_button_action; ?>" id="download_btn_click" class="btn <?php echo $download_button_css; ?> pull-right" onclick="save_print()">
                                                                <?php echo $download_button_txt; ?>
                                                            </a>
                                                            <a target="_blank" href="<?php echo $print_button_action; ?>" class="btn pull-right <?php echo $download_button_css; ?>" style="margin-right: 10px;" id="print_btn_click">
                                                                print Document
                                                            </a>

                                                        <?php } ?>

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
                if (input_value[0] == 'signature_person_name') {
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

            <?php if ($document['fillable_documents_slug'] == 'written-employee-counseling-report-form') { ?>
                if (writtenEmployeeCounselingReportFormValidation() == true) {
                    retrun;
                }
            <?php } ?>

            <?php if ($document['fillable_documents_slug'] == 'notice-of-separation') { ?>
                if (noticeOfSeparation() == true) {
                    retrun;
                }
            <?php } ?>


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

            if (is_sign == 'true' && is_init == 'true' && is_date == 'true') {
                var input_values_obj = {};

                $('input.short_textbox').map(function() {
                    input_values_obj[this.name] = this.value;
                }).get();


                $('input.long_textbox').map(function() {
                    input_values_obj[this.name] = this.value;
                }).get();

                $('textarea.long_textbox').map(function() {
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



            <?php if ($document['fillable_documents_slug'] == 'payroll-status-change-form') { ?>
                if (payrollStatusChangeFormValidation() == true) {
                    retrun;
                }
            <?php }
            if ($document['fillable_documents_slug'] == 'oral-employee-counseling-report-form') { ?>
                if (oralEmployeeCounselingReportFormValidation() == true) {
                    retrun;
                }
            <?php }  ?>
            <?php if ($document['fillable_documents_slug'] == 'notice-of-termination-of-employment') { ?>
                if (noticeOfTerminationOfEmployment() == true) {
                    retrun;
                }
            <?php } ?>

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

                            $('input.short_textbox').map(function() {
                                input_values_obj[this.name] = this.value;
                            }).get();


                            $('input.long_textbox').map(function() {
                                input_values_obj[this.name] = this.value;
                            }).get();

                            $('textarea.long_textbox').map(function() {
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


    //

    function payrollStatusChangeFormValidation() {

        var validationError = false;

        var regex = /^\d+(?:\.\d{0,2})$/;
        var rateFrom = $('#short_textbox_4_id').val();
        var rateTo = $('#short_textbox_5_id').val();

        //
        if ($('#short_textbox_0_id').val() == '') {
            alertify.error('Please provide Employee Name ');
            validationError = true;
        }

        //
        if ($('#short_textbox_1_id').val() == '') {
            alertify.error('Please provide Effective Date');
            validationError = true;
        }
        //
        if ($('#short_textbox_2_id').val() == '') {
            alertify.error('Please provide Department');
            validationError = true;
        }
        //
        if ($('#short_textbox_3_id').val() == '') {
            alertify.error('Please provide Supervisor');
            validationError = true;
        }
        //
        if (isNaN(rateFrom)) {
            alertify.error('Rate From is not valid');
            validationError = true;
        } else if (rateFrom == '') {
            alertify.error('Please provide Rate From');

        }

        if (isNaN(rateTo)) {
            alertify.error('Rate To is not valid');
            validationError = true;
        } else if (rateTo == '') {
            alertify.error('Please provide Rate To');

        }

        //
        if ($('#short_textbox_6_id').val() == '') {
            alertify.error('Please provide Job');
            validationError = true;
        }
        //
        if ($('#short_textbox_7_id').val() == '') {
            alertify.error('Please provide Department');
            validationError = true;
        }
        if ($('#short_textbox_8_id').val() == '') {
            alertify.error('Please provide Location');
            validationError = true;
        }

        if ($('#short_textbox_9_id').val() == '') {
            alertify.error('Please provide Shift');
            validationError = true;
        }

        return validationError;

    }


    function oralEmployeeCounselingReportFormValidation() {


        var validationError = false;

        //
        if ($('#short_textbox_0_id').val() == '') {
            alertify.error('Please Provide Employee Name ');
            validationError = true;
        }
        //
        if ($('#short_textbox_1_id').val() == '') {
            alertify.error('Please Provide Department');
            validationError = true;
        }
        //
        if ($('#short_textbox_2_id').val() == '') {
            alertify.error('Please Provide Date of Occurrence');
            validationError = true;
        }
        //
        if ($('#short_textbox_3_id').val() == '') {
            alertify.error('Please Provide Supervisor');
            validationError = true;
        }

        //
        if ($('#long_textbox_0_id').val() == '') {
            alertify.error('Please Provide Summary of Violation');
            validationError = true;
        }
        //
        if ($('#long_textbox_1_id').val() == '') {
            alertify.error('Please provide Summary of Corrective Plan');
            validationError = true;
        }
        //
        if ($('#short_textbox_4_id').val() == '') {
            alertify.error('Please Provide  Follow Up Dates: ');
            validationError = true;
        }

        return validationError;
    }


    function noticeOfTerminationOfEmployment() {

        var validationError = false;

        //
        if ($('#short_textbox_0_id').val() == '') {
            alertify.error('Please Provide Employee Name ');
            validationError = true;
        }

        //
        if ($('#short_textbox_1_id').val() == '') {
            alertify.error('Please Provide Job Title');
            validationError = true;
        }
        //
        if ($('#short_textbox_2_id').val() == '') {
            alertify.error('Please Provide Supervisor');
            validationError = true;
        }
        //
        if ($('#short_textbox_3_id').val() == '') {
            alertify.error('Please Provide Last Day of Work:');
            validationError = true;
        }

        return validationError;

    }


    function writtenEmployeeCounselingReportFormValidation() {

        var validationError = false;

        //
        if ($('#short_textbox_0_id').val() == '') {
            alertify.error('Please Provide Employee Name ');
            validationError = true;
        }

        //
        if ($('#short_textbox_1_id').val() == '') {
            alertify.error('Please Provide Employee Number:');
            validationError = true;
        }

        if ($('#short_textbox_2_id').val() == '') {
            alertify.error('Please Provide Job title:');
            validationError = true;
        }

        if ($('#short_textbox_3_id').val() == '') {
            alertify.error('Please Provide Department:');
            validationError = true;
        }
        if ($('#short_textbox_4_id').val() == '') {
            alertify.error('Please Provide Location:');
            validationError = true;
        }
        //
        if ($('#short_textbox_5_id').val() == '') {
            alertify.error('Please Provide Supervisor');
            validationError = true;
        }
        //
        if ($('#long_textbox_0_id').val() == '') {
            alertify.error('Please Provide Description of Problem:');
            validationError = true;
        }
        if ($('#long_textbox_1_id').val() == '') {
            alertify.error('Please Provide Description of performance:');
            validationError = true;
        }
        if ($('#long_textbox_2_id').val() == '') {
            alertify.error('Please Provide Description of consequences:');
            validationError = true;
        }
        if ($('#long_textbox_3_id').val() == '') {
            alertify.error('Please Provide Dates and descriptions of prior discissions:');
            validationError = true;
        }
        if ($('#short_textbox_6_id').val() == '') {
            alertify.error('Please Provide Date');
            validationError = true;
        }

        return validationError;

    }


    function noticeOfSeparation() {

        var validationError = false;

        //
        if ($('#short_textbox_0_id').val() == '') {
            alertify.error('Please Provide Your Name ');
            validationError = true;
        }

        //
        if ($('#short_textbox_1_id').val() == '') {

            alertify.error('Please Provide Your Supervisor');
            validationError = true;
        }
        //
        if ($('#short_textbox_2_id').val() == '') {
            alertify.error('Please Provide Your Department');
            validationError = true;
        }
        //
        if ($('#short_textbox_3_id').val() == '') {
            alertify.error('Please Provide Job Title');
            validationError = true;
        }
        if ($('#short_textbox_4_id').val() == '') {
            alertify.error('Please Provide Last day of work:');
            validationError = true;
        }


        if ($('#long_textbox_0_id').val() == '') {
            alertify.error('Please Provide Please fully explain the reasons you are leaving the company:');
            validationError = true;
        }
        if ($('#long_textbox_1_id').val() == '') {
            alertify.error('Please Provide Please Forwarding information: Please include your full address:');
            validationError = true;
        }

        if ($('#short_textbox_5_id').val() == '') {
            alertify.error('Please Employee Printed Name:');
            validationError = true;
        }

        if ($('#short_textbox_6_id').val() == '') {
            alertify.error('Please Employee Date:');
            validationError = true;
        }

        return validationError;

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