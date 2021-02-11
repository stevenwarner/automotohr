<script type="text/javascript">
    var baseURI = "<?=base_url();?>timeoff/";
    $(function(){
        var
        employees = [],
        departments = [],
        is_filter = false,
        lastPage = "<?=$page;?>",
        default_slot = 0,
        fetchStatus = 'active',
        policies = [],
        plans = [],
        xhr = null,
        pOBJ = {
            'fetchCompanyApprovers' : {
                page: 1,
                totalPages: 0,
                limit: 0,
                records: 0,
                totalRecords: 0,
                cb: fetchCompanyApprovers
            }
        },
        record = [],
        intervalCatcher = null;

        /* FILTER START */
        fetchDepartments();
        fetchEmployees();

        // Select2
        $('#js-filter-status').select2();
        $('#js-status-add').select2();
        $('#js-status-edit').select2();
        $('#js-accural-type-add').select2();
        $('#js-accrue-start-add').select2();

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
            fetchCompanyApprovers();
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

            pOBJ['fetchCompanyApprovers']['records'] = [];
            pOBJ['fetchCompanyApprovers']['totalPages'] =
            pOBJ['fetchCompanyApprovers']['totalRecords'] =
            pOBJ['fetchCompanyApprovers']['limit'] = 0;
            pOBJ['fetchCompanyApprovers']['page'] = 1;

            fetchCompanyApprovers();
        }
        //
        function applyFilter(e){
            loader();
            e.preventDefault();
            is_filter = true;
            pOBJ['fetchCompanyApprovers']['records'] = [];
            pOBJ['fetchCompanyApprovers']['totalPages'] =
            pOBJ['fetchCompanyApprovers']['totalRecords'] =
            pOBJ['fetchCompanyApprovers']['limit'] = 0;
            pOBJ['fetchCompanyApprovers']['page'] = 1;

            fetchCompanyApprovers();
        }
        // Fetch plans
        function fetchCompanyApprovers(){
            if(xhr != null) return;
            loader('show');
            $('.js-error-row').remove();
            var megaOBJ = {};
            megaOBJ.page = pOBJ['fetchCompanyApprovers']['page'];
            megaOBJ.action = 'get_approvers_by_company';
            megaOBJ.companySid = <?=$company_sid;?>;
            megaOBJ.status = is_filter ? $('#js-filter-status').val() : '';
            megaOBJ.departmentSid = is_filter ? $('#js-filter-departments').val() : '';
            megaOBJ.endDate = is_filter ? $('#js-filter-to-date').val().trim() : '';
            megaOBJ.startDate = is_filter ? $('#js-filter-from-date').val().trim() : '';
            megaOBJ.employeeSid = is_filter ? $('#js-filter-employee').val() : '';
            megaOBJ.fetchType = fetchStatus;

            xhr = $.post(baseURI+'handler', megaOBJ, function(resp) {
                xhr = null;
                //
                if(resp.Status === false && pOBJ['fetchCompanyApprovers']['page'] == 1){
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

                pOBJ['fetchCompanyApprovers']['records'] = resp.Data;
                if(pOBJ['fetchCompanyApprovers']['page'] == 1) {
                    pOBJ['fetchCompanyApprovers']['limit'] = resp.Limit;
                    pOBJ['fetchCompanyApprovers']['totalPages'] = resp.TotalPages;
                    pOBJ['fetchCompanyApprovers']['totalRecords'] = resp.TotalRecords;
                }
                //
                setTable(resp);
            });
        }
        //
        function setTable(resp){
            var title = fetchStatus != 'active' ? 'Activate Approver' : 'Archive Approver',
            icon = fetchStatus != 'active' ? 'fa-check-square-o' : 'fa-archive',
            cl = fetchStatus != 'active' ? 'js-activate-btn' : 'js-archive-btn',
            rows = '';
            if(resp.Data.length == 0) return;
            //
            $.each(resp.Data, function(i, v){
                rows += '<tr data-id="'+( v.approver_id )+'">';
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
                rows += '            <p>'+( v.department_name == null ? 'All Departments' : ucwords(v.department_name) )+'</p>';
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
                rows += '            <a href="javascript:void(0)" data-toggle="tooltip" title="Edit Approver" class="action-edit js-edit-row-btn"><i class="fa fa-pencil-square-o fa-fw icon_blue"></i></a>';
                rows += '            <a href="javascript:void(0)" data-toggle="tooltip" title="'+( title )+'" class="action-activate custom-tooltip '+( cl )+'"><i class="fa '+( icon )+' fa-fw "></i></a>';
                rows += '        </div>';
                rows += '    </td>';
                rows += '</tr>';
            });

            //
            load_pagination(
                pOBJ['fetchCompanyApprovers']['limit'],
                5,
                $('.js-ip-pagination'),
                'fetchCompanyApprovers'
            );

            $('#js-data-area').html(rows);
            loader('hide');

            callDrager();
        }
        //
        $(document).on('click', '.js-archive-btn', function(e){
            e.preventDefault();
            var _this = $(this);
            alertify.confirm('Do you really want to archive this Approver?', function(){
                var megaOBJ = {};
                megaOBJ.companySid = <?=$company_sid;?>;
                megaOBJ.action = 'archive_company_approver';
                megaOBJ.approverSid = _this.closest('tr').data('id');
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
        $(document).on('click', '.js-activate-btn', function(e){
            e.preventDefault();
            var _this = $(this);
            alertify.confirm('Do you really want to activate this Approver?', function(){
                var megaOBJ = {};
                megaOBJ.companySid = <?=$company_sid;?>;
                megaOBJ.action = 'activate_company_approver';
                megaOBJ.approverSid = _this.closest('tr').data('id');
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
        $(document).on('click', '.js-edit-row-btn', function(e){
            e.preventDefault();
            //
            loader('show');
            loadEditPage($(this).closest('tr').data('id'));
            setHistory('edit/', baseURI+'approvers/edit/'+($(this).closest('tr').data('id'))+'', 'Edit Page', {}, $(this).closest('tr').data('id'));
        });

        /* VIEW PAGE END */

        /* ADD PAGE START*/
        //
        $('#js-save-add-btn').click(function(e){
            e.preventDefault();
            var megaOBJ = {};
            megaOBJ.companySid = <?=$company_sid;?>;
            megaOBJ.employeeSid = <?=$employer_sid;?>;
            megaOBJ.departmentSid = $('#js-departments-add').val();
            megaOBJ.approverSid = $('#js-employee-add').val();
            megaOBJ.action = 'add_company_approver';
            megaOBJ.status = $('#js-status-add').val();
            megaOBJ.archiveCheck = Number($('#js-archive-check-add').prop('checked'));

            // Check if title is empty
            if(megaOBJ.departmentSid == '' || megaOBJ.departmentSid == 0){
                alertify.alert('ERROR!', 'Please select a department.');
                return;
            }

            // Check and assign employees
            if(megaOBJ.approverSid == 0){
                alertify.alert('ERROR!', 'Please select a approver.');
                return;
            }
            //
            loader('show');
            $.post(baseURI+'handler', megaOBJ, function(resp) {
                loader('hide');
                if(resp.Status === false){
                    alertify.alert('ERROR!', resp.Response, function(){ return; });
                    return;
                }
                alertify.alert('SUCCESS!', resp.Response, function(){ setHistory('view', baseURI+'approvers/view', 'View Page'); });
            });
        });
        /* ADD PAGE END*/

        /* EDIT PAGE END*/
        //
        $('#js-save-edit-btn').click(function(e){
            e.preventDefault();
            var megaOBJ = {};
            megaOBJ.companySid = <?=$company_sid;?>;
            megaOBJ.employeeSid = <?=$employer_sid;?>;
            megaOBJ.departmentSid = $('#js-departments-edit').val();
            megaOBJ.approverSid = $('#js-employee-edit').val();
            megaOBJ.sid = $('#js-id-edit').val();
            megaOBJ.action = 'edit_company_approver';
            megaOBJ.status = $('#js-id-edit').val();
            megaOBJ.archiveCheck = Number($('#js-archive-check-edit').prop('checked'));

            // Check if title is empty
            if(megaOBJ.departmentSid == '' || megaOBJ.departmentSid == 0){
                alertify.alert('ERROR!', 'Please select a department.');
                return;
            }

            // Check and assign employees
            if(megaOBJ.approverSid == 0){
                alertify.alert('ERROR!', 'Please select a approver.');
                return;
            }
            //
            loader('show');
            $.post(baseURI+'handler', megaOBJ, function(resp) {
                loader('hide');
                if(resp.Status === false){
                    alertify.alert('ERROR!', resp.Response, function(){ return; });
                    return;
                }
                alertify.alert('SUCCESS!', resp.Response, function(){ setHistory('view', baseURI+'approvers/view', 'View Page'); });
            });
        });
        //
        function showEditPage(approver){
            lastPage = 'edit';
            //
            $('#js-id-edit').val(approver.approver_id);
            //
            $('#js-employee-edit').select2('val', approver.employee_sid);
            //
            $('#js-departments-edit').select2('val', approver.department_id == null ? 'all' : approver.department_id);
            //
            $('#js-status-edit').select2('val', approver.status);
            //
            $('#js-archive-check-edit').prop('checked', Boolean(Number(approver.is_archived)));
            //
            loader('hide');
        }
        /* EDIT PAGE START*/

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
                    employees.map(function(v){
                        rows += '<option '+( v.access_level.toLowerCase() == 'employee' ? 'disabled="true"' : '' )+' value="'+( v.user_id )+'">'+( v.full_name )+' ('+( v.access_level )+')</option>';
                    });
                    $('#js-employee-add').html('<option value="0">[Select Approver]</option>'+rows);
                    $('#js-employee-add').select2();
                    $('#js-employee-edit').html('<option value="0">[Select Approver]</option>'+rows);
                    $('#js-employee-edit').select2();
                    rows = '<option value="all">All</option>'+rows;
                    $('#js-filter-employee').html(rows);
                    $('#js-filter-employee').select2();
            });
        }
        // Departments
        function fetchDepartments(){
            $.post(baseURI+'handler', {
                action: 'get_company_departments',
                companySid: <?=$company_sid;?>
            }, function(resp){
                    if(resp.Status === false){
                        console.log('Failed to load departments.');
                        return;
                    }
                    departments = resp.Data;
                    var rows = '';
                    rows += '<option value="all">All Departments</option>';
                    if(departments.length != 0){
                        departments.map(function(v){
                            rows += '<option value="'+( v.department_id )+'">'+( v.title )+'</option>';
                        });
                    }
                    departments.push({ department_id: 'all', name: 'All Departments' });
                    $('#js-departments-add').html(rows);
                    $('#js-departments-add').select2();
                    $('#js-departments-edit').html(rows);
                    $('#js-departments-edit').select2();
                    $('#js-filter-departments').html(rows);
                    $('#js-filter-departments').select2();
            });
        }

        // Page events
        // Page handlers
        $("#js-add-btn").click(function() { setHistory('add', baseURI+'approvers/add', 'Add Page'); });
        $(".js-view-page-btn").click(function() { setHistory('view', baseURI+'approvers/view', 'View Page'); });
        // Pages
        function loadAddPage(){
            if(employees.length == 0 || departments.length == 0){
                setTimeout(function(){
                    loadAddPage();
                }, 100);
                return;
            }
            $('.js-page').fadeOut(0);
            // Flush
            $('#js-departments-add').select2('val', 'all');
            $('#js-archive-check-add').prop('checked', false);
            $('#js-employee-add').select2('val', 0);
            $('#js-status-add').select2('val', 1);
            $('#js-page-add').fadeIn(500);
            //
            loader('hide');
        }
        function loadViewPage(){
            $('.js-page').fadeOut(0);
            pOBJ['fetchCompanyApprovers']['records'] = [];
            pOBJ['fetchCompanyApprovers']['page'] = 1;
            pOBJ['fetchCompanyApprovers']['limit'] = 0;
            pOBJ['fetchCompanyApprovers']['totalPages'] = 0;
            pOBJ['fetchCompanyApprovers']['totalRecords'] = 0;
            fetchCompanyApprovers();
            $('#js-page-view').fadeIn(500);
        }
        function loadEditPage(sid){
            if(departments.length == 0){
                intervalCatcher = setInterval(function(){
                    if(departments.length != 0){
                        loadEditPage(sid);
                        clearInterval(intervalCatcher);
                    }
                }, 100);
                return;
            }
            $('.js-page').fadeOut(0);
            $('#js-page-edit').fadeIn(500);
            $.post(baseURI+'handler', {
                action: 'get_single_company_approver',
                companySid: <?=$company_sid;?>,
                approverSid: sid
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
                <?php if($page == 'view') { ?>fetchCompanyApprovers();
                <?php } else if($page == 'add') { ?> loadAddPage();
                <?php } else if($page == 'edit' && $approverSid != null) { ?> loadEditPage(<?=$approverSid;?>); <?php } ?>
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
        <?php if($page == 'view') { ?>fetchCompanyApprovers();
        <?php } else if($page == 'add') { ?> loadAddPage();
        <?php } else if($page == 'edit' && $approverSid != null) { ?> loadEditPage(<?=$approverSid;?>); <?php } ?>
        //
        <?php $this->load->view('timeoff/scripts/common'); ?>

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
                type: 'approvers',
                action: 'update_sort_order'
            }, function(resp){
                loader('hide');
            });
        }
    })

</script>
<style>.js-archive-btn > i{ color: #a94442 !important; }</style>
<style>.js-activate-btn > i{ color: #81b431  !important; }</style>