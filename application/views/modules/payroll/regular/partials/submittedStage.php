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
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h1 class="panel-heading-text text-large">
                                <strong>Regular Payroll</strong>
                            </h1>
                        </div>
                        <div class="panel-body">
                            <!--  -->
                            <div class="row">
                                <div class="col-sm-12">
                                    <p class="csF16">
                                        <strong>Pay period: </strong>
                                        <?= formatDateToDB($regularPayroll['start_date'], DB_DATE, DATE); ?> -
                                        <?= formatDateToDB($regularPayroll['end_date'], DB_DATE, DATE); ?>
                                    </p>
                                    <p class="csF16">
                                        <strong>Check date: </strong>
                                        <?= formatDateToDB($regularPayroll['check_date'], DB_DATE, DATE); ?>
                                    </p>
                                    <p class="csF16">
                                        <strong>Deadline date: </strong>
                                        <?= formatDateToDB($regularPayroll['payroll_deadline'], 'Y-m-d\TH:i:sZ', DATE); ?>4:00pm PDT
                                    </p>
                                    <p class="csF16">
                                        <strong>Status: </strong>
                                        Submitted
                                    </p>
                                </div>
                            </div>
                            <br />

                            <!--  -->
                            <div class="alert alert-info text-center">
                                <p>
                                    The current payroll is submitted. Please wait, this might take a few minutes.
                                </p>

                            </div>


                        </div>
                    </div>
                    <!-- loader -->
                    <?php $this->load->view('v1/loader', ['id' => 'jsPageLoader']); ?>
                </div>
            </div>
        </div>
    </div>
</div>