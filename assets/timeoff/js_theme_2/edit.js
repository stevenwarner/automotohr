$(function () {
    let
        selectedRequestId = 0,
        selectedEmployeeId = 0,
        oldTime = 0,
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
    $(document).on('click', '.jsEditTimeOffBTN', function (e) {
        //
        e.preventDefault();
        //
        policyOffDays = undefined;
        //
        let policy = getSelectedPolicy(
            getField('#jsEditPolicy')
        );
        //
        if (policy.length == 0) {
            //
            alertify.alert(
                'WARNING!',
                'You don\'t have any policies. Please select a different date.',
                () => { }
            );
            //
            return;
        }
        //
        cOBJ.policyId = getField('#jsEditPolicy');
        cOBJ.startDate = getField('#jsStartDateEdit');
        cOBJ.endDate = getField('#jsEndDateEdit');
        cOBJ.status = getField('#jsStatusEdit');
        cOBJ.reason = CKEDITOR.instances['jsReasonEdit'].getData();
        cOBJ.comment = CKEDITOR.instances['jsCommentEdit'].getData();
        cOBJ.sendEmailNotification = getField('.js-send-emailEdit:checked');
        cOBJ.dateRows = getRequestedDays('.jsDurationBox', 'edit');
        //
        if (cOBJ.policyId == 0) {
            //
            alertify.alert(
                'WARNING!',
                'Please select a policy.',
                () => { }
            );
            //
            return;
        }
        //
        if (cOBJ.startDate == 0) {
            //
            alertify.alert(
                'WARNING!',
                'Please select the start date.',
                () => { }
            );
            //
            return;
        }
        //
        if (cOBJ.endDate == 0) {
            //
            alertify.alert(
                'WARNING!',
                'Please select an end date.',
                () => { }
            );
            //
            return;
        }
        //
        if (window.timeoff.isMine == undefined || window.timeoff.isMine == 0) {
            if (cOBJ.status == 'pending') {
                //
                alertify.alert(
                    'WARNING!',
                    'Please either approve/reject the time off.',
                    () => { }
                );
                //
                return;
            }
        }
        //
        if (cOBJ.dateRows.error) return;
        //
        let selectedPolicy = getPolicy(
            cOBJ.policyId,
            window.timeoff.cPolicies
        );
        //
        if (selectedPolicy.OffDays == null) {
            policyOffDays = undefined;
        } else {
            policyOffDays = selectedPolicy.OffDays.split(',');
        }
        // Check if it's not unlimited
        if (selectedPolicy.IsUnlimited == 0) {
            //
            if (selectedPolicy.RemainingTimeWithNegative.M.minutes <= 0) {
                alertify.alert('WARNING!', `You don't have any time left against this policy.`, () => { });
                return;
            }
            //
            if (cOBJ.dateRows.totalTime > selectedPolicy.RemainingTimeWithNegative.M.minutes) {
                alertify.alert('WARNING!', `Requested time-off can not be greater than the allowed time i.e. "${selectedPolicy.RemainingTimeWithNegative.text}"`, () => { });
                return;
            }
        }
        //
        cOBJ.fromAdmin = 1;
        if (window.location.pathname.match(/(dashboard)/gi) !== null) {
            cOBJ.fromAdmin = 0;
        }
        cOBJ.requestId = selectedRequestId;
        //
        ml(true, 'editModalLoader');
        //
        $.post(
            handlerURL, Object.assign({
                action: 'update_timeoff',
                companyId: companyId,
                employerId: employerId,
                employeeId: selectedEmployeeId
            }, cOBJ),
            (resp) => {
                if (resp.Status === false) {
                    ml(false, 'editModalLoader');
                    alertify.alert('WARNING!', resp.Response, () => { });
                    return;
                }
                //
                ml(false, 'editModalLoader');
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
    $(document).on('click', '.jsEditTimeOff', function (e) {
        //
        e.preventDefault();
        //
        console.log($(this).closest('.jsBox').data())
        let requestId = $(this).closest('.jsBox').data('id'),
            employeeId = $(this).closest('.jsBox').data('userid'),
            status = $(this).closest('.jsBox').data('status'),
            view = $(this).closest('.jsBox').data('view');
        selectedRequestId = requestId;
        selectedEmployeeId = employeeId;
        //
        timeRowsOBJEdit = {};
        //
        Modal({
            Id: 'editModal',
            Title: `Edit Time off for ${$(this).closest('.jsBox').data('name')}`,
            Body: '',
            Buttons: [
                '<button class="btn btn-orange btn-theme jsCreateTimeOffBalanceEdit" style="margin-right: 10px;" title="See employee balance" placement="left"><i class="fa fa-balance-scale" aria-hidden="true"></i>&nbsp;View Balance History</button>',
                '<button class="btn btn-black jsCreateTimeOffBalanceBackEdit dn" style="margin-right: 10px;" ><i class="fa fa-long-arrow-left" aria-hidden="true"></i>&nbsp;Back To Create</button>',
                `<a class="btn btn-orange" target="_blank" style="margin-right: 5px; margin-top: -5px;" href="${baseURL}timeoff/print/requests/${requestId}"><i class="fa fa-print"></i> Print</a>`,
                `<a class="btn btn-orange" target="_blank" style="margin-right: 5px; margin-top: -5px;" href="${baseURL}timeoff/download/requests/${requestId}"><i class="fa fa-download"></i> Download</a>`,
                status == 'cancelled' || view == 1 ? '' : '<button class="btn btn-orange jsEditTimeOffBTN">Update</button>'
            ],
            Loader: 'editModalLoader',
            Ask: false
        }, async () => {
            //
            if (status == 'cancelled' || view == 1) $('.jsModalCancel').removeAttr('data-ask');
            //
            if (window.timeoff.companyEmployees === undefined) {
                const resp1 = await fetchCompanyEmployees();
                window.timeoff.companyEmployees = resp1.Data;
            }
            // Get modal body
            const resp = await getEmployeePolicies(employeeId);
            const policies = resp.Data.Policies;
            const approvers = resp.Data.Approvers;
            //
            if (policies.length === 0) {
                $('.jsAddTimeOff').remove();
                $('#editModal').find('.csModalBody').append(`<div class="alert alert-success text-center">We are unable to find policies against this employee.</div>`);
                ml(false, 'editModalLoader');
                return;
            }
            //
            const request = await getRequestById(requestId);
            //
            cOBJ.policyId = request.Data.timeoff_policy_sid;
            cOBJ.startDate = moment(request.Data.request_from_date).format('MM/DD/YYYY');
            cOBJ.endDate = moment(request.Data.request_to_date).format('MM/DD/YYYY');
            cOBJ.dateRows = JSON.parse(request.Data.timeoff_days);
            cOBJ.status = request.Data.status;
            cOBJ.reason = request.Data.reason;
            cOBJ.comment = '';
            cOBJ.sendEmailNotification = 0;
            cOBJ.fromAdmin = 1;
            //
            oldTime = cOBJ.dateRows.totalTime;
            //
            let newPolicies = {};
            //
            window.timeoff.cPolicies = policies;
            //
            policies.map((policy) => {
                //
                if (newPolicies[policy['Category']] === undefined) newPolicies[policy['Category']] = [];
                //
                newPolicies[policy['Category']].push(policy);
            });
            //
            let policyRows = '<option value="0" selected="true">[Select a policy]</option>';
            //
            $.each(newPolicies, (category, policies) => {
                policyRows += `<optgroup label="${category}">`;
                //
                policies.map((policy) => {
                    policyRows += `<option value="${policy.PolicyId}">${policy.Title} (<strong class="text-${policy.categoryType == 1 ? 'success' : 'danger'}">${policy.categoryType == 1 ? 'Paid' : 'Unpaid'}</strong>)</option>`;
                });
                policyRows += `</optgroup>`;
            });
            //
            const bodyText = await getModalBody('edit');
            //
            $('.jsAddTimeOff').remove();
            $('#editModal').find('.csModalBody').append(bodyText);
            //
            if (status == 'cancelled' || view == 1) {
                $('.jsModalCancel').removeAttr('data-ask');
                $('.jsEditTimeOffBTN').remove();
                $('#jsStatusEdit').append('<option value="cancelled">Canceled</option>');
            }
            //
            $('#jsEditPolicy').html(policyRows);
            $('#jsEditPolicy').select2();
            //
            $('#jsStartDateEdit').datepicker({
                format: 'mm-dd-yyyy',
                changeYear: true,
                changeMonth: true,
                beforeShowDay: unavailable,
                onSelect: () => {
                    //

                    $('#asoffdate').text('AS Of  ' + moment($('#jsStartDateEdit').val(), 'MM/DD/YYYY').format(timeoffDateFormat));
                    getSideBarPolicies();
                    //
                    remakeRangeRows(
                        '#jsStartDateEdit',
                        '#jsEndDateEdit',
                        '.jsDurationBox',
                        'edit'
                    );
                }
            });
            $('#jsEndDateEdit').datepicker({
                format: 'mm-dd-yyyy',
                changeYear: true,
                changeMonth: true,
                beforeShowDay: unavailable,
                onSelect: () => {
                    //
                    remakeRangeRows(
                        '#jsStartDateEdit',
                        '#jsEndDateEdit',
                        '.jsDurationBox',
                        'edit'
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

            if (window.location.pathname.match(/(lms)|(employee_management_system)|(dashboard)/gi) !== null && window.timeoff.isMine == 1) {
                // Hide comment area
                $('#jsCommentEdit').parent().hide();
                $('.jsEmailBoxEdit').hide();
                $('#jsStatusEdit').html('<option value="pending">Pending</option>').parent().hide(0);
                $('#jsStartDateEdit').datepicker('option', 'minDate', -1);
                $('#jsEndDatetEdit').datepicker('option', 'minDate', -1);
            } else {
                //
                $('#jsStatusEdit').select2({ minimumResultsForSearch: 5 });
                $('#jsStatusEdit').select2('val', cOBJ.status);
            }
            //
            CKEDITOR.config.toolbar = [
                ['Bold', 'Italic', 'Underline']
            ];
            CKEDITOR.replace('jsReasonEdit');
            if ($('#jsCommentEdit').length > 0) CKEDITOR.replace('jsCommentEdit');
            //
            $('#jsEditPolicy').select2('val', cOBJ.policyId);
            $('#jsStartDateEdit').val(cOBJ.startDate);
            $('#jsEndDateEdit').val(cOBJ.endDate);
            //
            CKEDITOR.instances['jsReasonEdit'].setData(cOBJ.reason);

            //
            cOBJ.dateRows.days.map((v) => {
                //
                let newTime = timeConvert(v.time);
                let newDate = moment(v.date, 'MM-DD-YYYY').format('MMDDYYYY');
                timeRowsOBJEdit[newDate] = {};
                timeRowsOBJEdit[newDate]['hour'] = newTime.hours;
                timeRowsOBJEdit[newDate]['partial'] = v.partial == undefined ? 'fullday' : v.partial;
                timeRowsOBJEdit[newDate]['minute'] = newTime.minutes;
            });
            //        //
            remakeRangeRowsEdit(
                '#jsStartDateEdit',
                '#jsEndDateEdit',
                '.jsDurationBox',
                cOBJ.dateRows
            );
            //
            window.timeoff.companyEmployees.map(function (emp) {
                if (emp.user_id == selectedEmployeeId) {
                    var employeeJoinedAt = emp['joined_at'] == null ? emp['joined_at'] : emp['registration_date'];
                    //
                    employeeJoinedAt = moment(employeeJoinedAt, 'YYYY-MM-DD').format(timeoffDateFormat);
                    //
                    $('#jsEmployeeInfoEdit').html(`
                    <figure>
                        <img src="${getImageURL(emp.image)}"
                            class="csRadius50">
                        <div class="csTextBox">
                            <p>${emp.first_name} ${emp.last_name}</p>
                            <p class="csTextSmall"> ${remakeEmployeeName(emp, false)}</p>
                            <p class="csTextSmall">${emp.email}</p>
                            <p class="csTextSmall">${emp.anniversary_text}</p>
                        </div>
                    </figure>
                    <div class="clearfix"></div>
                    `);
                }
            });
            // Get policies by date
            getSideBarPolicies(employeeId);
            //
            if (status == 'cancelled' || view == 1) {
                $('#editModal').find('select').prop('disabled', true);
                $('#editModal').find('input').prop('disabled', true);
            }
            setApproversView(approvers, request.Data.history);
            setHistoryView(request.Data.history);
            //
            setUpcomingTimeOffs(selectedEmployeeId);
            //
            ml(false, 'editModalLoader');

        });
    });

    /**
     * @param {Object} event
     */
    $(document).on('click', '.jsCreateTimeOffBalanceEdit', function (event) {
        //
        event.preventDefault();
        //
        if (selectedEmployeeId == null) {
            alertify.alert('Warning!', 'Please, select an employee.');
            return;
        }
        //
        $(this).hide(0);
        $('.jsCreateTimeOffBalanceBackEdit').show(0);
        $('.jsCreateTimeOffBTN').hide(0);
        $('#editModal').find('[data-page="main"]').hide(0);
        $('#editModal').find('[data-page="balance-view"]').show(0);
        //
        $('#editModal').find('.csModalHeaderTitle span:nth-child(1)').text(
            $('#editModal').find('.csModalHeaderTitle span:nth-child(1)').text().trim().replace('Edit Time off', 'Balance History')
        );
        //
        $.post(
            handlerURL, {
            action: 'get_employee_balance_history',
            companyId: companyId,
            employerId: employerId,
            employeeId: selectedEmployeeId,
        }
        ).done(function (resp) {
            //
            var rows = '';
            //
            if (resp.Data.length === 0) {
                rows += '<tr>';
                rows += '   <td colspan="6">';
                rows += '       <p class="alert alert-info text-center">';
                rows += '          No balance history found.';
                rows += '       </p>';
                rows += '   </td>';
                rows += '</tr>';
            } else {
                var
                    totalTOs = 0,
                    totalTimeTaken = {},
                    totalManualTime = {};
                //
                if (resp.Data[0].timeoff_breakdown.active.hour !== undefined) {
                    totalTimeTaken['hour'] = 0;
                    totalManualTime['hour'] = 0;
                }

                //
                if (resp.Data[0].timeoff_breakdown.active.minutes !== undefined) {
                    totalTimeTaken['minutes'] = 0;
                    totalManualTime['minutes'] = 0;
                }

                //
                resp.Data.map(function (balance) {
                    //
                    var
                        startDate = '',
                        endDate = '',
                        employeeName = '',
                        employeeRole = '';
                    //
                    if (balance.is_manual == 0 && balance.is_allowed == 1) {
                        startDate = moment(balance.effective_at, 'YYYY-MM-DD').format(timeoffDateFormat);
                        endDate = '';
                        employeeName = '-';
                        employeeRole = '';
                    } else if (balance.is_manual == 1) {
                        startDate = moment(balance.effective_at, 'YYYY-MM-DD').format(timeoffDateFormat);
                        endDate = moment(balance.effective_at, 'YYYY-MM-DD').format(timeoffDateFormat);
                        employeeName = balance.first_name + ' ' + balance.last_name;
                        employeeRole = remakeEmployeeName(balance, false);
                        //
                        if (balance.timeoff_breakdown.active.hours !== undefined) {
                            //
                            if (totalManualTime['hours'] === undefined) {
                                totalManualTime['hours'] = 0;
                            }
                            totalManualTime['hours'] += parseInt(balance.timeoff_breakdown.active.hours);
                        }
                        //
                        if (balance.timeoff_breakdown.active.minutes !== undefined) {
                            //
                            if (totalManualTime['minutes'] === undefined) {
                                totalManualTime['minutes'] = 0;
                            }
                            totalManualTime['minutes'] += parseInt(balance.timeoff_breakdown.active.minutes);
                        }
                    } else {
                        totalTOs++;
                        startDate = moment(balance.request_from_date, 'YYYY-MM-DD').format(timeoffDateFormat);
                        endDate = moment(balance.request_to_date, 'YYYY-MM-DD').format(timeoffDateFormat);
                        employeeName = balance.approverName;
                        employeeRole = balance.approverRole;
                        //
                        if (balance.timeoff_breakdown.active.hours !== undefined) {
                            //
                            if (totalTimeTaken['hours'] === undefined) {
                                totalTimeTaken['hours'] = 0;
                            }
                            totalTimeTaken['hours'] += parseInt(balance.timeoff_breakdown.active.hours);
                        }
                        //
                        if (balance.timeoff_breakdown.active.minutes !== undefined) {
                            //
                            if (totalTimeTaken['minutes'] === undefined) {
                                totalTimeTaken['minutes'] = 0;
                            }
                            totalTimeTaken['minutes'] += parseInt(balance.timeoff_breakdown.active.minutes);
                        }
                    }
                    //
                    rows += '<tr>';
                    rows += '   <td>';
                    rows += '       <strong>';
                    rows += employeeName + '<br>';
                    rows += '       </strong>';
                    rows += employeeRole;
                    rows += '   </td>';
                    rows += '   <td>';
                    rows += '       <strong>' + (balance.title) + '</strong>';
                    rows += '       <p>' + (startDate) + (endDate != '' ? ' - ' + endDate : '') + '</p>';
                    rows += '   </td>';
                    rows += ' <td class="' + (balance.is_added == 0 ? 'text-danger' : 'text-success') + '"><i class="fa fa-arrow-' + (balance.is_added == 0 ? 'down ' : 'up') + '"></i>&nbsp;' + (balance.timeoff_breakdown.text) + '</td>';
                    rows += '   <td>';
                    rows += (balance.note != '' ? balance.note : '-');
                    rows += '   </td>';
                    rows += '   <td>';
                    rows += moment(balance.created_at, 'YYYY-MM-DD').format(timeoffDateFormatWithTime);
                    rows += '   </td>';
                    rows += '   <td>';
                    rows += '       <strong class="text-' + (balance.is_manual == 1 ? "success" : "danger") + '">' + (balance.is_manual == 1 ? "Yes" : "No") + '</strong>';
                    rows += '   </td>';
                    rows += '</tr>';
                    rows += '<tr>';
                    rows += '   <td colspan="6">';
                    if (balance.is_manual == 0 && balance.is_allowed == 1) {
                        rows += '       <p><strong>Note</strong>: A balance of <b>'+(balance.added_time/60)+'</b> hours is available against policy <b>"' +balance.title+ '"</b> effective from <b>' + moment(balance.effective_at, 'YYYY-MM-DD').format(timeoffDateFormat)+'</b>';
                    } else {
                        rows += '       <p><strong>Note</strong>: <strong>' + (employeeName) + '</strong> has ' + (balance.is_manual == 1 ? (balance.is_added == 1 ? 'added balance' : 'subtracted balance') : 'approved time off') + ' against policy "<strong>' + (balance.title) + '</strong>" on <strong>' + (moment(balance.created_at, 'YYYY-MM-DD').format(timeoffDateFormatWithTime)) + '</strong> which will take effect ' + (startDate == endDate ? 'on ' : ' from ') + ' <strong>' + (startDate) + '' + (startDate != endDate ? (' to  ' + endDate) : '') + '</strong>.</p>';
                    }
                    rows += '   </td>';
                    rows += '</tr>';
                });
                //
                $('.jsCreateTimeOffNumber').text(totalTOs);
                $('.jsCreateTimeOffTimeTaken').text(getText(totalTimeTaken));
                $('.jsCreateTimeOffManualAllowedTime').text(getText(totalManualTime));
            }
            //
            $('#jsCreateTimeoffBalanceBody').html(rows);
            //
            ml(false, "balance-view");
        });
    });


    /**
     * @param {Object} event
     */
    $(document).on('click', '.jsCreateTimeOffBalanceBackEdit', function (event) {
        //
        event.preventDefault();
        //
        $(this).hide(0);
        $('.jsCreateTimeOffBalanceEdit').show(0);
        $('.jsCreateTimeOffBTN').show(0);
        $('#editModal').find('[data-page="balance-view"]').hide(0);
        $('#editModal').find('[data-page="main"]').show(0);
        //
        $('#editModal').find('.csModalHeaderTitle span:nth-child(1)').text(
            $('#editModal').find('.csModalHeaderTitle span:nth-child(1)').text().trim().replace('Balance History', 'Edit Time off')
        );
    });

    //
    function getModalBody(type) {
        return new Promise((res) => {
            $.post(
                handlerURL, {
                action: 'get_modal',
                companyId: companyId,
                employerId: employerId,
                employeeId: employeeId,
                type: type,
                formLMS: window.location.pathname.match(/(lms)|(create_employee)|(employee_management_system)|(dashboard)/gi) !== null ? 1 : 0
            },
                (resp) => {
                    res(resp);
                }
            );
        });
    }

    //
    function getEmployeePolicies(employeeId) {
        return new Promise((res) => {
            $.post(
                handlerURL, {
                action: 'get_employee_policies_with_approvers',
                companyId: companyId,
                employerId: employerId,
                employeeId: employeeId
            },
                (resp) => {
                    res(resp);
                }
            );
        });
    }

    //
    function getRequestById(requestId) {
        return new Promise((res) => {
            $.post(
                handlerURL, {
                action: 'get_request_by_id',
                companyId: companyId,
                employerId: employerId,
                employeeId: selectedEmployeeId,
                requestId: requestId
            },
                (resp) => {
                    res(resp);
                }
            );
        });
    }

    //
    function getSideBarPolicies() {
        //
        $('.jsAsOfTodayPolicies').html('');
        //
        $.post(
            handlerURL, {
            action: 'get_employee_policies_by_date',
            companyId: companyId,
            employerId: employerId,
            employeeId: selectedEmployeeId,
            fromDate: $('#jsStartDateEdit').val()
        },
            (resp) => {
                //
                window.timeoff.cPolicies = resp.Data;
                $('.jsEditTimeOffBTN').prop('disabled', false);
                $('#jsEndDateEdit').prop('disabled', false);
                $('#jsAddPolicyEdit').prop('disabled', false);
                //
                let newPolicies = [];
                let newPoliciesObj = {};
                //
                if (resp.Data.length > 0) {
                    //
                    let rows = '';
                    //
                    resp.Data.map((policy) => {
                        if (policy.Reason != '') return;
                        //
                        newPolicies.push(policy);
                        //
                        if (newPoliciesObj[policy['Category']] === undefined) newPoliciesObj[policy['Category']] = [];
                        newPoliciesObj[policy['Category']].push(policy);
                        //
                        rows += `
                        <div class="p10">
                        <strong>${policy.Title} (<strong class="text-${policy.categoryType == 1 ? 'success' : 'danger'}">${policy.categoryType == 1 ? 'Paid' : 'Unpaid'}</strong>)</strong>
                        <br />
                        <span>Remaining Time: ${policy.AllowedTime.M.minutes == 0 && policy.Reason == '' ? 'Unlimited' : policy.RemainingTime.text}</span>
                        <br />
                        <span>Scheduled Time: ${policy.AllowedTime.M.minutes == 0 && policy.Reason == '' ? 'Unlimited' : policy.ConsumedTime.text}</span>
                        <br />
                        <span>Employment Status: ${ucwords(policy.EmployementStatus)}</span> <br> 
                        <span><strong>Policy Cycle</strong> <br>
                        Last Anniversary: ${moment(policy.lastanniversary, 'YYYY/MM/DD').format(timeoffDateFormat)} <br>
                        Upcoming Anniversary: ${moment(policy.upcominganniversary, 'YYYY/MM/DD').format(timeoffDateFormat)}
                        </span> 
                        </div>
                        <hr />
                        `;
                    });
                    //
                    window.timeoff.cPolicies = newPolicies;
                    //
                    $('#jsAsOfTodayPolicies').html(rows);
                    //
                     //
                    // add policy dropdown with selected date.
                    let policyRows = '<option value="0" selected="true">[Select a policy]</option>';
                    //
                    $.each(newPoliciesObj, (category, policies) => {
                        policyRows += `<optgroup label="${category}">`;
                        //
                        policies.map((policy) => {
                            policyRows += `<option value="${policy.PolicyId}">${policy.Title} (<strong class="text-${policy.categoryType == 1 ? 'success' : 'danger'}">${policy.categoryType == 1 ? 'Paid' : 'Unpaid'}</strong>)</option>`;
                        });
                        policyRows += `</optgroup>`;
                    });
                    //
                    $('#jsEditPolicy').html(policyRows);
                    $('#jsEditPolicy').select2();
                }
                //
                if (window.timeoff.cPolicies.length == 0) {
                    $('#jsAsOfTodayPolicies').html('<div class="alert alert-success">No policies found.</div>');
                    $('.jsEditTimeOffBTN').prop('disabled', true);
                    $('#jsEndDateEdit').prop('disabled', true);
                    $('#jsAddPolicyEdit').prop('disabled', true);
                }
            }
        );
    }


    //
    function unavailable(date) {
        //
        var checkOffDays = policyOffDays === undefined ? timeOffDays : policyOffDays;
        //
        var dmy = moment(date).format('MM-DD-YYYY');
        let d = moment(date).format('dddd').toString().toLowerCase();
        let t = 1;
        if ($.inArray(d, checkOffDays) !== -1) {
            t = { work_on_holiday: 0, holiday_title: 'Weekly off' };
        } else {
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

    //
    function setApproversView(
        approvers,
        history
    ) {
        if (approvers.length === 0) {
            $('#jsApproversListingEdit').html('<p>No approvers found.</p>');
            $('#jsApproversListingNoteEdit').hide();
            return;
        }
        //
        let rows = '';
        let mRows = '';
        //
        approvers.map((approver) => {
            //
            let a = getApproverAction(approver.userId, history);
            if (a.length == 0) {
                a[0] = ' - waiting for approval';
                a[1] = 'waiting';
            }
            let msg = `${remakeEmployeeName(approver)} ${a[0]}`;
            //
            //
            rows += `
            <div class="csApproverBox" title="Approver" data-content="${msg}">
            <img src="${approver.profile_picture == null || approver.profile_picture == '' ? awsURL + 'test_file_01.png' : awsURL + approver.profile_picture}" />
            <i class="fa fa-${a[1] == 'approved' ? 'check-circle text-success' : (a[1] == 'rejected' ? 'times-circle text-danger' : 'clock-o')}"></i>
            </div>
            `;
            mRows += `
            <div class="csApproverBox">
                <div class="employee-info">            
                    <figure>                
                        <img src="${approver.profile_picture == null || approver.profile_picture == '' ? awsURL + 'test_file_01.png' : awsURL + approver.profile_picture}" />  
                        <i class="fa fa-${a[1] == 'approved' ? 'check-circle text-success' : (a[1] == 'rejected' ? 'times-circle text-danger' : 'clock-o')}"></i>        
                    </figure>            
                    <div class="text">                
                        <h4>${msg}</h4>                
                        <p><a href="http://automotohr.local/employee_profile/${approver.userId}" target="_blank">Id: ${getEmployeeId(approver.userId, approver.employee_number)}</a></p>            
                    </div>        
                </div>
            </div>
        `;
        });
        //
        $('#jsApproversListingEdit').html(rows);
        $('#jsApproversListingMobileEdit').html(mRows);
        $('#jsApproversListingNoteEdit').show();
        //
        $('.csApproverBox').popover({
            html: true,
            trigger: 'hover',
            placement: 'top'
        });
    }



    //
    function setHistoryView(
        history
    ) {
        //
        let rows = '';
        //
        if (history.length == 0) {
            rows = '<p class="alert alert-info text-center">No History found</p>';
        } else {
            //
            history.map((v) => {
                //
                let note = JSON.parse(v.note);
                //
                let act = "",
                    comment = "-";
                if (v.action == "create") {
                    act = "Created time-off.";
                } else {
                    if (note.status !== undefined) {
                        //
                        if (note.status == "archive")
                            act = "Marked time-off as archive.";
                        else if (note.status == "activate")
                            act = "Marked time-off as active.";
                        else if (note.status == "approved" && note.canApprove == 1)
                            act = "Approved time-off 100%.";
                        else if (note.status == "approved" && note.canApprove == 0)
                            act = "Approved time-off 50%.";
                        else if (note.status == "rejected" && note.canApprove == 1)
                            act = "Rejected time-off 100%.";
                        else if (note.status == "rejected" && note.canApprove == 0)
                            act = "Rejected time-off 50%.";
                        else if (note.status == "pending" && note.canApprove == 0)
                            act = "Updated time-off.";
                        else if (note.status == "cancelled")
                            act = "Canceled time-off.";

                        //
                        if (note.comment != undefined && note.comment != "")
                            comment = note.comment;
                    } else {
                        act = "";
                    }
                    if (v.action == "update" && act == "")
                        act = "Time-off updated.";
                }

                //
                rows += '        <div class="employee-info">';
                rows += "            <figure>";
                rows += `                <img src="${getImageURL(
                    v.image
                )}" class="img-circle emp-image" />`;
                rows += "            </figure>";
                rows += '            <div class="text">';
                rows += `                <h4>${v.first_name} ${v.last_name} </h4>`;
                rows += `                <p>${remakeEmployeeName(v, false)}</p>`;
                rows += `                <p><a href="${baseURL}employee_profile/${v.userId
                    }" target="_blank">Id: ${getEmployeeId(
                        v.userId,
                        v.employee_number
                    )}</a></p>`;
                rows += "            </div>";
                rows += "        </div>";

                rows += `<p>${act}</p>`;
                rows += `<p>Action Taken: ${moment(v.created_at).format(timeoffDateFormatWithTime)}</p>`;
                rows += `<p>${comment}</p><hr />`;
            });
        }
        //
        $('.jsHistoryBox').html(rows);
    }

    //
    function getApproverAction(
        approverId,
        history
    ) {
        //
        if (history.length == 0) return '';
        //
        let row = [];
        //
        history.map((v) => {
            if (row.length != 0) return '';
            if (v.userId != approverId) return '';
            if (v.action == 'create') return '';
            if (v.note == '{}') return '';
            //
            let action = JSON.parse(v.note);
            //
            if (action.canApprove == undefined) return '';
            //
            if (action.status == 'approved') {
                row[0] = ` has approved the time-off at ${moment(v.created_at).format(timeoffDateFormatWithTime)}`;
                row[1] = `approved`;
            } else if (action.status == 'rejected') {
                row[0] = ` has rejected the time-off at ${moment(v.created_at).format(timeoffDateFormatWithTime)}`;
                row[1] = `rejected`;
            }
        });
        //
        return row;
    }

    //
    function getSelectedPolicy(
        policyId
    ) {
        if (window.timeoff.cPolicies.length === 0) return [];
        //
        let selectedPolicy = [];
        //
        window.timeoff.cPolicies.map((policy) => {
            if (policy.PolicyId == policyId) selectedPolicy = policy;
        });
        //
        return selectedPolicy;
    }

    //
    function fetchCompanyEmployees() {
        return new Promise((res) => {
            $.post(
                handlerURL, {
                action: 'get_company_employees',
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
    $(document).on('change', '#jsEditPolicy', function () {
        //
        if ($(this).val() === null) {
            policyOffDays = undefined;
            return;
        }
        //
        var singlePolicy = getPolicy(
            $(this).val(),
            window.timeoff.cPolicies
        );
        //
        if (singlePolicy.OffDays == null) {
            policyOffDays = undefined;
        } else {
            policyOffDays = singlePolicy.OffDays.split(',');
        }
    });
})