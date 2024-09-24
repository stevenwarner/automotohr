<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="page-header">
                    <h1 class="section-ttile">Review & Sign Offer Letter</h1>
                    <strong>Information:</strong> If you are unable to view the offer letter, kindly reload the page.
                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div id="print_offer_letter" class="hr-box" style="background: #fff;">
                            <div class="alert alert-info js-splash-hide">
                                <strong><?php echo ucwords($offer_letter['document_title']); ?></strong>
                            </div>
                            <div class="hr-innerpadding">
                                <div class="row">
                                    <div class="col-xs-12" id="required_fields_div">
                                        <?php if ($offer_letter['offer_letter_type'] == 'hybrid_document') { ?>

                                            <?php
                                            $offer_letter_filename = !empty($offer_letter['document_s3_name']) ? $offer_letter['document_s3_name'] : '';
                                            $offer_letter_file = pathinfo($offer_letter_filename);
                                            $offer_letter_extension = strtolower($offer_letter['document_extension']);

                                            //
                                            $t = explode('.', $offer_letter_filename);
                                            $de = $t[sizeof($t) - 1];
                                            //
                                            if ($de != $offer_letter_extension) $offer_letter_extension = $de;
                                            ?>

                                            <?php if (in_array($offer_letter_extension, ['csv'])) { ?>
                                                <iframe src="<?php echo 'https://docs.google.com/gview?url=' . AWS_S3_BUCKET_URL . $offer_letter_filename . '&embedded=true'; ?>" class="uploaded-file-preview js-hybrid-iframe" style="width:100%; height:80em;" frameborder="0"></iframe>

                                            <?php } else if (in_array($offer_letter_extension, ['jpe', 'jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg'])) { ?>
                                                <img class="img-responsive js-hybrid-iframe" src="<?php echo AWS_S3_BUCKET_URL . $offer_letter_filename; ?>" />
                                            <?php } else if (in_array($offer_letter_extension, ['doc', 'docx', 'xlsx', 'xlx', 'pptx', 'ppt'])) { ?>
                                                <iframe src="<?php echo 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode(AWS_S3_BUCKET_URL . $offer_letter_filename); ?>" class="uploaded-file-preview js-hybrid-iframe" style="width:100%; height:80em;" frameborder="0"></iframe>
                                            <?php } else { ?>
                                                <iframe src="<?php echo 'https://docs.google.com/gview?url=' . (AWS_S3_BUCKET_URL . $offer_letter_filename); ?>&embedded=true" class="uploaded-file-preview js-hybrid-iframe" style="width:100%; height:80em;" frameborder="0"></iframe>
                                            <?php } ?>

                                            <div class="alert alert-info js-hybrid-iframe">
                                                <strong>Description</strong>
                                            </div>

                                            <?php $consent = isset($offer_letter['user_consent']) ? $offer_letter['user_consent'] : 0; ?>

                                            <?php if ($consent == 0 || ($consent == 1 && !empty($offer_letter['form_input_data']))) { ?>
                                                <?php echo html_entity_decode($offer_letter['document_description']); ?>
                                            <?php } elseif ($consent == 1) { ?>
                                                <iframe src="<?php echo $offer_letter['submitted_description']; ?>" name="printf" class="uploaded-file-preview" style="width:100%; height:80em;" frameborder="0"></iframe>
                                            <?php } ?>
                                        <?php } else if ($offer_letter['offer_letter_type'] == 'uploaded') { ?>
                                            <?php
                                            $print_url = '';
                                            $download_url = '';
                                            $offer_letter_iframe_url  = '';
                                            $offer_letter_url         = $offer_letter['document_s3_name'];
                                            $offer_letter_file_info   = explode(".", $offer_letter_url);
                                            $offer_letter_name        = $offer_letter_file_info[0];
                                            $offer_letter_extension   = $offer_letter_file_info[1];

                                            if (in_array($offer_letter_extension, ['pdf', 'csv'])) {
                                                $offer_letter_iframe_url = 'https://docs.google.com/gview?url=' . AWS_S3_BUCKET_URL . $offer_letter_url . '&embedded=true';
                                                $print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $offer_letter_name . '.pdf';
                                            } else if (in_array($offer_letter_extension, ['doc', 'docx'])) {
                                                $offer_letter_iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode(AWS_S3_BUCKET_URL . $offer_letter_url);
                                                if ($offer_letter_extension == 'doc') {
                                                    $print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $offer_letter_name . '%2Edoc&wdAccPdf=0';
                                                } else {
                                                    $print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $offer_letter_name . '%2Edocx&wdAccPdf=0';
                                                }
                                            } else {
                                                $iframe_url = 'https://docs.google.com/gview?url=' . AWS_S3_BUCKET_URL . $resume_url . '&embedded=true';
                                                $print_url = '';
                                            }

                                            $download_url = base_url('hr_documents_management/download_upload_document' . '/' . $offer_letter_url);
                                            ?>
                                            <iframe src="<?php echo $offer_letter_iframe_url; ?>" id="printf" name="printf" class="uploaded-file-preview" style="width:100%; height:80em;" frameborder="0"></iframe>
                                        <?php } else { ?>
                                            <?php
                                            $consent = isset($offer_letter['user_consent']) ? $offer_letter['user_consent'] : 0;
                                            if ($consent == 0) {
                                                echo html_entity_decode($offer_letter['document_description']);
                                            } elseif ($consent == 1) { ?>
                                                <iframe src="<?php echo $offer_letter['submitted_description']; ?>" id="printf" name="printf" class="uploaded-file-preview" style="width:100%; height:80em;" frameborder="0"></iframe>
                                        <?php }
                                        } ?>

                                        <?php if ((!empty($document_info) && $document_info['signature_required'] == 1) || $save_offer_letter_type == 'consent_only') { ?>
                                            <form id="user_consent_form" enctype="multipart/form-data" method="post" action="">
                                                <input type="hidden" name="perform_action" value="sign_document" />
                                                <input type="hidden" name="page_content" value="">
                                                <input type="hidden" name="save_signature" id="save_signature" value="yes">
                                                <input type="hidden" name="save_signature_initial" id="save_signature_initial" value="yes">
                                                <input type="hidden" name="save_signature_date" id="save_signature_date" value="yes">
                                                <input type="hidden" name="save_PDF" id="save_PDF" value="yes">
                                                <input type="hidden" name="save_input_values" id="save_input_values" value="yes">
                                                <?php $consent = isset($offer_letter['user_consent']) ? $offer_letter['user_consent'] : 0;
                                                if ($consent == 0) { ?>
                                                    <div class="row">
                                                        <div class="col-xs-12 text-justify">
                                                            <p>
                                                                <b>CONSENT AND NOTICE REGARDING ELECTRONIC COMMUNICATIONS</b> FOR <b>AutomotoSocial LLC / <?php echo STORE_NAME; ?></b><br />
                                                            </p>
                                                            <p>1. Electronic Signature Agreement.</p>
                                                            <p>
                                                                By selecting the "I Accept" button, you are signing this Agreement electronically. You agree your electronic signature is the legal equivalent of your manual signature on this Agreement. By selecting "I Accept" you consent to be legally bound by this Agreement's terms and conditions.
                                                                You further agree that your use of a key pad, mouse or other device to select an item, button, icon or similar act/action, or to otherwise
                                                                provide AutomotoSocial LLC / <?php echo STORE_NAME; ?>, or in accessing or making any transaction regarding any agreement, acknowledgement, consent terms, disclosures or conditions constitutes your signature (hereafter referred to as "E-Signature"), acceptance and agreement as if actually signed by
                                                                you in writing.
                                                                You also agree that no certification authority or other third party verification
                                                                is necessary to validate your E-Signature and that the lack of such certification or third party verification will not in any way affect the enforceability of your E-Signature or any resulting contract between you and AutomotoSocial LLC / <?php echo STORE_NAME; ?>. You also represent that
                                                                you are authorized to enter into this Agreement for all persons who own or are authorized to access any of your accounts and that such persons will be bound by the terms of this Agreement.
                                                                You further agree that each use of your E-Signature in obtaining a AutomotoSocial LLC / <?php echo STORE_NAME; ?> service constitutes your agreement to be bound by the terms and conditions of the AutomotoSocial LLC / <?php echo STORE_NAME; ?> Disclosures and Agreements as they exist on the date of your
                                                                E-Signature
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <?php $consent = isset($offer_letter['user_consent']) ? $offer_letter['user_consent'] : 0; ?>
                                                            <label class="control control--checkbox">
                                                                I Consent and Accept Electronic Signature Agreement
                                                                <input <?php echo set_checkbox('user_consent', 1, $consent == 1); ?> data-rule-required="true" type="checkbox" id="user_consent" name="user_consent" value="1" <?php echo $consent == 1 ? 'checked="checked"' : ''; ?> />
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                <?php if ($offer_letter['user_consent'] == 0) { ?>
                                                    <div class="row">
                                                        <div class="col-lg-12 text-center">
                                                            <button <?php echo $offer_letter['user_consent'] == 1 ? 'disabled="disabled"' : ''; ?> onclick="func_save_e_signature();" type="button" class="btn blue-button break-word-text disabled_consent_btn" <?php echo $consent == 1 ? 'disabled="disabled"' : ''; ?>>I Consent and Accept Electronic Signature Agreement</button>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </form>
                                        <?php } else if ($save_offer_letter_type == 'save_only') { ?>
                                            <form id="user_consent_form" enctype="multipart/form-data" method="post" action="">
                                                <input type="hidden" name="perform_action" value="sign_document" />
                                                <input type="hidden" name="page_content" value="">
                                                <input type="hidden" name="save_signature" id="save_signature" value="no">
                                                <input type="hidden" name="save_signature_initial" id="save_signature_initial" value="no">
                                                <input type="hidden" name="save_signature_date" id="save_signature_date" value="no">
                                                <input type="hidden" name="save_PDF" id="save_PDF" value="yes">
                                                <input type="hidden" name="save_input_values" id="save_input_values" value="yes">
                                                <input type="hidden" name="user_consent" value="1">
                                                <?php $consent = isset($offer_letter['user_consent']) ? $offer_letter['user_consent'] : 0;
                                                if ($consent == 0) { ?>
                                                    <div class="row">
                                                        <div class="col-lg-12 text-center">
                                                            <button onclick="func_save_offer_letter();" type="button" class="btn blue-button break-word-text disabled_consent_btn">Save Offer Letter</button>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </form>
                                        <?php } ?>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if (!empty($document_info) && ($document_info['acknowledgment_required'] == 1 || $document_info['download_required'] == 1 || $document_info['signature_required'] == 1)) { ?>
                            <div class="row">
                                <div class="col-xs-12">
                                    <?php if ($document_info['acknowledgment_required'] == 1) { ?>
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
                                                        <input type="hidden" id="user_type" name="user_type" value="<?php echo $offer_letter['user_type']; ?>" />
                                                        <input type="hidden" id="user_sid" name="user_sid" value="<?php echo $offer_letter['user_sid']; ?>" />
                                                        <input type="hidden" id="document_sid" name="document_sid" value="<?php echo $offer_letter['sid']; ?>" />
                                                    </form>
                                                    <?php if ($offer_letter['acknowledged_date'] != NULL) {
                                                        echo '<b>Acknowledged On: </b>';
                                                        echo convert_date_to_frontend_format($offer_letter['acknowledged_date']);
                                                    } ?>
                                                    <button onclick="<?php echo $acknowledgement_button_action; ?>" type="button" class="btn <?php echo $acknowledgement_button_css; ?> pull-right"><?php echo $acknowledgement_button_txt; ?></button>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <?php if (!empty($document_info) && $document_info['download_required'] == 1) { ?>
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
                                                    <?php if ($offer_letter['downloaded_date'] != NULL) {
                                                        echo '<b>Downloaded On: </b>';
                                                        echo convert_date_to_frontend_format($offer_letter['downloaded_date']);
                                                    } ?>

                                                    <?php if ($offer_letter['offer_letter_type'] == 'generated') { ?>
                                                        <a target="_blank" href="<?php echo $download_button_action; ?>" id="download_btn_click" class="btn <?php echo $download_button_css; ?> pull-right" onclick="save_print()">
                                                            <?php echo $download_button_txt; ?>
                                                        </a>
                                                        <a target="_blank" href="<?php echo $print_button_action; ?>" class="btn pull-right <?php echo $download_button_css; ?>" style="margin-right: 10px;" id="print_btn_click">
                                                            print Document
                                                        </a>
                                                    <?php } else { ?>
                                                        <a target="_blank" href="<?php echo $download_url; ?>" id="download_btn_click" class="btn <?php echo $download_button_css; ?> pull-right">
                                                            <?php echo $download_button_txt; ?>
                                                        </a>
                                                        <a target="_blank" href="<?php echo $print_url; ?>" class="btn pull-right <?php echo $download_button_css; ?>" style="margin-right: 10px;" id="print_btn_click">
                                                            print Document
                                                        </a>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <?php if ($offer_letter['offer_letter_type'] == 'uploaded' && $document_info['signature_required'] == 1) { ?>
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
                                                    <?php if ($offer_letter['uploaded_date'] != NULL) {
                                                        echo '<b>Uploaded On: </b>';
                                                        echo convert_date_to_frontend_format($offer_letter['uploaded_date']);
                                                    } ?>

                                                    <form id="form_upload_file" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                        <input type="hidden" id="perform_action" name="perform_action" value="upload_document" />
                                                        <input type="hidden" id="user_type" name="user_type" value="<?php echo $offer_letter['user_type']; ?>" />
                                                        <input type="hidden" id="user_sid" name="user_sid" value="<?php echo $offer_letter['user_sid']; ?>" />
                                                        <input type="hidden" id="document_sid" name="document_sid" value="<?php echo $offer_letter['document_sid']; ?>" />
                                                        <input type="hidden" id="document_type" name="document_type" value="<?php echo $offer_letter['document_type']; ?>" />
                                                        <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $offer_letter['company_sid']; ?>" />

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
                                                                <?php if (!empty($offer_letter['uploaded_file'])) { ?>
                                                                    <?php
                                                                    $upload_offer_letter_iframe_url  = '';
                                                                    $upload_offer_letter_url         = $offer_letter['uploaded_file'];
                                                                    $upload_offer_letter_file_info   = explode(".", $upload_offer_letter_url);
                                                                    $upload_offer_letter_name        = $upload_offer_letter_file_info[0];
                                                                    $upload_offer_letter_extension   = $upload_offer_letter_file_info[1];

                                                                    if (in_array($upload_offer_letter_extension, ['pdf', 'csv'])) {
                                                                        $upload_offer_letter_iframe_url = 'https://docs.google.com/gview?url=' . AWS_S3_BUCKET_URL . $upload_offer_letter_url . '&embedded=true';
                                                                    } else if (in_array($upload_offer_letter_extension, ['doc', 'docx'])) {
                                                                        $upload_offer_letter_iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode(AWS_S3_BUCKET_URL . $upload_offer_letter_url);
                                                                    }
                                                                    ?>
                                                                    <a href="javascript:;" class="btn blue-button btn-equalizer btn-block" onclick="show_uploaded_offer_letter(this);" data-preview-url="<?php echo $upload_offer_letter_iframe_url; ?>" data-file-name="<?php echo 'Uploaded Signed Offer Letter / Pay Plan'; ?>">Preview Uploaded
                                                                    </a>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
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

<style>
    #required_fields_div ol,
    #required_fields_div ul {
        padding-left: 15px !important;
    }
</style>
<script src="<?php echo base_url('assets/employee_panel/js/kendoUI.min.js'); ?>"></script>
<script>
    $(document).ready(function() {

        //
        if ($('#required_fields_div').find('select').length >= 0) {
            $('#required_fields_div').find('select').map(function(i) {
                //
                $(this).addClass('js_select_document');
                $(this).prop('name', 'selectDD' + i);
            });
        }

        $('.date_picker2').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>"
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
                alertify.error('Cancelled!');
            });
    }

    function save_print() {
        var company_sid = '<?php echo $offer_letter['company_sid']; ?>';
        var user_sid = '<?php echo $offer_letter['user_sid']; ?>';
        var user_type = '<?php echo $offer_letter['user_type']; ?>';
        var offer_letter_sid = '<?php echo $offer_letter['sid']; ?>';
        var myurl = "<?= base_url() ?>onboarding/downloaded_generated_doc/" + user_sid + "/" + company_sid + "/" + offer_letter_sid + "/" + user_type;

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

        var draw = kendo.drawing;
        draw.drawDOM($("#print_offer_letter"), {
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
            .done(function(data) {
                var consent = '<?php echo isset($offer_letter['user_consent']) ? $offer_letter['user_consent'] : 0; ?>';
                var pdfdata = "";
                if (consent == 0) {
                    pdfdata = data;
                } else {
                    pdfdata = '<?php echo $offer_letter['submitted_description']; ?>';
                }

                kendo.saveAs({
                    dataURI: pdfdata,
                    fileName: '<?php echo ucwords($offer_letter['document_title']) . ".pdf"; ?>',
                });
            });
    }

    $('#print_btn_click').click(function() {
        var company_sid = '<?php echo $offer_letter['company_sid']; ?>';
        var user_sid = '<?php echo $offer_letter['user_sid']; ?>';
        var user_type = '<?php echo $offer_letter['user_type']; ?>';
        var offer_letter_sid = '<?php echo $offer_letter['sid']; ?>';
        var myurl = "<?= base_url() ?>onboarding/downloaded_generated_doc/" + user_sid + "/" + company_sid + "/" + offer_letter_sid + "/" + user_type;

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

            if (is_sign == 'true' && is_init == 'true' && is_date == 'true') {
                var input_values_obj = {};

                //
                let dataRequiredFlag = false;
                $(".validation").remove();

                $('input.short_textbox').map(function() {
                    if ($(this).data("required") == "yes" && !this.value.length) {
                        $(this).parent().after("<div class='validation' style='color:red;margin-bottom: 20px;'>This data field is required!</div>");
                        dataRequiredFlag = true;
                    }
                    //
                    input_values_obj[this.name] = this.value;
                }).get();


                $('input.long_textbox').map(function() {
                    if ($(this).data("required") == "yes" && !this.value.length) {
                        $(this).parent().after("<div class='validation' style='color:red;margin-bottom: 20px;'>This data field is required!</div>");
                        dataRequiredFlag = true;
                    }
                    //
                    input_values_obj[this.name] = this.value;
                }).get();

                $('input#signature_person_name').map(function() {
                    input_values_obj[this.name] = this.value;
                }).get();

                $('.jsCheckbox').map(function() {
                    //
                    if ($(this).data("required") == "yes") {
                        let name = $(this).data("name");
                        //
                        if (!$('input[name="'+name+'1"]:checked').length && !$('input[name="'+name+'2"]:checked').length) {
                            $(this).after("<div class='validation' style='color:red;margin-bottom: 20px;'>These check-box field is required!</div>");
                            dataRequiredFlag = true;
                        } else if ($('input[name="'+name+'1"]:checked').length && $('input[name="'+name+'2"]:checked').length) {
                            $(this).after("<div class='validation' style='color:red;margin-bottom: 20px;'>Please select only one check-box!</div>");
                            dataRequiredFlag = true;
                        } else if ($('input[name="'+name+'1"]:checked').length) {
                            input_values_obj[name] = 'yes';
                        } else if ($('input[name="'+name+'2"]:checked').length) {
                            input_values_obj[name] = 'no';
                        }
                    } else if ($(this).data("required") == "no") {
                        var condition = 'no';
                        if ($(this).is(":checked")) {
                            condition = 'yes';
                        }

                        input_values_obj[this.name] = condition;
                    }
                    
                }).get();

                $('textarea.text_area').map(function() {
                    if ($(this).data("required") == "yes" && !this.value.length) {
                        $(this).parent().after("<div class='validation' style='color:red;margin-bottom: 20px;'>This data field is required!</div>");
                        dataRequiredFlag = true;
                    }
                    //
                    input_values_obj[this.name] = this.value;
                }).get();

                if (dataRequiredFlag) {
                    alertify.alert('WARNING!', 'Please provided the required data to save the document.');
                    $("html, body").animate({
                        scrollTop: $(".validation").offset().top
                    });
                    return;
                } 


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
                        $('.js-splash-hide, .js-hybrid-iframe').remove();
                        var draw = kendo.drawing;

                        draw.drawDOM($("#print_offer_letter"), {
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
                                $('.disabled_consent_btn').prop('disabled', true);
                                $('#save_PDF').val(pdfdata);
                                $('#user_consent_form').submit();
                            });
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

    function func_save_offer_letter() {
        if ($('#user_consent_form').valid()) {
            alertify.confirm(
                'Are you Sure?',
                'Are you sure you want to Save this Offer Letter?',
                function() {
                    var draw = kendo.drawing;

                    draw.drawDOM($("#print_offer_letter"), {
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

                            //
                            let dataRequiredFlag = false;
                            $(".validation").remove();

                            $('input.short_textbox').map(function() {
                                if ($(this).data("required") == "yes" && !this.value.length) {
                                    $(this).parent().after("<div class='validation' style='color:red;margin-bottom: 20px;'>This data field is required!</div>");
                                    dataRequiredFlag = true;
                                }
                                //
                                input_values_obj[this.name] = this.value;
                            }).get();


                            $('input.long_textbox').map(function() {
                                if ($(this).data("required") == "yes" && !this.value.length) {
                                    $(this).parent().after("<div class='validation' style='color:red;margin-bottom: 20px;'>This data field is required!</div>");
                                    dataRequiredFlag = true;
                                }
                                //
                                input_values_obj[this.name] = this.value;
                            }).get();

                            $('input#signature_person_name').map(function() {
                                input_values_obj[this.name] = this.value;
                            }).get();

                            $('.jsCheckbox').map(function() {
                                //
                                if ($(this).data("required") == "yes") {
                                    let name = $(this).data("name");
                                    //
                                    if (!$('input[name="'+name+'1"]:checked').length && !$('input[name="'+name+'2"]:checked').length) {
                                        $(this).after("<div class='validation' style='color:red;margin-bottom: 20px;'>These check-box field is required!</div>");
                                        dataRequiredFlag = true;
                                    } else if ($('input[name="'+name+'1"]:checked').length && $('input[name="'+name+'2"]:checked').length) {
                                        $(this).after("<div class='validation' style='color:red;margin-bottom: 20px;'>Please select only one check-box!</div>");
                                        dataRequiredFlag = true;
                                    } else if ($('input[name="'+name+'1"]:checked').length) {
                                        input_values_obj[name] = 'yes';
                                    } else if ($('input[name="'+name+'2"]:checked').length) {
                                        input_values_obj[name] = 'no';
                                    }
                                } else if ($(this).data("required") == "no") {
                                    var condition = 'no';
                                    if ($(this).is(":checked")) {
                                        condition = 'yes';
                                    }

                                    input_values_obj[this.name] = condition;
                                }
                                
                            }).get();

                            $('textarea.text_area').map(function() {
                                if ($(this).data("required") == "yes" && !this.value.length) {
                                    $(this).parent().after("<div class='validation' style='color:red;margin-bottom: 20px;'>This data field is required!</div>");
                                    dataRequiredFlag = true;
                                }
                                //
                                input_values_obj[this.name] = this.value;
                            }).get();

                            if (dataRequiredFlag) {
                                alertify.alert('WARNING!', 'Please provided the required data to save the document.');
                                $("html, body").animate({
                                    scrollTop: $(".validation").offset().top
                                });
                                return;
                            } 


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
                ok: 'Save Offer Letter!',
                cancel: 'Cancel'
            });
        }
    }

    function show_uploaded_offer_letter(source) {
        var offer_letter_preview_url = $(source).attr('data-preview-url');
        var offer_letter_name = $(source).attr('data-file-name');

        $('#show_uploaded_offer_letter_modal').modal('show');
        $("#offer_letter_modal_title").html(offer_letter_name);

        $('#offer-letter-iframe-container').show();
        var offer_letter_content = $("<iframe />")
            .attr("id", "offer_letter_iframe")
            .attr("class", "uploaded-file-preview")
            .attr("src", offer_letter_preview_url);

        $("#offer-letter-iframe-holder").append(offer_letter_content);
    }

    $('#show_uploaded_offer_letter_modal').on('hidden.bs.modal', function() {
        $("#offer_letter_iframe").remove();
        $('#offer-letter-iframe-container').hide();
    });
</script>
<!-- Preview Offer Letter Modal Start -->
<div id="show_uploaded_offer_letter_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="offer_letter_modal_title"></h4>
            </div>
            <div class="modal-body">
                <div class="embed-responsive embed-responsive-4by3">
                    <div id="offer-letter-iframe-container" style="display:none;">
                        <div id="offer-letter-iframe-holder" class="embed-responsive-item">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" id="resume_modal_footer">

            </div>
        </div>
    </div>
</div>
<!-- Preview Offer Letter Modal End -->

<?php $this->load->view('hr_documents_management/hybrid/scripts') ?>

<?php if ($offer_letter['offer_letter_type'] == 'uploaded' || $offer_letter['offer_letter_type'] == 'hybrid_document') { ?>
    <?php $this->load->view('iframeLoader') ?>
    <script>
        loadIframe('<?= $offer_letter_iframe_url; ?>', '.uploaded-file-preview');
    </script>
<?php } ?>