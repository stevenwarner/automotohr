let cmnOBJ = {
    Categories: {
        Main: {
            action: 'get_company_types_list',
            companyId: companyId,
            employerId: employerId,
            employeeId: employeeId,
            public: 0,
        }
    },
    Employees: {
        Main: {
            action: 'get_company_employees',
            companyId: companyId,
            employerId: employerId,
            employeeId: employeeId,
            public: 0,
        }
    },
    Policies: {
        Main: {
            action: 'get_policy_list_by_company',
            companyId: companyId,
            employerId: employerId,
            employeeId: employeeId,
            public: 0,
        }
    }
},
    redirectPage = true;

//
fetchCategories();
fetchEmployees();
fetchPolicies();

//
if (page == 'add') loadAddPage();

//
$('.js-step').fadeOut(300);
$(`.js-step[data-step="1"]`).fadeIn(300);
//
$('.jsViewPoliciesBtn').click(function (e) {
    e.preventDefault();
    //
    alertify.confirm(
        'WARNING!',
        'Any unsaved changes will be lost.',
        loadViewPage,
        () => { }
    ).set('labels', {
        ok: "Leave",
        cancel: "No, I will stay"
    });
});

$(document).on('click', '.js-to-step', function (e) {
    //

    e.preventDefault();
    //
    ml(true, 'policy');
    //
    let step = $(this).closest('div').data('step'),
        type = $(this).closest('div').data('type');
    //
    if (step === 1) {
        $('#js-step-bar-add').hide();
        //
        let templateId = $('.js-template-add:checked').val();
        //
        if (templateId === undefined) {
            alertify.alert('WARNING!', 'Please, select a template.', () => { });
            ml(false, 'policy');
            return;
        }
        //
        if (templateId != 0) {
            loadTemplate(
                templateId
            );
        }
        //
        $('#js-step-bar-add').show();
    } else {
        //
        if (!isStepCompleted(step - 1, type)) { step = step - 1; }
    }

    //
    if (step === 8) {
        //
        redirectPage = false;
        finalStep(type);
        return;
    }
    //
    $(`.js-step-tab[data-type="${type}"]`).parent('li').removeClass('active');
    $(`.js-step-tab[data-type="${type}"][data-step="${step}"]`).parent('li').addClass('active');
    $(`.js-step-tab[data-type="${type}"]`).find('i').remove();
    $(`.js-step-tab[data-type="${type}"][data-step="${step}"]`).append('<i class="fa fa-long-arrow-right"></i>');
    //
    $(`.js-step[data-type="${type}"]`).fadeOut(0);
    $(`.js-step[data-type="${type}"][data-step="${step}"]`).fadeIn(300);
    //
    $('body, html').animate({ scrollTop: 0 }, 0);
    //
    ml(false, 'policy');
});


$(document).on('click', '.js-to-step-back', function (e) {
    //
    e.preventDefault();
    //
    ml(true, 'policy');
    //
    let step = $(this).closest('div').data('step'),
        type = $(this).closest('div').data('type');
    //
    $(`.js-step-tab[data-type="${type}"]`).parent('li').removeClass('active');
    $(`.js-step-tab[data-type="${type}"][data-step="${step - 2}"]`).parent('li').addClass('active');
    $(`.js-step-tab[data-type="${type}"]`).find('i').remove();
    $(`.js-step-tab[data-type="${type}"][data-step="${step - 2}"]`).append('<i class="fa fa-long-arrow-right"></i>');
    //
    $(`.js-step[data-type="${type}"]`).fadeOut(0);
    $(`.js-step[data-type="${type}"][data-step="${step - 2}"]`).fadeIn(300);
    //
    ml(false, 'policy');
});


$(document).on('click', '.js-step-tab', function (e) {
    //
    e.preventDefault();
    //
    ml(true, 'policy');
    //
    let step = $(this).data('step'),
        type = $(this).data('type');
    //
    if (!isStepCompleted(1, type)) { step = 1; } else if (!isStepCompleted(2, type)) { step = 2; } else if (!isStepCompleted(3, type)) { step = 3; } else if (!isStepCompleted(4, type)) { step = 4; } else if (!isStepCompleted(5, type)) { step = 5; } else if (!isStepCompleted(6, type)) { step = 6; } else if (!isStepCompleted(7, type)) { step = 7; }
    // else if(!isStepCompleted(8, type)){ step = 8; }
    //
    $(`.js-step-tab[data-type="${type}"]`).parent('li').removeClass('active');
    $(`.js-step-tab[data-type="${type}"][data-step="${step}"]`).parent('li').addClass('active');
    $(`.js-step-tab[data-type="${type}"]`).find('i').remove();
    $(`.js-step-tab[data-type="${type}"][data-step="${step}"]`).append('<i class="fa fa-long-arrow-right"></i>');
    //
    $(`.js-step[data-type="${type}"]`).fadeOut(0);
    $(`.js-step[data-type="${type}"][data-step="${step}"]`).fadeIn(300);
    //
    ml(false, 'policy');
});

//
$('#js-add-policy-btn').click((e) => {
    //
    e.preventDefault();
    //
    loadAddPage();
    //
    $('#NonEntitledEmployeesadd').prop('checked', true);
});


//
$(document).on('click', '.js-employee-accrual-settings', function (e) {
    //
    e.preventDefault();
    //
    ml(true, 'policy');
    //
    let step = $(this).data('step'),
        type = $(this).data('type');

    //
    $(`.js-step-tab[data-type="${type}"]`).parent('li').removeClass('active');
    $(`.js-step-tab[data-type="${type}"][data-step="${step}"]`).parent('li').addClass('active');
    $(`.js-step-tab[data-type="${type}"]`).find('i').remove();
    $(`.js-step-tab[data-type="${type}"][data-step="${step}"]`).append('<i class="fa fa-long-arrow-right"></i>');
    //
    $(`.js-step[data-type="${type}"]`).fadeOut(0);
    $(`.js-step[data-type="${type}"][data-step="${step}"]`).fadeIn(300);
    //
    ml(false, 'policy');

});


//
$(document).on('click', '.js-add-employee-accrual-settings', function (e) {
    //
    e.preventDefault();
    //
    ml(true, 'policy');
    //
    let step = $(this).data('step'),
        type = $(this).data('type');

    //
    $(`.js-step[data-type="${type}"]`).fadeOut(0);
    $(`.js-step[data-type="${type}"][data-step="${step}"]`).fadeIn(300);
    //
    ml(false, 'policy');

});


