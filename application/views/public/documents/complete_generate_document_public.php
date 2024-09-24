<?php if ($document['document_type'] != 'generated' && $document['document_type'] != 'hybrid_document') return ''; ?>

<!-- For Generated Documents -->
<div class="row" style="margin-top: 20px;">
    <div class="col-sm-12">
        <div>
            <div id="jstopdf2">
                <?= html_entity_decode($document['document_description']); ?>
            </div>
            <div id="jstopdf" style="display: none;">
                <?= html_entity_decode($document['print_content']); ?>
            </div>

            <?php if ($document['save_offer_letter_type'] == 'save_only') { ?>
                <form id="user_consent_form" enctype="multipart/form-data" method="post" action="">
                    <input type="hidden" name="perform_action" value="sign_document" />
                    <input type="hidden" name="page_content" value="">
                    <input type="hidden" name="save_signature" id="save_signature" value="no">
                    <input type="hidden" name="save_signature_initial" id="save_signature_initial" value="no">
                    <input type="hidden" name="save_signature_date" id="save_signature_date" value="no">
                    <input type="hidden" name="save_PDF" id="save_PDF" value="yes">
                    <input type="hidden" name="save_input_values" id="save_input_values" value="">
                    <input type="hidden" name="user_consent" value="1">
                    <input type="hidden" name="document_sid" value="<?= $document['sid']; ?>">
                    <?php $consent = isset($document['user_consent']) ? $document['user_consent'] : 0;

                    if ($consent == 0) { ?>
                        <div class="row">
                            <div class="col-lg-12 text-center">
                                <button onclick="func_save_document_only();" type="button" class="btn blue-button break-word-text disabled_consent_btn">Save Document</button>
                            </div>
                        </div>
                    <?php } ?>
                    <hr />
                </form>
            <?php } else if ($document['save_offer_letter_type'] == 'consent_only') { ?>
                <form id="user_consent_form" enctype="multipart/form-data" method="post" action="<?= current_url(); ?>">
                    <input type="hidden" name="perform_action" value="sign_document" />
                    <input type="hidden" name="page_content" value="">
                    <input type="hidden" name="save_signature" id="save_signature" value="yes">
                    <input type="hidden" name="save_signature_initial" id="save_signature_initial" value="yes">
                    <input type="hidden" name="save_signature_date" id="save_signature_date" value="yes">
                    <input type="hidden" name="save_PDF" id="save_PDF" value="yes">
                    <input type="hidden" name="document_sid" value="<?= $document['sid']; ?>">
                    <input type="hidden" name="save_input_values" id="save_input_values" value="">
                    <hr />

                    <div class="row">
                        <div class="col-xs-12 text-justify">
                            <?php
                            echo '<p>' . str_replace("{{company_name}}", $session['company_detail']['CompanyName'], SIGNATURE_CONSENT_HEADING) . '</p>';
                            echo '<p>' . SIGNATURE_CONSENT_TITLE . '</p>';
                            echo '<p>' . str_replace("{{company_name}}", $session['company_detail']['CompanyName'], SIGNATURE_CONSENT_DESCRIPTION) . '</p>';
                            ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <label class="control control--checkbox">
                                <?php echo SIGNATURE_CONSENT_CHECKBOX; ?>
                                <input data-rule-required="true" type="checkbox" id="user_consent" name="user_consent" value="1" />
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-lg-12 text-center">
                            <button onclick="func_save_e_signature();" type="button" class="btn blue-button break-word-text disabled_consent_btn"><?php echo SIGNATURE_CONSENT_BUTTON; ?></button>
                        </div>
                    </div>
                    <hr />
                </form>
            <?php } ?>
        </div>
    </div>
</div>

<!--  -->
<input type="hidden" id="is_signature" value="false">
<input type="hidden" id="is_signature_initial" value="false">
<input type="hidden" id="is_signature_date" value="false">
<?php $this->load->view('static-pages/e_signature_popup'); ?>

