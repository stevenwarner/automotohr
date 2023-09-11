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
                                <strong>External Payroll</strong>
                            </h1>
                        </div>
                        <div class="panel-body">
                            <!--  -->
                            <div class="row">
                                <div class="col-sm-12">
                                    <h3 class="csF22">
                                        <strong>
                                            Payroll for check date <?= formatDateToDB($externalPayroll['check_date'], DB_DATE, DATE); ?>
                                        </strong>
                                    </h3>
                                    <p class="csF16">
                                        Payment Period: <?= formatDateToDB($externalPayroll['payment_period_start_date'], DB_DATE, DATE); ?> - <?= formatDateToDB($externalPayroll['payment_period_end_date'], DB_DATE, DATE); ?>
                                    </p>
                                </div>
                            </div>
                            <hr />

                            <?php if (!$payrollEmployees) { ?>
                                <?php $this->load->view('v1/no_data', [
                                    'message' => "No payroll employees found. Please, onboard employees to payroll first."
                                ]); ?>
                            <?php } else { ?>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <caption></caption>
                                        <thead>
                                            <tr>
                                                <th scope="col" class="csBG4 csW">Employee</th>
                                                <th scope="col" class="csBG4 csW text-right">Hired date</th>
                                                <th scope="col" class="csBG4 csW text-right">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($payrollEmployees as $value) { ?>
                                                <tr>
                                                    <td class="vam">
                                                        <p class="csF16">
                                                            <strong>
                                                                <?= $value['name']; ?>
                                                            </strong>
                                                        </p>
                                                    </td>
                                                    <td class="vam text-right">
                                                        <p class="csF16">
                                                            <?= formatDateToDB($value['hired_date'], DB_DATE, DATE); ?>
                                                        </p>
                                                    </td>
                                                    <td class="vam text-right">
                                                        <a href="<?= base_url('payrolls/external/' . ($externalPayrollId) . '/' . ($value['id']) . ''); ?>" class="btn csW csBG3 csF16">
                                                            <?php if (in_array($value['id'], $linkedEmployeeIds)) { ?>
                                                                <i class="fa fa-edit csF16" aria-hidden="true"></i>
                                                                &nbsp;Edit
                                                            <?php } else { ?>
                                                                <i class="fa fa-play-circle csF16" aria-hidden="true"></i>
                                                                &nbsp;Start
                                                            <?php } ?>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php } ?>


                            <!-- Info -->
                            <hr />
                            <div class="row">
                                <div class="col-sm-12">
                                    <p class="csF16">
                                        If an employee is not in the list, please check their hire and/or dismissal dates. The hire date must be before or equal to the payroll's payment period end date. The dismissal date (if exists) must be on or after the payroll's payment period start date.
                                    </p>
                                </div>
                            </div>
                            <hr />
                            <div class="row">
                                <div class="col-sm-12 text-right">
                                    <a href="<?= base_url('payrolls/external'); ?>" class="btn csW csF16 csBG4">
                                        <i class="fa fa-long-arrow-left csF16" aria-hidden="true"></i>
                                        &nbsp;Back
                                    </a>
                                    <?php if($externalPayroll['is_processed'] == 0) {?>
                                    <a href="<?= base_url('payrolls/external/create'); ?>" class="btn csW csF16 csBG3">
                                        <i class="fa fa-plus-circle csF16" aria-hidden="true"></i>
                                        &nbsp;Create an external payroll
                                    </a>
                                    <?php } ?>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>