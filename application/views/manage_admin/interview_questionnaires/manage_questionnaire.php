<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-question-circle"></i><?php echo $subtitle; ?></h1>
                                        <a href="<?php echo base_url('manage_admin/interview_questionnaires/')?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Interview Questionnaires</a>
                                    </div>
                                    <div class="add-new-company">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <h4 class=""><strong>Questionnaire Name: </strong> <?php echo $questionnaire['title']; ?>
                                                            <br />
                                                            <!--<small>
                                                                <?php /*echo $questionnaire['short_description']; */?>
                                                            </small>-->
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
                                                                        <th class="text-center" style="height: 200px; vertical-align: middle;">Candidate Information will be displayed here</th>
                                                                    </tr>
                                                                </thead>
                                                            </table>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <div class="heading-title">
                                                            <span class="page-title">Sections</span>
                                                            <button id="add-section" onclick="show_add_questionnaire_section_modal(<?php echo $questionnaire['sid']?>, 0);" class="btn btn-sm btn-success pull-right"><i class="fa fa-plus"></i>&nbsp;Add New Section</button>
                                                        </div>


                                                        <div class="panel-group-wrp">                           
                                                            <div class="panel-group" id="accordion">

                                                                <?php $questionnaire_sections = $questionnaire['sections']; ?>
                                                                <?php if(!empty($questionnaire_sections)) { ?>
                                                                    <?php foreach($questionnaire_sections as $key => $questionnaire_section) { ?>
                                                                        <div class="panel panel-default">                                                                           

                                                                            <div class="panel-heading modal-header-bg">
                                                                                <div class="panel-title">
                                                                                    <div class="row">
                                                                                        <div class="col-lg-7 col-md-12 col-xs-12 col-sm-12">
                                                                                            <a href="javascript:;" class="accordion-toggle btn-block" data-toggle="collapse" data-parent="#accordion" data-target="#collapse<?php echo $key; ?>">
                                                                                                <span class="glyphicon glyphicon-minus"></span>
                                                                                                <?php echo $questionnaire_section['title']; ?>
                                                                                            </a>   
                                                                                        </div>
                                                                                        <div class="col-lg-5 col-md-12 col-xs-12 col-sm-12 question-custom-btn">                                                                                    
                                                                                            <span class="question-btn-custom pull-right">
                                                                                                <form id="form_delete_section_<?php echo $questionnaire_section['sid']; ?>" method="post" enctype="multipart/form-data" action="<?php echo current_url(); ?>">
                                                                                                    <input type="hidden" id="perform_action" name="perform_action" value="delete_section" />
                                                                                                    <input type="hidden" id="section_sid" name="section_sid" value="<?php echo $questionnaire_section['sid']; ?>" />
                                                                                                    <button onclick="func_delete_section(<?php echo $questionnaire_section['sid']; ?>);" type="button" class="btn btn-xs btn-danger pull-left"><i class="fa fa-trash"></i></button>
                                                                                                </form>
                                                                                            </span>
                                                                                            <span class="question-btn-custom pull-right" style="margin-right: 5px;">
                                                                                                <button class="btn btn-xs btn-default pull-left" onclick="show_add_questionnaire_section_modal(<?php echo $questionnaire_section['questionnaire_sid']; ?>, <?php echo $questionnaire_section['sid']; ?>)"><i class="fa fa-pencil"></i></button>
                                                                                            </span>
                                                                                            <span class="question-btn-custom pull-right" style="margin-right: 5px;">
                                                                                                <button class="btn btn-xs btn-default pull-left" onclick="show_add_questionnaire_section_question_modal(<?php echo $questionnaire_section['questionnaire_sid']; ?>, <?php echo $questionnaire_section['sid']; ?>, 0)"><i class="fa fa-plus"></i>&nbsp;Add Question</button>
                                                                                            </span>                                                                     
                                                                                            <span class="question-btn-custom pull-right" style="margin-right: 5px;">
                                                                                                <button class="btn btn-xs btn-default pull-left" onclick="show_add_default_question_modal(<?php echo $questionnaire_section['questionnaire_sid']; ?>, <?php echo $questionnaire_section['sid']; ?>, 0)"><i class="fa fa-plus"></i>&nbsp;Add Question From Template</button>
                                                                                            </span>                                                                                    
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div id="collapse<?php echo $key; ?>" class="panel-collapse collapse in <?php //echo ($key == 0 ? 'in': ''); ?>" style="padding: 0; margin: 0;">
                                                                                <div class="panel-body">
                                                                                    <div class="row">
                                                                                        <div class="col-xs-12">
                                                                                            <?php $questions = $questionnaire_section['candidate_questions']; ?>
                                                                                            <?php $my_data['questions'] = $questions; ?>

                                                                                            <?php $this->load->view('manage_admin/interview_questionnaires/manage_questionnaire_partial', $my_data); ?>

                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    <?php } ?>
                                                                <?php } else { ?>
                                                                    <div class="row">
                                                                        <div class="col-xs-12 text-center">
                                                                            <span class="no-data">No Sections Defined!</span>
                                                                        </div>
                                                                    </div>
                                                                <?php } ?>
                                                            </div>
                                                        </div>


                                                        



                                                        <div class="clearfix"></div>
                                                    </div>
                                                </div>
												<?php $this->load->view('manage_admin/interview_questionnaires/questionnaire_evaluation_partial'); ?>
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
</div>

