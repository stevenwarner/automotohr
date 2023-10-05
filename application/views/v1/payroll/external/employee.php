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
                                <strong>Add external payroll details</strong>
                            </h1>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h3 class="csF20" style="margin: 0">
                                        <strong>
                                            <?= $employeeDetails['name']; ?>
                                        </strong>
                                    </h3>
                                </div>
                                <div class="col-sm-6 text-right">
                                    <a href="<?= base_url('payrolls/external/' . ($externalPayrollId) . ''); ?>" class="btn csW csF16 csBG4">
                                        <i class="fa fa-long-arrow-left csF16" aria-hidden="true"></i>
                                        &nbsp;Back to list
                                    </a>
                                    <?php if ($externalPayrollDetails['is_processed'] == 0) { ?>
                                        <button class="btn csW csF16 csBG3 jsExternalPayrollSaveBtn">
                                            <i class="fa fa-save csF16" aria-hidden="true"></i>
                                            &nbsp;Save
                                        </button>
                                    <?php } ?>
                                </div>
                            </div>
                            <hr />
                            <form action="#">
                                <!--  -->
                                <?php if ($externalPayrollDetails['applicable_earnings']) { ?>
                                    <!-- Taxes -->
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <h3 class="csF20" style="margin: 0">
                                                <strong>
                                                    Wages
                                                </strong>
                                            </h3>
                                        </div>
                                        <br>
                                        <br>
                                    </div>
                                    <?php foreach ($externalPayrollDetails['applicable_earnings'] as $value) { ?>
                                        <div class="form-group">
                                            <label class="csF16">
                                                <?= $value['name']; ?>
                                                &nbsp;<strong class="csF16 text-danger">*</strong>
                                            </label>
                                            <?php if ($value['input_type'] === 'amount') { ?>

                                                <div class="input-group">
                                                    <div class="input-group-addon csF16">$</div>
                                                    <input type="number" class="form-control jsExternalPayrollApplicableInputs input-lg" data-id="<?= $value['earning_id']; ?>" data-type="<?= $value['earning_type']; ?>" data-input="<?= $value['input_type']; ?>" value="<?= $value['amount'] ?? "0.0" ?>" />
                                                </div>

                                            <?php } elseif ($value['input_type'] === 'hours') { ?>
                                                <input type="number" class="form-control jsExternalPayrollApplicableInputs input-lg" data-id="<?= $value['earning_id']; ?>" data-type="<?= $value['earning_type']; ?>" data-input="<?= $value['input_type']; ?>" value="<?= $value['hours'] ?? "0.0" ?>" />
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                <?php } ?>

                                <?php if ($externalPayrollDetails['applicable_taxes']) { ?>
                                    <!-- Taxes -->
                                    <div class="row">
                                        <br>
                                        <div class="col-sm-6">
                                            <h3 class="csF20" style="margin: 0">
                                                <strong>
                                                    Taxes
                                                </strong>
                                            </h3>
                                        </div>
                                        <?php if ($externalPayrollDetails['is_processed'] == 0) { ?>
                                            <div class="col-sm-6 text-right">
                                                <button class="btn csW csF16 csBG3 jsExternalPayrollCalculateTaxesBtn">
                                                    <i class="fa fa-calculator csF16" aria-hidden="true"></i>
                                                    &nbsp;Calculate taxes
                                                </button>
                                            </div>
                                        <?php } ?>
                                        <br>
                                        <br>
                                    </div>
                                    <?php foreach ($externalPayrollDetails['applicable_taxes'] as $value) { ?>
                                        <div class="form-group">
                                            <label class="csF16">
                                                <?= $value['name']; ?>
                                                &nbsp;<strong class="csF16 text-danger">*</strong>
                                            </label>

                                            <div class="input-group">
                                                <div class="input-group-addon csF16">$</div>
                                                <input type="number" class="form-control jsExternalPayrollTaxInputs input-lg" data-id="<?= $value['id']; ?>" value="<?= $value['amount'] ?? "0.0" ?>" />
                                            </div>
                                        </div>
                                    <?php } ?>
                                <?php } ?>


                            </form>
                            <hr />
                            <div class="row">
                                <div class="col-sm-12 text-right">
                                    <a href="<?= base_url('payrolls/external/' . ($externalPayrollId) . ''); ?>" class="btn csW csF16 csBG4">
                                        <i class="fa fa-long-arrow-left csF16" aria-hidden="true"></i>
                                        &nbsp;Back to list
                                    </a>
                                    <?php if ($externalPayrollDetails['is_processed'] == 0) { ?>
                                        <button class="btn csW csF16 csBG3 jsExternalPayrollSaveBtn">
                                            <i class="fa fa-save csF16" aria-hidden="true"></i>
                                            &nbsp;Save
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