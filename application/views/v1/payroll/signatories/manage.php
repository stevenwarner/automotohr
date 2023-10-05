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
                                    Manage Signatories
                                </span>
                            </div>
                        </div>
                    </div>

                    <!--  -->
                    <div class="panel panel-success">
                        <div class="panel-body">
                            <?php if (!$signatories) { ?>
                                <div class="alert alert-info text-center">
                                    <p>
                                        <strong>
                                            <span class="csF16">No signatory found</span> <br><br>
                                            <a href="<?= base_url('payrolls/signatories/create'); ?>" class="btn csBG3 csW csF16">
                                                <i class="fa fa-plus csF16"></i>
                                                Create Signatory
                                            </a>
                                        </strong>
                                    </p>
                                </div>
                            <?php } else { ?>
                                <!--  -->
                                <section>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <label class="csF16">
                                                <strong>Reference</strong>
                                            </label>
                                            <p><?= $signatories['gusto_uuid']; ?></p>
                                            <br>
                                        </div>

                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <label class="csF16">
                                                <strong>Status</strong>
                                            </label>
                                            <p><?= $signatories['identity_verification_status']; ?></p>
                                            <br>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <label class="csF16">
                                                <strong>Created At</strong>
                                            </label>
                                            <p><?= formatDateToDB($signatories['created_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME); ?></p>
                                            <br>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <label class="csF16">
                                                <strong>First Name</strong>
                                            </label>
                                            <p><?= $signatories['first_name']; ?></p>
                                            <br>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <label class="csF16">
                                                <strong>Last Name</strong>
                                            </label>
                                            <p><?= $signatories['last_name']; ?></p>
                                            <br>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <label class="csF16">
                                                <strong>Middle Initial</strong>
                                            </label>
                                            <p><?= $signatories['middle_initial']; ?></p>
                                            <br>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <label class="csF16">
                                                <strong>SSN</strong>
                                            </label>
                                            <p><?= _secret($signatories['ssn']); ?></p>
                                            <br>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <label class="csF16">
                                                <strong>Email</strong>
                                            </label>
                                            <p><?= $signatories['email']; ?></p>
                                            <br>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <label class="csF16">
                                                <strong>Title</strong>
                                            </label>
                                            <p><?= $signatories['title']; ?></p>
                                            <br>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <label class="csF16">
                                                <strong>Phone</strong>
                                            </label>
                                            <p><?= $signatories['phone']; ?></p>
                                            <br>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <label class="csF16">
                                                <strong>Birthday</strong>
                                            </label>
                                            <p>
                                                <?= formatDateToDB(
                                                    $signatories['birthday'],
                                                    DB_DATE,
                                                    DATE
                                                ); ?>
                                            </p>
                                            <br>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <label class="csF16">
                                                <strong>Street 1</strong>
                                            </label>
                                            <p><?= $signatories['street_1']; ?></p>
                                            <br>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <label class="csF16">
                                                <strong>Street 2</strong>
                                            </label>
                                            <p><?= $signatories['street_2']; ?></p>
                                            <br>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <label class="csF16">
                                                <strong>City</strong>
                                            </label>
                                            <p><?= $signatories['city']; ?></p>
                                            <br>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <label class="csF16">
                                                <strong>State</strong>
                                            </label>
                                            <p><?= $signatories['state']; ?></p>
                                            <br>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <label class="csF16">
                                                <strong>Zip</strong>
                                            </label>
                                            <p><?= $signatories['zip']; ?></p>
                                            <br>
                                        </div>
                                    </div>
                                </section>
                            <?php } ?>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>