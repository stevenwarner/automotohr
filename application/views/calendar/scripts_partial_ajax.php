<link href="<?= base_url() ?>assets/calendar/fullcalendar.css" rel="stylesheet" />
<link href="<?= base_url() ?>assets/calendar/fullcalendar.print.css" rel="stylesheet" media="print" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.2/moment.min.js"></script>
<script src="<?= base_url() ?>assets/calendar/fullcalendar.min.js"></script>
<script src="https://cdn.polyfill.io/v2/polyfill.min.js"></script>


<!-- lodash -->
<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.11/lodash.min.js"></script>
<?php
$data['company_sid'] = $company_id;
$data['employerData']['sid'] = $employer_id;
$data['employerData'] = $employee;
$data['companyData'] = $session['company_detail'];
?>
<link rel="StyleSheet" type="text/css" href="<?= base_url() ?>assets/css/pagination.min.css" />
<script src="<?= base_url() ?>assets/js/pagination.min.js"></script>
<style>
    /* fix of NAV icons for IE*
    .fc-icon::after{ margin: 0 0 !important; }
    ul.ui-front{ z-index: 9999 !important; }
    /* To set loader precedence */
    /* higher than modal*/
    #my_loader {
        z-index: 9999;
    }

    /* Trianing session tab bg*/
    .btn-event-training-session {
        color: #ffffff;
        background-color: #337ab7;
    }

    .ui-autocomplete {
        z-index: 1234;
    }

    /*Event tab border*/
    .fc-event-cc {
        border-width: 20px;
        /*border-left: 0 !important;*/
        border-bottom: 0 !important;
        border-top: 0 !important;
        border-right: 0 !important;
        padding: 5px;
        margin-bottom: 1px;
    }

    .fc-event-hld {
        border-width: 0;
        padding-left: 27px;
    }

    /*Added on: 06-05-2019*/
    .fc-more-popover .fc-event-container {
        max-height: 303px;
        overflow: auto;
    }

    .fc-ltr .fc-popover .fc-header .fc-title {
        color: #000000;
    }

    .modal {
        z-index: 1062;
    }

    .js-category-list {
        z-index: 1000;
    }

    .js-timeoff-edit-request {
        cursor: pointer;
    }

    .popover {
        width: 100%;
    }

    .popover-title {
        background-color: #81b431;
        color: #ffffff;
    }
</style>

<?php
//
$calendar_opt = $this->config->item('calendar_opt');

// Check for blue panel
if (!check_blue_panel_status()) {
    unset(
        $calendar_opt['event_type_info']['employee'][1],
        $calendar_opt['event_type_info']['personal'][1]
    );
}

