<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/settings_left_menu_administration'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?><?php echo $title; ?></span>
                            </div>
                        </div>
                        <div class="dashboard-conetnt-wrp">
                            <?php echo form_open_multipart('', array('id' => 'kpa_onboarding')); ?>
                            <?php echo form_hidden('company_sid', $company_sid); ?>
                            <?php echo form_hidden('template_code', $template_code); ?>
                            <?php echo form_hidden('template_sid', $onboarding_email_template['sid']); ?>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="create-job-wrap">
                                    <div class="universal-form-style-v2">
                                        <ul>
                                            <li class="form-col-100">
                                                <?php echo form_label('Outsourced HR Compliance and Onboarding Url', 'kpa_url'); ?>
                                                <?php echo form_input('kpa_url', set_value('kpa_url', $kpaData['kpa_url']), 'class="invoice-fields"'); ?>
                                                <?php echo form_error('kpa_url'); ?>
                                            </li>
                                            <li class="form-col-100 autoheight">
                                                <label class="control control--checkbox">
                                                    Enable <b class="text-success">Outsourced HR Onboarding</b> <small class="help_text">You can active/deactive Outsourced HR Compliance and Onboarding from here.</small>
                                                    <input class="select-domain" type="checkbox" id="kpa_status" name="status" value="1" <?php echo $kpaData['status'] == '1' ? 'checked="checked"' : ''; ?> />
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </li>
                                        </ul>
                                        <hr />

                                        <div id="email_template" class="hr-box">
                                            <div class="hr-box-header bg-header-green"><strong>Email Template</strong></div>
                                            <div class="hr-innerpadding">
                                                <ul>
                                                    <li class="form-col-100 autoheight">
                                                        <?php $field_id = 'subject'; ?>
                                                        <?php echo form_label('Message Subject', $field_id); ?>
                                                        <?php echo form_input($field_id, set_value($field_id, $onboarding_email_template[$field_id]), 'class="invoice-fields"'); ?>
                                                        <?php echo form_error($field_id); ?>
                                                    </li>
                                                    <li class="form-col-100 autoheight">
                                                        <?php $field_id = 'message_body'; ?>
                                                        <?php echo form_label('Message Body', $field_id); ?>
                                                        <?php echo form_textarea($field_id, set_value($field_id, html_entity_decode($onboarding_email_template[$field_id]), false), 'class="invoice-fields autoheight ckeditor"'); ?>
                                                        <?php echo form_error($field_id); ?>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <ul>
                                            <li class="form-col-100 autoheight">
                                                <input type="submit" value="Save" onclick="return validate_form()" class="submit-btn">
                                                <input type="button" value="Cancel" class="submit-btn btn-cancel" onClick="document.location.href = '<?= base_url('my_settings') ?>'" />
                                            </li>
                                        </ul>
                                    </div>
                                </div>
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
<script>
    $(document).ready(function () {
        toggle_email_template();
        $('#kpa_status').on('click', function(){
            toggle_email_template();
        });
    });

    function toggle_email_template(){
        var checked = $('#kpa_status').prop('checked');

        if(checked == true){
            $('#email_template').show();
        } else {
            $('#email_template').hide();
        }
    }

    function validate_form() {
        $("#kpa_onboarding").validate({
            ignore: ":hidden:not(select)",
            rules: {
                kpa_url: {
                    required: true,
                    pattern: /^(http|https|ftp|smtp)?:\/\/[a-zA-Z0-9-\.]+\.[a-z]{2,4}/
                }
            },
            messages: {
                kpa_url: {
                    required: 'Outsourced HR Compliance and Onboarding URL is required',
                    pattern: 'Please Provide valid Web Url'
                }
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
    }
</script>