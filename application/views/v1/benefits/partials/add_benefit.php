<br>
<br>
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
                    <input type="text" class="form-control jsName" placeholder="Blue Cross/ Blue Shield" />
                </div>

                <!--  -->
                <div class="form-group">
                    <label class="csF16">
                        Benefit type <strong class="text-danger">*</strong>
                    </label>
                    <select class="form-control jsType">
                        <?php foreach ($storeBenefits as $key => $value) { ?>
                            <optgroup label="<?= $key; ?>">
                                <?php foreach ($value as $v0) { ?>
                                    <option value="<?= $v0['sid']; ?>"><?= $v0['name']; ?></option>
                                <?php } ?>
                            </optgroup>
                        <?php } ?>
                    </select>
                </div>

                <!--  -->
                <div class="form-group hidden">
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
                    <select class="form-control jsEmployerTaxes">
                        <option value="no">No</option>
                        <option value="yes">Yes</option>
                    </select>
                </div>

                <!--  -->
                <div class="form-group hidden">
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
                    <select class="form-control jsEmployeeW2">
                        <option value="no">No</option>
                        <option value="yes">Yes</option>
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
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="panel-footer text-right">
            <button class="btn btn-black jsModalCancel">
                <i class="fa fa-times-circle" aria-hidden="true"></i>
                &nbsp;Cancel
            </button>
            <button class="btn btn-orange jsSaveBenefit">
                <i class="fa fa-save" aria-hidden="true"></i>
                &nbsp;Save
            </button>
        </div>
    </div>
</div>