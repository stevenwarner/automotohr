<!--  -->
<div class="container-fluid">
    <div class="csPageWrap">
        <div class="row">
            <!-- left sidebar -->
            <?php $this->load->view('payroll/pages/sidebar', ['mainIndex'=> "company", "subIndex" =>"company_address"]);?>
            <!-- Main Content -->
            <div class="col-md-9 col-sm-12">
                <!-- Heading -->
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="csF18 csB7">
                            Company Address
                        </h1>
                    </div>
                </div>
                <!-- Body -->
                <?php foreach ($locations as $location) { ?>
                    <div class="row">
                        <div class="col-sm-6">
                            <p class="csF16 mb0">
                                <?php echo $location['street_1'].', '.$location['city'].', '.$location['state'].' - '.$location['zip']; ?>
                            </p>
                            <p class="csF16 mb0">
                            <?php echo $location['phone_number']; ?>
                            </p>
                            <p class="csF16 mb0">
                                <?php
                                    if ($location['mailing_address'] == 1 && $location['filing_address'] == 1) {
                                        echo "Work & Mailling address, Filling address";
                                    } else if ($location['mailing_address'] == 1) {
                                        echo "Work & Mailling address";
                                    } else if ($location['filing_address'] == 1) {
                                        echo "Filling address";
                                    }
                                ?>
                            </p>
                        </div>
                        <div class="col-sm-6 ">
                            <button class="btn btn-orange csF16 csB7 jsPayrollUpdateCompanyLocation" data-location_id="<?php echo $location['sid']; ?>">
                                <i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;
                                Edit
                            </button>
                        </div>
                    </div>
                    <br>
                <?php } ?>  
                <div class="row">
                    <div class="col-md-6 col-xs-6">
                        <button class="btn btn-orange csF16 csB7 jsPayrollAddCompanyLocation" data-location_id="0">
                            <i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;
                            Add another
                        </button>
                        <button class="btn btn-orange csF16 csB7 jsPayrollConfirmContinue" data-id="1">
                            <i class="fa fa-check-circle-o" aria-hidden="true"></i>&nbsp;
                            Confirm
                        </button>
                    </div>
                </div>
                <br>
            </div>
        </div>

    </div>
</div>
