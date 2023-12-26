<br />
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 text-right">
            <a href="<?= base_url("dashboard"); ?>" class="btn btn-black">
                <i class="fa fa-arrow-left csF16" aria-hidden="true"></i>
                &nbsp;Dashboard
            </a>
            <a href="<?= base_url("attendance/my"); ?>" class="btn btn-orange">
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

                        <div class="col-sm-12">
                            <hr />
                            <div class="row">
                                <div class="col-md-4">
                                    <p>
                                        <strong>This week</strong>
                                        <br> 23h 00m
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <p>
                                        <strong>Pay period</strong>
                                        <br> 23h 00m
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <a href="<?= base_url("attendance/timesheet/my"); ?>" class="btn btn-orange">
                                        Time Sheet
                                    </a>
                                </div>
                            </div>
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
                            &nbsp;Time logged in this Week (9th October, 2023 - 15 October, 2023)
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
                    <iframe src="" frameborder="0" width="100%" height="500"></iframe>
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
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <caption></caption>
                            <thead>
                                <tr>
                                    <th class="csW csBG4" scope="col">Type</th>
                                    <th class="csW csBG4 text-right" scope="col">Time</th>
                                    <th class="csW csBG4 text-right" scope="col">Duration</th>
                                    <th class="csW csBG4 text-right" scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="csVam">
                                        <p class="csF16">Clock in / out</p>
                                    </td>
                                    <td class="csVam text-right">
                                        <p class="csF16">06:00 PM - 06:00 PM</p>
                                    </td>
                                    <td class="csVam text-right">
                                        <p class="csF16">10h 5m</p>
                                    </td>
                                    <td class="csVam text-right">
                                        <button class="btn csW csBG3 csRadius5 csF16">
                                            <i class="fa fa-map" aria-hidden="true"></i>
                                            &nbsp;View location
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>