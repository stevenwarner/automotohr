
<script type="text/javascript">
    var baseURI = "<?=base_url();?>timeoff/";
    $(function(){
        var
        employees = [],
        departments = [],
        is_filter = false,
        default_slot = 0,
        fetchStatus = 'active',
        policies = [],
        plans = [],
        xhr = null,
        pOBJ = {
            'fetchBalances' : {
                page: 1,
                totalPages: 0,
                limit: 0,
                records: 0,
                totalRecords: 0,
                cb: fetchBalances
            }
        },
        record = [],
        intervalCatcher = null;

        /* FILTER START */
        fetchBalances();

        // Filter buttons
        $(document).on('click', '.js-apply-filter-btn', applyFilter);
        $(document).on('click', '.js-reset-filter-btn', resetFilter);
        /* FILTER END */

        /* VIEW PAGE START */
        //
        function resetFilter(e){
            e.preventDefault();
            is_filter = false;
            $('#js-filter-employee').select2('val', 'all');

            pOBJ['fetchBalances']['records'] = [];
            pOBJ['fetchBalances']['totalPages'] =
            pOBJ['fetchBalances']['totalRecords'] =
            pOBJ['fetchBalances']['limit'] = 0;
            pOBJ['fetchBalances']['page'] = 1;

            fetchBalances();
        }
        //
        function applyFilter(e){
            loader();
            e.preventDefault();
            is_filter = true;
            pOBJ['fetchBalances']['records'] = [];
            pOBJ['fetchBalances']['totalPages'] =
            pOBJ['fetchBalances']['totalRecords'] =
            pOBJ['fetchBalances']['limit'] = 0;
            pOBJ['fetchBalances']['page'] = 1;

            fetchBalances();
        }
        // Fetch plans
        function fetchBalances(){
            if(xhr != null) return;
            loader('show');
            $('.js-error-row').remove();
            var megaOBJ = {};
            megaOBJ.page = pOBJ['fetchBalances']['page'];
            megaOBJ.action = 'get_balance_sheet';
            megaOBJ.companySid = <?=$company_sid;?>;
            megaOBJ.employeeSid = <?=$employerData['sid'];?>;
            megaOBJ.isSuper = <?=$employerData['access_level_plus'];?>;
            megaOBJ.searchedEmployeeSid = is_filter ? $('#js-filter-employee').val() : '';
            megaOBJ.fetchType = fetchStatus;

            xhr = $.post(baseURI+'handler', megaOBJ, function(resp) {
                xhr = null;
                //
                if(resp.Status === false && pOBJ['fetchBalances']['page'] == 1){
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

                pOBJ['fetchBalances']['records'] = resp.Data;
                if(pOBJ['fetchBalances']['page'] == 1) {
                    pOBJ['fetchBalances']['limit'] = 50;
                    pOBJ['fetchBalances']['totalPages'] = 1;
                    pOBJ['fetchBalances']['totalRecords'] = resp.Data.Balances.length;
                }
                //
                setTable(resp);
            });
        }
        //
        function setTable(resp){
            if(resp.Data.Balances.length == 0) return;
            var rows = '';
            if(employees.length == 0){
                employees = resp.Data.Employees;
                employees.map(function(v){
                    rows += '<option value="'+( v.employee_id )+'">'+( remakeEmployeeName( v ) )+'</option>';
                });
                rows = '<option value="all">All</option>'+rows;
                $('#js-filter-employee').html(rows);
                $('#js-filter-employee').select2();
            }

            rows = '';
            //
            $.each(resp.Data.Balances, function(i, v){
                rows += '<tr data-id="'+( v.employee_id )+'" data-name="'+(  remakeEmployeeName(v) )+'">';
                rows += '    <td scope="row">';
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
                rows += '            <p>'+( v.joined_at == null ? 'N/A' : moment(v.joined_at, 'YYYY-MM-DD').format(timeoffDateFormat) )+'</p>';
                rows += '        </div>';
                rows += '    </td>';
                rows += '    <td>';
                rows += '        <div class="text">';
                rows += '            <p>'+( v.Details === undefined || v.Details.length == 0 ? 'N/A' : v.Details.Total.text )+'</p>';
                rows += '        </div>';
                rows += '    </td>';
                rows += '    <td>';
                rows += '        <div class="text">';
                rows += '            <p>'+( v.Details === undefined || v.Details.length == 0 ? 'N/A' : v.Details.Consumed.text )+'</p>';
                rows += '        </div>';
                rows += '    </td>';
                rows += '    <td>';
                rows += '        <div class="text">';
                rows += '            <p>'+( v.Details === undefined || v.Details.length == 0 ? 'N/A' : v.Details.Pending.text )+'</p>';
                rows += '        </div>';
                rows += '    </td>';
                <?php if($session['employer_detail']['access_level_plus'] == 1 || $session['employer_detail']['pay_plan_flag'] == 1) { ?>
                rows += '    <td style="vertical-align: middle" align="left">';
                let k = Math.ceil(Math.random(1,9) * 2000);
                rows += `
                <div class="dropdown show">
                    <a class="btn btn-success dropdown-toggle" href="#" role="button" id="dropdownMenuLink${k}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                        <span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink${k}" style="left: auto; right: 0;">
                        <li><a class="js-balance-btn" href="javascript:void(0);">Manage Balance</a></li>
                        <li><a class="js-view-policies" href="javascript:void(0)">View Policies</a></li>
                    </ul>
                </div>
                `;
                <?php } ?>
                rows += '</tr>';
            });

            //
            load_pagination(
                pOBJ['fetchBalances']['limit'],
                5,
                $('.js-ip-pagination'),
                'fetchBalances'
            );

            $('#js-data-area').html(rows);
            loader('hide');
        }

        //
        $(document).on('click', '.js-balance-btn', function(e){
            e.preventDefault();
            //
            startBalanceProcess(
                $(this).closest('tr').data('id'),
                $(this).closest('tr').data('name')
            );
        });

        //
        $(document).on('click', '.js-view-policies', function(e) {
            //
            e.preventDefault();
            //
            startPolicyProcess(
                $(this).closest('tr').data('id'),
                $(this).closest('tr').data('name')
            );
        });
        //
        <?php $this->load->view('timeoff/scripts/common'); ?>
    })

</script>
