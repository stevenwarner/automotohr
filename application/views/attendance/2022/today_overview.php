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
                    <div class="col-xs-12 col-md-8">
                        <h1 class="m0 p0 csB7">
                            Today Overview
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
                <p class="csF14 csB4 pa10"><?php echo $today_date; ?></p>
                <!--  -->
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
                                        <label class="label csF16 csB7 pl0">Select Employee(s)</label>
                                        <select name="id[]" id="jsSpecificEmployees" class="form-control" multiple="multiple">
                                            <?php if(!empty($employees)): ?>
                                                <?php foreach($employees as $emp): ?>
                                                    <option <?=in_array($emp['sid'], $filterEmployeeId) ? 'selected="true"' : '';?> value="<?=$emp['sid'];?>"><?=$emp['name'];?><?=$emp['role'];?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-4 text-right">
                                </div>
                                <div class="col-sm-12 col-md-4 text-right">
                                </div>
                                <div class="col-sm-12 col-md-4 text-right">
                                    <div class="p10">
                                        <label class="label csF16 csB7">&nbsp;</label> <br>
                                        <button class="btn btn-orange" type="submit"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;Search</button>
                                        <a href="<?=base_url('attendance/today_overview');?>" class="btn btn-black" type="clear"><i class="fa fa-times" aria-hidden="true"></i>&nbsp;Clear Filter</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!--  -->
                <div class="row">
                    <div class="col-xs-12 col-md-8">
                        <h2 class="m0 p0 csB7">
                            Clock In Employees
                        </h2>
                        <p class="m0 p0 csF16">Total : <?php echo count($active_employees)." employee(s)"; ?></p>
                    </div>
                </div>    
                <div class="row">
                    <div class="col-sm-12 col-md-12 text-center">
                        <div class="csPageBox csRadius5">
                            <div class="csPageBoxBody">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th scope="col">Employee</th>
                                                <th scope="col">Last Status</th>
                                                <th scope="col">Worked Time (HH:MM)</th>
                                                <th scope="col">Break Time (HH:MM)</th>
                                                <th scope="col">Total Time (HH:MM)</th>
                                                <th scope="col">Over Time (HH:MM)</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(!empty($active_employees)): ?>
                                                <?php foreach($active_employees as $key => $employee): ?>
                                                    <tr class="jsAttendanceMyList">
                                                        <?php
                                                            $employee_info = $employees[$employee["employee_sid"]];
                                                            //
                                                            $total = GetHMSFromSeconds($employee['total_minutes']);
                                                            $todayWorked = GetHMSFromSeconds($employee['total_worked_minutes']);
                                                            $todayBreak = GetHMSFromSeconds($employee['total_break_minutes']);
                                                            $overtime = GetHMSFromSeconds($employee['total_overtime_minutes']);
                                                        ?>
                                                        <td class="vam">
                                                            <div class="employee-profile-info">
                                                                <figure>
                                                                    <img src="<?=$employee_info["image"];?>" alt=""/>
                                                                </figure>
                                                                <strong><?php echo $employee_info["name"].$employee_info["role"];?></strong>
                                                            </div>
                                                        </td>
                                                        <td class="vam text-center">
                                                            <strong><?php echo GetAttendanceActionText($employee["last_action"]); ?></strong>
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
                                                            <a href="<?=base_url('attendance/manage/'.$employee['sid']);?>" class="btn btn-orange">
                                                                <i class="fa fa-cogs" aria-hidden="true"></i>&nbsp;Manage
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="7">
                                                        <p class="alert alert-info text-center">
                                                            No One Active Today.
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
                <!--  -->
                  <!--  -->
                <div class="row">
                    <div class="col-xs-12 col-md-8">
                        <h2 class="m0 p0 csB7">
                            Absent Employees
                        </h2>
                        <p class="m0 p0 csF16">Total : <?php echo count($inactive_employees)." employee(s)"; ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-12 text-center">
                        <div class="csPageBox csRadius5">
                            <div class="csPageBoxBody">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th scope="col">Employee</th>
                                                <th scope="col">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(!empty($inactive_employees)): ?>
                                                <?php foreach($inactive_employees as $key => $employee_sid): ?> 
                                                    <?php $employee_info = $employees[$employee_sid];  ?>
                                                    <tr class="jsAttendanceMyList">
                                                        <td class="vam">
                                                            
                                                            <div class="employee-profile-info">
                                                                <figure>
                                                                    <img src="<?=$employee_info["image"];?>" alt=""/>
                                                                </figure>
                                                                <strong class="timeSheet_Emp_Name"><?php echo $employee_info["name"]." ".$employee_info["role"];?></strong>
                                                            </div>
                                                        </td>
                                                        <td class="vam text-center">
                                                            <strong><?php echo "Absent"; ?></strong>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="2">
                                                        <p class="alert alert-info text-center">
                                                            No One Absent Today.
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
                <!--  -->
            </div>
        </div>
    </div>
</div>