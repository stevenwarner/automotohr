
<?php  
    //
    if(!empty($Payroll['employee_compensations'])): 
        //
        $payrollOBJ = [];
?>
<!-- Steps -->
<div class="row">
    <div class="col-sm-4">
        <div class="progress mb0">
            <div class="progress-bar csBG2" role="progressbar" aria-valuenow="100" aria-valuemin="0"
                aria-valuemax="100" style="width:100%">
            </div>
        </div>
        <p class="csF14 csB7" style="margin-top: 5px;">1. Hours and earnings</p>
    </div>

    <div class="col-sm-4">
        <div class="progress mb0">
            <div class="progress-bar csBG2" role="progressbar" aria-valuenow="0" aria-valuemin="0"
                aria-valuemax="100" style="width:0%">
            </div>
        </div>
        <p class="csF14 csB7" style="margin-top: 5px;">2. Review and submit</p>
    </div>

    <div class="col-sm-4">
        <div class="progress mb0">
            <div class="progress-bar csBG2" role="progressbar" aria-valuenow="0" aria-valuemin="0"
                aria-valuemax="100" style="width:0%">
            </div>
        </div>
        <p class="csF14 csB7" style="margin-top: 5px;">3. Confirmation</p>
    </div>
</div>
<!-- Tabs -->
<div class="jsPageTabContainer">
    <!-- Hours and earnings -->
    <div class="jsPageTab" data-page="hours">
        <!-- Info -->
        <div class="row">
            <div class="col-sm-12">
                <h1 class="csF16 csB7">
                    Hours and additional earnings
                </h1>
                <p class="csF16">
                    Update your employee's here, reimbursements, and additional settings below.
                </p>
                <p class="csF16">
                    To pay your employees with direct deposit on <span class="csFC2"><?=formatDateToDB($Payroll['check_date'], DB_DATE, DATE);?></span>, you'll need to run payroll by <span class="csFC2">04:00pm PST on <?=formatDateToDB($Payroll['payroll_deadline'], DB_DATE, DATE);?></span>. If you miss this deadline. your employees' direct deposit will be delayed.
                </p>
            </div>
        </div>
         <!-- Info -->
         <div class="row">
            <div class="col-sm-12">
                <span class="pull-left">
                    <p class="csF16"><b>Payroll Period:</b> 
                        <span class="csFC2">
                            <?=formatDateToDB($Payroll['pay_period']['start_date'], DB_DATE, DATE);?> - 
                            <?=formatDateToDB($Payroll['pay_period']['end_date'], DB_DATE, DATE);?>
                        </span>
                    </p>
                </span>
                <span class="pull-right">
                    <button class="btn btn-orange">
                        <i class="fa fa-upload" aria-hidden="true"></i>&nbsp;Upload CSV
                    </button>
                    <button class="btn btn-black">
                        <i class="fa fa-filter" aria-hidden="true"></i>&nbsp;Filter
                    </button>
                </span>
            </div>
        </div>
        <!-- filter -->
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-striped">
                    <caption></caption>
                    <thead>
                        <tr>
                            <th scope="col">
                                <label class="control control--checkbox">
                                    <input type="checkbox" id="jsSelectAll" />
                                    <div class="control__indicator" style="margin-top: -12px"></div>
                                </label>
                            </th>
                            <th scope="col">
                                Employees (<?=count($Payroll['employee_compensations']);?>)
                            </th>
                            <th scope="col">
                                Regular Hours (RH) / Overtime (OT)
                            </th>
                            <th scope="col">
                                Additional Earnings
                            </th>
                            <th scope="col">
                                Gross Pay (GP)
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($Payroll['employee_compensations'] as $payrollEmployee):
                            //
                            $emp = $PayrollEmployees[$payrollEmployee['employee_id']];
                            //
                            $tmp = [
                                'overtimeMultiplier' => "0.00",
                                'overtime' => "0.00",
                                'doubleOvertime' => "0.00",
                                'doubleOvertimeMultiplier' => "0.00",
                                'bonus' => "0.00",
                                'cashTips' => "0.00",
                                'commission' => "0.00",
                                'correctionPayments' => "0.00",
                                'reimbursement' => "0.00",
                                'reimbursements' => [],
                                'regularHours' => number_format(WORK_WEEK_HOURS, 2),
                                'rate' => $emp['jobs'][0]['rate'],
                                'rateUnit' => $emp['jobs'][0]['payment_unit'],
                                'rateByHour' => "0.00",
                                'rhTotal' => "0.00",
                                'employeeId' => $payrollEmployee['employee_id'],
                                'first_name' => ucwords($emp['first_name']),
                                'last_name' => ucwords($emp['last_name'])
                            ];
                            //
                            $ot = 
                            $dot = 0;
                            //
                            $tmp['rateByHour'] = (float) ResetRate($tmp['rate'], $tmp['rateUnit']);
                            //
                            if(isset($payrollEmployee['fixed_compensations']['bonus'])){
                                $tmp['bonus'] = (float) $payrollEmployee['fixed_compensations']['bonus']['amount'];
                            }
                            //
                            if(isset($payrollEmployee['fixed_compensations']['cash-tips'])){
                                $tmp['cashTips'] = $payrollEmployee['fixed_compensations']['cash-tips']['amount'];
                            }
                            //
                            if(isset($payrollEmployee['fixed_compensations']['commission'])){
                                $tmp['commission'] = $payrollEmployee['fixed_compensations']['commission']['amount'];
                            }
                            //
                            if(isset($payrollEmployee['fixed_compensations']['reimbursement'])){
                                //
                                $payrollEmployee['fixed_compensations']['reimbursement'][] = [
                                    'description' => 'Additional Amount',
                                    'amount' => 20.00
                                ];
                                //
                                foreach($payrollEmployee['fixed_compensations']['reimbursement'] as $reimbursement){
                                    //
                                    $tmp['reimbursement'] += $reimbursement['amount'];
                                    $tmp['reimbursements'][] = [
                                        'description' => 'Additional Amount',
                                        'amount' => $reimbursement['amount']
                                    ];
                                    //
                                    $tmp['reimbursement'] += (float) $reimbursement['amount'];
                                }
                            }
                            //
                            if(isset($payrollEmployee['hourly_compensations']['overtime'])){
                                $tmp['overtime'] = (float) $payrollEmployee['hourly_compensations']['overtime']['hours'];
                                $ot = $tmp['rate'] * $tmp['overtime'] * $payrollEmployee['hourly_compensations']['overtime']['compensation_multiplier'];
                                $tmp['overtimeMultiplier'] = $payrollEmployee['hourly_compensations']['overtime']['compensation_multiplier'];
                            }
                            //
                            if(isset($payrollEmployee['hourly_compensations']['double-overtime'])){
                                $tmp['doubleOvertime'] = (float) $payrollEmployee['hourly_compensations']['double-overtime']['hours'];
                                $dot = $tmp['rate'] * $tmp['overtime'] * $payrollEmployee['hourly_compensations']['double-overtime']['compensation_multiplier'];
                                $tmp['doubleOvertimeMultiplier'] = $payrollEmployee['hourly_compensations']['double-overtime']['compensation_multiplier'];
                            }
                            //
                            if(isset($payrollEmployee['hourly_compensations']['regular-hours'])){
                                $tmp['regularHours'] = number_format(
                                    $payrollEmployee['hourly_compensations']['regular-hours']['hours'], 2
                                );
                            }
                            //
                            $tmp['rhTotal'] = number_format(
                                ($tmp['rateByHour'] * $tmp['regularHours']) +
                                ($ot) +
                                ($dot) +
                                ($tmp['bonus']) +
                                ($tmp['cashTips']) +
                                ($tmp['commission']) +
                                ($tmp['reimbursement']), 2
                            );
                            //
                            $payrollOBJ[$payrollEmployee['employee_id']] = $tmp;
                            ?>
                            <tr data-id="<?=$payrollEmployee['employee_id'];?>">
                                <td class="vam">
                                    <label class="control control--checkbox">
                                        <input type="checkbox" id="jsSelectSingle" value="44" />
                                        <div class="control__indicator"  style="margin-top: -18px"></div>
                                    </label>
                                </td>
                                <td class="vam">
                                    <p class="csF16">
                                        <b>
                                            <?=$tmp['last_name'].', '.$tmp['first_name'];?>
                                        </b>
                                    </p>
                                    <p class="csF16">
                                        $<?=number_format($tmp['rate'], 2);?> /<?=$tmp['rateUnit'];?>
                                    </p>
                                </td>
                                <td class="vam">
                                    <a class="csF16 csCP jsPayrollRHBTN">
                                        <i class="fa fa-edit" aria-hidden="true"></i>&nbsp; <?=$tmp['regularHours'];?> hr
                                    </a>
                                    <br>
                                    <!--  -->
                                    <div class="csPR jsPayrollRHBox dn">
                                        <div class="input-group">
                                            <span class="input-group-addon csCI" title="Regular Hours (RH)" placement="top">RH</span>
                                            <input type="text" id="jsPayrollRH<?=$payrollEmployee['employee_id'];?>" class="form-control text-right jsInputRH" value="<?=$tmp['regularHours'];?>" style="padding-right: 25px;"/>
                                        </div>
                                        <b class="csInputPlaceholder csF14 csB1">hr</b>
                                    </div>
                                    <br>
                                    <a class="csF16 csFC2 csCP jsPayrollOvertimeBTN">
                                        <i class="fa fa-plus-square" aria-hidden="true"></i>&nbsp;Overtime
                                    </a>
                                    <div class="jsPayrollOvertimeBox dn">
                                        <div class="csPR">
                                            <div class="input-group">
                                                <span class="input-group-addon csCI" title="Overtime (OT)" placement="top">OT</span>
                                                <input type="text" id="jsPayrollOT<?=$payrollEmployee['employee_id'];?>" class="form-control text-right jsInputOT" value="<?=$tmp['overtime'];?>" style="padding-right: 25px;"/>
                                            </div>
                                            <b class="csInputPlaceholder csF14 csB1">hr</b>
                                        </div>
                                        <br>
                                        <div class="csPR">
                                            <div class="input-group">
                                                <span class="input-group-addon csCI" title="Double Overtime (DOT)" placement="top">DOT</span>
                                                <input type="text" id="jsPayrollDOT<?=$payrollEmployee['employee_id'];?>" class="form-control text-right jsInputDOT" value="<?=$tmp['doubleOvertime'];?>" style="padding-right: 25px;"/>
                                            </div>
                                            <b class="csInputPlaceholder csF14 csB1">hr</b>
                                        </div>
                                    </div>
                                </td>
                                <td class="vam">
                                    <div class="jsPayrollBonusBox dn">
                                        <div class="csPR">
                                            <div class="input-group">
                                                <span class="input-group-addon csCI" title="Bonus" placement="top">B</span>
                                                <input type="text" id="jsPayrollB<?=$payrollEmployee['employee_id'];?>" class="form-control text-right jsInputB" value="<?=$tmp['bonus'];?>" style="padding-right: 25px;"/>
                                            </div>
                                            <b class="csInputPlaceholder csF14 csB1">hr</b>
                                        </div>
                                    </div>
                                    <a class="csF16 csFC2 csCP jsPayrollBonusBTN">
                                        <i class="fa fa-plus-square" aria-hidden="true"></i>&nbsp;Bonus
                                        <br>
                                    </a> <br>
                                    <div class="jsPayrollCashTipsBox dn">
                                        <div class="csPR">
                                            <div class="input-group">
                                                <span class="input-group-addon csCI" title="Cash Tips" placement="top">C</span>
                                                <input type="text" id="jsPayrollC<?=$payrollEmployee['employee_id'];?>" class="form-control text-right jsInputCT" value="<?=$tmp['cashTips'];?>" style="padding-right: 25px;"/>
                                            </div>
                                            <b class="csInputPlaceholder csF14 csB1">hr</b>
                                        </div>
                                    </div>
                                    <a class="csF16 csFC2 csCP jsPayrollCashTipsBTN">
                                        <i class="fa fa-plus-square" aria-hidden="true"></i>&nbsp;Cash Tips
                                        <br>
                                    </a> <br>
                                    <a class="csF16 csFC2 csCP jsPayrollOtherEarnings">
                                        <i class="fa fa-plus-square" aria-hidden="true"></i>&nbsp;Other Earnings
                                        <br>
                                    </a> <br>
                                </td>
                                <td class="vam">
                                    <p class="csF16">
                                        <b id="jsPayrollRowTotal<?=$payrollEmployee['employee_id'];?>">
                                            $<?=$tmp['rhTotal'];?>
                                        </b>
                                    </p>
                                    <a class="csF16 csFC2 csCP jsPayrollReimbursement">
                                        <i class="fa fa-plus-square" aria-hidden="true"></i>&nbsp;Reimbursement
                                    </a> <br> <br>
                                    <p class="csF16">
                                        Pay By: Direct Deposit
                                    </p>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!--  -->
        <div class="csPB">
            <span class="pull-right">
                <button class="btn btn-orange jsPayrollSaveBTN">
                    <i class="fa fa-save" aria-hidden="true"></i>&nbsp;Save
                </button>
                <button class="btn btn-orange jsPayrollSaveAndNextBTN">
                    <i class="fa fa-long-arrow-right" aria-hidden="true"></i>&nbsp;Save & Next
                </button>
                <button class="btn btn-black jsPayrollCancelBTN">
                    <i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;Cancel
                </button>
            </span>
        </div>
    </div>
