<script type="text/javascript">
    var baseURI = "<?=base_url();?>timeoff/";
    $(function(){
        var
        companyYears = [],
        is_filter = false,
        lastPage = "<?=$page;?>",
        default_slot = 0,
        fetchStatus = 'active',
        policies = [],
        plans = [],
        xhr = null,
        pOBJ = {
            'fetchCompanyHolidays' : {
                page: 1,
                totalPages: 0,
                limit: 0,
                records: 0,
                totalRecords: 0,
                cb: fetchCompanyHolidays
            }
        },
        record = [],
        intervalCatcher = null;

        /* FILTER START */

        // Filter buttons
        $(document).on('click', '.js-apply-filter-btn', applyFilter);
        $(document).on('click', '.js-reset-filter-btn', resetFilter);
        /* FILTER END */

        /* TAB CHANGER START*/
        $(".js-tab").click(function() {
            fetchStatus = $(this).data('type');
            fetchCompanyHolidays();
        });
        /* TAB CHANGER END*/

        fetchCompanyYears();

        /* VIEW PAGE START */
        //
        function resetFilter(e){
            e.preventDefault();
            is_filter = false;
            $('#js-filter-years').select2('val', '<?=date('Y');?>');

            pOBJ['fetchCompanyHolidays']['records'] = [];
            pOBJ['fetchCompanyHolidays']['totalPages'] =
            pOBJ['fetchCompanyHolidays']['totalRecords'] =
            pOBJ['fetchCompanyHolidays']['limit'] = 0;
            pOBJ['fetchCompanyHolidays']['page'] = 1;

            fetchCompanyHolidays();
        }
        //
        function applyFilter(e){
            loader();
            e.preventDefault();
            is_filter = true;
            pOBJ['fetchCompanyHolidays']['records'] = [];
            pOBJ['fetchCompanyHolidays']['totalPages'] =
            pOBJ['fetchCompanyHolidays']['totalRecords'] =
            pOBJ['fetchCompanyHolidays']['limit'] = 0;
            pOBJ['fetchCompanyHolidays']['page'] = 1;

            fetchCompanyHolidays();
        }
        // Fetch plans
        function fetchCompanyHolidays(){
            if(xhr != null) return;
            loader('show');
            $('.js-error-row').remove();
            var megaOBJ = {};
            megaOBJ.page = pOBJ['fetchCompanyHolidays']['page'];
            megaOBJ.action = 'get_company_holidays';
            megaOBJ.companySid = <?=$company_sid;?>;
            megaOBJ.fetchType = fetchStatus;
            megaOBJ.years = $('#js-filter-years').val();

            xhr = $.post(baseURI+'handler', megaOBJ, function(resp) {
                xhr = null;
                //
                if(resp.Status === false && pOBJ['fetchCompanyHolidays']['page'] == 1){
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

                pOBJ['fetchCompanyHolidays']['records'] = resp.Data;
                if(pOBJ['fetchCompanyHolidays']['page'] == 1) {
                    pOBJ['fetchCompanyHolidays']['limit'] = resp.Limit;
                    pOBJ['fetchCompanyHolidays']['totalPages'] = resp.TotalPages;
                    pOBJ['fetchCompanyHolidays']['totalRecords'] = resp.TotalRecords;
                }
                console.log(resp);
                //
                setTable(resp);
            });
        }
        //
        function setTable(resp){
            var title = fetchStatus != 'active' ? 'Activate Holiday' : 'Deactivate Holiday',
            icon = fetchStatus != 'active' ? 'fa-check-square-o' : 'fa-archive',
            cl = fetchStatus != 'active' ? 'js-activate-btn' : 'js-archive-btn',
            rows = '';
            if(resp.Data.length == 0) return;
            //
            $.each(resp.Data, function(i, v){
                rows += '<tr data-id="'+( v.sid )+'">';
                rows += '    <td>';
                rows += '        <div class="text">';
                rows += '            <p>'+( v.holiday_title )+'</p>';
                rows += '        </div>';
                rows += '    </td>';
                rows += '    <td>';
                rows += '        <div class="text">';
                rows += '            <p>'+( getHolidayText(v) )+'</p>';
                rows += '        </div>';
                rows += '    </td>';
                rows += '    <td>';
                rows += '        <div class="text">';
                rows += '            <p>'+( v.holiday_year )+'</p>';
                rows += '        </div>';
                rows += '    </td>';
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
                rows += '            <p>'+( moment(v.created_at, 'YYYY-MM-DD').format(timeoffDateFormat) )+'</p>';
                rows += '        </div>';
                rows += '    </td>';
                rows += '    <td>';
                rows += '        <div class="action-employee">';
                rows += '            <a href="javascript:void(0)" data-toggle="tooltip" title="Edit Holiday" class="action-edit js-edit-row-btn"><i class="fa fa-pencil-square-o fa-fw icon_blue"></i></a>';
                rows += '            <a href="javascript:void(0)" data-toggle="tooltip" title="'+( title )+'" class="action-activate custom-tooltip '+( cl )+'"><i class="fa '+( icon )+' fa-fw "></i></a>';
                rows += '        </div>';
                rows += '    </td>';
                rows += '</tr>';
            });

            //
            load_pagination(
                pOBJ['fetchCompanyHolidays']['limit'],
                5,
                $('.js-ip-pagination'),
                'fetchCompanyHolidays'
            );

            $('#js-data-area').html(rows);
            loader('hide');
        }
        //
        $(document).on('click', '.js-archive-btn', function(e){
            e.preventDefault();
            var _this = $(this);
            alertify.confirm('Do you really want to archive this Holiday?', function(){
                var megaOBJ = {};
                megaOBJ.companySid = <?=$company_sid;?>;
                megaOBJ.action = 'archive_company_holiday';
                megaOBJ.holidaySid = _this.closest('tr').data('id');
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
            alertify.confirm('Do you really want to activate this Holiday?', function(){
                var megaOBJ = {};
                megaOBJ.companySid = <?=$company_sid;?>;
                megaOBJ.action = 'activate_company_holiday';
                megaOBJ.holidaySid = _this.closest('tr').data('id');
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
            setHistory('edit/', baseURI+'holidays/edit/'+($(this).closest('tr').data('id'))+'', 'Edit Page', {}, $(this).closest('tr').data('id'));
        });

        /* VIEW PAGE END */

        /* ADD PAGE START*/
        //
        $('#js-save-add-btn').click(function(e){
            e.preventDefault();
            var megaOBJ = {};
            megaOBJ.companySid = <?=$company_sid;?>;
            megaOBJ.employerSid = <?=$employer_sid;?>;
            megaOBJ.year = $('#js-year-add').val();
            megaOBJ.title = $('#js-holiday-add').val();
            megaOBJ.frequency = $('#js-frequency-add').val();
            megaOBJ.icon = $('#js-holiday-icon-add').val();
            megaOBJ.fromDate = $('#js-from-date-add').val();
            megaOBJ.sortOrder = $('#js-sort-order-add').val();
            megaOBJ.toDate = $('#js-to-date-add').val();
            megaOBJ.action = 'add_company_holiday';
            megaOBJ.archiveCheck = Number($('#js-archive-check-add').prop('checked'));
            // 
            if(megaOBJ.year == '-1' || megaOBJ.year == 0){
                alertify.alert('ERROR!', 'Please select a year.', function(){ return; });
                return;
            }
            // 
            if(megaOBJ.fromDate == ''){
                alertify.alert('ERROR!', 'Please select a date for holiday.', function(){ return; });
                return;
            }
            //
            if(megaOBJ.toDate == ''){
                alertify.alert('ERROR!', 'Please select a date for holiday.', function(){ return; });
                return;
            }
            //
            if(megaOBJ.title == ''){
                alertify.alert('ERROR!', 'Please, add the holiday.', function(){ return; });
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
                alertify.alert('SUCCESS!', resp.Response, function(){ setHistory('view', baseURI+'holidays/view', 'View Page'); });
            });
        });
        /* ADD PAGE END*/

        /* EDIT PAGE END*/
        //
        $('#js-save-edit-btn').click(function(e){
            e.preventDefault();
            var megaOBJ = {};
            megaOBJ.holidaySid = $('#js-id-edit').val();
            megaOBJ.companySid = <?=$company_sid;?>;
            megaOBJ.employerSid = <?=$employer_sid;?>;
            megaOBJ.year = $('#js-year-edit').val();
            megaOBJ.title = $('#js-holiday-edit').val();
            megaOBJ.frequency = $('#js-frequency-edit').val();
            megaOBJ.icon = $('#js-icon-edit').val();
            megaOBJ.fromDate = $('#js-from-date-edit').val();
            megaOBJ.sortOrder = $('#js-sort-order-edit').val();
            megaOBJ.toDate = $('#js-to-date-edit').val();
            megaOBJ.action = 'edit_company_holiday';
            megaOBJ.archiveCheck = Number($('#js-archive-check-edit').prop('checked'));
            // 
            if(megaOBJ.year == '-1' || megaOBJ.year == 0){
                alertify.alert('ERROR!', 'Please select a year.', function(){ return; });
                return;
            }
            // 
            if(megaOBJ.fromDate == ''){
                alertify.alert('ERROR!', 'Please select a date for holiday.', function(){ return; });
                return;
            }
            //
            if(megaOBJ.toDate == ''){
                alertify.alert('ERROR!', 'Please select a date for holiday.', function(){ return; });
                return;
            }
            //
            if(megaOBJ.title == ''){
                alertify.alert('ERROR!', 'Please, add the holiday title.', function(){ return; });
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
                alertify.alert('SUCCESS!', resp.Response, function(){ setHistory('view', baseURI+'holidays/view', 'View Page'); });
            });
        });
        //
        function showEditPage(holiday){
            lastPage = 'edit';
            //
            $('#js-id-edit').val(holiday.sid);
            $('#js-year-edit').select2('val', holiday.holiday_year);
            $('#js-holiday-edit').val(holiday.holiday_title);
            $('#js-frequency-edit').select2('val', holiday.frequency);
            $('#js-icon-edit').val( holiday.icon);
            $('#js-sort-order-edit').val( holiday.sort_order);
            $('#js-from-date-edit').val( holiday.from_date);
            $('#js-to-date-edit').val( holiday.to_date);
            $('#js-archive-check-edit').prop('checked', Boolean(Number(holiday.is_archived)));
            //
            loader('hide');
        }
        /* EDIT PAGE START*/

        // 
        function fetchCompanyYears(){
            $.post(baseURI+'handler', {
                action: 'get_company_holiday_years',
                companySid: <?=$company_sid;?>
            }, function(resp){
                if(resp.Status === false){
                    console.log(resp.Response);
                    return;
                }
                companyYears = resp.Data;
                if(companyYears.length === 0) return;
                var rows = '';
                companyYears.map(function(v){
                    rows += '<option value="'+(v)+'">'+(v)+'</option>';
                });
                $('#js-filter-years').html(rows).select2().select2('val', moment().format('YYYY'));
                rows = '<option value="-1">Please Select a Year</option>'+rows;
                $('#js-year-add').html(rows).select2().select2('val', '-1');
                $('#js-year-edit').html(rows).select2().select2('val', '-1');
            });
        }

        // Page events
        // Page handlers
        $("#js-add-btn").click(function() { setHistory('add', baseURI+'holidays/add', 'Add Page'); });
        $(".js-view-page-btn").click(function() { setHistory('view', baseURI+'holidays/view', 'View Page'); });
        // Pages
        function loadAddPage(){
            if(companyYears.length == 0){
                setTimeout(function(){
                    loadAddPage();
                }, 100);
                return;
            }
            $('.js-page').fadeOut(0);
            // Flush
            $('#js-year-add').select2('val', '-1');
            $('#js-frequency-add').select2().select2('val', 'yearly');
            $('#js-holiday-add').val('');
            $('#js-sort-order-add').val(pOBJ['fetchCompanyHolidays']['records'] == 0 ? 1 : pOBJ['fetchCompanyHolidays']['records'].length + 1);
            $('#js-from-date-add').val('');
            $('#js-to-date-add').val('');
            $('#js-archive-check-add').prop('checked', false);
            $('#js-page-add').fadeIn(500);
            //
            loader('hide');
        }
        function loadViewPage(){
            $('.js-page').fadeOut(0);
            pOBJ['fetchCompanyHolidays']['records'] = [];
            pOBJ['fetchCompanyHolidays']['page'] = 1;
            pOBJ['fetchCompanyHolidays']['limit'] = 0;
            pOBJ['fetchCompanyHolidays']['totalPages'] = 0;
            pOBJ['fetchCompanyHolidays']['totalRecords'] = 0;
            fetchCompanyHolidays();
            $('#js-page-view').fadeIn(500);
        }
        function loadEditPage(sid){
            if(companyYears.length == 0){
                intervalCatcher = setInterval(function(){
                    if(companyYears.length != 0){
                        loadEditPage(sid);
                        clearInterval(intervalCatcher);
                    }
                }, 100);
                return;
            }
            $('.js-page').fadeOut(0);
            $('#js-year-edit').select2('val', '-1');
            $('#js-frequency-edit').select2().select2('val', 'yearly');
            $('#js-holiday-edit').val('');
            $('#js-sort-order-edit').val(pOBJ['fetchCompanyHolidays']['records'] == 0 ? 1 : pOBJ['fetchCompanyHolidays']['records'].length + 1);
            $('#js-from-date-edit').val('');
            $('#js-to-date-edit').val('');
            $('#js-archive-check-edit').prop('checked', false);
            $('#js-page-edit').fadeIn(500);
            $.post(baseURI+'handler', {
                action: 'get_single_company_holiday',
                companySid: <?=$company_sid;?>,
                holidaySid: sid
            }, function(resp){
                if(resp.Status === false){
                    alertify.alert('ERROR!', resp.Response);
                    return;
                }
                showEditPage(resp.Holiday);
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
                <?php if($page == 'view') { ?>fetchCompanyHolidays();
                <?php } else if($page == 'add') { ?> loadAddPage();
                <?php } else if($page == 'edit' && $holidaySid != null) { ?> loadEditPage(<?=$holidaySid;?>); <?php } ?>
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
        <?php if($page == 'view') { ?>fetchCompanyHolidays();
        <?php } else if($page == 'add') { ?> loadAddPage();
        <?php } else if($page == 'edit' && $holidaySid != null) { ?> loadEditPage(<?=$holidaySid;?>); <?php } ?>
        //
        <?php $this->load->view('timeoff/scripts/common'); ?>

        //
        $('#js-year-add').change(function(e){
            $('#js-from-date-add').val(''); 
            $('#js-from-date-add').datepicker('option', 'yearRange', getYearRange()); 
            $('#js-from-date-add').datepicker('option', 'defaultDate', moment().format('MM-DD-')+getYearRange('add', true)); 

            $('#js-to-date-add').val(''); 
            $('#js-to-date-add').datepicker('option', 'yearRange', getYearRange()); 
            $('#js-to-date-add').datepicker('option', 'minDate', moment().format('MM-DD-')+getYearRange('add', true)); 
        });
        
        $('#js-year-edit').change(function(e){
            $('#js-from-date-edit').val(''); 
            $('#js-from-date-edit').datepicker('option', 'yearRange', getYearRange()); 
            $('#js-from-date-edit').datepicker('option', 'defaultDate', moment().format('MM-DD-')+getYearRange('edit', true)); 

            $('#js-to-date-edit').val(''); 
            $('#js-to-date-edit').datepicker('option', 'yearRange', getYearRange()); 
            $('#js-to-date-edit').datepicker('option', 'minDate', moment().format('MM-DD-')+getYearRange('edit', true)); 
        });

        loadPickers();

        //
        function getYearRange(type, single){
            type = type === undefined ? 'add' : type;
            var selection = $('#js-year-'+( type )+'').val();
            //
            if(selection == null || selection == '-1'){
                if(single !== undefined) return moment().format('YYYY');
                return moment().format('YYYY')+':'+moment().format('YYYY');
            }
            //
            if(single !== undefined) return moment(selection).format('YYYY');
            return moment(selection).format('YYYY')+':'+moment(selection).format('YYYY');
        }
        //
        function loadPickers(type){
            if(type == 'add' || type == undefined){
                // Datepickers
                $('#js-from-date-add').datepicker({
                    dateFormat: 'mm-dd-yy',
                    yearRange: getYearRange(),
                    onSelect: function (v) { 
                        $('#js-to-date-add').datepicker('option', 'minDate', v); 
                        $('#js-to-date-add').val(v); 
                    }
                })
                $('#js-to-date-add').datepicker({
                    dateFormat: 'mm-dd-yy',
                    yearRange: getYearRange()
                }).datepicker('option', 'minDate', $('#js-from-date-add').val());
            }
            if(type == 'edit' || type == undefined){
                $('#js-from-date-edit').datepicker({
                    dateFormat: 'mm-dd-yy',
                    yearRange: getYearRange(),
                    onSelect: function (v) { 
                        $('#js-to-date-edit').datepicker('option', 'minDate', v); 
                        $('#js-to-date-edit').val(v); 
                    }
                })
                $('#js-to-date-edit').datepicker({
                    dateFormat: 'mm-dd-yy',
                    yearRange: getYearRange()
                }).datepicker('option', 'minDate', $('#js-from-date-edit').val());
            }
        }

        $('#js-icon-add').click(function(){
            $('#js-holiday-icon-type').val('add');
            $('#js-holiday-modal').modal();
        });

        $('#js-icon-edit').click(function(){
            $('#js-holiday-icon-type').val('edit');
            $('#js-holiday-modal').modal();
        });

        $('#js-icon-remove-add').click(function(){
            $('#js-icon-plc-add').prop('src', false);
            $('#js-icon-plc-box-add').addClass('hidden');
            $('#js-holiday-icon-add').val('');
        });

        $('#js-icon-remove-edit').click(function(){
            $('#js-icon-plc-edit').prop('src', false);
            $('#js-icon-plc-box-edit').addClass('hidden');
            $('#js-holiday-icon-edit').val('');
        });


    })

</script>
<style>.js-archive-btn > i{ color: #a94442 !important; }</style>
<style>.js-activate-btn > i{ color: #81b431  !important; }</style>