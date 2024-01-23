<br />
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 text-right">
            <a href="<?= base_url("dashboard"); ?>" class="btn btn-black">
                <i class="fa fa-arrow-left csF16" aria-hidden="true"></i>
                &nbsp;Dashboard
            </a>
            <a href="<?= base_url("attendance/my/timesheet"); ?>" class="btn btn-orange">
                <i class="fa fa-eye csF16" aria-hidden="true"></i>
                &nbsp;Time sheet
            </a>
        </div>
    </div>
    <hr />
    <div class="row">
        <!-- Sidebar -->
        <div class="col-sm-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="text-large">
                        <strong>
                            <i class="fa fa-clock-o text-orange" aria-hidden="true"></i>
                            &nbsp;My Time
                        </strong>
                    </h2>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <div class="jsAttendanceCurrentClockDateTime text-medium"></div>
                        </div>

                        <div class="col-sm-12 text-center">
                            <p class="csF26 text-center text-xxxl">
                                <span class="jsAttendanceClockHour"></span>
                                <span class="jsAttendanceClockSeparator"></span>
                                <span class="jsAttendanceClockMinute"></span>
                                <span class="jsAttendanceClockSeparator"></span>
                                <span class="jsAttendanceClockSeconds"></span>
                            </p>
                            <div class="jsAttendanceBTNs text-center"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Right side -->
        <div class="col-sm-9">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="text-large">
                        <strong>
                            <i class="fa fa-clock-o text-orange" aria-hidden="true"></i>
                            &nbsp;Time logged between (<?= formatDateToDB($startDate, DB_DATE, DATE); ?> - <?= formatDateToDB($endDate, DB_DATE, DATE); ?>)
                        </strong>
                    </h2>
                </div>
                <div class="panel-body" style="position: relative">
                    <div style="height: 400px;" id="container"></div>
                    <?php $this->load->view("v1/loader", [
                        "id" => "jsWeekGraph"
                    ]); ?>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="text-large">
                        <strong>
                            <i class="fa fa-clock-o text-orange" aria-hidden="true"></i>
                            &nbsp;Today's footprints
                        </strong>
                    </h2>
                </div>
                <div class="panel-body">
                    <div id="map" style="width: 100%; height: 400px;"></div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="text-large">
                        <strong>
                            <i class="fa fa-clock-o text-orange" aria-hidden="true"></i>
                            &nbsp;Time Entries
                        </strong>
                    </h2>
                </div>
                <div class="panel-body jsTimeEntriesBox">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <caption></caption>
                            <thead>
                                <tr>
                                    <th class="csW csBG4" scope="col">Type</th>
                                    <th class="csW csBG4 text-right" scope="col">Time</th>
                                    <th class="csW csBG4 text-center" scope="col">On location</th>
                                    <th class="csW csBG4 text-right" scope="col">Duration</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>