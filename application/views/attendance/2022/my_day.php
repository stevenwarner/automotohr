<?php 
// echo "<pre>";
// print_r($timeCounts);
// die();
// Today's worked time
$todayWorked = GetHMSFromMinutes($timeCounts['totalTodayWorked']);
$todayWorkedPercentage = (($timeCounts['totalTodayWorked'] * 100) / (8*60));

// Today's break time
$todayBreak = GetHMSFromMinutes($timeCounts['totalTodayBreaks']);
$todayBreakPercentage = (($timeCounts['totalTodayBreaks'] * 100) / (8*60));

// Today's over time
$todayOvertime = GetHMSFromMinutes($timeCounts['totalTodayOvertime']);
$todayOvertimePercentage = (($timeCounts['totalTodayOvertime'] * 100) / (8*60));

// Week's worked time including breaks
$WeekWorkedWithBreaks = GetHMSFromMinutes($timeCounts['totalWeekWorked'] + $timeCounts['totalWeekBreaks']);

// Week's worked time
$WeekWorked = GetHMSFromMinutes($timeCounts['totalWeekWorked']);
$WeekWorkedPercentage = (($timeCounts['totalWeekWorked'] * 100) / (8*60*7));

// Week's break time
$WeekBreaks = GetHMSFromMinutes($timeCounts['totalWeekBreaks']);


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
                                    <span><?=$WeekWorked['hours'];?></span>
                                    <span>:</span>
                                    <span><?=$WeekWorked['minutes'];?></span>
                                    <span>/</span>
                                    <span>40</span>
                                    <span>:</span>
                                    <span>00</span>
                                </p>

                                <div class="progress ml10 mr10">
                                    <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="" aria-valuemin="" aria-valuemax="" style="width: <?=$WeekWorkedPercentage;?>%;">
                                        <span class="sr-only"> <?=$WeekWorkedPercentage;?> % Complete</span>
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
                                    <span><?=$WeekWorkedWithBreaks['hours'];?></span>
                                    <span>:</span>
                                    <span><?=$WeekWorkedWithBreaks['minutes'];?></span>
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
                                    <span><?=$WeekBreaks['hours'];?></span>
                                    <span>:</span>
                                    <span><?=$WeekBreaks['minutes'];?></span>
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
                                    <span><?=$todayWorked['hours'];?></span>
                                    <span>:</span>
                                    <span><?=$todayWorked['minutes'];?></span>
                                    <span>/</span>
                                    <span>08</span>
                                    <span>:</span>
                                    <span>00</span>
                                </p>

                                <div class="progress ml10 mr10">
                                    <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="" aria-valuemin="" aria-valuemax="" style="width: <?=$todayWorkedPercentage;?>%;">
                                        <span class="sr-only"> <?=$todayWorkedPercentage;?> % Complete</span>
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
                                    <span><?=$todayBreak['hours'];?></span>
                                    <span>:</span>
                                    <span><?=$todayBreak['minutes'];?></span>
                                </p>

                                <div class="progress ml10 mr10">
                                    <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="" aria-valuemin="" aria-valuemax="" style="width: <?=$todayBreakPercentage;?>%;">
                                        <span class="sr-only"> <?=$todayBreakPercentage;?> % Complete</span>
                                    </div>
                                </div>
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
                                    <span><?=$todayOvertime['hours'];?></span>
                                    <span>:</span>
                                    <span><?=$todayOvertime['minutes'];?></span>
                                </p>

                                <div class="progress ml10 mr10">
                                    <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="" aria-valuemin="" aria-valuemax="" style="width: <?=$todayOvertimePercentage;?>%;">
                                        <span class="sr-only"><?=$todayOvertimePercentage;?> % Complete</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="row">
                    <div class="col-sm-12 col-md-8">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <caption></caption>
                                <thead>
                                    <tr>
                                        <th scope="col">Date</th>
                                        <th scope="col">Time</th>
                                        <th scope="col">Distance</th>
                                        <th scope="col">Status</th>
                                        <th scope="col" class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($todayList)): ?>
                                        <?php foreach($todayList as $index => $list): ?>
                                            <?php 
                                                $distance = DistanceBTWLatLon(
                                                    isset($todayList[--$index]) ? $todayList[--$index]['latitude'] : 0,
                                                    isset($todayList[--$index]) ? $todayList[--$index]['longitude'] : 0,
                                                    $list['latitude'],
                                                    $list['longitude']
                                                )
                                            ?>
                                            <tr class="jsAttendanceMyList" lat="<?=$list['latitude'];?>" lon="<?=$list['longitude'];?>">
                                                <td class="vam">
                                                    <?=reset_datetime([
                                                        'datetime' => $list['action_date_time'],
                                                        'from_format' => DB_DATE_WITH_TIME,
                                                        'format' => DATE,
                                                        'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                                                        '_this' => $this
                                                    ]);?>
                                                </td>
                                                <td class="vam">
                                                    <?=reset_datetime([
                                                        'datetime' => $list['action_date_time'],
                                                        'from_format' => DB_DATE_WITH_TIME,
                                                        'format' => TIME,
                                                        'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                                                        '_this' => $this
                                                    ]);?>
                                                </td>
                                                <td class="vam">
                                                    <strong class="text-"><?=$distance['text'];?></strong>
                                                </td>
                                                <td class="vam">
                                                    <strong class="text-<?=GetActionColor($list['action']);?>"><?=ucwords(str_replace('_', ' ', $list['action']));?></strong>
                                                </td>
                                                <td class="vam text-center">
                                                    <button class="btn btn-orange jsAttendanceViewLocation"><i class="fa fa-map" aria-hidden="true"></i>&nbsp;View Location</button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
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
                    <div class="col-sm-12 col-md-4">
                        <p class="csF16 csB7">Last Location</p>
                        <iframe 
                            width="300" 
                            height="170" 
                            frameborder="0" 
                            scrolling="no" 
                            marginheight="0" 
                            marginwidth="0" 
                            src="https://maps.google.com/maps?q=<?=$lastLocation[0];?>,<?=$lastLocation[1];?>&hl=en&z=14&amp;output=embed"
                            >
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>