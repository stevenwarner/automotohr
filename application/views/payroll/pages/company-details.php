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
                            Company Address
                        </h1>
                    </div>
                </div>
                <!-- Body -->
                <div class="row">
                    <div class="col-sm-12">
                        <p class="csF16">
                            To automate your payroll filings, we need to have your company's accurate addresses. Please enter your mailing and filing addresses and all addresses where you have employees physically working in the United States.
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
                            Street 1 <span class="csRequired"></span>
                        </label>
                        <input type="text" class="form-control jsStreet1" value="<?=!empty($location) ? $location['street_1'] : '';?>" placeholder="e.g. 425 2nd Street" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Street 2
                        </label>
                        <input type="text" class="form-control jsStreet2" value="<?=!empty($location) ? $location['street_2'] : '';?>" placeholder="" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            City <span class="csRequired"></span>
                        </label>
                        <input type="email" class="form-control jsCity"
                            value="<?=!empty($location) ? $location['city'] : '';?>" placeholder="e.g. San Francisco" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            State <span class="csRequired"></span>
                        </label>
                        <select class="form-control jsState">
                            <?php foreach($states as $state): ?>
                                <option value="<?=$state['state_code'];?>" <?=!empty($location) &&  $location['state'] === $state['state_code'] ? 'selected="selected"' : '';?>><?=$state['state_name'];?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Zip <span class="csRequired"></span>
                        </label>
                        <input type="email" class="form-control jsZip" value="<?=!empty($location) ? $location['zip'] : '';?>" placeholder="e.g. 94107" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Phone <span class="csRequired"></span>
                        </label>
                        <input type="email" class="form-control jsPhoneNumber" value="<?=!empty($location) ? $location['phone_number'] : '';?>" placeholder="e.g. 8009360383" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Select address type(s)
                        </label> <br>
                        <label class="control control--checkbox">
                            <input type="checkbox" class="jsMailingAddress" <?=!empty($location) && $location['mailing_address'] == 1 ? 'checked' : ''; ?> /> Mailing address
                            <div class="control__indicator"></div>
                        </label> <br>
                        <label class="control control--checkbox">
                            <input type="checkbox" class="jsFilingAddress" <?=!empty($location) && $location['filing_address'] == 1 ? 'checked' : ''; ?> /> Filing address
                            <div class="control__indicator"></div>
                        </label>
                        <p class="csF14">We will need to collect and add any employee's physical working address in the US including remote employees and employees who work from home.</p>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-12 text-right">
                        <button class="btn btn-black csF16 csB7 jsPayrollCancel">
                            <i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;
                            Cancel
                        </button>
                        <button class="btn btn-orange csF16 csB7 jsPayrollSaveCompanyLocation">
                            <i class="fa fa-save" aria-hidden="true"></i>&nbsp;
                            <span id="jsSaveBtnTxt">Save & continue</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
