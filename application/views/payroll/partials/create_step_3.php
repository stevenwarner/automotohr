

<!-- Tabs -->
<div class="jsPageTabContainer">
    <!-- Hours and earnings -->
    <div class="jsPageTab" data-page="hours">
        <!-- Info -->
        <div class="row">
            <div class="col-sm-12">
                <h1 class="csF24 csB7">
                    You did it, AutomotoHR!
                </h1>
                <p class="csF16">
                    Time to kick back. Payroll will be debited $<span class="csFC2"><?=number_format($Payroll['totals']['gross_pay'], 2);?></span> from AutomotoHR Demo Company on <span class="csFC2"><?=formatDateToDB($Payroll['payroll_deadline'], DB_DATE, DATE);?></span>. Please make sure you have these funds available.
                </p>
            </div>
        </div>
        <br>
        <!-- Info -->
        <div class="row">
            <div class="col-sm-6 text-left">
                <p class="csF16 csB7 mb0">
                    Employee Pay Date
                </p>
                <p class="csF18 csB7">
                    <?=formatDateToDB($Payroll['check_date'], DB_DATE, DATE);?>
                </p>
            </div>
            <div class="col-sm-6 text-left">
                <p class="csF16 csB7 mb0">
                    Total Payroll
                </p>
                <p class="csF18 csB7">
                $<?=number_format(
                            $Payroll['totals']['gross_pay'] +
                            $Payroll['totals']['reimbursement_debit'] +
                            $Payroll['totals']['employer_taxes'] +
                            $Payroll['totals']['benefits'], 2
                        );?>
                </p>
            </div>
        </div>
        <!-- Info -->
        <div class="row dn">
            <div class="col-sm-12">
                <a class="btn btn-black" href="<?=base_url("payroll/run");?>">
                    Back To Dashboard
                </a>
                <button class="btn btn-orange">
                    Full Summary
                </button>
            </div>
        </div>
        <br>
        <!-- Info -->
        <div class="row">
            <div class="col-sm-12">
                <p class="csF16">
                    Need to cancel payroll? It’s easy to re-run if needed. <button class="btn btn-link mtn5 csFC3 jsCancelPayroll">Cancel this payroll </button>
                </p>
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
                <!-- Tax & deductions -->
                <?php $this->load->view('payroll/partials/partial_create_step_2_taxes'); ?>
                <!-- Worked hours -->
                <?php $this->load->view('payroll/partials/partial_create_step_2_worked_hours'); ?>
                <!-- Company pay -->
                <?php $this->load->view('payroll/partials/partial_create_step_2_companies_pay'); ?>
            </div>
        </div>
    </div>
</div>


<script>
    $(function(){
        //
        $('.jsCancelPayroll').click(function(event){
            //
            event.preventDefault();
            //
            alertify.confirm(
                'You may cancel <strong><?=formatDateToDB($Payroll['pay_period']['start_date'], DB_DATE, DATE);?> - <?=formatDateToDB($Payroll['pay_period']['end_date'], DB_DATE, DATE);?></strong> payroll now and run it again later. Just note that your employees will be paid late if you don’t run it by <strong><?=GUSTO_PAYROLL_TIME;?></strong> on <strong><?=formatDateToDB($Payroll['payroll_deadline'], DB_DATE, DATE);?></strong>.<br><br><strong>Don’t want to lose all your data?</strong><br>Rest assured—we’ll save all the info you entered for this payroll, in case you need to re-run it.', 
                function(){
                    CancelPayroll();
                }
            ).setHeader('Cancel Payroll Confirmation');
        });
        
        //
        function CancelPayroll(){
            //
            ml(true, 'main_loader', 'Please wait, while we cancel the payroll.');
            //
            $.post(
                "<?=base_url("cancel_payroll");?>", {
                    payrollId:"<?=$payrollId;?>"
                }
            ).done(function(resp){
                alertify.alert("Success!", "Payroll canceled.", function(){
                    window.location.href = window.location.origin + '/payroll/run';
                });
            });
        }
    });
</script>
