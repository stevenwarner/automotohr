<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="text-medium panel-heading-text">
                <i class="fa fa-plus-circle text-orange"></i>
                Add Company Bank Account
            </h3>
        </div>
        <form action="javascript:void(0)" id="jsCompanyBankAccountForm">
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="text-medium" for="jsBankName">
                                Name
                                <strong class="text-danger">*</strong>
                            </label>
                            <input type="text" class="form-control" name="jsBankName" id="jsBankName" />
                        </div>
                        <div class="form-group">
                            <label class="text-medium" for="jsRoutingNumber">
                                Routing Number (9 digits)
                                <strong class="text-danger">*</strong>
                            </label>
                            <input type="number" class="form-control" name="jsRoutingNumber" id="jsRoutingNumber" />
                        </div>
                        <!--  -->
                        <div class="form-group">
                            <label class="text-medium" for="jsAccountNumber">
                                Account Number
                                <strong class="text-danger">*</strong>
                            </label>
                            <input type="number" class="form-control" name="jsAccountNumber" id="jsAccountNumber" />
                        </div>
                        <!--  -->
                        <div class="form-group">
                            <label class="text-medium" for="jsAccountType">
                                Account Type
                                <strong class="text-danger">*</strong>
                            </label>
                            <select class="form-control" name="jsAccountType" id="jsAccountType">
                                <option value=""></option>
                                <option value="Checking">Checking</option>
                                <option value="Savings">Savings</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 text-center">
                        <img src="<?= base_url("images/check_bank_account.png"); ?>" alt="Check" style="max-width: 100%" />
                    </div>
                </div>
            </div>
            <div class="panel-footer text-right">
                <button class="btn btn-black jsModalCancel" type="button">
                    <i class="fa fa-times-circle"></i>
                    Cancel
                </button>
                <button class="btn btn-orange jsCompanyBankAccountFormBtn" type="submit">
                    <i class="fa fa-save"></i>
                    Save
                </button>
            </div>
        </form>
    </div>
</div>