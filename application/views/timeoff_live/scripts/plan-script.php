<script type="text/javascript">
    var baseURI = "<?=base_url();?>timeoff/";
    $(function(){
        var employees = [],
        is_filter = false,
        lastPage = "<?=$page;?>",
        default_slot = 0,
        fetchStatus = 'active',
        plans = [],
        xhr = null,
        pOBJ = {
            'fetchCompanyPlans' : {
                page: 1,
                totalPages: 0,
                limit: 0,
                records: 0,
                totalRecords: 0,
                cb: fetchCompanyPlans
            }
        },
        record = [];

        /* FILTER START */
        // Fetch all plans
        fetchPlanList();
        fetchPlanCreators();

        // Select2
        $('#js-filter-status').select2();
        $('#js-status-add').select2();
        $('#js-status-edit').select2();

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
            fetchCompanyPlans();
        });
        /* TAB CHANGER END*/

        /* VIEW PAGE START */
        //
        function resetFilter(e){
            e.preventDefault();
            is_filter = false;
            $('#js-filter-plans').select2('val', 0);
            $('#js-filter-employee').select2('val', 0);
            $('#js-filter-from-date').val('');
            $('#js-filter-to-date').val('');
            $('#js-filter-status').select2('val', '-1');

            pOBJ['fetchCompanyPlans']['records'] = [];
            pOBJ['fetchCompanyPlans']['totalPages'] =
            pOBJ['fetchCompanyPlans']['totalRecords'] =
            pOBJ['fetchCompanyPlans']['limit'] = 0;
            pOBJ['fetchCompanyPlans']['page'] = 1;

            fetchCompanyPlans();
        }
        //
        function applyFilter(e){
            loader();
            e.preventDefault();
            is_filter = true;
            pOBJ['fetchCompanyPlans']['records'] = [];
            pOBJ['fetchCompanyPlans']['totalPages'] =
            pOBJ['fetchCompanyPlans']['totalRecords'] =
            pOBJ['fetchCompanyPlans']['limit'] = 0;
            pOBJ['fetchCompanyPlans']['page'] = 1;

            fetchCompanyPlans();
        }
        // Fetch plans
        function fetchCompanyPlans(){
            if(xhr != null) return;
            loader('show');
            $('.js-error-row').remove();
            var megaOBJ = {};
            megaOBJ.page = pOBJ['fetchCompanyPlans']['page'];
            megaOBJ.action = 'get_plan_by_company';
            megaOBJ.companySid = <?=$company_sid;?>;
            megaOBJ.status = is_filter ? $('#js-filter-status').val() : '';
            megaOBJ.planSid = is_filter ? $('#js-filter-plans').val() : '';
            megaOBJ.endDate = is_filter ? $('#js-filter-to-date').val().trim() : '';
            megaOBJ.startDate = is_filter ? $('#js-filter-from-date').val().trim() : '';
            megaOBJ.employeeSid = is_filter ? $('#js-filter-employee').val() : '';
            megaOBJ.fetchType = fetchStatus;

            xhr = $.post(baseURI+'handler', megaOBJ, function(resp) {
                xhr = null;
                //
                if(resp.Status === false && pOBJ['fetchCompanyPlans']['page'] == 1){
                    $('.js-ip-pagination').html('');
                    loader('hide');
                    $('#js-data-area').html('<tr class="js-error-row"><td colspan="6"><p class="alert alert-info text-center">'+( resp.Response )+'</p></td></tr>')
                }
                //
                if(resp.Status === false){
                    loader('hide');
                    $('.js-ip-pagination').html('');
                    return;
                }

                pOBJ['fetchCompanyPlans']['records'] = resp.Data;
                if(pOBJ['fetchCompanyPlans']['page'] == 1) {
                    pOBJ['fetchCompanyPlans']['limit'] = resp.Limit;
                    pOBJ['fetchCompanyPlans']['totalPages'] = resp.TotalPages;
                    pOBJ['fetchCompanyPlans']['totalRecords'] = resp.TotalRecords;
                }
                //
                setTable(resp);
            });
        }
        //
        function setTable(resp){
            var title = fetchStatus != 'active' ? 'Activate Plan' : 'Archive Plan',
            icon = fetchStatus != 'active' ? 'fa-check-square-o' : 'fa-archive',
            cl = fetchStatus != 'active' ? 'js-activate-plan' : 'js-archive-plan',
            rows = '';
            if(resp.Data.length == 0) return;
            //
            $.each(resp.Data, function(i, v){
                rows += '<tr data-id="'+( v.plan_id )+'">';
                rows += '    <td scope="row">';
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
                rows += '            <p>'+( getPlanTitle(v) )+'</p>';
                rows += '        </div>';
                rows += '    </td>';
                rows += '    <td>';
                rows += '        <div class="text">';
                rows +=             '<p>'+( formatMinutes(v.format, v.default_timeslot, v.allowed_timeoff, true) )+'</p>';
                rows += '        </div>';
                rows += '    </td>';
                rows += '    <td>';
                rows += '        <div class="text">';
                rows += '            <p>'+( moment(v.created_at, 'MM-DD-YYYY').format(timeoffDateFormat) )+'</p>';
                rows += '        </div>';
                rows += '    </td>';
                rows += '    <td>';
                rows += '        <div class="text cs-status-text">';
                rows += '            <p class="'+( v.status == 1 ? 'cs-success' : 'cs-danger' )+'">'+( v.status == 1 ? 'Active' : 'In-active' )+'</p>';
                rows += '        </div>';
                rows += '    </td>';
                rows += '    <td>';
                rows += '        <div class="action-employee">';
                rows += '            <a href="javascript:void(0)" class="action-edit js-edit-plan-btn"><i class="fa fa-pencil-square-o fa-fw icon_blue"></i></a>';
                rows += '            <a href="javascript:void(0)" data-toggle="tooltip" title="'+( title )+'" class="action-activate custom-tooltip '+( cl )+'"><i class="fa '+( icon )+' fa-fw "></i></a>';
                rows += '        </div>';
                rows += '    </td>';
                rows += '</tr>';

            });

            //
            load_pagination(
                pOBJ['fetchCompanyPlans']['limit'],
                5,
                $('.js-ip-pagination'),
                'fetchCompanyPlans'
            );

            $('#js-data-area').html(rows);
            loader('hide');
        }
        //
        $(document).on('click', '.js-archive-plan', function(e){
            e.preventDefault();
            var _this = $(this);
            alertify.confirm('Do you really want to archive this plan?', function(){
                var megaOBJ = {};
                megaOBJ.companySid = <?=$company_sid;?>;
                megaOBJ.action = 'archive_company_plan';
                megaOBJ.planSid = _this.closest('tr').data('id');
                megaOBJ.employeeSid = <?=$employer_sid;?>;
                //
                loader('show');
                $.post(baseURI+'handler', megaOBJ, function(resp) {
                    //
                    if(resp.Status === false){
                        loader('hide');
                        alertify.alert('ERROR!', resp.Response, function(){ return; });
                        return;
                    }
                    //
                    alertify.alert('SUCCESS!', resp.Response, function(){ loadViewPage(); });
                });
            }).set('labels',{ok:'Yes', cancel:'No'}).set('label', 'WARNING!');
        });
        //
        $(document).on('click', '.js-activate-plan', function(e){
            e.preventDefault();
            var _this = $(this);
            alertify.confirm('Do you really want to activate this plan?', function(){
                var megaOBJ = {};
                megaOBJ.companySid = <?=$company_sid;?>;
                megaOBJ.action = 'activate_company_plan';
                megaOBJ.planSid = _this.closest('tr').data('id');
                megaOBJ.employeeSid = <?=$employer_sid;?>;
                //
                loader('show');
                $.post(baseURI+'handler', megaOBJ, function(resp) {
                    //
                    if(resp.Status === false){
                        loader('hide');
                        alertify.alert('ERROR!', resp.Response, function(){ return; });
                        return;
                    }
                    //
                    alertify.alert('SUCCESS!', resp.Response, function(){ loadViewPage(); });
                });
            }).set('labels',{ok:'Yes', cancel:'No'}).set('label', 'WARNING!');
        });
        //
        $(document).on('click', '.js-edit-plan-btn', function(e){
            e.preventDefault();
            var sid = $(this).closest('tr').data('id'),
            record = findRecord(pOBJ['fetchCompanyPlans']['records'], sid);
            //
            $('.js-page').fadeOut(0);
            $('#js-page-edit').fadeIn(500);
            loadEditPage(record);
        });

        /* VIEW PAGE END */


        /* ADD PAGE START*/
        //
        $('.js-plan-type-add').click(function(){
            if($(this).val() == 'existed'){
                $('#js-plan-add-input').hide();
                $('#js-plan-add-select').show();
            }else{
                $('#js-plan-add-select').hide();
                $('#js-plan-add-input').show();
                $('#js-plan-add-input input').focus();
            }
        });
        //
        $('#js-allowed-hours-add').keyup(function(){
            $(this).val(
                $(this).val().replace(/[^0-9\.]/g, '')
            );
        });
        $('#js-allowed-days-add').keyup(function(){
            $(this).val(
                $(this).val().replace(/[^0-9\.]/g, '')
            );
        });
        $('#js-allowed-minutes-add').keyup(function(){
            $(this).val(
                $(this).val().replace(/[^0-9\.]/g, '')
            );
        });
        $('#js-plan-period-add-input').keyup(function(){
            $(this).val(
                $(this).val().replace(/[^0-9\.]/g, '')
            );
        });
        //
        $('#js-save-add-btn').click(function(e){
            e.preventDefault();
            var megaOBJ = {},
            formats = <?=json_encode($timeOffFormat);?>;
            megaOBJ.companySid = <?=$company_sid;?>;
            megaOBJ.formatSid = <?=$timeoff_format_sid;?>;
            megaOBJ.employeeSid = <?=$employer_sid;?>;
            megaOBJ.planSid = $('#js-plan-period-add').val();
            megaOBJ.plan = '';
            megaOBJ.action = 'add_company_plan';
            megaOBJ.status = $('#js-status-add').val();
            megaOBJ.isArchived = Number($('#js-archived-add').prop('checked'));
            megaOBJ.allowedDays = megaOBJ.allowedHours = megaOBJ.allowedMinutes = 0;
            // Validation
            if($('.js-plan-type-add[value="custom"]').prop('checked') == true){
                if($('#js-plan-period-add-input').val().trim() < 0){
                    alertify.alert('ERROR!', 'Plan year can not be less than 0.');
                    return;
                }
                if($('#js-plan-period-month-add-input').val().trim() < 0){
                    alertify.alert('ERROR!', 'Plan month can not be less than 0.');
                    return;
                }
                if($('#js-plan-period-month-add-input').val().trim() > 11){
                    alertify.alert('ERROR!', 'Plan month can not be greater than 11.', function(){ return; });
                    return;
                }
                megaOBJ.plan = 1;
                megaOBJ.year = $('#js-plan-period-add-input').val().trim();
                megaOBJ.month = $('#js-plan-period-month-add-input').val().trim();
            } else if(megaOBJ.planSid == 0 || megaOBJ.planSid == null){
                alertify.alert('ERROR!', 'Please select a plan.');
                return;
            }
            //
            //
            if(formats.indexOf('D') !== -1){
                megaOBJ.allowedDays = $('#js-allowed-days-add').val().trim().replace(/[^0-9\.]/g, '');
                if(megaOBJ.allowedDays < 0){
                    alertify.alert('ERROR!', 'Allowed days can not be less than 0.');
                    return;
                }
            }
            if(formats.indexOf('H') !== -1){
                megaOBJ.allowedHours = $('#js-allowed-hours-add').val().trim().replace(/[^0-9\.]/g, '');
                if(megaOBJ.allowedHours < 0){
                    alertify.alert('ERROR!', 'Allowed hours can not be less than 0.');
                    return;
                }
            }
            if(formats.indexOf('M') !== -1){
                megaOBJ.allowedMinutes = $('#js-allowed-minutes-add').val().trim().replace(/[^0-9\.]/g, '');
                if(megaOBJ.allowedMinutes < 0){
                    alertify.alert('ERROR!', 'Allowed minutes can not be less than 0.');
                    return;
                }
            }
            //
            if(megaOBJ.allowedDays == 0 && megaOBJ.allowedHours == 0 && megaOBJ.allowedMinutes == 0){
                alertify.alert('Allowed time off can not be set to zero.');
                return;
            }
            //
            loader('show');
            $.post(baseURI+'handler', megaOBJ, function(resp) {
                //
                if(resp.Status === false){
                    loader('hide');
                    alertify.alert('ERROR!', resp.Response, function(){ return; });
                    return;
                }
                //
                alertify.alert('SUCCESS!', resp.Response, function(){ loadViewPage(); });
            });
        });
        /* ADD PAGE END*/

        /* EDIT PAGE END*/
        //
        $('.js-plan-type-edit').click(function(){
            if($(this).val() == 'existed'){
                $('#js-plan-edit-input').hide();
                $('#js-plan-edit-select').show();
            }else{
                $('#js-plan-edit-select').hide();
                $('#js-plan-edit-input').show();
                $('#js-plan-edit-input input').focus();
            }
        });
        //
        $('#js-allowed-hours-edit').keyup(function(){
            $(this).val(
                $(this).val().replace(/[^0-9\.]/g, '')
            );
        });
        $('#js-allowed-days-edit').keyup(function(){
            $(this).val(
                $(this).val().replace(/[^0-9\.]/g, '')
            );
        });
        $('#js-allowed-minutes-edit').keyup(function(){
            $(this).val(
                $(this).val().replace(/[^0-9\.]/g, '')
            );
        });
        $('#js-plan-period-edit-input').keyup(function(){
            $(this).val(
                $(this).val().replace(/[^0-9\.]/g, '')
            );
        });
        $('#js-plan-period-month-edit-input').keyup(function(){
            $(this).val(
                $(this).val().replace(/[^0-9\.]/g, '')
            );
        });
        $('#js-plan-period-month-add-input').keyup(function(){
            $(this).val(
                $(this).val().replace(/[^0-9\.]/g, '')
            );
        });
        //
        $(".js-edit-plan-btn").click(function() {
            $('.js-page').fadeOut(0);
            // Flush
            $('#js-page-edit').fadeIn(500);
            loader('hide');
        });
        //
        $('#js-save-edit-btn').click(function(e){
            e.preventDefault();
            var megaOBJ = {},
            formats = <?=json_encode($timeOffFormat);?>;
            megaOBJ.companySid = <?=$company_sid;?>;
            megaOBJ.formatSid = <?=$timeoff_format_sid;?>;
            megaOBJ.employeeSid = <?=$employer_sid;?>;
            megaOBJ.planListSid = $('#js-plan-period-edit').val();
            megaOBJ.recordSid = $('#js-plan-id-edit').val();
            megaOBJ.plan = '';
            megaOBJ.action = 'edit_company_plan';
            megaOBJ.status = $('#js-status-edit').val();
            megaOBJ.isArchived = Number($('#js-archived-edit').prop('checked'));
            megaOBJ.allowedDays = megaOBJ.allowedHours = megaOBJ.allowedMinutes = 0;
            // Validation
            if($('.js-plan-type-edit[value="custom"]').prop('checked') == true){
                if($('#js-plan-period-edit-input').val().trim() < 0){
                    alertify.alert('ERROR!', 'Plan year can not be less than 0.', function(){ return; });
                    return;
                }
                if($('#js-plan-period-month-edit-input').val().trim() < 0){
                    alertify.alert('ERROR!', 'Plan month can not be less than 0.', function(){ return; });
                    return;
                }
                if($('#js-plan-period-month-edit-input').val().trim() > 11){
                    alertify.alert('ERROR!', 'Plan month can not be greater than 11.', function(){ return; });
                    return;
                }
                megaOBJ.plan = 1;
                megaOBJ.year = $('#js-plan-period-edit-input').val().trim();
                megaOBJ.month = $('#js-plan-period-month-edit-input').val().trim();
            } else if(megaOBJ.planListSid == 0 || megaOBJ.planListSid == null){
                alertify.alert('ERROR!', 'Please select a plan.');
                return;
            }
            //
            if(formats.indexOf('D') !== -1){
                megaOBJ.allowedDays = $('#js-allowed-days-edit').val().trim().replace(/[^0-9\.]/g, '');
                if(megaOBJ.allowedDays < 0){
                    alertify.alert('ERROR!', 'Allowed days can not be less than 0.');
                    return;
                }
            }
            if(formats.indexOf('H') !== -1){
                megaOBJ.allowedHours = $('#js-allowed-hours-edit').val().trim().replace(/[^0-9\.]/g, '');
                if(megaOBJ.allowedHours < 0){
                    alertify.alert('ERROR!', 'Allowed hours can not be less than 0.');
                    return;
                }
            }
            if(formats.indexOf('M') !== -1){
                megaOBJ.allowedMinutes = $('#js-allowed-minutes-edit').val().trim().replace(/[^0-9\.]/g, '');
                if(megaOBJ.allowedMinutes < 0){
                    alertify.alert('ERROR!', 'Allowed minutes can not be less than 0.');
                    return;
                }
            }
            //
            if(megaOBJ.allowedDays == 0 && megaOBJ.allowedHours == 0 && megaOBJ.allowedMinutes == 0){
                alertify.alert('Allowed time off can not be set to zero.');
                return;
            }
            //
            loader('show');
            $.post(baseURI+'handler', megaOBJ, function(resp) {
                //
                if(resp.Status === false){
                    loader('hide');
                    alertify.alert('ERROR!', resp.Response, function(){ return; });
                    return;
                }
                //
                alertify.alert('SUCCESS!', resp.Response, function(){ loadViewPage(); });
            });
        });
        ///
        function loadEditPage(record){
            lastPage = 'edit';
            loader();
            loadPageTime(
                record,
                '#js-allowed-days-edit',
                '#js-allowed-hours-edit',
                '#js-allowed-minutes-edit'
            );
            //
            $('#js-archived-edit').prop('checked', record.is_archived == 1 ? true : false);
            $('#js-plan-period-edit').select2();
            $('#js-status-edit').select2();
            $('#js-plan-period-edit').select2('val', record.plan_list_id);
            $('#js-status-edit').select2('val', record.status);
            $('#js-plan-id-edit').val(record.plan_id);
            $('#js-plan-edit-select').show();
            $('#js-plan-edit-input').hide();
            $('#js-plan-edit-input input').val('');
            $('.js-plan-type-edit[value="existed"]').prop('checked', true);
            //
            loader('hide');
        }
        /* EDIT PAGE START*/

        // Page events
        // Page handlers
        $("#js-add-plan-btn").click(function() { setHistory('add', baseURI+'plans/add', 'Add Page'); });
        $(".js-view-plan-btn").click(function() { setHistory('view', baseURI+'plans/view', 'View Page'); });
        // Pages
        function loadAddPage(){
            lastPage = 'add';
            $('.js-page').fadeOut(0);
            // Flush
            $('#js-plan-period-add').select2();
            $('#js-status-add').select2();
            $('#js-plan-period-add').select2('val', 0);
            $('#js-status-add').select2('val', 1);
            $('#js-plan-period-add-input').val('');
            $('#js-allowed-hours-add').val('');
            $('#js-allowed-minutes-add').val('');
            $('#js-allowed-days-add').val('');
            $('#js-plan-add-select').show();
            $('#js-plan-add-input').hide();
            $('.js-plan-type-add[value="existed"]').prop('checked', true);
            $('#js-archived-add').prop('checked', false);
            $('#js-page-add').fadeIn(500);
            loader('hide');
        }
        function loadViewPage(){
            lastPage = 'view';
            $('.js-page').fadeOut(0);
            pOBJ['fetchCompanyPlans']['records'] = [];
            pOBJ['fetchCompanyPlans']['page'] = 1;
            pOBJ['fetchCompanyPlans']['limit'] = 0;
            pOBJ['fetchCompanyPlans']['totalPages'] = 0;
            pOBJ['fetchCompanyPlans']['totalRecords'] = 0;
            fetchCompanyPlans();
            $('#js-page-view').fadeIn(500);
        }
        // Set history
        function setHistory(page, pageURL, pageTitle, dataToBind){
            if(page == lastPage) return;
            if(page === undefined) return;
            if(pageURL === undefined) return;
            if(dataToBind === undefined) dataToBind = {};
            if(pageTitle === undefined) pageTitle = '';
            window.history.pushState({fromPage: { title: lastPage }, toPage: {title: page}}, pageTitle, pageURL);
            switch(page){
                case 'add': loadAddPage(); break;
                case 'view': loadViewPage(); break;
            }
            lastPage = page;
            $('html,body').animate({ scrollTop: 0 }, 'slow');
        }
        //
        window.onpopstate = function(event) {
            //
            if(event.state == null){
                <?php if($page == 'view') { ?>fetchCompanyPlans();
                <?php } else if($page == 'add') { ?> loadAddPage();
                <?php } else if($page == 'edit' && $planSid != null) { ?> loadEditPage(<?=$planSid;?>); <?php } ?>
            }
            //
            switch (event.state.toPage.title) {
                case 'add': loadAddPage(); break;
                case 'view': loadViewPage(); break;
            }
            lastPage = event.state.toPage.title;
        };
        //
        function fetchPlanList(){
            $.post(baseURI+'handler', {
                action: 'get_plan_list_by_company',
                companySid: <?=$company_sid;?>
            }, function(resp) {
                //
                if(resp.Status === false){
                    console.log('failed to load plans list');
                    return;
                }
                //
                plans = resp.Data;
                var tmp = plans.map(function(v){ return '<option value="'+( v.timeoff_plan_list_sid )+'">'+( getPlanTitle(v) )+'</option>'; });
                tmp = '<option value="0">[Select a plan]</option>' + tmp;
                $('#js-filter-plans, #js-plan-period-add, #js-plan-period-edit').html(tmp);
                $('#js-filter-plans, #js-plan-period-add, #js-plan-period-edit').select2();
            });
        }
        //
        function fetchPlanCreators(){
            $.post(baseURI+'handler', {
                action: 'get_plan_creators_by_company',
                companySid: <?=$company_sid;?>
            }, function(resp) {
                //
                if(resp.Status === false){
                    console.log('failed to load employees');
                    return;
                }
                //
                employees = resp.Data;
                var tmp = employees.map(function(v){ return '<option value="'+( v.employee_id )+'">'+( ucwords(v.full_name) )+'</option>'; });
                tmp = '<option value="0">[Select an employee]</option>' + tmp;
                $('#js-filter-employee').html(tmp);
                $('#js-filter-employee').select2();
            });
        }
        //
        <?php if($page == 'view') { ?>fetchCompanyPlans();
        <?php } else if($page == 'add') { ?> loadAddPage(); <?php } ?>
        //
        <?php $this->load->view('timeoff/scripts/common'); ?>
    })

</script>
<style>.js-archive-plan > i{ color: #a94442 !important; }</style>
<style>.js-activate-plan > i{ color: #81b431  !important; }</style>