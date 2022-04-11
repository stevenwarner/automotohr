<?php
    $last_time = "00:00";
    $markers = [];
    // Calculate time
    $ct = CalculateTime($lists, $currentEmployee['sid']);
    // Seconds to HM
    $totalTime = GetHMSFromSeconds($ct['total_minutes']);
    $todayWorked = GetHMSFromSeconds($ct['total_worked_minutes']);
    $todayBreak = GetHMSFromSeconds($ct['total_break_minutes']);
    $overtime = GetHMSFromSeconds($ct['total_overtime_minutes']);
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
                            Manage Time Sheet
                        </h1>
                    </div>
                    <div class="col-xs-12c col-md-4 text-right">
                        <a href="<?=current_url().GetParams('export=1');?>" class="btn btn-orange">
                            <i class="fa fa-download" aria-hidden="true"></i>&nbsp;Export In CSV
                        </a>
                    </div>
                </div>
                <!--  -->
                <p class="csF14 csB4 pa10"> <?=
                    reset_datetime([
                        'datetime' => $lists[0]['action_date_time'],
                        'format' => DATE,
                        'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                        'new_zone' => $currentEmployee['timezone'],
                        '_this' => $this
                    ]);
                ?></p>
                <!--  -->
                <div class="csPageBox">
                    <div class="csPageBody">
                        <div class="row">
                            <div class="col-sm-12">
                                <h2 class="csF20 csB7 pl10">Employee details</h2>
                            </div>
                        </div>
                        <!--  -->
                        <div class="row">
                            <div class="col-sm-12 col-md-8">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <caption></caption>
                                        <tbody>
                                            <tr>
                                                <td><strong>Name</strong></td>
                                                <td><?=$currentEmployee['name'];?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Role</strong></td>
                                                <td><?=$currentEmployee['role'];?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Timezone</strong></td>
                                                <td><?=$currentEmployee['timezone'];?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Workable Shift Time</strong></td>
                                                <td>
                                                    <span class="jsAttendanceModifyRow">
                                                        <?=GetEmployeeShiftTime($currentEmployee['shift_hours'], $currentEmployee['shift_minutes'], 't');?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Employee Since</strong></td>
                                                <td><?=formatDateToDB($currentEmployee['joined_on'], DB_DATE, DATE);?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <img src="<?=$currentEmployee['image'];?>" class="img-responsive" style="width: auto !important; margin: auto;" alt=""/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-3 text-center">
                        <div class="csPageBox csRadius5">
                            <div class="csPageBoxHeader">
                                <h4 class="csF16 csB7">Clocked Time</h4>
                            </div>
                            <div class="csPageBoxBody">
                                <p class="csF40 csB7" style="margin-top: 30px;">
                                    <span class="text-success"><?=$todayWorked['hours'];?></span>
                                    <span class="text-success">:</span>
                                    <span class="text-success"><?=$todayWorked['minutes'];?></span>
                                <p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-3 text-center">
                        <div class="csPageBox csRadius5">
                            <div class="csPageBoxHeader">
                                <h4 class="csF16 csB7">Break Time</h4>
                            </div>
                            <div class="csPageBoxBody">
                                <p class="csF40 csB7" style="margin-top: 30px;">
                                    <span class="text-warning"><?=$todayBreak['hours'];?></span>
                                    <span class="text-warning">:</span>
                                    <span class="text-warning"><?=$todayBreak['minutes'];?></span>
                                <p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-3 text-center">
                        <div class="csPageBox csRadius5">
                            <div class="csPageBoxHeader">
                                <h4 class="csF16 csB7">Clocked Time (Inc. breaks)</h4>
                            </div>
                            <div class="csPageBoxBody">
                                <p class="csF40 csB7" style="margin-top: 30px;">
                                    <span><?=$totalTime['hours'];?></span>
                                    <span>:</span>
                                    <span><?=$totalTime['minutes'];?></span>
                                <p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-3 text-center">
                        <div class="csPageBox csRadius5">
                            <div class="csPageBoxHeader">
                                <h4 class="csF16 csB7">Overtime</h4>
                            </div>
                            <div class="csPageBoxBody">
                                <p class="csF40 csB7" style="margin-top: 30px;">
                                    <span class="text-danger"><?=$overtime['hours'];?></span>
                                    <span class="text-danger">:</span>
                                    <span class="text-danger"><?=$overtime['minutes'];?></span>
                                <p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="csPageBox">
                    <div class="csPageBody">
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <h2 class="csF20 csB7 pl10 pr10">Footprints</h2>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div id="map" style="width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="csPageBox">
                    <div class="csPageBody">
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <h2 class="csF20 csB7 pl10 pr10">Time Sheet
                                    <span class="pull-right">
                                        <button class="btn btn-orange jsAttendanceAddNewSlot" data-attendance_sid="<?=$attendance_sid;?>">
                                            <i class="fa fa-plus-circle"  aria-hidden="true"></i>&nbsp;Add Slot
                                        </button>
                                    </span>
                                </h2>
                            </div>
                        </div>
                        <!--  -->
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="table-responsive pl10 pr10">
                                    <?php $this->load->view('loader_new', ['id' => 'jsAttendanceManageLoader']); ?>
                                    <table class="table table-striped">
                                        <caption></caption>
                                        <thead>
                                            <tr>
                                                <th scope="col">Status</th>
                                                <th scope="col">Action Date</th>
                                                <th scope="col">Action Time</th>
                                                <th scope="col" class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="jsAddAttendanceSlot" style="display: none;">
                                                <input type="hidden" id="jsAttendanceSlotID" value="">
                                                <td class="vam">
                                                    <select id="jsAttendanceStatus" class="form-control">
                                                    </select>
                                                </td>
                                                <td class="vam text-center">
                                                    <input type="text" id="jsAttendanceDate" class="form-control" readonly value="<??>"/>
                                                </td>
                                                <td class="vam text-center">
                                                    <input type="text" id="jsAttendanceTime" class="form-control jsTimeField" placeholder="HH:MM" value=""/>
                                                </td>
                                               
                                                <td class="vam text-center">
                                                    <button class="btn btn-orange jsAttendanceSaveSlot">
                                                        <i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;Add
                                                    </button>

                                                    <button class="btn btn-black jsAttendanceCancelSlot">
                                                        <i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;Cancel
                                                    </button>
                                                </td>
                                            </tr>
                                            <?php if(!empty($lists)): ?>
                                                <?php foreach($lists as $key => $list): ?>
                                                    <?php 
                                                        //
                                                        if(!empty($list['latitude']) || !empty($list['longitude'])){
                                                            $markers[] = ['lat' => $list['latitude'], 'lng' => $list['longitude'], 'action' => GetCleanedAction($list['action'])];
                                                        }    
                                                    ?>
                                                    <tr class="jsAttendanceMyList" data-id="<?=$list['sid'];?>">
                                                        <td class="vam">
                                                            <strong class="text-<?=GetActionColor($list['action'])?>">
                                                                <?=GetCleanedAction($list['action']);?>
                                                            </strong>
                                                        </td>
                                                        <td class="vam text-center">
                                                            <input type="text" class="form-control" readonly value="<?=
                                                                reset_datetime([
                                                                    'datetime' => $list['action_date_time'],
                                                                    'format' => DATE,
                                                                    'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                                                                    'new_zone' => $currentEmployee['timezone'],
                                                                    '_this' => $this
                                                                ]);
                                                            ?>"/>
                                                        </td>
                                                        <td class="vam text-center">
                                                            <?php
                                                                $slot_time = reset_datetime([
                                                                    'datetime' => $list['action_date_time'],
                                                                    'format' => MD,
                                                                    'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                                                                    'new_zone' => $currentEmployee['timezone'],
                                                                    '_this' => $this
                                                                ]);
                                                                //
                                                                if ($last_time == "00:00") {
                                                                    $last_time = $slot_time;
                                                                }
                                                                
                                                            ?>
                                                            <input type="text" class="form-control jsTimeField" placeholder="HH:MM" value="<?=$slot_time;?>"/>
                                                        </td>
                                                       
                                                        <td class="vam text-center">
                                                            <button class="btn btn-orange jsAttendanceManageUpdate">
                                                                <i class="fa fa-edit" aria-hidden="true"></i>&nbsp;Update
                                                            </button>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="jsAttendanceLastSlotTime" value="<?php echo $last_time; ?>">


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
                lat: parseFloat(data[0].lat),
                lng: parseFloat(data[0].lng)
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