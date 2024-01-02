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
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <caption></caption>
                            <thead>
                                <tr>
                                    <th class="csW csBG4" scope="col">Type</th>
                                    <th class="csW csBG4 text-right" scope="col">Time</th>
                                    <th class="csW csBG4 text-right" scope="col">Duration</th>
                                </tr>
                            </thead>
                            <?php if ($logs["logs"]) {
                                $totalDuration = 0;
                            ?>
                                <tbody>
                                    <?php foreach ($logs["logs"] as $v0) {
                                        $totalDuration += $v0["duration"];
                                    ?>
                                        <tr data-id="<?= $v0["sid"]; ?>">
                                            <td class="csVerticalAlignMiddle">
                                                <p class="csF16"><?= $v0["text"]; ?></p>
                                            </td>
                                            <td class="csVerticalAlignMiddle text-right">
                                                <p class="csF16"><?=
                                                                    reset_datetime([
                                                                        "datetime" => $v0["startTime"],
                                                                        "from_format" => DB_DATE_WITH_TIME,
                                                                        "format" => "h:i a",
                                                                        "_this" => $this,
                                                                        "from_timezone" => "UTC"
                                                                    ]);
                                                                    ?> - <?= $v0["endTime"] ?
                                                                                reset_datetime([
                                                                                    "datetime" => $v0["endTime"],
                                                                                    "from_format" => DB_DATE_WITH_TIME,
                                                                                    "format" => "h:i a",
                                                                                    "_this" => $this,
                                                                                    "from_timezone" => "UTC"
                                                                                ]) : "N/A";
                                                                            ?></p>
                                            </td>
                                            <td class="csVerticalAlignMiddle text-right">
                                                <p class="csF16"><?= $v0["durationText"]; ?></p>
                                            </td>
                                        </tr>
                                    <?php
                                    } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th scope="col" colspan="3" class="csVerticalAlignMiddle text-right">
                                            Total:
                                            <?= convertSecondsToTime($totalDuration);?>
                                        </th>
                                    </tr>
                                </tfoot>
                            <?php }
                            ?>
                        </table>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>

<script>
    const footprintLocations = <?= json_encode($logs["locations"]); ?>;
</script>