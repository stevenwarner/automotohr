<?php
$this->config->load('calendar_config');
//
$calendar_opt = $this->config->item('calendar_opt');

$event_color_array = get_calendar_event_color();
$event_obj = $calendar_opt['event_types'];
$event_obj['demo'] = 'Demo';
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
echo '</style>';
?>

<script>
    // Select ID for employer
    var selected_employer_id = null,
        //
        site_date_format = 'MM-DD-YYYY',
        site2_date_format = 'YYYY-MM-DD',
        // 
        calendar_OBJ = {},
        // Set default time gap
        // for end time in event
        // modal
        add_time = 30,
        add_time_type = 'minutes',
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
        // New
        calendar_ref = $('#js-calendar'),
        //
        show_clone_btn = false,
        current_page = 1,
        show_recur_btn = <?= $calendar_opt['show_recur_btn']; ?>,
        show_sent_reminder_history_btn = true,
        show_request_status_history_btn = true,
        // Show reschedule popup
        show_reschedule_box = 1,
        // Set default comment section
        // text
        default_comment_text = 'Comment for Participant(s)',
        // Set default comment section
        // text for 'Personal' type
        personal_comment_text = 'Personal Comment',
        demo_comment_text = 'Comment for System User(s) / External User(s)',
        // Last selected tab
        last_selected_category = null,
        last_selected_user_type = null,
        interviewers_list_array = [],
        interviewers_list_name_array = [],
        // Phone validation regex
        // @accepts
        // Number, Hyphens, Underscrores, Brackets
        phone_regex = new RegExp(/[0-9]/g),
        // phone_regex = new RegExp(/^[+]?[\s./0-9]*[(]?[0-9]{1,4}[)]?[_-\s./0-9]*$/g),
        email_regex = new RegExp(/[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/),
        digit_regex = new RegExp(/^[0-9]+$/g),
        url_regex = new RegExp(/(https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]+\.[^\s]{2,}|www\.[a-zA-Z0-9]+\.[^\s]{2,})/),
        eventsXHR = null,
        event_XHR = null,
        blink_interval = null,
        blink_interval_btn = null,
        time_24 = ' 23:59:59',
        // Set request page reference
        request_page_REF = $('.js-request-page'),
        // Set reminder page reference
        reminder_page_REF = $('.js-reminder-email-history-page'),
        // Set reschedule page reference
        reschedule_page_REF = $('.js-reschedule-page'),
        // Set main page reference
        main_page_REF = $('.js-main-page'),
        event_title_text = 'Event Management',
        // Set clone and history buttons reference
        extra_btns_wrap = $('.js-extra-btns'),
        current_edit_event_details = {},
        drag_event = {};

    // Set default values
    calendar_OBJ.current = {};
    calendar_OBJ.employee_id = "<?= $admin_id; ?>";
    calendar_OBJ.records = [];
    //
    $('#js-event-modal').appendTo('body');
    $('.fc-popover').popover('show');
    $('.fc-popover').on('hide.bs.popover', function() {
        return false;
    });
</script>


