<style type="text/css">
    .modifyDate{
        style="vertical-align: middle;
        font-size: 16px !important;
        font-weight: 600;
    }
</style>
<div class="row">
    <div class="col-xs-6">
        <p style="font-size: 16px;"><strong>Number Of Employees:</strong> <span><?php echo count($allowed_employees); ?></span></p>
    </div>
</div>
<div class="tabel-responsive">
    <table class="table table-striped csCustomTableHeader">
        <thead>
            <tr>
                <th>Employee</th>
                <th>Allowed Time</th>
                <th>Consumed Time</th>
                <th>Remaining Time</th>
            </tr>
        </thead>
        <tbody id="jsManagePolicyTable">
            <?php if (!empty($allowed_employees)) { ?>
                <?php foreach ($allowed_employees as $employee) { ?>
                    <tr>
                        <td style="vertical-align: middle;">
                            <strong><?php echo remakeEmployeeName($employee); ?></strong>
                            <br>
                            <?php echo $employee['anniversary_text']; ?>
                        </td>
                        <td class="modifyDate"><?php echo $employee['allowed_time']; ?></td>
                        <td class="modifyDate"><?php echo $employee['consumed_time']; ?></td>
                        <td class="modifyDate"><?php echo $employee['remaining_time']; ?></td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="4">
                        <p class="alert alert-info text-center">No employee found against this policy</p>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>    
</div>