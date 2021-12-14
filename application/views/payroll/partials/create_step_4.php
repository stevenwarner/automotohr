<?php  
//
if(!empty($Payroll['employee_compensations'])): 
    //
    $payrollOBJ = [];
    //
    foreach($Payroll['employee_compensations'] as $payrollEmployee):
        //
        if($payrollEmployee['excluded']){
            continue;
        }
        //
        $emp = $PayrollEmployees[$payrollEmployee['employee_id']];
        //
        $tmp = [
            'employeeId' => $payrollEmployee['employee_id'],
            'firstName' => ucwords($emp['first_name']),
            'lastName' => ucwords($emp['last_name']),
            'fixedCompensations' => [],
            'hourlyCompensations' => [],
            'paidTimeOff' => [],
            'reimbursement' => 0.00,
            'reimbursements' => [],
            'rate' => $emp['jobs'][0]['rate'],
            'rateUnit' => $emp['jobs'][0]['payment_unit'],
            'rateByHour' => 0.00,
            'excluded' => false
        ];
        //
        $tmp['rateByHour'] = number_format((float)ResetRate($tmp['rate'], $tmp['rateUnit']), 2);
        //
        if(isset($payrollEmployee['paid_time_off'])){
            $tmp['paidTimeOff'] = $payrollEmployee['paid_time_off'];
        }
        if(isset($payrollEmployee['fixed_compensations'])){
            $tmp['fixedCompensations'] = $payrollEmployee['fixed_compensations'];
        }
        //
        if(isset($payrollEmployee['fixed_compensations']['reimbursement']) && !empty($payrollEmployee['fixed_compensations']['reimbursement'])){
            //
            $tot = 0;
            //
            foreach($payrollEmployee['fixed_compensations']['reimbursement'] as $imb){
                //
                $tot += $imb['amount'];
                //
                $imb['description'] = '';
                $tmp['reimbursements'][] = $imb;
            }
            //
            $tmp['reimbursement'] = $tot;
        }
        //
        if(isset($payrollEmployee['fixed_compensations']['paid-time-off']) && !empty($payrollEmployee['fixed_compensations']['paid-time-off'])){
            //
            $tmp['paid_time_off_amount'] = $payrollEmployee['fixed_compensations']['paid-time-off']['amount'];
        }
        //
        if(isset($payrollEmployee['hourly_compensations']) && !empty($payrollEmployee['hourly_compensations'])){
            $tmp['hourlyCompensations'] = $payrollEmployee['hourly_compensations'];
        } else{
            $tmp['hourlyCompensations']['regular-hours'] = [
                'compensation_multiplier' => 1,
                'job_id' => $emp['jobs'][0]['id'],
                'hours' => (float)number_format(WORK_WEEK_HOURS, 2),
                'name' => 'Regular Hours'
            ];
        }
        //
        $payrollOBJ[$payrollEmployee['employee_id']] = $tmp;
    endforeach;
endif;
?>

<!-- Tabs -->
<div class="jsPageTabContainer">
    <!-- Hours and earnings -->
    <div class="jsPageTab" data-page="hours">
        <!-- Info -->
        <div class="row">
            <div class="col-sm-12">
                <h1 class="csF16 csB7">
                    Time offs
                </h1>
                <p class="csF16">
                   With your time off policies set up, you can now track time off for this pay period below.
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <span class="pull-left">
                    <p class="csF16"><strong>Payroll Period:</strong> 
                        <span class="csFC2">
                            <?=formatDateToDB($Payroll['pay_period']['start_date'], DB_DATE, DATE);?> - 
                            <?=formatDateToDB($Payroll['pay_period']['end_date'], DB_DATE, DATE);?>
                        </span>
                    </p>
                </span>
            </div>
        </div>
        <!-- Box Layout -->
        <div class="csPageBoxBody" style="min-height: 300px;">
            <!-- Main Loader -->
            <div class="csIPLoader jsIPLoader dn" data-page="main_loader">
                <div>
                    <p class="text-center">
                        <i class="fa fa-circle-o-notch fa-spin" aria-hidden="true"></i> <br> <br>
                        <span class="csF16 csB7 jsIPLoaderText">
                            Please wait, while we are generating a preview.
                        </span>
                    </p>
                </div>
            </div>
            <!-- -->
            <div class="jsPayrollContainer">
                <!--  -->
                <table class="table table-responsive table-striped">
                    <caption></caption>
                    <thead>
                        <tr>
                            <th scope="col" class="col-sm-4 csF16 csB7 csBG4 csW">Employee</th>
                            <th scope="col" class="col-sm-2 csF16 csB7 csBG4 csW text-right">Paid Time Off (PTO)</th>
                            <th scope="col" class="col-sm-2 csF16 csB7 csBG4 csW text-right">Total Time Off</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($payrollOBJ as $key => $obj) { ?>
                            <tr>
                                <td class="vam">
                                    <strong><?php echo $obj["lastName"].", ".$obj["firstName"]; ?></strong>
                                </td>
                                <td class="vam text-right">
                                    <!--  -->
                                    <div class="input-group">
                                        <div class="input-group-addon">$</div>
                                        <input type="text" class="form-control jsPaidTimeOffInput" name="paid_time_offs[]" placeholder="0" value="<?php echo $obj["paid_time_off_amount"]; ?>" data-row_id="<?php echo $key; ?>"/>
                                    </div>
                                </td>
                                <td class="vam text-right">
                                    <strong class="csFC4 csF16"><span id="row_id_<?php echo $key; ?>">$<?php echo $obj["paid_time_off_amount"]; ?></span> </strong>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <!--  -->
                <div class="row">
                    <div class="col-sm-12 text-right">
                        <!--  -->
                        <div class="csPB">
                            <button class="btn btn-orange jsPayrollSaveBTN" data-type="next">
                                <i class="fa fa-long-arrow-right" aria-hidden="true"></i>&nbsp;Save & Next
                            </button>
                            <button class="btn btn-black jsPayrollCancelBTN" data-mendatory="true">
                                <i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    //
    var payrollOBJ = <?=json_encode($payrollOBJ);?>;
    //
    var payrollCode = "<?=$payrollId;?>";
    //
    var payrollVersion = "<?=$payrollVersion;?>";
    //

    //
    function upd(type){
        //
        $.post(
            "<?=base_url("payroll/update_payroll");?>", {
                payrollId: payrollCode,
                payrollVersion: payrollVersion,
                payroll: payrollOBJ,
                postType: type,
            }
        ).done(function(resp){
            //
            window.location.href = window.location.href.replace('step=2', 'step=3')

            //
            ml(false, 'main_loader');
        });
    };

    $(".jsPaidTimeOffInput").on("keyup", function () {
        var row_id = $(this).data("row_id");
        var amount = $(this).val();

        if (amount != '' && !/^[0-9.]+$/.test(amount)) {
            alertify.alert("Note", "Only number are accepted");
        } else if (amount < '0') {
            alertify.alert("Note", "Please enter value greater than or equals to 0.");
        } else {
            //
            $("#row_id_"+row_id).text('$'+amount);

            $.each(payrollOBJ, function(i, v){
                //
                if(v.employeeId == row_id) {
                    $.each(v.fixedCompensations, function(i, vfc){
                        if(vfc.name == "Paid Time Off"){
                            vfc.amount = amount;
                        }
                    });
                }
            });
        }
            

        
    });

    $(".jsPayrollSaveBTN").on("click", function () {
        ml(true, 'main_loader');
        var type = $(this).data("type");
        upd(type);
    })
</script>