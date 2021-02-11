<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <?php $this->load->view('flashmessage/flash_message'); ?>
                <div class="heading-title page-title">
                    <h1 class="page-title"><i class="fa fa-envelope"></i><?php echo $title; ?> (<?= $page ?>)</h1>
                    <a class="black-btn pull-right" href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-long-arrow-left"></i> Back to Dashboard</a>
                </div>
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <div class="bt-panel">
                        <a href="<?php echo base_url('private_messages') . '/' . $company_id; ?>" class="btn btn-success">Inbox <span>(<?= $total_messages ?>)</span></a>
                        <a href="<?php echo base_url('outbox') . '/' . $company_id; ?>" class="btn btn-success">Outbox</a>
                        <a href="<?php echo base_url('compose_message') . '/' . $company_id; ?>" class="btn btn-success">Compose new Message</a>
                    </div>
                </div>
                    <div class="compose-message">
                        <div class="form-wrp">
            <?php       echo form_open_multipart('', array('class' => 'form-horizontal', 'id' => 'compose_message_form')); ?>
                            <input type="hidden" name="users_type" value="employee">
            
            <?php           if ($page == 'reply') {
                                if ($messgae_type_flag == 'email') { ?>
                                    <input type="hidden" name="send_invoice" value="to_email">
                                    <input type="hidden" name="toemail"  value="<?php echo $message_type; ?>">
            <?php           } elseif ($messgae_type_flag == 'employer') { ?>
                                    <input type="hidden" name="send_invoice" value="to_employer">
                                    <input type="hidden" name="toemail"  value="<?php echo $message_type; ?>">
            <?php           } elseif ($messgae_type_flag == 'admin') { ?>
                                    <input type="hidden" name="send_invoice" value="to_admin">
            <?php           } ?>

                            <div class="form-control autoheight" <?php if ($message_type != '1') { ?>style="display: none"  <?php } ?> id="to_employer_div">
                                <div class="row">
                                    <div class="col-md-3"><b>Message To (E-Mail)</b></div>
                                    <div class="col-md-9">
                                        <input class="form-control"  type="text" value="Admin" name="to-admin" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="form-control autoheight"  <?php if ($message_type == '1') { ?>style="display: none"  <?php } ?>id="to_email_div">
                                <div class="row">
                                    <div class="col-md-3"><b>Message To</b></div>
                                    <div class="col-md-9">
                                        <input class="form-control" value="<?php echo $message_type; ?>" id="toemail" type="text"  disabled>
                                    </div>
                                </div>
                            </div>
            <?php       } else { ?>
                            <div class="form-col-100 autoheight">
                                <div class="row">
                                    <div class="col-md-3"><b>Select a Receiver</b><span class="hr-required red"> * </span></div>
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="control control--radio">To Admin
                                                    <input type="radio" name="send_invoice" value="to_admin" id="to_employer" checked>
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="control control--radio">Email
                                                    <input type="radio" name="send_invoice" value="to_email" id="to_email">
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="control control--radio">Employees
                                                    <input type="radio" name="send_invoice" value="to_employees" id="to_employees">
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="control control--radio">Applicants
                                                    <input type="radio" name="send_invoice" value="to_applicants" id="to_applicants">
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-col-100 autoheight" id="to_employer_div">
                                <div class="row">
                                    <div class="col-md-3"><b>Message To </b></div>
                                    <div class="col-md-9">
                                        <input class="form-control invoice-fields"  type="text" value="Admin" name="to-admin" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="form-col-100 autoheight"  style="display: none" id="to_email_div">
                                <div class="row">
                                    <div class="col-md-3"><b>Message To (E-Mail)</b></div>
                                    <div class="col-md-9">
                                        <p>Please enter comma separated values</p>
                                        <input class="hr-form-fileds invoice-fields" name="toemail" id="toemail"  type="text" >
                                    </div>
                                </div>
                            </div>
                            <div class="form-col-100 autoheight" style="display: none" id="to_employees_div">
                                <div class="row">
                                    <div class="col-md-3"><b>Select Employees</b></div>
                                    <div class="col-md-9">
                                        <div class="field-row">
                                            <div class="multiselect-wrp">
                                <?php           if (sizeof($employees) > 0) { ?>
                                                    <select style="width:350px;" multiple class="chosen-select" tabindex="8" name='employees[]' id='employees'>

                                <?php                   foreach ($employees as $employee) {
                                                            if ($employer_sid == $employee['sid']) { continue; } ?>
                                                            <option value="<?php echo $employee['sid']; ?>"><?php echo $employee['first_name'] . ' ' . $employee['last_name'] . ' ' . $employee['email']; ?></option>
                                <?php                   } ?>
                                                    </select>
                                <?php           } else { ?>
                                                    <p>No Employee Found.</p>
                                <?php           } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-col-100 autoheight" style="display: none" id="to_applicants_div">
                                <div class="row">
                                    <div class="col-md-3"><b>Select Applicants</b></div>
                                    <div class="col-md-9">
                                        <div class="field-row">
                                            <div class="multiselect-wrp">
                            <?php               if (sizeof($applicants) > 0) { ?>
                                                    <select style="width:350px;" multiple class="chosen-select" tabindex="8" name='applicants[]' id='applicants'>
                            <?php                       foreach ($applicants as $applicant) { ?>
                                                            <option value="<?php echo $applicant['sid']; ?>"><?php echo $applicant['first_name'] . ' ' . $applicant['last_name'] . ' ' . $applicant['email']; ?></option>
                            <?php                       } ?>
                                                    </select>
                            <?php               } else { ?>
                                                    <p>No Applicant Found.</p>
                            <?php               } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
            <?php       } ?>
                        <div class="form-col-100 autoheight">
                            <div class="row">
                                <div class="col-md-3"><b>Subject</b><span class="hr-required red"> * </span></div>
                                <div class="col-md-9">
                                    <?php
                                    echo form_input('subject', set_value('subject'), 'class="hr-form-fileds invoice-fields"');
                                    echo form_error('subject');
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-col-100 autoheight">
                            <div class="row">
                                <div class="col-md-3"><b>Message</b><span class="hr-required red"> * </span></div>
                                <div class="col-md-9">
                                    <textarea class="ckeditor" style="padding:5px; height:200px; width:100%;" class="invoice-fields" name="message" id="message"><?php echo set_value('message'); ?></textarea>
                                    <?php echo form_error('message'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-col-100 autoheight">
                            <div class="row">
                                <div class="col-md-3"><b>Attachments</b></div>
                                <div class="col-md-9">
                                <div class="upload-file invoice-fields">
                                    <span class="selected-file">No file selected</span>
                                    <input type="file" name="message_attachment" id="message_attachment" class="image">
                                    <a href="javascript:;">Choose File</a>
                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="form-col-100 autoheight">
                            <div class="row">
                                <div class="col-md-3"> </div>
                                <div class="col-md-9">
                                    <div class="custom_loader pull-left">
                                        <div id="candidate-loader" class="candidate-loader" style="display: none">
                                            <i style="font-size: 25px; color: #81b431;" class="fa fa-cog fa-spin"></i>
                                            <span>Sending...</span>
                                        </div>
                                    </div>
                                    <div class="message-action-btn"> <!-- name="submit" -->
                                        <input type="submit" value="Send Message" class="submit-btn" id="submit_button" onclick="return validate_form();">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                            <!--</ul>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css"  />
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>
<script type="text/javascript">
        $('input[name="send_invoice"]').change(function (e) {
            var div_to_show = $(this).val();
            display(div_to_show);
        });
        
        $(document).ready(function () {
            var div_to_show = $('input[name="send_invoice"]').val();
            display(div_to_show);
            $('#message_attachment').on('change',function(){
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

        $('#submit_button').click(function () {
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
                        required: function (element) {
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
                submitHandler: function (form) {
                    $('#candidate-loader').show();
                    $('#submit_button').addClass('disabled-btn');
                    $('#submit_button').prop('disabled', true);
                    form.submit();
                }
            });
        });

    $(document).ready(function(){
        $.validator.addMethod(
                "multiemails",
                function (value, element) {
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