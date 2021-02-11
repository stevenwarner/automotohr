$(function(){


let 
    selectedEmployeeId = employeeId,
    selectedEmployeeName = employeeName,
    cOBJ = {
        policyId: 0,
        startDate: 0,
        endDate: 0,
        dateRows: '',
        status: 0,
        reason: 0,
        comment: 0,
        sendEmailNotification: 0,
        fromAdmin: 1
    };
//
$(document).on('click', '.jsCreateTimeOffBTN', function(e) {
    //
    e.preventDefault();
    //
    cOBJ.policyId = getField('#jsAddPolicy');
    cOBJ.startDate = getField('#jsStartDate');
    cOBJ.endDate = getField('#jsEndDate');
    cOBJ.status = getField('#jsStatus');
    cOBJ.reason = CKEDITOR.instances['jsReason'].getData();
    cOBJ.comment = CKEDITOR.instances['jsComment'].getData();
    cOBJ.sendEmailNotification = getField('.js-send-email:checked');
    cOBJ.dateRows = getRequestedDays('.jsDurationBox');
    //
    if(cOBJ.policyId == 0){
        //
        alertify.alert(
            'WARNING!',
            'Please select a policy.',
            () => {}
        );
        //
        return;
    }
    //
    if(cOBJ.startDate == 0){
        //
        alertify.alert(
            'WARNING!',
            'Please select the start date.',
            () => {}
        );
        //
        return;
    }
    //
    if(cOBJ.endDate == 0){
        //
        alertify.alert(
            'WARNING!',
            'Please select an end date.',
            () => {}
        );
        //
        return;
    }
    //
    if(window.location.pathname.match(/(lms)|(employee_management_system)|(dashboard)/gi) === null){
        //
        if(cOBJ.status == 'pending'){
            //
            alertify.alert(
                'WARNING!',
                'Please, either select approve or reject.',
                () => {}
            );
            //
            return;
        }
    }
    //
    if(cOBJ.dateRows.error) return;
    //
    let selectedPolicy = getPolicy(
        cOBJ.policyId,
        window.timeoff.cPolicies
    );
    // Check if it's not unlimited
    if(selectedPolicy.AllowedTime.M.minutes != 0){
        //
        if(cOBJ.dateRows.totalTime > selectedPolicy.RemainingTimeWithNegative.M.minutes){
            alertify.alert('WARNING!', `Requested time-off can not be greater than the allowed time i.e. "${selectedPolicy.RemainingTimeWithNegative.text}"`, () => {});
            return;
        }
    }
    //
    cOBJ.fromAdmin = 1;
    //
    ml(true, 'addTimeoffModalLoader');
    //
    $.post(
        handlerURL, Object.assign({
            action: 'create_timeoff',
            companyId: companyId,
            employerId: employerId,
            employeeId: selectedEmployeeId
        }, cOBJ),
        (resp) => {
            if(resp.Status === false){
                ml(false, 'addTimeoffModalLoader');
                alertify.alert('WARNING!', resp.Response, () => {});
                return;
            }
            //
            ml(false, 'addTimeoffModalLoader');
            //
            alertify.alert('SUCCESS!', resp.Response, () => {
                $('.jsModalCancel').removeAttr('data-ask');
                $('.jsModalCancel').trigger('click');
                window.location.reload();
            });
            //
            return;
        }
    );
});

// 
$(document).on('click', '#jsCreateTimeOffForEmployee', function(e){
    //
    e.preventDefault();
    //
    let 
    employeeId = 0,
    employeeName = '';
    //
    if($(this).data('id') !== undefined){
        employeeId = selectedEmployeeId;
        employeeName = selectedEmployeeName;
    } else{
        employeeId = $(this).closest('tr').data('id');
        employeeName = $(this).closest('tr').data('name');
    }
    selectedEmployeeId = employeeId;
    //
    Modal({
        Id: 'addTimeoffModal',
        Title: `Create Time-off`,
        Body: '',
        Buttons: [
            '<button class="btn btn-success dn jsCreateTimeOffEmployeeBTN">Create<button>'
        ],
        Loader: 'addTimeoffModalLoader',
        Ask: false
    }, async () => {
        ml(false, 'addTimeoffModalLoader');
        return;
        // Get modal body
        const policies = await getEmployeePolicies(employeeId);
        //
        if(policies.Data.length === 0){
            $('.jsAddTimeOff').remove();
            $('#addTimeoffModal').find('.csModalBody').append(`<div class="alert alert-success text-center">We are unable to find policies against this employee.</div>`);
            return;
        }
        //
        let 
        c = policies.Data.length,
        d = 0;
        //
        let newPolicies = {};
        //
        window.timeoff.cPolicies = policies.Data;
        //
        policies.Data.map((policy) => {
            if(policy.Reason != '') { d++; return; }
            //
            if(newPolicies[policy['Category']] === undefined)  newPolicies[policy['Category']] = [];
            //
            newPolicies[policy['Category']].push(policy);
        });
        //
        if(c == d){
            $('.jsAddTimeOff').remove();
            $('#addTimeoffModal').find('.csModalBody').append(`<div class="alert alert-success text-center">We are unable to find policies against this employee.</div>`);
            ml(false, 'addTimeoffModalLoader');
            return;
        }
        //
        let policyRows = '<option value="0" selected="true">[Select a policy]</option>';
        //
        $.each(newPolicies, (category, policies) => {
            policyRows += `<optgroup label="${category}">`;
            //
            policies.map((policy) => {
                policyRows += `<option value="${policy.PolicyId}">${policy.Title}</option>`;
            });
            policyRows += `</optgroup>`;
        });
        //
        const bodyText = await getModalBody('add');
        //
        $('.jsAddTimeOff').remove();
        $('#addTimeoffModal').find('.csModalBody').append(bodyText);
        //
        $('#jsAddPolicy').html(policyRows);
        $('#jsAddPolicy').select2();
        //
        $('#jsStartDate').datepicker({
            format: 'mm-dd-yyyy',
            changeYear: true,
            changeMonth: true,
            beforeShowDay: unavailable,
            onSelect: (date) => {
                $('#jsEndDate').datepicker('option', 'minDate', date);
                //
                getSideBarPolicies();
                //
                remakeRangeRows(
                    '#jsStartDate',
                    '#jsEndDate',
                    '.jsDurationBox'
                );
            }
        });
        $('#jsEndDate').datepicker({
            format: 'mm-dd-yyyy',
            changeYear: true,
            changeMonth: true,
            beforeShowDay: unavailable,
            onSelect: (date) => {
                //
                remakeRangeRows(
                    '#jsStartDate',
                    '#jsEndDate',
                    '.jsDurationBox'
                );
            }
        });
        $('#js-vacation-return-date').datepicker({
            format: 'mm-dd-yyyy',
            changeYear: true,
            changeMonth: true,
            minDate: 1,
            beforeShowDay: unavailable,
        });
        $('#js-bereavement-return-date').datepicker({
            format: 'mm-dd-yyyy',
            changeYear: true,
            changeMonth: true,
            minDate: 1,
            beforeShowDay: unavailable,
        });
        $('#js-compensatory-return-date').datepicker({
            format: 'mm-dd-yyyy',
            changeYear: true,
            changeMonth: true,
            minDate: 1,
            beforeShowDay: unavailable,
        });
        $('#js-compensatory-start-time').datepicker({
            format: 'g:i A',
            formatTime: 'g:i A',
            step: 15
        });
        $('#js-compensatory-end-time').datepicker({
            format: 'g:i A',
            formatTime: 'g:i A',
            step: 15
        });
        //
        if(window.location.pathname.match(/(lms)|(employee_management_system)|(dashboard)/gi) !== null){
            // Hide comment area
            $('#jsComment').parent().hide();
            $('.jsEmailBoxAdd').hide();
            $('#jsStatus').html('<option value="pending">Pending</option>').parent().hide(0);
            $('#jsStartDate').datepicker('option', 'minDate', -1);
            $('#jsEndDate').datepicker('option', 'minDate', -1);
        } else{
             //
            $('#jsStatus').select2({ minimumResultsForSearch: 5 })
        }

        //
        CKEDITOR.config.toolbar = [['Bold', 'Italic', 'Underline']];
        CKEDITOR.replace('jsReason');
        if($('#jsComment').length > 0) CKEDITOR.replace('jsComment');
        // Get policies by date
        getSideBarPolicies(employeeId);
        //
        ml(false, 'addTimeoffModalLoader');
    });
});

// $(document).on('click', '#addTimeoffModal .jsModalCancel', (e) => {
//     $('#jsStartDate').datepicker('destroy');
//     $('#jsEndDate').datepicker('destroy');
//     $('#jsStartDate').removeClass("hasDatepicker").removeAttr('id');
//     $('#jsEndDate').removeClass("hasDatepicker").removeAttr('id');
//     $('#ui-datepicker-div').remove();
//     $('#addTimeoffModal').remove();
// });

//
function getModalBody(type){
    return new Promise((res) => {
        $.post(
            handlerURL, 
            {
                action: 'get_modal',
                companyId: companyId,
                employerId: employerId,
                employeeId: employeeId,
                type : type
            }, 
            (resp) => {
                res(resp);
            }
        );
    });
}

//
function getEmployeePolicies(employeeId){
    return new Promise((res) => {
        $.post(
            handlerURL, 
            {
                action: 'get_employee_policies',
                companyId: companyId,
                employerId: employerId,
                employeeId: selectedEmployeeId
            }, 
            (resp) => {
                res(resp);
            }
        );
    });
}

//
function getSideBarPolicies(){
    //
    $('.jsAsOfTodayPolicies').html('');
    //
    $.post(
        handlerURL, 
        {
            action: 'get_employee_policies_by_date',
            companyId: companyId,
            employerId: employerId,
            employeeId: selectedEmployeeId,
            fromDate: $('#jsStartDate').val()
        }, 
        (resp) => {
            //
            if(resp.Data.length > 0){
                window.timeoff.cPolicies = resp.Data;
                //
                let rows = '';
                //
                resp.Data.map((policy) => {
                    if(policy.Reason != '') return;
                    rows += `
                        <div>
                            <strong>${policy.Title}</strong>
                            <br />
                            <span>Remaining Time: ${policy.AllowedTime.M.minutes == 0 && policy.Reason == '' ? 'Unlimited' : policy.RemainingTime.text}</span>
                            <br />
                            <span>Employement Status: ${ucwords(policy.EmployementStatus)}</span>  
                        </div>
                        <hr />
                    `;
                });
                //
                $('.jsAsOfTodayPolicies').html(rows);
            }
        }
    );
}

//
function unavailable(date) {
    var dmy = moment(date).format('MM-DD-YYYY');
    let d = moment(date).format('dddd').toString().toLowerCase();
    let t = 1;
    if($.inArray(d, timeOffDays) !== -1) {
        t = { work_on_holiday: 0, holiday_title: 'Weekly off'};
    } else{
        t = inObject(dmy, holidayDates);
    }
    if (t == -1) {
        return [true, ""];
    } else {
        if (t.work_on_holiday == 1) {
            return [true, "set_disable_date_color", t.holiday_title];
        } else {
            return [false, "", t.holiday_title];
        }
    }
} 
})