//
$(document).on('click', '.js-employee-accrual-save', function (e) {
    e.preventDefault();
    //

    let employeeMinimumApplicableHours = $("#js-employee-minimum-applicable-hours-add").val(),
        employeeMinimumApplicableTimeAdd = $(".js-employee-minimum-applicable-time-add:checked").val(),
        employeeId = $("#js-accrual-employee-add").val()

    if (employeeId === null || employeeId === '') {
        alertify.alert('WARNING!', 'Please, select employee.', () => { });
        return false;
    }
    if (employeeMinimumApplicableHours === '') {

        alertify.alert('WARNING!', 'Please, enter minimum applicable time .', () => { });
        return false;
    }

    ml(true, 'policy');

    let post = {
        action: 'add-employee-accural-settings',
        companyId: companyId,
        employeeId: employeeId,
        employerId: employerId,
        employeeMinimumApplicableHours: employeeMinimumApplicableHours,
        employeeMinimumApplicableTimeAdd: employeeMinimumApplicableTimeAdd

    };

    $.post(handlerURL, post, (resp) => {
        //
        if (resp.Redirect === true) {
            //
            alertify.alert('WARNING!', 'Your session expired. Please, re-login to continue.', () => {
                window.location.reload();
            });
            return;
        }
        // On fail
        if (resp.Status === false) {
            alertify.alert('WARNING!', resp.Response, () => { });
            return;
        }
        // On success
        alertify.alert('SUCCESS!', resp.Response, () => {
            ml(false, 'policy');
            window.location.reload();
        });

        return;
    });

});


//
$(document).on('click', '.js-employee-accural-setting-delete', function (e) {
    //
    e.preventDefault();
    //
    alertify.confirm('Do you really want to delete this setting?', () => {

        let sid = $(this).data('id');

        ml(true, 'policy');

        let post = {
            action: 'delete-employee-accural-settings',
            companyId: companyId,
            employeeId: employeeId,
            employerId: employerId,
            sid: sid
        };

        $.post(handlerURL, post, (resp) => {
            //
            if (resp.Redirect === true) {
                //
                alertify.alert('WARNING!', 'Your session expired. Please, re-login to continue.', () => {
                    window.location.reload();
                });
                return;
            }
            // On fail
            if (resp.Status === false) {
                alertify.alert('WARNING!', resp.Response, () => { });
                return;
            }
            // On success
            alertify.alert('SUCCESS!', resp.Response, () => {
                ml(false, 'policy');
                window.location.reload();
            });

            return;
        });

    }).set('labels', {
        ok: 'Yes',
        cancel: 'No'
    });

});


//
$(document).on('click', '.js-employee-accrual-update', function (e) {
    //
    e.preventDefault();
    //
    let employeeMinimumApplicableHours = $("#js-employee-minimum-applicable-hours-update").val(),
        employeeMinimumApplicableTimeAdd = $(".js-employee-minimum-applicable-time-update:checked").val(),
        employeeId = 0,//$("#js-accrual-employee-add").val()
        sid = $(".js-employe-accural-setting-sid").val();

    if (employeeMinimumApplicableHours === '') {
        alertify.alert('WARNING!', 'Please, enter minimum applicable time .', () => { });
        return false;
    }

    ml(true, 'policy');

    let post = {
        action: 'update-employee-accural-settings',
        companyId: companyId,
        employeeId: employeeId,
        employerId: employerId,
        employeeMinimumApplicableHours: employeeMinimumApplicableHours,
        employeeMinimumApplicableTimeAdd: employeeMinimumApplicableTimeAdd,
        sid: sid
    };

    $.post(handlerURL, post, (resp) => {
        //
        if (resp.Redirect === true) {
            //
            alertify.alert('WARNING!', 'Your session expired. Please, re-login to continue.', () => {
                window.location.reload();
            });
            return;
        }
        // On fail
        if (resp.Status === false) {
            alertify.alert('WARNING!', resp.Response, () => { });
            return;
        }
        // On success
        alertify.alert('SUCCESS!', resp.Response, () => {
            ml(false, 'policy');
            window.location.reload();

        });

        return;
    });

});


//
$(document).on('click', '.js-cancel-employee-settings', function (e) {
    //
    e.preventDefault();
    //
    ml(true, 'policy');
    //
    let step = type = $(this).closest('div').data('type');

    //
    $(`.js-step[data-type="${type}"]`).fadeOut(0);
    $(`.js-step[data-type="${type}"][data-step="8"]`).fadeIn(300);
    //
    ml(false, 'policy');
});



$(document).on('click', '.js-employee-accural-setting-edit', function (e) {
    //
    e.preventDefault();
    //

    e.preventDefault();
    //
    ml(true, 'policy');


    let sid = $(this).data('id'),
        minimumApplicableHours = $(this).data('minimumhours'),
        minimumApplicableTime = $(this).data('minimumtime');

    $("#js-employee-minimum-applicable-hours-update").val(minimumApplicableHours);
    $('.js-employee-minimum-applicable-time-update[value="' + minimumApplicableTime + '"]').prop('checked', true);
    $(".js-employe-accural-setting-sid").val(sid);
    //
    $(`.js-step[data-type="add"]`).fadeOut(0);
    $(`.js-step[data-type="add"][data-step="10"]`).fadeIn(300);
    //
    ml(false, 'policy');
});



//
function getTypeNames(ids) {
    if (ids.length == 0) return 'Not Assigned';
    if (window.timeoff.categories.length == 0) return 'Not Assigned';
    //
    let row = '';
    //
    window.timeoff.categories.map(function (v) {
        if (v.type_id == ids) row = v.type_name + ', ';
    });
    //
    return row.substring(0, row.length - 2);
}

// Fetch the categories
function fetchCategories() {
    $.post(handlerURL, cmnOBJ.Categories.Main, function (resp) {
        //
        if (resp.Redirect === true) {
            alertify.alert('WARNING!', 'Your session expired. Please, re-login to continue.', () => {
                window.location.reload();
            });
            return;
        }
        //
        if (resp.Status === false) {
            return alertify.alert(
                "Warning!",
                "No policy types found. Please, add a type before creating policies.",
                function () {
                    $('.jsIPLoader[data-page="policy"]').hide(0);
                }
            );
            return;
        }
        //
        window.timeoff.categories = resp.Data;
        //
        let rows = '';
        //
        rows += '<option value="0" selected="true">[Please select a policy type]</option>';
        //
        window.timeoff.categories.map(function (v) {
            rows += `<option value="${v.type_id}">${v.type_name}</option>`;
        });
        //
        $('#js-category-add, #js-category-edit, #js-category-reset').html(rows);
    });
}

// Employees
function fetchEmployees() {
    $.post(handlerURL, cmnOBJ.Employees.Main, function (resp) {
        //
        if (resp.Redirect === true) {
            alertify.alert('WARNING!', 'Your session expired. Please, re-login to continue.', () => {
                window.location.reload();
            });
            return;
        }
        //
        if (resp.Status === false) {
            console.log('Failed to load employees.');
            resp.Data = [];
            // return;
        }
        //
        window.timeoff.employees = resp.Data;
        //
        let rows = '';
        //
        rows += '<option value="all">All</option>';
        //
        window.timeoff.employees.map(function (v) {
            rows += '<option value="' + (v.user_id) + '">' + (remakeEmployeeName(v)) + '</option>';
        });
        //
        $('#js-employee-add').html(rows);
        $('#js-employee-add').select2();
        $('#js-employee-add').select2MultiCheckboxes({
            templateSelection: function (selected, total) {
                total--;
                return "Selected " + ($.inArray('all', $('#js-employee-add').val()) !== -1 ? total : selected.length) + " of " + total;
            }
        });

        //
        $('#js-approvers-list-add').html(rows);
        $('#js-approvers-list-add').select2();
        $('#js-approvers-list-add').select2MultiCheckboxes({
            templateSelection: function (selected, total) {
                total--;
                return "Selected " + ($.inArray('all', $('#js-approvers-list-add').val()) !== -1 ? total : selected.length) + " of " + total;
            }
        });


        //
        $('#js-approvers-list-edit').html(rows);
        $('#js-approvers-list-edit').select2();
        $('#js-approvers-list-edit').select2MultiCheckboxes({
            templateSelection: function (selected, total) {
                total--;
                return "Selected " + ($.inArray('all', $('#js-approvers-list-edit').val()) !== -1 ? total : selected.length) + " of " + total;
            }
        });
        //
        $('#js-employee-edit').html(rows);
        $('#js-employee-reset').html(rows);
        $('#js-filter-employee').html(rows);
        $('#js-filter-employee').select2();
    });
}


