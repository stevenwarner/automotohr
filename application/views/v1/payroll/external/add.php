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
                                <strong>Create an External Payroll</strong>
                            </h1>
                        </div>
                        <div class="panel-body">
                            <!--  -->
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="alert alert-info">
                                        <h2>
                                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                                            &nbsp;Check date vs. Pay period
                                        </h2>
                                        <p class="csF16">There is a difference between a check date and the pay period:</p>
                                        <br>
                                        <ul style="padding-left: 20px;">
                                            <li>The <strong>check date</strong> is the date the employee was paid. This date determines your payroll tax liability and will be the date you’ll enter when reporting previous payrolls.</li>
                                            <li>The pay period is the period of time during which the wages were earned. The pay period is defined by a <strong>payment_period_start_date</strong> and <strong>payment_period_end_date</strong>.</li>
                                        </ul>
                                        <br>
                                        <p class="csF16">
                                            <em>
                                                For example, if an employee worked from December 15th to December 31st and was paid on January 15th, the pay period is the December 15th - 31st and the check date is January 15th.
                                            </em>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!--  -->
                            <div class="row">
                                <div class="col-sm-12">
                                    <p class="csF16 text-danger">
                                        <strong>
                                            <em>
                                                Previous quarter information can be entered as a single external payroll.
                                            </em>
                                        </strong>
                                    </p>
                                </div>
                            </div>
                            <hr />
                            <form action="">
                                <!--  -->
                                <div class="form-group">
                                    <label class="csF16">Check date <strong class="text-danger csF16">*</strong></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control input-lg jsDatePicker jsExternalPayrollCheckDate" id="jsExternalPayrollCheckDate" readonly placeholder="MM/DD/YYYY" />
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-o csF16" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>

                                <!--  -->
                                <div class="form-group">
                                    <label class="csF16">Payment period start <strong class="text-danger csF16">*</strong></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control input-lg jsDatePicker jsExternalPayrollPayrollPeriodStart" id="jsExternalPayrollPayrollPeriodStart" readonly placeholder="MM/DD/YYYY" />
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-o csF16" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>

                                <!--  -->
                                <div class="form-group">
                                    <label class="csF16">Payment period end <strong class="text-danger csF16">*</strong></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control input-lg jsDatePicker jsExternalPayrollPayrollPeriodEnd" id="jsExternalPayrollPayrollPeriodEnd" readonly placeholder=" MM/DD/YYYY" />
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-o csF16" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="panel-footer text-right">
                        <a href="<?= base_url("payrolls/external"); ?>" class="btn csW csF16 csBG4">
                            <i class="fa fa-times-circle csF16"></i>
                            &nbsp;Cancel
                        </a>
                        <button class="btn csW csF16 csBG3 jsExternalPayrollCreateBtn">
                            <i class="fa fa-plus-circle csF16"></i>
                            &nbsp;Create
                        </button>
                    </div>
                    <!-- loader -->
                    <?php $this->load->view('v1/loader', ['id' => 'jsPageLoader']); ?>
                </div>
            </div>
        </div>
    </div>
</div>