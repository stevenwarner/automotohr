<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/employer_column_left_view'); ?>
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
                        <!-- Page content -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <h2 class="text-medium panel-heading-text">
                                            <i class="fa fa-cogs text-orange" aria-hidden="true"></i>
                                            &nbsp;
                                            <strong>
                                                Company Minimum Wages
                                            </strong>
                                        </h2>
                                    </div>
                                    <div class="col-sm-6 text-right">
                                        <a href="javascript:void(0)" class="btn btn-orange jsAddOvertimeRuleBtn hidden">
                                            <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                            &nbsp;Add Minimum Wage
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body">
                                <!--  -->
                                <div class="row">
                                    <div class="col-sm-12">
                                        <p class="text-danger text-medium">
                                            <strong>
                                                Minimum wages are updated automatically and therefore do not necessitate manual addition.
                                            </strong>
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <br>
                                    <?php if ($wages) {
                                        foreach ($wages as $v0) {
                                    ?>
                                            <div class="col-md-3 jsBox" data-id="<?= $v0["sid"]; ?>">
                                                <div class="panel panel-default csRelative">
                                                    <div class="panel-body">
                                                        <p class="text-medium">
                                                            <span class="text-small">Wage</span>
                                                            <br />
                                                            <strong>$<?= $v0["wage"] ?></strong>
                                                        </p>
                                                        <p class="text-medium">
                                                            <span class="text-small">Wage type</span>
                                                            <br />
                                                            <strong><?= $v0["wage_type"] ?></strong>
                                                        </p>
                                                        <p class="text-medium">
                                                            <span class="text-small">Authority</span>
                                                            <br />
                                                            <strong><?= $v0["authority"] ?></strong>
                                                        </p>
                                                        <p class="text-medium">
                                                            <span class="text-small">Effective date</span>
                                                            <br />
                                                            <strong><?= formatDateToDB($v0["effective_date"], DB_DATE, DATE); ?></strong>
                                                        </p>
                                                        <p class="text-medium">
                                                            <span class="text-small">Notes</span>
                                                            <br />
                                                            <strong><?= $v0["notes"] ?></strong>
                                                        </p>
                                                    </div>
                                                    <div class="panel-footer text-center">
                                                        <?php if ($v0["is_custom"]) { ?>
                                                            <button class="btn btn-yellow jsEditOvertimeRuleBtn">
                                                                <i class="fa fa-edit" aria-hidden="true"></i>
                                                                &nbsp;Edit
                                                            </button>
                                                            <button class="btn btn-red jsDeleteOvertimeRuleBtn">
                                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                                                &nbsp;Delete
                                                            </button>
                                                        <?php } else { ?>
                                                            <button class="btn btn-yellow" disabled>
                                                                <i class="fa fa-edit" aria-hidden="true"></i>
                                                                &nbsp;Edit
                                                            </button>
                                                        <?php } ?>
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