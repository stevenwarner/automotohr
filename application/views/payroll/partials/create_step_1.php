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
            'jobId' => $payrollEmployee['job_id'],
            'reimbursements' => [],
            'rate' => $emp['jobs'][0]['rate'],
            'rateUnit' => $emp['jobs'][0]['payment_unit'],
            'rateByHour' => 0.00,
            'excluded' => $payrollEmployee['excluded'],
            'paymentMethod' => $payrollEmployee['payment_method']
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
                    To pay your employees with direct deposit on <span class="csFC2"><?=formatDateToDB($Payroll['check_date'], DB_DATE, DATE);?></span>, you'll need to run payroll by <span class="csFC2"><?=GUSTO_PAYROLL_TIME;?></span> on <span class="csFC2"><?=formatDateToDB($Payroll['payroll_deadline'], DB_DATE, DATE);?></span>. If you miss this deadline. your employees' direct deposit will be delayed.
                </p>
            </div>
        </div>
         <!-- Info -->
        <div class="row dn">
            <div class="col-sm-12">
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
            <div class="jsPayrollContainer jsLayoutArea dn" data-id="box"></div>
            <!--  -->
            <div class="jsPayrollContainer2  jsLayoutArea" data-id="list">
                <div class="row">
                    <div class="col-sm-12">
                        <!--  -->
                        <table class="table table-responsive table-striped">
                            <caption></caption>
                            <thead>
                                <tr>
                                    <th scope="col" class="col-md-3"><p class="csF16 mb0 csW text-left vam">Employee</p></th>
                                    <th scope="col" class="col-md-2"><p class="csF16 mb0 csW text-right vam">Regular Hours (RH)</p></th>
                                    <th scope="col" class="col-md-2"><p class="csF16 mb0 csW text-right vam">Additional Earnings</p></th>
                                    <th scope="col" class="col-md-2"><p class="csF16 mb0 csW text-right vam">Gross Pay (GP)</p></th>
                                    <th scope="col" class="col-md-1"><p class="csF16 mb0 csW text-right vam">Actions</p></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <!--  -->
                <div class="csPB text-right">
                    <button class="btn btn-orange jsPayrollSaveBTN">
                        <i class="fa fa-save" aria-hidden="true"></i>&nbsp;Save
                    </button>
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
         *  Toggle data row
         *  TR view
         */  
        $(document).on('click', '.jsToggleRow', function(event){
            //
            event.preventDefault();
            //
            var boxREF = $(this).closest('.jsPayrollRowId');
            //
            var key = $(this).data('key');
            //
            boxREF.find('.js'+(key)+'Text').toggleClass('dn');
            boxREF.find('.js'+(key)+'Data').toggleClass('dn');
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
            payrollOBJ[employeeId]['hourlyCompensations']['regular-hours']['hours'] = parseFloat(value);
            //
            UpdatePayrollRow(employeeId);
            //
            boxREF.find('.csPageBoxBody').removeClass('dn');
            boxREF.find('.portionSections .csPageBoxBody').addClass('dn');
            //
            $(this).closest('.jsRHData').addClass('dn');
            $(this).closest('td').find('.jsRHText').removeClass('dn');
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
            //
            $(this).closest('.jsOTData').addClass('dn');
            $(this).closest('td').find('.jsOTText').removeClass('dn');
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
            //
            $(this).closest('.jsDOTData').addClass('dn');
            $(this).closest('td').find('.jsDOTText').removeClass('dn');
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
            if(!payrollOBJ[employeeId]['fixedCompensations']['bonus']){
                payrollOBJ[employeeId]['fixedCompensations']['bonus'] = {
                    name: 'bonus',
                    amount: 0
                };
            }
            //
            payrollOBJ[employeeId]['fixedCompensations']['bonus']['amount'] = parseFloat(boxREF.find('.jsPayrollBInput').val().trim());
            //
            UpdatePayrollRow(employeeId);
            //
            boxREF.find('.csPageBoxBody').removeClass('dn');
            boxREF.find('.portionSections .csPageBoxBody').addClass('dn');
            //
            $(this).closest('.jsBData').addClass('dn');
            $(this).closest('td').find('.jsBText').removeClass('dn');
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
            if(!payrollOBJ[employeeId]['fixedCompensations']['cash-tips']){
                payrollOBJ[employeeId]['fixedCompensations']['cash-tips'] = {
                    name: 'cash tips',
                    amount: 0
                };
            }
            //
            payrollOBJ[employeeId]['fixedCompensations']['cash-tips']['amount'] = parseFloat(boxREF.find('.jsPayrollCTInput').val().trim());
            //
            UpdatePayrollRow(employeeId);
            //
            boxREF.find('.csPageBoxBody').removeClass('dn');
            boxREF.find('.portionSections .csPageBoxBody').addClass('dn');
            //
            $(this).closest('.jsCTData').addClass('dn');
            $(this).closest('td').find('.jsCTText').removeClass('dn');
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
                Title: "Reimbursements for "+(payrollEmployee.lastName+', '+payrollEmployee.firstName),
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
         * Skip or Add payroll
         */
        $(document).on('click', '.jsPayrollExcludeToggle', ToggleExclude);
        
        /**
         * Payment type
         */
        $(document).on('change', '.jsPayrollPaymentType', UpdatePaymentType);

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
            html += '                <input type="text" id="jsPayrollPaycheckTips'+(payrollEmployee.employeeId)+'" value="'+(payrollEmployee.paycheckTips)+'" class="form-control jsInputCMN" style="padding-right: 25px;"/>';
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
            if(!payrollOBJ[employeeId]['fixedCompensations']['commission']){
                payrollOBJ[employeeId]['fixedCompensations']['commission'] = {
                    name: 'commission',
                    amount: 0
                };
            }
            //
            if(!payrollOBJ[employeeId]['fixedCompensations']['paycheck-tips']){
                payrollOBJ[employeeId]['fixedCompensations']['paycheck-tips'] = {
                    name: 'paycheck tips',
                    amount: 0
                };
            }
            //
            if(!payrollOBJ[employeeId]['fixedCompensations']['correction-payment']){
                payrollOBJ[employeeId]['fixedCompensations']['correction-payment'] = {
                    name: 'correction payment',
                    amount: 0
                };
            }
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
                        window.location = window.location.origin +'/payroll/run';
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
                            //
                            window.location.href = window.location.href.replace(payrollVersion, resp.Response.version);
                        } else{
                            window.location.href = window.location.href.replace(payrollVersion, resp.Response.version).replace('?step=1', '')+'?step=2';
                        }
                    }
                );
            });
        });


        // Functions
        
        // 
        function MakeView(){
            //
            var trs = '';
            //
            $.each(payrollOBJ, function(i, v){
                //
                trs += GetSingleRow(v);
            });
           
            //
            $('.jsPayrollContainer2 tbody').html(trs);
            //
            ml(false, 'main_loader');
            //
            $.each(payrollOBJ, function(i, v){
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

        /**
         * Create payroll trs
         * @param {Object} payrollEmployee
         * @returns
         */
        function GetSingleRow(payrollEmployee){
            //
            var tr = '';
            tr +='<tr class="jsPayrollRowId" data-id="'+(payrollEmployee.employeeId)+'">';
            tr +='    <td class="vam">';
            tr +='        <strong>'+(payrollEmployee.lastName+', '+payrollEmployee.firstName)+'</strong><br/>';
            tr +='        <p class="ma10">$'+(numberFormat(payrollEmployee.rate)+' /'+(payrollEmployee.rateUnit.toLowerCase()))+'</p>';
            tr +='        <a class="ma10 csFC2" href="javascript:void(0)"><i class="fa fa-edit" aria-hidden="true"></i>&nbsp;Edit Personal Note</a>';
            tr +='    </td>';
            if(payrollEmployee.excluded){
                tr +='    <td class="vam text-right" colspan="4">';
                tr +='        <div class="row">';
                tr +='            <div class="col-sm-12 jsToggleRow" data-key="EP">';
                tr +='                <span class="csFC2 csB7 csCP">';
                tr +='                    <i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;<span class="jsPayrollExcludeToggle">Enter Payment</span>';
                tr +='                </span>';
                tr +='            </div>';
                tr +='        </div>';
                tr +='    </td>';
            } else{

                tr +='    <td class="vam text-right">';
                tr +='        <!-- Hours Row -->';
                tr +='        <div class="row">';
                tr +='            <div class="col-sm-12 jsRHText jsToggleRow" data-key="RH">';
                tr +='                <span class="csFC2 csB7 csCP">';
                tr +='                    <i class="fa fa-edit" aria-hidden="true"></i>&nbsp;<span class="jsPayrollRowEditRHValue">0.00</span>&nbsp;(RH)';
                tr +='                </span>';
                tr +='            </div>';
                tr +='            <div class="col-sm-12 jsRHData dn">';
                tr +='                <div class="input-group">';
                tr +='                    <div class="input-group-addon" title="Regular Hours" placement="true">RH</div>';
                tr +='                    <input type="text" class="form-control jsPayrollRHInput" placeholder="40.00"/>';
                tr +='                    <div class="input-group-addon csBG1 csCP csW jsPayrollRHSaveBTN" title="Save" placement="true"><i class="fa fa-check" aria-hidden="true"></i></div>';
                tr +='                    <div class="input-group-addon csBG3 csCP csW jsToggleRow" data-key="RH" title="Cancel" placement="true"><i class="fa fa-times" aria-hidden="true"></i></div>';
                tr +='                </div>';
                tr +='                <p class="csF14 text-left ma10">The employee worked hours.</p>';
                tr +='            </div>';
                tr +='        </div>';
                if(payrollEmployee.hourlyCompensations['overtime'] !== undefined){
                tr +='        <!-- Overtime Row -->';
                tr +='        <div class="row ma10">';
                tr +='            <div class="col-sm-12 jsOTText jsToggleRow" data-key="OT">';
                tr +='                <span class="csFC2 csB7 csCP jsPayrollRowEditOTE">';
                tr +='                    <i class="fa fa-edit" aria-hidden="true"></i>&nbsp;<span class="jsPayrollRowEditOTValue">0.00</span>&nbsp;(OT)';
                tr +='                </span>';
                tr +='                <span class="csFC2 csB7 csCP jsPayrollRowEditOTP">';
                tr +='                    <i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;<span>Overtime</span>&nbsp;(OT)';
                tr +='                </span>';
                tr +='            </div>';
                tr +='            <div class="col-sm-12 jsOTData dn">';
                tr +='                <div class="input-group">';
                tr +='                    <div class="input-group-addon" title="Overtime" placement="true">OT</div>';
                tr +='                    <input type="text" class="form-control jsPayrollOTInput" placeholder="0.00"/>';
                tr +='                    <div class="input-group-addon csBG1 csCP csW jsPayrollOTSaveBTN" title="Save" placement="true"><i class="fa fa-check" aria-hidden="true"></i></div>';
                tr +='                    <div class="input-group-addon csBG3 csCP csW jsToggleRow" data-key="OT" title="Cancel" placement="true"><i class="fa fa-times" aria-hidden="true"></i></div>';
                tr +='                </div>';
                tr +='                <p class="csF14 text-left ma10">The amount multiplied by the base rate to calculate total compensation per hour worked which is '+((payrollEmployee.hourlyCompensations['overtime']['compensation_multiplier'] || '0').toFixed(2))+'.</p>';
                tr +='            </div>';
                tr +='        </div>';
                }
                if(payrollEmployee.hourlyCompensations['double-overtime'] !== undefined){
                tr +='        <!-- Double Overtime Row -->';
                tr +='        <div class="row ma10">';
                tr +='            <div class="col-sm-12 jsDOTText jsToggleRow" data-key="DOT">';
                tr +='                <span class="csFC2 csB7 csCP jsPayrollRowEditDOTE">';
                tr +='                    <i class="fa fa-edit" aria-hidden="true"></i>&nbsp;<span class="jsPayrollRowEditDOTValue">0.00</span>&nbsp;(DOT)';
                tr +='                </span>';
                tr +='                <span class="csFC2 csB7 csCP jsPayrollRowEditDOTP">';
                tr +='                    <i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;<span>Double Overtime</span>&nbsp;(DOT)';
                tr +='                </span>';
                tr +='            </div>';
                tr +='            <div class="col-sm-12 jsDOTData dn">';
                tr +='                <div class="input-group">';
                tr +='                    <div class="input-group-addon" title="Double Overtime" placement="true">DOT</div>';
                tr +='                    <input type="text" class="form-control jsPayrollDOTInput" placeholder="0.00"/>';
                tr +='                    <div class="input-group-addon csBG1 csCP csW jsPayrollDOTSaveBTN" title="Save" placement="true"><i class="fa fa-check" aria-hidden="true"></i></div>';
                tr +='                    <div class="input-group-addon csBG3 csCP csW jsToggleRow" data-key="DOT" title="Cancel" placement="true"><i class="fa fa-times" aria-hidden="true"></i></div>';
                tr +='                </div>';
                tr +='                <p class="csF14 text-left ma10">The amount multiplied by the base rate to calculate total compensation per hour worked which is '+((payrollEmployee.hourlyCompensations['double-overtime']['compensation_multiplier'] || '0').toFixed(2))+'.</p>';
                tr +='            </div>';
                tr +='        </div>';
                }
                tr +='    </td>';
                tr +='    <td class="vam text-right">';
                tr +='        <!-- Bonus -->';
                tr +='        <div class="row ma10">';
                tr +='            <div class="col-sm-12 jsBText jsToggleRow" data-key="B">';
                tr +='                <span class="csFC2 csB7 csCP jsPayrollRowEditBE">';
                tr +='                    <i class="fa fa-edit" aria-hidden="true"></i>&nbsp;<span class="jsPayrollRowEditBValue">0.00</span>&nbsp;(B)';
                tr +='                </span>';
                tr +='                <span class="csFC2 csB7 csCP jsPayrollRowEditBP">';
                tr +='                    <i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;<span>Bonus</span>&nbsp;(B)';
                tr +='                </span>';
                tr +='            </div>';
                tr +='            <div class="col-sm-12 jsBData dn">';
                tr +='                <div class="input-group">';
                tr +='                    <div class="input-group-addon" title="Bonus" placement="true">$</div>';
                tr +='                    <input type="text" class="form-control jsPayrollBInput" placeholder="0.00"/>';
                tr +='                    <div class="input-group-addon csBG1 csCP csW jsPayrollBSaveBTN" title="Save" placement="true"><i class="fa fa-check" aria-hidden="true"></i></div>';
                tr +='                    <div class="input-group-addon csBG3 csCP csW jsToggleRow" data-key="B" title="Cancel" placement="true"><i class="fa fa-times" aria-hidden="true"></i></div>';
                tr +='                </div>';
                tr +='                <p class="csF14 text-left ma10">The bonus amount for the employee.</p>';
                tr +='            </div>';
                tr +='        </div>';
                tr +='        <!-- Cash Tips -->';
                tr +='        <div class="row ma10">';
                tr +='            <div class="col-sm-12 jsCTText jsToggleRow" data-key="CT">';
                tr +='                <span class="csFC2 csB7 csCP jsPayrollRowEditCTE">';
                tr +='                    <i class="fa fa-edit" aria-hidden="true"></i>&nbsp;<span class="jsPayrollRowEditCTValue">0.00</span>&nbsp;(CT)';
                tr +='                </span>';
                tr +='                <span class="csFC2 csB7 csCP jsPayrollRowEditCTP">';
                tr +='                    <i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;<span>Cash Tips</span>&nbsp;(CT)';
                tr +='                </span>';
                tr +='            </div>';
                tr +='            <div class="col-sm-12 jsCTData dn">';
                tr +='                <div class="input-group">';
                tr +='                    <div class="input-group-addon" title="Cash Tips" placement="true">$</div>';
                tr +='                    <input type="text" class="form-control jsPayrollCTInput" placeholder="0.00"/>';
                tr +='                    <div class="input-group-addon csBG1 csCP csW jsPayrollCTSaveBTN" title="Save" placement="true"><i class="fa fa-check" aria-hidden="true"></i></div>';
                tr +='                    <div class="input-group-addon csBG3 csCP csW jsToggleRow" data-key="CT" title="Cancel" placement="true"><i class="fa fa-times" aria-hidden="true"></i></div>';
                tr +='                </div>';
                tr +='                <p class="csF14 text-left ma10">The cash tips for the employee.</p>';
                tr +='            </div>';
                tr +='        </div>';
                tr +='        <!-- Other Earnings -->';
                tr +='        <div class="row ma10">';
                tr +='            <div class="col-sm-12">';
                tr +='                <span class="csFC2 csB7 csCP jsPayrollRowEditOEE jsPayrollRowEditOE">';
                tr +='                    <i class="fa fa-edit" aria-hidden="true"></i>&nbsp;<span class="jsPayrollRowEditOEValue">0.00</span>&nbsp;(OE)';
                tr +='                </span>';
                tr +='                <span class="csFC2 csB7 csCP jsPayrollRowEditOEP">';
                tr +='                    <i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;<span>Other Earnings</span>&nbsp;(OE)';
                tr +='                </span>';
                tr +='            </div>';
                tr +='        </div>';
                tr +='    </td>';
                tr +='    <td class="vam text-right">';
                tr +='        <!-- Gross Pay -->';
                tr +='        <div class="row ma10">';
                tr +='            <div class="col-sm-12">';
                tr +='                <strong class="csFC4 jsPayrollRowEditGPValue">';
                tr +='                    $0.00';
                tr +='                </strong>';
                tr +='            </div>';
                tr +='        </div>';
                tr +='        <!-- Reimbursements -->';
                tr +='        <div class="row ma10">';
                tr +='            <div class="col-sm-12">';
                tr +='                <span class="csFC2 csB7 csCP jsPayrollRowEditRE">';
                tr +='                    <i class="fa fa-edit" aria-hidden="true"></i>&nbsp;<span class="jsPayrollRowEditRValue">0.00</span>';
                tr +='                </span>';
                tr +='                <span class="csFC2 csB7 csCP jsPayrollRowEditRP jsPayrollRowEditR">';
                tr +='                    <i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;<span>Reimbursement</span>';
                tr +='                </span>';
                tr +='            </div>';
                tr +='        </div>';
                tr +='        <!-- Pay type -->';
                tr +='        <div class="row ma10">';
                tr +='            <div class="col-sm-12">';
                tr +='                <strong class="csFC4">';
                tr +='                   <select class="jsPayrollPaymentType"><option '+(payrollEmployee.paymentMethod != 'Check' ? 'selected' : '')+' value="Direct Deposit">Direct Deposit</option><option '+(payrollEmployee.paymentMethod == 'Check' ? 'selected' : '')+' value="Check">Check</option></select>';
                tr +='                </strong>';
                tr +='            </div>';
                tr +='        </div>';
                tr +='    </td>';
                tr +='    <td class="vam text-right">';
                tr +='       <div class="dropdown ml10">';
                tr +='           <button';
                tr +='             class="btn btn-default dropdown-toggle"';
                tr +='             type="button"';
                tr +='             id="dd_'+(payrollEmployee.employeeId)+'"';
                tr +='             data-toggle="dropdown"';
                tr +='             aria-haspopup="true"';
                tr +='             aria-expanded="false">';
                tr +='                <i class="fa fa-ellipsis-v" aria-hidden="true"></i>';
                tr +='           </button>';
                tr +='           <ul class="dropdown-menu pull-right" aria-labelledby="dd_'+(payrollEmployee.employeeId)+'">';
                tr +='               <li><a href="javascript:void(0)" class="jsPayrollExcludeToggle">Skip payroll</a></li>';
                tr +='           </ul>';
                tr +='       </div>';
                tr +='    </td>';
            }
            tr +='</tr>';

            //
            return tr;
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
        function ToggleExclude(){
            //
            const employeeId = $(this).closest('.jsPayrollRowId').data('id');
            //
            ml(true, 'jsPayrollEditLoader'+employeeId)
            //
            const boxREF = $(this).closest('.jsPayrollRowId');
            //
            payrollOBJ[employeeId]['excluded'] = !payrollOBJ[employeeId]['excluded'];
            //
            MakeView();
        }
        
        //
        function UpdatePaymentType(){
            //
            const employeeId = $(this).closest('.jsPayrollRowId').data('id');
            //
            ml(true, 'jsPayrollEditLoader'+employeeId)
            //
            const boxREF = $(this).closest('.jsPayrollRowId');
            //
            payrollOBJ[employeeId]['paymentMethod'] = payrollOBJ[employeeId]['paymentMethod'] == 'Direct Deposit' ? 'Check' : 'Direct Deposit';
            //
            MakeView();
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

        /**
         * Update calculations
         * @param {Integer} employeeId
         * @param {String}  key
         * @param {String}  value
         */
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
            payrollEmployee.regularHours = payrollEmployee.regularHours || 0.00;
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
            payrollEmployee.regularHours = parseFloat(payrollEmployee.regularHours || payrollEmployee.hourlyCompensations['regular-hours']['hours']);
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
            boxREF.find('.jsPayrollRowEditRHValue').text(payrollEmployee.regularHours.toFixed(2));
            boxREF.find('.jsPayrollRHInput').val(payrollEmployee.regularHours.toFixed(2));
            // Let's set the overtime
            boxREF.find('.jsPayrollRowEditOTValue').text(payrollEmployee.overtime.toFixed(2));
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
            boxREF.find('.jsPayrollRowEditDOTValue').text(payrollEmployee.doubleOvertime.toFixed(2));
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
            boxREF.find('.jsPayrollRowEditRValue').text("$"+ payrollEmployee.reimbursement.toFixed(2)+' (R)');
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
        MakeView();
        //
        window.payrollOBJ = payrollOBJ;
    });
</script>

<?php else: ?>
    <p class="alert alert-info text-center csF16 csB7">
        No employees qualify for the selected payroll <br><br>
        <a href="<?=base_url('payroll/run');?>" class="btn btn-orange">Go To Payrolls</a>
    </p>
<?php
    endif;
?>