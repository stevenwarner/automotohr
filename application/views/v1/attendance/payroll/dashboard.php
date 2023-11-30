<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
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
                                    <a class="btn btn-black" href="<?= base_url() . 'employee_profile/' . $userId; ?>">
                                        <i class="fa fa-arrow-left"></i>
                                        Employee Profile
                                    </a>
                                </div>
                            </div>
                            <hr />
                            <!-- main content area -->
                            <div class="row">
                                <!-- pay schedule -->
                                <div class="col-md-4">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h3 class="text-medium panel-heading-text">
                                                <i class="fa fa-calendar text-orange" aria-hidden="true"></i>
                                                Pay schedule
                                            </h3>
                                        </div>
                                        <div class="panel-body">
                                            <p class="text-medium">
                                                <strong>Weekly</strong>
                                                <br />
                                                <span class="text-small">Pay frequency</span>
                                            </p>
                                            <p class="text-medium">
                                                <strong>Twice per month</strong>
                                                <br />
                                                <span class="text-small">Pay frequency</span>
                                            </p>
                                            <p class="text-medium">
                                                <strong><?= formatDateToDB("2023-11-29", DB_DATE, DATE); ?></strong>
                                                <br />
                                                <span class="text-small">Pay period start date</span>
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
                                <div class="col-md-4">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h3 class="text-medium panel-heading-text">
                                                <i class="fa fa-money text-orange" aria-hidden="true"></i>
                                                Job & wage
                                            </h3>
                                        </div>
                                        <div class="panel-body">
                                            <p class="text-medium">
                                                <strong>Software Engineer II</strong>
                                                <br />
                                                <span class="text-small">Position</span>
                                            </p>
                                            <p class="text-medium">
                                                <strong>$20.00 /hour</strong>
                                                <br />
                                                <span class="text-small">Wage</span>
                                            </p>
                                            <p class="text-medium">
                                                <strong>x1.5</strong>
                                                <br />
                                                <span class="text-small">Overtime Multiplier</span>
                                            </p>
                                            <p class="text-medium">
                                                <strong>x2.0</strong>
                                                <br />
                                                <span class="text-small">Double Overtime Multiplier</span>
                                            </p>
                                        </div>
                                        <div class="panel-footer text-center">
                                            <button class="btn btn-yellow">
                                                <i class="fa fa-edit" aria-hidden="true"></i>
                                                Edit wage
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- rules -->
                                <div class="col-md-4">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h3 class="text-medium panel-heading-text">
                                                <i class="fa fa-money text-orange" aria-hidden="true"></i>
                                                Overtime rules
                                            </h3>
                                        </div>
                                        <div class="panel-body">
                                            <p class="text-medium">
                                                <strong>40 hours week</strong>
                                            </p>
                                            <p class="text-medium">
                                                <strong>8 hours day, 40 hours week</strong>
                                            </p>
                                        </div>
                                        <div class="panel-footer text-center">
                                            <button class="btn btn-yellow">
                                                <i class="fa fa-edit" aria-hidden="true"></i>
                                                Edit Overtime rules
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