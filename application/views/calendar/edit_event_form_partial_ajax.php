<div class="modal fade" id="js-event-edit-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                <h4 class="modal-title"><span class="js-edit-event-head-title">Event Management (<?= $employer_timezone ?>)</span>
                    <div class="js-extra-btns pull-right" style="margin-right: 20px;"></div>
                </h4>
            </div>
            <div class="modal-body">
                <div class="js-main-page">
                    <form action="javascript:void(0)">
                        <?php $this->load->view('calendar/edit_event_form_partial_modal_ajax'); ?>
                    </form>
                </div>
                <!-- Request list for event status button -->
                <div class="js-request-page" style="padding: 10px;">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>User name</th>
                                            <th>User Type</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Start Time</th>
                                            <th>End Time</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="js-pagination-area pull-right"></div>
                        </div>
                    </div>
                </div>

                <!-- Reminder emails list -->
                <div class="js-reminder-email-history-page" style="padding: 10px;">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>User Name</th>
                                            <th>User Email</th>
                                            <th>User Type</th>
                                            <!-- <th>Sent Date</th> -->
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="js-pagination-area pull-right"></div>
                        </div>
                    </div>
                </div>
                <!-- Event change history list -->
                <div class="js-event-change-history-page" style="padding: 10px;display:none;">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Before change event details</th>
                                            <th>After change event details</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="js-pagination-area pull-right"></div>
                        </div>
                    </div>
                </div>

                <!-- Expired Reschedule box -->
                <div class="js-reschedule-page" style="padding: 10px;">
                    <div class="row">
                        <div class="col-sm-12">
                            <form action="javascript:void(0)" id="js-edit-reschedule-form">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label>Event Date</label>
                                            <input type="text" class="form-control" readonly="true" id="js-edit-reschedule-event-date" />
                                        </div>
                                        <div class="col-sm-4">
                                            <label>Event Start Time</label>
                                            <input type="text" class="form-control" readonly="true" id="js-edit-reschedule-event-start-time" />
                                        </div>
                                        <div class="col-sm-4">
                                            <label>Event End Time</label>
                                            <input type="text" class="form-control" readonly="true" id="js-edit-reschedule-event-end-time" />
                                        </div>
                                    </div>
                                </div>
                                <!--  -->
                                <div class="form-group  pull-right">
                                    <input type="submit" class="btn btn-success" value="Reschedule" readonly="true" />
                                    <input type="button" class="btn btn-default js-edit-reschedule-cancel" value="Cancel" readonly="true" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer js-modal-footer">
                <div>
                    <?php if (check_access_permissions_for_view($security_details, 'reschedule_event')) { ?>
                        <input class="js-edit-modal-btn btn btn-success" name="event_submit" type="button" style="display: none;" value="Re Schedule Event" id="js-edit-reschedule" />
                    <?php } ?>
                    <?php if (check_access_permissions_for_view($security_details, 'update_event')) { ?>
                        <input class="js-edit-modal-btn btn btn-success training-session-btns" name="event_submit" type="button" style="display: none;" value="Update" id="js-edit-update" />
                    <?php } ?>
                    <?php if (check_access_permissions_for_view($security_details, 'create_event')) { ?>
                        <input class="js-edit-modal-btn btn btn-success js-save-btn" name="event_submit" style="display: none; margin-top: 1px !important;" type="button" value="Save" id="js-edit-save" />
                    <?php } ?>
                    <a id="close_btn" href="javascript:;" class="btn btn-primary" data-dismiss="modal">Close</a>
                    <?php if (check_access_permissions_for_view($security_details, 'delete_event')) { ?>
                        <input class="js-edit-modal-btn btn btn-danger training-session-btns" name="event_submit" type="button" style="display: none;" value="Delete" id="js-edit-delete" />
                    <?php } ?>
                    <?php if (check_access_permissions_for_view($security_details, 'cancel_event')) { ?>
                        <input class="js-edit-modal-btn btn btn-warning training-session-btns" name="event_submit" type="button" style="display: none;" value="Cancel Event" id="js-edit-cancel" />
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        var // Set recur page reference
            recur_page_REF = $('.js-recur-page'),
            //
            site_date_format = 'MM-DD-YYYY',
            site2_date_format = 'YYYY-MM-DD',
            // Set request page reference
            request_page_REF = $('.js-request-page'),
            // Set reminder page reference
            reminder_page_REF = $('.js-reminder-email-history-page'),
            change_history_page_REF = $('.js-event-change-history-page'),
            // Set reschedule page reference
            reschedule_page_REF = $('.js-reschedule-page'),
            // Set main page reference
            main_page_REF = $('.js-main-page'),
            // Set clone and history buttons reference
            extra_btns_wrap = $('.js-extra-btns'),
            event_history_type = null,
            // Set interviewers default text
            default_interviewer_text = 'Participant(s)',
            // Set interviewers default text
            default_einterviewer_text = 'Non-employee Participant(s)',
            employee_list = <?= @json_encode($company_accounts); ?>,
            current_event_type = '';
        $('#js-edit-date').datepicker({
            dateFormat: 'mm-dd-yy DD',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>"
        }).val();
        //
        $('#js-event-edit-modal').on('hidden.bs.modal', function() {
            edit_mode = '';
            load_categories(user_type, '#js-event-type-area');
        });

        $(document).on('click', '.js-edit-event', function() {
            edit_event($(this).data('eid'));
        });

        function edit_event(event_sid) {
            reset_modal();
            edit_mode = '-edit';
            var my_request;
            $('.btn').prop('disabled', true);
            $('#spinner_' + event_sid).show();

            my_request = $.ajax({
                url: '<?php echo base_url('calendar/get-event-detail'); ?>/' + event_sid + '',
                type: 'GET'
            });

            my_request.done(function(response) {
                load_categories(user_type, '#js-edit-event-type-area');
                $('.btn').prop('disabled', false);
                var e = response.Event;
                save_event_locally(e, event_sid);

                // e.users_phone = e.users_phone.replace('+1', '');
                // var tmp = fpn(e.users_phone);
                // e.users_phone = typeof(tmp) === 'object' ? tmp.number : tmp;
                // if(typeof(tmp) === 'object') setCaretPosition($('#js-edit-users-phone'), tmp.cur);

                $('#js-edit-event-id').val(event_sid);
                $('#js-edit-event-type').val(e.users_type);
                $('#js-edit-applicant-id').val(e.applicant_job_sid);
                $('#js-edit-employee-id').val(e.employers_sid);
                $('#js-edit-event-title').val(e.event_title);
                $('#js-edit-user-phone').val(e.users_phone);
                $('#js-edit-date').val(moment(e.date).format('MM-DD-YYYY dddd'));
                $('#js-edit-start-time').val(e.event_start_time);
                $('#js-edit-end-time').val(e.event_end_time);
                var employer_timezone = '<?= $employer_timezone ?>';
                if (e.event_timezone != null)
                    $('#edit_event_timezone').val(e.event_timezone);
                else
                    $('#edit_event_timezone').val(employer_timezone);
                //
                $('.js-edit-comment-wrap, .js-edit-comment-hr').show(0);
                $('.js-edit-message-wrap, .js-edit-message-hr').show(0);
                $('label#js-edit-attendees-label').text("<?= $users_type == 'Employee' ? 'Participant(s)' : 'Interviewer(s)'; ?>");
                $('#js-edit-interviewers-list').show(0);
                $('#js-edit-interviewer').select2('val', <?= $main_employer_id; ?>);
                $('#js_edit_show_email_container_0_' + <?= $main_employer_id; ?> + '').show(0);
                $('#js-edit-interviewer option[value="all"]').remove();
                //
                if (e.comment_check == 1) {
                    $('#js-edit-comment-check').prop('checked', true);
                    $('#js-edit-comment-msg').val(e.comment);
                    $('#js-edit-comment-box').show();
                }
                //
                if (e.meeting_check == 1) {
                    $('#js-edit-meeting-check').prop('checked', true);
                    $('#js-edit-meeting-id').val(e.meeting_id);
                    $('#js-edit-meeting-url').val(e.meeting_url);
                    $('#js-edit-meeting-call').val(e.meeting_call_number);
                    $('#js-edit-meeting-box').show();
                }
                //
                if (e.message_check == 1) {
                    $('#js-edit-message-check').prop('checked', true);
                    $('#js-edit-message-subject').val(e.subject);
                    $('#js-edit-message-body').val(e.message);
                    $('#js-edit-message-box').show();
                }
                //
                if (e.reminder_flag == 1) {
                    $('#js-edit-reminder-check').prop('checked', true);
                    $('#js-edit-reminder-select').val(e.duration);
                    $('#js-edit-reminder-box').show();
                }
                var show_interviewer_email_list = e.interviewer_show_email != '' && e.interviewer_show_email != null ? e.interviewer_show_email.split(',') : [];

                $('#js-edit-address-new').val(e.address);
                $('#js-edit-users-phone').val(e.users_phone);
                if (e.interviewers_details.length > 0) {
                    var selected_vals = [];
                    $.each(e.interviewers_details, function(i, v) {
                        selected_vals.push(v.id);
                        $('#js_edit_show_email_container_0_' + v.id + '').show();
                    });

                    $('#js-edit-interviewer').select2('val', selected_vals);
                    // console.log(selected_vals);

                    if (e.category_uc != 'Training-session') {
                        if (show_interviewer_email_list.length > 0) {
                            $.each(show_interviewer_email_list, function(i0, v0) {
                                $('#js_edit_show_email_0_' + v0 + '').
                                prop('checked', true);
                            });
                        }
                        // $('#js-edit-interviewer').select2('val', <?= $main_employer_id; ?>);
                        // $('#js_edit_show_email_container_0_'+<?= $main_employer_id; ?>+'').show(0);
                        // $('#js_edit_show_email_0_'+<?= $main_employer_id; ?>+'').prop('disabled', false);
                        $('#js-edit-interviewers-list').show();
                    } else {
                        $('.js_edit_show_email_col').hide(0);
                        $('#js-edit-interviewers-list').hide(0);
                    }
                    // $('#js-edit-interviewer').trigger('change');
                }


                if (e.hasOwnProperty('applicant_details') && e.applicant_details.hasOwnProperty('jobs') && e.applicant_details.jobs.length > 0) {
                    $.each(e.applicant_details.jobs, function(i, v) {
                        if (v.job_title == '' || v.job_title == 'null' || v.job_title == null) return true;
                        var rows = '';
                        rows += '<div class="col-xs-6">';
                        rows += '  <label class="control control--checkbox">';
                        rows += v.job_title;
                        rows += '      <input name="applicant_jobs_list[]" class="external_participants_show_email js-edit-applicant-job js-edit-applicant-job-' + v.list_sid + '" value="' + v.list_sid + '" type="checkbox"  />';
                        rows += '      <div class="control__indicator"></div>';
                        rows += '  </label>';
                        rows += '<div>';
                        $('.js-edit-applicant-box').append(rows);
                    });
                }

                var applicant_jobs_list = e.applicant_jobs_list != null ? e.applicant_jobs_list.split(',') : [];
                //
                if (applicant_jobs_list.length > 0) {
                    $.each(applicant_jobs_list, function(i, v) {
                        $('.js-edit-applicant-job-' + v + '').prop('checked', true);
                    });
                }

                var category = e.category_uc.toLowerCase();
                $('#js-edit-event-selected-type-value').val(category);
                $('#js-edit-event-selected-type-text').text(event_obj[category]);
                $('#js-edit-event-selected-type-area').css({
                    'background-color': event_color_obj[category],
                    'color': '#ffffff'
                });


                // Add history and clone buttons
                load_extra_buttons(e.parent_event_sid, e.event_history_count, e.event_reminder_email_history, e.event_change_history);

                // Check for Recur
                if (show_recur_btn == 1 && e.is_recur == 1) {
                    var recur_obj = {};
                    recur_obj.end_date = e.recur_end_date == null ? default_end_date : moment(e.recur_end_date).format('MM-DD-YYYY');
                    var list = JSON.parse(e.recur_list);
                    var type = e.recur_type;

                    switch (e.recur_type) {
                        case 'Yearly':
                            recur_obj.years = list.Years;
                            recur_obj.months = reset_array_for_recur(list.Months, 12);
                            recur_obj.weeks = list.Weeks;
                            recur_obj.days = reset_array_for_recur(list.Days, 7);
                            break;
                        case 'Monthly':
                            recur_obj.months = reset_array_for_recur(list.Months, 12);
                            recur_obj.weeks = list.Weeks;
                            recur_obj.days = reset_array_for_recur(list.Days, 7);
                            break;
                        case 'Weekly':
                            recur_obj.weeks = list.Weeks;
                            recur_obj.days = reset_array_for_recur(list.Days, 7);
                            break;
                        case 'Daily':
                            recur_obj.days = reset_array_for_recur(list.Days, 7);
                            break;
                    }

                    $('#js-edit-reoccur-check').prop('checked', true);
                    $('#js-edit-reoccur-box').show(0);
                    load_recurr_view(e.recur_type.toLowerCase(), recur_obj);
                    if (event.recur_end_date == null)
                        $('.js-edit-infinite').prop('checked', true);
                }

                if (e.external_participants.length > 0)
                    generate_extra_interviewers(e.external_participants);
                var user_details = {
                    email_address: (e.users_type == 'applicant' ? e.applicant_details.email_address : e.employer_details.email_address),
                    value: (e.users_type == 'applicant' ? e.applicant_details.value : e.employer_details.value),
                    id: (e.users_type == 'applicant' ? e.applicant_details.id : e.employer_details.id),
                    type: e.users_type,
                    timezone: (e.users_type == 'applicant' ? e.event_timezone : e.employer_details.timezone),
                }
                // When event is expired
                generate_reminder_email_rows(
                    e.interviewers_details,
                    e.external_participants,
                    user_details
                );


                <?php if (check_access_permissions_for_view($security_details, 'application_tracking')) { ?>
                    if (e.event_status == 'confirmed') {
                        $('#js-edit-cancelled-message').hide();
                        $('#js-edit-update').show();
                        $('#js-edit-cancel').show();
                        $('#js-edit-delete').show();
                        $('#js-edit-reschedule').hide();
                    } else if (e.event_status == 'cancelled') {
                        $('#js-edit-cancelled-message').show();
                        $('#js-edit-reschedule').show();
                    } else if (e.event_status == 'pending') {
                        $('#js-edit-cancelled-message').hide();
                        $('#js-edit-update').show();
                        $('#js-edit-cancel').show();
                        $('#js-edit-delete').show();
                        $('#js-edit-reschedule').hide();
                    } else {
                        $('#js-edit-cancelled-message').show();
                    }
                <?php } else { ?>
                    if (e.employers_sid == "<?= $employer_id; ?>") {
                        if (e.event_status == 'confirmed') {
                            $('#js-edit-cancelled-message').hide();
                            $('#js-edit-update').show();
                            $('#js-edit-cancel').show();
                            $('#js-edit-delete').show();
                            $('#js-edit-close').show();
                        } else if (e.event_status == 'pending') {
                            $('#js-editcancelled-message').hide();
                            $('#js-edit-update').show();
                            $('#js-edit-cancel').show();
                            $('#js-edit-delete').show();
                            $('#js-edit-reschedule').hide();
                        } else if (e.event_status == 'cancelled') {
                            $('#js-edit-cancelled-message').show();
                            $('#save').hide();
                            $('#js-edit-close').show();
                            $('#js-edit-reschedule').show();
                        } else {
                            $('#js-edit-cancelled-message').show();
                        }
                    } else {
                        $('#js-edit-cancelled-message').hide();
                        $('#js-edit-close').show();
                    }
                <?php } ?>
                var is_event_expired = false;

                // check for expired 
                // events
                $('#js-edit-cancelled-message').html('Event cancelled!!').hide(0);
                if (
                    moment(e.date + ' 23:59:59') <
                    moment().utc()
                ) {
                    $('#js-edit-cancelled-message').html('Event expired!!').show();
                    $('#js-edit-update').hide();
                    $('#js-edit-cancel').hide();
                    $('#js-edit-delete').hide();
                    $('#js-edit-reschedule').show();
                    $('#js-edit-close').show();
                    is_event_expired = true;
                }

                $('#js-edit-reminder-email-wrap').show(0);
                if (is_event_expired) $('#js-edit-reminder-email-wrap').hide(0);

                // Disable date picker when 
                // event is expired
                if (is_event_expired === true) {
                    $('#js-edit-date').datepicker('disable');
                    $('#js-edit-start-time').prop('disabled', true);
                    $('#js-edit-end-time').prop('disabled', true);
                }

                if (is_event_expired) $('#js-edit-reschedule').prop('id', 'js-edit-expired-reschedule');

                <?php if ($users_type == 'Employee') { ?>
                    // Learning Center check
                    if (e.category_uc == 'Training-session') {
                        // load view
                        $('#js-edit-interviewers-list').hide(0);
                        $('label#js-edit-attendees-label').text('Assigned Attendees');
                        $('#js-edit-non-employee-heading').text('Assigned non-employee Attendees');
                        $('.js-edit-comment-wrap, .js-edit-message-wrap, #js-edit-message-box, .js-edit-meeting-wrap').hide(0);
                        $('.js-edit-message-hr').hide(0);
                        $('.js-edit-comment-hr').hide(0);
                        <?php if ($calendar_opt['show_online_videos'] === 1) { ?>
                            $('#js-edit-video-wrap, .js-video-hr').show(0);
                        <?php } ?>

                        $('#js-lcts-id').val(e.learning_center_training_sessions);

                        append_all_to_interviewers((e.interviewer != null && (e.interviewer.split(',').length == employee_list.length)) ? undefined : false);

                        <?php if ($calendar_opt['show_online_videos'] === 1) { ?>
                            if (e.online_video_sid != 'null' && e.online_video_sid != null) {
                                $('#js-edit-video-wrap select').select2('val', e.online_video_sid.split(','));
                            }
                        <?php } ?>
                        $('.js-edit-interviewers-wrap').show(0);
                    }
                    current_event_type = e.category_uc.toLowerCase();
                <?php } ?>

                // Blinker check
                if (e.event_requests != 0) {
                    $('.js-status-history-btn').addClass('js-status-btn-blinker');
                    $('.fc-event-' + e.event_id + '').addClass('fc-event-blink');
                    event_blinker('button');
                }

                // Do this before you initialize any of your modals
                $.fn.modal.Constructor.prototype.enforceFocus = function() {};
                $('#js-event-edit-modal').modal('show');
                $('.btn').prop('disabled', false);
                $('#spinner_' + event_sid).hide();

            });
        }

        // Create rows for selected extra interviewers
        // Occurs only on update event
        // @param extra_interviewers
        // Holds the data of extra interviewers
        function generate_extra_interviewers(extra_interviewers) {
            var rows = '';
            //
            $.each(extra_interviewers, function(i, v) {
                var rand_id = Math.floor((Math.random() * 1000) + 1);
                rows += '<div id="js-edit-ep-' + i + '" class="row external_participants">';
                rows += '    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">';
                rows += '        <div class="form-group">';
                rows += '            <label>Name</label>';
                rows += '            <input name="ext_participants[' + i + '][name]" id="js-edit-ep-' + i + '-name" type="text" class="form-control external_participants_name" value="' + v.name + '" />';
                rows += '        </div>';
                rows += '    </div>';
                rows += '    <div class="col-lg-5 col-md-5 col-xs-12 col-sm-6">';
                rows += '        <div class="form-group">';
                rows += '            <label>Email</label>';
                rows += '            <input name="ext_participants[' + i + '][email]" id="js-edit-ep-' + i + '-email" type="email" class="form-control external_participants_email" value="' + v.email + '" />';
                rows += '        </div>';
                rows += '    </div>';
                rows += '    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">';
                rows += '        <div class="form-group">';
                rows += '            <label class="control control--checkbox margin-top-20">';
                rows += '                Show Email';
                rows += '                <input name="ext_participants[' + i + '][show_email]" ' + (v.show_email == 1 ? 'checked="checked"' : '') + ' id="js-edit-ep-' + i + '-show_email" class="external_participants_show_email" value="1"  type="checkbox" />';
                rows += '                <div class="control__indicator"></div>';
                rows += '            </label>';
                rows += '        </div>';
                rows += '    </div>';
                rows += '    <div class="col-lg-1 col-md-1 col-xs-12 col-sm-1">';
                if (i == 0)
                    rows += '<button id="js-edit-btn-add-participant" type="button" class="btn btn-success btn-equalizer btn-block margin-top-20"><i class="fa fa-plus-square"></i></button>';
                else
                    rows += '<button  type="button" class="btn btn-danger btn-equalizer btn_remove_participant btn-block margin-top-20"><i class="fa fa-plus-square fa-trash" data-id="' + rand_id + '"></i></button>';
                rows += '    </div>';
                rows += '</div>';
            });
            $('#js-edit-ep-box').html(rows);
        }

        //
        function reset_modal() {
            current_edit_event_details = {};
            load_categories(user_type, '#js-edit-event-type-area');
            $('#js-edit-interviewer').select2();
            $('#js-edit-comment-check, #js-edit-meeting-check, #js-edit-message-check, #js-edit-reminder-check, #js-edit-recur-check').prop('checked', false);
            $('#js-edit-comment-msg, #js-edit-message-subject, #js-edit-message-body').val('');
            $('#js-edit-meeting-id, #js-edit-meeting-call, #js-edit-meeting-url').val('');
            $('#js-edit-reminder-select').val(15);
            $('#js-edit-meeting-box, #js-edit-message-box, #js-edit-comment-box').hide(0);
            $('#js-edit-reminder-email-box').hide(0);

            $('.js-edit-recurr-type').prop('checked', false);
            $('#js-edit-reoccur-check').prop('checked', false);
            $('#js-edit-recurr-daily-check').prop('checked', true);
            $('#js-edit-reoccur-box').hide(0);
            $('#js-edit-reminder-email-check').prop('checked', false);

            $('.js-edit-modal-btn').hide(0);

            $('#js-edit-date').val("<?= date('m-d-Y'); ?>");
            $('#js-edit-start-time').val(default_start_time);
            $('#js-edit-end-time').val(default_end_time);

            func_make_time_pickers();
            func_make_date_picker();

            recur_page_REF.hide(0);
            reschedule_page_REF.hide(0);
            reminder_page_REF.hide(0);
            change_history_page_REF.hide(0);
            request_page_REF.hide(0);
            extra_btns_wrap.hide(0);
            main_page_REF.show(0);

            $('.js-edit-modal-btn').hide(0);
            extra_btns_wrap.html('');

            if (show_recur_btn == 0) {
                $('#js-edit-recur-check').prop('checked', false);
                $('#js-edit-reoccur-wrap').hide();
            }
            $('.js-edit-applicant-box').html('');

            $('#js-edit-event-start-time').prop('disabled', false);
            $('#js-edit-event-end-time').prop('disabled', false);

            $('#js-edit-expired-reschedule').prop('id', 'js-edit-reschedule');

            $('#js-edit-cancelled-message').hide(0);
            $('.js-modal-footer div').show();
            $('.js_edit_show_email_col').hide();

            //
            $('#js-edit-event-title').val('');
        }

        //
        $(document).on('click', '.js-edit-address-type', function() {
            if ($(this).val() == 'saved') {
                $('#js-edit-address-input-box').hide();
                $('#js-edit-address-select-box').show();
            } else if ($(this).val() == 'new') {
                $('#js-edit-address-input-box').show();
                $('#js-edit-address-select-box').hide();
            }
        });

        $(document).on('click', '#js-edit-comment-check', function() {
            $('#js-edit-comment-box').toggle();
        });
        $(document).on('click', '#js-edit-reminder-check', function() {
            $('#js-edit-reminder-box').toggle();
        });
        $(document).on('click', '#js-edit-meeting-check', function() {
            $('#js-edit-meeting-box').toggle();
        });
        $(document).on('click', '#js-edit-message-check', function() {
            $('#js-edit-message-box').toggle();
        });
        $(document).on('click', '#js-edit-reoccur-check', function() {
            $('#js-edit-reoccur-box').toggle();
        });
        $(document).on('click', '#js-edit-reminder-email-check', function() {
            $('#js-edit-reminder-email-box').toggle();
        });

        $(document).on('click', '#js-edit-btn-add-participant', function() {
            var random_id = Math.floor((Math.random() * 1000) + 1);
            var new_row = $('#js-edit-ep-0').clone();
            var event_id = $('#event_id').val();
            event_id = event_id == '' || event_id == undefined || event_id == null ? 0 : event_id;
            $(new_row).find('i.fa').removeClass('fa-plus').addClass('fa-trash');
            $(new_row).find('button.btn').removeAttr('id').removeClass('btn-success').removeClass('btn_add_participant').addClass('btn-danger').addClass('btn_remove_participant').attr('data-id', random_id);
            $(new_row).find('input').val('');
            $(new_row).attr('id', 'js-edit-ep-' + random_id);
            $(new_row).addClass('external_participants');
            $(new_row).attr('data-id', random_id);
            $(new_row).find('input.external_participants_name').attr('data-rule-required', true);
            $(new_row).find('input.external_participants_email').attr('data-rule-required', true);
            $(new_row).find('input.external_participants_email').attr('data-rule-email', true);
            $(new_row).find('input').each(function() {

                var name = $(this).attr('name').toString();

                var id = $(this).attr('id').toString();

                name = name.split('0').join(random_id);

                id = id.split('0').join(random_id);

                $(this).attr('name', name);

                $(this).attr('id', id);
            });

            $('#js-edit-ep-box').append(new_row);
        });

        // Set category on click 
        $(document).on('click', '.js-edit-btn-category', function() {
            current_event_type = $(this).data('id');
            if (user_type == 'employee') {
                $('#js-edit-interviewers-list').show(0);

                $('label#js-edit-attendees-label').text(default_interviewer_text);
                $('#js-edit-non-employee-heading').text(default_einterviewer_text);
                $('.js-edit-comment-wrap, .js-edit-message-wrap, #js-edit-message-box, .js-edit-meeting-wrap').show(0);
                $('.js-edit-message-hr').show(0);
                $('.js-edit-comment-hr').show(0);

                if ($('#js-edit-message-check').prop('checked') === false)
                    $('#js-edit-message-box').hide(0);
                if ($('#js-edit-meeting-check').prop('checked') === false)
                    $('#js-edit-meeting-box').hide(0);

                $('#js-edit-video-wrap, .js-edit-video-hr').hide(0);

                $('.js_edit_show_email_col').hide(0);
                remove_all_from_interviewers();
                // For training session
                if ($(this).data('id') == 'training-session') {
                    $('#js-edit-interviewers-list').hide(0);

                    $('label#js-edit-attendees-label').text('Assigned Attendees');
                    $('#js-edit-non-employee-heading').text('Assigned non-employee Attendees');
                    $('.js-edit-comment-wrap, .js-edit-message-wrap, #js-edit-message-box, .js-edit-meeting-wrap, #js-edit-meeting-box').hide(0);
                    $('.js-edit-message-hr').hide(0);
                    $('.js-edit-comment-hr').hide(0);
                    $('#js-edit-video-wrap, .js-edit-video-hr').show(0);

                    $('.js_edit_show_email_col').hide(0);

                    append_all_to_interviewers();
                }
            }
            //
            $('#js-edit-event-selected-type-value').val($(this).data('id'));
            $('#js-edit-event-selected-type-text').text(event_obj[$(this).data('id')]);
            $('#js-edit-event-selected-type-area').css({
                'background-color': event_color_obj[$(this).data('id')],
                'color': '#ffffff'
            });
        });

        $('body').on('click', '.btn_remove_participant', function() {
            $($(this).closest('.external_participants').get()).remove();
        });

        // Added on: 24-04-2019
        // Loads categories
        // @param type
        // The user type
        // @param target_OBJ
        // Target el array
        function load_categories(type, target_OBJ) {
            var target = $(target_OBJ),
                rows = '',
                selected = event_type.default_applicant,
                arr = event_type.applicant;
            target.html('');
            //
            if (type == 'employee') {
                selected = event_type.default_employee;
                arr = event_type.employee;
            }
            // Sort array by asc
            arr = _.sortBy(arr);
            var cls = 'js-btn-category';
            if (edit_mode != '') cls += ' js-edit-btn-category';
            $.each(arr, function(i, v) {
                if (v == 'training-session') {
                    <?php if ($this->session->userdata('logged_in')['company_detail']['ems_status']) { ?>
                        rows += '<li><button type="button" data-id="' + v + '" class="' + cls + ' btn btn-default btn-block dropdown-btn cs-event-btn-' + v + ' training-session-tile">' + event_obj[v] + '</button></li>';
                    <?php } ?>
                } else {
                    rows += '<li><button type="button" data-id="' + v + '" class="' + cls + ' btn btn-default btn-block dropdown-btn cs-event-btn-' + v + ' training-session-tile">' + event_obj[v] + '</button></li>';
                }
            });

            $('#js-edit-event-selected-type-value').val(selected);
            $('#js-edit-event-selected-type-text').text(event_obj[selected]);
            $('#js-edit-event-selected-type-area').css({
                'background-color': event_color_obj[selected],
                'color': '#ffffff'
            });
            target.html(rows);
        }

        // TODO
        // Check for reoccur events
        // @param e
        // Holds the current render event
        function check_for_reoccur_event(e) {
            if (e.is_recur == 0) return true;
            // 
            // if(e.event_id != '1799') return false;

            var return_value = false,
                recur_obj = {},
                list = JSON.parse(e.recur_list);
            recur_obj.start_date = moment(e.recur_start_date);
            recur_obj.end_date = null;
            if (e.recur_end_date != null)
                recur_obj.end_date = moment(e.recur_end_date);

            // Check for reoccur type
            switch (e.recur_type) {
                case 'Yearly':
                    recur_obj.years = list.Years;
                    // Check for years
                    return_value = check_recur_years(e, recur_obj);
                    if (return_value === false) return false;

                    recur_obj.months = reset_array_for_recur(list.Months, 12);
                    // Check for months
                    return_value = check_recur_months(e, recur_obj);
                    if (return_value === false) return false;

                    recur_obj.weeks = list.Weeks;
                    // Check for weeks
                    return_value = check_recur_weeks(e, recur_obj);
                    if (return_value === false) return false;

                    recur_obj.days = reset_array_for_recur(list.Days, 7);
                    // Check for timeframe
                    return_value = check_recur_days(e, recur_obj);
                    break;
                case 'Monthly':
                    recur_obj.months = reset_array_for_recur(list.Months, 12);
                    // Check for months
                    return_value = check_recur_months(e, recur_obj);
                    if (return_value === false) return false;

                    recur_obj.weeks = list.Weeks;
                    // Check for weeks
                    return_value = check_recur_weeks(e, recur_obj);
                    if (return_value === false) return false;

                    recur_obj.days = reset_array_for_recur(list.Days, 7);
                    // Check for timeframe
                    return_value = check_recur_days(e, recur_obj);
                    break;
                case 'Weekly':
                    recur_obj.weeks = list.Weeks;
                    recur_obj.days = reset_array_for_recur(list.Days, 7);

                    // Check for weeks
                    // Don't check the end time if
                    return_value = check_recur_weeks(e, recur_obj);
                    if (return_value === false) return false;

                    // Check for timeframe
                    // Don't check the end time if
                    return_value = check_recur_days(e, recur_obj);
                    break;
                case 'Daily':
                    recur_obj.days = reset_array_for_recur(list.Days, 7);
                    // Check for timeframe
                    // Don't check the end time if
                    return_value = check_recur_days(e, recur_obj);
                    break;
            }

            return return_value;
        }

        // Check recur days check
        function check_recur_days(e, recur_obj) {

            var is_return = false,
                is_return_2 = false;
            // it's null
            if (recur_obj.end_date == null)
                // 2019-05-08 >= 2019-05-08
                is_return = e.start.isAfter(recur_obj.start_date) || e.start.format('MM-DD-YYYY') == recur_obj.start_date.format('MM-DD-YYYY');
            // is_return = e.start.isAfter(recur_obj.start_date) || e.start.format('MM-DD-YYYY') == recur_obj.start_date.format('MM-DD-YYYY');
            // 2019-05-08 <= 2019-09-04 && 2019-05-08 >= 2019-05-08
            else if (recur_obj.end_date != null) {
                is_return =
                    (e.start.isBefore(recur_obj.end_date) || e.start.format('MM-DD-YYYY') == recur_obj.start_date.format('MM-DD-YYYY')) &&
                    (e.end.isAfter(recur_obj.start_date) || e.end.format('MM-DD-YYYY') == recur_obj.end_date.format('MM-DD-YYYY'));
            }

            is_return_2 = (recur_obj.days.hasOwnProperty('is_all') || recur_obj.days.hasOwnProperty(e.start.day()));

            // console.log(
            // e.start.format('MM-DD-YYYY'),
            // e.end.format('MM-DD-YYYY'),
            // recur_obj.start_date.format('MM-DD-YYYY'),
            // recur_obj.end_date.format('MM-DD-YYYY')

            // recur_obj
            // );

            // console.log(recur_obj.days.hasOwnProperty('is_all') || recur_obj.days.hasOwnProperty( e.start.day() ), e.start.day(), recur_obj.days, e.start.format('MM-DD-YYYY'), is_return, is_return_2);
            // console.log( e.start.day(), recur_obj.days, e.start.format('MM-DD-YYYY') );

            return is_return && is_return_2;
        }

        // Check recur weeks check
        function check_recur_weeks(e, recur_obj) {
            return moment(e.start).week() % recur_obj.weeks === 0 ? true : false
        }

        // Check recur months check
        function check_recur_months(e, recur_obj) {
            if (recur_obj.months.hasOwnProperty('is_all'))
                return true;
            return recur_obj.months.hasOwnProperty(e.start.month() + 1);
        }

        // Check recur years check
        function check_recur_years(e, recur_obj) {
            return moment(e.start).year() % recur_obj.years === 0 ? true : false
        }

        // Generates HTML for recurr view
        // @param type
        // @param selected
        function get_recurr_html(type, selected) {
            var rows = '';
            //
            switch (type) {
                case 'days_row':
                    rows += '<div class="cs-row">';
                    rows += '    <div class="row">';
                    rows += '        <div class="col-sm-2">';
                    rows += '            <p><strong>On Days</strong></p>';
                    rows += '        </div>';
                    rows += '        <div class="col-sm-10">';
                    $.each(recurr_days, function(i, v) {
                        var key = i++,
                            is_class = (selected !== undefined && (selected.hasOwnProperty(key) || selected.hasOwnProperty('is_all'))) ? 'cs-active-day' : '';
                        rows += '        <span data-key="' + key + '" title="' + v + '" class="cs-day js-edit-day-box ' + is_class + '">' + (v.substring(0, 1)) + '</span>';
                    });
                    rows += '        </div>';
                    rows += '    </div>';
                    rows += '</div>';
                    break;

                case 'end_date_row':
                    rows += '<div class="cs-row">';
                    rows += '    <div class="row">';
                    rows += '        <div class="col-sm-2">';
                    rows += '            <p><strong>Ends On</strong></p>';
                    rows += '        </div>';
                    rows += '        <div class="col-sm-10">';
                    rows += '            <div class="row">';
                    rows += '                <div class="col-sm-4">';
                    rows += '                    <input type="text" readonly="true" class="js-edit-recurr-datepicker" ' + (selected === undefined ? '' : 'value="' + selected + '"') + ' />';
                    rows += '                </div>';
                    rows += '                <div class="col-sm-4">';
                    rows += '                    <label class="control control--checkbox">';
                    rows += '                        Infinite';
                    rows += '                        <input type="checkbox" class="js-edit-infinite"/>';
                    rows += '                        <div class="control__indicator"></div>';
                    rows += '                    </label>';
                    rows += '                </div>';
                    rows += '            </div>';
                    rows += '        </div>';
                    rows += '    </div>';
                    rows += '</div>';
                    break;

                case 'summary_row':
                    rows += '<div class="cs-row">';
                    rows += '    <div class="row">';
                    rows += '        <div class="col-sm-2">';
                    rows += '            <p><strong>Summary</strong></p>';
                    rows += '        </div>';
                    rows += '        <div class="col-sm-10">';
                    rows += '            <p class="js-edit-summary-row"></p>';
                    rows += '        </div>';
                    rows += '    </div>';
                    rows += '</div>';
                    break;
                    // 
                case 'week_row':
                    rows += '<div class="cs-row">';
                    rows += '    <div class="row">';
                    rows += '        <div class="col-sm-2">';
                    rows += '            <p><strong>Repeat Every</strong></p>';
                    rows += '        </div>';
                    rows += '        <div class="col-sm-10">';
                    rows += '            <div class="row">';
                    rows += '                <div class="col-sm-2">';
                    rows += '                    <input value="' + (selected === undefined ? 1 : selected) + '" type="text" minLength="1" id="js-edit-recurr-week-input" class="form-control" />';
                    rows += '                </div>';
                    rows += '                <div class="col-sm-8">';
                    rows += '                    <span>Weeks</span>';
                    rows += '                </div>';
                    rows += '             </div>';
                    rows += '        </div>';
                    rows += '    </div>';
                    rows += '</div>';
                    break;
                    // 
                case 'month_row':
                    rows += '<div class="cs-row">';
                    rows += '    <div class="row">';
                    rows += '        <div class="col-sm-2">';
                    rows += '            <p><strong>On Months</strong></p>';
                    rows += '        </div>';
                    rows += '        <div class="col-sm-10">';
                    $.each(recurr_months, function(i, v) {
                        var key = i++,
                            is_class = (selected !== undefined && (selected.hasOwnProperty(key) || selected.hasOwnProperty('is_all'))) ? 'cs-active-day' : '';
                        rows += '        <span data-key="' + key + '" title="' + v + '" class="cs-day js-edit-month-box ' + is_class + '">' + (v.substring(0, 1)) + '</span>';
                    });
                    rows += '        </div>';
                    rows += '    </div>';
                    rows += '</div>';
                    break;

                case 'year_row':
                    rows += '<div class="cs-row">';
                    rows += '    <div class="row">';
                    rows += '        <div class="col-sm-2">';
                    rows += '            <p><strong>Repeat Every</strong></p>';
                    rows += '        </div>';
                    rows += '        <div class="col-sm-10">';
                    rows += '            <div class="row">';
                    rows += '                <div class="col-sm-2">';
                    rows += '                    <input value="' + (selected === undefined ? 1 : selected) + '" type="text" minLength="1" id="js-edit-recurr-year-input" class="form-control" />';
                    rows += '                </div>';
                    rows += '                <div class="col-sm-8">';
                    rows += '                    <span>Years</span>';
                    rows += '                </div>';
                    rows += '             </div>';
                    rows += '        </div>';
                    rows += '    </div>';
                    rows += '</div>';
                    break;

                case 'every_day_row':
                    rows += '<div class="cs-row">';
                    rows += '    <div class="row">';
                    rows += '        <div class="col-sm-2">';
                    rows += '            <p><strong>Repeat</strong></p>';
                    rows += '        </div>';
                    rows += '        <div class="col-sm-10">';
                    rows += '            <label class="control control--checkbox">';
                    rows += '                  Every Day';
                    rows += '                  <input type="checkbox" />';
                    rows += '                  <div class="control__indicator"></div>';
                    rows += '            </label>';
                    rows += '        </div>';
                    rows += '    </div>';
                    rows += '</div>';
                    break;
            }
            return rows;
        }

        // Load recurr view
        // @param type
        // Recur type
        // @param data
        // Holds data for edit mode
        function load_recurr_view(type, data) {
            var row = '',
                is_load = false;
            switch (type) {
                case 'daily':
                    row +=
                        get_recurr_html('days_row', data === undefined ? default_days : data.days) +
                        get_recurr_html('end_date_row', data === undefined ? default_end_date : data.end_date) +
                        get_recurr_html('summary_row');
                    is_load = 'daily';
                    break;
                case 'weekly':
                    row +=
                        get_recurr_html('week_row', data === undefined ? default_weeks : data.weeks) +
                        get_recurr_html('days_row', data === undefined ? default_days : data.days) +
                        get_recurr_html('end_date_row', data === undefined ? default_end_date : data.end_date) +
                        get_recurr_html('summary_row');
                    is_load = 'weekly';
                    break;
                case 'monthly':
                    row +=
                        get_recurr_html('month_row', data === undefined ? default_months : data.months) +
                        get_recurr_html('week_row', data === undefined ? default_weeks : data.weeks) +
                        get_recurr_html('days_row', data === undefined ? default_days : data.days) +
                        get_recurr_html('end_date_row', data === undefined ? default_end_date : data.end_date) +
                        get_recurr_html('summary_row');
                    is_load = 'monthly';
                    break;
                case 'yearly':
                    row +=
                        get_recurr_html('year_row', data === undefined ? 2 : data.years) +
                        get_recurr_html('month_row', data === undefined ? default_months : data.months) +
                        get_recurr_html('week_row', data === undefined ? default_weeks : data.weeks) +
                        get_recurr_html('days_row', data === undefined ? default_days : data.days) +
                        get_recurr_html('end_date_row', data === undefined ? default_end_date : data.end_date) +
                        get_recurr_html('summary_row');
                    is_load = 'yearly';
                    break;
            }
            $('.js-edit-row-view').html(row);
            // Load datepikcer for
            // recurr event
            $(".js-edit-recurr-datepicker").datepicker({
                dateFormat: 'mm-dd-yy',
                minDate: 0,
                onSelect: function() {
                    set_summary();
                }
            });
            // Trigger set summary if is_load
            // check is active
            if (is_load) {
                $('.js-edit-recurr-type').prop('checked', false);
                $('#js-edit-recurr-' + is_load + '-check').prop('checked', true);
                set_summary();
            }
        }

        // Set summary for recurr event
        function set_summary() {
            var rows = '',
                number_of = null,
                type = 'week';

            // For monthly
            if ($('#js-edit-recurr-monthly-check').prop('checked') === true ||
                $('#js-edit-recurr-yearly-check').prop('checked') === true) {
                var month_length = $('.js-edit-month-box.cs-active-day').length;
                if (month_length !== 0) {
                    rows += ' for month' + (month_length === 1 ? ' ' : 's ');
                    // Loop through selected
                    $.each($('.js-edit-month-box.cs-active-day'), function(i, v) {
                        if (i == 0)
                            rows += recurr_months[$(this).data('key')];
                        else if (++i == month_length)
                            rows += ' and ' + recurr_months[$(this).data('key')];
                        else
                            rows += ', ' + recurr_months[$(this).data('key')];
                    });
                }
                // rows += ' ';
                number_of = $('#js-edit-recurr-week-input').val();
                type = 'week' + (number_of == 1 ? '' : 's');
            }

            // For daily view
            if ($('#js-edit-recurr-daily-check').prop('checked') === true ||
                $('#js-edit-recurr-weekly-check').prop('checked') === true ||
                $('#js-edit-recurr-monthly-check').prop('checked') === true ||
                $('#js-edit-recurr-yearly-check').prop('checked') === true) {
                var day_length = $('.js-edit-day-box.cs-active-day').length;
                rows += ' on ';
                // Loop through selected
                $.each($('.js-edit-day-box.cs-active-day'), function(i, v) {
                    if (i == 0)
                        rows += recurr_days[$(this).data('key')];
                    else if (++i == day_length)
                        rows += ' and ' + recurr_days[$(this).data('key')];
                    else
                        rows += ', ' + recurr_days[$(this).data('key')];
                });
            }

            // For weekly
            if ($('#js-edit-recurr-weekly-check').prop('checked') === true) {
                number_of = $('#js-edit-recurr-week-input').val();
                type = 'week' + (number_of == 1 ? '' : 's');
            }

            // For yearly
            if ($('#js-edit-recurr-yearly-check').prop('checked') === true) {
                number_of = $('#js-edit-recurr-year-input').val();
                type = 'year' + (number_of == 1 ? '' : 's');
            }

            // Check if nothing was selected
            if (rows == '') {
                $('.js-edit-summary-row').text('');
                return false;
            }
            // Load end time if defined
            if ($('.js-edit-infinite').prop('checked') === false)
                rows += ' until ' + moment($('.js-edit-recurr-datepicker').val()).format('MMMM Do, YYYY');
            // Add prefix
            rows = 'Repeats ' + (number_of === null ? '' : 'every ' + number_of + ' ' + type + ' ') + ' ' + rows;
            // Load summary to DOM
            $('.js-edit-summary-row').html(rows);
        }

        // Convert array to object
        // @param arr
        // Array to be converted in Object
        // @param check_length
        // Length
        function reset_array_for_recur(arr, check_length) {
            //
            if (arr == '*') return {
                is_all: true
            };
            //
            if (arr.length === check_length) return {
                is_all: true
            };
            //
            var tmp = {};
            $.each(arr, function(i, v) {
                tmp[v] = true;
            });
            return tmp;
        }

        // Load recurr view
        $(document).on('click', '.js-edit-recurr-type', function() {
            load_recurr_view($(this).val().toLowerCase());
        });

        // Days on, off
        $(document).on('click', '.js-edit-day-box', function() {
            if ($(this).hasClass('cs-active-day'))
                $(this).removeClass('cs-active-day');
            else
                $(this).addClass('cs-active-day');

            set_summary();
        });

        // Months on, off
        $(document).on('click', '.js-edit-month-box', function() {
            if ($(this).hasClass('cs-active-day'))
                $(this).removeClass('cs-active-day');
            else
                $(this).addClass('cs-active-day');

            set_summary();
        });

        // Triggeres when infinite check
        // is changed
        $(document).on('click', '.js-edit-infinite', function() {
            set_summary();
        });

        // Triggers when
        // week is entered 
        $(document).on('keyup', '#js-edit-recurr-week-input', function() {
            if (isNaN($(this).val()) || $(this).val() < 1)
                $(this).val(1);
            set_summary();
        });

        // Triggers when 
        // year is entered
        $(document).on('keyup', '#js-edit-recurr-year-input', function() {
            if (isNaN($(this).val()) || $(this).val() < 1)
                $(this).val(1);
            set_summary();
        });

        // Triggers on
        // reccur check
        $(document).on('click', '#js-edit-reoccur-check', function() {
            $('#js-edit-reoccur-box').hide();
            if ($(this).prop('checked') === true) {
                $('#js-edit-reoccur-box').show();
                $('#js-edit-recurr-daily-check').prop('checked', true);
                load_recurr_view('daily');
            }
        });

        // Get selected days
        function get_selected_days() {
            var tmp = [];
            $('.js-edit-day-box.cs-active-day').map(function() {
                tmp.push($(this).data('key'));
            });
            return tmp;
        }

        // Get selected months
        function get_selected_months() {
            var tmp = [];
            $('.js-edit-month-box.cs-active-day').map(function() {
                tmp.push($(this).data('key'));
            });
            return tmp;
        }

        // Load history and clone
        // buttons
        // @param parent_event_sid
        // Holds parent event sid
        // @param event_history_count
        // Count of event history rows
        // 0 for null
        // @param event_reminder_email_history
        // Count of sent reminder emails
        function load_extra_buttons(parent_event_sid, event_history_count, event_reminder_email_history, event_change_history) {
            if (show_clone_btn) {
                if (parent_event_sid != 0)
                    $('.modal-header h4').append('<span class="label label-danger disabled"  style="margin-left: 20px;">Cloned</span>');
            }
            var rows = '';
            if (show_request_status_history_btn) {
                if (event_history_count != 0)
                    rows += '<button class="btn btn-info js-status-history-btn pull-right" style="margin-top: -6px;">Status</button>';

            }
            if (show_sent_reminder_history_btn) {
                if (event_reminder_email_history != 0)
                    rows += '<button class="btn btn-primary js-status-reminder-history-btn pull-right" style="margin-top: -6px; margin-right: 10px;">History</button>';
            }
            if (event_change_history != 0) {
                rows += '<button class="btn btn-primary js-event-change-history-btn pull-right" style="margin-top: -6px; margin-right: 10px;">Event History</button>';
            }
            if (show_sent_reminder_history_btn || show_request_status_history_btn || event_change_history)
                extra_btns_wrap.html(rows).show(0);
        }

        // Shows event status requests history page
        $(document).on('click', '.js-status-history-btn', function(e) {
            e.preventDefault();
            func_show_loader();
            $('.js-history-close').remove();
            request_page_REF.prepend('<button class="btn btn-default js-history-close" style="margin-bottom: 10px;"><i class="fa fa-arrow-left"></i>&nbsp; back</button>')
            main_page_REF.fadeOut(500);
            reminder_page_REF.fadeOut(0);
            request_page_REF.fadeIn(500);
            request_page_REF.find('tbody').html('tr><td colspan="' + request_page_REF.find('thead > tr > th').length + '"><h4 class="text-center">Please, wait while we are fetching event history...</h4></td>');
            event_history_type = 'requests';
            $('.js-edit-event-head-title').text(event_title_text + ' - Event Status Requests');
            extra_btns_wrap.hide(0);
            current_page = 1;
            $('.js-modal-footer div').hide();
            fetch_event_status_history();
        });

        // Shows event status requests history page
        $(document).on('click', '.js-status-reminder-history-btn', function(e) {
            e.preventDefault();
            func_show_loader();
            $('.js-reminder-history-close').remove();
            reminder_page_REF.prepend('<button class="btn btn-default js-reminder-history-close" style="margin-bottom: 10px;"><i class="fa fa-arrow-left"></i>&nbsp; back</button>')
            request_page_REF.fadeOut(0);
            main_page_REF.fadeOut(200);
            reminder_page_REF.fadeIn(200);
            reminder_page_REF.find('tbody').html('tr><td colspan="' + reminder_page_REF.find('thead > tr > th').length + '"><h4 class="text-center">Please, wait while we are fetching reminder emails history...</h4></td>');
            event_history_type = 'history';
            $('.js-edit-event-head-title').text(event_title_text + ' - Reminder Sent Emails History');
            current_page = 1;
            extra_btns_wrap.hide(0);
            $('.js-modal-footer div').hide();
            fetch_event_status_history();
        });

        // Shows event change history page
        $(document).on('click', '.js-event-change-history-btn', function(e) {
            e.preventDefault();
            func_show_loader();
            $('.js-event-change-close').remove();
            change_history_page_REF.prepend('<button class="btn btn-default js-event-change-close" style="margin-bottom: 10px;"><i class="fa fa-arrow-left"></i>&nbsp; back</button>')
            request_page_REF.fadeOut(0);
            main_page_REF.fadeOut(200);
            change_history_page_REF.fadeIn(200);
            change_history_page_REF.find('tbody').html('tr><td colspan="' + change_history_page_REF.find('thead > tr > th').length + '"><h4 class="text-center">Please, wait while we are fetching event change history...</h4></td>');
            event_history_type = 'change_history';
            $('.js-event-head-title').text(event_title_text + ' - Event Change History');
            current_page = 1;
            extra_btns_wrap.hide(0);
            $('.js-modal-footer div').hide();
            fetch_event_change_history();
        });
        // Shows main modal page
        // on close button click
        $(document).on('click', '.js-history-close', function(e) {
            e.preventDefault();
            func_show_loader();
            $('.js-history-close').remove();
            $('.js-edit-event-head-title').text(event_title_text);
            request_page_REF.fadeOut(300);
            main_page_REF.fadeIn(300);
            $('.js-modal-footer div').show();
            func_hide_loader();
            extra_btns_wrap.show();
        });

        // Shows main modal page
        // on close button click
        $(document).on('click', '.js-reminder-history-close', function(e) {
            e.preventDefault();
            func_show_loader();
            $('.js-edit-event-head-title').text(event_title_text);
            $('.js-history-close').remove();
            reminder_page_REF.fadeOut(200);
            main_page_REF.fadeIn(200);
            $('.js-modal-footer div').show();
            extra_btns_wrap.show();
            func_hide_loader();
        });
        // on close button clickjs-event-change-close
        $(document).on('click', '.js-event-change-close', function(e) {
            e.preventDefault();
            func_show_loader();
            $('.js-event-head-title').text(event_title_text);
            $('.js-history-close').remove();
            change_history_page_REF.fadeOut(200);
            main_page_REF.fadeIn(200);
            $('.js-modal-footer div').show();
            extra_btns_wrap.show();
            func_hide_loader();
        });

        // Get previous page
        $(document).on('click', '.js-pagination-prev', function(event) {
            event.preventDefault();
            func_show_loader();
            current_page--;
            fetch_event_status_history();
        });

        // Get first page
        $(document).on('click', '.js-pagination-first', function(event) {
            event.preventDefault();
            func_show_loader();
            current_page = 1;
            fetch_event_status_history();
        });

        // Get last page
        $(document).on('click', '.js-pagination-last', function(event) {
            event.preventDefault();
            func_show_loader();
            current_page = total_records;
            fetch_event_status_history();
        });

        // Get next page
        $(document).on('click', '.js-pagination-next', function(event) {
            event.preventDefault();
            func_show_loader();
            current_page++;
            fetch_event_status_history();
        });

        // Get page
        $(document).on('click', '.js-pagination-shift', function(event) {
            event.preventDefault();
            func_show_loader();
            current_page = $(this).data('page');
            fetch_event_status_history();
        });

        // Pagination
        // TODO convert it into a plugin
        function load_pagination(limit, total, current_page, record_length, list_size, target_ref) {
            // parsing to int           
            limit = parseInt(limit);
            total = parseInt(total);
            current_page = parseInt(current_page);
            // get paginate array
            var page_array = paginate(total, current_page, limit, list_size);
            // append the target ul
            // to top and bottom of table
            target_ref.html('<ul class="pagination js-pagination pull-left"></ul>');
            // set rows append table
            var target = $('.js-pagination');
            // get total items number
            total_records = page_array.total_pages;
            // load pagination only there
            // are more than one page
            if (total >= limit) {
                // generate li for
                // pagination
                var rows = '';
                // move to one step back
                rows += '<li><a href="javascript:void(0)" class="' + (current_page == 1 ? '' : 'js-pagination-first') + '">First</a></li>';
                rows += '<li><a href="javascript:void(0)" class="' + (current_page == 1 ? '' : 'js-pagination-prev') + '">&laquo;</a></li>';
                // generate 5 li
                $.each(page_array.pages, function(index, val) {
                    rows += '<li ' + (val == current_page ? 'class="active"' : '') + '><a href="javascript:void(0)" data-page="' + (val) + '" class="' + (current_page != val ? 'js-pagination-shift' : '') + '">' + (val) + '</a></li>';
                });
                // move to one step forward
                rows += '<li><a href="javascript:void(0)" class="' + (current_page == page_array.total_pages ? '' : 'js-pagination-next') + '">&raquo;</a></li>';
                rows += '<li><a href="javascript:void(0)" class="' + (current_page == page_array.total_pages ? '' : 'js-pagination-last') + '">Last</a></li>';
                // append to ul
                target.html(rows);
            }
            // remove showing
            $('.js-show-record').remove();
            // append showing of records
            target.before('<span class="pull-left js-show-record" style="margin-top: 27px; padding-right: 10px;">Showing ' + (page_array.start_index + 1) + ' - ' + (page_array.end_index + 1) + ' of ' + (total) + '</span>');
        }
        // Paginate logic
        function paginate(total_items, current_page, page_size, max_pages) {
            // calculate total pages
            var total_pages = Math.ceil(total_items / page_size);

            // ensure current page isn't out of range
            if (current_page < 1) current_page = 1;
            else if (current_page > total_pages) current_page = total_pages;

            var start_page, end_page;
            if (total_pages <= max_pages) {
                // total pages less than max so show all pages
                start_page = 1;
                end_page = total_pages;
            } else {
                // total pages more than max so calculate start and end pages
                var max_pagesBeforecurrent_page = Math.floor(max_pages / 2);
                var max_pagesAftercurrent_page = Math.ceil(max_pages / 2) - 1;
                if (current_page <= max_pagesBeforecurrent_page) {
                    // current page near the start
                    start_page = 1;
                    end_page = max_pages;
                } else if (current_page + max_pagesAftercurrent_page >= total_pages) {
                    // current page near the end
                    start_page = total_pages - max_pages + 1;
                    end_page = total_pages;
                } else {
                    // current page somewhere in the middle
                    start_page = current_page - max_pagesBeforecurrent_page;
                    end_page = current_page + max_pagesAftercurrent_page;
                }
            }

            // calculate start and end item indexes
            var start_index = (current_page - 1) * page_size;
            var end_index = Math.min(start_index + page_size - 1, total_items - 1);

            // create an array of pages to ng-repeat in the pager control
            var pages = Array.from(Array((end_page + 1) - start_page).keys()).map(i => start_page + i);

            // return object with all pager properties required by the view
            return {
                total_items: total_items,
                // current_page: current_page,
                // page_size: page_size,
                total_pages: total_pages,
                start_page: start_page,
                end_page: end_page,
                start_index: start_index,
                end_index: end_index,
                pages: pages
            };
        }

        // Get event status requests
        function fetch_event_status_history() {

            if (event_history_type == 'change_history') {
                fetch_event_change_history();
            } else if (event_history_type == 'history') {
                fetch_sent_reminder_emails_history();
            } else {

                $.get("<?= base_url(); ?>calendar/get-event-availablity-requests/" + $('#js-edit-event-id').val() + "/" + current_page, function(resp) {
                    if (resp.Status === false && resp.Redirect === true) window.location.reload();
                    if (resp.Status === false) {
                        alertify.alert(resp.Response);
                        return false;
                    }
                    // Deafault values
                    var rows = '';
                    // Loop through arary
                    $.each(resp.History, function(i, v) {
                        // rows += '<tr>';
                        // rows += '   <td>'+v.user_name+'</td>';
                        // rows += '   <td>'+v.user_type+'</td>';
                        // rows += '   <td>'+v.event_status+'</td>';
                        // rows += '   <td>'+v.reason+'</td>';
                        // rows += '   <td>'+v.date+'</td>';
                        // rows += '   <td>'+v.start_time+'</td>';
                        // rows += '   <td>'+v.end_time+'</td>';
                        // rows += '</tr>';

                        var nd = moment(v.created_at),
                            reason_prefix = v.event_status.toLowerCase() == 'reschedule' ? 'Reschedule' : (v.event_status.toLowerCase() == 'cannot attend' ? 'Cancellation' : v.event_status),
                            reason_color = '#000000',
                            tr_class = i % 2 === 0 ? 'cs-odd' : '';
                        switch (v.event_status.toLowerCase()) {
                            case 'reschedule':
                                reason_color = '#E67C73';
                                break;
                            case 'confirmed':
                                reason_color = '#5cb85c';
                                break;
                            case 'cannot attend':
                                reason_color = '#d9534f';
                                break;
                        }
                        rows += '<tr class="' + tr_class + '">';
                        rows += '   <td>' + v.user_name + '</td>';
                        rows += '   <td>' + v.user_type + '</td>';
                        rows += '   <td><p style="color: ' + reason_color + '">' + v.event_status + '</p></td>';
                        rows += '   <td>' + (v.date != '-' ? moment(v.date, site_date_format).format('MMM DD YYYY') : v.date) + '</td>';
                        rows += '   <td>' + v.start_time + '</td>';
                        rows += '   <td>' + v.end_time + '</td>';
                        rows += '</tr>';
                        rows += '<tr class="' + tr_class + '">';
                        rows += '   <td colspan="6">';
                        if (v.event_status.toLowerCase() != 'confirmed')
                            rows += '   <strong> ' + (reason_prefix) + ' Reason</strong> <br />' + v.reason + ' <br /><br />';
                        rows += '       <strong>Request Received on:</strong> ' + nd.format('MMM DD YYYY') + ' at ' + nd.format('HH:mm A') + ' <br />';
                        rows += '    </td>';
                        rows += '</tr>';
                    });
                    // Append rows
                    request_page_REF.find('tbody').html(rows);
                    // Set recors count for cache
                    total_records_count = current_page == 1 ? resp.Total : total_records_count;
                    // Load pagination
                    load_pagination(
                        resp.Limit,
                        total_records_count,
                        current_page,
                        resp.History.length,
                        resp.ListSize,
                        request_page_REF.find('.js-pagination-area')
                    );
                    func_hide_loader();
                });
            }
        }
        // Get event change history
        function fetch_event_change_history() {
            $.get("<?= base_url(); ?>calendar/get_event_change_history/" + $('#js-edit-event-id').val() + "/" + current_page, function(resp) {
                if (resp.Status === false && resp.Redirect === true) window.location.reload();
                if (resp.Status === false) {
                    func_hide_loader();
                    alertify.alert(resp.Response);
                    return false;
                }
                // Deafault values
                var rows = '';
                // Loop through array
                $.each(resp.History, function(i, v) {
                    if (v !== undefined) {
                        if (v.details != null) {
                            if (v.details.old_event_status !== undefined && v.details.new_event_status !== undefined) {
                                rows += '<tr>';
                                rows += '   <td> Event Status: ' + v.details.old_event_status.replace(/^./, v.details.old_event_status[0].toUpperCase()) + '</td>';
                                rows += '   <td> Event Status: ' + v.details.new_event_status.replace(/^./, v.details.new_event_status[0].toUpperCase()) + '</td>';
                                rows += '</tr>';
                            }
                            if (v.details.old_users_type !== undefined && v.details.new_users_type !== undefined) {
                                rows += '<tr>';
                                rows += '   <td>Event For: ' + v.details.old_users_type + '</td>';
                                rows += '   <td>Event For: ' + v.details.new_users_type + '</td>';
                                rows += '</tr>';

                                if (v.details.old_applicant_name !== undefined && v.details.new_employee_name !== undefined) {
                                    rows += '<tr>';
                                    rows += '   <td>' + v.details.old_users_type + ' Name: ' + v.details.old_applicant_name + '</td>';
                                    rows += '   <td>' + v.details.new_users_type + ' Name: ' + v.details.new_employee_name + '</td>';
                                    rows += '</tr>';
                                }
                                if (v.details.old_employee_name !== undefined && v.details.new_applicant_name !== undefined) {
                                    rows += '<tr>';
                                    rows += '   <td>' + v.details.old_users_type + ' Name: ' + v.details.old_employee_name + '</td>';
                                    rows += '   <td>' + v.details.new_users_type + ' Name: ' + v.details.new_applicant_name + '</td>';
                                    rows += '</tr>';
                                }
                            } else {
                                if (v.details.old_applicant_name !== undefined && v.details.new_applicant_name !== undefined) {
                                    rows += '<tr>';
                                    rows += '   <td>Applicant Name: ' + v.details.old_applicant_name + '</td>';
                                    rows += '   <td>Applicant Name: ' + v.details.new_applicant_name + '</td>';
                                    rows += '</tr>';
                                }
                                if (v.details.old_employee_name !== undefined && v.details.new_employee_name !== undefined) {
                                    rows += '<tr>';
                                    rows += '   <td>Employee Name: ' + v.details.old_employee_name + '</td>';
                                    rows += '   <td>Employee Name: ' + v.details.new_employee_name + '</td>';
                                    rows += '</tr>';
                                }
                            }
                            if (v.details.old_users_phone !== undefined && v.details.new_users_phone !== undefined) {
                                rows += '<tr>';
                                rows += '   <td>Phone: ' + v.details.old_users_phone + '</td>';
                                rows += '   <td>Phone: ' + v.details.new_users_phone + '</td>';
                                rows += '</tr>';
                            }
                            if (v.details.old_category !== undefined && v.details.new_category !== undefined) {
                                rows += '<tr>';
                                rows += '   <td>Category: ' + v.details.old_category.replace(/^./, v.details.old_category[0].toUpperCase()) + '</td>';
                                rows += '   <td>Category: ' + v.details.new_category.replace(/^./, v.details.new_category[0].toUpperCase()) + '</td>';
                                rows += '</tr>';
                            }
                            if (v.details.old_event_timezone !== undefined && v.details.new_event_timezone !== undefined) {
                                rows += '<tr>';
                                rows += '   <td>Event Timezone: ' + v.details.old_event_timezone + '</td>';
                                rows += '   <td>Event Timezone: ' + v.details.new_event_timezone + '</td>';
                                rows += '</tr>';
                            }
                            if (v.details.old_date !== undefined && v.details.new_date !== undefined) {
                                rows += '<tr>';
                                rows += '   <td>Date: ' + v.details.old_date + '</td>';
                                rows += '   <td>Date: ' + v.details.new_date + '</td>';
                                rows += '</tr>';
                            }
                            if (v.details.old_event_start_time !== undefined && v.details.new_event_start_time !== undefined) {
                                rows += '<tr>';
                                rows += '   <td>Start Time: ' + v.details.old_event_start_time + '</td>';
                                rows += '   <td>Start Time: ' + v.details.new_event_start_time + '</td>';
                                rows += '</tr>';
                            }
                            if (v.details.old_event_end_time !== undefined && v.details.new_event_end_time !== undefined) {
                                rows += '<tr>';
                                rows += '   <td>End Time: ' + v.details.old_event_end_time + '</td>';
                                rows += '   <td>End Time: ' + v.details.new_event_end_time + '</td>';
                                rows += '</tr>';
                            }
                            if (v.details.old_interviewer_names !== undefined && v.details.new_interviewer_names !== undefined) {
                                var user_type1 = 'Participant(s)';
                                var user_type2 = 'Participant(s)';
                                if (v.details.users_type !== undefined && v.details.users_type == 'Applicant') {
                                    user_type1 = 'Interviewer(s)';
                                    user_type2 = 'Interviewer(s)';
                                }
                                if (v.details.old_users_type !== undefined && v.details.old_users_type == 'Applicant') {
                                    user_type1 = 'Interviewer(s)';
                                }
                                if (v.details.new_users_type !== undefined && v.details.new_users_type == 'Applicant') {
                                    user_type2 = 'Interviewer(s)';
                                }

                                rows += '<tr>';
                                rows += '   <td>' + user_type1 + ': ' + v.details.old_interviewer_names.join(", ") + '</td>';
                                rows += '   <td>' + user_type2 + ': ' + v.details.new_interviewer_names.join(", ") + '</td>';
                                rows += '</tr>';
                            }
                        }
                        var nd = moment(v.created_at);
                        rows += '<tr>';
                        rows += '   <td colspan="3"><strong>' + v.user_name + ' changed the event on ' + nd.format('MMM DD YYYY') + ' at ' + nd.format('hh:mm A') + '.</strong></td>';
                        rows += '</tr>';

                    }
                });
                // Append rows
                change_history_page_REF.find('tbody').html(rows);
                // Set recors count for cache
                total_records_count = current_page == 1 ? resp.Total : total_records_count;
                // Load pagination
                load_pagination(
                    resp.Limit,
                    total_records_count,
                    current_page,
                    resp.History.length,
                    resp.ListSize,
                    change_history_page_REF.find('.js-pagination-area')
                );
                func_hide_loader();
            });
        }
        // Get sent reminder email history
        function fetch_sent_reminder_emails_history() {
            $.get("<?= base_url(); ?>calendar/get-reminder-email-history/" + $('#js-edit-event-id').val() + "/" + current_page, function(resp) {
                if (resp.Status === false && resp.Redirect === true) window.location.reload();
                if (resp.Status === false) {
                    alertify.alert(resp.Response);
                    return false;
                }
                // Deafault values
                var rows = '';
                // Loop through array
                $.each(resp.History, function(i, v) {
                    if (v[0] !== undefined) {
                        $.each(v, function(i0, v0) {
                            rows += '<tr>';
                            rows += '   <td>' + v0.user_name + '</td>';
                            rows += '   <td>' + v0.user_email + '</td>';
                            rows += '   <td>' + v0.user_type + '</td>';
                            rows += '</tr>';
                        });
                        var nd = moment(i);
                        rows += '<tr>';
                        rows += '   <td colspan="3"><strong>The reminder ' + (v.length == 1 ? 'email was' : 'email(s) were') + ' sent on ' + nd.format('MMM DD YYYY') + ' at ' + nd.format('HH:mm A') + '.</strong></td>';
                        rows += '</tr>';

                    } else {
                        rows += '<tr>';
                        rows += '   <td>' + v.user_name + '</td>';
                        rows += '   <td>' + v.user_email + '</td>';
                        rows += '   <td>' + v.user_type + '</td>';
                        rows += '</tr>';
                    }
                });
                // reminder_page_REF.show(0);
                // Append rows
                reminder_page_REF.find('tbody').html(rows);
                // Set recors count for cache
                total_records_count = current_page == 1 ? resp.Total : total_records_count;
                // Load pagination
                load_pagination(
                    resp.Limit,
                    total_records_count,
                    current_page,
                    resp.History.length,
                    resp.ListSize,
                    reminder_page_REF.find('.js-pagination-area')
                );
                func_hide_loader();
            });
        }

        // Loads reminder email rows
        // @param a
        // Interviewers
        // @param b
        // Non-employee Interviewers
        // @param c
        // Applicant or Employee
        function generate_reminder_email_rows(a, b, c) {
            var rows = '';
            rows += '<div class="row">';
            if (a.length) {
                rows += '<div class="col-xs-12"><h4>Participant(s)</h4></div>';
                $.each(a, function(i, v) {
                    rows += '<div class="col-xs-6">';
                    rows += '   <label class="control control--checkbox">';
                    rows += v.value;
                    rows += '       <input data-value="' + v.value.replace(/ *\([^)]*\) */g, '') + '" data-id="' + v.id + '" data-timezone="' + v.timezone + '" data-type="interviewer" value="' + v.email_address + '" name="reminder_emails[]" checked="checked" type="checkbox" />';
                    rows += '       <div class="control__indicator"></div>';
                    rows += '   </label>';
                    rows += '</div>';
                });
            }
            if (b.length) {
                rows += '<div class="col-xs-12"><h4>Non Employee Participant(s)</h4></div>';
                $.each(b, function(i, v) {
                    rows += '<div class="col-xs-6">';
                    rows += '   <label class="control control--checkbox">';
                    rows += v.name;
                    rows += '       <input data-value="' + v.name + '" data-id="0" data-type="non-employee interviewer" value="' + v.email + '" data-timezone="" name="reminder_emails[]" checked="checked" type="checkbox" />';
                    rows += '       <div class="control__indicator"></div>';
                    rows += '   </label>';
                    rows += '</div>';
                });
            }

            if (c !== undefined) {
                rows += '<div class="col-xs-12"><h4>' + (c.type == 'applicant' ? 'Applicant' : 'Employee') + '</h4></div>';
                rows += '<div class="col-xs-6">';
                rows += '   <label class="control control--checkbox">';
                rows += c.value;
                rows += '       <input data-value="' + c.value.replace(/ *\([^)]*\) */g, '') + '" data-id="' + c.id + '" data-type="' + c.type + '" value="' + c.email_address + '" data-timezone="' + c.timezone + '" name="reminder_emails[]" class="js-reminder-input" checked="checked" type="checkbox" />';
                rows += '       <div class="control__indicator"></div>';
                rows += '   </label>';
                rows += '</div>';

            }
            rows += '    <div class="col-sm-12">';
            rows += '    <br />';
            rows += '       <button class="btn btn-success pull-right js-edit-reminder-email-btn">Send Reminder Email</button>';
            rows += '    </div>';
            rows += '</div>';

            $('#js-edit-reminder-email-box').html(rows);
        }

        // Send reminder emails
        // to interviewers, non-employee interviewers
        // applicant/employee
        $(document).on('click', '.js-edit-reminder-email-btn', function(e) {
            e.preventDefault();
            var reminder_emails = [];
            $('input[name="reminder_emails[]"]:checked').map(function() {
                var obj = {
                    type: $(this).data('type'),
                    id: $(this).data('id'),
                    value: $(this).data('value'),
                    email_address: $(this).val().trim(),
                    timezone: $(this).data('timezone')
                };
                reminder_emails.push(obj);
            });
            // Check for empty array
            if (reminder_emails.length === 0) {
                alertify.alert("Error! Please, select atleast one email to send reminder.", flush_alertify_cb);
                return false;
            }
            //
            func_show_loader();

            $.post("<?= base_url(); ?>calendar/event-handler", {
                emails: reminder_emails,
                event_id: $('#js-edit-event-id').val(),
                action: 'send_reminder_emails'
            }, function(resp) {
                if (resp.Status === false && resp.Redirect === true) window.location.reload();
                alertify.alert(resp.Response, flush_alertify_cb);
                // Load history button
                if ($('.js-status-reminder-history-btn').length == 0)
                    load_extra_buttons(false, false, 1);
                func_hide_loader();
            });
        });

        //
        function flush_alertify_cb() {
            return;
        }

        // File plugin on message file button
        $('#js-edit-message-file').filestyle({
            text: 'Add Attachment',
            btnClass: 'btn-success',
            placeholder: "No file selected"
        });


        $('#js-edit-delete').click(function() { //delete an event
            alertify.confirm(
                'Are you sure?',
                'Are you sure you want to delete this event?',
                function() {
                    var event_id = $('#js-edit-event-id').val();
                    $('.btn').addClass('disabled');
                    $('.btn').prop('disabled', true);

                    $.ajax({
                        url: "<?= base_url() ?>calendar/deleteEvent?sid=" + event_id,
                        type: 'GET',
                        success: function(msg) {

                            alertify.alert(msg, function() {
                                $('#js-event-edit-modal').modal('hide');
                                $('#remove_li' + event_id + '').remove();
                                $('.btn').removeClass('disabled').prop('disabled', false);
                            });
                        }
                    });
                },
                function() {
                    alertify.error('Cancelled!');
                });
        });

        // Cancel event
        $('#js-edit-cancel').click(function() { //Cancel Event
            alertify.confirm(
                'Are you sure?',
                'Are you sure you want to Cancel this event?',
                function() {
                    func_show_loader();
                    var event_id = $('#js-edit-event-id').val();
                    var my_request;

                    $('.btn').addClass('disabled');
                    $('.btn').prop('disabled', true);

                    my_request = $.ajax({
                        url: '<?php echo base_url('calendar/event-handler'); ?>',
                        type: 'POST',
                        data: {
                            'event_id': event_id,
                            'action': 'cancel_event'
                        }
                    });

                    my_request.done(function(res) {
                        alertify.alert(res.Response, function() {
                            func_hide_loader();
                            $('#js-event-edit-modal').modal('hide');
                            $('.btn').removeClass('disabled').prop('disabled', false);
                        });
                    });
                },
                function() {
                    alertify.error('Cancelled!');
                });
        });

        // Reschedule event
        $(document).on('click', '#js-edit-reschedule', function(e) {
            e.preventDefault();
            update_event('reschedule_event');
        });

        // Reschedule event
        $(document).on('click', '#js-edit-expired-reschedule', function(e) {
            e.preventDefault();
            main_page_REF.hide(200);
            reschedule_page_REF.show(200);
            extra_btns_wrap.hide(0);
            $('.js-modal-footer div').hide();
            $('#js-edit-reschedule-event-start-time').val($('#js-edit-start-time').val());
            $('#js-edit-reschedule-event-end-time').val($('#js-edit-end-time').val());
        });

        $('#js-edit-update').click(function(e) {
            e.preventDefault();
            update_event();
        });

        // Set datepicker for 
        // expired reschedule
        $('#js-edit-reschedule-event-date').datepicker({
            minDate: 0,
            format: 'mm-dd-yyyy',
            onSelect: function(d) {
                $('#js-edit-reschedule-event-date').val(moment(d).format('MM-DD-YYYY'));
            }
        }).val(moment().format('MM-DD-YYYY'));

        // Loads time plugin for start time field
        // for expired reschedule
        $('#js-edit-reschedule-event-start-time').datetimepicker({
            datepicker: false,
            format: 'g:iA',
            formatTime: 'g:iA',
            step: 15,
            onShow: function(ct) {
                this.setOptions({
                    maxTime: $('#js-edit-reschedule-event-end-time').val() ? $('#js-edit-reschedule-event-end-time').val() : false
                });
            }
        });

        // Loads time plugin for end time field
        // for expired reschedule
        $('#js-edit-reschedule-event-end-time').datetimepicker({
            datepicker: false,
            format: 'g:iA',
            formatTime: 'g:iA',
            step: 15,
            onShow: function(ct) {
                time = $('#js-edit-reschedule-event-start-time').val();
                if (time == '') return false;
                timeAr = time.split(":");
                last = parseInt(timeAr[1].substr(0, 2)) + 15;
                if (last == 0)
                    last = "00";
                mm = timeAr[1].substr(2, 2);
                timeFinal = timeAr[0] + ":" + last + mm;
                this.setOptions({
                    minTime: $('#js-edit-reschedule-event-start-time').val() ? timeFinal : false
                })
            }
        });


        function update_event(type) {
            if (!form_check()) return false;
            func_show_loader();
            //
            var eventOBJ = get_event_obj_template();
            eventOBJ.action = type === undefined ? eventOBJ.action : type;

            eventOBJ.applicant_sid = $('#js-edit-applicant-id').val();
            eventOBJ.employee_sid = $('#js-edit-employee-id').val();

            eventOBJ.title = $('#js-edit-event-title').val().trim();
            eventOBJ.users_phone = $('#js-edit-users-phone').val().trim();
            eventOBJ.category = $('#js-edit-event-selected-type-value').val().trim();
            eventOBJ.date = moment($('#js-edit-date').val().trim(), site_date_format + " dddd").format(site_date_format);
            eventOBJ.eventstarttime = $('#js-edit-start-time').val().trim();
            eventOBJ.eventendtime = $('#js-edit-end-time').val().trim();

            eventOBJ.commentCheck = $('#js-edit-comment-check').prop('checked') ? 1 : 0;
            if (eventOBJ.commentCheck === 1)
                eventOBJ.comment = $('#js-edit-comment-msg').val().trim();

            eventOBJ.reminder_flag = $('#js-edit-reminder-check').prop('checked') ? 1 : 0;
            if (eventOBJ.reminder_flag === 1)
                eventOBJ.duration = $('#js-edit-reminder-select').val().trim();

            eventOBJ.messageCheck = $('#js-edit-message-check').prop('checked') ? 1 : 0;
            if (eventOBJ.messageCheck === 1) {
                eventOBJ.message = $('#js-edit-message-body').val().trim();
                eventOBJ.subject = $('#js-edit-message-subject').val().trim();
            }

            eventOBJ.goToMeetingCheck = $('#js-edit-meeting-check').prop('checked') ? 1 : 0;
            if (eventOBJ.goToMeetingCheck === 1) {
                eventOBJ.meetingId = $('#js-edit-meeting-id').val().trim();
                eventOBJ.meetingURL = $('#js-edit-meeting-url').val().trim();
                eventOBJ.meetingCallNumber = $('#js-edit-meeting-call').val().trim();
            }

            // Interviewers
            eventOBJ.interviewer = $('#js-edit-interviewer').val();
            var tmp = [];
            $('#js-edit-interviewers-list').find('input:checked').each(function() {
                tmp.push($(this).val());
            });
            eventOBJ.interviewer_show_email = tmp.join(',');

            // Applicant job list
            tmp = [];
            $('input.js-edit-applicant-job:checked').each(function() {
                tmp.push($(this).val());
            });
            eventOBJ.applicant_jobs_list = tmp.join(',');
            // show_email_sids.join(',');

            var address_type = $('.js-edit-address-type:checked').val();
            var address_saved = $('#js-edit-address-saved').val();
            var address_new = $('#js-edit-address-new').val();

            eventOBJ.address = address_type == 'new' ? address_new.trim() : address_saved;

            // if(user_type == 'employee'){
            //     console.log(eventOBJ);
            //     return;
            // }

            // TODO
            // Recurring event
            var recur_obj = {};
            if ($('#js-edit-reoccur-check').prop('checked') === true && show_recur_btn == 1) {
                recur_obj.recur_type = $('.js-edit-recurr-type:checked').val();
                recur_obj.recur_start_date = moment(eventOBJ.date + ' 23:59:59').format('YYYY-MM-DD');
                recur_obj.recur_end_date = $('.js-edit-infinite').prop('checked') === false ? moment($('.js-edit-recurr-datepicker').val() + ' 23:59:59').format('YYYY-MM-DD') : null;
                recur_obj.list = {};
                switch (recur_obj.recur_type) {
                    case 'Daily':
                        recur_obj.list.Days = get_selected_days();
                        if (recur_obj.list.Days.length === 0) {
                            alertify.alert("Please, select atleast one day", flush_alertify_cb);
                            return false;
                        }
                        break;

                    case 'Weekly':
                        recur_obj.list.Weeks = $('#js-edit-recurr-week-input').val().trim();
                        recur_obj.list.Days = get_selected_days();
                        if (recur_obj.list.Days.length === 0) {
                            alertify.alert("Please, select atleast one day", flush_alertify_cb);
                            return false;
                        }
                        break;

                    case 'Monthly':
                        recur_obj.list.Months = get_selected_months();
                        if (recur_obj.list.Months.length === 0) {
                            alertify.alert("Please, select atleast one month", flush_alertify_cb);
                            return false;
                        }
                        recur_obj.list.Weeks = $('#js-edit-recurr-week-input').val().trim();
                        recur_obj.list.Days = get_selected_days();
                        if (recur_obj.list.Days.length === 0) {
                            alertify.alert("Please, select atleast one day", flush_alertify_cb);
                            return false;
                        }
                        break;

                    case 'Yearly':
                        recur_obj.list.Years = $('#js-edit-recurr-year-input').val().trim();
                        recur_obj.list.Months = get_selected_months();
                        if (recur_obj.list.Months.length === 0) {
                            alertify.alert("Please, select atleast one month", flush_alertify_cb);
                            return false;
                        }
                        recur_obj.list.Weeks = $('#js-edit-recurr-week-input').val().trim();
                        recur_obj.list.Days = get_selected_days();
                        if (recur_obj.list.Days.length === 0) {
                            alertify.alert("Please, select atleast one day", flush_alertify_cb);
                            return false;
                        }
                        break;
                }
            }

            eventOBJ.recur = JSON.stringify(recur_obj);


            if (type == 'expired-reschedule') {
                eventOBJ.action = 'reschedule_event';
                eventOBJ.date = $('#js-edit-reschedule-event-date').val();
                eventOBJ.eventstarttime = $('#js-edit-reschedule-event-start-time').val();
                eventOBJ.eventendtime = $('#js-edit-reschedule-event-end-time').val();
            }

            // Set video 
            eventOBJ.video_ids = $('#js-edit-video-wrap select').val();
            eventOBJ.lcts = $('#js-lcts-id').val();
            //
            if (eventOBJ.interviewer == 'all') {
                tmp = [];
                employee_list.map(function(v) {
                    tmp.push(v.employer_id);
                });
                eventOBJ.interviewer = tmp.join(',');
                eventOBJ.interviewer_show_email = '';
                eventOBJ.interviewer_type = 'all';
            } else eventOBJ.interviewer_type = 'specific';

            eventOBJ.event_timezone = $("#edit_event_timezone option:selected").val();

            var form_data = new FormData();

            var file_data = $('#js-edit-message-file').prop('files')[0];
            form_data.append('messageFile', file_data);
            form_data.append('event_timezone', $("#edit_event_timezone option:selected").val());

            for (myKey in eventOBJ) {
                form_data.append(myKey, eventOBJ[myKey]);
            }

            // Find the difference of
            // prev and new data of
            // current event
            var diff_obj = get_difference_of_event(eventOBJ);
            if (Object.size(diff_obj) == 0) {
                func_hide_loader();
                alertify.confirm('&nbsp;', 'You haven\'t made any changes to the event. Do yo want to close this event modal?', function() {
                    $('#js-event-edit-modal').modal('hide');
                }, function() {
                    return;
                }).set('labels', {
                    ok: 'Yes',
                    cancel: 'No'
                });
            } else {
                form_data.append('diff', JSON.stringify(diff_obj));
                make_ajax_request(type, form_data);
            }
            return;
        }

        // Validate calendar
        function form_check() {
            url_regex.lastIndex = digit_regex.lastIndex = email_regex.lastIndex = phone_regex.lastIndex = 0;
            alertify.defaults.glossary.title = 'Event Management!';
            //
            var users_phone = $('#js-edit-users-phone').val().trim();
            if (users_phone != '' && users_phone != '(___) ___-____') {
                // Check for phone
                if (!phone_regex.test(users_phone)) {
                    alertify.alert("Please, enter a proper phone number or leave it empty.", flush_alertify_cb);
                    return false;
                }
            }


            var ep = $.parseJSON(func_get_external_users_data());
            if (ep.length > 1) {
                var is_error = false;
                $.each(ep, function(i, v) {
                    email_regex.lastIndex = 0;
                    if (v.name.trim() != '' || v.email.trim() != '') {
                        if (v.name.trim() == '') {
                            alertify.alert("Name is missing for non-employee participants.( " + (++i) + " row ) ", flush_alertify_cb);
                            is_error = true;
                            return false;
                        }
                        if (v.email.trim() == '') {
                            alertify.alert("Email is missing for non-employee participants.( " + (++i) + " row ) ", flush_alertify_cb);
                            is_error = true;
                            return false;
                        }
                        if (!email_regex.test(v.email.trim())) {
                            alertify.alert("Invalid email is provided for non-employee participants.( " + (++i) + " row ) ", flush_alertify_cb);
                            is_error = true;
                            return false;
                        }
                    }
                });

                if (is_error) return false;
            } else if (ep.length == 1) {
                if (ep[0].name.trim() != '' || ep[0].email.trim() != '') {
                    if (ep[0].name.trim() == '') {
                        alertify.alert("Name is missing for non-employee participants.( 1 row ) ", flush_alertify_cb);
                        return false;
                    }
                    if (ep[0].email.trim() == '') {
                        alertify.alert("Email is missing for non-employee participants.( 1 row ) ", flush_alertify_cb);
                        return false;
                    }
                    if (!email_regex.test(ep[0].email.trim())) {
                        alertify.alert("Invalid email is provided for non-employee participants.( 1 row ) ", flush_alertify_cb);
                        return false;
                    }
                }
            }

            // Event title
            if ($('#js-edit-event-title').val().trim() == '') {
                alertify.alert('An event title is missing.', flush_alertify_cb);
                return false;
            }

            // Check for comment
            if ($('#js-edit-comment-check').prop('checked') === true && $('#js-edit-comment-msg').val().trim() == '') {
                alertify.alert('A comment is missing. If you don\'t want to add a comment please uncheck "Comment" check.', flush_alertify_cb);
                return false;
            }

            // Check for message
            if ($('#js-edit-message-check').prop('checked') === true) {
                if ($('#js-edit-message-subject').val().trim() == '') {
                    alertify.alert('The Subject field is missing. If you don\'t want to send email to a candidate, please uncheck "Message To Candidate" check.', flush_alertify_cb);
                    return false;
                }
                if ($('#js-edit-message-body').val().trim() == '') {
                    alertify.alert('Message field is missing. If you don\'t want to send email to a candidate, please uncheck "Message To Candidate" check.', flush_alertify_cb);
                    return false;
                }
            }

            // Check for meeting
            if ($('#js-edit-meeting-check').prop('checked') === true) {

                if ($('#js-edit-meeting-call').val().trim() == '') {
                    alertify.alert('The call field is missing. If you don\'t want to set meeting details, please uncheck "Meeting Call Details" check.', flush_alertify_cb);
                    return false;
                }
                if (!digit_regex.test($('#js-edit-meeting-call').val().trim())) {
                    alertify.alert('The call field can only hold numeric values. If you don\'t want to set meeting details, please uncheck "Meeting Call Details" check.', flush_alertify_cb);
                    return false;
                }
                // Reset
                digit_regex.lastIndex = 0;

                if ($('#js-edit-meeting-id').val().trim() == '') {
                    alertify.alert('The ID field is missing. If you don\'t want to set meeting details, please uncheck "Meeting Call Details" check.', flush_alertify_cb);
                    return false;
                }

                if ($('#js-edit-meeting-url').val().trim() == '') {
                    alertify.alert('The URL field is missing. If you don\'t want to set meeting details, please uncheck "Meeting Call Details" check.', flush_alertify_cb);
                    return false;
                }

                if (!url_regex.test($('#js-edit-meeting-url').val().trim())) {
                    alertify.alert('The URL is invalid. If you don\'t want to set meeting details, please uncheck "Meeting Call Details" check.', flush_alertify_cb);
                    return false;
                }
            }


            // set default interviewer to employer
            if ($('#js-edit-interviewer').val() == null || $('#js-edit-interviewer').val() <= 0 || $('#js-edit-interviewer').val() == ' ') {
                $('#js-edit-interviewer').val(<?= $employer_id; ?>);
            }

            return true;
        }

        function get_event_obj_template() {
            return {
                action: 'update_event',
                sid: $('#js-edit-event-id').val(),
                applicant_sid: 0,
                employee_sid: 0,
                title: '',
                category: '',
                date: '',
                eventstarttime: '',
                eventendtime: '',
                interviewer: '',
                address: '',
                users_type: $('#js-edit-event-type').val(),
                users_phone: '',
                interviewer_show_email: '',
                external_participants: func_get_external_users_data(),
                applicant_jobs_list: [],
                reminder_flag: 0,
                duration: 15,
                commentCheck: 0,
                comment: '',
                messageCheck: 0,
                subject: '',
                message: '',
                goToMeetingCheck: 0,
                meetingId: 0,
                meetingCallNumber: 0,
                meetingURL: '',
                recur: ''
            };
        }

        // Get added external partipents
        function func_get_external_users_data() {
            var names = $('.external_participants_name'),
                emails = $('.external_participants_email'),
                show_emails = $('.external_participants_show_email'),
                data = [];

            for (iCount = 0; iCount < names.length; iCount++) {
                var person_data = {
                    'name': $(names[iCount]).val().trim(),
                    'email': $(emails[iCount]).val().trim(),
                    'show_email': $(show_emails[iCount]).prop('checked') === true ? 1 : 0
                };

                data.push(person_data);
            }

            return JSON.stringify(data);
        }

        func_hide_loader();

        function func_hide_loader() {
            $('#my_loader').hide();
        }

        function func_show_loader() {
            $('#my_loader').show();
        }

        // Triggers when cancel button is clicked
        // for expired reschedule
        $(document).on('click', '.js-edit-reschedule-cancel', function(event) {
            event.preventDefault();
            reschedule_page_REF.fadeOut(200);
            main_page_REF.fadeIn(200);
            $('.js-modal-footer div').show();
            extra_btns_wrap.show();
            show_reschedule_box = 1;
        });

        // Triggers when expired reschedule 
        // form submits
        $(document).on('submit', '#js-edit-reschedule-form', function(e) {
            e.preventDefault();
            //
            var date = $('#js-edit-reschedule-event-date').val() + ' 23:59:59',
                start_time = $('#js-edit-reschedule-event-start-time').val(),
                end_time = $('#js-edit-reschedule-event-start-time').val();
            if (moment(date) < moment().utc()) {
                alertify.alert("Please, select a date for the event. Date can't be lower than " + moment().utc().format('MM-DD-YYYY') + "", flush_alertify_cb);
                return false;
            }
            if (start_time == '' || end_time == '') {
                alertify.alert("Please, select a start and end time for reschedule.", flush_alertify_cb);
                return false;
            }
            show_reschedule_box = 0;
            update_event('expired-reschedule');
        })

        $(document).on('change', '#js-edit-interviewer', function() {
            var employee_sid = $(this).val();
            employee_sid = employee_sid == null || employee_sid == undefined || employee_sid == '' || employee_sid == 0 ? 0 : employee_sid;
            var selected = $(this).val();
            $('.js_edit_show_email_col').hide(0);
            if (selected !== null && selected.length > 0 && current_event_type != 'training-session') {
                $('#js-edit-interviewers-list').show();

                for (var i = 0; i < selected.length; i++) {
                    var emp_sid = selected[i];
                    $('#js_edit_show_email_container_0_' + emp_sid).show();
                    $('#js_edit_show_email_0_' + emp_sid).prop('disabled', false);
                }
            } else {
                $('#js-edit-interviewers-list').hide();
                $('#js_edit_show_email_container_0_' + employee_sid).hide();
                $('#js_edit_show_email_container_0_' + employee_sid + ' input[type=checkbox]').prop('disabled', true);
            }
        });

        $('.js_edit_show_email_col').hide();


        // Added on: 09-06-2019
        if (user_type === 'employee') {
            // Added on: 09-06-2019
            // Remove 'all' from interviewers
            // list
            function remove_all_from_interviewers() {
                var vals = $('#js-interviewer').val();
                // var vals = $('#js-interviewer').val();
                // if(vals == null) return;
                if (vals != null) vals.remove('all');
                $('#js-interviewer').find('option[value="all"]').remove();
                $('#js-edit-interviewer').find('option[value="all"]').remove();
                $('#js-interviewer').select2('val', <?= $employer_id; ?>);
                $('#js-edit-interviewer').select2('val', <?= $main_employer_id; ?>);
                $('#js_edit_show_email_container_0_' + <?= $main_employer_id; ?> + '').show(0);
                // $('#js-edit-interviewer').select2('val', <?= $employer_id; ?>);
                // var obj = get_ie_obj(<?= $employer_id; ?>);    
            }
            // Add 'all' in interviewers
            // list
            function append_all_to_interviewers(default_select) {
                $('#js-edit-interviewer').append('<option value="all">All</option>');
                if (default_select === undefined) {
                    $('#js-edit-interviewer').select2('val', '');
                    $('#js-edit-interviewer').select2('val', 'all');
                }
            }

            // Remove 'all' when employee is selected
            // Add 'all' when no employee is selected
            function handle_all_check(selected_interviewers) {
                //
                if (selected_interviewers == null) {
                    $('#js-edit-interviewer').select2('val', 'all');
                    return;
                }
                //
                if (selected_interviewers.length > 1) {
                    selected_interviewers.remove('all');
                    $('#js-edit-interviewer').select2('val', selected_interviewers);
                }
            }

            // Fetch online videos 
            function fetch_online_videos() {
                $.get("<?= base_url('calendar/fetch-online-videos'); ?>", function(resp) {
                    if (resp.Status === false && resp.Redirect === true) window.reload();
                    if (resp.Status === false) return;
                    //
                    $.each(resp.Videos, function(i, v) {
                        $('#js-edit-video-wrap select').append('<option value="' + v.id + '">' + v.name + '</option>');
                    });
                });
            }

            // Get employee index
            function get_ie_obj(employer_id) {
                var return_value = false;
                $.each(employee_list, function(i, v) {
                    if (employer_id == v.employer_id) {
                        return_value = v;
                        return false;
                    }
                });

                return return_value;
            }

            fetch_online_videos();
            $('#js-edit-video-wrap select').select2();

            // Triggers when interviewer is un-selected
            $('#js-edit-interviewer').on('select2:select', function() {
                handle_all_check($('#js-edit-interviewer').val());
            });
            $('#js-edit-interviewer').on('select2:unselect', function() {
                handle_all_check($('#js-edit-interviewer').val());
            });
        }


        // Added on: 18-06-2019

        // Blinks the event tab
        // @param event_blinker
        // @param event_sid
        function event_blinker(interval_type, event_sid) {
            if (interval_type === undefined) {
                blink_interval =
                    setInterval(function() {
                        $('.fc-event-blink').css('opacity', $('.fc-event-blink').css("opacity") == 1 ? '.5' : 1);
                    }, 600);
            } else if (interval_type == 'button') {
                blink_interval_btn =
                    setInterval(function() {
                        $('.js-status-btn-blinker').css('opacity', $('.js-status-btn-blinker').css("opacity") == 1 ? '.5' : 1);
                    }, 600);
            } else if (interval_type == 'clearBtnInterval') {
                clearInterval(blink_interval_btn);
                $('.js-status-history-btn').removeClass('js-status-btn-blinker');
                $('.js-status-history-btn').css('opacity', 1);
            } else if (interval_type == 'clearTabInterval') {
                $('.fc-event-' + event_sid + '').removeClass('fc-event-blink');
                $('.fc-event-' + event_sid + '').css('opacity', 1);
            }
        }

        // Saves the event details locally
        // @param event
        // @param event_sid
        function save_event_locally(event, event_sid) {
            // Save current event details to 
            // local variable
            current_edit_event_details = event;
            current_edit_event_details.event_sid = event_sid;
        }

        // Finds the difference of event
        // changed data
        function get_difference_of_event(new_data) {
            new_data = reset_obj(new_data);
            current_edit_event_details = reset_obj(current_edit_event_details);
            var diff_obj = {};

            // Difference of event category
            if (new_data.category != current_edit_event_details.category_uc.toLowerCase()) {
                diff_obj.old_category = current_edit_event_details.category_uc.toLowerCase();
                diff_obj.new_category = new_data.category;
            }

            // Difference of event date
            if (new_data.date != moment(current_edit_event_details.date, (new_data.users_type == 'personal' ? site_date_format : site2_date_format)).format(site_date_format)) {
                diff_obj.old_date = moment(current_edit_event_details.date, (new_data.users_type == 'personal' ? site_date_format : site2_date_format)).format(site_date_format);
                diff_obj.new_date = new_data.date;
            }

            // Difference of event start time
            if (new_data.eventstarttime != current_edit_event_details.event_start_time) {
                diff_obj.old_event_start_time = current_edit_event_details.event_start_time;
                diff_obj.new_event_start_time = new_data.eventstarttime;
            }

            // Difference of event end time
            if (new_data.eventendtime != current_edit_event_details.event_end_time) {
                diff_obj.old_event_end_time = current_edit_event_details.event_end_time;
                diff_obj.new_event_end_time = new_data.eventendtime;
            }
            // Difference of event end time
            console.log(new_data.event_timezone);
            console.log(current_edit_event_details.event_timezone);
            if (new_data.event_timezone != current_edit_event_details.event_timezone) {
                diff_obj.old_event_timezone = current_edit_event_details.event_timezone;
                diff_obj.new_event_timezone = new_data.event_timezone;
            }

            // Difference of message subject
            if (new_data.subject != current_edit_event_details.subject) {
                diff_obj.old_subject = current_edit_event_details.subject;
                diff_obj.new_subject = new_data.subject;
            }

            // Difference of message body
            if (new_data.message != current_edit_event_details.message) {
                diff_obj.old_message = current_edit_event_details.message;
                diff_obj.new_message = new_data.message;
            }

            // Difference of message check
            if (new_data.messageCheck != current_edit_event_details.message_check) {
                diff_obj.old_message_check = current_edit_event_details.message_check;
                diff_obj.new_message_check = new_data.messageCheck;
            }

            // Difference of message file
            if (new_data.message_file != current_edit_event_details.message_file && new_data.hasOwnProperty('message_file') === true) {
                diff_obj.old_message_file = current_edit_event_details.message_file;
                diff_obj.new_message_file = new_data.message_file;
            }

            // Difference of meeting url
            if (new_data.meetingURL != current_edit_event_details.meeting_url) {
                diff_obj.old_meeting_url = current_edit_event_details.meeting_url;
                diff_obj.new_meeting_url = new_data.meetingURL;
            }

            // Difference of meeting id
            if (new_data.meetingId != current_edit_event_details.meeting_id) {
                diff_obj.old_meeting_id = current_edit_event_details.meeting_id;
                diff_obj.new_meeting_id = new_data.meetingId;
            }

            // Difference of meeting call number
            if (new_data.meetingCallNumber != current_edit_event_details.meeting_call_number) {
                diff_obj.old_meeting_call_number = current_edit_event_details.meeting_call_number;
                diff_obj.new_meeting_call_number = new_data.meetingCallNumber;
            }

            // Difference of meeting check
            if (new_data.goToMeetingCheck != current_edit_event_details.meeting_check) {
                diff_obj.old_meeting_check = current_edit_event_details.meeting_check;
                diff_obj.new_meeting_check = new_data.goToMeetingCheck;
            }

            // Difference of event title
            if (new_data.title != current_edit_event_details.event_title) {
                diff_obj.old_event_title = current_edit_event_details.event_title;
                diff_obj.new_event_title = new_data.title;
            }

            // Difference of users phone
            if (new_data.users_phone != current_edit_event_details.users_phone) {
                diff_obj.old_users_phone = current_edit_event_details.users_phone;
                diff_obj.new_users_phone = new_data.users_phone;
            }

            // Difference of recir check
            if (new_data.recur != current_edit_event_details.is_recur) {
                diff_obj.old_is_recur = current_edit_event_details.is_recur;
                diff_obj.new_is_recur = new_data.recur;
            }

            // Difference of address
            if (new_data.address != current_edit_event_details.address) {
                diff_obj.old_address = current_edit_event_details.address;
                diff_obj.new_address = new_data.address;
            }

            // Difference of comment body
            if (new_data.comment != current_edit_event_details.comment) {
                diff_obj.old_comment = current_edit_event_details.comment;
                diff_obj.new_comment = new_data.comment;
            }

            // Difference of comment check
            if (new_data.commentCheck != current_edit_event_details.comment_check) {
                diff_obj.old_comment_check = current_edit_event_details.comment_check;
                diff_obj.new_comment_check = new_data.commentCheck;
            }

            // if(user_type === 'employee'){
            if (new_data.interviewer != '' && new_data.interviewer !== undefined) {
                // Difference of interviewers show email
                if (new_data.interviewer_show_email != current_edit_event_details.interviewer_show_email) {
                    diff_obj.old_interviewer_show_email = current_edit_event_details.interviewer_show_email;
                    diff_obj.new_interviewer_show_email = new_data.interviewer_show_email;
                }

                var removed_interviewers = _.difference(current_edit_event_details.interviewer.split(','), new_data.interviewer),
                    added_interviewers = _.difference(new_data.interviewer, current_edit_event_details.interviewer.split(','));
                // Difference of interviewers|participants|attendees
                if (removed_interviewers.length != 0 ||
                    added_interviewers.length != 0) {
                    diff_obj.old_interviewers = current_edit_event_details.interviewer;
                    diff_obj.new_interviewers = new_data.interviewer.join(',');
                    diff_obj.removed_interviewers = removed_interviewers;
                    diff_obj.added_interviewers = added_interviewers;
                }
            }

            // External participants
            if (new_data.external_participants != '') {
                new_data.external_participants = clean_external_participants(JSON.parse(new_data.external_participants));
                var added_exinterviewers =
                    _.differenceBy(
                        new_data.external_participants,
                        current_edit_event_details.external_participants,
                        'email'
                    ),
                    removed_exinterviewers =
                    _.differenceBy(
                        current_edit_event_details.external_participants,
                        new_data.external_participants,
                        'email'
                    );

                if (added_exinterviewers.length != 0 || removed_exinterviewers.length != 0) {
                    diff_obj.old_external_interviewers = current_edit_event_details.external_participants;
                    diff_obj.new_external_interviewers = new_data.external_participants;
                    diff_obj.removed_external_interviewers = removed_exinterviewers;
                    diff_obj.added_external_interviewers = added_exinterviewers;
                }
            }
            // }

            // Difference of Applicant job sid
            if (new_data.applicant_jobs_list != current_edit_event_details.applicant_jobs_list) {
                var added_jobs = _.difference(
                        new_data.applicant_jobs_list.split(','),
                        current_edit_event_details.applicant_jobs_list.split(',')
                    ),
                    removed_jobs = _.difference(
                        current_edit_event_details.applicant_jobs_list.split(','),
                        new_data.applicant_jobs_list.split(',')
                    );
                diff_obj.old_applicant_jobs_list = current_edit_event_details.applicant_jobs_list;
                diff_obj.new_applicant_jobs_list = new_data.applicant_jobs_list;
                diff_obj.added_jobs = added_jobs;
                diff_obj.removed_jobs = removed_jobs;
            }

            // Difference of Applicant id
            if (new_data.applicant_sid != current_edit_event_details.applicant_job_sid) {
                diff_obj.old_applicant_job_sid = current_edit_event_details.applicant_job_sid;
                diff_obj.new_applicant_job_sid = new_data.applicant_sid;
            }

            // Difference of Message file
            if ($('#js-message-file').prop('files').length != 0 && $('#js-message-file').prop('files')[0]['name'] != current_edit_event_details.message_file) {
                diff_obj.new_message_file = $('#js-message-file').prop('files')[0]['name'];
                diff_obj.old_message_file = current_edit_event_details.message_file;
            }

            // Difference of users name
            // if(new_data.users_email != current_edit_event_details.users_email){
            //     diff_obj.old_users_email = current_edit_event_details.users_email;
            //     diff_obj.new_users_email = new_data.users_email;
            // }

            // Difference of users name
            // if(new_data.users_name != current_edit_event_details.users_name){
            //     diff_obj.old_users_name = current_edit_event_details.users_name;
            //     diff_obj.new_users_name = new_data.users_name;
            // }     
            return diff_obj;
        }

        // Convert null, undefined, 0 to ''
        function reset_obj(obj) {
            for (var k0 in obj) {
                var v0 = obj[k0];
                if (k0 == 'external_participants' && v0 != '') {
                    var tmp = v0[0]['name'] !== undefined ? v0 : JSON.parse(v0);
                    if (tmp[0].name == '' || tmp[0].email == '') obj[k0] = '';
                }
                if (v0 == null || v0 == 'null' || v0 == undefined || v0 == '' || v0 == '{}' || v0 == '0') obj[k0] = '';
            }
            return obj;
        }

        // Make AJAX request to 
        // save/update/reschedule event
        function make_ajax_request(type, form_data) {
            $.ajax({
                url: "<?php echo base_url('calendar/event-handler'); ?>",
                type: 'POST',
                data: form_data,
                processData: false,
                contentType: false,
                success: function(res) {
                    //
                    alertify.alert(res.Response, function() {
                        if (res.Status !== false && (type === undefined || type == 'expired-reschedule'))
                            window.location.reload();
                        $('#js-event-edit-modal').modal('hide');
                        $('.btn').removeClass('disabled').prop('disabled', false);
                        func_hide_loader();
                    });
                }
            });
        }

        // Gets the size of an Object
        Object.size = function(o) {
            var s = 0,
                k;
            for (k in o) {
                if (o.hasOwnProperty(k)) s++;
            }
            return s;
        }

        // Flush empty rows
        function clean_external_participants(external_participants) {
            var k0, nex = [];
            for (k0 in external_participants) {
                if (external_participants[k0]['name'] != '' && external_participants[k0]['email'] != '') nex.push(external_participants[k0]);
            }
            return nex;
        }


        //
        // $('#js-edit-users-phone').keyup(function(event) {
        //     var val = fpn($(this).val());
        //     if(typeof(val) === 'object'){
        //         $(this).val(val.number);
        //         setCaretPosition($(this), val);
        //     } else $(this).val(val);
        // });

    })
</script>
<style>
    #my_loader {
        z-index: 9999 !important;
    }
</style>