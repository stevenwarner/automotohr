<!--  -->
<div class="container-fluid">
    <div class="csPageWrap">
        <div class="row">
            <!-- left sidebar -->
            <?php $this->load->view('payroll/pages/sidebar', ['mainIndex'=> "employee_state_tax", "subIndex" =>""]);?>
            <!-- Main Content -->
            <div class="col-md-9 col-sm-12">
                <!-- Heading -->
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="csF18 csB7">
                            State tax information
                        </h1>
                    </div>
                </div>
                <!-- Body -->
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="csF18 csB7" id="JSStateName">
                            
                        </h1>
                        <p>
                        The information below is used to determine the state tax rate for Maya.
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
                <div id="JSQusetionSection">
                    <!-- <br>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <label class="csF16 csB7">
                                Filing Status
                            </label>
                            <p class="csF16">
                                The Head of Household status applies to unmarried individuals who have a relative living with them in their home. If unsure, read the <a href="https://www.ftb.ca.gov/file/personal/filing-status/index.html" target="_blank">CA Filing Status explanation</a>.
                            </p>
                            <select class="form-control jsFilingStatus">
                                <option value="0">Select…</option>
                                <option value="S" <?=!empty($state_tax_info) &&  $state_tax_info['filing_status'] === "S" ? 'selected="selected"' : '';?>>Single or married filing separately</option>
                                <option value="M" <?=!empty($state_tax_info) &&  $state_tax_info['filing_status'] === "M" ? 'selected="selected"' : '';?>>Married filing jointly</option>
                                <option value="MD" <?=!empty($state_tax_info) &&  $state_tax_info['filing_status'] === "MD" ? 'selected="selected"' : '';?>>Head of household</option>
                                <option value="H" <?=!empty($state_tax_info) &&  $state_tax_info['filing_status'] === "H" ? 'selected="selected"' : '';?>>Exempt from withholding</option>
                                <option value="E" <?=!empty($state_tax_info) &&  $state_tax_info['filing_status'] === "E" ? 'selected="selected"' : '';?>>Exempt from withholding</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <label class="csF16 csB7">
                            Withholding Allowance <span class="csRequired"></span>
                            </label>
                            <p class="csF16">
                                This value is needed to calculate the employee's CA income tax withholding. If unsure, use the <a href="http://www.edd.ca.gov/pdf_pub_ctr/de4.pdf">CA DE-4 form</a> to calculate the value manually.
                            </p>
                            <input type="text" class="form-control jsWithholdingAllowance" placeholder="0.00" value="<?=!empty($state_tax_info)  ? $state_tax_info['withholding_allowance'] : '';?>">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <label class="csF16 csB7">
                                Additional Withholding
                            </label>
                            <p class="csF16">
                                You can withhold an additional amount of California income taxes here.
                            </p>
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="text" class="form-control jsAdditionalWithholding" placeholder="0.00" value="<?=!empty($state_tax_info)  ? $state_tax_info['additional_withholding'] : '';?>">
                            </div>
                        </div>
                    </div>
                    <br> -->
                </div>    
                <div class="row">
                    <div class="col-sm-12 text-right">
                        <button class="btn btn-black csF16 csB7 jsPayrollEmployeeOnboard" data-employee_id="<?php echo $employee_sid; ?>" data-level="3">
                            <i class="fa fa-arrow-circle-o-left" aria-hidden="true"></i>&nbsp;
                            Back
                        </button>
                        <button class="btn btn-orange csF16 csB7 jsPayrollSaveEmployeeStateTax">
                            <i class="fa fa-save" aria-hidden="true"></i>&nbsp;
                            <span id="jsSaveBtnTxt">Save & continue</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
