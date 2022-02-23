<!-- Basic Information Panel -->
<br>
<div class="panel panel-success">
    <div class="panel-heading">
        <h1 class="csF16 csB7 csW m0">
            Bank Account(s) 
            <span class="pull-right">
                Accounts Found: <span class="csCounter">0</span>
            </span>
        </h1>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12 col-xs-12 text-right">
                <button class="btn btn-success csF16 csB7 jsEAddBankAccount">
                    <i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;Add A Bank Account
                </button>
            </div>
        </div>
        <!--  -->
        <div class="table-responsive">
            <table class="table table-striped">
                <caption></caption>
                <thead>
                    <tr>
                        <th scope="col" class="csBG1 csF16 csB7 csW">Name</th>
                        <th scope="col" class="csBG1 csF16 csB7 csW text-right">Routing Number</th>
                        <th scope="col" class="csBG1 csF16 csB7 csW text-right">Account Number</th>
                        <th scope="col" class="csBG1 csF16 csB7 csW text-right">Account Type</th>
                        <th scope="col" class="csBG1 csF16 csB7 csW text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="jsDataBody"></tbody>
            </table>
        </div>
    </div>
    <div class="panel-footer text-right">
        <a href="<?=base_url("employee/add/".($employeeId)."?section=jobs");?>" class="btn btn-success csF16 csB7 jsEBankAccount">
            <i class="fa fa-save" aria-hidden="true"></i>&nbsp;Next
        </a>
    </div>
</div>