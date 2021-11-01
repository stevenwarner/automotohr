<!--  -->
<div class="container-fluid">
    <div class="csPageWrap">
        <div class="row">
            <!-- left sidebar -->
            <?php $this->load->view('payroll/pages/sidebar', ['mainIndex'=> "payroll", "subIndex" =>""]);?>
            <!-- Main Content -->
            <div class="col-md-9 col-sm-12">
                <!-- Heading -->
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="csF18 csB7">
                            Payroll setup
                        </h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="csF18 csB7">
                            payroll schedule
                        </h1>
                        <p class="csF16">
                            Please run payroll by 4:00pm PT on Wednesday, Febuary 3rd to pay your employees for their hard work. They'll receive their funds on friday, Febuary 5th.
                        </p>
                    </div>
                </div>
                <br>
                <!-- Body -->
                <?php if (!empty($payrollInfo)) { ?>
                    <div class="row">
                        <div class="col-sm-6">
                            <p class="csF16">
                                <b><?php echo $payrollInfo['frequency']; ?></b>
                                <br>
                                <span>Pay frequency</span>
                            </p>
                            <p class="csF16">
                                <b><?php echo $payrollInfo['anchor_pay_date']; ?></b>
                                <br>
                                <span>Day off the week</span>
                            </p>
                            <p class="csF16">
                                <b><?php echo ucfirst($payrollInfo['anchor_pay_date']); ?></b>
                                <br>
                                <span>First pay date:</span>
                            </p>
                            <p class="csF16">
                                <b><?php echo ucfirst($payrollInfo['anchor_end_of_pay_period']); ?></b>
                                <br>
                                <span>Deadline to run payroll:</span>
                            </p>
                        </div>
                        <div class="col-sm-6 ">
                            <button class="btn btn-orange csF16 csB7 jsEditPayrollInfo" data-location_id="<?php echo $payrollInfo['sid']; ?>">
                                <i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;
                                Edit
                            </button>
                        </div>
                    </div>
                    <br>
                <?php } ?></b>  
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <button class="btn btn-black csF16 csB7 jsPayrollInfoCancel">
                            <i class="fa fa-arrow-circle-o-left" aria-hidden="true"></i>&nbsp;
                            Back
                        </button>
                        <button class="btn btn-orange csF16 csB7 jsPayrollConfirmContinue" data-id="5">
                            <i class="fa fa-check-circle-o" aria-hidden="true"></i>&nbsp;
                            Save & continue
                        </button>
                    </div>
                </div>
                <br>
            </div>
        </div>

    </div>
</div>
