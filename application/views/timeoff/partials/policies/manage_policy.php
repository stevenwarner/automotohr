<?php
//
$policyOptions = '';
//
foreach ($policies as $key => $policy) {
    $policyOptions .= '<option value="' . $policy['sid'] . '" ' . ($policy['sid'] == $selectedPolicyId ? 'selected' : '') . '>' . $policy['title'] . ' (' . ($policy['policy_category_type'] ? 'PAID' : 'UNPAID') . ') - ' . ($policy['is_archived'] ? 'INACTIVE' : 'ACTIVE') . '</option>';
}

$trs = '';
//
if (!empty($policyRequests)) {
    foreach ($policyRequests as $key => $employee) {
        //
        $trs .= '<tr class="jsEmployeeRow" data-sid="' . $employee['userId'] . '">';
        $trs .=     '<td>' . (remakeEmployeeName($employee)) . '</td>';
        $trs .=     '<td><select name="policies[]" class="jsAssignNewPolicy invoice-fields">' . ($policyOptions) . '</select></td>';
        $trs .= '</tr>';
    }
}
?>

<div class="row">
    <div class="col-sm-9">
    </div>
    <div class="col-sm-3" style="text-align: right;">
        <button class="btn btn-success jsTransferPolicyBTN">Change Policy For Selected Employees</button>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <label>Policy</label>
        <p class="text-danger"><em>Apply this policy to all the following requests</em></p>
        <div>
            <select name="NewPolicy" id="jsAssigPolicyToAll">
                <?php foreach ($policies as $key => $policy) { ?>
                    <option value="<?php echo $policy['sid']; ?>"><?php echo $policy['title']; ?> (<?= $policy['policy_category_type'] ? 'PAID' : 'UNPAID'; ?>)- <?=$policy['is_archived'] ? 'INACTIVE' : 'ACTIVE'; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
</div>
</br>
</br>
<div class="table-responsive">
    <table class="table table-striped csCustomTableHeader">
        <thead>
            <tr>
                <th>Employee</th>
                <th>Policy</th>
            </tr>
        </thead>
        <tbody id="jsManagePolicyTable">
            <?= $trs; ?>
        </tbody>
    </table>
</div>
<div class="row">
    <div class="col-sm-9">
    </div>
    <div class="col-sm-3" style="text-align: right;">
        <button class="btn btn-success jsTransferPolicyBTN">Change Policy For Selected Employees</button>
    </div>
</div>