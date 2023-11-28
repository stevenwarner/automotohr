<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/settings_left_menu_administration'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <!-- Page header -->
                    <div class="page-header-area">
                        <span class="page-heading down-arrow">
                            <?php $this->load->view('manage_employer/company_logo_name'); ?>
                        </span>
                    </div>
                    <!-- Page title -->
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <a href="<?= base_url("my_settings"); ?>" class="btn btn-black">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i>
                                &nbsp;Settings
                            </a>
                            <a href="<?= base_url("schedules"); ?>" class="btn btn-black">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i>
                                &nbsp;Company Pay Schedules
                            </a>
                        </div>
                    </div>
                    <br />
                    <!-- Page content -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h2 class="text-large panel-heading-text">
                                        <i class="fa fa-plus-circle text-orange" aria-hidden="true"></i>
                                        &nbsp;
                                        <strong>
                                            Add A Company Pay Schedule
                                        </strong>
                                    </h2>
                                </div>
                            </div>
                        </div>
                        <form action="javascript:void(0)" id="jsAddScheduleForm">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <p class="text-medium text-red">
                                            <strong>
                                                Pick what frequency you’d like to run payroll.
                                            </strong>
                                        </p>
                                        <p class="text-medium text-red">
                                            <strong>
                                                Why do we need to ask for this? We need to know when to pay your employees. Some states have laws around when you must pay your employees. Please choose pay schedules that are legal for your employees.
                                            </strong>
                                        </p>
                                        <p class="text-medium text-red">
                                            <strong>
                                                When selecting your pay date, please account for the 2 days it will take to process payroll.
                                            </strong>
                                        </p>
                                    </div>
                                </div>
                                <hr />

                                <!--  -->
                                <div class="form-group">
                                    <label class="text-medium">
                                        Name
                                    </label>
                                    <input type="text" class="form-control" name="name" placeholder="Weekly" />
                                </div>

                                <!--  -->
                                <div class="form-group">
                                    <label class="text-medium">
                                        Pay frequency
                                        <strong class="text-red">
                                            *
                                        </strong>
                                    </label>
                                    <select name="pay_frequency" class="form-control">
                                        <option selected="selected" value="Every week">Every week</option>
                                        <option value="Every other week">Every other week</option>
                                        <option value="Twice a month: 15th and last day of the month">Twice a month: 15th and last day of the month</option>
                                        <option value="Twice a month: Custom">Twice a month: Custom</option>
                                        <option value="Monthly: last day of the month">Monthly: last day of the month</option>
                                    </select>
                                </div>

                                <!--  -->
                                <div class="form-group">
                                    <label class="text-medium">
                                        First pay date
                                        <strong class="text-red">
                                            *
                                        </strong>
                                    </label>
                                    <div class="input-group">
                                        <input type="date" class="form-control" name="first_pay_date" placeholder="MM/DD/YYYY" />
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>

                                <!--  -->
                                <div class="form-group">
                                    <label class="text-medium">
                                        Deadline to run payroll
                                        <strong class="text-red">
                                            *
                                        </strong>
                                    </label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="deadline_to_run_payroll" placeholder="MM/DD/YYYY" readonly />
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                                <!--  -->
                                <div class="form-group">
                                    <label class="text-medium">
                                        First pay period end date
                                        <strong class="text-red">
                                            *
                                        </strong>
                                    </label>
                                    <p class="text-red">
                                        <strong>
                                            The last date of the first pay period to help calculate future pay periods. This can be the same date as the first pay date.
                                        </strong>
                                    </p>
                                    <div class="input-group">
                                        <input type="date" class="form-control" name="first_pay_period_end_date" placeholder="MM/DD/YYYY" />
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="panel-footer text-center">
                                <button class="btn btn-orange jsAddScheduleBtn" type="submit">
                                    <i class="fa fa-save" aria-hidden="true"></i>
                                    &nbsp;Save
                                </button>
                                <a href="<?= base_url("schedules"); ?>" class="btn btn-black">
                                    <i class="fa fa-times-circle" aria-hidden="true"></i>
                                    &nbsp;Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>