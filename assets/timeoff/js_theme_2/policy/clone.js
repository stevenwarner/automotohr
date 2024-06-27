$(function () {

    let
        policy = {},
        resetPolicy = {},
        policyId = 0,
        originalOBJ = {},
        //
        policyOBJ = {
            type: 0,
            title: 0,
            order: 1,
            policy_category_type: 1,
            entitledEmployees: [],
            isEntitledEmployees: 0,
            employeeTypes: [],
            offDays: [],
            approver: 0,
            approverList: [],
            deactivate: 0,
            include: 1,
            isESST: 0,
            method: 'yearly',
            time: 'none',
            frequency: 'none',
            frequencyVal: 0,
            rate: 0,
            rateType: 'days',
            applicableTime: 0,
            applicableTimeType: 'hours',
            carryOverCheck: 'no',
            carryOverType: 'days',
            carryOverVal: 0,
            carryOverCycle: 1,
            negativeBalanceCheck: 'no',
            negativeBalanceVal: 0,
            negativeBalanceType: 'days',
            applicableDateType: 'hireDate',
            applicableDate: 0,
            resetDateType: 'policyDateCustom',
            resetDate: 0,
            newHireTime: 0,
            newHireTimeType: 0,
            newHireRate: 0,
            plans: []
        },
        cmnOBJ = {
            Policies: {
                Main: {
                    action: 'get_single_policy_by_id',
                    companyId: companyId,
                    employerId: employerId,
                    employeeId: employeeId,
                    public: 0,
                }
            }
        };

    //
    window.timeoff.startPolicyCloneProcess = startPolicyCloneProcess;
    window.timeoff.stepCompletedEditClone = stepCompletedEditClone;
    window.timeoff.finalStepCompletedEditClone = finalStepCompletedEditClone;
    window.timeoff.stepCompletedResetClone = stepCompletedResetClone;
    window.timeoff.finalStepCompletedResetClone = finalStepCompletedResetClone;

    //
    $('.jsEditResetCheckbox').click(function () {
        // 
        $('.jsEditResetCheckbox').removeClass('btn-orange');
        $('.jsEditResetCheckbox').addClass('btn-default');
        $(this).addClass('btn-orange');

        if ($(this).data('type') === 'rp') {
            // Show current
            $('#jsEditPageClone').fadeOut();
            $('#jsResetPageClone').fadeIn(300);
            $('.jsEditResetText').text('The policy for next year.');
            //
            page = 'reset';
        } else {
            $('#jsResetPageClone').fadeOut();
            $('#jsEditPageClone').fadeIn(300);
            $('.jsEditResetText').text('The policy for current year.')
            page = 'edit';
        }
    });

    $('#js-employee-edit-clone').on('select2:select', function (event) {
        //
        if (event.params.data.text != 'All') {
            //
            let newVals = $(this).val().filter(function (ef) {
                return ef == 'all' ? false : true;
            });
            $('#js-employee-edit-clone').val(newVals);
        } else {
            $('#js-employee-edit-clone').val('all');
        }
        $('#js-employee-edit-clone').trigger('change.select2');
    });

    $('#js-approvers-list-edit-clone').on('select2:select', function (event) {
        //
        if (event.params.data.text != 'All') {
            //
            let newVals = $(this).val().filter(function (ef) {
                return ef == 'all' ? false : true;
            });
            $('#js-approvers-list-edit-clone').val(newVals);
        } else {
            $('#js-approvers-list-edit-clone').val('all');
        }
        $('#js-approvers-list-edit-clone').trigger('change.select2');
    });

    // Change events
    // Policy type change
    $('#js-category-edit-clone').on('change', function () {
        var i = $('#js-category-edit-clone option[value="' + ($(this).val()) + '"]').text().toLowerCase().trim();
        //
        if (i.match(/(fmla)/g) !== null) {
            $('.js-fmla-range-wrap-edit-clone').show();
        } else {
            $('.js-fmla-range-wrap-edit-clone').hide(0);
            $('.js-fmla-range-edit-clone').prop('checked', false);
            $('.js-fmla-range-edit-clone[value="standard_year"]').prop('checked', true);
        }
    });
    // Accrual method change
    $('#js-accrual-method-edit-clone').change(setAccrualText);
    // Accrual frequency
    $('#js-accrual-frequency-edit-clone').on('change', function () {
        if ($(this).val() == 'none') {
            if ($(`#js-accrual-time-edit[value="none"]`).text() != 'Jan To Dec') {
                $(`#js-accrual-time-edit-clone`).html(`
                <option value="none" selected="true">Jan To Dec</option>
                <option value="start_of_period">Jan to Jun</option>
                <option value="end_of_period">Jul to Dec</option>
                `);
                $(`#js-accrual-time-edit`).select2();
            }
        } else {
            if ($(`#js-accrual-time-edit[value="none"]`).text() != '1st To 30th') {
                $(`#js-accrual-time-edit-clone`).html(`
                    <option value="none" selected="true">1st To 30th</option>
                    <option value="start_of_period">1st To 15th</option>
                    <option value="end_of_period">15th To 30th</option>
                `);
                $(`#js-accrual-time-edit-clone`).select2();
            }
        }
        if ($(this).val() == 'custom') {
            $('.jsCustomBoxAdd').show(0);
            $('#js-accrual-time-edit-clone').prop('disabled', true);
            $('#js-accrual-time-text-edit-clone').show();
        } else {
            $('.jsCustomBoxAdd').hide(0);
            $('#js-accrual-time-edit-clone').prop('disabled', false);
            $('#js-accrual-time-text-edit-clone').hide();
        }
        //
        setAccrualText();
    });
    // Carryover change
    $('#js-carryover-cap-check-edit-clone').change(function () {
        $('.js-carryover-box-edit').find('input').val(0);
        if ($(this).val() === 'no') {
            $('.js-carryover-box-edit').hide();
        } else {
            $('.js-carryover-box-edit').show();
        }
    });
    // Negative balance change
    $('#js-accrual-balance-edit-clone').change(function () {
        $('.js-accrual-balance-edit').find('input').val(0);
        if ($(this).val() === 'no') {
            $('.js-negative-box-edit').hide();
        } else {
            $('.js-negative-box-edit').show();
        }
    });
    // Policy applicable change
    $('.js-hire-date-edit').on('change', function () {
        if ($(this).val() == 'hireDate') {
            $('.jsImplementDateBox-edit').hide(0);
            $('#js-custom-date-edit-clone').val('');
        } else {
            $('.jsImplementDateBox-edit').show(0);
        }
    });
    // Policy reset date
    $('.js-policy-reset-date-edit').on('change', function () {
        if ($(this).val() == 'policyDate') {
            $('.jsResetDateBox-edit').hide(0);
            $('#js-custom-reset-date-edit-clone').val('');
        } else {
            $('.jsResetDateBox-edit').show(0);
        }
    });
    //
    $('.js-plan-btn-edit,.js-plan-btn-reset').click(function (e) {
        //
        e.preventDefault();
        //
        loadAccrualPlans(
            $(this).data('type')
        );
    });
    //
    $(document).on('click', '.js-plan-remove-btn', function (e) {
        //
        e.preventDefault();
        //
        if (
            $(this).parent().find('.js-pt').val().trim() != '' ||
            $(this).parent().find('.js-py').val().trim() != '') {
            alertify.confirm('Do you really want to delete this accrual plan?', () => {
                $(this).parent().remove();
            }).set('labels', {
                ok: 'Yes',
                cancel: 'No'
            });
        } else $(this).parent().remove();
    });
    //
    $('[data-hint="js-hint"]').click(function (e) {
        e.preventDefault();
        $(`.js-hint-${$(this).data('target')}`).toggle();
    });
    //
    $(document).on('select2:selecting', '#js-plans-select-edit-clone', function (e) { makePlanRow(e.params.args.data.id, 'edit'); });
    $(document).on('select2:unselecting', '#js-plans-select-edit-clone', function (e) { removePlan(e.params.args.data.id, 'edit'); });
    $(document).on('click', '.js-remove-plan-edit', function (e) {
        removePlan($(this).closest('li').data('id'), 'edit');
        $('#js-plans-select-edit-clone').select2(
            'val',
            newValues($('#js-plans-select-edit-clone').val(), $(this).closest('li').data('id'))
        );
    });
    //
    $('#js-unlimited-policy-check-edit-clone').click(function () {
        if ($(this).prop('checked') === true) $('#js-plan-box-edit-clone').hide();
        else $('#js-plan-box-edit-clone').show();
    });
    //
    $('#js-accrual-time-edit-clone').change(setAccrualText);
    $('#js-accrual-rate-type-edit-clone').change(setAccrualText);
    $('#js-accrual-rate-edit-clone').keyup(function () {
        //
        if ($(this).val().trim() == '') {
            $('#js-accrual-time-edit-clone').prop('disabled', true);
            $('#js-accrual-time-text-edit-clone').show();
        } else if ($(this).val().match(/[a-zA-Z]/) != null) {
            $(this).val(0);
            $('#js-accrual-time-edit-clone').prop('disabled', true);
            $('#js-accrual-time-text-edit-clone').show();
        } else {
            //
            if ($(this).val() < 0) {
                $(this).val(0);
                $('#js-accrual-time-edit-clone').prop('disabled', true);
                $('#js-accrual-time-text-edit-clone').show();

            } else {
                $('#js-accrual-time-edit-clone').prop('disabled', false);
                $('#js-accrual-time-text-edit-clone').hide();
            }
        }
        //
        if ($(this).val() == 0) {
            $('.js-plan-btn-edit')
                .prop('disabled', true);
        } else {
            $('.js-plan-btn-edit')
                .prop('disabled', false);
        }
        //
        setAccrualText();
    });
    $('#js-accrual-frequency-val-edit-clone').keyup(function () {
        //
        if ($(this).val().trim() <= 0) $(this).val(1);
        else if ($(this).val().trim() > 12) $(this).val(12);
        //
        setAccrualText();
    });

    // For Reset
    $('#js-category-reset-clone').on('change', function () {
        var i = $('#js-category-reset-clone option[value="' + ($(this).val()) + '"]').text().toLowerCase().trim();
        //
        if (i.match(/(fmla)/g) !== null) {
            $('.js-fmla-range-wrap-reset').show();
        } else {
            $('.js-fmla-range-wrap-reset').hide(0);
            $('.js-fmla-range-reset').prop('checked', false);
            $('.js-fmla-range-reset[value="standard_year"]').prop('checked', true);
        }
    });
    // Accrual method change
    $('#js-accrual-method-reset-clone').change(setAccrualText);
    // Accrual frequency
    $('#js-accrual-frequency-reset-clone').on('change', function () {
        if ($(this).val() == 'none') {
            if ($(`#js-accrual-time-reset[value="none"]`).text() != 'Jan To Dec') {
                $(`#js-accrual-time-reset-clone`).html(`
                <option value="none" selected="true">Jan To Dec</option>
                <option value="start_of_period">Jan to Jun</option>
                <option value="end_of_period">Jul to Dec</option>
                `);
                $(`#js-accrual-time-reset`).select2();
            }
        } else {
            if ($(`#js-accrual-time-reset[value="none"]`).text() != '1st To 30th') {
                $(`#js-accrual-time-reset-clone`).html(`
                    <option value="none" selected="true">1st To 30th</option>
                    <option value="start_of_period">1st To 15th</option>
                    <option value="end_of_period">15th To 30th</option>
                `);
                $(`#js-accrual-time-reset`).select2();
            }
        }
        if ($(this).val() == 'custom') {
            $('.jsCustomBoxAdd').show(0);
            $('#js-accrual-time-reset-clone').prop('disabled', true);
            $('#js-accrual-time-text-reset-clone').show();
        } else {
            $('.jsCustomBoxAdd').hide(0);
            $('#js-accrual-time-reset-clone').prop('disabled', false);
            $('#js-accrual-time-text-reset-clone').hide();
        }
        //
        setAccrualText();
    });
    // Carryover change
    $('#js-carryover-cap-check-reset-clone').change(function () {
        $('.js-carryover-box-reset').find('input').val(0);
        if ($(this).val() === 'no') {
            $('.js-carryover-box-reset').hide();
        } else {
            $('.js-carryover-box-reset').show();
        }
    });
    // Negative balance change
    $('#js-accrual-balance-reset-clone').change(function () {
        $('.js-accrual-balance-reset').find('input').val(0);
        if ($(this).val() === 'no') {
            $('.js-negative-box-reset').hide();
        } else {
            $('.js-negative-box-reset').show();
        }
    });
    // Policy applicable change
    $('.js-hire-date-reset').on('change', function () {
        if ($(this).val() == 'hireDate') {
            $('.jsImplementDateBox-reset').hide(0);
            $('#js-custom-date-reset-clone').val('');
        } else {
            $('.jsImplementDateBox-reset').show(0);
        }
    });
    // Policy reset date
    $('.js-policy-reset-date-reset').on('change', function () {
        if ($(this).val() == 'policyDate') {
            $('.jsResetDateBox-reset').hide(0);
            $('#js-custom-reset-date-reset-clone').val('');
        } else {
            $('.jsResetDateBox-reset').show(0);
        }
    });
    //
    $(document).on('click', '.js-plan-remove-btn', function (e) {
        //
        e.preventDefault();
        //
        if (
            $(this).parent().find('.js-pt').val().trim() != '' ||
            $(this).parent().find('.js-py').val().trim() != '') {
            alertify.confirm('Do you really want to delete this accrual plan?', () => {
                $(this).parent().remove();
            }).set('labels', {
                ok: 'Yes',
                cancel: 'No'
            });
        } else $(this).parent().remove();
    });
    //
    $('[data-hint="js-hint"]').click(function (e) {
        e.preventDefault();
        $(`.js-hint-${$(this).data('target')}`).toggle();
    });
    //
    $(document).on('select2:selecting', '#js-plans-select-reset', function (e) { makePlanRow(e.params.args.data.id, 'reset'); });
    $(document).on('select2:unselecting', '#js-plans-select-reset', function (e) { removePlan(e.params.args.data.id, 'reset'); });
    $(document).on('click', '.js-remove-plan-reset', function (e) {
        removePlan($(this).closest('li').data('id'), 'reset');
        $('#js-plans-select-reset-clone').select2(
            'val',
            newValues($('#js-plans-select-reset-clone').val(), $(this).closest('li').data('id'))
        );
    });
    //
    $('#js-unlimited-policy-check-reset-clone').click(function () {
        if ($(this).prop('checked') === true) $('#js-plan-box-reset-clone').hide();
        else $('#js-plan-box-reset-clone').show();
    });
    //
    $('#js-accrual-time-reset-clone').change(setAccrualText);
    $('#js-accrual-rate-type-reset-clone').change(setAccrualText);
    $('#js-accrual-rate-reset-clone').keyup(function () {
        //
        if ($(this).val() <= 0) {
            $(this).val(0);
            $('#js-accrual-time-reset-clone').prop('disabled', true);
            $('#js-accrual-time-text-reset-clone').show();
        } else {
            $('#js-accrual-time-reset-clone').prop('disabled', false);
            $('#js-accrual-time-text-reset-clone').hide();
        }
        //
        if ($(this).val() == 0) {
            $('.js-plan-btn-reset')
                .prop('disabled', true);
        } else {
            $('.js-plan-btn-reset')
                .prop('disabled', false);
        }
        //
        setAccrualText();
    });
    $('#js-accrual-frequency-val-reset-clone').keyup(function () {
        //
        if ($(this).val().trim() <= 0) $(this).val(1);
        else if ($(this).val().trim() > 12) $(this).val(12);
        //
        setAccrualText();
    });



    //
    $('#js-policy-title-edit-clone').keyup(function () {
        $('#jsPolicyTitleEdit-clone').text(' - ' + $(this).val());
    });

    // New hire edit change
    $('#js-accrue-new-hire-edit-clone').keyup(setNewHireAccrual);
    $('#js-newhire-prorate-edit-clone').keyup(setNewHireAccrual);
    $('#js-accrual-new-hire-time-type-edit-clone').change(setNewHireAccrual);
    // New hire reset change
    $('#js-accrue-new-hire-reset-clone').keyup(setNewHireAccrual);
    $('#js-newhire-prorate-reset-clone').keyup(setNewHireAccrual);
    $('#js-accrual-new-hire-time-type-reset-clone').change(setNewHireAccrual);

    //
    $('.jsStepSaveclone').click((e) => {
        //
        e.preventDefault();
        //
        if (!stepCompletedEditClone(1)) return;
        if (!stepCompletedEditClone(2)) return;
        if (!stepCompletedEditClone(3)) return;
        if (!stepCompletedEditClone(4)) return;
        if (!stepCompletedEditClone(5)) return;
        if (!stepCompletedEditClone(6)) return;
        if (!stepCompletedEditClone(7)) return;
        //
        redirectPage = false;
        //
        finalStepCompletedEditClone(JSON.parse(localStorage.getItem(`editPolicy`)));
    });

    //
    $('.jsStepSaveReset').click((e) => {
        //
        e.preventDefault();
        //
        if (!stepCompletedResetClone(1)) return;
        if (!stepCompletedResetClone(2)) return;
        if (!stepCompletedResetClone(3)) return;
        if (!stepCompletedResetClone(4)) return;
        if (!stepCompletedResetClone(5)) return;
        if (!stepCompletedResetClone(6)) return;
        if (!stepCompletedResetClone(7)) return;
        //
        redirectPage = false;
        //
        finalStepCompletedResetClone(JSON.parse(localStorage.getItem(`resetPolicy`)));
    });

    //
    async function startPolicyCloneProcess() {
        // Get the single policy
        const resp = await getSinglePolicy(policyId);
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
        //
        let accruals = JSON.parse(resp.Data.accruals);

        //
        policy.type = resp.Data.type_sid;
        policy.title = resp.Data.title;
        policy.order = resp.Data.sort_order;
        policy.entitledEmployees = resp.Data.assigned_employees !== null ? resp.Data.assigned_employees.split(',') : null;
        policy.approver = parseInt(resp.Data.for_admin);
        policy.deactivate = resp.Data.is_archived;
        policy.include = resp.Data.is_included;
        policy.isESST = resp.Data.is_esst;
        policy.method = accruals.method;
        policy.time = accruals.time;
        policy.frequency = accruals.frequency;
        policy.frequencyVal = accruals.frequencyVal;
        policy.rate = accruals.rate;
        policy.rateType = accruals.rateType;
        policy.applicableTime = accruals.applicableTime;
        policy.applicableTimeType = accruals.applicableTimeType;
        policy.carryOverCheck = accruals.carryOverCheck;
        policy.carryOverType = accruals.carryOverType;
        policy.carryOverVal = parseInt(accruals.carryOverVal);
        policy.carryOverCycle = parseInt(accruals.carryOverCycle);
        policy.negativeBalanceCheck = accruals.negativeBalanceCheck;
        policy.negativeBalanceVal = accruals.negativeBalanceVal;
        policy.negativeBalanceType = accruals.negativeBalanceType;
        policy.applicableDateType = accruals.applicableDateType;
        policy.applicableDate = accruals.applicableDate;
        policy.resetDateType = accruals.resetDateType;
        policy.resetDate = accruals.resetDate == 0 ? 0 : accruals.resetDate;
        policy.newHireTime = accruals.newHireTime;
        policy.newHireTimeType = accruals.newHireTimeType;
        policy.newHireRate = accruals.newHireRate;
        policy.employeeTypes = accruals.employeeTypes;
        policy.offDays = resp.Data.off_days !== null ? resp.Data.off_days.split(',') : null;
        policy.plans = accruals.plans;
        policy.policy_category_type = resp.Data.policy_category_type;
        policy.approverList = resp.Data.allowed_approvers;
        //
        policy.accuralDefaultFlow = accruals.defaultFlow;
        //
        if (policy.carryOverCheck != 'no' && policy.carryOverCheck != 'yes') {
            policy.carryOverCheck = 'no';
        }
        //
        if (policy.negativeBalanceCheck != 'no' && policy.negativeBalanceCheck != 'yes') {
            policy.negativeBalanceCheck = 'no';
        }
        //
        originalOBJ = Object.assign({}, policy);
        //
        $('#js-policy-type-edit-clone').select2('val', policy.policy_category_type);
        //
        $('#jsPolicyTitleEdit-clone').text(' - ' + policy.title);
        // Set policy types
        $('#js-category-edit-clone').select2('val', policy.type);
        // Set policy title
        $('#js-policy-title-edit-clone').val(policy.title);
        // Set sort order
        $('#js-sort-order-edit-clone').val(policy.order);
        // Set employees
        $('#js-employee-edit-clone').select2('val', policy.entitledEmployees);
        //
        $('#js-employee-type-edit-clone').select2('val', policy.employeeTypes);
        //
        $('#js-off-days-edit-clone').select2('val', policy.offDays);
        // Set approver check
        $('#js-approver-check-edit-clone').prop('checked', policy.approver == 1 ? true : false);

        if (policy.approver == 1) {
            $('#js-approvers-list-edit-clone').select2('val', policy.approverList ? policy.approverList.split(',') : []);
        }
        // Set archive check
        $('#js-archive-check-edit-clone').prop('checked', policy.deactivate == 1 ? true : false);
        // Set include check
        $('#js-include-check-edit-clone').prop('checked', policy.include == 1 ? true : false);
        //
        $('#js-is-esst-edit-clone').prop('checked', policy.isESST == 1 ? true : false);
        // Set accrual method
        // $('#js-accrual-method-edit').select2('val', policy.method);
        // $('#js-accrual-method-edit').trigger('change');
        // Set accrual time
        $('#js-accrual-time-edit-clone').select2('val', policy.time);
        $('#js-accrual-time-edit-clone').trigger('change');
        // Set accrual frquency
        $('#js-accrual-frequency-edit-clone').select2('val', policy.frequency);
        $('#js-accrual-frequency-edit-clone').trigger('change');
        $('#js-accrual-frequency-val-edit-clone').val(policy.frequencyVal);
        // Set accrual rate
        $('#js-accrual-rate-edit-clone').val(policy.rate);
        $(`#js-accrual-rate-type-edit-clone option[value="${policy.rateType}"]`).prop('selected', true);
        // Set accrual minimum worked time
        $('#js-minimum-applicable-hours-edit-clone').val(policy.applicableTime);
        $(`.js-minimum-applicable-time-edit[value="${policy.applicableTimeType}"]`).prop('checked', true).trigger('click');
        // Set carryover
        $('#js-carryover-cap-check-edit-clone').select2('val', policy.carryOverCheck);
        $('#js-carryover-cap-check-edit-clone').trigger('change');
        $('#js-carryover-cap-edit-clone').val(policy.carryOverVal);
        $('#js-carryover-cycle-edit-clone').val(policy.carryOverCycle);
        // Set negative balance
        $('#js-accrual-balance-edit-clone').select2('val', policy.negativeBalanceCheck);
        $('#js-accrual-balance-edit-clone').trigger('change');
        $('#js-maximum-balance-edit-clone').val(policy.negativeBalanceVal);
        // Set policy applicable date
        $(`.js-hire-date-edit[value="${policy.applicableDateType}"]`).prop('checked', true);
        $(`.js-hire-date-edit[value="${policy.applicableDateType}"]`).trigger('change');
        // Set policy reset date
        $(`.js-policy-reset-date-edit[value="${policy.resetDateType}"]`).prop('checked', true).trigger('change');
        // New hire
        $('#js-accrue-new-hire-edit-clone').val(policy.newHireTime);
        $('#js-newhire-prorate-edit-clone').val(policy.newHireRate);
        $('#js-accrual-new-hire-time-type-edit-clone').val(policy.newHireTimeType);
        // Plans
        // Policy applicable date
        $('#js-custom-date-edit-clone').val(policy.applicableDate);
        // Policy reset date
        $('#js-custom-reset-date-edit-clone').val(policy.resetDate);
        //
        $('#js-step-bar-clone').show();
        //
        $("#js-accrual-default-flow-edit-clone").prop(
            "checked",
            policy.accuralDefaultFlow == 1 || policy.accuralDefaultFlow == undefined ? true
                : false
        );
        //
        if (resp.Data.is_entitled_employee == 1) {
            $('#EntitledEmployees').prop('checked', true);
            $('#NonEntitledEmployees').prop('checked', false);
        } else {
            $('#NonEntitledEmployees').prop('checked', true);
            $('#EntitledEmployees').prop('checked', false);
        }


        //
        loadAccrualPlans('edit', policy.plans);
        //
        let resetAccrual = JSON.parse(resp.Data.reset_policy);

        resetPolicy.type = resp.Data.type_sid;
        resetPolicy.title = resp.Data.title;
        resetPolicy.order = resp.Data.sort_order;
        resetPolicy.entitledEmployees = resp.Data.assigned_employees !== null ? resp.Data.assigned_employees.split(',') : null;
        resetPolicy.approver = parseInt(resp.Data.for_admin);
        resetPolicy.deactivate = resp.Data.is_archived;
        resetPolicy.include = resp.Data.is_included;
        resetPolicy.method = resetAccrual.method;
        resetPolicy.time = resetAccrual.time;
        resetPolicy.frequency = resetAccrual.frequency;
        resetPolicy.frequencyVal = parseInt(resetAccrual.frequencyVal);
        resetPolicy.rate = resetAccrual.rate;
        resetPolicy.rateType = resetAccrual.rateType;
        resetPolicy.applicableTime = resetAccrual.applicableTime;
        resetPolicy.applicableTimeType = resetAccrual.applicableTimeType;
        resetPolicy.carryOverCheck = resetAccrual.carryOverCheck;
        resetPolicy.carryOverType = resetAccrual.carryOverType;
        resetPolicy.carryOverVal = resetAccrual.carryOverVal;
        resetPolicy.carryOverCycle = resetAccrual.carryOverCycle;
        resetPolicy.negativeBalanceCheck = resetAccrual.negativeBalanceCheck;
        resetPolicy.negativeBalanceVal = resetAccrual.negativeBalanceVal;
        resetPolicy.negativeBalanceType = resetAccrual.negativeBalanceType;
        resetPolicy.applicableDateType = resetAccrual.applicableDateType;
        resetPolicy.applicableDate = resetAccrual.applicableDate;
        resetPolicy.resetDateType = resetAccrual.resetDateType;
        resetPolicy.resetDate = resetAccrual.resetDate == 0 ? 0 : resetAccrual.resetDate;
        resetPolicy.newHireTime = resetAccrual.newHireTime;
        resetPolicy.newHireTimeType = resetAccrual.newHireTimeType;
        resetPolicy.newHireRate = resetAccrual.newHireRate;
        resetPolicy.employeeTypes = resetAccrual.employeeTypes;
        resetPolicy.offDays = resp.Data.off_days !== null ? resp.Data.off_days.split(',') : null;
        resetPolicy.plans = resetAccrual.plans;
        //
        //load reset policy
        $('#js-step-bar-reset-clone').show();
        // Set policy types
        $('#js-category-reset').select2('val', policy.type);
        //
        $('#js-category-reset').select2('val', resetPolicy.employeeTypes);
        // Set policy title
        $('#js-policy-title-reset').val(policy.title);
        // Set sort order
        $('#js-sort-order-reset').val(policy.order);
        // Set employees
        $('#js-employee-reset').select2('val', policy.entitledEmployees);
        //
        $('#js-off-days-edit-clone').select2('val', policy.offDays);
        //
        $('#js-employee-type-reset').select2('val', policy.employeeTypes);
        // Set approver check
        $('#js-approver-check-reset').prop('checked', policy.approver == 1 ? true : false);
        // Set archive check
        $('#js-archive-check-reset').prop('checked', policy.deactivate == 1 ? true : false);
        // Set include check
        $('#js-include-check-reset').prop('checked', policy.include == 1 ? true : false);
        // Set accrual method
        // $('#js-accrual-method-reset').select2('val', resetAccrual.method);
        // $('#js-accrual-method-reset').trigger('change');
        // Set accrual time
        $('#js-accrual-time-reset').select2('val', resetAccrual.time);
        $('#js-accrual-time-reset').trigger('change');
        // Set accrual frquency
        $('#js-accrual-frequency-reset').select2('val', resetAccrual.frequency);
        $('#js-accrual-frequency-reset').trigger('change');
        $('#js-accrual-frequency-val-reset').val(resetAccrual.frequencyVal);
        // Set accrual rate
        $('#js-accrual-rate-reset').val(resetAccrual.rate);
        $(`#js-accrual-rate-type-reset option[value="${resetAccrual.rateType}"]`).prop('selected', true);
        // Set accrual minimum worked time
        $('#js-minimum-applicable-hours-reset').val(resetAccrual.applicableTime);
        $(`.js-minimum-applicable-time-reset[value="${resetAccrual.applicableTimeType}"]`).prop('checked', true).trigger('click');
        // Set carryover
        $('#js-carryover-cap-check-reset').select2('val', resetAccrual.carryOverCheck);
        $('#js-carryover-cap-check-reset').trigger('change');
        $('#js-carryover-cap-reset').val(resetAccrual.carryOverVal);
        $('#js-carryover-cycle-reset').val(resetAccrual.carryOverCycle);
        // Set negative balance
        $('#js-accrual-balance-reset').select2('val', resetAccrual.negativeBalanceCheck);
        $('#js-accrual-balance-reset').trigger('change');
        $('#js-maximum-balance-reset').val(resetAccrual.negativeBalanceVal);
        // Set policy applicable date
        $(`.js-hire-date-reset[value="${resetAccrual.applicableDateType}"]`).prop('checked', true);
        $(`.js-hire-date-reset[value="${resetAccrual.applicableDateType}"]`).trigger('change');
        // Set policy reset date
        $(`.js-policy-reset-date-reset[value="${resetAccrual.resetDateType}"]`).prop('checked', true).trigger('change');
        // New hire
        $('#js-accrue-new-hire-reset').val(resetAccrual.newHireTime);
        $('#js-newhire-prorate-reset').val(resetAccrual.newHireRate);
        $('#js-accrual-new-hire-time-type-reset').val(resetAccrual.newHireTimeType);
        // Plans
        // Policy applicable date
        $('#js-custom-date-reset').val(resetAccrual.applicableDate);
        // Policy reset date
        $('#js-custom-reset-date-reset').val(resetAccrual.resetDate);
        //
        $('#js-step-bar-reset').show();
        //
        loadAccrualPlans('reset', resetAccrual.plans);
        //
        setNewHireAccrual('edit');
        setNewHireAccrual('reset');
        //
        ml(false, 'policy');
    }

    //

    function getSinglePolicy(policyId) {

        return new Promise((res, rej) => {
            $.post(handlerURL, Object.assign(cmnOBJ.Policies.Main, { policyId: policyId }), (resp) => {
                res(resp);
            });
        });
    }

    // Handles back and forth clicks
    function stepCompletedEditClone(step) {

        // 
        if (step === 1) {
            //
            policyOBJ.policy_category_type = getField('#js-policy-type-edit-clone')
            // Set policy type
            policyOBJ.type = getField('#js-category-edit-clone');

            // Check policy type
            if (policyOBJ.type == 0) {
                alertify.alert('WARNING!', 'Please, select the policy type.', () => { });
                return false;
            }
            // Set policy title
            policyOBJ.title = getField('#js-policy-title-edit-clone');
            // Check policy title
            if (policyOBJ.title == 0) {
                alertify.alert('WARNING!', 'Please, add the policy title.', () => { });
                return false;
            }
            // Set sort order
            policyOBJ.order = getField('#js-sort-order-edit-clone');
            // Set entitled employees
            policyOBJ.entitledEmployees = getField('#js-employee-clone');
            // Set type
            policyOBJ.isEntitledEmployees = $('.jsIsEntitledEmployee:checked').val();
            //
            policyOBJ.employeeTypes = getField('#js-employee-type-edit');
            //
            policyOBJ.offDays = getField('#js-off-days-edit-clone');
            // Set approver check
            policyOBJ.approver = $('#js-approver-check-edit-clone').prop('checked') === true ? 1 : 0;
            policyOBJ.approverList = [];
            if (policyOBJ.approver == 1) {
                policyOBJ.approverList = getField('#js-approvers-list-edit-clone') || [];
            }
            // // Set deactivate check
            policyOBJ.deactivate = $('#js-archive-check-edit-clone').prop('checked') === true ? 1 : 0;
            //
            policyOBJ.isESST = $('#js-is-esst-edit-clone').prop('checked') === true ? 1 : 0;
            // Set deactivate check
            policyOBJ.include = $('#js-include-check-edit-clone').prop('checked') == true ? 1 : 0;
            //

            saveStep(policyOBJ);
            //
            return true;
        }

        //
        if (step === 2) {
            // Set policy method
            policyOBJ.method = getField('#js-accrual-method-edit-clone');
            // Set policy time
            policyOBJ.time = getField('#js-accrual-time-edit-clone');
            // Set policy frequency
            policyOBJ.frequency = getField('#js-accrual-frequency-edit-clone');
            // Set policy frequency type
            policyOBJ.frequencyVal = getField('#js-accrual-frequency-val-edit-clone');
            // Set policy rate
            policyOBJ.rate = getField('#js-accrual-rate-edit-clone');
            // Set policy rate type
            policyOBJ.rateType = getField('#js-accrual-rate-type-edit-clone option:selected');

            // Set policy minimum aplicable type
            policyOBJ.applicableTimeType = getField('.js-minimum-applicable-time-edit:checked');
            // Set policy minimum aplicable time
            policyOBJ.applicableTime = getField('#js-minimum-applicable-hours-edit-clone');
            //
            policyOBJ.plans = getAccrualPlans('edit');
            //
            // // Set default accural flow check
            policyOBJ.accuralDefaultFlow = $('#js-accrual-default-flow-edit-clone').prop('checked') === true ? 1 : 0;
            //
            if (policyOBJ.plans === true) {
                alertify.alert('WARNING!', 'Please, add the proper plans.', () => { });
                return false;
            }
            //
            saveStep(policyOBJ);
            //
            return true;
        }

        //
        if (step === 3) {
            // Set policy carryover
            policyOBJ.carryOverCheck = getField('#js-carryover-cap-check-edit-clone');
            // Set policy carryover
            policyOBJ.carryOverVal = getField('#js-carryover-cap-edit-clone');
            // Set policy carryover
            policyOBJ.carryOverType = getField('#js-accrual-carryover-type-edit-clone option:selected');
            // Set policy carryover
            policyOBJ.carryOverCycle = getField('#js-carryover-cycle-edit-clone');
            //
            saveStep(policyOBJ);
            //
            return true;
        }

        //
        if (step === 4) {
            // Set policy negative balance
            policyOBJ.negativeBalanceCheck = getField('#js-accrual-balance-edit-clone');
            // Set policy negative balance
            policyOBJ.negativeBalanceVal = getField('#js-maximum-balance-edit-clone');
            // Set policy negative balance
            policyOBJ.negativeBalanceType = getField('#js-accrual-negative-balance-type-edit-clone option:selected');
            //
            saveStep(policyOBJ);
            //
            return true;
        }

        //
        if (step === 5) {
            // Set applicable type
            policyOBJ.applicableDateType = getField('.js-hire-date-edit:checked');
            // Set applicable date
            policyOBJ.applicableDate = getField('#js-custom-date-edit-clone');
            //
            if (policyOBJ.applicableDateType.toLowerCase() != 'hiredate' && policyOBJ.applicableDate == 0) {
                alertify.alert('WARNING!', 'Please, select a policy applicable date.', () => { });
                return false;
            }
            saveStep(policyOBJ);
            //
            return true;
        }

        //
        if (step === 6) {
            // Set reset type
            policyOBJ.resetDateType = getField('.js-policy-reset-date-edit:checked');
            // Set reset date
            policyOBJ.resetDate = getField('#js-custom-reset-date-edit-clone');
            //
            if (policyOBJ.resetDateType.toLowerCase() != 'policydate' && policyOBJ.resetDate == 0) {
                alertify.alert('WARNING!', 'Please, select a policy reset date.', () => { });
                return false;
            }
            saveStep(policyOBJ);
            //
            return true;
        }

        //
        if (step === 7) {
            // Set new hire time
            policyOBJ.newHireTime = getField('#js-accrue-new-hire-edit-clone');
            // Set new hire time type
            policyOBJ.newHireTimeType = getField('#js-accrual-new-hire-time-type-edit-clone option:selected');
            // Set new hire rate
            policyOBJ.newHireRate = getField('#js-newhire-prorate-edit-clone');
            //
            saveStep(policyOBJ);
            //
            return true;
        }

        //
        if (step === 8) {
            // Set plans 
            policyOBJ.plans = getAccrualPlans('edit');
            //
            saveStep(policyOBJ);
            //
            return true;
        }

        return false;
    }

    //
    function finalStepCompletedEditClone(policy) {
        alertify.confirm(
            'Do you really want to clone this policy?',
            () => {
                // 
                ml(true, 'policy');
                //
                let post = Object.assign({}, policy, {
                    action: 'create_policy_clone',
                    companyId: companyId,
                    employeeId: employeeId,
                    employerId: employerId,
                    public: 0,
                    policyId: policyId
                });
                //

                $.post(handlerURL, post, (resp) => {
                    //
                    ml(false, 'policy');
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
                        loadViewPage();

                    });
                    return;
                });
            },
            () => {
                ml(false, 'policy');
            },
        ).set('labels', {
            ok: 'Yes',
            cancel: 'No'
        }).setHeader('CONFIRM!');

    }

    // Handles back and forth clicks
    function stepCompletedResetClone(step) {
        //
        if (step === 2) {
            // Set policy method
            resetPolicy.method = getField('#js-accrual-method-reset');
            // Set policy time
            resetPolicy.time = getField('#js-accrual-time-reset');
            // Set policy frequency
            resetPolicy.frequency = getField('#js-accrual-frequency-reset');
            // Set policy frequency type
            resetPolicy.frequencyVal = getField('#js-accrual-frequency-val-reset');
            // Set policy rate
            resetPolicy.rate = getField('#js-accrual-rate-reset');
            // Set policy rate type
            resetPolicy.rateType = getField('#js-accrual-rate-type-reset option:selected');
            // Set policy minimum aplicable type
            resetPolicy.applicableTimeType = getField('.js-minimum-applicable-time-reset:checked');
            // Set policy minimum aplicable time
            resetPolicy.applicableTime = getField('#js-minimum-applicable-hours-reset');
            // Set plans 
            resetPolicy.plans = getAccrualPlans('reset');
            //
            if (resetPolicy.plans === true) {
                alertify.alert('WARNING!', 'Please, add the proper plans.', () => { });
                return false;
            }
            //
            saveStep(resetPolicy);
            //
            return true;
        }

        //
        if (step === 3) {
            // Set policy carryover
            resetPolicy.carryOverCheck = getField('#js-carryover-cap-check-reset');
            // Set policy carryover
            resetPolicy.carryOverVal = getField('#js-carryover-cap-reset');
            // Set policy carryover
            resetPolicy.carryOverType = getField('#js-accrual-carryover-type option:selected');
            // Set policy carryover
            policyOBJ.carryOverCycle = getField('#js-carryover-cycle-edit');
            //
            saveStep(resetPolicy);
            //
            return true;
        }

        //
        if (step === 4) {
            // Set policy negative balance
            resetPolicy.negativeBalanceCheck = getField('#js-accrual-balance-reset');
            // Set policy negative balance
            resetPolicy.negativeBalanceVal = getField('#js-maximum-balance-reset');
            // Set policy negative balance
            resetPolicy.negativeBalanceType = getField('#js-accrual-negative-balance-type-reset option:selected');
            //
            saveStep(resetPolicy);
            //
            return true;
        }

        //
        if (step === 5) {
            // Set applicable type
            resetPolicy.applicableDateType = getField('.js-hire-date-reset:checked');
            // Set applicable date
            resetPolicy.applicableDate = getField('#js-custom-date-reset');
            //
            if (resetPolicy.applicableDateType.toLowerCase() != 'hiredate' && resetPolicy.applicableDate == 0) {
                alertify.alert('WARNING!', 'Please, select a policy applicable date.', () => { });
                return false;
            }
            saveStep(resetPolicy);
            //
            return true;
        }

        //
        if (step === 6) {
            // Set reset type
            resetPolicy.resetDateType = getField('.js-policy-reset-date-reset:checked');
            // Set reset date
            resetPolicy.resetDate = getField('#js-custom-reset-date-reset');
            //
            if (resetPolicy.resetDateType.toLowerCase() != 'policydate' && resetPolicy.resetDate == 0) {
                alertify.alert('WARNING!', 'Please, select a policy reset date.', () => { });
                return false;
            }
            saveStep(resetPolicy);
            //
            return true;
        }

        //
        if (step === 7) {
            // Set new hire time
            resetPolicy.newHireTime = getField('#js-accrue-new-hire-reset');
            // Set new hire time type
            resetPolicy.newHireTimeType = getField('#js-accrual-new-hire-time-type-reset option:selected');
            // Set new hire rate
            resetPolicy.newHireRate = getField('#js-newhire-prorate-reset');
            //
            saveStep(resetPolicy);
            //
            return true;
        }


        return true;
    }

    //
    function finalStepCompletedResetClone(policy) {
        //
        ml(true, 'policy');
        //
        let post = Object.assign({}, policy, {
            action: 'update_reset_policy',
            companyId: companyId,
            employeeId: employeeId,
            employerId: employerId,
            public: 0,
            policyId: policyId
        });
        //
        $.post(handlerURL, post, (resp) => {
            ml(false, 'policy');
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
                if (redirectPage) loadViewPage();
            });
            return;
        });
    }


    //


    $(document).on('click', '.js-clone-row-btn', function (e) {
        e.preventDefault();
        //


     //   alertify.confirm('Do you really want to clone this policy?', () => {
            policy = Object.assign(policyOBJ);
            policyId = $(this).closest('.jsBox').data('id');
            originalOBJ = {};
            //
            ml(true, 'policy');
            //
            loadClonePage();

     //   }).set('labels', {
       //     ok: 'Yes',
//cancel: 'No'
     //   });

    });



});