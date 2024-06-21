<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<style>
    .upload-file input[type="file"],
    .upload-file a {
        top: -8px;
        height: 39px;
    }
</style>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>assets/css/chosen.css" />
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>assets/js/chosen.jquery.js"></script>
<div class="main jsmaincontent">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-3">
                        <a href="<?php echo base_url('private_messages'); ?>" class="btn btn-info btn-block csRadius5"><i class="fa fa-arrow-left"></i> back</a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a class="btn btn-success btn-block mb-2" href="<?= base_url('private_messages') ?>"><i class="fa fa-envelope-o"></i> Inbox <?php if ($total_messages > 0) { ?><span>(<?= $total_messages ?>)</span><?php } ?></a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a class="btn btn-success btn-block mb-2" href="<?= base_url('outbox') ?>"><i class="fa fa-inbox"></i> Outbox</a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a class="btn btn-succes-selected btn-block mb-2" href="<?= base_url('compose_message') ?>"><i class="fa fa-pencil-square-o"></i> Compose new Message </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="page-header">
                    <h1 class="section-ttile">Private Messages (<?= $page ?>)</h1>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="dashboard-conetnt-wrp">
                            <div class="table-responsive table-outer">
                                <div class="table-wrp data-table">
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <?php echo form_open_multipart('', array('class' => 'form-horizontal', 'id' => 'compose_message_form')); ?>
                                        <input type="hidden" name="users_type" value="employee">
                                        <?php
                                        if ($page == 'reply') {
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
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                <b>Select a Receiver</b><span class="hr-required red"> * </span>
                                            </div>
                                            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                        <label class="control control--radio">To Admin
                                                            <input type="radio" name="send_invoice" value="to_admin" id="to_employer" checked>
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                        <label class="control control--radio">Email
                                                            <input type="radio" name="send_invoice" value="to_email" id="to_email">
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                        <label class="control control--radio">Employees
                                                            <input type="radio" name="send_invoice" value="to_employees" id="to_employees">
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                    <?php if ($access_level != 'Employee') { ?>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                            <label class="control control--radio">Applicants
                                                                <input type="radio" name="send_invoice" value="to_applicants" id="to_applicants">
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                    <?php           } ?>
                                                </div>
                                            </div>
                                        <?php   } ?>
                                        <table class="table table-bordered table-hover table-stripped">
                                            <tbody>

                                            <?php if(strtolower($this->session->userdata("logged_in")["employer_detail"]["access_level"]) != "employee") {?>

                                                <tr>
                                                    <td>Email Template</td>
                                                    <td> <select class="form-control invoice-fields" name="template" id="template">
                                                            <option id="" data-name="" data-subject="" data-body="" value="">Please Select</option>
                                                            <?php if (!empty($portal_email_templates)) { ?>
                                                                <?php foreach ($portal_email_templates as $template) { ?>
                                                                    <option id="template_<?php echo $template['sid']; ?>" data-name="<?php echo $template['template_name'] ?>" data-subject="<?php echo $template['subject']; ?>" data-body="<?php echo htmlentities($template['message_body']); ?>" value="<?php echo $template['sid']; ?>"><?php echo $template['template_name']; ?></option>
                                                                <?php } ?>
                                                            <?php } else { ?>
                                                                <option id="template_" data-name="" data-subject="" data-body="" value="">No Custom Template Defined</option>
                                                            <?php } ?>
                                                        </select></td>
                                                </tr>
                                                <?php } ?>

                                                <?php if ($page == 'reply') { ?>
                                                    <tr <?php if ($message_type != '1') { ?>style="display: none" <?php } ?> id="to_employer_div">
                                                        <td><b>Message To (E-Mail)</b></td>
                                                        <td><input class="form-control invoice-fields" type="text" value="Admin" name="to-admin" disabled></td>
                                                    </tr>
                                                    <tr <?php if ($message_type == '1') { ?>style="display: none" <?php } ?>id="to_email_div">
                                                        <td><b>Message To</b></td>
                                                        <td><input class="form-control invoice-fields" value="<?php echo $message_type; ?>" id="toemail" type="text" disabled></td>
                                                    </tr>
                                                <?php   } else { ?>
                                                    <tr id="to_employer_div">
                                                        <td><b>Message To</b></td>
                                                        <td><input class="form-control invoice-fields" type="text" value="Admin" name="to-admin" disabled></td>
                                                    </tr>
                                                    <tr style="display: none" id="to_email_div">
                                                        <td><b>Message To (E-Mail)</b></td>
                                                        <td>
                                                            <p>Please enter comma separated values</p>
                                                            <input class="form-control invoice-fields" name="toemail" id="toemail" type="text">
                                                        </td>
                                                    </tr>
                                                    <tr style="display: none" id="to_employees_div">
                                                        <td><b>Select Employees</b></td>
                                                        <td><?php if (sizeof($employees) > 0) { ?>
                                                                <select multiple class="chosen-select" tabindex="8" name='employees[]' id='employees'>
                                                                    <?php foreach ($employees as $employee) {
                                                                        if ($employer_sid == $employee['sid']) {
                                                                            continue;
                                                                        } ?>
                                                                        <option value="<?php echo $employee['sid']; ?>"><?php echo (remakeEmployeeName($employee)) . ' [' . $employee['email'] . ']'; ?></option>
                                                                    <?php                                   } ?>
                                                                </select>
                                                            <?php                           } else { ?>
                                                                <p>No Employee Found.</p>
                                                            <?php                           } ?>
                                                        </td>
                                                    </tr>
                                                    <?php if ($access_level != 'Employee') { ?>
                                                        <tr style="display: none" id="to_applicants_div">
                                                            <td><b>Select Applicants</b></td>
                                                            <td><?php if (sizeof($applicants) > 0) { ?>
                                                                    <select multiple class="chosen-select" tabindex="8" name='applicants[]' id='applicants'>
                                                                        <?php foreach ($applicants as $applicant) { ?>
                                                                            <option value="<?php echo $applicant['sid']; ?>"><?php echo $applicant['first_name'] . ' ' . $applicant['last_name'] . ' ' . $applicant['email']; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                <?php       } else { ?>
                                                                    <p>No Applicant Found.</p>
                                                                <?php       } ?>
                                                            </td>
                                                        </tr>
                                                    <?php           } ?>
                                                <?php   } ?>
                                                <tr>
                                                    <td><b>Subject</b><span class="hr-required red"> * </span></td>
                                                    <td><?php echo form_input('subject', set_value('subject', $message_subject), 'class="form-control invoice-fields" id="email_subject"');
                                                        echo form_error('subject'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td><b>Message</b></td>
                                                    <script type="text/javascript" src="<?php echo site_url('assets/ckeditor/ckeditor.js'); ?>"></script>
                                                    <td><textarea class="ckeditor" style="padding:5px; height:200px; width:100%;" class="invoice-fields" name="message" id="message"><?php echo set_value('message'); ?></textarea>
                                                        <?php echo form_error('message'); ?>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>Attachments</td>
                                                    <td></td>
                                                </tr>

                                                <?php if (!empty($portal_email_templates)) {
                                                    foreach ($portal_email_templates as $template) { ?>

                                                        <tr>
                                                            <td> </td>
                                                            <td>
                                                                <div id="<?php echo $template['sid']; ?>" class="temp-attachment" style="display: none">

                                                                    <table class="table table-bordered table-hover table-stripped">
                                                                        <?php if (sizeof($template['attachments']) > 0) {
                                                                            foreach ($template['attachments'] as $attachment) { ?>
                                                                                <tr>
                                                                                    <td>
                                                                                        <div class="invoice-fields">
                                                                                            <span class="selected-file"><?php echo $attachment['original_file_name'] ?></span>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>

                                                                            <?php } ?>

                                                                        <?php } else { ?>
                                                                            <div class="invoice-fields">
                                                                                <span class="selected-file">No Attachments</span>
                                                                            </div>
                                                                        <?php } ?>
                                                                </div>
                                        </table>
                                        </td>
                                        </tr>

                                <?php    }
                                                } ?>


                                <tr id="dynamicattachment">
                                    <td><b><a href="javascript:;" onclick="addattachmentblock(); return false;" class="add"> + Additional Attachments</a></b></td>
                                    <td>
                                        <div class="upload-file invoice-fields">
                                            <span class="selected-file">No file selected</span>
                                            <input type="file" name="message_attachment[]" id="message_attachment" class="image">
                                            <a href="javascript:;">Choose File</a>
                                        </div>
                                    </td>
                                </tr>
                                <!--                                                    <tr>-->
                                <!--                                                        <td>-->
                                <!--                                                            <a href="javascript:;" onclick="addattachmentblock(); return false;" class="add"> + Attachment</a>-->
                                <!--                                                        </td>-->
                                <!--                                                    </tr>-->

                                <tr>
                                    <td colspan="2">
                                        <div class="btn-wrp full-width text-right">
                                            <a class="btn btn-black margin-right" href="<?php base_url('compose_message'); ?>">cancel</a>
                                            <input type="submit" value="Send Message" class="btn btn-info" id="submit_button" onclick="return validate_form();">
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
            <!-- <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4"> -->
            <?php //$this->load->view('manage_employer/employee_hub_right_menu'); 
            ?>
            <!-- </div> -->
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
        //            $('.image').on('change',function(){
        //                var fileName = $(this).val();
        //                if (fileName.length > 0) {
        //                    $(this).prev().html(fileName.substring(0, 45));
        //                } else {
        //                    $(this).prev().html('No file selected');
        //                }
        //            });
        $(document).on('change', '.image', function() {
            var fileName = $(this).val();
            if (fileName.length > 0) {
                $(this).prev().html(fileName.substring(0, 45));
            } else {
                $(this).prev().html('No file selected');
            }
        });


        $('#template').on('change', function() {
            var template_sid = $(this).val();
            var msg_subject = $('#template_' + template_sid).attr('data-subject');
            var msg_body = $('#template_' + template_sid).attr('data-body');
            $('#email_subject').val(msg_subject);
            CKEDITOR.instances.message.setData(msg_body);
            $('.temp-attachment').hide();
            $('#' + template_sid).show();
        });

        $('#message_attachment').on('change', function() {
            var fileName = $(this).val();
            if (fileName.length > 0) {
                $(this).prev().html(fileName.substring(0, 45));
            } else {
                $(this).prev().html('No file selected');
            }
        });


    });


    var i = 1;

    function addattachmentblock() {
        var container_id = "message_attachment_container" + i;
        var id = "message_attachment" + i;
        //            $('#dynamicattachment').after('<td><b>Attachment</td></b><td><span id="name_'+id+'" class="selected-file">No file selected</span><input type="file" name="'+id+'" id="'+id+'" class="image"><a href="javascript:;">Choose File</a></div><div class="delete-row"><a href="javascript:;" onclick="deleteAnswerBlock(\'' + container_id + '\'); return false;" class="remove">Delete</a></div></td>');
        $('#dynamicattachment').after('<tr id="' + i + '"><td><b class="btn btn-danger text-center" onclick="deleteAnswerBlock(' + i + '); return false;">Delete</b></td><td><div class="upload-file invoice-fields"><span class="selected-file">No file selected</span><input type="file" name="message_attachment[]" id="' + id + '" class="image"><a href="javascript:;">Choose File</a></div></td></tr>');
        //            $('#' + container_id).html($('#' + container_id).html() + '<td><b>Attachment</td></b><div class="upload-file invoice-fields"><span id="name_'+id+'" class="selected-file">No file selected</span><input type="file" name="'+id+'" id="'+id+'" class="image"><a href="javascript:;">Choose File</a></div><div class="delete-row"><a href="javascript:;" onclick="deleteAnswerBlock(\'' + container_id + '\'); return false;" class="remove">Delete</a></div></tr>');

        i++;
    }

    function deleteAnswerBlock(id) {
        console.log('Delete it: ' + id);
        $('#' + id).remove();
    }

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

    var config = { // Multiselect
        '.chosen-select': {}
    }

    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }

    function validate_form() { // validate form for empty selects
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
                    }
                }
            },
            messages: {
                subject: {
                    required: 'Subject is required',
                },
                message_body: {
                    required: 'Message body is required',
                },
                toemail: {
                    required: "Email address is required.",
                }
            },
            submitHandler: function(form) {
                $('#candidate-loader').show();
                $('#submit_button').addClass('disabled-btn');
                $('#submit_button').prop('disabled', true);
                form.submit();
            }
        })
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
</script>