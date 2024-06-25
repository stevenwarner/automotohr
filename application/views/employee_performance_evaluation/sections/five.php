<br />
<div class="container">
    <form action="" id="jsSectionFiveForm">

        <?php if ($section_5_employee_type == "manager") { ?>
            <?php if ($section_5_status == "uncompleted") { ?>
                <div class="panel panel-default">
                    <div class="panel-footer text-right">
                        <button class="btn btn-orange jsSaveSectionFive">Save</button>
                    </div>
                </div>
            <?php } ?>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="m0">
                        <strong>
                            Section 5: Salary Recommendation:
                        </strong>
                        For Manager Use Only:
                    </h3>
                </div>
                <div class="panel-body">
                    <!-- Question Start -->
                    <label class="col-sm-12">
                        <br>
                        <span class="text-large">
                            Employees Current Pay Rate:
                        </span>
                        <input type="number" name="current_pay" class="invoice-fields" value="<?php echo $section_5['current_pay'] ?? '' ?>">
                    </label>
                    <!-- Question End -->
                    <!-- Question Start -->
                    <label class="col-sm-12">
                        <br>
                        <span class="text-large">
                            Recommended Pay Increase:
                        </span>
                        <input type="number" name="recommended_pay" class="invoice-fields" value="<?php echo $section_5['recommended_pay'] ?? '' ?>">
                    </label>
                    <!-- Question End -->
                </div>
            </div>

            <input type="hidden" name="user_type" value="manager">

            <?php if ($section_5_status == "uncompleted") { ?>
                <div class="panel panel-default">
                    <div class="panel-footer text-right">
                        <button class="btn btn-orange jsSaveSectionFive">Save</button>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>

        <?php if ($section_5_employee_type == "HR_manager") { ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="m0">
                        <strong>
                            Section 5: Salary Recommendation:
                        </strong>
                        For Manager Use Only:
                    </h3>
                </div>
                <div class="panel-body">
                    <!-- Question Start -->
                    <label class="col-sm-12">
                        <br>
                        <span class="text-large">
                            Employees Current Pay Rate:
                        </span>
                        <input type="number" readonly name="current_pay" class="invoice-fields" value="<?php echo $current_pay ?? '' ?>">
                    </label>
                    <!-- Question End -->
                    <!-- Question Start -->
                    <label class="col-sm-12">
                        <br>
                        <span class="text-large">
                            Recommended Pay Increase:
                        </span>
                        <input type="number" readonly name="recommended_pay" class="invoice-fields" value="<?php echo $recommended_pay ?? '' ?>">
                    </label>
                    <!-- Question End -->
                    <!-- Question Start -->
                    <label class="col-sm-12">
                        <br>
                        <span class="text-large">
                            Approved Amount:
                        </span>
                        <input type="number" name="approved_amount" class="invoice-fields" value="<?php echo $approved_amount ?? '' ?>">
                    </label>
                    <!-- Question End -->
                    <!-- Question Start -->
                    <label class="col-sm-12">
                        <br>
                        <span class="text-large">
                            Effective Date of Increase:
                        </span>
                        <input type="text" readonly name="effective_increase_date" class="form-control input-bg jsDatePicker" value="<?php echo $effective_increase_date ?? '' ?>" />
                    </label>
                    <!-- Question End -->
                    <!-- Question Start -->
                    <label class="col-sm-12">
                        <br>
                        <span class="text-large">
                            Authorized signature: <span class="cs-required">*</span>
                        </span>
                        <?php if (!$signature) { ?>
                            <p>
                                <a class="btn blue-button btn-sm jsGetEmployeeSignature" href="javascript:;">Create E-Signature</a>
                            <div class="img-full">
                                <img style="max-height: <?= SIGNATURE_MAX_HEIGHT ?>;" src="" id="jsDrawEmployeeSignature" />
                            </div>
                            </p>
                        <?php } else { ?>
                            <p>
                            <div class="img-full">
                                <img style="max-height: <?= SIGNATURE_MAX_HEIGHT ?>;" src="<?= $signature ?>" />
                            </div>
                            </p>
                        <?php } ?>
                    </label>
                    <!-- Question End -->
                    <hr />

                    <!--  -->
                    <div class="row">
                        <div class="col-sm-12">
                            <p><strong>Authorization</strong> (enter your company name in the blank space below)</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <p>This authorizes</p>
                        </div>
                        <div class="col-sm-8">
                            <input disabled="true" type="text" class="form-control" value="<?= isset($companyName) ? $companyName : ''; ?>" />
                        </div>
                        <div class="col-sm-2">
                            <p>(the “Company”)</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <p class="text-justify">to send credit entries (and appropriate debit and adjustment entries), electronically or by any other commercially accepted method, to my (our) account(s) indicated below and to other accounts I (we) identify in the future (the “Account”). This authorizes the financial institution holding the Account to post all such entries. I agree that the ACH transactions authorized herein shall comply with all applicable U.S. Law. This authorization will be in effect until the Company receives a written termination notice from myself and has a reasonable opportunity to act on it.</p>
                        </div>
                    </div>

                    <hr />
                    <!--  -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="btn-wrp full-width mrg-top-20 text-center">
                                <button class="btn blue-button btn-lg not_sign_yet jsSaveHRManagerApproval">I CONSENT AND ACCEPT</button>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="user_type" value="HR_manager">
            </div>
        <?php } ?>
    </form>
</div>