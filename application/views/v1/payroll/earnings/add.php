<div class="container">
    <br />
    <div class="row">
        <div class="col-sm-12">
            <p class="csF16 text-danger">
                <strong>
                    <em>
                        Note: The fields marked with "*" are mandatory.
                    </em>
                </strong>
            </p>
        </div>
    </div>
    <br>
    <form action="" id="jsEarningForm">
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                Name
                &nbsp;<strong class="text-danger">*</strong>
            </label>
            <p class="csF16 text-danger">
                <strong>
                    <em>
                        The name of the custom earning type.
                    </em>
                </strong>
            </p>
            <input type="text" required class="form-control jsEarningName" id="jsEarningName" placeholder="Bonus" name="name" />
        </div>
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                Rate Type
                &nbsp;<strong class="text-danger">*</strong>
            </label>
            <select name="rate_type" class="form-control">
                <option value="Flat Rate">Flat Rate</option>
                <option value="Hourly Rate">Hourly Rate</option>
            </select>
        </div>
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                Rate
                &nbsp;<strong class="text-danger">*</strong>
            </label>
            <input type="number" class="form-control" placeholder="0.0" name="rate" />
        </div>
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                Wage Type
                &nbsp;<strong class="text-danger">*</strong>
            </label>
            <select name="wage_type" class="form-control">
                <option value="Supplemental Wages">Supplemental Wages</option>
                <option value="Regular Wages">Regular Wages</option>
                <option value="Imputed Income">Imputed Income</option>
            </select>
        </div>
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                Count toward minimum wage?
            </label>
            <select name="<?= stringToSlug("Count toward minimum wage", "_"); ?>" class="form-control">
                <option value="0"></option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
        </div>
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                Non-monetary Income
            </label>
            <select name="<?= stringToSlug("Non monetary Income", "_"); ?>" class="form-control">
                <option value="0"></option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
        </div>
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                Process as OT
            </label>
            <select name="<?= stringToSlug("Process as OT", "_"); ?>" class="form-control">
                <option value="0"></option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
        </div>
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                Report as a fringe benefit
            </label>
            <select name="<?= stringToSlug("Report as a fringe benefit", "_"); ?>" class="form-control">
                <option value="0"></option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
        </div>
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                From W-2, Box 14
            </label>
            <select name="<?= stringToSlug("From W-2, Box 14", "_"); ?>" class="form-control">
                <option value="0"></option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
        </div>
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                Update balances?
            </label>
            <select name="<?= stringToSlug("Update balances?", "_"); ?>" class="form-control">
                <option value="0"></option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
        </div>
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                Leave plan
            </label>
            <select name="<?= stringToSlug("Leave plan", "_"); ?>" class="form-control">
                <option value="0"></option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
        </div>
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                Federal Loan Assessment
            </label>
            <select name="<?= stringToSlug("Federal Loan Assessment", "_"); ?>" class="form-control">
                <option value="0"></option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
        </div>
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                Federal Income Tax
            </label>
            <select name="<?= stringToSlug("Federal Income Tax", "_"); ?>" class="form-control">
                <option value="0"></option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
        </div>
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                Federal Income Tax Additional
            </label>
            <select name="<?= stringToSlug("Federal Income Tax Additional", "_"); ?>" class="form-control">
                <option value="0"></option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
        </div>
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                Federal Income Tax Fixed Rate
            </label>
            <select name="<?= stringToSlug("Federal Income Tax Fixed Rate", "_"); ?>" class="form-control">
                <option value="0"></option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
        </div>
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                Social Security Company
            </label>
            <select name="<?= stringToSlug("Social Security Company", "_"); ?>" class="form-control">
                <option value="0"></option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
        </div>
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                Social Security Employee
            </label>
            <select name="<?= stringToSlug("Social Security Employee", "_"); ?>" class="form-control">
                <option value="0"></option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
        </div>
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                Medicare Company
            </label>
            <select name="<?= stringToSlug("Medicare Company", "_"); ?>" class="form-control">
                <option value="0"></option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
        </div>
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                Medicare Employee
            </label>
            <select name="<?= stringToSlug("Medicare Employee", "_"); ?>" class="form-control">
                <option value="0"></option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
        </div>
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                Federal Unemployment Insurance
            </label>
            <select name="<?= stringToSlug("Federal Unemployment Insurance", "_"); ?>" class="form-control">
                <option value="0"></option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
        </div>
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                MN Income Tax
            </label>
            <select name="<?= stringToSlug("MN Income Tax", "_"); ?>" class="form-control">
                <option value="0"></option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
        </div>
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                MN Income Tax Additional
            </label>
            <select name="<?= stringToSlug("MN Income Tax Additional", "_"); ?>" class="form-control">
                <option value="0"></option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
        </div>
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                MN Income Tax Fixed Rate
            </label>
            <select name="<?= stringToSlug("MN Income Tax Fixed Rate", "_"); ?>" class="form-control">
                <option value="0"></option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
        </div>
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                MN Unemployment Insurance
            </label>
            <select name="<?= stringToSlug("MN Unemployment Insurance", "_"); ?>" class="form-control">
                <option value="0"></option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
        </div>
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                MN Workforce Dev Assessment
            </label>
            <select name="<?= stringToSlug("MN Workforce Dev Assessment", "_"); ?>" class="form-control">
                <option value="0"></option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
        </div>
        <hr />
        <!--  -->
        <div class="form-group text-right">
            <button class="btn csW csBG3 csRadius5 jsAddBtn">
                <i class="fa fa-save" aria-hidden="true"></i>
                &nbsp;Create
            </button>
        </div>
    </form>
</div>