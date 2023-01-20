<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/employer_column_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow">
                                    <a class="dashboard-link-btn" href="<?php echo base_url('interview_questionnaire'); ?>"><i class="fa fa-chevron-left"></i>Interview Questionnaires</a>
                                    <?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <?php echo $title; ?>
                                </span>
                            </div>
                            <div class="dashboard-conetnt-wrp">

                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="row">
                                            <div class="col-xs-12">

                                                <h4 class=""><strong>Questionnaire Name: </strong> <?php echo $questionnaire['title']; ?>

                                                </h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="alert alert-info alert-dismissible">
                                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                                    Modifying Interview Questionnaire will not effect any <strong>Previously conducted / Scored Interviews</strong>, All changes / Modifications will be reflected in <strong>New Interviews</strong>.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12">

                                                <div class="table-responisve">
                                                    <table class="table table-bordered table-stripped table-hover">
                                                        <thead>
                                                        <tr>
                                                            <th class="text-center" style="vertical-align: middle;"><span class="no-data">Applicant Information will be displayed here</span></th>
                                                        </tr>
                                                        </thead>
                                                    </table>
                                                </div>

                                            </div>
                                        </div>
                                        <?php $this->load->view('interview_questionnaires/manage_questionnaire_partial'); ?>
                                        <?php $this->load->view('interview_questionnaires/questionnaire_evaluation_partial'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function func_remove_question_option(source) {
        $(source).closest('.option_row_template').remove();
    }

    function func_add_question_option() {
        var question_option = $('#option_row_template').clone();

        $(question_option).removeAttr('id');
        $(question_option).find('button').removeClass('disabled');
        $(question_option).find('button').prop('disabled', false);
        $(question_option).find('input[type="text"]').val('');
        $(question_option).find('input[type="text"]').removeAttr('data-rule-required');
        $(question_option).find('input[type="text"]').removeAttr('aria-required');
        $(question_option).find('input[type="text"]').removeAttr('aria-invalid');
        $(question_option).find('input[type="text"]').removeClass('valid');
        $(question_option).find('input[type="text"]').removeClass('error');
        $(question_option).find('input[type="text"]').removeClass('invalid');
        $(question_option).find('label.error').remove();
        $(question_option).find('#add_option_btn').remove();
        $(question_option).appendTo('#options_container');
    }

    function show_add_questionnaire_section_question_modal(questionnaire_sid, section_sid, question_sid = 0) {
        $('#popupmodal #popupmodalbody').html('');
        var my_request;

        var data_to_send = {
            'perform_action': 'get_questionnaire_section_question_form',
            'questionnaire_sid': questionnaire_sid,
            'section_sid': section_sid,
            'question_sid': question_sid
        };

        my_request = $.ajax({
            url: '<?php echo base_url("interview_questionnaire/ajax_responder"); ?>',
            type: 'POST',
            data: data_to_send,
            dataType: 'json'
        });

        my_request.done(function (response) {
            //console.log(response);
            $('#popupmodal .modal-dialog').addClass('modal-lg');

            $('#popupmodal #popupmodalbody').html(response.html);
            $('#popupmodal #popupmodallabel').html(response.title);

            $('#popupmodal').modal('toggle');
        });
    }

    function show_add_questionnaire_section_modal(questionnaire_sid, section_sid) {
        $('#popupmodal #popupmodalbody').html('');
        var my_request;

        var data_to_send = {
            'perform_action': 'add_questionnaire_section',
            'questionnaire_sid': questionnaire_sid,
            'section_sid': section_sid
        };

        my_request = $.ajax({
            url: '<?php echo base_url("interview_questionnaire/ajax_responder"); ?>',
            type: 'POST',
            data: data_to_send,
            dataType: 'json'
        });

        my_request.done(function (response) {

            $('#popupmodal .modal-dialog').addClass('modal-lg');

            $('#popupmodal #popupmodalbody').html(response.html);
            $('#popupmodal #popupmodallabel').html(response.title);

            $('#popupmodal').modal('toggle');
        });
    }

    function f_save_interview_questionnaire() {
        $('#form_add_new_interview_questionnaire').validate({
            rules: {
                title: {
                    required: true
                }
            },
            messages: {
                title: {
                    required: 'Please type a title for questionnaire!'
                }
            }
        });

        if ($('#form_add_new_interview_questionnaire').valid()) {
            $('#form_add_new_interview_questionnaire').submit();
        } else {
            console.log('Invalid Form');
        }
    }

    function func_delete_section(section_sid) {
        alertify.confirm(
            'Are You Sure?',
            'Are you sure you want to delete this Section?',
            function () {
                $('#form_delete_section_' + section_sid).submit();
            },
            function () {
                alertify.error('Cancelled');
            });
    }

    function func_delete_question(question_sid) {
        alertify.confirm(
            'Are You Sure?',
            'Are you sure you want to delete this Question?',
            function () {
                $('#form_delete_question_' + question_sid).submit();
            },
            function () {
                alertify.error('Cancelled');
            });
    }

    function func_save_questionnaire_section() {
        $('#form_add_new_interview_questionnaire_section').validate({
            rules: {
                title: {
                    required: true
                }
            },
            messages: {
                title: {
                    required: 'Please type a title for questionnaire!'
                }
            }
        });

        if ($('#form_add_new_interview_questionnaire_section').valid()) {

            var data_to_send = func_convert_form_to_json_object('form_add_new_interview_questionnaire_section');

            var my_request;
            my_request = $.ajax({
                url: '<?php echo base_url("interview_questionnaire/ajax_responder"); ?>',
                type: 'POST',
                data: data_to_send,
                requestType: 'json'
            });

            my_request.done(function (response) {
                if (response.status == 'success') {
                    $('#popupmodal').modal('toggle');
                    window.location.href = window.location.href;
                } else {
                    alertify.error('Something went wrong');
                }
            });


        } else {
            console.log('Invalid Form');
        }
    }

    function func_convert_form_to_json_object(form_id) {
        var form_data = $('#' + form_id).serializeArray();

        var my_return = {};

        $.each(form_data, function () {
            if (my_return[this.name] !== undefined) {
                if (!my_return[this.name].push) {
                    my_return[this.name] = [my_return[this.name]];
                }
                my_return[this.name].push(this.value || '');
            } else {
                my_return[this.name] = this.value || '';
            }
        });

        return my_return;
    }

    function function_save_question() {

        $('#form_add_question').validate({
            rules: {
                question_for: {
                    required: true
                },
                question_text: {
                    required: true
                },
                answer_required: {
                    required: true
                }
            }
        });

        if ($('#form_add_question').valid()) {
            var data_to_send = func_convert_form_to_json_object('form_add_question');

            console.log(data_to_send);


            var my_request;
            my_request = $.ajax({
                url: '<?php echo base_url("interview_questionnaire/ajax_responder"); ?>',
                type: 'POST',
                data: data_to_send,
                requestType: 'json'
            });

            my_request.done(function (response) {
                if (response.status == 'success') {
                    $('#popupmodal').modal('toggle');
                    window.location.href = window.location.href;
                } else {
                    alertify.error('Something went wrong');
                }
            });

        } else {

        }
    }

    $(document).ready(function () {
        $('#popupmodal').on('shown.bs.modal', function () {

            $('.answer_required').each(function () {
                $(this).on('click', function () {
                    if ($(this).prop('checked')) {
                        var selected_option = $(this).val();

                        if (parseInt(selected_option) == 1) {
                            $('#answer_container').show('blind');
                            $('#answer_type').trigger('change');
                            console.log('Yes');
                        } else {
                            $('#answer_container').hide('blind');
                            $('#answer_options_container').hide('blind');
                            console.log('No');
                        }
                    }
                });
            });

            $('#answer_type').on('change', function () {
                var selected_option = $(this).val();

                if (selected_option != 'textual' && selected_option != '') {
                    $('#answer_options_container').show('blind');
                } else {
                    $('#answer_options_container').hide('blind');
                }

            });

            $('#answer_type').trigger('change');

            $('input[type=radio]:checked').each(function () {
                console.log(this);
                $(this).trigger('click');
            });

        });
    });

    function show_add_default_question_modal(questionnaire_sid, section_sid, question_sid = 0) {
        $('#popupmodal #popupmodalbody').html('');
        var my_request;

        var data_to_send = {
            'perform_action': 'get_default_question_form',
            'questionnaire_sid': questionnaire_sid,
            'section_sid': section_sid,
            'question_sid': question_sid
        };

        my_request = $.ajax({
            url: '<?php echo base_url("interview_questionnaire/ajax_responder"); ?>',
            type: 'POST',
            data: data_to_send,
            dataType: 'json'
        });

        my_request.done(function (response) {
            //console.log(response);
            $('#popupmodal .modal-dialog').addClass('modal-lg');

            $('#popupmodal #popupmodalbody').html(response.html);
            $('#popupmodal #popupmodallabel').html(response.title);

            $('#popupmodal').modal('toggle');
        });
    }

    function func_add_default_question() {

        var q_checkboxes = $('input[type=checkbox]:checked');

        var total_selected = q_checkboxes.length;


        if (total_selected > 0) {
            var data_to_send = func_convert_form_to_json_object('form_add_default_questions');

            console.log(data_to_send);


            var my_request;
            my_request = $.ajax({
                url: '<?php echo base_url("interview_questionnaire/ajax_responder"); ?>',
                type: 'POST',
                data: data_to_send,
                requestType: 'json'
            });

            my_request.done(function (response) {
                if (response.status == 'success') {
                    $('#file_preview_modal').modal('toggle');
                    window.location.href = window.location.href;
                } else {
                    alertify.error('Something went wrong');
                }
            });


        } else {
            alertify.error('Please select Questions to add!');
        }
    }
</script>
