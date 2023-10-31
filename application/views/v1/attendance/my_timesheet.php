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
    <hr />
    <div class="row">
        <div class="col-sm-4">
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
        </div>
    </div>
</div>