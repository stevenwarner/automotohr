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
                                <a class="dashboard-link-btn" href="<?php echo base_url('portal_sms_templates') ?>"><i class="fa fa-chevron-left"></i>Portal SMS Templates</a>
                                <?php echo $title; ?>
                            </span>
                        </div>
                        <div class="job-title-text">
                            <p>Fields marked with an asterisk (<span>*</span>) are mandatory.</p>
                        </div>
                    </div>
                    <div class="dashboard-conetnt-wrp">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php echo form_open_multipart('', array('id' => 'form_add_sms_template', 'autocomplete' => 'off')); ?>
                                <div class="universal-form-style-v2">
                                    <ul>

                                        <li class="form-col-100">
                                            <?php echo form_label('Template Name <span class="hr-required">*</span>', 'template_name'); ?>
                                            <?php echo form_input('template_name', set_value('template_name', ''), 'class="invoice-fields" id="template_name" data-rule-required="true"'); ?>
                                            <?php echo form_error('template_name'); ?>
                                        </li>
                                        <li class="form-col-100">
                                            <?php echo form_label('Status <span class="hr-required">*</span>', 'status'); ?>
                                            <select name="status" class="invoice-fields">
                                                <option value="1">Active</option>
                                                <option value="0">In-Active</option>
                                            </select>
                                        </li>
                                        <li class="form-col-100 autoheight">

                                            <div class="row">
<!--                                                <div class="col-md-8 col-xs-12">-->
<!--                                                    <label>Email Body <span class="hr-required">*</span></label>-->
<!--                                                    <textarea class="ckeditor"  name="message_body" id="message_body" rows="10" data-rule-required="true">--><?php //echo set_value('message_body', ''); ?><!--</textarea>-->
<!--                                                </div>-->
                                                <div class="col-md-8 col-xs-12">
                                                    <label>Message<span class="cs-required">*</span></label>
                                                    <textarea name="txt_message" rows="10" class="form-control js-message"></textarea>
                                                    <p><span class="js-words">0</span> words / <span class="js-sms">0</span> sms (160 words/sms )</p>
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
                                                            </ul>

                                                        </div>
                                                        <div class="tags-area pull-left">
                                                            <br />
                                                            <strong>Employee / Applicant Tags :</strong>
                                                            <ul class="tags">
                                                                <li>{{contact_name}}</li>
                                                                <li>{{email}}</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </li>
                                        <li class="form-col-100">
                                            <input type="hidden" name="action" id="action" value="add_sms_template" />

                                            <input type="submit" value="Save" class="submit-btn">
                                            <input type="button" value="Cancel" class="submit-btn btn-cancel" onClick="document.location.href = '<?= base_url('portal_sms_templates') ?>'" />
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
        var word_limit = 160;
        $('.js-message').keyup(function(event) {
            var total_words = $(this).val().length;
            $('.js-words').text(total_words);
            $('.js-sms').text(total_words === 0 ? 0 : (total_words <= word_limit ? 1 : Math.ceil(total_words/word_limit)));
        });
    });

    
//    function validate_form() {
        $("#form_add_sms_template").validate({
            ignore: ":hidden:not(select)",
            rules: {
                template_name: {
                    required: true
                },
                txt_message: {
                    required: true
                }
            },
            messages: {
                template_name: {
                    required: 'Please provide template name'
                },
                txt_messages: {
                    required: 'Please provide sms'
                }
            },
            submitHandler: function (form) {
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