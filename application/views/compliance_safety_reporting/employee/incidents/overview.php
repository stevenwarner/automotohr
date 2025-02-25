<div class="main csPageWrap">
    <div class="container-fluid">
        <div style="position: relative">
            <?php $this->load->view('loader_new', ['id' => 'jsPageLoader']); ?>
            <!--  -->
            <div class="row">
                <div class="col-lg-12 text-right">
                    <a href="<?php echo $employee['access_level'] == 'Employee' ?  base_url('employee_management_system') : base_url('dashboard'); ?>" class="btn btn-black">
                        <i class="fa fa-arrow-left"></i>
                        Dashboard
                    </a>
                    <a href="<?= base_url('compliance_safety_reporting/overview') ?>" class="btn btn-blue">
                        <i class="fa fa-pie-chart"></i>
                        Compliance Safety Reporting
                    </a>
                </div>
            </div>
            <!--  -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="page-header">
                        <h1 class="section-ttile">
                            Compliance Safety Reporting - Incidents
                        </h1>
                    </div>
                </div>
            </div>
            <div role="tabpanel">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation">
                        <a href="<?= base_url("compliance_safety_reporting/employee/overview/reports"); ?>">Reports</a>
                    </li>
                    <li role="presentation" class="active">
                        <a href="<?= base_url("compliance_safety_reporting/employee/overview/incidents"); ?>">Incidents</a>
                    </li>
                </ul>
            </div>

            <!--  -->
            <div class="row">
                <!--  -->
                <div class="col-sm-3">
                    <div class="panel panel-default">
                        <div class="panel-body" style="background: #e6e7ff; border-radius: 4px;">
                            <h2 style="">Completed: <span style="color: #ef6c34;" id="jsOverViewTrainings"><?= count($completedReports); ?></span></h2>
                            <h3 style="margin-bottom: 0px;"><span id="jsOverViewCourseDueSoon"><?= count($pendingReports); ?></span> Pending</h3>
                            <h3 style="margin-top: 0px;"><span id="jsOverViewCourseTotal"><?= count($pendingReports) + count($completedReports) + count($onHoldReports);; ?></span> Total</h3>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h1 class="panel-heading-text text-medium">
                                <strong>Incidents</strong>
                            </h1>
                        </div>
                        <div class="panel-body">
                            <div id="jsReportsGraph"></div>
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="col-sm-9">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="csTabContent">
                                <div class="csLisitingArea">
                                    <div class="csBoxWrap jsBoxWrap">

                                        <?php $this->load->view("compliance_safety_reporting/employee/incidents/panel", [
                                            "panel" => [
                                                "title" => "Pending",
                                                "sub_title" => "Compliance Safety Incidents that are currently in progress.",
                                                "data" => $pendingReports
                                            ]
                                        ]); ?>

                                        <?php $this->load->view("compliance_safety_reporting/employee/incidents/panel", [
                                            "panel" => [
                                                "title" => "Completed",
                                                "sub_title" => "Compliance Safety Incidents that have been completed",
                                                "data" => $completedReports
                                            ]
                                        ]); ?>

                                        <?php $this->load->view("compliance_safety_reporting/employee/incidents/panel", [
                                            "panel" => [
                                                "title" => "On Hold",
                                                "sub_title" => "Compliance Safety Incidents that are on hold",
                                                "data" => $onHoldReports
                                            ]
                                        ]); ?>

                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const reportGraphData = <?= json_encode([
                                "pending" => count($pendingReports),
                                "on_hold" => count($onHoldReports),
                                "completed" => count($completedReports),
                            ]); ?>;
</script>