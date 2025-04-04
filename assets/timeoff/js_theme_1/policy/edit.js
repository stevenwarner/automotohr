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
            policy_category_type: 0,
            entitledEmployees: [],
            isEntitledEmployees: 0,
            employeeTypes: [],
            offDays: [],
            approver: 0,
            approverList: [],
            deactivate: 0,
            include: 1,
            isESST: 0,
            isESTA: 0,
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
    window.timeoff.startPolicyEditProcess = startPolicyEditProcess;
    window.timeoff.stepCompletedEdit = stepCompletedEdit;
    window.timeoff.finalStepCompletedEdit = finalStepCompletedEdit;
    window.timeoff.stepCompletedReset = stepCompletedReset;
    window.timeoff.finalStepCompletedReset = finalStepCompletedReset;

    //
    $('.jsEditResetCheckbox').click(function () {
        // 
        // if( $(this).prop('checked') === false){
        //     // Show current
        //     $('#jsEditPage').fadeOut();
        //     $('#jsResetPage').fadeIn(300);
        //     $('.jsEditResetText').text('The policy for next year.');
        //     page = 'reset';
        // } else{
        //     $('#jsResetPage').fadeOut();
        //     $('#jsEditPage').fadeIn(300);
        //     $('.jsEditResetText').text('The policy for current year.')
        //     page = 'edit';
        // }
        $('.jsEditResetCheckbox').removeClass('btn-success');
        $('.jsEditResetCheckbox').addClass('btn-default');
        $(this).addClass('btn-success');

        if ($(this).data('type') === 'rp') {
            // Show current
            $('#jsEditPage').fadeOut();
            $('#jsResetPage').fadeIn(300);
            $('.jsEditResetText').text('The policy for next year.');
            //
            page = 'reset';
        } else {
            $('#jsResetPage').fadeOut();
            $('#jsEditPage').fadeIn(300);
            $('.jsEditResetText').text('The policy for current year.')
            page = 'edit';
        }
    });

    $('#js-employee-edit').on('select2:select', function (event) {
        //
        if (event.params.data.text != 'All') {
            //
            let newVals = $(this).val().filter(function (ef) {
                return ef == 'all' ? false : true;
            });
            $('#js-employee-edit').val(newVals);
        } else {
            $('#js-employee-edit').val('all');
        }
        $('#js-employee-edit').trigger('change.select2');
    });

    $('#js-approvers-list-edit').on('select2:select', function (event) {
        //
        if (event.params.data.text != 'All') {
            //
            let newVals = $(this).val().filter(function (ef) {
                return ef == 'all' ? false : true;
            });
            $('#js-approvers-list-edit').val(newVals);
        } else {
            $('#js-approvers-list-edit').val('all');
        }
        $('#js-approvers-list-edit').trigger('change.select2');
    });

    // Change events
    // Policy type change
    $('#js-category-edit').on('change', function () {
        var i = $('#js-category-edit option[value="' + ($(this).val()) + '"]').text().toLowerCase().trim();
        //
        if (i.match(/(fmla)/g) !== null) {
            $('.js-fmla-range-wrap-edit').show();
        } else {
            $('.js-fmla-range-wrap-edit').hide(0);
            $('.js-fmla-range-edit').prop('checked', false);
            $('.js-fmla-range-edit[value="standard_year"]').prop('checked', true);
        }
    });
    // Accrual method change
    $('#js-accrual-method-edit').change(setAccrualText);
    // Accrual frequency
    $('#js-accrual-frequency-edit').on('change', function () {
        if ($(this).val() == 'none') {
            if ($(`#js-accrual-time-edit[value="none"]`).text() != 'Jan To Dec') {
                $(`#js-accrual-time-edit`).html(`
                <option value="none" selected="true">Jan To Dec</option>
                <option value="start_of_period">Jan to Jun</option>
                <option value="end_of_period">Jul to Dec</option>
                `);
                $(`#js-accrual-time-edit`).select2();
            }
        } else {
            if ($(`#js-accrual-time-edit[value="none"]`).text() != '1st To 30th') {
                $(`#js-accrual-time-edit`).html(`
                    <option value="none" selected="true">1st To 30th</option>
                    <option value="start_of_period">1st To 15th</option>
                    <option value="end_of_period">15th To 30th</option>
                `);
                $(`#js-accrual-time-edit`).select2();
            }
        }
        if ($(this).val() == 'custom') {
            $('.jsCustomBoxAdd').show(0);
            $('#js-accrual-time-edit').prop('disabled', true);
            $('#js-accrual-time-text-edit').show();
        } else {
            $('.jsCustomBoxAdd').hide(0);
            $('#js-accrual-time-edit').prop('disabled', false);
            $('#js-accrual-time-text-edit').hide();
        }
        //
        setAccrualText();
    });
    // Carryover change
    $('#js-carryover-cap-check-edit').change(function () {
        $('.js-carryover-box-edit').find('input').val(0);
        if ($(this).val() === 'no') {
            $('.js-carryover-box-edit').hide();
        } else {
            $('.js-carryover-box-edit').show();
        }
    });
    // Negative balance change
    $('#js-accrual-balance-edit').change(function () {
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
            $('#js-custom-date-edit').val('');
        } else {
            $('.jsImplementDateBox-edit').show(0);
        }
    });
    // Policy reset date
    $('.js-policy-reset-date-edit').on('change', function () {
        if ($(this).val() == 'policyDate') {
            $('.jsResetDateBox-edit').hide(0);
            $('#js-custom-reset-date-edit').val('');
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
    $(document).on('select2:selecting', '#js-plans-select-edit', function (e) { makePlanRow(e.params.args.data.id, 'edit'); });
    $(document).on('select2:unselecting', '#js-plans-select-edit', function (e) { removePlan(e.params.args.data.id, 'edit'); });
    $(document).on('click', '.js-remove-plan-edit', function (e) {
        removePlan($(this).closest('li').data('id'), 'edit');
        $('#js-plans-select-edit').select2(
            'val',
            newValues($('#js-plans-select-edit').val(), $(this).closest('li').data('id'))
        );
    });
    //
    $('#js-unlimited-policy-check-edit').click(function () {
        if ($(this).prop('checked') === true) $('#js-plan-box-edit').hide();
        else $('#js-plan-box-edit').show();
    });
    //
    $('#js-accrual-time-edit').change(setAccrualText);
    $('#js-accrual-rate-type-edit').change(setAccrualText);
    $('#js-accrual-rate-edit').keyup(function () {
        //
        if ($(this).val().trim() == '') {
            $('#js-accrual-time-edit').prop('disabled', true);
            $('#js-accrual-time-text-edit').show();
        } else if ($(this).val().match(/[a-zA-Z]/) != null) {
            $(this).val(0);
            $('#js-accrual-time-edit').prop('disabled', true);
            $('#js-accrual-time-text-edit').show();
        } else {
            //
            if ($(this).val() < 0) {
                $(this).val(0);
                $('#js-accrual-time-edit').prop('disabled', true);
                $('#js-accrual-time-text-edit').show();

            } else {
                $('#js-accrual-time-edit').prop('disabled', false);
                $('#js-accrual-time-text-edit').hide();
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
    $('#js-accrual-frequency-val-edit').keyup(function () {
        //
        if ($(this).val().trim() <= 0) $(this).val(1);
        else if ($(this).val().trim() > 12) $(this).val(12);
        //
        setAccrualText();
    });

    // For Reset
    $('#js-category-reset').on('change', function () {
        var i = $('#js-category-reset option[value="' + ($(this).val()) + '"]').text().toLowerCase().trim();
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
    $('#js-accrual-method-reset').change(setAccrualText);
    // Accrual frequency
    $('#js-accrual-frequency-reset').on('change', function () {
        if ($(this).val() == 'none') {
            if ($(`#js-accrual-time-reset[value="none"]`).text() != 'Jan To Dec') {
                $(`#js-accrual-time-reset`).html(`
                <option value="none" selected="true">Jan To Dec</option>
                <option value="start_of_period">Jan to Jun</option>
                <option value="end_of_period">Jul to Dec</option>
                `);
                $(`#js-accrual-time-reset`).select2();
            }
        } else {
            if ($(`#js-accrual-time-reset[value="none"]`).text() != '1st To 30th') {
                $(`#js-accrual-time-reset`).html(`
                    <option value="none" selected="true">1st To 30th</option>
                    <option value="start_of_period">1st To 15th</option>
                    <option value="end_of_period">15th To 30th</option>
                `);
                $(`#js-accrual-time-reset`).select2();
            }
        }
        if ($(this).val() == 'custom') {
            $('.jsCustomBoxAdd').show(0);
            $('#js-accrual-time-reset').prop('disabled', true);
            $('#js-accrual-time-text-reset').show();
        } else {
            $('.jsCustomBoxAdd').hide(0);
            $('#js-accrual-time-reset').prop('disabled', false);
            $('#js-accrual-time-text-reset').hide();
        }
        //
        setAccrualText();
    });
    // Carryover change
    $('#js-carryover-cap-check-reset').change(function () {
        $('.js-carryover-box-reset').find('input').val(0);
        if ($(this).val() === 'no') {
            $('.js-carryover-box-reset').hide();
        } else {
            $('.js-carryover-box-reset').show();
        }
    });
    // Negative balance change
    $('#js-accrual-balance-reset').change(function () {
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
            $('#js-custom-date-reset').val('');
        } else {
            $('.jsImplementDateBox-reset').show(0);
        }
    });
    // Policy reset date
    $('.js-policy-reset-date-reset').on('change', function () {
        if ($(this).val() == 'policyDate') {
            $('.jsResetDateBox-reset').hide(0);
            $('#js-custom-reset-date-reset').val('');
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
        $('#js-plans-select-reset').select2(
            'val',
            newValues($('#js-plans-select-reset').val(), $(this).closest('li').data('id'))
        );
    });
    //
    $('#js-unlimited-policy-check-reset').click(function () {
        if ($(this).prop('checked') === true) $('#js-plan-box-reset').hide();
        else $('#js-plan-box-reset').show();
    });
    //
    $('#js-accrual-time-reset').change(setAccrualText);
    $('#js-accrual-rate-type-reset').change(setAccrualText);
    $('#js-accrual-rate-reset').keyup(function () {
        //
        if ($(this).val() <= 0) {
            $(this).val(0);
            $('#js-accrual-time-reset').prop('disabled', true);
            $('#js-accrual-time-text-reset').show();
        } else {
            $('#js-accrual-time-reset').prop('disabled', false);
            $('#js-accrual-time-text-reset').hide();
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
    $('#js-accrual-frequency-val-reset').keyup(function () {
        //
        if ($(this).val().trim() <= 0) $(this).val(1);
        else if ($(this).val().trim() > 12) $(this).val(12);
        //
        setAccrualText();
    });

    //
    $(document).on('click', '.js-edit-row-btn', function (e) {
        e.preventDefault();
        //
        policy = Object.assign(policyOBJ);
        policyId = $(this).closest('tr').data('id');
        originalOBJ = {};
        //
        ml(true, 'policy');
        //
        loadEditPage();
    });

    //
    $('#js-policy-title-edit').keyup(function () {
        $('#jsPolicyTitleEdit').text(' - ' + $(this).val());
    });

    // New hire edit change
    $('#js-accrue-new-hire-edit').keyup(setNewHireAccrual);
    $('#js-newhire-prorate-edit').keyup(setNewHireAccrual);
    $('#js-accrual-new-hire-time-type-edit').change(setNewHireAccrual);
    // New hire reset change
    $('#js-accrue-new-hire-reset').keyup(setNewHireAccrual);
    $('#js-newhire-prorate-reset').keyup(setNewHireAccrual);
    $('#js-accrual-new-hire-time-type-reset').change(setNewHireAccrual);

    //
    $('.jsStepSave').click((e) => {
        //
        e.preventDefault();
        //
        if (!stepCompletedEdit(1)) return;
        if (!stepCompletedEdit(2)) return;
        if (!stepCompletedEdit(3)) return;
        if (!stepCompletedEdit(4)) return;
        if (!stepCompletedEdit(5)) return;
        if (!stepCompletedEdit(6)) return;
        if (!stepCompletedEdit(7)) return;
        //
        redirectPage = false;
        //
        finalStepCompletedEdit(JSON.parse(localStorage.getItem(`editPolicy`)));
    });

    //
    $('.jsStepSaveReset').click((e) => {
        //
        e.preventDefault();
        //
        if (!stepCompletedReset(1)) return;
        if (!stepCompletedReset(2)) return;
        if (!stepCompletedReset(3)) return;
        if (!stepCompletedReset(4)) return;
        if (!stepCompletedReset(5)) return;
        if (!stepCompletedReset(6)) return;
        if (!stepCompletedReset(7)) return;
        //
        redirectPage = false;
        //
        finalStepCompletedReset(JSON.parse(localStorage.getItem(`resetPolicy`)));
    });

    //
    async function startPolicyEditProcess() {
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
        policy.isESTA = resp.Data.is_esta;
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
        policy.policyCategory = resp.Data.policy_category_type;
        policy.approverList = resp.Data.allowed_approvers;
        policy.accuralDefaultFlow = accruals.defaultFlow;

        //
        if (policy.approverList) {
            policy.approverList = policy.approverList.split(',');
        }
        //
        originalOBJ = Object.assign({}, policy);
        //
        $('#jsPolicyTitleEdit').text(' - ' + policy.title);
        //
        $('#js-policy-type-edit').select2('val', policy.policyCategory);
        // Set policy types
        $('#js-category-edit').select2('val', policy.type);
        // Set policy title
        $('#js-policy-title-edit').val(policy.title);
        // Set sort order
        $('#js-sort-order-edit').val(policy.order);
        // Set employees
        $('#js-employee-edit').select2('val', policy.entitledEmployees);
        //
        $('#js-employee-type-edit').select2('val', policy.employeeTypes);
        //
        $('#js-off-days-edit').select2({ closeOnSelect: false });
        $('#js-off-days-edit').select2('val', policy.offDays);
        // Set approver check
        $('#js-approver-check-edit').prop('checked', policy.approver == 1 ? true : false);
        //
        $('#js-approvers-list-edit').select2('val', policy.approverList);
        // Set archive check
        $('#js-archive-check-edit').prop('checked', policy.deactivate == 1 ? true : false);
        // Set include check
        $('#js-include-check-edit').prop('checked', policy.include == 1 ? true : false);
        // Set accrual method
        // $('#js-accrual-method-edit').select2('val', policy.method);
        // $('#js-accrual-method-edit').trigger('change');
        // Set accrual time
        $('#js-accrual-time-edit').select2('val', policy.time);
        $('#js-accrual-time-edit').trigger('change');
        // Set accrual frquency
        $('#js-accrual-frequency-edit').select2('val', policy.frequency);
        $('#js-accrual-frequency-edit').trigger('change');
        $('#js-accrual-frequency-val-edit').val(policy.frequencyVal);
        // Set accrual rate
        $('#js-accrual-rate-edit').val(policy.rate);
        $(`#js-accrual-rate-type-edit option[value="${policy.rateType}"]`).prop('selected', true);
        // Set accrual minimum worked time
        $('#js-minimum-applicable-hours-edit').val(policy.applicableTime);
        $(`.js-minimum-applicable-time-edit[value="${policy.applicableTimeType}"]`).prop('checked', true).trigger('click');
        // Set carryover
        $('#js-carryover-cap-check-edit').select2('val', policy.carryOverCheck);
        $('#js-carryover-cap-check-edit').trigger('change');
        $('#js-carryover-cap-edit').val(policy.carryOverVal);
        $('#js-carryover-cycle-edit').val(policy.carryOverCycle);
        // Set negative balance
        $('#js-accrual-balance-edit').select2('val', policy.negativeBalanceCheck);
        $('#js-accrual-balance-edit').trigger('change');
        $('#js-maximum-balance-edit').val(policy.negativeBalanceVal);
        // Set policy applicable date
        $(`.js-hire-date-edit[value="${policy.applicableDateType}"]`).prop('checked', true);
        $(`.js-hire-date-edit[value="${policy.applicableDateType}"]`).trigger('change');
        // Set policy reset date
        $(`.js-policy-reset-date-edit[value="${policy.resetDateType}"]`).prop('checked', true).trigger('change');
        // New hire
        $('#js-accrue-new-hire-edit').val(policy.newHireTime);
        $('#js-newhire-prorate-edit').val(policy.newHireRate);
        $('#js-accrual-new-hire-time-type-edit').val(policy.newHireTimeType);
        // Plans
        // Policy applicable date
        $('#js-custom-date-edit').val(policy.applicableDate);
        // Policy reset date
        $('#js-custom-reset-date-edit').val(policy.resetDate);
        //
        $('#js-step-bar-edit').show();
        //
        $('#js-accrual-default-flow-edit').prop('checked', policy.accuralDefaultFlow == 1 ? true : false);
        //
        if (resp.Data.is_entitled_employee == 1) {
            $('#EntitledEmployees').prop('checked', true);
            $('#NonEntitledEmployees').prop('checked', false);
        } else {
            $('#NonEntitledEmployees').prop('checked', true);
            $('#EntitledEmployees').prop('checked', false);
        }


        //
        $('#js-is-esst-edit').prop('checked', policy.isESST == 1 ? true : false);
        $('#js-is-esta-edit').prop('checked', policy.isESTA == 1 ? true : false);


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
        $('#js-step-bar-reset').show();
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
        $('#js-employee-type-reset').select2('val', policy.employeeTypes);
        //
        $('#js-off-days-edit').select2('val', policy.offDays);
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
        let accrualsCustomJson = JSON.parse(resp.Data.accruals_custom_json);

        if (accrualsCustomJson && accrualsCustomJson.esta) {

            $('#js-esta-policy-allowed-time-edit').val(accrualsCustomJson.esta.allowed_time);
            $('#js-esta-policy-applicable-time-edit').val(accrualsCustomJson.esta.applicable_time);
            $('#js-esta-policy-applicable-time-type-edit').val(accrualsCustomJson.esta.applicable_time_type);

            $('#js-esta-policy-accrual-allowed-time-edit').val(accrualsCustomJson.esta.applicable_accrual_time);
            $('#js-esta-policy-accrual-time-effectiv-edit').val(accrualsCustomJson.esta.applicable_accrual_time_effectiv);
            $('#js-esta-policy-accrual-time-type-edit').val(accrualsCustomJson.esta.applicable_accrual_time_type
            );



        } else {
            $('#js-esta-policy-allowed-time-edit').val();
            $('#js-esta-policy-applicable-time-edit').val();
            $('#js-esta-policy-applicable-time-type-edit').val();
            $('#js-esta-policy-accrual-allowed-time-edit').val();
            $('#js-esta-policy-accrual-time-effectiv-edit').val();
            $('#js-esta-policy-accrual-time-type-edit').val();
        }

        //
        if (accrualsCustomJson && accrualsCustomJson.esst) {
            $('#js-est-policy-allowed-time-edit').val(accrualsCustomJson.esst.allowed_time);
            $('#js-est-policy-applicable-time-edit').val(accrualsCustomJson.esst.applicable_time);
            $('#js-est-policy-applicable-time-type-edit').val(accrualsCustomJson.esst.applicable_time_type);

            $('#js-est-policy-accrual-allowed-time-edit').val(accrualsCustomJson.esst.applicable_accrual_time);
            $('#js-est-policy-accrual-time-effectiv-edit').val(accrualsCustomJson.esst.applicable_accrual_time_effectiv);
            $('#js-est-policy-accrual-time-type-edit').val(accrualsCustomJson.esst.applicable_accrual_time_type);

        } else {
            //
            $('#js-est-policy-allowed-time-edit').val();
            $('#js-est-policy-applicable-time-edit').val();
            $('#js-est-policy-applicable-time-type-edit').val();
            $('#js-est-policy-accrual-allowed-time-edit').val();
            $('#js-est-policy-accrual-time-effectiv-edit').val();
            $('#js-esta-policy-accrual-time-type-edit').val()
        }


        if (policy.isESST == 1) {
            $("#js-est-policy-box").show();
        } else {
            $("#js-est-policy-box").hide();
        }

        if (policy.isESTA == 1) {
            $("#js-esta-policy-box").show();
        } else {
            $("#js-esta-policy-box").hide();
        }

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
    function stepCompletedEdit(step) {
        //
        if (step === 1) {
            policyOBJ.policy_category_type = getField('#js-policy-type-edit');
            // Set policy type
            policyOBJ.type = getField('#js-category-edit');
            // Check policy type
            if (policyOBJ.type == 0) {
                alertify.alert('WARNING!', 'Please, select the policy type.', () => { });
                return false;
            }
            // Set policy title
            policyOBJ.title = getField('#js-policy-title-edit');
            // Check policy title
            if (policyOBJ.title == 0) {
                alertify.alert('WARNING!', 'Please, add the policy title.', () => { });
                return false;
            }
            // Set sort order
            policyOBJ.order = getField('#js-sort-order-edit');
            // Set entitled employees
            policyOBJ.entitledEmployees = getField('#js-employee-edit');

            //
            policyOBJ.isEntitledEmployees = $('.jsIsEntitledEmployee:checked').val();
            //
            policyOBJ.employeeTypes = getField('#js-employee-type-edit');
            //
            policyOBJ.offDays = getField('#js-off-days-edit');
            // Set approver check
            policyOBJ.approver = $('#js-approver-check-edit').prop('checked') === true ? 1 : 0;
            //
            policyOBJ.approverList = [];
            //
            if (policyOBJ.approver == 1) {
                policyOBJ.approverList = getField('#js-approvers-list-edit') || [];
            }
            // // Set deactivate check
            policyOBJ.deactivate = $('#js-archive-check-edit').prop('checked') === true ? 1 : 0;
            // Set deactivate check
            policyOBJ.include = $('#js-include-check-edit').prop('checked') == true ? 1 : 0;
            //

            policyOBJ.isESST = $('#js-is-esst-edit').prop('checked') === true ? 1 : 0;
            policyOBJ.isESTA = $('#js-is-esta-edit').prop('checked') === true ? 1 : 0;



            //run validations
            if (policyOBJ.isESST == 1) {
                policyOBJ.ESST_policy_Allowed_Time = getField('#js-est-policy-allowed-time-edit');
                policyOBJ.ESST_policy_Applicable_Time = getField('#js-est-policy-applicable-time-edit');
                policyOBJ.ESST_policy_Applicable_Time_Type = getField('#js-est-policy-applicable-time-type-edit');

                policyOBJ.ESST_policy_Applicable_Accrual_Time = getField('#js-est-policy-accrual-allowed-time-edit');
                policyOBJ.ESST_policy_Applicable_Accrual_Time_Effectiv = getField('#js-est-policy-accrual-time-effectiv-edit');
                policyOBJ.ESST_policy_Applicable_Accrual_Time_Type = getField('#js-est-policy-accrual-time-type-edit');

                if (isValidInteger(policyOBJ.ESST_policy_Allowed_Time == false)) {
                    alertify.alert('WARNING!', 'ESST Allowed Time: Please Enter Valid Number', () => { });
                    return false;
                }

                if (isValidInteger(policyOBJ.ESST_policy_Applicable_Time == false)) {
                    alertify.alert('WARNING!', 'ESST Applicable Time: Please Enter Valid Number', () => { });
                    return false;
                }

                if (isValidInteger(policyOBJ.ESST_policy_Applicable_Accrual_Time == false)) {
                    alertify.alert('WARNING!', 'ESST Allow: Please Enter Valid Number', () => { });
                    return false;
                }

                if (isValidInteger(policyOBJ.ESST_policy_Applicable_Accrual_Time_Type == false)) {
                    alertify.alert('WARNING!', 'ESST extra hours(s) after: Please Enter Valid Number', () => { });
                    return false;
                }

            }

            //run validations
            if (policyOBJ.isESTA == 1) {
                policyOBJ.ESTA_policy_Allowed_Time = getField('#js-esta-policy-allowed-time-edit');
                policyOBJ.ESTA_policy_Applicable_Time = getField('#js-esta-policy-applicable-time-edit');
                policyOBJ.ESTA_policy_Applicable_Time_Type = getField('#js-esta-policy-applicable-time-type-edit');

                policyOBJ.ESTA_policy_Applicable_Accrual_Time = getField('#js-esta-policy-accrual-allowed-time-edit');
                policyOBJ.ESTA_policy_Applicable_Accrual_Time_Effectiv = getField('#js-esta-policy-accrual-time-effectiv-edit');
                policyOBJ.ESTA_policy_Applicable_Accrual_Time_Type = getField('#js-esta-policy-accrual-time-type-edit');

                if (isValidInteger(policyOBJ.ESTA_policy_Allowed_Time == false)) {
                    alertify.alert('WARNING!', 'Allowed Time: Please Enter Valid Number', () => { });
                    return false;
                }

                if (isValidInteger(policyOBJ.ESTA_policy_Applicable_Time == false)) {
                    alertify.alert('WARNING!', 'Applicable Time: Please Enter Valid Number', () => { });
                    return false;
                }

                if (isValidInteger(policyOBJ.ESTA_policy_Applicable_Accrual_Time == false)) {
                    alertify.alert('WARNING!', 'Allow: Please Enter Valid Number', () => { });
                    return false;
                }

                if (isValidInteger(policyOBJ.ESTA_policy_Applicable_Accrual_Time_Type == false)) {
                    alertify.alert('WARNING!', 'extra hours(s) after: Please Enter Valid Number', () => { });
                    return false;
                }

            }

            saveStep(policyOBJ);
            //
            return true;
        }

        //
        if (step === 2) {
            // Set policy method
            policyOBJ.method = getField('#js-accrual-method-edit');
            // Set policy time
            policyOBJ.time = getField('#js-accrual-time-edit');
            // Set policy frequency
            policyOBJ.frequency = getField('#js-accrual-frequency-edit');
            // Set policy frequency type
            policyOBJ.frequencyVal = getField('#js-accrual-frequency-val-edit');
            // Set policy rate
            policyOBJ.rate = getField('#js-accrual-rate-edit');
            // Set policy rate type
            policyOBJ.rateType = getField('#js-accrual-rate-type-edit option:selected');
            // Set policy minimum aplicable type
            policyOBJ.applicableTimeType = getField('.js-minimum-applicable-time-edit:checked');
            // Set policy minimum aplicable time
            policyOBJ.applicableTime = getField('#js-minimum-applicable-hours-edit');
            //
            policyOBJ.plans = getAccrualPlans('edit');
            // // Set default accural flow check
            policyOBJ.accuralDefaultFlow = $('#js-accrual-default-flow-edit').prop('checked') === true ? 1 : 0;
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
            policyOBJ.carryOverCheck = getField('#js-carryover-cap-check-edit');
            // Set policy carryover
            policyOBJ.carryOverVal = getField('#js-carryover-cap-edit');
            // Set policy carryover
            policyOBJ.carryOverType = getField('#js-accrual-carryover-type-edit option:selected');
            // Set policy carryover
            policyOBJ.carryOverCycle = getField('#js-carryover-cycle-edit');
            //
            saveStep(policyOBJ);
            //
            return true;
        }

        //
        if (step === 4) {
            // Set policy negative balance
            policyOBJ.negativeBalanceCheck = getField('#js-accrual-balance-edit');
            // Set policy negative balance
            policyOBJ.negativeBalanceVal = getField('#js-maximum-balance-edit');
            // Set policy negative balance
            policyOBJ.negativeBalanceType = getField('#js-accrual-negative-balance-type-edit option:selected');
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
            policyOBJ.applicableDate = getField('#js-custom-date-edit');
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
            policyOBJ.resetDate = getField('#js-custom-reset-date-edit');
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
            policyOBJ.newHireTime = getField('#js-accrue-new-hire-edit');
            // Set new hire time type
            policyOBJ.newHireTimeType = getField('#js-accrual-new-hire-time-type-edit option:selected');
            // Set new hire rate
            policyOBJ.newHireRate = getField('#js-newhire-prorate-edit');
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
    function finalStepCompletedEdit(policy) {
        alertify.confirm(
            'This action will effect the employees balance. Are you sure you want to continue?',
            () => {
                //
                ml(true, 'policy');
                //
                let post = Object.assign({}, policy, {
                    action: 'update_policy',
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
                        if (redirectPage) loadViewPage();
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
    function stepCompletedReset(step) {
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
    function finalStepCompletedReset(policy) {
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
    $(document).on('click', '#js-is-esta-edit', function (e) {

        if ($(this).is(":checked")) {

            $("#js-esta-policy-box").show();
        } else {
            $("#js-esta-policy-box").hide();
        }

    });

    //
    function isValidInteger(str) {
        const num = Number(str);
        return Number.isInteger(num) && num > 0;
    }


    //
    $(document).on('click', '#js-is-esst-edit', function (e) {

        if ($(this).is(":checked")) {

            $("#js-est-policy-box").show();
        } else {
            $("#js-est-policy-box").hide();
        }

    });

});