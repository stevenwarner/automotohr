<!-- Main Start -->
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('main/employer_column_left_view'); ?>
            </div>  
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?><?php echo $title; ?></span>
                    </div>
                    <div class="create-job-wrap">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                <form enctype="multipart/form-data" method="POST" action="" id="document_form">
                                    <div class="universal-form-style-v2">
                                        <div class="upload-new-doc-heading">
                                            <i class="fa fa-file-text-o"></i>
                                            <?php echo $heading; ?>
                                        </div>
                                        <p class="upload-file-type">Upload a .pdf, .doc or .docx to distribute to your employees.</p>
                                        <ul>
                                            <li class="form-col-100 autoheight">
                                                <label>Document Name<span class="staric">*</span></label>
                                                <input type="text" name="document_name"  value="<?php
                                                if (isset($document['document_original_name'])) {
                                                    echo set_value('document_name', $document['document_original_name']);
                                                } else {
                                                    echo set_value('document_name');
                                                }
                                                ?>" class="invoice-fields">
                                                       <?php echo form_error('document_name'); ?>
                                            </li>
                                            <li class="form-col-100 autoheight">
                                                <label>Upload<?php if ($page == 'add') { ?> <span class="staric">*</span><?php } ?></label>
                                                <div class="upload-file invoice-fields">
                                                    <?php if (isset($document['document_name']) && $document['document_name'] != "") { ?>
                                                        <input type="hidden" name="old_file_name" value="<?php echo $document['document_name']; ?>" >
                                                        <input type="hidden" name="old_file_type" value="<?php echo $document['document_type']; ?>" >

                                                        <div id="remove_image" class="profile-picture">
                                                            <a  href="javascript:;" data-toggle="modal" data-target="#document_modal" class = "action-btn">
                                                                <i class = "fa fa-lightbulb-o fa-2x"></i>
                                                                <span class = "btn-tooltip">View Current Document</span>
                                                            </a>
                                                        </div>
                                                    <?php } ?>
                                                    <input type="file" name="document" id="document" <?php if ($page == 'add') { ?>required<?php } ?> onchange="check_file('document')">
                                                    <p id="name_document"></p>
                                                    <a href="javascript:;">Choose File</a>
                                                </div>
                                            </li>
                                            <div class="description-editor">
                                                <label>Document Description</label>
                                                <textarea class="invoice-fields-textarea" maxlength="128"  name="document_description" onkeyup="check_length()" id="document_description" cols="54" rows="6"><?php
                                                    if (isset($document['document_description'])) {
                                                        echo set_value('document_description', $document['document_description']);
                                                    } else {
                                                        echo set_value('document_description');
                                                    }
                                                    ?></textarea>
                                                <p id="remaining_text" class="info">128 characters left!</p>
                                                <?php echo form_error('document_description'); ?>
                                            </div>
                                            <li class="form-col-100 autoheight">
                                                <label>Include in Onboarding<span class="staric">*</span></label>
                                                <div class="hr-select-dropdown">
                                                    <select class="invoice-fields" name="onboarding">
                                                        <option <?php
                                                        if (isset($document['onboarding'])) {
                                                            if (set_value('onboarding', $document['onboarding']) == '0') {
                                                                ?>
                                                                    selected
                                                                    <?php
                                                                }
                                                            } else {
                                                                if (set_value('onboarding') == '0') {
                                                                    ?>
                                                                    selected
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                            value="0">No</option>    
                                                        <option 
                                                        <?php
                                                        if (isset($document['onboarding'])) {
                                                            if (set_value('onboarding', $document['onboarding']) == '1') {
                                                                ?>
                                                                    selected
                                                                    <?php
                                                                }
                                                            } else {
                                                                if (set_value('onboarding') == '1') {
                                                                    ?>
                                                                    selected
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                            value="1">Yes</option>   
                                                    </select>
                                                </div>
                                                <div class="help-text">
                                                    This doc will be available to select/send to new hires as part of the onboarding wizard.
                                                </div>
                                            </li>
                                            <li class="form-col-100 autoheight">
                                                <label>Action Required<span class="staric">*</span></label>
                                                <div class="hr-select-dropdown">
                                                    <select class="invoice-fields" name="action_required">
                                                        <option
                                                        <?php
                                                        if (isset($document['action_required'])) {
                                                            if (set_value('action_required', $document['action_required']) == '0') {
                                                                ?>
                                                                    selected
                                                                    <?php
                                                                }
                                                            } else {
                                                                if (set_value('action_required') == '0') {
                                                                    ?>
                                                                    selected
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                            value="0">No</option>    
                                                        <option
                                                        <?php
                                                        if (isset($document['action_required'])) {
                                                            if (set_value('action_required', $document['action_required']) == '1') {
                                                                ?>
                                                                    selected
                                                                    <?php
                                                                }
                                                            } else {
                                                                if (set_value('action_required') == '1') {
                                                                    ?>
                                                                    selected
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                            value="1">Yes</option>   
                                                    </select>
                                                </div>
                                                <div class="help-text">
                                                    If this doc requires a new hire to fill something out and return it to you, select this option and we'll notify the new hire during the onboarding process that this doc requires specific action.
                                                </div>
                                            </li>
                                            <div class="information-text-block no-margin">
                                                <label class="control control--checkbox">If this is a document you would like to distribute to your current employees, check this box & we will send them each an email alert and add the doc to their accounts!
                                                    <input type="checkbox" id="background-check" name="to_all_employees" 
                                                    <?php
                                                    if (isset($document['to_all_employees'])) {
                                                        if (set_value('to_all_employees', $document['to_all_employees']) == '1') {
                                                            ?>
                                                                   checked
                                                                   <?php
                                                               }
                                                           } else {
                                                               if (set_value('to_all_employees') == '1') {
                                                                   ?>
                                                                   checked
                                                                   <?php
                                                               }
                                                           }
                                                           ?>
                                                           value="1">
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                            <div class="btn-panel">
                                                <input type="submit" class="submit-btn" onclick="validate_form();" value="upload">
                                                <a class="submit-btn btn-cancel" href ="<?php echo base_url('hr_documents'); ?>">Cancel</a>

                                            </div>
                                        </ul>
                                    </div>
                                </form>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                <div class="tick-list-box">
                                    <h2><?php echo STORE_NAME; ?> is Secure</h2>
                                    <ul>
                                        <li>Transmissions encrypted by SSL</li>
                                        <li>Information treated confidential by AutomotHR</li>
                                        <li>Receive emails with your signed paperwork</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>          
        </div>
    </div>
</div>
<?php if (isset($document)) { ?>
    <div id="document_modal" class="modal fade file-uploaded-modal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php echo $document['document_name']; ?> </h4>
                </div>
                <iframe class="uploaded-file-preview" src="https://docs.google.com/gview?url=<?php echo AWS_S3_BUCKET_URL . urlencode($document['document_name']); ?>&embedded=true" style="width:600px; height:500px;" frameborder="0"></iframe>
            </div>
        </div>
    </div>
<?php } ?>
<!-- Main End -->
<script  language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script  language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script>
                                                    function check_file(val) {
                                                        var fileName = $("#" + val).val();
                                                        if (fileName.length > 0) {
                                                            $('#name_' + val).html(fileName.substring(0, 28));
                                                            var ext = fileName.split('.').pop();
                                                            var ext = ext.toLowerCase();
                                                            if (val == 'document') {
                                                                if (ext != "pdf" && ext != "docx" && ext != "doc") {
                                                                    $("#" + val).val(null);
                                                                    $('#name_' + val).html('<p class="red">Only (.pdf .docx .doc) allowed!</p>');
                                                                }
                                                            }
                                                        } else {
                                                            $('#name_' + val).html('Please Select');
                                                        }
                                                    }

                                                    function validate_form() {
                                                        $("#document_form").validate({
                                                            ignore: [],
                                                            rules: {
                                                                document_name: {
                                                                    required: true,
                                                                    pattern: /^[a-zA-Z0-9\-._ ]+$/
                                                                }

                                                            },
                                                            messages: {
                                                                document_name: {
                                                                    required: 'Document name is required',
                                                                    pattern: 'Letters, numbers,underscore and dashes only please'
                                                                },
                                                                document: {
                                                                    required: 'Document file is required',
                                                                }
                                                            },
                                                            submitHandler: function (form) {
                                                                form.submit();
                                                            }
                                                        });
                                                    }

                                                    function check_length() {
                                                        var text_allowed = 128;
                                                        var user_text = $('#document_description').val();
                                                        var newLines = user_text.match(/(\r\n|\n|\r)/g);
                                                        var addition = 0;
                                                        if (newLines != null) {
                                                            addition = newLines.length;
                                                        }
                                                        var text_length = user_text.length + addition;
                                                        var text_left = text_allowed - text_length;
                                                        //console.log(user_text+' = '+text_length+" LEFT: "+text_left);
                                                        $('#remaining_text').html(text_left + ' characters left!');
                                                    }
</script>