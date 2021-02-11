<script type="text/javascript">
    

    //
    $('#js-save-add-btn').click(function(e){
        e.preventDefault();
        var megaOBJ = {},
        obj = {},
        formats = <?=json_encode($timeOffFormat);?>;
        megaOBJ.companySid = <?=$company_sid;?>;
        megaOBJ.formatSid = <?=$timeoff_format_sid;?>;
        megaOBJ.employeeSid = <?=$employer_sid;?>;
        obj.policy =  megaOBJ.policy = $('#js-policy-title-add').val().trim();
        obj.assignedEmployees =  megaOBJ.assignedEmployees = $('#js-employee-add').val();
        obj.action =  megaOBJ.action = 'add_company_policy';
        obj.status =  megaOBJ.status = 1;
        // obj.status =  megaOBJ.status = $('#js-status-add').val();
        obj.sortOrder =  megaOBJ.sortOrder = $('#js-sort-order-add').val();
        obj.archiveCheck =  megaOBJ.archiveCheck = Number($('#js-archive-check-add').prop('checked'));
        obj.approverCheck =  megaOBJ.approverCheck = Number($('#js-approver-check-add').prop('checked'));
        obj.isIncluded =  megaOBJ.isIncluded = Number($('#js-include-check-add').prop('checked'));
        obj.isArchived =  megaOBJ.isArchived = Number($('#js-archive-check-add').prop('checked'));
        // Get categories
        obj.types =  megaOBJ.types = $('#js-category-add').val();

        // Acccural settings
        obj.allowedDays =  megaOBJ.allowedDays = megaOBJ.allowedHours = megaOBJ.allowedMinutes = 0;

        // Validation
        // Check if title is empty
        if(megaOBJ.policy == ''){
            alertify.alert('ERROR!', 'Please add a policy title.');
            return;
        }
        //
        obj.newHireDays = megaOBJ.newHireDays = parseInt($('#js-accrue-new-hire-add').val().trim());
        if(isNaN(megaOBJ.newHireDays)) obj.newHireDays = megaOBJ.newHireDays = 0;


        // Check and assign employees
        if(megaOBJ.assignedEmployees == null) megaOBJ.assignedEmployees = 'all';
        else if (megaOBJ.assignedEmployees.indexOf('all') !== -1)  megaOBJ.assignedEmployees = 'all';
        else if ((megaOBJ.assignedEmployees.length == employees.length) || megaOBJ.assignedEmployees.length - 1 == employees.length)  megaOBJ.assignedEmployees = 'all';
        else megaOBJ.assignedEmployees = megaOBJ.assignedEmployees.join(',');
        //
        obj.accuralDateType = megaOBJ.accuralDateType = 'hireDate';
        obj.accuralStartDate = megaOBJ.accuralStartDate = null;
        //
        if($('.js-hire-date-add:checked').val() == 'customHireDate'){
            if($('#js-custom-date-add').val() == ''){
                alertify.alert('ERROR!', 'Please select accural start date.');
                return;
            }
            obj.accuralDateType = megaOBJ.accuralDateType = 'custom';
            obj.accuralStartDate = megaOBJ.accuralStartDate = $('#js-custom-date-add').val();
        }
        // FMLA Range
        if(getOptionValue(megaOBJ.types, 'add') !== null){
            obj.fmlaRange = megaOBJ.fmlaRange = $('.js-fmla-range-add:checked').val();
            if(!megaOBJ.fmlaRange){
                alertify.alert('Please select a FMLA range.');
                return;
            }
        }
        // Set up phase 3 checks
        // Minimum applicable hours
        obj.minimumApplicableHours = megaOBJ.minimumApplicableHours = $('#js-minimum-applicable-hours-add').val().trim();
        obj.minimumApplicableHoursType = megaOBJ.minimumApplicableHoursType = $('.js-minimum-applicable-time-add:checked').val().trim();
        // Accrual Method
        obj.accrualMethod = megaOBJ.accrualMethod = $('#js-accrual-method-add').val();
        // Accrual Rate
        obj.accrualRate = megaOBJ.accrualRate = $('#js-accrual-rate-add').val().trim();
        // Accrual Time
        obj.accrualTime = megaOBJ.accrualTime = $('#js-accrual-time-add').val();
        // Reset Date
        obj.resetDate = megaOBJ.resetDate = 0;
        if($('.js-policy-reset-date-add:checked').val() == 'policyDateCustom'){
            obj.resetDate = megaOBJ.resetDate = $('#js-custom-reset-date-add').val();
        }
        // Accrual Frequency
        obj.accrualFrequency = megaOBJ.accrualFrequency = $('#js-accrual-frequency-add').val();
        obj.accrualFrequencyCustom = megaOBJ.accrualFrequencyCustom = $('#js-accrue-custom-frequency-add').val();
        // Carryover Cap Check
        obj.carryoverCapCheck = megaOBJ.carryoverCapCheck = $('#js-carryover-cap-check-add').val();
        // Carryover Cap
        obj.carryoverCap = megaOBJ.carryoverCap = $('#js-carryover-cap-add').val().trim();
        // Negative Balance Ccheckc
        obj.negativeBalance = megaOBJ.negativeBalance = $('#js-accrual-balance-add').val();
        // Negative Balance
        obj.negativeBalanceAllowed = megaOBJ.negativeBalanceAllowed = $('#js-maximum-balance-add').val();
        // Newhire Prorate
        obj.newhireProrate = megaOBJ.newhireProrate = $('#js-newhire-prorate-add').val();
        // Checks
        if(megaOBJ.policyStartDate == ''){
            alertify.alert('ERROR!', 'Please, select Policy Start Date.', function(){ return; });
            return;
        }
        //
        if(megaOBJ.minimumApplicableHours == ''){
            obj.minimumApplicableHours = megaOBJ.minimumApplicableHours = 0;
        }
        //
        if(megaOBJ.accrualRate == '' && megaOBJ.accrualMethod !='unlimited'){
            alertify.alert('ERROR!', 'Please, add accrual rate.', function(){ return; });
            return;
        }
        //
        if(megaOBJ.carryoverCapCheck == 'yes' && megaOBJ.carryoverCap == ''){
            obj.carryoverCap= megaOBJ.carryoverCap = 0;
        }
        //
        if(megaOBJ.negativeBalance == 'yes' && megaOBJ.negativeBalanceAllowed == ''){
            obj.negativeBalanceAllowed = megaOBJ.negativeBalanceAllowed = 0;
        }
        //
        if(megaOBJ.newhireProrate == ''){
            obj.newhireProrate = megaOBJ.newhireProrate = 0;
        }
        // Get accrual plans
        megaOBJ.plans = getAccrualPlans('add');
        obj.plans = getAccrualPlans('add', true);
        //
        megaOBJ.reset_policy = JSON.stringify(obj);
        //
        if( typeof(megaOBJ.plans) === 'boolean') return;
        //
        ml(true, 'policy');
        $.post(baseURI+'handler', megaOBJ, function(resp) {

            if(resp.Status === false){
                ml(false, 'policy');
                alertify.alert('ERROR!', resp.Response, function(){ return; });
                return;
            }

            ml(false, 'policy');
            alertify.alert('SUCCESS!', resp.Response, function(){ setHistory('view', baseURI+'policies/view', 'View Page'); });
        });
    });

   

    // setAccrualView();

    //
    
</script>