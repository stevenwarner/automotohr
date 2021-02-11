<style>
    .csModalLoader{
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        width: 100%;
        min-height: 400px;
        background-color: #ffffff;
        z-index: 1;
    }
    .csModalLoader i{
        position: relative;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 50px;
        color: #81b431;
    }
    .csSelect2ErrorLi:hover{
        color: #000000 !important;
    }
</style>
<script>
    //
    let balanceEmployeeId = null;
    let balanceTimeslot = null;
    let balanceFormat = null;
    // startBalanceProcess(58); 
    // Step 1
    async function startBalanceProcess(employeeId, employeeName){
        // Set the employee id
        balanceEmployeeId = employeeId;
        // Load Modal
        let rows = `
        <!-- Modal -->
        <div class="modal fade" id="jsBalanceModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #81b431; color: #ffffff;">
                        <h4 class="modal-title">Time off Balance for ${employeeName}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="csModalLoader jsBPModalLoader"><i class="fa fa-circle-o-notch fa-spin"></i></div>
                        <div class="jsBalancePage" data-step="1">
                            <div class="form-group">
                                <label>Policy <span class="cs-required">*</span></label>
                                <div>
                                    <select id="jsBalancePolicy"></select>
                                </div>
                            </div>
                            <!-- -->
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label>Type <span class="cs-required">*</span></label>
                                        <div>
                                            <select id="jsBalanceType">
                                                <option value="add">Add</option>
                                                <option value="substract">Subtract</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div id="jsBPModalTR"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label>Effective Date <span class="cs-required">*</span></label>
                                        <div>
                                            <input type="text" readonly="true" class="form-control" value="<?=date('m/d/Y');?>" id="jsBalanceEffectDate" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Note <span class="cs-required">*</span></label>
                                <div>
                                    <textarea id="jsBalanceNote" rows="5" class="form-control"></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <p><strong>Note: </strong> Add balance to the selected employee.</p>
                            </div>
                        </div>
                        <!-- -->
                        <div class="jsBalancePage" data-step="2" style="display: none;">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Employee</th>
                                            <th>Policy</th>
                                            <th>Balance</th>
                                            <th>Note</th>
                                            <th>Effective Date</th>
                                            <th>Action Taken</th>
                                        </tr>
                                    </thead>
                                    <tbody id="jsBalanceHistoryTable">
                                        <tr>
                                            <td colspan="5"><p class="text-center alert alert-info">Please wait, while we are fetching balances.</p></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-black" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-success jsBalanceSaveBtn">Save</button>
                        <button type="button" class="btn btn-success jsBalanceHistoryBtn pull-left">Balance History</button>
                        <button type="button" class="btn btn-success jsBalanceAddBtn pull-left">Add Balance</button>
                    </div>
                </div>
            </div>
        </div>`;
        //
        $('#jsBalanceModal').remove();
        //
        $('body').append(rows);
        //
        $('.jsBalanceAddBtn').hide(0);
        //
        $('.jsBPModalLoader').show();
        //
        $('#jsBalanceModal').modal('show');
        //
        $('#jsBalanceEffectDate').datepicker({
            changeMonth: true,
            changeYear: true
        });
        // Get employee policies
        let policies = await fetchManualBalances({
                action: 'get_employee_policies',
                companyId: <?=$company_sid;?>,
                employerId: <?=$employer_sid;?>,
                employeeId: balanceEmployeeId
            });
        //
        if(policies.Status === false){
            $('#jsBalanceModal').modal('hide');
            alertify.alert('ERROR!', 'Please add the policies first as balances are added against employee policies.', () => {});
            return;
        }
        else policies = policies.Data;
        //
        $('#jsBPModalTR').html(getTimeRow(policies[0]['Settings']['Slug']));
        //
        let policyList = {};
        //
        balanceFormat = policies[0]['Settings']['Slug'];
        balanceTimeslot = policies[0]['Settings']['ShiftHours'];
        //
        policies.map(function(policy){
            //
            if(!policyList.hasOwnProperty(policy.Category)) policyList[policy.Category] = [];
            //
            policyList[policy.Category].push(policy);
        });
        //
        policyList  = sortObjectByKey(policyList);
        //
        let policyOptions = '<option value="0">[Select Policy]</option>';
        //
        Object.keys(policyList).map((p) => {
            //
            let policy = policyList[p];
            //
            policyOptions += '<optgroup label="'+(p)+'">';
            //
            policy.map((pi) => {
                //
                policyOptions += `<option ${pi.Reason != '' ? `data-reason="${pi.Reason}"` : ''}" value="${pi.PolicyId}">${pi.Title} (${ pi.RemainingTime.text})</option>`;
            });
            //
            policyOptions += '</optgroup>';
        });
        //
        $('#jsBalancePolicy').html(policyOptions);
        //
        $('#jsBalancePolicy').select2({
            templateResult: (item) => {
                if (!item.id) {
                    return item.text;
                }
                return `<div  ${ $(item.element).data('reason') !== undefined ? 'class="csSelect2BError bg-danger"' : ''} >${item.text} ${ $(item.element).data('reason') !== undefined ? ` <i class="fa fa-question-circle jsBPopover" data-title="Why?" data-content="${$(item.element).data('reason')}"></i>` : ''}</div>`;
            },
            escapeMarkup: (i) => {
                return i;
            }
        });
        //
        $('#jsBalanceType').select2({
            minimumResultsForSearch: -1
        });
        //
        $('.jsBPModalLoader').hide();
    }

    $(document).on('select2:open', '#jsBalancePolicy', function(){
        //
        $('.jsBPopover').popover({
            html: true,
            trigger: 'hover'
        });
        //
        $('.csSelect2BError')
        .parent()
        .removeClass('bg-danger')
        .removeClass('csSelect2ErrorLi');
        //
        $.each($('.csSelect2BError'), function(){
            if($(this).hasClass('bg-danger')) {
            console.log($(this).parent());
                $(this).removeClass('bg-danger');
                $(this).parent()
                .addClass('bg-danger')
                .addClass('csSelect2ErrorLi');
            }
        })
    })


    // Fetch employee policies
    function fetchManualBalances(obj){
        return new Promise((res, rej) => {
            //
            $.post("<?=base_url('timeoff/handler');?>", obj, function(resp){
                res(resp);
            });
        });
    }

    // Get time rows
    function getTimeRow(format){
        //
        var row = '';
        //
        if(format == 'D:H:M'){
            row += '<div class="col-sm-4">';
            row += '    <div class="form-group">';
            row += '        <label>Days </label>';
            row += '        <input type="text" class="form-control js-number" id="jsBalanceDays" />';
            row += '    </div>';
            row += '</div>';
            row += '<div class="col-sm-4">';
            row += '    <div class="form-group">';
            row += '        <label>Hours </label>';
            row += '        <input type="text" class="form-control js-number" id="jsBalanceHours" />';
            row += '    </div>';
            row += '</div>';
            row += '<div class="col-sm-4">';
            row += '    <div class="form-group">';
            row += '        <label>Minutes </label>';
            row += '        <input type="text" class="form-control js-number" id="jsBalanceMinutes" />';
            row += '    </div>';
            row += '</div>';
        }
        else if(format == 'D:H'){
            row += '<div class="col-sm-6">';
            row += '    <div class="form-group">';
            row += '        <label>Days </label>';
            row += '        <input type="text" class="form-control js-number" id="jsBalanceDays" />';
            row += '    </div>';
            row += '</div>';
            row += '<div class="col-sm-6">';
            row += '    <div class="form-group">';
            row += '        <label>Hours </label>';
            row += '        <input type="text" class="form-control js-number" id="jsBalanceHours" />';
            row += '    </div>';
            row += '</div>';
        }
        else if(format == 'H:M'){
            row += '<div class="col-sm-6">';
            row += '    <div class="form-group">';
            row += '        <label>Hours </label>';
            row += '        <input type="text" class="form-control js-number" id="jsBalanceHours" />';
            row += '    </div>';
            row += '</div>';
            row += '<div class="col-sm-6">';
            row += '    <div class="form-group">';
            row += '        <label>Minutes </label>';
            row += '        <input type="text" class="form-control js-number" id="jsBalanceMinutes" />';
            row += '    </div>';
            row += '</div>';
        }
        else if(format == 'H'){
            row += '<div class="col-sm-12">';
            row += '    <div class="form-group">';
            row += '        <label>Hours </label>';
            row += '        <input type="text" class="form-control js-number" id="jsBalanceHours" />';
            row += '    </div>';
            row += '</div>';
        }
        else{
            row += '<div class="col-sm-12">';
            row += '    <div class="form-group">';
            row += '        <label>Minutes </label>';
            row += '        <input type="text" class="form-control js-number" id="jsBalanceMinutes" />';
            row += '    </div>';
            row += '</div>';
        }
        //
        return row;
    }

    // Object sorter
    var sortObjectByKey = function(obj){
        var keys = [];
        var sorted_obj = {};

        for(var key in obj){
            if(obj.hasOwnProperty(key)){
                keys.push(key);
            }
        }

        // sort keys
        keys.sort();

        // create new array based on Sorted Keys
        jQuery.each(keys, function(i, key){
            sorted_obj[key] = obj[key];
        });

        return sorted_obj;
    };

    //
    $(document).on('click', '.jsBalanceSaveBtn', async (e) => {
        //
        e.preventDefault();
        //
        let 
            obj = {};
            days = 0,
            hours = 0,
            minutes = 0;
        // Set policy
        obj.policy = parseInt($('#jsBalancePolicy').val());
        // Set type
        obj.btype = $('#jsBalanceType').val();
        // Set date
        obj.effectedDate = $('#jsBalanceEffectDate').val();
        // Set time
        if($('#jsBalanceDays').length > 0 ) days = $('#jsBalanceDays').val().trim();
        if($('#jsBalanceHours').length > 0 ) hours = $('#jsBalanceHours').val().trim();
        if($('#jsBalanceMinutes').length > 0 ) minutes = $('#jsBalanceMinutes').val().trim();
        //
        if(days < 0) days = 0;
        if(hours < 0) hours = 0;
        if(minutes < 0) minutes = 0;
        //
        days = parseFloat(days);
        hours = parseFloat(hours);
        balanceTimeslot = parseFloat(balanceTimeslot);
        minutes = parseFloat(minutes);
        days = isNaN(days) ? 0 : days;
        hours = isNaN(hours) ? 0 : hours;
        balanceTimeslot = isNaN(balanceTimeslot) ? 0 : balanceTimeslot;
        minutes = isNaN(minutes) ? 0 : minutes;
        //        
        obj.time = (days * balanceTimeslot * 60) + ((hours * 60) + minutes);
        // Set note
        obj.note = $('#jsBalanceNote').val().trim();
        //
        if(obj.policy <= 0){
            alertify.alert('ERROR!', 'Please select a policy.', () => {});
            return;
        }
        //
        if(obj.time <= 0){
            alertify.alert('ERROR!', 'Time cannot be less or equal to 0.', () => {});
            return;
        }
        //
        if(obj.effectedDate == ''){
            alertify.alert('ERROR!', 'Please, select the effected date.', () => {});
            return;
        }
        //
        if(obj.note == ''){
            alertify.alert('ERROR!', 'Please, add a note.', () => {});
            return;
        }
        // Add empployee id to obj
        obj.employeeId = balanceEmployeeId;
        obj.companyId = <?=$company_sid;?>;
        obj.employerId = <?=$employer_sid;?>;
        obj.action = "add_employee_balance";
        //
        $('.jsBalanceSaveBtn').text('Saving...');
        //
        let response = await addBalance(obj);
        //
        if(response.Status === false){
            alertify.alert('ERROR!', resp.Response, () => {});
            return;
        }
        //
        alertify.alert('SUCCESS!', 'Balance added against the selected employee.', () => {
            window.location.reload();
        });
        return;
    });

    //
    function addBalance(obj){
        return new Promise((res, rej) => {
            //
            $.post("<?=base_url('timeoff/handler');?>", obj, (resp) => {
                $('.jsBalanceSaveBtn').text('Save');
                res(resp);
            });
        });
    }

    //
    $(document).on('click', '.jsBalanceHistoryBtn', async () => {
        $('.jsBalanceSaveBtn').hide(0);
        $('.jsBalanceAddBtn').show(0);
        $('.jsBalanceHistoryBtn').hide(0);
        $('.jsBalancePage').fadeOut(0);
        $('.jsBalancePage[data-step="2"]').fadeIn(200);
        //
        let balanceHistory = await fetchBalanceHistory();
        //
        if(balanceHistory.Status === false || balanceHistory.Data.length === 0){
            $('#jsBalanceHistoryTable').html(`
                <tr>
                    <td colspan="6"><p class="text-center alert alert-info">No records found.</p></td>
                </tr>
            `);
            //
            return;
        }
        //
        $('#jsBalanceModal .modal-dialog').addClass('modal-lg');
        //
        $('#jsBalanceHistoryTable').html('');
        //
        balanceHistory.Data.map((bh) => {
            $('#jsBalanceHistoryTable').append(`
                <tr>
                <td>${bh.first_name} ${bh.last_name}<br />${remakeEmployeeName(bh, false)}</td>
                    <td>${bh.title}</td>
                    <td class="${bh.is_added == 0 ? 'text-danger' : 'text-success'}"><i class="fa fa-arrow-${bh.is_added == 0 ? 'down' : 'up'}"></i> ${bh.timeoff_breakdown.text}</td>
                    <td>${bh.note != '' && bh.note != null ?  bh.note : '-'}</td>
                    <td>${moment(bh.effective_at, 'YYYY-MM-DD').format('MMM DD YYYY, ddd')}</td>
                    <td>${moment(bh.created_at, 'YYYY-MM-DD').format('MMM DD YYYY, ddd hh:mm a')}</td>
                </tr>
            `);
        });
    });
    
    //
    $(document).on('click', '.jsBalanceAddBtn', () => {
        $('.jsBalanceAddBtn').hide(0);
        $('.jsBalanceHistoryBtn').show(0);
        $('.jsBalanceSaveBtn').show(0);
        $('.jsBalancePage').fadeOut(0);
        $('#jsBalanceModal .modal-dialog').removeClass('modal-lg');
        $('.jsBalancePage[data-step="1"]').fadeIn(200);
    });

    //
    function fetchBalanceHistory(){
        return new Promise((res, rej) => {
            //
            $.post("<?=base_url('timeoff/handler');?>", {
                action: 'get_employee_balance_history',
                companyId: <?=$company_sid;?>,
                employerId: <?=$employer_sid;?>,
                employeeId: balanceEmployeeId
            }, (resp) => {
                res(resp);
            });
        });
    }
</script>

<style>
    .cs-required{
        font-weight: bold;
        font-size: 14px;
        color: #cc1100;
    }
    .js-number{
        height: 40px;
    }
    #ui-datepicker-div{
        z-index: 1051 !important;
    }
</style>

