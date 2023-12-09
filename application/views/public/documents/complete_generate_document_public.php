<?php if ($document['document_type'] != 'generated' && $document['document_type'] != 'hybrid_document') return ''; ?>

<!-- For Generated Documents -->
<div class="row" style="margin-top: 20px;">
    <div class="col-sm-12">
        <div>
            <div id="jstopdf2">

                <?php
                $consentOnly = false;
                if ($document['fillable_documents_slug'] != null && $document['fillable_documents_slug'] != '') { ?>
                    <?php
                    $document_filename = !empty($document['fillable_documents_slug']) ? $document['fillable_documents_slug'] : '';
                    $document_file = pathinfo($document_filename);
                    $document_extension = strtolower($document['document_extension']);
                    //

                    $doc = str_replace('-', '_', $document['fillable_documents_slug']);
                    ?>

                    <?php $this->load->view('v1/fillable_documents/' . $doc, $document); ?>

                    <?php
                    if ($document['signature_required'] == 1) {
                        $consentOnly = true;
                    } else {
                        $consentOnly = false;
                    } ?>

                <?php } else { ?>


                    <?= html_entity_decode($document['document_description']); ?>
                <?php } ?>
            </div>
            <div id="jstopdf" style="display: none;">
                <?= html_entity_decode($document['print_content']); ?>
            </div>

            <?php if ($document['save_offer_letter_type'] == 'save_only' || $consentOnly == false) { ?>
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
            <?php } else if ($document['save_offer_letter_type'] == 'consent_only' || $consentOnly ==true) { ?>
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

            if (is_sign == 'true' && is_init == 'true' && is_date == 'true') {
                var input_values_obj = {};

                $('#jstopdf2 input.short_textbox').map(function() {
                    input_values_obj[this.name] = this.value;
                }).get();

                $('#jstopdf2 input.long_textbox').map(function() {
                    input_values_obj[this.name] = this.value;
                }).get();

                $('#jstopdf2 textarea.long_textbox').map(function() {
                    input_values_obj[this.name] = this.value;
                }).get();


                $('#jstopdf2 input#signature_person_name').map(function() {
                    input_values_obj[this.name] = this.value;
                }).get();

                $('#jstopdf2 input.user_checkbox').map(function() {
                    var condition = 'no';
                    if ($(this).is(":checked")) {
                        condition = 'yes';
                    }

                    input_values_obj[this.name] = condition;
                }).get();

                $('#jstopdf2 textarea.text_area').map(function() {
                    input_values_obj[this.name] = this.value;
                }).get();

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
            var input_values_obj = {};

            $('#jstopdf2 input.short_textbox').map(function() {
                input_values_obj[this.name] = this.value;
            }).get();

            $('#jstopdf2 input.long_textbox').map(function() {
                input_values_obj[this.name] = this.value;
            }).get();


            $('#jstopdf2 textarea.long_textbox').map(function() {
                input_values_obj[this.name] = this.value;
            }).get();

            $('input#signature_person_name').map(function() {
                input_values_obj[this.name] = this.value;
            }).get();

            $('#jstopdf2 input.user_checkbox').map(function() {
                var condition = 'no';
                if ($(this).is(":checked")) {
                    condition = 'yes';
                }

                input_values_obj[this.name] = condition;
            }).get();

            $('#jstopdf2 textarea.text_area').map(function() {
                input_values_obj[this.name] = this.value;
            }).get();

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