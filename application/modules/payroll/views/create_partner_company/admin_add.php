<!--  -->
<div class="container">
    <div class="">
        <!-- Heading -->
        <div class="row">
            <div class="col-sm-12">
                <h1 class="text-large ">
                    Set Payroll Admin
                </h1>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <h1 class="text-medium">
                    The person who will manage and run the payroll for this store.
                </h1>
            </div>
        </div>

        <!-- Body -->
        <div class="row">
            <div class="col-sm-12">
                <p class="text-medium">
                    Fields marked with asterisk (<span class="csRequired"></span>) are mandatory.
                </p>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-6 col-xs-12">
                <label class="text-medium csB7">
                    Select an employee
                </label>
                <select class="form-control" id="jsEmployeeChoose">
                    <option value="0"></option>
                    <?php foreach ($employees as $value) { ?>
                        <option value="<?= $value['userId']; ?>"><?= remakeEmployeeName($value); ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <hr>
        <form autocomplete="off">
            <div class="row">
                <div class="col-md-6 col-xs-12">
                    <label class="text-medium csB7">
                        First name <span class="csRequired"></span>
                    </label>
                    <input type="text" class="form-control jsAdminFirstName" placeholder="e.g. John" autocomplete="off" />
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-6 col-xs-12">
                    <label class="text-medium csB7">
                        Last name <span class="csRequired"></span>
                    </label>
                    <input type="text" class="form-control jsAdminLastName" placeholder="e.g. Doe" autocomplete="off" />
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-6 col-xs-12">
                    <label class="text-medium csB7">
                        Email <span class="csRequired"></span>
                    </label>
                    <input type="email" class="form-control jsAdminEmailAddress" placeholder="e.g. john.doe@example.com" autocomplete="off" />
                </div>
            </div>
        </form>
        <br>
        <input type="hidden" class="jsAdminUserId" value="0" />
        <div class="row">
            <div class="col-sm-12">
                <button class="btn btn-black jsBackToStep3">
                    <i class="fa fa-long-arrow-left" aria-hidden="true"></i>&nbsp;
                    Back
                </button>
                <button class="btn btn-orange jsPayrollSaveAdmin">
                    <i class="fa fa-save" aria-hidden="true"></i>&nbsp;
                    Save
                </button>
            </div>
        </div>
    </div>
</div>