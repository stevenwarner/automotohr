<br />
<br />
<div class="container">
    <div class="panel panel-default">
        <div class="panel-body">
            <!--  -->
            <form action="">
                <!--  -->
                <div class="form-group">
                    <label class="csF16">
                        Name <strong class="text-danger">*</strong>
                    </label>
                    <p class="csF12 text-danger">
                        <strong>
                            <em>
                                The description of the company benefit.For example, a company may offer multiple benefits with for Medical Insurance. The description would show something more specific like “Kaiser Permanente” or “Blue Cross/ Blue Shield”.
                            </em>
                        </strong>
                    </p>
                    <input type="text" class="form-control jsName" placeholder="Blue Cross/ Blue Shield" value="<?= $benefit['description']; ?>" />
                </div>
                <!--  -->
                <div class="form-group">
                    <label class="csF16">
                        Benefit type <strong class="text-danger">*</strong>
                    </label>
                    <select class="form-control jsType" disabled>
                        <?php foreach ($storeBenefits as $key => $value) { ?>
                            <optgroup label="<?= $key; ?>">
                                <?php foreach ($value as $v0) { ?>
                                    <option value="<?= $v0['sid']; ?>" <?= $v0['benefit_type'] == $benefit['benefit_type'] ? 'selected' : ''; ?>><?= $v0['name']; ?></option>
                                <?php } ?>
                            </optgroup>
                        <?php } ?>
                    </select>
                </div>
                <!--  -->
                <div class="form-group">
                    <label class="csF16">
                        Employer taxes
                    </label>
                    <p class="csF12 text-danger">
                        <strong>
                            <em>
                                Whether the employer is subject to pay employer taxes when an employee is on leave. Only applicable to third party sick pay benefits.
                            </em>
                        </strong>
                    </p>
                    <select class="form-control jsEmployerTaxes" disabled>
                        <option <?= !$benefit['responsible_for_employer_taxes'] ? 'selected' : ''; ?> value="no">No</option>
                        <option <?= $benefit['responsible_for_employer_taxes'] ? 'selected' : ''; ?> value="yes">Yes</option>
                    </select>
                </div>
                <!--  -->
                <div class="form-group">
                    <label class="csF16">
                        Employee w2
                    </label>
                    <p class="csF12 text-danger">
                        <strong>
                            <em>
                                Whether the employer is subject to file W-2 forms for an employee on leave. Only applicable to third party sick pay benefits.
                            </em>
                        </strong>
                    </p>
                    <select class="form-control jsEmployeeW2" disabled>
                        <option <?= !$benefit['responsible_for_employee_w2'] ? 'selected' : ''; ?> value="no">No</option>
                        <option <?= $benefit['responsible_for_employee_w2'] ? 'selected' : ''; ?> value="yes">Yes</option>
                    </select>
                </div>
                <!--  -->
                <div class="form-group">
                    <label class="csF16">
                        Active
                    </label>
                    <p class="csF12 text-danger">
                        <strong>
                            <em>
                                Whether this benefit is active for employee participation.
                            </em>
                        </strong>
                    </p>
                    <select class="form-control jsActive">
                        <option <?= $benefit['active'] ? 'selected' : ''; ?> value="yes">Yes</option>
                        <option <?= !$benefit['active'] ? 'selected' : ''; ?> value="no">No</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="panel-footer text-right">
            <input type="hidden" class="jsKey" value="<?= $benefit['sid']; ?>" />
            <button class="btn csW csBG4 csF16 jsModalCancel">
                <i class="fa fa-times-circle csF16" aria-hidden="true"></i>
                &nbsp;Cancel
            </button>
            <button class="btn csW csBG3 csF16 jsUpdateBenefit">
                <i class="fa fa-save csF16" aria-hidden="true"></i>
                &nbsp;Save
            </button>
        </div>
    </div>
</div>