$event_color_array = get_calendar_event_color();
$event_obj = $calendar_opt['event_types'];
$event_status_array = $calendar_opt['event_status'];
$recur_months = array(1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December');
// Add borderes for event status
echo '<style>';
foreach ($event_color_array as $k0 => $v0) {
    echo '.fc-event-cc-' . $k0 . '{';
    echo '  border-color: ' . $v0 . ' !important;';
    echo '}';
    echo '';
    if (!in_array($v0, $event_status_array)) {
        echo '.cs-event-btn-' . $k0 . ' {';
        echo ' background-color: ' . $v0 . ' !important;';
        echo ' color: #ffffff !important;';
        echo '}';
    }
}
for ($i = 1; $i <= 28; $i++) {
    echo '.fc-event-hld-' . $i . '{';
    echo '  background: url("' . base_url('assets/images/holidays') . '/' . ($i) . '.png") no-repeat;';
    echo '}';
    echo '';
}
echo '</style>';
?>

<script>
    var user_zone = <?= @json_encode(get_current_timezone()); ?>,
        // incoming event sid
        i_event_array = <?= @json_encode($show_event); ?>,
        i_triggered = i_event_array.length != 0 ? true : false,
        // Select ID for employer
        selected_employer_id = null,
        //
        site_date_format = 'MM-DD-YYYY',
        site2_date_format = 'YYYY-MM-DD',
        // Set recur days
        recurr_days = <?= @json_encode($calendar_opt['recur_days']); ?>,
        event_status_obj = <?= @json_encode($event_status_array); ?>,
        // Set color object
        // for categories
        event_obj = <?= @json_encode($event_obj); ?>,
        event_color_obj = <?= @json_encode($event_color_array); ?>,
        event_type = <?= @json_encode($calendar_opt['event_type_info']); ?>,
        // Set months
        recurr_months = <?= json_encode($recur_months); ?>,
        //
        default_recur = <?= $calendar_opt['recur_active']; ?>,
        // Set recurr defaults
        default_days = {
            1: true,
            2: true,
            3: true,
            4: true,
            5: true
        },
        default_weeks = 2,
        default_end_date = moment().utc().add('+20', 'weeks').format(site_date_format),
        default_months = {
            is_all: true
        },
        blink_interval = null,
        blink_interval_btn = null;
    // jQuery IFFY
    // updated on: 03-04-2019
    function timeoffview(_this, event) {
        //
        if (event.type != 'timeoff') return;
        //
        var body_title = "<strong>" + event.employee_name + "time-off</strong>";
        var body_content = '';
        var img_path = event.img == '' || event.img == null ? 'https://www.automotohr.com/assets/images/img-applicant.jpg' : "<?= AWS_S3_BUCKET_URL; ?>" + event.img;
        var content_date = event.from_date == event.to_date ? moment(event.from_date, 'YYYY-MM-DD').format('MMM DD YYYY, ddd') : (moment(event.from_date, 'YYYY-MM-DD').format('MMM DD YYYY, ddd') + ' - ' + moment(event.to_date, 'YYYY-MM-DD').format('MMM DD YYYY, ddd'));
        body_content += '<div class="row">';
        body_content += '   <div class="col-sm-4">';
        body_content += '       <img src="' + img_path + '" style="max-width: 100%;" />';
        body_content += '   </div>';
        body_content += '   <div class="col-sm-8">';
        body_content += '       <p>' + event.employee_name + '</p>';
        body_content += '   </div>';
        body_content += '</div>';
        body_content += '<hr />';
        body_content += '<div class="row">';
        body_content += '   <div class="col-sm-12">';
        body_content += '       <p><strong>Date:</strong>' + content_date + '</p>';
        body_content += '       <p><strong>Time:</strong>' + event.timeoff_breakdown.text + '</p>';
        if (event.approver != 2) {
            body_content += '       <p><strong>Policy:</strong>' + event.policy + '</p>';
            body_content += '       <p><strong>Status:</strong>' + event.timeoff_status + '</p>';
            if (event.reason != '' && event.reason != null) {
                body_content += '       <p><strong>Reason:</strong>' + event.reason + '</p>';
            }
        }
        body_content += '   </div>';
        body_content += '</div>';
        //
        $(_this).popover({
            title: body_title,
            placement: 'top',
            trigger: 'hover',
            html: true,
            content: body_content,
            container: 'body'
        }).popover('show');
        //    
        // $(_this).popover({
        //     title: `<strong>${event.employee_name} time-off</strong>`,
        //     placement:'top',
        //     trigger : 'hover',
        //     html: true,
        //     content: `
        //         <div class="row">
        //             <div class="col-sm-4">
        //                 <img src="${event.img == '' || event.img == null ? 'https://www.automotohr.com/assets/images/img-applicant.jpg' : `<?= AWS_S3_BUCKET_URL; ?>${event.img}`}" style="max-width: 100%;" />
        //             </div>
        //             <div class="col-sm-8">
        //                 <p>${event.employee_name}</p>
        //                 <!-- <p title="Employee number">${event.employee_number}</p> -->
        //             </div>
        //         </div>
        //         <hr />
        //         <div class="row">
        //             <div class="col-sm-12">
        //             <p><strong>Date:</strong> ${event.from_date == event.to_date ? moment(event.from_date, 'YYYY-MM-DD').format('MMM DD YYYY, ddd') : (moment(event.from_date, 'YYY-MM-DD').format('MMM DD YYYY, ddd')+' - '+moment(event.to_date, 'YYYY-MM-DD').format('MMM DD YYYY, ddd'))}</p>
        //             <p><strong>Time:</strong> ${event.timeoff_breakdown.text}</p>
        //             ${
        //                 event.approver == 2 ? '' :
        //                 `
        //                     <p><strong>Policy:</strong> ${event.policy}</p>
        //                     <p><strong>Status:</strong> ${event.timeoff_status}</p>
        //                     ${
        //                     event.reason != '' && event.reason != null ? 
        //                     `<p><strong>Reason:</strong> ${event.reason}</p>` :
        //                     ''
        //                 }
        //                 `
        //             }

        //             </div>
        //         </div>
        //     `,
        //     container:'body'
        // }).popover('show');
    }
    // jQuery IFFY
    // updated on: 03-04-2019
    function goalsView(_this, event) {
        //
        if (event.type != 'goals') return;
        //
        $(_this).popover({
            title: `<strong>${event.title}</strong>`,
            placement: 'top',
            trigger: 'hover',
            html: true,
            content: `
                <div class="row">
                    <div class="col-sm-4">
                        <img src="${event.profile_picture == '' || event.profile_picture == null ? 'https://www.automotohr.com/assets/images/img-applicant.jpg' : `<?= AWS_S3_BUCKET_URL; ?>${event.profile_picture}`}" style="max-width: 100%;" />
                    </div>
                    <div class="col-sm-8">
                        <p>${event.first_name} ${event.last_name}</p>
                    </div>
                </div>
                <hr />
                <div class="row">
                    <div class="col-sm-12">
                    <p><strong>Start Date:</strong> ${event.start.format('MMM DD YYYY, ddd')}</p>
                    <p><strong>End Date:</strong> ${event.end.format('MMM DD YYYY, ddd')}</p>
                    <p><strong>Target:</strong> ${event.target}</p>
                    <p><strong>Completed Target:</strong> ${event.completed_target}</p>
                </div>
            `,
            container: 'body'
        }).popover('show');
    }


    $(function() {

        var calendar_ref = $('#calendar');
        $('.js-search-loader-applicant').hide(0);
        // Variable declerations
        // Set default calendar
        // event obj
        var calendar_OBJ = {},
            // Holds edit event details
            current_edit_event_details = {},
            drag_event = {},
            // TODO 
            // To be removed after 
            // checking
            obj = {},
            //
            last_selected_category = null,
            // Set default start and end
            // time for event modal
            default_start_time = "<?= $calendar_opt['default_start_time']; ?>",
            default_end_time = "<?= $calendar_opt['default_end_time']; ?>",
            // Holds the searhced values
            applicant_list_array = [],
            employee_list_array = [],
            interviewers_list_array = [],
            interviewers_list_name_array = [],
            address_list_array = [],
            // Employee list
            employee_list = <?= json_encode($employees); ?>,
            // List of selected interviewers
            selected_interviewers = [],
            // Set recur page reference
            recur_page_REF = $('.js-recur-page'),
            // Set request page reference
            request_page_REF = $('.js-request-page'),
            // Set reminder page reference
            reminder_page_REF = $('.js-reminder-email-history-page'),
            change_history_page_REF = $('.js-change-history-page'),
            // Set reschedule page reference
            reschedule_page_REF = $('.js-reschedule-page'),
            // Set main page reference
            main_page_REF = $('.js-main-page'),
            // Set clone and history buttons reference
            extra_btns_wrap = $('.js-extra-btns'),
            // Set type for event history
            // 'history'; Fetch sent reminder emails history
            // 'request'; Fetch event status request history via buttons
            // in emails 
            event_history_type = null,
            //
            show_clone_btn = false,
            show_recur_btn = <?= $calendar_opt['show_recur_btn']; ?>,
            show_sent_reminder_history_btn = true,
            show_request_status_history_btn = true,
            // Show reschedule popup
            show_reschedule_box = 1,
            //
            event_title_text = 'Event Management',
            // Set current page for history
            // pagination
            current_page = 1,
            total_records_count = 0,
            // Set user type in
            // update mode
            selected_user_type = null,
            // Set user selected category in
            // update mode
            selected_user_category = null,
            // Set category type in
            // update mode
            selected_personal_category = null,
            // Set default comment section
            // text
            default_comment_text = 'Comment for Interviewer(s)',
            // Set default comment section
            // text for 'Personal' type
            personal_comment_text = 'Personal Comment',
            // Set interviewers default text
            default_interviewer_text = 'Interviewers(s)',
            // Set interviewers default text
            default_einterviewer_text = 'Non-employee Interviewers(s)',
            // Phone validation regex
            // @accepts
            // Number, Hyphens, Underscrores, Brackets
            phone_regex = new RegExp(/[0-9]/),
            // phone_regex = new RegExp(/(\(\d{3}\))\s(\d{3})-(\d{4})$/),
            // phone_regex = new RegExp(/^[+]?[\s./0-9]*[(]?[0-9]{1,4}[)]?[_-\s./0-9]*$/g),
            email_regex = new RegExp(/[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/),
            digit_regex = new RegExp(/^[0-9]+$/g),
            url_regex = new RegExp(/(https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]+\.[^\s]{2,}|www\.[a-zA-Z0-9]+\.[^\s]{2,})/),
            //
            // Holds the selected employee
            // value to match with 
            // selected interviewer
            tmp = [],
            // Set default ajax calls
            // value
            ac = aa = ae = aj = null,
            applicantXHR = null,
            employeeXHR = null,
            interviewerXHR = null,
            addressXHR = null,
            eventsXHR = null,
            // Set default time gap
            // for end time in event
            // modal
            add_time = 30,
            add_time_type = 'minutes',
            default_loader_text = "Please wait while we generate a preview...";
        // Set default values
        calendar_OBJ.current = {};
        calendar_OBJ.employee_id = "<?= $employer_id; ?>";
        calendar_OBJ.records = [];
        selected_interviewers.push("<?= $employer_id; ?>");

        // Load notes to DOM
        load_notes();
        fetch_online_videos();

        $('.js-reminder-email-history-page').hide(0);
        $('.js-change-history-page').hide(0);

        // Set datepicker for 
        // expired reschedule
        $('#js-reschedule-event-date').datepicker({
            minDate: 0,
            format: site_date_format,
            onSelect: function(d) {
                $('#js-reschedule-event-date').val(moment(d, site_date_format).format(site_date_format));
                // $('#js-reschedule-event-date').datepicker('setDate', moment(d, site_date_format).format(site_date_format));
            }
        }).val(moment().format(site_date_format));

        // Loads time plugin for start time field
        // for expired reschedule
        $('#js-reschedule-event-start-time').datetimepicker({
            datepicker: false,
            format: 'g:iA',
            formatTime: 'g:iA',
            step: <?= $calendar_opt['time_duration']; ?>,
            onShow: function(ct) {
                this.setOptions({
                    maxTime: $('#js-reschedule-event-end-time').val() ? $('#js-reschedule-event-end-time').val() : false
                });
            }
        });

        // Loads time plugin for end time field
        // for expired reschedule
        $('#js-reschedule-event-end-time').datetimepicker({
            datepicker: false,
            format: 'g:iA',
            formatTime: 'g:iA',
            step: <?= $calendar_opt['time_duration']; ?>,
            onShow: function(ct) {
                time = $('#js-reschedule-event-start-time').val();
                if (time == '') return false;
                timeAr = time.split(":");
                last = parseInt(timeAr[1].substr(0, 2)) + 15;
                if (last == 0)
                    last = "00";
                mm = timeAr[1].substr(2, 2);
                timeFinal = timeAr[0] + ":" + last + mm;
                this.setOptions({
                    minTime: $('#js-reschedule-event-start-time').val() ? timeFinal : false
                })
            }
        });

        // Triggers when cancel button is clicked
        // for expired reschedule
        $(document).on('click', '.js-reschedule-cancel', function(event) {
            event.preventDefault();
            reschedule_page_REF.fadeOut(200);
            main_page_REF.fadeIn(200);
            show_reschedule_box = 1;
        });

        // Triggers when expired reschedule 
        // form submits
        $(document).on('submit', '#js-reschedule-form', function(e) {
            e.preventDefault();
            //
            var date = $('#js-reschedule-event-date').val() + ' 23:59:59',
                start_time = $('#js-reschedule-event-start-time').val(),
                end_time = $('#js-reschedule-event-start-time').val();
            if (moment(date) < moment().utc()) {
                alertify.alert("Please, select a date for the event. Date can't be lower than " + moment().utc().format(site_date_format) + "", flush_alertify_cb);
                return false;
            }
            if (start_time == '' || end_time == '') {
                alertify.alert("Please, select a start and end time for reschedule.", flush_alertify_cb);
                return false;
            }
            show_reschedule_box = 0;
            save_update_event('expired-reschedule', event_save_info);
        })

        // Set category on click 
        $(document).on('click', '.js-btn-category', function() {
            //
            if ($('#js-user-type-personal').prop('checked') === true) {
                $('.js-selected-event-category-p').val($(this).data('id'));
                $('.js-active-category-p').text(event_obj[$(this).data('id')]);
                $('.js-category-dropdown-p').css({
                    'background-color': event_color_obj[$(this).data('id')],
                    'color': '#ffffff'
                });
                load_personal_view();
                return false;
            }
            remove_all_from_interviewers();
            // For training session
            if ($(this).data('id') == 'training-session') {
                $('#js-employee-box, .js-employee-hr').hide(0);
                $('#js-interviewers-box').hide(0);
                $('label#attendees_label').text('Assigned Attendees');
                $('#js-non-employee-heading').text('Assigned non-employee Attendees');
                $('.js-comment-box, .js-message-wrap, #js-message-box, .js-meeting-wrap, #js-meeting-box').hide(0);
                $('.js-message-hr').hide(0);
                $('.js-comment-hr').hide(0);
                <?php if ($calendar_opt['show_online_videos'] == 1) { ?>
                    $('#js-video-wrap, .js-video-hr').show(0);
                <?php } ?>

                append_all_to_interviewers();

            } else if ($('#js-user-type-employee').prop('checked') === true) {
                $('#js-employee-box, .js-employee-hr').show(0);
                $('#js-interviewers-box').show(0);
                // $('label#attendees_label').text(default_interviewer_text);
                // $('#js-non-employee-heading').text(default_einterviewer_text);
                $('label#attendees_label').text('Participant(s)');
                $('#js-non-employee-heading').text('Non-employee Participant(s)');
                $('.js-comment-box, .js-message-wrap, #js-message-box, .js-meeting-wrap, #js-meeting-box').show(0);
                $('.js-message-hr').show(0);
                $('.js-comment-hr').show(0);

                if ($('#js-message-check').prop('checked') === false)
                    $('#js-message-box').hide(0);
                if ($('#js-meeting-check').prop('checked') === false)
                    $('#js-meeting-box').hide(0);
                $('#js-video-wrap, .js-video-hr').hide(0);
            }
            //
            $('.js-selected-event-category').val($(this).data('id'));
            $('.js-active-category').text(event_obj[$(this).data('id')]);
            $('.js-category-dropdown').css({
                'background-color': event_color_obj[$(this).data('id')],
                'color': '#ffffff'
            });
        });

        // Set category on click 
        $(document).on('click', '.js-btn-category-p', function() {
            //
            remove_all_from_interviewers();
            $('.js-selected-event-category-p').val($(this).data('id'));
            $('.js-active-category-p').text(event_obj[$(this).data('id')]);
            $('.js-category-dropdown-p').css({
                'background-color': event_color_obj[$(this).data('id')],
                'color': '#ffffff'
            });
            //
            if ($('#js-user-type-personal').prop('checked') === true) load_personal_view(true);
        });

        // Send reminder emails
        // to interviewers, non-employee interviewers
        // applicant/employee
        $(document).on('click', '.js-reminder-email-btn', function(e) {
            e.preventDefault();
            var reminder_emails = [];
            $('input[name="reminder_emails[]"]:checked').map(function() {
                var obj = {
                    type: $(this).data('type'),
                    id: $(this).data('id'),
                    value: $(this).data('value'),
                    email_address: $(this).val().trim(),
                    timezone: $(this).data('timezone').trim(),
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
                event_id: $('#js-event-id').val(),
                action: 'send_reminder_emails'
            }, function(resp) {
                if (resp.Status === false && resp.Redirect === true) window.location.reload();
                alertify.alert(resp.Response, flush_alertify_cb);
                // Load history button
                if ($('.js-status-reminder-history-btn').length == 0)
                    load_extra_buttons(false, false, 1, 0);
                func_hide_loader();
            });
        });

        // Bind select2 event
        // with employee and interviewer
        // selects
        $('#js-employee-select, #js-interviewers-select').select2();

        // Triggers when an employee is selected
        $('#js-employee-select').on('select2:select', function(e) {
            // Reset employee on null
            if ($(this).val() == 'null') {
                $('#js-employee-profile-link').addClass('disabled');
                $('#js-employee-phone').val('');
                selected_employer_id = null;
                return false;
            }

            var employee_phone = get_ie_obj($(this).val())['phone_number'];
            // employee_phone = employee_phone == null ? fpn('') : fpn(employee_phone);
            // var cursor;
            // if(typeof(employee_phone) === 'object'){
            //     cursor = employee_phone.cur;
            //     employee_phone = employee_phone.number;
            // }
            // Fill phone number
            $('#js-employee-phone').val(employee_phone);
            // if(cursor !== undefined) setCaretPosition($('#js-employee-phone'), cursor);

            // Remove the disabled check
            // for last employee
            if (selected_employer_id != null) {
                $('#js-interviewers-select').find('option[value="' + selected_employer_id + '"]').prop('disabled', false);
            }
            // Overwrite current selected employer
            selected_employer_id = $(this).val();
            // Delete interviewer matches the employee
            var in_vals = $('#js-interviewers-select').val();
            if (in_vals != null) {
                in_vals.remove(selected_employer_id);
            }
            $('#js-interviewers-select').select2('val', in_vals);
            $('#js-interviewers-select').find('option[value="' + selected_employer_id + '"]').prop('disabled', true);
            $('#js-interviewers-select').select2();
            $('#js-employee-profile-link').removeClass('disabled');
            //
            selected_interviewers = $('#js-interviewers-select').val();
            unselect_interviewer({
                id: selected_employer_id
            });
        });

        // Triggers when interviewers are selected
        $('#js-interviewers-select').on('select2:select', function(e) {
            $('.select2-container').css('z-index', '9999');
            // Get the difference
            var diff = get_array_difference(selected_interviewers, $(this).val());
            // Save the current array of values
            // to a variable
            selected_interviewers = $(this).val();


            handle_all_check(selected_interviewers);
            //
            if (selected_interviewers.length > 1) {
                selected_interviewers.remove('all');
                $('#js-interviewers-select').select2('val', selected_interviewers);
            }

            // Get interviewer details
            var obj = get_ie_obj(diff[0]);
            // Create email row
            select_interviewer({
                id: obj['employer_id'],
                employee_type: obj['employee_type'],
                'label': obj['full_name']
            });
        });

        // Triggers when interviewer is un-selected
        $('#js-interviewers-select').on('select2:unselect', function() {
            // Get the id
            var id = $(this).val();
            // If 'NULL' then don't continue
            if (id == null) $('#js-interviewers-list').html('');
            // Get the difference
            var diff = _.difference(selected_interviewers, id);
            selected_interviewers = id;
            handle_all_check(selected_interviewers);
            // Close the menu
            $('#js-interviewers-select').select2('close');
            // Remove the email row
            unselect_interviewer({
                id: diff[0]
            });
        });

        // Calendar
        // Updated on: 01-04-2019
        calendar_ref.fullCalendar({
            header: {
                left: 'prevYear,prev,next,nextYear',
                center: 'title',
                right: 'today, agendaDay, agendaWeek, month'
            },
            forceEventDuration: true,
            selectHelper: true,
            eventLimit: true, // allow "more" link when too many events
            allDaySlot: false,
            timeFormat: 'h(:mm)',
            agendaEventMinHeight: null,
            //
            select: function(start, end) {
                var title = prompt('Event Title:');
                if (!title) {
                    calendar_ref.fullCalendar('unselect');
                    return false;
                }
                calendar_ref.fullCalendar('renderEvent', {
                    title: title,
                    start: start,
                    end: end
                }, true); // stick? = true
            },
            // Triggers when view mode is changed
            viewRender: function(e) {
                drag_event = {};
                event_process(e);
            },
            // Triggers when an event is clicked
            eventClick: function(event) {
                if (event.type != 'timeoff' && event.type != 'holidays' && event.type != 'goals' && event.type != 'shifts')
                    edit_event(event);
                else if (event.type == 'timeoff') timeoffview($(this), event);
                else if (event.type == 'goals') goalsView($(this), event);
                else if (event.type == 'shifts') shiftsview($(this), event);

            },
            eventMouseover: function(event, jsEvent, view) {
                if (event.type == 'goals') {
                    goalsView($(this), event);
                } else if (event.type == 'shifts') {
                    shiftsview($(this), event);
                } else {
                    timeoffview($(this), event);
                }
            },
            //
            eventDragStart: function(event) {
                if (Object.size(drag_event) == 0 || event.event_id != drag_event.id) {
                    drag_event = {
                        id: event.event_id,
                        start_time: event.start,
                        end_time: event.end,
                        date: event.eventdate
                    };
                }
            },
            // Triggers when an event is drop
            eventDrop: function(event, delta, revertFunc) {
                update_event(event, revertFunc, 'drag');
            },
            // Triggers when an event is resized
            eventResizeStart: function(event) {
                if (Object.size(drag_event) == 0 || event.event_id != drag_event.id) drag_event = {
                    start_time: event.start,
                    end_time: event.end,
                    date: event.eventdate
                };
            },
            eventResize: function(event) {
                update_event(event, undefined, 'resize');
            },
            // Triggers when there is click on calendar
            dayClick: function(date, all_day, js_event) {
                func_show_loader();
                add_event(date, js_event);
            },
            eventRender: function(event, el) {
                //
                if (event.type == 'goals' && ob['goals'] == 0) return false;
                if (event.type == 'timeoff' && ob['timeoff'] == 0) return false;
                if (event.type == 'shifts' && ob['shifts'] == 0) return false;

                if (event.requests != 0) {
                    el.addClass("fc-event-blink");
                    if (blink_interval == null)
                        event_blinker();
                }
                // Add id for trigger reschedule
                // click on drag
                el.addClass("fc-event-" + event.event_id + "");
                el.addClass("fc-event-" + event._id + "");
                // Basic styling
                el.addClass("fc-event-cc");
                if (event.category == 'holidays') {
                    el.addClass("fc-event-hld");
                    el.addClass("fc-event-hld-" + (event.icon == '' || event.icon == null ? '28' : event.icon.split('.')[0]) + "");
                }
                if (event.type == 'timeoff' && event.timeoff_status.toLowerCase() == 'pending') {
                    el.addClass("fc-event-cc-timeoff_pending");
                    el.addClass("cs-event-btn-timeoff_pending");
                }
                if (event.type == 'goals') {
                    el.addClass("fc-event-cc-goals");
                    el.addClass("cs-event-btn-goals");
                }
                // Category effect
                el.addClass("fc-event-cc-" + event.category + "");
                //Set border style
                el.addClass("fc-event-cc-" + event.status + "");
                // if(event.type == 'timeoff'){
                //     el.addClass("js-timeoff-edit-request");
                //     el.attr("timeoff-request-id",event.request_id);
                // }
                // For event coming from url
                if (i_triggered === true && event.event_id == i_event_array.event_sid) {
                    i_triggered = false;
                    edit_event(event);
                }
                // Returns if recur option is FALSE
                if (default_recur === 0) return true;
                return check_for_reoccur_event(event);
            }
        });

        // Load date calendar
        $(".datepicker").datepicker({
            dateFormat: 'mm-dd-yy DD',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>",
            onSelect: function(d) {
                $('.js-datepicker').datepicker('setDate', new Date(d));
            }
        });

        // Loads time plugin for start time field
        $('#js-event-start-time').datetimepicker({
            datepicker: false,
            format: 'g:iA',
            formatTime: 'g:iA',
            step: <?= $calendar_opt['time_duration']; ?>,
            onShow: function(ct) {
                this.setOptions({
                    maxTime: $('#js-event-end-time').val() ? $('#js-event-end-time').val() : false
                });
            }
        });

        // Loads time plugin for end time field
        $('#js-event-end-time').datetimepicker({
            datepicker: false,
            format: 'g:iA',
            formatTime: 'g:iA',
            step: <?= $calendar_opt['time_duration']; ?>,
            onShow: function(ct) {
                time = $('#js-event-start-time').val();
                if (time == '') return false;
                timeAr = time.split(":");
                last = parseInt(timeAr[1].substr(0, 2)) + 15;
                if (last == 0)
                    last = "00";
                mm = timeAr[1].substr(2, 2);
                timeFinal = timeAr[0] + ":" + last + mm;
                this.setOptions({
                    minTime: $('#js-event-start-time').val() ? timeFinal : false
                })
            }
        });

        // File plugin on message file button
        $('#js-message-file').filestyle({
            text: 'Add Attachment',
            btnClass: 'btn-success',
            placeholder: "No file selected"
        });

        // User type toggle
        $(document).on('click', '.js-users-type', function() {
            remove_all_from_interviewers();
            $('#js-video-wrap, .js-video-hr').hide(0);

            $('#js-applicant-search-bar, #js-employee-search-bar, #js-applicant-box, #js-employee-box').hide(0);

            $('#js-reminder-email-wrap').hide(0);
            //
            $('#js-' + $(this).val() + '-box').show(0);
            // For ajax
            // $('#js-'+ $(this).val() +'-search-bar').show(0);

            // Resets
            reset_modal_view(true);
            // 
            if ($('#js-event-id').val() != '' && selected_user_type == $(this).val()) event_type['default_' + selected_user_type] = selected_user_category;
            // For non-ajax
            // Only show input search bar for applicant
            if ($(this).val() == 'applicant') {
                $('#js-' + $(this).val() + '-search-bar').show(0);
            }
            $('.js-comment-text > span').html(default_comment_text);
            // Comment changes
            if ($(this).val() == 'personal' || $(this).val() == 'employee') {
                $('.js-comment-text > span').html('Comment for Participant(s)');
                $('#message_to_label').html('Employee');
            } else {
                $('.js-comment-text > span').html('Comment for Interviewers(s)');
                $('#message_to_label').html('Applicant');
            }
            if ($(this).val() == 'applicant') {
                $('#attendees_label').text('Interviewer(s)');
                $('#js-non-employee-heading').text('Non-employee Interviewer(s)');
            }
            if ($(this).val() == 'employee') {
                $('#attendees_label').text('Participant(s)');
                $('#js-non-employee-heading').text('Non-employee Participant(s)');
            }
            // Check if personal is clicked
            if ($(this).val() == 'personal') {
                load_personal_view();
                $('.js-comment-text > span').text(personal_comment_text);
            } else load_categories($(this).val());
            //
            if ($('#js-event-id').val() != '' && selected_user_type == $(this).val()) $('#js-reminder-email-wrap').show(0);
        });

        // Comment check toggle
        $(document).on('click', '#js-comment-check', function() {
            $('#js-comment-msg-box').hide(0);
            if ($(this).prop('checked')) $('#js-comment-msg-box').show(0);
        });

        // Message check toggle
        $(document).on('click', '#js-message-check', function() {
            $('#js-message-box').hide(0);
            if ($(this).prop('checked')) $('#js-message-box').show(0);
        });

        // Address check toggle
        $(document).on('click', '.js-address-type', function() {
            $('#js-address-select-box, #js-address-input-box').hide(0);
            if ($(this).val() == 'new') $('#js-address-input-box').show(0);
            else $('#js-address-select-box').show(0);
        });

        // Meeting check toggle
        $(document).on('click', '#js-meeting-check', function() {
            $('#js-meeting-box').hide(0);
            if ($(this).prop('checked')) $('#js-meeting-box').show(0);
        });

        // Reminder check toggle
        $(document).on('click', '#js-reminder-check', function() {
            $('#js-reminder-box').hide(0);
            if ($(this).prop('checked')) $('#js-reminder-box').show(0);
        });

        // Reminder email check toggle
        $(document).on('click', '#js-reminder-email-check', function() {
            $('#js-reminder-email-box').hide(0);
            if ($(this).prop('checked')) $('#js-reminder-email-box').show(0);
        });

        // Add extra interviewer event
        $(document).on('click', '#btn_add_participant', function() {
            var random_id = Math.floor((Math.random() * 1000) + 1);
            var new_row = $('#external_participant_0').clone();
            //
            $(new_row).find('i.fa').removeClass('fa-plus').addClass('fa-trash');
            $(new_row).find('button.btn').removeAttr('id').removeClass('btn-success').addClass('btn-danger').addClass('btn_remove_participant').attr('data-id', random_id);
            $(new_row).find('input').val('');
            $(new_row).attr('id', 'external_participant_' + random_id);
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
            $('#js-extra-interviewers-box').append(new_row);
        });
        // Remove extra interviewer event
        $(document).on('click', '.btn_remove_participant', function() {
            $($(this).closest('.external_participants').get()).remove();
        });

        // Get adress event
        $('#js-address-select').click(get_address);

        // Remove body overlay
        // on modal close
        $(document).on('hidden.bs.modal', '#js-event-modal', function() {
            $('body').removeClass('ajs-no-overflow');
        });

        // Remove interviewer row
        $(document).on('click', '.js-delete-interviewer', function() {
            var id = $(this).data('id');
            //
            $.each(interviewers_list_array, function(i, v) {
                if (v == id) {
                    interviewers_list_array.splice(i, 1);
                    return false;
                }
            })
            $(this).parent().parent().remove();
            if ($('.js-delete-interviewer').length == 0)
                $('#js-interviewers-box').hide(0);
        });

        // Calendar save, update
        // delete, cancel, reschedule
        // events
        // Save new event
        $('#js-save').click(function() {
            if (!form_check() || !$('#event_form').valid()) return false;
            save_update_event('save', event_save_info);
        });
        // Update event
        $('#js-update').click(function() {
            if (!form_check()) return false;
            save_update_event('update', event_update_info);
        });
        // Reschedule event
        $(document).on('click', '#js-reschedule', function() {
            if (!form_check()) return false;
            save_update_event('reschedule', event_update_info);
        });
        // Expired Reschedule event
        $(document).on('click', '#js-expired-reschedule', function() {
            if (!form_check()) return false;
            save_update_event('expired-reschedule', event_save_info);
        });
        // Delete event
        $('#js-delete').click(function() { //delete an event
            alertify.confirm(
                'Are you sure?',
                'Are you sure you want to delete this event?',
                function() {
                    event_id = $('#js-event-id').val();
                    $('#loader').show();
                    $('.btn').addClass('disabled');
                    $('.btn').prop('disabled', true);

                    $.ajax({
                        url: "<?= base_url() ?>calendar/deleteEvent?sid=" + event_id,
                        type: 'GET',
                        success: function(msg) {
                            $('#loader').hide();
                            $('.btn').removeClass('disabled');
                            $('.btn').prop('disabled', false);
                            $('#popup1').modal('hide');

                            alertify.alert(msg, function() {
                                remove_event(event_id);
                                // location.reload();
                            });
                        },
                        always: function() {
                            $('#loader').hide();
                            $('.btn').removeClass('disabled');
                            $('.btn').prop('disabled', false);
                        }
                    });
                },
                function() {
                    alertify.error('Cancelled!');
                });
        });
        // Cancel event
        $('#js-cancel').click(function() { //Cancel Event
            alertify.confirm(
                'Are you sure?',
                'Are you sure you want to Cancel this event?',
                function() {
                    event_id = $('#js-event-id').val();
                    var my_request;

                    $('#loader').show();
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
                        $('#loader').hide();
                        $('.btn').removeClass('disabled');
                        $('.btn').prop('disabled', false);

                        if (res.Status === true) {
                            // Do this before you initialize any of your modals
                            $.fn.modal.Constructor.prototype.enforceFocus = function() {};
                            $('#js-event-modal').modal('toggle');
                            alertify.success('Event Cancelled!');
                            cancel_event(event_id);
                        } else {
                            // Do this before you initialize any of your modals
                            $.fn.modal.Constructor.prototype.enforceFocus = function() {};
                            $('#js-event-modal').modal('toggle');
                            alertify.error('Could Not Cancel Event!');
                            window.location = window.location.href;
                        }
                    });

                    my_request.always(function() {
                        $('#loader').hide();
                        $('.btn').removeClass('disabled');
                        $('.btn').prop('disabled', false);
                    });
                },
                function() {
                    alertify.error('Cancelled!');
                });
        });

        // Loads autocomplete plugins for applicant
        // passed a function to fetch records
        $('#js-applicant-search-bar').autocomplete({
            source: get_applicant,
            minLength: 2,
            select: function(e, ui) {
                select_applicant(ui.item.id,
                    ui.item.value,
                    ui.item.phone_number,
                    ui.item.portal_applicant_jobs_list_sid,
                    (ui.item.jobs == null ? [] : ui.item.jobs[ui.item.jobs.length - 1])
                );
            }
        });

        // Datepicker for personal type
        $('.js-datepicker').datepicker({
            dateFormat: 'mm-dd-yy DD',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>",
            onSelect: function(d) {
                $('.datepicker').datepicker('setDate', new Date(d));
            }
        });

        // Loads time plugin for start time field
        $('.js-clone-start-time').datetimepicker({
            datepicker: false,
            format: 'g:iA',
            formatTime: 'g:iA',
            step: <?= $calendar_opt['time_duration']; ?>,
            onShow: function(ct) {
                this.setOptions({
                    maxTime: $('.js-clone-end-time').val() ? $('.js-clone-end-time').val() : false
                });
            }
        });

        // Loads time plugin for end time field
        $('.js-clone-end-time').datetimepicker({
            datepicker: false,
            format: 'g:iA',
            formatTime: 'g:iA',
            step: <?= $calendar_opt['time_duration']; ?>,
            onShow: function(ct) {
                var time = $('.js-clone-start-time').val();
                if (time == '') return false;
                timeAr = time.split(":");
                last = parseInt(timeAr[1].substr(0, 2)) + 15;
                if (last == 0)
                    last = "00";
                mm = timeAr[1].substr(2, 2);
                timeFinal = timeAr[0] + ":" + last + mm;
                this.setOptions({
                    minTime: $('.js-clone-start-time').val() ? timeFinal : false
                })
            }
        });

        // Shows event status requests history page
        $(document).on('click', '.js-status-history-btn', function(e) {
            e.preventDefault();
            func_show_loader();
            // Remove Blinker
            event_blinker('clearBtnInterval');
            $('.js-history-close').remove();
            request_page_REF.prepend('<button class="btn btn-default js-history-close" style="margin-bottom: 10px;"><i class="fa fa-arrow-left"></i>&nbsp; back</button>')
            main_page_REF.fadeOut(500);
            reminder_page_REF.fadeOut(0);
            request_page_REF.fadeIn(500);
            request_page_REF.find('tbody').html('tr><td colspan="' + request_page_REF.find('thead > tr > th').length + '"><h4 class="text-center">Please, wait while we are fetching event history...</h4></td>');
            event_history_type = 'requests';
            $('.js-event-head-title').text(event_title_text + ' - Event Status Requests');
            current_page = 1;
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
            $('.js-event-head-title').text(event_title_text + ' - Reminder Sent Emails History');
            current_page = 1;
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
            fetch_event_change_history();
        });

        // Shows main modal page
        // on close button click
        $(document).on('click', '.js-history-close', function(e) {
            e.preventDefault();
            func_show_loader();
            $('.js-history-close').remove();
            $('.js-event-head-title').text(event_title_text);
            request_page_REF.fadeOut(300);
            main_page_REF.fadeIn(300);
            func_hide_loader();
        });

        // Shows main modal page
        // on close button click
        $(document).on('click', '.js-reminder-history-close', function(e) {
            e.preventDefault();
            func_show_loader();
            $('.js-event-head-title').text(event_title_text);
            $('.js-history-close').remove();
            reminder_page_REF.fadeOut(200);
            main_page_REF.fadeIn(200);
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

        // Load recurr view
        $(document).on('click', '.js-recurr-type', function() {
            load_recurr_view($(this).val().toLowerCase());
        });

        // Days on, off
        $(document).on('click', '.js-day-box', function() {
            if ($(this).hasClass('cs-active-day'))
                $(this).removeClass('cs-active-day');
            else
                $(this).addClass('cs-active-day');

            set_summary();
        });

        // Months on, off
        $(document).on('click', '.js-month-box', function() {
            if ($(this).hasClass('cs-active-day'))
                $(this).removeClass('cs-active-day');
            else
                $(this).addClass('cs-active-day');

            set_summary();
        });

        // Triggeres when infinite check
        // is changed
        $(document).on('click', '.js-infinite', function() {
            set_summary();
        });

        // Triggers when
        // week is entered 
        $(document).on('keyup', '#js-recurr-week-input', function() {
            if (isNaN($(this).val()) || $(this).val() < 1)
                $(this).val(1);
            set_summary();
        });

        // Triggers when 
        // year is entered
        $(document).on('keyup', '#js-recurr-year-input', function() {
            if (isNaN($(this).val()) || $(this).val() < 1)
                $(this).val(1);
            set_summary();
        });

        // Triggers on
        // reccur check
        $(document).on('click', '#js-reoccur-check', function() {
            $('#js-reoccur-box').hide();
            if ($(this).prop('checked') === true) {
                $('#js-reoccur-box').show();
                $('#js-recurr-daily-check').prop('checked', true);
                load_recurr_view('daily');
            }
        });

        //
        $('.js-person-email-check-input').click(function() {
            $('.js-person-email').toggle();
        });

        // Loads autocomplete plugins for employee
        // passed a function to fetch records
        // For AJAX
        // $('#js-employee-search-bar').autocomplete({
        //     source: get_employees,
        //     minLength: 2,
        //     select: function(e, ui){
        //         select_employee(ui.item.id, ui.item.value, ui.item.phone_number);
        //     }
        // });

        // Loads autocomplete plugins for interviewers
        // passed a function to fetch records
        // For AJAX
        // $('#js-interviewers').autocomplete({
        //     source: get_interviewers,
        //     minLength: 2,
        //     select: function(e, ui){
        //         select_interviewer(ui.item);
        //         ui.item.value = '';
        //     }
        // });

        //  paste js-event-title-p value in js-event-title.
        $("#js-event-title-p").on("keyup paste change", function() {
            var title_p = $('#js-event-title-p').val();
            $('#js-event-title').val(title_p);
        });

        //  paste js-event-title value in js-event-title-p.
        $("#js-event-title").on("keyup paste change", function() {
            var title_p = $('#js-event-title').val();
            $('#js-event-title-p').val(title_p);
        });


        // Validate calendar
        function form_check() {
            url_regex.lastIndex = digit_regex.lastIndex = email_regex.lastIndex = phone_regex.lastIndex = 0;
            alertify.defaults.glossary.title = 'Event Management!';
            //
            if ($('.js-users-type:checked').val() == 'applicant') {
                if ($('#js-applicant-input').val() == null || $('#js-applicant-input').val() <= 0 || $('#js-applicant-input').val() == ' ') {
                    alertify.alert("Please, select an Applicant.", flush_alertify_cb);
                    return false;
                }
                //
                var applicant_phone = $('#js-applicant-phone').val().trim();
                // Check for phone
                if (!phone_regex.test(applicant_phone)) {
                    alertify.alert("Please, enter a proper phone number for the applicant.", flush_alertify_cb);
                    return false;
                }
            } else if ($('.js-users-type:checked').val() == 'employee') {
                // if ($('#js-employee-select').val() == null || $('#js-employee-select').val() == 'null' || $('#js-employee-select').val() <= 0 || $('#js-employee-select').val() == ' ') {
                //     alertify.alert("Please, select an Employee.", flush_alertify_cb);
                //     return false;
                // }
                var employee_phone = $('#js-employee-phone').val().trim();
                if (employee_phone != '' && employee_phone != '(___) ___-____') {
                    //
                    // Check for phone
                    if (!phone_regex.test(employee_phone)) {
                        alertify.alert("Please, enter a proper phone number for the employee or leave it empty.", flush_alertify_cb);
                        return false;
                    }
                }

            }

            var user_type = $('.js-users-type:checked').val();
            //
            if (user_type == 'employee' && $('.js-selected-event-category').val() != 'training-session') {
                if ($('#js-employee-select').val() == '' || $('#js-employee-select').val() == null) {
                    alertify.alert('Please, select an employee', flush_alertify_cb);
                    return false;
                }
            }

            if ($((user_type == 'personal' && last_selected_category != 'other') ? '#js-event-title-p' : '#js-event-title').val() == '') {
                alertify.alert("Please, provide a title for the event.", flush_alertify_cb);
                return false;
            }

            if ($(user_type == 'personal' ? '.js-datepicker' : '#js-event-date').val() == '') {
                alertify.alert("Please, provide the event date.", flush_alertify_cb);
                return false;
            }

            if ($(user_type == 'personal' ? '.js-clone-start-time' : '#js-event-start-time').val() == '') {
                alertify.alert("Please, provide event start-time.", flush_alertify_cb);
                return false;
            }

            if ($(user_type == 'personal' ? '.js-clone-end-time' : '#js-event-end-time').val() == '') {
                alertify.alert("Please, provide event end-time.", flush_alertify_cb);
                return false;
            }

            if (user_type == 'personal' && (last_selected_category == 'call' || last_selected_category == 'email') && $('.js-person-name input').val() == '') {
                alertify.alert("Please, provide person name.", flush_alertify_cb);
                return false;
            }

            if (user_type == 'personal' && last_selected_category == 'call' && $('.js-person-phone input').val() == '' && $('.js-person-phone input').val() == '(___) ___-____') {
                alertify.alert("Please, provide person phone.", flush_alertify_cb);
                return false;
            }

            if (user_type == 'personal' && last_selected_category == 'call' && !phone_regex.test($('.js-person-phone input').val())) {
                alertify.alert("Please, provide a valid phone number.", flush_alertify_cb);
                return false;
            }

            if (user_type == 'personal' && (last_selected_category == 'email' || $('.js-person-email-check-input').prop('checked') === true) && $('.js-person-email input').val() == '') {
                alertify.alert("Please, provide email address.", flush_alertify_cb);
                return false;
            }

            if (user_type == 'personal' && last_selected_category == 'email' && email_regex.test($('.js-person-email input').val()) === false) {
                alertify.alert("Please, provide a valid email address.", flush_alertify_cb);
                return false;
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

            // Check for comment
            if ($('#js-comment-check').prop('checked') === true && $('#js-comment-msg').val().trim() == '') {
                alertify.alert('A comment is missing. If you don\'t want to add a comment please uncheck "Comment" check.', flush_alertify_cb);
                return false;
            }

            // Check for message
            if ($('#js-message-check').prop('checked') === true) {
                if ($('#js-message-subject').val().trim() == '') {
                    alertify.alert('The Subject field is missing. If you don\'t want to send email to a candidate, please uncheck "Message To Candidate" check.', flush_alertify_cb);
                    return false;
                }
                if ($('#js-message-body').val().trim() == '') {
                    alertify.alert('Message field is missing. If you don\'t want to send email to a candidate, please uncheck "Message To Candidate" check.', flush_alertify_cb);
                    return false;
                }
            }

            // Check for meeting
            if ($('#js-meeting-check').prop('checked') === true) {

                if ($('#js-meeting-call').val().trim() == '') {
                    alertify.alert('The call field is missing. If you don\'t want to set meeting details, please uncheck "Meeting Call Details" check.', flush_alertify_cb);
                    return false;
                }
                if (!digit_regex.test($('#js-meeting-call').val().trim())) {
                    alertify.alert('The call field can only hold numeric values. If you don\'t want to set meeting details, please uncheck "Meeting Call Details" check.', flush_alertify_cb);
                    return false;
                }
                // Reset
                digit_regex.lastIndex = 0;

                if ($('#js-meeting-id').val().trim() == '') {
                    alertify.alert('The ID field is missing. If you don\'t want to set meeting details, please uncheck "Meeting Call Details" check.', flush_alertify_cb);
                    return false;
                }
                // if(!digit_regex.test($('#js-meeting-id').val().trim())){
                //     alertify.alert('The ID field can only hold numeric values. If you don\'t want to set meeting details, please uncheck "Meeting Call Details" check.', flush_alertify_cb);
                //     return false;
                // }

                if ($('#js-meeting-url').val().trim() == '') {
                    alertify.alert('The URL field is missing. If you don\'t want to set meeting details, please uncheck "Meeting Call Details" check.', flush_alertify_cb);
                    return false;
                }

                if (!url_regex.test($('#js-meeting-url').val().trim())) {
                    alertify.alert('The URL is invalid. If you don\'t want to set meeting details, please uncheck "Meeting Call Details" check.', flush_alertify_cb);
                    return false;
                }
            }

            // Only if address is required
            // if ($('#js-address-text').val() == '' && $('#js-address-list').val() == null) {
            //     alertify.alert("Please, provide an address for the event.");
            //     return false;
            // }

            // set default interviewer to employer
            if ($('#js-interviewer').val() == null || $('#js-interviewer').val() <= 0 || $('#js-interviewer').val() == ' ') {
                $('#js-interviewer').val(<?= $employer_id; ?>);
            }
            return true;
        }


        // Update event on drag, resize
        // @param event 
        // Contains the information
        // of current event
        // @param revert
        // @param type
        // Either drag and resize 
        function update_event(event, revert, type) {
            type = type === undefined ? 'drag' : type;
            if (event.is_recur == 1) {
                revert();
                return true;
            }
            // updates
            // use moment functions
            var eventstarttime = moment(event.start).format('hh:mm');
            var eventstarttime12hr = moment(event.start).format('hh:mmA');
            var eventendtime = moment(event.end).format('hh:mm');
            var eventendtime12hr = moment(event.end).format('hh:mmA');
            var eventDate = moment(event.start, site_date_format).format(site_date_format);

            // Check for expire events
            // and stop drag and drop event
            if (event.expired_status === true) {
                // Search for el
                // var el = calendar_ref.find(event._id);

                // event.color = event_color_obj['expired'];
                // Popup message and show event details
                // upon okay
                alertify.confirm("The event is expired. Do you want to reschedule it?", function() {
                    // Trigger click event on modal
                    $('.fc-event-' + event._id + '').trigger('click');
                    // Make sure the event hit after
                    // loading data to modal
                    var tmp_interval = setInterval(function() {
                        if ($('#js-event-id').val() != '') {
                            clearInterval(tmp_interval);
                            save_update_event('expired-reschedule', event_save_info);
                        }
                    }, 300);
                    // main_page_REF.hide(0);
                    // reschedule_page_REF.show(0);
                }, flush_alertify_cb).set('labels', {
                    ok: 'Yes',
                    cancel: 'No'
                });
                // Revert the changes
                revert();
                return false;
            }
            // Check for expiration
            else if (moment().utc() > moment(eventDate + ' 23:59:59')) {
                calendar_ref.find(event._id).switchClass('fc-event-cc-' + event.status + '', 'fc-event-cc-expired');
                // event.status = 'expired';
                event.expired_status = true;
            } else {
                event.color = event_color_obj[event.category];
                // event.color = event.status != 'confirmed' ? event_color_obj['notconfirmed'] : event.color;
                // event.color = event.status == 'cancelled' ? event_color_obj['cancelled'] : event.color;
            }

            // Set difference object
            var diff_obj = {};
            // Difference of event date
            if (eventDate != drag_event.date) {
                diff_obj.new_date = eventDate;
                diff_obj.old_date = drag_event.date;
            }

            // Difference of event start time
            if (eventstarttime12hr != moment(drag_event.start_time).format('hh:mmA')) {
                diff_obj.new_event_start_time = eventstarttime12hr;
                diff_obj.old_event_start_time = moment(drag_event.start_time).format('hh:mmA');
            }

            // Difference of event end time
            if (eventendtime12hr != moment(drag_event.end_time).format('hh:mmA')) {
                diff_obj.new_event_end_time = eventendtime12hr;
                diff_obj.old_event_end_time = moment(drag_event.end_time).format('hh:mmA');
            }

            // Reset date to UTC
            var date = moment(eventDate, site_date_format).format(site2_date_format);
            date = moment(date + ' ' + eventstarttime12hr, site2_date_format + ' hh:mmA').format(site2_date_format);

            func_show_loader();

            $.ajax({
                url: "<?php echo base_url('calendar/event-handler'); ?>",
                type: 'POST',
                data: {
                    action: 'drag_update_event',
                    sid: event.event_id,
                    date: date,
                    type: type,
                    eventstarttime: eventstarttime12hr,
                    eventendtime: eventendtime12hr,
                    diff: JSON.stringify(diff_obj)
                },

                success: function(res) {
                    func_hide_loader();
                    alertify.success(res.Response, flush_alertify_cb);
                }
            });
        }


        function find_calendar_event(event_id) {
            var return_obj;
            $.each(calendar_ref.fullCalendar('clientEvents'), function(i, v) {
                if (v.event_id == event_id) {
                    return_obj = v;
                    return false;
                }
            });

            return return_obj;
        }

        function reset_timezone(str, from_format, format, utc) {
            //
            utc = utc === undefined ? false : utc;
            from_format = from_format === undefined ? site_date_format : from_format;
            format = format === undefined ? site2_date_format : format;
            //
            var new_date = moment(str, from_format).utcOffset(user_zone.time);
            //
            return utc === true ? new_date.utc().format(format) : new_date.format(format);
        }

        // Load add event
        // when day or time is clicked
        // @param date
        // Holds the selected datetime info.
        // @param js_event
        // Contains the information
        // of current event
        function add_event(date, js_event) {
            // set default start and end time
            // for day and week modes
            if (js_event.name == 'agendaDay' || js_event.name == 'agendaWeek') {
                default_start_time = moment(date).format('hh:mmA');
                default_end_time = moment(date).add(add_time, add_time_type).format('hh:mmA');
            }
            // reset the modal
            reset_modal();
            // Do this before you initialize any of your modals
            $.fn.modal.Constructor.prototype.enforceFocus = function() {};
            $('#js-event-modal').modal('show');
            $('#js-event-date').val(moment(date, site_date_format).format(site_date_format + " dddd"));
            $('#js-event-start-time').val(default_start_time);
            $('#js-event-end-time').val(default_end_time);
            var employer_timezone = '<?= $employer_timezone ?>';
            $('#event_timezone').val(employer_timezone);
            $('#event_timezone-personal').val(employer_timezone);
            // For AJAX
            // select_interviewer({id: "<?= $employer_id; ?>", email_address: "<?= $employee['email']; ?>"});

            select_interviewer({
                id: "<?= $employer_id; ?>",
                employee_type: get_ie_obj(<?= $employer_id; ?>)['employee_type'],
                label: get_ie_obj(<?= $employer_id; ?>)['full_name']
            });
            $('#js-save').show();
            func_hide_loader();
        }

        // Load edit event
        // when event is clicked
        // @param e
        // Contains the information
        // of current event
        function edit_event(e) {
            event_blinker('clearBtnInterval');
            if (e.event_id === undefined) {
                alertify.alert('This event is no longer available.', flush_alertify_cb);
                return false;
            }
            func_show_loader();
            reset_modal();
            $('.loader-text').text('Please wait while we fetch event details.');
            $('#loader').show();
            $('.btn').addClass('disabled');
            // Do this before you initialize any of your modals
            $.fn.modal.Constructor.prototype.enforceFocus = function() {};
            $('#js-event-modal').modal('show');
            // send ajax request
            $.get("<?= base_url(); ?>calendar/get-event-detail/" + e.event_id + "", function(resp) {
                if (resp.Status === false && resp.Redirect === true) window.location.reload();
                if (resp.Status === false) {
                    alertify.alert('Event is no longer available.', flush_alertify_cb);
                    func_hide_loader();
                    //
                    remove_event_from_calendar(e._id);
                    $('#loader').hide();
                    $('#js-event-modal').modal('hide');
                    $('.loader-text').text(default_loader_text);
                    $('.btn').removeClass('disabled');
                    return false;
                }
                var personal_status = false,
                    is_event_expired = false,
                    is_personal_check = false;
                //
                var event = resp.Event;
                selected_user_type = event.users_type;
                selected_user_category = e.category;
                // Save event locally
                save_event_locally(event, e.event_id);
                // format phone number
                // event.users_phone = event.users_phone.replace('+1', '');
                //
                // var tmp = fpn(event.users_phone);
                // event.users_phone = typeof(tmp) === 'object' ? tmp.number : tmp;
                // if(typeof(tmp) === 'object') setCaretPosition($('#js-'+(event.user_type)+'-phone'), tmp.cur);

                // load data to modal
                //
                <?php if (strtolower($access_level) != 'employee') { ?>
                    $('#js-user-type-applicant').prop('checked', true);
                    $('#js-user-type-employee').prop('checked', false);
                <?php } else { ?>
                    $('#js-user-type-employee').prop('checked', true);
                <?php } ?>

                if (event.users_type == 'applicant') {
                    // Comment changes
                    $('.js-comment-text > span').html('Comment for Interviewers(s)');
                    $('#message_to_label').html('Applicant');

                    // fill the search bar
                    $('#js-applicant-search-bar').val(event.applicant_details.value);
                    $('#js-applicant-input').val(event.applicant_details.value);
                    $('#js-applicant-phone').val(event.users_phone);
                    $('#js-applicant-input').data('id', event.applicant_details.id);
                    $('#js-employee-input').data('id', event.applicant_details.id);
                    // add job list
                    if (event.applicant_details.jobs.length > 0) {
                        generate_job_list(
                            event.applicant_details.jobs,
                            ((event.applicant_jobs_list == '' || event.applicant_jobs_list == null) ? [] : event.applicant_jobs_list.split(','))
                        );
                    }
                    $('.js-comment-box, .js-comment-hr').show(0);
                    selected_employer_id = 0;
                } else if (event.users_type == 'employee') {
                    $('.js-comment-text > span').html('Comment for Participant(s)');
                    $('#message_to_label').html('Employee');
                    $('label#attendees_label').text('Participant(s)');
                    $('#js-non-employee-heading').text('Non-employee Participant(s)');

                    $('#js-user-type-employee').prop('checked', true);
                    $('#js-user-type-applicant').prop('checked', false);
                    // For AJAX
                    // $('#js-employee-search-bar').val(event.employer_details.value);
                    // $('#js-employee-input').val(event.employer_details.value);
                    if (event.employer_details === null) {
                        alertify.alert("Error! Employer sid is not set", flush_alertify_cb);
                        func_hide_loader();
                        //
                        $('#loader').hide();
                        $('.loader-text').text(default_loader_text);
                        $('.btn').removeClass('disabled');
                        $('body').removeClass('ajs-no-overflow');
                        $('#js-event-modal').modal('hide');
                        return false;
                    }
                    selected_employer_id = event.employer_details.id;
                    // interviewers_list_array.push(selected_employer_id);
                    $('#js-employee-select').select2('val', [event.employer_details.id]);
                    $('#js-employee-phone').val(event.users_phone);
                    $('#js-applicant-input').data('id', event.employer_details.id);
                    $('#js-employee-input').data('id', event.employer_details.id);
                    $('#js-interviewers-select').find('option[value="' + selected_employer_id + '"]').prop('disabled', true);
                    // For AJAX
                    // $('#js-employee-search-bar').show(0);
                    $('#js-employee-box').show(0);
                    $('#js-applicant-box').hide(0);
                    $('#js-applicant-search-bar').hide(0);
                    load_categories('employee');
                    $('.js-comment-box, .js-comment-hr').show(0);

                } else {
                    $('#js-applicant-box, #js-employee-box, #js-applicant-search-bar').hide(0);
                    $('#js-user-type-employee').prop('checked', false);
                    $('#js-user-type-applicant').prop('checked', false);
                    $('#js-user-type-personal').prop('checked', true);
                    reset_modal_view();
                    $('.js-selected-event-category-p').val(e.category);
                    load_personal_view({
                        category: e.category
                    });
                    $('#js-event-title-p').val(event.event_title);
                    $('.js-datepicker').val(moment(event.date, site2_date_format).format(site_date_format + " dddd"));
                    if (e.category == 'call') {
                        $('.js-person-name').find('input').val(event.users_name);
                        $('.js-person-phone').find('input').val(event.users_phone);
                    }
                    if (e.category == 'email') {
                        $('.js-person-name').find('input').val(event.users_name);
                        $('.js-person-email').find('input').val(event.users_email);
                    }
                    event.address = event.address == 'null' ? '' : event.address
                    $('#js-address-text').val(event.address);
                    //
                    //
                    is_personal_check = true;
                    personal_status = true;
                    event.date = moment(event.date, site2_date_format).format(site_date_format)
                    selected_employer_id = 0;
                    // if(e.category == 'call' || e.category == 'email') personal_status = true;
                }

                $('.js-clone-start-time').val(event.event_start_time);
                $('.js-clone-end-time').val(event.event_end_time);
                var employer_timezone = '<?= $employer_timezone ?>';
                if (event.event_timezone != null) {
                    $('#event_timezone').val(event.event_timezone);
                    $('#event_timezone-personal').val(event.event_timezone);
                } else {
                    $('#event_timezone').val(employer_timezone);
                    $('#event_timezone-personal').val(employer_timezone);
                }

                //
                $('#js-event-title').val(event.event_title);
                //
                $('#js-event-id').val(e.event_id);
                $('#js-event-date').val(moment(event.date, site_date_format).format(site_date_format + " dddd"));
                //$('#js-event-date').val(event.date);
                //$('#js-event-date').val(moment(event.date).format(site_date_format+" dddd"));
                $('#js-event-start-time').val(event.event_start_time);
                $('#js-event-end-time').val(event.event_end_time);
                //
                if (event.comment_check == 1) {
                    $('#js-comment-check').prop('checked', true);
                    $('#js-comment-msg').val(event.comment);
                    $('#js-comment-msg-box').show();
                }
                //
                if (event.reminder_flag == 1) {
                    $('#js-reminder-check').prop('checked', true);
                    $('#js-reminder-select').val(event.duration);
                    $('#js-reminder-box').show();
                }

                var event_category = '';

                // Reset categories
                switch (e.category) {
                    case 'interview':
                        event_category = 'In-Person Interview';
                        break;
                    case 'interview-phone':
                        event_category = 'Phone Interview';
                        break;
                    case 'interview-voip':
                        event_category = 'VOIP Interview';
                        break;
                    case 'training-session':
                        event_category = 'Training Session';
                        break;
                    case 'other':
                        event_category = 'Other';
                        break;
                    default:
                        event_category = event.category_uc.replace(/-/g, ' ');
                        break;
                }
                //
                if (is_personal_check === false || e.category == 'other') {
                    $('.js-active-category').text(event_category);
                    $('.js-selected-event-category').val(e.category);
                    $('.js-category-dropdown').css({
                        'background-color': event_color_obj[e.category],
                        'color': '#fff'
                    });
                    //
                    $('#js-event-date').val(moment(event.date).format(site_date_format + " dddd"));
                    //
                    $('#js-event-start-time').val(event.event_start_time);
                    //
                    $('#js-event-end-time').val(event.event_end_time);
                    //
                    if (event.message_check == 1) {
                        $('#js-message-check').prop('checked', true);
                        $('#js-message-subject').val(event.subject);
                        $('#js-message-body').val(event.message);
                        $('#js-message-box').show();
                    }
                    //
                    $('#js-address-text').val(event.address);
                    //
                    if (event.meeting_check == 1) {
                        $('#js-meeting-check').prop('checked', true);
                        $('#js-meeting-id').val(event.meeting_id);
                        $('#js-meeting-url').val(event.meeting_url);
                        $('#js-meeting-call').val(event.meeting_call_number);
                        $('#js-meeting-box').show();
                    }
                }

                <?php if (check_access_permissions_for_view($security_details, 'application_tracking')) { ?>
                    if (event.event_status == 'confirmed') {
                        $('#cancelled_message').hide();
                        $('#js-update').show();
                        $('#js-cancel').show();
                        $('#js-delete').show();
                        $('#js-reschedule').hide();
                    } else if (event.event_status == 'pending') {
                        $('#cancelled_message').hide();
                        $('#js-update').show();
                        $('#js-cancel').show();
                        $('#js-delete').show();
                        $('#js-reschedule').hide();
                    } else if (event.event_status == 'cancelled') {
                        $('#cancelled_message').show();
                        $('#js-reschedule').show();
                    } else {
                        $('#cancelled_message').show();
                    }
                <?php } else { ?>
                    if (event.employers_sid == "<?= $employer_id; ?>") {
                        if (event.event_status == 'confirmed') {
                            $('#cancelled_message').hide();
                            $('#js-update').show();
                            $('#js-cancel').show();
                            $('#js-delete').show();
                            $('#js-close').show();
                        } else if (event.event_status == 'pending') {
                            $('#cancelled_message').hide();
                            $('#js-update').show();
                            $('#js-cancel').show();
                            $('#js-delete').show();
                            $('#js-reschedule').hide();
                        } else if (event.event_status == 'cancelled') {
                            $('#cancelled_message').show();
                            $('#save').hide();
                            $('#js-close').show();
                            $('#js-reschedule').show();
                        } else {
                            $('#cancelled_message').show();
                        }
                    } else {
                        $('#cancelled_message').hide();
                        $('#js-close').show();
                    }
                <?php } ?>

                var sent_reminder_check = true;
                var user_details;

                // check for expired 
                // events
                $('#cancelled_message').html('Event cancelled!!').hide(0);
                if (
                    moment(event.date + ' 23:59:00') <
                    moment().utc()
                ) {
                    $('#cancelled_message').html('Event expired!!').show();
                    $('#js-update').hide();
                    $('#js-cancel').hide();
                    $('#js-delete').hide();
                    $('#js-reschedule').show();
                    $('#js-close').show();
                    is_event_expired = true;
                }

                //
                event.event_status = event.status = e.status;

                if (event.event_status == 'cancelled') sent_reminder_check = false;

                if (personal_status === false || (e.category == 'training-session' || e.category == 'other')) {
                    //
                    if (event.external_participants.length > 0)
                        generate_extra_interviewers(event.external_participants);
                    //
                    var interviewer_ids = [];
                    if (event.interviewers_details.length > 0) {
                        $.each(event.interviewers_details, function(i, v) {
                            //
                            interviewer_ids.push(v.id);
                            select_interviewer(
                                v,
                                ((event.interviewer_show_email == '' || event.interviewer_show_email == null) ? [] : event.interviewer_show_email.split(','))
                            );
                        });
                    } else {
                        selected_interviewers.push(<?= $employer_id; ?>);
                        interviewer_ids.push(<?= $employer_id; ?>);
                        //
                        select_interviewer({
                            id: "<?= $employer_id; ?>",
                            label: get_ie_obj(<?= $employer_id; ?>)['full_name'],
                            employer_type: get_ie_obj(<?= $employer_id; ?>)['employer_type']
                        });
                    }

                    selected_interviewers = interviewers_list_array = interviewer_ids;
                    $('#js-interviewers-select').select2('val', interviewer_ids);

                    if (event.users_type != 'personal') {
                        user_details = {
                            email_address: (event.users_type == 'applicant' ? event.applicant_details.email_address : event.employer_details.email_address),
                            value: (event.users_type == 'applicant' ? event.applicant_details.value : event.employer_details.value),
                            id: (event.users_type == 'applicant' ? event.applicant_details.id : event.employer_details.id),
                            type: event.users_type,
                            timezone: (event.users_type == 'applicant' ? event.event_timezone : event.employer_details.timezone),
                        }
                    }

                    if (event.users_type == 'personal' && event_category == 'Call') {
                        user_details = {
                            email_address: event.users_email,
                            value: event.users_name,
                            id: 0,
                            type: 'person'
                        }
                        sent_reminder_check = true;
                    }

                    console.log(is_event_expired);
                    console.log(sent_reminder_check);

                    if (is_event_expired === false && sent_reminder_check === true) {

                        if (is_event_expired === false) {
                            //
                            $('#js-reminder-email-check').show(0).prop('checked', false);
                            $('#js-reminder-email-wrap').show();
                        }

                        // When event is expired
                        generate_reminder_email_rows(
                            event.interviewers_details,
                            event.external_participants,
                            user_details
                        );
                    } else {
                        $('#js-reminder-email-wrap').hide(0);
                    }
                }

                // Disable date picker when 
                // event is expired
                if (is_event_expired === true) {
                    $('.datepicker').datepicker('disable');
                    $('#js-event-start-time').prop('disabled', true);
                    $('#js-event-end-time').prop('disabled', true);
                }

                if (event.users_type == 'personal') $('#js-reminder-email-wrap').hide(0);

                if (is_event_expired) $('#js-reschedule').prop('id', 'js-expired-reschedule');

                // Add history and clone buttons
                load_extra_buttons(event.parent_event_sid, event.event_history_count, event.event_reminder_email_history, event.event_change_history);

                // Check for Recur
                if (show_recur_btn === 0 && event.is_recur == 1) {
                    var recur_obj = {};
                    recur_obj.end_date = event.recur_end_date == null ? default_end_date : moment(event.recur_end_date, site_date_format).format(site_date_format);
                    var list = JSON.parse(event.recur_list);
                    var type = event.recur_type;

                    switch (event.recur_type) {
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

                    $('#js-reoccur-check').prop('checked', true);
                    $('#js-reoccur-box').show(0);
                    load_recurr_view(event.recur_type.toLowerCase(), recur_obj);
                    if (event.recur_end_date == null)
                        $('.js-infinite').prop('checked', true);
                }

                // Learning Center check
                if ((event.users_type == 'employee' && event.category_uc == 'Training-session') ||
                    event.users_type == 'personal' && event.category_uc == 'Training-session') {
                    // load view
                    $('#js-employee-box, .js-employee-hr').hide(0);
                    $('#js-interviewers-box').hide(0);
                    $('label#attendees_label').text('Assigned Attendees');
                    $('#js-non-employee-heading').text('Assigned non-employee Attendees');
                    $('.js-comment-box, .js-message-wrap, #js-message-box, .js-meeting-wrap, #js-meeting-box').hide(0);
                    $('.js-message-hr').hide(0);
                    $('.js-comment-hr').hide(0);
                    <?php if ($calendar_opt['show_online_videos'] == 1) { ?>
                        $('#js-video-wrap, .js-video-hr').show(0);
                    <?php } ?>

                    $('#js-lcts-id').val(event.learning_center_training_sessions);

                    append_all_to_interviewers((event.interviewer != null && (event.interviewer.split(',').length == employee_list.length)) ? undefined : false);

                    if (event.online_video_sid != 'null' && event.online_video_sid != null)
                        $('#js-video-wrap select').select2('val', event.online_video_sid.split(','));
                    $('.js-interviewers-wrap').show(0);

                    // $('#js-reschedule, #js-expired-reschedule').hide(0);
                }

                func_hide_loader();

                // Blinker check
                if (event.event_requests != 0) {
                    $('.js-status-history-btn').addClass('js-status-btn-blinker');
                    $('.fc-event-' + e.event_id + '').addClass('fc-event-blink');
                    event_blinker('button');
                }

                //
                if (event.users_type == 'personal' && event_category == 'Call' && event.users_email != '' && event.users_email != null) {
                    $('.js-person-email-check-input').prop('checked', true);
                    $('.js-person-email input').val(event.users_email);
                    $('.js-person-email').show();
                }


                if (event.users_type == 'personal' && event_category == 'Call' && event.users_email != '' && event.users_email != null) {
                    user_details = {
                        email_address: event.users_email,
                        value: event.users_name,
                        id: 0,
                        type: 'person'
                    }
                    sent_reminder_check = true;
                    $('#js-reminder-email-check').show(0).prop('checked', false);
                    $('#js-reminder-email-wrap').show();
                    // When event is expired
                    generate_reminder_email_rows(
                        event.interviewers_details,
                        event.external_participants,
                        user_details
                    );
                }
                //
                $('#loader').hide();
                $('.loader-text').text(default_loader_text);
                $('.btn').removeClass('disabled');
            });
        }

        // Event modal resets
        function reset_modal() {
            drag_event = current_edit_event_details = {};
            default_start_time = "<?= $calendar_opt['default_start_time']; ?>";
            default_end_time = "<?= $calendar_opt['default_end_time']; ?>";
            show_reschedule_box = 1;
            $('.js-event-head-title').text(event_title_text);
            $('#js-interviewers-list').html('');
            selected_employer_id = 0;
            selected_interviewers = interviewers_list_array = [];
            interviewers_list_array.push("<?= $employer_id; ?>");
            selected_interviewers.push("<?= $employer_id; ?>");
            reset_modal_view();
            $('#js-interviewers-select option').prop('disabled', false);
            // select2 binders
            // $('#js-interviewers').val('');
            $('#js-interviewers-select').select2();
            $('#js-interviewers-select').select2('val', '');
            $('#js-interviewers-select').select2('val', <?= $employer_id; ?>);
            $('#cancelled_message').html('Event Cancelled!!').hide(0);
            <?php if (strtolower($access_level) != 'employee') { ?>
                // Applicant area
                $('#js-user-type-applicant').prop('checked', true);
                $('#js-applicant-search-bar').show(0);
                $('#js-applicant-box').show(0);
                $('#js-applicant-job-box').hide(0);
                $('#js-applicant-job-list').html('');
                $('#js-applicant-input').val('');
                // Employee area
                $('#js-user-type-employee').prop('checked', false);
                // For ajax
                // $('#js/-employee-search-bar').hide(0);
                $('#js-employee-box').hide(0);
                // $('#js-employee-select').trigger({ type: 'select2:select', params: {data: $('#js-employee-select').val()}});
                // For ajax
                // $('#js-employee-input').val('');
            <?php } else { ?>
                // Employee area
                $('#js-user-type-employee').prop('checked', true);
                $('#js-employee-box').show(0);
            <?php } ?>
            //
            $('#js-event-title, #js-event-title-p').val('');
            //
            load_categories();
            //
            $('#js-event-date').val();
            //
            $('#js-event-start-time').val('');
            //
            $('#js-event-end-time').val('');
            //
            $('#js-interviewers').html('');
            $('#js-interviewers-list').html('');
            $('#js-interviewers-box').show(0);
            //
            $('#js-extra-interviewers-box').html(get_extra_interviewer_row());
            //
            $('#js-comment-check').prop('checked', false);
            $('#js-comment-msg-box').hide(0);
            $('#js-comment-msg').val('');
            //
            $('#js-message-check').prop('checked', false);
            $('#js-message-box').hide(0);
            $('#js-message-subject').val('');
            $('#js-message-body').val('');
            //
            $('#js-address-input').prop('checked', true);
            $('#js-address-select').prop('checked', false);
            $('#js-address-list').html('');
            $('#js-address-text').val('');
            $('#js-address-select-box').hide(0);
            $('#js-address-input-box').show(0);
            //
            $('#js-meeting-check').prop('checked', false);
            $('#js-meeting-box').hide(0);
            $('#js-meeting-call').val('');
            $('#js-meeting-id').val('');
            $('#js-meeting-url').val('');
            //
            $('#js-reminder-check').prop('checked', false);
            $('#js-reminder-box').hide(0);
            //
            $('.js-modal-btn').hide(0);
            $('#js-applicant-search-bar').val('');
            $('#js-applicant-phone').val('');
            // For ajax
            // $('#js-employee-search-bar').val('');
            $('#js-employee-phone').val('');
            $('#js-reminder-email-wrap').hide(0);
            $('#js-reminder-email-check').hide(0).prop('checked', false);
            $('#js-reminder-email-box').hide(0).html('');
            //
            $('#js-event-id').val('');
            $('.js-person-name').find('input').val('');
            $('.js-person-phone').find('input').val('');
            $('.js-person-email').find('input').val('');
            $('.js-selected-event-category-p').val('');
            $('#js-expired-reschedule').prop('id', 'js-reschedule');
            // Flush the HTML of buttons
            extra_btns_wrap.html('');
            // Clear HTML of event history table
            // and hide it
            request_page_REF.hide(0).find('tbody').html('');
            main_page_REF.show(0);
            reschedule_page_REF.hide(0);
            recur_page_REF.hide(0);
            reminder_page_REF.hide(0);
            change_history_page_REF.hide(0);
            // Remove clone span
            $('.modal-header h4 span').remove();
            //
            $('.js-clone-start-time').val(default_start_time);
            $('.js-clone-end-time').val(default_end_time);
            //
            last_selected_category = selected_personal_category = selected_user_category = selected_user_type = null;
            //
            $('#js-applicant-profile-link').addClass('disabled');
            $('#js-employee-profile-link').addClass('disabled');
            $('#js-reschedule-page').hide(0);
            //
            $('.datepicker').datepicker('enable');
            $('#js-event-start-time').prop('disabled', false);
            $('#js-event-end-time').prop('disabled', false);
            //
            $('#js-reschedule-event-date').val(moment().utc().format(site_date_format));
            //
            $('.js-recurr-type').prop('checked', false);
            $('#js-reoccur-check').prop('checked', false);
            $('#js-recurr-daily-check').prop('checked', true);
            $('#js-reoccur-box').hide(0);
            if (show_recur_btn === 0) $('#js-reoccur-wrap').hide(0);

            $('#js-employee-select').select2('val', '');

            $('.js-search-loader-applicant').hide(0);
            // remove_all_from_interviewers()
            var vals = $('#js-interviewers-select').val();
            if (vals != null) {
                vals.remove('all');
                $('#js-interviewers-select option[value="all"]').remove();
            }

            $('#js-video-wrap select').select2();
            $('#js-video-wrap, .js-video-hr').hide(0);
            $('#js-video-wrap, #js-video-wrap select, .js-video-hr').hide(0);
            $('#js-lcts-id').val('');

            $('label#attendees_label').text(default_interviewer_text);
            $('#js-non-employee-heading').text(default_einterviewer_text);

            $('.js-comment-text > span').html('Comment for Interviewers(s)');
            $('#message_to_label').html('Applicant');

            //
            if ($('#js-user-type-employee').prop('checked') === true) {
                $('label#attendees_label').text('Participant(s)');
                $('#js-non-employee-heading').text('Non-employee Participant(s)');

                $('.js-comment-text > span').html('Comment for Participant(s)');
                $('#message_to_label').html('Employee');
            }

            $('.js-comment-box, .js-comment-hr').show(0);


            // For applicant
            // var val = fpn($('#js-applicant-phone').val().trim());
            // $('#js-applicant-phone').val(typeof(val) === 'object'  ? val.number : val);
            // // For employee
            // val = fpn($('#js-employee-phone').val().trim());
            // $('#js-employee-phone').val(typeof(val) === 'object'  ? val.number : val);
            // // For personal
            // val = fpn($('.js-person-phone input').val().trim());
            // $('.js-person-phone input').val(typeof(val) === 'object'  ? val.number : val);

            //
            $('.js-person-email-check').hide(0);
            $('.js-person-email-check-input').prop('checked', false);

        }

        // Loads event process
        // when day or mode is changed
        // @param e
        // Contains the information
        // of current event
        function event_process(e) {
            clearInterval(blink_interval);
            blink_interval = null;
            // show loader
            func_show_loader();
            // get view
            switch (e.name) {
                case 'agendaWeek':
                    calendar_OBJ.current.type = 'week';
                    break;
                case 'agendaDay':
                    calendar_OBJ.current.type = 'day';
                    break;
                default:
                    calendar_OBJ.current.type = 'month';
            }
            // set start and end year year
            calendar_OBJ.current.start_date = e.start.format('Y-MM-DD');
            calendar_OBJ.current.end_date = e.end.format('Y-MM-DD');
            // for start day to end day
            calendar_OBJ.current.week_start = moment(e.start).format('MM-DD');
            calendar_OBJ.current.week_end = moment(e.end).format('MM-DD');
            //
            default_start_time = "<?= $calendar_opt['default_start_time']; ?>";
            default_end_time = "<?= $calendar_opt['default_end_time']; ?>";
            // remove last evenets
            calendar_ref.fullCalendar('removeEventSource', calendar_OBJ.records);
            // flush re-render events
            remove_event_from_calendar(0, 'all');
            // set current date
            calendar_OBJ.current.date_str = moment(e.intervalStart, site_date_format).format(site2_date_format);
            calendar_OBJ.current.date_array = calendar_OBJ.current.date_str.split('-');
            console.log()
            // Check for incoming 
            if (i_triggered === true) {
                calendar_ref.fullCalendar('gotoDate', i_event_array.event_date);
            }
            // fetch records
            fetch_events_by_date(calendar_OBJ.current.date_array, function(resp) {
                // check for redirect
                if (resp.Status === false && resp.Redirect === true) location.reload();
                // check for records
                if (resp.Status === false) {
                    func_hide_loader();
                    return false;
                }
                // save latest records
                calendar_OBJ.records = resp.Events;
                // load events
                calendar_ref.fullCalendar('addEventSource', calendar_OBJ.records);
                calendar_ref.fullCalendar('render');
                func_hide_loader();
            });
        }

        var ob = {};
        ob['goals'] = 1;
        ob['timeoff'] = 1;
        ob['shifts'] = 1;


        $('.jsCalendarTypes').click(function() {
            if ($(this).prop('checked') === true) {
                ob[$(this).attr('name')] = 1;
            } else {
                ob[$(this).attr('name')] = 0;
            }
            calendar_ref.fullCalendar('rerenderEvents');
        });

        // Fetch events according to modes
        // @param date_array
        // Holds the date
        // @param cb
        // Trigger the callback-function 
        // on completion
        function fetch_events_by_date(date_array, cb) {
            // abort previous event 
            // when new event is called
            if (eventsXHR !== null) eventsXHR.abort();
            //
            eventsXHR = $.post("<?= base_url(); ?>calendar/get-events", {
                type: calendar_OBJ.current.type,
                year: date_array[0],
                month: date_array[1],
                day: date_array[2],
                start_date: calendar_OBJ.current.start_date || null,
                end_date: calendar_OBJ.current.end_date || null,
                week_start: calendar_OBJ.current.week_start || null,
                week_end: calendar_OBJ.current.week_end || null,
                company_id: "<?= $company_id; ?>",
                employer_id: "<?= $employer_id; ?>"
            }, cb);
        }

        // Re-render the event
        // also updates the info of event
        // triggers when an event is updated
        // @param new_event
        // Holds the info. of updated event
        // @param prev_event
        // Holds the old info. of updated event
        function event_update_info(new_event, prev_event) {
            //
            prev_event = prev_event[0];

            prev_event.category = new_event.category;
            prev_event.end = prev_event.start = prev_event.date = new Date(moment(new_event.date, site_date_format).format(site2_date_format));
            prev_event.employee_sid = new_event.employee_sid;
            prev_event.eventendtime = new_event.eventendtime;
            prev_event.eventstarttime = new_event.eventstarttime;
            prev_event.color = event_color_obj[prev_event.category];
            //
            if (new_event.color !== undefined) prev_event.color = new_event.color;
            if (new_event.editable !== undefined) prev_event.editable = new_event.editable;

            prev_event.eventstarttime = prev_event.start = moment(new_event.date, site_date_format).format(site2_date_format) + ' ' + convert_12_to24(prev_event.eventstarttime);
            prev_event.eventendtime = prev_event.end = moment(new_event.date, site_date_format).format(site2_date_format) + ' ' + convert_12_to24(prev_event.eventendtime);

            //
            prev_event.start = moment(prev_event.eventstarttime);
            prev_event.end = moment(prev_event.eventendtime);

            if (new_event.action == 'reschedule_event') {
                calendar_ref.find(prev_event._id).switchClass('fc-event-cc-' + prev_event.status + '', 'fc-event-cc-confirmed');
                prev_event.event_status = prev_event.status = prev_event.last_status;
                // prev_event.status = 'confirmed';
            }

            //
            var title_name = '';
            // For AJAX
            // title_name += $('#js-'+( new_event.users_type != 'employee' ? 'applicant' : 'employee' )+'-input').val().replace(/ *\([^)]*\) */g, '');
            // //
            // if( new_event.users_type == 'applicant')
            //     title_name += $('#js-'+( new_event.users_type != 'employee' ? 'applicant' : 'employee' )+'-input').val().replace(/ *\([^)]*\) */g, '');
            // else
            //     title_name += get_ie_obj(new_event.applicant_sid)['full_name'];

            if (new_event.users_type != 'personal') {
                if (new_event.users_type == 'applicant')
                    title_name += $('#js-' + (new_event.users_type != 'employee' ? 'applicant' : 'employee') + '-input').val().replace(/ *\([^)]*\) */g, '');
                else
                    title_name += get_ie_obj(new_event.applicant_sid)['full_name'];
                var category = prev_event.category.charAt(0).toUpperCase() + prev_event.category.slice(1);
                category.replace('-', ' ');
                title_name += ' - ' + category;
            } else
                title_name += 'Personal Appointment';

            // title_name += ', '+$(new_event.users_type == 'personal' ? '.js-active-category-p' : '#js-active-category').text();
            title_name += ', ' + new_event.title;
            title_name += ', Interviewers(s): ';

            if (interviewers_list_name_array.length > 0) {
                title_name += interviewers_list_name_array.toString();
            }

            prev_event.title = title_name;
            // prev_event.event_status = 'confirmed';
            if (new_event.interviewer != null && new_event.interviewer != '' && new_event.interviewer != undefined) {
                if (new_event.interviewer.length == 0) {
                    new_event.interviewer.push("<?= $employer_id; ?>");
                    new_event.interviewer_show_email = "<?= $employer_id; ?>";
                    // interviewers_list_name_array.push(item.label);                
                }
            }

            prev_event.interviewer = new_event.interviewer;
            prev_event.interviewer_show_email = new_event.interviewer_show_email;

            prev_event.is_recur = 0;

            // Handle Recur event
            var recur = JSON.parse(new_event.recur == '' ? '{}' : new_event.recur);
            //
            if (recur.hasOwnProperty('list')) {
                prev_event.is_recur = 1;
                prev_event.recur_type = recur.recur_type;
                prev_event.recur_start_date = recur.recur_start_date;
                prev_event.recur_end_date = recur.recur_end_date;
                prev_event.recur_list = JSON.stringify(recur.list);

                // prev_event.dow = recur.list.Days;
            }
            // Added on: 02-05-2019
            prev_event.status = prev_event.event_status = prev_event.last_status;

            // updates event on calendar
            calendar_ref.fullCalendar('updateEvent', prev_event);
            $('#js-event-modal').modal('hide');
            $('body').removeClass('ajs-no-overflow');
            // calendar_ref.fullCalendar('rerender');
        }

        // Re-render the event
        // triggers when a new event is added
        // @param new_event
        // Holds the info. of new event
        // @param event_id
        // event id for new event
        function event_save_info(new_event, event_id) {
            //
            var prev_event = {},
                title_name = '',
                is_editable = true;

            prev_event.requests = '0';
            prev_event.category = new_event.category;
            // prev_event.category = $(new_event.users_type == 'personal' ? '.js-selected-event-category-p': '#js-selected-event-category').val();
            prev_event.end = prev_event.start = prev_event.date = moment(moment(new_event.date, site_date_format).format(site2_date_format));
            prev_event.employee_sid = new_event.employee_sid;
            prev_event.eventendtime = new_event.eventendtime;
            prev_event.eventstarttime = new_event.eventstarttime;
            prev_event.color = event_color_obj[new_event.category];
            // prev_event.status = 'confirmed';

            if (moment(new_event.date + ' 23:59:59') < moment().utc()) {
                // is_editable = false;
                calendar_ref.find(event._id).switchClass('fc-event-cc-' + event.status + '', 'fc-event-cc-expired');
                // prev_event.status = 'expired';
                prev_event.expired_status = true;
            }
            //
            // For AJAX
            // title_name += $('#js-'+( new_event.users_type != 'employee' ? 'applicant' : 'employee' )+'-input').val().replace(/ *\([^)]*\) */g, '');
            //
            if (new_event.users_type != 'personal') {
                if (new_event.users_type == 'applicant')
                    title_name += $('#js-' + (new_event.users_type != 'employee' ? 'applicant' : 'employee') + '-input').val().replace(/ *\([^)]*\) */g, '');
                else
                    title_name += get_ie_obj(new_event.applicant_sid)['full_name'];
                var category = prev_event.category.charAt(0).toUpperCase() + prev_event.category.slice(1);
                category.replace('-', ' ');
                var category = prev_event.category.charAt(0).toUpperCase() + prev_event.category.slice(1);
                category.replace('-', ' ');
                title_name += ' - ' + category;
            } else
                title_name += 'Personal Appointment';

            // title_name += ' - '+$(new_event.users_type == 'personal' ? '.js-active-category-p' : '#js-active-category').text();
            title_name += ', ' + new_event.title;
            title_name += ', Interviewers(s): ';
            //
            if (interviewers_list_name_array.length > 0) title_name += interviewers_list_name_array.toString();
            //    
            prev_event.title = title_name;
            // prev_event.event_status = 'confirmed';
            if (new_event.interviewer != null) {
                if (new_event.interviewer.length == 0) {
                    new_event.interviewer.push("<?= $employer_id; ?>");
                    new_event.interviewer_show_email = "<?= $employer_id; ?>";
                }
            }
            //
            prev_event.interviewer = new_event.interviewer;
            prev_event.interviewer_show_email = new_event.interviewer_show_email;
            //
            prev_event.event_id = event_id;
            prev_event.editable = is_editable;
            // Added on: 06-05-2019
            prev_event.status = prev_event.event_status = prev_event.last_status = 'pending';
            // prev_event.event_status = 'confirmed';

            prev_event.eventstarttime = moment(new_event.date, site_date_format).format(site2_date_format) + ' ' + convert_12_to24(prev_event.eventstarttime);
            prev_event.eventendtime = moment(new_event.date, site_date_format).format(site2_date_format) + ' ' + convert_12_to24(prev_event.eventendtime);
            //
            prev_event.start = moment(prev_event.eventstarttime);
            prev_event.end = moment(prev_event.eventendtime);
            prev_event.is_recur = 0;
            // Handle Recur event
            var recur = JSON.parse(new_event.recur);
            //
            if (recur.hasOwnProperty('list')) {
                prev_event.is_recur = 1;
                prev_event.recur_type = recur.recur_type;
                prev_event.recur_start_date = recur.recur_start_date;
                prev_event.recur_end_date = recur.recur_end_date;
                prev_event.recur_list = JSON.stringify(recur.list);
                prev_event.dow = recur.list.Days;
            }

            // updates event on calendar
            calendar_ref.fullCalendar('renderEvent', prev_event);
            $('#js-event-modal').modal('hide');
            $('body').removeClass('ajs-no-overflow');
        }

        // Remove the event from calendar
        // @param event_id
        // event id for event
        function remove_event(event_id) {
            $.each(calendar_ref.fullCalendar('clientEvents'), function(i, v) {
                if (v.event_id == event_id) {
                    calendar_ref.fullCalendar('removeEvents', v._id);
                    return false;
                }
            });
            $('#js-event-modal').modal('hide');
            $('body').removeClass('ajs-no-overflow');
        }

        // Cancel the event
        // @param event_id
        // event id for event
        function cancel_event(event_id) {
            $.each(calendar_ref.fullCalendar('clientEvents'), function(i, v) {
                if (v.event_id == event_id) {
                    calendar_ref.find(v._id).switchClass('fc-event-cc-' + v.status + '', 'fc-event-cc-cancelled');
                    v.status = 'cancelled';
                    calendar_ref.fullCalendar('updateEvent', v);
                    return false;
                }
            });
            $('#js-event-modal').modal('hide');
            $('body').removeClass('ajs-no-overflow');
        }

        // Create extra interviewer
        // row
        function get_extra_interviewer_row() {
            var row = '';
            row += '<div id="external_participant_0" class="row external_participants">';
            row += '    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">';
            row += '        <div class="form-group">';
            row += '            <label>Name</label>';
            row += '            <input name="ext_participants[0][name]" id="ext_participants_0_name" type="text" class="form-control external_participants_name" />';
            row += '        </div>';
            row += '    </div>';
            row += '    <div class="col-lg-5 col-md-5 col-xs-12 col-sm-6">';
            row += '        <div class="form-group">';
            row += '            <label>Email</label>';
            row += '            <input name="ext_participants[0][email]" id="ext_participants_0_email" type="email" class="form-control external_participants_email" />';
            row += '        </div>';
            row += '    </div>';
            row += '    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">';
            row += '        <div class="form-group">';
            row += '            <label class="control control--checkbox margin-top-20">';
            row += '                Show Email';
            row += '                <input name="ext_participants[0][show_email]" id="ext_participants_0_show_email" class="external_participants_show_email" value="1"  type="checkbox" />';
            row += '                <div class="control__indicator"></div>';
            row += '            </label>';
            row += '        </div>';
            row += '    </div>';
            row += '    <div class="col-lg-1 col-md-1 col-xs-12 col-sm-1">';
            row += '        <button id="btn_add_participant" type="button" class="btn btn-success btn-equalizer btn-block margin-top-20"><i class="fa fa-plus-square"></i></button>';
            row += '    </div>';
            row += '</div>';
            return row;
        }

        // Get searched applicants via ajax
        // @param req
        // Holds the request function
        // reference of autocomplete
        // @param res
        // Holds the response function
        // reference of autocomplete
        function get_applicant(req, res) {
            $('.js-search-loader-applicant').show(0);
            //
            if (applicantXHR !== null) applicantXHR.abort();

            applicantXHR = $.get("<?= base_url(); ?>calendar/get-applicant/" + req.term + "", function(resp) {
                if (!resp) {
                    $('.js-search-loader-applicant').hide(0);
                    return false;
                }
                // for(i =0;i<resp.length;i++)
                //     resp[i]['value'] =  resp[i]['value']+' (<?= $company_timezone ?>)';
                applicant_list_array = resp;
                res(resp);
                applicantXHR = null;
                $('.js-search-loader-applicant').hide(0);
            });
        }

        // Get searched employees via ajax
        // For AJAX
        // @param req
        // Holds the request function
        // reference of autocomplete
        // @param res
        // Holds the response function
        // reference of autocomplete
        // function get_employees(req, res){
        //     //
        //     if(employeeXHR !== null) employeeXHR.abort();
        //     employeeXHR = $.get("<?= base_url(); ?>calendar/get-employees/"+ req.term +"", function(resp){
        //         if(!resp) return false;
        //         employee_list_array = resp;
        //         res(resp);
        //         employeeXHR = null;
        //     });
        // }

        // Get searched interviewers via ajax
        // For AJAX
        // @param req
        // Holds the request function
        // reference of autocomplete
        // @param res
        // Holds the response function
        // reference of autocomplete
        // function get_interviewers(req, res){
        //     //
        //     if(interviewerXHR !== null) interviewerXHR.abort();
        //     interviewerXHR = $.post("<?= base_url(); ?>calendar/get-interviewers/"+ req.term +"", {
        //         ids: _.concat(interviewers_list_array, tmp).toString()
        //     }, function(resp){
        //         if(!resp) return false;
        //         res(resp);
        //         interviewerXHR = null;
        //     });
        // }

        // Get company addresses via ajax
        function get_address() {
            if (address_list_array.length > 0) {
                $('#js-address-list').html(address_list_array.map(function(v) {
                    return '<option value="' + v + '">' + v + '</option>';
                }));
                return true;
            }
            $('#loader').show();
            $('.btn').addClass('disabled');
            $('.btn').prop('disabled', true);
            //
            if (addressXHR !== null) addressXHR.abort();

            addressXHR = $.get("<?= base_url(); ?>calendar/get-address/", function(resp) {
                address_list_array = resp.Address;
                $('#js-address-list').html(resp.Address.map(function(v) {
                    return '<option value="' + v + '">' + v + '</option>';
                }));

                $('#loader').hide();
                $('.btn').removeClass('disabled');
                $('.btn').prop('disabled', false);

                addressXHR = null;
            });
        }

        // Append data to fields for selected applicant
        // Event triggers when applicant is selected
        // @param id
        // Holds the id of applicant
        // @param name
        // Holds the name of applicant
        // @param phone_number
        // Holds the phone_number of applicant
        // @param jid
        // Holds the job id of applicant
        // @param jobs
        // Holds the job list of applicant
        function select_applicant(id, name, phone_number, jid, jobs) {
            $('#js-applicant-input').val(name);
            $('#js-applicant-input').data('id', id);
            $('#js-applicant-input').data('jid', jid);
            // var tmp = fpn(phone_number);
            // phone_number = typeof(tmp) === 'object' ? tmp.number : tmp;
            // $('#js-applicant-phone').val(phone_number);

            //
            if (jobs.length === 0) return false;
            var rows = '';
            $.each(jobs, function(i, v) {
                if (v.job_title == null) return true;
                rows += '<div class="col-xs-6">';
                rows += '   <label class="control control--checkbox">';
                rows += v.job_title;
                rows += '       <input id="enable_disable_jobs" value="' + v.list_sid + '" name="applicant_emails[]" type="checkbox" />';
                rows += '       <div class="control__indicator"></div>';
                rows += '   </label>';
                rows += '</div>';
            });
            //
            if (rows == '') {
                $('#js-applicant-job-box').hide();
                $('#js-applicant-job-list').html('');
                return false;
            }
            //
            $('#js-applicant-job-list').html(rows);
            $('#js-applicant-job-box').show();
            $('#js-applicant-profile-link').removeClass('disabled')
        }

        // Append data to fields for selected employee
        // Event triggers when employee is selected
        // For AJAX
        // @param id
        // Holds the id of employee
        // @param name
        // Holds the name of employee
        // @param phone_number
        // Holds the phone_number of employee
        // function select_employee(id, name, phone_number){
        //     $('#js-employee-input').val(name);
        //     $('#js-employee-input').data('id', id);
        //     $('#js-employee-phone').val(phone_number);
        //     tmp = [];tmp.push(id);
        //     if(interviewers_list_array.length == 0) return false;
        //     // check if there is an overwrite
        //     $.each(interviewers_list_array, function(i,v){
        //         if(v == id){
        //             interviewers_list_array.splice(i,1);
        //             $('.int_ems_'+ v +'').remove(); return false;
        //         }

        //     });
        // }

        // Create row for selected interviewer
        // Event triggers when interviewer is selected
        // @param item
        // Holds the data of interviewer
        // @param selected_item
        // Holds the ids of checked interviewer
        // Occurs only on update event
        function select_interviewer(item, selected_item) {
            console.log(item);
            if (item.length == 0) return false;
            var selected = 'checked="checked"',
                rows = '';
            if (selected_item !== undefined) {
                selected = '';
                $.each(selected_item, function(i, v) {
                    if (v == item.id) {
                        selected = 'checked="checked"';
                        return false;
                    }
                });
            }
            rows += '<div class="col-xs-6 int_ems_' + item.id + '"">';
            rows += '   <label class="control control--checkbox">';
            if (item.value !== undefined) {
                rows += item.value;

            } else {
                rows += item.label + ' (' + item.employee_type + ')';
            }
            // For AJAX
            // rows += '       <i style="margin-left: 10px;" data-id="'+item.id+'" class="fa fa-close js-delete-interviewer"></i>';
            rows += '       <input value="' + item.id + '" name="interviewers_emails[]" ' + selected + ' type="checkbox" />';
            rows += '       <div class="control__indicator"></div>';
            rows += '   </label>';
            rows += '</div>';
            $('#js-interviewers-list').append(rows);
            //
            if ($('#js-user-type-employee').prop('checked') === true && $('.js-selected-event-category').val() != 'training-session')
                $('#js-interviewers-box').show();
            else if ($('#js-user-type-personal').prop('checked') === true && last_selected_category != 'training-session')
                $('#js-interviewers-box').show();

            interviewers_list_array.push(item.id);
            interviewers_list_name_array.push(item.label);
            // empty the field
            $('#js-interviewers').autocomplete('close').val('');
        }

        // Remove interviewer row from DOM
        function unselect_interviewer(e) {
            $('.int_ems_' + e.id + '').remove();
        }

        // Create row for selected applicant jobs
        // Event triggers when applicant is selected
        // @param jobs
        // Holds the jobs list of applicant
        // @param selected_jobs
        // Holds the ids of checked applicant
        // Occurs only on update event
        function generate_job_list(jobs, selected_jobs) {
            if (jobs.length == 0) return false;
            var rows = '';
            $.each(jobs, function(i, v) {
                var selected = '';
                if (selected_jobs.length > 0) {
                    $.each(selected_jobs, function(i0, v0) {
                        if (v0 == v.list_sid) {
                            selected = 'checked="checked"';
                            return false;
                        }
                    });
                }
                rows += '<div class="col-xs-6">';
                rows += '   <label class="control control--checkbox">';
                rows += v.job_title;
                rows += '       <input id="enable_disable_jobs" value="' + v.list_sid + '" ' + selected + ' name="applicant_emails[]" type="checkbox" />';
                rows += '       <div class="control__indicator"></div>';
                rows += '   </label>';
                rows += '</div>';
            });
            $('#js-applicant-job-list').html(rows);
            $('#js-applicant-job-box').show();
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
                rows += '<div id="external_participant_' + i + '" class="row external_participants">';
                rows += '    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">';
                rows += '        <div class="form-group">';
                rows += '            <label>Name</label>';
                rows += '            <input name="ext_participants[' + i + '][name]" id="ext_participants_' + i + '_name" type="text" class="form-control external_participants_name" value="' + v.name + '" />';
                rows += '        </div>';
                rows += '    </div>';
                rows += '    <div class="col-lg-5 col-md-5 col-xs-12 col-sm-6">';
                rows += '        <div class="form-group">';
                rows += '            <label>Email</label>';
                rows += '            <input name="ext_participants[' + i + '][email]" id="ext_participants_' + i + '_email" type="email" class="form-control external_participants_email" value="' + v.email + '" />';
                rows += '        </div>';
                rows += '    </div>';
                rows += '    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">';
                rows += '        <div class="form-group">';
                rows += '            <label class="control control--checkbox margin-top-20">';
                rows += '                Show Email';
                rows += '                <input name="ext_participants[' + i + '][show_email]" ' + (v.show_email == 1 ? 'checked="checked"' : '') + ' id="ext_participants_' + i + '_show_email" class="external_participants_show_email" value="1"  type="checkbox" />';
                rows += '                <div class="control__indicator"></div>';
                rows += '            </label>';
                rows += '        </div>';
                rows += '    </div>';
                rows += '    <div class="col-lg-1 col-md-1 col-xs-12 col-sm-1">';
                if (i == 0)
                    rows += '<button id="btn_add_participant" type="button" class="btn btn-success btn-equalizer btn-block margin-top-20"><i class="fa fa-plus-square"></i></button>';
                else
                    rows += '<button  type="button" class="btn btn-danger btn-equalizer btn_remove_participant btn-block margin-top-20"><i class="fa fa-plus-square fa-trash" data-id="' + rand_id + '"></i></button>';
                rows += '    </div>';
                rows += '</div>';
            });
            $('#js-extra-interviewers-box').html(rows);
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
                    'show_email': $(show_emails[iCount]).prop('checked') == true ? 1 : 0
                };

                data.push(person_data);
            }

            return JSON.stringify(data);
        }

        // Get selected days
        function get_selected_days() {
            var tmp = [];
            $('.js-day-box.cs-active-day').map(function() {
                tmp.push($(this).data('key'));
            });
            return tmp;
        }

        // Get selected months
        function get_selected_months() {
            var tmp = [];
            $('.js-month-box.cs-active-day').map(function() {
                tmp.push($(this).data('key'));
            });
            return tmp;
        }

        // Saves/Updates the event
        // @param type
        // Holds the type of event
        // 'save' for to add new event
        // 'update' for to update event
        // @param cb
        // Callback-function to trigger after 
        // completion
        function save_update_event(type, cb) {
            if (type == 'update' || (type == 'reschedule' || type == 'expired-reschedule')) {
                // Getting form data by ID to save
                var event_id = $('#js-event-id').val();
            }

            var title = null,
                category = null,
                date = null,
                eventstarttime = null,
                eventendtime = null,
                interviewer = null,
                users_phone = null,
                address_type = null,
                address_saved = null,
                address = null,
                show_email_sids = [],
                show_jobs_sids = [],
                external_participants = func_get_external_users_data(),
                reminder_flag = 0,
                duration = 0;

            var applicant_sid = $('#js-applicant-input').data('id'),
                employee_sid = selected_employer_id,
                // For AJAX
                // employee_sid  = $('#js-employee-input').data('id'),
                users_type = $('.js-users-type:checked').val();
            if ((users_type == 'applicant' || users_type == 'employee')) {
                title = $('#js-event-title').val();
                category = $('.js-selected-event-category').val();
                date = moment($('#js-event-date').val(), site_date_format + " dddd").format(site_date_format);
                eventstarttime = $('#js-event-start-time').val();
                eventendtime = $('#js-event-end-time').val();
                interviewer = $('#js-interviewers-select').val();
                // For AJAX
                // interviewer =  [];
                show_email_sids = [];
                show_jobs_sids = [];
                users_phone = $('#js-' + users_type + '-phone').val();
                address_type = $('.js-address-type:checked').val();
                address_saved = $('#js-address-list').val();
                address_new = $('#js-address-text').val();
                address = address_saved;
                external_participants = func_get_external_users_data();
                reminder_flag = 0;
                duration = 0;
                //
                if (address_type == 'new') address = address_new;

                // For AJAX
                // $('#js-interviewers-list').find('input').each(function () {
                //     interviewer.push($(this).val());
                // });
                //
                if (users_type == 'applicant') {
                    $('#js-applicant-job-list').find('input:checked').each(function() {
                        show_jobs_sids.push($(this).val());
                    });
                    show_jobs_sids = show_jobs_sids.join(',');
                    // employee_sid = applicant_sid;
                } else applicant_sid = employee_sid;

                // Set applicant sid if set to 0
                if (users_type == 'employee' && applicant_sid == 0 && (category == 'call' || category == 'email')) applicant_sid = employee_sid = selected_employer_id = $('#js-employee-select').find(':selected').val();

                //
                $('#js-interviewers-list').find('input:checked').each(function() {
                    show_email_sids.push($(this).val());
                });
                //
                if (interviewer == null) {
                    interviewer = [];
                    interviewer.push("<?= $employer_id; ?>");
                    show_email_sids.push("<?= $employer_id; ?>");
                }
                show_email_sids = show_email_sids.join(',');

                // For training session
                if (category == 'training-session') {
                    applicant_sid = <?= $employer_id; ?>;
                }
                // For meeting and other
                if (category == 'meeting' || category == 'other') {
                    applicant_sid = $('#js-employee-select').val();
                }
            } else {
                title = $('#js-event-title-p').val();
                category = $('.js-selected-event-category-p').val() || last_selected_category;
                date = moment($('.js-datepicker').val(), site_date_format + " dddd").format(site_date_format);
                eventstarttime = $('#js-event-start-time').val();
                // eventstarttime = $('.js-clone-start-time').val();
                eventendtime = $('#js-event-end-time').val();
                // eventendtime = $('.js-clone-end-time').val();

                if (category == 'call') {
                    users_name = $('.js-person-name').find('input').val().trim();
                    users_phone = $('.js-person-phone').find('input').val().trim();
                }

                if (category == 'email') {
                    users_name = $('.js-person-name').find('input').val().trim();
                    users_email = $('.js-person-email').find('input').val().trim();
                }

                if ($('.js-person-email-check-input').prop('checked') === true) {
                    users_email = $('.js-person-email').find('input').val().trim();
                }

                if (category == 'training-session' || category == 'other' || category == 'meeting') {
                    //
                    interviewer = $('#js-interviewers-select').val();
                    $('#js-interviewers-list').find('input:checked').each(function() {
                        show_email_sids.push($(this).val());
                    });
                    //
                    if (interviewer == null) {
                        interviewer = [];
                        interviewer.push("<?= $employer_id; ?>");
                        show_email_sids.push("<?= $employer_id; ?>");
                    }
                    show_email_sids = show_email_sids.join(',');

                    address_type = $('.js-address-type:checked').val();
                    address_saved = $('#js-address-list').val();
                    address_new = $('#js-address-text').val();
                    address = address_saved;
                    if (address_type == 'new') address = address_new;
                }
                if (category == 'other') {
                    title = $('#js-event-title').val();
                    date = moment($('#js-event-date').val(), site_date_format + " dddd").format(site_date_format);
                    eventstarttime = $('#js-event-start-time').val();
                    eventendtime = $('#js-event-end-time').val();
                } else {
                    eventstarttime = $('.js-clone-start-time').val();
                    eventendtime = $('.js-clone-end-time').val();

                }
            }

            if (type == 'expired-reschedule' && show_reschedule_box === 1) {
                main_page_REF.fadeOut(200);
                reschedule_page_REF.fadeIn(200);

                if (moment(date + ' 23:59:59') > moment().utc())
                    $('#js-reschedule-event-date').val($('.datepicker').val());

                $('#js-reschedule-event-start-time').val($('#js-event-start-time').val());
                $('#js-reschedule-event-end-time').val($('#js-event-end-time').val());

                return false;
            }
            if (type == 'expired-reschedule' && show_reschedule_box === 0) {
                date = moment($('#js-reschedule-event-date').val(), site_date_format).format(site_date_format);
                eventstarttime = $('#js-reschedule-event-start-time').val();
                eventendtime = $('#js-reschedule-event-end-time').val();
            }

            // TODO
            // Recurring event
            var recur_obj = {};
            if ($('#js-reoccur-check').prop('checked') === true) {
                recur_obj.recur_type = $('.js-recurr-type:checked').val();
                recur_obj.recur_start_date = moment(date + ' 23:59:59').format(site2_date_format);
                recur_obj.recur_end_date = $('.js-infinite').prop('checked') === false ? moment($('.js-recurr-datepicker').val() + ' 23:59:59', site_date_format).format(site2_date_format) : null;
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
                        recur_obj.list.Weeks = $('#js-recurr-week-input').val().trim();
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
                        recur_obj.list.Weeks = $('#js-recurr-week-input').val().trim();
                        recur_obj.list.Days = get_selected_days();
                        if (recur_obj.list.Days.length === 0) {
                            alertify.alert("Please, select atleast one day", flush_alertify_cb);
                            return false;
                        }
                        break;

                    case 'Yearly':
                        recur_obj.list.Years = $('#js-recurr-year-input').val().trim();
                        recur_obj.list.Months = get_selected_months();
                        if (recur_obj.list.Months.length === 0) {
                            alertify.alert("Please, select atleast one month", flush_alertify_cb);
                            return false;
                        }
                        recur_obj.list.Weeks = $('#js-recurr-week-input').val().trim();
                        recur_obj.list.Days = get_selected_days();
                        if (recur_obj.list.Days.length === 0) {
                            alertify.alert("Please, select atleast one day", flush_alertify_cb);
                            return false;
                        }
                        break;
                }
            }

            recur_obj = JSON.stringify(recur_obj);

            //
            var my_array = {
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
                'applicant_jobs_list': show_jobs_sids,
                'duration': duration,
                'reminder_flag': reminder_flag,
                'recur': recur_obj
            };
            //
            my_array.commentCheck = 0;
            my_array.comment = null;

            my_array.messageCheck = 0;
            my_array.subject = null;
            my_array.message = null;

            my_array.goToMeetingCheck = 0;
            my_array.meetingId = my_array.meetingCallNumber = my_array.meetingURL = 0;

            //
            if (type == 'update') {
                my_array.action = 'update_event';
                my_array.sid = event_id;
            }
            //
            if ((type == 'reschedule' || type == 'expired-reschedule')) {
                my_array.action = 'reschedule_event';
                my_array.sid = event_id;
            }

            if ($('#js-comment-check').is(":checked")) {
                my_array.commentCheck = 1;
                my_array.comment = $('#js-comment-msg').val();
            }

            if ($('#js-reminder-check').prop("checked")) {
                my_array.reminder_flag = 1;
                my_array.duration = $('#js-reminder-select').val();
            }
            //
            if ((users_type == 'applicant' || users_type == 'employee') ||
                (users_type == 'personal' && my_array.category == 'other')) {
                if ($('#js-message-check').is(":checked")) {
                    my_array.messageCheck = 1;
                    my_array.subject = $('#js-message-subject').val();
                    my_array.message = $('#js-message-body').val();
                }

                if ($('#js-meeting-check').is(":checked")) {
                    my_array.goToMeetingCheck = 1;
                    my_array.meetingCallNumber = $('#js-meeting-call').val();
                    my_array.meetingId = $('#js-meeting-id').val();
                    my_array.meetingURL = $('#js-meeting-url').val();
                }
                my_array.event_timezone = $("#event_timezone option:selected").val();
            }
            //
            if ((type == 'reschedule' || type == 'expired-reschedule') && moment().utc() >= moment(my_array.date)) {
                my_array.date = moment().format(site_date_format);
            }

            var form_data = new FormData();

            if (users_type == 'personal') {
                // my_array.action = 'save_personal_event';

                if (my_array.category == 'email') {
                    my_array.users_name = users_name;
                    my_array.users_email = users_email;
                }
                if (my_array.category == 'call') {
                    my_array.users_phone = users_phone;
                    my_array.users_name = users_name;
                }
                //
                if ($('.js-person-email-check-input').prop('checked') === true) my_array.users_email = users_email;
                my_array.event_timezone = $("#event_timezone-personal option:selected").val();
                form_data.append('event_timezone', $("#event_timezone-personal option:selected").val());
            }

            my_array.employee_sid = <?= $employer_id; ?>;
            my_array.employers_sid = <?= $employer_id; ?>;

            // Set video 
            my_array.video_ids = $('#js-video-wrap select').val();

            //
            if (my_array.interviewer == 'all') {
                var tmp = [];
                employee_list.map(function(v) {
                    tmp.push(v.employer_id);
                });
                my_array.interviewer = tmp.join(',');
                my_array.interviewer_show_email = '';
                my_array.interviewer_type = 'all';
            }
            my_array.lcts = $('#js-lcts-id').val();


            if ((users_type == 'applicant' || users_type == 'employee') ||
                (users_type == 'personal' && my_array.category == 'other')) {
                var file_data = $('#js-message-file').prop('files')[0];
                form_data.append('messageFile', file_data);
                my_array.event_timezone = $("#event_timezone option:selected").val();
                form_data.append('event_timezone', $("#event_timezone option:selected").val());
            }

            // Reset date/time to UTC
            my_array.date = moment(my_array.date + ' ' + my_array.eventstarttime, site_date_format + ' hh:mmA').format(site_date_format);
            my_array.eventstarttime = moment(my_array.eventstarttime, 'hh:mmA').format('hh:mmA');
            my_array.eventendtime = moment(my_array.eventendtime, 'hh:mmA').format('hh:mmA');

            // Reset phone
            // if(typeof(my_array.users_phone) !== 'undefined' && my_array.users_phone != null && my_array.users_phone != '' ){
            //     my_array.users_phone = (my_array.users_phone.replace(/\D/g, ''));
            //     my_array.users_phone = '+1'+my_array.users_phone;
            // }

            for (myKey in my_array) {
                form_data.append(myKey, my_array[myKey]);
            }

            $('#loader').show();
            $('.btn').addClass('disabled');
            $('.btn').prop('disabled', true);

            //
            if (type == 'update' || type == 'reschedule') {
                var prev_event = calendar_ref.fullCalendar('clientEvents', function(e) {
                    if (e.event_id == event_id) return e;
                });
            }

            if (moment(date + ' 23:59:59') < moment().utc()) {
                my_array.editable = false;
                my_array.color = event_color_obj['expired'];
            }

            // TODO
            // check applicant sid issue


            if (type == 'update') {
                // Find the difference of
                // prev and new data of
                // current event
                var diff_obj = get_difference_of_event(my_array);
                if (Object.size(diff_obj) == 0) {
                    $('#loader').hide();
                    $('.btn').removeClass('disabled');
                    $('.btn').prop('disabled', false);
                    alertify.confirm('&nbsp;', 'You haven\'t made any changes to the event. Do yo want to close this event modal?', function() {
                        $('#js-event-modal').modal('hide');
                    }, function() {
                        return;
                    }).set('labels', {
                        ok: 'Yes',
                        cancel: 'No'
                    });
                } else {
                    form_data.append('diff', JSON.stringify(diff_obj));
                    make_ajax_request(type, form_data, my_array, prev_event === undefined ? null : prev_event, cb);
                }
                return;
            } else {
                //
                make_ajax_request(type, form_data, my_array, prev_event === undefined ? null : prev_event, cb);
            }

            // /   
        }

        // Hide loader
        function func_hide_loader() {
            $('#my_loader, .file_loader').hide();
        }

        // Show loader
        function func_show_loader() {
            $('#my_loader, .file_loader').show();
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

        // Get difference
        function get_array_difference(a, b) {
            var diff1 = _.difference(a, b),
                diff2 = _.difference(b, a);

            return _.concat(diff1, diff2);
        }

        // Converts 24 hour time to 12 hours
        // @param time
        // Time to be converted, HH:mmA
        function convert_12_to24(time) {
            if (time == null) return false;
            var tmp = time.split(':'),
                hours = parseInt(tmp[0]),
                AMPM = time.substr(-2, time.length),
                minutes = parseInt(tmp[1].substr(0, (tmp[1].length - 2)));
            //
            if (AMPM == "PM" && hours < 12) hours = hours + 12;
            if (AMPM == "AM" && hours == 12) hours = hours - 12;
            var sHours = hours.toString();
            var sMinutes = minutes.toString();
            if (hours < 10) sHours = "0" + sHours;
            if (minutes < 10) sMinutes = "0" + sMinutes;
            return sHours + ":" + sMinutes + ":00";
        }

        // Loads note rows
        function load_notes() {
            var rows = '';
            $.each(event_obj, function(i, v) {
                rows += '<tr>';
                rows += '   <td style="background-color: ' + event_color_obj[i] + '"></td>';
                rows += '   <td><strong>' + v + '</strong></td>';
                rows += '</tr>';
            });
            $('#js-event-colors').html(rows);
            rows = '';
            $.each(event_status_obj, function(i, v) {
                rows += '<tr>';
                rows += '   <td style="background-color: ' + event_color_obj[i] + '"></td>';
                rows += '   <td><strong>' + v + '</strong></td>';
                rows += '</tr>';
            });
            $('#js-event-status').html(rows);
        }

        // Loads categories
        // @param type
        // The user type
        // @param target_OBJ
        // Target el array
        function load_categories(type, target_OBJ) {
            var target = $((target_OBJ === undefined ? target_OBJ : target_OBJ.list) === undefined ? '.js-category-list' : target_OBJ.list),
                rows = '',
                selected = event_type.default_applicant,
                arr = event_type.applicant;
            target.html('');
            //
            switch (type !== undefined ? type : $('.js-users-type').val()) {
                case 'employee':
                    selected = event_type.default_employee;
                    arr = event_type.employee;
                    break;
                case 'personal':
                    selected = event_type.default_personal;
                    arr = event_type.personal;
                    break;
            }
            // Sort array by asc
            arr = _.sortBy(arr);
            var cls = (target_OBJ === undefined ? target_OBJ : target_OBJ.sec) === undefined ? 'js-btn-category' : 'js-btn-category-p';
            $.each(arr, function(i, v) {
                if (v == 'training-session') {
                    <?php if ($this->session->userdata('logged_in')['company_detail']['ems_status']) { ?>
                        rows += '<li><button type="button" data-id="' + v + '" class="' + cls + ' btn btn-default btn-block dropdown-btn cs-event-btn-' + v + ' training-session-tile">' + event_obj[v] + '</button></li>';
                    <?php } ?>
                } else {
                    rows += '<li><button type="button" data-id="' + v + '" class="' + cls + ' btn btn-default btn-block dropdown-btn cs-event-btn-' + v + ' training-session-tile">' + event_obj[v] + '</button></li>';
                }
            });
            //      
            if (target_OBJ !== undefined) selected = target_OBJ.selected;

            $((target_OBJ === undefined ? target_OBJ : target_OBJ.sec) === undefined ? '.js-selected-event-category' : target_OBJ.sec).val(selected);
            $((target_OBJ === undefined ? target_OBJ : target_OBJ.ac) === undefined ? '.js-active-category' : target_OBJ.ac).text(event_obj[selected]);
            $((target_OBJ === undefined ? target_OBJ : target_OBJ.cd) === undefined ? '.js-category-dropdown' : target_OBJ.cd).css({
                'background-color': event_color_obj[selected],
                'color': '#ffffff'
            });
            target.html(rows);
        }

        // Load personal view
        // @param is_load
        // Check for first time load
        function load_personal_view(edit_mode) {

            $('.js-person-email-check').hide(0);
            $('.js-person-email-check-input').prop('checked', false);
            //
            if (selected_personal_category === null)
                selected_personal_category = $('.js-selected-event-category-p').val();
            $('#js-reminder-email-wrap').hide(0);
            reset_modal_view('personal');
            // Get the category
            var selected_category = $('.js-selected-event-category-p').val() || event_type.default_personal;
            if (last_selected_category === selected_category) return false;
            // $('#js-interviewers-list').hide(0);
            load_categories('personal', {
                list: '.js-category-list-p',
                cd: '.js-category-dropdown-p',
                sec: '.js-selected-event-category-p',
                ac: '.js-active-category-p',
                selected: edit_mode !== undefined ? edit_mode.category : selected_category
            });
            //
            last_selected_category = selected_category;
            if (last_selected_category === selected_personal_category)
                $('#js-reminder-email-wrap').show(0);

            $('label#attendees_label').text(default_interviewer_text);
            $('#js-non-employee-heading').text(default_einterviewer_text);

            $('.js-comment-box').show(0);
            $('.js-comment-hr').show(0);
            // $('.js-interviewers-wrap').hide(0);

            remove_all_from_interviewers();
            $('#js-interviewers-box').hide(0);

            switch (selected_category) {
                case 'call':
                    $('.js-hide').hide(0);
                    $('.js-person-email').hide(0);

                    $('.js-personal-box').show(0);
                    $('.js-person-name').show(0);
                    $('.js-person-phone').show(0);

                    $('.js-person-email-check').show(0);
                    $('.js-person-email-check-input').prop('checked', false);
                    break;
                case 'email':
                    $('.js-hide').hide(0);
                    $('.js-person-phone').hide(0);
                    $('.js-personal-box').show(0);
                    $('.js-person-email').show(0);
                    $('.js-person-name').show(0);
                    break;
                case 'training-session':
                    $('.js-person-email').hide(0);
                    $('.js-person-phone').hide(0);
                    $('.js-person-name').hide(0);
                    $('.js-hide').hide(0);
                    //
                    $('.js-address-wrap').show();
                    if ($('#js-address-input').prop('checked') === true) {
                        $('#js-address-select-box').hide(0);
                        $('#js-address-input-box').show();
                    } else {
                        $('#js-address-input-box').hide(0);
                        $('#js-address-select-box').show(0);
                    }
                    // $('#js-address-input').parent().parent().parent().show(0);
                    // $('#js-address-input-box').show(0);
                    // $('#js-address-input-box').next().show(0);
                    // $('#js-interviewers-select').parent().parent().parent().show(0);
                    // $('#js-interviewers-box').show(0);
                    // $('#js-interviewers-box').next().show(0);
                    $('#js-extra-interviewers-box').parent().parent().parent().show(0);
                    $('#js-extra-interviewers-box').parent().parent().parent().next().show(0);
                    // $('#js-interviewers-list').show();

                    //
                    $('#js-employee-box, .js-employee-hr').hide(0);
                    $('#js-interviewers-box').hide(0);
                    $('label#attendees_label').text('Assigned Attendees');
                    $('#js-non-employee-heading').text('Assigned non-employee Attendees');
                    $('.js-comment-box, .js-message-wrap, #js-message-box, .js-meeting-wrap, #js-meeting-box').hide(0);
                    $('.js-message-hr').hide(0);
                    $('.js-comment-hr').hide(0);
                    <?php if ($calendar_opt['show_online_videos'] == 1) { ?>
                        $('#js-video-wrap, .js-video-hr').show(0);
                    <?php } ?>

                    $('.js-interviewers-wrap').show(0);
                    append_all_to_interviewers();
                    break;
                case 'meeting':
                    reset_modal_view();

                    $('label#attendees_label').text('Assigned Attendees');
                    $('#js-non-employee-heading').text('Assigned non-employee Attendees');

                    $('#js-interviewers-box').show(0);
                    $('.js-message-wrap, #js-message-box, .js-meeting-wrap, #js-meeting-box').show(0);

                    $('.js-message-hr').show(0);

                    if ($('#js-message-check').prop('checked') === false)
                        $('#js-message-box').hide(0);
                    if ($('#js-meeting-check').prop('checked') === false)
                        $('#js-meeting-box').hide(0);
                    load_categories('personal', {
                        selected: 'meeting'
                    });
                    break;
                default:
                    reset_modal_view();
                    $('label#attendees_label').text('Assigned Attendees');
                    $('#js-non-employee-heading').text('Assigned non-employee Attendees');
                    $('#js-interviewers-box').show(0);
                    $('.js-message-wrap, #js-message-box, .js-meeting-wrap, #js-meeting-box').show(0);

                    $('.js-message-hr').show(0);

                    if ($('#js-message-check').prop('checked') === false)
                        $('#js-message-box').hide(0);
                    if ($('#js-meeting-check').prop('checked') === false)
                        $('#js-meeting-box').hide(0);
                    load_categories('personal', {
                        selected: 'other'
                    });
                    break;
            }
        }


        // Resets modal view
        // @param user_type
        // Handles 
        function reset_modal_view(user_type) {
            if (user_type == 'personal') {
                $('.js-personal-type-wrap').show(0);
                $('.js-applicant-hr, .js-message-hr, .js-meeting-hr').hide(0);
                $('.js-interviewers-wrap').hide(0);
                $('#js-interviewers-list').hide(0);
                $('#js-interviewers-box').hide(0);
                $('.js-interviewers-hr').hide(0);
                $('.js-extra-interviewers-hr').hide(0);
                $('.js-extra-interviewers-wrap').hide(0);
                $('.js-message-wrap').hide(0);
                $('.js-address-wrap').hide(0);
                $('#js-address-input-box').hide(0);
                $('.js-address-hr').hide(0);
                $('.js-meeting-wrap').hide(0);
                $('#js-meeting-box, #js-message-box').hide(0);
                $('.js-event-title-wrap, .js-event-detail-wrap').hide(0);
                // console.log($('#js-event-date').datepicker('getDate'));
                //
                $('.js-datepicker').val(
                    moment($('#js-event-date').val(), site_date_format).format(site_date_format + " dddd")
                );
                // $('.js-selected-event-category-p').val('call');
                $('.js-clone-start-time').val($('#js-event-start-time').val());
                $('.js-clone-end-time').val($('#js-event-end-time').val());
                if ($('#js-event-title').val() != '')
                    $('#js-event-title-p').val($('#js-event-title').val());

                last_selected_category = null;

            } else {
                $('.js-personal-type-wrap').hide(0);
                $('.js-applicant-hr, .js-message-hr, .js-meeting-hr').show(0);
                $('.js-event-title-wrap').show(0);
                $('.js-event-detail-wrap').show(0);
                $('.js-interviewers-wrap').show(0);
                $('.js-interviewers-hr').show(0);
                $('.js-extra-interviewers-hr').show(0);
                $('.js-extra-interviewers-wrap').show(0);
                $('.js-message-wrap').show(0);
                $('.js-address-wrap').show(0);
                $('#js-address-input-box').show(0);
                $('#js-interviewers-list').show(0);
                $('.js-address-hr').show(0);
                $('.js-meeting-wrap').show(0);
                $('#js-interviewers-box').show(0);
                //
                if ($('#js-message-check').prop('checked') === true) $('#js-message-box').show();
                if ($('#js-meeting-check').prop('checked') === true) $('#js-meeting-box').show();
                //
                if (user_type === true && last_selected_category != null) {
                    if ($('.js-clone-start-time').val() != '')
                        $('#js-event-start-time').val($('.js-clone-start-time').val());
                    if ($('.js-clone-end-time').val() != '')
                        $('#js-event-end-time').val($('.js-clone-end-time').val());
                    if ($('#js-event-title-p').val() != '')
                        $('#js-event-title').val($('#js-event-title-p').val());
                }
                //
                if ($('#js-address-input').prop('checked') === true) {
                    $('#js-address-select-box').hide(0);
                    $('#js-address-input-box').show();
                } else {
                    $('#js-address-select-box').show(0);
                    $('#js-address-input-box').hide();
                }
            }
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
                    rows += '       <input data-value="' + v.value.replace(/ *\([^)]*\) */g, '') + '" data-id="' + v.id + '" data-type="interviewer" value="' + v.email_address + '" data-timezone="' + v.timezone + '" name="reminder_emails[]" checked="checked" type="checkbox" />';
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
                rows += '<div class="col-xs-12"><h4>' + (c.type == 'applicant' ? 'Applicant' : c.type == 'person' ? 'Person' : 'Employee') + '</h4></div>';
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
            rows += '       <button class="btn btn-success pull-right js-reminder-email-btn">Send Reminder Email</button>';
            rows += '    </div>';
            rows += '</div>';

            $('#js-reminder-email-box').html(rows);
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
            if (show_sent_reminder_history_btn || show_request_status_history_btn)
                extra_btns_wrap.html(rows);
        }

        // Get event status requests
        function fetch_event_status_history() {
            if (event_history_type == 'change_history') {
                fetch_event_change_history();
            } else if (event_history_type == 'history') {
                fetch_sent_reminder_emails_history();
            } else {
                event_blinker('clearTabInterval', $('#js-event-id').val());
                // get event
                $.get("<?= base_url(); ?>calendar/get-event-availablity-requests/" + $('#js-event-id').val() + "/" + current_page, function(resp) {
                    if (resp.Status === false && resp.Redirect === true) window.location.reload();
                    if (resp.Status === false) {
                        alertify.alert(resp.Response);
                        return false;
                    }
                    // Deafault values
                    var rows = '';
                    // Loop through arary
                    $.each(resp.History, function(i, v) {
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
                        rows += '       <strong>Request Received on:</strong> ' + nd.format('MMM DD YYYY') + ' at ' + nd.format('hh:mm A') + ' <br />';
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

        // Get sent reminder email history
        function fetch_sent_reminder_emails_history() {
            $.get("<?= base_url(); ?>calendar/get-reminder-email-history/" + $('#js-event-id').val() + "/" + current_page, function(resp) {
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
                        rows += '   <td colspan="3"><strong>The reminder ' + (v.length == 1 ? 'email was' : 'email(s) were') + ' sent on ' + nd.format('MMM DD YYYY') + ' at ' + nd.format('hh:mm A') + '.</strong></td>';
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

        // Get event change history
        function fetch_event_change_history() {
            $.get("<?= base_url(); ?>calendar/get_event_change_history/" + $('#js-event-id').val() + "/" + current_page, function(resp) {
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
                    if (v !== undefined && v.details != null) {
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
                            if (v.details.users_type !== undefined && v.details.users_type == 'applicant') {
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
            // var pages = Array.from(Array((end_page + 1) - start_page).keys()).map(i => start_page + i);
            var pages = Array.from(Array((end_page + 1) - start_page).keys()).map(function(i) {
                start_page + i
            });

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

        // Overwrite Alertify callback
        function flush_alertify_cb() {
            return;
        }

        // Remove event from calendar
        // @param eid
        // Holds event_sid or calendar id
        // @param is_search
        // Either search calendar id or not
        function remove_event_from_calendar(eid, is_search) {
            if (is_search !== undefined) {
                calendar_ref.fullCalendar('clientEvents', function(e) {
                    //
                    if (is_search == 'all')
                        calendar_ref.fullCalendar('removeEvents', e._id);
                    else {
                        if (e.event_id == eid) {
                            calendar_ref.fullCalendar('removeEvents', e._id);
                            return false;
                        }
                    }
                });
            } else calendar_ref.fullCalendar('removeEvents', eid);
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
                is_return = e.start.isAfter(recur_obj.start_date) || e.start.format(site_date_format) == recur_obj.start_date.format(site_date_format);
            // is_return = e.start.isAfter(recur_obj.start_date) || e.start.format(site_date_format) == recur_obj.start_date.format(site_date_format);
            // 2019-05-08 <= 2019-09-04 && 2019-05-08 >= 2019-05-08
            else if (recur_obj.end_date != null) {
                is_return =
                    (e.start.isBefore(recur_obj.end_date) || e.start.format(site_date_format) == recur_obj.start_date.format(site_date_format)) &&
                    (e.end.isAfter(recur_obj.start_date) || e.end.format(site_date_format) == recur_obj.end_date.format(site_date_format));
            }

            is_return_2 = (recur_obj.days.hasOwnProperty('is_all') || recur_obj.days.hasOwnProperty(e.start.day()));

            // console.log(
            // e.start.format(site_date_format),
            // e.end.format(site_date_format),
            // recur_obj.start_date.format(site_date_format),
            // recur_obj.end_date.format(site_date_format)

            // recur_obj
            // );

            // console.log(recur_obj.days.hasOwnProperty('is_all') || recur_obj.days.hasOwnProperty( e.start.day() ), e.start.day(), recur_obj.days, e.start.format(site_date_format), is_return, is_return_2);
            // console.log( e.start.day(), recur_obj.days, e.start.format(site_date_format) );

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
                        rows += '        <span data-key="' + key + '" title="' + v + '" class="cs-day js-day-box ' + is_class + '">' + (v.substring(0, 1)) + '</span>';
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
                    rows += '                    <input type="text" readonly="true" class="js-recurr-datepicker" ' + (selected === undefined ? '' : 'value="' + selected + '"') + ' />';
                    rows += '                </div>';
                    rows += '                <div class="col-sm-4">';
                    rows += '                    <label class="control control--checkbox">';
                    rows += '                        Infinite';
                    rows += '                        <input type="checkbox" class="js-infinite"/>';
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
                    rows += '            <p class="js-summary-row"></p>';
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
                    rows += '                    <input value="' + (selected === undefined ? 1 : selected) + '" type="text" minLength="1" id="js-recurr-week-input" />';
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
                        rows += '        <span data-key="' + key + '" title="' + v + '" class="cs-day js-month-box ' + is_class + '">' + (v.substring(0, 1)) + '</span>';
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
                    rows += '                    <input value="' + (selected === undefined ? 1 : selected) + '" type="text" minLength="1" id="js-recurr-year-input" />';
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
            $('.cs-row-view').html(row);
            // Load datepikcer for
            // recurr event
            $(".js-recurr-datepicker").datepicker({
                dateFormat: 'mm-dd-yy',
                minDate: 0,
                onSelect: function() {
                    set_summary();
                }
            });
            // Trigger set summary if is_load
            // check is active
            if (is_load) {
                $('.js-recurr-type').prop('checked', false);
                $('#js-recurr-' + is_load + '-check').prop('checked', true);
                set_summary();
            }
        }

        // Set summary for recurr event
        function set_summary() {
            var rows = '',
                number_of = null,
                type = 'week';

            // For monthly
            if ($('#js-recurr-monthly-check').prop('checked') === true ||
                $('#js-recurr-yearly-check').prop('checked') === true) {
                var month_length = $('.js-month-box.cs-active-day').length;
                if (month_length !== 0) {
                    rows += ' for month' + (month_length === 1 ? ' ' : 's ');
                    // Loop through selected
                    $.each($('.js-month-box.cs-active-day'), function(i, v) {
                        if (i == 0)
                            rows += recurr_months[$(this).data('key')];
                        else if (++i == month_length)
                            rows += ' and ' + recurr_months[$(this).data('key')];
                        else
                            rows += ', ' + recurr_months[$(this).data('key')];
                    });
                }
                // rows += ' ';
                number_of = $('#js-recurr-week-input').val();
                type = 'week' + (number_of == 1 ? '' : 's');
            }

            // For daily view
            if ($('#js-recurr-daily-check').prop('checked') === true ||
                $('#js-recurr-weekly-check').prop('checked') === true ||
                $('#js-recurr-monthly-check').prop('checked') === true ||
                $('#js-recurr-yearly-check').prop('checked') === true) {
                var day_length = $('.js-day-box.cs-active-day').length;
                rows += ' on ';
                // Loop through selected
                $.each($('.js-day-box.cs-active-day'), function(i, v) {
                    if (i == 0)
                        rows += recurr_days[$(this).data('key')];
                    else if (++i == day_length)
                        rows += ' and ' + recurr_days[$(this).data('key')];
                    else
                        rows += ', ' + recurr_days[$(this).data('key')];
                });
            }

            // For weekly
            if ($('#js-recurr-weekly-check').prop('checked') === true) {
                number_of = $('#js-recurr-week-input').val();
                type = 'week' + (number_of == 1 ? '' : 's');
            }

            // For yearly
            if ($('#js-recurr-yearly-check').prop('checked') === true) {
                number_of = $('#js-recurr-year-input').val();
                type = 'year' + (number_of == 1 ? '' : 's');
            }

            // Check if nothing was selected
            if (rows == '') {
                $('.js-summary-row').text('');
                return false;
            }
            // Load end time if defined
            if ($('.js-infinite').prop('checked') === false)
                rows += ' until ' + moment($('.js-recurr-datepicker').val()).format('MMMM Do, YYYY');
            // Add prefix
            rows = 'Repeats ' + (number_of === null ? '' : 'every ' + number_of + ' ' + type + ' ') + ' ' + rows;
            // Load summary to DOM
            $('.js-summary-row').html(rows);
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

        // Remove 'all' from interviewers
        // list
        function remove_all_from_interviewers() {
            var vals = $('#js-interviewers-select').val();
            if (vals == null) return;
            vals.remove('all');
            $('#js-interviewers-select option[value="all"]').remove();
            $('#js-interviewers-list').html('');
            $('#js-interviewers-select').select2('val', <?= $employer_id; ?>);

            selected_employer_id = 0;
            selected_interviewers = interviewers_list_array = [];
            interviewers_list_array.push("<?= $employer_id; ?>");
            selected_interviewers.push("<?= $employer_id; ?>");
            $('#js-interviewers-select option').prop('disabled', false);

            var obj = get_ie_obj(<?= $employer_id; ?>);
            select_interviewer({
                id: obj['employer_id'],
                employee_type: obj['employee_type'],
                'label': obj['full_name']
            });
        }

        // Add 'all' in interviewers
        // list
        function append_all_to_interviewers(default_select) {
            $('#js-interviewers-select').find('option[value="all"]').remove();
            $('#js-interviewers-select').append('<option value="all">All</option>');
            if (default_select === undefined) {
                $('#js-interviewers-select').select2('val', '');
                $('#js-interviewers-select').select2('val', 'all');
            }
        }

        // Remove 'all' when employee is selected
        // Add 'all' when no employee is selected
        function handle_all_check(selected_interviewers) {
            //
            if (($('#js-user-type-employee').prop('checked') === true && $('.js-selected-event-category').val() == 'training-session') ||
                ($('#js-user-type-personal').prop('checked') === true && last_selected_category == 'training-session')) {
                if (selected_interviewers == null) {
                    $('#js-interviewers-select').select2('val', 'all');
                    return false;
                }
                console.log(selected_interviewers);
                //
                if (selected_interviewers.length > 1) {
                    selected_interviewers.remove('all');
                    $('#js-interviewers-select').select2('val', selected_interviewers);
                }
            }
        }

        // Fetch online videos 
        function fetch_online_videos() {
            $.get("<?= base_url('calendar/fetch-online-videos'); ?>", function(resp) {
                if (resp.Status === false && resp.Redirect === true) window.reload();
                if (resp.Status === false) return;
                //
                $.each(resp.Videos, function(i, v) {
                    $('#js-video-wrap select').append('<option value="' + v.id + '">' + v.name + '</option>');
                });
            });
        }

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
            // Difference of event type
            if (new_data.users_type != current_edit_event_details.users_type) {
                diff_obj.old_users_type = current_edit_event_details.users_type;
                diff_obj.new_users_type = new_data.users_type;
            }

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
                    if (typeof new_data.interviewer === 'string' || new_data.interviewer instanceof String)
                        diff_obj.new_interviewers = new_data.interviewer;
                    else
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
            // Difference of Applicant timezone
            if (new_data.event_timezone != current_edit_event_details.event_timezone) {
                diff_obj.old_event_timezone = current_edit_event_details.event_timezone;
                diff_obj.new_event_timezone = new_data.event_timezone;
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
        function make_ajax_request(type, form_data, my_array, prev_event, cb) {
            // Send post AJAX
            $.ajax({
                url: "<?php echo base_url('calendar/event-handler'); ?>",
                type: 'POST',
                data: form_data,
                processData: false,
                contentType: false,
                success: function(res) {
                    $('#loader').hide();
                    $('.btn').removeClass('disabled');
                    $('.btn').prop('disabled', false);

                    $('#file_loader').css("display", "none");
                    $('#js-event-modal').modal('hide');
                    //
                    alertify.alert(res.Response, flush_alertify_cb);
                    if (res.Status === false) {
                        if (type == 'update' || type == 'reschedule' || type == 'expired-reschedule')
                            remove_event_from_calendar(event_id, 'find');
                    } else
                        cb(my_array, ((type == 'update' || type == 'reschedule') ? prev_event : res.EventId));
                    $('body').removeClass("ajs-no-overflow");
                },
                always: function(e) {
                    $('#loader').hide();
                    $('.btn').removeClass('disabled');
                    $('.btn').prop('disabled', false);
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

        // // For applicant
        // $('#js-applicant-phone').keyup(function(){ 
        //      var val = fpn($(this).val());
        //     if(typeof(val) === 'object'){
        //         $(this).val(val.number);
        //         setCaretPosition(this, val.cur);
        //     } else $(this).val(val);
        // });

        // // For employee
        // $('#js-employee-phone').keyup(function(){ 
        //      var val = fpn($(this).val());
        //     if(typeof(val) === 'object'){
        //         $(this).val(val.number);
        //         setCaretPosition(this, val.cur);
        //     } else $(this).val(val);
        // });

        // // For personal
        // $('.js-person-phone  input').keyup(function(){ 
        //      var val = fpn($(this).val());
        //     if(typeof(val) === 'object'){
        //         $(this).val(val.number);
        //         setCaretPosition(this, val.cur);
        //     } else $(this).val(val);
        // });
    });

    // Goto employee profile
    function func_goto_employee_profile() {
        // var employee_sid = $('#js-employee-input').data('id');
        if (selected_employer_id !== undefined && selected_employer_id !== '' && selected_employer_id !== 0 && selected_employer_id !== null) {
            var url = '<?php echo base_url('employee_profile'); ?>/' + selected_employer_id;
            window.open(url);
        }
    }

    // Goto applicant profile
    function func_goto_applicant_profile() {
        var applicant_sid = $('#js-applicant-input').data('id'),
            job_list_sid = $('#js-applicant-input').data('jid');
        job_list_sid = job_list_sid == undefined ? '' : job_list_sid;
        if (applicant_sid !== undefined && applicant_sid !== '' && applicant_sid !== 0 && applicant_sid !== null) {
            var url = '<?php echo base_url('applicant_profile'); ?>/' + applicant_sid + '/' + job_list_sid;
            window.open(url);
        }
    }



    //
    function shiftsview(_this, event) {
        //
        if (event.type != 'shifts') return;
        //
        var body_title = "<strong>" + event.title + "</strong>";
        var body_content = '';
        var img_path = event.img == '' || event.img == null ? 'https://www.automotohr.com/assets/images/img-applicant.jpg' : "<?= AWS_S3_BUCKET_URL; ?>" + event.img;

        let shiftDate = event.shift_date ? moment(event.shift_date, 'YYYY-MM-DD').format('MMM DD YYYY, ddd') : "";

        let startTime = moment(event.start_time, "HH:mm").format("h:mm a");

        body_content += '<div class="row">';
        body_content += '   <div class="col-sm-4">';
        body_content += '       <img src="' + img_path + '" style="max-width: 100%;" />';
        body_content += '   </div>';

        body_content += '   <div class="col-sm-8"><strong>';
        body_content += '       <p>' + shiftDate + '</p>';
        body_content += '   </strong></div>';

        body_content += '   <div class="col-sm-8"><strong>';
        body_content += '       <p>Start Time : ' + moment(event.start_time, "HH:mm").format("h:mm a") + '</p>';
        body_content += '       <p>End Time: ' + moment(event.end_time, "HH:mm").format("h:mm a") + '</p>';
        body_content += '  </strong> </div>';

        body_content += '</div>';
        body_content += '<hr />';
        body_content += '<div class="row">';
        body_content += '   <div class="col-sm-12">';

        body_content += '   </div>';
        body_content += '</div>';
        //
        $(_this).popover({
            title: body_title,
            placement: 'top',
            trigger: 'hover',
            html: true,
            content: body_content,
            container: 'body'
        }).popover('show');
        //    

    }

    //
    $(document).on('click', '.js-pagination-shift', function(event) {
        event.preventDefault();
        func_show_loader();
        current_page = $(this).data('page');
        fetch_event_status_history();
    });



    // Format Phone Number
    // @param phone_number
    // The phone number string that 
    // need to be reformatted
    // @param format
    // Match format 
    // @param is_return
    // Verify format or change format
    // function fpn(phone_number, format, is_return) {
    //     // 
    //     var default_number = '(___) ___-____';
    //     var cleaned = phone_number.replace(/\D/g, '');
    //     if(cleaned.length > 10) cleaned = cleaned.substring(0, 10);
    //     match = cleaned.match(/^(1|)?(\d{3})(\d{3})(\d{4})$/);
    //     //
    //     if (match) {
    //         var intlCode = '';
    //         if( format == 'e164') intlCode = (match[1] ? '+1 ' : '');
    //         return is_return === undefined ? [intlCode, '(', match[2], ') ', match[3], '-', match[4]].join('') : true;
    //     } else{
    //         var af = '', an = '', cur = 1;
    //         if(cleaned.substring(0,1) != '') { af += "(_"; an += '('+cleaned.substring(0,1); cur++; }
    //         if(cleaned.substring(1,2) != '') { af += "_";  an += cleaned.substring(1,2); cur++; }
    //         if(cleaned.substring(2,3) != '') { af += "_) "; an += cleaned.substring(2,3)+') '; cur = cur + 3; }
    //         if(cleaned.substring(3,4) != '') { af += "_"; an += cleaned.substring(3,4);  cur++;}
    //         if(cleaned.substring(4,5) != '') { af += "_"; an += cleaned.substring(4,5);  cur++;}
    //         if(cleaned.substring(5,6) != '') { af += "_-"; an += cleaned.substring(5,6)+'-';  cur = cur + 2;}
    //         if(cleaned.substring(6,7) != '') { af += "_"; an += cleaned.substring(6,7);  cur++;}
    //         if(cleaned.substring(7,8) != '') { af += "_"; an += cleaned.substring(7,8);  cur++;}
    //         if(cleaned.substring(8,9) != '') { af += "_"; an += cleaned.substring(8,9);  cur++;}
    //         if(cleaned.substring(9,10) != '') { af += "_"; an += cleaned.substring(9,10);  cur++;}

    //         if(is_return) return match === null ? false : true;

    //         return { number: default_number.replace(af, an), cur: cur };
    //     }
    // }

    // // Change cursor position in input
    // function setCaretPosition(elem, caretPos) {
    //     if(elem != null) {
    //         if(elem.createTextRange) {
    //             var range = elem.createTextRange();
    //             range.move('character', caretPos);
    //             range.select();
    //         }
    //         else {
    //             if(elem.selectionStart) {
    //                 elem.focus();
    //                 elem.setSelectionRange(caretPos, caretPos);
    //             } else elem.focus();
    //         }
    //     }
    // }
</script>