</div>

<script>
    $(function(){
        //
        var payrollOBJ = <?=json_encode($payrollOBJ);?>;
        //
        var payrollCode = "<?=$payrollId;?>";
        //
        $('.jsInputCMN').keyup(function(){
            //
            var nv = $(this).val().replace(/[^0-9.]/g, '');
            //
            $(this).val(
                isNaN(nv) ? 0.00 : nv
            );
        });
        //
        $('.jsInputRH').keyup(function(){
            //
            var nv = $(this).val().replace(/[^0-9.]/g, '');
            //
            $(this).val(
                isNaN(nv) ? 0.00 : nv
            );
            //
            UpdatePayrollRow(
                $(this).closest('tr').data('id'), 
                'regularHours',
                nv
            );
        });
        //
        $('.jsInputOT').keyup(function(){
            //
            var nv = $(this).val().replace(/[^0-9.]/g, '');
            //
            $(this).val(
                isNaN(nv) ? 0.00 : nv
            );
            //
            UpdatePayrollRow(
                $(this).closest('tr').data('id'), 
                'overtime',
                nv
            );
        });
        //
        $('.jsInputDOT').keyup(function(){
            //
            var nv = $(this).val().replace(/[^0-9.]/g, '');
            //
            $(this).val(
                isNaN(nv) ? 0.00 : nv
            );
            //
            UpdatePayrollRow(
                $(this).closest('tr').data('id'), 
                'doubleOverTime',
                nv
            );
        });
        //
        $('.jsInputB').keyup(function(){
            //
            var nv = $(this).val().replace(/[^0-9.]/g, '');
            //
            $(this).val(
                isNaN(nv) ? 0.00 : nv
            );
            //
            UpdatePayrollRow(
                $(this).closest('tr').data('id'), 
                'bonus',
                nv
            );
        });
        //
        $('.jsInputCT').keyup(function(){
            //
            var nv = $(this).val().replace(/[^0-9.]/g, '');
            //
            $(this).val(
                isNaN(nv) ? 0.00 : nv
            );
            //
            UpdatePayrollRow(
                $(this).closest('tr').data('id'), 
                'cashTips',
                nv
            );
        });
        //
        $('.jsPayrollOvertimeBTN').click(function(event){
            //
            event.preventDefault();
            //
            $(this).hide();
            //
            $(this).closest('tr').find('.jsPayrollOvertimeBox').removeClass('dn');
        });
        //
        $('.jsPayrollRHBTN').click(function(event){
            //
            event.preventDefault();
            //
            $(this).hide();
            //
            $(this).closest('tr').find('.jsPayrollRHBox').removeClass('dn');
        });
        //
        $('.jsPayrollBonusBTN').click(function(event){
            //
            event.preventDefault();
            //
            $(this).hide();
            //
            $(this).closest('tr').find('.jsPayrollBonusBox').removeClass('dn');
        });
        //
        $('.jsPayrollCashTipsBTN').click(function(event){
            //
            event.preventDefault();
            //
            $(this).hide();
            //
            $(this).closest('tr').find('.jsPayrollCashTipsBox').removeClass('dn');
        });
        //
        $(document).on('click', '.jsPayrollEOSaveBTN', function(event){
            //
            event.preventDefault();
            //
            ml(true, 'jsPayrollOEModelLoader');
            //
            var id = $(this).data('id');
            //
            payrollOBJ[id]['commission'] = $('#jsPayrollCommission'+id).val().trim();
            payrollOBJ[id]['correctionPayments'] = $('#jsPayrollCorrectionPayment'+id).val().trim();
            payrollOBJ[id]['cashTips'] = $('#jsPayrollPaycheckTips'+id).val().trim();
            //
            UpdatePayrollRow(id);
            //
            ml(false, "jsPayrollOEModelLoader");
            //
            $('#jsPayrollOEModel .jsModalCancel').click();
        });
        //
        $('.jsPayrollOtherEarnings').click(function(event){
            //
            event.preventDefault();
            //
            const singleEmployee = payrollOBJ[$(this).closest('tr').data('id')];
            //
            var html = '';
            html += '<!-- Other Earning -->';
            html += '<div class="container csPageWrap">';
            html += '    <div class="row">';
            html += '        <div class="col-sm-12">';
            html += '            <p class="csF16">';
            html += '                '+(singleEmployee.first_name+' '+singleEmployee.last_name)+' will be paid the amounts below in addition to their regular wages.';
            html += '            </p>';
            html += '        </div>';
            html += '    </div><br>';
            html += '    <div class="row">';
            html += '        <div class="col-sm-12">';
            html += '            <p class="csF16 csB7">';
            html += '                Commission';
            html += '            </p>';
            html += '            <p class="csF16">';
            html += '                The amount of commission pay the employee received for this pay period.';
            html += '            </p>';
            html += '            <div class="input-group">';
            html += '                <span class="input-group-addon">$</span>';
            html += '                <input type="text" id="jsPayrollCommission'+(singleEmployee.employeeId)+'" value="'+(singleEmployee.commission)+'" class="form-control jsInputCMN" style="padding-right: 25px;"/>';
            html += '            </div>';
            html += '        </div>';
            html += '    </div><br>';
            html += '    <div class="row">';
            html += '        <div class="col-sm-12">';
            html += '            <p class="csF16 csB7">';
            html += '                Correction Payment';
            html += '            </p>';
            html += '            <p class="csF16">';
            html += '                Correction payment for this pay period. This amount will be added to gross wages, and taxed as regular income.';
            html += '            </p>';
            html += '            <div class="input-group">';
            html += '                <span class="input-group-addon">$</span>';
            html += '                <input type="text" id="jsPayrollCorrectionPayment'+(singleEmployee.employeeId)+'" value="'+(singleEmployee.correctionPayments)+'" class="form-control jsInputCMN" style="padding-right: 25px;"/>';
            html += '            </div>';
            html += '        </div>';
            html += '    </div><br>';
            html += '    <div class="row">';
            html += '        <div class="col-sm-12">';
            html += '            <p class="csF16 csB7">';
            html += '                Paycheck Tips';
            html += '            </p>';
            html += '            <p class="csF16">';
            html += '                Paycheck tips (service charges) to be paid to the employee this pay period. This amount will be added to the employee\'s gross pay.';
            html += '            </p>';
            html += '            <div class="input-group">';
            html += '                <span class="input-group-addon">$</span>';
            html += '                <input type="text" id="jsPayrollPaycheckTips'+(singleEmployee.employeeId)+'" value="'+(singleEmployee.cashTips)+'" class="form-control jsInputCMN" style="padding-right: 25px;"/>';
            html += '            </div>';
            html += '        </div>';
            html += '    </div><br>';
            html += '    <div class="row">';
            html += '        <div class="col-sm-12">';
            html += '            <span class="pull-right">';
            html += '                <button class="btn btn-orange jsPayrollEOSaveBTN" data-id="'+(singleEmployee.employeeId)+'">';
            html += '                    <i class="fa fa-save" aria-hidden="true"></i>&nbsp;Save';
            html += '                </button>';
            html += '                <button class="btn btn-black jsModalCancel">';
            html += '                    <i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;Cancel';
            html += '                </button>';
            html += '            </span>';
            html += '        </div>';
            html += '    </div>';
            html += '</div>';
            //
            Modal({
                Title: "Other Earnings for "+(singleEmployee.last_name+', '+singleEmployee.first_name),
                Id: "jsPayrollOEModel",
                Loader:"jsPayrollOEModelLoader",
                Body: '<div id="jsPayrollOEModel">'+(html)+'</div>'
            }, function(){
                //
                ml(false, "jsPayrollOEModelLoader")
            });
        });
        //
        $('.jsPayrollReimbursement').click(function(event){
            //
            event.preventDefault();
            //
            const singleEmployee = payrollOBJ[$(this).closest('tr').data('id')];
            //
            var html = '';
            html +='<div class="container csPageWrap jsPayrollReimbursementWrap" data-id="'+(singleEmployee.employeeId)+'">';
            html +='    <div class="row">';
            html +='        <div class="col-sm-12">';
            html +='            <p class="csF16">';
            html +='                Below are all reimbursements to be paid to this employee this pay period.';
            html +='            </p>';
            html +='        </div>';
            html +='    </div>';
            html +='    <br>';
            html +='    <!--  -->';
            html +='    <div class="panel panel-theme">';
            html +='        <div class="panel-heading csBC3">';
            html +='            <h1 class="csF16 csB7 csW">';
            html +='                One-time Reimbursements';
            html +='                <span class="pull-right">';
            html +='                    <b class="jsPayrollReimbursementTotalAmount">$0.00</b>';
            html +='                </span>';
            html +='            </h1>';
            html +='        </div>';
            html +='        <!--  -->';
            html +='        <div class="panel-body">';
            html +='            <!--  -->';
            html +='            <div class="jsPayrollReimbursementBox">';
            html +='            </div>';
            html +='            <!--  -->';
            html +='            <div class="row">';
            html +='                <div class="col-sm-12 text-center">';
            html +='                    <button class="btn btn-orange jsPayrollReimbursementBTN">';
            html +='                        <i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;Add Reimbursement';
            html +='                    </button>';
            html +='                </div>';
            html +='            </div>';
            html +='        </div>';
            html +='        <div class="panel-footer">';
            html +='            <div class="row">';
            html +='                <div class="col-sm-6 col-xs-12">';
            html +='                    <p class="csF16 csB7 text-left">';
            html +='                        Total';
            html +='                    </p>';
            html +='                </div>';
            html +='                <div class="col-sm-6 col-xs-12">';
            html +='                    <p class="csF16 csB7 text-right jsPayrollReimbursementTotalAmount">';
            html +='                        $0.00';
            html +='                    </p>';
            html +='                </div>';
            html +='            </div>';
            html +='        </div>';
            html +='    </div>';
            html += '<div class="row">';
            html += '    <div class="col-sm-12 text-right">';
            html += '        <button class="btn btn-orange jsPayrollReimbursementSaveBTN">';
            html += '            <i class="fa fa-save" aria-hidden="true"></i>&nbsp;Save';
            html += '        </button>';
            html += '        <button class="btn btn-black jsModalCancel">';
            html += '            <i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;Cancel';
            html += '        </button>';
            html += '    </div>';
            html += '</div>';
            html +='</div>';
            //
            Modal({
                Title: "Other Earnings for "+(singleEmployee.last_name+', '+singleEmployee.first_name),
                Id: "jsPayrollOEModel",
                Loader:"jsPayrollOEModelLoader",
                Body: '<div id="jsPayrollOEModel">'+(html)+'</div>'
            }, function(){
                //
                var totalReimbursement = 0;
                //
                if(singleEmployee.reimbursements.length > 0){
                    singleEmployee.reimbursements.map(function(reim){
                        //
                        totalReimbursement += parseFloat(reim.amount);
                        //
                        $('.jsPayrollReimbursementBox').append(GetImbursementRow(reim));
                    });
                }
                //
                $('.jsPayrollReimbursementTotalAmount').text('$'+(numberFormat(totalReimbursement.toFixed(2))));
                //
                ml(false, "jsPayrollOEModelLoader");
            });
        });

        //
        $('.jsPayrollCancelBTN').click(function(event){
            //
            event.preventDefault();
            //
            alertify.confirm(
                "Any unsaved changes will be lost. Do you wish to continue?",
                function(){
                    window.location = window.location.origin +'/payroll/create';
                }
            );
        });

        //
        $(document).on('click', '.jsPayrollReimbursementBTN', function(event){
            //
            event.preventDefault();
            //
            $('.jsPayrollReimbursementBox').append(GetImbursementRow());
        });

        //
        $(document).on('click', '.jsPayrollReimbursementRowRemoveBTN', function(event){
            //
            event.preventDefault();
            //
            var id = $(this).closest('.jsPayrollReimbursementRow').data('id');
            //
            $(this).closest('.jsPayrollReimbursementRow').remove();
            //
            CalculateReimbursment();
        });

        //
        $(document).on('keyup', '.jsPayrollReimbursementRowAmount', CalculateReimbursment);

        //
        $(document).on('click', '.jsPayrollReimbursementSaveBTN', function(event){
            //
            event.preventDefault();
            //
            ml(true, 'jsPayrollOEModelLoader');
            //
            var id = $('.jsPayrollReimbursementWrap').data('id');
            //
            var ReimbursmentArray = [];
            //
            var total = 0;
            //
            $('.jsPayrollReimbursementRow').map(function(){
                //
                total += parseFloat($(this).find('.jsPayrollReimbursementRowAmount').val().trim());
                //
                ReimbursmentArray.push({
                    description: $(this).find('.jsPayrollReimbursementRowDescription').val().trim(),
                    amount: parseFloat($(this).find('.jsPayrollReimbursementRowAmount').val().trim())
                });
            });
            //
            payrollOBJ[id]['reimbursements'] = ReimbursmentArray;
            //
            UpdatePayrollRow(id, 'reimbursement', total);
            //
            $('#jsPayrollOEModel .jsModalCancel').click();
            //
            ml(false, 'jsPayrollOEModelLoader');
        });

        //
        function CalculateReimbursment(){
            //
            var id = $('.jsPayrollReimbursementWrap').data('id');
            //
            var total = 0;
            //
            $('.jsPayrollReimbursementRow').map(function(){
                //
                total += parseFloat($(this).find('.jsPayrollReimbursementRowAmount').val().trim());
            });
            //
            $('.jsPayrollReimbursementTotalAmount').text('$'+(numberFormat(total.toFixed(2))));

        }

        //
        function GetImbursementRow(data){
            //
            var html = '';
            //
            html +='<div class="jsPayrollReimbursementRow">';
            html +='    <div class="row">';
            html +='        <div class="col-sm-8">';
            html +='            <label class="csF16 csB7">';
            html +='                Description';
            html +='            </label>';
            html +='            <p class="csF14">';
            html +='                If you choose not to add a description, this amount will be labeled “Additional amount.”';
            html +='            </p>';
            html +='            <input type="text" class="form-control jsPayrollReimbursementRowDescription" placeholder="Description (Optional)" '+( data !== undefined ? 'value="'+(data.description)+'"' : '' )+' />';
            html +='        </div>';
            html +='        <div class="col-sm-3">';
            html +='            <label class="csF16 csB7">';
            html +='                Amount <i class="fa fa-asterisk csInfo" aria-hidden="true"></i>';
            html +='            </label>';
            html +='            <p class="csF14">';
            html +='                Amount to reimburse';
            html +='            </p>';
            html +='            <div class="input-group">';
            html +='                <span class="input-group-addon csCI">$</span>';
            html +='                <input type="text" class="form-control text-right jsPayrollReimbursementRowAmount" placeholder="0.00" '+( data !== undefined ? 'value="'+(data.amount)+'"' : '' )+'  />';
            html +='            </div>';
            html +='        </div>';
            html +='        <div class="col-sm-1"><label>&nbsp;</label><p>&nbsp;</p>';
            html +='            <button class="btn btn-danger jsPayrollReimbursementRowRemoveBTN">';
            html +='                <i class="fa fa-times-circle" aria-hidden="true"></i>';
            html +='            </button>';
            html +='        </div>';
            html +='    </div>';
            html +='    <br>';
            html +='</div>';
            //
            return html;
        }
        
        //
        function UpdatePayrollRow(
            employeeId,
            key,
            value
        ){
            //
            if(key !== undefined){
                //
                payrollOBJ[employeeId][key] = value;
            }
            //
            const singleEmployee = payrollOBJ[employeeId];
            //
            var total = 0;
            //
            total = parseFloat(singleEmployee.rateByHour * $('#jsPayrollRH'+employeeId).val().trim());
            
            //
            total += parseFloat(
                ($('#jsPayrollOT'+employeeId).val().trim() * singleEmployee.overtimeMultiplier) * singleEmployee.rateByHour
            );
            //
            total += parseFloat(
                ($('#jsPayrollDOT'+employeeId).val().trim() * singleEmployee.doubleOvertimeMultiplier) * singleEmployee.rateByHour
            );
            //
            total += parseFloat(
                singleEmployee.commission
            );
            //
            total += parseFloat(
                singleEmployee.correctionPayments
            );
            //
            total += parseFloat(
                singleEmployee.cashTips
            );
            //
            total += parseFloat(
                singleEmployee.reimbursement
            );
            
            //
            $('#jsPayrollRowTotal'+employeeId).text('$'+numberFormat(total.toFixed(2)));
        }

        //
        window.payrollOBJ =payrollOBJ;
    });
</script>

<?php else: ?>
    <p class="alert alert-info text-center csF16 csB7">
        No employees qualify for the selected payroll <br><br>
        <a href="<?=base_url('payroll/create');?>" class="btn btn-orange">Go To Payrolls</a>
    </p>
<?php
    endif;
?>