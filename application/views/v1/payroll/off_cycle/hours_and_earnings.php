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
                            <h1 class="panel-heading-text text-medium">
                                <strong>Off-cycle Payroll</strong>
                            </h1>
                        </div>
                        <div class="panel-body">
                            <!--  -->
                            <div class="row">
                                <div class="col-sm-12">
                                    <p class="text-medium">
                                        <strong>Pay period: </strong>
                                        <?= formatDateToDB($payroll['start_date'], DB_DATE, DATE); ?> -
                                        <?= formatDateToDB($payroll['end_date'], DB_DATE, DATE); ?>
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
                                    <p class="text-medium" style="margin-top: 10px;">
                                        <strong>1. Basics</strong>
                                    </p>
                                </div>
                                <!-- 1 -->
                                <div class="col-sm-4 col-xs-12">
                                    <div class="progress mb0" style="height: 5px">
                                        <div class="progress-bar csBG3" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                                        </div>
                                    </div>
                                    <p class="text-medium" style="margin-top: 10px;">
                                        <strong>2. Hours and earnings</strong>
                                    </p>
                                </div>

                                <!-- 3 -->
                                <div class="col-sm-4 col-xs-12">
                                    <div class="progress mb0" style="height: 5px">
                                        <div class="progress-bar csBG3" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
                                        </div>
                                    </div>
                                    <p class="text-medium" style="margin-top: 10px;">
                                        3. Review and submit
                                    </p>
                                </div>
                            </div>
                            <br />
                            <div class="alert alert-danger text-medium">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <p class="btn">
                                            <strong>
                                                <i class="fa fa-warning"></i>
                                                Draft payroll data included
                                            </strong>
                                        </p>
                                    </div>
                                    <div class="col-sm-6 text-right">
                                        <button class="btn btn-danger text-medium jsClearDraftData">
                                            <i class="fa fa-times-circle text-medium"></i>
                                            &nbsp; Clear draft data
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!-- Text -->
                            <h1 class="text-medium">
                                <strong>
                                    Hours and additional earnings
                                </strong>
                            </h1>
                            <p class="text-medium">Update your employees' hours, reimbursements, and additional earnings below.</p>
                            <hr />
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <caption></caption>
                                    <thead>
                                        <tr>
                                            <th scope="col" class="csW csBG4">
                                                <label class="control control--checkbox">
                                                    <input type="checkbox" name="jsSelectAll" class="jsSelectAll" value="all" />
                                                    <div class="control__indicator" style="margin-top: -11px;"></div>
                                                </label>
                                            </th>
                                            <th scope="col" class="csW csBG4">
                                                Employees (2)
                                            </th>
                                            <th scope="col" class="csW csBG4">
                                                Regular Hours (RH)<br />
                                                Overtime (OT/DOT)
                                            </th>
                                            <th scope="col" class="csW csBG4">
                                                Additional<br />
                                                Earnings
                                            </th>
                                            <th scope="col" class="csW csBG4">
                                                Gross Pay (GP)<br />
                                                Reimbursement (R)<br />
                                                Payment Method
                                            </th>
                                            <th scope="col" class="csW csBG4">
                                                Actions
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