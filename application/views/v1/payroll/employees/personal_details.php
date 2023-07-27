<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="csW" style="margin-top: 0; margin-bottom: 0">
            <strong> Personal details</strong>
        </h3>
    </div>
    <div class="panel-body">
        <h4 style="margin-top: 0">
            <strong>Employee personal details</strong>
        </h4>
        <p class="text-danger csF16">
            <strong>
                <em>
                    This information will be used for payroll and taxes, so double-check that it's accurate.
                </em>
            </strong>
        </p>

        <br>
        <form action="javascript:void(0)">
            <!--  -->
            <div class="form-group">
                <label class="csF16">First name <strong class="text-danger">*</strong></label>
                <input type="text" class="form-control jsEmployeeFlowFirstName" placeholder="John" />
            </div>

            <!--  -->
            <div class="form-group">
                <label class="csF16">Middle initial</label>
                <input type="text" class="form-control jsEmployeeFlowMiddleInitial" placeholder="M" />
            </div>

            <!--  -->
            <div class="form-group">
                <label class="csF16">Last name <strong class="text-danger">*</strong></label>
                <input type="text" class="form-control jsEmployeeFlowLastName" placeholder="Doe" />
            </div>

            <!--  -->
            <div class="form-group">
                <label class="csF16">Work address <strong class="text-danger">*</strong></label>
                <select class="form-control jsEmployeeFlowWorkAddress"></select>
            </div>

            <!--  -->
            <div class="form-group">
                <label class="csF16">Start date <strong class="text-danger">*</strong></label>
                <input type="text" class="form-control jsEmployeeFlowStartDate" readonly placeholder="MM/DD/YYYY" />
            </div>

            <!--  -->
            <div class="form-group">
                <label class="csF16">Email <strong class="text-danger">*</strong></label>
                <input type="email" class="form-control jsEmployeeFlowEmail" placeholder="john.doe@automotohr.com" />
            </div>

            <!--  -->
            <div class="form-group">
                <label class="csF16">Social security Number (SSN) <strong class="text-danger">*</strong></label>
                <input type="text" class="form-control jsEmployeeFlowSSN" placeholder="___-__-____" />
            </div>

            <!--  -->
            <div class="form-group">
                <label class="csF16">Date of birth <strong class="text-danger">*</strong></label>
                <input type="text" class="form-control jsEmployeeFlowDateOfBirth" readonly placeholder="MM/DD/YYYY" />
            </div>

        </form>
    </div>
    <div class="panel-footer text-right">
        <button class="btn csBG3 csF16 jsEmployeeFlowSavePersonalDetailsBtn">
            <i class="fa fa-save csF16"></i>
            <span>Save & continue</span>
        </button>
    </div>
</div>