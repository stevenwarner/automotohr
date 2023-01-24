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
                        <div class="job-title-text">
                            <p>Fields marked with an asterisk (<span>*</span>) are mandatory.</p>
                        </div>
                    </div>
                    <div class="dashboard-conetnt-wrp">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php echo form_open_multipart('', array('id' => 'form_add_email_template', 'autocomplete' => 'off')); ?>
                                <div class="universal-form-style-v2">
                                    <ul>

                                        <li class="form-col-100">
                                            <?php echo form_label('Template Name <span class="hr-required">*</span>', 'template_name'); ?>
                                            <?php echo form_input('template_name', set_value('template_name', ''), 'class="invoice-fields" id="template_name" data-rule-required="true"'); ?>
                                            <?php echo form_error('template_name'); ?>
                                        </li>
                                        <li class="form-col-100">
                                            <?php echo form_label('From Name <span class="hr-required">*</span>', 'from_name'); ?>
                                            <?php echo form_input('from_name', set_value('from_name', ''), 'class="invoice-fields" data-rule-required="true"'); ?>
                                            <?php echo form_error('from_name'); ?>
                                        </li>
                                        <li class="form-col-100">
                                            <?php echo form_label('Subject <span class="hr-required">*</span>', 'subject'); ?>
                                            <?php echo form_input('subject', set_value('subject', ''), 'class="invoice-fields" data-rule-required="true"'); ?>
                                            <?php echo form_error('subject'); ?>
                                        </li>

                                        <li class="form-col-100 autoheight">
                                            <label>Attachment</label>
                                            <div class="upload-file invoice-fields">
                                                <span id="name_message_attachment" class="selected-file">No file selected</span>
                                                <input type="file" name="message_attachment" id="message_attachment" class="image">
                                                <a href="javascript:;">Choose File</a>
                                            </div>
                                        </li>
                                        <li class="form-col-100 autoheight">
                                            <div class="form-col-100" id="add_answer">
                                                <a href="javascript:;" onclick="addattachmentblock(); return false;" class="add"> + Attachment</a>
                                            </div>
                                        </li>
                                        <li class="form-col-100 autoheight">
                                            <div id="dynamicattachment"></div>
                                        </li>
                                        <li class="form-col-100 autoheight">

                                            <div class="row">
                                                <div class="col-md-8 col-xs-12">
                                                    <label>Email Body <span class="hr-required">*</span></label>
                                                    <textarea class="ckeditor"  name="message_body" id="message_body" rows="10" data-rule-required="true"><?php echo set_value('message_body', ''); ?></textarea>
                                                </div>
                                                <div class="col-md-4 col-xs-12">
                                                    <div class="offer-letter-help-widget pull-right">
                                                        <div class="how-it-works-insturction">
                                                            <strong>How it's Works :</strong>
                                                            <p class="how-works-attr">1. Add template name</p>
                                                            <p class="how-works-attr">2. Add template subject</p>
                                                            <p class="how-works-attr">3. Add template body</p>
                                                            <p class="how-works-attr">4. Add data from tags below</p>
                                                            <p class="how-works-attr">5. Save the template</p>
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
                                                    </div>
                                                </div>
                                            </div>

                                        </li>
                                        <li class="form-col-100">
                                            <input type="hidden" name="action" id="action" value="edit_email_template" />
                                            <input type="hidden" name="company_sid" id="company_sid" value="<?php echo $company_sid; ?>" />
                                            <input type="hidden" name="company_name" id="company_name" value="<?php echo $company_name; ?>" />

                                            <input type="submit" value="Save" class="submit-btn">
                                            <input type="button" value="Cancel" class="submit-btn btn-cancel" onClick="document.location.href = '<?= base_url('portal_email_templates') ?>'" />
                                        </li>
                                    </ul>
                                </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script language="JavaScript" type="text/javascript">
    $(document).ready(function(){
//        $('#form_add_email_template').validate();
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
        $('#' + container_id).html($('#' + container_id).html() + '<li class="form-col-100 autoheight"><label>Attachment</label><div class="upload-file invoice-fields"><span id="name_'+id+'" class="selected-file">No file selected</span><input type="file" name="'+id+'" id="'+id+'" class="image"><a href="javascript:;">Choose File</a></div><div class="delete-row"><a href="javascript:;" onclick="deleteAnswerBlock(\'' + container_id + '\'); return false;" class="remove">Delete</a></div></li>');
        i++;
    }
    
    function deleteAnswerBlock(id) {
        console.log('Delete it: '+id);
        $('#' + id).remove();
    }
    
//    function validate_form() {
        $("#form_add_email_template").validate({
            ignore: ":hidden:not(select)",
            rules: {
                template_name: {
                    required: true
                },
                from_name: {
                    required: true
                },
                subject: {
                    required: true
                }
            },
            messages: {
                template_name: {
                    required: 'Please provide template name'
                },
                from_name: {
                    required: 'Subject provide from name'
                },
                subject: {
                    required: 'Subject is required'
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
//    }
</script>
<style>
    .tags-area strong{ padding: 0 10px;}
    .offer-letter-help-widget{ padding-bottom: 10px; }
    .tags-area ul.tags{ padding: 0 0; }
    .tags-area ul.tags li{ width: auto; background-color: #f8f8f8; border: 1px solid #d9d8d5;border-radius: 50px;display: inline-block;height: auto !important;margin: 10px 0 0 10px !important;overflow: hidden;padding: 7px;text-align: center; }
</style>