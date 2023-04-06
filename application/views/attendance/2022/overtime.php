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
                            Overtime Report
                        </h1>
                    </div>
                    <div class="col-xs-12c col-md-4 text-right">
                        <?php if(!empty($lists)): ?>
                        <a href="<?=current_url().GetParams('export=1');?>" class="btn btn-orange">
                            <i class="fa fa-download" aria-hidden="true"></i>&nbsp;Export In CSV
                        </a>
                        <?php else: ?>
                        <a href="javascript:void(0)" class="btn btn-orange" disabled>
                            <i class="fa fa-download" aria-hidden="true"></i>&nbsp;Export In CSV
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <!--  -->
                <p class="csF14 csB4 pa10"><?php echo formatDateToDB($from_date, DB_DATE, DATE); ?> - <?=formatDateToDB($to_date, DB_DATE, DATE);?></p>
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
                                        <select name="id[]" id="jsSpecificEmployees" class="form-control">
                                            <option value="0">[Please Select]</option>
                                            <?php if(!empty($employees)): ?>
                                                <?php foreach($employees as $emp): ?>
                                                    <option <?=in_array($emp['sid'], $selected_employee_ids) ? 'selected="true"' : '';?> value="<?=$emp['sid'];?>"><?=$emp['name'];?><?=$emp['role'];?></option>
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
                                        <input type="text" class="form-control jsDatePicker" name="from" readonly required value="<?=formatDateToDB($from_date, DB_DATE, 'm/d/Y');?>" />
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <div class="p10">
                                        <label class="label csF16 csB7 pl0">To</label>
                                        <input type="text" class="form-control jsDatePicker" name="to" readonly required  value="<?=formatDateToDB($to_date, DB_DATE, 'm/d/Y');?>"/>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4 text-right">
                                    <div class="p10">
                                        <label class="label csF16 csB7">&nbsp;</label> <br>
                                        <button class="btn btn-orange" type="submit"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;Search</button>
                                        <a href="<?=base_url('attendance/overtime');?>" class="btn btn-black" type="clear"><i class="fa fa-times" aria-hidden="true"></i>&nbsp;Clear Filter</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!--  -->
                <div class="row">
                    <div class="col-xs-12 col-md-8">
                        <p class="m0 p0 csF16">Total : <?php echo count($lists)." employee(s)"; ?></p>
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
                                                <th scope="col">Worked Time (HH:MM)</th>
                                                <th scope="col">Break Time (HH:MM)</th>
                                                <th scope="col">Total Time (HH:MM)</th>
                                                <th scope="col">Over Time (HH:MM)</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(!empty($lists)): ?>
                                                <?php foreach($lists as $key => $employee): ?>
                                                    <?php
                                                        $employee_info = $employees[$key];
                                                        //
                                                        $total = GetHMSFromSeconds($employee['total_minutes']);
                                                        $todayWorked = GetHMSFromSeconds($employee['total_worked_minutes']);
                                                        $todayBreak = GetHMSFromSeconds($employee['total_break_minutes']);
                                                        $overtime = GetHMSFromSeconds($employee['total_overtime_minutes']);
                                                    ?>
                                                    <tr class="jsAttendanceMyList">
                                                        <td class="vam">
                                                            <div class="employee-profile-info">
                                                                <figure>
                                                                    <img src="<?=$employee_info["image"];?>" alt=""/>
                                                                </figure>
                                                                <strong><?php echo $employee_info["name"].$employee_info["role"];?></strong>
                                                            </div>
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
                                                        </td>
                                                    </tr>
                                                    <?php if(!empty($employee['lists'])): ?>
                                                        <?php foreach($employee['lists'] as $k1 => $v1): ?>
                                                            <?php 
                                                                //
                                                                $total2 = GetHMSFromSeconds($v1['total_minutes']);
                                                                $todayWorked2 = GetHMSFromSeconds($v1['total_worked_minutes']);
                                                                $todayBreak2 = GetHMSFromSeconds($v1['total_break_minutes']);
                                                                $overtime2 = GetHMSFromSeconds($v1['total_overtime_minutes']);    
                                                            ?>
                                                            <tr>
                                                                <td class="vam text-right">
                                                                    <?=reset_datetime([
                                                                        'datetime' => $k1.' 00:00:00',
                                                                        'from_format' => 'Y-m-d H:i:s',
                                                                        'format' => DATE,
                                                                        'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                                                                        'new_zone' => $this->args['employees'][$key]['timezone'],
                                                                        '_this' => $this
                                                                    ]);?>
                                                                </td>
                                                                <td class="vam text-center">
                                                                    <strong class="text-success"><?=$todayWorked2['hours'];?>:<?=$todayWorked2['minutes'];?></strong>
                                                                </td>
                                                                <td class="vam text-center">
                                                                    <strong class="csFC3"><?=$todayBreak2['hours'];?>:<?=$todayBreak2['minutes'];?></strong>
                                                                </td>
                                                                <td class="vam text-center">
                                                                    <strong><?=$total2['hours'];?>:<?=$total2['minutes'];?></strong>
                                                                </td>
                                                                <td class="vam text-center">
                                                                    <strong class="text-danger"><?=$overtime2['hours'];?>:<?=$overtime2['minutes'];?></strong>
                                                                </td>
                                                                <td class="vam text-center">
                                                                    <a href="<?=base_url('attendance/manage/'.$v1['pId']);?>" class="btn btn-orange">
                                                                        <i class="fa fa-cogs" aria-hidden="true"></i>&nbsp;Manage
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
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
            </div>
        </div>
    </div>
</div>