// Employees
function fetchEmployeesForAccrualSettings() {

    let postSettings = Object.assign({}, {
        action: 'get_company_employees_for_accrual_settings',
        companyId: companyId,
        employeeId: employeeId,
        employerId: employerId,
        public: 0
    });
    $.post(handlerURL, postSettings, function (resp) {
        //
        if (resp.Redirect === true) {
            alertify.alert('WARNING!', 'Your session expired. Please, re-login to continue.', () => {
                window.location.reload();
            });
            return;
        }
        //
        if (resp.Status === false) {
            console.log('Failed to load employees.');
            resp.Data = [];
            // return;
        }
        //
        window.timeoff.employees = resp.Data;
        //
        let rows = '';
        //
        rows += '<option value="">Please Select</option>';
        //
        window.timeoff.employees.map(function (v) {
            rows += '<option value="' + (v.user_id) + '">' + (remakeEmployeeName(v)) + '</option>';
        });

        $('#js-accrual-employee-add').html(rows);
        $('#js-accrual-employee-add').select2();

    });
}

//

fetchEmployeesForAccrualSettings();


// Polciies
function fetchPolicies() {
    $.post(handlerURL, cmnOBJ.Policies.Main, function (resp) {
        //
        if (resp.Redirect === true) {
            alertify.alert('WARNING!', 'Your session expired. Please, re-login to continue.', () => {
                window.location.reload();
            });
            return;
        }
        //
        if (resp.Status === false) {
            console.log('Failed to load employees.');
            return;
        }
        //
        let rows = '';
        //
        rows += '<option value="all">All</option>';
        //
        resp.Data.map(function (v) {
            rows += `<option value="${v.policy_id}">${v.policy_title} (${(v.category_type == 1) ? "Paid" : "Unpaid"}) </option>`;
        });
        //
        $('#js-filter-policies').html(rows);
        $('#js-filter-policies').select2();
    });
}

// Accrual to text
function getAccrualText(accrualOBJ, isNewHire) {
    //
    let
        method = accrualOBJ.method == null ? 'days_per_year' : accrualOBJ.method,
        frequency = accrualOBJ.frequency === null ? 'none' : accrualOBJ.frequency,
        time = accrualOBJ.time == null ? 'none' : accrualOBJ.time,
        rate = accrualOBJ.rate == null ? 0 : accrualOBJ.rate,
        rateType = accrualOBJ.rateType,
        frequencyVal = accrualOBJ.frequencyVal,
        text = isNewHire === undefined ? 'The entitled employee(s) will have {{accrue}} that can be accrued from {{time}}.' : 'The entitled employee(s) (on probation) will have {{accrue}} that can be accrued from {{time}}.',
        accrue = '',
        o = {
            'days': {
                'none': 'Jan to Dec',
                'start_of_period': 'Jan to June',
                'end_of_period': 'July to Dec'
            },
            'time': {
                'none': '1st to 30th',
                'start_of_period': '1st to 15th',
                'end_of_period': '16th to 30th'
            }
        };

    //
    if (isNewHire !== undefined) {
        rate = accrualOBJ.newHireRate;
        // rateType = 'total_hours';
        //
        if (rate == null || rate == 0) rate = accrualOBJ.rate;
    }

    //
    if (frequency == 'none') time = o.days[time];
    else time = o.time[time];

    // For days - none
    // The entitled employee(s) will have 12 days each year. The time will be accrued between Jan and Dec.
    // The entitled employee(s) will have 12 days each year. The time will be accrued between Jan and Jun.
    // The entitled employee(s) will have 12 days each year. The time will be accrued between July and Dec.

    // For days - yearly
    // The entitled employee(s) will have 1 day each month. The time will be accrued between Jan and Dec.
    // The entitled employee(s) will have 1 day each month. The time will be accrued between Jan and Jun.
    // The entitled employee(s) will have 1 day each month. The time will be accrued between Jun and Dec.

    // For days - custom
    // The entitled employee(s) will have 4 days every 4 months. The time will be accrued between Jan and Dec.
    // The entitled employee(s) will have 4 days every 4 months. The time will be accrued between Jan and Jun.
    // The entitled employee(s) will have 4 days every 4 months. The time will be accrued between Jun and Dec.

    // For hours - none
    // The entitled employee(s) will have 2 hours each month that will be accrued between 01 and 30.
    // The entitled employee(s) will have 2 hours each month that will be accrued between 01 and 15.
    // The entitled employee(s) will have 2 hours each month that will be accrued between 16 and 30.

    // For hours - yearly
    // The entitled employee(s) will have 3.33 hours each month that will be accrued between 01 and 30.
    // The entitled employee(s) will have 3.33 hours each month that will be accrued between 01 and 15.
    // The entitled employee(s) will have 3.33 hours each month that will be accrued between 16 and 30.

    // For hours - custom
    // The entitled employee(s) will have 8 hours in every 4 months between 01 and 30. 
    // The entitled employee(s) will have 8 hours in every 4 months between 01 and 15. 
    // The entitled employee(s) will have 8 hours in every 4 months between 16 and 30. 


    // When the default is set to unlimited

    // Case 1
    if (rate == 0) {
        //
        accrue = ` "Unlimited" days per year`;
        return text
            .replace(/{{accrue}}/g, accrue)
            .replace(/{{time}}/g, time);
    }

    // Case 1
    if (method == 'hours_per_month' && frequency == 'none') {
        //
        accrue = ` ${rate} hour${rate > 1 ? 's' : ''} per year`;
        duration = ` ${time}.`
        return text
            .replace(/{{accrue}}/g, accrue)
            .replace(/{{time}}/g, time);
    }

    // Case 5
    if (method == 'hours_per_month' && frequency == 'yearly') {
        //
        let newRate = (rateType == 'total_hours' ? rate / 12 : rate * 12).toFixed(2);
        //
        if (rateType == 'total_hours') {
            accrue = ` ${rate} hour${rate > 1 ? 's' : ''} per year with an accrue rate of ${newRate} hour${newRate > 1 ? 's' : ''} per month`;
        } else {
            accrue = ` ${newRate} hour${newRate > 1 ? 's' : ''} per year with an accrue rate of ${rate} hour${rate > 1 ? 's' : ''} per month`;
        }
        time = `${time} of each month`
        return text
            .replace(/{{accrue}}/g, accrue)
            .replace(/{{time}}/g, time);
    }

    // Case 6
    if (method == 'hours_per_month' && frequency == 'monthly') {
        //
        let newRate = rate;
        //
        accrue = ` ${newRate} hour${newRate > 1 ? 's' : ''} each month`;
        duration = ` ${time}.`
        return text
            .replace(/{{accrue}}/g, accrue)
            .replace(/{{time}}/g, time);
    }

    // Case 7
    if (method == 'hours_per_month' && frequency == 'custom') {
        //
        let slots = (12 / frequencyVal);
        let frequencyValC = (rate / slots);
        frequencyValC = frequencyValC.toFixed(2);
        //
        accrue = ` ${frequencyValC} hour${frequencyValC > 1 ? 's' : ''} every ${frequencyVal} month${frequencyVal > 1 ? 's' : ''}`;
        duration = ` ${time}.`
        return text
            .replace(/{{accrue}}/g, accrue)
            .replace(/{{time}}/g, time);
    }
    //
    return '-';
}

