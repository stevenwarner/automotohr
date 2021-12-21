

<!-- Tabs -->
<div class="jsPageTabContainer">
    <!-- Hours and earnings -->
    <div class="jsPageTab" data-page="hours">
        <!-- Info -->
        <div class="row">
            <div class="col-sm-12">
                <h1 class="csF16 csB7">
                    Review and submit
                </h1>
                <p class="csF16">
                    Here’s a quick summary to review—we’ll debit funds after you submit payroll. We saved your progress so you can submit this later. Or, download a full summary now. To pay your team on the pay date below, submit payroll by <span class="csFC2"><?=formatDateToDB($Payroll['payroll_deadline'], DB_DATE, DATE);?></span> at <span class="csFC2"><?=GUSTO_PAYROLL_TIME;?></span>.
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
                <br>
                <!-- Payroll Row -->
                <div class="row">
                    <div class="col-sm-2 col-xs-12">
                        <p class="csF16 mb0">Total Payroll</p>
                        <p class="csF18 csB9">$<?=number_format(
                            $Payroll['totals']['gross_pay'] +
                            $Payroll['totals']['reimbursement_debit'] +
                            $Payroll['totals']['employer_taxes'] +
                            $Payroll['totals']['benefits'], 2
                        );?></p>
                    </div>
                    <div class="col-sm-2 col-xs-12">
                        <p class="csF16 mb0">Debit Amount <i class="fa fa-info-circle csInfo csF18 csCP" aria-hidden="true" title="The debit amount is what we will withdraw from your bank account. Because it excludes anything paid by the company directly (check payments, company benefits, benefits, etc.), it might be less than the total payroll." placement="top"></i></p>
                        <p class="csF18 csB9">$<?=number_format($Payroll['totals']['company_debit'], 2);?></p>
                    </div>
                    <div class="col-sm-3 col-xs-12">
                        <p class="csF16 mb0">Debit Date</p>
                        <p class="csF18 csB9"><?=formatDateToDB($Payroll['payroll_deadline'], DB_DATE, DATE);?></p>
                    </div>
                    <div class="col-sm-3 col-xs-12">
                        <p class="csF16 mb0">Employee pay date</p>
                        <p class="csF18 csB9"><?=formatDateToDB($Payroll['check_date'], DB_DATE, DATE);?></p>
                    </div>
                </div>
                <br>
                <!--  -->
                <div class="row">
                    <div class="col-sm-12 text-left">
                        <a href="<?=current_url();?>?step=1" class="btn btn-black">
                            <i class="fa fa-long-arrow-left" aria-hidden="true"></i>&nbsp;Go to Dashboard
                        </a>
                        <button class="btn btn-orange jsSubmitPayroll">
                            <i class="fa fa-save" aria-hidden="true"></i>&nbsp;Submit Payroll
                        </button>
                    </div>
                </div>
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
    $(function PayrollSubmit(){
        //
        $('.jsSubmitPayroll').click(function(event){
            //
            event.preventDefault();
            //
            ml(true, 'main_loader');
            //
            $.ajax({
                method:"POST",
                url: "<?=base_url("payroll/submit");?>",
                data: {
                    payrollId: "<?=$payrollId;?>",
                    payrollVersion: "<?=$payrollVersion;?>",
                }
            }).done(function(resp){
                //
                if(!resp.Status){
                    return alertify.alert('Error!', 'Something went wrong while submitting the payroll.');
                }
                //
                return alertify.alert('Success!', 'You have successfully submitted the payroll.', function(){
                    window.location.href = window.location.href.replace(/step=2/, 'step=3');
                });
            });
        });
    });
</script>