<br />
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 text-right">
            <a href="<?= base_url("dashboard"); ?>" class="btn csW csBG4 csF16 csRadius5">
                <i class="fa fa-arrow-left csF16" aria-hidden="true"></i>
                &nbsp;Dashboard
            </a>
            <a href="<?= base_url("attendance/overview"); ?>" class="btn csW csBG3 csF16 csRadius5">
                <i class="fa fa-eye csF16" aria-hidden="true"></i>
                &nbsp;Overview
            </a>
        </div>
    </div>
    <br />

    <div class="row">
        <div class="col-sm-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="csF16 p0 csF3">
                        <strong>
                            <i class="fa fa-clock-o csF16" aria-hidden="true"></i>
                            &nbsp;My Time
                        </strong>
                    </h2>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <div class="jsAttendanceCurrentClockDateTime csF20"></div>
                        </div>

                        <div class="col-sm-12 text-center">
                            <p class="csF26 text-center">
                                <span class="jsAttendanceClockHour">00</span>
                                <span>:</span>
                                <span class="jsAttendanceClockMinute">00</span>
                                <span>:</span>
                                <span class="jsAttendanceClockSeconds">00</span>
                            </p>
                            <p>Clocked Out: Today at 04:47 AM</p>
                            <div class="jsAttendanceBTNs text-center"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!--  -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="csF16 p0 csF3">
                        <strong>
                            <i class="fa fa-clock-o csF16" aria-hidden="true"></i>
                            &nbsp;This Week
                        </strong>
                    </h2>
                </div>
                <div class="panel-body">
                    <div id="container" style="height: 200px;"></div>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="col-sm-9">
            <!--  -->
            <div class="row">
                <div class="col-sm-12 text-right">
                    <button class="btn csRangePicker" id="jsReportRange">
                        <i class="fa fa-calendar" aria-hidden="true"></i>
                        &nbsp;<span></span>
                        <i class="fa fa-caret-down" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
            <br />
            <!--  -->
            <div class="csTimeSheetBox">
                <!-- row -->
                <div class="csTimeSheetRow">
                    <div class="csTimeSheetRowDate">
                        <strong>Mon</strong>
                        <p>Oct 29</p>
                    </div>
                    <div class="csTimeSheetRowDetails">
                        0h 00m
                    </div>
                </div>

                <!-- row -->
                <div class="csTimeSheetRow">
                    <div class="csTimeSheetRowDate">
                        <strong>Tue</strong>
                        <p>Oct 29</p>
                    </div>
                    <div class="csTimeSheetRowDetails">
                        0h 00m
                    </div>
                </div>
                <!-- row -->
                <div class="csTimeSheetRow active">
                    <div class="csTimeSheetRowDate">
                        <strong>Wed</strong>
                        <p>Oct 29</p>
                    </div>
                    <div class="csTimeSheetRowDetails">
                        16h 00m
                    </div>
                </div>

                <!-- row -->
                <div class="csTimeSheetRow">
                    <div class="csTimeSheetRowDate">
                        <strong>Tue</strong>
                        <p>Oct 29</p>
                    </div>
                    <div class="csTimeSheetRowDetails">
                        0h 00m
                    </div>
                </div>

                <!-- row -->
                <div class="csTimeSheetRow">
                    <div class="csTimeSheetRowDate">
                        <strong>Tue</strong>
                        <p>Oct 29</p>
                    </div>
                    <div class="csTimeSheetRowDetails">
                        0h 00m
                    </div>
                </div>

                <!-- row -->
                <div class="csTimeSheetRow">
                    <div class="csTimeSheetRowDate">
                        <strong>Tue</strong>
                        <p>Oct 29</p>
                    </div>
                    <div class="csTimeSheetRowDetails">
                        0h 00m
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script src="https://code.highcharts.com/highcharts.js"></script>

<script>
    Highcharts.chart('container', {
        chart: {
            type: 'column'
        },
        title: {
            text: "22h 50m",
            align: 'left'
        },
        xAxis: {
            categories: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            crosshair: true,
            accessibility: {
                description: 'Week days'
            }
        },
        tooltip: {
            valueSuffix: ''
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            name: 'Worked time',
            data: [4, 5, 10, 0, 0, 0, 0]
        }, ]
    });
</script>