<script>
    // jQuery IFFY
    $(function() {
        //
        load_notes();
        reset_modal();
        loader('show');

        // Restrict modal not to close on esc or outside click
        // $('#js-event-modal').modal({ backdrop: 'static', keyboard: false , show: false});

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
                loader('show');
                drag_event = {};
                event_process(e);
            },
            // Triggers when an event is clicked
            eventClick: function(event) {
                if (event.scheduled == '1') {
                    scheduleEventview($(this), event);
                } else {
                    loader('show');
                    reset_modal();
                    edit_event(event);
                }
            },
             eventMouseover: function(event, jsEvent, view) {
               if (event.scheduled == '1') {
                    scheduleEventview($(this), event);
                }
            },
            //
            eventDragStart: function(event) {
                if (Object.size(drag_event) == 0 || event.event_id != drag_event.id) {
                    drag_event = {
                        id: event.event_sid,
                        start_time: event.start,
                        end_time: event.end,
                        date: event.event_date
                    };
                }
            },
            // Triggers when an event is drop
            eventDrop: function(event, delta, revertFunc) {
                update_event(event, revertFunc);
            },
            eventResizeStart: function(event) {
                if (Object.size(drag_event) == 0 || event.event_id != drag_event.id) drag_event = {
                    start_time: event.start,
                    end_time: event.end,
                    date: event.eventdate
                };
            },
            // Triggers when an event is resized
            eventResize: function(event) {
                update_event(event);
            },
            // Triggers when there is click on calendar
            dayClick: function(date, all_day, js_event) {
                loader();
                reset_modal();
                add_event(date, js_event);
            },
            eventRender: function(event, el) {
                if (event.new_event_requests != 0 && event.new_event_requests !== undefined) {
                    el.addClass("fc-event-blink");
                    if (blink_interval == null)
                        event_blinker();
                }
                // Add id for trigger reschedule
                // click on drag
                el.addClass("fc-event-" + event.event_sid + "");
                el.addClass("fc-event-" + event._id + "");
                // Basic styling
                el.addClass("fc-event-cc");
                // Category effect
                el.addClass("fc-event-cc-" + event.category + "");
                //Set border style
                el.addClass("fc-event-cc-" + event.status + "");
                // Returns if recur option is FALSE
                if (default_recur === 0) return true;
                // return check_for_reoccur_event(event); 
            }
        });

        // Document click starts

        // User type toggle
        $(document).on('click', '.js-users-type', function() {
            if ($(this).val() == last_selected_user_type) return;
            last_selected_user_type = $(this).val();
            reset_modal($(this).val());
            last_selected_category = null;
            // Check if personal is clicked
            if ($(this).val() == 'personal') {
                load_personal_view();
            } else if ($(this).val() == 'demo') {
                load_demo_view();
            } else load_categories($(this).val());
        });

        // Set category on click 
        $(document).on('click', '.js-btn-category', function() {
            // Check if last selected type is same
            if ($(this).data('id') == last_selected_category) return false;
            // Set last seelcted category
            last_selected_category = $(this).data('id');
            //
            remove_all_from_participants();
            //
            reset_modal('super admin');
            reset_modal(last_selected_category, $('.js-users-type').val());
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
            // Check if last selected type is same
            if ($(this).data('id') == last_selected_category) return false;
            // Set last seelcted category
            last_selected_category = $(this).data('id');
            //
            remove_all_from_participants();
            //
            reset_modal('personal');
            reset_modal(last_selected_category, 'personal');
            $('.js-selected-event-category-p').val($(this).data('id'));
            $('.js-active-category-p').text(event_obj[$(this).data('id')]);
            $('.js-category-dropdown-p').css({
                'background-color': event_color_obj[$(this).data('id')],
                'color': '#ffffff'
            });
            // if($('#js-user-type-personal').prop('checked') === true) load_personal_view(true);
        });

        // Set demo category on click 
        $(document).on('click', '.js-demo-category-btn', function() {
            // Check if last selected type is same
            if ($(this).data('id') == last_selected_category) return false;
            // Set last seelcted category
            last_selected_category = $(this).data('id');
            //
            remove_all_from_participants();
            //
            reset_modal('demo');
            reset_modal(last_selected_category, 'demo');
            $('.js-selected-demo-category').val($(this).data('id'));
            $('.js-demo-active-category').text(event_obj[$(this).data('id')]);
            $('.js-demo-category-dropdown').css({
                'background-color': event_color_obj[$(this).data('id')],
                'color': '#ffffff'
            });
        });

        // Comment check toggle
        $(document).on('click', '#js-comment-check', function() {
            $('#js-comment-msg-box').hide(0);
            if ($(this).prop('checked')) $('#js-comment-msg-box').show(0);
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

        // Add external user
        $(document).on('click', '#btn_add_user', function() {
            var random_id = Math.floor((Math.random() * 1000) + 1);
            var new_row = $('#external_user_0').clone();
            //
            $(new_row).find('i.fa').removeClass('fa-plus').addClass('fa-trash');
            $(new_row).find('button.btn').removeAttr('id').removeClass('btn-success').addClass('btn-danger').addClass('btn_remove_user').attr('data-id', random_id);
            $(new_row).find('input').val('');
            $(new_row).attr('id', 'external_users_' + random_id);
            $(new_row).addClass('external_users');
            $(new_row).attr('data-id', random_id);
            $(new_row).find('input.external_users_name').attr('data-rule-required', true);
            $(new_row).find('input.external_users_email').attr('data-rule-required', true);
            $(new_row).find('input.external_users_email').attr('data-rule-email', true);
            $(new_row).find('input').each(function() {
                var name = $(this).attr('name').toString(),
                    id = $(this).attr('id').toString();
                name = name.split('0').join(random_id);
                id = id.split('0').join(random_id);
                $(this).attr('name', name);
                $(this).attr('id', id);
            });
            $('#js-external-users').append(new_row);
        });
        // Remove external user
        $(document).on('click', '.btn_remove_user', function() {
            $($(this).closest('.external_users').get()).remove();
        });

        // Add external user
        $(document).on('click', '#btn_add_admin', function() {
            var random_id = Math.floor((Math.random() * 1000) + 1);
            var new_row = $('#external_admin_0').clone();
            //
            $(new_row).find('i.fa').removeClass('fa-plus').addClass('fa-trash');
            $(new_row).find('button.btn').removeAttr('id').removeClass('btn-success').addClass('btn-danger').addClass('btn_remove_admin').attr('data-id', random_id);
            $(new_row).find('input').val('');
            $(new_row).attr('id', 'external_admins_' + random_id);
            $(new_row).addClass('external_admins');
            $(new_row).attr('data-id', random_id);
            $(new_row).find('input.external_admins_name').attr('data-rule-required', true);
            $(new_row).find('input.external_admins_email').attr('data-rule-required', true);
            $(new_row).find('input.external_admins_email').attr('data-rule-email', true);
            $(new_row).find('input').each(function() {
                var name = $(this).attr('name').toString(),
                    id = $(this).attr('id').toString();
                name = name.split('0').join(random_id);
                id = id.split('0').join(random_id);
                $(this).attr('name', name);
                $(this).attr('id', id);
            });
            $('#js-external-admins').append(new_row);
        });
        // Remove external user
        $(document).on('click', '.btn_remove_admin', function() {
            $($(this).closest('.external_admin').get()).remove();
        });

        // Reoccur
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
        $('#js-participants-select').on('select2-selecting', function(e) {
            $('.js_ps_row_' + e.val + '').show();
        });
        //
        $('#js-participants-select').on('select2-removing', function(e) {
            $('.js_ps_row_' + e.val + '').hide();
        });

        // Add extra interviewer event
        $(document).on('click', '#btn_add_participant', function() {
            var random_id = Math.floor((Math.random() * 1000) + 1),
                new_row = $('#external_participant_0').clone();
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
                $(this).attr('name', $(this).attr('name').toString().split('0').join(random_id));
                $(this).attr('id', $(this).attr('id').toString().split('0').join(random_id));
            });
            $('#js-extra-participants-box').append(new_row);
        });
        // Remove extra interviewer event
        $(document).on('click', '.btn_remove_participant', function() {
            $($(this).closest('.external_participants').get()).remove();
        });

        //
        $('#js-event-modal').on('hidden.bs.modal', function() {
            $('body').removeClass('ajs-no-overflow');
            last_selected_category = null;
        });

        // Flush local data on modal close
        $(document).on('hidden.bs.modal', '#js-event-modal', function() {
            $('#js-event-modal').find('input, textarea, select, button').prop('disabled', false);
            current_edit_event_details = {};
        });

        // Handles 'all' option for 'training-session'
        // Exclude 'all' if other user is selected
        $('#js-participants-select').on('change', function() {
            if ($(this).val() !== null && $(this).val().length > 1 && $(this).val().indexOf('all') != -1) {
                var tmp = $(this).val();
                tmp.splice($(this).val().indexOf('all'));
                $('#js-participants-select').select2(
                    'val',
                    tmp
                );
            }
        });

        // Handles 'all' option for 'training-session'
        // Include 'all' when no user is selected
        $('#js-participants-select').on('select2-removed', function() {
            if ($(this).val() == null || $(this).val().length == 0) $('#js-participants-select').select2('val', 'all');
        });

        // Triggeres upon 'Save'
        $(document).on('click', '#js-save', function(e) {
            e.preventDefault();
            var obj = event_validation('save');
            // return;
            if (obj === undefined || obj === false) return;
            process_event(obj, 'save');
            // loader(true);
        });

        // Triggers on 'Update'
        $(document).on('click', '#js-update', function(e) {
            e.preventDefault();
            var obj = event_validation('update');
            if (obj === undefined || obj === false) return;
            if (Object.size(obj.diff) == 0) {
                alertify.confirm('&nbsp;', 'You haven\'t made any changes to the event. Do you want to close this event modal?', function() {
                    $('#js-event-modal').modal('hide');
                }, function() {
                    return;
                }).set('labels', {
                    ok: 'Yes',
                    cancel: 'No'
                });
            } else process_event(obj, 'update');
            // loader(true);
        });

        // Reschedule expired event
        $('form#js-reschedule-form').submit(function(e) {
            e.preventDefault();
            var obj = {
                action: 'expired_reschedule',
                reschedule_date: moment($('#js-reschedule-event-date').val(), site_date_format).format(site2_date_format),
                reschedule_start_time: moment($('#js-reschedule-event-start-time').val(), 'hh:mmA').format('hh:mm A'),
                reschedule_end_time: moment($('#js-reschedule-event-end-time').val(), 'hh:mmA').format('hh:mm A'),
                event_sid: $('#js-event-id').val()
            };

            loader('show');

            $.post("<?= base_url('manage_admin/process-event'); ?>", obj, function(resp) {
                if (resp.Status === true) {
                    var old_obj = find_event(obj.event_sid),
                        new_obj = {};
                    new_obj.title = old_obj.title;
                    new_obj.editable = old_obj.editable;
                    new_obj.color = old_obj.color;
                    new_obj.event_category = old_obj.event_category;
                    new_obj.event_type = old_obj.event_type;
                    new_obj.event_requests = old_obj.event_requests;
                    new_obj.expired_status = old_obj.expired_status;
                    new_obj.status = old_obj.status;
                    new_obj.last_status = old_obj.last_status;
                    new_obj.event_sid = resp.EventCode;
                    new_obj.event_date = moment(obj.reschedule_date, site2_date_format).format(site2_date_format);
                    new_obj.start = moment(obj.reschedule_date + 'T' + convert_12_to24(obj.reschedule_start_time)).utc();
                    new_obj.end = moment(obj.reschedule_date + 'T' + convert_12_to24(obj.reschedule_end_time)).utc();

                    calendar_ref.fullCalendar('renderEvent', new_obj);
                    $('#js-event-modal').modal('hide');
                } else $('.fc-event-cc-' + obj.event_sid + '').trigger('click');

                alertify.alert(resp.Response, flush_cb);
                loader();
            });
        });

        // Cancel the event
        $(document).on('click', '#js-cancel', function(e) {
            e.preventDefault();
            // Ask for confirmation
            alertify.confirm('Are you sure you want to Cancel this event?', function() {
                // Cancel the event
                $.post("<?= base_url('manage_admin/process-event'); ?>", {
                    action: 'cancel',
                    event_sid: $('#js-event-id').val()
                }, function(resp) {
                    if (resp.Status === true) {
                        // Remove event from calendar
                        cancel_event($('#js-event-id').val());
                        alertify.notify(resp.Response, 'success', 5, flush_cb);
                        return;
                    }
                    alertify.notify(resp.Response, 'error', 5, flush_cb);
                });
            }, flush_cb).set('labels', {
                ok: 'Yes',
                cancel: 'No'
            });
        });

        // Reschedule the expired event
        $(document).on('click', '#js-expired-reschedule', function(e) {
            e.preventDefault();
            var event_sid = $('#js-event-id').val();
            if (event_sid == undefined || event_sid == '' || event_sid == null) return;
            // Popup message and show event details
            // upon okay
            alertify.confirm("The event is expired. Do you want to reschedule it?", function() {
                // Make sure the event hit after
                $('#js-reschedule-form').find('input').prop('disabled', false);
                // loading data to modal
                process_expired_reschedule();
            }, flush_cb).set('labels', {
                ok: 'Yes',
                cancel: 'No'
            });
        });

        // Reschedule the event
        $(document).on('click', '#js-reschedule', function(e) {
            e.preventDefault();
            var event_sid = $('#js-event-id').val(),
                e;
            if (event_sid == undefined || event_sid == '' || event_sid == null) return;
            e = find_event(event_sid);
            // Popup message and show event details
            // upon okay
            alertify.confirm("Do you want to reschedule it?", function() {
                $.post("<?= base_url('manage_admin/process-event'); ?>", {
                    action: 'reschedule',
                    event_sid: event_sid,
                    status: 'pending',
                    reschedule_date: e.event_date
                }, function(resp) {
                    if (resp.Status === true) {
                        e.status = e.last_status;
                        // Change the event status
                        alertify.notify(resp.Response, 'success', 5, flush_cb);
                        calendar_ref.fullCalendar('renderEvent', e);
                        $('#js-event-modal').modal('hide');
                        return;
                    }
                    alertify.notify(resp.Response, 'error', 5, flush_cb);
                });
            }, flush_cb).set('labels', {
                ok: 'Yes',
                cancel: 'No'
            });
        });

        // Delete the event
        $(document).on('click', '#js-delete', function(e) {
            e.preventDefault();
            var event_sid = $('#js-event-id').val(),
                e;
            if (event_sid == undefined || event_sid == '' || event_sid == null) return;
            e = find_event(event_sid);
            // Popup message and show event details
            // upon okay
            alertify.confirm("Are you sure you want to delete this event?", function() {
                $.post("<?= base_url('manage_admin/process-event'); ?>", {
                    action: 'delete',
                    event_sid: event_sid,
                    event_cancel_email: 'no'
                }, function(resp) {
                    if (resp.Status === true) {
                        // Change the event status
                        alertify.notify(resp.Response, 'success', 5, flush_cb);
                        $('#js-event-modal').modal('hide');
                        calendar_ref.fullCalendar('removeEvents', e._id);
                        return;
                    }
                    alertify.notify(resp.Response, 'error', 5, flush_cb);
                });
            }, flush_cb).set('labels', {
                ok: 'Yes',
                cancel: 'No'
            });
        });

        // Close resdhedule modal
        $(document).on('click', '#js-reschedule-cancel', function(e) {
            e.preventDefault();
            $('#js-event-modal').modal('hide');
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
                    email_address: $(this).val().trim()
                };
                reminder_emails.push(obj);
            });
            // Check for empty array
            if (reminder_emails.length === 0) {
                alertify.alert("Error! Please, select atleast one email to send reminder.", flush_cb);
                return false;
            }
            //
            loader('show');

            $.post("<?= base_url('manage_admin/process-event'); ?>", {
                emails: reminder_emails,
                event_sid: $('#js-event-id').val(),
                action: 'send_reminder_emails'
            }, function(resp) {
                if (resp.Status === false && resp.Redirect === true) window.location.reload();
                alertify.alert(resp.Response, flush_cb);
                // Load history button
                if ($('.js-status-reminder-history-btn').length == 0)
                    load_extra_buttons(false, false, 1);
                loader();
            });
        });

        // Toggle show email notification option
        $('.js-person-send-notification-input').click(function(event) {
            $('.js-person-email').toggle();
        });

        // Document click ends

        // Loads event process
        // when day or mode is changed
        // @param e
        // Contains the information
        // of current event
        function event_process(e) {
            current_edit_event_detailsdrag_event = drag_event = {};
            clearInterval(blink_interval);
            blink_interval = null;
            // show loader
            loader('show');
            // return;
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
            // for start day to end day

            // set start and end year year
            calendar_OBJ.current.start_date = e.start.format('YYYY-MM-DD');
            calendar_OBJ.current.end_date = e.end.format('YYYY-MM-DD');

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
            // fetch records
            fetch_events_by_date(calendar_OBJ.current.date_array, function(resp) {
                // check for redirect
                if (resp.Status === false && resp.Redirect === true) location.reload();
                // check for records
                if (resp.Status === false) {
                    loader(false);
                    return false;
                }
                // save latest records
                calendar_OBJ.records = resp.Events;
                // load events
                calendar_ref.fullCalendar('addEventSource', calendar_OBJ.records);
                calendar_ref.fullCalendar('render');
                loader();
            });
        }

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
            eventsXHR = $.post("<?= base_url(); ?>manage_admin/get-events", {
                type: calendar_OBJ.current.type,
                year: date_array[0],
                month: date_array[1],
                day: date_array[2],
                start_date: calendar_OBJ.current.start_date || null,
                end_date: calendar_OBJ.current.end_date || null,
                week_start: calendar_OBJ.current.week_start || null,
                week_end: calendar_OBJ.current.week_end || null,
                admin_id: <?= $admin_id; ?>
            }, cb);
        }

        // Reset Modal
        // @param reset_type OPtional
        // @param event_for OPtional
        function reset_modal(reset_type, event_for) {
            $('.js-person-show-email-check').hide();
            $('.js-person-send-notification-input').prop('checked', false);
            // Box reset
            if (reset_type === undefined || reset_type === 'super admin') {
                last_selected_user_type = null;
                $('#js-user-select').select2({
                    closeOnSelect: false,
                    allowHtml: true,
                    allowClear: true,
                    tags: true
                });
                //
                $('.js-comment-text > span').text(default_comment_text);
                $('.js-event-detail-wrap').show(0);
                $('#js-superadmin-box').show(0);
                $('.js-superasmin-hr').show(0);
                $('.js-event-title-wrap').show(0);
                $('.js-reminder-box').show(0);
                $('.js-meeting-wrap').hide(0);
                // Hide pages
                $('.js-request-page').hide(0);
                $('.js-reminder-email-history-page').hide(0);
                $('.js-reschedule-page').hide(0);
                $('.js-recur-page').hide(0);
                // Show page
                $('.js-main-page').show(0);
                // Hide personal view
                $('.js-personal-box').hide(0);
                $('.js-superadmin-box').hide(0);

                //
                $('.js-address-text-row').hide(0);
                $('#js-address-input-box').hide(0);
                $('.js-address-hr').hide(0);
                $('#js-address-text').val('');
                //
                $('#js-meeting-wrap').hide(0);
                $('#js-meeting-box').hide(0);
                $('#js-meeting-check').prop('checked', false);
                //

                $('#js-reminder-email-wrap').hide(0);
                $('#js-reminder-email-check').prop('checked', false);
                $('#js-reminder-email-box').html('');
                //
                $('#js-reoccur-check').prop('checked', false);
                $('#js-reoccur-box').hide(0);
                //
                $('.js-participants-wrap').show(0);
                $('#js-participants-box').show(0);
                $('.js-participants-hr').show(0);
                //
                $('.js-demo-category-wrap').hide(0);
                $('.js-event-category-wrap').show(0);

                //
                $('.js-extra-participants-wrap').show(0);
                $('.js-extra-participants-hr').show(0);
                //
                $('.js_ps_row').hide(0);
                $('#js-demo-box').hide(0);
                //
                if (show_recur_btn === 0) $('#js-reoccur-wrap').hide(0);

                //
                load_categories();
                load_main_page_pickers();

                //
                if (reset_type === undefined) {
                    extra_btns_wrap.html('');
                    current_edit_event_details = {};
                    // Reset event title
                    $('.js-event-title-wrap > input').val('');
                    // Set Super admin by default
                    $('#js-user-type-super-admin').prop('checked', true);
                    $('#js-user-type-personal').prop('checked', false);
                    // Select2
                    $('#js-participants-select').select2({
                        closeOnSelect: false,
                        allowHtml: true,
                        allowClear: true,
                        tags: true
                    });
                    $('#js-superadmin-select').select2({
                        closeOnSelect: false,
                        allowHtml: true,
                        allowClear: true,
                        tags: true
                    });
                    $('#js-comment-check').prop('checked', false);
                    $('#js-reminder-box').hide(0);
                    $('#js-reminder-check').prop('checked', false);
                    $('#js-reminder-select').val(15);

                    //
                    $('#js-extra-participants-box').html(get_extra_participants_row());

                    default_start_time = "<?= $calendar_opt['default_start_time']; ?>";
                    default_end_time = "<?= $calendar_opt['default_end_time']; ?>";
                    //
                    $('#js-event-date').val(moment().format(site_date_format));
                    $('#js-event-start-time').val(default_start_time);
                    $('#js-event-end-time').val(default_end_time);

                    $('#js-event-title').val(
                        $('#js-event-title-p').val().trim()
                    );
                    //
                    $('.js-modal-btn').hide(0);
                    $('.js-save-btn').show(0);
                    //
                    $('#js-comment-msg-box').hide(0);
                    $('.js-comment-box').hide(0);

                    $('#js-event-title').val('');
                    $('#js-event-title-p').val('');
                    $('#js-comment-msg').val('');
                    $('#js-superadmin-select').select2('val', [0]);
                    $('#js-meeting-call').val('');
                    $('#js-meeting-id').val('');
                    $('#js-meeting-url').val('');
                    $('#cancelled_message').hide(0);

                    $('.js-meeting-hr').hide(0);

                    all_option('remove');

                    $('#js-user-select').select2('val', []);
                    $('.js-person-name input').val('');
                    $('.js-person-phone input').val('');
                    // $('.js-person-phone input').val('(___) ___-____');
                    $('.js-person-email input').val('');
                    $('#js-participants-select').select2('val', null);
                }
            } else if (reset_type == 'personal') {
                $('#js-demo-box').hide(0);
                $('.js-personal-box').show(0);
                $('#js-reminder-email-wrap').hide(0);
                $('.js-reminder-box').show(0);
                $('.js-meeting-wrap').hide(0);
                $('#js-meeting-box').hide(0);
                $('.js-address-text-row').hide(0);
                $('#js-address-input-box').hide(0);
                $('.js-address-hr').hide(0);
                // Updated on: 01-07-2019
                $('.js-extra-participants-wrap').hide(0);
                $('.js-extra-participants-hr').hide(0);
                $('.js-participants-wrap').hide(0);
                $('#js-participants-box').hide(0);
                $('.js-participants-hr').hide(0);
                $('.js-event-detail-wrap').hide(0);
                $('.js-event-detail-wrap').next('hr').hide(0);
                $('#js-superadmin-box').hide(0);
                $('.js-superasmin-hr').hide(0);
                $('.js-person-email').hide(0);
                $('.js-person-phone').show(0);
                $('.js-event-title-wrap').hide(0);

                $('.js-person-name').show(0);
                $('#js-meeting-check').prop('checked', false);
                //
                $('.js-comment-text > span').text(personal_comment_text);
                $('#js-event-title-p').val(
                    $('#js-event-title').val().trim()
                );

                $('.js-clone-start-time').val(default_start_time);
                $('.js-clone-end-time').val(default_end_time);

                $('#js-participants-box').hide(0);

                $('.js-person-show-email-check').show();
                $('.js-person-send-notification-input').prop('checked', false);

                load_personal_pickers();
            } else if (reset_type == 'demo') {
                $('.js-personal-box').hide(0);
                $('#js-superadmin-box').hide(0);
                $('#js-demo-box').show(0);
                $('#js-user-select').select2({
                    closeOnSelect: false,
                    allowHtml: true,
                    allowClear: true,
                    tags: true
                });
                //
                $('#js-reminder-email-wrap').hide(0);
                $('.js-reminder-box').show(0);
                $('.js-meeting-wrap').hide(0);
                $('#js-meeting-box').hide(0);
                $('.js-address-text-row').hide(0);
                $('#js-address-input-box').hide(0);
                $('.js-address-hr').hide(0);
                $('.js-extra-participants-wrap').show(0);
                $('.js-extra-participants-hr').show(0);
                $('.js-participants-wrap').show(0);
                $('#js-participants-box').show(0);
                $('.js-participants-hr').show(0);
                $('.js-event-detail-wrap').hide(0);
                $('#js-superadmin-box').hide(0);
                $('.js-superasmin-hr').hide(0);
                $('.js-person-email').hide(0);
                $('.js-event-title-wrap').hide(0);

                $('.js-comment-text > span').text(demo_comment_text);

                $('.js-event-title-wrap').show(0);
                $('#js-event-date').show(0);
                $('#js-event-start-time').show(0);
                $('#js-event-end-time').show(0);
                $('.js-event-detail-wrap').show(0);

                $('.js-demo-category-wrap').show(0);
                $('.js-event-category-wrap').hide(0);

                $('#js-event-title').val(
                    $('#js-event-title-p').val().trim()
                );

                // load_personal_pickers();
            }

            if (reset_type == 'personal' || reset_type == 'demo') $('.js-comment-box').show(0);
            else $('.js-comment-box').hide(0);

            if (reset_type == 'training-session' || reset_type == 'meeting' || reset_type == 'other' || reset_type == 'gotomeeting') $('.js-comment-box').show(0);

            // For Admin Personal
            if (reset_type == 'email' && event_for == 'personal') {
                $('.js-person-phone').hide(0);
                $('.js-person-email').show(0);
                $('.js-meeting-wrap').hide(0);
            } else if (reset_type == 'call' && event_for == 'personal') {
                $('.js-person-phone').show(0);
                $('.js-person-email').hide(0);
                $('.js-meeting-wrap').hide(0);
                $('.js-person-show-email-check').show();
                $('.js-person-send-notification-input').prop('checked', false);
            } else if (reset_type == 'gotomeeting' && event_for == 'personal') {
                $('.js-meeting-wrap').show(0);
                $('.js-person-phone').hide(0);
                $('.js-person-name').hide(0);
                $('.js-person-email').hide(0);
                $('#js-meeting-check').prop('checked', true);
                $('#js-meeting-box').show(0);
                $('#js-participants-box').hide(0);


            } else if ((reset_type == 'training-session' || reset_type == 'meeting' || reset_type == 'other') && event_for == 'personal') {
                $('.js-meeting-wrap').hide(0);
                $('.js-person-phone').hide(0);
                $('.js-person-name').hide(0);
                $('.js-person-email').hide(0);
                $('#js-meeting-check').prop('checked', false);
                $('#js-meeting-box').hide(0);

                $('.js-address-text-row').show(0);
                $('#js-address-input-box').show(0);
                $('.js-address-hr').show(0);
                //
                $('.js-participants-wrap').show(0);
                $('#js-participants-box').show(0);
                $('.js-participants-hr').show(0);
                //
                $('.js-extra-participants-wrap').show(0);
                $('.js-extra-participants-hr').show(0);
            }

            //
            if (reset_type == 'gotomeeting') $('.js-meeting-hr').show(0);
            else $('.js-meeting-hr').hide(0);

            // For Super Admin Tab
            if (reset_type == 'gotomeeting' && event_for == 'super admin') {
                $('.js-meeting-wrap').show(0);
                $('#js-meeting-box').show(0);
                $('#js-meeting-check').prop('checked', true);
            } else if ((reset_type == 'training-session' || reset_type == 'meeting' || reset_type == 'other') && event_for == 'super admin') {
                $('.js-meeting-wrap').hide(0);
                $('.js-person-phone').hide(0);
                $('.js-person-name').hide(0);
                $('.js-person-email').hide(0);
                $('#js-meeting-check').prop('checked', false);
                $('#js-meeting-box').hide(0);

                $('.js-address-text-row').show(0);
                $('#js-address-input-box').show(0);
                $('.js-address-hr').show(0);
                //
                $('.js-participants-wrap').show(0);
                $('#js-participants-box').show(0);
                $('.js-participants-hr').show(0);
                //
                $('.js-extra-participants-wrap').show(0);
                $('.js-extra-participants-hr').show(0);
                $('#js-superadmin-box').hide(0);

                $('#js-participants-select').select2('val', <?= $admin_id ?>);
                $('.js_ps_row_' + <?= $admin_id ?> + '').show(0);
            }
            // For demo tab
            if (reset_type == 'gotomeeting' && event_for == 'demo') {
                $('.js-meeting-wrap').show(0);
                $('#js-meeting-box').show(0);
                $('#js-meeting-check').prop('checked', true);
            } else if (reset_type == 'demo' && event_for == 'demo') {
                $('.js-meeting-wrap').hide(0);
                $('#js-meeting-check').prop('checked', false);
                $('#js-meeting-box').hide(0);
            }

            // For training session text
            if (reset_type == 'training-session') {
                $('#attendees_label').text('Assigned Attendee(s)');
                $('#js-non-employee-heading').text('Assigned Non Employee Attendee(s)');
                $('.js_ps_row').hide(0);
                $('#js-participants-box').hide(0);
                all_option('add');
            }
            // For meeting and other and demo and gotomeeting
            if (reset_type == 'meeting' || reset_type == 'other' || reset_type == 'demo' || reset_type == 'gotomeeting') {
                $('#attendees_label').text('Participant(s)');
                $('#js-non-employee-heading').text('Non Employee Participant(s)');
                $('#js-participants-select').select2('val', <?= $admin_id; ?>);
                $('.js_ps_row_' + <?= $admin_id; ?> + '').show(0);
                $('#js-participants-box').show(0);
                all_option('remove');
            }


            if (reset_type == 'personal' || event_for == 'personal') {
                $('#js-participants-box').hide(0);
            }

            // For 'demo' type and 'gotomeeting' category
            if ((event_for === undefined || event_for == 'demo' || event_for == 'gotomeeting') && (reset_type == 'demo'))
                $('#js-external-users').html(get_external_users_row());


            // For 'super admin' type and 'gotomeeting' category
            if ((event_for == 'super admin' || event_for === undefined)) {
                // if((event_for == 'super admin') && (reset_type == 'gotomeeting')){
                $('#js-superadmin-select').prop('multiple', true).select2({
                    closeOnSelect: false,
                    allowHtml: true,
                    allowClear: true,
                });
                $('#js-external-admins').html(get_external_admin_row());
                $('.js-external-admin-wrap').show(0);
            } else {
                $('#js-superadmin-select').prop('multiple', false).select2({
                    closeOnSelect: false,
                    allowHtml: true,
                    allowClear: true,
                });
                $('#js-external-admins').html('');
                $('.js-external-admin-wrap').hide(0);
            }
        }

        //
        function loader(dis) {
            dis = dis == undefined ? false : dis;
            if (dis) {
                $('.btn').prop('disabled', true);
                $('.btn').addClass('disabled');
                $('.js-loader').show();
            } else {
                $('.btn').removeClass('disabled');
                $('.btn').prop('disabled', false);
                $('.js-loader').hide();
            }
        }

        // Loads note rows
        function load_notes() {
            var rows = '';
            $.each(event_obj, function(i, v) {
                if (i == 'interview' ||
                    i == 'interview-phone' ||
                    i == 'interview-voip'
                ) return true;
                rows += '<tr>';
                rows += '   <td style="background-color: ' + event_color_obj[i] + '"></td>';
                rows += '   <td><strong>' + v + '</strong></td>';
                rows += '</tr>';
                // if(i == 'call'){
                //     // For demo
                //     rows += '<tr>';
                //     rows += '   <td style="background-color: '+  event_color_obj['demo'] +'"></td>';
                //     rows += '   <td><strong>Demo</strong></td>';
                //     rows += '</tr>';
                // }
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
                selected = event_type.default_superadmin,
                arr = event_type.superadmin;
            target.html('');
            //
            switch (type !== undefined ? type : $('.js-users-type').val()) {
                case 'super admin':
                    selected = event_type.default_superadmin;
                    arr = event_type.superadmin;
                    break;
                case 'personal':
                    selected = event_type.default_personal;
                    arr = event_type.admin_personal;
                    break;
                case 'demo':
                    selected = event_type.default_demo;
                    arr = event_type.demo;
                    break;
            }
            // Sort array by asc
            arr = _.sortBy(arr);
            var cls = (target_OBJ === undefined ? target_OBJ : target_OBJ.sec) === undefined ? 'js-btn-category' : (target_OBJ.btnClass !== undefined ? target_OBJ.btnClass : 'js-btn-category-p');
            $.each(arr, function(i, v) {
                if (v == 'training-session')
                    rows += '<li><button type="button" data-id="' + v + '" class="' + cls + ' btn btn-default btn-block dropdown-btn cs-event-btn-' + v + ' training-session-tile">' + event_obj[v] + '</button></li>';
                else
                    rows += '<li><button type="button" data-id="' + v + '" class="' + cls + ' btn btn-default btn-block dropdown-btn cs-event-btn-' + v + ' training-session-tile">' + event_obj[v] + '</button></li>';
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

        // Load main page
        // datetime pickers
        function load_main_page_pickers() {
            // Load date calendar
            $(".datepicker").datepicker({
                dateFormat: 'mm-dd-yy',
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
        }

        // Create extra participants
        // row
        function get_extra_participants_row() {
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
            row += '            <label>&nbsp;</label>';
            row += '            <label class="control control--checkbox margin-top-20">';
            row += '                Show Email';
            row += '                <input name="ext_participants[0][show_email]" id="ext_participants_0_show_email" class="external_participants_show_email" value="1"  type="checkbox" />';
            row += '                <div class="control__indicator"></div>';
            row += '            </label>';
            row += '        </div>';
            row += '    </div>';
            row += '    <div class="col-lg-1 col-md-1 col-xs-12 col-sm-1">';
            row += '         <label>&nbsp;</label>';
            row += '        <button id="btn_add_participant" type="button" class="btn btn-success btn-equalizer btn-block margin-top-20"><i class="fa fa-plus-square"></i></button>';
            row += '    </div>';
            row += '</div>';
            return row;
        }

        // Create external users
        function get_external_users_row() {
            var row = '';
            row += '<div id="external_user_0" class="row external_users">';
            row += '    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">';
            row += '        <div class="form-group">';
            row += '            <label>Name</label>';
            row += '            <input name="external_users[0][name]" id="external_users_0_name" type="text" class="form-control external_users_name" />';
            row += '        </div>';
            row += '    </div>';
            row += '    <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">';
            row += '        <div class="form-group">';
            row += '            <label>Email</label>';
            row += '            <input name="external_users[0][email]" id="external_users_0_email" type="email" class="form-control external_users_email" />';
            row += '        </div>';
            row += '    </div>';
            row += '    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">';
            row += '        <div class="form-group">';
            row += '            <label>&nbsp;</label>';
            row += '            <label class="control control--checkbox margin-top-20">';
            row += '                Show Email';
            row += '                <input name="external_users[0][show_email]" id="external_users_0_show_email" class="external_users_show_email" value="1"  type="checkbox" />';
            row += '                <div class="control__indicator"></div>';
            row += '            </label>';
            row += '        </div>';
            row += '    </div>';
            row += '    <div class="col-lg-1 col-md-1 col-xs-12 col-sm-1">';
            row += '         <label>&nbsp;</label>';
            row += '        <button id="btn_add_user" type="button" class="btn btn-success btn-equalizer btn-block margin-top-20"><i class="fa fa-plus-square"></i></button>';
            row += '    </div>';
            row += '</div>';
            return row;
        }


        // Create external admins
        function get_external_admin_row() {
            var row = '';
            row += '<div id="external_admin_0" class="row external_admin">';
            row += '    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">';
            row += '        <div class="form-group">';
            row += '            <label>Name</label>';
            row += '            <input name="external_admins[0][name]" id="external_admins_0_name" type="text" class="form-control external_admins_name" />';
            row += '        </div>';
            row += '    </div>';
            row += '    <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">';
            row += '        <div class="form-group">';
            row += '            <label>Email</label>';
            row += '            <input name="external_admins[0][email]" id="external_admins_0_email" type="email" class="form-control external_admins_email" />';
            row += '        </div>';
            row += '    </div>';
            row += '    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">';
            row += '        <div class="form-group">';
            row += '            <label>&nbsp;</label>';
            row += '            <label class="control control--checkbox margin-top-20">';
            row += '                Show Email';
            row += '                <input name="external_admins[0][show_email]" id="external_admin_0_show_email" class="external_admins_show_email" value="1"  type="checkbox" />';
            row += '                <div class="control__indicator"></div>';
            row += '            </label>';
            row += '        </div>';
            row += '    </div>';
            row += '    <div class="col-lg-1 col-md-1 col-xs-12 col-sm-2">';
            row += '         <label>&nbsp;</label>';
            row += '        <button id="btn_add_admin" type="button" class="btn btn-success btn-equalizer btn-block margin-top-20"><i class="fa fa-plus-square"></i></button>';
            row += '    </div>';
            row += '</div>';
            return row;
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

        // Remove 'all' from participants
        // list
        function remove_all_from_participants() {
            // var vals = $('#js-participants-select').val();
            var vals = null;
            $('#js-participants-select').val(vals).trigger('change');
            // vals.remove('all');
            $('#js-participants-select option[value="all"]').remove();
            // $('#js-participants-list').html('');

            $('#js-participants-select option').prop('disabled', false);
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
                //
                if (selected_interviewers.length > 1) {
                    selected_interviewers.remove('all');
                    $('#js-interviewers-select').select2('val', selected_interviewers);
                }
            }
        }

        // Load personal view
        // @param is_load
        // Check for first time load
        function load_personal_view() {
            load_categories('personal', {
                list: '.js-category-list-p',
                cd: '.js-category-dropdown-p',
                sec: '.js-selected-event-category-p',
                ac: '.js-active-category-p',
                selected: 'call'
            });
        }

        //
        function load_personal_pickers() {
            // Datepicker for personal type
            $('.js-datepicker').datepicker({
                dateFormat: 'mm-dd-yy',
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
        }

        //
        function load_demo_view() {
            load_categories('demo', {
                list: '.js-demo-category-list',
                cd: '.js-demo-category-dropdown',
                sec: '.js-selected-demo-category',
                ac: '.js-demo-active-category',
                btnClass: 'js-demo-category-btn',
                selected: 'demo'
            });
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
            // Do this before you initialize any of your modals
            $.fn.modal.Constructor.prototype.enforceFocus = function() {};
            $('#js-event-date').val(moment(date, site_date_format).format(site_date_format));
            $('#js-event-start-time').val(default_start_time);
            $('#js-event-end-time').val(default_end_time);

            //
            $('.js-datepicker').val($('#js-event-date').val());
            $('.js-clone-start-time').val($('#js-event-start-time').val());
            $('.js-clone-end-time').val($('#js-event-end-time').val());
            $('#js-event-modal').modal('show');
            loader(false);
        }

        // Get employee index               
        function get_ie_obj(admin_id) {
            var return_value = false;
            $.each(admin_list, function(i, v) {
                if (admin_id == v.admin_id) {
                    return_value = v;
                    return false;
                }
            });

            return return_value;
        }

        // Create rows for selected extra users
        // Occurs only on update event
        // @param extra_users
        // Holds the data of extra users
        function generate_extra_users(extra_users) {
            var rows = '';
            //
            $.each(extra_users, function(i, v) {
                var rand_id = Math.floor((Math.random() * 1000) + 1);
                rows += '<div id="external_users_' + i + '" class="row external_users">';
                rows += '    <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">';
                rows += '        <div class="form-group">';
                rows += '            <label>Name</label>';
                rows += '            <input name="external_users_[' + i + '][name]" id="external_users_' + i + '_name" type="text" class="form-control external_users_name" value="' + v.name + '" />';
                rows += '        </div>';
                rows += '    </div>';
                rows += '    <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">';
                rows += '        <div class="form-group">';
                rows += '            <label>Email</label>';
                rows += '            <input name="external_users_[' + i + '][email]" id="external_users_' + i + '_email" type="email" class="form-control external_users_email" value="' + v.email_address + '" />';
                rows += '        </div>';
                rows += '    </div>';
                rows += '    <div class="col-lg-1 col-md-1 col-xs-12 col-sm-1">';
                rows += '        <div class="form-group">';
                rows += '            <label>&nbsp;</label>';
                rows += '            <label class="control control--checkbox margin-top-20">';
                rows += '                Show Email';
                rows += '                <input name="external_users[' + i + '][show_email]" id="external_users_' + i + '_show_email" class="external_users_show_email" value="1" ' + (v.show_email == 1 ? 'checked="true"' : '') + '  type="checkbox" />';
                rows += '                <div class="control__indicator"></div>';
                rows += '            </label>';
                rows += '        </div>';
                rows += '    </div>';

                rows += '    <div class="col-lg-1 col-md-1 col-xs-12 col-sm-1">';
                rows += '        <div class="form-group">';
                rows += '            <label>&nbsp;</label>';
                if (i == 0)
                    rows += '<button id="btn_add_user" type="button" class="btn btn-success btn-equalizer btn-block margin-top-20"><i class="fa fa-plus-square"></i></button>';
                else
                    rows += '<button  type="button" class="btn btn-danger btn-equalizer btn_remove_user btn-block margin-top-20"><i class="fa fa-plus-square fa-trash" data-id="' + rand_id + '"></i></button>';
                rows += '        </div>';
                rows += '    </div>';
                rows += '</div>';
            });
            $('#js-external-users').html(rows);
        }

        // Create rows for selected extra admins
        // Occurs only on update event
        // @param extra_admins
        // Holds the data of extra admins
        function generate_extra_admins(extra_users) {
            var rows = '';
            //
            $.each(extra_users, function(i, v) {
                var rand_id = Math.floor((Math.random() * 1000) + 1);
                rows += '<div id="external_admin_' + i + '" class="row external_admin">';
                rows += '    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">';
                rows += '        <div class="form-group">';
                rows += '            <label>Name</label>';
                rows += '            <input name="external_admins_[' + i + '][name]" id="external_admins_' + i + '_name" type="text" class="form-control external_admins_name" value="' + v.name + '" />';
                rows += '        </div>';
                rows += '    </div>';
                rows += '    <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">';
                rows += '        <div class="form-group">';
                rows += '            <label>Email</label>';
                rows += '            <input name="external_admins_[' + i + '][email]" id="external_admins_' + i + '_email" type="email" class="form-control external_admins_email" value="' + v.email_address + '" />';
                rows += '        </div>';
                rows += '    </div>';
                rows += '    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">';
                rows += '        <div class="form-group">';
                rows += '            <label>&nbsp;</label>';
                rows += '            <label class="control control--checkbox margin-top-20">';
                rows += '                Show Email';
                rows += '                <input name="external_admins[' + i + '][show_email]" id="external_admin_' + i + '_show_email" class="external_admins_show_email" value="1" ' + (v.show_email == 1 ? 'checked="true"' : '') + '  type="checkbox" />';
                rows += '                <div class="control__indicator"></div>';
                rows += '            </label>';
                rows += '        </div>';
                rows += '    </div>';

                rows += '    <div class="col-lg-1 col-md-1 col-xs-12 col-sm-1">';
                rows += '        <div class="form-group">';
                rows += '            <label>&nbsp;</label>';
                if (i == 0)
                    rows += '<button id="btn_add_admin" type="button" class="btn btn-success btn-equalizer btn-block margin-top-20"><i class="fa fa-plus-square"></i></button>';
                else
                    rows += '<button  type="button" class="btn btn-danger btn-equalizer btn_remove_admin btn-block margin-top-20"><i class="fa fa-plus-square fa-trash" data-id="' + rand_id + '"></i></button>';
                rows += '        </div>';
                rows += '    </div>';
                rows += '</div>';
            });
            //
            $('#js-external-admins').html(rows);
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

        // Get added external users
        function external_users_data(do_stringify) {
            var names = $('.external_users_name'),
                emails = $('.external_users_email'),
                show_emails = $('.external_users_show_email'),
                data = [];
            // 
            for (iCount = 0; iCount < names.length; iCount++) {
                if ($(names[iCount]).val().trim() != '' && $(emails[iCount]).val().trim() != '') {
                    data.push({
                        'name': $(names[iCount]).val().trim(),
                        'email': $(emails[iCount]).val().trim(),
                        'show_email': $(show_emails[iCount]).prop('checked') == true ? 1 : 0
                    });
                }
            }

            return do_stringify === true ? JSON.stringify(data) : data;
        }


        // Get added external admins
        function external_admins_data(do_stringify) {
            var names = $('.external_admins_name'),
                emails = $('.external_admins_email'),
                show_emails = $('.external_admins_show_email'),
                data = [];
            // 
            for (iCount = 0; iCount < names.length; iCount++) {
                if ($(names[iCount]).val().trim() != '' && $(emails[iCount]).val().trim() != '') {
                    data.push({
                        'name': $(names[iCount]).val().trim(),
                        'email': $(emails[iCount]).val().trim(),
                        'show_email': $(show_emails[iCount]).prop('checked') == true ? 1 : 0
                    });
                }
            }

            return do_stringify === true ? JSON.stringify(data) : data;
        }

        // Event validation
        // @param action of the event
        function event_validation(action) {
            // Reset last indexes for regex
            phone_regex.lastIndex = email_regex.lastIndex = digit_regex.lastIndex = url_regex.lastIndex = 0;
            var event_type = $('.js-users-type:checked').val().toLowerCase(),
                event_category = event_type == 'super admin' ? $('.js-selected-event-category').val().trim() : (
                    event_type == 'demo' ? $('.js-selected-demo-category').val().trim() : $('.js-selected-event-category-p').val().trim()
                ),
                mega_obj = {
                    'action': action === undefined ? 'save' : action,
                    'event_sid': '',
                    'creator_sid': <?= $admin_id; ?>,
                    'event_title': '',
                    'event_type': event_type,
                    'event_category': event_category,
                    'event_date': '',
                    'event_date_bk': '',
                    'event_start_time': '',
                    'event_end_time': '',
                    'event_address': '',
                    'comment': '',
                    'meeting_id': '',
                    'meeting_phone': '',
                    'meeting_url': '',
                    'user_id': '',
                    'user_type': '',
                    'user_name': '',
                    'user_email': '',
                    'user_phone': '',
                    'participants': '',
                    'participants_show_email': '',
                    'external_participants': '',
                    'external_user_array': '',
                    'user_ids': '',
                    'reminder_check': 0,
                    'reminder_duration': 15
                },
                include_participants = true,
                include_users = false;
            if ((event_type == 'personal') && (event_category != 'meeting' && event_category != 'other' && event_category != 'training-session')) include_participants = false;
            if ((event_type == 'demo' || event_type == 'super admin') && (event_category != 'meeting' && event_category != 'other' && event_category != 'training-session')) include_users = true;
            // 
            if (action == 'update' || action == 'reschedule' || action == 'drag_update') mega_obj.event_sid = $('#js-event-id').val();
            // Check for admin id
            if (event_type == 'super admin' && (event_category != 'meeting' && event_category != 'other' && event_category != 'training-session')) {
                if ($('#js-superadmin-select').val() == 0) {
                    alertify.alert('Error!', 'Please, select an admin.', flush_cb);
                    return;
                }
                mega_obj.user_id = parseInt($('#js-superadmin-select').val());
            }

            // Check for user id
            if (event_type == 'demo' && include_users === true) {
                var external_users_data_array = external_users_data();
                if ($('#js-user-select').val() === null && external_users_data_array.length === 0) {
                    alertify.alert('Error!', 'Please, select a system user or enter details for external user.', flush_cb);
                    return;
                }

                // Set system users
                if ($('#js-user-select').val() !== null) {
                    var system_users_array = [];
                    $.each($('#js-user-select').val(), function(i, v) {
                        //
                        var u_id = v.split('-')[0];
                        var u_type = v.split('-')[1];
                        system_users_array.push({
                            id: u_id,
                            type: u_type
                        });
                    });

                    mega_obj.user_ids = system_users_array;
                    // mega_obj.user_ids = $('#js-user-select').val();
                    // mega_obj.user_type = get_user_details(mega_obj.user_id)['type'].toLowerCase();
                    // if(mega_obj.user_type == 'referred affiliate') mega_obj.user_type = 'affiliate';
                    // else if(mega_obj.user_type == 'referred affiliate client') mega_obj.user_type = 'affiliate client';
                }

                // Set external users
                if (external_users_data_array.length !== 0) {
                    var is_error = false;
                    $.each(external_users_data_array, function(i, v) {
                        email_regex.lastIndex = 0;
                        if (v.name.trim() != '' || v.email.trim() != '') {
                            if (v.name.trim() == '') {
                                alertify.alert("Name is missing for external user.( " + (++i) + " row ) ", flush_cb);
                                is_error = true;
                                return false;
                            }
                            if (v.email.trim() == '') {
                                alertify.alert("Email is missing for external user.( " + (++i) + " row ) ", flush_cb);
                                is_error = true;
                                return false;
                            }
                            v.email = v.email.toLowerCase();
                            if (!email_regex.test(v.email.trim())) {
                                alertify.alert("Invalid email is provided for external user.( " + (++i) + " row ) ", flush_cb);
                                is_error = true;
                                return false;
                            }
                        }
                    });

                    if (is_error) return;
                    //
                    mega_obj.external_user_array = JSON.stringify(external_users_data_array);
                }
            }
            // Run for both superadmin and demo
            if (event_type == 'super admin' || event_type == 'demo') {
                // Check for event title
                if ($('#js-event-title').val().trim() == '') {
                    alertify.alert('Error!', 'Please, provide an event title.', flush_cb);
                    return;
                }
                mega_obj.event_title = $('#js-event-title').val().trim();
                // Check for date
                if ($('#js-event-date').val().trim() == '') {
                    alertify.alert('Error!', 'Please, provide an event date.', flush_cb);
                    return;
                }
                mega_obj.event_date = $('#js-event-date').val().trim();
                // Check for start time
                if ($('#js-event-start-time').val().trim() == '') {
                    alertify.alert('Error!', 'Please, provide an event start time.', flush_cb);
                    return;
                }
                mega_obj.event_start_time = $('#js-event-start-time').val().trim();
                // Check for end time
                if ($('#js-event-end-time').val().trim() == '') {
                    alertify.alert('Error!', 'Please, provide an event end time.', flush_cb);
                    return;
                }
                mega_obj.event_end_time = $('#js-event-end-time').val().trim();
            }

            // Run for personal
            if (event_type == 'personal') {
                // Check for event title
                if ($('#js-event-title-p').val().trim() == '') {
                    alertify.alert('Error!', 'Please, provide an event title.', flush_cb);
                    return;
                }
                mega_obj.event_title = $('#js-event-title-p').val().trim();
                // Check for date
                if ($('.js-datepicker').val().trim() == '') {
                    alertify.alert('Error!', 'Please, provide an event date.', flush_cb);
                    return;
                }
                mega_obj.event_date = $('.js-datepicker').val().trim();
                // Check for start time
                if ($('.js-clone-start-time').val().trim() == '') {
                    alertify.alert('Error!', 'Please, provide an event start time.', flush_cb);
                    return;
                }
                mega_obj.event_start_time = $('.js-clone-start-time').val().trim();
                // Check for end time
                if ($('.js-clone-start-time').val().trim() == '') {
                    alertify.alert('Error!', 'Please, provide an event end time.', flush_cb);
                    return;
                }
                mega_obj.event_end_time = $('.js-clone-end-time').val().trim();
            }

            // Check for comment
            if ($('#js-comment-check').prop('checked') === true && $('#js-comment-msg').val().trim() == '') {
                alertify.alert('Error!', 'A comment is missing. If you don\'t want to add a comment please uncheck "Comment" check.', flush_cb);
                return;
            } else mega_obj.comment = $('#js-comment-msg').val().trim();

            // Check for gotomeeting
            if (mega_obj.event_category == 'gotomeeting') {
                phone_regex.lastIndex = 0;
                // Check for meeting id
                if ($('#js-meeting-call').val().trim() == '') {
                    alertify.alert('Error!', 'The call field is missing.', flush_cb);
                    return;
                }
                mega_obj.meeting_phone = $('#js-meeting-call').val().trim();
                // Check for meeting id
                if ($('#js-meeting-id').val().trim() == '') {
                    alertify.alert('Error!', 'The ID field is missing.', flush_cb);
                    return;
                }
                // Check for meeting id
                if (!phone_regex.test($('#js-meeting-call').val().trim())) {
                    alertify.alert('Error!', 'The meeting phone format is invalid!.', flush_cb);
                    return;
                }
                mega_obj.meeting_id = $('#js-meeting-id').val().trim();
                // Check for meeting id
                if ($('#js-meeting-url').val().trim() == '') {
                    alertify.alert('Error!', 'The URL field is missing.', flush_cb);
                    return;
                }
                // Check for meeting id
                if (!url_regex.test($('#js-meeting-url').val().trim())) {
                    alertify.alert('Error!', 'The URL is invalid.', flush_cb);
                    return;
                }
                mega_obj.meeting_url = $('#js-meeting-url').val().trim();
            }

            // Check for partipants
            if (include_participants) {
                // Get selected particpants
                var participants = $('#js-participants-select').val(),
                    external_participants = func_get_external_users_data();
                participants_show_email = [];
                // If no participants are selected 
                // then add current admin by default
                if (participants == null) {
                    participants = [];
                    participants.push(<?= $admin_id; ?>);
                    participants_show_email.push(<?= $admin_id; ?>);
                } else {
                    $.each(participants, function(i, el) {
                        if ($('.js_ps_row_' + el + '').find('input').prop('checked') === true) participants_show_email.push(el);
                    });
                }
                // Convert array to string
                mega_obj.participants = participants.join(',');
                mega_obj.participants_show_email = participants_show_email.join(',');

                // Check for address
                if ($('#js-address-text').val().trim() != '')
                    mega_obj.event_address = $('#js-address-text').val().trim();

                var ep = $.parseJSON(func_get_external_users_data());
                if (ep.length > 1) {
                    var is_error = false;
                    $.each(ep, function(i, v) {
                        email_regex.lastIndex = 0;
                        if (v.name.trim() != '' || v.email.trim() != '') {
                            if (v.name.trim() == '') {
                                alertify.alert("Name is missing for non-employee participants.( " + (++i) + " row ) ", flush_cb);
                                is_error = true;
                                return false;
                            }
                            if (v.email.trim() == '') {
                                alertify.alert("Email is missing for non-employee participants.( " + (++i) + " row ) ", flush_cb);
                                is_error = true;
                                return false;
                            }
                            v.email = v.email.toLowerCase();
                            if (!email_regex.test(v.email.trim())) {
                                alertify.alert("Invalid email is provided for non-employee participants.( " + (++i) + " row ) ", flush_cb);
                                is_error = true;
                                return false;
                            }
                        }
                    });

                    if (is_error) return false;
                } else if (ep.length == 1) {
                    if (ep[0].name.trim() != '' || ep[0].email.trim() != '') {
                        if (ep[0].name.trim() == '') {
                            alertify.alert("Name is missing for non-employee participants.( 1 row ) ", flush_cb);
                            return false;
                        }
                        if (ep[0].email.trim() == '') {
                            alertify.alert("Email is missing for non-employee participants.( 1 row ) ", flush_cb);
                            return false;
                        }
                        ep[0].email = ep[0].email.toLowerCase();
                        if (!email_regex.test(ep[0].email.trim())) {
                            alertify.alert("Invalid email is provided for non-employee participants.( 1 row ) ", flush_cb);
                            return false;
                        }
                    }
                }

                // Check for external pariticipants
                mega_obj.external_participants = external_participants;
            }

            // Check for user name
            if (event_type == 'personal' && (event_category != 'meeting' && event_category != 'other' && event_category != 'training-session' && event_category != 'gotomeeting')) {
                // Check person name
                if ($('.js-person-name input').val().trim() == '') {
                    alertify.alert('Error!', 'Please, provide person name.', flush_cb);
                    return;
                }
                mega_obj.user_name = $('.js-person-name input').val().trim();

                // Check person phone
                if (!phone_regex.test($('.js-person-phone input').val().trim()) && event_category == 'call') {
                    alertify.alert('Error!', 'Please, provide a valid phone number.', flush_cb);
                    return;
                } else mega_obj.user_phone = $('.js-person-phone input').val().trim();

                // Check person email
                if ($('.js-person-email input').val().trim() != '')
                    $('.js-person-email input').val($('.js-person-email input').val().trim().toLowerCase());
                if ($('.js-person-email input').val().trim() == '' && (event_category == 'email' || $('.js-person-send-notification-input').prop('checked') === true)) {
                    alertify.alert('Error!', 'Please, provide person email.', flush_cb);
                    return;
                } else if (($('.js-person-email input').val().trim() == '' || !email_regex.test($('.js-person-email input').val().trim())) && (event_category == 'email' || $('.js-person-send-notification-input').prop('checked') === true)) {
                    alertify.alert('Error!', 'Please, provide person email.', flush_cb);
                    return;
                } else mega_obj.user_email = $('.js-person-email input').val().trim();
                if ($('.js-person-send-notification-input').prop('checked') === false)
                    mega_obj.user_email = '';
            }

            // Check for reminder
            if ($('#js-reminder-check').prop('checked') === true) {
                mega_obj.reminder_check = 1;
                mega_obj.reminder_duration = parseInt($('#js-reminder-select').val());
            }

            // Convert date & times to bk
            mega_obj.event_start_time = moment(mega_obj.event_start_time, 'hh:mmA').format('hh:mm A');
            mega_obj.event_end_time = moment(mega_obj.event_end_time, 'hh:mmA').format('hh:mm A');
            mega_obj.event_date_bk = moment(mega_obj.event_date, site_date_format).format(site2_date_format);


            // Check for user id
            if (event_type == 'super admin' && include_users === true) {
                var external_admin_data_array = external_admins_data();
                if ($('#js-superadmin-select').val() === null && external_admin_data_array.length === 0) {
                    alertify.alert('Error!', 'Please, select a system user or enter details for external user.', flush_cb);
                    return;
                }

                // Set system users
                if ($('#js-superadmin-select').val() !== null) {
                    var system_users_array = [];
                    $.each($('#js-superadmin-select').val(), function(i, v) {
                        //
                        system_users_array.push({
                            id: v,
                            type: 'super admin'
                        });
                    });

                    mega_obj.user_ids = system_users_array;
                    // mega_obj.user_ids = $('#js-user-select').val();
                    // mega_obj.user_type = get_user_details(mega_obj.user_id)['type'].toLowerCase();
                    // if(mega_obj.user_type == 'referred affiliate') mega_obj.user_type = 'affiliate';
                    // else if(mega_obj.user_type == 'referred affiliate client') mega_obj.user_type = 'affiliate client';
                }
                mega_obj.external_user_array = "";
                // Set external users
                if (external_admin_data_array.length !== 0) {
                    var is_error = false;
                    $.each(external_admin_data_array, function(i, v) {
                        email_regex.lastIndex = 0;
                        if (v.name.trim() != '' || v.email.trim() != '') {
                            if (v.name.trim() == '') {
                                alertify.alert("Name is missing for external admin.( " + (++i) + " row ) ", flush_cb);
                                is_error = true;
                                return false;
                            }
                            if (v.email.trim() == '') {
                                alertify.alert("Email is missing for external admin.( " + (++i) + " row ) ", flush_cb);
                                is_error = true;
                                return false;
                            }
                            v.email = v.email.toLowerCase();
                            if (!email_regex.test(v.email.trim())) {
                                alertify.alert("Invalid email is provided for external admin.( " + (++i) + " row ) ", flush_cb);
                                is_error = true;
                                return false;
                            }
                        }
                    });

                    if (is_error) return;
                    //
                    mega_obj.external_user_array = JSON.stringify(external_admin_data_array);
                }
            }

            mega_obj.user_phone = '+1' + (mega_obj.user_phone.replace(/\D/g, ''));

            // Get the difference on uodate
            if (action == 'update' || action == 'drag_update') mega_obj.diff = JSON.stringify(get_difference_of_event(mega_obj));

            return mega_obj;
        }

        //
        function flush_cb() {
            return;
        }

        // Process event
        function process_event(obj, type) {
            loader(true);
            // Abort previous AJAX request
            if (event_XHR !== null) event_XHR.abort();
            // Make a AJAX request
            event_XHR = $.post("<?= base_url('manage_admin/process-event'); ?>", obj, function(resp) {
                loader(false);
                if (resp.Status === true) {
                    // Add event to calendar
                    if (obj.action == 'save') event_save_info(obj, resp.EventCode);
                    else if (obj.action == 'update') event_update_info(obj, $('#js-event-id').val());
                    else $('#js-event-modal').modal('hide');
                    // TODO
                    // Edit eveent on calendar
                }
                alertify.alert(resp.Status === false ? 'Error!' : 'Success', resp.Response, flush_cb);
                return;
                event_XHR = null;
                loader(false);
            });
        }

        // Re-render the event
        // triggers when a new event is added
        // @param new_event
        // Holds the info. of new event
        // @param event_sid
        // event id for new event
        function event_save_info(new_event, event_sid) {
            var prev_event = {},
                title_name = '',
                is_editable = true;

            prev_event.date = moment(moment(new_event.event_date, site_date_format).format(site2_date_format));
            prev_event.creator_id = new_event.creator_sid;
            prev_event.event_category = new_event.event_category;
            prev_event.color = event_color_obj[new_event.event_category];
            // Check for expired event
            if (moment(new_event.event_date + ' 23:59:59', site_date_format) < moment().utc()) {
                calendar_ref.find(new_event._id).switchClass('fc-event-cc-pending', 'fc-event-cc-expired');
                prev_event.expired_status = true;
            }
            //
            prev_event.event_sid = event_sid;
            prev_event.new_event_requests = prev_event.event_requests = 0;
            prev_event.editable = is_editable;
            //
            prev_event.status = prev_event.event_status = prev_event.last_status = 'pending';
            // 
            prev_event.event_start_time = moment(new_event.event_date, site_date_format).format(site2_date_format) + ' ' + convert_12_to24(new_event.event_start_time);
            prev_event.event_end_time = moment(new_event.event_date, site_date_format).format(site2_date_format) + ' ' + convert_12_to24(new_event.event_end_time);
            //
            prev_event.start = moment(prev_event.event_start_time);
            prev_event.end = moment(prev_event.event_end_time);
            //
            prev_event.is_recur = 0;
            prev_event.new_event_requests = 0;
            // Handle Recur event
            // TODO
            // var recur = JSON.parse(new_event.recur);
            // //
            // if(recur.hasOwnProperty('list')){
            //     prev_event.is_recur = 1;
            //     prev_event.recur_type = recur.recur_type;
            //     prev_event.recur_start_date = recur.recur_start_date;
            //     prev_event.recur_end_date = recur.recur_end_date;
            //     prev_event.recur_list = JSON.stringify( recur.list );
            //     prev_event.dow = recur.list.Days;
            // }
            //
            if (new_event.participants != '') {
                if (new_event.participants.length == 0) {
                    new_event.participants.push("<?= $admin_id; ?>");
                    new_event.participants_show_email = "<?= $admin_id; ?>";
                }
            }
            //
            prev_event.participants = new_event.participants;
            prev_event.participants_show_email = new_event.participants_show_email;

            prev_event.title = new_event.event_title;
            prev_event.event_date = moment(new_event.event_date, site_date_format).format(site2_date_format);
            calendar_ref.fullCalendar('renderEvent', prev_event);
            $('#js-event-modal').modal('hide');
            $('body').removeClass('ajs-no-overflow');


            // if(new_event.users_type != 'personal'){
            //     if( new_event.users_type == 'applicant')
            //         title_name += $('#js-'+( new_event.users_type != 'employee' ? 'applicant' : 'employee' )+'-input').val().replace(/ *\([^)]*\) */g, '');
            //     else
            //         title_name += get_ie_obj(new_event.applicant_sid)['full_name'];
            //     var category = prev_event.category.charAt(0).toUpperCase() + prev_event.category.slice(1);
            //     category.replace('-', ' ');
            //     var category = prev_event.category.charAt(0).toUpperCase() + prev_event.category.slice(1);
            //     category.replace('-', ' ');
            //     title_name += ' - '+category;
            // }else
            //     title_name += 'Personal Appointment';

            // // title_name += ' - '+$(new_event.users_type == 'personal' ? '.js-active-category-p' : '#js-active-category').text();
            // title_name += ', '+new_event.title;
            // title_name += ', Interviewers(s): ';
            // //
            // if(interviewers_list_name_array.length > 0) title_name += interviewers_list_name_array.toString();
            // //    
            // prev_event.title = title_name;

            // updates event on calendar
            // calendar_ref.fullCalendar('renderEvent', prev_event);
            // $('#js-event-modal').modal('hide');
            // $('body').removeClass('ajs-no-overflow');
        }

        // Re-render the event on update
        // @param new_obj
        // @param event_sid
        function event_update_info(new_obj, event_sid) {
            // Get old event
            var old_obj = find_event(event_sid);
            // Set date/times
            old_obj.event_date = moment(new_obj.event_date, site_date_format).format(site2_date_format);
            old_obj.start = moment((moment(new_obj.event_date, site_date_format).format(site2_date_format)) + 'T' + (moment(new_obj.event_start_time, 'hh:mm A').format('HH:mm:ss')), site2_date_format + 'THH:mm:ss');
            old_obj.end = moment((moment(new_obj.event_date, site_date_format).format(site2_date_format)) + 'T' + (moment(new_obj.event_end_time, 'hh:mm A').format('HH:mm:ss')), site2_date_format + 'THH:mm:ss');

            //set color
            old_obj.color = event_color_obj[new_obj.event_category];

            // Set event category
            old_obj.event_category = new_obj.event_category;
            // Set event title
            old_obj.title = old_obj.event_title = new_obj.event_title;

            // Check for expired
            if (moment(old_obj.event_date, site_date_format) < moment().utc()) {
                old_obj.last_status = old_obj.status;
                old_obj.expired_status = true;
            }

            // TODO Recur

            calendar_ref.fullCalendar('updateEvent', old_obj);
            $('#js-event-modal').modal('hide');
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
            // minutes = minutes.trim();
            //
            if (AMPM == "PM" && hours < 12) hours = hours + 12;
            if (AMPM == "AM" && hours == 12) hours = hours - 12;
            var sHours = hours.toString();
            var sMinutes = minutes.toString();
            if (hours < 10) sHours = "0" + sHours;
            if (minutes < 10) sMinutes = "0" + sMinutes;
            return sHours + ":" + sMinutes + ":00";
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

        // Triggers when event tab is clicked
        // @param e Event Details
        function edit_event(e) {
            event_blinker('clearBtnInterval');
            if (e.event_sid === undefined) {
                alertify.alert('This event is no longer available.', flush_cb);
                loader();
                return false;
            }
            // send ajax request
            $.get("<?= base_url(); ?>manage_admin/event-detail/" + e.event_sid + "", function(resp) {
                // Load edit view
                load_edit_view(resp.Event);
            });
        }

        // Loads modal view on edit
        // @param e Event details from DB
        function load_edit_view(e) {
            e.event_type = e.event_type.toLowerCase();
            // Reset modal to type view
            reset_modal(e.event_type);
            //
            if ((e.event_type == 'super admin'))
                $('.js-btn-category[data-id="' + e.event_category + '"]').trigger('click');
            // Fill data for type 'demo'
            if (e.event_type == 'demo') {
                load_demo_view();
                $('.js-demo-category-btn[data-id="' + e.event_category + '"]').trigger('click');
            }
            // Check for personal type
            if (e.event_type == 'personal') {
                load_personal_view();
                $('.js-btn-category-p[data-id="' + e.event_category + '"]').trigger('click');
            }
            // Fill data for type 'personal' and category 'email'
            if (e.event_type == 'personal' && e.event_category == 'email') {
                $('.js-person-name input').val(e.user_name);
                $('.js-person-email input').val(e.user_email);
            }
            // Fill data for type 'personal' and category 'call'
            if (e.event_type == 'personal' && e.event_category == 'call') {
                $('.js-person-name input').val(e.user_name);
                var tmp = fpn(e.user_phone.replace('+1', ''));
                $('.js-person-phone input').val(typeof(tmp) === 'object' ? tmp.number : tmp);
                //
                if (e.user_email != '' && e.user_email != null) {
                    $('.js-person-show-email-check').show();
                    $('.js-person-send-notification-input').prop('checked', true);
                    $('.js-person-email').show();
                    $('.js-person-email input').val(e.user_email);
                }
            }
            // Fill data for type 'personal' or 'demo' and category 'gotomeeting'
            if ((e.event_type == 'personal' || e.event_type == 'demo' || e.event_type == 'super admin') && e.event_category == 'gotomeeting') {
                $('#js-meeting-call').val(e.meeting_phone);
                $('#js-meeting-id').val(e.meeting_id);
                $('#js-meeting-url').val(e.meeting_url);
            }
            // Fill data for type 'super admin'
            if (e.event_type == 'super admin') $('#js-superadmin-select').select2('val', e.user_id);

            // Fill data for category 'meeting', 'training-session', 'other'
            // if(e.event_category == 'meeting' || e.event_category == 'training-session' || e.event_category == 'other'){
            if (e.participants.length != 0 && e.event_type != 'personal') {
                var selected_users = [],
                    selected_users_list = e.participants_show_email_list != '' && e.participants_show_email_list != null ? e.participants_show_email_list.split(',') : [];
                if (e.participants != 'all') {
                    // For TS only
                    if (e.event_category == 'training-session' && admin_list.length == e.participants.length) {
                        selected_users.push('all');
                    } else {
                        $('.js_ps_row').hide(0);
                        $.each(e.participants, function(i, v) {
                            selected_users.push(v.id);
                            $('.js_ps_row_' + v.id + '').show();
                            if (_.findIndex(selected_users_list, v.id) !== -1)
                                $('.js_ps_row_' + v.id + ' input').prop('checked', true);
                        });
                    }
                } else selected_users.push('all');
                // Users
                $('#js-participants-select').select2('val', selected_users);
            }

            // External Users
            if (e.external_participants.length != 0)
                generate_extra_interviewers(e.external_participants);
            // }
            // Add 'All' option in case of 'training-session'
            // if(e.event_category == 'training-session') all_option('add', undefined, false);

            // Set the event type
            if (e.event_type == 'super admin') {
                $('#js-user-type-super-admin').prop('checked', true);
            } else if (e.event_type.toLowerCase() == 'personal') {
                $('#js-user-type-personal').prop('checked', true);
            } else if (e.event_type.toLowerCase() == 'demo') {
                $('#js-user-type-demo').prop('checked', true);
            }

            // Set the event title
            $('#js-event-title').val(e.event_title);
            $('#js-event-title-p').val(e.event_title);

            // Set the event date
            $('.js-datepicker').val(moment(e.event_date, site2_date_format).format(site_date_format));
            $('#js-event-date').val(moment(e.event_date, site2_date_format).format(site_date_format));

            // Set the event start time
            $('#js-event-start-time').val(e.event_start_time.replace(/\s+/, ''));
            $('.js-clone-start-time').val(e.event_start_time.replace(/\s+/, ''));
            $('#js-event-end-time').val(e.event_end_time.replace(/\s+/, ''));
            $('.js-clone-end-time').val(e.event_end_time.replace(/\s+/, ''));

            // Set event address
            if (e.event_address != null || e.event_address != '') $('#js-address-text').val(e.event_address);

            // Check and set the comment
            if (e.comment != '' && e.comment != null) {
                $('#js-comment-msg').val(e.comment);
                $('#js-comment-msg-box').show();
                $('#js-comment-check').prop('checked', true);
            }
            // Check and set the reminder
            if (e.reminder_check != '0') {
                $('#js-reminder-select').val(e.reminder_duration);
                $('#js-reminder-box').show();
                $('#js-reminder-check').prop('checked', true);
            }

            // 
            $('.js-modal-btn').hide(0);
            // Reset id
            $('#js-expired-reschedule').prop('id', 'js-reschedule');

            // For cancelled
            if (e.event_status == 'cancelled') {
                $('#js-reschedule').show(0);
            } else {
                $('#js-update').show(0);
                $('#js-cancel').show(0);
                $('#js-delete').show(0);
            }
            $('.js-users-type').prop("disabled", true);
            var is_event_expired = false,
                super_admin_obj = {};

            // Check for expired event
            if (moment(e.event_date + time_24) < moment().utc()) {
                $('#cancelled_message').html('Event expired!').show();
                $('.js-modal-btn').hide(0);
                $('#js-reschedule').show(0);
                $('#js-close').show();
                is_event_expired = true;
            }
            // Set 'super admin' reminder email
            if (e.event_type == 'super admin' && (e.event_category != 'training-session' && e.event_category != 'meeting' && e.event_category != 'other')) {
                super_admin_obj = {
                    id: e.user_id,
                    type: 'admin',
                    name: e.first_name + ' ' + e.last_name,
                    email_address: e.email_address
                };
            }

            if (e.event_type == 'personal' && (e.event_category != 'training-session' && e.event_category != 'meeting' && e.event_category != 'other' && e.event_category != 'gotomeeting') && e.user_email != '' && e.user_email != null) {
                super_admin_obj = {
                    id: 0,
                    name: e.user_name,
                    type: 'person',
                    email_address: e.user_email
                };
            }
            // Only show send reminder email option
            // when event is not expired
            // and there are particpants
            // and 'personal' type is not selected
            if (is_event_expired === false &&
                ((e.participants.length != 0 && typeof(e.participants[0]['value']) !== 'undefined') || e.external_participants.length != 0 || Object.size(super_admin_obj) != 0)) {
                // Send reminder emails options
                generate_reminder_email_rows(
                    e.participants,
                    e.external_participants,
                    super_admin_obj,
                    e.users_array != '' ? e.users_array : [],
                    e.external_users,
                    e.event_type
                );
                // Hides the reminder email box
                $('#js-reminder-email-box').hide(0);
                // Shows the reminder email wrap
                $('#js-reminder-email-wrap').show(0);
            }

            // Cancellation message
            if (e.event_status == 'cancelled') $('#cancelled_message').show(0).text('Event cancelled!');
            // Expired message
            if (is_event_expired === true) $('#cancelled_message').show(0).text('Event expired!');
            // Disable inputs, textrea, button... 
            // when event is expired
            if (is_event_expired === true || e.event_status == 'cancelled') {
                $('#js-event-modal').find('input, textarea, select, button:not(.close)').prop('disabled', true);
                $('#js-reschedule').prop('disabled', false);
            }
            // Set for reshedule expired
            if (is_event_expired === true) {
                $('#js-reschedule').prop('id', 'js-expired-reschedule');
            }

            // Set Parent, Request and Status Buttons
            load_extra_buttons(
                e.parent_event_sid,
                e.event_requests,
                e.event_reminder_email_history
            );

            // Blinker check
            if (e.new_event_requests != 0) {
                $('.js-status-history-btn').addClass('js-status-btn-blinker');
                $('.fc-event-' + e.event_sid + '').addClass('fc-event-blink');
                event_blinker('button');
            }

            $('#js-event-id').val(e.event_sid);

            loader();

            // Save a copy of fetched data
            save_event_locally(e, e.event_sid);

            // Set external users
            if (e.external_users.length !== 0 && e.event_type == 'demo') generate_extra_users(e.external_users);
            if (e.external_users.length !== 0 && e.event_type == 'super admin') generate_extra_admins(e.external_users);
            // Set system users
            if (e.users_array.length !== 0) {
                var tmp = [];
                $.each(e.users_array, function(i, v) {
                    if (e.event_type == 'demo')
                        tmp.push(v.id + '-' + v.type);
                    else
                        tmp.push(v.id);
                });
                $(e.event_type == 'super admin' ? '#js-superadmin-select' : '#js-user-select').select2('val', tmp);
            }

            // Do this before you initialize any of your modals
            $.fn.modal.Constructor.prototype.enforceFocus = function() {};
            $('#js-event-modal').modal('show');
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
                rows += '            <input name="ext_participants[' + i + '][name]" id="ext_participants_' + i + '_name" type="text" class="form-control external_participants_name" value="' + v.external_participant_name + '" />';
                rows += '        </div>';
                rows += '    </div>';
                rows += '    <div class="col-lg-5 col-md-5 col-xs-12 col-sm-6">';
                rows += '        <div class="form-group">';
                rows += '            <label>Email</label>';
                rows += '            <input name="ext_participants[' + i + '][email]" id="ext_participants_' + i + '_email" type="email" class="form-control external_participants_email" value="' + v.external_participant_email + '" />';
                rows += '        </div>';
                rows += '    </div>';
                rows += '    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">';
                rows += '        <div class="form-group">';
                rows += '            <label>&nbsp;</label>';
                rows += '            <label class="control control--checkbox margin-top-20">';
                rows += '                Show Email';
                rows += '                <input name="ext_participants[' + i + '][show_email]" ' + (v.show_email == 1 ? 'checked="checked"' : '') + ' id="ext_participants_' + i + '_show_email" class="external_participants_show_email" value="1"  type="checkbox" />';
                rows += '                <div class="control__indicator"></div>';
                rows += '            </label>';
                rows += '        </div>';
                rows += '    </div>';
                rows += '    <div class="col-lg-1 col-md-1 col-xs-12 col-sm-1">';
                rows += '            <label>&nbsp;</label>';
                if (i == 0)
                    rows += '<button id="btn_add_participant" type="button" class="btn btn-success btn-equalizer btn-block margin-top-20"><i class="fa fa-plus-square"></i></button>';
                else
                    rows += '<button  type="button" class="btn btn-danger btn-equalizer btn_remove_participant btn-block margin-top-20"><i class="fa fa-plus-square fa-trash" data-id="' + rand_id + '"></i></button>';
                rows += '    </div>';
                rows += '</div>';
            });
            $('#js-extra-participants-box').html(rows);
        }

        // Add or remove all option
        // @param type
        // @param target
        // @param set_default_option
        function all_option(type, target, set_default_option) {
            type = (type == undefined || type == true || type == '') ? 'add' : type;
            target = target === undefined ? $('#js-participants-select') : $(target);
            if (type == 'add') {
                target.append('<option value="all">All</option>');
                if (set_default_option === undefined) target.select2('val', 'all');
            } else
                target.find('option[value="all"]').remove();
        }

        //
        function get_user_details(user_id) {
            if (user_list.length == 0) return;
            var details;
            $.each(user_list, function(i, v) {
                if (v.id == user_id) {
                    details = v;
                    return v;
                }
            });
            return details;
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

        // Loads reminder email rows
        // @param a
        // Interviewers
        // @param b
        // Non-employee Interviewers
        // @param c
        // Applicant or Employee
        // @param d
        // system admin/user
        // @param e
        // external admin/user
        // @param f
        // event type
        function generate_reminder_email_rows(a, b, c, d, e, f) {
            var rows = '';
            rows += '<div class="row">';
            if (a.length && a.length != 0) {
                rows += '<div class="col-xs-12"><h4>Participant(s)</h4></div>';
                $.each(a, function(i, v) {
                    rows += '<div class="col-xs-6">';
                    rows += '   <label class="control control--checkbox">';
                    rows += v.value === undefined ? v.first_name + ' ' + v.last_name : v.value;
                    rows += '       <input data-value="' + (v.value === undefined ? v.first_name + ' ' + v.last_name : v.value.replace(/ *\([^)]*\) */g, '')) + '" data-id="' + v.id + '" data-type="interviewer" value="' + v.email_address + '" name="reminder_emails[]" checked="checked" type="checkbox" />';
                    rows += '       <div class="control__indicator"></div>';
                    rows += '   </label>';
                    rows += '</div>';
                });
            }
            if (b.length && b.length != 0) {
                rows += '<div class="col-xs-12"><h4>Non Employee Participant(s)</h4></div>';
                $.each(b, function(i, v) {
                    rows += '<div class="col-xs-6">';
                    rows += '   <label class="control control--checkbox">';
                    rows += v.external_participant_name;
                    rows += '       <input data-value="' + v.external_participant_name + '" data-id="0" data-type="non-employee interviewer" value="' + v.external_participant_email + '" name="reminder_emails[]" checked="checked" type="checkbox" />';
                    rows += '       <div class="control__indicator"></div>';
                    rows += '   </label>';
                    rows += '</div>';
                });
            }
            if (c !== undefined && Object.size(c) != 0 && c.email_address !== undefined) {
                rows += '<div class="col-xs-12"><h4>' + (c.type == 'person' ? 'Person' : 'Admin') + '</h4></div>';
                rows += '<div class="col-xs-6">';
                rows += '   <label class="control control--checkbox">';
                rows += c.name;
                rows += '       <input data-value="' + c.name + '" data-id="' + c.id + '" data-type="' + (c.type == 'person' ? 'person' : 'admin') + '" value="' + c.email_address + '" name="reminder_emails[]" class="js-reminder-input" checked="checked" type="checkbox" />';
                rows += '       <div class="control__indicator"></div>';
                rows += '   </label>';
                rows += '</div>';

            }

            if (d !== undefined && d != '' && d.length !== 0) {
                rows += '<div class="col-xs-12"><h4>System ' + (f == 'demo' ? 'User(s)' : 'Admin(s)') + '</h4></div>';
                $.each(d, function(i, v) {
                    rows += '<div class="col-xs-6">';
                    rows += '   <label class="control control--checkbox">';
                    rows += v.first_name + ' ' + v.last_name;
                    rows += '       <input data-value="' + (v.first_name + ' ' + v.last_name) + '" data-id="' + v.id + '" data-type="' + (f == 'demo' ? 'user' : 'admin') + '" value="' + v.email_address + '" name="reminder_emails[]" class="js-reminder-input" checked="checked" type="checkbox" />';
                    rows += '       <div class="control__indicator"></div>';
                    rows += '   </label>';
                    rows += '</div>';
                });

            }


            if (e !== undefined && e != '' && e.length !== 0) {
                rows += '<div class="col-xs-12"><h4>External ' + (f == 'demo' ? 'User(s)' : 'Admin(s)') + '</h4></div>';
                $.each(e, function(i, v) {
                    rows += '<div class="col-xs-6">';
                    rows += '   <label class="control control--checkbox">';
                    rows += v.name;
                    rows += '       <input data-value="' + (v.name) + '" data-id="0" data-type="' + (f == 'demo' ? 'external user' : 'external admin') + '" value="' + v.email_address + '" name="reminder_emails[]" class="js-reminder-input" checked="checked" type="checkbox" />';
                    rows += '       <div class="control__indicator"></div>';
                    rows += '   </label>';
                    rows += '</div>';
                });

            }

            rows += '    <div class="col-sm-12">';
            rows += '    <br />';
            rows += '       <button class="btn btn-success pull-right js-reminder-email-btn">Send Reminder Email</button>';
            rows += '    </div>';
            rows += '</div>';

            $('#js-reminder-email-box').html(rows);
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

        // Load history and clone
        // buttons
        // @param parent_event_sid
        // Holds parent event sid
        // @param event_history_count
        // Count of event history rows
        // 0 for null
        // @param event_reminder_email_history
        // Count of sent reminder emails
        function load_extra_buttons(parent_event_sid, event_history_count, event_reminder_email_history) {
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
            if (show_sent_reminder_history_btn || show_request_status_history_btn)
                extra_btns_wrap.html(rows);
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
            if (new_data.event_type != current_edit_event_details.event_type) {
                diff_obj.old_user_type = current_edit_event_details.event_type;
                diff_obj.new_user_type = new_data.event_type;
            }

            // Difference of event category
            if (new_data.event_category != current_edit_event_details.event_category) {
                diff_obj.old_category = current_edit_event_details.event_category;
                diff_obj.new_category = new_data.event_category;
            }

            // Difference of event date
            if (new_data.event_date != moment(current_edit_event_details.event_date, (new_data.users_type == 'personal' ? site_date_format : site2_date_format)).format(site_date_format)) {
                diff_obj.old_date = moment(current_edit_event_details.event_date, (new_data.users_type == 'personal' ? site_date_format : site2_date_format)).format(site_date_format);
                diff_obj.new_date = new_data.event_date;
            }

            // Difference of event start time
            if (new_data.event_start_time != current_edit_event_details.event_start_time) {
                diff_obj.old_event_start_time = current_edit_event_details.event_start_time.replace(/\s+/, '');
                diff_obj.new_event_start_time = new_data.event_start_time.replace(/\s+/, '');
            }

            // Difference of event end time
            if (new_data.event_end_time != current_edit_event_details.event_end_time) {
                diff_obj.old_event_end_time = current_edit_event_details.event_end_time.replace(/\s+/, '');
                diff_obj.new_event_end_time = new_data.event_end_time.replace(/\s+/, '');
            }

            // Difference of event title
            if (new_data.event_title != current_edit_event_details.event_title) {
                diff_obj.old_event_title = current_edit_event_details.event_title;
                diff_obj.new_event_title = new_data.event_title;
            }

            // Difference of comment
            if (new_data.comment != current_edit_event_details.comment) {
                diff_obj.old_comment = current_edit_event_details.comment;
                diff_obj.new_comment = new_data.comment;
            }

            // Difference of Admin id
            if (new_data.user_id != current_edit_event_details.user_id && new_data.event_type == 'super admin') {
                diff_obj.old_user_id = current_edit_event_details.user_id;
                diff_obj.new_user_id = new_data.user_id;
            }

            // Difference GoToMeeting
            if (new_data.event_category == 'gotomeeting') {
                // Difference of meeting id
                if (new_data.meeting_id != current_edit_event_details.meeting_id) {
                    diff_obj.old_meeting_id = current_edit_event_details.meeting_id;
                    diff_obj.new_meeting_id = new_data.meeting_id;
                }

                // Difference of meeting url
                if (new_data.meeting_url != current_edit_event_details.meeting_url) {
                    diff_obj.old_meeting_url = current_edit_event_details.meeting_url;
                    diff_obj.new_meeting_url = new_data.meeting_url;
                }

                // Difference of meeting call number
                if (new_data.meeting_phone != current_edit_event_details.meeting_phone) {
                    diff_obj.old_meeting_call_number = current_edit_event_details.meeting_phone;
                    diff_obj.new_meeting_call_number = new_data.meeting_phone;
                }
            }

            // Difference of address
            if (new_data.event_address != current_edit_event_details.event_address) {
                diff_obj.old_address = current_edit_event_details.event_address;
                diff_obj.new_address = new_data.event_address;
            }

            // Difference Users
            if (new_data.participants != '' && new_data.participants !== undefined) {
                if (new_data.event_category != 'training-session') {
                    // Difference of users show email
                    if (new_data.participants_show_email != current_edit_event_details.participants_show_email_list) {
                        diff_obj.old_interviewer_show_email = current_edit_event_details.participants_show_email_list;
                        diff_obj.new_interviewer_show_email = new_data.participants_show_email;
                    }

                }

                var tmp_users = [];
                _.filter(current_edit_event_details.participants, function(o) {
                    tmp_users.push(o.id);
                });
                //
                if (new_data.participants == 'all') {
                    var t = [];
                    $.each(admin_list, function(i, v) {
                        t.push(v.admin_id);
                    });
                    new_data.participants = t.join(',');
                }

                if (tmp_users == 'all' && new_data.event_category == 'training-session') {
                    tmp_users = admin_list;
                }

                var removed_interviewers = _.difference(
                        tmp_users,
                        new_data.participants.split(',')
                    ),
                    added_interviewers = _.difference(new_data.participants.split(','), tmp_users);
                // Difference of interviewers|participants|attendees
                if (removed_interviewers.length != 0) diff_obj.removed_interviewers = reset_ids_array(removed_interviewers);
                if (added_interviewers.length != 0) diff_obj.added_interviewers = reset_ids_array(added_interviewers);
                //
                if (removed_interviewers.length != 0 ||
                    added_interviewers.length != 0) {
                    diff_obj.old_interviewers = tmp_users.join(',');
                    diff_obj.new_interviewers = new_data.participants;
                }
            }

            // External participants
            if (new_data.external_participants != '') {
                new_data.external_participants = clean_external_participants(JSON.parse(new_data.external_participants));
                current_edit_event_details.external_participants = clean_external_participants(current_edit_event_details.external_participants);
                //
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

                //
                if (added_exinterviewers.length != 0 || removed_exinterviewers.length != 0) {
                    diff_obj.old_external_interviewers = current_edit_event_details.external_participants;
                    diff_obj.new_external_interviewers = new_data.external_participants;
                    diff_obj.removed_external_interviewers = removed_exinterviewers;
                    diff_obj.added_external_interviewers = added_exinterviewers;
                }
            }

            // Difference of users phone
            if (new_data.user_phone != current_edit_event_details.user_phone && new_data.event_type == 'personal') {
                diff_obj.old_users_phone = current_edit_event_details.user_phone;
                diff_obj.new_users_phone = new_data.user_phone;
            }


            // TODO
            // Difference of recir check
            // if(new_data.recur != current_edit_event_details.is_recur){
            //     diff_obj.old_is_recur = current_edit_event_details.is_recur;
            //     diff_obj.new_is_recur = new_data.recur;
            // }

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

            // console.log(new_data);
            // console.log(current_edit_event_details);

            // Compare user_ids array
            if (current_edit_event_details.user_ids != '') {
                var added_user_ids = _.differenceBy(
                        new_data.user_ids,
                        current_edit_event_details.user_ids,
                        'id'
                    ),
                    removed_user_ids = _.differenceBy(
                        current_edit_event_details.user_ids,
                        new_data.user_ids,
                        'id'
                    );
                //
                if (added_user_ids != '' || removed_user_ids != '') {
                    diff_obj.old_user_ids = current_edit_event_details.user_ids;
                    diff_obj.new_user_ids = new_data.user_ids;
                    diff_obj.removed_user_ids = removed_user_ids;
                    // 
                    if (removed_user_ids.length != 0) {
                        $.each(removed_user_ids, function(i, v) {
                            if (new_data.event_type == 'demo')
                                removed_user_ids[i]['name'] = get_user_details(v.id)['email_address'];
                            else {
                                removed_user_ids[i]['name'] = find_admin(v.id)['full_name'];
                            }


                        });
                        diff_obj.removed_user_ids = removed_user_ids;
                    }
                    //
                    diff_obj.added_user_ids = convert_to_single_array(added_user_ids, 'id');
                }
            }

            // Compare external_users
            if (current_edit_event_details.external_users != '') {
                // Reset the indexes
                if (new_data.external_user_array != '')
                    new_data.external_user_array = clean_external_participants(JSON.parse(new_data.external_user_array));
                else
                    new_data.external_user_array = '';
                current_edit_event_details.external_users = clean_external_participants(current_edit_event_details.external_users);

                var added_external_users = _.differenceBy(
                        new_data.external_user_array,
                        current_edit_event_details.external_users,
                        'email'
                    ),
                    removed_external_users = _.differenceBy(
                        current_edit_event_details.external_users,
                        new_data.external_user_array,
                        'email'
                    );

                //
                if (added_external_users != '' || removed_external_users != '') {
                    diff_obj.old_external_users = current_edit_event_details.external_users;
                    diff_obj.new_external_users = new_data.external_user_array;
                    diff_obj.removed_external_users = removed_external_users;
                    diff_obj.added_external_users = convert_to_single_array(added_external_users, 'email');
                }
            }

            return diff_obj;
        }

        // Convert null, undefined, 0 to ''
        function reset_obj(obj) {
            for (var k0 in obj) {
                var v0 = obj[k0];
                if (k0 == 'external_participants' && v0 != '') {
                    var tmp = v0[0]['external_participant_name'] !== undefined ? v0 : JSON.parse(v0);
                    if (tmp[0].external_participant_name == '' || tmp[0].external_participant_email == '') obj[k0] = '';
                }
                if (v0 == null || v0 == 'null' || v0 == undefined || v0 == '' || v0 == '{}' || v0 == '0') obj[k0] = '';
            }
            return obj;
        }

        // Flush empty rows
        function clean_external_participants(external_participants) {
            var k0, nex = [];
            for (k0 in external_participants) {
                if (external_participants[k0].hasOwnProperty('external_participant_email')) external_participants[k0]['email'] = external_participants[k0]['external_participant_email'];
                if (external_participants[k0].hasOwnProperty('external_participant_name')) external_participants[k0]['name'] = external_participants[k0]['external_participant_name'];
                if (external_participants[k0].hasOwnProperty('email_address')) {
                    external_participants[k0]['email'] = external_participants[k0]['email_address'];
                    delete external_participants[k0]['email_address'];
                }
                if (external_participants[k0]['name'] != '' && external_participants[k0]['email'] != '') nex.push(external_participants[k0]);
            }
            return nex;
        }

        // Update event on drag, resize
        // @param event 
        // Contains the information
        // of current event
        // @param revert
        // Stop drag and drop 
        function update_event(event, revert) {
            if (event.is_recur == 1) {
                revert();
                return true;
            }
            // updates
            // use moment functions
            var eventstarttime = moment(event.start).format('hh:mm'),
                eventstarttime12hr = moment(event.start).format('hh:mm A'),
                eventendtime = moment(event.end).format('hh:mm'),
                eventendtime12hr = moment(event.end).format('hh:mm A'),
                eventDate = moment(event.start, site_date_format).format(site_date_format);

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
                            process_expired_reschedule();
                        }
                    }, 300);
                    // main_page_REF.hide(0);
                    // reschedule_page_REF.show(0);
                }, flush_cb).set('labels', {
                    ok: 'Yes',
                    cancel: 'No'
                });
                // Revert the changes
                revert();
                return false;
            }
            // Check for expiration
            else if (moment().utc() > moment(eventDate + time_24)) {
                calendar_ref.find(event._id).switchClass('fc-event-cc-' + event.status + '', 'fc-event-cc-expired');
                // event.status = 'expired';
                event.expired_status = true;
            } else event.color = event_color_obj[event.event_category];

            // Set difference object
            var diff_obj = {};
            // Difference of event date
            if (eventDate != drag_event.date) {
                diff_obj.new_date = eventDate;
                diff_obj.old_date = drag_event.date;
            }

            // Difference of event start time
            if (eventstarttime12hr != moment(drag_event.start_time).format('hh:mm A')) {
                diff_obj.new_event_start_time = eventstarttime12hr;
                diff_obj.old_event_start_time = moment(drag_event.start_time).format('hh:mmA');
            }

            // Difference of event end time
            if (eventendtime12hr != moment(drag_event.end_time).format('hh:mm A')) {
                diff_obj.new_event_end_time = eventendtime12hr;
                diff_obj.old_event_end_time = moment(drag_event.end_time).format('hh:mmA');
            }

            loader('show');

            $.ajax({
                url: "<?php echo base_url('manage_admin/process-event'); ?>",
                type: 'POST',
                data: {
                    action: 'drag_update',
                    event_sid: event.event_sid,
                    event_date_bk: moment(eventDate, site_date_format).format(site2_date_format),
                    event_start_time: eventstarttime12hr,
                    event_end_time: eventendtime12hr,
                    diff: JSON.stringify(diff_obj)
                },

                success: function(res) {
                    loader();
                    alertify.success(res.Response, flush_cb);

                    event.eventdate = eventDate;
                    event.eventstarttime = eventstarttime;
                    event.eventendtime = eventendtime;
                    event.eventstarttime_12hr = eventstarttime12hr;
                    event.eventendtime_12hr = eventendtime12hr;
                }
            });
        }

        // Show reschedule page
        function process_expired_reschedule() {
            $('.js-main-page, .js-request-page, .js-reminder-email-history-page, .js-recur-page').hide(0);
            $('.js-reschedule-page').show(0);


            $('#js-reschedule-event-date').val(moment().format(site_date_format)).removeClass('disabled').prop('disabled', false);
            $('#js-reschedule-event-start-time').val("<?= $calendar_opt['default_start_time']; ?>").removeClass('disabled').prop('disabled', false);
            $('#js-reschedule-event-end-time').val("<?= $calendar_opt['default_end_time']; ?>").removeClass('disabled').prop('disabled', false);

            load_reschedule_pickers();
        }

        // Load reschedule date/time pickers
        function load_reschedule_pickers() {
            // Datepicker for personal type
            $('#js-reschedule-event-date').datepicker({
                dateFormat: 'mm-dd-yy',
                minDate: 0,
                onSelect: function(d) {
                    $('#js-reschedule-event-date').datepicker('setDate', new Date(d));
                }
            });

            // Loads time plugin for start time field
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
            $('#js-reschedule-event-end-time').datetimepicker({
                datepicker: false,
                format: 'g:iA',
                formatTime: 'g:iA',
                step: <?= $calendar_opt['time_duration']; ?>,
                onShow: function(ct) {
                    var time = $('#js-reschedule-event-start-time').val();
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
        }

        // Cancel the event
        // @param event_sid
        // event id for event
        function cancel_event(event_sid) {
            $.each(calendar_ref.fullCalendar('clientEvents'), function(i, v) {
                if (v.event_sid == event_sid) {
                    calendar_ref.find(v._id).switchClass('fc-event-cc-' + v.status + '', 'fc-event-cc-cancelled');
                    v.status = 'cancelled';
                    calendar_ref.fullCalendar('updateEvent', v);
                    return false;
                }
            });
            $('#js-event-modal').modal('hide');
            $('body').removeClass('ajs-no-overflow');
        }

        // Find event
        // @param event_sid
        function find_event(event_sid) {
            var e = calendar_ref.fullCalendar('clientEvents', function(e) {
                if (e.event_sid == event_sid) return e;
            });
            return e.length != 0 ? e[0] : [];
        }

        // Find admin id 
        // @param ids_array
        function reset_ids_array(ids_array) {
            if (admin_list.length === 0 || ids_array.length === 0) return;
            var new_ids = [];
            $.each(ids_array, function(i, v) {
                var admin_row = find_admin(v);
                if (admin_row.length != 0) new_ids.push(admin_row);
            });
            return new_ids;
        }

        // Find admin id 
        // @param admin_id
        function find_admin(admin_id) {
            if (admin_list.length === 0) return [];
            var tmp = [];
            $.each(admin_list, function(i, v) {
                if (v.admin_id == admin_id) {
                    tmp = v;
                    return false;
                }
            });
            return tmp;
        }

        // Shows event status requests history page
        $(document).on('click', '.js-status-history-btn', function(e) {
            e.preventDefault();
            loader('show');
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
            loader('show');
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

        // Shows main modal page
        // on close button click
        $(document).on('click', '.js-history-close', function(e) {
            e.preventDefault();
            loader('show');
            $('.js-history-close').remove();
            $('.js-event-head-title').text(event_title_text);
            request_page_REF.fadeOut(300);
            main_page_REF.fadeIn(300);
            loader();
        });

        // Shows main modal page
        // on close button click
        $(document).on('click', '.js-reminder-history-close', function(e) {
            e.preventDefault();
            loader('show');
            $('.js-event-head-title').text(event_title_text);
            $('.js-history-close').remove();
            reminder_page_REF.fadeOut(200);
            main_page_REF.fadeIn(200);
            loader();
        });


        // Pagination

        // Get previous page
        $(document).on('click', '.js-pagination-prev', function(event) {
            event.preventDefault();
            loader('show');
            current_page--;
            fetch_event_status_history();
        });

        // Get first page
        $(document).on('click', '.js-pagination-first', function(event) {
            event.preventDefault();
            loader('show');
            current_page = 1;
            fetch_event_status_history();
        });

        // Get last page
        $(document).on('click', '.js-pagination-last', function(event) {
            event.preventDefault();
            loader('show');
            current_page = total_records;
            fetch_event_status_history();
        });

        // Get next page
        $(document).on('click', '.js-pagination-next', function(event) {
            event.preventDefault();
            loader('show');
            current_page++;
            fetch_event_status_history();
        });

        // Get page
        $(document).on('click', '.js-pagination-shift', function(event) {
            event.preventDefault();
            loader('show');
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

            if (event_history_type == 'history') {
                fetch_sent_reminder_emails_history();
            } else {
                event_blinker('clearTabInterval', $('#js-event-id').val());
                // get event
                $.post("<?= base_url('manage_admin/process-event'); ?>", {
                    action: 'status_history',
                    event_sid: $('#js-event-id').val(),
                    current_page: current_page
                }, function(resp) {
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
                        rows += '   <td>' + (v.date != null ? moment(v.date, site_date_format).format('MMM DD YYYY') : '-') + '</td>';
                        rows += '   <td>' + (v.start_time != null ? v.start_time : '-') + '</td>';
                        rows += '   <td>' + (v.end_time != null ? v.end_time : '-') + '</td>';
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
                    loader();
                });
            }
        }

        // Get sent reminder email history
        function fetch_sent_reminder_emails_history() {
            $.post("<?= base_url('manage_admin/process-event'); ?>", {
                action: 'reminder_email_history',
                event_sid: $('#js-event-id').val(),
                current_page: current_page
            }, function(resp) {
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
                loader();
            });
        }

        //
        function convert_to_single_array(ar, ind) {
            if (ar.length === 0) return ar;
            var return_array = [];
            $.each(ar, function(i, v) {
                return_array.push(v[ind]);
            });
            return return_array;
        }


        //
        function scheduleEventview(_this, event) {
            //
            var body_title = "<strong>Scheduled Reminder</strong>";
            var body_content = '';
            var content_date = moment(event.schedule_datetime).format("MM/DD/YYYY @ hh:mm A");
            body_content += '<div class="row">';
            body_content += '   <div class="col-sm-8">';
            body_content += '       <p>' + event.job_role + '</p>';
            body_content += '   </div>';
            body_content += '</div>';
            body_content += '<hr />';
            body_content += '<div class="row">';
            body_content += '   <div class="col-sm-12">';
            body_content += '       <p><strong> ' + capitalizeWords(event.schedule_type) + ' </strong> ( ' + content_date + ' )</p>';
            body_content += '       <p><strong>( ' + capitalizeWords(event.schedule_status) + ' )</strong></p>';

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
            }).popover('show'); //    

        }

        function capitalizeWords(str) {
            return str.replace(/\b\w/g, char => char.toUpperCase());
        }


    })
</script>


<script>
    //var phone_regex = new RegExp(/(\(\d{3}\))\s(\d{3})-(\d{4})$/);

    // $.each($('.js-phone'), function() {
    //     var v = fpn($(this).val().trim());
    //     if(typeof(v) === 'object'){
    //         $(this).val( v.number );
    //         setCaretPosition($(this), v.cur);
    //     }else $(this).val( v );
    // });


    // $('.js-phone').keyup(function(e){
    //     var val = fpn($(this).val().trim());
    //     if(typeof(val) === 'object'){
    //         $(this).val( val.number );
    //         setCaretPosition(this, val.cur);
    //     }else $(this).val( val );
    // })


    // Format Phone Number
    // @param phone_number
    // The phone number string that 
    // need to be reformatted
    // @param format
    // Match format 
    // @param is_return
    // Verify format or change format
    function fpn(phone_number, format, is_return) {
        // 
        var default_number = '(___) ___-____';
        var cleaned = phone_number.replace(/\D/g, '');
        if (cleaned.length > 10) cleaned = cleaned.substring(0, 10);
        match = cleaned.match(/^(1|)?(\d{3})(\d{3})(\d{4})$/);
        //
        if (match) {
            var intlCode = '';
            if (format == 'e164') intlCode = (match[1] ? '+1 ' : '');
            return is_return === undefined ? [intlCode, '(', match[2], ') ', match[3], '-', match[4]].join('') : true;
        } else {
            var af = '',
                an = '',
                cur = 1;
            if (cleaned.substring(0, 1) != '') {
                af += "(_";
                an += '(' + cleaned.substring(0, 1);
                cur++;
            }
            if (cleaned.substring(1, 2) != '') {
                af += "_";
                an += cleaned.substring(1, 2);
                cur++;
            }
            if (cleaned.substring(2, 3) != '') {
                af += "_) ";
                an += cleaned.substring(2, 3) + ') ';
                cur = cur + 3;
            }
            if (cleaned.substring(3, 4) != '') {
                af += "_";
                an += cleaned.substring(3, 4);
                cur++;
            }
            if (cleaned.substring(4, 5) != '') {
                af += "_";
                an += cleaned.substring(4, 5);
                cur++;
            }
            if (cleaned.substring(5, 6) != '') {
                af += "_-";
                an += cleaned.substring(5, 6) + '-';
                cur = cur + 2;
            }
            if (cleaned.substring(6, 7) != '') {
                af += "_";
                an += cleaned.substring(6, 7);
                cur++;
            }
            if (cleaned.substring(7, 8) != '') {
                af += "_";
                an += cleaned.substring(7, 8);
                cur++;
            }
            if (cleaned.substring(8, 9) != '') {
                af += "_";
                an += cleaned.substring(8, 9);
                cur++;
            }
            if (cleaned.substring(9, 10) != '') {
                af += "_";
                an += cleaned.substring(9, 10);
                cur++;
            }

            if (is_return) return match === null ? false : true;

            return {
                number: default_number.replace(af, an),
                cur: cur
            };
        }
    }

    // Change cursor position in input
    function setCaretPosition(elem, caretPos) {
        if (elem != null) {
            if (elem.createTextRange) {
                var range = elem.createTextRange();
                range.move('character', caretPos);
                range.select();
            } else {
                if (elem.selectionStart) {
                    elem.focus();
                    elem.setSelectionRange(caretPos, caretPos);
                } else elem.focus();
            }
        }
    }
</script>

<style>
    /* Remove the radius from left fro phone field*/
    .input-group input {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }
</style>
<style>
    .select2-container {
        min-width: 400px;
    }

    .select2-results__option {
        padding-right: 20px;
        vertical-align: middle;
    }

    .select2-results__option:before {
        content: "";
        display: inline-block;
        position: relative;
        height: 20px;
        width: 20px;
        border: 2px solid #e9e9e9;
        border-radius: 4px;
        background-color: #fff;
        margin-right: 20px;
        vertical-align: middle;
    }

    .select2-results__option[aria-selected=true]:before {
        font-family: fontAwesome;
        content: "\f00c";
        color: #fff;
        background-color: #81b431;
        border: 0;
        display: inline-block;
        padding-left: 3px;
    }

    .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: #fff;
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #eaeaeb;
        color: #272727;
    }

    .select2-container--default .select2-selection--multiple {
        margin-bottom: 10px;
    }

    .select2-container--default.select2-container--open.select2-container--below .select2-selection--multiple {
        border-radius: 4px;
    }

    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #81b431;
        border-width: 2px;
    }

    .select2-container--default .select2-selection--multiple {
        border-width: 2px;
    }

    .select2-container--open .select2-dropdown--below {

        border-radius: 6px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);

    }

    .select2-selection .select2-selection--multiple:after {
        content: 'hhghgh';
    }

    /* select with icons badges single*/
    .select-icon .select2-selection__placeholder .badge {
        display: none;
    }

    .select-icon .placeholder {
        display: none;
    }

    .select-icon .select2-results__option:before,
    .select-icon .select2-results__option[aria-selected=true]:before {
        display: none !important;
        /* content: "" !important; */
    }

    .select-icon .select2-search--dropdown {
        display: none;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        height: 25px !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__rendered {
        height: 30px;
    }
</style>