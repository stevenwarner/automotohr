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
                    <div class="col-xs-12 col-md-12">
                        <h1 class="m0 p0 csB7">
                            Manage Time Sheet
                        </h1>
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
                                                            <input type="text" class="form-control jsTimeField" placeholder="HH:MM" value="<?=
                                                                reset_datetime([
                                                                    'datetime' => $list['action_date_time'],
                                                                    'format' => MD,
                                                                    'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                                                                    'new_zone' => $currentEmployee['timezone'],
                                                                    '_this' => $this
                                                                ]);
                                                            ?>"/>
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