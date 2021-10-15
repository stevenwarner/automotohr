<!--  -->
<div class="container-fluid">
    <div class="csPageWrap">
        <div class="row">
            <!-- left sidebar -->
            <?php $this->load->view('payroll/pages/sidebar', ['mainIndex'=> "company", "subIndex" =>"federal_tax_info"]);?>
            <!-- Main Content -->
            <div class="col-md-9 col-sm-12">
                <!-- Heading -->
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="csF18 csB7">
                            Federal tax information
                        </h1>
                    </div>
                </div>
                <!-- Body -->
                <div class="row">
                    <div class="col-sm-12">
                        <p class="csF16">
                            Enter your entity and the legal name of your company. You can find this info on your FEIN assignment form (Form CP575). We need this to file and pay your taxes correctly.
                        </p>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-12">
                        <p class="csF16">
                            Fields marked with an asterisk (<span class="csRequired"></span>) are mandatory.
                        </p>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Federal EIN <span class="csRequired"></span>
                        </label>
                        <p class="csF14">Your Company's Federal Employer identification number(EIN). If you do not have one, please apply online.</p>
                        <input type="text" class="form-control jsTaxEIN" placeholder="__-_________"  value="<?=!empty($taxInfo) ? $taxInfo['ein_number'] : '';?>"/>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Company Type <span class="csRequired"></span>
                        </label>
                        <p class="csF14 ">Some common types are Solo Prop, LLC, S-Corp.</p>
                        <select class="form-control jsTaxPayerType">
                            <option value="0">[Select]</option>
                            <option value="S-Corporation" <?=!empty($taxInfo) &&  $taxInfo['tax_payer_type'] === "S-Corporation" ? 'selected="selected"' : '';?>>S-Corporation</option>
                            <option value="C-Corporation" <?=!empty($taxInfo) &&  $taxInfo['tax_payer_type'] === "C-Corporation" ? 'selected="selected"' : '';?>>C-Corporation</option>
                            <option value="Sole proprietor" <?=!empty($taxInfo) &&  $taxInfo['tax_payer_type'] === "Sole proprietor" ? 'selected="selected"' : '';?>>Sole Proprietor</option>
                            <option value="LLC" <?=!empty($taxInfo) &&  $taxInfo['tax_payer_type'] === "LLC" ? 'selected="selected"' : '';?>>LLC</option>
                            <option value="LLP" <?=!empty($taxInfo) &&  $taxInfo['tax_payer_type'] === "LLP" ? 'selected="selected"' : '';?>>LLP</option>
                            <option value="Limited partnership" <?=!empty($taxInfo) &&  $taxInfo['tax_payer_type'] === "Limited partnership" ? 'selected="selected"' : '';?>>Limited Partnership</option>
                            <option value="Co-ownership" <?=!empty($taxInfo) &&  $taxInfo['tax_payer_type'] === "Co-ownership" ? 'selected="selected"' : '';?>>Co-ownership</option>
                            <option value="Association" <?=!empty($taxInfo) &&  $taxInfo['tax_payer_type'] === "Association" ? 'selected="selected"' : '';?>>Association</option>
                            <option value="Trusteeship" <?=!empty($taxInfo) &&  $taxInfo['tax_payer_type'] === "Trusteeship" ? 'selected="selected"' : '';?>>Trusteeship</option>
                            <option value="General partnership" <?=!empty($taxInfo) &&  $taxInfo['tax_payer_type'] === "General partnership" ? 'selected="selected"' : '';?>>General Partnership</option>
                            <option value="Joint venture" <?=!empty($taxInfo) &&  $taxInfo['tax_payer_type'] === "Joint venture" ? 'selected="selected"' : '';?>>Joint Venture</option>
                            <option value="Non-Profit" <?=!empty($taxInfo) &&  $taxInfo['tax_payer_type'] === "Non-Profit" ? 'selected="selected"' : '';?>>Non-Profit</option>
                        </select>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Federal filing form <span class="csRequired"></span>
                        </label>
                        <p class="csF14">Choose the filing form assigned to you by the IRS.</p>
                        <select class="form-control jsTaxFillingForm">
                            <option value="0">[Select]</option>
                            <option value="941" <?=!empty($taxInfo) &&  $taxInfo['filling_form'] === "941" ? 'selected="selected"' : '';?>>941 (Quarterly federal tax return)</option>
                            <option value="944" <?=!empty($taxInfo) &&  $taxInfo['filling_form'] === "944" ? 'selected="selected"' : '';?>>944 (Annual federal tax return)</option>
                        </select>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Legal entity name <span class="csRequired"></span>
                        </label>
                        <p class="csF14">Make sure this is your legal name, not your DBA.</p>
                        <input type="text" class="form-control jsTaxLegalName" placeholder="eg: Turn Around Industries" value="<?=!empty($taxInfo) ? $taxInfo['legal_name'] : '';?>" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="control control--checkbox">
                            <input type="checkbox" class="jsTaxableAsScorp" <?=!empty($taxInfo) && $taxInfo['taxable_as_scorp'] == 1 ? 'checked' : ''; ?> /> Taxable S-Corporation
                            <div class="control__indicator"></div>
                        </label>
                        <p class="csF14">Whether this company should be taxed as an S-Corporation.</p>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-12 text-right">
                        <button class="btn btn-black csF16 csB7 jsFederalTaxCancel">
                            <i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;
                            Cancel
                        </button>
                        <button class="btn btn-orange csF16 csB7 jsFederalTaxUpdate">
                            <i class="fa fa-save" aria-hidden="true"></i>&nbsp;
                            <span id="jsSaveBtnTxt">Save & continue</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
