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
            <!-- Main Content Area -->
            <div class="col-md-12">
                <!-- Heading -->
                <div class="row">
                    <div class="col-xs-12 col-md-12">
                        <h1 class="m0 p0 csB7">
                            Time Sheet
                        </h1>
                    </div>
                </div>
                <!--  -->
                <p class="csF14 csB4 pa10"> <?=formatDateToDB($fromDate, DB_DATE, DATE);?> - <?=formatDateToDB($toDate, DB_DATE, DATE);?></p>
                <div class="csPageBox">
                    <div class="csPageBody">
                        <div class="row">
                            <div class="col-sm-12">
                                <h2 class="csF20 csB7 pl10">Advance Filter</h2>
                            </div>
                        </div>
                        <form action="<?=current_url();?>" method="GET">
                            <div class="row">
                                <div class="col-sm-12 col-md-12">
                                    <div class="p10">
                                        <label class="label csF16 csB7 pl0">Employee</label>
                                        <select id="jsSpecificEmployees" name="id" class="form-control">
                                            <?php if(!empty($employees)): ?>
                                                <?php foreach($employees as $emp): ?>
                                                    <option <?=$emp['sid'] === $filterEmployeeId ? 'selected' : '';?> value="<?=$emp['sid'];?>"><?=$emp['name'];?><?=$emp['role'];?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-4">
                                    <div class="p10">
                                        <label class="label csF16 csB7 pl0">From</label>
                                        <input type="text" class="form-control jsDatePicker" name="from" readonly required value="<?=formatDateToDB($fromDate, DB_DATE, 'm/d/Y');?>" />
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <div class="p10">
                                        <label class="label csF16 csB7 pl0">To</label>
                                        <input type="text" class="form-control jsDatePicker" name="to" readonly required  value="<?=formatDateToDB($toDate, DB_DATE, 'm/d/Y');?>"/>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4 text-right">
                                    <div class="p10">
                                        <label class="label csF16 csB7">&nbsp;</label> <br>
                                        <button class="btn btn-orange" type="submit"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;Search</button>
                                        <a href="<?=base_url('attendance/time-sheet');?>" class="btn btn-black" type="clear"><i class="fa fa-times" aria-hidden="true"></i>&nbsp;Clear Filter</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
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
                                                <td><strong>Shift Time</strong></td>
                                                <td><?=$currentEmployee['shift_hours'];?> H</td>
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
                            <div class="col-sm-12">
                                <h2 class="csF20 csB7 pl10">Time Sheet</h2>
                            </div>
                        </div>
                        <!--  -->
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <caption></caption>
                                        <thead>
                                            <tr>
                                                <th scope="col">Date</th>
                                                <th scope="col">Worked Time (HH:MM)</th>
                                                <th scope="col">Break Time (HH:MM)</th>
                                                <th scope="col">Total Time (HH:MM)</th>
                                                <th scope="col">Over Time (HH:MM)</th>
                                                <th scope="col">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(!empty($lists['lists'])): ?>
                                                <?php 
                                                    $sum_worked_minutes = 0;
                                                    $sum_break_minutes = 0;
                                                    $sum_overtime_minutes = 0;
                                                    $sum_minutes = 0;
                                                ?>
                                                <?php foreach($lists['lists'] as $key => $list): ?>
                                                    <?php 
                                                        //
                                                        $total = GetHMSFromSeconds($list['total_minutes']);
                                                        $todayWorked = GetHMSFromSeconds($list['total_worked_minutes']);
                                                        $todayBreak = GetHMSFromSeconds($list['total_break_minutes']);
                                                        $overtime = GetHMSFromSeconds($list['total_overtime_minutes']);
                                                        //
                                                        $sum_minutes = $sum_minutes + $list['total_minutes'];
                                                        $sum_worked_minutes = $sum_worked_minutes + $list['total_worked_minutes'];
                                                        $sum_break_minutes = $sum_break_minutes + $list['total_break_minutes'];
                                                        $sum_overtime_minutes = $sum_overtime_minutes + $list['total_overtime_minutes'];
                                                        //
                                                    ?>
                                                    <tr class="jsAttendanceMyList">
                                                        <td class="vam">
                                                            <?=reset_datetime([
                                                                'datetime' => $key,
                                                                'from_format' => DB_DATE,
                                                                'format' => DATE,
                                                                'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                                                                '_this' => $this
                                                            ]);?>
                                                        </td>
                                                        <td class="vam text-center">
                                                            <strong class="text-success"><?=$todayWorked['hours'];?>:<?=$todayWorked['minutes'];?></strong>
                                                        </td>
                                                        <td class="vam text-center">
                                                            <strong class="csFC3"><?=$todayBreak['hours'];?>:<?=$todayBreak['minutes'];?></strong>
                                                        </td>
                                                        <td class="vam text-center">
                                                            <strong><?=$total['hours'];?>:<?=$total['minutes'];?></strong>
                                                        </td>
                                                        <td class="vam text-center">
                                                            <strong class="text-danger"><?=$overtime['hours'];?>:<?=$overtime['minutes'];?></strong>
                                                        </td>
                                                        <td class="vam text-center">
                                                            <a href="<?=base_url('attendance/manage/'.$list['pId']);?>" class="btn btn-orange">
                                                                <i class="fa fa-cogs" aria-hidden="true"></i>&nbsp;Manage
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                <?php 
                                                    $sumTotal = GetHMSFromSeconds($sum_minutes);
                                                    $sumTodayWorked = GetHMSFromSeconds($sum_worked_minutes);
                                                    $sumTodayBreak = GetHMSFromSeconds($sum_break_minutes);
                                                    $sumTodayOvertime = GetHMSFromSeconds($sum_overtime_minutes);
                                                ?>
                                                <tr>
                                                    <td class="vam">
                                                        <strong>Total</strong>
                                                    </td>
                                                    <td class="vam text-center">
                                                        <strong class="text-success"><?=$sumTodayWorked['hours'];?>:<?=$sumTodayWorked['minutes'];?></strong>
                                                    </td>
                                                    <td class="vam text-center">
                                                        <strong class="csFC3"><?=$sumTodayBreak['hours'];?>:<?=$sumTodayBreak['minutes'];?></strong>
                                                    </td>
                                                    <td class="vam text-center">
                                                        <strong><?=$sumTotal['hours'];?>:<?=$sumTotal['minutes'];?></strong>
                                                    </td>
                                                    <td class="vam text-center">
                                                        <strong class="text-danger"><?=$sumTodayOvertime['hours'];?>:<?=$sumTodayOvertime['minutes'];?></strong>
                                                    </td>
                                                </tr>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="5">
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