<?php  
//
if(!empty($Payroll['employee_compensations'])): 
    //
    $payrollOBJ = [];
    //
    foreach($Payroll['employee_compensations'] as $payrollEmployee):
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
            'rateByHour' => 0.00
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
?>
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
        <!-- Box Layout -->
        <div class="csPageBoxBody" style="min-height: 300px;">
            <!-- Main Loader -->
            <div class="csIPLoader jsIPLoader" data-page="main_loader">
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
            <div class="jsPayrollContainer"></div>
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
        var payrollVersion = "<?=$payrollVersion;?>";

        // Triggers

        /**
         *  Regular Hours Edit
         *  Click
         */  
        $(document).on('click', '.jsPayrollRowEditRHE, .jsPayrollRowEditRHP', function(event){
            //
            event.preventDefault();
            //
            const boxREF = $(this).closest('.jsPayrollRowId');
            //
            boxREF.find('.csPageBoxBody').addClass('dn');
            boxREF.find('.portionSections .csPageBoxBody[data-portion="regular_hours"]').removeClass('dn');
        });

        /**
         *  Regular Hours Save
         *  Click
         */  
        $(document).on('click', '.jsPayrollRHSaveBTN', function(event){
            //
            event.preventDefault();
            //
            const employeeId = $(this).closest('.jsPayrollRowId').data('id');
            //
            ml(true, 'jsPayrollEditLoader'+employeeId)
            //
            const boxREF = $(this).closest('.jsPayrollRowId');
            //
            let value = boxREF.find('.jsPayrollRHInput').val().trim();
            //
            if(parseInt(value) == 0){
                value = <?=WORK_WEEK_HOURS;?>;
            }
            //
            payrollOBJ[employeeId]['regularHours'] = parseFloat(value);
            //
            UpdatePayrollRow(employeeId);
            //
            boxREF.find('.csPageBoxBody').removeClass('dn');
            boxREF.find('.portionSections .csPageBoxBody').addClass('dn');
        });

        /**
         *  Overtime Edit
         *  Click
         */  
        $(document).on('click', '.jsPayrollRowEditOTE, .jsPayrollRowEditOTP', function(event){
            //
            event.preventDefault();
            //
            const boxREF = $(this).closest('.jsPayrollRowId');
            //
            boxREF.find('.csPageBoxBody').addClass('dn');
            boxREF.find('.portionSections .csPageBoxBody[data-portion="overtime"]').removeClass('dn');
        });

        /**
         *  Overtime Save
         *  Click
         */  
        $(document).on('click', '.jsPayrollOTSaveBTN', function(event){
            //
            event.preventDefault();
            //
            const employeeId = $(this).closest('.jsPayrollRowId').data('id');
            //
            ml(true, 'jsPayrollEditLoader'+employeeId)
            //
            const boxREF = $(this).closest('.jsPayrollRowId');
            //
            payrollOBJ[employeeId]['hourlyCompensations']['overtime']['hours'] = parseFloat(boxREF.find('.jsPayrollOTInput').val().trim());
            //
            UpdatePayrollRow(employeeId);
            //
            boxREF.find('.csPageBoxBody').removeClass('dn');
            boxREF.find('.portionSections .csPageBoxBody').addClass('dn');
        });

        /**
         *  Double Overtime Edit
         *  Click
         */  
        $(document).on('click', '.jsPayrollRowEditDOTE, .jsPayrollRowEditDOTP', function(event){
            //
            event.preventDefault();
            //
            const boxREF = $(this).closest('.jsPayrollRowId');
            //
            boxREF.find('.csPageBoxBody').addClass('dn');
            boxREF.find('.portionSections .csPageBoxBody[data-portion="double_overtime"]').removeClass('dn');
        });

        /**
         * Double  Overtime Save
         *  Click
         */  
        $(document).on('click', '.jsPayrollDOTSaveBTN', function(event){
            //
            event.preventDefault();
            //
            const employeeId = $(this).closest('.jsPayrollRowId').data('id');
            //
            ml(true, 'jsPayrollEditLoader'+employeeId)
            //
            const boxREF = $(this).closest('.jsPayrollRowId');
            //
            payrollOBJ[employeeId]['hourlyCompensations']['double-overtime']['hours'] = parseFloat(boxREF.find('.jsPayrollDOTInput').val().trim());
            //
            UpdatePayrollRow(employeeId);
            //
            boxREF.find('.csPageBoxBody').removeClass('dn');
            boxREF.find('.portionSections .csPageBoxBody').addClass('dn');
        });

        /**
         *  Bonus Edit
         *  Click
         */  
        $(document).on('click', '.jsPayrollRowEditBE, .jsPayrollRowEditBP', function(event){
            //
            event.preventDefault();
            //
            const boxREF = $(this).closest('.jsPayrollRowId');
            //
            boxREF.find('.csPageBoxBody').addClass('dn');
            boxREF.find('.portionSections .csPageBoxBody[data-portion="bonus"]').removeClass('dn');
        });

        /**
         *  Bonus Save
         *  Click
         */  
        $(document).on('click', '.jsPayrollBSaveBTN', function(event){
            //
            event.preventDefault();
            //
            const employeeId = $(this).closest('.jsPayrollRowId').data('id');
            //
            ml(true, 'jsPayrollEditLoader'+employeeId)
            //
            const boxREF = $(this).closest('.jsPayrollRowId');
            //
            payrollOBJ[employeeId]['fixedCompensations']['bonus']['amount'] = parseFloat(boxREF.find('.jsPayrollBInput').val().trim());
            //
            UpdatePayrollRow(employeeId);
            //
            boxREF.find('.csPageBoxBody').removeClass('dn');
            boxREF.find('.portionSections .csPageBoxBody').addClass('dn');
        });

        /**
         *  Cash Tips Edit
         *  Click
         */  
        $(document).on('click', '.jsPayrollRowEditCTE, .jsPayrollRowEditCTP', function(event){
            //
            event.preventDefault();
            //
            const boxREF = $(this).closest('.jsPayrollRowId');
            //
            boxREF.find('.csPageBoxBody').addClass('dn');
            boxREF.find('.portionSections .csPageBoxBody[data-portion="cash_tips"]').removeClass('dn');
        });

        /**
         *  Cash Tips Save
         *  Click
         */  
        $(document).on('click', '.jsPayrollCTSaveBTN', function(event){
            //
            event.preventDefault();
            //
            const employeeId = $(this).closest('.jsPayrollRowId').data('id');
            //
            ml(true, 'jsPayrollEditLoader'+employeeId)
            //
            const boxREF = $(this).closest('.jsPayrollRowId');
            //
            payrollOBJ[employeeId]['fixedCompensations']['cash-tips']['amount'] = parseFloat(boxREF.find('.jsPayrollCTInput').val().trim());
            //
            UpdatePayrollRow(employeeId);
            //
            boxREF.find('.csPageBoxBody').removeClass('dn');
            boxREF.find('.portionSections .csPageBoxBody').addClass('dn');
        });

        /**
         *  Portion Cancel
         *  Click
         */  
        $(document).on('click', '.jsPayrollCancelBTN', function(event){
            //
            event.preventDefault();
            //
            const boxREF = $(this).closest('.jsPayrollRowId');
            //
            boxREF.find('.csPageBoxBody').removeClass('dn');
            boxREF.find('.portionSections .csPageBoxBody').addClass('dn');
        });
       
        /**
         *  Reset input field
         *  keyup
         */  
        $(document).on('keyup', '.jsPayrollRHInput, .jsPayrollOTInput, .jsPayrollDOTInput, .jsPayrollBInput, .jsPayrollCTInput', function(){
            //
            const nv = $(this).val().replace(/[^0-9.]/g, '');
            //
            $(this).val(
                isNaN(nv) ? 0.00 : nv
            );
        });

        /**
         * Reimbursement Model
         * Click
         */
        $(document).on('click', '.jsPayrollRowEditR, .jsPayrollRowEditRE', function(event){
            //
            event.preventDefault();
            //
            const payrollEmployee = payrollOBJ[$(this).closest('.jsPayrollRowId').data('id')];
            //
            var html = '';
            html +='<div class="container csPageWrap jsPayrollReimbursementWrap" data-id="'+(payrollEmployee.employeeId)+'">';
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
                Title: "Other Earnings for "+(payrollEmployee.lastName+', '+payrollEmployee.firstName),
                Id: "jsPayrollOEModel",
                Loader:"jsPayrollOEModelLoader",
                Body: '<div id="jsPayrollOEModel">'+(html)+'</div>'
            }, function(){
                //
                var totalReimbursement = 0;
                //
                if(payrollEmployee.reimbursements.length > 0){
                    payrollEmployee.reimbursements.map(function(reim){
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

        /**
         * Reimbursement Data Update
         * Click
         */
        $(document).on('keyup', '.jsPayrollReimbursementRowAmount', CalculateReimbursment);

        /**
         * Reimbursement Row Add
         * Click
         */
        $(document).on('click', '.jsPayrollReimbursementBTN', function(event){
            //
            event.preventDefault();
            //
            $('.jsPayrollReimbursementBox').append(GetImbursementRow());
        });

        /**
         * Reimbursement Row Remove
         * Click
         */
        $(document).on('click', '.jsPayrollReimbursementRowRemoveBTN', function(event){
            //
            event.preventDefault();
            //
            $(this).closest('.jsPayrollReimbursementRow').remove();
            //
            CalculateReimbursment();
        });

        /**
         * Save Reimbursements
         * Click
         */
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
                let tv = $(this).find('.jsPayrollReimbursementRowAmount').val().trim() || 0;
                //
                total += parseFloat(tv);
                //
                ReimbursmentArray.push({
                    description: $(this).find('.jsPayrollReimbursementRowDescription').val().trim(),
                    amount: parseFloat(tv)
                });
            });
            //
            payrollOBJ[id]['reimbursements'] = ReimbursmentArray;
            payrollOBJ[id]['reimbursement'] = total;
            //
            UpdatePayrollRow(id);
            //
            $('#jsPayrollOEModel .jsModalCancel').click();
            //
            ml(false, 'jsPayrollOEModelLoader');
        });

        /**
         * Other Earning Model
         * Click
         */
        $(document).on('click', '.jsPayrollRowEditOE, .jsPayrollRowEditOEP', function(event){
            //
            event.preventDefault();
            //
            const payrollEmployee = payrollOBJ[$(this).closest('.jsPayrollRowId').data('id')];
            //
            var html = '';
            html += '<!-- Other Earning -->';
            html += '<div class="container csPageWrap">';
            html += '    <div class="row">';
            html += '        <div class="col-sm-12">';
            html += '            <p class="csF16">';
            html += '                '+(payrollEmployee.firstName+' '+payrollEmployee.lastName)+' will be paid the amounts below in addition to their regular wages.';
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
            html += '                <input type="text" id="jsPayrollCommission'+(payrollEmployee.employeeId)+'" value="'+(payrollEmployee.commission)+'" class="form-control jsInputCMN" style="padding-right: 25px;"/>';
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
            html += '                <input type="text" id="jsPayrollCorrectionPayment'+(payrollEmployee.employeeId)+'" value="'+(payrollEmployee.correctionPayments)+'" class="form-control jsInputCMN" style="padding-right: 25px;"/>';
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
            html += '                <input type="text" id="jsPayrollPaycheckTips'+(payrollEmployee.employeeId)+'" value="'+(payrollEmployee.cashTips)+'" class="form-control jsInputCMN" style="padding-right: 25px;"/>';
            html += '            </div>';
            html += '        </div>';
            html += '    </div><br>';
            html += '    <div class="row">';
            html += '        <div class="col-sm-12">';
            html += '            <span class="pull-right">';
            html += '                <button class="btn btn-orange jsPayrollEOSaveBTN" data-id="'+(payrollEmployee.employeeId)+'">';
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
                Title: "Other Earnings for "+(payrollEmployee.lastName+', '+payrollEmployee.firstName),
                Id: "jsPayrollOEModel",
                Loader:"jsPayrollOEModelLoader",
                Body: '<div id="jsPayrollOEModel">'+(html)+'</div>'
            }, function(){
                //
                ml(false, "jsPayrollOEModelLoader")
            });
        });

        /**
         * Save Other Earnings
         * Click
         */
        $(document).on('click', '.jsPayrollEOSaveBTN', function(event){
            //
            event.preventDefault();
            //
            ml(true, 'jsPayrollOEModelLoader');
            //
            var employeeId = $(this).data('id');
            //
            payrollOBJ[employeeId]['fixedCompensations']['commission']['amount'] = parseFloat($('#jsPayrollCommission'+employeeId).val().trim() || 0);
            payrollOBJ[employeeId]['fixedCompensations']['correction-payment']['amount'] = parseFloat($('#jsPayrollCorrectionPayment'+employeeId).val().trim() || 0);
            payrollOBJ[employeeId]['fixedCompensations']['paycheck-tips']['amount'] = parseFloat($('#jsPayrollPaycheckTips'+employeeId).val().trim() || 0);
            //
            UpdatePayrollRow(employeeId);
            //
            ml(false, "jsPayrollOEModelLoader");
            //
            $('#jsPayrollOEModel .jsModalCancel').click();
        });

        /**
         * Shift to another page
         * Click
         */
        $(document).on('click', '.jsPayrollCancelBTN', function(event){
            //
            event.preventDefault();
            //
            if($(this).data('mendatory') !== undefined){
                //
                alertify.confirm(
                    "Any unsaved changes will be lost. Do you wish to continue?",
                    function(){
                        window.location = window.location.origin +'/payroll/create';
                    }
                );
            }
        });

        /**
         * 
         */
        $(document).on('click', '.jsPayrollSaveBTN', function(event){
            //
            event.preventDefault();
            //
            ml(true, 'main_loader', 'Please wait, while we are updating payrolls.');
            //
            const doNext = $(this).data('type');
            //
            $.post(
                "<?=base_url("payroll/update_payroll");?>", {
                    payrollId: payrollCode,
                    payrollVersion: payrollVersion,
                    payroll: payrollOBJ
                }
            ).done(function(resp){
                //
                if(!resp.Status){
                    alertify.alert(
                        'Error!',
                        resp.Message
                    );
                    return;
                }
                alertify.alert(
                    'Success!',
                    'The payroll has been successfully updated.',
                    function(){
                        ml(false, 'main_loader');
                        if(doNext === undefined){
                            window.location.reload();
                        } else{
                            window.location = window.location.origin +'/'+window.location.pathname+'?step=2';
                        }
                    }
                );
            });
        });


        // Functions
        
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
            const payrollEmployee = payrollOBJ[employeeId];
            // Get reference of box
            const boxREF = $('.jsPayrollRowId[data-id="'+(employeeId)+'"]');

            // Reset the index to be used
            payrollEmployee.regularHours = 0.00;
            payrollEmployee.overtime = 0.00;
            payrollEmployee.overtimeMultiplier = 0.00;
            payrollEmployee.doubleOvertime = 0.00;
            payrollEmployee.doubleOvertimeMultiplier = 0.00;
            payrollEmployee.bonus = 0.00;
            payrollEmployee.cashTips = 0.00;
            payrollEmployee.correctionPayments = 0.00;
            payrollEmployee.commission = 0.00;
            payrollEmployee.paycheckTips = 0.00;
            //
            payrollEmployee.regularHours = parseFloat(payrollEmployee.hourlyCompensations['regular-hours']['hours']);
            //
            if(payrollEmployee.hourlyCompensations.overtime !== undefined){
                payrollEmployee.overtime = parseFloat(payrollEmployee.hourlyCompensations['overtime']['hours']);
                payrollEmployee.overtimeMultiplier = parseFloat(payrollEmployee.hourlyCompensations['overtime']['compensation_multiplier']);
            }
            //
            if(payrollEmployee.hourlyCompensations['double-overtime'] !== undefined){
                payrollEmployee.doubleOvertime = parseFloat(payrollEmployee.hourlyCompensations['double-overtime']['hours']);
                payrollEmployee.doubleOvertimeMultiplier = parseFloat(payrollEmployee.hourlyCompensations['double-overtime']['compensation_multiplier']);
            }
            //
            payrollEmployee.bonus = payrollEmployee.fixedCompensations['bonus'] !== undefined ? parseFloat(payrollEmployee.fixedCompensations['bonus']['amount']) : 0.00;
            payrollEmployee.cashTips = payrollEmployee.fixedCompensations['cash-tips'] !== undefined ? parseFloat(payrollEmployee.fixedCompensations['cash-tips']['amount']): 0.00;
            payrollEmployee.correctionPayments = payrollEmployee.fixedCompensations['correction-payment'] !== undefined ? parseFloat(payrollEmployee.fixedCompensations['correction-payment']['amount']): 0.00;
            payrollEmployee.commission = payrollEmployee.fixedCompensations['commission'] !== undefined ? parseFloat(payrollEmployee.fixedCompensations['commission']['amount']): 0.00;
            payrollEmployee.paycheckTips = payrollEmployee.fixedCompensations['paycheck-tips'] !== undefined ? parseFloat(payrollEmployee.fixedCompensations['paycheck-tips']['amount']) : 0.00;

            // Let's set the regular hours
            boxREF.find('.jsPayrollRowEditRHValue').text("$"+ payrollEmployee.regularHours.toFixed(2));
            boxREF.find('.jsPayrollRHInput').val(payrollEmployee.regularHours.toFixed(2));
            // Let's set the overtime
            boxREF.find('.jsPayrollRowEditOTValue').text("$"+ payrollEmployee.overtime.toFixed(2));
            boxREF.find('.jsPayrollOTInput').val(payrollEmployee.overtime.toFixed(2));
            //
            if(parseInt(payrollEmployee.overtime) != 0){
                boxREF.find('.jsPayrollRowEditOTP').addClass('dn');
                boxREF.find('.jsPayrollRowEditOTE').removeClass('dn');
            } else{
                boxREF.find('.jsPayrollRowEditOTP').removeClass('dn');
                boxREF.find('.jsPayrollRowEditOTE').addClass('dn');
            }

            // Let's set the double overtime
            boxREF.find('.jsPayrollRowEditDOTValue').text("$"+ payrollEmployee.doubleOvertime.toFixed(2));
            boxREF.find('.jsPayrollDOTInput').val(payrollEmployee.doubleOvertime.toFixed(2));
            //
            if(parseInt(payrollEmployee.doubleOvertime) != 0){
                boxREF.find('.jsPayrollRowEditDOTP').addClass('dn');
                boxREF.find('.jsPayrollRowEditDOTE').removeClass('dn');
            } else{
                boxREF.find('.jsPayrollRowEditDOTP').removeClass('dn');
                boxREF.find('.jsPayrollRowEditDOTE').addClass('dn');
            }

            // Let's set the bonus
            boxREF.find('.jsPayrollRowEditBValue').text("$"+ payrollEmployee.bonus.toFixed(2));
            boxREF.find('.jsPayrollBInput').val(payrollEmployee.bonus.toFixed(2));
            //
            if(parseInt(payrollEmployee.bonus) != 0){
                boxREF.find('.jsPayrollRowEditBP').addClass('dn');
                boxREF.find('.jsPayrollRowEditBE').removeClass('dn');
            } else{
                boxREF.find('.jsPayrollRowEditBP').removeClass('dn');
                boxREF.find('.jsPayrollRowEditBE').addClass('dn');
            }

            // Let's set the cash tips
            boxREF.find('.jsPayrollRowEditCTValue').text("$"+ payrollEmployee.cashTips.toFixed(2));
            boxREF.find('.jsPayrollCTInput').val(payrollEmployee.cashTips.toFixed(2));
            //
            if(parseInt(payrollEmployee.cashTips) != 0){
                boxREF.find('.jsPayrollRowEditCTP').addClass('dn');
                boxREF.find('.jsPayrollRowEditCTE').removeClass('dn');
            } else{
                boxREF.find('.jsPayrollRowEditCTP').removeClass('dn');
                boxREF.find('.jsPayrollRowEditCTE').addClass('dn');
            }
            
            // Let's set the additional earnings
            const additonalEarnings = 
            parseFloat(payrollEmployee.correctionPayments) + 
            parseFloat(payrollEmployee.commission) + 
            parseFloat(payrollEmployee.paycheckTips);
            //
            boxREF.find('.jsPayrollRowEditOEValue').text("$"+ additonalEarnings.toFixed(2));
            //
            if(parseInt(additonalEarnings) != 0){
                boxREF.find('.jsPayrollRowEditOEP').addClass('dn');
                boxREF.find('.jsPayrollRowEditOEE').removeClass('dn');
            } else{
                boxREF.find('.jsPayrollRowEditOEP').removeClass('dn');
                boxREF.find('.jsPayrollRowEditOEE').addClass('dn');
            }

            // Let's set the reimbursements
            boxREF.find('.jsPayrollRowEditRValue').text("$"+ payrollEmployee.reimbursement.toFixed(2));
            boxREF.find('.jsPayrollRInput').val(payrollEmployee.reimbursement.toFixed(2));
            //
            if(parseInt(payrollEmployee.reimbursement) != 0){
                boxREF.find('.jsPayrollRowEditRP').addClass('dn');
                boxREF.find('.jsPayrollRowEditRE').removeClass('dn');
            } else{
                boxREF.find('.jsPayrollRowEditRP').removeClass('dn');
                boxREF.find('.jsPayrollRowEditRE').addClass('dn');
            }

            //
            const grossPay = numberFormat(
                (
                    parseFloat(payrollEmployee.rateByHour * payrollEmployee.regularHours) +
                    parseFloat(payrollEmployee.rateByHour * (payrollEmployee.overtimeMultiplier * payrollEmployee.overtime)) +
                    parseFloat(payrollEmployee.rateByHour * (payrollEmployee.doubleOvertimeMultiplier * payrollEmployee.doubleOvertime)) +
                    additonalEarnings +
                    payrollEmployee.bonus +
                    payrollEmployee.cashTips +
                    parseFloat(payrollEmployee.reimbursement)
                ).toFixed(2)
            );

            //
            boxREF.find('.jsPayrollRowEditGPValue').text("$"+ grossPay);

            //
            ml(false, 'jsParyrollRowLoader'+employeeId);
        }
        
        // 
        function MakeView(){
            //
            var html = '';
            //
            html += '<div class="row">';
            //
            $.each(payrollOBJ, function(i, v){
                //
                html += GetSingleView(v);
            });
            //
            html += '</div>';
            // Add Buttons
            html +='<!--  -->';
            html +='<div class="csPB">';
            html +='    <span class="pull-right">';
            html +='        <button class="btn btn-orange jsPayrollSaveBTN">';
            html +='            <i class="fa fa-save" aria-hidden="true"></i>&nbsp;Save';
            html +='        </button>';
            html +='        <button class="btn btn-orange jsPayrollSaveBTN" data-type="next">';
            html +='            <i class="fa fa-long-arrow-right" aria-hidden="true"></i>&nbsp;Save & Next';
            html +='        </button>';
            html +='        <button class="btn btn-black jsPayrollCancelBTN" data-mendatory="true">';
            html +='            <i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;Cancel';
            html +='        </button>';
            html +='    </span>';
            html +='</div>';
            //
            $('.jsPayrollContainer').html(html);
            //
            ml(false, 'main_loader');
            //
            $.each(payrollOBJ, function(i, v){
                //
                html += GetSingleView(v);
                //
                UpdatePayrollRow(v.employeeId);
            });
            //
            loadTitles();
        }

        //
        function GetSingleView(payrollEmployee){
            //
            var html = '';
            //
            html += '    <div class="col-md-4 col-sm-12 col-xs-12">';
            html += '        <div class="csPageBox csRadius5 jsPayrollRowId" style="height: 500px; overflow-y: auto; overflow-x: hidden;"  data-id="'+(payrollEmployee.employeeId)+'">';
            html += '            <div class="csPageBoxHeader csBG4 p10">';
            html += '                <div class="row">';
            html += '                    <div class="col-sm-6 col-xs-12">';
            html += '                        <p class="csF16 csB7 csW mb0">';
            html +=                           payrollEmployee.lastName+', '+payrollEmployee.firstName;
            html += '                        </p>';
            html += '                    </div>';
            html += '                    <div class="col-sm-6 col-xs-12">';
            html += '                        <p class="csF16 csB7 csW mb0 text-right">';
            html +=                             '$'+numberFormat(payrollEmployee.rate)+' /'+(payrollEmployee.rateUnit.toLowerCase());
            html += '                        </p>';
            html += '                    </div>';
            html += '                </div>';
            html += '            </div>';
            html += '            <div class="csPR">';
            html += '                <!--  -->';
            html += '                <div class="csIPLoader jsIPLoader" data-page="jsParyrollRowLoader'+(payrollEmployee.employeeId)+'">';
            html += '                    <i class="fa fa-circle-o-notch fa-spin" aria-hidden="true"></i>';
            html += '                </div>';
            html += '                <!-- Main -->';
            html += '                <div class="csPageBoxBody p10" >';
            html += '                    <!-- RH -->';
            html += '                    <div class="row">';
            html += '                        <div class="col-sm-6 col-xs-12">';
            html += '                            <p class="csF16 csB7">';
            html += '                                Regular Hours (RH) <i class="fa fa-info-circle csF16 csCP jsPayrollRowEditRH"';
            html += '                                placement="top" ';
            html += '                                title="Employee worked hours." ';
            html += '                                aria-hidden="true"></i>';
            html += '                            </p>';
            html += '                        </div>';
            html += '                        <div class="col-sm-6 col-xs-12 text-right">';
            html += '                            <p class="csF16 csB1 csFC2 csCP jsPayrollRowEditRH jsPayrollRowEditRHE" title="Update Regular Hours" placement="true">';
            html += '                                <i class="fa fa-edit" aria-hidden="true"></i>&nbsp;<span class="jsPayrollRowEditRHValue">$0.00</span>';
            html += '                            </p>';
            if(payrollEmployee.hourlyCompensations['overtime'] !== undefined){
                html += '                            <p class="csF16 csB1 csFC2 csCP jsPayrollRowEditOT jsPayrollRowEditOTP">';
                html += '                                <i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;Overtime';
                html += '                            </p>';
                html += '                            <p class="csF16 csB1 csFC2 csCP jsPayrollRowEditOT jsPayrollRowEditOTE" title="Update Overtime" placement="true">';
                html += '                                <i class="fa fa-edit" aria-hidden="true"></i>&nbsp;<span class="jsPayrollRowEditOTValue">$0.00</span> (OT)';
                html += '                            </p>';
            }
            //
            if(payrollEmployee.hourlyCompensations['double-overtime'] !== undefined){
                html += '                            <p class="csF16 csB1 csFC2 csCP jsPayrollRowEditDOT jsPayrollRowEditDOTP">';
                html += '                                <i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;Double Overtime';
                html += '                            </p>';
                html += '                            <p class="csF16 csB1 csFC2 csCP jsPayrollRowEditDOT jsPayrollRowEditDOTE" title="Update Double Overtime" placement="true">';
                html += '                                <i class="fa fa-edit" aria-hidden="true"></i>&nbsp;<span class="jsPayrollRowEditDOTValue">$0.00</span> (DOT)';
                html += '                            </p>';
            }
            html += '                        </div>';
            html += '                    </div>';
            html += '                    <br>';
            html += '                    <!-- AH -->';
            html += '                    <div class="row">';
            html += '                        <div class="col-sm-6 col-xs-12">';
            html += '                            <p class="csF16 csB7">';
            html += '                                Additional Earnings <i class="fa fa-info-circle csF16 csCP" ';
            html += '                                placement="top" ';
            html += '                                title="Additional earnings include bonuses, cash tips, commissions, correction payments, and paycheck tips.';
            html += '                                aria-hidden="true"></i>';
            html += '                            </p>';
            html += '                        </div>';
            html += '                        <div class="col-sm-6 col-xs-12 text-right">';
            html += '                            <p class="csF16 csB1 csFC2 csCP jsPayrollRowEditB jsPayrollRowEditBP">';
            html += '                                <i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;Bonus';
            html += '                            </p>';
            html += '                            <p class="csF16 csB1 csFC2 csCP jsPayrollRowEditB jsPayrollRowEditBE" title="Update Bonus" placement="true">';
            html += '                                <i class="fa fa-edit" aria-hidden="true"></i>&nbsp;<span class="jsPayrollRowEditBValue">$0.00</span> (B)';
            html += '                            </p>';
            html += '                            <p class="csF16 csB1 csFC2 csCP jsPayrollRowEditCT jsPayrollRowEditCTP">';
            html += '                                <i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;Cash Tips';
            html += '                            </p>';
            html += '                            <p class="csF16 csB1 csFC2 csCP jsPayrollRowEditCT jsPayrollRowEditCTE" title="Update Cash Tips" placement="true">';
            html += '                                <i class="fa fa-edit" aria-hidden="true"></i>&nbsp;<span class="jsPayrollRowEditCTValue">$0.00</span> (CT)';
            html += '                            </p>';
            html += '                            <p class="csF16 csB1 csFC2 csCP jsPayrollRowEditOE jsPayrollRowEditOEP">';
            html += '                                <i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;Other Earnings';
            html += '                            </p>';
            html += '                            <p class="csF16 csB1 csFC2 csCP jsPayrollRowEditOE jsPayrollRowEditOEE" title="Update Other Earnings" placement="true">';
            html += '                                <i class="fa fa-edit" aria-hidden="true"></i>&nbsp;<span class="jsPayrollRowEditOEValue">$0.00</span> (OE)';
            html += '                            </p>';
            html += '                        </div>';
            html += '                    </div>';
            html += '                    <br>';
            html += '                    <!-- Reimbursement -->';
            html += '                    <div class="row">';
            html += '                        <div class="col-sm-6 col-xs-12">';
            html += '                            <p class="csF16 csB7">';
            html += '                                Reimbursement <i class="fa fa-info-circle csF16 csCP" ';
            html += '                                placement="top" ';
            html += '                                title="Add multiple one-time reimbursements." ';
            html += '                                aria-hidden="true"></i>';
            html += '                            </p>';
            html += '                        </div>';
            html += '                        <div class="col-sm-6 col-xs-12 text-right">';
            html += '                            <p class="csF16 csB1 csFC2 csCP jsPayrollRowEditR jsPayrollRowEditRP">';
            html += '                                <i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;Reimbursement';
            html += '                            </p>';
            html += '                            <p class="csF16 csB1 csFC2 csCP jsPayrollRowEditR jsPayrollRowEditRE">';
            html += '                                <i class="fa fa-edit" aria-hidden="true"></i>&nbsp;<span class="jsPayrollRowEditRValue">$0.00</span>';
            html += '                            </p>';
            html += '                        </div>';
            html += '                    </div>';
            html += '                    <br>';
            html += '                    <!-- Payment Mode -->';
            html += '                    <div class="row">';
            html += '                        <div class="col-sm-6 col-xs-12">';
            html += '                            <p class="csF16 csB7">';
            html += '                                Pay By <i class="fa fa-info-circle csF16 csCP" ';
            html += '                                placement="top" ';
            html += '                                title="Payment type; how the employee is going to get paid." ';
            html += '                                aria-hidden="true"></i>';
            html += '                            </p>';
            html += '                        </div>';
            html += '                        <div class="col-sm-6 col-xs-12 text-right">';
            html += '                            <p class="csF16">';
            html += '                                Direct Deposit';
            html += '                            </p>';
            html += '                        </div>';
            html += '                    </div>';
            html += '                    <br>';
            html += '                    <!-- GP -->';
            html += '                    <div class="row">';
            html += '                        <div class="col-sm-6 col-xs-12">';
            html += '                            <p class="csF16 csB7">';
            html += '                                Gross Pay (GP)';
            html += '                            </p>';
            html += '                        </div>';
            html += '                        <div class="col-sm-6 col-xs-12 text-right">';
            html += '                            <p class="csF16 csB7">';
            html += '                                <span class="jsPayrollRowEditGPValue">$0.00<span>';
            html += '                            </p>';
            html += '                        </div>';
            html += '                    </div>';
            html += '                    ';
            html += '                </div>';
            html += '                <!-- Portion Page -->';
            html += '                <div class="portionSections">';
            html += '                    <!-- Manage Regular Hours -->';
            html += '                    <div class="csPageBoxBody p10 dn" data-portion="regular_hours">';
            html += '                        <!--  -->';
            html += '                        <p class="csF16 csB7">';
            html += '                            Regular Hours (RH)';
            html += '                        </p>';
            html += '                        <p class="csF14 csInfo">';
            html += '                            The employee worked hours.';
            html += '                        </p>';
            html += '                        <!--  -->';
            html += '                        <div class="csPR">';
            html += '                            <div class="input-group">';
            html += '                                <span class="input-group-addon csCI">RH</span>';
            html += '                                <input type="text" class="form-control text-right pr30 jsPayrollRHInput" placeholder="0.00"/>';
            html += '                            </div>';
            html += '                            <b class="csInputPlaceholder csF14 csB1">hr</b>';
            html += '                        </div>';
            html += '                        <br>';
            html += '                        <!--  -->';
            html += '                        <span class="pull-right">';
            html += '                            <button class="btn btn-orange jsPayrollRHSaveBTN">';
            html += '                                <i class="fa fa-save" aria-hidden="true"></i>&nbsp;Save';
            html += '                            </button>';
            html += '                            <button class="btn btn-black jsPayrollCancelBTN">';
            html += '                                <i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;Cancel';
            html += '                            </button>';
            html += '                        </span>';
            html += '                        <div class="clearfix"></div>';
            html += '                    </div>';
            if(payrollEmployee.hourlyCompensations['overtime'] !== undefined){
                html += '                    <!-- Manage Overtime -->';
                html += '                    <div class="csPageBoxBody p10 dn" data-portion="overtime">';
                html += '                        <!--  -->';
                html += '                        <p class="csF16 csB7">';
                html += '                            Overtime (OT)';
                html += '                        </p>';
                html += '                        <p class="csF14 csInfo">';
                html += '                            The amount multiplied by the base rate to calculate total compensation per hour worked which is <b>'+((payrollEmployee.hourlyCompensations['overtime']['compensation_multiplier'] || "0").toFixed(2))+'</b>.';
                html += '                        </p>';
                html += '                        <!--  -->';
                html += '                        <div class="csPR">';
                html += '                            <div class="input-group">';
                html += '                                <span class="input-group-addon csCI">OT</span>';
                html += '                                <input type="text" class="form-control text-right pr30 jsPayrollOTInput" placeholder="0.00"/>';
                html += '                            </div>';
                html += '                            <b class="csInputPlaceholder csF14 csB1">hr</b>';
                html += '                        </div>';
                html += '                        <br>';
                html += '                        <!--  -->';
                html += '                        <span class="pull-right">';
                html += '                            <button class="btn btn-orange jsPayrollOTSaveBTN">';
                html += '                                <i class="fa fa-save" aria-hidden="true"></i>&nbsp;Save';
                html += '                            </button>';
                html += '                            <button class="btn btn-black jsPayrollCancelBTN">';
                html += '                                <i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;Cancel';
                html += '                            </button>';
                html += '                        </span>';
                html += '                        <div class="clearfix"></div>';
                html += '                    </div>';
            }
            //
            if(payrollEmployee.hourlyCompensations['double-overtime'] !== undefined){
                html += '                    <!-- Manage Double Overtime -->';
                html += '                    <div class="csPageBoxBody p10 dn" data-portion="double_overtime">';
                html += '                        <!--  -->';
                html += '                        <p class="csF16 csB7">';
                html += '                            Double Overtime (DOT)';
                html += '                        </p>';
                html += '                        <p class="csF14 csInfo">';
                html += '                            The amount multiplied by the base rate to calculate total compensation per hour worked which is <b>'+((payrollEmployee.hourlyCompensations['double-overtime']['compensation_multiplier'] || '0').toFixed(2))+'</b>';
                html += '                        </p>';
                html += '                        <!--  -->';
                html += '                        <div class="csPR">';
                html += '                            <div class="input-group">';
                html += '                                <span class="input-group-addon csCI">DOT</span>';
                html += '                                <input type="text" class="form-control text-right pr30 jsPayrollDOTInput" placeholder="0.00"/>';
                html += '                            </div>';
                html += '                            <b class="csInputPlaceholder csF14 csB1">hr</b>';
                html += '                        </div>';
                html += '                        <br>';
                html += '                        <!--  -->';
                html += '                        <span class="pull-right">';
                html += '                            <button class="btn btn-orange jsPayrollDOTSaveBTN">';
                html += '                                <i class="fa fa-save" aria-hidden="true"></i>&nbsp;Save';
                html += '                            </button>';
                html += '                            <button class="btn btn-black jsPayrollCancelBTN">';
                html += '                                <i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;Cancel';
                html += '                            </button>';
                html += '                        </span>';
                html += '                        <div class="clearfix"></div>';
                html += '                    </div>';
            }
            html += '                    <!-- Manage Bonus -->';
            html += '                    <div class="csPageBoxBody p10 dn" data-portion="bonus">';
            html += '                        <!--  -->';
            html += '                        <p class="csF16 csB7">';
            html += '                            Bonus (B)';
            html += '                        </p>';
            html += '                        <p class="csF14 csInfo">';
            html += '                            The bonus amount for the employee.';
            html += '                        </p>';
            html += '                        <!--  -->';
            html += '                        <div class="csPR">';
            html += '                            <div class="input-group">';
            html += '                                <span class="input-group-addon csCI">$</span>';
            html += '                                <input type="text" class="form-control jsPayrollBInput" placeholder="0.00"/>';
            html += '                            </div>';
            html += '                        </div>';
            html += '                        <br>';
            html += '                        <!--  -->';
            html += '                        <span class="pull-right">';
            html += '                            <button class="btn btn-orange jsPayrollBSaveBTN">';
            html += '                                <i class="fa fa-save" aria-hidden="true"></i>&nbsp;Save';
            html += '                            </button>';
            html += '                            <button class="btn btn-black jsPayrollCancelBTN">';
            html += '                                <i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;Cancel';
            html += '                            </button>';
            html += '                        </span>';
            html += '                        <div class="clearfix"></div>';
            html += '                    </div>';
            html += '                    <!-- Manage Cash Tips -->';
            html += '                    <div class="csPageBoxBody p10 dn" data-portion="cash_tips">';
            html += '                        <!--  -->';
            html += '                        <p class="csF16 csB7">';
            html += '                            Cash Tips (CT)';
            html += '                        </p>';
            html += '                        <p class="csF14 csInfo">';
            html += '                            The cash tips for the employee.';
            html += '                        </p>';
            html += '                        <!--  -->';
            html += '                        <div class="csPR">';
            html += '                            <div class="input-group">';
            html += '                                <span class="input-group-addon csCI">$</span>';
            html += '                                <input type="text" class="form-control jsPayrollCTInput" placeholder="0.00"/>';
            html += '                            </div>';
            html += '                        </div>';
            html += '                        <br>';
            html += '                        <!--  -->';
            html += '                        <span class="pull-right">';
            html += '                            <button class="btn btn-orange jsPayrollCTSaveBTN">';
            html += '                                <i class="fa fa-save" aria-hidden="true"></i>&nbsp;Save';
            html += '                            </button>';
            html += '                            <button class="btn btn-black jsPayrollCancelBTN">';
            html += '                                <i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;Cancel';
            html += '                            </button>';
            html += '                        </span>';
            html += '                        <div class="clearfix"></div>';
            html += '                    </div>';
            html += '                </div>';
            html += '            </div>';
            html += '        </div>';
            html += '    </div>';
            //
            return html;
        }

        //
        function CalculateReimbursment(){
            //
            var id = $('.jsPayrollReimbursementWrap').data('id');
            //
            var total = 0;
            //
            $('.jsPayrollReimbursementRow').map(function(){
                //
                let tv = $(this).find('.jsPayrollReimbursementRowAmount').val().trim();
                //
                total += parseFloat(isNaN(tv) || !tv ? 0 : tv);
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
            html +='        <div class="col-sm-8 col-xs-8">';
            html +='            <label class="csF16 csB7">';
            html +='                Description';
            html +='            </label>';
            html +='            <p class="csF14">';
            html +='                If you choose not to add a description, this amount will be labeled “Additional amount.”';
            html +='            </p>';
            html +='            <input type="text" class="form-control jsPayrollReimbursementRowDescription" placeholder="Description (Optional)" '+( data !== undefined ? 'value="'+(data.description)+'"' : '' )+' />';
            html +='        </div>';
            html +='        <div class="col-sm-3 col-xs-3">';
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
            html +='        <div class="col-sm-1 col-xs-1"><label>&nbsp;</label><p>&nbsp;</p>';
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
        MakeView();
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