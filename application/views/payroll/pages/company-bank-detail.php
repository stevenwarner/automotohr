<!--  -->
<div class="container-fluid">
    <div class="csPageWrap">
        <div class="row">
            <!-- left sidebar -->
            <?php $this->load->view('payroll/pages/sidebar', ['mainIndex'=> "bank", "subIndex" =>""]);?>
            <!-- Main Content -->
            <div class="col-md-9 col-sm-12">
                <!-- Heading -->
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="csF18 csB7">
                            Bank account information
                        </h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="csF18 csB7">
                            Bank account
                        </h1>
                        <p class="csF16">
                            We create bank account varifications at 5:00pm PT everyday. If you finish this step before then, you will see the test deposits by the morning of the next business day. If you enter this information after 5:00pm PT, we will create the test deposits the next business dat at 5:oopm PT.
                        </p>
                    </div>
                </div>
                <br>
                <!-- Body -->
                <?php if (!empty($bankInfo)) { ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <p class="csF16">
                                <b><?php echo $bankInfo['ein_number']; ?></b>
                                <br>
                                <span>Routing number (9 digits)</span>
                            </p>
                            <p class="csF16">
                                <b><?php echo $bankInfo['tax_payer_type']; ?></b>
                                <br>
                                <span>Account number</span>
                            </p>
                            <p class="csF16">
                                <b><?php echo $bankInfo['filling_form']; ?></b>
                                <br>
                                <span>Account type</span>
                            </p>
                        </div>
                    </div>
                    <br>
                <?php } ?></b>  
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <button class="btn btn-orange csF16 csB7 jsFederalTaxEdit" data-location_id="0">
                            <i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;
                            Edit tax information
                        </button>
                        <button class="btn btn-orange csF16 csB7 jsPayrollConfirmContinue" data-id="2">
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
