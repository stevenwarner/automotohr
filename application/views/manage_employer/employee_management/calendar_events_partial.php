<div class="form-wrp">
    <form action="<?php echo base_url('calendar/tasks'); ?>" name="event_form" id="add_event_form" method="POST" enctype="multipart/form-data">
        <div class="form-title-section">
            <h2>Calendar & Scheduling</h2>
            <div class="form-btns event_detail">
                <input type="button" id="add_event" value="Add Event">
            </div>
            <div class="form-btns event_create" style="display: none">
                <input type="submit" value="Save">
                <input onclick="cancel_event()" type="button" value="Cancel">
            </div>
        </div>

        <input type="hidden" name="employee_sid" value="<?= $id ?>">
        <input type="hidden" name="applicant_sid" value="0">
        <input type="hidden" name="employers_sid" value="<?= $user_sid ?>">
        <input type="hidden" name="users_type" value="employee">
        <input type="hidden" name="applicant_email" value="<?= $email ?>">
        <input type="hidden" name="action" value="save_event">

        <input type="hidden" name="redirect_to" value="employee_profile">
        <input type="hidden" name="redirect_to_user_sid" value="<?php echo $employer_id; ?>">

        <?php $this->load->view('calendar/add_event_form_partial'); ?>
    </form>
</div>
<div class="event_detail">
    <div class="row">
        <div class="col-xs-12">
            <ul class="nav nav-tabs nav-justified">
                <li class="active"><a data-toggle="tab" href="#upcoming_events">Upcoming Events</a></li>
                <li onclick="func_get_past_events('employee', <?php echo $employer_id; ?>)"><a data-toggle="tab" href="#past_events">Past Events</a></li>
            </ul>
            <div class="tab-content">
                <div id="upcoming_events" class="tab-pane fade in active hr-innerpadding">
                    <div class="row">
                        <div class="col-xs-12">
                            <br />

                            <?php

                            $view_data = array();
                            $view_data['events'] = $upcoming_events;
                            $view_data['employees'] = $company_accounts;
                            $view_data['employer_id'] = $employer_id;

                            $this->load->view('calendar/list_events_partial', $view_data);

                            ?>

                        </div>
                    </div>
                </div>
                <div id="past_events" class="tab-pane fade">
                    <div class="row">
                        <div class="col-xs-12">
                            <div id="tab_loader" class="text-center" style="height: 400px; padding: 100px; color: rgb(77, 160, 0);">
                                <i class="fa fa-spin fa-cog" style="font-size: 200px; opacity: 0.25;"></i>
                            </div>
                            <div id="tab_content">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function func_set_category(input_id, label_id, selected){
        $('#' + input_id).val(selected);
        switch (selected){
            case 'call':
                $('#' + label_id).html('Call');
                $('.comment_for').html('Participant(s)');
                $('.attendees_label').html('Participant(s)');
                break;
            case 'email':
                $('#' + label_id).html('Email');
                $('.comment_for').html('Participant(s)');
                $('.attendees_label').html('Participant(s)');
                break;
            case 'meeting':
                $('#' + label_id).html('Meeting');
                $('.comment_for').html('Participant(s)');
                $('.attendees_label').html('Participant(s)');
                break;
            case 'personal':
                $('#' + label_id).html('Personal');
                $('.comment_for').html('Participant(s)');
                $('.attendees_label').html('Participant(s)');
                break;
            case 'other':
                $('#' + label_id).html('Other');
                $('.comment_for').html('Participant(s)');
                $('.attendees_label').html('Participant(s)');
                break;
            case 'interview':
                $('#' + label_id).html('In-Person Interview');
                $('.comment_for').html('Interviewer(s)');
                $('.attendees_label').html('Interviewer(s)');
                break;
            case 'interview-phone':
                $('#' + label_id).html('Phone Interview');
                $('.comment_for').html('Interviewer(s)');
                $('.attendees_label').html('Interviewer(s)');
                break;
            case 'interview-voip':
                $('#' + label_id).html('VOIP Interview');
                $('.comment_for').html('Interviewer(s)');
                $('.attendees_label').html('Interviewer(s)');
                break;
            case 'training-session':
                $('#' + label_id).html('Training Session');
                $('.comment_for').html('Interviewer(s)');
                $('.attendees_label').html('Interviewer(s)');
                break;
        }
    }

    $(document).ready(function(){
        $('.loader').hide();
        $('#tab_loader').hide();

        $('body').on('click', '.btn_add_participant', function () {
            var random_id = Math.floor((Math.random() * 1000) + 1);
            var new_row = $('#external_participant_0').clone();

            var event_id = $('#event_id').val();
            event_id = event_id == '' || event_id == undefined || event_id == null ? 0 : event_id;

            console.log(event_id);

            $(new_row).find('i.fa').removeClass('fa-plus').addClass('fa-trash');
            $(new_row).find('button.btn').removeAttr('id').removeClass('btn-success').removeClass('btn_add_participant').addClass('btn-danger').addClass('btn_remove_participant').attr('data-id', random_id);
            $(new_row).find('input').val('');

            $(new_row).attr('id', 'external_participant_' + random_id);
            $(new_row).addClass('external_participants');
            $(new_row).attr('data-id', random_id);

            $(new_row).find('input.external_participants_name').attr('data-rule-required', true);
            $(new_row).find('input.external_participants_email').attr('data-rule-required', true);
            $(new_row).find('input.external_participants_email').attr('data-rule-email', true);

            $(new_row).find('input').each(function () {
                var name = $(this).attr('name').toString();
                var id = $(this).attr('id').toString();
                name = name.split('0').join(random_id);
                id = id.split('0').join(random_id);
                $(this).attr('name', name);
                $(this).attr('id', id);
            });

            $('#external_participants_container_' + event_id).append(new_row);
        });

        $('body').on('click', '.btn_remove_participant', function () {
            $($(this).closest('.external_participants').get()).remove();
        });


        $('#add_event_form').validate({
            submitHandler: function(form) {
                var participants = $('#interviewer').val();

                if(participants != null && participants.length > 0){
                    form.submit();
                } else {
                    alertify.error('Please Select Participants!');
                }
            }
        });

        $('body').on('change', '.category', function(){
            var selected = $(this).val();
            switch (selected)
            {
                case 'call':
                case 'email':
                case 'meeting':
                case 'personal':
                case 'other':
                    $('.comment_for').html('Participant(s)');
                    $('.attendees_label').html('Participant(s)');
                    break;
                case 'interview':
                    $('.comment_for').html('Interviewer(s)');
                    $('.attendees_label').html('Interviewer(s)');
                    break;

            }
        });

        $('.category').trigger('change');

        $('body').on('click', '.address_type', function () {
            var event_sid = $(this).attr('data-event_sid');
            if ($(this).prop('checked') == true) {
               if ($(this).val() == 'saved') {
                   $('#address_select_' + event_sid).show();
                   $('#address_select_' + event_sid + ' select').prop('disabled', false);

                   $('#address_input_'  + event_sid).hide();
                   $('#address_input_'  + event_sid + ' input').prop('disabled', true);
               } else if ($(this).val() == 'new') {
                   $('#address_select_' + event_sid).hide();
                   $('#address_select_' + event_sid + ' select').prop('disabled', true);

                   $('#address_input_' + event_sid).show();
                   $('#address_input_' + event_sid + ' input').prop('disabled', false);
               }
            }
        });

        $('#address_type_new_0').trigger('click');
        $('#address_select_0').hide();
        $('#address_select_0 select').prop('disabled', true);

        $('#address_input_0').show();
        $('#address_input_0 input').prop('disabled', false);

        $('body').on('shown.bs.modal', '#popupmodal', function () {
            $('.show_email_main_container').hide();
            $('.show_email_col').hide();

            $('#interviewer').select2();
            $('#interviewer').trigger('change');

            func_make_time_pickers();
            func_make_date_picker();

            $('#popupmodalbody .category').trigger('change');

            $('#edit_event_form').validate({
                submitHandler: function (form) {
                    var participants = $('#interviewer').val();

                    if (participants != null && participants.length > 0) {
                        form.submit();
                    } else {
                        alertify.error('Please Select Participants!');
                    }
                }
            });

            var event_sid = $('#event_sid').val();

            func_toggle_visibility('comment_check_' + event_sid, 'interviewer_comment_' + event_sid);
            func_toggle_visibility('message_check_' + event_sid, 'message_' + event_sid);
            func_toggle_visibility('goto_meeting_check_' + event_sid, 'meeting_details_' + event_sid);

            $('#add_attachment').filestyle({
                text: 'Add Attachment',
                btnClass: 'btn-success',
                placeholder: "No file selected"
            });

            //Show Saved Addresses
            $('#address_type_new_' + event_sid).trigger('click');
            $('#address_select_' + event_sid).hide();
            $('#address_saved_' + event_sid).prop('disabled', true);
            $('#address_input_' + event_sid).show();
            $('#address_new_' + event_sid).prop('disabled', false);

        });

        $('body').on('click', '.comment_check', function () {
            var checked = $(this).prop('checked');
            var event_id = $(this).attr('data-event_id');

            if (checked) {
                $('#interviewer_comment_' + event_id).show();
            } else {
                $('#interviewer_comment_' + event_id).hide();
            }
        });

        $('body').on('click', '.message_check', function () {
            var checked = $(this).prop('checked');
            var event_id = $(this).attr('data-event_id');

            if (checked) {
                $('#message_' + event_id).show();
            } else {
                $('#message_' + event_id).hide();
            }
        });

        $('body').on('click', '.goto_meeting_check', function () {
            var checked = $(this).prop('checked');
            var event_id = $(this).attr('data-event_id');

            if (checked) {
                $('#meeting_details_' + event_id).show();
            } else {
                $('#meeting_details_' + event_id).hide();
            }
        });


        func_make_time_pickers();
        func_make_date_picker();

        $('.show_email_main_container').hide();
        $('.show_email_col').hide();

        $('#interviewer').select2();
        $('#interviewer').trigger('change');

        $('body').on('change', '#interviewer', function(){
            var event_sid = $(this).attr('data-event_sid');
            event_sid = event_sid == null || event_sid == undefined || event_sid == '' || event_sid == 0 ? 0 : event_sid;

            var selected = $(this).val();
            if(selected !== null && selected.length > 0) {
                $('#show_email_main_container_' + event_sid).show();
                $('#show_email_main_container_' + event_sid + ' input[type=checkbox]').prop('disabled', false);

                $('.show_email_col').hide();

                for (i = 0; i < selected.length; i++) {
                    emp_sid = selected[i];

                    $('#show_email_container_' + event_sid + '_' + emp_sid).show();
                    $('#show_email_' + event_sid + '_' + emp_sid).prop('disabled', false);
                }
            } else {
                $('#show_email_main_container_' + event_sid).hide();
                $('#show_email_main_container_' + event_sid + ' input[type=checkbox]').prop('disabled', true);
            }
        });

        $('#add_attachment').filestyle({
            text: 'Add Attachment',
            btnClass: 'btn-success',
            placeholder: "No file selected"
        });
    });

    function func_toggle_visibility(checkbox_id, container_id) {
        var checked = $('#' + checkbox_id).prop('checked');
        if (checked) {
            $('#' + container_id).show();
        } else {
            $('#' + container_id).hide();
        }
    }

    function func_make_time_pickers(){
        $('.start_time').datetimepicker({
            datepicker: false,
            format: 'g:iA',
            formatTime: 'g:iA',
            step: 15,
            onChangeDateTime: function (dp, $input) {
                $('.end_time').datetimepicker({
                    minTime: $input.val()
                });
            }
        });

        $('.end_time').datetimepicker({
            datepicker: false,
            format: 'g:iA',
            formatTime: 'g:iA',
            step: 15,
            onChangeDateTime: function (dp, $input) {
                $('.start_time').datetimepicker({
                    maxTime: $input.val()
                });
            }
        });
    }

    function func_make_date_picker(){
        $('.datepicker').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>"
        });
    }

    function func_get_past_events(user_type, user_sid) {
        var my_request;

        $('#tab_content').hide();
        $('#tab_loader').show();

        my_request = $.ajax({
            url: '<?php echo base_url('calendar/ajax_responder'); ?>',
            type: 'POST',
            data: {
                'perform_action': 'get_past_events',
                'user_type': user_type,
                'user_sid': user_sid
            }
        });

        my_request.done(function(response){
            $('#tab_content').show();
            $('#tab_loader').hide();

            $('#tab_content').html(response);
            $('.loader').hide();
        });

        my_request.always(function(){
            $('#tab_content').show();
            $('#tab_loader').hide();

        });
    }

    function func_edit_event(event_sid, user_sid, user_job_list_sid) {
        var my_request;

        $('.btn').prop('disabled', true);
        $('#spinner_' + event_sid).show();

        my_request = $.ajax({
            url: '<?php echo base_url('calendar/ajax_responder'); ?>',
            type: 'POST',
            data: {
                'perform_action': 'get_event_edit_form',
                'event_sid': event_sid,
                'user_sid': user_sid,
                'redirect_to': 'employee_profile',
                'redirect_to_user_sid': user_sid,
                'redirect_to_job_list_sid': user_job_list_sid
            }
        });

        my_request.done(function (response) {

            $('.btn').prop('disabled', false);
            $('#spinner_' + event_sid).hide();

            $('#popupmodallabel').html('Edit Event');
            $('#popupmodalbody').html(response);
            $('.modal-dialog').addClass('modal-lg');
            $('#popupmodal').modal('show');

        });

        my_request.always(function(){
            $('.btn').prop('disabled', false);
            $('#spinner_' + event_sid).hide();
        });
    }
</script>
