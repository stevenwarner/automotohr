<script type="text/javascript">
    var baseURI = "<?=base_url();?>timeoff/";
    $(function(){
        var employees = [],
        options = {
            yearly: <?= getTimeOffCompaniesForyearly($company_sid) ? 1 : 0; ?>
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
                cb: fetchCompanyPolicies
            }
        },
        record = [],
        intervalCatcher = null;
        console.log(options);

        /* FILTER START */
        fetchPoliciesList();
        // fetchPlans();
        fetchCategories();
        fetchEmployees();

        // Select2
        $('#js-filter-status').select2();
        $('#js-status-add').select2();
        $('#js-status-edit').select2();
        $('#js-accural-type-add').select2();
        $('#js-accrue-start-add').select2();
        $('#js-employee-add').select2MultiCheckboxes({
            templateSelection: function(selected, total) {
                return "Selected " +( $.inArray('all', $('#js-employee-add').val()) !== -1 ? total : selected.length )+ " of " + total;
            }
        })

        // Datepickers
        $('#js-filter-from-date').datepicker({
            dateFormat: 'mm-dd-yy',
            onSelect: function (v) { $('#js-filter-to-date').datepicker('option', 'minDate', v); }
        })
        $('#js-filter-to-date').datepicker({
            dateFormat: 'mm-dd-yy'
        }).datepicker('option', 'minDate', $('#js-filter-from-date').val());

        // Filter buttons
        $(document).on('click', '.js-apply-filter-btn', applyFilter);
        $(document).on('click', '.js-reset-filter-btn', resetFilter);
        /* FILTER END */

        /* TAB CHANGER START*/
        $(".js-tab").click(function() {
            fetchStatus = $(this).data('type');
            fetchCompanyPolicies();
        });
        /* TAB CHANGER END*/

        /* VIEW PAGE START */
        //
        function resetFilter(e){
            e.preventDefault();
            is_filter = false;
            $('#js-filter-policies').select2('val', 0);
            $('#js-filter-employee').select2('val', 0);
            $('#js-filter-from-date').val('');
            $('#js-filter-to-date').val('');
            $('#js-filter-status').select2('val', '-1');

            pOBJ['fetchCompanyPolicies']['records'] = [];
            pOBJ['fetchCompanyPolicies']['totalPages'] =
            pOBJ['fetchCompanyPolicies']['totalRecords'] =
            pOBJ['fetchCompanyPolicies']['limit'] = 0;
            pOBJ['fetchCompanyPolicies']['page'] = 1;

            fetchCompanyPolicies();
        }
        //
        function applyFilter(e){
            loader();
            e.preventDefault();
            is_filter = true;
            pOBJ['fetchCompanyPolicies']['records'] = [];
            pOBJ['fetchCompanyPolicies']['totalPages'] =
            pOBJ['fetchCompanyPolicies']['totalRecords'] =
            pOBJ['fetchCompanyPolicies']['limit'] = 0;
            pOBJ['fetchCompanyPolicies']['page'] = 1;

            fetchCompanyPolicies();
        }
        // Fetch plans
        function fetchCompanyPolicies(){
            if(xhr != null) return;
            loader('show');
            $('.js-error-row').remove();
            var megaOBJ = {};
            megaOBJ.page = pOBJ['fetchCompanyPolicies']['page'];
            megaOBJ.action = 'get_policies_by_company';
            megaOBJ.companySid = <?=$company_sid;?>;
            megaOBJ.status = is_filter ? $('#js-filter-status').val() : '';
            megaOBJ.policySid = is_filter ? $('#js-filter-policies').val() : '';
            megaOBJ.endDate = is_filter ? $('#js-filter-to-date').val().trim() : '';
            megaOBJ.startDate = is_filter ? $('#js-filter-from-date').val().trim() : '';
            megaOBJ.employeeSid = is_filter ? $('#js-filter-employee').val() : '';
            megaOBJ.fetchType = fetchStatus;

            xhr = $.post(baseURI+'handler', megaOBJ, function(resp) {
                xhr = null;
                //
                if(resp.Status === false && pOBJ['fetchCompanyPolicies']['page'] == 1){
                    $('.js-ip-pagination').html('');
                    loader('hide');
                    $('#js-data-area').html('<tr class="js-error-row"><td colspan="'+( $('.js-table-head').find('th').length )+'"><p class="alert alert-info text-center">'+( resp.Response )+'</p></td></tr>')
                }
                //
                if(resp.Status === false){
                    loader('hide');
                    $('.js-ip-pagination').html('');
                    return;
                }

                pOBJ['fetchCompanyPolicies']['records'] = resp.Data;
                if(pOBJ['fetchCompanyPolicies']['page'] == 1) {
                    pOBJ['fetchCompanyPolicies']['limit'] = resp.Limit;
                    pOBJ['fetchCompanyPolicies']['totalPages'] = resp.TotalPages;
                    pOBJ['fetchCompanyPolicies']['totalRecords'] = resp.TotalRecords;
                }
                //
                setTable(resp);
            });
        }
        //
        function setTable(resp){
            oldState = {};
            var title = fetchStatus != 'active' ? 'Activate Policy' : 'Deactivate Policy',
            icon = fetchStatus != 'active' ? 'fa-check-square-o' : 'fa-archive',
            cl = fetchStatus != 'active' ? 'js-activate-btn' : 'js-archive-btn',
            rows = '';
            if(resp.Data.length == 0) return;
            //
            $.each(resp.Data, function(i, v){
                oldState[v.policy_id] = i;
                rows += '<tr data-id="'+( v.policy_id )+'">';
                rows += '    <td>';
                rows += '        <div class="text">';
                rows += '            <p>'+( ucwords(v.policy_title) )+'</p>';
                rows += '        </div>';
                rows += '    </td>';
                rows += '    <td>';
                rows += '        <div class="text">';
                rows += '            <p class="js-type-popovers"><abbr>'+( getTypeNames(v.types) )+' </abbr></p>';
                rows += '        </div>';
                rows += '    </td>';
                rows += '    <td>';
                rows += '        <div class="text">';
                rows += '            <p>'+( v.accural_start_date == null ? 'Hire Date' : moment(v.accural_start_date, 'YYYY-MM-DD').format(timeoffDateFormat) )+'</p>';
                rows += '        </div>';
                rows += '    </td>';
                rows += '    <td>';
                rows += '        <div class="text">';
                rows += '            <p class="cs-status-text '+( v.carryover_cap_check == 1 ? 'cs-success' : 'cs-danger' )+'">'+( v.carryover_cap_check == 1 ? 'Enabled' : 'Disabled' )+'</p>';
                rows += '        </div>';
                rows += '    </td>';
                rows += '    <td scope="row" style="vertical-align: middle;">';
                rows += '        <div class="employee-info">';
                rows += '            <figure>';
                rows += '                <img src="'+( getImageURL(v.img) )+'" class="img-circle emp-image" />';
                rows += '            </figure>';
                rows += '            <div class="text">';
                rows += '                <h4>'+( v.full_name )+'</h4>';
                rows += '                <p>'+( remakeEmployeeName(v, false) )+'</p>';
                rows += '                <p><a href="<?=base_url('employee_profile');?>/'+( v.employee_id )+'" target="_blank">Id: '+( getEmployeeId(v.employee_id, v.employee_number) )+'</a></p>';
                rows += '            </div>';
                rows += '        </div>';
                rows += '    </td>';
                rows += '    <td>';
                rows += '        <div class="text">';
                rows += '            <p>'+( moment(v.created_at, 'YYYY-MM-DD').format(timeoffDateFormat) )+'</p>';
                rows += '        </div>';
                rows += '    </td>';
                // rows += '    <td>';
                // rows += '        <div class="text cs-status-text">';
                // rows += '            <p class="'+( v.status == 1 ? 'cs-success' : 'cs-danger' )+'">'+( v.status == 1 ? 'Active' : 'In-active' )+'</p>';
                // rows += '        </div>';
                // rows += '    </td>';
                rows += '    <td>';
                rows += '        <div class="action-employee">';
                rows += '            <a href="javascript:void(0)" data-toggle="tooltip" title="Edit Policy" class="action-edit js-edit-row-btn"><i class="fa fa-pencil-square-o fa-fw icon_blue"></i></a>';
                rows += '            <a href="javascript:void(0)" data-toggle="tooltip" title="'+( title )+'" class="action-activate custom-tooltip '+( cl )+'"><i class="fa '+( icon )+' fa-fw"></i></a>';
                rows += '        </div>';
                rows += '    </td>';
                rows += '</tr>';
            });

            //
            load_pagination(
                pOBJ['fetchCompanyPolicies']['limit'],
                5,
                $('.js-ip-pagination'),
                'fetchCompanyPolicies'
            );

            $('#js-data-area').html(rows);
            loader('hide');

            //
            $('.js-type-popover').popover({
                html: true,
                trigger: 'hover'
            });

            callDrager();
        }
        //
        $(document).on('click', '.js-archive-btn', function(e){
            e.preventDefault();
            var _this = $(this);
            alertify.confirm('Do you really want to archive this Policy?', function(){
                var megaOBJ = {};
                megaOBJ.companySid = <?=$company_sid;?>;
                megaOBJ.action = 'archive_company_policy';
                megaOBJ.policySid = _this.closest('tr').data('id');
                megaOBJ.employeeSid = <?=$employer_sid;?>;
                //
                loader('show');
                $.post(baseURI+'handler', megaOBJ, function(resp) {
                    //
                    loader('hide');
                    if(resp.Status === false){
                        alertify.alert('ERROR!', resp.Response, function(){ return; });
                        return;
                    }
                    //
                    alertify.alert('SUCCESS!', resp.Response, function(){ loadViewPage(); });
                });
            }).set('labels',{ok:'Yes', cancel:'No'}).set('label', 'WARNING!');
        });
        //
        $(document).on('click', '.js-activate-btn', function(e){
            e.preventDefault();
            var _this = $(this);
            alertify.confirm('Do you really want to activate this Policy?', function(){
                var megaOBJ = {};
                megaOBJ.companySid = <?=$company_sid;?>;
                megaOBJ.action = 'activate_company_policy';
                megaOBJ.policySid = _this.closest('tr').data('id');
                megaOBJ.employeeSid = <?=$employer_sid;?>;
                //
                loader('show');
                $.post(baseURI+'handler', megaOBJ, function(resp) {
                    //
                    loader('hide');
                    if(resp.Status === false){
                        alertify.alert('ERROR!', resp.Response, function(){ return; });
                        return;
                    }
                    //
                    alertify.alert('SUCCESS!', resp.Response, function(){ loadViewPage(); });
                });
            }).set('labels',{ok:'Yes', cancel:'No'}).set('label', 'WARNING!');
        });
        //
        $(document).on('click', '.js-edit-row-btn', function(e){
            e.preventDefault();
            //
            loader('show');
            loadEditPage($(this).closest('tr').data('id'));
            setHistory('edit/', baseURI+'policies/edit/'+($(this).closest('tr').data('id'))+'', 'Edit Page', {}, $(this).closest('tr').data('id'));
        });

        /* VIEW PAGE END */

        /* ADD PAGE START*/
        $('#js-custom-date-add').datepicker({ dateFormat: 'mm-dd-yy' });
        // $('#js-policy-start-date-add').datepicker({ dateFormat: 'mm-dd-yy' });
        $('#js-custom-reset-date-add').datepicker({ dateFormat: 'mm-dd-yy' });
        //
        $('#js-unlimited-policy-check-add').click(function(){
            if($(this).prop('checked') === true) $('#js-plan-box-add').hide();
            else $('#js-plan-box-add').show();
        });
        //
        $('#js-save-add-btn').click(function(e){
            e.preventDefault();
            var megaOBJ = {},
            formats = <?=json_encode($timeOffFormat);?>;
            megaOBJ.companySid = <?=$company_sid;?>;
            megaOBJ.formatSid = <?=$timeoff_format_sid;?>;
            megaOBJ.employeeSid = <?=$employer_sid;?>;
            megaOBJ.policy = $('#js-policy-title-add').val().trim();
            megaOBJ.assignedEmployees = $('#js-employee-add').val();
            megaOBJ.action = 'add_company_policy';
            megaOBJ.status = 1;
            // megaOBJ.status = $('#js-status-add').val();
            megaOBJ.sortOrder = $('#js-sort-order-add').val();
            megaOBJ.archiveCheck = Number($('#js-archive-check-add').prop('checked'));
            megaOBJ.approverCheck = Number($('#js-approver-check-add').prop('checked'));
            megaOBJ.isIncluded = Number($('#js-include-check-add').prop('checked'));
            // megaOBJ.unlimitedPolicyCheck = Number($('#js-unlimited-policy-check-add').prop('checked'));
            megaOBJ.isArchived = Number($('#js-archive-check-add').prop('checked'));
            // megaOBJ.isUse = Number($('#js-lose-add').prop('checked'));
            // Get categories
            megaOBJ.types = $('#js-category-add').val();

            // Acccural settings
            // megaOBJ.accuralType = $('#js-accural-type-add').val();
            megaOBJ.allowedDays = megaOBJ.allowedHours = megaOBJ.allowedMinutes = 0;

            // Validation
            // Check if title is empty
            if(megaOBJ.policy == ''){
                alertify.alert('ERROR!', 'Please add a policy title.');
                return;
            }
            //
            megaOBJ.newHireDays = parseInt($('#js-accrue-new-hire-add').val().trim());
            if(isNaN(megaOBJ.newHireDays)) megaOBJ.newHireDays = 0;


            // Check and assign employees
            if(megaOBJ.assignedEmployees == null) megaOBJ.assignedEmployees = 'all';
            else if (megaOBJ.assignedEmployees.indexOf('all') !== -1)  megaOBJ.assignedEmployees = 'all';
            else if ((megaOBJ.assignedEmployees.length == employees.length) || megaOBJ.assignedEmployees.length - 1 == employees.length)  megaOBJ.assignedEmployees = 'all';
            else megaOBJ.assignedEmployees = megaOBJ.assignedEmployees.join(',');
            // Accural settigs
            // if(megaOBJ.accuralType == 0){
            //     alertify.alert('ERROR!', 'Please select a accural type.');
            //     return;
            // }
            //
            megaOBJ.accuralDateType = 'hireDate';
            megaOBJ.accuralStartDate = null;
            //
            if($('.js-hire-date-add:checked').val() == 'customHireDate'){
                if($('#js-custom-date-add').val() == ''){
                    alertify.alert('ERROR!', 'Please select accural start date.');
                    return;
                }
                megaOBJ.accuralDateType = 'custom';
                megaOBJ.accuralStartDate = $('#js-custom-date-add').val();
            }
            // FMLA Range
            if(getOptionValue(megaOBJ.types, 'add') !== null){
                megaOBJ.fmlaRange = $('.js-fmla-range-add:checked').val();
                if(!megaOBJ.fmlaRange){
                    alertify.alert('Please select a FMLA range.');
                    return;
                }
            }
            // Set up phase 3 checks
            // Policy Activation Date
            // megaOBJ.policyStartDate = $('#js-policy-start-date-add').val();
            // Minimum applicable hours
            megaOBJ.minimumApplicableHours = $('#js-minimum-applicable-hours-add').val().trim();
            // Accrual Method
            megaOBJ.accrualMethod = $('#js-accrual-method-add').val();
            // Accrual Rate
            megaOBJ.accrualRate = $('#js-accrual-rate-add').val().trim();
            // Accrual Time
            megaOBJ.accrualTime = $('#js-accrual-time-add').val();
            // Reset Date
            megaOBJ.resetDate = 0;
            if($('.js-policy-reset-date:checked').val() == 'policyDateCustom'){
                megaOBJ.resetDate = $('#js-custom-reset-date-add').val();
            }
            // Accrual Frequency
            megaOBJ.accrualFrequency = $('#js-accrual-frequency-add').val();
            // Carryover Cap Check
            megaOBJ.carryoverCapCheck = $('#js-carryover-cap-check-add').val();
            // Carryover Cap
            megaOBJ.carryoverCap = $('#js-carryover-cap-add').val().trim();
            // Negative Balance Ccheckc
            megaOBJ.negativeBalance = $('#js-accrual-balance-add').val();
            // Negative Balance
            megaOBJ.negativeBalanceAllowed = $('#js-maximum-balance-add').val();
            // Newhire Prorate
            megaOBJ.newhireProrate = $('#js-newhire-prorate-add').val();
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
            megaOBJ.plans = getAccrualPlans('add');
            //
            if( typeof(megaOBJ.plans) === 'boolean') return;
            //
            loader('show');
            $.post(baseURI+'handler', megaOBJ, function(resp) {

                if(resp.Status === false){
                    loader('hide');
                    alertify.alert('ERROR!', resp.Response, function(){ return; });
                    return;
                }

                alertify.alert('SUCCESS!', resp.Response, function(){ setHistory('view', baseURI+'policies/view', 'View Page'); });
            });
        });
        /* ADD PAGE END*/

        /* EDIT PAGE END*/
        $('#js-custom-date-edit').datepicker({ dateFormat: 'mm-dd-yy' });
        // $('#js-policy-start-date-edit').datepicker({ dateFormat: 'mm-dd-yy' });
        $('#js-custom-reset-date-edit').datepicker({ dateFormat: 'mm-dd-yy' });
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
            //
            if( typeof(megaOBJ.plans) === 'boolean') return;
            //
            loader('show');
            $.post(baseURI+'handler', megaOBJ, function(resp) {

                loader('hide');
                if(resp.Status === false){
                    alertify.alert('ERROR!', resp.Response, function(){ return; });
                    return;
                }

                alertify.alert('SUCCESS!', resp.Response, function(){ setHistory('view', baseURI+'policies/view', 'View Page'); });
            });
        });
        //
        function showEditPage(policy){
            lastPage = 'edit';
            //
            loader('hide');
            $('#js-policy-id-edit').val(policy.policy_id);
            //
            $('#js-policy-title-edit').val(ucwords(policy.policy_title));

            $('#js-employee-edit').find('option').prop('checked', false);

            $('#js-employee-edit').select2MultiCheckboxes({
                templateSelection: function(selected, total) {
                    $('#js-employee-count-edit').text($('#js-employee-edit').val() == 'all' ? total : selected.length);
                    return "Selected " +( $.inArray('all', $('#js-employee-edit').val()) !== -1 ? total : selected.length )+ " of " + total;
                }
            });

            $('#js-category-edit').select2();
            $('#js-category-edit').select2('val', policy.types == undefined ? 0 : policy.types[0]);

            $('#js-employee-edit').select2('val', policy.assigned_employees.split(','));
            $('#js-sort-order-edit').val(policy.sort_order);
            $('#js-approver-check-edit').prop('checked', Boolean(Number(policy.for_admin)));
            $('#js-archive-check-edit').prop('checked', Boolean(Number(policy.is_archived)));
            $('#js-include-check-edit').prop('checked', Boolean(Number(policy.is_included)));
            $('.js-hire-date-edit[value="hireDate"]').prop('checked', true);
            $('#js-accrue-new-hire-edit').val(policy.new_hire_days);
            $('#js-custom-date-edit').val('');
            //
            $('#js-accrual-method-edit').select2();
            $('#js-accrual-method-edit').select2('val', policy.accrual_method == null ? 'days_per_year' : policy.accrual_method);
            //
            if(options.yearly === 1) {
                $('#js-accrual-frequency-edit option[value="none"]').remove();
                if(policy.accrual_frequency == 'none') policy.accrual_frequency = 'yearly';
            }
            //
            $('#js-accrual-frequency-edit').select2();
            $('#js-accrual-frequency-edit').select2('val', policy.accrual_frequency == null ? ( (options.yearly === 1) ? 'yearly' : 'none') : policy.accrual_frequency);
            //
            $('#js-accrual-balance-edit').select2();
            $('#js-accrual-balance-edit').select2('val', policy.allow_negative_balance == 0 ? 'no' : 'yes');
            //
            $('#js-accrual-time-edit').select2();
            $('#js-accrual-time-edit').select2('val', policy.accrual_time == null ? 'start_of_period' : policy.accrual_time);
            //
            $('#js-minimum-applicable-hours-edit').val(policy.minimum_applicable_hours);
            $('#js-accrual-rate-edit').val(policy.accrual_rate);
            $('#js-maximum-balance-edit').val(policy.negative_balance);
            $('#js-newhire-prorate-edit').val(policy.newhire_prorate);
            //
            $('#js-carryover-cap-check-edit').select2();
            $('#js-carryover-cap-check-edit').select2('val', policy.carryover_cap_check == 0 ? 'no' : 'yes');
            //
            if(policy.accural_start_date != null){
                $('.js-hire-date-edit[value="customHireDate"]').prop('checked', true);
                $('#js-custom-date-edit').val(moment(policy.accural_start_date, 'YYYY-MM-DD').format('MM-DD-YYYY'));
            }
            if(policy.reset_date != null){
                $('.js-policy-reset-date-edit[value="policyDateCustom"]').prop('checked', true);
                $('#js-custom-reset-date-edit').val(moment(policy.reset_date, 'YYYY-MM-DD').format('MM-DD-YYYY'));
            }
            //
            $('#js-carryover-cap-edit').val(policy.carryover_cap);
            $('#js-plan-box-edit').find('ul').html('');
            $('#js-plan-box-edit').hide();
            //
            if(policy.types !== undefined){
                if($('#js-category-edit option[value="'+( policy.types[0] )+'"]').text().toLowerCase().trim().match(/(fmla)/g) !== null){
                    $('.js-fmla-range-edit[value="'+( policy.fmla_range )+'"]').prop('checked', true);
                }
            }
            //
            $('.js-carryover-box-edit').hide();
            $('.js-negative-box-edit').hide();
            if(policy.carryover_cap_check == 1) $('.js-carryover-box-edit').show();
            if(policy.allow_negative_balance == 1) $('.js-negative-box-edit').show();
            //
            $('#js-plan-area-edit').html('');
            // load plans
            loadAccrualPlans( 'edit', policy.plans );
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
        // Employees
        function fetchEmployees(){
            $.post(baseURI+'handler', {
                action: 'get_company_employees',
                companySid: <?=$company_sid;?>
            }, function(resp){
                    if(resp.Status === false){
                        console.log('Failed to load employees.');
                        return;
                    }
                    employees = resp.Data;
                    var rows = '';
                    rows += '<option value="all">All</option>';
                    employees.map(function(v){
                        rows += '<option value="'+( v.user_id )+'">'+( remakeEmployeeName( v ) )+'</option>';
                    });
                    $('#js-employee-add').html(rows);
                    $('#js-employee-add').select2MultiCheckboxes({
                        templateSelection: function(selected, total) {
                            return "Selected " +( $.inArray('all', $('#js-employee-add').val()) !== -1 ? total : selected.length )+ " of " + total;
                        }
                    });

                    $('#js-employee-edit').html(rows);
                    $('#js-filter-employee').html(rows);
                    $('#js-filter-employee').select2();
            });
        }

        // Page events
        // Page handlers
        $("#js-add-policy-btn").click(function() { setHistory('add', baseURI+'policies/add', 'Add Page'); });
        $(".js-view-page-btn").click(function() { setHistory('view', baseURI+'policies/view', 'View Page'); });
        $('#js-accrual-method-add').change(function(e) {
            if($(this).val() == 'unlimited') $('.js-hider-add').hide(0);
            else  $('.js-hider-add').show(0);
            //
            if($(this).val() == 'days_per_year'){
                $('.js-plan-type').text('day(s)');
                $('.js-plan-year').text('year(s)');
            } else if($(this).val() == 'hours_per_month'){
                $('.js-plan-type').text('hour(s)');
                $('.js-plan-year').text('month(s)');
            }
        });
        $('#js-accrual-method-edit').change(function(e) {
            if($(this).val() == 'unlimited') $('.js-hider-edit').hide(0);
            else  $('.js-hider-edit').show(0);

            //
            if($(this).val() == 'days_per_year'){
                $('.js-plan-type').text('day(s)');
                $('.js-plan-year').text('year(s)');
            } else if($(this).val() == 'hours_per_month'){
                $('.js-plan-type').text('hour(s)');
                $('.js-plan-year').text('month(s)');
            }
        });
        // Pages
        function loadAddPage(){
            $('.js-page').fadeOut(0);
            // Flush
            $('#js-policy-title-add').val();
            $('#js-employee-add').select2('val', 0);
            $('#js-employee-count-add').text(0);
            // $('#js-status-add').select2('val', 1);
            $('#js-approver-check-add').prop('checked', false);
            // $('#js-unlimited-policy-check-add').prop('checked', false);
            $('#js-archive-check-add').prop('checked', false);
            // $('#js-lose-add').prop('checked', false);
            // $('#js-accural-type-add').select2();
            // $('#js-accural-type-add').select2('val', 0);
            $('#js-accrual-method-add').select2({minimumResultsForSearch: -1});
            $('#js-accrual-method-add').select2('val', 'days_per_year');
            //
            if(options.yearly === 1) $('#js-accrual-frequency-add option[value="none"]').remove();
            //
            $('#js-accrual-frequency-add').select2({minimumResultsForSearch: -1});
            $('#js-accrual-frequency-add').select2('val', 'yearly');
            //
            $('#js-accrual-balance-add').select2({minimumResultsForSearch: -1});
            $('#js-accrual-balance-add').select2('val', 'no');
            //
            $('#js-accrual-time-add').select2({minimumResultsForSearch: -1});
            $('#js-accrual-time-add').select2('val', 'start_of_period');
            //
            $('#js-carryover-cap-check-add').select2({minimumResultsForSearch: -1});
            $('#js-carryover-cap-check-add').select2('val', 'yes');
            //
            $('.js-hire-date-add[value="hireDate"]').prop('checked', true);
            $('#js-custom-date-add').val('');
            $('#js-sort-order-add').val(policies.length + 1);
            // $('#js-policy-start-date-add').val();
            //
            $('#js-category-add').select2();
            //
            setTimeout(function(){
                $('#js-category-add').select2('val', 0);
            }, 1000);
            //
            $('#js-plan-box-add').find('ul').html('');
            $('#js-plan-box-add').show();
            // loadPlans();
            $('#js-page-add').fadeIn(500);
            //
            $('#js-plan-area-add').html('');
            //
            loader('hide');
        }
        function loadViewPage(){
            $('.js-page').fadeOut(0);
            pOBJ['fetchCompanyPolicies']['records'] = [];
            pOBJ['fetchCompanyPolicies']['page'] = 1;
            pOBJ['fetchCompanyPolicies']['limit'] = 0;
            pOBJ['fetchCompanyPolicies']['totalPages'] = 0;
            pOBJ['fetchCompanyPolicies']['totalRecords'] = 0;
            fetchCompanyPolicies();
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
                <?php if($page == 'view') { ?>fetchCompanyPolicies();
                <?php } else if($page == 'add') { ?> loadAddPage();
                <?php } else if($page == 'edit' && $policySid != null) { ?> loadEditPage(<?=$policySid;?>); <?php } ?>
            }
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
        <?php if($page == 'view') { ?>fetchCompanyPolicies();
        <?php } else if($page == 'add') { ?> loadAddPage();
        <?php } else if($page == 'edit' && $policySid != null) { ?> loadEditPage(<?=$policySid;?>); <?php } ?>
        //
        <?php $this->load->view('timeoff/scripts/common'); ?>

        // For Add
        $(document).on('select2:selecting', '#js-plans-select-add', function(e){ makePlanRow(e.params.args.data.id, 'add'); });
        $(document).on('select2:unselecting', '#js-plans-select-add', function(e){ removePlan(e.params.args.data.id, 'add'); });
        $(document).on('click', '.js-remove-plan-add', function(e){
           removePlan($(this).closest('li').data('id'), 'add');
            $('#js-plans-select-add').select2(
                'val',
                newValues($('#js-plans-select-add').val(), $(this).closest('li').data('id'))
            );
        });

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
        function makePlanRow( planId, type ){  
            var plan = getPlanFromArray(planId);
            if(plan.length == 0) {
                alertify.alert('ERROR!', 'Plan with Id '+(planId)+' not found.');
                return;
            }
            var rows = '';

            var slot = formatMinutes(plan.format, plan.default_timeslot, plan.allowed_timeoff, false, true),
            format = plan.format.split(':');
            //
            rows += '<li data-id="'+( plan.plan_id )+'" data-timeslot="'+( plan.default_timeslot )+'" data-format="'+( plan.format )+'" class="js-plan-row-'+(type)+'">';
            rows += '    <div class="timeline-content margin-right">';
            rows += '        <h4 class="timeline-field-title">'+( getPlanTitle(plan) )+' <span title="Remove Plan" class="pull-right text-danger js-remove-plan-'+(type)+'"><i class="fa fa-close"></i></span></h4>';
            rows += '        <div class="row">';
            if(format.indexOf('D') != -1){
                rows += '            <div class="col-lg-6 col-sm-6 col-xs-12">';
                rows += '                <div class="input-group pto-time-off-margin-custom">';
                rows += '                    <input type="text" class="form-control js-days-'+( type )+'" value="'+( slot.D )+'" />';
                rows += '                    <span class="input-group-addon">Days</span>';
                rows += '                </div>';
                rows += '            </div>';
            }
            if(format.indexOf('H') != -1){
                rows += '            <div class="col-lg-6 col-sm-6 col-xs-12">';
                rows += '                <div class="input-group pto-time-off-margin-custom">';
                rows += '                    <input type="text" class="form-control js-hours-'+( type )+'" value="'+( slot.H )+'" />';
                rows += '                    <span class="input-group-addon">Hours</span>';
                rows += '                </div>';
                rows += '            </div>';
            }
            if(format.indexOf('M') != -1){
                rows += '            <div class="col-lg-6 col-sm-6 col-xs-12">';
                rows += '                <div class="input-group pto-time-off-margin-custom">';
                rows += '                    <input type="text" class="form-control js-minutes-'+( type )+'" value="'+( slot.M )+'" />';
                rows += '                    <span class="input-group-addon">Minutes</span>';
                rows += '                </div>';
                rows += '            </div>';
            }
            rows += '        </div>';
            rows += '    </div>';
            rows += '</li>';
            $('#js-plan-area-'+( type )+'').append(rows);
        }
        //
        function removePlan( planId, type){
            $('li[data-id="'+( planId )+'"]').remove();
        }
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
            // loader('show');
            $.post(baseURI+'handler', {
                sort: s,
                companySid: <?=$company_sid;?>,
                type: 'policies',
                action: 'update_sort_order'
            }, function(resp){
                loader('hide');
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
        $('#js-category-add').on('change', function(){
            var i = $('#js-category-add option[value="'+( $(this).val() )+'"]').text().toLowerCase().trim();
            //
            if(i.match(/(fmla)/g) !== null){
                $('.js-fmla-range-wrap-add').show();
            }else{
                $('.js-fmla-range-wrap-add').hide(0);
                $('.js-fmla-range-add').prop('checked', false);
                $('.js-fmla-range-add[value="standard_year"]').prop('checked', true);
            }
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

        //
        $('#js-carryover-cap-check-add').change(function(){
            $('.js-carryover-box-add').find('input').val(0);
            if($(this).val() === 'no'){
                $('.js-carryover-box-add').hide();
            } else{
                $('.js-carryover-box-add').show();
            }
        });

        //
        $('#js-accrual-balance-add').change(function(){
            $('.js-accrual-balance-add').find('input').val(0);
            if($(this).val() === 'no'){
                $('.js-negative-box-add').hide();
            } else{
                $('.js-negative-box-add').show();
            }
        });

        //
        $('.js-plan-btn-add,.js-plan-btn-edit').click(function(e) {
            //
            e.preventDefault();
            //
            loadAccrualPlans( 
                $(this).data('type')
            );
        });
        //
        $(document).on('click', '.js-plan-remove-btn', function(e) {
            //
            e.preventDefault();
            //
            if(
                $(this).parent().find('.js-pt').val().trim() != '' ||
                $(this).parent().find('.js-py').val().trim() != '') {
                alertify.confirm('Do you really want to delete this accrual plan?', () =>{
                    $(this).parent().remove();
                }).set('labels', {
                    ok: 'Yes',
                    cancel: 'No'
                });
            } else $(this).parent().remove();
        });

        //
        function loadAccrualPlans(
            type,
            plans
        ){
            //
            type = type === undefined ? 'add' : type;
            //
            var 
            rows = '',
            dt = 'day(s)',
            yt = 'year(s)';
            //
            if( $('#js-accrual-method-'+( type )+'').val() == 'hours_per_month'){
                dt = 'hour(s)';
                yt = 'month(s)';
            }

            // For edit view
            if( plans !== undefined && plans.length !== 0 ){
                plans.map(function(plan){
                    rows += '<div class="js-plan-row-'+( type )+'" style="padding: 20px 0 10px;">';
                    rows += '    <span>Allow</span>';
                    rows += '    <div class="form-group form-group-custom form-group-custom-settings">';
                    rows += '        <input class="form-control form-control-custom js-pt js-plan-type-'+( type )+'" value="'+( plan.accrual_rate )+'" />';
                    rows += '    </div><span> extra <span class="js-plan-type">'+( dt )+'</span> on</span>';
                    rows += '    <div class="form-group form-group-custom form-group-custom-settings">';
                    rows += '        <input class="form-control form-control-custom js-py js-plan-year-'+( type )+'" value="'+( plan.plan_title )+'" />';
                    rows += '    </div><span> <span class="js-plan-year">'+( yt )+'</span>.</span>';
                    rows += '    <span class="label label-danger js-plan-remove-btn" title="Remove accrual plan"><i class="fa fa-close"></i></span>';
                    rows += '</div>';
                });
                //
                $('#js-plan-area-'+( type )+'').html(rows);
                //
                return;
            }
            // For add view
            rows += '<div class="js-plan-row-'+( type )+'" style="padding: 20px 0 10px;">';
            rows += '    <span>Allow</span>';
            rows += '    <div class="form-group form-group-custom form-group-custom-settings">';
            rows += '        <input class="form-control form-control-custom js-pt js-plan-type-'+( type )+'" />';
            rows += '    </div><span> extra <span class="js-plan-type">'+( dt )+'</span> on</span>';
            rows += '    <div class="form-group form-group-custom form-group-custom-settings">';
            rows += '        <input class="form-control form-control-custom js-py js-plan-year-'+( type )+'" />';
            rows += '    </div><span> <span class="js-plan-year">'+( yt )+'</span>.</span>';
            rows += '    <span class="label label-danger js-plan-remove-btn" title="Remove accrual plan"><i class="fa fa-close"></i></span>';
            rows += '</div>';

            console.log(type);
            //
            $('#js-plan-area-'+( type )+'').append(rows);
            //
        }

        //
        function getAccrualPlans(
            type
        ){
            //
            var r = [];
            //
            var is_error = false;
            //
            if($('#js-accrual-method-'+( type )+'').val() == 'unlimited') return r;
            //
            if($('.js-plan-row-'+( type )+'').length === 0) return r;
            //
            $('.js-plan-row-'+( type )+'').map(function(i) {
                //
                if( is_error ) return;
                //
                var l = {
                    accrualType: $(this).find('.js-py').val().trim(),
                    accrualRate: $(this).find('.js-pt').val().trim()
                };
                //
                if( (l.accrualType == 0 || l.accrualType == '' ) && (l.accrualRate == 0 || l.accrualRate == '' ) ) return;
                //
                if( l.accrualType == 0 || l.accrualType == '' ) is_error = true;
                if( l.accrualRate == 0 || l.accrualRate == '' ) is_error = true;
                //
                if(is_error) {
                    //
                    alertify.alert(
                        'WARNING!',
                        'Accrual fields are mendatory for '+( ++i )+' plan.'
                    );
                    //
                    return;
                }
                //
                r.push(l);
            });
            //
            return is_error ? is_error : r;
        }
    })

</script>
<style>#js-data-area tr{ cursor: move};</style>    
<style>.js-archive-btn > i{ color: #a94442 !important; }</style>
<style>.js-activate-btn > i{ color: #81b431  !important; }</style>