<!--  -->
<div class="container-fluid">
    <div class="csPageWrap">
        <div class="row">
            <!-- left sidebar -->
            <?php $this->load->view('payroll/pages/sidebar', ['mainIndex'=> "company", "subIndex" =>"federal_tax_info"]);?>
            <!-- Main Content -->
            <div class="col-md-9 col-sm-12">
                <!-- Heading -->
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="csF18 csB7">
                            Federal tax confirmation
                        </h1>
                    </div>
                </div>
                <!-- Body -->
                <?php if (!empty($taxInfo)) { ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <p class="csF16">
                                <b><?php echo $taxInfo['ein_number']; ?></b>
                                <br>
                                <span>Federal EIN</span>
                            </p>
                            <p class="csF16">
                                <b><?php echo $taxInfo['tax_payer_type']; ?></b>
                                <br>
                                <span>Company type</span>
                            </p>
                            <p class="csF16">
                                <b><?php echo $taxInfo['filling_form']; ?></b>
                                <br>
                                <span>Federal filing form</span>
                            </p>
                            <p class="csF16">
                                <b><?php echo $taxInfo['legal_name']; ?></b>
                                <br>
                                <span>Legal entity name</span>
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
                            <i class="fa fa-save" aria-hidden="true"></i>&nbsp;
                            Save & continue
                        </button>
                    </div>
                </div>
                <br>
            </div>
        </div>

    </div>
</div>
