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
                                <div class="col-sm-12 text-right">
                                    <a href="<?= base_url('payrolls/history'); ?>" class="btn csW csBG3 csF16">
                                        <i class="fa fa-history csF16" aria-hidden="true"></i>
                                        &nbsp;Payroll history
                                    </a>
                                </div>
                            </div>
                            <?php if (!$regularPayrolls['current'] && !$regularPayrolls['late']) { ?>
                                <?php $this->load->view('v1/no_data', [
                                    'message' => 'No payroll found.'
                                ]); ?>
                            <?php } else { ?>

                                <?php if ($regularPayrolls['current']) { ?>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <!--  -->
                                            <div class="panel panel-success">
                                                <div class="panel-heading">
                                                    <h3 class="csF16 csW" style="margin: 0;">
                                                        <strong>
                                                            Up next
                                                        </strong>
                                                    </h3>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <label class="label label-danger csF12">
                                                                <i class="fa fa-info-circle csF12"></i>
                                                                &nbsp;Due in <?= getDueDate($regularPayrolls['current']['end_date']); ?>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <!--  -->
                                                    <div class="row">
                                                        <br>
                                                        <div class="col-sm-12">
                                                            <strong>
                                                                Pay Period
                                                            </strong>
                                                            <p>
                                                                <?= formatDateToDB(
                                                                    $regularPayrolls['current']['start_date'],
                                                                    DB_DATE,
                                                                    DATE
                                                                ); ?> -
                                                                <?= formatDateToDB(
                                                                    $regularPayrolls['current']['end_date'],
                                                                    DB_DATE,
                                                                    DATE
                                                                ); ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <!--  -->
                                                    <div class="row">
                                                        <br>
                                                        <div class="col-sm-12">
                                                            <strong>
                                                                Payday
                                                            </strong>
                                                            <p>
                                                                <?= formatDateToDB(
                                                                    $regularPayrolls['current']['check_date'],
                                                                    DB_DATE,
                                                                    DATE
                                                                ); ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <!--  -->
                                                    <div class="row">
                                                        <br>
                                                        <div class="col-sm-12">
                                                            <a href="<?= base_url('payrolls/regular/' . $regularPayrolls['current']['sid']); ?>" class="btn csW csBG3 csF16">
                                                                Run Payroll
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>

                                <?php if ($regularPayrolls['late']) { ?>
                                    <h1 class="csF16">
                                        <strong>
                                            Late payrolls
                                        </strong>
                                    </h1>
                                    <?php foreach ($regularPayrolls['late'] as $value) { ?>
                                        <div class="panel">
                                            <div class="panel-body csRadius5">
                                                <div class="row">
                                                    <div class="col-sm-4 col-xs-12">
                                                        <strong>
                                                            Pay period
                                                            &nbsp;
                                                        </strong>
                                                        <label class="label label-danger csF12">
                                                            <i class="fa fa-info-circle csF12"></i>
                                                            &nbsp;Due in -<?= getDueDate($value['check_date']); ?>
                                                        </label>
                                                        <p style="margin-top: 5px">
                                                            <?= formatDateToDB(
                                                                $value['start_date'],
                                                                DB_DATE,
                                                                DATE
                                                            ); ?> -
                                                            <?= formatDateToDB(
                                                                $value['end_date'],
                                                                DB_DATE,
                                                                DATE
                                                            ); ?>
                                                        </p>
                                                    </div>
                                                    <div class="col-sm-4 col-xs-12">
                                                        <strong>
                                                            Payday
                                                        </strong>
                                                        <p>
                                                            <?= formatDateToDB(
                                                                $value['check_date'],
                                                                DB_DATE,
                                                                DATE
                                                            ); ?>
                                                        </p>
                                                    </div>
                                                    <div class="col-sm-4 col-xs-12 text-right">
                                                        <a href="<?= base_url('payrolls/regular/' . $value['sid']); ?>" class="btn csW csBG3 csF16">Run Payroll</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        </div>
                        <div class="panel-footer">
                            <h1 class="csF16">
                                <strong>
                                    Other payroll options
                                </strong>
                            </h1>
                            <hr />
                            <div class="row">

                                <div class="col-sm-4 col-xs-12 ">
                                    <div class="panel">
                                        <a href="<?= base_url('payrolls/bonus'); ?>">
                                            <div class="panel-body csBox">
                                                <h1 class="csF16">
                                                    <strong>
                                                        Bonus Payroll
                                                    </strong>
                                                </h1>
                                                <p class="csF16">
                                                    Reward a team member with a bonus, gift, or commission.
                                                </p>
                                                <br>
                                                <button class="btn btn-link csF16">
                                                    <i class="fa fa-long-arrow-right csF16" aria-hidden="true"></i>
                                                    &nbsp;Run Bonus Payroll
                                                </button>
                                            </div>
                                        </a>
                                    </div>
                                </div>

                                <div class="col-sm-4 col-xs-12 ">
                                    <div class="panel">
                                        <a href="<?= base_url('payrolls/off-cycle'); ?>">
                                            <div class="panel-body csBox">
                                                <h1 class="csF16">
                                                    <strong>
                                                        Off-Cycle Payroll
                                                    </strong>
                                                </h1>
                                                <p class="csF16">
                                                    Run a payroll outside of your regular pay schedule.
                                                </p>
                                                <br>
                                                <br>
                                                <button class="btn btn-link csF16">
                                                    <i class="fa fa-long-arrow-right csF16" aria-hidden="true"></i>
                                                    &nbsp;Run Off-Cycle Payroll
                                                </button>
                                            </div>
                                        </a>
                                    </div>
                                </div>

                                <div class="col-sm-4 col-xs-12 ">
                                    <div class="panel">
                                        <a href="<?= base_url('payrolls/corrections'); ?>">
                                            <div class="panel-body csBox">
                                                <h1 class="csF16">
                                                    <strong>
                                                        Payroll Corrections
                                                    </strong>
                                                </h1>
                                                <p class="csF16">
                                                    Adjust or cancel a recent payroll.
                                                </p>
                                                <br>
                                                <br>
                                                <button class="btn btn-link csF16">
                                                    <i class="fa fa-long-arrow-right csF16" aria-hidden="true"></i>
                                                    &nbsp;Adjust or cancel payroll
                                                </button>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<style>
    .csBox {
        color: #000;
        border: 2px solid #ccc;
        border-radius: 5px;
        min-height: 220px;
    }

    .csBox:hover {
        border: 2px solid rgba(253, 122, 42, .5);
        background-color: rgba(253, 122, 42, .5);
    }
</style>