// Set policy accrual
function setAccrualText() {
    let
        method = 'hours_per_month',
        // method = $(`#js-accrual-method-${page}`).val(),
        frequency = $(`#js-accrual-frequency-${page}`).val(),
        time = $(`#js-accrual-time-${page} option:selected`).text(),
        rate = $(`#js-accrual-rate-${page}`).val() == '' ? 0 : $(`#js-accrual-rate-${page}`).val(),
        rateType = $(`#js-accrual-rate-type-${page} option:selected`).val(),
        frequencyVal = $(`#js-accrual-frequency-val-${page}`).val(),
        text = 'The entitled employee(s) will have {{accrue}} that can be accrued from {{time}}.',
        accrue = '';
    $(`.js-plan-btn-${page}`).prop('disabled', false);
    //

    // For hours - none
    // The entitled employee(s) will have 40 hours per year that will be accrued between 01 and 30.
    // The entitled employee(s) will have 40 hours per year that will be accrued between 01 and 15.
    // The entitled employee(s) will have 40 hours per year that will be accrued between 16 and 30.

    // For hours - monthly
    // The entitled employee(s) will have 2 hours each month that will be accrued between 01 and 30.
    // The entitled employee(s) will have 2 hours each month that will be accrued between 01 and 15.
    // The entitled employee(s) will have 2 hours each month that will be accrued between 16 and 30.

    // For hours - yearly
    // The entitled employee(s) will have 3.33 hours each month that will be accrued between 01 and 30.
    // The entitled employee(s) will have 3.33 hours each month that will be accrued between 01 and 15.
    // The entitled employee(s) will have 3.33 hours each month that will be accrued between 16 and 30.

    // For hours - custom
    // The entitled employee(s) will have 8 hours in every 4 months between 01 and 30. 
    // The entitled employee(s) will have 8 hours in every 4 months between 01 and 15. 
    // The entitled employee(s) will have 8 hours in every 4 months between 16 and 30. 
    //
    $(`#js-accrual-time-text-${page}`).html('<i>The accrual time can\'t be set for unlimited policies.</i>');
    $(`#js-accrual-time-text-${page}`).hide();
    $(`#js-accrual-time-${page}`).prop('disabled', false);
    $(`#js-accrual-frequency-${page}`).prop('disabled', false);
    //
    $(`.js-policy-reset-date-${page}`).prop('disabled', false);
    //
    $(`#js-accrual-rate-type-${page}`).html(`<option value="total_hours">Total Hour(s)</option><option value="hours">Hour(s)</option>`);

    // When months are selected
    //
    $(`.jsTimeTypeSelect-${page}`).find('option').remove();
    //
    $(`.jsTimeType-${page}`).text('Hour(s)');
    //
    $(`.jsTimeTypeSelect-${page}`)
        .append(`
        <option value="total_hours" ${rateType == undefined || rateType == 'total_hours' ? 'selected="true"' : ''}>Total Hour(s)</option>
        <option value="hours_per_month" ${rateType == 'hours_per_month' ? 'selected="true"' : ''}>Hour(s) /month</option>
    `);

    //
    time = $(`#js-accrual-time-${page} option:selected`).text();

    // Case 1
    // Case 2
    // Case 3

    // Case 6
    if (method == 'hours_per_month' && rate != 0 && frequency == 'none') {
        $(`.jsTimeTypeSelect-${page} option[value="total_hours"]`).prop('checked', true);
        $(`.jsTimeTypeSelect-${page} option[value="hours_per_month"]`).remove();
        //
        // $(`.js-policy-reset-date-${page}`).prop('disabled', true);
        //
        accrue = ` ${rate} hour${rate > 1 ? 's' : ''} per year `;
        text = text
            .replace(/{{accrue}}/g, accrue)
            .replace(/{{time}}/g, time);
    }

    // Case 5
    if (method == 'hours_per_month' && rate != 0 && frequency == 'yearly') {
        //
        let newRate = (rateType == 'total_hours' ? rate / 12 : rate * 12).toFixed(2);
        newRate = newRate;
        //
        if (rateType == 'total_hours') {
            accrue = ` ${rate} hour${rate > 1 ? 's' : ''} per year with an accrue rate of ${newRate} hour${newRate > 1 ? 's' : ''} per month`;
        } else {
            accrue = ` ${newRate} hour${newRate > 1 ? 's' : ''} per year with an accrue rate of ${rate} hour${rate > 1 ? 's' : ''} per month`;
        }
        duration = ` ${time}.`
        text = text
            .replace(/{{accrue}}/g, accrue)
            .replace(/{{time}}/g, time);
    }

    // Case 6
    if (method == 'hours_per_month' && rate != 0 && frequency == 'monthly') {
        $(`.jsTimeTypeSelect-${page} option[value="total_hours"]`).prop('checked', true);
        $(`.jsTimeTypeSelect-${page} option[value="hours_per_month"]`).remove();
        //
        // $(`.js-policy-reset-date-${page}`).prop('disabled', true);
        //
        let newRate = rate;
        //
        accrue = ` ${newRate} hour${newRate > 1 ? 's' : ''} each month`;
        text = text
            .replace(/{{accrue}}/g, accrue)
            .replace(/{{time}}/g, time);
    }

    // Case 7
    if (method == 'hours_per_month' && rate != 0 && frequency == 'custom') {
        $(`.jsTimeTypeSelect-${page} option[value="total_hours"]`).prop('checked', true);
        $(`.jsTimeTypeSelect-${page} option[value="hours_per_month"]`).remove();
        //
        let slots = (12 / frequencyVal);
        let frequencyValC = (rate / slots);
        frequencyValC = frequencyValC.toFixed(2);
        //
        accrue = ` ${frequencyValC} hour${frequencyValC > 1 ? 's' : ''} every ${frequencyVal} month${frequencyVal > 1 ? 's' : ''}`;
        text = text
            .replace(/{{accrue}}/g, accrue)
            .replace(/{{time}}/g, '')
            .replace(/that can be accrued from/g, '');
        //
        $(`#js-accrual-time-${page}`).prop('disabled', true);
        $(`#js-accrual-time-text-${page}`).html('<i>The accrual time can\'t be set for custom frequency.</i>');
        $(`#js-accrual-time-text-${page}`).show();
    }

    // Case 8
    if (method == 'unlimited' || rate == 0) {
        //
        accrue = ` "Unlimited" ${method == 'hours_per_month' ? "hours per month" : "days per year"}`;
        text = text
            .replace(/{{accrue}}/g, accrue)
            .replace(/{{time}}/g, '')
            .replace(/ that can be accrued from/g, '');
        //
        $(`#js-accrual-time-text-${page}`).show();
        $(`#js-accrual-time-${page}`).prop('disabled', true);
        $(`#js-accrual-frequency-${page}`).prop('disabled', true);
        $(`.js-plan-btn-${page}`).prop('disabled', true);
        $(`.js-plan-area-${page}`).html('');
    }
    //
    if (text.match('{{') === null) $(`#jsFormula-${page}`).text(text);
    else $(`#jsFormula-${page}`).text('');
}

