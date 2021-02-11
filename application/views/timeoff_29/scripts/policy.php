<script type="text/javascript">
    var baseURI = "<?=base_url();?>timeoff/";
    $(function(){
        var employees = [],
        is_filter = false,
        oldState = {},
        lastPage = "<?=$page;?>",
        default_slot = 0,
        fetchStatus = 'active',
        policies = [],
        plans = [],
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

        /* FILTER START */
        fetchPoliciesList();
        fetchPlans();
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
            var title = fetchStatus != 'active' ? 'Activate Policy' : 'Archive Policy',
            icon = fetchStatus != 'active' ? 'fa-check-square-o' : 'fa-archive',
            cl = fetchStatus != 'active' ? 'js-activate-btn' : 'js-archive-btn',
            rows = '';
            if(resp.Data.length == 0) return;
            //
            $.each(resp.Data, function(i, v){
                oldState[v.policy_id] = i;
                rows += '<tr data-id="'+( v.policy_id )+'">';
                rows += '    <td scope="row" style="vertical-align: middle;">';
                rows += '        <div class="employee-info">';
                rows += '            <figure>';
                rows += '                <img src="'+( getImageURL(v.img) )+'" class="img-circle emp-image" />';
                rows += '            </figure>';
                rows += '            <div class="text">';
                rows += '                <h4>'+( v.full_name )+'</h4>';
                rows += '                <p><a href="<?=base_url('employee_profile');?>/'+( v.employee_id )+'" target="_blank">Id: '+( getEmployeeId(v.employee_id, v.employee_number) )+'</a></p>';
                rows += '            </div>';
                rows += '        </div>';
                rows += '    </td>';
                rows += '    <td>';
                rows += '        <div class="text">';
                rows += '            <p>'+( ucwords(v.policy_title) )+'</p>';
                rows += '        </div>';
                rows += '    </td>';
                rows += '    <td>';
                rows += '        <div class="text">';
                rows += '            <p>'+( v.accural_type  == 'pay_per_year' ? 'Pay Per Year' : 'Pay Per Month' )+'</p>';
                rows += '        </div>';
                rows += '    </td>';
                rows += '    <td>';
                rows += '        <div class="text">';
                rows += '            <p>'+( v.accural_start_date == null ? 'Hire Date' : moment(v.accural_start_date, 'YYYY-MM-DD').format(timeoffDateFormat) )+'</p>';
                rows += '        </div>';
                rows += '    </td>';
                rows += '    <td>';
                rows += '        <div class="text">';
                rows += '            <p class="cs-status-text '+( v.is_lose_active == 1 ? 'cs-success' : 'cs-danger' )+'">'+( v.is_lose_active == 1 ? 'Enabled' : 'Disabled' )+'</p>';
                rows += '        </div>';
                rows += '    </td>';
                rows += '    <td>';
                rows += '        <div class="text">';
                rows += '            <p>'+( moment(v.created_at, 'YYYY-MM-DD').format(timeoffDateFormat) )+'</p>';
                rows += '        </div>';
                rows += '    </td>';
                rows += '    <td>';
                rows += '        <div class="text cs-status-text">';
                rows += '            <p class="'+( v.status == 1 ? 'cs-success' : 'cs-danger' )+'">'+( v.status == 1 ? 'Active' : 'In-active' )+'</p>';
                rows += '        </div>';
                rows += '    </td>';
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
            megaOBJ.status = $('#js-status-add').val();
            megaOBJ.sortOrder = $('#js-sort-order-add').val();
            megaOBJ.archiveCheck = Number($('#js-archive-check-add').prop('checked'));
            megaOBJ.approverCheck = Number($('#js-approver-check-add').prop('checked'));
            megaOBJ.unlimitedPolicyCheck = Number($('#js-unlimited-policy-check-add').prop('checked'));
            megaOBJ.isArchived = Number($('#js-archive-check-add').prop('checked'));
            megaOBJ.isUse = Number($('#js-lose-add').prop('checked'));

            // Acccural settings
            megaOBJ.accuralType = $('#js-accural-type-add').val();
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
            if($('.js-hire-date-add:checked').val() == 'customHireDate'){
                if($('#js-custom-date-add').val() == ''){
                    alertify.alert('ERROR!', 'Please select accural start date.');
                    return;
                }
                megaOBJ.accuralDateType = 'custom';
                megaOBJ.accuralStartDate = $('#js-custom-date-add').val();
            }

            // Validate plans
            var planError = false;
            megaOBJ.plans = [];
            if($('#js-unlimited-policy-check-add').prop('checked') === false){
                //
                $.each($('.js-plan-row-add'), function(){
                    var days = parseInt($(this).find('.js-days-add').val()),
                    hours = parseInt($(this).find('.js-hours-add').val()),
                    minutes = parseInt($(this).find('.js-minutes-add').val()),
                    allZero = 0;
                    // Check for days
                    if($(this).find('.js-days-add').length != 0){
                        if(isNaN(days) || days < 0){
                            alertify.alert('ERROR!', 'In-valid value for day is provided for '+( $(this).find('h4').text() )+' plan.');
                            planError = true;
                            return false;
                        } else allZero += 1;
                    }
                    if($(this).find('.js-hours-add').length != 0){
                        if(isNaN(hours) || hours < 0){
                            alertify.alert('ERROR!', 'In-valid value for hour is provided for '+( $(this).find('h4').text() )+' plan.');
                            planError = true;
                            return false;
                        } else allZero += 1;
                    }
                    if($(this).find('.js-minutes-add').length != 0){
                        if(isNaN(minutes) || minutes < 0){
                            alertify.alert('ERROR!', 'In-valid value for minutes is provided for '+( $(this).find('h4').text() )+' plan.');
                            planError = true;
                            return false;
                        } else allZero += 1;
                    }

                    if(allZero == 0){
                        alertify.alert('ERROR!', 'Plan timeslot can not be zero for '+( $(this).find('h4').text() )+' plan.');
                        planError = true;
                        return false;
                    } else{
                        days = isNaN(days) ? 0 : days;
                        hours = isNaN(hours) ? 0 : hours;
                        minutes = isNaN(minutes) ? 0 : minutes;
                        megaOBJ.plans.push({
                            planSid: $(this).data('id'),
                            days: days,
                            hours: hours,
                            minutes: minutes,
                            timeslot: $(this).data('timeslot')
                        });
                    }
                });
            }
            //
            if(planError) return;
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
            megaOBJ.status = $('#js-status-edit').val();
            megaOBJ.sortOrder = $('#js-sort-order-edit').val();
            megaOBJ.archiveCheck = Number($('#js-archive-check-edit').prop('checked'));
            megaOBJ.approverCheck = Number($('#js-approver-check-edit').prop('checked'));
            megaOBJ.unlimitedPolicyCheck = Number($('#js-unlimited-policy-check-edit').prop('checked'));
            megaOBJ.isArchived = Number($('#js-archive-check-edit').prop('checked'));
            megaOBJ.isUse = Number($('#js-lose-edit').prop('checked'));
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
            // Validate plans
            var planError = false;
            megaOBJ.plans = [];
            if($('#js-unlimited-policy-check-edit').prop('checked') === false){
                //
                $.each($('.js-plan-row-edit'), function(){
                    var days = parseInt($(this).find('.js-days-edit').val()),
                    hours = parseInt($(this).find('.js-hours-edit').val()),
                    minutes = parseInt($(this).find('.js-minutes-edit').val()),
                    allZero = 0;
                    // Check for days
                    if($(this).find('.js-days-edit').length != 0){
                        if(isNaN(days) || days < 0){
                            alertify.alert('ERROR!', 'In-valid value for day is provided for '+( $(this).find('h4').text() )+' plan.');
                            planError = true;
                            return false;
                        } else allZero += days;
                    }
                    if($(this).find('.js-hours-edit').length != 0){
                        if(isNaN(hours) || hours < 0){
                            alertify.alert('ERROR!', 'In-valid value for hour is provided for '+( $(this).find('h4').text() )+' plan.');
                            planError = true;
                            return false;
                        } else allZero += hours;
                    }
                    if($(this).find('.js-minutes-edit').length != 0){
                        if(isNaN(minutes) || minutes < 0){
                            alertify.alert('ERROR!', 'In-valid value for minutes is provided for '+( $(this).find('h4').text() )+' plan.');
                            planError = true;
                            return false;
                        } else allZero += minutes;
                    }

                    if(allZero == 0){
                        alertify.alert('ERROR!', 'Plan timeslot can not be zero for '+( $(this).find('h4').text() )+' plan.');
                        planError = true;
                        return false;
                    } else{
                        days = isNaN(days) ? 0 : days;
                        hours = isNaN(hours) ? 0 : hours;
                        minutes = isNaN(minutes) ? 0 : minutes;
                        megaOBJ.plans.push({
                            planSid: $(this).data('id'),
                            days: days,
                            hours: hours,
                            minutes: minutes,
                            timeslot: $(this).data('timeslot')
                        });
                    }
                });
            }
            //
            if(planError) return;
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

            $('#js-employee-edit').select2('val', policy.assigned_employees.split(','));
            $('#js-status-edit').select2();
            $('#js-status-edit').select2('val', policy.status);
            $('#js-sort-order-edit').val(policy.sort_order);
            $('#js-approver-check-edit').prop('checked', Boolean(Number(policy.for_admin)));
            $('#js-unlimited-policy-check-edit').prop('checked', Boolean(Number(policy.is_unlimited)));
            $('#js-archive-check-edit').prop('checked', Boolean(Number(policy.is_archived)));
            $('#js-lose-edit').prop('checked', Boolean(Number(policy.is_lose_active)));
            $('#js-accural-type-edit').select2();
            $('#js-accural-type-edit').select2('val', policy.accural_type);
            $('.js-hire-date-edit[value="hireDate"]').prop('checked', true);
            $('#js-custom-date-edit').val('');
            //
            if(policy.accural_start_date != null){
                $('.js-hire-date-edit[value="customHireDate"]').prop('checked', true);
                $('#js-custom-date-edit').val(moment(policy.accural_start_date, 'YYYY-MM-DD').format('MM-DD-YYYY'));
            }
            //
            $('#js-plan-box-edit').find('ul').html('');
            $('#js-plan-box-edit').hide();
            //
            if(policy.plans.length != 0){
                $('#js-plan-box-edit').show();
                loadPlans(policy.plans, 'edit');
            }
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
                    alertify.alert('NOTICE', 'Please add plans before adding policies.');
                    return;
                }
                plans = resp.Data;
                loadPlans();
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
                        rows += '<option value="'+( v.user_id )+'">'+( v.full_name )+'</option>';
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
        // Pages
        function loadAddPage(){
            $('.js-page').fadeOut(0);
            // Flush
            $('#js-policy-title-add').val();
            $('#js-employee-add').select2('val', 0);
            $('#js-employee-count-add').text(0);
            $('#js-status-add').select2('val', 1);
            $('#js-approver-check-add').prop('checked', false);
            $('#js-unlimited-policy-check-add').prop('checked', false);
            $('#js-archive-check-add').prop('checked', false);
            $('#js-lose-add').prop('checked', false);
            $('#js-accural-type-add').select2();
            $('#js-accural-type-add').select2('val', 0);
            $('.js-hire-date-add[value="hireDate"]').prop('checked', true);
            $('#js-custom-date-add').val('');
            $('#js-sort-order-add').val(policies.length + 1);
            //
            $('#js-plan-box-add').find('ul').html('');
            $('#js-plan-box-add').show();
            loadPlans();
            $('#js-page-add').fadeIn(500);
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
            if(plans.length == 0){
                intervalCatcher = setInterval(function(){
                    if(plans.length != 0){
                        loadEditPage(sid);
                        clearInterval(intervalCatcher);
                    }
                }, 100);

                return;
            }
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
    })

</script>
<style>.js-archive-btn > i{ color: #a94442 !important; }</style>
<style>.js-activate-btn > i{ color: #81b431  !important; }</style>