$(function () {
    //
    let policyOBJ = {
        type: 0,
        title: 0,
        order: 1,
        policyCategory: 1,
        entitledEmployees: [],
        isEntitledEmployees: 0,
        offDays: [],
        approver: 0,
        approverList: [],
        deactivate: 0,
        include: 1,
        isESST:0,
        isESTA:0,
        employeeTypes: [],
        method: 'none',
        time: 'none',
        frequency: 'none',
        frequencyVal: 0,
        rate: 0,
        rateType: 'hours',
        applicableTime: 0,
        applicableTimeType: 'hours',
        carryOverCheck: 'no',
        carryOverType: 'hours',
        carryOverVal: 0,
        carryOverCycle: 1,
        negativeBalanceCheck: 'no',
        negativeBalanceVal: 0,
        negativeBalanceType: 'hours',
        applicableDateType: 'hireDate',
        applicableDate: 0,
        resetDateType: 'policyDateCustom',
        resetDate: 0,
        newHireTime: 0,
        newHireTimeType: 0,
        newHireRate: 0,
        plans: []
    };
    //
    window.timeoff.stepCompletedAdd = stepCompletedAdd;
    window.timeoff.finalStepCompletedAdd = finalStepCompletedAdd;

    // Click events
    // Change events
    // Policy type change
    $('#js-category-add').on('change', function () {
        var i = $('#js-category-add option[value="' + ($(this).val()) + '"]').text().toLowerCase().trim();
        //
        if (i.match(/(fmla)/g) !== null) {
            $('.js-fmla-range-wrap-add').show();
        } else {
            $('.js-fmla-range-wrap-add').hide(0);
            $('.js-fmla-range-add').prop('checked', false);
            $('.js-fmla-range-add[value="standard_year"]').prop('checked', true);
        }
    });

    $('#js-employee-add').on('select2:select', function (event) {
        //
        if (event.params.data.text != 'All') {
            //
            let newVals = $(this).val().filter(function (ef) {
                return ef == 'all' ? false : true;
            });
            $('#js-employee-add').val(newVals);
        } else {
            $('#js-employee-add').val('all');
        }
        $('#js-employee-add').trigger('change.select2');
    });

    $('#js-approvers-list-add').on('select2:select', function (event) {
        //
        if (event.params.data.text != 'All') {
            //
            let newVals = $(this).val().filter(function (ef) {
                return ef == 'all' ? false : true;
            });
            $('#js-approvers-list-add').val(newVals);
        } else {
            $('#js-approvers-list-add').val('all');
        }
        $('#js-approvers-list-add').trigger('change.select2');
    });

    // Accrual method change
    // $('#js-accrual-method-add').change(setAccrualText);
    // Accrual frequency
    $('#js-accrual-frequency-add').on('change', function () {
        //
        if ($(this).val() == 'none') {
            if ($(`#js-accrual-time-add[value="none"]`).text() != 'Jan To Dec') {
                $(`#js-accrual-time-add`).html(`
                <option value="none" selected="true">Jan To Dec</option>
                <option value="start_of_period">Jan to Jun</option>
                <option value="end_of_period">Jul to Dec</option>
                `);
                $(`#js-accrual-time-add`).select2();
            }
        } else {
            if ($(`#js-accrual-time-add[value="none"]`).text() != '1st To 30th') {
                $(`#js-accrual-time-add`).html(`
                    <option value="none" selected="true">1st To 30th</option>
                    <option value="start_of_period">1st To 15th</option>
                    <option value="end_of_period">15th To 30th</option>
                `);
                $(`#js-accrual-time-add`).select2();
            }
        }
        if ($(this).val() == 'custom') {
            $('.jsCustomBoxAdd').show(0);
            $('#js-accrual-time-add').prop('disabled', true);
            $('#js-accrual-time-text-add').show();
        } else {
            $('.jsCustomBoxAdd').hide(0);
            $('#js-accrual-time-add').prop('disabled', false);
            $('#js-accrual-time-text-add').hide();
        }
        //
        setAccrualText();
    });
    // Carryover change
    $('#js-carryover-cap-check-add').change(function () {
        $('.js-carryover-box-add').find('input').val(0);
        if ($(this).val() === 'no') {
            $('.js-carryover-box-add').hide();
        } else {
            $('.js-carryover-box-add').show();
        }
    });
    // Negative balance change
    $('#js-accrual-balance-add').change(function () {
        $('.js-accrual-balance-add').find('input').val(0);
        if ($(this).val() === 'no') {
            $('.js-negative-box-add').hide();
        } else {
            $('.js-negative-box-add').show();
        }
    });
    // Policy applicable change
    $('.js-hire-date-add').on('change', function () {
        if ($(this).val() == 'hireDate') {
            $('.jsImplementDateBox-add').hide(0);
            $('#js-custom-date-add').val('');
        } else {
            $('.jsImplementDateBox-add').show(0);
        }
    });
    // Policy reset date
    $('.js-policy-reset-date-add').on('change', function () {
        if ($(this).val() == 'policyDate') {
            $('.jsResetDateBox-add').hide(0);
            $('#js-custom-reset-date-add').val('');
        } else {
            $('.jsResetDateBox-add').show(0);
        }
    });
    //
    $('.js-plan-btn-add').click(function (e) {
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
    $(document).on('select2:selecting', '#js-plans-select-add', function (e) { makePlanRow(e.params.args.data.id, 'add'); });
    $(document).on('select2:unselecting', '#js-plans-select-add', function (e) { removePlan(e.params.args.data.id, 'add'); });
    $(document).on('click', '.js-remove-plan-add', function (e) {
        removePlan($(this).closest('li').data('id'), 'add');
        $('#js-plans-select-add').select2(
            'val',
            newValues($('#js-plans-select-add').val(), $(this).closest('li').data('id'))
        );
    });
    //
    $('#js-unlimited-policy-check-add').click(function () {
        if ($(this).prop('checked') === true) $('#js-plan-box-add').hide();
        else $('#js-plan-box-add').show();
    });
    //
    $('#js-accrual-time-add').change(setAccrualText);
    $('#js-accrual-rate-type-add').change(setAccrualText);
    $('#js-accrual-rate-add').keyup(function () {
        //
        if ($(this).val().trim() == '') {
            $('#js-accrual-time-add').prop('disabled', true);
            $('#js-accrual-time-text-add').show();
        } else if ($(this).val().match(/[a-zA-Z]/) != null) {
            $(this).val(0);
            $('#js-accrual-time-add').prop('disabled', true);
            $('#js-accrual-time-text-add').show();
        } else {
            //
            if ($(this).val() < 0) {
                $(this).val(0);
                $('#js-accrual-time-add').prop('disabled', true);
                $('#js-accrual-time-text-add').show();

            } else {
                $('#js-accrual-time-add').prop('disabled', false);
                $('#js-accrual-time-text-add').hide();
            }
        }
        //
        if ($(this).val() == 0) {
            $('.js-plan-btn-add')
                .prop('disabled', true);
        } else {
            $('.js-plan-btn-add')
                .prop('disabled', false);
        }
        //
        setAccrualText();
    });
    $('#js-accrual-frequency-val-add').keyup(function () {
        //
        if ($(this).val().trim() <= 0) $(this).val(1);
        else if ($(this).val().trim() > 12) $(this).val(12);
        //
        setAccrualText();
    });

    //
    $('#js-policy-title-add').keyup(function () {
        $('#jsPolicyTitleAdd').text(' - ' + $(this).val());
    });

    // New hire add change
    $('#js-accrue-new-hire-add').keyup(setNewHireAccrual);
    $('#js-newhire-prorate-add').keyup(setNewHireAccrual);
    $('#js-accrual-new-hire-time-type').change(setNewHireAccrual);

    // Handles back and forth clicks
    function stepCompletedAdd(step) {
        //
        if (step === 1) {
            //
            policyOBJ.policyCategory = getField('#js-policy-type-add');
            // Set policy type
            policyOBJ.type = getField('#js-category-add');
            // Check policy type
            if (policyOBJ.type == 0) {
                alertify.alert('WARNING!', 'Please, select the policy type.', () => { });
                return false;
            }
            // Set policy title
            policyOBJ.title = getField('#js-policy-title-add');
            // Check policy title
            if (policyOBJ.title == 0) {
                alertify.alert('WARNING!', 'Please, add the policy title.', () => { });
                return false;
            }
            // Set sort order
            policyOBJ.order = getField('#js-sort-order-add');
            // Set entitled employees
            policyOBJ.entitledEmployees = getField('#js-employee-add');
            // Set approver check
            policyOBJ.approver = $('#js-approver-check-add').prop('checked') === true ? 1 : 0;
            //
            policyOBJ.approverList = [];
            //
            if (policyOBJ.approver == 1) {
                policyOBJ.approverList = getField('#js-approvers-list-add') || [];
            }
            // Set deactivate check
            policyOBJ.deactivate = $('#js-archive-check-add').prop('checked') === true ? 1 : 0;
            // // Set deactivate check
            policyOBJ.include = $('#js-include-check-add').prop('checked') === true ? 1 : 0;
            // // Set deactivate check
            policyOBJ.isESST = $('#js-is-esst-add').prop('checked') === true ? 1 : 0;
            //
            policyOBJ.isESTA = $('#js-is-esta-add').prop('checked') === true ? 1 : 0;
            //
            policyOBJ.isEntitledEmployees = $('.jsIsEntitledEmployee:checked').val();
            //
            policyOBJ.employeeTypes = $('#js-employee-type-add').val();
            //
            policyOBJ.offDays = getField('#js-off-days-add');
            // Check policy title
            if (policyOBJ.employeeTypes == null) {
                alertify.alert('WARNING!', 'Please, add the employee type.', () => { });
                return false;
            }


            //
            saveStep(policyOBJ);
            //
            return true;
        }

        //
        if (step === 2) {
            // Set policy method
            policyOBJ.method = 'hours_per_month';
            // policyOBJ.method = getField('#js-accrual-method-add');
            // Set policy time
            policyOBJ.time = getField('#js-accrual-time-add');
            // Set policy frequency
            policyOBJ.frequency = getField('#js-accrual-frequency-add');
            // Set policy frequency type
            policyOBJ.frequencyVal = getField('#js-accrual-frequency-val-add');
            // Set policy rate
            policyOBJ.rate = getField('#js-accrual-rate-add');
            // Set policy rate type
            policyOBJ.rateType = getField('#js-accrual-rate-type-add option:selected');
            // Set policy minimum aplicable type
            policyOBJ.applicableTimeType = getField('.js-minimum-applicable-time-add:checked');
            // Set policy minimum aplicable time
            policyOBJ.applicableTime = getField('#js-minimum-applicable-hours-add');
            // Set plans 
            policyOBJ.plans = getAccrualPlans('add');
            // // Set default accural flow check
            policyOBJ.accuralDefaultFlow = $('#js-accrual-default-flow-add').prop('checked') === true ? 1 : 0;
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
            policyOBJ.carryOverCheck = getField('#js-carryover-cap-check-add');
            // Set policy carryover
            policyOBJ.carryOverVal = getField('#js-carryover-cap-add');
            // Set policy carryover
            policyOBJ.carryOverType = getField('#js-accrual-carryover-type option:selected');
            // Set policy carryover
            policyOBJ.carryOverCycle = getField('#js-carryover-cycle-add');
            //
            saveStep(policyOBJ);
            //
            return true;
        }

        //
        if (step === 4) {
            // Set policy negative balance
            policyOBJ.negativeBalanceCheck = getField('#js-accrual-balance-add');
            // Set policy negative balance
            policyOBJ.negativeBalanceVal = getField('#js-maximum-balance-add');
            // Set policy negative balance
            policyOBJ.newHireTimeType = getField('#js-accrual-negative-balance-type option:selected');
            //
            saveStep(policyOBJ);
            //
            return true;
        }

        //
        if (step === 5) {
            // Set applicable type
            policyOBJ.applicableDateType = getField('.js-hire-date-add:checked');
            // Set applicable date
            policyOBJ.applicableDate = getField('#js-custom-date-add');
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
            policyOBJ.resetDateType = getField('.js-policy-reset-date-add:checked');
            // Set reset date
            policyOBJ.resetDate = getField('#js-custom-reset-date-add');
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
            policyOBJ.newHireTime = getField('#js-accrue-new-hire-add');
            // Set new hire time type
            policyOBJ.newHireTimeType = getField('#js-accrual-new-hire-time-type option:selected');
            // Set new hire rate
            policyOBJ.newHireRate = getField('#js-newhire-prorate-add');
            //
            saveStep(policyOBJ);
            //
            return true;
        }

        return false;
    }

    //
    function finalStepCompletedAdd(policy) {
        ml(true, 'policy');
        //
        let post = Object.assign({}, policy, {
            action: 'create_policy',
            companyId: companyId,
            employeeId: employeeId,
            employerId: employerId,
            public: 0
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
                loadViewPage();
            });
            return;
        });
    }
})