// Set new hire accrual
function setNewHireAccrual(
    pageType
) {
    //
    if (typeof (pageType) === 'object') pageType = undefined;
    //
    pageType = pageType === undefined ? page : pageType;
    //
    let
        duration = $(`#js-accrue-new-hire-${pageType}`).val().trim(),
        rate = $(`#js-newhire-prorate-${pageType}`).val().trim(),
        type = $(`#js-accrual-new-hire-time-type-${pageType} option:selected`).val(),
        target = $(`.js-newhire-text-${pageType}`),
        msg = '';
    //
    if (pageType == 'add') type = $(`#js-accrual-new-hire-time-type option:selected`).val();
    //
    if (duration == '' || duration < 0 || duration.match(/[^0-9]/) !== null) duration = 0;
    if (rate == '' || rate < 0 || rate.match(/[^0-9]/) !== null) rate = 0;
    //
    if (type == 'per_month') {
        msg = `The employees that are working ${duration} hour${duration == 1 ? '' : 's'} per month will be considered as permanent.`;
    } else if (type == 'per_week') {
        msg = `The employees that are working ${duration} hour${duration == 1 ? '' : 's'} per week will be considered as permanent.`;
    } else if (type == 'months') {
        msg = `The employees that have worked for ${duration} month${duration == 1 ? '' : 's'} will be considered as permanent.`;
    } else if (type == 'days') {
        msg = `The employees that have worked for ${duration} day${duration == 1 ? '' : 's'} will be considered as permanent.`;
    } else if (type == 'hours') {
        msg = `The employees that have worked for ${duration} hour${duration == 1 ? '' : 's'} will be considered as permanent.`;
    }
    //
    msg += `The employees that are on probation can use ${rate == 0 ? "0" : rate} ${$(`.jsTimeType-${pageType}`).text().match(/hour/gi) !== null ? "hour" : "day"}${rate == 1 ? '' : 's'}.`;
    //
    target.html(msg);
}

//
function loadAccrualPlans(
    type,
    plans
) {
    //
    type = type === undefined ? 'add' : type;
    //
    var
        rows = '',
        dt = 'hours(s)',
        yt = 'year(s)';
    //
    // if( $('#js-accrual-method-'+( type )+'').val() == 'hours_per_month'){
    //     dt = 'hour(s)';
    //     yt = 'month(s)';
    // }

    // For edit view
    if (plans !== undefined && plans.length !== 0) {
        if (plans[0] !== undefined) {
            plans.map(function (plan) {
                rows += '<div class="js-plan-row-' + (type) + '" style="padding: 20px 0 10px;">';
                rows += '    <span>Allow</span>';
                rows += '    <div class="form-group form-group-custom form-group-custom-settings csIn50">';
                rows += '        <input class="form-control form-control-custom js-pt js-plan-type-' + (type) + '" value="' + (plan.accrualRate) + '" />';
                rows += '    </div><span> extra <span class="js-plan-type">' + (dt) + '</span> after </span>';
                rows += '    <div class="form-group form-group-custom form-group-custom-settings csIn50">';
                rows += '        <input class="form-control form-control-custom js-py js-plan-year-' + (type) + '" value="' + (plan.accrualType) + '" />';
                rows += '    </div>';
                rows += '    <div class="form-group form-group-custom form-group-custom-settings csIn50 csW100">';
                rows += '        <select class="form-control form-control-custom js-pyt js-plan-duration-' + (type) + '"><option value="months" ' + (plan.accrualTypeM == 'months' ? 'selected="true"' : '') + '>Month(s)</option><option value="years" ' + (plan.accrualTypeM == 'years' ? 'selected="true"' : '') + '>Year(s)</option></select>';
                rows += '    </div>';
                rows += '    <span class="label label-danger js-plan-remove-btn" title="Remove the accrual plan" placement="top"><i class="fa fa-close"></i></span>';
                rows += '</div>';
            });
        }
        //
        $('#js-plan-area-' + (type) + '').html(rows);
        //
        return;
    }
    //
    if (plans === undefined) {
        // For add view
        rows += '<div class="js-plan-row-' + (type) + '" style="padding: 20px 0 10px;">';
        rows += '    <span>Allow</span>';
        rows += '    <div class="form-group form-group-custom form-group-custom-settings csIn50">';
        rows += '        <input class="form-control form-control-custom js-pt js-plan-type-' + (type) + '" />';
        rows += '    </div><span> extra <span class="js-plan-type">' + (dt) + '</span> after </span>';
        rows += '    <div class="form-group form-group-custom form-group-custom-settings csIn50">';
        rows += '        <input class="form-control form-control-custom js-py js-plan-year-' + (type) + '" />';
        rows += '    </div>';
        rows += '    <div class="form-group form-group-custom form-group-custom-settings csIn50 csW100">';
        rows += '        <select class="form-control form-control-custom  js-pyt js-plan-duration-' + (type) + '"><option value="months">Month(s)</option><option value="years" selected="true">Year(s)</option></select>';
        rows += '    </div>';
        rows += '    <span class="label label-danger js-plan-remove-btn" title="Remove the accrual plan" placement="top"><i class="fa fa-close"></i></span>';
        rows += '</div>';
        //
        $('#js-plan-area-' + (type) + '').append(rows);
    }
    //
}

