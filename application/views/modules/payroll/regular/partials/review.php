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
                            <h1 class="text-medium csW" style="margin: 0">
                                <strong>Regular Payroll</strong>
                            </h1>
                        </div>
                        <div class="panel-body">
                            <!--  -->
                            <div class="row">
                                <div class="col-sm-12">
                                    <p class="text-medium">
                                        <strong>Pay period: </strong>
                                        <?= formatDateToDB($regularPayroll['start_date'], DB_DATE, DATE); ?> -
                                        <?= formatDateToDB($regularPayroll['end_date'], DB_DATE, DATE); ?>
                                    </p>
                                    <p class="text-medium">
                                        <strong>Calculated At: </strong>
                                        <?=
                                        reset_datetime(
                                            [
                                                "datetime" => $regularPayroll['calculated_at'],
                                                "from_timezone" => "UTC",
                                                "from_format" => 'Y-m-d\TH:i:sZ',
                                                "format" => DATE . " h:i A T",
                                                "_this" => $this
                                            ]
                                        );
                                        ?>
                                    </p>
                                </div>
                            </div>
                            <br />
                            <br />
                            <!-- Steps -->
                            <div class="row">
                                <!-- 1 -->
                                <div class="col-sm-6 col-xs-12">
                                    <div class="progress mb0" style="height: 5px">
                                        <div class="progress-bar bg-orange" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                                        </div>
                                    </div>
                                    <p class="text-medium" style="margin-top: 10px;">
                                        <strong>1. Hours and earnings</strong>
                                    </p>
                                </div>

                                <!-- 3 -->
                                <div class="col-sm-6 col-xs-12">
                                    <div class="progress mb0" style="height: 5px">
                                        <div class="progress-bar bg-orange" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                                        </div>
                                    </div>
                                    <p class="text-medium" style="margin-top: 10px;">
                                        <strong>2. Review and submit</strong>
                                    </p>
                                </div>
                            </div>
                            <br />
                            <div class="jsContentArea"></div>
                        </div>
                    </div>
                    <!-- loader -->
                    <?php $this->load->view('v1/loader', ['id' => 'jsPageLoader']); ?>
                </div>
            </div>
        </div>
    </div>
</div>