<div class="container">
    <form action="javascript:void(0)" id="jsPagePayScheduleForm">
        <!--  -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="text-medium panel-heading-text">
                    <i class="fa fa-edit text-orange" aria-hidden="true"></i>
                    Manage Employee Earnings
                </h2>
            </div>
            <div class="panel-body">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th><input type="checkbox" class="jsCheckAll" /></th>
                            <th>Name</th>
                            <th>Wage Type</th>
                            <th>Rate Type</th>
                            <th>Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($earnings) { ?>
                            <?php foreach ($earnings as  $value) { ?>
                                <tr class="<?php echo $value['sid'] != 0 ? 'jsStoreCourseRow' : ''; ?>">
                                    <td><input type="checkbox" name="earningIds[]" <?php echo $value['is_assign'] == 1 ? "checked" : ""; ?> class="jsAssignEarning" value="<?php echo $value['sid']; ?>" /></td>
                                    <td class="jsEarningTitle"><?php echo $value['title']; ?></td>
                                    <td class="jsWageType"><?php echo $value['wage_type']; ?></td>
                                    <td class="jsRateType"><?php echo $value['rate_type']; ?></td>
                                    <td class="jsRate">
                                        <input type="number" class="form-control" placeholder="0.0" name="rate" value="<?php echo $value['rate']; ?>">
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="5" class="text-center"><strong>No Earnings Found</strong></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>    
            </div>
            <div class="panel-footer text-center">
                <button class="btn btn-orange jsPageEarningTypeBtn">
                    <i class="fa fa-edit" aria-hidden="true"></i>
                    Update
                </button>
                <button class="btn btn-black jsModalCancel">
                    <i class="fa fa-times-circle" aria-hidden="true"></i>
                    Cancel
                </button>
            </div>
        </div>
    </form>
 </div>