<div>
    <div class="container">
        <div class="row">
            <!-- Main Area -->
            <div class="col-sm-12 col-md-12">
                <div class="_csMt30 _csMb30">
                    <!--  -->
                    <div class="row">
                        <div class="col-sm-12 col-md-8">
                            <h1 class="_csF18 _csMt0">Employee Changes History</h1>
                        </div>
                        <div class="col-sm-12 col-md-4 text-right">
                            <a href="<?= base_url('dashboard'); ?>" class="btn _csF14 _csB1 _csF2 _csR5">
                                <i class="fa fa-long-arrow-left _csF14" aria-hidden="true"></i>&nbsp;Dashboard
                            </a>
                        </div>
                        <hr />
                    </div>
                    <!-- Maim -->
                    <div class="panel panel-default">
                        <div class="panel-heading _csB4">
                            <strong>Employee change report</strong>
                        </div>
                        <div class="panel-body">
                            <form method="get">
                                <div class="row">
                                    <div class="col-sm-12 col-md-6">
                                        <label>Select Employees</label>
                                        <select name="employeeIds[]" id="employeeIds" multiple="multiple">
                                            <?php foreach ($employeesList as $emp) : ?>
                                                <option value="<?= $emp['userId']; ?>" <?=in_array($emp['userId'], $employeeIds) ? 'selected':'';?>><?= remakeEmployeeName($emp); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-12 col-md-3">
                                        <label>Start Date</label>
                                        <input type="text" name="startDate" class="form-control jsStartDatePicker" readonly value="<?=$startDate?>" />
                                    </div>
                                    <div class="col-sm-12 col-md-3">
                                        <label>EndDate</label>
                                        <input type="text" name="endDate" class="form-control jsEndDatePicker" readonly value="<?=$endDate?>" />
                                    </div>
                                </div>
                                <br>
                                <!--  -->
                                <div class="row">
                                    <div class="col-sm-12 text-right">
                                        <button type="submit" class="btn _csB3 _csF2 _csR5">Apply</button>
                                        <a href="<?=base_url('employee/information/report');?>" class="btn _csB1 _csF2 _csR5">Reset</a>
                                    </div>
                                </div>
                            </form>
                            <br />
                            <!--  -->
                            <div class="row">

                                <div class="col-sm-12 text">
                                    <h4 class="_csMb0"><strong>Total:</strong> <?=count($records);?> employees</h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <caption></caption>
                                            <thead>
                                                <tr>
                                                    <th class="_csB1 _csF2" scope="col">Employee</th>
                                                    <th class="_csB1 _csF2" scope="col">Last Changed<br> Date & Time</th>
                                                    <th class="_csB1 _csF2" scope="col">What <br> changed?</th>
                                                    <th class="_csB1 _csF2 text-right" scope="col">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    if (!empty($records)) {
                                                        foreach ($records as $record) {
                                                            ?>
                                                        <tr>
                                                            <td class="_csVm">
                                                                <strong><?=$record['full_name'];?></strong>
                                                            </td>
                                                            <td class="_csVm">
                                                                <?=formatDateToDB($record['last_changed'], DB_DATE_WITH_TIME, DATE_WITH_TIME);?>
                                                            </td>
                                                            <td class="_csVm">
                                                                <dl>
                                                                    <?php foreach ($record['what_changed'] as $hist => $v) {
                                                                        ?>
                                                                        <dd>- <?=ucwords($hist);?></dd>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </dl>
                                                            </td>
                                                            <td class="text-right _csVm">
                                                                <button class="btn _csB3 _csF2 _csR5 _csF14 jsProfileHistory" data-id="<?=$record['sid'];?>" data-name="<?=$record['full_name'];?>">
                                                                    <i class="fa fa-eye _csF14" aria-hidden="true"></i>&nbsp;
                                                                    View
                                                                </button>
                                                                <?php if (
                                                                    $employee['access_level_plus'] == 1 ||
                                                                    $employee['pay_plan_flag'] == 1
                                                                ) :?>
                                                                <a href="<?=base_url("employee_profile/".($record['sid']));?>" class="btn btn _csB4 _csF2 _csR5 _csF14 ">
                                                                    <i class="fa fa-eye _csF14" aria-hidden="true"></i>&nbsp;
                                                                    View Profile
                                                                </a>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                            <?php
                                                        }
                                                    } else {
                                                        ?>
                                                        <tr>
                                                            <th scope="col" colspan="4">
                                                                <p class="alert alert-info text-center">No records found.</p>
                                                            </th>
                                                        </tr>
                                                        <?php
                                                    } ?>
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
</div>