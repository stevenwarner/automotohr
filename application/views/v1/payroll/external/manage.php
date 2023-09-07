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
                                <strong>External Payrolls</strong>
                            </h1>
                        </div>
                        <div class="panel-body">
                            <!--  -->
                            <div class="row">
                                <div class="col-sm-8 col-xs-12">
                                    <p class="csF16">To make sure we file your taxes properly, we need to collect some info from your employees' previous payrolls.</p>
                                </div>
                                <div class="col-sm-4 col-xs-12 text-right">
                                    <a href="<?= base_url('payrolls/external/add'); ?>" class="btn csW csBG3 csF16">
                                        <i class="fa fa-plus-circle csF16"></i>
                                        &nbsp;Add external payroll
                                    </a>
                                </div>
                            </div>
                            <hr />
                            <?php $this->load->view('v1/payroll/historical_info'); ?>

                            <!--  -->
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <caption></caption>
                                    <thead>
                                        <tr>
                                            <th scope="col" class="csBG4">Check date</th>
                                            <th scope="col" class="csBG4">Pay periods</th>
                                            <th scope="col" class="text-right csBG4">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="vam">
                                                <p class="csF16">2022-07-01</p>
                                            </td>
                                            <td class="vam">
                                                <p class="csF16">2022-06-15 to 2022-06-30</p>
                                            </td>
                                            <td class="vam text-right">
                                                <button class="btn btn-danger csF16">
                                                    <i class="fa fa-times-circle csF16"></i>
                                                    &nbsp;Delete
                                                </button>
                                                <button class="btn btn-warning csF16">
                                                    <i class="fa fa-edit csF16"></i>
                                                    &nbsp;Update
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td class="vam text-right" colspan="3">
                                                <a href="<?= base_url("payrolls/external/confirm-tax-liabilities"); ?>" class="btn csW csBG3 csF16">
                                                    <i class="fa fa-check-circle csF16"></i>
                                                    &nbsp;Confirm tax liability
                                                </a>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>