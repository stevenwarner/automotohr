$(function() {

    let callOBJ = {
            Settings: {
                Main: {
                    action: 'get_settings_by_company',
                    companyId: companyId,
                    employerId: employerId,
                    employeeId: employeeId,
                    public: 0,
                },
                Update: {
                    action: 'edit_settings',
                    companyId: companyId,
                    employerId: employerId,
                    employeeId: employeeId,
                    public: 0,
                }
            }
        },
        settingOBJ = {
            defaultTimeslot: 0,
            format: 0,
            offDays: 0,
            approvalCheck: 0,
            forAllEmployees: 0,
            emailSendCheck: 0,
            emailCheck: 0,
        };

    // Fetch Settings
    fetchTimeOffSettings();
    //
    $('.js-accural-date').datepicker({ dateFormat: 'mm-dd-yy' });
    $('#js-off-days').select2({
        closeOnSelect: false
    });
    //
    $('#js-themes').select2({
        minimumResultsForSearch: -1
    });
    //
    $('#js-save-btn').on('click', function() {
        //
        ml(true, 'setting');
        //
        settingOBJ.defaultTimeslot = $('#js-default-time-slot-hours').val().trim();
        settingOBJ.format = $('#js-formats').val();
        settingOBJ.offDays = $('#js-off-days').val();
        settingOBJ.theme = $('#js-themes').val();
        settingOBJ.forAllEmployees = Number($('.js-for-all-employees').prop('checked'));
        settingOBJ.emailSendCheck = Number($('#js-send-email-check').prop('checked'));
        settingOBJ.emailCheck = Number($('#js-email-check').prop('checked'));
        //
        $.post(
            handlerURL,
            Object.assign(callOBJ.Settings.Update, settingOBJ),
            function(resp) {
                //
                if (resp.Redirect === true) {
                    //
                    alertify.alert('WARNING!', 'Your session expired. Please, re-login to continue.', () => {
                        window.location.reload();
                    });
                    return;
                }
                if (resp.Status === false) {
                    //
                    ml(false, 'setting');
                    //
                    alertify.alert('WARNING!', resp.Response);
                    return;
                }
                //
                alertify.alert('SUCCESS!', resp.Response, function() {
                    window.location.reload();
                });
                //
                ml(false, 'setting');
            }
        );
    });
    //
    //
    function fetchTimeOffSettings() {
        $.post(
            handlerURL,
            callOBJ.Settings.Main,
            (resp) => {
                //
                if (resp.Redirect === true) {
                    //
                    alertify.alert('WARNING!', 'Your session expired. Please, re-login to continue.', () => {
                        window.location.reload();
                    });
                    return;
                }
                //
                if (resp.Data.Formats.length != 0) {
                    var rows = '';
                    $.each(resp.Data.Formats, function(i, v) {
                        rows += '<option value="' + (v.format_id) + '">' + (v.title.replace(/:/g, ' ')) + '</option>';
                    });
                    $('#js-formats').html(rows);
                    $('#js-formats').select2();
                }
                //
                if (resp.Data.Settings != null && resp.Data.Settings.length != 0) {
                    //
                    $('#js-default-time-slot-hours').val(resp.Data.Settings.default_timeslot);
                    $('#js-approval-check').prop('checked', resp.Data.Settings.approval_check == '1' ? true : false);
                    // $('#js-email-check').prop('checked', resp.Data.Settings.email_check == '0' ? true : false);
                    $('#js-send-email-check').prop('checked', resp.Data.Settings.send_email_to_supervisor == '1' ? true : false);
                    $('#js-formats').select2('val', resp.Data.Settings.timeoff_format_sid);
                    //
                    $('#js-off-days').select2('val', resp.Data.Settings.off_days.split(','));
                    $('#js-themes').select2('val', resp.Data.Settings.theme);
                }
                ml(false, 'setting');
            }

        );
    }

    $('[data-toggle="popovers"]').popover({
        trigger: 'hover'
    });
})