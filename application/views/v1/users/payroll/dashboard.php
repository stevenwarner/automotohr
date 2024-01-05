<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>

                            <?php $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top'); ?>
                            <!-- page header -->
                            <div class="page-header-area margin-top">
                                <span class="page-heading down-arrow">
                                    <?php $this->load->view('manage_employer/company_logo_name'); ?>
                                </span>
                            </div>
                            <!-- main buttons area -->
                            <div class="row">
                                <div class="col-sm-12 text-right">
                                    <a class="btn btn-black" href="<?= $return_title_heading_link; ?>">
                                        <i class="fa fa-arrow-left"></i>
                                        <?= $return_title_heading; ?>
                                    </a>
                                </div>
                            </div>
                            <hr />
                            <!-- main content area -->
                            <div class="row">
                                <!-- pay schedule -->
                                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h3 class="text-medium panel-heading-text">
                                                <i class="fa fa-calendar text-orange" aria-hidden="true"></i>
                                                Pay schedule
                                            </h3>
                                        </div>
                                        <div class="panel-body">
                                            <p class="text-medium">
                                                <span class="text-small">Name</span>
                                                <br />
                                                <strong><?= GetVal($paySchedule["custom_name"]); ?></strong>
                                            </p>
                                            <p class="text-medium">
                                                <span class="text-small">Pay frequency</span>
                                                <br />
                                                <strong><?= GetVal($paySchedule["frequency"]); ?></strong>
                                            </p>
                                            <?php if ($paySchedule["day_1"] && $paySchedule["day_2"]) { ?>
                                                <p class="text-medium">
                                                    <span class="text-small">First day of payment</span>
                                                    <br />
                                                    <strong>"<?= GetVal($paySchedule["day_1"]); ?>" of the month</strong>
                                                </p>
                                                <p class="text-medium">
                                                    <span class="text-small">Second day of payment</span>
                                                    <br />
                                                    <strong>"<?= GetVal($paySchedule["day_2"]); ?>" of the month</strong>
                                                </p>
                                            <?php } ?>
                                            <p class="text-medium">
                                                <span class="text-small">Pay period start date</span>
                                                <br />
                                                <strong><?= $paySchedule["anchor_pay_date"] ? formatDateToDB($paySchedule["anchor_pay_date"], DB_DATE, DATE) : "Not specified"; ?></strong>
                                            </p>
                                        </div>
                                        <div class="panel-footer text-center">
                                            <button class="btn btn-yellow jsEditPaySchedule">
                                                <i class="fa fa-edit" aria-hidden="true"></i>
                                                Edit Pay schedule
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- wage -->
                                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h3 class="text-medium panel-heading-text">
                                                <i class="fa fa-money text-orange" aria-hidden="true"></i>
                                                Job & wage
                                            </h3>
                                        </div>
                                        <div class="panel-body">
                                            <p class="text-medium">
                                                <span class="text-small">Position</span>
                                                <br />
                                                <strong>Software Engineer II</strong>
                                            </p>
                                            <p class="text-medium">
                                                <span class="text-small">Employment type</span>
                                                <br />
                                                <strong>Full time</strong>
                                            </p>
                                            <p class="text-medium">
                                                <span class="text-small">Hire date</span>
                                                <br />
                                                <strong>2023-04-55 time</strong>
                                            </p>
                                            <p class="text-medium">
                                                <span class="text-small">Wage</span>
                                                <br />
                                                <strong>$20.00 /hour</strong>
                                            </p>
                                            <p class="text-medium">
                                                <span class="text-small">Overtime Rule</span>
                                                <br />
                                                <strong>40 hours a week</strong>
                                            </p>
                                        </div>
                                        <div class="panel-footer text-center">
                                            <button class="btn btn-yellow jsEditJobWage">
                                                <i class="fa fa-edit" aria-hidden="true"></i>
                                                Edit wage
                                            </button>
                                        </div>
                                    </div>
                                </div>


                            </div>
                            <!-- time & attendance -->
                            <div class="row">
                                <div class="col-sm-4">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $this->load->view($left_navigation); ?>
            </div>
        </div>
    </div>
</div>

<script>
    const profileUserInfo = {
        userId: <?= $userId; ?>,
        userType: "<?= $userType; ?>",
        nameWithRole: "<?= remakeEmployeeName($employer); ?>"
    };
</script>