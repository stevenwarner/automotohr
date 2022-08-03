<div class="row">
    <div class="col-xs-12">
        <div class="hr-box">
            <div class="hr-box-header">
                <strong>Authorized Management Signers:</strong>
            </div>
            <div class="hr-innerpadding">
                <div class="universal-form-style-v2">
                    <?php foreach ($employeesList as $key => $emp) { ?>
                        <div class="col-xs-6">
                            <label class="control control--checkbox font-normal">
                                <?php echo remakeEmployeeName($emp); ?>
                                <input class="disable_doc_checkbox" name="managersList[]" type="checkbox" value="<?php echo $emp['sid']; ?>" <?php echo in_array($emp['sid'], $pre_assigned_employees) ? 'checked="checked"' : ''; ?>>
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>