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
                    <a href="<?= base_url("compliance_safety_reporting/listing") ?>" class="btn btn-orange">
                        <i class="fa fa-plus-circle"></i>
                        Add New Report
                    </a>
                </div>
            </div>
            <!--  -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="page-header">
                        <h1 class="section-ttile">
                            Compliance Safety Reporting
                        </h1>
                    </div>
                </div>
            </div>
            <!--  -->

            <!--  -->
            <div class="col-sm-3">
                <div class="panel panel-default">
                    <div class="panel-body" style="background: #e6e7ff; border-radius: 4px;">
                        <h2 style="">Completed: <span style="color: #ef6c34;" id="jsOverViewTrainings"><?= count($completedReports);?></span></h2>
                        <h3 style="margin-bottom: 0px;"><span id="jsOverViewCourseDueSoon"><?= count($pendingReports); ?></span> Pending</h3>
                        <h3 style="margin-top: 0px;"><span id="jsOverViewCourseTotal"><?= count($pendingReports) + count($completedReports) + count($onHoldReports);; ?></span> Total</h3>
                    </div>
                </div>

                <!-- <div class="panel panel-default">
                    <div class="panel-heading">
                        <h1 class="panel-heading-text text-medium">
                            <strong>Progress Graph</strong>
                        </h1>
                    </div>
                    <div class="panel-body">
                        <div id="container2"></div>
                    </div>
                </div> -->
            </div>
            <!--  -->
            <div class="col-sm-9">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="csTabContent">
                            <div class="csLisitingArea">
                                <div class="csBoxWrap jsBoxWrap">

                                    <!-- Courses in Progress Start -->
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <h1 class="panel-heading-text text-medium">
                                                        <strong>Pending</strong>
                                                    </h1>
                                                    <p class="csF14 csInfo csB7" style="font-size: 12px !important">
                                                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                        &nbsp;Compliance Safety Reports that are currently in progress.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row" id="jsCSPPending">
                                                <?php if ($pendingReports) : ?>
                                                    <?php foreach ($pendingReports as $item): ?>
                                                        <?php $this->load->view("compliance_safety_reporting/partials/display_box", [
                                                            "display_box_data" => $item
                                                        ]); ?>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <div class="col-sm-12">
                                                        <div class="alert alert-info text-center">
                                                            No reports found.
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Courses in Progress End -->

                                    <!-- Ready To Start Start -->
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <h1 class="panel-heading-text text-medium">
                                                        <strong>Completed</strong>
                                                    </h1>
                                                    <p class="csF14 csInfo csB7" style="font-size: 12px !important">
                                                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                        &nbsp;Compliance Safety Reports that have been completed.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row" id="jsCSPCompleted">
                                                <?php if ($completedReports) : ?>
                                                    <?php foreach ($completedReports as $item): ?>
                                                        <?php $this->load->view("compliance_safety_reporting/partials/display_box", [
                                                            "display_box_data" => $item
                                                        ]); ?>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <div class="col-sm-12">
                                                        <div class="alert alert-info text-center">
                                                            No reports found.
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Ready To Start End -->

                                    <!-- Past Due Start -->
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <h1 class="panel-heading-text text-medium">
                                                        <strong>Hold</strong>
                                                    </h1>
                                                    <p class="csF14 csInfo csB7" style="font-size: 12px !important">
                                                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                        &nbsp;Compliance Safety Reports that are on hold.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row" id="jsCSPHold">
                                                <?php if ($onHoldReports) : ?>
                                                    <?php foreach ($onHoldReports as $item): ?>
                                                        <?php $this->load->view("compliance_safety_reporting/partials/display_box", [
                                                            "display_box_data" => $item
                                                        ]); ?>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <div class="col-sm-12">
                                                        <div class="alert alert-info text-center">
                                                            No reports found.
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
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