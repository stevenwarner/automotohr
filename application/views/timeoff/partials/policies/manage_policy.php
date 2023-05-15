<div class="row">
    <div class="col-xs-6">
        <p><strong>Number Of Employees:</strong> <span><?php echo count($company_employees); ?></span></p>
    </div>
</div>
<div class="row">    
    <div class="col-sm-10">
    </div> 
    <div class="col-sm-2">
        <button class="btn btn-success btn-block jsTransferPolicyBTN">Save</button>
    </div>    
</div>
<div class="row">  
    <div class="col-sm-12">
        <label>policy</label>
        <div>
            <select name="NewPolicy" id="jsAssigPolicyToAll">
                <?php foreach ($company_policies as $key => $policy) { ?>
                    <option value="<?php echo $policy['sid']; ?>"><?php echo $policy['title']; ?></option>
                <?php } ?>    
            </select>
        </div>
    </div>     
</div>
</br>
</br>
<div class="tabel-responsive">
    <table class="table table-striped csCustomTableHeader">
        <thead>
            <tr>
                <th>Employee</th>
                <th>Request(s)</th>
                <th>Policy</th>
            </tr>
        </thead>
        <tbody id="jsManagePolicyTable">
            <?php if (!empty($company_employees)) { ?>
                <?php foreach ($company_employees as $key => $employee) { ?>
                    <tr class="jsEmployeeRow" data-sid="<?php echo $employee['sid']; ?>">
                        <td><?php echo remakeEmployeeName($employee); ?></td>
                        <td><?php echo count($employee['timeoffs']) > 1 ? count($employee['timeoffs'])." Requests" : "1 Request"; ?></td>
                        <td>
                            <select name="policies[]" class="jsAssignNewPolicy invoice-fields">
                                <?php foreach ($company_policies as $key => $policy) { ?>
                                    <option value="<?php echo $policy['sid']; ?>"><?php echo $policy['title']; ?></option>
                                <?php } ?>    
                            </select>
                        </td>
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
<div class="row">    
    <div class="col-sm-10">
    </div> 
    <div class="col-sm-2">
        <button class="btn btn-success btn-block jsTransferPolicyBTN">Save</button>
    </div>    
</div>