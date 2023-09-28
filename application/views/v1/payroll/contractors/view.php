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
                                    Manage Contractors
                                </span>
                            </div>
                        </div>
                    </div>

                    <!--  -->
                    <div class="panel panel-success">
                        <div class="panel-heading text-right">
                            <button class="btn csF16 csBG4 jsContractorAdd">
                                <i class="fa fa-plus-circle csF16"></i>
                                <span>Add a Contractor for Payroll</span>
                            </button>
                        </div>
                        <div class="panel-body">
                            <?php if (!$payrollContractors) : ?>
                                <div class="alert alert-info text-center">
                                    <p><strong>There are no records available.</strong></p>
                                </div>
                            <?php else : ?>
                                <p class="text-danger csF16">
                                    <strong>
                                        <em>
                                            Employee Status Listing for Payroll: Additionally, the option to include new employees in the payroll is available.
                                        </em>
                                    </strong>
                                </p>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <caption></caption>
                                        <thead>
                                            <tr>
                                                <th scope="col" class="csBG1">Contractor</th>
                                                <th scope="col" class="text-center csBG1">Type</th>
                                                <th scope="col" class="text-center csBG1">Wage</th>
                                                <th scope="col" class="text-center csBG1">Start Date</th>
                                                <th scope="col" class="text-center csBG1">Status</th>
                                                <th scope="col" class="text-center csBG1">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($payrollContractors as $payrollContractor) : ?>
                                                <?php
                                                $cl = $payrollContractor['onboarded'] ? 'success' : 'warning';
                                                $text = $payrollContractor['onboarded'] ? 'COMPLETED' : 'INPROGRESS';
                                                ?>
                                                <tr data-id="<?= $payrollContractor['sid']; ?>">
                                                    <td class="vam">
                                                        <p>
                                                            <strong>
                                                                <?php if ($payrollContractor['contractor_type'] === 'Business') : ?>
                                                                    <?= $payrollContractor['business_name']; ?>
                                                                <?php else : ?>
                                                                    <?php $name = $payrollContractor['first_name'];
                                                                    $name .= $payrollContractor['middle_initial'] ? ' ' . $payrollContractor['middle_initial'] : '';
                                                                    $name .= $payrollContractor['last_name'] ? ' ' . $payrollContractor['last_name'] : '';

                                                                    echo $name;
                                                                    ?>
                                                                <?php endif; ?>
                                                            </strong>
                                                        </p>
                                                    </td>
                                                    <td class="vam">
                                                        <p><?= $payrollContractor['contractor_type']; ?></p>
                                                    </td>
                                                    <td class="vam">
                                                        <p><?= $payrollContractor['wage_type']; ?></p>
                                                        <?php if ($payrollContractor['wage_type'] === 'Hourly') : ?>
                                                            <p>$ <?= $payrollContractor['hourly_rate']; ?></p>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="vam">
                                                        <p><?= formatDateToDB($payrollContractor['start_date'], DB_DATE, DATE); ?></p>
                                                    </td>
                                                    <td class="bg-<?= $cl; ?> text-center vam">
                                                        <strong class="text-<?= $cl; ?>"><?= $text; ?></strong>
                                                    </td>
                                                    <td class="text-center vam">
                                                        <button class="btn btn-warning csF16 jsContractorEdit">
                                                            <i class="fa fa-edit csF16" aria-hidden="true"></i>&nbsp;
                                                            <span>Edit</span>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>