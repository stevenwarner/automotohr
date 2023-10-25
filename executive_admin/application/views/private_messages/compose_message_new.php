<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <?php $this->load->view('flashmessage/flash_message'); ?>
                <div class="heading-title page-title">
                    <h1 class="page-title"><i class="fa fa-envelope"></i><?php echo $title; ?> (<?= $page ?>)</h1>
                    <a class="black-btn pull-right" href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-long-arrow-left"></i> Back to Dashboard</a>
                </div>
                <div class="bt-panel">
                    <div class="row">
                        <div class="col-lg-8 col-md-6 col-xs-12 col-sm-5">
                            <div class="company-name pull-left">
                                Company Name: <strong><?php echo $company_name; ?></strong>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-xs-12 col-sm-7">
                            <a href="<?php echo base_url('private_messages') . '/' . $company_id; ?>" class="btn btn-success">Inbox <?php if ($total_messages > 0) { ?><span>(<?= $total_messages ?>)</span><?php } ?></a>
                            <a href="<?php echo base_url('outbox') . '/' . $company_id; ?>" class="btn btn-success">Outbox</a>
                            <a href="<?php echo base_url('compose_message') . '/' . $company_id; ?>" class="btn btn-success">Compose new Message</a>
                        </div>
                    </div>
                </div>
                <div class="compose-message">
                    <?php echo form_open_multipart('', array('class' => 'compose-message-form', 'id' => 'compose_message_form')); ?>
                    <input type="hidden" name="users_type" value="employee">
                    <div class="panel panel-default full-width">
                        <?php
                        if ($page == 'reply') { ?>
                            <div class="panel-heading">
                                <strong>Reply to Message</strong>
                            </div>
                            <?php
                            if ($messgae_type_flag == 'email') { ?>
                                <input type="hidden" name="send_invoice" value="to_email">
                                <input type="hidden" name="toemail" value="<?php echo $message_type; ?>">
                            <?php       } elseif ($messgae_type_flag == 'employer') { ?>
                                <input type="hidden" name="send_invoice" value="to_employer">
                                <input type="hidden" name="toemail" value="<?php echo $message_type; ?>">
                            <?php       } elseif ($messgae_type_flag == 'admin') { ?>
                                <input type="hidden" name="send_invoice" value="to_admin">
                            <?php       }
                        } else { ?>
                            <div class="panel-heading select-msg-receiver">
                                <div class="row">
                                    <div class="col-lg-2 col-md-3 col-xs-12 col-sm-12">
                                        <label class="form-group no-border-right">
                                            <strong class="text-uppercase">Select a Receiver</strong><span class="hr-required red"> * </span>
                                        </label>
                                    </div>
                                    <div class="col-lg-10 col-md-9 col-xs-12 col-sm-12">
                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                <div class="form-group">
                                                    <label class="control control--radio">To Admin
                                                        <input type="radio" name="send_invoice" value="to_admin" id="to_employer" checked>
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                <div class="form-group">
                                                    <label class="control control--radio">Email
                                                        <input type="radio" name="send_invoice" value="to_email" id="to_email">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                <div class="form-group">
                                                    <label class="control control--radio">Employees
                                                        <input type="radio" name="send_invoice" value="to_employees" id="to_employees">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                <div class="form-group no-border-right">
                                                    <label class="control control--radio">Applicants
                                                        <input type="radio" name="send_invoice" value="to_applicants" id="to_applicants">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="panel-body">
                            <div class="form-wrp">

                                <div class="row">
                                    <div class="col-lg-2 col-md-3 col-xs-12 col-sm-3">
                                        <label>Email Template</label>
                                    </div>
                                    <div class="col-lg-10 col-md-9 col-xs-12 col-sm-9">
                                        <div class="form-group autoheight">
                                            <select class="form-control invoice-fields" name="template" id="template">
                                                <option id="" data-name="" data-subject="" data-body="" value="">Please Select</option>
                                                <?php if (!empty($portal_email_templates)) { ?>
                                                    <?php foreach ($portal_email_templates as $template) { ?>
                                                        <option id="template_<?php echo $template['sid']; ?>" data-name="<?php echo $template['template_name'] ?>" data-subject="<?php echo $template['subject']; ?>" data-body="<?php echo htmlentities($template['message_body']); ?>" value="<?php echo $template['sid']; ?>"><?php echo $template['template_name']; ?></option>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <option id="template_" data-name="" data-subject="" data-body="" value="">No Custom Template Defined</option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>



                                <?php if ($page == 'reply') { ?>
                                    <div class="row" <?php if ($message_type != '1') { ?>style="display: none" <?php } ?> id="to_employer_div">
                                        <div class="col-lg-2 col-md-3 col-xs-12 col-sm-3">
                                            <label>Message To (E-Mail)</label>
                                        </div>
                                        <div class="col-lg-10 col-md-9 col-xs-12 col-sm-9">
                                            <div class="form-group autoheight">
                                                <input class="form-control" type="text" value="Admin" name="to-admin" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" <?php if ($message_type == '1') { ?>style="display: none" <?php } ?>id="to_email_div">
                                        <div class="col-lg-2 col-md-3 col-xs-12 col-sm-3">
                                            <label>Message To</label>
                                        </div>
                                        <div class="col-lg-10 col-md-9 col-xs-12 col-sm-9">
                                            <div class="form-group autoheight">
                                                <input class="form-control" value="<?php echo $message_type; ?>" id="toemail" type="text" disabled>
                                            </div>
                                        </div>
                                    </div>
                                <?php   } else { ?>
                                    <div class="row" id="to_employer_div">
                                        <div class="col-lg-2 col-md-3 col-xs-12 col-sm-3">
                                            <label>Message To</label>
                                        </div>
                                        <div class="col-lg-10 col-md-9 col-xs-12 col-sm-9">
                                            <div class="form-group autoheight">
                                                <input class="form-control" type="text" value="Admin" name="to-admin" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="display: none" id="to_email_div">
                                        <div class="col-lg-2 col-md-3 col-xs-12 col-sm-3">
                                            <label>Message To (E-Mail)</label>
                                        </div>
                                        <div class="col-lg-10 col-md-9 col-xs-12 col-sm-9">
                                            <div class="form-group autoheight">
                                                <input class="form-control" name="toemail" id="toemail" type="text">
                                                <em class="text-muted">Please enter comma separated values</em>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="display: none" id="to_employees_div">
                                        <div class="col-lg-2 col-md-3 col-xs-12 col-sm-3">
                                            <label>Select Employees</label>
                                        </div>
                                        <div class="col-lg-10 col-md-9 col-xs-12 col-sm-9">
                                            <div class="form-group autoheight">
                                                <?php if (sizeof($employees) > 0) { ?>
                                                    <select multiple class="chosen-select" tabindex="8" name='employees[]' id='employees'>
                                                        <?php
                                                        foreach ($employees as $employee) {
                                                            if ($employer_sid == $employee['sid']) {
                                                                continue;
                                                            }
                                                        ?>
                                                            <option value="<?php echo $employee['sid']; ?>"><?php echo $employee['first_name'] . ' ' . $employee['last_name'] . ' ' . $employee['email']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                <?php } else { ?>
                                                    <p>No Employee Found.</p>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="display: none" id="to_applicants_div">
                                        <div class="col-lg-2 col-md-3 col-xs-12 col-sm-3">
                                            <label>Select Applicants</label>
                                        </div>
                                        <div class="col-lg-10 col-md-9 col-xs-12 col-sm-9">
                                            <div class="form-group autoheight">
                                                <?php if (sizeof($applicants) > 0) { ?>
                                                    <select multiple class="chosen-select" tabindex="8" name='applicants[]' id='applicants'>
                                                        <?php foreach ($applicants as $applicant) { ?>
                                                            <option value="<?php echo $applicant['sid']; ?>"><?php echo $applicant['first_name'] . ' ' . $applicant['last_name'] . ' ' . $applicant['email']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                <?php } else { ?>
                                                    <p>No Applicant Found.</p>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php   } ?>
                                <div class="row">
                                    <div class="col-lg-2 col-md-3 col-xs-12 col-sm-3">
                                        <label>Subject<span class="required"> * </span></label>
                                    </div>
                                    <div class="col-lg-10 col-md-9 col-xs-12 col-sm-9">
                                        <div class="form-group autoheight">
                                            <?php echo form_input('subject', set_value('subject'), 'class="form-control jsSubject"');
                                            echo form_error('subject'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-2 col-md-3 col-xs-12 col-sm-3">
                                        <label>Message</label>
                                    </div>
                                    <script type="text/javascript" src="<?php echo site_url('assets/ckeditor/ckeditor.js'); ?>"></script>
                                    <div class="col-lg-10 col-md-9 col-xs-12 col-sm-9">
                                        <div class="form-group autoheight">
                                            <textarea class="ckeditor form-control" name="message" id="message"><?php echo set_value('message'); ?></textarea>
                                            <?php echo form_error('message'); ?>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-lg-2 col-md-3 col-xs-12 col-sm-3">
                                        <label>Attachments</label>
                                    </div>
                                    <div class="col-lg-10 col-md-9 col-xs-12 col-sm-9">
                                        <div class="form-group autoheight">
                                            <div class="choose-file-wrp">

                                                <?php if (!empty($portal_email_templates)) {
                                                    foreach ($portal_email_templates as $template) { ?>
                                                        <div id="<?php echo $template['sid']; ?>" class="temp-attachment" style="display: none">
                                                            <?php if (sizeof($template['attachments']) > 0) {
                                                                foreach ($template['attachments'] as $attachment) { ?>

                                                                    <div class="invoice-fields">
                                                                        <span class="selected-file"><?php echo $attachment['original_file_name'] ?></span>
                                                                    </div>


                                                                <?php } ?>

                                                            <?php } else { ?>
                                                                <div class="invoice-fields">
                                                                    <span class="selected-file">No Attachments</span>
                                                                </div>
                                                            <?php } ?>
                                                        </div>


                                                <?php    }
                                                } ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-2 col-md-3 col-xs-12 col-sm-3">
                                        <label>Additional Attachments</label>
                                    </div>
                                    <div class="col-lg-10 col-md-9 col-xs-12 col-sm-9">
                                        <div class="form-group autoheight">
                                            <div class="choose-file-wrp">
                                                <input type="file" name="message_attachment" id="message_attachment" class="choose-file">
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="message-action-btn" style="float:right;">
                                            <input type="submit" value="Send Message" class="btn btn-success" id="submit_button" onclick="return validate_form();">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php  //  } //herererere
                    ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css" />
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>
<script type="text/javascript">
    $('input[name="send_invoice"]').change(function(e) {
        var div_to_show = $(this).val();
        display(div_to_show);
    });

    $(document).ready(function() {
        var div_to_show = $('input[name="send_invoice"]').val();
        display(div_to_show);
        $('#message_attachment').on('change', function() {
            var fileName = $(this).val();
            if (fileName.length > 0) {
                $(this).prev().html(fileName.substring(0, 45));
            } else {
                $(this).prev().html('No file selected');
            }
        });
    });

    function display(div_to_show) {
        $('input[name="subject"]').prop('disabled', false);
        $("#message").removeAttr("disabled");
        $("#submit_button").removeAttr("disabled");

        if (div_to_show == 'to_admin') {
            $('#to_employer_div').show();
            $("#toemail").prop('required', false);
            $('#to_email_div').hide();
            $('#to_employees_div').hide();
            $('#to_applicants_div').hide();
            //$('#contact_name').hide();
        } else if (div_to_show == 'to_email') {
            $('#to_employer_div').hide();
            $('#to_email_div').show();
            $("#toemail").prop('required', true);
            $('#to_employees_div').hide();
            $('#to_applicants_div').hide();
            //$('#contact_name').show();
        } else if (div_to_show == 'to_employees') {
            $('#to_employer_div').hide();
            $("#toemail").prop('required', false);
            $('#to_email_div').hide();
            $('#to_employees_div').show();
            $('#to_applicants_div').hide();
            //$('#contact_name').hide();
            // disable fields if array is empty
            var emp_size = '<?php echo sizeof($employees); ?>';

            if (emp_size <= 0) {
                $('input[name="subject"]').prop('disabled', true);
                $("#message").attr("disabled", "disabled");
                $("#submit_button").attr("disabled", "disabled");
            }
        } else if (div_to_show == 'to_applicants') {
            $('#to_employer_div').hide();
            $("#toemail").prop('required', false);
            $('#to_email_div').hide();
            $('#to_employees_div').hide();
            $('#to_applicants_div').show();
            //$('#contact_name').hide();
            // disable fields if array is empty
            var app_size = '<?php echo sizeof($applicants); ?>';

            if (app_size <= 0) {
                $('input[name="subject"]').prop('disabled', true);
                $("#message").attr("disabled", "disabled");
                $("#submit_button").attr("disabled", "disabled");
            }
        }
    }

    // Multiselect
    var config = {
        '.chosen-select': {}
    }

    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }

    // validate form for empty selects
    function validate_form() {
        var div_to_show = $('input[name=send_invoice]:checked', '#compose_message_form').val()

        if (div_to_show == 'to_applicants') {
            var items_length = $('#applicants :selected').length;

            if (items_length == 0) {
                alertify.alert('No applicant selected', "Please select some applicants");
                return false;
            }
        } else if (div_to_show == 'to_employees') {
            var items_length = $('#employees :selected').length;

            if (items_length == 0) {
                alertify.alert('No employee selected', "Please select some employees");
                return false;
            }
        }
    }

    $('#submit_button').click(function() {
        $("#compose_message_form").validate({
            ignore: [],
            rules: {
                subject: {
                    required: true,
                },
                message: {
                    required: true,
                },
                toemail: {
                    required: function(element) {
                        return $('input[name=send_invoice]:checked', '#compose_message_form').val() == 'to_email';
                    },
                    //                        multiemails: {
                    //                            depends : function(element) {
                    //                                return $('input[name=send_invoice]:checked', '#compose_message_form').val() == 'to_email';
                    //                            }
                    //                        }
                }
                //                contact_name: {required: function (element) {
                //                        return $('input[name=send_invoice]:checked', '#compose_message_form').val() == 'to_email';
                //                    }
                //                }
            },
            messages: {
                subject: {
                    required: 'Subject is required',
                },
                message_body: {
                    required: 'Message body is required',
                },
                //                contact_name: {
                //                    required: 'Contact name is required',
                //                }
                toemail: {
                    required: "Email address is required.",
                    //                        multiemails: "You must enter a valid email, or comma separate multiple emails"
                }
            },
            submitHandler: function(form) {
                $('#candidate-loader').show();
                $('#submit_button').addClass('disabled-btn');
                $('#submit_button').prop('disabled', true);
                form.submit();
            }
        });
    });

    $(document).ready(function() {
        $.validator.addMethod(
            "multiemails",
            function(value, element) {
                if (this.optional(element)) // return true on optional element
                    return true;
                var emails = value.split(/[;,]+/); // split element by , and ;
                valid = true;
                for (var i in emails) {
                    value = emails[i];
                    valid = valid && jQuery.validator.methods.email.call(this, $.trim(value), element);
                }
                return valid;
            },
            $.validator.messages.multiemails
        );
    });

    //
    $('#template').on('change', function() {
        var template_sid = $(this).val();
        var msg_subject = $('#template_' + template_sid).attr('data-subject');
        var msg_body = $('#template_' + template_sid).attr('data-body');
        $('#email_subject').val(msg_subject);
        $('.jsSubject').val(msg_subject);
        CKEDITOR.instances.message.setData(msg_body);
        $('.temp-attachment').hide();
        $('#' + template_sid).show();
    });
</script>