//
function makePlanRow(planId, type) {
    var plan = getPlanFromArray(planId);
    if (plan.length == 0) {
        alertify.alert('ERROR!', 'Plan with Id ' + (planId) + ' not found.');
        return;
    }
    var rows = '';

    var slot = formatMinutes(plan.format, plan.default_timeslot, plan.allowed_timeoff, false, true),
        format = plan.format.split(':');
    //
    rows += '<li data-id="' + (plan.plan_id) + '" data-timeslot="' + (plan.default_timeslot) + '" data-format="' + (plan.format) + '" class="js-plan-row-' + (type) + '">';
    rows += '    <div class="timeline-content margin-right">';
    rows += '        <h4 class="timeline-field-title">' + (getPlanTitle(plan)) + ' <span title="Remove Plan" class="pull-right text-danger js-remove-plan-' + (type) + '"><i class="fa fa-close"></i></span></h4>';
    rows += '        <div class="row">';
    if (format.indexOf('D') != -1) {
        rows += '            <div class="col-lg-6 col-sm-6 col-xs-12">';
        rows += '                <div class="input-group pto-time-off-margin-custom">';
        rows += '                    <input type="text" class="form-control js-days-' + (type) + '" value="' + (slot.D) + '" />';
        rows += '                    <span class="input-group-addon">Days</span>';
        rows += '                </div>';
        rows += '            </div>';
    }
    if (format.indexOf('H') != -1) {
        rows += '            <div class="col-lg-6 col-sm-6 col-xs-12">';
        rows += '                <div class="input-group pto-time-off-margin-custom">';
        rows += '                    <input type="text" class="form-control js-hours-' + (type) + '" value="' + (slot.H) + '" />';
        rows += '                    <span class="input-group-addon">Hours</span>';
        rows += '                </div>';
        rows += '            </div>';
    }
    if (format.indexOf('M') != -1) {
        rows += '            <div class="col-lg-6 col-sm-6 col-xs-12">';
        rows += '                <div class="input-group pto-time-off-margin-custom">';
        rows += '                    <input type="text" class="form-control js-minutes-' + (type) + '" value="' + (slot.M) + '" />';
        rows += '                    <span class="input-group-addon">Minutes</span>';
        rows += '                </div>';
        rows += '            </div>';
    }
    rows += '        </div>';
    rows += '    </div>';
    rows += '</li>';
    $('#js-plan-area-' + (type) + '').append(rows);
}

//
function removePlan(planId, type) {
    $('li[data-id="' + (planId) + '"]').remove();
}

//
function isStepCompleted(step, type) {
    //
    if (page == 'add' || type == 'add') return window.timeoff.stepCompletedAdd(step);
    else if (page == 'edit' || type == 'edit') return window.timeoff.stepCompletedEdit(step);
    else if (page == 'reset' || type == 'reset') return window.timeoff.stepCompletedReset(step);
}

//
function loadTemplate(templateId) {
    //
    let o = {
        method: 'hours_per_month',
        time: 'none',
        frequency: 'none',
        frequencyMonths: 0,
        rate: 0,
        rateType: 'hours_per_month',
    };

    // Days Per Year - None
    //

    // Hours Per Month - Yearly
    //
    if (templateId == 10) {
        o.method = 'hours_per_month';
        o.time = 'none';
        o.frequency = 'yearly';
        o.rate = 24;
    }
    //
    if (templateId == 11) {
        o.method = 'hours_per_month';
        o.time = 'start_of_period';
        o.frequency = 'yearly';
        o.rate = 24;
    }
    //
    if (templateId == 12) {
        o.method = 'hours_per_month';
        o.time = 'end_of_period';
        o.frequency = 'yearly';
        o.rate = 24;
    }
    // Hours Per Month - Monthly
    //
    if (templateId == 13) {
        o.method = 'hours_per_month';
        o.time = 'none';
        o.frequency = 'yearly';
        o.rate = 3.33;
        o.rateType = 'hours_per_month';
    }
    //
    if (templateId == 14) {
        o.method = 'hours_per_month';
        o.time = 'start_of_period';
        o.frequency = 'yearly';
        o.rate = 3.33;
        o.rateType = 'hours_per_month';
    }
    //
    if (templateId == 15) {
        o.method = 'hours_per_month';
        o.time = 'end_of_period';
        o.frequency = 'yearly';
        o.rate = 3.33;
        o.rateType = 'hours_per_month';
    }

    // Hours Per Month - Custom
    //
    if (templateId == 16) {
        o.method = 'hours_per_month';
        o.time = 'none';
        o.frequency = 'custom';
        o.rate = 24;
        o.frequencyMonths = 4;
    }
    //
    if (templateId == 17) {
        o.method = 'hours_per_month';
        o.time = 'start_of_period';
        o.frequency = 'custom';
        o.rate = 24;
        o.frequencyMonths = 4;
    }
    //
    if (templateId == 18) {
        o.method = 'hours_per_month';
        o.time = 'end_of_period';
        o.frequency = 'custom';
        o.rate = 24;
        o.frequencyMonths = 4;
    }

    if (templateId == 19) {
        o.method = 'hours_per_month';
        o.time = 'none';
        o.frequency = 'none';
        o.rate = 40;
        o.frequencyMonths = 0;
    }

    //
    // $(`#js-accrual-method-${page}`).select2('val', o.method);
    // $(`#js-accrual-method-${page}`).trigger('change');
    $(`#js-accrual-time-${page}`).select2('val', o.time);
    $(`#js-accrual-time-${page}`).trigger('change');
    $(`#js-accrual-frequency-${page}`).select2('val', o.frequency);
    $(`#js-accrual-frequency-${page}`).trigger('change');
    if (o.frequencyMonths != 0) $(`#js-accrual-frequency-val-${page}`).val(o.frequencyMonths);
    if (o.rateType != 0) $(`#js-accrual-rate-type-${page}`).val(o.rateType);
    $(`#js-accrual-rate-${page}`).val(o.rate).trigger('keyup');
}

//
function finalStep(type) {
    //
    if (page == 'add' || type == 'add') return window.timeoff.finalStepCompletedAdd(getStep());
    else if (page == 'edit' || type == 'edit') return window.timeoff.finalStepCompletedEdit(getStep());
    else if (page == 'reset' || type == 'reset') return window.timeoff.finalStepCompletedReset(getStep());
}

//
function saveStep(d) {
    return localStorage.setItem(`${page}Policy`, JSON.stringify(d));
}

//
function getStep(d) {
    return JSON.parse(localStorage.getItem(`${page}Policy`));
}

//
function removeStep(d) {
    return localStorage.removeItem(`${page}Policy`);
}


