<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('v1/payroll/sidebar'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <!-- Top bar -->
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow">
                                    <!-- Company details header -->
                                    <?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <!--  -->
                                    <a href="<?php echo base_url('dashboard'); ?>" class="dashboard-link-btn">
                                        <i class="fa fa-chevron-left"></i>Dashboard
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!--  -->
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h1 class="csF16 csW" style="margin: 0">
                                <strong>Confirm Tax liabilities</strong>
                            </h1>
                        </div>
                        <div class="panel-body">
                            <p class="csF16 text-danger">
                                <strong>
                                    <em>
                                        You will not be able to change any external payroll data after you confirm.
                                    </em>
                                </strong>
                            </p>
                            <hr />

                            <!--  -->
                            <div class="row">
                                <div class="col-sm-12">
                                    <p class="csF16">
                                        <strong>
                                            Please confirm the amounts below are correct.
                                        </strong>
                                    </p>
                                </div>
                            </div>

                            <?php foreach ($taxLiabilities['liabilities_json'] as $tax) { ?>
                                <?php
                                $selectedValue = $taxLiabilities['liabilities_save_json'][$tax['tax_id']];
                                $selectedDate = '';
                                //
                                foreach ($tax['possible_liabilities'] as $option) {
                                    //
                                    if ($selectedValue['unpaid_liability_amount'] == $option['liability_amount']) {
                                        $selectedDate = $option['payroll_check_date'];
                                        break;
                                    }
                                }
                                ?>
                                <div class="row">
                                    <br />
                                    <div class="col-sm-12">
                                        <label class="csF16">
                                            <strong>
                                                <?= $tax['tax_name']; ?>
                                            </strong>
                                        </label>
                                        <p class="csF16">
                                            <?= $selectedValue['unpaid_liability_amount']; ?>
                                            <?= $selectedValue['last_unpaid_external_payroll_uuid'] ? ' (liabilities after ' . (formatDateToDB($selectedDate, DB_DATE, 'm/d')) . ')' : ''; ?>
                                        </p>
                                    </div>
                                </div>
                            <?php } ?>

                            <hr />
                            <div class="row">
                                <div class="col-sm-12 text-right">
                                    <a href="<?= base_url('payrolls/external'); ?>" class="btn csW csF16 csBG4">
                                        <i class="fa fa-long-arrow-left csF16" aria-hidden="true"></i>
                                        &nbsp;Back
                                    </a>
                                    <?php if ($taxLiabilities) { ?>
                                        <button class="btn csW csF16 csBG3 jsTaxLiabilitiesConfirmSaveBtn">
                                            <i class="fa fa-save csF16" aria-hidden="true"></i>
                                            &nbsp;Confirm
                                        </button>
                                    <?php } ?>
                                </div>
                            </div>
                            <!-- loader -->
                            <?php $this->load->view('v1/loader', ['id' => 'jsPageLoader']); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>