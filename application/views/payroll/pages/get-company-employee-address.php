<!--  -->
<div class="container-fluid">
    <div class="csPageWrap">
        <div class="row">
            <!-- left sidebar -->
            <?php $this->load->view('payroll/pages/sidebar', ['mainIndex'=> "employee_address", "subIndex" =>""]);?>
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
                            Home address
                        </h1>
                        <p class="csF16">
                            Employee’s home mailing address, within the United States.
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
                            Street 1 <span class="csRequired"></span>
                        </label>
                        <input type="text" class="form-control jsStreet1" value="<?=!empty($employee_address_info) ? $employee_address_info['Location_Address'] : '';?>" placeholder="e.g. 425 2nd Street" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Street 2
                        </label>
                        <input type="text" class="form-control jsStreet2" value="<?=!empty($employee_address_info["Location_Address_2"])  ? $employee_address_info['Location_Address_2'] : '';?>" placeholder="e.g. 425 2nd Street" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            City <span class="csRequired"></span>
                        </label>
                        <input type="email" class="form-control jsCity"
                            value="<?=!empty($employee_address_info) ? $employee_address_info['Location_City'] : '';?>" placeholder="e.g. San Francisco" />
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
                                <option value="<?=$state['state_code'];?>" <?=!empty($employee_address_info) &&  $employee_address_info['Location_State'] === $state['state_code'] ? 'selected="selected"' : '';?>><?=$state['state_name'];?></option>
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
                        <input type="email" class="form-control jsZip" value="<?=!empty($employee_address_info) ? $employee_address_info['Location_ZipCode'] : '';?>" placeholder="e.g. 94107" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Phone
                        </label>
                        <input type="email" class="form-control jsPhoneNumber" value="<?=!empty($employee_address_info) ? $employee_address_info['PhoneNumber'] : '';?>" placeholder="e.g. 8009360383" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-12 text-right">
                        <button class="btn btn-black csF16 csB7 jsPayrollEmployeeOnboard" data-employee_id="<?php echo $employee_sid; ?>" data-level="0">
                            <i class="fa fa-arrow-circle-o-left" aria-hidden="true"></i>&nbsp;
                            Back
                        </button>
                        <button class="btn btn-orange csF16 csB7 jsPayrollSaveEmployeeAddressInfo">
                            <i class="fa fa-save" aria-hidden="true"></i>&nbsp;
                            <span id="jsSaveBtnTxt">Save & continue</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