// Pages
// Add policy page
function loadAddPage() {
    // localStorage.clear();
    //
    page = 'add';
    // Show loader
    ml(true, 'policy');
    // Hide all other pages
    $('.js-page').fadeOut(0);
    // Show page
    $('#js-page-add').fadeIn(500);
    // Check if categories and employees are loaded
    if (
        window.timeoff.categories === undefined ||
        window.timeoff.employees === undefined
    ) {
        setTimeout(loadAddPage, 2000);
        return;
    }

    // Reset view
    //
    $('.js-template-add').prop('checked', false);
    $('.js-template-add[value="0"]').prop('checked', true);
    // Set policy types
    $('#js-category-add').select2();
    $('#js-category-add').select2('val', 0);
    //
    $('#js-policy-type-add').select2({
        minimumResultsForSearch: -1
    });
    $('#js-policy-type-add').select2('val', 1);
    // Set policy title
    $('#js-policy-title-add').val();
    // Set sort order
    $('#js-sort-order-add').val(1);
    // Set employees
    $('#js-employee-add').select2();
    $('#js-employee-add').select2('val', 0);
    $('#js-employee-add').select2MultiCheckboxes({
        templateSelection: function (selected, total) {
            total = total - 1;
            return "Selected " + ($.inArray('all', $('#js-employee-add').val()) !== -1 ? total : selected.length) + " of " + total;
        }
    });
    $('#js-approvers-list-add').select2();
    $('#js-approvers-list-add').select2('val', 0);
    $('#js-approvers-list-add').select2MultiCheckboxes({
        templateSelection: function (selected, total) {
            total = total - 1;
            return "Selected " + ($.inArray('all', $('#js-approvers-list-add').val()) !== -1 ? total : selected.length) + " of " + total;
        }
    });
    //
    $('#js-employee-type-add').select2();
    $('#js-employee-type-add').select2('val', 'all');

    //Add by Alee on 4 Apr 2021
    $('#js-off-days-add').select2({ closeOnSelect: false });
    $('#js-off-days-add').select2('val', null);

    // Set approver check
    $('#js-approver-check-add').prop('checked', false);
    // Set archive check
    $('#js-archive-check-add').prop('checked', false);
    // Set include check
    $('#js-include-check-add').prop('checked', true);
    // Set accrual method
    // $('#js-accrual-method-add').select2({minimumResultsForSearch: -1});
    // $('#js-accrual-method-add').select2('val', 'hours_per_month');
    // $('#js-accrual-method-add').trigger('change');
    // Set accrual time
    $('#js-accrual-time-add').select2({ minimumResultsForSearch: -1 });
    $('#js-accrual-time-add').select2('val', 'none');
    $('#js-accrual-time-add').trigger('change');
    // // Set accrual frquency
    $('#js-accrual-frequency-add').select2({ minimumResultsForSearch: -1 });
    $('#js-accrual-frequency-add').select2('val', 'none');
    $('#js-accrual-frequency-add').trigger('change');
    $('#js-accrual-frequency-val-add').val(1);
    // Set accrual rate
    $('#js-accrual-rate-add').val(0);
    $('#js-accrual-rate-type-add option[value="days"]').prop('selected', true);
    // Set accrual minimum worked time
    $('#js-minimum-applicable-hours-add').val(0);
    $('.js-minimum-applicable-time-add[value="hours"]').prop('checked', true).trigger('click');
    // Set carryover
    $('#js-carryover-cap-check-add').select2({ minimumResultsForSearch: -1 });
    $('#js-carryover-cap-check-add').select2('val', 'no');
    $('#js-carryover-cap-check-add').trigger('change');
    $('#js-carryover-cap-add').val(0);
    // Set negative balance
    $('#js-accrual-balance-add').select2({ minimumResultsForSearch: -1 });
    $('#js-accrual-balance-add').select2('val', 'no');
    $('#js-accrual-balance-add').trigger('change');
    $('#js-maximum-balance-add').val(0);
    // Set policy applicable date
    $('.js-hire-date-add[value="hireDate"]').prop('checked', true).trigger('click');
    // Set policy reset date
    $('.js-policy-reset-date-add[value="policyDate"]').prop('checked', true).trigger('click');
    // New hire
    $('#js-accrue-new-hire-add').val(0);
    $('#js-newhire-prorate-add').val(0);
    // Plans
    $('#js-plan-box-add').show();
    $('#js-plan-box-add').find('ul').html('');
    $('#js-plan-area-add').html('');
    $('.jsPlanArea').html('');
    // Set default accural check
    $('#js-accrual-default-flow-add').prop('checked', true);
    // Show hionts
    $('.js-hint').hide(0);
    // Policy applicable date
    $('#js-custom-date-add').datepicker({
        dateFormat: 'mm-dd-yy',
        changeYear: true,
        changeMonth: true,
        onSelect: (d) => {
            if (d <= moment().format('MM-DD-YYYY')) $('#js-applicable-date-text-add').show();
            else $('#js-applicable-date-text-add').hide();
        }
    });
    // Policy reset date
    $('#js-custom-reset-date-add').datepicker({
        dateFormat: 'mm-dd-yy',
        changeYear: true,
        changeMonth: true,
    });
    // Hide all tabs
    $('.js-step').hide();
    $('.js-step[data-step="0"]').show();

    //
    ml(false, 'policy');
}

//
function loadViewPage() {
    // localStorage.clear();
    //
    page = 'view';
    //
    ml(true, 'policy');
    // Hide all other pages
    $('.js-page').fadeOut(0);
    // Show page
    $('#js-page-view').fadeIn(500);
    //
    window.timeoff.fetchCompanyPolicies();
}

