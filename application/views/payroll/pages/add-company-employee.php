<!--  -->
<div class="container-fluid">
    <div class="csPageWrap">
        <div class="row">
            <!-- left sidebar -->
            <?php $this->load->view('payroll/pages/sidebar', ['mainIndex'=> "company", "subIndex" =>"company_address"]);?>
            <!-- Main Content -->
            <div class="col-md-9 col-sm-12">
                <!-- Heading -->
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="csF18 csB7">
                            Employee profile
                        </h1>
                    </div>
                </div>
                <!-- Body -->
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="csF18 csB7">
                            Personal details
                        </h1>
                        <p class="csF16">
                            This information will be used for payroll, taxes, and benefits, so double-check that it's accurate.
                        </p>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-12">
                        <p class="csF16">
                            Fields marked with asterisk (<span class="csRequired"></span>) are mendatory.
                        </p>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            First name <span class="csRequired"></span>
                        </label>
                        <input type="text" class="form-control jsFirstName" value="" placeholder="Enter first name" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Middle name <span class="csRequired"></span>
                        </label>
                        <input type="text" class="form-control jsMiddleName" value="" placeholder="Enter middle name" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Last name <span class="csRequired"></span>
                        </label>
                        <input type="text" class="form-control jsLastName" value="" placeholder="Enter last name" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Start date
                        </label>
                        <input type="text" class="form-control jsStartDate" value="" placeholder="" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Work address <span class="csRequired"></span>
                        </label>
                        <input type="email" class="form-control jsCity"
                            value="<?=!empty($location) ? $location['city'] : '';?>" placeholder="e.g. San Francisco" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Personal email address
                        </label>
                        <input type="text" class="form-control jsEmail" value="" placeholder="We may use this email address to send them critical information about payroll" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Employee's Social Security number <span class="csRequired"></span>
                        </label>
                        <input type="text" class="form-control jsEmployeeSSN" value="" placeholder="We may use this email address to send them critical information about payroll" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Birthday
                        </label>
                        <input type="text" class="form-control jsDOB" value="" placeholder="" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-12 text-right">
                    <button class="btn btn-black csF16 csB7 jsAddEmployeeCancel">
                            <i class="fa fa-arrow-circle-o-left" aria-hidden="true"></i>&nbsp;
                            Back
                        </button>
                        <button class="btn btn-orange csF16 csB7 jsPayrollSaveCompanyEmployee">
                            <i class="fa fa-save" aria-hidden="true"></i>&nbsp;
                            <span id="jsSaveBtnTxt">Save & continue</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
