<?php
// Today's worked time
$todayWorked = GetHMSFromSeconds($timeCounts['totalTodayWorked']);
$todayWorkedPercentage = (($timeCounts['totalTodayWorked'] * 100) / (8 * 60 * 60));

// Today's break time
$todayBreak = GetHMSFromSeconds($timeCounts['totalTodayBreaks']);

// Today's over time
$todayOvertime = GetHMSFromSeconds($timeCounts['totalTodayOvertime']);

// Week's worked time including breaks
$WeekWorkedWithBreaks = GetHMSFromSeconds($timeCounts['totalWeekWorked'] + $timeCounts['totalWeekBreaks']);

// Week's worked time
$WeekWorked = GetHMSFromSeconds($timeCounts['totalWeekWorked']);
$WeekWorkedPercentage = (($timeCounts['totalWeekWorked'] * 100) / (8 * 60 * 60 * 7));

// Week's break time
$WeekBreaks = GetHMSFromSeconds($timeCounts['totalWeekBreaks']);

// Set markers
$markers = [];

?>
<div class="csPageWrap" style="background-color: #f1f1f1;">
    <!-- Nav bar -->
    <div class="container-fluid">
        <?php $this->load->view('attendance/2022/navbar'); ?>
    </div>
    <br>
    <!--  -->
    <div class="row">
        <div class="container-fluid">
            <!-- Side Bar -->
            <?php $this->load->view('employee_info_sidebar_ems'); ?>
            <!-- Main Content Area -->
            <div class="col-md-9">
                <!-- Heading -->
                <div class="row">
                    <div class="col-xs-12 col-md-8">
                        <h1 class="m0 p0 csB7">
                            Clock My Day
                        </h1>
                    </div>
                    <div class="col-xs-12 col-md-4 text-right">
                        <button class="btn btn-orange jsAttendanceBTN dn" data-type="clock_in">
                            <i class="fa fa-play" aria-hidden="true"></i>&nbsp;Clock In
                        </button>
                        <button class="btn btn-warning jsAttendanceBTN dn" data-type="break_in">
                            <i class="fa fa-pause" aria-hidden="true"></i>&nbsp;Break Start
                        </button>
                        <button class="btn btn-black jsAttendanceBTN dn" data-type="break_out">
                            <i class="fa fa-play" aria-hidden="true"></i>&nbsp;Break End
                        </button>
                        <button class="btn btn-danger jsAttendanceBTN dn" data-type="clock_out">
                            <i class="fa fa-stop" aria-hidden="true"></i>&nbsp;Clock Out
                        </button>
                    </div>
                </div>
                <!--  -->
                <div class="row">
                    <div class="col-sm-12 col-md-4 text-center">
                        <div class="csPageBox csRadius5">
                            <div class="csPageBoxHeader">
                                <h4 class="csF16 csB7">This Week</h4>
                            </div>
                            <div class="csPageBoxBody">
                                <p class="csF40 csB7" style="margin-top: 30px;">
                                    <span><?= $WeekWorked['hours']; ?></span>
                                    <span>:</span>
                                    <span><?= $WeekWorked['minutes']; ?></span>
                                    <span>/</span>
                                    <span>40</span>
                                    <span>:</span>
                                    <span>00</span>
                                </p>

                                <div class="progress ml10 mr10">
                                    <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="" aria-valuemin="" aria-valuemax="" style="width: <?= $WeekWorkedPercentage; ?>%;">
                                        <span class="sr-only"> <?= $WeekWorkedPercentage; ?> % Complete</span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-4 text-center">
                        <div class="csPageBox csRadius5">
                            <div class="csPageBoxHeader">
                                <h4 class="csF16 csB7">Clocked Time (Including breaks)</h4>
                            </div>
                            <div class="csPageBoxBody">
                                <p class="csF40 csB7" style="margin-top: 30px;">
                                    <span><?= $WeekWorkedWithBreaks['hours']; ?></span>
                                    <span>:</span>
                                    <span><?= $WeekWorkedWithBreaks['minutes']; ?></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-4 text-center">
                        <div class="csPageBox csRadius5">
                            <div class="csPageBoxHeader">
                                <h4 class="csF16 csB7">Breaks Total</h4>
                            </div>
                            <div class="csPageBoxBody">
                                <p class="csF40 csB7" style="margin-top: 30px;">
                                    <span><?= $WeekBreaks['hours']; ?></span>
                                    <span>:</span>
                                    <span><?= $WeekBreaks['minutes']; ?></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="row">
                    <div class="col-sm-12 col-md-4 text-center">
                        <div class="csPageBox csRadius5">
                            <div class="csPageBoxHeader">
                                <h4 class="csF16 csB7">Today</h4>
                            </div>
                            <div class="csPageBoxBody">
                                <p class="csF40 csB7" style="margin-top: 30px;">
                                    <span class="jsAttendanceClockHour"><?= $todayWorked['hours']; ?></span>
                                    <span>:</span>
                                    <span class="jsAttendanceClockMinute"><?= $todayWorked['minutes']; ?></span>
                                    <span>/</span>
                                    <span>08</span>
                                    <span>:</span>
                                    <span>00</span>
                                </p>

                                <div class="progress ml10 mr10">
                                    <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="" aria-valuemin="" aria-valuemax="" style="width: <?= $todayWorkedPercentage; ?>%;">
                                        <span class="sr-only"> <?= $todayWorkedPercentage; ?> % Complete</span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-4 text-center">
                        <div class="csPageBox csRadius5">
                            <div class="csPageBoxHeader">
                                <h4 class="csF16 csB7">Breaks</h4>
                            </div>
                            <div class="csPageBoxBody">
                                <p class="csF40 csB7" style="margin-top: 30px;">
                                    <span><?= $todayBreak['hours']; ?></span>
                                    <span>:</span>
                                    <span><?= $todayBreak['minutes']; ?></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-4 text-center">
                        <div class="csPageBox csRadius5">
                            <div class="csPageBoxHeader">
                                <h4 class="csF16 csB7">Over Time</h4>
                            </div>
                            <div class="csPageBoxBody">
                                <p class="csF40 csB7" style="margin-top: 30px;">
                                    <span><?= $todayOvertime['hours']; ?></span>
                                    <span>:</span>
                                    <span><?= $todayOvertime['minutes']; ?></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <p class="csF16 csB7">Foot prints</p>
                        <div id="map" style="width: 100%;"></div>
                    </div>
                </div>
                <!--  -->
                <div class="csPageBox">
                    <div class="csPageBody">
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th scope="col">Date</th>
                                                <th scope="col">Time</th>
                                                <th scope="col">Status</th>
                                                <th scope="col" class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($todayList)) : ?>
                                                <?php foreach ($todayList as $index => $list) : ?>
                                                    <?php
                                                    $distance = DistanceBTWLatLon(
                                                        isset($todayList[--$index]) ? $todayList[--$index]['latitude'] : 0,
                                                        isset($todayList[--$index]) ? $todayList[--$index]['longitude'] : 0,
                                                        $list['latitude'],
                                                        $list['longitude']
                                                    );
                                                    //
                                                    if(!empty($list['latitude']) || !empty($list['longitude'])){
                                                        $markers[] = ['lat' => $list['latitude'], 'lng' => $list['longitude'], 'action' => GetCleanedAction($list['action'])];
                                                    }
                                                    ?>
                                                    <tr class="jsAttendanceMyList" data-lat="<?= $list['latitude']; ?>" data-lon="<?= $list['longitude']; ?>">
                                                        <td class="vam">
                                                            <?= reset_datetime([
                                                                'datetime' => $list['action_date_time'],
                                                                'from_format' => DB_DATE_WITH_TIME,
                                                                'format' => DATE,
                                                                'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                                                                '_this' => $this
                                                            ]); ?>
                                                        </td>
                                                        <td class="vam">
                                                            <?= reset_datetime([
                                                                'datetime' => $list['action_date_time'],
                                                                'from_format' => DB_DATE_WITH_TIME,
                                                                'format' => TIME,
                                                                'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                                                                '_this' => $this
                                                            ]); ?>
                                                        </td>
                                                        <td class="vam">
                                                            <strong class="text-<?= GetActionColor($list['action']); ?>"><?= GetAttendanceActionText($list['action']); ?></strong>
                                                        </td>
                                                        <td class="vam text-center">
                                                            <?php if(!empty($list['latitude']) || !empty($list['longitude'])){ ?>
                                                                <button class="btn btn-orange jsAttendanceViewLocation"><i class="fa fa-map" aria-hidden="true"></i>&nbsp;View Location</button>
                                                            <?php } else { ?>
                                                                <p>-</p>
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else : ?>
                                                <tr>
                                                    <td colspan="4">
                                                        <p class="alert alert-info text-center">
                                                            No records found.
                                                        </p>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?= getCreds('AHR')->GoogleAPIKey; ?>&callback=initMap"></script>
<script>
    function initMap() {
        var data = JSON.parse('<?=json_encode($markers);?>');
        //
        if(Object.keys(data).length){
            //
            $('#map').css('height', '440px')
            //
            const myLatLng = {
                lat: <?= isset($lastLocation[0]) ? $lastLocation[0] : 0; ?>,
                lng: <?= isset($lastLocation[1]) ? $lastLocation[1] : 0; ?>
            };
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 11,
                mapTypeControl: true,
                center: myLatLng,
            });
            //
            data.map(function(mark, i){
                //
                mark = {lat: parseFloat(mark['lat']), 'lng': parseFloat(mark['lng'])};
                new google.maps.Marker({
                    position: mark,
                    map: map
                });
                //
                var oldMark = data[i - 1];
                if(oldMark !== undefined){
                    oldMark['lat'] = parseFloat(oldMark['lat']);
                    oldMark['lng'] = parseFloat(oldMark['lng']);
                    var line = new google.maps.Polyline({
                        path: [oldMark, mark],
                        map: map
                    });
                }
            });
        } else{
            $('#map').html('<p class="alert alert-info text-center csF16">No foot prints found.</p>');
        }
    }
</script>