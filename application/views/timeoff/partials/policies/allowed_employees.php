<style type="text/css">
    .modifyDate {
        vertical-align: middle !important;
        font-size: 16px !important;
        font-weight: 600;
    }

    .finishTime {
        background: #f2dede !important;
    }
</style>
<?php if (!empty($allowed_employees)) { ?>
    <div class="row">
        <div class="col-xs-12">
            <p class="alert alert-danger" style="font-size: 16px;"><strong><em>This policy is applicable to the following employees.</em></p>
        </div>
    </div>
<?php } ?>
<div class="table-responsive">
    <table class="table table-striped csCustomTableHeader">
        <thead>
            <tr>
                <th>Employee</th>
                <th class="text-right">Allowed Time</th>
                <th class="text-right">Consumed Time</th>
                <th class="text-right">Remaining Time</th>
            </tr>
        </thead>
        <tbody id="jsManagePolicyTable">
            <?php if (!empty($allowed_employees)) { ?>
                <?php foreach ($allowed_employees as $employee) { ?>
                    <tr class="<?php echo $employee['remaining_minutes'] < 1 ? 'finishTime' : ''; ?>">
                        <td style="vertical-align: middle;">
                            <strong><?php echo remakeEmployeeName($employee); ?></strong>
                            <br>
                            <?php echo $employee['anniversary_text']; ?>
                        </td>
                        <td class="modifyDate text-right"><?php echo $employee['allowed_time']; ?></td>
                        <td class="modifyDate text-right"><?php echo $employee['consumed_time']; ?></td>
                        <td class="modifyDate text-right"><?php echo $employee['remaining_time']; ?></td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="4">
                        <p class="alert alert-info text-center">No employees found against this policy.</p>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>