<script>
    //
    $(document).ready(function() {
        if ($('#jstopdf2').find('select').length >= 0) {
            $('#jstopdf2').find('select').map(function(i) {
                //
                $(this).addClass('js_select_document');
                $(this).prop('name', 'selectDD' + i);
            });
        }
    });
    //
    <?php if ($document['signature_required'] == 1) { ?> requiredC = true;
    <?php } ?>
    //
    $(".js_last_work_date").datepicker({
        format: "MM/DD/YYYY",
        changeYear: true,
        changeMonth: true
    });

    //
    $(".js_date_of_occurrence").datepicker({
        format: "MM/DD/YYYY",
        changeYear: true,
        changeMonth: true
    });
    //
    $('.date_picker2').datepicker({
        dateFormat: 'mm-dd-yy',
        changeMonth: true,
        changeYear: true,
        yearRange: "<?php echo DOB_LIMIT; ?>"
    });
    // On submit
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

            let manualRequired = false;

            if ($('input.js_employee_name').length && !$('input.js_employee_name').val()) {
                manualRequired = true;
            }
            if ($('input.employee_job_title').length && !$('input.employee_job_title').val()) {
                manualRequired = true;
            }
            if ($('input.js_supervisor').length && !$('input.js_supervisor').val()) {
                manualRequired = true;
            }
            if ($('input.js_department').length && !$('input.js_department').val()) {
                manualRequired = true;
            }
            if ($('input.js_last_work_date').length && !$('input.js_last_work_date').val()) {
                manualRequired = true;
            }
            if ($('textarea.js_reason_to_leave_company').length && !$('textarea.js_reason_to_leave_company').val()) {
                manualRequired = true;
            }
            if ($('textarea.js_forwarding_information').length && !$('textarea.js_forwarding_information').val()) {
                manualRequired = true;
            }
            if ($('input.js_date_of_occurrence').length && !$('input.js_date_of_occurrence').val()) {
                manualRequired = true;
            }
            if ($('textarea.js_summary_of_violation').length && !$('textarea.js_summary_of_violation').val()) {
                manualRequired = true;
            }
            if ($('textarea.js_summary_of_corrective_plan').length && !$('textarea.js_summary_of_corrective_plan').val()) {
                manualRequired = true;
            }
            if ($('textarea.js_follow_up_dates').length && !$('textarea.js_follow_up_dates').val()) {
                manualRequired = true;
            }

            if ($('[name="counselling_form_fields[]"]').length) {
                let selectedCounselling = $('[name="counselling_form_fields[]"]:checked').map(function() {
                    return $(this).val()
                }).get()

                if (!selectedCounselling.length) {
                    manualRequired = true;
                } else if ($.inArray("Other", selectedCounselling) !== -1 && !$(".js_counselling_form_fields_textarea").val()) {
                    manualRequired = true;
                }
            }

            if ($('textarea.js_q1').length && !$('textarea.js_q1').val()) {
                manualRequired = true;
            }
            if ($('input.js_employee_number').length && !$('input.js_employee_number').val()) {
                manualRequired = true;
            }
            if ($('textarea.js_q2').length && !$('textarea.js_q2').val()) {
                manualRequired = true;
            }
            if ($('textarea.js_q3').length && !$('textarea.js_q3').val()) {
                manualRequired = true;
            }
            if ($('textarea.js_q4').length && !$('textarea.js_q4').val()) {
                manualRequired = true;
            }
            if ($('textarea.js_q5').length && !$('textarea.js_q5').val()) {
                manualRequired = true;
            }

            if (is_sign == 'true' && is_init == 'true' && is_date == 'true' && !manualRequired) {
                var input_values_obj = {};
                if ($('input.js_employee_name').length) {
                    input_values_obj["employee_name"] = $('input.js_employee_name').val();
                }
                if ($('input.js_employee_job_title').length) {
                    input_values_obj["employee_job_title"] = $('input.js_employee_job_title').val();
                }
                if ($('input.js_supervisor').length) {
                    input_values_obj["supervisor"] = $('input.js_supervisor').val();
                }
                if ($('input.js_department').length) {
                    input_values_obj["department"] = $('input.js_department').val();
                }
                if ($('input.js_last_work_date').length) {
                    input_values_obj["last_work_date"] = $('input.js_last_work_date').val();
                }
                if ($('textarea.js_reason_to_leave_company').length) {
                    input_values_obj["reason_to_leave_company"] = $('textarea.js_reason_to_leave_company').val();
                }
                if ($('textarea.js_forwarding_information').length) {
                    input_values_obj["forwarding_information"] = $('textarea.js_forwarding_information').val();
                }
                if ($('input.js_date_of_occurrence').length) {
                    input_values_obj["date_of_occurrence"] = $('input.js_date_of_occurrence').val();
                }
                if ($('textarea.js_summary_of_violation').length) {
                    input_values_obj["summary_of_violation"] = $('textarea.js_summary_of_violation').val();
                }
                if ($('textarea.js_summary_of_corrective_plan').length) {
                    input_values_obj["summary_of_corrective_plan"] = $('textarea.js_summary_of_corrective_plan').val();
                }
                if ($('textarea.js_follow_up_dates').length) {
                    input_values_obj["follow_up_dates"] = $('textarea.js_follow_up_dates').val();
                }
                if ($('[name="counselling_form_fields[]"]').length) {
                    input_values_obj["counselling_form_fields"] = $('[name="counselling_form_fields[]"]:checked').map(function() {
                        return $(this).val()
                    }).get()

                    if ($.inArray("Other", input_values_obj["counselling_form_fields"]) !== -1) {
                        input_values_obj["counselling_form_fields_textarea"] = $(".js_counselling_form_fields_textarea").val()
                    }
                }

                if ($('input.js_employee_number').length) {
                    input_values_obj["employee_number"] = $('input.js_employee_number').val();
                }
                if ($('textarea.js_q1').length) {
                    input_values_obj["q1"] = $('textarea.js_q1').val();
                }
                if ($('textarea.js_q2').length) {
                    input_values_obj["q2"] = $('textarea.js_q2').val();
                }
                if ($('textarea.js_q3').length) {
                    input_values_obj["q3"] = $('textarea.js_q3').val();
                }
                if ($('textarea.js_q4').length) {
                    input_values_obj["q4"] = $('textarea.js_q4').val();
                }
                if ($('textarea.js_q5').length) {
                    input_values_obj["q5"] = $('textarea.js_q5').val();
                }

                //
                let dataRequiredFlag = false;
                $(".validation").remove();

                $('#jstopdf2 input.short_textbox').map(function() {
                    if ($(this).data("required") == "yes" && !this.value.length) {
                        $(this).parent().after("<div class='validation' style='color:red;margin-bottom: 20px;'>This data field is required!</div>");
                        dataRequiredFlag = true;
                    }
                    //
                    input_values_obj[this.name] = this.value;
                }).get();

                $('#jstopdf2 input.long_textbox').map(function() {
                    if ($(this).data("required") == "yes" && !this.value.length) {
                        $(this).parent().after("<div class='validation' style='color:red;margin-bottom: 20px;'>This data field is required!</div>");
                        dataRequiredFlag = true;
                    }
                    //
                    input_values_obj[this.name] = this.value;
                }).get();

                $('#jstopdf2 input#signature_person_name').map(function() {
                    input_values_obj[this.name] = this.value;
                }).get();

                $('#jstopdf2 input.jsCheckbox').map(function() {
                    //
                    if ($(this).data("required") == "yes") {
                        let name = $(this).data("name");
                        //
                        if (!$('input[name="' + name + '1"]:checked').length && !$('input[name="' + name + '2"]:checked').length) {
                            $(this).after("<div class='validation' style='color:red;margin-bottom: 20px;'>These check-box field is required!</div>");
                            dataRequiredFlag = true;
                        } else if ($('input[name="' + name + '1"]:checked').length && $('input[name="' + name + '2"]:checked').length) {
                            $(this).after("<div class='validation' style='color:red;margin-bottom: 20px;'>Please select only one check-box!</div>");
                            dataRequiredFlag = true;
                        } else if ($('input[name="' + name + '1"]:checked').length) {
                            input_values_obj[name] = 'yes';
                        } else if ($('input[name="' + name + '2"]:checked').length) {
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

                $('#jstopdf2 textarea.text_area').map(function() {
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
                        input_values_obj[this.name] = multi_select_values == null ? multi_select_values : multi_select_values.toString()
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
                        $('.js-hybrid-iframe').remove();
                        var draw = kendo.drawing;
                        //
                        draw.drawDOM($("#jstopdf2"), {
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
                        alertify.error('Canceled!');
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

                //
                if ($('input.js_employee_name').length) {
                    validation["Your Name"] = $('input.js_employee_name').val() || "false"
                }
                //
                if ($('input.employee_job_title').length) {
                    validation["Job Title"] = $('input.employee_job_title').val() || "false"
                }
                //
                if ($('input.js_supervisor').length) {
                    validation["Supervisor"] = $('input.js_supervisor').val() || "false"
                }
                //
                if ($('input.js_department').length) {
                    validation["Department"] = $('input.js_department').val() || "false"
                }
                //
                if ($('input.js_last_work_date').length) {
                    validation["Last Work Date"] = $('input.js_last_work_date').val() || "false"
                }
                //
                if ($('textarea.js_reason_to_leave_company').length) {
                    validation["Reason to the company"] = $('textarea.js_reason_to_leave_company').val() || "false"
                }
                //
                if ($('textarea.js_forwarding_information').length) {
                    validation["Forwarding information"] = $('textarea.js_forwarding_information').val() || "false"
                }


                if ($('input.js_date_of_occurrence').length) {
                    validation["Date Of Occurence"] = $('input.js_date_of_occurrence').val() || "false"
                }
                if ($('textarea.js_summary_of_violation').length) {
                    validation["Summary Of Voilation"] = $('textarea.js_summary_of_violation').val() || "false"
                }
                if ($('textarea.js_summary_of_corrective_plan').length) {
                    validation["Summary Of Corrective Plan"] = $('textarea.js_summary_of_corrective_plan').val() || "false"
                }
                if ($('textarea.js_follow_up_dates').length) {
                    validation["Follow Up Dates"] = $('textarea.js_follow_up_dates').val() || "false"
                }

                if ($('[name="counselling_form_fields[]"]').length) {
                    let selectedCounselling = $('[name="counselling_form_fields[]"]:checked').map(function() {
                        return $(this).val()
                    }).get()

                    if (!selectedCounselling.length) {
                        validation["Counselling Form Fields"] = "false"
                    } else if ($.inArray("Other", selectedCounselling) !== -1 && !$(".js_counselling_form_fields_textarea").val()) {
                        validation["Counselling Form Fields Other"] = "false"
                    }
                }

                if ($('input.js_employee_number').length) {
                    validation["Employee number"] = $('input.js_employee_number').val() || "false"
                }
                if ($('textarea.js_q1').length) {
                    validation["Description of problem"] = $('textarea.js_q1').val() || "false"
                }
                if ($('textarea.js_q2').length) {
                    validation["Description of performance"] = $('textarea.js_q2').val() || "false"
                }
                if ($('textarea.js_q3').length) {
                    validation["Description of consequences for not meeting expectations"] = $('textarea.js_q3').val() || "false"
                }
                if ($('textarea.js_q4').length) {
                    validation["Dates and descriptions of prior discissions or warnings formal or informal"] = $('textarea.js_q4').val() || "false"
                }
                if ($('textarea.js_q5').length) {
                    validation["Other information you would like to provide"] = $('textarea.js_q5').val() || "false"
                }

                for (var key in validation) {
                    if (validation[key] == 'false') {
                        var type = key.replace("_", " ");
                        alertify.error('Please provide ' + type);
                    }
                }
            }
        }
    }
    //
    function func_save_document_only() {
        if ($('#user_consent_form').valid()) {
            let hasErrors = false;

            var validation = {};

            //
            if ($('input.js_employee_name').length) {
                validation["Your Name"] = $('input.js_employee_name').val() || "false"
            }
            //
            if ($('input.employee_job_title').length) {
                validation["Job Title"] = $('input.employee_job_title').val() || "false"
            }
            //
            if ($('input.js_supervisor').length) {
                validation["Supervisor"] = $('input.js_supervisor').val() || "false"
            }
            //
            if ($('input.js_department').length) {
                validation["Department"] = $('input.js_department').val() || "false"
            }
            //
            if ($('input.js_last_work_date').length) {
                validation["Last Work Date"] = $('input.js_last_work_date').val() || "false"
            }
            //
            if ($('textarea.js_reason_to_leave_company').length) {
                validation["Reason to the company"] = $('textarea.js_reason_to_leave_company').val() || "false"
            }
            //
            if ($('textarea.js_forwarding_information').length) {
                validation["Forwarding information"] = $('textarea.js_forwarding_information').val() || "false"
            }


            if ($('input.js_date_of_occurrence').length) {
                validation["Date Of Occurence"] = $('input.js_date_of_occurrence').val() || "false"
            }
            if ($('textarea.js_summary_of_violation').length) {
                validation["Summary Of Voilation"] = $('textarea.js_summary_of_violation').val() || "false"
            }
            if ($('textarea.js_summary_of_corrective_plan').length) {
                validation["Summary Of Corrective Plan"] = $('textarea.js_summary_of_corrective_plan').val() || "false"
            }
            if ($('textarea.js_follow_up_dates').length) {
                validation["Follow Up Dates"] = $('textarea.js_follow_up_dates').val() || "false"
            }

            if ($('[name="counselling_form_fields[]"]').length) {
                let selectedCounselling = $('[name="counselling_form_fields[]"]:checked').map(function() {
                    return $(this).val()
                }).get()

                if (!selectedCounselling.length) {
                    validation["Counselling Form Fields"] = "false"
                } else if ($.inArray("Other", selectedCounselling) !== -1 && !$(".js_counselling_form_fields_textarea").val()) {
                    validation["Counselling Form Fields Other"] = "false"
                }
            }

            if ($('input.js_employee_number').length) {
                validation["Employee number"] = $('input.js_employee_number').val() || "false"
            }
            if ($('textarea.js_q1').length) {
                validation["Description of problem"] = $('textarea.js_q1').val() || "false"
            }
            if ($('textarea.js_q2').length) {
                validation["Description of performance"] = $('textarea.js_q2').val() || "false"
            }
            if ($('textarea.js_q3').length) {
                validation["Description of consequences for not meeting expectations"] = $('textarea.js_q3').val() || "false"
            }
            if ($('textarea.js_q4').length) {
                validation["Dates and descriptions of prior discissions or warnings formal or informal"] = $('textarea.js_q4').val() || "false"
            }
            if ($('textarea.js_q5').length) {
                validation["Other information you would like to provide"] = $('textarea.js_q5').val() || "false"
            }

            //
            if ($('input.js_is_termination_voluntary').length) {
                validation["Voluntary Termination"] = $('input.js_is_termination_voluntary:checked').val() || "false"
            }
            //
            if ($('input.js_property_returned').length) {
                validation["Property returned"] = $('input.js_property_returned:checked').val() || "false"
            }
            //
            if ($('input.js_reemploying').length) {
                validation["Re-employing"] = $('input.js_reemploying:checked').val() || "false"
            }

            for (var key in validation) {
                if (validation[key] == 'false') {
                    hasErrors = true;
                    var type = key.replace("_", " ");
                    alertify.error('Please provide ' + type);
                }
            }
            if (hasErrors) {
                return false;
            }
            var input_values_obj = {};
            if ($('input.js_employee_name').length) {
                input_values_obj["employee_name"] = $('input.js_employee_name').val();
            }
            if ($('input.js_employee_job_title').length) {
                input_values_obj["employee_job_title"] = $('input.js_employee_job_title').val();
            }
            if ($('input.js_supervisor').length) {
                input_values_obj["supervisor"] = $('input.js_supervisor').val();
            }
            if ($('input.js_department').length) {
                input_values_obj["department"] = $('input.js_department').val();
            }
            if ($('input.js_last_work_date').length) {
                input_values_obj["last_work_date"] = $('input.js_last_work_date').val();
            }
            if ($('textarea.js_reason_to_leave_company').length) {
                input_values_obj["reason_to_leave_company"] = $('textarea.js_reason_to_leave_company').val();
            }
            if ($('textarea.js_forwarding_information').length) {
                input_values_obj["forwarding_information"] = $('textarea.js_forwarding_information').val();
            }
            if ($('input.js_date_of_occurrence').length) {
                input_values_obj["date_of_occurrence"] = $('input.js_date_of_occurrence').val();
            }
            if ($('textarea.js_summary_of_violation').length) {
                input_values_obj["summary_of_violation"] = $('textarea.js_summary_of_violation').val();
            }
            if ($('textarea.js_summary_of_corrective_plan').length) {
                input_values_obj["summary_of_corrective_plan"] = $('textarea.js_summary_of_corrective_plan').val();
            }
            if ($('textarea.js_follow_up_dates').length) {
                input_values_obj["follow_up_dates"] = $('textarea.js_follow_up_dates').val();
            }
            if ($('[name="counselling_form_fields[]"]').length) {
                input_values_obj["counselling_form_fields"] = $('[name="counselling_form_fields[]"]:checked').map(function() {
                    return $(this).val()
                }).get()

                if ($.inArray("Other", input_values_obj["counselling_form_fields"]) !== -1) {
                    input_values_obj["counselling_form_fields_textarea"] = $(".js_counselling_form_fields_textarea").val()
                }
            }

            if ($('input.js_employee_number').length) {
                input_values_obj["employee_number"] = $('input.js_employee_number').val();
            }
            if ($('textarea.js_q1').length) {
                input_values_obj["q1"] = $('textarea.js_q1').val();
            }
            if ($('textarea.js_q2').length) {
                input_values_obj["q2"] = $('textarea.js_q2').val();
            }
            if ($('textarea.js_q3').length) {
                input_values_obj["q3"] = $('textarea.js_q3').val();
            }
            if ($('textarea.js_q4').length) {
                input_values_obj["q4"] = $('textarea.js_q4').val();
            }
            if ($('textarea.js_q5').length) {
                input_values_obj["q5"] = $('textarea.js_q5').val();
            }

            if ($('input.js_employee_name').length) {
                input_values_obj["employee_name"] = $('input.js_employee_name').val();
            }
            if ($('input.js_employee_job_title').length) {
                input_values_obj["employee_job_title"] = $('input.js_employee_job_title').val();
            }
            if ($('input.js_supervisor').length) {
                input_values_obj["supervisor"] = $('input.js_supervisor').val();
            }
            if ($('input.js_last_work_date').length) {
                input_values_obj["last_work_date"] = $('input.js_last_work_date').val();
            }
            if ($('input.js_is_termination_voluntary').length) {
                input_values_obj["is_termination_voluntary"] = $('input.js_is_termination_voluntary:checked').val();
            }
            if ($('input.js_property_returned').length) {
                input_values_obj["property_returned"] = $('input.js_property_returned:checked').val();
            }
            if ($('input.js_reemploying').length) {
                input_values_obj["reemploying"] = $('input.js_reemploying:checked').val();
            }

            //
            let dataRequiredFlag = false;
            $(".validation").remove();

            $('#jstopdf2 input.short_textbox').map(function() {
                if ($(this).data("required") == "yes" && !this.value.length) {
                    $(this).parent().after("<div class='validation' style='color:red;margin-bottom: 20px;'>This data field is required!</div>");
                    dataRequiredFlag = true;
                }
                //
                input_values_obj[this.name] = this.value;
            }).get();

            $('#jstopdf2 input.long_textbox').map(function() {
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

            $('#jstopdf2 input.jsCheckbox').map(function() {
                //
                if ($(this).data("required") == "yes") {
                    let name = $(this).data("name");
                    //
                    if (!$('input[name="' + name + '1"]:checked').length && !$('input[name="' + name + '2"]:checked').length) {
                        $(this).after("<div class='validation' style='color:red;margin-bottom: 20px;'>These check-box field is required!</div>");
                        dataRequiredFlag = true;
                    } else if ($('input[name="' + name + '1"]:checked').length && $('input[name="' + name + '2"]:checked').length) {
                        $(this).after("<div class='validation' style='color:red;margin-bottom: 20px;'>Please select only one check-box!</div>");
                        dataRequiredFlag = true;
                    } else if ($('input[name="' + name + '1"]:checked').length) {
                        input_values_obj[name] = 'yes';
                    } else if ($('input[name="' + name + '2"]:checked').length) {
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

            $('#jstopdf2 textarea.text_area').map(function() {
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
                    input_values_obj[this.name] = multi_select_values == null ? multi_select_values : multi_select_values.toString()
                } else {
                    input_values_obj[this.name] = this.value;
                }
            }).get();

            if (hasError) return;

            $('#save_input_values').val(JSON.stringify(input_values_obj));

            alertify.confirm(
                'Are you Sure?',
                'Are you sure you want to Save this Document?',
                function() {
                    $('.js-hybrid-iframe').remove();
                    var draw = kendo.drawing;
                    //
                    draw.drawDOM($("#jstopdf2"), {
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
                    alertify.error('Canceled!');
                }).set('labels', {
                ok: 'I Consent and Accept!',
                cancel: 'Cancel'
            });
        }
    }
</script>