<!--  -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/css/jquery-ui-datepicker-custom.css">
<script src="<?php echo base_url('assets') ?>/js/jquery.datetimepicker.js"></script>
<div class="container-fluid">
    <div class="csPageWrap">
        <div class="row">
            <!-- left sidebar -->
            <?php $this->load->view('payroll/pages/sidebar', ['mainIndex'=> "employee_profile", "subIndex" =>""]);?>
            <!-- Main Content -->
            <div class="col-md-9 col-sm-12">
                <!-- Body -->
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="csF18 csB7">
                            Personal details
                        </h1>
                        <p class="csF16">
                            This information will be used for payroll and taxes, so double-check that it's accurate.
                        </p>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-12">
                        <p class="csF16">
                            Fields marked with asterisk (<span class="csRequired"></span>) are mandatory.
                        </p>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            First Name <span class="csRequired"></span>
                        </label>
                        <input type="text" class="form-control jsFirstName" value="<?=!empty($employee_info) ? $employee_info['first_name'] : '';?>" placeholder="John" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Middle Name / Initial 
                        </label>
                        <input type="text" class="form-control jsMiddleName" value="<?=!empty($employee_info) ? $employee_info['middle_name'] : '';?>" placeholder="B" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Last Name <span class="csRequired"></span>
                        </label>
                        <input type="text" class="form-control jsLastName" value="<?=!empty($employee_info) ? $employee_info['last_name'] : '';?>" placeholder="Doe" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Date Of Birth <span class="csRequired"></span>
                        </label>
                        <input type="text" class="form-control jsEDOB jsDOBPicker" value="<?=!empty($employee_info) ? date('m/d/Y',strtotime($employee_info['dob'])) : '';?>" readonly placeholder="MM/DD/YYYY" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Work Address
                        </label>
                        <select class="form-control jsEWD">
                            <?php foreach($locations as $location): ?>
                                <option value="<?=$location['gusto_location_id'];?>" <?php echo $workAddressId == $location['gusto_location_id'] ? 'selected="selected"' : ''; ?>><?php echo $location['street_1'].', '.$location['city'].', '.$location['state'].' - '.$location['zip']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Start Date
                        </label>
                        <input type="text" class="form-control jsStartDate jsDatePicker" value="<?=isset($employee_info) ?  formatDate(GetHireDate($employee_info['rehire_date'], $employee_info['joined_at'], $employee_info['registration_date']), 'Y-m-d', 'm/d/Y') : ''; ?>" placeholder="MM/DD/YYYY" readonly />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Email <span class="csRequired"></span>
                        </label>
                        <input type="text" class="form-control jsEmail" value="<?=!empty($employee_info) ? $employee_info['email'] : '';?>" placeholder="We may use this email address to send them critical information about payroll" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Social Security Number <span class="csRequired"></span>
                        </label>
                        <input type="text" class="form-control jsEmployeeSSN" value="<?=!empty($employee_info) ? $employee_info['ssn'] : '';?>" placeholder="123456789" />
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
                            <span id="jsSaveBtnTxt">Save & Continue</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