<script>

    $(document).ready(function () {
        $('.collapse').on('shown.bs.collapse', function () {
            $(this).parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
        }).on('hidden.bs.collapse', function () {
            $(this).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
        });
    });

    function func_remove_question_option(source){
        $(source).closest('.option_row_template').remove();
    }

    function func_add_question_option(){
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

    function show_add_questionnaire_section_question_modal(questionnaire_sid, section_sid, question_sid = 0){
        $('#file_preview_modal #document_modal_body').html('');
        var my_request;

        var data_to_send = { 'perform_action': 'get_questionnaire_section_question_form', 'questionnaire_sid': questionnaire_sid, 'section_sid': section_sid, 'question_sid': question_sid };

        my_request = $.ajax({
            url : '<?php echo base_url("manage_admin/interview_questionnaires/ajax_responder"); ?>',
            type: 'POST',
            data: data_to_send,
            dataType:'json'
        });

        my_request.done(function (response) {
            //console.log(response);
            $('#file_preview_modal .modal-dialog').addClass('modal-lg');

            $('#file_preview_modal #document_modal_body').html(response.html);
            $('#file_preview_modal #document_modal_title').html(response.title);

            $('#file_preview_modal').modal('toggle');
        });
    }

    function show_add_default_question_modal(questionnaire_sid, section_sid, question_sid = 0){
        $('#file_preview_modal #document_modal_body').html('');
        var my_request;

        var data_to_send = { 'perform_action': 'get_default_question_form', 'questionnaire_sid': questionnaire_sid, 'section_sid': section_sid, 'question_sid': question_sid };

        my_request = $.ajax({
            url : '<?php echo base_url("manage_admin/interview_questionnaires/ajax_responder"); ?>',
            type: 'POST',
            data: data_to_send,
            dataType:'json'
        });

        my_request.done(function (response) {
            //console.log(response);
            $('#file_preview_modal .modal-dialog').addClass('modal-lg');

            $('#file_preview_modal #document_modal_body').html(response.html);
            $('#file_preview_modal #document_modal_title').html(response.title);

            $('#file_preview_modal').modal('toggle');
        });
    }


    function show_add_questionnaire_section_modal(questionnaire_sid, section_sid){
        $('#file_preview_modal #document_modal_body').html('');
        var my_request;

        var data_to_send = { 'perform_action': 'add_questionnaire_section', 'questionnaire_sid': questionnaire_sid, 'section_sid': section_sid };

        my_request = $.ajax({
            url : '<?php echo base_url("manage_admin/interview_questionnaires/ajax_responder"); ?>',
            type: 'POST',
            data: data_to_send,
            dataType:'json'
        });

        my_request.done(function (response) {



            $('#file_preview_modal #document_modal_body').html(response.html);
            $('#file_preview_modal #document_modal_title').html(response.title);

            $('#file_preview_modal').modal('toggle');
        });
    }








    function f_save_interview_questionnaire(){
        $('#form_add_new_interview_questionnaire').validate({
            rules:{
                title: {
                    required: true
                }
            },
            messages:{
                title: {
                    required: 'Please type a title for questionnaire!'
                }
            }
        });

        if($('#form_add_new_interview_questionnaire').valid()){
            $('#form_add_new_interview_questionnaire').submit();
        } else {
            console.log('Invalid Form');
        }
    } 
    function func_delete_section(section_sid){
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
    function func_delete_question(question_sid){
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

    function func_save_questionnaire_section(){
        $('#form_add_new_interview_questionnaire_section').validate({
            rules:{
                title: {
                    required: true
                }
            },
            messages:{
                title: {
                    required: 'Please type a title for questionnaire!'
                }
            }
        });

        if($('#form_add_new_interview_questionnaire_section').valid()){

            var data_to_send = func_convert_form_to_json_object('form_add_new_interview_questionnaire_section');

            var my_request;
            my_request = $.ajax({
                url: '<?php echo base_url("manage_admin/interview_questionnaires/ajax_responder"); ?>',
                type: 'POST',
                data: data_to_send,
                requestType: 'json'
            });

            my_request.done(function (response) {
                if(response.status == 'success'){
                    $('#file_preview_modal').modal('toggle');
                    window.location.href = window.location.href;
                } else {
                    alertify.error('Something went wrong');
                }
            });


        } else {
            console.log('Invalid Form');
        }
    }


    function func_convert_form_to_json_object(form_id){
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


    function function_save_question(){

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

        if($('#form_add_question').valid()){
            var data_to_send = func_convert_form_to_json_object('form_add_question');

            console.log(data_to_send);


            var my_request;
            my_request = $.ajax({
                url: '<?php echo base_url("manage_admin/interview_questionnaires/ajax_responder"); ?>',
                type: 'POST',
                data: data_to_send,
                requestType: 'json'
            });

            my_request.done(function (response) {
                if(response.status == 'success'){
                    $('#file_preview_modal').modal('toggle');
                    window.location.href = window.location.href;
                } else {
                    alertify.error('Something went wrong');
                }
            });

        } else {

        }
    }

    function func_add_default_question(){

        var q_checkboxes = $('input[type=checkbox]:checked');

        var total_selected = q_checkboxes.length;


        if(total_selected > 0){
            var data_to_send = func_convert_form_to_json_object('form_add_default_questions');

            console.log(data_to_send);


            var my_request;
            my_request = $.ajax({
                url: '<?php echo base_url("manage_admin/interview_questionnaires/ajax_responder"); ?>',
                type: 'POST',
                data: data_to_send,
                requestType: 'json'
            });

            my_request.done(function (response) {
                if(response.status == 'success'){
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

    $(document).ready(function () {
        $('#file_preview_modal').on('shown.bs.modal', function() {



            $('.answer_required').each(function () {
                $(this).on('click', function () {
                    if($(this).prop('checked')){
                        var selected_option = $(this).val();

                        if(parseInt(selected_option) == 1){
                            $('#answer_container').show('blind');
                            console.log('Yes');
                        } else {
                            $('#answer_container').hide('blind');
                            console.log('No');
                        }
                    }
                });
            });



            $('#answer_type').on('change', function () {
                var selected_option = $(this).val();

                if(selected_option != 'textual' && selected_option != ''){
                    $('#answer_options_container').show('blind');
                } else {
                    $('#answer_options_container').hide('blind');
                }

            });

            $('#answer_type').trigger('change');

        });
    });
</script>

