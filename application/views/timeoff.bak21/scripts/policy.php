<script type="text/javascript">
    var baseURI = "<?=base_url();?>timeoff/";
    $(function(){
        var employees = [],
        options = {
            yearly: 1
        },
        is_filter = false,
        oldState = {},
        lastPage = "<?=$page;?>",
        default_slot = 0,
        fetchStatus = 'active',
        policies = [],
        plans = [],
        categories = [],
        xhr = null,
        pOBJ = {
            'fetchCompanyPolicies' : {
                page: 1,
                totalPages: 0,
                limit: 0,
                records: 0,
                totalRecords: 0,
            }
        },
        record = [],
        intervalCatcher = null;

        /* FILTER START */
        // fetchPoliciesList();
        // fetchPlans();
        // fetchCategories();
        // fetchEmployees();

        // Select2
        $('#js-filter-status').select2();
        $('#js-status-add').select2();
        $('#js-status-edit').select2();
        $('#js-accural-type-add').select2();
        $('#js-accrue-start-add').select2();
        

        //
        $('#js-carryover-cap-check-edit').change(function(){
            $('.js-carryover-box-edit').find('input').val(0);
            if($(this).val() === 'no'){
                $('.js-carryover-box-edit').hide();
            } else{
                $('.js-carryover-box-edit').show();
            }
        });

        //
        $('#js-accrual-balance-edit').change(function(){
            $('.s-negative-box-edit').find('input').val(0);
            if($(this).val() === 'no'){
                $('.js-negative-box-edit').hide();
            } else{
                $('.js-negative-box-edit').show();
            }
        });

        

        

       
        /* FILTER END */

        /* TAB CHANGER START*/
        
        /* TAB CHANGER END*/

        /* VIEW PAGE START */
        //
       
       
        
        
        //
       

        /* VIEW PAGE END */

        /* ADD PAGE START*/
        
        
        /* ADD PAGE END*/

        /* EDIT PAGE END*/
        $('#js-custom-date-edit-reset').datepicker({ 
            dateFormat: 'mm-dd-yy',
            changeYear: true,
            changeMonth: true,
        });
        // $('#js-policy-start-date-edit').datepicker({ dateFormat: 'mm-dd-yy' });
        $('#js-custom-reset-date-edit-reset').datepicker({ 
            dateFormat: 'mm-dd-yy',
            changeYear: true,
            changeMonth: true,
        });
        $('#js-custom-date-edit').datepicker({ 
            dateFormat: 'mm-dd-yy',
            changeYear: true,
            changeMonth: true,
        });
        // $('#js-policy-start-date-edit').datepicker({ dateFormat: 'mm-dd-yy' });
        $('#js-custom-reset-date-edit').datepicker({ 
            dateFormat: 'mm-dd-yy',
            changeYear: true,
            changeMonth: true,
        });
        //
        $('#js-unlimited-policy-check-edit').click(function(){
            if($(this).prop('checked') === true) $('#js-plan-box-edit').hide();
            else $('#js-plan-box-edit').show();
        });
        //
        $('#js-save-edit-btn').click(function(e){
            e.preventDefault();
            var megaOBJ = {},
            formats = <?=json_encode($timeOffFormat);?>;
            megaOBJ.companySid = <?=$company_sid;?>;
            megaOBJ.formatSid = <?=$timeoff_format_sid;?>;
            megaOBJ.policySid = $('#js-policy-id-edit').val();
            megaOBJ.employeeSid = <?=$employer_sid;?>;
            megaOBJ.policy = $('#js-policy-title-edit').val().trim();
            megaOBJ.assignedEmployees = $('#js-employee-edit').val();
            megaOBJ.action = 'edit_company_policy';
            megaOBJ.status = 1;
            // megaOBJ.status = $('#js-status-edit').val();
            megaOBJ.sortOrder = $('#js-sort-order-edit').val();
            megaOBJ.archiveCheck = Number($('#js-archive-check-edit').prop('checked'));
            megaOBJ.approverCheck = Number($('#js-approver-check-edit').prop('checked'));
            megaOBJ.isIncluded = Number($('#js-include-check-edit').prop('checked'));
            megaOBJ.unlimitedPolicyCheck = Number($('#js-unlimited-policy-check-edit').prop('checked'));
            megaOBJ.isArchived = Number($('#js-archive-check-edit').prop('checked'));
            megaOBJ.isUse = Number($('#js-lose-edit').prop('checked'));
            // Categories
            megaOBJ.types = $('#js-category-edit').val();
            // Acccural settings
            megaOBJ.accuralType = $('#js-accural-type-edit').val();
            megaOBJ.allowedDays = megaOBJ.allowedHours = megaOBJ.allowedMinutes = 0;
            // Validation
            // Check if title is empty
            if(megaOBJ.policy == ''){
                alertify.alert('ERROR!', 'Please add a policy title.');
                return;
            }
            // Check and assign employees
            if(megaOBJ.assignedEmployees == null) megaOBJ.assignedEmployees = 'all';
            else if (megaOBJ.assignedEmployees.indexOf('all') !== -1)  megaOBJ.assignedEmployees = 'all';
            else if ((megaOBJ.assignedEmployees.length == employees.length) || megaOBJ.assignedEmployees.length - 1 == employees.length)  megaOBJ.assignedEmployees = 'all';
            else megaOBJ.assignedEmployees = megaOBJ.assignedEmployees.join(',');
            // Accural settigs
            if(megaOBJ.accuralType == 0){
                alertify.alert('ERROR!', 'Please select a accural type.');
                return;
            }
            //
            megaOBJ.accuralDateType = 'hireDate';
            megaOBJ.accuralStartDate = null;
            //
            if($('.js-hire-date-edit:checked').val() == 'customHireDate'){
                if($('#js-custom-date-edit').val() == ''){
                    alertify.alert('ERROR!', 'Please select accural start date.');
                    return;
                }
                megaOBJ.accuralDateType = 'custom';
                megaOBJ.accuralStartDate = $('#js-custom-date-edit').val();
            }

            // FMLA Range
            if(getOptionValue(megaOBJ.types, 'edit') !== null){
                megaOBJ.fmlaRange = $('.js-fmla-range-edit:checked').val();
                if(!megaOBJ.fmlaRange){
                    alertify.alert('Please select a FMLA range.');
                    return;
                }
            }

            //
            megaOBJ.newHireDays = parseInt($('#js-accrue-new-hire-edit').val().trim());
            if(isNaN(megaOBJ.newHireDays)) megaOBJ.newHireDays = 0;

            // Set up phase 3 checks
            // Policy Activation Date
            // megaOBJ.policyStartDate = $('#js-policy-start-date-edit').val();
            // Minimum applicable hours
            megaOBJ.minimumApplicableHours = $('#js-minimum-applicable-hours-edit').val().trim();
            megaOBJ.minimumApplicableHoursType = $('.js-minimum-applicable-time-edit:checked').val().trim();
            // Accrual Method
            megaOBJ.accrualMethod = $('#js-accrual-method-edit').val();
            // Accrual Rate
            megaOBJ.accrualRate = $('#js-accrual-rate-edit').val().trim();
            // Accrual Time
            megaOBJ.accrualTime = $('#js-accrual-time-edit').val();
            // Reset Date
            megaOBJ.resetDate = 0;
            if($('.js-policy-reset-date-edit:checked').val() == 'policyDateCustom'){
                megaOBJ.resetDate = $('#js-custom-reset-date-edit').val();
            }
            // Accrual Frequency
            megaOBJ.accrualFrequency = $('#js-accrual-frequency-edit').val();
            megaOBJ.accrualFrequencyCustom = $('#js-accrue-custom-frequency-edit').val();
            // Carryover Cap Check
            megaOBJ.carryoverCapCheck = $('#js-carryover-cap-check-edit').val();
            // Carryover Cap
            megaOBJ.carryoverCap = $('#js-carryover-cap-edit').val().trim();
            // Negative Balance Ccheckc
            megaOBJ.negativeBalance = $('#js-accrual-balance-edit').val();
            // Negative Balance
            megaOBJ.negativeBalanceAllowed = $('#js-maximum-balance-edit').val();
            // Newhire Prorate
            megaOBJ.newhireProrate = $('#js-newhire-prorate-edit').val();
            // Checks
            if(megaOBJ.policyStartDate == ''){
                alertify.alert('ERROR!', 'Please, select Policy Start Date.', function(){ return; });
                return;
            }
            //
            if(megaOBJ.minimumApplicableHours == ''){
                megaOBJ.minimumApplicableHours = 0;
            }
            //
            if(megaOBJ.accrualRate == '' && megaOBJ.accrualMethod !='unlimited'){
                alertify.alert('ERROR!', 'Please, add accrual rate.', function(){ return; });
                return;
            }
            //
            if(megaOBJ.carryoverCapCheck == 'yes' && megaOBJ.carryoverCap == ''){
                megaOBJ.carryoverCap = 0;
            }
            //
            if(megaOBJ.negativeBalance == 'yes' && megaOBJ.negativeBalanceAllowed == ''){
                megaOBJ.negativeBalanceAllowed = 0;
            }
            //
            if(megaOBJ.newhireProrate == ''){
                megaOBJ.newhireProrate = 0;
            }

            // Get accrual plans
            megaOBJ.plans = getAccrualPlans('edit');
            megaOBJ.reset_policy = getResetPolicy();
            if(!megaOBJ.reset_policy) return;
            //
            if( typeof(megaOBJ.plans) === 'boolean') return;
            //
            ml(true, 'policy');
            $.post(baseURI+'handler', megaOBJ, function(resp) {

                ml(false, 'policy');
                if(resp.Status === false){
                    alertify.alert('ERROR!', resp.Response, function(){ return; });
                    return;
                }

                alertify.alert('SUCCESS!', resp.Response, function(){ setHistory('view', baseURI+'policies/view', 'View Page'); });
            });
        });


        //
        function getResetPolicy(){
            //
            let obj = {}
            //
            obj.assignedEmployees = $('#js-employee-edit-reset').val();
            obj.archiveCheck = Number($('#js-archive-check-edit-reset').prop('checked'));
            obj.approverCheck = Number($('#js-approver-check-edit-reset').prop('checked'));
            obj.isIncluded = Number($('#js-include-check-edit-reset').prop('checked'));
            obj.unlimitedPolicyCheck = Number($('#js-unlimited-policy-check-edit-reset').prop('checked'));
            obj.isArchived = Number($('#js-archive-check-edit-reset').prop('checked'));
            obj.isUse = Number($('#js-lose-edit-reset').prop('checked'));
            // Acccural settings
            obj.accuralType = $('#js-accural-type-edit-reset').val();
            obj.allowedDays = obj.allowedHours = obj.allowedMinutes = 0;
            // Validation
            // Check and assign employees
            if(obj.assignedEmployees == null) obj.assignedEmployees = 'all';
            else if (obj.assignedEmployees.indexOf('all') !== -1)  obj.assignedEmployees = 'all';
            else if ((obj.assignedEmployees.length == employees.length) || obj.assignedEmployees.length - 1 == employees.length)  obj.assignedEmployees = 'all';
            else obj.assignedEmployees = obj.assignedEmployees.join(',');
            // Accural settigs
            if(obj.accuralType == 0){
                alertify.alert('ERROR!', 'Please select a accural type.');
                return false;
            }
            //
            obj.accuralDateType = 'hireDate';
            obj.accuralStartDate = null;
            //
            if($('.js-hire-date-edit-reset:checked').val() == 'customHireDate'){
                if($('#js-custom-date-edit-reset').val() == ''){
                    alertify.alert('ERROR!', 'Please select accural start date.');
                    return false;
                }
                obj.accuralDateType = 'custom';
                obj.accuralStartDate = $('#js-custom-date-edit-reset').val();
            }

            // FMLA Range
            if(getOptionValue(obj.types, 'edit-reset') !== null){
                obj.fmlaRange = $('.js-fmla-range-edit-reset:checked').val();
                if(!obj.fmlaRange){
                    alertify.alert('Please select a FMLA range.');
                    return false;
                }
            }

            //
            obj.newHireDays = parseInt($('#js-accrue-new-hire-edit-reset').val().trim());
            if(isNaN(obj.newHireDays)) obj.newHireDays = 0;

            // Set up phase 3 checks
            // Policy Activation Date
            // obj.policyStartDate = $('#js-policy-start-date-edit-reset').val();
            // Minimum applicable hours
            obj.minimumApplicableHours = $('#js-minimum-applicable-hours-edit-reset').val().trim();
            obj.minimumApplicableHoursType = $('.js-minimum-applicable-time-edit:checked').val().trim();
            // Accrual Method
            obj.accrualMethod = $('#js-accrual-method-edit-reset').val();
            // Accrual Rate
            obj.accrualRate = $('#js-accrual-rate-edit-reset').val().trim();
            // Accrual Time
            obj.accrualTime = $('#js-accrual-time-edit-reset').val();
            // Reset Date
            obj.resetDate = 0;
            if($('.js-policy-reset-date-edit:checked').val() == 'policyDateCustom'){
                obj.resetDate = $('#js-custom-reset-date-edit-reset').val();
            }
            // Accrual Frequency
            obj.accrualFrequency = $('#js-accrual-frequency-edit-reset').val();
            obj.accrualFrequencyCustom = $('#js-accrue-custom-frequency-edit-reset').val();
            // Carryover Cap Check
            obj.carryoverCapCheck = $('#js-carryover-cap-check-edit-reset').val();
            // Carryover Cap
            obj.carryoverCap = $('#js-carryover-cap-edit-reset').val().trim();
            // Negative Balance Ccheckc
            obj.negativeBalance = $('#js-accrual-balance-edit-reset').val();
            // Negative Balance
            obj.negativeBalanceAllowed = $('#js-maximum-balance-edit-reset').val();
            // Newhire Prorate
            obj.newhireProrate = $('#js-newhire-prorate-edit-reset').val();
            // Checks
            if(obj.policyStartDate == ''){
                alertify.alert('ERROR!', 'Please, select Policy Start Date.', function(){ return; });
                return false;
            }
            //
            if(obj.minimumApplicableHours == ''){
                obj.minimumApplicableHours = 0;
            }
            //
            if(obj.accrualRate == '' && obj.accrualMethod !='unlimited'){
                alertify.alert('ERROR!', 'Please, add accrual rate.', function(){ return; });
                return false;
            }
            //
            if(obj.carryoverCapCheck == 'yes' && obj.carryoverCap == ''){
                obj.carryoverCap = 0;
            }
            //
            if(obj.negativeBalance == 'yes' && obj.negativeBalanceAllowed == ''){
                obj.negativeBalanceAllowed = 0;
            }
            //
            if(obj.newhireProrate == ''){
                obj.newhireProrate = 0;
            }

            // Get accrual plans
            obj.plans = getAccrualPlans('edit-reset');
            //
            return JSON.stringify(obj);
        }
        //
        function showEditPage(policy){
            lastPage = 'edit';
            $('.js-hint').hide(0);
            // Defaults
            $('.js-hire-date-edit[value="hireDate"]').prop('checked', true);
            $('.js-carryover-box-edit').hide();
            $('.js-negative-box-edit').hide();
            $('#js-plan-box-edit').find('ul').html('');
            $('#js-plan-box-edit').hide();
            $('.jsPlanArea').html('');
            $('#js-custom-date-edit').val('');
            //
            $('.jsTabPolicy').parent().removeClass('active');
            $('.jsTabPolicy[data-href="#jsAddTabCurrent"]').parent().addClass('active');
            $('.jsTabPane').hide(0);
            $('#jsAddTabCurrent').show(0);
            
            // Changes
            //
            $('#js-accrue-custom-frequency-edit').val(0);
            //
            $('#js-policy-id-edit').val(policy.policy_id);
            //
            $('#js-policy-title-edit').val(ucwords(policy.policy_title));
            //
            $('#js-employee-edit').find('option').prop('checked', false);
            //
            $('#js-employee-edit').select2MultiCheckboxes({
                templateSelection: function(selected, total) {
                    total = total - 1;
                    return "Selected " +( $.inArray('all', $('#js-employee-edit').val()) !== -1 ? total : selected.length )+ " of " + total;
                }
            });
            //
            $('#js-category-edit').select2();
            $('#js-category-edit').select2('val', policy.types == undefined ? 0 : policy.types[0]);

            $('#js-employee-edit').select2('val', policy.assigned_employees.split(','));
            $('#js-sort-order-edit').val(policy.sort_order);
            $('#js-approver-check-edit').prop('checked', Boolean(Number(policy.for_admin)));
            $('#js-archive-check-edit').prop('checked', Boolean(Number(policy.is_archived)));
            $('#js-include-check-edit').prop('checked', Boolean(Number(policy.is_included)));
            //
            if(policy.accural_start_date != null){
                $('.js-hire-date-edit[value="customHireDate"]').prop('checked', true).trigger('change');
                $('#js-custom-date-edit').val(moment(policy.accural_start_date, 'YYYY-MM-DD').format('MM-DD-YYYY'));
            }
            //
            if(policy.reset_date != null){
                $('.js-policy-reset-date-edit[value="policyDateCustom"]').prop('checked', true).trigger('change');
                $('#js-custom-reset-date-edit').val(moment(policy.reset_date, 'YYYY-MM-DD').format('MM-DD-YYYY'));
            }
            //
            $('#js-minimum-applicable-hours-edit').val(policy.minimum_applicable_hours);
            $(`.js-minimum-applicable-time-edit[value="${policy.minimum_applicable_type}"]`).prop('checked', true);
            //
            $('#js-accrual-method-edit').select2();
            $('#js-accrual-method-edit').select2('val', policy.accrual_method == null ? 'days_per_year' : policy.accrual_method);
            //
            $('#js-accrual-time-edit').select2();
            $('#js-accrual-time-edit').select2('val', policy.accrual_time == null ? 'start_of_period' : policy.accrual_time);
            //
            $('#js-carryover-cap-check-edit').select2();
            $('#js-carryover-cap-check-edit').select2('val', policy.carryover_cap_check == 0 ? 'no' : 'yes');
            //
            $('#js-accrual-balance-edit').select2();
            $('#js-accrual-balance-edit').select2('val', policy.allow_negative_balance == 0 ? 'no' : 'yes');
            //
            $('#js-accrual-frequency-edit').select2();
            $('#js-accrual-frequency-edit').select2('val', policy.accrual_frequency == null ? 'none' : policy.accrual_frequency);
            if(policy.accrual_frequency == 'custom') {
                $('#js-accrue-custom-frequency-edit').val(policy.accrual_frequency_custom);
            }
            //
            $('#js-accrual-rate-edit').val(policy.accrual_rate);
            $('#js-carryover-cap-edit').val(policy.carryover_cap);
            $('#js-maximum-balance-edit').val(policy.negative_balance);
            //
            if(policy.carryover_cap_check == 1) $('.js-carryover-box-edit').show();
            if(policy.allow_negative_balance == 1) $('.js-negative-box-edit').show();
            //
            $('#js-accrue-new-hire-edit').val(policy.new_hire_days);
            $('#js-newhire-prorate-edit').val(policy.newhire_prorate);
            //
            if(policy.types !== undefined){
                if($('#js-category-edit option[value="'+( policy.types[0] )+'"]').text().toLowerCase().trim().match(/(fmla)/g) !== null){
                    $('.js-fmla-range-edit[value="'+( policy.fmla_range )+'"]').prop('checked', true);
                }
            }
            // load plans
            loadAccrualPlans( 'edit', policy.plans );
            //
            setResetPolicyTab(policy);
        }

        //
        function setResetPolicyTab(policy){
            //
            let resetPolicy = policy.reset_policy != null ? JSON.parse(policy.reset_policy) : {};
            //
            if(resetPolicy.for_admin === undefined) resetPolicy.for_admin = policy.for_admin ;
            if(resetPolicy.assignedEmployees === undefined) resetPolicy.assignedEmployees = policy.assigned_employees ;
            if(resetPolicy.isArchived === undefined) resetPolicy.isArchived = policy.is_archived ;
            if(resetPolicy.isIncluded === undefined) resetPolicy.isIncluded = policy.is_included ;
            if(resetPolicy.accuralStartDate === undefined) resetPolicy.accuralStartDate = policy.accural_start_date ;
            if(resetPolicy.resetDate === undefined) resetPolicy.resetDate = policy.reset_date ;
            if(resetPolicy.minimumApplicableHours === undefined) resetPolicy.minimumApplicableHours = policy.minimum_applicable_hours ;
            if(resetPolicy.minimumApplicableHoursType === undefined) resetPolicy.minimumApplicableHoursType = policy.minimum_applicable_type ;
            if(resetPolicy.accrualMethod === undefined) resetPolicy.accrualMethod = policy.accrual_method ;
            if(resetPolicy.accrualTime === undefined) resetPolicy.accrualTime = policy.accrual_time ;
            if(resetPolicy.carryoverCapCheck === undefined) resetPolicy.carryoverCapCheck = policy.carryover_cap_check ;
            if(resetPolicy.negativeBalanceAllowed === undefined) resetPolicy.negativeBalanceAllowed = policy.allow_negative_balance ;
            if(resetPolicy.accrualFrequency === undefined) resetPolicy.accrualFrequency = policy.accrual_frequency ;
            if(resetPolicy.accrualFrequencyCustom === undefined) resetPolicy.accrualFrequencyCustom = policy.accrual_frequency_custom ;
            if(resetPolicy.accrualRate === undefined) resetPolicy.accrualRate = policy.accrual_rate ;
            if(resetPolicy.carryoverCap === undefined) resetPolicy.carryoverCap = policy.carryover_cap ;
            if(resetPolicy.negativeBalanceAllowed === undefined) resetPolicy.negativeBalanceAllowed = policy.negative_balance ;
            if(resetPolicy.newHireDays === undefined) resetPolicy.newHireDays = policy.new_hire_days ;
            if(resetPolicy.newhireProrate === undefined) resetPolicy.newhireProrate = policy.newhire_prorate ;
            if(resetPolicy.types === undefined) resetPolicy.types = policy.types ;
            if(resetPolicy.fmla_range === undefined) resetPolicy.fmla_range = policy.fmla_range ;
            if(resetPolicy.plans === undefined) resetPolicy.plans = policy.plans ;
            if(
                typeof resetPolicy.assignedEmployees === 'object'
            ) resetPolicy.assignedEmployees = resetPolicy.assignedEmployees.join(',');
            // Defaults
            $('.js-hire-date-edit-reset[value="hireDate"]').prop('checked', true);
            $('.js-carryover-box-edit-reset').hide();
            $('.js-negative-box-edit-reset').hide();
            $('#js-plan-box-edit-reset').find('ul').html('');
            $('#js-plan-box-edit-reset').hide();
            // $('.jsPlanArea').html('');
            $('#js-custom-date-edit-reset').val('');
            // Changes
            //
            $('#js-accrue-custom-frequency-edit-reset').val(0);
            //
            $('#js-policy-id-edit-reset').val(policy.policy_id);
            //
            $('#js-employee-edit-reset').find('option').prop('checked', false);
            //
            $('#js-employee-edit-reset').select2MultiCheckboxes({
                templateSelection: function(selected, total) {
                    total = total - 1;
                    return "Selected " +( $.inArray('all', $('#js-employee-edit-reset').val()) !== -1 ? total : selected.length )+ " of " + total;
                }
            });
            //
            $('#js-employee-edit-reset').select2('val', resetPolicy.assignedEmployees.split(','));
            $('#js-approver-check-edit-reset').prop('checked', Boolean(Number(resetPolicy.for_admin)));
            $('#js-archive-check-edit-reset').prop('checked', Boolean(Number(resetPolicy.isArchived)));
            $('#js-include-check-edit-reset').prop('checked', Boolean(Number(resetPolicy.isIncluded)));
            //
            if(resetPolicy.accuralStartDate != null){
                $('.js-hire-date-edit-reset[value="customHireDate"]').prop('checked', true).trigger('change');
                $('#js-custom-date-edit-reset').val(moment(resetPolicy.accuralStartDate, 'MM-DD-YYYY').format('MM-DD-YYYY'));
            }
            //
            if(resetPolicy.resetDate != null){
                $('.js-policy-reset-date-edit-reset[value="policyDateCustom"]').prop('checked', true).trigger('change');
                $('#js-custom-reset-date-edit-reset').val(moment(resetPolicy.resetDate, 'MM-DD-YYYY').format('MM-DD-YYYY'));
            }
            //
            $('#js-minimum-applicable-hours-edit-reset').val(resetPolicy.minimumApplicableHours);
            $(`.js-minimum-applicable-time-edit-reset[value="${resetPolicy.minimumApplicableHoursType}"]`).prop('checked', true);
            //
            $('#js-accrual-method-edit-reset').select2();
            $('#js-accrual-method-edit-reset').select2('val', resetPolicy.accrualMethod == null ? 'days_per_year' : resetPolicy.accrualMethod);
            //
            $('#js-accrual-time-edit-reset').select2();
            $('#js-accrual-time-edit-reset').select2('val', resetPolicy.accrualTime == null ? 'start_of_period' : resetPolicy.accrualTime);
            //
            $('#js-carryover-cap-check-edit-reset').select2();
            $('#js-carryover-cap-check-edit-reset').select2('val', resetPolicy.carryoverCapCheck == 0 ? 'no' : 'yes');
            //
            $('#js-accrual-balance-edit-reset').select2();
            $('#js-accrual-balance-edit-reset').select2('val', resetPolicy.negativeBalanceAllowed == 0 ? 'no' : 'yes');
            //
            $('#js-accrual-frequency-edit-reset').select2();
            $('#js-accrual-frequency-edit-reset').select2('val', resetPolicy.accrualFrequency == null ? 'none' : resetPolicy.accrualFrequency);
            if(resetPolicy.accrualFrequency == 'custom') {
                $('#js-accrue-custom-frequency-edit-reset').val(resetPolicy.accrualFrequencyCustom);
            }
            //
            $('#js-accrual-rate-edit-reset').val(resetPolicy.accrualRate);
            $('#js-carryover-cap-edit-reset').val(resetPolicy.carryoverCap);
            $('#js-maximum-balance-edit-reset').val(resetPolicy.negativeBalanceAllowed);
            //
            if(resetPolicy.carryoverCapCheck == 1) $('.js-carryover-box-edit-reset').show();
            if(resetPolicy.negativeBalanceAllowed == 1) $('.js-negative-box-edit-reset').show();
            //
            $('#js-accrue-new-hire-edit-reset').val(resetPolicy.newHireDays);
            $('#js-newhire-prorate-edit-reset').val(resetPolicy.newhireProrate);
            //
            if(resetPolicy.types !== undefined){
                if($('#js-category-edit-reset option[value="'+( resetPolicy.types[0] )+'"]').text().toLowerCase().trim().match(/(fmla)/g) !== null){
                    $('.js-fmla-range-edit-reset[value="'+( resetPolicy.fmla_range )+'"]').prop('checked', true);
                }
            }
            // load plans
            loadAccrualPlans( 'edit-reset', resetPolicy.plans );
            //
            ml(false, 'policy');
        }


        /* EDIT PAGE START*/
        // Load Plans
        function loadPlans(data, type){
            type = type === undefined ? 'add' : type;
            var rows = '';
            if(plans == null || plans.length === 0) return;
            //
            plans.map(function(v, i){
                var slot = formatMinutes(v.format, v.default_timeslot, v.allowed_timeoff, false, true),
                format = v.format.split(':');
                rows += '<option value="'+( v.plan_id )+'">'+( getPlanTitle(v) )+'</option>';
            });
            //
            $('#js-plans-select-'+( type )+'').html(rows);
            $('#js-plans-select-'+( type )+'').select2();

            //
            if(data !== undefined){
                let selectedPlans = [];
                data.map(function(v){ 
                    selectedPlans.push(v.timeoff_plan_sid); 
                    makePlanRow(v.timeoff_plan_sid, 'edit');
                    //
                    var slot = formatMinutes( v.format, v.default_timeslot, v.allowed_timeoff, false, true );
                    if($('li[data-id="'+( v.timeoff_plan_sid )+'"]').find('.js-days-edit').length != 0 ) $('li[data-id="'+( v.timeoff_plan_sid )+'"]').find('.js-days-edit').val(slot.D);
                    if($('li[data-id="'+( v.timeoff_plan_sid )+'"]').find('.js-hours-edit').length != 0 ) $('li[data-id="'+( v.timeoff_plan_sid )+'"]').find('.js-hours-edit').val(slot.H);
                    if($('li[data-id="'+( v.timeoff_plan_sid )+'"]').find('.js-minutes-edit').length != 0 ) $('li[data-id="'+( v.timeoff_plan_sid )+'"]').find('.js-minutes-edit').val(slot.M);
                });
                $('#js-plans-select-edit').select2('val', selectedPlans);
            }
        }
        // Fetch the plans
        function fetchPlans(){
            $.post(baseURI+'handler', {
                action: 'get_company_plans',
                companySid: <?=$company_sid;?>
            }, function(resp){
                //
                if(resp.Status === false){
                    // alertify.alert('NOTICE', 'Please add plans before adding policies.');
                    return;
                }
                plans = resp.Data;
                loadPlans();
            });
        } 
        // Fetch the categories
        function fetchCategories(){
            $.post(baseURI+'handler', {
                action: 'get_company_types_list',
                companySid: <?=$company_sid;?>
            }, function(resp){
                //
                if(resp.Status === false){
                    console.log('No categories found.');
                    return;
                }
                categories = resp.Data;
                var rows = '';
                rows += '<option value="0" selected="true">[Please select a type]</option>';
                categories.map(function(v){
                    rows += `<option value="${v.type_id}">${v.type_name}</option>`;
                });
                $('#js-category-add, #js-category-edit').html(rows);
            });
        }
        

        // Page events
        // Page handlers
        
        $(".js-view-page-btn").click(function() { setHistory('view', baseURI+'policies/view', 'View Page'); });

        

        //
        $('#js-accrual-method-edit').change(function(e) {
            if($(this).val() == 'unlimited') $('.js-hider-edit').hide(0);
            else  $('.js-hider-edit').show(0);
            $('#js-accrual-frequency-edit').prop('disabled', false).removeClass('disabled');
            $('#js-accrual-frequency-edit').select2();

            //
            if($(this).val() == 'days_per_year'){
                $('.js-plan-type').text('day(s)');
                $('.js-plan-year').text('year(s)');
            } else if($(this).val() == 'hours_per_month'){
                $('.js-plan-type').text('hour(s)');
                $('.js-plan-year').text('month(s)');
                $('#js-accrual-frequency-edit').select2('val', 'none');
                $('#js-accrual-frequency-edit').select2('destroy');
                $('#js-accrual-frequency-edit').prop('disabled', true).addClass('disabled');
            }
        });
        // Pages
       
        function loadViewPage(){
            $('.js-page').fadeOut(0);
            // pOBJ['fetchCompanyPolicies']['records'] = [];
            // pOBJ['fetchCompanyPolicies']['page'] = 1;
            // pOBJ['fetchCompanyPolicies']['limit'] = 0;
            // pOBJ['fetchCompanyPolicies']['totalPages'] = 0;
            // pOBJ['fetchCompanyPolicies']['totalRecords'] = 0;
            // f
            $('#js-page-view').fadeIn(500);
        }
        function loadEditPage(sid){
            
            $('.js-page').fadeOut(0);
            $('#js-page-edit').fadeIn(500);
            $.post(baseURI+'handler', {
                action: 'get_single_company_policy',
                companySid: <?=$company_sid;?>,
                policySid: sid
            }, function(resp){
                if(resp.Status === false){
                    alertify.alert('ERROR!', resp.Response);
                    return;
                }
                showEditPage(resp.Data);
            });
        }
        // Set history
        function setHistory(page, pageURL, pageTitle, dataToBind, sid){
            if(page == lastPage) return;
            if(page === undefined) return;
            if(pageURL === undefined) return;
            if(dataToBind === undefined) dataToBind = {};
            if(sid === undefined) sid = null;
            if(pageTitle === undefined) pageTitle = '';
            window.history.pushState({fromPage: { title: lastPage }, toPage: { title: page, sid: sid }}, pageTitle, pageURL);
            switch(page){
                case 'add': loadAddPage(); break;
                case 'view': loadViewPage(); break;
                case 'edit': loadEditPage(sid); break;
            }
            lastPage = page;
            $('html,body').animate({ scrollTop: 0 }, 'slow');
        }
        //
        window.onpopstate = function(event) {
            //
            if(event.state == null){
                <?php if($page == 'view') { ?>
                <?php } else if($page == 'add') { ?> loadAddPage();
                <?php } else if($page == 'edit' && $policySid != null) { ?> loadEditPage(<?=$policySid;?>); <?php } ?>
                return;
            }
            //
            if(event.state.toPage === undefined || event.state.toPage === null) return;
            //
            switch (event.state.toPage.title) {
                case 'add': loadAddPage(); break;
                case 'view': loadViewPage(); break;
                case 'edit':
                case 'edit/': loadEditPage(event.state.toPage.sid); break;
            }
            lastPage = event.state.toPage.title;
        };

        //
        function fetchPoliciesList(){
            $.post(baseURI+'handler', {
                action: 'get_policy_list_by_company',
                companySid: <?=$company_sid;?>
            }, function(resp) {
                //
                if(resp.Status === false){
                    console.log('failed to load policy list');
                    return;
                }
                //
                policies = resp.Data;
                var tmp = policies.map(function(v){ return '<option value="'+( v.policy_id )+'">'+( ucwords(v.policy_title) )+'</option>'; });
                tmp = '<option value="0">[Select a policy]</option>' + tmp;
                $('#js-filter-policies').html(tmp);
                $('#js-filter-policies').select2();
            });
        }
        //
        <?php if($page == 'view') { ?>f
        <?php } else if($page == 'add') { ?> loadAddPage();
        <?php } else if($page == 'edit' && $policySid != null) { ?> loadEditPage(<?=$policySid;?>); <?php } ?>
        //
        <?php $this->load->view('timeoff/scripts/common'); ?>

        // For Add
        

        // For edit
        $(document).on('select2:selecting', '#js-plans-select-edit', function(e){ makePlanRow(e.params.args.data.id, 'edit'); });
        $(document).on('select2:unselecting', '#js-plans-select-edit', function(e){ removePlan(e.params.args.data.id, 'edit'); });
        $(document).on('click', '.js-remove-plan-edit', function(e){
            removePlan($(this).closest('li').data('id'), 'edit');
            $('#js-plans-select-edit').select2(
                'val',
                newValues($('#js-plans-select-edit').val(), $(this).closest('li').data('id'))
            );
        });
        
       
        
        //
        function getPlanFromArray(planId){
            let i = 0,
            l = plans.length;
            //
            for(i; i < l; i++){
                if(plans[i]['plan_id'] == planId) return plans[i];
            }
            return [];
        }

        //
        function callDrager(){
            $( "#js-data-area" ).sortable({
              placeholder: "ui-state-highlight"
            });
            $( "#js-data-area" ).disableSelection();

        }
        
        $( "#js-data-area" ).on( "sortstop", callSort);

        function callSort(){
            var 
            i = 1,
            s = {},
            l = $('#js-data-area').find('tr').length;
            for(i; i <= l; i++){
                s[$('#js-data-area').find('tr:nth-child('+(i)+')').data('id')] = i;
            }
            updateSortInDb(s);
        }

        function updateSortInDb(s){
            // ml(true, 'policy');
            $.post(baseURI+'handler', {
                sort: s,
                companySid: <?=$company_sid;?>,
                type: 'policies',
                action: 'update_sort_order'
            }, function(resp){
                ml(false, 'policy');
            });
        }

        //
        function getTypeNames( ids ){
            if(ids.length == 0) return 'Not Assigned';
            if(categories.length == 0) return 'Not Assigned';
            //
            var row = '';
            //
            categories.map(function(v){
                if(v.type_id == ids) row = v.type_name+', ';
                // if($.inArray(v.type_id, ids) !== -1){
                //     row += v.type_name+', ';
                // }
            });

            return row.substring(0, row.length - 2);
        }

        //
        $('.js-popover').popover({
            html: true,
            placement: 'right',
            trigger: 'hover'
        });

        
        //
        $('#js-category-edit').on('change', function(){
            var i = $('#js-category-edit option[value="'+( $(this).val() )+'"]').text().toLowerCase().trim();
            //
            if(i.match(/(fmla)/g) !== null){
                $('.js-fmla-range-wrap-edit').show();
            }else{
                $('.js-fmla-range-wrap-edit').hide(0);
                $('.js-fmla-range-edit').prop('checked', false);
                $('.js-fmla-range-edit[value="standard_year"]').prop('checked', true);
            }
        });

        //
        function getOptionValue(sid, type){
            if($('#js-category-'+( type )+' option[value="'+( sid )+'"]').text().toLowerCase().trim().match(/(fmla)/g) !== null) return true;
            return null;
        }

        

        

        

        


        //
        $('.jsTabPolicy').click(function(e){
            //
            e.preventDefault();
            //
            $('.jsTabPolicy').parent().removeClass('active')
            $(this).parent().addClass('active')
            //
            $('.jsTabPane').hide();
            $($(this).data('href')).show();
        });

        //
        $('.js-hire-date-edit').on('change', function(){
            if($(this).val() == 'hireDate'){
                $('.jsImplementDateBox').hide(0);
                $('#js-custom-date-edit').val('');
            } else{
                $('.jsImplementDateBox').show(0);
            }
        });
        
        
        //
        $('.js-policy-reset-date-edit').on('change', function(){
            if($(this).val() == 'policyDate'){
                $('.jsResetDateBox').hide(0);
                $('#js-custom-reset-date-edit').val('');
            } else{
                $('.jsResetDateBox').show(0);
            }
        });

        //
        $('#js-accrual-frequency-edit').on('change', function(){
            if($(this).val() == 'custom'){
                $('.jsCustomBoxEdit').show(0);
            } else $('.jsCustomBoxEdit').hide(0);
        });

        // Reset
        //
        $('.js-hire-date-edit-reset').on('change', function(){
            if($(this).val() == 'hireDate'){
                $('.jsImplementDateBoxReset').hide(0);
                $('#js-custom-date-edit-reset').val('');
            } else{
                $('.jsImplementDateBoxReset').show(0);
            }
        });
        
        //
        $('.js-policy-reset-date-edit-reset').on('change', function(){
            if($(this).val() == 'policyDate'){
                $('.jsResetDateBoxReset').hide(0);
                $('#js-custom-reset-date-edit-reset').val('');
            } else{
                $('.jsResetDateBoxReset').show(0);
            }
        });

        //
        $('#js-accrual-frequency-edit-reset').on('change', function(){
            if($(this).val() == 'custom'){
                $('.jsCustomBoxEditReset').show(0);
            } else $('.jsCustomBoxEditReset').hide(0);
        });


        // Add
        
        

    });

    


    

</script>
<style>#js-data-area tr{ cursor: move};</style>    
<style>.js-archive-btn > i{ color: #a94442 !important; }</style>
<style>.js-activate-btn > i{ color: #81b431  !important; }</style>