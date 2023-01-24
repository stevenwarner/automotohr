<?php $side_work = '<div class="offer-letter-help-widget pull-right">
    <div class="how-it-works-insturction">
        <strong>How it\'s Works :</strong>
        <p class="how-works-attr">1. Edit template name</p>
        <p class="how-works-attr">2. Edit template subject</p>
        <p class="how-works-attr">3. Edit template body</p>
        <p class="how-works-attr">4. Edit data from tags below</p>
        <p class="how-works-attr">5. Update the template</p>
    </div>

    <div class="tags-area pull-left">
        <strong>Company Information Tags :</strong>
        <ul class="tags">
            <li>{{company_name}}</li>
            <li>{{company_address}}</li>
            <li>{{company_phone}}</li>
            <li>{{career_site_url}}</li>
        </ul>

    </div>
    <div class="tags-area pull-left">
        <br />
        <strong>Employee / Applicant Tags :</strong>
        <ul class="tags">
            <li>{{first_name}}</li>
            <li>{{last_name}}</li>
            <li>{{email}}</li>
            <li>{{job_title}}</li>
            <li>{{date}}</li>
        </ul>
    </div>
</div>'; ?>
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">				
                <?php $this->load->view('manage_employer/settings_left_menu_administration'); ?><!-- manage_employer/profile_left_menu_company -->
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-8">
                        <div class="page-header-area">
                            <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                <a class="dashboard-link-btn" href="<?php echo base_url('portal_email_templates') ?>"><i class="fa fa-chevron-left"></i>Portal Email Templates</a>
                                <?php echo $title; ?>
                            </span>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">

                        <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                        <div class="dashboard-conetnt-wrp">
                            <div class="row">
                                <div class="col-xs-12">
                                    <?php echo form_open_multipart('', array('id' => 'edittemplate')); ?>
                                    <div class="universal-form-style-v2">
                                        <div class="job-title-text">
                                            <p>Fields marked with an asterisk (<span>*</span>) are mandatory.</p>
                                        </div>
                                        <ul>
                                            <?php if($template_data['is_custom'] == 0) { ?>
                                                <?php if($template_data['template_code'] != 'rejection_letter' && $template_data['template_code'] != 'reference_request_letter' && $template_data['template_code'] != 'employment_reference_questionnaire_letter' && $template_data['template_code'] != 'hr_document_notification' && $template_data['template_code'] != 'on_boarding_request') { ?>
                                                    <li class="form-col-100">
                                                        <?php echo form_label('Enable Auto Responder E-mail', 'enable_auto_responder'); ?>
                                                        <br><br>
                                                        <input class="select-domain" type="checkbox" name="enable_auto_responder" value="1"
                                                            <?php   if ($template_data['enable_auto_responder'] == 1) { echo "checked"; } ?>>
                                                        <span>&nbsp;Enable Auto Responder for <b><i><?php echo $template_data['template_name']; ?></i></b></span>
                                                    </li>
                                                <?php } ?>
                                            <?php   } ?>
                                            <li class="form-col-100">
                                                <?php $readonly = $template_data['is_custom'] == 0 ? 'readonly' : ''; ?>
                                                <?php echo form_label('Template Name', 'template_name'); ?>
                                                <?php echo form_input('template_name', set_value('template_name', $template_data['template_name']), 'class="invoice-fields" id="template_name" '.$readonly); ?>
                                                <?php echo form_error('template_name'); ?>
                                            </li>
                                            <li class="form-col-100">
                                                <?php echo form_label('From Name <span class="hr-required">*</span>', 'from_name'); ?>
                                                <?php echo form_input('from_name', set_value('from_name', $template_data['from_name']), 'class="invoice-fields"'); ?>
                                                <?php echo form_error('from_name'); ?>
                                            </li>
                                            <li class="form-col-100">
                                                <?php echo form_label('Subject <span class="hr-required">*</span>', 'subject'); ?>
                                                <?php echo form_input('subject', set_value('subject', $template_data['subject']), 'class="invoice-fields"'); ?>
                                                <?php echo form_error('subject'); ?>
                                            </li>
                                            <li class="form-col-100 autoheight">
                                                <label>Attachments</label>
                                                <div class="upload-file invoice-fields">
                                                    <span id="name_message_attachment" class="selected-file">No file selected</span>
                                                    <input type="file" name="message_attachment" id="message_attachment" class="image">
                                                    <a href="javascript:;">Choose File</a>
                                                </div>
                                            </li>
                                            <li class="form-col-100 autoheight">
                                                <div class="form-col-100" id="add_answer">
                                                    <a href="javascript:;" onclick="addattachmentblock(); return false;" class="add btn btn-primary"> + Attachment</a>
                                                </div>
                                            </li>
                                            <li class="form-col-100 autoheight">
                                                <div id="dynamicattachment"></div>
                                            </li>
                                            <li class="form-col-100 autoheight">
                                                <?php if($template_data['is_custom'] == 0) {?>
                                                    <div class="row">
                                                        <div class="col-md-8 col-xs-12">
                                                            <?php echo form_label('Email Body <span class="hr-required">*</span>', 'message_body'); ?>
                                                            <div style='margin-bottom:5px;'><?php $this->load->view('templates/_parts/ckeditor_gallery_link'); ?></div>
                                                            <script type="text/javascript" src="<?php echo site_url('assets/ckeditor/ckeditor.js'); ?>"></script>
                                                            <textarea class="ckeditor" name="message_body" id="message_body" rows="8" cols="60" ><?php echo set_value('message_body', $template_data['message_body']); ?></textarea>
                                                        </div>    
                                                        <?php echo form_error('message_body');
                                                        if($template_data['template_code'] == 'send_candidate_application_notification'){ ?>
                                                            <div class="col-md-4 col-xs-12">
                                                                <div class="offer-letter-help-widget">
                                                                    <div class="how-it-works-insturction">
                                                                        <strong>How it's Works :</strong>
                                                                        <p class="how-works-attr">1. Add template name</p>
                                                                        <p class="how-works-attr">2. Add template subject</p>
                                                                        <p class="how-works-attr">3. Add template body</p>
                                                                        <p class="how-works-attr">4. Add data from tags below</p>
                                                                        <p class="how-works-attr">5. Save the template</p>
                                                                    </div>

                                                                    <div class="tags-arae">
                                                                        <strong>Tags :</strong> (select tag from below)
                                                                        <ul class="tags">
                                                                            <li>{{block_start}} <br> (Start of the block to be repeatable)</li>
                                                                            <li>{{employee_name}}</li>
                                                                            <li>{{email}}</li>
                                                                            <li>{{applicant_name}}</li>
                                                                            <li>{{phone_number}}</li>
                                                                            <li>{{download_resume}}</li>
                                                                            <li>{{city}}</li>
                                                                            <li>{{block_end}} <br> (End of the block to be repeatable)</li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php } else { ?>
                                                            <div class="col-md-4 col-xs-12" style="margin-top: 34px;">
                                                                <?=$side_work;?>
                                                            </div>
                                                        <?php } ?>    
                                                    </div>
                                                <?php
                                                }   else { ?>
                                                    <div class="row">
                                                        <div class="col-md-8 col-xs-12">
                                                            <label>Email Body <span class="hr-required">*</span></label>
                                                            <textarea class="ckeditor"  name="message_body" id="message_body" rows="10"><?php echo set_value('message_body', $template_data['message_body']); ?></textarea>
                                                        </div>
                                                        <div class="col-md-4 col-xs-12">
                                                           <?=$side_work;?>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </li>
                                            <li class="form-col-100">
                                                <input type="hidden" name="action" value="edit_email_template">
                                                <input type="submit" value="Update" onclick="return validate_form()" class="submit-btn">
                                                <input type="button" value="Cancel" class="submit-btn btn-cancel" onClick="document.location.href = '<?= base_url('portal_email_templates') ?>'" />
                                            </li>
                                        </ul>
                                    </div>
                                    <?php echo form_close(); ?>
                                </div>
                            </div>
                        <?php if(!empty($attachments)) { ?>
                            <hr />
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="hr-box">
                                        <div class="hr-innerpadding">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover table-striped table-condensed">
                                                    <thead>
                                                    <tr>
                                                        <th class="col-lg-10">Attachment</th>
                                                        <th class="col-lg-2 text-center">Actions</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if (!empty($attachments)) { ?>
                                                            <?php foreach($attachments as $attachment) { ?>
                                                                <tr>
                                                                    <td><?php echo $attachment['original_file_name']; ?></td>
                                                                    <td class="text-center">
                                                                        <a href="javascript:;" class="btn btn-primary btn-sm"
                                                                        onclick="fLaunchModal(this);"
                                                                        data-preview-url="<?php echo AWS_S3_BUCKET_URL . $attachment['attachment_aws_file']; ?>"
                                                                        data-document-sid="<?php echo  $attachment['sid']; ?>"
                                                                        data-document-title="<?php echo $attachment['original_file_name']; ?>">
                                                                            <i class="fa fa-eye"></i>
                                                                        </a>
                                                                        <a href="<?php echo $attachment['print_url']; ?>" class="btn btn-success btn-sm" target="_blank">
                                                                            <i class="fa fa-print"></i>
                                                                        </a>
                                                                        <a href="<?php echo $attachment['download_url']; ?>" class="btn btn-success btn-sm">
                                                                            <i class="fa fa-download"></i>
                                                                        </a>
                                                                        <button type="button" id="btn_delete_attachment" class="btn btn-danger btn-sm" onclick="func_delete_attachment(<?php echo $attachment['sid']; ?>);"><i class="fa fa-trash"></i></button>
                                                                        <form id="form_delete_attachment_<?php echo $attachment['sid']?>" method="post" enctype="multipart/form-data" action="<?php echo current_url(); ?>">
                                                                            <input type="hidden" id="perform_action" name="perform_action" value="delete_attachment" />
                                                                            <input type="hidden" id="attachment_sid" name="attachment_sid" value="<?php echo $attachment['sid']; ?>" />
                                                                            <input type="hidden" id="portal_email_template_sid" name="portal_email_template_sid" value="<?php echo $attachment['portal_email_template_sid']; ?>" />
                                                                        </form>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>    
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br />
                        <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script language="JavaScript" type="text/javascript">
    function func_delete_attachment(attachment_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to delete this Attachment?',
            function () {
                $('#form_delete_attachment_' + attachment_sid).submit();
            },
            function () {
                alertify.error('Cancelled!');
            }
        );
    }

    $(document).ready(function () {
        $('body').on('change', 'input[type=file]', function () {
            console.log($(this).val());
            var selected_file = $(this).val();
            var selected_file = selected_file.substring(selected_file.lastIndexOf('\\') + 1, selected_file.length);

            var id = $(this).attr('id');
            $('#name_' + id).html(selected_file);
        });
    });

    var i = 1;

    function addattachmentblock() {
        var container_id = "message_attachment_container" + i;
        var id = "message_attachment" + i;
        $("<div id='" + container_id + "'><\/div>").appendTo("#dynamicattachment");
        $('#' + container_id).html($('#' + container_id).html() + '<li class="form-col-100 autoheight"><label>Attachment</label><div class="upload-file invoice-fields"><span id="name_' + id + '" class="selected-file">No file selected</span><input type="file" name="' + id + '" id="' + id + '" class="image"><a href="javascript:;">Choose File</a></div><div class="delete-row"><a href="javascript:;" onclick="deleteAnswerBlock(\'' + container_id + '\'); return false;" class="remove">Delete</a></div></li>');
        i++;
    }

    function deleteAnswerBlock(id) {
        console.log('Delete it: ' + id);
        $('#' + id).remove();
    }

    function validate_form() {
        $("#edittemplate").validate({
            ignore: ":hidden:not(select)",
            rules: {
                from_name: {
                    required: true
                    /*pattern: /^[a-zA-Z0-9\- ]+$/*/
                },
                subject: {
                    required: true
                    /*pattern: /^[a-zA-Z0-9\- .]+$/*/
                }
            },
            messages: {
                from_email: {
                    required: 'Please provide Valid email'
                },
                subject: {
                    required: 'Email Subject is required',
                    /*pattern: 'Letters, numbers, and dashes only please'*/
                }
            },
            submitHandler: function (form) {
                var saved_name = JSON.parse('<?= $names?>');
                var user_title = $('#template_name').val().toLowerCase();
                if(jQuery.inArray(user_title, saved_name) != -1) {
                    alertify.alert('Error! Duplication', "Template Name Already Exists");
                    return false;
                }
                var text_pass = $.trim(CKEDITOR.instances.message_body.getData());
                if (text_pass.length === 0) {
                    alertify.alert('Error! E-Mail Body Missing', "E-Mail Body cannot be Empty");
                    return false;
                }
                form.submit();
            }
        });
    }

    function fLaunchModal(source) {
        var document_preview_url = $(source).attr('data-preview-url');
        var document_file_name = $(source).attr('data-document-title');
        var document_sid = $(source).attr('data-document-sid');
        var file_extension = document_file_name.substr(document_file_name.lastIndexOf('.') + 1, document_file_name.length);
        var modal_content = '';
        var footer_content = '';
        var iframe_url = '';

        if (document_preview_url != '') {
            switch (file_extension.toLowerCase()) {
                case 'doc':
                case 'docx':
                case 'xls':
                case 'xlsx':
                    iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_preview_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    break;
                case 'jpg':
                case 'jpe':
                case 'jpeg':
                case 'png':
                case 'gif':
                case 'JPG':
                case 'JPE':
                case 'JPEG':
                case 'PNG':
                case 'GIF':
                    modal_content = '<img src="' + document_preview_url + '" style="width:100%; height:500px;" />';
                    footer_print_btn = '<a target="_blank" href="<?php echo base_url('hr_documents_management/print_generated_and_offer_later/original/generated'); ?>'+'/'+document_sid+'" class="btn btn-success">Print</a>';
                    break;
                default : //using google docs
                    iframe_url = 'https://docs.google.com/gview?url=' + document_preview_url + '&embedded=true';
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    break;
            }

        } else {
            modal_content = '<h5>No ' + document_file_name + ' Uploaded.</h5>';
            footer_content = '';
        }

        $('#document_modal_body').html(modal_content);
        $('#document_modal_footer').html(footer_content);
        $('#document_modal_title').html(document_file_name);
        $('#document_modal').modal("toggle");
        $('#document_modal').on("shown.bs.modal", function () {
        
            if (iframe_url != '') {
                $('#preview_iframe').attr('src', iframe_url);
            }
        });
    }        
       
</script>
<style>
    .tags-area strong{ padding: 0 10px;}
    .offer-letter-help-widget{ padding-bottom: 10px; }
    .tags-area ul.tags{ padding: 0 0; }
    .tags-area ul.tags li{ width: auto; background-color: #f8f8f8; border: 1px solid #d9d8d5;border-radius: 50px;display: inline-block;height: auto !important;margin: 10px 0 0 10px !important;overflow: hidden;padding: 7px;text-align: center; }
</style>