// Edit policy page
function loadEditPage() {
    localStorage.clear();
    //
    page = 'edit';
    // Show loader
    ml(true, 'policy');
    // Hide all other pages
    $('.js-page').fadeOut(0);
    // Show page
    $('#js-page-edit').fadeIn(500);
    // Check if categories and employees are loaded
    if (
        window.timeoff.categories === undefined ||
        window.timeoff.employees === undefined
    ) {
        setTimeout(loadEditPage, 2000);
        return;
    }

    // Reset view
    //
    $('#js-policy-type-edit').select2({
        minimumResultsForSearch: -1
    });
    $('#js-policy-type-edit').select2('val', 1);
    // Set policy types
    $('#js-category-edit').select2();
    $('#js-category-edit').select2('val', 0);
    // Set policy title
    $('#js-policy-title-edit').val('');
    // Set sort order
    $('#js-sort-order-edit').val(1);
    // Set employees
    $('#js-employee-edit').select2();
    $('#js-employee-edit').select2('val', 0);
    $('#js-employee-edit').select2MultiCheckboxes({
        templateSelection: function (selected, total) {
            total = total - 1;
            return "Selected " + ($.inArray('all', $('#js-employee-edit').val()) !== -1 ? total : selected.length) + " of " + total;
        }
    });
    // Set employees
    $('#js-approvers-list-edit').select2();
    $('#js-approvers-list-edit').select2('val', 0);
    $('#js-approvers-list-edit').select2MultiCheckboxes({
        templateSelection: function (selected, total) {
            total = total - 1;
            return "Selected " + ($.inArray('all', $('#js-approvers-list-edit').val()) !== -1 ? total : selected.length) + " of " + total;
        }
    });
    //
    $('#js-employee-type-edit').select2();
    $('#js-employee-type-edit').select2('val', 'all');

    //Add by Alee on 4 Apr 2021
    $('#js-off-days-edit').select2({ closeOnSelect: false });
    $('#js-off-days-edit').select2('val', null);
    // Set approver check
    $('#js-approver-check-edit').prop('checked', false);
    // Set archive check
    $('#js-archive-check-edit').prop('checked', false);
    // Set include check
    $('#js-include-check-edit').prop('checked', true);
    // Set accrual method
    // $('#js-accrual-method-edit').select2({minimumResultsForSearch: -1});
    // $('#js-accrual-method-edit').select2('val', 'days_per_year');
    // $('#js-accrual-method-edit').trigger('change');
    // Set accrual time
    $('#js-accrual-time-edit').select2({ minimumResultsForSearch: -1 });
    $('#js-accrual-time-edit').select2('val', 'none');
    $('#js-accrual-time-edit').trigger('change');
    // Set accrual frquency
    $('#js-accrual-frequency-edit').select2({ minimumResultsForSearch: -1 });
    $('#js-accrual-frequency-edit').select2('val', 'none');
    $('#js-accrual-frequency-edit').trigger('change');
    $('#js-accrual-frequency-val-edit').val(1);
    // Set accrual rate
    $('#js-accrual-rate-edit').val(0);
    $('#js-accrual-rate-type-edit option[value="days"]').prop('selected', true);
    // Set accrual minimum worked time
    $('#js-minimum-applicable-hours-edit').val(0);
    $('.js-minimum-applicable-time-edit[value="hours"]').prop('checked', true).trigger('click');
    // Set carryover
    $('#js-carryover-cap-check-edit').select2({ minimumResultsForSearch: -1 });
    $('#js-carryover-cap-check-edit').select2('val', 'no');
    $('#js-carryover-cap-check-edit').trigger('change');
    $('#js-carryover-cap-edit').val(0);
    // Set negative balance
    $('#js-accrual-balance-edit').select2({ minimumResultsForSearch: -1 });
    $('#js-accrual-balance-edit').select2('val', 'no');
    $('#js-accrual-balance-edit').trigger('change');
    $('#js-maximum-balance-edit').val(0);
    setTimeout(() => {
        // Set policy applicable date
        $('.js-hire-date-edit[value="hireDate"]').prop('checked', true);
        $('.jsImplementDateBox-edit').hide();
        // Set policy reset date
        $('.js-policy-reset-date-edit[value="policyDate"]').prop('checked', true).trigger('change');
    }, 0);
    // New hire
    $('#js-accrue-new-hire-edit').val(0);
    $('#js-newhire-prorate-edit').val(0);
    // Plans
    $('#js-plan-box-edit').show();
    $('#js-plan-box-edit').find('ul').html('');
    $('#js-plan-area-edit').html('');
    $('.jsPlanArea').html('');
    // Set default accural check
    $('#js-accrual-default-flow-edit').prop('checked', true);
    // Show hionts
    $('.js-hint').hide(0);
    // Policy applicable date
    $('#js-custom-date-edit').datepicker({
        dateFormat: 'mm-dd-yy',
        changeYear: true,
        changeMonth: true,
        onSelect: (d) => {
            if (d <= moment().format('MM-DD-YYYY')) $('#js-applicable-date-text-edit').show();
            else $('#js-applicable-date-text-edit').hide();
        }
    });
    // Policy reset date
    $('#js-custom-reset-date-edit').datepicker({
        dateFormat: 'mm-dd-yy',
        changeYear: true,
        changeMonth: true,
    });
    // Hide all tabs
    $('.js-step').hide();
    $('.js-step[data-step="1"]').show();
    //
    // $('.jsEditResetCheckbox').bootstrapToggle('on');
    $('.js-step-tab[data-type="edit"]').parent().removeClass('active');
    $('.js-step-tab[data-type="edit"][data-step="1"]').parent().addClass('active');
    $('.js-step-tab[data-type="reset"]').parent().removeClass('active');
    $('.js-step-tab[data-type="reset"][data-step="1"]').parent().addClass('active');
    //
    loadResetPage();
    $(".jsEditResetCheckbox[data-type='cp']").trigger('click');
}

//
function loadResetPage() {
    // Reset view
    // Set policy types
    $('#js-category-reset').select2().prop('disabled', true);
    $('#js-category-reset').select2('val', 0);
    // Set policy title
    $('#js-policy-title-reset').val('').prop('disabled', true);
    // Set sort order
    $('#js-sort-order-reset').val(1).prop('disabled', true);
    // Set employees
    $('#js-employee-reset').select2().prop('disabled', true);
    $('#js-employee-reset').select2('val', 0);
    $('#js-employee-reset').select2MultiCheckboxes({
        templateSelection: function (selected, total) {
            total = total - 1;
            return "Selected " + ($.inArray('all', $('#js-employee-reset').val()) !== -1 ? total : selected.length) + " of " + total;
        }
    });
    //
    $('#js-employee-type-reset').select2();
    $('#js-employee-type-reset').select2('val', 'all');
    // Set approver check
    $('#js-approver-check-reset').prop('checked', false).prop('disabled', true);
    // Set archive check
    $('#js-archive-check-reset').prop('checked', false).prop('disabled', true);
    // Set include check
    $('#js-include-check-reset').prop('checked', true).prop('disabled', true);
    // Set accrual method
    // $('#js-accrual-method-reset').select2({minimumResultsForSearch: -1});
    // $('#js-accrual-method-reset').select2('val', 'days_per_year');
    // $('#js-accrual-method-reset').trigger('change');
    // Set accrual time
    $('#js-accrual-time-reset').select2({ minimumResultsForSearch: -1 });
    $('#js-accrual-time-reset').select2('val', 'none');
    $('#js-accrual-time-reset').trigger('change');
    // Set accrual frquency
    $('#js-accrual-frequency-reset').select2({ minimumResultsForSearch: -1 });
    $('#js-accrual-frequency-reset').select2('val', 'none');
    $('#js-accrual-frequency-reset').trigger('change');
    $('#js-accrual-frequency-val-reset').val(1);
    // Set accrual rate
    $('#js-accrual-rate-reset').val(0);
    $('#js-accrual-rate-type-reset option[value="days"]').prop('selected', true);
    // Set accrual minimum worked time
    $('#js-minimum-applicable-hours-reset').val(0);
    $('.js-minimum-applicable-time-reset[value="hours"]').prop('checked', true).trigger('click');
    // Set carryover
    $('#js-carryover-cap-check-reset').select2({ minimumResultsForSearch: -1 });
    $('#js-carryover-cap-check-reset').select2('val', 'no');
    $('#js-carryover-cap-check-reset').trigger('change');
    $('#js-carryover-cap-reset').val(0);
    // Set negative balance
    $('#js-accrual-balance-reset').select2({ minimumResultsForSearch: -1 });
    $('#js-accrual-balance-reset').select2('val', 'no');
    $('#js-accrual-balance-reset').trigger('change');
    $('#js-maximum-balance-reset').val(0);
    setTimeout(() => {
        // Set policy applicable date
        $('.js-hire-date-reset[value="hireDate"]').prop('checked', true);
        $('.jsImplementDateBox-reset').hide();
        // Set policy reset date
        $('.js-policy-reset-date-reset[value="policyDate"]').prop('checked', true).trigger('change');
    }, 0);
    // New hire
    $('#js-accrue-new-hire-reset').val(0);
    $('#js-newhire-prorate-reset').val(0);
    // Policy applicable date
    $('#js-custom-date-reset').datepicker({
        dateFormat: 'mm-dd-yy',
        changeYear: true,
        changeMonth: true,
        onSelect: (d) => {
            if (d <= moment().format('MM-DD-YYYY')) $('#js-applicable-date-text-reset').show();
            else $('#js-applicable-date-text-reset').hide();
        }
    });
    // Policy reset date
    $('#js-custom-reset-date-reset').datepicker({
        dateFormat: 'mm-dd-yy',
        changeYear: true,
        changeMonth: true,
    });

    //
    window.timeoff.startPolicyEditProcess();

}
