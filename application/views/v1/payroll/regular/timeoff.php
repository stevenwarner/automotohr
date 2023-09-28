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
                                </div>
                            </div>
                            <br />
                            <br />
                            <!-- Steps -->
                            <div class="row">
                                <!-- 1 -->
                                <div class="col-sm-4 col-xs-12">
                                    <div class="progress mb0" style="height: 5px">
                                        <div class="progress-bar csBG3" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                                        </div>
                                    </div>
                                    <p class="csF16" style="margin-top: 10px;">
                                        <strong>1. Hours and earnings</strong>
                                    </p>
                                </div>

                                <!-- 2 -->
                                <div class="col-sm-4 col-xs-12">
                                    <div class="progress mb0" style="height: 5px">
                                        <div class="progress-bar csBG3" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                                        </div>
                                    </div>
                                    <p class="csF16" style="margin-top: 10px;">
                                        <strong>2. Time off </strong>
                                    </p>
                                </div>

                                <!-- 3 -->
                                <div class="col-sm-4 col-xs-12">
                                    <div class="progress mb0" style="height: 5px">
                                        <div class="progress-bar csBG3" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
                                        </div>
                                    </div>
                                    <p class="csF16" style="margin-top: 10px;">
                                        3. Review and submit
                                    </p>
                                </div>
                            </div>
                            <br />
                            <!-- Text -->
                            <h1 class="csF16">
                                <strong>
                                    Time off
                                </strong>
                            </h1>
                            <p class="csF16">With your time off policies set up, you can track time off for this pay period below.</p>
                            <hr />
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <caption></caption>
                                    <thead>
                                        <tr>
                                            <th scope="col" class="csW csBG4">
                                                Employees
                                            </th>
                                            <th scope="col" class="csW csBG4">
                                                Paid Time Off (PTO)
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot></tfoot>
                                </table>
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