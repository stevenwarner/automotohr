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
    <form action="" id="jsEarningEditForm">
        <!--  -->
        <div class=" form-group">
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
            <input type="text" <?= $earning["is_default"] == 0 ? "" : "readonly"; ?> name="name" class="form-control jsEarningName" id="jsEarningName" placeholder="Bonus" value="<?= $earning['name']; ?>" />
        </div>
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                Rate Type
                &nbsp;<strong class="text-danger">*</strong>
            </label>
            <select name="rate_type" class="form-control">
                <option  <?= $earning["rate_type"] && $earning["rate_type"] == "Flat Rate" ? "selected" : ""; ?> value="Flat Rate">Flat Rate</option>
                <option  <?= $earning["rate_type"] && $earning["rate_type"] == "Hourly Rate" ? "selected" : ""; ?> value="Hourly Rate">Hourly Rate</option>
            </select>
        </div>
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                Rate
                &nbsp;<strong class="text-danger">*</strong>
            </label>
            <input type="number" class="form-control" placeholder="0.0" name="rate" value="<?= $earning["rate"]; ?>" />
        </div>
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                Wage Type
                &nbsp;<strong class="text-danger">*</strong>
            </label>
            <select name="wage_type" class="form-control">
                <option <?= $earning["wage_type"] && $earning["wage_type"] == "Supplemental Wages" ? "selected" : ""; ?> value="Supplemental Wages">Supplemental Wages</option>
                <option <?= $earning["wage_type"] && $earning["wage_type"] == "Regular Wages" ? "selected" : ""; ?> value="Regular Wages">Regular Wages</option>
                <option <?= $earning["wage_type"] && $earning["wage_type"] == "Imputed Income" ? "selected" : ""; ?> value="Imputed Income">Imputed Income</option>
            </select>
        </div>
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                Count toward minimum wage?
            </label>
            <select name="<?= $slug =  stringToSlug("Count toward minimum wage", "_"); ?>" class="form-control">
                <option <?= $earning["count_toward_minimum_wage"] && $earning["count_toward_minimum_wage"] == "0" ? "selected" : ""; ?> value="0"></option>
                <option <?= $earning["count_toward_minimum_wage"] && $earning["count_toward_minimum_wage"] == "Yes" ? "selected" : ""; ?> value="Yes">Yes</option>
                <option <?= $earning["count_toward_minimum_wage"] && $earning["count_toward_minimum_wage"] == "No" ? "selected" : ""; ?> value="No">No</option>
            </select>
        </div>
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                Non-monetary Income
            </label>
            <select name="<?= $slug = stringToSlug("Non monetary Income", "_"); ?>" class="form-control">
                <option <?= $earning[$slug] && $earning[$slug] == "0" ? "selected" : ""; ?> value="0"></option>
                <option <?= $earning[$slug] && $earning[$slug] == "Yes" ? "selected" : ""; ?> value="Yes">Yes</option>
                <option <?= $earning[$slug] && $earning[$slug] == "No" ? "selected" : ""; ?> value="No">No</option>
            </select>
        </div>
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                Process as OT
            </label>
            <select name="<?= $slug =  stringToSlug("Process as OT", "_"); ?>" class="form-control">
                <option <?= $earning[$slug] && $earning[$slug] == "0" ? "selected" : ""; ?> value="0"></option>
                <option <?= $earning[$slug] && $earning[$slug] == "Yes" ? "selected" : ""; ?> value="Yes">Yes</option>
                <option <?= $earning[$slug] && $earning[$slug] == "No" ? "selected" : ""; ?> value="No">No</option>
            </select>
        </div>
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                Report as a fringe benefit
            </label>
            <select name="<?= $slug =  stringToSlug("Report as a fringe benefit", "_"); ?>" class="form-control">
                <option <?= $earning[$slug] && $earning[$slug] == "0" ? "selected" : ""; ?> value="0"></option>
                <option <?= $earning[$slug] && $earning[$slug] == "Yes" ? "selected" : ""; ?> value="Yes">Yes</option>
                <option <?= $earning[$slug] && $earning[$slug] == "No" ? "selected" : ""; ?> value="No">No</option>
            </select>
        </div>
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                From W-2, Box 14
            </label>
            <select name="<?= $slug =  stringToSlug("From W-2, Box 14", "_"); ?>" class="form-control">
                <option <?= $earning[$slug] && $earning[$slug] == "0" ? "selected" : ""; ?> value="0"></option>
                <option <?= $earning[$slug] && $earning[$slug] == "Yes" ? "selected" : ""; ?> value="Yes">Yes</option>
                <option <?= $earning[$slug] && $earning[$slug] == "No" ? "selected" : ""; ?> value="No">No</option>
            </select>
        </div>
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                Update balances?
            </label>
            <select name="<?= $slug =  stringToSlug("Update balances?", "_"); ?>" class="form-control">
                <option <?= $earning[$slug] && $earning[$slug] == "0" ? "selected" : ""; ?> value="0"></option>
                <option <?= $earning[$slug] && $earning[$slug] == "Yes" ? "selected" : ""; ?> value="Yes">Yes</option>
                <option <?= $earning[$slug] && $earning[$slug] == "No" ? "selected" : ""; ?> value="No">No</option>
            </select>
        </div>
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                Leave plan
            </label>
            <select name="<?= $slug =  stringToSlug("Leave plan", "_"); ?>" class="form-control">
                <option <?= $earning[$slug] && $earning[$slug] == "0" ? "selected" : ""; ?> value="0"></option>
                <option <?= $earning[$slug] && $earning[$slug] == "Yes" ? "selected" : ""; ?> value="Yes">Yes</option>
                <option <?= $earning[$slug] && $earning[$slug] == "No" ? "selected" : ""; ?> value="No">No</option>
            </select>
        </div>
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                Federal Loan Assessment
            </label>
            <select name="<?= $slug =  stringToSlug("Federal Loan Assessment", "_"); ?>" class="form-control">
                <option <?= $earning[$slug] && $earning[$slug] == "0" ? "selected" : ""; ?> value="0"></option>
                <option <?= $earning[$slug] && $earning[$slug] == "Yes" ? "selected" : ""; ?> value="Yes">Yes</option>
                <option <?= $earning[$slug] && $earning[$slug] == "No" ? "selected" : ""; ?> value="No">No</option>
            </select>
        </div>
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                Federal Income Tax
            </label>
            <select name="<?= $slug =  stringToSlug("Federal Income Tax", "_"); ?>" class="form-control">
                <option <?= $earning[$slug] && $earning[$slug] == "0" ? "selected" : ""; ?> value="0"></option>
                <option <?= $earning[$slug] && $earning[$slug] == "Yes" ? "selected" : ""; ?> value="Yes">Yes</option>
                <option <?= $earning[$slug] && $earning[$slug] == "No" ? "selected" : ""; ?> value="No">No</option>
            </select>
        </div>
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                Federal Income Tax Additional
            </label>
            <select name="<?= $slug =  stringToSlug("Federal Income Tax Additional", "_"); ?>" class="form-control">
                <option <?= $earning[$slug] && $earning[$slug] == "0" ? "selected" : ""; ?> value="0"></option>
                <option <?= $earning[$slug] && $earning[$slug] == "Yes" ? "selected" : ""; ?> value="Yes">Yes</option>
                <option <?= $earning[$slug] && $earning[$slug] == "No" ? "selected" : ""; ?> value="No">No</option>
            </select>
        </div>
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                Federal Income Tax Fixed Rate
            </label>
            <select name="<?= $slug =  stringToSlug("Federal Income Tax Fixed Rate", "_"); ?>" class="form-control">
                <option <?= $earning[$slug] && $earning[$slug] == "0" ? "selected" : ""; ?> value="0"></option>
                <option <?= $earning[$slug] && $earning[$slug] == "Yes" ? "selected" : ""; ?> value="Yes">Yes</option>
                <option <?= $earning[$slug] && $earning[$slug] == "No" ? "selected" : ""; ?> value="No">No</option>
            </select>
        </div>
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                Social Security Company
            </label>
            <select name="<?= $slug =  stringToSlug("Social Security Company", "_"); ?>" class="form-control">
                <option <?= $earning[$slug] && $earning[$slug] == "0" ? "selected" : ""; ?> value="0"></option>
                <option <?= $earning[$slug] && $earning[$slug] == "Yes" ? "selected" : ""; ?> value="Yes">Yes</option>
                <option <?= $earning[$slug] && $earning[$slug] == "No" ? "selected" : ""; ?> value="No">No</option>
            </select>
        </div>
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                Social Security Employee
            </label>
            <select name="<?= $slug =  stringToSlug("Social Security Employee", "_"); ?>" class="form-control">
                <option <?= $earning[$slug] && $earning[$slug] == "0" ? "selected" : ""; ?> value="0"></option>
                <option <?= $earning[$slug] && $earning[$slug] == "Yes" ? "selected" : ""; ?> value="Yes">Yes</option>
                <option <?= $earning[$slug] && $earning[$slug] == "No" ? "selected" : ""; ?> value="No">No</option>
            </select>
        </div>
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                Medicare Company
            </label>
            <select name="<?= $slug =  stringToSlug("Medicare Company", "_"); ?>" class="form-control">
                <option <?= $earning[$slug] && $earning[$slug] == "0" ? "selected" : ""; ?> value="0"></option>
                <option <?= $earning[$slug] && $earning[$slug] == "Yes" ? "selected" : ""; ?> value="Yes">Yes</option>
                <option <?= $earning[$slug] && $earning[$slug] == "No" ? "selected" : ""; ?> value="No">No</option>
            </select>
        </div>
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                Medicare Employee
            </label>
            <select name="<?= $slug =  stringToSlug("Medicare Employee", "_"); ?>" class="form-control">
                <option <?= $earning[$slug] && $earning[$slug] == "0" ? "selected" : ""; ?> value="0"></option>
                <option <?= $earning[$slug] && $earning[$slug] == "Yes" ? "selected" : ""; ?> value="Yes">Yes</option>
                <option <?= $earning[$slug] && $earning[$slug] == "No" ? "selected" : ""; ?> value="No">No</option>
            </select>
        </div>
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                Federal Unemployment Insurance
            </label>
            <select name="<?= $slug =  stringToSlug("Federal Unemployment Insurance", "_"); ?>" class="form-control">
                <option <?= $earning[$slug] && $earning[$slug] == "0" ? "selected" : ""; ?> value="0"></option>
                <option <?= $earning[$slug] && $earning[$slug] == "Yes" ? "selected" : ""; ?> value="Yes">Yes</option>
                <option <?= $earning[$slug] && $earning[$slug] == "No" ? "selected" : ""; ?> value="No">No</option>
            </select>
        </div>
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                MN Income Tax
            </label>
            <select name="<?= $slug =  stringToSlug("MN Income Tax", "_"); ?>" class="form-control">
                <option <?= $earning[$slug] && $earning[$slug] == "0" ? "selected" : ""; ?> value="0"></option>
                <option <?= $earning[$slug] && $earning[$slug] == "Yes" ? "selected" : ""; ?> value="Yes">Yes</option>
                <option <?= $earning[$slug] && $earning[$slug] == "No" ? "selected" : ""; ?> value="No">No</option>
            </select>
        </div>
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                MN Income Tax Additional
            </label>
            <select name="<?= $slug =  stringToSlug("MN Income Tax Additional", "_"); ?>" class="form-control">
                <option <?= $earning[$slug] && $earning[$slug] == "0" ? "selected" : ""; ?> value="0"></option>
                <option <?= $earning[$slug] && $earning[$slug] == "Yes" ? "selected" : ""; ?> value="Yes">Yes</option>
                <option <?= $earning[$slug] && $earning[$slug] == "No" ? "selected" : ""; ?> value="No">No</option>
            </select>
        </div>
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                MN Income Tax Fixed Rate
            </label>
            <select name="<?= $slug =  stringToSlug("MN Income Tax Fixed Rate", "_"); ?>" class="form-control">
                <option <?= $earning[$slug] && $earning[$slug] == "0" ? "selected" : ""; ?> value="0"></option>
                <option <?= $earning[$slug] && $earning[$slug] == "Yes" ? "selected" : ""; ?> value="Yes">Yes</option>
                <option <?= $earning[$slug] && $earning[$slug] == "No" ? "selected" : ""; ?> value="No">No</option>
            </select>
        </div>
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                MN Unemployment Insurance
            </label>
            <select name="<?= $slug =  stringToSlug("MN Unemployment Insurance", "_"); ?>" class="form-control">
                <option <?= $earning[$slug] && $earning[$slug] == "0" ? "selected" : ""; ?> value="0"></option>
                <option <?= $earning[$slug] && $earning[$slug] == "Yes" ? "selected" : ""; ?> value="Yes">Yes</option>
                <option <?= $earning[$slug] && $earning[$slug] == "No" ? "selected" : ""; ?> value="No">No</option>
            </select>
        </div>
        <!--  -->
        <div class="form-group">
            <label class="csF16">
                MN Workforce Dev Assessment
            </label>
            <select name="<?= $slug =  stringToSlug("MN Workforce Dev Assessment", "_"); ?>" class="form-control">
                <option <?= $earning[$slug] && $earning[$slug] == "0" ? "selected" : ""; ?> value="0"></option>
                <option <?= $earning[$slug] && $earning[$slug] == "Yes" ? "selected" : ""; ?> value="Yes">Yes</option>
                <option <?= $earning[$slug] && $earning[$slug] == "No" ? "selected" : ""; ?> value="No">No</option>
            </select>
        </div>
        <hr />
        <!--  -->
        <div class="form-group text-right">
            <button class="btn csW csBG3 csRadius5 jsEditBtn">
                <i class="fa fa-save" aria-hidden="true"></i>
                &nbsp;Update
            </button>
        </div>
    </form>
</div>