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
                        </div>
                    </div>
                    <br />

                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>

                    <div role="tabpanel">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="text-medium <?= $status === "active" ? "active" : "bg-default"; ?>">
                                <a href="<?= base_url("overtimerules/active"); ?>">
                                    Active Overtime Rules
                                </a>
                            </li>
                            <li role="presentation" class="text-medium <?= $status === "inactive" ? "active" : "bg-default"; ?>">
                                <a href="<?= base_url("overtimerules/inactive"); ?>">
                                    InActive Overtime Rules
                                </a>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <!-- Page content -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <h2 class="text-medium panel-heading-text">
                                            <i class="fa fa-cogs text-orange" aria-hidden="true"></i>
                                            &nbsp;
                                            <strong>
                                                Company Overtime Rules
                                            </strong>
                                        </h2>
                                    </div>
                                    <div class="col-sm-6 text-right">
                                        <a href="javascript:void(0)" class="btn btn-orange jsAddOvertimeRuleBtn">
                                            <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                            &nbsp;Add Overtime Rule
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <?php if ($overtimeRules) {
                                        foreach ($overtimeRules as $v0) {
                                    ?>
                                            <div class="col-md-3 jsBox" data-id="<?= $v0["sid"]; ?>">
                                                <div class="panel panel-default csRelative">
                                                    <div class="panel-body">

                                                        <p class="text-medium">
                                                            <span class="text-small">Name</span>
                                                            <br />
                                                            <strong><?= $v0["rule_name"] ?></strong>
                                                        </p>

                                                        <p class="text-medium">
                                                            <span class="text-small">Overtime multiplier</span>
                                                            <br />
                                                            <strong><?= $v0["overtime_multiplier"] ?>x</strong>
                                                        </p>
                                                        <p class="text-medium">
                                                            <span class="text-small">Double time multiplier</span>
                                                            <br />
                                                            <strong><?= $v0["double_overtime_multiplier"] ?>x</strong>
                                                        </p>
                                                    </div>
                                                    <div class="panel-footer text-center">
                                                        <button class="btn btn-yellow jsEditOvertimeRuleBtn">
                                                            <i class="fa fa-edit" aria-hidden="true"></i>
                                                            &nbsp;Edit
                                                        </button>
                                                        <button class="btn btn-red jsDeleteOvertimeRuleBtn">
                                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                                            &nbsp;Delete
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                    <?php
                                        }
                                    } else {
                                        $this->load->view("v1/no_data");
                                    } ?>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>