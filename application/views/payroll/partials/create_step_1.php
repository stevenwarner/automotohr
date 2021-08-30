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
                        <?=formatDateToDB($Payroll['pay_period']['start_date'], DB_DATE, DATE);?> - 
                        <?=formatDateToDB($Payroll['pay_period']['end_date'], DB_DATE, DATE);?>
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
                                Regular Hours (RH)
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
                            //
                            $payrollOBJ = [];
                            if(!empty($Payroll['employee_compensations'])):
                                foreach($Payroll['employee_compensations'] as $payrollEmployee):
                                    //
                                    $tmp = [
                                        'overtime' => "0.00",
                                        'doubleOvertime' => "0.00",
                                        'bonus' => "0.00",
                                        'cashTips' => "0.00",
                                        'commission' => "0.00",
                                        'correctionPayments' => "0.00",
                                        'reimbursement' => "0.00",
                                        'regularHours' => "0.00",
                                        'total' => "0.00",
                                        'rhTotal' => "0.00",
                                        'compensations' => $payrollEmployee
                                    ];
                                    //
                                    if(isset($payrollEmployee['fixed_compensations']['bonus'])){
                                        $tmp['bonus'] = $payrollEmployee['fixed_compensations']['bonus']['amount'];
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
                                        $tmp['reimbursement'] = $payrollEmployee['fixed_compensations']['reimbursement']['amount'];
                                    }
                                    //
                                    if(isset($payrollEmployee['hourly_compensations']['overtime'])){
                                        $tmp['overtime'] = $payrollEmployee['hourly_compensations']['overtime']['hours'];
                                    }
                                    //
                                    if(isset($payrollEmployee['hourly_compensations']['double-overtime'])){
                                        $tmp['doubleOvertime'] = $payrollEmployee['hourly_compensations']['double-overtime']['hours'];
                                    }
                                    //
                                    if(isset($payrollEmployee['hourly_compensations']['regular-hours'])){
                                        $tmp['regularHours'] = number_format(
                                            $payrollEmployee['hourly_compensations']['regular-hours']['hours'], 2
                                        );
                                    }
                                    //
                                    $payrollOBJ[$payrollEmployee['employee_id']] = $tmp;
                                    ?>
                                    <tr>
                                        <td class="vam">
                                            <label class="control control--checkbox">
                                                <input type="checkbox" id="jsSelectSingle" value="44" />
                                                <div class="control__indicator"  style="margin-top: -12px"></div>
                                            </label>
                                        </td>
                                        <td class="vam">
                                            <p class="csF16">
                                                <?=$payrollEmployee['employee_id'];?>
                                            </p>
                                        </td>
                                        <td class="vam">
                                            <!--  -->
                                            <div class="csPR">
                                                <div class="input-group">
                                                    <span class="input-group-addon">RH</span>
                                                    <input type="text" class="form-control text-right jsInputRewrite" value="<?=$tmp['regularHours'];?>" style="padding-right: 25px;"/>
                                                </div>
                                                <b class="csInputPlaceholder csF14 csB1">hr</b>
                                            </div>
                                            <br>
                                            <a class="csF16 csFC2 csCP">
                                                <i class="fa fa-plus-square" aria-hidden="true"></i>&nbsp;Overtime
                                            </a>
                                        </td>
                                        <td class="vam">
                                            <a class="csF16 csFC2 csCP">
                                                <i class="fa fa-plus-square" aria-hidden="true"></i>&nbsp;Bonus
                                            </a> <br>
                                            <a class="csF16 csFC2 csCP">
                                                <i class="fa fa-plus-square" aria-hidden="true"></i>&nbsp;Commission
                                            </a> <br>
                                            <a class="csF16 csFC2 csCP">
                                                <i class="fa fa-plus-square" aria-hidden="true"></i>&nbsp;Cash Tips
                                            </a> <br>
                                        </td>
                                        <td class="vam">
                                            <p class="csF16">
                                                $2,346.15
                                            </p>
                                            <a class="csF16 csFC2 csCP">
                                                <i class="fa fa-plus-square" aria-hidden="true"></i>&nbsp;Reimbursement
                                            </a> <br> <br>
                                            <p class="csF16">
                                                Pay By: Direct Deposit
                                            </p>
                                        </td>
                                    </tr>
                                    <?php
                                endforeach;
                            endif;
                        ?>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(function(){
        //
        var employeeList = <?=json_encode($payrollOBJ);?>;
        //
        console.log(
            employeeList
        );
        //
        $('.jsInputRewrite').keyup(debounce(function(){
            //
            var nv = parseFloat($(this).val().replace('hrs', '').replace(/[^0-9.]/g, '').trim());
            //
            nv = (isNaN(nv) ? 0.00 : nv).toFixed(2);
            //
            $(this).val(
                nv
            );
        }, 500));
    });
</script>