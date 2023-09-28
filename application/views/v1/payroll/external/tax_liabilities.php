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
                                <strong>Unpaid tax liabilities</strong>
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
                            <p class="csF16">
                                Unpaid tax liabilities are payroll taxes that haven't yet been paid to state and federal agencies. Since you're switching over to <?= STORE_NAME; ?> mid-year, we've calculated the amounts you may owe—tell us how much to pay on your behalf. The easiest way to confirm these accounts is to call your payroll provider. Ask for your outstanding tax liabilities, then select the correct amounts here.
                            </p>
                            <hr />
                            <?php if (!$taxLiabilities) { ?>
                                <?php $this->load->view(
                                    'v1/no_data',
                                    [
                                        'message' => "No tax liability found. Please make sure you have filled employees taxes."
                                    ]
                                ); ?>
                            <?php } else { ?>
                                <form action="">
                                    <?php foreach ($taxLiabilities['liabilities_json'] as $tax) { ?>
                                        <!--  -->
                                        <div class="form-group">
                                            <label class="csF16">
                                                <?= $tax['tax_name']; ?>
                                                &nbsp;
                                                <strong class="text-danger">*</strong>
                                            </label>
                                            <select data-id="<?= $tax['tax_id']; ?>" class="form-control input-lg jsTaxLiabilitySelect">
                                                <?php foreach ($tax['possible_liabilities'] as $option) { ?>
                                                    <option <?= $taxLiabilities['liabilities_save_json'][$tax['tax_id']]['unpaid_liability_amount'] == $option['liability_amount'] ? 'selected' : ''; ?> value="<?= $option['liability_amount'] ?>" data-uuid="<?= $option['external_payroll_uuid']; ?>"><?= $option['liability_amount'] . ($optionValue = $option['external_payroll_uuid'] ? ' (liabilities after ' . (formatDateToDB($option['payroll_check_date'], DB_DATE, 'm/d')) . ')' : ''); ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    <?php } ?>
                                </form>
                            <?php } ?>

                            <hr />
                            <div class="row">
                                <div class="col-sm-12 text-right">
                                    <a href="<?= base_url('payrolls/external'); ?>" class="btn csW csF16 csBG4">
                                        <i class="fa fa-long-arrow-left csF16" aria-hidden="true"></i>
                                        &nbsp;Back
                                    </a>
                                    <?php if ($taxLiabilities) { ?>
                                        <button class="btn csW csF16 csBG3 jsTaxLiabilitiesSaveBtn">
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