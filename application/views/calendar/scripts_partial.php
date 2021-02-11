<link href="<?= base_url() ?>assets/calendar/fullcalendar.css" rel="stylesheet"/>
<link href="<?= base_url() ?>assets/calendar/fullcalendar.print.css" rel="stylesheet" media="print"/>
<script src="<?= base_url() ?>assets/calendar/moment.min.js"></script>
<script src="<?= base_url() ?>assets/calendar/fullcalendar.min.js"></script>

<script>
    function func_get_external_users_data() {
        var names = $('.external_participants_name');
        var emails = $('.external_participants_email');
        var show_emails = $('.external_participants_show_email');
        var data = [];

        for (iCount = 0; iCount < names.length; iCount++) {
            var person_data = {
                'name': $(names[iCount]).val(),
                'email': $(emails[iCount]).val(),
                'show_email': $(show_emails[iCount]).prop('checked') == true ? 1 : 0
            };

            data.push(person_data);
        }
        
        return JSON.stringify(data);
    }

    function func_goto_employee_profile() {
        var employee_sid = $('#employee_sid').val();
        if (employee_sid !== undefined && employee_sid !== '' && employee_sid !== 0 && employee_sid !== null) {
            var url = '<?php echo base_url('employee_profile'); ?>/' + employee_sid;
            window.open(url);
        }
    }

    function func_goto_applicant_profile() {
        var applicant_sid = $('#applicant_sid').val();
        if (applicant_sid !== undefined && applicant_sid !== '' && applicant_sid !== 0 && applicant_sid !== null) {
            var job_list_sid = $('[data-applicant-sid=' + applicant_sid + ']').attr('data-job-list-sid');
            var url = '<?php echo base_url('applicant_profile'); ?>/' + applicant_sid + '/' + job_list_sid;
            window.open(url);
        }
    }

    function func_set_category(input_id, label_id, selected) {
        $('#' + input_id).val(selected);
        switch (selected) {
            case 'call':
                $('#' + label_id).html('Call');
                $('#comment_for').html('Participant(s)');
                $('#attendees_label').html('Participant(s)');
                $('#none_employee_title').html('Participant(s)');
                break;
            case 'email':
                $('#' + label_id).html('Email');
                $('#comment_for').html('Participant(s)');
                $('#attendees_label').html('Participant(s)');
                $('#none_employee_title').html('Participant(s)');
                break;
            case 'meeting':
                $('#' + label_id).html('Meeting');
                $('#comment_for').html('Participant(s)');
                $('#attendees_label').html('Participant(s)');
                $('#none_employee_title').html('Participant(s)');
                break;
            case 'personal':
                $('#' + label_id).html('Personal');
                $('#comment_for').html('Participant(s)');
                $('#attendees_label').html('Participant(s)');
                $('#none_employee_title').html('Participant(s)');
                break;
            case 'other':
                $('#' + label_id).html('Other');
                $('#comment_for').html('Participant(s)');
                $('#attendees_label').html('Participant(s)');
                $('#none_employee_title').html('Participant(s)');
                break;
            case 'interview':
                $('#' + label_id).html('In-Person Interview');
                $('#comment_for').html('Interviewer(s)');
                $('#attendees_label').html('Interviewer(s)');
                $('#none_employee_title').html('Interviewer(s)');
                break;
            case 'interview-phone':
                $('#' + label_id).html('Phone Interview');
                $('#comment_for').html('Interviewer(s)');
                $('#attendees_label').html('Interviewer(s)');
                $('#none_employee_title').html('Interviewer(s)');
                break;
            case 'interview-voip':
                $('#' + label_id).html('VOIP Interview');
                $('#comment_for').html('Interviewer(s)');
                $('#attendees_label').html('Interviewer(s)');
                $('#none_employee_title').html('Interviewer(s)');
                break;
            case 'training-session':
                $('#' + label_id).html('Training Session');
                $('#comment_for').html('Interviewer(s)');
                $('#attendees_label').html('Interviewer(s)');
                $('#none_employee_title').html('Interviewer(s)');
                break;
        }
    }

    $(document).ready(function () {
        $('#event_form').validate();

        $('body').on('click', '#btn_add_participant', function () {
            var random_id = Math.floor((Math.random() * 1000) + 1);
            var new_row = $('#external_participant_0').clone();

            $(new_row).find('i.fa').removeClass('fa-plus').addClass('fa-trash');
            $(new_row).find('button.btn').removeAttr('id').removeClass('btn-success').addClass('btn-danger').addClass('btn_remove_participant').attr('data-id', random_id);
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

            $('#external_participants_container').append(new_row);
        });

        $('body').on('click', '.btn_remove_participant', function () {
            $($(this).closest('.external_participants').get()).remove();
        });

        $('body').on('click', '.address_type', function () {
            if ($(this).prop('checked') == true) {
                if ($(this).val() == 'saved') {
                    $('#address_select').show();
                    $('#address_select select').prop('disabled', false);
                    $('#address_input').hide();
                    $('#address_input input').prop('disabled', true);
                } else if ($(this).val() == 'new') {
                    $('#address_select').hide();
                    $('#address_select select').prop('disabled', true);
                    $('#address_input').show();
                    $('#address_input input').prop('disabled', false);
                }
            }
        });

        $('body').on('shown.bs.modal', '#popup1', function () {
//            console.log('triggered');
            $('#messageFile').filestyle({
                text: 'Add Attachment',
                btnClass: 'btn-success',
                placeholder: "No file selected"
            });

            $('#address_type_new').trigger('click');
            $('#address_select').hide();
            $('#address_select select').prop('disabled', true);
            $('#address_input').show();
            $('#address_input input').prop('disabled', false);
            $('#event_form').validate();
            //Get External Participants
            var my_request;
            var my_data = {
                'perform_action': 'get_event_external_participants',
                'event_sid': $('#event_id').val()
            };

            my_request = $.ajax({
                url: '<?php echo base_url('calendar/ajax_responder');?>',
                type: 'POST',
                data: my_data
            });

            my_request.done(function (response) {
                $('#external_participants_container').html(response);
            });
        });

        $('#show_jobs_main_container').hide();
        
        $('body').on('change', '#applicant_sid', function () {
            var applicant_sid = $(this).val();
//            console.log('i am triggerred: '+applicant_sid); // hassan working area
            if (applicant_sid !== undefined && applicant_sid !== '' && applicant_sid !== 0 && applicant_sid !== null) {
                var applicant_phone = $('#applicant_sid > option[data-applicant-sid=' + applicant_sid + ']').attr('data-applicant-phone');
                $('#users_phone').val(applicant_phone);
                $('#applicant_profile_link').prop('disabled', false);
                $('#applicant_profile_link').removeClass('disabled');
                $('.show_jobs_col').hide();
                $('#show_jobs_main_container').show();
                $('.show_jobs_container_' + applicant_sid).show();
            } else {
                $('#users_phone').val('');
                $('#applicant_profile_link').prop('disabled', true);
                $('#applicant_profile_link').addClass('disabled');
                $('#show_jobs_main_container').hide();
                $('.show_jobs_col').hide();
            }
        });

        $('body').on('change', '#employee_sid', function () {
            var employee_sid = $(this).val();
            if (employee_sid !== undefined && employee_sid !== '' && employee_sid !== 0 && employee_sid !== null) {
                var employee_phone = $('#employee_sid > option[data-employee-sid=' + employee_sid + ']').attr('data-employee-phone');
                $('#users_phone').val(employee_phone);

                var interviewer_val = $('#interviewer').val();

                if (interviewer_val !== null && interviewer_val !== undefined && interviewer_val !== '' && interviewer_val !== 0) {
                    interviewer_val.remove(employee_sid);
                }

                $('#interviewer').select2('val', interviewer_val);
                $('.int').prop('disabled', false);
                $('#interviewer').select2();
                $('#int_' + employee_sid).prop('disabled', true);
                $('#interviewer').select2();
                $('#employee_profile_link').prop('disabled', false);
                $('#employee_profile_link').removeClass('disabled');
            } else {
                $('#users_phone').val('');
                $('#employee_profile_link').prop('disabled', true);
                $('#employee_profile_link').addClass('disabled');
            }
        });

        $('body').on('hidden.bs.modal', '#popup1', function(){
            $('#users_type_applicant').prop('disabled', false);
            $('#users_type_employee').prop('disabled', false);
            $('#applicant_sid').prop('disabled', false);
            $('#employee_sid').prop('disabled', false);
            $('#applicant_profile_link').attr('disabled', false);
            $('#employee_profile_link').attr('disabled', false);
            $('#applicant_sid').select2();
            $('#employee_sid').select2();
        });

        $('#show_email_main_container').hide();
        
        $('.show_email_col').hide();
        //$('.show_jobs_col').hide();

        $('body').on('click', '.users_type', function () {
            if ($(this).val() == 'applicant') {
                $('#applicant_sid').prop('disabled', false);
                $('#applicant_select').show();
                $('#employee_sid').prop('disabled', true);
                $('#employee_select').hide();
                $('.int').prop('disabled', false);
                $('#interviewer').select2();
                $('#applicant_sid').trigger('change');
                $('#message_to_label').html('Applicant');
            } else {
                $('#applicant_sid').prop('disabled', true);
                $('#applicant_select').hide();
                $('#employee_sid').prop('disabled', false);
                $('#employee_select').show();
                $('#employee_sid').trigger('change');
                $('#message_to_label').html('Employee');
            }
        });

        $('#interviewer').on('change', function () {
            var selected = $(this).val();
            
            if (selected !== null && selected.length > 0) {
                $('#show_email_main_container').show();
                $('#show_email_main_container input[type=checkbox]').prop('disabled', false);
                $('.show_email_col').hide();

                for (i = 0; i < selected.length; i++) {
                    emp_sid = selected[i];
                    $('#show_email_container_' + emp_sid).show();
                    $('#show_email_' + emp_sid).prop('disabled', false);
                }
            } else {
                $('#show_email_main_container').hide();
                $('#show_email_main_container input[type=checkbox]').prop('disabled', true);
            }
        });

        $('.users_type:checked').trigger('click');

        if ($('.users_type:checked').length <= 0) {
            $('#users_type_applicant').trigger('click');
        }

        $('#interviewer').select2();
        $('#applicant_sid').select2();
        $('#employee_sid').select2();

        $('#candidate_msg').click(function () {
            if ($('#candidate_msg').is(":checked")) {
                $('.message-div').fadeIn();
                $('#applicantMessage').prop('required', true);
            } else {
                $('.message-div').hide();
                $('#applicantMessage').prop('required', false);
            }
        });

        $('.interviewer_comment').click(function () {
            if ($('.interviewer_comment').is(":checked")) {
                $('.comment-div').fadeIn();
                $('#interviewerComment').prop('required', true);
            } else {
                $('.comment-div').hide();
                $('#interviewerComment').prop('required', false);
            }
        });

        $('.goto_meeting').click(function () {
            if ($('.goto_meeting').is(":checked")) {
                $('.meeting-div').fadeIn();
                $('#meetingId').prop('required', true);
                $('#meetingCallNumber').prop('required', true);
                $('#meetingURL').prop('required', true);
            } else {
                $('.meeting-div').hide();
                $('#meetingId').prop('required', false);
                $('#meetingCallNumber').prop('required', false);
                $('#meetingURL').prop('required', false);
            }
        });

        $('#file_loader').click(function () {
            $('#select_new').hide();
            $('#update').hide();
            $('#save').hide();
            $('#title').val('');
            $('#address_new').val('');
            $('#eventdate').val('');
            $('#eventstarttime').val('');
            $('#eventendtime').val('');
            $('#file_loader').css("display", "none");
            $('#popup1').modal('hide');
            $('body').css("overflow", "auto");
        });

        $(".js-modal-close").click(function () {
            $('#select_new').hide();
            $('#update').hide();
            $('#save').hide();
            $('#title').val('');
            $('#address_new').val('');
            $('#eventdate').val('');
            $('#eventstarttime').val('');
            $('#eventendtime').val('');
            $('#popup1').modal('hide');
            $('#file_loader').css("display", "none");
        });

        var eventt = new Event('main');
        //populating date and time in Popup
        $(".datepicker").datepicker({dateFormat: 'mm-dd-yy'}).val();
        $('#eventendtime').datetimepicker({
            datepicker: false,
            format: 'g:iA',
            formatTime: 'g:iA',
            step: 15,
            onShow: function (ct) {
                time = $('#eventstarttime').val();
                timeAr = time.split(":");
                last = parseInt(timeAr[1].substr(0, 2)) + 15;
                if (last == 0)
                    last = "00";
                mm = timeAr[1].substr(2, 2);
                timeFinal = timeAr[0] + ":" + last + mm;
                this.setOptions({
                        minTime: $('#eventstarttime').val() ? timeFinal : false
                    }
                )
            }
        });

        $('#eventstarttime').datetimepicker({
            datepicker: false,
            format: 'g:iA',
            formatTime: 'g:iA',
            step: 15,
            onShow: function (ct) {
                this.setOptions({
                    maxTime: $('#eventendtime').val() ? $('#eventendtime').val() : false
                });
            }
        });

        function form_check() {
            alertify.defaults.glossary.title = 'Event Management!';
            if ($('#eventdate').val() == '') {
                alertify.alert("Please Provide Event Date");
                return false;
            }

            if ($('#eventstarttime').val() == '') {
                alertify.alert("Please provide Event Start Time");
                return false;
            }

            if ($('#eventendtime').val() == '') {
                alertify.alert("Please provide Event End Time");
                return false;
            }

            if ($('.users_type:checked').val() == 'applicant') {
                if ($('#applicant_sid').val() == null || $('#applicant_sid').val() <= 0 || $('#applicant_sid').val() == ' ') {
                    alertify.alert("Please select an Applicant");
                    return false;
                }
            } else if ($('.users_type:checked').val() == 'employee') {
                if ($('#employee_sid').val() == null || $('#employee_sid').val() <= 0 || $('#employee_sid').val() == ' ') {
                    alertify.alert("Please select an Employee");
                    return false;
                }
            }

//            if ($('#interviewer').val() == null || $('#interviewer').val() <= 0 || $('#interviewer').val() == ' ') {
//                alertify.alert("Please select an Interviewer");
//                return false;
//            }
            if ($('#interviewer').val() == null || $('#interviewer').val() <= 0 || $('#interviewer').val() == ' ') {
                $('#interviewer').val(<?= $employer_id; ?>);
            }
            return true;
        }

        $('#update,#reschedule').click(function () { //send ajax request to save new data
            if (form_check()) { //getting form data by ID to save
                var event_id = $('#event_id').val();
                var applicant_sid = $('#applicant_sid').val();
                var employee_sid = $('#employee_sid').val();
                var title = $('#title').val();
                var category = $('#add_event_category').val();
                var date = $('#eventdate').val();
                var eventstarttime = $('#eventstarttime').val();
                var eventendtime = $('#eventendtime').val();
                var interviewer = $('#interviewer').val();
                //var address = $('#address').val();
                var users_phone = $('#users_phone').val();
                var users_type = $('.users_type:checked').val();
                var user_id = 0;
                var address_type = $('.address_type:checked').val();
                var address_saved = $('#address_saved').val();
                var address_new = $('#address_new').val();
                var address = '';
                var show_jobs_sids = '';
                
                if (address_type == 'saved') {
                    address = address_saved;
                } else if (address_type == 'new') {
                    address = address_new;
                }

                var external_participants = func_get_external_users_data();

                if (users_type == 'applicant') {
                    user_id = $('.applicant_sid').val();
                } else {
                    user_id = $('#employee_id').val();
                }

                var show_email_chk = $('.show_email:checked');
                var show_email_sids = [];
                
                $(show_email_chk).each(function () {
                    show_email_sids.push($(this).val());
                });
                
                show_email_sids = show_email_sids.join(',');
                
                /////////////////////////////////////////////////////////
                if (users_type == 'applicant') {
                    var show_jobs_class = 'show_jobs_'+applicant_sid;
//                    console.log(show_jobs_class);
                    var show_jobs_chk = $('.'+show_jobs_class+':checked');
                    var show_jobs_sids = [];

                    $(show_jobs_chk).each(function () {
                        show_jobs_sids.push($(this).val());
                    });

                    show_jobs_sids = show_jobs_sids.join(',');
//                    console.log(show_jobs_sids);
                }
                
                //////////////////////////////////////////////////////////

                var myArray = {
                                'action': 'update_event',
                                'sid': event_id,
                                'applicant_sid': applicant_sid,
                                'employee_sid': employee_sid,
                                'title': title,
                                'category': category,
                                'date': date,
                                'eventstarttime': eventstarttime,
                                'eventendtime': eventendtime,
                                'interviewer': interviewer,
                                'address': address,
                                'users_phone': users_phone,
                                'users_type': users_type,
                                'interviewer_show_email': show_email_sids,
                                'external_participants': external_participants,
                                'applicant_jobs_list': show_jobs_sids
                            };

                myArray.commentCheck = '0';
                
                if ($('.interviewer_comment').is(":checked")) {
                    myArray.commentCheck = '1';
                    myArray.comment = $('#interviewerComment').val();
                }

                myArray.messageCheck = '0';
                
                if ($('#candidate_msg').is(":checked")) {
                    myArray.messageCheck = '1';
                    myArray.subject = $('#applicantSubject').val();
                    myArray.message = $('#applicantMessage').val();
                }

                myArray.goToMeetingCheck = '0';
                
                if ($('#goto_meeting').is(":checked")) {
                    myArray.goToMeetingCheck = '1';
                    myArray.meetingCallNumber = $('#meetingCallNumber').val();
                    myArray.meetingId = $('#meetingId').val();
                    myArray.meetingURL = $('#meetingURL').val();
                }

                var file_data = $('#messageFile').prop('files')[0];
                var form_data = new FormData();
                form_data.append('messageFile', file_data);
                
                for (myKey in myArray) {
                    form_data.append(myKey, myArray[myKey]);
                }

                $('#loader').show();
                $('.btn').addClass('disabled');
                $('.btn').prop('disabled', true);

                $.ajax({
                    url: "<?php echo base_url('calendar/tasks'); ?>",
                    type: 'POST',
                    data: form_data,
                    processData: false,
                    contentType: false,
                    success: function (msg) {
                        $('#loader').hide();
                        $('.btn').removeClass('disabled');
                        $('.btn').prop('disabled', false);
                        //console.log(msg);
                        alertify
                            .alert(msg, function () {
                                location.reload();
                            });
                    },
                    always: function () {
                        $('#loader').hide();
                        $('.btn').removeClass('disabled');
                        $('.btn').prop('disabled', false);
                    }
                });
            } else {
                return false;
            }
        });
        
        $('#delete').click(function () { //delete an event
            alertify.confirm(
                'Are you sure?',
                'Are you sure you want to delete this event?',
                function () {
                    event_id = $('#event_id').val();
                    $('#loader').show();
                    $('.btn').addClass('disabled');
                    $('.btn').prop('disabled', true);

                    $.ajax({
                        url: "<?= base_url() ?>calendar/deleteEvent?sid=" + event_id,
                        type: 'GET',
                        success: function (msg) {
                            $('#loader').hide();
                            $('.btn').removeClass('disabled');
                            $('.btn').prop('disabled', false);
                            $('#popup1').modal('hide');
                            
                            alertify.alert(msg, function () {
                                location.reload();
                            });
                        },
                        always: function () {
                            $('#loader').hide();
                            $('.btn').removeClass('disabled');
                            $('.btn').prop('disabled', false);
                        }
                    });
                },
                function () {
                    alertify.error('Cancelled!');
                });
        });

        $('#cancel').click(function () { //Cancel Event
            alertify.confirm(
                'Are you sure?',
                'Are you sure you want to Cancel this event?',
                function () {
                    event_id = $('#event_id').val();
                    var my_request;

                    $('#loader').show();
                    $('.btn').addClass('disabled');
                    $('.btn').prop('disabled', true);

                    my_request = $.ajax({
                        url: '<?php echo base_url('calendar/tasks');?>',
                        type: 'POST',
                        data: {'event_id': event_id, 'action': 'cancel_event'}
                    });

                    my_request.done(function (response) {
                        $('#loader').hide();
                        $('.btn').removeClass('disabled');
                        $('.btn').prop('disabled', false);

                        if (response == 'success') {
                            $('#popup1').modal('toggle');
                            alertify.success('Event Cancelled!');
                            window.location = window.location.href;
                        } else {
                            $('#popup1').modal('toggle');
                            alertify.error('Could Not Cancel Event!');
                            window.location = window.location.href;
                        }
                    });

                    my_request.always(function () {
                        $('#loader').hide();
                        $('.btn').removeClass('disabled');
                        $('.btn').prop('disabled', false);
                    });
                },
                function () {
                    alertify.error('Cancelled!');
                });
        });

        $('#save').click(function () { //Save new event
            if (form_check() && $('#event_form').valid()) {
                //getting form data by ID to save
                var applicant_sid = $('#applicant_sid').val();
                var employee_sid = $('#employee_sid').val();
                var title = $('#title').val();
                var category = $('#add_event_category').val();
                var date = $('#eventdate').val();
                var eventstarttime = $('#eventstarttime').val();
                var eventendtime = $('#eventendtime').val();
                var interviewer = $('#interviewer').val();
                //var address = $('#address').val();
                var users_type = $('.users_type:checked').val();
                var users_phone = $('#users_phone').val();
                var address_type = $('.address_type:checked').val();
                var address_saved = $('#address_saved').val();
                var address_new = $('#address_new').val();
                var external_participants = func_get_external_users_data();
                var address = '';
                var show_jobs_sids = '';
                
                if (address_type == 'saved') {
                    address = address_saved;
                } else if (address_type == 'new') {
                    address = address_new;
                }

                var show_email_chk = $('.show_email:checked');
                var show_email_sids = [];
                
                $(show_email_chk).each(function () {
                    show_email_sids.push($(this).val());
                });
                
                show_email_sids = show_email_sids.join(',');
                
                /////////////////////////////////////////////////////////
                if (users_type == 'applicant') {
                    var show_jobs_class = 'show_jobs_'+applicant_sid;
//                    console.log(show_jobs_class);
                    var show_jobs_chk = $('.'+show_jobs_class+':checked');
                    var show_jobs_sids = [];

                    $(show_jobs_chk).each(function () {
                        show_jobs_sids.push($(this).val());
                    });

                    show_jobs_sids = show_jobs_sids.join(',');
//                    console.log(show_jobs_sids);
                }
                
                //////////////////////////////////////////////////////////

                var myArray = {
                    'action': 'save_event',
                    'applicant_sid': applicant_sid,
                    'employee_sid': employee_sid,
                    'title': title,
                    'category': category,
                    'date': date,
                    'eventstarttime': eventstarttime,
                    'eventendtime': eventendtime,
                    'interviewer': interviewer,
                    'address': address,
                    'users_type': users_type,
                    'users_phone': users_phone,
                    'interviewer_show_email': show_email_sids,
                    'external_participants': external_participants,
                    'applicant_jobs_list': show_jobs_sids
                };

                if ($('.interviewer_comment').is(":checked")) {
                    myArray.commentCheck = '1';
                    myArray.comment = $('#interviewerComment').val();
                }

                if ($('#candidate_msg').is(":checked")) {
                    myArray.messageCheck = '1';
                    myArray.subject = $('#applicantSubject').val();
                    myArray.message = $('#applicantMessage').val();
                }

                if ($('#goto_meeting').is(":checked")) {
                    myArray.goToMeetingCheck = '1';
                    myArray.meetingCallNumber = $('#meetingCallNumber').val();
                    myArray.meetingId = $('#meetingId').val();
                    myArray.meetingURL = $('#meetingURL').val();
                }

                var file_data = $('#messageFile').prop('files')[0];
                var form_data = new FormData();
                form_data.append('messageFile', file_data);

                for (myKey in myArray) {
                    form_data.append(myKey, myArray[myKey]);
                }

                //console.log(event_id);
                $('#loader').show();
                $('.btn').addClass('disabled');
                $('.btn').prop('disabled', true);

                $.ajax({
                    url: "<?php echo base_url('calendar/tasks'); ?>",
                    type: 'POST',
                    data: form_data,
                    processData: false,
                    contentType: false,
                    success: function (msg) {
                        $('#loader').hide();
                        $('.btn').removeClass('disabled');
                        $('.btn').prop('disabled', false);

                        $('#file_loader').css("display", "none");
                        $('#popup1').modal('hide');
                        $('body').css("overflow", "auto");
                        alertify
                            .alert(msg, function () {
                                location.reload();
                            });
                    },
                    always: function () {
                        $('#loader').hide();
                        $('.btn').removeClass('disabled');
                        $('.btn').prop('disabled', false);
                    }
                });
            } else {
                return false;
            }
        });
        //start of calendar

        $('#calendar').fullCalendar({
            header: {
                left: 'prevYear,prev,next,nextYear',
                center: 'title',
                right: 'today, agendaDay, agendaWeek, month'
            },
            forceEventDuration: true,
            selectHelper: true,
            select: function (start, end) {
                var title = prompt('Event Title:');
                var eventData;
                if (title) {
                    eventData = {
                        title: title,
                        start: start,
                        end: end
                    };
                    $('#calendar').fullCalendar('renderEvent', eventData, true); // stick? = true
                }

                $('#calendar').fullCalendar('unselect');
            },

            eventLimit: true, // allow "more" link when too many events
            eventClick: function (calEvent, jsEvent, view) { //Reset Event Popup
                $('#event_id').val('');
                $('#applicant_sid').select2('val', '');
                $('#employee_sid').select2('val', '');
                $('#title').val('');
                $('#address_new').val('');
                $('#category').val('');
                $('#eventdate').val('');
                $('#eventstarttime').val('');
                $('#eventendtime').val('');
                $('#interviewerComment').val('');
                $('#applicantSubject').val('');
                $('#applicantMessage').val('');
                $('#meetingCallNumber').val('');
                $('#meetingId').val('');
                $('#meetingURL').val('');
                $('#users_phone').val('');
//                console.log('825: '+calEvent);
//                console.log(JSON.stringify(calEvent));
//                return false;
                if (calEvent.users_type == "applicant") {
                    $('#users_type_applicant').trigger('click');
                    $('#applicant_sid').select2('val', calEvent.applicant_sid);
                } else {
                    $('#users_type_employee').trigger('click');
                    $('#employee_sid').select2('val', calEvent.applicant_sid);
                }

                if (calEvent.messageCheck == "0") {
                    $('#candidate_msg').prop('checked', false);
                    $('.message-div').hide();
                }

                //Check if edit event is training session then disable all except training session

                if (calEvent.commentCheck == "0") {
                    $('#interviewer_comment').prop('checked', false);
                    $('.comment-div').hide();
                }

                if (calEvent.goToMeetingCheck == "0") {
                    $('#goto_meeting').prop('checked', false);
                    $('.meeting-div').hide();
                }
                
                if (calEvent.applicant_jobs_list !== undefined && calEvent.applicant_jobs_list !== '' && calEvent.applicant_jobs_list !== 0 && calEvent.applicant_jobs_list !== null) {
//                    console.log("Data exists: "+calEvent.applicant_jobs_list);
                    var jobs_list_string = calEvent.applicant_jobs_list;
                    var jobs_list_array = jobs_list_string.split(',');
                    
                    $('.enable_disable_jobs').prop('checked', false);

                    for (var jc = 0; jc < jobs_list_array.length; jc++) {
                        $('#show_jobs_' + jobs_list_array[jc]).prop('checked', true);
                    }
                } else {
                    $('.enable_disable_jobs').prop('checked', false);
                }
                
//                console.log('me: '+calEvent.applicant_jobs_list);
                //Reset Event Popup End
                //open popup
                $('#select_new').show();

                <?php if(check_access_permissions_for_view($security_details, 'application_tracking')) { ?>
                    if (calEvent.event_status == 'confirmed') {
                        $('#cancelled_message').hide();
                        $('#update').show();
                        $('#cancel').show();
                        $('#delete').show();
                        $('#save').hide();
                        $('#reschedule').hide();
                        $('#close_btn').show();
                    } else if (calEvent.event_status == 'cancelled') {
                        $('#cancelled_message').show();
                        $('#update').hide();
                        $('#cancel').hide();
                        $('#delete').hide();
                        $('#save').hide();
                        $('#close_btn').show();
                        $('#reschedule').show();
                    } else {
                        $('#reschedule').hide();
                        $('#cancelled_message').show();
                        $('#update').hide();
                        $('#cancel').hide();
                        $('#delete').hide();
                        $('#save').hide();
                        $('#close_btn').hide();
                    }
                <?php } else { ?>
                    if(calEvent.employer_sid == <?php echo $employer_id; ?>){
                        if (calEvent.event_status == 'confirmed') {
                            $('#cancelled_message').hide();
                            $('#update').show();
                            $('#cancel').show();
                            $('#delete').show();
                            $('#save').hide();
                            $('#reschedule').hide();
                            $('#close_btn').show();
                        } else if (calEvent.event_status == 'cancelled') {
                            $('#cancelled_message').show();
                            $('#update').hide();
                            $('#cancel').hide();
                            $('#delete').hide();
                            $('#save').hide();
                            $('#close_btn').show();
                            $('#reschedule').show();
                        } else {
                            $('#reschedule').hide();
                            $('#cancelled_message').show();
                            $('#update').hide();
                            $('#cancel').hide();
                            $('#delete').hide();
                            $('#save').hide();
                            $('#close_btn').hide();
                        }
                    } else {
                        $('#cancelled_message').hide();
                        $('#update').hide();
                        $('#cancel').hide();
                        $('#delete').hide();
                        $('#save').hide();
                        $('#reschedule').hide();
                        $('#close_btn').show();
                    }
                <?php } ?>
                if (calEvent.category != "training-session") {
                    $('#training-session-tile').hide();
//                    $('.training-session-btns').show();
                    $('.training-session-tile').removeAttr('disabled','disabled');
                    $('#users_type_applicant').removeAttr('disabled','disabled');
                } else{
                    $('#training-session-tile').show();
                    $('.training-session-btns').hide();
                    $('.training-session-tile').attr('disabled','disabled');
                    $('#users_type_applicant').attr('disabled','disabled');
                }
                //fill up the popup
                $('#event_id').val(calEvent.event_id);
                $('#title').val(calEvent.event_title);
                func_set_category('add_event_category', 'add_event_selected_category', calEvent.category);
                $('#eventdate').val(calEvent.eventdate);
                $('#eventstarttime').val(calEvent.eventstarttime_12hr);
                $('#eventendtime').val(calEvent.eventendtime_12hr);
                $('#users_phone').val(calEvent.users_phone);
                var myarr = calEvent.interviewer;

                if (myarr instanceof Array) {
                } else {
                    var myarr = calEvent.interviewer.split(",");
                    if (myarr[0] == "") {
                        myarr.shift();
                    }
                }

                $('#interviewer').select2('val', myarr);
                var show_email = calEvent.interviewer_show_email;
                show_email = show_email.trim();
                show_email = show_email.split(',');
                $('.show_email').prop('checked', false);

                for (var iCount = 0; iCount < show_email.length; iCount++) {
                    $('#show_email_' + show_email[iCount]).prop('checked', true);
                }

                if (calEvent.commentCheck == "1") {
                    $('#interviewer_comment').prop('checked', true);
                    $('.comment-div').fadeIn();
                    $('#interviewerComment').val(calEvent.comment);
                }

                if (calEvent.messageCheck == "1") {
                    $('#candidate_msg').prop('checked', true);
                    $('.message-div').fadeIn();
                    $('#applicantSubject').val(calEvent.subject);
                    $('#applicantMessage').val(calEvent.message.toString().replace(new RegExp('<br>', 'g'), '\r\n'));
                }

                if (calEvent.goToMeetingCheck == "1") {
                    $('#goto_meeting').prop('checked', true);
                    $('.meeting-div').fadeIn();
                    $('#meetingCallNumber').val(calEvent.meetingCallNumber);
                    $('#meetingId').val(calEvent.meetingId);
                    $('#meetingURL').val(calEvent.meetingURL);
                }

                $('.users_type:checked').val(calEvent.users_type);
                eventt = calEvent;

                <?php if(!check_access_permissions_for_view($security_details, 'application_tracking')) { ?>
                    $('#users_type_applicant').prop('disabled', true);
                    $('#applicant_profile_link').attr('disabled', true);
                    $('#applicant_sid').prop('disabled', true);
                    $('#applicant_sid').select2();

                    if(eventt.users_type === 'applicant') {
                        $('#users_type_employee').prop('disabled', true);
                        $('#employee_profile_link').attr('disabled', true);
                        $('#employee_sid').prop('disabled', true);
                        $('#employee_sid').select2();
                    }
                <?php } ?>

                $('#popup1').modal('show');
                $('#address_type_new').prop('checked', true).trigger('click');
                $('#address_saved').select2().select2('val', calEvent.address);
                $('#address_input').show();
                $('#address_new').val(calEvent.address);
                //$('#current_saved_address').html(calEvent.address);
                $('#current_saved_address').html('');
                //$('#label_address_type').html('Current Address');

                $('html,body').animate({
                    scrollTop: $("#popup1").offset().top
                });
            },

            events: [
                <?php
                foreach ($events as $event) {
                $event_category = $event['category_uc'];

                if ($event_category == 'Interview') {
                    $event_category = 'In Person Interview';
                } else if ($event_category == 'Interview-phone') {
                    $event_category = 'Phone Interview';
                } else if ($event_category == 'Interview-voip') {
                    $event_category = 'Voip Interview';
                }
                ?>
                {
                    title: '<?= addslashes($event['f_name_uc']) ?> <?= addslashes($event['l_name_uc']) ?> - <?= $event_category; ?>',
                    start: '<?= $event['backDate'] ?>T<?= $event['eventstarttime24Hr'] ?>',
                    end: '<?= $event['date'] ?>T<?= $event['eventendtime24Hr'] ?>}',
                    <?php if($event['event_status'] == 'confirmed') { ?>
                    <?php if ($event['category'] == 'interview') { ?>
                    color: '#0000ff'
                    <?php } else if ($event['category'] == 'interview-voip') { ?>
                    color: '#0fa600'
                    <?php } else if ($event['category'] == 'interview-phone') { ?>
                    color: '#1c521d'
                    <?php } else if ($event['category'] == 'call') { ?>
                    color: '#dd7600'
                    <?php } else if ($event['category'] == 'email') { ?>
                    color: '#b910ff'
                    <?php } else if ($event['category'] == 'meeting') { ?>
                    color: '#0091dd'
                    <?php } else if ($event['category'] == 'personal') { ?>
                    color: '#266d55'
                    <?php } else if ($event['category'] == 'other') { ?>
                    color: '#7e7b7b'
                    <?php } else if ($event['category'] == 'training-session') { ?>
                    color: '#337ab7'
                    <?php } else { ?>
                    color: '#cc0000'
                    <?php } ?>
                    <?php } else { ?>
                    color: '#ff0000'
                    <?php } ?>,
                    event_title: "<?= addslashes($event['title']) ?>",
                    applicant_sid: "<?= $event['applicant_job_sid'] ?>",
                    employer_sid: "<?= $event['employers_sid'] ?>",
                    event_id: "<?= $event['sid'] ?>",
                    category: "<?= $event['category'] ?>",
                    date: "<?= $event['date'] ?>",
                    eventdate: "<?= $event['frontDate'] ?>",
                    eventstarttime: "<?= $event['eventstarttime'] ?>",
                    eventendtime: "<?= $event['eventendtime'] ?>",
                    eventstarttime: "<?= $event['eventstarttime'] ?>",
                    eventendtime: "<?= $event['eventendtime'] ?>",
                    eventstarttime_12hr: "<?= $event['eventstarttime'] ?>",
                    eventendtime_12hr: "<?= $event['eventendtime'] ?>",
                    interviewer: "<?= addslashes($event['interviewer']) ?>",
                    address: "<?= str_replace(array('.', ' ', "\n", "\t", "\r"), ' ', addslashes($event['address'])) ?>",
                    commentCheck: "<?= str_replace(array('.', ' ', "\n", "\t", "\r"), ' ', addslashes($event['commentCheck'])) ?>",
                    comment: "<?= str_replace(array('.', ' ', "\n", "\t", "\r"), ' ', addslashes($event['comment'])) ?>",
                    messageCheck: "<?= str_replace(array('.', ' ', "\n", "\t", "\r"), ' ', addslashes($event['messageCheck'])) ?>",
                    subject: "<?= str_replace(array('.', ' ', "\n", "\t", "\r"), ' ', addslashes(addslashes($event['subject']))) ?>",
                    message: "<?= str_replace(array("\n", "\r"), '<br>', addslashes($event['message'])); ?>",
                    goToMeetingCheck: "<?= $event['goToMeetingCheck'] ?>",
                    meetingId: "<?= $event['meetingId'] ?>",
                    meetingCallNumber: "<?= $event['meetingCallNumber'] ?>",
                    meetingURL: "<?= $event['meetingURL'] ?>",
                    editable: true,
                    users_type: "<?= $event["users_type"] ?>",
                    users_phone: "<?= !empty($event["users_phone"]) ? $event["users_phone"] : '' ?>",
                    event_status: "<?= $event["event_status"] ?>",
                    interviewer_show_email: "<?= $event["interviewer_show_email"] ?>",
                    applicant_jobs_list: "<?= $event["applicant_jobs_list"] ?>"
                },
                <?php } ?>
            ],

            eventDrop: function (event, delta, revertFunc) {

                //.................eventstarttime..............................................
                datetime = new Date(event.start.format());
                eventDate = datetime.getDate() + '-' + (datetime.getMonth() + 1) + '-' + datetime.getFullYear();
                hours = event.start.format().substr(event.start.format().indexOf('T') + 1, 2);
                if (datetime.getUTCMinutes() == 0) {
                    mins = '00';
                } else {
                    mins = datetime.getUTCMinutes();
                }

                if (hours == '0') {
                    hours = '00';
                }

                eventstarttime = hours + ':' + mins;
                eventstarttime12hr = tConvert(eventstarttime);
                //.,................eventendtime.........................................................
                datetime = new Date(event.end.format());
                hours = event.end.format().substr(event.end.format().indexOf('T') + 1, 2);
                
                if (datetime.getUTCMinutes() == 0) {
                    mins = '00';
                } else {
                    mins = datetime.getUTCMinutes();
                }

                if (hours == '0') {
                    hours = '00';
                }

                eventendtime = hours + ':' + mins;
                eventendtime12hr = tConvert(eventendtime);
                //............................//
                olddate = eventDate;
                newdate = eventDate.split('-');
                eventDate = newdate[1] + '-' + newdate[0] + '-' + newdate[2];
                //console.log(event);
                func_show_loader();

                $.ajax({
                    url: "<?php echo base_url('calendar/tasks'); ?>",
                    type: 'POST',
                    data: {
                        action: 'drop_update_event',
                        sid: event.event_id,
                        applicant_sid: event.applicant_sid,
                        employee_sid: event.applicant_sid,
                        title: event.event_title,
                        category: event.category,
                        date: eventDate,
                        eventstarttime: event.eventstarttime_12hr,
                        eventendtime: event.eventendtime_12hr,
                        interviewer: event.interviewer,
                        users_type: event.users_type
                    },

                    success: function (msg) {
                        func_hide_loader();
                        alertify.success(msg);
                        event.eventdate = eventDate;
                        event.eventstarttime = eventstarttime;
                        event.eventendtime = eventendtime;
                        event.eventstarttime_12hr = eventstarttime12hr;
                        event.eventendtime_12hr = eventendtime12hr;
                    }
                });
            },

            dayClick: function (date, allDay, jsEvent, view) {
                //fill up the popup
                //$('#contact_id').select2({}).select2('val', ' ');
                $('#select_new').show();
                $('#update').hide();
                $('#delete').hide();
                $('#save').show();
                $('#title').val('');
                $('#address_new').val('');
                $('#cancel').hide();
                $('#reschedule').hide();
                $('#cancelled_message').hide();
                $('#close_btn').show();
                newdate = moment(date).format('MM-DD-YYYY');
                $('#eventdate').val(newdate);
                $('#eventstarttime').val('');
                $('#eventendtime').val('');
                $('#address').val('');
                $('#interviewer_comment').prop('checked', false);
                $('.comment-div').fadeOut();
                $('#interviewerComment').val('');
                $('#candidate_msg').prop('checked', false);
                $('.message-div').fadeOut();
                $('#applicantSubject').val('');
                $('#applicantMessage').val('');
                $('#goto_meeting').prop('checked', false);
                $('.meeting-div').fadeOut();
                $('#meetingCallNumber').val('');
                $('#meetingId').val('');
                $('#meetingURL').val('');
                //$('#users_type_applicant').trigger('click');
                $('#current_saved_address').html('');
                $('#event_id').val(0);
                //$('#label_address_type').html('New Address');
                $('#popup1').modal('show');
                $('#applicant_sid').select2('val', '');
                $('#interviewer').select2('val', '<?= $employer_id ?>');
                $('.show_email').prop('checked', false);
                $('#training-session-tile').hide();
                $('.training-session-tile').removeAttr('disabled');
                func_set_category('add_event_category', 'add_event_selected_category', 'interview');
                $('html,body').animate({
                    scrollTop: $("#popup1").offset().top
                });

                <?php if(check_access_permissions_for_view($security_details, 'application_tracking')) { ?>
                $('#users_type_applicant').attr('disabled', false);
                $('#applicant_sid').attr('disabled', false);
                $('#users_type_employee').attr('disabled', false);
                $('#employee_sid').attr('disabled', false);
                $('#applicant_profile_link').attr('disabled', false);
                $('#employee_profile_link').attr('disabled', false);
                $('#users_type_applicant').trigger('click');
                <?php } else { ?>
                $('#users_type_applicant').attr('disabled', true);
                $('#applicant_sid').attr('disabled', true);
                $('#users_type_employee').attr('disabled', false);
                $('#employee_sid').attr('disabled', false);
                $('#applicant_profile_link').attr('disabled', true);
                $('#employee_profile_link').attr('disabled', false);
                $('#users_type_employee').trigger('click');
                <?php } ?>
            },

            allDaySlot: false,
            timeFormat: 'h(:mm)'
        });

        func_hide_loader();
    });

    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    function convertTime(time) {
        var hours = Number(time.match(/^(\d+)/)[1]);
        var minutes = Number(time.match(/:(\d+)/)[1]);
        var AMPMposition = time.indexOf(":");
        var AMPM = time.substr(AMPMposition + 3, time.length);
        if (AMPM == "pm" && hours < 12) hours = hours + 12;
        if (AMPM == "am" && hours == 12) hours = hours - 12;
        var sHours = hours.toString();
        var sMinutes = minutes.toString();
        if (hours < 10) sHours = "0" + sHours;
        if (minutes < 10) sMinutes = "0" + sMinutes;
        return sHours + ":" + sMinutes;
    }

    function tConvert(time) {
        // Check correct time format and split into components
        time = time.toString().match(/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];
        if (time.length > 1) { // If time format correct
            time = time.slice(1); // Remove full string match value
            time[5] = +time[0] < 12 ? 'AM' : 'PM'; // Set AM/PM
            time[0] = +time[0] % 12 || 12; // Adjust hours
        }
        return time.join(''); // return adjusted time or original string
    }

    function func_hide_loader() {
        $('#file_loader').css("display", "none");
        $('.my_spinner').css("visibility", "hidden");
        $('.loader-text').css("display", "none");
        $('.my_loader').css("display", "none");
    }

    function func_show_loader() {
        $('#file_loader').css("display", "block");
        $('.my_spinner').css("visibility", "visible");
        $('.loader-text').css("display", "block");
        $('.my_loader').css("display", "block");
    }

    $('.select2-dropdown').css('z-index', '99999999999999999999999');
</script>
