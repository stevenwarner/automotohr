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
                                    Manage Admins
                                </span>
                            </div>
                        </div>
                    </div>

                    <!--  -->
                    <div class="panel panel-success">
                        <div class="panel-heading text-right">
                            <button class="btn csF16 csBG4">
                                <i class="fa fa-plus-circle csF16"></i>
                                <span>Add Employee for Payroll</span>
                            </button>
                        </div>
                        <div class="panel-body">
                            <?php if (!$payrollEmployees) : ?>
                                <div class="alert alert-info text-center">
                                    <p><strong>There are no records available..</strong></p>
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
                                                <th scope="col" class="csBG1">Employee</th>
                                                <th scope="col" class="text-center csBG1">Status</th>
                                                <th scope="col" class="text-center csBG1">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($payrollEmployees as $payrollEmployee) : ?>
                                                <?php
                                                $cl = $payrollEmployee['is_onboard'] ? 'success' : 'warning';
                                                $text = $payrollEmployee['is_onboard'] ? 'COMPLETED' : 'INPROGRESS';
                                                ?>
                                                <tr data-id="<?= $payrollEmployee['id']; ?>">
                                                    <td class="vam">
                                                        <p>
                                                            <strong>
                                                                <?= $payrollEmployee['name']; ?>
                                                            </strong>
                                                        </p>
                                                    </td>
                                                    <td class="bg-<?= $cl; ?> text-center vam">
                                                        <strong class="text-<?= $cl; ?>"><?= $text; ?></strong>
                                                    </td>
                                                    <td class="text-center vam">
                                                        <button class="btn btn-warning csF16 jsPayrollEmployeeEdit">
                                                            <i class="fa fa-edit csF16" aria-hidden="true"></i>&nbsp;
                                                            <span>Edit</span>
                                                        </button>
                                                        <?php if ($cl === 'warning') : ?>
                                                            <button class="btn btn-danger csF16 jsPayrollEmployeeDelete">
                                                                <i class="fa fa-times-circle csF16" aria-hidden="true"></i>&nbsp;
                                                                <span>Delete</span>
                                                            </button>
                                                        <?php endif; ?>
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