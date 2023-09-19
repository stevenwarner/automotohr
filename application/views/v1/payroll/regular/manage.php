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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>