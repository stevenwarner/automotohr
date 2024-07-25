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
                                <strong>Off-cycle payroll - select employees</strong>
                            </h1>
                        </div>
                        <div class="panel-body">
                            <p class="csF16">
                                <strong>
                                    <em>
                                        Select who will be on this payroll. You can only choose from employees at your company.
                                    </em>
                                </strong>
                            </p>
                            <?php if ($payrollEmployees) { ?>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <caption></caption>
                                        <thead>
                                            <tr>
                                                <th class="csW csBG4 col-sm-1" scope="col">
                                                    <label class="control control--checkbox">
                                                        <input type="checkbox" class="jsSelectAll" />
                                                        <div class="control__indicator" style="margin-top: -11px;"></div>
                                                    </label>
                                                </th>
                                                <th class="csW csBG4" scope="col">
                                                    Employee name
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($payrollEmployees as $value) { ?>
                                                <tr>
                                                    <td class="vam">
                                                        <label class="control control--checkbox">
                                                            <input type="checkbox" class="jsSelectSingle" value="<?= $value['id']; ?>" />
                                                            <div class="control__indicator" style="margin-top: -11px;"></div>
                                                        </label>
                                                    </td>
                                                    <td class="vam">
                                                        <?= $value['name']; ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php } else { ?>
                                <?php $this->load->view('v1/no_data', ['message' => 'No employees found.']); ?>
                            <?php } ?>
                        </div>
                        <div class="panel-footer text-right">
                            <!-- cancel -->
                            <a href="<?= base_url('payroll/dashboard'); ?>" class="btn csW csBG4 csF16">
                                <i class="fa fa-times-circle csF16" aria-hidden="true"></i>
                                &nbsp;Cancel
                            </a>
                            <?php if ($payrollEmployees) { ?>
                                <button class="btn csW csBG3 csF16 jsOffCycleSave">
                                    <i class="fa fa-save csF16" aria-hidden="true"></i>
                                    &nbsp;Continue
                                </button>
                            <?php } ?>
                        </div>
                        <?php if ($payrollEmployees) { ?>
                            <!--  -->
                            <?php $this->load->view(
                                'v1/loader',
                                [
                                    'id' => 'jsPageLoader'
                                ]
                            ); ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>