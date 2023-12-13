<?php $monthDates = getMonthDatesByYearAndMonth($year, $month); ?>
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
                            <a href="<?= base_url("settings/shifts/breaks"); ?>" class="btn btn-orange">
                                <i class="fa fa-cogs" aria-hidden="true"></i>
                                &nbsp;Manage Breaks
                            </a>
                            <a href="<?= base_url("settings/shifts/templates"); ?>" class="btn btn-orange">
                                <i class="fa fa-cogs" aria-hidden="true"></i>
                                &nbsp;Manage Shift Templates
                            </a>
                        </div>
                    </div>
                    <br />

                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>

                    <div role="tabpanel">
                        <!-- Page content -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h2 class="text-large weight-6 panel-heading-text">
                                    Scheduling
                                </h2>
                                <p class="text-small myt-10">
                                    Manage shift scheduling from one place.
                                </p>
                            </div>
                            <div class="panel-body">
                                <!--  -->
                                <div class="row">
                                    <div class="col-sm-6 text-left">
                                        <button class="btn btn-orange">
                                            <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                            Apply Template
                                        </button>
                                        <button class="btn btn-orange">
                                            <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                            Create
                                        </button>
                                        <button class="btn btn-red">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                            Delete
                                        </button>
                                    </div>
                                    <div class="col-sm-6 text-right">
                                        <button class="btn btn-blue">
                                            <i class="fa fa-filter" aria-hidden="true"></i>
                                            Filters
                                        </button>
                                    </div>
                                </div>
                                <br />
                                <!--  -->
                                <div class="row">
                                    <div class="col-sm-12">
                                        <p class="text-small">
                                            <span class="circle circle-orange"></span>
                                            Geo Fence
                                        </p>
                                    </div>
                                </div>
                                <!-- Main -->
                                <div class="schedule-container myt-10">
                                    <div class="row">
                                        <div class="col-sm-3" style="padding-right: 0">
                                            <div class="schedule-sidebar">
                                                <!-- navigator -->
                                                <div class="schedule-navigator">
                                                    <span class="schedule-navigator-arrow schedule-navigator-left-arrow">
                                                        <i class="fa fa-chevron-left" aria-hidden="true"></i>
                                                    </span>
                                                    <span class="schedule-navigator-text">
                                                        <?= formatDateToDB($monthDates[0], "D d", "M d, y"); ?> -
                                                        <?= formatDateToDB($monthDates[count($monthDates) - 1], "D d", "M d, y"); ?>
                                                    </span>
                                                    <span class="schedule-navigator-arrow schedule-navigator-right-arrow">
                                                        <i class="fa fa-chevron-right" aria-hidden="true"></i>
                                                    </span>
                                                </div>
                                                <!-- employee boxes -->
                                                <?php if ($employees) {
                                                    foreach ($employees as $employee) {
                                                ?>
                                                        <div class="schedule-employee-row" data-id="<?= $employee["userId"]; ?>">
                                                            <div class="row">
                                                                <div class="col-sm-2">
                                                                    <img src="<?= getImageURL($employee["profile_picture"]); ?>" alt="" />
                                                                </div>
                                                                <div class="col-sm-8">
                                                                    <p class="text-small weight-6 myb-0">
                                                                        <?= remakeEmployeeName($employee, true, true); ?>
                                                                    </p>
                                                                    <p class="text-small">
                                                                        <?= remakeEmployeeName($employee, false); ?>
                                                                    </p>
                                                                </div>
                                                                <div class="col-sm-2 text-right">
                                                                    <span class="text-small">
                                                                        0h
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                <?php
                                                    }
                                                }
                                                ?>
                                                <!-- planned hours -->
                                                <div class="schedule-footer">
                                                    <p class="text-medium weight-6 text-center">
                                                        Planned Hours
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-9" style="padding-left: 0">
                                            <div class="schedule-row-container">
                                                <?php foreach ($monthDates as $monthDate) { ?>
                                                    <!-- column-->
                                                    <div class="schedule-column-container" data-date="" data-eid="">
                                                        <div class="schedule-column-header">
                                                            <p class="text-center text-small">
                                                                <?= $monthDate; ?>
                                                            </p>
                                                        </div>
                                                        <?php if ($employees) {
                                                            foreach ($employees as $employee) {
                                                        ?>
                                                                <div class="schedule-column schedule-column-clickable schedule-column-<?= $employee["userId"]; ?> text-center" data-eid="<?= $employee["userId"]; ?>">
                                                                    <?php if (false) { ?>
                                                                        <div class="schedule-item">
                                                                            <span class="circle circle-orange"></span>
                                                                            <p class="text-small">
                                                                                09:00AM - 06:00PM
                                                                            </p>
                                                                        </div>
                                                                    <?php } elseif (false) { ?>
                                                                        <div class="schedule-dayoff hidden">
                                                                            <button class="btn btn-red text-small btn-xs">
                                                                                <i class="fa fa-stamp"></i>
                                                                                Day Off
                                                                            </button>
                                                                        </div>
                                                                    <?php } else { ?>

                                                                    <?php } ?>
                                                                </div>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                        <div class="schedule-footer schedule-border">
                                                            <p class="text-small text-center">
                                                                0h
                                                            </p>
                                                        </div>
                                                    </div>
                                                <?php } ?>
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
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        const rows = document.getElementsByClassName("schedule-employee-row")

        for (let index in rows) {
            if (typeof rows[index].getAttribute !== "undefined") {

                const employeeId = parseInt(rows[index].getAttribute("data-id"));
                const height = rows[index].offsetHeight
                const cls = document.getElementsByClassName("schedule-column-" + employeeId);

                for (let i0 in cls) {
                    if (typeof cls[i0].getAttribute !== "undefined") {
                        cls[i0].setAttribute("style", "height:" + height + "px");
                    }
                }
            }
        }
    })
</script>